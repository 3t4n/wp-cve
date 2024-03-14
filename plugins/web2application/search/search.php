<?php

add_action('wp_enqueue_scripts', 'custom_save_user_search_script');
function custom_save_user_search_script()
{
	global $w2a_options;

	if ($w2a_options['w2a_enable_search_widget'] == "1") {
		wp_enqueue_style('search-css', plugins_url() . '/web2application/css/search.css?v=' . time());

		wp_register_script('search-js', plugins_url() . '/web2application/js/search.js?v=' . time(), array('jquery'));
		wp_enqueue_script('search-js');

		wp_localize_script(
			'search-js',
			'webapp',
			array(
				'ajax_url' => admin_url('admin-ajax.php')
			)
		);
	}
}


// GET API KEY
function get_api_key()
{
	global $w2a_options;

	if (isset($w2a_options['w2a_api_key']) && !empty($w2a_options['w2a_api_key'])) {
		return $w2a_options['w2a_api_key'];
	} else {
		return false;
	}
}

add_action('wp_ajax_save_user_search', 'custom_save_user_search_func');
add_action('wp_ajax_nopriv_save_user_search', 'custom_save_user_search_func');
function custom_save_user_search_func()
{
	global $wpdb, $w2a_options;

	$response = array();

	$search_value = $_POST['s'];

	$api_key = get_api_key();

//	if (!empty($api_key)) {

		/* search functionality */
		$product_args = array(
			'post_type' => 'product',
			'post_status' => 'publish',
			's' => $search_value,
			'posts_per_page' => 6,
		);

		$product_query = new WP_Query($product_args);
		$products = $product_query->posts;

		$suggestion_counts = 5;
		$popular_suggestions = array($search_value);
		$product_count = 1;

		$rtl_class = '';
		if ($w2a_options['w2a_enable_search_widget_rtl'] == '1') {
			$rtl_class = 'rtl';
		}

		$search_result_html = '';
		$product_html = '';
		if (!empty($products)) {

			foreach ($products as $wp_product) {
				if ($product_count < $suggestion_counts) {
					$keyword = $wp_product->post_title;
					$product_link = get_permalink($wp_product);
					$suggestion_html .= '<li><a href="' . esc_url($product_link) . '?dev">' . esc_html($keyword) . '</a></li>';
				}

				$product_id = $wp_product->ID;

				$product = wc_get_product($product_id);

				// get product categories
				$product_categories = get_the_terms($product_id, 'product_cat');

				if (!empty($product_categories) && !is_wp_error($product_categories)) {
					$category_html = '<ul class="category-list">';
					foreach ($product_categories as $category) {
						$category_html .= '<li><a href="' . get_term_link($category) . '?dev">' . $category->name . '</a></li>';
					}
					$category_html .= '</ul>';
				} else {
					$category_html = __('Uncategorized', 'web2Application');
				}


				$product_title = $product->get_title();
				$product_link = $product->get_permalink();
				$product_image = $product->get_image('thumbnail');
				// $product_description = wp_trim_words($product->get_short_description(), 10);
				$product_price = $product->get_price();


				$product_price = number_format($product_price, 2);
				$currency = get_woocommerce_currency_symbol();
				$sale = '';
				$product_price_html = $product->get_price_html();

				if ($product->is_on_sale()) {
					$regular_price = (float) $product->get_regular_price();
					$sale_price = (float) $product->get_price();

					$precision = 1;
					$saving_percentage = round(100 - ($sale_price / $regular_price * 100), 1) . '%';
					$saving_price = wc_price($regular_price - $sale_price);

					$sale = '<span>' . __('Sale', 'web2application') . ' ' . $saving_percentage . '</span>';
					$product_price_html = $product->get_price_html();
				}

				$product_link_with_track = $product_link . '?dev';
				$product_html .= '<li>
				<a href="' . $product_link_with_track . '" class="product_link">
					<div class="thumbnail">
						' . $product_image . '
					</div>
					<div class="">
						<div class="title">' . $product_title . '</div>
						<div class="prices-container">
							<div class="price-list">' . $product_price_html . '</div>
						</div>
						<div class="snize-labels-wrapper">' . $sale . '</div>
					</div>
				</a>
			</li>';

				$product_count++;
			}

			$search_result_html .= '<div class="' . $rtl_class . '">
			<div class="search-suggestion-box">
				<div class="popular-suggestion results-list">
					<h4>' . __('Categories', 'web2application') . '</h4>

					' . $category_html . '

					<h4>' . __('Popular Suggestions', 'web2application') . '</h4>
					<ul>
						' . $suggestion_html . '
					</ul>
					<div class="desktop-btn-box red-btn">
						<a href="#">' . __('Show All Products', 'web2application') . '</a>
					</div>
				</div>
				<div class="products results-list">
					<h4>' . __('Products', 'web2application') . '</h4>
					<ul>
						' . $product_html . '
					</ul>

					<div class="mobile-btn-box red-btn">
						<a href="#">' . __('Show All Products', 'web2application') . '</a>
					</div>
				</div>
			</div>
		</div>';
		} else {

			$search_result_html = '<p>' . __('Sorry, nothing found for', 'web2Application') . ' ' . $search_value . '.</p>';
		}

		$response['result_html'] = $search_result_html;
//	} else {
//		$response['result_html'] = __('API Key is missing or empty. Please Check Your API.', 'web2Application');
//	}

	echo json_encode($response);
	die;

}


