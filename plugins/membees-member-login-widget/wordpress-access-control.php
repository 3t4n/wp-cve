<?php

require dirname(__FILE__) . '/lib/Walker.php';

add_action('after_setup_theme', array('MembeeWordPressAccessControl', 'load_custom_widgets'));
add_action('wp', array('MembeeWordPressAccessControl', 'check_for_members_only'));
add_action('wp', array('MembeeWordPressAccessControl', 'check_for_nonmembers_only'));
add_action('init', array('MembeeWordPressAccessControl', 'add_wpac_nav_menus'));
add_action('admin_init', array('MembeeWordPressAccessControl', 'admin_init'));
add_action('admin_menu', array('MembeeWordPressAccessControl', 'admin_menu'));
add_action('add_meta_boxes', array('MembeeWordPressAccessControl', 'add_wp_access_meta_boxes'));
add_action('save_post', array('MembeeWordPressAccessControl', 'save_postdata'));
add_action('manage_pages_custom_column', array('MembeeWordPressAccessControl', 'show_column'));

add_filter('get_pages', array('MembeeWordPressAccessControl', 'get_pages'));
add_filter('manage_edit-page_columns', array('MembeeWordPressAccessControl', 'add_column'));
add_filter('the_excerpt', array('MembeeWordPressAccessControl', 'remove_excerpt'));
add_filter('the_content', array('MembeeWordPressAccessControl', 'remove_excerpt'));
add_filter('plugin_row_meta', array('MembeeWordPressAccessControl', 'plugin_row_meta'), 10, 5);
add_filter('posts_join_paged', array('MembeeWordPressAccessControl', 'posts_join_paged'), 10, 2);
add_filter('posts_where_paged', array('MembeeWordPressAccessControl', 'posts_where_paged'), 10, 2);
add_filter('wp_nav_menu_args', array('MembeeWordPressAccessControl', 'wp_nav_menu_members_only'), 99999);
add_filter('wp_nav_menu_args', array('MembeeWordPressAccessControl', 'wp_nav_menu_args'), 99999);
add_filter('wp_page_menu_args', array('MembeeWordPressAccessControl' , 'wp_page_menu_args'));

add_shortcode('member', array('MembeeWordPressAccessControl', 'shortcode_members'));
add_shortcode('members', array('MembeeWordPressAccessControl', 'shortcode_members'));
add_shortcode('non-members', array('MembeeWordPressAccessControl', 'shortcode_nonmembers'));
add_shortcode('nonmembers', array('MembeeWordPressAccessControl', 'shortcode_nonmembers'));
add_shortcode('non-member', array('MembeeWordPressAccessControl', 'shortcode_nonmembers'));
add_shortcode('nonmember', array('MembeeWordPressAccessControl', 'shortcode_nonmembers'));

/**
	* Membee Access Control
	* Adopted from the abandoned plugin by Brandon Wamboldt <brandon.wamboldt@gmail.com>
*/
class MembeeWordPressAccessControl
{
  public static function admin_init()
  {
    wp_enqueue_style('wpac-style', plugin_dir_url(__FILE__) . 'public/css/wpac.css');

    // Load translation files
    if (!load_plugin_textdomain('wpac')) {
      load_plugin_textdomain('wpac', false, 'wordpress-access-control/languages/');
    }
  }

  public static function admin_menu()
  {
    add_options_page('Member Access Control Settings', 'Members Only', 'manage_options', 'wpac-options', array('MembeeWordPressAccessControl', 'options_page'));
  }

