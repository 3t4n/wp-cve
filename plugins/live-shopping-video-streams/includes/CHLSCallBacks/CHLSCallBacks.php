<?php

/**
 * 
 * file contains all the call backs function
 * 
 * @package Channelize Shopping
 */



namespace Includes\CHLSCallBacks;

defined('ABSPATH') || exit;

use Includes\Libraries\Client;
use Includes\Libraries\User;

class CHLSCallBacks
{
	/** creating admin menu **/
	public function chls_add_admin_menu()
	{

		add_menu_page(
			'Live Shopping & Video Streams',
			'Live Shopping & Video Streams',
			'manage_options',
			'channelize_live_shopping',
			array($this, 'chls_admin_index'),
			'dashicons-media-video',
			999
		);
		add_submenu_page(
			'channelize_live_shopping',
			'Channelize Live Shopping Configuration',
			'Configuration',
			'manage_options',
			'channelize_live_shopping'
		);
		add_submenu_page(
			'channelize_live_shopping',
			'Channelize Live Shopping Page',
			'Live Shop Page',
			'manage_options',
			'channelize_live_shop_page',
			array($this, 'channelize_live_shop_page')
		);


		add_submenu_page(
			'channelize_live_shopping',
			'Channelize Live Shopping Instructions',
			'Instructions',
			'manage_options',
			'channelize_instructions',
			array($this, 'channelize_instructions')
		);
		add_submenu_page(
			'channelize_live_shopping',
			'Channelize Live Shopping Settings',
			'Settings',
			'manage_options',
			'channelize_settings',
			array($this, 'channelize_live_shop_settings')
	    );
	}

	public function chls_admin_index()
	{
		require_once CHLS_PLUGIN_PATH . 'templates/admin.php';
	}




	/* getting access token for login user.*/

	public function channelize_live_shop_user_sync_login()
	{
		$user_data  = wp_get_current_user();
		$user_login = $user_data->user_login;
		$config             = get_option('channelize_live_shopping');
		try {
			if (isset($_COOKIE['channelize_live_shop_access_token']) && !is_user_logged_in()) {
				unset($_COOKIE['channelize_live_shop_access_token']);
				setcookie('channelize_live_shop_access_token', null, -1, '/');
			}
			if ((!isset($_COOKIE['channelize_live_shop_access_token']) || !isset($_COOKIE['channelize_public_key'])) && is_user_logged_in()) {

				$this->channelize_live_shop_user_login($user_login, $user_data);
			}
			/**
			 * if admin change the private and public key 
			 */
			if (isset($_COOKIE['channelize_live_shop_access_token']) && is_user_logged_in() && isset($_COOKIE['channelize_public_key'])) {
				if ($config['public_key'] != $_COOKIE['channelize_public_key']) {
					$this->channelize_live_shop_user_login($user_login, $user_data);
				}
			}
		} catch (\Throwable $th) {
			if (WP_DEBUG === true) {
				error_log(print_r($th->getMessage(), true));
			}
		}
	}
	/**
	 * When User try to login
	 */

	public function channelize_live_shop_user_login($user_login, $user)
	{

		try {
			$config             = get_option('channelize_live_shopping');
			Client::$privateKey = isset($config['private_key']) ? $config['private_key'] : '';
			$user_api           = new User();
			$user_data          = array('userId' => (string) $user->ID,);
			$res                = $user_api->createAccessToken($user_data);
			$data               = json_decode($res->getBody());
			$access_token       = $data->id;


			setcookie('channelize_live_shop_access_token', $access_token, time() + (365 * 24 * 60 * 60), '/');
			setcookie('channelize_public_key', $config['public_key'], time() + (365 * 24 * 60 * 60), '/');
			$_COOKIE['channelize_live_shop_access_token'] = $access_token;
		} catch (\Throwable $th) {
			if (WP_DEBUG === true) {
				error_log(print_r($th->getMessage(), true));
			}
			$get_expection_class = get_class($th);
			$no_record_found     = 'NoRecordFoundException';
			if (strpos($get_expection_class, $no_record_found) !== false) {
				$this->channelize_live_shop_user_register($user->ID);
				$this->channelize_live_shop_user_register($user_login, $user);
			}
		}
	}

	/* 
	*for user registration on channelize.
	*/
	public function channelize_live_shop_user_register($user_id)
	{

		try {
			$user      = get_userdata($user_id);
			$user_data = array(
				'id'              => $user->ID,
				'displayName'     => $user->display_name,
				'profileImageUrl' => get_avatar_url($user_id),
			);

			$config             = get_option('channelize_live_shopping');
			Client::$privateKey = isset($config['private_key']) ? $config['private_key'] : '';
			$user_api           = new User();
			$user_api->create($user_data);
		} catch (\Throwable $th) {
			if (WP_DEBUG === true) {
				error_log(print_r($th->getMessage(), true));
			}
		}
	}



