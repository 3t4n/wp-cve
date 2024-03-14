<?php

add_filter('wpc_lesson_content', 'wpc_woo_restrict_content', 10, 3);

function wpc_woo_restrict_content( $content, $lesson_id, $course_id = null ){
	if ( ! function_exists( 'is_plugin_active' ) ){
	    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}

	// Checks for old stand alone WooCommerce integration plugin and links to single lesson template for backward compatibility.
	if(is_plugin_active( 'wp-courses-woocommerce/wp-courses-woocommerce.php' && get_post_type() !== 'lesson') === true) {
		return __('This Lesson has Moved.', 'wp-courses') . '<br><a class="wpc-btn" href="' . get_the_permalink( $lesson_id ) . '">' . __('View Lesson', 'wp-courses') . '</a>';
	}

	if(!class_exists( 'WooCommerce' )){
		return $content;
	}

	if(WPCP_ACTIVE === false) {
		return $content;
	}

	$show_restricted_to_admin = get_option( 'wpc_woo_show_restricted_content_to_admin' );
	if ( current_user_can( 'administrator' ) && $show_restricted_to_admin == 'true' ){
		$msg = '<div class="wpc-alert-message">' . __('You are seeing <b>all lessons</b> because you are an adminstrator and have selected an option to see unrestricted and restricted content under: WP Courses > Settings.', 'wp-courses') . '</div>';
		return $msg . $content;
	}

	if(!function_exists( 'wpc_woo_has_bought' )){
		return $content;
	}

	$has_bought = false;
	$product_id = false;
	$product_course_id = false;

	$current_user = wp_get_current_user();
	$lesson_restriction = get_post_meta($lesson_id, 'wpc-lesson-restriction', true);
	$lesson_content_drip_days = get_post_meta($lesson_id, 'wpc-lesson-content-drip-days', true);

	$has_bought = wpc_woo_has_bought($lesson_id);
	$can_purchase = wpc_woo_can_purchase($lesson_id);

	if($has_bought == true) {
		if(!empty($lesson_content_drip_days)) {
			$timestamp = wpc_timestamp_drip_lesson_content($lesson_id, $lesson_content_drip_days);

			if ($timestamp === 0) {
				return $content;
			} else {
				$date_string = wp_date('Y-m-d H:i', $timestamp); // Defaults to timezone from WP site settings

				return '<div class="wpc-alert-message">' . __('This content will become available on', 'wp-courses') . ' ' . $date_string . '</div>';
			}
		} else {
			return $content;
		}
	}

	if($can_purchase == false && $lesson_restriction == 'woo-paid'){
		return '<div class="wpc-alert-message">' . __('One or more of the courses that this lesson is connected to must be connected to a WooCommerce product in order to be purchasable.', 'wp-courses') . '</div>';
	}

	if($lesson_restriction == 'woo-paid' && $has_bought == false){
		$post_type = get_post_type($lesson_id);

		$all_connected_course_ids = $post_type == 'lesson' ? wpc_get_connected_course_ids($lesson_id) : wpc_get_connected_course_ids($lesson_id, 'quiz-to-course');
		$content = '<p class="wpc-content-restricted"><strong>' . __('This content can be purchased as part of the following courses', 'wp-courses') . ': </strong></p>';
		$content .= '<table class="wpc-woo-courses-table">';

		foreach($all_connected_course_ids as $course_id) {
			$product_id = wpc_get_connected_product_id($course_id);
			// only show course details and cart if has connected woo product
			if($product_id !== false){
				$content .= '<tr>';
				$content .= '<td><h3 class="wpc-h3">' . __('Purchase Course', 'wp-courses') . ': ' . get_the_title($course_id) . '</h3>';

				if(get_post_type() !== 'lesson') {
					$content .= '<a class="wpc-btn wpc-load-course" style="margin-right:10px;" data-id="' . $course_id . '">' . __('Course Details', 'wp-courses') . '</a>';
					$content .= '<a class="wpc-btn wpc-btn-solid" href="' . wc_get_cart_url() . '?add-to-cart=' . (int) $product_id . '"><i class="fa fa-shopping-cart"></i>' . __('Add to Cart ', 'wp-courses') . '</a></td>';
				} else {
					$content .= '<a class="wpc-btn" style="margin-right:10px;" href="' . get_the_permalink($course_id) . '">' . __('Course Details', 'wp-courses') . '</a>';
					$content .= '<a class="wpc-btn wpc-add-to-cart" href="' . wc_get_cart_url() . '?add-to-cart=' . (int) $product_id . '"><i class="fa fa-shopping-cart" style="margin-right: 5px;"></i>' . __('Add to Cart ', 'wp-courses') . '</a></td>';
				}
				
				$content .= '</tr>';
			}
		}

		$content .= '</table>';

		return $content;

	}

	return $content;
}

