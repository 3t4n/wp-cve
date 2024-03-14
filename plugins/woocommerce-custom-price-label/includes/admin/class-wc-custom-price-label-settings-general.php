<?php
/**
 * WooCommerce Custom Price Label - General Section Settings
 *
 * @version 2.5.12
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'WC_Custom_Price_Label_Settings_General' ) ) :

class WC_Custom_Price_Label_Settings_General extends Alg_WC_Custom_Price_Labels_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 2.3.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'woocommerce-custom-price-label' );
		parent::__construct();
		add_action( 'woocommerce_admin_field_alg_wc_custom_price_labels_dashboard', array( $this, 'output_dashboard' ) );
		add_action( 'woocommerce_admin_field_alg_wc_custom_price_label_textarea',   array( $this, 'output_custom_textarea' ) );
		add_filter( 'woocommerce_admin_settings_sanitize_option',                   array( $this, 'unclean_custom_textarea' ), PHP_INT_MAX, 3 );
	}

	/**
	 * unclean_custom_textarea.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function unclean_custom_textarea( $value, $option, $raw_value ) {
		return ( 'alg_wc_custom_price_label_textarea' === $option['type'] ) ? $raw_value : $value;
	}

	/**
	 * output_custom_textarea.
	 *
	 * @version 2.2.0
	 * @since   2.2.0
	 */
	function output_custom_textarea( $value ) {
		$option_value = get_option( $value['id'], $value['default'] );

		$custom_attributes = ( isset( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) ? $value['custom_attributes'] : array();
		$description = ' <p class="description">' . $value['desc'] . '</p>';
		$tooltip_html = ( isset( $value['desc_tip'] ) && '' != $value['desc_tip'] ) ? '<span class="woocommerce-help-tip" data-tip="' . $value['desc_tip'] . '"></span>' : '';

		?><tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
				<?php echo $tooltip_html; ?>
			</th>
			<td class="forminp forminp-<?php echo sanitize_title( $value['type'] ) ?>">
				<?php echo $description; ?>

				<textarea
					name="<?php echo esc_attr( $value['id'] ); ?>"
					id="<?php echo esc_attr( $value['id'] ); ?>"
					style="<?php echo esc_attr( $value['css'] ); ?>"
					class="<?php echo esc_attr( $value['class'] ); ?>"
					placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
					<?php echo implode( ' ', $custom_attributes ); ?>
					><?php echo esc_textarea( $option_value );  ?></textarea>
			</td>
		</tr><?php
	}

	/**
	 * get_settings_data.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function get_settings_data() {
		return array(
			'global_price_labels' => array(
				'title'                  => __( 'Global Price Labels', 'woocommerce-custom-price-label' ),
				'desc'                   => __( 'Set price labels for all products globally.', 'woocommerce-custom-price-label' ),
				'enabled_option_id'      => 'woocommerce_global_price_labels_enabled',
				'enabled_option_default' => 'yes',
			),
			'local_price_labels' => array(
				'title'                  => __( 'Per Product Price Labels', 'woocommerce-custom-price-label' ),
				'desc'                   => __( 'Set price labels for each product individually.', 'woocommerce-custom-price-label' ),
				'enabled_option_id'      => 'woocommerce_local_price_labels_enabled',
				'enabled_option_default' => 'yes',
			),
		);
	}

	/**
	 * output_dashboard.
	 *
	 * @version 2.3.0
	 * @since   2.3.0
	 */
	function output_dashboard( $value ) {
		$settings_data = $this->get_settings_data();
		$table_data = array(
			array(
				'<strong>' . __( 'Section', 'woocommerce-custom-price-label' ) . '</strong>',
				'<strong>' . __( 'Description', 'woocommerce-custom-price-label' ) . '</strong>',
				'<strong>' . __( 'Status', 'woocommerce-custom-price-label' ) . '</strong>',
			),
		);
		foreach ( $settings_data as $settings_id => $settings_info ) {
			$table_data[] = array(
				'<strong>' . $settings_info['title'] . '</strong>' .
					'<div class="row-actions visible">' .
						'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=custom_price_label&section=' . $settings_id ) . '">' . __( 'Settings', 'woocommerce-custom-price-label' ) . '</a>' .
					'</div>',
				'<em>' . $settings_info['desc'] . '</em>',
				'<em>' . ( 'yes' === get_option( $settings_info['enabled_option_id'], $settings_info['enabled_option_default'] ) ?
					'<span style="color:green;">' . __( 'Enabled', 'woocommerce-custom-price-label' ) . '</span>' : __( 'Disabled', 'woocommerce-custom-price-label' ) ) . '</em>',
			);
		}
		$table_html = alg_get_table_html( $table_data, array( 'table_class' => 'widefat striped', 'table_heading_type' => 'horizontal' ) );
		echo '<h2>' . __( 'Dashboard', 'woocommerce-custom-price-label' ) . '</h2>';
		echo '<tr valign="top"><td colspan="2">' . $table_html  . '</td></tr>';
	}

	/**
	 * get_section_settings.
	 *
	 * @version 2.5.11
	 * @todo    add link to custom roles plugin/tool
	 */
	public static function get_section_settings() {
		$settings = array(
			array(
				'title'     => __( 'Custom Price Labels Options', 'woocommerce-custom-price-label' ),
				'type'      => 'title',
				'id'        => 'woocommerce_custom_price_label_options',
			),
			array(
				'title'     => __( 'Custom Price Labels for WooCommerce', 'woocommerce-custom-price-label' ),
				'desc'      => '<strong>' . __( 'Enable plugin', 'woocommerce-custom-price-label' ) . '</strong>',
				'desc_tip'  => __( 'Create any custom price label for any WooCommerce product.', 'woocommerce-custom-price-label' )
					. '<br /><a href="https://wpwham.com/documentation/custom-price-labels-for-woocommerce/?utm_source=documentation_link&utm_campaign=free&utm_medium=custom_price_label" target="_blank" class="button">'
					. __( 'Documentation', 'woocommerce-custom-price-label' ) . '</a>',
				'id'        => 'woocommerce_custom_price_label_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Override global price labels with per product labels', 'woocommerce-custom-price-label' ),
				'desc'      => __( 'Enable', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'If enabled, this will override global price labels with per product labels (if set). Otherwise labels will be combined (global first).', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_custom_price_label_override_global_with_local',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Search bots', 'woocommerce-custom-price-label' ),
				'desc'      => __( 'Disable', 'woocommerce-custom-price-label' ),
				'desc_tip'  => __( 'Here you can disable custom price labels for search bots.', 'woocommerce-custom-price-label' ),
				'id'        => 'woocommerce_custom_price_label_disable_for_bots',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'woocommerce_custom_price_label_options',
			),
			array(
				'type'      => 'alg_wc_custom_price_labels_dashboard',
			),
		);

		return $settings;
	}

}

endif;

return new WC_Custom_Price_Label_Settings_General();
