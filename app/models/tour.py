from app.models import db

class Tour(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), default=1)
    title = db.Column(db.String(512), nullable=False)
    description = db.Column(db.Text, nullable=False)
    category = db.Column(db.String(512), nullable=False)
    from_country = db.Column(db.String(512), nullable=False) 
    to_country = db.Column(db.String(512), nullable=False)
    status = db.Column(db.String(128), nullable=False, default='active')
    video_url = db.Column(db.Text)

    images = db.relationship('TourImage', back_populates='tour', cascade='all, delete-orphan')
    departures = db.relationship('Departure', back_populates='tour', cascade='all, delete-orphan')