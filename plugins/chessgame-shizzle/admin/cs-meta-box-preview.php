<?php
/*
 *
 */


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add preview postbox to admin editor.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_add_meta_box_preview() {
	add_meta_box('cs_chessgame-posts-box-preview', esc_html__('Preview of the Chessgame', 'chessgame-shizzle' ), 'chessgame_shizzle_display_meta_box_preview', 'cs_chessgame', 'side', 'low');
}
add_action('admin_menu', 'chessgame_shizzle_add_meta_box_preview');


/*
 * Add preview postbox to admin editor.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_display_meta_box_preview() {

	echo chessgame_shizzle_get_iframe( get_the_ID() );

	// Only when GD is supported.
	if ( function_exists('gd_info') ) {
		?>
		<input type="button" name="chessgame_shizzle_generate_featured_image" id="chessgame_shizzle_generate_featured_image" class="button-primary" value="<?php esc_attr_e('Generate Featured Image', 'chessgame-shizzle'); ?>" />
		<?php
	}

}
