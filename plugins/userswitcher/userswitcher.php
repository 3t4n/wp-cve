<?php
/**
 * Plugin Name: User Switcher
 * Plugin URI: http://irenemitchell.com/userswitcher
 * Description: A simple tool that allows you to switch between user account without the hassle of logging in/out. It allows you to view, review and do what yours does to your site.
 * Version: 2.0.0
 * Author: Irene A. Mitchell
 * Author URI: http://irenemitchell.com
 * License: GPLv2 or later
 **/



/**

 * @class UserSwitcher

 * @version 2.0.0

 **/
class UserSwitcher {
	/**
	 * Version control
	 *
	 * @var string
	 **/
	private static $version = '1.1.3';

	/**
	 * Indicator if switch is currently on.
	 *
	 * @var bool
	 **/
	private $is_switching = false;

	/**
	 * User ID of currently logged in user
	 *
	 * @var int
	 **/
	private $current_switcher_id = 0;

	/**
	 * User ID of currently switch user.
	 *
	 * @var int
	 **/
	private $user_switch_id = 0;


	/**
	 * The current plugin URI
	 *
	 * @var string
	 **/
	private $plugin_uri = '';

	/**
	 * Indicates if current page is at theme customizer
	 *
	 * @var boolean
	 **/
	private $is_customizer = false;

	static $_instance = null;

	/**
	 * Single instance constructor
	 **/
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		$this->plugin_uri = plugins_url( basename( __DIR__ ) );

		// Check if switch is on!
		add_action( 'plugins_loaded', array( $this, 'maybe_switching' ), 1 );

		// Check currently logged in user

		// Make sure this hook is called first!
		add_action( 'init', array( $this, 'validate_current_user' ), 1 );

		// Clear previous cookies
		add_action( 'wp_login', array( $this, 'clear_cookies' ) );

