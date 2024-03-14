<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('shop_ready_resize')) {

    /**
     * Resize any image from
     *
     * @param url  $url image 
     * @param mix $width image width size false for auto
     * @param mix $height image height size false for auto
     * @param boolen  $crop 
     * @return url string path
     * @version 1.0 very beginning
     * 
     */
    function shop_ready_resize($url, $width = false, $height = false, $crop = false)
    {

        $shop_ready_resize = \Shop_Ready\helpers\Resize::getInstance();
        $response = $shop_ready_resize->process($url, $width, $height, $crop);

        return (!is_wp_error($response) && !empty($response['src'])) ? $response['src'] : $url;
    }
}

if (!function_exists('shop_ready_is_valid_domain_name')) {
    /**
     * Validate Domain Name
     *
     * @param string $domain.com 
     *
     * @return bool
     */
    function shop_ready_is_valid_domain_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) // valid chars check
            && preg_match("/^.{1,253}$/", $domain_name) // overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)); // length of each label
    }
}


/**
 * Safe load variables from an file
 * Use this function to not include files directly and to not give access to current context variables (like $this)
 *
 * @param string $file_path
 * @param array $_extract_variables Extract these from file array('variable_name' => 'default_value')
 * @param array $_set_variables Set these to be available in file (like variables in view)
 *
 * @return array
 */
function shop_ready_get_variables_from_file($file_path, array $_extract_variables, array $_set_variables = array())
{
    extract($_set_variables, EXTR_REFS);
    unset($_set_variables);

    require $file_path;

    foreach ($_extract_variables as $variable_name => $default_value) {
        if (isset($$variable_name)) {
            $_extract_variables[$variable_name] = $$variable_name;
        }
    }

    return $_extract_variables;
}

/**
 * Safe render a view and return html
 * In view will be accessible only passed variables
 * Use this function to not include files directly and to not give access to current context variables (like $this)
 *
 * @param string $file_path
 * @param array $view_variables
 * @param bool $return In some cases, for memory saving reasons, you can disable the use of output buffering
 *
 * @return string HTML
 */
function shop_ready_render_view($file_path, $view_variables = array(), $return = true)
{

    if (!is_file($file_path)) {
        return '';
    }

    extract($view_variables, EXTR_REFS);
    unset($view_variables);

    if ($return) {
        ob_start();
        require $file_path;

        return ob_get_clean();
    } else {
        require $file_path;
    }

    return '';
}

/**
 * Generate html tag
 *
 * @param string $tag Tag name
 * @param array $attr Tag attributes
 * @param bool|string $end Append closing tag. Also accepts body content
 *
 * @return string The tag's html
 */
function shop_ready_html_tag($tag, $attr = array(), $end = false)
{
    $html = '<' . $tag . ' ' . shop_ready_attr_to_html($attr);

    if ($end === true) {
        # <script></script>
        $html .= '></' . $tag . '>';
    } else if ($end === false) {
        # <br/>
        $html .= '/>';
    } else {
        # <div>content</div>
        $html .= '>' . $end . '</' . $tag . '>';
    }

    return $html;
}

/**
 * Convert to Unix style directory separators
 *  @param string $path url
 */
function shop_ready_fix_path($path)
{

    $windows_network_path = isset($_SERVER['windir']) && in_array(
        substr($path, 0, 2),
        array('//', '\\\\'),
        true
    );
    $fixed_path = untrailingslashit(str_replace(array('//', '\\'), array('/', '/'), $path));

    if (empty($fixed_path) && !empty($path)) {
        $fixed_path = '/';
    }

    if ($windows_network_path) {
        $fixed_path = '//' . ltrim($fixed_path, '/');
    }

    return $fixed_path;
}


/**
 * Strip slashes from values, and from keys if magic_quotes_gpc = On
 */
