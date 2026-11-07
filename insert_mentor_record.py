import sqlite3

for db_path in ['database/database.sqlite']:
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    # Check if user 21 has mentor row
    row = c.execute("SELECT id FROM mentors WHERE user_id=21").fetchone()
    if not row:
        c.execute("""
            INSERT INTO mentors (user_id, school_id, employee_number, created_at, updated_at)
            VALUES (21, 1, 'SPIF-MEN-02', '2026-10-31 08:00:00', '2026-10-31 08:00:00')
        """)
        conn.commit()
        print("Inserted mentor row for user 21")
    else:
        print("Mentor row already exists for user 21")
    conn.close()
