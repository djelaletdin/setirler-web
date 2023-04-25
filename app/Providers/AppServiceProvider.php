<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Traits\Macroable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Builder::macro('orderByTitleWithoutQuotes', function ($order = 'asc') {
            $order = strtolower($order) === 'desc' ? 'DESC' : 'ASC';
            $this->orderByRaw("REPLACE(REPLACE(REPLACE(title, '\"', ''), '\'', ''), '«', '') {$order}");
        });
    }
}