function shop_ready_stripslashes_deep_keys($value)
{
    static $magic_quotes = null;
    if ($magic_quotes === null) {
        $magic_quotes = false; //https://www.php.net/manual/en/function.get-magic-quotes-gpc.php - always returns FALSE as of PHP 5.4.0. false fixes https://github.com/ThemeFuse/Unyson/issues/3915
    }

    if (is_array($value)) {
        if ($magic_quotes) {
            $new_value = array();
            foreach ($value as $key => $val) {
                $new_value[is_string($key) ? stripslashes($key) : $key] = shop_ready_stripslashes_deep_keys($val);
            }
            $value = $new_value;
            unset($new_value);
        } else {
            $value = array_map('shop_ready_stripslashes_deep_keys', $value);
        }
    } elseif (is_object($value)) {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data) {
            $value->{$key} = shop_ready_stripslashes_deep_keys($data);
        }
    } elseif (is_string($value)) {
        $value = stripslashes($value);
    }

    return $value;
}

/**
 * Add slashes to values, and to keys if magic_quotes_gpc = On
 */
function shop_ready_addslashes_deep_keys($value)
{
    static $magic_quotes = null;
    if ($magic_quotes === null) {
        $magic_quotes = get_magic_quotes_gpc();
    }

    if (is_array($value)) {
        if ($magic_quotes) {
            $new_value = array();
            foreach ($value as $key => $value) {
                $new_value[is_string($key) ? addslashes($key) : $key] = shop_ready_addslashes_deep_keys($value);
            }
            $value = $new_value;
            unset($new_value);
        } else {
            $value = array_map('shop_ready_addslashes_deep_keys', $value);
        }
    } elseif (is_object($value)) {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data) {
            $value->{$key} = shop_ready_addslashes_deep_keys($data);
        }
    } elseif (is_string($value)) {
        $value = addslashes($value);
    }

    return $value;
}

/**
 * Use this id do not want to enter every time same last two parameters
 * Info: Cannot use default parameters because in php 5.2 encoding is not UTF-8 by default
 *
 * @param string $string
 *
 * @return string
 */
