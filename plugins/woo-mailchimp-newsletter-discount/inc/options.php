<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 * By default it uses the theme name, in lowercase and without spaces, but this can be changed if needed.
 * If the identifier changes, it'll appear as if the options have been reset.
 */

function wcmnd_optionsframework_option_name() {
	$wcmnd_optionsframework_settings = get_option( 'wcmnd_optionsframework' );
	$wcmnd_optionsframework_settings['id'] = 'wcmnd_options';
	update_option( 'wcmnd_optionsframework', $wcmnd_optionsframework_settings );
}


add_filter( 'wcmnd_optionsframework_menu', 'add_wcmnd' );

function add_wcmnd( $menu ) {
	$menu['page_title']  	= 'MailChimp NewsLetter Discount';
	$menu['menu_title']  	= 'Newsletter Discount';
	$menu['mode']		 	= 'menu';
	$menu['menu_slug']   	= 'mailchimp-subscribe-discount';
	$menu['icon_url'] 		= 'data:image/svg+xml;base64,'.mailchimp_newsletter_svg_icon();
	$menu['position']    	= '30';
	return $menu;
}


/**
* @return string
*/
function mailchimp_newsletter_svg_icon($color = false) {
	$color = ($color) ? $color : '#FFF';
	return base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52.03 55"><defs><style>.cls-1{fill:'.$color.';}</style></defs><title>Asset 1</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M11.64,28.54a4.75,4.75,0,0,0-1.17.08c-2.79.56-4.36,2.94-4.05,6a6.24,6.24,0,0,0,5.72,5.21,4.17,4.17,0,0,0,.8-.06c2.83-.48,3.57-3.55,3.1-6.57C15.51,29.83,13.21,28.63,11.64,28.54Zm2.77,8.07a1.17,1.17,0,0,1-1.1.55,1.53,1.53,0,0,1-1.37-1.58A4,4,0,0,1,12.23,34a1.44,1.44,0,0,0-.55-1.74,1.48,1.48,0,0,0-1.12-.21,1.44,1.44,0,0,0-.92.64,3.39,3.39,0,0,0-.34.79l0,.11c-.13.34-.33.45-.47.43s-.16-.05-.21-.21a3,3,0,0,1,.78-2.55,2.46,2.46,0,0,1,2.11-.76,2.5,2.5,0,0,1,1.91,1.39,3.19,3.19,0,0,1-.23,2.82l-.09.2A1.16,1.16,0,0,0,13,36a.74.74,0,0,0,.63.32,1.38,1.38,0,0,0,.34,0c.15,0,.3-.07.39,0A.24.24,0,0,1,14.41,36.61Z"/><path class="cls-1" d="M51,33.88a3.84,3.84,0,0,0-1.15-1l-.11-.37-.14-.42a5.57,5.57,0,0,0,.5-3.32,5.43,5.43,0,0,0-1.54-3,10.09,10.09,0,0,0-4.24-2.26c0-.67,0-1.43-.06-1.9a12.83,12.83,0,0,0-.49-3.25,10.46,10.46,0,0,0-1.3-2.92c2.14-2.56,3.29-5.21,3.29-7.57,0-3.83-3-6.3-7.59-6.3a19.3,19.3,0,0,0-7.22,1.6l-.34.14L28.7,1.52A6.31,6.31,0,0,0,24.43,0,14.07,14.07,0,0,0,17.6,2.2a36.93,36.93,0,0,0-6.78,5.21c-4.6,4.38-8.3,9.63-9.91,14A12.51,12.51,0,0,0,0,26.54a6.16,6.16,0,0,0,2.13,4.4l.78.66A10.44,10.44,0,0,0,2.74,35a9.36,9.36,0,0,0,3.21,6,10,10,0,0,0,5.13,2.43,20.19,20.19,0,0,0,7.31,8A23.33,23.33,0,0,0,30.17,55H31a23.27,23.27,0,0,0,12-3.16,19.1,19.1,0,0,0,7.82-9.06l0,0A16.89,16.89,0,0,0,52,37.23,5.17,5.17,0,0,0,51,33.88Zm-1.78,8.21c-3,7.29-10.3,11.35-19,11.09-8.06-.24-14.94-4.5-18-11.43a7.94,7.94,0,0,1-5.12-2.06,7.56,7.56,0,0,1-2.61-4.85A8.31,8.31,0,0,1,5,31L3.32,29.56C-4.42,23,19.77-3.86,27.51,2.89l2.64,2.58,1.44-.61c6.79-2.81,12.3-1.45,12.3,3,0,2.33-1.48,5.05-3.86,7.52a7.54,7.54,0,0,1,2,3.48,11,11,0,0,1,.42,2.82c0,1,.09,3.16.09,3.2l1,.27A8.64,8.64,0,0,1,47.2,27a3.66,3.66,0,0,1,1.06,2.06A4,4,0,0,1,47.55,32,10.15,10.15,0,0,1,48,33.08c.2.64.35,1.18.37,1.25.74,0,1.89.85,1.89,2.89A15.29,15.29,0,0,1,49.18,42.09Z"/><path class="cls-1" d="M48,36a1.36,1.36,0,0,0-.86-.16,11.76,11.76,0,0,0-.82-2.78A17.89,17.89,0,0,1,40.45,36a23.64,23.64,0,0,1-7.81.84c-1.69-.14-2.81-.63-3.23.74a18.3,18.3,0,0,0,8,.81.14.14,0,0,1,.16.13.15.15,0,0,1-.09.15s-3.14,1.46-8.14-.08a2.58,2.58,0,0,0,1.83,1.91,8.24,8.24,0,0,0,1.44.39c6.19,1.06,12-2.47,13.27-3.36.1-.07.16,0,.08.12l-.13.18c-1.59,2.06-5.88,4.44-11.45,4.44-2.43,0-4.86-.86-5.75-2.17-1.38-2-.07-5,2.24-4.71l1,.11a21.13,21.13,0,0,0,10.5-1.68c3.15-1.46,4.34-3.07,4.16-4.37A1.87,1.87,0,0,0,46,28.34a6.8,6.8,0,0,0-3-1.41c-.5-.14-.84-.23-1.2-.35-.65-.21-1-.39-1-1.61,0-.53-.12-2.4-.16-3.16-.06-1.35-.22-3.19-1.36-4a1.92,1.92,0,0,0-1-.31,1.86,1.86,0,0,0-.58.06,3.07,3.07,0,0,0-1.52.86,5.24,5.24,0,0,1-4,1.32c-.8,0-1.65-.16-2.62-.22l-.57,0a5.22,5.22,0,0,0-5,4.57c-.56,3.83,2.22,5.81,3,7a1,1,0,0,1,.22.52.83.83,0,0,1-.28.55h0a9.8,9.8,0,0,0-2.16,9.2,7.59,7.59,0,0,0,.41,1.12c2,4.73,8.3,6.93,14.43,4.93a15.06,15.06,0,0,0,2.33-1,12.23,12.23,0,0,0,3.57-2.67,10.61,10.61,0,0,0,3-5.82C48.6,36.7,48.33,36.23,48,36Zm-8.25-7.82c0,.5-.31.91-.68.9s-.66-.42-.65-.92.31-.91.68-.9S39.72,27.68,39.71,28.18Zm-1.68-6c.71-.12,1.06.62,1.32,1.85a3.64,3.64,0,0,1-.05,2,4.14,4.14,0,0,0-1.06,0,4.13,4.13,0,0,1-.68-1.64C37.29,23.23,37.31,22.34,38,22.23Zm-2.4,6.57a.82.82,0,0,1,1.11-.19c.45.22.69.67.53,1a.82.82,0,0,1-1.11.19C35.7,29.58,35.47,29.13,35.63,28.8Zm-2.8-.37c-.07.11-.23.09-.57.06a4.24,4.24,0,0,0-2.14.22,2,2,0,0,1-.49.14.16.16,0,0,1-.11,0,.15.15,0,0,1-.05-.12.81.81,0,0,1,.32-.51,2.41,2.41,0,0,1,1.27-.53,1.94,1.94,0,0,1,1.75.57A.19.19,0,0,1,32.83,28.43Zm-5.11-1.26c-.12,0-.17-.07-.19-.14s.28-.56.62-.81a3.6,3.6,0,0,1,3.51-.42A3,3,0,0,1,33,26.87c.12.2.15.35.07.44s-.44,0-.95-.24a4.18,4.18,0,0,0-2-.43A21.85,21.85,0,0,0,27.71,27.17Z"/><path class="cls-1" d="M35.5,13.29c.1,0,.16-.15.07-.2a11,11,0,0,0-4.69-1.23.09.09,0,0,1-.07-.14,4.78,4.78,0,0,1,.88-.89.09.09,0,0,0-.06-.16,12.46,12.46,0,0,0-5.61,2,.09.09,0,0,1-.13-.09,6.16,6.16,0,0,1,.59-1.45.08.08,0,0,0-.11-.11A22.79,22.79,0,0,0,20,16.24a.09.09,0,0,0,.12.13A19.53,19.53,0,0,1,27,13.32,19.1,19.1,0,0,1,35.5,13.29Z"/><path class="cls-1" d="M28.34,6.42S26.23,4,25.6,3.8C21.69,2.74,13.24,8.57,7.84,16.27,5.66,19.39,2.53,24.9,4,27.74a11.43,11.43,0,0,0,1.79,1.72A6.65,6.65,0,0,1,10,26.78,34.21,34.21,0,0,1,20.8,11.62,55.09,55.09,0,0,1,28.34,6.42Z"/></g></g></svg>');
}

