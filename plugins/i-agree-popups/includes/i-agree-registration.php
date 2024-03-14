<?php

/**
 * I Agree! Popups
 *
 * @package   I_Agree_Popups
 * @license   GPLv2 or later
**/

/**
 * Register post types and taxonomies.
 *
 * @package I_Agree_Popups
**/

class I_Agree_Registration {

    public $post_type = 'i-agree-popup';
    
    // Initialise functions
    public function init() {
        
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_filter( 'post_updated_messages', array( $this, 'popup_updated_messages' ) );
        add_filter('post_row_actions', array( $this, 'remove_quick_edit' ),10,2);
        
    }
    
    public function register() {
        $this->register_post_type();
    }

    /**
     * Register the Popup post type
     *
     * @since 1.0
    **/
    function register_post_type() {
        $labels = array(
            'name'               => __( 'I Agree! Popups', 'i-agree-popups' ),
            'singular_name'      => __( 'Popup', 'i-agree-popups' ),
            'add_new'            => __( 'Create Popup', 'i-agree-popups' ),
            'add_new_item'       => __( 'Create Popup', 'i-agree-popups' ),
            'edit_item'          => __( 'Edit Popup', 'i-agree-popups' ),
            'new_item'           => __( 'New Popup', 'i-agree-popups' ),
            'all_items'          => __( 'All Popups', 'i-agree-popups' ),
            'view_item'          => __( 'View Popup', 'i-agree-popups' ),
            'search_items'       => __( 'Search Popups', 'i-agree-popups' ),
            'not_found'          => __( 'No Popups found', 'i-agree-popups' ),
            'not_found_in_trash' => __( 'No Popups in the trash', 'i-agree-popups' ),
        );

        $supports = array(
            'title',
            'editor',
            'revisions',
        );

        $args = array(
            'labels'             => $labels,
            'supports'           => $supports,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 30,
            'menu_icon'          => 'dashicons-forms',
        );

        $args = apply_filters( 'i_agree_popups_args', $args );

        register_post_type( $this->post_type, $args );
        
    }

    /**
     * Amend 'Post Updated' messages
     *
     * @since 1.0
    **/
    function popup_updated_messages( $messages ) {
        
        $post             = get_post();
        $post_type        = get_post_type( $post );
        $post_type_object = get_post_type_object( $post_type );

        $messages['i-agree-popup'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => __( 'Popup updated.', 'i-agree-popups' ),
            2  => __( 'Custom field updated.', 'i-agree-popups' ),
            3  => __( 'Custom field deleted.', 'i-agree-popups' ),
            4  => __( 'Popup updated.', 'i-agree-popups' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Popup restored to revision from %s', 'i-agree-popups' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6  => __( 'Popup created.', 'i-agree-popups' ),
            7  => __( 'Popup saved.', 'i-agree-popups' ),
            8  => __( 'Popup submitted.', 'i-agree-popups' ),
            9  => sprintf(
                __( 'Popup scheduled for: <strong>%1$s</strong>.', 'i-agree-popups' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i', 'i-agree-popups' ), strtotime( $post->post_date ) )
            ),
            10 => __( 'Popup draft updated.', 'i-agree-popups' )
        );
        
        return $messages;
        
    }
    
    /**
     * Remove 'Quick Edit' in Admin
     *
     * @since 1.0
    **/
    function remove_quick_edit( $actions ) {
        global $post;
        if( $post->post_type == 'i-agree-popup' ) {
            unset($actions['inline hide-if-no-js']);
        }
        return $actions;
    }


}