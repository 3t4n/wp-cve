<?php

/*
 * Sets defaults in the Genesis Theme Settings
 */

add_filter( 'genesis_theme_settings_defaults', 'gfi_defaults' );
function gfi_defaults($defaults) {
	$defaults['featimg_default_enable'] = 0;
	$defaults['featimg_url'] = '';
	
	return $defaults;
}

add_action( 'genesis_theme_settings_metaboxes', 'gfi_theme_settings_boxes' );
/**
 * Adds a Genesis Featured Images Metabox to Genesis > Theme Settings
 * 
 */
function gfi_theme_settings_boxes( $pagehook ) {
    add_meta_box( 'genesis-theme-settings-featimg', __( 'Featured Image Settings', 'gfi' ), 'gfi_theme_settings_featimg_box', $pagehook, 'main' );
}

function gfi_theme_settings_featimg_box() { ?>
	<p><input type="checkbox" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[featimg_default_enable]" id="<?php echo GENESIS_SETTINGS_FIELD; ?>[featimg_default_enable]" value="1" <?php checked(1, genesis_get_option('featimg_default_enable')); ?> /> <label for="<?php echo GENESIS_SETTINGS_FIELD; ?>[featimg_default_enable]"><?php _e('Enable the default featured image?', 'genfeatimg'); ?></label></p>
		
	<input type="text" size="100" name="<?php echo GENESIS_SETTINGS_FIELD; ?>[featimg_url]" id="upload_image" value="<?php echo (genesis_get_option('featimg_url')) ? esc_attr( genesis_get_option('featimg_url') ) : ''; ?>" />
	<input type="button" name="upload_image_button" id="upload_image_button" value="<?php echo (genesis_get_option('featimg_url')) ? __("Change", 'genesis') : __("Add New", 'genesis'); ?>" />

	<p><span class="description"><?php printf( __('Use the Media Uploader to upload your default image. Then click Insert into Post to pull the url into the textbox.', 'genesis') ); ?></span></p>
	
<?php
}

/**
 * Adds necessary scripts and styles to load only on Genesis Settings Page
 * 
 */ 
add_action( 'init' , 'genesis_featimg_admin' );
function genesis_featimg_admin() {
	if ( isset( $_GET['page'] ) && $_GET['page'] === 'genesis' ) {
		add_action( 'admin_enqueue_scripts' , 'gfi_admin_scripts' );
	}
}

function gfi_admin_scripts() {	
	wp_register_script(
		'gfi-admin',
		GFI_URL . '/js/admin.js',
		array(
			'jquery',
			'media-upload',
			'thickbox',
		)
	);
	wp_enqueue_script( 'gfi-admin' );
	wp_enqueue_style('thickbox'  );
}