/**
* Get pages in the admin to show popup
*
* @param empty
* @return array
*
*/
function wcndp_get_pages() {
	$pages_array = array( 'Choose A Page' );
	$get_pages = get_pages( 'hide_empty=0' );
	foreach ( $get_pages as $page ) {
		$pages_array[$page->ID] = esc_attr( $page->post_title );
	}
	return $pages_array;
}


/**
* Get MailChimp Mailing lists in the admin
*
* @param empty
* @return array of list ids with list names
*
*/
function wcmnd_get_mailchimp_lists() {
	$options = get_option('wcmnd_options');
	$get_list_ids = get_option('wcmnd_mailchimp_list');

	if( $get_list_ids && is_array($get_list_ids) ) {
		$wcmnd_mailchimp_lists = array('select' => 'Select Option');

		$wcmnd_mailchimp_lists = array();
		$wcmnd_mailchimp_lists[''] = 'Select Option';
		foreach( $get_list_ids as $key => $get_list_id ) {
			$wcmnd_mailchimp_lists[$get_list_id['list_id']] = $get_list_id['list_name'];
		}
	}
	else {
		$wcmnd_mailchimp_lists = array('select' => 'Select Option');
	}
	return $wcmnd_mailchimp_lists;
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'options_framework_theme'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */
$options = get_option('wcmnd_options');

function wcmnd_optionsframework_options() {

	$categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
	$cats = array();

	if ( $categories ) foreach ( $categories as $cat ) $cats[$cat->term_id] = esc_html( $cat->name );

	$coupon = get_posts( array( 'post_type' => 'shop_coupon', 'post_status' => 'publish', 'posts_per_page' => -1 ) );
	$coupons = array();

	if( is_array($coupon) ) {
		foreach ( $coupon as $cpn ) {
			$coupons[$cpn->post_title] = $cpn->post_title;
		}
	}

	$shop_page_url = get_permalink( wc_get_page_id( 'shop' ) );

	$options 		= array();

	$options[] 	= array('name' => __('General Settings', 'wc_mailchimp_newsletter_discount'),
		'type' => 'heading',
		'icon_class' => 'dashicons-admin-generic'
	);

	$options[] 	= array('name' => __('Enable MailChimp Newsletter Discount', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Check if you want to activate Newsletter Subscribe Discount.', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'enabled',
		'std' 		=> '',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Display Fields', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select the fields that would be shown in the frontend. If you want to add your own custom fields then please use <a href="https://zetamatic.com/downloads/extra-fields-for-mailchimp-newsletter-discount/"  target="_blank">Extra Fields For MailChimp Newsletter Discount For MailChimp Newsletter Discount</a>', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'display_fields',
		'std' 		=> 'email',
		'options' => array('email' => 'Only Email', 'firstname_email' => 'First name and Email', 'firstname_lastname_email' => 'First name, Last name and Email'),
		'type' 		=> 'radio');
	$options[] 	= array('name' => __('Use For Normal Signups', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Check if you want it to use only for normal signups. No discount coupon will be generated or assigned to the subscriber', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'disable_discount',
		'std' 		=> '',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('MailChimp Configuration', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'heading', 'icon_url' => 'data:image/svg+xml;base64,'.mailchimp_newsletter_svg_icon('#24282d') );

	$options[] 	= array('name' => __('MailChimp API Key', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter your MailChimp api key. To find your API Key <a href="http://kb.mailchimp.com/accounts/management/about-api-keys" target="_blank">click here</a>', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'mailchimp_key',
		'std' 		=> '',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Select MailChimp List / Audienece Name', 'wc_newsletter_discounts'),
		'desc' 		=> __('Fetch your MailChimp list names / audienece name by doing click <a href="#" class="wcnd_fetch_mailchimp_lists">here</a>', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'mailchimp_list_id',
		'std' 		=> '',
		'options' => wcmnd_get_mailchimp_lists(),
		'type' 		=> 'selectcustom');

	$options[] 	= array('name' => __('Enable double optin feature for subscription', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('By enabling this option it will ask user to confirm their subscription to the MailChimp mailing list.In order to use double optin feature you need to add a webhook with <strong>callback url</strong> as <strong>'. site_url() . '</strong>', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'double_optin_pro',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Webhook Url', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter webhook url. If you want to add this site as your webhook url then add '.site_url().' . Please enter a valid url for webhook ', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'webhook_url',
		'class'		=> 'pro-feature',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Add signup source', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Add Source Merge to track the signup source', 'wc_newsletter_discounts'),
		'id' 			=> 'mailchimp_add_signup_source',
		'std' 		=> '',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Signup Source', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the signup source which can be shown in the MailChimp admin to check from where the user has been made signup. Available variables for signup sources are <strong>{SITEURL}</strong> - For Site Url, <strong>{PAGEURL}</strong> - For page url from where the user has made signup.', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'mailchimp_signup_source',
		'std' 		=> '{PAGEURL}',
		'class'		=> 'pro-feature',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Enter test email', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This email will be used for testing purpose. This email address would not be added to your MailChimp list.', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'test_email',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Enable Checkbox For Terms and Conditions', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enable this field to show checkbox where user can agree with the term and conditions. This is a required field. You can use it as GDPR.', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'mailchimp_enable_terms_condition',
		'std' 		=> '',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Enter Text For Terms and Conditions', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This would be the text for terms and conditions', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'mailchimp_terms_conditions_text',
		'class'		=> 'pro-feature',
		'std' 		=> 'You need to accept terms and condition to subscribe.',
		'type' 		=> 'textarea');

	$options[] 	= array('name' => __('Enter Error Message For Terms and Conditions', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This would be the error message for terms and conditions message', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'mailchimp_terms_conditions_error_msg',
		'std' 		=> 'Please accept our terms and conditions before subscribe',
		'class'		=> 'pro-feature',
		'type' 		=> 'textarea');

	$options[] 	= array('name' => __('Coupon Settings', 'wc_mailchimp_newsletter_discount'),
		'type' => 'heading', 'icon_class' => 'dashicons-tag');

	$options[] 	= array('name' => __('Discount Type', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'dis_type',
		'options' => wc_get_coupon_types(),
		'std' 		=> 'percent',
		'type' 		=> 'select');

	$options[] 	= array('name' => __('Coupon Code Length', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'coupon_length',
		'desc'		=> __('Enter the length of the coupon which will be created', 'wc_mailchimp_newsletter_discount'),
		'class'		=> 'pro-feature',
		'std' 		=> '10',
		'type' 		=> 'number');

	$options[] 	= array('name' => __('Coupon Amount', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'coupon_amount',
		'std' 		=> '10',
		'desc'		=> __('Value of the coupon', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Allow Free Shipping', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'free_shipping_pro',
		'std' 		=> 'yes',
		'desc' 		=> __('Check this box if the coupon grants free shipping. A <a href="https://docs.woocommerce.com/document/free-shipping/" target="_blank"> free shipping method </a>must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).','wc_mailchimp_newsletter_discount'),
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox');

	$options[] = array('name' => __('Individual use only', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'individual_use_pro',
		'std' 		=> 'yes',
		'desc' 		=> __('Check this box if the coupon cannot be used in conjunction with other coupons.', 'wc_mailchimp_newsletter_discount'),
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox');

	$options[] = array( 'name' => __( 'Restrict Email', 'wc_mailchimp_newsletter_discount' ),
		'desc' 		=> __( 'Allow discount if the purchase is made for the same email id user registered on mailchimp.', 'wc_mailchimp_newsletter_discount' ),
		'id' 			=> 'email_restrict_pro',
		'std'			=> '0',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox' );

	$options[] = array('name' => __('Exclude on sale items', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'exclude_sale_items',
		'std' 		=> '0',
		'desc' 		=> __('Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'wc_mailchimp_newsletter_discount'),
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox');

	$options[] = array('name' => __('Products', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'products',
		'class' 	=> 'ajax_products',
		'desc' 		=> __('Products that the coupon will be applied to, or that need to be in the cart for the "Fixed cart discount" to be applied', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'productselect2');

	$options[] = array('name' => __('Exclude Products', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'exclude_products_pro',
		'class' 	=> 'ajax_products',
		'desc' 		=> __('Products that the coupon will not be applied to, or that can not to be in the cart in order for the "Fixed cart discount" to be applied', 'wc_mailchimp_newsletter_discount'),
		'class'		=> 'pro-feature',
		'type' 		=> 'productselect2');

	$options[] = array('name' => __('Product Categories', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'categories',
		'options' => $cats,
		'desc' 		=> __('Products categories that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied','wc_mailchimp_newsletter_discount'),
		'type' 		=> 'catselect2');

	$options[] = array('name' => __('Exclude Categories', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'exclude_categories_pro',
		'options' => $cats,
		'desc' 		=> __('Products categories that the coupon will not be applied to, or that can not be in the cart in order for the "Fixed cart discount" to be applied','wc_mailchimp_newsletter_discount'),
		'class'		=> 'pro-feature',
		'type' 		=> 'catselect2');

	$options[] 	= array('name' => __('Minimum Purchase', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'min_purchase',
		'desc'		=> __('This field allows you to set the minimum spend(subtotal) allowed to use the coupon','wc_mailchimp_newsletter_discount'),
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Maximum Purchase', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'max_purchase',
		'desc'		=> __('This field allows you to set the maximum spend(subtotal) allowed to use the coupon','wc_mailchimp_newsletter_discount'),
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Coupon Valid For Days', 'wc_mailchimp_newsletter_discount'),
		'std'		=> '10',
		'id' 		=> 'coupon_valid_days_pro',
		'class'		=> 'pro-feature',
		'desc'		=> __('Number of days the coupon will be validate after coupon creation','wc_mailchimp_newsletter_discount'),
		'type' 		=> 'number');

	$options[] 	= array('name' => __('Coupon Expiry Date Format', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'date_format_pro',
		'std'			=> 'jS F Y',
		'class'		=> 'pro-feature',
		'desc' 		=> __('Enter the date format for the coupon expiry date which would be mailed to the user. <a href="http://php.net/manual/en/function.date.php" target="_blank">Click here</a> to know about the available types','wc_mailchimp_newsletter_discount'),
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Look and Feel Settings', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'heading', 'icon_class' => 'dashicons-admin-customizer');

	$options[] 	= array('name' => __('Error Text Background Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the errors background color which will be shown when some error comes', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'error_bg_color',
		'class'		=> 'pro-feature',
		'std' 		=> '#f7e0e2',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Error Text Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the errors color which will be shown when some error comes', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'error_text_color',
		'std' 		=> '#FF3E4D',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Success Text Background Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the success message background color when someone successfully subscribes', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'success_bg_color',
		'class'		=> 'pro-feature',
		'std' 		=> '#96c928',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Success Text Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the success message color when someone successfully subscribes', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'success_text_color',
		'std' 		=> '#019031',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Subscribe Button Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the subscribe button color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'subscribe_button_color',
		'std' 		=> '#333333',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Subscribe Button Hover Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the subscribe button hover color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'subscribe_button_hover_color',
		'std' 		=> '#666666',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Subscribe Button Text Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the subscribe button text color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'subscribe_button_text_color',
		'std' 		=> '#FFFFFF',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Subscribe Button Text Hover Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the subscribe button text hover color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'subscribe_button_text_hover_color',
		'std' 		=> '#FFFFFF',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Close Button Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the close button color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'close_btn_color_pro',
		'class'		=> 'pro-feature',
		'std' 		=> '#FFFFFF',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Close Button Hover Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the close button hover color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'close_btn_hover_color_pro',
		'class'		=> 'pro-feature',
		'std' 		=> '#CCCCCC',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Custom CSS', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Add your custom css here', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'ace_editor',
		'class'		=> 'pro-feature',
		'std' 		=> '',
		'type' 		=> 'cseditor');

	$options[] 	= array('name' => __('Subscribe Button Label', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter subscribe button text here', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'button_text',
		'std' 		=> 'SUBSCRIBE',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Success Message', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the success message when any user will subscribed through your list', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'wcmnd_success_msg_pro',
		'class'		=> 'pro-feature',
		'std' 		=> 'Thank you for subscribing! Check your mail for coupon code!',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Show Coupon Code After Subscribe?', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Check if you want to show coupon code in success message after user successfully subscribe. You just have to use <strong>{COUPONCODE}</strong> variable in the success message. Please note that this option will not work if you have enabled double optin feature.', 'wc_mailchimp_newsletter_discount'),
		'class'		=> 'pro-feature',
		'id' 			=> 'enable_success_coupon',
		'std' 		=> 'yes',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Already Subscribed Message', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the message for the user who has been already subscribed to the newsletter before', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'wcmnd_already_subscribed_pro',
		'class'		=> 'pro-feature',
		'std' 		=> 'It seems that you have already subscribed before.',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Enable Redirect After Subscribe?', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Check if you want to redirect users to another page after successfull subsribe', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'enable_redirect_pro',
		'class'		=> 'pro-feature',
		'std' 		=> '0',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Redirect After Seconds', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'redirect_timeout_pro',
		'std'			=> '2',
		'class'		=> 'pro-feature',
		'desc' 		=> __('This will be the time in seconds after which the user will be redirect after successfully subscribe', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'number');

	$options[] 	= array('name' => __('Redirect URL', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'redirect_url_pro',
		'std'			=> $shop_page_url,
		'class'		=> 'pro-feature',
		'desc' 		=> __('Enter url for the redirect where user will be redirected after successfull subscribed', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Popup Settings', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'heading', 'icon_class' => 'dashicons-welcome-view-site');

	$options[] 	= array('name' => __('Enable Popup Feature?', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Check this if you want to show subscribe form  popup in the frontend.','wc_mailchimp_newsletter_discount'),
		'id' 			=> 'enable_popup',
		'class'		=> 'pro-feature',
		'std' 		=> '1',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Popup Height (in px)', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter a height for the popup. Put 0 for auto height', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_height',
		'class'		=> 'pro-feature',
		'std' 		=> '0',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Popup Width (in px)', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter a width for the popup. Put 0 for auto width', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_width',
		'class'		=> 'pro-feature',
		'std' 		=> '0',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Close Popup On Overlay Click', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Check if you want to close the popup on overlay click', 'wc_mailchimp_newsletter_discount', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'overlay_click',
		'class'		=> 'pro-feature',
		'std' 		=> '1',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Popup Animation', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select animation type for the popup', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_animation',
		'std' 		=> 'fade-in',
		'class'		=> 'pro-feature',
		'options' => array('fadeIn' => 'Fade In', 'fadeInDown' => 'Fade In Down', 'fadeInDownBig' => 'Fade In Down Big', 'fadeInLeft' => 'Fade In Left', 'fadeInRight' => 'Fade In Right', 'fadeInUp' => 'fade In Up', 'flip' => 'Flip', 'flipInX' => 'Flip In X', 'flipInY' => 'Flip In Y', 'lightSpeedIn' => 'Light Speed In', 'pulse' => 'Pulse', 'rubberBand' => 'Rubber Band', 'shake' => 'Shake', 'tada' => 'Tada', 'wobble' => 'Wobble', 'swing' => 'Swing', 'bounceIn' => 'Bounce In', 'bounceInDown' => 'Bounce In Down', 'bounceInLeft' => 'bounce In Left', 'bounceInRight' => 'Bounce In Right', 'bounceInUp' => 'Bounce In Up', 'rollIn' => 'Roll In', 'zoomIn' => 'Zoom In', 'zoomInDown' => 'zoom In Down', 'zoomInLeft' => 'Zoom In Left', 'zoomInRight' => 'Zoom In Right', 'zoomInUp' => 'Zoom In Up', 'rotateIn' => 'Rotate In', 'rotateInDownLeft' => 'Rotate In Down Left', 'rotateInDownRight' => 'Rotate In Down Right', 'rotateInUpLeft' => 'Rotate In Up Left', 'rotateInUpRight' => 'Rotate In Up Right' ),
		'type' 		=> 'select');

	$options[] = array( 'name' => __( 'Close hinge effect', 'wc_mailchimp_newsletter_discount' ),
		'desc' 		=> __( 'Enable hinge effect when closing the modal.', 'wc_mailchimp_newsletter_discount' ),
		'id' 			=> 'enable_hinge',
		'std' 		=> 'yes',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox' );


	$options[] 	= array('name' => __('Enable Popup For', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enable popup for the user', 'wc_mailchimp_newsletter_discount', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'enable_popup_for',
		'std' 		=> 'all',
		'class'		=> 'pro-feature',
		'options' => array('logged_users' => 'Only for logged in users', 'guest_users' => 'Only for guest users', 'all' => 'For all users'),
		'type' 		=> 'radio');

	$options[] 	= array('name' => __('Enable Popup On Mobile', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Check if you want to show popup on mobile devices.','wc_mailchimp_newsletter_discount'),
		'id' 			=> 'enable_popup_on_mobile',
		'std' 		=> '',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox');

	$options[] 	= array('name' => __('Where To Show Popup?', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select where do you want to show the popup', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'where_to_show_popup',
		'std' 		=> 'over_site',
		'class'		=> 'pro-feature',
		'options' => array('over_site' => 'All Over Site', 'specific_pages' => 'Only On Specific Pages and Posts'),
		'type' 		=> 'radio');

	$options[] 	= array('name' => __('Select Pages To Show Popup', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select the pages where you want to show the popup', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_pages',
		'std' 		=> '',
		'class'		=> 'pro-feature',
		'options'	=> wcndp_get_pages(),
		'type' 		=> 'catselect2');

	$options[] 	= array('name' => __('Popup Header Text', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter the text that would be shown as the popup header text', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_header_text',
		'std' 		=> '',
		'class'		=> 'pro-feature',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Popup Text', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('The text that would be shown in the popup', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'wcmnd_popup_text',
		'std' 		=> '',
		'class'		=> 'pro-feature',
		'type' 		=> 'editor');

	$options[] 	= array( 'name' => __( 'Exit intent', 'wc_mailchimp_newsletter_discount' ),
		'desc' 		=> __( 'Display popup on exit intent', 'wc_mailchimp_newsletter_discount' ),
		'id' 			=> 'exit_intent',
		'std' 		=> 'yes',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox' );

	$options[] 	= array('name' => __('Remember user to hide popup for days', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter the number of days the popup would not be shown to the users once they close the popup', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_cookie',
		'std' 		=> '0',
		'class'		=> 'pro-feature',
		'type' 		=> 'number');

	$options[] 	= array('name' => __('Popup Background Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select popup background color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_bg',
		'std' 		=> '#F2F2F2',
		'class'		=> 'pro-feature',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Popup Background Image', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select popup background image. If no image has been set then popup background color will be used', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_bg_img',
		'class'		=> 'pro-feature',
		'type' 		=> 'upload');

	$options[] 	= array('name' => __('Popup Background Image Repeat', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select popup background image repeat', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_bg_img_repeat',
		'std'	=> 'repeat',
		'class'		=> 'pro-feature',
		'options' => array('repeat' => 'Repeat', 'repeat-x' => 'Repeat-X', 'repeat-y' => 'Repeat-Y'),
		'type' 		=> 'radio');

	$options[] 	= array('name' => __('Popup Background Image Size', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select popup background image size', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_bg_img_size',
		'std'		=> 'contain',
		'class'		=> 'pro-feature',
		'options' => array('contain' => 'Contain', 'cover' => 'Cover', 'auto' => 'Auto'),
		'type' 		=> 'select');

	$options[] 	= array('name' => __('Popup Background Image Position', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select popup background image position', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_bg_img_position',
		'std'	=> 'center',
		'class'		=> 'pro-feature',
		'options' => array('top' => 'Top', 'left' => 'Left', 'right' => 'Right', 'bottom' => 'Bottom', 'center' => 'Center'),
		'type' 		=> 'select');

	$options[] 	= array('name' => __('Popup Close Button Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select popup close button color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_close_btn_color',
		'std' 		=> '#2E2E2E',
		'class'		=> 'pro-feature',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Popup Overlay Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select popup overlay color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_overlay_color',
		'std' 		=> '#CFCFCF',
		'class'		=> 'pro-feature',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Popup Overlay Opacity', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('select popup overlay opacity', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_overlay_opacity',
		'std' 		=> '0.07',
		'class'		=> 'pro-feature',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Popup Header Text Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select popup header text color', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_header_text_color',
		'std' 		=> '#606060',
		'class'		=> 'pro-feature',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Popup Input Field Border Color', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Select the border color for input', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'popup_input_border_color',
		'std' 		=> '#606060',
		'class'		=> 'pro-feature',
		'type' 		=> 'color');

	$options[] 	= array('name' => __('Notification', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'heading', 'icon_class' => 'dashicons-flag');

	$options[] 	= array( 'name' => __( 'Enable Notification', 'wc_mailchimp_newsletter_discount' ),
		'desc' 		=> __( 'Enable desktop notification when someone subscribe to your newsletter through this plugin', 'wc_mailchimp_newsletter_discount' ),
		'id' 			=> 'enable_notification',
		'std' 		=> '',
		'class'		=> 'pro-feature',
		'type' 		=> 'checkbox' );

	$options[] 	= array( 'name' => __( 'Notification Title', 'wc_mailchimp_newsletter_discount' ),
		'desc' 		=> __( 'Enter notification title', 'wc_mailchimp_newsletter_discount' ),
		'id' 			=> 'notification_title',
		'class'		=> 'pro-feature',
		'std' 		=> 'Someone is picking your newsletter!',
		'type' 		=> 'text' );

	$options[] 	= array( 'name' => __( 'Notification Message', 'wc_mailchimp_newsletter_discount' ),
		'desc' 		=> __( 'Enter notification message. You can use {EMAIL} place holder for the email', 'wc_mailchimp_newsletter_discount' ),
		'class'		=> 'pro-feature',
		'id' 			=> 'notification_message',
		'std' 		=> '{EMAIL} subscribed to your newsletter',
		'type' 		=> 'textarea' );

	$options[] 	= array( 'name' => __( 'Notification Icon', 'wc_mailchimp_newsletter_discount' ),
		'class'		=> 'pro-feature',
		'desc' 		=> __( 'Upload notification icon here', 'wc_mailchimp_newsletter_discount' ),
		'id' 		=> 'notification_icon',
		'type' 		=> 'upload');

	$options[] 	= array( 'name' => __( 'Notification Sound', 'wc_mailchimp_newsletter_discount' ),
		'class'		=> 'pro-feature',
		'desc' 		=> __( 'Upload notification sound here', 'wc_mailchimp_newsletter_discount' ),
		'id' 		=> 'notification_sound',
		'type' 		=> 'upload');


	$options[] 	= array('name' => __('Email Settings', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'heading', 'icon_class' => 'dashicons-email');

	$options[] 	= array('name' => __('Invalid Email Error', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be the error message shown when someone tries with an invalid email', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'wcmnd_invalid_email_error_pro',
		'std' 		=> 'Please enter a valid email',
		'class'		=> 'pro-feature',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Mail From Name', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter mail from name', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'wcmnd_mail_from_name_pro',
		'class'		=> 'pro-feature',
		'std' 		=> get_bloginfo('name'),
		'type' 		=> 'text');

	$options[] 	= array('name' => __('From Email', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('Enter from mail', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'wcmnd_from_email',
		'std' 		=> get_bloginfo('admin_email'),
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Email Subject', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be email subject for the emails which would be sent to the users.', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'wcmnd_email_subject',
		'class'		=> 'pro-feature',
		'std' 		=> 'Hey! you just got a discount for signing up to our Newsletter',
		'type' 		=> 'text');

	$options[] 	= array('name' => __('Email Message', 'wc_mailchimp_newsletter_discount'),
		'desc' 		=> __('This will be email message for the emails which would be sent to the users.', 'wc_mailchimp_newsletter_discount'),
		'id' 			=> 'wcmnd_email_message',
		'desc'		=> 'You can use variables in the editor <strong>{COUPONCODE} </strong> - For coupon code, <strong>{COUPONEXPIRY} </strong> - Coupon expiry date, <strong>{ALLOWEDCATEGORIES} </strong> - Coupon allowed categories, <strong>{ALLOWEDPRODUCTS}</strong> - Allowed products for coupons',
		'std' 		=> '<p>Hi There,</p><p>Thanks for signing up for our Newsletter. We have just created a coupon for you. The coupon code to redeem the discount is <h3>{COUPONCODE}</h3></p>',
		'type' 		=> 'editor');

	$options[] 	= array('name' => __('Analytics', 'wc_mailchimp_newsletter_discount'),
		'type' 		=> 'heading', 'icon_class' => 'dashicons-chart-area');

  $options[] 	= array(
		'id' 			=> 'wcmnd_analytics',
		'class'		=> 'pro-feature',
		'type' 		=> 'wcmnd_analytics');

	return $options;
}
