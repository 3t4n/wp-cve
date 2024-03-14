<?php  


	if ( !function_exists('add_action') ) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
	}

	if ( ! class_exists( 'SCConn_SellingCommander' ) ) {

	   if(!function_exists("scconn_read_sanitized_server_parm")){
		   function scconn_read_sanitized_server_parm($parm_name, $default = NULL){
				if(isset($_SERVER[$parm_name])){
					if(is_array($_SERVER[$parm_name])){
						$arr = array();
						foreach($_SERVER[$parm_name] as $index => $value){
							$arr[$index] = sanitize_text_field($value);
						}
						return $arr;
					}else
						return sanitize_text_field($_SERVER[$parm_name]);
				}
				return $default;
		   }
	   }

	   if(!function_exists("scconn_read_sanitized_post_parm")){
		   function scconn_read_sanitized_post_parm($parm_name, $default = NULL){
				if(isset($_POST[$parm_name])){
					if(is_array($_POST[$parm_name])){
						$arr = array();
						foreach($_POST[$parm_name] as $index => $value){
							$arr[$index] = sanitize_text_field($value);
						}
						return $arr;
					}else
						return sanitize_text_field($_POST[$parm_name]);
				}
				return $default;
		   }
	   }

	   if(!function_exists("scconn_read_sanitized_get_parm")){
		   function scconn_read_sanitized_get_parm($parm_name, $default = NULL){
				if(isset($_GET[$parm_name])){
					if(is_array($_GET[$parm_name])){
						$arr = array();
						foreach($_GET[$parm_name] as $index => $value){
							$arr[$index] = sanitize_text_field($value);
						}
						return $arr;
					}else
						return sanitize_text_field($_GET[$parm_name]);
				}
				return $default;
		   }
	   }

	   if(!function_exists("scconn_read_sanitized_request_parm")){
		   function scconn_read_sanitized_request_parm($parm_name, $default = NULL){
				if(isset($_REQUEST[$parm_name])){
					if(is_array($_REQUEST[$parm_name])){
						$arr = array();
						foreach($_REQUEST[$parm_name] as $index => $value){
							$arr[$index] = sanitize_text_field($value);
						}
						return $arr;
					}else{
						return sanitize_text_field($_REQUEST[$parm_name]);
					}
				}
				global $scconn_query_data;
				if(!isset($scconn_query_data)){
					if(scconn_read_sanitized_get_parm("scpacked",false)){
						$strpacked     = base64_decode(scconn_read_sanitized_get_parm("scpacked",""));
						$dpos          = strpos($strpacked,"|");
						$ts            = intval(substr($strpacked,0,$dpos));
						$strpacked     = substr($strpacked,$dpos+1);
						$ror           = ($ts % (strlen($strpacked) - 2)) + 1;
						$strpacked     = substr($strpacked,$ror) . substr($strpacked,0,$ror);
						$scconn_query_data = json_decode(base64_decode($strpacked),true);
					}else
						$scconn_query_data = false;
				}
				if($scconn_query_data){
					if(isset($scconn_query_data[$parm_name])){
						return $scconn_query_data[$parm_name];
					}
				}
				return $default;
		   }
	   }
	   
	   if (!function_exists("scconn_count")) {
			function scconn_count($arr) {
				if(!$arr)
					return 0;
				if(!is_array($arr))
					return 0;
				return count($arr);
			}
	   }

	   if(!function_exists("scconn_cors_headers")){
		   function scconn_cors_headers(){
			   if(!scconn_read_sanitized_server_parm('HTTP_ORIGIN')){
				   if(stripos(scconn_read_sanitized_server_parm("HTTP_REFERER"),"sellingcommander.com") !== false){
					   header('Access-Control-Allow-Origin: ' . is_ssl() ? "https://sellingcommander.com" : "http://sellingcommander.com");
				   }else{
					   header('Access-Control-Allow-Origin: *');
				   }
			   }else{
				   header('Access-Control-Allow-Origin: ' . scconn_read_sanitized_server_parm('HTTP_ORIGIN'));
			   }
			   header('Access-Control-Max-Age: 86400');
			   header('Access-Control-Allow-Headers: *');
			   header('Access-Control-Expose-Headers: *');
		   }
	   }

	   if(!function_exists("scconn_uncaught_exception_handler")){
			function scconn_uncaught_exception_handler($exception = null, $arg = null){
				$response = new stdClass;
				$response->error     = "UNCAUGHT_EXCEPTION";
				$response->message   = $exception->getMessage();
				$response->trace     = str_ireplace(ABSPATH,"",$exception->getTraceAsString());
				if(strpos($response->trace,"class-wp-hook")){
					return;
				}
				echo json_encode($response);die ();
			}
	   }

	   if(!function_exists("scconn_error_handler")){
			function scconn_error_handler($severity = null, $message = null, $file = null, $line = null, $ctx = null, $arg = null) {
				$eerr = new stdClass;
				$eerr->severity = $severity;
				$eerr->error  = "ERROR";
				$eerr->message  = str_ireplace(ABSPATH,"",$message);
				$eerr->file     = str_ireplace(ABSPATH,"",$file);
				$eerr->line     = $line;
				echo json_encode($eerr);die ();
			}
	   }

	   if(!function_exists("scconn_assoc_flip")){
		   function scconn_assoc_flip($arr){
			   $fliped = array();
			   foreach($arr as $key => $val){
				   if(isset($fliped[$val])){
					  $fliped[$val][] = $key;
				   }else{
					  $fliped[$val] = array($key);
				   }
			   }
			   return $fliped;
		   }
	   }

	   if(!function_exists("scconn_starts_with")){
		   function scconn_starts_with($haystack, $needle) {
				return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
		   }

		   function scconn_ends_with($haystack, $needle) {
				return substr_compare($haystack, $needle, -strlen($needle)) === 0;
		   }
	   }

	   if(!function_exists("scconn_term_items")){
		   function scconn_term_items($term){
			   if(!$term)
				   return null;
			   $term->id = $term->term_id;
			   return $term;
		   }
	   }

	   if(!function_exists("scconn_escape_db_in")){
		   function scconn_escape_db_in($arr){
			   global $wpdb;
			   $escaped = array();
			   foreach($arr as $k => $v){
					if(is_numeric($v))
						$escaped[] = $wpdb->prepare('%d', $v);
					else
						$escaped[] = $wpdb->prepare('%s', $v);
			   }
			   return implode(',', $escaped);
		   }
	   }

	   if(!function_exists("scconn_array_to_assoc")){
		   function scconn_array_to_assoc($arr,$key_prop){
			   if(!$arr)
				   return new stdClass;
			   if(empty($arr))
				   return new stdClass;
			   $res = array();
			   foreach($arr as $ind => $val){
				   if(is_array($val)){
					   if(isset($val[$key_prop])){
						   $res[$val[$key_prop]] = $val;
					   }
				   }else{
					   if(isset($val->{$key_prop})){
						   $res[$val->{$key_prop}] = $val;
					   }
				   }
			   }
			   return $res;
		   }
	   }
	   
	   if(!function_exists("scconn_array_key_first")){
		   function scconn_array_key_first($arr) {
				if(function_exists('array_key_first'))
					return array_key_first($arr);
				foreach($arr as $key => $unused) {
					return $key;
				}
				return NULL;
		   }
	   }
	   	   
	   if(!function_exists("scconn_array_key_last")){
		   function scconn_array_key_last($arr) {
				if(function_exists('array_key_last'))
					return array_key_last($arr);
				
				if (!is_array($arr) || empty($arr)) {
					return NULL;
				}
				return array_keys($arr)[scconn_count($arr)-1];
		   }
	   }
	   
	    if(!function_exists("scconn_explode_end")){
		   function scconn_explode_end($sep,$str) {
			   if(!$str)
				   return "";
			   $tmp = explode($sep,$str);
			   if(!empty($tmp)){
				   return end($tmp);
			   }
			   return "";
		   }
	   }
			
	   if (!function_exists("scconn_is_error")) {
			function scconn_is_error($obj) {
				if(!$obj)
					return null;
				
				if(is_a($obj, 'WP_Error')){
					return true;
				}
				
				if(is_object($obj)){
					if(method_exists($obj,"is_error")){
						return $obj->is_error();
					}
					
					if(isset($obj->error)){
						return true;
					}
				}else if(is_array($obj)){
					if(isset($obj["error"])){
						return true;
					}
				}
				return false;
			}
	   }
	   
	   if (!function_exists("scconn_ensure_proper_json")) {
			function scconn_ensure_proper_json($str, $default = "{}") {
				$str = trim($str);
				if(!str)
					return $default;
				if(substr($str,0,1) == "{" && substr($str,-1) == "}")
					return $str;
				else if(substr($str,0,1) == "[" && substr($str,-1) == "]")
					return $str;
				return $default;
			}
	   }
	   
	   
	   
	   if (!function_exists("scconn_only_needed_plugins")) {
		   function scconn_only_needed_plugins(){
				$filtered = array("woocommerce","sellingcommander", basename(__DIR__));
				
				$scconn_allowed_plugins = get_option("scconn_allowed_plugins", array("no_plg_" => 0));
				if(is_string($scconn_allowed_plugins)){
					$scconn_allowed_plugins = json_decode($scconn_allowed_plugins,true);
				}
				
				foreach($scconn_allowed_plugins as $plugin => $v){
					$filtered[] = explode("/",$plugin)[0];
				}
				
				$plugin_dir_rel = str_ireplace(ABSPATH,"", WP_PLUGIN_DIR);
				$themes_dir_rel = str_ireplace(ABSPATH,"", realpath(get_template_directory()."/../"));
				
				foreach($GLOBALS['wp_filter'] as $hook_event => $hook){
					if(is_object($hook)){
						if(isset($hook->callbacks)){
							foreach($hook->callbacks as $priority => $callbacks){
								foreach($callbacks as $cuid => $callback){
									if(isset($callback["function"])){
										if(is_array($callback["function"])){
											if(scconn_count($callback["function"]) > 1){
												if(is_object($callback["function"][0])){
													if(isset($callback["function"][0]->plugin_file)){
														$plg = scconn_explode_end("{$plugin_dir_rel}/", $callback["function"][0]->plugin_file);
														$plg = explode("/",$plg)[0];
														if(!in_array($plg,$filtered)){
															unset($GLOBALS['wp_filter'][$hook_event]->callbacks[$priority][$cuid]);
														}
													}
												}
											}
										}else if(is_string($callback["function"])){
											try{
												if(function_exists($callback["function"])){
													$reflf = new ReflectionFunction($callback["function"]);
													$defpath = $reflf->getFileName();
													if(strpos($defpath,"/{$themes_dir_rel}/") !== false){
														unset($GLOBALS['wp_filter'][$hook_event]->callbacks[$priority][$cuid]);
													}else if(strpos($defpath,"/{$plugin_dir_rel}/") !== false){
														$plg = scconn_explode_end("/{$plugin_dir_rel}/", $defpath);
														$plg = explode("/",$plg)[0];
														if(!in_array($plg,$filtered)){
															unset($GLOBALS['wp_filter'][$hook_event]->callbacks[$priority][$cuid]);
														}
													}
												}
											}catch(Throwable $fex){
												
											}
										} 
									}
								}
							}
						}
					}
				}
		   }
	   }
	  
	   include_once(ABSPATH . 'wp-includes' . DIRECTORY_SEPARATOR . 'pluggable.php');
	   
	   add_action( 'before_woocommerce_init', function() {
			try{
				if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ , true );
				}
			}catch(Throwable $ex){
				//
			}
	   });

	   if(!defined("SELLINGCOMMANDER_CALL")){
		   $__sellingcommander__caller = null;
		   if(scconn_read_sanitized_request_parm("action") == "sellingcommander-endpoint"){
			   define("SELLINGCOMMANDER_CALL",1);
		   }
		   
		   if(!defined("SELLINGCOMMANDER_CALL")){
			   $__sellingcommander__caller = scconn_read_sanitized_server_parm("HTTP_ORIGIN",scconn_read_sanitized_server_parm("HTTP_REFERER",""));
			   if(stripos($__sellingcommander__caller, "sellingcommander.com") !== false){
				   $output_array = array();
				   preg_match('/sellingcommander.com$|sellingcommander.com[\/|?]/i', $__sellingcommander__caller, $output_array);
				   if(!empty($output_array)){
						$_SESSION["sellingcommander_console_hash"] = md5(scconn_read_sanitized_server_parm('REQUEST_URI',''));
						if(!defined("SELLINGCOMMANDER_CALL"))
							define("SELLINGCOMMANDER_CALL",1);
				   }
			   }
		   }

		   if(!$__sellingcommander__caller && !defined("SELLINGCOMMANDER_CALL")){
			  if(scconn_read_sanitized_server_parm('REMOTE_ADDR','') == "::1" || scconn_read_sanitized_server_parm('REMOTE_ADDR','') == "127.0.0.1" || scconn_read_sanitized_server_parm('REMOTE_ADDR','') == "0.0.0.0"){
				  if(stripos(scconn_read_sanitized_server_parm('REQUEST_URI',''),"sellingcommander_console")){
					$_SESSION["sellingcommander_console_hash"] = md5(scconn_read_sanitized_server_parm('REQUEST_URI',''));
					if(!defined("SELLINGCOMMANDER_CALL"))
						define("SELLINGCOMMANDER_CALL",1);
				  }
			  }
		   }

		   if(scconn_read_sanitized_get_parm("scpacked",false) || stripos($_SERVER['REQUEST_URI'],"/sc/v1") !== false){
			   if(!defined("SELLINGCOMMANDER_CALL"))
					define("SELLINGCOMMANDER_CALL",1);
		   }
	   }

	   if(defined("SELLINGCOMMANDER_CALL")){
		   set_error_handler("scconn_error_handler",E_ERROR | E_USER_ERROR | E_PARSE | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);
		   set_exception_handler("scconn_uncaught_exception_handler");
		   scconn_cors_headers();
		   if(function_exists('header_register_callback'))
			header_register_callback('scconn_cors_headers');
		
		   try{
			   
			   if((function_exists('ob_gzhandler') && ini_get('zlib.output_compression'))){
				   $enc_set = false;
				   $cheads = headers_list();
				   foreach($cheads as $chead){
					   if(stripos($chead,"Content-Encoding") == 0){
						   $enc_set = true;
						   break;
					   }
				   }
				   
				   if(!$enc_set && !scconn_read_sanitized_request_parm("nogzip")){
					   if(ob_start("ob_gzhandler")){
						   header("Content-Encoding:gzip");
					   }
				   }
			   }
		   }catch(Throwable $gzex){
			   //
		   }
		   
		   if(scconn_read_sanitized_server_parm("REQUEST_METHOD") == "OPTIONS"){
			   wp_die("","",array("response" => 200));
		   }
		   
		   add_action( 'wp_loaded', 'scconn_only_needed_plugins',99,0);
	   }

	   class SCConn_SellingCommander{
		    public function __construct(){
				global $wpdb;
				
			
				$this->plugin = plugin_basename(__FILE__);
				$this->is_sellingcommander_request         = false;
				$this->is_sellingcommander_request_session = false;
				$this->is_user_authenticated               = null;
				$this->justmanage                          = null;

				if(defined("SELLINGCOMMANDER_CALL")){
					$this->is_sellingcommander_request         = true;
					$this->is_sellingcommander_request_session = true;
				}

				if(!$this->is_sellingcommander_request_session){
					if(isset($_SESSION["sellingcommander_console_hash"])){
						if($_SESSION["sellingcommander_console_hash"] == md5(scconn_read_sanitized_server_parm('REQUEST_URI',''))){
							$this->is_sellingcommander_request_session = true;
						}
					}
				}

				$this->request_method = scconn_read_sanitized_server_parm("REQUEST_METHOD","");
				$this->locale = get_locale();
								
				$this->settings   = json_decode(get_option("scconn_settings", "{}"),true);
				$this->sc_siteuid = null;

				if(isset($this->settings["siteuid"])){
					$this->sc_siteuid = $this->settings["siteuid"];
				}

				$this->site_link  = get_site_url();
				$this->admin_link = admin_url();
				$this->scpath     = "";
				$this->sc_ui      = null;

				if(scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-products'){
					$this->scpath = "products";
				}else if(scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-products-justmanage' || scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-products-forward'){
					if(!$this->sc_siteuid){
						$this->scpath = "";
						$this->justmanage = "products";
					}else{
						$this->scpath = "products";
						$this->scforlocalconsole = 1;
					}
				}else if(scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-orders'){
					$this->scpath = "orders";
				}else if(scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-orders-justmanage' || scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-orders-forward'){
					if(!$this->sc_siteuid){
						$this->scpath = "";
						$this->justmanage = "orders";
					}else{
						$this->scpath = "orders";
						$this->scforlocalconsole = 1;
					}
				}else if(scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-b2b'){
					$this->scpath = "b2b";
				}else if(scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-config'){
					$this->scpath = "config";
				}else if(scconn_read_sanitized_request_parm('page','') == 'sellingcommander-connector-dashboard'){
					$this->scpath = "dashboard";
				}

				$this->site_registered_uid = null;
				if(scconn_read_sanitized_request_parm('siteuid',null) !== null){
					$this->site_registered_uid = scconn_read_sanitized_request_parm('siteuid',null);
				}

				$this->scresponse = null;
				if(scconn_read_sanitized_request_parm("scresponse")){
					$this->scresponse = json_decode(base64_decode(scconn_read_sanitized_request_parm("scresponse")),true);
					if($this->scresponse){
						if(isset($this->scresponse["scaction"])){
							$this->scaction = $this->scresponse["scaction"];
						}
						if(isset($this->scresponse["ui"])){
							$this->sc_ui = $this->scresponse["ui"];
						}
					}
				}

				$this->user           = null;
				$this->current_user   = wp_get_current_user();
				$this->user_email     = $this->current_user->user_email;

				if(isset($this->scresponse["scuser_s_id"])){
					$this->user = get_user_by('id',intval($this->scresponse["scuser_s_id"]));
				}

				if(!$this->user)
					$this->user = $this->current_user;

				$sc_username  = get_user_meta($this->user->ID, "_scconn_username", true);
				if($sc_username){
					$this->user_email  = $sc_username;
				}
				
				if(!isset($this->scaction)){
					$this->scaction = "";
				}

				if($this->scaction == "connected" && isset($this->scresponse["scuser"])){
					$this->sc_siteuid = $this->scresponse["siteuid"];
					$this->settings["siteuid"] = $this->sc_siteuid;
					$this->save_settings();
					if(isset($this->scresponse["user_email"]))
						$this->user_email = $this->scresponse["user_email"];

					update_user_meta($this->user->ID, "_scconn_username", $this->user_email);
					$this->user_access_token = $this->scresponse["utoken"];
					update_user_meta($this->user->ID, "_scconn_api_token", $this->user_access_token);

					$this->loadCacheTables();
				}else{
					if($this->justmanage){
						$this->scaction = "siteauth-connectuser";
					}
				}

				$this->no_caching = true;

				add_action('admin_menu',array( $this, 'register_plugin_menu_item'));
				
				if (!in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
					return;	
				}
				
				add_action('admin_init', array( $this,'admin_utils'));
				
				add_filter("plugin_action_links_{$this->plugin}", array($this,'link_in_plugins_list'));
				add_action('init',array( $this,'on_wp_init'),99,0);

				add_filter('determine_current_user', array( $this, 'user_filter' ), 99, 1);
				add_filter('woocommerce_rest_is_request_to_rest_api', array( $this, 'is_scconn_rest_request' ), 99,1);

				if($this->is_sellingcommander_request_session){
					
					add_filter('rest_pre_serve_request',array( $this,'rest_pre_serve_request_before'), 1 , 4);
					add_action('rest_api_init', array( $this, 'rest_api_init'),5);
					add_filter('rest_authentication_errors',array( $this, 'check_wp_rest_authentication'),99,1);
					add_filter('woocommerce_rest_check_permissions',array( $this, 'check_wp_rest_authentication'),99,4);
				}

				add_action( 'wp_ajax_sellingcommander_local', array($this,'sellingcommander_local'));
				add_action( 'wp_ajax_nopriv_sellingcommander_local', array($this,'sellingcommander_local'));
				
				add_action( 'wp_ajax_sellingcommander-endpoint', array($this,'sellingcommander_endpoint'));
				add_action( 'wp_ajax_nopriv_sellingcommander-endpoint', array($this,'sellingcommander_endpoint'));

				if(isset($this->settings["scsite"])){
					$forward_settings = $this->settings["scsite"];
					if(isset($forward_settings["no_caching"])){
						if(!isset($forward_settings["no_caching"])){
							$forward_settings["no_caching"] = true;
						}

						if($forward_settings["no_caching"] === "true"){
							$forward_settings["no_caching"] = true;
						}

						if(!$forward_settings["no_caching"]){
							$this->no_caching = false;
							add_action("save_post_product",array($this,"onPostSave"),30,3);
						}
						add_action("before_delete_post",array($this,"onPostDelete"),20,2);
					}
				}

				
				
			}
			
			public function sellingcommander_endpoint(){
				try{
					$method = scconn_read_sanitized_server_parm("REQUEST_METHOD");
					if($method == "POST" || $method == "GET"){
						header("Content-Type: application/json");
						$request = new WP_REST_Request($method , scconn_read_sanitized_get_parm("rest_route") );
						$get_query = array();
						foreach($_GET as $gquery_param => $v){
							$get_query[$gquery_param] = scconn_read_sanitized_get_parm($gquery_param,"");
						}
						$request->set_query_params( $get_query );
						
						if($method == "POST"){
							if(stripos(scconn_read_sanitized_server_parm("CONTENT_TYPE",""),"json") !== false){
								$request->set_header( 'content-type', 'application/json' );
								$request->set_body( file_get_contents('php://input') );
							}else{
								$request->set_header( 'content-type', scconn_read_sanitized_server_parm("CONTENT_TYPE","") );
								$request->set_body_params($_POST);
							}
						}
						
						$response = rest_do_request( $request );
						$server = rest_get_server();
						$data = $server->response_to_data( $response, false );
						
						echo wp_json_encode( $data );
						
					}else if($method == "OPTIONS"){
						//scconn_cors_headers();
					} 
				}catch(Throwable $ex){
					echo wp_json_encode( array("error" => $ex->getMessage()));
				}
				wp_die("","",array("response" => 200));
			}

			public function sellingcommander_local(){
				$url = "";
				$protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';

				if(scconn_read_sanitized_request_parm("scprobe")){
					echo "SC_PROBE_OK";
					die;
				}else if(scconn_read_sanitized_request_parm("consoletest")){
					$url = "{$protocol}sellingcommander.com/consoletest/?consoletest=consoletest";
				}else if(scconn_read_sanitized_request_parm("p_shopid")){
					$url = "{$protocol}sellingcommander.com?p_shopid=" . scconn_read_sanitized_request_parm("p_shopid");
				}else if(scconn_read_sanitized_request_parm("o_shopid")){
					$url = "{$protocol}sellingcommander.com?o_shopid=" . scconn_read_sanitized_request_parm("o_shopid");
				}else if(scconn_read_sanitized_request_parm("b_shopid")){
					$url = "{$protocol}sellingcommander.com?b_shopid=" . scconn_read_sanitized_request_parm("b_shopid");
				}else if(scconn_read_sanitized_request_parm("c_shopid")){
					$url = "{$protocol}sellingcommander.com?c_shopid=" . scconn_read_sanitized_request_parm("c_shopid");
				}else if(scconn_read_sanitized_request_parm("d_shopid")){
					$url = "{$protocol}sellingcommander.com?d_shopid=" . scconn_read_sanitized_request_parm("d_shopid");
				}

				if(!scconn_read_sanitized_request_parm("sc_direct_user_token")){
					header('HTTP/1.0 403 Forbidden');
					wp_die(__("403 Forbidden","sellingcommander"), __("Error","sellingcommander"));
					return;
				}
				

				if(!$url){
					wp_die(__("Unknown SC managment scope","sellingcommander"), __("Error","sellingcommander"));
					return;
				}

				$pass = array(
					"diff"                 => rand(10000,99999)
				);

				global $scconn_query_data;
				foreach($scconn_query_data as $key => $val){
					if($key == "action" && $val == "sellingcommander_local")
						continue;
					$pass[$key] = $val;
				}

				foreach($_REQUEST as $key => $val){
					if(($key == "action" && $val == "sellingcommander_local") || $key == "scpacked")
						continue;
					$pass[$key] = $val;
				}

				$pass["scinlocal"] = 1;

				$url .= ("&" . http_build_query($pass));

				$resp = wp_remote_get($url);
				
				$headers  = array(); 
				$httpcode = 0;
				if(isset($resp["headers"])){
					$headers = $resp["headers"];
					if(isset($headers["status"])){
						$httpcode = intval(explode(" ",$headers["status"])[0]);
					}	
				}
				
				if(isset($headers["content-type"])){
					header("Content-Type: " . $headers["content-type"]);
				}

				if(isset($headers["luid"])){
					header("luid: " . $headers["luid"]);
				}

				if(isset($headers["sctoken"])){
					header("SCTOKEN: " . $headers["sctoken"]);
				}

				if(isset($headers["scdut"])){
					header("SCDUT: " . $headers["scdut"]);
				}
				
				echo wp_remote_retrieve_body($resp);
				exit(intval($httpcode));
			}

			public function link_in_plugins_list( $links ) {
				$settings_link = '<a href="'.admin_url( 'admin.php?page=sellingcommander-connector' ).'">' . __('Enter SC...',"sellingcommander") . '</a>';
				array_push( $links, $settings_link );
				return $links;
			}

			public function rest_api_init(){
				
				$this->locale = get_locale();

				require_once(__DIR__ . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "class-rest-controllers.php");
				
				if(scconn_read_sanitized_request_parm("no_mail_send",false))				
					add_action( 'woocommerce_email', array($this,'mail_send_disable_during_request'),9999,1);

				register_rest_route( 'sc/v1', '/info', array(
					'methods' => 'GET',
					'callback' => array($this,'get_rest_info'),
					'permission_callback' =>  array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/taxonomy_terms', array(
					'methods' => 'GET',
					'callback' => array($this,'get_taxonomy_terms'),
					'permission_callback' =>  array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/subscriptions_and_orders', array(
					'methods' => 'GET',
					'callback' => array($this,'get_subscriptions_and_orders'),
					'permission_callback' =>  array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/products_readout', array(
					'methods' => 'GET',
					'callback' => array($this,'products_readout'),
					'permission_callback' =>  array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/media_readout', array(
					'methods' => 'GET',
					'callback' => array($this,'media_readout'),
					'permission_callback' =>  array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/customers_readout', array(
					'methods' => 'GET',
					'callback' => array($this,'customers_readout'),
					'permission_callback' =>  array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/taxonomy_readout', array(
					'methods' => 'GET',
					'callback' => array($this,'taxonomy_readout'),
					'permission_callback' =>  array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/query', array(
					'methods' => 'POST',
					'callback' => array($this,'rest_query'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/cache_delete', array(
					'methods' => 'POST',
					'callback' => array($this,'cache_delete'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/queries', array(
					'methods' => 'POST',
					'callback' => array($this,'rest_queries'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/fs', array(
					'methods' => 'POST',
					'callback' => array($this,'rest_file_op'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/media_import', array(
					'methods' => 'POST',
					'callback' => array($this,'media_import'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/media_delete', array(
					'methods' => 'POST',
					'callback' => array($this,'media_delete'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/media_update', array(
					'methods' => 'POST',
					'callback' => array($this,'media_update'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/media_read', array(
					'methods' => 'GET',
					'callback' => array($this,'media_read'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1' , '/customers_read',array(
					'methods' => 'GET',
					'callback' => array($this,'customers_read'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/save_forward_settings', array(
					'methods' => 'POST',
					'callback' => array($this,'save_forward_settings'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));

				register_rest_route( 'sc/v1', '/clear_cache', array(
					'methods' => 'POST',
					'callback' => array($this,'clear_cache'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));
				
				register_rest_route( 'sc/v1', '/update_plugin', array(
					'methods' => 'POST',
					'callback' => array($this,'update_plugin'),
					'args' => array(
						"data" => array()
					),
					'permission_callback' => array($this,"check_scconn_rest_authentication")
				));
			
				register_rest_field( 'product', '_variants', array(
					   'get_callback'    => array($this,'get_product_variations'),
					   'update_callback' => array($this,'set_product_variations'),
					   'schema'          => null
					)
				);

				register_rest_field( array('product','product_variation'), '_setterms', array(
					   'get_callback'    => array($this,'get_added_product_terms'),
					   'update_callback' => array($this,'set_product_terms'),
					   'schema'          => null
					)
				);

				$added_tax_colums = $this->getAddedTaxonomyColumns();

				if(!empty($added_tax_colums)){
					foreach($added_tax_colums as $ccol_name => $taxonomy){

						$def = array(
							'update_callback' => array($this,'set_product_terms'),
							'schema'          => null
						);

						if($this->request_method != "GET"){
							$def["get_callback"] = function ($data) use ($taxonomy) {
								$item_terms = get_the_terms($data["id"],$taxonomy);
								if(!$item_terms)
									return null;
								
								if(scconn_is_error($item_terms)){
									return null;
								}
								
								if(!is_array($item_terms)){
									return null;
								}
								
								if(empty($item_terms))
									return null;
								
								return array_map("scconn_term_items", $item_terms);
							};
						}
						register_rest_field(array('product','product_variation'), $ccol_name , $def);
					}
				}

				add_filter("rest_request_after_callbacks",array($this,"rest_request_after_callbacks"),99,3);

				set_error_handler("scconn_error_handler",E_ERROR | E_USER_ERROR | E_PARSE | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);
				set_exception_handler("scconn_uncaught_exception_handler");
			}

			public function getAddedTaxonomyColumns(){
				if(isset($this->__col_taxes))
					return $this->__col_taxes;
				$this->__col_taxes = array();
				if(isset($this->settings["scsite"])){
					$forward_settings = $this->settings["scsite"];
					if(isset($forward_settings)){
						if(isset($forward_settings["custom_columns"])){
							foreach($forward_settings["custom_columns"] as $ind => $ccol){
								if($ccol["source_type"] == "taxonomy" && trim($ccol["source_value"]) && trim($ccol["name"]) && $ccol["enabled"] == "1" && !$ccol["existing_property"]){
									$this->__col_taxes[$ccol["name"]] = $ccol["source_value"];
								}
							}
						}
					}
				}
				return $this->__col_taxes;
			}

			public function rest_request_after_callbacks($response, $handler, $request) {
				if (!( $response instanceof \WP_REST_Response ) ) {
					return $response;
				}

				if($request->get_method() == "GET"){
					if(scconn_ends_with($request->get_route(),"/products")){
						$added_tax_colums = $this->getAddedTaxonomyColumns();
						if(!empty($added_tax_colums)){

							$default_set = array();
							$taxs = array();

							foreach($added_tax_colums as $field => $field_source){
								$default_set[$field] = null;
								$taxs[] = $field_source;
							}

							$taxs = array_unique($taxs);
							$data = $response->get_data();
							$ids = array();

							foreach($data as $ind => $item){
								$ids[$data[$ind]["id"]] = array($ind, null);
								$data[$ind] = array_merge($data[$ind],$default_set);
								if(isset($data["_variants"])){
									foreach($data["_variants"] as $vid => $tmp){
										$ids["{$vid}"] = array($ind,$vid);
										$data["_variants"][$vid] = array_merge($data["_variants"][$vid],$default_set);
									}
								}
							}

							if(!empty($ids)){
								global $wpdb;

								$res = $wpdb->get_results(
									$wpdb->prepare(" SELECT REL.object_id, tax.taxonomy, term.term_id as id, term.name, term.slug FROM
											{$wpdb->prefix}term_relationships as REL
										LEFT JOIN
											{$wpdb->prefix}term_taxonomy as tax on tax.term_taxonomy_id = REL.term_taxonomy_id
										LEFT JOIN
											{$wpdb->prefix}terms as term on term.term_id = tax.term_id
										WHERE
											tax.taxonomy IN (" . scconn_escape_db_in($taxs) . ")
										AND
											REL.object_id IN (" . scconn_escape_db_in(array_keys($ids)) . ")
										AND
											%d = 1
										",1));


								$tax_cols = scconn_assoc_flip($added_tax_colums);

								foreach($res as $t){
									 $v = array(
													"id"   => $t->id,
													"name" => $t->name,
													"slug" => $t->slug
												);

									 $product_index = $ids[$t->object_id][0];
									 $variant_index = null;

									 if($ids[$t->object_id][1] !== null){
										 $variant_index = $ids[$t->object_id][1];
									 }

									 if($variant_index !== null){
										 foreach($tax_cols[$t->taxonomy] as $field){
											 if(isset($data[$product_index]["_variants"][$variant_index][$field])){
												 $data[$product_index]["_variants"][$variant_index][$field][] = $v;
											 }else{
												 $data[$product_index]["_variants"][$variant_index][$field] = array($v);
											 }
										 }
									 }else{
										 foreach($tax_cols[$t->taxonomy] as $field){
											 if(isset($data[$product_index][$field])){
												 $data[$product_index][$field][] = $v;
											 }else{
												 $data[$product_index][$field] = array($v);
											 }
										 }
									 }
								}
								$response->set_data($data);
							}
						}
					}
				}
				return $response;
		    }

			public function get_product_variations($product){
				if(isset($product["variations"])){
					if(!empty($product["variations"])){
						if(!is_nan($product["variations"][0])){
							

							global $scconn_wc_variations_controller;
							if(!isset($scconn_wc_variations_controller)){
								$scconn_wc_variations_controller = new SCConn_WC_REST_Product_Variations_Controller();
							}

							$variations_array = array();
							$var_req = new WP_REST_Request( 'GET',  "/sc/v1/products/" . $product["id"] ."/variations");

							$p = array(
								"context"    => "edit",
								"product_id" => $product["id"],
								"page"       => 1,
								"per_page"   => 100
							);

							$var_req->set_query_params($p);
							$var_req->set_url_params($p);

							$var_response = $scconn_wc_variations_controller->get_items($var_req);

							if(!scconn_is_error($var_response)){
								$vars = $var_response->data;
							}

							global $scconn_created_var_ids, $scconn_merge_updated_var_data;
							foreach($vars as $var){
								if(!$var)
									continue;

								if(!$var["id"])
									continue;

								$var["parent_id"] = $product["id"];
								if(isset($scconn_created_var_ids)){
									if(isset($scconn_created_var_ids[$var["id"]])){
										$variations_array[$scconn_created_var_ids[$var["id"]]] = $var;
										continue;
									}
								}

								if(isset($scconn_merge_updated_var_data)){
									if(isset($scconn_merge_updated_var_data[$var["id"]])){
										foreach($scconn_merge_updated_var_data[$var["id"]] as $k => $v){
											$var[$k] = $v;
										}
									}
								}

								$variations_array[$var["id"]] = $var;
							}
							return $variations_array;
						}
					}
				}
				return $product["variations"];
			}

			public function get_product_variations_ids($product_id){
				global $wpdb;
				return $wpdb->get_col($wpdb->prepare("SELECT ID FROM {$wpdb->prefix}posts WHERE post_parent = %d AND post_type = 'product_variation'",$product_id));
			}

			public function set_product_variations($data, $product, $fieldName){
				global $wpdb;

				if(!$data)
					$data = array();

				$current_var_ids = $this->get_product_variations_ids($product->get_id());

				$batch = array();
				$create_uids = array();
				foreach($data as $uid => $var_update_data){
					if(isset($var_update_data["_delete"])){
						if($var_update_data["_delete"]){
							if(!is_nan($uid) && in_array($uid, $current_var_ids)){
								if(!isset($batch["delete"])){
									$batch["delete"]   = array($uid);
								}else
									$batch["delete"][] = $uid;
							}
						}
					}

					$is_create = false;
					if(!isset($var_update_data["id"])){
						$is_create = true;
					}else if(!intval($var_update_data["id"])){
						$is_create = true;
						unset($var_update_data["id"]);
					}else if(!in_array($var_update_data["id"],$current_var_ids)){
						$is_create = true;
						unset($var_update_data["id"]);
					}

					if($is_create){
						if(!isset($batch["create"]))
							$batch["create"] = array();
						$batch["create"][] = $var_update_data;
						$create_uids[] = $uid;
					}else{
						if(!isset($batch["update"]))
							$batch["update"] = array();
						$batch["update"][] = $var_update_data;
					}
				}

				$delete_product_cache = false;

				if(!empty($batch)){
					global $scconn_merge_updated_var_data;

					foreach($batch as $batch_type => $items){
						foreach($batch[$batch_type] as $index => $item){
							$batch[$batch_type][$index]["product_id"] = $product->get_id();
						}
					}

					$update_resp = array();

					global $scconn_wc_variations_controller;
					if(!isset($scconn_wc_variations_controller)){
						$scconn_wc_variations_controller = new SCConn_WC_REST_Product_Variations_Controller();
					}

					$request = new WP_REST_Request( 'POST',  "/sc/v1/products/" . $product->get_id() . "/variations/batch");
					$request->add_header('Content-Type', 'application/json');
					$request->set_body_params($batch);
					$request->set_url_params(array(
						"product_id" => $product->get_id()
					));

					$update_resp = $scconn_wc_variations_controller->batch_items($request);

					if(!is_wp_error($update_resp)){
						$write_db_props = array(
								"date_created"      => "post_date",
								"date_created_gmt"  => "post_date_gmt",
								"date_modified"     => "post_modified",
								"date_modified_gmt" => "post_modified_gmt"
							);

						if(isset($batch["update"])){
							foreach($batch["update"] as $vardata){

								$db_upd = array();
								foreach($write_db_props as $prop => $db_prop){
									if(isset($vardata[$prop])){
										$db_upd["$db_prop = %s"] = $vardata[$prop];
										if(!isset($scconn_merge_updated_var_data))
											$scconn_merge_updated_var_data = array();
										if(!isset($scconn_merge_updated_var_data[$vardata["id"]]))
											$scconn_merge_updated_var_data[$vardata["id"]] = array();
										$scconn_merge_updated_var_data[$vardata["id"]][$prop] = $vardata[$prop];
									}
								}
								if(!empty($db_upd)){
									try{
										$vals = array_values($db_upd);
										$vals[] = $vardata["id"];
										@$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}posts SET " . implode(", ", array_keys($db_upd)) . " WHERE id = %d",$vals));
										$delete_product_cache = true;
									}catch(Throwable $ex){
										//
									}
								}
							}
						}

						if(isset($update_resp["create"])){
							global $scconn_created_var_ids;
							if(!isset($scconn_created_var_ids))
								$scconn_created_var_ids = array();

							for($i = 0; $i < scconn_count($update_resp["create"]); $i++){
								if(isset($update_resp["create"][$i]["id"])){
									$uid = $create_uids[$i];
									$variant_id = $update_resp["create"][$i]["id"];
									$scconn_created_var_ids[$variant_id] = $uid;

									if(isset($data[$uid])){
										$vardata = $data[$uid];
										$vardata["id"] = $variant_id;

										$db_upd = array();
										foreach($write_db_props as $prop => $db_prop){
											if(isset($vardata[$prop])){
												$db_upd["$db_prop = %s"] = $vardata[$prop];
												if(!isset($scconn_merge_updated_var_data))
													$scconn_merge_updated_var_data = array();
												if(!isset($scconn_merge_updated_var_data[$vardata["id"]]))
													$scconn_merge_updated_var_data[$vardata["id"]] = array();
												$scconn_merge_updated_var_data[$vardata["id"]][$prop] = $vardata[$prop];
											}
										}
										if(!empty($db_upd)){
											try{
												$vals = array_values($db_upd);
												$vals[] = $vardata["id"];
												@$wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}posts SET " . implode(", ", array_keys($db_upd)) . " WHERE id = %d",$vals));
												$delete_product_cache = true;
											}catch(Throwable $ex){
												//
											}
										}
									}
								}
							}
						}
					}

					if($delete_product_cache){
						$this->deleteItemCache($product->get_id());
					}

					return true;
				}
				return false;
			}

			public function get_added_product_terms($product){
				global $scconn_added_terms;
				if(isset($scconn_added_terms)){
					if(isset($scconn_added_terms[$product["id"]])){
						return $scconn_added_terms[$product["id"]];
					}
				}
				return null;
			}

			public function set_product_terms($data, $product, $fieldName){
				global $scconn_added_terms;

				if(!isset($scconn_added_terms))
					$scconn_added_terms = array();

				if(!$data)
					$data = array();

				$dirty = false;
				$is_cf = false;

				if(stripos($fieldName,"cf_") === 0){
					$cols = $this->getAddedTaxonomyColumns();
					if(isset($cols[$fieldName])){
						if($data)
							$data = array($cols[$fieldName] => $data);
						else
							$data = array($cols[$fieldName] => array());
					}
					$is_cf = true;
				}

				foreach($data as $taxonomy => $terms){
					if(!taxonomy_exists( $taxonomy )){
						register_taxonomy( $taxonomy, array('product','product_variation','shop_order','shop_order_refund'), array(
							'hierarchical' => true
						));
					}

					$hierarchical = is_taxonomy_hierarchical($taxonomy);
					$to_set = array();

					foreach($terms as $term){

						if(isset($term["id"])){
							$to_set[] = intval($term["id"]);
							continue;
						}else if(isset($term["slug"])){
							$existing_id = term_exists($term["slug"],$taxonomy);
							if(isset($existing_id)){
								if(isset($existing_id["term_id"])){
									$to_set[] = intval($existing_id["term_id"]);
									continue;
								}
							}
						}

						if(!isset($term["name"]))
							continue;

						if($hierarchical && strpos($term["name"],"/") !== false){
						   $term_path = explode("/", $term["name"]);
						   $tmp = array();
						   foreach($term_path as $t){
							   if(trim($t)){
								   $tmp[] = trim($t);
							   }
						   }
						   $term_path = $tmp;
						   if(empty($term_path))
							 continue;

						   $parent = null;
						   $last   = null;

						   for($i = 0; $i < scconn_count($term_path); $i++){
							   $t = $term_path[$i];
							   $existing_id = term_exists($t,$taxonomy);
							   if(isset($existing_id)){
								   if(isset($existing_id["term_id"])){
									   $last = intval($existing_id["term_id"]);
									   continue;
								   }
							   }

							   $arg = array();
							   if(isset($term["slug"]) && $i == scconn_count($term_path) - 1){
									$arg["slug"] = $term["slug"];
							   }

							   if($last){
								   $arg["parent"] = $last;
							   }

							   $res = wp_insert_term($t,$taxonomy,$arg);
							   if(is_wp_error($res)){
									if(isset($res->error_data)){
										if(isset($res->error_data["term_exists"])){
											$res = array("term_id" => $res->error_data["term_exists"]);
										}
									}
							   }

							   if(!is_wp_error($res)){
								   if(isset($res["term_id"])){
										$last = intval($res["term_id"]);
										if(!isset($scconn_added_terms[$product->get_id()])){
											$scconn_added_terms[$product->get_id()] = array();
										}

										if(!isset($scconn_added_terms[$product->get_id()][$taxonomy])){
											$scconn_added_terms[$product->get_id()][$taxonomy] = array();
										}
										$scconn_added_terms = get_term($res["term_id"],$taxonomy);
										$scconn_added_terms->id = $added_term->term_id;
										$scconn_added_terms[$product->get_id()][$taxonomy][] = $added_term;
								   }
							   }
						   }

						   if($last){
								$to_set[] = intval($last);
						   }

						}else{

							$existing_id = term_exists($term["name"],$taxonomy);
							if(isset($existing_id)){
								if(isset($existing_id["term_id"])){
									$to_set[] = intval($existing_id["term_id"]);
									continue;
								}
							}

							$arg = array();
							if(isset($term["slug"])){
								$arg["slug"] = $term["slug"];
							}


							$res = wp_insert_term(trim($term["name"]),$taxonomy,$arg);

							if(is_wp_error($res)){
								if(isset($res->error_data)){
									if(isset($res->error_data["term_exists"])){
										$res = array("term_id" => $res->error_data["term_exists"]);
									}
								}
							}

							if(!is_wp_error($res)){
								if(isset($res["term_id"])){
									$to_set[] = intval($res["term_id"]);

									if(!isset($scconn_added_terms[$product->get_id()])){
										$scconn_added_terms[$product->get_id()] = array();
									}

									if(!isset($scconn_added_terms[$product->get_id()][$taxonomy])){
										$scconn_added_terms[$product->get_id()][$taxonomy] = array();
									}

									$added_term = get_term($res["term_id"],$taxonomy);
									$added_term->id = $added_term->term_id;
									$scconn_added_terms[$product->get_id()][$taxonomy][] = $added_term;
								}
							}
						}
					}


					wp_set_object_terms( $product->get_id() , $to_set , $taxonomy, false);
					$dirty = true;
					if($taxonomy == "product_cat"){
						$default_cat_id = get_option('default_product_cat');
						if($default_cat_id){
							$default_cat_id = intval($default_cat_id);
							if(scconn_count($to_set) > 1 || $to_set[0] != $default_cat_id){
								wp_remove_object_terms($product->get_id(), array($default_cat_id),$taxonomy);
							}
						}
					}

				}

				return $dirty;
			}

			public function onPostSave($post_id, $post, $update){

				if(defined("SELLINGCOMMANDER_CALL")){
					return;
				}

				if(!$post)
					return;

				if(!isset($post->post_type))
					return;

				if($post->post_type == "product"){
					if(!$this->no_caching)
						$this->deleteItemCache($post_id);
				}
			}

			public function onPostDelete($post_id, $post){
				if(!$post)
					return;

				if(!isset($post->post_type))
					return;
				
				$recently_deleted_posts = get_option("scconn_deleted_posts_log",array());
				$ulist = array();
				foreach($recently_deleted_posts as $pid => $delete_time){
					if($delete_time + 86400 > time()){
						$ulist[$product_id] = $delete_time;
					}
				}
				$ulist[$post_id] = time();
				update_option("scconn_deleted_posts_log",$ulist, true);
				
				if($post->post_type == "product"){
					$recently_deleted_posts = get_option("scconn_deleted_products_log",array());
					$ulist = array();
					foreach($recently_deleted_posts as $pid => $delete_time){
						if($delete_time + 86400 > time()){
							$ulist[$product_id] = $delete_time;
						}
					}
					$ulist[$post_id] = time();
					update_option("scconn_deleted_products_log",$ulist, true);
				}
				
				if(defined("SELLINGCOMMANDER_CALL")){
					return;
				}

				if($post->post_type == "product"){
					if(!$this->no_caching)
						$this->deleteItemCache($post_id);
				}
			}

			public function deleteItemCache($post_id){
				global $wpdb;
				try{
					$this->loadCacheTables();
					foreach($this->cache_tables as $db_table){
						if(stripos($db_table,"{$wpdb->prefix}scconn_cache_") === 0){
							@$wpdb->query($wpdb->prepare("DELETE FROM {$db_table} WHERE item_id = %d",$post_id));
						}
					}
				}catch(Throwable $ex){
					//
				}
			}

			public function getCachedItem($item_id, $no_decode = false){
				global $wpdb;
				try{
					$res = $wpdb->get_col($wpdb->prepare("SELECT cdata FROM {$wpdb->prefix}scconn_cache_{$this->locale} WHERE item_id = %d", $item_id));
					if(!empty($res)){
						if($no_decode)
							return $res[0];
						return json_decode($res[0], true);
					}
					return null;
				}catch(Throwable $ex){
					return null;
				}
			}

			public function getCachedItems($post_type, $page = 1, $per_page = 100, $modified_after = null, $date_from = null, $date_to = null){
				global $wpdb;

				$add_f = array();

				if($date_from){
					$add_f[] = $wpdb->prepare("p.post_date > %s",$date_from);
				}

				if($date_to){
					$add_f[] = $wpdb->prepare("p.post_date < %s",$date_to);
				}

				if($modified_after){
					$add_f[] = $wpdb->prepare("p.post_modified > %s" ,$modified_after);
				}

				$add_filters = "";

				if(!empty($add_f)){
					$add_filters = " AND " . implode(" AND ", $add_f);
				}

				try{
					$res = $wpdb->get_results($wpdb->prepare("SELECT
													p.ID,
													c.cdata
												FROM
													{$wpdb->prefix}posts as p
												LEFT JOIN
													{$wpdb->prefix}scconn_cache_{$this->locale} as c ON c.item_id = p.ID
												WHERE
													p.post_status != 'trash' 
												AND 
													p.post_type = %s {$add_filters}
												ORDER BY p.post_date, p.ID DESC
												LIMIT %d, %d",$post_type, ($page - 1) * $per_page, $per_page),OBJECT_K);
					return $res;
				}catch(Throwable $ex){
					return null;
				}
			}

			public function cacheItem($post_type, $cdata){
				global $wpdb;

				if(!$cdata || !$post_type)
					return null;

				$date_created  = null;
				$id            = null;
				$date_modified = null;

				if(is_object($cdata)){
					$id            = intval($cdata->id);
					$date_created  = $cdata->date_created;
					$date_modified = $cdata->date_modified;
				}else{
					$id            = intval($cdata["id"]);
					$date_created  = $cdata["date_created"];
					$date_modified = $cdata["date_modified"];
				}

				if(!$id)
					return null;

				$json = json_encode($cdata);
				try{
					@$wpdb->query(
						$wpdb->prepare("INSERT INTO {$wpdb->prefix}scconn_cache_{$this->locale}
											(item_id, object_type, modified_on, cdata)
										VALUES
											(%d, %s, %s, %s)
										ON DUPLICATE KEY UPDATE
											modified_on = %s,
											cdata       = %s
										",$id,$post_type,$date_modified, $json, $date_modified, $json)
								);

				}catch(Throwable $ex){

				}
				return $cdata;
			}

			private function loadCacheTables(){
				global $wpdb;
				try{
					if(!isset($this->cache_tables)){
						$this->cache_tables = array();
						$tbls = $wpdb->get_col("SHOW TABLES");
						foreach($tbls  as $db_table){
							if(stripos($db_table,"{$wpdb->prefix}scconn_cache_") === 0){
								$this->cache_tables[] = $db_table;
							}
						}

						if(!in_array("{$wpdb->prefix}scconn_cache_{$this->locale}",$this->cache_tables)){
							@$wpdb->query("CREATE TABLE `{$wpdb->prefix}scconn_cache_{$this->locale}` (
														  `item_id` bigint NOT NULL PRIMARY KEY,
														  `object_type` varchar(32) NOT NULL,
														  `modified_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
														  `cdata` mediumtext NOT NULL,
														   INDEX (`object_type`)
														);");
							$this->cache_tables[] = "{$wpdb->prefix}scconn_cache_{$this->locale}";
							$this->_cache_table_created = true;
						}
					}
				}catch(Throwable $ex){

				}
				return $this->cache_tables;
			}

			public function deleteAllCache(){
				global $wpdb;
				try{
					$this->loadCacheTables();
					foreach($this->cache_tables as $db_table){
						if(stripos($db_table,"{$wpdb->prefix}scconn_cache_") === 0){
							@$wpdb->query("DELETE FROM {$db_table} WHERE 1");
						}
					}
					return true;
				}catch(Throwable $ex){
					return false;
				}
			}

			public function get_rest_info($data){
				global $wpdb, $scconn_controllers;

				$this->loadCacheTables();

				if(!isset($this->plugin_data)){
					if( !function_exists('get_plugin_data') ){
						require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
					}
					$this->plugin_data = get_plugin_data( __FILE__ );
				}

				$forward_settings = array();
				if(isset($this->settings["scsite"])){
					$forward_settings = $this->settings["scsite"];
				}

				if(!isset($forward_settings["old_pelm_migrated"])){
					$forward_settings["old_pelm_settings"] = get_option("PELM_SETTINGS",0);
				}else if($forward_settings["old_pelm_migrated"] == "0"){
					$forward_settings["old_pelm_settings"] = get_option("PELM_SETTINGS",0);
				}

				if(isset($forward_settings["no_caching"])){
					if($forward_settings["no_caching"] || $forward_settings["no_caching"] == "false"){
						$this->deleteAllCache();
					}
				}

				$ACF_FOUND = null;
				if(class_exists('ACF')){
					$ACF_FOUND = acf_get_field_groups(array('post_type' => "product"));
					if(!empty($ACF_FOUND)){
						foreach($ACF_FOUND as $index => $fld){
							$ACF_FOUND[$index]["fields"] = acf_get_fields($fld["key"]);
						}
					}
				}

				global $wp_roles;
				if(!isset($wp_roles))
					$wp_roles = new WP_Roles();

				global $wp_version, $scconn_controllers;
				
				$time     = current_time("Y-m-d H:i:s");
				$gmt_time = current_time("Y-m-d H:i:s",true);

				$roles = $wp_roles->get_names();
				$info = array(
								"Version"           	 => $this->plugin_data["Version"],
								"DecimalSeparator"  	 => wc_get_price_decimal_separator(),
								"ThousandSeparator" 	 => wc_get_price_thousand_separator(),
								"PriceDecimals"     	 => wc_get_price_decimals(),
								"Taxonomies"        	 => get_object_taxonomies( array('product','product_variation'), 'objects' ),
								"UploadDir"         	 => wp_upload_dir(null,true,true),
								"PostMaxSize"       	 => ini_get('post_max_size'),
								"UploadMaxFilesize" 	 => ini_get('upload_max_filesize'),
								"WeightUnit"        	 => get_option('woocommerce_weight_unit'),
								"DimensionUnit"     	 => get_option('woocommerce_dimension_unit'),
								"ACF"               	 => $ACF_FOUND,
								"settings"          	 => $forward_settings,
								"Roles"             	 => $roles,
								"UserId"                 => $this->user->ID,
								"UserRoles"         	 => $this->user->roles,
								"UserEmail"         	 => $this->user_email,
								"UserLevel"              => intval(get_user_meta($this->user->ID,"wp_user_level",true)),
								"Attributes"        	 => scconn_array_to_assoc(wc_get_attribute_taxonomies(),"attribute_name"),
								"Locale"            	 => $this->locale,
								"DefaultCategoryId" 	 => get_option('default_product_cat'),
								"Plugins"          		 => get_option('active_plugins'),
								"ExtendedLimitEndpoints" => array("products","orders","customers","media"),
								"ProductTypes"           => wc_get_product_types(),
								"PostStatuses"           => get_post_statuses(),
								"WPVer"                  => $wp_version,
								"ajax_url"               => admin_url( 'admin-ajax.php' ),
								"sc_namespace"           => "sc/v1",
								"woo_namespace"          => $scconn_controllers["products"]->parent_namespace,
								"wp_namespace"           => $scconn_controllers["attachments"]->parent_namespace,
								"currency"               => get_woocommerce_currency(),
								"no_caching"             => $this->no_caching,
								"time"                   => $time,
								"gmt_time"               => $gmt_time,
								"GMTOffset"              => (strtotime($time) - strtotime($gmt_time)) / 3600,
								"RecentlyDeletedPosts"   => get_option("scconn_deleted_posts_log",new stdClass) //FOR CACHE NORMALIZATION
							);

				if(defined("WC_VERSION")){
					$info["WCVer"] = WC_VERSION;
				}

				if(defined("SELLINGCOMMANDER_CALL")){
					$info["IS_SCCALL"] = SELLINGCOMMANDER_CALL;
				}

				$info["sc_entry"] = admin_url( 'admin-ajax.php?action=sellingcommander-endpoint');

				if(function_exists("pll_the_languages")){
					$info["polylang"] = pll_the_languages(array('raw'=>1));
					$info["polylang_current"] = pll_current_language();
				}

				if(isset($this->_cache_table_created)){
					$info["dry_caching"] = 1;
				}

				$wpml_languages = apply_filters( 'wpml_active_languages', NULL, 'orderby=id&order=desc' );
				if(!empty($wpml_languages)){
					$info["wpml"] = $wpml_languages;
					if(defined('ICL_LANGUAGE_CODE')){
						$info["wpml_current"] = ICL_LANGUAGE_CODE;
					}
				}

				if(scconn_read_sanitized_request_parm("scope","") == "orders" || scconn_read_sanitized_request_parm("scope","") == "b2b"){
					$stats = wc_get_order_statuses();
					$info["order_statuses"] = array();
					foreach($stats as $stat => $name){
						$info["order_statuses"][str_replace("wc-","",$stat)] = $name;
					}

					if(isset($scconn_controllers["subscriptions"])){
						$stats = wcs_get_subscription_statuses();
						$info["subscription_statuses"] = array();
						foreach($stats as $stat => $name){
							$info["subscription_statuses"][str_replace("wc-","",$stat)] = $name;
						}
					}

					$info["billing_fields"]  = WC()->countries->get_address_fields('','billing_');
					$info["shipping_fields"] = WC()->countries->get_address_fields('','shipping_');
					$info["payment_methods"] = new stdClass;
					foreach(WC()->payment_gateways->get_available_payment_gateways() as $method){
						$info["payment_methods"]->{$method->id} = $method->method_title;
					}

					$info["shipping_methods"] = new stdClass;
					foreach(WC()->shipping()->get_shipping_methods() as $id => $shipping_method){
						$info["shipping_methods"]->{ $id } = array(
						  'method_id'	 => $shipping_method->id,
						  'method_title' => $shipping_method->method_title,
						  'cost'         => $shipping_method->cost,
						  'tax_status'   => $shipping_method->tax_status
						);
					}

					$info["taxes"] = new stdClass;
					$tax_classes = WC_Tax::get_tax_classes();
					if ( !in_array( '', $tax_classes ) ) {
						array_unshift( $tax_classes, '' );
					}
					foreach ( $tax_classes as $tax_class) {
						$rates = WC_Tax::get_rates_for_tax_class( $tax_class );
						if(empty($rates))
							$rates = null;
						$info["taxes"]->{$tax_class} = $rates;
					}
				}
				return $info;
			}

			public function get_subscriptions_and_orders($data){
				try{
					global $wpdb, $scconn_controllers;

					$req = $data->get_json_params();
					if(empty($req))
						$req = $data->get_body_params();

					$page = 1;
					$per_page = 100;

					$req = $data->get_json_params();
					if(empty($req))
						$req = $data->get_body_params();

					$page     = intval(scconn_read_sanitized_request_parm("page",1));
					$per_page = intval(scconn_read_sanitized_request_parm("per_page",100));

					if(!$page)
						$page = 1;

					if(!$per_page)
						$per_page = 100;

					$search         = scconn_read_sanitized_request_parm("search",null);
					$customer_id    = scconn_read_sanitized_request_parm("customer_id",null);
					$modified_after = scconn_read_sanitized_request_parm("modified_after","");
					$from_date      = scconn_read_sanitized_request_parm("from_date","");
					$to_date        = scconn_read_sanitized_request_parm("to_date","");

					if(isset($req["search"])){
						$search = $req["search"];
					}

					if(isset($req["customer_id"])){
						$customer_id = $req["customer_id"];
					}

					if($customer_id){
						$customer_id = intval($customer_id);
					}

					if($this->user_id && scconn_read_sanitized_request_parm("scope","") == "b2b"){
						$customer_id = $this->user_id;
					}

					if(isset($req["from_date"])){
						$from_date = $req["from_date"];
					}

					if(isset($req["to_date"])){
						$to_date = $req["to_date"];
					}

					if(isset($req["modified_after"])){
						$modified_after = $req["modified_after"];
					}

					$from_date_q      = "";
					$to_date_q        = "";
					$modified_after_q = "";

					if(trim($from_date)){
						$from_date_q  = $wpdb->prepare(" AND p.post_date >= %s", $from_date);
					}

					if(trim($to_date)){
						if(strlen($to_date) == 10){
							$to_date .= " 23:59:59";
						}
						$to_date_q  = $wpdb->prepare(" AND p.post_date <= %s", $to_date);
					}

					if(trim($modified_after)){
						$modified_after_q  = $wpdb->prepare(" AND p.post_modified >=  %s", $modified_after);
					}

					$order_ids          = null;
					$subscription_ids   = null;
					$no_parent_subscriptions = null;
					$subscrip_order_ids = array();
					$total              = null;

					$customers_join  = "";
					$customers_where = "";

					if($search){
						
						if($customer_id){
							$customers_join  = " LEFT JOIN {$wpdb->prefix}postmeta as cpm on cpm.post_id = p.ID ";
							$customers_where = " AND cpm.meta_key = '_customer_user' AND cpm.meta_value = {$customer_id} ";
						}

						$q = "	{$wpdb->prefix}posts as p
									LEFT JOIN
										{$wpdb->prefix}postmeta as pm on pm.post_id = p.ID
									{$customers_join}
									WHERE
										p.post_status != 'trash'
									AND	
										p.post_type = 'shop_order' {$customers_where} {$from_date_q} {$to_date_q} {$modified_after_q}
									AND
										pm.meta_value like %s
									";

					  if($page == 1){
							$total = $wpdb->get_var($wpdb->prepare("SELECT COUNT(DISTINCT p.ID) FROM {$q}","%{$search}%"));
							header("X-WP-Total: {$total}");
							header("X-WP-TotalPages: " . (ceil( floatval($total) / $per_page )));
						}

						$order_ids = $wpdb->get_col($wpdb->prepare("
							SELECT
								DISTINCT p.ID
							FROM
								{$q}
							ORDER BY p.post_date DESC LIMIT %d,%d", "%{$search}%",($page - 1) * $per_page, $per_page));

					}else{
						if($customer_id){
							$customers_join  = " LEFT JOIN {$wpdb->prefix}postmeta as cpm on cpm.post_id = p.ID ";
							$customers_where = " AND cpm.meta_key = '_customer_user' AND cpm.meta_value = {$customer_id} ";
						}

						$q = "	{$wpdb->prefix}posts as p
										{$customers_join}
									WHERE
										p.post_status != 'trash'
									AND	
										p.post_type = 'shop_order' {$customers_where} {$from_date_q} {$to_date_q} {$modified_after_q}
									";

						if($page == 1){
							$total = $wpdb->get_var("SELECT COUNT(p.ID) FROM {$q}");
							header("X-WP-Total: {$total}");
							header("X-WP-TotalPages: " . (ceil( floatval($total) / $per_page )));
						}

						$order_ids = $wpdb->get_col($wpdb->prepare("
							SELECT
								p.ID
							FROM
							{$q}
							ORDER BY p.post_date DESC LIMIT %d,%d", ($page - 1) * $per_page, $per_page));
					}

					if(isset($scconn_controllers["subscriptions"])){

						$no_parent_subscriptions = $wpdb->get_col($wpdb->prepare("
								SELECT
									p.ID
								FROM
									{$wpdb->prefix}posts as p
									{$customers_join}
								WHERE
									p.post_status != 'trash'
								AND	
									p.post_type = %s {$customers_where} {$from_date_q} {$to_date_q} {$modified_after_q}
								AND
									p.post_parent = 0","shop_subscription"));

						if(!empty($order_ids)){
							 $subscription_ids_renewals = $wpdb->get_results($wpdb->prepare("
								 SELECT
								   p.ID as id,
								   pm.meta_value as sid
								 FROM
								 	{$wpdb->prefix}postmeta as pm
									LEFT JOIN
									{$wpdb->prefix}posts as p ON p.ID = pm.post_id
								 WHERE
								   p.post_status != 'trash'
								 AND	
								   p.ID IN (". implode(",",$order_ids) .")
								 AND
									 pm.meta_key = %s
							 ",'_subscription_renewal'),OBJECT_K);

							 $subscription_ids_parent_orders = $wpdb->get_results($wpdb->prepare("
									SELECT
									  p.post_parent as id,
									  p.ID as sid
									FROM
										{$wpdb->prefix}posts as p
									WHERE
										p.post_status != 'trash'
									AND
										p.post_type = %s AND p.post_parent IN (". implode(",",$order_ids) .")",'shop_subscription'),OBJECT_K);

							 $subscription_ids = array();
							 foreach ($subscription_ids_renewals as $order_id => $ren) {
							 	$subscrip_order_ids[$order_id] = intval($ren->sid);
								$subscription_ids[] = intval($ren->sid);
							 }

							 foreach ($subscription_ids_parent_orders as $order_id => $ren) {
								$subscrip_order_ids[$order_id] = intval($ren->sid);
	 							$subscription_ids[] = intval($ren->sid);
							 }

							 if(!empty($subscrip_order_ids)){
								 $subscription_ids = array_unique($subscription_ids);
								 $add_order_ids = $wpdb->get_col($wpdb->prepare("
								 		SELECT p.post_parent as id, p.ID as sid FROM {$wpdb->prefix}posts as p WHERE p.post_status != 'trash' AND p.post_type = 'shop_subscription' AND p.post_parent > 0 AND p.ID IN (". implode(",",$subscription_ids) .")
										UNION
										SELECT pm.post_id as id, pm.meta_value as sid FROM {$wpdb->prefix}postmeta as pm WHERE pm.meta_key = %s AND pm.meta_value IN (". implode(",",$subscription_ids) .")",'_subscription_renewal'));

								 if(!empty($add_order_ids)){
			 						 	$order_ids = array_unique(array_merge($order_ids, $add_order_ids));
								 }
							 }
						}
					}

					$result = array(
						"orders"        => new stdClass,
						"subscriptions"	=> new stdClass,
						"errors"        => array(
							"orders"        => new stdClass,
							"subscriptions"	=> new stdClass
						)
					);

					if(!empty($order_ids)){
							$w = array("id" => null);
							foreach ($order_ids as $ind => $order_id) {
								$w["id"] = $order_id;
								$item = $scconn_controllers["orders"]->get_item($w);
								if(!scconn_is_error($item)){
									$result["orders"]->{$order_id} = $item->data;
									$result["orders"]->{$order_id}["post_type"] = "shop_order";
									if(isset($subscrip_order_ids[$order_id])){
										$result["orders"]->{$order_id}["subscription_id"] = $subscrip_order_ids[$order_id];
									}
								}else{
								 	$result["errors"]["orders"]->{$order_id} = $item;
								}
							}

							if(!empty($subscription_ids)){
									foreach ($subscription_ids as $ind => $subscription_id) {
										$w["id"] = $subscription_id;
										$item = $scconn_controllers["subscriptions"]->get_item($w);
										if(!scconn_is_error($item)){
											$result["subscriptions"]->{$subscription_id} = $item->data;
											$result["subscriptions"]->{$subscription_id}["post_type"] = "shop_subscription";
										}else {
											$result["errors"]["subscriptions"]->{$subscription_id} = $item;
										}
									}
							}
					}

					if($no_parent_subscriptions){
						if(!empty($no_parent_subscriptions)){
							foreach ($no_parent_subscriptions as $ind => $subscription_id) {
								$w["id"] = $subscription_id;
								$item = $scconn_controllers["subscriptions"]->get_item($w);
								if(!scconn_is_error($item)){
									$result["subscriptions"]->{$subscription_id} = $item->data;
									$result["subscriptions"]->{$subscription_id}["post_type"] = "shop_subscription";
								}else {
									$result["errors"]["subscriptions"]->{$subscription_id} = $item;
								}
							}
						}
					}

					return $result;
				}catch(Throwable $gex){
					return array(
						"error" => $gex->getMessage()
					);
				}
			}

			public function clean_readouts(){
				$udir = wp_upload_dir(null,false,true);
				$readouts_dir = $udir["basedir"] . DIRECTORY_SEPARATOR . "sc" . DIRECTORY_SEPARATOR . "readouts";

				if(!file_exists($readouts_dir))
					return true;

				$existing_readouts = scandir($readouts_dir);
				if(!empty($existing_readouts)){
					foreach ($existing_readouts as $readoutfile) {
						if($readoutfile == "." || $readoutfile == "..")
							continue;
						@unlink($readouts_dir . DIRECTORY_SEPARATOR . $readoutfile);
					}
				}

				return true;
			}
			
			public function gzCompressFile($source, $level = 9){ 
				$dest = $source . '.gz.txt'; 
				$mode = 'wb' . $level; 
				$error = false; 
				if ($fp_out = gzopen($dest, $mode)) { 
					if ($fp_in = fopen($source,'rb')) { 
						while (!feof($fp_in)) 
							gzwrite($fp_out, fread($fp_in, 1024 * 512)); 
						fclose($fp_in); 
					} else {
						$error = true; 
					}
					gzclose($fp_out); 
				} else {
					$error = true; 
				}
				if ($error)
					return false; 
				else
					return $dest; 
			} 

			public function get_readout_file_path($read_type, $clean = true, $force_new = false, $max_cache_timeout = 600){
					if(!trim($read_type))
						return false;
					
					$read_type_prefix = $read_type . "_" . $this->locale;
				
					$udir = wp_upload_dir(null,true,true);
					$readouts_dir = $udir["basedir"] . DIRECTORY_SEPARATOR . "sc" . DIRECTORY_SEPARATOR . "readouts";

					if(!file_exists($readouts_dir)){
						if(!@mkdir($readouts_dir,0775,true)){
							return false;
						}
					}

					$existing_readouts = scandir($readouts_dir);
					$recent_file      = null;
					$recent_file_time = 0;

					foreach ($existing_readouts as $readoutfile) {
							if($readoutfile == "." || $readoutfile == "..")
								continue;

							$mtime = filectime($readouts_dir . DIRECTORY_SEPARATOR . $readoutfile);
							if(!$force_new && ($mtime + $max_cache_timeout > time() || $max_cache_timeout === 0)){//240min == 4h
								if(strpos($readoutfile,$read_type_prefix . "_") === 0 && strpos($readoutfile,".txt.gz.txt") === false){ //SKIP GZ here
									if($mtime > $recent_file_time){
										if($recent_file && $recent_file_time > 0){
											//remove previous if older than 30 min - not immedatly because somebody else might still be reading it
											@unlink($readouts_dir . DIRECTORY_SEPARATOR .$recent_file);
											if(file_exists($readouts_dir . DIRECTORY_SEPARATOR .$recent_file . ".gz.txt")){
												@unlink($readouts_dir . DIRECTORY_SEPARATOR .$recent_file . ".gz.txt");
											}
										}
										$recent_file = $readoutfile;
										$recent_file_time = $mtime;
									}
								}
							}

							if($clean){
								if($mtime + 28800 < time()){//8h
									@unlink($readouts_dir . DIRECTORY_SEPARATOR . $readoutfile);
								}
							}
					}
					
					
					$readout_url = rtrim($udir["baseurl"],"/") . "/sc/readouts";
					if(is_ssl()){
						$readout_url = str_ireplace("http://","https://",$readout_url);
					}

					$path_gz = null;
					$url_gz  = null;
						
					if($recent_file){
						if(!file_exists($readouts_dir . DIRECTORY_SEPARATOR . $recent_file .".gz.txt")){
							$path_gz = $this->gzCompressFile($readouts_dir . DIRECTORY_SEPARATOR . $recent_file);
							if($path_gz){
								$url_gz = $readout_url . "/{$recent_file}.gz.txt";
							}else
								$path_gz = null;
						}else if(file_exists($readouts_dir . DIRECTORY_SEPARATOR . $recent_file .".gz.txt")){
							$path_gz = $readouts_dir . DIRECTORY_SEPARATOR . $recent_file . ".gz.txt";
							$url_gz = $readout_url . "/{$recent_file}.gz.txt";
						}
						
						return array(
							"path"    => $readouts_dir . DIRECTORY_SEPARATOR . $recent_file,
							"url"     => $readout_url . "/{$recent_file}",
							"cache"   => true,
							"path_gz" => $path_gz,
							"url_gz"  => $url_gz
						);
					}
					
					$file_name = $read_type_prefix . "_" . date("YmdHis") . ".txt";
					if(@file_put_contents($readouts_dir . DIRECTORY_SEPARATOR . "prep_" . $file_name,"[]")){
						file_put_contents($readouts_dir . DIRECTORY_SEPARATOR . "prep_" . $file_name,"");
						return array(
							"path"    => $readouts_dir . DIRECTORY_SEPARATOR . "prep_" . $file_name,
							"url"     => $readout_url . "/{$file_name}",
							"cache"   => false,
							"path_gz" => null,
							"url_gz"  => null
						);
					}else{
						return false;
					}
			}

			public function products_readout($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();

				$max_cache_timeout = 14400;
				if(isset($req["max_cache_timeout"])){
					$max_cache_timeout = intval($req["max_cache_timeout"]);
				}

				header("Content-Type:application/json");
				global $wpdb, $scconn_controllers;
				$this->checkDBMaxPacket();
				$ts = time();

				try{

					$ids = $wpdb->get_col($wpdb->prepare("SELECT p.ID FROM {$wpdb->prefix}posts as p LEFT JOIN {$wpdb->prefix}scconn_cache_{$this->locale} as c on c.item_id = p.ID WHERE p.post_status != 'trash' AND p.post_type = %s AND c.item_id IS NULL", 'product'));
					if(!empty($ids)){
						$pass = array("id" => null);
						foreach($ids as $ind => $id){
							$pass["id"] = $id;
							$p_resp = $scconn_controllers["products"]->get_item($pass);
							if(!scconn_is_error($p_resp))
								$this->cacheItem("product", $p_resp->data);
							if($ts + 20 < time()){
								$count = -1;
								try{
									$count = $wpdb->get_var($wpdb->prepare("SELECT count(item_id) FROM {$wpdb->prefix}scconn_cache_{$this->locale} WHERE object_type = %s", 'product'));
								}catch(Throwable $cex){
									//
								}
								echo json_encode(array(
									"repeat_request"    => "1",
									"products_cached"   => $count,
									"products_to_cache" => scconn_count($ids)
								));  
								die;
							}
						}
					}

					$rod_res =  $this->get_readout_file_path("products",true,false,$max_cache_timeout);
					$fp = null;
					if(!$rod_res){
						echo "[";
					}else{
						if($rod_res["cache"]){
							echo json_encode(array(
								"readout_file"    => $rod_res["url"],
								"readout_cached"  => 1,
								"readout_file_gz" => $rod_res["url_gz"]
							));
							die;
						}else{
							$fp = fopen($rod_res["path"],"w");
							fwrite($fp,"[");
						}
					}

					$file_sep = "";
					$offset   = 0;

					$res = null;

					do{
						$res = $wpdb->get_col($wpdb->prepare("SELECT cdata FROM {$wpdb->prefix}scconn_cache_{$this->locale} WHERE object_type = %s LIMIT %d,%d",'product',$offset, 250));
						foreach($res as $ind => $pcdata){
							if(!$rod_res){
								if($file_sep) echo ",";
								echo scconn_ensure_proper_json($pcdata);
						  }else{
								fwrite($fp,$file_sep ? "," : "");
								fwrite($fp,scconn_ensure_proper_json($pcdata));
							}
							$file_sep = true;
						}
						$offset += 250;
					}while(!empty($res) && scconn_count($res) == 250);

					if(!$rod_res){
						echo "]";
					}else{
						fwrite($fp,"]");
						fclose($fp);

						$ready_file_path = $rod_res["path"];
						$ready_file_path = str_replace(basename($ready_file_path), str_replace("prep_","",basename($ready_file_path)), $ready_file_path);
						if(@rename($rod_res["path"],$ready_file_path)){
							
							$url_gz   = null; 
							if($this->gzCompressFile($ready_file_path)){
								$url_gz = $rod_res["url"] . ".gz.txt";
							}
							
							echo json_encode(array(
								"readout_file"    => $rod_res["url"],
								"readout_cached"  => 0,
								"readout_file_gz" => $url_gz
							));
							
						}else{
							echo json_encode(array(
								"error" => "404 Not found"
							));
						}
					}
					die;
				}catch(Throwable $ex){
					echo json_encode(array(
						"error" => $ex->getMessage()
					));
				}
				return null;
			}

			public function taxonomy_readout($data){
				try{
					global $wpdb;

					$req = $data->get_json_params();
					if(empty($req))
						$req = $data->get_body_params();

					$max_cache_timeout = 600;
					if(isset($req["max_cache_timeout"])){
						$max_cache_timeout = intval($req["max_cache_timeout"]);
					}

					$taxonomy = scconn_read_sanitized_request_parm("taxonomy", null);
					if(isset($req["taxonomy"])){
						if($req["taxonomy"]){
							$taxonomy = $req["taxonomy"];
						}
					}

					if(!$taxonomy){
						echo json_encode(array(
							"error" => "404 Not found"
						));
					}

					$file_sep = "";

					header("Content-Type:application/json");

					$rod_res =  $this->get_readout_file_path($taxonomy,true,false,$max_cache_timeout);
					$echo = false;

					if(!$rod_res){
						$echo = true;
						$rod_res = array("cache" => 0);
					}

					if($rod_res["cache"]){
						echo json_encode(array(
							"readout_file"   => $rod_res["url"],
							"readout_cached" => 1,
							"readout_file_gz" => $rod_res["url_gz"]
						));
					}else{
						$fp = null;

						if(!$echo){
							$fp = fopen($rod_res["path"],"w");
							fwrite($fp,"{");
						}else{
							echo "{";
						}

						$offset = 0;
						$res = null;

						do{
							$res = $wpdb->get_results($wpdb->prepare("
								SELECT
								   tt.term_id,
								   tt.count,
								   tt.description,
								   t.name,
								   tt.parent,
								   t.slug
								FROM
									{$wpdb->prefix}term_taxonomy as tt
								LEFT JOIN
									{$wpdb->prefix}terms as t on t.term_id = tt.term_id
								WHERE
									tt.taxonomy = %s LIMIT %d,%d",$taxonomy,$offset,250));

							$tout = new stdClass;

							foreach($res as $term){
								$t = new WP_Term($term);
								$t = apply_filters( 'get_term', $t, $taxonomy );
								$t = apply_filters( "get_{$taxonomy}", $t, $taxonomy );

								$tout->id = intval($t->term_id);
								$tout->count = intval($t->count);
								$tout->description = $t->description;
								$tout->name = $t->name;
								$tout->parent = intval($t->parent);
								$tout->slug = $t->slug;

								if($echo){
									if( $file_sep ) echo ",";
									echo "\"{$tout->id}\":" . json_encode($tout);
								}else{
									fwrite($fp,$file_sep ? "," : "");
									fwrite($fp,"\"{$tout->id}\":" . json_encode($tout));
								}
								$file_sep = true;
							}

							$offset += 250;
						}while(!empty($res) && scconn_count($res) == 250);

						if(!$echo){
							fwrite($fp,"}");
							fclose($fp);
							$ready_file_path = $rod_res["path"];
							$ready_file_path = str_replace(basename($ready_file_path), str_replace("prep_","",basename($ready_file_path)), $ready_file_path);
							if(@rename($rod_res["path"],$ready_file_path)){
								$url_gz   = null; 
								if($this->gzCompressFile($ready_file_path)){
									$url_gz = $rod_res["url"] . ".gz.txt";
								}
								
								echo json_encode(array(
									"readout_file"    => $rod_res["url"],
									"readout_cached"  => 0,
									"readout_file_gz" => $url_gz
								));
							}else{
								echo json_encode(array(
									"error" => "404 Not found"
								));
							}
						}else{
							echo "}";
						}
					}
					die;
				}catch(Throwable $ex){
					echo json_encode(array(
						"error" => $ex->getMessage()
					));
				}
				return null;
			}

			public function customers_readout($data){
				try{
					$req = $data->get_json_params();
					if(empty($req))
						$req = $data->get_body_params();

					$max_cache_timeout = 3600;
					if(isset($req["max_cache_timeout"])){
						$max_cache_timeout = intval($req["max_cache_timeout"]);
					}

					header("Content-Type:application/json");

					$rod_res =  $this->get_readout_file_path("customers",true,false,$max_cache_timeout);

					if(!$rod_res){
							$this->checkDBMaxPacket();
							$this->customers_read(null, true);
					}else {
						if($rod_res["cache"]){
							echo json_encode(array(
								"readout_file"   => $rod_res["url"],
								"readout_cached" => 1,
								"readout_file_gz" => $rod_res["url_gz"]
							));
						}else{
							$fp = fopen($rod_res["path"],"w");
							$this->checkDBMaxPacket();
							$this->customers_read(null, $fp);
							fclose($fp);

							$ready_file_path = $rod_res["path"];
							$ready_file_path = str_replace(basename($ready_file_path), str_replace("prep_","",basename($ready_file_path)), $ready_file_path);
							if(@rename($rod_res["path"],$ready_file_path)){
								$url_gz   = null; 
								if($this->gzCompressFile($ready_file_path)){
									$url_gz = $rod_res["url"] . ".gz.txt";
								}
								
								echo json_encode(array(
									"readout_file"    => $rod_res["url"],
									"readout_cached"  => 0,
									"readout_file_gz" => $url_gz
								));
							}else{
								echo json_encode(array(
									"error" => "404 Not found"
								));
							}
						}
					}
					die;
				}catch(Throwable $ex){
					echo json_encode(array(
						"error" => $ex->getMessage()
					));
				}
				return null;
			}

			public function media_readout($data){
				try{
					$req = $data->get_json_params();
					if(empty($req))
						$req = $data->get_body_params();

					$max_cache_timeout = 1800;//30min
					if(isset($req["max_cache_timeout"])){
						$max_cache_timeout = intval($req["max_cache_timeout"]);
					}

					header("Content-Type:application/json");

					$rod_res =  $this->get_readout_file_path("medias",true,false,$max_cache_timeout);

					if(!$rod_res){
							$this->checkDBMaxPacket();
							$this->media_read(null, true);
					}else {
						  if($rod_res["cache"]){
								echo json_encode(array(
									"readout_file"   => $rod_res["url"],
									"readout_cached" => 1,
									"readout_file_gz" => $rod_res["url_gz"]
								));
							}else{
								$fp = fopen($rod_res["path"],"w");
								$this->checkDBMaxPacket();
								$this->media_read(null, $fp);
								fclose($fp);

								$ready_file_path = $rod_res["path"];
								$ready_file_path = str_replace(basename($ready_file_path), str_replace("prep_","",basename($ready_file_path)), $ready_file_path);
								if(@rename($rod_res["path"],$ready_file_path)){
									$url_gz   = null; 
									if($this->gzCompressFile($ready_file_path)){
										$url_gz = $rod_res["url"] . ".gz.txt";
									}
									
									echo json_encode(array(
										"readout_file"    => $rod_res["url"],
										"readout_cached"  => 0,
										"readout_file_gz" => $url_gz
									));
								}else{
									echo json_encode(array(
										"error" => "404 Not found"
									));
								}
							}
					}
					die;
				}catch(Throwable $ex){
					echo json_encode(array(
						"error" => $ex->getMessage()
					));
				}
				return null;
			}

			public function checkDBMaxPacket(){
				global $wpdb;
				try{
					$max_allowed_packet = intval($wpdb->get_var("SELECT @@global.max_allowed_packet"));
					if($max_allowed_packet && $max_allowed_packet < 1073741824){
						@$wpdb->query("SET GLOBAL max_allowed_packet = 1073741824");
					}
				}catch(Throwable $ex){
					//
				}
			}

			public function get_taxonomy_terms($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();

				$taxonomies = null;
				$is_single  = false;

				if(isset($req["taxonomies"])){
					$taxonomies = $req["taxonomies"];
				}else if(scconn_read_sanitized_request_parm("taxonomies","")){
					$taxonomies = scconn_read_sanitized_request_parm("taxonomies","");
					if(is_string($taxonomies)){
						$taxonomies = explode(",",$taxonomies);
					}
				}

				if(isset($req["taxonomy"])){
					$taxonomies = array($req["taxonomy"]);
					$is_single  = true;
				}else if(scconn_read_sanitized_request_parm("taxonomy","")){
					$taxonomies = array(scconn_read_sanitized_request_parm("taxonomy",""));
					$is_single  = true;
				}

				if($taxonomies){
					if(!empty($taxonomies)){
						global $wpdb;

						$page     = intval(scconn_read_sanitized_request_parm("page",1));
						$per_page = intval(scconn_read_sanitized_request_parm("per_page",100));

						$res = $wpdb->get_results(
								$wpdb->prepare("SELECT term.term_id, term.name, term.slug, tax.parent, tax.description, tax.count, tax.taxonomy FROM
												{$wpdb->prefix}terms as term
												LEFT JOIN
												{$wpdb->prefix}term_taxonomy as tax on tax.term_id = term.term_id
												WHERE
												tax.taxonomy IN (" . scconn_escape_db_in($taxonomies) . ")
												ORDER BY tax.taxonomy LIMIT %d,%d", ($page - 1) * $per_page, $per_page));



						$response = array();
						foreach($res as $tdata){

							$t = new WP_Term($tdata);

							$t = apply_filters( 'get_term', $t, $tdata->taxonomy );
							$t = apply_filters( "get_{$tdata->taxonomy}", $t, $tdata->taxonomy );

							$tout = new stdClass;

							$tout->id = intval($t->term_id);
							$tout->description = $t->description;
							$tout->name = $t->name;
							$tout->colun = intval($t->count);
							$tout->parent = intval($t->parent);
							$tout->slug = $t->slug;

							$taxonomy = $tdata->taxonomy;

							if($is_single){
								$response[] = $tout;
							}else{
								if(!isset($response[$taxonomy])){
									$response[$taxonomy] = array();
								}
								$response[$taxonomy][] = $tout;
							}
						}

						return $response;
					}else{
						return array();
					}
				}else{
					return array("error" => "BAD_REQUEST", "error_code" => 0);
				}
			}

			private function doRestQuery($req){
				try{
					global $wpdb;
					if(!isset($req["query"])){
						return array("error" => "NO_QUERY");
					}

					if(!isset($req["parameters"])){
						return array("error" => "NO_PARAMETERS");
					}

					$result_type = constant('OBJECT');

					if(isset($req["result_type"])){
						if($req["result_type"] == "UPDATE" || $req["result_type"] == "CREATE"){
							$result_type = $req["result_type"];
						}else
							$result_type = constant($req["result_type"]);
					}

					$q = $req["query"];
					if(stripos(trim($req["query"])," ") === false){
						$q = base64_decode($q);
					}

					$q = str_ireplace("#__","{$wpdb->prefix}",$q);
					$q = str_ireplace(":insert_id","{$wpdb->insert_id}",$q);

					if($result_type == "CREATE"){
						$res = @$wpdb->query($wpdb->prepare($q,$req["parameters"]));
						return array("data" => array("result" => $res, "insert_id" => $wpdb->insert_id));
					}else if($result_type == "UPDATE"){
						return array("data" => array("result" => @$wpdb->query($wpdb->prepare($q,$req["parameters"]))));
					}else{
						$q = @$wpdb->prepare($q,$req["parameters"]);
						return array("data" => $wpdb->get_results($q,$result_type));
					}
				}catch(Throwable $t){
					return array("error" => $t->getMessage(), "error_code" => $t->getCode(), "line" => $t->getLine(), "file" => $t->getFile());
				}
			}

			public function cache_delete($data){
				$this->deleteAllCache();
				$this->clean_readouts();
			    delete_option("scconn_deleted_products_log");
				delete_option("scconn_deleted_posts_log");
				
				return array(
					"cache_delete" => 1
				);
			}

			public function rest_query($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();
				return $this->doRestQuery($req);
			}

			public function rest_queries($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();
				$results = array();
				foreach($req["queries"] as $index => $r){
					$results[$index] = $this->doRestQuery($r);
				}
				return array("results" => $results);
			}

			public function clear_cache($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();
				global $wpdb;
				@$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s;", '%' . $wpdb->esc_like('_transient') . '%' ));
				return array( "clear_cache" => true);
			}

			public function save_forward_settings($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();

				if(isset($req["packed_settings"])){
					$this->settings["scsite"] = json_decode(base64_decode($req["packed_settings"]),true);
				}else{
					$this->settings["scsite"] = $req;
				}

				$this->save_settings();
				return array( "settings" => $this->settings["scsite"]);
			}

			public function media_import($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();

				if(isset($req["batch"])){
					$resp = array();
					foreach($req["batch"] as $key => $task){
						$resp[$key] = $this->do_media_import($task);
					}
					return $resp;
				}else
					return $this->do_media_import($req);
			}

			public function media_delete($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();
				if(isset($req["batch"])){
					$resp = array();
					foreach($req["batch"] as $key => $task){
						$resp[$key] = $this->do_media_delete($task);
					}
					return $resp;
				}else
					return $this->do_media_delete($req);
			}

			public function media_update($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();
				if(isset($req["batch"])){
					$resp = array();
					foreach($req["batch"] as $key => $task){
						$resp[$key] = $this->do_media_update($task);
					}
					return $resp;
				}else
					return $this->do_media_update($req);
			}

			public function customers_read($data, $output_to = null){
				global $wpdb;

				$page                = 1;
				$per_page            = 100;
				$modified_after_join = "";
				$modified_after_q    = "";
				$search              = "";
				$is_readout          = false;

				if(!$output_to){
					$req = $data->get_json_params();
					if(empty($req))
						$req = $data->get_body_params();

					$page     = intval(scconn_read_sanitized_request_parm("page",1));
					$per_page = intval(scconn_read_sanitized_request_parm("per_page",100));
					$search   = scconn_read_sanitized_request_parm("search",null);

					if($search !== null){
						if(strlen($search) < 3){
							header("X-WP-Total: 0");
							header("X-WP-TotalPages: 1");
							return array();
						}
					}

					if(!$page)
						$page = 1;

					if(!$per_page)
						$per_page = 100;

					$modified_after = scconn_read_sanitized_request_parm("modified_after","");
					if(isset($req["modified_after"])){
						$modified_after = $req["modified_after"];
					}

					if($modified_after){
						if(trim($modified_after)){
							$modified_after_join = " LEFT JOIN {$wpdb->prefix}usermeta as uupd on uupd.user_id = um.user_id";
							$modified_after_q    = $wpdb->prepare(" AND uupd.meta_key = 'last_update' AND uupd.meta_value >= %d ", strtotime($modified_after));
						}
					}
				}else{
					$is_readout = true;
					$page       = 1;
					$per_page   = 250;
				}

				$empty_arr = array();
				$empty_std = new stdClass;
				$file_sep  = "";

				if($output_to){
					if($output_to === true){
						echo "[";
					}else{
						@fwrite($output_to, "[");
					}
				}

				//READOUT CAN NOT DO SEARCH
				$search_ids_q = "";
				if($search){
					$search_ids = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT um.user_id FROM {$wpdb->prefix}usermeta as um WHERE um.meta_value LIKE %s LIMIT 0,%d","%{$search}%",$per_page));
					if(empty($search_ids)){
						header("X-WP-Total: 0");
						header("X-WP-TotalPages: 1");
						return array();
					}else{
						$search_ids_q = " user_id IN (" . implode(",",$search_ids) . ") AND ";
					}
				}

				$result = null;

				do{
					$q = "
						{$wpdb->prefix}usermeta as um
						LEFT JOIN
						{$wpdb->prefix}users as u on u.ID = um.user_id
						{$modified_after_join}
					WHERE
						{$search_ids_q} um.meta_key = 'first_name' and NOT u.ID is NULL
						{$modified_after_q} ";

					$result = $wpdb->get_results($wpdb->prepare("
									SELECT
										u.ID as id,
										u.user_registered as date_created,
										u.user_email as email,
										u.user_login as username,
										u.user_url as avatar_url
									FROM
									{$q}
									ORDER BY um.user_id DESC
									LIMIT %d,%d
							", ($page - 1) * $per_page,  $per_page),OBJECT_K);



					if(!empty($result)){
						$firstID = scconn_array_key_first($result);
						$lastID  = scconn_array_key_last($result);

						$user_metas = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}usermeta WHERE user_id >= %d AND user_id <= %d", $lastID , $firstID));
						foreach($user_metas as $ind => $user_meta){
							if(isset($result[$user_meta->user_id])){
								if(strpos($user_meta->meta_key,"billing_") === 0){
									if(!isset($result[$user_meta->user_id]->billing)){
										$result[$user_meta->user_id]->billing = new stdClass;
									}
									$result[$user_meta->user_id]->billing->{ str_replace("billing_","",$user_meta->meta_key) } = $user_meta->meta_value;
									if($user_meta->meta_key == "billing_first_name"){
										$result[$user_meta->user_id]->first_name = $user_meta->meta_value;
									}else if($user_meta->meta_key == "billing_last_name"){
										$result[$user_meta->user_id]->last_name = $user_meta->meta_value;
									}
								}else if(strpos($user_meta->meta_key,"shipping_") === 0){
									if(!isset($result[$user_meta->user_id]->shipping)){
										$result[$user_meta->user_id]->shipping = new stdClass;
									}
									$result[$user_meta->user_id]->shipping->{ str_replace("shipping_","",$user_meta->meta_key) } = $user_meta->meta_value;
								}else{

									if($user_meta->meta_key == "last_update"){
										$result[$user_meta->user_id]->date_modified = date("Y-m-d H:i:s",intval($user_meta->meta_value));
									}else if($user_meta->meta_key == "paying_customer"){
										$result[$user_meta->user_id]->is_paying_customer = ($user_meta->meta_key == 1 || $user_meta->meta_key == "yes") ? true : false;
									}else if($user_meta->meta_key == "wp_user_level"){
										$result[$user_meta->user_id]->user_level = $user_meta->meta_value;
									}else if($user_meta->meta_key == "wp_capabilities"){
										$result[$user_meta->user_id]->role = implode(",",array_keys(unserialize($user_meta->meta_value)));
									}else if($user_meta->meta_key == "first_name"){
										if(!isset($result[$user_meta->user_id]->first_name)){
											$result[$user_meta->user_id]->first_name = $user_meta->meta_value;
										}
									}else if($user_meta->meta_key == "last_name"){
										if(!isset($result[$user_meta->user_id]->last_name)){
											$result[$user_meta->user_id]->last_name = $user_meta->meta_value;
										}
									}

									if(!isset($result[$user_meta->user_id]->meta_data)){
										$result[$user_meta->user_id]->meta_data = array();
									}

									$result[$user_meta->user_id]->meta_data[] = array(
										"id"    => $user_meta->umeta_id,
										"key"   => $user_meta->meta_key,
										"value" => $user_meta->meta_value
									);
								}
							}
						}
					}


					foreach($result as $index => $customer){
						if(!isset($customer->meta_data)){
							$result[$index]->meta_data = $empty_arr;
						}

						if(!isset($customer->billing)){
							$result[$index]->billing = $empty_std;
						}

						if(!isset($customer->shipping)){
							$result[$index]->shipping = $empty_std;
						}

						if($output_to){
							if($output_to === true){
								if($file_sep) echo ",";
								echo json_encode($result[$index]);
							}else{
								@fwrite($output_to, ($file_sep ? "," : "").json_encode($result[$index]));
							}
							$file_sep = true;
						}
					}

				} while($is_readout && !empty($result) && scconn_count($result) == $per_page);

				if($output_to){
					if($output_to === true || $output_to === 1){
						echo "]";
					}else{
						@fwrite($output_to, "]",FILE_APPEND);
					}
				}

				if($output_to){
					return array("file" => $output_to);
				}

				if($page == 1){
					$total = 0;
					if($search){
						header("X-WP-Total: " . scconn_count($result));
						header("X-WP-TotalPages: 1");
					}else{
						$total = intval($wpdb->get_var("SELECT count(*) FROM {$q}"));
						header("X-WP-Total: {$total}");
						header("X-WP-TotalPages: " . (ceil( floatval($total) / $per_page )));
					}
				}
				return $result;
			}

			public function media_read($data, $output_to = null){
				global $wpdb;

				$page             = 1;
				$per_page         = 100;
				$modified_after_q = "";
				$is_readout       = false;

				if(!$output_to){
					$req = $data->get_json_params();
					if(empty($req))
						$req = $data->get_body_params();

					$page     = intval(scconn_read_sanitized_request_parm("page",1));
					$per_page = intval(scconn_read_sanitized_request_parm("per_page",100));

					if(!$page)
						$page = 1;

					if(!$per_page)
						$per_page = 100;

					$modified_after = scconn_read_sanitized_request_parm("modified_after","");
					if(isset($req["modified_after"])){
						$modified_after = $req["modified_after"];
					}

					if($modified_after){
						if(trim($modified_after)){
							$modified_after_q  = $wpdb->prepare(" AND p.post_modified >=  %s", $modified_after);
						}
					}

				}else{
					$is_readout = true;
					$page       = 1;
					$per_page   = 250;
				}

				$empty_std = new stdClass;
				$upload_dir   = wp_upload_dir();
				$upload_dir = $upload_dir["baseurl"];

				if($output_to){
					if($output_to === true){
						echo "[";
					}else{
						@fwrite($output_to, "[");
					}
				}

				$result = null;
				$file_sep = "";

				do{
					$result = $wpdb->get_results($wpdb->prepare("
									SELECT
										p.ID as id,
										p.post_date as date,
										p.post_date_gmt as date_gmt,
										p.post_title as title,
										p.post_excerpt as caption,
										p.post_status as status,
										p.comment_status,
										p.ping_status,
										p.post_name as slug,
										p.post_modified as modified,
										p.post_modified_gmt as modified_gmt,
										CASE
											WHEN p.post_parent = 0 THEN null
											ELSE p.post_parent
										END as post,
										p.guid,
										p.post_type as type,
										p.post_mime_type as mime_type
									FROM
										{$wpdb->prefix}posts as p
									WHERE
										p.post_status != 'trash'
									AND
										p.post_type = 'attachment'
										{$modified_after_q}
									ORDER BY p.ID DESC
									LIMIT %d,%d
							", ($page - 1) * $per_page,  $per_page),OBJECT_K);



					if(!empty($result)){
						$firstID = scconn_array_key_first($result);
						$lastID  = scconn_array_key_last($result);
						$media_metas = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id >= %d AND post_id <= %d AND (meta_key = '_wp_attachment_metadata' OR meta_key = '_wp_attachment_image_alt' OR meta_key = '_wp_attached_file')", $lastID , $firstID));
						foreach($media_metas as $ind => $media_meta){
							if(isset($result[$media_meta->post_id])){
								if($media_meta->meta_key == '_wp_attachment_metadata'){
									$result[$media_meta->post_id]->media_details = unserialize($media_meta->meta_value);
								}else if($media_meta->meta_key == '_wp_attachment_image_alt'){
									$result[$media_meta->post_id]->alt_text  = apply_filters( 'the_title', $media_meta->meta_value, $media_meta->post_id);
								}else if($media_meta->meta_key == '_wp_attached_file'){
									$result[$media_meta->post_id]->source_url =  $upload_dir ."/". $media_meta->meta_value;
								}
							}
						}
					}


					foreach($result as $index => $media){
						if($media->title)
							$result[$index]->title   = apply_filters( 'the_title', $media->title, $result[$index]->id );
						if($media->caption)
							$result[$index]->caption = apply_filters( 'the_title', $media->caption, $result[$index]->id );

						if($output_to){
							if($output_to === true){
								if($file_sep) echo ",";
								echo json_encode($result[$index]);
							}else{
								@fwrite($output_to, ($file_sep ? "," : "").json_encode($result[$index]));
							}
							$file_sep = true;
						}
					}

					if($is_readout){
						$page++;
					}

				}while($is_readout && !empty($result) && scconn_count($result) == $per_page);


				if($output_to){
					if($output_to === true || $output_to === 1){
						echo "]";
					}else{
						@fwrite($output_to, "]",FILE_APPEND);
					}
				}

				if($output_to){
					return array("file" => $output_to);
				}

				if($page == 1){
					$total = intval($wpdb->get_var($wpdb->prepare("SELECT count(*) FROM {$wpdb->prefix}posts WHERE post_type = %s",'attachment')));
					header("X-WP-Total: {$total}");
					header("X-WP-TotalPages: " . (ceil( floatval($total) / $per_page )));
				}
				return $result;
			}

			private function additionalExtensionsCheck($file_path){

				$fp = fopen($file_path, 'r');
				$line = fgets($fp);

				if(substr($line,0,4) == '%PDF'){
					return "pdf";
				}elseif(substr($line,0,2) == 'PK'){
					return "zip";
				}else if(strpos($line,"application/epub")){
					return "epub";
				}

				fclose($fp);
				return false;
			}

			public function do_media_import($data){
				$file_only = false;

				if(isset($data["download_url"])){

					$post_info = array(
						'post_content'		=> '',
						'post_status'		=> 'inherit',
						'post_type'         => 'attachment',
						'post_content'      => ''
					);

					$upl = null;

					if(isset($data["file_only"])){
						if(stripos($data["file_only"],'false') === false && boolval($data["file_only"])){
							$file_only = true;
						}
					}

					if(stripos($data["download_url"],"data:") === 0){

						$data_info = substr($data["download_url"],0,strpos($data["download_url"],","));
						$data_info = str_ireplace(";base64","",$data_info);
						$data_info = explode(":",$data_info);
						$data_info[1] = explode("/",$data_info[1]);
						$ext = strtolower($data_info[1][1]);
						$ext = str_ireplace(".","",$ext);
						$ext = str_ireplace("jpeg","jpg",$ext);

						$filename = ($data_info[0] ? $data_info[0] : "upload") . date("YmdHis") . rand(1000,9999)  . "." . $ext;
						if(isset($data["filename"])){
							$filename = $data["filename"];
							if(stripos($filename,".{$ext}") === false){
								$filename .= ".{$ext}";
							}
						}

						$filename = sanitize_file_name($filename);

						$upl = wp_upload_bits($filename,null,file_get_contents($data["download_url"]));
						if($upl["error"]){
							return $upl;
						}
					}else{
						if ( ! function_exists( 'download_url' ) ) {
							require_once ABSPATH . 'wp-admin/includes/file.php';
						}

						$tmp_file = download_url($data["download_url"]);

						if(is_wp_error($tmp_file)){
							return array(
								"error" => $tmp_file->get_error_messages(),
								"error_stage" => "download_url"
							);
						}

						$filename = "upload" . date("YmdHis") . rand(1000,9999);
						if(isset($data["filename"])){
							$filename = $data["filename"];
							$filename = sanitize_file_name($filename);
						}

						$finfo = wp_check_filetype_and_ext($tmp_file, basename($tmp_file));

						if($finfo["ext"]){
							$ext = str_replace(".","",$finfo["ext"]);
							$ext = str_ireplace("jpeg","jpg",$ext);
							if(stripos($filename,".{$ext}") === false){
								$filename .= ".{$ext}";
							}
						}else{
							$intext = exif_imagetype($tmp_file);
							if($intext !== false){
								$ext = image_type_to_extension($intext);
								if($ext !== false){
									$ext = str_replace(".","",$ext);
									$ext = str_ireplace("jpeg","jpg",$ext);
									if(stripos($filename,".{$ext}") === false){
										$filename .= ".{$ext}";
									}
								}
							}else{
								$ext = $this->additionalExtensionsCheck($tmp_file);
								if($ext){
									if(stripos($filename,".{$ext}") === false){
										$filename .= ".{$ext}";
									}
								}else if(isset($data["type"])){
									$ext = trim(strtolower($data["type"]));
									if(stripos($ext,"/") !== false){
										$ext = trim(explode("/",$ext)[1]);
									}

									if($ext){
										$ext = str_replace(".","",$ext);
										$ext = str_ireplace("jpeg","jpg",$ext);
										if(stripos($filename,".{$ext}") === false){
											$filename .= ".{$ext}";
										}
									}
								}
							}
						}

						$upl = wp_upload_bits($filename,null,file_get_contents($tmp_file));
						if($upl["error"]){
							$upl["error_stage"] = "wp_upload_bits";
							$upl["file_info"]   = $finfo;
							$upl["filename"]    = $filename;
							return $upl;
						}
					}

					if(isset($data["safe_path"])){
						if($data["safe_path"]){
							$upload_dir      = wp_get_upload_dir();
							$downloads_path  = $upload_dir['basedir'] . '/woocommerce_uploads';
							$safe_path       = str_ireplace("//","/",str_ireplace($upload_dir['basedir'], $downloads_path, $upl["file"]));
							$safe_path_dir   = dirname($safe_path);

							if(!file_exists($safe_path_dir)){
								wp_mkdir_p($safe_path_dir);
							}

							if(file_exists($safe_path_dir)){
								if(rename($upl["file"],$safe_path)){
									$upl["file"] = $safe_path;
									if(isset($upl["url"]))
										$upl["url"] = rtrim($upload_dir['baseurl'],"/"). '/woocommerce_uploads/' . scconn_explode_end("/woocommerce_uploads/",$upl["url"]);
								}
							}
						}
					}

					if($file_only){
						return $upl;
					}

					$post_info["guid"]           = $upl["url"];
					$post_info["post_mime_type"] = $upl["type"];
					$post_info["post_title"]     = $filename;

					if(isset($data["title"])){
						$post_info["post_title"] = $data["title"];
					}

					$post_id = null;
					if(isset($data["post_id"])){
						$post_id = $data["post_id"];
					}
					$attach_id = wp_insert_attachment( $post_info, $upl["file"], $post_id );
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $upl["file"] );
					wp_update_attachment_metadata( $attach_id,  $attach_data );
					$post_info["file"] = $upl["file"];
					$post_info["id"]   = $attach_id;

					$request = new WP_REST_Request( 'GET',  "/" . scconn_read_sanitized_request_parm("wp_namespace","wp/v2") . "/media/{$attach_id}" );
					$request->set_query_params( [ 'per_page' => 1 ] );
					$response = rest_do_request( $request );
					$server = rest_get_server();
					$data = $server->response_to_data( $response, false );

					if($data)
						return $data;

					return $post_info;
				}else{
					return array(
						"error" => __("Unsupported media operation","sellingcommander")
					);
				}
			}


			public function do_media_delete($data){
				if(!$data){
					return array("error" => "NO_DATA");
				}

				if(!isset($data["id"])){
					return array("error" => "NO_DATA_IDENTIFIER");
				}

				$request = new WP_REST_Request( 'DELETE',  "/" . scconn_read_sanitized_request_parm("wp_namespace","wp/v2") . "/media/" . $data["id"]);
				$request->set_query_params( array( 'per_page' => 1, 'force' => true ));

				$response = rest_do_request( $request );
				$server = rest_get_server();
				$resp = $server->response_to_data( $response, false );
				$resp["id"] = $data["id"];
				return $resp;
			}

			public function do_media_update( $data ){
				if(!$data){
					return array("error" => "NO_DATA");
				}

				$media_id   = null;
				$media_update_data = null;
				$filename   = null;
				$type       = null;
				$title      = null;
				$media      = null;

				if(isset($data["filename"]))
					$filename = $data["filename"];
				if(isset($data["type"]))
					$type = $data["type"];
				if(isset($data["title"]))
					$title = $data["title"];

				if(isset($data["media_object"])){
					if(isset($data["media_object"]["id"])){
						$media_id   = $data["media_object"]["id"];
						if(scconn_count($data["media_object"]) > 1){
							$media_update_data = $data["media_object"];
							unset($media_update_data["id"]);
						}
					}else{
						if(scconn_count($data["media_object"]) > 0){
							$media_update_data = $data["media_object"];
						}
					}
				}

				if(isset($data["existing_file"])){
					if(!isset($data["data"])){
						$data["data"] = $data["existing_file"];
					}
				}

				if(isset($data["data"])){
					$media_import_data = array( "download_url" => $data["data"]);
					if($filename)
						$media_import_data["filename"] = $filename;
					if($type)
						$media_import_data["type"] = $type;
					if($title)
						$media_import_data["title"] = $title;
					if($media_id)
						$media_import_data["file_only"] = true;

					$res = $this->do_media_import($media_import_data);

					if(isset($res["error"]))
						return $res;

					if($media_id){
						update_attached_file($media_id,$res["file"]);
					}else{
						$media    = $res;
						$media_id = $res["id"];
					}
				}else if($filename && $media_id){
					$file = get_attached_file($media_id);
					$path = pathinfo($file);
					$filename_info = pathinfo($filename);

					$ext = $path['extension'];
					$ext = str_replace(".","",$ext);
					$ext = str_ireplace("jpeg","jpg",$ext);

					$newfile = $path['dirname']."/". $filename;
					rename($file, $newfile);
					update_attached_file( $media_id , $newfile );
				}

				if($media_update_data && $media_id){

					$request = new WP_REST_Request( 'POST',  "/" . scconn_read_sanitized_request_parm("wp_namespace","wp/v2") . "/media/" . $media_id);
					$request->set_body_params($media_update_data);
					$response = rest_do_request( $request );

				}

				$is_error = false;
				if($media_id){
					$request = new WP_REST_Request( 'GET',  "/" . scconn_read_sanitized_request_parm("wp_namespace","wp/v2") . "/media/{$media_id}" );
					$request->set_query_params( [ 'per_page' => 1 ] );
					$response = rest_do_request( $request );

					$is_error = scconn_is_error($response);

					$server = rest_get_server();
					$media = $server->response_to_data( $response, false );
				}

				if(!$media){
					return array("error" => "BAD_REQUEST");
				}

				// if(!$this->no_caching && !$is_error){
					// $this->cacheItem("media",$media);
				// }

				return $media;
			}

			public function rest_file_op($data){
				$req = $data->get_json_params();
				if(empty($req))
					$req = $data->get_body_params();
				if(isset($req["batch"])){
					$resp = array();
					foreach($req["batch"] as $key => $task){
						$resp[$key] = $this->do_rest_file_op($task);
					}
					return array("data" => $resp);
				}else
					return $this->do_rest_file_op($req);
			}

			private function do_rest_file_op($req){
				if($req["command"]){
					try{
						global $wp_filesystem;
						if(isset($wp_filesystem)){
							if(empty($req["data"])){
								return array("data" => call_user_func(array($wp_filesystem,$req["command"])));
							}else if(scconn_count($req["data"])){
								return array("data" => call_user_func_array(array($wp_filesystem,$req["command"]),$req["data"]));
							}
						}
					}catch(Throwable $t){
						return array("error" => $t->getMessage(), "error_code" => $t->getCode(), "line" => $t->getLine(), "file" => $t->getFile());
					}
				}
				return array("error" => "METHOD_NOT_FOUND" , "error_code" => 0, "line" => __LINE__,  "file" => __FILE__);
			}

		    public function check_scconn_rest_authentication(){
				return $this->check_wp_rest_authentication(false);
			}

			public function check_wp_rest_authentication($res, $arg2 = null, $arg3 = null, $arg4 = null){

				if($this->is_user_authenticated === true){
					return true;
				}

				if($res !== true || $this->is_user_authenticated === null){
					$request_user_id = $this->user_filter(null);
					if($request_user_id){
						$user = wp_get_current_user();
						if($user->ID != $request_user_id){
							wp_set_current_user($request_user_id);
							if (wp_validate_auth_cookie() == false){
								wp_set_auth_cookie($request_user_id, true, false);
							}
							$user = wp_get_current_user();
						}
						$this->user = $user;
						$this->user_email  = $this->user->user_email;
						$sc_username  = get_user_meta($this->user->ID, "_scconn_username", true);
						if($sc_username){
							$this->user_email  = $sc_username;
						}
						$this->is_user_authenticated = true;
						return true;
					}else{
						$this->is_user_authenticated = false;
						return false;
					}
				}
				return $this->is_user_authenticated;
			}

			public function is_scconn_rest_request($is_rest){
				if(!$is_rest){
					if($this->is_sellingcommander_request_session){
						$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
						if(strpos($request_uri,"rest_route=/")){
							return true;
						}else if(strpos($request_uri,trailingslashit( rest_get_url_prefix() ) . "/wc/v") !== false){
							return true;
						}else if(strpos($request_uri,trailingslashit( rest_get_url_prefix() ) . "/sc/v1") !== false){
							return true;
						}else if(strpos($request_uri,trailingslashit( rest_get_url_prefix() ) . "/wp/v2") !== false){
							return true;
						}
					}
				}
				return $is_rest;
			}

			public function user_filter($user_id){
				if(isset($this->sc_request_user_id)){
					return $this->sc_request_user_id;
				}

				$access_key = scconn_read_sanitized_request_parm("consumer_key",scconn_read_sanitized_server_parm("PHP_AUTH_USER",""));

				if($access_key){
					global $wpdb;
					$row = $wpdb->get_row(
						$wpdb->prepare(	"SELECT user_id FROM {$wpdb->prefix}woocommerce_api_keys WHERE consumer_key = %s OR consumer_key = %s",wc_api_hash($access_key),$access_key)
					);

					if($row){
						$this->sc_request_user_id = $row->user_id;
					}else{
						$this->sc_request_user_id = false;
					}
					return $this->sc_request_user_id;
				}
				return $user_id;
			}

			public function on_wp_init(){
				$this->locale = get_locale();
				$this->load_plugin_textdomain();
			}

			public function rest_pre_serve_request_before($served, $result, $request, $rest_server){
				return false;
			}

			private function readAccessToken(){
				global $wpdb;
				$this->user_access_token = get_user_meta($this->user->ID, "_scconn_api_token", true);
				if($this->user_access_token){
					if(!$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}woocommerce_api_keys WHERE consumer_secret LIKE %s AND (permissions = 'read_write' OR permissions = 'read') ;",$this->user_access_token))){
						$this->user_access_token = null;
					}
				}else{
					$this->user_access_token = null;
				}
				return $this->user_access_token;
			}

			private function removeUrlProtocol($url){
				return str_ireplace("https://","",str_ireplace("http://","",$url));
			}

			public function save_settings(){
				update_option("scconn_settings", json_encode($this->settings));
				if(isset($this->settings["scsite"])){
					if(isset($this->settings["scsite"]["active_plugins"])){
						update_option("scconn_allowed_plugins", $this->settings["scsite"]["active_plugins"]);
					}
				}
			}

			public function load_plugin_textdomain() {
				$plugin_locale = apply_filters( 'plugin_locale', get_locale(), 'sellingcommander' );
				
				load_textdomain( 'sellingcommander', WP_LANG_DIR . "/sellingcommander/sellingcommander-{$plugin_locale}.mo" );
				load_textdomain( 'sellingcommander', $this->plugin_path() . "/languages/sellingcommander-{$plugin_locale}.mo" );
				
				load_plugin_textdomain( 'sellingcommander', false, dirname( plugin_basename( __FILE__ ) ) . "/languages" );
			}

			public function plugin_path() {
				if(isset($this->plugin_path)){
					if($this->plugin_path){
						return $this->plugin_path;
					}
				}
				return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			public function register_plugin_menu_item(){

				$user  = wp_get_current_user();
				$roles = (array) $user->roles;

				if (current_user_can('administrator') || in_array( 'manage_woocommerce', $roles ) ) {
					add_menu_page( __( 'Selling commander', 'sellingcommander' )
								 , __( 'Selling commander', 'sellingcommander' )
								 , 'edit_pages'
								 , 'sellingcommander-connector'
								 , array( $this,'display')
								 ,'dashicons-list-view'
					);

					add_submenu_page( "edit.php?post_type=product",
									   esc_attr__( 'Selling Commander', 'sellingcommander' ),
									   esc_attr__( 'Manage with S.C.', 'sellingcommander' ),
									   current_user_can('administrator') ? 'administrator' : "manage_woocommerce",
									   "sellingcommander-connector-products" . (!$this->sc_siteuid ? "-justmanage" : ""),
						array( $this,'manage_products')
					);
					
					add_submenu_page( "sellingcommander-connector",
									   esc_attr__( 'Selling Commander', 'sellingcommander' ),
									   esc_attr__( 'Manage products', 'sellingcommander' ),
									   current_user_can('administrator') ? 'administrator' : "manage_woocommerce",
									   "sellingcommander-connector-products"  . (!$this->sc_siteuid ? "-justmanage" : ""),
						array( $this,'manage_products')
					);
				}
			}

			private function createAccess($app_user_id, $scope ){
				global $wpdb;

				$old_token = get_user_meta($app_user_id, "_scconn_api_token", true);

				if($old_token){
					$wpdb->delete(
						$wpdb->prefix . 'woocommerce_api_keys',
						array(
							'consumer_secret' => $old_token
						),
						array(
							'%s'
						)
					);
				}

				$description = "Selling Commander, user id: {$app_user_id}";

				$permissions     = in_array( $scope, array( 'read', 'write', 'read_write' ), true ) ? sanitize_text_field( $scope ) : 'read';
				$consumer_key    = 'ck_' . wc_rand_hash();
				$consumer_secret = 'cs_' . wc_rand_hash();

				$wpdb->insert(
					$wpdb->prefix . 'woocommerce_api_keys',
					array(
						'user_id'         => $app_user_id,
						'description'     => $description,
						'permissions'     => $permissions,
						'consumer_key'    => wc_api_hash( $consumer_key ),
						'consumer_secret' => $consumer_secret,
						'truncated_key'   => substr( $consumer_key, -7 ),
					),
					array(
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
					)
				);

				update_user_meta($app_user_id, "_scconn_api_token", $consumer_secret);
				return array(
					'key_id'          => $wpdb->insert_id,
					'user_id'         => $app_user_id,
					'consumer_key'    => $consumer_key,
					'consumer_secret' => $consumer_secret,
					'key_permissions' => $permissions
				);
			}

			public function admin_utils(){
				global $woocommerce;
				$rest_url = null;
				if(isset($woocommerce)){
					$rest_url = get_rest_url();
				}

				wp_enqueue_script( 'jquery');
				if(stripos(scconn_read_sanitized_request_parm('page',''),'sellingcommander-connector') === 0){
					
					if(!isset($this->plugin_data)){
						if( !function_exists('get_plugin_data') ){
							require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
						}
						$this->plugin_data = get_plugin_data( __FILE__ );
					}

					wp_enqueue_style( 'scconn-sellingcommander-style', plugins_url('/style/style.css', __FILE__),null,$this->plugin_data['Version']);
					wp_enqueue_script( 'scconn-sellingcommander-script', plugins_url('/js/script.js', __FILE__),array( 'jquery' ),$this->plugin_data['Version']);

					$ts   = time();
					$this->readAccessToken();

					$connectdata = array(
								'admin_url'         => esc_url($this->admin_link),
								'site_url'          => esc_url($this->site_link),
								'rest_url'          => esc_url($rest_url),
								'user_id'           => esc_attr($this->user->ID),
								'user_email'        => esc_attr($this->user->user_email),
								'siteuid'           => esc_attr($this->sc_siteuid),
								'utoken'            => esc_attr($this->user_access_token),
								"scaction"          => esc_attr($this->scaction),
								"scpath"            => esc_attr($this->scpath),
								"shoptype"          => "woocommerce",
								"timestamp"         => esc_attr($ts),
								"locale"            => get_locale(),
								"is_admin"          => in_array("administrator",$this->user->roles) ? 1 : 0
							);
							
					if($this->justmanage){
						$connectdata["justmanage"] = $this->justmanage;
					}		

					$show_add = false;
					$respose_siteuid = null;
					if($this->scresponse){
						if(isset($this->scresponse["scuser"])){
							$connectdata["scuser"] = $this->scresponse["scuser"];
						}
						if(isset($this->scresponse["siteuid"])){
							$respose_siteuid = $this->scresponse["siteuid"];
						}
						if(isset($this->scresponse["add"])){
							if($this->scresponse["add"] == 1){
								$show_add = true;
							}
						}
					}

					if($this->scaction){
						$token_at_sc = null;
						if($this->scresponse){
							if(isset($this->scresponse["utoken"])){
								$token_at_sc = $this->scresponse["utoken"];
							}
						}

						if(!$this->user_access_token || ($token_at_sc && ($token_at_sc != $connectdata["utoken"]))){
							$access = $this->createAccess($this->user->ID,"read_write");
							$connectdata["utoken"]     =  $access["consumer_secret"];
							$connectdata["utoken_key"] =  $access["consumer_key"];
						}
					}

					$hash = $connectdata["timestamp"].$connectdata["user_id"].$connectdata["utoken"];
					$connectdata["hash"] = md5($hash);

					$data  = array(
							'ajax_url'       => esc_url(admin_url( 'admin-ajax.php' )),
							"connectdata"    => $connectdata,
							"plugin_version" => $this->plugin_data['Version'],
							"ui"             => $this->sc_ui,
							"siteuid"        => $respose_siteuid,
							"show_add"       => $show_add,
							"labels"         => array(
								"SC_MANAGE_PRODUCTS" => esc_attr__("Manage Products","sellingcommander"),
								"SC_MANAGE_ORDERS"   => esc_attr__("Manage Orders","sellingcommander"),
								"SC_CONFIGURE"       => esc_attr__("Configure","sellingcommander"),
								"SC_B2B"             => esc_attr__("B2B","sellingcommander"),
								"SC_ADD"             => esc_attr__("Add new site/channel","sellingcommander"),
								"SC_ADD_HINT"        => esc_attr__("Manage other sites, Google Shopping, Facebook Mini Shops or Instagram for business catalogues?","sellingcommander"),
							)
					);
					
					if(isset($this->scforlocalconsole)){
						if($this->scforlocalconsole){
							$data["in_local_console"] = 1;
						}
					}
					
					if(!$this->sc_siteuid){
						$data["just_manage_products_url"] = admin_url("admin.php?page=sellingcommander-connector-products-justmanage");
					}

					if(true || scconn_read_sanitized_request_parm("sc_debug")){
						$data["response"] = $this->scresponse;
					}

					if($this->scresponse){
						if(isset($this->scresponse["error"])){
							$data["error"] = esc_attr($this->scresponse["error"]);
						}
						if(isset($this->scresponse["init"])){
							$data["init"] = esc_attr($this->scresponse["init"]);
						}
					}
					wp_localize_script( 'scconn-sellingcommander-script', 'SellingCommander',$data);
				}
			}
			
			public function mail_send_disable_during_request($email_class){
				remove_action( 'woocommerce_low_stock_notification', array( $email_class, 'low_stock' ) );
				remove_action( 'woocommerce_no_stock_notification', array( $email_class, 'no_stock' ) );
				remove_action( 'woocommerce_product_on_backorder_notification', array( $email_class, 'backorder' ) );
				remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
				remove_action( 'woocommerce_order_status_pending_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
				remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
				remove_action( 'woocommerce_order_status_failed_to_processing_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
				remove_action( 'woocommerce_order_status_failed_to_completed_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
				remove_action( 'woocommerce_order_status_failed_to_on-hold_notification', array( $email_class->emails['WC_Email_New_Order'], 'trigger' ) );
				remove_action( 'woocommerce_order_status_pending_to_processing_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
				remove_action( 'woocommerce_order_status_pending_to_on-hold_notification', array( $email_class->emails['WC_Email_Customer_Processing_Order'], 'trigger' ) );
				remove_action( 'woocommerce_order_status_completed_notification', array( $email_class->emails['WC_Email_Customer_Completed_Order'], 'trigger' ) );
				remove_action( 'woocommerce_new_customer_note_notification', array( $email_class->emails['WC_Email_Customer_Note'], 'trigger' ) );
			}
			
			public function display(){
				if(!class_exists("WooCommerce")){
					echo "<br/><br/><br/>";
					echo "<h3>".esc_attr__("No WooCommerce?","sellingcommander")."</h3>";
					echo "<p style='font-weight: bold;'>";
					echo "sellingcommander: " . esc_attr__("This plugin is for WooCommerce therefore it requires WooCommerce to be installed and activated on your shop","sellingcommander");
					echo "</p>";
					return;
				}
				require_once( __DIR__ . DIRECTORY_SEPARATOR . "view" .  DIRECTORY_SEPARATOR . "index.php");
			}

			public function manage_products(){
				require_once( __DIR__ . DIRECTORY_SEPARATOR . "view" .  DIRECTORY_SEPARATOR . "index.php");
			}
			
			


			public function update_plugin($data){
				ob_start();
				try{

					global $scconn_upd_message;
					$scconn_upd_message = "";

					require_once( ABSPATH . 'wp-admin/includes/misc.php');
					require_once( ABSPATH . 'wp-admin/includes/file.php');
					require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
					require_once( ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php');

					$plugin_idenifier = plugin_basename( __FILE__ );
					$upgrade_t = get_site_transient( 'update_plugins' );
					if(!$upgrade_t){
						$upgrade_t = (object)array(
							"last_checked" => time() - 86400, 
							"response" => array(),
							"checked" => array()
						); 
					}
					
					$upgrade_t->response[$plugin_idenifier] = do_action("plugins_api",$remote,'plugin_information',(object)array("slug" => $plugin_idenifier));
					$upgrade_t->checked[$plugin_idenifier] = $remote->version;
					set_site_transient('update_plugins',$upgrade_t);
					$wp_updater = new Plugin_Upgrader();
					$updated = $wp_updater->upgrade($plugin_idenifier);
				
					try{
						activate_plugin(plugin_basename( __FILE__ ),null);
						if ( is_multisite() ) {
							activate_plugin(plugin_basename( __FILE__ ),null,true);
						}
					}catch(Throwable $aex){
						
					}

					$just_dump = ob_get_clean();

					return array( "updated" => $updated, "message" => $scconn_upd_message);
				}catch(Throwable $ex){
					try{
						activate_plugin(plugin_basename( __FILE__ ),null);
						if ( is_multisite() ) {
							activate_plugin(plugin_basename( __FILE__ ),null,true);
						}
					}catch(Throwable $aex){
						
					}
					
					$just_dump = ob_get_clean();
					return array( "updated" => false, "error" => $ex->getMessage(), "message" => $scconn_upd_message);
				}
			}
		}
		
		
		$GLOBALS["scconn_sellingcommander"] = new SCConn_SellingCommander();
	}
