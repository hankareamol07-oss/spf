import subprocess
import sys

sys.stdout.reconfigure(encoding='utf-8')

php_script = """
$pdo = new PDO('sqlite:/var/www/html/spf/database/database.sqlite');
$stmt = $pdo->query("SELECT id, name, email FROM users WHERE email LIKE '%walke%' OR email LIKE '%mentor%'");
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    echo $row['id'] . ' | ' . $row['name'] . ' | ' . $row['email'] . PHP_EOL;
}
"""

cmd = [
    "ssh",
    "-i", "d:\\SMS\\school-management-key-v2.pem",
    "-o", "StrictHostKeyChecking=no",
    "ubuntu@52.66.73.149",
    f"php8.4 -r \"{php_script.strip()}\""
]

res = subprocess.run(cmd, capture_output=True, text=True)
print("STDOUT:\n", res.stdout)
print("STDERR:\n", res.stderr)
