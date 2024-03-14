<?php
/**
 * WooCommerce Shipping Rates by City By timersys
 * #[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_shipping_by_city
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_WC_shipping_by_city {

	public function __construct() {
		add_action( 'wfacp_checkout_page_found', [ $this, 'actions' ] );
		add_filter( 'woocommerce_checkout_posted_data', [ $this, 'update_shipping_city' ], 8, 1 );
		add_filter( 'wfacp_print_shipping_hidden_fields', [ $this, 'do_not_print_hidden_shipping' ] );
	}

	public function actions() {
		add_filter( 'wfacp_after_billing_first_name_field', [ $this, 'add_hidden_field' ] );
		add_action( 'wfacp_internal_css', [ $this, 'add_js' ] );
	}

	public function add_hidden_field() {

		$object = WFACP_Common::remove_actions( 'woocommerce_checkout_before_order_review', 'ShipRate_Public', 'add_shiprate_events' );
		if ( $object instanceof ShipRate_Public ) {
			$object->add_shiprate_events();
		}

	}

	public function do_not_print_hidden_shipping( $status ) {
		if ( class_exists( 'ShipRate_Public' ) ) {
			$status = false;
		}

		return $status;
	}

	public function update_shipping_city( $posted_data ) {
		if ( ! class_exists( 'ShipRate_Public' ) ) {
			return $posted_data;
		}
		if ( ! isset( $_REQUEST['_wfacp_post_id'] ) ) {
			return $posted_data;
		}
		if ( ! isset( $posted_data['shipping_city'] ) && isset( $posted_data['billing_city'] ) ) {
			$posted_data['shipping_city'] = $posted_data['billing_city'];
		}

		return $posted_data;
	}

	public function add_js() {
		if ( ! class_exists( 'ShipRate_Public' ) ) {
			return;
		}
		?>
        <script>
            window.addEventListener('DOMContentLoaded', function () {

                jQuery('#billing_city_field').block({
                    message: null,
                    overlayCSS: {
                        background: '#fff',
                        opacity: 0.6
                    }
                });

            });
            window.addEventListener('load', function () {
                (function ($) {
                    trigger_city_field();
                    $(document.body).on('wfacp_coupon_apply', function () {
                        trigger_city_field();
                    });

                    $(document.body).on('wfacp_coupon_form_removed', function () {
                        trigger_city_field();
                    });


                    $(document.body).on('updated_checkout', function () {

                        var city = ['billing', 'shipping'];
                        for (var i in city) {
                            var val = city[i];
                            var el = $('#' + val + '_city');
                            if (el.length > 0) {
                                setTimeout(function () {
                                    var $this = $('#' + val + '_city_field');
                                    if (!$this.hasClass('wfacp-col-left-half')) {
                                        $this.addClass("wfacp-col-left-half");
                                        $this.addClass("wfacp-form-control-wrapper");
                                        $this.find("label").addClass("wfacp-form-control-label");
                                        $('#' + val + '_city').addClass("wfacp-form-control");

                                    }
                                    jQuery('#billing_city_field').unblock()
                                }, 300);
                            }
                        }
                    });

                    $(document.body).on('update_checkout', function () {
                        jQuery('#billing_city_field').block({
                            message: null,
                            overlayCSS: {
                                background: '#fff',
                                opacity: 0.6
                            }
                        });
                    })

                    function trigger_city_field() {
                        var city = ['billing', 'shipping'];
                        for (var i in city) {
                            var val = city[i];
                            var el = $('#' + val + '_city');
                            if (el.length > 0 && true == el.parents(".form-row").is(":visible") && el[0].tagName == 'SELECT') {
                                el.trigger('change');
                            }
                        }
                    }
                })(jQuery);
            });
        </script>
		<?php
	}
}
WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_WC_shipping_by_city(), 'wcsrc' );
