<?php
/**
 * Plugin Name: Bosta WooCommerce
 * Description: WooCommerce integration for Bosta eCommerce
 * Author: Bosta
 * Author URI: https://www.bosta.co/
 * Version: 3.0.11
 * Requires at least: 5.0
 * php version 7.0
 * Tested up to: 6.1.1
 * WC requires at least: 2.6
 * WC tested up to: 7.2.2
 * Text Domain: bosta-woocommerce
 * Domain Path: /languages
 *
 */


include plugin_dir_path(__FILE__) . 'components/pickups/pickups.php';
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly

}

const BOSTA_ENV_URL = 'https://app.bosta.co/api/v0';
const PLUGIN_VERSION = '3.0.11';
const bosta_cache_duration = 86400;
const bosta_country_id_duration = 604800;

add_action('admin_print_styles', 'bosta_stylesheet');

function bosta_get_api_key() {
    if(isset(get_option('woocommerce_bosta_settings')['APIKey'])){
        return sanitize_text_field(get_option('woocommerce_bosta_settings')['APIKey']);
    }
}

function bosta_stylesheet()
{
	wp_enqueue_style('myCSS', plugins_url('/Css/main.css', __FILE__));
	wp_enqueue_style('pickupsCSS', plugins_url('components/pickups/pickups.css', __FILE__));
}

function bosta_get_country_id() {
	if (bosta_get_api_key() == null) {
		return;
	}
	$url = BOSTA_ENV_URL . '/businesses/' . esc_html(bosta_get_api_key()). '/info';
	$business_result = wp_remote_get($url, array(
		'timeout' => 30,
		'method' => 'GET',
		'headers' => array(
			'Content-Type' => 'application/json',
			'X-Requested-By' => 'WooCommerce',
			'X-Plugin-Version' => PLUGIN_VERSION
		),
	));
	if (is_wp_error($business_result) || $business_result['response']['code'] !== 200) {
		$country_id = "60e4482c7cb7d4bc4849c4d5";
		return $country_id;
	} else {
		$business = json_decode($business_result['body']);
		$country_id = $business->country->_id;
		set_transient( 'bosta_country_id_Transient', $country_id, bosta_country_id_duration );
		return $country_id;
	}
}

function checkAvailability($zone) {
	return $zone->dropOffAvailability == true;
}

function bosta_clear_caching() {
	if(isset(get_option('woocommerce_bosta_settings')['ClearZoningCache'])) {
		$ClearZoningCache = get_option('woocommerce_bosta_settings')['ClearZoningCache'];
		if ($ClearZoningCache == 'yes') {
			delete_transient('bosta_new_cities');
			delete_transient('bosta_new_states');
			delete_transient('bosta_country_id_Transient');
			update_option('woocommerce_bosta_settings', array('ClearZoningCache' => 'no'));
		}
	}
}

function bosta_get_new_cities(): array {
	bosta_clear_caching();
	$country_id = get_transient('bosta_country_id_Transient');

	if ( ! $country_id ) {
		$country_id = bosta_get_country_id();
		if ($country_id == null) {
			return array();
		}
	}

	$cities_cache_key = 'bosta_new_cities';
	$resultCities = get_transient( $cities_cache_key );

	if ( ! $resultCities ) {
		$cities_url = BOSTA_ENV_URL .  '/cities?context=dropoff&countryId=' . esc_html($country_id);
		$result = wp_remote_post($cities_url, array(
			'timeout' => 30,
			'method' => 'GET',
			'headers' => array(
				'Content-Type' => 'application/json',
				'X-Requested-By' => 'WooCommerce',
				'Accept-Language' => get_locale() === 'ar' ? 'ar' : 'en',
				'X-Plugin-Version' => PLUGIN_VERSION
			),
		));

		if (is_wp_error($result)) {
			$error_message = $result->get_error_message();
			echo "<script>alert('Something went wrong: ",esc_html($error_message),")</script>";
		} else {
			if ($result['response']['code'] !== 200) {
				$resultCities = [];
			} else {
				$result = json_decode($result['body']);
				$resultCities = array();
				$coveredCities = array();
				$coveredCities = array_filter($result, 'checkAvailability');
				for ($i = 0; $i < count($result); $i++) {
					if (isset($coveredCities[$i]->name) && isset($coveredCities[$i]->nameAr)){
						$resultCities[$i] = get_locale() === 'ar' ? $coveredCities[$i]->nameAr : $coveredCities[$i]->name;
					}
				}
				set_transient( $cities_cache_key, $resultCities, bosta_cache_duration );
			}
		}
	}

	return $resultCities ? $resultCities : [];
}

function bosta_get_new_states(): array {
	bosta_clear_caching();
	$country_id = get_transient('bosta_country_id_Transient');

	if ( ! $country_id ) {
		$country_id = bosta_get_country_id();
		if ($country_id == null) {
			return array();
		}
	}

	$cache_key = 'bosta_new_states';
	$resultStates = get_transient( $cache_key );
	if ( ! $resultStates ) {
		$states_url = BOSTA_ENV_URL .  '/cities/getAllDistricts?context=dropoff&countryId=' . esc_html($country_id);

		$states = wp_remote_post($states_url, array(
			'timeout' => 30,
			'method' => 'GET',
			'headers' => array(
				'Content-Type' => 'application/json',
				'X-Requested-By' => 'WooCommerce',
				'Accept-Language' => get_locale() === 'ar' ? 'ar' : 'en',
				'X-Plugin-Version' => PLUGIN_VERSION
			),
		));

		if (is_wp_error($states) && $states->get_error_message()) {
			$error_message = $states->get_error_message();
			echo "<script>alert('Something went wrong: ",esc_html($error_message),")</script>";
		} else {
			if ($states['response']['code'] === 200) {
				$states = json_decode($states['body']);
				$resultStates = array();
				for ($i = 0; $i < count($states); $i++) {
					if ($states[$i]->dropOffAvailability == true) {
						$coveredDistricts = array();
						$currentDistrictArray = $states[$i]->districts;
						for ($j = 0; $j < count($currentDistrictArray); $j++) {
							if ($currentDistrictArray[$j]->dropOffAvailability == true) {
								array_push($coveredDistricts, $currentDistrictArray[$j]);
							}
						}
						array_push($resultStates, $coveredDistricts);
					}
				}
				set_transient( $cache_key, $resultStates, bosta_cache_duration );
			}
		}
	}

	return $resultStates ? $resultStates : [];
}

$resultCities = bosta_get_new_cities();
$resultStates = bosta_get_new_states();

