<?php


namespace EvolutionCMS\EvocmsDiscounts\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * EvolutionCMS\EvocmsDiscount\Models\DiscountUser
 *
 * @property int $id
 * @property int $discount_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsUser whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsUser whereUserId($value)
 * @mixin \Eloquent
 */
class DiscountsUser extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'discount_id','user_id'
    ];

}