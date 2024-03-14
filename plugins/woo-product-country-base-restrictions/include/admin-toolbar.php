<?php
/**
 * CBR Setting 
 *
 * @class   CBR_Admin_Toolbar
 * @package WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CBR_Admin_Toolbar class
 *
 * @since 1.0.0
 */
class CBR_Admin_Toolbar {
	
	/**
	 * Get the class instance
	 *
	 * @since  1.0.0
	 * @return CBR_Admin_Toolbar
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Instance of this class.
	 *
	 * @since 1.0.0
	 * @var object Class Instance
	*/
	private static $instance;
	
	/*
	* construct function
	*
	* @since 1.0.0
	*/
	public function __construct() {
		$this->init();
	}

	/*
	* Init function
	*
	* @since 1.0.0
	*/
	public function init() {
		
		add_action('wp_ajax_set_widget_country', array( $this, 'set_checkout_country') );
		add_action('wp_ajax_nopriv_set_widget_country', array( $this, 'set_checkout_country'));
		
		add_action('wp_ajax_set_cart_page_country', array( $this, 'set_cart_page_country') );
		add_action('wp_ajax_nopriv_set_cart_page_country', array( $this, 'set_cart_page_country' ));
		
		add_action( 'wp_before_admin_bar_render', array( $this, 'cbr_adminbar_rendar' ), 9999, 1 );
		
	}
	
	/**
	 * Update WooCommerce Customer country on checkout
	 *
	 * @since 1.0.0
	 */
	public function set_checkout_country() {
		
		$location = WC_Geolocation::geolocate_ip();
		$country = $location['country'];
		$cookie_country = !empty($_COOKIE['country']) ? sanitize_text_field($_COOKIE['country']) : $country;
		
		if ( $cookie_country ) { 
			//WC()->customer->set_billing_country( $cookie_country );
			WC()->customer->set_shipping_country( $cookie_country );			
		}
		
		$country = WC()->countries->countries[$cookie_country];
		
		echo json_encode( array('success' => 'true', 'country' => $country, 'countrycode' => $cookie_country ) );
		die();
	}
	
	/**
	 * Update WooCommerce Customer country on checkout
	 *
	 * @since 1.0.0
	 */
	public function set_cart_page_country() {

		$COOKIE = !empty($_COOKIE['country']) ? sanitize_text_field($_COOKIE['country']) : '';
		$cookie_country = !empty($_POST['country']) ? sanitize_text_field($_POST['country']) : $COOKIE;		 

		if ( $cookie_country ) { 
			WC()->customer->set_shipping_country( $cookie_country );
		}
		
		$country = WC()->countries->countries[$cookie_country];
		
		echo json_encode( array('success' => 'true', 'country' => $country, 'countrycode' => $cookie_country ) );
		die();
	}
	
	/**
	 * Rendar user search function in wp admin bar.
	 *
	 * @since 1.0.0
	 */
	public function cbr_adminbar_rendar() {
	
		// if admin_bar is showing.
		if (is_admin_bar_showing()) {
	
			global $wp_admin_bar;
	
			// if current user can edit_users than he can see this
			ob_start();
			
			if ( '1' != get_option('wpcbr_debug_mode') || is_admin() ) {
				return;
			}
			global $fzpcr;
			$user = wp_get_current_user();
			$user_role = array('administrator','shop_manager');
			
			if ( isset($user->roles[0]) && !in_array(  $user->roles[0], $user_role ) ) {
				return;
			}
			
			$wp_admin_bar->add_menu(
				array(
					'id'    => 'cbr_item',
					'title' => __('CBR Country: ' . WC()->countries->countries[$fzpcr->restriction->get_country()], 'fast-user-switching'),
					'href'  => '#',
				)
			);
			$wsmab_zorem_icon = plugins_url( 'assets/images/green-light.png', __FILE__  );
			$countries_obj   = new WC_Countries();
			$countries   = $countries_obj->__get('countries');
			asort( $countries );
			$country = array();
			?>
			<div class="display-country-for-customer">
				<span class="ab-label">
						<select class="country" onchange="setCountryCookie('country', this.value, 365)">
							<option value="">Select Country</option>
							<?php foreach ( $countries as $key => $val ) { ?>
								 <option value="<?php echo esc_html($key); ?>"
									<?php
									if ( isset($_COOKIE['country']) && $_COOKIE['country'] == $key ) {
										echo 'selected';
									}
									?>
									><?php echo esc_html($val); ?></option>
							<?php } ?>
						</select>
				</span>
			</div>
			<style type="text/css">
				#wpadminbar .display-country-for-customer .country {border-radius: 25px;padding: 0 5px;line-height: 1;min-height: 25px;}
				li#wp-admin-bar-cbr_child_item .ab-item {height: 40px !important;}
				#wpadminbar .display-country-for-customer .ab-label {vertical-align: middle;}
				#wpadminbar .display-country-for-customer .ab-icon:before{background-image: url('<?php echo esc_html($wsmab_zorem_icon); ?>');background-repeat: no-repeat;background-position: 0 50%;padding-left: 12px !important;background-size: 12px;content: '';top: 2px;vertical-align: middle;}
			</style>
			<?php
			$html = ob_get_clean();
			$wp_admin_bar->add_menu(
				array(
					'id'		=> 'cbr_child_item',
					'parent'	=> 'cbr_item',
					'title'		=> $html,
				)
			);
		}	
	}
}
