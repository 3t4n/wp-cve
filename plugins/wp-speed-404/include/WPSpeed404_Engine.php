<?php

if(!defined('ABSPATH')) exit;

class WPSpeed404_Engine {
    private static $_instance = null;
    public static function instance() {
        if (self::$_instance == null) {
            self::$_instance = new WPSpeed404_Engine();
        }
        return self::$_instance;
    }

    public static $modes = array(
        'off' => 'Off',
        'log' => 'Log',
        'hide' => 'Hide'
    );

    public function __construct() {
        add_filter('mod_rewrite_rules', array($this, 'mod_rewrite_rules'));

        $settings = WPSpeed404_Settings::instance();
        if($settings->mode == 'log'){
            add_action('template_redirect', array($this, 'template_redirect'));
            add_action(WPSpeed404::$slug . '-notify', array($this, 'notify'));
        }
    }

    public function activate(){
        $this->flush();
        if (!wp_next_scheduled('my_hourly_event')){
            wp_schedule_event(time(), 'daily', WPSpeed404::$slug . '-notify');
        }
    }

    public function deactivate(){
        $this->cleanup();
        wp_clear_scheduled_hook(WPSpeed404::$slug . '-notify');
    }

    public function notify(){
        $settings = WPSpeed404_Settings::instance();
        if($settings->mode != 'log'){
            return;
        }
        $log = WPSpeed404_Log::instance();

        if($log->count() == 0){
            return;
        }

        $to = filter_var($settings->notify_email, FILTER_VALIDATE_EMAIL) ? $settings->notify_email : get_option('admin_email');

        $subject = sprintf(
            __('[%s] %d Missing File(s)', 'wp-speed-404'),
            get_bloginfo('name'),
            $log->count()
        );

        $message = $log->format(false);

        $message .= "\n\n";
        $message .= "For more great tools, check out https://imincomelab.com";

        wp_mail($to, $subject, $message);
    }

    public function get_folder_regex(){
        $settings = WPSpeed404_Settings::instance();
        $folders = 'wp-content';
        if($settings->include_wp_includes){
            $folders  .= '|wp-includes';
        }
        if($settings->include_wp_admin){
            $folders  .= '|wp-admin';
        }
        return "^($folders)/.*";
    }

    public function template_redirect(){
        if(is_404()){
            global $wp_query;
            $page = $_SERVER['REQUEST_URI'];

            $home_root = parse_url(home_url());
            if ( isset( $home_root['path'] ) )
                $home_root = trailingslashit($home_root['path']);
            else
                $home_root = '/';

            $page = substr($page, strlen($home_root));
            $page = trim($page, '/');

            if(preg_match('#' . $this->get_folder_regex(true) . '#', $page)){
                $url = $_SERVER['REQUEST_URI'];
                $parts = explode('?', $url);
                $url = $parts[0];

                $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
                WPSpeed404_Log::instance()->log($url, $referrer);

                //instead of returning a 404, send an empty 200... may as well avoid
                //the cost of 'rendering' a 404 page.

                status_header(200);
                exit;
            }
        }
    }

    public function flush() {
        flush_rewrite_rules(true);
    }

    public function cleanup() {
        remove_filter('mod_rewrite_rules', array($this, 'mod_rewrite_rules'));
        flush_rewrite_rules(true);
    }

    public function mod_rewrite_rules($rules){
        $settings = WPSpeed404_Settings::instance();

        if($settings->mode == 'hide'){
            $regex = $this->get_folder_regex();

            $path = substr(WPSpeed404::$path, strlen(ABSPATH));
            $empty = str_replace('\\', '/', $path) . '/empty';

            $lines = array();
            $lines[] = '# BEGIN ' . WPSpeed404::$title;
            $lines[] = '<IfModule mod_rewrite.c>';
            $lines[] = 'RewriteEngine On';
            $lines[] = 'RewriteCond %{REQUEST_FILENAME} !-f';
            $lines[] = 'RewriteCond %{REQUEST_FILENAME} !-d';
            $lines[] = "RewriteRule $regex $empty [L]";
            $lines[] = '</IfModule>';
            $lines[] = '# END ' . WPSpeed404::$title;

            $lines[] = $rules;
            return implode("\n", $lines);
        }
        return $rules;
    }

    public static function is_supported(){
        if(strstr($_SERVER['SERVER_SOFTWARE'], 'Apache')){
            return true;
        }
        return false;
    }
}