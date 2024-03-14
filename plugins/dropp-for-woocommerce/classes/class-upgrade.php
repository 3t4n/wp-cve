<?php

namespace Dropp;

use Dropp\Shipping_Method\Shipping_Method;
use Dropp\Utility\Admin_Notice_Utility;
use Dropp\Utility\Zone_Utility;
use WC_Shipping_Zones;

class Upgrade
{
	const VERSION = '1.0.0';

	public static function setup()
	{
		add_action( 'admin_init', __CLASS__ . '::upgrade' );
	}

	/**
	 * Upgrade
	 */
	public static function upgrade() {
		$saved_version = get_site_option( 'woocommerce_dropp_shipping_db_version' );
		if ( version_compare( $saved_version, '0.0.3' ) === - 1 ) {
			self::upgrade_0_0_4();
		}
		if ( version_compare( $saved_version, '1.0.0' ) === - 1 && $saved_version) {
			// Added new price settings
			self::upgrade_1_0_0();
		}
		if ( version_compare( $saved_version, self::VERSION) === - 1 && self::schema() ) {
			update_site_option( 'woocommerce_dropp_shipping_db_version', self::VERSION );
		}
	}

	/**
	 * Install Consignment table
	 */
	public static function schema() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'dropp_consignments';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			barcode varchar(63) NULL,
			return_barcode varchar(63) NULL,
			day_delivery tinyint(1) DEFAULT 0 NOT NULL,
			dropp_order_id varchar(63) NULL,
			status varchar(15) NOT NULL,
			`comment` text NOT NULL,
			shipping_item_id varchar(63) NOT NULL,
			location_id varchar(63) NOT NULL,
			`value` float NULL,
			products text NOT NULL,
			customer text NOT NULL,
			test tinyint(1) DEFAULT 0 NOT NULL,
			mynto_id varchar(63) NULL,
			created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( $sql );

		return true;
	}

	private static function upgrade_0_0_4()
	{
		/**
		 * In version 1.4.0 (database 0.0.3) we removed cost_2 field from the dropp_is shipping method.
		 * This field is now refactored as a separate shipping method (Dropp Outside Capital Area).
		 *
		 * Note: cost_2 was used based on location pricetype being '2'.
		 * Pricetype is now refactored to be a parameter/shortcode as part of the cost setting.
		 */
		$zones = Zone_Utility::get_zones();
		foreach ( $zones as $zone_data ) {
			$zone = WC_Shipping_Zones::get_zone( $zone_data['zone_id'] );
			foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
				if ( 'Dropp\Shipping_Method\Dropp' !== get_class( $shipping_method ) ) {
					continue;
				}
				$instance_id = $zone->add_shipping_method( 'dropp_is_oca' );
				if ( ! $instance_id ) {
					continue;
				}
				$shipping_methods    = $zone->get_shipping_methods();
				$new_shipping_method = $shipping_methods[ $instance_id ];
				$instance_settings   = $shipping_method->instance_settings;

				// Configure shipping method.
				if ( $instance_settings['cost_2'] ) {
					$instance_settings['cost'] = $instance_settings['cost_2'];
				}
				unset( $instance_settings['cost_2'] );
				update_option(
					$new_shipping_method->get_instance_option_key(),
					apply_filters(
						'woocommerce_shipping_' . $new_shipping_method->id . '_instance_settings_values',
						$instance_settings,
						$new_shipping_method
					),
					'yes'
				);
			}
		}
	}

	private static function upgrade_1_0_0(): void
	{
		// This update introduced new price options based on weight.
		// To make the update intuitive we're going to copy the prices from the first tier to all tiers.
		// The admin user will have to update the prices themselves if they want to use the provided prices.
		$zones = Zone_Utility::get_zones();
		foreach ( $zones as $zone_data ) {
			foreach ( $zone_data['shipping_methods'] as $shipping_method ) {
				if (! $shipping_method instanceof Shipping_Method) {
					continue;
				}
				$cost = $shipping_method->get_option('cost');
				if ($cost === '') {
					continue;
				}
				$cost_tiers = $shipping_method->get_cost_tiers();
				$len = count($cost_tiers);
				for ($i = 1; $i < $len; $i++) {
					$shipping_method->set_instance_option('cost_' . $i, $cost);
				}
				$shipping_method->save_instance_settings();
			}
		}

		// Because this update adds new setting fields we also want to inform users about this change.
		Admin_Notice_Utility::get('dropp_cost_tier_upgrade_notice')->enable();
		Admin_Notice_Utility::update();
	}
}
