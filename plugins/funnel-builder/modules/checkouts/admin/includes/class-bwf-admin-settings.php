<?php
/**
 * Class to control Settings and its behaviour accross the buildwoofunnels
 * @author buildwoofunnels
 */
if ( ! class_exists( 'BWF_Admin_Settings' ) ) {

	class BWF_Admin_Settings {

		private static $ins = null;

		public function __construct() {
			add_action( 'admin_menu', [ $this, 'maybe_register_admin_menu' ], 900 );
			add_action( 'admin_init', array( $this, 'maybe_open_correct_settings' ), - 1 );
		}

		public static function get_instance() {

			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function maybe_register_admin_menu() {

			global $submenu;
			if ( isset( $submenu['woofunnels'] ) ) {
				foreach ( $submenu['woofunnels'] as $menu ) {
					if ( 'woofunnels_settings' === $menu[2] ) {
						$found = 1;
						break;
					}
				}
			}

			$user = WFACP_Core()->role->user_access( 'menu', 'read' );
			if ( empty( $found ) && false !== $user  ) {
				add_submenu_page( 'woofunnels', __( 'Settings', 'woofunnels' ), __( 'Settings', 'woofunnels' ), $user, 'woofunnels_settings', [ $this, '_callback' ] );
			}
		}

		public function _callback() {

		}

		public function maybe_open_correct_settings() {

			if ( is_admin() && 'woofunnels_settings' === filter_input( INPUT_GET, 'page', FILTER_UNSAFE_RAW ) ) {
				$get_all_registered_settings = apply_filters( 'woofunnels_global_settings', [] );
				usort( $get_all_registered_settings, function ( $a, $b ) {
					if ( $a['priority'] === $b['priority'] ) {
						return 0;
					}

					return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;
				} );
				$first_menu = array_values( $get_all_registered_settings )[0];
				wp_redirect( $first_menu['link'] );
			}


		}

		public function render_tab_html( $current ) {
			$get_all_registered_settings = apply_filters( 'woofunnels_global_settings', [] );

			if ( is_array( $get_all_registered_settings ) && count( $get_all_registered_settings ) > 0 ) {
				usort( $get_all_registered_settings, function ( $a, $b ) {
					if ( $a['priority'] === $b['priority'] ) {
						return 0;
					}

					return ( $a['priority'] < $b['priority'] ) ? - 1 : 1;
				} );

				?>

                <div class="bwf_menu_list_primary">
                    <ul>

						<?php
						foreach ( $get_all_registered_settings as $menu ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
							$class = '';
							if ( $menu['slug'] === $current ) {
								$class = 'active';
							}
							?>
                        <li class="<?php echo $class ?>">
                            <a href="<?php echo esc_url_raw( $menu['link'] ) ?>">
								<?php echo esc_attr( $menu['title'] ); ?>
                            </a>
                            </li><?php

						}
						?>
                    </ul>
                </div>
				<?php
			}
		}


	}
}
BWF_Admin_Settings::get_instance();