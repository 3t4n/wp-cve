<?php

/**
 * @package WP Product Feed Manager/User Interface/Functions
 * @version 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function wppfm_feed_manager_main_page() { // REM_BLUE

	global $wppfm_tab_data;

	$feed_type           = $_GET['feed-type'] ?? 'product-feed';
	$active_tab          = $_GET['tab'] ?? 'feed-list';
	$page_start_function = 'wppfm_main_admin_page'; // default

	switch ( $feed_type ) {
		case 'google-product-review-feed':
			$class_identifier = 'wpprfm_call_review_feed_page';
			break;
		case 'google-merchant-promotions-feed':
			$class_identifier = 'wpppfm_call_promotions_feed_page';
			break;
		default:
			$class_identifier = 'wppfm_add_product_feed_page';
			break;
	}

	$list_tab = new WPPFM_Tab(
		'feed-list',
		'feed-list' === $active_tab,
		__( 'Feed List', 'wp-product-feed-manager' ),
		'wppfm_main_admin_page'
	);

	$product_feed_tab = new WPPFM_Tab(
		'product-feed',
		'product-feed' === $active_tab,
		__( 'Product Feed', 'wp-product-feed-manager' ),
		$class_identifier
	);

	$wppfm_tab_data = apply_filters( 'wppfm_main_form_tabs', array( $list_tab, $product_feed_tab ), $active_tab );

	foreach ( $wppfm_tab_data as $tab ) {
		if ( $tab->get_page_identifier() === $active_tab ) {
			$page_start_function = $tab->get_class_identifier();
			break;
		}
	}

	$page_start_function();
}

/**
 * starts the main admin page
 */
/** @noinspection PhpUnused */
function wppfm_main_admin_page() { // REM_BLUE?
	wppfm_check_prerequisites();

	$start = new WPPFM_Main_Admin_Page();
	$start->show();
}

/**
 * starts the Feed Editor page for a standard feed
 */
/** @noinspection PhpUnused */
function wppfm_open_product_feed_page() {
		$add_new_feed_page = new WPPFM_Add_Feed_Editor_Page();
	$add_new_feed_page->show();
}

/**
 * Starts the feed list page
 */
function wppfm_feed_list_page() {
	wppfm_check_prerequisites();

		$add_feed_list_page = new WPPFM_Add_Feed_List_Page();
		$add_feed_list_page->show();
}

/**
 * Starts the correct Feed Editor page
 */
function wppfm_feed_editor_page() {
	wppfm_check_prerequisites();

	$feed_type           = $_GET['feed-type'] ?? 'product-feed';

	switch ( $feed_type ) {
		case 'google-product-review-feed':
			wpprfm_open_review_feed_page();
			break;
		case 'google-merchant-promotions-feed':
			wpppfm_open_promotions_feed_page();
			break;
		default:
			wppfm_open_product_feed_page();
			break;
	}
}

/**
 * Starts the settings page
 */
function wppfm_settings_page() {
	wppfm_check_prerequisites();

		$add_settings_page = new WPPFM_Add_Settings_Page();
		$add_settings_page->show();
}

/**
 * Starts the support page
 */
function wppfm_support_page() {
	wppfm_check_prerequisites();

		$add_support_page = new WPPFM_Add_Support_Page();
		$add_support_page->show();
}

/**
 * Returns an array of possible feed types that can be altered using the wppfm_feed_types filter.
 *
 * @return array with possible feed types
 */
function wppfm_list_feed_type_text() {

	return apply_filters(
		'wppfm_feed_types',
		array(
			'1' => 'Product Feed',
			'4' => 'Google Local Product Inventory Feed',
			'5' => 'Google Dynamic Remarketing Feed',
			'6' => 'Google Vehicle Ads Feed',
			'7' => 'Google Dynamic Search Ads Feed',
			'8' => 'Google Local Product Feed',
		)
	);
}

