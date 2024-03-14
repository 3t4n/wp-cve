<?php
/*
Plugin Name: Global Site Tag Tracking
Version: 1.0.1
Plugin URI: https://noorsplugin.com/global-site-tag-tracking-plugin-for-wordpress/
Author: naa986
Author URI: https://noorsplugin.com/
Description: Easily add Global Site Tag (Google Analytics) Tracking code to your WordPress site
Text Domain: global-site-tag-tracking
Domain Path: /languages 
 */

if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('GLOBAL_SITE_TAG_TRACKING')) {

    class GLOBAL_SITE_TAG_TRACKING {

        var $plugin_version = '1.0.1';

        function __construct() {
            define('GLOBAL_SITE_TAG_TRACKING_VERSION', $this->plugin_version);
            $this->plugin_includes();
        }

        function plugin_includes() {
            if (is_admin()) {
                add_filter('plugin_action_links', array($this, 'plugin_action_links'), 10, 2);
            }
            add_action('plugins_loaded', array($this, 'plugins_loaded_handler'));
            add_action('admin_init', array($this, 'settings_api_init'));
            add_action('admin_menu', array($this, 'add_options_menu'));
            add_action('wp_head', array($this, 'add_tracking_code'));
        }
        
        function plugins_loaded_handler()
        {
            load_plugin_textdomain('global-site-tag-tracking', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/'); 
        }

        function plugin_url() {
            if ($this->plugin_url)
                return $this->plugin_url;
            return $this->plugin_url = plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__));
        }

        function plugin_action_links($links, $file) {
            if ($file == plugin_basename(dirname(__FILE__) . '/main.php')) {
                $links[] = '<a href="options-general.php?page=global-site-tag-tracking-settings">'.__('Settings', 'global-site-tag-tracking').'</a>';
            }
            return $links;
        }
        function add_options_menu() {
            if (is_admin()) {
                add_options_page(__('Global Site Tag Tracking', 'global-site-tag-tracking'), __('Global Site Tag Tracking', 'global-site-tag-tracking'), 'manage_options', 'global-site-tag-tracking-settings', array($this, 'options_page'));
            }
        }
        function settings_api_init(){
            	register_setting( 'globalsitetagtrackingpage', 'global_site_tag_tracking_settings' );
                
                add_settings_section(
                        'global_site_tag_tracking_section', 
                        __('General Settings', 'global-site-tag-tracking'), 
                        array($this, 'global_site_tag_tracking_settings_section_callback'), 
                        'globalsitetagtrackingpage'
                );
                
                add_settings_field( 
                        'tracking_id', 
                        __('Tracking ID', 'global-site-tag-tracking'), 
                        array($this, 'tracking_id_render'), 
                        'globalsitetagtrackingpage', 
                        'global_site_tag_tracking_section' 
                );
        }
        function tracking_id_render() { 
            $options = get_option('global_site_tag_tracking_settings');            
            ?>
            <input type='text' name='global_site_tag_tracking_settings[tracking_id]' value='<?php echo $options['tracking_id']; ?>'>
            <p class="description"><?php printf(__('Enter your Global Site Tag (Google Analytics) Tracking ID for this website (e.g %s).', 'global-site-tag-tracking'), 'UA-35118216-1');?></p>
            <?php
        }
        function global_site_tag_tracking_settings_section_callback() { 
                //echo __( 'This section description', 'globalsitetagtracking' );
        }

        function options_page() {
            $url = "https://noorsplugin.com/global-site-tag-tracking-plugin-for-wordpress/";
            $link_text = sprintf(wp_kses(__('Please visit the <a target="_blank" href="%s">Global Site Tag Tracking</a> documentation page for setup instructions.', 'global-site-tag-tracking'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url));
            ?>           
            <div class="wrap">               
            <h2>Global Site Tag Tracking - v<?php echo $this->plugin_version; ?></h2> 
            <div class="update-nag"><?php echo $link_text;?></div>
            <form action='options.php' method='post'>
            <?php
            settings_fields( 'globalsitetagtrackingpage' );
            do_settings_sections( 'globalsitetagtrackingpage' );
            submit_button();
            ?>
            </form>
            </div>
            <?php
        }
        
        function is_logged_in(){
            $is_logged_in = false;
            if(is_user_logged_in()){ //the user is logged in
                if(current_user_can('editor') || current_user_can('administrator')){
                    $is_logged_in = true;
                }
            }
            return $is_logged_in; 
        }
        
        function add_tracking_code() {
            if(!$this->is_logged_in()) {
                $options = get_option( 'global_site_tag_tracking_settings' );
                $tracking_id = $options['tracking_id'];
                if(isset($tracking_id) && !empty($tracking_id)){
                    $ouput = <<<EOT
                    <!-- Tracking code generated with Global Site Tag Tracking plugin v{$this->plugin_version} -->
                    <script async src="https://www.googletagmanager.com/gtag/js?id=$tracking_id"></script>
                    <script>
                      window.dataLayer = window.dataLayer || [];
                      function gtag(){dataLayer.push(arguments);}
                      gtag('js', new Date());

                      gtag('config', '$tracking_id');
                    </script>      
                    <!-- / Global Site Tag Tracking plugin -->
EOT;

                    echo $ouput;
                }
            }
        }

    }

    $GLOBALS['global_site_tag_tracking'] = new GLOBAL_SITE_TAG_TRACKING();
}
