<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Xoo_Wl{

	protected static $_instance = null;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	public function __construct(){
		$this->defining_constants();
		$this->includes();
		$this->hooks();
	}


	public function defining_constants(){
		$this->define( "XOO_WL_PATH", plugin_dir_path( XOO_WL_PLUGIN_FILE ) ); // Plugin path
		$this->define( "XOO_WL_PLUGIN_BASENAME",plugin_basename( XOO_WL_PLUGIN_FILE ) );
		$this->define( "XOO_WL_URL", untrailingslashit( plugins_url( '/', XOO_WL_PLUGIN_FILE ) ) ); // plugin url
		$this->define( "XOO_WL_VERSION", "2.5.3" ); //Plugin version
		$this->define( "XOO_WL_LITE", true );
	}


	public function define( $name, $value ){
		if( !defined( $name ) ){
			define( $name, $value );
		}
	}


	/**
	 * File Includes
	*/
	public function includes(){

		//Field framework
		require_once XOO_WL_PATH.'/xoo-form-fields-fw/xoo-aff.php';
		$this->aff = xoo_aff_fire( 'waitlist-woocommerce', 'xoo-wl-fields' ); // start framework
		
		require_once XOO_WL_PATH.'includes/xoo-framework/xoo-framework.php';
		require_once XOO_WL_PATH.'includes/xoo-wl-functions.php';
		require_once XOO_WL_PATH.'includes/class-xoo-wl-helper.php';
		require_once XOO_WL_PATH.'includes/class-xoo-wl-db.php';
		require_once XOO_WL_PATH.'includes/class-xoo-wl-core.php';
		require_once XOO_WL_PATH.'includes/class-xoo-wl-row.php';
		require_once XOO_WL_PATH.'includes/emails/class-xoo-wl-email.php';
		require_once XOO_WL_PATH.'includes/emails/class-xoo-wl-emails.php';

		if($this->is_request('frontend')){
			require_once XOO_WL_PATH.'includes/class-xoo-wl-frontend.php';
		}
		
		if($this->is_request('admin')) {
			require_once XOO_WL_PATH.'admin/class-xoo-wl-admin-settings.php';
			require_once XOO_WL_PATH.'admin/class-xoo-wl-aff-fields.php';
			require_once XOO_WL_PATH.'admin/class-xoo-wl-table-core.php';
		}

	}

	/**
	 * Hooks
	*/
	public function hooks(){
		add_action( 'wp_loaded', array( $this, 'on_install' ) );
		add_action( 'xoo_wl_cron_fetch_old_waitlist', array( $this, 'fetch_old_waitlist' ) );
	}


	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}


	/**
	* On install
	*/
	public function on_install(){

		$version_option = 'xoo-wl-version';
		$db_version 	= get_option( $version_option );

		$this->set_free_settings();

		//If first time installed
		if( !$db_version ){

			//If updated from ^1 fetch old settings and waitlist
			if( $this->hasUpdatedFromOlderVersion() ){
				$this->fetch_old_plugin_settings();
				wp_schedule_single_event( time(), 'xoo_wl_cron_fetch_old_waitlist' );
			}

			//Uncheck out of stock visibility
			update_option( 'woocommerce_hide_out_of_stock_items', false );
			
		}

		if( version_compare( $db_version, XOO_WL_VERSION, '<') ){
			xoo_wl()->aff->fields->set_defaults();
			//Update to current version
			update_option( $version_option, XOO_WL_VERSION);
		}
	}


	/* Fetch old plugin settings */
	public function fetch_old_plugin_settings(){

		//Old settings to be mapped with new key
		$settings = array(
			'xoo-wl-general-options' => array(
				'xoo-wl-gl-enguest' => 'm-en-guest',
				'xoo-wl-gl-enmail' 	=> 'bis-auto-send',
				'xoo-wl-gl-enshop' 	=> 'm-en-shop',
				'xoo-wl-gl-bntxt' 	=> 'txt-btn'
			),
			'xoo-wl-email-options' => array(
				'xoo-wl-emgl-frem' 	=> 's-email',
				'xoo-wl-emgl-frnm'	=> 's-name',
				'xoo-wl-emsy-logo' 	=> 'bis-logo',
			)
		);

		foreach ( $settings as $new_option_key => $mapSettings ) {

			$new_option_value = (array) get_option( $new_option_key, array() );

			foreach ( $mapSettings as $old_key => $new_key ) {

				$old_value 	= get_option( $old_key );
				if( $old_value == 'true' ){
					$old_value = "yes";
				}
				$new_option_value[ $new_key ] = $old_value;
			}

			update_option( $new_option_key, $new_option_value );

		}

	}

	public function set_free_settings(){

		if( !defined( 'XOO_WL_LITE' ) ) return;
		$settings = array(
			'xoo-wl-email-options' => array(
				'bis-keep-wl' 	=> 'no',
				'bis-send-once' => 'yes',
				'bis-auto-send' => 'no'
			)
		);

		foreach ( $settings as $option_name => $option_data ) {
			$option_value = get_option( $option_name, array() );
			foreach ( $option_data as $key => $value ) {
				if( isset( $option_value[ $key ] ) ) continue;
				$option_value[ $key ] = $value;
			}
			update_option( $option_name, $option_value );
		}

	}

	public function fetch_old_waitlist(){

		//Get Waitlisted products
		$posts = get_posts(
			array(
				'post_type'  => array('product','product_variation'),
				'meta_key' 	 => '_xoo-wl-users',
				'posts_per_page' => -1
			)
		);

		foreach ( $posts as $post ) {
			$usersList = (array) json_decode( get_post_meta( $post->ID, '_xoo-wl-users', true ), true );
			if( empty( $usersList ) ) continue;

			foreach ( $usersList as $user_email => $user_data ) {
				if( !$user_email  ) continue;
				$insertData = array(
					'product_id' 	=> $post->ID,
					'email' 		=> $user_email
				);

				if( isset( $user_data['quantity'] ) ){
					$insertData['quantity'] = $user_data['quantity'];
				}

				if( isset( $user_data['joined_on'] ) && $user_data['joined_on'] ){
					$formatDate = str_replace( '/' , ' ', $user_data['joined_on'] ) .' '. date( 'Y' );
					$insertData['join_date'] = date( 'Y-m-d H:i:s', strtotime( $formatDate ) );
				}
				xoo_wl_db()->update_waitlist_row( $insertData );
			}

		}
	}

	public function hasUpdatedFromOlderVersion(){
		return get_option( 'xoo-wl-gl-enguest' ) !== false;
	}


}

?>