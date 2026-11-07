import subprocess
import sys

sys.stdout.reconfigure(encoding='utf-8')

test_code = """
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\\Contracts\\Http\\Kernel::class);

$user = App\\Models\\User::where('email', 'amolhankare04@gmail.com')->first();
$request = Illuminate\\Http\\Request::create('/game-sessions/38a79ab4-6d25-47cc-b063-2deedc78c6e0', 'GET');
$request->setUserResolver(fn() => $user);

$response = $kernel->handle($request);
echo 'Response status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Response content preview: ' . substr($response->getContent(), 0, 500) . PHP_EOL;
"""

cmd = [
    "ssh",
    "-i", "d:\\SMS\\school-management-key-v2.pem",
    "-o", "StrictHostKeyChecking=no",
    "ubuntu@52.66.73.149",
    f"php8.4 -r {subprocess.list2cmdline([test_code.strip()])}"
]

res = subprocess.run(cmd, capture_output=True, text=True)
print("STDOUT:\n", res.stdout)
print("STDERR:\n", res.stderr)