		// Hide switcher UI when customizer is on
		add_action( 'customize_register', array( $this, 'turn_off' ) );
	}

	public function validate_current_user() {
		global $current_user;

		if ( user_can( $current_user->ID, 'manage_options' ) ) {
			// Get real user's ID
			$this->current_switcher_id = get_current_user_id();

			add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 99 );

			// Set switcher selection
			add_action( 'wp_enqueue_scripts', array( $this, 'set_assets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'set_assets' ) );

			// Set selection box
			add_action( 'admin_footer', array( $this, 'user_selection' ) );
			add_action( 'wp_footer', array( $this, 'user_selection' ) );

			add_filter( 'wp_die_handler', array( $this, 'show_switch_info' ), 99, 3 );

			// Listen to user search request call
			add_action( 'wp_ajax_us_request', array( $this, 'process_request' ) );
			add_action( 'wp_ajax_nopriv_us_request', array( $this, 'process_request' ) );

			// Listen to switch back request call
			add_action( 'wp_ajax_us_restore_account', array( $this, '_restore_account' ) );
			add_action( 'wp_ajax_nopriv_us_restore_account', array( $this, '_restore_account' ) );

			// Check if switch ID is present
			if ( ! empty( $this->user_switch_id ) ) {
				$fake_user = new WP_User( $this->user_switch_id );
				$current_user = $fake_user;
			}
		}
	}

	/**
	 * Add menus to admin bar
	 **/
	public function admin_bar_menu( $admin_bar_menu ) {
		$admin_bar_menu->add_menu( array(
			'id' => 'us-switcher-menu',
			 'title' => '<span class="us-icon us-main-menu">' . __( 'User Switcher' ) . '</span>',
		) );

		$admin_bar_menu->add_menu( array(
			'parent' => 'us-switcher-menu',
			'id' => 'us-to-guest',
			'title' => '<span class="us-icon us-guest-user">' . __( 'Switch to Guest User' ) . '</span>',
		) );

		if ( ! empty( $this->user_switch_id ) ) {
			$admin_bar_menu->add_menu( array(
				'parent' => 'us-switcher-menu',
				'id' => 'us-switch-back',
				'title' => '<span class="us-icon us-switch-back">' . __( 'Switch Back' ) . '</span>',
			) );
		}

		$admin_bar_menu->add_menu( array(
			'parent' => 'us-switcher-menu',
			'id' => 'us-search-users',
			'title' => '<span class="us-icon us-search-users">' . __( 'Search Users' ) . '</span>',
		) );
	}

	/**
	 * Check if the switch occured.
	 **/
	public function maybe_switching() {
		$cookie = $_COOKIE;
		$cookie_name = 'user_switcher_' . COOKIEHASH;

		if ( ! empty( $cookie[ $cookie_name ] ) ) {
			$this->user_switch_id = $cookie[ $cookie_name ];
		}
	}

	/**
	 * Clear switcher cookies whenever the user login.
	 **/
	public function clear_cookies() {
		$cookie_name = 'user_switcher_' . COOKIEHASH;
		$secure = ( 'https' === parse_url( home_url(), PHP_URL_SCHEME ) );
		setcookie( $cookie_name, null, -1, COOKIEPATH, COOKIE_DOMAIN, $secure );
	}

	/**
	 * Set's JS and CSS assets
	 **/
	public function set_assets() {
		// Include CSS
		wp_enqueue_style( 'userswitcher_stylesheet', $this->plugin_uri . '/style.min.css', array( 'dashicons' ), self::$version );

		// Include JS
		wp_enqueue_script( 'userswitcher_js', $this->plugin_uri . '/switch.min.js', array( 'jquery', 'backbone', 'underscore' ), self::$version );

		$localize_array = array(
			'_ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'user_switcher_nonce' ),
			'switcher' => $this->current_switcher_id,
			'switch_to' => $this->user_switch_id,
			'is_admin' => is_admin(),
			'admin_bar' => is_admin_bar_showing(),
			'l8n' => array(
				'title' => __( 'User Switcher' ),
				'description' => __( 'Search users by name, display name, or email.' ),
				'search_placeholder' => __( 'Search ...' ),
				'submit_button' => __( 'Go' ),
				'notice' => array(
					'char_limit' => __( 'Enter at least 3 characters!' ),
				),
				'server_error' => __( 'Something went wrong while processing your request. Please contact your administrator.' ),
				'guest_notice_info' => __( 'You are currently switch to guest user!' ),
				'switch_back' => __( 'Switch Back' ),
				'search_users' => __( 'Search Users' ),
				'closed' => __( 'Closed' ),
				'name' => __( 'User Switcher' ),
				'us_is_on' => __( 'User Switcher Is On' ),
				'switch_to_guest' => __( 'Switch to Guest User' ),
				'prev' => __( 'Previous' ),
				'next' => __( 'Next' ),
			),
		);
		wp_localize_script( 'userswitcher_js', 'userSwitcher', $localize_array );
	}

	public function turn_off() {
		$this->is_customizer = true;
	}

	public function user_selection() {
		if ( $this->is_customizer ) {
			return; // If in customizer, don't show the switcher
		}
		?>
		<script type="text/template" id="user-switcher-window">
		<div class="user-switcher-content">
			<h2><%=userSwitcher.l8n.title%></h2>
			<a class="us-close-icon" title="<%=userSwitcher.l8n.closed%>"></a>
			<p class="description"><%=userSwitcher.l8n.description%></p>
			<form method="post">
				<input type="text" class="us-search-key" name="key" placeholder="<%=userSwitcher.l8n.search_placeholder%>" />
				<button type="submit" class="us-search-submit"><%=userSwitcher.l8n.submit_button%></button>
			</form>
			<div id="us-notice-box"></div>
			<div id="us-search-results"></div>
			<div id="us-navs">
				<button type="button" class="us-prev-button">&laquo; <%=userSwitcher.l8n.prev%></button>
				<button type="button" class="us-next-button"><%=userSwitcher.l8n.next%> &raquo;</button>
			</div>
		</div>
		</script>
		<script type="text/template" id="user-no-admin-bar">
		<div class="us-no-admin-content">
			<p class="description"><%=userSwitcher.l8n.guest_notice_info%></p>
			<a class="us-back">&larr; <%=userSwitcher.l8n.switch_back%></a>
			<a class="us-right us-search"><%=userSwitcher.l8n.search_users%> &rarr;</a>
			</div>
		</script>
		<script type="text/template" id="user-no-admin-bar-admin">
			<div class="us-no-admin-content us-no-admin">
				<% if( 'guest' !== userSwitcher.switch_to ) { %>
				<p class="us-guest-user"><%=userSwitcher.l8n.switch_to_guest%></p>
				<% } %>
				<% if ( '0' !== userSwitcher.switch_to ) { %>
				<p class="us-switch-back"><%=userSwitcher.l8n.switch_back%></p>
				<% } %>
				<p class="us-search-user"><%=userSwitcher.l8n.search_users%></p>
				<p class="description"><%=userSwitcher.l8n.name%></p>
			</div>
		</script>
		<?php
	}

	/**
	 * Validate and retrieve server $_REQUEST
	 *
	 * @return (array) $array					An array of request if successful otherwise false.
	 **/
	protected static function get_request() {
		$request = $_REQUEST;

		if ( ! empty( $request['nonce'] ) && wp_verify_nonce( $request['nonce'], 'user_switcher_nonce' ) ) {

			$request = json_decode( file_get_contents( 'php://input' ) );
			return $request;
		}
		return false;
	}

	/**
	 * Process ajax request and calls it's corresponding method.
	 **/
	public function process_request() {
		$request = self::get_request();

		if ( $request && ! empty( $request->action ) ) {
			$action = $request->action;

			if ( method_exists( $this, $action ) ) {
				$response = call_user_func( array( $this, $action ), $request );
			}
		}
	}

	public function search_users( $input ) {
		$term = $input->term;
		$per_page = 20;
		$paged = ! empty( $input->page ) ? (int) $input->page : 1;
		$offset = ( $paged - 1 ) * $per_page;
		$exclude = array( $this->current_switcher_id );
		$q = explode( ' ', $term );

		$user_query = array(
			'suppress_filters' => true,
			'number' => $per_page,
			'offset' => $offset,
			'meta_query' => array(),
			//'paged' => $paged,
			'exclude' => $exclude,
		);

		$results = array();
		$found_results = 0;

		if ( count( $q ) > 1 ) {
			// Try searching using first and last name
			$user_query['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key' => 'first_name',
					'value' => $q[0],
					'compare' => 'LIKE',
				),
				array(
					'key' => 'last_name',
					'value' => $q[1],
					'compare' => 'LIKE',
				),
			);

			$query = new WP_User_Query( $user_query );
			$get_results = $query->get_results();

			if ( count( $get_results ) > 0 ) {
				$results += $get_results;
				$found_results += $query->get_total();
			}
		} else {

			// Try first name OR last name
			$user_query['meta_query'] = array(
				'relation' => 'OR',
				array(
					'key' => 'first_name',
					'value' => $term,
					'compare' => 'LIKE',
				),
				array(
					'key' => 'last_name',
					'value' => $term,
					'compare' => 'LIKE',
				),
			);

			$query = new WP_User_Query( $user_query );
			$get_results = $query->get_results();

			if ( count( $get_results ) > 0 ) {
				$results += $get_results;
				$found_results += $query->get_total();
			}
		}

		if ( count( $results ) < $per_page ) {
			if ( ! empty( $user_query['meta_query'] ) ) {
				unset( $user_query['meta_query'] );
			}
			if ( ! empty( $user_query['meta_key'] ) ) {
				unset( $user_query['meta_key'], $user_query['meta_value'], $user_query['meta_compare'] );
			}

			$user_query['search'] = $term . '*';
			$user_query['search_columns'] = array(
				'user_login',
				'user_nicename',
				'user_email',
			);

			$query = new WP_User_Query( $user_query );
			$get_results = $query->get_results();

			if ( count( $get_results ) > 0 ) {
				$results += $get_results;
				$found_results += $query->get_total();
			}
		}

		if ( empty( $results ) ) {
			$message = __( 'No users found! Perhaps a different keyword.' );
			wp_send_json_error( array( 'message' => $message ) );
		}

		$items = array();
		if ( count( $results ) > 0 ) {
			$results = array_map( array( $this, 'result_template' ), $results );
			wp_send_json_success(array(
				'users' => $results,
				'total' => $found_results,
			));
		}
	}

	public function result_template( $user ) {
		$display_name = array( $user->first_name, $user->last_name );
		$display_name = array_filter( $display_name );
		$avatar = get_avatar( $user->user_email, 42 );

		if ( empty( $display_name ) ) {
			$display_name = $user->display_name;
		} else {
			$display_name = implode( ' ', $display_name );
		}

		$role = ! empty( $user->roles ) ? ucfirst( $user->roles[0] ) : __( 'No Role' );
		$button = sprintf( '<button type="button" data-id="%s">%s</button>', $user->ID, __( 'Switch' ) );
		$template = sprintf( '<div class="switch_to_user" data-id="%s">%s %s <br /> <em>(%s)</em><span>(%s)</span>%s</div>', $user->ID, $avatar, $display_name, $user->user_login, $role, $button );

		return $template;
	}

	/**
	 * Switch to a different user other than the current.
	 **/
	public function switch_user( $input ) {

		// Let's make sure nothing will prevent us from switching
		ob_start();
		ob_get_clean();

		$user_id = $input->user_id;

		self::set_cookie( 'user_switcher', $user_id, time() + DAY_IN_SECONDS );
		wp_send_json_success( array( 'ok' => true ) );
	}

	/**
	 * Set or unset cookie.
	 *
	 * @param (string) $cookie_name				The name of the cookie. Cookiehash will be appended to the name.
	 * @param (string) $value					The value to store.
	 * @param (mixed) $time						The duraction the cookie will remain.
	 * @return null
	 **/
	protected static function set_cookie( $cookie_name, $value, $time ) {
		$cookie_name .= '_' . COOKIEHASH;
		$secure = ( 'https' === parse_url( home_url(), PHP_URL_SCHEME ) );
		setcookie( $cookie_name, $value, $time, COOKIEPATH, COOKIE_DOMAIN, $secure );
	}

	/**
	 * Helper function to restore account without ajax call.
	 **/
	public function _restore_account() {
		$request = $_REQUEST;
		self::restore_account( (object) $request );
	}

	/**
	 * Restore current user.
	 *
	 * @param (object) $input			The request/post object.
	 **/
	public function restore_account( $input ) {
		// Let's make sure we can switch back without problem
		ob_start();
		ob_get_clean();

		self::set_cookie( 'user_switcher', null, -1 );

		if ( ! empty( $input->ajax ) ) {
			wp_send_json_success( array( 'ok' => true ) );
		} else {
			if ( ! empty( $input->return_url ) ) {
				wp_safe_redirect( $input->return_url );
			}
		}
	}

	/**
	 * Helper function to redirect back to admin dashboard to unuathorized admin pages.
	 **/
	public function show_switch_info( $function ) {
		return array( $this, 'wp_die' );
	}

	public function wp_die( $message, $title, $args = array() ) {
		if ( ! empty( $this->user_switch_id ) ) {
			$switch_back_url = add_query_arg( array(
				'action' => 'us_restore_account',
				'return_url' => admin_url(), // Always return to /dashboard
			), admin_url( 'admin-ajax.php' ) );

			$back = sprintf( '<a style="font-weight:700;text-decoration:none;text-transform:uppercase;" href="%s">&larr; %s</a>', $switch_back_url, __( 'Switch Back' ) );
			$msg = sprintf( '<p>%s %s</p>', __( 'You are currently switch to a user with no admin access!' ), $back );
			$message = $msg . $message;
		}
		_default_wp_die_handler( $message, $title, $args );
	}
}

if ( ! function_exists( 'user_switcher' ) ) {
	/** Calls the single `UserSwitcher` instance **/
	function user_switcher() {
		return UserSwitcher::instance();
	}

	// Now save in global variable
	$GLOBALS['userSwitcher'] = user_switcher();
}

if ( ! function_exists( 'user_switcher_on' ) ) {
	/**
	 * Helper function to check if the switch is currently on going.
	 *
	 * @return (bool)		Returns true if the user is currently switching to a different account.
	 **/
	function user_switcher_on() {
		$switcher_id = user_switcher()->user_switch_id;

		return ! empty( $switcher_id );
	}
}
