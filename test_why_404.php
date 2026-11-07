<?php
require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'amolhankare04@gmail.com')->first();
$request = Illuminate\Http\Request::create('/game-sessions/bac2de75-d5c5-4fdb-8f4c-c00bfa1aa63e', 'GET');
$request->setUserResolver(fn() => $user);

// Let's see what happens during route matching
try {
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode() . PHP_EOL;
    echo "Content: " . substr($response->getContent(), 0, 500) . PHP_EOL;
} catch (\Throwable $e) {
    echo "Exception: " . get_class($e) . ": " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
