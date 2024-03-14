<?php

add_shortcode('web2application_products', 'web2application_products_func');
function web2application_products_func($attr)
{

    ob_start();
    $term_ids = get_client_fevorite_cat();

    $order = 'ASC';
    $termids = $term_ids;

    if(!empty($attr['attr'])){
        if ($attr['attr'] == 'ASC') {
            $order = 'ASC';
        }
        else{
            $order = 'DESC';
        }
    }
    if(!empty($attr['clientfav'])){
        
        if ($attr['clientfav'] == 'First Favorite Products'){
            $termids=array();
            $termids[0] = $term_ids[0];
        }
        else if ($attr['clientfav'] == 'Second Favorite Products'){
            $termids=array();
            $termids[0] = $term_ids[1];
        }
        else if ($attr['clientfav'] == 'Third Favorite Products'){
            $termids=array();
            $termids[0] = $term_ids[2];
        }
    }
    if(!empty($attr['perpage'])){
        $product_per_page = $attr['perpage'];
    }
    else{
        $product_per_page = 4;
    }


    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $product_per_page,
        'orderby'        => 'ID',
        'order'          => $order,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'id',
                'terms' => $termids
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ?>

        <div class="woocommerce columns-4">
            <?php
            woocommerce_product_loop_start();
            while ($query->have_posts()) {
                $query->the_post();
                wc_get_template_part('content', 'product');
            }
            woocommerce_product_loop_end();
            ?>
        </div>

        <?php
        wp_reset_postdata();
    } 
    else {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            ?>

            <div class="woocommerce columns-4">
                <?php
                woocommerce_product_loop_start();
                while ($query->have_posts()) {
                    $query->the_post();
                    wc_get_template_part('content', 'product');
                }
                woocommerce_product_loop_end();
                ?>
            </div>

            <?php
            wp_reset_postdata();
        } else {
            echo __('No additional products found');
        }
    }

    return ob_get_clean();


}

// function hook_css() {
//     $inside_app = isInAppUser();

//     $switcher = new \Elementor\Control_Switcher();
//     print_r($switcher);

//     $switcherValue = $switcher->get_settings('return_value');
//     print_r($switcherValue);
//     exit;

//     if ($inside_app == 1 && $switcherValue == 'Yes') {
//         echo "Hii";
//     }
//     else{
//         echo "Byy";
//     }
// }

// add_action('wp_head', 'hook_css');


// create wordpress widget
class client_fav extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'client_fav',
            __('client fav', 'web2application'),
            array(
                'description' => __(
                    'Show client fav products',
                    'web2application'
                )
            )
        );
    }

    public function widget($args, $instance)
    {
        global $post;

        // Check if this is a specific page or post by its ID
        if ($post->ID === 5) {
            // echo custom_product_category_shortcode();
            echo do_shortcode('[web2application_products]');
        }
    }

    public function form($instance)
    {
        // Some code here
    }

    public function update($new_instance, $old_instance)
    {
        // Some code here
    }
}
function widget_registration()
{
    register_widget('client_fav');
}

add_action('widgets_init', 'widget_registration');



// create block
function block_fun()
{
    ?>
    <script type="text/javascript">

        const el = wp.element.createElement;

        wp.blocks.registerBlockType('my/simple-text', {
            title: 'ClientFav Product',
            icon: 'universal-access-alt',
            category: 'layout',
            attributes: {
                content: {
                    type: 'array',
                    source: 'children',
                    selector: 'p',
                    default: '[web2application_products]',
                },
            },
            edit: myEdit,
            save: mySave,
        });

        function myEdit(props) {
            const atts = props.attributes;

            return el(wp.editor.RichText, {
                tagName: 'p',
                className: props.className,
                value: atts.content,

                // Listener when the RichText is changed.
                onChange: (value) => {
                    props.setAttributes({ content: value });
                },
            });
        }

        function mySave(props) {
            const atts = props.attributes;

            return el(wp.editor.RichText.Content, {
                tagName: 'p',
                value: atts.content,
            });
        }
    </script>
    <?php
}
add_action('admin_footer', 'block_fun');




