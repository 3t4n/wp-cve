<?php
defined( 'ABSPATH' ) || exit;
/**
 * pos bridge class.
 */
class Pos_Bridge {
    /**
     * Represents the slug of hte plugin that can be used throughout the plugin
     * for internationalization and other purposes.
     *
     * @access protected
     * @var    string   $plugin_slug    The single, hyphenated string used to identify this plugin.
     */
    protected $plugin_slug;
    /**
     * Maintains the current version of the plugin so that we can use it throughout
     * the plugin.
     *
     * @access protected
     * @var    string   $version    The current version of the plugin.
     */
    protected $version;
    private $namespace = 'pos-bridge';
    private $staff_management;
    private $pos_bridge_product;
    private $pos_bridge_order;
    private $pos_bridge_user;
    private $pos_bridge_tax;
    private $pos_bridge_miscellaneous;
    /**
     * Instantiates the plugin by setting up the core properties and loading
     * all necessary dependencies and defining the hooks.
     *
     * The constructor will define both the plugin slug and the verison
     * attributes, but will also use internal functions to import all the
     * plugin dependencies, and will leverage the Single_Post_Meta_Loader for
     * registering the hooks and the callback functions used throughout the
     * plugin.
     */
    public function __construct() {
        $this->plugin_slug = 'oliver-pos-bridge-slug';
        $this->version = '1.0.0';
        $this->oliver_pos_define_constants();
        $this->oliver_pos_includes();
        $this->oliver_pos_init_hooks();
        $this->oliver_pos_create_instance();
        $this->oliver_pos_register_hooks();
        add_action('rest_api_init', array( $this,'oliver_pos_register_all_api_routes' ));
        // Register AJAX request for Resync remaining records
        //add_action('wp_ajax_oliver_pos_sync_remaining_records', array($this, 'oliver_pos_sync_remaining_records'));
    }
    /**
     * Resync trigger on ajax call
     * @since 2.1.2.2
     * @return void.
     */
    /*public static function oliver_pos_sync_remaining_records()
    {
        // invooke function defined in misseleneous file
        $this->pos_bridge_miscellaneous->resync_remaining_records();
    }*/
    /**
     * Imports the Single Post Meta administration classes, and the Single Post Meta Loader.
     *
     * The Single Post Meta Manager administration class defines all unique functionality for
     * introducing custom functionality into the WordPress dashboard.
     *
     * The Single Post Meta Manager Loader is the class that will coordinate the hooks and callbacks
     * from WordPress and the plugin. This function instantiates and sets the reference to the
     * $loader class property.
     *
     * @access    private
     */
    private function oliver_pos_includes() {
        /**
         * Core Classes.
         */
        include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-config.php';
        include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-install.php';
        /**
         * Classes.
         */
        include_once OLIVER_POS_ABSPATH . 'includes/class-staff-management.php';
        include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-product.php';
        include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-order.php';
        include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-user.php';
        include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-tax.php';
        include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-miscellaneous.php';
        include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-tickera.php';
    }
    /**
     * Defines the hooks and callback functions that are used for setting up the plugin stylesheets
     * and the plugin's meta box.
     *
     * This function relies on the Single Post Meta Manager Admin class and the Single Post Meta Manager
     * Loader class property.
     *
     * @access    private
     */
    /**
     * Define OLIVER_POS Constants.
     */
    private function oliver_pos_define_constants() {
        $this->oliver_pos_define('OLIVER_POS_ABSPATH', dirname( OLIVER_POS_PLUGIN_FILE ) . '/');
        $this->oliver_pos_define('ASP_DOT_NET_UDID', get_option('oliver_pos_subscription_udid') ? get_option('oliver_pos_subscription_udid') : 0 );
    }
    /**
     * Define constant if not already set.
     *
     * @param string      $name  Constant name.
     * @param string|bool $value Constant value.
     */
    private function oliver_pos_define( $name, $value ) {
        if( !defined( $name ) ) {
            define( $name, $value );
        }
    }
    /**
     * Hook into actions and filters.
     *
     * @since 2.3
     */
    private function oliver_pos_init_hooks() {
        //For plugin activation
        // register_activation_hook( OLIVER_POS_PLUGIN_FILE, array('Pos_Bridge_Install') );
        register_activation_hook( OLIVER_POS_PLUGIN_FILE, array(__CLASS__, 'oliver_pos_bridge_install') );
	    //Update plugin send details to hub
        add_action('upgrader_process_complete', array(__CLASS__, 'oliver_pos_plugin_upgrade_completed'),10,2);
        //For redirect to subscription page after activation
        add_action('admin_init', array(__CLASS__, 'oliver_pos_bridge_redirection'));
	    add_action('wp_login', array(__CLASS__, 'oliver_pos_warehouse_sync'), 10, 2);
        //For enqueue scripts and styles
        // for back end
        add_action('admin_enqueue_scripts', array( __CLASS__,  'oliver_pos_bridge_enqueue_scripts_and_styles'));
        // // =========== FRONT END AJAX REQUEST ===========
        //For simple product cost
        add_action('woocommerce_product_options_general_product_data', array(__CLASS__, 'oliver_pos_register_simple_product_cost_field'));
        add_action('woocommerce_process_product_meta', array(__CLASS__, 'oliver_pos_save_simple_product_cost_field'));
        //For simle product barcode
        add_action('woocommerce_product_options_general_product_data', array(__CLASS__, 'oliver_pos_register_simple_product_barcode_field'));
        add_action('woocommerce_process_product_meta', array(__CLASS__, 'oliver_pos_save_simple_product_barcode_field'));
        //For plugin deactivation
        register_deactivation_hook( OLIVER_POS_PLUGIN_FILE, array( __CLASS__,  'oliver_pos_bridge_uninstall') );
        /**
         * Whitelist JWT Plugin
         * @since 2.3.8.6
         * Whitelist namespace pos-bridge
         */
        if( in_array('jwt-auth/jwt-auth.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {
            add_filter('jwt_auth_default_whitelist', function ( $default_whitelist ) { return array('wp-json/pos-bridge/',); } );
        }
    }
    /**
     * Hook into actions and filters.
     *
     * @since 2.3
     */
    public static function oliver_pos_bridge_install() {
        if ( is_network_admin() ) {
            wp_die('This plugin can only be activated within each individual site. <br><a href="' . network_admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
            exit;
        }
        else{
            if ( is_multisite() ) {
                if(SUBDOMAIN_INSTALL == false) {
                    wp_die('We support only subdomain based multisite shop. <br><a href="' . esc_url( network_admin_url('plugins.php') ) . '">&laquo; Return to Plugins</a>');
                    exit;
                }
            }
        }
        if( !function_exists('wc_get_page_id') ){
            // Stop activation redirect and show error
            wp_die('Sorry, but this plugin requires the woocommerce Plugin to be installed and active. <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
            exit;
        }
        // set plugin installation date
        if( ! get_option('oliver_pos_install_date')){
            add_option('oliver_pos_install_date', date('Y-m-d h:i:s'));
        }
        // show rating panel or not
        if( ! get_option('oliver_pos_show_rating_div')){
            add_option('oliver_pos_show_rating_div', true);
        }
        /**
         * Set oliver order email flag (send or not).
         * @since 2.3.3.2
         */
        if( !get_option('oliver_pos_email_flag') ){
            add_option('oliver_pos_email_flag', true);
        }
        oliver_log('Start List of Activate Plugins');
        $data = get_option('active_plugins');
        foreach( $data as $value ) {
            oliver_log( $value );
        }
        oliver_log('End List of Activate Plugins');
        // page redirection after activation
        update_option('pos_bridge_plugin_do_activation_redirection', true);
        /**
         * Schedules a hook which will be triggered by WordPress at the specified interval. The action will trigger when someone visits your WordPress site if the scheduled time has passed.
         * @since 2.3.6.1
         * @param int $timestamp Unix timestamp (UTC) for when to next run the event.
         * @param string $recurrence How often the event should subsequently recur.
         * @param string $hook Action hook to execute when the event is run.
         * @param array $args Array containing each separate argument to pass to the hook's callback function.
         */
        $schedule_args = array(true, time());
        if( !wp_next_scheduled ('oliver_sync_records', $schedule_args)) {
            wp_schedule_event( time(), 'oliver_cron_schedule_5_minutes', 'oliver_sync_records', $schedule_args );
        }
        /**
         * @since 2.3.8.6
         * send payment details and plugins details
         */
        $clientGuid = get_option('oliver_pos_subscription_client_id');
        if( $clientGuid ) {
            $clientGuid = urlencode( $clientGuid );
        }
        else{
            $clientGuid='';
        }
        global $wpdb;
        $gateways = WC()->payment_gateways->get_available_payment_gateways();
        $enabled_gateways = [];
        if( $gateways ) {
            foreach( $gateways as $key =>$gateway ) {
                if( $gateway->enabled == 'yes' ) {
                    $enabled_gateways[ $key ] = $gateway->title;
                }
            }
        }
        $send_payments = array(
            'clientUrl' => GET_SITE_URL,
            'clientGuid' => $clientGuid,
            'tablename' => ASP_PAYMENT_DETAILS,
            'data' =>$enabled_gateways
        );
        wp_remote_post( esc_url_raw( ASP_BRIDGEINFOPOST ), array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode( $send_payments ),
        ) );
        $all_plugins = get_plugins();
        $active_plugin = [];
        foreach ( $all_plugins as $all_plugin ) {
            $active_plugin[ $all_plugin['Name'] ] = $all_plugin['PluginURI'];
        }
        $send_plugin_details = array(
            'clientUrl' => GET_SITE_URL,
            'clientGuid' => $clientGuid,
            'tablename' => ASP_PLUGIN_DETAILS,
            'data' =>$active_plugin
        );
        wp_remote_post( esc_url_raw( ASP_BRIDGEINFOPOST ), array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode( $send_plugin_details ),
        ) );
        /**
         * Whitelist Cerber Security Wordpress plugin
         * @since 2.3.8.7
         * namespace pos-bridge
         */
        if( in_array('wp-cerber/wp-cerber.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            oliver_log('WP Cerber Security is activated');
            $cerber_configuration_value = array(
                'stopenum' => 1,
                'adminphp' => '',
                'phpnoupl' => '',
                'nophperr' => '',
                'xmlrpc' => '',
                'nofeeds' => '',
                'norestuser' => 1,
                'norest' => 1,
                'restauth' => 1,
                'restroles' => array(0 => 'administrator'), 'restwhite' => 'pos-bridge'
            );
            $cerber_hardening_value = array(
                'stopenum' => 1,
                'adminphp' => '',
                'phpnoupl' => '',
                'nophperr' => '',
                'xmlrpc' => '',
                'nofeeds' => '',
                'norestuser' => 1,
                'norest' => 1,
                'restauth' => 1,
                'restroles' => array(0 => 'administrator'), 'restwhite' => 'pos-bridge', 'hashauthor' => '', 'cleanhead' => '');
            update_option('cerber_configuration', $cerber_configuration_value);
            update_option('cerber-hardening', $cerber_hardening_value);
        }
        wp_remote_post( esc_url_raw( ASP_TRIGGER_ORDER_FILE_CREATED ), array(
            'headers' => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode( array( 'status'   => true, 'message'  => 'success' ) ),
        ) );
        pos_bridge_miscellaneous::oliver_pos_add_payment_method_to_old_order();
    }
    //Add Since 2.4.0.2
	public static function oliver_pos_plugin_upgrade_completed($upgrader_object, $options) {
        pos_bridge_miscellaneous::oliver_pos_add_payment_method_to_old_order();
		if( ($options['action'] == 'install' || $options['action'] == 'update') && $options['type'] == 'plugin' ) {
			wp_schedule_single_event(  time() + 90, 'plugin_updated_completed_delay');
		}
	}
    public static function oliver_pos_bridge_redirection() {
        if( get_option('pos_bridge_plugin_do_activation_redirection', false) ) {
            delete_option('pos_bridge_plugin_do_activation_redirection');
            exit( wp_redirect( admin_url('admin.php?page=oliver-pos') ) );
        }
    }
    // this function is used for enqueue style and script in backend or admin area
    public static function oliver_pos_bridge_enqueue_scripts_and_styles() {
        wp_enqueue_script('jquery');
        wp_register_style('pos-bridge-style-connect-css', plugins_url('public/resource/css/style-connect.css', dirname(__FILE__)), array(), OLIVER_POS_PLUGIN_VERSION_NUMBER);
        wp_enqueue_style('pos-bridge-style-connect-css');
        wp_register_style('oliver-pos-feedback-css', plugins_url('public/resource/css/style-feedback-popup.css', dirname(__FILE__)), '', '', '' );
        wp_enqueue_style('oliver-pos-feedback-css');
        // feedback script
        $troubleshoot_url = ASP_TROUBLESHOOT;
        if( get_option('oliver_pos_subscription_client_id', false)) {
            $troubleshoot_url .= '?_client=' . get_option('oliver_pos_subscription_client_id');
        }
        if( get_option('oliver_pos_subscription_autologin_token', false)) {
            $troubleshoot_url .= '&_token=' . get_option('oliver_pos_subscription_autologin_token');
        }
        //Add Since 2.3.8.8 for plugin deactivate feedback form
        wp_register_script('oliver-pos-hubspot-script-v2', 'https://js.hsforms.net/forms/v2.js');
        wp_enqueue_script('oliver-pos-hubspot-script-v2');
        wp_register_script('oliver-pos-feedback-script', plugins_url('public/resource/js/oliver-feedback.js', dirname(__FILE__)), array(), false, true);
        wp_enqueue_script('oliver-pos-feedback-script'); 
        wp_localize_script('oliver-pos-feedback-script', 'oliver_pos_feedback', array('ajax_url' => admin_url('admin-ajax.php'), 'security' => wp_create_nonce('oliver-pos-nonce'), 'cross_img' => plugins_url('public/resource/img/close-dark.svg', dirname(__FILE__)), 'troubleshoot_url' => $troubleshoot_url, 'admin_email' => get_option('admin_email'), 'website' => site_url()));
    }
    public static function oliver_pos_register_simple_product_cost_field() {
        woocommerce_wp_text_input(
            array(
                'id' => 'product_cost',
                'label' => __( 'Cost ('.get_woocommerce_currency_symbol().')', 'woocommerce' ),
                'placeholder' => 'Enter Product Cost',
                'desc_tip' => 'true',
                'description' => __( 'Enter Product Cost value here.', 'woocommerce' )
            )
        );
    }
    public static function oliver_pos_save_simple_product_cost_field( $post_id )
    {
        $product_cost = sanitize_text_field( $_POST['product_cost'] );
        update_post_meta( $post_id, 'product_cost', $product_cost );
    }
    public static function oliver_pos_register_simple_product_barcode_field() {
        woocommerce_wp_text_input(
            array(
                'id' => 'oliver_barcode',
                'label' => __( 'Barcode', 'woocommerce' ),
                'placeholder' => 'Enter Product Barcode',
                'desc_tip' => 'true',
                'description' => __( 'Scan the product\'s barcode.', 'woocommerce' )
            )
        );
    }
    public static function oliver_pos_save_simple_product_barcode_field( $post_id )
    {
        $oliver_barcode = sanitize_text_field( $_POST['oliver_barcode'] );
        update_post_meta( $post_id, 'oliver_barcode', $oliver_barcode );
    }
    public static function oliver_pos_bridge_uninstall() {
        // This page responsible for display deactivation form
        // include_once OLIVER_POS_ABSPATH . 'includes/deactivation.php';
        if( get_option('oliver_pos_subscription_client_id') && get_option('oliver_pos_subscription_token') ) {
            $url = esc_url_raw( ASP_TRY_DISCONNECT );
            wp_remote_get($url, array(
                'headers' => array(
	                'Authorization' => AUTHORIZATION,
                ),
            ));
            // bridge
            delete_option('oliver_pos_authorization_token');
            // super admin
            delete_option('oliver_pos_subscription_udid');
            delete_option('oliver_pos_subscription_client_id');
            delete_option('oliver_pos_subscription_token');
            // delete_option('oliver_pos_subscription_autologin_token');
            oliver_log("Deactivate try dis connect = {$url}");
        }
        delete_option('oliver_pos_install_date');
        delete_option('oliver_pos_show_rating_div');
    }
    /**
     * Hook into actions and filters.
     *
     * @since 2.3
     */
    private function oliver_pos_register_hooks() {
        /*
         *  Register all order related trigger like add update delete
         */
        // add_action('save_post', array( $this->pos_bridge_order, 'order_listener' ));
        add_action('woocommerce_new_order', array( $this->pos_bridge_order, 'oliver_pos_new_order_listener' ));
        add_action('woocommerce_update_order', array( $this->pos_bridge_order, 'oliver_pos_update_order_listener' ));
        //Since version 2.3.8.1 Add
        add_action('woocommerce_update_order_delay', array( $this->pos_bridge_order, 'oliver_pos_update_order_listener_delay_call' ), 10, 1);
        add_action('woocommerce_order_refunded', array( $this->pos_bridge_order, 'oliver_pos_refund_order_listener' ), 10, 2);
        //Since version 2.3.8.1 Add
        add_action('woocommerce_order_refunded_delay', array( $this->pos_bridge_order, 'oliver_pos_refund_order_listener_delay_call' ), 10, 2);
        add_action('wp_trash_post', array( $this->pos_bridge_order, 'oliver_pos_delete_order_listener' ));
	    //Since 2.4.1.0 Add
	    add_action('delete_post', array( $this->pos_bridge_order, 'oliver_pos_delete_permanent_order_listener' ), 10, 1);
        add_action('untrash_post', array( $this->pos_bridge_order, 'oliver_pos_untrash_order_listener' ));
	    //Since version 2.4.1.0 Add
	    add_action('woocommerce_order_status_changed', array( $this->pos_bridge_order, 'oliver_pos_woo_order_status_change' ), 10, 3);
        /**
         * Fire while line item stock reduce after refund
         * @since 2.2.0.1
         * @param int $product_id
         * @param int $old_stock
         * @param int $new_stock
         * @param array $order
         * @param array $product
         * @return void Return void.
         */
        add_action('woocommerce_restock_refunded_item', array($this->pos_bridge_order, 'oliver_pos_restock_refunded_item'), 10, 5);
	    /**
	     *  Action : - Stop reduce main quantity if warehouse found.
	     */
	    add_action('woocommerce_can_reduce_order_stock', array($this->pos_bridge_order, 'oliver_pos_stock_reduced_based_on_warehouse'), 20, 2);
        /*
         *  Register when user profile updated
         */
        add_action('edit_user_profile_update', array( $this->pos_bridge_user, 'oliver_pos_edit_user_listener' ));
        /*
         *  Register when user profile created
         */
        add_action('user_register', array( $this->pos_bridge_user, 'oliver_pos_register_user_listener' ), 10, 1);
        /*
         *  Register when user profile deleted
         */
        add_action('delete_user', array( $this->pos_bridge_user, 'oliver_pos_delete_user_listener' ));
        /*
         * @since 2.3.4.1
         * Call trigger while customer data chnaged after checkout process
         */
        add_action('woocommerce_update_customer', array( $this->pos_bridge_user, 'oliver_pos_update_customer' ), 10, 1);
        /*
         *  Register all product related trigger like add update delete
         */
        //Since version 2.3.8.1 Modify
        add_action('save_post_product', array( $this->pos_bridge_product, 'oliver_pos_product_listener' ), 10, 3);
        //Since version 2.3.8.1 Add
        add_action('woocommerce_update_product', array( $this->pos_bridge_product, 'oliver_pos_product_update_listener' ), 10, 1);
        add_action('woocommerce_save_product_variation', array( $this->pos_bridge_product, 'oliver_pos_trigger_save_product_variation' ), 10, 1);
        //add_action('woocommerce_update_product_variation', array( $this->pos_bridge_product, 'oliver_pos_trigger_update_product_variation' ), 10, 1);
        //Since version 2.3.8.1
        //We are not using that actions for triggre ASP API thats by commented
        /*add_action('woocommerce_product_set_stock', function( $a ){
            oliver_log('Start simple product inventory change trigger');
        });*/
        /*add_action('woocommerce_variation_set_stock', function( $a ){
            oliver_log('Start variation product inventory change trigger');
        });*/
        add_action('woocommerce_product_duplicate', array( $this->pos_bridge_product, 'oliver_pos_duplicate_product_listener' ), 10, 2);
        /**
         * Fire while product CSV import
         * @since 2.1.3.4
         * @return object Returns product object and data object.
         */
        add_filter('woocommerce_product_import_get_product_object', function( $product, $data ) {
            $this->pos_bridge_product->oliver_pos_imported_product_listener( $product, $data );
            return $product;
        }, 10, 2);
        /**
         * Fire for operation with wc points and rewards (decrease points)
         * @since 2.3.5.0
         * @return array Returns array of statuses.
         */
        add_filter('wc_points_rewards_redeem_points_order_statuses', function( $statuses ) {
            unset( $statuses[ array_search('completed', $statuses) ] );
            return $statuses;
        });
	    /**
	     *  @since 2.4.0.5
	     *  To add oliver pos product visibility
	     *
	     */
	    add_action('post_submitbox_misc_actions', array( $this->pos_bridge_product, 'oliver_pos_product_data_visibility' ));
	    add_action('woocommerce_process_product_meta', array( $this->pos_bridge_product, 'oliver_pos_save_woocommerce_product_visibility' ), 10, 1);
	    /**
	     * Show inventory for Warehouse Product for Simple and variable woocommerce Product.
	     */
	    add_action('woocommerce_product_options_stock_fields', array( $this->pos_bridge_product, 'oliver_pos_show_warehouse_simple_inventory' ));
	    add_action('woocommerce_variation_options_inventory', array( $this->pos_bridge_product, 'oliver_pos_show_warehouse_variable_inventory' ), 20, 3);
	    /**
	     *Variations: Save warehouse inventory value from admin variation options
	     */
	    add_action('woocommerce_save_product_variation', array( $this->pos_bridge_product, 'oliver_pos_save_warehouse_variation_inventory' ), 10, 2);
	    /**
	     *Simple: Save warehouse inventory value from admin
	     */
	    add_action('woocommerce_admin_process_product_object', array( $this->pos_bridge_product, 'oliver_pos_save_warehouse_simple_inventory' ), 10, 1);
        add_action('woocommerce_tax_rate_added', array( $this->pos_bridge_tax, 'oliver_pos_tax_rate_added_listener' ), 10, 2);
        /*
         *  Register if existing tax rate edited
         */
        add_action('woocommerce_tax_rate_updated', array( $this->pos_bridge_tax, 'oliver_pos_tax_rate_updated_listener' ), 10, 2);
        //Since version 2.3.8.1 Add
        add_action('woocommerce_tax_location_updated', array( $this->pos_bridge_tax, 'oliver_pos_tax_rate_updated_listener_delay_call' ), 10, 2);
        /*
         *  Register if existing tax rate deleted
         */
        add_action('woocommerce_tax_rate_deleted', array( $this->pos_bridge_tax, 'oliver_pos_tax_rate_deleted_listener' ));
        //*  Register all category related trigger like add update delete
        add_action('create_term', array( $this->pos_bridge_miscellaneous, 'oliver_pos_category_created_listener' ) ,10,3);
        add_action('edited_term', array( $this->pos_bridge_miscellaneous, 'oliver_pos_category_updated_listener' ) ,10,3);
        add_action('delete_term', array( $this->pos_bridge_miscellaneous, 'oliver_pos_category_deleted_listener' ) ,10,5);
        //*  Register all category related trigger like add update delete
        //*  Register all attribute related trigger like add update delete
        add_action('woocommerce_attribute_added', array( $this->pos_bridge_miscellaneous, 'oliver_pos_attribute_created_listener' ) , 10, 2);
        //Since version 2.3.8.1 Add
        add_action('woocommerce_attribute_create_delay', array( $this->pos_bridge_miscellaneous, 'oliver_pos_attribute_create_listener_delay_call' ), 10, 2);
	    //Since version 2.4.0.2 Add
        add_action('plugin_updated_completed_delay', array(__CLASS__, 'oliver_pos_plugin_updated_completed_delay_call'));
        add_action('woocommerce_attribute_updated', array( $this->pos_bridge_miscellaneous, 'oliver_pos_attribute_updated_listener' ) , 10, 3);
        //Since version 2.3.8.1 Add
        add_action('woocommerce_attribute_updated_delay', array( $this->pos_bridge_miscellaneous, 'oliver_pos_attribute_updated_listener_delay_call' ), 10, 3);
        add_action('woocommerce_attribute_deleted', array( $this->pos_bridge_miscellaneous, 'oliver_pos_attribute_deleted_listener' ) , 10, 3);
        //Since version 2.3.8.4
        //add_action('woocommerce_after_set_term_order', array( $this->pos_bridge_miscellaneous, 'oliver_pos_after_subattribute_reorder' ) , 10, 3);
        //*  Register all attribute related trigger like add update delete
        //Since 2.3.9.1 update
        //* Post woocommerce general setting related data to dotnet like add, update, delete
        add_action('woocommerce_update_options_general', array( $this->pos_bridge_miscellaneous, 'oliver_pos_woocommerce_general_settings_post_listener' ));
        //Since 2.3.9.1 Add
        //* Post woocommerce tax setting related data to dotnet like add, update, delete
        add_action('woocommerce_update_options_tax', array( $this->pos_bridge_tax, 'oliver_pos_woocommerce_tax_settings_post_listener' ));
        /**
         * while save wordpress settings this hook runs (Fires after the value of a specific option has been successfully updated).
         * @since 2.3.5.1
         * @since 2.3.9.1 update
         */
        add_action('update_option_oliver_pos_general_setting_field', array( $this->pos_bridge_miscellaneous, 'oliver_pos_woocommerce_general_settings_post_listener' ), 10, 3);
        //*  Register tickera related trigger like add update delete
        add_action('save_post_tc_forms', array( $this->pos_bridge_tickera, 'oliver_pos_save_tickera_form_post') , 10, 3);
        add_action('save_post_tc_events', array( $this->pos_bridge_tickera, 'oliver_pos_save_tickera_event_post') , 10, 3);
        add_action('save_post_tc_tickets_instances', array( $this->pos_bridge_tickera, 'oliver_pos_save_tickera_ticket_post') , 10, 3);
        add_action('tc_save_tc_general_settings', array( $this->pos_bridge_tickera, 'oliver_pos_save_tickera_general_setting'));
        add_action('save_post_tc_seat_charts', array( $this->pos_bridge_tickera, 'oliver_pos_save_tickera_seating_chart') , 10, 3);
        //*  Register tickera related trigger like add update delete
        /**
         * Prevent mail to shop owner and customer when order placed by oliver pos according to pos email toggle setting.
         * @since 2.3.3.2
         * Update 2.3.8.7
         */
        add_filter('woocommerce_email_recipient_new_order', array( $this->pos_bridge_order, 'oliver_pos_unhook_order_emails_admin' ), 10, 2);
        add_filter('woocommerce_email_recipient_customer_completed_order', array( $this->pos_bridge_order, 'oliver_pos_unhook_order_emails_customer' ), 10, 2);
        add_filter('woocommerce_email_recipient_customer_processing_order', array( $this->pos_bridge_order, 'oliver_pos_unhook_order_emails_customer' ), 10, 2);
        add_action('woocommerce_before_email_order', array( $this->pos_bridge_order, 'oliver_pos_unhook_order_emails_customer' ), 10, 2);
        // /**
        //  * Adds a custom cron schedule for every 5 minutes.
        //  * @since 2.3.6.1
        //  * @param array $schedules List of existing cron schedules
        // //  * @return array $schedules add our schedule in existing cron schedules
        //  */
        // add_filter('cron_schedules', function( $schedules ){
        // $schedules['oliver_cron_schedule_5_minutes'] = array(
        // 'interval' => 300,
        // 'display' => __('Every 5 Minutes')
        // );
        // return $schedules;
        // });
        // /**
        //  * Our Custom cron schedule hook
        //  * @since 2.3.6.1
        //  * @param boolean $status cron schedule status
        //  * @param integer $time registration timestamp of cron schedule
        //  * @return void perform custom operations
        //  */
        // add_action('oliver_sync_records', function( $status = true, $time )
        // {
        // if ( $status ) {
        // // oliver_log('oliver_sync_records run on: ' . date('Y-m-d H:i:s a', time()));
        // }
        // }, 10, 2);
        /**
         * Add Update plugin list when activate and deactivate plugin
         * @since 2.3.8.7
         *
         */
        add_action('update_option_active_plugins', array( $this->pos_bridge_miscellaneous, 'oliver_pos_wordpress_plugin_update_option' ), 10, 2);
        /**
         * Add additional information to woocommerce single product page tab
         * @since 2.3.8.7
         */
        add_action('woocommerce_product_additional_information', array( $this->pos_bridge_miscellaneous, 'oliver_pos_woocommerce_additional_tab' ) , 20, 1);
        /**
         * Add html and hubspot  for plugin deactivate feedback
         * @since 2.3.8.8
         */
        add_action('pre_current_active_plugins', array( $this->pos_bridge_miscellaneous, 'oliver_pos_feedback_form' ), 10, 1);
	    add_action('admin_init', array( $this->pos_bridge_miscellaneous, 'oliver_pos_add_setting_field' ));
    }
    /**
     * Returns the current version of the plugin to the caller.
     *
     * @return    string    $this->version    The current version of the plugin.
     */
    public function oliver_pos_register_all_api_routes() {
        $this->oliver_pos_register_all_api_staff_routes();     		// staff routes
        $this->oliver_pos_register_all_api_product_routes();			// product routes
        $this->oliver_pos_register_all_api_order_routes();				// order routes
        $this->oliver_pos_register_all_api_user_routes();				// user routes
        $this->oliver_pos_register_all_api_tax_routes();				// tax routes
        $this->oliver_pos_register_all_api_miscellaneous_routes();		// miscellaneous routes
        $this->oliver_pos_register_all_api_tickera_routes();			// tickera routes
        $this->oliver_pos_register_all_onboard_routes();				// onboard routes
    }
    private function oliver_pos_create_instance() {
        $this->bridge_config = new Pos_Bridge_Config(); // load all constant
        $this->staff_management = new Staff_Management();
        $this->pos_bridge_product = new Pos_Bridge_Product();
        $this->pos_bridge_order = new Pos_Bridge_Order();
        $this->pos_bridge_user = new Pos_Bridge_User();
        $this->pos_bridge_tax = new Pos_Bridge_Tax();
        $this->pos_bridge_miscellaneous = new Pos_Bridge_Miscellaneous();
        $this->pos_bridge_tickera = new Pos_Bridge_Tickera();
    }
    /**
     * Returns the current version of the plugin to the caller.
     *
     * @return string $this->version The current version of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Warehouse sync
     */
    public static function oliver_pos_warehouse_sync( $user_login, $user ) {
        global $wpdb;
        $data_warehouses = $wpdb->get_results( "SELECT oliver_warehouseid FROM {$wpdb->prefix}pos_warehouse", OBJECT );
        $warehouseids    = array();
        foreach ( $data_warehouses as $data_warehouse ) {
            $warehouseids[] = $data_warehouse->oliver_warehouseid;
        }
        $wp_remote_get = wp_remote_get( ASP_GETALL, array(
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ));
        if ( wp_remote_retrieve_response_code( $wp_remote_get ) == 200 ) {
            $decode_response = json_decode( wp_remote_retrieve_body( $wp_remote_get ) );
            if ( $decode_response->is_success ) {
                if ( ! empty( $decode_response->content ) ) {
                    $contents = $decode_response->content;
                    foreach ( $contents as $content ) {
                        if ( in_array( $content->Id, $warehouseids ) ) {
                            if ( ( $key = array_search( $content->Id, $warehouseids ) ) !== false ) {
                                unset( $warehouseids[$key] );
                            }
                        } else {
                            $name                  = sanitize_text_field( $content->Name );
                            $type                  = sanitize_text_field( $content->Type );
                            $relwarehouselocations = serialize( $content->relWarehouseLocations );
                            $isdefault             = sanitize_text_field( $content->IsDefault );
                            // $syncerror          = sanitize_text_field( $parameters['SyncError'] );
                            $syncerror             = '';
                            $isdeleted             = sanitize_text_field( $content->IsDeleted );
                            $warehouser_id         = sanitize_text_field( $content->Id );
                            $time                  = current_time( 'mysql' );
                            $table                 = $wpdb->prefix . 'pos_warehouse';
                            $data                  = array('name' => $name, 'type' => $type, 'time' => $time, 'oliver_warehouseid' => $warehouser_id, 'relwarehouselocations' => $relwarehouselocations,'isdefault' => $isdefault, 'isdeleted' => $isdeleted, 'syncerror' => $syncerror);
                            $wpdb->insert( $table,$data );
                            $responce_id           = $wpdb->insert_id;
                        }
                    }
                }
            }
        }
        if ( ! empty( $warehouseids ) ) {
            foreach ( $warehouseids as $data_warehouse ) {
                $table    = $wpdb->prefix . 'pos_warehouse';
                $responce = $wpdb->delete( $table, array( 'oliver_warehouseid' => $data_warehouse ) );
                if ( $responce ) {
                    // Delete warehouse from product.
                    $table_postmeta = $wpdb->prefix . 'postmeta';
                    $wpdb->delete( $table_postmeta, array( 'meta_key' => '_warehouse_' . $data_warehouse ) );
                    oliver_log( 'warehouse deleted =' . $data_warehouse );
                }
            }
        }
    }

    /**
     * Group of all onboard related routes.
     * @since 2.2.5.0
     * @return void Returns void.
     */
    public function oliver_pos_register_all_onboard_routes() {
        /**
         * Response to super admin request(Like Hi).
         * @since 2.2.5.0
         * @return array Returns array of status and message.
         */
        register_rest_route( $this->namespace, '/try-connect/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => function(){
                    $permalinks_settings =  get_option('permalink_structure');
                    if( $permalinks_settings == '' )
                    {
                        $permalinks_settings = 'Plane';
                    }
                    return array('status' => true, 'message' => 'Success', 'permalinks' => $permalinks_settings );
                },
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get login token from supr admin and use it for auto login.
         * @since 2.2.5.0
         * @return bool Returns true|false.
         */
        register_rest_route( $this->namespace, '/set-auth-token/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => function($request_data){
                    $parameters = $request_data->get_params();
                    $status = false;
                    if( isset( $parameters['_at'] ) && !empty( $parameters['_at'] ) ) {
                        //This token used for auto login get from super admin
                        update_option('oliver_pos_subscription_autologin_token', $parameters['_at'], false);
                        oliver_log('oliver_pos_subscription_autologin_token = ' . get_option('oliver_pos_subscription_autologin_token'));
                        $status = true;
                    }
                    return array('status' => $status);
                },
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
    }
    private function oliver_pos_register_all_api_staff_routes()
    {
        register_rest_route(  $this->namespace, '/get-all-members/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->staff_management, 'oliver_pos_getStaffMembers' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
    }
    private function oliver_pos_register_all_api_product_routes()
    {
        register_rest_route( $this->namespace, '/products/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_product, 'oliver_pos_products' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/product/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_product, 'oliver_pos_product' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/variation-product/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_product, 'oliver_pos_variation_product' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-remaining-products/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_product, 'oliver_pos_get_remainig_products' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/update-oliver-inventory/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_product, 'oliver_pos_update_oliver_inventory' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get id and inventory of all products.
         * @since 2.1.3.2
         * @return array Returns products array.
         */
        register_rest_route( $this->namespace, '/get-products-stock-quantity/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_product, 'oliver_pos_get_products_stock_quantity' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get id,title and price of all products.
         * @since 2.1.3.2
         * @return array Returns products array.
         */
        register_rest_route( $this->namespace, '/get-products-price-with-title/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_product, 'oliver_pos_get_products_price_with_title' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get product id and their child id.
         * @since 2.3.5.1
         * @return array Returns products array.
         */
        register_rest_route( $this->namespace, '/get-products-id-and-child-id/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_product, 'oliver_pos_get_products_id_and_child_id' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
	    /**
	     * Create product.
	     * @since 2.4.0.6
	     * @return array Returns products array.
	     */
	    register_rest_route( $this->namespace, '/create-product/', array(
			    'methods' => WP_REST_Server::ALLMETHODS,
			    'callback' => array( $this->pos_bridge_product, 'oliver_pos_create_product' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
	    /**
	     * Update product Details.
	     * @since 2.4.0.6
	     * @return array Returns products array.
	     */
	    register_rest_route( $this->namespace, '/update-product/', array(
			    'methods' => WP_REST_Server::ALLMETHODS,
			    'callback' => array( $this->pos_bridge_product, 'oliver_pos_update_product' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
	    /**
	     * Delete product.
	     * @since 2.4.0.6
	     * @return array Returns product success.
	     */
	    register_rest_route( $this->namespace, '/delete-product/', array(
			    'methods' => WP_REST_Server::ALLMETHODS,
			    'callback' => array( $this->pos_bridge_product, 'oliver_pos_delete_product' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
	    /**
	     * Get product id and its backorder value.
	     * @since 2.4.1.3
	     * @return array.
	     */
	    register_rest_route( $this->namespace, '/products-backorder/', array(
			    'methods' => WP_REST_Server::READABLE,
			    'callback' => array( $this->pos_bridge_product, 'oliver_pos_products_backorder' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
    }
    private function oliver_pos_register_all_api_order_routes()
    {
        register_rest_route( $this->namespace, '/orders/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_orders' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/order/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_order' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/create-order/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_create_order' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/refund-order/', array(
                'methods' => WP_REST_Server::CREATABLE,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_refund_order' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-remaining-orders/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_get_remainig_orders' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/set-order-status/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_set_order_status' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/cancel-order/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_cancel_order' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/save-user-in-order/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_save_user_in_order' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Set new customer on existing order by temporary order id.
         * @since 2.2.1.2
         * @param string temp order id
         * @param string email
         * @return array Returns array of success or error message.
         */
        register_rest_route( $this->namespace, '/save-user-in-order-by-temp-id/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_save_user_in_order_by_temp_order_id' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Set order payments.
         * @since 2.1.3.2
         * @param int order id
         * @param array payments
         * @return array Returns array of order payments.
         */
        register_rest_route( $this->namespace, '/set-order-payments/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_set_order_payments' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get order payments.
         * @since 2.1.3.2
         * @param int order id
         * @return array Returns array of order payments.
         */
        register_rest_route( $this->namespace, '/get-order-payments/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_get_order_payments' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Set order Refund payments.
         * @since 2.1.3.1
         * @param int order id
         * @param array payments
         * @return array Returns array of order refund payments.
         */
        register_rest_route( $this->namespace, '/set-order-refund-payments/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_set_order_refund_payments' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get order payments.
         * @since 2.1.3.2
         * @param int order id
         * @return array Returns array of order refund payments.
         */
        register_rest_route( $this->namespace, '/get-order-refund-payments/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_get_order_refund_payments' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get last temp order id.
         * @since 2.2.5.6
         * @return string|array Return last temp order id.
         */
        register_rest_route( $this->namespace, '/get-last-temp-order-id/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_get_last_temp_order_id' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get Order details through Oliver Pos receipt id.
         * @since 2.3.8.3
         * @Add string return order details
         */
        register_rest_route( $this->namespace, '/get-order-details-by-oliver-receipt-id/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_get_order_details_by_oliver_receipt_id' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get all order status.
         * @since 2.3.8.5
         * @Add string return order status
         */
        register_rest_route( $this->namespace, '/get-orders-status/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_get_orders_status' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Delete order.
         * @since 2.3.9.5
         * @return string|array order status
         */
        register_rest_route( $this->namespace, '/delete-order/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_delete_order' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * count orders and dates.
         * @since 2.4.0.9
         * @return string|array order status
         */
        register_rest_route( $this->namespace, '/orders-count/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_order_counts' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/orders-with-time/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_order, 'oliver_pos_orders_with_time' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
    }
    private function oliver_pos_register_all_api_user_routes()
    {
        register_rest_route( $this->namespace, '/users/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_users' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/user/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_user' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-remaining-users/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_get_remainig_users' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/customers/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_users' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/customer/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_user' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/count-users/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_count_users' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/create-user/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_create_user' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/update-user/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_update_user' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/user-orders/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_get_user_order' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/delete-user/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_delete_user' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Delete customer by customer email
         * @since 2.2.0.1
         * @param string email
         * @return object Returns API response.
         */
        register_rest_route( $this->namespace, '/delete-user-by-email/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_delete_user_by_email' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get user by email
         * @since 2.3.9.8
         * @return  Returns user details.
         */
        register_rest_route( $this->namespace, '/get-user-by-email/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_get_user_by_email' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );

        /**
         * Set store credit
         * @since 2.1.3.2
         * @param string email
         * @param float amount
         * @return object Returns customer details.
         */
        register_rest_route( $this->namespace, '/set-store-credit/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_set_store_credit' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get store credit
         * @since 2.1.3.2
         * @param string email
         * @return float Returns customer store credit amount.
         */
        register_rest_route( $this->namespace, '/get-store-credit/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_get_store_credit' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get all user roles
         * @since 2.3.8.5
         * @param string Roles
         * @return  Returns user roles.
         */
        register_rest_route( $this->namespace, '/get-all-roles/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_user, 'oliver_pos_get_all_roles' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
    }
    public function oliver_pos_register_all_api_tax_routes()
    {
        register_rest_route( $this->namespace, '/taxes/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_tax, 'oliver_pos_get_taxes' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tax/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_tax, 'oliver_pos_get_tax' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-tax-by-location/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_tax, 'oliver_pos_get_tax_by_location' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-tax-settings/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_tax, 'oliver_pos_get_tax_settings' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
    }
    public function oliver_pos_register_all_api_miscellaneous_routes()
    {
        // *****  All category relative routes  ***** //
        register_rest_route( $this->namespace, '/categories/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_categories' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/category/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_category' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-subcategory/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_subcategory' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-category-product/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_category_product' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        // *****  All attribute relative routes  ***** //
        register_rest_route( $this->namespace, '/attributes/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_attributes' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/attribute/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_attribute' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/sub-attribute/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_subattribute' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-attribute-product/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_attribute_product' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-sub-attribute-product/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_sub_attribute_product' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        // ***** miscellaneous ***** //
	    /**
	     * Get product tags.
	     * @since 2.4.0.5
	     */
	    register_rest_route( $this->namespace, '/tags/', array(
			    'methods' => WP_REST_Server::ALLMETHODS,
			    'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_tags' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
        register_rest_route( $this->namespace, '/get-counts/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_counts' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get count by element.
         * @since 2.2.2.0
         * @param string element name
         * @return int Returns count of given element | otherwise error.
         */
        register_rest_route( $this->namespace, '/get-count-for/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_count_for' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/email-order-details/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_send_email' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        // ***** settings ***** //
        register_rest_route( $this->namespace, '/get-general-settings/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_general_settings' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-coupon/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_coupon' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-countries/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_countries' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-states/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_states' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/reset-oliver-subscription/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_reset_subscription' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/oliver-pos-version/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => function(){
                    return defined( 'OLIVER_POS_PLUGIN_VERSION_NUMBER' ) ? OLIVER_POS_PLUGIN_VERSION_NUMBER : '1.0.0';
                },
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/is-connection-alive/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_is_connection_alive' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/get-log-file/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => 'oliver_pos_get_log_file',
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * check multiple tax details.
         * @since 2.1.2.1
         * @return bool Returns true (implement for ASP.Net checks to find plugin availability)
         */
        register_rest_route( $this->namespace, '/is-multiple-tax/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => function(){
                    return true;
                },
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * check multiple tax details.
         * @since 2.1.2.1
         * @return bool Returns true (implement for ASP.Net checks to find plugin availability)
         */
        /*register_rest_route( $this->namespace, '/resync-remaining-records/', array(
            'methods' => WP_REST_Server::READABLE,
            'callback' => array( $this->pos_bridge_miscellaneous, 'resync_remaining_records' ),
            'permission_callback' => array( $this, 'oliver_rest_authentication' )
            )
        );*/
        /**
         * Set oliver order email flag (send or not).
         * @since 2.3.3.1
         * @return void Return suceess status and message
         */
        register_rest_route( $this->namespace, '/set-oliver-email-flag/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_set_oliver_email_flag' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Set oliver order email flag (send or not).
         * @since 2.3.3.2
         * @return void Return suceess status and message
         */
        register_rest_route( $this->namespace, '/get-oliver-email-flag/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_oliver_email_flag' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        // === Super admin API's ===
        /**
         * Get all plugins list.
         * @since 2.3.3.1
         * @return array Returns list of all plugins
         */
        register_rest_route( $this->namespace, '/get-all-plugins-details/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_all_plugins_details' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get Wp and Site URL.
         * @since 2.3.3.1
         * @return array Returns wp and site url
         */
        register_rest_route( $this->namespace, '/get-wp-site-url/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_wp_site_url' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get PHP and MySQL version.
         * @since 2.3.3.1
         * @return array Return PHP and MySQL version
         */
        register_rest_route( $this->namespace, '/get-php-mysql-version/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_php_mysql_version' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get wp and wc version.
         * @since 2.3.3.1
         * @return array Return wp and wc version
         */
        register_rest_route( $this->namespace, '/get-wp-wc-version/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_wp_wc_version' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get count of all kind of products.
         * @since 2.3.3.1
         * @return array Return products count
         */
        register_rest_route( $this->namespace, '/get-products-count/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_products_count' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get configuration details.
         * @since 2.3.3.1
         * @return array Return configuration details
         */
        register_rest_route( $this->namespace, '/get-bridge-details/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_bridge_details' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get product id and their quantity.
         * @since 2.3.8.6
         * @return array Returns products ids and Quantity.
         */
        register_rest_route( $this->namespace, '/get-products-id-and-quantity/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_products_id_and_quantity' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Set oliver shop settings.
         * @since 2.3.8.6
         * @return void Return shop setting status
         */
        register_rest_route( $this->namespace, '/set-oliver-shop-settings/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_set_oliver_shop_settings' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        // Wherehouse
        /**
         * create warehouse.
         * @since 2.4.0.1
         * @return void Return shop inventry
         */
        register_rest_route( $this->namespace, '/create-warehouse/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_create_warehouse' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Delete warehouse.
         * @since 2.3.9.8
         */
        register_rest_route( $this->namespace, '/delete-warehouse/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_delete_warehouse' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * update warehouse.
         * @since 2.3.9.8
         */
        register_rest_route( $this->namespace, '/update-warehouse/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_update_warehouse' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get all warehouse.
         * @since 2.3.9.8
         */
        register_rest_route( $this->namespace, '/get-warehouse/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_warehouse' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Update Product Quantity  warehouse.
         * @since 2.3.9.8
         */
        register_rest_route( $this->namespace, '/wQty-bulk-update/', array(
                'methods' => WP_REST_Server::ALLMETHODS,
                'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_wqty_bulk_update' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
	    /**
	     * Get oliver pos save setting in databse.
	     * @since 2.4.1.0
	     */
	    register_rest_route( $this->namespace, '/get-oliver-setting/', array(
			    'methods' => WP_REST_Server::READABLE,
			    'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_oliver_setting' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
	    /**
	     * Get sync data status.
	     * @since 2.4.1.3
	     */
	    register_rest_route( $this->namespace, '/sync-complete/', array(
			    'methods' => WP_REST_Server::READABLE,
			    'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_sync_status' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
	    /**
	     * Get sync data status.
	     * @since 2.4.1.6
	     */
	    register_rest_route( $this->namespace, '/get-points-setting/', array(
			    'methods' => WP_REST_Server::ALLMETHODS,
			    'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_points_setting' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
	    /**
	     * Get Woo all log files name.
	     * @since 2.4.1.7
	     */
	    register_rest_route( $this->namespace, '/get-woo-log-files/', array(
			    'methods' => WP_REST_Server::READABLE,
			    'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_woo_log_files_name' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
	    /**
	     * Get Woo single log file with name.
	     * @since 2.4.1.7
	     * return file data
	     */
	    register_rest_route( $this->namespace, '/get-woo-log-file/', array(
			    'methods' => WP_REST_Server::READABLE,
			    'callback' => array( $this->pos_bridge_miscellaneous, 'oliver_pos_get_woo_log_file' ),
			    'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
		    )
	    );
        // === Super admin API's ===
    }
    public function oliver_pos_register_all_api_tickera_routes() {
        register_rest_route( $this->namespace, '/is-tickera-active/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_is_tickera_active' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-forms/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_tickera_custom_forms' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-form/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_tickera_custom_form' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-form-fields/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_tickera_custom_form_fields' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-form-field/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_tickera_custom_form_field' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-tickets/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_get_tickets' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-ticket/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_get_ticket' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-events/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_get_events' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-get-ticket-by-order/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_tickera_get_ticket_by_order_id' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-event/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_get_event' ),
                // permission_callback is work as middleware so we can check every request
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        register_rest_route( $this->namespace, '/tickera-settings/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_get_settings' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get seating chart.
         * @since 2.1.2.1
         * @param int chart_id
         * @return object Returns a single seat chart details.
         */
        register_rest_route( $this->namespace, '/tickera-charts/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_get_charts' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get seating chart.
         * @since 2.1.2.1
         * @param int chart_id
         * @return object Returns a single seat chart details (only for internally usage).
         */
        register_rest_route( $this->namespace, '/tickera-chart/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_get_chart' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
        /**
         * Get chart reserved seat.
         * @since 2.1.2.3
         * @param int chart_id
         * @return array Returns reserved seat of chart.
         */
        register_rest_route( $this->namespace, '/tickera-get-chart-reserved-seats/', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array( $this->pos_bridge_tickera, 'oliver_pos_get_chart_reserved_seats' ),
                'permission_callback' => array( $this, 'oliver_pos_rest_authentication' )
            )
        );
    }
    /**
     * check request token is valid or not.
     *
     * @param array Request header parameter
     * @return bool Returns true if valid otherwise false
     */
    public function oliver_pos_rest_authentication( $data ) {
        $params = $data->get_headers();
        if( isset( $params['oliverauth'] ) ) {
            $token = is_array( $params['oliverauth'] ) ? reset( $params['oliverauth'] ) : $params['oliverauth'];
            return ( $token == get_option('oliver_pos_authorization_token') ) ? true : false;
        }
        return false;
    }
	public static function oliver_pos_plugin_updated_completed_delay_call(){
		self::oliver_pos_bridge_install();
		Pos_Bridge_Install::oliver_pos_trigger_update_version_number();
	}
}
