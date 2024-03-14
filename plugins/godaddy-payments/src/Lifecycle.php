<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt;

use Automattic\WooCommerce\Internal\DataStores\Orders\OrdersTableDataStore;
use GoDaddy\WooCommerce\Poynt\Gateways\CreditCardGateway;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1 as Framework;

defined('ABSPATH') or exit;

/**
 * Plugin lifecycle handler.
 *
 * @since 1.0.0
 */
class Lifecycle extends Framework\Plugin\Lifecycle
{
    /** @var string file path identifier of the legacy plugin */
    const LEGACY_PLUGIN_FILE = 'woocommerce-gateway-poynt-collect/woocommerce-gateway-poynt-collect.php';

    /** @var string option name used to flag whether an install has migrated settings from a legacy plugin version */
    const MIGRATED_FROM_LEGACY_PLUGIN_FLAG = 'wc_poynt_migrated_from_legacy_plugin';

    /**
     * Lifecycle constructor.
     *
     * @since 1.3.0
     *
     * @param Plugin $plugin
     */
    public function __construct($plugin)
    {
        parent::__construct($plugin);

        $this->upgrade_versions = [
            '2.0.0',
        ];
    }

    /**
     * Adds lifecycle-related action & filter hooks.
     *
     * @since 1.0.0
     */
    protected function add_hooks()
    {
        parent::add_hooks();

        add_action('admin_init', [$this, 'deactivateLegacyPlugin']);
    }

    /**
     * Deactivates the legacy plugin, if present.
     *
     * @internal
     *
     * @since 1.0.0
     */
    public function deactivateLegacyPlugin()
    {
        $file = self::LEGACY_PLUGIN_FILE;
        $parts = explode('/', $file);
        $name = end($parts);

        if ($this->get_plugin()->is_plugin_active($name)) {
            deactivate_plugins($file);
        }
    }

    /**
     * Determines whether the plugin was migrated from a legacy version.
     *
     * @since 1.0.0
     *
     * @return bool
     */
    public function hasMigratedFromLegacyPlugin() : bool
    {
        return 'yes' === get_option(self::MIGRATED_FROM_LEGACY_PLUGIN_FLAG);
    }

    /**
     * Performs plugin install tasks.
     *
     * @since 1.0.0
     */
    protected function install()
    {
        if ($this->migrateLegacySettings()) {
            $this->migrateLegacyTokens();
            $this->migrateLegacyOrders();
        }
    }

    /**
     * Migrates the legacy plugin settings to the new gateway.
     *
     * @since 1.0.0
     *
     * @return bool whether settings were migrated
     */
    private function migrateLegacySettings() : bool
    {
        // attempts to get the original plugin settings
        $legacySettings = get_option('woocommerce_poynt-collect_settings', []);

        if (empty($legacySettings)) {
            return false;
        }

        // converts old settings to the new plugin settings
        $newSettings = [
            'enabled'          => wc_bool_to_string(! empty($legacySettings['enabled']) && 'yes' === $legacySettings['enabled']),
            'title'            => (! empty($legacySettings['title'])) ? $legacySettings['title'] : esc_html__('Credit Card', 'godaddy-payments'),
            'description'      => (! empty($legacySettings['description'])) ? $legacySettings['description'] : esc_html__('Allow customers to securely pay using their credit cards with Poynt.', 'godaddy-payments'),
            'transaction_type' => (! empty($legacySettings['auth_only']) && 'yes' === $legacySettings['auth_only']) ? CreditCardGateway::TRANSACTION_TYPE_AUTHORIZATION : CreditCardGateway::TRANSACTION_TYPE_CHARGE,
            'environment'      => (! empty($legacySettings['poynt_env']) && 'st' === $legacySettings['poynt_env']) ? Plugin::ENVIRONMENT_STAGING : Plugin::ENVIRONMENT_PRODUCTION,
            'tokenization'     => 'yes',
        ];

        // sets the app id and the private key only for stating and production environments
        if (! empty($legacySettings['poynt_env']) && in_array($legacySettings['poynt_env'], ['st', 'prod'])) {
            // the app id and the private key have different names for their respective environments
            $appId = 'st' === $legacySettings['poynt_env'] ? 'stagingAppId' : 'appId';
            $privateKey = 'st' === $legacySettings['poynt_env'] ? 'stagingPrivateKey' : 'privateKey';

            $newSettings[$appId] = $legacySettings['api_app_id'] ?? '';
            $newSettings[$privateKey] = $legacySettings['api_app_priv_key'] ?? '';
        }

        // stores the new plugin settings
        update_option('woocommerce_poynt_credit_card_settings', $newSettings);

        // mark settings migrated
        update_option(self::MIGRATED_FROM_LEGACY_PLUGIN_FLAG, 'yes');

        return true;
    }

