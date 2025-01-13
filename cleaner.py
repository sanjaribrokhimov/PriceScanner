import os
import shutil
import subprocess
import psycopg2
from psycopg2 import sql
from dotenv import load_dotenv

load_dotenv()

DB_HOST = os.getenv("DB_HOST")
DB_PORT = os.getenv("DB_PORT")
DB_NAME = os.getenv("DB_NAME")
DB_USER = os.getenv("DB_USER")
DB_PASSWORD = os.getenv("DB_PASSWORD")

# def drop_all_tables():
#     try:
#         conn = psycopg2.connect(
#             host=DB_HOST, port=DB_PORT, dbname=DB_NAME, user=DB_USER, password=DB_PASSWORD
#         )
#         conn.autocommit = True
#         cur = conn.cursor()
#         cur.execute(
#             """SELECT table_name FROM information_schema.tables WHERE table_schema='public'"""
#         )
#         tables = cur.fetchall()
#         for table in tables:
#             cur.execute(sql.SQL("DROP TABLE IF EXISTS {} CASCADE").format(sql.Identifier(table[0])))
#             print(f"Table {table[0]} dropped.")
#         cur.close()
#         conn.close()
#     except Exception as e:
#         print(f"Error dropping tables: {e}")

def drop_all_tables():
    try:
        conn = psycopg2.connect(
            host=DB_HOST, port=DB_PORT, dbname=DB_NAME, user=DB_USER, password=DB_PASSWORD
        )
        conn.autocommit = True
        cur = conn.cursor()

        cur.execute(
            """SELECT table_name FROM information_schema.tables WHERE table_schema='public'"""
        )
        tables = cur.fetchall()
        for table in tables:
            cur.execute(sql.SQL("DROP TABLE IF EXISTS {} CASCADE").format(sql.Identifier(table[0])))
            print(f"Table {table[0]} dropped.")

        cur.execute(
            """SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema='public'"""
        )
        sequences = cur.fetchall()
        for sequence in sequences:
            cur.execute(sql.SQL("DROP SEQUENCE IF EXISTS {} CASCADE").format(sql.Identifier(sequence[0])))
            print(f"Sequence {sequence[0]} dropped.")

        cur.close()
        conn.close()
    except Exception as e:
        print(f"Error dropping tables and sequences: {e}")

def delete_enums():
    conn = psycopg2.connect(
            host=DB_HOST, port=DB_PORT, dbname=DB_NAME, user=DB_USER, password=DB_PASSWORD
        )
    conn.autocommit = True
    cursor = conn.cursor()
    cursor.execute('DROP TYPE IF EXISTS fueltype;DROP TYPE IF EXISTS categorytype;DROP TYPE IF EXISTS climatetype;DROP TYPE IF EXISTS transmissiontype;DROP TYPE IF EXISTS insurancetype;')

    cursor.close()
    conn.close()

def delete_pycache_folders(root_folder):
    for dirpath, dirnames, filenames in os.walk(root_folder):
        # Skip 'venv' folder and its subdirectories
        if 'venv' in dirpath:
            continue
        # Check if __pycache__ exists in the current directory
        if "__pycache__" in dirnames:
            pycache_path = os.path.join(dirpath, "__pycache__")
            shutil.rmtree(pycache_path)
            print(f"Deleted: {pycache_path}")


def delete_migrations_files(migrations_folder):
    for dirpath, dirnames, filenames in os.walk(migrations_folder, topdown=False):
        # Skip 'venv' folder and its subdirectories
        if 'venv' in dirpath:
            continue
        # Delete each file inside migrations folder
        for filename in filenames:
            file_path = os.path.join(dirpath, filename)
            os.remove(file_path)
            print(f"Deleted file: {file_path}")
        # Delete any directories inside migrations, like 'versions'
        for dirname in dirnames:
            dir_to_remove = os.path.join(dirpath, dirname)
            shutil.rmtree(dir_to_remove)
            print(f"Deleted folder: {dir_to_remove}")

def run_flask_commands():
    commands = [
        "flask db init",
        'flask db migrate -m "update"',
        "flask db upgrade",
    ]
    for command in commands:
        subprocess.run(command, shell=True, check=True)
        print(f"Executed: {command}")

def insert_data():
    try:
        # Establish database connection
        conn = psycopg2.connect(
            host=DB_HOST,
            port=DB_PORT,
            dbname=DB_NAME,
            user=DB_USER,
            password=DB_PASSWORD
        )
        conn.autocommit = True  # Enable autocommit mode
        cur = conn.cursor()  # Create a cursor object

        # Prepare the insert query
        insert_query = sql.SQL("INSERT INTO {} (legal_name, name, category, city, district, address) VALUES (%s, %s, %s, %s, %s, %s)").format(
            sql.Identifier('company')
        )

        # Define the data to be inserted
        data = ("smt24", "SMT24", "Services", "Tashkent", "Mirabad", "Mirabad00")

        # Execute the query with the data
        cur.execute(insert_query, data)
        print(f"Data inserted into {'company'}")

    except Exception as e:
        print(f"Error inserting data: {e}")

    finally:
        # Ensure the cursor and connection are closed
        if cur:
            cur.close()
        if conn:
            conn.close()

def insert_user():
    try:
        # Establish database connection
        conn = psycopg2.connect(
            host=DB_HOST,
            port=DB_PORT,
            dbname=DB_NAME,
            user=DB_USER,
            password=DB_PASSWORD
        )
        conn.autocommit = True  # Enable autocommit mode
        cur = conn.cursor()  # Create a cursor object

        # Prepare the insert query
        insert_query = sql.SQL("INSERT INTO {} (status, first_name, last_name, email, phone_number, city, role, category, company_id, password_hash) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s)").format(
            sql.Identifier("user")
        )

        # Define the data to be inserted
        data = (True, "admin", "admin", "admin@gmail.com", "+998888882323", "Mirabad", "admin", "admin", "1", "scrypt:32768:8:1$0wGLi15eOJS8mZp9$0305dc67d54c5303a623298888b6ece6b2c6e3deccb5da098bde53043453fb8383c72ec2eee7b936565a484f6a60c9f87ce6789abae6261131c5ebedaa9dac7f")

        # Execute the query with the data
        cur.execute(insert_query, data)
        print(f"Data inserted into user ")

    except Exception as e:
        print(f"Error inserting data: {e}")

    finally:
        # Ensure the cursor and connection are closed
        if cur:
            cur.close()
        if conn:
            conn.close()

if __name__ == "__main__":
    root_folder = os.getcwd()  
    migrations_folder = os.path.join(root_folder, "migrations")

    drop_all_tables()

    delete_pycache_folders(root_folder)

    delete_migrations_files(migrations_folder)

    delete_enums()

    run_flask_commands()

    insert_data()

    insert_user()
