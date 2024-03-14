<?php

defined('ABSPATH') || exit;

if( ! class_exists('Better_image_sizes_focal_point') ){
	class Better_image_sizes_focal_point{
		function __construct(){
			add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields_to_edit' ), 10, 2 );
			add_action( 'edit_attachment', array( $this, 'edit_attachment' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		function attachment_fields_to_edit( $form_fields, $post ){
			if( in_array( get_post_mime_type( $post->ID ), BIS_ALLOWED_MIME_TYPES ) ){
				$focal_point = sanitize_focal_point( get_post_meta( $post->ID, 'focal_point', true ) );
				ob_start(); ?>
				<input name="attachments[<?php echo $post->ID ?>][focal_point]" type="hidden" value="<?php echo esc_attr( implode( ';', $focal_point ) ) ?>" class="focal-point-input">
				<div style="display:flex;align-items:center;gap:40px">
					<div class="focal-point-values">
						<b><?php esc_html_e( 'top', 'better-image-sizes' ) ?></b>: <span class="focal-point-top" data-value="<?php echo floatval( $focal_point[1] ) ?>"><?php echo round( $focal_point[1] * 100 ) ?></span>%
						<br>
						<b><?php esc_html_e( 'left', 'better-image-sizes' ) ?></b>: <span class="focal-point-left" data-value="<?php echo floatval( $focal_point[0] ) ?>"><?php echo round( $focal_point[0] * 100 ) ?></span>%
					</div>
					<button type="button" class="button button-small pick-focal-point">
						<?php esc_html_e( 'Pick focal point', 'better-image-sizes' ) ?>
					</button>
					<button type="button" class="button button-small save-focal-point" style="display:none">
						<?php esc_html_e( 'Save focal point', 'better-image-sizes' ) ?>
					</button>
				</div>
				<div class="focal-point-area"><?php
					$img = wp_get_attachment_image_src( $post->ID, 'full' );
					echo '<img src="' . esc_url( $img[0] ) . '" width="' . esc_attr( $img[1] ) . '" height="' . esc_attr( $img[2] ) . '" alt="">'; ?>

					<svg class="focal-point-handle" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"><path fill="#fff" d="M15 1C7.3 1 1 7.3 1 15s6.3 14 14 14 14-6.3 14-14S22.7 1 15 1zm0 22c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8z"/><path fill="#007cba" d="M15 3C8.4 3 3 8.4 3 15s5.4 12 12 12 12-5.4 12-12S21.6 3 15 3zm0 22C9.5 25 5 20.5 5 15S9.5 5 15 5s10 4.5 10 10-4.5 10-10 10z"/></svg>
				</div>
				<div class="focal-point-previews"><?php
					for( $i = 0; $i < 4; $i++ ){
						echo '<div><img src="' . esc_url( $img[0] ) . '" alt=""></div>';
					} ?>
				</div><?php
				$html = ob_get_clean();
				
				$form_fields['focal_point'] = array(
					'value' => implode( ';', $focal_point ),
					'label' => __( 'Focal point', 'better-image-sizes' ),
					'input' => 'html',
					'html' => $html
				);
			}

			return $form_fields;
		}

		function edit_attachment( $attachment_id ){
			if( isset( $_REQUEST['attachments'][ $attachment_id ]['focal_point'] ) ){
				$focal_point = sanitize_focal_point( $_REQUEST['attachments'][ $attachment_id ]['focal_point'] );
				update_post_meta( $attachment_id, 'focal_point', implode( ';', $focal_point ) );
			}
		}
		
		function admin_enqueue_scripts(){
			wp_enqueue_style( 'better-image-sizes-focal-point', plugin_dir_url( __FILE__ ) . '/style.css' );
			wp_enqueue_script( 'better-image-sizes-focal-point', plugin_dir_url( __FILE__ ) . '/script.js' );
		}
	}

	new Better_image_sizes_focal_point();
}

if( ! function_exists('sanitize_focal_point') ){
	function sanitize_focal_point( $focal_point ){
		if( ! is_array( $focal_point ) ){
			$focal_point = explode( ';', $focal_point );
		}
		if( count( $focal_point ) >= 2 ){
			$focal_point = array( floatval( $focal_point[0] ), floatval( $focal_point[1] ) );
		}else{
			$focal_point = array( 0.5, 0.5 );
		}
		return $focal_point;
	}
}