    /**
     * Migrates the legacy plugin tokens to the new gateway.
     *
     * @since 1.0.0
     *
     * @return bool whether data was migrated
     */
    private function migrateLegacyTokens() : bool
    {
        global $wpdb;

        $rows = $wpdb->update("{$wpdb->prefix}woocommerce_payment_tokens", ['gateway_id' => Plugin::CREDIT_CARD_GATEWAY_ID], ['gateway_id' => 'poynt-collect']);

        return ! empty($rows);
    }

    /**
     * Migrates the legacy plugin orders to the new gateway.
     *
     * @since 1.0.0
     *
     * @return bool whether orders were migrated
     */
    private function migrateLegacyOrders() : bool
    {
        global $wpdb;

        if (Framework\SV_WC_Plugin_Compatibility::is_hpos_enabled()) {
            $meta_table = OrdersTableDataStore::get_meta_table_name();
            $order_id_col = 'order_id';
        } else {
            $meta_table = $wpdb->postmeta;
            $order_id_col = 'post_id';
        }

        $rows = 0;

        // update the payment method
        if ($updated = $wpdb->update($meta_table, ['meta_value' => Plugin::CREDIT_CARD_GATEWAY_ID], ['meta_key' => '_payment_method', 'meta_value' => 'poynt-collect'])) {
            $rows += $updated;
        }

        $updateMetaKeys = [
            '_transaction_id'        => '_wc_poynt_credit_card_trans_id',
            'capture_transaction_id' => '_wc_poynt_credit_card_capture_trans_id',
            'captured'               => '_wc_poynt_credit_card_charge_captured',
        ];

        // copy legacy meta data
        foreach ($updateMetaKeys as $oldKey => $newKey) {
            // collect the original meta value and order ID using the old meta key
            $original = $wpdb->get_results("
                SELECT {$order_id_col}, meta_value
                FROM {$meta_table}
                WHERE meta_key = '{$oldKey}'
            ", ARRAY_N);

            if (! $original) {
                continue;
            }

            $copy = [];

            // convert the results to string and append the new meta key to the set of retrieved values
            foreach ($original as $set) {
                $values = '';

                foreach ($set as $value) {
                    $values .= "'{$value}',";
                }

                $values .= "'{$newKey}'";

                $copy[] = "({$values})";
            }

            // turn the sets into comma separated groups of values
            $copy = implode(',', $copy);

            // copy (insert) rows
            $inserted = $wpdb->query("
                INSERT INTO {$meta_table}
                ( {$order_id_col}, meta_value, meta_key )
                VALUES
                {$copy}
            ");

            if ($inserted) {
                $rows += $inserted;
            }
        }

        // update captured status from boolean to string values
        if ($updated = $wpdb->update($meta_table, ['meta_value' => 'yes'], ['meta_key' => '_wc_poynt_credit_card_charge_captured', 'meta_value' => 'true'])) {
            $rows += $updated;
        }
        if ($updated = $wpdb->update($meta_table, ['meta_value' => 'no'], ['meta_key' => '_wc_poynt_credit_card_charge_captured', 'meta_value' => 'false'])) {
            $rows += $updated;
        }

        return $rows > 0;
    }

    /**
     * Updates to version 2.0.0.
     *
     * From version 1.2.2 going to version 2.0, which supports BOPIT feature, the 'wc_poynt_appId' and some other options are not available in the database
     * which are required for the BOPIT feature to properly work, so let's get them from the API if the payment gateway is properly configured by the user.
     *
     * @since 1.3.0
     */
    public function upgrade_to_2_0_0()
    {
        if (! $plugin = $this->get_plugin()) {
            $plugin = poynt_for_woocommerce();
        }
        $gateway = $plugin->get_gateway(Plugin::CREDIT_CARD_GATEWAY_ID);

        // bail out if the gateway is not configured properly by the user already, and make sure that we have everything we need for the API requests.
        if (
            ! $gateway
            || ! $gateway->is_configured()
            || ! $gateway->getAppId()
            || ! $gateway->getBusinessId()
            || ! $gateway->getPrivateKey()
            || ! $gateway->get_api()
        ) {
            return;
        }

        try {
            $businessResponse = $gateway->get_api()->getBusinessDetails();
            update_option('wc_poynt_appId', $businessResponse->getAppId());
            update_option('wc_poynt_serviceId', $businessResponse->getServiceId());
            update_option('wc_poynt_businessId', $gateway->getBusinessId());

            $businessStoresResponse = $gateway->get_api()->getBusinessStores();
            update_option('wc_poynt_storeId', $businessStoresResponse->getStoreId());
            update_option('wc_poynt_payinperson_terminal_activated', $businessStoresResponse->hasActiveTerminalDevices());
        } catch (Framework\SV_WC_API_Exception $e) {
            // TODO: Log error in case of update failure (@sahmed2-godaddy: 2021-12-17)
        }
    }
}
