<?php

namespace Beartropy\AlertSystem;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Beartropy\AlertSystem\Livewire\ManageLogs;
use Beartropy\AlertSystem\Livewire\ManageTypes;
use Beartropy\AlertSystem\Livewire\ManageChannels;
use Beartropy\AlertSystem\Livewire\ManageRecipients;

class AlertSystemServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/lang', 'alert-system');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'alert-system');
        $this->loadRoutesFrom(__DIR__ . '/../routes/alert-system.php');

        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/alert-system'),
        ], 'alert-system-views');

        $this->publishes([
            __DIR__.'/lang' => $this->app->langPath('vendor/alert-system'),
        ], 'alert-system-lang');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'alert-system-migrations');

        $this->publishes([
            __DIR__.'/../config/alert-system.php' => config_path('alert-system.php'),
        ], 'alert-system-config');

        $this->publishes([
            __DIR__.'/../database/seeders' => database_path('seeders'),
        ], 'alert-system-seeders');

        Livewire::component('alert-system.manage-types', ManageTypes::class);
        Livewire::component('alert-system.manage-recipients', ManageRecipients::class);
        Livewire::component('alert-system.manage-channels', ManageChannels::class);
        Livewire::component('alert-system.manage-logs', ManageLogs::class);
    }
    
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/alert-system.php', 'alert-system'
        );

        $this->app->singleton(\Beartropy\AlertSystem\Services\TelegramService::class);
        
        $this->app->singleton('alert-system', function ($app) {
            return new AlertService();
        });
    }

}
