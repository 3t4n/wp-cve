<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The public-facing functionality of the plugin.
 *
 * @link       Epaka.pl
 * @since      1.0.0
 *
 * @package    Epaka
 * @subpackage Epaka/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Epaka
 * @subpackage Epaka/public
 * @author     Epaka <bok@epaka.pl>
 */
class Epaka_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if(is_checkout()){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/epaka-public.min.css', array(), $this->version, 'all' );
			wp_enqueue_style('leaflet_stylesheet', plugin_dir_url( __FILE__ ) .'../vendor/leaflet/leaflet.min.css', false, $this->version, 'all');
			wp_enqueue_style('leaflet_markercluster_stylesheet', plugin_dir_url( __FILE__ ) .'../vendor/leaflet/MarkerCluster.Epaka.min.css', false, $this->version, 'all');
			wp_enqueue_style('loading_stylesheet', plugin_dir_url( __FILE__ ) .'../assets/css/epaka-loading.min.css', false, $this->version, 'all');
			wp_enqueue_style('map_stylesheet', plugin_dir_url( __FILE__ ) .'../assets/css/epaka-map.min.css', false, $this->version, 'all');
			wp_enqueue_style('epaka_icons_stylesheet', EPAKA_DOMAIN.'css/epaka_icons.min.css', false, $this->version, 'all');
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if(is_checkout()){
			wp_enqueue_script('leaflet_scripts', plugin_dir_url( __FILE__ ) .'../vendor/leaflet/leaflet.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script('leaflet_markercluster_scripts', plugin_dir_url( __FILE__ ) .'../vendor/leaflet/leaflet.markercluster.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script('parseXML_scripts', plugin_dir_url( __FILE__ ) .'../vendor/parseXML.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script('scrollTo_scripts', plugin_dir_url( __FILE__ ) .'../vendor/scrollTo/jquery.scrollTo.min.js', array('jquery'), $this->version, false);
			wp_enqueue_script('babel_scripts', plugin_dir_url( __FILE__ ) .'../vendor/babel.min.js', array('jquery'), $this->version, false);

			wp_enqueue_script('epaka_map_scripts', plugin_dir_url( __FILE__ ) .'../assets/js/src/epaka-map.prod.js', array('jquery'), $this->version, false);
			wp_localize_script('epaka_map_scripts', 'epaka_object',[
				'api_endpoint' => get_rest_url(null, 'epaka-public')
			]);
		}
	}

	function epaka_map_checkout_code( ) {
		include_once(EPAKA_PATH.'/assets/partials/epaka-map.php');
		// echo file_get_contents(EPAKA_PATH.'/assets/partials/epaka-map.php');
	}

	function epaka_after_shipping_rate($method, $index){
		if(!is_cart()){
			$savedShippingMapping = json_decode(get_option('epakaShippingCourierMapping'),true);
			$chosen_method_array = WC()->session->get('chosen_shipping_methods');
			$customer = WC()->session->get('customer');
			$shipping_zone = wc_get_shipping_zone([
				"destination"=>
				[
					"country"=>$customer['country'],
					"state"=>$customer['state'],
					"postcode"=>$customer['postcode']
				]
			]);
			
			if($chosen_method_array[0] == $method->id){
				$method_title = preg_replace("/[^a-zA-Z0-9\']/","",$method->get_label());
				if(!empty($savedShippingMapping['Epaka_Shipping_Mapping'][$shipping_zone->get_id()][$method_title]['map_enabled'])){
					 woocommerce_form_field( 'epaka_paczkomat', array(
					 	'type'          => 'text', // text, textarea, select, radio, checkbox, password, about custom validation a little later
						'required'		=> true, // actually this parameter just adds "*" to the field
						'class'         => array('epakaPointHidden'),
					), "" );

					woocommerce_form_field( 'epaka_paczkomat_opis', array(
						'type'          	=> 'text', // text, textarea, select, radio, checkbox, password, about custom validation a little later
						'required'			=> true, // actually this parameter just adds "*" to the field
						'class'				=> array('epakaPointDesc'),
						'input_class'       => array('showMapOnClick'), // array only, read more about classes and styling in the previous step
						'placeholder'   	=> __('Wybierz punkt odbioru','epakapl'),
						'autocomplete'		=> 'off',
						'custom_attributes' => [
							"data-map-source-url" => $savedShippingMapping['Epaka_Shipping_Mapping'][$shipping_zone->get_id()][$method_title]['map_source_url'],
							"data-map-source-name" => $savedShippingMapping['Epaka_Shipping_Mapping'][$shipping_zone->get_id()][$method_title]['map_source_name'],
							"data-map-source-id" => $savedShippingMapping['Epaka_Shipping_Mapping'][$shipping_zone->get_id()][$method_title]['map_source_id']
						]
					), "" );
					
					echo "<script>window.mapBind();</script>";
				}
			}
		}
	}

	function epaka_custom_order_thankyou_fields($order){
		$epaka_point_description = wc_get_order_item_meta($order->id,'_epakaPD');

		if(!empty($epaka_point_description)){
			echo 
			"<table>".
				"<tbody>".
					"<tr>".
						"<th>".__("Punkt odbioru","epakapl").":</th>".
						"<td>".$epaka_point_description."</td>".
					"</tr>".
				"</tbody>".
			"</table>";
		}
	}

	function epaka_validate_delivery_point( $fields, $errors ){

		$savedShippingMapping = json_decode(get_option('epakaShippingCourierMapping'),true);
		$chosen_method_array = WC()->session->get('chosen_shipping_methods');
		$customer = WC()->session->get('customer');
		$shipping_zone = wc_get_shipping_zone([
			"destination"=>
			[
				"country"=>$customer['country'],
				"state"=>$customer['state'],
				"postcode"=>$customer['postcode']
			]
		]);

		$method_id = explode(":",$fields['shipping_method'][0])[1];
		$method = $shipping_zone->get_shipping_methods(true)[$method_id];

		if(!empty($method)) {
			$method_title = preg_replace("/[^a-zA-Z0-9\']/","",$method->get_title());
		}


		if($savedShippingMapping['Epaka_Shipping_Mapping'][$shipping_zone->get_id()][$method_title]['map_enabled'] == "1" 
		|| $savedShippingMapping['Epaka_Shipping_Mapping'][$shipping_zone->get_id()][$method_title]['map_enabled'] == "on"){
			if(empty($_POST['epaka_paczkomat_opis']) || empty($_POST['epaka_paczkomat'])){
				$errors = array(wc_add_notice(__('Punkt odbioru jest wymagany dla wybranego kuriera.','epakapl'),'error'));
			}
		}
	}

	function epaka_update_custom_meta($order_id, $posted){
		if(isset($_POST['epaka_paczkomat'])){
			$point = sanitize_text_field($_POST['epaka_paczkomat']);
			wc_add_order_item_meta($order_id,'_epakaP',$point);
			// $order->add_meta_data('_epakaP', $point);
		}
		
		if(isset($_POST['epaka_paczkomat_opis'])){
			$pointDesc = sanitize_text_field($_POST['epaka_paczkomat_opis']);
			// $order->add_meta_data('_epakaPD', $pointDesc);
			wc_add_order_item_meta($order_id,'_epakaPD',$pointDesc);
		}
	}

}
