import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
c = conn.cursor()
c.execute("SELECT id, name, email, role_id FROM users WHERE email LIKE '%walke%' OR email LIKE '%mentor%' OR name LIKE '%सोमनाथ%'")
for row in c.fetchall():
    print(row)
