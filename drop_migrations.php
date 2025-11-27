<?php
// drop_migrations.php

// Manually include the Laravel autoloader to access framework features
require __DIR__.'/vendor/autoload.php';

// Bootstrap the Laravel application to get access to the DB facade
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

try {
    // Disable foreign key checks to avoid errors when dropping tables
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // Drop the 'migrations' table if it exists
    if (Schema::hasTable('migrations')) {
        Schema::drop('migrations');
        echo "The 'migrations' table has been successfully dropped.\n";
    } else {
        echo "The 'migrations' table does not exist, so no action was taken.\n";
    }

    // It's good practice to re-enable foreign key checks afterward
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

} catch (\Exception $e) {
    // If an error occurs, output the error message
    echo "An error occurred: " . $e->getMessage() . "\n";
    // Re-enable foreign key checks even if an error occurred
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
}

