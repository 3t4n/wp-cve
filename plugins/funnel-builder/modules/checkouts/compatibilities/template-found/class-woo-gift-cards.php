<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Plugin: Woo Gift Cards By Woo | v1.16.8
 * Plugin URL: https://woo.com/
 */

#[AllowDynamicProperties]
class WFACP_Compatibility_With_woo_Gift_Cards {
	private $order_summary_present = false;
	private $mini_cart_summary_present = false;

	public function __construct() {
		add_action( 'wfacp_template_load', [ $this, 'action' ] );
		add_action( 'wfacp_html_fields_order_summary', [ $this, 'order_summary_present' ] );
		add_action( 'wfacp_before_mini_cart_html', [ $this, 'mini_cart_present' ] );
	}

	public function action() {
		add_action( 'woocommerce_checkout_before_customer_details', [ $this, 'detect_field' ] );
		add_action( 'wp_footer', [ $this, 'js' ] );
	}

	public function detect_field() {
		$template = wfacp_template();
		$fields   = $template->get_checkout_fields();
		if ( ! isset( $fields['advanced']['order_summary'] ) ) {
			$this->order_summary_present = true;
			?>
            <input type="checkbox" id="use_gift_card_balance" name="use_gift_card_balance" style="display: none">
			<?php
		}
	}

	public function order_summary_present( $status ) {
		$this->order_summary_present = true;

		return $status;
	}


	public function mini_cart_present() {
		$this->mini_cart_summary_present = true;
	}

	public function js() {
		if ( ! is_checkout() || false == $this->mini_cart_summary_present ) {
			return;
		}
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {

                    function set_prop(el) {
                        var form = $('.checkout.woocommerce-checkout');
                        let field = form.find('#use_gift_card_balance');
                        if (field.length == 0) {
                            return;
                        }
                        console.log(el.is(":checked"));
                        if (el.is(":checked")) {
                            field.prop('checked', true);
                        } else {
                            field.prop('checked', false);
                        }
                        setTimeout(function () {
                            $(document.body).trigger('update_checkout');
                        }, 300);
                    }

                    $('.wfacp_collapsible_order_summary_wrap').on('change', '#use_gift_card_balance', function () {
                        set_prop($(this));
                    });
                    $('.wfacp_min_cart_widget').on('change', '#use_gift_card_balance', function () {
                        set_prop($(this));
                    });

                })(jQuery);
            });

        </script>
		<?php
	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_woo_Gift_Cards(), 'woocommerce-gift-cards' );