function shop_ready_htmlspecialchars($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Generate attributes string for html tag
 *
 * @param array $attr_array array('href' => '/', 'title' => 'Test')
 *
 * @return string 'href="/" title="Test"'
 */
function shop_ready_attr_to_html(array $attr_array)
{
    $html_attr = '';

    foreach ($attr_array as $attr_name => $attr_val) {
        if ($attr_val === false) {
            continue;
        }

        $html_attr .= $attr_name . '="' . shop_ready_htmlspecialchars($attr_val) . '" ';
    }

    return $html_attr;
}

/**
 * Generate attributes string for shortcode tag
 *
 * @param array $attr_array array('col' => '3', 'title' => 'Test')
 *	$att = [
 *		'column' => '3',
 *		'ids' => [12,344,44]
 *	];
 *	$array = shop_ready_attr_to_shortcode($att);
 */
function shop_ready_attr_to_shortcode(array $attr_array)
{
    $shortcode_attr = '';

    foreach ($attr_array as $attr_name => $attr_val) {

        if ($attr_val === false) {
            continue;
        }

        if (is_array($attr_val)) {
            $shortcode_attr .= $attr_name . '="' . shop_ready_convert_arr($attr_val) . '" ';
        } else {
            $shortcode_attr .= $attr_name . '="' . sanitize_text_field($attr_val) . '" ';
        }

    }

    return $shortcode_attr;
}

if (!function_exists('shop_ready_convert_arr')) {
    /**
     * Generate attributes string for shortcode tag
     * nested array not allowed
     * @param array $attr_array array('products','Test')
     *
     */
    function shop_ready_convert_arr($attr)
    {

        $return_arr = '[';
        $store_value = '';

        foreach ($attr as $value) {

            if (!is_array($value)) {
                $store_value .= $value . ',';
            }

        }

        $store_value = trim($store_value, ',');
        $return_arr .= $store_value . ']';

        return $return_arr;
    }
}


if (!function_exists('shop_ready_is_account_dashboard')) {

    function shop_ready_is_account_dashboard()
    {
        return is_user_logged_in() && is_account_page() && !is_wc_endpoint_url();
    }

}

if (!function_exists('shop_ready_is_account_order')) {

    function shop_ready_is_account_order()
    {
        return is_user_logged_in() && is_account_page() && is_wc_endpoint_url('orders');
    }

}

if (!function_exists('shop_ready_is_account_order_view')) {

    function shop_ready_is_account_order_view()
    {
        return is_user_logged_in() && is_account_page() && is_wc_endpoint_url('view-order');
    }

}


if (!function_exists('shop_ready_is_account_custom_end_point')) {

    function shop_ready_is_account_custom_end_point($slug)
    {
        return is_user_logged_in() && is_account_page() && is_wc_endpoint_url($slug);
    }

}

if (!function_exists('shop_ready_is_account_downloads')) {

    function shop_ready_is_account_downloads()
    {
        return is_user_logged_in() && is_account_page() && is_wc_endpoint_url('downloads');
    }

}

if (!function_exists('shop_ready_is_account_edit_address')) {

    function shop_ready_is_account_edit_address()
    {
        return is_user_logged_in() && is_account_page() && is_wc_endpoint_url('edit-address');
    }

}
// Edit Account End point
if (!function_exists('shop_ready_is_account_edit_account')) {

    function shop_ready_is_account_edit_account()
    {
        return is_user_logged_in() && is_account_page() && is_wc_endpoint_url('edit-account');
    }

}

// Login Register Account End point
if (!function_exists('shop_ready_is_account_login')) {

    function shop_ready_is_account_login()
    {
        return !is_user_logged_in() && is_account_page();
    }

}

if (!function_exists('shop_ready_heading_camelize')) {
    function shop_ready_heading_camelize($input, $separator = '_')
    {
        return strtolower(str_replace($separator, '', ucwords($input, $separator)));
    }
}



if (!function_exists('shop_ready_current_theme_supported_post_format')) {
    /* 
     * Current Theme Supported post format
     */
    function shop_ready_current_theme_supported_post_format()
    {

        static $list = [];

        if (!count($list)) {

            $post_formats = get_theme_support('post-formats');

            if (isset($post_formats[0])) {
                $post_formats = $post_formats[0];
            } else {
                return $list;
            }

            foreach ($post_formats as $format) {
                $list['post-format-' . $format] = $format;
            }

        }

        return $list;

    }
}


if (!function_exists('shop_ready_get_post_tags')) {

    function shop_ready_get_post_tags($tax = 'product_tag')
    {

        static $list = [];

        if (!count($list)) {
            $categories = get_terms(
                $tax,
                array(
                    'orderby' => 'name',
                    'order' => 'DESC',
                    'hide_empty' => false,
                    'number' => 300

                )
            );

            foreach ($categories as $category) {
                $list[$category->term_id] = $category->name;
            }

        }

        return $list;
    }
}

if (!function_exists('shop_ready_get_post_cat')) {

    function shop_ready_get_post_cat($tax = 'product_cat')
    {

        static $clist = [];

        if (!count($clist)) {
            $categories = get_terms(
                $tax,
                array(
                    'orderby' => 'name',
                    'order' => 'DESC',
                    'hide_empty' => false,
                    'number' => 600

                )
            );

            foreach ($categories as $category) {
                $clist[$category->term_id] = $category->name;
            }

        }

        return $clist;
    }
}

if (!function_exists('shop_ready_get_post_author')) {

    function shop_ready_get_post_author()
    {
        static $list = [];

        if (!count($list)) {
            $authors = get_users(
                array(
                    'fields' => array('display_name', 'ID')
                )
            );

            foreach ($authors as $author) {
                $list[$author->ID] = $author->display_name;
            }

        }

        return $list;
    }

}


if (!function_exists('shop_ready_get_posts')) {

    /**  Get Products
     * @since 1.0
     * parameter elementor widget setttings 
     * @return post array
     */
    function shop_ready_get_posts($type = 'post')
    {
        static $list = [];

        if (!count($list)) {
            $posts = get_posts(
                [
                    'numberposts' => -1,
                    'post_status' => 'publish',
                    'post_type' => $type,
                ]
            );

            foreach ($posts as $post) {
                $list[$post->ID] = $post->post_title;
            }

        }

        return $list;
    }

}


if (!function_exists('shop_ready_widgets_slider_controls')) {

    /**  elementor Slider control 
     * @simce 1.0
     * parameter elementor widget setttings 
     * @return array
     */
    function shop_ready_widgets_slider_controls($settings)
    {

        $return_controls = [];

        $slider_controls = [
            'slider_items',
            'slider_items_tablet',
            'slider_items_mobile',
            'slider_autoplay',
            'slider_autoplay_hover_pause',
            'slider_autoplay_timeout',
            'slider_smart_speed',
            'slider_dot_nav_show',
            'slider_nav_show',
            'slider_margin',
            'slider_loop'
        ];


        foreach ($settings as $key => $item) {

            if (in_array($key, $slider_controls)) {
                $return_controls[$key] = $item;
            }

        }

        return $return_controls;
    }

}


/** 
 * Admin Dashboard Notice plugin check
 * @since 1.0
 * parameter plugin path
 * @return url string
 */
function shop_ready_plugin_activation_link_url($plugin = 'woocommerce/woocommerce.php')
{
    $activateUrl = sprintf(admin_url('plugins.php?action=activate&plugin=%s&plugin_status=all&paged=1&s'), $plugin);
    // change the plugin request to the plugin to pass the nonce check
    $_REQUEST['plugin'] = $plugin;
    $activateUrl = wp_nonce_url($activateUrl, 'activate-plugin_' . $plugin);

    return $activateUrl;
}

if (!function_exists('shop_ready_get_current_user_role')) {

    function shop_ready_get_current_user_role()
    {

        if (is_user_logged_in()) { // check if there is a logged in user 

            $user = wp_get_current_user(); // getting & setting the current user 
            $roles = (array) $user->roles; // obtaining the role 
            return $roles; // return the role for the current user 
        } else {
            return array(); // if there is no logged in user return empty array  
        }
    }

}


if (!function_exists('shop_ready_get_editable_roles')) {

    /************************************ ******
     *
     ** user role
     *** @since 1.0
     **** parameter boolen user role 
     *** @return user array
     **
     ******************************************** *****/
    function shop_ready_get_editable_roles($slug = false)
    {

        global $wp_roles;

        $all_roles = $wp_roles->roles;

        $editable_roles = apply_filters('editable_roles', $all_roles);

        // return only roles array 
        if ($slug) {

            $role_list = [];

            foreach ($editable_roles as $key => $item) {

                $role_list[$key] = $item['name'];

            }

            return $role_list;
        }

        return $editable_roles;
    }
}

if (!function_exists('shop_ready_get_post_templates')) {

    function shop_ready_get_post_templates()
    {

        $templates = wp_get_theme()->get_post_templates();
        $return_data = [];
        $return_data[-1]['label'] = '--------';
        $return_data[-1]['options'] = [
            '-1' => esc_html__('None', 'shopready-elementor-addon')
        ];

        foreach ($templates as $post_type => $template) {

            $template_option = [];

            foreach ($template as $key => $item_name) {
                $template_option[$key] = $item_name;
            }

            foreach (shop_ready_get_page_templates() as $p_key => $page_nem) {
                $template_option[$p_key] = $page_nem;
            }

            $slug = str_replace(' ', '-', $post_type);
            $return_data[$slug]['label'] = $post_type;
            $return_data[$slug]['options'] = $template_option;

        }

        return $return_data;
    }

}

if (!function_exists('shop_ready_get_page_templates')) {

    function shop_ready_get_page_templates()
    {

        $templates = wp_get_theme()->get_page_templates();
        $return_data = [];
        foreach ($templates as $template_name => $template_filename) {
            $return_data[$template_name] = $template_filename;
        }

        return $return_data;
    }
}

function shop_ready_get_transform_options($options = [], $key = false)
{

    if (!is_array($options) || $key == false) {
        return $options;
    }

    $db_option = get_option($key);

    $return_options = $options;

    foreach ($options as $key => $value) {

        if (isset($db_option[$key])) {
            $return_options[$key]['default'] = 1;
        } else {
            $return_options[$key]['default'] = 0;
        }

    }

    return $return_options;
}

/**
 * Config File Write 
 * @param Param2 array config
 * @param param1 string  file path
 * @since 1.0
 * @qumodosoft
 */
function shop_ready_core_config_file_write($file_path, $content_array)
{

    global $wp_filesystem;

    $errors = new \WP_Error();

    if (!is_array($content_array)) {

        $errors->add(1, esc_html__('Content should be array'));
    }

    if (!file_exists($file_path)) {
        $errors->add(2, esc_html__('File Path should be valid'));
    }

    if ($errors->get_error_code()) {

        return $errors;
    }

    if (empty($wp_filesystem)) {
        require_once(ABSPATH . '/wp-admin/includes/file.php');
        WP_Filesystem();
    }

    $data = var_export($content_array, 1);
    $file_contents = "<?php\n return $data; ";

    if (!$wp_filesystem->put_contents($file_path, $file_contents, FS_CHMOD_FILE)) {
        $errors->add(503, esc_html__('File can not write'));
        return $errors;
    }

    return true;

}


if (!function_exists('shop_ready_wc_is_endpoint')) {

    /**
     * WooCommerce Notice Shortcode
     * WC Endpont Validation
     * @param endpoint_name string
     * @return bool
     */
    function shop_ready_wc_is_endpoint($endpoint_name)
    {

        if (is_wc_endpoint_url() && ($endpoint_name == WC()->query->get_current_endpoint())) {
            return true;
        }

        return true;
    }
}


if (!function_exists('shop_ready_wc_get_current_endpoint')) {

    /**
     * WC Endpont Validation
     * @param endpoint_name string
     */
    function shop_ready_wc_get_current_endpoint()
    {

        return WC()->query->get_current_endpoint();
    }
}

if (!function_exists('shop_ready_is_checkout_endpoint')) {

    /**
     * WC Checkout Endpont Validation
     * @return bool
     */
    function shop_ready_is_checkout_endpoint()
    {
        return is_wc_endpoint_url('order-pay') || is_wc_endpoint_url('order-received');
    }
}

if (!function_exists('shop_ready_locate_tpl')) {

    /**
     * Locate template.
     *
     * Locate the called template.
     * Search Order:
     * 1. /themes/theme/woo-ready/$template_name
     * 2. /templates/$template_name.
     * @param   string  $template_name          Template to load.
     * @param   string  $string $template_path  Path to templates.
     * @param   string  $default_path           Default path to template files.
     * @return  string                          Path to the template file.
     */
    function shop_ready_locate_tpl($template_name, $template_path = '', $default_path = '')
    {


        if (!$template_path):
            $template_path = 'shop-ready/';
        endif;


        if (!$default_path):
            $default_path = SHOP_READY_DIR_PATH . 'templates/';
        endif;


        $template = locate_template(
            array(
                $template_path . $template_name,
                $template_name
            )
        );


        if (!$template):
            $template = $default_path . $template_name;
        endif;

        return apply_filters('shop_ready_locate_tpl', $template, $template_name, $template_path, $default_path);

    }
}

if (!function_exists('shop_ready_get_template')) {

    /**
     * Search for the template and include the file.
     * @param string  $template_name          Template to load.
     * @param array   $args                   Args passed for the template file.
     * @param string  $string $template_path  Path to templates.
     * @param string  $default_path           Default path to template files.
     */
    function shop_ready_get_template($template_name, $args = array(), $tempate_path = '', $default_path = '')
    {

        if (is_array($args) && isset($args)):
            extract($args);
        endif;

        $template_file = shop_ready_locate_tpl($template_name, $tempate_path, $default_path);

        if (!file_exists($template_file)):
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_file), '1.0.0');
            return;
        endif;

        include $template_file;

    }
}

