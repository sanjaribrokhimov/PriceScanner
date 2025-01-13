import random
import smtplib
from email.mime.text import MIMEText
import os
def generate_otp(length=6):
    return ''.join([str(random.randint(0, 9)) for i in range(length)])


def send_otp_via_email(otp, recipient_email):
    sender_email = os.getenv("SENDER_EMAIL")
    sender_password = os.getenv('EMAIL_PASSWORD')

    msg = MIMEText(f'Your OTP is {otp}')
    msg['Subject'] = 'Your OTP Code'
    msg['From'] = sender_email
    msg['To'] = recipient_email

    with smtplib.SMTP('smtp.gmail.com', 587) as server:
        server.starttls()
        server.login(sender_email, sender_password)
        server.sendmail(sender_email, recipient_email, msg.as_string())