import requests as req # type: ignore
import os
from flask import Blueprint, request, jsonify, send_file
from app.models.orders import RentCarOrder, TourOrder, HotelOrder
from app.models import db 
from app.models.user import User
from app.models.car import Car
from app.models.tour import Tour
from app.models.hotel import Hotel
from app.models.tgPartnerChats import ChatIds
from flask_jwt_extended import jwt_required, get_jwt_identity
from sqlalchemy.exc import SQLAlchemyError

order_bp = Blueprint('order', __name__, url_prefix='/api/order')

BOT_TOKEN = os.environ.get("BOT_TOKEN")
def notifyTG(message_thread_id, chat_id, text):
    url = f"https://api.telegram.org/bot{BOT_TOKEN}/sendMessage"
    data = {
        "message_thread_id": message_thread_id,
        "chat_id": chat_id,
        "text": text,
        "parse_mode": "Markdown",
    }
    response = req.post(url, json=data)
    if response.status_code == 200:
        print("Message sent successfully to TG!")
    else:
        print(f"Error sending message to TG: {response.text}")
'''
# ===================== ORDERS FOR RENTCAR =====================
@order_bp.route('/rco', methods=['POST'])
@jwt_required()
def create_rent_order():
    data = request.get_json()
    if not data:
        return jsonify({'error': 'No input data provided'}), 400
    try:
        new_order = RentCarOrder(
            company_id=data['company_id'],
            car_id=data['car_id'],
            user_id=data['user_id'],
            telephone=data['telephone'],
            name=data['name'],
            surname=data['surname'],
        )
        db.session.add(new_order)
        db.session.commit()

        chat_entry = ChatIds.query.filter_by(company_id=data['company_id']).first()
        if chat_entry:
             orderInfo = f'{data["surname"]} {data["name"]}\n{data["telephone"]}\n[Zakaz](http://45.88.105.79/partner/login.php)'
             notifyTG(chat_entry.thread_id, chat_entry.chat_id, orderInfo)
        return jsonify({'message': 'Rent car order created successfully', 'id': new_order.id}), 201
    except KeyError as e:
        db.session.rollback()
        return jsonify({'error': f'Missing field in input data: {e}'}), 400
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500
    

@order_bp.route('/rco-by-company', methods=['GET'])
@jwt_required()
def get_orders_by_company():
    """
    Fetch paginated rental car orders for a specific company.
    Query Parameters:
    - company_id (required): ID of the company.
    - page (optional): Page number for pagination (default: 1).
    - per_page (optional): Number of items per page (default: 10).
    """
    company_id = request.args.get('company_id', type=int)
    if not company_id:
        return jsonify({"error": "company_id is required"}), 400

    page = request.args.get('page', default=1, type=int)
    per_page = request.args.get('per_page', default=10, type=int)

    try:
        pagination = RentCarOrder.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page)
        orders = pagination.items
        result = {
            "total": pagination.total,
            "pages": pagination.pages,
            "current_page": pagination.page,
            "per_page": pagination.per_page,
            "orders": [
                {
                    "id": order.id,
                    "counter": order.counter,
                    "company_id": order.company_id,
                    "car_id": order.car_id,
                    "user_id": order.user_id,
                    "telephone": order.telephone,
                    "name": order.name,
                    "surname": order.surname,
                    "status": order.status,
                    "created_at": order.created_at,
                    "updated_at": order.updated_at,
                    "has_seen": order.has_seen
                }
                for order in orders
            ],
        }
        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500


@order_bp.route('/rco/item', methods=['PUT'])
@jwt_required()
def update_order_status():
    """
    Update the status of a specific RentCarOrder.
    Request Body (JSON):
    - order_id (required): ID of the order to update.
    - status (required): New status value.
    """
    data = request.get_json()
    if not data:
        return jsonify({'error': 'No input data provided'}), 400
    order_id = data.get('order_id')
    if not order_id:
        return jsonify({"error": "order_id is required"}), 400
    try:
         order = RentCarOrder.query.get_or_404(order_id)
         for key, value in data.items():
            if hasattr(order, key):
                setattr(order, key, value)
         db.session.commit()
         return jsonify({
             "message": "Rent car order updated successfully",
             "order_id": order.id,
         }), 200
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

@order_bp.route('/rco/item/<int:order_id>', methods=['DELETE'])
@jwt_required()
def delete_rent_order(order_id):
    try:
        rent_order = RentCarOrder.query.get_or_404(order_id)
        db.session.delete(rent_order)
        db.session.commit()
        return jsonify({'message': 'Rent car order deleted successfully'}), 200
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500
    
# ===================== ORDERS FOR TOUR =====================
@order_bp.route('/tour/items/all', methods=['GET'])
@jwt_required()
def get_tour_orders():
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        paginated_orders = TourOrder.query.paginate(page=page, per_page=per_page, error_out=False)

        result = {
            "orders": [
                {
                    "id": order.id,
                    "counter": order.counter,
                    "tour_id": order.tour_id,
                    "company_id": order.company_id,
                    "user_id": order.user_id,
                    "telephone": order.telephone,
                    "name": order.name,
                    "surname": order.surname,
                    "status": order.status,
                    "created_at": order.created_at,
                    "updated_at": order.updated_at,
                    "has_seen": order.has_seen,
                }
                for order in paginated_orders.items
            ],
            "total": paginated_orders.total,
            "page": paginated_orders.page,
            "per_page": paginated_orders.per_page,
            "pages": paginated_orders.pages,
        }

        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
       return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500


@order_bp.route('/tour/item', methods=['POST'])
@jwt_required()
def create_tour_order():
    data = request.get_json()
    if not data:
      return jsonify({'error': 'No input data provided'}), 400

    try:
        new_order = TourOrder(
            tour_id=data['tour_id'],
            company_id=data['company_id'],
            user_id=data['user_id'],
            telephone=data['telephone'],
            name=data['name'],
            surname=data['surname'],
        )
        db.session.add(new_order)
        db.session.commit()

        chat_entry = ChatIds.query.filter_by(company_id=data['company_id']).first()

        if chat_entry:
            orderInfo = f'{data["surname"]} {data["name"]}\n{data["telephone"]}\n[Zakaz](http://45.88.105.79/partner/login.php)'
            notifyTG(chat_entry.thread_id, chat_entry.chat_id, orderInfo)

        return jsonify({'message': 'Tour order created successfully', 'id': new_order.id}), 201
    except KeyError as e:
        db.session.rollback()
        return jsonify({'error': f'Missing field in input data: {e}'}), 400
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500


@order_bp.route('/tour/item/<int:order_id>', methods=['PUT'])
@jwt_required()
def update_tour_order(order_id):
  data = request.get_json()
  if not data:
    return jsonify({'error': 'No input data provided'}), 400
  try:
      order = TourOrder.query.get_or_404(order_id)
      for key, value in data.items():
        if hasattr(order, key):
            setattr(order, key, value)
      db.session.commit()
      return jsonify({'message': 'Tour order updated successfully'}), 200
  except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
  except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

@order_bp.route('/tour/item/<int:order_id>', methods=['DELETE'])
@jwt_required()
def delete_tour_order(order_id):
  try:
      order = TourOrder.query.get_or_404(order_id)
      db.session.delete(order)
      db.session.commit()
      return jsonify({"message": "Tour order deleted successfully"}), 200
  except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
  except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

@order_bp.route('/tour/items', methods=['GET'])
@jwt_required()
def get_tour_orders_by_company():
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)
    company_id = request.args.get('company_id', type=int)

    if not company_id:
        return jsonify({"error": "company_id is required"}), 400

    try:
        paginated_orders = (
            TourOrder.query.filter_by(company_id=company_id)
            .paginate(page=page, per_page=per_page, error_out=False)
        )

        result = {
            "orders": [
                {
                    "id": order.id,
                    "counter": order.counter,
                    "tour_id": order.tour_id,
                    "company_id": order.company_id,
                    "user_id": order.user_id,
                    "telephone": order.telephone,
                    "name": order.name,
                    "surname": order.surname,
                    "status": order.status,
                    "created_at": order.created_at,
                    "updated_at": order.updated_at,
                    "has_seen": order.has_seen
                }
                for order in paginated_orders.items
            ],
            "total": paginated_orders.total,
            "page": paginated_orders.page,
            "per_page": paginated_orders.per_page,
            "pages": paginated_orders.pages,
        }

        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
       return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

# ===================== ORDERS FOR HOTEL =====================
@order_bp.route('/hotel', methods=['POST'])
@jwt_required()
def create_hotel_order():
    data = request.get_json()
    if not data:
      return jsonify({'error': 'No input data provided'}), 400

    try:
        order = HotelOrder(
            hotel_id=data['hotel_id'],
            company_id=data['company_id'],
            user_id=data['user_id'],
            telephone=data['telephone'],
            name=data['name'],
            surname=data['surname'],
            num_adults=data['num_adults'],
            num_kids=data['num_kids'],
            room_capacity=data['room_capacity'],
            room_type=data['room_type'],
            bed_type=data['bed_type']
        )
        db.session.add(order)
        db.session.commit()
        chat_entry = ChatIds.query.filter_by(company_id=data['company_id']).first()
        
        if chat_entry:
          orderInfo = f'{data["surname"]} {data["name"]}\n{data["telephone"]}\n[Zakaz](http://45.88.105.79/partner/login.php)'
          notifyTG(chat_entry.thread_id, chat_entry.chat_id, orderInfo)

        return jsonify({'message': 'Hotel order created successfully', 'id': order.id}), 201
    except KeyError as e:
        db.session.rollback()
        return jsonify({'error': f'Missing field in input data: {e}'}), 400
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500
    

@order_bp.route('/hotel/<int:company_id>', methods=['GET'])
@jwt_required()
def get_hotel_orders_by_company(company_id):
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        orders = HotelOrder.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page)

        hotel_order_list = [{
            'id': order.id,
            'hotel_id': order.hotel_id,
            'company_id': order.company_id,
            'user_id': order.user_id,
            'telephone': order.telephone,
            'name': order.name,
            'surname': order.surname,
            'status': order.status,
            'created_at': order.created_at,
            'updated_at': order.updated_at,
            'num_adults': order.num_adults,
            'num_kids': order.num_kids,
            'room_capacity': order.room_capacity,
            'room_type': order.room_type,
            'bed_type': order.bed_type,
            'has_seen': order.has_seen
        } for order in orders.items]

        return jsonify({
            'orders': hotel_order_list,
            'total': orders.total,
            'pages': orders.pages,
            'current_page': orders.page,
            'per_page': orders.per_page
        }), 200
    except SQLAlchemyError as e:
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

@order_bp.route('/hotel/<int:order_id>', methods=['PUT'])
@jwt_required()
def update_hotel_order(order_id):
  data = request.get_json()
  if not data:
    return jsonify({'error': 'No input data provided'}), 400
  try:
      order = HotelOrder.query.get_or_404(order_id)
      for key, value in data.items():
        if hasattr(order, key):
            setattr(order, key, value)
      db.session.commit()
      return jsonify({'message': 'Hotel order updated successfully'}), 200
  except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
  except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

#===============OREDER HAS_SEEN -> TRUE#===============
@order_bp.route('/has-seen/<int:order_id>', methods=['PUT'])
@jwt_required()
def mark_order_as_seen(order_id):
    data = request.get_json()
    order_type = data.get('order_type')

    if not order_type or not order_id:
        return jsonify({"error": "Missing order_type or order_id"}), 400

    order_model = None
    if order_type == "rentcar":
        order_model = RentCarOrder
    elif order_type == "tour":
        order_model = TourOrder
    elif order_type == "hotel":
        order_model = HotelOrder
    else:
        return jsonify({"error": "Invalid order_type"}), 400

    order = order_model.query.get(order_id)
    if not order:
         return jsonify({"error": "Order not found"}), 404

    order.has_seen = True
    db.session.commit()

    return jsonify({"message": f"{order_type.capitalize()} order {order_id} marked as seen."}), 200

@order_bp.route('/user-orders', methods=['GET'])
@jwt_required()
def get_all_orders_by_user():
    """
    Fetches all orders (RentCar, Tour, and Hotel) for the current user, combined into a single paginated response.

    Query Parameters:
        - page (int, optional, default=1): Page number for pagination.
        - per_page (int, optional, default=10): Number of items per page.

    Returns:
        - 200: Returns a JSON with paginated combined orders for the current user.
        - 404: If the user is not found, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    if not user:
        return jsonify({'message': 'User not found'}), 404

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        rentcar_orders = RentCarOrder.query.filter_by(user_id=current_user_id).all()
        tour_orders = TourOrder.query.filter_by(user_id=current_user_id).all()
        hotel_orders = HotelOrder.query.filter_by(user_id=current_user_id).all()

        all_orders = []
        for order in rentcar_orders:
            all_orders.append({
                "type": "rentcar",
                "id": order.id,
                "counter": order.counter,
                "company_id": order.company_id,
                "car_id": order.car_id,
                "user_id": order.user_id,
                "telephone": order.telephone,
                "name": order.name,
                "surname": order.surname,
                "status": order.status,
                "created_at": order.created_at,
                "updated_at": order.updated_at,
                "has_seen": order.has_seen
            })
        for order in tour_orders:
             all_orders.append({
                "type": "tour",
                "id": order.id,
                "counter": order.counter,
                "tour_id": order.tour_id,
                "company_id": order.company_id,
                "user_id": order.user_id,
                "telephone": order.telephone,
                "name": order.name,
                "surname": order.surname,
                "status": order.status,
                "created_at": order.created_at,
                "updated_at": order.updated_at,
                "has_seen": order.has_seen
            })
        for order in hotel_orders:
            all_orders.append({
                "type": "hotel",
                "id": order.id,
                "counter": order.counter,
                "hotel_id": order.hotel_id,
                "company_id": order.company_id,
                "user_id": order.user_id,
                "telephone": order.telephone,
                "name": order.name,
                "surname": order.surname,
                "status": order.status,
                "created_at": order.created_at,
                "updated_at": order.updated_at,
                "has_seen": order.has_seen,
                "num_adults": order.num_adults,
                "num_kids": order.num_kids,
                "room_capacity": order.room_capacity,
                "room_type": order.room_type,
                "bed_type": order.bed_type
            })

        start = (page - 1) * per_page
        end = start + per_page
        paginated_orders = all_orders[start:end]
        total_orders = len(all_orders)
        total_pages = (total_orders + per_page - 1) // per_page

        result = {
            "orders": paginated_orders,
            "total": total_orders,
            "page": page,
            "per_page": per_page,
            "pages": total_pages,
        }
        return jsonify(result), 200
    except SQLAlchemyError as e:
         return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500
'''
# ===================== ORDERS FOR RENTCAR =====================
@order_bp.route('/rco', methods=['POST'])
@jwt_required()
def create_rent_order():
    data = request.get_json()
    if not data:
        return jsonify({'error': 'No input data provided'}), 400
    try:
        new_order = RentCarOrder(
            company_id=data['company_id'],
            car_id=data['car_id'],
            user_id=data['user_id'],
            telephone=data['telephone'],
            name=data['name'],
            surname=data['surname'],
            comment=data.get('comment')
        )
        db.session.add(new_order)
        db.session.commit()

        chat_entry = ChatIds.query.filter_by(company_id=data['company_id']).first()
        if chat_entry:
             orderInfo = f'{data["surname"]} {data["name"]}\n{data["telephone"]}\n[Zakaz](http://45.88.105.79/partner/login.php)'
             notifyTG(chat_entry.thread_id, chat_entry.chat_id, orderInfo)
        return jsonify({'message': 'Rent car order created successfully', 'id': new_order.id}), 201
    except KeyError as e:
        db.session.rollback()
        return jsonify({'error': f'Missing field in input data: {e}'}), 400
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500
    

