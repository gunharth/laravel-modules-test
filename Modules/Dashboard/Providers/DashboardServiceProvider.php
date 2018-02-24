<?php

namespace Modules\Dashboard\Providers;

// use Illuminate\Support\ServiceProvider;
// use Illuminate\Database\Eloquent\Factory;
// use Modules\Core\Events\BuildingSidebar;
// use Modules\Core\Traits\CanGetSidebarClassForModule;
// use Modules\Core\Traits\CanPublishConfiguration;
// use Modules\Dashboard\Events\Handlers\RegisterDashboardSidebar;
// use Modules\Theme\Manager\StylistThemeManager;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Events\BuildingSidebar;
// use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Core\Traits\CanGetSidebarClassForModule;
use Modules\Core\Traits\CanPublishConfiguration;
// use Modules\Dashboard\Entities\Widget;
use Modules\Dashboard\Events\Handlers\RegisterDashboardSidebar;
// use Modules\Dashboard\Repositories\Cache\CacheWidgetDecorator;
// use Modules\Dashboard\Repositories\Eloquent\EloquentWidgetRepository;
// use Modules\Dashboard\Repositories\WidgetRepository;
use Modules\Theme\Manager\StylistThemeManager;

class DashboardServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration, CanGetSidebarClassForModule;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(WidgetRepository::class, function () {
        //     $repository = new EloquentWidgetRepository(new Widget());

        //     if (! config('app.cache')) {
        //         return $repository;
        //     }

        //     return new CacheWidgetDecorator($repository);
        // });

        $this->app['events']->listen(
            BuildingSidebar::class,
            $this->getSidebarClassForModule('dashboard', RegisterDashboardSidebar::class)
        );

        // $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
        //     $event->load('dashboard', array_dot(trans('dashboard::dashboard')));
        // });
    }

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(StylistThemeManager $theme)
    {
        $this->publishes([
            __DIR__ . '/../Resources/views' => base_path('resources/views/sorter/dashboard'),
        ], 'views');

        $this->app['view']->prependNamespace(
            'dashboard',
            $theme->find(config('sorter.core.core.admin-theme'))->getPath() . '/views/modules/dashboard'
        );

        $this->publishConfig('dashboard', 'config');


        //$this->registerTranslations();
        //$this->registerConfig();
        $this->registerViews();
        // $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }


    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/dashboard');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/dashboard';
        }, \Config::get('view.paths')), [$sourcePath]), 'dashboard');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    // public function registerTranslations()
    // {
    //     $langPath = resource_path('lang/modules/dashboard');

    //     if (is_dir($langPath)) {
    //         $this->loadTranslationsFrom($langPath, 'dashboard');
    //     } else {
    //         $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'dashboard');
    //     }
    // }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    // public function registerFactories()
    // {
    //     if (! app()->environment('production')) {
    //         app(Factory::class)->load(__DIR__ . '/../Database/factories');
    //     }
    // }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    // public function provides()
    // {
    //     return [];
    // }
}
