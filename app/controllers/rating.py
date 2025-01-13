import json
from io import BytesIO
from flask import Blueprint, request, jsonify, send_file
from app.models import db
from app.models.rating import Rating
from app.models.user import User
from flask_jwt_extended import jwt_required, get_jwt_identity
from datetime import datetime
from sqlalchemy.exc import SQLAlchemyError
from sqlalchemy import func


rating_bp = Blueprint('rating', __name__, url_prefix='/api/rating')

# ===================== CREATE =====================
@rating_bp.route('/item', methods=['POST'])
@jwt_required()
def create_rating():
    """
    Creates a new rating.

    Request Body (JSON):
        - company_id (int, required): ID of the company being rated.
        - user_id (int, required): ID of the user leaving the rating.
        - stars (int, required): Star rating (1 to 10).
        - comment (str, optional): Comment for the rating.
        - order_id (int, optional): ID of the order associated with the rating.

    Returns:
        - 201: If the rating is created successfully, returns a JSON with a success message and the ID of the new rating.
        - 400: If the request body is missing or has missing fields, returns a JSON with an error message.
        - 403: If the user is not authorized to perform this action, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    data = request.get_json()
    if not data:
        return jsonify({'message': 'No input data provided'}), 400

    try:
        stars = Rating.validate_stars(data.get('stars'))
        new_rating = Rating(
            company_id=data['company_id'],
            user_id=user.id,
            stars=stars,
            comment=data.get('comment'),
            order_id=data.get('order_id')
        )
        db.session.add(new_rating)
        db.session.commit()
        return jsonify({'message': 'Rating created successfully', 'id': new_rating.id}), 201
    except ValueError as e:
        return jsonify({'message': str(e)}), 400
    except KeyError as e:
        db.session.rollback()
        return jsonify({'message': f'Missing field in input data: {e}'}), 400
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500

# ===================== READ (ALL WITH PAGINATION) =====================
@rating_bp.route('/items', methods=['GET'])
@jwt_required()
def get_all_ratings():
    """
    Fetches all ratings with pagination.

    Query Parameters:
        - page (int, optional, default=1): Page number for pagination.
        - per_page (int, optional, default=10): Number of items per page.

    Returns:
        - 200: Returns a JSON with paginated ratings.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        paginated_ratings = Rating.query.paginate(page=page, per_page=per_page, error_out=False)
        result = {
            "ratings": [
                {
                    "id": rating.id,
                    "company_id": rating.company_id,
                    "user_id": rating.user_id,
                    "order_id": rating.order_id,
                    "stars": rating.stars,
                    "comment": rating.comment,
                    "created_at": rating.created_at,
                    "updated_at": rating.updated_at
                }
                for rating in paginated_ratings.items
            ],
            "total": paginated_ratings.total,
            "page": paginated_ratings.page,
            "per_page": paginated_ratings.per_page,
            "pages": paginated_ratings.pages,
        }
        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500


# ===================== READ (BY COMPANY ID WITH PAGINATION) =====================
@rating_bp.route('/items/company/<int:company_id>', methods=['GET'])
def get_ratings_by_company(company_id):
    """
    Fetches paginated ratings for a specific company.

    Query Parameters:
        - company_id (int, required): ID of the company.
        - page (int, optional, default=1): Page number for pagination.
        - per_page (int, optional, default=10): Number of items per page.

    Returns:
        - 200: Returns a JSON with paginated ratings for the given company.
        - 400: If company_id is missing, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        paginated_ratings = Rating.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page, error_out=False)
        result = {
            "ratings": [
                {
                    "id": rating.id,
                    "company_id": rating.company_id,
                    "user_id": rating.user_id,
                    "order_id": rating.order_id,
                    "stars": rating.stars,
                    "comment": rating.comment,
                    "created_at": rating.created_at,
                    "updated_at": rating.updated_at
                }
                for rating in paginated_ratings.items
            ],
            "total": paginated_ratings.total,
            "page": paginated_ratings.page,
            "per_page": paginated_ratings.per_page,
            "pages": paginated_ratings.pages,
        }
        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500

# ===================== READ (BY USER ID WITH PAGINATION) =====================
@rating_bp.route('/items/user/<int:user_id>', methods=['GET'])
def get_ratings_by_user(user_id):
    """
    Fetches paginated ratings for a specific user.

    Query Parameters:
        - user_id (int, required): ID of the user.
        - page (int, optional, default=1): Page number for pagination.
        - per_page (int, optional, default=10): Number of items per page.

    Returns:
        - 200: Returns a JSON with paginated ratings for the given user.
        - 400: If user_id is missing, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        paginated_ratings = Rating.query.filter_by(user_id=user_id).paginate(page=page, per_page=per_page, error_out=False)
        result = {
            "ratings": [
                {
                    "id": rating.id,
                    "company_id": rating.company_id,
                    "user_id": rating.user_id,
                    "order_id": rating.order_id,
                    "stars": rating.stars,
                    "comment": rating.comment,
                    "created_at": rating.created_at,
                    "updated_at": rating.updated_at
                }
                for rating in paginated_ratings.items
            ],
            "total": paginated_ratings.total,
            "page": paginated_ratings.page,
            "per_page": paginated_ratings.per_page,
            "pages": paginated_ratings.pages,
        }
        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500

