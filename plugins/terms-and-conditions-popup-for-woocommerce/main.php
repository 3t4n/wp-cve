<?php
define( "BeRocket_terms_cond_popup_domain", 'terms-and-conditions-popup-for-woocommerce'); 
define( "terms_cond_popup_TEMPLATE_PATH", plugin_dir_path( __FILE__ ) . "templates/" );
load_plugin_textdomain('terms-and-conditions-popup-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
require_once(plugin_dir_path( __FILE__ ).'berocket/framework.php');
foreach (glob(__DIR__ . "/includes/*.php") as $filename)
{
    include_once($filename);
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class BeRocket_terms_cond_popup extends BeRocket_Framework {
    public static $settings_name = 'br-terms_cond_popup-options';
    protected static $instance;
    public static $error_log = array();
    public static $debug_mode = FALSE;
    protected $disable_settings_for_admin = array(
        array('script', 'js_page_load'),
    );
    protected $check_init_array = array(
        array(
            'check' => 'woocommerce_version',
            'data' => array(
                'version' => '3.0',
                'operator' => '>=',
                'notice'   => 'Plugin WooCommerce Terms and Conditions Popup required WooCommerce version 3.0 or higher'
            )
        ),
        array(
            'check' => 'framework_version',
            'data' => array(
                'version' => '2.1',
                'operator' => '>=',
                'notice'   => 'Please update all BeRocket plugins to the most recent version. WooCommerce Terms and Conditions Popup is not working correctly with older versions.'
            )
        ),
    );
    function __construct () {
        $this->info = array(
            'id'          => 13,
            'lic_id'      => 77,
            'version'     => BeRocket_terms_cond_popup_version,
            'plugin'      => '',
            'slug'        => '',
            'key'         => '',
            'name'        => '',
            'plugin_name' => 'terms_cond_popup',
            'full_name'   => __('WooCommerce Terms and Conditions Popup', 'terms-and-conditions-popup-for-woocommerce'),
            'norm_name'   => __('Terms and Conditions', 'terms-and-conditions-popup-for-woocommerce'),
            'price'       => '',
            'domain'      => 'terms-and-conditions-popup-for-woocommerce',
            'templates'   => terms_cond_popup_TEMPLATE_PATH,
            'plugin_file' => BeRocket_terms_cond_popup_file,
            'plugin_dir'  => __DIR__,
        );
        $this->defaults = array(
            'agree_button'      => '',
            'popup_width'       => '',
            'popup_height'      => '',
            'timer'             => '',
            'agree_checkbox_remove' => '',
            'agree_class'       => '',
            'decline_class'     => '',
            'concat_content'    => '',
            'prevent_close_scroll'=> '',
            'styles'            => array(
                'content_back_color' => '#ffffff'
            ),
            'custom_css'        => '',
            'script'            => array(
                'js_page_load'      => '',
            ),
            'fontawesome_frontend_disable'    => '',
            'fontawesome_frontend_version'    => '',
        );
        $this->values = array(
            'settings_name' => 'br-terms_cond_popup-options',
            'option_page'   => 'br-terms_cond_popup',
            'premium_slug'  => 'woocommerce-terms-and-conditions-popup',
            'free_slug'     => 'terms-and-conditions-popup-for-woocommerce',
            'hpos_comp'     => true
        );
        $this->feature_list = array();
        $this->framework_data['fontawesome_frontend'] = true;
        $this->active_libraries = array('addons', 'popup', 'templates', 'feature');
        parent::__construct( $this );
        if( is_admin() ) {
            $this->check_previous_version();
        }
        if( $this->check_framework_version() ) {
            if ( $this->init_validation() ) {
                $options = $this->get_option();
                add_filter ( 'BeRocket_updater_menu_order_custom_post', array($this, 'menu_order_custom_post') );
                add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
                add_shortcode( 'br_terms_and_conditions', array( $this, 'shortcode' ) );
                add_action('wp_footer', array($this, 'wp_footer'));
                add_filter('berocket_terms_cond_pages_contents', array($this, 'get_terms_content_array'), 3);
                add_filter('berocket_terms_cond_pages_contents', array($this, 'get_policy_content_array'), 7);
                if( ! empty($options['prevent_close_scroll']) ) {
                    include_once(__DIR__ . '/libraries/prevent_close_scroll.php');
                }
                if( class_exists('BeRocket_updater') && property_exists('BeRocket_updater', 'debug_mode') ) {
                    self::$debug_mode = ! empty(BeRocket_updater::$debug_mode);
                }
                add_filter( 'BeRocket_updater_error_log', array( $this, 'add_error_log' ) );
            }
        } else {
            add_filter( 'berocket_display_additional_notices', array(
                $this,
                'old_framework_notice'
            ) );
        }
    }
    function check_previous_version() {
        $version_old = get_option('BeRocket_terms_cond_popup_version');
        $old_options = get_option( $this->values['settings_name'] );
        if( empty($version_old) && ! empty($old_options) && is_array($old_options) && count($old_options) ) {
            $options = $this->get_option();
            $options['addons'] = array('/deprecated_old_popup/deprecated_old_popup.php');
            update_option( $this->values[ 'settings_name' ], $options );
        }
        update_option('BeRocket_terms_cond_popup_version', $this->info['version']);
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
            'html'          => __('<strong>Please update all BeRocket plugins to the most recent version. WooCommerce Terms and Conditions Popup is not working correctly with older versions.</strong>', 'terms-and-conditions-popup-for-woocommerce'),
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
        remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_terms_and_conditions_page_content', 30 );
    }
    public function set_styles () {
        parent::set_styles();
    }
    public function admin_settings( $tabs_info = array(), $data = array() ) {
        parent::admin_settings(
            array(
                'General' => array(
                    'icon' => 'cog',
                ),
                'Templates' => array(
                    'icon' => 'files-o'
                ),
                'Advanced' => array(
                    'icon' => 'cogs',
                ),
                'Custom CSS' => array(
                    'icon' => 'css3'
                ),
                'Addons' => array(
                    'icon' => 'plus'
                ),
                'License' => array(
                    'icon' => 'unlock-alt',
                    'link' => admin_url( 'admin.php?page=berocket_account' )
                ),
            ),
            array(
            'General' => array(
                'agree_button' => array(
                    "label"     => __('Agree button on terms', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => "agree_button",
                    "value"     => '1',
                ),
                'agree_checkbox_remove' => array(
                    "label"     => __('Remove agree checkbox', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => "agree_checkbox_remove",
                    "value"     => '1',
                ),
                'popup_width' => array(
                    "label"     => __('Popup Width', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "text",
                    "name"      => "popup_width",
                    "value"     => '1',
                ),
                'popup_height' => array(
                    "label"     => __('Popup Height', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "text",
                    "name"      => "popup_height",
                    "value"     => '1',
                ),
                'timer' => array(
                    "label"     => __('Timer', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "number",
                    "name"      => "timer",
                    "value"     => '1',
                ),
                'agree_class' => array(
                    "label"     => __('Classes for agree button', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "text",
                    "name"      => "agree_class",
                    "value"     => '1',
                ),
                'decline_class' => array(
                    "label"     => __('Classes for decline button', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "text",
                    "name"      => "decline_class",
                    "value"     => '1',
                ),
                'shortcode' => array(
                    "label"     => "",
                    "section"   => 'shortcode'
                ),
            ),
            'Advanced' => array(
                'hide_body_scroll' => array(
                    "label"     => __('Hide main scroll', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => 'hide_body_scroll',
                    "value"     => '1',
                    'label_for' => __('Hide body scroll when popup is opened. This will make scroll experience better.', 'terms-and-conditions-popup-for-woocommerce'),
                ),
                'print_button' => array(
                    "label"     => __('Show print button', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => 'print_button',
                    "value"     => '1',
                    'label_for' => __('By clicking it user will be able to print(or save to PDF) popup content', 'terms-and-conditions-popup-for-woocommerce'),
                ),
                'prevent_close_scroll' => array(
                    "label"     => __('Scroll to close', 'terms-and-conditions-popup-for-woocommerce'),
                    "type"      => "checkbox",
                    "name"      => 'prevent_close_scroll',
                    "value"     => '1',
                    'label_for' => __('Close popup only when scrolled to the end', 'terms-and-conditions-popup-for-woocommerce'),
                ),
            ),
            'Custom CSS' => array(
                'global_font_awesome_disable' => array(
                    "label"     => __( 'Disable Font Awesome', "terms-and-conditions-popup-for-woocommerce" ),
                    "type"      => "checkbox",
                    "name"      => "fontawesome_frontend_disable",
                    "value"     => '1',
                    'label_for' => __('Don\'t load Font Awesome css files on site front end. Use it only if you don\'t use Font Awesome icons in widgets or your theme has Font Awesome.', 'terms-and-conditions-popup-for-woocommerce'),
                ),
                'global_fontawesome_version' => array(
                    "label"    => __( 'Font Awesome Version', "terms-and-conditions-popup-for-woocommerce" ),
                    "name"     => "fontawesome_frontend_version",
                    "type"     => "selectbox",
                    "options"  => array(
                        array('value' => '', 'text' => __('Font Awesome 4', 'terms-and-conditions-popup-for-woocommerce')),
                        array('value' => 'fontawesome5', 'text' => __('Font Awesome 5', 'terms-and-conditions-popup-for-woocommerce')),
                    ),
                    "value"    => '',
                    "label_for" => __('Version of Font Awesome that will be used on front end. Please select version that you have in your theme', 'terms-and-conditions-popup-for-woocommerce'),
                ),
                array(
                    "label"   => "Custom CSS",
                    "name"    => "custom_css",
                    "type"    => "textarea",
                    "value"   => "",
                ),
            ),
            'Addons' => array(
                'addons' => array(
                    "label"     => "",
                    "section"   => 'addons'
                ),
            ),
            'Templates' => array(
                'templates' => array(
                    "label"     => "",
                    "section"   => 'templates'
                ),
            )
        ) );
    }
    public function section_shortcode($html) {
        return '<th>Shortcode</th><td><strong>[br_terms_and_conditions]</strong> - ' . __('shortcode to add terms and conditions block', 'terms-and-conditions-popup-for-woocommerce') . '</td>';
    }
    public function wp_enqueue_scripts ($force = false) {
        if( (is_checkout() && ! is_order_received_page()) || $force ) {
            $options = self::get_option();
            //SET POPUP GLOBAL SETTINGS
            $theme_template = '';
            if( ! empty($options['template']) ) {
                $template_data = $this->libraries->libraries_class['templates']->get_active_template_info($options['template']);
                $theme_template = $template_data['class'];
                do_action('berocket_init_template_'.$this->info['plugin_name'], $options['template']);
            }
            $popup_options = array(
                'close_delay'      => '0',
                'theme'            => $theme_template,
                'hide_body_scroll' => empty( $options['hide_body_scroll'] ) ? false : true,
                'print_button'     => empty( $options['print_button'] ) ? false : true,
                'close_delay_text' => __('%s second(s) before close', 'terms-and-conditions-popup-for-woocommerce'),
            );
            if( ! empty($options['popup_width']) ) {
                if( is_numeric($options['popup_width']) ) {
                    $options['popup_width'] = $options['popup_width'].'px';
                }
                $popup_options['width'] = $options['popup_width'];
            }
            if( ! empty($options['popup_height']) ) {
                if( is_numeric($options['popup_height']) ) {
                    $options['popup_height'] = $options['popup_height'].'px';
                }
                $popup_options['height'] = $options['popup_height'];
            }
            if( ! empty($options['timer']) ) {
                $popup_options['close_delay'] = $options['timer'];
            }
            if( ! empty($options['agree_button']) ) {
                $popup_options['yes_no_buttons'] = array(
                    'show'          => true,
                    'yes_text'      => __('Accept', 'terms-and-conditions-popup-for-woocommerce'),
                    'no_text'       => __('Decline', 'terms-and-conditions-popup-for-woocommerce'),
                    'location'      => 'popup',
                    'yes_func'      => 'jQuery("#terms.woocommerce-form__input-checkbox").prop("checked", true).trigger("change");',
                    'no_func'       => 'jQuery("#terms.woocommerce-form__input-checkbox").prop("checked", false).trigger("change");',
                    'yes_classes'   => $options['agree_class'],
                    'no_classes'    => $options['decline_class'],
                );
            }
            if( ! empty( $options['print_button'] ) ) {
                $popup_options['print_button_text'] = __('Print', 'terms-and-conditions-popup-for-woocommerce');
            }

            //GET TERMS AND CONDITIONS PAGE DATA
            $popup_pages = apply_filters('berocket_terms_cond_pages_contents', array(), $options);
            if( self::$debug_mode ) {
                self::$error_log['1_settings']      = $options;
                self::$error_log['2_popup_options'] = $popup_options;
                self::$error_log['3_popup_pages']   = $popup_pages;
            }
            if( ! empty($popup_pages['term_cond_page']) && $popup_pages['term_cond_page']['page'] !== false && ! apply_filters('berocket_terms_cond_add_popup', true, $popup_pages['term_cond_page']['page'], $options) ) {
                return false;
            }
            //ADD POPUP TO THE PAGE
            foreach($popup_pages as $popup_id => $popup_page) {
                if( ! empty($popup_page['title']) && ! empty($popup_page['content']) ) {
                    $temp_popup_options = array_merge($popup_options, $popup_page['popup_options']);
                    $popup_id_generated = BeRocket_popup_display::add_popup($temp_popup_options, $popup_page['content'], $popup_page['popup_open']);
                    do_action('berocket_terms_cond_popup_created', $popup_id_generated, $popup_id, $popup_page, $temp_popup_options);
                }
            }
        }
    }
    public function get_terms_content_array($popup_pages) {
        $page_id = wc_get_page_id( "terms" );
        $page_data = $this->get_page_content_array($page_id);
        $page_data['popup_open']['click']['selector'] = '.woocommerce-terms-and-conditions-link';
        $popup_pages['term_cond_page'] = $page_data;
        return $popup_pages;
    }
    public function get_policy_content_array($popup_pages) {
        $page_id = wc_privacy_policy_page_id();
        $page_data = $this->get_page_content_array($page_id);
        $page_data['popup_open']['click']['selector'] = '.woocommerce-privacy-policy-link';
        $page_data['popup_options']['yes_no_buttons'] = array(
            'show' => false,
        );
        $popup_pages['policy_page'] = $page_data;
        return $popup_pages;
    }
    public function get_page_content_array(&$page_id = false) {
        $page_content = array('title' => '', 'content' => '', 'page' => false, 'popup_options' => array(), 'popup_open' => array('click' => array('type' => 'click', 'selector' => '')));
        if( ! empty( $page_id ) && $page_id > 0 ) {
            $page = get_post( $page_id );
            if( $page && 'publish' === $page->post_status && $page->post_content && ! has_shortcode( $page->post_content, 'woocommerce_checkout' ) ) {
                $page_content['page'] = $page;
                $content = $page->post_content;
                $content = apply_filters( 'br_terms_cond_the_content', $content );
                $content = $this->convert_content($content);
                $page_content['content'] = $content;
                $page_content['title'] = $page->post_title;
                $page_content['popup_options']['title'] = $page->post_title;
            }
        }
        return $page_content;
    }
    public function convert_content($post_content) {
        global $wp_embed;
        $post_content = do_blocks($post_content);
        $post_content = $wp_embed->run_shortcode($post_content);
        $post_content = do_shortcode($post_content);
        $post_content = $wp_embed->autoembed($post_content);
        $post_content = wptexturize($post_content);
        $post_content = wpautop($post_content);
        $post_content = shortcode_unautop($post_content);
        $post_content = prepend_attachment($post_content);
        $wp_filter_content_tags = function_exists('wp_filter_content_tags') ? 'wp_filter_content_tags' : 'wp_make_content_images_responsive';
        $post_content = $wp_filter_content_tags($post_content);
        $post_content = convert_smilies($post_content);
        return $post_content;
    }
    public function shortcode($atts = array()) {
        $this->wp_enqueue_scripts(true);
        ob_start();
        echo '<div class="br_term_and_cond_shortcode">';
        wc_get_template( 'checkout/terms.php' );
        echo '</div>';
        return ob_get_clean();
    }
    public function menu_order_custom_post($compatibility) {
        $compatibility['br_popups'] = 'br-splash_popup';
        return $compatibility;
    }
    public function wp_footer() {
        $options = $this->get_option();
        if( ! empty($options['agree_checkbox_remove']) ) {
            echo '<script>
            jQuery(document).ready(function() {
                function berocket_terms_cond_hide_termcheck() {
                    jQuery( "#terms" ).hide();
                    jQuery(".terms label").attr("for", "");
                    jQuery(".woocommerce-terms-and-conditions-wrapper label").click(function(event) {
                        event.preventDefault();
                    });
                    jQuery(".woocommerce-terms-and-conditions").remove();
                }
                berocket_terms_cond_hide_termcheck();
                jQuery(document).ajaxComplete(function() {
                    berocket_terms_cond_hide_termcheck();
                });
            });
            </script>';
        }
    }
    public function add_error_log( $error_log ) {
        $error_log[plugin_basename( __FILE__ )] =  self::$error_log;
        return $error_log;
    }
}
new BeRocket_terms_cond_popup;
