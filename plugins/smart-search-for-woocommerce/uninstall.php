<?php
/**
 * Searchanise Uninstall
 *
 * Uninstalling Searchanise deletes searchanise engine
 *
 * @package Searchanise\Uninstaller
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

use Searchanise\SmartWoocommerceSearch\Queue;
use Searchanise\SmartWoocommerceSearch\Cron;
use Searchanise\SmartWoocommerceSearch\Installer;
use Searchanise\SmartWoocommerceSearch\Api;

require_once __DIR__ . '/init.php';

$engines = Api::get_instance()->get_engines( null, false, true );
foreach ( $engines as $engine ) {
	Api::get_instance()->addon_status_request( Api::ADDON_STATUS_DELETED, $engine['lang_code'] );
	Api::get_instance()->set_export_status( Api::EXPORT_STATUS_NONE, $engine['lang_code'] );
}

Queue::get_instance()->clear_actions();
Cron::unregister();
Installer::uninstall();
