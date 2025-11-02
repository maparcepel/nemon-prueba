<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/test', function () {
    return '¡Laravel funciona!';
});

Route::get('/api-test', function () {
    return [
        'message' => 'API funcionando',
        'timestamp' => now(),
        'endpoints' => [
            'POST /api/calculate' => 'Calcular precio de energía',
            'GET /api/consumptions' => 'Obtener datos de consumo',
            'GET /api/prices' => 'Obtener precios',
        ],
    ];
});

Route::get('/welcome-full', function () {
    try {
        return Inertia::render('welcome');
    } catch (\Exception $e) {
        return 'Error en welcome completa: '.$e->getMessage();
    }
});

Route::get('/debug', function () {
    $manifestPath = public_path('build/manifest.json');
    $manifestExists = file_exists($manifestPath);

    return [
        'app_env' => app()->environment(),
        'app_debug' => config('app.debug'),
        'app_url' => config('app.url'),
        'app_key_set' => config('app.key') ? 'YES' : 'NO',
        'database' => config('database.default'),
        'cache' => config('cache.default'),
        'vite_manifest_exists' => $manifestExists,
        'vite_manifest_path' => $manifestPath,
        'public_path' => public_path(),
        'build_files' => file_exists(public_path('build')) ? array_slice(scandir(public_path('build')), 0, 10) : 'build directory not found',
    ];
});

Route::get('/simple', function () {
    return '<h1>Simple Laravel Route - Working!</h1><p>Environment: '.app()->environment().'</p>';
});

Route::get('/', function () {
    try {
        return Inertia::render('welcome-simple');
    } catch (\Exception $e) {
        return 'Error en Inertia: '.$e->getMessage().'<br><br>'.
               '<a href="/simple">Ruta simple</a> | '.
               '<a href="/debug">Debug</a> | '.
               '<a href="/api-test">API Test</a>';
    }
})->name('home');

// Rutas de autenticación temporalmente deshabilitadas
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('dashboard', function () {
//         return Inertia::render('dashboard');
//     })->name('dashboard');
// });

// require __DIR__.'/settings.php';
