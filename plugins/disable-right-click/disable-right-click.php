<?php
/*
Plugin Name: Prevent Content Theft Lite
Plugin URI: http://techsini.com/our-wordpress-plugins/disable-right-click/
Description: Prevent Content Theft [Disable Right Click] plugin prevents right click context menu which avoids copying website content and source code up to some extent.
Author: Shrinivas Naik
Version: 2.1
Author URI: http://techsini.com
*/

if(!class_exists('disable_right_click')){

    class disable_right_click{

        public function __construct(){

            //Activate the plugin for first time
            register_activation_hook(__FILE__, array($this, "activate"));

            register_deactivation_hook(__FILE__, array($this, "deactivate"));

            //Load scipts and styles
            add_action('wp_enqueue_scripts', array($this, 'register_scripts'));
            add_action('wp_enqueue_scripts', array($this, 'register_styles'));


            //Run the plugin in footer
            add_action('wp_footer', array($this, 'run_plugin'));

            //plugin action links
            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'my_plugin_action_links'));

            //Show activation notice
            add_action('admin_notices', array($this, 'pct_activation_notice'));
        }


        public function activate(){

            /* Create transient data */
            set_transient('pct-admin-notice', true, 10);

        }

        public function deactivate(){

        }

        public function register_scripts(){
            if(!is_page( array('contact', 'contact-us',  ))){
                wp_enqueue_script('jquery');
                wp_register_script('no_right_click_js',plugins_url( 'disable-right-click-js.js' , __FILE__ ),array( 'jquery' ));
                wp_enqueue_script('no_right_click_js');
            }
        }

        public function register_styles(){
            wp_enqueue_style( 'ts_pct_style', plugins_url('css/style.css', __FILE__) );
        }

        public function run_plugin() {
            ?>

            <div id="ts_pct_wrapper">
                <div class="ts_pct_modal">
                    <p>This function has been disabled for <strong><?php echo get_bloginfo('name');?></strong>.</p>
                    <button class="close-ts-pct-modal">OK</button>
                </div>
            </div>

            <?php

        }

        function my_plugin_action_links( $links ) {
           $links[] = '<br><b><a href="http://techsini.com/our-wordpress-plugins/disable-right-click/" target="_blank">Get PRO version</a></b>';
           return $links;
        }

        public function pct_activation_notice(){
        /* Check transient, if available display notice */
        if( get_transient( 'pct-admin-notice' ) ){
            ?>
                <div class="notice notice-success">
                    <p>Thank you for installing Prevent Content Theft Lite Plugin! <strong>Upgrade to Prevent Content Theft PRO version and get the following features</strong></p>
                        <ul>
                            <li>* Uses beautiful alert box to show the disable message which automatically hides after few seconds</li>
                            <li>* Disable Cut/Copy Shortcut Keys</li>
                            <li>* Disable Save Shortcut Key</li>
                            <li>* Disable Text/Image Selection</li>
                            <li>* Disable Image Drag-n-Drop</li>
                            <li>* Exclude Pages option to allow right click on the pages you want</li>
                        </ul>
                        <p>
                        <a href="http://techsini.com/our-wordpress-plugins/disable-right-click/" target="_blank"><input class="button-primary" type="button" value="Get the PRO Version Now!"></a>
                        </p>
                </div>
                <?php
                /* Delete transient, only display this notice once. */
                delete_transient( 'pct-admin-notice' );
            }
        }

    }

}


$disable_right_click = new disable_right_click();

?>
