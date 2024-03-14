<?php
/**
 * Render the settings panel.
 *
 * Author:          Uriahs Victor
 * Created on:      13/10/2022 (d/m/y)
 *
 * @link    https://uriahsvictor.com
 * @since   1.0.0
 * @package Views
 */

namespace Lpac_DPS\Views\Admin\Settings_Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use CSF;
use Lpac_DPS\Views\Admin\Settings_Panel\SettingsSections;

/**
 * Class RenderSettingsPanel.
 */
class RenderSettingsPanel {

	/**
	 * File constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'csf_loaded', array( $this, 'render_admin_settings' ) );
	}

	/**
	 * Bring together all our menu sections together for outputting.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function render_admin_settings() {

		if ( ! class_exists( 'CSF' ) ) {
			return;
		}

		$footer_text  = '<a href="https://chwazidatetime.com/docs/?utm_source=plugin_settings&utm_medium=settings_footer" target="_blank">' . __( 'Documentation', 'delivery-and-pickup-scheduling-for-woocommerce' ) . '</a>';
		$footer_text .= ' | ' . sprintf( __( 'Check out the %1$sPRO plugin!%2$s', 'delivery-and-pickup-scheduling-for-woocommerce' ), '<a href="https://chwazidatetime.com?utm_source=plugin-settings&utm_medium=settings-footer&utm_campaign=upsell" target="_blank">', '</a>' );
		$footer_text .= ' | ' . '<a href="https://lpacwp.com/?utm_source=dps_plugin_settings&utm_medium=settings_footer&utm_campaign=cross-sell" target="_blank">' . __( 'Kikote - Location Picker at Checkout Plugin for WooCommerce', 'delivery-and-pickup-scheduling-for-woocommerce' ) . '</a>';
		$footer_text .= ' | ' . '<a href="https://printus.cloud/?utm_source=dps_plugin_settings&utm_medium=settings_footer&utm_campaign=cross-sell" target="_blank">' . __( 'Printus - Cloud Printing Plugin for WooCommerce', 'delivery-and-pickup-scheduling-for-woocommerce' ) . '</a>';

		CSF::createOptions(
			LPAC_DPS_CSF_ID,
			array(
				'menu_title'      => 'Chwazi - Delivery & Pickup Scheduling',
				'menu_slug'       => 'lpac-dps-menu',
				'framework_title' => 'Chwazi - Delivery & Pickup Scheduling <small>for WooCommerce</small>',
				'menu_type'       => 'submenu',
				'menu_parent'     => 'sl-plugins-menu',
				'theme'           => 'light',
				'footer_text'     => $footer_text,
				'ajax_save'       => false,
				'show_bar_menu'   => false,
			)
		);

		( new SettingsSections() )->render_menu_sections();
	}
}
