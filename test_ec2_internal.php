<?php

require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'amolhankare04@gmail.com')->first();
$request = Illuminate\Http\Request::create('/game-sessions/38a79ab4-6d25-47cc-b063-2deedc78c6e0', 'GET');
$request->setUserResolver(fn() => $user);

$response = $kernel->handle($request);
echo 'Response status: ' . $response->getStatusCode() . PHP_EOL;
if ($response->getStatusCode() === 404) {
    echo '404 occurred!' . PHP_EOL;
} else {
    echo 'Success!' . PHP_EOL;
}
echo 'Preview: ' . substr($response->getContent(), 0, 300) . PHP_EOL;
