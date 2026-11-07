import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
print("--- Domains ---")
for r in conn.execute("SELECT id, code, name, name_marathi FROM holistic_domains").fetchall():
    print(r)

print("\n--- Indicators ---")
for r in conn.execute("SELECT id, holistic_domain_id, code, name, name_marathi, rating_scale FROM holistic_indicators").fetchall():
    print(r)

print("\n--- Records count ---")
print("Holistic records:", conn.execute("SELECT count(*) FROM holistic_records").fetchone()[0])
print("Mentor observations:", conn.execute("SELECT count(*) FROM mentor_observations").fetchone()[0])

conn.close()
