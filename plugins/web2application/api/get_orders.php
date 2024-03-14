<?php
/* if ( ! defined( 'ABSPATH' ) ) exit;
define('WP_DEBUG', false); */
// Init Options Global

global $w2a_options;
// $inside_app_cookie = isInAppUser();
// echo $inside_app_cookie;
?>

<?php
if (isset($_POST['start_date']) && isset($_POST['end_date']) && isset($_POST['api_key']) && isset($_POST['last_order_number']) && !empty($_POST['start_date']) && !empty($_POST['end_date']) && !empty($_POST['api_key']) && !empty($_POST['last_order_number'])) {

	if (trim($w2a_options['w2a_api_key']) == trim($_POST['api_key'])) {
		//echo 'API key OK';
		$w2ApiKey = trim($_POST['api_key']);
	} else {
		echo 'API key is wrong';
		die;
	}

	$inside_app_cookie = isInAppUser();
	// echo $inside_app_cookie;

	$start_date = $_POST['start_date']; // Start date in YYYY-MM-DD format
	$end_date = $_POST['end_date']; // End date in YYYY-MM-DD format

	$last_order_number = intval($_POST['last_order_number']);

	// Function to check if an order with a given ID exists
	function doOrderExists($order_id)
	{
		return wc_get_order($order_id) !== false;
	}

	// if we satrt from 1 that meens its new client and we need to search for the first order to start_date
	if ($last_order_number == 1) {
//echo "enter to 1 as orderID. <br>";
//echo "OrderID is : " . $last_order_number;
	
		// Get the most recent order
			$args = array(
				'limit' => 1, // We only need the last one
				'orderby' => 'date',
				'order' => 'DESC', // Ensure we're going from most recent to oldest
				'return' => 'ids', // We just need the IDs
			);

			$orders = wc_get_orders($args);

			// Check if we got an order and output the order number
			if (!empty($orders)) {
				$last_order_id = $orders[0];
				$last_order = wc_get_order($last_order_id);
				
				// Get the order number. Note that order number is not always the same as order ID due to plugins that change order numbers.
				$last_order_number = $last_order->get_order_number();

				//echo "The last order number is: " . $last_order_number;
			} else {
				echo "No orders found.";
			}
			if ($last_order_number >= 502) {
				$last_order_number = $last_order_number - 501;
			} else {
				$last_order_number = 1;
				while (!doOrderExists($last_order_number)) {
					$last_order_number++;
					//echo $last_order_number."<br>";
				}		
			
			}
		
	} //end if last oreder 1
//echo "OrderID after 1 section : " . $last_order_number;
	// Loop to find the next existing order ID
	$current_order_id = $last_order_number;
	$safeOrderLoopStop = $current_order_id + 100;

	while (!doOrderExists($current_order_id)) {
		$current_order_id++;
		if ($safeOrderLoopStop == $current_order_id) {
			echo "no more orders";
			die;
		}
	}

	//echo "The next existing order ID is: $current_order_id";
	$last_order_number = $current_order_id;



	if (strtotime($start_date) > strtotime($end_date)) {
		// Start date is larger than end date, switch their values
		$temp_date = $start_date;
		$start_date = $end_date;
		$end_date = $temp_date;
	}

	if ($last_order_number > 1) {

		// Get $order object
		$order_last_night = wc_get_order($last_order_number);



		// Get date created
		$order_last_date_created = $order_last_night->get_date_created();

		$start_date = $order_last_date_created;
	}

	$args = array(
		'status' => array('processing', 'completed'),
		// Status of the orders you want to search for
		'date_created' => $start_date . '...' . $end_date,
		// Date range query

		'limit' => -1,
		// Retrieve all matching orders
	);
	$orders = wc_get_orders($args);

	if (!empty($orders)) {
		$all_orders = array();
		foreach ($orders as $order) {
			$order_id = $order->get_id();
			$order_date = $order->get_date_created()->date('Y-m-d');
			//     $first_name = $order->get_billing_first_name();
			//    $last_name = $order->get_billing_last_name();
			//     $customer_name = $first_name . ' ' . $last_name;

			$order_temp_data = $order->get_data();
			$order_data = array(
				'id' => $order->get_id(),
				'order_date' => $order_date,
				'api_key' => $w2ApiKey,
				'inside_app'=> $inside_app_cookie,
				'status' => $order_temp_data['status'],
				'currency' => $order_temp_data['currency'],
				'discount_total' => $order_temp_data['discount_total'],
				'discount_tax' => $order_temp_data['discount_tax'],
				'shipping_total' => $order_temp_data['shipping_total'],
				'shipping_tax' => $order_temp_data['shipping_tax'],
				'cart_tax' => $order_temp_data['cart_tax'],
				'total' => $order_temp_data['total'],
				'total_tax' => $order_temp_data['total_tax'],
				'payment_method' => $order_temp_data['payment_method'],
				'payment_method_title' => $order_temp_data['payment_method_title'],
				'billing' => array(
					'first_name' => $order_temp_data['billing']['first_name'],
					'last_name' => $order_temp_data['billing']['last_name'],
					'company' => $order_temp_data['billing']['company'],
					'address_1' => $order_temp_data['billing']['address_1'],
					'address_2' => $order_temp_data['billing']['address_2'],
					'city' => $order_temp_data['billing']['city'],
					'state' => $order_temp_data['billing']['state'],
					'postcode' => $order_temp_data['billing']['postcode'],
					'country' => $order_temp_data['billing']['country'],
					'email' => $order_temp_data['billing']['email'],
					'phone' => $order_temp_data['billing']['phone'],
				),
				'shipping' => array(
					'first_name' => $order_temp_data['shipping']['first_name'],
					'last_name' => $order_temp_data['shipping']['last_name'],
					'company' => $order_temp_data['shipping']['company'],
					'address_1' => $order_temp_data['shipping']['address_1'],
					'address_2' => $order_temp_data['shipping']['address_2'],
					'city' => $order_temp_data['shipping']['city'],
					'state' => $order_temp_data['shipping']['state'],
					'postcode' => $order_temp_data['shipping']['postcode'],
					'country' => $order_temp_data['shipping']['country'],
					'phone' => $order_temp_data['shipping']['phone'],
				),
			);

			$item_data = array();
			$order = wc_get_order($order_id);
			$items = $order->get_items();
			foreach ($items as $item) {
				//	echo '<pre>';
				//		print_r($item);
				//	echo '<pre>';


				$item_data[] = array(
					'product_id' => $item->get_product_id(),
					'product_name' => $item->get_name(),
					'quantity' => $item->get_quantity(),
					'product_total' => $item->get_total(),

					'category_tree_product' => get_product_categories_with_ancestors($item->get_product_id()),
					'product_permalink' => get_permalink($item->get_product_id()),
					'product_thumbnail' => get_the_post_thumbnail_url($item->get_product_id(), 'full'),

				);

			}
			$order_data['items'] = $item_data;

			$all_orders[$order_id] = $order_data;
		}
		echo json_encode($all_orders, JSON_PRETTY_PRINT);

	} else {
		echo '<div class="my-section">';
		_e('No orders found.', 'web2application');
		echo '</div>';
	}
} else {
	echo 'wrong parameters';
}

//product main parent category				
function get_product_categories_with_ancestors($product_id, $taxonomy = 'product_cat')
{
	$categories = wp_get_post_terms($product_id, $taxonomy);

	foreach ($categories as &$category) {
		$ancestors = get_ancestors($category->term_id, $taxonomy);
		$ancestors = array_reverse($ancestors);

		$category->ancestors = array_map(function ($ancestor_id) use ($taxonomy) {
			return get_term($ancestor_id, $taxonomy);
		}, $ancestors);
	}

	return $categories;
}
//end product main parent category	

?>