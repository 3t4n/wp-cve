<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\Sync\Jobs;

use Exception;
use GoDaddy\WooCommerce\Poynt\API\GatewayAPI;
use GoDaddy\WooCommerce\Poynt\Helpers\PoyntHelper;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

/**
 * Schedule terminal activation status.
 *
 * Schedules terminal activation by setting a cronjob which checks terminal activation status periodically.
 */
class ActiveSmartTerminalDetector
{
    /** @var string terminal activation status action name */
    const ACTION_TERMINAL_ACTIVATION_STATUS = 'wc_poynt_terminal_activation_status';

    /** @var int interval every x hours */
    const SCHEDULE_INTERVAL_IN_HOURS = 12;

    /**
     * Schedule terminal activation constructor.
     */
    public function __construct()
    {
        $this->addHooks();
    }

    /**
     * Adds the hooks.
     *
     * @since 1.3.0
     *
     * @return void
     */
    protected function addHooks()
    {
        add_action('admin_init', [$this, 'scheduleTerminalActivationStatus']);
        add_action(self::ACTION_TERMINAL_ACTIVATION_STATUS, [$this, 'updateTerminalActivationStatus']);
    }

    /**
     * Schedules an Action Scheduler event to update the terminal activation status.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @return void
     * @throws Exception
     */
    public function scheduleTerminalActivationStatus()
    {
        // don't attempt to poll until the payment method is configured properly
        if (! PoyntHelper::isGDPConnected()) {
            return;
        }

        // unsubscribe to all the future events and return if the terminal is already activated
        if (PoyntHelper::hasPoyntSmartTerminalActivated()) {
            as_unschedule_action(self::ACTION_TERMINAL_ACTIVATION_STATUS);

            return;
        }

        if (! as_next_scheduled_action(self::ACTION_TERMINAL_ACTIVATION_STATUS)) {
            $recurringInterval = HOUR_IN_SECONDS * self::SCHEDULE_INTERVAL_IN_HOURS;
            as_schedule_recurring_action(MINUTE_IN_SECONDS, $recurringInterval, self::ACTION_TERMINAL_ACTIVATION_STATUS);
        }
    }

    /**
     * Updates the terminal activation status.
     *
     * @since 1.3.0
     *
     * @internal
     *
     * @return void
     * @throws Exception
     */
    public function updateTerminalActivationStatus()
    {
        $businessId = PoyntHelper::getBusinessId();
        $appId = PoyntHelper::getAppId();
        $privateKey = PoyntHelper::getPrivateKey();
        $environment = PoyntHelper::getEnvironment();

        // ensure we have the minimum requirements to be connected to the API
        if (! $businessId || ! $appId || ! $privateKey) {
            return;
        }

        $api = new GatewayAPI($appId, $businessId, $privateKey, $environment);

        try {
            $businessStoresResponse = $api->getBusinessStores();
            $hasActivatedTerminalDevices = (bool) $businessStoresResponse->hasActiveTerminalDevices();

            // update terminal activation status
            update_option('wc_poynt_payinperson_terminal_activated', $hasActivatedTerminalDevices);

            // if terminal activation status is activated, unsubscribe from the recurring job
            if ($hasActivatedTerminalDevices) {
                // Make sure webhooks already not registered
                if ('yes' !== get_option('wc_poynt_webhooksRegistered')) {
                    $api->registerWebhooks();
                    // Update option webhooks registration status
                    update_option('wc_poynt_webhooksRegistered', 'yes');
                }

                as_unschedule_action(self::ACTION_TERMINAL_ACTIVATION_STATUS);
            }
        } catch (Framework\SV_WC_API_Exception $e) {
            poynt_for_woocommerce()->log($e->getMessage());
        }
    }
}
