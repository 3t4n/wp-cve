<?php
	add_action( 'wp_footer', 'wpc_action_update_user_option_js' );

	function wpc_action_update_user_option_js() { ?>
		<?php $ajax_nonce = wp_create_nonce( "wpc-user-meta-ajax" ); ?>
		<script type="text/javascript" >

		jQuery(document).ready(function($) {

			jQuery(document).on('click', '.wpc-ajax-user-meta-option', function(){

				var data = {
					'security'      : "<?php echo esc_js( $ajax_nonce ); ?>",
					'action'        : 'wpc_update_user_meta',
					'user_id'       : $(this).data('user-id'),
					'meta_key'      : $(this).data('key'),
					'meta_value'    : $(this).prop('checked') === true ? 'true' : 'false',
				};

				wpcShowAjaxIcon();

				jQuery.post(ajaxurl, data, function(response) {
					wpcHideAjaxIcon();
				});
			});

		});
		</script> <?php
	}

	add_action( 'wp_ajax_wpc_update_user_meta', 'wpc_update_user_meta_option' );
	function wpc_update_user_meta_option(){
		check_ajax_referer( 'wpc-user-meta-ajax', 'security' );

		$user_id    = isset( $_POST['user_id'] ) ? absint( $_POST['user_id'] ) : 0;
		$meta_key   = isset( $_POST['meta_key'] ) ? sanitize_key( $_POST['meta_key'] ) : '';
		$meta_value = isset( $_POST['meta_value'] ) ? sanitize_text_field( $_POST['meta_value'] ) : '';

		if ( $user_id && $meta_key !== '' ) {
			update_user_meta( $user_id, $meta_key, $meta_value );
		}

	    wp_die(); // required
	}
?>
