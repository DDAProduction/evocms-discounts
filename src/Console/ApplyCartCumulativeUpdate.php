<?php


namespace EvolutionCMS\EvocmsDiscounts\Console;


use Carbon\Carbon;
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


            if (!empty($cartCumulative['type'])) {

                switch ($cartCumulative['type']){
                    case 'day':
                        $q->where('created_at', '>=', Carbon::today()->subDays($cartCumulative['period_count']));
                        break;
                    case 'week':
                        $q->where('created_at', '>=', Carbon::today()->subWeeks($cartCumulative['period_count']));
                        break;
                    case 'month':
                        $q->where('created_at', '>=', Carbon::today()->subMonths($cartCumulative['period_count']));
                        break;
                    case 'year':
                        $q->where('created_at', '>=', Carbon::today()->subYears($cartCumulative['period_count']));
                        break;
                }


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