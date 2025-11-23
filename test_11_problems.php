<?php
/**
 * TESTING SCRIPT - FIND 11 PROBLEMS
 * This script identifies potential issues across all roles
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Laboratorium;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

echo "\n========== TESTING 11 POTENTIAL PROBLEMS ==========\n\n";

$issues = [];

// CHECK 1: Database connectivity
echo "[1] Checking database connectivity... ";
try {
    DB::connection()->getPDO();
    echo "✅\n";
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    $issues[] = "Database connection failed";
}

// CHECK 2: Users exist
echo "[2] Checking test accounts... ";
$users = User::count();
echo "$users users found. ";
if ($users >= 3) {
    echo "✅\n";
} else {
    echo "❌ Need at least 3 users\n";
    $issues[] = "Not enough test users";
}

// CHECK 3: Laboratorium exists
echo "[3] Checking laboratories... ";
$labs = Laboratorium::count();
echo "$labs labs found. ";
if ($labs > 0) {
    echo "✅\n";
} else {
    echo "❌ Need at least 1 lab\n";
    $issues[] = "No laboratories found";
}

// CHECK 4: View files exist
echo "[4] Checking critical view files... ";
$views = [
    'resources/views/welcome.blade.php',
    'resources/views/auth/login.blade.php',
    'resources/views/admin/dashboard.blade.php',
    'resources/views/staf/dashboard.blade.php',
    'resources/views/user/index.blade.php',
    'resources/views/admin/peminjaman/index.blade.php',
    'resources/views/staf/peminjaman.blade.php',
    'resources/views/staf/pengembalian.blade.php',
    'resources/views/staf/kerusakan.blade.php',
    'resources/views/staf/sop.blade.php',
    'resources/views/user/peminjaman/create.blade.php',
];
$missing_views = [];
foreach ($views as $view) {
    if (!file_exists($view)) {
        $missing_views[] = $view;
    }
}
if (empty($missing_views)) {
    echo "✅ All " . count($views) . " view files exist\n";
} else {
    echo "❌ Missing " . count($missing_views) . " view files\n";
    foreach ($missing_views as $v) {
        echo "   - $v\n";
        $issues[] = "Missing view: $v";
    }
}

// CHECK 5: Controllers have required methods
echo "[5] Checking controller methods... ";
$peminjaman_ctrl = new \App\Http\Controllers\PeminjamanController();
$staf_ctrl = new \App\Http\Controllers\StafController();

$methods_peminjaman = [
    'index', 'create', 'store', 'edit', 'update', 'destroy',
    'createForUser', 'storeUser',
    'validasi', 'approve', 'reject',
    'pengembalian', 'konfirmasiPengembalian'
];

$methods_staf = ['kerusakan', 'inputKerusakan', 'sop', 'uploadSOP'];

$missing_methods = [];
foreach ($methods_peminjaman as $m) {
    if (!method_exists($peminjaman_ctrl, $m)) {
        $missing_methods[] = "PeminjamanController::$m";
    }
}
foreach ($methods_staf as $m) {
    if (!method_exists($staf_ctrl, $m)) {
        $missing_methods[] = "StafController::$m";
    }
}

if (empty($missing_methods)) {
    echo "✅ All required methods exist\n";
} else {
    echo "❌ Missing methods:\n";
    foreach ($missing_methods as $m) {
        echo "   - $m\n";
        $issues[] = "Missing method: $m";
    }
}

// CHECK 6: Routes are defined correctly
echo "[6] Checking route definitions... ";
$routes = [
    'welcome', 'login', 'dashboard', 
    'admin.dashboard', 'admin.peminjaman.index',
    'staf.dashboard', 'staf.peminjaman',
    'user.index', 'peminjaman.create'
];
$app = app();
$router = $app['router'];
$missing_routes = [];
foreach ($routes as $route) {
    try {
        route($route, [], false);
    } catch (\Exception $e) {
        $missing_routes[] = $route;
    }
}
if (empty($missing_routes)) {
    echo "✅ All key routes exist\n";
} else {
    echo "❌ Missing routes: " . implode(', ', $missing_routes) . "\n";
    foreach ($missing_routes as $r) {
        $issues[] = "Route not defined: $r";
    }
}

// CHECK 7: Auth middleware working
echo "[7] Checking auth middleware... ";
$auth_files = [
    'app/Http/Middleware/Admin.php',
    'app/Http/Middleware/Staf.php',
    'app/Http/Middleware/User.php',
];
$missing_middleware = [];
foreach ($auth_files as $f) {
    if (!file_exists($f)) {
        $missing_middleware[] = $f;
    }
}
if (empty($missing_middleware)) {
    echo "✅ All middleware files exist\n";
} else {
    echo "❌ Missing middleware: " . implode(', ', $missing_middleware) . "\n";
    foreach ($missing_middleware as $m) {
        $issues[] = "Missing middleware: $m";
    }
}

// CHECK 8: Models have correct relationships
echo "[8] Checking model relationships... ";
try {
    $peminjaman = Peminjaman::with(['user', 'laboratorium', 'alat'])->first();
    if ($peminjaman) {
        echo "✅ Relationships loaded\n";
    } else {
        echo "⚠️  No peminjaman records to test\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: {$e->getMessage()}\n";
    $issues[] = "Model relationship error: " . $e->getMessage();
}

// CHECK 9: User profiles are complete
echo "[9] Checking user profiles... ";
$incomplete_users = User::where('is_profile_complete', false)->count();
if ($incomplete_users == 0) {
    echo "✅ All users have complete profiles\n";
} else {
    echo "⚠️  {$incomplete_users} users have incomplete profiles\n";
}

// CHECK 10: Config/routes cache state
echo "[10] Checking Laravel caches... ";
$cache_exists = [
    'bootstrap/cache/config.php' => file_exists('bootstrap/cache/config.php'),
    'bootstrap/cache/routes.php' => file_exists('bootstrap/cache/routes.php'),
    'bootstrap/cache/services.php' => file_exists('bootstrap/cache/services.php'),
];
$cache_count = count(array_filter($cache_exists));
echo "$cache_count/3 cache files exist. ";
if (config('app.debug') === false && $cache_count === 0) {
    echo "⚠️  Production mode without caches\n";
} else {
    echo "✅\n";
}

// CHECK 11: Error handling in views
echo "[11] Checking view error handling... ";
$iterator = new RecursiveDirectoryIterator('resources/views');
$recursive = new RecursiveIteratorIterator($iterator);
$view_files = array_filter(iterator_to_array($recursive), function($file) {
    return $file->getExtension() === 'php' && strpos($file->getFilename(), '.blade.php') !== false;
});
$views_without_null_checks = [];
foreach ($view_files as $file) {
    $content = file_get_contents($file->getRealPath());
    // Simple check: look for ->property without ?-> or ?? fallback nearby
    if (preg_match('/\$\w+\->\w+/', $content) && !preg_match('/\?->|\?\?/', $content)) {
        $views_without_null_checks[] = str_replace(base_path() . '/', '', $file);
    }
}
if (empty($views_without_null_checks)) {
    echo "✅ All views have proper null handling\n";
} else {
    echo "⚠️  " . count($views_without_null_checks) . " views may need null checks\n";
    $issues[] = "Views without null safety: " . count($views_without_null_checks);
}

echo "\n========== SUMMARY ==========\n";
echo "Total Issues Found: " . count($issues) . "\n\n";

if (count($issues) > 0) {
    echo "Issues List:\n";
    foreach ($issues as $i => $issue) {
        echo ($i+1) . ". $issue\n";
    }
} else {
    echo "✅ No critical issues found!\n";
}

echo "\n";
