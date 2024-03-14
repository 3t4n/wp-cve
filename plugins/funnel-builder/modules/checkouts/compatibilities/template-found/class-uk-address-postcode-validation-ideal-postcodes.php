<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class wfacp_uk_address_postcode_validation_by_ideal_postcodes {
	public function __construct() {
		add_action( 'wp_footer', [ $this, 'add_js' ] );
		add_action( 'wfacp_internal_css', [ $this, 'internal_css' ] );
	}


	public function internal_css() {
		echo '<style>';

		echo ".wfacp_main_form.woocommerce input#idpc_input {padding: 12px 12px 10px;}";
		echo ".wfacp_main_form.woocommerce .idpc_lookup > button {margin: 10px 0 0;padding: 10px;}";
		echo ".wfacp_main_form.woocommerce select#idpc_dropdown{
        margin: 10px 0 0;padding: 12px 12px 10px;-webkit-appearance: menulist;-moz-appearance: menulist;
        -webkit-appearance: menulist;appearance: menulist;
        }";
		echo '</style>';


	}

	public function add_js() {
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    add_aero_class();
                    $(document.body).on('updated_checkout', function () {
                        setTimeout(function () {
                            add_aero_class();
                        }, 500)
                    });

                    function add_aero_class() {
                        var addresses = ['billing', 'shipping'];
                        for (var i in addresses) {
                            var key = addresses[i];
                            $(".wfacp_divider_" + key + " .form-row").each(function () {
                                var field_id = $(this).attr("id");
                                var section_wrapper = ".wfacp_divider_" + key;
                                if (typeof field_id == "undefined") {
                                    var country_key = key + "_country_field";
                                    if ($(section_wrapper + " #" + country_key).length > 0 && $(section_wrapper + " #" + country_key).hasClass("wfacp_" + key + "_field_hide")) {
                                        var field_key = "wfacp_" + key + "_fields";
                                        var field_hide_key = "wfacp_" + key + "_field_hide";
                                        if (!$(this).hasClass(field_key)) {
                                            $(this).addClass(field_key);
                                        }
                                        if (!$(this).hasClass(field_hide_key)) {
                                            $(this).addClass(field_hide_key);
                                        }
                                    }
                                    if (!$(this).hasClass("wfacp-col-full")) {
                                        $(this).addClass("wfacp-col-full");
                                        $(this).addClass("wfacp-form-control-wrapper");
                                        $(this).find("input").addClass("wfacp-form-control");
                                    }

                                }
                            });
                        }
                    }

                })(jQuery);
            });
        </script>
		<?php
	}
}

if ( ! class_exists( 'WC_IdealPostcodes' ) ) {
	return;
}

WFACP_Plugin_Compatibilities::register( new wfacp_uk_address_postcode_validation_by_ideal_postcodes(), 'ukapv' );
