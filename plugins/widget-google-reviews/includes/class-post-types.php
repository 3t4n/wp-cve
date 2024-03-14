<?php

namespace WP_Rplg_Google_Reviews\Includes;

class Post_Types {

    const FEED_POST_TYPE = 'grw_feed';

    public function register() {
        add_action('init', array($this, 'register_post_types'));

        add_action('trash_' . self::FEED_POST_TYPE, array($this, 'trash'), 10, 2);
        add_action('publish_' . self::FEED_POST_TYPE, array($this, 'publish'), 10, 2);
    }

    public function register_post_types() {
        $this->register_feed_post_type();
    }

    public function register_feed_post_type() {
        $labels = array(
            'name'                  => _x('Reviews widgets', 'Post Type General Name', 'widget-google-reviews'),
            'singular_name'         => _x('Reviews widget', 'Post Type Singular Name', 'widget-google-reviews'),
            'menu_name'             => __('Reviews widgets', 'widget-google-reviews'),
            'name_admin_bar'        => __('Reviews widget', 'widget-google-reviews'),
            'archives'              => __('Reviews Feed Archives', 'widget-google-reviews'),
            'attributes'            => __('Reviews Feed Attributes', 'widget-google-reviews'),
            'parent_item_colon'     => __('Parent Reviews Feed:', 'widget-google-reviews'),
            'all_items'             => __('Widgets', 'widget-google-reviews'),
            'add_new_item'          => __('Add New Reviews Feed', 'widget-google-reviews'),
            'add_new'               => __('Add Reviews Feed', 'widget-google-reviews'),
            'new_item'              => __('New Reviews Feed', 'widget-google-reviews'),
            'edit_item'             => __('Edit Reviews Feed', 'widget-google-reviews'),
            'update_item'           => __('Update Reviews Feed', 'widget-google-reviews'),
            'view_item'             => __('View Reviews Feed', 'widget-google-reviews'),
            'view_items'            => __('View Reviews Feeds', 'widget-google-reviews'),
            'search_items'          => __('Search Reviews Widgets', 'widget-google-reviews'),
            'not_found'             => __('Not found', 'widget-google-reviews'),
            'not_found_in_trash'    => __('Not found in Trash', 'widget-google-reviews'),
            'featured_image'        => __('Featured Image', 'widget-google-reviews'),
            'set_featured_image'    => __('Set featured image', 'widget-google-reviews'),
            'remove_featured_image' => __('Remove featured image', 'widget-google-reviews'),
            'use_featured_image'    => __('Use as featured image', 'widget-google-reviews'),
            'insert_into_item'      => __('Insert into item', 'widget-google-reviews'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'widget-google-reviews'),
            'items_list'            => __('Reviews Feeds list', 'widget-google-reviews'),
            'items_list_navigation' => __('Reviews Feeds list navigation', 'widget-google-reviews'),
            'filter_items_list'     => __('Filter items list', 'widget-google-reviews'),
        );

        $args = array(
            'label'               => __('Reviews Feed', 'widget-google-reviews'),
            'labels'              => $labels,
            'supports'            => array('title'),
            'taxonomies'          => array(),
            'hierarchical'        => false,
            'public'              => false,
            'show_in_rest'        => false,
            'show_ui'             => true,
            'show_in_menu'        => 'grw',
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capabilities'        => array('create_posts' => 'do_not_allow'),
            'map_meta_cap'        => true,
        );

        register_post_type(self::FEED_POST_TYPE, $args);
    }

    public function trash($ID) {
        $feed_ids = get_option('grw_feed_ids');
        if (!empty($feed_ids)) {
            $ids = explode(",", $feed_ids);
            if (in_array($ID, $ids)) {
                $ids = array_diff($ids, [$ID]);
                update_option('grw_feed_ids', implode(",", $ids));
            }
        }
    }

    public function publish($ID) {
        $feed_ids = get_option('grw_feed_ids');
        $ids = empty($feed_ids) ? array($ID) : explode(",", $feed_ids);
        if (!in_array($ID, $ids)) {
            array_push($ids, $ID);
        }
        update_option('grw_feed_ids', implode(",", $ids));
    }
}
