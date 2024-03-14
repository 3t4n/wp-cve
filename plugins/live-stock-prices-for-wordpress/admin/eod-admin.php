<?php
if(!class_exists('EOD_Stock_Prices_Admin')) {
    class EOD_Stock_Prices_Admin {
        protected $fd_admin;
        protected $financials_admin;
        public $user_data;

        /**
         * Prepare plugin hooks / filters
         */
        public function __construct(){
            $this->fd_admin = new EOD_Fundamental_Data_Admin();
            $this->financials_admin = new EOD_Financials_Admin();

            add_filter( 'plugin_action_links_'.EOD_BASENAME, array(&$this, 'add_plugins_list_link') );

            add_action( 'admin_menu', array(&$this,'admin_menu'), 3);
            add_action( 'admin_init', array(&$this,'admin_settings') );
            add_action( 'admin_init', array(&$this,'add_notices') );
            add_action( 'api_key_error', array(&$this,'api_key_error') );
            add_action( 'admin_footer', array(&$this,'admin_footer') );
            add_action( 'admin_enqueue_scripts',  array(&$this,'admin_scripts'));

            /**
             * define account data
             */
            $this->get_user_data();
        }

        /**
         * Add links in description on the plugins page
         * @param $links
         * @return mixed
         */
        function add_plugins_list_link( $links ) {
            $links[] = '<a style="color: #f06a40; font-weight: bold;" href="https://eodhd.com/pricing" target="_blank">' . __( 'Pricing' ) . '</a>';
            $links[] = '<a href="admin.php?page=eod-stock-prices">' . __( 'Settings' ) . '</a>';
            $links[] = '<a href="https://eodhd.com/" target="_blank">' . __( 'EOD Historical Data' ) . '</a>';
            $links[] = '<a href="https://eodhd.com/financial-apis/" target="_blank">' . __( 'Documentation' ) . '</a>';
            return $links;
        }

        /**
         *
         */
        public function admin_scripts( $hook ){
            global $eod;

            //css
            wp_enqueue_style( 'eod_stock_admin_css',plugins_url('/css/eod-stock-prices-admin.css',__FILE__), array(), EOD_VER );

            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-sortable' );
            wp_enqueue_script( 'jquery-ui-draggable' );
            wp_enqueue_script( 'eod-fundamental-data', EOD_URL . 'admin/js/eod-fundamental.js', array('jquery', 'jquery-ui-draggable', 'jquery-ui-sortable', 'eod-admin'), EOD_VER );
            wp_enqueue_script( 'eod-admin', EOD_URL . 'admin/js/eod-admin.js', array('jquery'), EOD_VER );

            // Color picker
            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_style( 'wp-color-picker' );

            // Add ajax vars
            wp_add_inline_script( 'eod-admin', 'let eod_ajax_nonce = "'.wp_create_nonce('eod_ajax_nonce').'", eod_ajax_url = "'.admin_url('admin-ajax.php').'";', 'before' );

            // Add display vars
            wp_localize_script( 'eod-admin', 'eod_display_settings', $eod->get_js_display_settings());
            wp_localize_script( 'eod-admin', 'eod_service_data', $eod->get_js_service_data());

            // Only for widget.php page
            if ( $hook == 'widgets.php' ) {
                wp_enqueue_script( 'eod_stock_widget_js', plugins_url('/js/eod-widget-form.js', __FILE__), array('eod-admin'), EOD_VER, true);
            }
        }

        /**
         * Add an admin menu entry for options page
         */
        public function admin_menu(){
            global $menu, $submenu;

            add_menu_page('Stock Prices Plugin', 'EODHD Financial and Stocks Market Data', 'manage_options', 'eod-stock-prices', array(&$this,'general_page'),'data:image/svg+xml;base64,' . base64_encode('<svg width="68" height="68" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M25.0952 17.4624V14H18.9048V17.4624H17V28.3441H18.9048V60H25.0952V28.3441H27V17.4624H25.0952Z" fill="white"/><path d="M32.2222 6V27H30V57H32.2222V63H37.2222V57H40V27H37.2222V6H32.2222Z" fill="white"/><path d="M50.3684 9.5H45.6316V13.5H43V34.5H45.6316V41.5H50.3684V34.5H53V13.5H50.3684V9.5Z" fill="white"/></svg>') );
            add_submenu_page('eod-stock-prices', 'Quick Start & Samples', 'Quick Start & Samples', 'manage_options', 'eod-stock-prices', array(&$this,'general_page') );

            //add_submenu_page('eod-stock-prices', 'Widgets Customization', 'Widgets customization', 'manage_options', 'eod-widgets', array(&$this,'widgets_page') );
            add_submenu_page('eod-stock-prices', 'Shortcode Generator', 'Shortcode Generator', 'manage_options', 'eod-examples', array(&$this,'examples_page') );
            add_submenu_page('eod-stock-prices', 'Settings', 'Settings', 'manage_options', 'eod-settings', array(&$this,'settings_page') );

            // Permalinks
            // $permalink = get_admin_url().'edit.php?post_type=fundamental-data';
            // $submenu['eod-stock-prices'][] = array( 'Test page', 'manage_options', $permalink, 'aaa', 'bbb' );

            // Add css classes
            foreach( $menu as $key => $value ){
                if( 'EODhd Financial and Stocks Market Data' == $value[0] )
                    $menu[$key][4] .= " eod_admin_menu";
            }
        }

        /**
         * Display errors about API key
         */
        public function api_key_error() {
            if(get_transient( 'eod_admin_notice_invalid_key' )){
                ?>
                <div class="notice notice-error is-dismissible">
                    <p>Your API key seems to be wrong. Please, take the API key from the email which was sent after the registration, or you can get it from <a href="https://eodhd.com/cp/settings?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp" target="_blank"> the settings website section</a>.</p>
                    <p>Didn't register yet? You can do it by the <a href="https://eodhd.com/?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp" target="_blank">click here</a>.</p>
                </div>
                <?php
            }
        }
        public function admin_footer() {
            // delete transient
            if( get_transient( 'eod_admin_notice_invalid_key' ) ) {
                delete_transient( 'eod_admin_notice_invalid_key' );
            }
        }

        /**
         * Displays notices
         */
        public function add_notices() {
            global $eod_api;
            $key = $eod_api->get_eod_api_key();

            // Used demo key
            if( $key === EOD_DEFAULT_API )
                add_action( 'admin_notices', array(&$this, 'eod_notice_used_demo_key') );

            add_action( 'admin_notices', array(&$this,'admin_settings_notices') );
        }
        public function eod_notice_used_demo_key() {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><b><?= EOD_PLUGIN_NAME ?> Warning:</b> The plugin works in free trial mode, your demo API key allows you to obtain limited ticker's data AAPL.US | TSLA.US  | VTI.US | AMZN.US | BTC-USD | EUR-USD.</p>
                <p>
                    <b>Please, <a target="_blank" href="https://eodhd.com/register/?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp">get your own API Key</a>.</b>
                </p>
            </div>
            <?php
        }
        public function admin_settings_notices(){
            settings_errors( 'eod_options' );
        }

        /**
         * Prepare the options registering fields (for admin configuration page)
         */
        public function admin_settings(){
            register_setting( 'eod_options', 'eod_options', array(&$this, 'eod_options_validate'));
            register_setting( 'eod_display_settings', 'eod_display_settings', array(&$this, 'eod_options_validate'));
        }

        /**
         * Validates the options form
         * @param $input
         * @return mixed
         */
        function eod_options_validate($input) {
            // Check API key
            if($input['api_key']){

            }
            $output = array();
            foreach( $input as $key => $value ) {
                if( isset( $value ) ) {
                    $output[$key] = strip_tags( stripslashes( $value ) );
                }

                // Check API key
                if( $key === 'api_key' ){
                    global $eod_api;
                    $user_data = $this->user_data;

                    // Key is invalid
                    if($user_data && isset($user_data['error_code']) && $user_data['error_code'] === 'unauthenticated' ) {
                        // Show WP notice after refresh
                        set_transient( 'eod_admin_notice_invalid_key', true, 5 );
                        // Use old key
                        $output[$key] = $eod_api->get_eod_api_key();
                    }
                }
            }
            return $output;
        }


        /**
         * Get user information by API key
         */
        public function get_user_data()
        {
            global $eod_api;
            $key = $eod_api->get_eod_api_key();
            $apiUrl = 'https://eodhd.com/api/user/?api_token='.$key;
            $result = $eod_api->call_eod_api($apiUrl);
            if(!isset($result['errors']))
                $this->user_data = $result;
        }

        /**
         * Subscription info
         */
        public function get_subscription()
        {
            global $eod_api;
            if(!$this->user_data || $eod_api->get_eod_api_key() === EOD_DEFAULT_API) return 'free';
            return $this->user_data['subscriptionType'];
        }


        /**
         * Displays the general page
         */
        public function general_page(){
            echo eod_load_template(
                'admin/template/general.php',
                array()
            );
        }

        /**
         * Displays the settings page
         */
        public function settings_page(){
            echo eod_load_template(
                'admin/template/settings.php',
                array()
            );
        }
        
        /**
         * Displays the examples page
         */
        public function examples_page(){
            global $eod_api;
            echo eod_load_template(
                'admin/template/examples.php',
                array('api_key' => $eod_api->get_eod_api_key())
            );
        }

        /**
         * Displays the widgets settings
         */
        public function widgets_page(){
            echo eod_load_template(
                'admin/template/widgets.php',
                array()
            );
        }

    }
}

