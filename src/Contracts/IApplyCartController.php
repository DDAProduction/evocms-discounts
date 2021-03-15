<?php


namespace EvolutionCMS\EvocmsDiscounts\Contracts;


use EvolutionCMS\EvocmsDiscounts\Models\Discount;

interface IApplyCartController
{

    public function checkDiscount(Discount $discount,array $cart,array $applyValue);

}