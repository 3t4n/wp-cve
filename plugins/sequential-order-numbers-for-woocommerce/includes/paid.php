<?php

class BeRocket_Order_Numbers_Paid extends BeRocket_plugin_variations {
    public $plugin_name = 'BeRocket_Order_Numbers';
    public $version_number = 15;
    public $info;
    public $condition_types;
    public $text_types;
    function __construct () {
        $this->condition_types = array(
            'total_price'   => __( 'Total Price', 'BeRocket_Sequential_Order_Numbers_domain' ),
            'item_count'    => __( 'Products Count', 'BeRocket_Sequential_Order_Numbers_domain' ),
            'product'       => __( 'Products', 'BeRocket_Sequential_Order_Numbers_domain' ),
            'user_role'     => __( 'User Role', 'BeRocket_Sequential_Order_Numbers_domain' ),
            'post_meta'     => __( 'Post Meta', 'BeRocket_Sequential_Order_Numbers_domain' ),
            'custom_meta'   => __( 'Custom Post Meta', 'BeRocket_Sequential_Order_Numbers_domain' ),
        );
        $this->text_types = array(
            'user_role'     => __('User Role', 'BeRocket_Sequential_Order_Numbers_domain'),
            'total_price'   => __('Total Price', 'BeRocket_Sequential_Order_Numbers_domain'),
            'item_count'    => __('Products Count', 'BeRocket_Sequential_Order_Numbers_domain'),
            'post_meta'     => __('Post Meta', 'BeRocket_Sequential_Order_Numbers_domain' ),
            'custom_meta'   => __('Custom Post Meta', 'BeRocket_Sequential_Order_Numbers_domain' ),
        );
        $this->info = array(
            'plugin_name' => 'BeRocket_Order_Numbers',
            'domain' => 'BeRocket_Sequential_Order_Numbers_domain',
        );
        parent::__construct();
        add_action( 'berocket_sequential_order_number_after_construct', array( $this, 'after_construct' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );

        //CONDITIONS HTML
        foreach( $this->condition_types as $cond_slug => $cond_name ) {
            add_filter('berocket_seq_order_numbers_type_'.$cond_slug, array($this, 'condition_'.$cond_slug), 10, 3);
            add_filter('berocket_seq_order_numbers_check_type_'.$cond_slug, array($this, 'condition_check_'.$cond_slug), 10, 3);
        }
        foreach( $this->text_types as $type_slug => $type_name ) {
            add_filter('berocket_seq_generate_number_type_'.$type_slug, array($this, 'generate_number_'.$type_slug), 10, 3);
        }
        add_filter('br_number_text_selector_types', array($this, 'number_text_selector_types'));
    }
    public function admin_init() {
        add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );
        add_filter( 'request', array( $this, 'request_query' ), 100 );
        add_filter( 'bulk_actions-edit-shop_order', array( $this, 'shop_order_bulk_actions' ), 1 );
        add_filter( 'bulk_actions-edit-shop_order', array( $this, 'shop_order_bulk_actions_after' ), 20 );
        add_action( 'manage_shop_order_posts_custom_column', array( $this, 'render_shop_order_columns' ), 1 );
        add_action( 'bulk_edit_custom_box', array( $this, 'bulk_edit' ), 10, 2 );
        add_action( 'save_post', array( $this, 'bulk_save' ), 10, 2 );
        add_action( 'save_post', array( $this, 'bulk_save' ), 10, 2 );
        add_filter( 'brfr_data_' . $this->info['plugin_name'], array( $this, 'general_tab' ), 20 );
        add_filter( 'berocket_seq_order_numbers_types', array( $this, 'number_condition_types' ), 20 );
        add_filter('br_number_text_selector_type_post_meta', array($this, 'selector_type_post_meta'), 10, 3);
        add_filter('br_number_text_selector_type_custom_meta', array($this, 'selector_type_custom_meta'), 10, 3);
        foreach( $this->text_types as $type_slug => $type_name ) {
            add_filter('br_number_text_preview_js_'.$type_slug, array($this, 'javascript_'.$type_slug));
            add_filter('br_number_text_explanation_'.$type_slug, array($this, 'explanation_'.$type_slug));
        }
    }
    public function condition_total_price($html, $name, $options) {
        $html = br_supcondition_equal($name, $options, array('equal_less' => true, 'equal_more' => true));
        $html .= '<label><input type="number" value="' . (empty($options['price']) ? '1' : $options['price']) . '" min="0" name="'.$name.'[price]">' . __('Price value', 'BeRocket_Sequential_Order_Numbers_domain') . '</label>';
        return $html;
    }
    public function condition_check_total_price($show_it, $condition, $options) {
        $order = $options['order'];
        $price = floatval($condition['price']);
        $order_total = $order->get_total();
        $order_total = floatval($order_total);
        $show_it = br_supcondition_check($order_total, $price, $condition);
        return $show_it;
    }
    public function condition_item_count($html, $name, $options) {
        $html = br_supcondition_equal($name, $options, array('equal_less' => true, 'equal_more' => true));
        $html .= '<label><input type="number" value="' . (empty($options['count']) ? '1' : $options['count']) . '" min="0" name="'.$name.'[count]">' . __('Products count', 'BeRocket_Sequential_Order_Numbers_domain') . '</label>';
        return $html;
    }
    public function condition_check_item_count($show_it, $condition, $options) {
        $order = $options['order'];
        $count = $condition['count'];
        $order_count = $order->get_item_count();
        $show_it = br_supcondition_check($order_count, $count, $condition);
        return $show_it;
    }

