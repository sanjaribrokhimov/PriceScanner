from flask import Blueprint, request, jsonify
from flask_jwt_extended import create_access_token, jwt_required, get_jwt_identity
from app.models.user import User, db
from app.models.otp import OTP
from app.utils import generate_otp, send_otp_via_email
from datetime import datetime, timedelta
from werkzeug.security import generate_password_hash
from app.models.user import UserVisit
from sqlalchemy.exc import SQLAlchemyError

auth_bp = Blueprint('auth', __name__, url_prefix='/api/auth')

@auth_bp.route('/register', methods=['POST'])
def register():
    data = request.get_json()
    email = data.get('email')
    password = data.get('password')
    
    if not email or not password:
        return jsonify({'message': 'Email and password are required'}), 400

    user = User.query.filter_by(email=email).first()
    if user:
        return jsonify({'message': 'User already exists'}), 400    

    otp = generate_otp()
    expiration_time = datetime.utcnow() + timedelta(minutes=5)  # OTP valid for 5 minutes
    existing_otp = OTP.query.filter_by(email=email).first()

    if existing_otp:
        existing_otp.otp = otp
        existing_otp.expiration_time = expiration_time
    else:
        new_otp = OTP(email, otp, expiration_time)
        db.session.add(new_otp)

    db.session.commit()
    send_otp_via_email(otp, email)
    return jsonify({'message': 'OTP sent to your email'}), 200

@auth_bp.route('/verify', methods=['POST'])
def verify():
    data = request.json
    email = data.get('email')
    first_name = data.get('first_name')
    last_name = data.get('last_name')
    email = data.get('email')
    phone_number = data.get('phone_number')
    city = data.get('city')
    password = data.get('password')
    otp = data.get('otp')

    if not email or not otp:
        return jsonify({'error': 'Email and OTP are required'}), 400

    otp_record = OTP.query.filter_by(email=email).first()
    if otp_record and otp_record.otp == otp:
        if datetime.utcnow() > otp_record.expiration_time:
            return jsonify({'error': 'OTP has expired'}), 400

        new_user = User(first_name=first_name, last_name=last_name, email=email, phone_number=phone_number, city=city)
        new_user.set_password(password)
        db.session.add(new_user)
        db.session.delete(otp_record)
        db.session.commit()
        return jsonify({'message': 'OTP verified. Registration complete.'}), 200
    else:
        return jsonify({'error': 'Invalid OTP'}), 400

@auth_bp.route('/login', methods=['POST'])
def login():
    data = request.get_json()
    email = data.get('email')
    password = data.get('password')

    if not email or not password:
        return jsonify({'message': 'Username and password are required'}), 400

    user = User.query.filter_by(email=email).first()
    if not user or not user.check_password(password):
        return jsonify({'message': 'Invalid credentials'}), 401
    
    
    expires = timedelta(days=1)
    access_token = create_access_token(identity=user.id, expires_delta=expires)
    return jsonify({
        'access_token': access_token, 
        'user_id': user.id,
        'first_name': user.first_name,
        'last_name': user.last_name,
        'email': user.email,
        'phone_number': user.phone_number,
        'city': user.city,
        'role': user.role,
        'company_id': user.company_id,
        'category': user.category,
    }), 200

@auth_bp.route('/check-health', methods=['GET', 'POST'])
@jwt_required()
def protected():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    if user:
        return jsonify({
            'message': True,
            'first_name': user.first_name, 
            'last_name': user.last_name, 
            'email': user.email, 
            }), 200
    return jsonify({'message': False}), 403
    
@auth_bp.route('/user/update', methods=['POST'])
@jwt_required()
def user_update():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    data = request.get_json()

    user.first_name = data.get('first_name', user.first_name)
    user.last_name = data.get('last_name', user.last_name)
    user.phone_number = data.get('phone_number', user.phone_number)

    new_password = data.get('password')
    if new_password:
        user.password_hash = generate_password_hash(new_password)
    
    db.session.commit()

    return jsonify({'message': f'User {user.first_name} updated successfully'}), 200