add_filter('woocommerce_checkout_fields', 'bosta_override_checkout_city_fields');
function bosta_override_checkout_city_fields($fields): array {
	global $resultCities;
	$city_args = wp_parse_args(array(
		'type' => 'select',
		'options' => $resultCities,
		'placeholder' => 'Select city',
		'input_class' => array(
			'wc-enhanced-select',
		),
	), $fields['shipping']['shipping_city']);

	$fields['shipping']['shipping_city'] = $city_args;
	$fields['billing']['billing_city'] = $city_args; // Also change for billing field
	unset($fields['shipping']['shipping_city']);
	unset($fields['billing']['billing_city']);
	wc_enqueue_js("
	jQuery( ':input.wc-enhanced-select' ).filter( ':not(.enhanced)' ).each( function() {
		var select2_args = { minimumResultsForSearch: 5 };
		jQuery( this ).select2( select2_args ).addClass( 'enhanced' );
	});");
    if(isset(get_option('woocommerce_bosta_settings')['AllowNewZonesInCheckout'])) {
        $AllowNewZonesInCheckout = get_option('woocommerce_bosta_settings')['AllowNewZonesInCheckout'];
        if ($AllowNewZonesInCheckout === 'yes') {
            wc_enqueue_js("
            jQuery(document).ready(function($) {
                $('select.wc-enhanced-select').val('').trigger('change');
            });");
        }
    }

	return $fields;
}

function bosta_override_checkout_state_fields($fields): array {
	global $resultCities;

	$state_args = wp_parse_args(array(
		'type' => 'select',
		'options' => $resultCities,
		'placeholder' => 'Select state',
		'input_class' => array(
			'wc-enhanced-select',
		),
	), $fields['shipping']['shipping_city']);

	$fields['shipping']['shipping_city'] = $state_args;
	$fields['billing']['billing_city'] = $state_args; // Also change for billing field

	return $fields;
}

if(isset(get_option('woocommerce_bosta_settings')['AllowNewZonesInCheckout'])) {
    $AllowNewZonesInCheckout = get_option('woocommerce_bosta_settings')['AllowNewZonesInCheckout'];
    if ($AllowNewZonesInCheckout == 'yes') {
        add_filter('woocommerce_checkout_fields', 'bosta_override_checkout_state_fields');
    }
}

add_action('admin_head', 'bosta_woocommerce_admin_init');
function bosta_woocommerce_admin_init()
{
	global $resultStates;
	global $resultCities;
	$states = $resultStates;

	$options_a = $states;
	$screen = get_current_screen();

	if ($screen->post_type == "shop_order") {
		?>

        <script type="text/javascript">

            jQuery(function($){
                const opa = <?php echo json_encode($options_a); ?>,
                    select1 = 'select[name="_shipping_city"]',
                    select2 = 'select[name="_shipping_state"]',
                    select3 = 'select[name="_billing_city"]',
                    select4 = 'select[name="_billing_state"]';
                const cities = <?php echo json_encode($resultCities); ?>;

                function dynamicSelectOptions( opt,type ){
                    let options = '';
                    $.each( opt, function( key, value ){
                        options += '<option value="'+value.zoneName+' - '+value.districtName +'">'+value.zoneName+' - '+value.districtName+'</option>';
                    });
                    type==='shipping'? $(select2).html(options):$(select4).html(options);
                }
                $(select1).change(function(){

                    for (let i = 0; i < cities.length; i++) {
                        if(cities[i]===$(this).val())
                        {
                            dynamicSelectOptions( opa[i],'shipping');
                        }
                    }

                });

                $(select3).change(function(){

                    for (let i = 0; i < cities.length; i++) {
                        if(cities[i]===$(this).val())
                        {
                            dynamicSelectOptions( opa[i]);
                        }
                    }

                });
            });

        </script>

		<?php
	}

}
add_filter('woocommerce_admin_billing_fields', 'bosta_admin_order_pages_bosta_city_fields');
add_filter('woocommerce_admin_shipping_fields', 'bosta_admin_order_pages_bosta_city_fields');
function bosta_admin_order_pages_bosta_city_fields($fields): array {
	$fields['city']['type'] = 'select';
	global $resultCities;
	$cities = array();
	for ($i = 0; $i < count($resultCities); $i++) {
		if (isset($resultCities[$i])) $cities[$resultCities[$i]] = $resultCities[$i];
	}
	$fields['city']['options'] = $cities;
	$fields['city']['class'] = 'short';
	return $fields;
}

add_filter('woocommerce_states', 'bosta_cities_and_zones');
function bosta_cities_and_zones($states)
{
	global $resultCities;

	$country_id = get_transient('bosta_country_id_Transient');
	if($country_id == '60e4482c7cb7d4bc4849c4d5') {
        $states['EG'] = $resultCities;
	} else if ($country_id == 'eF-3f9FZr') {
	    $states['SA'] = $resultCities;
	}

	return $states;
}
// add notice to config plugin
add_action('admin_notices', 'bosta_woocommerce_notice');
function bosta_woocommerce_notice()
{
	//check if woocommerce installed and activated
	if (!class_exists('WooCommerce')) {
		echo '<div class="error notice-warning text-bold">
              <p>
              <img src="' . esc_url(plugins_url('assets/images/bosta.svg', __FILE__)) . '" alt="Bosta" style="height:13px; width:25px;">
              <strong>' . sprintf(esc_html__('Bosta requires WooCommerce to be installed and active. You can download %s here.'), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>') . '</strong>
              </p>
              </div>';
	}
}

add_action('admin_menu', 'bosta_setup_menu');
function bosta_setup_menu()
{
	//check if woocommerce is activated
	if (!class_exists('WooCommerce')) {
		return;
	}

	add_menu_page('Test Plugin Page', 'Bosta', 'manage_options', 'bosta-woocommerce', 'bosta_setting', esc_url(plugins_url('assets/images/bosta.svg', __FILE__)));

	// link to plugin settings
	add_submenu_page('bosta-woocommerce', 'Setting', 'Setting', 'manage_options', 'bosta-woocommerce', 'bosta_setting');

	// link to woocommerce orders
	add_submenu_page('bosta-woocommerce', 'Send Orders', 'Send Orders', 'manage_options', 'bosta-woocommerce-orders', 'bosta_orders');

	// create pickup request
	add_submenu_page('bosta-woocommerce', 'Create Pickup', 'Create Pickup', 'manage_options', 'bosta-woocommerce-create-edit-pickup', 'bosta_create_edit_pickup_form');

	//view pickups
	add_submenu_page('bosta-woocommerce', 'Pickup Requests', 'Pickup Requests', 'manage_options', 'bosta-woocommerce-view-pickups', 'bosta_view_scheduled_pickups');

	// link to bosta shipments
	add_submenu_page('bosta-woocommerce', 'Track Bosta Orders', 'Track Bosta Orders', 'manage_options', 'bosta-woocommerce-shipments', 'bosta_dashboard');
}

function bosta_setting()
{
	$redirect_url = admin_url('admin.php?') . 'page=wc-settings&tab=shipping&section=bosta';
	wp_redirect($redirect_url);
}

function bosta_orders()
{
	$redirect_url = admin_url('edit.php?') . 'post_type=shop_order&paged=1';
	wp_redirect($redirect_url);
}

function bosta_dashboard()
{
	$redirect_url = 'https://bosta.co/tracking-shipments';
	wp_redirect($redirect_url);
}

add_action('load-edit.php', 'bosta_wco_load', 20);
function bosta_wco_load()
{
	$screen = get_current_screen();
	if (!isset($screen->post_type) || 'shop_order' != $screen->post_type) {
		return;
	}

	add_filter("manage_{$screen->id}_columns", 'bosta_wco_add_columns');
	add_action("manage_{$screen->post_type}_posts_custom_column", 'bosta_wco_column_cb_data', 10, 2);
}

add_action('woocommerce_checkout_before_customer_details', 'bosta_custom_checkout_fields', 20);
function bosta_custom_checkout_fields()
{
	global $resultStates;
	$states = $resultStates;

	for ($i = 0; $i < count($resultStates); $i++) {
        if(isset($resultStates[$i])) {
            $states[$i] = __($resultStates[$i], 'wps');
        }
	}

	$options_a = $states;
	$required = esc_attr__('required', 'woocommerce');
	?>
    <script>
        jQuery(function($){
            const opa = <?php echo json_encode($options_a); ?>,
                select1 = 'select[name="billing_city"]',
                select2 = 'select[name="billing_state"]',
                select3 = 'select[name="shipping_city"]',
                select4 = 'select[name="shipping_state"]';
            function dynamicSelectOptions( opt,cityIndex,type ){
                let index=0;
                for(let i=0;i<cityIndex;i++){
                    index+=opa[i].flat().length
                }

                let options = '';
                $.each( opt, function( key, value ){
                    const newKey=index+key;
                    options += '<option value='+newKey+'>'+value.zoneName+' - '+value.districtName+'</option>';
                });
                type==='billing'? $(select2).html(options): $(select4).html(options);
            }

            $(select1).change(function(){
                for (let i = 0; i < opa.length; i++) {
                    if( $(this).val() === i )
                        dynamicSelectOptions( opa[i],i,'billing' );
                }
            });

            $(select3).change(function(){
                for (let i = 0; i < opa.length; i++) {
                    if( $(this).val() === i )
                        dynamicSelectOptions( opa[i] ,i);
                }
            });
        });
    </script>
	<?php
}

function bosta_wco_add_columns($columns)
{
	$order_total = $columns['order_total'];
	$order_date = $columns['order_date'];
	$order_status = $columns['order_status'];
	unset($columns['order_date']);
	unset($columns['order_status']);
	$columns["bosta_tracking_number"] = __("Bosta Tracking Number", "themeprefix");
	$columns['order_date'] = $order_date;
	$columns['order_status'] = $order_status;
	unset($columns['order_total']);
	$columns["bosta_status"] = __("Bosta Status", "themeprefix");
	$columns["bosta_delivery_date"] = __("Delivered at", "themeprefix");
	$columns["bosta_customer_phone"] = __("Customer phone", "themeprefix");
	$columns['order_total'] = $order_total;

	return $columns;
}

function bosta_wco_column_cb_data($colName, $orderId)
{
	if ($colName == 'bosta_status') {
		$status = get_post_meta($orderId, 'bosta_status', true);
		if (!empty($status)) {
			echo esc_html($status);
		} else {
			echo "---";
		}
	}

	if ($colName == 'bosta_tracking_number') {
		$trackingNumber = get_post_meta($orderId, 'bosta_tracking_number', true);
		if (!empty($trackingNumber)) {
			echo esc_html($trackingNumber);
		} else {
			echo "---";
		}
	}

	if ($colName == 'bosta_delivery_date') {
		$deliveryDate = get_post_meta($orderId, 'bosta_delivery_date', true);
		if (!empty($deliveryDate)) {
			echo date("D d-M-Y", strtotime($deliveryDate));
		} else {
			echo "---";
		}
	}

	if ($colName == 'bosta_customer_phone') {
		$customerPhone = get_post_meta($orderId, 'bosta_customer_phone', true);
		if (!empty($customerPhone)) {
			echo esc_html($customerPhone);
		} else {
			echo "---";
		}
	}
}

add_action('manage_posts_extra_tablenav', 'bosta_create_pickup_top_bar_button', 20);
function bosta_create_pickup_top_bar_button($which)
{
	global $pagenow, $typenow;

	if ('shop_order' === $typenow && 'edit.php' === $pagenow && 'top' === $which) {
		?>
        <br>
        <br>
        <br>
        <div class="alignleft actions custom">
            <button type="submit" name="create_pickup" style="height:32px;" class="orders-button" value="yes"><?php
				echo __('Create Pickup', 'woocommerce'); ?></button>
        </div>
		<?php
	}
	if ('shop_order' === $typenow && 'edit.php' === $pagenow && isset($_GET['create_pickup']) && $_GET['create_pickup'] === 'yes') {
		$redirect_url = admin_url('admin.php?') . 'page=bosta-woocommerce-create-edit-pickup';
		wp_redirect($redirect_url);
	}
}

add_filter('bulk_actions-edit-shop_order', 'bosta_sync_cash_collection_orders', 20);
function bosta_sync_cash_collection_orders($actions)
{
	$actions['sync_cash_collection_orders'] = __('Send Cash Collection Orders', 'woocommerce');
	return $actions;
}

add_filter('bulk_actions-edit-shop_order', 'bosta_sync', 20);
function bosta_sync($actions)
{
	$actions['sync_to_bosta'] = __('Send To Bosta', 'woocommerce');
	return $actions;
}

add_filter('handle_bulk_actions-edit-shop_order', 'bosta_sync_handle', 10, 3);
function bosta_sync_handle($redirect_to, $action, $order_ids)
{
	if ($action != 'sync_to_bosta' && $action != 'sync_cash_collection_orders') {
		return;
	} else if ($action == 'sync_cash_collection_orders') {
		$orderType = 15;
		$addressType = 'pickupAddress';
	} else if ($action == 'sync_to_bosta') {
		$orderType = 10;
		$addressType = 'dropOffAddress';
	}
	global $resultStates;
	$APIKey = bosta_get_api_key();
	$ProductDescription = get_option('woocommerce_bosta_settings')['ProductDescription'];
	$OrderRef = get_option('woocommerce_bosta_settings')['OrderRef'];
	$AllowToOpenPackage = get_option('woocommerce_bosta_settings')['AllowToOpenPackage'];
	if (empty($APIKey)) {
		$redirect_url = admin_url('admin.php?') . 'page=wc-settings&tab=shipping&section=bosta';
		wp_redirect($redirect_url);
		return;
	}
	$args = array(
		'limit' => -1,
		'post__in' => $order_ids,
	);
	$allOrders = wc_get_orders($args);
	$formatedOrders = array();
	$AWBDesc = 0;

	foreach ($allOrders as $order) {
		if (empty(get_post_meta($order->id, 'bosta_tracking_number', true))) {
			$items = $order->get_items();
			$desc = 'Products: ';
			$itemsQuantity = 0;
			$descWithSku = '';
			foreach ($items as $item_id => $item_data) {
				$product = $item_data->get_product();
				$product_name = $product->get_name();
				$item_quantity = $item_data->get_quantity();
				$itemsQuantity += $item_data->get_quantity();
				$desc .= $product_name . '(' . $item_quantity . ') ';
				$product_sku = $product->get_sku();
				$AWBDesc .= $product_sku . '(' . $item_quantity . ') ,';
				$descWithSku .= 'Product sku: ' . $product_sku . ' => ' . $product_name . ' ' . '(' . $item_quantity . ') ,';
			}
			$order = json_decode($order);
			$newOrder = new stdClass();
			$newOrder->id = $order->id;
			$newOrder->type = $orderType;
			$newOrder->specs = new stdClass();
			$newOrder
				->specs->packageDetails = new stdClass();
			$newOrder
				->specs
				->packageDetails->itemsCount = $itemsQuantity;
			if ($product_sku && $ProductDescription == 'yes') {
				$newOrder
					->specs
					->packageDetails->description = $descWithSku;
			} elseif ($product_sku && $ProductDescription == 'no') {
				$newOrder
					->specs
					->packageDetails->description = $AWBDesc;
			} elseif
			($ProductDescription == 'yes') {
				$newOrder
					->specs
					->packageDetails->description = $desc;
			}
			$newOrder->notes = $order->customer_note;
			if ($OrderRef == 'yes') {
				$newOrder->businessReference = 'Woocommerce_' . $order->order_key;
			}
			if ($AllowToOpenPackage == 'yes') {
				$newOrder->allowToOpenPackage = true;
			}
			$newOrder->receiver = new stdClass();
			$newOrder
				->receiver->firstName = $order
				->shipping->first_name;
			$newOrder
				->receiver->lastName = $order
				->shipping->last_name;
			if ($order->shipping &&  $order->shipping->phone) {
				$newOrder
					->receiver->phone =  $order->shipping->phone;
			} else {
				$newOrder
					->receiver->phone = $order
					->billing->phone;
			}
			$newOrder->$addressType = new stdClass();
			$newOrder
				->$addressType->firstLine = $order
				->shipping->address_1;
				$newOrder
				->$addressType->secondLine = $order
				->shipping->address_2;

				$result = preg_split("/\s*(?<!\w(?=.\w))[\-[\]()]\s*/", $order
					->shipping
					->state);

				global $resultCities;
				$shippingCityName = $resultCities[$order
					->shipping
					->state];					
				$newOrder
					->$addressType->city = $shippingCityName;
			
			if ($order->payment_method == 'cod') {
				$newOrder->cod = (float) $order->total;
			}
			$formatedOrders[] = $newOrder;
		}
	}

	for ($i = 0; $i < count($formatedOrders); $i++) {
		$id = $formatedOrders[$i]->id;
		unset($formatedOrders[$i]->id);
		$result = wp_remote_post(BOSTA_ENV_URL .  '/deliveries', array(
			'timeout' => 30,
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json',
				'authorization' => $APIKey,
				'X-Requested-By' => 'WooCommerce',
				'X-Plugin-Version' => PLUGIN_VERSION
			),
			'body' => json_encode($formatedOrders[$i]),
		));


		if ($result['response']['code'] != 201) {
			$result = json_decode($result['body']);
			if (gettype($result) == 'array') {
				$error = $result[0]->message;
			} else {
				$error = $result->message;
			}
			echo "Something went wrong: " . esc_html($error);
		} else {
			$result = json_decode($result['body']);
			if ($result->_id && empty(get_post_meta($id, 'bosta_delivery_id', true))) {
				add_post_meta($id, 'bosta_delivery_id', $result->_id);
			}
			if ($result
				    ->state->value && empty(get_post_meta($id, 'bosta_status', true))) {
				add_post_meta($id, 'bosta_status', $result
					->state
					->value);
			}
			if ($result->trackingNumber && empty(get_post_meta($id, 'bosta_tracking_number', true))) {
				add_post_meta($id, 'bosta_tracking_number', $result->trackingNumber);
			}
			if ($result->deliveryTime && empty(get_post_meta($id, 'bosta_delivery_date', true))) {
				add_post_meta($id, 'bosta_delivery_date', $result->deliveryTime);
			}
			if ($result
				    ->receiver->phone && empty(get_post_meta($id, 'bosta_customer_phone', true))) {
				add_post_meta($id, 'bosta_customer_phone', $result
					->receiver
					->phone);
			}
		}
	}
	$page_num = $page_num ? $page_num : 1;
	$redirect_url = admin_url('edit.php?') . 'post_type=shop_order&paged=' . $page_num;
	wp_redirect($redirect_url);
}