/**
 * Checkout Address Fields
 * @since 1.0
 * @param string type ex: billing | shipping
 * @param string item col ex: label | required | priority | autocomplete | class as array
 * @defs woocommerce
 * @return array
 */
function shop_ready_get_wc_checkout_address_fields($type = 'billing', $col = 'label')
{

    $fields_with_label = [];

    try {

        $checkout = WC()->checkout;
        if (isset($checkout->checkout_fields)) {

            if (isset($checkout->checkout_fields[$type]) && is_array($checkout->checkout_fields[$type])) {
                foreach ($checkout->checkout_fields[$type] as $key => $item) {
                    $fields_with_label[$key] = $item[$col];

                }

                return $fields_with_label;
            }

        }

    } catch (Exception $e) {
        wc_add_notice(esc_html__('Checkout not Init', 'shopready-elementor-addon'));
    }

    return $fields_with_label;
}


if (!function_exists('shop_ready_get_default_shipping_address')) {

    function shop_ready_get_default_shipping_address()
    {

        return [

            'shipping_first_name' => esc_html__('First name', 'shopready-elementor-addon'),
            'shipping_last_name' => esc_html__('Last name', 'shopready-elementor-addon'),
            'shipping_company' => esc_html__('Company name', 'shopready-elementor-addon'),
            'shipping_country' => esc_html__('Country / Region', 'shopready-elementor-addon'),
            'shipping_address_1' => esc_html__('Street address', 'shopready-elementor-addon'),
            'shipping_address_2' => esc_html__('Apartment, suite, unit, etc.', 'shopready-elementor-addon'),
            'shipping_city' => esc_html__('Town / City', 'shopready-elementor-addon'),
            'shipping_state' => esc_html__('State', 'shopready-elementor-addon'),
            'shipping_postcode' => esc_html__('ZIP', 'shopready-elementor-addon')

        ];
    }

}

