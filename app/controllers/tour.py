from datetime import datetime
from io import BytesIO
from PIL import Image as PILImage
from app.models.tour import Tour
from app.models.departure import Departure
from app.models.tour_image import TourImage
from flask import Blueprint, request, jsonify, send_file
from werkzeug.utils import secure_filename
from flask_jwt_extended import jwt_required, get_jwt_identity
from app.models.company import Company
from app.models.user import User
from app.models import db
import base64
import json
from sqlalchemy import cast, Float, and_
from sqlalchemy.dialects.postgresql import JSONB
from sqlalchemy.orm import aliased
from sqlalchemy.sql import exists

tour_bp = Blueprint('tour', __name__, url_prefix='/api/tour')

@tour_bp.route('/item/tour_image/<int:image_id>', methods=['GET'])
def render_image(image_id):
    image_record = TourImage.query.get(image_id)
    
    if not image_record:
        return jsonify({'error': 'Image not found'}), 404

    im = BytesIO(image_record.image_data)
    return send_file(im, mimetype='image/jpeg')  

@tour_bp.route('/item', methods=['POST'])
@jwt_required()
def add_tour():
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404
    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    company_id = request.form.get('company_id')
    title = request.form.get('title')
    description = request.form.get('description')
    departures = request.form.getlist('departures')
    video_url = request.form.get('video_url')
    from_country = request.form.get('fromCountry')
    to_country = request.form.get('toCountry')
    category = request.form.get('category')

    if not all([title, description, departures]):
        return jsonify({'message': 'Missing required fields'}), 400

    parsed_departures = []
    try:
        departures = [json.loads(dep) for dep in departures]
        for departure in departures:
            departure_date = departure.get('departure_date')
            price = departure.get('price')

            if not departure_date or not price:
                return jsonify({'message': 'Each departure must have a date and a price.'}), 400

            try:
                parsed_date = datetime.strptime(departure_date, "%Y-%m-%d")
            except ValueError:
                return jsonify({'message': f'Invalid date format for {departure_date}. Expected YYYY-MM-DD.'}), 400

            try:
                parsed_price = float(price)
            except ValueError:
                return jsonify({'message': f'Invalid price format for {price}. Price must be a valid number.'}), 400

            parsed_departures.append({
                'departure_date': parsed_date,
                'price': parsed_price
            })
    except ValueError:
        return jsonify({'message': 'Invalid format for departures. It should be a list of JSON objects.'}), 400

    tour = Tour(
        title=title,
        company_id=company_id,
        video_url=video_url,
        description=description,
        from_country=from_country,
        to_country=to_country,
        category=category,
    )

    if 'images' in request.files:
        images = request.files.getlist('images')
        if len(images) > 10:
            return jsonify({'message': 'You can upload up to 10 images'}), 400
        for image in images:
            image_data = image.read()
            tour_image = TourImage(image_data=image_data)
            tour.images.append(tour_image)

    db.session.add(tour)
    db.session.flush()  

    for departure in parsed_departures:
        departure_record = Departure(
            tour_id=tour.id,
            departure_date=departure['departure_date'],
            price=departure['price']
        )
        db.session.add(departure_record)

    db.session.commit()

    return jsonify({'message': 'Tour added successfully'}), 201

