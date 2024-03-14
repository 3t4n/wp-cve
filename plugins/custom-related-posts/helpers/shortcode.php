<?php

class CRP_Shortcode {

    public function __construct()
    {
        add_shortcode( 'custom-related-posts', array( $this, 'shortcode' ) );

        // Shortcode button in TinyMCE.
        add_filter( 'mce_external_plugins', array( $this, 'add_button' ) );
		add_filter( 'mce_buttons', array( $this, 'register_button' ) );
    }

    public function shortcode( $options )
    {
        return CustomRelatedPosts::get()->helper( 'output' )->output_list( get_the_ID(), $options );
    }

    public function add_button( $plugin_array ) {
		$plugin_array['custom_related_posts'] = CustomRelatedPosts::get()->coreUrl . '/assets/js/other/shortcode-button-tinymce.js';
		return $plugin_array;
	}

	public function register_button( $buttons ) {
		array_push( $buttons, 'custom_related_posts' );
		return $buttons;
	}
}