if (!function_exists('shop_ready_get_default_billing_address')) {

    function shop_ready_get_default_billing_address()
    {

        return [

            'billing_first_name' => esc_html__('First name', 'shopready-elementor-addon'),
            'billing_last_name' => esc_html__('Last name', 'shopready-elementor-addon'),
            'billing_company' => esc_html__('Company name', 'shopready-elementor-addon'),
            'billing_country' => esc_html__('Country / Region', 'shopready-elementor-addon'),
            'billing_address_1' => esc_html__('Street address', 'shopready-elementor-addon'),
            'billing_address_2' => esc_html__('Apartment, suite, unit, etc.', 'shopready-elementor-addon'),
            'billing_city' => esc_html__('Town / City', 'shopready-elementor-addon'),
            'billing_state' => esc_html__('District', 'shopready-elementor-addon'),
            'billing_postcode' => esc_html__('Postcode / ZIP', 'shopready-elementor-addon'),
            'billing_phone' => esc_html__('Phone', 'shopready-elementor-addon'),
            'billing_email' => esc_html__('Email address', 'shopready-elementor-addon'),

        ];
    }

}

if (!function_exists('shop_ready_html_tags_options')) {
    function shop_ready_html_tags_options()
    {

        return apply_filters('shop_ready_html_tags_options', [

            'h1' => 'H1',
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
            'div' => 'DIV',
            'p' => 'p',
            'span' => 'span',
            'b' => 'span',
            'b' => 'B',
            'strong' => 'Strong',
            'pre' => 'Strong',

        ]);
    }
}


