<?php
/**
 * Class to create the LearnDash Powerpack Settings page
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'LearnDash_PowerPack_Setting_Page', false ) ) {
	/**
	 * Class LearnDash Setting Page
	 *
	 * @since 1.0.0
	 */
	class LearnDash_PowerPack_Setting_Page {
		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_menu', [ $this, 'ld_learndash_powerpack_admin_menu' ] );
		}

		/**
		 * Add LearnDash Powerpack admin menu
		 *
		 * @since 1.0.0
		 */
		public function ld_learndash_powerpack_admin_menu() {
			add_submenu_page(
				'learndash-lms',
				__( 'PowerPack', 'learndash-powerpack' ),
				__( 'PowerPack', 'learndash-powerpack' ),
				'manage_options',
				'learndash-powerpack',
				[ $this, 'settings' ]
			);
		}

		/**
		 * Display LearDash Powerpack settings
		 *
		 * @since 1.0.0
		 */
		public function settings() {
			$get_all_powerpack_classes = LearnDash_PowerPack_All_Classes::get_all_powerpack_classes();
			?>
			<div class="ld-head-panel">
				<h1>
					<?php esc_html_e( 'LearnDash PowerPack', 'learndash-powerpack' ); ?>
				</h1>
				<div id="ld-powerpack-tabs" class="ld-tab-buttons">
					<a href="#" class="button active" data-target-content="ld-powerpack-tab-standard">
						<?php esc_html_e( 'Standard', 'learndash-powerpack' ); ?>
					</a>
					<a href="#" class="button" data-target-content="ld-powerpack-tab-premium">
						<?php esc_html_e( 'Premium', 'learndash-powerpack' ); ?>
					</a>
				</div>
			</div>

			<div class="wrap">
				<h1 class="wp-heading-inline"></h1>
				<?php wp_nonce_field( 'learndash-powerpack-settings-nonce-' . get_current_user_id(), 'learndash-powerpack-settings-nonce' ); ?>
				<div id="ld-powerpack-tab-standard" class="ld-powerpack-tab">
					<?php echo LearnDash_PowerPack_Build_Setting_Page_Html::settings_select_option(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML ?>

					<div id="learndash_snippet_list"
						class="imm-grid imm-grid-cols-1 lg:imm-grid-cols-2 xl:imm-grid-cols-3 imm-gap-5">
						<?php if ( is_array( $get_all_powerpack_classes ) ) : ?>
							<?php foreach ( $get_all_powerpack_classes as $key ) : ?>
								<?php echo LearnDash_PowerPack_Build_Setting_Page_Html::settings_page_html( $key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Echoes HTML ?>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>

				<div id="ld-powerpack-tab-premium" class="ld-powerpack-tab" style="display: none">
					<?php
					global $wp_filter;

					if ( isset( $wp_filter['ld_powerpack_premium_settings'] ) ) {
						if ( ! defined( 'LEARNDASH_POWERPACK_PREMIUM_VERSION' ) ) {
							$wp_filter['ld_powerpack_premium_settings'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
						} else {
							$wp_filter['ld_powerpack_premium_settings']->callbacks = array_filter(
								$wp_filter['ld_powerpack_premium_settings']->callbacks,
								function ( $callback ) {
									$callback_data = end( $callback );

									return is_a( $callback_data['function'][0], Learndash_Powerpack_Premium_Admin::class );
								}
							);
						}
					}
					?>
					<?php esc_html_e( 'Coming Soon..', 'learndash-powerpack' ); ?>
				</div>
			</div>
			<?php
		}
	}

	new LearnDash_PowerPack_Setting_Page();
}
