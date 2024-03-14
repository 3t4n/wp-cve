<?php
if ( ! class_exists( 'HtMegaBlocks' ) ) :

	define( 'HTMEGA_BLOCK_FILE', __FILE__ );
	define( 'HTMEGA_BLOCK_PATH', __DIR__ );
	define( 'HTMEGA_BLOCK_URL', plugins_url( '/', HTMEGA_BLOCK_FILE ) );
	define( 'HTMEGA_BLOCK_DIR', plugin_dir_path( HTMEGA_BLOCK_FILE ) );
	define( 'HTMEGA_BLOCK_ASSETS', HTMEGA_BLOCK_URL . '/assets' );
	define( 'HTMEGA_BLOCK_TEMPLATE', trailingslashit( HTMEGA_BLOCK_DIR . 'includes/templates' ) );

	/**
	 * Main HtMegaBlocks Class
	 */
	final class HtMegaBlocks{

		/**
		 * [$_instance]
		 * @var null
		 */
		private static $_instance = null;

		/**
		 * [instance] Initializes a singleton instance
		 * @return [Actions]
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * The Constructor.
		 */
		public function __construct() {
			$this->includes();
			add_action( 'plugins_loaded', [ $this, 'init' ] );
		}

		/**
		 * Initialize
		 */
		public function init(){
			$this->dependency_class_instance();
		}

		/**
		 * Load required file
		 *
		 * @return void
		 */
		private function includes() {
			include( HTMEGA_BLOCK_PATH . '/vendor/autoload.php' );
		}

		/**
		 * Load dependency class
		 *
		 * @return void
		 */
		private function dependency_class_instance() {
			HtMegaBlocks\Scripts::instance();
			HtMegaBlocks\Manage_Styles::instance();
			HtMegaBlocks\Actions::instance();
			HtMegaBlocks\Blocks_init::instance();
		}


	}
	
endif;

/**
 * The main function for that returns htmegablocks
 *
 */
function htmegablocks() {
	if ( ! empty( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] ) {
		return;
	}elseif( class_exists( 'Classic_Editor' ) ){
		return;
	}else{
		return HtMegaBlocks::instance();
	}
}
htmegablocks();
