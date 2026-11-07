<?php
require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$router = app('router');
$routes = $router->getRoutes();
$route = $routes->getByName('games.sessions.show');
echo "Route URI: " . $route->uri() . PHP_EOL;
echo "Parameter names: " . implode(', ', $route->parameterNames()) . PHP_EOL;

// Test binding resolution
$session = \App\Models\GameSession::first();
if ($session) {
    echo "Found session key: " . $session->session_key . PHP_EOL;
    try {
        $bound = $session->resolveRouteBinding($session->session_key);
        echo "resolveRouteBinding with session_key: " . ($bound ? "OK (id={$bound->id})" : "NULL") . PHP_EOL;
    } catch (\Exception $e) {
        echo "resolveRouteBinding error: " . $e->getMessage() . PHP_EOL;
    }

    try {
        $bound2 = $session->resolveRouteBinding($session->id);
        echo "resolveRouteBinding with id: " . ($bound2 ? "OK" : "NULL") . PHP_EOL;
    } catch (\Exception $e) {
        echo "resolveRouteBinding with id error: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "No session in DB!" . PHP_EOL;
}
