<?php
/**
 * YITH Proteo Toolkit Modules class
 *
 * @package YITH_Proteo_tookit
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'YITH_Proteo_Toolkit_Modules' ) ) {
	/**
	 * Proteo Toolkit Testimonials class.
	 *
	 * @access private
	 */
	class YITH_Proteo_Toolkit_Modules {
		/**
		 * Constructor
		 */
		public function __construct() {
			require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/class-yith-proteo-toolkit-wizard.php';

			if ( $this->is_module_enabled( 'yith-proteo-toolkit-block-patterns' ) ) {
				require_once YITH_PROTEO_TOOLKIT_PATH . 'block-patterns/block-patterns.php';
			}

			if ( false && $this->is_module_enabled( 'yith-proteo-toolkit-testimonial' ) ) {
				require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/testimonials-module/module.php';
			}

			add_action( 'yith_proteo_dashboard_additional_sidebar_content', array( $this, 'modules_sidebar_panel' ) );

			add_action( 'admin_print_scripts', array( $this, 'add_admin_scripts' ) );

			// enable modules AJAX.
			add_action( 'wp_ajax_yith_proteo_toolkit_module_save', array( $this, 'save_module_ajax' ) );
		}

		/**
		 * Add admin scripts for plugin panels
		 *
		 * @return void
		 */
		public function add_admin_scripts() {
			wp_enqueue_script( 'yith_toolkit_admin_js', YITH_PROTEO_TOOLKIT_URL . 'assets/js/modules-admin.js', array( 'jquery' ), YITH_PROTEO_TOOLKIT_VERSION, true );

			$localize = array(
				'proteoToolkitModulesNonce' => wp_create_nonce( 'yith-proteo-toolkit-modules-nonce' ),
			);

			wp_localize_script( 'yith_toolkit_admin_js', 'yith_proteo_toolkit', $localize );
		}

		/**
		 * Save Toolkit modules state.
		 *
		 * @return void
		 */
		public function save_module_ajax() {
			$nonce = ( isset( $_REQUEST['nonce'] ) ) ? sanitize_key( $_REQUEST['nonce'] ) : '';

			if ( false === wp_verify_nonce( $nonce, 'yith-proteo-toolkit-modules-nonce' ) ) {
				wp_send_json_error( esc_html_e( 'WordPress Nonce not validated.', 'yith-proteo' ) );
			}

			if ( ! isset( $_REQUEST['action'] ) || 'yith_proteo_toolkit_module_save' !== $_REQUEST['action'] || ! isset( $_REQUEST['id'] ) ) {
				die();
			}

			$id                    = sanitize_text_field( wp_unslash( $_REQUEST['id'] ) );
			$modules_active        = get_option( 'yith_proteo_toolkit_modules_active', array() );
			$modules_active[ $id ] = isset( $modules_active[ $id ] ) ? ! $modules_active[ $id ] : false;

			$success = update_option( 'yith_proteo_toolkit_modules_active', $modules_active );
			wp_send_json(
				array(
					'success' => $success,
				)
			);
		}

		/**
		 * Module sidebar template
		 *
		 * @return void
		 */
		public function modules_sidebar_panel() {
			$modules_active                   = get_option( 'yith_proteo_toolkit_modules_active', array() );
			$is_testimonial_module_enabled    = $this->is_module_enabled( 'yith-proteo-toolkit-testimonial' );
			$is_block_patterns_module_enabled = $this->is_module_enabled( 'yith-proteo-toolkit-block-patterns' );
			?>
			<div class="content">
				<h3>
					<?php esc_html_e( 'Proteo toolkit modules', 'yith-proteo-toolkit' ); ?>
				</h3>
				<ul id="yith-proteo-toolkit-modules">
					<?php
					/*
					<li>
						<span class="module-name">- <?php echo esc_html_x( 'Testimonials', 'Proteo Toolkit module name.', 'yith-proteo' ); ?></span>
						<span class="form-switch <?php echo $is_testimonial_module_enabled ? 'enabled' : ''; ?>"
							data-option_id="yith-proteo-toolkit-testimonial">
						</span>
					</li>
					*/
					?>
					<li>
						<span class="module-name">- <?php echo esc_html_x( 'Block patterns', 'Proteo Toolkit module name.', 'yith-proteo' ); ?></span>
						<span class="form-switch  <?php echo $is_block_patterns_module_enabled ? 'enabled' : ''; ?>"
							data-option_id="yith-proteo-toolkit-block-patterns">
						</span>
					</li>
				</ul>
			</div>
			<?php
		}

		/**
		 * Check module state
		 *
		 * @param string $module Module identifier.
		 * @return boolean
		 */
		public function is_module_enabled( $module ) {
			$saved_modules = get_option( 'yith_proteo_toolkit_modules_active', array() );
			$module        = sanitize_title( $module );
			$state         = isset( $saved_modules[ $module ] ) ? $saved_modules[ $module ] : true;
			return $state;
		}
	}

}
new YITH_Proteo_Toolkit_Modules();