@order_bp.route('/rco-by-company', methods=['GET'])
@jwt_required()
def get_orders_by_company():
    """
    Fetch paginated rental car orders for a specific company.
    Query Parameters:
    - company_id (required): ID of the company.
    - page (optional): Page number for pagination (default: 1).
    - per_page (optional): Number of items per page (default: 10).
    """
    company_id = request.args.get('company_id', type=int)
    if not company_id:
        return jsonify({"error": "company_id is required"}), 400

    page = request.args.get('page', default=1, type=int)
    per_page = request.args.get('per_page', default=10, type=int)

    try:
        pagination = RentCarOrder.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page)
        orders = pagination.items
        result = {
            "total": pagination.total,
            "pages": pagination.pages,
            "current_page": pagination.page,
            "per_page": pagination.per_page,
            "orders": [
                {
                    "id": order.id,
                    "counter": order.counter,
                    "company_id": order.company_id,
                    "car_id": order.car_id,
                    "user_id": order.user_id,
                    "telephone": order.telephone,
                    "name": order.name,
                    "surname": order.surname,
                    "status": order.status,
                    "created_at": order.created_at,
                    "updated_at": order.updated_at,
                    "has_seen": order.has_seen,
                    "comment": order.comment
                }
                for order in orders
            ],
        }
        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500


