<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://anssilaitila.fi
 * @since      1.0.0
 *
 * @package    Contact_List
 * @subpackage Contact_List/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contact_List
 * @subpackage Contact_List/public
 * @author     Anssi Laitila <anssi.laitila@gmail.com>
 */
class Contact_List_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles( $hook )
    {
        wp_enqueue_style(
            $this->plugin_name,
            CONTACT_LIST_URI . 'dist/css/p.css',
            array(),
            $this->version,
            'all'
        );
        wp_enqueue_style(
            $this->plugin_name . '-font-awesome',
            CONTACT_LIST_URI . 'dist/font-awesome-4.7.0/css/font-awesome.min.css',
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts( $hook )
    {
        $s = get_option( 'contact_list_settings' );
        $settings = array(
            'focus_on_search_field' => ( isset( $s['focus_on_search_field'] ) && $s['focus_on_search_field'] ? 1 : 0 ),
        );
        $is_premium = 0;
        if ( !$is_premium ) {
            wp_register_script(
                $this->plugin_name,
                esc_url( CONTACT_LIST_URI ) . 'dist/js/p.js',
                array( 'jquery' ),
                $this->version,
                false
            );
        }
        wp_localize_script( $this->plugin_name, 'contact_list_settings', $settings );
        wp_enqueue_script( $this->plugin_name );
    }
    
    /**
     * Register the shortcodes.
     *
     * @since    1.0.0
     */
    public function register_shortcodes()
    {
        add_shortcode( 'contact_list', array( 'Contact_List_Public', 'contact_list' ) );
        add_shortcode( 'contact_list_groups', array( 'Contact_List_Public', 'contact_list_groups' ) );
        add_shortcode( 'contact_list_form', array( 'Contact_List_Public', 'contact_list_form' ) );
        add_shortcode( 'contact_list_search', array( 'Contact_List_Public', 'contact_list_search' ) );
        add_shortcode( 'contact_list_simple', array( 'Contact_List_Public', 'contact_list_simple' ) );
        add_shortcode( 'contact_list_simple_groups', array( 'Contact_List_Public', 'contact_list_simple_groups' ) );
        add_shortcode( 'contact_list_send_email', array( 'Contact_List_Public', 'contact_list_send_email' ) );
    }
    
    /**
     * Public contact list view.
     *
     * @since    1.0.0
     */
    public static function contact_list( $atts = array(), $content = null, $tag = '' )
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
        $html = '';
        $html .= ShortcodeContactList::shortcodeContactListMarkup( $atts );
        return $html;
    }
    
    /**
     * Public groups list view.
     *
     * @since    2.0.0
     */
    public static function contact_list_groups( $atts = array(), $content = null, $tag = '' )
    {
        // normalize attribute keys, lowercase
        $atts = array_change_key_case( (array) $atts, CASE_LOWER );
        $html = '';
        if ( ContactListHelpers::isPremium() == 0 ) {
            $html .= ContactListPublicHelpers::proFeaturePublicMarkup();
        }
        return $html;
    }
    
    /**
     * Public form
     *
     * @since    2.0.0
     */
    public static function contact_list_form()
    {
        $html = '';
        if ( ContactListHelpers::isPremium() == 0 ) {
            $html .= ContactListPublicHelpers::proFeaturePublicMarkup();
        }
        return $html;
    }
    
    public static function contact_list_search( $atts = array(), $content = null, $tag = '' )
    {
        $html = '';
        if ( ContactListHelpers::isPremium() == 0 ) {
            $html .= ContactListPublicHelpers::proFeaturePublicMarkup();
        }
        return $html;
    }
    
    public static function contact_list_simple( $atts = array(), $content = null, $tag = '' )
    {
        $html = '';
        $html .= ShortcodeContactListSimple::view( $atts );
        return $html;
    }
    
    public static function contact_list_simple_groups( $atts = array(), $content = null, $tag = '' )
    {
        $html = '';
        if ( ContactListHelpers::isPremium() == 0 ) {
            $html .= ContactListPublicHelpers::proFeaturePublicMarkup();
        }
        return $html;
    }
    
    public static function contact_list_send_email( $atts = array(), $content = null, $tag = '' )
    {
        $html = '';
        if ( ContactListHelpers::isPremium() == 0 ) {
            $html .= ContactListPublicHelpers::proFeaturePublicMarkup();
        }
        return $html;
    }

}