<?php
/**
 * Return the premium features
 *
 * @author  YITH
 * @package YITH/Search/Options
 * @version 2.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return array(
	'features' => array(
		array(
			'title'       => __( 'Convert the search field into an advanced search engine', 'yith-woocommerce-ajax-search' ),
			'description' => __( 'Choose whether to show the filter for the search fields and the list of categories (only the main ones or all of them), a solution inspired by Amazon\'s search form. ', 'yith-woocommerce-ajax-search' ),
		),
		array(
			'title'       => __( 'Extend the search to pages and posts', 'yith-woocommerce-ajax-search' ),
			'description' => __( 'Choose whether to search for the keyword also in the pages\' content and blog articles.', 'yith-woocommerce-ajax-search' ),
		),
		array(
			'title'       => __( 'Extend the search to categories and product tags', 'yith-woocommerce-ajax-search' ),
			'description' => __( 'Choose to also search the keyword in the categories and product tags of your shop.', 'yith-woocommerce-ajax-search' ),
		),
		array(
			'title'       => __( 'Extend the search to product excerpts and descriptions', 'yith-woocommerce-ajax-search' ),
			'description' => __( 'Choose whether to search for the keyword in summaries and product descriptions.', 'yith-woocommerce-ajax-search' ),
		),
		array(
			'title'       => __( 'Allow searching by product SKU', 'yith-woocommerce-ajax-search' ),
			'description' => __( 'Enable the option to enter an SKU in the search field so users can quickly locate products they are interested in.', 'yith-woocommerce-ajax-search' ),
		),
		array(
			'title'       => __( 'Choose what to show in the suggestion list', 'yith-woocommerce-ajax-search' ),
			'description' => __( 'Decide whether or not to show product images, prices, and short descriptions (summaries) in the suggestion list.', 'yith-woocommerce-ajax-search' ),
		),
		array(
			'title'       => __( 'Use custom badges to enhance search results ', 'yith-woocommerce-ajax-search' ),
			'description' => __( 'Show and customize the "On sale" badge to enhance products with a discount or the "Featured" badge for products marked as such on WooCommerce.', 'yith-woocommerce-ajax-search' ),
		)
	),
);