  public static function options_page()
  {
    // Save options?
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
      if (!wp_verify_nonce($_REQUEST['_wpnonce'], 'wpac_options_save')) {
        $admin_error = __('Security check failed, please try again', 'wpac');
      } else {
        // Make the blog members only
        if (isset($_REQUEST['wpac_members_only_blog']) && $_REQUEST['wpac_members_only_blog'] == 'yes') {
          update_option('wpac_members_only_blog', true);
        } else {
          update_option('wpac_members_only_blog', false);
        }

        // Members blog redirect link
        if (isset($_REQUEST['wpac_members_blog_redirect'])) {
          update_option('wpac_members_blog_redirect', esc_url($_REQUEST['wpac_members_blog_redirect']));
        } else {
          delete_option('wpac_members_blog_redirect');
        }

        // Support custom post types
        if (isset($_REQUEST['wpac_custom_post_types']) && is_array($_REQUEST['wpac_custom_post_types'])) {
          update_option('wpac_custom_post_types', $_REQUEST['wpac_custom_post_types']);
        } else {
          delete_option('wpac_custom_post_types');
        }

        // Override per-page permissions
        if (isset($_REQUEST['wpac_always_accessible_by']) && is_array($_REQUEST['wpac_always_accessible_by'])) {
          $wpac_always_accessible_by = array();

          foreach ($_REQUEST['wpac_always_accessible_by'] as $role) {
            $wpac_always_accessible_by[] = $role;
          }

          update_option('wpac_always_accessible_by', $wpac_always_accessible_by);
        } else {
          update_option('wpac_always_accessible_by', array());
        }

        // Show in the menu settings
        if (isset($_REQUEST['wpac_show_in_menus']) && $_REQUEST['wpac_show_in_menus'] == 'always') {
          update_option('wpac_show_in_menus', 'always');
        } else {
          update_option('wpac_show_in_menus', 'with_access');
        }

        // Default post-type state setting
        if (isset($_POST['default_state'])) {
          foreach ($_POST['default_state'] as $post_type => $value) {
            if (in_array($value, array('public', 'members', 'nonmembers'))) {
              update_option('wpac_default_' . $post_type . '_state', $value);
            } else {
              update_option('wpac_default_' . $post_type . '_state', 'public');
            }
          }
        }

        // Default post-type roles
        if (isset($_POST['wpac_default_restricted_to'])) {
          foreach ($_POST['wpac_default_restricted_to'] as $post_type => $value) {
            if (is_array($value)) {
              update_option('wpac_' . $post_type . 's_default_restricted_to', $value);
            } else {
              update_option('wpac_' . $post_type . 's_default_restricted_to', array());
            }
          }
        }

        // Default redirect
        if (isset($_REQUEST['wpac_default_members_redirect']) && !empty($_REQUEST['wpac_default_members_redirect'])) {
          update_option('wpac_default_members_redirect', $_REQUEST['wpac_default_members_redirect']);
        } else {
          update_option('wpac_default_members_redirect', '');
        }

        // Search Options
        if (isset($_REQUEST['show_posts_in_search']) && $_REQUEST['show_posts_in_search'] == 'yes') {
          update_option('wpac_show_posts_in_search', true);
        } else {
          update_option('wpac_show_posts_in_search', false);
        }

        if (isset($_REQUEST['show_post_excerpts_in_search']) && $_REQUEST['show_post_excerpts_in_search'] == 'yes') {
          update_option('wpac_show_post_excerpts_in_search', true);
        } else {
          update_option('wpac_show_post_excerpts_in_search', false);
        }

        if (isset($_REQUEST['show_pages_in_search']) && $_REQUEST['show_pages_in_search'] == 'yes') {
          update_option('wpac_show_pages_in_search', true);
        } else {
          update_option('wpac_show_pages_in_search', false);
        }

        if (isset($_REQUEST['show_page_excerpts_in_search']) && $_REQUEST['show_page_excerpts_in_search'] == 'yes') {
          update_option('wpac_show_page_excerpts_in_search', true);
        } else {
          update_option('wpac_show_page_excerpts_in_search', false);
        }

        // Custom excerpts
        if (isset($_REQUEST['page_excerpt_text'])) {
          update_option('wpac_page_excerpt_text', $_REQUEST['page_excerpt_text']);
        }

        if (isset($_REQUEST['post_excerpt_text'])) {
          update_option('wpac_post_excerpt_text', $_REQUEST['post_excerpt_text']);
        }

        $admin_message = '<strong>' . __('Settings Saved.') . '</strong>';
      }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'bulk_set_all_roles') {
      // Get all posts of the given post type
      $posts = get_posts(array('post_type' => $_POST['bulk_post_type'], 'post_status' => 'any', 'posts_per_page' => -1));

      foreach ($posts as $post) {
        $restricted_to = maybe_unserialize(get_post_meta($post->ID, '_wpac_restricted_to', true));

        if (!is_array($restricted_to)) {
          $restricted_to = array();
        }

        $restricted_to[] = $_POST['bulk_role'];
        $restricted_to = array_unique($restricted_to);

        update_post_meta($post->ID, '_wpac_restricted_to', serialize($restricted_to));
      }

      $admin_message = '<strong>' . sprintf(__('Updated %d posts'), count($posts)) . '</strong>';
    } elseif (isset($_POST['action']) && $_POST['action'] == 'bulk_set_same') {
      // Get all posts of the given post type
      $posts = get_posts(array('post_type' => $_POST['bulk_post_type'], 'post_status' => 'any', 'posts_per_page' => -1));
      $count = 0;

      foreach ($posts as $post) {
        $restricted_to = maybe_unserialize(get_post_meta($post->ID, '_wpac_restricted_to', true));

        if (!is_array($restricted_to)) {
          continue;
        } elseif (!in_array($_POST['bulk_has_role'], $restricted_to)) {
          continue;
        }

        $restricted_to[] = $_POST['bulk_grant_role'];
        $restricted_to = array_unique($restricted_to);

        update_post_meta($post->ID, '_wpac_restricted_to', serialize($restricted_to));
        $count++;
      }

      $admin_message = '<strong>' . sprintf(__('Updated %d posts'), $count) . '</strong>';
    } elseif (isset($_POST['action']) && $_POST['action'] == 'bulk_set_all') {
      // Get all posts of the given post type
      $posts = get_posts(array('post_type' => $_POST['bulk_post_type'], 'post_status' => 'any', 'posts_per_page' => -1));

      foreach ($posts as $post) {
        update_post_meta($post->ID, '_wpac_is_members_only', 'true');
      }

      $admin_message = '<strong>' . sprintf(__('Updated %d posts'), count($posts)) . '</strong>';
    }

