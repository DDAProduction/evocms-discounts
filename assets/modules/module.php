<?php

use EvolutionCMS\EvocmsDiscounts\Controllers\DiscountController;
use EvolutionCMS\EvocmsDiscounts\Router\Router;
use EvolutionCMS\EvocmsDiscounts\Rules\RulesLoader;
use Illuminate\Http\Request;

$_POST['test'] = 'x';
$request = Request::createFromGlobals();


$di = [
    'moduleUrl' => 'index.php?a=112&id='.$request->query('id').'&'
];

$router = evo()->make(Router::class);

$router->addRoute('discounts-show', [DiscountController::class, 'discountsShow']);
$router->addRoute('discounts-load', [DiscountController::class, 'discountsLoad']);
$router->addRoute('discounts-add', [DiscountController::class, 'discountsAdd']);
$router->addRoute('discounts-update', [DiscountController::class, 'discountsUpdate']);
$router->addRoute('discounts-remove', [DiscountController::class, 'discountsRemove']);

$ruleLoader = evo()->make(RulesLoader::class);
$ruleLoader->initRules($ruleLoader->loadRules());

$appliesLoader = evo()->make(\EvolutionCMS\EvocmsDiscounts\Apply\AppliesLoader::class);
$appliesLoader->initApplies($appliesLoader->loadApplies());


$action = $request->has('action')?$request->get('action'):'discounts-show';


$route = $router->match($action);



$response = call_user_func_array([evo()->make($route[0],$di),$route[1]],[$request]);

if(is_array($response)){
    header('Content-type:text/json');
    echo json_encode($response,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
else{
    echo $response;
}