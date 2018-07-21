import pandas as pd
from src.DataBase import DataBase


def create_database(database_name):
    # connect to server
    db = DataBase(host="localhost", user="root", password="")
    # db.execute("""DROP DATABASE """ + database_name)

    # create database
    db.execute("""CREATE DATABASE IF NOT EXISTS """ + database_name)

    # use database
    db.execute("""USE """ + database_name)
    #
    # # # Create database from data/tables.sql
    db.run_sql_file("./data/tables.sql")
    # db.db.commit()
    db.cursor.close()
    db.db.close()


def insert_data():
    # connect to server
    db = DataBase(host="localhost", user="root", password="")

    # use database
    db.execute("""USE """ + database_name)

    add_role = ("INSERT INTO main_roles (name) VALUES (%(name)s)")
    roles_data = pd.read_csv("./data/roles.csv", sep=";", header=0)

    for index, row in roles_data.iterrows():
        data_role = {
            roles_data.columns.values[0]: row[0]
        }
        print(data_role)
        db.cursor.execute(add_role, data_role)

    # Make sure data is committed to the database
    db.db.commit()

    add_permission = ("INSERT INTO main_permissions (tag, description) VALUES (%(tag)s, %(description)s)")
    roles_data = pd.read_csv("./data/permissions.csv", sep=";", header=0)

    for index, row in roles_data.iterrows():
        data_permission = {}
        for i, attr_name in enumerate(roles_data.columns.values):
            data_permission[attr_name] = row[i]

        print(data_permission)
        db.cursor.execute(add_permission, data_permission)

    # Make sure data is committed to the database
    db.db.commit()

    ##
    # add superuser
    ##
    add_superuser = """
    INSERT INTO users (email, username, password, fname, lname, id_role, email_verified, title)
    VALUES (%(email)s, %(username)s, %(password)s, %(fname)s, %(lname)s, %(id_role)s, %(email_verified)s, 
    %(title)s)"""

    superuser_data = {
        "email": "admin@admin.com",
        "username": "admin",
        "password": "$2y$12$jmt4x34D8M5GU7g89PVpzeQGbqkX7AwHjjqsa1FHy2YxurXVgJKuq", # "admin"
        "fname": "admin",
        "lname": "admin",
        "id_role": str(1),
        "title": "superuser",
        "email_verified": 1
    }
    db.cursor.execute(add_superuser, superuser_data)
    db.db.commit()

    db.cursor.close()
    db.db.close()


database_name = "testdb"
# connect to server
db = DataBase(host="localhost", user="root", password="")
db.execute("""DROP DATABASE """ + database_name)
db.cursor.close()
db.db.close()

create_database(database_name)

insert_data()



