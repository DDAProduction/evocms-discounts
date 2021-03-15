<?php


namespace EvolutionCMS\EvocmsDiscounts\Console;


use EvolutionCMS\EvocmsDiscounts\Config;
use EvolutionCMS\EvocmsDiscounts\Models\Discount;
use EvolutionCMS\EvocmsDiscounts\Models\DiscountsCartCumulativeAchieved;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApplyCartCumulativeUpdate extends Command
{
    protected $signature = 'evocms-discounts:cumulative-update';

    protected $description = '';


    public function handle()
    {
        $discounts = Discount::where('apply', 'like', '%"cartCumulative"%')
            ->get();

        $config = evo()->make(Config::class);

        DB::beginTransaction();

        DiscountsCartCumulativeAchieved::query()->delete();


        /** @var Discount[] $discounts */
        foreach ($discounts as $discount) {

            $apply = $discount->apply;
            if (empty($apply['cartCumulative'])) {
                continue;
            }

            $cartCumulative = $apply['cartCumulative'];

            $q = DB::table('commerce_orders');
            $q->select(['customer_id']);
            $q->selectRaw('SUM(amount) as amount_total');


            $statuses = $config->get('apply.cart_cumulative_apply.statuses', []);
            if (!empty($statuses)) {
                $q->whereIn('status_id', $statuses);
            }

            if (!empty($cartCumulative['period_from'])) {
                $q->where('created_at', '>=', $cartCumulative['period_from']);
            }
            if (!empty($cartCumulative['period_to'])) {
                $q->where('created_at', '<=', $cartCumulative['period_to']);
            }

            if (!empty($cartCumulative['sum_from'])) {
                $q->having('amount_total', '>=', $cartCumulative['sum_from']);
            }

            if (!empty($cartCumulative['sum_to'])) {
                $q->having('amount_total', '<=', $cartCumulative['sum_to']);
            }


            $q->groupBy('customer_id');

            $res = $q->get();

            foreach ($res as $re) {
                DiscountsCartCumulativeAchieved::create([
                    'discount_id' => $discount->id,
                    'user_id' => $re->customer_id,
                    'achieved' => 1
                ]);
            }
        }
        DB::commit();

        $this->info('ok');
    }

}