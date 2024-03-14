<?php
/*
* Plugin Name:       Duplicate Pages, Posts & CPT
* Plugin URI:        https://wp-ninjas.de/plugins/duplicate-pages-posts/
* Description:       Duplicate pages, posts and custom post types with all their settings and contents with a single click.
* Version:           1.2
* Requires at least: 3.6
* Requires PHP:      5.2
* Author:            WP Ninjas - Jonas Tietgen, Ferry Abt
* Author URI:        https://wp-ninjas.de/
* License:           GPL v3
* License URI:       https://www.gnu.org/licenses/gpl-3.0.html
* Text Domain:       duplicate-pages-posts
*/

if (!class_exists('duplicate_pages_posts')):
  abstract class duplicate_pages_posts
  {
      public static function init()
      {
          add_filter('post_row_actions', self::class.'::post_list_link', 10, 2);
          add_filter('page_row_actions', self::class.'::post_list_link', 10, 2);
          add_action('admin_action_duplicate_pages_posts_wp_ninjas', self::class.'::duplicate');
          add_action('admin_bar_menu', self::class.'::admin_bar_link', 100);
          add_action('plugins_loaded', self::class.'::load_plugin_textdomain');
      }

      public static function load_plugin_textdomain()
      {
          load_plugin_textdomain('duplicate-pages-posts', false, basename(dirname(__FILE__)).'/languages/');
      }

      public static function post_list_link($actions, $post)
      {
          if (current_user_can('edit_post', $post->ID)) {
              $query_args = [
                'action' => 'duplicate_pages_posts_wp_ninjas',
                'post'   => $post->ID,
                'nonce'  => wp_create_nonce('duplicate_pages_posts_wp_ninjas')
              ];
              $url=add_query_arg($query_args, admin_url());
              $action='<a href="'.esc_url($url).'">';
              $action.=_x('Duplicate', 'list link', 'duplicate-pages-posts');
              $action.='</a>';
              $actions['ninja_dup']=$action;
          }
          return $actions;
      }


      public static function admin_bar_link($admin_bar)
      {
          global $current_screen;
          if (!empty($current_screen)
          && !(strpos($current_screen->id, 'edit')===false)) {
              return;
          }

          global $wp_query;
          $not_duplicable = [
            "is_archive",
            "is_date",
            "is_year",
            "is_month",
            "is_day",
            "is_time",
            "is_author",
            "is_category",
            "is_tag",
            "is_tax",
            "is_search",
            "is_feed",
            "is_comment_feed",
            "is_trackback",
            "is_home",
            "is_privacy_policy",
            "is_404",
            "is_embed",
            "is_paged",
            "is_admin",
            "is_attachment",
            "is_robots",
            "is_favicon",
            "is_post_type_archive"
          ];
          foreach ($not_duplicable as $key) {
              if ($wp_query->$key) {
                  return;
              }
          }

          $post=get_post();
          if (empty($post) || !current_user_can('edit_post', $post->ID)) {
              return;
          }
          $query_args=[
            'action' => 'duplicate_pages_posts_wp_ninjas',
            'post'   => $post->ID,
            'nonce'  => wp_create_nonce('duplicate_pages_posts_wp_ninjas')
          ];
          $url=add_query_arg($query_args, admin_url());
          $title='<span style="';
          $title.='display:inline-block;padding-right:10px;top:6px;position:relative;';
          $title.='"><img src="'.plugins_url('admin/icon.png', __FILE__);
          $title.='" style="width:21px; height:21px;"/></span><span>';
          $title.=_x('Duplicate', 'admin bar', 'duplicate-pages-posts');
          $title.='</span>';
          $admin_bar->add_menu(array(
            'id'    => 'ninja-cloner',
            'title' => $title,
            'href'  => esc_url($url)
          ));
      }

      public static function duplicate()
      {
          if (!(isset($_GET['action'])
          && isset($_GET['nonce'])
          && isset($_GET['post'])
          && 'duplicate_pages_posts_wp_ninjas' === $_GET['action']
          && wp_verify_nonce($_GET['nonce'], 'duplicate_pages_posts_wp_ninjas'))) {
              return;
          }
          $post_id=intval($_GET['post']);
          $post = get_post($post_id);
          if (empty($post) ||!current_user_can('edit_post', $post->ID)) {
              return;
          }
          global $wpdb;
          $args = [
            'post_content' => $post->post_content,
            'post_title' => $post->post_title.' - '._x('Duplicate', 'post title', 'duplicate-pages-posts'),
            'post_excerpt' => $post->post_excerpt,
            'post_status' => 'draft',
            'post_type' => $post->post_type,
            'comment_status' => $post->comment_status,
            'ping_status' => $post->ping_status,
            'post_password' => $post->post_password,
            'post_name' => $post->post_name,
            'to_ping' => $post->to_ping,
            'post_parent' => $post->post_parent,
            'menu_order' => $post->menu_order,
            'post_mime_type' => $post->post_mime_type
          ];
          $new_post_id = wp_insert_post($args);
          foreach (get_post_meta($post_id) as $key => $value) {
              if (is_array($value)) {
                  foreach ($value as $value2) {
                      $data = @unserialize($value2);
                      if ($data !== false) {
                          add_post_meta($new_post_id, $key, $data);
                      } else {
                          add_post_meta($new_post_id, $key, wp_slash($value2));
                      }
                  }
              } else {
                  add_post_meta($new_post_id, $key, wp_slash($value));
              }
          }
          $taxonomies = get_object_taxonomies($post->post_type);
          if (!empty($taxonomies) && is_array($taxonomies)) {
              foreach ($taxonomies as $taxonomy) {
                  $post_terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'slugs']);
                  wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
              }
          }
          wp_redirect(admin_url('edit.php?post_type='.$post->post_type));
      }
  }

  duplicate_pages_posts::init();

endif;
