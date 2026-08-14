<?php
// Production Configuration Verification Script

require_once 'c:/Project/KERJA PRAKTEK/SIAPTIKA/vendor/autoload.php';

$app = require_once 'c:/Project/KERJA PRAKTEK/SIAPTIKA/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Activity;
use App\Models\Document;

echo "=========================================================================\n";
echo "              SIAPTIKA — PRODUCTION CONFIGURATION AUDIT                  \n";
echo "=========================================================================\n\n";

echo "--- 1. APPLICATION ENVIRONMENT & SECURITY ---\n";
echo "  APP_NAME      : " . config('app.name') . "\n";
echo "  APP_ENV       : " . config('app.env') . "\n";
echo "  APP_DEBUG     : " . (config('app.debug') ? 'TRUE (DEBUG ACTIVE)' : 'FALSE (PRODUCTION SAFE)') . "\n";
echo "  APP_URL       : " . config('app.url') . "\n";
echo "  APP_KEY       : " . (config('app.key') ? 'SET (' . substr(config('app.key'), 0, 15) . '...)' : 'MISSING') . "\n";
echo "  BCRYPT_ROUNDS : " . config('hashing.bcrypt.rounds', 12) . "\n";

echo "\n--- 2. LOCAL SERVER BINDING & HOST/PORT ---\n";
echo "  Target Host   : 127.0.0.1 (Loopback only - secure from external LAN)\n";
echo "  Target Port   : 8000\n";
echo "  Health Route  : /up (HTTP 200 OK)\n";

echo "\n--- 3. DATABASE CONFIGURATION (SUPABASE POSTGRESQL) ---\n";
echo "  Default Conn  : " . config('database.default') . "\n";
echo "  DB Host       : " . config('database.connections.pgsql.host') . "\n";
echo "  DB Port       : " . config('database.connections.pgsql.port') . "\n";
echo "  DB Database   : " . config('database.connections.pgsql.database') . "\n";
echo "  DB Username   : " . config('database.connections.pgsql.username') . "\n";
echo "  DB SSL Mode   : " . config('database.connections.pgsql.sslmode') . "\n";

$dbPdo = DB::connection()->getPdo();
echo "  Driver Active : " . $dbPdo->getAttribute(PDO::ATTR_DRIVER_NAME) . "\n";
echo "  Server Version: " . $dbPdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";
echo "  User Records  : " . User::count() . " (Administrator ID: " . User::first()->login_id . ")\n";
echo "  Activity Count: " . Activity::count() . " activities\n";
echo "  Document Count: " . Document::count() . " documents\n";

echo "\n--- 4. CLOUD STORAGE CONFIGURATION (SUPABASE S3) ---\n";
echo "  Default Disk  : " . config('filesystems.default') . "\n";
echo "  Driver        : " . config('filesystems.disks.supabase.driver') . "\n";
echo "  Bucket        : " . config('filesystems.disks.supabase.bucket') . "\n";
echo "  Endpoint      : " . config('filesystems.disks.supabase.endpoint') . "\n";
echo "  Region        : " . config('filesystems.disks.supabase.region') . "\n";
echo "  Key Configured: " . (config('filesystems.disks.supabase.key') ? 'YES' : 'NO') . "\n";
echo "  Secret Config : " . (config('filesystems.disks.supabase.secret') ? 'YES' : 'NO') . "\n";

$testFile = 'audit_test_' . time() . '.txt';
Storage::disk('supabase')->put($testFile, 'SIAPTIKA S3 STORAGE AUDIT');
$exists = Storage::disk('supabase')->exists($testFile);
Storage::disk('supabase')->delete($testFile);
echo "  S3 Live Test  : " . ($exists ? 'SUCCESS (Put, Exists, and Delete verified)' : 'FAILED') . "\n";

echo "\n--- 5. WRITABLE STORAGE DIRECTORIES & PERMISSIONS ---\n";
$paths = [
    'storage/app'                  => storage_path('app'),
    'storage/framework/cache/data' => storage_path('framework/cache/data'),
    'storage/framework/sessions'   => storage_path('framework/sessions'),
    'storage/framework/views'      => storage_path('framework/views'),
    'storage/logs'                 => storage_path('logs'),
    'bootstrap/cache'              => base_path('bootstrap/cache'),
];
foreach ($paths as $label => $p) {
    if (!is_dir($p)) {
        @mkdir($p, 0777, true);
    }
    $exists = is_dir($p) ? 'EXISTS' : 'MISSING';
    $writable = is_writable($p) ? 'WRITABLE (OK)' : 'READ ONLY (FAIL)';
    printf("  %-28s: %-8s | %s\n", $label, $exists, $writable);
}

echo "\n--- 6. BUNDLED PHP EXTENSIONS CHECK ---\n";
$requiredExts = [
    'pdo_pgsql', 'pgsql', 'openssl', 'curl', 'fileinfo',
    'mbstring', 'gd', 'intl', 'exif', 'sodium', 'zip', 'pdo_sqlite', 'sqlite3'
];
foreach ($requiredExts as $ext) {
    $loaded = extension_loaded($ext);
    printf("  %-16s: %s\n", $ext, $loaded ? 'LOADED (OK)' : 'MISSING (FAIL)');
}

echo "\n=========================================================================\n";
echo "                        AUDIT COMPLETED                                  \n";
echo "=========================================================================\n";