@order_bp.route('/rco/item', methods=['PUT'])
@jwt_required()
def update_order_status():
    """
    Update the status of a specific RentCarOrder.
    Request Body (JSON):
    - order_id (required): ID of the order to update.
    - status (required): New status value.
    """
    data = request.get_json()
    if not data:
        return jsonify({'error': 'No input data provided'}), 400
    order_id = data.get('order_id')
    if not order_id:
        return jsonify({"error": "order_id is required"}), 400
    try:
         order = RentCarOrder.query.get_or_404(order_id)
         for key, value in data.items():
            if hasattr(order, key):
                setattr(order, key, value)
         db.session.commit()
         return jsonify({
             "message": "Rent car order updated successfully",
             "order_id": order.id,
         }), 200
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

@order_bp.route('/rco/item/<int:order_id>', methods=['DELETE'])
@jwt_required()
def delete_rent_order(order_id):
    try:
        rent_order = RentCarOrder.query.get_or_404(order_id)
        db.session.delete(rent_order)
        db.session.commit()
        return jsonify({'message': 'Rent car order deleted successfully'}), 200
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500
    
# ===================== ORDERS FOR TOUR =====================
@order_bp.route('/tour/items/all', methods=['GET'])
@jwt_required()
def get_tour_orders():
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        paginated_orders = TourOrder.query.paginate(page=page, per_page=per_page, error_out=False)

        result = {
            "orders": [
                {
                    "id": order.id,
                    "counter": order.counter,
                    "tour_id": order.tour_id,
                    "company_id": order.company_id,
                    "user_id": order.user_id,
                    "telephone": order.telephone,
                    "name": order.name,
                    "surname": order.surname,
                    "status": order.status,
                    "created_at": order.created_at,
                    "updated_at": order.updated_at,
                    "has_seen": order.has_seen,
                    "comment": order.comment
                }
                for order in paginated_orders.items
            ],
            "total": paginated_orders.total,
            "page": paginated_orders.page,
            "per_page": paginated_orders.per_page,
            "pages": paginated_orders.pages,
        }

        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
       return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500