    public static function condition_product($html, $name, $options) {
        $def_options = array('product' => array());
        $options = array_merge($def_options, $options);
        $html .= br_supcondition_equal($name, $options) . '
        <div class="br_framework_settings">' . br_products_selector( $name . '[product]', $options['product']) . '</div>';
        return $html;
    }

    public static function condition_check_product($show_it, $condition, $options) {
        if( isset($condition['product']) && is_array($condition['product']) ) {
            $show_it = false;
            $order_items  = $options['order']->get_items();
            foreach( $order_items as $key => $item ) {
                if( in_array( $item->get_product_id(), $condition['product'] ) ) {
                    $show_it = true;
                    break;
                }
            }
            if( $condition['equal'] == 'not_equal' ) {
                $show_it = ! $show_it;
            }
        }
        return $show_it;
    }
    public static function condition_user_role($html, $name, $options) {
        $def_options = array('role' => '');
        $options = array_merge($def_options, $options);
        $html .= br_supcondition_equal($name, $options);
        $html .= '<select name="' . $name . '[role]">';
        if ( ! function_exists( 'get_editable_roles' ) ) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }
        $editable_roles = array_reverse( get_editable_roles() );
        foreach ( $editable_roles as $role => $details ) {
            $name = translate_user_role($details['name'] );
            $html .= "<option " . ($options['role'] == $role ? ' selected' : '') . " value='" . esc_attr( $role ) . "'>{$name}</option>";
        }
        $html .= '</select>';
        return $html;
    }

    public static function condition_check_user_role($show_it, $condition, $options) {
        $order_id = $options['order_id'];
        $post_author_id = get_post_field( 'post_author', $order_id );
        $user_info = get_userdata($post_author_id);
        if( ! empty($user_info) ) {
            $show_it = in_array($condition['role'], $user_info->roles);
        } else {
            $show_it = false;
        }
        if( $condition['equal'] == 'not_equal' ) {
            $show_it = ! $show_it;
        }
        return $show_it;
    }
    public function condition_post_meta($html, $name, $options) {
        $html = br_supcondition_equal($name, $options);
        $order = self::get_last_order();
        if( $order !== false ) {
            $meta = get_post_meta($order->ID);
            $options['meta_key'] = (empty($options['meta_key']) ? '' : $options['meta_key']);
            $options['meta_value'] = (empty($options['meta_value']) ? '' : $options['meta_value']);
            if( ! empty($options['meta_key']) && ! isset($meta[$options['meta_key']]) ) {
                $meta[$options['meta_key']] = '';
            }
            $html .= '<label>' . __('Post Meta Name', 'BeRocket_Sequential_Order_Numbers_domain') . '<select name="' . $name . '[meta_key]">';
            foreach($meta as $meta_key => $meta_val) {
                $html .= '<option value="' . $meta_key . '"' . ($options['meta_key'] == $meta_key ? ' selected' : '') . '>' . $meta_key . '</option>';
            }
            $html .= '</select></label>';
            $html .= '<label>' . __('Post Meta Value', 'BeRocket_Sequential_Order_Numbers_domain') . '<input type="text"  name="' . $name . '[meta_value]" value="' . $options['meta_value'] . '"></label>';
        } else {
            $type_name = __('No Orders(Use custom post meta)', 'BeRocket_Sequential_Order_Numbers_domain');
        }
        return $html;
    }
    public function condition_check_post_meta($show_it, $condition, $options) {
        $meta_val = get_post_meta($options['order_id'], $condition['meta_key'], true);
        $show_it = ( $meta_val == $condition['meta_value'] || (empty($meta_val) && empty($condition['meta_value'])) );
        if( $condition['equal'] == 'not_equal' ) {
            $show_it = ! $show_it;
        }
        return $show_it;
    }
    public function condition_custom_meta($html, $name, $options) {
        $html = br_supcondition_equal($name, $options);
        $order = self::get_last_order();
        if( $order !== false ) {
            $meta = get_post_meta($order->ID);
            $options['meta_key'] = (empty($options['meta_key']) ? '' : $options['meta_key']);
            $options['meta_value'] = (empty($options['meta_value']) ? '' : $options['meta_value']);
            if( ! empty($options['meta_key']) && ! isset($meta[$options['meta_key']]) ) {
                $meta[$options['meta_key']] = '';
            }
            $html .= '<label>' . __('Post Meta Name', 'BeRocket_Sequential_Order_Numbers_domain') . '<input type="text" name="' . $name . '[meta_key]" value="' . $options['meta_key'] . '"></label>';
            $html .= '<label>' . __('Post Meta Value', 'BeRocket_Sequential_Order_Numbers_domain') . '<input type="text"  name="' . $name . '[meta_value]" value="' . $options['meta_value'] . '"></label>';
        } else {
            $type_name = __('No Orders(Use custom post meta)', 'BeRocket_Sequential_Order_Numbers_domain');
        }
        return $html;
    }
    public function condition_check_custom_meta($show_it, $condition, $options) {
        $meta_val = get_post_meta($options['order_id'], $condition['meta_key'], true);
        $show_it = ( $meta_val == $condition['meta_value'] || (empty($meta_val) && empty($condition['meta_value'])) );
        if( $condition['equal'] == 'not_equal' ) {
            $show_it = ! $show_it;
        }
        return $show_it;
    }
    public function number_condition_types($types) {
        $types = array_merge($types, $this->condition_types);
        return $types;
    }
    public function after_construct() {
        $options = BeRocket_Order_Numbers::getInstance();
        $options = $options->get_option();
        if( ! empty($options['delete_hash_check']) ) {
            add_filter( 'gettext', array( $this, 'gettext' ), 1, 3 );
            add_filter( 'gettext_with_context', array( $this, 'gettext_with_context' ), 1, 4 );
        }
    }
    public function general_tab ( $data ) {
        $data['General'] = berocket_insert_to_array(
            $data['General'],
            'number_text',
            array(
                array(
                    "type"      => "checkbox",
                    "label"     => __("Delete hash (#) before order number", 'BeRocket_Sequential_Order_Numbers_domain'),
                    "value"     => 1,
                    "name"      => "delete_hash_check",
                    "class"     => "delete_hash_check",
                ),
            )
        );
        return $data;
    }
    public function restrict_manage_posts() {
        global $typenow;

        if ( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ) ) ) {
            ?>
            <select name="_payment_method" data-placeholder="<?php _e( 'Payment method', 'BeRocket_Sequential_Order_Numbers_domain' ); ?>">
                <option value=""><?php _e('Any Payment Method');?></option>
                <option value="free"<?php echo (! empty($_GET['_payment_method']) && $_GET['_payment_method'] == 'free' ? ' selected' : '') ?>><?php _e('Free', 'BeRocket_Sequential_Order_Numbers_domain');?></option>
                <?php
                $payment_gateways_obj     = new WC_Payment_Gateways();
                $avaible_payment_gateways = $payment_gateways_obj->get_available_payment_gateways();
                foreach ( $avaible_payment_gateways as $slug => $payment_obj ) {
                    echo '<option value="' . $slug . '"' . (! empty($_GET['_payment_method']) && $_GET['_payment_method'] == $slug ? ' selected' : '') . '>' . $payment_obj->title . '</option>';
                }
                ?>
            </select>
            <select name="_user_role" data-placeholder="<?php _e( 'User Role', 'BeRocket_Sequential_Order_Numbers_domain' ); ?>">
                <option value=""><?php _e('Any User Role');?></option>
                <?php
                if ( ! function_exists( 'get_editable_roles' ) ) {
                    require_once ABSPATH . 'wp-admin/includes/user.php';
                }
                $editable_roles = array_reverse( get_editable_roles() );
                foreach ( $editable_roles as $role => $details ) {
                    $name = translate_user_role($details['name'] );
                    echo "<option " . (! empty($_GET['_user_role']) && $_GET['_user_role'] == $role ? ' selected' : '') . " value='" . esc_attr( $role ) . "'>{$name}</option>";
                }
                ?>
            </select>
            <?php
        }
    }
    public function request_query( $vars ) {
        global $typenow, $wp_query, $wp_post_statuses;

        if ( in_array( $typenow, wc_get_order_types( 'order-meta-boxes' ) ) ) {
            if ( ! empty( $_GET['_payment_method'] ) ) {
                $payment_method = $_GET['_payment_method'];
                if( empty($vars['meta_query']) || ! is_array($vars['meta_query']) ) {
                    $vars['meta_query'] = array();
                }
                if( $payment_method == 'free' ) {
                    $payment_method = "";
                    $vars['meta_query'] = array_merge($vars['meta_query'],array(
                        'relation' => 'AND',
                        array(
                            'key'   => '_order_total',
                            'value' => '0.00',
                            'compare' => '=',
                        ),
                    ));
                }
                $vars['meta_query'] = array_merge($vars['meta_query'],array(
                    array(
                        'key'   => '_payment_method',
                        'value' => $payment_method,
                        'compare' => '=',
                    ),
                ));
            }
            if ( ! empty( $_GET['_user_role'] ) ) {
                $ids = get_users( array('role' => $_GET['_user_role'] ,'fields' => 'ID') );
                if( ! is_array($ids) || ! count($ids) ) {
                    $ids = array(0);
                }
                $vars['author__in'] = $ids;
            }
        }

        return $vars;
    }
    public function render_shop_order_columns( $column ) {
        global $post, $the_order;

        if ( empty( $the_order ) || $the_order->get_id() !== $post->ID ) {
            $the_order = wc_get_order( $post->ID );
        }

        switch ( $column ) {
            case 'order_title' :
                echo '<div class="hidden" id="inline_' . $post->ID . '"><div class="post_title">' . $the_order->get_order_number() . '</div></div>';
            case 'order_number' :
                echo '<div class="hidden" id="inline_' . $post->ID . '"><div class="post_title">' . $the_order->get_order_number() . '</div></div>';
            break;
        }
    }
    public function shop_order_bulk_actions( $actions ) {
        $actions['edit2'] = $actions['edit'];

        return $actions;
    }
    public function shop_order_bulk_actions_after( $actions ) {
        if( isset($actions['edit2']) ) {
            $actions['edit'] = $actions['edit2'];
            unset($actions['edit2']);
        }

        return $actions;
    }
    public function bulk_edit( $column_name, $post_type ) {
        global $br_order_actions;
        if ( 'shop_order' != $post_type || ! empty($br_order_actions) ) {
            return;
        }
        $br_order_actions = true;
        ?>
        <fieldset class="inline-edit-col-right" style="display: block;">
            <div id="woocommerce-fields-bulk" class="inline-edit-col">
                <label>
                    <span class="title"><?php _e( 'Order status:', 'woocommerce' ) ?></span>
                    <span class="input-text-wrap">
                        <select id="order_status" name="order_status">
                            <?php
                                echo '<option value="">' . esc_html__( '— No Change —', 'woocommerce' ) . '</option>';
                                $statuses = wc_get_order_statuses();
                                foreach ( $statuses as $status => $status_name ) {
                                    echo '<option value="' . esc_attr( $status ) . '">' . esc_html( $status_name ) . '</option>';
                                }
                            ?>
                        </select>
                    </span>
                </label>
                <label>
                    <span class="title"></span>
                    <span class="input-text-wrap">
                        <input type="checkbox" value="1" name="br_bulk_number_text_reset">
                        <?php _e( 'Restore start number text', 'BeRocket_Sequential_Order_Numbers_domain' ) ?>
                    </span>
                </label>
                <label>
                    <span class="title"><?php _e( 'Preview', 'BeRocket_Sequential_Order_Numbers_domain' ) ?></span>
                    <span class="input-text-wrap br_bulk_preview">
                        
                    </span>
                </label>
                <div class="br_framework_settings" style="clear: both;">
                    <?php 
                    echo BeRocket_order_numbers_text_selector::generate_selector('br_bulk_number_text', array(), array('preview' => '.br_bulk_preview'));
                    wp_enqueue_style( 'berocket_framework_admin_style' );
                    wp_enqueue_script( 'br_son_admin_js' );
                    ?>
                </div>

                <input type="hidden" name="woocommerce_bulk_order_edit" value="1" />
                <input type="hidden" name="woocommerce_bulk_order_edit_nonce" value="<?php echo wp_create_nonce( 'woocommerce_bulk_order_edit_nonce' ); ?>" />
            </div>
        </fieldset>
        <style>
            .bulk-edit-shop_order .inline-edit-col-right {
                display: none;
            }
        </style>
        <?php
    }
    public function bulk_save($post_id, $post) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Don't save revisions and autosaves
        if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {
            return $post_id;
        }

        // Check post type is product
        if ( 'shop_order' != $post->post_type ) {
            return $post_id;
        }

        // Check user permission
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return $post_id;
        }

        // Check nonces
        if ( ! isset( $_REQUEST['woocommerce_bulk_order_edit'] ) 
          || ! isset( $_REQUEST['woocommerce_bulk_order_edit_nonce'] )
          || ! wp_verify_nonce( $_REQUEST['woocommerce_bulk_order_edit_nonce'], 'woocommerce_bulk_order_edit_nonce' ) ) {
            return $post_id;
        }
        $BeRocket_Order_Numbers = BeRocket_Order_Numbers::getInstance();
        $options = $BeRocket_Order_Numbers->get_option();
        $order = new WC_Order( $post_id );
        if( ! empty($_REQUEST['order_status']) ) {
            $order->update_status( wc_clean( $_REQUEST['order_status'] ) );
        }
        if( ! empty($_REQUEST['br_bulk_number_text']) ) {
            $new_order_id = get_post_meta($post_id, '_sequential_order_number_id', true);
            if( empty($new_order_id) ) {
                $new_order_id = $post_id;
            }
            $additional = array('new_order_id' => $new_order_id, 'options' => $options, 'seq_options' => false, 'seq_id' => false, 'order_id' => $post_id, 'order' => $order);
            $order_number = $BeRocket_Order_Numbers->generate_number_for_order($_REQUEST['br_bulk_number_text'], $additional);

            update_post_meta($post_id, '_sequential_order_number', $order_number);
        }
        if( ! empty($_REQUEST['br_bulk_number_text_reset']) ) {
            $order_number = get_post_meta($post_id, '_start_sequential_order_number', true);
            if( ! empty($order_number) ) {
                update_post_meta($post_id, '_sequential_order_number', $order_number);
            }
        }
    }
    public function gettext( $translated, $text, $domain ) {
        if( $domain == 'woocommerce' ) {
            switch ($text) {
                case 'Order #%1$s was placed on %2$s and is currently %3$s.':
                    $translated = str_replace( '#%1$s', '%1$s', $translated );
                    break;
                case 'Payment for order #%1$d from %2$s has failed. The order was as follows:':
                    $translated = str_replace( '#%1$d', '%1$d', $translated );
                    break;
                case 'The order #%1$d from %2$s has been cancelled. The order was as follows:':
                    $translated = str_replace( '#%1$d', '%1$d', $translated );
                    break;
                case 'Order #%s':
                    $translated = str_replace( '#%s', '%s', $translated );
                    break;
                case 'Order #%1$s has been marked paid by PayPal IPN, but was previously cancelled. Admin handling required.':
                    $translated = str_replace( '#%1$s', '%1$s', $translated );
                    break;
                case 'Order #%1$s has been marked as refunded - PayPal reason code: %2$s':
                    $translated = str_replace( '#%1$s', '%1$s', $translated );
                    break;
                case 'Order #%1$s has been marked on-hold due to a reversal - PayPal reason code: %2$s':
                    $translated = str_replace( '#%1$s', '%1$s', $translated );
                    break;
                case 'Reversal cancelled for order #%s':
                    $translated = str_replace( '#%s', '%s', $translated );
                    break;
                case 'Order #%1$s has had a reversal cancelled. Please check the status of payment and update the order status accordingly here: %2$s':
                    $translated = str_replace( '#%1$s', '%1$s', $translated );
                    break;
                case '%1$s - Order #%2$s':
                    $translated = str_replace( '#%2$s', '%2$s', $translated );
                    break;
                case '%1$s units of %2$s have been backordered in order #%3$s.':
                    $translated = str_replace( '#%3$s', '%3$s', $translated );
                    break;
            }
        }

        return $translated;
    }
    public function gettext_with_context( $translated, $text, $context, $domain ) {
        if( $domain == 'woocommerce' && $context == 'hash before order number' && $text == '#' ) {
            $translated = '';
        }
        return $translated;
    }
    public function number_text_selector_types($types) {
        $types = array_merge($types, $this->text_types);
        return $types;
    }
    public function selector_type_post_meta($type_name, $name, $type_data) {
        $order = self::get_last_order();
        if( $order !== false ) {
            $meta = get_post_meta($order->ID);
            $type_data['meta_key'] = (empty($type_data['meta_key']) ? '' : $type_data['meta_key']);
            $type_data['meta_value'] = (isset($type_data['meta_value']) ? $type_data['meta_value'] : '');
            if( ! empty($type_data['meta_key']) && ! isset($meta[$type_data['meta_key']]) ) {
                $meta[$type_data['meta_key']] = '';
            }
            $type_name = '<select style="vertical-align: inherit;width:150px;" class="br_meta_key" name="' . $name . '[meta_key]">';
            foreach($meta as $meta_key => $meta_val) {
                $type_name .= '<option value="' . $meta_key . '"' . ($type_data['meta_key'] == $meta_key ? ' selected' : '') . '>' . $meta_key . '</option>';
            }
            $type_name .= '</select>';
            $type_name .= '<input style="width:50px;" type="text"  name="' . $name . '[meta_value]" value="' . $type_data['meta_value'] . '">';
        } else {
            $type_name = __('No Orders(Use custom post meta)', 'BeRocket_Sequential_Order_Numbers_domain');
        }
        return $type_name;
    }
    public function selector_type_custom_meta($type_name, $name, $type_data) {
        $type_data['meta_key'] = (empty($type_data['meta_key']) ? '' : $type_data['meta_key']);
        $type_data['meta_value'] = (isset($type_data['meta_value']) ? $type_data['meta_value'] : '');
        $type_name = '<span style="vertical-align:sub;display:inline-block;"><span style="display:block;font-size:0.8em;line-height:1em;">Custom</span><span style="display:block;font-size:0.8em;line-height:1em;">Post Meta</span></span>'
        .'<input style="width:100px;" type="text" name="' . $name . '[meta_key]" value="' . $type_data['meta_key'] . '">'
        .'<input style="width:50px;" type="text" name="' . $name . '[meta_value]" value="' . $type_data['meta_value'] . '">';
        return $type_name;
    }
    public function javascript_user_role() {
        $user_role = '';
        if ( ! function_exists( 'get_editable_roles' ) ) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }
        $editable_roles = array_reverse( get_editable_roles() );
        foreach ( $editable_roles as $role => $details ) {
            $name = translate_user_role($details['name'] );
            $user_role = $name;
            break;
        }
        $html = 'function berocket_number_text_selector_user_role($element) {
            return "' . $user_role . '";
        }';
        return $html;
    }
    public function javascript_total_price() {
        $html = 'function berocket_number_text_selector_total_price($element) {
            return "219.21";
        }';
        return $html;
    }
    public function javascript_item_count() {
        $html = 'function berocket_number_text_selector_item_count($element) {
            return "8";
        }';
        return $html;
    }
    public function javascript_post_meta() {
        $html = 'function berocket_number_text_selector_post_meta($element) {
            return "metaVal";
        }';
        return $html;
    }
    public function javascript_custom_meta() {
        $html = 'function berocket_number_text_selector_custom_meta($element) {
            return "customMetaVal";
        }';
        return $html;
    }
    public function generate_number_user_role($number, $options, $additional) {
        $post_author_id = intval(get_post_meta( $additional['order_id'], '_customer_user', true ));
        if( ! $post_author_id ) return $number;
        $user_info = get_userdata($post_author_id);
        $user_role = array_pop($user_info->roles);
        if ( ! function_exists( 'get_editable_roles' ) ) {
            require_once ABSPATH . 'wp-admin/includes/user.php';
        }
        $editable_roles = array_reverse( get_editable_roles() );
        if( ! empty($editable_roles[$user_role]) ) {
            $user_role = $editable_roles[$user_role];
            $user_role = translate_user_role($user_role['name'] );
        } else {
            $user_role = '';
        }
        return $number . $user_role;
    }
    public function generate_number_total_price($number, $options, $additional) {
        return $number . $additional['order']->get_total();
    }
    public function generate_number_item_count($number, $options, $additional) {
        return $number . $additional['order']->get_item_count();
    }
    public function generate_number_post_meta($number, $options, $additional) {
        $meta_val = get_post_meta($additional['order_id'], $options['meta_key'], true);
        if( $meta_val === false || $meta_val == '' ) {
            $meta_val = $options['meta_value'];
        }
        return $number . $meta_val;
    }
    public function generate_number_custom_meta($number, $options, $additional) {
        $meta_val = get_post_meta($additional['order_id'], $options['meta_key'], true);
        if( $meta_val === false || $meta_val == '' ) {
            $meta_val = $options['meta_value'];
        }
        return $number . $meta_val;
    }
    public static function explanation_user_role($html) {
        $html .= __('Last User Role for user that create order', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
    public static function explanation_total_price($html) {
        $html .= __('Order total price', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
    public static function explanation_item_count($html) {
        $html .= __('Order products count', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
    public static function explanation_post_meta($html) {
        $html .= __('Post Meta from order. Must be at least one order on your site.<br>Select needed post meta. In input field you can set default value if post meta not exist or empty', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
    public static function explanation_custom_meta($html) {
        $html .= __('Custom Post Meta from order. <br>First input field is post meta name, second input field is default value if post meta not exist or empty', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
    public static function get_last_order() {
        $args = array(
            'numberposts' => 1,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_type' => 'shop_order',
            'post_status' => 'any',
        );
        $recent_posts = wp_get_recent_posts( $args, OBJECT );
        if( ! empty($recent_posts) && is_array($recent_posts) && count($recent_posts) ) {
            $order = array_pop($recent_posts);
        } else {
            $order = false;
        }
        return $order;
    }
}
new BeRocket_Order_Numbers_Paid();
