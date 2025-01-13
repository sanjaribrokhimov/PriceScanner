from app.models import db

class OTP(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    email = db.Column(db.String(120), unique=True, nullable=False)
    otp = db.Column(db.String(6), nullable=False)
    expiration_time = db.Column(db.DateTime, nullable=False)

    def __init__(self, email, otp, expiration_time):
        self.email = email
        self.otp = otp
        self.expiration_time = expiration_time