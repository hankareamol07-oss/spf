import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

tables = [t[0] for t in cursor.execute("SELECT name FROM sqlite_master WHERE type='table'").fetchall()]
found = False

for t in tables:
    cols = [c[1] for c in cursor.execute(f"PRAGMA table_info({t})").fetchall()]
    for c in cols:
        try:
            cursor.execute(f"SELECT rowid, [{c}] FROM [{t}] WHERE CAST([{c}] AS TEXT) LIKE '%पुरस्कार%' OR CAST([{c}] AS TEXT) LIKE '%ICT%'")
            rows = cursor.fetchall()
            if rows:
                print(f"Table {t}, Col {c}: {rows}")
                found = True
        except Exception as e:
            pass

if not found:
    print("Database is 100% CLEAN of पुरस्कार and ICT!")
conn.close()
