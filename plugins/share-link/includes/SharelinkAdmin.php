<?php

class SharelinkAdmin {
    public function settingPage() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        SharelinkHelpers::render('install');
    }

    public function sharelinkAdminPage() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        if (SharelinkOptions::getLicenseIsActivated()) {
            $widgets = (new SharelinkWidgets())->getAll();
            SharelinkHelpers::render('admin', ['widgets' => $widgets]);
        } else {
            $this->settingPage();
        }
    }

    public function loginError($status) {
        if ($status == "403") {
            SharelinkHelpers::render('error403');
            return;
        }

        SharelinkHelpers::render('error404');
    }

    public static function stylesAndScripts($hook) {
        if ($hook == "share-link_page_sharelink-setting" || $hook == "toplevel_page_sharelink" || $hook == "admin_page_sharelink-error-403") {
            wp_register_style('sharelinkStyle', plugin_dir_url(__FILE__) . '../assets/css/sharelink.css');
            wp_enqueue_style('sharelinkStyle');

            wp_register_style('sharelink-jquery-ui', plugin_dir_url(__FILE__) . '../assets/css/jquery-ui.css');
            wp_enqueue_style('sharelink-jquery-ui');

            wp_register_style('font-awsome-sharelink', '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
            wp_enqueue_style('font-awsome-sharelink');

            wp_register_style('highlight-code-sharelink', plugin_dir_url(__FILE__) . '../assets/css/dark.min.css');
            wp_enqueue_style('highlight-code-sharelink');

            wp_enqueue_script('highlight-script', plugin_dir_url(__FILE__) . '../assets/js/highlight.pack.js', ['jquery']);

            wp_enqueue_script('sharelink-script', plugin_dir_url(__FILE__) . '../assets/js/sharelink.js', ['jquery']);

            wp_enqueue_script('jQueryUI', plugin_dir_url(__FILE__) . '../assets/js/jquery-ui.js', ['jquery']);

            wp_enqueue_script('clipboardJs', plugin_dir_url(__FILE__) . '../assets/js/clipboard.min.js');
        }

        wp_enqueue_script('sharelink-checker', plugin_dir_url(__FILE__) . '../assets/js/plugin-checker.js', ['jquery']);
    }
}
