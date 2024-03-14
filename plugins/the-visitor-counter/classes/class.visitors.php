<?php

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

Class WTVCP_Visitors
{

    // Init hooks
    public static function WTVCP_init_hooks()
    {
        add_filter('plugin_row_meta', [__CLASS__, 'add_action_links'], 10, 4);
        add_action('admin_menu', [__CLASS__, 'WTVCP_create_menu']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'WTVCP_add_assets']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'WTVCP_add_admin_assets']);
        add_shortcode('visitors', [__CLASS__, 'WTVCP_vnr_shortcode']);
    }

    // Connect assets
    public static function WTVCP_add_assets()
    {
        wp_enqueue_style(
            'WTVCP_custom-frontend',
            WTVCP_PLUGIN_URL . 'assets/css/custom-frontend.css',
            WTVCP_VERSION
        );
    }

    // Connect assets
    public static function WTVCP_add_admin_assets()
    {
        // Scripts
        wp_register_script(
            'WTVCP_prefixfree',
            WTVCP_PLUGIN_URL . 'assets/js/prefixfree.min.js',
            ['jquery'],
            WTVCP_VERSION,
            true
        );
        wp_register_script(
            'WTVCP_color-picker',
            WTVCP_PLUGIN_URL . 'assets/js/pickr.min.js',
            ['jquery'],
            WTVCP_VERSION,
            true
        );
        wp_register_script(
            'WTVCP_index',
            WTVCP_PLUGIN_URL . 'assets/js/index.js',
            ['jquery'],
            WTVCP_VERSION,
            true
        );
    }

    public static function add_action_links($meta, $plugin_file)
    {
        if (false === strpos($plugin_file, WTVCP_PLUGIN_BASENAME)) {
            return $meta;
        }

        $meta[] = '<a href="tools.php?page=visitors">' . __('Settings') . '</a>';

        return $meta;
    }

    //  Admin menu content
    public static function WTVCP_create_menu()
    {
        $admin_page = add_submenu_page(
            'tools.php',
            'The Visitor Counter Plugin Settings',
            'The Visitor Counter',
            'manage_options',
            'visitors',
            [
                __CLASS__,
                'WTVCP_show_content'
            ]
        );

        // Load the JS conditionally
        add_action('load-' . $admin_page, [__CLASS__, 'load_admin_js']);
    }

    // This function is only called when our plugin's page loads!
    public static function load_admin_js()
    {
        // Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_js']);
    }

    public static function enqueue_admin_js()
    {
        // Styles
        wp_enqueue_style('WTVCP_custom-backend', WTVCP_PLUGIN_URL . 'assets/css/custom-backend.css', WTVCP_VERSION);
        wp_enqueue_style('WTVCP_color-picker', WTVCP_PLUGIN_URL . 'assets/css/pickr.min.css', WTVCP_VERSION);

        // Scripts
        wp_enqueue_script('WTVCP_prefixfree');
        wp_enqueue_script('WTVCP_color-picker');
        wp_enqueue_script('WTVCP_index');
    }

    //  Install
    public static function WTVCP_install()
    {
        global $wpdb;

        $table_name = $wpdb->get_blog_prefix() . 'vnr_visitors';

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            $sql = "CREATE TABLE {$table_name} (
                id int(11) NOT NULL auto_increment,
                title varchar(255) NOT NULL default '',
                show_title varchar(10) NOT NULL default '',
                background varchar(20) NOT NULL default '',
                color varchar(20) NOT NULL default '',
                border_radius int NOT NULL default 6,
                width int NOT NULL default 380, 
                PRIMARY KEY  (id)

                ) {$charset_collate};
                INSERT INTO {$table_name} (`id`, `title`, `show_title`, `background`, `color` )
                VALUES (NULL, 'Visitor Counter', 'true', '#eee', '#333');";
            dbDelta($sql);
        }

        // block robots in .htaccess and robots.txt
        $ht_file           = ABSPATH . '.htaccess';
        $robots_file       = ABSPATH . 'robots.txt';
        $file_for_inc      = plugin_dir_path(__FILE__) . 'htaccess-data.txt';
        $htaccess_data_inc = file_get_contents($file_for_inc);

        if (file_exists($ht_file)) {
            $htaccess_data = file_get_contents($ht_file);

            if (stristr($htaccess_data, '# BEGIN WTVCPBlocker') === false AND is_writable($ht_file)) {
                $htaccess_data .= $htaccess_data_inc;
                file_put_contents($ht_file, $htaccess_data);
            }
        } else {
            $hf = fopen($ht_file, 'w+');
            fwrite($hf, $htaccess_data_inc);
            fclose($hf);
        }

        if (file_exists($robots_file)) {
            $htaccess_data = file_get_contents($robots_file);

            if (stristr($htaccess_data, '# BEGIN WTVCPBlocker') === false AND is_writable($robots_file)) {
                $htaccess_data .= $htaccess_data_inc;
                file_put_contents($robots_file, $htaccess_data);
            }
        } else {
            $rf = fopen($robots_file, 'w+');
            fwrite($rf, $htaccess_data_inc);
            fclose($rf);
        }
    }

    //  Get user online
    public static function WTVCP_get_users_online()
    {
        $base      = "base_sessions.dat";
        $last_time = time() - 120;
        touch($base);
        $file = file($base);

        $output = '';

        $id = session_id();
        if ($id !== '') {
            $res_file = [];

            foreach ($file as $line) {
                list($sid, $utime) = explode('|', $line);

                if ($utime > $last_time) {
                    $res_file[$sid] = trim($sid) . '|' . trim($utime) . PHP_EOL;
                }
            }

            $res_file[$id] = trim($id) . '|' . time() . PHP_EOL;
            file_put_contents($base, $res_file, LOCK_EX);
            $count_users = count($res_file);
            $count_users = (string)number_format($count_users);
            $symbols     = str_split($count_users);

            foreach ($symbols as $symbol) {
                if ($symbol == ',') {
                    $output .= ",";
                } else {
                    $output .= "<span>$symbol</span>";
                }
            }
        }

        return $output;
    }

    //  Get template
    public static function WTVCP_view($name)
    {
        if(is_admin()){
            $path = WTVCP_PLUGIN_DIR . 'views/' . $name . '-template.php';
	        include($path);
        }
    }

    public static function WTVCP_vnr_shortcode()
    {
	    $path = WTVCP_PLUGIN_DIR . 'views/widget-template.php';
	    ob_start();
	    require_once($path);
	    $html = ob_get_clean();
	    return $html;
    }

    //  Get settings
    public static function WTVCP_get_settings()
    {
        global $wpdb;

        $table  = $wpdb->get_blog_prefix() . 'vnr_visitors';
        $result = $wpdb->get_row("SELECT * FROM $table WHERE id = 1", OBJECT);

        return $result;
    }

    //  Save update settings
    public static function WTVCP_save_param()
    {
        if ( ! is_admin() ) {
            return false;
        }

        global $wpdb;

        $options = [
            'tvcp_save'  => '',
            'title'      => 'My Time',
            'show-title' => 'true',
            'bg-color'   => '#CCC',
            'text-color' => '#FFF',
            'border-radius' => 6,
            'width' => 380,
        ];

        if ($_POST) {
            
            wp_verify_nonce( $_POST['_wpnonce'], 'tvcp-save-settings' );

            foreach ($options as $key => $value) {
                if (isset($_POST[$key]) && ! empty($_POST[$key])) {
                    $options[$key] = sanitize_text_field($_POST[$key]);
                }
            }

            if ($options['tvcp_save'] === 'true') {

                $table  = $wpdb->get_blog_prefix() . 'vnr_visitors';
                $result = $wpdb->update($table,
                    [
                        'title'         => $options['title'],
                        'show_title'    => $options['show-title'],
                        'background'    => $options['bg-color'],
                        'color'         => $options['text-color'],
                        'border_radius' => $options['border-radius'],
                        'width'         => $options['width']
                    ],
                    ['id' => 1]
                );

                if(!empty($result)) {
                    echo '<p class="success-db">Settings have been saved</p>';
                } 
            }
        }
    }

    //  Show widget content
    public static function WTVCP_show_widget_content()
    {
        self::WTVCP_view('widget');
    }

    //  Show content
    public static function WTVCP_show_content()
    {
        self::WTVCP_save_param();
        self::WTVCP_view('admin/main');
    }

}
