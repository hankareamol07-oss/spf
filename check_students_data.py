import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
print("Academic Years:")
for r in conn.execute("SELECT id, name, code, is_current FROM academic_years").fetchall():
    print(r)

print("\nStudents:")
for r in conn.execute("SELECT s.id, s.student_number, u.name, u.email FROM students s JOIN users u ON s.user_id = u.id LIMIT 10").fetchall():
    print(r)

conn.close()