@order_bp.route('/tour/item', methods=['POST'])
@jwt_required()
def create_tour_order():
    data = request.get_json()
    if not data:
      return jsonify({'error': 'No input data provided'}), 400

    try:
        new_order = TourOrder(
            tour_id=data['tour_id'],
            company_id=data['company_id'],
            user_id=data['user_id'],
            telephone=data['telephone'],
            name=data['name'],
            surname=data['surname'],
            comment=data.get('comment')
        )
        db.session.add(new_order)
        db.session.commit()

        chat_entry = ChatIds.query.filter_by(company_id=data['company_id']).first()

        if chat_entry:
            orderInfo = f'{data["surname"]} {data["name"]}\n{data["telephone"]}\n[Zakaz](http://45.88.105.79/partner/login.php)'
            notifyTG(chat_entry.thread_id, chat_entry.chat_id, orderInfo)

        return jsonify({'message': 'Tour order created successfully', 'id': new_order.id}), 201
    except KeyError as e:
        db.session.rollback()
        return jsonify({'error': f'Missing field in input data: {e}'}), 400
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500


@order_bp.route('/tour/item/<int:order_id>', methods=['PUT'])
@jwt_required()
def update_tour_order(order_id):
  data = request.get_json()
  if not data:
    return jsonify({'error': 'No input data provided'}), 400
  try:
      order = TourOrder.query.get_or_404(order_id)
      for key, value in data.items():
        if hasattr(order, key):
            setattr(order, key, value)
      db.session.commit()
      return jsonify({'message': 'Tour order updated successfully'}), 200
  except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
  except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