function wppfm_get_menu_icon_svg(  ) {
	return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNTAiIHZpZXdCb3g9IjAgMCAxMTIuNSAxMTIuNSIgaGVpZ2h0PSIxNTAiIHZlcnNpb249IjEuMCI+CiAgICA8ZGVmcz4KICAgICAgICA8Y2xpcFBhdGggaWQ9ImIiPgogICAgICAgICAgICA8cGF0aCBkPSJNOSAxOWg5NHY5My4wMDRIOVptMCAwIi8+CiAgICAgICAgPC9jbGlwUGF0aD4KICAgICAgICA8Y2xpcFBhdGggaWQ9ImUiPgogICAgICAgICAgICA8cGF0aCBkPSJNLjc5Ny41OTRoOTIuMzE2VjkzSC43OTdabTAgMCIvPgogICAgICAgIDwvY2xpcFBhdGg+CiAgICAgICAgPGNsaXBQYXRoIGlkPSJkIj4KICAgICAgICAgICAgPHBhdGggZD0iTTAgMGg5NHY5NEgweiIvPgogICAgICAgIDwvY2xpcFBhdGg+CiAgICAgICAgPGNsaXBQYXRoIGlkPSJmIj4KICAgICAgICAgICAgPHBhdGggZD0iTTIyLjY0NSAwSDg5djM0LjM0OEgyMi42NDVabTAgMCIvPgogICAgICAgIDwvY2xpcFBhdGg+CiAgICAgICAgPGZpbHRlciB4PSIwJSIgeT0iMCUiIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGlkPSJhIj4KICAgICAgICAgICAgPGZlQ29sb3JNYXRyaXggdmFsdWVzPSIwIDAgMCAwIDEgMCAwIDAgMCAxIDAgMCAwIDAgMSAwIDAgMCAxIDAiIGNvbG9yLWludGVycG9sYXRpb24tZmlsdGVycz0ic1JHQiIvPgogICAgICAgIDwvZmlsdGVyPgogICAgICAgIDxtYXNrIGlkPSJjIj4KICAgICAgICAgICAgPGcgZmlsdGVyPSJ1cmwoI2EpIj4KICAgICAgICAgICAgICAgIDxwYXRoIGZpbGwtb3BhY2l0eT0iLjk5IiBkPSJNLTExLjI1LTExLjI1aDEzNXYxMzVoLTEzNXoiLz4KICAgICAgICAgICAgPC9nPgogICAgICAgIDwvbWFzaz4KICAgIDwvZGVmcz4KICAgIDxnIG1hc2s9InVybCgjYykiIGNsaXAtcGF0aD0idXJsKCNiKSI+CiAgICAgICAgPGcgY2xpcC1wYXRoPSJ1cmwoI2QpIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg5IDE5KSI+CiAgICAgICAgICAgIDxnIGNsaXAtcGF0aD0idXJsKCNlKSI+CiAgICAgICAgICAgICAgICA8cGF0aCBmaWxsPSIjZmZmIiBkPSJNMTIuMzcxIDc3LjIzOGMuMzUyLjQ1Ny43MjcuODI1IDEuMTAyIDEuMjA3QzIxLjg4MyA4Ny4zODMgMzMuNzg1IDkzIDQ3IDkzYzI1LjQyNiAwIDQ2LjExMy0yMC42ODggNDYuMTEzLTQ2LjExM0M5My4xMTMgMjEuNDU3IDcyLjQyNi43NzMgNDcgLjc3MyAyMS41Ny43NzMuODg3IDIxLjQ1Ny44ODcgNDYuODg3YzAgMTEuNjI1IDQuMzU1IDIyLjIzIDExLjQ4NCAzMC4zNTFaTTQ3IDg5LjE1NmMtOS4xOTEgMC0xNy42OC0yLjk4LTI0LjYyNS03Ljk4IDIuNjA1LS43MDMgNS40MS0yLjkzIDUuNDEtOS4zMTMgMC0yLjcwNy0xLjk3My02LjA2Mi00LjI2Mi05Ljk0NS0zLjA1LTUuMTg0LTYuODQ3LTExLjYzMy01LjQyNS0xNi40MDIuMDQzLS4xNDEuMDMtLjI3OC4wMzktLjQxOCAyLjE2OC02LjgyNSA0Ljg5OC0xNS40MDMgNy4xNi0yMi41bDEzLjc3NyA0My4wNWMtLjgwOC4xNDEtMS41OS4zMTctMi4yODkuNTUxYTEuOTIyIDEuOTIyIDAgMCAwLTEuMjE1IDIuNDNBMS45MTcgMS45MTcgMCAwIDAgMzggNjkuODQ0YzIuNjY4LS44ODcgNy4zMTYtLjg2NCAxMC4xMTMtLjg2NGgxLjYxNGMyLjc4IDAgNy40NDktLjAyMyAxMC4xMTMuODY4YTEuOTIxIDEuOTIxIDAgMCAwIDIuNDMtMS4yMiAxLjkxNyAxLjkxNyAwIDAgMC0xLjIxMS0yLjQyOWMtMS4zMzYtLjQ0NS0yLjk1LS42NzYtNC41OTQtLjgyOGwtNy4zMzYtMjAuNjc2IDcuMzA1LTIwLjg2NyAxMy4zODIgNDEuODJjLS44MTIuMTQ1LTEuNTkzLjMxNy0yLjI4OS41NTFhMS45MjIgMS45MjIgMCAwIDAtMS4yMTUgMi40MyAxLjkxNiAxLjkxNiAwIDAgMCAyLjQzIDEuMjE1YzIuNjY0LS44ODcgNy4zMi0uODk1IDEwLjEyMS0uODY0aC44MDFjMS4zNTIgMCAyLjM0OC4xMzcgMy4xMzMuMjgyQzc1LjMxMyA4MS4xODcgNjIuMDg2IDg5LjE1NiA0NyA4OS4xNTZabTQyLjI3LTQyLjI3YTQxLjk4IDQxLjk4IDAgMCAxLTMuMDU1IDE1LjY5Nkw2Ny41NSA5Ljk4QzgwLjQ5MiAxNy4yMTUgODkuMjcgMzEuMDQgODkuMjcgNDYuODg3Wk01OC41MTIgNi4yNSA0Ny4wNzggMzguOTIyIDM1LjQ4NCA2LjI1QzM5LjE1MiA1LjIxIDQzLjAwNCA0LjYxNyA0NyA0LjYxN2MzLjk5NiAwIDcuODQ4LjU5NCAxMS41MTIgMS42MzNabS0zMy41MDggNC42MWMtMi40OTIgNy44Mi05LjI3IDI5LjA3OC0xMi40OTYgMzkuMjg4LTEuOTU3IDYuMTgtMy4wMDQgMTEuNDY1LTMuMTkyIDE1Ljc4Mi0yLjkxLTUuNzI3LTQuNTg2LTEyLjE4OC00LjU4Ni0xOS4wNDMgMC0xNS4yNDYgOC4xMzctMjguNTkgMjAuMjc0LTM2LjAyOFptMCAwIi8+CiAgICAgICAgICAgIDwvZz4KICAgICAgICA8L2c+CiAgICA8L2c+CiAgICA8ZyBjbGlwLXBhdGg9InVybCgjZikiPgogICAgICAgIDxwYXRoIGZpbGw9IiNmZmYiIGQ9Ik01NS44OSAwQzM3Ljk5NyAwIDI0LjI0MyAxNS43MzggMjQuMjQzIDE1LjczOGwtMS4yODUgMS40MzQgMS4yODUgMS40MzdTMzYuOTggMzMuMTMgNTMuODc1IDM0LjI0Yy42NjQuMDcgMS4zMzYuMTA5IDIuMDE2LjEwOS42ODMgMCAxLjM1NS0uMDQgMi4wMi0uMTFDNzQuOCAzMy4xMjggODcuNTQyIDE4LjYxIDg3LjU0MiAxOC42MWwxLjI4MS0xLjQzNy0xLjI4MS0xLjQzNFM3My43ODUgMCA1NS44OTEgMFptMCA0LjI5M2M0LjcyMyAwIDkuMDcgMS4yNTggMTIuODU2IDIuOThhMTQuOTAzIDE0LjkwMyAwIDAgMSAyLjE3MiA3Ljc1NCAxNC45OTYgMTQuOTk2IDAgMCAxLTE1LjAyNyAxNS4wMjggMTQuOTk2IDE0Ljk5NiAwIDAgMS0xNS4wMjgtMTUuMDI4YzAtMi44Ljc2Mi01LjQwNiAyLjA5LTcuNjQ4bC0uMDY2LS4wNGMzLjgyLTEuNzU3IDguMjE4LTMuMDQ2IDEzLjAwNC0zLjA0NlptMCA0LjI5M2E2LjQzOSA2LjQzOSAwIDAgMC0yLjQ2NC40OTJjLS4zOS4xNi0uNzYyLjM2LTEuMTE0LjU5NGE2LjYxNyA2LjYxNyAwIDAgMC0uOTc2LjhjLS4yOTcuMzAxLS41NjMuNjI2LS44Ljk3N2E2LjUyIDYuNTIgMCAwIDAtLjk2IDIuMzIgNi40NzUgNi40NzUgMCAwIDAtLjEyMyAxLjI1OGMwIC40MjIuMDQuODQuMTIxIDEuMjU4YTYuNTIgNi41MiAwIDAgMCAuOTYxIDIuMzJjLjIzOC4zNTIuNTA0LjY3Ni44Ljk3Ny4zMDIuMjk3LjYyNi41NjYuOTc3LjguMzUyLjIzNS43MjMuNDM0IDEuMTE0LjU5NWE2LjQzOSA2LjQzOSAwIDAgMCAyLjQ2NS40OTIgNi40NDggNi40NDggMCAwIDAgNi40NDEtNi40NDIgNi40NzkgNi40NzkgMCAwIDAtMS4wODYtMy41NzggNi4zNzkgNi4zNzkgMCAwIDAtMS43NzctMS43NzcgNi4zNDMgNi4zNDMgMCAwIDAtMS4xMTQtLjU5NCA2LjQ0OCA2LjQ0OCAwIDAgMC0yLjQ2NS0uNDkyWm0xOC43NTggMi4wMDhjNC4wNzUgMi43MTkgNi45NTcgNS41MTUgNy45OTcgNi41NzgtMS4yMzkgMS4yNy01LjAxMiA0Ljk5Mi0xMC40MTUgOC4xMWExOS4xNSAxOS4xNSAwIDAgMCAyLjk4LTEwLjI1NWMwLTEuNTI3LS4yMjItMy0uNTYyLTQuNDMzWm0tMzcuNTI3LjAxMWMtLjMzNiAxLjQyNi0uNTUgMi44OTktLjU1IDQuNDIyIDAgMy43NjYgMS4xMDUgNy4yNzggMi45OCAxMC4yNTQtNS4zOTktMy4xMTctOS4xNzItNi44NC0xMC40MTQtOC4xMSAxLjA0My0xLjA2MiAzLjkxOC0zLjg1NSA3Ljk4NC02LjU2NlptMCAwIi8+CiAgICA8L2c+Cjwvc3ZnPgo=';
}

