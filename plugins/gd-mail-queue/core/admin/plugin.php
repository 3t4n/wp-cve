<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_admin_core extends d4p_admin_core {
    public $plugin = 'gd-mail-queue';

    public function __construct() {
        parent::__construct();

        $this->url = GDMAQ_URL;

        add_action('gdmaq_plugin_init', array($this, 'core'));

        add_filter('set-screen-option', array($this, 'screen_options_grid_rows_save'), 10, 3);
    }

    public function plugin_actions($links, $file) {
        if ($file == 'gd-mail-queue/gd-mail-queue.php' ){
            $settings_link = '<a href="admin.php?page=gd-mail-queue-settings">'.__("Settings", "gd-mail-queue").'</a>';
            array_unshift($links, $settings_link);

            $settings_link = '<a href="admin.php?page=gd-mail-queue-front">'.__("Dashboard", "gd-mail-queue").'</a>';
            array_unshift($links, $settings_link);
        }

        return $links;
    }

    function plugin_links($links, $file) {
        if ($file == 'gd-mail-queue/gd-mail-queue.php' ){
            $links[] = '<a target="_blank" style="color: #cc0000; font-weight: bold;" href="https://plugins.dev4press.com/gd-mail-queue/">'.__("Upgrade to GD Mail Queue Pro", "gd-mail-queue").'</a>';
        }

        return $links;
    }

    public function screen_options_grid_rows_save($status, $option, $value) {
        if ($option == 'gdmaq_rows_log_per_page') {
            return $value;
        }

        return $status;
    }

    public function screen_options_grid_rows_log() {
        $args = array(
            'label' => __("Rows", "gd-mail-queue"),
            'default' => 25,
            'option' => 'gdmaq_rows_log_per_page'
        );

        add_screen_option('per_page', $args);

        require_once(GDMAQ_PATH.'core/grids/log.php');

        new gdmaq_emails_log();
    }

    public function core() {
        parent::core();

        add_action('admin_menu', array($this, 'admin_menu'));

        $this->init_ready();

        if (gdmaq_settings()->is_install()) {
            add_action('admin_notices', array($this, 'install_notice'));
        }

        if (gdmaq_settings()->is_update()) {
            add_action('admin_notices', array($this, 'update_notice'));
        }

        if (gdmaq_settings()->get('show_coupon_36', 'core')) {
            add_action('admin_notices', array($this, 'show_coupon_notice'));
        }

        add_filter('plugin_action_links', array($this, 'plugin_actions'), 10, 2);
        add_filter('plugin_row_meta', array($this, 'plugin_links'), 10, 2);
    }

    public function install_notice() {
        if (current_user_can('install_plugins') && $this->page === false) {
            echo '<div class="updated"><p>';
            echo __("GD Mail Queue is activated and it needs to finish installation.", "gd-mail-queue");
            echo ' <a href="'.admin_url('admin.php?page=gd-mail-queue-front').'">'.__("Click Here", "gd-mail-queue").'</a>.';
            echo '</p></div>';
        }
    }

    public function update_notice() {
        if (current_user_can('install_plugins') && $this->page === false) {
            echo '<div class="updated"><p>';
            echo __("GD Mail Queue is updated and it needs to finish the update process.", "gd-mail-queue");
            echo ' <a href="'.admin_url('admin.php?page=gd-mail-queue-front').'">'.__("Click Here", "gd-mail-queue").'</a>.';
            echo '</p></div>';
        }
    }

    public function show_coupon_notice() {
        if (current_user_can('install_plugins') && $this->page !== false) {
            $web = parse_url(get_bloginfo('url'), PHP_URL_HOST);

            $url = 'https://plugins.dev4press.com/gd-mail-queue/';
            $url = add_query_arg('utm_source', $web, $url);
            $url = add_query_arg('utm_medium', 'plugin-gd-mail-queue-lite', $url);
            $url = add_query_arg('utm_campaign', 'upgrade-lite-to-pro', $url);

            echo '<div class="d4p-notice-info">';
            echo sprintf(__("Please, take a few minutes to check out GD Mail Queue Pro. If you decide to upgrade to Pro version, get %s discount with coupon %s.", "gd-mail-queue"), '<strong>10%</strong>', '<strong>MAILFREETOPRO</strong>');
            echo '<blockquote>'.__("Pro version includes support for third party SMTP servers to use with PHPMailer (AWS SES, Mailgun, Sendgrid, PepiPost, Mandrill...), and it has support for REST API email sending engines (Sendgrid, AWS SES, Gmail, Mailjet, Mailgun and SendInBlue with more to be added in the future). Pro version has enhanced dashboard, email log, tools and more.", "gd-mail-queue").'</blockquote>';
            echo '<a href="'.$url.'" class="button-primary">'.__("Plugin Home Page", "gd-mail-queue").'</a>';
            echo '<a style="float: right;" href="'.gdmaq_admin()->current_url(false).'&gdmaq_handler=getback&single-action=dismiss-lite-to-pro" class="button-secondary">'.__("Do not show this notice anymore", "gd-mail-queue").'</a>';
            echo '</div>';
        }
    }

    public function init_ready() {
        $this->menu_items = array(
            'front' => array('title' => __("Overview", "gd-mail-queue"), 'icon' => 'home'),
            'about' => array('title' => __("About", "gd-mail-queue"), 'icon' => 'info-circle'),
            'log' => array('title' => __("Log", "gd-mail-queue"), 'icon' => 'file-text-o'),
            'settings' => array('title' => __("Settings", "gd-mail-queue"), 'icon' => 'cogs'),
            'tools' => array('title' => __("Tools", "gd-mail-queue"), 'icon' => 'wrench')
        );
    }

    public function admin_init() {
        d4p_include('grid', 'admin', GDMAQ_D4PLIB);

        do_action('gdmaq_admin_init');
    }

    public function title() {
        return 'GD Mail Queue';
    }

    public function admin_menu() {
        $parent = 'gd-mail-queue-front';

        $this->page_ids[] = add_menu_page(
            'GD Mail Queue',
            'GD Mail Queue',
            gdmaq()->cap,
            $parent,
            array($this, 'panel_general'),
            gdmaq()->svg_icon);

        foreach($this->menu_items as $item => $data) {
            $this->page_ids[] = add_submenu_page($parent,
                'GD Mail Queue: '.$data['title'],
                $data['title'],
                gdmaq()->cap,
                'gd-mail-queue-'.$item,
                array($this, 'panel_general'));
        }

        $this->admin_load_hooks();
    }

    public function enqueue_scripts($hook) {
        $load_admin_data = false;

        if ($this->page !== false) {
            d4p_admin_enqueue_defaults();

            wp_enqueue_script('jquery-form');

            wp_enqueue_style('fontawesome', GDMAQ_URL.'d4plib/resources/fontawesome/css/font-awesome.min.css');

            wp_enqueue_style('d4plib-font', $this->file('css', 'font', true), array(), D4P_VERSION);
            wp_enqueue_style('d4plib-shared', $this->file('css', 'shared', true), array(), D4P_VERSION);
            wp_enqueue_style('d4plib-admin', $this->file('css', 'admin', true), array('d4plib-shared'), D4P_VERSION);
            wp_enqueue_style('d4plib-ctrl', $this->file('css', 'ctrl', true), array(), D4P_VERSION);

            wp_enqueue_script('d4plib-shared', $this->file('js', 'shared', true), array('jquery', 'wp-color-picker'), D4P_VERSION, true);
            wp_enqueue_script('d4plib-admin', $this->file('js', 'admin', true), array('d4plib-shared'), D4P_VERSION, true);
            wp_enqueue_script('d4plib-ctrl', $this->file('js', 'ctrl', true), array('jquery'), D4P_VERSION, true);

            wp_enqueue_style('gdmaq-plugin', $this->file('css', 'plugin'), array('d4plib-admin', 'd4plib-ctrl', 'wp-jquery-ui-dialog'), gdmaq_settings()->file_version());
            wp_enqueue_script('gdmaq-plugin', $this->file('js', 'plugin'), array('d4plib-admin', 'd4plib-ctrl', 'wpdialogs'), gdmaq_settings()->file_version(), true);

            if ($this->page == 'about') {
                wp_enqueue_style('d4plib-grid', $this->file('css', 'grid', true), array(), D4P_VERSION.'.'.D4P_BUILD);
            }

            $_data = array(
                'nonce' => wp_create_nonce('gdmaq-admin-internal'),
                'wp_version' => GDMAQ_WPV,
                'page' => $this->page,
                'panel' => $this->panel,
                'button_icon_ok' => '<i class="fa fa-check fa-fw" aria-hidden="true"></i> ',
                'button_icon_cancel' => '<i class="fa fa-times fa-fw" aria-hidden="true"></i> ',
                'button_icon_delete' => '<i class="fa fa-trash fa-fw" aria-hidden="true"></i> ',
                'dialog_button_ok' => _x("OK", "Dialog button", "gd-mail-queue"),
                'dialog_button_cancel' => _x("Cancel", "Dialog button", "gd-mail-queue"),
                'dialog_button_delete' => _x("Delete", "Dialog button", "gd-mail-queue"),
                'dialog_button_remove' => _x("Remove", "Dialog button", "gd-mail-queue"),
                'dialog_button_clear' => _x("Clear", "Dialog button", "gd-mail-queue"),
                'dialog_title_areyousure' => __("Are you sure you want to do this?", "gd-mail-queue"),
                'dialog_content_pleasewait' => __("Please Wait...", "gd-mail-queue")
            );

            wp_localize_script('gdmaq-plugin', 'gdmaq_admin_data', $_data);

            $load_admin_data = true;
        }

        if ($load_admin_data) {
            wp_localize_script('d4plib-shared', 'd4plib_admin_data', array(
                'string_media_image_title' => __("Select Image", "gd-mail-queue"),
                'string_media_image_button' => __("Use Selected Image", "gd-mail-queue"),
                'string_are_you_sure' => __("Are you sure you want to do this?", "gd-mail-queue"),
                'string_image_not_selected' => __("Image not selected.", "gd-mail-queue")
            ));
        }
    }

    public function admin_load_hooks() {
        foreach ($this->page_ids as $id) {
            add_action('load-'.$id, array($this, 'load_admin_page'));
        }

        add_action('load-gd-mail-queue_page_gd-mail-queue-log', array($this, 'screen_options_grid_rows_log'));
    }

    public function current_screen($screen) {
        if (isset($_GET['panel']) && $_GET['panel'] != '') {
            $this->panel = d4p_sanitize_slug($_GET['panel']);
        }

        $id = $screen->id;

        if ($id == 'toplevel_page_gd-mail-queue-front') {
            $this->page = 'front';
        } else if (substr($id, 0, 33) == 'gd-mail-queue_page_gd-mail-queue-') {
            $this->page = substr($id, 33);
        }

	    if (current_user_can('activate_plugins')) {
            if (isset($_POST['gdmaq_handler']) && $_POST['gdmaq_handler'] == 'postback') {
                require_once(GDMAQ_PATH.'core/admin/postback.php');

                new gdmaq_admin_postback();
            } else if (isset($_GET['gdmaq_handler']) && $_GET['gdmaq_handler'] == 'getback') {
                require_once(GDMAQ_PATH.'core/admin/getback.php');

                new gdmaq_admin_getback();
            }
        }
    }

    public function load_admin_page() {
        $this->help_tab_sidebar();

        do_action('gdmaq_load_admin_page_'.$this->page);

        if ($this->panel !== false && $this->panel != '') {
            do_action('gdmaq_load_admin_page_'.$this->page.'_'.$this->panel);
        }

        $this->help_tab_getting_help();
    }

    public function install_or_update() {
        $install = gdmaq_settings()->is_install();
        $update = gdmaq_settings()->is_update();

        if ($install) {
            include(GDMAQ_PATH.'forms/install.php');
        } else if ($update) {
            include(GDMAQ_PATH.'forms/update.php');
        }

        return $install || $update;
    }

    public function panel_general() {
        if (!$this->install_or_update()) {
            $path = GDMAQ_PATH.'forms/'.$this->page.'.php';

            $path = apply_filters('gdmaq_admin_panel_'.$this->page, $path);

            include($path);
        }
    }

    public function current_url($with_panel = true) {
        $page = 'admin.php?page=gd-mail-queue-';

        $page.= $this->page;

        if ($with_panel && $this->panel !== false && $this->panel != '') {
            $page.= '&panel='.$this->panel;
        }

        return self_admin_url($page);
    }

    public function file($type, $name, $d4p = false, $min = true, $base_url = null) {
        $get = is_null($base_url) ? $this->url : $base_url;

        if ($d4p) {
            $get.= 'd4plib/resources/';
        } else {
            $get.= 'admin/';
        }

        if ($name == 'font') {
            $get.= 'font/styles.css';
        } else if ($name == 'flags') {
            $get.= 'flags/flags.css';
        } else {
            $get.= $type.'/'.$name;

            if (!$this->is_debug && $type != 'font' && $min) {
                $get.= '.min';
            }

            $get.= '.'.$type;
        }

        return $get;
    }

    public function help_tab_getting_help() {
        $screen = get_current_screen();

        if ($this->page == 'settings' && $this->panel == 'htmlfy') {
            $screen->add_help_tab(
                array(
                    'id' => 'gdmaq-help-htmlfy',
                    'title' => __("HTMLfy", "gd-mail-queue"),
                    'content' => '<h2>'.__("Additional Templates", "gd-mail-queue").'</h2><p>'.__("To learn how to add additional HTML templates to use for turning plain text emails to HTML emails, check out the article in the knowledge base.", "gd-mail-queue").'</p>'.
                        '<p><a href=" https://support.dev4press.com/kb/article/additional-html-templates/" class="button-primary" target="_blank">'.__("Additional HTML Templates", "gd-mail-queue").'</a></p>'.
                        '<p>'.__("When creating custom templates, you can use custom HTML tags listed here, and they will be replaced by actual content and various other elements.", "gd-mail-queue").'</p>'.
                        $this->_render_help_table_htmlfy_tags()
                )
            );
        }

        $screen->add_help_tab(
            array(
                'id' => 'gdmaq-help-info',
                'title' => __("Help & Support", "gd-mail-queue"),
                'content' => '<h2>'.__("Help & Support", "gd-mail-queue").'</h2><p>'.__("To get help with this plugin, you can start with Knowledge Base list of frequently asked questions, user guides, articles (tutorials) and reference guide (for developers).", "gd-mail-queue").
                    '</p><p><a href="https://support.dev4press.com/kb/product/gd-mail-queue/" class="button-primary" target="_blank">'.__("Knowledge Base", "gd-mail-queue").'</a> <a href="https://support.dev4press.com/forums/forum/plugins/gd-mail-queue/" class="button-secondary" target="_blank">'.__("Support Forum", "gd-mail-queue").'</a></p>'
            )
        );

        $screen->add_help_tab(
            array(
                'id' => 'gdmaq-help-bugs',
                'title' => __("Found a bug?", "gd-mail-queue"),
                'content' => '<h2>'.__("Found a bug?", "gd-mail-queue").'</h2><p>'.__("If you find a bug in GD Mail Queue, you can report it in the support forum.", "gd-mail-queue").
                    '</p><p>'.__("Before reporting a bug, make sure you use latest plugin version, your website and server meet system requirements. And, please be as descriptive as possible, include server-side logged errors, or errors from browser debugger.", "gd-mail-queue").
                    '</p><p><a href="https://support.dev4press.com/forums/forum/plugins-lite/gd-mail-queue/" class="button-primary" target="_blank">'.__("Open new topic", "gd-mail-queue").'</a></p>'
            )
        );
    }

    private function _render_help_table_htmlfy_tags() {
        $list = $this->get_template_tags();

        $render = '<table class="d4p-table-help">';
        $render.= '<thead><tr>';
        $render.= '<th>'.__("Tags", "gd-mail-queue").'</th>';
        $render.= '<th>'.__("Description", "gd-mail-queue").'</th>';
        $render.= '</tr></thead><tbody>';

        foreach ($list as $tag => $label) {
            $render.= '<tr>';
            $render.= '<th><code>'.$tag.'</code></th>';
            $render.= '<td>'.$label.'</td>';
            $render.= '</tr>';
        }

        $render.= '</tbody></table>';

        return $render;
    }

    public function get_shared_template_tags() {
        return array(
            '{{WEBSITE_URL}}' => __("Website URL", "gd-mail-queue"),
            '{{WEBSITE_NAME}}' => __("Website Name", "gd-mail-queue"),
            '{{WEBSITE_TAGLINE}}' => __("Website Tagline", "gd-mail-queue"),
            '{{WEBSITE_LINK}}' => __("Website Link", "gd-mail-queue"),
            '{{CURRENT_DATE}}' => __("Current Date", "gd-mail-queue"),
            '{{CURRENT_TIME}}' => __("Current Time", "gd-mail-queue")
        );
    }

    public function get_template_tags() {
        return array_merge(array(
            '{{EMAIL_SUBJECT}}' => __("Subject", "gd-mail-queue").' <strong>('.__("Required", "gd-mail-queue").')</strong>',
            '{{EMAIL_CONTENT}}' => __("Content", "gd-mail-queue").' <strong>('.__("Required", "gd-mail-queue").')</strong>',
            '{{EMAIL_HEADER}}' => __("Header", "gd-mail-queue").' <strong>('.__("From Templates Parts Settings", "gd-mail-queue").')</strong>',
            '{{EMAIL_FOOTER}}' => __("Footer", "gd-mail-queue").' <strong>('.__("From Templates Parts Settings", "gd-mail-queue").')</strong>',
            '{{EMAIL_PREHEADER}}' => __("Preheader", "gd-mail-queue"),
            '{{EMAIL_POWEREDBY}}' => __("Powered By", "gd-mail-queue")
        ), $this->get_shared_template_tags());
    }
}

global $_gdmaq_core_admin;
$_gdmaq_core_admin = new gdmaq_admin_core();

function gdmaq_admin() {
    global $_gdmaq_core_admin;
    return $_gdmaq_core_admin;
}
