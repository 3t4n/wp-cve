<?php

class BeRocket_Order_Numbers_custom_post extends BeRocket_custom_post_class {
    public $hook_name = 'berocket_seq_order_numbers';
    public $post_type_parameters = array(
        'sortable' => true,
        'can_be_disabled' => true
    );
    protected static $instance;
    function __construct() {
        $this->post_name = 'br_order_numbers';
        $this->post_settings = array(
            'label'  => __( 'Order Numbers', 'BeRocket_Sequential_Order_Numbers_domain' ),
            'labels' => array(
                'menu_name'          => _x( 'Order Numbers', 'Admin menu name', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'add_new_item'       => __( 'Add New Order Number', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'edit'               => __( 'Edit', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'edit_item'          => __( 'Edit Order Number', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'new_item'           => __( 'New Order Number', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'view'               => __( 'View Order Numbers', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'view_item'          => __( 'View Order Number', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'search_items'       => __( 'Search Order Numbers', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'not_found'          => __( 'No Order Numbers found', 'BeRocket_Sequential_Order_Numbers_domain' ),
                'not_found_in_trash' => __( 'No Order Numbers found in trash', 'BeRocket_Sequential_Order_Numbers_domain' ),
            ),
            'description'     => __( 'This is where you can add Order Numbers.', 'BeRocket_Sequential_Order_Numbers_domain' ),
            'public'          => true,
            'show_ui'         => true,
            'capability_type' => 'post',
            'publicly_queryable'  => false,
            'exclude_from_search' => true,
            'show_in_menu'        => 'berocket_account',
            'hierarchical'        => false,
            'rewrite'             => false,
            'query_var'           => false,
            'supports'            => array( 'title' ),
            'show_in_nav_menus'   => false,
        );
        $this->default_settings = array(
            'condition'     => array(),
            'prefix'        => '',
            'postfix'       => '',
            'start_number'  => '',
            'reset_number'  => '',
            'order_text'    => array(),
        );
        $this->add_meta_box('conditions', __( 'Conditions', 'BeRocket_Sequential_Order_Numbers_domain' ));
        $this->add_meta_box('number_order_settings', __( 'Order Number Settings', 'BeRocket_Sequential_Order_Numbers_domain' ));
        $this->add_meta_box('order_preview', __( 'Preview', 'BeRocket_Sequential_Order_Numbers_domain' ), false, 'side');
        parent::__construct();
        add_filter('berocket_seq_order_numbers_types', array($this, 'condition_types'));
        add_filter('brfr_berocket_seq_number_order_number_text_selector', array($this, 'number_text_selector'), 20, 4);
        add_filter('brfr_berocket_seq_number_order_time_block', array($this, 'section_time_block'), 20, 4);

        //CONDITIONS HTML
        add_filter('berocket_seq_order_numbers_type_free', array($this, 'condition_free'), 10, 3);
        add_filter('berocket_seq_order_numbers_type_payment_method', array($this, 'condition_payment_method'), 10, 3);

        //CONDITIONS CHECK
        add_filter('berocket_seq_order_numbers_check_type_free', array($this, 'condition_check_free'), 10, 3);
        add_filter('berocket_seq_order_numbers_check_type_payment_method', array($this, 'condition_check_payment_method'), 10, 3);
    }
    public function conditions($post) {
        $options = $this->get_option( $post->ID );
        if( empty($options['condition']) ) {
            $options['condition'] = array();
        }
        echo br_condition_builder($this->post_name.'[condition]', $options['condition'], array('hook_name' => $this->hook_name));
    }
    public function number_order_settings($post) {
        wp_enqueue_script( 'br_son_admin_js' );
        $options = $this->get_option( $post->ID );
        $BeRocket_Order_Numbers = BeRocket_Order_Numbers::getInstance();
        echo '<div class="br_framework_settings br_alabel_settings">';
        $BeRocket_Order_Numbers->display_admin_settings(
            array(
                'General' => array(
                    'icon' => 'cog',
                ),
            ),
            array(
                'General' => array(
                    'start_number' => array(
                        "type"     => "number",
                        "label"    => __('Start Number', 'BeRocket_Sequential_Order_Numbers_domain'),
                        "name"     => "start_number",
                        "value"    => $options['start_number'],
                        "extra"    => 'placeholder="' . __( 'Use global option', 'BeRocket_Sequential_Order_Numbers_domain' ) . '"'
                    ),
                    'reset_number' => array(
                        "type"     => "checkbox",
                        "label"    => '',
                        "label_for"=> __( 'Reset number (numbers for order will be started from this value instead latest number)', 'BeRocket_Sequential_Order_Numbers_domain' ),
                        "name"     => "reset_number",
                        "value"    => '1',
                    ),
                    'number_text'  => array(
                        "section"  => "number_text_selector",
                        "name"     => "order_text",
                        "value"    => $options["order_text"],
                    ),
                ),
            ),
            array(
                'name_for_filters' => 'berocket_seq_number_order',
                'hide_header' => true,
                'hide_form' => true,
                'hide_additional_blocks' => true,
                'hide_save_button' => true,
                'settings_name' => $this->post_name,
                'options' => $options
            )
        );
        echo '</div>';
    }
    public function number_text_selector($item, $options) {
        $html = '</tr><tr><td colspan="2">';
        $html .= BeRocket_order_numbers_text_selector::generate_selector($this->post_name.'['.$options['name'].']', (empty($options['value']) ? array() : $options['value']), array('preview' => '.br_seq_preview'));
        $html .= '</td></tr>';
        $html .= '<style>.br_seq_preview {
            word-break: break-all;
        }</style>';
        return $html;
    }
    public function order_preview($post_id) {
        echo '<p><span class="br_seq_preview"></span></p>';
    }
    public function condition_types($types) {
        $types['free'] = __( 'Is Free', 'BeRocket_Sequential_Order_Numbers_domain' );
        $types['payment_method'] = __( 'Payment Method', 'BeRocket_Sequential_Order_Numbers_domain' );
        return $types;
    }
    public function condition_free($html, $name, $options) {
        $html .= '<label><input type="checkbox" value="1" name="'.$name.'[isfree]"'.(empty($options['isfree']) ? '' : ' checked').'>' . __('Only free order', 'BeRocket_Sequential_Order_Numbers_domain') . '</label>';
        $html .= '<p>' . __('Only for paid order if not checked', 'BeRocket_Sequential_Order_Numbers_domain') . '</p>';
        return $html;
    }
    public function condition_payment_method($html, $name, $options) {
        $payment_gateways_obj     = new WC_Payment_Gateways();
        $avaible_payment_gateways = $payment_gateways_obj->get_available_payment_gateways();
        $html .= '<select name="' . $name . '[payment_method]">';
        foreach ( $avaible_payment_gateways as $slug => $payment_obj ) {
            $html .= '<option value="' . $slug . '"' . (! empty($options['payment_method']) && $options['payment_method'] == $slug ? ' selected' : '') . '>' . $payment_obj->title . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
    public function condition_check_free($show_it, $condition, $options) {
        $order = $options['order'];
        $show_it = true;
        if ( $order->get_total() > 0 ) {
            $show_it = false;
        }
        if( empty($condition['isfree']) ) {
            $show_it = ! $show_it;
        }
        return $show_it;
    }
    public function condition_check_payment_method($show_it, $condition, $options) {
        $order_id = $options['order_id'];
        $payment_method = get_post_meta($order_id, '_payment_method', true);
        $show_it = $payment_method == $condition['payment_method'];
        return $show_it;
    }
    public function wc_save_product( $post_id, $post ) {
        if( ! $this->wc_save_check($post_id, $post) ) {
            return;
        }
        if( ! empty($_POST[$this->post_name]['reset_number']) ) {
            $_POST[$this->post_name]['reset_number'] = '';
            delete_post_meta($post_id, 'br_order_number_id');
        }
        $berocket_priority = get_post_meta($post_id, 'berocket_priority', true);
        if( empty($berocket_priority) ) {
            add_post_meta($post_id, 'berocket_priority', 9999, true);
        }
        parent::wc_save_product( $post_id, $post );
    }
}
new BeRocket_Order_Numbers_custom_post();
