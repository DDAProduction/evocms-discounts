<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\PeriodToRule;


use Carbon\Carbon;
use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleController;

class PeriodToRuleController implements IRuleController
{

    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query, array $values)
    {

        $periodTo = $values['period_to'] ?? Carbon::now();

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use($periodTo){

            $query->whereNull('discounts.rule_period_to');
            $query->orWhere('discounts.rule_period_to','>=',$periodTo);
        });
    }
}