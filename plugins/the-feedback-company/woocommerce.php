<?php

/**
 * This is the file for Feedback Company WooCommerce functionality
 *
 * this file is only loaded if WooCommerce is enabled as a plugin
 */

// security - stop if file isn't accessed via WP
if (!defined('ABSPATH'))
	exit;

/**
 * Class holds all WooCommerce functionality of this plugin
 */
class feedbackcompany_woocommerce
{
	/**
	 * This function initializes, overrides and extends WooCommerce functionality with Feedback Company reviews
	 */
	static function init()
	{
		// call function on order status change to register order with Feedback Company if invitations are enabled
		if (feedbackcompany_api_wp()->ext->get_locale_option('invitation_enabled') !== "0")
			add_action('woocommerce_order_status_changed', 'feedbackcompany_woocommerce::register_order', 99, 3);

		// if product reviews are enabled, implement our ratings in templates and add rich snippet data
		if (feedbackcompany_api_wp()->ext->get_client_option('productreviews_enabled') == true)
		{
			/* Review stars widgets */

			// replace the rating template stars with our own stars widget
			add_filter('wc_get_template', 'feedbackcompany_woocommerce::get_template', 10, 5);

			// output our custom html for product rating stars
			add_filter('woocommerce_product_get_rating_html', 'feedbackcompany_woocommerce::output_rating');

			// replace the rating stars in Gutenberg blocks with our own stars widget
			add_filter('woocommerce_blocks_product_grid_item_html', 'feedbackcompany_woocommerce::filter_blocks_product_grid_item', 10, 3);

			/* Extended product review widgets */

			// delete default review tab and (only if inline display is configured) insert our own tab into the product tabs
			add_filter('woocommerce_product_tabs', 'feedbackcompany_woocommerce::add_reviews_tab', 99);

			// if product reviews widget is not inline in a tab (see above), include the widget code for popup/sidebar in another place
			if (feedbackcompany_api_wp()->ext->get_locale_option('productreviewsextendedwidget_displaytype') != 'inline')
				add_action('woocommerce_after_single_product', 'feedbackcompany_woocommerce::output_reviewswidget', 99, 3);

			/* Structured data */

			// add our product ratings to structured data for products
			add_filter('woocommerce_structured_data_product', 'feedbackcompany_woocommerce::add_structured_data');
		}
	}

	/**
	 * Function to override the default WooCommerce rating template
	 *
	 * called by filter: wc_get_template
	 */
	static function get_template($located, $template_name, $args, $template_path, $default_path)
	{
		if ('single-product/rating.php' == $template_name)
			$located = plugin_dir_path( __FILE__ ) . 'woocommerce_templates/rating.php';

		return $located;
	}

	/**
	 * Function to override the Gutenberg Blocks rating display
	 *
	 * called by filter: woocommerce_blocks_product_grid_item_html
	 */
	static function filter_blocks_product_grid_item($html, $data, $product)
	{
		if (!$product)
			return $html;

		if ($product->get_image_id())
			$product_image_url = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail')[0];
		else
			$product_image_url = '';

		$widget = feedbackcompany_api_wp()->get_widget_productsummary($product->get_id(), $product->get_name(), $product->get_permalink(), $product_image_url);

		return str_replace($data->rating, $widget, $html);
	}

	/**
	 * Function to override the regular reviews tab and add our own
	 *
	 * Our own reviews are only added if productextended widget display is inline
	 *
	 * called by filter: woocommerce_product_tabs
	 */
	static function add_reviews_tab($tabs)
	{
		unset($tabs['reviews']);

		if (feedbackcompany_api_wp()->ext->get_locale_option('productreviewsextendedwidget_displaytype') == 'inline')
		{
			$tab = array(
				'title' => 'Reviews',
				'priority' => 50,
				'callback' => 'feedbackcompany_woocommerce::output_reviewswidget'
			);
			$tabs['reviews'] = $tab;
		}

		return $tabs;
	}

	/**
	 * Filter function to add the Feedback Company rating to the WooCommerce structured data
	 *
	 * called by filter: woocommerce_structured_data_product
	 */
	static function add_structured_data($markup)
	{
		$product = wc_get_product();

		if (!$product)
			return;

		// unset default WooCommerce reviews
		unset($markup['aggregateRating']);
		unset($markup['review']);

		$data = feedbackcompany_api_wp()->get_product_reviews_aggregate($product->get_id());

		// don't add structured data if there are no reviews yet for this product
		if (!$data || !isset($data['amount']) || $data['amount'] == 0)
			return $markup;

		// add aggregateRating to the structured data for this product
		$markup['aggregateRating'] = array(
			'@type' => 'AggregateRating',
			'ratingValue' => $data['score'],
			'reviewCount' => $data['amount'],
		);

		return $markup;
	}

