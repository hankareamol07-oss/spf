<?php
require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'amolhankare04@gmail.com')->first();
$session = App\Models\GameSession::where('status', 'in_progress')->first();

echo "Testing session_key: " . $session->session_key . PHP_EOL;

// Create request
$request = Illuminate\Http\Request::create('/game-sessions/' . $session->session_key, 'GET');
$app->instance('request', $request);

// Listen to events and queries
\DB::listen(function($query) {
    echo "SQL: " . $query->sql . " [" . implode(', ', $query->bindings) . "]" . PHP_EOL;
});

// Let's test route matching and dispatch
try {
    // Simulate user login in session
    $sessionStore = $app->make('session')->driver();
    $sessionStore->start();
    $sessionStore->put($app->make('auth')->guard()->getName(), $user->getAuthIdentifier());
    $sessionStore->save();
    
    $request->setLaravelSession($sessionStore);
    $request->setUserResolver(fn() => $user);

    $response = $kernel->handle($request);
    echo "Response status: " . $response->getStatusCode() . PHP_EOL;
    if ($response->getStatusCode() === 404) {
        echo "Response was 404! Response content:" . PHP_EOL;
        echo substr($response->getContent(), 0, 400) . PHP_EOL;
    } else {
        echo "Response was: " . $response->getStatusCode() . PHP_EOL;
    }
} catch (\Throwable $e) {
    echo "Exception caught: " . get_class($e) . ": " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
