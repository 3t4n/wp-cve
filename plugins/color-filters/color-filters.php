<?php
/*
Plugin Name: Ultimate WooCommerce Filters
Plugin URI: https://www.etoilewebdesign.com/plugins/woocommerce-filters/
Description: Filter WooCommerce products by color, size, attribute, categories and tags. Customize your filtering and set a schedule for ordering. 
Author: Etoile Web Design
Author URI: https://www.etoilewebdesign.com
Terms and Conditions: http://www.etoilewebdesign.com/plugin-terms-and-conditions/
Text Domain: color-filters
Version: 3.3.2
WC requires at least: 7.1
WC tested up to: 8.2
*/

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'ewduwcfInit' ) ) {
class ewduwcfInit {

	// Any data that needs to be passed from PHP to our JS files 
	public $front_end_php_js_data = array();

	/**
	 * Initialize the plugin and register hooks
	 */
	public function __construct() {

		self::constants();
		self::includes();
		self::instantiate();
		self::wp_hooks();
	}

	/**
	 * Define plugin constants.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function constants() {

		define( 'EWD_UWCF_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EWD_UWCF_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'EWD_UWCF_PLUGIN_FNAME', plugin_basename( __FILE__ ) );
		define( 'EWD_UWCF_TEMPLATE_DIR', 'ewd-uwcf-templates' );
		define( 'EWD_UWCF_VERSION', '3.3.2' );

		define( 'EWD_UWCF_WOOCOMMERCE_POST_TYPE', 'product' );
		define( 'EWD_UWCF_PRODUCT_COLOR_TAXONOMY', 'product_color' );
		define( 'EWD_UWCF_PRODUCT_SIZE_TAXONOMY', 'product_size' );
	}

	/**
	 * Include necessary classes.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function includes() {

		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/AboutUs.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Blocks.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/CustomPostTypes.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Dashboard.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/DeactivationSurvey.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Helper.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/InstallationWalkthrough.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Permissions.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/ReviewAsk.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Scheduling.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Settings.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/template-functions.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/Widgets.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/WooCommerceFiltering.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/WooCommerceSync.class.php' );
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/WooCommerceTable.class.php' );
	}

	/**
	 * Spin up instances of our plugin classes.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function instantiate() {

		new ewduwcfDashboard();
		new ewduwcfDeactivationSurvey();
		new ewduwcfInstallationWalkthrough();
		new ewduwcfReviewAsk();
		new ewduwcfWidgetManager();
		new ewduwcfBlocks();

		$this->cpts 		= new ewduwcfCustomPostTypes();
		$this->permissions 	= new ewduwcfPermissions();
		$this->scheduling 	= new ewduwcfScheduling();
		$this->settings 	= new ewduwcfSettings();
		$this->wc_filtering = new ewduwcfWooCommerceFiltering();
		$this->wc_sync 		= new ewduwcfWooCommerceSync();
		$this->wc_table 	= new ewduwcfWooCommerceTable();

		$this->cpts->colors_enabled = $this->settings->get_setting( 'color-filtering' );
		$this->cpts->sizes_enabled = $this->settings->get_setting( 'size-filtering' );

		new ewduwcfAboutUs();
	}

	/**
	 * Run walk-through, load assets, add links to plugin listing, etc.
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	protected function wp_hooks() {

		register_activation_hook( __FILE__, 	array( $this, 'run_walkthrough' ) );
		register_activation_hook( __FILE__, 	array( $this, 'convert_options' ) );

		add_action( 'init', array( $this, 'load_view_files' ) );
		add_action( 'init', array( $this, 'plugin_init' ), 9 );

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		add_action( 'admin_notices', array( $this, 'display_header_area' ) );
		add_action( 'admin_notices', array( $this, 'maybe_display_helper_notice' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ), 10, 1 );
		add_action( 'wp_enqueue_scripts',    array( $this, 'register_assets' ) );
		add_action( 'wp_footer', 			 array( $this, 'assets_footer' ), 2 );

		add_action( 'wp_head', 'ewd_add_frontend_ajax_url' );

		add_filter( 'plugin_action_links', array( $this, 'plugin_action_links' ), 10, 2);

		add_action( 'wp_ajax_ewd_uwcf_hide_helper_notice', array( $this, 'hide_helper_notice' ) );

		add_action( 'before_woocommerce_init', array( $this, 'declare_wc_hpos' ) );
	}

	/**
	 * Run the options conversion function on update if necessary
	 *
	 * @since  3.0.0
	 * @access protected
	 * @return void
	 */
	public function convert_options() {
		
		require_once( EWD_UWCF_PLUGIN_DIR . '/includes/BackwardsCompatibility.class.php' );
		new ewduwcfBackwardsCompatibility();
	}

	/**
	 * Load files needed for views
	 * @since 3.0.0
	 * @note Can be filtered to add new classes as needed
	 */
	public function load_view_files() {
	
		$files = array(
			EWD_UWCF_PLUGIN_DIR . '/views/Base.class.php' // This will load all default classes
		);
	
		$files = apply_filters( 'ewd_uwcf_load_view_files', $files );
	
		foreach( $files as $file ) {
			require_once( $file );
		}
	
	}

	/**
	 * Load the plugin textdomain for localisation
	 * @since 3.0.0
	 */
	public function load_textdomain() {
		
		load_plugin_textdomain( 'color-filters', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Set a transient so that the walk-through gets run
	 * @since 2.0.0
	 */
	public function run_walkthrough() {

		if( ! defined( 'WC_VERSION' ) ) {
			wp_die( __( 'WooCommerce is required to be acive for our plugin to work properly.', 'color-filters' ) );
		}

		set_transient( 'ewd-uwcf-getting-started', true, 30 );
	} 

	/**
	 * Enqueue the admin-only CSS and Javascript
	 * @since 3.0.0
	 */
	public function enqueue_admin_assets( $hook ) {
		global $post;
		global $ewd_uwcf_controller;

		wp_enqueue_script( 'ewd-uwcf-helper-notice', EWD_UWCF_PLUGIN_URL . '/assets/js/ewd-uwcf-helper-install-notice.js', array( 'jquery' ), EWD_UWCF_VERSION, true );
		wp_localize_script(
			'ewd-uwcf-helper-notice',
			'ewd_uwcf_helper_notice',
			array( 'nonce' => wp_create_nonce( 'ewd-uwcf-helper-notice' ) )
		);

		wp_enqueue_style( 'ewd-uwcf-helper-notice', EWD_UWCF_PLUGIN_URL . '/assets/css/ewd-uwcf-helper-install-notice.css', array(), EWD_UWCF_VERSION );

		$screen = get_current_screen();

		$candidates = array(
			EWD_UWCF_WOOCOMMERCE_POST_TYPE,

			EWD_UWCF_PRODUCT_COLOR_TAXONOMY,
			EWD_UWCF_PRODUCT_SIZE_TAXONOMY,

			'product_page_product_attributes',

			'toplevel_page_ewd-uwcf-dashboard',
			'wc-filters_page_ewd-uwcf-settings',
			'wc-filters_page_ewd-uwcf-table-mode',
			'wc-filters_page_ewd-uwcf-about-us',

			'widgets.php',
		);
		
   		// Return if not on a uwcf post_type, we're not on a post-type page, or we're not on the settings or widget pages
		if ( ! in_array( $hook, $candidates )
			and ( empty( $screen->post_type ) or ! in_array ( $screen->post_type, $candidates ) )
			and ( empty( $screen->taxonomy ) or ! in_array ( $screen->taxonomy, $candidates ) )
			and ! in_array( $screen->id, $candidates )
		) {
			return;
		}

		if ( $screen->taxonomy == 'product_color' ) {
			wp_enqueue_style( 'ewd-uwcf-admin-spectrum-css', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/spectrum.css', array(), EWD_UWCF_VERSION );
			wp_enqueue_script( 'ewd-uwcf-admin-spectrum-js', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/js/spectrum.js', array( 'jquery' ), EWD_UWCF_VERSION );
		}

		if ( $screen->id == 'wc-filters_page_ewd-uwcf-table-mode' || $screen->taxonomy == 'product_color' ) {
			wp_enqueue_style( 'sap-admin-style', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/admin.css', array(), EWD_UWCF_VERSION );
			wp_enqueue_style( 'sap-admin-settings-css', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/css/admin-settings.css', array(), EWD_UWCF_VERSION );
			wp_enqueue_script( 'sap-admin-settings-js', EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/js/admin-settings.js', array( 'jquery' ), EWD_UWCF_VERSION );
		}

		wp_enqueue_style( 'ewd-uwcf-admin-css', EWD_UWCF_PLUGIN_URL . '/assets/css/ewd-uwcf-admin.css', array(), EWD_UWCF_VERSION );
		wp_enqueue_script( 'ewd-uwcf-admin-js', EWD_UWCF_PLUGIN_URL . '/assets/js/ewd-uwcf-admin.js', array( 'jquery', 'jquery-ui-sortable' ), EWD_UWCF_VERSION, true );

		if ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering' ) ) {
			
			$args = array( 
				'hide_empty' => false,
				'taxonomy' => EWD_UWCF_PRODUCT_COLOR_TAXONOMY
			);

			$colors = get_terms( $args );
		
			if ( ! is_wp_error( $colors ) ) {

				foreach ( $colors as $index => $color ) {

					$color_value = get_term_meta( $color->term_id, 'EWD_UWCF_Color', true );
					$colors[ $index ]->color = $color_value;
				}
			}
		}
		else { $colors = array(); }

		wp_localize_script( 'ewd-uwcf-admin-js', 'ewd_uwcf_color_data', $colors );

		$settings = array(
			'nonce' => wp_create_nonce( 'ewd-uwcf-admin-js' ),
		);

		wp_localize_script( 'ewd-uwcf-admin-js', 'ewd_uwcf_admin_php_data', $settings );
	}

	/**
	 * Register the front-end CSS and Javascript for the slider
	 * @since 3.0.0
	 */
	function register_assets() {
		global $ewd_uwcf_controller;

		wp_register_style( 'jquery-ui', EWD_UWCF_PLUGIN_URL . '/assets/css/jquery-ui.min.css' );
		wp_register_style( 'ewd-uwcf-css', EWD_UWCF_PLUGIN_URL . '/assets/css/ewd-uwcf.css', EWD_UWCF_VERSION );
		
		wp_register_script( 'ewd-uwcf-js', EWD_UWCF_PLUGIN_URL . '/assets/js/ewd-uwcf.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-autocomplete', 'jquery-ui-slider' ), EWD_UWCF_VERSION, true );
	}

	/**
	 * Print out any PHP data needed for our JS to work correctly
	 * @since 3.1.0
	 */
	public function assets_footer() {

		if ( empty( $this->front_end_php_js_data ) ) { return; }

		$print_variables = array();

		foreach ( (array) $this->front_end_php_js_data as $variable => $values ) {

			if ( empty( $values ) ) { continue; }

			$print_variables[ $variable ] = ewduwcfHelper::escape_js_recursive( $values );
		}

		foreach ( $print_variables as $variable => $values ) {

			echo "<script type='text/javascript'>\n";
			echo "/* <![CDATA[ */\n";
			echo 'var ' . esc_attr( $variable ) . ' = ' . wp_json_encode( $values ) . "\n";
			echo "/* ]]> */\n";
			echo "</script>\n";
		}
	}

	/**
	 * Adds a variable to be passed to our front-end JS
	 * @since 3.1.0
	 */
	public function add_front_end_php_data( $handle, $variable, $data ) {

		$this->front_end_php_js_data[ $variable ] = $data;
	}

	/**
	 * Returns the corresponding front-end JS variable if it exists, otherwise an empty array
	 * @since 3.1.0
	 */
	public function get_front_end_php_data( $handle, $variable ) {

		return ! empty( $this->front_end_php_js_data[ $variable ] ) ? $this->front_end_php_js_data[ $variable ] : array();
	}

	/**
	 * Add links to the plugin listing on the installed plugins page
	 * @since 3.0.0
	 */
	public function plugin_action_links( $links, $plugin ) {
		global $ewd_uwcf_controller;
		
		if ( $plugin == EWD_UWCF_PLUGIN_FNAME ) {

			if ( ! $ewd_uwcf_controller->permissions->check_permission( 'premium' ) ) {

				array_unshift( $links, '<a class="ewd-uwcf-plugin-page-upgrade-link" href="https://www.etoilewebdesign.com/license-payment/?Selected=UWCF&Quantity=1&utm_source=wp_admin_plugins_page" title="' . __( 'Try Premium', 'color-filters' ) . '" target="_blank">' . __( 'Try Premium', 'color-filters' ) . '</a>' );
			}

			$links['settings'] = '<a href="admin.php?page=ewd-uwcf-settings" title="' . __( 'Head to the settings page for Ultimate WooCommerce Filters', 'color-filters' ) . '">' . __( 'Settings', 'color-filters' ) . '</a>';
		}

		return $links;

	}

	/**
	 * Adds in a menu bar for the plugin
	 * @since 3.0.0
	 */
	public function display_header_area() {
		global $ewd_uwcf_controller;

		$screen = get_current_screen();
		
		if ( $screen->id != 'toplevel_page_ewd-uwcf-dashboard' && $screen->id != 'wc-filters_page_ewd-uwcf-settings' && $screen->id != 'wc-filters_page_ewd-uwcf-table-mode' && $screen->id != 'wc-filters_page_ewd-uwcf-about-us' ) { return; }
		
		if ( ! $ewd_uwcf_controller->permissions->check_permission( 'styling' ) or get_option( 'EWD_UWCF_Trial_Happening' ) == 'Yes' ) {
			?>
			<div class="ewd-uwcf-dashboard-new-upgrade-banner">
				<div class="ewd-uwcf-dashboard-banner-icon"></div>
				<div class="ewd-uwcf-dashboard-banner-buttons">
					<a class="ewd-uwcf-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=UWCF&Quantity=1&utm_source=uwcf_admin&utm_content=banner" target="_blank">UPGRADE NOW</a>
				</div>
				<div class="ewd-uwcf-dashboard-banner-text">
					<div class="ewd-uwcf-dashboard-banner-title">
						GET FULL ACCESS WITH OUR PREMIUM VERSION
					</div>
					<div class="ewd-uwcf-dashboard-banner-brief">
						Attribute filtering, advanced styling options and more!
					</div>
				</div>
			</div>
			<?php
		}
		
		?>
		<div class="ewd-uwcf-admin-header-menu">
			<h2 class="nav-tab-wrapper">
				<a id="ewd-uwcf-dash-mobile-menu-open" href="#" class="menu-tab nav-tab"><?php _e("MENU", 'color-filters'); ?><span id="ewd-uwcf-dash-mobile-menu-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-uwcf-dash-mobile-menu-up-caret">&nbsp;&nbsp;&#9650;</span></a>
				<a id="dashboard-menu" href="admin.php?page=ewd-uwcf-dashboard" class="menu-tab nav-tab <?php if ( $screen->id == 'toplevel_page_ewd-uwcf-dashboard' ) {echo 'nav-tab-active';}?>"><?php _e("Dashboard", 'color-filters'); ?></a>
				<?php if ( $ewd_uwcf_controller->settings->get_setting( 'color-filtering' ) ) { ?>
					<a id="colors-menu" href="edit-tags.php?taxonomy=product_color" class="menu-tab nav-tab <?php if ( $screen->id == 'wc-filters_page_' ) {echo 'nav-tab-active';}?>"><?php _e("Colors", 'color-filters'); ?></a>
				<?php } ?>
				<?php if ( $ewd_uwcf_controller->settings->get_setting( 'size-filtering' ) ) { ?>
					<a id="sizes-menu" href="edit-tags.php?taxonomy=product_size" class="menu-tab nav-tab <?php if ( $screen->id == 'wc-filters_page_' ) {echo 'nav-tab-active';}?>"><?php _e("Sizes", 'color-filters'); ?></a>
				<?php } ?>
				<a id="options-menu" href="admin.php?page=ewd-uwcf-settings" class="menu-tab nav-tab <?php if ( $screen->id == 'wc-filters_page_ewd-uwcf-settings' ) {echo 'nav-tab-active';}?>"><?php _e("Settings", 'color-filters'); ?></a>
				<?php if ( $ewd_uwcf_controller->settings->get_setting( 'table-format' ) ) { ?>
					<a id="table-mode-menu" href="admin.php?page=ewd-uwcf-table-mode" class="menu-tab nav-tab <?php if ( $screen->id == 'wc-filters_page_ewd-uwcf-table-mode' ) {echo 'nav-tab-active';}?>"><?php _e("Table Format", 'color-filters'); ?></a>
				<?php } ?>
			</h2>
		</div>
		<?php
	}

	public function maybe_display_helper_notice() {
		global $ewd_uwcf_controller;

		if ( empty( $ewd_uwcf_controller->permissions->check_permission( 'premium' ) ) ) { return; }

		if ( is_plugin_active( 'ewd-premium-helper/ewd-premium-helper.php' ) ) { return; }

		if ( get_transient( 'ewd-helper-notice-dismissed' ) ) { return; }

		?>

		<div class='notice notice-error is-dismissible ewd-uwcf-helper-install-notice'>
			
			<div class='ewd-uwcf-helper-install-notice-img'>
				<img src='<?php echo EWD_UWCF_PLUGIN_URL . '/lib/simple-admin-pages/img/options-asset-exclamation.png' ; ?>' />
			</div>

			<div class='ewd-uwcf-helper-install-notice-txt'>
				<?php _e( 'You\'re using the Ultimate WooCommerce Filters premium version, but the premium helper plugin is not active.', 'color-filters' ); ?>
				<br />
				<?php echo sprintf( __( 'Please re-activate the helper plugin, or <a target=\'_blank\' href=\'%s\'>download and install it</a> if the plugin is no longer installed to ensure continued access to the premium features of the plugin.', 'color-filters' ), 'https://www.etoilewebdesign.com/2021/12/11/requiring-premium-helper-plugin/' ); ?>
			</div>

			<div class='ewd-uwcf-clear'></div>

		</div>

		<?php 
	}

	public function hide_helper_notice() {

		// Authenticate request
		if ( ! check_ajax_referer( 'ewd-uwcf-helper-notice', 'nonce' ) or ! current_user_can( 'manage_options' ) ) {

			ewduwcfHelper::admin_nopriv_ajax();
		}

		set_transient( 'ewd-helper-notice-dismissed', true, 3600*24*7 );

		die();
	}

	/**
	 * Let other plugin extend this plugin
	 * 
	 * @return void
	 */
	public function plugin_init()
	{
		do_action( 'ewd_uwcf_initialized' );
	}

	/**
	 * Declares compatibility with WooCommerce High-Performance Order Storage
	 * @since 3.3.2
	 */
	public function declare_wc_hpos() {

		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {

			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
}
} // endif;

global $ewd_uwcf_controller;
$ewd_uwcf_controller = new ewduwcfInit();