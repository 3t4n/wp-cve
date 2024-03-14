<?php
/*
Plugin Name: TGchannel
Plugin URI: http://ttmga.com
Description: Telegram Channel For wordpress
Author: S.J.Hosseini
Version: 0.2
Text Domain: TGchannel
Domain Path: /languages
*/


    define('TGchannel_PLUGIN_PATH', plugin_dir_path(__FILE__));
    require_once ('helper.php');


//////////////////////////////////////////////////////////////////// add setting page
    add_action('admin_menu',
    function ()
    {
	add_options_page(__("Settings ", 'TGchannel') . 'TGchannel', __("Settings ", 'TGchannel') . ' TGchannel ', 'manage_options', 'TGchannel-settings', 'TGchannel_plugin_page');
    },9);




/////////////////////////////////////////////////////////////////// register settings
    add_action('admin_init',
    function ()
     {
	register_setting('TGchannel-settings', 'TG_Channel_link');
	register_setting('TGchannel-settings', 'TG_Channel_name');
	register_setting('TGchannel-settings', 'TG_Channel_postid');
	
	register_setting('TGchannel-settings', 'TG_Channel_Header_color');
	register_setting('TGchannel-settings', 'TG_Channel_Footer_color');
	register_setting('TGchannel-settings', 'TG_Channel_Header_Font_color');
	register_setting('TGchannel-settings', 'TG_Channel_Footer_Font_color');
	register_setting('TGchannel-settings', 'TG_Channel_Header_Font_size');
	register_setting('TGchannel-settings', 'TG_Channel_Footer_Font_size');
	register_setting('TGchannel-settings', 'TG_Channel_Header_Height');
	register_setting('TGchannel-settings', 'TG_Channel_Footer_Height');	
	register_setting('TGchannel-settings', 'TG_Channel_Body_height');
	register_setting('TGchannel-settings', 'TG_Channel_Body_width');
	
	register_setting('TGchannel-settings', 'TG_Channel_background');
	register_setting('TGchannel-settings', 'TG_Channel_background_color');

	register_setting('TGchannel-settings', 'TG_Channel_body_size');	
	
	register_setting('TGchannel-settings', 'TG_channel_Bot_Token');
	register_setting('TGchannel-settings', 'TG_Channel_username');
	register_setting('TGchannel-settings', 'TG_Channel_saving_count');		 
		 
	
     });



///////////////////////////////////////////////////////////////// set options on activation

    function TGchannel_plugin_activation()
    {
	if (!get_option('TG_Channel_background')){	
    update_option( 'TG_Channel_background',plugins_url('/css/geometry.png', __FILE__));
	}
	if (!get_option('TG_Channel_background_color')){	
    update_option( 'TG_Channel_background_color','#ffffff');
	}			
	if (!get_option('TG_Channel_Header_color')){	
    update_option( 'TG_Channel_Header_color','#4c70db');
	}
	if (!get_option('TG_Channel_Footer_color')){	
    update_option( 'TG_Channel_Footer_color','#ffffff');
	}	
	if (!get_option('TG_Channel_Header_Font_color')){	
    update_option( 'TG_Channel_Header_Font_color','#ffffff');
	}
	if (!get_option('TG_Channel_Footer_Font_color')){	
    update_option( 'TG_Channel_Footer_Font_color','#4c70db');
	}
	if (!get_option('TG_Channel_Header_Font_size')){	
    update_option( 'TG_Channel_Header_Font_size','20px');
	}
	if (!get_option('TG_Channel_Footer_Font_size')){	
    update_option( 'TG_Channel_Footer_Font_size','17px');
	}
	if (!get_option('TG_Channel_Header_Height')){	
    update_option( 'TG_Channel_Header_Height','50px');
	}
	if (!get_option('TG_Channel_Footer_Height')){	
    update_option( 'TG_Channel_Footer_Height','50px');
	}
	if (!get_option('TG_Channel_Body_height')){	
    update_option( 'TG_Channel_Body_height','400px');
	}
	if (!get_option('TG_Channel_Body_width')){	
    update_option( 'TG_Channel_Body_width','308px');
	}
	if (!get_option('TG_Channel_body_size')){	
    update_option( 'TG_Channel_body_size','custom');
	}	
    }



    register_activation_hook( __FILE__, 'TGchannel_plugin_activation' );


///////////////////////////////////////////////////////////////// load textdomain
    function TGchannel_plugin_textdomain()
    {
	load_plugin_textdomain('TGchannel', FALSE, basename(dirname(__FILE__)) . '/languages/');
    }
    add_action('plugins_loaded', 'TGchannel_plugin_textdomain');





