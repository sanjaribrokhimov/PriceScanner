from flask import Blueprint, request, jsonify
from flask_jwt_extended import jwt_required, get_jwt_identity
from app.models.company import Company
from app.models.user import User
from app.models.tgPartnerChats import ChatIds
from app.models import db
import base64
from app.models.orders import RentCarOrder, TourOrder, HotelOrder

admin_bp = Blueprint('admin', __name__, url_prefix='/api/admin')

# rent car stat
@admin_bp.route('/rco/count_by_company/<int:company_id>', methods=['GET'])
@jwt_required()
def count_by_company(company_id):
    """Get the count of orders for a specific company."""
    count = db.session.query(RentCarOrder).filter_by(company_id=company_id).count()
    return jsonify({
        'company_id': company_id,
        'order_count': count
    })

@admin_bp.route('/rco/total_count', methods=['GET'])
@jwt_required()
def total_count():
    """Get the total count of orders across all companies."""
    count = db.session.query(RentCarOrder).count()
    return jsonify({
        'total_order_count': count
    })

# tour stat
@admin_bp.route('/tour/count_by_company/<int:company_id>', methods=['GET'])
@jwt_required()
def count_by_company_tour(company_id):
    """Get the count of orders for a specific company."""
    count = db.session.query(TourOrder).filter_by(company_id=company_id).count()
    return jsonify({
        'company_id': company_id,
        'order_count': count
    })

@admin_bp.route('/tour/total_count', methods=['GET'])
@jwt_required()
def total_count_tour():
    """Get the total count of orders across all companies."""
    count = db.session.query(TourOrder).count()
    return jsonify({
        'total_order_count': count
    })

# hotel stat
@admin_bp.route('/hotel/count_by_company/<int:company_id>', methods=['GET'])
@jwt_required()
def count_by_company_hotel(company_id):
    """Get the count of orders for a specific company."""
    count = db.session.query(HotelOrder).filter_by(company_id=company_id).count()
    return jsonify({
        'company_id': company_id,
        'order_count': count
    })

@admin_bp.route('/hotel/total_count', methods=['GET'])
@jwt_required()
def total_count_hotel():
    """Get the total count of orders across all companies."""
    count = db.session.query(HotelOrder).count()
    return jsonify({
        'total_order_count': count
    })



@admin_bp.route('/chat_id', methods=['POST'])
@jwt_required()
def add_or_update_chat_id():
    data = request.get_json()
    
    chat_id = data.get('chat_id')
    thread_id = data.get('thread_id')
    company_id = data.get('company_id')
    
    if not chat_id or not thread_id or not company_id:
        return jsonify({"error": "Missing required fields: chat_id, thread_id, or company_id"}), 400

    company = Company.query.get(company_id)
    if not company:
        return jsonify({"error": "Company not found"}), 404

    existing_chat_id = ChatIds.query.filter_by(company_id=company_id, thread_id=thread_id).first()

    if existing_chat_id:
        existing_chat_id.chat_id = chat_id
        db.session.commit()
        return jsonify({
            "message": "ChatId entry updated successfully",
            "chat_id": existing_chat_id.id,
            "thread_id": existing_chat_id.thread_id,
            "company_id": existing_chat_id.company_id
        }), 200

    new_chat_id = ChatIds(
        chat_id=chat_id,
        thread_id=thread_id,
        company_id=company_id
    )

    db.session.add(new_chat_id)
    db.session.commit()

    return jsonify({"message": "New ChatId added successfully", "chat_id": new_chat_id.id}), 201

@admin_bp.route('/chat_ids/<int:company_id>', methods=['GET'])
@jwt_required()
def get_chat_ids_by_company(company_id):
    chat_entries = ChatIds.query.filter_by(company_id=company_id).all()

    if not chat_entries:
        return jsonify({"error": "No ChatIds found for the given company ID"}), 404

    chat_ids_list = [
        {
            "id": chat_entry.id,
            "chat_id": chat_entry.chat_id,
            "thread_id": chat_entry.thread_id,
            "company_id": chat_entry.company_id
        }
        for chat_entry in chat_entries
    ]

    return jsonify({"chat_ids": chat_ids_list}), 200

