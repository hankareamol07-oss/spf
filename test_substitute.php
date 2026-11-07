<?php
require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = '74841c3a-e9e9-4a12-9b5f-15492fa84666';

$router = app('router');
$request = \Illuminate\Http\Request::create('/game-sessions/' . $key, 'GET');
$route = $router->getRoutes()->match($request);
$router->setCurrentRoute($route);
$route->bind($request);

$substitute = new \Illuminate\Routing\Middleware\SubstituteBindings($router);
$substitute->handle($request, function($req) use ($route) {
    echo "Inside next after SubstituteBindings:\n";
    $params = $route->parameters();
    foreach ($params as $k => $v) {
        echo "Param $k: " . (is_object($v) ? get_class($v) . ' (id=' . $v->id . ')' : gettype($v) . " ($v)") . PHP_EOL;
    }
});
