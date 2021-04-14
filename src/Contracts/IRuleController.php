<?php

namespace EvolutionCMS\EvocmsDiscounts\Contracts;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;

interface IRuleController
{

    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query,array $values);

}