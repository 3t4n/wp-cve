<?php
/*
 * Plugin Name: Disable Elements for WPBakery Page Builder
 * Plugin URI: https://wordpress.org/plugins/disable-elements-for-wpbakery-page-builder/
 * Description: Adds a new page at WPBakery Page Builder > Disable Elements so you can disable elements not in use.
 * Author: WPExplorer
 * Author URI: https://www.wpexplorer.com/
 * Version: 1.1
 *
 * Text Domain: disable-elements-for-wpbakery-page-builder
 * Domain Path: /languages/
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Disable_Elements_For_WPBakery_Page_Builder' ) ) {

	final class Disable_Elements_For_WPBakery_Page_Builder {

		/**
		 * User permission that has access to the settings.
		 */
		protected $user_capability = 'edit_theme_options';

		/**
		 * Initlialize.
		 */
		public function __construct() {
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [ $this, 'add_settings_link' ] );
			add_action( 'init', [ $this, 'admin_init' ] );
			add_action( 'vc_after_init', [ $this, 'maybe_remove_elements' ] );
		}

		/**
		 * Add settings link to plugin dashboard screen.
		 */
		public function add_settings_link( array $links ): array {
			return array_merge( $links, array(
				'<a href="' . esc_url( admin_url( 'admin.php?page=vc-disable-elements' ) ) . '">' . esc_html__( 'Settings', 'disable-elements-for-wpbakery-page-builder' ) . '</a>',
			) );
		}

		/**
		 * Run on admin init.
		 */
		public function admin_init(): void {
			if ( class_exists( 'WPBMap' ) ) {
				add_action( 'admin_menu', [ $this, 'add_admin_submenu_page' ] );
			}
		}

		/**
		 * Returns the admin page parent slug.
		 */
		private function get_admin_parent_slug(): string {
			return defined( 'VC_PAGE_MAIN_SLUG' ) ? VC_PAGE_MAIN_SLUG : 'vc-general';
		}

		/**
		 * Add submenu page.
		 */
		public function add_admin_submenu_page(): void {
			add_submenu_page(
				$this->get_admin_parent_slug(),
				esc_html__( 'Disable Elements', 'disable-elements-for-wpbakery-page-builder' ),
				esc_html__( 'Disable Elements', 'disable-elements-for-wpbakery-page-builder' ),
				'administrator', // allow admin to decide what "edit_theme_options" roles can edit.
				'vc-disable-elements',
				[ $this, 'admin_page' ]
			);
		}

		/**
		 * Save the list of disabled elements.
		 */
		private function save_option( $value ): void {
			$excluded_elements = [];
			$elements = $this->get_all_elements();
			if ( is_array( $value ) ) {
				foreach ( $elements as $element_id => $element_settings ) {
					if ( ! array_key_exists( $element_id, $value ) ) {
						$excluded_elements[] = $element_id;
					}
				}
			}
			if ( ! $excluded_elements ) {
				delete_option( 'wpex_wpb_disabled_elements' );
			} else {
				update_option( 'wpex_wpb_disabled_elements', $excluded_elements, false );
			}
		}

		/**
		 * Display Admin page.
		 */
		public function admin_page(): void {
			if ( ! current_user_can( $this->user_capability ) ) {
				return;
			}

			$elements = $this->get_all_elements();

			if ( ! $elements ) {
				return;
			}

			if ( array_key_exists( 'wpex_wpb_disabled_elements_nonce_field', $_POST )
				&& wp_verify_nonce( $_POST['wpex_wpb_disabled_elements_nonce_field'], 'wpex_wpb_disabled_elements_nonce_action' )
				&& array_key_exists( 'wpex_wpb_disabled_elements', $_POST )
			) {
				$this->save_option( $_POST['wpex_wpb_disabled_elements'] );
			}

			if ( defined( 'WPB_VC_VERSION' ) && function_exists( 'vc_asset_url' ) ) {
				wp_enqueue_style(
					'js_composer_settings',
					vc_asset_url( 'css/js_composer_settings.min.css' ),
					false,
					WPB_VC_VERSION
				);
			}

			$disabled_elements = self::get_disabled_elements();
			?>

			<div class="wrap vc_settings">

				<h1 style="margin-bottom:15px;"><?php esc_html_e( 'Disable WPBakery Elements', 'disable-elements-for-wpbakery-page-builder' ); ?></h1>

				<form method="post">
					<div class="vc_ui-settings-roles-role">
						<table class="wp-list-table widefat fixed striped table-view-list">
							<thead>
								<tr>
									<th><?php esc_html_e( 'Element', 'disable-elements-for-wpbakery-page-builder' ); ?></th>
									<th><?php esc_html_e( 'Enabled', 'disable-elements-for-wpbakery-page-builder' ); ?></th>
								</tr>
							</thead>
							<?php
							foreach ( $elements as $key => $value) {
								$name = $value['name'] ?? null;
								$icon = $value['icon'] ?? null;
								if ( ! $name ) {
									continue;
								}
								if ( in_array( $key, $disabled_elements ) ) {
									$enabled = false;
								} else {
									$enabled = true;
								}
								?>
							<tr<?php echo $enabled ? '' : ' style="opacity:.5"'; ?>>
								<td class="vc_row" style="width:auto;">
									<label for="wpex_wpb_disabled_elements[<?php echo esc_attr( $key ); ?>]">
										<i class="vc_general vc_element-icon <?php echo esc_attr( $icon ); ?>"></i>
										<div>
											<?php echo esc_html( $name ); ?>
											<span class="vc_element-description"><?php echo esc_html( $value['description'] ?? '' ); ?></span>
										</div>
									</label>
								</th>
								<td>
									<input type="checkbox" id="wpex_wpb_disabled_elements[<?php echo esc_attr( $key ); ?>]" name="wpex_wpb_disabled_elements[<?php echo esc_attr( $key ); ?>]"<?php checked( $enabled, true, true ); ?>>
								</td>
							</tr>
							<?php } ?>
						</table>
					</div>

					<?php wp_nonce_field( 'wpex_wpb_disabled_elements_nonce_action', 'wpex_wpb_disabled_elements_nonce_field' ); ?>

					<?php submit_button(); ?>

				</form>

			</div>

			<?php
		}

		/**
		 * Returns an array of all WPBakery builder elements.
		 */
		private function get_all_elements(): array {
			$elements = [];
			if ( is_callable( array( 'WPBMap', 'getAllShortCodes' ) ) ) {
				$elements = WPBMap::getAllShortCodes();
				if ( $elements && is_array( $elements ) ) {
					foreach ( $this->get_required_elements() as $element ) {
						unset( $elements[ $element ] );
					}
				}
			}
			return $elements;
		}

		/**
		 * Returns an array of all WPBakery builder elements.
		 */
		private function get_required_elements(): array {
			return [
				'vc_row',
				'vc_row_inner',
				'vc_column',
				'vc_column_inner',
				'vc_section',
			];
		}

		/**
		 * Returns an array of all disabled WPBakery builder elements.
		 */
		public static function get_disabled_elements(): array {
			return (array) get_option( 'wpex_wpb_disabled_elements', [] );
		}

		/**
		 * Check if elements can be removed.
		 */
		private function elements_can_be_removed(): bool {
			return ( ! is_admin() || ( isset( $_GET['post'] ) || isset( $_GET['post_type'] ) ) || ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) );
		}

		/**
		 * Remove elements.
		 */
		public function maybe_remove_elements(): void {
			if ( $this->elements_can_be_removed() ) {
				foreach ( self::get_disabled_elements() as $element ) {
					if ( ! in_array( $element, $this->get_required_elements() ) ) {
						vc_remove_element( $element );
					}
				}
			}
		}

	}

	new Disable_Elements_For_WPBakery_Page_Builder;

}