@order_bp.route('/tour/item/<int:order_id>', methods=['DELETE'])
@jwt_required()
def delete_tour_order(order_id):
  try:
      order = TourOrder.query.get_or_404(order_id)
      db.session.delete(order)
      db.session.commit()
      return jsonify({"message": "Tour order deleted successfully"}), 200
  except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
  except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

@order_bp.route('/tour/items', methods=['GET'])
@jwt_required()
def get_tour_orders_by_company():
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)
    company_id = request.args.get('company_id', type=int)

    if not company_id:
        return jsonify({"error": "company_id is required"}), 400

    try:
        paginated_orders = (
            TourOrder.query.filter_by(company_id=company_id)
            .paginate(page=page, per_page=per_page, error_out=False)
        )

        result = {
            "orders": [
                {
                    "id": order.id,
                    "counter": order.counter,
                    "tour_id": order.tour_id,
                    "company_id": order.company_id,
                    "user_id": order.user_id,
                    "telephone": order.telephone,
                    "name": order.name,
                    "surname": order.surname,
                    "status": order.status,
                    "created_at": order.created_at,
                    "updated_at": order.updated_at,
                    "has_seen": order.has_seen,
                    "comment": order.comment
                }
                for order in paginated_orders.items
            ],
            "total": paginated_orders.total,
            "page": paginated_orders.page,
            "per_page": paginated_orders.per_page,
            "pages": paginated_orders.pages,
        }

        return jsonify(result), 200
    except SQLAlchemyError as e:
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
       return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

