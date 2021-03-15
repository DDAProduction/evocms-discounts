<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\CategoriesRule;


use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleController;
use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleModuleController;
use Illuminate\Database\Query\Builder;

class CategoriesRuleController implements IRuleController
{


    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query, array $product)
    {
        $query->leftJoin('discounts_categories','discounts_categories.discount_id','=','discounts.id');

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use($product){
           $query->whereNull('discounts_categories.category_id');
            $query->orWhere('discounts_categories.category_id',$product['meta']['parent']);
        });


    }
}