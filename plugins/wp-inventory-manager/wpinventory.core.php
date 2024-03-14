<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once "includes/wpinventory.class.php";

/**
 * This is the class that takes care of all the WordPress hooks and actions.
 * The real management takes place in the WPInventory Class
 * @author WP Inventory Manager
 */
class WPInventoryInit extends WPIMCore {

	public static function initialize() {

		self::require_files();

		self::$url  = plugins_url( '', __FILE__ );
		self::$path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;

		self::plugins_loaded();
		self::add_actions();

		// Dependency Injection.  Singleton pattern.
		self::$config = WPIMConfig::getInstance();
		self::$api    = WPIMAPI::getInstance();

		do_action( 'wpim_core_loaded' );
	}

	private static function require_files() {
		require_once "includes/wpinventory.config.class.php";
		require_once "includes/wpinventory.api.class.php";
		require_once "includes/wpinventory.db.class.php";
		require_once "includes/wpinventory.item.class.php";
		require_once "includes/wpinventory.category.class.php";
		require_once "includes/wpinventory.status.class.php";
		require_once "includes/wpinventory.label.class.php";
		require_once "includes/wpinventory.status.class.php";
		require_once "includes/wpinventory.admin.class.php";
		require_once "includes/wpinventory.template.class.php";
		require_once "includes/wpinventory.shortcode.class.php";
		require_once "includes/wpinventory.shortcodes.class.php";
		require_once "includes/wpinventory.widgets.class.php";
		require_once "includes/wpinventory.loop.class.php";
		require_once "includes/wpinventory.search.class.php";
		require_once "includes/wpinventory.support.class.php";
		require_once "includes/wpinventory.bxslideshow.class.php";
		require_once "includes/wpinventory.reserve.class.php";
		require_once "includes/wpinventory.functions.php";
		require_once "includes/wpinventory.promo.class.php";
		require_once "includes/wpinventory.filters.php";
	}

	/**
	 * Set up all the wordpress hooks
	 */
	private static function add_actions() {
		$actions = [
			'init',
			'widgets_init',
			'admin_notices',
			'admin_init',
			'admin_menu',
			'admin_enqueue_scripts',
			'admin_footer',
			'wp_enqueue_scripts',
			'admin_print_footer_scripts',
			'wp_footer',
			'delete_user_form'          => [ 10, 3 ],
			'delete_user'               => [ 10, 3 ],
			'upgrader_process_complete' => [ 10, 2 ]
		];

		foreach ( $actions as $action => $args ) {
			$action   = ( is_array( $args ) ) ? $action : $args;
			$priority = ( is_array( $args ) ) ? $args[0] : 10;
			$num      = ( is_array( $args ) && ! empty( $args[1] ) ) ? $args[1] : 1;


			if ( method_exists( __CLASS__, $action ) ) {
				add_action( $action, [ __CLASS__, $action ], $priority, $num );
			}
		}

		// Filter to handle shortcode on home page
		add_filter( 'redirect_canonical', [ __CLASS__, 'disable_canonical_redirect_for_front_page' ] );

		// Filter to check if user has inventory items (in core WP "delete user" interface)
		add_filter( 'users_have_additional_content', [ __CLASS__, 'users_have_additional_content' ], 10, 2 );

		// Filters necessary for DataTables integration
		add_filter( 'wpim_shortcode_rendered', [ __CLASS__, 'wpim_shortcode_rendered' ] );
		add_filter( 'wpim_display_filter_form', [ __CLASS__, 'wpim_display_filter_form' ] );
		add_filter( 'wpim_display_pagination', [ __CLASS__, 'wpim_display_pagination' ] );
		add_filter( 'wpim_loop_query_args', [ __CLASS__, 'wpim_loop_query_args' ] );
		add_filter( 'cron_schedules', [ __CLASS__, 'wpim_cron_schedules' ] );

		// Handle notice dismissals
		add_action( 'wp_ajax_wpim_notice_handler', [ __CLASS__, 'ajax_notice_handler' ] );

		// Handle daily cron job
		add_action( self::$cron_hook, [ 'WPIMAdmin', 'update_reg_key' ] );

		// Provide rich notification information
		add_action( 'in_plugin_update_message-' . self::$PLUGIN_FILE, [ __CLASS__, 'plugin_update_message' ], 10, 2 );

		self::add_cron_tasks();
	}

