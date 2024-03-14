<?php

namespace ShopWP\Processing;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Utils;
use ShopWP\Utils\Server;

class Webhooks extends \ShopWP\Processing\Vendor_Background_Process
{
    protected $action = 'shopwp_background_processing_webhooks';

    protected $DB_Settings_Syncing;
    protected $Webhooks;
    protected $Shopify_API;

    public function __construct($DB_Settings_Syncing, $Webhooks, $Shopify_API)
    {
        $this->DB_Settings_Syncing = $DB_Settings_Syncing;
        $this->Webhooks = $Webhooks;
        $this->Shopify_API = $Shopify_API;

        parent::__construct($DB_Settings_Syncing);
    }

    public function process($items, $params = false)
    {
        if ($this->expired_from_server_issues($items, __METHOD__, __LINE__)) {
            return;
        }

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Connecting new webhooks ...');
        $this->dispatch_items($items);
    }

    protected function task($topic)
    {

        // Stops background process if syncing stops
        if (!$this->DB_Settings_Syncing->is_syncing()) {
            $this->complete();
            return false;
        }

        if ($this->time_exceeded() || $this->memory_exceeded()) {
            return $topic;
        }

        $this->DB_Settings_Syncing->set_current_syncing_step_text('Connecting: ' . $topic . ' ...');

        // Actual work
        $response = $this->Shopify_API->register_webhook(
            $this->Webhooks->get_webhook_body_from_topic($topic)
        );

        if (is_wp_error($response)) {
            if ($this->Webhooks->is_invalid_topic_error($response)) {
                $this->DB_Settings_Syncing->save_warning(
                    'Unable to register webhook of topic: ' . $topic
                );
                return false;
            }

            $this->DB_Settings_Syncing->save_notice_and_expire_sync($response);
            $this->complete();
            return false;
        }

        $this->DB_Settings_Syncing->increment_current_amount('webhooks');

        return false;
    }

}
