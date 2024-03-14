<?php 
namespace Adminz\Admin;
use Adminz\Admin\ADMINZ_DefaultOptions;
use Adminz\Admin\ADMINZ_Enqueue;
use Adminz\Admin\ADMINZ_ContactGroup;
use Adminz\Admin\ADMINZ_Woocommerce;
use Adminz\Admin\ADMINZ_Flatsome;
use Adminz\Admin\ADMINZ_Mailer;
use Adminz\Admin\ADMINZ_Security;

class ADMINZ_PluginOptions extends Adminz{
	public $setting_tab;
	static $slug = 'adminz';
	public $options_group = "adminz";
	function __construct() {
		if(!is_admin()) return;
		add_action('admin_menu', [$this,'adminz_add_menu_page']);
		// $this->upgrade_old_config();
	}
	function upgrade_old_config(){
		$new_v = "2022925";
		register_setting( $this->options_group, 'adminz_db_version' );




		
		// check option version
		if(!(get_option('adminz_db_version') == $new_v)){
			
			// move tinh huyen xa sang woocommerce
			$adminz_flatsome = get_option('adminz_flatsome',[]);
			if(
				isset($adminz_flatsome['enable_acf_thx']) and 
				$adminz_flatsome['enable_acf_thx'] == 'on'){
				$options_woocommerce = get_option('adminz_woocommerce', []);
				$options_woocommerce['enable_acf_thx'] = "on";
				update_option('adminz_woocommerce',$options_woocommerce);
			}
			if(
				isset($adminz_flatsome['enable_acf_thx_disable_data']) and 
				$adminz_flatsome['enable_acf_thx_disable_data'] == 'on'){
				$options_woocommerce = get_option('adminz_woocommerce', []);
				$options_woocommerce['enable_acf_thx_disable_data'] = "on";
				update_option('adminz_woocommerce',$options_woocommerce);
			}



			// ADMINZ_DefaultOptions
			$options_default = get_option('adminz_default', []);
			if(empty($options_default)){
				$options_default = [
					'adminz_tax_thumb'=> get_option('adminz_tax_thumb',[]),
					'adminz_use_classic_editor'=> get_option('adminz_use_classic_editor',"on"),
					'adminz_use_category_tiny_mce'=> get_option('adminz_use_category_tiny_mce',''),	
					'adminz_notice'=> get_option('adminz_notice',''),
					'adminz_enable_countview'=> get_option('adminz_enable_countview',''),
					'adminz_menu_title'=> get_option('adminz_menu_title','Administrator Z'),
					'adminz_login_logo'=> get_option('adminz_login_logo',''),
					'adminz_logo_url'=> get_option('adminz_logo_url',''),
					'adminz_autoupdate'=>get_option('adminz_autoupdate',['update_core', 'update_plugin', 'update_theme', 'update_translation']),
					'adminz_hide_admin_menu'=> get_option('adminz_hide_admin_menu',[]),
					'adminz_user_excluded'=> get_option('adminz_user_excluded',[1]),
					'auto_image_excerpt'=> get_option('auto_image_excerpt',"")
				];
				update_option('adminz_default',$options_default);
			}
			



			// ADMINZ_Enqueue
			$options_enqueue = get_option('adminz_enqueue', []);
			if(empty($options_enqueue)){
				$adminz_supported_font = (array)get_option('adminz_supported_font',[]);
				if(get_option('adminz_choose_font_lato') == "on"){				
					$adminz_supported_font[] = "lato";
				}
				$options_enqueue = [
					'adminz_supported_font'=> $adminz_supported_font,
					'adminz_custom_css_fonts'=> get_option('adminz_custom_css_fonts',''),
					'adminz_custom_js'=> get_option('adminz_custom_js',''),
					'adminz_js_uploaded'=>get_option('adminz_js_uploaded',''),
					'adminz_css_uploaded'=> get_option('adminz_css_uploaded',''),
					'adminz_fonts_uploaded'=> get_option('adminz_fonts_uploaded',''),
					'adminz_enqueue_registed_js_' => get_option('adminz_enqueue_registed_js_',[]),
					'adminz_enqueue_registed_css_'=> get_option('adminz_enqueue_registed_css_',[]),
					'adminz_supported_js'=> get_option('adminz_supported_js',[]),
				];
				update_option('adminz_enqueue',$options_enqueue);
			}			
			



			// ADMINZ_ContactGroup
			$options_contactgroup = get_option('adminz_contactgroup', []);			
			if(empty($options_contactgroup)){
				$nav_asigned = [];
				$nav_asigned_0 = get_option('contactgroup_style',[]);
							
				if(isset($nav_asigned_0[0])) $nav_asigned['callback_style2'] = $nav_asigned_0[0];
				if(isset($nav_asigned_0[1])) $nav_asigned['callback_style1'] = $nav_asigned_0[1];
				if(isset($nav_asigned_0[2])) $nav_asigned['callback_style3'] = $nav_asigned_0[2];
				if(isset($nav_asigned_0[3])) $nav_asigned['callback_style4'] = $nav_asigned_0[3];
				if(isset($nav_asigned_0[4])) $nav_asigned['callback_style5'] = $nav_asigned_0[4];

				$options_contactgroup = [
					'nav_asigned'=> $nav_asigned,
					'settings' => [
						'contactgroup_title' => get_option('contactgroup_title',"Quick Contact"),
						'contactgroup_color_code' => get_option('contactgroup_color_code',"#1296d5"),
						'adminz_ctg_animation' => get_option('adminz_ctg_animation',''),
						'adminz_hide_title_mobile' => get_option('adminz_hide_title_mobile',''),
						'fixed_bottom_mobile_hide_other' => get_option('fixed_bottom_mobile_hide_other',''),
					]
				];
				update_option('adminz_contactgroup',$options_contactgroup);
			}
			



			// ADMINZ_Woocommerce	
			$options_woocommerce = get_option('adminz_woocommerce', []);
			if(empty($options_woocommerce)){	
				$adminz_woocommerce_action_hook = [];
				foreach (ADMINZ_Woocommerce::$action_hooks as $action) {				
					$get_option = get_option( 'adminz_'.$action,'' );				
					if($get_option){
						$adminz_woocommerce_action_hook[$action] = $get_option;
					}						
				}
				$options_woocommerce = [
					'adminz_woocommerce_ajax_add_to_cart_single_product'=> get_option('adminz_woocommerce_ajax_add_to_cart_single_product', ''),
					'adminz_woocommerce_ajax_add_to_cart_redirect_checkout'=> get_option('adminz_woocommerce_ajax_add_to_cart_redirect_checkout', ''),
					'adminz_woocommerce_ajax_add_to_cart_text'=> get_option('adminz_woocommerce_ajax_add_to_cart_text', ''),
					'adminz_woocommerce_remove_quanity'=> get_option('adminz_woocommerce_remove_quanity', ''),
					'adminz_woocommerce_add_buy_now_text'=> get_option('adminz_woocommerce_add_buy_now_text', ''),
					'adminz_woocommerce_add_buy_now_popup_text'=> get_option('adminz_woocommerce_add_buy_now_popup_text', ''),
					'adminz_woocommerce_empty_price_html'=> get_option('adminz_woocommerce_empty_price_html', ''),
					'adminz_woocommerce_add_buy_now_hook'=>get_option('adminz_woocommerce_add_buy_now_hook', 'woocommerce_after_add_to_cart_button'),
					'adminz_woocommerce_simple_checkout_field'=>get_option('adminz_woocommerce_simple_checkout_field', ''),
					'adminz_woocommerce_test_all_hook'=>get_option('adminz_woocommerce_test_all_hook', ''),
					'adminz_woocommerce_remove_select_woo'=>get_option('adminz_woocommerce_remove_select_woo', ''),
					'adminz_woocommerce_fix_gallery_image_size'=>get_option('adminz_woocommerce_fix_gallery_image_size', ''),
					'adminz_woocommerce_description_readmore'=>get_option('adminz_woocommerce_description_readmore', ''),
					'adminz_woocommerce_remove_add_to_cart_button'=>get_option('adminz_woocommerce_remove_add_to_cart_button', ''),
					'enable_product_cat_tinymce'=>get_option('enable_product_cat_tinymce', ''),				
					'adminz_woocommerce_from_currency_formatting'=>get_option('adminz_woocommerce_from_currency_formatting', ''),
					'adminz_woocommerce_to_currency_formatting'=>get_option('adminz_woocommerce_to_currency_formatting', ''),
					'adminz_woocoommerce_custom_hook'=>get_option('adminz_woocoommerce_custom_hook', ''),
					'adminz_woocommerce_action_hook'=>$adminz_woocommerce_action_hook,
					'adminz_tooltip_products'=>get_option('adminz_tooltip_products', ''),
					'filter_price_value'=>'',
					'filter_price_display'=>'',				
				];
				update_option('adminz_woocommerce',$options_woocommerce);
			}			
			


			// ADMINZ_Flatsome
			$options_flatsome = get_option('adminz_flatsome', []);		
			if(empty($options_flatsome)){
				$adminz_flatsome_action_hook = [];
				foreach (ADMINZ_Flatsome::$flatsome_actions as $action) {				
					$get_option = get_option( 'adminz_'.$action,'' );
					if($get_option){				
						$adminz_flatsome_action_hook[$action] = $get_option;
					}						
				}
				$options_flatsome = [
					'adminz_choose_stylesheet'=>get_option('adminz_choose_stylesheet', ''),
					'adminz_use_mce_button'=>get_option('adminz_use_mce_button', ''),
					'adminz_flatsome_lightbox_close_btn_inside'=>get_option('adminz_flatsome_lightbox_close_btn_inside', ''),
					'adminz_flatsome_lightbox_close_button'=>get_option('adminz_flatsome_lightbox_close_button', ''),
					'adminz_flatsome_viewport_meta'=>get_option('adminz_flatsome_viewport_meta', ''),
					'adminz_flatsome_woocommerce_product_gallery'=>get_option('adminz_flatsome_woocommerce_product_gallery', '4'),
					'adminz_flatsome_portfolio_name'=>get_option('adminz_flatsome_portfolio_name', ''),
					'adminz_flatsome_portfolio_category'=>get_option('adminz_flatsome_portfolio_category', ''),
					'adminz_flatsome_portfolio_tag'=>get_option('adminz_flatsome_portfolio_tag', ''),				
					'adminz_flatsome_test_all_hook'=>get_option('adminz_flatsome_test_all_hook', ''),	
					'adminz_enable_vertical_blog_post_mobile'=>get_option('adminz_enable_vertical_blog_post_mobile', ''),
					'adminz_enable_zalo_support'=>get_option('adminz_enable_zalo_support', ''),
					'adminz_flatsome_custom_hook'=>get_option('adminz_flatsome_custom_hook', ''),
					'adminz_flatsome_action_hook'=>get_option('adminz_flatsome_action_hook', ''),
					'adminz_flatsome_portfolio_product_tax'=>get_option('adminz_flatsome_portfolio_product_tax', ''),
					'adminz_add_products_after_portfolio'=>get_option('adminz_add_products_after_portfolio', ''),
					'adminz_add_products_after_portfolio_title'=>get_option('adminz_add_products_after_portfolio_title', ''),
					'adminz_enable_vertical_product_mobile'=>get_option('adminz_enable_vertical_product_mobile', ''),
					'adminz_enable_vertical_product_related_mobile'=>get_option('adminz_enable_vertical_product_related_mobile', ''),
					'fix_product_image_box_vertical'=> ''
				];
				update_option('adminz_flatsome',$options_flatsome);
			}			
			


			// ADMINZ_Mailer
			$options_mailer = get_option('adminz_mailer', []);
        	if(empty($options_mailer)){
				$options_mailer = [
	                'adminz_mailer_host'=> get_option( 'adminz_mailer_host',''),
	                'adminz_mailer_username'=> get_option( 'adminz_mailer_username',''),
	                'adminz_mailer_password'=> get_option( 'adminz_mailer_password',''),
	                'adminz_mailer_from'=> get_option( 'adminz_mailer_from',''),
	                'adminz_mailer_fromname'=> get_option( 'adminz_mailer_fromname',''),
	                'adminz_mailer_port'=> get_option( 'adminz_mailer_port',''),
	                'adminz_mailer_smtpauth'=> get_option( 'adminz_mailer_smtpauth',''),
	                'adminz_mailer_smtpsecure'=> get_option( 'adminz_mailer_smtpsecure',''),
	            ];
	            update_option('adminz_mailer',$options_mailer);
        	}
            


            // ADMINZ_Sercurity
            $options_security = get_option('adminz_sercurity', []);
            if(empty($options_security)){
	            $options_security = [
	                'adminz_xmlrpc_enabled'=>get_option('adminz_xmlrpc_enabled', ''),
					'adminz_disable_x_pingback'=>get_option('adminz_disable_x_pingback', ''),
					'adminz_disable_json'=>get_option('adminz_disable_json', ''),
					'adminz_disable_file_edit'=>get_option('adminz_disable_file_edit', ''),
	            ];
	        }
	        // fix for old slug
            update_option('adminz_security',$options_security);

            
			update_option( 'adminz_db_version', $new_v );
		}		
		
	}
	function adminz_add_menu_page(){		
		if(is_user_logged_in() and "administrator" == wp_get_current_user()->roles[0]){		
			add_submenu_page (
		        $this->options_pageslug,
		        apply_filters( 'adminz_menu_title', $this::$name),
		        apply_filters( 'adminz_menu_title', $this::$name),
		        'manage_options',
		        self::$slug,
		        [$this,'setting_pages' ]
		    );	
		}
	}
	function get_settings_tab(){
		return apply_filters( 'adminz_setting_tab', $this->setting_tab );
	}
	function setting_pages() {
		$tabs = $this->get_settings_tab();
		$tab_name = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']): "adminz_default-setting";
		?>
		<style type="text/css">
			.adminz_nav_tab .nav-tab {
				display: flex;
				margin-top:  5px;
			}
			.adminz_nav_tab svg{
			    width: 17px;
			    color: gray;
			    margin-right: 5px;
			    max-width: 17px;
			}
			.adminz_tab_content svg{
				max-width: 17px;
			}
			.adminz_tab_content a::after{
				content: url(<?php echo plugin_dir_url(ADMINZ_BASENAME).'assets/icons/external-link-alt.svg'; ?>);
			    width: 12px;
			    display: inline-block;
			    opacity: 0.2;
				fill: currentColor;
				padding-left: 5px;
				padding-right: 5px;
			}
			.adminz_tab_content h3{
			}	
			.adminz_tab_content code{
				font-weight: bold;
			}	
			.adminz_tab_content input,
			.adminz_tab_content textarea{
				max-width: 600px !important;
			}	
		</style>
		<h1><?php echo apply_filters( 'adminz_menu_title', $this::$name); ?> settings</h1>
		<nav class="adminz_nav_tab nav-tab-wrapper"><?php				
			$plugin_tabs = [];

			foreach ($tabs as $key=> $tab) {				
				$is_nav_tab_active = ($key == $tab_name) ? " nav-tab-active" : "";			
				if(isset($tab['type']) and $tab['type']){				
					$plugin_tabs[$key] = $tab;
				}else{
					$href = "#";
					echo wp_sprintf( 
						'<a href="%1$s" class="nav-tab %2$s" style="">%3$s</a>', 
						get_admin_url().$this->options_pageslug.'?page='.self::$slug."&tab=".$tab['slug'],
						$is_nav_tab_active,
		 				$tab['title']
		 			);
				}
			}
			if(!empty($plugin_tabs) and is_array($plugin_tabs)){
				echo "<div style='clear:both'>";
				foreach ($plugin_tabs as $key=> $tab) {
					$is_nav_tab_active = ($key == $tab_name) ? " nav-tab-active" : "";			
					$href = "#";
					echo wp_sprintf( 
						'<a href="%1$s" class="nav-tab %2$s" style="">%3$s</a>', 
						get_admin_url().$this->options_pageslug.'?page='.self::$slug."&tab=".$tab['slug'],
						$is_nav_tab_active,
		 				$tab['title']
		 			);
	 			}
	 			echo "</div>";
			}
			?> 
		</nav> 
		<div class="adminz_tab_content">
		<?php
		do_action('adminz_tabs_html');
		?>
		</div>
		<?php
	}
}