<?php
if ( !function_exists('add_action') ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

class SCConn_CacheObjectWrapper{
	
	public function __construct($d) {
		$this->data = $d; 	 
	}
	
	public function get_id(){
		if($this->data){
			if(isset($this->data["id"]))
				return intval($this->data["id"]);	
		}
		return null;
	}
};

class SCSCConn_WCProductWrapper extends WC_Product{
	public function __construct( $product = 0 ) {
		
	}
	
	public function scconn_set_date_prop( $prop, $value ) {
		$this->WRAPPED->set_date_prop($prop, $value);
	}
	
	public function scconn_set_prop( $prop, $value ) {
		$this->WRAPPED->set_prop($prop, $value);
	}
};

class SCConn_WCOrderWrapper extends WC_Order{
	public function __construct( $product = 0 ) {
		
	}
	
	public function scconn_set_date_prop( $prop, $value ) {
		$this->WRAPPED->set_date_prop($prop, $value);
	}
	
	public function scconn_set_prop( $prop, $value ) {
		$this->WRAPPED->set_prop($prop, $value);
	}
};  

if(class_exists("WC_Subscription")){
	class SCConn_WCSubscriptionWrapper extends WC_Subscription{
		public function __construct( $product = 0 ) {
			
		}
		
		public function scconn_set_date_prop( $prop, $value ) {
			$this->WRAPPED->set_date_prop($prop, $value);
		}
		
		public function scconn_set_prop( $prop, $value ) {
			$this->WRAPPED->set_prop($prop, $value);
		}
	}; 
}

function scconn_set_extra_fileds($params, $object, $wrapper ){
	
	$write_db_props = array(
		"date_created"      => "post_date",
		"date_created_gmt"  => "post_date_gmt",
		"date_modified"     => "post_modified",
		"date_modified_gmt" => "post_modified_gmt",
		"date_paid"          => "",
		"date_paid_gmt"      => "",
		"date_completed"     => "",
		"date_completed_gmt" => ""
	);
	
	$do_object_save = false;
	$db_upd = array();
	if(!empty($params)){
		foreach($write_db_props as $prop => $db_prop){
			if(isset($params[$prop])){
				if(method_exists($object,"set_{$prop}")){
					$object->{"set_{$prop}"}($params[$prop]);
				}else{
					if($db_prop)
						$db_upd["$db_prop = %s"] = $params[$prop];
					
					$wrapper->WRAPPED = $object;
					if(strpos($prop,"date_") === 0)
						$wrapper->scconn_set_date_prop($prop,$params[$prop]);
					else
						$wrapper->scconn_set_prop($prop,$params[$prop]);
					
				}
				$do_object_save	= true;				
			}
		}
	}
	
	if($do_object_save)
		$object->save();
	
	if(!empty($db_upd)){
		global $wpdb;
		try{
			$vals = array_values($db_upd);
			$vals[] = $object->get_id();
			$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}posts SET " . implode(",", array_keys($db_upd)) . " WHERE id = %d",$vals));
		}catch(Throwable $ex){
			//
		}
	}
	
	return $object;
}

function scconn_batch_repack_to_assoc($request_items, $response){
	foreach($request_items as $type => $items){
		if($type == "update" || $type == "create" || $type == "delete"){
			$i = 0;
			if(isset($response[$type])){
				$assoc = array();
				foreach($request_items[$type] as $key => $request_items_item){
					if(isset($response[$type][$i])){
						$assoc[$key] = $response[$type][$i];
					}
					$i++;
				}
				if(!empty($assoc)){
					$response[$type] = $assoc;
				}
			}
		}
	}
	return $response;
}
	
class SCConn_WC_REST_Products_Controller extends WC_REST_Products_V2_Controller{
	public $parent_namespace = null;
	public function __construct() {
		parent::__construct();
		
		$this->parent_namespace = $this->namespace;
		$this->cacheIDs = array();
		$this->namespace = "sc/v1";
	}
	
