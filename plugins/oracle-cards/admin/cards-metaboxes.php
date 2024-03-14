<?php
/**
 *  File required by oracle-cards.php to load the funcitons needed for the plugin options
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} 
function eos_cards_add_meta_box() {
	$screens = array( 'card');
	foreach ( $screens as $screen ) {
		add_meta_box( 'eos_cards_sectionid',__( 'Linked url', 'oracle-cards' ),'eos_cards_meta_box_callback',$screen );
	}
}
add_action( 'add_meta_boxes', 'eos_cards_add_meta_box' );
//Callback for cards metaboxes
function eos_cards_meta_box_callback($post){
	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'eos_cards_meta_box', 'eos_cards_meta_box_nonce' );
	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	?>
	<h4><?php _e( 'Linked url','oracle-cards' ); ?></h4>
	<input type="text" name="eos-linked-url" id="eos-linked-url" style="min-width:400px;max-width:100%;" value="<?php echo esc_attr( get_post_meta( $post->ID, '_eos_linked_url_key', true ) ); ?>" />
	<?php
}
//Save cards metaboxes data
function eos_cards_save_meta_box( $post_id ) {
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if ( ! isset( $_POST['eos_cards_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['eos_cards_meta_box_nonce'], 'eos_cards_meta_box' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( ! current_user_can( 'edit_others_pages', $post_id ) ) {
		return;
	}
	/* OK, it's safe for us to save the data now. */
	// Update the meta field in the database.
	update_post_meta( $post_id, '_eos_linked_url_key',isset( $_POST['eos-linked-url'] ) ? esc_attr( $_POST['eos-linked-url'] ) : '' );
}
add_action( 'save_post', 'eos_cards_save_meta_box' );