<?php

class wpApplaudPostSettings {
	function __construct() 
	{
		//Add wordpress meta box
		add_action( 'add_meta_boxes', array(&$this, 'wp_applaud_add_meta_box') );
		add_action( 'save_post', array(&$this, 'wp_applaud_metabox_save') );
		
		//Add gutenberg support
		add_action( 'init', array(&$this, 'wp_applaud_backend_gutenberg_scripts_register') );
		add_action( 'enqueue_block_editor_assets', array(&$this, 'wp_applaud_backend_gutenberg_scripts_enqueue') );
		add_action( 'enqueue_block_assets', array(&$this, 'wp_applaud_backend_gutenberg_style_enqueue') );
	}

	//Register exclude Meta Box
	function wp_applaud_add_meta_box() {

		//Check if gutenberg is active as editor
		/*$current_screen = get_current_screen();
		if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) return;*/

		$wp_applaud_exclude_type = array();

		$options = get_option( 'wp_applaud_settings' );
		if( isset($options['add_to_posts']) && $options['add_to_posts'] == "1" ) array_push($wp_applaud_exclude_type, 'post');
		if( isset($options['add_to_pages']) && $options['add_to_pages'] == "1" ) array_push($wp_applaud_exclude_type, 'page');

		add_meta_box( 'wp_applaud_meta_box', __( 'WP Applaud', 'wpapplaud' ), array(&$this, 'wp_applaud_metabox_callback'), $wp_applaud_exclude_type );
	}

	//exclude Metabox Callback
	function wp_applaud_metabox_callback( $post ) {
		$value = get_post_meta( $post->ID, '_wp_applaud_exclude', true );
		$value = $value ? "checked" : "";
		?>
		<input type="checkbox" name="_wp_applaud_exclude" id="_wp_applaud_exclude" value="1" <?php echo $value; ?> />
		<label for="_wp_applaud_exclude"><?php _e( 'Hide Applaud for this post?', 'wpapplaud' ) ?></label>
		<?php
	}

	//exclude Metabox Save
	function wp_applaud_metabox_save( $post_id ) {

		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

		if( isset( $_POST['_wp_applaud_exclude'] ) )
	        update_post_meta( $post_id, '_wp_applaud_exclude', esc_attr( $_POST['_wp_applaud_exclude'] ) );
	    else
	    	update_post_meta( $post_id, '_wp_applaud_exclude', false );
	}


	function wp_applaud_backend_gutenberg_scripts_register() {
	    wp_register_script(
	        'wp-applaud-backend-gutenberg-js',
	        plugins_url( '/assets/scripts/dist/index.js', dirname(__FILE__) ),
	        array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-compose' )
	    );

	    register_meta( 'post', '_wp_applaud_exclude', array(
		    'show_in_rest' => true,
		    'auth_callback' => '__return_true',
		    'single' => true,
		    'type' => 'boolean',
		) );

		wp_register_style(
	        'wp-applaud-backend-gutenberg-css',
	        plugins_url( '/assets/styles/plugin-sidebar.css', dirname(__FILE__) )
	    );
	}

	function wp_applaud_backend_gutenberg_scripts_enqueue() {
		global $post;

		$wp_applaud_exclude_type = array();

		$options = get_option( 'wp_applaud_settings' );
		if( isset($options['add_to_posts']) && $options['add_to_posts'] == "1" ) array_push($wp_applaud_exclude_type, 'post');
		if( isset($options['add_to_pages']) && $options['add_to_pages'] == "1" ) array_push($wp_applaud_exclude_type, 'page');

		if(!in_array($post->post_type, $wp_applaud_exclude_type))  return;

	    wp_enqueue_script( 'wp-applaud-backend-gutenberg-js' );
	}

	function wp_applaud_backend_gutenberg_style_enqueue() {
		global $post;

		$wp_applaud_exclude_type = array();

		$options = get_option( 'wp_applaud_settings' );
		if( isset($options['add_to_posts']) && $options['add_to_posts'] == "1" ) array_push($wp_applaud_exclude_type, 'post');
		if( isset($options['add_to_pages']) && $options['add_to_pages'] == "1" ) array_push($wp_applaud_exclude_type, 'page');

		if(!in_array($post->post_type, $wp_applaud_exclude_type))  return;

	    wp_enqueue_style( 'wp-applaud-backend-gutenberg-css' );
	}

}

?>