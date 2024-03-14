<?php
class BeRocket_conditions_terms_cond_popup extends BeRocket_conditions {
    public static function get_conditions() {
        $conditions = parent::get_conditions();
        $conditions['condition_week_day'] = array(
            'func' => 'check_condition_week_day',
            'type' => 'week_day',
            'name' => __('Week Day', 'terms-and-conditions-popup-for-woocommerce')
        );
        $conditions['condition_user_status'] = array(
            'func' => 'check_condition_user_status',
            'type' => 'user_status',
            'name' => __('User Status', 'terms-and-conditions-popup-for-woocommerce')
        );
        $conditions['condition_user_role'] = array(
            'func' => 'check_condition_user_role',
            'type' => 'user_role',
            'name' => __('User Role', 'terms-and-conditions-popup-for-woocommerce')
        );
        $conditions['condition_shipping_zone'] = array(
            'func' => 'check_condition_shipping_zone',
            'type' => 'shipping_zone',
            'name' => __('Shipping Zone', 'terms-and-conditions-popup-for-woocommerce')
        );
        $conditions['condition_country'] = array(
            'func' => 'check_condition_country',
            'type' => 'country',
            'name' => __('Country', 'terms-and-conditions-popup-for-woocommerce')
        );
        $conditions['condition_country'] = array(
            'func' => 'check_condition_country',
            'type' => 'country',
            'name' => __('Country', 'terms-and-conditions-popup-for-woocommerce')
        );
        $conditions['condition_product_in_cart'] = array(
            'save' => 'save_condition_product',
            'func' => 'check_condition_product_in_cart',
            'type' => 'product_in_cart',
            'name' => __('Product In Cart', 'terms-and-conditions-popup-for-woocommerce')
        );
        return $conditions;
    }
    public static function condition_week_day($html, $name, $options) {
        $def_options = array('day1' => '', 'day2' => '', 'day3' => '', 'day4' => '', 'day5' => '', 'day6' => '', 'day7' => '');
        $options = array_merge($def_options, $options);
        $html = '<p>
            <label><input type="checkbox" name="'.$name.'[day1]"'.(empty($options['day1']) ? '' : ' checked').'>'.__('Monday', 'terms-and-conditions-popup-for-woocommerce').'</label>
            <label><input type="checkbox" name="'.$name.'[day2]"'.(empty($options['day2']) ? '' : ' checked').'>'.__('Tuesday', 'terms-and-conditions-popup-for-woocommerce').'</label>
            <label><input type="checkbox" name="'.$name.'[day3]"'.(empty($options['day3']) ? '' : ' checked').'>'.__('Wednesday', 'terms-and-conditions-popup-for-woocommerce').'</label>
            <label><input type="checkbox" name="'.$name.'[day4]"'.(empty($options['day4']) ? '' : ' checked').'>'.__('Thursday', 'terms-and-conditions-popup-for-woocommerce').'</label>
            <label><input type="checkbox" name="'.$name.'[day5]"'.(empty($options['day5']) ? '' : ' checked').'>'.__('Friday', 'terms-and-conditions-popup-for-woocommerce').'</label>
            <label><input type="checkbox" name="'.$name.'[day6]"'.(empty($options['day6']) ? '' : ' checked').'>'.__('Saturday', 'terms-and-conditions-popup-for-woocommerce').'</label>
            <label><input type="checkbox" name="'.$name.'[day7]"'.(empty($options['day7']) ? '' : ' checked').'>'.__('Sunday', 'terms-and-conditions-popup-for-woocommerce').'</label>
        </p>';
        return $html;
    }
    public static function check_condition_week_day($show, $condition, $additional) {
        $week_day = date('N');
        $show = ! empty($condition['day'.$week_day]);
        return $show;
    }
    public static function condition_user_status($html, $name, $options) {
        $def_options = array('not_logged_page' => '', 'customer_page' => '', 'logged_page' => '');
        $options = array_merge($def_options, $options);
        $html = '<p>
            <label><input type="checkbox" name="'.$name.'[not_logged_page]"'.(empty($options['not_logged_page']) ? '' : ' checked').'>'.__('Not Logged In', 'terms-and-conditions-popup-for-woocommerce').'</label>
            <label><input type="checkbox" name="'.$name.'[customer_page]"'.(empty($options['customer_page']) ? '' : ' checked').'>'.__('Logged In Customers', 'terms-and-conditions-popup-for-woocommerce').'</label>
            <label><input type="checkbox" name="'.$name.'[logged_page]"'.(empty($options['logged_page']) ? '' : ' checked').'>'.__('Logged In Not Customers', 'terms-and-conditions-popup-for-woocommerce').'</label>
        </p>';
        return $html;
    }
    public static function check_condition_user_status($show, $condition, $additional) {
        $orders = get_posts( array(
            'meta_key'    => '_customer_user',
            'meta_value'  => get_current_user_id(),
            'post_type'   => 'shop_order',
            'post_status' => array( 'wc-processing', 'wc-completed' ),
        ) );
        $is_logged_in = is_user_logged_in();
        if( ! $is_logged_in ) {
            $show = ! empty($condition['not_logged_page']);
        } elseif( $orders ) {
            $show = ! empty($condition['customer_page']);
        } else {
            $show = ! empty($condition['logged_page']);
        }
        return $show;
    }
    public static function condition_user_role($html, $name, $options) {
        $def_options = array('role' => '');
        $options = array_merge($def_options, $options);
        $html = static::supcondition($name, $options);
        $html .= '<select name="' . $name . '[role]">';
        $editable_roles = array_reverse( get_editable_roles() );
        foreach ( $editable_roles as $role => $details ) {
            $name = translate_user_role($details['name'] );
            $html .= "<option " . ($options['role'] == $role ? ' selected' : '') . " value='" . esc_attr( $role ) . "'>{$name}</option>";
        }
        $html .= '</select>';
        return $html;
    }
    public static function check_condition_user_role($show, $condition, $additional) {
        $post_author_id = get_current_user_id();
        $user_info = get_userdata($post_author_id);
        if( ! empty($user_info) ) {
            $show = in_array($condition['role'], $user_info->roles);
        } else {
            $show = false;
        }
        if( $condition['equal'] == 'not_equal' ) {
            $show = ! $show;
        }
        return $show;
    }
    public static function condition_shipping_zone($html, $name, $options) {
        $def_options = array('zone_id' => '');
        $options = array_merge($def_options, $options);
        $html = static::supcondition($name, $options);
        $html .= '<select name="' . $name . '[zone_id]">';
        $shipping_zone = WC_Shipping_Zones::get_zones();
        foreach ( $shipping_zone as $shipping ) {
            $html .= "<option " . ($options['zone_id'] == $shipping['id'] ? ' selected' : '') . " value='".$shipping['id']."'>".$shipping['zone_name']."</option>";
        }
        $html .= '</select>';
        return $html;
    }
    public static function check_condition_shipping_zone($show, $condition, $additional) {
        $def_options = array('zone_id' => '');
        $condition = array_merge($def_options, $condition);
        $cart_shipping = WC()->cart->get_shipping_packages();
        $cart_shipping = $cart_shipping[0];
        $shipping_zone = WC_Shipping_Zones::get_zone_matching_package($cart_shipping);
        $show = $shipping_zone->get_id() == $condition['zone_id'];
        if( $condition['equal'] == 'not_equal' ) {
            $show = ! $show;
        }
        return $show;
    }
    public static function condition_country($html, $name, $options) {
        $def_options = array('country' => array());
        $options = array_merge($def_options, $options);
        $html = static::supcondition($name, $options);
        $countries = WC()->countries->get_countries();
        $html .= '<div style="max-height:150px;overflow:auto;border:1px solid #ccc;padding: 5px;">';
        foreach ( $countries as $country_code => $country_name ) {
            $html .= '<div><label>
            <input type="checkbox" name="' . $name . '[country][]" value="' . $country_code . '"' . ( (! empty($options['country']) && is_array($options['country']) && in_array($country_code, $options['country']) ) ? ' checked' : '' ) . '>
            ' . $country_name . '
            </label></div>';
        }
        $html .= '</div>';
        return $html;
    }
    public static function check_condition_country($show, $condition, $additional) {
        $def_options = array('country' => array());
        $condition = array_merge($def_options, $condition);
        $location = WC_Geolocation::geolocate_ip();
        $location = (isset($location['country']) ? $location['country'] : '');
        $show = in_array($location, $condition['country']);
        if( $condition['equal'] == 'not_equal' ) {
            $show = ! $show;
        }
        return $show;
    }
    public static function condition_product_in_cart($html, $name, $options) {
        return self::condition_product($html, $name, $options);
    }
    public static function check_condition_product_in_cart($show, $condition, $additional) {
        $cart = WC()->cart;
        if( empty($cart) ) {
            return false;
        }
        $get_cart = $cart->get_cart();
        $product_list = array();
        foreach($get_cart as $cart_item_key => $values) {
            //INIT PRODUCT VARIABLES
            $_product = $values['data'];
            $check_additional = array();
            if ( $_product->is_type( 'variation' ) ) {
                $_product = wc_get_product($values['variation_id']);
                $check_additional['var_id'] = br_wc_get_product_id($_product);
                $check_additional['id'] = $_product->get_parent_id();
            } else {
                $_product_post = br_wc_get_product_post($_product);
                $_product_id = br_wc_get_product_id($_product);
                $check_additional['id'] = br_wc_get_product_id($_product);
            }
            $product_list[$cart_item_key] = $check_additional;
        }
        if( ! empty($product_list) ) {
            foreach($product_list as $product) {
                if( self::check_condition_product($show, $condition, array('product_id' => $product['id'])) ) {
                    return true;
                }
                if( ! empty($product['var_id']) && self::check_condition_product($show, $condition, array('product_id' => $product['var_id'])) ) {
                    return true;
                }
            }
            return false;
        }
    }
}
