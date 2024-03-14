<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* 
 *  Fast Tags taxonomy 'fast_tag'
 */


function fast_tagger_update_count( $terms, $taxonomy ) {
    global $wpdb;

    foreach ( (array) $terms as $term ) {
            $count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->term_relationships WHERE term_taxonomy_id = %d", $term ) );

            do_action( 'edit_term_taxonomy', $term, $taxonomy );
            $wpdb->update( $wpdb->term_taxonomy, compact( 'count' ), array( 'term_taxonomy_id' => $term ) );
            do_action( 'edited_term_taxonomy', $term, $taxonomy );
    }
}


function fast_tagger_register_user_taxonomy() {
        $labels = array(
                        'name' => __( 'Fast Tags' ),
                        'singular_name' => __( 'Fast Tag' ),
                        'menu_name' => __( 'Fast Tags' ),
                        'search_items' => __( 'Search Fast Tags' ),
                        'popular_items' => __( 'Popular Fast Tags' ),
                        'all_items' => __( 'All Fast Tags' ),
                        'edit_item' => __( 'Edit Fast Tag' ),
                        'update_item' => __( 'Update Fast Tag' ),
                        'add_new_item' => __( 'Add New Fast Tag' ),
                        'new_item_name' => __( 'New Fast Tag Name' ),
                        'separate_items_with_commas' => __( 'Separate link tags with commas' ),
                        'add_or_remove_items' => __( 'Add or remove link tags' ),
                        'choose_from_most_used' => __( 'Choose from the most popular tags' ),
                    );
        $capabilities = array(
                                'manage_terms' => 'list_users', // Using 'edit_users' cap to keep this simple.
                                'edit_terms'   => 'list_users',
                                'delete_terms' => 'list_users',
                                'assign_terms' => 'list_users',
                        );
        register_taxonomy(
                            'fast_tag',
                            'user',
                            array(
                                    'public' => true,
                                    'labels' => $labels,
                                    'rewrite' => array(
                                            'with_front' => true,
                                            'slug' => 'author' // Use 'author' (default WP user slug).
                                    ),
                                    'capabilities' => $capabilities,
                                    'hierarchical'  => true,
                                    'update_count_callback' => 'fast_tagger_update_count' // Use a custom function to update the count.
                            )
        );
}


function fast_tagger_initial_terms() {
	//var_dump(get_option( 'fast_tag_link_type', true ));
	//exit;
   if( get_option( 'fast_tag_member_type', true ) == false ) {
       $terms = wp_insert_term(
                                'Member',
                                'fast_tag',
                                array(
                                  'slug' => 'member'
                                )
                            );
       update_option( 'fast_tag_member_type', $terms['term_id'] );
   }
   if( get_option( 'fast_tag_subscriber_type', true ) == false) {
       $terms = wp_insert_term(
                                'Subscriber',
                                'fast_tag',
                                array(
                                  'slug'        => 'subscriber'
                                )
                            );
       update_option( 'fast_tag_subscriber_type', $terms['term_id'] );
   }
   if( get_option( 'fast_tag_link_type', true ) == false ) {
       $terms = wp_insert_term(
                                'Link Tag',
                                'fast_tag',
                                array(
                                  'slug'        => 'link-tag'
                                )
                            );
        update_option( 'fast_tag_link_type', $terms['term_id'] );
   }
}

