import sqlite3

conn = sqlite3.connect('database/database.sqlite')
row = conn.execute("SELECT password FROM users WHERE email='amolhankare04@gmail.com'").fetchone()
print("Hash:", row[0])
