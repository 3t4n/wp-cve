<?php

/**
 * Invoice. Register custom post type.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore
 */
namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress;

use WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable;
/**
 * Register custom post types.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class RegisterPostType implements \WPDeskFIVendor\WPDesk\PluginBuilder\Plugin\Hookable
{
    const POST_TYPE_NAME = 'inspire_invoice';
    const POST_TYPE_MENU_URL = 'edit.php?post_type=' . self::POST_TYPE_NAME;
    /**
     * @var PostTypeCapabilities
     */
    private $capabilities;
    /**
     * @param PostTypeCapabilities $capabilities
     */
    public function __construct(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities $capabilities)
    {
        $this->capabilities = $capabilities;
    }
    /**
     * Fires hooks.
     */
    public function hooks()
    {
        \add_action('init', [$this, 'register_post_type_action']);
    }
    /**
     * Get post type args.
     *
     * @return array
     */
    private function get_post_type_args() : array
    {
        global $menu;
        $menu_pos = 56.8673974;
        while (isset($menu[$menu_pos])) {
            $menu_pos++;
        }
        /**
         * Filters the has_archive arg for the register_post_type function.
         *
         * @param false
         */
        $has_archive = \apply_filters('fi/core/register_post_type/has_archive', \false);
        /**
         * Filters the can_export arg for the register_post_type function.
         *
         * @param false
         */
        $can_export = \apply_filters('fi/core/register_post_type/can_export', \false);
        return ['label' => 'inspire_invoice', 'description' => \esc_html__('Invoices', 'flexible-invoices'), 'labels' => ['name' => \esc_html__('Invoices', 'flexible-invoices'), 'singular_name' => \esc_html__('Invoice', 'flexible-invoices'), 'menu_name' => \esc_html__('Invoices', 'flexible-invoices'), 'parent_item_colon' => '', 'all_items' => \esc_html__('All Invoices', 'flexible-invoices'), 'view_item' => \esc_html__('View Invoice', 'flexible-invoices'), 'add_new_item' => \esc_html__('Add New Invoice', 'flexible-invoices'), 'add_new' => \esc_html__('Add New', 'flexible-invoices'), 'edit_item' => \esc_html__('Edit Invoice', 'flexible-invoices'), 'update_item' => \esc_html__('Save Invoice', 'flexible-invoices'), 'search_items' => \esc_html__('Search Invoices', 'flexible-invoices'), 'not_found' => \esc_html__('No invoices found.', 'flexible-invoices'), 'not_found_in_trash' => \esc_html__('No invoices found in Trash.', 'flexible-invoices')], 'supports' => ['title'], 'taxonomies' => [], 'hierarchical' => \false, 'public' => \false, 'show_ui' => \true, 'show_in_menu' => \true, 'show_in_nav_menus' => \false, 'show_in_admin_bar' => \true, 'menu_position' => $menu_pos, 'menu_icon' => 'dashicons-media-spreadsheet', 'can_export' => $can_export, 'has_archive' => $has_archive, 'exclude_from_search' => \true, 'publicly_queryable' => \false, 'capability_type' => [\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities::CAPABILITY_SINGULAR, \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\PostTypeCapabilities::CAPABILITY_PLURAL], 'map_meta_cap' => \false, 'cap' => $this->capabilities->get_post_capability_map_as_object()];
    }
    /**
     * @return void
     *
     * @internal You should not use this directly from another application
     */
    public function register_post_type_action()
    {
        \register_post_type(self::POST_TYPE_NAME, $this->get_post_type_args());
    }
}
