<?php
namespace EvolutionCMS\EvocmsDiscounts\Router;


use EvolutionCMS\EvocmsDiscounts\Router\RouteNotFoundException;

class Router
{
    private $routes = [];


    public function addRoute($action,$callable){
        $this->routes[$action] = $callable;
    }

    public function match($action){

        if(!array_key_exists($action,$this->routes)){
            throw new RouteNotFoundException();
        }
        return $this->routes[$action];
    }

    public function getRoutes(){
        return $this->routes;
    }
}