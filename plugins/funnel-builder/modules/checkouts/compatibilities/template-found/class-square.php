<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Square {
	protected $is_enabled = false;

	public function __construct() {
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'is_enabled' ] );
		add_filter( 'wfacp_css_js_deque', [ $this, 'unset_cart_fragment_js' ], 10, 2 );
		add_filter( 'wfacp_not_allowed_cart_fragments_js_for_embed_form', [ $this, 'do_no_deque_cart_fragment' ] );
		add_action( 'wfacp_internal_css', [ $this, 'unblock_shipping_method' ] );
	}

	public function is_enabled() {
		if ( WFACP_Common::is_theme_builder() ) {
			return;
		}
		$gateways = WC()->payment_gateways()->get_available_payment_gateways();
		if ( isset( $gateways['square_credit_card'] ) ) {
			$this->is_enabled = true;
		}
	}

	public function unset_cart_fragment_js( $status, $path ) {
		if ( false == $this->is_enabled ) {
			return $status;
		}
		if ( false !== strpos( $path, 'cart-fragments.min.js' ) || false !== strpos( $path, 'cart-fragments.js' ) ) {
			$status = false;
		}

		return $status;
	}

	public function do_no_deque_cart_fragment( $status ) {
		if ( true == $this->is_enabled ) {
			$status = false;
		}

		return $status;
	}

	public function unblock_shipping_method() {
		if ( false == $this->is_enabled ) {
			return;
		}
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    $(document).ajaxComplete(function (event, jqxhr, settings) {
                        if (settings.url.indexOf("wc_square_") > -1 || settings.url.indexOf("checkout") > -1) {
                            $('.wfacp_shipping_options').unblock();
                        }
                    });
                })(jQuery);
            });
        </script>
		<?php
	}

	public static function is_enable() {
		return class_exists( 'WooCommerce_Square_Loader' );
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Square(), 'square' );

