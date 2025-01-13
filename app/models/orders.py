from sqlalchemy.schema import Sequence
from app.models import db

class RentCarOrder(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    counter = db.Column(
        db.Integer, 
        Sequence('rent_car_order_id_seq', start=1, increment=1), 
        unique=True, 
        nullable=False
    )
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), nullable=False)
    car_id = db.Column(db.Integer, db.ForeignKey('car.id'), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    telephone = db.Column(db.String(20), nullable=False)
    name = db.Column(db.String(128), nullable=False)
    surname = db.Column(db.String(128), nullable=False)
    status = db.Column(db.String(128), nullable=False, default="IN PROGRESS")
    created_at = db.Column(db.DateTime, default=db.func.current_timestamp(), nullable=False)
    updated_at = db.Column(db.DateTime, default=db.func.current_timestamp(), onupdate=db.func.current_timestamp(), nullable=False)
    has_seen = db.Column(db.Boolean, default=False, nullable=False) 
    comment = db.Column(db.Text, nullable=True)

    car = db.relationship('Car', backref=db.backref('rent_orders', lazy=True))
    company = db.relationship('Company', backref=db.backref('rent_orders', lazy=True))
    user = db.relationship('User', backref=db.backref('rent_orders', lazy=True))

class TourOrder(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    counter = db.Column(
        db.Integer, 
        Sequence('tour_order_id_seq', start=1, increment=1), 
        unique=True, 
        nullable=False
    )
    tour_id = db.Column(db.Integer, db.ForeignKey('tour.id'), nullable=False)
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    telephone = db.Column(db.String(20), nullable=False)
    name = db.Column(db.String(128), nullable=False)
    surname = db.Column(db.String(128), nullable=False)
    status = db.Column(
        db.String(128), 
        nullable=False, 
        default="IN PROGRESS"
    )
    created_at = db.Column(
        db.DateTime, 
        default=db.func.current_timestamp(), 
        nullable=False
    )
    updated_at = db.Column(
        db.DateTime, 
        default=db.func.current_timestamp(), 
        onupdate=db.func.current_timestamp(), 
        nullable=False
    )
    has_seen = db.Column(db.Boolean, default=False, nullable=False) 
    comment = db.Column(db.Text, nullable=True)

    tour = db.relationship('Tour', backref=db.backref('tour_orders', lazy=True))
    company = db.relationship('Company', backref=db.backref('tour_orders', lazy=True))
    user = db.relationship('User', backref=db.backref('tour_orders', lazy=True))

class HotelOrder(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    counter = db.Column(
        db.Integer, 
        Sequence('hotel_order_id_seq', start=1, increment=1), 
        unique=True, 
        nullable=False
    )
    hotel_id = db.Column(db.Integer, db.ForeignKey('hotel.id'), nullable=False)
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    telephone = db.Column(db.String(20), nullable=False)
    name = db.Column(db.String(128), nullable=False)
    surname = db.Column(db.String(128), nullable=False)
    status = db.Column(
        db.String(128), 
        nullable=False, 
        default="IN PROGRESS"
    )
    created_at = db.Column(
        db.DateTime, 
        default=db.func.current_timestamp(), 
        nullable=False
    )
    updated_at = db.Column(
        db.DateTime, 
        default=db.func.current_timestamp(), 
        onupdate=db.func.current_timestamp(), 
        nullable=False
    )
    has_seen = db.Column(db.Boolean, default=False, nullable=False)  # Added has_seen
    comment = db.Column(db.Text, nullable=True)
    num_adults = db.Column(db.Integer, nullable=False)  # Add num_adults field
    num_kids = db.Column(db.Integer, nullable=False)   # Add num_kids field
    room_capacity = db.Column(db.Integer, nullable=False) # Add room_capacity field
    room_type = db.Column(db.String(128), nullable=False)  # Add room_type field
    bed_type = db.Column(db.String(128), nullable=False)   # Add bed_type field

    hotel = db.relationship('Hotel', backref=db.backref('hotel_orders', lazy=True))
    company = db.relationship('Company', backref=db.backref('hotel_orders', lazy=True))
    user = db.relationship('User', backref=db.backref('hotel_orders', lazy=True))



    