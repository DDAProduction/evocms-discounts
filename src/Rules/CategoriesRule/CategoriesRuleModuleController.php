<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\CategoriesRule;


use EvolutionCMS\EvocmsDiscounts\Config;
use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Models\DiscountsCategory;
use EvolutionCMS\EvocmsDiscounts\Models\DiscountsProduct;
use EvolutionCMS\EvocmsDiscounts\Router\Router;
use EvolutionCMS\Models\SiteContent;

class CategoriesRuleModuleController implements IRuleModuleController
{
    /**
     * @var Router
     */
    private Router $router;
    /**
     * @var Config
     */
    private Config $config;

    public function __construct(Router $router, Config $config)
    {
        $this->router = $router;
        $this->config = $config;
    }

    public function init()
    {
        $this->router->addRoute('rule-categories-load', [self::class, 'load']);
    }



    public function getRule(Discount $discount)
    {

        if($discount->categories->count()){
            return  $discount->categories;
        }
        return null;
    }


    public function saveRule($updateRow,$requestRules)
    {

        DiscountsCategory::whereDiscountId($updateRow['id'])->delete();

        if (isset($requestRules['categories'])) {
            foreach ($requestRules['categories'] as $category) {
                DiscountsCategory::create([
                    'discount_id' => $updateRow['id'],
                    'category_id' => $category['id'],
                ]);
            }
        }

        return $updateRow;
    }


    public function load()
    {
        $depth = $this->config->get('rules.categories.depth',10);
        $parents = $this->config->get('rules.categories.parents',0);
        $templates = $this->config->get('rules.categories.templates',[]);

        if (empty($parents)) {
            $q = SiteContent::GetRootTree($depth);
        } else {
            $q = SiteContent::descendantsOf($parents)->where('depth', '<', $depth);
        }


        if($templates){
            $q->whereIn('site_content.template',[4]);
            $q->whereIn('t2.template',[4]);
        }

        $docs = $q->get()
            ->toTree()
            ->toArray()
        ;

        return $this->prepareTree($docs);
    }

    private function prepareTree($docs)
    {
        $tree = [];
        foreach ($docs as $key => $doc) {

            $new = [
                "id" => $doc['id'],
                "parent" => $doc['parent'],
                "value" => !empty($doc['menutitle']) ?$doc['menutitle']: $doc['pagetitle']
            ];

            if(array_key_exists('children',$doc)){
                $new['data'] = $this->prepareTree($doc['children']);

            }

            $tree[] = $new;
        }

        return $tree;
    }

}