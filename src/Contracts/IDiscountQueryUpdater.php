<?php


namespace EvolutionCMS\EvocmsDiscounts\Contracts;


interface IDiscountQueryUpdater
{
    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query,array $data = []);

}