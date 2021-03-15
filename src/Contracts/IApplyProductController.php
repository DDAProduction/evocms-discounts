<?php


namespace EvolutionCMS\EvocmsDiscounts\Contracts;


interface IApplyProductController
{
    public function getCountProductWithDiscount($product, $applyValue);

}