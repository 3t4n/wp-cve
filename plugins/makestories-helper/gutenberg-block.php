<?php
/**
 * Gutenberg block in wordpress
 */
function makestories_gutenberg_blocks() {
	wp_register_script('custom-cta-js', MS_PLUGIN_BASE_URL.'assets/js/gutenberg.js', array('wp-blocks'));
    wp_localize_script( 'custom-cta-js', 'MS_API_CONFIG', [
		'categories' => ms_get_categories_raw(),
		'stories' => ms_get_story_raw(),
        'widgets' => ms_get_widget_raw(),
		'icon' => MS_ROUTING['EDITOR']['icon']
    ]);

    if(function_exists("register_block_type")){
        // For all publish post display
        register_block_type('makestories/custom-block-all-published-post', array(
            'editor_script' => 'custom-cta-js'
        ) );

        // For display posts by category
        register_block_type('makestories/custom-block-category-post', array(
            'editor_script' => 'custom-cta-js'
        ) );

        // For display single post
        register_block_type('makestories/custom-block-single-post', array(
            'editor_script' => 'custom-cta-js'
        ) );

        // For display single widget
        register_block_type('makestories/custom-block-single-widget', array(
            'editor_script' => 'custom-cta-js'
        ));
    }
}

add_action('init', 'makestories_gutenberg_blocks');