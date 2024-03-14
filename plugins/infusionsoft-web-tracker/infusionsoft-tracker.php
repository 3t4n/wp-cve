<?php
    /* 
        Plugin Name: Infusionsoft Analytics for WordPress
        Plugin URI: http://help.infusionsoft.com/tracker
        Description: Plugin for that injects the Web Tracking Code from the Infusionsoft into your Wordpress site.
        Author: Jordan Hatch - Infusionsoft
        Version: 2.0 
        Author URI: http://help.infusionsoft.com 
    */  
    function infusionsoft_tracker_admin() {
        include('infusionsoft-tracker_import_admin.php');  
    }
    
    function infusionsoft_tracker_admin_actions() {  
        add_options_page("Infusionsoft Analytics", "Infusionsoft Analytics", 'install_plugins', "InfusionsoftAnalytics", "infusionsoft_tracker_admin");  
    }
    
    
    function inject_web_tracker($content) {
        if ( !is_user_logged_in() ) { echo get_option('infusionsoft_tracker_scriptTag'); }
        return true;
    }
    
    
    add_action('admin_menu', 'infusionsoft_tracker_admin_actions');
    add_action('wp_footer', 'inject_web_tracker', 999999);

?>