if (!function_exists('shop_ready_get_latest_products_id')) {

    /**
     * Get WooCommerce Latest Product
     * @arg $count default 1
     * @return array
     */
    function shop_ready_get_latest_products_id($count = 1)
    {

        $key = 'wready_get_latest_products_id_' . $count;

        $product_object = wp_cache_get($key);
        $product_object_array = array();

        if (false === $product_object) {
            $args = array(
                'post_type' => 'product',
                'stock' => 1,
                'numberposts' => $count,
                'orderby' => 'date',
                'order' => 'DESC',

            );

            if (isset($_GET['tpl_type']) && isset($_GET['sr_tpl'])) {

                $arg['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_type',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($_GET['tpl_type'])
                    ),
                );

            }

            $products = get_posts($args);

            foreach ($products as $product) {
                $product_object_array[$product->ID] = $product->post_title;
            }

            wp_cache_set($key, $product_object_array);
        }

        return $product_object;

    }

}

if (!function_exists('shop_ready_get_single_product_key')) {
    /**
     * Usagte in elementor control
     * @return string product id 
     */
    function shop_ready_get_single_product_key()
    {

        $variation = null;

        if (isset($_GET['sr_single_product_id']) && is_numeric($_GET['sr_single_product_id'])) {

            $GLOBALS['product'] = wc_get_product(sanitize_key($_GET['sr_single_product_id']));
            WC()->session->set('sr_single_product_id', sanitize_key($_GET['sr_single_product_id']));
            $variation = sanitize_key($_GET['sr_single_product_id']);
        }

        if (isset($_GET['tpl_type']) && isset($_GET['sr_tpl']) && $_GET['tpl_type'] == 'simple') {
            $variation = shop_ready_get_single_product('simple');
        }

        if (isset($_GET['tpl_type']) && isset($_GET['sr_tpl']) && $_GET['tpl_type'] == 'variable') {
            $variation = shop_ready_get_single_product('variable');
        }

        if (isset($_GET['tpl_type']) && isset($_GET['sr_tpl']) && $_GET['tpl_type'] == 'grouped') {
            $variation = shop_ready_get_single_product('grouped');
        }

        if (is_null($variation) && !is_numeric($variation)) {

            $product_object = shop_ready_get_latest_products_id(1);

            if (!is_array($product_object)) {
                return '';
            }

            $variation = key($product_object);

        }


        return $variation;

    }
}

