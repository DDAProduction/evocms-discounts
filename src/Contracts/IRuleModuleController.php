<?php


namespace EvolutionCMS\EvocmsDiscounts\Contracts;


use EvolutionCMS\EvocmsDiscounts\Models\Discount;

interface IRuleModuleController
{
    public function init();
    public function getRule(Discount $discount);
    public function saveRule($updateRow,$requestRules);
}