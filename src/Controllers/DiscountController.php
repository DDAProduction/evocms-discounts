<?php

namespace EvolutionCMS\EvocmsDiscounts\Controllers;


use EvolutionCMS\EvocmsDiscounts\Apply\AppliesLoader;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Router\Router;
use EvolutionCMS\EvocmsDiscounts\Rules\RulesLoader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class DiscountController
{
    /**
     * @var void
     */
    private $rules;
    /**
     * @var RulesLoader
     */
    private RulesLoader $rulesLoader;
    private $moduleUrl;
    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var AppliesLoader
     */
    private AppliesLoader $appliesLoader;
    /**
     * @var \EvolutionCMS\EvocmsDiscounts\Apply\Apply[]
     */
    private array $applies;

    public function __construct(Router $router,RulesLoader $rulesLoader,AppliesLoader $appliesLoader,$moduleUrl)
    {
        $this->rules = $rulesLoader->loadRules();
        $this->applies = $appliesLoader->loadApplies();
        $this->router = $router;

        $this->rulesLoader = $rulesLoader;
        $this->moduleUrl = $moduleUrl;
        $this->appliesLoader = $appliesLoader;
    }

    public function discountsShow(Request $request){
        return View::make('EvocmsDiscounts::module')->with([
            'rules'=>$this->rules,
            'applies'=>$this->applies,
            'moduleUrl'=>$this->moduleUrl
        ])->render();
    }

    public function discountsLoad(Request $request)
    {


        $start = $request->has('start') ? $request->get('start') : 0;
        $count = $request->has('count') ? $request->get('count') : 10;


        $query = Discount::with([
            'users' => function ($query) {
                return $query->select(['user_attributes.id', 'fullname as title']);
            },
            'categories' => function ($query) {
                return $query->select(['site_content.id', 'pagetitle as value']);
            },
            'products' => function ($query) {
                return $query->select(['site_content.id', 'pagetitle as title']);
            },
            'ruleUserGroupsModel'
        ])
            ->orderBy('id', 'desc');


        $total = $query->count();
        $discounts  = $query->limit($count)->offset($start)->get();


        $result = [
            "total_count" => $total,
            "pos" =>  $start,
            "data" => []
        ];


        /** @var Discount[] $discounts */
        foreach ($discounts as $discount) {

            $item = [
                'id'=>$discount->id,
                'title'=>$discount->title,
                'type'=>$discount->type,
                'apply'=>$discount->apply,

                'discount_value'=>$discount->discount_value,
                'discount_type'=>$discount->discount_type,

                'active'=>$discount->active,
                'exclude_sales'=>$discount->exclude_sales,

                'rules' => []
            ];

            foreach ($this->rules as $ruleId => $rule) {

                $preparedRule = $rule->getModuleController()->getRule($discount);
                if ($preparedRule) {
                    $item['rules'][$ruleId] = $preparedRule;
                }

            }


            $result['data'][] = $item;
        }

        return $result;
    }

    public function discountsAdd(Request $request){

        $discount = new Discount();
        $discount->type = $request->get('type');
        $discount->apply = json_decode($request->get('apply'),true);

        $discount->save();
        return [
            'status'=>true,
            'newid'=>$discount->id
        ];
    }

    public function discountsUpdate(Request $request){

        $request = $request->toArray();

        $requestRules = json_decode($request['rules'],true);
        $requestApply = json_decode($request['apply'],true);



        $updateRow = [
            'id'=>$request['id'],
            'title'=>$request['title'],
            'type'=>$request['type'],
            'apply'=>$requestApply,

            'discount_value'=>$request['discount_value'],
            'discount_type'=>$request['discount_type'],

            'active'=>$request['active'],
            'exclude_sales'=>$request['exclude_sales'],
        ];


        foreach ($this->rules as $ruleId => $rule) {
            $updateRow = $rule->getModuleController()->saveRule($updateRow,$requestRules);
        }

        $discountId = $updateRow['id'];

        unset($updateRow['id']);
        Discount::findOrFail($discountId)->update($updateRow);

    }

    public function discountsRemove(Request $request){

         Discount::findOrFail($request->post('id'))->delete();

        return [
            'status' => true,
        ];
    }

}