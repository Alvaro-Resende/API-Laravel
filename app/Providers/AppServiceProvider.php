<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registre aqui serviços da aplicação, se necessário
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inicializações que precisam acontecer na aplicação, se necessário
    }
}