	protected function save_object( $request, $creating = false ) {
		global $scconn_sellingcommander;
		
		$params = $request->get_body_params();
		$object = parent::save_object($request,$creating);
		global $scconn_wcproduct_wrapper;
		if(!isset($scconn_wcproduct_wrapper)){
			$scconn_wcproduct_wrapper = new SCSCConn_WCProductWrapper;
		}
		
		$object = scconn_set_extra_fileds($params, $object, $scconn_wcproduct_wrapper );
		
		$scconn_sellingcommander->deleteItemCache($object->get_id());
		clean_post_cache($object->get_id());
		
		return $object;
	}
	
	public function get_collection_params() {
		$params = parent::get_collection_params();
		if(isset($params)){
			if(isset($params["per_page"])){
				if(isset($params["per_page"]["maximum"])){
 					$params["per_page"]["maximum"] = 9999;
				}
			}	
		}
		return $params;
	}
	
	protected function get_objects( $query_args ) {
		global $scconn_sellingcommander, $scconn_read_product_cache;
		if(!$scconn_sellingcommander->no_caching){
			$scconn_read_product_cache = true;
		}
		
		$objects = parent::get_objects( $query_args );
		
		$scconn_read_product_cache = false;
		
		return $objects;
	}
	
	protected function get_object( $id ) {
		global $scconn_sellingcommander, $scconn_read_product_cache;
		if($scconn_read_product_cache){
			$pID = $id;
			if(is_object($pID)){
				$pID = $pID->ID;
			}
			$cache_data = $scconn_sellingcommander->getCachedItem($pID);
			if($cache_data){
				return new SCConn_CacheObjectWrapper($cache_data);
			}else{
				$this->cacheIDs[$pID] = 1;
			}
		}
		return parent::get_object( $id );
	}
	
	public function prepare_object_for_response( $object, $request ) {
		if(is_a($object,"SCConn_CacheObjectWrapper")){
			return $object->data;
		}
		return parent::prepare_object_for_response( $object, $request );
	}
	
	public function prepare_response_for_collection( $response ) {
		if(is_a($response,"SCConn_CacheObjectWrapper")){
			return $response;
		}
		return parent::prepare_response_for_collection( $response );
	}
	
	public function parent_get_items( $request ) {
		return parent::get_items( $request );
	}
	
	public function get_items( $request ) {
		global $scconn_sellingcommander;
		
		if(scconn_read_sanitized_request_parm("modified_after",false)){
			$recently_deleted_products = get_option("scconn_deleted_products_log",array());
			$deletedp = array();
			if(!empty($recently_deleted_products)){
				$upd_rdp = false;
				foreach($recently_deleted_products as $product_id => $delete_time){
					if($delete_time + 86400 > time()){
						$deletedp[$product_id] = $delete_time;
					}else{
						$upd_rdp = true;
					}
				}
				if($upd_rdp){
					update_option("scconn_deleted_products_log",$deletedp, true);
				}
			}
			
			if(!empty($deletedp)){
				global $wpdb;
				$prevent = $wpdb->get_col($wpdb->prepare("SELECT ID from {$wpdb->prefix}posts WHERE ID IN (" . implode(",", array_keys($deletedp)) . ") AND post_type = %s",'product'));
				if(!empty($prevent)){
					foreach($prevent as $pid){
						if(isset($deletedp[$pid])){
							unset($deletedp[$pid]);
						}
					}
				}
				header("Access-Control-Expose-Headers: X-WP-Total, X-WP-TotalPages, X-SC-Deleted-P, Link",true);
				$hval = implode(",",array_slice(array_keys($deletedp),-500));
				header("X-SC-Deleted-P: " . $hval);//LAST 500 PREVENT ENTITY TOO LARGE
			}
		}
		
		$response = parent::get_items( $request );
		
		if(!$scconn_sellingcommander->no_caching){
			if(!empty($this->cacheIDs)){
				foreach($response->data as $item){
					if(isset($this->cacheIDs[$item["id"]])){
						if(isset($item["product_id"]))
							continue;
						
						$scconn_sellingcommander->cacheItem("product",$item);
					}
				}
			}
		}
		return $response;
	}
	
