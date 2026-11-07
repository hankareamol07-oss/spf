import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
games = conn.execute("SELECT id, code, title, title_marathi FROM games").fetchall()
print("Local DB games count:", len(games))
for g in games:
    print(f"{g[0]:2d} | {g[1]:30s} | {g[2]} | {g[3]}")
