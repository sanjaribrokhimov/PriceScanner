'''
# ============================ADMIN PART============================
from flask import Blueprint, request, jsonify
from flask_jwt_extended import jwt_required, get_jwt_identity
from app.models import db
from app.models.user import User
from app.models.car import Car, CarAvailability, TransmissionType, InsuranceType, ClimateType, CategoryType, FuelType
from app.models.otp import OTP
from app.models.company import Company
import base64
from sqlalchemy import and_, cast, Float
from datetime import datetime
from sqlalchemy import func

rc_bp = Blueprint('rentcar', __name__, url_prefix='/api/rentcar')

@rc_bp.route('/companies/<int:company_id>/cars', methods=['GET'])
@jwt_required()
def list_cars(company_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'Company not found'}), 404

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_cars = Car.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page, error_out=False)

    cars_list = []
    for car in paginated_cars.items:
        availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()  # Filter to only booked
        availability_data = []
        for period in availability_periods:
            availability_data.append({
                "start_date": period.start_date.strftime('%Y-%m-%d'),
                "end_date": period.end_date.strftime('%Y-%m-%d'),
                "is_available": period.is_available,
                "id": period.id
            })
       
        latest_availability = CarAvailability.query.filter_by(car_id=car.id).order_by(CarAvailability.end_date.desc()).first()

        availability_status = "Available" if latest_availability and latest_availability.is_available else "Unavailable" if latest_availability and not latest_availability.is_available else "No data"

        cars_list.append({
            'id': car.id,
            'model': car.model,
            'price': car.price,
            'comment': car.comment,
            'color': car.color,
            'seats': car.seats,
            'fuel_type': car.fuel_type.value,
            'insurance': car.insurance.value,
            'transmission': car.transmission.value,
            'deposit': car.deposit,
            'year': car.year,
            'climate': car.climate.value,
            'category': car.category.value,
            'status': availability_status,
            'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
             'availability': availability_data 
        })

    return jsonify({
        'message': True,
        'company': {
            'id': company.id,
            'name': company.name,
        },
        'cars': cars_list,
        'total': paginated_cars.total,
        'pages': paginated_cars.pages,
        'current_page': paginated_cars.page,
        'per_page': paginated_cars.per_page
    }), 200

@rc_bp.route('/companies/<int:company_id>/cars', methods=['POST'])
@jwt_required()
def add_car(company_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'Company not found'}), 404

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
        try:
            image_file = request.files.get('image')
            image_data = None
            if image_file:
                image_data = image_file.read()
            new_car = Car(
                model=request.form.get("model"),
                price=float(request.form.get("price")),
                comment=request.form.get("comment"),
                color=request.form.get("color"),
                seats=int(request.form.get("seats")),
                fuel_type=FuelType(request.form.get("fuel_type")),
                company_id=company.id,
                insurance=InsuranceType(request.form.get("insurance")),
                transmission=TransmissionType(request.form.get("transmission")),
                deposit=float(request.form.get("deposit")),
                year=int(request.form.get("year")),
                climate=ClimateType(request.form.get("climate")),
                image=image_data,
                category=CategoryType(request.form.get("category")),
            )
            db.session.add(new_car)
            db.session.commit()
            return jsonify({"message": "Car added successfully"}), 201
        except (KeyError, ValueError) as e:
            return jsonify({"error": f"Missing field {e} or invalid data: {e}"}), 400
    else:
        return jsonify({"error": "Request must be JSON"}), 400



@rc_bp.route('/companies/<int:company_id>/cars/<int:car_id>', methods=['PUT'])
@jwt_required()
def edit_car(company_id, car_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'Company not found'}), 404

    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
        try:
            car.model = request.form.get('model', car.model)
            car.price = float(request.form.get('price', car.price))
            car.comment = request.form.get('comment', car.comment)
            car.color = request.form.get('color', car.color)
            car.seats = int(request.form.get('seats', car.seats))

            fuel_type = request.form.get('fuel_type')
            if fuel_type:
                car.fuel_type = FuelType(fuel_type)

            insurance = request.form.get('insurance')
            if insurance:
                 car.insurance = InsuranceType(insurance)

            transmission = request.form.get('transmission')
            if transmission:
                car.transmission = TransmissionType(transmission)

            car.deposit = float(request.form.get('deposit', car.deposit))
            car.year = int(request.form.get('year', car.year))

            climate = request.form.get('climate')
            if climate:
                car.climate = ClimateType(climate)
                
            category = request.form.get('category')
            if category:
                car.category = CategoryType(category)


            image_file = request.files.get('image')
            if image_file:
                image_data = image_file.read()
                car.image = image_data
            
            db.session.commit()
            return jsonify({'message': 'Car updated successfully'}), 200
        except (KeyError, ValueError) as e:
            return jsonify({"error": f"Missing field {e} or invalid data: {e}"}), 400
    else:
       return jsonify({"error":"Request must be multipart form data"})
    

@rc_bp.route('/availability/cars/<int:car_id>/', methods=['POST'])
@jwt_required()
def set_car_availability(car_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404

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

            new_availability = CarAvailability(
                car_id=car.id,
                start_date=start_date,
                end_date=end_date,
                is_available=is_available
            )
            db.session.add(new_availability)
            db.session.commit()

            return jsonify({'message': 'Car availability updated'}), 200
        except Exception as e:
           return jsonify({"error": f"An error occurred {e}"}), 400
    else:
       return jsonify({"error":"Request must be multipart form data"})

@rc_bp.route('/availability/cars/<int:car_id>/<int:availability_id>', methods=['PUT'])
@jwt_required()
def update_car_availability(car_id, availability_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    car = Car.query.filter_by(id=car_id).first()
    if not car:
      return jsonify({'message': 'Car not found'}), 404

    availability = CarAvailability.query.filter_by(id=availability_id, car_id=car_id).first()
    if not availability:
      return jsonify({'message': 'Availability record not found'}), 404

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
       try:
            start_date_str = request.form.get("start_date")
            end_date_str = request.form.get("end_date")
            is_available = request.form.get("is_available")
            
            if not all([start_date_str, end_date_str, is_available != None]):
              return jsonify({"message":"Missing Data fields"}), 400
            
            try:
              start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
              end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()
              is_available = bool(int(is_available))

            except ValueError:
              return jsonify({"message":"Invalid Date or Boolean format, should be YYYY-MM-DD and '0' or '1' "}) , 400

            availability.start_date = start_date
            availability.end_date = end_date
            availability.is_available = is_available
            db.session.commit()

            return jsonify({"message":"Availability record successfully updated"}), 200
       except Exception as e:
          return jsonify({"error": f"An error occurred {e}"}), 400
    else:
       return jsonify({"error":"Request must be multipart form data"})

@rc_bp.route('/companies/<int:company_id>/cars/<int:car_id>', methods=['GET'])
@jwt_required()
def get_car_info(company_id, car_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'No company found for the car with id: ' + str(car.id)}), 404
    
    availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()  
    availability_data = []
    for period in availability_periods:
        availability_data.append({
            "start_date": period.start_date.strftime('%Y-%m-%d'),
            "end_date": period.end_date.strftime('%Y-%m-%d'),
            "is_available": period.is_available,
            "id": period.id
        })


    latest_availability = CarAvailability.query.filter_by(car_id=car.id).order_by(CarAvailability.end_date.desc()).first()
    availability_status = "Available" if latest_availability and latest_availability.is_available else "Unavailable" if latest_availability and not latest_availability.is_available else "No data"

    car_info = {
        'id': car.id,
        'model': car.model,
        'price': car.price,
        'comment': car.comment,
        'color': car.color,
        'seats': car.seats,
        'fuel_type': car.fuel_type.value,
        'insurance': car.insurance.value,
        'transmission': car.transmission.value,
        'deposit': car.deposit,
        'year': car.year,
        'climate': car.climate.value,
        'category': car.category.value,
        'status': availability_status,
        'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
        'availability': availability_data 
    }

    return jsonify({
        'message': True,
        'company': {
            'id': company.id,
            'name': company.name,
        },
        'car': car_info
    }), 200


@rc_bp.route('/companies/<int:company_id>/cars/<int:car_id>', methods=['DELETE'])
@jwt_required()
def delete_car(company_id,car_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'No company found for the car with id: ' + str(car.id)}), 404

    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404

    db.session.delete(car)
    db.session.commit()

    return jsonify({'message': 'Car successfully deleted'}), 200

# ============================WEB MAIN PART============================
@rc_bp.route('/companies/web_main/all_cars', methods=['GET'])
def list_allcars():
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_cars = db.session.query(Car, Company).join(Company, Car.company_id == Company.id).paginate(
        page=page, per_page=per_page, error_out=False)

    cars_list = []
    for car, company in paginated_cars.items:
        availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()
        availability_data = []
        for period in availability_periods:
            availability_data.append({
                "start_date": period.start_date.strftime('%Y-%m-%d'),
                "end_date": period.end_date.strftime('%Y-%m-%d'),
                "is_available": period.is_available
            })
        cars_list.append({
            'id': car.id,
            'model': car.model,
            'price': car.price,
            'comment': car.comment,
            'color': car.color,
            'seats': car.seats,
            'fuel_type': car.fuel_type.value,
            'insurance': car.insurance.value,
            'transmission': car.transmission.value,
            'deposit': car.deposit,
            'year': car.year,
            'climate': car.climate.value,
            'category': car.category.value,
            'status': "Available",  
            'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
            'company': {
                'id': company.id,
                'name': company.name
            },
            'availability': availability_data
        })

    return jsonify({
        'message': True,
        'cars': cars_list,
        'total': paginated_cars.total,
        'pages': paginated_cars.pages,
        'current_page': paginated_cars.page,
        'per_page': paginated_cars.per_page
    }), 200

@rc_bp.route('/companies/web_main/car/<int:car_id>', methods=['GET'])
def web_main_get_car_info(car_id):
    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404
    company = Company.query.filter_by(id=car.company_id).first()
    if not company:
        return jsonify({'message': 'Company not found'}), 404
    
    availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()
    availability_data = []
    for period in availability_periods:
        availability_data.append({
            "start_date": period.start_date.strftime('%Y-%m-%d'),
            "end_date": period.end_date.strftime('%Y-%m-%d'),
            "is_available": period.is_available
        })
    
    latest_availability = CarAvailability.query.filter_by(car_id=car.id).order_by(CarAvailability.end_date.desc()).first()
    availability_status = "Available" if latest_availability and latest_availability.is_available else "Unavailable" if latest_availability and not latest_availability.is_available else "No data"

    car_info = {
        'id': car.id,
        'model': car.model,
        'price': car.price,
        'comment': car.comment,
        'color': car.color,
        'seats': car.seats,
        'fuel_type': car.fuel_type.value,
        'insurance': car.insurance.value,
        'transmission': car.transmission.value,
        'deposit': car.deposit,
        'year': car.year,
        'climate': car.climate.value,
        'category': car.category.value,
        'status': availability_status,
        'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
        'availability': availability_data  
    }

    return jsonify({
        'message': True,
        'company': {
            'id': company.id,
            'name': company.name,
        },
        'car': car_info
    }), 200

@rc_bp.route('/companies/web_main/cars-filtered', methods=['GET'])
def get_rentable_cars():
    filters = []

    model = request.args.get('model')
    if model:
        filters.append(func.lower(Car.model).contains(func.lower(model)))

    min_price = request.args.get('min_price', type=float)
    max_price = request.args.get('max_price', type=float)
    if min_price is not None:
        filters.append(Car.price >= min_price)
    if max_price is not None:
        filters.append(Car.price <= max_price)

    color = request.args.get('color')
    if color:
         filters.append(func.lower(Car.color) == func.lower(color))

    seats = request.args.get('seats', type=int)
    if seats:
        filters.append(Car.seats == seats)

    fuel_type = request.args.get('fuel_type')
    if fuel_type:
        filters.append(func.lower(Car.fuel_type) == func.lower(fuel_type))

    transmission = request.args.get('transmission')
    if transmission:
        filters.append(func.lower(Car.transmission) == func.lower(transmission))

    climate = request.args.get('climate')
    if climate:
       filters.append(func.lower(Car.climate) == func.lower(climate))

    insurance = request.args.get('insurance')
    if insurance is not None:
        insurance = bool(int(insurance))
        filters.append(Car.insurance == insurance)

    start_date_str = request.args.get("start_date")
    end_date_str = request.args.get("end_date")

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    # Build the main query
    base_query = db.session.query(Car, Company).join(Company, Car.company_id == Company.id)
    
    if start_date_str and end_date_str:
      try:
        start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
        end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()

        #Subquery to find car ids NOT available in the given range
        unavailable_car_ids = db.session.query(CarAvailability.car_id).filter(
             CarAvailability.is_available == False,
             CarAvailability.start_date <= end_date,
             CarAvailability.end_date >= start_date
           ).distinct().subquery()

        #Filter base query to only include cars NOT in the subquery
        base_query = base_query.filter(Car.id.notin_(unavailable_car_ids))

      except ValueError:
            return jsonify({"message": "Invalid Date Format, Should be YYYY-MM-DD"}), 400


    # Apply all filters
    paginated_cars = base_query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)

    cars_list = []
    for car, company in paginated_cars.items:
      # Fetch the availability periods for each car
      availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()  # Filter to only booked
      availability_data = []
      for period in availability_periods:
         availability_data.append({
            "start_date": period.start_date.strftime('%Y-%m-%d'),
            "end_date": period.end_date.strftime('%Y-%m-%d'),
            "is_available": period.is_available
         })


      # Get the latest availability for display
      latest_availability = CarAvailability.query.filter_by(car_id=car.id).order_by(CarAvailability.end_date.desc()).first()
      availability_status = "Available" if latest_availability and latest_availability.is_available else "Unavailable" if latest_availability and not latest_availability.is_available else "No data"

      cars_list.append({
        'id': car.id,
        'model': car.model,
        'price': car.price,
        'comment': car.comment,
        'color': car.color,
        'seats': car.seats,
        'fuel_type': car.fuel_type.value,
        'insurance': car.insurance.value,
        'transmission': car.transmission.value,
        'deposit': car.deposit,
        'year': car.year,
        'climate': car.climate.value,
        'status': availability_status,
        'category': car.category.value,
        'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
        'company': {
            'id': company.id,
            'name': company.name
         },
         'availability': availability_data  # Include booked periods
       })


    return jsonify({
        'message': True,
        'cars': cars_list,
        'total': paginated_cars.total,
        'pages': paginated_cars.pages,
        'current_page': paginated_cars.page,
        'per_page': paginated_cars.per_page
    }), 200
'''
# =====================version 3====================
# ============================ADMIN PART============================
from flask import Blueprint, request, jsonify
from flask_jwt_extended import jwt_required, get_jwt_identity
from app.models import db
from app.models.user import User
from app.models.car import Car, CarAvailability, TransmissionType, InsuranceType, ClimateType, CategoryType, FuelType
from app.models.otp import OTP
from app.models.company import Company
import base64
from sqlalchemy import and_, cast, Float
from datetime import datetime
from sqlalchemy import func

