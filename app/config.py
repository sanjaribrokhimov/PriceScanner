import os

class Config:
    SECRET_KEY = "nothing"
    JWT_SECRET_KEY = "nothing"
    # SQLALCHEMY_DATABASE_URI = os.environ.get('DATABASE_URL')
    SQLALCHEMY_DATABASE_URI = "postgresql://postgres:jkha@localhost/smt24"
    SQLALCHEMY_TRACK_MODIFICATIONS = False
