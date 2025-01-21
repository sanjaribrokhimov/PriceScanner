from io import BytesIO
from flask import Blueprint, request, jsonify, send_file
from flask_jwt_extended import jwt_required, get_jwt_identity
from app.models import db 
from app.models.user import User
from app.models.car import Car
from app.models.otp import OTP
from app.models.company import Company
from app.models.hotel import Hotel, HotelImage, Room, RoomAvailability
import base64
from sqlalchemy import and_, or_, func, not_

from collections import defaultdict
from datetime import date, datetime


hotel_bp = Blueprint('hotel', __name__, url_prefix='/api/hotel')

@hotel_bp.route('/item/hotel_image/<int:image_id>', methods=['GET'])
def render_image(image_id):
    image_record = HotelImage.query.get(image_id)
    
    if not image_record:
        return jsonify({'error': 'Image not found'}), 404

    im = BytesIO(image_record.image_data)
    return send_file(im, mimetype='image/jpeg') 

@hotel_bp.route('/items/company/<int:company_id>', methods=['GET'])
def get_hotels_by_company(company_id):
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)
    
    pagination = Hotel.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page, error_out=False)
    hotels = pagination.items

    if not hotels:
        return jsonify({'message': 'No hotels found for this company'}), 404

    hotels_info = []
    for hotel in hotels:
        hotel_info = {
            'hotel_id': hotel.id,
            'status': hotel.status,
            'company_id': hotel.company_id,
            'name': hotel.name,
            'city': hotel.city,
            'room_type': hotel.room_type,
            'bed_type': hotel.bed_type,
            'images': [f'/item/hotel_image/{image.id}' for image in hotel.images],
            'wifi': hotel.wifi,
            'air_conditioner': hotel.air_conditioner,
            'price_per_night': hotel.price_per_night,
            'location': hotel.location,
            'address': hotel.address,
            'comments': hotel.comments,
            'stars': hotel.stars,
            'breakfast': hotel.breakfast,
            'transport': hotel.transport,
            'kitchen': hotel.kitchen,
            'restaurant_bar': hotel.restaurant_bar,
            'swimming_pool': hotel.swimming_pool,
            'gym': hotel.gym,
            'parking': hotel.parking,
            'reviews': hotel.reviews
        }
        hotels_info.append(hotel_info)

    return jsonify({
        'hotels': hotels_info,
        'pagination': {
            'total': pagination.total,
            'pages': pagination.pages,
            'current_page': pagination.page,
            'per_page': pagination.per_page,
            'has_next': pagination.has_next,
            'has_prev': pagination.has_prev,
        }
    }), 200

@hotel_bp.route('/items', methods=['GET'])
def get_all_hotels():
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)
    
    pagination = Hotel.query.paginate(page=page, per_page=per_page, error_out=False)
    hotels = pagination.items

    if not hotels:
        return jsonify({'message': 'No hotels found'}), 404

    hotels_info = []
    for hotel in hotels:
        hotel_info = {
            'hotel_id': hotel.id,
            'status': hotel.status,
            'company_id': hotel.company_id,
            'name': hotel.name,
            'city': hotel.city,
            'room_type': hotel.room_type,
            'bed_type': hotel.bed_type,
            'images': [f'/item/hotel_image/{image.id}' for image in hotel.images],
            'wifi': hotel.wifi,
            'air_conditioner': hotel.air_conditioner,
            'price_per_night': hotel.price_per_night,
            'location': hotel.location,
            'address': hotel.address,
            'comments': hotel.comments,
            'stars': hotel.stars,
            'breakfast': hotel.breakfast,
            'transport': hotel.transport,
            'kitchen': hotel.kitchen,
            'restaurant_bar': hotel.restaurant_bar,
            'swimming_pool': hotel.swimming_pool,
            'gym': hotel.gym,
            'parking': hotel.parking,
            'reviews': hotel.reviews
        }
        hotels_info.append(hotel_info)

    return jsonify({
        'hotels': hotels_info,
        'pagination': {
            'total': pagination.total,
            'pages': pagination.pages,
            'current_page': pagination.page,
            'per_page': pagination.per_page,
            'has_next': pagination.has_next,
            'has_prev': pagination.has_prev,
        }
    }), 200

@hotel_bp.route('/items', methods=['POST'])
@jwt_required()
def add_hotel():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403
    
    status = request.form.get('status')
    print("Status:", bool(int(status)))
    company_id = request.form.get('company_id')
    name = request.form.get('name')
    city = request.form.get('city')
    room_type = request.form.get('room_type')
    bed_type = request.form.get('bed_type')
    wifi = request.form.get('wifi')
    air_conditioner = request.form.get('air_conditioner')
    price_per_night = request.form.get('price_per_night')
    location = request.form.get('location')
    address = request.form.get('address')
    comments = request.form.get('comments')
    stars = request.form.get('stars')
    breakfast = request.form.get('breakfast')
    transport = request.form.get('transport')
    kitchen = request.form.get('kitchen')
    restaurant_bar = request.form.get('restaurant_bar')
    swimming_pool = request.form.get('swimming_pool')
    gym = request.form.get('gym')
    parking = request.form.get('parking')
    reviews = request.form.get('reviews')

    hotel = Hotel(
        status=bool(int(status)),
        company_id=company_id,
        name=name,
        city=city,
        room_type=room_type,
        bed_type=bed_type,
        wifi=bool(int(wifi)),
        air_conditioner=bool(int(air_conditioner)),
        price_per_night=price_per_night,
        location=location,
        address=address,
        comments=comments,
        stars=stars,
        breakfast=bool(int(breakfast)),
        transport=transport,
        kitchen=bool(int(kitchen)),
        restaurant_bar=bool(int(restaurant_bar)),
        swimming_pool=bool(int(swimming_pool)),
        gym=bool(int(gym)),
        parking=bool(int(parking)),
        reviews=reviews
    )

    if 'images' in request.files:
       images = request.files.getlist('images')  
       if len(images) > 40:
           return jsonify({'message': 'You can upload up to 20 images'}), 400
       for image in images:
           image_data = image.read()  
           tour_image = HotelImage(image_data=image_data)
           hotel.images.append(tour_image)

    db.session.add(hotel)
    db.session.commit()

    return jsonify({'message': 'Hotel added successfully'}), 201


