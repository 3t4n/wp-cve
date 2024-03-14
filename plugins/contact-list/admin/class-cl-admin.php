<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://anssilaitila.fi
 * @since      1.0.0
 *
 * @package    Contact_List
 * @subpackage Contact_List/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Contact_List
 * @subpackage Contact_List/admin
 * @author     Anssi Laitila <anssi.laitila@gmail.com>
 */
class Contact_List_Admin
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
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    public function enqueue_styles( $hook )
    {
        $current_screen = get_current_screen();
        $current_screen_id = '';
        if ( isset( $current_screen->id ) ) {
            $current_screen_id = $current_screen->id;
        }
        $inline_css = ContactListAdminInlineStyles::generateInlineStyles();
        wp_enqueue_style(
            $this->plugin_name,
            CONTACT_LIST_URI . 'dist/css/a.css',
            array(),
            $this->version,
            'all'
        );
        wp_add_inline_style( $this->plugin_name, $inline_css );
        
        if ( $hook === 'edit-tags.php' || $hook === 'contact_page_contact-list-shortcodes' || $hook === 'contact_page_contact-list-support' ) {
            wp_enqueue_style(
                $this->plugin_name . '-tipso',
                CONTACT_LIST_URI . 'dist/tipso.min.css',
                array(),
                $this->version,
                'all'
            );
        } elseif ( $current_screen_id == 'contact' && ($hook === 'post-new.php' || 'post.php') ) {
            $contact_edit_css = ContactListAdminInlineStyles::contactEditStyles();
            wp_enqueue_style(
                $this->plugin_name . '-tipso',
                CONTACT_LIST_URI . 'dist/tipso.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_add_inline_style( $this->plugin_name, $contact_edit_css );
        } elseif ( $current_screen_id === 'edit-contact' ) {
            wp_enqueue_style(
                $this->plugin_name . '-tipso',
                CONTACT_LIST_URI . 'dist/tipso.min.css',
                array(),
                $this->version,
                'all'
            );
        } elseif ( $hook == 'contact_page_contact-list-printable' ) {
            // WP admin / Contact List / Printable list
            $printable_inline_css = ContactListAdminInlineStyles::printableListStyles();
            wp_enqueue_style(
                $this->plugin_name . '-printable',
                CONTACT_LIST_URI . 'dist/css/p.css',
                array(),
                $this->version,
                'all'
            );
            wp_add_inline_style( $this->plugin_name . '-printable', $printable_inline_css );
        } elseif ( $hook == 'settings_page_contact-list' ) {
            wp_enqueue_style(
                $this->plugin_name . '-font-awesome',
                CONTACT_LIST_URI . 'dist/font-awesome-4.7.0/css/font-awesome.min.css',
                array(),
                $this->version,
                'all'
            );
        }
    
    }
    
    public function enqueue_scripts( $hook )
    {
        $current_screen = get_current_screen();
        $current_screen_id = '';
        if ( isset( $current_screen->id ) ) {
            $current_screen_id = $current_screen->id;
        }
        $is_premium = 0;
        if ( !$is_premium ) {
            wp_enqueue_script(
                $this->plugin_name,
                CONTACT_LIST_URI . 'dist/js/a.js',
                array( 'jquery' ),
                $this->version,
                false
            );
        }
        
        if ( $hook === 'edit-tags.php' ) {
            wp_enqueue_script(
                $this->plugin_name . '-tipso',
                CONTACT_LIST_URI . 'dist/tipso.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name . '-clipboard',
                '/wp-includes/js/clipboard.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            $inline_js = ContactListAdminInlineScripts::inline_scripts();
            wp_add_inline_script( $this->plugin_name, $inline_js );
        } elseif ( $hook === 'contact_page_contact-list-shortcodes' ) {
            wp_enqueue_script(
                $this->plugin_name . '-tipso',
                CONTACT_LIST_URI . 'dist/tipso.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name . '-clipboard',
                '/wp-includes/js/clipboard.js',
                array( 'jquery' ),
                $this->version,
                true
            );
        } elseif ( $hook === 'contact_page_contact-list-support' ) {
            wp_enqueue_script(
                $this->plugin_name . '-tipso',
                CONTACT_LIST_URI . 'dist/tipso.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name . '-clipboard',
                '/wp-includes/js/clipboard.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            $inline_js = ContactListAdminInlineScripts::help_support_scripts();
            wp_add_inline_script( $this->plugin_name, $inline_js );
        } elseif ( $current_screen_id === 'edit-contact' ) {
            $inline_js = ContactListAdminInlineScripts::inline_scripts();
            wp_add_inline_script( $this->plugin_name, $inline_js );
            wp_enqueue_script(
                $this->plugin_name . '-tipso',
                CONTACT_LIST_URI . 'dist/tipso.min.js',
                array( 'jquery' ),
                $this->version,
                true
            );
            wp_enqueue_script(
                $this->plugin_name . '-clipboard',
                '/wp-includes/js/clipboard.js',
                array( 'jquery' ),
                $this->version,
                true
            );
        } elseif ( $current_screen_id == 'contact' && ($hook === 'post-new.php' || 'post.php') ) {
            $inline_js = ContactListAdminInlineScripts::contact_edit_scripts();
            wp_add_inline_script( $this->plugin_name, $inline_js );
        } elseif ( $hook == 'contact_page_contact-list-mail-log' ) {
            $inline_js = ContactListAdminInlineScripts::inline_scripts( 'mail-log' );
            wp_add_inline_script( $this->plugin_name, $inline_js );
        }
    
    }

}