<?php


namespace EvolutionCMS\EvocmsDiscounts\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * EvolutionCMS\EvocmsDiscount\Models\DiscountProduct
 *
 * @property int $id
 * @property int $discount_id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsProduct whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsProduct whereProductId($value)
 * @mixin \Eloquent
 */
class DiscountsProduct extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'discount_id','product_id'
    ];

}