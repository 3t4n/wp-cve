<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package Canvas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'CNVS' ) ) {
	/**
	 * Main Core Class
	 */
	class CNVS {
		/**
		 * The plugin version number.
		 *
		 * @var string $data The plugin version number.
		 */
		public $version;

		/**
		 * The plugin data array.
		 *
		 * @var array $data The plugin data array.
		 */
		public $data = array();

		/**
		 * The plugin settings array.
		 *
		 * @var array $settings The plugin data array.
		 */
		public $settings = array();

		/**
		 * INIT (global theme queue)
		 */
		public function init() {

			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			// Get plugin data.
			$plugin_data = get_plugin_data( CNVS_PATH . '/canvas.php' );

			// Vars.
			$this->version = $plugin_data['Version'];

			// Settings.
			$this->settings = array(
				'name'          => esc_html__( 'Canvas', 'canvas' ),
				'version'       => $plugin_data['Version'],
				'documentation' => $plugin_data['AuthorURI'] . '/documentation/canvas',
			);

			// Constants.
			$this->define( 'CANVAS', true );
			$this->define( 'CANVAS_VERSION', $plugin_data['Version'] );

			// Include core.
			require_once CNVS_PATH . 'core/core-canvas-api.php';
			require_once CNVS_PATH . 'core/core-canvas-helpers.php';
			require_once CNVS_PATH . 'core/core-canvas-filters.php';
			require_once CNVS_PATH . 'core/core-canvas-post-meta.php';

			// Include core classes.
			require_once CNVS_PATH . 'core/class-canvas-page-templates.php';
			require_once CNVS_PATH . 'core/class-canvas-gutenberg.php';
			require_once CNVS_PATH . 'core/class-canvas-layouts.php';

			// Gutenberg blocks.
			if ( function_exists( 'register_block_type' ) ) {
				require_once CNVS_PATH . '/components/content-formatting/class-block-heading.php';
				require_once CNVS_PATH . '/components/content-formatting/class-block-list.php';
				require_once CNVS_PATH . '/components/content-formatting/class-block-paragraph.php';
				require_once CNVS_PATH . '/components/content-formatting/class-block-separator.php';
				require_once CNVS_PATH . '/components/content-formatting/class-format-badge.php';
				require_once CNVS_PATH . '/components/basic-elements/class-block-group.php';
				require_once CNVS_PATH . '/components/basic-elements/class-block-cover.php';
				require_once CNVS_PATH . '/components/basic-elements/class-block-alert.php';
				require_once CNVS_PATH . '/components/basic-elements/class-block-progress.php';
				require_once CNVS_PATH . '/components/basic-elements/class-block-collapsibles.php';
				require_once CNVS_PATH . '/components/basic-elements/class-block-tabs.php';
				require_once CNVS_PATH . '/components/basic-elements/class-block-widgetized-area.php';
				require_once CNVS_PATH . '/components/basic-elements/class-block-section-heading.php';
				require_once CNVS_PATH . '/components/layout-blocks/class-block-section.php';
				require_once CNVS_PATH . '/components/layout-blocks/class-block-row.php';
				require_once CNVS_PATH . '/components/posts/class-block-posts.php';
				require_once CNVS_PATH . '/components/posts/class-block-posts-sidebar.php';
				require_once CNVS_PATH . '/components/justified-gallery/class-block-justified-gallery.php';
				require_once CNVS_PATH . '/components/slider-gallery/class-block-slider-gallery.php';
			}

			// Actions.
			add_action( 'canvas_plugin_activation', array( $this, 'activation' ) );
			add_action( 'plugins_loaded', array( $this, 'check_version' ) );
			add_action( 'amp_post_template_css', array( $this, 'amp_enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 5 );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 5 );
			add_action( 'after_setup_theme', array( $this, 'after_setup_theme' ) );
			add_action( 'wp_head', array( $this, 'wp_head' ), 5 );
		}

		/**
		 * This function will safely define a constant
		 *
		 * @param string $name  The name.
		 * @param mixed  $value The value.
		 */
		public function define( $name, $value = true ) {

			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
		}

		/**
		 * Returns true if has setting.
		 *
		 * @param string $name The name.
		 */
		public function has_setting( $name ) {
			return isset( $this->settings[ $name ] );
		}

		/**
		 * Returns a setting.
		 *
		 * @param string $name The name.
		 */
		public function get_setting( $name ) {
			return isset( $this->settings[ $name ] ) ? $this->settings[ $name ] : null;
		}

		/**
		 * Updates a setting.
		 *
		 * @param string $name  The name.
		 * @param mixed  $value The value.
		 */
		public function update_setting( $name, $value ) {
			$this->settings[ $name ] = $value;
			return true;
		}

		/**
		 * Returns data.
		 *
		 * @param string $name The name.
		 */
		public function get_data( $name ) {
			return isset( $this->data[ $name ] ) ? $this->data[ $name ] : null;
		}

		/**
		 * Sets data.
		 *
		 * @param string $name  The name.
		 * @param mixed  $value The value.
		 */
		public function set_data( $name, $value ) {
			$this->data[ $name ] = $value;
		}

		/**
		 * Hook activation
		 */
		public function activation() {
			if ( get_option( 'canvas_db_version' ) ) {
				return;
			}

			update_option( 'canvas_db_version', cnvs_raw_setting( 'version' ), true );
		}

		/**
		 * Check current version
		 */
		public function check_version() {

			// Version Data.
			$new = cnvs_raw_setting( 'version' );

			// Get db version.
			$current = get_option( 'canvas_db_version', $new );

			// If versions don't match.
			if ( $current && $current !== $new ) {
				/**
				 * If different versions call a special hook.
				 *
				 * @param string $current Current version.
				 * @param string $new     New version.
				 */
				do_action( 'canvas_plugin_upgrade', $current, $new );

				update_option( 'canvas_db_version', $new, true );
			}

			if ( $current ) {
				update_option( 'canvas_db_version', $new, true );
			}
		}

		/**
		 * AMP stylesheets.
		 */
		public function amp_enqueue_styles() {
			echo file_get_contents( CNVS_PATH . 'assets/css/amp.css' ); // XSS.
		}

		/**
		 * This function will register scripts and styles for admin dashboard.
		 *
		 * @param string $page Current page.
		 */
		public function admin_enqueue_scripts( $page ) {
			wp_enqueue_style( 'canvas', CNVS_URL . 'assets/css/canvas.css', array(), cnvs_get_setting( 'version' ) );

			$default_slug = apply_filters( 'canvas_scheme_default_slug', 'default' );
			$dark_slug    = apply_filters( 'canvas_scheme_dark_slug', 'dark' );

			wp_localize_script( 'jquery-ui-core', 'canvasLocalize', array(
				'postType'          => get_post_type(),
				'ajaxURL'           => admin_url( 'admin-ajax.php' ),
				'schemeDefaultSlug' => 'default' === $default_slug ? '' : $default_slug,
				'schemeDarkSlug'    => 'default' === $dark_slug ? '' : $dark_slug,
			) );
		}

		/**
		 * This function will register scripts and styles
		 */
		public function wp_enqueue_scripts() {
			// Styles.
			wp_enqueue_style( 'canvas', cnvs_style( CNVS_URL . 'assets/css/canvas.css' ), array(), cnvs_get_setting( 'version' ) );

			// Add RTL support.
			wp_style_add_data( 'canvas', 'rtl', 'replace' );
		}
		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 */
		public function after_setup_theme() {
			// Register custom thumbnail sizes.
			add_image_size( 'cnvs-small', 80, 80, true );
			add_image_size( 'cnvs-thumbnail', 300, 225, true );

			// Add editor style.
			if ( is_rtl() ) {
				add_editor_style( cnvs_style( CNVS_URL . 'assets/css/canvas-rtl.css' ) );
			} else {
				add_editor_style( cnvs_style( CNVS_URL . 'assets/css/canvas.css' ) );
			}
		}

		/**
		 * Fire the wp_head action.
		 */
		public function wp_head() {
			?>
			<link rel="preload" href="<?php echo esc_url( CNVS_URL . 'assets/fonts/canvas-icons.woff' ); ?>" as="font" type="font/woff" crossorigin>
			<?php
		}

	}

	/**
	 * The main function responsible for returning the one true cnvs Instance to functions everywhere.
	 * Use this function like you would a global variable, except without needing to declare the global.
	 *
	 * Example: <?php $cnvs = cnvs(); ?>
	 */
	function cnvs() {

		// Globals.
		global $cnvs_instance;

		// Init.
		if ( ! isset( $cnvs_instance ) ) {
			$cnvs_instance = new CNVS();
			$cnvs_instance->init();
		}

		return $cnvs_instance;
	}

	// Initialize.
	cnvs();
}
