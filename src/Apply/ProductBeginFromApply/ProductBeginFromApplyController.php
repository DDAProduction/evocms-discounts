<?php


namespace EvolutionCMS\EvocmsDiscounts\Apply\ProductBeginFromApply;


use EvolutionCMS\EvocmsDiscounts\Contracts\IApplyProductController;

class ProductBeginFromApplyController implements IApplyProductController
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

        if($from < $total){
            $diff = $total - $from;

            return ceil($diff / $product['price']);
        }

        return false;
    }

    private function getCountProductWithDiscountForPC($product, $applyValue)
    {
        $from = intval($applyValue['from']);

        if($product['count'] >= $from){
            return $product['count'] - $from +1;
        }

        return false;
    }
}