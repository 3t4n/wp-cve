<?php
/**
 * Plugin Name: remarketable
 * Plugin URI: http://nightshiftapps.com/
 * Description: (Formerly WP Facebook Pixel) Simply the most feature rich re-marketing pixel and tag plugin ever! 
 * Version: 5.2
 * Author: Night Shift Apps
 * Author URI: http://nightshiftapps.com/
 * License: GPL3 or later (Pro version under NSA Pro WordPress Plugin License)
 * License URI: http://www.gnu.org/licenses/gpl.html (NSA Pro WordPress Plugin License: http://nightshiftapps.com/nsa-pro-wordpress-plugin-license/)
 * Requires at least: 4.0
 * Tested up to: 5.2.2
 * 
 * ***************************************************************************************************************************************
 * 
 *      Developed by Night Shift Apps
 *      
 *      Hire us to work for you!  Contact us at info@nightshiftapps.com
 *      
 * ***************************************************************************************************************************************
 * 
 * @package remarketable
 * @author Night Shift Apps
 *  
 */

/*
 * UPDATE NOTES
 * For Version Names: http://www.dogbreedinfo.com/purebred.htm
 * 
 * Update version in this file (2 places)
 * Read Me
 *      Update version
 *      Update Change log
 * 
 * Add version post
 * Create notification
 * 
 * For Free Version:
 *  Remove pro folder & update name on line 3 above
 *  Update WordPress.org repo
 * 
 * For Pro Version:
 *  Update Product's version
 *  Upload new version file
 *  
 * */



if ( ! defined( 'ABSPATH' ) ) { exit; }



