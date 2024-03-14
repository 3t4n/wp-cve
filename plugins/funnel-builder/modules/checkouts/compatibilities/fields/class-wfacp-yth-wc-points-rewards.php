<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * YITH WooCommerce Points and Rewards Premium by YITH upto(2.0.7)
 * Plugin Path: https://yithemes.com/themes/plugins/yith-woocommerce-points-and-rewards/
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_With_YTH_WC_Points_Rewards {
	public $instance = null;

	public function __construct() {
		/* Add field in the advanced option */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_yith_wc_birthday', '__return_false' );
		/* Display the field */
		add_action( 'process_wfacp_html', [ $this, 'process_wfacp_html' ], 10, 2 );
		/* Remove Checkout field and initialize object  */
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'action' ] );
		/* styling for tipping field */
		add_action( 'wfacp_internal_css', [ $this, 'wfacp_internal_css' ] );
	}

	public function is_enabled() {
		if ( ! class_exists( 'YITH_WC_Points_Rewards_Frontend' ) ) {
			return false;
		}
		$available_places = get_option( 'ywpar_birthday_date_field_where', array( 'my-account', 'register_form', 'checkout' ) );

		if ( ! in_array( 'checkout', $available_places ) ) {
			return false;
		}

		return true;
	}

	public function action() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		$this->instance = WFACP_Common::remove_actions( 'woocommerce_checkout_fields', 'YITH_WC_Points_Rewards_Frontend', 'add_birthday_field_checkout' );

	}

	public function add_field( $fields ) {
		if ( ! $this->is_enabled() ) {
			return $fields;
		}
		$fields['yith_wc_birthday'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'yith_wc_birthday' ],
			'id'         => 'yith_wc_birthday',
			'field_type' => 'yith_wc_birthday',
			'label'      => __( 'Yth WC Birthday', 'woofunnels-aero-checkout' ),
		];

		return $fields;
	}

	public function process_wfacp_html( $field, $key ) {
		if ( ! $this->is_enabled() || 'yith_wc_birthday' !== $key || ! $this->instance instanceof YITH_WC_Points_Rewards_Frontend ) {
			return;
		}
		$all_fields = $this->instance->add_birthday_field_checkout( (array) WC()->checkout() );
		if ( isset( $all_fields['billing']['yith_birthday'] ) ) {
			$yith_birthday_field = $all_fields['billing']['yith_birthday'];
			echo "<div id=wfacp_yith_wc_birthday>";
			$yith_birthday_field['input_class'] = array_merge( [ 'wfacp-form-control' ], $yith_birthday_field['input_class'] );
			$label_class                        = [];
			if ( isset( $yith_birthday_field['label_class'] ) ) {
				$label_class = $yith_birthday_field['label_class'];
			}
			$yith_birthday_field['label_class'] = array_merge( [ 'wfacp-form-control-label' ], $label_class );
			$yith_birthday_field['class']       = array_merge( [ 'wfacp-form-control-wrapper wfacp-col-left-half ' ], $yith_birthday_field['class'] );
			$yith_birthday_field['cssready']    = [ 'wfacp-col-left-half' ];
			woocommerce_form_field( 'yith_birthday', $yith_birthday_field );
			echo "</div>";
		}

	}

	public function wfacp_internal_css() {
		if ( ! $this->is_enabled() ) {
			return;
		}
		?>
        <style>
            #wfacp_yith_wc_birthday {
                clear: both;
            }
        </style>
		<?php
	}

}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_YTH_WC_Points_Rewards(), 'wfacp-yth-wc-points-rewards' );