add_action('manage_posts_extra_tablenav', 'bosta_send_all_top_bar_button', 20);
function bosta_send_all_top_bar_button($which)
{
	global $pagenow, $typenow;

	if ('shop_order' === $typenow && 'edit.php' === $pagenow && 'top' === $which) {
		?>
        <div class="alignleft actions custom">
						<?php wp_nonce_field('bosta_send_all_nonce', 'bosta_send_all_nonce_field'); ?>
						<input type="hidden" name="page_num" value="<?php echo esc_attr($_GET['paged']); ?>">
            <button type="submit" name="send_all_orders" style="height:32px;" class="orders-button" value="yes">
				<?php
				echo __('Send all orders to bosta', 'woocommerce');
				?>
            </button>
        </div>
        <script type="text/JavaScript">
            document.getElementsByClassName("alignleft")[1].setAttribute("class", "alignright");
        </script>
		<?php
	}
	if ('shop_order' === $typenow && 'edit.php' === $pagenow && isset($_GET['send_all_orders']) && $_GET['send_all_orders'] === 'yes') {
		// Verify the nonce before processing the form
	if (isset($_GET['bosta_send_all_nonce_field']) && check_admin_referer('bosta_send_all_nonce', 'bosta_send_all_nonce_field')) {
			$page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
			$sendAllOrders = sanitize_text_field($_GET['send_all_orders']);
			echo esc_html($sendAllOrders);
			$orders = wc_get_orders(array(
					'limit' => 25,
					'paged' => $page_num,
					'return' => 'ids',
			));
			bosta_sync_handle('', 'sync_to_bosta', $orders);
	} else {
			echo "Invalid nonce! Something went wrong.";
	}
}

}

