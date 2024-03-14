<?php
/**
Plugin Name: One page checkout and layouts for Woocommerce
Plugin URI: https://wordpress.org/plugins/custom-checkout-layouts-for-woocommerce/
Description: This plugin is designed to Combine Cart and Checkout process which gives users a faster checkout experience, with less interruption.
Author: BluePlugins
Author URI: http://blueplugins.com
Version: 4.0.0
License:GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: cclw
WC requires at least: 3.4
WC tested up to: 8.3.1
*/
 
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'CCLW_VERSION', '4.0.0' );
define('CCLW_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define('CCLW_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if( !class_exists( 'CclwCheckout' ) ) 
{
	class CclwCheckout 
	{
		
		function __construct()  
		{
		
		add_action( 'plugins_loaded',array($this,'cclw_verify_woocommerce_installed'));
		add_action('admin_init',array($this,'cclw_plugin_redirect'));
		if ( file_exists( CCLW_PLUGIN_DIR . '/cmb2/init.php' ) ) {
            require_once CCLW_PLUGIN_DIR . '/cmb2/init.php';
			require_once CCLW_PLUGIN_DIR . '/cmb2-fontawesome-picker.php';
            }
		add_action('plugins_loaded', array($this, 'cclw_load_plugin_textdomain'));	
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'cclw_action_links' ) );	
		add_action( 'cmb2_admin_init',array($this,'cclw_custom_checkout_panel')) ;	
		add_action( 'wp_enqueue_scripts',array($this,'cclw_register_plugin_styles'));
		add_filter( 'woocommerce_locate_template',array($this,'cclw_adon_plugin_template'), 20, 3 );
		add_filter ('woocommerce_checkout_cart_item_quantity',array($this,'cclw_add_quantity'), 10, 2 );	
		add_action( 'wp_footer',array($this,'cclw_add_js'));
		add_action( 'init',array($this,'cclw_load_ajax'));
		add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );
		
		
		/*redirect to checkout page*/
		add_action( 'template_redirect',array($this,'cclw_redirect_to_checkout_if_cart'));
		add_action( 'admin_enqueue_scripts', array( $this, 'cclw_setup_admin_scripts' ) );
	    $overide_fields = get_option( 'cclw_checkout_fields');
		if(isset($overide_fields['cclw_overide_fields']) && $overide_fields['cclw_overide_fields'] == 'yes' )
		{
			add_filter( 'woocommerce_billing_fields' ,array($this,'cclw_custom_billing_fields') );	
			add_filter( 'woocommerce_shipping_fields' ,array($this,'cclw_custom_shipping_fields') );
		} 
	  	add_action( 'cmb2_before_form',array($this,'cclw_option_page_menu'));
				
		 
		}
		function cclw_action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=custom_checkout_settings' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		$custom_links[] = '<a style="font-weight: 800;" href="https://blueplugins.com/product/woocommerce-one-page-checkout-and-layouts-pro">' . __( 'Go Pro', 'woocommerce' ) . '</a>';
		
		  return array_merge( $custom_links, $links );
	    }
			
			
		/*load traslations*/
		 function cclw_load_plugin_textdomain() {
              $rs = load_plugin_textdomain('cclw', FALSE, basename(dirname(__FILE__)) . '/languages/');
          }
		/*Check if woocommerce is installed*/
		function cclw_verify_woocommerce_installed() {
			if ( ! class_exists( 'WooCommerce' )) {
				add_action( 'admin_notices',array($this,'cclw_show_woocommerce_error_message'));
			}
			
		}
    
		function cclw_show_woocommerce_error_message() {
			if ( current_user_can( 'activate_plugins' ) ) {
				$url = 'plugin-install.php?s=woocommerce&tab=search&type=term';
				$title = __( 'WooCommerce', 'woocommerce' );
				echo '<div class="error"><p>' . sprintf( esc_html( __( 'To begin using "%s" , please install the latest version of %s%s%s and add some product.', 'cclw' ) ), 'Custom Checkout Layouts WooCommerce', '<a href="' . esc_url( admin_url( $url ) ) . '" title="' . esc_attr( $title ) . '">', 'WooCommerce', '</a>' ) . '</p></div>';
			}
		}
		
		/*register admin section*/
        function cclw_setup_admin_scripts($hook) {
			wp_register_style( 'cclw-admin-panel',CCLW_PLUGIN_URL.'asserts/css/admin_panel.css',array(), CCLW_VERSION);
			
			wp_enqueue_style('cclw-admin-panel');
			
		 
            wp_enqueue_script('cclw_admin_js',CCLW_PLUGIN_URL.'asserts/js/cclw_admin_js.js', array('jquery'),CCLW_VERSION, true);
		    wp_localize_script('cclw_admin_js', 'cclw_ajax',array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        	
			 
        }
	
		/**
		* Register style sheet.
		*/
		function cclw_register_plugin_styles() {
			$layout = cmb2_get_option( 'custom_checkout_settings','cclw_checkout_layouts');	
			if(is_checkout())
			{
				wp_register_style( 'custom-checkout-css', CCLW_PLUGIN_URL .'asserts/css/custom-checkout.css', array(), CCLW_VERSION );
				wp_enqueue_style( 'custom-checkout-css' );
				
			    /*add custom Jquery*/
				wp_enqueue_script('custom-checkout-js',CCLW_PLUGIN_URL.'asserts/js/custom-checkout.js', array('jquery'),CCLW_VERSION, true);
				wp_localize_script('custom-checkout-js', 'cclw_front_ajax',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
				
				/*Jquery library*/
				wp_register_style( 'cclw-front-tabs', CCLW_PLUGIN_URL .'asserts/css/cclw-front-tabs.css', array(), CCLW_VERSION );
				wp_enqueue_style( 'cclw-front-tabs' );
				wp_enqueue_script("jquery");
                wp_enqueue_script("jquery-ui-dialog");
				wp_enqueue_script('cclw-front-tabs_js',CCLW_PLUGIN_URL.'asserts/js/cclw-front-tabs.js', array('jquery'),CCLW_VERSION, true);
				
			}	
		
 
		}
	
		/*option page menu*/
		function cclw_option_page_menu(){
			if(isset($_GET['page']))
			{
				if( $_GET['page'] == 'custom_checkout_settings' || $_GET['page'] == 'cclw_global_css' || $_GET['page'] == 'cclw_checkout_fields' || $_GET['page'] == 'cclw_pro_version')	
				{
				?>
				<div class="custom_layout_setting_panel">
					<nav class="nav-tab-wrapper woo-nav-tab-wrapper">
					<a class="nav-tab <?php if( $_GET['page'] == 'custom_checkout_settings'){echo 'nav-tab-active';}?>" href="<?php echo admin_url( 'admin.php?page=custom_checkout_settings');?>">General Settings</a>
					<a class="nav-tab <?php if( $_GET['page'] == 'cclw_global_css'){echo 'nav-tab-active';}?>" href="<?php echo admin_url( 'admin.php?page=cclw_global_css');?>">Advance Setting</a>
					<a class="nav-tab <?php if( $_GET['page'] == 'cclw_checkout_fields'){echo 'nav-tab-active';}?>" href="<?php echo admin_url( 'admin.php?page=cclw_checkout_fields');?>">Customize Checkout Fields</a>
					<a class="nav-tab <?php if( $_GET['page'] == 'cclw_pro_version'){echo 'nav-tab-active';}?>" href="<?php echo admin_url( 'admin.php?page=cclw_pro_version');?>" class="button-primary">Pro Version</a>
				
					</nav>
				</div>	
				<?php	
				}
			}
		}
		
	

		/* custom checkout setting page  */
		function cclw_custom_checkout_panel() {
			
			 require_once CCLW_PLUGIN_DIR . 'includes/admin/setting_panel.php';
			 require_once CCLW_PLUGIN_DIR . 'includes/admin/global_css.php';
			 require_once CCLW_PLUGIN_DIR . 'includes/admin/customize_checkout_fields.php';
			 require_once CCLW_PLUGIN_DIR . 'includes/admin/pro_version.php';
			
			 
		} 
		

		/*Locate new woocommerce setting folder */
		function cclw_adon_plugin_template( $template, $template_name, $template_path ) {
			 global $woocommerce;
			 $_template = $template;
			 if ( ! $template_path ) 
			  $template_path = $woocommerce->template_url;
			 $plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) )  . '/WooCommerce/';
            if(is_checkout())
			{
			   $template = $plugin_path . $template_name;
			
				if ( file_exists( $template ) ) {
				$template = $plugin_path . $template_name;
				}
				else
				{
				$template = $_template;
				}
			 
			}
            else
			{
				$template = $_template;
			}
			return $template;
			}
			
			 /*hide billing/shipping fields*/
		  function cclw_custom_billing_fields($fields) {
              include_once CCLW_PLUGIN_DIR . 'includes/front/billing_fields.php';
			  return $fields;
		  }
		   /*hide billing/shipping fields*/
		  function cclw_custom_shipping_fields($fields) {
              include_once CCLW_PLUGIN_DIR . 'includes/front/shipping_fields.php';
			  return $fields;
		  }
    	
          /*set product quantity*/ 
		
	   
		    function cclw_add_quantity( $cart_item, $cart_item_key ) {
				   $product_quantity= '';
				   return intval($product_quantity);
			}
				
		/**add some footer js*/		
		
     	
            function cclw_add_js(){
			
			require_once CCLW_PLUGIN_DIR . '/includes/template_style.php';
			     	    
            }
		
			
        /*starts ajax*/
        function cclw_load_ajax() {
			$hide_notes = get_option( 'custom_checkout_settings');
			if(isset($hide_notes) && !empty($hide_notes))
			{
				$hide_notes  = $hide_notes['cclw_order_notes'];
				if($hide_notes == 'yes')
				{
				add_filter( 'woocommerce_enable_order_notes_field', '__return_false', 9999 );  
				}
			}
					
			
            if ( !is_user_logged_in() ){
                add_action( 'wp_ajax_nopriv_cclw_update_order_review',array($this,'cclw_update_order_review'));
			} else{
                add_action( 'wp_ajax_cclw_update_order_review',array($this,'cclw_update_order_review'));
			}
			
			require_once CCLW_PLUGIN_DIR . '/includes/compatibility_functions.php';
			
		       
        }
        
		
        function cclw_update_order_review() {
             
            $values = array();
		    parse_str($_POST['post_data'], $values);
		    $cart = $values['cart'];
            foreach ( $cart as $cart_key => $cart_value ){
                WC()->cart->set_quantity( $cart_key, $cart_value['qty'], true );
                WC()->cart->calculate_totals();
                woocommerce_cart_totals();
            }
            exit;
        }
		
				
		function cclw_redirect_to_checkout_if_cart() {
			global $woocommerce;
			$checkout_setting = get_option( 'custom_checkout_settings' ); 
			
			
			if ( is_cart() && WC()->cart->get_cart_contents_count() > 0 && isset($checkout_setting['cclw_skip_cart']) && $checkout_setting['cclw_skip_cart'] =='yes')
			{
			
			// Redirect to check out url
			wp_redirect( $woocommerce->cart->get_checkout_url(), '301' );
			exit;
			}
			
		}
	/*register activation hook*/
	public static function cclw_myplugin_activate() {
     add_option('cclw_do_activation_redirect', true);
	}
	function cclw_plugin_redirect() {
		if (get_option('cclw_do_activation_redirect', false)) {
			delete_option('cclw_do_activation_redirect');
			wp_redirect(admin_url( 'admin.php?page=custom_checkout_settings' ) );
		}
    }
		
		
	 public static function cclw_myplugin_deactivate() {
	/* 	nothing to do  */
		}
     
		
	
	}// end of class
}// end of if class
register_activation_hook(__FILE__, array('CclwCheckout', 'cclw_myplugin_activate'));
register_deactivation_hook(__FILE__, array('CclwCheckout', 'cclw_myplugin_deactivate'));

$CclwCheckout_obj = new CclwCheckout();   