@hotel_bp.route('/items/<int:hotel_id>', methods=['PUT'])
@jwt_required()
def edit_hotel(hotel_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403
    
    hotel = Hotel.query.get(hotel_id)
    
    if not hotel:
        return jsonify({'message': 'Hotel not found'}), 404
    
    hotel.status = bool(int(request.form.get('status')))
    hotel.company_id = request.form.get('company_id', hotel.company_id)
    hotel.name = request.form.get('name', hotel.name)
    hotel.city = request.form.get('city', hotel.city)
    hotel.room_type = request.form.get('room_type', hotel.room_type)
    hotel.bed_type = request.form.get('bed_type', hotel.bed_type)
    hotel.wifi = bool(int(request.form.get('wifi')))
    hotel.air_conditioner = bool(int(request.form.get('air_conditioner')))
    hotel.price_per_night = request.form.get('price_per_night', hotel.price_per_night)
    hotel.location = request.form.get('location', hotel.location)
    hotel.address = request.form.get('address', hotel.address)
    hotel.comments = request.form.get('comments', hotel.comments)
    hotel.stars = request.form.get('stars', hotel.stars)
    hotel.breakfast = bool(int(request.form.get('breakfast')))
    hotel.transport = request.form.get('transport', hotel.transport)
    hotel.kitchen = bool(int(request.form.get('kitchen')))
    hotel.restaurant_bar = bool(int(request.form.get('restaurant_bar')))
    hotel.swimming_pool = bool(int(request.form.get('swimming_pool')))
    hotel.gym = bool(int(request.form.get('gym')))
    hotel.parking = bool(int(request.form.get('parking')))
    hotel.reviews = request.form.get('reviews', hotel.reviews)
    
    if 'images' in request.files:
        images = request.files.getlist('images')
        
        if len(images) > 20:
            return jsonify({'message': 'You can upload up to 20 images'}), 400

        hotel.images.clear()
        
        for image in images:
            image_data = image.read()
            tour_image = HotelImage(image_data=image_data)
            hotel.images.append(tour_image)

    db.session.commit()

    return jsonify({'message': 'Hotel updated successfully'}), 200


@hotel_bp.route('/items/<int:hotel_id>', methods=['DELETE'])
@jwt_required()
def delete_hotel(hotel_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403
    
    hotel = Hotel.query.get(hotel_id)
    
    if not hotel:
        return jsonify({'message': 'Hotel not found'}), 404
    
    if user.role == "partner" and hotel.company_id != user.company_id:
        return jsonify({'message': 'Unauthorized to delete this hotel'}), 403
    
    db.session.delete(hotel)
    db.session.commit()

    return jsonify({'message': 'Hotel deleted successfully'}), 200

@hotel_bp.route('/items/<int:hotel_id>', methods=['GET'])
def get_hotel_by_id(hotel_id):
    hotel = Hotel.query.get(hotel_id)

    if not hotel:
        return jsonify({'message': 'Hotel not found'}), 404

    hotel_info = {
        'hotel_id': hotel.id,
        'status': hotel.status,
        'company_id': hotel.company_id,
        'name': hotel.name,
        'city': hotel.city,
        'room_type': hotel.room_type,
        'bed_type': hotel.bed_type,
        'images': [f'/item/hotel_image/{image.id}' for image in hotel.images],
        'wifi': hotel.wifi,
        'air_conditioner': hotel.air_conditioner,
        'price_per_night': hotel.price_per_night,
        'location': hotel.location,
        'address': hotel.address,
        'comments': hotel.comments,
        'stars': hotel.stars,
        'breakfast': hotel.breakfast,
        'transport': hotel.transport,
        'kitchen': hotel.kitchen,
        'restaurant_bar': hotel.restaurant_bar,
        'swimming_pool': hotel.swimming_pool,
        'gym': hotel.gym,
        'parking': hotel.parking,
        'reviews': hotel.reviews
    }

    return jsonify({'hotel': hotel_info}), 200

@hotel_bp.route('/items/filter', methods=['GET'])
def get_hotels_by_filter():
    filters = []
    
    city = request.args.get('city')
    if city:
        filters.append(Hotel.city == city)
        
    room_type = request.args.get('room_type')
    if room_type:
        filters.append(Hotel.room_type == room_type)
    
    bed_type = request.args.get('bed_type')
    if bed_type:
        filters.append(Hotel.bed_type == bed_type)
    
    wifi = request.args.get('wifi')
    if wifi is not None:
        wifi = bool(int(wifi))
        filters.append(Hotel.wifi == wifi)
    
    air_conditioner = request.args.get('air_conditioner')
    if air_conditioner is not None:
        air_conditioner = bool(int(air_conditioner))
        filters.append(Hotel.air_conditioner == air_conditioner)
    
    price_min = request.args.get('price_min', type=float)
    price_max = request.args.get('price_max', type=float)
    if price_min is not None:
        filters.append(Hotel.price_per_night >= price_min)
    if price_max is not None:
        filters.append(Hotel.price_per_night <= price_max)
    
    stars = request.args.get('stars', type=int)
    if stars is not None:
        filters.append(Hotel.stars == stars)
    
    breakfast = request.args.get('breakfast')
    if breakfast is not None:
        breakfast = bool(int(breakfast))
        filters.append(Hotel.breakfast == breakfast)
    
    parking = request.args.get('parking')
    if parking is not None:
        parking = bool(int(parking))
        filters.append(Hotel.parking == parking)
    
    swimming_pool = request.args.get('swimming_pool')
    if swimming_pool is not None:
        swimming_pool = bool(int(swimming_pool))
        filters.append(Hotel.swimming_pool == swimming_pool)
    
    gym = request.args.get('gym')
    if gym is not None:
        gym = bool(int(gym))
        filters.append(Hotel.gym == gym)
    
    transport = request.args.get('transport')
    if transport is not None:
        transport = bool(int(transport))
        filters.append(Hotel.transport == transport)
    
    restaurant_bar = request.args.get('restaurant_bar')
    if restaurant_bar is not None:
        restaurant_bar = bool(int(restaurant_bar))
        filters.append(Hotel.restaurant_bar == restaurant_bar)
    
    location = request.args.get('location')
    if location:
        filters.append(Hotel.location.contains(location)) 
    
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)
    
    pagination = Hotel.query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)
    hotels = pagination.items

    if not hotels:
        return jsonify({'message': 'No hotels found'}), 404

    result = []
    for hotel in hotels:
        hotel_data = {
            'id': hotel.id,
            'company_id': hotel.company_id,
            'name': hotel.name,
            'city': hotel.city,
            'room_type': hotel.room_type,
            'bed_type': hotel.bed_type,
            'wifi': hotel.wifi,
            'images': [f'/item/hotel_image/{image.id}' for image in hotel.images],
            'air_conditioner': hotel.air_conditioner,
            'price_per_night': hotel.price_per_night,
            'stars': hotel.stars,
            'breakfast': hotel.breakfast,
            'transport': hotel.transport,
            'kitchen': hotel.kitchen,
            'restaurant_bar': hotel.restaurant_bar,
            'swimming_pool': hotel.swimming_pool,
            'gym': hotel.gym,
            'parking': hotel.parking,
            'reviews': hotel.reviews,
            'status': "true",
        }
        result.append(hotel_data)

    return jsonify({
        'hotels': result,
        'pagination': {
            'total': pagination.total,
            'pages': pagination.pages,
            'current_page': pagination.page,
            'per_page': pagination.per_page,
            'has_next': pagination.has_next,
            'has_prev': pagination.has_prev,
        }
    }), 200
