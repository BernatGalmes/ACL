import mysql
import mysql.connector as conn
from mysql.connector import errorcode

import datetime, time


class DataBase:
    def __init__(self, host="localhost", user="root", password=""):
        self.db = conn.connect(host=host, user=user, password=password)
        self.cursor = self.db.cursor()

    def execute(self, query):
        # self.cursor = self.db.cursor()
        self.cursor.execute(query)
        # self.cursor.close()
        # self.db.commit()

    def run_sql_file(self, filename):
        """
        The function takes a filename and a connection as input
        and will run the SQL query on the given connection
        """
        start = time.time()

        file = open(filename, 'r')
        sql = " ".join(file.readlines())
        print("Start executing: " + filename + " at " + str(datetime.datetime.now().strftime("%Y-%m-%d %H:%M")))
        try:
            self.cursor.execute(sql)

        except mysql.connector.Error as err:
            print(sql)
            if err.errno == errorcode.ER_TABLE_EXISTS_ERROR:
                print("already exists.")
            else:
                print(err.msg)
        else:
            print("OK")

        end = time.time()
        print("Time elapsed to run the query:")
        print(str((end - start)*1000) + ' ms')
        # self.cursor.close()

    def __del__(self):
        self.cursor.close()
        self.db.close()
