<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Admin;

use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Page_Tab_Interface;
use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Settings_Admin;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
class Dotdigital_WordPress_Admin
{
    /**
     * The ID of this plugin.
     *
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    /**
     * The available page tabs.
     *
     * @var      array    $available_page_tabs    The available page tabs.
     */
    private $available_page_tabs = array(\Dotdigital_WordPress\Admin\Page\Tab\Dotdigital_WordPress_About_Admin::class, \Dotdigital_WordPress\Admin\Page\Tab\Dotdigital_WordPress_Credentials_Admin::class, \Dotdigital_WordPress\Admin\Page\Tab\Dotdigital_WordPress_Lists_Admin::class, \Dotdigital_WordPress\Admin\Page\Tab\Dotdigital_WordPress_Datafields_Admin::class, \Dotdigital_WordPress\Admin\Page\Tab\Dotdigital_WordPress_Messages_Admin::class, \Dotdigital_WordPress\Admin\Page\Tab\Dotdigital_WordPress_Redirects_Admin::class);
    /**
     * The page tabs.
     *
     * @var      array    $page_tabs    The page tabs.
     */
    public $page_tabs = array();
    /**
     * Initialize the class and set its properties.
     *
     * @param      string $plugin_name       The name of this plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        global $dotdigital;
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->page_tabs =& $dotdigital['admin']['view'];
    }
    /**
     * Register the stylesheets for the admin area.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/dotdigital-wordpress-admin.css', array(), $this->version, 'all');
    }
    /**
     * Register the JavaScript for the admin area.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/dotdigital-wordpress-admin.js', array('jquery', 'jquery-ui-core'), $this->version, \true);
    }
    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @return void
     */
    public function add_plugin_admin_menus()
    {
        $admin_settings_page = new Dotdigital_WordPress_Settings_Admin();
        add_menu_page(__('Dotdigital for WordPress'), __('Dotdigital for WordPress'), 'manage_options', $admin_settings_page->get_slug(), array($admin_settings_page, 'render'), 'data:image/svg+xml;base64,' . DOTDIGITAL_WORDPRESS_PLUGIN_ICON);
        add_submenu_page(null, '', '', 'manage_options', 'dm_form_settings', array($admin_settings_page, 'legacy_redirect'));
    }
    /**
     * Render the tab links for the settings page.
     *
     * @return void
     */
    public function render_tab_links()
    {
        $admin_tabs = array();
        $active_tab = $this->get_active_tab_slug();
        $page_slug = (new Dotdigital_WordPress_Settings_Admin())->get_slug();
        $tabs = $this->page_tabs;
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/partials/dotdigital-wordpress-admin-settings-tab-links.php';
    }
    /**
     * Initialise the tab hooks.
     *
     * @return void
     */
    public function add_plugin_page_tabs()
    {
        foreach ($this->available_page_tabs as $page_tab) {
            if (!\is_a($page_tab, Dotdigital_WordPress_Page_Tab_Interface::class, \true)) {
                continue;
            }
            try {
                $tab = new $page_tab();
                $tab->initialise();
            } catch (\Exception $e) {
                \error_log($e);
                continue;
            }
            $this->page_tabs[$tab->get_slug()] = $tab;
        }
    }
    /**
     * Render the active tab for the settings page.
     *
     * @return void
     */
    public function render_active_tab()
    {
        $active_tab = $this->get_active_tab_slug();
        foreach ($this->page_tabs as $page_tab) {
            if ($page_tab->get_url_slug() === $active_tab) {
                $page_tab->render();
            }
        }
    }
    /**
     * Register the administration pages for this plugin.
     *
     * @return void
     */
    public function add_plugin_admin_page_actions()
    {
        add_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', array($this, 'display_notice'), 10, 2);
        add_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_tab_links', array($this, 'render_tab_links'));
        add_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_tabs', array($this, 'render_active_tab'));
    }
    /**
     * Trigger settings_error hook with the passed message and type
     *
     * @param string $message The message to display.
     * @param string $type   The type of message.
     *
     * @return void
     */
    public function display_notice(string $message, string $type)
    {
        add_settings_error(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notices', DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notices', $message, $type);
    }
    /**
     * Get the active tab slug.
     *
     * @return string
     */
    private function get_active_tab_slug() : string
    {
        return isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : Dotdigital_WordPress_Config::DEFAULT_TAB;
    }
    /**
     * @param array  $data
     * @param string $sort_order
     *
     * @return void
     */
    public static function sort(array &$data, string $sort_order = 'asc')
    {
        if ('asc' == $sort_order) {
            \uasort($data, array('self', 'dotdigital_item_sort_asc'));
        } elseif ('desc' == $sort_order) {
            \uasort($data, array('self', 'dotdigital_item_sort_desc'));
        }
    }
    /**
     * Sort ascending.
     *
     * @param object $a Object A.
     * @param object $b Object B.
     * @return int
     */
    private static function dotdigital_item_sort_asc(object $a, object $b)
    {
        if (!\method_exists($a, 'getName') || !\method_exists($b, 'getName')) {
            return 0;
        }
        $a_name = \strtolower($a->getName());
        $b_name = \strtolower($b->getName());
        if ($a_name === $b_name) {
            return 0;
        }
        return $a_name > $b_name ? 1 : -1;
    }
    /**
     * Sort descending.
     *
     * @param object $a Object A.
     * @param object $b Object B.
     * @return int
     */
    private static function dotdigital_item_sort_desc(object $a, object $b)
    {
        if (!\method_exists($a, 'getName') || !\method_exists($b, 'getName')) {
            return 0;
        }
        $a_name = \strtolower($a->getName());
        $b_name = \strtolower($b->getName());
        if ($a_name === $b_name) {
            return 0;
        }
        return $a_name > $b_name ? -1 : 1;
    }
}
