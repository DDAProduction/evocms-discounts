<?php namespace EvolutionCMS\EvocmsDiscounts;

use Carbon\Carbon;
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



//        var_dump(Carbon::today()->subYears(2)->format('d-m-Y'));
//        die();

        evo()->singleton(Router::class);
        evo()->singleton(Config::class);


        $this->commands([
            ApplyCartCumulativeUpdate::class
        ]);

        $this->loadPluginsFrom(
            dirname(__DIR__) . '/assets/plugins/'
        );

        $this->publishes([__DIR__ . '/../public/modules/evocms-discounts/' => public_path('assets/modules/evocms-discounts/')]);

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');



        $this->loadViewsFrom(__DIR__.'/../view','EvocmsDiscounts');
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'EvocmsDiscounts');



        $this->app->registerModule(
            'EvocmsDiscounts',
            dirname(__DIR__).'/assets/modules/module.php'
        );


    }
}