''' 
#=======================ROOMS=======================
@hotel_bp.route('/room', methods=['POST'])
@jwt_required()
def add_room():
    data = request.get_json()
    try:
        hotel_id = data.get('hotel_id')
        room_type = data.get('room_type')
        capacity = data.get('capacity')
        num_adults = data.get('num_adults')
        bed_type = data.get('bed_type')
        price_per_night = data.get('price_per_night')
        is_available = data.get('is_available', True)
        features = data.get('features', '')

        room = Room(
            hotel_id=hotel_id,
            room_type=room_type,
            capacity=capacity,
            bed_type=bed_type,
            price_per_night=price_per_night,
            is_available=is_available,
            features=features,
            num_adults=num_adults,
        )
        db.session.add(room)
        db.session.commit()

        return jsonify({"message": "Room added successfully", "room_id": room.id}), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"error": str(e)}), 400

@hotel_bp.route('/rooms/bulk', methods=['POST'])
@jwt_required()
def add_multiple_rooms():
    data = request.get_json()
    try:
        hotel_id = data.get('hotel_id')
        room_type = data.get('room_type')
        capacity = data.get('capacity')
        bed_type = data.get('bed_type')
        price_per_night = data.get('price_per_night')
        is_available = data.get('is_available', True)
        features = data.get('features', '')
        num_adults = data.get('num_adults')
        quantity = data.get('quantity', 1) 

        rooms = []
        for _ in range(quantity):
            room = Room(
                hotel_id=hotel_id,
                room_type=room_type,
                capacity=capacity,
                bed_type=bed_type,
                price_per_night=price_per_night,
                is_available=is_available,
                num_adults=num_adults,
                features=features
            )
            db.session.add(room)
            rooms.append(room)

        db.session.commit()

        return jsonify({"message": f"{quantity} rooms added successfully", "room_ids": [room.id for room in rooms]}), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"error": str(e)}), 400
    
@hotel_bp.route('/room/<int:room_id>', methods=['PUT'])
@jwt_required()
def edit_room(room_id):
    data = request.get_json()
    try:
        room = Room.query.get_or_404(room_id)

        room.room_type = data.get('room_type', room.room_type)
        room.capacity = data.get('capacity', room.capacity)
        room.num_adults = data.get('num_adults', room.num_adults)
        room.bed_type = data.get('bed_type', room.bed_type)
        room.price_per_night = data.get('price_per_night', room.price_per_night)
        room.is_available = data.get('is_available', room.is_available)
        room.features = data.get('features', room.features)

        db.session.commit()

        return jsonify({"message": "Room updated successfully", "room_id": room.id}), 200
    except Exception as e:
        db.session.rollback()
        return jsonify({"error": str(e)}), 400
    
@hotel_bp.route('/rooms', methods=['GET'])
def get_all_rooms():
    try:
        page = request.args.get('page', 1, type=int)
        per_page = request.args.get('per_page', 10, type=int)

        rooms_query = Room.query.paginate(page=page, per_page=per_page, error_out=False)

        rooms = [
            {
                "id": room.id,
                "hotel_id": room.hotel_id,
                "room_type": room.room_type,
                "capacity": room.capacity,
                "bed_type": room.bed_type,
                "price_per_night": room.price_per_night,
                "is_available": room.is_available,
                "features": room.features,
                "num_adults": room.num_adults
            } for room in rooms_query.items
        ]

        return jsonify({
            "rooms": rooms,
            "total": rooms_query.total,
            "pages": rooms_query.pages,
            "current_page": rooms_query.page
        }), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 400
    
@hotel_bp.route('/hotel/<int:hotel_id>/rooms', methods=['GET'])
@jwt_required()
def get_rooms_by_hotel(hotel_id):
    try:
        page = request.args.get('page', 1, type=int)
        per_page = request.args.get('per_page', 10, type=int)
        rooms = Room.query.filter_by(hotel_id=hotel_id).paginate(page=page, per_page=per_page, error_out=False)

        data = []
        for room in rooms.items:
            data.append({
                "id": room.id,
                "room_type": room.room_type,
                "capacity": room.capacity,
                "bed_type": room.bed_type,
                "price_per_night": room.price_per_night,
                "is_available": room.is_available,
                "features": room.features
            })

        return jsonify({
            "rooms": data,
            "total": rooms.total,
            "pages": rooms.pages,
            "current_page": rooms.page
        }), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 400
    
@hotel_bp.route('/room/<int:room_id>', methods=['GET'])
def get_single_room(room_id):
    try:
        room = Room.query.get_or_404(room_id)

        room_data = {
            "id": room.id,
            "hotel_id": room.hotel_id,
            "room_type": room.room_type,
            "capacity": room.capacity,
            "bed_type": room.bed_type,
            "price_per_night": room.price_per_night,
            "is_available": room.is_available,
            "features": room.features
        }

        return jsonify({"room": room_data}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 400

@hotel_bp.route('/items/filter/v2', methods=['GET'])
def get_hotels_by_filter_v2():
    filters = []
    room_filters = []

    city = request.args.get('city')
    if city:
        filters.append(Hotel.city == city)

    # Room Filters
    room_type = request.args.get('room_type')
    if room_type:
        room_filters.append(Room.room_type == room_type)
    
    bed_type = request.args.get('bed_type')
    if bed_type:
        room_filters.append(Room.bed_type == bed_type)

    price_min = request.args.get('price_min', type=float)
    price_max = request.args.get('price_max', type=float)
    if price_min:
        room_filters.append(Room.price_per_night >= price_min)
    if price_max:
        room_filters.append(Room.price_per_night <= price_max)
    
    num_adults = request.args.get('num_adults', type=int)
    num_kids = request.args.get('num_kids', type=int)
    
    if num_adults is not None and num_kids is not None:
        required_capacity = num_adults + num_kids
        room_filters.append(Room.capacity == required_capacity)
        room_filters.append(Room.num_adults == num_adults)
    elif num_adults is not None:
        room_filters.append(Room.num_adults == num_adults)

    num_rooms = request.args.get('num_rooms', 1, type=int) 
    
    # Hotel Filters
    wifi = request.args.get('wifi')
    if wifi is not None:
        wifi = bool(int(wifi))
        filters.append(Hotel.wifi == wifi)
    
    air_conditioner = request.args.get('air_conditioner')
    if air_conditioner is not None:
        air_conditioner = bool(int(air_conditioner))
        filters.append(Hotel.air_conditioner == air_conditioner)
    
    stars = request.args.get('stars', type=int)
    if stars is not None:
        filters.append(Hotel.stars == stars)
    
    breakfast = request.args.get('breakfast')
    if breakfast is not None:
        breakfast = bool(int(breakfast))
        filters.append(Hotel.breakfast == breakfast)
    
    parking = request.args.get('parking')
    if parking is not None:
        parking = bool(int(parking))
        filters.append(Hotel.parking == parking)
    
    swimming_pool = request.args.get('swimming_pool')
    if swimming_pool is not None:
        swimming_pool = bool(int(swimming_pool))
        filters.append(Hotel.swimming_pool == swimming_pool)
    
    gym = request.args.get('gym')
    if gym is not None:
        gym = bool(int(gym))
        filters.append(Hotel.gym == gym)
    
    transport = request.args.get('transport')
    if transport is not None:
        transport = bool(int(transport))
        filters.append(Hotel.transport == transport)
    
    restaurant_bar = request.args.get('restaurant_bar')
    if restaurant_bar is not None:
        restaurant_bar = bool(int(restaurant_bar))
        filters.append(Hotel.restaurant_bar == restaurant_bar)
    
    location = request.args.get('location')
    if location:
        filters.append(Hotel.location.contains(location))
    
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)
        
    
    if room_filters:
      available_rooms = Room.query.filter(and_(*room_filters),Room.is_available == True).all()
      #check if enough rooms
      hotels_with_rooms = defaultdict(int)
      for room in available_rooms:
        hotels_with_rooms[room.hotel_id] += 1

      hotel_ids = [hotel_id for hotel_id, count in hotels_with_rooms.items() if count >= num_rooms]
      
      if not hotel_ids:
          return jsonify({'message': 'No hotels found with the specified room criteria'}), 404
      
      filters.append(Hotel.id.in_(hotel_ids))
      pagination = Hotel.query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)
      hotels = pagination.items

    else:
      
      pagination = Hotel.query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)
      hotels = pagination.items
    
    if not hotels:
        return jsonify({'message': 'No hotels found'}), 404

    result = []
    for hotel in hotels:
        
        hotel_data = {
            'hotel_id': hotel.id,
            'company_id': hotel.company_id,
            'name': hotel.name,
            'city': hotel.city,
            'location': hotel.location,
             'images': [f'/item/hotel_image/{image.id}' for image in hotel.images],
            'wifi': hotel.wifi,
            'air_conditioner': hotel.air_conditioner,
            'stars': hotel.stars,
            'breakfast': hotel.breakfast,
            'transport': hotel.transport,
            'kitchen': hotel.kitchen,
            'restaurant_bar': hotel.restaurant_bar,
            'swimming_pool': hotel.swimming_pool,
            'gym': hotel.gym,
            'parking': hotel.parking,
            'reviews': hotel.reviews,
            'status': "true",
              'rooms':[{
                'id':room.id,
                'room_type':room.room_type,
                'capacity':room.capacity,
                'num_adults':room.num_adults,
                 'num_kids': room.capacity - room.num_adults,
                'bed_type':room.bed_type,
                'price_per_night':room.price_per_night,
                'is_available':room.is_available,
              }for room in hotel.rooms]
        }
        result.append(hotel_data)

    return jsonify({
        'hotels': result,
        'pagination': {
            'total': pagination.total,
            'pages': pagination.pages,
            'current_page': pagination.page,
            'per_page': pagination.per_page,
            'has_next': pagination.has_next,
            'has_prev': pagination.has_prev,
        }
    }), 200

#...
'''
@hotel_bp.route('/room', methods=['POST'])
@jwt_required()
def add_room():
    data = request.get_json()
    try:
        hotel_id = data.get('hotel_id')
        room_type = data.get('room_type')
        capacity = data.get('capacity')
        num_adults = data.get('num_adults')
        bed_type = data.get('bed_type')
        price_per_night = data.get('price_per_night')
        is_available = data.get('is_available', True)
        features = data.get('features', '')
        
        room = Room(
            hotel_id=hotel_id,
            room_type=room_type,
            capacity=capacity,
            bed_type=bed_type,
            price_per_night=price_per_night,
            is_available=is_available,
            features=features,
            num_adults=num_adults,
        )
        db.session.add(room)
        db.session.commit()

        return jsonify({"message": "Room added successfully", "room_id": room.id}), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"error": str(e)}), 400

