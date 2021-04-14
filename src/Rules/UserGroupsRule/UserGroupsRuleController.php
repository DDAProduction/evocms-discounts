<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\UserGroupsRule;


use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleController;
use EvolutionCMS\Models\MemberGroup;

class UserGroupsRuleController implements IRuleController
{

    public function updateQuery(\Illuminate\Database\Eloquent\Builder $query, array $values)
    {
        $userGroupIds = [];

        if (isset($values['user_groups'])) {
            $userGroupIds = $values['user_groups'];
        } else {
            $userId = evo()->getLoginUserID();

            if ($userId) {
                $userGroupIds = MemberGroup::where('member', $userId)->pluck('user_group')->toArray();
            }
        }



        $query->where(function (\Illuminate\Database\Eloquent\Builder $query) use($userGroupIds){
            $query->whereNull('discounts.rule_user_groups');

            if(!empty($userGroupIds)){
                $query->orWhereIn('discounts.rule_user_groups',$userGroupIds);
            }
        });


    }
}