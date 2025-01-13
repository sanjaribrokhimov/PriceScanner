from app.models import db

class ChatIds(db.Model):
    __tablename__ = 'chat_ids'
    
    id = db.Column(db.Integer, primary_key=True)
    chat_id = db.Column(db.String(512), nullable=False)
    thread_id = db.Column(db.String(512), nullable=False)
    company_id = db.Column(db.Integer, db.ForeignKey('company.id'), nullable=False)

    company = db.relationship('Company', backref='chat_ids', lazy=True)