<?php
namespace EvolutionCMS\EvocmsDiscounts\Rules\ProductsRule;


use Carbon\Carbon;
use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;

class ProductsRuleController implements IRuleController
{


    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query, array $values)
    {

        $product = $values['product'] ?? [];

        $query->leftJoin('discounts_products','discounts_products.discount_id','=','discounts.id');

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use($product){
            $query->whereNull('discounts_products.product_id');
            $query->orWhere('discounts_products.product_id',$product['id']);
        });

    }
}