from app.models import db

class Cart(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=False)
    user = db.relationship('User', back_populates='cart')  

    items = db.relationship('CartItem', back_populates='cart')

    def __init__(self, user_id):
        self.user_id = user_id

class CartItem(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    cart_id = db.Column(db.Integer, db.ForeignKey('cart.id'), nullable=False)
    product_type = db.Column(db.String(512))
    product_company_id = db.Column(db.Integer)
    product_id = db.Column(db.Integer)
    product = db.Column(db.Text)

    cart = db.relationship('Cart', back_populates='items')