@admin_bp.route('/dashboard', methods=['GET'])
@jwt_required()
def admin_dashboard():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403


    return jsonify({
        'message': True,
        'user': user.first_name,
        'companies': Company.query.count(),
        'users': User.query.count()
        }), 200


@admin_bp.route('/companies', methods=['GET'])
@jwt_required()
def companies():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_companies = Company.query.paginate(page=page, per_page=per_page, error_out=False)

    companies_list = [
        {
            'id': company.id,
            'name': company.name,
            'category': company.category
        }
        for company in paginated_companies.items
    ]

    return jsonify({
        'message': True,
        'companies': companies_list,
        'total': paginated_companies.total,  
        'pages': paginated_companies.pages, 
        'current_page': paginated_companies.page,  
        'per_page': paginated_companies.per_page  
    }), 200


@admin_bp.route('/company/<int:company_id>', methods=['GET'])
@jwt_required()
def get_company_info(company_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    company = Company.query.get(company_id)

    if not company:
        return jsonify({'message': 'Company not found'}), 404

    users = User.query.filter_by(company_id=company.id).all()

    users_data = [{
        'id': u.id,
        'first_name': u.first_name,
        'last_name': u.last_name,
        'email': u.email,
        'phone_number': u.phone_number,
        'city': u.city,
        'status': u.status,
        'role': u.role
    } for u in users]

    logo_base64 = base64.b64encode(company.logo).decode('utf-8') if company.logo else None

    company_data = {
        'id': company.id,
        'legal_name': company.legal_name,
        'name': company.name,
        'category': company.category,
        'city': company.city,
        'district': company.district,
        'address': company.address,
        'logo': logo_base64
    }

    return jsonify({
        'message': True,
        'company': company_data,
        'users': users_data 
    }), 200


@admin_bp.route('/company/<int:company_id>', methods=['PUT'])
@jwt_required()
def edit_company(company_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    company = Company.query.get(company_id)

    if not company:
        return jsonify({'message': 'Company not found'}), 404

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
        try:
            image_file = request.files.get('logo')
            image_data = None
            if image_file:
                image_data = image_file.read()
            company.legal_name = request.form.get('legal_name')
            company.name = request.form.get('name')
            company.category = request.form.get('category')
            company.city = request.form.get('city')
            company.district = request.form.get('district')
            company.address = request.form.get('address')
            company.logo = image_data
            db.session.commit()
            return jsonify({"message": "Company updated successfully"}), 201
        except Exception as e:
            db.session.rollback()
            return jsonify({"error": f"Error: {e}"}), 500
    else:
        return jsonify({"error": "Request must be JSON"}), 400


@admin_bp.route('/users', methods=['GET'])
@jwt_required()
def users():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_users = db.session.query(
        User.id, 
        User.first_name, 
        User.last_name, 
        User.company_id,  
        User.status,
        User.role,
        Company.name.label('company_name')
    ).join(Company, User.company_id == Company.id) \
    .paginate(page=page, per_page=per_page, error_out=False)

    users_list = [
        {
            'id': user.id,
            'first_name': user.first_name,
            'last_name': user.last_name,
            'company_id': user.company_id,  
            'company': user.company_name,
            'status': user.status,
            'role': user.role,
        } 
        for user in paginated_users.items
    ]

    return jsonify({
        'message': True,
        'users': users_list,
        'total': paginated_users.total,
        'page': paginated_users.page,
        'pages': paginated_users.pages,
        'has_next': paginated_users.has_next,
        'has_prev': paginated_users.has_prev
    }), 200


@admin_bp.route('/companies/new', methods=['POST'])
@jwt_required()
def create_company():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
        try:
            image_file = request.files.get('logo')
            image_data = None
            if image_file:
                image_data = image_file.read()
                legal_name = request.form.get('legal_name')
                name = request.form.get('name')
                category = request.form.get('category')
                city = request.form.get('city')
                district = request.form.get('district')
                address = request.form.get('address')
            new_company = Company(
                legal_name=legal_name,
                name=name,
                category=category,
                city=city,
                district=district,
                address=address,
                logo= image_data if image_file else None 
            )
            db.session.add(new_company)
            db.session.commit()
            return jsonify({"message": "Company created successfully"}), 201
        except KeyError as e:
            return jsonify({"error": f"Missing field {e}"}), 400
    else:
        return jsonify({"error": "Request must be JSON"}), 400


@admin_bp.route('/companies/assign_partner', methods=['POST'])
@jwt_required()
def assign_partner():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    if request.is_json:
        data = request.get_json()
        first_name = data.get('first_name')
        last_name = data.get('last_name')
        email = data.get('email')
        phone_number = data.get('phone_number')
        city = data.get('city')
        category = data.get('category')
        company_id = data.get('company_id')
        password = data.get('password')

        try:
            new_user = User(
                first_name=first_name,
                last_name=last_name,
                email=email,
                phone_number=phone_number,
                city=city,
                status=True,
                role='partner',
                category=category,
                company_id=company_id
            )
            new_user.set_password(password)            
            db.session.add(new_user)
            db.session.commit()
            return jsonify({
                "message": "User successfully assigned"
                }), 201
        except KeyError as e:
            return jsonify({"error": f"Missing field {e}"}), 400
    else:
        return jsonify({"error": "Request must be JSON"}), 400
    

@admin_bp.route('/companies/new_admin', methods=['POST'])
@jwt_required()
def new_admin():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    if request.is_json:
        data = request.get_json()
        first_name = data.get('first_name')
        last_name = data.get('last_name')
        email = data.get('email')
        phone_number = data.get('phone_number')
        city = data.get('city')
        company_id = data.get('company_id')
        category = data.get('category')
        password = data.get('password')

        try:
            new_user = User(
                first_name=first_name,
                last_name=last_name,
                email=email,
                phone_number=phone_number,
                city=city,
                status=True,
                role='admin',
                category=category,
                company_id=company_id
            )
            new_user.set_password(password)            
            db.session.add(new_user)
            db.session.commit()
            return jsonify({
                "message": "User successfully assigned"
                }), 201
        except KeyError as e:
            return jsonify({"error": f"Missing field {e}"}), 400
    else:
        return jsonify({"error": "Request must be JSON"}), 400


@admin_bp.route('/users/edit_user', methods=['POST'])
@jwt_required()
def edit_user():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    if request.is_json:
        data = request.get_json()
        user_id = data.get('user_id')  
        user_to_edit = User.query.get(user_id)

        if not user_to_edit:
            return jsonify({'message': 'User to edit not found'}), 404

        user_to_edit.first_name = data.get('first_name', user_to_edit.first_name)
        user_to_edit.last_name = data.get('last_name', user_to_edit.last_name)
        user_to_edit.email = data.get('email', user_to_edit.email)
        user_to_edit.phone_number = data.get('phone_number', user_to_edit.phone_number)
        user_to_edit.city = data.get('city', user_to_edit.city)
        user_to_edit.status = data.get('status', user_to_edit.status) 

        try:
            db.session.commit()
            return jsonify({"message": "User successfully updated"}), 200
        except Exception as e:
            db.session.rollback()
            return jsonify({"error": str(e)}), 500
    else:
        return jsonify({"error": "Request must be JSON"}), 400


@admin_bp.route('/company/<int:company_id>/users', methods=['GET'])
@jwt_required()
def get_users_by_company(company_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role != "admin":
        return jsonify({'message': False}), 403

    users = User.query.filter_by(company_id=company_id).all()

    if not users:
        return jsonify({'message': 'No users found for this company'}), 404

    users_list = [
        {
            'id': user.id,
            'first_name': user.first_name,
            'last_name': user.last_name,
            'email': user.email,
            'phone_number': user.phone_number,
            'city': user.city,
            'status': user.status,
            'role': user.role
        }
        for user in users
    ]

    return jsonify({
        'message': True,
        'users': users_list
    }), 200
