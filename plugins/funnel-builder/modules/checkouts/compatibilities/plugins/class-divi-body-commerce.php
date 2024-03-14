<?php
/*
 * Compatability added with plugin Divi BodyCommerce by Divi Engine upto v.6.5.2.1
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


#[AllowDynamicProperties] 

  class WFACP_Compatibility_With_Divi_BodyCommerce {
	public function __construct() {
		add_filter( 'wfacp_after_form', [ $this, 'action' ], 50 );
		add_filter( 'wfacp_internal_css', [ $this, 'add_js' ], 50 );
	}

	public function action() {
		wp_enqueue_script( 'wc-add-to-cart' );
	}

	public function add_js() {
		?>

        <script>
            window.addEventListener('bwf_checkout_load', function () {
                (function ($) {

                    $(document.body).on('removed_from_cart', function (e, v) {

                        $(document.body).trigger('update_checkout');
                    });


                })(jQuery);
            });
        </script>
		<?php

	}
}

WFACP_Plugin_Compatibilities::register( new WFACP_Compatibility_With_Divi_BodyCommerce(), 'wfacp-divi-body-commerce' );