function wpc_woo_profile_content($user_id){
	$purchased_courses = wpc_woo_get_purchased_course_ids($user_id);

	if($purchased_courses === false) {
		return 'No results';
	}

	$html = '<table class="wpc-table wpc-fade">';
		$html .= '<thead><tr><th></th><th>' . __('Title', 'wp-courses') . '</th><th>' . __('Viewed Percent', 'wp-courses') . '</th><th>' . __('Completed Percent', 'wp-courses') . '</th></tr></thead>';
		$html .= '<tbody>';
			foreach($purchased_courses as $course_id) {

				$link = get_the_permalink($course_id);

				$html .= '<tr>';
					$html .= '<td><button data-id="' . $course_id . '" class="wpc-load-course wpc-btn wpc-btn-sm wpc-btn-solid wpc-btn-round">' . __('Details', 'wp-courses') . '</button></td>';

					$courses_shortcode_url = wpc_get_main_shortcode_page_url();
					$first_lesson_id = wpc_get_course_first_lesson_id($course_id);
					if ($courses_shortcode_url) {
						$params = array(
							'view' => 'single-lesson',
							'course_id' => $course_id,
							'lesson_id' => $first_lesson_id,
							'page' => 'null',
							'category' => 'null',
							'orderby' => 'null',
							'search' => 'null',
						);
						$courses_hash = wpc_get_courses_hash($params);
						$link = $courses_shortcode_url . $courses_hash;
					}
					$title = get_the_title($course_id);
					$html .= '<td><a class="wpc-link" href="' . $link . '">' . $title . '</a></td>';

					$html .= '<td>' . wpc_get_progress_bar($course_id, $user_id, false, false, 'rgb(79, 100, 109)') . '</td>';
					$html .= '<td>' . wpc_get_progress_bar($course_id, $user_id, true) . '</td>';
				$html .= '</tr>';

			}

		$html .= '</tbody>';
	$html .= '</table>';

	return $html;

}

function wpc_get_email_options($user_id){

	$opt_in = get_option('wpc_opt_in');
	$status = get_user_meta($user_id, 'wpc-email-status', true);

	if($opt_in != 'true' && empty($status) || $status == 'false') {
		$checked = '';
	} else{
		$checked = 'checked=true'; // Not HTML standard, but needed for wp_kses
	}

	return '<div class="wpc-material wpc-flex-container wpc-profile-option wpc-fade">
		<div class="wpc-flex-8 wpc-flex-no-margin">
			<label>' . __('Yes, send me emails that are triggered by my course progress.', 'wp-courses') . '</label>
		</div>
		<div class="wpc-flex-4 wpc-flex-no-margin">
			<div class="wpc-option wpc-option-toggle">
				<label class="wpc-switch" for="wpc-opt-in">
					<input type="checkbox" class="wpc-ajax-user-meta-option" id="wpc-opt-in" data-type="text" data-user-id="' . $user_id . '" data-key="wpc-email-status" value="true" ' . $checked . '/>
					<div class="wpc-slider wpc-round"></div>
				</label>
			</div>
		</div>
	</div>';

}

/** 
* Returns an array of purchased course ID's
* @param int $user_id The user ID you'd like to get purchased courses for
* @return array An array of purchased course ID's or false if none
*/

