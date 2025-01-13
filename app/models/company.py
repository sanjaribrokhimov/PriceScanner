from app.models import db

class Company(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    legal_name = db.Column(db.String(512), nullable=False)
    name = db.Column(db.String(256), nullable=False)
    category = db.Column(db.String(256), nullable=False)
    city = db.Column(db.String(128), nullable=False)         
    district = db.Column(db.String(128), nullable=False)         
    address = db.Column(db.String(256), nullable=False)
    logo = db.Column(db.LargeBinary)

def create_company_instance():
    company_exists = Company.query.first()
    
    if not company_exists:
        company = Company(
            legal_name="smt24",
            name="SMT24",
            category="Services",
            city="Tashkent",
            district="Mirabad",
            address="Mirabad 0000",
            logo=None 
        )
        db.session.add(company)
        db.session.commit()