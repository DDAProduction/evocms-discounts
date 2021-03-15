<?php

namespace EvolutionCMS\EvocmsDiscounts\Models;

use EvolutionCMS\Models\MembergroupName;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\UserAttribute;
use Illuminate\Database\Eloquent\Model;




/**
 * EvolutionCMS\EvocmsDiscount\Models\Discount
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property \Illuminate\Support\Carbon $rule_period_from
 * @property \Illuminate\Support\Carbon $rule_period_to
 * @property string $rule_user_groups
 * @property array $apply
 * @property float $discount_value
 * @property string $discount_type
 * @property bool $active
 * @property bool $exclude_sales
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \EvolutionCMS\Extensions\Collection|SiteContent[] $categories
 * @property-read int|null $categories_count
 * @property-read \EvolutionCMS\Extensions\Collection|SiteContent[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|UserAttribute[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount query()
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereApply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDiscountType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereDiscountValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereExcludeSales($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereRulePeriodFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereRulePeriodTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereRuleUserGroups($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Discount whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read MembergroupName|null $ruleUserGroupsModel
 * @property-read \EvolutionCMS\EvocmsDiscounts\Models\DiscountsCartCumulativeAchieved|null $applyCumulativeAchieved
 */
class Discount extends Model
{
    const TYPE_PRODUCT = 'product';
    const TYPE_CART = 'cart';

    const DISCOUNT_TYPE_AMOUNT = 'amount';
    const DISCOUNT_TYPE_PERCENT = 'percent';

    protected $fillable = [
        'id',
        'title',
        'type',
        'rule_period_from',
        'rule_period_to',
        'rule_user_groups',
        'apply',
        'discount_value',
        'discount_type',
        'active',
        'exclude_sales',
        'created_at',
        'updated_at',

    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'rule_period_from',
        'rule_period_to'
    ];

    protected  $casts = [
        'apply'=>'array',
    ];


    public function products(){
        return $this->hasManyThrough(SiteContent::class,DiscountsProduct::class,'discount_id','id','id','product_id');
    }

    public function categories(){
        return $this->hasManyThrough(SiteContent::class,DiscountsCategory::class,'discount_id','id','id','category_id');
    }

    public function users(){
        return $this->hasManyThrough(UserAttribute::class,DiscountsUser::class,'discount_id','internalKey','id','user_id');
    }

    public function ruleUserGroupsModel(){
        return $this->hasOne(MembergroupName::class,'id','rule_user_groups');
    }

    public function applyCumulativeAchieved(){
        $userId = evo()->getLoginUserID();

        return $this->hasOne(DiscountsCartCumulativeAchieved::class,'discount_id','id')->where('user_id',$userId);
    }

}