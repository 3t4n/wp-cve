<?php

namespace WunderAuto\PostTypes;

/**
 * Set up custom post types
 */
class Workflow
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
     * Public method to register the workflow post type
     *
     * @param \WunderAuto\Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('init', $this, 'addPostType', PHP_INT_MAX, 0);
        $loader->addFilter('parent_file', $this, 'keepMenuOpen');
    }

    /**
     * @param string $parentFile
     *
     * @return mixed|string
     */
    public function keepMenuOpen($parentFile)
    {
        global $current_screen;
        $taxonomy = $current_screen->taxonomy;

        if ($taxonomy == 'automation-category') {
            $parentFile = 'wunderautomation';
        }
        return $parentFile;
    }

    /**
     * @param array<string, string> $columns
     *
     * @return array<string, string>
     */
    public function workflowColumns($columns)
    {
        unset($columns['date']);
        $columns['trigger'] = __('Trigger', 'wunderauto');
        $columns['order']   = __('Order', 'wunderauto');
        $columns['active']  = __('Active', 'wunderauto');
        $columns['date']    = __('Last modified', 'wunderauto');

        return $columns;
    }

    /**
     * @param string $column
     * @param int    $postId
     *
     * @return void
     */
    public function workflowSingleColumn($column, $postId)
    {
        switch ($column) {
            case 'active':
                $active = get_post_meta($postId, 'active', true);
                echo '<span class="span-active" data-active="' . esc_attr($active) . '">';
                echo $active === 'active' ?
                    esc_html__('Active', 'wunderauto') :
                    esc_html__('Disabled', 'wunderauto');
                echo '</span>';
                break;
            case 'trigger':
                $trigger = get_post_meta($postId, 'workflow_trigger', true);
                $trigger = str_replace('|WunderAuto|Types|Triggers|', '', $trigger);
                $trigger = str_replace('|', '\\', $trigger);
                assert(is_string($trigger));
                esc_html_e($trigger);
                break;
            case 'order':
                echo (int)get_post_meta($postId, 'sortorder', true);
                break;
        }
    }

    /**
     * Tell WordPress which column to make sortable
     *
     * @param array<string, string> $columns
     *
     * @return array<string, string>
     */
    public function workflowSortable($columns)
    {
        $columns['active']  = 'active';
        $columns['trigger'] = 'trigger';
        $columns['order']   = 'order';

        return $columns;
    }

    /**
     * @param array<string, string> $vars
     *
     * @return array<string, string>
     */
    public function workflowSortableRequest($vars)
    {
        global $pagenow;
        if (!is_admin()) {
            return $vars;
        }

        if (!$pagenow == 'edit.php') {
            return $vars;
        }

        $postType = isset($_GET['post_type']) ? sanitize_key($_GET['post_type']) : '';
        if ($postType !== 'automation-workflow') {
            return $vars;
        }

        if (isset($vars['orderby']) && 'order' == $vars['orderby']) {
            $vars['orderby']  = 'meta_value_num';
            $vars['meta_key'] = 'sortorder';
        }

        return $vars;
    }

    /**
     * Render the filter dropdown in manage post screen
     *
     * @return void
     */
    public function workflowFilters()
    {
        global $typenow;
        if ($typenow == 'automation-workflow') {
            $active = isset($_GET['active']) ? sanitize_key($_GET['active']) : 'all';
            $active = in_array($active, ['all', 'active', 'disabled']) ? $active : 'all';

            echo '<select name="active" id="active">';
            echo '<option value="all"' . ($active == 'all' ? 'selected' : '') . '>All active status</option>';
            echo '<option value="active"' . ($active == 'active' ? 'selected' : '') . '>Active</option>';
            echo '<option value="disabled"' . ($active == 'disabled' ? 'selected' : '') . '>Disabled</option>';
            echo "</select>";

            $trigger  = isset($_GET['trigger']) ? sanitize_text_field($_GET['trigger']) : 'all';
            $triggers = [];
            $args     = array(
                'post_type'   => 'automation-workflow',
                'numberposts' => -1,
            );
            $posts    = get_posts($args);
            foreach ($posts as $post) {
                if (!($post instanceof \WP_Post)) {
                    continue;
                }
                $metaTrigger            = (string)get_post_meta($post->ID, 'workflow_trigger', true);
                $shortTrigger           = str_replace('|WunderAuto|Types|Triggers|', '', $metaTrigger);
                $shortTrigger           = str_replace('|', '\\', $shortTrigger);
                $triggers[$metaTrigger] = $shortTrigger;
            }
            echo '<select name="trigger" id="trigger">';
            echo '<option value="all"' . ($trigger == 'all' ? 'selected' : '') . ">All triggers</option>";
            foreach ($triggers as $key => $shortTrigger) {
                echo sprintf(
                    '<option value="%s"%s>',
                    esc_attr($key),
                    $trigger == $key ? 'selected' : ''
                );
                esc_html_e($shortTrigger) . "</option>";
            }
            echo "</select>";

            $tmp = wp_dropdown_categories([
                'name'            => 'automation-category',
                'value_field'     => 'slug',
                'show_option_all' => __('All categories', 'wunderauto'),
                'taxonomy'        => 'automation-category',
                'show_count'      => 1,
            ]);
        }
    }

    /**
     * @param string $columnName
     * @param string $postType
     *
     * @return void
     */
    public function workflowQuickEdit($columnName, $postType)
    {
        if ($postType !== 'automation-workflow') {
            return;
        }

        if ($columnName == 'taxonomy-automation-category') {
            echo '<fieldset class="inline-edit-col-right"><div class="inline-edit-col">';
            echo '<div class="inline-edit-group wp-clearfix">';
            echo '</div>';
        }

        if ($columnName === 'order') {
            echo '<br>';
            echo '<div class="inline-edit-group wp-clearfix">';
            echo '<label class="inline-edit-sortorder alignleft">';
            echo '<input class="sortorder" name="sortorder" type="number" step="1" min="1" max="99" maxlength="3">';
            echo '<span class="title">' . __('Order', 'wunderauto') . '</span>';
            echo '</label>';
            echo '</div>';
        }

        if ($columnName === 'active') {
            echo '<br>';
            echo '<div class="inline-edit-group wp-clearfix">';
            echo '<label class="inline-edit-active alignleft">';
            echo '<select class="active" name="active">';
            echo '<option value="active">' . __('Active', 'wunderauto') . '</option>';
            echo '<option value="disabled">' . __('Disabled', 'wunderauto') . '</option>';
            echo '</select>';
            echo '<span class="title">' . __('Active', 'wunderauto') . '</span>';
            echo '</label>';
            echo '</div>';
            echo '</div>';
            echo '</div></fieldset>';

            echo '<input type="hidden" name="wunderautomation_save_post" value="quick_edit"/>';
            echo sprintf(
                '<input name="wunderautomation_save_post_nonce" type="hidden" value="%s"/>',
                esc_attr(wp_create_nonce('wunderautomation_quick_edit_nonce_' . 'automation-workflow'))
            );
        }
    }

    /**
     * Modify query to respect filter values
     *
     * @param \WP_Query $query
     *
     * @return void
     */
    public function workflowParseQuery($query)
    {
        global $pagenow;
        if (!is_admin()) {
            return;
        }

        if (!$pagenow == 'edit.php') {
            return;
        }

        $postType = isset($_GET['post_type']) ? sanitize_key($_GET['post_type']) : '';
        if ($postType !== 'automation-workflow') {
            return;
        }

        if (!$query->is_search) {
            return;
        }

        $active = isset($_GET['active']) ? sanitize_key($_GET['active']) : 'all';
        $active = in_array($active, ['all', 'active', 'disabled']) ? $active : 'all';
        if ($active != 'all') {
            if (!isset($query->query_vars['meta_query'])) {
                $query->query_vars['meta_query'] = [];
            }
            $query->query_vars['meta_query'][] = [
                'key'     => 'active',
                'value'   => $active,
                'compare' => '='
            ];
        }

        $trigger = isset($_GET['trigger']) ? sanitize_text_field($_GET['trigger']) : 'all';
        if ($trigger != 'all') {
            if (!isset($query->query_vars['meta_query'])) {
                $query->query_vars['meta_query'] = [];
            }
            $query->query_vars['meta_query'][] = [
                'key'     => 'workflow_trigger',
                'value'   => $trigger,
                'compare' => '='
            ];
        }
    }

    /**
     * Set up workflow type
     *
     * @return void
     */
    public function addPostType()
    {
        $labels = [
            'name'                       => _x('Categories', 'Taxonomy General Name', 'text_domain'),
            'singular_name'              => _x('Category', 'Taxonomy Singular Name', 'text_domain'),
            'menu_name'                  => __('Taxonomy', 'text_domain'),
            'all_items'                  => __('All Items', 'text_domain'),
            'parent_item'                => __('Parent Item', 'text_domain'),
            'parent_item_colon'          => __('Parent Item:', 'text_domain'),
            'new_item_name'              => __('New Item Name', 'text_domain'),
            'add_new_item'               => __('Add New Item', 'text_domain'),
            'edit_item'                  => __('Edit Item', 'text_domain'),
            'update_item'                => __('Update Item', 'text_domain'),
            'view_item'                  => __('View Item', 'text_domain'),
            'separate_items_with_commas' => __('Separate items with commas', 'text_domain'),
            'add_or_remove_items'        => __('Add or remove items', 'text_domain'),
            'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
            'popular_items'              => __('Popular Items', 'text_domain'),
            'search_items'               => __('Search Items', 'text_domain'),
            'not_found'                  => __('Not Found', 'text_domain'),
            'no_terms'                   => __('No items', 'text_domain'),
            'items_list'                 => __('Items list', 'text_domain'),
            'items_list_navigation'      => __('Items list navigation', 'text_domain'),
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => false,
            'public'            => false,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_admin_column' => true,
            'show_tagcloud'     => false,
        ];
        register_taxonomy('automation-category', ['automation-workflow'], $args);

        $labels = [
            'name'                  => _x('Workflows', 'Post Type General Name', 'wunderauto'),
            'singular_name'         => _x('Workflow', 'Post Type Singular Name', 'wunderauto'),
            'menu_name'             => __('Post Types', 'wunderauto'),
            'name_admin_bar'        => __('Post Type', 'wunderauto'),
            'archives'              => __('Item Archives', 'wunderauto'),
            'attributes'            => __('Item Attributes', 'wunderauto'),
            'parent_item_colon'     => __('Parent workflow:', 'wunderauto'),
            'all_items'             => __('Workflows', 'wunderauto'),
            'add_new_item'          => __('Add New Workflow', 'wunderauto'),
            'add_new'               => __('Add Workflow', 'wunderauto'),
            'new_item'              => __('New Workflow', 'wunderauto'),
            'edit_item'             => __('Edit Workflow', 'wunderauto'),
            'update_item'           => __('Update workflow', 'wunderauto'),
            'view_item'             => __('View workflow', 'wunderauto'),
            'view_items'            => __('View workflows', 'wunderauto'),
            'search_items'          => __('Search workflows', 'wunderauto'),
            'not_found'             => __('Not found', 'wunderauto'),
            'not_found_in_trash'    => __('Not found in Trash', 'wunderauto'),
            'insert_into_item'      => __('Insert into workflow', 'wunderauto'),
            'uploaded_to_this_item' => __('Uploaded to this workflow', 'wunderauto'),
            'items_list'            => __('Workflows list', 'wunderauto'),
            'items_list_navigation' => __('Workflows list navigation', 'wunderauto'),
            'filter_items_list'     => __('Filter workflows list', 'wunderauto'),
        ];

        $args = [
            'label'               => __('Workflow', 'wunderauto'),
            'description'         => __('Market automation workflow', 'wunderauto'),
            'labels'              => $labels,
            'supports'            => ['title'],
            'taxonomies'          => ['automation-category'],
            'hierarchical'        => false,
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => $this->menuSlug,
            'menu_position'       => 5,
            'show_in_admin_bar'   => false,
            'show_in_nav_menus'   => false,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => false,
            'capability_type'     => 'page',
        ];

        register_post_type('automation-workflow', $args);
        add_filter('manage_automation-workflow_posts_columns', [$this, 'workflowColumns']);
        add_action('manage_automation-workflow_posts_custom_column', [$this, 'workflowSingleColumn'], 10, 2);
        add_filter('manage_edit-automation-workflow_sortable_columns', [$this, 'workflowSortable'], 10, 1);
        add_filter('request', [$this, 'workflowSortableRequest'], 10, 1);
        add_action('restrict_manage_posts', [$this, 'workflowFilters']);
        add_filter('parse_query', [$this, 'workflowParseQuery']);
        add_action('quick_edit_custom_box', [$this, 'workflowQuickEdit'], 10, 2);
    }
}
