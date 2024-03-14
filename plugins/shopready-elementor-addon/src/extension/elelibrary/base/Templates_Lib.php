<?php

namespace Shop_Ready\extension\elelibrary\base;

use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Base;
use Elementor\TemplateLibrary\Source_Local;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\User;

/**
 *  Template Library.
 *
 * @since 1.0
 */
class Templates_Lib
{

	public static $source = null;
	/**
	 * FireFly library option key.
	 */
	const LIBRARY_OPTION_KEY = 'wr_templates_library';

	/**
	 * API templates URL.
	 *
	 * Holds the URL of the templates API.
	 *
	 * @access public
	 * @static
	 *
	 * @var string API URL.
	 */

	public static $api_url = 'https://plugins.quomodosoft.com/woo-templates/wp-json/rest/v1/woo/templates/info_library';

	/**
	 * Init.
	 *
	 * Initializes the hooks.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return void
	 */
	public function register()
	{

		add_action('elementor/init', [__CLASS__, 'register_source']);
		add_action('elementor/editor/after_enqueue_scripts', [__CLASS__, 'enqueue_editor_scripts']);
		add_action('elementor/ajax/register_actions', [__CLASS__, 'register_ajax_actions']);
		add_action('elementor/editor/footer', [__CLASS__, 'render_template']);
		add_action('wp_ajax_shopready_get_library_data', [__CLASS__, 'custom_eready_get_library_data']);
		add_action('wp_ajax_shopready_get_library_data_single', [__CLASS__, 'eready_get_library_data_single']);
	}

	/**
	 * Register source.
	 *
	 * Registers the library source.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return void
	 */
	public static function register_source()
	{

		Plugin::$instance->templates_manager->register_source(__NAMESPACE__ . '\FE_Source');
	}

	/**
	 * Enqueue Editor Scripts.
	 *
	 * Enqueues required scripts in Elementor edit mode.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @return void
	 */
	public static function enqueue_editor_scripts()
	{

		wp_enqueue_style('shop-ready-templates-lib', SHOP_READY_TEMPLATE_MODULE_URL . 'assets/css/editor.css', 1.9, true);

		wp_register_script('isotope', SHOP_READY_TEMPLATE_MODULE_URL . 'assets/js/isotope.js', ['jquery']);


		wp_enqueue_script(
			'shop-ready-templates-lib',
			SHOP_READY_TEMPLATE_MODULE_URL . 'assets/js/templates-lib.js',
			[
				'jquery',
				'backbone-marionette',
				'backbone-radio',
				'elementor-common-modules',
				'elementor-dialog',
				'isotope',
				'elementor-editor'

			],
			2.7,
			true
		);

		wp_localize_script(
			'shop-ready-templates-lib',
			'sr_templates_lib',
			array(
				'logoUrl' => SHOP_READY_PUBLIC_ROOT_IMG . 'editor-logo.svg',
				'ajax_nonce' => wp_create_nonce("elementor_reset_library")
			)
		);
	}

	/**
	 * Init ajax calls.
	 *
	 * Initialize template library ajax calls for allowed ajax requests.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param Ajax $ajax Elementor's Ajax object.
	 * @return void
	 */
	public static function register_ajax_actions(Ajax $ajax)
	{

		$library_ajax_requests = [
			'shopready_get_library_data',
		];

		foreach ($library_ajax_requests as $ajax_request) {
			$ajax->register_ajax_action($ajax_request, function ($data) use ($ajax_request) {
				return self::handle_ajax_request($ajax_request, $data);
			});
		}
	}

	/**
	 * Handle ajax request.
	 *
	 * Fire authenticated ajax actions for any given ajax request.
	 *
	 * @since 1.0
	 * @access private
	 *
	 * @param string $ajax_request Ajax request.
	 * @param array  $data Elementor data.
	 *
	 * @return mixed
	 * @throws \Exception Throws error message.
	 */
	private static function handle_ajax_request($ajax_request, array $data)
	{

		if (!User::is_current_user_can_edit_post_type(Source_Local::CPT)) {
			throw new \Exception('Access Denied');
		}

		if (!empty($data['editor_post_id'])) {
			$editor_post_id = absint($data['editor_post_id']);

			if (!get_post($editor_post_id)) {
				throw new \Exception(esc_html__('Post not found.', 'shopready-elementor-addon'));
			}

			Plugin::$instance->db->switch_to_post($editor_post_id);
		}

		$result = call_user_func([__CLASS__, $ajax_request], $data);

		if (is_wp_error($result)) {
			throw new \Exception($result->get_error_message());
		}

		return $result;
	}

