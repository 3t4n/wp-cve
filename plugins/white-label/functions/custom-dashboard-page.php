<?php

/**
 *  Custom WP Dashboard.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class White_Label_Custom_Dashboard
{
    protected $capability = 'read'; // Allows everyone to see the page.
    protected $title;

    public function __construct()
    {
        if (!is_admin()) {
            return;
        }

        $enable_custom_dashboard = white_label_get_option('admin_enable_custom_dashboard', 'white_label_dashboard', false);

        if ($enable_custom_dashboard === 'on' && !isset($_GET['action'])) {
            add_action('init', [$this, 'init'], 90);
        }
    }

    public function init()
    {
        if (current_user_can($this->capability)) {
            $this->set_title();
            add_filter('admin_title', [$this, 'admin_title'], 10, 2);
            add_action('admin_menu', [$this, 'admin_menu']);
            add_action('current_screen', [$this, 'current_screen']);
        }
    }

    // Custom page title.
    public function set_title()
    {
        if (!isset($this->title)) {
            $this->title = __('Dashboard', 'white-label');
        }
    }

    // Generate the custom page.
    public function page_content()
    {

        $content = do_shortcode(stripslashes(white_label_get_option('admin_custom_dashboard_content', 'white_label_dashboard', false)));
        $content = wpautop($content);

        echo <<<HTML
<div class="wrap">
    <h2>{$this->title}</h2>
      <div class="white-label-page" style="background:#ffffff;padding:1px 30px;">
        <div class="white-label-col-1">
          <p>{$content}</p>
        </div>
      </div>
    </div>
HTML;
    }

    public function admin_title($admin_title, $title)
    {
        global $pagenow;
        if ('admin.php' == $pagenow && isset($_GET['page']) && 'my-dashboard' == $_GET['page']) {
            $admin_title = $this->title.$admin_title;
        }
        return $admin_title;
    }

    public function admin_menu()
    {
        // Add our custom page.
        add_menu_page($this->title, '', 'read', 'my-dashboard', [$this, 'page_content']);

        // Hide it from the menu.
        remove_menu_page('my-dashboard');

        // Make dashboard menu item the active item/
        global $parent_file, $submenu_file;
        $parent_file = 'index.php';
        $submenu_file = 'index.php';

        // rename the dashboard.
        global $menu;
        $menu[2][0] = $this->title;

        // Rename the dashboard submenu item.
        global $submenu;
        $submenu['index.php'][0][0] = $this->title;
    }

    // Redirect users to our new dashboard from the old one.
    public function current_screen($screen)
    {
        if ('dashboard' == $screen->id) {
            wp_safe_redirect(admin_url('admin.php?page=my-dashboard'));
            exit;
        }
    }
}

new White_Label_Custom_Dashboard();