    include(dirname(__FILE__) . '/templates/options.php');
  }

  public static function add_column($columns)
  {
    $columns['wpac'] = '<img src="' . plugin_dir_url(__FILE__) . 'public/images/lock.png" alt="Access" />';
    return $columns;
  }

  public static function show_column($name)
  {
    global $post;

    switch ($name) {
      case 'wpac':
        if (get_post_meta($post->ID, '_wpac_is_members_only', true)) {
          echo '<img src="' . plugin_dir_url(__FILE__) . 'public/images/lock.png" alt="Members Only" title="Members Only" />';
        } else if (get_post_meta($post->ID, '_wpac_is_nonmembers_only', true)) {
          echo '<img src="' . plugin_dir_url(__FILE__) . 'public/images/unlock.png" alt="Non-members Only" title="Non-members Only" />';
        }

        break;
    }
  }

  public static function check_conditions($page_id)
  {
    global $wp_roles, $current_user;

    if (is_user_logged_in()) {
      get_currentuserinfo();

      $allowed_roles  = maybe_unserialize(get_post_meta($page_id, '_wpac_restricted_to', true));
      $override_roles = maybe_unserialize(get_option('wpac_always_accessible_by', array(0 => 'administrator')));

      if (empty($allowed_roles)) {
        $allowed_roles_tmp = $wp_roles->get_names();
        $allowed_roles = array();

        foreach ($allowed_roles_tmp as $role => $garbage) {
          $allowed_roles[] = $role;
        }
      }

      $intersections = array_intersect($current_user->roles, $allowed_roles);
      $override_intersections = array_intersect($current_user->roles, $override_roles);

      if (empty($intersections) && empty($override_intersections)) {
        $role = false;
      } else {
        $role = true;
      }
    } else {
      $role = true;
    }

    $is_members_only = get_post_meta($page_id, '_wpac_is_members_only', true);
    $is_nonmembers_only = get_post_meta($page_id, '_wpac_is_nonmembers_only', true);

    return ((is_user_logged_in() && $is_members_only && $role) || (!is_user_logged_in() && $is_nonmembers_only) || (!$is_members_only && !$is_nonmembers_only));
  }

  public static function check_for_members_only()
  {
    global $post;

    if (is_admin() || !$post) {
      return;
    }

    // Check for a members only blog
    $blog_is_members_only = get_option('wpac_members_only_blog', false);

    if ($blog_is_members_only && !is_user_logged_in()) {
      $redirect_to = get_option('wpac_members_blog_redirect', wp_login_url($_SERVER['REQUEST_URI']));

      if (empty($redirect_to)) {
        $redirect_to = wp_login_url($_SERVER['REQUEST_URI']);
      }

      header('Location: ' . add_query_arg('redirect_to', $_SERVER['REQUEST_URI'], $redirect_to));
      exit();
    }

    if (get_post_meta($post->ID, '_wpac_is_members_only', true) && !MembeeWordPressAccessControl::check_conditions($post->ID)) {
      if (is_singular()) {
        $redirect_to = get_post_meta($post->ID, '_wpac_members_redirect_to', true);
        $default_redirect = get_option('wpac_default_members_redirect');

        if (empty($redirect_to)) {
          if (empty($default_redirect)) {
            header('Location: ' . wp_login_url(site_url($_SERVER['REQUEST_URI'])));
          } else {
            header('Location: ' . add_query_arg('redirect_to', $_SERVER['REQUEST_URI'], $default_redirect));
          }
        } else {
          header('Location: ' . add_query_arg('redirect_to', $_SERVER['REQUEST_URI'], $redirect_to));
        }

        exit;
      }
    }
  }

  public static function check_for_nonmembers_only()
  {
    global $post;

    if (is_admin() || !$post) {
      return;
    }

    if (get_post_meta($post->ID, '_wpac_is_nonmembers_only', true) && !MembeeWordPressAccessControl::check_conditions($post->ID)) {
      $redirect_to = get_post_meta($post->ID, '_wpac_nonmembers_redirect_to', true);

      if (empty($redirect_to)) {
        header('Location: ' . get_bloginfo('wpurl') . '/index.php');
      } else {
        header('Location: ' . $redirect_to);
      }

      exit();
    }
  }

  public static function remove_excerpt($excerpt)
  {
    if (is_admin() || is_single()) return $excerpt;

    global $post;

    if (get_post_meta($post->ID, '_wpac_is_members_only', true) && !MembeeWordPressAccessControl::check_conditions($post->ID)) {
      $show_excerpt = get_post_meta($post->ID, '_wpac_show_excerpt_in_search', true);

      if (!is_singular() && $show_excerpt != 'yes') {
        if ($post->post_type == 'post') {
          $excerpt = get_option('wpac_post_excerpt_text', __('To view the contents of this post, you must be authenticated and have the required access level.', 'wpac'));
        } else {
          $excerpt = get_option('wpac_page_excerpt_text', __('To view the contents of this page, you must be authenticated and have the required access level.', 'wpac'));
        }

        return wpautop($excerpt);
      } else {
        return $excerpt;
      }
    }

    return $excerpt;
  }

  public static function posts_join_paged($join, $query)
  {
    global $wpdb;

    if (is_admin()) return $join;

    if (!is_singular() && !is_admin()) {
      $join .= " LEFT JOIN {$wpdb->postmeta} PMWPAC3 ON PMWPAC3.post_id = {$wpdb->posts}.ID AND PMWPAC3.meta_key = '_wpac_show_in_search' LEFT JOIN {$wpdb->postmeta} PMWPAC ON PMWPAC.post_id = {$wpdb->posts}.ID AND PMWPAC.meta_key = '_wpac_is_members_only' LEFT JOIN {$wpdb->postmeta} PMWPAC2 ON PMWPAC2.post_id = {$wpdb->posts}.ID AND PMWPAC2.meta_key = '_wpac_is_nonmembers_only' ";
    }

    return $join;
  }

  public static function posts_where_paged($where, $query)
  {
    global $wpdb;

    if (is_admin()) return $where;

    if (!is_singular() && !is_admin()) {
      if (!is_user_logged_in()) {
        $where .= " AND (PMWPAC.meta_value IS NULL OR PMWPAC3.meta_value = '1' OR PMWPAC.meta_value != 'true') ";
      } else {
        $where .= " AND (PMWPAC2.meta_value IS NULL OR PMWPAC2.meta_value != 'true') ";
      }
    }

    return $where;
  }

  public static function remove_restricted_posts($posts, $query = false)
  {
    return $posts;
    echo $query->request;exit();

    if (!is_singular() && !is_admin()) {
      $new_posts = array();

      foreach ($posts as $post) {
        // The page is members only and we do NOT have access to it
        if (get_post_meta($post->ID, '_wpac_is_members_only', true) && !MembeeWordPressAccessControl::check_conditions($post->ID)) {
          if (get_post_meta($post->ID, '_wpac_show_in_search', true) != 1) {
            continue;
          }
        }

        // The page is non-members only and we do NOT have access to it
        if (get_post_meta($post->ID, '_wpac_is_nonmembers_only', true) && !MembeeWordPressAccessControl::check_conditions($post->ID)) {
          if (get_post_meta($post->ID, '_wpac_show_in_search', true) != 1) {
            continue;
          }
        }

        // Remove results where the search term is in a [member] or [nonmember] tag and we don't have access
        $st = get_search_query();
        $content = do_shortcode($post->post_content);


        if (!empty($st) && is_search() && !stristr($content, $st)) {
          continue;
        }

        $new_posts[] = $post;
      }

      $query->found_posts   = count($new_posts);
      $query->max_num_pages = ceil(count($new_posts) / $query->query_vars['posts_per_page']);
      $query->posts         = $new_posts;
      $query->post_count    = count($new_posts);

      return $new_posts;
    }

    return $posts;
  }

  /**
   * Add our custom meta box to all support post types
   *
   * @since WordPress Access Control 2.0
   * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
   */
  public static function add_wp_access_meta_boxes()
  {
    $enabled_post_types = get_option('wpac_custom_post_types', array());
    $supported_pages = apply_filters('wp_access_supported_pages', array_merge(array('page', 'post'), $enabled_post_types));

    foreach ($supported_pages as $page_type) {
      add_meta_box('wpac_controls_meta', 'Member Access Control', array('MembeeWordPressAccessControl', 'add_wpac_meta_box'), $page_type, 'side', 'high');
    }
  }

  /**
   * Display the various per-page/per-post options in the sidebar
   *
   * @since WordPress Access Control 2.0
   * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
   */
  public static function add_wpac_meta_box()
  {
    global $post;

    $is_members_only = get_post_meta($post->ID, '_wpac_is_members_only', true);
    $is_nonmembers_only = get_post_meta($post->ID, '_wpac_is_nonmembers_only', true);

    if ($post->post_type == 'post') {
      $wpac_default_post_state = get_option('wpac_default_post_state', 'public');
    } else {
      $wpac_default_post_state = get_option('wpac_default_page_state', 'public');
    }

    if ($is_members_only == 'true' || ($post->post_status == 'auto-draft' && $wpac_default_post_state == 'members')) {
      $is_members_only = 'checked="checked"';
    }

    if ($is_nonmembers_only == 'true' || ($post->post_status == 'auto-draft' && $wpac_default_post_state == 'nonmembers')) {
      $is_nonmembers_only = 'checked="checked"';
    }

    $wpac_show_in_search = get_post_meta($post->ID, '_wpac_show_in_search', true);
    $wpac_show_excerpt_in_search = get_post_meta($post->ID, '_wpac_show_excerpt_in_search', true);
    $pass_to_children = get_post_meta($post->ID, '_wpac_pass_to_children', true);

    include(dirname(__FILE__) . '/templates/meta_box.php');
  }

  /**
   * Saves our custom meta data for the post being saved
   *
   * @since WordPress Access Control 2.0
   * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
   */
  public static function save_postdata($post_id)
  {
    // Security check
    if (!isset($_POST['members_only_nonce'])) {
      return $post_id;
    }

    if (!wp_verify_nonce($_POST['members_only_nonce'], 'members_only')) {
      return $post_id;
    }

    // Meta data isn't transmitted during autosaves so don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return $post_id;
    }

    if (isset($_POST['members_only']) && $_POST['members_only'] == 'true') {
      update_post_meta($post_id, '_wpac_is_members_only', 'true');

      if (isset($_POST['wpac_restricted_to']) && !empty($_POST['wpac_restricted_to'])) {
        update_post_meta($post_id, '_wpac_restricted_to', serialize($_POST['wpac_restricted_to']));
      } else {
        delete_post_meta($post_id, '_wpac_restricted_to');
      }
    } else {
      delete_post_meta($post_id, '_wpac_is_members_only');
    }

    if (isset($_POST['nonmembers_only']) && $_POST['nonmembers_only'] == 'true') {
      update_post_meta($post_id, '_wpac_is_nonmembers_only', 'true');
    } else {
      delete_post_meta($post_id, '_wpac_is_nonmembers_only');
    }

    if (isset($_POST['wpac_members_redirect_to'])) {
      update_post_meta($post_id, '_wpac_members_redirect_to', $_POST['wpac_members_redirect_to']);
    }

    if (isset($_POST['wpac_show_in_search'])) {
      update_post_meta($post_id, '_wpac_show_in_search', 1);
    } else {
      update_post_meta($post_id, '_wpac_show_in_search', 0);
    }

    if (isset($_POST['wpac_show_excerpt_in_search'])) {
      update_post_meta($post_id, '_wpac_show_excerpt_in_search', 1);
    } else {
      update_post_meta($post_id, '_wpac_show_excerpt_in_search', 0);
    }

    if (isset($_POST['wpac_nonmembers_redirect_to'])) {
      update_post_meta($post_id, '_wpac_nonmembers_redirect_to', $_POST['wpac_nonmembers_redirect_to']);
    }

    if (isset($_POST['pass_to_children']) && $_POST['pass_to_children'] == 'true') {
      update_post_meta($post_id, '_wpac_pass_to_children', 'true');

      $original_id = $post_id;
      $is_revision = wp_is_post_revision($original_id);

      if ($is_revision) {
        $original_id = $is_revision;
      }

      $children = get_pages(array('child_of' => $original_id));

      foreach ($children as $child) {
        MembeeWordPressAccessControl::save_postdata($child->ID);
      }
    } else {
      delete_post_meta($post_id, '_wpac_pass_to_children');
    }

    return $post_id;
  }

  /**
   * Replaces the default WordPress nav menu walker with our own version
   *
   * @param array $args The arguments passed to the wp_nav_menu function
   *
   * @return array The original arguments, with our custom walker instead of the default
   *
   * @since WordPress Access Control 1.1
   * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
   */
  public static function wp_nav_menu_args($args)
  {
    $args['walker'] = new MembeeSecureWalker($args['walker']);
    return $args;
  }

  /**
    * Prevents the code from degrading terribly when no nav menu is available
   *
   * @param array $args The arguments passed to the wp_nav_menu function
   *
   * @return array The original arguments, possible with our custom walker instead of the default
   *
   * @since WordPress Access Control 1.2
   * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
   */
  public static function wp_page_menu_args($args)
  {
    // Only remove the walker if it is ours
    if (isset($args['walker']) && get_class($args['walker']) == 'MembeeSecureWalker') {
      $args['walker'] = new MembeeSecureWalker(new Walker_Page());
    } elseif (isset($args['walker'])) {
      $args['walker'] = new MembeeSecureWalker($args['walker']);
    } else {
      $args['walker'] = new MembeeSecureWalker(new Walker_Page());
    }

    return $args;
  }

  /**
   * This hooks in at a higher level to make sure functions like wp_list_pages
   * won't return members only pages.
   *
   * @param array $pages
   * @return array
   *
   * @since WordPress Access Control 1.6.4
   * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
   */
  public static function get_pages($pages)
  {
    // Don't affect the display of pages when viewing the list in the admin
    if (is_admin()) {
      return $pages;
    }

    // Whether or not the user is logged in
    $auth = is_user_logged_in();

    // Go through each page, remove ones we can't see
    foreach ($pages as $key => $page) {
      $is_members_only = get_post_meta($page->ID, '_wpac_is_members_only', true);
      $is_nonmembers_only = get_post_meta($page->ID, '_wpac_is_nonmembers_only', true);

      if ($is_members_only && !$auth) {
        unset($pages[$key]);
      }

      if ($is_nonmembers_only && $auth) {
        unset($pages[$key]);
      }
    }

    return $pages;
  }

  /**
   * Adds links to the plugin row to view the options page or view the plugin
   * documentation.
   *
   * @param array $plugin_meta The links to display in the plugin row
   * @param string $plugin_file The directory name and filename of the plugin (wordpress-access-control/wordpress-access-control.php)
   * @param array $plugin_data
   * @param string $status The status of the plugin (active or inactive)
   *
   * @return array The $plugin_meta array of links for this plugin
   *
   * @uses plugin_dir_url() to get the URL of the plugin directory since that is where the documentation is
   *
   * @since WordPress Access Control 3.0
   * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
   */
  public static function plugin_row_meta($plugin_meta, $plugin_file, $plugin_data, $status)
  {
    if ($plugin_file == str_replace('.php', '/', basename(__FILE__)) . basename(__FILE__)) {
      $plugin_meta[] = '<a href="options-general.php?page=wpac-options">' . __('Visit options page', 'wpac') . '</a>';
      $plugin_meta[] = '<a href="' . plugin_dir_url(__FILE__) . 'documentation/index.html">' . __('Plugin Documentation', 'wpac') . '</a>';
    }

    return $plugin_meta;
  }


  /**
   * Hides content for users that are not logged in and displays content for
   * users who are logged in. Calls do_shortcode on the content to allow
   * nested shortcodes.
   *
   * @uses is_user_logged_in() to determine if the user is logged in or not
   * @uses wpautop() to format the text in the shortcodes
   * @uses do_shortcode() to execute nested shortcodes in our shortcode
   *
   * @since WordPress Access Control 3.0
   * @author Brandon Wamboldt <brandon.wamboldt@gmail.com>
   */
  public static function shortcode_members($attributes, $content = NULL)
  {
    global $current_user, $post;

    if (is_user_logged_in()) {
            if (isset($attributes['role'])) {

                // Determine the syntax (Singular role, OR syntax or NOT syntax)
                if (substr($attributes['role'], 0, 3) == 'or:') {
                    $roles = explode(',', substr($attributes['role'], 3));

                    $has_a_role = false;

                    foreach ($roles as $role) {
                        if (in_array($role, $current_user->roles)) {
                            $has_a_role = true;
                            break;
                        }
                    }

                    if (!$has_a_role) {
                        return '';
                    }
                } else if (substr($attributes['role'], 0, 4) == 'not:') {
                    $roles = explode(',', substr($attributes['role'], 4));

                    foreach ($roles as $role) {
                        if (in_array($role, $current_user->roles)) {
                            return '';
                        }
                    }
                } else {
                    if (!in_array($attributes['role'], $current_user->roles)) {
                        return '';
                    }
                }
            }

      return wpautop(do_shortcode($content));
    }

    return '';
  }

  /**
   * Hides content for users that are logged in and displays content for users
   * who are not logged in. Calls do_shortcode on the content to allow nested
   * shortcodes.
   *
   * @param  array $attributes
   * @param string $content
   */
  public static function shortcode_nonmembers($attributes, $content = NULL)
  {
    global $post;

    if (!is_user_logged_in()) {
      return wpautop(do_shortcode($content));
    }

    return '';
  }

  /**
   * Loads a file that will override certain WordPress default widgets withget_pages
   * a slightly modified version that allows admins to select it's visibility
   * (Members only or non members only).
   */
  public static function load_custom_widgets()
  {
    require dirname(__FILE__) . '/default-widgets.php';
  }

  /**
   * Adds our member only menus for each regular menu
   */
  public static function add_wpac_nav_menus()
  {
    global $_wp_registered_nav_menus;

    $original = (array) $_wp_registered_nav_menus;

    foreach ($original as $location => $label) {
      $_wp_registered_nav_menus[$location . '_wpac'] = $label . ' - Members Only';
    }
  }

  /**
   * Checks to see if a members only version of the current menu is available
   * and will load that instead of the normal menu.
   *
   * @param  array $args The arguments passed to the wp_nav_menu function
   * @return array The original arguments, possible with a new value for theme_location
   */
  public static function wp_nav_menu_members_only($args)
  {
    // Don't do anything if the user isn't logged in
    if (is_user_logged_in()) {

      // See if the theme even passed a proper menu ID
      if (!empty($args['theme_location'])) {

        // Member only menus end in _wpac
        $theme_location = $args['theme_location'] . '_wpac';

        // Get the nav menu based on the theme_location
        if (($locations = get_nav_menu_locations()) && isset($locations[ $theme_location ])) {
          $menu = wp_get_nav_menu_object($locations[ $theme_location ]);

          // Only use the member only menu if it's not empty
          if ($menu && $menu->count > 0) {
            $args['theme_location'] = $theme_location;
          }
        }
      }
    }

    return $args;
  }
}