@hotel_bp.route('/rooms/bulk', methods=['POST'])
@jwt_required()
def add_multiple_rooms():
    data = request.get_json()
    try:
        hotel_id = data.get('hotel_id')
        room_type = data.get('room_type')
        capacity = data.get('capacity')
        bed_type = data.get('bed_type')
        price_per_night = data.get('price_per_night')
        is_available = data.get('is_available', True)
        features = data.get('features', '')
        num_adults = data.get('num_adults')
        quantity = data.get('quantity', 1)

        rooms = []
        for _ in range(quantity):
            room = Room(
                hotel_id=hotel_id,
                room_type=room_type,
                capacity=capacity,
                bed_type=bed_type,
                price_per_night=price_per_night,
                is_available=is_available,
                num_adults=num_adults,
                features=features
            )
            db.session.add(room)
            rooms.append(room)
        db.session.commit()

        return jsonify({"message": f"{quantity} rooms added successfully", "room_ids": [room.id for room in rooms]}), 201
    except Exception as e:
        db.session.rollback()
        return jsonify({"error": str(e)}), 400


@hotel_bp.route('/room/<int:room_id>', methods=['PUT'])
@jwt_required()
def edit_room(room_id):
    data = request.get_json()
    try:
        room = Room.query.get_or_404(room_id)

        room.room_type = data.get('room_type', room.room_type)
        room.capacity = data.get('capacity', room.capacity)
        room.num_adults = data.get('num_adults', room.num_adults)
        room.bed_type = data.get('bed_type', room.bed_type)
        room.price_per_night = data.get('price_per_night', room.price_per_night)
        room.is_available = data.get('is_available', room.is_available)
        room.features = data.get('features', room.features)

        db.session.commit()

        return jsonify({"message": "Room updated successfully", "room_id": room.id}), 200
    except Exception as e:
        db.session.rollback()
        return jsonify({"error": str(e)}), 400

