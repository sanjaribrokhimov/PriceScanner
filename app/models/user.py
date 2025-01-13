from werkzeug.security import generate_password_hash, check_password_hash
from app.models import db
from datetime import datetime

class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    first_name = db.Column(db.String(128), nullable=False)
    last_name = db.Column(db.String(128), nullable=False)
    email = db.Column(db.String(80), unique=True, nullable=False)
    phone_number = db.Column(db.String(32), unique=True, nullable=True) 
    city = db.Column(db.String(128), nullable=True)         
    status = db.Column(db.Boolean, default=True, nullable=False)
    role = db.Column(db.String(32), default='client', nullable=False)
    category = db.Column(db.String(64), default='client', nullable=False)
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), default=1)
    password_hash = db.Column(db.String(512), nullable=False)

    cart = db.relationship('Cart', back_populates='user') 
    def set_password(self, password):
        self.password_hash = generate_password_hash(password)

    def check_password(self, password):
        return check_password_hash(self.password_hash, password)

class UserVisit(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    visit_time = db.Column(db.DateTime, default=datetime.utcnow, nullable=False)

    user = db.relationship('User', backref=db.backref('visits', lazy=True))