add_action('manage_posts_extra_tablenav', 'bosta_fetch_status_top_bar_button', 20);
function bosta_fetch_status_top_bar_button($which)
{
	global $pagenow, $typenow;
	if ('shop_order' === $typenow && 'edit.php' === $pagenow && 'top' === $which) {
			?>
			<div class="alignright actions custom">
							<?php wp_nonce_field('bosta_fetch_status_nonce', 'bosta_fetch_status_nonce_field'); ?>
							<input type="hidden" name="page_num" value="<?php echo esc_attr($_GET['paged']); ?>">
							<button type="submit" name="fetch_status" style="height:32px;" class="danger-button" value="yes"> <?php
									echo __('Refresh status ', 'woocommerce') . '<img src=' . esc_url(plugins_url('assets/images/refreshIcon.png', __FILE__)) . ' alt="Bosta" style="height:17px; width:20px;">'; ?></button>
			</div>
			<br>
			<br>
			<?php
	}
	if ('shop_order' === $typenow && 'edit.php' === $pagenow && isset($_GET['fetch_status']) && $_GET['fetch_status'] === 'yes') {
			// Verify the nonce before processing the form
			if (isset($_GET['bosta_fetch_status_nonce_field']) && check_admin_referer('bosta_fetch_status_nonce', 'bosta_fetch_status_nonce_field')) {
					$page_num = isset($_GET['page_num']) ? $_GET['page_num'] : 1;
					$fetchStatus = sanitize_text_field($_GET['fetch_status']);
					echo esc_html($fetchStatus);
					$orders = wc_get_orders(array(
							'limit' => 25,
							'paged' => $page_num,
							'return' => 'ids',
					));
					bosta_fetch_latest_status_action('fetch_latest_status', $orders, $page_num);
			} else {
					echo "Invalid nonce! Something went wrong.";
			}
	}
}

