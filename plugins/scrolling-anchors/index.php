<?php

/*
Plugin Name: Scrolling Anchors
Description: Create anchors and add up to to 30 scrolling animation effects to links that link to page anchors. You can set scroll speed and offset value. 
Version: 1.0
Author: Andrew Matt
*/

//Anchor Button to TinyMCE Editor
global $wp_version;
if ( $wp_version < 3.9 ) {
	if ( ! function_exists('scanc_enable_anchor_button') ) {
		function scanc_enable_anchor_button($buttons) {
		  $buttons[] = 'anchor';
		  return $buttons;
		}
	}
	add_filter("mce_buttons_2", "scanc_enable_anchor_button");
} else {
	add_action( 'init', 'scanc_anchor_button' );
	function scanc_anchor_button() {
		add_filter( "mce_external_plugins", "scanc_anchor_add_button" );
		add_filter( 'mce_buttons_2', 'scanc_anchor_register_button' );
	}
	function scanc_anchor_add_button( $plugin_array ) {
		$plugin_array['anchor'] = $dir = plugins_url( '/anchor/plugin.min.js', __FILE__ );
		return $plugin_array;
	}
	function scanc_anchor_register_button( $buttons ) {
		array_push( $buttons, 'anchor' );
		return $buttons;
	}
}

//Shortcode
if ( ! function_exists('scanc_scanc_shortcode') ) {
function scanc_scanc_shortcode( $atts, $content = null ) {
   return '<a id="' . $content . '">';
}
add_shortcode( 'anchor', 'scanc_scanc_shortcode' );
}


/* 
Registering Options Page
*/	
if(!class_exists('SCANC_ESSLPluginOptions')) :

