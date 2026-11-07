import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

tables = [t[0] for t in cursor.execute("SELECT name FROM sqlite_master WHERE type='table'").fetchall()]
print("Matching tables:")
for t in tables:
    if any(k in t.lower() for k in ['holistic', 'hpc', 'mentor', 'domain', 'eval', 'progress', 'student']):
        cols = [c[1] for c in cursor.execute(f"PRAGMA table_info({t})").fetchall()]
        print(f"  {t}: {cols}")

print("\n--- holistic_domains contents ---")
try:
    for r in cursor.execute("SELECT * FROM holistic_domains").fetchall():
        print(" ", r)
except Exception as e:
    print(" ", e)

print("\n--- holistic_indicators sample (first 5) ---")
try:
    for r in cursor.execute("SELECT * FROM holistic_indicators LIMIT 5").fetchall():
        print(" ", r)
except Exception as e:
    print(" ", e)

conn.close()
