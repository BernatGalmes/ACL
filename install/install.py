import pandas as pd
from dicttoxml import dicttoxml

from src.DataBase import DataBase
import json

"""
    PROGRAM CONSTANTS
"""
DB_PATH_TABLES_QUERIES = "./data/tables.sql"
DB_PATH_ROLES_DATA = "./data/roles.csv"
DB_PATH_PERMISSIONS_DATA = "./data/permissions.csv"

DB_PATH_DATABASE_CONFIG = "../phpacl/data/settings/database_config.json"

APP_NAME = "PHPACL"
APP_PATH_SETTINGS = "../phpacl/data/settings/settings.xml"

"""
    DATA TO CONFIGURE
"""
db_config = {
    "host": "localhost",
    "user": "root",
    "pass": ""
}

database_name = "testdb"

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


def create_database(database_name):
    # connect to server
    db = DataBase(host=db_config['host'], user=db_config['user'], password=db_config['pass'])

    # create database
    db.execute("""CREATE DATABASE IF NOT EXISTS """ + database_name)

    # use database
    db.execute("""USE """ + database_name)
    #
    # # # Create database from data/tables.sql
    db.run_sql_file(DB_PATH_TABLES_QUERIES)
    # db.db.commit()
    db.cursor.close()
    db.db.close()


def insert_data():
    # connect to server
    db = DataBase(host=db_config['host'], user=db_config['user'], password=db_config['pass'])

    # use database
    db.execute("""USE """ + database_name)

    add_role = ("INSERT INTO main_roles (name) VALUES (%(name)s)")
    roles_data = pd.read_csv(DB_PATH_ROLES_DATA, sep=";", header=0)

    for index, row in roles_data.iterrows():
        data_role = {
            roles_data.columns.values[0]: row[0]
        }
        print(data_role)
        db.cursor.execute(add_role, data_role)

    # Make sure data is committed to the database
    db.db.commit()

    add_permission = ("INSERT INTO main_permissions (tag, description) VALUES (%(tag)s, %(description)s)")
    roles_data = pd.read_csv(DB_PATH_PERMISSIONS_DATA, sep=";", header=0)

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


    db.cursor.execute(add_superuser, superuser_data)
    db.db.commit()

    db.cursor.close()
    db.db.close()


def save_config():
    db_config['db'] = database_name
    with open(DB_PATH_DATABASE_CONFIG, 'w') as fp:
        json.dump(db_config, fp)


def generate_settings():

    settings_data = {
        "website": {
            "site_name": APP_NAME,
            "status": {
                "force_ssl": 0,
                "track_guest": 1,
                "site_offline": 0
            },
            "superuser": 1
        },
        "mail": {
            "websiteName": APP_NAME,
            "smtp_server": "smtp.gmail.com",
            "smtp_port": 587,
            "transport": "tls",
            "username": "",
            "password": "",
            "from_name": "",
            "from_mail": "",
            "verify_url": ""
        }
    }
    xml = dicttoxml(settings_data, custom_root='settings', attr_type=False)
    with open(APP_PATH_SETTINGS, 'wb') as fp:
        fp.write(xml)


# connect to server
db = DataBase(host=db_config['host'], user=db_config['user'], password=db_config['pass'])
db.execute("""DROP DATABASE """ + database_name) # TODO: remove this line
db.cursor.close()
db.db.close()

create_database(database_name)

insert_data()

save_config()

generate_settings()