@hotel_bp.route('/room/<int:room_id>/availability/', methods=['POST'])
@jwt_required()
def set_room_availability(room_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    room = Room.query.filter_by(id=room_id).first()
    if not room:
        return jsonify({'message': 'Room not found'}), 404
    
    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
        try:
          start_date_str = request.form.get("start_date")
          end_date_str = request.form.get("end_date")
          is_available = request.form.get("is_available")
          
          if not all([start_date_str, end_date_str, is_available != None]):
                return jsonify({"message": "Missing Data fields"}), 400

          try:
            start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
            end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()
            is_available = bool(int(is_available))
          except ValueError:
                return jsonify({"message": "Invalid Date or boolean Format, Should be YYYY-MM-DD and '0' or '1' "}), 400
          
          new_availability = RoomAvailability(
                room_id=room.id,
                start_date=start_date,
                end_date=end_date,
                is_available=is_available
            )
          db.session.add(new_availability)
          db.session.commit()

          return jsonify({'message': 'Room availability updated'}), 200
        except Exception as e:
           return jsonify({"error": f"An error occurred {e}"}), 400
    else:
       return jsonify({"error":"Request must be multipart form data"})

@hotel_bp.route('/room/<int:room_id>/availability/<int:availability_id>', methods=['PUT'])
@jwt_required()
def update_room_availability(room_id, availability_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    room = Room.query.filter_by(id=room_id).first()
    if not room:
        return jsonify({'message': 'Room not found'}), 404

    availability = RoomAvailability.query.filter_by(id=availability_id, room_id=room_id).first()
    if not availability:
        return jsonify({'message': 'Availability record not found'}), 404

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
        try:
            start_date_str = request.form.get("start_date")
            end_date_str = request.form.get("end_date")
            is_available = request.form.get("is_available")

            if not all([start_date_str, end_date_str, is_available != None]):
                return jsonify({"message": "Missing Data fields"}), 400

            try:
                start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
                end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()
                is_available = bool(int(is_available))

            except ValueError:
                return jsonify({"message": "Invalid Date or Boolean format, should be YYYY-MM-DD and '0' or '1' "}), 400

            availability.start_date = start_date
            availability.end_date = end_date
            availability.is_available = is_available
            db.session.commit()

            return jsonify({"message": "Availability record successfully updated"}), 200
        except Exception as e:
            return jsonify({"error": f"An error occurred {e}"}), 400
    else:
        return jsonify({"error": "Request must be multipart form data"})

@hotel_bp.route('/rooms', methods=['GET'])
def get_all_rooms():
    try:
        page = request.args.get('page', 1, type=int)
        per_page = request.args.get('per_page', 10, type=int)

        rooms_query = Room.query.paginate(page=page, per_page=per_page, error_out=False)

        rooms = [
            {
                "id": room.id,
                "hotel_id": room.hotel_id,
                "room_type": room.room_type,
                "capacity": room.capacity,
                "bed_type": room.bed_type,
                "price_per_night": room.price_per_night,
                "is_available": room.is_available,
                "features": room.features,
                "num_adults": room.num_adults,
                'availability':[{
                  'start_date':str(availability.start_date),
                  'end_date':str(availability.end_date),
                  'is_available':availability.is_available
                }for availability in room.availabilities if availability.is_available == False]
            } for room in rooms_query.items
        ]

        return jsonify({
            "rooms": rooms,
            "total": rooms_query.total,
            "pages": rooms_query.pages,
            "current_page": rooms_query.page
        }), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 400
    
@hotel_bp.route('/hotel/<int:hotel_id>/rooms', methods=['GET'])
@jwt_required()
def get_rooms_by_hotel(hotel_id):
    try:
        page = request.args.get('page', 1, type=int)
        per_page = request.args.get('per_page', 10, type=int)
        rooms = Room.query.filter_by(hotel_id=hotel_id).paginate(page=page, per_page=per_page, error_out=False)

        data = []
        for room in rooms.items:
            data.append({
                "id": room.id,
                "room_type": room.room_type,
                "capacity": room.capacity,
                "bed_type": room.bed_type,
                "price_per_night": room.price_per_night,
                "is_available": room.is_available,
                "features": room.features,
                  'availability':[{
                  'id':availability.id,
                  'start_date':str(availability.start_date),
                  'end_date':str(availability.end_date),
                  'is_available':availability.is_available
                }for availability in room.availabilities if availability.is_available == False]
            })

        return jsonify({
            "rooms": data,
            "total": rooms.total,
            "pages": rooms.pages,
            "current_page": rooms.page
        }), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 400
    
@hotel_bp.route('/room/<int:room_id>', methods=['GET'])
def get_single_room(room_id):
    try:
        room = Room.query.get_or_404(room_id)

        room_data = {
            "id": room.id,
            "hotel_id": room.hotel_id,
            "room_type": room.room_type,
            "capacity": room.capacity,
            "bed_type": room.bed_type,
            "price_per_night": room.price_per_night,
            "is_available": room.is_available,
            "features": room.features,
              'availability':[{
                  'start_date':str(availability.start_date),
                  'end_date':str(availability.end_date),
                  'is_available':availability.is_available
                }for availability in room.availabilities if availability.is_available == False]
        }

        return jsonify({"room": room_data}), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 400


@hotel_bp.route('/items/filter/v2', methods=['GET'])
def get_hotels_by_filter_v2():
    filters = []
    room_filters = []

    city = request.args.get('city')
    if city:
        filters.append(Hotel.city == city)

    # Room Filters
    room_type = request.args.get('room_type')
    if room_type:
        room_filters.append(Room.room_type == room_type)
    
    bed_type = request.args.get('bed_type')
    if bed_type:
        room_filters.append(Room.bed_type == bed_type)

    price_min = request.args.get('price_min', type=float)
    price_max = request.args.get('price_max', type=float)
    if price_min:
        room_filters.append(Room.price_per_night >= price_min)
    if price_max:
        room_filters.append(Room.price_per_night <= price_max)
    
    num_adults = request.args.get('num_adults', type=int)
    num_kids = request.args.get('num_kids', type=int)
    
    if num_adults is not None and num_kids is not None:
        required_capacity = num_adults + num_kids
        room_filters.append(Room.capacity == required_capacity)
        room_filters.append(Room.num_adults == num_adults)
    elif num_adults is not None:
        room_filters.append(Room.num_adults == num_adults)

    num_rooms = request.args.get('num_rooms', 1, type=int) 
    
    # Hotel Filters
    wifi = request.args.get('wifi')
    if wifi is not None:
        wifi = bool(int(wifi))
        filters.append(Hotel.wifi == wifi)
    
    air_conditioner = request.args.get('air_conditioner')
    if air_conditioner is not None:
        air_conditioner = bool(int(air_conditioner))
        filters.append(Hotel.air_conditioner == air_conditioner)
    
    stars = request.args.get('stars', type=int)
    if stars is not None:
        filters.append(Hotel.stars == stars)
    
    breakfast = request.args.get('breakfast')
    if breakfast is not None:
        breakfast = bool(int(breakfast))
        filters.append(Hotel.breakfast == breakfast)
    
    parking = request.args.get('parking')
    if parking is not None:
        parking = bool(int(parking))
        filters.append(Hotel.parking == parking)
    
    swimming_pool = request.args.get('swimming_pool')
    if swimming_pool is not None:
        swimming_pool = bool(int(swimming_pool))
        filters.append(Hotel.swimming_pool == swimming_pool)
    
    gym = request.args.get('gym')
    if gym is not None:
        gym = bool(int(gym))
        filters.append(Hotel.gym == gym)
    
    transport = request.args.get('transport')
    if transport is not None:
        transport = bool(int(transport))
        filters.append(Hotel.transport == transport)
    
    restaurant_bar = request.args.get('restaurant_bar')
    if restaurant_bar is not None:
        restaurant_bar = bool(int(restaurant_bar))
        filters.append(Hotel.restaurant_bar == restaurant_bar)
    
    location = request.args.get('location')
    if location:
        filters.append(Hotel.location.contains(location))
    
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    start_date_str = request.args.get('start_date')
    end_date_str = request.args.get('end_date')

    start_date = None
    end_date = None

    if start_date_str and end_date_str:
        try:
            start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
            end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()

            # Subquery to find room ids NOT available in the given range
            unavailable_room_ids = db.session.query(RoomAvailability.room_id).filter(
                RoomAvailability.is_available == False,
                RoomAvailability.start_date <= end_date,
                RoomAvailability.end_date >= start_date
            ).distinct().subquery()


            # Filter rooms to only include available rooms
            room_query = Room.query.filter(and_(*room_filters), not_(Room.id.in_(unavailable_room_ids)))
            
            # Count available rooms for each hotel
            hotels_with_rooms = defaultdict(int)
            for room in room_query.all():
                hotels_with_rooms[room.hotel_id] += 1
            
            # Filter hotels based on availability criteria
            hotel_ids = [hotel_id for hotel_id, count in hotels_with_rooms.items() if count >= num_rooms]

            if not hotel_ids:
                return jsonify({'message': 'No hotels found with the specified room criteria'}), 404

            filters.append(Hotel.id.in_(hotel_ids))
            pagination = Hotel.query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)
            hotels = pagination.items

        except ValueError:
            return jsonify({'message': 'Invalid date format, please use YYYY-MM-DD'}), 400
    
    else:
        pagination = Hotel.query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)
        hotels = pagination.items
    
    if not hotels:
        return jsonify({'message': 'No hotels found'}), 404

    result = []
    for hotel in hotels:
        hotel_data = {
            'hotel_id': hotel.id,
            'company_id': hotel.company_id,
            'name': hotel.name,
            'city': hotel.city,
            'location': hotel.location,
            'images': [f'/item/hotel_image/{image.id}' for image in hotel.images],
            'wifi': hotel.wifi,
            'air_conditioner': hotel.air_conditioner,
            'stars': hotel.stars,
            'breakfast': hotel.breakfast,
            'transport': hotel.transport,
            'kitchen': hotel.kitchen,
            'restaurant_bar': hotel.restaurant_bar,
            'swimming_pool': hotel.swimming_pool,
            'gym': hotel.gym,
            'parking': hotel.parking,
            'reviews': hotel.reviews,
            'status': "true",
            'rooms': [{
                'id': room.id,
                'room_type': room.room_type,
                'capacity': room.capacity,
                'num_adults': room.num_adults,
                'num_kids': room.capacity - room.num_adults,
                'bed_type': room.bed_type,
                'price_per_night': room.price_per_night,
                'is_available': True if not any(
                    start_date and end_date and 
                    availability.start_date <= end_date and availability.end_date >= start_date and not availability.is_available
                    for availability in room.availabilities
                ) else False,
                'availability': [{
                    'start_date': availability.start_date.strftime('%Y-%m-%d'),
                    'end_date': availability.end_date.strftime('%Y-%m-%d'),
                    'is_available': availability.is_available
                } for availability in room.availabilities if availability.is_available == False]
            } for room in hotel.rooms]
        }
        result.append(hotel_data)

    return jsonify({
        'hotels': result,
        'pagination': {
            'total': pagination.total,
            'pages': pagination.pages,
            'current_page': pagination.page,
            'per_page': pagination.per_page,
            'has_next': pagination.has_next,
            'has_prev': pagination.has_prev,
        }
    }), 200

