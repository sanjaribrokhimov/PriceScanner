from flask import Flask
from flask_jwt_extended import JWTManager
from app.config import Config
from app.models import db
from app.models.car import Car
from flask_migrate import Migrate
from flask_cors import CORS

def create_app():
    app = Flask(__name__)
    app.config.from_object(Config)

    db.init_app(app)
    migrate = Migrate(app, db) 

    JWTManager(app)

    from app.controllers.auth import auth_bp
    from app.controllers.admin import admin_bp
    from app.controllers.rent_car_admin import rc_bp
    from app.controllers.tour import tour_bp
    from app.controllers.hotel import hotel_bp
    from app.controllers.search import search_bp
    from app.controllers.like import cart_bp
    from app.controllers.orders import order_bp
    from app.controllers.rating import rating_bp
    CORS(rating_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    CORS(order_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    CORS(cart_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    CORS(auth_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    CORS(admin_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    CORS(rc_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    CORS(tour_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    CORS(hotel_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    CORS(search_bp, resources={r"/*": {"origins": "*", "methods": ["GET", "POST", "PUT", "DELETE"], "allow_headers": ["Content-Type", "Authorization"]}})
    app.register_blueprint(rating_bp)
    app.register_blueprint(order_bp)
    app.register_blueprint(cart_bp)
    app.register_blueprint(search_bp)
    app.register_blueprint(auth_bp)
    app.register_blueprint(admin_bp)
    app.register_blueprint(rc_bp)
    app.register_blueprint(tour_bp)
    app.register_blueprint(hotel_bp)

    return app
