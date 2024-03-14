<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}

function gutenberg_examples_dynamic_render_callback(  ) {
	return do_shortcode('[dsgvo_policy]');
}

function gutenberg_examples_dynamic_render_callback_user_data_remove(  ) {
	return do_shortcode('[dsgvo_user_remove_form]');
}

function gutenberg_examples_dynamic_render_callback_opt_in_out(  ) {
	return do_shortcode('[dsgvo_service_control]');
}

function gutenberg_examples_dynamic_render_callback_show_user_data(  ) {
	return do_shortcode('[dsgvo_show_user_data]');
}

function gutenberg_examples_dynamic_render_callback_imprint(  ) {
	return do_shortcode('[dsgvo_imprint]');
}

function gutenberg_examples_dynamic_render_callback_facebook_like(  ) {
	return do_shortcode('[dsgvo_facebook_like]');
}

function gutenberg_examples_dynamic_render_callback_facebook_comments(  ) {
	return do_shortcode('[dsgvo_facebook_comments]');
}

function gutenberg_examples_dynamic_render_callback_shareaholic(  ) {	
	return do_shortcode('[dsgvo_shareaholic]');
}

function gutenberg_examples_dynamic_render_callback_twitter_tweet(  ) {	
	return do_shortcode('[dsgvo_twitter_button]');
}

function gutenberg_examples_dynamic_render_callback_linkedin(  ) {	
	return do_shortcode('[dsgvo_linkedin]');
}
 
function gutenberg_examples_dynamic() {
	if (is_admin()) {
		wp_enqueue_script('dsgvoaio-block-script', plugins_url( '../../assets/js/blocks.js', __FILE__ ), array('wp-block-editor', 'wp-editor', 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n', 'wp-polyfill'));
		wp_localize_script('dsgvoaio-block-script', 'dsgvoaio_blockparms', array(
				'dsgvoaio_imgdir' => plugins_url( '../../assets/img/', __FILE__ )
			)
		);
	}
	
    register_block_type( 'dsgvo-all-in-one-for-wp/opt-in-out', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_opt_in_out'
    ) );
    register_block_type( 'dsgvo-all-in-one-for-wp/show-user-data', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_show_user_data'
    ) );
    register_block_type( 'dsgvo-all-in-one-for-wp/remove-user-data', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_user_data_remove'
    ) );
    register_block_type( 'dsgvo-all-in-one-for-wp/privacy-policy', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback'
    ) );
    register_block_type( 'dsgvo-all-in-one-for-wp/imprint', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_imprint'
    ) ); 
    register_block_type( 'dsgvo-all-in-one-for-wp/facebook-like', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_facebook_like'
    ) ); 	
    register_block_type( 'dsgvo-all-in-one-for-wp/facebook-comments', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_facebook_comments'
    ) ); 		
    register_block_type( 'dsgvo-all-in-one-for-wp/shareaholic', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_shareaholic'
    ) ); 	
    register_block_type( 'dsgvo-all-in-one-for-wp/twitter-tweet', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_twitter_tweet'
    ) ); 	
    register_block_type( 'dsgvo-all-in-one-for-wp/linkedin', array(
        'editor_script' => 'dsgvoaio-block-script',
        'render_callback' => 'gutenberg_examples_dynamic_render_callback_linkedin'
    ) ); 		
}
add_action( 'init', 'gutenberg_examples_dynamic' );

?>