function bosta_fetch_latest_status_action($action, $order_ids, $page_num)
{
	if ($action != 'fetch_latest_status') {
		return;
	}

	$orderArray = array();

	$APIKey = bosta_get_api_key();
	if (empty($APIKey)) {
		$redirect_url = admin_url('admin.php?') . 'page=wc-settings&tab=shipping&section=bosta';
		wp_redirect($redirect_url);
		return;
	}
	$trackingNumbers = '';
	foreach ($order_ids as $order) {
		$trackingNumber = get_post_meta($order, 'bosta_tracking_number', true);
		if (!empty($trackingNumber)) {

			$orderObject = new stdClass();
			$orderObject->id = $order;
			$orderObject->trackingNumber = $trackingNumber;
			$orderArray[] = $orderObject;
			$trackingNumbers .= ($trackingNumbers ? ',' : '') . $trackingNumber;
		}
	}
	$url = BOSTA_ENV_URL .  '/deliveries/search?trackingNumbers=' . $trackingNumbers;
	$result = wp_remote_get($url, array(
		'timeout' => 30,
		'method' => 'GET',
		'headers' => array(
			'Content-Type' => 'application/json',
			'authorization' => $APIKey,
			'X-Requested-By' => 'WooCommerce',
			'X-Plugin-Version' => PLUGIN_VERSION
		),
	));

	if ($result['response']['code'] != 200) {
		$result = json_decode($result['body']);
		if (gettype($result) == 'array') {
			$error = $result[0]->message;
		} else {
			$error = $result->message;
		}
		echo "Something went wrong: " . esc_html($error);
	} else {
		$result = json_decode($result['body']);
		$deliveries = $result->deliveries;
		for ($i = 0; $i < count($deliveries); $i++) {
			for ($j = 0; $j < count($orderArray); $j++) {
				if ($deliveries[$i]->trackingNumber == $orderArray[$j]->trackingNumber) {
					update_post_meta($orderArray[$j]->id, 'bosta_status', $deliveries[$i]
						->state
						->value);
					update_post_meta($orderArray[$j]->id, 'bosta_delivery_date', $deliveries[$i]
						->state
						->deliveryTime);
					update_post_meta($orderArray[$j]->id, 'bosta_customer_phone', $deliveries[$i]
						->receiver
						->phone);
				}

			}
		}
		$page_num = $page_num ? $page_num : 1;
		$redirect_url = admin_url('edit.php?') . 'post_type=shop_order&paged=' . $page_num;
		wp_redirect($redirect_url);
		exit;
	}
}

add_action('manage_posts_extra_tablenav', 'bosta_filter_by_status', 30, 6);

function bosta_filter_by_status($which)
{
	global $pagenow, $typenow;

	if ('shop_order' === $typenow && 'edit.php' === $pagenow && 'top' === $which) {
		?>
        <p style="font-size: 15px;
   font-weight: 600;">Filter with bosta status:</p>
        <div class="bosta_status_search_tags">
            <input type="button" value="Created" class="createdStatus" id="createdStatus"
                   onClick="document.location.href='edit.php?s=created&post_type=shop_order&paged=1'" />
            <input type="button" value="Delivered" class="deliveredcreatedStatus" id="Delivered"
                   onClick="document.location.href='edit.php?s=delivered&post_type=shop_order&paged=1'" />
            <input type="button" value="Terminated" class="terminatedtatus" id="terminatedStatus"
                   onClick="document.location.href='edit.php?s=terminated&post_type=shop_order&paged=1'" />
            <input type="button" value="Returned" class="returnedStatus" id="returnedStatus"
                   onClick="document.location.href='edit.php?s=returned&post_type=shop_order&paged=1'" />
        </div>
		<?php
	}
}

add_filter('bulk_actions-edit-shop_order', 'bosta_print_awb', 20);
function bosta_print_awb($actions)
{
	$actions['print_bosta_awb'] = __('Print Bosta AirWaybill', 'woocommerce');
	return $actions;
}

