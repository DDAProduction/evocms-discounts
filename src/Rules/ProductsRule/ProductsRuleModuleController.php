<?php
namespace EvolutionCMS\EvocmsDiscounts\Rules\ProductsRule;


use EvolutionCMS\EvocmsDiscounts\Config;
use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Models\DiscountsProduct;
use EvolutionCMS\EvocmsDiscounts\Router\Router;
use EvolutionCMS\Models\SiteContent;
use Illuminate\Http\Request;

class ProductsRuleModuleController implements IRuleModuleController
{
    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var Config
     */
    private Config $config;

    public function __construct(Router $router,Config $config)
    {

        $this->router = $router;
        $this->config = $config;
    }

    public function init()
    {
        $this->router->addRoute('rule-products-search-products',[self::class,'searchProducts']);
    }



    public function searchProducts(Request $request){
        $templates = $this->config->get('rules.product.templates',[]);

        $q = SiteContent::select(['id','pagetitle as title']);

        if($templates){
            $q->whereIn('template',$templates);  
        }

        if($request->has('checked')){
            $q->whereNotIn('id',explode(',',$request->get('checked')));
        }
        if($request->has('search')){
            $q->where('pagetitle','like','%'.$request->get('search').'%');
        }


        return $q->get()->toArray();
    }

    public function getRule(Discount $discount){
        if($discount->products->count()){
            return  $discount->products;
        }
        return null;
    }

    public function saveRule($updateRow,$requestRules)
    {
        DiscountsProduct::whereDiscountId($updateRow['id'])->delete();

        if(isset($requestRules['products'])){
            foreach ($requestRules['products'] as $product) {
                DiscountsProduct::create([
                    'discount_id'=>$updateRow['id'],
                    'product_id'=>$product['id'],
                ]);
            }
        }


        return $updateRow;
    }
}