	/**
	 * Leverages WP wp_get_schedules hook filter.
	 *
	 * Add support for a weekly cron task
	 */
	public static function wpim_cron_schedules() {
		return [ 'weekly' => [ 'interval' => 604800, 'display' => 'Once Weekly' ] ];
	}

	private static function add_cron_tasks() {
		$next = wp_next_scheduled( self::$cron_hook );
		if ( ! $next ) {
			wp_schedule_event( time(), 'weekly', self::$cron_hook );
		}
	}

	/**
	 * WordPress plugins_loaded action callback.  We use this to initialize the loading of any WP Inventory add-ons
	 */
	public static function plugins_loaded() {
	  do_action( 'wpim_load_add_ons' );
	}

	public static function plugin_update_message( $data, $response ) {
		if ( isset( $data['upgrade_notice'] ) ) {
			printf(
				'<div class="update-message">%s</div>',
				wpautop( $data['upgrade_notice'] )
			);
		}
	}

	/**
	 * Ensure plugin versions are force-reloaded after any WPIM plugin is upgraded.
	 *
	 * @param object $upgrader_object
	 * @param array  $options
	 */
	public static function upgrader_process_complete( $upgrader_object, $options ) {
		if ( empty( $options['plugins'] ) ) {
			return;
		}

		$plugins = $options['plugins'];
		$plugins = array_filter( $plugins, function ( $plugin ) {
			return ( FALSE !== stripos( $plugin, 'wpinventory' ) );
		} );

		if ( empty( $plugins ) ) {
			return;
		}

		self::deleted_site_transient( 'update_plugins' );
	}

	/**
	 * WordPress init action callback function
	 */
	public static function init() {
		add_shortcode( self::SHORTCODE, [ __CLASS__, 'shortcode' ] );

		// Enable internationalization
		if ( ! load_plugin_textdomain( 'wpinventory', FALSE, '/wp-content/languages/' ) ) {
			load_plugin_textdomain( 'wpinventory', FALSE, basename( dirname( __FILE__ ) ) . "/languages/" );
		}

		add_action( 'wp_ajax_wpim_send_support', [ self::SUPPORT_CLASS, 'ajax_send_support' ] );

		self::setup_seo_endpoint();
		self::after_activation();
	}

	private static function after_activation() {
		if ( empty( get_option( 'wp_inventory_rewrite' ) ) ) {
			return;
		}

		// on initial install, permalinks won't work properly...
		flush_rewrite_rules();
		delete_option( 'wp_inventory_rewrite' );
	}


	/**
	 * WordPress widgets_init action callback function
	 */
	public static function widgets_init() {
		register_widget( 'WPInventory_Categories_Widget' );
		register_widget( 'WPInventory_Latest_Items_Widget' );
	}

	/**
	 * WordPress admin_notices action callback
	 */
	public static function admin_notices() {
		if ( ! self::is_wpinventory_page() && self::notice_dismissed( 'core-license' ) ) {
			return;
		}
	}

	/**
	 * WordPress admin_init action callback function
	 */
	public static function admin_init() {
		register_setting( self::SETTINGS_GROUP, self::SETTINGS );
		self::$options = get_option( self::SETTINGS );
		wp_enqueue_style( 'inventory-admin-style', self::$url . 'css/style-admin.css' );

		self::admin_call( 'admin_init' );
	}