	public function parent_batch_items( $request ) {
		return parent::batch_items( $request );
	}
	
	public function batch_items( $request ) {
		$response = parent::batch_items( $request );
		global $scconn_sellingcommander;
		if(!$scconn_sellingcommander->no_caching){
			if(isset($response["update"])){
				foreach($response["update"] as $item){
					$scconn_sellingcommander->cacheItem("product",$item);
				}
			}
			
			if(isset($response["create"])){
				foreach($response["create"] as $item){
					$scconn_sellingcommander->cacheItem("product",$item);
				}
			}
			
			if(isset($response["delete"])){
				foreach($response["delete"] as $item){
					$scconn_sellingcommander->deleteItemCache(intval($item["id"]));
				}
			}
		}
		return scconn_batch_repack_to_assoc($request->get_params(),$response);
	}
};

class SCConn_WC_REST_Product_Variations_Controller extends WC_REST_Product_Variations_Controller{
	public $parent_namespace = null;
	
	public function __construct() {
		parent::__construct();
		$this->parent_namespace = $this->namespace;
		$this->namespace = "sc/v1";
	}
	
	public function get_collection_params() {
		$params = parent::get_collection_params();
		if(isset($params)){
			if(isset($params["per_page"])){
				if(isset($params["per_page"]["maximum"])){
 					$params["per_page"]["maximum"] = 9999;
				}
			}	
		}
		return $params;
	}
};


class SCConn_WC_REST_Customers_Controller extends WC_REST_Customers_Controller{
	public $parent_namespace = null;
	public function __construct() {
		//parent::__construct();
		$this->parent_namespace = $this->namespace;
		$this->namespace = "sc/v1";
	}
	
	public function get_collection_params() {
		$params = parent::get_collection_params();
		if(isset($params)){
			if(isset($params["per_page"])){
				if(isset($params["per_page"]["maximum"])){
 					$params["per_page"]["maximum"] = 9999;
				}
			}	
		}
		return $params;
	}
};

class SCConn_WC_REST_Orders_Controller extends WC_REST_Orders_Controller{
	public $parent_namespace = null;
	public function __construct() {
		//parent::__construct();
		$this->parent_namespace = $this->namespace;
		$this->namespace = "sc/v1";
	}
	
	protected function save_object( $request, $creating = false ) {
		global $scconn_sellingcommander;
		
		$params = $request->get_body_params();
		$object = parent::save_object($request,$creating);
		global $scconn_wcorder_wrapper;
		if(!isset($scconn_wcorder_wrapper)){
			$scconn_wcorder_wrapper = new SCConn_WCOrderWrapper;
		}
		
		$object = scconn_set_extra_fileds($params, $object, $scconn_wcorder_wrapper );
		
		clean_post_cache($object->get_id());
		
		return $object;
	}
	
