<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jika kamu ingin pakai auto-namespace di controller route,
     * ubah protected $namespace jadi 'App\Http\Controllers'
     * Tapi sekarang Laravel rekomendasi pakai null dan FQCN.
     */
    protected $namespace = null;

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();

        $this->routes(function () {
            // Daftarkan routes API tanpa namespace prefix otomatis,
            // supaya controller di api.php harus pakai FQCN (use App\Http\Controllers\...)
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Daftarkan routes Web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
