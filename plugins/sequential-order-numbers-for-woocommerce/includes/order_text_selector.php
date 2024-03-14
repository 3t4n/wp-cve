<?php
class BeRocket_order_numbers_text_selector {
    public static $types;
    public function __construct() {
        //NUMBER TEXT
        add_filter('br_number_text_selector_type_input', array($this, 'selector_type_input'), 10, 3);
        add_filter('br_number_text_selector_type_date_time', array($this, 'selector_type_date_time'), 10, 3);
        add_action('init', array($this, 'init'));
        add_action('admin_init', array($this, 'admin_init'));
    }
    public function init() {
        self::$types = array(
            'id'        => __('ID', 'BeRocket_Sequential_Order_Numbers_domain'),
            'id_wc'     => __('ID WC', 'BeRocket_Sequential_Order_Numbers_domain'),
            'date_time' => __('Date Time', 'BeRocket_Sequential_Order_Numbers_domain'),
            'input'     => __('Input', 'BeRocket_Sequential_Order_Numbers_domain'),
        );
        self::$types = apply_filters('br_number_text_selector_types_before_hooks', self::$types);
        foreach(self::$types as $type_slug => $type) {
            add_filter('berocket_seq_generate_number_type_'.$type_slug, array(__CLASS__, 'generate_number_'.$type_slug), 10, 3);
        }
        self::$types = apply_filters('br_number_text_selector_types', self::$types);
    }
    public function admin_init() {
        foreach(self::$types as $type_slug => $type) {
            if( method_exists(__CLASS__, 'javascript_'.$type_slug) ) {
                add_filter('br_number_text_preview_js_'.$type_slug, array(__CLASS__, 'javascript_'.$type_slug));
            }
            if( method_exists(__CLASS__, 'explanation_'.$type_slug) ) {
                add_filter('br_number_text_explanation_'.$type_slug, array(__CLASS__, 'explanation_'.$type_slug));
            }
        }
    }
    public function selector_type_input($type_name, $name, $type_data) {
        $type_data['input'] = (empty($type_data['input']) ? '' : $type_data['input']);
        $type_name = '<input class="br_item_input" style="width:40px;" type="text" value="' . $type_data['input'] . '" name="' . $name . '[input]">';
        return $type_name;
    }
    public function selector_type_date_time($type_name, $name, $type_data) {
        $date_time = array(
            'd' => __('Day', 'BeRocket_Sequential_Order_Numbers_domain'),
            'm' => __('Month', 'BeRocket_Sequential_Order_Numbers_domain'),
            'y' => __('Year(2)', 'BeRocket_Sequential_Order_Numbers_domain'),
            'Y' => __('Year(4)', 'BeRocket_Sequential_Order_Numbers_domain'),
            'H' => __('Hour', 'BeRocket_Sequential_Order_Numbers_domain'),
            'i' => __('Minute', 'BeRocket_Sequential_Order_Numbers_domain'),
            's' => __('Second', 'BeRocket_Sequential_Order_Numbers_domain'),
        );
        $type_data['date_time'] = (empty($type_data['date_time']) ? 'd' : $type_data['date_time']);
        $type_name = '<select style="vertical-align: inherit;" class="br_date_time" name="' . $name . '[date_time]">';
        foreach($date_time as $slug => $name) {
            $type_name .= '<option value="' . $slug . '"' . ($type_data['date_time'] == $slug ? ' selected' : '') . '>' . $name . '</option>';
        }
        $type_name .= '</select>';
        return $type_name;
    }
    public static function generate_selector($name, $values, $additional = array()) {
        $types = self::$types;
        $html = '<div class="berocket_number_text_selector" data-name="'.$name.'"' . (empty($additional['preview']) ? '' : ' data-preview="' . $additional['preview'] . '"') . '>';
        $html .= '<div class="br_example" style="display: none">';
        foreach($types as $type_slug => $type) {
            $html .= '<div class="br_example_' . $type_slug . '">';
            $html .= self::type_html('%name%', $type_slug, $type, array(), $additional);
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '<div class="br_type_selector">';
        $html .= '<select class="br_type_selector_select">';
        foreach($types as $type_slug => $type) {
            $html .= '<option value="' . $type_slug . '">' . $type . '</option>';
        }
        $html .= '</select>';
        $html .= '<a href="#add_type" class="button br_type_selector_add">' . __('ADD', 'BeRocket_Sequential_Order_Numbers_domain') . '</a>';
        $html .= '</div>';
        $html .= '<div class="br_fields_explanation">';
        $first = true;
        foreach($types as $type_slug => $type) {
            $html .= '<div class="br_field_explanation br_field_' . $type_slug . '"' . ($first ? '' : ' style="display: none;"') . '>';
            $html .= apply_filters('br_number_text_explanation_'.$type_slug, '');
            $html .= '</div>';
            $first = false;
        }
        $html .= '</div>';
        $html .= '<div class="br_added_types">';
        $i = 1;
        foreach($values as $type) {
            if( ! empty( $types[$type['type']] ) ) {
                $html .= self::type_html($name.'['.$i.']', $type['type'], $types[$type['type']], $type, $additional);
                $i++;
            }
        }
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<style>
            .berocket_number_text_selector {
                font-size: 16px;
            }
            .berocket_number_text_selector .br_added_types .br_type {
                border: 1px solid #777;
                padding: 3px;
                display: inline-block;
                height: 30px;
                line-height: 30px;
                margin-bottom: 5px;
                background-color: white;
                border-right: 0;
                vertical-align: top;
            }
            .berocket_number_text_selector .br_added_types .br_type:last-child {
                border-right:1px solid #777;
            }
            .berocket_number_text_selector .br_added_types .br_type .fa-times {
                margin-left: 3px;
                cursor: pointer;
            }
            .berocket_number_text_selector .br_added_types .br_type .fa-bars {
                cursor: move;
            }
            .berocket_number_text_selector .br_added_types .br_type .fa-times:hover {
                color: red;
            }
            .berocket_number_text_selector .br_type_selector_select {
                max-width: 100%;
                margin-top: 30px;
                margin-bottom: 20px;
                height: 42px;
            }
            .berocket_number_text_selector .br_added_types .berocket_sortable_space {
                display: inline-block;
                border: 2px dashed #777;
                width: 70px;
                height: 30px;
                padding: 2px;
            }
            .berocket_number_text_selector .br_fields_explanation {
                padding-bottom: 0.5em;
            }
        </style>';
        $html .= '<script>';
        foreach($types as $type_slug => $type) {
            $html .= apply_filters('br_number_text_preview_js_'.$type_slug, '');
        }
        $html .= '</script>';
        return $html;
    }
    public static function type_html($name, $type_slug, $type_name, $type_data = array(), $additional = array()) {
        $html = '<div class="br_type br_type_' . $type_slug . '" data-name="' . $name . '">';
        $html .= '<i class="fa fa-bars"></i>';
        $html .= '<input type="hidden" class="br_item_type" name="' . $name . '[type]" value="' . $type_slug . '">';
        $type_html = apply_filters('br_number_text_selector_type_'.$type_slug, $type_name, $name, $type_data, $additional);
        $html .= $type_html;
        $html .= '<i class="fa fa-times"></i>';
        $html .= '</div>';
        return $html;
    }
    public static function javascript_id() {
        $html = 'function berocket_number_text_selector_id($element) {
            return "12345";
        }';
        return $html;
    }
    public static function javascript_id_wc() {
        $html = 'function berocket_number_text_selector_id_wc($element) {
            return "12345";
        }';
        return $html;
    }
    public static function javascript_date_time() {
        $html = 'function berocket_number_text_selector_date_time($element) {
            var date_time_name = $element.find(".br_date_time").val();
            if( date_time_name == "d" ) {
                date_time_name = "'.date('d').'";
            } else if( date_time_name == "m" ) {
                date_time_name = "'.date('m').'";
            } else if( date_time_name == "y" ) {
                date_time_name = "'.date('y').'";
            } else if( date_time_name == "Y" ) {
                date_time_name = "'.date('Y').'";
            } else if( date_time_name == "H" ) {
                date_time_name = "'.date('H').'";
            } else if( date_time_name == "i" ) {
                date_time_name = "'.date('i').'";
            } else if( date_time_name == "s" ) {
                date_time_name = "'.date('s').'";
            }
            return date_time_name;
        }';
        return $html;
    }
    public static function javascript_input() {
        $html = 'function berocket_number_text_selector_input($element) {
            return $element.find(".br_item_input").val();
        }';
        return $html;
    }
    public static function generate_number_id($number, $options, $additional) {
        return $number . $additional['new_order_id'];
    }
    public static function generate_number_id_wc($number, $options, $additional) {
        return $number . $additional['order_id'];
    }
    public static function generate_number_date_time($number, $options, $additional) {
        return $number . get_the_date((empty($options['date_time']) ? 'd' : $options['date_time']), $additional['order_id']);
    }
    public static function generate_number_input($number, $options, $additional) {
        return $number . (empty($options['input']) ? '' : $options['input']);
    }
    public static function explanation_id($html) {
        $html .= __('ID generated from option <strong>Start Number</strong><br>Uses default WooCommerce ID if option not set', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
    public static function explanation_id_wc($html) {
        $html .= __('Default WooCommerce Order ID', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
    public static function explanation_date_time($html) {
        $html .= __('Day, Month, Year, Hour, Minute or Second from order date', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
    public static function explanation_input($html) {
        $html .= __('Any custom text that you need', 'BeRocket_Sequential_Order_Numbers_domain');
        return $html;
    }
}
new BeRocket_order_numbers_text_selector();
