<?php

namespace Modules\Core\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\Handlers\RegisterCoreSidebar;
use Modules\Core\Foundation\Theme\ThemeManager;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Traits\CanGetSidebarClassForModule;

class CoreServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig('core', 'config');
        $this->publishConfig('core', 'core');


        $this->registerTranslations();
        // $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sorter.isInstalled', function () {
            return true === env('INSTALLED', false);
        });

        $this->app->singleton('sorter.onBackend', function () {
            return $this->onBackend();
        });

        $this->registerServices();

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('core', RegisterCoreSidebar::class)
        );
    }


    private function registerServices()
    {
        $this->app->singleton(ThemeManager::class, function ($app) {
            $path = $app['config']->get('sorter.core.core.themes_path');

            return new ThemeManager($app, $path);
        });

        $this->app->singleton('sorter.ModulesList', function () {
            return [
                'core',
                'dashboard',
                // 'media',
                // 'menu',
                // 'page',
                // 'setting',
                // 'tag',
                // 'translation',
                // 'user',
                // 'workshop',
            ];
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    // protected function registerConfig()
    // {
    //     $this->publishes([
    //         __DIR__.'/../Config/config.php' => config_path('core.php'),
    //     ], 'config');
    //     $this->mergeConfigFrom(
    //         __DIR__.'/../Config/config.php',
    //         'core'
    //     );
    // }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/core');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/core';
        }, \Config::get('view.paths')), [$sourcePath]), 'core');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/core');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'core');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'core');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Checks if the current url matches the configured backend uri
     * @return bool
     */
    private function onBackend()
    {
        $url = app(Request::class)->url();
        if (str_contains($url, config('sorter.core.core.admin-prefix'))) {
            return true;
        }

        return false;
    }
}
