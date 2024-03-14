<?php namespace BDroppy\CronJob\Jobs;


class SyncOrderJob extends BaseJob
{

    protected $actionName = 'bdroppy_sync_order_event';

    public function handle()
    {
        update_option( 'bdroppy-cron-sync-order-last-run', (int) time());
        $this->syncMethod();
        return true;
    }

    public function syncMethod()
    {
        $this->wc->syncOrder->update_order_statuses();
        $this->wc->syncOrder->sync_with_supplier();

        return true;
    }

}