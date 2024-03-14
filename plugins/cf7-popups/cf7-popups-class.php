<?php
/**
* Plugin main class
*
*
*/


if ( !class_exists( 'CF7_POPUPS' ) ):

    class CF7_POPUPS {

        /**
         * A reference to an instance of this class.
         *
         * @since  1.0.0
         * @access private
         * @var    object
         */
        private static $instance = null;


         /**
         * Returns the instance.
         *
         * @since  1.0.0
         * @access public
         * @return object
         */
        public static function get_instance() {
            // If the single instance hasn't been set, set it now.
            if ( null == self::$instance ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        /**
         * Sets up needed actions/filters for the plugin to initialize.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function __construct() {
          
            add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
            add_action('wp_enqueue_scripts', array($this,'cf7_popups_load_styles') ); 
            add_action('wp_enqueue_scripts',array ($this, 'cf7_popups_load_scripts') );
            add_action('admin_enqueue_scripts',array ($this, 'cf7_popups_admin') );
            add_filter( 'plugin_action_links_' . CF7_POPUPS_BASENAME, array($this,'plugin_pro_link_action') );
            add_action( 'admin_notices', array( $this,'cf7_poppus_notice') );
            add_action( 'wp_ajax_cf7_popups_ajax_notice_handler', array( $this,'cf7_popups_ajax_notice_handler') );
            
        }


        /**
         * Loads the translation files.
         *
         * @since 1.0.0
         * @access public
         * @return void
         */
        public function load_plugin_textdomain() {

            load_plugin_textdomain( 'cf7-popups', false, basename( dirname( __FILE__ ) ) . '/languages' );
        }




        /**
        * Enqueue styles for frontend
        */
        function cf7_popups_load_styles() {
            

        $view_assets = CF7_POPUPS_URL.'/views/assets/';

        wp_enqueue_style( 'sweetalert2', $view_assets . 'css/sweetalert2.min.css', array(), CF7_POPUPS_VER ); 
        wp_enqueue_style( 'cf7-popups-frontend', $view_assets . 'css/frontend.css', array(), CF7_POPUPS_VER ); 
            
        }



        /**
        * Enqueue scripts for frontend
        */
        function cf7_popups_load_scripts() {

            $view_assets = CF7_POPUPS_URL.'/views/assets/';
            wp_enqueue_script( 'sweetalert2', $view_assets . 'js/sweetalert2.min.js', array(), CF7_POPUPS_VER, true );
            wp_register_script( 'cf7-popups-frontend', $view_assets . 'js/cf7-popups.js', array( 'jquery' ), CF7_POPUPS_VER, true );

            $localize_options =  array(
                'msg1' => esc_html__('Validation Error','cf7-popups'),
                'msg2' => esc_html__('One or more field validation error','cf7-popups'),
                'msg3' => esc_html__('Error','cf7-popups'),
                'msg4' => esc_html__('Failed to send email because possible spam activity has been detected.','cf7-popups'),
                'msg5' => esc_html__('Failed to send email.','cf7-popups'),
                'msg6' => esc_html__('Email Sent','cf7-popups'),
                'msg7' => esc_html__('Thank you for your message. It has been sent.','cf7-popups'),
                );

            wp_localize_script( 'cf7-popups-frontend', 'cf7_popups_val', $localize_options  );
            wp_enqueue_script( 'cf7-popups-frontend' );
                
            
        }
        

        
        /**
        * Enqueue scripts for backend
        */
        function cf7_popups_admin(){
            $view_assets = CF7_POPUPS_URL.'/views/assets/';
            wp_register_script( 'cf7-popups-admin', $view_assets . 'js/cf7-admin.js', array( 'jquery' ), CF7_POPUPS_VER, true );

            $localize_options =  array(
                'ajax_nonce'    => wp_create_nonce('cf7-popup-cww')
               
                );

            wp_localize_script( 'cf7-popups-admin', 'cf7_popups_admin', $localize_options  );
            wp_enqueue_script( 'cf7-popups-admin' );
        }

        /**
         * AJAX handler to store the state of dismissible notices.
         */
        function cf7_popups_ajax_notice_handler() {

            
            if( check_ajax_referer( 'cf7-popup-cww', $_POST['security'], false ) ){
                return;
            }
            
            $type = $_POST['type'];
            // Store it in the options table
            update_option( 'dismissed-' . $type, TRUE );
        }

        public function plugin_pro_link_action( $links ) {
         
            $links[] = '<a href="https://codeworkweb.com/wordpress-plugins/contact-form-power-pack/" target="_blank" style="color:#05c305; font-weight:bold;">'.esc_html__('Upgrade To Pro','cf7-popups').'</a>';
            return $links;
        }

        function cf7_poppus_notice(){

            if ( get_option('dismissed-notice-upgrade-code-cf7pp-cww', FALSE ) ) { 
                return;
            }
            
            if(! class_exists('CF7_Power_Pack') ){
                ?>
                <div class="notice-upgrade-cf7pp updated notice is-dismissible" data-notice="notice-upgrade-code-cf7pp-cww">
                    <p>
                        <?php esc_html_e('Power pack for contact form is now available with more stunning features and flexiblity. Use coupon code "POWERPACK" for 10% off','cf7-popups')?>
                    </p>
                    <p class="actions">
                        <a href="https://codeworkweb.com/wordpress-plugins/contact-form-power-pack/" target="_blank" ><?php echo esc_html__('View Details','cf7-popups'); ?></a>
                    </p>
                </div>
                <?php 
            }
        }


    }
endif;

if ( !function_exists( 'cf7_popups_init' ) ) {

    /**
     * Returns instanse of the plugin class.
     *
     * @since  1.0.0
     * @return object
     */
    function cf7_popups_init() {
        return CF7_POPUPS::get_instance();
    }

}

cf7_popups_init();