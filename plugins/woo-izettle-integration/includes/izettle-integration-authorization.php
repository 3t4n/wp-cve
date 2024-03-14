<?php

/**
 * This class handles authorization issues
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2019 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Integration_Authorization', false)) {

    class WC_iZettle_Integration_Authorization
    {
        static $is_connection_ok = true;

        public function __construct()
        {
            add_filter('izettle_is_client_allowed_to_sync', array($this, 'is_client_allowed_to_sync'), 10, 2);
            add_filter('izettle_is_it_time_to_check_sync', array($this, 'is_it_time_to_check_sync'), 10, 5);
            add_filter('izettle_connection_status', array($this, 'connection_status'));
            add_action('izettle_connection_fail', array($this, 'connection_fail'));
            add_action('izettle_connection_success', array($this, 'connection_success'));
            add_action('izettle_force_connection', array($this, 'force_connection'));
            add_action('izettle_service_heartbeat', array($this, 'izettle_service_heartbeat'));
            add_action('init', array($this, 'schedule_heartbeat_sync'));

        }

        public function is_it_time_to_check_sync($sync, $name, $model, $sync_all, $microtime)
        {

            $is_client_allowed_to_sync = $this->is_client_allowed_to_sync($sync, $model, $sync_all);

            if (1440 == $model) {
                $delay = strtotime('tomorrow') - current_time('timestamp') + (HOUR_IN_SECONDS * get_option('izettle_product_sync_model_force_daily_time', 6));
            } elseif (is_numeric($model) && $is_client_allowed_to_sync) {
                $delay = $model * MINUTE_IN_SECONDS;
            } else {
                $delay = DAY_IN_SECONDS;
            }

            set_site_transient($name, $delay + $microtime);

            return $is_client_allowed_to_sync;

        }

        public function is_client_allowed_to_sync($sync, $sync_all = false)
        {
            $connection_status = $this->connection_status('ok');
            if (($sync_all && in_array($connection_status, array( 'trial', 'ok'))) || in_array($connection_status, array('trial', 'ok'))) {
                $sync = true;
            } else {
                WC_IZ()->logger->add(sprintf('is_client_allowed_to_sync: Connection status %s is not allowed to sync %s', $connection_status, $sync_all ? 'all' : 'incremental'));
            }

            return $sync;
        }

        public function connection_fail($reason)
        {
            $failed_syncs = ($failed_syncs = get_site_transient('izettle_number_of_failed_connections')) ? $failed_syncs++ : 1;

            set_site_transient('izettle_number_of_failed_connections', $failed_syncs);

            if ($failed_syncs > 10) {
                $message = __(sprintf('Can not connect to the Zettle service. Update the connection <a href="%s">manually</a> to start the automatic sync again', 'woo-izettle-integration'), get_admin_url(null, 'admin.php?page=wc-settings&tab=izettle&section=advanced'));
                $id = IZ_Notice::add($message, 'warning', 'failed_connection');
                set_site_transient('izettle_failed_connection', $reason, DAY_IN_SECONDS);
            } else {
                set_site_transient('izettle_failed_connection', $reason, MINUTE_IN_SECONDS * 6);
            }
        }

        public function connection_success()
        {
            delete_site_transient('izettle_failed_connection');
            delete_site_transient('izettle_number_of_failed_connections');
        }

        public function is_connection_ok()
        {

            return get_site_transient('izettle_failed_connection') === false;

        }

        public function connection_status($status = '')
        {

            if (!izettle_api()->get_organization_uuid()) {
                return 'unauthorized';
            }

            if (!$this->is_connection_ok()) {
                return 'error';
            }

            $trial = izettle_api()->get_is_trial();

            $now = intval(time());
            $valid_to = intval(izettle_api()->get_valid_to());
            $expires_in = intval(izettle_api()->get_expires_in());
            $next_sync = intval(izettle_api()->get_last_synced() + WEEK_IN_SECONDS);

            // if account is not valid
            if ($valid_to < $now) {
                return 'expired';
            }

            if ($trial) {
                return 'trial';
            }

            return $status;

        }

        public function force_connection()
        {
            delete_site_transient('izettle_locations');
            $this->connection_success();
            izettle_api()->set_access_token('');
            izettle_api()->set_expires_in(0); // Forcing the client to connect again
            izettle_api()->connect_to_service();
        }

        public function schedule_heartbeat_sync()
        {

            if ($this->connection_status('ok') == 'ok') {
                if (false === as_next_scheduled_action('izettle_service_heartbeat')) {
                    as_schedule_recurring_action(time(), HOUR_IN_SECONDS, 'izettle_service_heartbeat');
                }
                $actions = as_get_scheduled_actions(
                    array(
                        'hook' => 'izettle_service_heartbeat',
                        'status' => ActionScheduler_Store::STATUS_PENDING,
                        'claimed' => false,
                        'per_page' => -1,
                    ),
                    'ids'
                );
                if (count($actions) > 1) {
                    try{
                        as_unschedule_action('izettle_service_heartbeat');
                    }catch(\Throwable $throwable){
                        WC_IZ()->logger->add(sprintf('schedule_heartbeat_sync - No process to unschedule'));
                    }
                }
            } else {
                if (false !== as_next_scheduled_action('izettle_service_heartbeat')) {
                    try{
                        as_unschedule_all_actions('izettle_service_heartbeat');
                    }catch(\Throwable $throwable){
                        WC_IZ()->logger->add(sprintf('schedule_heartbeat_sync - No process to unschedule'));
                    }
                }
            }

        }

        public function izettle_service_heartbeat()
        {
            WC_IZ()->logger->add(sprintf('izettle_service_heartbeat: Connecting to service'));
            izettle_api()->connect_to_service();
        }
    }
    new WC_iZettle_Integration_Authorization();
}
