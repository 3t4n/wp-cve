<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Automattic\WooCommerce\Utilities\OrderUtil;

require_once dirname(dirname(__FILE__)) . '/libraries/MontonioShippingSDK.php';

class Montonio_Shipping {
   
    /**
	 * Notices (array)
	 *
	 * @var array
	 */
    protected $admin_notices = array();
    
    /**
	 * API access key
	 *
	 * @var string
	 */
    public $access_key;

    /**
	 * API secret key
	 *
	 * @var string
	 */
    public $secret_key;
    
    /**
     * @return void
     */
    public function __construct() {
        $api_keys = WC_Montonio_Helper::get_api_keys();

        $this->access_key = $api_keys[ 'access_key' ];
        $this->secret_key = $api_keys[ 'secret_key' ];

        $this->register_hooks();
    }

    /**
     * @return self
     */
    public static function create() {
        return new self();
    }

    /**
     * @return void
     */
    protected function register_hooks() {

        // Get shipping rates to display in checkout
        add_filter( 'woocommerce_package_rates', array( $this, 'get_available_shipping_methods' ), 10, 2 );

        // Add custom order status(es); needs to be registered using register_post_status()
        add_filter( 'wc_order_statuses', array( $this, 'add_custom_order_statuses' ) );

        // Modify shipping text in "order received" page
        add_filter( 'woocommerce_order_shipping_to_display', array( $this, 'modify_order_shipping_text' ), 10, 2 );

        // Add action "Print shipping labels" to order bulk actions list
        if ( class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {
            add_filter( 'bulk_actions-woocommerce_page_wc-orders', array( $this, 'add_print_labels_action' ) );
        } else {
            add_filter( 'bulk_actions-edit-shop_order', array( $this, 'add_print_labels_action' ) );
        }

        // Add action "Print shipping labels" to order action in detail view page
        add_filter( 'woocommerce_order_actions', array( $this, 'add_print_labels_action_single' ) );

        // Replace email placeholder(s) with relevant data
        add_filter( 'woocommerce_email_format_string', array( $this, 'replace_email_placeholders' ), 10, 2 );

        // Hide some metadata fields from order view
        add_filter( 'woocommerce_hidden_order_itemmeta', array( $this, 'hide_shipping_method_meta_data' ) );

        // Update shipping methods labels
        add_filter( 'woocommerce_cart_shipping_method_full_label', array( $this, 'edit_shipping_method_label' ), 10, 2 );

        // Register status "Label printed"
        add_action( 'init', array( $this, 'register_label_printed_status' ) );

        // Add custom html to shipping section in order review
        add_action( 'woocommerce_review_order_after_shipping', array( $this, 'add_pickup_points_to_checkout' ) ); 
        
        // Update order data on checkout
        add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'update_checkout_pickup_point' ) );

        // Validate fields on checkout
        add_action( 'woocommerce_after_checkout_validation', array( $this, 'validate_pickup_point' ) );

        // Create shipment in shipping api when payment completed (either from checkout or manually)
        add_action( 'woocommerce_order_status_processing', array( $this, 'create_shipment_when_payment_complete' ), 10, 2 );

