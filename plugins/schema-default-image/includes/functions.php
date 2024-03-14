<?php
/**
 * Schema Default Image
 *
 * @version 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


add_action( 'admin_init', 'schema_wp_default_image_admin_init' );
/**
 * init
 *
 * @since 1.0
 */
function schema_wp_default_image_admin_init() {
	
	if ( ! class_exists( 'Schema_WP' ) ) return;
	
	$prefix = '_schema_default_image_';

	$fields = array(
		
		array( 
			'label'	=> 'Set Default Image', 
			'desc'	=> __('Upload a default image.', 'schema-wp-default-image'),
			'id'	=> $prefix.'id',
			'type'	=> 'image' 
		),
	);

	/**
	* Instantiate the class with all variables to create a meta box
	* var $id string meta box id
	* var $title string title
	* var $fields array fields
	* var $page string|array post type to add meta box to
	* var $context string context where to add meta box at (normal, side)
	* var $priority string meta box priority (high, core, default, low) 
	* var $js bool including javascript or not
	*/
	$schema_wp_default_image = new Schema_Custom_Add_Meta_Box( 'schema_default_image', 'Default Image', $fields, 'schema', 'normal', 'high', true );
}


add_filter('schema_wp_cpt_enabled', 'schema_wp_default_image_cpt_enabled');
/**
 * Extend the CPT Enabled array
 *
 * @since 1.0
 */
function schema_wp_default_image_cpt_enabled( $cpt_enabled ) {

	if ( empty($cpt_enabled) ) return;
	
	$args = array(
					'post_type'			=> 'schema',
					'post_status'		=> 'publish',
					'posts_per_page'	=> -1
				);
				
	$schemas_query = new WP_Query( $args );
	
	$schemas = $schemas_query->get_posts();
	
	// If there is no schema types set, return and empty array
	if ( empty($schemas) ) return array();
	
	$i = 0;
	
	foreach( $schemas as $schema ) : 
		
		// Get post meta
		$default_image	= get_post_meta( $schema->ID, '_schema_wp_default_image_id' , true );
		// Append review type
		$cpt_enabled[$i]['default_image']  = $default_image;
						
		$i++;
			
	endforeach;
 	
	// debug
	//echo '<pre>'; print_r($cpt_enabled); echo '</pre>';
	
	return $cpt_enabled;
}



add_filter('schema_output', 				'schema_wp_default_image_output');
add_filter('schema_output_blog_post', 		'schema_wp_default_image_output');
add_filter('schema_output_category_post', 	'schema_wp_default_image_output');

//add_filter( 'schema_wp_filter_media', 'schema_wp_default_image_output', 99 );


/**
 * Add Review output
 *
 * @since 1.0
 */
function schema_wp_default_image_output( $schema ) {
	
	global $post;

	// check if media is already set
	if ( isset($schema['image']) || ! empty($schema['image']) ) return $schema;
	
	// Get ref
	$schema_ref = get_post_meta( $post->ID, '_schema_ref', true );
	
	// Check for ref, if is not presented, then get out!
	if ( ! isset($schema_ref) || $schema_ref  == '' ) return $schema;
	
	$attachment_id	= get_post_meta( $schema_ref, '_schema_default_image_id', true );
	
	if ( ! isset($attachment_id) || $attachment_id == '' ) return $schema;
		
	$new_media = wp_get_attachment_image_src( $attachment_id, 'full' );
		
	$url 	= isset($new_media[0]) ? $new_media[0] : '';
	$width	= isset($new_media[1]) ? $new_media[1] : 696;
	$height	= isset($new_media[2]) ? $new_media[2] : '';
		
	$schema['image'] = array (
		'@type'		=> 'ImageObject',
		'url' 		=> $url,
		'width' 	=> $width,
		'height' 	=> $height,
	);
	
	// debug
	//echo '<pre>'; print_r($schema); echo '</pre>';
	
	return $schema;
}