# ===================== ORDERS FOR HOTEL =====================
@order_bp.route('/hotel', methods=['POST'])
@jwt_required()
def create_hotel_order():
    data = request.get_json()
    if not data:
      return jsonify({'error': 'No input data provided'}), 400

    try:
        order = HotelOrder(
            hotel_id=data['hotel_id'],
            company_id=data['company_id'],
            user_id=data['user_id'],
            telephone=data['telephone'],
            name=data['name'],
            surname=data['surname'],
            num_adults=data['num_adults'],
            num_kids=data['num_kids'],
            room_capacity=data['room_capacity'],
            room_type=data['room_type'],
            bed_type=data['bed_type'],
            comment=data.get('comment')
        )
        db.session.add(order)
        db.session.commit()
        chat_entry = ChatIds.query.filter_by(company_id=data['company_id']).first()
        
        if chat_entry:
          orderInfo = f'{data["surname"]} {data["name"]}\n{data["telephone"]}\n[Zakaz](http://45.88.105.79/partner/login.php)'
          notifyTG(chat_entry.thread_id, chat_entry.chat_id, orderInfo)

        return jsonify({'message': 'Hotel order created successfully', 'id': order.id}), 201
    except KeyError as e:
        db.session.rollback()
        return jsonify({'error': f'Missing field in input data: {e}'}), 400
    except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500
    

