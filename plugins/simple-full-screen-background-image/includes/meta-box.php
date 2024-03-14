<?php

function sfsb_add_meta_box() {
	$posttypes = get_post_types(array('show_ui' => true), 'objects');
	foreach($posttypes as $posttype) {
		// this is to make sure the meta box is not added to certain post types
		$exclude = array(
			'attachment',
			'menu',
			'modals'
		);
		if( !in_array( $posttype->name, $exclude ) ) {
			add_meta_box('sfsb-meta-box', __('Full Screen Background Image',' simple-full-screen-background-image'), 'sfsb_show_meta_box', $posttype->name, 'side', 'high');
		}
	}
}
add_action('add_meta_boxes', 'sfsb_add_meta_box');

function sfsb_show_meta_box($post) {
	$url	= 'https://fullscreenbackgroundimages.com/downloads/full-screen-background-images-pro/?utm_source=simple-fsbgimg-editor-metabox&utm_medium=upgrade-to-pro&utm_campaign=admin';
	$link	= sprintf(
		__( 'Want a different background image on this %s? Check out Full Screen Background Images Pro. ', 'simple-full-screen-background-image' ),
		$post->post_type
	);

	echo '<div>' . $link . '</div>';
	echo '<div style="margin-top:10px;"><a target="_blank" class="button button-primary" href="' . $url . '">' . __( 'Get Full Screen Background Images Pro!', 'simple-full-screen-background-image' ) . '</a></div>';
}