if ( !file_exists( dirname( __FILE__ ).'/NSA_WP_Plugin.php') ) {
    
    //Do not use nsau_AdminNotice because it may be the file which is missing
    add_action( 'admin_notices', function() { 
        echo('<div class="error"><p>
            <strong>remarketable Error:</strong>
            Missing file NSA_WP_Plugin.php.  Please reinstall the plugin.</p></div>'); 
    });


} else {
    
    require_once 'NSA_WP_Plugin.php';


    class wp_facebook_pixel extends NSA_WP_Plugin {
        

        //----------------------------CONSTANTS----------------------------//
        /**
         * ID/Domain of plugin
         */
        public $PLUGIN_ID = 'nsa_wpfbp';
        const PLUGIN_ID = 'nsa_wpfbp';

        /**
         * Readable name of plugin
         */
        public $PLUGIN_NAME = 'remarketable';
        
        /**
         * Current Plugin version
         */
        public $PLUGIN_VERSION = '5.0';
        public $PLUGIN_VERSION_NAME = 'Entlebucher Mountain Dog';

        /**
         * URL to Notification JSON
         */
        public $NOTIFICATION_URL = 'http://nightshiftapps.com/nsa_wpfbp_notifications.txt';



        


        //------------------------PUBLIC PROPERTIES------------------------//
        public $facebook_pixel_id;
        public $plugin_file;
        const PLUGIN_FILE = __FILE__;
        public $ProEnabled = false;

        public $Product_Id = false;

        public $Tracking_Exclusion;

        public $Product_Value = false;
        public $Delay_View_Content = 0;

        public $Track_Title = false;
        public $TrackViewDuration = false;
        public $TrackAllTerms = false;
        public $TrackedTerms = false;

        public $TrackAllKeys = false;
        public $TrackedKeys = false;

        public $product_send_ViewContent = false;
        public $product_ViewContent_Value = true;
        
        public $product_send_AddToCart = false;
        public $product_AddToCart_Value = true;

        public $shop_send_AddToCart = false;
        public $shop_AddToCart_Value = true;

        public $order_recieved_send_purchase = false;





        public $ame_options;
        public $pro_licesnse_email;
        public $pro_licesnse_key;


        public function __construct() {
            $this->plugin_file = __FILE__;
			if (file_exists(dirname(__FILE__).'/pro_assets/nsa_wpfbp-pro.php')) $this->PLUGIN_NAME.=' Pro';
						
			
            parent::__construct($this->PLUGIN_ID, $this->PLUGIN_NAME, $this->PLUGIN_VERSION, 'administrator', $this->NOTIFICATION_URL);

            //Must be registered from this file
            //register_activation_hook(__FILE__, function(){});
            //register_deactivation_hook( __FILE__, 'nsa_wp_facebook_pixel_plugin_on_deactivation' );
            //register_uninstall_hook( __FILE__, 'nsa_wp_facebook_pixel_plugin_on_uninstall' );


            add_action('init', array( $this, 'Update'), 10000);
            

            ////Set up the Updater
            //require '/pro_assets/updater/plugin-update-checker.php';
            //$myUpdateChecker = PucFactory::buildUpdateChecker(
            //    $this->UPDATE_URL,
            //    __FILE__
            //);


        }



        

        public function init() {
            //-------------------------LOAD ASSETS-------------------------//
            $this->LoadAsset('assets/NSAFaceBookPixel.php');


            add_filter('plugin_action_links', array( $this, 'AddPluginAction' ), 10, 5 );           //Adds special links to Plugin's page
            add_action('admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts'));
            add_action('wp_enqueue_scripts', array( $this, 'enqueue_scripts'));

            add_action('admin_head', array($this, 'CheckSettings'));
            
            $this->ame_options 				= get_option($this->settings->get_tab_id('pro_activation'));
            $this->pro_licesnse_email       = $this->ame_options[$this->settings->get_field_id('pro_activation', 'license_email')];
            $this->pro_licesnse_key         = $this->ame_options[$this->settings->get_field_id('pro_activation', 'license_key')];
        
        }



        public function Update() {
            if($this->facebook_pixel_id == false || $this->Product_Id == false) {
                
                //Get Current Settings
                $PluginOptions = $this->settings->get_override(false);


                //Upgrade Facebook Pixel Id
                if($this->facebook_pixel_id == false) {

                    
                    //Get Pixel ID from Previous Version
                    $facebook_pixel_id = cmb2_get_option('ksp_wp_facebook_pixel_options', 'facebook_pixel_id');


                    //Get Pixel ID from Previous Version Code
                    if($facebook_pixel_id == false) {
                        $facebook_pixel = cmb2_get_option('ksp_wp_facebook_pixel_options', 'facebook_pixel');
                        if($facebook_pixel != false) {
                            $re = "/fbq\\s*\\(\\s*('|\")init('|\")\\s*,\\s*('|\")(.*?)('|\")\\s*\\)\\s*;/"; 
                            preg_match_all($re, $facebook_pixel, $matches);

                            $facebook_pixel_id = isset($matches[4][0]) ? $matches[4][0] : false;
                        }
                    }

                    if($facebook_pixel_id != false) {
                        $PluginOptions[$this->settings->get_field_id('general', 'facebook_pixel_id')] = $facebook_pixel_id;
                    }
                }
                    

                //Upgrade Product Id
                if($this->Product_Id == false) { 
                    //Pre 3.0
                    $woocommerce_options = cmb2_get_option('ksp_wp_facebook_pixel_options', 'woocommerce_options');
                    if($woocommerce_options != false) {
                        $PluginOptions[$this->settings->get_field_id('woocommerce', 'product_id')] = $woocommerce_options[0]['content_id']; 
                    }
                }



                $this->settings->update_override(false, $PluginOptions);

            }
        }



        public function load_settings() {
            //General
            $this->facebook_pixel_id = $this->settings->get_value('general', 'facebook_pixel_id');
            $this->Track_Title = $this->settings->get_value('general','track_title');
            $this->Delay_View_Content = ($this->settings->get_value('general', 'delay_view_content')) * 1000;
            $this->TrackViewDuration = $this->settings->get_value('general', 'track_view_duration');
            $this->Track_Search = $this->settings->get_value('general', 'track_search');
            $this->Tracking_Exclusion = $this->settings->get_value('general', 'tracking_exclusion');




            //Category Tracking
            $this->TrackAllTerms = $this->settings->get_value('category_tracking', 'track_all_terms');

            //Build a setting which looks exactly like the return from get_post_terms().  This way we can compare with array_intersect_assoc()
            $taxonomies_tracked = $this->settings->get_value('category_tracking', 'term_tracking');
            if($taxonomies_tracked != false) {
                $tracked_terms = array();
                foreach ($taxonomies_tracked[0] as $key => $value)
                {
            	    $tracked_terms[str_replace('track_category_', '', $key)] = $value;
                }
                $this->TrackedTerms = $tracked_terms;
            }

            //Key Tracking
            $this->TrackAllKeys = $this->settings->get_value('key_tracking', 'track_all_keys');

            $keys_tracked = $this->settings->get_value('key_tracking', 'key_tracking');
            if($keys_tracked != false) {
                $tracked_keys = array();
                foreach ($keys_tracked[0] as $key => $value)
                {
            	    $tracked_keys[str_replace('track_keys_', '', $key)] = array_flip($value);
                }
                $this->TrackedKeys = $tracked_keys;
            }

            //WooCommerce Options
            $this->Product_Id = $this->settings->get_value('woocommerce', 'product_id');
            $this->Product_Value = $this->settings->get_value('woocommerce', 'product_value');

            $this->product_send_ViewContent = $this->settings->get_value('woocommerce', 'product_send_ViewContent');
            $this->product_ViewContent_Value = $this->settings->get_value('woocommerce', 'product_ViewContent_Value');

            $this->product_send_AddToCart = $this->settings->get_value('woocommerce', 'product_send_AddToCart');
            $this->product_AddToCart_Value = $this->settings->get_value('woocommerce', 'product_AddToCart_Value');

            $this->shop_send_AddToCart = $this->settings->get_value('woocommerce', 'shop_send_AddToCart');
            $this->shop_AddToCart_Value = $this->settings->get_value('woocommerce', 'shop_AddToCart_Value');

            $this->order_recieved_send_purchase = $this->settings->get_value('woocommerce', 'order_recieved_send_purchase');
            
        }




        /**
         * Display warning if settings are not configured
         */
        public function CheckSettings() {
            if($this->facebook_pixel_id == false) {
                nsau_AdminNotice(
                    $this->PLUGIN_NAME, 
                    'Pixel ID Missing',
                    'Please visit the <a href="options-general.php?page='.$this->PLUGIN_ID.'_settings&tab='.$this->settings->get_tab_id('general').'">' . __('Settings', 'General') . '</a> to set your Facebook Pixel ID.', 
                    $this->PLUGIN_ID,
                    'red',
                    false,
                    0,
                    array('settings_page_nsa_wpfbp_settings'));
                
                return;
            }
        }




        




        //public function ActivatePlugin() {
            //global $wpdb;
            //$current = $wpdb->get_var( "SELECT option_value FROM $wpdb->options where option_name = 'nsa_wpfbp_settings';" );
            //$previous = $wpdb->get_var( "SELECT option_value FROM $wpdb->options where option_name = 'ksp_wp_facebook_pixel_options';" );
        //}


        ///**
        // * Adds actions to Plugin listing on Plugins page.
        // * 
        // * Hooked to the 'plugin_action_links' filter.
        // * @param array $actions 
        // * @param string $plugin_file 
        // * @return array
        // */
        public function AddPluginAction( $actions, $plugin_file ) 
        {
            static $plugin;

            if (!isset($plugin))
                $plugin = plugin_basename(__FILE__);

            if ($plugin == $plugin_file) {
                $settings = array('settings' => '<a href="options-general.php?page='.$this->plugin_id.'_settings'.'">' . __('Settings', 'General') . '</a>');
                $actions = array_merge($settings, $actions);
            }
            
            return $actions;
        }

        

        public function admin_enqueue_scripts() {
            if (is_admin())   
            {  
                wp_enqueue_script('NSAValidateMetaBox', plugins_url('/framework/ValidateMetaBox.js', __FILE__), false, false, false);
                wp_enqueue_style('NSAMetaBoxStyle', plugins_url('/inc/css/NSAMetaBox.css', __FILE__), false, false, false);
            } 
        }
        public function enqueue_scripts() {
            if (!is_admin()) {
                wp_enqueue_script('jquery');
            }

        }




    }


    global $WPFacebookPixel;
    $WPFacebookPixel = new wp_facebook_pixel();
}


