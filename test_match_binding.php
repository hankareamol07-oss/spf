<?php
require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = '74841c3a-e9e9-4a12-9b5f-15492fa84666';
$session = \App\Models\GameSession::where('session_key', $key)->first();
echo "GameSession exists: " . ($session ? "YES, id={$session->id}, status={$session->status}" : "NO") . PHP_EOL;

// Let's test route matching
$request = \Illuminate\Http\Request::create('/game-sessions/' . $key, 'GET');
$route = app('router')->getRoutes()->match($request);
echo "Matched route URI: " . $route->uri() . PHP_EOL;
echo "Route parameters before binding: " . json_encode($route->parameters()) . PHP_EOL;

try {
    $route->bind($request);
    echo "Route parameters after binding: " . json_encode($route->parameters()) . PHP_EOL;
} catch (\Throwable $e) {
    echo "Binding threw exception: " . get_class($e) . ": " . $e->getMessage() . PHP_EOL;
}
