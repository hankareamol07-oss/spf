<?php
require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Total Games: " . \App\Models\Game::count() . PHP_EOL;

foreach (['ADDITION_ADVENTURE', 'PLACE_VALUE_CASTLE', 'NUMBER_LINE_FROG'] as $code) {
    $game = \App\Models\Game::where('code', $code)->first();
    if (!$game) {
        echo "Game $code: NOT FOUND in DB!" . PHP_EOL;
    } else {
        echo "Game $code (id={$game->id}): status={$game->status}, supportsGrade(4)=" . ($game->supportsGrade(4) ? 'true' : 'false') . ", levels=" . $game->levels()->count() . PHP_EOL;
    }
}
