<?php
/**
 * Global Shop Discount for WooCommerce - Settings
 *
 * @version 1.9.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Global_Shop_Discount_Settings' ) ) :

class Alg_WC_Global_Shop_Discount_Settings extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	function __construct() {

		$this->id    = 'alg_wc_global_shop_discount';
		$this->label = __( 'Global Shop Discount', 'global-shop-discount-for-woocommerce' );

		parent::__construct();

		// Sections
		require_once( 'class-alg-wc-global-shop-discount-settings-section.php' );
		require_once( 'class-alg-wc-global-shop-discount-settings-general.php' );
		require_once( 'class-alg-wc-global-shop-discount-settings-group.php' );
		for ( $i = 1; $i <= apply_filters( 'alg_wc_global_shop_discount_total_groups', 1 ); $i++ ) {
			new Alg_WC_Global_Shop_Discount_Settings_Group( $i );
		}
		require_once( 'class-alg-wc-global-shop-discount-settings-tools.php' );

	}

	/**
	 * get_settings.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge(
			apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ),
			( 'tools' === $current_section ?
				array() :
				array(
					array(
						'title'     => __( 'Reset Settings', 'global-shop-discount-for-woocommerce' ),
						'type'      => 'title',
						'id'        => $this->id . '_' . $current_section . '_reset_options',
					),
					array(
						'title'     => __( 'Reset section settings', 'global-shop-discount-for-woocommerce' ),
						'desc'      => '<strong>' . __( 'Reset', 'global-shop-discount-for-woocommerce' ) . '</strong>',
						'desc_tip'  => __( 'Check the box and save changes to reset.', 'global-shop-discount-for-woocommerce' ),
						'id'        => $this->id . '_' . $current_section . '_reset',
						'default'   => 'no',
						'type'      => 'checkbox',
					),
					array(
						'type'      => 'sectionend',
						'id'        => $this->id . '_' . $current_section . '_reset_options',
					),
				)
			)
		);
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.1.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) add notice
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$id   = explode( '[', $value['id'] );
					$id_a = $id[0];
					if ( $id_a === $value['id'] ) {
						delete_option( $value['id'] );
					} else {
						$id_i = explode( ']', $id[1] );
						$id_i = $id_i[0];
						$prev_value = get_option( $id_a, array() );
						$prev_value[ $id_i ] = $value['default'];
						update_option( $id_a, $prev_value );
					}
				}
			}
		}
	}

	/**
	 * Save settings.
	 *
	 * @version 1.9.0
	 * @since   1.0.0
	 */
	function save() {

		parent::save();

		$this->maybe_reset_settings();

		do_action( 'alg_wc_global_shop_discount_settings_saved' );

		global $current_section;
		if ( '' === $current_section ) {
			// for the "Total groups" option
			wp_safe_redirect( add_query_arg( '', '' ) );
			exit;
		}

	}

}

endif;

return new Alg_WC_Global_Shop_Discount_Settings();