add_filter('handle_bulk_actions-edit-shop_order', 'bosta_print_awb_handle', 10, 3);
function bosta_print_awb_handle($redirect_to, $action, $order_ids)
{
	if ($action != 'print_bosta_awb') {
		return;
	}

	$APIKey = bosta_get_api_key();
	if (empty($APIKey)) {
		$redirect_url = admin_url('admin.php?') . 'page=wc-settings&tab=shipping&section=bosta';
		wp_redirect($redirect_url);
		return;
	}

	$ids = '';
	foreach ($order_ids as $order) {
		$deliveryId = get_post_meta($order, 'bosta_delivery_id', true);
		if (!empty($deliveryId)) {
			$ids .= ($ids ? ',' : '') . $deliveryId;
		}
	}
    $ids = rtrim($ids, ',');
	$url = BOSTA_ENV_URL .  '/admin/deliveries/printawb?ids=' . $ids . '&lang=ar';
	$result = wp_remote_get($url, array(
		'timeout' => 30,
		'method' => 'GET',
		'headers' => array(
			'Content-Type' => 'application/json',
			'authorization' => $APIKey,
			'X-Requested-By' => 'WooCommerce',
			'X-Plugin-Version' => PLUGIN_VERSION
		),
	));
	if ($result['response']['code'] != 200) {
		$result = json_decode($result['body']);
		if (gettype($result) == 'array') {
			$error = $result[0]->message;
		} else {
			$error = $result->message;
		}
		echo "Something went wrong: " . esc_html($error);
	} else {
		$result = json_decode($result['body']);

		$decoded = base64_decode($result->data, true);

		header('Content-Type: application/pdf');
		header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
		header('Pragma: public');
		ob_clean();
		flush();
		echo $decoded;
	}
}

add_action('woocommerce_update_order', 'bosta_action_woocommerce_update_order');
function bosta_action_woocommerce_update_order($order_get_id)
{
	$bostaStatus = get_post_meta($order_get_id, 'bosta_status', true);
	if ($bostaStatus != 'Pickup requested' && $bostaStatus != 'Created') {
		return;
	}

	$APIKey = bosta_get_api_key();
	$deliveryId = get_post_meta($order_get_id, 'bosta_delivery_id', true);
	$order = wc_get_order($order_get_id);

	$order = json_decode($order);
	$newOrder = new stdClass();
	$newOrder->notes = $order->customer_note;
	$newOrder->receiver = new stdClass();
	$newOrder
		->receiver->firstName = $order
		->shipping->first_name;
	$newOrder
		->receiver->lastName = $order
		->shipping->last_name;
	if ($order->meta_data[0]->key == '_shipping_phone') {
		$newOrder
			->receiver->phone = $order->meta_data[0]->value;
	} else {
		$newOrder
			->receiver->phone = $order
			->billing->phone;
	}
	$newOrder->dropOffAddress = new stdClass();
	$newOrder
		->dropOffAddress->firstLine = $order
		->shipping->address_1;
		$newOrder
		->dropOffAddress->secondLine = $order
		->shipping->address_2;

	global $resultStates;

	$result = preg_split("/\s*(?<!\w(?=.\w))[\-[\]()]\s*/", $order
		->shipping
		->state);

	global $resultCities;
	$shippingCityName = $resultCities[$order
		->shipping
		->state];
	$newOrder
		->dropOffAddress->city = $shippingCityName;
	

	if ($order->payment_method == 'cod') {
		$newOrder->cod = (float) $order->total;
	}

	wp_remote_request(BOSTA_ENV_URL .  '/deliveries/' . $deliveryId, array(
		'timeout' => 30,
		'method' => 'PUT',
		'headers' => array(
			'Content-Type' => 'application/json',
			'authorization' => $APIKey,
			'X-Requested-By' => 'WooCommerce',
			'X-Plugin-Version' => PLUGIN_VERSION
		),
		'body' => json_encode($newOrder),
	));
}

add_action('woocommerce_update_order', 'bosta_action_woocommerce_update_order');

add_action('add_meta_boxes', 'bosta_add_custom_box');
if (!function_exists('bosta_add_custom_box')) {
	function bosta_add_custom_box()
	{
		add_meta_box('wporg_box_id', __('My Field', 'woocommerce'), 'bosta_wporg_custom_box_html', 'shop_order', 'side', 'core');
	}
}

add_action('add_meta_boxes', 'bosta_add_custom_box');
function bosta_wporg_custom_box_html($post)
{
	$screen = get_current_screen();
	if (!isset($screen->post_type) || 'shop_order' != $screen->post_type) {
		return;
	}

	$APIKey = bosta_get_api_key();
	$trackingNumber = get_post_meta($post->ID, 'bosta_tracking_number', true);
	if (empty($trackingNumber)) {
		return;
	}

	$url = BOSTA_ENV_URL .  '/deliveries/search?trackingNumbers=' . $trackingNumber;
	$result = wp_remote_get($url, array(
		'timeout' => 30,
		'method' => 'GET',
		'headers' => array(
			'Content-Type' => 'application/json',
			'authorization' => $APIKey,
			'X-Requested-By' => 'WooCommerce',
			'X-Plugin-Version' => PLUGIN_VERSION
		),
	));

	if (is_wp_error($result)) {
		$error_message = $result->get_error_message();
		echo "Something went wrong: " . esc_html($error_message);
	} else {
		$result = json_decode($result['body']);
		$delivery = $result->deliveries[0];

		if ($delivery
			    ->state->value != 'Created') {
			?>
            <script>
                let div = document.createElement("div");
                let p = document.createElement("p");
                let textnode = document.createTextNode(" The order is being shipped by bosta. Any updating or deleting on the order info will not reflect to bosta system. For support email help@bosta.co");
                p.appendChild(textnode);
                div.appendChild(p);
                div.setAttribute('class', 'error error-note');
                const parent = document.getElementsByClassName("wrap")[0];
                parent.insertBefore(div, parent.children[3]);


            </script>
			<?php
		}
	}
}
add_action('wp_trash_post', 'bosta_custom_delete_function');
function bosta_custom_delete_function($id)
{
	$screen = get_current_screen();
	if (!isset($screen->post_type) || 'shop_order' != $screen->post_type) {
		return;
	}
	$bostaStatus = get_post_meta($id, 'bosta_status', true);
	if ($bostaStatus != 'Pickup requested' && $bostaStatus != 'Created') {
		return;
	}
	$APIKey = bosta_get_api_key();
	$deliveryId = get_post_meta($id, 'bosta_delivery_id', true);
	wp_remote_request(BOSTA_ENV_URL .  '/deliveries/' . $deliveryId, array(
		'timeout' => 30,
		'method' => 'DELETE',
		'headers' => array(
			'Content-Type' => 'application/json',
			'authorization' => $APIKey,
			'X-Requested-By' => 'WooCommerce',
			'X-Plugin-Version' => PLUGIN_VERSION
		),
	));
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'bosta_plugin_action_links');
function bosta_plugin_action_links($links)
{
	$plugin_links = array(
		'<a href="' . menu_page_url('bosta-woocommerce', false) . '">' . __('Settings') . '</a>',
	);
	return array_merge($plugin_links, $links);
}
add_action('plugins_loaded', 'bosta_init_shipping_class');
function bosta_init_shipping_class()
{
	//check if woocommerce is activated
	if (!class_exists('WooCommerce')) {
		return;
	}
	if (!class_exists('bosta_Shipping_Method')) {
		class bosta_Shipping_Method extends WC_Shipping_Method
		{
			public function __construct()
			{
				parent::__construct();

				$this->id = 'bosta';
				$this->method_title = __('Bosta Shipping', 'bosta');
				$this->method_description = __('Custom Shipping Method for bosta', 'bosta');
				$this->init();
				$this->enabled = isset($this->settings['enabled']) ? $this->settings['enabled'] : 'yes';
				$this->title = isset($this->settings['title']) ? $this->settings['title'] : __('bosta Shipping', 'bosta');
			}
			function init()
			{
				$this->init_form_fields();
				$this->init_settings();
				add_action('woocommerce_update_options_shipping_' . $this->id, array(
					$this,
					'process_admin_options',
				));
			}
			function init_form_fields()
			{
				$this->form_fields = array(
					'APIKey' => array(
						'title' => __('APIKey', 'bosta'),
						'type' => 'text',
					),
					'ProductDescription' => array(
						'label' => 'Enable Woocomerce product description',
						'title' => __('Product description', 'bosta'),
						'type' => 'checkbox',
						'default' => 'yes',
					),
					'OrderRef' => array(
						'label' => 'Enable Woocomerce order reference',
						'title' => __('Order reference', 'bosta'),
						'type' => 'checkbox',
						'default' => 'yes',
					),
					'AllowToOpenPackage' => array(
						'label' => 'Allow Customer to open package',
						'title' => __('Allow to open package', 'bosta'),
						'type' => 'checkbox',
						'default' => 'no',
					),
					'AllowNewZonesInCheckout' => array(
						'label' => 'Allow new Bosta zones in checkout page',
						'title' => __('Allow New Zones In Checkout', 'bosta'),
						'type' => 'checkbox',
						'default' => 'no',
					),
					'ClearZoningCache' => array(
						'label' => 'Clear Zoning Cache',
						'title' => __('ClearZoningCache', 'bosta'),
						'type' => 'checkbox',
						'default' => 'no',
					),
				);
			}
		}
	}
}
add_action('woocommerce_shipping_init', 'bosta_init_shipping_class');
function bosta_add_shipping_method($methods)
{
	$methods[] = 'bosta_Shipping_Method';
	return $methods;
}
add_filter('woocommerce_shipping_methods', 'bosta_add_shipping_method');

