<?php


// No direct calls to this script
if ( strpos($_SERVER['PHP_SELF'], basename(__FILE__) )) {
	die('No direct calls allowed!');
}


/*
 * Add JavaScript to the admin Footer so we can do Ajax.
 */
add_action( 'admin_footer', 'chessgame_shizzle_fen_ajax' );
function chessgame_shizzle_fen_ajax() {
	if ( ! current_user_can('upload_files') ) {
		return;
	}

	// Set Your Nonce
	$ajax_nonce = wp_create_nonce( 'chessgame_shizzle_fen_ajax' );
	$post_id = get_the_ID();
	$attachment_nonce = wp_create_nonce( "set_post_thumbnail-$post_id" ); ?>
	<script>
	jQuery(document).ready(function($) {

		/*
		 * Query for the CurrentFEN in the iframe.
		 */
		jQuery( '#chessgame_shizzle_generate_featured_image' ).on('click', function() {
			var post_id = jQuery('#post_ID').val();
			var iframe_id = 'cs-iframe-' + post_id;
			var iframe = document.getElementById(iframe_id);

			iframe.contentWindow.postMessage({
				call:  'cs-sendvalue',
				value: 'CurrentFEN'
			}, '*' );
		});

		/*
		 * Do magic with that CurrentFEN.
		 */
		window.addEventListener('message', function(event) {
			if (typeof event.data == 'object' && event.data.call == 'cs-sendvalue') {
				// Do something with event.data.value;
				var fen = event.data.value;
				var post_id = jQuery('#post_ID').val();

				// Set up data to send
				var data = {
					action: 'chessgame_shizzle_fen_ajax',
					security: '<?php echo esc_attr( $ajax_nonce ); ?>',
					fen: fen,
					post_id: post_id
				};

				// Do the actual request
				$.post( ajaxurl, data, function( attachment_id ) {
					if ( ! isNaN( attachment_id ) ) { // We got what we wanted

						// Set the thumbnail in ajax.
						jQuery.post(ajaxurl, {
							action: 'set-post-thumbnail',
							post_id: post_id,
							thumbnail_id: attachment_id,
							_ajax_nonce: '<?php echo esc_attr( $attachment_nonce ); ?>',
							cookie: encodeURIComponent(document.cookie)
						}, function(str){
							var win = window.dialogArguments || opener || parent || top;
							if ( str == '0' ) {
								alert( 'An error occurred.' );
							} else {
								jQuery('#postimagediv .inside').html(str);
								jQuery('#postimagediv .inside #plupload-upload-ui').hide();
							}
						});
						jQuery('#postimagediv .inside h2.uploading_message').remove();

					} else {
						// Error or unexpected answer...
					}
				});
				event.preventDefault();
			}
		}, false);
	});
	</script>
	<?php
}


/*
 * Callback function for handling the Ajax requests that are generated from the JavaScript above in chessgame_shizzle_fen_ajax
 */
add_action( 'wp_ajax_chessgame_shizzle_fen_ajax', 'chessgame_shizzle_fen_ajax_callback' );
function chessgame_shizzle_fen_ajax_callback() {

	if ( ! current_user_can('upload_files') ) {
		echo 'error';
		die();
	}

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['security']) ) {
		$verified = wp_verify_nonce( $_POST['security'], 'chessgame_shizzle_fen_ajax' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		esc_html_e('Nonce check failed. Please go back and try again.', 'chessgame-shizzle');
		die();
	}

	if (isset($_POST['fen'])) {
		$fen = trim( (string) $_POST['fen'] );
	}
	if (isset($_POST['post_id'])) {
		$post_id = (int) $_POST['post_id'];
		$post = get_post( $post_id );
		$description = chessgame_shizzle_truncate_slug( get_the_title( $post ) );
	}

	if ( ! function_exists( 'gd_info' ) ) {
		$response = esc_html__( 'error: GD extension is not loaded for PHP, please contact the site administrator.', 'chessgame-shizzle' );
	} else if ( is_object( $post ) && is_a( $post, 'WP_Post' ) && isset($fen) && strlen($fen) > 0 && isset( $description ) ) {

		$board = new MFEN();
		$board->set_fen( $fen );
		$filename = $board->render( true, $description ); // true returns filename instead of raw image.

		$attachment_id = media_sideload_image( $filename, $post_id, $description, 'id' );

		$board->purge(); // purge cache
		$board->destroy();

		//set_post_thumbnail( $post_id, $attachment_id ); // Do not do this, it will not set the thumbnail from AJAX.

		if ( is_numeric( $attachment_id ) ) {
			$response = (int) $attachment_id;
		} else {
			$response = 'error: unexpected error happened.';
		}

	} else {
		$response = 'error: no FEN code or post ID set.';
	}

	echo $response;
	die(); // this is required to return a proper result

}
