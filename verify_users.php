<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\User;

$users = User::whereIn('email', ['admin@lab.com', 'staf@lab.com', 'user@lab.com'])->get();

echo "\n========== USER ACCOUNTS CREATED ==========\n\n";
foreach($users as $u) {
    echo "Email: " . $u->email . "\n";
    echo "Nama: " . $u->nama . "\n";
    echo "Level: " . $u->level . "\n";
    echo "Profile Complete: " . ($u->is_profile_complete ? 'Yes' : 'No') . "\n";
    echo "---\n";
}
echo "\nTotal Users: " . $users->count() . "\n";
echo "==========================================\n\n";
?>
