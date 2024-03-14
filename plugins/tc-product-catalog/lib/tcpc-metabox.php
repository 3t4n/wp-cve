<?php
function tcpc_fields_get_meta( $value ) {
	global $post;

	$tcpc_field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $tcpc_field ) ) {
		return is_array( $tcpc_field ) ? stripslashes_deep( $tcpc_field ) : stripslashes( wp_kses_decode_entities( $tcpc_field ) );
	} else {
		return false;
	}
}

function tcpc_fields_add_meta_box() {
	add_meta_box(
		'tcpc_fields-tcpc-fields',
		__( 'Product Price', 'tcpc_fields' ),
		'tcpc_fields_html',
		'tcpc',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'tcpc_fields_add_meta_box' );

function tcpc_fields_html( $post) {
	wp_nonce_field( '_tcpc_fields_nonce', 'tcpc_fields_nonce' ); ?>

	<p>
		<label for="tcpc_fields_currency_icon"><?php _e( 'Currency Icon:', 'tcpc' ); ?></label><br>
		<input type="text" name="tcpc_fields_currency_icon" id="tcpc_fields_currency_icon" value="<?php echo tcpc_fields_get_meta( 'tcpc_fields_currency_icon' ); ?>" size="30" placeholder="$">
	</p>
	<p>
		<label for="tcpc_fields_regular_price"><?php _e( 'Regular price:', 'tcpc' ); ?></label><br>
		<input type="number" name="tcpc_fields_regular_price" id="tcpc_fields_regular_price" value="<?php echo tcpc_fields_get_meta( 'tcpc_fields_regular_price' ); ?>" size="30" placeholder="12">
	</p>
		<p>
		<label for="tcpc_fields_sale_price"><?php _e( 'Sale Price:', 'tcpc_fields' ); ?></label><br>
		<input type="number" name="tcpc_fields_sale_price" id="tcpc_fields_sale_price" value="<?php echo tcpc_fields_get_meta( 'tcpc_fields_sale_price' ); ?>" size="30" placeholder="8">
	</p>
	<?php
}

function tcpc_fields_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['tcpc_fields_nonce'] ) || ! wp_verify_nonce( $_POST['tcpc_fields_nonce'], '_tcpc_fields_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;

	if ( isset( $_POST['tcpc_fields_currency_icon'] ) )
		update_post_meta( $post_id, 'tcpc_fields_currency_icon', esc_attr( $_POST['tcpc_fields_currency_icon'] ) );

	if ( isset( $_POST['tcpc_fields_regular_price'] ) )
		update_post_meta( $post_id, 'tcpc_fields_regular_price', esc_attr( $_POST['tcpc_fields_regular_price'] ) );
		
	if ( isset( $_POST['tcpc_fields_sale_price'] ) )
		update_post_meta( $post_id, 'tcpc_fields_sale_price', esc_attr( $_POST['tcpc_fields_sale_price'] ) );
}
add_action( 'save_post', 'tcpc_fields_save' );

 ?>