@order_bp.route('/hotel/<int:company_id>', methods=['GET'])
@jwt_required()
def get_hotel_orders_by_company(company_id):
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        orders = HotelOrder.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page)

        hotel_order_list = [{
            'id': order.id,
            'hotel_id': order.hotel_id,
            'company_id': order.company_id,
            'user_id': order.user_id,
            'telephone': order.telephone,
            'name': order.name,
            'surname': order.surname,
            'status': order.status,
            'created_at': order.created_at,
            'updated_at': order.updated_at,
            'num_adults': order.num_adults,
            'num_kids': order.num_kids,
            'room_capacity': order.room_capacity,
            'room_type': order.room_type,
            'bed_type': order.bed_type,
            'has_seen': order.has_seen,
            'comment': order.comment
        } for order in orders.items]

        return jsonify({
            'orders': hotel_order_list,
            'total': orders.total,
            'pages': orders.pages,
            'current_page': orders.page,
            'per_page': orders.per_page
        }), 200
    except SQLAlchemyError as e:
        return jsonify({'error': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

@order_bp.route('/hotel/<int:order_id>', methods=['PUT'])
@jwt_required()
def update_hotel_order(order_id):
  data = request.get_json()
  if not data:
    return jsonify({'error': 'No input data provided'}), 400
  try:
      order = HotelOrder.query.get_or_404(order_id)
      for key, value in data.items():
        if hasattr(order, key):
            setattr(order, key, value)
      db.session.commit()
      return jsonify({'message': 'Hotel order updated successfully'}), 200
  except SQLAlchemyError as e:
        db.session.rollback()
        return jsonify({'error': f'Database error: {str(e)}'}), 500
  except Exception as e:
        db.session.rollback()
        return jsonify({'error': f'An unexpected error occurred: {str(e)}'}), 500

#===============OREDER HAS_SEEN -> TRUE#===============
@order_bp.route('/has-seen/<int:order_id>', methods=['PUT'])
@jwt_required()
def mark_order_as_seen(order_id):
    data = request.get_json()
    order_type = data.get('order_type')

    if not order_type or not order_id:
        return jsonify({"error": "Missing order_type or order_id"}), 400

    order_model = None
    if order_type == "rentcar":
        order_model = RentCarOrder
    elif order_type == "tour":
        order_model = TourOrder
    elif order_type == "hotel":
        order_model = HotelOrder
    else:
        return jsonify({"error": "Invalid order_type"}), 400

    order = order_model.query.get(order_id)
    if not order:
         return jsonify({"error": "Order not found"}), 404

    order.has_seen = True
    db.session.commit()

    return jsonify({"message": f"{order_type.capitalize()} order {order_id} marked as seen."}), 200

@order_bp.route('/user-orders', methods=['GET'])
@jwt_required()
def get_all_orders_by_user():
    """
    Fetches all orders (RentCar, Tour, and Hotel) for the current user, combined into a single paginated response.

    Query Parameters:
        - page (int, optional, default=1): Page number for pagination.
        - per_page (int, optional, default=10): Number of items per page.

    Returns:
        - 200: Returns a JSON with paginated combined orders for the current user.
        - 404: If the user is not found, returns a JSON with an error message.
        - 500: If a database error or other unexpected error occurs, returns a JSON with an error message.
    """
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    if not user:
        return jsonify({'message': 'User not found'}), 404

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    try:
        rentcar_orders = RentCarOrder.query.filter_by(user_id=current_user_id).all()
        tour_orders = TourOrder.query.filter_by(user_id=current_user_id).all()
        hotel_orders = HotelOrder.query.filter_by(user_id=current_user_id).all()

        all_orders = []
        for order in rentcar_orders:
            all_orders.append({
                "type": "rentcar",
                "id": order.id,
                "counter": order.counter,
                "company_id": order.company_id,
                "car_id": order.car_id,
                "user_id": order.user_id,
                "telephone": order.telephone,
                "name": order.name,
                "surname": order.surname,
                "status": order.status,
                "created_at": order.created_at,
                "updated_at": order.updated_at,
                "has_seen": order.has_seen,
                "comment": order.comment
            })
        for order in tour_orders:
             all_orders.append({
                "type": "tour",
                "id": order.id,
                "counter": order.counter,
                "tour_id": order.tour_id,
                "company_id": order.company_id,
                "user_id": order.user_id,
                "telephone": order.telephone,
                "name": order.name,
                "surname": order.surname,
                "status": order.status,
                "created_at": order.created_at,
                "updated_at": order.updated_at,
                "has_seen": order.has_seen,
                "comment": order.comment
            })
        for order in hotel_orders:
            all_orders.append({
                "type": "hotel",
                "id": order.id,
                "counter": order.counter,
                "hotel_id": order.hotel_id,
                "company_id": order.company_id,
                "user_id": order.user_id,
                "telephone": order.telephone,
                "name": order.name,
                "surname": order.surname,
                "status": order.status,
                "created_at": order.created_at,
                "updated_at": order.updated_at,
                "has_seen": order.has_seen,
                "comment": order.comment,
                "num_adults": order.num_adults,
                "num_kids": order.num_kids,
                "room_capacity": order.room_capacity,
                "room_type": order.room_type,
                "bed_type": order.bed_type
            })

        start = (page - 1) * per_page
        end = start + per_page
        paginated_orders = all_orders[start:end]
        total_orders = len(all_orders)
        total_pages = (total_orders + per_page - 1) // per_page

        result = {
            "orders": paginated_orders,
            "total": total_orders,
            "page": page,
            "per_page": per_page,
            "pages": total_pages,
        }
        return jsonify(result), 200
    except SQLAlchemyError as e:
         return jsonify({'message': f'Database error: {str(e)}'}), 500
    except Exception as e:
        return jsonify({'message': f'An unexpected error occurred: {str(e)}'}), 500