<?php


namespace EvolutionCMS\EvocmsDiscounts;


use EvolutionCMS\EvocmsDiscounts\Models\Discount;

class DiscountSumCalculator
{
    public function calculate($itemSum,Discount $discount){
        if($discount->discount_type == Discount::DISCOUNT_TYPE_AMOUNT){
            return $discount->discount_value;
        }


        if($discount->discount_type == Discount::DISCOUNT_TYPE_PERCENT){

            return round($itemSum * $discount->discount_value / 100);
        }


        return false;
    }
}