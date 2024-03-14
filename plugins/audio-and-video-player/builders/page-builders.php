<?php
/**
 * Main class to interace with the different Content Editors: AVP_PAGE_BUILDERS class
 */
if ( ! class_exists( 'AVP_PAGE_BUILDERS' ) ) {
	class AVP_PAGE_BUILDERS {

		private function __construct(){}
		public static function init() {
			 $instance = new self();
			add_action( 'enqueue_block_editor_assets', array( $instance, 'gutenberg_editor' ) );
			add_action( 'elementor/widgets/register', array( $instance, 'elementor_editor' ) );
			add_action( 'elementor/elements/categories_registered', array( $instance, 'elementor_editor_category' ) );
			add_action( 'elementor/controls/register', array( $instance, 'elementor_register_control' ) );
		}

		/**************************** GUTENBERG ****************************/

		/**
		 * Loads the javascript resources to integrate the plugin with the Gutenberg editor
		 */
		public function gutenberg_editor() {
			wp_enqueue_style( 'cpmp-gutenberg-editor-style', CPMP_PLUGIN_URL . '/css/gutenberg.css', array(), CPMP_VERSION );
			wp_enqueue_script( 'cpmp-gutenberg-editor', CPMP_PLUGIN_URL . '/js/gutenberg.js', array( 'jquery' ), CPMP_VERSION );
			$url  = get_home_url( get_current_blog_id(), '', is_ssl() ? 'https' : 'http' );
			$url .= ( ( strpos( $url, '?' ) === false ) ? '?' : '&' ) . 'cpmp-avp-preview=';
			wp_localize_script( 'cpmp-gutenberg-editor', 'cpmp_gutenberg_editor_config', array( 'url' => $url ) );
		} // End gutenberg_editor

		/**************************** ELEMENTOR ****************************/

		public function elementor_editor_category() {
			require_once dirname( __FILE__ ) . '/elementor-category.pb.php';
		} // End elementor_editor

		public function elementor_editor() {
			if ( is_admin() ) {
				wp_enqueue_style( 'cpm_admin', CPMP_PLUGIN_URL . '/css/cpmp_admin.css', array(), CPMP_VERSION );
			}
			require_once dirname( __FILE__ ) . '/elementor.pb.php';
		} // End elementor_editor

		public function elementor_register_control() {
			require_once dirname( __FILE__ ) . '/elementor-control.pb.php';
		} // End elementor_register_control

	} // End AVP_PAGE_BUILDERS
}
