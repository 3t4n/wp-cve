<?php

#[AllowDynamicProperties] 

  class WFACP_ShipMondo_PakkeLabels_Compatibility {
	public function __construct() {
		add_filter( 'wfacp_print_shipping_hidden_fields', [ $this, 'do_not_print_hidden_shipping' ] );
		add_action( 'wfacp_internal_css', [ $this, 'print_js' ] );
	}

	public function do_not_print_hidden_shipping( $status ) {
		$status = false;

		return $status;
	}

	public function print_js() {
		?>
        <script>
            window.addEventListener('load', function () {
                (function ($) {
                    /**
                     * Pakkable Ship Mondo Plugoni
                     */
                    function shipMondo() {
                        if ($('.shipmondo-shipping-field-wrap').length == 0) {
                            return;
                        }
                        var methods = $('.wfacp_single_shipping_method');
                        methods.each(function () {
                            var field = $(this).find('.shipmondo-shipping-field-wrap');
                            if (field.length > 0) {
                                var radio = $(this).find('.wfacp_shipping_radio');
                                var html = $(this).children('.wfacp_single_shipping_desc').html();
                                $(this).children('.wfacp_single_shipping_desc').html('');
                                radio.append(html);
                            }
                        });
                    }

                    $(document.body).on('updated_checkout', function (e, v) {
                        $('.wfacp_shipping_calculator').unblock();
                        shipMondo();
                    });
                })(jQuery)
            });
        </script>
		<?php

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_ShipMondo_PakkeLabels_Compatibility(), 'shipmondo_pakkelabels' );
