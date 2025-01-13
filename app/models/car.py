# #=======================VERSION2 ADDED CALENDAR=======================
# from app.models import db
# import enum

# class InsuranceType(enum.Enum):
#     BASIC = "Basic"
#     PREMIUM = "Premium"
#     FULL = "Full"

# class TransmissionType(enum.Enum):
#     MANUAL = "Manual"
#     AUTOMATIC = "Automatic"
#     SEMI_AUTOMATIC = "Semi-Automatic"

# class ClimateType(enum.Enum):
#     AC = "Air Conditioning"
#     CLIMATE_CONTROL = "Climate Control"
#     DUAL_ZONE = "Dual Zone Climate Control"
#     MULTI_ZONE = "Multi Zone Climate Control"

# class CategoryType(enum.Enum):
#     START = "Start"
#     COMFORT = "Comfort"
#     EV = "Electro"
#     PREMIUM = "Premium"

# class FuelType(enum.Enum):
#     PETROL = "Petrol"
#     DIESEL = "Diesel"
#     ELECTRIC = "Electric"
#     HYBRID = "Hybrid"
#     PLUG_IN_HYBRID = "Plug-in Hybrid"
#     CNG = "Compressed Natural Gas (CNG)"
#     LPG = "Liquefied Petroleum Gas (LPG)"
#     HYDROGEN = "Hydrogen Fuel Cell"
#     ETHANOL = "Ethanol"
#     BIODIESEL = "Biodiesel"

# class Car(db.Model):
#     id = db.Column(db.Integer, primary_key=True)
#     model = db.Column(db.String(50), nullable=False)
#     price = db.Column(db.Float, nullable=False) 
#     comment = db.Column(db.Text)
#     color = db.Column(db.String(20))
#     seats = db.Column(db.Integer) 
#     fuel_type = db.Column(db.Enum(FuelType), default=FuelType.PETROL)
#     company_id = db.Column(db.Integer, db.ForeignKey('company.id'), nullable=False)
#     image = db.Column(db.LargeBinary)

#     insurance = db.Column(db.Enum(InsuranceType), default=InsuranceType.BASIC)
#     transmission = db.Column(db.Enum(TransmissionType), default=TransmissionType.MANUAL) 
#     deposit = db.Column(db.Float) 
#     year = db.Column(db.Integer) 
#     climate = db.Column(db.Enum(ClimateType), default=ClimateType.AC)
#     category = db.Column(db.Enum(CategoryType), default=CategoryType.START)

#     company = db.relationship('Company', backref=db.backref('cars', lazy=True))
#     availability = db.relationship('CarAvailability', backref='car', lazy=True, cascade="all, delete-orphan") 

#     def __init__(self, model, price, comment, color, seats, fuel_type, company_id, image, 
#                  insurance, transmission, deposit, year, climate, category):
#         self.model = model
#         self.price = price
#         self.comment = comment
#         self.color = color
#         self.seats = seats
#         self.fuel_type = fuel_type
#         self.company_id = company_id
#         self.image = image
#         self.insurance = insurance
#         self.transmission = transmission
#         self.deposit = deposit
#         self.year = year
#         self.climate = climate
#         self.category = category

# class CarAvailability(db.Model):
#     id = db.Column(db.Integer, primary_key=True)
#     car_id = db.Column(db.Integer, db.ForeignKey('car.id'), nullable=False)
#     start_date = db.Column(db.Date, nullable=False)
#     end_date = db.Column(db.Date, nullable=False)
#     is_available = db.Column(db.Boolean, default=True)
    
#     def __init__(self,car_id, start_date, end_date, is_available):
#       self.car_id = car_id
#       self.start_date = start_date
#       self.end_date = end_date
#       self.is_available=is_available


# ==================version 3======================
#=======================VERSION2 ADDED CALENDAR=======================
from app.models import db
import enum

class InsuranceType(enum.Enum):
    BASIC = "Basic"
    PREMIUM = "Premium"
    FULL = "Full"

class TransmissionType(enum.Enum):
    MANUAL = "Manual"
    AUTOMATIC = "Automatic"
    SEMI_AUTOMATIC = "Semi-Automatic"

class ClimateType(enum.Enum):
    AC = "Air Conditioning"
    CLIMATE_CONTROL = "Climate Control"
    DUAL_ZONE = "Dual Zone Climate Control"
    MULTI_ZONE = "Multi Zone Climate Control"

class CategoryType(enum.Enum):
    START = "Start"
    COMFORT = "Comfort"
    EV = "Electro"
    PREMIUM = "Premium"

class FuelType(enum.Enum):
    PETROL = "Petrol"
    DIESEL = "Diesel"
    ELECTRIC = "Electric"
    HYBRID = "Hybrid"
    PLUG_IN_HYBRID = "Plug-in Hybrid"
    CNG = "Compressed Natural Gas (CNG)"
    LPG = "Liquefied Petroleum Gas (LPG)"
    HYDROGEN = "Hydrogen Fuel Cell"
    ETHANOL = "Ethanol"
    BIODIESEL = "Biodiesel"

class Car(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    model = db.Column(db.String(50), nullable=False)
    price = db.Column(db.Float, nullable=False) 
    comment = db.Column(db.Text)
    color = db.Column(db.String(20))
    seats = db.Column(db.Integer) 
    fuel_type = db.Column(db.Enum(FuelType), default=FuelType.PETROL)
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), nullable=False)
    image = db.Column(db.LargeBinary)
    status = db.Column(db.Boolean, default=True) # Changed to boolean, True for available
    insurance = db.Column(db.Enum(InsuranceType), default=InsuranceType.BASIC)
    transmission = db.Column(db.Enum(TransmissionType), default=TransmissionType.MANUAL) 
    deposit = db.Column(db.Float) 
    year = db.Column(db.Integer) 
    climate = db.Column(db.Enum(ClimateType), default=ClimateType.AC)
    category = db.Column(db.Enum(CategoryType), default=CategoryType.START)

    company = db.relationship('Company', backref=db.backref('cars', lazy=True))
    availability = db.relationship('CarAvailability', backref='car', lazy=True, cascade="all, delete-orphan") 

    def __init__(self, model, price, comment, color, seats, fuel_type, company_id, image, 
                 insurance, transmission, deposit, year, climate, category):
        self.model = model
        self.price = price
        self.comment = comment
        self.color = color
        self.seats = seats
        self.fuel_type = fuel_type
        self.company_id = company_id
        self.image = image
        self.insurance = insurance
        self.transmission = transmission
        self.deposit = deposit
        self.year = year
        self.climate = climate
        self.category = category

class CarAvailability(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    car_id = db.Column(db.Integer, db.ForeignKey('car.id'), nullable=False)
    start_date = db.Column(db.Date, nullable=False)
    end_date = db.Column(db.Date, nullable=False)
    is_available = db.Column(db.Boolean, default=True)
    
    def __init__(self,car_id, start_date, end_date, is_available):
      self.car_id = car_id
      self.start_date = start_date
      self.end_date = end_date
      self.is_available=is_available