	/**
	 * WordPress admin_menu action callback function
	 */
	public static function admin_menu() {
		$lowest_role   = self::$config->get( 'permissions_lowest_role' );
		self::$pages[] = self::MENU;
		add_menu_page( self::__( 'WP Inventory' ), self::__( 'WP Inventory' ), $lowest_role, self::MENU, [
			__CLASS__,
			'instructions'
		], self::$url . 'images/admin-menu-icon.png' );

		$count = (int) self::$config->get( 'status_notifications' );
		if ( $count ) {
			$count = ' <span class="awaiting-mod count-' . $count . '"><span class="mod-count">' . $count . '</span></span>';
		} else {
			$count = '';
		}

		self::add_submenu( self::__( 'Status' . $count ), self::MENU, $lowest_role, '', 'instructions' );
		self::add_submenu( self::__( 'Inventory Items' ), 'wpim_manage_inventory_items', $lowest_role );
		self::add_submenu( self::__( 'Categories' ), 'wpim_manage_categories' );

		if ( ! apply_filters( 'wpim_suppress_admin_menu_labels', FALSE ) ) {
			self::add_submenu( self::__( 'Labels' ), 'wpim_manage_labels' );
		}

		if ( ! apply_filters( 'wpim_suppress_admin_menu_display', FALSE ) ) {
			self::add_submenu( self::__( 'Display' ), 'wpim_manage_display' );
		}

		self::add_submenu( self::__( 'Statuses' ), 'wpim_manage_statuses' );
		do_action( 'wpim_admin_menu' );
		self::add_submenu( self::__( 'Add Ons' ), 'wpim_manage_add_ons' );
		self::add_submenu( self::__( 'Settings' ), 'wpim_manage_settings' );

		if ( ! apply_filters( 'wpim_suppress_admin_menu_support', FALSE ) ) {
			self::add_submenu( self::__( 'Support' ), 'wpim_manage_support', $lowest_role, self::SUPPORT_CLASS );
		}

		self::$pages = apply_filters( 'wpim_admin_pages', self::$pages );
	}

	/**
	 * Utility function to simplify adding submenus
	 *
	 * @param string $title
	 * @param string $slug
	 * @param string $role
	 * @param string $class
	 * @param string $method
	 */
	private static function add_submenu( $title, $slug, $role = 'manage_options', $class = '', $method = '' ) {
		if ( ! $class || ! class_exists( $class ) ) {
			$class = __CLASS__;
		}

		if ( ! $method ) {
			$method = 'admin_' . str_ireplace( 'wpim_manage_', '', $slug );
		}

		add_submenu_page( self::MENU, $title, $title, $role, $slug, [
			$class,
			$method
		] );

		self::$pages[] = $slug;
	}

	public static function users_have_additional_content( $has_content, $userids ) {
		if ( $has_content ) {
			return $has_content;
		}

		$items = new WPIMItem();
		$count = 0;

		foreach ( $userids AS $user_id ) {
			$count += (int) $items->get_all( [ 'user_id' => $user_id ], TRUE );
			$count += (int) $items->get_all( [ 'inventory_updated_by' => $user_id ], TRUE );
			if ( $count ) {
				break;
			}
		}

		return $count;
	}

	public static function delete_user( $id, $reassign, $user ) {
		$items = new WPIMItem();
		$items->reassign_user( $id, $reassign );
	}

	public static function delete_user_form( $current_user, $userids ) {
		$count = self::users_have_additional_content( FALSE, $userids );
		echo '<p><strong>' . sprintf( self::__( 'Content includes %d inventory items.' ), $count ) . '</strong></p>';
	}

