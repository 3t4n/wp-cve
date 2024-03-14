<?php

/**
 *
 */
class Img_Slider_Admin {

	private $tabs;
	private $version = '1.0.0';
	private $current_tab = 'general';

	function __construct() {

		

		// Show general tab
		add_action( 'img_slider_admin_tab_general', array( $this, 'show_general_tab' ) );

		// Add CSS to admin menu
		add_action( 'admin_head', array( $this, 'admin_custom_css' ) );

		add_action( 'wp_ajax_portfolio_save_images', array( $this, 'save_images' ) );
		//add_action( 'wp_ajax_portfolio_save_image', array( $this, 'save_image' ) );



	}

	
	public function show_general_tab() {
		include 'tabs/general.php';
	}

	private function sanitize_image( $image ){

		$new_image = array();

		// This list will not contain id because we save our images based on image id.
		$image_attributes = apply_filters( 'portfolio_gallery_image_attributes', array(
			'id',
			'alt',
			'title',
			'description',
			'halign',
			'valign',
			'link',
			'target',
			'width',
			'height',
		) );

		foreach ( $image_attributes as $attribute ) {
			if ( isset( $image[ $attribute ] ) ) {

				switch ( $attribute ) {
					case 'alt':
						$new_image[ $attribute ] = sanitize_text_field( $image[ $attribute ] );
						break;
					case 'width':
					case 'height':
						$new_image[ $attribute ] = absint( $image[ $attribute ] );
						break;
					case 'title':
					case 'description' :
						$new_image[ $attribute ] = wp_filter_post_kses( $image[ $attribute ] );
						break;
					case 'link' :
						$new_image[ $attribute ] = esc_url_raw( $image[ $attribute ] );
						break;
					case 'target':
						if ( isset( $image[ $attribute ] ) ) {
							$new_image[ $attribute ] = absint( $image[ $attribute ] );
						}else{
							$new_image[ $attribute ] = 0;
						}
						break;
					case 'halign' :
						if ( in_array( $image[ $attribute ], array( 'left', 'right', 'center' ) ) ) {
							$new_image[ $attribute ] = $image[ $attribute ];
						}else{
							$new_image[ $attribute ] = 'center';
						}
						break;
					case 'valign' :
						if ( in_array( $image[ $attribute ], array( 'top', 'bottom', 'middle' ) ) ) {
							$new_image[ $attribute ] = $image[ $attribute ];
						}else{
							$new_image[ $attribute ] = 'middle';
						}
						break;
					default:
						$new_image[ $attribute ] = apply_filters( 'portfolio_image_field_sanitization', sanitize_text_field( $image[ $attribute ] ), $image[ $attribute ], $attribute );
						break;
				}

			}else{
				$new_image[ $attribute ] = '';
			}
		}

		return $new_image;

	}

	public function save_images(){

		$nonce = $_POST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'img-slider-ajax-save' ) ) {
		    wp_send_json( array( 'status' => 'failed' ) );
		}

		if ( ! isset( $_POST['gallery'] ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$gallery_id = absint( $_POST['gallery'] );

		if ( 'img_slider' != get_post_type( $gallery_id ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		if ( ! isset( $_POST['images'] ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$old_images = get_post_meta( $gallery_id, 'slider-images', true );
		$images     = json_decode( stripslashes($_POST['images']), true );
		$new_images = array();

		if ( is_array( $images ) ) {
			foreach ( $images as $image ) {
				$new_images[] = $this->sanitize_image( $image );
			}
		}

		update_post_meta( $gallery_id, 'slider-images', $new_images );
		wp_send_json( array( 'status' => 'succes' ) );

	}

/*	public function save_image(){

		$nonce = $_POST['_wpnonce'];
		if ( ! wp_verify_nonce( $nonce, 'img-slider-ajax-save' ) ) {
		    wp_send_json( array( 'status' => 'failed' ) );
		}

		if ( ! isset( $_POST['gallery'] ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$gallery_id = absint( $_POST['gallery'] );

		if ( 'img_slider' != get_post_type( $gallery_id ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		if ( ! isset( $_POST['image'] ) ) {
			wp_send_json( array( 'status' => 'failed' ) );
		}

		$image      = json_decode( stripslashes($_POST['image']), true );
		$old_images = get_post_meta( $gallery_id, 'slider-images', true );

		foreach ( $old_images as $key => $old_image ) {
			if ( $old_image['id'] == $image['id'] ) {
				$old_images[ $key ] = $this->sanitize_image( $image );
			}
		}

		update_post_meta( $gallery_id, 'slider-images', $old_images );
		wp_send_json( array( 'status' => 'succes' ) );

	}*/

	public function admin_custom_css(){
		?>

		<style type="text/css">
			li#menu-posts-img-slider .wp-submenu li:last-child a {color: #52ad3a;}
		</style>

		<?php
	}

}

new Img_Slider_Admin();