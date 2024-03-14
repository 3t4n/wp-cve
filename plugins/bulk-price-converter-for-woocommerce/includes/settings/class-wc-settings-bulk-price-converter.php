<?php
/**
 * Bulk Price Converter - Settings
 *
 * @version 1.5.0
 * @since   1.0.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_Settings_Bulk_Price_Converter' ) ) :

class Alg_WC_Settings_Bulk_Price_Converter extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id    = 'alg_wc_bulk_price_converter';
		$this->label = __( 'Bulk Price Converter', 'bulk-price-converter-for-woocommerce' );
		add_action( 'woocommerce_admin_field_bulk_price_converter_custom_link', array( $this, 'output_custom_link' ) );
		parent::__construct();
	}

	/**
	 * output_custom_link.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function output_custom_link( $value ) {
		?><tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
			</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
				<?php echo $value['link']; ?>
			</td>
		</tr><?php
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'bulk-price-converter-for-woocommerce' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'bulk-price-converter-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Reset', 'bulk-price-converter-for-woocommerce' ) . '</strong>',
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			__( 'Your settings have been reset.', 'bulk-price-converter-for-woocommerce' ) . '</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 1.5.0
	 * @since   1.5.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}

}

endif;

return new Alg_WC_Settings_Bulk_Price_Converter();
