<?php
define( "BeRocket_pagination_domain", 'pagination-styler-for-woocommerce'); 
define( "pagination_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('pagination-styler-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'berocket/framework.php');
foreach (glob(__DIR__ . "/includes/*.php") as $filename)
{
    include_once($filename);
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class BeRocket_Pagination extends BeRocket_Framework {
    public static $settings_name = 'br-pagination-options';
    protected $plugin_version_capability = 15;
    protected static $instance;
    protected $disable_settings_for_admin = array(
        array('javascript_settings', 'page_load'),
    );
    protected $check_init_array = array(
        array(
            'check' => 'woocommerce_version',
            'data' => array(
                'version' => '3.0',
                'operator' => '>=',
                'notice'   => 'Plugin Pagination Styler for WooCommerce required WooCommerce version 3.0 or higher'
            )
        ),
        array(
            'check' => 'framework_version',
            'data' => array(
                'version' => '2.1',
                'operator' => '>=',
                'notice'   => 'Please update all BeRocket plugins to the most recent version. Pagination Styler for WooCommerce is not working correctly with older versions.'
            )
        ),
    );
    function __construct () {
        $this->info = array(
            'id'          => 6,
            'lic_id'      => 41,
            'version'     => BeRocket_pagination_version,
            'plugin'      => '',
            'slug'        => '',
            'key'         => '',
            'name'        => '',
            'plugin_name' => 'pagination',
            'full_name'   => __('Pagination Styler for WooCommerce', 'pagination-styler-for-woocommerce'),
            'norm_name'   => __('Pagination Styler', 'pagination-styler-for-woocommerce'),
            'price'       => '',
            'domain'      => 'pagination-styler-for-woocommerce',
            'templates'   => pagination_TEMPLATE_PATH,
            'plugin_file' => BeRocket_pagination_file,
            'plugin_dir'  => __DIR__,
        );
        $this->defaults = array(
            'general_settings'    => array(
                'use_next_prev'                     => '1',
                'pos_next_prev'                     => 'around_pagination',
                'page_end_size'                     => '3',
                'page_mid_size'                     => '3',
                'use_dots'                          => '1',
            ),
            'style_settings'      => array(
                'style'                             => 'default',
                'use_styles'                        => array(),
                'buttons'                           => array(
                    'prev'                              => array(),
                    'next'                              => array(),
                    'dots'                              => array(),
                    'current'                           => array(),
                    'other'                             => array(),
                ),
                'pagination_pos'                    => 'center',
                'default_show'                      => array(
                    'after_products'                    => '1',
                    'before_products'                   => '',
                ),
                'fixed_position'                    => array(
                    'top'                               => '',
                    'bottom'                            => '0',
                    'left'                              => '',
                    'right'                             => '',
                ),
                'buffer_top'                        => '700',
                'bottom_position'                   => '10',
                'ul_style'                          => array(
                    'background-color'                  => '',
                    'border-color'                      => 'd3ced2',
                    'border-top-width'                  => '1',
                    'border-bottom-width'               => '1',
                    'border-left-width'                 => '1',
                    'border-right-width'                => '0',
                    'padding-top'                       => '0',
                    'padding-bottom'                    => '0',
                    'padding-left'                      => '0',
                    'padding-right'                     => '0',
                    'border-top-left-radius'            => '0',
                    'border-top-right-radius'           => '0',
                    'border-bottom-right-radius'        => '0',
                    'border-bottom-left-radius'         => '0',
                ),
                'ul_li_style'                       => array(
                    'border-color'                      => 'd3ced2',
                    'border-top-width'                  => '0',
                    'border-bottom-width'               => '0',
                    'border-left-width'                 => '0',
                    'border-right-width'                => '1',
                    'border-top-left-radius'            => '0',
                    'border-top-right-radius'           => '0',
                    'border-bottom-right-radius'        => '0',
                    'border-bottom-left-radius'         => '0',
                    'margin-top'                        => '0',
                    'margin-bottom'                     => '0',
                    'margin-left'                       => '0',
                    'margin-right'                      => '0',
                    'float'                             => 'left',
                ),
                'ul_li_hover_style'                 => array(
                    'border-color'                      => 'd3ced2',
                ),
                'ul_li_a-span_style'                => array(
                    'color'                             => '333',
                    'background-color'                  => '',
                    'padding-top'                       => '10',
                    'padding-bottom'                    => '10',
                    'padding-left'                      => '10',
                    'padding-right'                     => '10',
                ),
                'ul_li_a-span_hover_style'          => array(
                    'color'                             => '8a7e88',
                    'background-color'                  => 'ebe9eb',
                ),
            ),
            'text_settings'       => array(
                'dots_prev_icon'                    => 'fa-ellipsis-h',
                'dots_prev_text'                    => '…',
                'dots_next_icon'                    => 'fa-ellipsis-h',
                'dots_next_text'                    => '…',
                'prev_icon'                         => 'fa-angle-double-left',
                'next_icon'                         => 'fa-angle-double-right',
                'prev_text'                         => '«',
                'next_text'                         => '»',
                'current_page'                      => '%PAGE%',
                'page'                              => '%PAGE%',
                'first_page_icon'                   => '',
                'first_page'                        => '1',
                'last_page_icon'                    => '',
                'last_page'                         => '%LAST%',
            ),
            'javascript_settings' => array(
                'page_load'                         => '',
                'custom_css'                        => '',
            ),
            'fontawesome_frontend_disable'    => '',
            'fontawesome_frontend_version'    => '',
        );
        $this->values = array(
            'settings_name' => 'br-pagination-options',
            'option_page'   => 'br-pagination',
            'premium_slug'  => 'woocommerce-pagination-styler',
            'free_slug'     => 'pagination-styler-for-woocommerce',
            'hpos_comp'     => true
        );
        $this->feature_list = array();
        $this->framework_data['fontawesome_frontend'] = true;
        parent::__construct( $this );
        if( $this->check_framework_version() ) {
            if ( $this->init_validation() ) {
                $options = $this->get_option();
                add_action ( 'wp_footer', array( $this, 'wp_footer_script' ) );
                $options_global = $this->get_option();
                $style_options = $options_global['style_settings'];
            }
        } else {
            add_filter( 'berocket_display_additional_notices', array(
                $this,
                'old_framework_notice'
            ) );
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
            'html'          => __('<strong>Please update all BeRocket plugins to the most recent version. Pagination Styler for WooCommerce is not working correctly with older versions.</strong>', 'pagination-styler-for-woocommerce'),
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
        $options = $this->get_option();
        $style_options = $options['style_settings'];
        wp_register_style( 'berocket_pagination_style', plugins_url( 'css/pagination.css', __FILE__ ), "", BeRocket_pagination_version );
        wp_enqueue_style( 'berocket_pagination_style' );
        wp_enqueue_script( 'berocket_pagination_script', plugins_url( 'js/pagination_styler.js', __FILE__ ), array( 'jquery' ), BeRocket_pagination_version );
        remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
        if ( $style_options['style'] != 'default' || $style_options['default_show']['after_products'] ) {
            add_action ( 'woocommerce_after_shop_loop', 'berocket_pagination', 10 );
        }
        if ( $style_options['style'] == 'default' && $style_options['default_show']['before_products'] ) {
            add_action ( 'woocommerce_before_shop_loop', 'berocket_pagination', 80 );
        }
        add_filter ( 'woocommerce_pagination_args', array( $this, 'set_pagination_settings' ) );
    }
    public function admin_init () {
        parent::admin_init();
        $this->update_from_not_framework();
        wp_enqueue_script( 'berocket_pagination_admin_script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ) );
        wp_register_style( 'berocket_pagination_admin_style', plugins_url( 'css/admin.css', __FILE__ ), "", BeRocket_pagination_version );
        wp_enqueue_style( 'berocket_pagination_admin_style' );
    }
    public function update_from_not_framework() {
        $update_option = false;
        $options = $this->get_option();
        $settings_list = array('general_settings', 'style_settings', 'text_settings', 'javascript_settings');
        foreach($settings_list as $setting_list) {
            $settings = get_option('br_pagination_'.$setting_list);
            if( ! empty($settings) && is_array($settings) ) {
                $update_option = true;
                $options[$setting_list] = $settings;
                delete_option('br_pagination_'.$setting_list);
            }
        }
        if($update_option) {
        }
        $options = $this->recursive_array_set( $this->defaults, $options );
        update_option($this->values[ 'settings_name' ], $options);
    }
    public function set_styles () {
        $options_global = $this->get_option();
        $style_options = $options_global['style_settings'];
        echo '<style>';
        echo '.woocommerce-pagination.berocket_pagination {';
        if ( $style_options['style'] == 'default' ) {
            echo 'text-align: '.$style_options['pagination_pos'].'!important;';
            echo 'clear: both;';
        } else if ( $style_options['style'] == 'fixed' ) {
            echo 'line-height: 0;';
            echo 'position: fixed;';
            self::array_to_style ( $style_options['fixed_position'] );
            echo 'z-index: 99999;';
        } else if ( $style_options['style'] == 'bottom' ) {
            echo 'text-align: '.$style_options['pagination_pos'].'!important;';
        }
        echo '}';
        echo '.woocommerce-pagination.berocket_pagination ul{';
        self::array_to_style ( $style_options['ul_style'] );
        echo '}';
        echo '.woocommerce-pagination.berocket_pagination ul li{';
        self::array_to_style ( $style_options['ul_li_style'] );
        echo '}';
        echo '.woocommerce-pagination.berocket_pagination ul li:hover{';
        self::array_to_style ( $style_options['ul_li_hover_style'] );
        echo '}';
        echo '.woocommerce-pagination.berocket_pagination ul li > a, .woocommerce-pagination.berocket_pagination ul li > span{';
        self::array_to_style ( $style_options['ul_li_a-span_style'] );
        echo '}';
        echo '.woocommerce-pagination.berocket_pagination ul li > a:hover, .woocommerce-pagination.berocket_pagination ul li > span.current{';
        self::array_to_style ( $style_options['ul_li_a-span_hover_style'] );
        echo '}';
        if( isset($style_options['use_styles']) && is_array($style_options['use_styles']) ) {
            foreach($style_options['use_styles'] as $style_id) {
                if( isset($style_options['buttons'][$style_id]['ul_li_style']) && is_array($style_options['buttons'][$style_id]['ul_li_style']) ) {
                    echo '.woocommerce-pagination.berocket_pagination ul li.'.$style_id.'{';
                    self::array_to_style ( $style_options['buttons'][$style_id]['ul_li_style'] );
                    echo '}';
                }
                if( isset($style_options['buttons'][$style_id]['ul_li_hover_style']) && is_array($style_options['buttons'][$style_id]['ul_li_hover_style']) ) {
                    echo '.woocommerce-pagination.berocket_pagination ul li.'.$style_id.':hover{';
                    self::array_to_style ( $style_options['buttons'][$style_id]['ul_li_hover_style'] );
                    echo '}';
                }
                if( isset($style_options['buttons'][$style_id]['ul_li_a-span_style']) && is_array($style_options['buttons'][$style_id]['ul_li_a-span_style']) ) {
                    echo '.woocommerce-pagination.berocket_pagination ul li.'.$style_id.' > a, .woocommerce-pagination.berocket_pagination ul li.'.$style_id.' > span{';
                    self::array_to_style ( $style_options['buttons'][$style_id]['ul_li_a-span_style'] );
                    echo '}';
                }
                if( isset($style_options['buttons'][$style_id]['ul_li_a-span_hover_style']) && is_array($style_options['buttons'][$style_id]['ul_li_a-span_hover_style']) ) {
                    echo '.woocommerce-pagination.berocket_pagination ul li.'.$style_id.' > a:hover, .woocommerce-pagination.berocket_pagination ul li.'.$style_id.' > span.current{';
                    self::array_to_style ( $style_options['buttons'][$style_id]['ul_li_a-span_hover_style'] );
                    echo '}';
                }
            }
        }
        $javascript_options = $options_global['javascript_settings'];
        echo $javascript_options['custom_css'];
        echo '</style>';
    }
    public static function array_to_style ( $styles ) {
        $color = array( 'color', 'background-color', 'border-color' );
        $size = array( 'border-top-width', 'border-bottom-width', 'border-left-width', 'border-right-width',
            'padding-top', 'padding-bottom', 'padding-left', 'padding-right',
            'border-top-left-radius', 'border-top-right-radius', 'border-bottom-right-radius', 'border-bottom-left-radius',
            'margin-top', 'margin-bottom', 'margin-left', 'margin-right', 'top', 'bottom', 'left', 'right' );
        $border_color = array('border-color');
        $border_width = array('border-width', 'border-top-width', 'border-bottom-width', 'border-left-width', 'border-right-width');
        $has_border_color = $has_border_width = false;
        foreach( $styles as $name => $value ) {
            if ( isset( $value ) ) {
                if ( ! $has_border_color && in_array( $name, $border_color ) ) {
                    $has_border_color = true;
                }
                if ( ! $has_border_width && in_array( $name, $border_width ) ) {
                    $has_border_width = true;
                }
                if ( in_array( $name, $color ) ) {
                    if( ! empty($value) ) {
                        if ( $value[0] != '#' ) {
                            $value = '#' . $value;
                        }
                        echo $name . ':' . $value . '!important;';
                    }
                } else if ( in_array( $name, $size ) ) {
                    if ( strpos( $value, '%' ) || strpos( $value, 'em' ) || strpos( $value, 'px' ) ) {
                        echo $name . ':' . $value . '!important;';
                    } else {
                        echo $name . ':' . $value . 'px!important;';
                    }
                } else {
                    echo $name . ':' . $value . '!important;';
                }
            }
        }
        if( $has_border_color && $has_border_width ) {
            echo 'border-style:solid!important;';
        }
    }
    public function wp_footer_script() {
        $options_global = $this->get_option();
        $style_options = $options_global['style_settings'];
        $javascript_options = $options_global['javascript_settings'];
        echo '<script>';
        echo 'jQuery(document).ready( function () { ';
        echo $javascript_options['page_load'];
        echo '});';
        if ( $style_options['style'] == 'bottom' ) {
            ?>
            function bottom_style_set() {
                var bottom_position = <?php echo $style_options['bottom_position']; ?>;
                var $block = jQuery('.woocommerce-pagination.berocket_pagination');
                var pagination_height = jQuery('.woocommerce-pagination.berocket_pagination ul').outerHeight(true);
                var pagination_width = jQuery('.woocommerce-pagination.berocket_pagination ul').outerWidth(true);
                var pagination_pos = '<?php echo $style_options['pagination_pos']; ?>';
                if( pagination_pos == 'left' ) {
                    var left_pos = 0;
                } else if( pagination_pos == 'right' ) {
                    var left_pos = $block.outerWidth(true) - pagination_width;
                } else {
                    var left_pos = ($block.outerWidth(true) - pagination_width) / 2;
                }
                var full_left_pos = left_pos + $block.offset().left;
                $block.css('position', 'relative').width('initial').height(pagination_height);
                
                var bufferTop = <?php echo $style_options['buffer_top']; ?>;
                var top = $block.offset().top;
                var height = $block.innerHeight();
                var padding_top = parseFloat($block.css('padding-top'));
                var scrolled = jQuery(window).scrollTop() + jQuery(window).height();
                
                var first_top = bufferTop + padding_top;
                var first_position = bufferTop - top - bottom_position - pagination_height;
                
                var second_top = top + height + bottom_position;
                var second_position = bottom_position;
                var left_position = jQuery('.woocommerce-pagination.berocket_pagination').offset().left;
                var third_position = 0;
                if ( scrolled < first_top ) {
                    jQuery('.woocommerce-pagination.berocket_pagination ul').css( { 'position': 'absolute', 'top': first_position, 'bottom': '', 'left': left_pos } );
                } else if ( scrolled < second_top ) {
                    jQuery('.woocommerce-pagination.berocket_pagination ul').css( { 'position': 'fixed', 'bottom': second_position, 'top': '', 'left': full_left_pos } );
                } else {
                    jQuery('.woocommerce-pagination.berocket_pagination ul').css( { 'position': 'absolute', 'bottom': third_position, 'top': '', 'left': left_pos } );
                }
            }
            bottom_style_set();
            jQuery(window).scroll(bottom_style_set);
            jQuery(document).on("berocket_lmp_end berocket_ajax_filtering_end", bottom_style_set);
            <?php
        }
        echo '</script>';
    }

    public function set_pagination_settings ( $args ) {
        $options_global = $this->get_option();
        $options = $options_global['general_settings'];
        $text_options = $options_global['text_settings'];
        $args['prev_next']  = $options['use_next_prev'];
        $args['end_size']   = $options['page_end_size'];
        $args['mid_size']   = $options['page_mid_size'];
        $prev_icon = '<i class= "fa '.$text_options['prev_icon'].'"></i>';
        $next_icon = '<i class= "fa '.$text_options['next_icon'].'"></i>';
        $dots_prev_icon = '<i class="fa '.$text_options['dots_prev_icon'].'"></i>';
        $dots_next_icon = '<i class="fa '.$text_options['dots_next_icon'].'"></i>';
        $first_page_icon = '<i class="fa '.$text_options['first_page_icon'].'"></i>';
        $last_page_icon = '<i class="fa '.$text_options['last_page_icon'].'"></i>';
        $args['prev_text']   = str_replace( '%ICON%', $prev_icon, $text_options['prev_text'] );
        $args['next_text']   = str_replace( '%ICON%', $next_icon, $text_options['next_text'] );
        $args['dots_prev_text'] = str_replace( '%ICON%', $dots_prev_icon, $text_options['dots_prev_text'] );
        $args['dots_next_text'] = str_replace( '%ICON%', $dots_next_icon, $text_options['dots_next_text'] );
        $args['first_page'] = str_replace( '%ICON%', $first_page_icon, $text_options['first_page'] );
        $args['last_page'] = str_replace( '%ICON%', $last_page_icon, $text_options['last_page'] );
        $args['current_page'] = $text_options['current_page'];
        $args['page'] = $text_options['page'];
        return apply_filters( 'berocket_pagination_styler_page_data', $args );
    }
    public function admin_settings( $tabs_info = array(), $data = array() ) {
        parent::admin_settings(
            array(
                'General' => array(
                    'icon' => 'cog',
                    'name' => __('General', 'pagination-styler-for-woocommerce'),
                ),
                'Style' => array(
                    'icon' => 'eye',
                    'name' => __('Style', 'pagination-styler-for-woocommerce'),
                ),
                'Text' => array(
                    'icon' => 'align-center',
                    'name' => __('Text', 'pagination-styler-for-woocommerce'),
                ),
                'Custom CSS/JavaScript' => array(
                    'icon' => 'css3',
                    'name' => __('Custom CSS/JavaScript', 'pagination-styler-for-woocommerce'),
                ),
                'License' => array(
                    'icon' => 'unlock-alt',
                    'link' => admin_url( 'admin.php?page=berocket_account' ),
                    'name' => __('License', 'pagination-styler-for-woocommerce'),
                ),
            ),
            array(
            'General' => array(
                'use_next_prev' => array(
                    "label"     => __('Enable previous and next buttons on pagination', 'pagination-styler-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array("general_settings", "use_next_prev"),
                    "value"     => '1',
                ),
                'pos_next_prev' => array(
                    "label"    => __( 'Previous and next buttons position', "pagination-styler-for-woocommerce" ),
                    "name"     => array("general_settings", "pos_next_prev"),
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => 'around_pagination', 'text' => __('Around pagination', 'pagination-styler-for-woocommerce')),
                        array('value' => 'around_central', 'text' => __('Around central page', 'pagination-styler-for-woocommerce')),
                        array('value' => 'around_current', 'text' => __('Around current page', 'pagination-styler-for-woocommerce')),
                    ),
                    "value"    => '',
                ),
                'page_end_size' => array(
                    "label"     => __('First and last button count in pagination', 'pagination-styler-for-woocommerce'),
                    "type"      => "number",
                    "name"      => array("general_settings", "page_end_size"),
                    "value"     => '1',
                    "extra"     => "min='0'"
                ),
                'page_mid_size' => array(
                    "label"     => __('Button count around current page', 'pagination-styler-for-woocommerce'),
                    "type"      => "number",
                    "name"      => array("general_settings", "page_mid_size"),
                    "value"     => '1',
                    "extra"     => "min='0'"
                ),
                'use_dots' => array(
                    "label"     => __('Enable pagination dots', 'pagination-styler-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => array("general_settings", "use_dots"),
                    "value"     => '1',
                ),
            ),
            'Style' => array(
                'style' => array(
                    "label"    => __( 'Pagination style', "pagination-styler-for-woocommerce" ),
                    "name"     => array("style_settings", "style"),
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => 'default', 'text' => __('Default', 'pagination-styler-for-woocommerce')),
                        array('value' => 'fixed', 'text' => __('Fixed', 'pagination-styler-for-woocommerce')),
                        array('value' => 'bottom', 'text' => __('Bottom', 'pagination-styler-for-woocommerce')),
                    ),
                    "class"    => 'berocket_pagination_type',
                    "value"    => '',
                ),
                'pagination_pos' => array(
                    "label"    => __( 'Position', "pagination-styler-for-woocommerce" ),
                    "name"     => array("style_settings", "pagination_pos"),
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => 'center', 'text' => __('Center', 'pagination-styler-for-woocommerce')),
                        array('value' => 'left', 'text' => __('Left', 'pagination-styler-for-woocommerce')),
                        array('value' => 'right', 'text' => __('Right', 'pagination-styler-for-woocommerce')),
                    ),
                    "tr_class" => 'berocket_pagination_type_blocks berocket_pagination_type_default berocket_pagination_type_bottom',
                    "value"    => '',
                ),
                'default_show' => array(
                    "label"    => __( 'Position for pagination', "pagination-styler-for-woocommerce" ),
                    "items"    => array(
                        'after_products' => array(
                            "label_for" => __('After products', 'pagination-styler-for-woocommerce'),
                            "type"      => "checkbox",
                            "name"      => array("style_settings", "default_show", "after_products"),
                            "value"     => '1',
                        ),
                        'before_products' => array(
                            "label_for" => __('Before products', 'pagination-styler-for-woocommerce'),
                            "type"      => "checkbox",
                            "name"      => array("style_settings", "default_show", "before_products"),
                            "value"     => '1',
                        ),
                    ),
                    'tr_class' => 'berocket_pagination_type_blocks berocket_pagination_type_default',
                ),
                'fixed_position' => array(
                    "label"    => __( 'Position', "pagination-styler-for-woocommerce" ),
                    "items"    => array(
                        'top' => array(
                            "label_be_for"=> __('Top: ', 'pagination-styler-for-woocommerce'),
                            "type"      => "text",
                            "name"      => array("style_settings", "fixed_position", "top"),
                            "value"     => '1',
                        ),
                        'bottom' => array(
                            "label_be_for"=> '<br>' . __('Bottom: ', 'pagination-styler-for-woocommerce'),
                            "type"      => "text",
                            "name"      => array("style_settings", "fixed_position", "bottom"),
                            "value"     => '1',
                        ),
                        'left' => array(
                            "label_be_for"=> '<br>' . __('Left: ', 'pagination-styler-for-woocommerce'),
                            "type"      => "text",
                            "name"      => array("style_settings", "fixed_position", "left"),
                            "value"     => '1',
                        ),
                        'right' => array(
                            "label_be_for"=> '<br>' . __('Right: ', 'pagination-styler-for-woocommerce'),
                            "type"      => "text",
                            "name"      => array("style_settings", "fixed_position", "right"),
                            "value"     => '1',
                        ),
                    ),
                    'tr_class' => 'berocket_pagination_type_blocks berocket_pagination_type_fixed',
                ),
                'buffer_top' => array(
                    "label"     => __('Text for dots Previous', 'pagination-styler-for-woocommerce'),
                    "type"      => "number",
                    "name"      => array("style_settings", "buffer_top"),
                    "extra"     => 'min="0"',
                    "tr_class"  => "berocket_pagination_type_blocks berocket_pagination_type_bottom",
                    "value"     => '',
                ),
                'bottom_position' => array(
                    "label"     => __('Padding from bottom', 'pagination-styler-for-woocommerce'),
                    "type"      => "number",
                    "name"      => array("style_settings", "bottom_position"),
                    "extra"     => 'min="0"',
                    "tr_class"  => "berocket_pagination_type_blocks berocket_pagination_type_bottom",
                    "value"     => '',
                ),
                'float' => array(
                    "label"    => __( 'Buttons orientation', "pagination-styler-for-woocommerce" ),
                    "name"     => array("style_settings", "ul_li_style", "float"),
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => 'left', 'text' => __('Horizontal', 'pagination-styler-for-woocommerce')),
                        array('value' => 'none', 'text' => __('Vertical', 'pagination-styler-for-woocommerce')),
                    ),
                    "value"    => '',
                ),
                'style_section' => array(
                    "label"     => "",
                    "section"   => 'style'
                ),
            ),
            'Text' => array(
                'dots_prev_icon' => array(
                    "label"     => __('Text for dots Previous', 'pagination-styler-for-woocommerce'),
                    "type"      => "fa",
                    "name"      => array("text_settings", "dots_prev_icon"),
                    "value"     => '1',
                ),
                'dots_prev_text' => array(
                    "label"     => '',
                    "type"      => "text",
                    "name"      => array("text_settings", "dots_prev_text"),
                    "value"     => '',
                    "label_for" => '<br>' . __( '%ICON% - use selected Font Awesome icon', 'pagination-styler-for-woocommerce' )
                ),
                'dots_next_icon' => array(
                    "label"     => __('Text for dots Next', 'pagination-styler-for-woocommerce'),
                    "type"      => "fa",
                    "name"      => array("text_settings", "dots_next_icon"),
                    "value"     => '1',
                ),
                'dots_next_text' => array(
                    "label"     => '',
                    "type"      => "text",
                    "name"      => array("text_settings", "dots_next_text"),
                    "value"     => '',
                    "label_for" => '<br>' . __( '%ICON% - use selected Font Awesome icon', 'pagination-styler-for-woocommerce' )
                ),
                'prev_icon' => array(
                    "label"     => __('Text for Previous button', 'pagination-styler-for-woocommerce'),
                    "type"      => "fa",
                    "name"      => array("text_settings", "prev_icon"),
                    "value"     => '1',
                ),
                'prev_text' => array(
                    "label"     => '',
                    "type"      => "text",
                    "name"      => array("text_settings", "prev_text"),
                    "value"     => '',
                    "label_for" => '<br>' . __( '%ICON% - use selected Font Awesome icon', 'pagination-styler-for-woocommerce' )
                ),
                'next_icon' => array(
                    "label"     => __('Text for Next button', 'pagination-styler-for-woocommerce'),
                    "type"      => "fa",
                    "name"      => array("text_settings", "next_icon"),
                    "value"     => '1',
                ),
                'next_text' => array(
                    "label"     => '',
                    "type"      => "text",
                    "name"      => array("text_settings", "next_text"),
                    "value"     => '',
                    "label_for" => '<br>' . __( '%ICON% - use selected Font Awesome icon', 'pagination-styler-for-woocommerce' )
                ),
                'current_page' => array(
                    "label"     => __('Text for current page', 'pagination-styler-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "current_page"),
                    "value"     => '',
                    "label_for" => '<br>' . __( '%PAGE% - Page number', 'pagination-styler-for-woocommerce' )
                ),
                'page' => array(
                    "label"     => __('Text for page', 'pagination-styler-for-woocommerce'),
                    "type"      => "text",
                    "name"      => array("text_settings", "page"),
                    "value"     => '',
                    "label_for" => '<br>' . __( '%PAGE% - Page number', 'pagination-styler-for-woocommerce' )
                ),
                'first_page_icon' => array(
                    "label"     => __('Text for first page', 'pagination-styler-for-woocommerce'),
                    "type"      => "fa",
                    "name"      => array("text_settings", "first_page_icon"),
                    "value"     => '1',
                ),
                'first_page' => array(
                    "label"     => '',
                    "type"      => "text",
                    "name"      => array("text_settings", "first_page"),
                    "value"     => '',
                    "label_for" => '<br>' . __( '%LAST% - Last page number. %ICON% - use selected Font Awesome icon', 'pagination-styler-for-woocommerce' )
                ),
                'last_page_icon' => array(
                    "label"     => __('Text for last page', 'pagination-styler-for-woocommerce'),
                    "type"      => "fa",
                    "name"      => array("text_settings", "last_page_icon"),
                    "value"     => '1',
                ),
                'last_page' => array(
                    "label"     => '',
                    "type"      => "text",
                    "name"      => array("text_settings", "last_page"),
                    "value"     => '',
                    "label_for" => '<br>' . __( '%LAST% - Last page number. %ICON% - use selected Font Awesome icon', 'pagination-styler-for-woocommerce' )
                ),
            ),
            'Custom CSS/JavaScript' => array(
                'global_font_awesome_disable' => array(
                    "label"     => __( 'Disable Font Awesome', "pagination-styler-for-woocommerce" ),
                    "type"      => "checkbox",
                    "name"      => "fontawesome_frontend_disable",
                    "value"     => '1',
                    'label_for' => __('Don\'t load Font Awesome css files on site front end. Use it only if you don\'t use Font Awesome icons in widgets or your theme has Font Awesome.', 'pagination-styler-for-woocommerce'),
                ),
                'global_fontawesome_version' => array(
                    "label"    => __( 'Font Awesome Version', "pagination-styler-for-woocommerce" ),
                    "name"     => "fontawesome_frontend_version",
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => '', 'text' => __('Font Awesome 4', 'pagination-styler-for-woocommerce')),
                        array('value' => 'fontawesome5', 'text' => __('Font Awesome 5', 'pagination-styler-for-woocommerce')),
                    ),
                    "value"    => '',
                    "label_for" => __('Version of Font Awesome that will be used on front end. Please select version that you have in your theme', 'pagination-styler-for-woocommerce'),
                ),
                array(
                    "label"   => __("Custom CSS", 'pagination-styler-for-woocommerce'),
                    "name"    => array("javascript_settings", "custom_css"),
                    "type"    => "textarea",
                    "value"   => "",
                ),
                array(
                    "label"   => __("JavaScript on Page Load", 'pagination-styler-for-woocommerce'),
                    "name"    => array("javascript_settings", "page_load"),
                    "type"    => "textarea",
                    "value"   => "",
                ),
            ),
        ) );
    }
    public function section_style($data, $options_global) {
        ob_start();
        $options = br_get_value_from_array($options_global, 'style_settings');
        $settings_name = $this->values['settings_name'];
        include('templates/style_section.php');
        return '<td colspan="2">' . ob_get_clean() . '</td>';
    }

    public function option_page_capability($capability = '') {
        return 'manage_berocket_pagination_styler';
    }
}

new BeRocket_Pagination;
