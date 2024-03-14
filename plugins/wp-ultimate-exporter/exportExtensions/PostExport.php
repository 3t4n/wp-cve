<?php
/**
 * WP Ultimate Exporter plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */

namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

/**
 * Class PostExport
 * @package Smackcoders\WCSV
 */	
class PostExport extends ExportExtension{

	protected static $instance = null,$mapping_instance,$export_handler,$export_instance;
	public $offset = 0;	
	public $limit;
	public $totalRowCount;
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$export_instance = ExportExtension::getInstance();
		}
		return self::$instance;
	}

	/**
	 * PostExport constructor.
	 */
	public function __construct() {
		$this->plugin = Plugin::getInstance();
	}

	/**
	 * Get records based on the post types
	 * @param $module
	 * @param $optionalType
	 * @param $conditions
	 * @return array
	 */
	public function getRecordsBasedOnPostTypes ($module, $optionalType, $conditions ,$offset , $limit ,$headers = '') {
		global $wpdb;
		if($module == 'CustomPosts' && $optionalType == 'nav_menu_item'){
			$get_menu_id = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}terms AS t LEFT JOIN {$wpdb->prefix}term_taxonomy AS tt ON tt.term_id = t.term_id WHERE tt.taxonomy = 'nav_menu' ", ARRAY_A);
			$get_menu_arr = array_column($get_menu_id, 'term_id');
			self::$export_instance->totalRowCount = count($get_menu_arr);
			return $get_menu_arr;			
		}
		if($module == 'CustomPosts' && $optionalType == 'widgets'){
			$get_widget_id = $wpdb->get_row("SELECT option_id FROM {$wpdb->prefix}options where option_name = 'widget_recent-posts' ", ARRAY_A);
			self::$export_instance->totalRowCount = 1;
			return $get_widget_id;			
		}
		if($module == 'CustomPosts') {
			$module = $optionalType;
		} elseif ($module == 'WooCommerceOrders') {
			$module = 'shop_order';
		}
		elseif ($module == 'WooCommerceCoupons') {
			$module = 'shop_coupon';
		}
		elseif ($module == 'WooCommerceRefunds') {
			$module = 'shop_order_refund';
		}
		elseif ($module == 'WooCommerceVariations') {
			if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
				$module = 'product_variation';
				$extracted_ids = "select DISTINCT ID from {$wpdb->prefix}posts";
				$extracted_ids .= " where post_type = '$module'";
				$extracted_ids .= "and post_status in ('publish','draft','future','private','pending') AND post_parent!=0";
				$extracted_id = $wpdb->get_col($extracted_ids);
				$extracted_ids =array();
				foreach($extracted_id as $ids){
					
					$parent_id = $wpdb->get_var("SELECT post_parent FROM {$wpdb->prefix}posts where ID=$ids");
					
					$post_status =$wpdb->get_var("SELECT post_status FROM {$wpdb->prefix}posts where ID=$parent_id");
					if(!empty($post_status )){
						if($post_status !='trash' && $post_status != 'inherit'){
							$extracted_ids [] =$ids;
						}

					}
				}
				self::$export_instance->totalRowCount = count($extracted_ids);          
				return array_reverse($extracted_ids);
			}
			else{
				$product_statuses = array('publish', 'draft', 'future', 'private', 'pending');
				$products = wc_get_products(array('status' => $product_statuses ,'limit' => -1));
				$variable_product_ids = [];
				foreach($products as $product){
					if ($product->is_type('variable')) {
						$variable_product_ids[] = $product->get_id();
					}
				 }$variation_ids = [];
				foreach($variable_product_ids as $variable_product_id){
					$variable_product = wc_get_product($variable_product_id);
					$variation_ids[]  = $variable_product->get_children();
				}
				$extracted_ids = [];
				foreach ($variation_ids as $v_ids) {
					foreach ($v_ids as $v_id) {
						$extracted_ids[] = $v_id;
					}
				}
				self::$export_instance->totalRowCount = count($extracted_ids);          
				return array_reverse($extracted_ids);
			}		
		}
		elseif($module == 'WPeCommerceCoupons'){
			$module = 'wpsc-coupon';
		}
		else {
			$module = self::import_post_types($module);
		}

		$get_post_ids = "select DISTINCT ID from {$wpdb->prefix}posts";
		$get_post_ids .= " where post_type = '$module'";

		/**
		 * Check for specific status
		 */
		if($module == 'product' && is_plugin_active('woocommerce/woocommerce.php')){	
			if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
				//TODO temporary fix 
				//wc_get_products only exports default language product 
				$products = "select DISTINCT ID from {$wpdb->prefix}posts";
				$products .= " where post_type = '$module'";
				$products .= "and post_status in ('publish','draft','future','private','pending')";
				$products = $wpdb->get_col($products);
			   self::$export_instance->totalRowCount = count($products);
			   return array_reverse($products);	
			}
		   else{
			$offset = self::$export_instance->offset;
			$limit = self::$export_instance->limit;
			$product_statuses = array('publish', 'draft', 'future', 'private', 'pending');
			$products_count = wc_get_products(array('status' => $product_statuses, 'numberposts' => -1));
			$ids = array();
			foreach ($products_count as $my_product) {
				$ids[] = $my_product->get_id();
			}
			self::$export_instance->totalRowCount = count($ids);
			$args = array('status' => $product_statuses,'numberposts' => $limit,'offset' => $offset,'orderby' => 'date','order' => 'ASC');
			$products = wc_get_products($args);
			$product_ids = array();
			foreach ($products as $product) {
				$product_ids[] = $product->get_id();
			}
			sort($product_ids);
			return $product_ids;	
		   }
		}
		if($module == 'shop_order'){
			if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
				$order_statuses_id = array('wc-completed', 'wc-cancelled', 'wc-on-hold', 'wc-processing', 'wc-pending');
				$orders_id = wc_get_orders(array('status' => $order_statuses));
				$order_ids = wp_list_pluck($orders_id, 'ID');
				$get_post_ids = array_reverse($order_ids);  
				foreach($get_post_ids as $ids){
					$module =$wpdb->get_var("SELECT post_type FROM {$wpdb->prefix}posts where id=$ids");
				}
				if($module == 'shop_order_placehold'){
					$orders = "select DISTINCT p.ID from {$wpdb->prefix}posts as p inner join {$wpdb->prefix}wc_orders as wc ON p.ID=wc.id";
					$orders.= " where p.post_type = '$module'";
					$orders .= "and wc.status in ('wc-completed', 'wc-cancelled', 'wc-on-hold', 'wc-processing', 'wc-pending')";
					$orders = $wpdb->get_col($orders);
					self::$export_instance->totalRowCount = count($orders);          
					return $orders;
				}
				else{
					$orders = "select DISTINCT ID from {$wpdb->prefix}posts";
					$orders.= " where post_type = '$module'";
					$orders .= "and post_status in ('wc-completed','wc-cancelled','wc-on-hold','wc-processing','wc-pending')";
					$orders = $wpdb->get_col($orders);
					self::$export_instance->totalRowCount = count($orders);          
					return $orders;
				}
				
			}
			else{
				if(!empty($conditions['specific_status']['status'])) {
					if($conditions['specific_status']['status'] == 'All') {
						$get_post_ids .= " and post_status in ('wc-completed','wc-cancelled','wc-refunded','wc-on-hold','wc-processing','wc-pending')";
					} elseif($conditions['specific_status']['status'] == 'Completed Orders') {
						$get_post_ids .= " and post_status in ('wc-completed')";
					} elseif($conditions['specific_status']['status'] == 'Cancelled Orders') {
						$get_post_ids .= " and post_status in ('wc-cancelled')";
					} elseif($conditions['specific_status']['status'] == 'On Hold Orders') {
						$get_post_ids .= " and post_status in ('wc-on-hold')";
					} elseif($conditions['specific_status']['status'] == 'Processing Orders') {
						$get_post_ids .= " and post_status in ('wc-processing')";
					} elseif($conditions['specific_status']['status'] == 'Pending Orders') {
						$get_post_ids .= " and post_status in ('wc-pending')";
					} 
				} else {
					$order_statuses = array('wc-completed', 'wc-cancelled', 'wc-on-hold', 'wc-processing', 'wc-pending');
					$orders = wc_get_orders(array('status' => $order_statuses, 'numberposts' => -1));
					$order_ids = wp_list_pluck($orders, 'ID');
					$get_post_ids = array_reverse($order_ids);  
					self::$export_instance->totalRowCount = count($get_post_ids);
					return $get_post_ids;				
				}
			}
		}elseif ($module == 'shop_coupon') {
			if(!empty($conditions['specific_status']['status'])) {
				if($conditions['specific_status']['status'] == 'All') {
					$get_post_ids .= " and post_status in ('publish','draft','pending')";
				} elseif($conditions['specific_status']['status']== 'Publish') {
					$get_post_ids .= " and post_status in ('publish')";
				} elseif($conditions['specific_status']['status'] == 'Draft') {
					$get_post_ids .= " and post_status in ('draft')";
				} elseif($conditions['specific_status']['status'] == 'Pending') {
					$get_post_ids .= " and post_status in ('pending')";
				} 
			} else {
				$get_post_ids .= " and post_status in ('publish','draft','pending')";
			}

		}elseif ($module == 'shop_order_refund') {

		}
		elseif( $module == 'lp_order'){
			$get_post_ids .= " and post_status in ('lp-pending', 'lp-processing', 'lp-completed', 'lp-cancelled', 'lp-failed')";
		}
		elseif ($module == 'forum') {
			$get_post_ids .= " and post_status in ('publish','draft','future','private','pending','hidden')";
		}
		elseif ($module == 'topic') {
			$get_post_ids .= " and post_status in ('publish','draft','future','open','pending','closed','spam')";
		}
		elseif ($module == 'reply') {
			$get_post_ids .= " and post_status in ('publish','spam','pending')";
		}
		else {
			if(!empty($conditions['specific_status']['status'])) {
				if($conditions['specific_status']['status'] == 'All') {
					$get_post_ids .= " and post_status in ('publish','draft','future','private','pending')";
				} elseif($conditions['specific_status']['status'] == 'Publish' || $conditions['specific_status']['status'] == 'Sticky') {
					$get_post_ids .= " and post_status in ('publish')";
				} elseif($conditions['specific_status']['status'] == 'Draft') {
					$get_post_ids .= " and post_status in ('draft')";
				} elseif($conditions['specific_status']['status'] == 'Scheduled') {
					$get_post_ids .= " and post_status in ('future')";
				} elseif($conditions['specific_status']['status'] == 'Private') {
					$get_post_ids .= " and post_status in ('private')";
				} elseif($conditions['specific_status']['status'] == 'Pending') {
					$get_post_ids .= " and post_status in ('pending')";
				} elseif($conditions['specific_status']['status'] == 'Protected') {
					$get_post_ids .= " and post_status in ('publish') and post_password != ''";
				}
			} else {
				$get_post_ids .= " and post_status in ('publish','draft','future','private','pending')";
			}
		}
		// Check for specific period
		if(!empty($conditions['specific_period']['is_check']) && $conditions['specific_period']['is_check'] == 'true') {
			if($conditions['specific_period']['from'] == $conditions['specific_period']['to']){
				$get_post_ids .= " and post_date >= '" . $conditions['specific_period']['from'] . "'";
			}else{
				$get_post_ids .= " and post_date >= '" . $conditions['specific_period']['from'] . "' and post_date <= '" . $conditions['specific_period']['to'] . " 23:00:00'";
			}
		}
		if($module == 'woocommerce')
			$get_post_ids .= " and pm.meta_key = '_sku'";

		if($module == 'wpcommerce')
			$get_post_ids .= " and pm.meta_key = '_wpsc_sku'";

		// Check for specific authors
		if (!empty($conditions['specific_authors']['is_check'] == '1') && !empty($conditions['specific_authors']['author'])) {
			if (isset($conditions['specific_authors']['author'])) {
				$author_ids = implode(',', $conditions['specific_authors']['author']);
				$get_post_ids .= " AND post_author IN ({$author_ids})";

			}
		}


		//WpeCommercecoupons
		if($module == 'wpsc-coupon'){
			$get_post_ids = "select DISTINCT ID from {$wpdb->prefix}wpsc_coupon_codes";
		}
		//WpeCommercecoupons
		$get_total_row_count = $wpdb->get_col($get_post_ids);
		if(!empty($conditions['specific_period']['is_check']) && $conditions['specific_period']['is_check'] == 'true') {
			if($conditions['specific_period']['from'] == $conditions['specific_period']['to']){
				$result = array();
				foreach($get_total_row_count as $result_value){
					$get_post_date_time = $wpdb->get_results( $wpdb->prepare("SELECT post_date FROM {$wpdb->prefix}posts WHERE id=$result_value") ,ARRAY_A);
					$get_post_date = date("Y-m-d",strtotime($get_post_date_time[0]['post_date'] ));
					if($get_post_date == $conditions['specific_period']['from']){
						$get_post_date_value[] = $result_value;
					}		
				}
				self::$export_instance->totalRowCount = count($get_post_date_value);
				$result = $get_post_date_value;
			}
			else{
				self::$export_instance->totalRowCount = count($get_total_row_count);
				$offset_limit = " order by ID asc limit $offset, $limit";
				$query_with_offset_limit = $get_post_ids . $offset_limit;
				$result = $wpdb->get_col($query_with_offset_limit);
			}
		}
		else{
			self::$export_instance->totalRowCount = count($get_total_row_count);
			$offset_limit = " order by ID asc limit $offset, $limit";
			$query_with_offset_limit = $get_post_ids . $offset_limit;
			$result = $wpdb->get_col($query_with_offset_limit);
		}
		// Get sticky post alone on the specific post status
		if(isset($conditions['specific_period']['is_check']) && isset($conditions['specific_status']['is_check']) && $conditions['specific_status']['is_check'] == 'true') {
			if(isset($conditions['specific_status']['status']) && $conditions['specific_status']['status'] == 'Sticky') {
				$get_sticky_posts = get_option('sticky_posts');
				foreach($get_sticky_posts as $sticky_post_id) {
					if(in_array($sticky_post_id, $result))
						$sticky_posts[] = $sticky_post_id;
				}
				return $sticky_posts;
			}
		}
		return $result;
	}

	public function import_post_types($import_type, $importAs = null) {	
		$import_type = trim($import_type);
		$module = array('Posts' => 'post', 'Pages' => 'page', 'Users' => 'user', 'Comments' => 'comments', 'Taxonomies' => $importAs, 'CustomerReviews' =>'wpcr3_review', 'Categories' => 'categories', 'Tags' => 'tags', 'WooCommerce' => 'product', 'WPeCommerce' => 'wpsc-product','WPeCommerceCoupons' => 'wpsc-product','WooCommerceVariations' => 'product', 'WooCommerceOrders' => 'product', 'WooCommerceCoupons' => 'product', 'WooCommerceRefunds' => 'product', 'CustomPosts' => $importAs);
		foreach (get_taxonomies() as $key => $taxonomy) {
			$module[$taxonomy] = $taxonomy;
		}
		if(array_key_exists($import_type, $module)) {
			return $module[$import_type];
		}
		else {
			return $import_type;
		}
	}

	/**
	 * Function to export the meta information based on Fetch ACF field information to be export
	 * @param $id
	 * @return mixed
	 */
	public function getPostsMetaDataBasedOnRecordId ($id, $module, $optionalType) {

		global $wpdb;
		$typeOftypesField = NULL; $checkRep = NULL; $allacf= NULL; $alltype = NULL; $parent = NULL; $typesf= NULL;$jet_metafields=NULL;
		if($module == 'Users'){
			$query = $wpdb->prepare("SELECT user_id,meta_key,meta_value FROM {$wpdb->prefix}users wp JOIN {$wpdb->prefix}usermeta wpm ON wpm.user_id = wp.ID where meta_key NOT IN (%s,%s) AND ID=%d", '_edit_lock', '_edit_last', $id);
		}else if($module == 'Categories' || $module == 'Taxonomies' || $module == 'Tags'){
			$query = $wpdb->prepare("SELECT wp.term_id,meta_key,meta_value FROM {$wpdb->prefix}terms wp JOIN {$wpdb->prefix}termmeta wpm ON wpm.term_id = wp.term_id where meta_key NOT IN (%s,%s) AND wp.term_id = %d", '_edit_lock', '_edit_last', $id);
		}else{
			$query = $wpdb->prepare("SELECT post_id,meta_key,meta_value FROM {$wpdb->prefix}posts wp JOIN {$wpdb->prefix}postmeta wpm ON wpm.post_id = wp.ID where meta_key NOT IN (%s,%s) AND ID=%d", '_edit_lock', '_edit_last', $id);
		}

		$get_acf_fields = $wpdb->get_results("SELECT ID, post_excerpt, post_content, post_name, post_parent, post_type FROM {$wpdb->prefix}posts where post_type = 'acf-field'", ARRAY_A);

		$group_unset = array('customer_email', 'product_categories', 'exclude_product_categories');

		if(!empty($get_acf_fields)){
			foreach ($get_acf_fields as $key => $value) {

				if(!empty($value['post_parent'])){
					$parent = get_post($value['post_parent']);
					if(!empty($parent)){
						if($parent->post_type == 'acf-field'){
							$allacf[$value['post_excerpt']] = $parent->post_excerpt.'_'.$value['post_excerpt']; 
						}else{
							$allacf[$value['post_excerpt']] = $value['post_excerpt']; 	
						}
					}else{
						$allacf[$value['post_excerpt']] = $value['post_excerpt']; 
					}
				}else{
					$allacf[$value['post_excerpt']] = $value['post_excerpt']; 
				}

				self::$export_instance->allacf = $allacf;

				$content = unserialize($value['post_content']);
				$alltype[$value['post_excerpt']] = $content['type'];

				if($content['type'] == 'repeater' || $content['type'] == 'flexible_content'){
					$checkRep[$value['post_excerpt']] = $this->getRepeater($value['ID']);
				}else{
					$checkRep[$value['post_excerpt']] = "";
				}
			}
		}

		self::$export_instance->allpodsfields = $this->getAllPodsFields();

		self::$export_instance->alltoolsetfields = get_option('wpcf-fields');

		if(!empty(self::$export_instance->alltoolsetfields)){
			$i = 1;
			foreach (self::$export_instance->alltoolsetfields as $key => $value) {
				$typesf[$i] = 'wpcf-'.$key;
				$typeOftypesField[$typesf[$i]] = $value['type']; 
				$i++;
			}
		}

		self::$export_instance->typeOftypesField = $typeOftypesField;

		$result = $wpdb->get_results($query);

		// jeteng fields
		if(is_plugin_active('jet-engine/jet-engine.php')){

			$jetEnginefields = $wpdb->get_results("SELECT id, meta_fields FROM {$wpdb->prefix}jet_post_types WHERE slug = '$optionalType' AND status IN ('publish','built-in')", ARRAY_A);
			$jetEnginefields[0]['meta_fields']=isset($jetEnginefields[0]['meta_fields'])?$jetEnginefields[0]['meta_fields']:'';

			$unserializedMeta = maybe_unserialize($jetEnginefields[0]['meta_fields']);
			$unserializedMeta=isset($unserializedMeta)?$unserializedMeta:'';

			if(is_array($unserializedMeta)){
				foreach($unserializedMeta as $jet_key => $jetValue){
					$jetFieldLabel = $jetValue['title'];
					$jetFieldType = $jetValue['type'];
					if($jetFieldType != 'repeater' && $jetFieldType != 'media' && $jetFieldType != 'gallery' && $jetFieldType != 'posts' && $jetFieldType != 'html' ){					
						$jetFieldNameArr[] = $jetValue['name'];
					}
					else{
						$jetFieldNameArr[] = $jetValue['name'];
						$fields=$jetValue['repeater-fields'];
						if(is_array($fields)){
							foreach($fields as $repFieldKey => $repFieldVal){
								$jetFieldName[] = $repFieldVal['name'];

							}
						}
					}
				}	
			}

			if(isset($jetFieldName) && is_array($jetFieldName) ){
				if(is_array($jetFieldNameArr)){
					$jetCPTFieldsName=array_merge($jetFieldNameArr,$jetFieldName);
				}
				else{
					$jetCPTFieldsName= $jetFieldName;
				}

			}
			else{
				$jetFieldNameArr = isset($jetFieldNameArr) ? $jetFieldNameArr : '';
				$jetCPTFieldsName= $jetFieldNameArr;
			}

			//jeteng metabox fields

			global $wpdb;	
			$getMetaFields = $wpdb->get_results( $wpdb->prepare("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=%s",'jet_engine_meta_boxes'),ARRAY_A);			
			if(!empty($getMetaFields)){
				$unserializedMeta = maybe_unserialize($getMetaFields[0]['option_value']);
			}
			else{
				$unserializedMeta = '';
			}

			if(is_array($unserializedMeta)){
				$arraykeys = array_keys($unserializedMeta);

				foreach($arraykeys as $val){
					$values = explode('-',$val);
					$v = $values[1];
				}
			}


			$jetMetaFieldName = [];
			if(isset($v)){
				for($i=1 ; $i<=$v ; $i++){
					$unserializedMeta['meta-'.$i]= isset($unserializedMeta['meta-'.$i])? $unserializedMeta['meta-'.$i] : '';
					$fields= $unserializedMeta['meta-'.$i];					
					if(!empty($fields)){
						foreach($fields['meta_fields'] as $jet_key => $jetValue){
							if($jetValue['type'] != 'repeater'){
								$jetMetaFieldName[] = $jetValue['name'];
							}
							else{
								$jetMetaFieldName[] = $jetValue['name'];
								$jetRepFields = $jetValue['repeater-fields'];
								foreach($jetRepFields as $jetRepKey => $jetRepVal){
									$jetRepFieldName[] = $jetRepVal['name'];
								}
							}
						}
					}

				}
			}	
			if( isset($jetRepFieldName) && is_array($jetRepFieldName)){
				if(is_array($jetMetaFieldName)){
					$jetFName = array_merge($jetMetaFieldName,$jetRepFieldName);
				}
				else{
					$jetFName= $jetRepFieldName;
				}
			}
			else{
				$jetFName= $jetMetaFieldName;
			}
		}
		else{
			$jetCPTFieldsName =$jetFName= $jet_tax_fields_name = '';
		}

		//added for metabox plugin fields
		if(is_plugin_active('meta-box/meta-box.php')){
			$metabox_import_type = self::import_post_types($module, $optionalType);
			$metabox_fields = \rwmb_get_object_fields( $metabox_import_type ); 

		$taxonomies = get_taxonomies();
				
		if ($metabox_import_type == 'user')
		{
			$metabox_fields = \rwmb_get_object_fields($metabox_import_type, 'user');
		}
		else if (array_key_exists($metabox_import_type, $taxonomies))
		{
			$metabox_fields = \rwmb_get_object_fields($metabox_import_type, 'term');
		}
		else
		{
			$metabox_fields = \rwmb_get_object_fields($metabox_import_type);
		}
		$this->getCustomFieldValue($id, $value=null, $checkRep, $allacf, $typeOftypesField, $alltype, $jet_fields=null, $jet_types=null, $jet_rep_fields =null, $jet_rep_types=null,  $parent, $typesf, $group_unset , $optionalType , self::$export_instance->allpodsfields, $metabox_fields, $module, $metabox_relation_fields =null);			

		}
		else{
			$metabox_fields = [];
		}

		if(!empty($result)) {

			foreach($result as $key => $value) {

				if($value->meta_key == 'rank_math_schema_BlogPosting'){
					$rank_value=$value->meta_value;	
					$rank_math=unserialize($rank_value)	;
					$headline=$rank_math['headline'];
					$schema_description=$rank_math['description'];
					$article_type=$rank_math['@type'];
					$re_id =  $wpdb->get_results("SELECT redirection_id FROM {$wpdb->prefix}rank_math_redirections_cache where object_id='$id'");	
					$redirect_id=$re_id[0];
					$redirection_id=$redirect_id->redirection_id;
					$result =  $wpdb->get_results("SELECT url_to,header_code FROM {$wpdb->prefix}rank_math_redirections where id='$redirection_id'");	
					$rank_math_redirections=$result[0];
					$url_to=$rank_math_redirections->url_to;
					$header_code=$rank_math_redirections->header_code;

					self::$export_instance->data[$id]['headline'] = $headline;
					self::$export_instance->data[$id]['schema_description'] = $schema_description;
					self::$export_instance->data[$id]['article_type'] = $article_type;
					self::$export_instance->data[$id]['destination_url'] = $url_to;
					self::$export_instance->data[$id]['redirection_type'] = $header_code;
				}
				if($value->meta_key == 'rank_math_advanced_robots'){
					$rank_robots_value=$value->meta_value;
					$rank_robots=unserialize($rank_robots_value);
					$max_snippet=$rank_robots['max-snippet'];
					$max_video_preview=$rank_robots['max-video-preview'];
					$max_image_preview=$rank_robots['max-image-preview'];
					$rank_math_advanced_robots=$max_snippet.','.$max_video_preview.','.$max_image_preview;
					self::$export_instance->data[$id]['rank_math_advanced_robots'] = $rank_math_advanced_robots;
				}
				if((isset($value->meta_key) && is_array($jetFName))){
					if(in_array($value->meta_key,$jetFName)){
						$getMetaFields = $wpdb->get_results( $wpdb->prepare("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name=%s",'jet_engine_meta_boxes'),ARRAY_A);
						$getMetaFields[0]['option_value'] = isset($getMetaFields[0]) ? $getMetaFields[0]['option_value'] : '';
						$unserializedMeta = maybe_unserialize($getMetaFields[0]['option_value']);
						if(is_array($unserializedMeta)){
							$arraykeys = array_keys($unserializedMeta);

							foreach($arraykeys as $val){
								$values = explode('-',$val);
								$v = $values[1];
							}
						}



						for($i=1 ; $i<=$v ; $i++){
							$unserializedMeta['meta-'.$i] = isset($unserializedMeta['meta-'.$i])? $unserializedMeta['meta-'.$i] :'';
							$fields = $unserializedMeta['meta-'.$i];
							if(!empty($fields)){
								$jet_metatypes=array();
								$jet_reptype=array();
								foreach($fields['meta_fields'] as $jet_key => $jetValue){
									$jetFieldLabel = $jetValue['title'];
									$jetFNames = $jetValue['name'];
									$jetFieldType = $jetValue['type'];
									if($jetFieldType != 'repeater'){

										$jet_metafields[$jetFNames]=$jetFNames;

										$jet_metatypes[$jetFNames] = $jetFieldType;

									}
									else{
										$jet_metafields[$jetFNames]=$jetFNames;
										$jet_metatypes[$jetFNames] = $jetFieldType;
										$repfields=$jetValue['repeater-fields'];
										$jet_repfield=array();
										foreach($repfields as $repFieldKey => $repFieldVal){
											$jetRepFields_label = $repFieldVal['name'];
											$jetRepFields_type  = $repFieldVal['type'];

											$jet_repfield[$jetRepFields_label] = $jetRepFields_label;
											$jet_reptype[$jetRepFields_label]  = $jetRepFields_type;
										}
									}		
								}
							}

							self::$export_instance->jet_metafields = $jet_metafields;
							self::$export_instance->jet_metatypes = $jet_metatypes;
							if(!empty($jet_repfield)){
								self::$export_instance->jet_repfield = $jet_repfield;
								self::$export_instance->jet_reptype  = $jet_reptype;
							}
							else{
								$jet_repfield = '';
								$jet_reptype = '';
							}
							$this->getCustomFieldValue($id, $value, $checkRep, $allacf, $typeOftypesField, $alltype, $jet_metafields, $jet_metatypes, $jet_repfield, $jet_reptype,$parent, $typesf, $group_unset , $optionalType , self::$export_instance->allpodsfields, $metabox_fields, $module);
						}	
					}
				}
				if(is_array($jetCPTFieldsName)&& isset($value->meta_key)){
					if(in_array($value->meta_key,$jetCPTFieldsName)){
						$jetEnginefields = $wpdb->get_results("SELECT id, meta_fields FROM {$wpdb->prefix}jet_post_types WHERE slug = '$optionalType' AND status IN ('publish','built-in')", ARRAY_A);

						if(!empty($jetEnginefields)){
							$unserializedMeta = maybe_unserialize($jetEnginefields[0]['meta_fields']);
						}
						else{
							$unserializedMeta = '';
						}
						$jetTypes=array();
						$jet_rep_cpttypes=array();
						foreach($unserializedMeta as $jet_key => $jetValue){
							$jetFieldLabel = $jetValue['title'];
							$jet_cptfield_names = $jetValue['name'];
							$jetFieldType = $jetValue['type'];
							if($jetFieldType != 'repeater'){
								$jet_cptfields[$jet_cptfield_names]=$jet_cptfield_names;
								$jetTypes[$jet_cptfield_names] = $jetFieldType;
							}
							else{
								$jet_cptfields[$jet_cptfield_names]=$jet_cptfield_names;
								$jetTypes[$jet_cptfield_names] = $jetFieldType;
								$fields=$jetValue['repeater-fields'];
								foreach($fields as $repFieldKey => $repFieldVal){
									$jet_rep_cptfields_label = $repFieldVal['name'];
									$jet_rep_cptfields_type  = $repFieldVal['type'];
									$jet_rep_cptfields[$jet_rep_cptfields_label] = $jet_rep_cptfields_label;
									$jet_rep_cpttypes[$jet_rep_cptfields_label]  = $jet_rep_cptfields_type;
								}
							}

						}
						self::$export_instance->jet_cptfields = $jet_cptfields;
						self::$export_instance->jet_types = $jetTypes;
						if(isset($jet_rep_cptfields)){
							self::$export_instance->jet_rep_cptfields = $jet_rep_cptfields;
							self::$export_instance->jet_rep_cpttypes  = $jet_rep_cpttypes;
						}
						else{
							$jet_rep_cptfields = '';
							$jet_rep_cpttypes = '';
						}
						$this->getCustomFieldValue($id, $value, $checkRep, $allacf, $typeOftypesField, $alltype, $jet_cptfields, $jetTypes, $jet_rep_cptfields, $jet_rep_cpttypes,  $parent, $typesf, $group_unset , $optionalType , self::$export_instance->allpodsfields, $metabox_fields, $module);
					}
				}
				else{
					$jet_fields = $jetFieldType = $jetRepFields = $jet_rep_types = '';
					$typesf=isset($typesf)?$typesf:'';
					$jetTypes=isset($jetTypes)?$jetTypes:''; 
					$this->getCustomFieldValue($id, $value, $checkRep, $allacf, $typeOftypesField, $alltype, $jet_fields , $jetTypes, $jetRepFields, $jet_rep_types, $parent, $typesf, $group_unset , $optionalType , self::$export_instance->allpodsfields ,$metabox_fields, $module);
				}
			}
		}

		if(is_plugin_active('wordpress-seo/wp-seo.php') || is_plugin_active('wordpress-seo-premium/wp-seo-premium.php')){
			self::$export_instance->data[$id]['title'] = self::$export_instance->data[$id]['post_title'];
			if($value->meta_key == '_yoast_wpseo_focuskw'){
				self::$export_instance->data[$id]['focus_keyword'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_linkdex'){
				self::$export_instance->data[$id]['linkdex'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_meta-robots-noindex'){
				self::$export_instance->data[$id]['meta-robots-noindex'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_metadesc'){
				self::$export_instance->data[$id]['meta_desc'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_opengraph-description'){
				self::$export_instance->data[$id]['opengraph-description'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_opengraph-title'){
				self::$export_instance->data[$id]['opengraph-title'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_twitter-title'){
				self::$export_instance->data[$id]['twitter-title'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_google-plus-title'){
				self::$export_instance->data[$id]['google-plus-title'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_google-plus-description'){
				self::$export_instance->data[$id]['google-plus-description'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_google-plus-image'){
				self::$export_instance->data[$id]['google-plus-image'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_twitter-description'){
				self::$export_instance->data[$id]['twitter-description'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_twitter-image'){
				self::$export_instance->data[$id]['twitter-image'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_bctitle'){
				self::$export_instance->data[$id]['bctitle'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_canonical'){
				self::$export_instance->data[$id]['canonical'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_redirect'){
				self::$export_instance->data[$id]['redirect'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_opengraph-image'){
				self::$export_instance->data[$id]['opengraph-image'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_meta-robots-nofollow'){
				self::$export_instance->data[$id]['meta-robots-nofollow'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_meta-robots-adv'){
				self::$export_instance->data[$id]['meta-robots-adv'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_cornerstone-content'){
				self::$export_instance->data[$id]['cornerstone-content'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_focuskeywords'){
				self::$export_instance->data[$id]['focuskeywords'] = $value->meta_value;
			}					
			if($value->meta_key == '_yoast_wpseo_keywordsynonyms'){
				self::$export_instance->data[$id]['keywordsynonyms'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_schema_page_type'){
				self::$export_instance->data[$id]['schema_page_type'] = $value->meta_value;
			}
			if($value->meta_key == '_yoast_wpseo_schema_article_type'){
				self::$export_instance->data[$id]['schema_article_type'] = $value->meta_value;
			}
		}

		return self::$export_instance->data;
	}

	public function getAllPodsFields(){

		$pods_fields = [];
		if(in_array('pods/init.php', self::$export_instance->get_active_plugins())) {
			global $wpdb;
			$pods_fields_query_result = $wpdb->get_results("SELECT post_name FROM ".$wpdb->prefix."posts WHERE post_type = '_pods_field'");	
			foreach($pods_fields_query_result as $single_result){
				$pods_fields[] = $single_result->post_name;
			}
		}
		return $pods_fields;
	}

	public function getCustomFieldValue($id, $value, $checkRep, $allacf, $typeOftypesField, $alltype, $jet_fields, $jetTypes, $jetRepFields, $jet_rep_types, $parent, $typesf, $group_unset , $optionalType , $pods_type, $metabox_fields, $module){
		global $wpdb;
		$taxonomies = get_taxonomies();
		$down_file = false;

		if ($value !== null && $value->meta_key == '_thumbnail_id') {

			$attachment_file = null;
			$get_attachment = $wpdb->prepare("select guid from {$wpdb->prefix}posts where ID = %d AND post_type = %s", $value->meta_value, 'attachment');
			$attachment_file = $wpdb->get_var($get_attachment);
			self::$export_instance->data[$id][$value->meta_key] = '';
			$value->meta_key = 'featured_image';
			self::$export_instance->data[$id][$value->meta_key] = $attachment_file;
		}else if($value !== null && $value->meta_key == '_downloadable_files'){ 
			$downfiles = unserialize($value->meta_value); 
			if(!empty($downfiles)){
				foreach($downfiles as $dk => $dv){
					$down_file .= $dv['name'].','.$dv['file'].'|';
				}
				self::$export_instance->data[$id]['downloadable_files'] = rtrim($down_file,"|");
			}
		}
		elseif($value !== null && $value->meta_key == '_downloadable'){
			self::$export_instance->data[$id]['downloadable'] =  $value->meta_value;
		}
		elseif($value !== null && $value->meta_key == '_upsell_ids'){
			$upselldata = unserialize($value->meta_value);
			if(!empty($upselldata)){
				foreach($upselldata as $upselldata_value){
					$upselldata_query = $wpdb->prepare("SELECT post_title FROM {$wpdb->prefix}posts where id = %d", $upselldata_value);
					$upselldata_value=$wpdb->get_results($upselldata_query);	
					$upselldata_item[] = $upselldata_value[0]->post_title;
				}
				$upsellids = implode(',',$upselldata_item);
				self::$export_instance->data[$id]['upsell_ids'] =  $upsellids;
			}
		}
		elseif($value !== null && $value->meta_key == '_crosssell_ids'){
			$cross_selldata = unserialize($value->meta_value);
			if(!empty($cross_selldata)){
				foreach($cross_selldata as $cross_selldata_value){
					$cross_selldata_query = $wpdb->prepare("SELECT post_title FROM {$wpdb->prefix}posts where id = %d", $cross_selldata_value);
					$cross_selldata_value=$wpdb->get_results($cross_selldata_query);

					$cross_selldata_item[] = $cross_selldata_value[0]->post_title;
				}
				$cross_sellids = implode(',',$cross_selldata_item);
				self::$export_instance->data[$id]['crosssell_ids'] =  $cross_sellids;
			}
		}
		elseif($value !== null && $value->meta_key == '_wc_pb_bundle_sell_ids'){
			$bundleselldata = unserialize($value->meta_value);
			if(!empty($bundleselldata) && is_array($bundleselldata)){
				$bundsell = [];
				foreach($bundleselldata as $bundle_id){
					$bundleids = $wpdb->get_results("SELECT post_title FROM {$wpdb->prefix}posts WHERE post_type = 'product' AND ID = '$bundle_id'");
					foreach($bundleids as $bundid){
						$bundsell[] = $bundid->post_title;
					}
				}
				$value->meta_value = implode(',',$bundsell);
				self::$export_instance->data[$id]['_wc_pb_bundle_sell_ids'] =  $value->meta_value;
			}
		}
		elseif($value !== null && $value->meta_key == '_children'){
			$grpdata = unserialize($value->meta_value);
			if(!empty($grpdata)){
				$grpids = implode(',',$grpdata);
				self::$export_instance->data[$id]['grouping_product'] =  $grpids;
			}
		}elseif($value !== null && $value->meta_key == '_product_image_gallery'){
			if(strpos($value->meta_value, ',') !== false) {
				$file_data = explode(',',$value->meta_value);
				foreach($file_data as $k => $v){
					$attachment = wp_get_attachment_image_src($v);
					$attach[$k] = $attachment[0];
				}

				$gallery_data = '';
				foreach($attach as $values){
					$gallery_data .= $values.'|';
				}
				$gallery_data = rtrim($gallery_data , '|');
				self::$export_instance->data[$id]['product_image_gallery'] = $gallery_data;
			}else{
				$attachment = wp_get_attachment_image_src($value->meta_value);
				self::$export_instance->data[$id]['product_image_gallery'] = $attachment[0];
			}
		}elseif($value !== null && $value->meta_key == '_sale_price_dates_from'){
			$sales_price_date_from_value = '';
			if(!empty($value->meta_value)){
				$sales_price_date_from_value = date('Y-m-d',$value->meta_value);
			}
			self::$export_instance->data[$id]['sale_price_dates_from'] = $sales_price_date_from_value;
		}
		elseif($value !== null && $value->meta_key == '_lp_faqs'){
			$faqs=$value->meta_value;
			$unserialize_faq_value=unserialize($faqs);
			foreach($unserialize_faq_value as $faq_key=>$faq_value){
				$faqs_value .= $faq_value[0].','.$faq_value[1].'|';
			}
			self::$export_instance->data[$id][ $value->meta_key ] = rtrim($faqs_value,'|');
		}
		elseif($value !== null && $value->meta_key == '_sale_price_dates_to'){
			$sales_price_dates_value = '';
			if(!empty($value->meta_value)){
				$sales_price_dates_value = date('Y-m-d',$value->meta_value);
			}
			self::$export_instance->data[$id]['sale_price_dates_to'] = $sales_price_dates_value;
		}else {

			// Mari commented this if statement
			// if(preg_match('/group_/',$value->meta_key)){
			// 	$value->meta_key = preg_replace('/group_/','', $value->meta_key );
			// }            


			if($value !== null && isset($allacf) && array_search($value->meta_key, $allacf)){         
				$repeaterOfrepeater = false;
				$getType = $alltype[$value->meta_key];
				if(empty($getType)){
					$tempFieldname = array_search($value->meta_key, $allacf);
					$getType = $alltype[$tempFieldname];
				}

				if ($getType == 'flexible_content' || $getType == 'repeater') { 
					if(is_serialized($value->meta_value)){
						$value->meta_value = unserialize($value->meta_value);
						$count = count($value->meta_value);
					}else{
						$count = $value->meta_value;
					}

					$getRF = $checkRep[$value->meta_key];
					$repeater_data = [];

					if($getType == 'flexible_content'){
						$flexible_value = '';
						foreach($value->meta_value as $values){
							$flexible_value .= $values.'|';
						}
						$flexible_value = rtrim($flexible_value , '|');	
						self::$export_instance->data[$id][$value->meta_key] = self::$export_instance->returnMetaValueAsCustomerInput($flexible_value);
					}

					foreach ($getRF as $rep => $rep1) {
						$repType = $alltype[$rep1];

						$reval = "";
						for($z=0;$z<$count;$z++){
							$var = $value->meta_key.'_'.$z.'_'.$rep1;

							if(in_array($optionalType , $taxonomies)){
								$qry = $wpdb->get_results($wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}terms wp JOIN {$wpdb->prefix}termmeta wpm ON wpm.term_id = wp.term_id where meta_key = %s AND wp.term_id = %d", $var, $id));
							}else{
								$qry = $wpdb->get_results($wpdb->prepare("SELECT meta_value FROM {$wpdb->prefix}posts wp JOIN {$wpdb->prefix}postmeta wpm ON wpm.post_id = wp.ID where meta_key = %s AND ID=%d", $var, $id));
							}

							$meta = $qry[0]->meta_value;
							if($repType == 'image')
								$meta = $this->getAttachment($meta);
							if($repType == 'file')
								$meta =$this->getAttachment($meta);
							if($repType == 'repeater' || $repType == 'flexible_content')
								$meta = $this->getRepeaterofRepeater($value->meta_key);
							if(is_serialized($meta))
							{
								$unmeta = unserialize($meta);
								$meta = "";
								foreach ($unmeta as $unmeta1) {
									if($repType == 'image' || $repType == 'gallery')
										$meta .= $this->getAttachment($unmeta1).",";
									elseif($repType == 'taxonomy') {
										$meta .=$unmeta1.',';
									}
									elseif($repType == 'user') {
										$meta .=$unmeta1.',';
									}
									elseif($repType == 'post_object') {
										$meta .=$unmeta1.',';
									}
									elseif($repType == 'relationship') {
										$meta .=$unmeta1.',';
									}
									elseif($repType == 'page_link') {
										$meta .=$unmeta1.',';
									}
									elseif($repType == 'link') {
										$meta .=$unmeta1;
									}


									else
										$meta .= $unmeta1.",";
								}
								$meta = rtrim($meta,',');
							}
							if($meta != "")
								$reval .= $meta."|";
						}
						self::$export_instance->data[$id][$rep1] = self::$export_instance->returnMetaValueAsCustomerInput(rtrim($reval,'|'));
					}
				}
				elseif( is_serialized($value->meta_value)){

					$acfva = unserialize($value->meta_value);
					$acfdata = "";
					foreach ($acfva as $key1 => $value1) {
						if($getType == 'checkbox')
							$acfdata .= $value1.',';
						elseif($getType == 'gallery' || $getType == 'image'){
							$attach = $this->getAttachment($value1);
							$acfdata .= $attach.',';
						}
						elseif($getType == 'google_map')
						{
							$acfdata=$acfva['address'];
						}
						else{
							if(!empty($value1)) { 
								$acfdata .= $value1.',';
							}
						}

					}
					self::$export_instance->data[$id][ $value->meta_key ] = self::$export_instance->returnMetaValueAsCustomerInput(rtrim($acfdata,','));
				}
				elseif($getType == 'gallery' || $getType == 'image'|| $getType == 'file'  ){
					$attach1 = $this->getAttachment($value->meta_value);
					self::$export_instance->data[$id][ $value->meta_key ] = $attach1;
				}
				else{
					self::$export_instance->data[$id][ $value->meta_key ] = self::$export_instance->returnMetaValueAsCustomerInput($value->meta_value);
				}
			}
			elseif(is_array($jet_fields) && in_array($value->meta_key, $jet_fields) && !empty($value->meta_value)){
				$getjetType = isset($jetTypes[$value->meta_key]) ? $jetTypes[$value->meta_key] : '';
				if(empty($getjetType)){
					$tempFieldname = array_search($value->meta_key, $jet_fields);
					$getjetType = isset($jetTypes[$tempFieldname]) ? $jetTypes[$tempFieldname] : '';
				}				

				if($getjetType == 'checkbox' && is_string($value->meta_value)){
					$value->meta_value = unserialize($value->meta_value);
					$check = '';
					foreach($value->meta_value as $key => $metvalue){
						if(is_numeric($key)){
							$check .= $metvalue.',';	
							$rcheck = substr($check,0,-1);
							self::$export_instance->data[$id][ $value->meta_key ] = $rcheck;
						}
						else{
							if($metvalue == 'true'){

								$exp_value[] = $key;
							}
							if(isset($exp_value) && is_array($exp_value)){
								$value->meta_value = implode(',',$exp_value );
							}

							self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
						}

					}

				}
				elseif($getjetType == 'select'){					
					if(is_serialized($value->meta_value)){
						$value->meta_value = unserialize($value->meta_value);
						foreach($value->meta_value as $metkey => $metselectvalue){
							$select[] = $metselectvalue;
							$value->meta_value = implode(',',$select );	
							self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
						}						
					}
					else{
						self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;						
					}															
				}
				elseif($getjetType == 'date'){
					if(!empty($value->meta_value)){
						if(strpos($value->meta_value, '-') !== FALSE){
						}else{
							$value->meta_value = date('Y-m-d', $value->meta_value);
						}
					}
					self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
				}
				elseif($getjetType == 'datetime-local'){
					if(!empty($value->meta_value)){
						if(strpos($value->meta_value, '-') !== FALSE){
						}else{
							$value->meta_value = date('Y-m-d H:i', $value->meta_value);
						}
						$value->meta_value = str_replace(' ', 'T', $value->meta_value);
					}
					self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
				}
				else{	
					self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
				}								
			}
			elseif (!empty($typesf) && in_array($value->meta_key, $typesf)) {
				$typeoftype = $typeOftypesField[$value->meta_key];
				if(is_serialized($value->meta_value)){
					$typeFileds = unserialize($value->meta_value);
					$typedata = "";
					foreach ($typeFileds as $key2 => $value2) {
						if(is_array($value2)){
							foreach ($value2 as $keytypeOftypesField3 => $value3) {
								$typedata .= $value3.',';
							}
						}
						else{
							$typedata .= $value2.',';
						}
					}

					if(preg_match('/wpcf-/', $value->meta_key)){
						$value->meta_key = preg_replace('/wpcf-/', '', $value->meta_key);
					}

					self::$export_instance->data[$id][ $value->meta_key ] = substr($typedata, 0, -1);
				}
				elseif ($typeoftype == 'date') {
					if(!empty($value->meta_value))
						self::$export_instance->data[$id][ $value->meta_key ] = date('Y-m-d', $value->meta_value);
					else
						self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
				}
				else{
					self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
				}
				//TYPES Allow multiple-instances of this field
				$multi_row = '_'.$value->meta_key.'-sort-order';
				$multi_data = get_post_meta($id,$multi_row);				
				$multi_data = isset($multi_data[0]) ? $multi_data[0] : $multi_data;
				if(is_array($multi_data)){
					foreach($multi_data as $k => $mid){
						$m_data = $this->get_common_post_metadata($mid);
						if($typeoftype == 'date' && !empty($m_data['meta_value']) )
							$multi_data[$k] = date('Y-m-d H:i:s',$m_data['meta_value']);
						else
							$multi_data[$k] = $m_data['meta_value'];			                                      				       }
					self::$export_instance->data[$id][ $value->meta_key ] = implode('|',$multi_data);
					if(preg_match('/wpcf-/',$value->meta_key)){
						$value->meta_key = preg_replace('/wpcf-/','', $value->meta_key );
						self::$export_instance->data[$id][ $value->meta_key ] = implode('|',$multi_data);
					}
					if(preg_match('/group_/',$value->meta_key)){
						$getType = $alltype[$value->meta_key];
						if($value->meta_key == 'group_gallery' || $value->meta_key == 'group_image'|| $value->meta_key == 'file'  ){
							$groupattach = $this->getAttachment($value->meta_value);
							self::$export_instance->data[$id][ $value->meta_key ] = $groupattach;
						}

						else{
							$value->meta_key = preg_replace('/group_/','', $value->meta_key );
							self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
						}
					}
				}
				//TYPES Allow multiple-instances of this field
			// }elseif(!empty($group_unset) && in_array($value->meta_key, $group_unset) && is_serialized($value->meta_value)) {
			}elseif(!empty($group_unset) && is_array($value) && isset($value['meta_key']) && in_array($value['meta_key'], $group_unset) && is_serialized($value['meta_value'])) {

				$unser = unserialize($value->meta_value);
				$data = "";
				foreach ($unser as $key4 => $value4) 
					$data .= $value4.',';
				self::$export_instance->data[$id][ $value->meta_key ] = substr($data, 0, -1);
			}
			elseif(!empty($pods_type) && in_array($value->meta_key , $pods_type)){	
				if(!isset(self::$export_instance->data[$id][$value->meta_key])){
					if(in_array($optionalType , $taxonomies)){
						$pods_file_data = get_term_meta($id,$value->meta_key);
					}else{
						$pods_file_data = get_post_meta($id,$value->meta_key);
					}

					$pods_value = '';
					foreach($pods_file_data as $pods_file_value){
						if(!empty($pods_file_value)){
							if(is_array($pods_file_value)){
								$pods_file_value['post_type']=isset($pods_file_value['post_type'])?$pods_file_value['post_type']:'';
								$posts_type=$pods_file_value['post_type'];
								if($posts_type=='attachment'){
									$pods_value .= $pods_file_value['guid'] . ',';
								}
								elseif($posts_type!=='attachment'){
									$pods_file_value['guid']=isset($pods_file_value['guid'])?$pods_file_value['guid']:'';
									$p_guid=$pods_file_value['guid'];
									$pod_tit =  $wpdb->get_results("SELECT post_title FROM {$wpdb->prefix}posts where guid='$p_guid'");	
									if(!empty($pod_tit)){
										foreach($pod_tit as $pods_title){
											$pods_title_value=$pods_title->post_title;
											$pods_value .= $pods_title_value . ',';
										}
									}
									else{
										$podstaxval = $pods_file_value['name'];
										$pods_value .= $podstaxval. ',';
									}
								}
							}else{
								$pods_value .= $pods_file_value . ',';
							}
						}	
					}
					self::$export_instance->data[$id][$value->meta_key] = rtrim($pods_value , ',');		
				}
			}

			elseif(isset($metabox_fields) && is_array($metabox_fields) || (is_array($metabox_fields) && array_key_exists($value->meta_key, $metabox_fields))){
 				foreach($metabox_fields as $meta_val){
					if($meta_val['type'] == 'taxonomy'){
						$meta_tax = $meta_val['taxonomy'];

						$meta_key = $meta_val['id'];
						foreach($meta_tax as $meta_val){
							$get_metabox_titles[] = $wpdb->get_results("SELECT t.name FROM {$wpdb->prefix}terms t Inner join {$wpdb->prefix}term_taxonomy tax ON t.term_id=tax.term_id INNER JOIN {$wpdb->prefix}term_relationships tr ON tr.term_taxonomy_id=tax.term_taxonomy_id  WHERE tr.object_id =$id AND tax.taxonomy ='$meta_val'",ARRAY_A);
						}


						$titles =array();
						if(is_array($get_metabox_titles)){
							foreach($get_metabox_titles as $title => $value){
								foreach($value as $valu => $val){
									$titles[] = $val['name'];
								}
							}
							$tax_val =implode('|',$titles);

						self::$export_instance->data[$id][$meta_key] = $tax_val;
						}
					}	
				}

				$get_metabox_fieldtype = $metabox_fields[$value->meta_key]['type'];

				if($get_metabox_fieldtype == 'select' || $get_metabox_fieldtype == 'select_advanced' || $get_metabox_fieldtype == 'checkbox_list' || $get_metabox_fieldtype == 'text_list' || $get_metabox_fieldtype == 'file_advanced'){

					$metabox_metakey = $value->meta_key;
					if($module == 'Users'){
						$get_metabox_values = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = '$metabox_metakey' AND user_id = $id ", ARRAY_A);
					}else if($module == 'Categories' || $module == 'Taxonomies' || $module == 'Tags'){
						$get_metabox_values = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}termmeta WHERE meta_key = '$metabox_metakey' AND term_id = $id ", ARRAY_A);
					}else{	
						$get_metabox_values = $wpdb->get_results("SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = '$metabox_metakey' AND post_id = $id ", ARRAY_A);
					}

					$metabox_values = array_column($get_metabox_values, 'meta_value');
					if($get_metabox_fieldtype == 'file_advanced' || $get_metabox_fieldtype == 'image_advanced'){
						$get_metabox_file_url = [];
						foreach($metabox_values as $metavalue){
							$get_metabox_file_url[] = $wpdb->get_var("SELECT guid FROM {$wpdb->prefix}posts WHERE ID = $metavalue AND post_type = 'attachment' ");
						}

						$metabox_file_value = implode(',', $get_metabox_file_url);
						self::$export_instance->data[$id][ $value->meta_key ] = $metabox_file_value;
					}
					else{
						$metabox_value = implode(',', $metabox_values);
						self::$export_instance->data[$id][ $value->meta_key ] = $metabox_value;
					}
				}

				elseif($get_metabox_fieldtype == 'fieldset_text'){
					$fieldset_values = unserialize($value->meta_value);
					$fieldset_value = implode(',', array_values($fieldset_values));
					self::$export_instance->data[$id][ $value->meta_key ] = $fieldset_value;
				}

				elseif($get_metabox_fieldtype == 'post' || $get_metabox_fieldtype == 'taxonomy' || $get_metabox_fieldtype == 'user'){
					if($get_metabox_fieldtype == 'post'){
						$get_metabox_titles = $wpdb->get_var("SELECT post_title FROM {$wpdb->prefix}posts WHERE ID = $value->meta_value ");
					}
					elseif($get_metabox_fieldtype == 'taxonomy' || $get_metabox_fieldtype == 'taxonomy_advanced'){
						$get_metabox_titles = $wpdb->get_var("SELECT name FROM {$wpdb->prefix}terms WHERE term_id = $value->meta_value ");
					}
					elseif($get_metabox_fieldtype == 'user'){
						$get_metabox_titles = $wpdb->get_var("SELECT user_login FROM {$wpdb->prefix}users WHERE ID = $value->meta_value ");
					}
					self::$export_instance->data[$id][ $value->meta_key ] = $get_metabox_titles;
				}

				elseif($get_metabox_fieldtype == 'image' || $get_metabox_fieldtype == 'file'){
					$upload_values = $value->meta_value;
					$upload_value = $wpdb->get_var("SELECT guid FROM {$wpdb->prefix}posts WHERE ID = $upload_values AND post_type = 'attachment' ");
					self::$export_instance->data[$id][ $value->meta_key ] = $upload_value;
				}
				else{
					self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
				}
			}

			else{
				self::$export_instance->data[$id][ $value->meta_key ] = $value->meta_value;
			}


		}
	}

	public function getRepeater($parent)
	{
		global $wpdb;

		$get_fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts where post_parent = %d", $parent), ARRAY_A);
		$i = 0;
		foreach ($get_fields as $key => $value) {
			$array[$i] = $value['post_excerpt'];
			$i++;
		}

		return $array;	
	}

	public function getRepeaterofRepeater($parent)
	{
		global $wpdb;
		$get_fields = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts where post_parent = %d", $parent), ARRAY_A);
		$test = $get_fields[0]->ID ;
		$get_fieldss = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts where post_parent = %d", $test), ARRAY_A);
		$i = 0;
		foreach ($get_fieldss as $key => $value) {
			$array[$i] = $value['post_excerpt'];			
			$i++;
		}

		return $array;	
	}



	/**
	 * Fetch all Categories
	 * @param $mode
	 * @param $module
	 * @param $optionalType
	 * @return array
	 */
	public function FetchCategories($module,$optionalType,$mode = null) {
		$headers = self::$export_instance->generateHeaders($module, $optionalType);
		global $wpdb;
		// $get_all_terms = get_categories('hide_empty=0');
		// self::$export_instance->totalRowCount = count($get_all_terms);	
		$query = "SELECT * FROM {$wpdb->prefix}terms t INNER JOIN {$wpdb->prefix}term_taxonomy tax 
				ON  `tax`.term_id = `t`.term_id WHERE `tax`.taxonomy =  'category'";         
		$get_all_taxonomies =  $wpdb->get_results($query);
		self::$export_instance->totalRowCount = count($get_all_taxonomies);	
		$offset = self::$export_instance->offset;
		$limit = self::$export_instance->limit;	
			
		
		$query="SELECT term_id FROM {$wpdb->prefix}term_taxonomy where taxonomy='category'";
		
		$offset_limit = " order by term_id asc limit $offset, $limit";
		$query_with_offset_limit = $query.$offset_limit;
			 
		$result= $wpdb->get_col($query_with_offset_limit);
		$query1=array();
		foreach($result as $res=>$re){
			 $query1[]=$wpdb->get_results(" SELECT t.name, t.slug, tx.description, tx.parent, t.term_id FROM {$wpdb->prefix}terms as t join {$wpdb->prefix}term_taxonomy as tx on t.term_id = tx.term_id where t.term_id = '$re'");
		}
		$new=array();
		foreach($query1 as $qkey => $qval){		
			foreach($qval as $qid){
				$new[]=$qid;
			}
			
		}	
		if(!empty($new)) {
			foreach( $new as $termKey => $termValue ) {
				$termID = $termValue->term_id;
				$termName = $termValue->cat_name;
				$termSlug = $termValue->slug;
				$termDesc = $termValue->category_description;
				$termParent = $termValue->parent;
				if($termParent == 0) {
					self::$export_instance->data[$termID]['name'] = $termName;
				} else {
					$termParentName = get_cat_name( $termParent );
					self::$export_instance->data[$termID]['name'] = $termParentName . '|' . $termName;
				}
				self::$export_instance->data[$termID]['slug'] = $termSlug;
				self::$export_instance->data[$termID]['description'] = $termDesc;
				self::$export_instance->data[$termID]['parent'] = $termParent;
				self::$export_instance->data[$termID]['TERMID'] = $termID;

				self::$export_instance->getWPMLData($termID,$optionalType,$module);
				if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
					self::$export_instance->getPolylangData($termID,$optionalType,$module);
				}
				$this->getPostsMetaDataBasedOnRecordId ($termID, $module, $optionalType);

				if(in_array('wordpress-seo/wp-seo.php', self::$export_instance->get_active_plugins())) {
					$seo_yoast_taxonomies = get_option( 'wpseo_taxonomy_meta' );
					if ( isset( $seo_yoast_taxonomies['category'] ) ) {

						self::$export_instance->data[ $termID ]['title'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_title'];
						self::$export_instance->data[ $termID ]['meta_desc'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_desc'];
						self::$export_instance->data[ $termID ]['canonical'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_canonical'];
						self::$export_instance->data[ $termID ]['bctitle'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_bctitle'];
						self::$export_instance->data[ $termID ]['meta-robots-noindex'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_noindex'];
						self::$export_instance->data[ $termID ]['sitemap-include'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_sitemap_include'];
						self::$export_instance->data[ $termID ]['opengraph-title'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_opengraph-title'];
						self::$export_instance->data[ $termID ]['opengraph-description'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_opengraph-description'];
						self::$export_instance->data[ $termID ]['opengraph-image'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_opengraph-image'];
						self::$export_instance->data[ $termID ]['twitter-title'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_twitter-title'];
						self::$export_instance->data[ $termID ]['twitter-description'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_twitter-description'];
						self::$export_instance->data[ $termID ]['twitter-image'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_twitter-image'];
						self::$export_instance->data[ $termID ]['focus_keyword'] = $seo_yoast_taxonomies['category'][$termID]['wpseo_focuskw'];

					}
				}
			}
		}
		$result = self::$export_instance->finalDataToExport(self::$export_instance->data, $module);

		if($mode == null){
			self::$export_instance->proceedExport($result);
		}else{
			return $result;
		}
	}


	public function get_common_post_metadata($meta_id){
		global $wpdb;
		$mdata = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->postmeta WHERE meta_id = %d", $meta_id) ,ARRAY_A);
		return $mdata[0];
	}

	public function getAttachment($id)
	{
		global $wpdb;
		$get_attachment = $wpdb->prepare("select guid from $wpdb->posts where ID = %d AND post_type = %s", $id, 'attachment');
		$attachment = $wpdb->get_results($get_attachment);
		$attachment_file = $attachment[0]->guid;
		return $attachment_file;

	}

	/**
	 * Fetch all Tags
	 * @param $mode
	 * @param $module
	 * @param $optionalType
	 * @return array
	 */
	public function FetchTags($module,$optionalType,$mode = null) {
		global $wpdb;
		self::$export_instance->generateHeaders($module, $optionalType);
		// $get_all_terms = get_tags('hide_empty=0');
	
		// self::$export_instance->totalRowCount = count($get_all_terms);
		$query = "SELECT * FROM {$wpdb->prefix}terms t INNER JOIN {$wpdb->prefix}term_taxonomy tax 
				ON  `tax`.term_id = `t`.term_id WHERE `tax`.taxonomy =  'post_tag'";         
			$get_all_taxonomies =  $wpdb->get_results($query);
		self::$export_instance->totalRowCount = count($get_all_taxonomies);
		$offset = self::$export_instance->offset;
		$limit = self::$export_instance->limit;	
		
		$query="SELECT term_id FROM {$wpdb->prefix}term_taxonomy where taxonomy='post_tag'";
			
		$offset_limit = " order by term_id asc limit $offset, $limit";
		$query_with_offset_limit = $query.$offset_limit;
			 
		$result= $wpdb->get_col($query_with_offset_limit);
		$query1=array();
		foreach($result as $res=>$id){
				 $query1[]=$wpdb->get_results(" SELECT t.name, t.slug, tx.description, tx.parent, t.term_id FROM {$wpdb->prefix}terms as t join {$wpdb->prefix}term_taxonomy as tx on t.term_id = tx.term_id where t.term_id = '$id'");
		}
		$new=array();
		foreach($query1 as $qkey => $qval){		
			foreach($qval as $qid){
				$new[]=$qid;
			}
			
		}
		if(!empty($new)) {
			foreach( $new as $termKey => $termValue ) {
				$termID = $termValue->term_id;
				$termName = $termValue->name;
				$termSlug = $termValue->slug;
				$termDesc = $termValue->description;
				self::$export_instance->data[$termID]['name'] = $termName;
				self::$export_instance->data[$termID]['slug'] = $termSlug;
				self::$export_instance->data[$termID]['description'] = $termDesc;

				$this->getPostsMetaDataBasedOnRecordId ($termID, $module, $optionalType);
				self::$export_instance->getWPMLData($termID,$optionalType,$module);
				if(is_plugin_active('polylang/polylang.php') || is_plugin_active('polylang-pro/polylang.php') || is_plugin_active('polylang-wc/polylang-wc.php')){
					self::$export_instance->getPolylangData($termID,$optionalType,$module);
				}
				if(in_array('wordpress-seo/wp-seo.php', self::$export_instance->get_active_plugins())) {
					$seo_yoast_taxonomies = get_option( 'wpseo_taxonomy_meta' );
					if ( isset( $seo_yoast_taxonomies['post_tag'] ) ) {

						self::$export_instance->data[ $termID ]['title'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_title'];
						self::$export_instance->data[ $termID ]['meta_desc'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_desc'];
						self::$export_instance->data[ $termID ]['canonical'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_canonical'];
						self::$export_instance->data[ $termID ]['bctitle'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_bctitle'];
						self::$export_instance->data[ $termID ]['meta-robots-noindex'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_noindex'];
						self::$export_instance->data[ $termID ]['sitemap-include'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_sitemap_include'];
						self::$export_instance->data[ $termID ]['opengraph-title'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_opengraph-title'];
						self::$export_instance->data[ $termID ]['opengraph-description'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_opengraph-description'];
						self::$export_instance->data[ $termID ]['opengraph-image'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_opengraph-image'];
						self::$export_instance->data[ $termID ]['twitter-title'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_twitter-title'];
						self::$export_instance->data[ $termID ]['twitter-description'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_twitter-description'];
						self::$export_instance->data[ $termID ]['twitter-image'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_twitter-image'];
						self::$export_instance->data[ $termID ]['focus_keyword'] = $seo_yoast_taxonomies['post_tag'][$termID]['wpseo_focuskw'];

					}
				}
			}
		}

		$result = self::$export_instance->finalDataToExport(self::$export_instance->data, $module);
		if($mode == null)
			self::$export_instance->proceedExport($result);
		else
			return $result;
	}
}

global $post_export_class;
$post_export_class = new PostExport();
