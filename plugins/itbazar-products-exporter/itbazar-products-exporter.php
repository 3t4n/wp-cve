<?php
/**
 * Plugin Name: آی تی بازار
 * Plugin URI: https://wordpress.org/plugins/itbazar-products-exporter/
 * Description: استخراج محصولات ووکامرس برای آی تی بازار
 * Version: 1.9.1
 * Author: ITBazar
 * Author URI: http://itbazar.com/
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 * Text Domain: itbazar-products-exporter
 **/

defined('ABSPATH') || exit;

class ITBazar_Products_Exporter extends WP_REST_Controller
{
    private $version;
    private $itb_db;
    private $itb_url;
    private $plugin_name;
    private $plugin_slug;
    private $posts_per_page;
    private $itb_token;
    private $page;
    private $products;
    private $is_token_valid = false;
    private $validity_response;

    public function __construct()
    {
        global $wpdb;
        $this->itb_db = $wpdb;
        $this->version = '1.9.1';
        $this->itb_url = 'https://www.itbazar.com/api/bot';
        $this->plugin_name = 'itbazar-products-exporter';
        $this->plugin_slug = $this->plugin_name.'/itbazar-products-exporter.php';
        $this->posts_per_page = 1;
        $this->itb_token = $_GET['itb_token'] ?? null;
        $this->page = 1;
        $this->products = [];

        if($this->itb_token) {
            $this->validity_response = wp_safe_remote_request($this->itb_url.'/get-validity', [
                'method' => 'POST',
                'timeout' => 5,
                'redirection' => 0,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => [],
                'body' => ['itb_token' => $this->itb_token],
                'cookies' => [],
            ]);

            if(isset($this->validity_response) && is_array($this->validity_response)) {
                // if the status code is 200 so it is valid else it is not
                if($this->validity_response['response']['code'] == 200) {
                    $this->is_token_valid = true;

                    if(!defined('WP_MEMORY_LIMIT')) {
                        define('WP_MEMORY_LIMIT', '256M');
                    }
                    @ini_set('memory_limit', '256M');
                    @ini_set('max_execution_time', '600');

                    if(@$_GET['itb_display_errors'] == true) {
                        @ini_set('display_errors', 1);
                        @ini_set('display_startup_errors', 1);
                        @error_reporting(E_ALL);
                    } else {
                        @ini_set('display_errors', 0);
                        @ini_set('display_startup_errors', 0);
                        @error_reporting(0);
                    }

                    if(@$_GET['itb_display_buffer'] != true) {
                        @ob_start(PHP_OUTPUT_HANDLER_CLEANABLE);
                    }
                }
            }
        }

        try {
            add_action('admin_notices', [$this, 'author_admin_notice']);
            add_action('rest_api_init', [$this, 'itb_rest_api_init']);
            add_action('wp_footer', [&$this, 'showPriceTag']);
            // add_action('woocommerce_product_meta_end', [&$this, 'showPriceTag']);
        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    public function author_admin_notice() {
        try {
            if(current_user_can( 'update_plugins' )) {
                $screen = get_current_screen();
                if($screen && $screen->id != 'update') {
                    $plugins = get_site_transient( 'update_plugins' );
                    if ( isset( $plugins->response ) && is_array( $plugins->response ) ) {
                        foreach($plugins->response as $plugin_name => $plugin_data) {
                            if($plugin_name == $this->plugin_slug) {
                                $new_version = explode('.', $plugin_data->new_version);
                                if(isset($new_version[3]) && $new_version[3] == 'n') {
                                    // $link = self_admin_url('update.php?action=upgrade-plugin&plugin=') . $plugin_name;
                                    // $link = wp_nonce_url($link, 'upgrade-plugin_'.$plugin_name);
                                    echo '<div class="notice notice-error is-dismissible">
                                            <p>پلاگین آی تی بازار نیازمند بروز رسانی است، هر چه سریعتر آن را بروزرسانی نمائید.</p>
                                          </div>';
                                }
                                break;
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    public function itb_rest_api_init()
    {
        try {
            register_rest_route('itb-products-exporter/v1', '/get-products', array(
                'methods' => 'GET, POST',
                'callback' => [$this, 'itb_get_data'],
                'permission_callback' => '__return_true'
            ));
        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    public function itb_get_data($request)
    {
        try {
            if ($this->is_token_valid) {
                // Upgrade the plugin
                if($request->get_param('itb_update') == true) {
                    $this->update();
                }

                if($request->get_param('itb_extra_data') == true) {
                    $this->get_extra_data();
                }

                $this->page = intval($request->get_param('page'));
                $this->posts_per_page = intval($request->get_param('posts_per_page'));
                $this->id = intval($request->get_param('id'));

                // Get the prices
                if($request->get_param('itb_price') == true) {
                    $return['data'] = $this->itb_get_prices();
                }
                // Get the products
                else {
                    $full_data = $request->get_param('full_data') == true;
                    $return['data'] = $this->itb_get_products($full_data);
                }

                $return['status'] = $this->validity_response['response']['code'];

                $json_options = 0;
                $itb_json_options = $request->get_param('itb_json_options');
                if(isset($itb_json_options)) {
                    $json_options_exploded = explode(',', $itb_json_options);
                    $json_options_count = count($json_options_exploded);
                    if($json_options_count) {
                        if($json_options_count == 1) {
                            $json_options = $json_options_exploded[0];
                        } else {
                            $json_options = array_reduce($json_options_exploded, function($a, $b) { return $a | $b; }, 0);
                        }

                        $json_options = (int)($json_options);
                    }
                }

                if($json_options > 0) {
                    $return['data'] = json_encode($return['data'], $json_options);
                } else {
                    $return['data'] = json_encode($return['data']);
                }

                if($request->get_param('itb_utf8_ignore') == true) {
                    $return['data'] = iconv('UTF-8', 'UTF-8//IGNORE', $return['data']);
                }

                if(!is_null(error_get_last())) {
                    $errors['general_error'] = error_get_last();
                }

                if(json_last_error() !== 0) {
                    $errors['json_error'] = json_last_error();
                }

                if($request->get_param('itb_display_all_errors') && (isset($errors['general_error']) || isset($errors['json_error']))) {
                    header('Content-Type: application/json');
                    http_response_code(500);
                    if(@$_GET['itb_display_buffer'] != true) {
                        @ob_clean();
                    }
                    exit(json_encode($errors));
                }

                header('Content-Type: application/json');
                http_response_code($return['status']);
                if(@$_GET['itb_display_buffer'] != true) {
                    @ob_clean();
                }
                exit($return['data']);
            }

            header('Content-Type: application/json');
            http_response_code(403);
            if(@$_GET['itb_display_buffer'] != true) {
                @ob_clean();
            }
            exit(json_encode('Forbidden!!'));

            // TODO: fix it later..urldecode
            /*$main_data = $return['data'];
            $data_json_encoded = json_encode($main_data);
            $this->log($data_json_encoded);

            // Has data
            if(!is_null($main_data) && count($main_data)) {
                $return['data'] = urldecode($data_json_encoded);
                $return['data'] = json_decode($return['data'], true);

                // Has data even after decoding
                if(count($return['data'])) {
                    return new WP_REST_Response($return['data'], $return['status']);
                }

                // Data destroyed, so send main data
                return new WP_REST_Response($main_data, 200);
            }

            // Invalid token
            if($return['status'] == 403) {
                return new WP_REST_Response($return['data'], $return['status']);
            }

            // Doesn't have data
            return new WP_REST_Response($main_data, $return['status']);*/

        } catch (\Exception $e) {
            $this->update();
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    private function update()
    {
        require_once ABSPATH . '/wp-admin/includes/file.php';
        require_once ABSPATH . '/wp-admin/includes/misc.php';
        require_once ABSPATH . '/wp-includes/pluggable.php';
        require_once ABSPATH . '/wp-admin/includes/plugin.php';
        require_once ABSPATH . '/wp-admin/includes/class-wp-upgrader.php';
        if(is_plugin_active($this->plugin_slug)) {
            $upgrader = new Plugin_Upgrader();
            $upgrader->upgrade($this->plugin_slug, array('clear_update_cache' => true));
            activate_plugin($this->plugin_slug);
        }
        if(@$_GET['itb_display_buffer'] != true) {
            @ob_clean();
        }

        exit("\n\nVersion: ".$this->version."\n\n");
    }

    private function itb_get_custom_fields($product, $product_id)
    {
        $this->log('itb_get_custom_fields: started => ', [$product, $product_id]);
        try {
            $result = [];
            $attributes = get_post_custom($product_id);
            $this->log('itb_get_custom_fields: $attributes filled by get_post_custom => ', [$attributes]);

            $keys[] = $product_id;
            $placeholders = '';
            foreach ($attributes as $key => $value) {
                if (stripos($key, '_') !== 0 && !preg_match('/^attribute_/', $key) && !preg_match('/^pa_/', $key)) {
                    $keys[] = $key;
                    $placeholders .= '%s, ';
                }
            }
            $this->log('itb_get_custom_fields: $attributes iterated.');

            if(count($keys) > 1) {
                $this->log('itb_get_custom_fields: quering custom fields...');

                $placeholders = rtrim($placeholders, ', ');
                $stmt = "SELECT meta_id AS custom_id, meta_key AS custom_key, meta_value AS custom_value FROM {$this->itb_db->prefix}postmeta WHERE post_id = %d AND meta_key IN ";
                $stmt = $stmt.'('.$placeholders.')';
                $query = $this->itb_db->prepare($stmt, $keys);
                $result = $this->itb_db->get_results($query, ARRAY_A);

                $this->log('itb_get_custom_fields: custom fields fetched => ', [$result]);
            }

            return $result;
        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    private function itb_get_attributes($product, $product_id)
    {
        $this->log('itb_get_attributes: started => ', [$product->get_attributes()]);
        try {
            $result = [];
            $index = 0;
            foreach ($product->get_attributes() as $attribute_key => $attribute_value) {
                if ($attribute_value['visible'] == 1) {
                    $taxonomy = get_taxonomy($attribute_value['name']);
                    $attribute_key_filtered = wc_attribute_label($taxonomy ? $taxonomy->name : $attribute_key);
                    $this->log('itb_get_attributes: $attribute_key_filtered => ', [$attribute_key_filtered]);
                    $attribute_key_filtered = preg_replace('/^attribute_/', '', $attribute_key_filtered);
                    $attribute_key_filtered = preg_replace('/^pa_/', '', $attribute_key_filtered);
                    $attr_name = $product->get_attribute($attribute_value['name']);

                    $this->log('itb_get_attributes: $attr_name => ', [$attr_name]);
                    $terms = wc_get_product_terms($product_id, $attribute_value['name']);
                    if (count($terms)) {
                        $this->log('itb_get_attributes: $terms has values => ', [$terms]);

                        $termNames = [];
                        foreach ($terms as $term) {
                            $termNames[] = $term->name;
                        }

                        $result[$index]['attribute_key'] = $attribute_key_filtered;
                        $result[$index]['attribute_values'] = $termNames;
                    } else {
                        $this->log('itb_get_attributes: $terms doesn\'t have values.');

                        $attr_val = strpos($attr_name, ' | ') > -1 ?
                                    explode(' | ', $attr_name) :
                                    [$attr_name];

                        $result[$index]['attribute_key'] = $attribute_key_filtered;
                        $result[$index]['attribute_values'] = $attr_val;
                    }

                    $result[$index]['attribute_id'] = $attribute_value['id'];

                    $index++;
                }
            }

            return $result;
        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    private function itb_get_products($full_data = false)
    {
        $this->log('itb_get_products: started.');
        try {
            $result = $this->init_getting_data();
            $result['products'] = [];

            foreach ($this->products as $product) {
                if($product !== false) {
                    $product = wc_get_product($product->ID);
                    if($product !== false) {
                        $child = $product->get_parent_id() == 0 ? false : true;
                        $filled_products = $this->fill_product($product, $child, $full_data);
                        if(count($filled_products)) {
                            $result['products'][] = $filled_products;
                        }
                    }
                }
            }

            $this->log('itb_get_products: all products filled.');

            return $result;
        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    private function init_getting_data()
    {
        try {
            $query_params = [
                'posts_per_page' => $this->posts_per_page,
                'paged' => $this->page,
                'post_status' => 'publish',
                'orderby' => 'ID',
                'order' => 'DESC',
                'post_type' => ['product'],
            ];

            if($this->id > 0) {
                $query_params['p'] = $this->id;
            }

            $query = new WP_Query($query_params);

            $this->products = $query->get_posts();

            $this->log('itb_get_products: products fetched.');
            
            return [
                'version' => $this->version,
                'total' => $query->found_posts,
                'per_page' => $this->posts_per_page,
                'current_page' => $this->page,
                'last_page' => $query->max_num_pages,
            ];

        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    private function itb_get_prices()
    {
        try {
            $result = $this->init_getting_data();
            $result['prices'] = [];

            foreach ($this->products as $product) {
                if($product !== false) {
                    $product = wc_get_product($product->ID);
                    if($product !== false) {
                        $parent_id = $product->get_parent_id();
                        if ($parent_id != 0) {
                            $product = wc_get_product($parent_id);
                        }

                        if($product !== false) {
                            $result['prices'][] = [
                                'id' => $product->get_id(),
                                'price' => $product->get_regular_price(),
                                'sale_price' => $product->get_price(),
                            ];
                        }
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    private function fill_product($product, $child = false, $full_data = false)
    {
        $this->log('fill_product: started => ', [$product]);
        try {
            $filled_product = [];
            if ($child) {
                if($product !== false) {
                    $product = wc_get_product($product->get_parent_id());
                }
                $this->log('fill_product: parent_product => ', [$product]);
            }

            if($product !== false) {
                $product_id = $product->get_id();
                $filled_product = [
                    'id' => $product_id,
                    'title' => $product->get_title(),
                    //'short_description' => $product->get_short_description(),
                    'regular_price' => $product->get_regular_price(),
                    'sale_price' => $product->get_price(),
                    'is_on_sale' => $product->is_on_sale(),
                    'is_in_stock' => $product->is_in_stock(),
                    'permalink' => $product->get_permalink(),
                ];

                $categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'all']);
                $this->log('fill_product: categories fetched => ', [$categories]);

                if(count($categories)) {
                    $c = 0;
                    foreach($categories as $category) {
                        $filled_product['categories'][$c]['id'] = $category->term_id;
                        $filled_product['categories'][$c]['name'] = $category->name;
                        $c++;
                    }
                }

                if($full_data) {
                    $this->log('fill_product: full_data started.');
                    
                    $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'single-post-thumbnail');
                    $this->log('fill_product: $thumbnail fetched => ', [$thumbnail]);
                    
                    $filled_product['thumbnail'] = $thumbnail ? $thumbnail[0] : null;

                    $filled_product['images'] = [];
                    $image_ids = $product->get_gallery_image_ids();
                    foreach ($image_ids as $image_id) {
                        $filled_product['images'][] = wp_get_attachment_url($image_id);
                    }
                    $this->log('fill_product: images filled => ', [$filled_product['images']]);

                    $filled_product['custom_fields'] = $this->itb_get_custom_fields($product, $product_id);
                    $this->log('fill_product: custom_fields filled => ', [$filled_product['custom_fields']]);

                    $filled_product['attributes'] = $this->itb_get_attributes($product, $product_id);
                    $this->log('fill_product: attributes filled => ', [$filled_product['attributes']]);
                }

                $filled_product['variations'] = [];

                if ($product->is_type('variable')) {

                    $this->log('fill_product: product is variable.');

                    $product_variations = new WC_Product_Variable($product_id);
                    $product_variations = $product_variations->get_available_variations();

                    if($product_variations) {
                        $p = 0;
                        foreach ($product_variations as $product_variation) {
                            if (isset($product_variation['attributes'])) {
                                $attribute_values = [];
                                foreach ($product_variation['attributes'] as $attribute_key => $attribute_value) {
                                    $attribute_key = preg_replace('/^attribute_/', '', $attribute_key);
                                    $attribute_key = preg_replace('/^pa_/', '', $attribute_key);
                                    $attribute_values[$attribute_key] = $attribute_value;
                                }

                                if(count($attribute_values)) {
                                    $filled_product['variations'][$p] = [
                                        'attributes' => $attribute_values,
                                        'regular_price' => isset($product_variation['display_regular_price']) ? $product_variation['display_regular_price'] : 0,
                                        'sale_price' => isset($product_variation['display_price']) ? $product_variation['display_price'] : 0,
                                    ];
                                    $p++;
                                }
                            }
                        }
                    }
                }
            }

            $this->log('fill_product: variations filled => ', [$filled_product]);

            return $filled_product;
        } catch (\Exception $e) {
            exit("\n\nmessage: ".$e->getMessage().' line: '.$e->getLine().' file: '.$e->getFile()."\n\n");
        }
    }

    private function log($itb_log = 'emtpy!', $extras = [])
    {
        if(isset($_GET['itb_log']) && $_GET['itb_log'] == true) {
            $max_post_per_page = 1;
            if($this->posts_per_page > $max_post_per_page) {
                exit("\n\nposts_per_page cannot be greater than ".$max_post_per_page."\n\n");
            }

            $itb_log = '['.date('Y-m-d H:i:s').'] ['.get_site_url().'] '.$itb_log;

            foreach($extras as $extra) {
                @$itb_log .= json_encode($extra);
            }

            wp_safe_remote_request($this->itb_url.'/store-report', [
                'method' => 'POST',
                'timeout' => 5,
                'redirection' => 0,
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => [],
                'body' => [
                    'itb_log' => $itb_log,
                    'itb_token' => $this->itb_token,
                ],
                'cookies' => [],
            ]);
        }
    }

    private function dd($value)
    {
        echo "<pre>";
        print_r($value);
        echo "</pre>";
    }

    private function get_extra_data()
    {
        global $wp_version;
        $versions = array(
            'php_version' => PHP_VERSION,
            'plugin_version' => $this->version,
            'wordpress_version' => $wp_version,
            'woocommerce_version' => WC_VERSION,
            'rest_url' => get_rest_url(),
        );

        exit(json_encode($versions));
    }

    // Bot Starts Here

    /**
     * @param string $context
     * @return string
     */
    public function get_manage_stock($context = 'view')
    {
        $value = $this->get_prop('manage_stock', $context);

        // Inherit value from parent.
        if ('view' === $context && false === $value && true === wc_string_to_bool($this->parent_data['manage_stock'])) {
            $value = 'parent';
        }
        return $value;
    }

    /**
     * @param $raw_attributes
     */
    public function set_attributes($raw_attributes)
    {
        $raw_attributes = (array)$raw_attributes;
        $attributes = [];

        foreach ($raw_attributes as $key => $value) {
            // Remove attribute prefix which meta gets stored with.
            if (0 === strpos($key, 'attribute_')) {
                $key = substr($key, 10);
            }
            $attributes[$key] = $value;
        }
        $this->set_prop('attributes', $attributes);
    }

    /**
     * @param $html
     */
    protected function output($html)
    {
        // Ignore admin, feed, robots or trackbacks
        if (is_admin() || is_feed() || is_robots() || is_trackback()) {
            return;
        }

        // Output
        echo wp_unslash($html);
    }

    /**
     *
     */
    public function showPriceTag()
    {
        // Ignore admin, feed, robots or trackbacks
        if (is_admin() || is_feed() || is_robots() || is_trackback()) {
            return;
        }

        global $product;

        if (isset($product)) {
            if (method_exists($product, 'is_in_stock') && method_exists($product, 'get_price')) {

                $price = 0;
                if($product->is_in_stock()) {
                    $price = $product->get_price();

                    if ((int)($price) == 0) {
                        if(method_exists($product, 'is_type')) {
                            if ($product->is_type('variable')) {
                                if(class_exists('WC_Product_Variable')) {
                                    $product_variations = new WC_Product_Variable($product);

                                    if(method_exists($product_variations, 'get_available_variations')) {
                                        $product_variations = $product_variations->get_available_variations();

                                        if(isset($product_variations[0]['display_price'])) {
                                            $price = $product_variations[0]['display_price'];
                                        } elseif(isset($product_variations[0]['display_regular_price'])) {
                                            $price = $product_variations[0]['display_regular_price'];
                                        }
                                    }
                                }
                            }
                        }

                        if ((int)($price) == 0) {
                            $price = 0;
                        }
                    }
                }

                $html = '<span id="itbazar" style="display:none">' . $price . '</span>';
                echo $html;
            }
        }
    }


    /**
     * @return array
     */
    protected function get_valid_tax_classes()
    {
        $valid_classes = WC_Tax::get_tax_class_slugs();
        $valid_classes[] = 'parent';

        return $valid_classes;
    }

    /**
     * @param string $context
     * @return mixed
     */
    public function get_purchase_note($context = 'view')
    {
        $value = $this->get_prop('purchase_note', $context);

        // Inherit value from parent.
        if ('view' === $context && empty($value)) {
            $value = apply_filters($this->get_hook_prefix() . 'purchase_note', $this->parent_data['purchase_note'], $this);
        }
        return $value;
    }

    /**
     * @param string $context
     * @return mixed
     */
    public function get_backorders($context = 'view')
    {
        $value = $this->get_prop('backorders', $context);

        // Inherit value from parent.
        if ('view' === $context && 'parent' === $this->get_manage_stock()) {
            $value = apply_filters($this->get_hook_prefix() . 'backorders', $this->parent_data['backorders'], $this);
        }
        return $value;
    }

    /**
     * @param string $context
     * @return null
     */
    public function get_tax_class($context = 'view')
    {
        $value = null;

        if (array_key_exists('tax_class', $this->data)) {
            $value = array_key_exists('tax_class', $this->changes) ? $this->changes['tax_class'] : $this->data['tax_class'];

            if ('edit' !== $context && 'parent' === $value) {
                $value = $this->parent_data['tax_class'];
            }

            if ('view' === $context) {
                $value = apply_filters($this->get_hook_prefix() . 'tax_class', $value, $this);
            }
        }
        return $value;
    }
}

new ITBazar_Products_Exporter();