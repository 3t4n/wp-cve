<?php

require_once( GFI_PLUGIN_DIR . '/lib/metaboxes/init.php' );

function gfi_get_post_types() {
	$types = array();
	$post_types = get_post_types( array( 'public' => true ) );
	foreach ( $post_types as $post_type ) {
		if ( post_type_supports( $post_type, 'thumbnail' ) ) {
			$types[] = $post_type;
		}
	}
	
	return apply_filters( 'gfi_post_types', $types );
}

function gfi_get_image_sizes() {
	$sizes = genesis_get_image_sizes();
	$return = array();
	
	foreach ( $sizes as $name => $size ) {
		$return[ $name ] = ucfirst( $name ) . ' (' . $size['width'] . 'x' . $size['height'] . ')';
	}
	
	return $return;
}

add_action( 'cmb2_init', 'gfi_register_metabox' );
function gfi_register_metabox() {
	
	$metabox = new_cmb2_box( array(
		'id'            => 'genesis_post_image',
		'title'         => __( 'Featured Image Size', GFI_DOMAIN ),
		'object_types'  => gfi_get_post_types(), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );
		
	$metabox->add_field( array(
		'name'    => __( 'Featured Image Select', GFI_DOMAIN ),
		'desc'    => __( 'Select a Featured Image Size', GFI_DOMAIN ),
		'id'      => GFI_PREFIX . 'custom_feat_img',
		'type'    => 'select',
		'options' => gfi_get_image_sizes(),
		'show_option_none' => true,
	) );
	
}
