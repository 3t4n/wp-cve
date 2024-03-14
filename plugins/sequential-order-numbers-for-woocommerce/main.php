<?php
define( "BeRocket_Sequential_Order_Numbers_domain", 'BeRocket_Sequential_Order_Numbers_domain');
define( "BeRocket_Sequential_Order_Numbers_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('BeRocket_Sequential_Order_Numbers_domain', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'berocket/framework.php');
foreach (glob(__DIR__ . "/includes/*.php") as $filename)
{
    include_once($filename);
}

class BeRocket_Order_Numbers extends BeRocket_Framework {
    public $info, $defaults, $values;
    protected static $instance;
    protected $disable_settings_for_admin = array();
    function __construct () {
        $this->info = array(
            'id'          => 27,
            'version'     => BeRocket_Sequential_Order_Numbers_version,
            'plugin'      => '',
            'slug'        => 'berocket-order-numbers',
            'key'         => '',
            'name'        => 'Sequential Order Numbers for WooCommerce',
            'plugin_name' => 'BeRocket_Order_Numbers',
            'full_name'   => 'Sequential Order Numbers',
            'norm_name'   => 'Sequential Order Numbers',
            'price'       => '',
            'domain'      => 'BeRocket_Sequential_Order_Numbers_domain',
            'templates'   => BeRocket_Sequential_Order_Numbers_TEMPLATE_PATH,
            'plugin_file' => BeRocket_Order_Numbers_file,
            'plugin_dir'  => __DIR__,
        );

        $this->defaults = array(
            'remove_username'       => '',
            'number_start'          => '',
            'order_text'            => array(),
            'custom_css'            => '',
            'script'                => array(
                'js_page_load'      => '',
            ),
            'plugin_key'            => '',
        );

        $this->values = array(
            'settings_name' => 'br-BeRocket_Order_Numbers-options',
            'option_page'   => 'br-BeRocket_Order_Numbers',
            'premium_slug'  => 'sequential-order-numbers',
            'free_slug'     => 'sequential-order-numbers-for-woocommerce',
            'hpos_comp'     => true
        );

        // List of the features missed in free version of the plugin
        $this->feature_list = array();

        parent::__construct( $this );

        if ( $this->init_validation() ) {
            $options = parent::get_option();
            if ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) )
                && br_get_woocommerce_version() >= 2.1 ) {
                add_action( 'admin_init', array( $this, 'include_admin' ) );
            }

            /*
             * Set the custom order number on the new order
             */
            // hook for front-end order
            add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'set_sequential_order_number' ), 10, 2 );
            //hook for admin order
            add_action( 'woocommerce_process_shop_order_meta',    array( $this, 'set_sequential_order_number' ), 10, 1 );

            add_action( 'woocommerce_before_resend_order_emails', array( $this, 'set_sequential_order_number' ), 10, 1 );
            add_action( 'woocommerce_api_create_order',           array( $this, 'set_sequential_order_number' ), 10, 1 );
            add_action( 'woocommerce_deposits_create_order',      array( $this, 'set_sequential_order_number' ), 10, 1 );
