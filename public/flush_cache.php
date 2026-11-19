<?php
/**
 * Script de limpieza para PHP-FPM y cache de vistas compiladas
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
$viewsDir = dirname(__DIR__) . '/storage/framework/views';
$deletedViews = 0;
if (is_dir($viewsDir)) {
    foreach (glob($viewsDir . '/*.php') as $file) {
        if (@unlink($file)) {
            $deletedViews++;
        }
    }
}
$result['views_deleted'] = $deletedViews;

// 3. Limpiar cache general en bootstrap/cache
$bootstrapCache = dirname(__DIR__) . '/bootstrap/cache';
if (is_dir($bootstrapCache)) {
    foreach (['config.php', 'routes-v7.php', 'packages.php', 'services.php'] as $f) {
        $p = $bootstrapCache . '/' . $f;
        if (file_exists($p)) {
            @unlink($p);
            $result['bootstrap_cleared'][] = $f;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
