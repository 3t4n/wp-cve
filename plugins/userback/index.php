<?php
/**
 * Plugin Name:       Userback
 * Plugin URI:        https://www.userback.io
 * Description:       Userback WordPress Plugin
 * Version:           1.0.14
 * Requires at least: 3.5.0
 * Author:            Lee Le @ Userback
 */

    $plugin_dir = str_replace('/index.php', '', plugin_basename( __FILE__ ));

    define('PLUGIN_DIR_USERBACK',  $plugin_dir);
    define('ADMIN_DIR_USERBACK',   'admin');

    register_activation_hook( __FILE__, 'userback_install' );

    add_action('admin_menu',            'userback_add_menu');
    add_action('admin_enqueue_scripts', 'userback_include_admin_script');
    add_action('wp_ajax_get_userback',  'userback_get_json');
    add_action('wp_ajax_save_userback', 'userback_save');
    add_action('wp_footer',             'userback_add_plugin_html');

    function userback_add_plugin_html() {
        if (userback_is_active()) {
            $settings = userback_get_array();

            print '
<!-- Userback -->
<script>
    Userback = window.Userback || {};
    Userback.access_token = "' . esc_html($settings['access_token']) . '";
    (function(id) {
        var s = document.createElement("script");
        s.async = 1;s.src = "https://static.userback.io/widget/v1.js";
        var parent_node = document.head || document.body;parent_node.appendChild(s);
    })("userback-sdk");
</script>
<!-- END -->
';
        }
    }

    // is the widget turned on?
    function userback_is_active() {
        $settings = userback_get_array();

        $is_active = false;

        //  0: All Pages and Blog Posts
        // -1: All Pages and Blog Posts (Draft and Pending Review only)
        // -2: All Pages
        // -3: All Pages (Draft and Pending Review only)
        // -4: All Blog Posts
        // -5: All Blog Posts (Draft and Pending Review only)
        if (isset($settings['is_active']) && $settings['is_active'] == 1) {
            if (in_array('0', $settings['page']) !== false) {
                // option 0: everything
                $is_active = true;
            } else {
                $post = get_queried_object();
                if ($post && $post->ID) {
                    if (in_array($post->ID, $settings['page']) !== false) {
                        // page is selected
                        $is_active = true;
                    } else if (in_array(-1, $settings['page']) !== false && ($post->post_status == 'draft' || $post->post_status == 'pending')) {
                        // option -1
                        $is_active = true;
                    } else if (in_array(-2, $settings['page']) !== false && $post->post_type == 'page') {
                        // option -2
                        $is_active = true;
                    } else if (in_array(-4, $settings['page']) !== false && $post->post_type == 'post') {
                        // option -4
                        $is_active = true;
                    } else if (in_array(-3, $settings['page']) !== false && $post->post_type == 'page' && ($post->post_status == 'draft' || $post->post_status == 'pending')) {
                        // option -3
                        $is_active = true;
                    } else if (in_array(-5, $settings['page']) !== false && $post->post_type == 'post' && ($post->post_status == 'draft' || $post->post_status == 'pending')) {
                        // option -5
                        $is_active = true;
                    }
                }
            }
        }

        if (in_array('0', $settings['role']) !== false) {
            // option 0: everyone
        } else {
            $user = wp_get_current_user();

            if ($user && isset($user->roles)) {
                $found = false;
                foreach ($user->roles as $role) {
                    if (in_array($role, $settings['role'])) {
                        $found = true;
                    }
                }

                if (!$found) {
                    $is_active = false;
                }
            } else {
                $is_active = false;
            }
        }

        return $is_active;
    }

    // activate hook
    function userback_install() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'userback';

        $drop_sql = 'DROP TABLE IF EXISTS ' . $table_name;
        $create_sql = 'CREATE TABLE ' . $table_name . ' (
            id                      INT(10)         NOT NULL AUTO_INCREMENT,
            t_is_active             TINYINT(1)      NOT NULL,
            t_role                  VARCHAR(255)    NOT NULL,
            t_page                  TEXT            NOT NULL,
            t_access_token          TEXT            NOT NULL,
            UNIQUE KEY id (id)
        );';

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($drop_sql);
        dbDelta($create_sql);
    }

    // add plugin menu
    function userback_add_menu() {
        add_menu_page('Userback', 'Userback', 'manage_options', 'userback', 'userback_print_overview', plugins_url(PLUGIN_DIR_USERBACK . '/assets/logo.png'));
    }

    // include JS / CSS files
    function userback_include_admin_script($hook) {
        wp_enqueue_script('userback-admin-js',  plugins_url(PLUGIN_DIR_USERBACK . '/javascript/admin.js'));
        wp_enqueue_style('userback-admin-css',  plugins_url(PLUGIN_DIR_USERBACK . '/css/admin.css'));
    }

    // overview page
    function userback_print_overview() {
        require_once(ADMIN_DIR_USERBACK . '/overview.php');
    }

    // XHR handler for the page payload
    function userback_get_json() {
        $response = userback_get_array();

        print wp_send_json(array(
            'data' => $response,
            'page' => get_pages(array(
                'post_status' => 'publish,inherit,pending,private,future,draft'
            ))
        ));
    }

    // the $response is returned as part of the XHR response
    function userback_get_array() {
        global $wpdb; // this is how you get access to the database

        $response = array();
        $rows = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'userback LIMIT 0, 1');

        foreach ($rows as $userback) {
            $response = array(
                'role'            => $userback->t_role ? explode(',', $userback->t_role) : array(0),
                'page'            => $userback->t_page ? explode(',', $userback->t_page) : array(0),
                'is_active'       => (int)$userback->t_is_active,
                'access_token'    => $userback->t_access_token
            );
        };

        return $response;
    }

    // build the $data (to be inserted in database) from raw $_POST
    // sanitize and validate the data too
    function userback_build_post_data() {
        $data = $_POST['data'];

        // sanitize and validate $_POST data

        // boolean 1 or 0
        if (isset($data['is_active'])) {
            $data['is_active'] = $data['is_active'] == 1 ? 1 : 0; // 0 or 1
        }

        // the access_token
        if (isset($data['access_token'])) {
            // remove quotes and semicolon (because quotes is not needed to be in the access token)
            $data['access_token'] = str_replace('\\', "", $data['access_token']);
            $data['access_token'] = str_replace('"', "", $data['access_token']);
            $data['access_token'] = str_replace("'", "", $data['access_token']);
            $data['access_token'] = str_replace(";", "", $data['access_token']);

            $data['access_token'] = sanitize_text_field($data['access_token']);
        }

        // page is an array of integers
        if (isset($data['page'])) {
            foreach ($data['page'] as $key => $page) {
                $data['page'][$key] = intval($page);
            }
        }

        // WP roles or 0 (all)
        if (isset($data['role'])) {
            $available_roles = array_keys(get_editable_roles());
            $available_roles[] = 0; // 0: all

            foreach ($data['role'] as $key => $role) {
                $data['role'][$key] = in_array($role, $available_roles) ? $role : 'administrator';
            }
        }

        $userback_data = array(
            'role'            => (isset($data['role'])         ? implode(',', $data['role']) : '0'),
            'page'            => (isset($data['page'])         ? implode(',', $data['page']) : '0'),
            'is_active'       => (isset($data['is_active'])    ? $data['is_active']          : 0),
            'access_token'    => (isset($data['access_token']) ? $data['access_token']       : '')
        );

        return $userback_data;
    }

    // save submitted form values into database
    function userback_save() {
        global $wpdb;

        if (!isset($_POST['csrf_token']) || !wp_verify_nonce($_POST['csrf_token'], 'userback_plugin_settings_update') || !current_user_can('manage_options')) {
            print wp_send_json(false);
            wp_die(__('Security check failed.'));
        }

        if (isset($_POST['data'])) {
            // $_POST data is sanitized and validated in userback_build_post_data
            $data = userback_build_post_data();

            // delete the old setting
            $sql = 'DELETE FROM ' . $wpdb->prefix . 'userback';
            $wpdb->get_results($sql);

            // insert the new setting
            $sql = 'INSERT INTO ' . $wpdb->prefix . 'userback (`t_role`, `t_page`, `t_is_active`, `t_access_token`) VALUES ('.
                '"' . esc_sql($data['role']) . '", ' .
                '"' . esc_sql($data['page']) . '", ' .
                '"' . esc_sql($data['is_active']) . '", '.
                '"' . esc_sql($data['access_token']) . '"' .
            ');';

            $wpdb->get_results($sql);

            print wp_send_json(true);
        }

        print wp_send_json(false);
    }
?>
