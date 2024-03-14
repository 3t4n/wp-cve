<?php

/**
 * Order WooCommerce UPS Shipping Plugin with Print Label
 * Author: PluginHive
 *  Author URI: https://www.sendinblue.com/?r=wporg
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_WC_SendinBlue
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_UPS_Shipping_Access_Point {

	public function __construct() {
		/* Register Add field */
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_filter( 'wfacp_html_fields_ups_woocommerce_access_point', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 999, 3 );
		add_action( 'wfacp_internal_css', [ $this, 'js' ] );

	}

	public function add_field( $fields ) {

		$fields['ups_woocommerce_access_point'] = [
			'label'       => __( 'Access Point Locations', 'woofunnels-aero-checkout' ),
			'data_label'  => __( 'Access Point Locations', 'woofunnels-aero-checkout' ),
			'type'        => 'wfacp_html',
			'id'          => 'ups_woocommerce_access_point',
			'field_type'  => 'advanced',
			'cssready'    => [ 'wfacp-col-full' ],
			'class'       => [ 'wfacp-form-control-wrapper', 'wfacp-col-full', 'update_totals_on_change' ],
			'input_class' => 'wfacp-form-control',
		];

		return $fields;
	}


	public function call_fields_hook( $field, $key, $args ) {
		if ( ! empty( $key ) && 'ups_woocommerce_access_point' === $key ) {
			$billing_fields = WC()->checkout()->get_checkout_fields( 'billing' );
			if ( ! empty( $billing_fields ) && isset( $billing_fields['shipping_accesspoint'] ) ) {
				$field                = $billing_fields['shipping_accesspoint'];
				$field['class']       = array_merge( $field['class'], [ 'wfacp-form-control-wrapper', 'wfacp-col-full' ] );
				$field['input_class'] = [ 'wfacp-form-control' ];
				$field['label_class'] = 'wfacp-form-control-label';
				woocommerce_form_field( 'shipping_accesspoint', $field );
			}
		}
	}

	public function js() {
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    $(document.body).on('updated_checkout', function () {
                        setTimeout(function () {
                            let accesspoint = $('#shipping_accesspoint');
                            let accesspoint_field = $('#shipping_accesspoint_field');
                            if (accesspoint.length > 0) {
                                accesspoint.addClass('wfacp-form-control');
                                if ('' !== accesspoint.val()) {
                                    accesspoint_field.addClass('wfacp-anim-wrap');
                                }
                            }
                        }, 100);
                    });
                })(jQuery);
            })
        </script>
		<?php
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_UPS_Shipping_Access_Point(), 'ups_shipping_access_point' );