//            add_action( 'woocommerce_ajax_add_order_item_meta',   array( $this, 'set_sequential_order_number' ) );

            // return our custom order number for display
            add_filter( 'woocommerce_order_number', array( $this, 'get_order_number' ), 10, 2);

            // add custom order number to order search
            add_filter( 'woocommerce_shop_order_search_fields', array( $this, 'woocommerce_shop_order_search_fields' ) );
            
            do_action('berocket_sequential_order_number_after_construct');
            if( ! empty($options['remove_username']) ) {
                add_action( 'manage_shop_order_posts_custom_column', array( $this, 'render_columns' ), 1, 2 );
                add_action( 'manage_shop_order_posts_custom_column', array( $this, 'render_columns_after' ), 200, 2 );
            }
            add_filter ( 'BeRocket_updater_menu_order_custom_post', array($this, 'menu_order_custom_post') );
        }
    }

    public function render_columns( $column, $post_id ) {
        global $br_son_exc_html;
        if($column == 'order_number' ) {
            $buyer = '';
            $object = wc_get_order( $post_id );
            if ( $object->get_billing_first_name() || $object->get_billing_last_name() ) {
                $buyer = trim( sprintf( _x( '%1$s %2$s', 'full name', 'woocommerce' ), $object->get_billing_first_name(), $object->get_billing_last_name() ) );
            } elseif ( $object->get_billing_company() ) {
                $buyer = trim( $object->get_billing_company() );
            } elseif ( $object->get_customer_id() ) {
                $user  = get_user_by( 'id', $object->get_customer_id() );
                $buyer = ucwords( $user->display_name );
            }
            $br_son_exc_html = $buyer;
            add_filter('esc_html', array($this, 'esc_html'), 10, 2);
        }
    }

    public function esc_html ($safe_text, $text) {
        global $br_son_exc_html;
        if( ! empty($br_son_exc_html) && ! empty($text) && $text == $br_son_exc_html ) {
            $safe_text = '';
        }
        return $safe_text;
    }

    public function render_columns_after( $column, $post_id ) {
        if($column == 'order_number' ) {
            remove_filter('esc_html', array($this, 'esc_html'), 10, 2);
        }
    }

    public function include_admin() {
        wp_register_script( 'br_son_admin_js', plugins_url( 'js/admin-js.js',  __FILE__ ), array('jquery', 'jquery-ui-sortable'), $this->info['version'] );
        wp_register_style( 'br_son_admin_style', plugins_url( 'css/admin.css',  __FILE__ ), "", $this->info['version'] );
        if ( ! empty( $_GET['page'] ) && $_GET['page'] == $this->values['option_page'] ) {
            wp_enqueue_script( 'br_son_admin_js' );
            wp_enqueue_style('br_son_admin_style');
        }
    }
    public function init_validation() {
        return ( ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) && 
            br_get_woocommerce_version() >= 2.1 );
    }

    /**
     * Function add options button to admin panel if parent will not do it self
     *
     * @access public
     *
     * @return void
     */
    public function admin_menu() {
        if ( parent::admin_menu() ) {
            add_submenu_page(
                'woocommerce',
                __( $this->info[ 'norm_name' ]. ' Settings', $this->info[ 'domain' ] ),
                __( $this->info[ 'norm_name' ], $this->info[ 'domain' ] ),
                'manage_options',
                $this->values[ 'option_page' ],
                array(
                    $this,
                    'option_form'
                )
            );
        }
    }

    public function admin_settings( $tabs_info = array(), $data = array(), $setup_style = array() ) {
        $options = parent::get_option();
        parent::admin_settings(
            array(
                'General'  => array(
                    'icon' => 'cog',
                ),
                'Custom Numbers' => array(
                    'icon' => 'plus-square',
                    'link' => admin_url( 'edit.php?post_type=br_order_numbers' ),
                ),
                'CSS'      => array(
                    'icon' => 'css3',
                ),
            ),
            array(
                'General' => array(
                    'remove_username' => array(
                        "type"     => "checkbox",
                        "label"    => __('Remove username', 'BeRocket_products_label_domain'),
                        "label_for"=> __('Remove username from order number in admin panel', 'BeRocket_products_label_domain'),
                        "name"     => "remove_username",
                        "value"    => "1",
                    ),
                    array(
                        "section"   => "preview_order",
                        "label"     => "Preview order",
                    ),
                    array(
                        "label"     => __('Start Number', 'BeRocket_Sequential_Order_Numbers_domain'),
                        "items"     => array(
                            array(
                                "type"      => "number",
                                "name"      => "number_start",
                                "class"     => "order-field number_start",
                                "extra"     => "data-part='number'",
                            ),
                            array(
                                "type"      => "checkbox",
                                "name"      => 'number_start_reset',
                                "value"     => "1",
                                "class"     => "number_start_reset",
                                "label_for" => __( 'Reset number (numbers for order will be started from this value instead latest number)' , "BeRocket_Sequential_Order_Numbers_domain" ),
                            ),
                        ),
                    ),
                    'number_text'  => array(
                        "section"  => "number_text_selector",
                        "name"     => "order_text",
                        "value"    => $options["order_text"],
                    ),
                ),
                'CSS'     => array(
                    array(
                        "type"  => "textarea",
                        "label" => "Custom CSS",
                        "name"  => "custom_css",
                    ),
                ),
            ) );
    }

