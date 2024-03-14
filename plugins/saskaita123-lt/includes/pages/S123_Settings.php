<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 *
 * Class Description: Admin menu plugin settings
 */

declare(strict_types=1);

namespace S123\Includes\Pages;

use S123\Includes\Base\S123_BaseController;
use S123\Includes\Requests\S123_ApiRequest;
use WC_Tax;

if (!defined('ABSPATH')) exit;

class S123_Settings extends S123_BaseController
{
    /**
     * API request object
     *
     * @var S123_ApiRequest
     */
    private $apiRequest;

    public function __construct()
    {
        parent::__construct();
        $this->apiRequest = new S123_ApiRequest();
    }

    public function s123_register()
    {
        add_action('admin_menu', array($this, 's123_add_plugin_admin_menu'));
        add_filter('plugin_action_links_' . $this->plugin_basename, array($this, 's123_settings_link'));
        add_action('admin_init', array($this, 's123_migrate_database'));
    }

    /*
     * Add plugin link to admin sidebar
     */
    public function s123_add_plugin_admin_menu()
    {
        add_submenu_page(
            'woocommerce',
            'Invoice123',
            'Invoice123',
            'manage_options',
            S123_BaseController::PLUGIN_NAME,
            array($this, 's123_display_plugin_setup_page')
        );
    }

    /*
     * Render plugin html
     */
    public function s123_display_plugin_setup_page()
    {
        require_once $this->plugin_path . 'admin/partials/s123-invoices-admin-dashboard.php';
    }

    /*
    * Plugin action links
    */
    public function s123_settings_link($links)
    {
        $action_links = array(
            'settings' => '<a href="admin.php?page=' . S123_BaseController::PLUGIN_NAME . '">' . __("Settings", "s123-invoices") . '</a>',
        );

        return array_merge($action_links, $links);
    }

    /*
     * Get all available woocommerce vats
     */
    public function s123_getAvailableTaxRates()
    {
        $all_tax_rates = [];
        $tax_classes = WC_Tax::get_tax_classes(); // Retrieve all tax classes.
        if (!in_array('', $tax_classes)) { // Make sure "Standard rate" (empty class name) is present.
            array_unshift($tax_classes, '');
        }
        foreach ($tax_classes as $tax_class) { // For each tax class, get all rates.
            $taxes = WC_Tax::get_rates_for_tax_class($tax_class);
            $all_tax_rates = array_merge($all_tax_rates, $taxes);
        }

        return $all_tax_rates;
    }

    public function s123_migrate_database()
    {
        $currentPluginVersion = get_file_data($this->plugin_path . 's123-invoices.php', ['Version' => 'Version'], 'plugin')['Version'];
        $optionsVersion = $this->s123_get_option('plugin_version');
        // check if database needs any migration
        $this->migrateDatabase($optionsVersion);

        if ($currentPluginVersion !== $optionsVersion) {
            $options = array_merge($this->s123_get_options(), ['plugin_version' => $currentPluginVersion]);
            $this->s123_update_options($options);
        }
    }

    private function migrateDatabase($version)
    {
        if ($version === '1.3.7') {
            global $wpdb;
            $tableName = $wpdb->prefix . "woocommerce_tax_rates";
            // check if column already exists
            $row = $wpdb->get_results( "SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `table_name` = '{$tableName}' AND `COLUMN_NAME` = 's123_tax_id'" );
            if ( empty( $row ) ) {
                $wpdb->query("ALTER TABLE {$tableName} ADD COLUMN s123_tax_id VARCHAR(50) NULL DEFAULT NULL");
            }
        }
    }
}