if (!function_exists('shop_ready_get_single_product')) {
    /**
     * Usagte in elementor control
     * @return string product id 
     */
    function shop_ready_get_single_product($type)
    {

        $product_object = shop_ready_get_latest_product_by_type('simple');
        if (!is_array($product_object)) {
            return null;
        }

        return key($product_object);
    }
}

if (!function_exists('shop_ready_get_latest_product_by_type')) {

    /**
     * Get WooCommerce Latest Product
     * @arg $count default 1
     * @return array
     */
    function shop_ready_get_latest_product_by_type($type = 'simple')
    {

        $key = 'shop_ready_get_latest_product_type_' . $type;

        $product_object = wp_cache_get($key);
        if (false === $product_object) {
            $args = array(
                'post_type' => 'product',
                'numberposts' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_type',
                        'field' => 'slug',
                        'terms' => $type
                    ),
                ),

            );

            $products = get_posts($args);

            foreach ($products as $product) {
                $product_object[$product->ID] = $product->post_title;
            }

            wp_cache_set($key, $product_object);
        }

        return $product_object;

    }

}

if (!function_exists('shop_ready_get_page_list')) {

    function shop_ready_get_page_list()
    {

        static $return_pages = [];

        if (empty($return_pages)) {

            $pages = get_pages(
                array(
                    'parent' => 0,
                )
            );

            foreach ($pages as $item) {

                $return_pages[$item->ID] = $item->post_name;
            }
        }
        return $return_pages;

    }
}



if (!function_exists('shop_ready_get_dashboard_url')) {
    /**
     * Dashboard page url
     * @param page slug
     * @return url string
     */
    function shop_ready_get_dashboard_url($slug = null)
    {

        if (is_null($slug)) {

            return admin_url('admin.php?page=' . SHOP_READY_SETTING_PATH);
        }

        return admin_url('admin.php?page=' . $slug);
    }
}


/**
 * Get all elementor page templates
 * @param  null  $type
 * @return array
 */
if (!function_exists('shop_ready_get_elementor_templates')) {
    function shop_ready_get_elementor_templates($type = null)
    {
        $options = [];

        if ($type) {
            $args = [
                'post_type' => 'elementor_library',
                'posts_per_page' => -1,
            ];
            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];

            $page_templates = get_posts($args);

            if (!empty($page_templates) && !is_wp_error($page_templates)) {
                foreach ($page_templates as $post) {
                    $options[$post->ID] = $post->post_title;
                }
            }
        } else {
            $options = shop_ready_get_query_post_list('elementor_library');
        }

        return $options;
    }
}


if (!function_exists('shop_ready_get_query_post_list')) {

    function shop_ready_get_query_post_list($post_type = 'any', $limit = -1, $search = '')
    {

        global $wpdb;
        $where = '';
        $data = [];

        if (-1 == $limit) {
            $limit = '';
        } elseif (0 == $limit) {
            $limit = "limit 0,1";
        } else {
            $limit = $wpdb->prepare(" limit 0,%d", esc_sql($limit));
        }

        if ('any' === $post_type) {
            $in_search_post_types = get_post_types(['exclude_from_search' => false]);
            if (empty($in_search_post_types)) {
                $where .= ' AND 1=0 ';
            } else {
                $where .= " AND {$wpdb->posts}.post_type IN ('" . join(
                    "', '",
                    array_map('esc_sql', $in_search_post_types)
                ) . "')";
            }
        } elseif (!empty($post_type)) {
            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_type = %s", esc_sql($post_type));
        }

        if (!empty($search)) {
            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", '%' . esc_sql($search) . '%');
        }

        $query = "select post_title,ID  from $wpdb->posts where post_status = 'publish' $where $limit";
        $results = $wpdb->get_results($query);
        if (!empty($results)) {
            foreach ($results as $row) {
                $data[$row->ID] = $row->post_title;
            }
        }
        return $data;
    }

}



