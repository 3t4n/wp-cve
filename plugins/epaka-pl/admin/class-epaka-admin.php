<?php
if (!defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       Epaka.pl
 * @since      1.0.0
 *
 * @package    Epaka
 * @subpackage Epaka/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Epaka
 * @subpackage Epaka/admin
 * @author     Epaka <bok@epaka.pl>
 */

class Epaka_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/epaka-admin.min.css', array(), $this->version, 'all' );
		wp_enqueue_style('leaflet_stylesheet', plugin_dir_url( __FILE__ ) .'../vendor/leaflet/leaflet.min.css', false, $this->version, 'all');
		wp_enqueue_style('leaflet_markercluster_stylesheet', plugin_dir_url( __FILE__ ) .'../vendor/leaflet/MarkerCluster.Epaka.min.css', false, $this->version, 'all');
		wp_enqueue_style('loading_stylesheet', plugin_dir_url( __FILE__ ) .'../assets/css/epaka-loading.min.css', false, $this->version, 'all');
		wp_enqueue_style('map_stylesheet', plugin_dir_url( __FILE__ ) .'../assets/css/epaka-map.min.css', false, $this->version, 'all');
		wp_enqueue_style('alerts_stylesheet', plugin_dir_url( __FILE__ ) .'../assets/css/epaka-alerts.min.css', false, $this->version, 'all');
		wp_enqueue_style('epaka_alerts_stylesheet', plugin_dir_url( __FILE__ ) .'../assets/css/epaka-alerts.min.css', false, $this->version, 'all');
		wp_enqueue_style('epaka_icons_stylesheet', EPAKA_DOMAIN.'css/epaka_icons.min.css', false, $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script('leaflet_scripts', plugin_dir_url( __FILE__ ) .'../vendor/leaflet/leaflet.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script('leaflet_markercluster_scripts', plugin_dir_url( __FILE__ ) .'../vendor/leaflet/leaflet.markercluster.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script('parseXML_scripts', plugin_dir_url( __FILE__ ) .'../vendor/parseXML.min.js', array('jquery'), $this->version, false);
		
		wp_enqueue_script('scrollTo_scripts', plugin_dir_url( __FILE__ ) .'../vendor/scrollTo/jquery.scrollTo.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script('epaka_alerts_scripts', plugin_dir_url( __FILE__ ) .'../assets/js/src/epaka-alerts.prod.js', array('jquery'), $this->version, false);
		wp_enqueue_script('babel_scripts', plugin_dir_url( __FILE__ ) .'../vendor/babel.min.js', array('jquery'), $this->version, false);

		wp_enqueue_script('epaka_map_scripts', plugin_dir_url( __FILE__ ) .'../assets/js/src/epaka-map.prod.js', array('jquery'), $this->version, false);
		wp_localize_script('epaka_map_scripts', 'epaka_object',[
			'api_endpoint' => get_rest_url(null, 'epaka-public')
		]);

		wp_enqueue_script('epaka_form_scripts', plugin_dir_url( __FILE__ ) .'../assets/js/src/epaka-form.prod.js', array('jquery'), $this->version, false);
		wp_localize_script('epaka_form_scripts', 'epaka_admin_object',[
			'admin_token' => get_option("epakaAdminToken"),
			'api_endpoint' => get_rest_url(null, 'epaka-admin')
		]);

		wp_enqueue_script($this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/src/epaka-admin.prod.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name, 'epaka_admin_object',[
			'admin_token'=>get_option("epakaAdminToken"),
			'api_endpoint' => get_rest_url(null, 'epaka-admin')
		]);
	}

	public function epaka_admin_menu(){
		add_menu_page(__('epaka.pl', 'epakapl'),__('epaka.pl', 'epakapl'),'manage_woocommerce','epaka_admin',array($this, 'epaka_admin_panel_login_page'));
		add_submenu_page(null, __('Login', 'epakapl'), __('Panel', 'epakapl'), 'manage_woocommerce', 'epaka_admin_panel_login_page', array($this, 'epaka_admin_panel_login_page'));
		// add_submenu_page(null, __('Login', 'epaka'), __('Panel', 'epaka'), 'manage_woocommerce', 'epaka_admin_panel_save_profile', array($this, 'epaka_admin_panel_save_profile'));
		add_submenu_page(null, __('Login', 'epakapl'), __('Panel', 'epakapl'), 'manage_woocommerce', 'epaka_admin_order_send_once', array($this, 'epaka_admin_order_send_once'));
		add_submenu_page('epaka_admin', __('Panel', 'epakapl'), __('Panel', 'epakapl'), 'manage_woocommerce', 'epaka_admin_panel', array($this, 'epaka_admin_panel'));
		if(Epaka_Api_Controller::isAuthorized()) add_submenu_page('epaka_admin', __('Wyloguj', 'epakapl'), __('Wyloguj', 'epakapl'), 'manage_woocommerce', 'epaka_admin_panel_logout', array($this, 'epaka_admin_panel_logout'));
		remove_submenu_page('epaka_admin','epaka_admin');		
	}

	public function edit_order_column($columns){
		$columns[$this->plugin_name] = __('epaka.pl', 'epaka');
        return $columns;
	}
	
	public function order_column_custom_content($column){
		global $post;
		// global $wpdb;

        if ($this->plugin_name === $column) {
			$woo_order = wc_get_order($post->ID);
			// $epaka_order_id = null;
			// foreach($woo_order->get_meta_data() as $value){
			// 	if(get_class($value) == "WC_Meta_Data"){
			// 		$meta_data = $value->get_data();
			// 	}else{
			// 		$meta_data = json_decode(json_encode($value),true);
			// 	}
			// 	if($meta_data["key"] == "_epakaOrderId"){
			// 		$epaka_order_id = $meta_data['value'];
			// 	}
			// }

			$epaka_order_id = wc_get_order_item_meta($post->ID,'_epakaOrderId');

			$authCheck = Epaka_Api_Controller::ensureAuthorized();
			if(empty($authCheck)){
				include('partials/epaka-admin-panel-noconnection-page.php');
			}else{
				if(empty($epaka_order_id)){
					echo "<span>brak zamówienia</span>";
					echo "<br/>".
						"<div>".
							"<div class='epaka-add-existing-order' style='display: none;'>".
								"<input type='text' placeholder='Podaj id zamówienia'/>".
								"<a onclick='window.addExistingOrder(this,\"".$post->ID."\")' class='button'>Dodaj</a><br/>".
							"</div>".
							"<a onclick='window.showInputForAddExisting(this)' class='button'>Dodaj istniejące zamówienie</a><br/>".
						"</div>";
				}else{
					$epakaOrder = Epaka_Api_Controller::getEpakaOrderDetails($epaka_order_id);

					if(is_array($epakaOrder)){
						echo "<span>epaka.pl ID: </span><a href='".EPAKA_DOMAIN."zamowienie/szczegoly/".$epaka_order_id."' target='_blank'>".$epaka_order_id."</a>";
						echo ((!empty($epakaOrder['labelNumber'])) ? "<br/><span>Numer listu: </span><a href='".EPAKA_DOMAIN."sledzenie-przesylek/".$epakaOrder['labelNumber']."' target='_blank'>".$epakaOrder['labelNumber']."</a>" : "<br/><span>Numer listu: brak</span>");

						if($epakaOrder['labelAvailable'] == "1"){
							echo "<br/><a onclick='window.getEpakaOrderLabel(".$epaka_order_id.")'>Etykieta</a>";
						}

						if($epakaOrder['labelZebraAvailable'] == "1"){
							echo "<br/><a onclick='window.getEpakaOrderLabelZebra(".$epaka_order_id.")'>Zebra</a>";
						}

						if($epakaOrder['protocolAvailable'] == "1"){
							echo "<br/><a onclick='window.getEpakaOrderProtocol(".$epaka_order_id.")'>Protokół</a>";
						}

						if($epakaOrder['proformaAvailable'] == "1"){
							echo "<br/><a onclick='window.getEpakaOrderProforma(".$epaka_order_id.")'>Proforma</a>";
						}	

						if($epakaOrder['authorizationDocumentAvailable'] == "1"){
							echo "<br/><a onclick='window.getEpakaOrderAuthorizationDocument(".$epaka_order_id.")'>Upoważnienie</a>";
						}

						echo "<br/><a onclick='window.unlinkEpakaOrderFromWooOrder(\"".$post->ID."\")' class='button'>Nowe zamówienie</a>";
					}else{
						echo "<span>Nie udało się załadować zamówienia</span>";
						echo "<br/><a onclick='window.unlinkEpakaOrderFromWooOrder(\"".$post->ID."\")' class='button'>Nowe zamówienie</a>";
					}
				}
			}
		}
	}

	public function epaka_register_metaboxes(){
		add_meta_box($this->plugin_name . '_delivery', __('epaka.pl', 'epaka'), array($this, 'epaka_admin_order_wrapper'), 'shop_order', 'side', 'core');
	}

	public function epaka_admin_actions(){
		add_action('epaka_admin_panel_save_profile',[$this,'epaka_admin_panel_save_profile']);
	}

	public function epaka_admin_order_wrapper($post){
		// $woo_order = wc_get_order($post->ID);

		// $epaka_order_id = null;
		// foreach($woo_order->get_meta_data() as $value){
		// 	if(get_class($value) == "WC_Meta_Data"){
		// 		$meta_data = $value->get_data();
		// 	}else{
		// 		$meta_data = json_decode(json_encode($value),true);
		// 	}
		// 	if($meta_data["key"] == "_epakaOrderId"){
		// 		$epaka_order_id = $meta_data['value'];
		// 	}
		// }
		$epaka_order_id = wc_get_order_item_meta($post->ID,'_epakaOrderId');

		if(!empty($epaka_order_id)){
			$this->epaka_admin_order_details($post, $epaka_order_id);
		}else{
			$this->epaka_admin_order_send_once($post);
		}
	}

	public function epaka_admin_order_details($post, $epaka_order_id){
		$authCheck = Epaka_Api_Controller::ensureAuthorized();
		$center = false;
		if(empty($authCheck)){
			$center = true;
			include_once('partials/epaka-admin-panel-noconnection-page.php');
		}else{
			$epakaOrder = Epaka_Api_Controller::getEpakaOrderDetails($epaka_order_id);
			$epakaPayment = Epaka_Api_Controller::getEpakaOrderPaymentInfo($epaka_order_id);
			include_once('partials/epaka-admin-order-details.php');
		}	
	}

	public function epaka_admin_order_send_once($post)
	{	
		$authCheck = Epaka_Api_Controller::ensureAuthorized();
		$center = false;
		if(empty($authCheck)){
			$center = true;
			include_once('partials/epaka-admin-panel-noconnection-page.php');
		}else{
			$woo_order = wc_get_order($post->ID);
			$woo_order_items = $woo_order->get_items();
			$dimensionsFromProduct = null;
			if(count($woo_order_items)==1){
				$quantity = end($woo_order_items)['qty'];
				// end($woo_order_items)->get_quantity()
				if($quantity == 1){
					$product_id = end($woo_order_items)['product_id'];
					//wc_get_product(end($woo_order_items)->get_data()['product_id'])->get_data();

					$product = wc_get_product($product_id);
					$dimensionsFromProduct = [
						"weight" => $product->get_weight(),
						"length" => $product->get_length(),
						"width" => $product->get_width(),
						"height" => $product->get_height()
					];
				}
			}

			$savedEpakaShippingMapping = json_decode(get_option("epakaShippingCourierMapping"),true);
			$shipping_method = $woo_order->get_items( 'shipping');
			$shipping_method = end($shipping_method)['name'];
			$shipping_method = preg_replace("/[^a-zA-Z0-9\']/","", $shipping_method);
			// $instance_id = end($shipping_list)->get_id())[1];
			$shipping_zone = wc_get_shipping_zone([
				"destination"=>
				[
					"country"=>$woo_order->get_address()['country'],
					"state"=>$woo_order->get_address()['state'],
					"postcode"=>$woo_order->get_address()['postcode']
				]
			]);
			$shipping_method_mapping = $savedEpakaShippingMapping['Epaka_Shipping_Mapping'][$shipping_zone->get_id()][$shipping_method]['epaka_courier'];

			// $epaka_point = "";
			// $epaka_point_description = "";
			// foreach($woo_order->get_meta_data() as $value){
			// 	if(get_class($value) == "WC_Meta_Data"){
			// 		$meta_data = $value->get_data();
			// 	}else{
			// 		$meta_data = json_decode(json_encode($value),true);
			// 	}
			// 	if($meta_data["key"] == "_epakaP"){
			// 		$epaka_point = $meta_data['value'];
			// 	}
			// 	if($meta_data["key"] == "_epakaPD"){
			// 		$epaka_point_description = $meta_data['value'];
			// 	}
			// }
			$epaka_point = wc_get_order_item_meta($post->ID,'_epakaP');
			$epaka_point_description = wc_get_order_item_meta($post->ID,'_epakaPD');

			$woo_order_data = [
				"woo_order_id" => $post->ID,
				"woo_order_address" => $woo_order->get_address('shipping'),
				"woo_order_billing_address" => $woo_order->get_address('billing'),
				"dimensions" => $dimensionsFromProduct,
				"shipping_method" => $shipping_method_mapping,
				"content" => "Zamówienie #".$post->ID,
				"epaka_point" => $epaka_point,
				"epaka_point_description" => $epaka_point_description,
				"api_session" => get_option('epakaSession')
			];

			$address = Epaka_Utils::prepareAddress($woo_order_data['woo_order_address']['address_1']);

			$woo_order_data['woo_order_address']['street'] = $address[1];
			$woo_order_data['woo_order_address']['house_number'] = $address[2];
			if(!empty($address[5])){
				$woo_order_data['woo_order_address']['flat_number'] = $address[5];
			}else{
				$woo_order_data['woo_order_address']['flat_number'] = "";
			}
			
			include_once('partials/epaka-admin-order-send-once.php');
		}
	}

	public function epaka_admin_panel_login_page(){
		if (!current_user_can('manage_woocommerce')){
            wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		$error = null;

		if(Epaka_Api_Controller::isAuthorized() || Epaka_Api_Controller::hasAuthMem()) wp_redirect(admin_url('admin.php?page=epaka_admin_panel'));

		if(!empty($_POST['api_email']) && !empty($_POST['api_password'])){
			$result = Epaka_Api_Controller::getInstance()->authorize($_POST['api_email'], $_POST['api_password']);

			if($result['status'] == "OK"){
				wp_redirect(admin_url('admin.php?page=epaka_admin_panel'));
			}else{
				if(empty($result['message'])){
					$error = "Błąd połączenia z epaka.pl";
				}else{
					$error = $result['message'];
				}
			}
		}


		include_once('partials/epaka-admin-panel-login-page.php');
	}

	public function epaka_admin_panel_logout(){
		if (!current_user_can('manage_woocommerce')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		Epaka_Api_Controller::logout();
		wp_redirect(admin_url('admin.php?page=epaka_admin_panel_login_page'));
	}

	public function epaka_admin_panel(){
		if (!current_user_can('manage_woocommerce')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		if(!Epaka_Api_Controller::isAuthorized() && !Epaka_Api_Controller::hasAuthMem()) wp_redirect(admin_url('admin.php?page=epaka_admin_panel_login_page'));

		$profile = Epaka_Api_Controller::getInstance()->getProfile();

		$availableCouriers = Epaka_Api_Controller::getInstance()->getAvailableCouriers();

		$tmp = [];
		foreach($availableCouriers->couriers as $value){
			$elem = [
				"courierId" => (!empty($value->courierId) ? $value->courierId->__toString() : ''),
				"courierName" => urlencode((!empty($value->courierName) ? $value->courierName->__toString() : '')),
				"courierMapSourceId" => (!empty($value->courierMapSourceId) ? $value->courierMapSourceId->__toString() : ''),
				"courierMapSourceName" => (!empty($value->courierMapSourceName) ? $value->courierMapSourceName->__toString() : ''),
				"courierMapSourceUrl" => (!empty($value->courierMapSourceUrl) ? $value->courierMapSourceUrl->__toString() : ''),
				"courierPointDelivery" => (!empty($value->courierPointDelivery) ? $value->courierPointDelivery->__toString() : '')
			];

			$tmp[$elem["courierName"]] = $elem;
		}
		$availableCouriers = $tmp;

		$saveProfileCouriers = array_filter($availableCouriers, function($val){
			switch($val['courierId']){
				case "12":
					return true;
				case "11":
					return true;
				case "17":
					return true;
			}
			return false;
		});

		$tmp = [];
		foreach($saveProfileCouriers as $value){
			$tmp[$value['courierId']] = $value; 
		}
		$saveProfileCouriers = $tmp;
		
		$savedShippingMapping = json_decode(get_option("epakaShippingCourierMapping"),true);

		$zones = WC_Shipping_Zones::get_zones();

		include_once('partials/epaka-admin-panel.php');
	}

	public function epaka_admin_panel_save_profile(){
		if (!current_user_can('manage_woocommerce')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
		}
		if(!Epaka_Api_Controller::isAuthorized() && !Epaka_Api_Controller::hasAuthMem()) wp_redirect(admin_url('admin.php?page=epaka_admin_panel_login_page'));
		if(!empty($_POST['profile'])){
			$response = Epaka_Api_Controller::getInstance()->saveProfile($_POST['profile']);
		}
		include_once('partials/epaka-admin-panel-save-profile.php');
	}
}
