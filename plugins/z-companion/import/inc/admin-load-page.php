<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Zita Site Library
 *
 */

if ( ! class_exists( 'Z_Companion_Sites_Load' ) ) :

	class Z_Companion_Sites_Load {

		public static $api_url;

		private static $_instance = null;

		public static function get_instance() {
			if ( ! isset( self::$_instance ) ) {
				self::$_instance = new self;
			}

			return self::$_instance;
		}

		/**
		 * Constructor.
		 *
		 */
		private function __construct() {

			//self::set_api_url();

			$this->includes();

			add_action( 'admin_notices', array( $this, 'add_notice' ), 1 );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		}

		/**
		 * Add Admin Notice.
		 */
		function add_notice() {

			Z_Companion_Sites_Notices::add_notice(
				array(
					'id'               => 'zita-theme-activation',
					'type'             => 'error',
					'show_if'          => ( ! defined( 'ROYAL_SHOP_THEME_SETTINGS' ) ) ? true : false,
					/* translators: 1: theme.php file*/
					'message'          => sprintf( __( 'Royal Shop Theme needs to be active for you to use currently installed "%1$s" plugin. <a href="%2$s">Install & Activate Now</a>', 'z-companion-sites' ), Z_COMPANION_SITES_NAME, esc_url( admin_url( 'themes.php?theme=royal-shop' ) ) ),
					'dismissible'      => true,
					'dismissible-time' => WEEK_IN_SECONDS,
				)
			);

		}

		/**
		 * Loads textdomain for the plugin.
		 *
		 * @since 1.0.1
		 */
		function load_textdomain() {
			load_plugin_textdomain( 'zita-templates' );
		}

		/**
		 * Admin Notices
		 *
		 * @since 1.0.5
		 * @return void
		 */
		function admin_notices() {

			if ( ! defined( 'ROYAL_SHOP_THEME_SETTINGS' ) ) {
				return;
			}

			add_action( 'plugin_action_links_' . Z_COMPANION_SITES_BASE, array( $this, 'action_links' ) );
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @param   mixed $links Plugin Action links.
		 * @return  array
		 */
		function action_links( $links ) {
			$action_links = array(
				'settings' => '<a href="' . admin_url( 'themes.php?page='.Z_COMPANION_SLUG ) . '" aria-label="' . esc_attr__( 'See Library', 'z-companion-sites' ) . '">' . esc_html__( 'See Library', 'z-companion-sites' ) . '</a>',
			);

			return array_merge( $action_links, $links );
		}

		/**
		 * Enqueue admin scripts.
		 *
		 * @since  1.0.5    Added 'getUpgradeText' and 'getUpgradeURL' localize variables.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $hook Current hook name.
		 * @return void
		 */
		public function admin_enqueue( $hook = '' ) {

			if ( 'appearance_page_'.Z_COMPANION_SLUG !== $hook ) {
				return;
			}

			// Admin Page.
			wp_enqueue_style( 'z-companion-sites-admin', Z_COMPANION_SITES_URI . 'assets/css/admin.css', Z_COMPANION_SITES_VER, true );
			wp_enqueue_style( 'z-companion-sites-drop-down', Z_COMPANION_SITES_URI . 'assets/css/drop-down.css', Z_COMPANION_SITES_VER, true );

			wp_enqueue_script( 'classie', Z_COMPANION_SITES_URI . 'assets/js/classie.js', array( 'jquery'), Z_COMPANION_SITES_VER, true );

			wp_enqueue_script( 'selectFx', Z_COMPANION_SITES_URI . 'assets/js/selectFx.js', array( 'jquery'), Z_COMPANION_SITES_VER, true );

			wp_enqueue_script( 'z-companion-sites-admin-load', Z_COMPANION_SITES_URI . 'assets/js/admin-load.js', array( 'jquery','wp-util', 'updates'), Z_COMPANION_SITES_VER, true );

			$data = apply_filters(
				'zita_sites_localize_vars',
				array(
					'debug'           => ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || isset( $_GET['debug'] ) ) ? true : false,
					'ajax_url'         => esc_url( admin_url( 'admin-ajax.php' ) ),
					'siteURL'         => site_url(),
					'getProText'      => __( 'Purchase', 'z-companion-sites' ),
					'getProURL'       => esc_url( 'https://wpzita.com/pricing/' ),
					'getUpgradeText'  => __( 'Upgrade', 'z-companion-sites' ),
					'getUpgradeURL'   => esc_url( 'https://wpzita.com/pricing/' ),
					'z_companion_zc_ajax_nonce'     => wp_create_nonce( 'z-companion-sites' ),
					'requiredPlugins' => array(),
					'unique'         => array(
						'importFailedBtnSmall' => __( 'Error!', 'z-companion-sites' ),
						'importFailedBtnLarge' => __( 'Error! Read Possibilities.', 'z-companion-sites' ),
						'importFailedURL'      => esc_url( 'https://wpzita.com/docs/' ),
						'viewSite'             => __( 'Done! View Site', 'z-companion-sites' ),
						'pluginActivating'        => __( 'Activating', 'z-companion-sites' ) . '&hellip;',
						'pluginActive'            => __( 'Active', 'z-companion-sites' ),
						'importFailBtn'        => __( 'Import failed.', 'z-companion-sites' ),
						'importFailBtnLarge'   => __( 'Import failed. See error log.', 'z-companion-sites' ),
						'importDemo'           => __( 'Import This Site', 'z-companion-sites' ),
						'importingDemo'        => __( 'Importing..', 'z-companion-sites' ),
						'DescExpand'           => __( 'Read more', 'z-companion-sites' ) . '&hellip;',
						'DescCollapse'         => __( 'Hide', 'z-companion-sites' ),
						'responseError'        => __( 'There was a problem receiving a response from server.', 'z-companion-sites' ),
						'searchNoFound'        => __( 'No Demos found, Try a different search.', 'z-companion-sites' ),
						'importWarning'        => __( "Executing Demo Import will make your site similar as ours. Please bear in mind -\n\n1. It is recommended to run import on a fresh WordPress installation.\n\n2. Importing site does not delete any pages or posts. However, it can overwrite your existing content.\n\n3. Copyrighted media will not be imported. Instead it will be replaced with placeholders.", 'z-companion-sites' ),
						'importComplete'          => __( 'Import Complete..', 'z-companion-sites' ),
						'importCustomizer'     => __( 'Importing Customizer', 'z-companion-sites' ),
						'importXMLPreparing'      => __( 'Setting up import data..', 'z-companion-sites' ),
						'importingXML'            => __( 'Importing Pages & Media..', 'z-companion-sites' ),
						'importingWidgets'        => __( 'Importing Widgets..', 'z-companion-sites' ),
						'importingOptions'        => __( 'Importing Options Data..', 'z-companion-sites' ),

						'gettingData'             => __( 'Getting Site Information..', 'z-companion-sites' ),
						'serverConfiguration'     => esc_url( 'https://wpzita.com/docs/?p=1314&utm_source=demo-import-panel&utm_campaign=import-error&utm_medium=wp-dashboard' ),
					),
				)
			);

			wp_localize_script( 'z-companion-sites-admin-load', 'zCompanionAdmin', $data); 

		}

		/**
		 * Load all the required files in the importer.
		 *
		 * @since  1.0.0
		 */
		private function includes() {

			require_once Z_COMPANION_SITES_DIR . 'inc/helper.php';
			require_once Z_COMPANION_SITES_DIR . 'importer/wxr-importer.php';
			require_once Z_COMPANION_SITES_DIR . 'inc/zita-option-data-import.php';
			require_once Z_COMPANION_SITES_DIR . 'inc/import-widgets.php';
			require_once Z_COMPANION_SITES_DIR . 'inc/admin-ajax.php';
			require_once Z_COMPANION_SITES_DIR . 'inc/zita-notices.php';
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	Z_Companion_Sites_Load::get_instance();

endif;
