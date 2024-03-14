<?php
/**
 * @package excellikepricechangeforwoocommerceandwpecommercelight
 */
/*
 * Plugin Name: Spreadsheet Price Change for WooCommerce and WP E-commerce - Light
 * Plugin URI: https://holest.com/spreadsheet-price-changer-for-woocommerce-and-wp-e-commerce
 * Description:An WooCommerce / WP E-commerce 'MS excel'-like fast input spreadsheet editor for fast product price change using web-form spreadsheet or export / import form CSV. It supports both WooCommerce and WP E-commerce. UI behaves same as in MS Excel. This is the right thing for you if your users give you a blank stare when you're trying to explain them how to update prices.;EDITABLE / IMPORTABLE FIELDS: Price, Sales Price; VIEWABLE / EXPORTABLE FIELDS: WooCommerce: Price, Sales Price, Attributes (Each pivoted as column), SKU, Category, Shipping class, Name, Slug, Stock, Featured, Status, Weight, Height, Width, Length, Tax status, Tax class; WP E-commerce: Price, Sales Price, Tags, SKU, Category, Name, Slug, Stock, Status, Weight, Height, Width, Length, Taxable, local and international shipping costs; Allows custom fields you can configure to view/export any property
 * Tested up to: 6.1.1
 * Version: 2.4.22
 * Author: Holest Engineering
 * Author URI: http://www.holest.com
 * Requires at least: 3.6
 * WC requires at least: 2.5.0
 * WC tested up to: 7.4
 * License: GPLv2
 * Tags: csv, import, excel, export, bulk, fast, woo, woocommerce, products, editor, spreadsheet 
 * Text Domain: excel-like-price-change-for-woocommerce-and-wp-e-commerce-light
 * Domain Path: /languages/
 *
 * @category Core
 * @author Holest Engineering | www.holest.com
 */
