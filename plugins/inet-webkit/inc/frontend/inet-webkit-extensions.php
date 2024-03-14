<?php
defined('ABSPATH') || exit;

$inet_wk_options = get_option('inet_wk');

if (!empty($inet_wk_options['inet-webkit-classic-editor']['classic-editor-active'])) {
    add_filter('use_block_editor_for_post_type', '__return_false', 100);
}

if (!empty($inet_wk_options['inet-webkit-redirect-404']['redirect-404-active'])) {
    function inet_wk_redirect_404()
    {
        if (is_404()) {
            wp_redirect(home_url(), 301);
            die();
        }
    }

    add_action('wp', 'inet_wk_redirect_404', 1);
}


if (!empty($inet_wk_options['inet-webkit-custom-login']['custom-login-active'])) {
    function my_login_stylesheet()
    {
        wp_enqueue_style('custom-login', INET_WK_URL . 'assets/css/frontend/style-login.css');
    }

    add_action('login_enqueue_scripts', 'my_login_stylesheet');
    add_action('login_head', 'inet_wk_Load_Login_Style');

    function inet_wk_Load_Login_Style()
    {
        $inet_wk_options = get_option('inet_wk');
        $logo_url = $inet_wk_options['inet-webkit-custom-login-logo']['url'];

        echo '<style>';
        if (!empty($logo_url)) {
            echo '.login h1 a { background-image: url(' . esc_url($logo_url) . ')!important; background-size: contain; width:auto!important;max-width:100%; }';
        };
        echo '</style>';
    }

    add_filter('login_headerurl', 'inet_wk_Login_url');
    function inet_wk_Login_url()
    {
        $inet_wk_options = get_option('inet_wk');
        $logo_url = $inet_wk_options['inet-webkit-custom-login-link'];
        if (empty($logo_url))
            return site_url();
        else return $logo_url;
    }
}

if (!empty($inet_wk_options['inet-webkit-opt-duplicate']['duplicate-page-post-active'])) {
    add_filter('post_row_actions', 'inet_wk_duplicate_post_link', 10, 2);
    add_filter('page_row_actions', 'inet_wk_duplicate_post_link', 10, 2);

    function inet_wk_duplicate_post_link($actions, $post)
    {
        if (current_user_can('edit_posts')) {
            $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=inet_wk_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Sao chép bài viết" rel="permalink"><span class="dashicons dashicons-admin-page"></span> Nhân bản</a>';
        }
        return $actions;
    }

    add_action('admin_action_inet_wk_duplicate_post_as_draft', 'inet_wk_duplicate_post_as_draft');
    function inet_wk_duplicate_post_as_draft()
    {
        global $wpdb;
        if (!(isset($_GET['post']) || isset($_POST['post']) || (isset($_REQUEST['action']) && 'inet_wk_duplicate_post_as_draft' == $_REQUEST['action']))) {
            wp_die('No post to duplicate has been supplied!');
        }
        if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__)))
            return;

        $post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
        $post = get_post($post_id);
        $current_user = wp_get_current_user();
        $new_post_author = $current_user->ID;
        if (isset($post) && $post != null) {
            $args = array(
                'comment_status' => $post->comment_status,
                'ping_status' => $post->ping_status,
                'post_author' => $new_post_author,
                'post_content' => $post->post_content,
                'post_excerpt' => $post->post_excerpt,
                'post_name' => $post->post_name,
                'post_parent' => $post->post_parent,
                'post_password' => $post->post_password,
                'post_status' => 'draft',
                'post_title' => $post->post_title,
                'post_type' => $post->post_type,
                'to_ping' => $post->to_ping,
                'menu_order' => $post->menu_order
            );
            $new_post_id = wp_insert_post($args);
            $taxonomies = get_object_taxonomies($post->post_type);
            foreach ($taxonomies as $taxonomy) {
                $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
                wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
            }
            $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
            if (count($post_meta_infos) != 0) {
                $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
                foreach ($post_meta_infos as $meta_info) {
                    $meta_key = $meta_info->meta_key;
                    if ($meta_key == '_wp_old_slug') continue;
                    $meta_value = addslashes($meta_info->meta_value);
                    $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
                }
                $sql_query .= implode(" UNION ALL ", $sql_query_sel);
                $wpdb->query($sql_query);
            }
            wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
            exit;
        } else {
            wp_die('Post creation failed, could not find original post: ' . $post_id);
        }
    }
}

