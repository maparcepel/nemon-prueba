<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/test', function () {
    return "¡Laravel funciona!";
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
    return '<h1>Simple Laravel Route - Working!</h1><p>Environment: ' . app()->environment() . '</p>';
});

Route::get('/', function () {
    try {
        return Inertia::render('welcome', [
            'canRegister' => Features::enabled(Features::registration()),
        ]);
    } catch (\Exception $e) {
        return 'Inertia Error: ' . $e->getMessage() . '<br><br><a href="/simple">Try Simple Route</a> | <a href="/debug">Debug Info</a>';
    }
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
});

require __DIR__.'/settings.php';