function get_client_fevorite_cat()
{
    
    $path = W2A_APP_DATA_DIR . 'web2appdata.json';

    if (file_exists($path)) {
        $json_data = file_get_contents($path);

        if ($json_data) {
            $data = json_decode($json_data, true);
            if ($data && isset($data['app_id'])) {
                $app_id = $data['app_id'];
            }
        }
    }

    $cookie_name = "GetApiCookie";
    $expiration_time = time() + 24 * 60 * 60;
    $response = "";


    // $ch = curl_init($remote_server_url);
    if (!isset($_COOKIE[$cookie_name])) {

        $product_data = array(
            'api_key' => $api_key,
            'user_cookie_id' => isset($_COOKIE['aUserCookieID']) ? $_COOKIE['aUserCookieID'] : '',
            'user_email' => is_user_logged_in() ? wp_get_current_user()->user_email : '',
        );

        $json_data = json_encode($product_data);

        $apiUrl = 'https://web2application.com/w2a/engage/engage-api/clients-favorites.php?appnumber=' . $app_id;
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        }
        $cookie_value = $response;

        curl_close($ch);
        setcookie($cookie_name, $cookie_value, $expiration_time, '/');
    } else {
        $response = $_COOKIE[$cookie_name];
    }
    
    if ($response !== false) {

        $response = stripslashes($response);

        $data = json_decode($response, true);

        if ($data !== null) {

            $termIdsString = '';

            if (is_array($data) && count($data) > 0) {
                $termIdsString = '';

                foreach ($data as $key => $value) {
                    if (isset($value['term_id']) && is_numeric($value['term_id'])) {
                        $termIdsString .= $value['term_id'] . ', ';
                    }
                }

                // Remove the trailing comma and space
                $termIdsString = rtrim($termIdsString, ', ');

                // Convert the comma-separated string to an array
                $term_ids = explode(', ', $termIdsString);

            }
        }
    }
    else{
        $term_ids = '';
    }
    return $term_ids;


}


class MyCustomHooks
{
    private $original_filter_callback;

    public function __construct()
    {
        add_action('elementor/element/woocommerce-products/section_query/after_section_start', [$this, 'products_settings'], 10, 2);
        add_action('elementor/widget/before_render_content', [$this, 'after_render_widget']);
    }

    public function products_settings($element, $section_id)
    {
        // Add controls for query options
        $element->add_control(
            'query_option',
            array(
                'label' => __('Filters', 'woocommerce-product-source'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' =>  __('select', 'woocommerce-product-source'),
                'options' => array(
                    'clientfav' =>  __('Client Favorite Products', 'woocommerce-product-source'),
                    'select' =>  __('Select Filter', 'woocommerce-product-source'),
                ),
            )
        );

        $element->add_control(
            'term_options',
            array(
                'label' => __('Client Favorit', 'woocommerce-product-source'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => __('selectfav', 'woocommerce-product-source'),
                'options' => array(
                    '1' => __('First Favorite Products', 'woocommerce-product-source'),
                    '2' => __('Second Favorite Products', 'woocommerce-product-source'),
                    '3' => __('Third Favorite Products', 'woocommerce-product-source'),
                    'selectfav' => __('MixFavorite Products', 'woocommerce-product-source'),
                ),
            )
        );
    }

    public function after_render_widget($widget)
    {
        $query_options = $widget->get_settings('query_option');
        $term_options = $widget->get_settings('term_options');

        if ($query_options === 'clientfav') {

            $termids = get_client_fevorite_cat();

            if (!empty($termids)) {
                if ($term_options == 1 && isset($termids[0])) {
                    $termids = array($termids[0]);
                } elseif ($term_options == 2 && isset($termids[1])) {
                    $termids = array($termids[1]);
                } elseif ($term_options == 3 && isset($termids[2])) {
                    $termids = array($termids[2]);
                }
            }

            $widget->set_settings("query_include", array('terms'));
            $widget->set_settings("query_include_term_ids", $termids);

        }
    }
}

$my_custom_hooks = new MyCustomHooks();