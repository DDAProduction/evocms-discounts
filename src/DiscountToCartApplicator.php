<?php

namespace EvolutionCMS\EvocmsDiscounts;


use Commerce\Carts\ProductsCart;
use Commerce\CartsManager;
use EvolutionCMS\EvocmsDiscounts\Apply\AppliesManager;
use EvolutionCMS\EvocmsDiscounts\Contracts\IApplyCartController;
use EvolutionCMS\EvocmsDiscounts\Contracts\IDiscountQueryUpdater;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Rules\RulesLoader;

class DiscountToCartApplicator
{


    /**
     * @var DiscountSumCalculator
     */
    private DiscountSumCalculator $discountSumCalculator;
    /**
     * @var AppliesManager
     */
    private AppliesManager $appliesManager;
    /**
     * @var Rules\Rule[]
     */
    private array $rules;
    /**
     * @var ProductsCart
     */
    private $productCart;

    public function __construct(AppliesManager $appliesManager, DiscountSumCalculator $discountSumCalculator)
    {

        /** @var CartsManager $cartsManager */
        $cartsManager = ci()->get('carts');

        $this->productCart = $cartsManager->getCart('products');


        $ruleLoader = evo()->make(RulesLoader::class);
        $this->rules = $ruleLoader->loadRules();
        $this->appliesManager = $appliesManager;
        $this->discountSumCalculator = $discountSumCalculator;
    }


    public function apply(&$params)
    {

        /** @var \Illuminate\Database\Eloquent\Builder $q */
        $q = Discount::select('discounts.*')->where('type', Discount::TYPE_CART);

        foreach ($this->rules as $rule) {
            $rule->getController()->updateQuery($q,[]);
        }

        foreach ($this->appliesManager->getApplies() as $apply) {
            $applyController = $apply->getController();
            if($applyController instanceof IDiscountQueryUpdater){
                $applyController->updateQuery($q);
            }
        }

        $q->groupBy('discounts.id');




        /** @var Discount[] $discounts */
        $discounts = $q->where('active', 1)->get();

        $maxDiscount = null;


        foreach ($discounts as $discount) {


            $applyId = key($discount->apply);
            $applyValue = $discount->apply[$applyId];

            /** @var IApplyCartController $apply */
            $apply = $this->appliesManager->getApplyById($applyId)->getController();


            $cart = $this->calcTotal($discount->exclude_sales);


            $discountCheck = $apply->checkDiscount($discount,$cart,$applyValue);

            if($discountCheck !== true){
                continue;
            }

            $discountSum = $this->discountSumCalculator->calculate($cart['sum'],$discount);

            if($maxDiscount === null || $maxDiscount['sum'] < $discountSum){
                $maxDiscount = [
                    'discount'=>$discount,
                    'sum'=>$discountSum
                ];
            }
        }

        if($maxDiscount){
            $params['total'] += $maxDiscount['sum'];
            $params['rows']['discount'] = [
                'title' => $maxDiscount['discount']->title,
                'price' => $maxDiscount['sum'],
            ];
        }


    }

    private function calcTotal(bool $exclude_sales)
    {
        $count = 0;
        $sum = 0;


        foreach ($this->productCart->getItems() as $item) {

            if ($exclude_sales && isset($item['meta']['discount'])) {
                continue;
            }

            $count += $item['count'];

            $sum += $item['count'] * $item['price'];
        }

        return [
            'count' => $count,
            'sum' => $sum,
        ];
    }


}