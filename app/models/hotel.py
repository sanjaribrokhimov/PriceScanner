from app.models import db 

class HotelImage(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    hotel_id = db.Column(db.Integer, db.ForeignKey('hotel.id'), nullable=False)
    image_data = db.Column(db.LargeBinary, nullable=False)
    hotel = db.relationship('Hotel', back_populates='images')

class Hotel(db.Model):    
    id = db.Column(db.Integer, primary_key=True)
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), default=1)
    name = db.Column(db.String(255), nullable=False) 
    city = db.Column(db.String(255), nullable=False) 
    room_type = db.Column(db.String(255), nullable=True)
    bed_type = db.Column(db.String(255), nullable=True) 
    images = db.relationship('HotelImage', back_populates='hotel', cascade='all, delete-orphan')
    wifi = db.Column(db.Boolean, default=True)
    air_conditioner = db.Column(db.Boolean, default=False)
    price_per_night = db.Column(db.Float, nullable=True) 
    location = db.Column(db.String(255), nullable=True)  
    address = db.Column(db.String(255), nullable=False) 
    comments = db.Column(db.Text, nullable=True)  
    stars = db.Column(db.Integer, nullable=False) 
    breakfast = db.Column(db.Boolean, default=False)
    transport = db.Column(db.Text, default="")
    kitchen = db.Column(db.Boolean, default=False)
    restaurant_bar = db.Column(db.Boolean, default=False)
    swimming_pool = db.Column(db.Boolean, default=False)
    gym = db.Column(db.Boolean, default=False)
    parking = db.Column(db.Boolean, default=False)
    reviews = db.Column(db.Text, nullable=True)
    status = db.Column(db.Text, nullable=True)
    rooms = db.relationship('Room', backref='hotel', cascade='all, delete-orphan')

    def __repr__(self):
        return f'<Hotel {self.name} - {self.city}>'

class Room(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    hotel_id = db.Column(db.Integer, db.ForeignKey('hotel.id'), nullable=False)
    room_type = db.Column(db.String(255), nullable=False)
    capacity = db.Column(db.Integer, nullable=False)
    num_adults = db.Column(db.Integer, nullable=False)
    bed_type = db.Column(db.String(255), nullable=False)
    price_per_night = db.Column(db.Float, nullable=False)
    is_available = db.Column(db.Boolean, default=True)
    features = db.Column(db.Text, nullable=True)
    comments = db.Column(db.Text, nullable=True)
    availabilities = db.relationship('RoomAvailability', backref='room', cascade='all, delete-orphan')

    def __repr__(self):
        return f'<Room {self.room_type} in Hotel {self.hotel_id}>'

    
class RoomAvailability(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    room_id = db.Column(db.Integer, db.ForeignKey('room.id'), nullable=False)
    start_date = db.Column(db.Date, nullable=False)
    end_date = db.Column(db.Date, nullable=False)
    is_available = db.Column(db.Boolean, default=True)

    def __init__(self, room_id, start_date, end_date, is_available):
        self.room_id = room_id
        self.start_date = start_date
        self.end_date = end_date
        self.is_available = is_available