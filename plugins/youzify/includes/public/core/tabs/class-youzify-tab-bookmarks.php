<?php

class Youzify_Bookmarks_Tab {

    function __construct() {

        // add_action( 'bp_setup_nav', array( $this, 'add_bookmark_subtabs' ) );
        $this->add_bookmark_subtabs();
        add_filter( 'youzify_is_current_tab_has_children', '__return_false' );

    }

    /**
     * Tab.
     */
    function tab() {

        // Include Wall Files.
        require_once YOUZIFY_CORE . 'functions/wall/youzify-wall-general-functions.php';
        require_once YOUZIFY_CORE . 'class-youzify-wall.php';

        do_action( 'bp_bookmarks_screen' );

        bp_get_template_part( 'members/single/bookmarks' );

    }

    /**
     * Set User Bookmarks Query.
     */
    function set_user_bookmarks_query( $retval ) {

        if ( ! bp_is_current_component( 'bookmarks' ) || $retval['display_comments'] == 'stream'  ) {
            return $retval;
        }

        // Get List of bookmarked items.
        $sql = $wpdb->prepare( "SELECT item_id FROM $Youzify_bookmark_table WHERE user_id = %d AND item_type = %s", bp_displayed_user_id(), 'activity' );

        // Clean up array.
        $items_ids = wp_parse_id_list( $wpdb->get_col( $sql ) );

        // Check if private users have no activities.
        if ( empty( $items_ids ) ) {
            return $retval;
        }

        // Covert List of Activities ids to string.
        $items_ids = implode( ',', $items_ids );

        // Set Activities
        $retval['include'] = $items_ids;

        // Show Hidden Posts to admins and profile owners.
        if ( bp_core_can_edit_settings() ) {
            $retval['show_hidden'] = 1;
        }

        $retval['per_page'] = 2;

        return $retval;

    }

    /**
     * Setup Tabs.
     */
    function add_bookmark_subtabs() {

        // Add Activities Sub Tab.
        bp_core_new_subnav_item( array(
                'slug' => 'activities',
                'name' => __( 'Activities', 'youzify' ),
                'parent_slug' => 'bookmarks',
                'parent_url' => bp_displayed_user_url() . "bookmarks/",
                'screen_function' => 'youzify_bookmarks_screen',
            )
        );
    }

}