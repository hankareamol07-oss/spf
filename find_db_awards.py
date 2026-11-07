import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
tables = [r[0] for r in conn.execute("SELECT name FROM sqlite_master WHERE type='table'").fetchall()]
for t in tables:
    try:
        cols = [c[1] for c in conn.execute(f"PRAGMA table_info({t})").fetchall()]
        for c in cols:
            rows = conn.execute(f"SELECT id, {c} FROM {t} WHERE {c} LIKE '%पुरस्कार%'").fetchall()
            if rows:
                print(f"Table: {t}, Col: {c}")
                for r in rows:
                    print("  Row:", r)
    except Exception as e:
        pass