/*
Copyright (c) holest.com

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

if (!function_exists('add_action') ) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (! class_exists('excellikepricechangeforwoocommerceandwpecommercelight') ) {
    
	
	if(!function_exists("pelm_sanitize_array")){
		function pelm_sanitize_array($arr){
			if(!empty($arr)){
				foreach($arr as $key => $val){
					$arr[$key] = sanitize_text_field($val);
				}
			}
			return $arr;
		}
	}
   
    if(!function_exists("pelm_read_sanitized_request_parm")){
	   function pelm_read_sanitized_request_parm($parm_name, $default = NULL){
			if(isset($_REQUEST[$parm_name])){
				if(is_array($_REQUEST[$parm_name])){
					return pelm_sanitize_array($_REQUEST[$parm_name]);
				}else{
					return sanitize_text_field($_REQUEST[$parm_name]);
				}
			}
			return $default;
	   }	
    }
   
    if(!function_exists("pelm_esc_sanitized_request_parm")){
	   function pelm_esc_sanitized_request_parm($parm_name, $default = NULL){
			return esc_attr(pelm_read_sanitized_request_parm($parm_name, $default));
	   }	
    }

    if(!function_exists("pelm_read_sanitized_server_parm")){
	   function pelm_read_sanitized_server_parm($parm_name, $default = NULL){
			if(isset($_SERVER[$parm_name])){
				if(is_array($_SERVER[$parm_name])){
					return pelm_sanitize_array($_SERVER[$parm_name]);
				}else
					return sanitize_text_field($_SERVER[$parm_name]);
			}
			return $default;
	   }	
    }

	if(!function_exists("pelm_read_sanitized_cookie_parm")){
	   function pelm_read_sanitized_cookie_parm($parm_name, $default = NULL){
			if(isset($_COOKIE[$parm_name])){
				if(is_array($_COOKIE[$parm_name])){
					return pelm_sanitize_array($_COOKIE[$parm_name]);
				}else
					return sanitize_text_field($_COOKIE[$parm_name]);
			}
			return $default;
	   }	
   }   

   if(!function_exists("pelm_read_sanitized_post_parm")){
	   function pelm_read_sanitized_post_parm($parm_name, $default = NULL){
			if(isset($_POST[$parm_name])){
				if(is_array($_POST[$parm_name])){
					return pelm_sanitize_array($_POST[$parm_name]);
				}else
					return sanitize_text_field($_POST[$parm_name]);
			}
			return $default;
	   }	
   }  

   if(!function_exists("pelm_read_sanitized_get_parm")){
	   function pelm_read_sanitized_get_parm($parm_name, $default = NULL){
			if(isset($_GET[$parm_name])){
				if(is_array($_GET[$parm_name])){
					return pelm_sanitize_array($_GET[$parm_name]);
				}else
					return sanitize_text_field($_GET[$parm_name]);
			}
			return $default;
	   }	
   }
   
   if(!function_exists("pelm_uncaught_exception_handler")){
		function pelm_uncaught_exception_handler($exception){
			
			$response = new stdClass;
			$response->error     = "UNCAUGHT_EXCEPTION";
			$response->message   = esc_attr($exception->getMessage());
			$response->trace     = esc_attr($exception->getTraceAsString());
			
			echo json_encode($response);
			wp_die();
		}	
   }
   
   if(!function_exists("pelm_error_handler")){
		function pelm_error_handler($severity, $message, $file, $line, $ctx) {
			$eerr = new stdClass;
			$eerr->severity =  esc_attr($severity);
			$eerr->error  = "ERROR";
			$eerr->message  =  esc_attr($message);
			$eerr->file     =  esc_attr($file);
			$eerr->line     =  esc_attr($line); 
			
			echo json_encode($eerr);
			wp_die();
		}   
    }    
	
	if(!function_exists('pelm_get_nonce')){
		function pelm_get_nonce($nonce_ident){
			global $__nonce_reg;
			if(!isset($__nonce_reg)) {
				$__nonce_reg = array();
			}
			if(!isset($__nonce_reg[$nonce_ident])) {
				$__nonce_reg[$nonce_ident] = wp_create_nonce($nonce_ident);
			}
			return esc_attr($__nonce_reg[$nonce_ident]);
		}
	}
	
	if(!function_exists('pelm_accept_verified_nonce')){
		function pelm_accept_verified_nonce($nonce_ident, $nonce_value){
			global $__nonce_reg;
			if(!isset($__nonce_reg)) {
				$__nonce_reg = array();
			}
			$__nonce_reg[$nonce_ident] = $nonce_value;
		}
	}
	
	if(!function_exists('pelm_to_utf8')){
		function pelm_to_utf8($str){
			if(is_string($str)) {
				return mb_convert_encoding($str, "UTF-8");
			} else {
				return $str;
			}
		}
	}
	
	if(!function_exists('pelm_sc_in_spc')){
		function pelm_sc_in_spc(){
			global $__pelm_sc_in_spc, $__pelm_sc_in_spc_check;
			
			if(isset($__pelm_sc_in_spc_check)){
				return $__pelm_sc_in_spc;
			}
			
			$__pelm_sc_in_spc_check = true;
			
			$active_pulgins = get_option('active_plugins', array());
			$hassc = false; 
			$haswc = false; 
			
			foreach($active_pulgins as $plg){
				if(strpos($plg,"sellingcommander") !== false){
					$hassc = true;
					if($haswc) break;			
				}else if(strpos($plg,"woocommerce/woocommerce") !== false){
					$haswc = true;
					if($hassc) break;	
				}
			}
			
			if($haswc){
				if(!$hassc){
					$__pelm_sc_in_spc = false;
				}else
					$__pelm_sc_in_spc = true;
			}else{
				$__pelm_sc_in_spc = null;
			}
			
			return $__pelm_sc_in_spc;
		}
	}
	
	//INTEGARTIONS////////////////////////////////////////////////////
	if(pelm_sc_in_spc() === false){
		require_once(__DIR__ . "/sellingcommander.php");
	}
	///////////////////////////////////////////////////////////////////
	
	add_action( 'before_woocommerce_init', function() {
		try{
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__ , true );
			}
		}catch(Throwable $ex){
			//
		}
	});

    class excellikepricechangeforwoocommerceandwpecommercelight
    {
        var $settings          = array();
        var $plugin_path       = '';
        var $is_internal       = false;  
        var $saved             = false;
        var $aux_settings_path = '';
        var $shops             = array();    
        var $remoteImportError = '';
        
        function __construct()
        {
            $this->load_plugin_textdomain();
            $this->aux_settings_path = dirname(__FILE__). DIRECTORY_SEPARATOR . 'settings.dat';
            
            add_action('admin_menu', array( $this, 'register_plugin_menu_item'));
           
            $this->loadOptions();
            
            if(pelm_read_sanitized_request_parm('plem_price_do_save_settings', false) && $this->isPostRequest()) {
                if (!pelm_read_sanitized_request_parm('pelm_nonce_check', false)) { 
                       die("<br><br>PELM PRICE CSRF: Hmm .. looks like you didn't send any credentials.. No access for you!");
                }
                add_action('wp_loaded', array( $this, 'saveBackendOptions'));
            }
            
            add_action('wp_ajax_pelm_price_frame_display', array( $this,'internal_display'));
            
            if(!isset($this->settings["fixedColumns"])) {
                //SET DEFAULT VALUES
                $this->settings["fixedColumns"]        = 3;
                $this->settings["productsPerPage"]     = 500;
            }
            
            if(strpos(pelm_read_sanitized_request_parm('page', ''), "excellikepricechangeforwoocommerceandwpecommercelight") !== false) {
                add_action('admin_init', array( $this,'admin_css_s'));
            }
            
        }
        
        public function isPostRequest()
        {
			return pelm_read_sanitized_server_parm('REQUEST_METHOD','') === 'POST';
        }
        
        public function saveBackendOptions()
        {
            if(current_user_can('administrator')) {
                if (!wp_verify_nonce($_POST['pelm_nonce_check'], 'pelm_update_settings')) { 
                    die("<br><br>PELM PRICE CSRF: Hmm .. looks like you didn't send correct credentials.. No access for you!");
                }

                if(pelm_read_sanitized_request_parm('plem_price_mem_limit_reset', false)) {
                    global $wpdb;
                    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'plem_price_mem_limit%'");
                }
                
                $this->settings["fixedColumns"]    = intval(sanitize_text_field($_REQUEST["fixedColumns"])).""; 
                $this->settings["productsPerPage"] = intval(sanitize_text_field($_REQUEST["productsPerPage"]))."";
				
				if(isset($_REQUEST["use_old_core"])){
					$this->settings["use_old_core"] = intval(sanitize_text_field($_REQUEST["use_old_core"]))."";
				}else{
					$this->settings["use_old_core"] = "";
				}
                
                if(isset($_REQUEST['wooc_fileds'])) {
                    $this->settings["wooc_fileds"] = implode(",", array_map("sanitize_text_field", $_REQUEST['wooc_fileds']));
                }
                
                if(isset($_REQUEST['wpsc_fileds'])) {
                    $this->settings["wpsc_fileds"] = implode(",", array_map("sanitize_text_field", $_REQUEST['wpsc_fileds']));
                }
                
                $this->saveOptions();
                
            }else {
                wp_die("You can not edit properties of this plugin!");
            }
        }
        
        public function saveOptions()
        {
            update_option('PELM_PRICE_SETTINGS', (array)$this->settings);
            $this->saved = true;
        }
        
        public function loadOptions()
        {
            $this->settings = get_option('PELM_PRICE_SETTINGS', array());
        }
    
        public function admin_css_s()
        {
            wp_enqueue_style('excellikepricechangeforwoocommerceandwpecommercelight-style', plugins_url('/assets/admin.css', __FILE__));
        }
        
        public function register_plugin_menu_item()
        {
            $supported_shops = array();
            $shops_dir_path = dirname(__FILE__). DIRECTORY_SEPARATOR . 'shops';
            $sd_handle = opendir(dirname(__FILE__). DIRECTORY_SEPARATOR . 'shops');
            
            while(false !== ( $file = readdir($sd_handle)) ) {
                if (( $file != '.' ) && ( $file != '..' )) { 
                     $name_parts = explode('.', $file);
                     $ext = strtolower(end($name_parts));
                    if($ext == 'php') {
                        $last = array_pop($name_parts);
                        $shop = new stdClass();
                        $shop->uid   = implode('.', $name_parts);
                        $shop->path  = $shops_dir_path . DIRECTORY_SEPARATOR . $file;
                        
                        $file_handle = fopen($shop->path, "r");
                        $source_content = fread($file_handle, 512);
                        fclose($file_handle);
                        $out = array();
                        
                        $source_content = substr($source_content, 0, strpos($source_content, "?" . ">"));
                        $source_content = substr($source_content, strpos($source_content, "<" ."?" . "p" . "h" . "p") + 5);
                        
                        $properties = array();
                        $source_content = explode("*", $source_content);
                        foreach($source_content as $line){
                            if(trim($line)) {
                                $nv = explode(":", trim($line));
                                if(isset($nv[0]) && isset($nv[1])) {
                                         $properties[trim($nv[0])] = trim($nv[1]);
                                }
                            }
                        }
                        
                        $shop->originPlugin = explode(",", $properties["Origin plugin"]);
                        $shop->title        = $properties["Title"];
                        
                        $found_active = false;
                        foreach($shop->originPlugin as $orign_plugin){
                            if(is_plugin_active($orign_plugin)) {
                                        $found_active = true;
                                        break;
                            }
                        }
                        
                        if(!$found_active) {
                              continue;
                        }
                        
                        $supported_shops[] = $shop;
                        $this->shops[] = $shop->uid;
                    }
                }
            }
        
            $self = $this;
            $sc = null;
			if(pelm_sc_in_spc() !== null){
				
				if(isset($this->settings["use_old_core"])){
					if(!$this->settings["use_old_core"]){
						$sc = true;
					}
				}else{
					$sc = true;
				}
			}

            add_menu_page(
                esc_html__('Excel-Like Price Manager', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') . (count($supported_shops) == 1 ? " ". esc_attr($supported_shops[0]->uid) : "" ),
                esc_html__('Excel-Like Price Manager', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') . (count($supported_shops) == 1 ? " ". esc_attr($supported_shops[0]->uid) : "" ),
                'edit_pages',
                $sc ? "sellingcommander-connector-products-justmanage" : 'excellikepricechangeforwoocommerceandwpecommercelight-root',
                $sc ? array( $GLOBALS["scconn_sellingcommander"] ,'manage_products') : array( $this,'callDisplayLast'),
                'dashicons-list-view'
            );
            
            foreach($supported_shops as $sh){
                add_submenu_page(
                    $sc ? "sellingcommander-connector-products-justmanage" : "excellikepricechangeforwoocommerceandwpecommercelight-root", 
					esc_html__('Excel-Like Price Manager', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') . " - " . esc_attr($sh->title), 
					esc_attr($sh->title), 
					'edit_pages', 
					($sc && $sh->uid == "wooc") ? "sellingcommander-connector-products-forward" : ("excellikepricechangeforwoocommerceandwpecommercelight-". esc_attr($sh->uid)), 
                    ($sc && $sh->uid == "wooc") ? array( $GLOBALS["scconn_sellingcommander"] ,'manage_products') : array( $this,'callDisplayShop')
                );
            }
            
            add_submenu_page(
                "excellikepricechangeforwoocommerceandwpecommercelight-root", esc_html__('Settings', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), esc_html__('Settings', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'), 'edit_pages', "excellikepricechangeforwoocommerceandwpecommercelight-settings", 
                array( $this,'callDisplaySettings')
            );
			
			
			
			if(pelm_sc_in_spc() === false){
				add_action('admin_footer', array($this,'sc_menu_item'));
			}
            
        }
		
		public function sc_menu_item(){
			?>
			<script type='text/javascript'>
			document.getElementById('toplevel_page_sellingcommander-connector').style.display = 'none';
			</script>
			<?php
		} 
        
        public function callDisplayLast()
        {
            if(count($this->shops) > 1) {
            	$shop = pelm_read_sanitized_cookie_parm('excellikepricechangeforwoocommerceandwpecommercelight-last-shop-component',"wooc");
			    $this->display($shop);
            }else if(count($this->shops) == 0) {
                $this->display("noshop");
            }else{
                $this->display($this->shops[0]);
            }
        }
        
        public function callDisplayShop()
        {
            $this->display("auto");
        }
        
        public function callDisplaySettings()
        {
            $this->display("settings");
        }
        
        public function plugin_path()
        {
            if ($this->plugin_path ) { return $this->plugin_path;
            }
            return $this->plugin_path = untrailingslashit(plugin_dir_path(__FILE__));
        }
        
        public function load_plugin_textdomain()
        {
            $locale = apply_filters('plugin_locale', get_locale(), 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');
            load_textdomain('excel-like-price-change-for-woocommerce-and-wp-e-commerce-light', WP_LANG_DIR . "/excellikepricechangeforwoocommerceandwpecommercelight/excellikepricechangeforwoocommerceandwpecommercelight-$locale.mo");
            load_textdomain('excel-like-price-change-for-woocommerce-and-wp-e-commerce-light', $this->plugin_path() . "/languages/excellikepricechangeforwoocommerceandwpecommercelight-$locale.mo");
            load_plugin_textdomain('excel-like-price-change-for-woocommerce-and-wp-e-commerce-light', false, dirname(plugin_basename(__FILE__)) . "/languages");
        }
        
        public function internal_display()
        {
            set_error_handler("pelm_error_handler", E_ERROR | E_USER_ERROR | E_PARSE | E_COMPILE_ERROR | E_RECOVERABLE_ERROR);
            set_exception_handler("pelm_uncaught_exception_handler");
            
            $this->is_internal = true;
            $this->display("");
            die();
        }
        
        public function display($elpm_shop_com)
        {
            
            if(pelm_read_sanitized_request_parm("elpm_shop_com", false)) {
                $elpm_shop_com = pelm_read_sanitized_request_parm("elpm_shop_com");
            }elseif($elpm_shop_com == 'auto') {
                
                $elpm_shop_com = explode('-', pelm_read_sanitized_request_parm("page", ""));
                $elpm_shop_com = $elpm_shop_com[1];
               
            }
            
            if($elpm_shop_com == "settings") {
                ?>
                        <script type="text/javascript">
                            var plem_price_INIT = false;                                                                                                                                                                                                                                                                                                                                                                
                            var plem_price_BASE = '<?php echo esc_url(get_home_url()); ?>';
                        </script>
                        <div class="excellikepricechangeforwoocommerceandwpecommercelight-settings">
                           <h2 style="text-align:center;"><?php echo esc_html__('Excel-Like Price Manager for WooCommerce and WP E-commerce', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></h2>
                <?php { ?>
                                   <form style="text-align:center;" method="post" class="plem-form" >
                                    <input name="pelm_nonce_check" type="hidden" value="<?php echo esc_attr(wp_create_nonce('pelm_update_settings')); ?>" />
                                    <input type="hidden" name="plem_price_do_save_settings" value="1" /> 
                                    <table>
                                        <tr>
                                          <td><h3><?php echo esc_html__('Fixed columns count:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></h3></td>
                                          <td> <input style="width:50px;text-align:center;" type="text" name="fixedColumns" value="<?php echo esc_attr(isset($this->settings["fixedColumns"]) ? $this->settings["fixedColumns"] : ""); ?>" /></td>
                                          <td><?php echo esc_html__('(To make any column fixed move it to be within first [fixed columns count] columns)', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></td>
                                        </tr>
                                        
                                        <tr>
                                          <td><h3><?php echo esc_html__('Products per page(default):', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></h3></td>
                                          <td> <input style="width:50px;text-align:center;" type="text" name="productsPerPage" value="<?php echo esc_attr(isset($this->settings["productsPerPage"]) ? $this->settings["productsPerPage"] : "500"); ?>" /></td>
                                          <td><?php echo esc_html__('(If your server limits execution resources so spreadsheet loads incorrectly you will have to decrease this value)', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></td>
                                        </tr>
                                        
                                        <tr>
                                          <td><h3><?php echo esc_html__('Enable add product/varation', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></h3></td>
                                          <td> --Not available in this version--</td>
                                          <td></td>
                                        </tr>
                                        <tr>
                                          <td><h3><?php echo esc_html__('Enable delete product/varation', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></h3></td>
                                          <td> --Not available in this version--</td>
                                          <td></td>
                                        </tr>
                                                                                
                                        <tr>
                                          <td style="color:red;font-weight:bold;" colspan="3" ><h3><button id="cmd_plem_price_mem_limit_reset"><?php echo esc_html__('Re-calculate available memory', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></h3></button>
                                          <input id="plem_price_mem_limit_reset" name="plem_price_mem_limit_reset" type='hidden' value=''  />
                                        <?php echo esc_html__('Do this if you migrate your site or change server configuration!', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></td>
                                          <script type="text/javascript">
                                            jQuery(document).on("click","#cmd_plem_price_mem_limit_reset",function(e){
                                                e.preventDefault();
                                                jQuery("#plem_price_mem_limit_reset").val("1");
                                                jQuery(".cmdSettingsSave").trigger('click');
                                            });
                                          </script>
                                        </tr>
                                        
                                      <?php if(in_array('wooc', $this->shops)) {?>
										<tr>
                                          <td><h3><?php echo esc_html__('Use old core:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light'); ?></h3></td>
                                          <td> <input style="text-align:center;" type="checkbox" name="use_old_core" value='1' <?php echo esc_attr(isset($this->settings["use_old_core"]) ? ($this->settings["use_old_core"] ? " checked='checked' " : "") : ""); ?> /></td>
                                          <td><?php echo esc_html__('(If your perfer old GUI)', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></td>
                                        </tr>
                                        <tr>
                                            <td><h3><?php echo esc_html__('WooCommerce columns visibility:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></h3></td>
                                            <td colspan="2">
                                            
                                              <div class="checkbox-list">
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='name' checked='checked' /><label><?php echo esc_html__('Name', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='parent' checked='checked' /><label><?php echo esc_html__('Parent', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='slug' checked='checked' /><label><?php echo esc_html__('Slug', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='sku' checked='checked' /><label> <?php echo esc_html__('SKU', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='categories' checked='checked' /><label><?php echo esc_html__('Categories', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='categories_paths' checked='checked' /><label><?php echo esc_html__('Categories paths(exp)', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <br/>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='stock_status' checked='checked' /><label><?php echo esc_html__('Stock Status', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='stock' checked='checked' /><label><?php echo esc_html__('Stock', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='price' checked='checked' /><label><?php echo esc_html__('Price', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='override_price' checked='checked' /><label><?php echo esc_html__('Sales Price', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='product_type' checked='checked' /><label><?php echo esc_html__('Product type', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='status' checked='checked' /><label><?php echo esc_html__('Status', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <br/>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='weight' checked='checked' /><label><?php echo esc_html__('Weight', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='height' checked='checked' /><label><?php echo esc_html__('Height', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='width' checked='checked' /><label><?php echo esc_html__('Width', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='length' checked='checked' /><label><?php echo esc_html__('Length', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='backorders' checked='checked' /><label><?php echo esc_html__('Backorders', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='shipping_class' checked='checked' /><label><?php echo esc_html__('Shipping Class', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <br/>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='tax_status' checked='checked' /><label><?php echo esc_html__('Tax Status', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='tax_class' checked='checked' /><label><?php echo esc_html__('Tax Class', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='image' /><label><?php echo esc_html__('Image', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='featured' checked='checked' /><label><?php echo esc_html__('Featured', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='gallery' /><label><?php echo esc_html__('Gallery', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='tags' checked='checked' /><label><?php echo esc_html__('Tags', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <br/>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='virtual' checked='checked' /><label><?php echo esc_html__('Virtual', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='downloadable' checked='checked' /><label><?php echo esc_html__('Downloadable', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wooc_fileds[]" type='checkbox' value='attribute_show' checked='checked' /><label><?php echo esc_html__('Attribute show for product', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <hr/>
                                                  <h4><?php echo esc_html__('Attributes visibility', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?> <button onclick="jQuery('INPUT[name=\'wooc_fileds[]\'][value*=\'pattrib\']').prop('checked',true);return false;">All</button> <button onclick="jQuery('INPUT[name=\'wooc_fileds[]\'][value*=\'pattrib\']').prop('checked',false);return false;">None</button></h4>
                                                  
                                            <?php
                                            $attributes       = array();
                                            $attributes_asoc  = array();
                                            global $wpdb;
                                            $woo_attrs = $wpdb->get_results("select * from {$wpdb->prefix}woocommerce_attribute_taxonomies order by attribute_name", ARRAY_A);
                                            foreach($woo_attrs as $attr){
                                                $att         = new stdClass();
                                                $att->id     = $attr['attribute_id'];
                                                $att->name   = $attr['attribute_name'];  
                                                $att->label  = $attr['attribute_label']; 
                                                if(!$att->label) {
                                                    $att->label = ucfirst($att->name);
                                                }
                                                $att->type   = $attr['attribute_type'];

                                                          
                                                $att->values = array();
                                                $values     = get_terms('pa_' . $att->name, array('hide_empty' => false));
                                                foreach($values as $val){
                                                    $value          = new stdClass();
                                                    $value->id      = $val->term_id;
                                                    $value->slug    = $val->slug;
                                                    $value->name    = $val->name;
                                                    $value->parent  = $val->parent;
                                                    $att->values[]  = $value;
                                                }
                                                         
                                                $attributes[]                = $att;
                                                $attributes_asoc[$att->name] = $att;
                                            }
                                                        
                                            foreach($attributes as $att){
                                                ?>    
                                                             <span><input name="wooc_fileds[]" type='checkbox' value='<?php echo 'pattribute_'. esc_attr($att->id); ?>' checked='checked' /><label><?php echo esc_attr($att->label); ?></label></span>
                                                             <br/>
                                                <?php	
                                            }
                                            ?>
                                                  
                                                  <script type="text/javascript">
                                                     var pelm_woo_fileds = "<?php echo  esc_js($this->settings["wooc_fileds"]); ?>";
                                                     
                                                     if(jQuery.trim(pelm_woo_fileds)){
                                                         pelm_woo_fileds = pelm_woo_fileds.split(',');
                                                         if(pelm_woo_fileds.length > 0){
                                                             jQuery('INPUT[name="wooc_fileds[]"]').each(function(){
                                                                if(jQuery.inArray(jQuery(this).val(), pelm_woo_fileds) < 0)
                                                                    jQuery(this).removeAttr('checked');
                                                                else
                                                                    jQuery(this).attr('checked','checked');
                                                                
                                                             });
                                                         }
                                                         
                                                     }
                                                  </script>
                                              </div>
                                            </td>
                                        </tr>
                                      <?php } ?>
                                        
                <?php if(in_array('wpsc', $this->shops)) {?>
                                        <tr>
                                            <td><h3><?php echo esc_html__('WP E-Commerce columns visibility:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></h3></td>
                                            <td colspan="2">
                                              <div class="checkbox-list">
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='name' checked='checked' /><label><?php echo esc_html__('Name', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='slug' checked='checked' /><label><?php echo esc_html__('Slug', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='sku' checked='checked' /><label> <?php echo esc_html__('SKU', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='categories' checked='checked' /><label><?php echo esc_html__('Categories', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='tags' checked='checked' /><label><?php echo esc_html__('Tags', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <br/>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='stock' checked='checked' /><label><?php echo esc_html__('Stock', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='price' checked='checked' /><label><?php echo esc_html__('Price', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='override_price' checked='checked' /><label><?php echo esc_html__('Sales Price', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='status' checked='checked' /><label><?php echo esc_html__('Status', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='weight' checked='checked' /><label><?php echo esc_html__('Weight', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <br/>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='height' checked='checked' /><label><?php echo esc_html__('Height', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='width' checked='checked' /><label><?php echo esc_html__('Width', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='length' checked='checked' /><label><?php echo esc_html__('Length', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='taxable' checked='checked' /><label><?php echo esc_html__('Taxable', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span> 
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='loc_shipping' checked='checked' /><label><?php echo esc_html__('Local Shipping', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <br/>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='int_shipping' checked='checked' /><label><?php echo esc_html__('International Shipping', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>
                                                  <span><input name="wpsc_fileds[]" type='checkbox' value='image' /><label><?php echo esc_html__('Image', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label></span>    
                                                  
                                                  <script type="text/javascript">
                                                     var pelm_wpsc_fileds = "<?php echo esc_js($this->settings["wpsc_fileds"]); ?>";
                                                     if(jQuery.trim(pelm_wpsc_fileds)){
                                                         pelm_wpsc_fileds = pelm_wpsc_fileds.split(',');
                                                         if(pelm_wpsc_fileds.length > 0){
                                                             jQuery('INPUT[name="wpsc_fileds[]"]').each(function(){
                                                                if(jQuery.inArray(jQuery(this).val(), pelm_wpsc_fileds) < 0)
                                                                    jQuery(this).removeAttr('checked');
                                                                else
                                                                    jQuery(this).attr('checked','checked');
                                                             });
                                                         }
                                                         
                                                     }
                                                  </script>
                                              </div>
                                            </td>
                                        </tr>
                <?php } ?>
                                        <tr>
                                            <td><h3><?php echo esc_html__('Define custom fields', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></h3></td>
                                            <td colspan="2">
                                            Not available in this version
                                            </td>
                                        </tr>    
                                        <tr>
                                          <td colspan="3">
                                          <p style="color:red;font-weight:bold;" class="note" ><?php echo esc_html__("After changes in available/visible columns you should do `Options`->`Clean Layout Cache` form top menu in spreadsheet editor window", 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?> </p>
                                          </td>
                                        </tr>
                                        
                                        
                                        
                                    </table>
                <?php
                global $wpdb;
                $metas        = $wpdb->get_col("select DISTINCT pm.meta_key from $wpdb->postmeta as pm LEFT JOIN $wpdb->posts as p ON p.ID = pm.post_id where p.post_type in ('product','product_variation','wpsc-product')");
                $terms        = $wpdb->get_col("select DISTINCT tt.taxonomy from $wpdb->posts as p LEFT JOIN $wpdb->term_relationships as tr on tr.object_id = p.ID LEFT JOIN $wpdb->term_taxonomy as tt on tt.term_taxonomy_id = tr.term_taxonomy_id where p.post_type in ('product','product_variation','wpsc-product')");
                                    $post_fields  = $wpdb->get_results("SHOW COLUMNS FROM $wpdb->posts;");
                $autodata = array();
                                      
                foreach($post_fields as $key =>$val){
                    if($val->Field == "ID") {
                        continue;
                    }
                    $obj = new stdClass();
                    $obj->category = 'Post field';
                    $obj->label    = esc_html__($val->Field, 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');
                    $autodata[] = $obj;
                }
                                      
                foreach($terms as $key =>$val){
                    $obj = new stdClass();
                    $obj->category = 'Term taxonomy';
                    $obj->label    = esc_html__($val, 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');
                    $autodata[] = $obj;
                }
                                      
                foreach($metas as $key =>$val){
                    $obj = new stdClass();
                    $obj->category = 'Meta key';
                    $obj->label    = esc_html__($val, 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light');
                    $autodata[] = $obj;
                }
                                      
                                      
                ?>
                                    
							 <script type="text/javascript">
								  <?php
									for($I = 0; $I < 20; $I++){ 
										  $n = $I + 1;
										
										$woo_opt  = esc_attr("wooccf_editoptions" . $n);
										$wpsc_opt = esc_attr("wpsccf_editoptions" . $n);
										
										if(isset($this->settings[$woo_opt])) {
											if($this->settings[$woo_opt]) {
												?>
												jQuery('INPUT[name="<?php echo esc_attr($woo_opt); ?>"]').val(JSON.stringify(<?php echo esc_js($this->settings[$woo_opt]); ?>));
												 <?php	
											}
										}
										
										if(isset($this->settings[$wpsc_opt])) {
											if($this->settings[$wpsc_opt]) {
												?>
												jQuery('INPUT[name="<?php echo esc_attr($wpsc_opt); ?>"]').val(JSON.stringify(<?php echo esc_js($this->settings[$wpsc_opt]); ?>));
												<?php	
											}
										}
									}
									?>
							 
									jQuery.widget( "custom.catcomplete", jQuery.ui.autocomplete, {
										_renderMenu: function( ul, items ) {
										  var that = this,currentCategory = "";
										  
										  var catV = jQuery(this.element).closest('TR').find('SELECT.value-source-type').val();
										  var Filter = "Post field";
										  if(catV == "meta")
											Filter = "Meta key";
										  else if(catV == "term")
											Filter = "Term taxonomy";
											 
										  jQuery.each( items, function( index, item ) {
											if(item.category == Filter){
												if ( item.category != currentCategory ) {
												  ul.append( "<li style='font-weight: bold; padding: .2em .4em;  margin: .8em 0 .2em; line-height: 1.5;' class='ui-autocomplete-category'>" + item.category + "</li>" );
												  currentCategory = item.category;
												}
												that._renderItemData( ul, item );
											}
										  });
										}
									});

									jQuery(document).ready(function(){
									   jQuery('INPUT.auto-source').catcomplete({
										  delay: 0,
										  source: <?php 
										  foreach($autodata as $ind => $val){
											  $autodata[$ind]->category = esc_attr($autodata[$ind]->category);
											  $autodata[$ind]->label = esc_attr($autodata[$ind]->label);
										  }
										  echo json_encode($autodata); 
										  ?>
										});
										
									   jQuery("SELECT.value-source-type").change(function(){
										   jQuery(this).closest('TR').find().val('auto-source');
									   });    
									});
									
									var plem_price_vst_initLoad = true;
									jQuery('SELECT.value-source-type').change(function(){
										var type = jQuery(this).val();
										if(type == "post")
											pelm_metapostEditor(jQuery(this).closest('TR').find('.editor-options'),plem_price_vst_initLoad);
										else if(type == "meta")
											pelm_metapostEditor(jQuery(this).closest('TR').find('.editor-options'),plem_price_vst_initLoad);
										else if(type == "term")
											pelm_termEditor(jQuery(this).closest('TR').find('.editor-options'),plem_price_vst_initLoad);
									});
									
									function pelm_metapostEditor(container,load){
									   var value_input = container.parent().find('> INPUT');
									   container.find('> *').remove();
									   
									   if(!load)
										   value_input.val('{}');
										   
									   var values = JSON.parse((value_input.val() || "{}"));       
									   
									   jQuery('.postmetaOptModel > *').clone().appendTo(container);
									   var formatSelector = container.find('SELECT.formater-selector');
									   
									   if(load){
										   if(values.formater){ 
												formatSelector.attr('init',1); 
												formatSelector.val(values.formater);
										   }
									   }
									   
									   formatSelector.change(function(){
										   //value_input.val('{"formater":"' + jQuery(this).val() + '"}');
										   container.find('.sub-options > *').remove();
										   jQuery('.sub-option.' +  formatSelector.val() + " > *").clone().appendTo(container.find('.sub-options'));
										   
										   //container.find('*[pname]').each(function(i))
									   
										   if(formatSelector.attr('init')){
											formatSelector.removeAttr('init');
											for(var prop in values){
												   var item = container.find('.sub-options *[name="' + prop + '"]');
												if(item.is('.rdo, .chk') || item.length > 1){
												  item.each(function(ind){
													 if(jQuery(this).val() == values[prop])
														   jQuery(this).attr('checked','checked');                                                         
												  });
												}else
													item.val(values[prop]);
											}
										   }
									   
										   container.find('.sub-options INPUT, .sub-options SELECT, .sub-options TEXTAREA').change(function(){
												var obj = {};
												container.find('INPUT, SELECT, TEXTAREA').each(function(i){
													if(!jQuery(this).is('.rdo,.chk') || (jQuery(this).is('.rdo,.chk') && jQuery(this).attr('checked')))
														obj[jQuery(this).attr("name")] = jQuery(this).val();
												});
												value_input.val(JSON.stringify(obj));
										   });
									   });
									   
									   
									   
									   formatSelector.trigger('change');
									}
									
									function pelm_termEditor(container,load){
									   var value_input = container.parent().find('> INPUT');
									   container.find('> *').remove();
									   
									   if(!load)
										   value_input.val('{}');
									 
									   
									   
									   jQuery('.termOptModel > *').clone().appendTo(container);
									   container.find('INPUT, SELECT, TEXTAREA').change(function(){
											var obj = {};
											container.find('INPUT, SELECT, TEXTAREA').each(function(i){
												if(!jQuery(this).is('.rdo,.chk') || (jQuery(this).is('.rdo,.chk') && jQuery(this).attr('checked')))
													obj[jQuery(this).attr("name")] = jQuery(this).val();
											});
											value_input.val(JSON.stringify(obj));
									   });
									   
									   if(load){
										var values = JSON.parse((value_input.val() || "{}"));
										for(var prop in values){
												   var item = container.find('*[name="' + prop + '"]');
												
												if(item.is('.rdo, .chk') || item.length > 1){
												  item.each(function(ind){
													 if(jQuery(this).val() == values[prop])
														   jQuery(this).attr('checked','checked');                                                         
												  });
												}else
													item.val(values[prop]);
											}
									   }
									}
									
									jQuery(document).ready(function(){
									  jQuery('SELECT.value-source-type').trigger('change');
									  plem_price_vst_initLoad = false;
									});
									
							 </script>
							
							
							<input class="cmdSettingsSave plem_price_button" type="submit" value="Save" />
						   </form>
						   
						   <script type="text/javascript">
							 jQuery('.cmdSettingsSave').click(function(e){
								e.preventDefault();
								jQuery('.excellikepricechangeforwoocommerceandwpecommercelight-settings .editor-options *').remove();
								jQuery('.excellikepricechangeforwoocommerceandwpecommercelight-settings .cmdSettingsSave').closest('form').submit();
							 });
						   </script>
                <?php } ?>
                            
                            <div style="display:none;">
                              <div class="termOptModel" >
                                <label><?php echo esc_html__('Can have multiple values:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label><input name="multiple" class="chk chk-multiple" type="checkbox" value="1" />
                                <label><?php echo esc_html__('Allow new values:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label><input  name="allownew" class="chk chk-newvalues" type="checkbox" value="1" />
                              </div>
                              
                              <div class="postmetaOptModel" >
                                <label><?php echo esc_html__('Edit formater:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label>
                                <select name="formater" class="formater-selector">
                                  <option value="text" ><?php echo esc_html__('Simple', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></option>
                                  <option value="content" ><?php echo esc_html__('Content', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></option>
                                  <option value="checkbox" ><?php echo esc_html__('Checkbox', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></option>
                                  <option value="dropdown" ><?php echo esc_html__('Dropdown', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></option>
                                  <option value="date" ><?php echo esc_html__('Date', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></option>
                                </select>
                                <span class="sub-options">
                                
                                </span>
                              </div>
                              
                              <div class="sub-option text">
                                 <form style="display:inline;">
                                 <label><?php echo esc_html__('Text', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label>   <input class="rdo" type="radio" name="format" value="" checked="checked">
                                 <label><?php echo esc_html__('Integer', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label><input class="rdo" type="radio" name="format" value="integer">
                                 <label><?php echo esc_html__('Decimal', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label><input class="rdo" type="radio" name="format" value="decimal">
                                 </form>
                              </div>
                              
                              <div class="sub-option content">
                              </div>
                              
                              <div class="sub-option checkbox">
                                 <label><?php echo esc_html__('Checked value:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label> <input placeholder="1"  style="width:80px;" type="text" name="checked_value" value="">
                                 <label><?php echo esc_html__('Un-checked value:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label> <input placeholder=""  style="width:80px;" type="text" name="unchecked_value" value="">
                                 <label><?php echo esc_html__('Null value:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label> <input placeholder=""  style="width:80px;" type="text" name="null_value" value="">
                              </div>
                              
                              <div class="sub-option dropdown">
                                <label><?php echo esc_html__('Values(val1,val2...):', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label><input style="width:300px;" name="values" type="text" value="" />
                                <label><?php echo esc_html__('Strict:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label><input name="strict" class="chk chk-strict" type="checkbox" value="1" />
                              </div>
                              
                              <div class="sub-option date">
                                <label><?php echo esc_html__('Fromat:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label><input style="width:120px;" name="format" type="text" value="YYYY-MM-DD HH:mm:ss" />
                                <label><?php echo esc_html__('Default date:', 'excel-like-price-change-for-woocommerce-and-wp-e-commerce-light') ?></label><input style="width:120px;" name="default"  type="text" value="0000-00-00 00:00:00" />
                              </div>
                              
                            </div>
                        </div>
                <?php	
            }else if($elpm_shop_com != "noshop") {
                
                $plugin_data         = get_plugin_data(__FILE__);
                $plem_price_settings = &$this->settings;  
                $plem_price_settings['plugin_version'] = $plugin_data['Version'];
                $excellikepricechangeforwoocommerceandwpecommercelight_baseurl = plugins_url('/', __FILE__); 
                include_once dirname(__FILE__). DIRECTORY_SEPARATOR . 'shops' . DIRECTORY_SEPARATOR .  $elpm_shop_com.'.php';

                return;
                
                if(!$this->is_internal) {
                    ?>
                    
                    <iframe style="width:100%;position:absolute;" id="elpm_shop_frame" src="admin-ajax.php?action=pelm_price_frame_display&page=excellikepricechangeforwoocommerceandwpecommercelight-root&elpm_shop_com=<?php echo esc_attr($elpm_shop_com); ?>" ></iframe>
                    <button style="z-index: 999999;position:fixed;bottom:32px;color:white;border:none;background-color:#9b4f96;left:48%;cursor:pointer;" onclick="window.location = document.getElementById('elpm_shop_frame').src + '&pelm_full_screen=1'; return false;" >[View In Full Screen]</button>
                    <script type="text/javascript" >
                        (function(c_name,value,exdays) {
                            var exdate = new Date();
                            exdate.setDate(exdate.getDate() + exdays);
                            var c_value = escape(value) + ((exdays==null) ? "" : ";expires="+exdate.toUTCString());
                            document.cookie=c_name + "=" + c_value;
                        })("excellikepricechangeforwoocommerceandwpecommercelight-last-shop-component","<?php echo esc_attr($elpm_shop_com); ?>", 30);
                        
                        function pelm_onElpmShopFrameResize(){
                            jQuery('#elpm_shop_frame').outerHeight( window.innerHeight - 10 - (jQuery("#wpadminbar").outerHeight() + jQuery("#wpfooter").outerHeight()));
                        }
                        
                        function pelm_availablespace(){
                            var w =    jQuery(window).width();
                            
                            if(w <= 600){
                                jQuery('#wpbody.spreadsheet').css('right','16px');
                            }else{
                                jQuery('#wpbody.spreadsheet').css('right','0');
                                if(jQuery('#adminmenu:visible')[0])
                                    w-= jQuery('#adminmenu').outerWidth();
                            }
                            
                            w-= 20;        
                            
                            return w;    
                        }
                        
                        jQuery(window).resize(function(){
                            jQuery('#wpbody.spreadsheet').innerWidth( pelm_availablespace());
                            pelm_onElpmShopFrameResize();
                        });
                        
                        jQuery(document).ready(function(){
                            jQuery('#wpbody.spreadsheet').innerWidth( pelm_availablespace());
                            pelm_onElpmShopFrameResize();
                        });
                        
                        jQuery(window).load(function(){
                            jQuery('#wpbody.spreadsheet').innerWidth( pelm_availablespace());
                            pelm_onElpmShopFrameResize();
                        });
                        
                        jQuery('#wpbody').addClass('spreadsheet');
                        
                        pelm_onElpmShopFrameResize();
                    </script>
                                   <?php 
                }else{
                    $plugin_data         = get_plugin_data(__FILE__);
                    $plem_price_settings = &$this->settings;  
                    $plem_price_settings['plugin_version'] = $plugin_data['Version'];
                    $excellikepricechangeforwoocommerceandwpecommercelight_baseurl = plugins_url('/', __FILE__); 
                    include_once dirname(__FILE__). DIRECTORY_SEPARATOR . 'shops' . DIRECTORY_SEPARATOR .  $elpm_shop_com.'.php';
                }
            }
        }
    }
   
    $GLOBALS['excellikepricechangeforwoocommerceandwpecommercelight'] = new excellikepricechangeforwoocommerceandwpecommercelight();
}
?>