@auth_bp.route('/reset_password', methods=['POST'])
def reset_password():
    data = request.get_json()
    email = data.get('email')
    
    if not email:
        return jsonify({'message': 'Email and password are required'}), 400

    user = User.query.filter_by(email=email).first()
    if not user:
        return jsonify({'message': 'User with this email doesn\'t exist'}), 400    

    otp = generate_otp()
    expiration_time = datetime.utcnow() + timedelta(minutes=5)  
    existing_otp = OTP.query.filter_by(email=email).first()

    if existing_otp:
        existing_otp.otp = otp
        existing_otp.expiration_time = expiration_time
    else:
        new_otp = OTP(email, otp, expiration_time)
        db.session.add(new_otp)

    db.session.commit()
    send_otp_via_email(otp, email)
    return jsonify({'message': 'OTP sent to your email'}), 200

@auth_bp.route('/verify_pr', methods=['POST'])
def verify_pr():
    data = request.json
    email = data.get('email')
    new_password = data.get('password')
    otp = data.get('otp')
    user = User.query.filter_by(email=email).first()

    if not email or not otp:
        return jsonify({'error': 'Email and OTP are required'}), 400

    otp_record = OTP.query.filter_by(email=email).first()
    if otp_record and otp_record.otp == otp:
        if datetime.utcnow() > otp_record.expiration_time:
            return jsonify({'error': 'OTP has expired'}), 400

        user.password_hash = generate_password_hash(new_password)
        db.session.delete(otp_record)
        db.session.commit()
        return jsonify({'message': 'OTP verified. Password change complete.'}), 200
    else:
        return jsonify({'error': 'Invalid OTP'}), 400
    
@auth_bp.route('/visit', methods=['POST'])
@jwt_required()
def track_visit():
    """
    Tracks a user visit and creates a new UserVisit record.

    Returns:
        - 201: If the visit is tracked successfully, returns a JSON with a success message and the new visit ID.
        - 404: If the user is not found, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
      return jsonify({'message': 'User not found'}), 404
    try:
        new_visit = UserVisit(user_id=user.id)
        db.session.add(new_visit)
        db.session.commit()
        return jsonify({'message': 'Visit tracked successfully', 'visit_id': new_visit.id}), 201
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500
    
@auth_bp.route('/feedback-eligible', methods=['GET']) 
@jwt_required()
def check_feedback_eligibility():
    """
    Checks if a user is eligible to leave feedback based on their visit count.

    Returns:
        - 200: If the user is eligible, returns a JSON with a success message and eligibility status.
        - 404: If the user is not found, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
      return jsonify({'message': 'User not found'}), 404

    try:
        visit_count = UserVisit.query.filter_by(user_id=current_user_id).count()
        if visit_count >= 5: # Example: Show feedback popup after 5 visits
          return jsonify({'eligible': True}), 200
        else:
          return jsonify({'eligible': False}), 200
    except SQLAlchemyError as e:
        return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500

# @auth_bp.route('/set-false', methods=['PUT'])
# @jwt_required()
# def set_visit_count():
#     """
#     Sets the visit count for the current user to 100.

#     Returns:
#         - 200: If the visit count is set successfully, returns a JSON with a success message and the updated visit count.
#         - 404: If the user is not found, returns a JSON with an error message.
#         - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
#     """
#     current_user_id = get_jwt_identity()
#     user = User.query.get(current_user_id)

#     if not user:
#         return jsonify({'message': 'User not found'}), 404

#     try:
#         UserVisit.query.filter_by(user_id=user.id).delete()
#         for _ in range(100):
#             new_visit = UserVisit(user_id=user.id)
#             db.session.add(new_visit)
#         db.session.commit()
#         return jsonify({'message': 'set false successfully'}), 200
#     except SQLAlchemyError as e:
#         db.session.rollback()
#         return jsonify({'message': f'Database error: {str(e)}'}), 500
#     except Exception as e:
#         db.session.rollback()
#         return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500

@auth_bp.route('/set-false', methods=['PUT'])
@jwt_required()
def set_visit_count():
    """
    Resets the visit count for the current user to 0.

    Returns:
        - 200: If the visit count is reset successfully, returns a JSON with a success message.
        - 404: If the user is not found, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    try:
        UserVisit.query.filter_by(user_id=user.id).delete()
        db.session.commit()
        return jsonify({'message': 'Visit count reset to 0 successfully'}), 200
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500