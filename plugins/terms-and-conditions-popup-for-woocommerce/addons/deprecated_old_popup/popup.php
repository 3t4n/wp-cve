<?php
class BeRocket_terms_cond_popup_deprecated extends BeRocket_plugin_variations  {
    public $plugin_name = 'terms_cond_popup';
    public $version_number = 5;
    function __construct() {
        $this->info = array(
            'id'          => 13,
            'lic_id'      => 77,
            'version'     => BeRocket_terms_cond_popup_version,
            'plugin_name' => 'terms_cond_popup',
            'domain'      => 'terms-and-conditions-popup-for-woocommerce',
            'templates'   => terms_cond_popup_TEMPLATE_PATH,
        );
        $this->values = array(
            'settings_name' => 'br-terms_cond_popup-options',
            'option_page'   => 'br-terms_cond_popup',
            'premium_slug'  => 'woocommerce-terms-and-conditions-popup',
            'free_slug'     => 'terms-and-conditions-popup-for-woocommerce',
        );
        $this->default = array();
        parent::__construct();
        add_action( 'wp_head', array( $this, 'set_styles' ) );
        add_filter( 'berocket_terms_cond_add_popup', array($this, 'wp_enqueue_scripts'), 10, 3 );
    }
    function settings_tabs($tabs) {
        $tabs = berocket_insert_to_array($tabs, 'General', array(
            'Styles' => array(
                'icon' => 'eye',
            ),
        ));
        return $tabs;
    }
    function settings_page($data) {
        $data['Styles'] = array(
            'height_paddings' => array(
                "label"     => __('Height paddings', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "text",
                "name"      => array("styles", "height_paddings"),
                "value"     => '',
            ),
            'width_paddings' => array(
                "label"     => __('Width paddings', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "text",
                "name"      => array("styles", "width_paddings"),
                "value"     => '',
            ),
            'border_width' => array(
                "label"     => __('Border width', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "text",
                "name"      => array("styles", "border_width"),
                "value"     => '',
            ),
            'border_color' => array(
                "label"     => __('Border color', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "border_color"),
                "value"     => '#000000',
            ),
            'back_color' => array(
                "label"     => __('Background color', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "back_color"),
                "value"     => '#000000',
            ),
            'title_back_color' => array(
                "label"     => __('Title background color', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "title_back_color"),
                "value"     => '#ffffff',
            ),
            'title_font_color' => array(
                "label"     => __('Title font color', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "title_font_color"),
                "value"     => '#333333',
            ),
            'title_height' => array(
                "label"     => __('Title height', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "number",
                "name"      => array("styles", "title_height"),
                "value"     => '',
            ),
            'close_size' => array(
                "label"     => __('Close button size', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "text",
                "name"      => array("styles", "close_size"),
                "value"     => '',
            ),
            'close_color' => array(
                "label"     => __('Close button color', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "close_color"),
                "value"     => '#333333',
            ),
            'close_color_hover' => array(
                "label"     => __('Close button color Hover', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "close_color_hover"),
                "value"     => '#555555',
            ),
            'content_back_color' => array(
                "label"     => __('Content background color', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "content_back_color"),
                "value"     => '#ffffff',
            ),
            'content_font_color' => array(
                "label"     => __('Content font color', 'terms-and-conditions-popup-for-woocommerce'),
                "type"      => "color",
                "name"      => array("styles", "content_font_color"),
                "value"     => '#333333',
            )
        );
        return $data;
    }
    public function set_styles() {
        $BeRocket_terms_cond_popup = BeRocket_terms_cond_popup::getInstance();
        $options = $BeRocket_terms_cond_popup->get_option();
        echo '<style>';
        echo '.br_terms_cond_popup_window {';
        if(! empty($options['styles']['border_width'])) {
            echo 'border-width:'.$options['styles']['border_width'].'px;';
        }
        if(! empty($options['styles']['border_color'])) {
            echo 'border-color:'.($options['styles']['border_color'][0] != '#' ? '#' : '').$options['styles']['border_color'].';';
        }
        echo 'border-style:solid;}';
        echo '.br_terms_cond_popup_window_bg{';
        if(! empty($options['styles']['back_color'])) {
            echo 'background-color:'.($options['styles']['back_color'][0] != '#' ? '#' : '').$options['styles']['back_color'].'!important;';
        }
        echo '}';
        echo '.br_terms_cond_popup_window #TB_title {';
        if(! empty($options['styles']['title_back_color'])) {
            echo 'background-color:'.($options['styles']['title_back_color'][0] != '#' ? '#' : '').$options['styles']['title_back_color'].'!important;';
        }
        if(! empty($options['styles']['title_font_color'])) {
            echo 'color:'.($options['styles']['title_font_color'][0] != '#' ? '#' : '').$options['styles']['title_font_color'].'!important;';
        }
        if(isset($options['styles']['title_height'])) {
            echo 'height:'.$options['styles']['title_height'].'px;';
        }
        if(! empty($options['styles']['title_font_size'])) {
            echo 'font-size:'.$options['styles']['title_font_size'].'px;';
            echo 'line-height:'.$options['styles']['title_font_size'].'px;';
        }
        echo '}';
        echo '.br_terms_cond_popup_window #TB_title #TB_ajaxWindowTitle{line-height: inherit;';
        if(isset($options['styles']['close_size']) && is_numeric($options['styles']['close_size'])) {
            echo 'width: calc(100% - '.($options['styles']['close_size']*1.25).'px)!important;';
        }
        echo '}';
        echo '.br_terms_cond_popup_window #TB_title #TB_closeWindowButton{';
        if(isset($options['styles']['close_size'])) {
            echo 'height:'.$options['styles']['close_size'].'px!important;';
            echo 'width:'.$options['styles']['close_size'].'px!important;';
            echo 'line-height:'.$options['styles']['close_size'].'px!important;';
        }
        echo '}';
        echo '.br_terms_cond_popup_window #TB_title #TB_closeWindowButton,
        .br_terms_cond_popup_window #TB_title #TB_closeWindowButton .tb-close-icon,
        .br_terms_cond_popup_window #TB_title #TB_closeWindowButton .tb-close-icon:before,
        .br_terms_cond_popup_window #TB_title .br_timer{';
        if(isset($options['styles']['close_size'])) {
            echo 'height:'.$options['styles']['close_size'].'px!important;';
            echo 'width:'.$options['styles']['close_size'].'px!important;';
            echo 'line-height:'.$options['styles']['close_size'].'px!important;';
            echo 'font-size:'.$options['styles']['close_size'].'px!important;';
        }
        if(! empty($options['styles']['close_color'])) {
            echo 'color:'.($options['styles']['close_color'][0] != '#' ? '#' : '').$options['styles']['close_color'].'!important;';
        }
        echo '}';
        echo '.br_terms_cond_popup_window #TB_title #TB_closeWindowButton:hover,
        .br_terms_cond_popup_window #TB_title #TB_closeWindowButton .tb-close-icon:hover,
        .br_terms_cond_popup_window #TB_title #TB_closeWindowButton .tb-close-icon:hover:before{';
        if(! empty($options['styles']['close_color_hover'])) {
            echo 'color:'.($options['styles']['close_color_hover'][0] != '#' ? '#' : '').$options['styles']['close_color_hover'].'!important;';
        }
        echo '}';
        echo '.br_terms_cond_popup_window #TB_ajaxContent {width: initial!important;';
        if(! empty($options['styles']['content_back_color'])) {
            echo 'background-color:'.($options['styles']['content_back_color'][0] != '#' ? '#' : '').$options['styles']['content_back_color'].'!important;';
        }
        if(! empty($options['styles']['content_font_color'])) {
            echo 'color:'.($options['styles']['content_font_color'][0] != '#' ? '#' : '').$options['styles']['content_font_color'].'!important;';
        }
        echo '}';
        echo '</style>';
    }
    public function wp_enqueue_scripts($add_popup, $page, $options) {
        $BeRocket_terms_cond_popup = BeRocket_terms_cond_popup::getInstance();
        $content = $page->post_content;
        $content = apply_filters( 'br_terms_cond_the_content', $content );
        set_query_var( 'popup_id', 'br-woocommerce-terms-conditions-popup' );
        $content = $page->post_content;
        $content = apply_filters( 'br_terms_cond_the_content', $content );
        if( $options['agree_button'] ) {
            $content .= '<div><input type="submit" class="br-woocommerce-terms-conditions-popup-agree ' . $options['agree_class'] . '" data-type="agree" value="' . __( 'Agree', 'terms-and-conditions-popup-for-woocommerce' ) . '">';

            $content .= '<input type="submit" class="br-woocommerce-terms-conditions-popup-agree ' . $options['decline_class'] . '" data-type="decline" value="' . __( 'Decline', 'terms-and-conditions-popup-for-woocommerce' ) . '"></div>';
        }
        set_query_var( 'content', $content );
        $BeRocket_terms_cond_popup->br_get_template_part('popup');
        add_thickbox();
        wp_enqueue_script( 'berocket_terms_cond_popup_main', 
            plugins_url( '/frontend.js', __FILE__ ), 
            array( 'jquery' ), 
            BeRocket_terms_cond_popup_version );
        wp_localize_script(
            'berocket_terms_cond_popup_main',
            'the_terms_cond_popup_js_data',
            array(
                'id'            => 'br-woocommerce-terms-conditions-popup',
                'title'         => $page->post_title,
                'agree_button'  => $options['agree_button'],
                'popup_width'   => str_replace(array('px', '%'), '', $options['popup_width']),
                'popup_height'  => str_replace(array('px', '%'), '', $options['popup_height']),
                'timer'         => $options['timer'],
                'checkbox_rm'   => $options['agree_checkbox_remove'],
                'styles'        => array(
                    'height_paddings' => @ $options['styles']['height_paddings'],
                    'width_paddings'  => @ $options['styles']['width_paddings']
                ),
            )
        );
        return false;
    }
}
new BeRocket_terms_cond_popup_deprecated();