/**
 * Returns a string containing the footer for the plugin pages. This footer contains links to the About Us and
 * Contact Us pages and the Terms and Conditions and Documentation.
 *
 * @return  string  Html code containing the footer for the plugin pages.
 */
function wppfm_page_footer() {
	return '<a href="' . WPPFM_EDD_SL_STORE_URL . '" target="_blank">' . esc_html__( 'About Us', 'wp-product-feed-manager' ) . '</a>
			 | <a href="' . WPPFM_EDD_SL_STORE_URL . 'support/" target="_blank">' . esc_html__( 'Contact Us', 'wp-product-feed-manager' ) . '</a>
			 | <a href="' . WPPFM_EDD_SL_STORE_URL . 'terms/" target="_blank">' . esc_html__( 'Terms and Conditions', 'wp-product-feed-manager' ) . '</a>
			 | <a href="' . WPPFM_EDD_SL_STORE_URL . 'support/documentation/create-product-feed/" target="_blank">' . esc_html__( 'Documentation', 'wp-product-feed-manager' ) . '</a>
			 | '
	. sprintf(
		/* translators: %s: five stars link */
		__( 'If you like working with our Feed Manager please leave us a %s rating. A huge thanks in advance!', 'wp-product-feed-manager' ),
		'<a href="https://wordpress.org/support/plugin/wp-product-feed-manager/reviews?rate=5#new-post" target="_blank" class="wppfm-rating-request">' . '&#9733;&#9733;&#9733;&#9733;&#9733;' . '</a>'
	);
}

function wppfm_check_prerequisites() {
	if ( ! wppfm_wc_installed_and_active() ) {
		echo wppfm_you_have_no_woocommerce_installed_message();
		exit;
	}

	if ( ! wppfm_wc_min_version_required() ) {
		echo wppfm_update_your_woocommerce_version_message();
		exit;
	}
}

