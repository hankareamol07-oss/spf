import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

# Check if somnathwalke@gmail.com exists
row = cursor.execute("SELECT id FROM users WHERE email='somnathwalke@gmail.com'").fetchone()
hash_val = '$2y$12$TerYgvS/C6/FhrKXBd8w4eXOrb.B/BFcgt8cgdiSRe22S5ZlQol.2' # 'password'

if not row:
    cursor.execute("""
        INSERT INTO users (role_id, school_id, name, email, email_verified_at, password, is_active, created_at, updated_at)
        VALUES (3, 1, 'श्री. सोमनाथ वाळके (मार्गदर्शक)', 'somnathwalke@gmail.com', '2026-10-31 08:00:00', ?, 1, '2026-10-31 08:00:00', '2026-10-31 08:00:00')
    """, (hash_val,))
    print("Created somnathwalke@gmail.com")
else:
    cursor.execute("UPDATE users SET password=?, is_active=1 WHERE email='somnathwalke@gmail.com'", (hash_val,))
    print("Updated somnathwalke@gmail.com")

# Ensure somnath.walke@example.test has password
cursor.execute("UPDATE users SET password=?, is_active=1 WHERE email='somnath.walke@example.test'", (hash_val,))
print("Updated somnath.walke@example.test")

# Ensure mentor@example.test has password
cursor.execute("UPDATE users SET password=?, is_active=1 WHERE email='mentor@example.test'", (hash_val,))
print("Updated mentor@example.test")

conn.commit()

# Print all mentor users
rows = cursor.execute("SELECT u.id, u.name, u.email, r.name FROM users u JOIN roles r ON u.role_id = r.id WHERE u.email LIKE '%walke%' OR u.email LIKE '%mentor%'").fetchall()
for r in rows:
    print("User:", r)

conn.close()