//    Custom sections

    public function section_number_text_selector($item, $options) {
        $html = '</tr><tr><td colspan="2">';
        $html .= BeRocket_order_numbers_text_selector::generate_selector($this->values[ 'settings_name' ].'['.$item['name'].']', (empty($item['value']) ? array() : $item['value']), array('preview' => '.preview-order-mode'));
        $html .= '</td></tr>';
        return $html;
    }

    public function section_preview_order ( $item, $options ) {
        $html  = '<th>'. __( $item['label'], $this->info['domain'] ) .'</th>';
        $html .= '<td class="preview-order-mode">';
        $html .= '    <span class="br_preview"></span>';
        $html .= '</td>';

        return $html;
    }

    public function get_order_meta($order_id, $meta_key) {
        $order = wc_get_order( $order_id );
        if( is_a($order, 'WC_Order') ) {
            $result = $order->get_meta($meta_key, true);
        }
        if( empty($result) ) {
            $result = get_post_meta($order_id, $meta_key, true);
        }
        return $result;
    }

    public function update_order_meta($order, $meta_key, $meta_value) {
        $result = $order->update_meta_data($meta_key, $meta_value);
    }

    public function get_order_number( $br_order_id ) {
        $meta_array = $this->get_order_meta($br_order_id, '_sequential_order_number');
        if( ! empty($meta_array) && is_string($meta_array) ) {
            $br_order_id = esc_attr($meta_array);
        } elseif( ! empty($meta_array) && is_array($meta_array) ) {
            $prefix  = $meta_array['prefix'];
            $number  = $meta_array['number'] ?: $br_order_id;
            $postfix = $meta_array['postfix'];
            $br_order_id = $prefix . $number . $postfix;
            $br_order_id = esc_attr($br_order_id);
        }
        return $br_order_id;
    }

    public function set_sequential_order_number( $order_id, $data = false ) {
        $order_id   = is_a( $order_id, 'WC_Order' ) ? $order_id->get_id() : $order_id;
        $order      = wc_get_order( $order_id );
        $BeRocket_Order_Numbers_custom_post = BeRocket_Order_Numbers_custom_post::getInstance();
        $posts_array = $BeRocket_Order_Numbers_custom_post->get_custom_posts_frontend();
        $settings_seq_order_num = false;
        $settings_seq_order_num_id = false;
        foreach($posts_array as $number_order) {
            $settings_seq_order_num = get_post_meta( $number_order, 'br_order_numbers', true );
            $settings_seq_order_num_id = $number_order;
            if( br_condition_check($settings_seq_order_num['condition'], 'berocket_seq_order_numbers', array('order_id' => $order_id, 'order' => $order)) ) {
                break;
            }
            $settings_seq_order_num_id = false;
            $settings_seq_order_num = false;
        }
        $options = parent::get_option();
        $new_order_id = $this->get_order_meta($order, '_sequential_order_number_id');
        if( $settings_seq_order_num != false && empty($new_order_id) ) {
            $order_text = (empty($settings_seq_order_num['order_text']) ? array() : $settings_seq_order_num['order_text']);
            if( ! empty($settings_seq_order_num['start_number']) ) {
                $new_order_id = get_post_meta( $settings_seq_order_num_id, 'br_order_number_id', true );
                if( empty($new_order_id) ) {
                    $new_order_id = $settings_seq_order_num['start_number'];
                } else {
                    $new_order_id++;
                }
                update_post_meta( $settings_seq_order_num_id, 'br_order_number_id', $new_order_id );
            }
        } else {
            $order_text = (empty($options['order_text']) ? array() : $options['order_text']);
        }
        if( $new_order_id == false ) {
            if( ! empty($options['number_start']) ) {
                $new_order_id = get_option( 'br_order_number_id' );
                if( $new_order_id === false ) {
                    $new_order_id = $options['number_start'];
                } else {
                    $new_order_id++;
                }
                update_option('br_order_number_id', $new_order_id);
            }
        }
        if( $new_order_id == false ) {
            $new_order_id = $order_id;
        }
        $this->update_order_meta($order, '_sequential_order_number_id', $new_order_id);

        $additional = array('new_order_id' => $new_order_id, 'options' => $options, 'seq_options' => $settings_seq_order_num, 'seq_id' => $settings_seq_order_num_id, 'order_id' => $order_id, 'order' => $order);
        $order_number = $this->generate_number_for_order($order_text, $additional);

        $this->update_order_meta($order, '_sequential_order_number', $order_number);
        $this->update_order_meta($order, '_start_sequential_order_number', $order_number);
        $order->save();

        return true;
    }
    public function generate_number_for_order($order_text, $additional) {
        $order_number = '';
        $additional = apply_filters('berocket_seq_generate_number_additional_data', $additional);
        if( is_array($order_text) && count($order_text) ) {
            foreach($order_text as $order_text_type) {
                $order_number = apply_filters('berocket_seq_generate_number_type_'.$order_text_type['type'], $order_number, $order_text_type, $additional);
            }
        } else {
            $order_number = $additional['order_id'];
        }
        return $order_number;
    }
    public function woocommerce_shop_order_search_fields($metakeys) {
        $metakeys[] = '_sequential_order_number';
        return $metakeys;
    }
    /*
     * Save settings in Sequential order admin page
     */
    public function save_settings_callback($settings) {
        $options = parent::get_option();
        if( ! empty($settings['number_start_reset']) ) {
            delete_option('br_order_number_id');
        }
        $settings['number_start_reset'] = '';
        $settings = parent::save_settings_callback($settings);
        return $settings;
    }
    public function updater_info( $plugins ) {
        return $plugins;
    }
    public function menu_order_custom_post($compatibility) {
        $compatibility['br_order_numbers'] = 'br-BeRocket_Order_Numbers';
        return $compatibility;
    }
    public function update_version($previous, $current) {
        if ( version_compare( $previous, '3.5.1', '<' ) ) {
            $BeRocket_Order_Numbers_custom_post = BeRocket_Order_Numbers_custom_post::getInstance();
            $posts_array = $BeRocket_Order_Numbers_custom_post->get_custom_posts(array('orderby' => 'name', 'meta_key' => ''));
            foreach ( $posts_array as $order_number ) {
                $priority = get_post_meta( $order_number, 'berocket_priority' , true);
                $priority = intval($priority);
                $priority = ( $priority > 0 ? $priority : 0 );
                update_post_meta( $order_number, 'berocket_post_order', $priority, true );
            }
        }
    }
}

new BeRocket_Order_Numbers;

