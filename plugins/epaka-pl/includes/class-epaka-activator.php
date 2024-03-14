<?php
if (!defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Fired during plugin activation
 *
 * @link       Epaka.pl
 * @since      1.0.0
 *
 * @package    Epaka
 * @subpackage Epaka/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Epaka
 * @subpackage Epaka/includes
 * @author     Epaka <bok@epaka.pl>
 */

class Epaka_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$zones = WC_Shipping_Zones::get_zones();
		add_option('epakaAdminToken', bin2hex(random_bytes(16)));
		
		if(empty(array_filter($zones, function($val){return $val['zone_name'] == "epaka.pl - Polska";}))){
			$response = Epaka_Api_Controller::sendRequest("getCountryCourierMapping.xml");
			$availableCouriers = Epaka_Api_Controller::getInstance()->getAvailableCouriers();
			$availableCouriers = json_decode(json_encode($availableCouriers),true);
			//country:BJ
			$epaka_zone = new WC_Shipping_Zone();
			$epaka_zone->set_zone_name("epaka.pl - Polska");
			$epaka_zone->add_location("PL","country");
			$epaka_zone->save();
			
			foreach($response->Poland->couriers->courier as $courier){
				$method = WC_Shipping_Zones::get_shipping_method($epaka_zone->add_shipping_method("flat_rate"));
				$method->init_instance_settings();
				$instance_settings = $method->instance_settings;

				$instance_settings['title'] = $courier->name->__toString();

				update_option( $method->get_instance_option_key(), apply_filters( 'woocommerce_shipping_' . $method->id . '_instance_settings_values', $instance_settings, $method ) );
				$wpdb->update( "{$wpdb->prefix}woocommerce_shipping_zone_methods", array( 'is_enabled' => false ), array( 'instance_id' => absint( $method->get_instance_id() )));
				do_action( 'woocommerce_shipping_zone_method_status_toggled', $method->get_instance_id(), $method->id, $epaka_zone->get_id(), false);
			}

			$savedZone = WC_Shipping_Zones::get_zone($epaka_zone->get_id());
			$mapping = [
				"Epaka_Shipping_Mapping"=>[]
			];
			foreach($savedZone->get_shipping_methods() as $key=>$value){
				$courier_data = array_filter($availableCouriers['couriers'],function($val) use($value){
					return $val['courierName'] == $value->get_title();
				});
				if(!empty($courier_data)){
					$method_title = preg_replace("/[^a-zA-Z0-9\']/","", $value->get_title());
					
					$mapping['Epaka_Shipping_Mapping'][$savedZone->get_id()][$method_title]['epaka_courier'] = end($courier_data)["courierId"];
					$mapping['Epaka_Shipping_Mapping'][$savedZone->get_id()][$method_title]['map_source_url'] = end($courier_data)["courierMapSourceUrl"];
					$mapping['Epaka_Shipping_Mapping'][$savedZone->get_id()][$method_title]['map_source_name'] = end($courier_data)["courierMapSourceName"];
					$mapping['Epaka_Shipping_Mapping'][$savedZone->get_id()][$method_title]['map_source_id'] = end($courier_data)["courierMapSourceId"];
					$mapping['Epaka_Shipping_Mapping'][$savedZone->get_id()][$method_title]['map_enabled'] = end($courier_data)["courierPointDelivery"]; 
				}
			}

			$mappingJson = json_encode($mapping);
			add_option('epakaShippingCourierMapping',$mappingJson);
		}
	}

}
