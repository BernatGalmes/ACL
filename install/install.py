from src.DataBase import DataBase
###
###
###
# example
###
###
###

database_name = "testdb"

# connect to server
db = DataBase(host="localhost", user="root", password="")

# create database
db.execute("""CREATE DATABASE IF NOT EXISTS """ + database_name)


# use database
db.execute("""USE""" + database_name)

# TODO: create database from data/tables.sql

# TODO: set initial data from data/data.sql

# add superuser
user_mail = ""
user_username = ""
user_pass = ""
user_pass_hash = ""
user_fname = ""
user_lname = ""
user_permission_id = str(0)
user_permission_title = "superuser"
query = """
INSERT INTO `users` (`email`, `username`, `password`, `fname`, `lname`, `permission_id`, `email_verified`, `title`) VALUES
(""" + user_mail + """, 
""" + user_username + """, 
""" + user_pass_hash + """, 
""" + user_fname + """, 
""" + user_lname + """, 
""" + user_permission_id + """, 1, """ + user_permission_title + """);
"""

db.cursor.close()