rc_bp = Blueprint('rentcar', __name__, url_prefix='/api/rentcar')

@rc_bp.route('/companies/<int:company_id>/cars', methods=['GET'])
@jwt_required()
def list_cars(company_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'Company not found'}), 404

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_cars = Car.query.filter_by(company_id=company_id).paginate(page=page, per_page=per_page, error_out=False)

    cars_list = []
    for car in paginated_cars.items:
        availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()  # Filter to only booked
        availability_data = []
        for period in availability_periods:
            availability_data.append({
                "start_date": period.start_date.strftime('%Y-%m-%d'),
                "end_date": period.end_date.strftime('%Y-%m-%d'),
                "is_available": period.is_available,
                "id": period.id
            })
        
        cars_list.append({
            'id': car.id,
            'model': car.model,
            'price': car.price,
            'comment': car.comment,
            'color': car.color,
            'seats': car.seats,
            'fuel_type': car.fuel_type.value,
            'insurance': car.insurance.value,
            'transmission': car.transmission.value,
            'deposit': car.deposit,
            'year': car.year,
            'climate': car.climate.value,
            'category': car.category.value,
            'status': car.status,
            'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
             'availability': availability_data 
        })

    return jsonify({
        'message': True,
        'company': {
            'id': company.id,
            'name': company.name,
        },
        'cars': cars_list,
        'total': paginated_cars.total,
        'pages': paginated_cars.pages,
        'current_page': paginated_cars.page,
        'per_page': paginated_cars.per_page
    }), 200