@tour_bp.route('/item/<int:tour_id>/status', methods=['PUT'])
@jwt_required()
def edit_tour_status(tour_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    tour = Tour.query.get(tour_id)
    
    if not tour:
        return jsonify({'message': 'Tour not found'}), 404
    
    status = request.form.get('status')

    if status in ['active', 'inactive']:
        tour.status = status
        db.session.commit()
        return jsonify({'message': 'Tour status updated successfully'}), 200
    else:
        return jsonify({'message': 'Invalid status value. Must be "active" or "inactive"'}), 400

@tour_bp.route('/item/<int:tour_id>', methods=['PUT'])
@jwt_required()
def edit_tour(tour_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    tour = Tour.query.get(tour_id)
    
    if not tour:
        return jsonify({'message': 'Tour not found'}), 404
    
    status = request.form.get('status')
    company_id = request.form.get('company_id')
    title = request.form.get('title')
    description = request.form.get('description')
    video_url = request.form.get('video_url')
    from_country = request.form.get('fromCountry')
    to_country = request.form.get('toCountry')
    category = request.form.get('category')

    if status in ['active', 'inactive']:
        tour.status = status

    if category:
        tour.category = category

    if from_country:
        tour.from_country = from_country

    if to_country:
        tour.to_country = to_country

    if title:
        tour.title = title

    if video_url:
        tour.video_url = video_url

    if company_id:
        tour.company_id = company_id

    if description:
        tour.description = description

    departures = request.form.getlist('departures')  
    if departures:
        try:
            departures = [json.loads(dep) for dep in departures]
        except ValueError:
            return jsonify({'message': 'Invalid format for departures. It should be a list of JSON objects.'}), 400

        if not isinstance(departures, list) or not all(isinstance(dep, dict) for dep in departures):
            return jsonify({'message': 'Invalid format for departures. It should be a list of date-price pairs.'}), 400

        Departure.query.filter_by(tour_id=tour.id).delete()

        for departure in departures:
            departure_date = departure.get('departure_date')
            price = departure.get('price')

            if not departure_date or not price:
                return jsonify({'message': 'Each departure must have a date and a price.'}), 400

            try:
                parsed_date = datetime.strptime(departure_date, "%Y-%m-%d")
            except ValueError:
                return jsonify({'message': f'Invalid date format for {departure_date}. Expected YYYY-MM-DD.'}), 400

            try:
                parsed_price = float(price)
            except ValueError:
                return jsonify({'message': f'Invalid price format for {price}. Price must be a valid number.'}), 400

            new_departure = Departure(
                tour_id=tour.id,
                departure_date=parsed_date,
                price=parsed_price
            )
            db.session.add(new_departure)
            db.session.flush()

    if 'images' in request.files:
        images = request.files.getlist('images')

        if len(images) > 10:
            return jsonify({'message': 'You can upload up to 10 images'}), 400

        if tour.images:
            for image in tour.images:
                db.session.delete(image)

        for image in images:
            image_data = image.read()
            tour_image = TourImage(image_data=image_data)
            tour.images.append(tour_image)

    db.session.commit()

    return jsonify({'message': 'Tour updated successfully'}), 200

@tour_bp.route('/item/<int:tour_id>', methods=['DELETE'])
@jwt_required()
def delete_tour(tour_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)
    
    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    tour = Tour.query.get(tour_id)
    
    if not tour:
        return jsonify({'message': 'Tour not found'}), 404

    for image in tour.images:
        db.session.delete(image)
    
    db.session.delete(tour)
    db.session.commit()

    return jsonify({'message': 'Tour and related images deleted successfully'}), 200

@tour_bp.route('/items/all', methods=['GET'])
def get_all_tours():
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_tours = Tour.query.paginate(page=page, per_page=per_page, error_out=False)

    if not paginated_tours.items:
        return jsonify({'message': 'No tours found'}), 404

    tours_info = []
    for tour in paginated_tours.items:
        departures = Departure.query.filter_by(tour_id=tour.id).all()
        departures_info = [
            {'departure_date': departure.departure_date.isoformat(), 'price': departure.price}
            for departure in departures
        ]

        tour_info = {
            'id': tour.id,
            'title': tour.title,
            'company_id': tour.company_id,
            'description': tour.description,
            'video_url': tour.video_url,
            'status': tour.status,
            'departures': departures_info,
            'fromCountry': tour.from_country, 
            'toCountry': tour.to_country,  
            'category': tour.category,
            'images': [f'/item/tour_image/{image.id}' for image in tour.images]
        }
        tours_info.append(tour_info)

    return jsonify({
        'tours': tours_info,
        'total': paginated_tours.total,
        'pages': paginated_tours.pages,
        'current_page': paginated_tours.page,
        'per_page': paginated_tours.per_page
    }), 200

@tour_bp.route('/item/<int:tour_id>', methods=['GET'])
def get_tour_info(tour_id):
    tour = Tour.query.get(tour_id)
    
    if not tour:
        return jsonify({'message': 'Tour not found'}), 404

    departures = Departure.query.filter_by(tour_id=tour.id).all()
    
    departure_list = []
    for departure in departures:
        departure_list.append({
            'departure_date': departure.departure_date,
            'price': departure.price 
        })

    tour_info = {
        'id': tour.id,
        'title': tour.title,
        'company_id': tour.company_id,
        'description': tour.description,
        'status': tour.status,
        'video_url': tour.video_url,
        'fromCountry': tour.from_country,
        'toCountry': tour.to_country,
        'category': tour.category,
        'departures': departure_list,
        'images': [f'/item/tour_image/{image.id}' for image in tour.images]
    }

    return jsonify({'tour': tour_info}), 200

@tour_bp.route('/items/tours/company/<int:company_id>', methods=['GET'])
def get_tours_by_company(company_id):
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_tours = Tour.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page, error_out=False)

    if not paginated_tours.items:
        return jsonify({'message': 'No tours found for this company'}), 404

    tours_info = []
    for tour in paginated_tours.items:
        departures = Departure.query.filter_by(tour_id=tour.id).all()

        departure_list = []
        for departure in departures:
            departure_list.append({
                'departure_date': departure.departure_date, 
                'price': departure.price  
            })

        tour_info = {
            'id': tour.id,
            'title': tour.title,
            'company_id': tour.company_id,
            'description': tour.description,
            'departures': departure_list,
            'status': tour.status,
            'video_url': tour.video_url,
            'fromCountry': tour.from_country,
            'category': tour.category,
            'toCountry': tour.to_country,
            'images': [f'/item/tour_image/{image.id}' for image in tour.images]
        }
        tours_info.append(tour_info)

    return jsonify({
        'message': True,
        'tours': tours_info,
        'total': paginated_tours.total,
        'pages': paginated_tours.pages,
        'current_page': paginated_tours.page,
        'per_page': paginated_tours.per_page
    }), 200

@tour_bp.route('/items/tours/filtered', methods=['GET'])
def get_filtered_tours():
    filters = []

    title = request.args.get('title')
    if title:
        filters.append(Tour.title.contains(title))

    from_country = request.args.get('fromCountry')
    if from_country:
        filters.append(Tour.from_country == from_country)

    to_country = request.args.get('toCountry')
    if to_country:
        filters.append(Tour.to_country == to_country)

    category = request.args.get('category')
    if category:
        filters.append(Tour.category == category)

    status = request.args.get('status')
    if status:
        filters.append(Tour.status == status)

    min_price = request.args.get('min_price', type=float)
    max_price = request.args.get('max_price', type=float)

    if min_price is not None or max_price is not None:
        departure_alias = aliased(Departure)

        price_conditions = []
        if min_price is not None:
            price_conditions.append(departure_alias.price >= min_price)
        if max_price is not None:
            price_conditions.append(departure_alias.price <= max_price)

        subquery = db.session.query(departure_alias.tour_id).filter(
            and_(*price_conditions)
        ).subquery()

        filters.append(Tour.id.in_(subquery))

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_tours = Tour.query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)

    if not paginated_tours.items:
        return jsonify({'message': 'No tours found'}), 404

    tours_info = []
    for tour in paginated_tours.items:
        departures = Departure.query.filter_by(tour_id=tour.id).all()

        departure_list = []
        for departure in departures:
            departure_list.append({
                'departure_date': departure.departure_date,
                'price': departure.price
            })

        tour_info = {
            'id': tour.id,
            'title': tour.title,
            'company_id': tour.company_id,
            'description': tour.description,
            'departures': departure_list,
            'status': tour.status,
            'video_url': tour.video_url,
            'fromCountry': tour.from_country,
            'category': tour.category,
            'toCountry': tour.to_country,
            'images': [f'/item/tour_image/{image.id}' for image in tour.images]
        }
        tours_info.append(tour_info)

    return jsonify({
        'tours': tours_info,
        'total': paginated_tours.total,
        'pages': paginated_tours.pages,
        'current_page': paginated_tours.page,
        'per_page': paginated_tours.per_page
    }), 200