	/**
	 * Logout user from channelize.io Chat.
	 *
	 */
	public function channelize_live_shop_user_logout()
	{
		$channelize_live_shop_access_token = null;
		if (isset($_COOKIE['channelize_live_shop_access_token'])) {
			if (!empty(sanitize_text_field(wp_unslash($_COOKIE['channelize_live_shop_access_token'])))) {
				$channelize_live_shop_access_token = sanitize_text_field(wp_unslash($_COOKIE['channelize_live_shop_access_token']));
			}
		}
		try {
			$config             = get_option('channelize_live_shopping');
			Client::$privateKey = isset($config['private_key']) ? $config['private_key'] : '';
			Client::$userId     = get_current_user_id();
			$user_api           = new User();
			$access_token       = $channelize_live_shop_access_token;
			$logout_data        = array(
				'deviceId'    => '',
				'accessToken' => $access_token,
			);
			$user_api->logout($logout_data);
		} catch (\Throwable $th) {
			if (WP_DEBUG === true) {
				error_log(print_r($th->getMessage(), true));
			}
		}
		setcookie('channelize_live_shop_access_token', '', time() - 3600, '/');
		setcookie('channelize_public_key', '', time() - 3600, '/');
	}


	/**
	 * Update user profile from channelize.io Chat.
	 *
	 */


	public function channelize_live_shop_wp_profile_update($user_id, $old_user_data)
	{

		$user        = get_userdata($user_id);
		$fields      = array(
			'user_email'   => 'email',
			'display_name' => 'displayName',
		);
		$update_data = array();
		foreach ($fields as $wp_field => $ch_field) {
			if ($old_user_data->$wp_field !== $user->$wp_field) {
				$update_data[$ch_field] = $user->$wp_field;
			}
		}
		if (count($update_data)) {
			$this->channelize_live_shop_user_update($user_id, $update_data);
		}
	}

	/**
	 * Delete user from channelize.io Chat.
	 *
	 */

	public function channelize_live_shop_user_delete($user_id)
	{

		try {
			$config             = get_option('channelize_live_shopping');
			Client::$privateKey = isset($config['private_key']) ? $config['private_key'] : '';
			$user_api           = new User($user_id);
			$user_api->delete();
		} catch (\Throwable $th) {
			if (WP_DEBUG === true) {
				error_log(print_r($th->getMessage(), true));
			}
		}
	}

	/**
	 * Update user from channelize.io Chat.
	 *
	 */

	public function channelize_live_shop_user_update($user_id, $user_data)
	{

		try {
			$config             = get_option('channelize_live_shopping');
			Client::$privateKey = isset($config['private_key']) ? $config['private_key'] : '';
			$user_api           = new User($user_id);
			$user_api->update($user_data);
		} catch (\Throwable $th) {
			if (WP_DEBUG === true) {
				error_log(print_r($th->getMessage(), true));
			}
		}
	}

	/**
	* Commerce analysis for liveshow Shopping
	*/	

	public function channelize_live_shop_sales_analytics($order_id) 
	{ 
		$order = wc_get_order( $order_id );
		$currency = $order->get_currency();
		$orderItems = array();
		foreach ( $order->get_items() as $item_id => $item ) 
		{
			$product_id = $item->get_product_id(); 
			$subtotal   = $item->get_total();       
			$total      = $item->get_subtotal();   
			$tax        = $item->get_total_tax();
			$product    = $item->get_product();
		
			array_push($orderItems, array("product_id" => strval($product_id), "subtotal" => $subtotal, "total" => $total, "tax" => $tax));
		} 
	?>
		<script type="text/javascript">

			function onPurchase()
			{
				var trackedProducts =  window.ChannelizeLiveBroadcastAnalytics.getTrackedProducts();
				if(!trackedProducts) return;
				
				var order_id = <?php echo $order_id ?> ;
				var currency = '<?php echo $currency ?>';
				var user_id = <?php echo get_current_user_id()?>;
				var orderItems = <?php echo json_encode($orderItems) ?>;

				var filteredLineItems = orderItems.filter(n => trackedProducts.indexOf(n.product_id)>= 0 );
				if (!filteredLineItems || !filteredLineItems.length) return ;

				var orderAmount =  filteredLineItems.reduce(function ( sum ,lineitem){
                	return sum + parseFloat(lineitem.subtotal) + parseFloat(lineitem.tax);
				}, 0);

				window.ChannelizeLiveBroadcastAnalytics.trackActivity({
					name: "PURCHASE",
					user: {
						id: user_id.toString()
					},
					data: {
						orderId: order_id.toString(),
						products: filteredLineItems.map(n => ({id: n.product_id})),
						orderAmount: orderAmount,
						currency: currency
					}
				});
			}
			var element = document.createElement('script');      
			element.src = 'https://cdn.channelize.io/live-broadcast/analytics/sdk/prod/2.0.0/browser.js';
			element.onload = onPurchase;
			document.head.appendChild(element);

		</script>

	<?php
	}



