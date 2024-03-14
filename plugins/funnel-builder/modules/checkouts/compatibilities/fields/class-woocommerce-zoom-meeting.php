<?php

/**
 * WooCommerce to Zoom Meetings
 * https://www.northernbeacheswebsites.com.au
 * By Martin Gibson
 * #[AllowDynamicProperties]

  class WFACP_Compatibility_WooCommerce_Zoom_By_MB
 */
#[AllowDynamicProperties]

  class WFACP_Compatibility_WooCommerce_Zoom_By_MB {

	public function __construct() {
		add_filter( 'wfacp_advanced_fields', [ $this, 'add_field' ], 20 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'actions' ] );
		add_filter( 'wfacp_html_fields_wc_to_zoom_checkout', '__return_false' );
		add_action( 'process_wfacp_html', [ $this, 'call_fields_hook' ], 999, 3 );
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 99, 2 );
		add_action( 'wfacp_internal_css', [ $this, 'css' ] );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'add_fragments' ], 1000 );
	}

	public function actions() {
		if ( false === $this->is_enabled() ) {
			return;
		}
		remove_action( 'woocommerce_after_order_notes', 'woocommerce_to_zoom_checkout_fields' );
		remove_action( 'woocommerce_after_order_notes', 'woocommerce_to_zoom_meetings_checkout_fields' );


	}

	private function is_enabled() {
		return ( function_exists( 'woocommerce_to_zoom_checkout_fields' ) || function_exists( 'woocommerce_to_zoom_meetings_checkout_fields' ) );
	}

	public function add_field( $fields ) {
		if ( false === $this->is_enabled() ) {
			return $fields;
		}
		$fields['wc_to_zoom_checkout'] = [
			'type'       => 'wfacp_html',
			'class'      => [ 'wfacp-col-full', 'wfacp-form-control-wrapper', 'wfacp_anim_wrap', 'deliveryDatePosition' ],
			'id'         => 'wc_to_zoom_checkout',
			'field_type' => 'wc_to_zoom_checkout',
			'label'      => __( 'Zoom Meetings', 'funnel-builder' ),
		];


		return $fields;
	}

	public function call_fields_hook( $field, $key, $args ) {
		if ( ! empty( $key ) && $this->is_enabled() && 'wc_to_zoom_checkout' === $key ) {
			echo "<div class='aero_woocommerce_to_zoom_meetings_checkout_fields'>";
			if ( function_exists( 'woocommerce_to_zoom_meetings_checkout_fields' ) ) {
				woocommerce_to_zoom_meetings_checkout_fields( WC()->checkout() );
			}
			if ( function_exists( 'woocommerce_to_zoom_checkout_fields' ) ) {
				woocommerce_to_zoom_checkout_fields( WC()->checkout() );
			}
			echo "</div>";
		}
	}

	public function add_default_wfacp_styling( $args, $key ) {
		if ( ! empty( $key ) && $this->is_enabled() && ( false !== strpos( $key, '-first_name' ) || false !== strpos( $key, '-last_name' ) || false !== strpos( $key, '-email' ) ) ) {
			$args['input_class'] = array_merge( $args['input_class'], [ 'wfacp-form-control' ] );
			$args['label_class'] = array_merge( $args['label_class'], [ 'wfacp-form-control-label' ] );
			if ( false !== strpos( $key, '-email' ) ) {
				$args['class'] = array_merge( $args['class'], [ 'wfacp-col-full', 'wfacp-form-control-wrapper' ] );
			} else {
				$args['class'] = array_merge( $args['class'], [ 'wfacp-col-left-half', 'wfacp-form-control-wrapper' ] );
			}

		}

		return $args;
	}

	public function css() {
		?>
        <style>
            button.woocommerce-to-zoom-meetings-copy-from-billing {
                color: #fff;
                background: var(--global-palette-btn-bg-hover);
                box-shadow: 0px 15px 25px -7px rgba(0, 0, 0, 0.1);
                background: rgb(43, 108, 176);
                padding: 15px;
                margin-left: 14px;
                border-radius: 5px;
                padding: 0.4em 1em;
                border: 0;
                line-height: 1.6;
            }

            .zoom-meeting-section {
            }

            .zoom-meeting-section p {
                padding-left: 0;
                margin-top: 3px;
            }

            .zoom-meeting-section {
                padding-left: 15px;
            }

            strong.zoom-meeting-registrant-section {
                display: block;
            }
        </style>
		<?php
	}

	public function add_fragments( $fragments ) {
		if ( ! $this->is_enabled() ) {
			return $fragments;
		}

		ob_start();
		?>
        <div class='aero_woocommerce_to_zoom_meetings_checkout_fields'>
			<?php
			if ( function_exists( 'woocommerce_to_zoom_meetings_checkout_fields' ) ) {
				woocommerce_to_zoom_meetings_checkout_fields( WC()->checkout() );
			}
			if ( function_exists( 'woocommerce_to_zoom_checkout_fields' ) ) {
				woocommerce_to_zoom_checkout_fields( WC()->checkout() );
			}
			?>
        </div>
		<?php
		$fragments['.aero_woocommerce_to_zoom_meetings_checkout_fields'] = ob_get_clean();


		return $fragments;
	}


}

new WFACP_Compatibility_WooCommerce_Zoom_By_MB();
