<?php

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


    $applicator = evo()->make(\EvolutionCMS\EvocmsDiscounts\DiscountToCartApplicator::class);

    $discount = $applicator->apply($params);
});

Event::listen(['evolution.OnCartChanged'],function (){


    $applicator = evo()->make(DiscountToProductApplicator::class);
    $applicator->apply();

});