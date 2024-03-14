<?php
/**
 * WP Ultimate Exporter plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class ExportHandler extends ExportExtension{
	protected static $instance = null,$export_extension;
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$instance->doHooks();
			ExportHandler::$export_extension = ExportExtension::getInstance();
		}
		return self::$instance;
	}

	public  function doHooks(){
		add_action('wp_ajax_get_post_types',array($this,'getPostTypes'));
		add_action('wp_ajax_get_taxonomies',array($this,'getTaxonomies'));
		add_action('wp_ajax_get_authors',array($this,'getAuthors'));
	}

	/**
	 * SmackUCIExporter constructor.
	 *
	 * Set values into global variables based on post value
	 */
	public function __construct() {
		$this->plugin = Plugin::getInstance();
	}


	public  function getPostTypes(){
		check_ajax_referer('smack-ultimate-csv-importer', 'securekey');
		$i = 0;
		$get_post_types = get_post_types();
		array_push($get_post_types, 'widgets');
		foreach ($get_post_types as $key => $value) {
			if (($value !== 'featured_image') && ($value !== 'attachment') && ($value !== 'wpsc-product') && ($value !== 'wpsc-product-file') && ($value !== 'revision') && ($value !== 'post') && ($value !== 'page') && ($value !== 'wp-types-group') && ($value !== 'wp-types-user-group') && ($value !== 'product') && ($value !== 'product_variation') && ($value !== 'shop_order') && ($value !== 'shop_coupon') && ($value !== 'acf') && ($value !== 'acf-field') && ($value !== 'acf-field-group') && ($value !== '_pods_pod') && ($value !== '_pods_field') && ($value !== 'shop_order_refund') && ($value !== 'shop_webhook')) {
				$response['custom_post_type'][$i] = $value;
				$i++;
			}
		}						
		echo wp_json_encode($response);
		wp_die();
	}

	public function getAuthors(){
		check_ajax_referer('smack-ultimate-csv-importer', 'securekey');
		$i = 0;
		$blogusers =  get_users( [ 'role__in' => [ 'administrator', 'author' ] ] );
		foreach( $blogusers as $user ) { 
			$response['user_name'][$i] = $user->display_name;
			$response['user_id'][$i] = $user->ID;
			$i++;
		}
		echo wp_json_encode($response);
		wp_die();
	}

	public function getTaxonomies(){
		check_ajax_referer('smack-ultimate-csv-importer', 'securekey');
		$i = 0;
		foreach (get_taxonomies() as $key => $value) {
				$response['taxonomies'][$i] = $value;
				$i++;
		}
		echo wp_json_encode($response);
		wp_die();
	}
}

global $export_handler_class;
$export_handler_class = new ExportHandler();
