<?php

namespace Directorist_WPML_Integration\Controller\Hook;

use Directorist_WPML_Integration\Helper\WPML_Helper;

class Listings_Actions {

    public static $instance = null;

     /**
     * Constuctor
     * 
     * @return void
     */
    public function __construct() {
        add_filter( 'directorist_show_admin_edit_listing_directory_type_nav', '__return_true', 20, 1 );
        add_filter( 'directorist_should_update_directory_type', '__return_false', 20, 1 );
        add_action( 'icl_make_duplicate', [ $this, 'update_directory_type_after_listing_duplicate' ], 20, 4 );
        add_action( 'post_updated', [ $this, 'update_directory_type_after_listing_update' ], 20, 1 );
        add_filter( 'wpml_pro_translation_completed', [ $this, 'update_directory_type_after_listing_translation' ], 20, 3 );
    }

    /**
     * Update Directory Type After Listing Translation
     * 
     * @param int $new_post_id
     * 
     * @return int New post ID
     */
    public function update_directory_type_after_listing_translation( $new_post_id = 0, $fields = [], $job = null ) {

        if ( ATBDP_POST_TYPE !== get_post_type( $new_post_id ) ) {
            return $new_post_id;
        }
        
        $current_lang = apply_filters( 'wpml_current_language', null );
        $to_lang = ( ! empty( $job ) ) ? $job->language_code : $current_lang;

        do_action( 'wpml_switch_language', $to_lang );

        $directory_type    = get_the_terms( $new_post_id, ATBDP_DIRECTORY_TYPE );
        $directory_type_id = ( ! empty( $directory_type ) && is_array( $directory_type ) ) ? $directory_type[0]->term_id : 0;

        update_post_meta( $new_post_id, '_directory_type', $directory_type_id );

        do_action( 'wpml_switch_language', $current_lang );

        return $new_post_id;
    }

    /**
     * Update directory type after listing duplicate
     * 
     * @param int $master_post_id
     * @param string $lang
     * @param array $post_array
     * @param int $post_id
     * 
     * @return void
     */
    public function update_directory_type_after_listing_duplicate( $master_post_id, $lang, $post_array, $post_id ) {

        if ( get_post_type( $post_id ) !== ATBDP_POST_TYPE ) {
            return;
        }

        $directory_type_meta_id = get_post_meta( $post_id, '_directory_type', true );
        $directory_type_term    = get_the_terms( $post_id, ATBDP_DIRECTORY_TYPE );
        $directory_type_id      = ( ! is_wp_error( $directory_type_term ) && ! empty( $directory_type_term ) ) ? $directory_type_term[0]->term_id : $directory_type_meta_id;
        
        $directory_type_translations = WPML_Helper::get_element_translations( $directory_type_id, ATBDP_DIRECTORY_TYPE );

        if ( empty( $directory_type_translations ) ) {
            return;
        }

        if ( empty( $directory_type_translations[ $lang ] ) ) {
            return;
        }

        $translated_directory_type_id = $directory_type_translations[ $lang ]->term_id;
        
        update_post_meta( $post_id, '_directory_type', $translated_directory_type_id );
    }

    /**
     * Update directory type meta after listing update
     * 
     * @param int $post_id
     * @return void
     */
    public function update_directory_type_after_listing_update( $post_id ) {

        if ( get_post_type( $post_id ) !== ATBDP_POST_TYPE ) {
            return;
        }

        $directory_type_id = ( isset( $_REQUEST['directory_type'] ) ) ? sanitize_text_field( $_REQUEST['directory_type'] ) : 0;

        $log[ 'post_id' ] = $post_id;
        $log[ 'directory_type_id_{_REQUEST}' ] = $directory_type_id;

        if ( empty( $directory_type_id ) ) {
            $directory_type_meta       = get_post_meta( $post_id, '_directory_type', true );
            $default_directory_type_id = ( ! empty( $directory_type_meta ) ) ? $directory_type_meta : directorist_default_directory();
            $directory_type_term       = get_the_terms( $post_id, ATBDP_DIRECTORY_TYPE );
            $directory_type_id         = ( ! is_wp_error( $directory_type_term ) && ! empty( $directory_type_term ) ) ? $directory_type_term[0]->term_id : $default_directory_type_id;
        }

        $listings_translations       = WPML_Helper::get_element_translations( $post_id, ATBDP_POST_TYPE );
        $directory_type_translations = WPML_Helper::get_element_translations( $directory_type_id, ATBDP_DIRECTORY_TYPE );

        if ( ! empty( $listings_translations ) ) {
            foreach( $listings_translations as $language_key => $listings_translation ) {
                $listing_id        = $listings_translation->element_id;
                $directory_type_id = ( ! empty( $directory_type_translations ) && ! empty( $directory_type_translations[ $language_key ] ) ) ? $directory_type_translations[ $language_key ]->term_id : 0;
                $directory_type_id = ( ! empty( $directory_type_id ) ) ?  ( int ) $directory_type_id : '';
                
                update_post_meta( $listing_id, '_directory_type', $directory_type_id );
                wp_set_object_terms( $listing_id, $directory_type_id, ATBDP_DIRECTORY_TYPE );
            }
        }

    }
}