	public static function get_source()
	{

		if (is_null(self::$source)) {
			self::$source = new FE_Source();
		}

		return self::$source;
	}

	/**
	 * Get library data.
	 *
	 * Get data for template library.
	 *
	 * @since 1.0
	 * @access public
	 *
	 * @param array $args Arguments.
	 *
	 * @return array Collection of templates data.
	 */
	public static function eready_get_library_data(array $args)
	{

		$library_data = self::get_library_data(!empty($args['sync']));
		// Ensure all document are registered.
		Plugin::$instance->documents->get_document_types();
		return [
			'templates' => self::get_templates(),
			'config' => $library_data['types_data'],
		];
	}

	public static function custom_eready_get_library_data()
	{

		$library_data = self::get_library_data(!empty($_GET['sync']));

		// Ensure all document are registered.
		Plugin::$instance->documents->get_document_types();

		wp_send_json([
			'templates' => self::get_templates(),
			'config' => $library_data['types_data'],
		]);
	}

	public static function direct_eready_get_library_data()
	{

		$library_data = self::get_library_data(true);
		// Ensure all document are registered.
		Plugin::$instance->documents->get_document_types();
		return [
			'templates' => self::get_templates(),
			'config' => $library_data['types_data'],
		];
	}

	/**
	 * Get templates.
	 *
	 * Retrieve all the templates from all the registered sources.
	 *
	 * @since 1.16.0
	 * @access public
	 *
	 * @return array Templates array.
	 */
	public static function get_templates()
	{
		$source = Plugin::$instance->templates_manager->get_source('shopready');
		return $source->get_items();
	}

	/**
	 * Ajax reset API data.
	 *
	 * Reset Elementor library API data using an ajax call.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 */
	public static function ajax_reset_api_data()
	{

		check_ajax_referer('elementor_reset_library', '_nonce');

		self::get_templates_data(true);

		wp_send_json_success();
	}

	/**
	 * Get templates data.
	 *
	 * This function the templates data.
	 *
	 * @since 1.0
	 * @access private
	 * @static
	 *
	 * @param bool $force_update Optional. Whether to force the data retrieval or
	 *                                     not. Default is false.
	 *
	 * @return array|false Templates data, or false.
	 */
	private static function get_templates_data($force_update = false)
	{

		$cache_key = 'sr_templates_data_' . 1.2;
		$templates_data = get_transient($cache_key);
		if ($force_update || false === $templates_data) {
			$timeout = ($force_update) ? 90 : 80;

			$response = wp_remote_get(self::$api_url, [
				'timeout' => $timeout,
				'body' => [
					// Which API version is used.
					'api_version' => 1.1,
					// Which language to return.
					'site_lang' => get_bloginfo('language'),
				],
			]);

			if (is_wp_error($response) || 200 !== (int) wp_remote_retrieve_response_code($response)) {
				set_transient($cache_key, [], 1 * HOUR_IN_SECONDS);

				return false;
			}

			$templates_data = json_decode(wp_remote_retrieve_body($response), true);

			if (empty($templates_data) || !is_array($templates_data)) {
				set_transient($cache_key, [], 1 * HOUR_IN_SECONDS);

				return false;
			}

			if (isset($templates_data['library'])) {
				update_option(self::LIBRARY_OPTION_KEY, $templates_data['library'], 'no');

				unset($templates_data['library']);
			}

			set_transient($cache_key, $templates_data, 12 * HOUR_IN_SECONDS);
		}

		return $templates_data;
	}

	/**
	 * Get templates data.
	 *
	 * Retrieve the templates data from a remote server.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 * @param bool $force_update Optional. Whether to force the data update or
	 *                                     not. Default is false.
	 *
	 * @return array The templates data.
	 */
	public static function get_library_data($force_update = false)
	{
		//self::get_templates_data( $force_update );
		self::get_templates_data(true);

		$library_data = get_option(self::LIBRARY_OPTION_KEY);

		if (empty($library_data)) {
			return [];
		}

		return $library_data;
	}

