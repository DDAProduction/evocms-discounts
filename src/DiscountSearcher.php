<?php
namespace EvolutionCMS\EvocmsDiscounts;

use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Rules\RulesLoader;

class DiscountSearcher
{

    public function searchAvailableDiscounts(){

        /** @var \Illuminate\Database\Eloquent\Builder $q */
        $q = Discount::select('discounts.*');

        $ruleLoader = evo()->make(RulesLoader::class);
        $rules = $ruleLoader->loadRules();


        foreach ($rules as $ruleId => $rule) {
            if(in_array($ruleId,['periodFrom','periodTo','userGroups','users'])){
                $rule->getController()->updateQuery($q,[]);
            }
        }
        $q->groupBy('discounts.id');

        /** @var Discount[] $discounts */
        return $q->where('active', 1)->get();

    }
}