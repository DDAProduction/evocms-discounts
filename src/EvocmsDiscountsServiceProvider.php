<?php namespace EvolutionCMS\EvocmsDiscounts;

use EvolutionCMS\EvocmsDiscounts\Console\ApplyCartCumulativeUpdate;
use EvolutionCMS\ServiceProvider;
use EvolutionCMS\EvocmsDiscounts\Router\Router;


class EvocmsDiscountsServiceProvider extends ServiceProvider
{
    protected $namespace = 'Discounts';
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        evo()->singleton(Router::class);
        evo()->singleton(Config::class);


        $this->commands([
            ApplyCartCumulativeUpdate::class
        ]);

        $this->loadPluginsFrom(
            dirname(__DIR__) . '/assets/plugins/'
        );

        $this->publishes([__DIR__ . '/../public' => public_path('assets/modules/evocms-discounts/')]);

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');



        $this->loadViewsFrom(__DIR__.'/../view','EvocmsDiscounts');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'EvocmsDiscounts');



        $this->app->registerModule(
            'EvocmsDiscounts',
            dirname(__DIR__).'/assets/modules/module.php'
        );


    }
}