add_filter('woocommerce_shop_order_search_fields', 'bosta_woocommerce_shop_order_search_order_custom_fields');
function bosta_woocommerce_shop_order_search_order_custom_fields($search_fields)
{
	$search_fields[] = 'bosta_tracking_number';
	$search_fields[] = '_order_total';
	$search_fields[] = 'bosta_customer_phone';
	$search_fields[] = 'bosta_status';
	return $search_fields;
}
//For adding order details data from bosta API
add_filter('woocommerce_admin_order_preview_get_order_details', 'bosta_admin_order_preview_add_custom_meta_data', 10, 2);
function bosta_admin_order_preview_add_custom_meta_data($data, $order)
{
	$trackingNumber = get_post_meta($order->id, 'bosta_tracking_number', true);
	$APIKey = bosta_get_api_key();
	if (!empty($trackingNumber)) {
		$url = BOSTA_ENV_URL . '/deliveries/' . $trackingNumber;
		$result = wp_remote_get($url, array(
			'timeout' => 30,
			'method' => 'GET',
			'headers' => array(
				'Content-Type' => 'application/json',
				'authorization' => $APIKey,
				'X-Requested-By' => 'WooCommerce',
				'X-Plugin-Version' => PLUGIN_VERSION
			),
		));
		if (is_wp_error($result)) {
			return false;
		} else {
			$orderDetails = json_decode($result['body']);
			$data['trackingNumber'] = $orderDetails->trackingNumber;
			$data['type'] = $orderDetails
				->type->value;
			$data['status'] = $orderDetails
				->state->value;
			$data['cod'] = $orderDetails->cod ?: '0';
			$data['notes'] = $orderDetails->notes ?: 'N/A';
			$data['itemsCount'] = $orderDetails
				->specs
				->packageDetails->itemsCount ?: 'N/A';
			$data['createdAt'] = $orderDetails->createdAt ?: 'N/A';
			$data['updatedAt'] = $orderDetails->updatedAt ?: 'N/A';
			$data['fullName'] = $orderDetails
				->receiver->fullName ?: 'N/A';
			$data['phone'] = $orderDetails
				->receiver->phone ?: 'N/A';
			$data['dropOffAddressCity'] = $orderDetails
				->dropOffAddress
				->city->name;
			$data['dropOffAddressZone'] = $orderDetails
				->dropOffAddress
				->zone->name;
			$data['dropOffAddressDistrict'] = $orderDetails
				->dropOffAddress
				->district->name;
			$data['dropOffAddressFistLine'] = $orderDetails
				->dropOffAddress->firstLine;
			$data['dropOffAddressBuilding'] = $orderDetails
				->dropOffAddress->buildingNumber ?: 'N/A';
			$data['dropOffAddressFloor'] = $orderDetails
				->dropOffAddress->floor ?: 'N/A';
			$data['dropOffAddressApartment'] = $orderDetails
				->dropOffAddress->apartment ?: 'N/A';
			$data['pickupAddressCity'] = $orderDetails
				->pickupAddress
				->city->name;
			$data['pickupAddressZone'] = $orderDetails
				->pickupAddress
				->zone->name;
			$data['pickupAddressDistrict'] = $orderDetails
				->pickupAddress
				->district->name;
			$data['pickupAddressFistLine'] = $orderDetails
				->pickupAddress->firstLine;
			$data['pickupAddressBuilding'] = $orderDetails
				->pickupAddress->buildingNumber ?: 'N/A';
			$data['pickupAddressFloor'] = $orderDetails
				->pickupAddress->floor ?: 'N/A';
			$data['pickupAddressApartment'] = $orderDetails
				->pickupAddress->apartment ?: 'N/A';
			$data['pickupRequestId'] = $orderDetails->pickupRequestId ?: 'N/A';
			$data['deliveryAttemptsLength'] = $orderDetails->deliveryAttemptsLength;
			$data['outboundActionsCount'] = $orderDetails->outboundActionsCount;

			if (!empty($orderDetails->sla)) {
				$data['promise'] = $orderDetails
					->sla
					->e2eSla->isExceededE2ESla ? 'Not met' : 'Met';
			} else {
				$data['promise'] = 'Not started yet';
			}
			for ($x = 0; $x < count($orderDetails->timeline); $x++) {
				$data["timeline_value_$x"] = $orderDetails->timeline[$x]->value;
				$data["timeline_date_$x"] = $orderDetails->timeline[$x]->date;
				$data["timeline_done_$x"] = $orderDetails->timeline[ $x ]->done ? 'status_done' : 'status_not_done';
				if ( $orderDetails->timeline[ $x ]->done ) {
					$data["timeline_next_action"] = $orderDetails->timeline[$x]->nextAction ?: 'N/A';
					$data["timeline_shipment_age"] = $orderDetails->timeline[$x]->nextAction ?: 'N/A';
				}
			}
			for ($count = 0; $count < count($orderDetails->history); $count++) {
				$data["tracking_title_$count"] = $orderDetails->history[$count]->title;
				$data["tracking_date_$count"] = $orderDetails->history[$count]->date;
				for ($j = 0; $j < count($orderDetails->history[$count]->subs); $j++) {
					$data["tracking_subs_title_$count$j"] = $orderDetails->history[$count]->subs[$j]->title;
					$data["tracking_subs_date_$count$j"] = $orderDetails->history[$count]->subs[$j]->date;
				}
			}
		}
	}
	return $data;
}
// Display custom values in Order preview
add_action('woocommerce_admin_order_preview_start', 'bosta_custom_display_order_data_in_admin');
function bosta_custom_display_order_data_in_admin()
{
	echo "
       <h4 class='table-title'>Order Timeline</h4>
   <div class='timeline-table'>
      <div class='timeline-status'>
         ";
	for ($x = 0; $x < 7; $x++) {
		echo "
         <div><span class={{data.timeline_done_".esc_attr($x)."}}></span>  <span class={{data.timeline_done_".esc_attr($x)."}}_line></span> <br/><span class='timeline_title'>{{data.timeline_value_".esc_attr($x)."}}</span><br/>{{data.timeline_date_".esc_attr($x)."}} </div>
         ";
	}

	echo "
      </div>
      <span class='timeline-next-action'><span class='next-action-label'>Next Action: </span> {{data.timeline_next_action}}</span>
   </div>
   ";
	echo "
       <h4 class='table-title'>Order Tracking</h4>
   <div class='timeline-table'>
      <div class=''>
         ";
	for ($count = 0; $count < 6; $count++) {

		echo "<div class='tracking'><span class='tracking_title'>{{data.tracking_title_".esc_attr($count)."}}</span> <span class='tracking_date'>{{data.tracking_date_".esc_attr($count)."}}</span> </div>
         ";
		for ($i = 0; $i < 4; $i++) {
			echo "<div class='tracking'><span class='tracking_subs_title'>{{data.tracking_subs_title_".esc_attr($count)." ".esc_attr($i)."}}</span> <span class='tracking_date'>{{data.tracking_subs_date_".esc_attr($count)." ".esc_attr($i)."}}</span> </div>";
		}
	}

	echo "
      </div>
   </div>
   ";

	echo "
   <h4 class='table-title'>Order details</h4>
   <table class='order-details-table'>
      <tr>
         <th>Bosta tracking number</th>
         <th>Type</th>
         <th>Status</th>
      </tr>
      <tr>
         <td>esc_html({{data.trackingNumber}})</td>
         <td>esc_html({{data.type}})</td>
         <td>esc_html({{data.status}})</td>
      </tr>

      <tr>
         <th> Cash on delivery</th>
         <th>Created at</th>
         <th>Last update date</th>
      </tr>
      <tr>
         <td>esc_html({{data.cod}}) LE</td>
         <td>esc_html({{data.createdAt}})</td>
         <td>esc_html({{data.updatedAt}})</td>
      </tr>
      <tr>
      <th>Items count</th>
         <th>Delivery Notes</th>
      </tr>
      <tr>
      <td class='last-field'> esc_html({{data.itemsCount}}) </td>
         <td class='last-field'> esc_html({{data.notes}}) </td>
      </tr>
   </table>
   ";
	echo "
   <h4 class='table-title'>Customer Info</h4>
   <table class='order-details-table'>
      <tr>
         <th>Customer name</th>
         <th>Phone number</th>
         <th>Area,City</th>
      </tr>
      <tr>
         <td>esc_html({{data.fullName}})</td>
         <td>esc_html({{data.phone}})</td>
         <td>, esc_html({{data.dropOffAddressZone}})-esc_html({{data.dropOffAddressDistrict}}), esc_html({{data.dropOffAddressCity}})</td>
      </tr>
      <tr>
         <th>Customer address</th>
         <th>Building number</th>
         <th>Floor, Apartment</th>
      </tr>
      <tr>
         <td class='last-field'>esc_html({{data.dropOffAddressFistLine}})</td>
         <td class='last-field'>esc_html({{data.dropOffAddressBuilding}})</td>
         <td class='last-field'>esc_html({{data.dropOffAddressFloor}}), esc_html({{data.dropOffAddressApartment}})</td>
      </tr>
   </table>
   ";
	echo "
   <h4 class='table-title'>Pickup Info</h4>
   <table class='order-details-table'>
      <tr>
         <th>Street name</th>
         <th>City</th>
         <th>Area</th>
      </tr>
      <tr>
         <td>esc_html({{data.pickupAddressFistLine}})</td>
         <td>esc_html({{data.pickupAddressCity}})</td>
         <td>esc_html{{data.pickupAddressZone}}), esc_html({{data.pickupAddressDistrict}})</td>
      </tr>
      <tr>
         <th>Building</th>
         <th>Floor, Apartment</th>
         <th>Pickup ID</th>
      </tr>
      <tr>
         <td class='last-field'>esc_html({{data.pickupAddressBuilding}})</td>
         <td class='last-field'>esc_html({{data.pickupAddressFloor}}), esc_html({{data.pickupAddressApartment}})</td>
         <td class='last-field'>esc_html({{data.pickupRequestId}})</td>
      </tr>
   </table>
   ";
	echo "
   <h4 class='table-title'>Bosta Performance</h4>
   <table class='order-details-table'>
      <tr>
         <th>Delivery attempts <br/><span class='subtext'>The number of times the Bosta tried to deliver the order to your customer.</span></th>
         <th>Outbound calls <br/><span class='subtext'>The number of calls made by the outbound team to verify the star actions and take corrective actions if needed to deliver the order on time</span></th>
         <th>Delivery promise <br/><span class='subtext'>Bosta promises next day delivery (calculated from the pickup date) for orders with Cairo as the pickup and drop city. The expected delivery period increases to two or three days depending on the distance between the pick up and the drop off cities i.e. Alexandria, Delta or Upper Egypt.</span></th>
      </tr>
      <tr>
         <td class='last-field'>esc_html({{data.deliveryAttemptsLength}}) of 3 attempts</td>
         <td class='last-field'>esc_html({{data.outboundActionsCount}}) Calls  </td>
         <td class='last-field'>esc_html({{data.promise}})  </td>
      </tr>
   </table>";
}
