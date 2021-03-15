<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\UsersRule;


use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleController;
use EvolutionCMS\Models\MemberGroup;

class UsersRuleController implements IRuleController
{

    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query, array $product)
    {
        $userId = evo()->getLoginUserID();

        if($userId === false){
            $userId = 0;
        }

        $userId = 2;

        $query->leftJoin('discounts_users','discounts_users.discount_id','=','discounts.id');

        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use($userId){
            $query->whereNull('discounts_users.user_id');
            $query->orWhere('discounts_users.user_id',$userId);
        });
    }
}