<?php
/*
** Plugin Name:Catalog for Woocommerce
** Plugin URI: https://www.phoeniixx.com/product/catalog-for-woocommerce/
** Description: You can convert  your store into catalog mode by hiding add to cart button, price tag, ratings, reviews.
** Version: 1.2.0
** Author: Phoeniixx
** Text Domain:phoeniixx_woocommerce_extension
** Author URI: http://www.phoeniixx.com/
** License: GPLv2 or later
** License URI: http://www.gnu.org/licenses/gpl-2.0.html
** WC requires at least: 2.6.0
** WC tested up to: 3.9.0
**/  

if ( ! defined( 'ABSPATH' ) ) exit;
	
		
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	// Put your plugin code here
			
		add_action('admin_menu', 'phoe_woocom_menu');
		
		//Created Menu on dashboard

		function phoe_woocom_menu() {
			
			add_menu_page('Catalog_Mode', 'Catalog Mode','manage_options','Catalog_Mode','phoe_woo_cat', plugin_dir_url( __FILE__ ).'assets/images/aa2.png' ,'58');
			
		}
		
		add_action('admin_head','function_btnhook_assests');
		
		function function_btnhook_assests(){

			wp_enqueue_script( 'phoeniixx_pre_wpscript', plugin_dir_url(__FILE__)."assets/js/wpscripts.js");
		
		}
		
		function phoe_btnhook_Colorpicker(){ 
    
			wp_enqueue_style( 'wp-color-picker');
			
			wp_enqueue_script( 'wp-color-picker');
		
		}

	add_action('admin_enqueue_scripts','phoe_btnhook_Colorpicker');
			
		function phoe_woo_cat(){ ?>
				
			<div id="profile-page" class="wrap">
		
				<?php
					if(isset($_GET['tab']))
						
					{
						$tab = sanitize_text_field( $_GET['tab'] );
						
					}
					else
						
					{
						
						$tab="";
						
					} ?>
					
				<h2> <?php _e('Catalog Mode Settings','phoeniixx_woocommerce_extension'); ?></h2>
			
				<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
				
					<a class="nav-tab <?php if($tab == 'user_mode' || $tab == ''){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=Catalog_Mode&amp;tab=user_mode"><?php _e('Catalog Mode','phoeniixx_woocommerce_extension'); ?></a>
					
					<a class="nav-tab <?php if($tab == 'cate_mode' ){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=Catalog_Mode&amp;tab=cate_mode"><?php _e('Button Settings','phoeniixx_woocommerce_extension'); ?></a>
					
					<a class="nav-tab <?php if($tab == 'prem_tab' ){ echo esc_html( "nav-tab-active" ); } ?>" href="?page=Catalog_Mode&amp;tab=prem_tab"><?php _e('Premium','phoeniixx_woocommerce_extension'); ?></a>
										
				</h2>
				
			</div>
			
			<?php
			
			if($tab=='user_mode' || $tab == '')	{
					
				include_once(plugin_dir_path(__FILE__).'includes/catalgsettings.php');
				
				
			} 
			if($tab=='cate_mode') {
				
				include_once(plugin_dir_path(__FILE__).'includes/btnsettings.php');
			
			}
			if($tab=='prem_tab') {
				
				include_once(plugin_dir_path(__FILE__).'includes/phoen_premium_tab.php');
			
			}
		}
			//remove review tab
		function remove_single_tab()

		{		
			
			$gen_settings = get_option('phoen_woocommerce_catlog_mode');
			
					
				if($gen_settings['product_cat_review'] == 1)
				{
				
					add_filter( 'woocommerce_product_tabs', 'remove_loop_review', 98 );

				}
					
		} 
		//register_activation_hook( __FILE__, 'phoe_activate_catalogbtn_setting');
		
		function phoe_activate_catalogbtn_setting()
		{
		
			$btn_settings=array(
					
						'btn_title'		=>		'Click Here',
						
						'btn_url'		=>		'',
						
						'btn_new_win'	=>		'',
						
						'topmargin'		=>		'0',
						
						'rightmargin'	=>		'0',
						
						'bottommargin'	=>		'0',
						
						'leftmargin'	=>		'0',
						
						'btn_bg_col'	=>		'#a46497',
						
						'btn_txt_col'	=>		'#ffffff',
						
						'btn_hov_col'	=>		'#935386',
						
						'btn_border_style'=>	'none',
						
						'btn_border'	=>		'0',
						
						'btn_bor_col'	=>		'#ffffff',
						
						'btn_rad'		=>		'0'
										
			);
			
			update_option('phoeniixx_create_custom_btn',$btn_settings);
			
		}
		
		
		//remove review tab
		function remove_loop_review( $tabs ) {
		
			unset( $tabs['reviews'] );  // Removes the reviews tab
			
			return $tabs;
		}
			
		function cmk_additional_button() {
			
			$gen_settings = get_option('phoeniixx_create_custom_btn'); ?>
	
			<a href="//<?php echo $gen_settings["btn_url"]; ?>" <?php if($gen_settings["btn_new_win"]==1) echo 'target="_blank"'; ?>>
			
			<button  type="submit" style="color:<?php echo $gen_settings["btn_txt_col"]; ?>; background-color:<?php echo $gen_settings["btn_bg_col"]; ?>;" class="new_btn "><?php echo $gen_settings["btn_title"]; ?></button> </a>
			
			<?php 
			
			require('assets/css/btn-style.php'); 
		
		} 
			//action for remove button in shop page
		function remove_prod_btn() {
			
			$gen_settings = get_option('phoen_woocommerce_catlog_mode');
			
		
			
			$add_btn=get_option('phoeniixx_create_custom_btn');
			
			if(isset($gen_settings['product_cat_btn']) && $gen_settings['product_cat_btn'] == 1){
				
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			
				if($add_btn['show_prod']==1) {
			
						add_action( 'woocommerce_single_product_summary', 'cmk_additional_button', 30 );
				
				}
			}
			
		}
			
		function remove_shop_btn()	{
			
			$gen_settings = get_option('phoen_woocommerce_catlog_mode');
			
			$add_btn=get_option('phoeniixx_create_custom_btn');
			
			if(isset($gen_settings['shop_cat_btn']) && $gen_settings['shop_cat_btn'] == 1)	{
			
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			
					if($add_btn['show_shop']==1){
			
						add_action( 'woocommerce_after_shop_loop_item', 'cmk_additional_button', 30 );
				
					}
				
				}
			
		}
		
		//action for remove price in shop page
		function remove_prod_price(){
			 
			$gen_settings = get_option('phoen_woocommerce_catlog_mode');
			
			if(isset($gen_settings['product_cat_price']) && $gen_settings['product_cat_price'] == 1)	{
				
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
				
			}
		}
		
		function remove_shop_price() 	{
			 
			$gen_settings = get_option('phoen_woocommerce_catlog_mode');
			
			if(isset($gen_settings['shop_cat_price']) && $gen_settings['shop_cat_price'] == 1){
				
				remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			
			}
		}
		
		//action for remove rating in shop page
		function remove_prod_rating()
		{
			$gen_settings = get_option('phoen_woocommerce_catlog_mode');
				
			if(isset($gen_settings['product_cat_rating']) && $gen_settings['product_cat_rating'] == 1){
			
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
			}
			
		} 
		
		function remove_shop_rating()
		{
			$gen_settings = get_option('phoen_woocommerce_catlog_mode');
			
			if(isset($gen_settings['shop_cat_rating']) && $gen_settings['shop_cat_rating'] == 1) 	{
				
				remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
			
			}
			
		} 
		
		
		// hook page
		function remove_category_page_product() {

			add_action( 'init', 'remove_shop_btn' );
			
			add_action( 'init', 'remove_shop_price' );
			
			add_action( 'init', 'remove_shop_rating' );
			
			add_action( 'init', 'remove_prod_btn' );
			
			add_action( 'init', 'remove_prod_price' );
			
			add_action( 'init', 'remove_prod_rating' );
		}
		
		add_action( 'init', 'remove_category_page_product', 2);
		
		add_action('woocommerce_single_product_summary','remove_single_tab');
	}
?>