	/**
	 * Load js file for streams page
	 *
	 * @param  array  $dataToBePassed  The list of date to pass in js file
	 */
	public function chls_load_ui_kit_js_files()
	{
		//load admin js file
		$streamPageSlug = chls_get_main_stream_page_details();
		if (is_page(array($streamPageSlug))) {
			$config  = get_option('channelize_live_shopping');
			global $wpdb;
			$currency = get_woocommerce_currency();
			$locale = get_locale();
			$siteUrl = get_site_url();
			$_nonce = wp_create_nonce('wc_store_api');
			$loginUrl = wp_login_url();
			$cartUrl = wc_get_cart_url();
			$settings = get_option('channelize_live_shopping_settings');
			$streamPageUrl = get_permalink(get_page_by_path(chls_get_main_stream_page_details()));
			$dataToBePassed = array(
				'publicKey'         =>     isset($config['public_key']) ? $config['public_key'] : null,
				'userId'			=>     get_current_user_id(),
				'accessToken'       =>     isset($_COOKIE['channelize_live_shop_access_token']) ? $_COOKIE['channelize_live_shop_access_token'] : '',
				'currency'          =>     isset($currency) ? $currency : null,
				'locale'            =>     isset($locale) ? $locale : null,
				'url'               =>     isset($siteUrl) ? $siteUrl : null,
				'nonce'             =>     isset($_nonce) ? $_nonce : null,
				'streamPageUrl'     =>     isset($streamPageUrl) ? $streamPageUrl : null,
				'loginUrl'     		=>     isset($loginUrl) ? $loginUrl : null,
				'cartUrl'	        =>     isset($cartUrl) ? $cartUrl : null,
				'enableMiniPlayer'  =>     isset($settings['enableMiniPlayer']) ? 
				                                 $settings['enableMiniPlayer'] : null, 
			);
			wp_register_script('channelize-live-shopping-stream-js', plugins_url('/js/stream.js', dirname(__FILE__, 1)), array(), filemtime(plugin_dir_path(dirname(__FILE__)) . 'js/stream.js'), '1.0.0', true);
			wp_enqueue_script('channelize-live-shopping-stream-js');
			wp_localize_script('channelize-live-shopping-stream-js', 'php_vars', $dataToBePassed);
		}
	}
	/**
	 * load admin js file
	 */

	public function chls_admin_enqueue()
	{
		wp_enqueue_script('channelize-live-shopping-admin-js', plugins_url('/js/chls_admin.js', dirname(__FILE__, 1)), array(), filemtime(plugin_dir_path(dirname(__FILE__)) . 'js/chls_admin.js'), '1.0.0', true);
		wp_enqueue_style('channelize-live-shopping-admin-css' , plugins_url('/css/admin.css' ,dirname(__FILE__, 1)), array(), filemtime(plugin_dir_path(dirname(__FILE__)) . 'css/admin.css'), 'all', true );
	}

	/**
	 * Allow extra params in URL after page slug.
	 *
	 * @param null
	 *
	 * @return null
	 */
	public function chls_allow_custom_param_on_stream_page()
	{
		$streamPageSlug = chls_get_main_stream_page_details();
		flush_rewrite_rules();

		add_rewrite_rule(
			'^' . $streamPageSlug . '/([^/]+)([/]?)(.*)',
			//!IMPORTANT! THIS MUST BE IN SINGLE QUOTES!:
			'index.php?pagename=' . $streamPageSlug . '&user=$matches[1]',
			'top'
		);
	}


	/**
	 * Add page templates.
	 *
	 * @param  array  $templates  The list of page templates
	 *
	 * @return array  $templates  The modified list of page templates
	 */
	function chls_template_page_for_strems($templates)
	{


		$templates['channelize_live_shopping_stream_template.php'] = ('Channelize Live Shopping Template');

		return $templates;
	}

	/**
	 * template rendaring
	 *
	 * @param [template] $template
	 * @return predefine template
	 */
	public function chls_pt_change_page_template($template)
    {
		global $wp;
		$current_slug = add_query_arg(array(), $wp->request);
		if ($current_slug != 'streams') {
			return $template;
		}

		$meta = get_post_meta(get_the_ID());
		if (!empty($meta) && !empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template && 'default' !== $meta['_wp_page_template'][0]) {
            $template = CHLS_PLUGIN_PATH . 'templates/' . $meta['_wp_page_template'][0];
        }

        return $template;
    }


	/*
	* Template page for the instruction.
	*/
	public function channelize_instructions()
	{
		require_once CHLS_PLUGIN_PATH . 'templates/instructions.php';
	}

	/*
	* Template page for the Live shop page 
	*/

	public function channelize_live_shop_page()
	{
		require_once CHLS_PLUGIN_PATH . 'templates/live_shop_page.php';
	}

   /*
   * Template page for the settings.
   */
    public function channelize_live_shop_settings()
    {
   		require_once CHLS_PLUGIN_PATH . 'templates/settings.php';
    }
}
