<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\PeriodFromRule;


use Carbon\Carbon;
use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleController;

class PeriodFromRuleController implements IRuleController
{

    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query, array $values)
    {

        $periodFrom = $values['period_from'] ?? Carbon::now();

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use($periodFrom){
            $query->whereNull('discounts.rule_period_from');
            $query->orWhere('discounts.rule_period_from','<=',$periodFrom);
        });

    }
}