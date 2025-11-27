<?php
// reset_database.php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$dbName = DB::getDatabaseName();

try {
    // Drop the database
    DB::statement("DROP DATABASE `$dbName`");
    echo "Database '$dbName' dropped successfully.\n";

    // Recreate the database
    DB::statement("CREATE DATABASE `$dbName`");
    echo "Database '$dbName' created successfully.\n";

} catch (\Exception $e) {
    echo "An error occurred: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'database exists')) {
        echo "Database '$dbName' already exists. Trying to drop and recreate again.\n";
        try {
            DB::statement("DROP DATABASE `$dbName`");
            DB::statement("CREATE DATABASE `$dbName`");
            echo "Database '$dbName' recreated successfully.\n";
        } catch (\Exception $e) {
            echo "Failed to recreate database after initial error: " . $e->getMessage() . "\n";
        }
    }
}

