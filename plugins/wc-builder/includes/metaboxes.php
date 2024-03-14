<?php
// template layout metabox
function wpbforwpbakery_tmpl_layout_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function wpbforwpbakery_tmpl_layout_add_meta_box() {
	add_meta_box(
		'wpbforwpbakery_tmpl_layout',
		__( 'Template Layout', 'wpbforwpbakery' ),
		'wpbforwpbakery_tmpl_layout_html',
		'wpbfwpb_template',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'wpbforwpbakery_tmpl_layout_add_meta_box' );

function wpbforwpbakery_tmpl_layout_html( $post) {
	wp_nonce_field( '_wpbforwpbakery_tmpl_layout_nonce', 'wpbforwpbakery_tmpl_layout_nonce' ); ?>

	<p>
		<select name="wpbforwpbakery_tmpl_layout" id="wpbforwpbakery_tmpl_layout">
			<option <?php echo (wpbforwpbakery_tmpl_layout_get_meta( 'wpbforwpbakery_tmpl_layout' ) === 'Default' ) ? 'selected' : '' ?>>Default</option>
			<option <?php echo (wpbforwpbakery_tmpl_layout_get_meta( 'wpbforwpbakery_tmpl_layout' ) === 'Full Width' ) ? 'selected' : '' ?>>Full Width</option>
		</select>
	</p><?php
}

function wpbforwpbakery_tmpl_layout_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['wpbforwpbakery_tmpl_layout_nonce'] ) || ! wp_verify_nonce( $_POST['wpbforwpbakery_tmpl_layout_nonce'], '_wpbforwpbakery_tmpl_layout_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['wpbforwpbakery_tmpl_layout'] ) )
		update_post_meta( $post_id, 'wpbforwpbakery_tmpl_layout', esc_attr( $_POST['wpbforwpbakery_tmpl_layout'] ) );
}
add_action( 'save_post', 'wpbforwpbakery_tmpl_layout_save' );