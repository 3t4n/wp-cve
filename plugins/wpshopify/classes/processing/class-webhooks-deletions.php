<?php

namespace ShopWP\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Utils;
use ShopWP\Utils\Server;

class Webhooks_Deletions extends
    \ShopWP\Processing\Vendor_Background_Process
{
    protected $action = 'shopwp_background_processing_webhooks_deletions';

    protected $DB_Settings_Syncing;
    protected $Shopify_API;

    public function __construct($DB_Settings_Syncing, $Shopify_API)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Shopify_API = $Shopify_API;

        parent::__construct($DB_Settings_Syncing);
    }

    public function process($items, $params = false)
    {
        if ($this->expired_from_server_issues($items, __METHOD__, __LINE__)) {
            return;
        }

        $this->DB_Settings_Syncing->set_finished_webhooks_deletions(0);
        $this->DB_Settings_Syncing->set_current_syncing_step_text('Removing any existing webhooks ...');

        $this->dispatch_items($items);
    }

    protected function task($webhook)
    {
        if (empty($webhook)) {
            return false;
        }

        if ($this->time_exceeded() || $this->memory_exceeded()) {
            return $webhook;
        }

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Disconnecting: ' . $webhook->topic . ' ...');

        // Actual work
        $response = $this->Shopify_API->delete_webhook($webhook->id);

        if (is_wp_error($response)) {
            $this->DB_Settings_Syncing->save_notice_and_expire_sync($response);
            $this->complete();
            return false;
        }

        $this->DB_Settings_Syncing->increment_current_amount('webhooks');

        return false;
    }

    protected function complete()
    {
        $this->DB_Settings_Syncing->set_finished_webhooks_deletions(1);

        parent::complete();
    }
}
