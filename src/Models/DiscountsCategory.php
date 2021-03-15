<?php


namespace EvolutionCMS\EvocmsDiscounts\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * EvolutionCMS\EvocmsDiscount\Models\DiscountCategory
 *
 * @property int $id
 * @property int $discount_id
 * @property int $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCategory whereDiscountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DiscountsCategory whereId($value)
 * @mixin \Eloquent
 */
class DiscountsCategory extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'discount_id','category_id'
    ];


}