if (!function_exists('shop_ready_get_product_category_name_from_id')) {

    function shop_ready_get_product_category_name_from_id($category_id)
    {
        $term = get_term_by('id', $category_id, 'product_cat', 'ARRAY_A');
        return $term['name'];
    }

}


if (!function_exists('shop_ready_social_share_list')):

    function shop_ready_social_share_list()
    {

        $data = array(
            '' => '---',
            'facebook' => esc_html__('Facebook', 'shopready-elementor-addon'),
            'twitter' => esc_html__('twitter', 'shopready-elementor-addon'),
            'linkedin' => esc_html__('linkedin', 'shopready-elementor-addon'),
            'pinterest' => esc_html__('pinterest ', 'shopready-elementor-addon'),
            'digg' => esc_html__('digg', 'shopready-elementor-addon'),
            'tumblr' => esc_html__('tumblr', 'shopready-elementor-addon'),
            'blogger' => esc_html__('blogger', 'shopready-elementor-addon'),
            'reddit' => esc_html__('reddit', 'shopready-elementor-addon'),
            'delicious' => esc_html__('delicious', 'shopready-elementor-addon'),
            'flipboard' => esc_html__('flipboard', 'shopready-elementor-addon'),
            'vkontakte' => esc_html__('vkontakte', 'shopready-elementor-addon'),
            'odnoklassniki' => esc_html__('odnoklassniki', 'shopready-elementor-addon'),
            'moimir' => esc_html__('moimir', 'shopready-elementor-addon'),
            'livejournal' => esc_html__('livejournal', 'shopready-elementor-addon'),
            'blogger' => esc_html__('blogger', 'shopready-elementor-addon'),
            'evernote' => esc_html__('evernote', 'shopready-elementor-addon'),
            'flipboard' => esc_html__('flipboard', 'shopready-elementor-addon'),
            'mix' => esc_html__('mix', 'shopready-elementor-addon'),
            'meneame' => esc_html__('meneame ', 'shopready-elementor-addon'),
            'pocket' => esc_html__('pocket ', 'shopready-elementor-addon'),
            'surfingbird' => esc_html__('surfingbird ', 'shopready-elementor-addon'),
            'liveinternet' => esc_html__('liveinternet ', 'shopready-elementor-addon'),
            'buffer' => esc_html__('buffer ', 'shopready-elementor-addon'),
            'instapaper' => esc_html__('instapaper ', 'shopready-elementor-addon'),
            'xing' => esc_html__('xing ', 'shopready-elementor-addon'),
            'wordpres' => esc_html__('wordpres ', 'shopready-elementor-addon'),
            'baidu' => esc_html__('baidu ', 'shopready-elementor-addon'),
            'renren' => esc_html__('renren ', 'shopready-elementor-addon'),
            'weibo' => esc_html__('weibo ', 'shopready-elementor-addon'),

        );

        return $data;

    }

endif;


if (!function_exists('shop_ready_post_exists_by_slug')) {

    function shop_ready_post_exists_by_slug($slug = 'product-template-layout', $type = 'product', $meta = false)
    {

        $args_posts = array(
            'post_type' => $type,
            'name' => $slug,
            'posts_per_page' => 1,
        );

        if (is_array($meta)) {
            $args_posts['meta_query'] = $meta;
        }

        return get_posts($args_posts);

    }

}

if (!function_exists('shop_ready_lightness')) {
    function shop_ready_lightness($R = 255, $G = 255, $B = 255)
    {
        return (max($R, $G, $B) + min($R, $G, $B)) / 510.0; // HSL algorithm
    }
}

if (!function_exists('shop_ready_hex2rgba')) {
    function shop_ready_hex2rgba($color, $opacity = 50)
    {

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided 
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        }
        if ($opacity > 1) {
            $output = 'rgb(' . implode(" ", $rgb) . ' / ' . $opacity . '%' . ')';
        }

        //Return rgb(a) color string
        return $output;
    }
}

function shop_ready_woocommerce_setup()
{

    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

}

add_action('after_setup_theme', 'shop_ready_woocommerce_setup');