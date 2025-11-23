<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\Laboratorium;

$labs = Laboratorium::all();
echo "\n========== LABORATORIUM DATA ==========\n\n";
if ($labs->count() === 0) {
    echo "❌ NO LABS FOUND - Creating sample labs...\n\n";
    
    $sampleLabs = [
        ['nama_lab' => 'Lab Komputer 1', 'lokasi' => 'Gedung A Lantai 1', 'kapasitas' => 30],
        ['nama_lab' => 'Lab Komputer 2', 'lokasi' => 'Gedung A Lantai 2', 'kapasitas' => 25],
        ['nama_lab' => 'Lab Jaringan', 'lokasi' => 'Gedung B Lantai 1', 'kapasitas' => 15],
        ['nama_lab' => 'Lab Mikrokontroller', 'lokasi' => 'Gedung C', 'kapasitas' => 20],
    ];
    
    foreach ($sampleLabs as $lab) {
        Laboratorium::create($lab);
        echo "✓ Created: {$lab['nama_lab']}\n";
    }
    
    echo "\n";
    $labs = Laboratorium::all();
}

echo "Total Labs: " . $labs->count() . "\n\n";
foreach ($labs as $lab) {
    echo "ID: {$lab->id} | {$lab->nama_lab}\n";
    echo "   Lokasi: {$lab->lokasi}\n";
    echo "   Kapasitas: {$lab->kapasitas}\n";
}

echo "\n========================================\n\n";
?>