if (!empty($inet_wk_options['inet-webkit-opt-duplicate']['duplicate-menu-active'])) {
    add_action('wp_ajax_inetwk_duplicate_menu_maker', 'inetwk_duplicate_menu_maker');
    function inetwk_duplicate_menu_maker()
    {
        $response = array();
        /* Check for vaild input */
        if (!isset($_REQUEST['name'])) {
            echo '<strong> Something went wrong </strong>';
            die();
        }
        /* Make sure values are vaild to process */
        $name = sanitize_text_field($_REQUEST['name'] . '-copy');
        if (true === is_nav_menu($name)) {
            $response["error"] = 'Menu ' . $name . ' đã tồn tại. Vui lòng truy cập danh sách menu để kiểm tra';
            echo json_encode($response);
            die();
        }
        $source = wp_get_nav_menu_object(sanitize_text_field($_REQUEST['name']));
        $source_items = wp_get_nav_menu_items(sanitize_text_field($_REQUEST['name']));
        $new_id = wp_create_nav_menu($name);
        /* Ready to process the menu for duplication */
        $rel = array();
        $i = 1;
        foreach ($source_items as $menu_item) {
            $args = array(
                'menu-item-db-id' => $menu_item->db_id,
                'menu-item-object-id' => $menu_item->object_id,
                'menu-item-object' => $menu_item->object,
                'menu-item-position' => $i,
                'menu-item-type' => $menu_item->type,
                'menu-item-title' => $menu_item->title,
                'menu-item-url' => $menu_item->url,
                'menu-item-description' => $menu_item->description,
                'menu-item-attr-title' => $menu_item->attr_title,
                'menu-item-target' => $menu_item->target,
                'menu-item-classes' => implode(' ', $menu_item->classes),
                'menu-item-xfn' => $menu_item->xfn,
                'menu-item-status' => $menu_item->post_status
            ); // End of for-each()
            $parent_id = wp_update_nav_menu_item($new_id, 0, $args);
            $rel[$menu_item->db_id] = $parent_id;
            /* Just reassuring, child shouldn't be left home-alone */
            if ($menu_item->menu_item_parent) {
                $args['menu-item-parent-id'] = $rel[$menu_item->menu_item_parent];
                $parent_id = wp_update_nav_menu_item($new_id, $parent_id, $args);
            }
            $i++;
        } /* End of foreach() */
        /* Refresh(redirect to) the current page */
        $response["menu_id"] = $new_id;
        echo json_encode($response);
        die();
    }

    add_action('admin_enqueue_scripts', 'inetwk_duplicate_admin_scripts');

    function inetwk_duplicate_admin_scripts()
    {
        wp_enqueue_script('duplicate-menu-active', INET_WK_URL . 'assets/js/inet-webkit-menu.js',
            array('jquery'), '1.0.0', true);
        wp_localize_script('duplicate-menu-active', 'inetwk_button_duplicate',
            array(
                'enable_in_menu' => 1,
                'inetwk_bt_name' => __('<span class="dashicons dashicons-admin-page"></span> Sao chép menu', 'inetwk'),
                'ajax_url' => admin_url('admin-ajax.php')
            )
        );
    }
}

if (!empty($inet_wk_options['inet-webkit-disable-emojis']['disable-emojis-active'])) {
    function inet_wk_disable_emojis()
    {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

        // Remove from TinyMCE
        add_filter('tiny_mce_plugins', function ($plugins) {
            if (is_array($plugins)) {
                return array_diff($plugins, array('wpemoji'));
            } else {
                return array();
            }
        });

        add_filter('wp_resource_hints', function ($urls, $relation_type) {
            if ('dns-prefetch' == $relation_type) {
                $emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
                foreach ($urls as $key => $url) {
                    if (strpos($url, $emoji_svg_url_bit) !== false) {
                        unset($urls[$key]);
                    }
                }
            }
            return $urls;
        }, 10, 2);
    }

    add_action('init', 'inet_wk_disable_emojis');
}

if (!empty($inet_wk_options['inet-webkit-disable-wp-embeds']['disable-wp-embeds-active'])) {
    add_action('init', 'inet_wk_disable_embeds_code_init', 9999);
    function inet_wk_disable_embeds_code_init()
    {
        // Remove the REST API endpoint.
        remove_action('rest_api_init', 'wp_oembed_register_route');

        // Turn off oEmbed auto discovery.
        add_filter('embed_oembed_discover', '__return_false');

        // Don't filter oEmbed results.
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

        // Remove oEmbed discovery links.
        remove_action('wp_head', 'wp_oembed_add_discovery_links');

        // Remove oEmbed-specific JavaScript from the front-end and back-end.
        remove_action('wp_head', 'wp_oembed_add_host_js');
        add_filter('tiny_mce_plugins', function ($plugins) {
            return array_diff($plugins, array('wpembed'));
        });

        // Remove all embeds rewrite rules.
        add_filter('rewrite_rules_array', function ($rules) {
            foreach ($rules as $rule => $rewrite) {
                if (false !== strpos($rewrite, 'embed=true')) {
                    unset($rules[$rule]);
                }
            }
            return $rules;
        });
        remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result', 10);
    }
}

if (!empty($inet_wk_options['inet-webkit-remove-query-strings']['switcher-remove-query-strings'])) {
    function inet_wk_remove_query_strings()
    {
        if (!is_admin()) {
            add_filter('script_loader_src', 'inet_wk_remove_query_strings_split', 15);
            add_filter('style_loader_src', 'inet_wk_remove_query_strings_split', 15);
        }
    }

    function inet_wk_remove_query_strings_split($src)
    {
        $output = preg_split("/(&ver|\?ver)/", $src);
        return $output[0];
    }

    add_action('init', 'inet_wk_remove_query_strings');
}

if (!empty($inet_wk_options['inet-webkit-disable-google-font']['disable-google-font-active'])) {

    add_action('wp_loaded', function ($html) {
        if (!is_admin()) {
            $html = preg_replace('/<link[^<>]*\/\/fonts\.(googleapis|google|gstatic)\.com[^<>]*>/i', '', $html);
            return $html;
        }
    });
}

if (!empty($inet_wk_options['inet-webkit-disable-dashicons']['disable-dashicons-active'])) {

    function mb_wp_helper_disable_dashicons()
    {
        if (current_user_can('update_core')) {
            return;
        }
        wp_deregister_style('dashicons');
    }

    add_action('wp_enqueue_scripts', 'mb_wp_helper_disable_dashicons');
}