''' 
@hotel_bp.route('/items/filter/v2', methods=['GET'])
def get_hotels_by_filter_v2():
    filters = []
    room_filters = []

    city = request.args.get('city')
    if city:
        filters.append(Hotel.city == city)

    # Room Filters
    room_type = request.args.get('room_type')
    if room_type:
        room_filters.append(Room.room_type == room_type)
    
    bed_type = request.args.get('bed_type')
    if bed_type:
        room_filters.append(Room.bed_type == bed_type)

    price_min = request.args.get('price_min', type=float)
    price_max = request.args.get('price_max', type=float)
    if price_min:
        room_filters.append(Room.price_per_night >= price_min)
    if price_max:
        room_filters.append(Room.price_per_night <= price_max)
    
    num_adults = request.args.get('num_adults', type=int)
    num_kids = request.args.get('num_kids', type=int)
    
    if num_adults is not None and num_kids is not None:
        required_capacity = num_adults + num_kids
        room_filters.append(Room.capacity == required_capacity)
        room_filters.append(Room.num_adults == num_adults)
    elif num_adults is not None:
        room_filters.append(Room.num_adults == num_adults)

    num_rooms = request.args.get('num_rooms', 1, type=int) 
    
    # Hotel Filters
    wifi = request.args.get('wifi')
    if wifi is not None:
        wifi = bool(int(wifi))
        filters.append(Hotel.wifi == wifi)
    
    air_conditioner = request.args.get('air_conditioner')
    if air_conditioner is not None:
        air_conditioner = bool(int(air_conditioner))
        filters.append(Hotel.air_conditioner == air_conditioner)
    
    stars = request.args.get('stars', type=int)
    if stars is not None:
        filters.append(Hotel.stars == stars)
    
    breakfast = request.args.get('breakfast')
    if breakfast is not None:
        breakfast = bool(int(breakfast))
        filters.append(Hotel.breakfast == breakfast)
    
    parking = request.args.get('parking')
    if parking is not None:
        parking = bool(int(parking))
        filters.append(Hotel.parking == parking)
    
    swimming_pool = request.args.get('swimming_pool')
    if swimming_pool is not None:
        swimming_pool = bool(int(swimming_pool))
        filters.append(Hotel.swimming_pool == swimming_pool)
    
    gym = request.args.get('gym')
    if gym is not None:
        gym = bool(int(gym))
        filters.append(Hotel.gym == gym)
    
    transport = request.args.get('transport')
    if transport is not None:
        transport = bool(int(transport))
        filters.append(Hotel.transport == transport)
    
    restaurant_bar = request.args.get('restaurant_bar')
    if restaurant_bar is not None:
        restaurant_bar = bool(int(restaurant_bar))
        filters.append(Hotel.restaurant_bar == restaurant_bar)
    
    location = request.args.get('location')
    if location:
        filters.append(Hotel.location.contains(location))
    
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    start_date_str = request.args.get('start_date')
    end_date_str = request.args.get('end_date')

    if start_date_str and end_date_str:
      try:
        start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
        end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()

        # Subquery to find room ids NOT available in the given range
        unavailable_room_ids = db.session.query(RoomAvailability.room_id).filter(
            RoomAvailability.is_available == False,
            RoomAvailability.start_date <= end_date,
            RoomAvailability.end_date >= start_date
        ).distinct().subquery()


        # Filter rooms to only include available rooms
        room_query = Room.query.filter(and_(*room_filters),not_(Room.id.in_(unavailable_room_ids)))
        
        # Count available rooms for each hotel
        hotels_with_rooms = defaultdict(int)
        for room in room_query.all():
            hotels_with_rooms[room.hotel_id] += 1
        
        # Filter hotels based on availability criteria
        hotel_ids = [hotel_id for hotel_id, count in hotels_with_rooms.items() if count >= num_rooms]

        if not hotel_ids:
            return jsonify({'message': 'No hotels found with the specified room criteria'}), 404

        filters.append(Hotel.id.in_(hotel_ids))
        pagination = Hotel.query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)
        hotels = pagination.items

      except ValueError:
        return jsonify({'message': 'Invalid date format, please use YYYY-MM-DD'}), 400
    
    else:
        pagination = Hotel.query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)
        hotels = pagination.items
    
    if not hotels:
        return jsonify({'message': 'No hotels found'}), 404

    result = []
    for hotel in hotels:
        
        hotel_data = {
            'hotel_id': hotel.id,
            'company_id': hotel.company_id,
            'name': hotel.name,
            'city': hotel.city,
            'location': hotel.location,
             'images': [f'/item/hotel_image/{image.id}' for image in hotel.images],
            'wifi': hotel.wifi,
            'air_conditioner': hotel.air_conditioner,
            'stars': hotel.stars,
            'breakfast': hotel.breakfast,
            'transport': hotel.transport,
            'kitchen': hotel.kitchen,
            'restaurant_bar': hotel.restaurant_bar,
            'swimming_pool': hotel.swimming_pool,
            'gym': hotel.gym,
            'parking': hotel.parking,
            'reviews': hotel.reviews,
            'status': "true",
              'rooms':[{
                'id':room.id,
                'room_type':room.room_type,
                'capacity':room.capacity,
                'num_adults':room.num_adults,
                 'num_kids': room.capacity - room.num_adults,
                'bed_type':room.bed_type,
                'price_per_night':room.price_per_night,
                'is_available': True if not any(
                    availability.start_date <= end_date and availability.end_date >= start_date and not availability.is_available
                    for availability in room.availabilities
                ) else False,
                'availability': [{
                    'start_date': availability.start_date.strftime('%Y-%m-%d'),
                    'end_date': availability.end_date.strftime('%Y-%m-%d'),
                    'is_available': availability.is_available
                  } for availability in room.availabilities if availability.is_available == False]
              }for room in hotel.rooms]
        }
        result.append(hotel_data)

    return jsonify({
        'hotels': result,
        'pagination': {
            'total': pagination.total,
            'pages': pagination.pages,
            'current_page': pagination.page,
            'per_page': pagination.per_page,
            'has_next': pagination.has_next,
            'has_prev': pagination.has_prev,
        }
    }), 200

    '''