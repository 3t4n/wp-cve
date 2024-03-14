<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WFFN_Compatibility_With_Thrive_Theme
 */
if ( ! class_exists( 'WFFN_Compatibility_With_Thrive_Theme' ) ) {
	class WFFN_Compatibility_With_Thrive_Theme {

		public function __construct() {

			add_action( 'tve_editor_print_footer_scripts', function () {

				?>
				<script type="text/javascript">
					document.addEventListener('DOMContentLoaded', (event) => {
						if (typeof TVE !== "undefined") {
							TVE.add_filter('tve.allowed.empty.posts.type', function (list) {
								list.push('wfacp_checkout');
								list.push('wfocu_offer');
								list.push('wffn_landing');
								list.push('wffn_optin');
								list.push('wffn_oty');
								list.push('wffn_ty');
								return list;
							});
						}
					});


				</script>
				<?php
			} );

            add_action( 'tcb_landing_page_template_redirect', [ $this, 'maybe_tve_template_redirect'] );
		}

		public function is_enable() {
			return false;
		}

        public function maybe_tve_template_redirect(){
            WFFN_Core()->public->maybe_initialize_setup();
            WFFN_Common::remove_actions( 'template_redirect', 'WFFN_Public', 'maybe_initialize_setup' );
        }


	}

	WFFN_Plugin_Compatibilities::register( new WFFN_Compatibility_With_Thrive_Theme(), 'thrive_theme' );
}