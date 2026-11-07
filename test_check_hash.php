<?php
require_once '/var/www/html/spf/vendor/autoload.php';
$app = require_once '/var/www/html/spf/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = \App\Models\User::where('email', 'somnathwalke@gmail.com')->first();
if (!$u) {
    echo "User not found!" . PHP_EOL;
} else {
    echo "User found: id={$u->id}, name={$u->name}, is_active={$u->is_active}, role_id={$u->role_id}" . PHP_EOL;
    $check = \Illuminate\Support\Facades\Hash::check('password', $u->password);
    echo "Password 'password' check: " . ($check ? 'VALID' : 'INVALID') . PHP_EOL;
}
