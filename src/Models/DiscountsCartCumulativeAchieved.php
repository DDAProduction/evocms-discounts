<?php


namespace EvolutionCMS\EvocmsDiscounts\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * EvolutionCMS\EvocmsDiscount\Models\DiscountCartCumulativeAchieved
 *
 * @property int $id
 * @property int $discount_id
 * @property bool $achieved
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCartCumulativeAchieved newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCartCumulativeAchieved newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCartCumulativeAchieved query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCartCumulativeAchieved whereAchieved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCartCumulativeAchieved whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCartCumulativeAchieved whereId($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCartCumulativeAchieved whereUserId($value)
 */
class DiscountsCartCumulativeAchieved extends Model
{
    protected $fillable = [
        'discount_id',
        'user_id',
        'achieved'
    ];


    public $timestamps = false;
    protected $table = 'discounts_cart_cumulative_achieved';

}