import sqlite3

conn = sqlite3.connect('database/database.sqlite')
print("Mentors:", conn.execute("SELECT * FROM mentors").fetchall())
