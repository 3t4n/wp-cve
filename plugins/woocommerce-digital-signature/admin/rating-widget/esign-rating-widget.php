<?php

/**
 *
 * @package esignWoocommerceRatingWidget
 * @author  Arafat Rahman <arafatrahmank@gmail.com>
 */
if (!class_exists('esignWoocommerceRatingWidget')) :

    class esignWoocommerceRatingWidget{

        /**
         * Instance of this class.
         * @since    1.0.1
         * @var      object
         */
        protected static $instance = null;
        public $name;
        private $feedbackURL,$rattingURL;

        /**
         * Slug of the plugin screen.
         * @since    1.0.1
         * @var      string
         */
        protected $plugin_screen_hook_suffix = null;

        /**
         * Initialize the plugin by loading admin scripts & styles and adding a
         * settings page and menu.
         * @since     0.1
         */
        public function __construct() {
            /*
             * Call $plugin_slug from public plugin class.
             */

            $this->feedbackURL = 'https://www.approveme.com/plugin-feedback/';
            $this->rattingURL = 'https://wordpress.org/support/plugin/woocommerce-digital-signature/reviews/#new-post';
            
            add_action('esig_admin_notices', array($this, 'esignRatingWidget'));
            add_action('admin_enqueue_scripts', array($this, 'enqueueAdminStyles'));
            add_action('admin_enqueue_scripts', array($this, 'enqueueAdminScripts'));
            add_action('wp_ajax_esig_woocommerce_ratting_widget_remove', array($this, 'esigWoocommerceRattingWidgetRemove'));
        
        }

        public function esigWoocommerceRattingWidgetRemove() {                     
            update_option('remove_rating_widget_woocommerce','Yes');
            die();
        }
        
         public function enqueueAdminStyles() {
            $screen = get_current_screen();
            $current = $screen->id;
            
            if (($current == 'toplevel_page_esign-docs')) {
                wp_enqueue_style('esig-woocommerce-rating-widget-admin-styles', plugins_url('assets/css/esign-rating-widget.css', __FILE__), array(), '0.1.1');
            }
        }


        public function enqueueAdminScripts() {



            $screen = get_current_screen();
            $current = $screen->id;          

            
            if (($current == 'toplevel_page_esign-docs')) {
              
                 wp_enqueue_script('woocommerce-rating-widget-admin-script', plugins_url('assets/js/rating-widget-control.js', __FILE__), array('jquery', 'jquery-ui-dialog'), '0.1.1', true);
            }

        }
        
        
        
        public static function checkSignedDoc($metakey){
            
            $alldocid = WP_E_Sig()->meta->getall_bykey($metakey);
            $arrayValue = json_decode(json_encode($alldocid),true);

            $signature = 0;
            foreach ($arrayValue as $value) {
                

                $getStatus = WP_E_Sig()->document->getStatus($value['document_id']);
                
                if($getStatus == 'signed'){
                    $signature++;
                }
            }

            if($signature >= 5) return true;
            
            return false;
           
           
            
        }


 
        public function esignRatingWidget(){
            
             if (!function_exists('WP_E_Sig')) return false;
            
             $screen = get_current_screen();
                           
             if( $screen->id != 'toplevel_page_esign-docs') return false;
             
             $checkWidget = get_option('remove_rating_widget_woocommerce');
            

             if($checkWidget == "Yes") return false;       
  
             $checkRequierment = self::checkSignedDoc('esig_woocommerce_ratting_doc_id');

             if(!wp_validate_boolean($checkRequierment)) return false;           

             $api = new WP_E_Api();
              
             $data = array("form_name" => ' Woocommerce',"feedback_url"=>$this->feedbackURL,"plugin_url"=>$this->rattingURL);
             $displayNotice = dirname(__FILE__) . '/views/esig-ratting-widget-view.php';
             $api->view->renderPartial('', $data, true, '', $displayNotice);
          
        }
        
         /**
         * Return an instance of this class.
         * @since     0.1
         * @return    object    A single instance of this class.
         */
        public static function get_instance() {

            // If the single instance hasn't been set, set it now.
            if (null == self::$instance) {
                self::$instance = new self;
            }

            return self::$instance;
        }
        

        

    }

    

    
endif;

