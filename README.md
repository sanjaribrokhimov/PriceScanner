чтобы запустить проект на локльном сервера используйте следующие команды:

1. Установка необходимых библиотек 
- pip install -r requirements.txt
2. Задать правильные креды для БД в файле .env, который нужно создать самому, пример содержимого фала .env:
- SECRET_KEY=nothingsofar
- JWT_SECRET_KEY=nothingsofar
- DATABASE_URL=postgresql://username:password@localhost/db_name
- EMAIL_PASSWORD="asdfasdfasdf"
- SENDER_EMAIL="asdfasdfasdf"
3. После нужно прописать следующие команды чтобы модели мигрировали в БД:
- flask db init
- flask db migrate -m "комментарий"
- flask db upgrade
4. После можно запустить сервер с помощью команды:
- flask run
