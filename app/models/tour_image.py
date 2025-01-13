from app.models import db

class TourImage(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    tour_id = db.Column(db.Integer, db.ForeignKey('tour.id'), nullable=False)
    image_data = db.Column(db.LargeBinary, nullable=False)
    tour = db.relationship('Tour', back_populates='images')
