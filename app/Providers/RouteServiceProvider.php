<?php

namespace App\Providers;

use App\Models\Wallet;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ExchangeRatesController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ExchangeCurrencyController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
         
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));


            Route::redirect('/', '/exchange-rates');
            Route::get('/currency/{id}', [ExchangeRatesController::class, 'getById']);
            Route::get('/currency}', [ExchangeRatesController::class, 'getAll']);
            Route::get('/user/{id}/exchange/{from}/to/{to}/with/{amount}', [ExchangeRatesController::class, 'exchange']);

            Route::get('/exchange-rates', [ExchangeRatesController::class, 'getExchangeRatesPage']);
            Route::get('/wallet', [WalletController::class, 'getWalletsPage']);
            Route::get('/exchange-currency', [ExchangeRatesController::class, 'getExchangeCurrencyPage']);
            
            Route::post('/user/exchange', [ExchangeRatesController::class, 'exchangeCurrency']);
        });
    }
}