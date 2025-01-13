from app.models import db
from datetime import datetime

class Rating(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), nullable=False)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    order_id = db.Column(db.Integer, nullable=True)  
    stars = db.Column(db.Integer, nullable=False)
    comment = db.Column(db.Text, nullable=True)
    created_at = db.Column(db.DateTime, default=db.func.current_timestamp(), nullable=False)
    updated_at = db.Column(db.DateTime, default=db.func.current_timestamp(), onupdate=db.func.current_timestamp(), nullable=False)
    
    company = db.relationship('Company', backref=db.backref('ratings', lazy=True))
    user = db.relationship('User', backref=db.backref('ratings', lazy=True))

    def __init__(self, company_id, user_id, stars, comment=None, order_id=None):
        self.company_id = company_id
        self.user_id = user_id
        self.stars = stars
        self.comment = comment
        self.order_id = order_id

    def __repr__(self):
        return f"<Rating id={self.id}, company_id={self.company_id}, user_id={self.user_id}, stars={self.stars}>"

    @staticmethod
    def validate_stars(stars):
        if not isinstance(stars, int):
            raise ValueError("Stars must be an integer.")
        if not 1 <= stars <= 10:
            raise ValueError("Stars must be between 1 and 10.")
        return stars