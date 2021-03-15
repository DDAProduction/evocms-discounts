<?php


namespace EvolutionCMS\EvocmsDiscounts\Rules\UsersRule;


use EvolutionCMS\EvocmsDiscounts\Config;
use EvolutionCMS\EvocmsDiscounts\Contracts\IRuleModuleController;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Models\DiscountsCategory;
use EvolutionCMS\EvocmsDiscounts\Models\DiscountsUser;
use EvolutionCMS\EvocmsDiscounts\Router\Router;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\UserAttribute;
use Illuminate\Http\Request;

class UsersRuleModuleController implements IRuleModuleController
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
        $this->router->addRoute('rule-users-search',[self::class,'search']);
    }

    public function search(Request $request){

        $q = UserAttribute::select(['internalKey as id','fullname as title']);

        if($request->has('checked')){
            $q->whereNotIn('id',explode(',',$request->get('checked')));
        }
        if($request->has('search')){
            $q->where('fullname','like','%'.$request->get('search').'%');
        }

        return $q->get()->toArray();
    }

    public function getRule(Discount $discount){
        if($discount->users->toArray()){
            return $discount->users;
        }
        return null;
    }


    public function saveRule($updateRow,$requestRules)
    {
        DiscountsUser::whereDiscountId($updateRow['id'])->delete();

        if (isset($requestRules['users'])) {
            foreach ($requestRules['users'] as $user) {
                DiscountsUser::create([
                    'discount_id' => $updateRow['id'],
                    'user_id' => $user['id'],
                ]);
            }
        }

        return $updateRow;
    }
}