// Set the cookie when the user visit the site
add_action('wp_head', 'set_user_cookie');

function set_user_cookie()
{
	$cookie_name = "aUserCookieID";
	$expiration_time = time() + (20 * 24 * 60 * 60);

	if (!isset($_COOKIE[$cookie_name])) {
		$cookie_value = rand();
		setcookie($cookie_name, $cookie_value, $expiration_time, '/');
	} else {
		$cookie_value = $_COOKIE[$cookie_name];
		setcookie($cookie_name, $cookie_value, $expiration_time, '/');
	}

	// $cookie_name = "appUserToken";
	// $expiration_time = time() + (20 * 24 * 60 * 60);

	// if (!isset($_COOKIE[$cookie_name])) {
	// 	$cookie_value = 1;
	// 	setcookie($cookie_name, $cookie_value, $expiration_time, '/');
	// } else {
	// 	$cookie_value = $_COOKIE[$cookie_name];
	// 	setcookie($cookie_name, $cookie_value, $expiration_time, '/');
	// }
}

function isInAppUser()
{
	if (isset($_COOKIE['appUserToken']) || isset($_GET['dev'])) {
		$inside_app_cookie = 1;
	} else {
		$inside_app_cookie = 0;
	}

	return $inside_app_cookie;
}

function api_call_data()
{
	global $product;
	$inside_app_cookie = isInAppUser();
	$api_key = get_api_key();

	$date = '';
	$product_id = '';
	$product_title = '';
	$product_categories = '';
	$product_categories_id = '';
	$product_categories_name = '';
	$product_categories_main_id = '';
	$product_categories_main_name = '';

	if (is_cart()) {
		$cart = WC()->cart;

		foreach ($cart->get_cart() as $cart_item_key => $cart_item) {

			$product = $cart_item['data'];

			$date = current_datetime()->format('Y-m-d');
			$product_id = $product->get_id();
			$product_title = $product->get_title();
			$product_categories = get_product_categories_with_subcategories($product_id);
			$product_categories_id = $product_categories['id'];
			$product_categories_name = $product_categories['name'];
			$product_categories_main_id = $product_categories['main_id'];
			$product_categories_main_name = $product_categories['main_name'];

		}
	}

	if (is_product_category()) {
		$category = get_queried_object();

		$date = current_datetime()->format('Y-m-d');
		$product_categories_id = $category->term_id;
		$product_categories_name = $category->name;
	}

	if (is_a($product, 'WC_Product')) {

		$date = current_datetime()->format('Y-m-d');
		$product_id = $product->get_id();
		$product_title = $product->get_title();
		$product_categories = get_product_categories_with_subcategories($product_id);
		$product_categories_id = $product_categories['id'];
		$product_categories_name = $product_categories['name'];
		$product_categories_main_id = $product_categories['main_id'];
		$product_categories_main_name = $product_categories['main_name'];

	}
	$product_data = array(
		'api_key' => $api_key,
		'user_cookie_id' => isset($_COOKIE['aUserCookieID']) ? $_COOKIE['aUserCookieID'] : '',
		'inside_app' => $inside_app_cookie,
		'user_email' => is_user_logged_in() ? wp_get_current_user()->user_email : '',
		'date' => $date,
		'product_id' => $product_id,
		'product_name' => $product_title,
		'product_categories_id' => $product_categories_id,
		'product_categories_name' => $product_categories_name,
		'product_main_category_id' => $product_categories_main_id,
		'product_main_category_name' => $product_categories_main_name,
	);
	return $product_data;

	// print_r($product_data);
}


