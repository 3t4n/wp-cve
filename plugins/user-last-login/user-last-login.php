<?php
/** 
 * Plugin Name: User Last Login
 * Plugin URI:  http://www.online-advertisment.com/blog/user-last-login/
 * Description: Displays login datetime in manage users screen and sorts users by last login time.
 * Version:     1.2
 * Author:      Raj
 * Author URI: http://www.online-advertisment.com/blog/
 **/

class userLastLogin{
	
	public function __construct(){
		// Activation Hook
		register_activation_hook( __FILE__, array($this, 'rk_activation'));
		// Include widget file in plugin
		include_once( plugin_dir_path( __FILE__ ) . 'user-last-login-widget.php');	
		//Save user meta on new registration.
		add_action( 'user_register', array($this, 'registration_create_usermeta'), 10, 1);
		//update usermeta on login
		add_action('wp_login', array($this, 'rk_last_login'), 12, 3);
		//Filter applied to the columns on the manage users screen.
		add_filter('manage_users_columns', array($this, 'add_column'), 12, 3);
		//Hook to manage custom column on manage users screen.
		add_action('manage_users_custom_column',  array($this, 'custom_column_last_login'), 12, 3);
		//Filter to add column as sortable column
		add_filter( 'manage_users_sortable_columns', array($this, 'add_sortable_last_login'), 12, 3);
		// Hook for request
		add_filter('request', array($this, 'last_login_orderby'));
		// Hook for run a query before fetch users
		add_action('pre_user_query',array($this, 'rk_pre_user_query'));
		// Hook for add shortcode of a user
		add_shortcode('user_last_login',array($this, 'rk_last_login_shortcode'));	
	}

	/**
	 * Callback Function for activation hook
	 * Add user meta of all users if not added
	 **/
	public function rk_activation(){
		$users = get_users();
		if(is_array($users) && sizeof($users)>0){
			foreach ($users as $key => $user) {
				$meta = get_user_meta( $user->ID, 'wp-last-login', true );
				if(!$meta){
					update_user_meta($user->ID, 'wp-last-login', 0 );
				}
			}
		}
	}

	/**
	 * Register usermeta key 'wp-last-login' to zero.
	 * @author Raj K 
	 * @since 1.0 - 30-01-2015 
	 * @param int $user_id
	 * @return void
	 **/
	public function registration_create_usermeta( $user_id ) {
		  update_user_meta($user_id, 'wp-last-login', 0);
	}

	/**
	 * Update the login timestamp.
	 * @author Raj K 
	 * @since 1.0 - 30-01-2015 
	 * @param  string $user_login The user's login name.
	 * @return void
	 **/
	public function rk_last_login( $user_login ) {
		$user = get_user_by( 'login', $user_login );
		update_user_meta( $user->ID, 'wp-last-login', time() );
	}

	/**
	 * Adds the last login column to the network admin user list.
	 * @author Raj K 
	 * @since  1.0 - 30-01-2015 
	 * @param  array $cols The default columns.
	 * @return array
	 **/
	public function add_column( $cols ) {
		$cols['wp-last-login'] = __( 'Last Login', 'wp-last-login' );
		return $cols;
	}

	/**
	 * Adds the last login column to the network admin user list.
	 * @author Raj K 
	 * @since 1.0 - 30-01-2015 
	 * @param string $value
	 * @param string $column_name
	 * @param int $user_id
	 * @return string
	 **/
	public function custom_column_last_login( $value, $column_name, $user_id ) {
		if ( 'wp-last-login' == $column_name ) {
			$value      = __( '-', 'wp-last-login' );
			$last_login = (int) get_user_meta( $user_id, 'wp-last-login', true );

			if ( $last_login ) 
				$value  = date_i18n(get_option( 'date_format' ).' '.get_option( 'time_format' ), $last_login );
		}

		return $value;
	}

	/**
	 * Register the column as sortable.
	 * @author Raj K 
	 * @since  1.0 - 30-01-2015 
	 * @param  array $columns
	 * @return array
	 **/
	public function add_sortable_last_login( $columns ) {
	  $columns['wp-last-login'] = 'wp-last-login';
	  return $columns;
	}

	/**
	 * Handle ordering by last login.
	 * @author Raj K 
	 * @since  1.0 - 30-01-2015 
	 * @param  array $vars
	 * @return array
	 **/
	public function last_login_orderby( $vars ) {
		if ( isset( $vars['orderby'] ) && 'wp-last-login' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'wp-last-login',
				'orderby' => 'meta_value',
				'order'     => 'asc'
			) );
		}
		return $vars;
	}

	/**
	 * Handle query for sorting before listing.
	 * @author Raj K 
	 * @since  1.0 - 30-01-2015 
	 * @param  string $user_search
	 * @return array
	 **/
	public function rk_pre_user_query($user_search) {
		global $wpdb,$current_screen;
		if ( 'users' != $current_screen->id ) return;
		$vars = $user_search->query_vars;
		if('wp-last-login' == $vars['orderby']){
			$user_search->query_from .= " INNER JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='wp-last-login')";
			$user_search->query_orderby = ' ORDER BY UPPER(m1.meta_value) '. $vars['order'];
		}
	}
	/**
	 * Shortcode of user last login
	 * @author Raj K 
	 * @since  1.2 - 19-02-2017
	 * @param  string $atts
	 * @return array
	 **/
	public function rk_last_login_shortcode($atts){
		$atts = shortcode_atts(
		array(
			'user_id' => get_current_user_id(),
			'format' => get_option( 'date_format' ).' '.get_option( 'time_format' ) 
		), $atts, 'user_last_login' );
		$last_login = (int) get_user_meta( $atts['user_id'], 'wp-last-login', true );
		return date_i18n($atts['format'], $last_login );
	}

}

$lastLogin = new userLastLogin;
?>