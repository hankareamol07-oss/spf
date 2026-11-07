import sqlite3
import re
import glob
import sys

sys.stdout.reconfigure(encoding='utf-8')

print("==================================================")
print("VERIFYING HPC 360° MODULE & 17 OFFICIAL STUDENTS")
print("==================================================")

# 1. Database checks
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

students = cursor.execute("""
    SELECT s.id, s.student_number, u.name, s.gender, s.guardian_name, h.overall_fln_level
    FROM students s
    JOIN users u ON u.id = s.user_id
    LEFT JOIN hpc_evaluations h ON h.student_id = s.id AND h.term = 1
    ORDER BY s.id
""").fetchall()

print(f"\n1. Students in DB ({len(students)} total):")
for s in students:
    print(f"  Roll {s[0]}: {s[1]} | {s[2]} ({s[3]}) | पालक: {s[4]} | FLN: {s[5]}")

assert len(students) == 17, f"Expected 17 students, got {len(students)}"

attempts_count = cursor.execute("SELECT COUNT(*) FROM test_attempts WHERE test_id IN (1, 2, 3, 4)").fetchone()[0]
print(f"\n2. Total Pre-Test & Post-Test Attempts: {attempts_count}")
assert attempts_count >= 68, f"Expected at least 68 test attempts, got {attempts_count}"

evals_count = cursor.execute("SELECT COUNT(*) FROM hpc_evaluations").fetchone()[0]
print(f"\n3. Total HPC Evaluations Auto-completed: {evals_count}")
assert evals_count == 17, f"Expected 17 evaluations, got {evals_count}"

conn.close()

# 2. Strict Check for '(राष्ट्रीय ICT पुरस्कार विजेते)'
print("\n4. Checking for any occurrence of '(राष्ट्रीय ICT पुरस्कार विजेते)':")
bad_pattern = re.compile(r'राष्ट्रीय ICT|पुरस्कार विजेते')
found_violations = []

files_to_check = [
    'resources/views/welcome.blade.php',
    'resources/views/hpc/index.blade.php',
    'resources/views/hpc/show.blade.php',
    'resources/views/hpc/edit.blade.php',
    'resources/views/hpc/print.blade.php',
    'resources/views/components/layouts/app.blade.php',
    'app/Http/Controllers/HpcEvaluationController.php',
    'app/Services/HpcAutoCompleteService.php',
    'app/Models/HpcEvaluation.php',
    'routes/web.php'
]

for fp in files_to_check:
    with open(fp, 'r', encoding='utf-8') as f:
        content = f.read()
        if bad_pattern.search(content):
            found_violations.append(fp)

if found_violations:
    print(f"❌ Found violations in: {found_violations}")
    sys.exit(1)
else:
    print("✓ All 10 files are 100% CLEAN of '(राष्ट्रीय ICT पुरस्कार विजेते)'!")

print("\n✓ ALL LOCAL TESTS PASSED WITH 100% SUCCESS! 🎉")
print("==================================================")