	/**
	 * Get template content.
	 *
	 * Retrieve the templates content received from a remote server.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return object|\WP_Error The template content.
	 */
	public static function get_template_content($template_id)
	{

		$url = self::$api_url . '/' . $template_id;

		$_key = apply_filters('shop_ready_product', '*');

		if (empty($_key)) {
			return new \WP_Error('no_license', esc_html__('Product is not active.', 'shopready-elementor-addon'));
		}

		$body = self::insecure_request($url);

		if (false === $body) {
			return new \WP_Error(422, esc_html__('Wrong Server Response', 'shopready-elementor-addon'));
		}

		return $body;
	}

	public static function eready_get_library_data_single()
	{

		$source = self::get_source();

		$data = self::get_template_content(sanitize_text_field($_REQUEST['tpl_id']));

		if (is_wp_error($data)) {
			return $data;
		}

		$data = (array) $data;

		wp_send_json_success([
			'cus' => $source->get_custom_data($data, sanitize_text_field($_REQUEST['editor_post_id']))
		]);
	}

	public static function insecure_request($url)
	{

		$request = wp_remote_get(
			$url,
			array(
				'sslverify' => false
			)
		);

		$response = json_decode(wp_remote_retrieve_body($request), true);

		return $response;
	}

	/**
	 * Render template.
	 *
	 * Library modal template.
	 *
	 * @since 1.0
	 * @access public
	 * @static
	 *
	 * @return void
	 */
	public static function render_template()
	{

?>
		<style>
			.seready-header-left {
				display: flex;
				align-items: center;
				gap: 7px;
			}

			.sready-template-lib-modal-overlay {
				display: none;
				position: fixed;
				z-index: 1;
				left: 0;
				top: 0;
				height: 100%;
				width: 100%;
				overflow: auto;
				background-color: #fff;
			}

			.sready-sr-template-lib-modal-content {
				background-color: #fff;
				width: 100%;
				height: 100%;
			}

			.sraedy-sr-template-lib-modal-header {
				background: #ffffffd9;
				padding: 9px 15px;
				color: #191111;
				display: flex;
				flex-direction: row;
				justify-content: space-between;
			}

			.sready-template-lib-modal-body {
				padding: 10px 20px;
				background: #EEF4FF;
				position: relative;
			}

			.body-import-active-overlay {
				display: block;
				width: 100%;
				height: 100vh;
				position: fixed;
				left: 0;
				top: 0;
				z-index: 999;
				background: #ffffff85;
			}

			.sready-template-lib-modal-body #sready-sr-template-render-section {
				width: 100%;
			}

			.shop-ready-template-block-category-left {
				width: 20%;
				max-width: 120px;
			}

			.shop-ready-sr-template-category-section .shop-sr-templates-category {
				width: 250px;
			}

			.shop-ready-sr-template-category-section {
				display: flex;
				justify-content: space-between;

				padding: 10px 4px 19px 5px;

				gap: 20px;

			}

			.er-category-wrapper {
				display: flex;
				align-items: center;
				gap: 11px;
				width: 250px;
			}

			.er-tpl-category-label {
				font-weight: 500;
				font-size: 18px;
			}

			.shop-sr-templates-category option {
				padding: 5px;
			}

			.shop-ready-sr-template-category-section select {
				-webkit-appearance: none;
				-moz-appearance: none;
				-ms-appearance: none;
				appearance: none;
				outline: 0;
				height: 100%;
				box-shadow: none;
				border: 0 !important;
				background-image: none;
				background: #fff;
				flex: 1;
				padding: 0 .5em;
				color: #000;
				cursor: pointer;
				font-size: 1em;
			}

			.shop-ready-sr-template-category-section select::-ms-expand {
				display: none;
			}

			.shop-ready-sr-template-category-section .er-category-wrapper {
				position: relative;
				display: flex;
				width: 16em;
				height: 3em;
				line-height: 3;
				overflow: hidden;
				border-radius: 0.25em;
				gap: 15px;
				color: #fff;
				padding-left: 0;
			}

			.er-category-wrapper::after {
				content: '\25BC';
				position: absolute;
				top: 0;
				right: 0;
				padding: 0 1em;
				background: #fff;
				cursor: pointer;
				pointer-events: none;
				transition: .25s all ease;
				color: #000;
			}

			.er-category-wrapper:hover::after {
				color: #fff;
			}

			.shop-sr-ready--tpl-search {
				position: relative;
				color: #aaa;
				font-size: 16px;
			}

			.shop-sr-ready--tpl-search {
				display: inline-block;
			}

			.shop-sr-ready--tpl-search input {
				width: 250px;
				height: 39px;
				background: #fcfcfc;
				border-radius: 5px;
			}

