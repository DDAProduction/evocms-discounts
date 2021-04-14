<?php

namespace EvolutionCMS\EvocmsDiscounts;


use Commerce\Carts\ProductsCart;
use Commerce\CartsManager;
use EvolutionCMS\EvocmsDiscounts\Apply\AppliesManager;
use EvolutionCMS\EvocmsDiscounts\Contracts\IApplyProductController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Rules\RulesLoader;

class DiscountToProductApplicator
{
    /**
     * @var mixed|null
     */
    private $productCart;
    /**
     * @var \EvolutionCMS\EvocmsDiscounts\Rules\Rule[]
     */
    private array $rules;
    /**
     * @var Apply\Apply[]
     */
    private array $applies;
    /**
     * @var AppliesManager
     */
    private AppliesManager $appliesManager;
    /**
     * @var DiscountSumCalculator
     */
    private DiscountSumCalculator $discountSumCalculator;

    public function __construct(AppliesManager $appliesManager,DiscountSumCalculator $discountSumCalculator)
    {



        /** @var CartsManager $cartsManager */
        $cartsManager = ci()->get('carts');

        /** @var ProductsCart $productCart */
        $this->productCart = $cartsManager->getCart('products');

        $ruleLoader = evo()->make(RulesLoader::class);
        $this->rules = $ruleLoader->loadRules();
        $this->appliesManager = $appliesManager;
        $this->discountSumCalculator = $discountSumCalculator;
    }

    public function apply(){
        $this->clearDiscount();
        $products = $this->productCart->getItems();

        foreach ($products as $row => $product) {
            $maxDiscount = $this->getMaxDiscountForProduct($product);


            if(!$maxDiscount){
                continue;
            }


            for ($i = 1; $i <= $maxDiscount['count']; $i++) {

                $newProduct = $product;

                $newProduct['meta']['discount'] = [
                    'price' => $product['price'],
                    'hash' => $product['hash']
                ];

                $price = $product['price'] - $maxDiscount['sum'];
                $newProduct['price'] = $price;
                $newHash = $this->productCart->makeHash($newProduct);
                $newProduct['hash'] = $newHash;


                $products[$row]['count']--;

                if($products[$row]['count']<1){
                    unset($products[$row]);
                }

                $productRow = $this->findProduct($newHash, $products);


                if ($productRow) {
                    $products[$productRow]['count']++;
                } else {
                    $newRow = ci()->commerce->generateRandomString(16);

                    $newProduct['row'] = $newRow;
                    $newProduct['count'] = 1;
                    $products[$newRow] = $newProduct;
                }
            }
        }

        $this->productCart->setItems($products);
    }

    private function getMaxDiscountForProduct($product){

        $q = Discount::where('type',Discount::TYPE_PRODUCT);

        foreach ($this->rules as $rule) {
            $rule->getController()->updateQuery($q,[
                'product'=>$product
            ]);
        }

        $q->groupBy('discounts.id');
        /** @var Discount[] $discounts */
        $discounts = $q->where('active',1)->get();

        $maxDiscount = null;


        foreach ($discounts as $discount) {

            $applyId = key($discount->apply);

            $applyValue = $discount->apply[$applyId];

            $apply = $this->appliesManager->getApplyById($applyId)->getController();


            /** @var IApplyProductController $apply */
            $count = $apply->getCountProductWithDiscount($product,$applyValue);


            if($count === false){
                continue;
            }
            $discountSum = $this->discountSumCalculator->calculate($product['price'],$discount);

            if($maxDiscount === null || $maxDiscount['sum'] < $discountSum){
                $maxDiscount = [
                    'count'=>$count,
                    'sum'=>$discountSum
                ];
            }
        }
        return $maxDiscount;
    }

    private function clearDiscount(){

        $products = $this->productCart->getItems();

        foreach ($products as $row => $product) {
            if(!isset($product['meta']['discount'])){
                continue;
            }

            $originalRow = $this->findProduct($product['meta']['discount']['hash'],$products);

            if($originalRow){
                $products[$originalRow]['count'] += $product['count'];
            }
            else{
                $oldProduct = $product;
                $oldProduct['price'] = $oldProduct['meta']['discount']['price'];
                unset($oldProduct['meta']['discount']);

                $newRow = ci()->commerce->generateRandomString(16);
                $oldProduct['row'] = $newRow;

                $products[$newRow] = $oldProduct;
            }
            unset($products[$row]);
        }

        $this->productCart->setItems($products);

    }
    private function findProduct($hash,$products){
        foreach ($products as $row => $item) {
            if ($item['hash'] == $hash) {
                return $row;
            }
        }
        return false;
    }

}