	public function batch_items( $request ) {
	    global $scconn_controllers;
		
		$subscription_items = null;
		$subscription_batch_resp = null;
		$items = $request->get_params();
		
		$has_order_updates = false;
		
		
		if(isset($scconn_controllers["subscriptions"])){
			
			foreach($items as $type => $tasks){
				if(($type == "update" || $type == "create") && !empty($tasks)){
					$keys = array_keys($tasks);
					foreach($keys as $index => $key){
						
						if($type == "create"){
							if(isset($tasks[$key]["id"])){
								unset($tasks[$key]["id"]);
							}
						}
						
						if(isset($tasks[$key]["post_type"])){
							if($tasks[$key]["post_type"] == "shop_subscription"){
								if(!$subscription_items)
									$subscription_items = array();
								if(!isset($subscription_items[$type])){
									$subscription_items[$type] = array();
								}
								
								$subscription_items[$type][$key] = $tasks[$key];
								unset($items[$type][$key]);
								if(empty($tasks)){
									unset($items[$type]);
									break;
								}
							}else{
								$has_order_updates = true;
							}
						}
					}
				}else if($type == "delete"){
					$has_order_updates = true;
				}
			}
			
			try{
				if($subscription_items){
					
					$subscription_req = new WP_REST_Request( 'POST',  "/sc/v1/subscriptions/batch");
					$subscription_req->set_query_params(array("context" => "edit"));
					$subscription_req->set_body_params($subscription_items);
					
					$request = new WP_REST_Request( 'POST',  "/sc/v1/orders/batch");
					$request->set_query_params(array("context" => "edit"));
					$request->set_body_params($items);
					
					$subscription_batch_resp = $scconn_controllers["subscriptions"]->batch_items($subscription_req);
					$subscription_batch_resp = scconn_batch_repack_to_assoc($subscription_items, $subscription_batch_resp);
					
					
				}
			}catch(Throwable $ex){
				
			}
		}else
			$has_order_updates = true;
		
		$batch_resp = array();
		if($has_order_updates){
			$batch_resp = parent::batch_items( $request );
			$batch_resp = scconn_batch_repack_to_assoc($items, $batch_resp);
		}
		
		if(isset($items["create"])){
			foreach($items["create"] as $key => $order_data){
				if(isset($order_data["subscription_id"])){
					$subscription_uid = $order_data["subscription_id"];
					if($subscription_uid){
						
						$subscription_id = $subscription_uid;
						if(isset($subscription_batch_resp["create"])){
							if(isset($subscription_batch_resp["create"][$subscription_uid])){
								$subscription_id = $subscription_batch_resp["create"][$subscription_uid]["id"];
							}
						}
						
						if(intval($subscription_id)){
							$batch_resp["create"][$key]["subscription_id"] = intval($subscription_id);
							$order_created = $batch_resp["create"][$key];
							
							if(!wp_get_post_parent_id($subscription_id)){
								$upd = wp_update_post(array(
									"ID"          => $subscription_id,
									"post_parent" => $order_created["id"]
								));
								
								if($upd && !is_wp_error($upd)){
									if(isset($subscription_batch_resp["create"])){
										if(isset($subscription_batch_resp["create"][$subscription_uid])){
											$subscription_batch_resp["create"][$subscription_uid]["parent_id"] = $order_created["id"];
										}
									}
									if(isset($subscription_batch_resp["update"])){
										if(isset($subscription_batch_resp["update"][$subscription_uid])){
											$subscription_batch_resp["update"][$subscription_uid]["parent_id"] = $order_created["id"];
										}
									}
								}
							}else{
								update_post_meta($order_created["id"], "_subscription_renewal", $subscription_id);
							}
						}
					}
				}
			}
		}
		
		if(isset($items["update"])){
			foreach($items["update"] as $key => $order_data){
				if(isset($order_data["subscription_id"])){
					if($subscription_items){
						if(isset($subscription_items["update"][$order_data["subscription_id"]])){
							$order_data["subscription_id"] = $subscription_batch_resp["update"][$order_data["subscription_id"]]["id"];
						}	
					}
					$batch_resp["create"][$key]["subscription_id"] = $order_data["subscription_id"];
				}
			}
		}
		
		if($subscription_batch_resp){
			foreach($subscription_batch_resp as $type => $updated_items){
				if($type == "update" || $type == "create" || $type == "delete"){
					foreach($subscription_batch_resp[$type] as $index => $updated_item){
						$subscription_batch_resp[$type][$index]["post_type"] = 'shop_subscription';
					}
					if(!isset($batch_resp[$type])){
						$batch_resp[$type] = array();
					}
					foreach($batch_resp[$type] as $key => $data){
						$subscription_batch_resp[$type][$key] = $data;
					}
					$batch_resp[$type] = $subscription_batch_resp[$type];
				}
			}
		}
		
		foreach($batch_resp as $type => $items){
			if($type == "update" || $type == "create" || $type == "delete"){
				foreach($items as $index => $item){
					if(!isset($item["post_type"])){
						$batch_resp[$type][$index]["post_type"] = "shop_order";
					}
				}
			}
		}
		
		return $batch_resp;
	}
	
