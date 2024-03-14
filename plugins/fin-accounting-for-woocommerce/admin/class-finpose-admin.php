<?php

/**
 * The admin-specific functionality of the plugin.
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 * @link              https://finpose.com
 * @since             1.0.0
 * @package           Finpose
 * @author            info@finpose.com
 */
if ( !class_exists( 'finpose_Admin' ) ) {
    class finpose_Admin
    {
        /**
         * The ID of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string    $plugin_name    The ID of this plugin.
         */
        private  $plugin_name ;
        /**
         * The version of this plugin.
         *
         * @since    1.0.0
         * @access   private
         * @var      string    $version    The current version of this plugin.
         */
        private  $version ;
        private  $hook_suffixes = array() ;
        /**
         * Initialize the class and set its properties.
         *
         * @since    1.0.0
         * @param      string    $plugin_name       The name of this plugin.
         * @param      string    $version    The version of this plugin.
         */
        public  $pageName = '' ;
        public function __construct( $plugin_name, $version )
        {
            $this->plugin_name = $plugin_name;
            $this->version = $version;
        }
        
        /**
         * Register the stylesheets for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueue_styles( $hook = '' )
        {
            //if( empty( $hook ) ) $hook = bp_core_do_network_admin() ? str_replace( '-network', '', get_current_screen()->id ) : get_current_screen()->id;
            
            if ( in_array( $hook, $this->hook_suffixes ) ) {
                wp_enqueue_style( 'jquery-ui-css', FINPOSE_BASE_URL . 'assets/lib/jqueryui/jquery-ui.min.css' );
                wp_enqueue_style(
                    'finhelper',
                    FINPOSE_BASE_URL . 'assets/css/fin_helper.css',
                    array(),
                    $this->version,
                    'all'
                );
                wp_enqueue_style(
                    'fincss',
                    FINPOSE_BASE_URL . 'assets/css/finpose.css',
                    array(),
                    $this->version,
                    'all'
                );
                wp_enqueue_style(
                    'toastr',
                    FINPOSE_BASE_URL . 'assets/css/toastr.min.css',
                    array(),
                    $this->version,
                    'all'
                );
            }
        
        }
        
        /**
         * Register the JavaScript for the admin area.
         *
         * @since    1.0.0
         */
        public function enqueue_scripts( $hook = '' )
        {
            $screen = get_current_screen();
            $pageName = str_replace( 'accounting_page_fin_', '', $screen->id );
            $pageName = str_replace( 'toplevel_page_fin_', '', $pageName );
            $this->pageName = str_replace( 'admin_page_fin_', '', $pageName );
            
            if ( in_array( $hook, $this->hook_suffixes ) ) {
                add_thickbox();
                wp_enqueue_script( 'jquery-ui-datepicker' );
                wp_enqueue_script(
                    'jqblock',
                    FINPOSE_BASE_URL . 'assets/js/jquery.blockUI.js',
                    array( 'jquery' ),
                    $this->version,
                    true
                );
                wp_enqueue_script(
                    'finmain',
                    FINPOSE_BASE_URL . 'assets/js/main.js',
                    array( 'jquery' ),
                    $this->version,
                    true
                );
                wp_enqueue_script(
                    'vue',
                    FINPOSE_BASE_URL . 'assets/js/vue.js',
                    array(),
                    $this->version,
                    false
                );
                if ( in_array( $this->pageName, array( 'taxes', 'vendors', 'reports' ) ) ) {
                    wp_enqueue_script(
                        'vuerouter',
                        FINPOSE_BASE_URL . 'assets/js/vue-router.min.js',
                        array( 'vue' ),
                        $this->version,
                        false
                    );
                }
                wp_enqueue_script(
                    'vuepage',
                    FINPOSE_BASE_URL . 'assets/js/pages/' . $this->pageName . '.js',
                    array( 'vue', 'finmain' ),
                    $this->version,
                    true
                );
                if ( in_array( $this->pageName, array( 'dashboard' ) ) ) {
                    wp_enqueue_script(
                        'finchart',
                        FINPOSE_BASE_URL . 'assets/js/Chart.min.js',
                        array( 'jquery' ),
                        $this->version,
                        false
                    );
                }
                wp_enqueue_script(
                    'table2csv',
                    FINPOSE_BASE_URL . 'assets/js/jquery.tabletoCSV.js',
                    array( 'jquery' ),
                    $this->version,
                    false
                );
                wp_enqueue_script(
                    'toastr',
                    FINPOSE_BASE_URL . 'assets/js/toastr.min.js',
                    array( 'jquery' ),
                    $this->version,
                    false
                );
                $symbol = '';
                if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
                    $symbol = get_woocommerce_currency_symbol();
                }
                if ( function_exists( 'get_woocommerce_currency' ) ) {
                    $currency = get_woocommerce_currency();
                }
                wp_localize_script( 'finmain', 'ajax_object', array(
                    'ajaxurl'  => admin_url( 'admin-ajax.php' ),
                    'currency' => $currency,
                    'symbol'   => $symbol,
                    'siteurl'  => FINPOSE_WP_URL,
                    'finurl'   => FINPOSE_BASE_URL,
                    'nonce'    => wp_create_nonce( 'finpost' ),
                ) );
            }
        
        }
        
        /**
         * Build Admin Menu
         *
         * @since    1.0.0
         */
        public function buildmenu()
        {
            $this->hook_suffixes[] = add_menu_page(
                true,
                __( 'Accounting', 'finpose' ),
                'view_woocommerce_reports',
                'fin_dashboard',
                array( $this, 'pageDisplay' ),
                'dashicons-list-view',
                57
            );
            $this->hook_suffixes[] = add_submenu_page(
                'fin_dashboard',
                __( 'Dashboard', 'finpose' ),
                __( 'Dashboard', 'finpose' ),
                'view_woocommerce_reports',
                'fin_dashboard',
                array( $this, 'pageDisplay' )
            );
            $this->hook_suffixes[] = add_submenu_page(
                null,
                __( 'Spendings', 'finpose' ),
                __( 'Spendings', 'finpose' ),
                'view_woocommerce_reports',
                'fin_spendings',
                array( $this, 'pageDisplay' )
            );
            $this->hook_suffixes[] = add_submenu_page(
                null,
                __( 'Orders', 'finpose' ),
                __( 'Orders', 'finpose' ),
                'view_woocommerce_reports',
                'fin_orders',
                array( $this, 'pageDisplay' )
            );
            $this->hook_suffixes[] = add_submenu_page(
                null,
                __( 'Taxes', 'finpose' ),
                __( 'Taxes', 'finpose' ),
                'view_woocommerce_reports',
                'fin_taxes',
                array( $this, 'pageDisplay' )
            );
            $this->hook_suffixes[] = add_submenu_page(
                null,
                __( 'Accounts', 'finpose' ),
                __( 'Accounts', 'finpose' ),
                'view_woocommerce_reports',
                'fin_accounts',
                array( $this, 'pageDisplay' )
            );
            $this->hook_suffixes[] = add_submenu_page(
                null,
                __( 'Settings', 'finpose' ),
                __( 'Settings', 'finpose' ),
                'view_woocommerce_reports',
                'fin_settings',
                array( $this, 'pageDisplay' )
            );
            // HIDDEN PAGES
            $this->hook_suffixes[] = add_submenu_page(
                null,
                __( 'Categories', 'finpose' ),
                __( 'Categories', 'finpose' ),
                'view_woocommerce_reports',
                'fin_categories',
                array( $this, 'pageDisplay' )
            );
            $this->hook_suffixes[] = add_submenu_page(
                null,
                __( 'Inventory Items', 'finpose' ),
                __( 'Inventory Items', 'finpose' ),
                'view_woocommerce_reports',
                'fin_inventory_items',
                array( $this, 'pageDisplay' )
            );
        }
        
        /**
         * Display requested page
         */
        public function pageDisplay()
        {
            $handlers = array(
                'finhome'    => 'orders',
                'accounts'   => 'accounts',
                'spendings'  => 'spendings',
                'inventory'  => 'inventory',
                'categories' => 'spendings',
                'taxes'      => 'taxes',
                'dashboard'  => 'orders',
                'reports'    => 'reporting',
                'orders'     => 'orders',
                'settings'   => 'settings',
                'vendors'    => 'vendors',
            );
            $processes = array(
                'finhome'    => 'getHome',
                'accounts'   => 'pageAccounts',
                'spendings'  => 'getCosts',
                'inventory'  => 'pageInventory',
                'categories' => 'getCategories',
                'taxes'      => 'getTaxes',
                'dashboard'  => 'pageDashboard',
                'orders'     => 'pageOrders',
                'reports'    => 'getReport',
                'vendors'    => 'pageVendors',
                'settings'   => 'pageSettings',
            );
            
            if ( current_user_can( 'view_woocommerce_reports' ) ) {
                
                if ( isset( $handlers[$this->pageName] ) ) {
                    $hc = $handlers[$this->pageName];
                    require_once FINPOSE_PLUGIN_DIR . 'classes/' . $hc . '.class.php';
                    $hn = 'fin_' . $hc;
                    $proc = null;
                    if ( isset( $processes[$this->pageName] ) ) {
                        $proc = $processes[$this->pageName];
                    }
                    $handler = new $hn( $proc );
                }
                
                include 'views/' . $this->pageName . '.php';
            } else {
                printf( '<div class="notice notice-error is-dismissible"><p><strong>%s</strong></p></div>', esc_html__( 'You are not allowed to display this page. Please contact administrator.', 'finpose' ) );
            }
        
        }
    
    }
}