function wpc_woo_get_purchased_course_ids($user_id){
	$user = get_user_by('ID', $user_id);

	$args = array(
		'post_type'			=> 'course',
		'posts_per_page'	=> -1,
	);
	$query = new WP_Query($args);
	$course_ids = array();

	while($query->have_posts()) {
		$query->the_post();
		$course_id = get_the_ID();

		try { // Function throws error if user has nothing bought so far
			$product_id = wpc_get_connected_product_id( $course_id );
		} catch (\Error $e) {
			$product_id = false;
		}
		
		$has_bought = wc_customer_bought_product( $user->user_email, $user->ID, $product_id );

		if( $has_bought !== false ) {
			$course_ids[] = $course_id;
		}
	}

	wp_reset_postdata();

	if(empty($course_ids)) {
		return false;
	} else {
		return $course_ids;
	}

}

/** 
* returns an add to cart button for a course if it's linked to a WooCommerce product
* @param int $course_id Course ID for linked product add to cart button
* @return string Add to cart button which redirects to cart or false if course has already been purcahsed and linked product exists
*/

function wpc_woo_get_add_to_cart_button($course_id){

	if(function_exists('wpc_get_connected_product_id') === false) {
		return;
	}

	if(!class_exists( 'WooCommerce' )){
		return;
	}

	global $woocommerce;

	$in_cart = false;

	$cart_url = wc_get_cart_url();

	$product_id = wpc_get_connected_product_id($course_id);

	$current_user = wp_get_current_user();

	$has_bought = wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product_id );

	if($product_id != false && $has_bought != true ){
	
		$cart = WC()->cart->get_cart();
	    foreach($cart as $item) { 
	        $cart_product_id =  $item['product_id']; 
	        if( $cart_product_id == $product_id ){
				$in_cart = true;
				break;
	        } else {
	        	$in_cart = false;
	        }
	    }

	    if($in_cart == false) {
	    	return '<a class="wpc-btn wpc-add-to-cart" href="' . $cart_url . '?add-to-cart=' . (int) $product_id . '"><i class="fa fa-shopping-cart"></i> ' . __('Add to Cart', 'wp-courses-premium') . ' </a>';
	    } else {
	    	return '<a class="wpc-btn wpc-add-to-cart" href="' . esc_url($cart_url) . '"><i class="fa fa-shopping-cart"></i> ' . __('View Cart', 'wp-courses-premium') . ' </a>';
	    }

	} else {
		return false;
	}

}

function wpc_woo_add_to_cart_button($course_id){

	if(!class_exists( 'WooCommerce' )){
		return;
	}

	$button = wpc_woo_get_add_to_cart_button($course_id);
	if($button !== false) {
		echo $button;
	}

}

add_action('wpc_after_course_buttons', 'wpc_woo_add_to_cart_button', 10, 1);

/** 
* Adds price after course title in classic view
* @action wpc_after_course_title
* @return string html price for linked course product
*/

function wpc_add_price_after_course_details(){
	 
	if(function_exists('wpc_get_connected_product_id') === false) {
		return;
	}

	if(!class_exists( 'WooCommerce' )){
		return;
	}

    $product_id = wpc_get_connected_product_id(get_the_ID());

    $_product = wc_get_product( $product_id );

    $current_user = wp_get_current_user();
	// determine if customer has bought product
	$has_bought = wc_customer_bought_product( $current_user->email, $current_user->ID, $product_id );

    if(!empty($product_id) && $product_id != false){ 

        $reg_price = $_product->get_regular_price();
    	$sale_price = $_product->get_sale_price();
    	$currency_symbol = get_woocommerce_currency_symbol(); 

    	$class = (!empty($sale_price)) ? 'wpc-on-sale' : '';

    	echo '<div class="wpc-course-price">';

    		if($has_bought == false){
    			echo '<div class="wpc-price wpc-reg-price ' . esc_attr($class) . '">' . wp_kses($currency_symbol, 'post') . wp_kses($reg_price, 'post') . '</div>';

	    		if(!empty($sale_price)){
	    			echo '<div class="wpc-price wpc-sale-price">' . wp_kses($currency_symbol, 'post') . wp_kses($sale_price, 'post') . '</div>';
	    		}
    		} else {
    			echo '<div class="wpc-purchased"><i class="fa fa-check"></i> ' . __('Purchased', 'wp-courses') . '</div>';
    		}

    	echo '</div>';

    }

    wp_reset_postdata();

}

add_action('wpc_after_course_details_button', 'wpc_add_price_after_course_details');

?>