			.shop-ready--tpl-tag-filter {
				display: flex;
				gap: 15px;
				align-items: center;
				cursor: pointer;
			}

			.shop-ready--tpl-tag-filter>div {
				padding: 11px;
				color: #055BFF;
				font-weight: 500;
			}

			.shop-ready-grid-item-inner-content.active,
			.shop-ready-grid-item-inner-content:hover {
				border-right: 5px solid #055BFF;
				border-top: 5px solid #055BFF;
			}

			.shop-ready-grid-item-inner-content.active {
				border-color: #8b5bff;
			}

			.elementor-add-section-area-button.shop-ready-add-template-button:hover {
				box-shadow: none;
			}

			.shop-ready-grid-item-inner-content .action-wrapper {
				position: absolute;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				display: flex;
				justify-content: center;
				align-items: center;
			}

			.srrready-header-right {
				display: flex;
				align-items: center;
			}

			.er-filter-wrapper .shop-ready-active-tags {
				background: #8b5bff;
			}

			.shop-sr-ready--tpl-search input {
				text-indent: 32px;
			}

			.shop-sr-ready--tpl-search .eicon-search {
				position: absolute;
				top: 10px;
				left: 10px;
			}

			.shop-ready-grid-item-inner-content {
				position: relative;
			}

			.shop-ready-grid-item-inner-content .er-tyemplate-view {
				background: orange;
				display: inline-block;
				padding: 10px;
				color: #000;
				opacity: 0;
				visibility: hidden;
				transition: all 0.3s ease-in-out;
			}

			.shop-ready-grid-item-inner-content .shop-sr-template-import {
				background: #055BFF;
				display: inline-block;
				padding: 10px;
				color: #fff;
				opacity: 0;
				visibility: hidden;
				transition: all 0.3s ease-in-out;
			}

			.shop-ready-grid-item-inner-content.active .action-wrapper,
			.shop-ready-grid-item-inner-content:hover .action-wrapper {
				background: #000000d1;
			}

			.shop-ready-grid-item-inner-content .er-template-pro {
				background: #f11577;
				display: inline-block;
				padding: 10px 18px;
				color: #fff;
				opacity: 1;
				visibility: visible;
				transition: all 0.3s ease-in-out;
			}

			.shop-ready-grid-item-inner-content.active .er-template-pro,
			.shop-ready-grid-item-inner-content.active .shop-sr-template-import,
			.shop-ready-grid-item-inner-content.active h3.shop-ready-tpl-title,
			.shop-ready-grid-item-inner-content.active .er-tyemplate-view,
			.shop-ready-grid-item-inner-content:hover .er-template-pro,
			.shop-ready-grid-item-inner-content:hover .shop-sr-template-import,
			.shop-ready-grid-item-inner-content:hover h3.shop-ready-tpl-title,
			.shop-ready-grid-item-inner-content:hover .er-tyemplate-view {
				opacity: 1;
				visibility: visible;
			}

			h3.shop-ready-tpl-title {
				position: absolute;
				top: 0;
				text-align: center;
				width: 100%;
				color: #fff;
				padding: 9px;
				opacity: 1;
				visibility: hidden;
			}

			.shop-sr-ready--tpl-search .eicon-search {
				left: auto;
				right: 10px;
			}

			.shop-ready-template-grid-wrapper:after {
				content: '';
				display: block;
				clear: both;
			}

			.shop-ready-template-single-item,
			.grid-sizer {
				width: calc(33.33% - 32px);
			}

			.shop-ready-template-single-item.page {
				height: 600px;
			}

			.shop-ready-template-single-item {
				float: left;
				height: 250px;

				padding: 16px;
				overflow: hidden;
			}

			.shop-ready-grid-item-inner-content .img-wrapper {
				width: 100%;
				min-height: 100px;
			}

			.shop-ready-grid-item-inner-content .img-wrapper img {
				width: 100%;
				height: 100%
			}

			.shop-ready-tpl-sort-filter-wrapper {
				display: flex;
				align-items: center;
				gap: 25px;
			}

			.shop-ready-temlpates-sorts-button-group button {
				padding: 10px;
				background: #FFFFFF;
				color: #000000;
				border-radius: 5px;
				border: 0;
				cursor: pointer;
			}

			.shop-ready-temlpates-sorts-button-group {
				display: flex;
				gap: 10px;
			}
		</style>

<?php
		include_once('view.php');
	}
}
