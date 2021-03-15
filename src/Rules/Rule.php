<?php

namespace EvolutionCMS\EvocmsDiscounts\Rules;

use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleController;
use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleModuleController;

class Rule
{

    public function getController(): IRuleController
    {
        return evo()->make($this->controller);
    }


    public function getModuleController(): IRuleModuleController
    {
        return evo()->make($this->moduleController);
    }


    public function getView(): string
    {
        return $this->view;
    }

    private string $controller;
    private string $moduleController;
    private string $view;

    public function __construct(string $controller, string $moduleController, string $view)
    {
        $this->controller = $controller;
        $this->moduleController = $moduleController;
        $this->view = $view;
    }

}