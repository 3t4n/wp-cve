<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties]

  class WFACP_Compatibility_With_WC_Drip {
	/**
	 * @var WC_Drip_Subscriptions
	 */
	private $wc_drip_obj = null;
	private $field_arg = null;
	private $actives = [];

	public function __construct() {
		add_action( 'init', [ $this, 'init_class' ], 4 );
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_drip_field' ] );
		add_filter( 'wfacp_html_fields_wcdrip_subscribe_1', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_wc_drip_hook' ], 10, 3 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'drip_css_func' ] );
	}


	public function init_class() {

		$wrapper = [];
		if ( function_exists( 'wcdrip_get_settings' ) ) {
			$wrapper = wcdrip_get_settings();
		}

		if ( is_array( $wrapper ) && isset( $wrapper['subscribe_enable'] ) && isset( $wrapper['subscribe_campaign'] ) && ( $wrapper['subscribe_enable'] == 'yes' ) && $wrapper['subscribe_campaign'] ) {

			if ( class_exists( 'WC_Drip_Subscriptions' ) ) {

				$this->actives['WC_Drip_Subscriptions'] = WC_Drip_Subscriptions::get_instance();
			}
		}

	}


	public function add_drip_field( $field ) {

		if ( $this->is_enable( 'WC_Drip_Subscriptions' ) ) {
			$field['wcdrip_subscribe_1'] = [
				'type'       => 'wfacp_html',
				'class'      => [ 'drip_subscribe' ],
				'id'         => 'wcdrip_subscribe_1',
				'field_type' => 'advanced',
				'label'      => __( 'Drip', 'woocommerce' ),

			];
		}

		return $field;
	}

	public function is_enable( $slug ) {
		if ( isset( $this->actives[ $slug ] ) ) {
			return true;
		}

		return false;
	}

	public function call_wc_drip_hook( $field, $key, $args ) {

		if ( ! empty( $key ) && $key == 'wcdrip_subscribe_1' && class_exists( 'WC_Drip_Subscriptions' ) ) {

			$this->wc_drip_obj = WC_Drip_Subscriptions::get_instance();

			$this->field_arg = $args;
			if ( ! is_null( $this->wc_drip_obj ) ) {

				$this->wc_drip_obj->subscribe_field( WC()->checkout() );
			}
		}

	}

	public function add_default_wfacp_styling( $args, $key ) {

		if ( $key == 'wcdrip_subscribe' && $this->is_enable( 'WC_Drip_Subscriptions' ) ) {

			if ( ! is_null( $this->field_arg ) ) {
				$all_cls = array_merge( [ 'wfacp-form-control-wrapper wfacp_custom_field_cls wfacp_drip_wrap' ], $args['class'] );
				if ( isset( $this->field_arg['cssready'] ) && is_array( $this->field_arg['cssready'] ) ) {
					$all_cls = array_merge( $all_cls, $this->field_arg['cssready'] );
				}
				$args['class'] = $all_cls;

			}
		}

		return $args;
	}


	public function drip_css_func( $selected_template_slug ) {
		$array_class = [
			'layout_1' => 15,
			'layout_2' => 15,
			'layout_4' => 15,
			'layout_9' => 7,
		];

		if ( isset( $array_class[ $selected_template_slug ] ) ) {

			?>
            <style>
                body #wcdrip_subscribe_field span.optional {
                    display: inline-block !important;
                }

                body #wcdrip_subscribe_field label {
                    font-weight: normal;
                }

                body .wfacp_main_form #wcdrip_subscribe_field {

                    padding: 0 <?php echo $array_class[ $selected_template_slug ]; ?>px;
                }
            </style>
			<?php
		}
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_Drip(), 'wcdrip' );
