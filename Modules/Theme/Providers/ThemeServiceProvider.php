<?php

namespace Modules\Theme\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Theme\Manager\StylistThemeManager;
use Modules\Theme\Manager\ThemeManager;

class ThemeServiceProvider extends ServiceProvider
{
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
        // $this->registerTranslations();
        $this->registerConfig();
        // $this->registerViews();
        // $this->registerFactories();
        // $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->registerAllThemes();
        $this->setActiveTheme();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindThemeManager();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('theme.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'theme'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/theme');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/theme';
        }, \Config::get('view.paths')), [$sourcePath]), 'theme');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/theme');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'theme');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'theme');
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
     * Bind the theme manager
     */
    private function bindThemeManager()
    {
        $this->app->singleton(ThemeManager::class, function ($app) {
            return new StylistThemeManager($app['files']);
        });
    }

    /**
     * Set the active theme based on the settings
     */
    private function setActiveTheme()
    {
        // if ($this->app->runningInConsole() || ! app('asgard.isInstalled')) {
        //     return;
        // }

        if ($this->inAdministration()) {
            $themeName = $this->app['config']->get('sorter.core.core.admin-theme');

            return $this->app['stylist']->activate($themeName, true);
        }

        $themeName = $this->app['setting.settings']->get('core::template', null, 'Flatly');

        return $this->app['stylist']->activate($themeName, true);
    }

    /**
     * Check if we are in the administration
     * @return bool
     */
    private function inAdministration()
    {
        // $segment = config('laravellocalization.hideDefaultLocaleInURL', false) ? 1 : 2;

        // return $this->app['request']->segment($segment) === $this->app['config']->get('asgard.core.core.admin-prefix');
        return true;
    }

    /**
     * Register all themes with activating them
     */
    private function registerAllThemes()
    {
        $directories = $this->app['files']->directories(config('stylist.themes.paths', [base_path('/Themes')])[0]);

        foreach ($directories as $directory) {
            $this->app['stylist']->registerPath($directory);
        }
    }
}
