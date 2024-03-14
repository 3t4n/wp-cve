<?php
/**
 * View: Search Add-On promo Page
 *
 * @since      1.0.0
 * @package    wsal
 * @subpackage views
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Search Add-On promo Page.
 * Used only if the plugin is not activated.
 *
 * @package    wsal
 * @subpackage views
 */
class WSAL_Views_Search extends WSAL_ExtensionPlaceholderView {

	/**
	 * {@inheritDoc}
	 */
	public function get_title() {
		return esc_html__( 'Search Extension', 'wp-security-audit-log' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_name() {
		return esc_html__( 'Search Filters ✛', 'wp-security-audit-log' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_weight() {
		return 3;
	}

	/**
	 * {@inheritDoc}
	 */
	public function render() {
		$title        = esc_html__( 'Search & Filters for the Activity Log', 'wp-security-audit-log' );
		$description  = esc_html__( 'Add search filters and better text searching capabilities so you can find all the information you want in the activity log within just seconds. Upgrade to premium so you can:', 'wp-security-audit-log' );
		$addon_img    = trailingslashit( WSAL_BASE_URL ) . 'img/' . $this->get_safe_view_name() . '.jpg';
		$premium_list = array(
			esc_html__( 'Use search filters to fine tune the text search results', 'wp-security-audit-log' ),
			esc_html__( 'Easily find when and who did a specific change on your site', 'wp-security-audit-log' ),
			esc_html__( 'Easily identify and track back suspicious user behaviour', 'wp-security-audit-log' ),
			esc_html__( 'Search for the cause of a problem and ease troubleshooting', 'wp-security-audit-log' ),
			esc_html__( 'Save search terms and filters for future use and improved productivity', 'wp-security-audit-log' ),
		);
		$subtext      = false;
		$screenshots  = array(
			array(
				'desc' => esc_html__( 'Configure any filter you need to fine tune the search results and find what you are looking for with much less effort.', 'wp-security-audit-log' ),
				'img'  => trailingslashit( WSAL_BASE_URL ) . 'img/search/search_2.png',
			),
			array(
				'desc' => esc_html__( 'Save search terms and filters to run the searches again in the future with just a single click.', 'wp-security-audit-log' ),
				'img'  => trailingslashit( WSAL_BASE_URL ) . 'img/search/search_3.png',
			),
		);

		require_once __DIR__ . '/addons/html-view.php';
	}
}