	/**
	 * Function for our own custom rating output on product overview pages
	 *
	 * called by filter: woocommerce_product_get_rating_html
	 */
	static function output_rating($rating_html = null, $rating = null)
	{
		$product = wc_get_product();

		if (!$product)
			return $rating_html;

		if ($product->get_image_id())
			$product_image_url = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail')[0];
		else
			$product_image_url = '';

		return feedbackcompany_api_wp()->get_widget_productsummary($product->get_id(), $product->get_name(), $product->get_permalink(), $product_image_url);
	}

	/**
	 * Function outputs the reviews widget for a specific product
	 *
	 * This function can be called to output inline in a specific tab or
	 * just display the popup/sidebar widget code on 'woocommerce_after_single_product'
	 *
	 * called by filter: woocommerce_after_single_product
	 */
	static function output_reviewswidget()
	{
		$product = wc_get_product();

		if (!$product)
			return;

		if ($product->get_image_id())
			$product_image_url = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail')[0];
		else
			$product_image_url = '';

		echo feedbackcompany_api_wp()->get_widget_productextended($product->get_id(), $product->get_name(), $product->get_permalink(), $product_image_url);
	}

	/**
	 * Function interfaces with the API library to register an order with Feedback Company
	 *
	 * called by filter: woocommerce_order_status_changed
	 */
	static function register_order($order_id, $status_from, $status_to)
	{
		// if a supported WordPress Multilanguage plugin is enabled, we need to execute all our code with the locale the order was placed in
		if (feedbackcompany_wp::multilanguage_plugin())
		{
			// get order language
			$order_locale = feedbackcompany_wp::multilanguage_orderlanguage($order_id);

			// set locale override to the locale this order was placed in
			if ($order_locale)
				feedbackcompany_api_wp()->ext->locale_override = $order_locale;
		}

		// check if status is changed to the one configured as 'action' status via WP settings
		if (feedbackcompany_api_wp()->ext->get_locale_option('invitation_orderstatus') != 'wc-'.$status_to)
		{
			// if not, reset locale override and stop now
			feedbackcompany_api_wp()->ext->locale_override = null;
			return false;
		}

		// get this specific order
		$order = wc_get_order($order_id);

		// build data array for the api call
		$orderdata = array();
		$orderdata['external_id'] = strval($order_id);
		// add customer data
		$orderdata['customer'] = array(
			'email' => trim($order->get_billing_email()),
			'fullname' => trim($order->get_billing_first_name().' '.$order->get_billing_last_name())
		);
		// add products
		$orderdata['products'] = array();
		foreach ($order->get_items() as $orderitem)
		{
			// skip order items with a price of 0 or less
			if (floatval($orderitem->get_total()) <= 0)
				continue;

			// if an order item doesn't have a product associated, skip it
			$product = $orderitem->get_product();
			if (!$product)
				continue;

			$data = array();
			$data['external_id'] = strval($orderitem->get_product_id());
			if (method_exists($product, 'get_sku') && $product->get_sku())
				$data['sku'] = strval($product->get_sku());
			$data['name'] = $product->get_name();
			$data['url'] = $product->get_permalink();
			if ($product->get_image_id())
				$data['image_url'] = wp_get_attachment_image_src($product->get_image_id(), 'woocommerce_thumbnail')[0];

			$orderdata['products'][] = $data;
		}

		// make the api call
		feedbackcompany_api_wp()->register_order($orderdata, $platform = 'wordpress-'.feedbackcompany_wp::version);

		// remove locale override, if any
		feedbackcompany_api_wp()->ext->locale_override = null;
	}
}

/**
 * create a new instance of this class to start things off during WooCommerce init
 */
add_action('woocommerce_init', function() {
	feedbackcompany_woocommerce::init();
});

/**
 * tell WooCommerce about our feature support before WooCommerce init
 */
add_action('before_woocommerce_init', function() {
	if (class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class))
	{
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', feedbackcompany_wp::pluginfile, true);
	}
});
