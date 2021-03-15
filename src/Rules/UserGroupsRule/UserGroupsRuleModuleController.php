<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\UserGroupsRule;


use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Router\Router;
use EvolutionCMS\Models\MembergroupName;
use Illuminate\Support\Carbon;

class UserGroupsRuleModuleController implements IRuleModuleController
{

    /**
     * @var Router
     */
    private Router $router;

    public function __construct(Router $router)
    {

        $this->router = $router;
    }

    public function init()
    {
        $this->router->addRoute('rule-user-groups-load',[self::class,'load']);
        // TODO: Implement init() method.
    }

    public function load(){

        return MembergroupName::select(['id','name as value'])->get();
    }

    public function getRule(Discount $discount){


        if($discount->rule_user_groups){
            return [
                'id'=> $discount->rule_user_groups,
                'value'=>$discount->ruleUserGroupsModel->name
            ];
        }

        return null;
    }

    public function saveRule($updateRow,$requestRules)
    {

        if(isset($requestRules['userGroups'])){
            $updateRow['rule_user_groups'] = $requestRules['userGroups']['id'];
        }
        else{
            $updateRow['rule_user_groups'] = null;
        }

        return $updateRow;
    }
}