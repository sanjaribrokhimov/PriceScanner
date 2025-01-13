from flask import Blueprint, request, jsonify
from flask_jwt_extended import jwt_required, get_jwt_identity
from app.models import db 
from app.models.user import User
from app.models.car import Car
from app.models.otp import OTP
from app.models.company import Company
import base64
from sqlalchemy import and_, cast, Float
from app.models.likes import Cart, CartItem
from app.models.user import User

cart_bp = Blueprint('cart', __name__, url_prefix='/api/like')

@cart_bp.route('/', methods=['GET'])
@jwt_required() 
def get_all():
    user_id = get_jwt_identity()
    cart = Cart.query.filter_by(user_id=user_id).first()
    if not cart:
        return jsonify({"error": "Like not found"}), 404

    page = request.args.get('page', 1, type=int)  
    per_page = request.args.get('per_page', 10, type=int)

    paginated_items = CartItem.query.filter_by(cart_id=cart.id).paginate(page=page, per_page=per_page, error_out=False)

    items = [
        {
            "id": item.id,
            "product_type": item.product_type,
            "product_company_id": item.product_company_id,
            "product_id": item.product_id,
            "product": item.product,
        }
        for item in paginated_items.items
    ]

    return jsonify({
        "items": items,
        "pagination": {
            "total_items": paginated_items.total,
            "total_pages": paginated_items.pages,
            "current_page": paginated_items.page,
            "per_page": paginated_items.per_page,
        }
    }), 200

# @cart_bp.route('/item', methods=['POST'])
# @jwt_required()
# def add_item():
#     data = request.get_json()

#     user_id = get_jwt_identity()

#     product_type = data.get('product_type')
#     product_company_id = data.get('product_company_id')
#     product_id = data.get('product_id')
#     product = data.get('product')

#     if not product_type or not product_id:
#         return jsonify({"error": "Missing required fields"}), 400

#     cart = Cart.query.filter_by(user_id=user_id).first()
#     if not cart:
#         cart = Cart(user_id=user_id)
#         db.session.add(cart)
#         db.session.commit()

#     new_item = CartItem(
#         cart_id=cart.id,
#         product_type=product_type,
#         product_company_id=product_company_id,
#         product_id=product_id,
#         product=product
#     )
#     db.session.add(new_item)
#     db.session.commit()

#     return jsonify({"message": "Item added successfully", "item_id": new_item.id}), 201

@cart_bp.route('/item', methods=['POST'])
@jwt_required()
def add_item():
    data = request.get_json()

    user_id = get_jwt_identity()

    product_type = data.get('product_type')
    product_company_id = data.get('product_company_id')
    product_id = data.get('product_id')
    product = data.get('product')

    if not product_type or not product_id:
        return jsonify({"error": "Missing required fields"}), 400

    cart = Cart.query.filter_by(user_id=user_id).first()
    if not cart:
        cart = Cart(user_id=user_id)
        db.session.add(cart)
        db.session.commit()

    existing_item = CartItem.query.filter_by(cart_id=cart.id, product_id=product_id).first()
    if existing_item:
        return jsonify({"error": "liked before"}), 400

    new_item = CartItem(
        cart_id=cart.id,
        product_type=product_type,
        product_company_id=product_company_id,
        product_id=product_id,
        product=product
    )
    db.session.add(new_item)
    db.session.commit()

    return jsonify({"message": "added to favourites", "item_id": new_item.id}), 201

@cart_bp.route('/item/<int:item_id>', methods=['DELETE'])
def delete_item(item_id):
    item = CartItem.query.get(item_id)
    if not item:
        return jsonify({"error": "Item not found"}), 404

    db.session.delete(item)
    db.session.commit()
    return jsonify({"message": "Item deleted successfully"}), 200
