<?php

class CRP_Assets {

    private $assets = array();

    public function __construct()
    {
        add_action( 'wp_enqueue_scripts', array( $this, 'public_enqueue' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
        add_action( 'enqueue_block_editor_assets', array( $this, 'block_assets' ) );
    }

    public function public_enqueue()
    {
        wp_enqueue_style( 'crp-public', CustomRelatedPosts::get()->coreUrl . '/dist/public.css', array(), CRP_VERSION, 'all' );
    }

    public function admin_enqueue()
    {
        wp_enqueue_style( 'crp-admin', CustomRelatedPosts::get()->coreUrl . '/dist/admin.css', array(), CRP_VERSION, 'all' );
        wp_enqueue_script( 'crp-admin', CustomRelatedPosts::get()->coreUrl . '/dist/admin.js', array( 'jquery' ), CRP_VERSION, true );

        // Post types for search.
        $all_post_types = get_post_types( '', 'objects' );
        $search_post_types = CustomRelatedPosts::setting( 'general_post_types' );

        $post_types = array_filter( $all_post_types, function( $post_type ) use ( $search_post_types ) {
            return in_array( $post_type->name, $search_post_types );
        } );
        $post_types = wp_list_pluck( $post_types, 'label' );
        
        wp_localize_script( 'crp-admin', 'crp_admin', array(
			'ajax_url' => CustomRelatedPosts::get()->helper('ajax')->url(),
            'core_url' => CustomRelatedPosts::get()->coreUrl,
            'nonce' => wp_create_nonce( 'crp_admin' ),
            'remove_image_to' => '<img src="' . CustomRelatedPosts::get()->coreUrl . '/assets/images/minus.png" class="crp_remove_relation crp_remove_relation_to" title="' . __( 'Remove link to this post', 'custom-related-posts' ). '" />',
            'remove_image_both' => '<img src="' . CustomRelatedPosts::get()->coreUrl . '/assets/images/minus.png" class="crp_remove_relation crp_remove_relation_both" title="' . __( 'Remove both link to and from this post', 'custom-related-posts' ). '" />',
            'remove_image_from' => '<img src="' . CustomRelatedPosts::get()->coreUrl . '/assets/images/minus.png" class="crp_remove_relation crp_remove_relation_from" title="' . __( 'Remove link from this post', 'custom-related-posts' ). '" />',
            'post_types' => $post_types,
		));
    }

    public function block_assets() {
        wp_enqueue_style( 'crp-blocks', CustomRelatedPosts::get()->coreUrl . '/dist/blocks.css', array(), CRP_VERSION, 'all' );
		wp_enqueue_script( 'crp-blocks', CustomRelatedPosts::get()->coreUrl . '/dist/blocks.js', array( 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-data', 'wp-edit-post' ), CRP_VERSION );
	}
}