//////////////////////////////////////////////////////////////// add setting page link to installed plugin page
    function TGchannel_add_settings_link($links)
    {
	$settings_link = '<a href="admin.php?page=TGchannel-settings">' . __('Setting', 'TGchannel') . '</a>';
	array_push($links, $settings_link);
	return $links;
    }

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'TGchannel_add_settings_link');
	
	
	

///////////////////////////////////////////////////////////// Register and load the widget
    function tgchannel_load_widget()
	  {
			register_widget('tgchannel_widget');
	  }
    add_action('widgets_init', 'tgchannel_load_widget');



//////////////////////////////////////////////////////////// add shortcode

    add_shortcode('tgchannel', 'tgchannel_shortcode');



/////////////////////////////////////////////////////////// load settings page
    function TGchannel_plugin_page()
    {
    include('admin/settings.php');
    }	


/////////////////////////////////////////////////////////// load frontend script
    function tgchannel_scripts_basic()
	  {
           wp_enqueue_script('jquery');
			if (is_active_widget(false, false, 'tgchannel_widget', true))
				{
						wp_register_script('TGCH_load', plugins_url('/js/widgetload.js', __FILE__));
						wp_enqueue_script('TGCH_load');
				}
			else
				{
						wp_register_script('TGCH_load', plugins_url('/js/load.js', __FILE__));
						wp_enqueue_script('TGCH_load');
				}
				
	  }
    add_action('wp_enqueue_scripts', 'tgchannel_scripts_basic');
	
	
	
//////////////////////////////////////////////////////////// load admin scriptS
   function tgchannel_add_admin_scripts( $hook ) {
	  if (isset($_GET['page']) && ($_GET['page'] == 'TGchannel-settings')){  
	    wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style(  'admincss', plugins_url('/css/admin.css', __FILE__));

			
			
	    wp_enqueue_script('jquery');
        wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker');
        wp_enqueue_script(  'adminjquer', plugins_url('/js/admin.js', __FILE__));
		wp_enqueue_script('jquery-ui-tabs');
	  }
    }
    add_action('admin_enqueue_scripts','tgchannel_add_admin_scripts',10,1);
	
	

/////////////////////////////////////////////////////////// after update_option

function tgchannel_upgrade_completed( $upgrader_object, $options ) {
 // The path to our plugin's main file
 $our_plugin = plugin_basename( __FILE__ );
 // If an update has taken place and the updated type is plugins and the plugins element exists
 if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
  // Iterate through the plugins being updated and check if ours is there
  foreach( $options['plugins'] as $plugin ) {
   if( $plugin == $our_plugin ) {
	if (!get_option('TG_Channel_background')){	
    update_option( 'TG_Channel_background',plugins_url('/css/geometry.png', __FILE__));
	}
	if (!get_option('TG_Channel_background_color')){	
    update_option( 'TG_Channel_background_color','#ffffff');
	}			
	if (!get_option('TG_Channel_Header_color')){	
    update_option( 'TG_Channel_Header_color','#4c70db');
	}
	if (!get_option('TG_Channel_Footer_color')){	
    update_option( 'TG_Channel_Footer_color','#ffffff');
	}	
	if (!get_option('TG_Channel_Header_Font_color')){	
    update_option( 'TG_Channel_Header_Font_color','#ffffff');
	}
	if (!get_option('TG_Channel_Footer_Font_color')){	
    update_option( 'TG_Channel_Footer_Font_color','#4c70db');
	}
	if (!get_option('TG_Channel_Header_Font_size')){	
    update_option( 'TG_Channel_Header_Font_size','20px');
	}
	if (!get_option('TG_Channel_Footer_Font_size')){	
    update_option( 'TG_Channel_Footer_Font_size','17px');
	}
	if (!get_option('TG_Channel_Header_Height')){	
    update_option( 'TG_Channel_Header_Height','50px');
	}
	if (!get_option('TG_Channel_Footer_Height')){	
    update_option( 'TG_Channel_Footer_Height','50px');
	}
	if (!get_option('TG_Channel_Body_height')){	
    update_option( 'TG_Channel_Body_height','400px');
	}
	if (!get_option('TG_Channel_Body_width')){	
    update_option( 'TG_Channel_Body_width','308px');
	}
	if (!get_option('TG_Channel_body_size')){	
    update_option( 'TG_Channel_body_size','custom');
	}
   }
  }
 }
}
add_action( 'upgrader_process_complete', 'tgchannel_upgrade_completed', 10, 2 );
	