<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\SalesOrder;
use Illuminate\Console\Command;
use App\States\SalesOrder\Cancel;
use App\States\SalesOrder\Pending;

class CheckDueSalesOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sales-order:check-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Sales Order Due Date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now()->startOfMinute();

        SalesOrder::where('due_date_at', '<=', $now)
            ->where('status', Pending::class)
            ->get()->each(function (SalesOrder $salesOrder) {
                $this->info("Due Date Found: #{$salesOrder->trx_id}");

                $salesOrder->status->transitionTo(Cancel::class);
            });

            return Command::SUCCESS;
    }
}
