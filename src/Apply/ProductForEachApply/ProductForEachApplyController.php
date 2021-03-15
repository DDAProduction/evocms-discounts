<?php

namespace EvolutionCMS\EvocmsDiscounts\Apply\ProductForEachApply;


use EvolutionCMS\EvocmsDiscounts\Contracts\IApplyProductController;

class ProductForEachApplyController implements IApplyProductController
{
    public function getCountProductWithDiscount($product, $applyValue)
    {
        $from = intval($applyValue['from']);

        if($product['count']>= $from){
            return floor($product['count'] / $from);
        }

        return false;
    }
}