<?php
define( "BeRocket_Compare_Products_domain", 'products-compare-for-woocommerce'); 
define( "Compare_Products_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('products-compare-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'berocket/framework.php');
foreach (glob(__DIR__ . "/includes/*.php") as $filename)
{
    include_once($filename);
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
foreach (glob(plugin_dir_path( __FILE__ ) . "includes/compatibility/*.php") as $filename)
{
    include_once($filename);
}

class BeRocket_Compare_Products extends BeRocket_Framework {
    public $theme_template;
    public static $settings_name = 'br-compare-products-options';
    protected static $instance;
    protected $plugin_version_capability = 15;
    protected $disable_settings_for_admin = array(
        array('javascript_settings', 'before_load'),
        array('javascript_settings', 'after_load'),
        array('javascript_settings', 'before_remove'),
        array('javascript_settings', 'after_remove'),
    );
    protected $check_init_array = array(
        array(
            'check' => 'woocommerce_version',
            'data' => array(
                'version' => '3.0',
                'operator' => '>=',
                'notice'   => 'Plugin Products Compare for WooCommerce required WooCommerce version 3.0 or higher'
            )
        ),
        array(
            'check' => 'framework_version',
            'data' => array(
                'version' => '2.1',
                'operator' => '>=',
                'notice'   => 'Please update all BeRocket plugins to the most recent version. Products Compare for WooCommerce is not working correctly with older versions.'
            )
        ),
    );
    function __construct () {
        $this->info = array(
            'id'          => 4,
            'lic_id'      => 53,
            'version'     => BeRocket_Compare_Products_version,
            'plugin'      => '',
            'slug'        => '',
            'key'         => '',
            'name'        => '',
            'plugin_name' => 'compare_products',
            'full_name'   => __('Products Compare for WooCommerce', 'products-compare-for-woocommerce'),
            'norm_name'   => __('Products Compare', 'products-compare-for-woocommerce'),
            'price'       => '',
            'domain'      => 'products-compare-for-woocommerce',
            'templates'   => Compare_Products_TEMPLATE_PATH,
            'plugin_file' => BeRocket_Compare_Products_file,
            'plugin_dir'  => __DIR__,
        );
        $this->defaults = array(
            'template'          => '',
            'apply_filters'     => '',
            'general_settings'  => array(
                'fast_compare'                          => '0',
                'hide_same_button'                      => '1',
                'hide_same_default'                     => '0',
                'remove_all_compare'                    => '',
                'addthisID'                             => '',
                'compare_page'                          => '',
                'remove_compare_table'                  => '',
                'attributes'                            => array(),
                'use_full_screen'                       => '',
                'compare_template'                      => 'new-compare',
                'button_position'                       => 'after_add_to_cart',
            ),
            'button_position_product'               => 'after_add_to_cart',
            'style_settings'    => array(
                'button'                                => array(
                    'bcolor'                                => '999999',
                    'bwidth'                                => '0',
                    'bradius'                               => '0',
                    'fontsize'                              => '16',
                    'fcolor'                                => '333333',
                    'backcolor'                             => '9999ff',
                ),
                'toolbutton'                            => array(
                    'bcolor'                                => '999999',
                    'bwidth'                                => '0',
                    'bradius'                               => '0',
                    'fontsize'                              => '16',
                    'fcolor'                                => '333333',
                    'backcolor'                             => '9999ff',
                ),
                'table'                                 => array(
                    'colwidth'                              => '200',
                    'imgwidth'                              => '',
                    'toppadding'                            => '0',
                    'backcolor'                             => 'ffffff',
                    'backcolorsame'                         => '',
                    'margintop'                             => '',
                    'marginbottom'                          => '',
                    'marginleft'                            => '',
                    'marginright'                           => '',
                    'paddingtop'                            => '',
                    'paddingbottom'                         => '',
                    'paddingleft'                           => '',
                    'paddingright'                          => '',
                    'top'                                   => '',
                    'bottom'                                => '',
                    'left'                                  => '',
                    'right'                                 => '',
                    'bordercolor'                           => '',
                    'samecolor'                             => '',
                    'samecolorhover'                        => '',
                ),
                'fulltable'                             => array(
                    'closesize'                             => '34',
                    'closecolor'                            => '',
                    'closetop'                              => '0',
                    'closebottom'                           => '',
                    'closeleft'                             => '',
                    'closeright'                            => '0',
                    'backcolor'                             => '000000',
                    'backopacity'                           => '0.5',
                ),
                'dif_button'                            => array(
                    'fontsize'                              => '',
                    'top'                                   => '',
                    'bottom'                                => '0',
                    'color'                                 => '',
                    'backcolor'                             => '',
                ),
                'dif_button_hover'                      => array(
                    'fontsize'                              => '',
                    'color'                                 => '',
                    'backcolor'                             => '',
                ),
                'clear_button'                            => array(
                    'fontsize'                              => '',
                    'top'                                   => '',
                    'bottom'                                => '0',
                    'color'                                 => '',
                    'backcolor'                             => '',
                ),
                'clear_button_hover'                      => array(
                    'fontsize'                              => '',
                    'color'                                 => '',
                    'backcolor'                             => '',
                ),
                'comparebutton'                         => array(
                    'fontsize'                              => '',
                    'width'                                 => '',
                    'color'                                 => '',
                    'backcolor'                             => '',
                ),
                'comparebuttonhover'                    => array(
                    'fontsize'                              => '',
                    'width'                                 => '',
                    'color'                                 => '',
                    'backcolor'                             => '',
                ),
                'comparebuttonadded'                    => array(
                    'fontsize'                              => '',
                    'width'                                 => '',
                    'color'                                 => '',
                    'backcolor'                             => '',
                ),
            ),
            'text_settings'     => array(
                'compare'                               => 'Compare',
                'add_compare'                           => 'Compare',
                'added_compare'                         => 'Added',
                'toolbar'                               => 'Products For Compare',
                'attribute'                             => 'Attributes',
                'custom'                                => 'Other attributes',
                'availability'                          => 'Availability',
                'description'                           => 'Description',
                'remove_all_compare_text'               => '',
                'hide_same_button_text'                 => '',
                'show_same_button_text'                 => '',
            ),
            'javascript_settings'   => array(
                'before_load'                               => '',
                'after_load'                                => '',
                'before_remove'                             => '',
                'after_remove'                              => '',
                'custom_css'                                => '',
            ),
            'fontawesome_frontend_disable'    => '',
            'fontawesome_frontend_version'    => '',
        );
        $this->values = array(
            'settings_name' => 'br-compare-products-options',
            'option_page'   => 'br-compare-products',
            'premium_slug'  => 'woocommerce-products-compare',
            'free_slug'     => 'products-compare-for-woocommerce',
            'hpos_comp'     => true
        );
        $this->feature_list = array();
        $this->framework_data['fontawesome_frontend'] = true;
        $this->active_libraries = array('addons', 'popup', 'templates');
        parent::__construct( $this );
        if( $this->check_framework_version() ) {
            if ( $this->init_validation() ) {
                $options_global = $this->get_option();
                $options = $options_global['general_settings'];
                $this->add_compare_actions();
                add_action ( 'berocket_add_compare_actions', array($this, 'add_compare_actions') );
                add_action ( 'berocket_remove_compare_actions', array($this, 'remove_compare_actions') );
                add_action ( "widgets_init", array ( $this, 'widgets_init' ) );
                add_filter('berocket_compare_acf_product_field', array($this, 'acf_product_field'), 10, 3);
                add_filter('berocket_compare_acf_product_field_height', array($this, 'acf_product_field_height'), 10, 2);
                add_action( "wp_ajax_br_get_compare_products", array ( $this, 'listener_products' ) );
                add_action( "wp_ajax_nopriv_br_get_compare_products", array ( $this, 'listener_products' ) );
                add_action( "wp_ajax_br_get_compare_list", array ( $this, 'compare_list' ) );
                add_action( "wp_ajax_nopriv_br_get_compare_list", array ( $this, 'compare_list' ) );
                add_action( "br_compare_button_options", array ( $this, 'get_compare_button_options' ) );
                add_shortcode( 'br_compare_table', array( $this, 'shortcode' ) );
                add_shortcode( 'br_compare_text', array( $this, 'shortcode_text' ) );
                add_shortcode( 'br_compare_button', array( $this, 'get_compare_button' ) );
                if( empty($options['remove_compare_table']) ) {
                    add_filter ( 'the_content', array( $this, 'compare_page' ) );
                }
                if( isset($_GET['compare']) ) {
                    $_GET['compare'] = urldecode($_GET['compare']);
                }
                add_filter('BeRocket_popup_open_page_elements', array($this, 'popup_open_page_elements'), 10, 2);
                if( ! empty($options_global['template']) ) {
                    $template_data = $this->libraries->libraries_class['templates']->get_active_template_info($options_global['template']);
                    $theme_template = $template_data['class'];
                    do_action('berocket_init_template_'.$this->info['plugin_name'], $options_global['template']);
                    $this->theme_template = $theme_template;
                }
                add_action( 'divi_extensions_init', array($this, 'divi_initialize_extension') );
            }
        } else {
            add_filter( 'berocket_display_additional_notices', array(
                $this,
                'old_framework_notice'
            ) );
        }
    }

    public function popup_open_page_elements($html_elements, $elements) {
        $pattern = '/<div([^<>]+)>popup_compare_fullsize_table<\/div>/i';
        $replacement = '';
        $html_elements['html_content'] = preg_replace($pattern, $replacement, $html_elements['html_content']);
        return $html_elements;
    }

    public function add_compare_actions() {
        $this->add_remove_compare_actions('add');
    }

    public function remove_compare_actions() {
        $this->add_remove_compare_actions('remove');
    }

    public function add_remove_compare_actions($add_remove = 'add') {
        $options_global = $this->get_option();
        $action = $add_remove.'_action';
        $options = $options_global['general_settings'];
        switch($options['button_position']) {
            case 'before_all': 
                $action( 'woocommerce_before_shop_loop_item', array( $this, 'get_compare_button' ), 5 );
                $action( 'lgv_advanced_before', array( $this, 'get_compare_button' ), 38 );
                break;
            case 'after_image': 
                $action( 'woocommerce_before_shop_loop_item_title', array( $this, 'get_compare_button' ), 20 );
                $action( 'lgv_advanced_after_img', array( $this, 'get_compare_button' ), 38 );
                break;
            case 'after_title': 
                $action( 'woocommerce_shop_loop_item_title', array( $this, 'get_compare_button' ), 38 );
                $action( 'lgv_advanced_before_description', array( $this, 'get_compare_button' ), 38 );
                break;
            case 'after_price': 
                $action( 'woocommerce_after_shop_loop_item_title', array( $this, 'get_compare_button' ), 38 );
                $action( 'lgv_advanced_after_price', array( $this, 'get_compare_button' ), 38 );
                break;
            case 'after_add_to_cart': 
                $action( 'woocommerce_after_shop_loop_item', array( $this, 'get_compare_button' ), 38 );
                $action( 'lgv_advanced_after_price', array( $this, 'get_compare_button' ), 30 );
                break;
        }
        switch($options_global['button_position_product']) {
            case 'before_all': 
                $action( 'woocommerce_before_single_product_summary', array( $this, 'get_compare_button' ), 5 );
                break;
            case 'after_image': 
                $action( 'woocommerce_before_single_product_summary', array( $this, 'get_compare_button' ), 38 );
                break;
            case 'after_title': 
                $action( 'woocommerce_single_product_summary', array( $this, 'get_compare_button' ), 8 );
                break;
            case 'after_price': 
                $action( 'woocommerce_single_product_summary', array( $this, 'get_compare_button' ), 15 );
                break;
            case 'after_add_to_cart':
                $action( 'woocommerce_single_product_summary', array( $this, 'get_compare_button' ), 38 );
                break;
        }
    }
    function init_validation() {
        return parent::init_validation() && $this->check_framework_version();
    }
    function check_framework_version() {
        return ( ! empty(BeRocket_Framework::$framework_version) && version_compare(BeRocket_Framework::$framework_version, 2.1, '>=') );
    }
    function old_framework_notice($notices) {
        $notices[] = array(
            'start'         => 0,
            'end'           => 0,
            'name'          => $this->info[ 'plugin_name' ].'_old_framework',
            'html'          => __('<strong>Please update all BeRocket plugins to the most recent version. Products Compare for WooCommerce is not working correctly with older versions.</strong>', 'products-compare-for-woocommerce'),
            'righthtml'     => '',
            'rightwidth'    => 0,
            'nothankswidth' => 0,
            'contentwidth'  => 1600,
            'subscribe'     => false,
            'priority'      => 10,
            'height'        => 50,
            'repeat'        => false,
            'repeatcount'   => 1,
            'image'         => array(
                'local'  => '',
                'width'  => 0,
                'height' => 0,
                'scale'  => 1,
            )
        );
        return $notices;
    }
    public function init () {
        parent::init();
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_plugin'));
    }
    public function enqueue_scripts_plugin() {
        wp_reset_query();
        $element_i = $this->add_popup('ajax_load');

        $options_global = $this->get_option();
        wp_register_style( 'berocket_compare_products_style', plugins_url( 'css/products_compare.css', __FILE__ ), "", BeRocket_Compare_Products_version );
        wp_enqueue_style( 'berocket_compare_products_style' );
        wp_enqueue_script( 'berocket_jquery_cookie', plugins_url( 'js/jquery.cookie.js', __FILE__ ), array( 'jquery' ), BeRocket_Compare_Products_version );
        wp_enqueue_script( 'berocket_compare_products_script', plugins_url( 'js/products_compare.js', __FILE__ ), array( 'jquery' ), BeRocket_Compare_Products_version );
        wp_enqueue_script( 'jquery-mousewheel', plugins_url( 'js/jquery.mousewheel.min.js', __FILE__ ), array( 'jquery' ), BeRocket_Compare_Products_version );
        $javascript = $options_global['javascript_settings'];
        $options = $options_global['general_settings'];
        $style = $options_global['style_settings'];

        global $wp_query;
        $page = $options['compare_page'];
        $is_compare_page = false;
        $page_id = ( isset($wp_query->queried_object->ID) ? $wp_query->queried_object->ID : '' );
        if( ! empty( $page_id ) && $wp_query->is_main_query() ) {
            $default_language = apply_filters( 'wpml_default_language', NULL );
            $page_id = apply_filters( 'wpml_object_id', $page_id, 'page', true, $default_language );
            $is_compare_page = $page_id == $page;
        }
        wp_localize_script(
            'berocket_compare_products_script',
            'the_compare_products_data',
            array(
                'ajax_url'          => admin_url( 'admin-ajax.php' ),
                'user_func'         => $javascript,
                'home_url'          => site_url(),
                'hide_same'         => (empty($options_global['text_settings']['hide_same_button_text']) ? __( 'Hide attributes with same values', 'products-compare-for-woocommerce' ) : $options_global['text_settings']['hide_same_button_text']),
                'show_same'         => (empty($options_global['text_settings']['show_same_button_text']) ? __( 'Show attributes with same values', 'products-compare-for-woocommerce' ) : $options_global['text_settings']['show_same_button_text']),
                'hide_same_default' => ! empty($options['hide_same_default']),
                'compare_selector'  => '#br_popup_'.$element_i,
                'toppadding'        => (int) $style['table']['toppadding'],
                'is_compare_page'   => $is_compare_page,
            )
        );
    }
    public function widgets_init() {
        register_widget("berocket_compare_products_widget");
    }

    public static function get_all_compare_products() {
        if ( ! empty($_COOKIE['br_products_compare']) ) {
            $cookie = $_COOKIE['br_products_compare'];
            $products = explode( ',', $cookie );
            return $products;
        } else {
            return false;
        }
    }
    public static function is_set_cookie( $id ) {
        if ( ! empty($_COOKIE['br_products_compare']) ) {
            $cookie = $_COOKIE['br_products_compare'];
            if ( preg_match( "/(^".$id.",)|(,".$id."$)|(,".$id.",)|(^".$id."$)/", $cookie ) ) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function listener_products() {
        set_query_var( 'type', 'image' );
        $this->br_get_template_part('selected_products');
        wp_die();
    }

    public function compare_list() {
        $options_global = $this->get_option();
        $options = $options_global['general_settings'];
        $template_name = ( empty($options['compare_template']) || $options['compare_template'] == 'compare' ? 'new-compare' : $options['compare_template'] );
        set_query_var( 'is_full_screen', true );
        set_query_var( 'br_compare_apply_filters', ! empty($options_global['apply_filters']) );
        $this->br_get_template_part($template_name);
        wp_die();
    }

    public function shortcode_text($atts = array(), $content = '') {
        if( ! is_array($atts) ) {
            $atts = array();
        }
        $atts = array_merge(array(
            'noprod' => '1'
        ), $atts);
        if( isset($_GET['compare']) && $_GET['compare'] ) {
           $products = explode(',', $_GET['compare']);
        } else {
            $products = $this->get_all_compare_products();
        }
        if( ! is_array($products) ) {
            $products = array();
        }
        $has_prod = (! empty($products) && count($products) > 0);
        if( ($has_prod && empty($atts['noprod'])) || (! $has_prod && !empty($atts['noprod'])) ) {
            return $content;
        }
        return '';
    }
    public function shortcode($atts = array()) {
        $options_global = $this->get_option();
        $options = $options_global['general_settings'];
        global $wp;
        $default_atts = array(
            'addthis' => '0',
        );
        if( ! is_array($atts) ) {
            $atts = array();
        }
        if( ! empty($options['use_full_screen']) ) {
            $element_i = $this->add_popup('page');
            set_query_var( 'berocket_element_i', $element_i );
        }
        $atts = array_merge($default_atts, $atts);
        $br_compare_uri = add_query_arg('compare', (empty($_COOKIE['br_products_compare']) ? '' : $_COOKIE['br_products_compare']), home_url( $wp->request ));
        $template_name = ( empty($options['compare_template']) || $options['compare_template'] == 'compare' ? 'new-compare' : $options['compare_template'] );
        set_query_var( 'br_compare_apply_filters', ! empty($options_global['apply_filters']) );
        ob_start();
        $this->br_get_template_part($template_name);
        $compare_page = ob_get_clean();
        if( empty($compare_page) ) {
            return '';
        }
        ob_start();
        ?>
        <script>
        var br_compare_page = "<?php echo remove_query_arg('compare', home_url( $wp->request )); ?>";
        var br_compare_uri = "<?php echo $br_compare_uri; ?>";
        <?php
        if( ! isset($_GET['compare']) && ! empty($_COOKIE['br_products_compare']) ) {
        ?>
            if('history' in window && 'pushState' in history) {
                var stateParameters = { BeRocket: "Rules" };
                history.replaceState(stateParameters, "BeRocket Rules", br_compare_uri);
                history.pathname = br_compare_uri;
            } else {
                location.replace(br_compare_uri);
            }
        <?php } ?>
        </script>
        <?php if( ! empty($atts['addthis']) ) { ?>
        <div class="addthis_sharing_toolbox" data-url="<?php echo $br_compare_uri; ?>"></div>
        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $options['addthisID']; ?>"></script>
        <?php
        }
        echo '<div class="woocommerce">'.$compare_page.'</div>';
        return ob_get_clean();
    }

    public function compare_page ($content) {
        global $wp_query;
        $options_global = $this->get_option();
        $options = $options_global['general_settings'];
        $page = $options['compare_page'];
        $page_id = ( isset($wp_query->queried_object->ID) ? $wp_query->queried_object->ID : '' );
        remove_filter ( 'the_content', array( $this, 'compare_page' ) );
        if( ! empty( $page_id ) && $wp_query->is_main_query() ) {
            $default_language = apply_filters( 'wpml_default_language', NULL );
            $page_id = apply_filters( 'wpml_object_id', $page_id, 'page', true, $default_language );
            if ( $page == $page_id ) {
                ob_start();
                $br_compare_uri = add_query_arg('compare', (empty($_COOKIE['br_products_compare']) ? '' : $_COOKIE['br_products_compare']), get_page_link($page));
                ?>
                <script>
                var br_compare_page = "<?php echo get_page_link($page); ?>";
                var br_compare_uri = "<?php echo $br_compare_uri; ?>";
                <?php
                if( ! isset($_GET['compare']) && ! empty($_COOKIE['br_products_compare']) ) {
                ?>
                    if('history' in window && 'pushState' in history) {
                        var stateParameters = { BeRocket: "Rules" };
                        history.replaceState(stateParameters, "BeRocket Rules", br_compare_uri);
                        history.pathname = br_compare_uri;
                    } else {
                        location.replace(br_compare_uri);
                    }
                <?php } ?>
                </script>
                <?php if( ! empty($options['addthisID']) ) { ?>
                <div class="addthis_sharing_toolbox" data-url="<?php echo $br_compare_uri; ?>"></div>
                <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $options['addthisID']; ?>"></script>
                <?php
                }
                if( ! empty($options['use_full_screen']) ) {
                    $element_i = $this->add_popup('page');
                    set_query_var( 'berocket_element_i', $element_i );
                }
                set_query_var( 'br_compare_apply_filters', ! empty($options_global['apply_filters']) );
                $template_name = ( empty($options['compare_template']) || $options['compare_template'] == 'compare' ? 'new-compare' : $options['compare_template'] );
                $this->br_get_template_part($template_name);
                $content = ob_get_clean() . $content;
            }
        }
        add_filter ( 'the_content', array( $this, 'compare_page' ) );
        return $content;
    }

    public function add_popup($type = 'page') {
        $open_type = array();
        $html_content = '';
        if( $type == 'page' ) {
            $open_type = array('click' => array('type' => 'click', 'selector' => '.br_new_compare_full_size'));
            $html_content = 'popup_compare_fullsize_table';
        }
        $popup_options = array(
            'close_delay'      => '0',
            'hide_body_scroll' => true,
            'print_button'     => false,
            'width'            => '90%',
            'height'           => '90%',
            'theme'            => $this->theme_template,
        );
        return BeRocket_popup_display::add_popup($popup_options, $html_content, $open_type);
    }

    public function get_compare_button() {
        $this->get_compare_button_options();
    }
    public function get_compare_button_options($atts = array()) {
        global $product, $wp_query;
        $options_global = $this->get_option();
        if( empty($atts['product']) ) {
            if( empty($product) ) {
                return false;
            }
            $product_id = br_wc_get_product_id($product);
            $product_id = intval($product_id);
        } else {
            $product_id = intval($atts['product']);
        }
        $default_language = apply_filters( 'wpml_default_language', NULL );
        $product_id = apply_filters( 'wpml_object_id', $product_id, 'product', true, $default_language );
        $options = $options_global['general_settings'];
        $text = $options_global['text_settings'];
        $page_compare = intval($options['compare_page']);
        $page_compare = apply_filters( 'wpml_object_id', $page_compare, 'page', true );
        if( ! empty($atts) ) {
            if( ! empty($atts['added_compare']) ) {
                $text['added_compare'] = sanitize_text_field($atts['added_compare']);
            }
            if( ! empty($atts['add_compare']) ) {
                $text['add_compare'] = sanitize_text_field($atts['add_compare']);
            }
            if( isset($atts['fast_compare']) ) {
                $options['fast_compare'] = ! empty($atts['fast_compare']);
            }
        }
        $button_class = array(
            'add_to_cart_button',
            'button',
            'br_compare_button',
            'br_product_'.$product_id
        );
        if ( $this->is_set_cookie($product_id) ) {
            $button_class[] = 'br_compare_added';
        }
        if ( ! empty($options['fast_compare']) ) {
            $button_class[] = 'berocket_product_smart_compare';
        }
        $button_class = implode(' ', $button_class);
        $button_class = esc_html($button_class);
        $href_link = '#no_compare_page';
        if( get_post($page_compare) ) {
            $href_link = get_page_link($page_compare);
        }
        echo '<a class="'.$button_class.'" data-id="'.$product_id.'" href="'.$href_link.'">
            <i class="fa fa-square-o"></i>
            <i class="fa fa-check-square-o"></i>
            <span class="br_compare_button_text" data-added="'.(empty($text['added_compare']) ? __( 'Added', 'products-compare-for-woocommerce' ) : $text['added_compare']).'" data-not_added="'.(empty($text['add_compare']) ? __( 'Compare Product', 'products-compare-for-woocommerce' ) : $text['add_compare']).'">
            '.( $this->is_set_cookie($product_id) ? (empty($text['added_compare']) ? __( 'Added', 'products-compare-for-woocommerce' ) : $text['added_compare']) : (empty($text['add_compare']) ? __( 'Compare Product', 'products-compare-for-woocommerce' ) : $text['add_compare']) ).'
            </span>
        </a>';
    }
    
    public function acf_product_field($field_text, $field, $term) {
        if( is_array($field) && isset($field['type']) ) {
            if( $field['type'] == 'image' ) {
                $img = get_field($field['name'], $term['id']);
                if( empty($img) ) {
                    $field_text = '';
                } else {
                    $field_text = '<img style="display:inline-block;max-height: 90px;max-width:100%;" src="'.$img.'">';
                }
            } elseif($field['type'] == 'color_picker') {
                $color = get_field($field['name'], $term['id']);
                if( empty($color) ) {
                    $field_text = '';
                } else {
                    $field_text = '<p><span style="border:2px solid black; display: inline-block; width: 30px;height:30px;background-color: '.$color.';"></span></p>';
                }
            }
        }
        return $field_text;
    }
    public function acf_product_field_height($height, $field) {
        if( is_array($field) && isset($field['type']) ) {
            if( $field['type'] == 'image' ) {
                $height = 'height:100px!important;';
            }
        }
        return $height;
    }
    public function admin_init () {
        parent::admin_init();
        $options = $this->get_option();
        $this->update_from_not_framework();
        wp_enqueue_script( 'berocket_compare_products_admin_script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ) );
        wp_register_style( 'berocket_compare_products_admin_style', plugins_url( 'css/admin.css', __FILE__ ), "", BeRocket_Compare_Products_version );
        wp_enqueue_style( 'berocket_compare_products_admin_style' );
    }

    public function activation() {
        parent::activation();
        $this->update_from_not_framework();
        $options_global = $this->get_option();
        if ( ! $options_global['general_settings']['compare_page'] ) {
            $compare_page = array(
                'post_title' => 'Compare',
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page',
            );

            $post_id = wp_insert_post($compare_page);
            $options_global['general_settings']['compare_page'] = $post_id;
            update_option($this->values['settings_name'], $options_global);
        }
    }
    public function update_from_not_framework() {
        $update_option = false;
        $options = $this->get_option();
        $settings_list = array('general_settings', 'style_settings', 'text_settings', 'javascript_settings');
        foreach($settings_list as $setting_list) {
            $settings = get_option('br_compare_products_'.$setting_list);
            if( ! empty($settings) && is_array($settings) ) {
                $update_option = true;
                $options[$setting_list] = $settings;
                delete_option('br_compare_products_'.$setting_list);
            }
        }
        if($update_option) {
            $options = $this->recursive_array_set( $this->defaults, $options );
            update_option($this->values[ 'settings_name' ], $options);
        }
    }
    public function set_styles () {
        $options_global = $this->get_option();
        $general_options = $options_global['general_settings'];
        $template_name = ( empty($general_options['compare_template']) || $general_options['compare_template'] == 'compare' ? 'new-compare' : $general_options['compare_template'] );
        $options = $options_global['style_settings'];
        //TABLE STYLE
        if( $template_name == 'compare' ) {
            //OLD TABLE STYLE
            echo '<style>';
            echo '.berocket_compare_box .br_moved_attr tr td {';
            echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).';';
            echo '}';
            echo '.berocket_compare_box .berocket_compare_table_hidden {';
            echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).';';
            echo '}';
            echo 'div.berocket_compare_box.berocket_full_screen_box {';
            echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).';';
            echo '}';
            echo 'div.berocket_compare_box .berocket_compare_table td {';
            echo 'min-width: '.$options['table']['colwidth'].'px;';
            echo '}';
            //DIF BUTTON
            echo '.berocket_compare_table_hidden .br_product_hidden_first .br_show_compare_dif {';
            if( strlen($options['dif_button']['fontsize']) > 0 ) {
                echo 'font-size: '.$options['dif_button']['fontsize'].'px!important;';
            }
            if( strlen($options['dif_button']['top']) > 0 ) {
                echo 'top: '.$options['dif_button']['top'].'px;';
            }
            if( strlen($options['dif_button']['bottom']) > 0 ) {
                echo 'bottom: '.$options['dif_button']['bottom'].'px;';
            }
            if( strlen($options['dif_button']['color']) > 0 ) {
                echo 'color: #'.str_replace( '#', '', $options['dif_button']['color'] ).';';
            }
            if( strlen($options['dif_button']['backcolor']) > 0 ) {
                echo 'background-color: #'.str_replace( '#', '', $options['dif_button']['backcolor'] ).';';
            }
            echo '}';
            echo '.berocket_compare_table_hidden .br_product_hidden_first .br_show_compare_dif:hover {';
            if( strlen($options['dif_button_hover']['fontsize']) > 0 ) {
                echo 'font-size: '.$options['dif_button_hover']['fontsize'].'px;';
            }
            if( strlen($options['dif_button_hover']['color']) > 0 ) {
                echo 'color: #'.str_replace( '#', '', $options['dif_button_hover']['color'] ).';';
            }
            if( strlen($options['dif_button_hover']['backcolor']) > 0 ) {
                echo 'background-color: #'.str_replace( '#', '', $options['dif_button_hover']['backcolor'] ).';';
            }
            echo '}';
            //CLEAR BUTTON
            echo '.berocket_compare_table_hidden .br_product_hidden_first .br_remove_all_compare {';
            if( strlen($options['clear_button']['fontsize']) > 0 ) {
                echo 'font-size: '.$options['clear_button']['fontsize'].'px!important;';
            }
            if( strlen($options['clear_button']['top']) > 0 ) {
                echo 'top: '.$options['clear_button']['top'].'px;';
            }
            if( strlen($options['clear_button']['bottom']) > 0 ) {
                echo 'bottom: '.$options['clear_button']['bottom'].'px;';
            }
            if( strlen($options['clear_button']['color']) > 0 ) {
                echo 'color: #'.str_replace( '#', '', $options['clear_button']['color'] ).';';
            }
            if( strlen($options['clear_button']['backcolor']) > 0 ) {
                echo 'background-color: #'.str_replace( '#', '', $options['clear_button']['backcolor'] ).';';
            }
            echo '}';
            echo '.berocket_compare_table_hidden .br_product_hidden_first .br_remove_all_compare:hover {';
            if( strlen($options['clear_button_hover']['fontsize']) > 0 ) {
                echo 'font-size: '.$options['clear_button_hover']['fontsize'].'px;';
            }
            if( strlen($options['clear_button_hover']['color']) > 0 ) {
                echo 'color: #'.str_replace( '#', '', $options['clear_button_hover']['color'] ).';';
            }
            if( strlen($options['clear_button_hover']['backcolor']) > 0 ) {
                echo 'background-color: #'.str_replace( '#', '', $options['clear_button_hover']['backcolor'] ).';';
            }
            echo '}';
            echo 'div.berocket_compare_box .br_moved_attr tr td {';
            echo 'min-width: '.$options['table']['colwidth'].'px;';
            echo '}';
            echo '.beroket_compare_box .berocket_compare_table img {';
            echo 'width: '.$options['table']['imgwidth'].'px;';
            echo '}';
            echo '.berocket_compare_box {';
            echo 'margin-top: '.$options['table']['margintop'].'px;';
            echo 'margin-bottom: '.$options['table']['marginbottom'].'px;';
            echo 'margin-left: '.$options['table']['marginleft'].'px;';
            echo 'margin-right: '.$options['table']['marginright'].'px;';
            echo '}';
            echo 'div.berocket_full_screen_box.berocket_compare_box {';
            echo 'top: '.$options['table']['top'].'px;';
            echo 'bottom: '.$options['table']['bottom'].'px;';
            echo 'left: '.$options['table']['left'].'px;';
            echo 'right: '.$options['table']['right'].'px;';
            echo '}';
            echo 'div.berocket_back_full_screen {';
            echo 'background-color: #'.str_replace( '#', '', $options['fulltable']['backcolor'] ).';';
            if( isset($options['fulltable']['backopacity']) && $options['fulltable']['backopacity'] >= 0 && $options['fulltable']['backopacity'] <= 1 ) {
                echo 'opacity: '.$options['fulltable']['backopacity'].';';
            }
            echo '}';
            echo '.berocket_compare_box a.berocket_normal_size {';
            echo 'font-size: '.$options['fulltable']['closesize'].'px;';
            if( isset($options['fulltable']['closetop']) && strlen($options['fulltable']['closetop']) > 0 ) {
                echo 'top: '.$options['fulltable']['closetop'].'px;';
            }
            if( isset($options['fulltable']['closebottom']) && strlen($options['fulltable']['closebottom']) > 0 ) {
                echo 'bottom: '.$options['fulltable']['closebottom'].'px;';
            }
            if( isset($options['fulltable']['closeleft']) && strlen($options['fulltable']['closeleft']) > 0 ) {
                echo 'left: '.$options['fulltable']['closeleft'].'px;';
            }
            if( isset($options['fulltable']['closeright']) && strlen($options['fulltable']['closeright']) > 0 ) {
                echo 'right: '.$options['fulltable']['closeright'].'px;';
            }
            echo 'color: #'.str_replace( '#', '', $options['fulltable']['closecolor'] ).';';
            echo '}';
            echo '.berocket_compare_box .br_same_attr td {';
            echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolorsame'] ).';';
            echo '}';
            echo '</style>';
        } else {
            //NEW TABLE STYLE
            echo '<style>';
            echo '.br_new_compare_block .br_left_table {';
            echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).'!important;';
            echo '}';
            echo '.br_new_compare_block .br_main_top table {';
            echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).'!important;';
            echo '}';
            echo 'div.br_new_compare_block.br_full_size_popup {';
            echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolor'] ).'!important;';
            echo '}';
            echo '
            div.br_new_compare .br_right_table tr td,
            div.br_new_compare .br_right_table tr th,
            div.br_new_compare .br_left_table,
            div.br_new_compare_block .br_top_table table th,
            div.br_new_compare_block .br_top_table table td {';
            echo 'width: '.$options['table']['colwidth'].'px!important;';
            echo 'min-width: '.$options['table']['colwidth'].'px!important;';
            echo 'max-width: '.$options['table']['colwidth'].'px!important;';
            echo '}';
            echo '
            div.br_new_compare div.br_right_table,
            div.br_top_table div.br_main_top,
            div.br_top_table div.br_opacity_top{';
            echo 'margin-left: '.$options['table']['colwidth'].'px!important;';
            echo '}';
            echo 'div.br_new_compare_block .br_top_table .br_show_compare_dif {';
            echo 'max-width: '.$options['table']['colwidth'].'px!important;';
            echo '}';
            //DIF BUTTON
            echo 'div.br_new_compare_block .br_show_compare_dif {';
            if( strlen($options['dif_button']['fontsize']) > 0 ) {
                echo 'font-size: '.$options['dif_button']['fontsize'].'px!important;';
            }
            if( strlen($options['dif_button']['top']) > 0 ) {
                echo 'top: '.$options['dif_button']['top'].'px!important;';
            }
            if( strlen($options['dif_button']['bottom']) > 0 ) {
                echo 'bottom: '.$options['dif_button']['bottom'].'px!important;';
            }
            if( strlen($options['dif_button']['color']) > 0 ) {
                echo 'color: #'.str_replace( '#', '', $options['dif_button']['color'] ).'!important;';
            }
            if( strlen($options['dif_button']['backcolor']) > 0 ) {
                echo 'background-color: #'.str_replace( '#', '', $options['dif_button']['backcolor'] ).'!important;';
            }
            echo '}';
            echo 'div.br_new_compare_block .br_show_compare_dif:hover {';
            if( strlen($options['dif_button_hover']['fontsize']) > 0 ) {
                echo 'font-size: '.$options['dif_button_hover']['fontsize'].'px!important;';
            }
            if( strlen($options['dif_button_hover']['color']) > 0 ) {
                echo 'color: #'.str_replace( '#', '', $options['dif_button_hover']['color'] ).'!important;';
            }
            if( strlen($options['dif_button_hover']['backcolor']) > 0 ) {
                echo 'background-color: #'.str_replace( '#', '', $options['dif_button_hover']['backcolor'] ).'!important;';
            }
            echo '}';
            //CLEAR BUTTON
            echo 'div.br_new_compare_block .br_remove_all_compare {';
            if( strlen($options['clear_button']['fontsize']) > 0 ) {
                echo 'font-size: '.$options['clear_button']['fontsize'].'px!important;';
            }
            if( strlen($options['clear_button']['top']) > 0 ) {
                echo 'top: '.$options['clear_button']['top'].'px!important;';
            }
            if( strlen($options['clear_button']['bottom']) > 0 ) {
                echo 'bottom: '.$options['clear_button']['bottom'].'px!important;';
            }
            if( strlen($options['clear_button']['color']) > 0 ) {
                echo 'color: #'.str_replace( '#', '', $options['clear_button']['color'] ).'!important;';
            }
            if( strlen($options['clear_button']['backcolor']) > 0 ) {
                echo 'background-color: #'.str_replace( '#', '', $options['clear_button']['backcolor'] ).'!important;';
            }
            echo '}';
            echo 'div.br_new_compare_block .br_remove_all_compare:hover {';
            if( strlen($options['clear_button_hover']['fontsize']) > 0 ) {
                echo 'font-size: '.$options['clear_button_hover']['fontsize'].'px!important;';
            }
            if( strlen($options['clear_button_hover']['color']) > 0 ) {
                echo 'color: #'.str_replace( '#', '', $options['clear_button_hover']['color'] ).'!important;';
            }
            if( strlen($options['clear_button_hover']['backcolor']) > 0 ) {
                echo 'background-color: #'.str_replace( '#', '', $options['clear_button_hover']['backcolor'] ).'!important;';
            }
            echo '}';
            // -------
            echo '.br_new_compare_block .br_new_compare img {';
            echo 'width: '.$options['table']['imgwidth'].'px!important;';
            echo '}';
            echo '.br_new_compare_block {';
            echo 'margin-top: '.$options['table']['margintop'].'px!important;';
            echo 'margin-bottom: '.$options['table']['marginbottom'].'px!important;';
            echo 'margin-left: '.$options['table']['marginleft'].'px!important;';
            echo 'margin-right: '.$options['table']['marginright'].'px!important;';
            echo '}';
            echo 'div.br_new_compare_block.br_full_size_popup {';
            echo 'top: '.$options['table']['top'].'px!important;';
            echo 'bottom: '.$options['table']['bottom'].'px!important;';
            echo 'left: '.$options['table']['left'].'px!important;';
            echo 'right: '.$options['table']['right'].'px!important;';
            echo '}';
            echo 'div.br_new_compare_black_popup {';
            echo 'background-color: #'.str_replace( '#', '', $options['fulltable']['backcolor'] ).'!important;';
            if( isset($options['fulltable']['backopacity']) && $options['fulltable']['backopacity'] >= 0 && $options['fulltable']['backopacity'] <= 1 ) {
                echo 'opacity: '.$options['fulltable']['backopacity'].'!important;';
            }
            echo '}';
            echo '.br_full_size_close a {';
            echo 'font-size: '.$options['fulltable']['closesize'].'px!important;';
            if( isset($options['fulltable']['closetop']) && strlen($options['fulltable']['closetop']) > 0 ) {
                echo 'top: '.$options['fulltable']['closetop'].'px!important;';
            }
            if( isset($options['fulltable']['closebottom']) && strlen($options['fulltable']['closebottom']) > 0 ) {
                echo 'bottom: '.$options['fulltable']['closebottom'].'px!important;';
            }
            if( isset($options['fulltable']['closeleft']) && strlen($options['fulltable']['closeleft']) > 0 ) {
                echo 'left: '.$options['fulltable']['closeleft'].'px!important;';
            }
            if( isset($options['fulltable']['closeright']) && strlen($options['fulltable']['closeright']) > 0 ) {
                echo 'right: '.$options['fulltable']['closeright'].'px!important;';
            }
            echo 'color: #'.str_replace( '#', '', $options['fulltable']['closecolor'] ).'!important;';
            echo '}';
            echo '.br_new_compare_block .br_same_attr {';
            echo 'background-color: #'.str_replace( '#', '', $options['table']['backcolorsame'] ).'!important;';
            echo '}';
            echo '</style>';
        }
        //OTHER STYLE
        echo '<style>';
        echo '.berocket_compare_widget_start .berocket_compare_widget .berocket_open_compare ,';
        echo '.berocket_compare_widget_toolbar .berocket_compare_widget .berocket_open_compare {';
        echo 'border-color: #'.str_replace( '#', '', $options['button']['bcolor'] ).';';
        echo 'border-width: '.$options['button']['bwidth'].'px;';
        echo 'border-radius: '.$options['button']['bradius'].'px;';
        echo 'font-size: '.$options['button']['fontsize'].'px;';
        echo 'color: #'.str_replace( '#', '', $options['button']['fcolor'] ).';';
        echo 'background-color: #'.str_replace( '#', '', $options['button']['backcolor'] ).';';
        echo '}';
        echo '.berocket_compare_widget_start .berocket_show_compare_toolbar {';
        echo 'border-color: #'.str_replace( '#', '', $options['toolbutton']['bcolor'] ).';';
        echo 'border-width: '.$options['toolbutton']['bwidth'].'px;';
        echo 'border-radius: '.$options['toolbutton']['bradius'].'px;';
        echo 'font-size: '.$options['toolbutton']['fontsize'].'px;';
        echo 'color: #'.str_replace( '#', '', $options['toolbutton']['fcolor'] ).';';
        echo 'background-color: #'.str_replace( '#', '', $options['toolbutton']['backcolor'] ).';';
        echo '}';
        echo '.br_compare_button {';
        echo 'background-color: #'.str_replace( '#', '', $options['comparebutton']['backcolor'] ).'!important;';
        echo 'color: #'.str_replace( '#', '', $options['comparebutton']['color'] ).'!important;';
        if( isset($options['comparebutton']['fontsize']) && strlen($options['comparebutton']['fontsize']) > 0 ) {
            echo 'font-size: '.$options['comparebutton']['fontsize'].'px!important;';
        }
        if( isset($options['comparebutton']['width']) && strlen($options['comparebutton']['width']) > 0 ) {
            echo 'width: '.$options['comparebutton']['width'].'px!important;';
        }
        echo '}';
        echo '.button.br_compare_button:hover {';
        echo 'background-color: #'.str_replace( '#', '', $options['comparebuttonhover']['backcolor'] ).'!important;';
        echo 'color: #'.str_replace( '#', '', $options['comparebuttonhover']['color'] ).'!important;';
        if( isset($options['comparebuttonhover']['fontsize']) && strlen($options['comparebuttonhover']['fontsize']) > 0 ) {
            echo 'font-size: '.$options['comparebuttonhover']['fontsize'].'px!important;';
        }
        if( isset($options['comparebuttonhover']['width']) && strlen($options['comparebuttonhover']['width']) > 0 ) {
            echo 'width: '.$options['comparebuttonhover']['width'].'px!important;';
        }
        echo '}';
        echo '.br_compare_added {';
        echo 'background-color: #'.str_replace( '#', '', $options['comparebuttonadded']['backcolor'] ).'!important;';
        echo 'color: #'.str_replace( '#', '', $options['comparebuttonadded']['color'] ).'!important;';
        if( isset($options['comparebuttonadded']['fontsize']) && strlen($options['comparebuttonadded']['fontsize']) > 0 ) {
            echo 'font-size: '.$options['comparebuttonadded']['fontsize'].'px!important;';
        }
        if( isset($options['comparebuttonadded']['width']) && strlen($options['comparebuttonadded']['width']) > 0 ) {
            echo 'width: '.$options['comparebuttonadded']['width'].'px!important;';
        }
        echo '}';
        echo '</style>';
        $javascript_css = $options_global['javascript_settings'];
        if( ! empty($javascript_css['custom_css']) ) {
            echo '<style>'.$javascript_css['custom_css'].'</style>';
        }
    }
    public function admin_settings( $tabs_info = array(), $data = array() ) {
        $pages = get_pages();
        $pages_option = array();
        foreach($pages as $page) {
            $pages_option[] = array('value' => $page->ID, 'text' => $page->post_title);
        }
        parent::admin_settings(
            array(
                'General' => array(
                    'icon' => 'cog',
                    'name' => __( 'General', "products-compare-for-woocommerce" ),
                ),
                'Popup Templates' => array(
                    'icon' => 'files-o',
                    'name' => __( 'Popup Templates', "products-compare-for-woocommerce" ),
                ),
                'Style' => array(
                    'icon' => 'eye',
                    'name' => __( 'Style', "products-compare-for-woocommerce" ),
                ),
                'Text' => array(
                    'icon' => 'align-center',
                    'name' => __( 'Text', "products-compare-for-woocommerce" ),
                ),
                'Custom CSS/JavaScript' => array(
                    'icon' => 'css3',
                    'name' => __( 'Custom CSS/JavaScript', "products-compare-for-woocommerce" ),
                ),
                'License' => array(
                    'icon' => 'unlock-alt',
                    'link' => admin_url( 'admin.php?page=berocket_account' )
                ),
            ),
            array(
            'General' => array(
                'fast_compare' => array(
                    "label"     => __('Fast compare', 'products-compare-for-woocommerce'),
                    "label_for" => __('Open compare table via AJAX on same page', 'products-compare-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array("general_settings", "fast_compare"),
                    "value"     => '1',
                ),
                'hide_same_button' => array(
                    "label"     => __('Hide attributes with same value', 'products-compare-for-woocommerce'),
                    "items"     => array(
                        "hide_same_button" => array(
                            "label_for" => __('Display button to hide/show attributes', 'products-compare-for-woocommerce'),
                            "type"      => "checkbox",
                            "name"      => array("general_settings", "hide_same_button"),
                            "value"     => '1',
                        ),
                        "hide_same_default" => array(
                            "label_for" => __('Hide attributes by default', 'products-compare-for-woocommerce'),
                            "label_be_for"=> '<br>',
                            "type"      => "checkbox",
                            "name"      => array("general_settings", "hide_same_default"),
                            "value"     => '1',
                        ),
                    ),
                ),
                'remove_all_compare' => array(
                    "label"     => __('Clear compare list', 'products-compare-for-woocommerce'),
                    "label_for" => __('Display button to remove all products from compare', 'products-compare-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array("general_settings", "remove_all_compare"),
                    "value"     => '1',
                ),
                'compare_template' => array(
                    "tr_class" => "berocket_compare_template_hidden",
                    "label"    => __( 'Compare template', "products-compare-for-woocommerce" ),
                    "name"     => array("general_settings", "compare_template"),
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => 'new-compare', 'text' => __( 'Static CSS Build', "products-compare-for-woocommerce" )),
                    ),
                    "value"    => ''
                ),
                'button_position' => array(
                    "label"    => __( 'Button position Shop', "products-compare-for-woocommerce" ),
                    "name"     => array("general_settings", "button_position"),
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => 'before_all', 'text' => __( 'Before all', "products-compare-for-woocommerce" )),
                        array('value' => 'after_image', 'text' => __( 'After Image', "products-compare-for-woocommerce" )),
                        array('value' => 'after_title', 'text' => __( 'After Title', "products-compare-for-woocommerce" )),
                        array('value' => 'after_price', 'text' => __( 'After Price', "products-compare-for-woocommerce" )),
                        array('value' => 'after_add_to_cart', 'text' => __( 'After Add to cart button', "products-compare-for-woocommerce" )),
                        array('value' => '', 'text' => __( '== Disable ==', "products-compare-for-woocommerce" )),
                    ),
                    "value"    => ''
                ),
                'button_position_product' => array(
                    "label"    => __( 'Button position Product', "products-compare-for-woocommerce" ),
                    "name"     => "button_position_product",
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => 'before_all', 'text' => __( 'Before all', "products-compare-for-woocommerce" )),
                        array('value' => 'after_image', 'text' => __( 'After Image', "products-compare-for-woocommerce" )),
                        array('value' => 'after_title', 'text' => __( 'After Title', "products-compare-for-woocommerce" )),
                        array('value' => 'after_price', 'text' => __( 'After Price', "products-compare-for-woocommerce" )),
                        array('value' => 'after_add_to_cart', 'text' => __( 'After Add to cart button', "products-compare-for-woocommerce" )),
                        array('value' => '', 'text' => __( '== Disable ==', "products-compare-for-woocommerce" )),
                    ),
                    "value"    => ''
                ),
                'compare_page' => array(
                    "label"    => __( 'Compare Page', "products-compare-for-woocommerce" ),
                    "name"     => array("general_settings", "compare_page"),
                    "type"     => "selectbox",
                    "options"  => $pages_option,
                    "value"    => ''
                ),
                'remove_compare_table' => array(
                    "label"     => __('Remove compare table', 'products-compare-for-woocommerce'),
                    "label_for" => __('Remove compare table on "Compare page"', 'products-compare-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array("general_settings", "remove_compare_table"),
                    "value"     => '1',
                ),
                'addthisID' => array(
                    "label"     => __('AddThis ID', 'products-compare-for-woocommerce'),
                    "label_for" => ' ' . __('Your ID from AddThis site', 'products-compare-for-woocommerce') . ' <a target="_blank" href="https://www.addthis.com/">https://www.addthis.com/</a>',
                    "type"      => "text",
                    "name"      => array("general_settings", "addthisID"),
                    "value"     => '1',
                ),
                'use_full_screen' => array(
                    "label"     => __('Full screen button on compare page', 'products-compare-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array("general_settings", "use_full_screen"),
                    "value"     => '1',
                ),
                'apply_filters' => array(
                    "label"     => __('Apply Filters', 'products-compare-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array("apply_filters"),
                    "label_for" => __('Apply filters from plugins(Use WP Query. Can cause some issues with compare tables)', 'products-compare-for-woocommerce'),
                    "value"     => '1',
                ),
                'attribute' => array(
                    "label"     => "",
                    "section"   => 'attributes'
                ),
            ),
            'Style' => array(
                'all_style' => array(
                    "label"     => "",
                    "section"   => 'all_style'
                ),
            ),
            'Text' => array(
                'compare' => array(
                    "label"     => __('Text on compare button', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "compare"),
                    "value"     => '',
                ),
                'add_compare' => array(
                    "label"     => __('Add to compare button', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "add_compare"),
                    "value"     => '',
                ),
                'added_compare' => array(
                    "label"     => __('Add to compare button if product added', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "added_compare"),
                    "value"     => '',
                ),
                'toolbar' => array(
                    "label"     => __('Text on button for toolbar open', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "toolbar"),
                    "value"     => '',
                ),
                'attribute' => array(
                    "label"     => __('Attribute text', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "attribute"),
                    "value"     => '',
                ),
                'custom' => array(
                    "label"     => __('Custom taxonomies text', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "custom"),
                    "value"     => '',
                ),
                'availability' => array(
                    "label"     => __('Availability text', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "availability"),
                    "value"     => '',
                ),
                'description' => array(
                    "label"     => __('Description text', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "description"),
                    "value"     => '',
                ),
                'remove_all_compare_text' => array(
                    "label"     => __('Clear compare list Button text', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "remove_all_compare_text"),
                    "extra"     => 'placeholder="'.__( 'Clear compare list', 'products-compare-for-woocommerce' ).'"',
                    "value"     => '',
                ),
                'hide_same_button_text' => array(
                    "label"     => __('Hide attributes with same value Button text', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "hide_same_button_text"),
                    "extra"     => 'placeholder="'.__( 'Hide attributes with same values', 'products-compare-for-woocommerce' ).'"',
                    "value"     => '',
                ),
                'show_same_button_text' => array(
                    "label"     => __('Show attributes with same value Button text', 'products-compare-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "show_same_button_text"),
                    "extra"     => 'placeholder="'.__( 'Show attributes with same values', 'products-compare-for-woocommerce' ).'"',
                    "value"     => '',
                ),
            ),
            'Custom CSS/JavaScript' => array(
                'global_font_awesome_disable' => array(
                    "label"     => __( 'Disable Font Awesome', "products-compare-for-woocommerce" ),
                    "type"      => "checkbox",
                    "name"      => "fontawesome_frontend_disable",
                    "value"     => '1',
                    'label_for' => __('Don\'t load Font Awesome css files on site front end. Use it only if you don\'t use Font Awesome icons in widgets or your theme has Font Awesome.', 'products-compare-for-woocommerce'),
                ),
                'global_fontawesome_version' => array(
                    "label"    => __( 'Font Awesome Version', "products-compare-for-woocommerce" ),
                    "name"     => "fontawesome_frontend_version",
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => '', 'text' => __('Font Awesome 4', 'products-compare-for-woocommerce')),
                        array('value' => 'fontawesome5', 'text' => __('Font Awesome 5', 'products-compare-for-woocommerce')),
                    ),
                    "value"    => '',
                    "label_for" => __('Version of Font Awesome that will be used on front end. Please select version that you have in your theme', 'products-compare-for-woocommerce'),
                ),
                array(
                    "label"   => __("Custom CSS", 'products-compare-for-woocommerce'),
                    "name"    => array("javascript_settings", "custom_css"),
                    "type"    => "textarea",
                    "value"   => "",
                ),
                array(
                    "label"   => __("JavaScript Before products load", 'products-compare-for-woocommerce'),
                    "name"    => array("javascript_settings", "before_load"),
                    "type"    => "textarea",
                    "value"   => "",
                ),
                array(
                    "label"   => __("JavaScript After products load", 'products-compare-for-woocommerce'),
                    "name"    => array("javascript_settings", "after_load"),
                    "type"    => "textarea",
                    "value"   => "",
                ),
                array(
                    "label"   => __("JavaScript Before remove product", 'products-compare-for-woocommerce'),
                    "name"    => array("javascript_settings", "before_remove"),
                    "type"    => "textarea",
                    "value"   => "",
                ),
                array(
                    "label"   => __("JavaScript After remove product", 'products-compare-for-woocommerce'),
                    "name"    => array("javascript_settings", "after_remove"),
                    "type"    => "textarea",
                    "value"   => "",
                ),
            ),
            'Popup Templates' => array(
                'templates' => array(
                    "label"     => "",
                    "section"   => 'templates'
                ),
            )
        ) );
    }
    public function section_attributes($data, $options_global) {
        $options = $options_global['general_settings'];
        $settings_name = $this->values['settings_name'];
        $html = '<th>' . __( 'Uses Attributes', 'products-compare-for-woocommerce' ) . '</th>
        <td>' . __( 'Select attributes that will be displayed on compare table', 'products-compare-for-woocommerce' ) . '</td>
        </tr>
        <tr><th></th><td>';
        $attributes = get_taxonomies();
        unset( $attributes['category']);
        unset( $attributes['post_tag']);
        unset( $attributes['nav_menu']);
        unset( $attributes['link_category']);
        unset( $attributes['post_format']);
        unset( $attributes['product_type']);
        unset( $attributes['product_tag']);
        unset( $attributes['product_shipping_class']);
        foreach ( $attributes as $key_attr => $attr ) {
            $tax = get_taxonomy( $attr );
            $attributes[$key_attr] = $tax->labels->singular_name;
        }
        $attributes = array(
            'cp_price' => __('Price', 'products-compare-for-woocommerce'),
            'cp_add_to_cart' => __('Add To Cart Button', 'products-compare-for-woocommerce'),
            'cp_short_description' => __('Short Description', 'products-compare-for-woocommerce'),
            'cp_available' => __('Availability', 'products-compare-for-woocommerce'),
            'cp_image' => __('Image', 'products-compare-for-woocommerce'),
        ) + $attributes;
        foreach ( $attributes as $attr => $attr_label ) {
            $checked = '';
            if ( ( is_array( $options['attributes'] ) && in_array( $attr, $options['attributes'] ) ) || ! is_array( $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
                $checked = ' checked';
            }
            $html .= '<p><label><input name="' . $settings_name . '[general_settings][attributes][]" type="checkbox" value="' . $attr . '"' . $checked . '>' . $attr_label . '</label></p>';
        }
        if( function_exists('acf_get_field_groups') ) {
            $groups = acf_get_field_groups();
            if ( is_array( $groups ) ) {
                foreach ( $groups as $group ) {
                    $fields = acf_get_fields($group);
                    if( is_array($fields) ) {
                        foreach($fields as $field) {
                            $checked = '';
                            if ( is_array( $options['attributes'] ) && isset( $options['attributes']['acf_br_fields'] ) && is_array( $options['attributes']['acf_br_fields'] ) && in_array($field['name'], $options['attributes']['acf_br_fields']) ) {
                                $checked = ' checked';
                            }
                            $html .= '<p><label>
                                <input name="' . $settings_name . '[general_settings][attributes][acf_br_fields][]" type="checkbox" value="' . $field['name'] . '"' . $checked . '>
                                <strong>ACF </strong><small>[' . $group['title'] . ']</small> ' . $field['label'] . '
                            </label></p>';
                        }
                    }
                }
            }
        }
        $html .= '</td>';
        return $html;
    }
    public function section_all_style($data, $options_global) {
        $settings_name = $this->values['settings_name'];
        $options = $options_global['style_settings'];
        $defaults = $this->defaults['style_settings'];
        ob_start();
        include('templates/style_section.php');
        return ob_get_clean();
    }
    public function divi_initialize_extension() {
        require_once plugin_dir_path( __FILE__ ) . 'divi/includes/CompareExtension.php';
    }
}

new BeRocket_Compare_Products;