@rc_bp.route('/companies/<int:company_id>/cars', methods=['POST'])
@jwt_required()
def add_car(company_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'Company not found'}), 404

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
        try:
            image_file = request.files.get('image')
            image_data = None
            if image_file:
                image_data = image_file.read()
            new_car = Car(
                model=request.form.get("model"),
                price=float(request.form.get("price")),
                comment=request.form.get("comment"),
                color=request.form.get("color"),
                seats=int(request.form.get("seats")),
                fuel_type=FuelType(request.form.get("fuel_type")),
                company_id=company.id,
                insurance=InsuranceType(request.form.get("insurance")),
                transmission=TransmissionType(request.form.get("transmission")),
                deposit=float(request.form.get("deposit")),
                year=int(request.form.get("year")),
                climate=ClimateType(request.form.get("climate")),
                image=image_data,
                category=CategoryType(request.form.get("category")),
            )
            db.session.add(new_car)
            db.session.commit()
            return jsonify({"message": "Car added successfully"}), 201
        except (KeyError, ValueError) as e:
            return jsonify({"error": f"Missing field {e} or invalid data: {e}"}), 400
    else:
        return jsonify({"error": "Request must be JSON"}), 400



@rc_bp.route('/companies/<int:company_id>/cars/<int:car_id>', methods=['PUT'])
@jwt_required()
def edit_car(company_id, car_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'Company not found'}), 404

    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
        try:
            car.model = request.form.get('model', car.model)
            car.price = float(request.form.get('price', car.price))
            car.comment = request.form.get('comment', car.comment)
            car.color = request.form.get('color', car.color)
            car.seats = int(request.form.get('seats', car.seats))

            fuel_type = request.form.get('fuel_type')
            if fuel_type:
                car.fuel_type = FuelType(fuel_type)

            insurance = request.form.get('insurance')
            if insurance:
                 car.insurance = InsuranceType(insurance)

            transmission = request.form.get('transmission')
            if transmission:
                car.transmission = TransmissionType(transmission)

            car.deposit = float(request.form.get('deposit', car.deposit))
            car.year = int(request.form.get('year', car.year))

            climate = request.form.get('climate')
            if climate:
                car.climate = ClimateType(climate)
                
            category = request.form.get('category')
            if category:
                car.category = CategoryType(category)
            
            status = request.form.get('status')
            if status is not None:
                car.status = bool(int(status))


            image_file = request.files.get('image')
            if image_file:
                image_data = image_file.read()
                car.image = image_data
            
            db.session.commit()
            return jsonify({'message': 'Car updated successfully'}), 200
        except (KeyError, ValueError) as e:
            return jsonify({"error": f"Missing field {e} or invalid data: {e}"}), 400
    else:
       return jsonify({"error":"Request must be multipart form data"})
    

