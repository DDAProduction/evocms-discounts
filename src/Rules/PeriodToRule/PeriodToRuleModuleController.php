<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\PeriodToRule;


use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use Illuminate\Support\Carbon;

class PeriodToRuleModuleController implements IRuleModuleController
{

    public function init()
    {
        // TODO: Implement init() method.
    }

    public function prepareRuleData()
    {
        // TODO: Implement prepareRuleData() method.
    }

    public function getRule(Discount $discount){
        if($discount->rule_period_to){
            return  $discount->rule_period_to->format('Y-m-d H:i:s');
        }
        return null;
    }

    public function saveRule($updateRow,$requestRules)
    {
        if(isset($requestRules['periodTo'])){
            $updateRow['rule_period_to'] = Carbon::parse($requestRules['periodTo']);
        }
        else{
            $updateRow['rule_period_to'] = null;
        }

        return $updateRow;
    }
}