	public static function admin_print_footer_scripts() {
		$themes = self::load_available_themes();
		?>
      <script>var wpinventory_themes = <?php echo json_encode( $themes ); ?>;
        // Support dismissable nags.  Required on all pages, not just WPIM pages.
        jQuery( function( $ ) {
          $( document ).on( 'click', '.notice-wpinventory .notice-dismiss', function() {
            var type = $( this ).closest( '.notice-wpinventory' ).data( 'notice' );
            $.ajax( ajaxurl,
              {
                type: 'POST',
                data: {
                  action: 'wpim_notice_handler',
                  type: type,
                  nonce: '<?php echo wp_create_nonce( self::NONCE_ACTION ); ?>'
                }
              } );
          } );

          if ( $( 'select.wpinventory_themes' ).length ) {
            $( 'select.wpinventory_themes' ).change(
              function() {
                var theme_name = $( this ).val();
                var screenshot;
                if ( wpinventory_themes[ theme_name ] ) {
                  screenshot = wpinventory_themes[ theme_name ][ 'screenshot' ];
                }
                if ( typeof screenshot != 'undefined' ) {
                  $( '<img src="' + screenshot + '">' ).on( 'load',
                    function() {
                      $( '.theme_screenshot' ).empty().append( $( this ) );
                    }
                  )
                }
              }
            ).trigger( 'change' );
          }
        } );</script>
		<?php
	}

	public static function wp_footer() {
		if ( apply_filters( 'wpim_enqueue_toastr', FALSE ) ) {
			wp_enqueue_style( 'wpinventory-toastr', self::$url . 'vendor/toastr/toastr.custom.min.css' );
			wp_print_scripts( 'wpinventory-toastr' );
			wp_print_styles( 'wpinventory-toastr' );
		}

		if ( apply_filters( 'wpim_enqueue_datatables', FALSE ) ) {
			wp_enqueue_style( 'wpinventory-datatables', self::$url . 'vendor/DataTables/datatables.min.css' );
			wp_print_scripts( 'wpinventory-datatables' );
			wp_print_styles( 'wpinventory-datatables' );
		}

		if ( apply_filters( 'wpim_enqueue_sweetalert', FALSE ) ) {
			wp_print_scripts( 'wpinventory-sweetalert' );
		}

		if ( apply_filters( 'wpim_enqueue_select2', FALSE ) ) {
			wp_print_scripts( 'wpinventory-select2' );
			wp_print_styles( 'wpinventory-select2' );
		}

		if ( apply_filters( 'wpim_enqueue_stepper', TRUE ) ) {
			wp_print_scripts( 'wpinventory-stepper' );
			self::number_input_hide_steppers();
		}

		do_action( 'wpim_footer' );
	}

	public static function admin_footer() {
		if ( apply_filters( 'wpim_admin_enqueue_toastr', FALSE ) ) {
			wp_enqueue_style( 'wpinventory-toastr', self::$url . 'vendor/toastr/toastr.custom.min.css' );
			wp_print_scripts( 'wpinventory-toastr' );
			wp_print_styles( 'wpinventory-toastr' );
		}

		if ( apply_filters( 'wpim_admin_enqueue_sweetalert', FALSE ) ) {
			wp_print_scripts( 'wpinventory-sweetalert' );
		}

		if ( apply_filters( 'wpim_admin_enqueue_select2', FALSE ) ) {
			wp_print_scripts( 'wpinventory-select2' );
			wp_print_styles( 'wpinventory-select2' );
		}

		if ( apply_filters( 'wpim_admin_enqueue_stepper', TRUE ) ) {
			wp_print_scripts( 'wpinventory-stepper' );
			self::number_input_hide_steppers();
		}
	}

	private static function number_input_hide_steppers() {
		if ( ! apply_filters( 'wpim_number_input_styles', TRUE ) ) {
			return;
		}

		echo <<<STYLE
<style>
[class*="wpim"] input[type="number"]::-webkit-outer-spin-button,
[class*="wpiinventory"] input[type="number"]::-webkit-outer-spin-button,
[class*="wpim"] input[type="number"]::-webkit-inner-spin-button,
[class*="wpinventory"] input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

[class*="wpim"] input[type="number"],
[class*="wpinventory"] input[type="number"] {
    -moz-appearance: textfield;
}

.wpim-stepper {
position: relative;
display: flex;
align-items: stretch;
}

.wpim-stepper input {
width: 50px;
}

.wpim-stepper .stepper-arrow-container {
display: flex;
height: 100%;
flex-direction: column;
justify-content:center;
}

.wpim-stepper .stepper-arrow {
display: block;
border: 1px solid #ccc;
margin: 0;
padding: 0 5px;
line-height: 13px;
font-size: 13px;
margin-left: 2px;
margin-bottom: -1px;
background: #eee;
cursor: pointer;
}

</style>
STYLE;
	}

