<?php

namespace App\Console\Commands;

use App\Models\Discount;
use Carbon\Carbon;
use Illuminate\Console\Command;

class deleteDiscountCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discount:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete finished ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Discount::where('amount',0)->delete();
        Discount::whereDate('to','<',Carbon::now())->delete();
    }
}