// use cURL and send json in api code
add_action('woocommerce_before_shop_loop', 'send_product_data_to_remote_server');
add_action('woocommerce_before_single_product_summary', 'send_product_data_to_remote_server', 5);
add_action('woocommerce_after_cart_table', 'send_product_data_to_remote_server');

function send_product_data_to_remote_server()
{
	// Get the app_id
	$path = W2A_APP_DATA_DIR . 'web2appdata.json';

	if (file_exists($path)) {
		$json_data = file_get_contents($path);

		if ($json_data) {
			$data = json_decode($json_data, true);
			if ($data && isset($data['app_id'])) {
				$app_id = $data['app_id'];
			}
		}
	}

	if (isset($_GET['dev'])) {

		$product_data = api_call_data();
		$json_data = json_encode($product_data);

		$remote_server_url = 'https://web2application.com/w2a/engage/engage-api/exept-wp-serach-results.php?appnumber=' . $app_id;

		// Initialize cURL session
		$ch = curl_init($remote_server_url);

		// Set cURL options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

		$response = curl_exec($ch);

		// Check for cURL errors
		if (curl_errno($ch)) {
			echo 'cURL Error: ' . curl_error($ch);
		}
		curl_close($ch);

	} else {

		if (is_product()) {
			if (is_singular()) {
				$product_data = api_call_data();
				$file_path = plugin_dir_path(__FILE__) . 'product.json';

				$existing_data = [];
				if (file_exists($file_path)) {
					$existing_data = json_decode(file_get_contents($file_path), true);
				}

				$data_exists = false;
				foreach ($existing_data as $existing_item) {
					if ($existing_item === $product_data) {
						$data_exists = true;
						break;
					}
				}

				if (!$data_exists) {
					$existing_data[] = $product_data;

					$json_data = json_encode($existing_data);
					file_put_contents($file_path, $json_data);
				}

				if (!empty($existing_data)) {
					if (count($existing_data) >= 250) {
						$last_data = json_encode($existing_data);

						$remote_server_url = 'https://web2application.com/w2a/engage/engage-api/exept-wp-products-hits.php?appnumber=' . $app_id;
						$ch = curl_init($remote_server_url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POST, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $last_data);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

						$response = curl_exec($ch);

						if (curl_errno($ch)) {
							echo 'cURL Error: ' . curl_error($ch);
						}
						curl_close($ch);

						$existing_data = [];
						$json_data = json_encode($existing_data);
						file_put_contents($file_path, $json_data);
					}
				}
			}
		} else if (is_cart()) {
			// $product_data = api_call_data();
			// $json_data = json_encode($product_data);
			// do_action('woocommerce_before_send_email', $json_data, 10, 2);
		}
	}
}


// add_action('woocommerce_before_send_email', 'my_custom_function');

// function my_custom_function($json_data)
// {
// 	$file_path = plugin_dir_path(__FILE__) . 'myfile.json';
// 	file_put_contents($file_path, $json_data);
// }


// add_action('wp_ajax_wcf_ca_preview_email_send', 'abc');
// function abc()
// {
	
// }

// Get categories
function get_product_categories_with_subcategories($product_id)
{
	$product_categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'ids'));

	if (empty($product_categories)) {
		return array(
			'id' => '',
			'name' => '',
			'main_id' => '',
			'main_name' => '',
		);
	}

	$category_info = array(
		'id' => array(),
		'name' => array(),
	);

	foreach ($product_categories as $category_id) {

		$categories = get_term_children($category_id, 'product_cat');
		$categories[] = $category_id;

		$category_names = array();
		foreach ($categories as $category) {
			$term = get_term($category, 'product_cat');
			$category_names[] = $term->name;
		}

		$category_info['id'][] = implode(', ', $categories);
		$category_info['name'][] = implode(', ', $category_names);
	}

	return array(
		'id' => implode(', ', $category_info['id']),
		'name' => implode(', ', $category_info['name']),
		'main_id' => $product_categories[0],
		'main_name' => get_term($product_categories[0], 'product_cat')->name,
	);
}