	public static function shortcode( $args ) {
		self::$shortcode = WPIMShortcode::getInstance();

		self::$shortcode_rendered = apply_filters( 'wpim_shortcode_rendered', TRUE );

		return self::$shortcode->get( $args );
	}

	public static function instructions() {
		self::admin_call( "instructions" );
	}

	public static function admin_inventory_items() {
		self::admin_call( "wpim_manage_inventory_items" );
	}

	public static function admin_categories() {
		self::admin_call( "wpim_manage_categories" );
	}

	public static function admin_labels() {
		self::admin_call( "wpim_manage_labels" );
	}

	public static function admin_display() {
		self::admin_call( "wpim_manage_display" );
	}

	public static function admin_settings() {
		self::admin_call( "wpim_manage_settings" );
	}

	public static function admin_statuses() {
		self::admin_call( "wpim_manage_statuses" );
	}

	public static function admin_add_ons() {
		self::admin_call( "wpim_manage_add_ons" );
	}

	public static function admin_call( $method, $args = NULL ) {
		self::$admin = WPIMAdmin::getInstance();
		return self::$admin->{$method}( $args );
	}

	public static function setup_seo_endpoint() {
		// Add the query var filter
		add_filter( 'query_vars', [ __CLASS__, 'rewrite_variables' ] );

		$seo_urls = (int) self::$config->get( "seo_urls" );

		// add item as a possible "tail" item
		if ( $seo_urls ) {

			$seo_endpoint = self::$config->get( "seo_endpoint", 'inventory' );
			add_rewrite_endpoint( $seo_endpoint, EP_ALL );

			// Ensures the $query_vars['item'] is available
			add_rewrite_tag( "%{$seo_endpoint}%", '([^&]+)' );

			// Requires flushing endpoints whenever the
			// front page is switched to a different page
			$page_on_front = get_option( 'page_on_front' );

			// Match the front page and pass item value as a query var.
			add_rewrite_rule( "^{$seo_endpoint}/([^/]*)/?", 'index.php?page_id=' . $page_on_front . '&' . $seo_endpoint . '=$matches[1]', 'top' );
			// Match non-front page pages.
			add_rewrite_rule( "^(.*)/{$seo_endpoint}/([^/]*)/?", 'index.php?pagename=$matches[1]&static=true&' . $seo_endpoint . '=$matches[2]', 'top' );

			if ( get_transient( 'wpim_flush_rewrite_rules' ) ) {
				flush_rewrite_rules();
				delete_transient( 'wpim_flush_rewrite_rules' );
			}
		}
	}

	/**
	 * If the shortcode is displayed on the home page, then we need to disable canonical redirects on the home page.
	 *
	 * @param string $redirect
	 *
	 * @return bool
	 */
	public static function disable_canonical_redirect_for_front_page( $redirect ) {
		if ( ! self::$config->get( 'shortcode_on_home' ) ) {
			return $redirect;
		}

		if ( is_page() ) {
			$front_page = get_option( 'page_on_front' );
			if ( $front_page && is_page( $front_page ) ) {
				$redirect = FALSE;
			}
		}

		return $redirect;
	}

	// add seo rewrite endpoint as an allowed query var
	public static function rewrite_variables( $public_query_vars ) {
		// add item as a possible "tail" item
		if ( self::$config->get( 'seo_urls', FALSE ) ) {
			$seo_endpoint        = self::$config->get( 'seo_endpoint', 'wpinventory' );
			$public_query_vars[] = $seo_endpoint;
		}

		return $public_query_vars;
	}

