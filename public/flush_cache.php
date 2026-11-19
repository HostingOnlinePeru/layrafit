<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

/**
 * Script de limpieza para PHP-FPM, cache de vistas compiladas y reparador UTF-8 de base de datos
 */
if (($_GET['key'] ?? '') !== 'layra_deploy_2026') {
    http_response_code(403);
    exit('Forbidden');
}

$result = [];

// 1. Resetear OPcache de PHP-FPM
if (function_exists('opcache_reset')) {
    $res = opcache_reset();
    $result['opcache_reset'] = $res ? 'SUCCESS' : 'FAILED';
} else {
    $result['opcache_reset'] = 'NOT_AVAILABLE';
}

// 2. Limpiar vistas compiladas en storage/framework/views
$viewsDir = dirname(__DIR__).'/storage/framework/views';
$deletedViews = 0;
if (is_dir($viewsDir)) {
    foreach (glob($viewsDir.'/*.php') as $file) {
        if (@unlink($file)) {
            $deletedViews++;
        }
    }
}
$result['views_deleted'] = $deletedViews;

// 3. Limpiar cache general en bootstrap/cache
$bootstrapCache = dirname(__DIR__).'/bootstrap/cache';
if (is_dir($bootstrapCache)) {
    foreach (['config.php', 'routes-v7.php', 'packages.php', 'services.php'] as $f) {
        $p = $bootstrapCache.'/'.$f;
        if (file_exists($p)) {
            @unlink($p);
            $result['bootstrap_cleared'][] = $f;
        }
    }
}

// 4. Reparar caracteres mojibake / doble codificación UTF-8 en la base de datos
try {
    require dirname(__DIR__).'/vendor/autoload.php';
    $app = require_once dirname(__DIR__).'/bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();

    $replacements = [
        'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú',
        'Ã ' => 'Á', 'Ã‰' => 'É', 'Ã ' => 'Í', 'Ã“' => 'Ó', 'Ãš' => 'Ú',
        'Ã±' => 'ñ', 'Ã‘' => 'Ñ',
        'Â¡' => '¡', 'Â¿' => '¿',
        'Â®' => '®', 'Â©' => '©',
        'Â°' => '°',
        'â€“' => '–', 'â€”' => '—', 'â€œ' => '“', 'â€' => '”', 'â€™' => '’',
        'Â' => '',
    ];

    $driver = DB::connection()->getDriverName();
    $tables = [];

    if ($driver === 'sqlite') {
        $tables = collect(DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"))->pluck('name')->toArray();
    } else {
        $tables = collect(DB::select('SHOW TABLES'))->map(fn ($r) => array_values((array) $r)[0])->toArray();
    }

    $repaired = [];
    foreach ($tables as $table) {
        if (in_array($table, ['migrations', 'sessions', 'failed_jobs', 'cache', 'cache_locks'])) {
            continue;
        }
        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $updates = [];
            foreach ((array) $row as $col => $val) {
                if (is_string($val)) {
                    $cleaned = strtr($val, $replacements);
                    if ($cleaned !== $val) {
                        $updates[$col] = $cleaned;
                    }
                }
            }
            if (! empty($updates)) {
                $id = $row->id ?? null;
                if ($id) {
                    DB::table($table)->where('id', $id)->update($updates);
                    $repaired[] = "$table (ID: $id): ".implode(', ', array_keys($updates));
                }
            }
        }
    }
    $result['db_repaired_records'] = $repaired;
} catch (Throwable $e) {
    $result['db_repair_error'] = $e->getMessage();
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