@rating_bp.route('/average/<int:company_id>', methods=['GET'])
def get_average_rating(company_id):
    """
    Calculates and returns the average star rating for a company if there are more than 10 ratings.

    Args:
        company_id (int): ID of the company.

    Returns:
        - 200: Returns a JSON with the average star rating if there are more than 10 ratings.
        - 404: If the company is not found or has 10 or fewer ratings, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    try:
        rating_count = Rating.query.filter_by(company_id=company_id).count()
        if rating_count <= 5:
            return jsonify({'message': 'Not enough ratings for this company'}), 404

        average_stars = db.session.query(func.avg(Rating.stars)).filter(Rating.company_id == company_id).scalar()
        return jsonify({'average_rating': average_stars}), 200
    except SQLAlchemyError as e:
        return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500
    

# # ===================== READ (BY ID) =====================
# @rating_bp.route('/item/<int:rating_id>', methods=['GET'])
# def get_rating_by_id(rating_id):
#     """
#     Fetches a specific rating by its ID.

#     Args:
#         rating_id (int): ID of the rating to retrieve.

#     Returns:
#         - 200: Returns a JSON with the rating information.
#         - 404: If the rating with the given ID is not found, returns a JSON with an error message.
#         - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
#     """
#     try:
#         rating = Rating.query.get_or_404(rating_id)
#         result = {
#             "id": rating.id,
#             "company_id": rating.company_id,
#             "user_id": rating.user_id,
#             "order_id": rating.order_id,
#             "stars": rating.stars,
#             "comment": rating.comment,
#             "created_at": rating.created_at,
#             "updated_at": rating.updated_at
#         }
#         return jsonify(result), 200
#     except SQLAlchemyError as e:
#         return jsonify({'message': f'Database error: {str(e)}'}), 500
#     except Exception as e:
#         return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500

# # ===================== UPDATE =====================
# @rating_bp.route('/item/<int:rating_id>', methods=['PUT'])
# @jwt_required()
# def update_rating(rating_id):
#     """
#     Updates a specific rating.

#     Args:
#         rating_id (int): ID of the rating to update.

#     Request Body (JSON):
#         - stars (int, optional): New star rating (1 to 10).
#         - comment (str, optional): New comment for the rating.
#         - order_id (int, optional): New order ID.

#     Returns:
#         - 200: If the rating is updated successfully, returns a JSON with a success message and the ID of the updated rating.
#         - 400: If the request body is missing or has missing fields, returns a JSON with an error message.
#         - 403: If the user is not authorized to perform this action, returns a JSON with an error message.
#         - 404: If the rating with the given ID is not found, returns a JSON with an error message.
#         - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
#     """
#     current_user_id = get_jwt_identity()
#     user = User.query.get(current_user_id)

#     if not user:
#         return jsonify({'message': 'User not found'}), 404

#     data = request.get_json()
#     if not data:
#         return jsonify({'message': 'No input data provided'}), 400

#     try:
#         rating = Rating.query.get_or_404(rating_id)
#         if 'stars' in data:
#             rating.stars = Rating.validate_stars(data['stars'])
#         if 'comment' in data:
#             rating.comment = data['comment']
#         if 'order_id' in data:
#             rating.order_id = data['order_id']
#         db.session.commit()
#         return jsonify({'message': 'Rating updated successfully', 'id': rating.id}), 200
#     except ValueError as e:
#         return jsonify({'message': str(e)}), 400
#     except SQLAlchemyError as e:
#         db.session.rollback()
#         return jsonify({'message': f'Database error: {str(e)}'}), 500
#     except Exception as e:
#         db.session.rollback()
#         return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500

# # ===================== DELETE =====================
# @rating_bp.route('/item/<int:rating_id>', methods=['DELETE'])
# @jwt_required()
# def delete_rating(rating_id):
#     """
#     Deletes a specific rating.

#     Args:
#         rating_id (int): ID of the rating to delete.

#     Returns:
#         - 200: If the rating is deleted successfully, returns a JSON with a success message.
#         - 403: If the user is not authorized to perform this action, returns a JSON with an error message.
#         - 404: If the rating with the given ID is not found, returns a JSON with an error message.
#         - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
#     """
#     current_user_id = get_jwt_identity()
#     user = User.query.get(current_user_id)

#     if not user:
#         return jsonify({'message': 'User not found'}), 404

#     try:
#         rating = Rating.query.get_or_404(rating_id)
#         db.session.delete(rating)
#         db.session.commit()
#         return jsonify({'message': 'Rating deleted successfully'}), 200
#     except SQLAlchemyError as e:
#         db.session.rollback()
#         return jsonify({'message': f'Database error: {str(e)}'}), 500
#     except Exception as e:
#         db.session.rollback()
#         return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500