@rc_bp.route('/availability/cars/<int:car_id>/', methods=['POST'])
@jwt_required()
def set_car_availability(car_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404

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

            new_availability = CarAvailability(
                car_id=car.id,
                start_date=start_date,
                end_date=end_date,
                is_available=is_available
            )
            db.session.add(new_availability)
            db.session.commit()


            return jsonify({'message': 'Car availability updated'}), 200
        except Exception as e:
           return jsonify({"error": f"An error occurred {e}"}), 400
    else:
       return jsonify({"error":"Request must be multipart form data"})

@rc_bp.route('/availability/cars/<int:car_id>/<int:availability_id>', methods=['PUT'])
@jwt_required()
def update_car_availability(car_id, availability_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    car = Car.query.filter_by(id=car_id).first()
    if not car:
      return jsonify({'message': 'Car not found'}), 404

    availability = CarAvailability.query.filter_by(id=availability_id, car_id=car_id).first()
    if not availability:
      return jsonify({'message': 'Availability record not found'}), 404

    if request.content_type.startswith('multipart/form-data') or request.content_type == 'application/x-www-form-urlencoded':
       try:
            start_date_str = request.form.get("start_date")
            end_date_str = request.form.get("end_date")
            is_available = request.form.get("is_available")
            
            if not all([start_date_str, end_date_str, is_available != None]):
              return jsonify({"message":"Missing Data fields"}), 400
            
            try:
              start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
              end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()
              is_available = bool(int(is_available))

            except ValueError:
              return jsonify({"message":"Invalid Date or Boolean format, should be YYYY-MM-DD and '0' or '1' "}) , 400

            availability.start_date = start_date
            availability.end_date = end_date
            availability.is_available = is_available
            db.session.commit()
            
            return jsonify({"message":"Availability record successfully updated"}), 200
       except Exception as e:
          return jsonify({"error": f"An error occurred {e}"}), 400
    else:
       return jsonify({"error":"Request must be multipart form data"})

@rc_bp.route('/companies/<int:company_id>/cars/<int:car_id>', methods=['GET'])
@jwt_required()
def get_car_info(company_id, car_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': 'Access denied'}), 403

    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'No company found for the car with id: ' + str(car.id)}), 404
    
    availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()  
    availability_data = []
    for period in availability_periods:
        availability_data.append({
            "start_date": period.start_date.strftime('%Y-%m-%d'),
            "end_date": period.end_date.strftime('%Y-%m-%d'),
            "is_available": period.is_available,
            "id": period.id
        })

    car_info = {
        'id': car.id,
        'model': car.model,
        'price': car.price,
        'comment': car.comment,
        'color': car.color,
        'seats': car.seats,
        'fuel_type': car.fuel_type.value,
        'insurance': car.insurance.value,
        'transmission': car.transmission.value,
        'deposit': car.deposit,
        'year': car.year,
        'climate': car.climate.value,
        'category': car.category.value,
        'status': car.status,
        'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
        'availability': availability_data 
    }

    return jsonify({
        'message': True,
        'company': {
            'id': company.id,
            'name': company.name,
        },
        'car': car_info
    }), 200


@rc_bp.route('/companies/<int:company_id>/cars/<int:car_id>', methods=['DELETE'])
@jwt_required()
def delete_car(company_id,car_id):
    current_user_id = get_jwt_identity()
    user = User.query.get(current_user_id)

    if not user:
        return jsonify({'message': 'User not found'}), 404

    if user.role not in ["admin", "partner"]:
        return jsonify({'message': False}), 403

    company = Company.query.get(company_id)
    if not company:
        return jsonify({'message': 'No company found for the car with id: ' + str(car.id)}), 404

    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404

    db.session.delete(car)
    db.session.commit()

    return jsonify({'message': 'Car successfully deleted'}), 200

# ============================WEB MAIN PART============================
@rc_bp.route('/companies/web_main/all_cars', methods=['GET'])
def list_allcars():
    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    paginated_cars = db.session.query(Car, Company).join(Company, Car.company_id == Company.id).paginate(
        page=page, per_page=per_page, error_out=False)

    cars_list = []
    for car, company in paginated_cars.items:
        availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()
        availability_data = []
        for period in availability_periods:
            availability_data.append({
                "start_date": period.start_date.strftime('%Y-%m-%d'),
                "end_date": period.end_date.strftime('%Y-%m-%d'),
                "is_available": period.is_available
            })
        

        cars_list.append({
            'id': car.id,
            'model': car.model,
            'price': car.price,
            'comment': car.comment,
            'color': car.color,
            'seats': car.seats,
            'fuel_type': car.fuel_type.value,
            'insurance': car.insurance.value,
            'transmission': car.transmission.value,
            'deposit': car.deposit,
            'year': car.year,
            'climate': car.climate.value,
            'category': car.category.value,
            'status': car.status,  
            'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
            'company': {
                'id': company.id,
                'name': company.name
            },
            'availability': availability_data
        })

    return jsonify({
        'message': True,
        'cars': cars_list,
        'total': paginated_cars.total,
        'pages': paginated_cars.pages,
        'current_page': paginated_cars.page,
        'per_page': paginated_cars.per_page
    }), 200

@rc_bp.route('/companies/web_main/car/<int:car_id>', methods=['GET'])
def web_main_get_car_info(car_id):
    car = Car.query.filter_by(id=car_id).first()
    if not car:
        return jsonify({'message': 'Car not found'}), 404
    company = Company.query.filter_by(id=car.company_id).first()
    if not company:
        return jsonify({'message': 'Company not found'}), 404
    
    availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()
    availability_data = []
    for period in availability_periods:
        availability_data.append({
            "start_date": period.start_date.strftime('%Y-%m-%d'),
            "end_date": period.end_date.strftime('%Y-%m-%d'),
            "is_available": period.is_available
        })
    
    
    car_info = {
        'id': car.id,
        'model': car.model,
        'price': car.price,
        'comment': car.comment,
        'color': car.color,
        'seats': car.seats,
        'fuel_type': car.fuel_type.value,
        'insurance': car.insurance.value,
        'transmission': car.transmission.value,
        'deposit': car.deposit,
        'year': car.year,
        'climate': car.climate.value,
        'category': car.category.value,
        'status': car.status,
        'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
        'availability': availability_data  
    }

    return jsonify({
        'message': True,
        'company': {
            'id': company.id,
            'name': company.name,
        },
        'car': car_info
    }), 200

@rc_bp.route('/companies/web_main/cars-filtered', methods=['GET'])
def get_rentable_cars():
    filters = []

    model = request.args.get('model')
    if model:
        filters.append(func.lower(Car.model).contains(func.lower(model)))

    min_price = request.args.get('min_price', type=float)
    max_price = request.args.get('max_price', type=float)
    if min_price is not None:
        filters.append(Car.price >= min_price)
    if max_price is not None:
        filters.append(Car.price <= max_price)

    color = request.args.get('color')
    if color:
         filters.append(func.lower(Car.color) == func.lower(color))

    seats = request.args.get('seats', type=int)
    if seats:
        filters.append(Car.seats == seats)

    fuel_type = request.args.get('fuel_type')
    if fuel_type:
        filters.append(func.lower(Car.fuel_type) == func.lower(fuel_type))

    transmission = request.args.get('transmission')
    if transmission:
        filters.append(func.lower(Car.transmission) == func.lower(transmission))

    climate = request.args.get('climate')
    if climate:
       filters.append(func.lower(Car.climate) == func.lower(climate))

    insurance = request.args.get('insurance')
    if insurance is not None:
        insurance = bool(int(insurance))
        filters.append(Car.insurance == insurance)

    start_date_str = request.args.get("start_date")
    end_date_str = request.args.get("end_date")

    page = request.args.get('page', 1, type=int)
    per_page = request.args.get('per_page', 10, type=int)

    # Build the main query
    base_query = db.session.query(Car, Company).join(Company, Car.company_id == Company.id)
    
    if start_date_str and end_date_str:
      try:
        start_date = datetime.strptime(start_date_str, '%Y-%m-%d').date()
        end_date = datetime.strptime(end_date_str, '%Y-%m-%d').date()

        #Subquery to find car ids NOT available in the given range
        unavailable_car_ids = db.session.query(CarAvailability.car_id).filter(
             CarAvailability.is_available == False,
             CarAvailability.start_date <= end_date,
             CarAvailability.end_date >= start_date
           ).distinct().subquery()

        #Filter base query to only include cars NOT in the subquery
        base_query = base_query.filter(Car.id.notin_(unavailable_car_ids))

      except ValueError:
            return jsonify({"message": "Invalid Date Format, Should be YYYY-MM-DD"}), 400


    # Apply all filters
    filters.append(Car.status == True) # ADDED FILTER FOR STATUS
    paginated_cars = base_query.filter(and_(*filters)).paginate(page=page, per_page=per_page, error_out=False)

    cars_list = []
    for car, company in paginated_cars.items:
      # Fetch the availability periods for each car
      availability_periods = CarAvailability.query.filter_by(car_id=car.id, is_available=False).all()  # Filter to only booked
      availability_data = []
      for period in availability_periods:
         availability_data.append({
            "start_date": period.start_date.strftime('%Y-%m-%d'),
            "end_date": period.end_date.strftime('%Y-%m-%d'),
            "is_available": period.is_available
         })
      
      cars_list.append({
        'id': car.id,
        'model': car.model,
        'price': car.price,
        'comment': car.comment,
        'color': car.color,
        'seats': car.seats,
        'fuel_type': car.fuel_type.value,
        'insurance': car.insurance.value,
        'transmission': car.transmission.value,
        'deposit': car.deposit,
        'year': car.year,
        'climate': car.climate.value,
        'status': car.status,
        'category': car.category.value,
        'image': base64.b64encode(car.image).decode('utf-8') if car.image else None,
        'company': {
            'id': company.id,
            'name': company.name
         },
         'availability': availability_data  # Include booked periods
       })


    return jsonify({
        'message': True,
        'cars': cars_list,
        'total': paginated_cars.total,
        'pages': paginated_cars.pages,
        'current_page': paginated_cars.page,
        'per_page': paginated_cars.per_page
    }), 200