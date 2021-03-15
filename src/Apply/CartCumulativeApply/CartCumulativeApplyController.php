<?php


namespace EvolutionCMS\EvocmsDiscounts\Apply\CartCumulativeApply;


use EvolutionCMS\EvocmsDiscounts\Contracts\IApplyCartController;
use EvolutionCMS\EvocmsDiscounts\Contracts\IDiscountQueryUpdater;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;

class CartCumulativeApplyController implements IApplyCartController, IDiscountQueryUpdater
{

    public function checkDiscount(Discount $discount,array $cart,array $applyValue)
    {
        if($discount->achieved){
            return true;
        }
        return  false;
    }

    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query, array $data = [])
    {

        $query->leftJoin('discounts_cart_cumulative_achieved',function ($join){

            $userId = evo()->getLoginUserID();;
            $userId = 1;
            $join->on('discounts_cart_cumulative_achieved.discount_id','=','discounts.id');
            $join->where('discounts_cart_cumulative_achieved.user_id','=',$userId);
        })

        ;
        $query->addSelect('discounts_cart_cumulative_achieved.achieved');
    }
}