        // Add button to "Edit order" page to create shipment
        add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'add_create_shipment_button' ) );

        // Perform various actions when options are saved in Montonio Shipping
        add_action( 'woocommerce_update_options_montonio_shipping', array( $this, 'process_shipping_options' ) );
        add_action( 'woocommerce_shipping_zone_method_added', array( $this, 'sync_pickup_points' ) );

        // Modify orders view columns
        if ( class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {
            add_action( 'woocommerce_shop_order_list_table_custom_column', array( $this, 'modify_order_columns' ), 10, 2 );
        } else {
            add_action( 'manage_shop_order_posts_custom_column', array( $this, 'modify_order_columns' ), 10, 2 );
        }

        // Add product view custom fields
        add_action( 'woocommerce_product_options_shipping', array( $this, 'add_custom_shipping_fields' ) );
        
        // Save product view custom fields
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_custom_shipping_fields' ) );

        // Display any shipping related admin notices
        add_action( 'admin_notices', array( $this, 'display_shipping_notices' ) );

        // Add shipping options style
        add_action( 'woocommerce_cart_totals_before_shipping', array( $this, 'enqueue_shipping_options_style' ) );
        add_action( 'woocommerce_review_order_before_shipping', array( $this, 'enqueue_shipping_options_style' ) );

        // Admin notices
        add_action( 'admin_notices', array( $this, 'display_admin_notices' ), 999 );

        // Create shipment in shipping api via js request
        add_action( 'woocommerce_api_montonio_shipping_create_shipment', array( $this, 'create_shipment_from_js_request' ) );

        // Called from Montonio Shipping API
        add_action( 'woocommerce_api_montonio_shipping_api_webhook', array( $this, 'handle_shipping_api_webhook' ) );

        // Check orders' shipping methods for label creation
        add_action( 'woocommerce_api_montonio_shipping_create_labels', array( $this, 'create_labels' ) );

        // Custom hook to sync pickup points through MontonioShippingSDK
        add_action( 'montonio_shipping_sync_pickup_points', array( $this, 'sync_pickup_points' ) );

        add_action( 'wp_enqueue_scripts', array( $this, 'add_shipping_scripts') );

        // Add custom order filter
        if ( class_exists( 'Automattic\WooCommerce\Utilities\OrderUtil' ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {
            add_action( 'woocommerce_order_list_table_restrict_manage_orders', array( $this, 'add_custom_orders_filter' ) );
            add_filter( 'woocommerce_orders_table_query_clauses', array( $this, 'output_filter_results_hpos' ), 10, 3 );
        } else {
            add_action( 'restrict_manage_posts', array( $this, 'add_custom_orders_filter' ) );
            add_filter( 'posts_where', array( $this, 'output_filter_results' ), 10, 2 );
        }

        // Add pickup point selection in order view
        add_action( 'wp_ajax_get_country_select', array( $this, 'get_country_select' ) );
        add_action( 'wp_ajax_get_pickup_points_select', array( $this, 'get_pickup_points_select' ) );
        add_action( 'wp_ajax_process_selected_pickup_point', array( $this, 'process_selected_pickup_point' ) );
    }

    public function get_country_select() {
        if ( ! isset( $_POST['shipping_method_id'] ) ) {
            wp_send_json_error();
        }
        
        $shipping_method_id   = sanitize_text_field( $_POST['shipping_method_id'] );
        $country_availability = json_decode( get_option( 'montonio_carriers_pickup_point_countries' ) );
    
        $wc_countries       = new WC_Countries();
        $countries          = $wc_countries->__get('countries');
        $pickup_point_types = [ 'parcel_machine', 'post_office' ];
    
        foreach ( $pickup_point_types as $type ) {
            if ( strpos(  $shipping_method_id, $type ) !== false ) {
    
                $provider_name = str_replace( [ 'montonio_', '_' . $type . 's' ], '', $shipping_method_id );
    
                if ( ! empty( $country_availability ) ) {
                    $country_select = '<select name="montonio_admin_pickup_point_country" class="montonio-admin-pickup-point-country-select">';
                    $country_select .= '<option value="">' . __( 'Select a destination country', 'montonio-for-woocommerce' ) . '</option>';
    
                    foreach ( $country_availability->$provider_name->$type as $country ) {
                        $country_select .= '<option value="montonio_' . $country . '_' . $provider_name . '_' . $type . '">';
                        $country_select .= $countries[$country];
                        $country_select .= '</option>';
                    }
    
                    $country_select .= '</select>';
                } else {
                    wp_send_json_error();
                }
            }
        }
    
        wp_send_json_success( $country_select );
    }

    public function get_pickup_points_select() {
        if ( ! isset( $_POST['option_name'] ) ) {
            wp_send_json_error();
        }

        $pickup_points = json_decode( get_option( sanitize_text_field( $_POST['option_name'] ), '' ) ) ;

        if ( empty( $pickup_points ) ) {
            wp_send_json_error();
        }

        $include_address = get_option( 'montonio_shipping_show_address' );
        $pickup_points = $this->sort_pickup_points( $pickup_points );

        $pickup_point_select = '<select name="montonio_admin_pickup_point" class="montonio-admin-pickup-point-select">';
        $pickup_point_select .= '<option value="">' .  __( 'Select pickup point', 'montonio-for-woocommerce' ) . '</option>';

        foreach ( $pickup_points as $pickup_points_category => $pickup_points_in_category ) {
            $pickup_point_select .= '<optgroup label="' . $pickup_points_category . '">';

            foreach ( $pickup_points_in_category as $pickup_point ) {
                $pickup_point_select .= '<option value="' . $pickup_point->uuid . '">';
                $pickup_point_select .= $pickup_point->name;

                if ( $include_address === 'yes' && ! empty( $pickup_point->address ) ) {
                    $pickup_point_select .= ' - ' . $pickup_point->address;
                }
                
                $pickup_point_select .= '</option>';
            }
            $pickup_point_select .='</optgroup>';
        }

        $pickup_point_select .= '</select>';
            
        wp_send_json_success( $pickup_point_select ); 
    }

    public function process_selected_pickup_point() {
        $order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : null;
        $pickup_point_uuid = isset( $_POST['pickup_point_uuid'] ) ? sanitize_text_field( $_POST['pickup_point_uuid'] ) : null;
        $option_name = isset( $_POST['option_name'] ) ? sanitize_text_field( $_POST['option_name'] ) : null;

        if ( ! $order_id || ! $pickup_point_uuid || ! $option_name || strpos( $option_name, 'montonio_' ) === false ) {
            wp_send_json_error();
        }

        $pickup_points = json_decode( get_option( $option_name, '' ) );

        if ( empty( $pickup_points ) ) {
            wp_send_json_error();
        }

        // Search for pickup point with matching id
        $chosen_pickup_point = null;
        foreach ( $pickup_points as $pickup_point ) {
            if ( $pickup_point->uuid === $pickup_point_uuid ) {
                $chosen_pickup_point = $pickup_point;
                break;
            }
        }

        if ( ! $chosen_pickup_point ) {
            wp_send_json_error();
        }

        $order = wc_get_order( $order_id );
        $order->update_meta_data( '_montonio_pickup_point_name', $chosen_pickup_point->name );
        $order->update_meta_data( '_montonio_pickup_point_uuid', $pickup_point_uuid );
        $order->set_shipping_address( 
            [
                'address_1' => $chosen_pickup_point->name,
                'address_2' => '',
                'city'       => $chosen_pickup_point->locality,
                'state'      => '',
                'postcode'   => '',
                'country'    =>  $chosen_pickup_point->country
            ] 
        );
        $order->save();

        wp_send_json_success( $chosen_pickup_point );
    }

    public function add_custom_orders_filter() {
        if ( ( ! isset( $_GET['post_type'] ) || $_GET['post_type'] !== 'shop_order' ) && ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'wc-orders' ) ) {
            return;
        }
        
        $selected_option_value = ! empty( $_GET['montonio_shipping_provider'] ) ? sanitize_text_field( $_GET['montonio_shipping_provider'] ) : '';

        $active_methods   = array();
        $shipping_methods = WC()->shipping()->get_shipping_methods();
        foreach ( $shipping_methods as $id => $shipping_method ) {
            $active_methods[ $id ] = array(
                'id' => $id,
                'title' => $shipping_method->method_title,
            );
        }

        if ( ! empty( $active_methods ) ) {
            echo '<select id="montonio_shipping_provider" name="montonio_shipping_provider">';
            echo '<option value="">' . __( 'All shipping methods', 'montonio-for-woocommerce' ) . '</option>';
            foreach (  $active_methods as $method ) {
                if( $selected_option_value == $method['id'] ){
                    $selected = ' selected="selected"';
                } else {
                    $selected = '';
                }   

                echo '<option value="' . $method['id'] . '"' . $selected . '>' . $method['title'] . '</option>';
            }
            echo '</select>';
        }
    }

    public function add_shipping_scripts() {
        if ( get_option('montonio_shipping_enabled' ) !== 'yes' && get_option( 'montonio_shipping_enqueue_mode', 'enqueue' ) !== 'enqueue' && ! is_checkout() ) {
            return;
        }

        wp_enqueue_style( 'montonio-pickup-points' );
        wp_enqueue_style( 'montonio-shipping-options' );

        $custom_css = get_option( 'montonio_shipping_css', null );
        if ( $custom_css ) {
            wp_add_inline_style( 'montonio-shipping-options', $custom_css );
        }

        if ( ! wp_script_is('selectWoo', 'registered') && get_option('montonio_shipping_register_selectWoo', 'no') === 'yes' ) {
            wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ) );
        }

        wp_enqueue_script( 'montonio-pickup-point-select');
    }

    public function output_filter_results( $where, $query ) {
        global $pagenow, $wpdb;
        $method = isset( $_GET['montonio_shipping_provider'] ) ? sanitize_text_field( $_GET['montonio_shipping_provider'] ) : false;

        if ( is_admin() && $pagenow == 'edit.php' && array_key_exists( 'post_type', $query->query ) && $query->query['post_type'] == 'shop_order' && ! empty( $method ) ) {
            $where .= $wpdb->prepare( 
                "AND ID IN (
                    SELECT order_id
                    FROM {$wpdb->prefix}woocommerce_order_itemmeta m
                    LEFT JOIN {$wpdb->prefix}woocommerce_order_items i 
                    ON i.order_item_id = m.order_item_id
                    WHERE meta_key = 'method_id' and meta_value = %s 
                )",
                $method
            );
        }

        return $where;
    }

    public function output_filter_results_hpos( $pieces ) {
        global $wpdb;
        $method = isset( $_GET['montonio_shipping_provider'] ) ? sanitize_text_field( $_GET['montonio_shipping_provider'] ) : false;

        if ( is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'wc-orders' && ! empty( $method ) ) {
            $pieces['where'] .= $wpdb->prepare(
                " AND {$wpdb->prefix}wc_orders.id IN (
                    SELECT order_id
                    FROM {$wpdb->prefix}woocommerce_order_itemmeta m
                    LEFT JOIN {$wpdb->prefix}woocommerce_order_items i 
                    ON i.order_item_id = m.order_item_id
                    WHERE meta_key = 'method_id' and meta_value = %s 
                )",
                $method
            );
        }

        return $pieces;
    }

    public function add_custom_shipping_fields () {
        global $post;
        $montonio_no_parcel_machine = get_post_meta( $post->ID, '_montonio_no_parcel_machine', true );
        $montonio_separate_label = get_post_meta( $post->ID, '_montonio_separate_label', true );

        echo '<div class="montonio_shipping_options">';
        woocommerce_wp_checkbox( 
            array( 
                'id'            => '_montonio_no_parcel_machine', 
                'label'         => __( 'Parcel machine support', 'montonio-for-woocommerce' ), 
                'description'   => __( 'Disable "Parcel machine" shipping methods if this product is added to cart', 'montonio-for-woocommerce' ),
                'value'         => $montonio_no_parcel_machine,
            )
        );
        woocommerce_wp_checkbox( 
            array( 
                'id'            => '_montonio_separate_label', 
                'label'         => __( 'Separate shipping label', 'montonio-for-woocommerce' ), 
                'description'   => __( 'Create a separate Montonio shipping label for each of these products', 'montonio-for-woocommerce' ),
                'value'         => $montonio_separate_label,
            )
        );
        echo '</div>';
    }

    public function save_custom_shipping_fields( $post_id ) {
        $montonio_no_parcel_machine = '';
        $montonio_separate_label = '';

        if ( isset( $_POST['_montonio_no_parcel_machine'] ) ) {
            $montonio_no_parcel_machine = esc_attr( $_POST['_montonio_no_parcel_machine'] );
        }

        if ( isset( $_POST['_montonio_separate_label'] ) ) {
            $montonio_separate_label = esc_attr( $_POST['_montonio_separate_label'] );
        }
        
        update_post_meta( $post_id, '_montonio_no_parcel_machine', $montonio_no_parcel_machine );
        update_post_meta( $post_id, '_montonio_separate_label', $montonio_separate_label );
    }

    /**
     * Set up MontonioShippingSDK if necessary options exist
     * @return MontonioShippingSDK|null
     */
    protected function init_shipping_SDK() {
        $shipping_SDK = null;

        if ( $this->access_key && $this->secret_key ) {
            $shipping_SDK = new MontonioShippingSDK( $this->access_key, $this->secret_key, 'no' );
        }

        return $shipping_SDK;
    }

    /**
     * @param $rates
     * @param $package
     * @return mixed
     */
    public function get_available_shipping_methods( $rates, $package ) {
        if ( get_option( 'montonio_shipping_enabled' ) !== 'yes' ) {
            return $rates;
        }

        // Sync pickup points if needed
        $lastSyncedAt = get_option( 'montonio_pickup_points_synced_at' );
        $timeDiff = null;
        if ( $lastSyncedAt ) {
            $currentTime = time();
            $timeDiff    = $currentTime - $lastSyncedAt;
        }

        if ( ! $lastSyncedAt || ! $timeDiff || $timeDiff > 24 * 60 * 60 ) { // If difference greater than 24 hours
            do_action( 'montonio_shipping_sync_pickup_points' );
        }

        $shippingCountry = WC()->customer->get_shipping_country();

        // Go through shipping rates and add respective pickup points if needed
        foreach ( $rates as $key => $rate ) {
            if ( strpos($rate->method_id, 'montonio_' ) === false ) {
                continue;
            }

            if ( $rate->method_id === 'montonio_shipping' ) {
                unset( $rates[$key] );
                continue;
            }

            $metaData = $rate->get_meta_data();
            $providerName = $metaData['provider_name'];
            $type = $metaData['type'];
            $needsPickupPoints = in_array( $type, ['parcel_machine', 'post_office'] );

            if ( ! $needsPickupPoints ) {
                continue;
            }

            $pickupPointsOptionName = 'montonio_' . $shippingCountry . '_' . $providerName . '_' . $type;
            $availablePickupPoints = json_decode( get_option( $pickupPointsOptionName, '' ) );

            if ( ! $availablePickupPoints ) {
                unset( $rates[$key] );
            }

            $rate->montonio_pickup_points_option_name = $pickupPointsOptionName;
        }

        return $rates;
    }

    /**
     * @return void
     */
    public function add_pickup_points_to_checkout() {
        if ( get_option( 'montonio_shipping_enqueue_mode', 'enqueue' ) === 'echo' ) {
            echo '<link rel="stylesheet" href="' . WC_MONTONIO_PLUGIN_URL . '/shipping/assets/css/pickup-points.css?ver=' . WC_MONTONIO_PLUGIN_VERSION . '">';
            echo '<script type="text/javascript" src="'. WC_MONTONIO_PLUGIN_URL . '/shipping/assets/js/montonio-pickup-point-select.js?ver=' . WC_MONTONIO_PLUGIN_VERSION . '"></script>';
        }

        $chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods', array() );
        if ( ! $chosen_shipping_methods ) { 
            return;
        }

        ksort( $chosen_shipping_methods );
        $chosen_shipping_method_id = reset( $chosen_shipping_methods );

        if ( ! $chosen_shipping_method_id || strpos( $chosen_shipping_method_id, 'montonio_' ) === false ) {
            return;
        }

        $pickup_points = $this->get_pickup_points_for_chosen_shipping_method( $chosen_shipping_method_id );
        if ( empty( $pickup_points ) ) {
            return;
        }
        $pickup_points = $this->sort_pickup_points( $pickup_points );
        $placeholder = __( 'Select pickup point', 'montonio-for-woocommerce' );

        wc_get_template(
            'montonio/pickup-point-select.php',
            [
                'chosenShippingMethodId' => $chosen_shipping_method_id,
                'placeholder' => $placeholder,
                'pickupPoints' => $pickup_points
            ],
            '',
            WC_MONTONIO_PLUGIN_PATH . '/templates/'
        );
    }

    /**
     * @param $orderId
     * @return void
     */
    public function update_checkout_pickup_point( $orderId ) {
        $chosenPickupPointId = isset( $_POST['montonio_pickup_point'] ) ? sanitize_text_field( $_POST['montonio_pickup_point'] ) : null;

        $chosenShippingMethods = WC()->session->get( 'chosen_shipping_methods' );
        if ( ! $chosenShippingMethods || ! is_array( $chosenShippingMethods ) ) { 
            return;
        }

        ksort( $chosenShippingMethods );
        $chosenShippingMethodId = reset( $chosenShippingMethods );

        if ( ! $orderId || ! $chosenPickupPointId || ! $chosenShippingMethodId || strpos( $chosenShippingMethodId, 'montonio_' ) === false ) {
            return;
        }

        $pickupPoints = $this->get_pickup_points_for_chosen_shipping_method( $chosenShippingMethodId );
        if ( empty( $pickupPoints ) ) {
            return;
        }

        // Search for pickup point with matching id
        $chosenPickupPoint = null;
        foreach ( $pickupPoints as $pickupPoint ) {
            if ( $pickupPoint->uuid === $chosenPickupPointId ) {
                $chosenPickupPoint = $pickupPoint;
                break;
            }
        }

        if ( ! $chosenPickupPoint) {
            return;
        }

        $order = wc_get_order( $orderId );
        $order->update_meta_data( '_montonio_pickup_point_name', $chosenPickupPoint->name );
        $order->update_meta_data( '_montonio_pickup_point_uuid', $chosenPickupPointId );
        $order->set_shipping( 
            [
                'address_1' => $chosenPickupPoint->name,
                'address_2' => '',
                'city'       => $chosenPickupPoint->locality,
                'state'      => '',
                'postcode'   => '',
                'country'    =>  $chosenPickupPoint->country
            ] 
        );
        $order->save();
    }

    /**
     * @param $fields
     * @return void
     */
    public function validate_pickup_point( $fields ) {
        $chosenShippingMethods = WC()->session->get( 'chosen_shipping_methods' );
        if ( ! $chosenShippingMethods || ! is_array( $chosenShippingMethods ) ) { 
            return;
        }

        ksort( $chosenShippingMethods );
        $chosenShippingMethodId = reset( $chosenShippingMethods );

        if ( ! $chosenShippingMethodId || strpos( $chosenShippingMethodId, 'montonio_' ) === false ) {
            return;
        }

        if ( isset( $_POST['montonio_pickup_point'] ) && empty( $_POST['montonio_pickup_point'] ) ) {
            wc_add_notice( __( 'Please select a pickup point.', 'montonio-for-woocommerce' ), 'error' );
        }
    }

    /**
     * @return void
     */
    public function sync_pickup_points() {       
        $pickup_points = array();
        $shipping_SDK  = $this->init_shipping_SDK();

        if ( ! $shipping_SDK ) {
            $this->add_admin_notice( __( 'Please add Montonio API keys!', 'montonio-for-woocommerce' ), 'error' );
            return;
        }

        try {    
            $pickup_points = $shipping_SDK->get_pickup_points();
            $has_any_sync_failed = false;
            $carriers_pickup_point_countries = [];
            update_option( 'montonio_pickup_points_synced_at', time(), 'no' );

            // Divide pickup points into multiple rows in database
            foreach ( $pickup_points as $country => $pickup_points_per_country ) {
                foreach ( $pickup_points_per_country->providers as $provider => $pickup_points_per_provider ) {
                    foreach ( $pickup_points_per_provider as $type => $pickup_points_per_type ) {
                        $carriers_pickup_point_countries[$provider][$type][] = $country;
                        $option_name = 'montonio_' . $country . '_' . $provider . '_' . $type;
                        $pickup_point_json = json_encode($pickup_points_per_type );

                        if ( get_option( $option_name ) === $pickup_point_json ) {
                            continue;
                        }

                        $is_updated = update_option( $option_name, $pickup_point_json, 'no' );
                        $has_any_sync_failed = ! $is_updated ?: $has_any_sync_failed;
                    }
                }
            }

            update_option( 'montonio_carriers_pickup_point_countries', json_encode( $carriers_pickup_point_countries ), 'no' );

            if ( $has_any_sync_failed ) {
                throw new Exception( __( 'Montonio Shipping was unable to sync pickup points in the background. Please try again later!', 'montonio-for-woocommerce' ) );
            }

            $this->add_admin_notice( __( 'Montonio Shipping: Pickup point sync successful!', 'montonio-for-woocommerce' ), 'success' );

            update_option( 'montonio_shipping_is_shipping_set_up', true );

        } catch ( Exception $e ) {
            if ( ! empty( $e->getMessage() ) ) {
                WC_Montonio_Logger::log( 'Pickup points sync failed. Response: ' . $e->getMessage() );
                $this->add_admin_notice( __( 'Montonio API response: ', 'montonio-for-woocommerce' ) . $e->getMessage(), 'error' );
            }
        }
    }

    /**
     * @param $orderId
     * @param $order
     */
    public function create_shipment_when_payment_complete( $orderId, $order ) {
        if ( ! $order ) {
            return;
        }

        // Check if order has Montonio shipping method and no tracking code has already been generated
        $shippingMethod = $this->get_montonio_shipping_method( $order );

        if ( ! $shippingMethod || $shippingMethod->get_meta( 'tracking_codes' ) ) {
            return;
        }

        $this->create_shipment( $order, $shippingMethod, true );
    }

    /**
     * used to create a shipment in api manually via a button in wc order page
     */
    public function create_shipment_from_js_request() {
        $orderId = sanitize_text_field( $_POST['orderId'] );
        $order = wc_get_order( $orderId );

        if ( ! $order ) {
            wp_send_json_error( "SHIPMENT_CREATION_FAILED", 409 );
            return;
        }

        $shippingMethod = $this->get_montonio_shipping_method( $order );
        $response       = $this->create_shipment( $order, $shippingMethod );

        if ( $response ) {
            wp_send_json_success( "SHIPMENT_CREATED", 200 );
        } else {
            wp_send_json_error( "SHIPMENT_CREATION_FAILED", 409 );
        }
    }

    /**
     * @param $order
     */
    public function add_create_shipment_button( $order ) {
        if ( get_option( 'montonio_shipping_enabled' ) !== 'yes' ) {
            return;
        }

        wp_enqueue_script( 'montonio-admin-shipping-script' );

        $create_shipment_params = array(
            'orderId' => $order->get_id(),
            'createShipmentUrl' => add_query_arg( 'wc-api', 'montonio_shipping_create_shipment', trailingslashit( get_home_url() ) ),
            'shippingSettingsUrl' => admin_url( 'admin.php?page=wc-settings&tab=montonio_shipping' )
        );

        wp_localize_script( 'montonio-admin-shipping-script', 'montonio_create_shipment', $create_shipment_params );

        echo '
            <p style="margin-top:20px">
                <button
                    id="montonio-shipping-create-shipment"
                    type="button" class="button button-primary"
                    style="float:left">
                    Create shipment in Montonio
                </button>
                <span id="montonio-shipping-create-shipment-spinner" class="spinner" style="float:left"></span>
            </p>
        ';
    }

    /**
     * Perform some actions when Montonio Shipping settings are saved
     */
    public function process_shipping_options() {
        $this->sync_pickup_points();
    }

    public function handle_shipping_api_webhook() {
        if ( ! isset( $_REQUEST['webhook_token'] ) ) {
            http_response_code( 400 );
            return;
        }

        $token = sanitize_text_field( $_REQUEST['webhook_token'] );

        try {
            $response = MontonioShippingSDK::decode_webhook_token( $token, $this->secret_key );
        } catch ( Exception $exception ) {
            http_response_code( 401 );
            return;
        }

        $webhookMessage = sanitize_text_field( $response->message );

        switch ( $webhookMessage ) {
            case 'shipment_created':
                $this->handle_shipment_created_webhook( $response );
                break;
            case 'label_created':
                $this->handle_label_created_webhook( $response );
                break;
            default:
                break;
        }
    }

    /**
     * Register status "Label printed"
     */
    public function register_label_printed_status() {
        register_post_status( 'wc-mon-label-printed', array(
            'label'                     => _x( 'Label printed', 'Order status', 'montonio-for-woocommerce' ),
            'public'                    => true,
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'				=> _n_noop(
                'Label printed (%s)',
                'Label printed (%s)',
                'montonio-for-woocommerce'
            )
        ));
    }

    /**
     * @param $orderStatuses
     * @return mixed
     */
    public function add_custom_order_statuses( $orderStatuses ) {
        $orderStatuses['wc-mon-label-printed'] = _x( 'Label printed', 'Order status', 'montonio-for-woocommerce' );
        return $orderStatuses;
    }

    /**
     * @param $text
     * @param $order
     * @return string
     */
    public function modify_order_shipping_text( $text, $order ) {
        $shippingMethod = $this->get_montonio_shipping_method( $order );

        if ( ! $shippingMethod ) {
            return $text;
        }

        $shippingMethodName = $order->get_shipping_method();
        $pickupPointName = $order->get_meta( '_montonio_pickup_point_name' );
        
        if ( ! $pickupPointName ) {
            return $text;
        }

        $stringToReplace = $shippingMethodName . ' (' . $pickupPointName . ')';
        return str_replace( $shippingMethodName, $stringToReplace, $text );
    }

    public function add_print_labels_action( $actions ) {
        $actions['montonio_print_labels'] = __('Print shipping labels', 'montonio-for-woocommerce');

        wp_enqueue_script( 'montonio-print-labels', WC_MONTONIO_PLUGIN_URL . '/shipping/assets/js/montonio-print-labels.js', array(), WC_MONTONIO_PLUGIN_VERSION );

        $createLabelsUrl = add_query_arg( 'wc-api', 'montonio_shipping_create_labels', trailingslashit( get_home_url() ) );

        $inlineScriptData = '
            var createLabelsUrl = "' . $createLabelsUrl . '";
        ';
        wp_add_inline_script( 'montonio-print-labels', $inlineScriptData, 'before' );

        return $actions;
    }

    public function add_print_labels_action_single( $actions, $order = null ) {
        wp_enqueue_script( 'montonio-print-labels', WC_MONTONIO_PLUGIN_URL . '/shipping/assets/js/montonio-print-labels.js', array(), WC_MONTONIO_PLUGIN_VERSION );

        $createLabelsUrl = add_query_arg( 'wc-api', 'montonio_shipping_create_labels', trailingslashit( get_home_url() ) );
        $inlineScriptData = '
            var createLabelsUrl = "' . $createLabelsUrl . '";
        ';
        wp_add_inline_script( 'montonio-print-labels', $inlineScriptData, 'before' );

        $actions['montonio_print_labels'] = __( 'Print shipping labels', 'montonio-for-woocommerce' );
        return $actions;
    }

    public function create_labels() {
        $shipping_SDK = $this->init_shipping_SDK();

        if ( ! $shipping_SDK ) {
            wp_send_json_error( "LABEL_CREATION_FAILED", 409 );
            return;
        }

        if ( ! isset( $_POST['orderIds'] ) ) {
            wp_send_json_error( "NO_ORDER_IDS_PASSED", 409 );
            return;
        }

        $orderIds = sanitize_text_field( $_POST['orderIds'] );
        $storePrefix = get_option( 'montonio_shipping_order_prefix' );
        $data = [
            'merchant_references' => array_map( function( $orderId ) use ( $storePrefix ) {
                return MontonioOrderPrefixer::addPrefix( $storePrefix, (string) $orderId );
            }, $orderIds )
        ];

        try {
            $response = $shipping_SDK->create_labels( $data );
            wp_send_json_success( $response );
        } catch ( Exception $e ) {
            WC_Montonio_Logger::log( 'Label creation failed. Response: ' . $e->getMessage() );
            wp_send_json_error( "LABEL_CREATION_FAILED", 409 );
        }
    }

    public function modify_order_columns( $column, $orderId ) {
        $order = wc_get_order( $orderId );

        if ( ! $order ) {
            return;
        }

        if ( $column === 'shipping_address' ) {
            $this->add_tracking_codes_to_custom_column( $order );
        }
    }

    public function replace_email_placeholders( $string, $email ) {
        $placeholder = '{montonio_tracking_info}';
        $order       = $email->object;

        if (!$order) {
            return str_replace( $placeholder, '', $string );
        }

        $fullText = $this->get_montonio_tracking_info( $order );
        return str_replace( $placeholder, $fullText, $string );
    }

    public function hide_shipping_method_meta_data( $fieldsToHide ) {
        $montonioFieldsToHide = [
            'shipping_method_identifier',
            'provider_name',
            'type',
            'method_class_name',
            'tracking_codes',
            'instance_id'
        ];

        return array_merge( $fieldsToHide, $montonioFieldsToHide );
    }

    public function display_shipping_notices() {
        global $current_tab;
        $isShippingSetUp = get_option( 'montonio_shipping_is_shipping_set_up', false );

        if ( $current_tab !== 'montonio_shipping' || $isShippingSetUp ) {
            return;
        }
        
        $this->add_admin_notice( __( 'Before integrating deliveries in WooCommerce, make sure the providers are activated in Montonio\'s Partner System. If you cannot see "Shipping" under your account in the Partner System, please contact support@montonio.com', 'montonio-for-woocommerce' ), 'info' );
    }

    /**
     * Return appropriate multiplier to convert to kg
     * @return float|int
     */
    public static function get_weight_multiplier() {
        $weightUnit = get_option( 'woocommerce_weight_unit' );

        switch ($weightUnit) {
            case 'kg':
                return 1;
            case 'g':
                return 0.001;
            case 'lbs':
                return 0.45;
            case 'oz':
                return 0.028;
            default:
                // Unknown unit, treat as missing measurements
                return 0;
        }
    }

    public function edit_shipping_method_label( $label, $rate ) {
        if ( strpos( $rate->method_id, 'montonio_' ) === false ) {
            return $label;
        }

        $meta_data = $rate->get_meta_data();
        $class_name = $meta_data['method_class_name'];
        $class_instance = new $class_name( $rate->get_instance_id() );        

        if ( ! ( $rate->get_cost() > 0 ) && isset( $class_instance->instance_settings['enable_free_shipping_text'] ) && $class_instance->instance_settings['enable_free_shipping_text'] === 'yes' ) {
            if ( isset( $class_instance->instance_settings['free_shipping_text'] ) && $class_instance->instance_settings['free_shipping_text'] !== '' ) {
                $label .= ': ' . $class_instance->instance_settings['free_shipping_text'];
            } else {
                $label .= ': ' . wc_price( 0 );
            }
        }
        
        if ( get_option( 'montonio_shipping_show_provider_logos' ) === 'yes' && $class_instance->logo ) {
            $label .= '<br><img class="montonio-shipping-provider-logo" id="' . $rate->get_id() . '_logo" src="' . $class_instance->logo . '">';
        }

        return $label;
    }

    public function enqueue_shipping_options_style() {
        if ( get_option( 'montonio_shipping_enqueue_mode', 'enqueue' ) === 'echo' ) {
            echo '<link rel="stylesheet" href="' . WC_MONTONIO_PLUGIN_URL . '/shipping/assets/css/shipping-options.css?ver=' . WC_MONTONIO_PLUGIN_VERSION . '">';
        }
    }

    protected function get_montonio_tracking_info( $order ) {
        $shippingMethod = $this->get_montonio_shipping_method( $order );
        $trackingCodes = $shippingMethod ? $shippingMethod->get_meta( 'tracking_codes' ) : null;
        $trackingCodesText = __( get_option( 'montonio_email_tracking_code_text', __( 'Track your shipment:', 'montonio-for-woocommerce' ) ), 'montonio-for-woocommerce' );

        return $trackingCodes ? $trackingCodesText . ' ' . $trackingCodes : '';
    }

    protected function handle_shipment_created_webhook( $response ) {
        $orderId = MontonioOrderPrefixer::removePrefix( sanitize_text_field( $response->merchant_reference ) );
        $order = wc_get_order( $orderId );

        if ( ! $order ) {
            http_response_code( 409 );
            return;
        }

        $trackingCodesText = $this->process_tracking_codes( $response->tracking_numbers );
        $orderNote = 'Shipment created.';

        if ( $trackingCodesText ) {
            $orderNote .= '<br>Tracking codes: ' . $trackingCodesText;
        }

        $order->add_order_note( $orderNote );

        $this->update_tracking_codes_metadata( $order, $response->tracking_numbers );
        http_response_code( 200 );
    }

    protected function handle_label_created_webhook( $response ) {
        $orderId = MontonioOrderPrefixer::removePrefix( sanitize_text_field( $response->merchant_reference ) );
        $order = wc_get_order( $orderId );

        if ( ! $order ) {
            http_response_code( 409 );
            return;
        }

        $trackingCodesText = $this->process_tracking_codes( $response->tracking_numbers );
        $orderNote = 'Shipping label printed.';

        if ( $trackingCodesText ) {
            $orderNote .= '<br>Tracking codes: ' . $trackingCodesText;
        }

        $order->add_order_note( $orderNote );

        $newStatus = get_option(' montonio_shipping_orderStatusWhenLabelPrinted', 'wc-mon-label-printed' );

        if ( $order->get_status() === 'processing' && $newStatus !== 'no-change' ) {
            $order->update_status( $newStatus );
        }

        $this->update_tracking_codes_metadata( $order, $response->tracking_numbers );
        http_response_code( 200 );
    }

    protected function update_tracking_codes_metadata( $order, $trackingCodes ) {
        $trackingCodesText = $this->process_tracking_codes( $trackingCodes );
        $shippingMethod = $this->get_montonio_shipping_method( $order );

        if ( $shippingMethod && $trackingCodesText ) {
            $shippingMethod->update_meta_data( 'tracking_codes', $trackingCodesText );
            $shippingMethod->save_meta_data();

            $rawTrackingCodes = [];
            foreach ( $trackingCodes as $trackingCode ) {
                $rawTrackingCodes[] = $trackingCode->code;
            }

            $trackingInfo = $this->get_montonio_tracking_info( $order );
            $order->update_meta_data( '_montonio_tracking_codes', implode( ', ', $rawTrackingCodes ) );
            $order->update_meta_data( '_montonio_tracking_info', $trackingInfo );
            $order->save_meta_data();
        }
    }

    protected function process_tracking_codes( $trackingCodes ) {
        $trackingCodesText = '';
        foreach ( $trackingCodes as $trackingCode ) {
            $trackingCodesText .= '
            <a target="_blank" href=' . sanitize_text_field( $trackingCode->link ) . '>'
                . sanitize_text_field( $trackingCode->code ) .
                '</a><br>';
        }

        return $trackingCodesText;
    }

    protected function add_tracking_codes_to_custom_column( $order ) {
        $shippingMethod = $this->get_montonio_shipping_method( $order );

        if ( ! $shippingMethod ) {
            return;
        }

        if ( $shippingMethod->get_meta('tracking_codes' ) ) {
            echo __( 'Tracking code(s)', 'montonio-for-woocommerce' ) . ':<br />' .
                $shippingMethod->get_meta( 'tracking_codes' );
            return;
        }

        $orderDatePaid = $order->get_date_paid();
        $now = time();

        if ( ! $orderDatePaid ) {
            return;
        }

        // If enough time has passed from payment and no tracking code has been received, show error message
        if ( $now - $orderDatePaid->getTimestamp() > 5 * 60 ) {
            echo '<span style="color:sandybrown">'
                . __( 'Unexpected error - Check status from Montonio Partner System', 'montonio-for-woocommerce' )
                . '</span><br />';
        } else {
            echo '<span style="color:orange">'
                . __( 'Waiting for tracking codes from Montonio', 'montonio-for-woocommerce' )
                . '</span><br />';
        }
    }

    protected function sort_pickup_points( $pickupPoints ) {
        // Sort pickup points by name
        usort($pickupPoints, function ( $pickupPoint1, $pickupPoint2 ) {
            return strcmp( $pickupPoint1->name, $pickupPoint2->name );
        });

        $pickupPointCount = count( $pickupPoints );
        // Count the number of pickup points in each place
        $placeNameCounts = $this->get_place_name_counts( $pickupPoints );
        $eelistusOmnivas = array();
        $pickupPointsByLargePlaces = array();
        $pickupPointsByOtherPlaces = array();
        $unspecifiedPickupPoints = array();

        foreach ($pickupPoints as $pickupPoint) {
            $hasLocality = isset( $pickupPoint->locality ) && $pickupPoint->locality !== '';
            $hasRegion = isset( $pickupPoint->region) && $pickupPoint->region !== '';
            $placeName = $hasLocality ? $pickupPoint->locality : ( $hasRegion ? $pickupPoint->region : '--' );
            $placeNameCount = isset( $placeNameCounts[$placeName] ) ? $placeNameCounts[$placeName] : 0;

            // No locality or region
            if ( $placeName === '--' ) {
                $arrayToUse = &$unspecifiedPickupPoints;
            } elseif ( $placeName === '1. eelistus Omnivas' ) {
                $arrayToUse = &$eelistusOmnivas;
            // Place has relatively many pickup points
            } elseif ( $placeNameCount >= $pickupPointCount * 0.03 ) {
                $arrayToUse = &$pickupPointsByLargePlaces;
            // Place has relatively few pickup points
            } else {
                $arrayToUse = &$pickupPointsByOtherPlaces;
            }

            if ( ! isset( $arrayToUse[$placeName] ) ) {
                $arrayToUse[$placeName] = array();
            }

            array_push( $arrayToUse[$placeName], $pickupPoint );
        }

        // Sort large places by number of pickup points
        uasort( $pickupPointsByLargePlaces, function( $locality1, $locality2 ) {
            return count( $locality1 ) >= count( $locality2 ) ? -1 : 1;
        });

        // Sort other places alphabetically
        ksort($pickupPointsByOtherPlaces);

        return array_merge($eelistusOmnivas, $pickupPointsByLargePlaces, $pickupPointsByOtherPlaces, $unspecifiedPickupPoints);
    }

    protected function get_place_name_counts( $pickupPoints ) {
        $placeNameCounts = array();

        foreach ( $pickupPoints as $pickupPoint ) {
            if ( isset( $pickupPoint->locality ) && $pickupPoint->locality !== '' ) {
                $placeName = $pickupPoint->locality;
            } else if ( isset( $pickupPoint->region ) && $pickupPoint->region !== '' ) {
                $placeName = $pickupPoint->region;
            } else {
                continue;
            }

            if ( ! isset( $placeNameCounts[$placeName] ) ) {
                $placeNameCounts[$placeName] = 0;
            }

            $placeNameCounts[$placeName]++;
        }

        return $placeNameCounts;
    }

    /**
     * @param $order
     * @param null $shippingMethod
     * @param bool $async
     * @return array
     */
    protected function create_shipment( $order, $shippingMethod = null, $async = false ) {
        $shipping_SDK = $this->init_shipping_SDK();

        if ( ! $shipping_SDK ) {
            return;
        }

        $shippingMethodIdentifier = $shippingMethod && $shippingMethod->get_method_id() ?
        preg_replace('/^montonio_/', '', $shippingMethod->get_method_id() ) :
        null;

        $data = $this->get_create_shipment_data( $order, $shippingMethodIdentifier );
        
        try {
            return $shipping_SDK->post_shipment( $data, $async );
        } catch ( Exception $e ) {
            $order->add_order_note( __( 'Shipment creation failed. Response: ', 'montonio-for-woocommerce' ) . $e->getMessage()  );   
            WC_Montonio_Logger::log( 'Shipment creation failed. Response: ' . $e->getMessage() );
            return;
        }
    }

    /**
     * @param $chosenShippingMethodId
     * @return array
     */
    protected function get_pickup_points_for_chosen_shipping_method( $chosenShippingMethodId ) {
        $packages = WC()->shipping->get_packages();

        if ( ! $packages ) {
            return [];
        }

        $chosenShippingMethod = null;

        foreach ( $packages as $i => $package ) {
            if ( isset( $package['rates'] ) ) {
                foreach($package['rates'] as $shippingMethod ) {
                    if ( $shippingMethod->id === $chosenShippingMethodId ) {
                        $chosenShippingMethod = $shippingMethod;
                        break;
                    }
                }
            }
        }

        if ( ! $chosenShippingMethod ) {
            return [];
        }

        // Backwards compatibility for checkout sessions cached before version 4.2.0
        if ( isset( $chosenShippingMethod->montonio_pickup_points ) ) {
            return $chosenShippingMethod->montonio_pickup_points;
        }

        if ( isset( $chosenShippingMethod->montonio_pickup_points_option_name ) ) {
            return json_decode( get_option( $chosenShippingMethod->montonio_pickup_points_option_name, '' ) );
        }

        return [];
    }

    protected function get_create_shipment_data( $order, $shippingMethodIdentifier ) {
        $storePrefix = get_option( 'montonio_shipping_order_prefix' );
        $data = array(
            'currency'                  => (string) $order->get_currency(),
            'shipping_total'            => (float) $order->get_shipping_total(),
            'total'                     => (float) $order->get_total(),
            'billing_first_name'        => (string) $order->get_billing_first_name(),
            'billing_last_name'         => (string) $order->get_billing_last_name(),
            'billing_company'           => (string) $order->get_billing_company(),
            'billing_street_address_1'  => (string) $order->get_billing_address_1(),
            'billing_street_address_2'  => (string) $order->get_billing_address_2(),
            'billing_locality'          => (string) $order->get_billing_city(),
            'billing_region'            => (string) $order->get_billing_state(),
            'billing_postal_code'       => (string) $order->get_billing_postcode(),
            'billing_country'           => (string) $order->get_billing_country(),
            'billing_email'             => (string) $order->get_billing_email(),
            'billing_phone_number'      => (string) $order->get_billing_phone(),
            'shipping_first_name'       => (string) $order->get_shipping_first_name(),
            'shipping_last_name'        => (string) $order->get_shipping_last_name(),
            'shipping_company'          => (string) $order->get_shipping_company(),
            'shipping_street_address_1' => (string) $order->get_shipping_address_1(),
            'shipping_street_address_2' => (string) $order->get_shipping_address_2(),
            'shipping_locality'         => (string) $order->get_shipping_city(),
            'shipping_region'           => (string) $order->get_shipping_state(),
            'shipping_postal_code'      => (string) $order->get_shipping_postcode(),
            'shipping_country'          => (string) $order->get_shipping_country(),
            'shipping_phone_number'     => method_exists( $order, 'get_shipping_phone' ) ? (string) $order->get_shipping_phone() : null,
            'payment_method'            => (string) $order->get_payment_method(),
            'merchant_reference'        => (string) MontonioOrderPrefixer::addPrefix( $storePrefix, $order->get_id() ),
            'sender_name'               => (string) get_option('montonio_shipping_senderName'),
            'sender_phone_number'       => (string) get_option('montonio_shipping_senderPhone'),
            'sender_street_address_1'   => (string) get_option('montonio_shipping_senderStreetAddress'),
            'sender_locality'           => (string) get_option('montonio_shipping_senderLocality'),
            'sender_postal_code'        => (string) get_option('montonio_shipping_senderPostalCode'),
            'sender_region'             => (string) get_option('montonio_shipping_senderRegion'),
            'sender_country'            => (string) get_option('montonio_shipping_senderCountry'),
            'webhook_url'               => apply_filters( 'wc_montonio_shipping_webhook_url', add_query_arg( 'wc-api', 'montonio_shipping_api_webhook', trailingslashit( get_home_url() ) ) )
        );

        if ( $shippingMethodIdentifier ) {
            $data['shipping_method'] = (string) $shippingMethodIdentifier;
        }

        $pickupPointUuid = $order->get_meta( '_montonio_pickup_point_uuid' );
        
        if ( $pickupPointUuid ) {
            $data['pickup_point_uuid'] = (string) $pickupPointUuid;
        }

        $parcels = [];

        foreach ( $order->get_items() as $item ) {
            $product = $item->get_product();

            if ( $product->get_meta( '_montonio_separate_label') == 'yes' ) {
                for ( $i = 0; $i < $item->get_quantity(); $i++ ){
                    $parcels[] = [ 'weight' => (float) $product->get_weight() * self::get_weight_multiplier() ];
                }
            } else {
                if( array_key_exists( 'combined', $parcels ) ) {
                    $parcels['combined']['weight'] += (float) $product->get_weight() * $item->get_quantity() * self::get_weight_multiplier();
                } else {
                    $parcels['combined']['weight'] = (float) $product->get_weight() * $item->get_quantity() * self::get_weight_multiplier();
                }
           }
        }

        $data['parcels'] = array_values( $parcels );
        $data = apply_filters( 'wc_montonio_before_shipping_data_submission', $data, $order, $shippingMethodIdentifier );

        return $data;
    }

    protected function get_montonio_shipping_method( $order ){
        if ( get_option( 'montonio_shipping_enabled' ) !== 'yes' ) {
            return null;
        }

        if ( ! method_exists((object) $order, 'get_shipping_methods' ) ) {
            return null;
        }
        
        $shippingMethod = null;
        
        foreach( $order->get_shipping_methods() as $shippingItem ) {
            $methodId = $shippingItem->get_method_id();
            if ( strpos($methodId, 'montonio_' ) !== false ) {
                $shippingMethod = $shippingItem;
                break;
            }
        }

        return $shippingMethod;
    }

    // =========================================================================
    // Admin notices
    // =========================================================================

    /**
     * Display admin notices
     */
    public function add_admin_notice( $message, $class ) {
        $this->admin_notices[] = array( 'message' => $message, 'class' => $class );
	}

    public function display_admin_notices() {
		foreach ( $this->admin_notices as $notice ) {
			echo '<div class="notice notice-' . esc_attr( $notice['class'] ) . '">';
			echo '	<p>' . wp_kses_post( $notice['message'] ) . '</p>';
			echo '</div>';
		}
	}
}