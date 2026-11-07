import subprocess
import sys

sys.stdout.reconfigure(encoding='utf-8')

session_key = '38a79ab4-6d25-47cc-b063-2deedc78c6e0'

cmd = [
    "ssh",
    "-i", "d:\\SMS\\school-management-key-v2.pem",
    "-o", "StrictHostKeyChecking=no",
    "ubuntu@52.66.73.149",
    f"cd /var/www/html/spf && php8.4 artisan tinker --execute=\"echo 'Found: ' . json_encode(\App\Models\GameSession::where('session_key', '{session_key}')->first());\""
]

res = subprocess.run(cmd, capture_output=True, text=True)
print("STDOUT:\n", res.stdout)
print("STDERR:\n", res.stderr)
