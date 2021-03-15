<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\PeriodFromRule;


use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use Illuminate\Support\Carbon;

class PeriodFromRuleModuleController implements IRuleModuleController
{


    public function init()
    {
        // TODO: Implement init() method.
    }


    public function getRule(Discount $discount){

        if($discount->rule_period_from){
            return  $discount->rule_period_from->format('Y-m-d H:i:s');
        }
        return null;
    }
    public function saveRule($updateRow,$requestRules)
    {

        if(isset($requestRules['periodFrom'])){
            $updateRow['rule_period_from'] = Carbon::parse($requestRules['periodFrom']);
        }
        else{
            $updateRow['rule_period_from'] = null;
        }

        return $updateRow;
    }
}