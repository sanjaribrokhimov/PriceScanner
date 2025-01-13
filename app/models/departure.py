from app.models import db

class Departure(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    tour_id = db.Column(db.Integer, db.ForeignKey('tour.id'), nullable=False)
    departure_date = db.Column(db.DateTime, nullable=False)
    price = db.Column(db.Float, nullable=False)

    tour = db.relationship('Tour', back_populates='departures')