<?php

namespace Directorist_WPML_Integration\Controller\Ajax;

use Directorist_WPML_Integration\Helper\Response;
use Directorist_WPML_Integration\Helper\WPML_Helper;

class Get_Directory_Type_Translations {

    /**
     * Constructor
     * 
     * @return void
     */
    function __construct() {
        add_action( 'wp_ajax_get_directory_type_translations', [ $this, 'get_directory_type_translations_data' ] );
        add_action( 'wp_ajax_create_directory_type_translation', [ $this, 'create_directory_type_translation' ] );
    }

    /**
     * Get directory type translations data
     * 
     * @return Response
     */
    public function get_directory_type_translations_data() {

        $response = new Response();

        if ( ! directorist_verify_nonce() ) {
            $response->message = __( 'Access denied.', 'directorist-wpml-integration' );
            wp_send_json( $response->toArray() );
        }

        $data = [
            'wpml_current_language'          => apply_filters( 'wpml_current_language', NULL ),
            'wpml_active_languages'          => $this->get_wpml_active_languages(),
            'translations'                   => $this->get_directory_type_translations(),
            'translation_edit_link_template' => admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-directory-types&listing_type_id=__ID__&action=edit&lang=__LANGUAGE__' ),
        ];

        $response->success = true;
        $response->data = $data;

        wp_send_json( $response->toArray() );
    }

    /**
     * Create directory type translation
     * 
     * @return array Response
     */
    public function create_directory_type_translation() {
        $response = new Response();

        if ( ! directorist_verify_nonce() ) {
            $response->message = __( 'Access denied.', 'directorist-wpml-integration' );
            wp_send_json( $response->toArray() );
        }

        $directory_type_id          = ( isset( $_REQUEST['directory_type_id'] ) ) ? sanitize_text_field( $_REQUEST['directory_type_id'] ) : 0;
        $taranslation_language_code = ( isset( $_REQUEST['taranslation_language_code'] ) ) ? sanitize_text_field( $_REQUEST['taranslation_language_code'] ) : '';

        if ( empty( $directory_type_id ) ) {
            $response->message = __( 'Directory type ID is required', 'directorist-wpml-integration' );
            wp_send_json( $response->toArray() );
        }

        if ( empty( $taranslation_language_code ) ) {
            $response->message = __( 'Taranslation language code is required', 'directorist-wpml-integration' );
            wp_send_json( $response->toArray() );
        }

        $taxonomy = ATBDP_DIRECTORY_TYPE;

        $directory_type_language_info = WPML_Helper::get_language_info( $directory_type_id, $taxonomy );
        
        if ( empty( $directory_type_language_info ) ) {
            WPML_Helper::assign_language( $directory_type_id, $taxonomy );
            $directory_type_language_info = WPML_Helper::get_language_info( $directory_type_id, $taxonomy );
        }

        if ( empty( $directory_type_language_info ) ) {
            $response->message = __( 'There is no language is assingned to this directory', 'directorist-wpml-integration' );
            wp_send_json( $response->toArray() );
        }

        
        $term = get_term_by( 'id', $directory_type_id, $taxonomy );

        if ( is_wp_error( $term ) ) {
            $response->message = __( 'The term ID is not valid', 'directorist-wpml-integration' );
            wp_send_json( $response->toArray() );
        }

        $new_term_name  = $term->name . ' - ' . strtoupper( $taranslation_language_code );
        $duplicate_term = WPML_Helper::create_duplicate_term( $directory_type_id, $taxonomy, $new_term_name );

        if ( ! $duplicate_term->success ) {
            $response->message = $duplicate_term->message;
            wp_send_json( $response->toArray() );
        }
        
        $translation_term_id = $duplicate_term->data[ 'new_term_id' ];
        $set_translation = WPML_Helper::set_post_translation( $directory_type_id, $translation_term_id, $taranslation_language_code, $taxonomy );

        if ( ! $set_translation->success ) {
            $response->message = $set_translation->message;
            wp_send_json( $response->toArray() );
        }

        $response->success = true;
        $response->message = __( 'The translation has been created successfully', 'directorist-wpml-integration' );
        $response->data = [
            'translation_term_id' => $translation_term_id,
            'edit_link' => admin_url( 'edit.php?post_type=at_biz_dir&page=atbdp-directory-types&listing_type_id=' . $translation_term_id . '&action=edit' ),
        ];

        wp_send_json( $response->toArray() );
    }

    
    /**
     * Get WPML ctive languages
     * 
     * @return array
     */
    public function get_wpml_active_languages() {
        return apply_filters( 'wpml_active_languages', NULL, 'orderby=name&order=asc' );
    }

    /**
     * Get directory type translations
     * 
     * @return array
     */
    public function get_directory_type_translations() {
        $taxonomy     = ATBDP_DIRECTORY_TYPE;
        $element_type = apply_filters( 'wpml_element_type', $taxonomy );

        $directory_types = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ]);

        if ( is_wp_error( $directory_types ) ) {
            return [];
        }

        $directory_type_translations = [];

        foreach( $directory_types as $directory_type ) {
            $translation_id = apply_filters( 'wpml_element_trid', NULL, $directory_type->term_id, $element_type );
            $translation    = apply_filters( 'wpml_get_element_translations', NULL, $translation_id, $element_type );

            $directory_type_translations[ $directory_type->term_id ] = $translation;
        }

        return $directory_type_translations;
    }

}