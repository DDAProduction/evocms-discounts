<?php

use Commerce\CartsManager;
use EvolutionCMS\EvocmsDiscounts\DiscountToProductApplicator;

Event::listen('evolution.OnWebPageInit',function (){

    $plugin = evo()->getPluginCode('Commerce');
    evo()->evalPlugin($plugin['code'],json_decode($plugin['props'],true));

    $ruleLoader = evo()->make(\EvolutionCMS\EvocmsDiscounts\Rules\RulesLoader::class);
    $ruleLoader->initRules($ruleLoader->loadRules());

});

Event::listen('evolution.OnBeforeCartItemAdding',function ($params){

    $params['item']['meta']['parent'] = evo()->getPageInfo($params['item']['id'],'','site_content.parent')['parent'];

});



Event::listen(['evolution.OnCollectSubtotals'],function ($params){

    /** @var CartsManager $cartsManager */
    $cartsManager = ci()->get('carts');
    /** @var \Commerce\Carts\ProductsCart $productCart */
    $productCart = $cartsManager->getCart('products');

    $applicator = evo()->make(\EvolutionCMS\EvocmsDiscounts\DiscountToCartApplicator::class);
    $discount = $applicator->getDiscount($productCart->getItems());

    if ($discount) {

        $params['total'] -= $discount['sum'];
        $params['rows']['discount'] = [
            'title' => $discount['discount']->title,
            'price' => -$discount['sum'],
        ];

    }

});

Event::listen(['evolution.OnCartChanged'],function (){


    $applicator = evo()->make(DiscountToProductApplicator::class);
    $applicator->apply();

});