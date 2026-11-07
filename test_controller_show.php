<?php
require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$session = App\Models\GameSession::first();
echo "Testing with session: " . $session->session_key . PHP_EOL;

$request = Illuminate\Http\Request::create('/game-sessions/' . $session->session_key);
app()->instance('request', $request);

$user = App\Models\User::where('email', 'amolhankare04@gmail.com')->first();
$request->setUserResolver(fn() => $user);

$controller = app(App\Http\Controllers\StudentGameController::class);
$service = app(App\Services\GameSessionService::class);

try {
    $view = $controller->show($session, $service);
    echo "Controller returned: " . (is_object($view) ? get_class($view) : gettype($view)) . PHP_EOL;
    if ($view instanceof Illuminate\View\View) {
        echo "View name: " . $view->name() . PHP_EOL;
        $rendered = $view->render();
        echo "Rendered length: " . strlen($rendered) . PHP_EOL;
    }
} catch (\Throwable $e) {
    echo "Error calling show: " . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
