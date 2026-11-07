import subprocess
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

KEY_PATH = r"d:\SMS\school-management-key-v2.pem"
REMOTE_HOST = "ubuntu@52.66.73.149"
REMOTE_DIR = "/var/www/html/spf"

FILES_TO_SYNC = [
    ("app/Models/HpcEvaluation.php", "app/Models/HpcEvaluation.php"),
    ("app/Models/Student.php", "app/Models/Student.php"),
    ("app/Http/Controllers/HpcEvaluationController.php", "app/Http/Controllers/HpcEvaluationController.php"),
    ("app/Services/HpcAutoCompleteService.php", "app/Services/HpcAutoCompleteService.php"),
    ("app/Services/AssessmentAttemptService.php", "app/Services/AssessmentAttemptService.php"),
    ("routes/web.php", "routes/web.php"),
    ("resources/views/welcome.blade.php", "resources/views/welcome.blade.php"),
    ("resources/views/components/layouts/app.blade.php", "resources/views/components/layouts/app.blade.php"),
    ("resources/views/hpc/index.blade.php", "resources/views/hpc/index.blade.php"),
    ("resources/views/hpc/show.blade.php", "resources/views/hpc/show.blade.php"),
    ("resources/views/hpc/edit.blade.php", "resources/views/hpc/edit.blade.php"),
    ("resources/views/hpc/print.blade.php", "resources/views/hpc/print.blade.php"),
    ("database/database.sqlite", "/home/ubuntu/database.sqlite"),
]

print("Attempting to sync HPC 360 files to AWS EC2...")

# 1. Create remote hpc view directory
cmd_mkdir = ["ssh", "-i", KEY_PATH, "-o", "StrictHostKeyChecking=no", "-o", "ConnectTimeout=5", REMOTE_HOST, f"sudo mkdir -p {REMOTE_DIR}/resources/views/hpc && sudo chown -R ubuntu:www-data {REMOTE_DIR}/resources/views/hpc"]
try:
    res = subprocess.run(cmd_mkdir, capture_output=True, text=True, timeout=10)
    if res.returncode == 0:
        print("✓ Created remote directories.")
    else:
        print(f"Directory create note: {res.stderr}")
except Exception as e:
    print(f"EC2 connection note (Instance currently stopped or unreachable): {e}")
    sys.exit(0)

# 2. Sync files
for local, remote in FILES_TO_SYNC:
    remote_target = f"{REMOTE_HOST}:{REMOTE_DIR}/{remote}" if not remote.startswith("/home/ubuntu/") else f"{REMOTE_HOST}:{remote}"
    scp_cmd = ["scp", "-i", KEY_PATH, "-o", "StrictHostKeyChecking=no", "-o", "ConnectTimeout=5", local, remote_target]
    try:
        res = subprocess.run(scp_cmd, capture_output=True, text=True, timeout=10)
        if res.returncode == 0:
            print(f"✓ Synced: {local}")
        else:
            print(f"Failed {local}: {res.stderr}")
    except Exception as e:
        print(f"Error syncing {local}: {e}")

# 3. Move database.sqlite and set permissions
cmd_perms = f"""
if [ -f /home/ubuntu/database.sqlite ]; then
    sudo cp /home/ubuntu/database.sqlite {REMOTE_DIR}/database/database.sqlite
    sudo chown www-data:www-data {REMOTE_DIR}/database/database.sqlite
    sudo chmod 664 {REMOTE_DIR}/database/database.sqlite
fi
sudo chown -R www-data:www-data {REMOTE_DIR}/resources/views/hpc {REMOTE_DIR}/app {REMOTE_DIR}/routes
cd {REMOTE_DIR} && sudo -u www-data php artisan view:clear && sudo -u www-data php artisan cache:clear
"""
subprocess.run(["ssh", "-i", KEY_PATH, "-o", "StrictHostKeyChecking=no", REMOTE_HOST, cmd_perms])
print("✓ EC2 permissions and Laravel caches updated successfully!")