	/**
	 * Filter on whether the shortcode is rendered or not.
	 * Allows awareness when the shortcode is rendered, and run actions / filters.
	 *
	 * @param bool $rendered
	 *
	 * @return bool mixed
	 */
	public static function wpim_shortcode_rendered( $rendered ) {
		if ( $rendered && self::USE_DATATABLES === (int) self::$config->get( 'display_listing_table' ) ) {
			add_filter( 'wpim_enqueue_datatables', function () {
				return TRUE;
			} );
		}

		return $rendered;
	}

	/**
	 * Filter flag to determine if filter form should be displayed on front-end.
	 *
	 * @param bool $display
	 *
	 * @return bool
	 */
	public static function wpim_display_filter_form( $display ) {
		return self::wpim_hide_if_datatable( $display );
	}

	/**
	 * Filter flag to determine if pagination should be displayed on front-end.
	 *
	 * @param bool $display
	 *
	 * @return bool
	 */
	public static function wpim_display_pagination( $display ) {
		return self::wpim_hide_if_datatable( $display );
	}

	/**
	 * Filter to update query args.  Currently only used to reset some values if DataTables being used.
	 *
	 * @param $args
	 *
	 * @return mixed
	 */
	public static function wpim_loop_query_args( $args ) {
		if ( self::wpim_hide_if_datatable( FALSE, TRUE ) ) {
			$args['page']      = 0;
			$args['page_size'] = 0;
		}

		return $args;
	}

	public static function wpim_hide_if_datatable( $default, $if_true = FALSE ) {
		if ( is_admin() ) {
			return $default;
		}

		if ( self::USE_DATATABLES === (int) self::$config->get( 'display_listing_table' ) ) {
			return $if_true;
		}

		return $default;
	}

	/**
	 * WordPress admin_enqueue_scripts action callback function
	 */
	public static function admin_enqueue_scripts() {
		if ( self::is_wpinventory_page() ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-accordion' );

			wp_enqueue_script( 'wplink' );
			wp_enqueue_style( 'editor-buttons' );

			// Check WP version to get the best version of media upload
			$wp_version = get_bloginfo( 'version' );
			if ( (float) $wp_version >= 3.5 ) {
				wp_enqueue_media();
			} else {
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_style( 'thickbox' );
			}

			wp_register_script( 'wpinventory-admin', self::$url . 'js/wpinventory-admin.js' );
			$admin_script_variables = [
				'pluginUrl'                    => self::$url,
				'ajaxUrl'                      => admin_url( 'admin-ajax.php' ),
				'nonce'                        => wp_create_nonce( self::NONCE_ACTION ),
				'image_label'                  => self::__( 'Images' ),
				'image_label_singular'         => self::__( 'Image' ),
				'media_label'                  => self::__( 'Media' ),
				'media_label_singular'         => self::__( 'Media' ),
				'insert_button'                => self::__( 'Use In Item' ),
				'url_label'                    => self::__( 'URL' ),
				'title_label'                  => self::__( 'Title' ),
				'delete_prompt'                => self::__( 'Are you sure you want to delete' ),
				'delete_general'               => self::__( 'this item' ),
				'delete_named'                 => self::__( 'the item' ),
				'save_error'                   => self::__( 'Either %s or %s is required.' ),
				'prompt_qm'                    => self::__( '?' ),
				'support_message_error'        => self::__( 'Please describe your issue or request as completely as possible.' ),
				'support_sending'              => self::__( 'Sending...' ),
				'currency_symbol'              => self::$config->get( 'currency_symbol' ),
				'currency_symbol_location'     => self::$config->get( 'currency_symbol_location' ),
				'currency_thousands_separator' => self::$config->get( 'currency_thousands_separator' ),
				'currency_decimal_separator'   => self::$config->get( 'currency_decimal_separator' ),
				'currency_decimal_precision'   => self::$config->get( 'currency_decimal_precision' )
			];

			$admin_script_variables = apply_filters( 'wpim_admin_localize_script', $admin_script_variables );

			wp_localize_script( 'wpinventory-admin', 'wpinventory', $admin_script_variables );
			wp_enqueue_script( 'wpinventory-admin' );

			wp_enqueue_style( 'wpinventory', self::$url . 'css/style-admin.css' );

			self::register_common_scripts();
			self::load_font_awesome();
		}
	}

