<?php


namespace EvolutionCMS\EvocmsDiscounts\Apply;


use EvolutionCMS\EvocmsDiscounts\Contracts\Test;
use EvolutionCMS\EvocmsDiscounts\Contracts\IApplyModuleController;

class Apply
{


    private string $controller;
    private string $moduleController;
    private string $view;

    public function __construct(string $controller, string $moduleController, string $view)
    {
        $this->controller = $controller;
        $this->moduleController = $moduleController;
        $this->view = $view;
    }

    public function getController()
    {
        return evo()->make($this->controller);
    }


    public function getModuleController(): IApplyModuleController
    {
        return evo()->make($this->moduleController);
    }


    public function getView(): string
    {
        return $this->view;
    }

}