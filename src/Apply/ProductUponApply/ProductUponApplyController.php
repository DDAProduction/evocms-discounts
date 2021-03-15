<?php

namespace EvolutionCMS\EvocmsDiscounts\Apply\ProductUponApply;


use EvolutionCMS\EvocmsDiscounts\Contracts\IApplyProductController;

class ProductUponApplyController implements IApplyProductController
{

    public function getCountProductWithDiscount($product, $applyValue)
    {




        if($applyValue['type'] == 'sum'){
            return $this->getCountProductWithDiscountForSum($product, $applyValue);
        }
        if($applyValue['type'] == 'pc'){
            return $this->getCountProductWithDiscountForPC($product, $applyValue);
        }


        return false;
    }

    private function getCountProductWithDiscountForSum($product, $applyValue)
    {
        $total = $product['price'] * $product['count'];
        $from = floatval($applyValue['from']);

        if($total >= $from){
            return $product['count'];
        }

        return false;
    }

    private function getCountProductWithDiscountForPC($product, $applyValue)
    {

        $from = intval($applyValue['from']);

        if($product['count'] >= $from){
            return $product['count'];
        }


        return false;
    }
}