	public function get_collection_params() {
		$params = parent::get_collection_params();
		if(isset($params)){
			if(isset($params["per_page"])){
				if(isset($params["per_page"]["maximum"])){
 					$params["per_page"]["maximum"] = 9999;
				}
			}	
		}
		return $params;
	}
};


if(class_exists("WC_REST_Subscriptions_Controller")){
	class SCConn_WC_REST_Subscriptions_Controller extends WC_REST_Subscriptions_Controller{
		public $parent_namespace = null;
		public function __construct() {
			//parent::__construct();
			$this->parent_namespace = $this->namespace;
			$this->namespace = "sc/v1";
		}
		
		protected function save_object( $request, $creating = false ) {
			global $scconn_sellingcommander;
			
			$params = $request->get_body_params();
			$object = parent::save_object($request,$creating);
			global $scconn_wcsubscription_wrapper;
			if(!isset($scconn_wcsubscription_wrapper)){
				$scconn_wcsubscription_wrapper = new SCConn_WCSubscriptionWrapper;
			}
			
			$object = scconn_set_extra_fileds($params, $object, $scconn_wcsubscription_wrapper );
			
			clean_post_cache($object->get_id());
			
			return $object;
		}
		
		public function get_collection_params() {
			$params = parent::get_collection_params();
			if(isset($params)){
				if(isset($params["per_page"])){
					if(isset($params["per_page"]["maximum"])){
						$params["per_page"]["maximum"] = 9999;
					}
				}	
			}
			return $params;
		}
	};
}


class SCConn_WC_REST_Attachments_Controller extends WP_REST_Attachments_Controller{
	public $parent_namespace = null;
	public function __construct($post_type) {
		parent::__construct($post_type);
		$this->parent_namespace = $this->namespace;
		$this->namespace = "sc/v1";
	}
	
	public function get_collection_params() {
		$params = parent::get_collection_params();
		if(isset($params)){
			if(isset($params["per_page"])){
				if(isset($params["per_page"]["maximum"])){
 					$params["per_page"]["maximum"] = 9999;
				}
			}	
		}
		return $params;
	}
};

global $scconn_controllers;
if(!isset($scconn_controllers)){
	$scconn_controllers = array();
	
	try{
		$scconn_controllers["attachments"] = (new SCConn_WC_REST_Attachments_Controller("attachment"));  
	}catch(Throwable $ex){}

	try{
		$scconn_controllers["products"]    = (new SCConn_WC_REST_Products_Controller());
	}catch(Throwable $ex){}
	
	try{
		$scconn_controllers["variations"]  = (new SCConn_WC_REST_Product_Variations_Controller());
	}catch(Throwable $ex){}
	
	try{
		$scconn_controllers["customers"]   = (new SCConn_WC_REST_Customers_Controller());
	}catch(Throwable $ex){}
	
	try{
		$scconn_controllers["orders"]      = (new SCConn_WC_REST_Orders_Controller());
	}catch(Throwable $ex){}

	if(class_exists('SCConn_WC_REST_Subscriptions_Controller')){
		try{
			$scconn_controllers["subscriptions"] = (new SCConn_WC_REST_Subscriptions_Controller());  
		}catch(Throwable $ex){}
	}
	
	foreach($scconn_controllers as $cclass_name => $controller){
		try{
			$controller->register_routes();  
		}catch(Throwable $ex){}
	}
}