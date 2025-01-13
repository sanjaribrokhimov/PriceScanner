from flask import Flask, request, jsonify, Blueprint
from app.models import db
from app.models.car import Car
from app.models.tour import Tour
from app.models.hotel import Hotel
import base64

# def fuzzy_filter(items, key, threshold=75):
#         """
#         Filters a list of items using fuzzy matching.

#         :param items: List of objects to filter
#         :param key: Function to extract the string to compare (e.g., lambda x: x.name)
#         :param threshold: Minimum score to consider as a match
#         :return: Filtered list of items
#         """
#         return [
#             item for item in items
#             if fuzz.partial_ratio(query.lower(), key(item).lower()) >= threshold
#         ]

#     # Apply fuzzy matching to refine each list
#     cars = fuzzy_filter(cars, lambda car: car.model)
#     hotels = fuzzy_filter(hotels, lambda hotel: f"{hotel.name} {hotel.city}")
#     tours = fuzzy_filter(tours, lambda tour: f"{tour.title} {tour.from_country} {tour.to_country}")

search_bp = Blueprint('search', __name__, url_prefix='/api/search')

@search_bp.route('/', methods=['GET'])
def universal_search():
    query = request.args.get('query', '').strip()
    if not query:
        return jsonify({'error': 'Query parameter is required'}), 400

    cars = Car.query.filter(Car.model.ilike(f'%{query}%')).all()

    hotels = Hotel.query.filter(
        db.or_(
            Hotel.name.ilike(f'%{query}%'),
            Hotel.city.ilike(f'%{query}%')
        )
    ).all()

    tours = Tour.query.filter(
        db.or_(
            Tour.title.ilike(f'%{query}%'),
            Tour.from_country.ilike(f'%{query}%'),
            Tour.to_country.ilike(f'%{query}%')
        )
    ).all()

    response = {
        'tour': [serialize_tour(tour) for tour in tours],
        'car': [serialize_car(car) for car in cars],
        'hotel': [serialize_hotel(hotel) for hotel in hotels]
    }

    return jsonify(response)


def serialize_car(car):
    return {
        'id': car.id,
        'model': car.model,
        'price': car.price,
        'comment': car.comment,
        'color': car.color,
        'seats': car.seats,
        'fuel_type': car.fuel_type,
        'company_id': car.company_id,
        'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,  
        'insurance': car.insurance,
        'transmission': car.transmission,
        'deposit': car.deposit,
        'year': car.year,
        'climate': car.climate,
        'status': car.status,
        'category': car.category,
        'company': {
            'id': car.company.id,
            'name': car.company.name
        } if car.company else None
    }

def serialize_hotel(hotel):
    return {
        'id': hotel.id,
        'name': hotel.name,
        'city': hotel.city,
        'room_type': hotel.room_type,
        'bed_type': hotel.bed_type,
        'stars': hotel.stars,
        'price_per_night': hotel.price_per_night,
        'wifi': hotel.wifi,
        'air_conditioner': hotel.air_conditioner,
        'location': hotel.location,
        'address': hotel.address,
        'comments': hotel.comments,
        'breakfast': hotel.breakfast,
        'transport': hotel.transport,
        'kitchen': hotel.kitchen,
        'restaurant_bar': hotel.restaurant_bar,
        'swimming_pool': hotel.swimming_pool,
        'gym': hotel.gym,
        'parking': hotel.parking,
        'reviews': hotel.reviews,
        'status': hotel.status,
        'company_id': hotel.company_id,
        'images': [f'/item/hotel_image/{image.id}' for image in hotel.images], 
    }

def serialize_tour(tour):
    return {
        'id': tour.id,
        'title': tour.title,
        'description': tour.description,
        'category': tour.category,
        'from_country': tour.from_country,
        'to_country': tour.to_country,
        'status': tour.status,
        'video_url': tour.video_url,
        'company_id': tour.company_id,
        'images': [f'/item/tour_image/{image.id}' for image in tour.images],
        'departures': [
            {
                'date': departure.departure_date.isoformat() if departure.departure_date else None,
                'price': departure.price if departure.departure_date else None,
            }
            for departure in tour.departures
        ] if tour.departures else []
    }
