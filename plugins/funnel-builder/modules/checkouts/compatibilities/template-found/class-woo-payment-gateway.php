<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin Name: Braintree For WooCommerce
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Woo_Payment_Gateway
 * https://wordpress.org/plugins/woo-payment-gateway/
 */
#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Woo_Payment_Gateway {

	private $gateways = [];
	private $buttons = [];

	public function __construct() {
		add_filter( 'wfacp_body_class', [ $this, 'add_body_class' ], 999 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_action' ], 999 );
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'add_fragment' ], 150, 2 );
		add_action( 'wfacp_intialize_template_by_ajax', function () {
			//for when our fragments calls running
			add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'add_fragment' ], 99, 2 );
		}, 10 );

		add_filter( 'wfacp_smart_buttons', [ $this, 'add_buttons' ], 16 );
		add_action( 'wfacp_internal_css', [ $this, 'some_css' ] );
		add_filter( 'woocommerce_checkout_fields', [ $this, 'push_shipping_first_last_name_hidden_field' ] );
	}

	public function add_buttons( $buttons ) {

		if ( ! class_exists( 'WC_Braintree_Manager' ) ) {
			return $buttons;
		}
		if ( ! is_checkout() ) {
			return $buttons;
		}

		foreach ( WC()->payment_gateways()->get_available_payment_gateways() as $id => $gateway ) {
			if ( $gateway->supports( 'wc_braintree_banner_checkout' ) && $gateway->banner_checkout_enabled() ) {
				$slug                    = 'woo_braintree_' . $id;
				$this->gateways[ $slug ] = $gateway;

				$buttons[ $slug ] = [
					'iframe' => true,
					'name'   => $gateway->get_title(),
				];
				add_action( 'wfacp_smart_button_container_woo_braintree_' . $id, [
					$this,
					'print_smart_buttons'
				], 10, 2 );
			}
		}

		if ( ! empty( $this->gateways ) ) {
			remove_action( 'woocommerce_checkout_before_customer_details', 'wc_braintree_banner_checkout_template' );
		}

		return $buttons;
	}

	public function print_smart_buttons( $payment, $slug ) {
		if ( ! empty( $this->gateways ) && function_exists( 'wc_braintree_banner_checkout_template' ) ) {
			$gateway = $this->gateways[ $slug ];
			?>
			<div
				class="wc-braintree-banner-gateway wc_braintree_banner_gateway_<?php echo esc_attr( $gateway->id ); ?>">
				<?php $gateway->banner_fields(); ?>
			</div>
			<?php
		}
	}


	public function add_body_class( $class ) {
		if ( function_exists( 'requireBraintreeProDependencies' ) ) {
			$class[] = 'bfwc-body';
		}

		return $class;
	}

	public function remove_action() {
		if ( class_exists( 'WC_Braintree_Field_Manager' ) ) {
			remove_action( 'woocommerce_review_order_after_order_total', [
				'WC_Braintree_Field_Manager',
				'output_checkout_fields'
			] );
			add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'print_order_total_fields' ] );
		}

	}

	public function print_order_total_fields() {
		if ( class_exists( 'WC_Braintree_Field_Manager' ) ) {
			echo '<div id="woo-payment-gateway-wfacp-payment-fields">';
			WC_Braintree_Field_Manager::output_checkout_fields();
			echo '</div>';
		}
	}

	public function add_fragment( $fragments ) {
		if ( class_exists( 'WC_Braintree_Field_Manager' ) && isset( WFACP_Common::$post_data['_wfacp_post_id'] ) ) {
			ob_start();
			$this->print_order_total_fields();
			$fragments['#woo-payment-gateway-wfacp-payment-fields'] = ob_get_clean();
		}

		return $fragments;
	}

	public function some_css() {
		?>
		<style>
            .wfacp_smart_button_container .wc-braintree-banner-gateway {

                vertical-align: top;
            }

            #wfacp_smart_button_woo_braintree .gpay-card-info-placeholder-container {
                width: 240px !important
            }

            .gpay-card-info-animation-container {
                display: flex;
                width: 100% !important;
                position: absolute;
                z-index: 100;
                height: 40px;
                border-radius: 4px
            }

            #wfacp_smart_buttons .gpay-card-info-iframe {
                width: 240px !important;
                border: 0;
                display: block;
                height: 40px;
                margin: auto;
                max-width: 100%;
            }

            #wfacp_smart_buttons #wfacp_smart_button_woo_braintree image, #wfacp_smart_buttons #wfacp_smart_button_woo_braintree img {
                width: auto !important
            }
		</style>
		<script>
            window.addEventListener('bwf_checkout_load', function () {

                (function ($) {
                    function showAnimeField() {
                        setTimeout(() => {
                            let elements = $('.wfacp-form-control:visible');
                            elements.each(function () {
                                let value = $(this).val();
                                let parent = $(this).parents('.form-row');
                                if ('' !== value) {
                                    parent.addClass('wfacp-anim-wrap');
                                    parent.removeClass('woocommerce-invalid woocommerce-invalid-required-field');
                                }
                            });
                        }, 200);
                    }

                    $(document.body).on('update_checkout', function (e, args) {
                        if (typeof args !== 'undefined') {
                            showAnimeField();
                        }
                    });
                    $(document.body).on('wc_braintree_submit_error', function () {
                        showAnimeField();
                    });
                })(jQuery);

            });
		</script>
		<?php
	}

	public function push_shipping_first_last_name_hidden_field( $fields ) {
		$instance = wfacp_template();
		if ( is_null( $instance ) || wp_doing_ajax() ) {
			return $fields;
		}

		if ( ! ( $instance->have_shipping_address() && $instance->have_billing_address() ) ) {
			return $fields;
		}

		if ( empty( $fields['shipping'] ) ) {
			return $fields;
		}

		if ( isset( $fields['billing']['billing_first_name'] ) && ! isset( $fields['shipping']['shipping_first_name'] ) ) {
			$fields['shipping']['shipping_first_name'] = [
				'id'         => 'shipping_first_name',
				'type'       => 'hidden',
				'field_type' => 'shipping'
			];
		}
		if ( isset( $fields['billing']['billing_last_name'] ) && ! isset( $fields['shipping']['shipping_last_name'] ) ) {
			$fields['shipping']['shipping_last_name'] = [
				'id'         => 'shipping_last_name',
				'type'       => 'hidden',
				'field_type' => 'shipping'
			];
		}

		return $fields;
	}

}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Woo_Payment_Gateway(), 'woo-payment-gateway' );