	/**
	 * Attempt to prevent loading if it's already loaded!  No need to load multiple times.
	 */
	public static function load_font_awesome() {
		global $wp_styles;
		$styles = array_map( 'basename', (array) wp_list_pluck( $wp_styles->registered, 'src' ) );
		if ( ! in_array( 'font-awesome.css', $styles ) && ! in_array( 'font-awesome.min.css', $styles ) ) {
			wp_enqueue_style( 'font-awesome', self::$url . 'vendor/font-awesome-4.7.0/css/font-awesome.min.css', [], '4.7.0' );
		}
	}

	/**
	 * Wordpress enqueue scripts for the frontend
	 */
	public static function wp_enqueue_scripts() {
		$theme = self::$config->get( 'theme' );
		if ( $theme ) {
			$theme = self::get_theme_url( $theme );
			wp_enqueue_style( 'wpinventory-theme', $theme, [], self::VERSION );
		} else {
			echo '<!-- ' . self::__( 'WP Inventory styles not loaded due to settings in dashboard.' ) . '-->' . PHP_EOL;
		}

		wp_register_script( 'wpinventory-common', self::$url . 'js/wpinventory.js', [ 'jquery' ], self::VERSION, TRUE );

		$localize_array = [
			'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
			'ajaxNonce' => wp_create_nonce( self::NONCE_ACTION )
		];

		$localize_array = apply_filters( 'wpim_localize_script', $localize_array );

		wp_localize_script( 'wpinventory-common', 'wpinventory', $localize_array );
		wp_enqueue_script( 'wpinventory-common' );

		if ( wpinventory_is_single() && wpinventory_get_config( 'bxslideshow_mode' ) ) {
			wp_enqueue_script( 'jquery' );
			wp_register_script( 'wpinventory_bxslideshow', self::$url . 'js/jquery.bxslider.min.js', [ 'jquery' ], self::VERSION );
			wp_enqueue_style( 'wpinventory_bxslideshow', self::$url . 'css/jquery.bxslider.cust.css', [], self::VERSION );
		}

		self::register_common_scripts();
	}

	public static function register_common_scripts() {
		wp_register_script( 'wpinventory-stepper', self::$url . 'js/jquery.stepper.js', [ 'jquery' ] );
		wp_register_script( 'wpinventory-toastr', self::$url . 'vendor/toastr/toastr.min.js', [ 'jquery' ], self::VERSION, TRUE );
		wp_register_script( 'wpinventory-datatables', self::$url . 'vendor/DataTables/datatables.min.js', [ 'jquery' ], self::VERSION, TRUE );
		wp_register_script( 'wpinventory-sweetalert', self::$url . 'vendor/sweetalert/sweetalert.min.js' );
		wp_register_script( 'wpinventory-select2', self::$url . 'vendor/select2/dist/js/select2.js' );
		wp_register_style( 'wpinventory-select2', self::$url . 'vendor/select2/dist/css/select2.min.css' );
		wp_add_inline_style( 'wpinventory-select2', '.select2-container .select2-selection--single .select2-selection__rendered { text-overflow: clip; }' );
	}

	/**
	 * When a plugin updates, re-load the latest versions of the WPIM add-ons to prevent issues
	 *
	 * @param $transient
	 */
	public static function deleted_site_transient( $transient ) {
		if ( 'update_plugins' == $transient ) {
			delete_transient( 'wpim_full_add_ons' );
		}
	}

	/**
	 * AJAX handler to store the state of dismissible notices.
	 */
	public static function ajax_notice_handler() {
		$nonce = self::request( 'nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			echo 'security error';
			die();
		}

		$type = self::request( 'type' );
		self::notice_dismissed( $type, TRUE );
		die();
	}
}

