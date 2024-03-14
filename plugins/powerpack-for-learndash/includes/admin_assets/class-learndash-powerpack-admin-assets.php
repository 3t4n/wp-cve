<?php
/**
 * Load assets
 *
 * @version 1.0.0
 * @package LearnDash PowerPack
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'LearnDash_PowerPack_Admin_Assets', false ) ) :

	/**
	 * LearnDash_PowerPack_Admin_Assets Class.
	 */
	class LearnDash_PowerPack_Admin_Assets {
		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', [ $this, 'admin_scripts' ] );
			add_action( 'admin_footer', [ $this, 'admin_footer_func' ] );
		}

		/**
		 * Holds script file name suffix.
		 *
		 * @var string suffix
		 */
		private $suffix = '.min';

		/**
		 * Inject modal html.
		 */
		public function admin_footer_func() {
			if ( ! $this->is_powerpack_admin_page() ) {
				return;
			}
			?>
			<!-- The Modal -->
			<div id="learndash-powerpack-modal" class="modal">
			<?php wp_nonce_field( 'learndash-powerpack-modal-nonce-' . get_current_user_id(), 'learndash-powerpack-modal-nonce' ); ?>
				<!-- Modal content -->
				<div class="learndash-powerpack-modal-content">
					<form action="" name="learndash_save_class_data" class="form_learndash_save_class_data">
						<div class="imm-bg-gray-200 imm-p-5 imm-rounded-t">
							<h2 class="model_data_title imm-my-0 imm-font-semibold imm-text-lg imm-pr-10"></h2>
							<span class="learndash-powerpack-close imm-absolute imm-top-6 imm-right-5">&times;</span>
						</div>
						<div class="learndash-powerpack-modal-body imm-bg-white imm-py-10 imm-px-6 imm-font-semibold imm-leading-8"></div>
						<div class="learndash_success_message"></div>
						<div class="learndash_error_message"></div>
						<div class="learndash-powerpack-modal-footer imm-bg-gray-200 imm-p-6 imm-rounded-b"></div>
					</form>
				</div>
			</div>
			<?php
		}

		/**
		 * Enqueue admin scripts.
		 */
		public function admin_scripts() {
			if ( ! $this->is_powerpack_admin_page() ) {
				return;
			}

			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				$this->suffix = '';
			}

			// Register scripts.
			wp_enqueue_style( 'learndash-style', LD_POWERPACK_PLUGIN_URL . "/assets/css/admin/learndash-style{$this->suffix}.css", [], time(), 'all' );
			wp_enqueue_style( 'imm-tailwind', LD_POWERPACK_PLUGIN_URL . '/assets/css/admin/imm-tailwind.min.css', [], time(), 'all' );

			wp_enqueue_script( 'learndash-powerpack-custom-jquery-func', LD_POWERPACK_PLUGIN_URL . "/assets/js/admin/learndash-powerpack-custom-jquery-func{$this->suffix}.js", [ 'jquery' ], time(), true );
			wp_localize_script(
				'learndash-powerpack-custom-jquery-func',
				'learndash_powerpack_jquery_var',
				[
					'ajax_url' => admin_url( 'admin-ajax.php' ),
				]
			);
		}

		/**
		 * Checks if current_screen is the LearnDash Powerpack admin page
		 *
		 * @return boolean
		 */
		public function is_powerpack_admin_page() {
			$screen    = get_current_screen();
			$screen_id = $screen ? $screen->id : '';

			return preg_match( '/learndash-powerpack/i', $screen_id );
		}
	}

endif;

return new LearnDash_PowerPack_Admin_Assets();