// DEFINE PLUGIN ID
define('SCANC_PluginOptions_ID', 'scanc-plugin-options');
// DEFINE PLUGIN NICK
define('SCANC_PluginOptions_NICK', 'Scroll Anchor Settings');

    class SCANC_ESSLPluginOptions
    {
		/** function/method
		* Usage: return absolute file path
		* Arg(1): string
		* Return: string
		*/
		public static function scanc_file_path($file)
		{
			return plugin_dir_path( __FILE__ ).$file;
		}
		/** function/method
		* Usage: hooking the plugin options/settings
		* Arg(0): null
		* Return: void
		*/
		public static function register()
		{
			register_setting(SCANC_PluginOptions_ID.'_options', 'enable_scanc_aggressive');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_speed');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_offset');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_easing');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_begin_1');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_begin_2');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_begin_3');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_begin_4');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_begin_5');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_match_1');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_match_2');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_match_3');
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_match_4');			
			register_setting(SCANC_PluginOptions_ID.'_options', 'scanc_exclude_match_5');				
		}
		/** function/method
		* Usage: hooking (registering) the plugin menu
		* Arg(0): null
		* Return: void
		*/
		public static function menu()
		{
			// Create menu tab
			add_options_page(SCANC_PluginOptions_NICK.' Plugin Options', SCANC_PluginOptions_NICK, 'manage_options', SCANC_PluginOptions_ID, array('SCANC_ESSLPluginOptions', 'options_page'));
		}
		/** function/method
		* Usage: show options/settings form page
		* Arg(0): null
		* Return: void
		*/
		public static function options_page()
		{ 
			if (!current_user_can('manage_options')) 
			{
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			
			$plugin_id = SCANC_PluginOptions_ID;
			// display options page
			include(self::scanc_file_path('options.php'));
		}
		
    }
	
	
	// Add settings link on plugin page
	function scanc_plugin_action_links($links) { 
	  $settings_link = '<a href="options-general.php?page=scanc-plugin-options">Settings</a>'; 
	  array_unshift($links, $settings_link); 
	  return $links; 
	}
	 
	$plugin = plugin_basename(__FILE__); 
	add_filter("plugin_action_links_$plugin", 'scanc_plugin_action_links' );


	if ( is_admin() )
	{
		add_action('admin_init', array('SCANC_ESSLPluginOptions', 'register'));
		add_action('admin_menu', array('SCANC_ESSLPluginOptions', 'menu'));
		
	}
	
	if ( !is_admin() )
	{

		add_action('wp_enqueue_scripts', 'scanc_enqueue_jquery', 999 );
		add_action('wp_footer', 'scanc_script',100);
		
		
		function scanc_enqueue_jquery() {
			wp_deregister_script( 'jquery-easing' );
			wp_register_script( 'jquery-easing', plugins_url('js/jquery.easing.1.3.js',__FILE__),array( 'jquery' ) );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script('jquery-easing');		
		}		

		function scanc_script() {			
			$scanc_exclude_begin_1=$scanc_exclude_begin_2=$scanc_exclude_begin_3=$scanc_exclude_begin_4=$scanc_exclude_begin_5=$scanc_exclude_match_1=$scanc_exclude_match_2=$scanc_exclude_match_3=$scanc_exclude_match_4=$scanc_exclude_match_5='';			
			if(get_option('scanc_exclude_begin_1')){ $scanc_exclude_begin_1=":not([href^='".get_option('scanc_exclude_begin_1')."'])"; }			
			if(get_option('scanc_exclude_begin_2')){ $scanc_exclude_begin_2=":not([href^='".get_option('scanc_exclude_begin_2')."'])"; }			
			if(get_option('scanc_exclude_begin_3')){ $scanc_exclude_begin_3=":not([href^='".get_option('scanc_exclude_begin_3')."'])"; }			
			if(get_option('scanc_exclude_begin_4')){ $scanc_exclude_begin_4=":not([href^='".get_option('scanc_exclude_begin_4')."'])"; }			
			if(get_option('scanc_exclude_begin_5')){ $scanc_exclude_begin_5=":not([href^='".get_option('scanc_exclude_begin_5')."'])"; }			
			if(get_option('scanc_exclude_match_1')){ $scanc_exclude_match_1=":not([href='".get_option('scanc_exclude_match_1')."'])";}			
			if(get_option('scanc_exclude_match_2')){ $scanc_exclude_match_2=":not([href='".get_option('scanc_exclude_match_2')."'])";}			
			if(get_option('scanc_exclude_match_3')){ $scanc_exclude_match_3=":not([href='".get_option('scanc_exclude_match_3')."'])";}			
			if(get_option('scanc_exclude_match_4')){ $scanc_exclude_match_4=":not([href='".get_option('scanc_exclude_match_4')."'])";}			
			if(get_option('scanc_exclude_match_5')){ $scanc_exclude_match_5=":not([href='".get_option('scanc_exclude_match_5')."'])";}						
			$scanc_exclude_begin= $scanc_exclude_begin_1. $scanc_exclude_begin_2. $scanc_exclude_begin_3. $scanc_exclude_begin_4. $scanc_exclude_begin_5;
			$scanc_exclude_match= $scanc_exclude_match_1. $scanc_exclude_match_2. $scanc_exclude_match_3. $scanc_exclude_match_4. $scanc_exclude_match_5;		

			if(get_option('enable_scanc_aggressive')=='1'){ ?>	
			<script type="text/javascript">
				jQuery.noConflict();
				(function($){
				  
					var jump=function(e)
					{
					   if (e){
						   var target = $(this).attr("href");
					   }else{
						   var target = location.hash;
					   }
					   
						var scrollToPosition = $(target).offset().top - <?php if (get_option('scanc_offset')!='') {echo get_option('scanc_offset');} else {echo '20';} ?>;
					
					   $('html,body').animate({scrollTop: scrollToPosition },<?php if (get_option('scanc_speed')!='') {echo get_option('scanc_speed');} else {echo '900';} ?> ,'<?php echo  get_option('scanc_easing','easeInQuint');?>' );

					}

					$('html, body').hide()

					$(document).ready(function()
					{
						$("area[href*=\\#],a[href*=\\#]:not([href=\\#]):not([href^='\\#tab']):not([href^='\\#quicktab']):not([href^='\\#pane'])<?php if($scanc_exclude_begin) echo $scanc_exclude_begin; ?><?php if($scanc_exclude_match) echo $scanc_exclude_match; ?>").bind("click", jump);

						if (location.hash){
							setTimeout(function(){
								$('html, body').scrollTop(0).show()
								jump()
							}, 0);
						}else{
						  $('html, body').show()
						}
					});
				  
				})(jQuery)
			</script>
				<?php  } else {  ?>
			<script type="text/javascript">
				jQuery.noConflict();
				(function( $ ) {
					$(function() {
						// More code using $ as alias to jQuery
						jQuery("area[href*=\\#],a[href*=\\#]:not([href=\\#]):not([href^='\\#tab']):not([href^='\\#quicktab']):not([href^='\\#pane'])<?php if($scanc_exclude_begin) echo $scanc_exclude_begin; ?><?php if($scanc_exclude_match) echo $scanc_exclude_match; ?>").click(function() {
							if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
								var target = $(this.hash);
								target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
								if (target.length) {
								$('html,body').animate({
								scrollTop: target.offset().top - <?php if (get_option('scanc_offset')!='') {echo get_option('scanc_offset');} else {echo '20';} ?>  
								},<?php if (get_option('scanc_speed')!='') {echo get_option('scanc_speed');} else {echo '900';} ?> ,'<?php echo  get_option('scanc_easing','easeInQuint');?>');
								return false;
								}
							}
						});
					});
				})(jQuery);	
			</script>				
				<?php }	
		}					
	}	
endif;