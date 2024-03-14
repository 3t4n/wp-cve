<?php

defined( 'ABSPATH' ) || die( 'No soup for you' );

/**
 * User activity
 */
class Bu_User_Activity extends Bu_Plugin_Base {

	/**
	 * Submenu hook
	 *
	 * @var string
	 */
	private $hook;

	/**
	 * Plugin name
	 *
	 * @var string
	 */
	private $plugin_name = 'user-activity';

	/**
	 * Short name
	 *
	 * @var string
	 */
	private $short_name = 'buua';

	/**
	 * Required privilege
	 * manage_network_users for WordPress multisite
	 * manage_options for single site
	 *
	 * @var string
	 */
	private $privilege;

	/**
	 * Setup function
	 *
	 * @return void
	 */
	public function _setup() {
		add_action( 'admin_menu', array( $this, 'register_user_activity' ) );
	}

	/**
	 * Register user activity, set actions and filters
	 *
	 * @return void
	 */
	public function register_user_activity() {
		global $wp_version;
		$this->privilege = is_multisite() ? 'manage_network_users' : 'manage_options';
		$this->hook      = add_submenu_page(
			'tools.php',
			__( 'User Activity', 'buua' ),
			__( 'User Activity', 'buua' ),
			$this->privilege,
			'user-activity-page',
			[ $this, 'user_activity_page' ]
		);
		add_action( "load-{$this->hook}", [ $this, 'add_screen_options' ] );
		add_filter( 'set-screen-option', [ $this, 'set_screen_option', 10, 3 ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Add number of rows listed as a screen option, default 20.
	 */
	public function add_screen_options() {
		$option = 'per_page';
		$args   = array(
			'label'   => __( '#Users', 'buua' ),
			'default' => 20,
			'option'  => 'users_per_page',
		);
		add_screen_option( $option, $args );
	}

	/**
	 * Set number of rows to display
	 *
	 * @param string $status Status
	 * @param string $option Option
	 * @param string $value  Number of rows
	 */
	public function set_screen_option( $status, $option, $value ) {
		if ( 'users_per_page' === $option ) {
			return $value;
		}
	}

	/**
	 * If empty list, give a warning
	 */
	public function no_items() {
		_e( 'No users. What the what?', 'buua' );
	}

	/**
	 * Enqueue js and css files, the WP way.
	 *
	 * @param string $hook_suffix Hook
	 */
	public function enqueue_scripts( $hook_suffix ) {
		if ( $this->hook == $hook_suffix ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'bu-user-activity', plugins_url() . "/{$this->plugin_name}/js/user-activity.js", [ 'jquery-ui-datepicker' ], '1.0' );
			wp_enqueue_style( 'jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css' );
		}
	}

	/**
	 * Test for superadmin privs and render the table
	 */
	public function user_activity_page() {
		if ( ! current_user_can( $this->privilege ) ) {
			return;
		}
		require_once plugin_dir_path( __FILE__ ) . '/class-bu-user-activity-table.php';

		?>
		<div class="wrap">
			<h2><?php _e( 'User Activity', 'buua' ); ?></h2>
			<?php echo esc_html( $this->user_activity() ); ?>
		</div>
		<?php
	}

	/**
	 * Render the table
	 */
	public function user_activity() {
		global $wpdb;

		$user_activity_table = new Bu_User_Activity_Table();
		$user_activity_table->prepare_items();
		$user_activity_table->views(); ?>
		<form id="activity-filter" method="GET">
			<input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
			<?php $user_activity_table->search_box( __( 'Search users', 'buua' ), 's_user' );
			$user_activity_table->display();
			?>
		</form>
		<?php
	}
}
