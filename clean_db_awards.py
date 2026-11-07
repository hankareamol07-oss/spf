import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("""
    UPDATE games 
    SET description_marathi = REPLACE(description_marathi, ' (राष्ट्रीय ICT पुरस्कार प्राप्त खेळ)', '')
    WHERE description_marathi LIKE '%पुरस्कार%'
""")
conn.commit()

rows = cursor.execute("SELECT id, description_marathi FROM games WHERE id IN (7, 8)").fetchall()
print("Updated games 7 and 8:")
for r in rows:
    print(r)
conn.close()
