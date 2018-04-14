import mysql.connector as conn

class DataBase:
    def __init__(self, host="localhost", user="root", password=""):
        self.db = conn.connect(host=host, user=user, password=password)
        self.cursor = self.db.cursor()

    def execute(self, query):
        self.cursor.execute(query)
        self.db.commit()