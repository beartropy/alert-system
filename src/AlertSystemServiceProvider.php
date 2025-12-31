<?php

namespace Beartropy\AlertSystem;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Beartropy\AlertSystem\Livewire\ManageLogs;
use Beartropy\AlertSystem\Livewire\ManageTypes;
use Beartropy\AlertSystem\Livewire\ManageChannels;
use Beartropy\AlertSystem\Livewire\ManageRecipients;

/**
 * Service Provider for the Beartropy Alert System.
 *
 * This provider handles the registration of package resources including:
 * - Configuration merging
 * - Translations, Migrations, and Views
 * - Route loading
 * - Asset publishing
 * - Livewire component registration
 * - Service container bindings
 */
class AlertSystemServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * Perform post-registration booting of services.
     * This method:
     * - Loads translations, migrations, and views.
     * - Loads routes if configured.
     * - Defines publishable asset groups (views, lang, migrations, config, seeders).
     * - Registers Livewire components for the management UI.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/lang', 'alert-system');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'alert-system');
        if (config('alert-system.publish_routes', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/alert-system.php');
        }

        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('views/vendor/alert-system'),
        ], 'alert-system-views');

        $this->publishes([
            __DIR__ . '/lang' => $this->app->langPath('vendor/alert-system'),
        ], 'alert-system-lang');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'alert-system-migrations');

        $this->publishes([
            __DIR__ . '/../config/alert-system.php' => config_path('alert-system.php'),
        ], 'alert-system-config');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'alert-system-seeders');

        Livewire::component('alert-system.manage-types', ManageTypes::class);
        Livewire::component('alert-system.manage-recipients', ManageRecipients::class);
        Livewire::component('alert-system.manage-channels', ManageChannels::class);
        Livewire::component('alert-system.manage-logs', ManageLogs::class);
    }

    /**
     * Register any application services.
     *
     * This method:
     * - Merges the package configuration.
     * - Registers the TelegramService singleton.
     * - Registers the main AlertService singleton ('alert-system').
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/alert-system.php',
            'alert-system'
        );

        $this->app->singleton(\Beartropy\AlertSystem\Services\TelegramService::class);

        $this->app->singleton('alert-system', function ($app) {
            return new AlertService();
        });
    }
}
