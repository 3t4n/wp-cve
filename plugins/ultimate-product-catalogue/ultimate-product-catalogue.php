<?php
/**
 * Plugin Name: Ultimate Product Catalog
 * Plugin URI: https://www.etoilewebdesign.com/plugins/ultimate-product-catalog/
 * Description: Add a product catalog to your site with blocks or shortcodes. Works with WooCommerce or standalone. Flexible and customizable, works with any theme.
 * Version: 5.2.16
 * Author: Etoile Web Design
 * Author URI: https://www.etoilewebdesign.com/
 * Terms and Conditions: https://www.etoilewebdesign.com/plugin-terms-and-conditions/
 * Text Domain: ultimate-product-catalogue
 * Domain Path: /languages/
 * WC requires at least: 7.1
 * WC tested up to: 8.5
 */

if ( ! defined( 'ABSPATH' ) )
	exit;

if ( ! class_exists( 'ewdupcpInit' ) ) {
class ewdupcpInit {

	// Any data that needs to be passed from PHP to our JS files 
	public $front_end_php_js_data = array();

	// Flag for whether a single product page is being displayed
	public $is_single_product = false;

	// Holds the schema data for the single product currently being displayed, if any
	public $schema_product_data = array();

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
	 * @since  5.0.0
	 * @access protected
	 * @return void
	 */
	protected function constants() {

		define( 'EWD_UPCP_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EWD_UPCP_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'EWD_UPCP_PLUGIN_FNAME', plugin_basename( __FILE__ ) );
		define( 'EWD_UPCP_TEMPLATE_DIR', 'ewd-upcp-templates' );
		define( 'EWD_UPCP_VERSION', '5.2.16' );

		define( 'EWD_UPCP_PRODUCT_POST_TYPE', 'upcp_product' );
		define( 'EWD_UPCP_CATALOG_POST_TYPE', 'upcp_catalog' );
		define( 'EWD_UPCP_PRODUCT_CATEGORY_TAXONOMY', 'upcp-product-category' );
		define( 'EWD_UPCP_PRODUCT_TAG_TAXONOMY', 'upcp-product-tag' );
	}

	/**
	 * Include necessary classes.
	 *
	 * @since  5.0.0
	 * @access protected
	 * @return void
	 */
	protected function includes() {

		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/AboutUs.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Ajax.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/AdminCustomFields.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/AdminProductPage.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Blocks.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Patterns.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/CustomPostTypes.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Dashboard.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/DeactivationSurvey.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Helper.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Export.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Import.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/InstallationWalkthrough.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Product.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Permissions.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/ReviewAsk.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/SEO.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Settings.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/template-functions.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/Widgets.class.php' );
		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/WooCommerce.class.php' );
	}

	/**
	 * Spin up instances of our plugin classes.
	 *
	 * @since  5.0.0
	 * @access protected
	 * @return void
	 */
	protected function instantiate() {

		new ewdupcpDashboard();
		new ewdupcpDeactivationSurvey();
		new ewdupcpInstallationWalkthrough();
		new ewdupcpReviewAsk();

		$this->ajax 				= new ewdupcpAJAX();
		$this->cpts					= new ewdupcpCustomPostTypes();
		$this->admin_custom_fields	= new ewdupcpAdminCustomFields();
		$this->admin_product_page	= new ewdupcpAdminProductPage();
		$this->exports 				= new ewdupcpExport();
		$this->permissions 			= new ewdupcpPermissions();
		$this->settings 			= new ewdupcpSettings(); 

		if ( $this->settings->get_setting( 'woocommerce-sync' ) ) {
			
			$this->woocommerce = new ewdupcpWooCommerce();
		}

		new ewdupcpBlocks();
		if ( function_exists( 'register_block_pattern' ) ) { new ewdupcpPatterns(); }
		new ewdupcpImport();
		new ewdupcpSEO();
		new ewdupcpWidgetManager();
		new ewdupcpAboutUs();
	}

	/**
	 * Run walk-through, load assets, add links to plugin listing, etc.
	 *
	 * @since  5.0.0
	 * @access protected
	 * @return void
	 */
	protected function wp_hooks() {

		register_activation_hook( __FILE__, 			array( $this, 'run_walkthrough' ) );
		register_activation_hook( __FILE__, 			array( $this, 'schedule_upgrade' ) );
		register_activation_hook( __FILE__, 			array( $this, 'add_capabilities' ) );

		add_filter( 'init',								array( $this, 'rewrite_rules' ) );
		add_filter( 'query_vars',						array( $this, 'add_query_vars' ) );

		add_filter( 'the_content', 						array( $this, 'alter_product_content' ) );
		add_action( 'wp_footer', 						array( $this, 'output_ld_json_content' ) );

		add_action( 'init',			        			array( $this, 'load_view_files' ) );
		add_action( 'ewd_upcp_run_backwards_compat',	array( $this, 'convert_options' ) );

		add_action( 'plugins_loaded',        			array( $this, 'load_textdomain' ) );

		add_action( 'admin_notices', 					array( $this, 'display_header_area' ) );
		add_action( 'admin_notices', 					array( $this, 'maybe_display_helper_notice' ) );

		add_action( 'admin_enqueue_scripts', 			array( $this, 'enqueue_admin_assets' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', 			array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', 				array( $this, 'register_assets' ) );
		add_action( 'wp_head',							'ewd_add_frontend_ajax_url' );
		add_action( 'wp_footer', 						array( $this, 'assets_footer' ), 2 );

		add_filter( 'plugin_action_links',				array( $this, 'plugin_action_links' ), 10, 2);

		add_action( 'wp_ajax_ewd_upcp_hide_helper_notice', array( $this, 'hide_helper_notice' ) );

		add_action( 'before_woocommerce_init', array( $this, 'declare_wc_hpos' ) );
	}

	/**
	 * Runs a simple option set on activation
	 *
	 * @since  5.0.0
	 * @access protected
	 * @return void
	 */
	public function schedule_upgrade() {

		wp_schedule_single_event( time() + 2, 'ewd_upcp_run_backwards_compat' );
	}

	/**
	 * Run the options conversion function on update if necessary
	 *
	 * @since  5.0.0
	 * @access protected
	 * @return void
	 */
	public function convert_options() {

		require_once( EWD_UPCP_PLUGIN_DIR . '/includes/BackwardsCompatibility.class.php' );
		new ewdupcpBackwardsCompatibility();
	}

	/**
	 * Adds in the rewrite rules used by the plugin and flushes rules if necessary
	 *
	 * @since  5.0.0
	 * @access public
	 * @return void
	 */
	public function rewrite_rules() {
		global $ewd_upcp_controller;

		$review_rules = get_option( 'rewrite_rules' );
		$frontpage_id = get_option( 'page_on_front' );

		$permalink_base = $ewd_upcp_controller->settings->get_setting( 'permalink-base' );

		add_rewrite_tag( '%single_product%', '([^&]+)' );
	
		//add_rewrite_rule( $permalink_base . "/([^&]*)/?$", "index.php?page_id=". $frontpage_id . "&single_product=\$matches[1]", 'top' );
		add_rewrite_rule( "(.?.+?)/" . $permalink_base . "/([^&]*)/?$", "index.php?pagename=\$matches[1]&single_product=\$matches[2]", 'top' );

		if ( ! isset( $review_rules['(.?.+?)/' . $permalink_base . '/([^&]*)/?$'] ) ) { flush_rewrite_rules(); }
	}

	/**
	 * Adds in the query vars used by the plugin
	 *
	 * @since  5.0.0
	 * @access public
	 * @return array
	 */
	public function add_query_vars( $vars ) {

		$vars[] = 'single_product';
		$vars[] = 'product_id';

		return $vars;
	}

	/**
	 * Load files needed for views
	 * @since 5.0.0
	 * @note Can be filtered to add new classes as needed
	 */
	public function load_view_files() {
	
		$files = array(
			EWD_UPCP_PLUGIN_DIR . '/views/Base.class.php' // This will load all default classes
		);
	
		$files = apply_filters( 'ewd_upcp_load_view_files', $files );
	
		foreach( $files as $file ) {
			require_once( $file );
		}
	
	}

	/**
	 * Load the plugin textdomain for localisation
	 * @since 5.0.0
	 */
	public function load_textdomain() {
		
		load_plugin_textdomain( 'ultimate-product-catalogue', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Set a transient so that the walk-through gets run
	 * @since 5.0.0
	 */
	public function run_walkthrough() {

		set_transient( 'ewd-upcp-getting-started', true, 30 );
	} 

	/**
	 * Enqueue the admin-only CSS and Javascript
	 * @since 5.0.0
	 */
	public function enqueue_admin_assets( $hook ) {
		global $post;

		wp_enqueue_script( 'ewd-upcp-helper-notice', EWD_UPCP_PLUGIN_URL . '/assets/js/ewd-upcp-helper-install-notice.js', array( 'jquery' ), EWD_UPCP_VERSION, true );
		wp_localize_script(
			'ewd-upcp-helper-notice',
			'ewd_upcp_helper_notice',
			array( 'nonce' => wp_create_nonce( 'ewd-upcp-helper-notice' ) )
		);

		wp_enqueue_style( 'ewd-upcp-helper-notice', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-upcp-helper-install-notice.css', array(), EWD_UPCP_VERSION );

		$screen = get_current_screen();

		// Return if not one of the UPCP post types, we're not on a post-type page, or we're not on the settings or widget pages
		$candidates = array(
			'upcp_product_page_ewd-upcp-dashboard',
			'admin_page_ewd-upcp-dashboard',

			'edit-upcp_product',
			'upcp_product',

			'edit-upcp_catalog',
			'upcp_catalog',

			'edit-upcp-product-category',
			'edit-upcp-product-tag',
			'upcp_product_page_ewd-upcp-export',
			'admin_page_ewd-upcp-export',
			'upcp_product_page_ewd-upcp-import',
			'admin_page_ewd-upcp-import',
			'upcp_product_page_ewd-upcp-custom-fields',
			'admin_page_ewd-upcp-custom-fields',
			'upcp_product_page_ewd-upcp-product-page',
			'admin_page_ewd-upcp-product-page',
			'upcp_product_page_ewd-upcp-settings',
			'admin_page_ewd-upcp-settings'
		);

		if ( ! in_array( $hook, $candidates )
			and ( empty( $screen->post_type ) or ! in_array ( $screen->post_type, $candidates ) )
			and ! in_array( $screen->id, $candidates )
		) {
			return;
		}

		wp_enqueue_media();

		wp_enqueue_style( 'ewd-upcp-admin-css', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-upcp-admin.css', array(), EWD_UPCP_VERSION );
		wp_enqueue_script( 'ewd-upcp-admin-js', EWD_UPCP_PLUGIN_URL . '/assets/js/ewd-upcp-admin.js', array( 'jquery', 'jquery-ui-sortable' ), EWD_UPCP_VERSION, true );

		$args = array(
			'post_type' 	=> EWD_UPCP_PRODUCT_POST_TYPE,
			'numberposts'	=> 100,
		);

		$products = get_posts( $args );

		$args = array(
			'nonce' => wp_create_nonce( 'ewd-upcp-admin-js' ),
			'product_add' => ( $this->permissions->check_permission( 'premium' ) or sizeof( $products ) < 100 ),
		);

		wp_localize_script( 'ewd-upcp-admin-js', 'ewd_upcp_php_admin_data', $args );
	}

	/**
	 * Register the front-end CSS and Javascript for the FAQs
	 * @since 5.0.0
	 */
	function register_assets() {
		global $ewd_upcp_controller;

		wp_register_style( 'ewd-upcp-gridster', EWD_UPCP_PLUGIN_URL . '/assets/css/jquery.gridster.css', EWD_UPCP_VERSION );
		wp_register_style( 'ewd-upcp-css', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-upcp.css', EWD_UPCP_VERSION );
		wp_register_style( 'ewd-ulb-main-css', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-ulb-main.css', EWD_UPCP_VERSION );
		wp_register_style( 'ewd-upcp-jquery-ui', EWD_UPCP_PLUGIN_URL . '/assets/css/ewd-upcp-jquery-ui.css', EWD_UPCP_VERSION );
		wp_register_style( 'rrssb', EWD_UPCP_PLUGIN_URL . '/assets/css/rrssb-min.css', EWD_UPCP_VERSION );		

		wp_register_script( 'ultimate-lightbox', EWD_UPCP_PLUGIN_URL . '/assets/js/ultimate-lightbox.js', array( 'jquery' ), EWD_UPCP_VERSION, true ); 
		wp_register_script( 'ewd-upcp-gridster', EWD_UPCP_PLUGIN_URL . '/assets/js/jquery.gridster.js', array( 'jquery' ), EWD_UPCP_VERSION, true );
		wp_register_script( 'ewd-upcp-js', EWD_UPCP_PLUGIN_URL . '/assets/js/ewd-upcp.js', array( 'jquery', 'jquery-ui-slider' ), EWD_UPCP_VERSION, true );

		wp_localize_script(
			'ewd-upcp-js',
			'ewd_upcp_js',
			array(
				'nonce' => wp_create_nonce( 'ewd-upcp-js' )
			)
		);
	}

	/**
	 * Print out any PHP data needed for our JS to work correctly
	 * @since 5.1.0
	 */
	public function assets_footer() {

		if ( empty( $this->front_end_php_js_data ) ) { return; }

		$print_variables = array();

		foreach ( (array) $this->front_end_php_js_data as $variable => $values ) {

			if ( empty( $values ) ) { continue; }

			$print_variables[ $variable ] = ewdupcpHelper::escape_js_recursive( $values );
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
	 * @since 5.1.0
	 */
	public function add_front_end_php_data( $handle, $variable, $data ) {

		$this->front_end_php_js_data[ $variable ] = $data;
	}

	/**
	 * Returns the corresponding front-end JS variable if it exists, otherwise an empty array
	 * @since 5.1.0
	 */
	public function get_front_end_php_data( $handle, $variable ) {

		return ! empty( $this->front_end_php_js_data[ $variable ] ) ? $this->front_end_php_js_data[ $variable ] : array();
	}

	/**
	 * Add links to the plugin listing on the installed plugins page
	 * @since 5.0.0
	 */
	public function plugin_action_links( $links, $plugin ) {
		global $ewd_upcp_controller;
		
		if ( $plugin == EWD_UPCP_PLUGIN_FNAME ) {

			if ( ! $ewd_upcp_controller->permissions->check_permission( 'premium' ) ) {

				array_unshift( $links, '<a class="ewd-upcp-plugin-page-upgrade-link" href="https://www.etoilewebdesign.com/license-payment/?Selected=UPCP&Quantity=1&utm_source=wp_admin_plugins_page" title="' . __( 'Try Premium', 'ultimate-product-catalogue' ) . '" target="_blank">' . __( 'Try Premium', 'ultimate-product-catalogue' ) . '</a>' );
			}

			$links['settings'] = '<a href="edit.php?post_type=upcp_product&page=ewd-upcp-settings" title="' . __( 'Head to the settings page for Ultimate Product Catalog', 'ultimate-product-catalogue' ) . '">' . __( 'Settings', 'ultimate-product-catalogue' ) . '</a>';
		}

		return $links;
	}

	/**
	 * Add upcp_product editing capabilities to the selected roles
	 * @since 5.0.0
	 */
	public function add_capabilities() {
		
		$manage_products_roles = array(
			'administrator',
		);

		if ( $this->settings->get_setting( 'access-role' ) == 'delete_others_pages' ) {

			$manage_products_roles[] = 'editor';
		}
		elseif ( $this->settings->get_setting( 'access-role' ) == 'delete_published_posts' ) {

			$manage_products_roles[] = 'editor';
			$manage_products_roles[] = 'author';
		}
		elseif ( $this->settings->get_setting( 'access-role' ) == 'delete_posts' ) {

			$manage_products_roles[] = 'editor';
			$manage_products_roles[] = 'author';
			$manage_products_roles[] = 'contributor';
		}

		$capabilities = array(
			'edit_upcp_product',
			'read_upcp_product',
			'delete_upcp_product',
			'delete_upcp_products',
			'delete_private_upcp_products',
			'delete_published_upcp_products',
			'delete_others_upcp_products',
			'edit_upcp_products',
			'edit_private_upcp_products',
			'edit_published_upcp_products',
			'edit_others_upcp_products',
			'publish_upcp_products',
			'read_private_upcp_products',
			'create_upcp_products',
		);

		foreach ( $manage_products_roles as $role ) {

			$role_object = get_role( $role );

			foreach ( $capabilities as $capability ) {

				$role_object->add_cap( $capability );
			}
		}
	}

	/**
	 * Replace the content of the single Product page with the SingleProduct view rendering output
	 * @since 5.0.0
	 */
	public function alter_product_content( $content ) {
		global $post, $ewd_upcp_controller;

		if ( $post->post_type != EWD_UPCP_PRODUCT_POST_TYPE ) { return $content; }

		if ( is_archive() ) { return $content; }

		ewd_upcp_load_view_files();

		$product = new ewdupcpProduct();

		$product->load_post( $post->ID );

		$args = array(
			'product'	=> $product
		);

		$view =  new ewdupcpViewSingleProduct( $args );

		return $view->render();
	}

	/**
	 * Output any Product schema data, if enabled
	 *
	 * @since  5.0.0
	 */
	public function output_ld_json_content() {
		global $ewd_upcp_controller;

		if ( empty( $this->schema_product_data ) ) { return; }

		$ld_json_ouptut = apply_filters( 'ewd_upcp_ld_json_output', $this->schema_product_data );

		echo '<script type="application/ld+json" class="ewd-upcp-ld-json-data">';
		echo wp_json_encode( $ld_json_ouptut );
		echo '</script>';
	}

	/**
	 * Adds in a menu bar for the plugin
	 * @since 5.0.0
	 */
	public function display_header_area() {
		global $ewd_upcp_controller;

		$screen = get_current_screen();

		// Return if not one of the UPCP post types, we're not on a post-type page, or we're not on the settings or widget pages
		$candidates = array(
			'upcp_product_page_ewd-upcp-dashboard',
			'admin_page_ewd-upcp-dashboard',

			'edit-upcp_product',
			'upcp_product',

			'edit-upcp_catalog',
			'upcp_catalog',

			'edit-upcp-product-category',
			'edit-upcp-product-tag',
			'upcp_product_page_ewd-upcp-export',
			'admin_page_ewd-upcp-export',
			'upcp_product_page_ewd-upcp-import',
			'admin_page_ewd-upcp-import',
			'upcp_product_page_ewd-upcp-custom-fields',
			'admin_page_ewd-upcp-custom-fields',
			'upcp_product_page_ewd-upcp-product-page',
			'admin_page_ewd-upcp-product-page',
			'upcp_product_page_ewd-upcp-settings',
			'admin_page_ewd-upcp-settings'
		);

		if ( ( empty( $screen->post_type ) or ! in_array ( $screen->post_type, $candidates ) ) and ! in_array( $screen->id, $candidates ) ) { return; }

		if ( get_option( 'ewd-upcp-transition-id', 'not-exists' ) != 'not-exists' ) { ?>

			<div class="notice notice-error is-dismissible">

				<p>
					<?php _e( 'Upgrade process is happening in the background. Please wait for this to complete before making changes to the plugin.', 'ultimate-product-catalogue' ); ?>
				</p>

			</div>

		<?php }
		
		if ( ! $ewd_upcp_controller->permissions->check_permission( 'styling' ) or get_option( 'EWD_UPCP_Trial_Happening' ) == 'Yes' ) {
			?>
			<div class="ewd-upcp-dashboard-new-upgrade-banner">
				<div class="ewd-upcp-dashboard-banner-icon"></div>
				<div class="ewd-upcp-dashboard-banner-buttons">
					<a class="ewd-upcp-dashboard-new-upgrade-button" href="https://www.etoilewebdesign.com/license-payment/?Selected=UPCP&Quantity=1&utm_source=upcp_admin&utm_content=banner" target="_blank">UPGRADE NOW</a>
				</div>
				<div class="ewd-upcp-dashboard-banner-text">
					<div class="ewd-upcp-dashboard-banner-title">
						GET FULL ACCESS WITH OUR PREMIUM VERSION
					</div>
					<div class="ewd-upcp-dashboard-banner-brief">
						Multiple catalog and product page layouts, WooCommerce integration, custom fields, advanced styling options and much more!
					</div>
				</div>
			</div>
			<?php
		}
		
		?>
		<div class="ewd-upcp-admin-header-menu">
			<h2 class="nav-tab-wrapper">
			<a id="ewd-upcp-dash-mobile-menu-open" href="#" class="menu-tab nav-tab"><?php _e("MENU", 'ultimate-product-catalogue'); ?><span id="ewd-upcp-dash-mobile-menu-down-caret">&nbsp;&nbsp;&#9660;</span><span id="ewd-upcp-dash-mobile-menu-up-caret">&nbsp;&nbsp;&#9650;</span></a>
			<a id="dashboard-menu" href='admin.php?page=ewd-upcp-dashboard' class="menu-tab nav-tab <?php if ( $screen->id == 'tracking_page_ewd-upcp-dashboard' ) {echo 'nav-tab-active';}?>"><?php _e("Dashboard", 'ultimate-product-catalogue'); ?></a>
			<a id="products-menu" href='edit.php?post_type=upcp_product' class="menu-tab nav-tab <?php if ( $screen->id == 'toplevel_page_upcp_product_page' ) {echo 'nav-tab-active';}?>"><?php _e("Products", 'ultimate-product-catalogue'); ?></a>
			<a id="catalogs-menu" href='edit.php?post_type=upcp_catalog' class="menu-tab nav-tab <?php if ( $screen->id == 'upcp_catalog' ) {echo 'nav-tab-active';}?>"><?php _e("Catalogs", 'ultimate-product-catalogue'); ?></a>
			<a id="categories-menu" href='edit-tags.php?taxonomy=upcp-product-category&post_type=upcp_product' class="menu-tab nav-tab <?php if ( $screen->id == 'toplevel_page_upcp-category' ) {echo 'nav-tab-active';}?>"><?php _e("Categories", 'ultimate-product-catalogue'); ?></a>
			<a id="tags-menu" href='edit-tags.php?taxonomy=upcp-product-tag&post_type=upcp_product' class="menu-tab nav-tab <?php if ( $screen->id == 'toplevel_page_upcp-tag' ) {echo 'nav-tab-active';}?>"><?php _e("Tags", 'ultimate-product-catalogue'); ?></a>
			<a id="export-menu" href='admin.php?page=ewd-upcp-export' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd-upcp-export' ) {echo 'nav-tab-active';}?>"><?php _e("Export", 'ultimate-product-catalogue'); ?></a>
			<a id="import-menu" href='admin.php?page=ewd-upcp-import' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd-upcp-import' ) {echo 'nav-tab-active';}?>"><?php _e("Import", 'ultimate-product-catalogue'); ?></a>
			<a id="custom-fields-menu" href='admin.php?page=ewd-upcp-custom-fields' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd-upcp-custom-fields' ) {echo 'nav-tab-active';}?>"><?php _e("Custom Fields", 'ultimate-product-catalogue'); ?></a>
			<a id="product-page-menu" href='admin.php?page=ewd-upcp-product-page' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd-upcp-product-page' ) {echo 'nav-tab-active';}?>"><?php _e("Product Page", 'ultimate-product-catalogue'); ?></a>
			<a id="options-menu" href='edit.php?post_type=upcp_product&page=ewd-upcp-settings' class="menu-tab nav-tab <?php if ( $screen->id == 'ewd_upcp_page_ewd-upcp-settings' ) {echo 'nav-tab-active';}?>"><?php _e("Settings", 'ultimate-product-catalogue'); ?></a>
			</h2>
		</div>
		<?php
	}

	public function maybe_display_helper_notice() {
		global $ewd_upcp_controller;

		if ( empty( $ewd_upcp_controller->permissions->check_permission( 'premium' ) ) ) { return; }

		if ( is_plugin_active( 'ewd-premium-helper/ewd-premium-helper.php' ) ) { return; }

		if ( get_transient( 'ewd-helper-notice-dismissed' ) ) { return; }
		
		?>

		<div class='notice notice-error is-dismissible ewd-upcp-helper-install-notice'>
			
			<div class='ewd-upcp-helper-install-notice-img'>
				<img src='<?php echo EWD_UPCP_PLUGIN_URL . '/lib/simple-admin-pages/img/options-asset-exclamation.png' ; ?>' />
			</div>

			<div class='ewd-upcp-helper-install-notice-txt'>
				<?php _e( 'You\'re using the Ultimate Product Catalog premium version, but the premium helper plugin is not active.', 'ultimate-product-catalogue' ); ?>
				<br />
				<?php echo sprintf( __( 'Please re-activate the helper plugin, or <a target=\'_blank\' href=\'%s\'>download and install it</a> if the plugin is no longer installed to ensure continued access to the premium features of the plugin.', 'ultimate-product-catalogue' ), 'https://www.etoilewebdesign.com/2021/12/11/requiring-premium-helper-plugin/' ); ?>
			</div>

			<div class='ewd-upcp-clear'></div>

		</div>

		<?php 
	}

	public function hide_helper_notice() {
		global $ewd_upcp_controller;

		// Authenticate request
		if ( 
			! check_ajax_referer( 'ewd-upcp-helper-notice', 'nonce' )
			||
			! current_user_can( $ewd_upcp_controller->settings->get_setting( 'access-role' ) )
		) {
			ewdupcpHelper::admin_nopriv_ajax();
		}

		set_transient( 'ewd-helper-notice-dismissed', true, 3600*24*7 );

		die();
	}

	/**
	 * Declares compatibility with WooCommerce High-Performance Order Storage
	 * @since 5.2.11
	 */
	public function declare_wc_hpos() {

		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {

			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
}
} // endif;

global $ewd_upcp_controller;
$ewd_upcp_controller = new ewdupcpInit();

do_action( 'ewd_upcp_initialized' );