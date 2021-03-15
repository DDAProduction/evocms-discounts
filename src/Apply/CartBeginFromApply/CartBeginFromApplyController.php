<?php


namespace EvolutionCMS\EvocmsDiscounts\Apply\CartBeginFromApply;


use EvolutionCMS\EvocmsDiscounts\Contracts\IApplyCartController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;

class CartBeginFromApplyController implements IApplyCartController
{

    public function checkDiscount(Discount $discount,array $cart,array $applyValue)
    {

        if($applyValue['type'] == 'sum'){
            return $this->checkDiscountFormSum($cart, $applyValue);
        }
        if($applyValue['type'] == 'pc'){
            return $this->checkDiscountFormSumPc($cart, $applyValue);
        }

        return false;
    }

    private function checkDiscountFormSum(array $cart, array $applyValue)
    {
        $from = floatval($applyValue['from']);
        $cartSum = floatval($cart['sum']);

        if($cartSum >= $from){
            return true;
        }
        return  false;
    }

    private function checkDiscountFormSumPc(array $cart, array $applyValue)
    {
        $from = intval($applyValue['from']);
        $cartCount = intval($cart['count']);

        if($cartCount >= $from){
            return true;
        }
        return  false;
    }
}