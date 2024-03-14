<?php

namespace WunderAuto\PostTypes;

/**
 * Set up custom post types
 */
class ReTrigger
{
    /**
     * @var string
     */
    private $menuSlug;

    /**
     * @param string $menuSlug
     */
    public function __construct($menuSlug)
    {
        $this->menuSlug = $menuSlug;
    }

    /**
     * Public method to register the re-trigger post type
     *
     * @param \WunderAuto\Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('init', $this, 'addPostType', PHP_INT_MAX, 0);
    }

    /**
     * Set up ReTriggerHandler Trigger type
     *
     * @return void
     */
    public function addPostType()
    {
        $labels = [
            'name'                  => _x('Re-Triggers', 'Post Type General Name', 'wunderauto'),
            'singular_name'         => _x('Re-Trigger', 'Post Type Singular Name', 'wunderauto'),
            'menu_name'             => __('Re-Triggers', 'wunderauto'),
            'name_admin_bar'        => __('Re-Trigger', 'wunderauto'),
            'archives'              => __('Item Archives', 'wunderauto'),
            'attributes'            => __('Item Attributes', 'wunderauto'),
            'parent_item_colon'     => __('Parent re-trigger:', 'wunderauto'),
            'all_items'             => __('Re-Triggers', 'wunderauto'),
            'add_new_item'          => __('Add New ReTrigger', 'wunderauto'),
            'add_new'               => __('Add Re-Trigger', 'wunderauto'),
            'new_item'              => __('New Re-Trigger', 'wunderauto'),
            'edit_item'             => __('Edit Re-Trigger', 'wunderauto'),
            'update_item'           => __('Update Re-Trigger', 'wunderauto'),
            'view_item'             => __('View Re-Trigger', 'wunderauto'),
            'view_items'            => __('View Re-Trigger', 'wunderauto'),
            'search_items'          => __('Search Re-Trigger', 'wunderauto'),
            'not_found'             => __('Not found', 'wunderauto'),
            'not_found_in_trash'    => __('Not found in Trash', 'wunderauto'),
            'insert_into_item'      => __('Insert into Re-Trigger', 'wunderauto'),
            'uploaded_to_this_item' => __('Uploaded to this Re-Trigger', 'wunderauto'),
            'items_list'            => __('Re-Trigger list', 'wunderauto'),
            'items_list_navigation' => __('Re-Trigger list navigation', 'wunderauto'),
            'filter_items_list'     => __('Filter Re-Triggers list', 'wunderauto'),
        ];

        $args = [
            'label'               => __('Re-Trigger', 'wunderauto'),
            'description'         => __('Re-Triggers', 'wunderauto'),
            'labels'              => $labels,
            'supports'            => ['title'],
            'taxonomies'          => [],
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => $this->menuSlug,
            'menu_position'       => 10,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'page',
        ];

        register_post_type('automation-retrigger', $args);
    }
}
