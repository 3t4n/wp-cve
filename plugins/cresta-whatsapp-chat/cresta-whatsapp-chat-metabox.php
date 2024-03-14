<?php
/**
 * Cresta WhatsApp Chat Metabox
 */
/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cresta_whatsapp_chat_add_meta_box() {
	$cwc_options = get_option( 'crestawhatsappchat_settings' );
	$whatsapp_show_floating_box = $cwc_options['cresta_whatsapp_chat_show_floating_box'];
	if ($whatsapp_show_floating_box == 1) {
		$thePostType = $cwc_options['cresta_whatsapp_chat_selected_page'];
		$screens = explode(",",$thePostType);
		foreach ( $screens as $screen ) {
			add_meta_box(
				'cresta_whatsapp_chat_sectionid',
				esc_html__( 'Cresta Help Chat', 'cresta-whatsapp-chat' ),
				'cresta_whatsapp_chat_metabox_callback',
				$screen,
				'side',
				'low'
			);
		}
	}
}
add_action( 'add_meta_boxes', 'cresta_whatsapp_chat_add_meta_box' );

function cresta_whatsapp_chat_metabox_callback( $post ) {
	wp_nonce_field( 'cresta_whatsapp_chat_meta_box', 'cresta_whatsapp_chat_nonce' );
	$crestaValue = get_post_meta( $post->ID, '_get_cresta_whatsapp_chat_plugin', true );
	?>
	<label for="cresta_whatsapp_chat_new_field">
        <input type="checkbox" name="cresta_whatsapp_chat_new_field" id="cresta_whatsapp_chat_new_field" value="1" <?php checked( $crestaValue, '1' ); ?> /><?php esc_html_e( 'Hide Cresta Help Chat in this page?', 'cresta-whatsapp-chat' )?>
    </label>
	<?php
}

function cresta_whatsapp_chat_save_meta_box_data( $post_id ) {
	if ( ! isset( $_POST['cresta_whatsapp_chat_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['cresta_whatsapp_chat_nonce'], 'cresta_whatsapp_chat_meta_box' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if ( isset( $_POST['cresta_whatsapp_chat_new_field'] ) ) {
		update_post_meta( $post_id, '_get_cresta_whatsapp_chat_plugin', sanitize_text_field(wp_unslash($_POST['cresta_whatsapp_chat_new_field'])) );
	} else {
		delete_post_meta( $post_id, '_get_cresta_whatsapp_chat_plugin' );
	}
	
}
add_action( 'save_post', 'cresta_whatsapp_chat_save_meta_box_data' );