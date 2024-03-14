<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class owlc_cls_registerhook {
	public static function owlc_activation() {
		global $wpdb;

		add_option('owl-carousel-responsive', "1.0");

		// Creating default tables
		global $wpdb;

		$charset_collate = '';
		$charset_collate = $wpdb->get_charset_collate();

		$owlc_default_tables = "CREATE TABLE {$wpdb->prefix}owl_carousel_tbl (
									owl_id INT unsigned NOT NULL AUTO_INCREMENT,
									owl_guid VARCHAR(255) NOT NULL,
									owl_type VARCHAR(255) NOT NULL default 'IMG',
									owl_title VARCHAR(255) NOT NULL,
									owl_image VARCHAR(1024) NOT NULL,
									owl_setting VARCHAR(1024) NOT NULL,
									owl_galleryguid VARCHAR(255) NOT NULL,
									owl_order INT unsigned NOT NULL,
									PRIMARY KEY  (owl_id)
									) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $owlc_default_tables );

		$owlc_default_table_names = array( 'owl_carousel_tbl' );

		$owlc_has_errors = false;
		$owlc_missing_tables = array();
		foreach($owlc_default_table_names as $table_name) {
			if(strtoupper($wpdb->get_var("SHOW TABLES like  '". $wpdb->prefix.$table_name . "'")) != strtoupper($wpdb->prefix.$table_name)) {
				$owlc_missing_tables[] = $wpdb->prefix.$table_name;
			}
		}


		if($owlc_missing_tables) {
			$errors[] = __( 'These tables could not be created on installation ' . implode(', ',$owlc_missing_tables), 'owl-carousel-responsive' );
			$owlc_has_errors = true;
		}

		// if error call wp_die()
		if($owlc_has_errors) {
			wp_die( __( $errors[0] , 'owl-carousel-responsive' ) );
			return false;
		} else {
			// Inserting dummy data on first activation
			owlc_cls_default::owlc_gallery_default();
		}

		if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
			set_transient( '_owlc_activation_redirect', 1, 30 );
		}

		return true;
	}

	/**
	 * Sends user to the help & info page on activation.
	 */
	public static function owlc_welcome() {

		if ( ! get_transient( '_owlc_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_owlc_activation_redirect' );

		wp_redirect( admin_url( 'admin.php?page=owlc-gallery&ac=help' ) );
		exit;
	}

	public static function owlc_deactivation() {
		// do not generate any output here
	}

	public static function owlc_admin_option() {
		// do not generate any output here
	}

	public static function owlc_adminmenu() {
		$post = get_post_types();

		add_menu_page( __( 'OWL carousel', 'owl-carousel-responsive' ),
			__( 'OWL carousel', 'owl-carousel-responsive' ), 'manage_options', 'owlc-gallery', array( 'owlc_cls_intermediate', 'owlc_gallery' ), 'dashicons-layout', 51 );

		add_submenu_page('owlc-gallery', __( 'Create Gallery', 'owl-carousel-responsive' ),
			__( 'Create Gallery', 'owl-carousel-responsive' ), 'manage_options', 'owlc-gallery', array( 'owlc_cls_intermediate', 'owlc_gallery' ));
			
		add_submenu_page('owlc-gallery', __( 'Add Images', 'owl-carousel-responsive' ),
			__( 'Add Images', 'owl-carousel-responsive' ), 'manage_options', 'owlc-images', array( 'owlc_cls_intermediate', 'owlc_images' ));
	
	}

	public static function owlc_load_scripts() {

		if( !empty( $_GET['page'] ) ) {
			switch ( $_GET['page'] ) {
				case 'owlc-gallery':
					wp_register_script( 'owlc-gallery', OWLC_URL . 'gallery/gallery.js', '', '', true );
					wp_enqueue_script( 'owlc-gallery' );
					$owlc_select_params = array(
						'owlc_gallery_delete_record'   	=> __( 'Do you want to delete this record?', 'owlc-gallery-select', 'owl-carousel-responsive' ),
						'owlc_gallery_add_title'   		=> __( 'Please enter your gallery name.', 'owlc-gallery-select', 'owl-carousel-responsive' ),
						'owlc_gallery_img_count'   		=> __( 'The number of images you want to see on the screen. only number.', 'owlc-gallery-select', 'owl-carousel-responsive' ),
						'owlc_gallery_autoplaytimeout'  => __( 'Enter autoplay interval timeout. only number.', 'owlc-gallery-select', 'owl-carousel-responsive' ),
					);
					wp_localize_script( 'owlc-gallery', 'owlc_gallery', $owlc_select_params );
					break;
				case 'owlc-images':
					wp_register_script( 'owlc-images', OWLC_URL . 'images/images.js', '', '', true );
					wp_enqueue_script( 'owlc-images' );
					$owlc_select_params = array(
						'owlc_images_delete'  		=> __( 'Do you want to delete this record?', 'owlc-images-select', 'owl-carousel-responsive' ),
						'owlc_images_add_gallery' 	=> __( 'Please select your carousel image gallery.', 'owlc-images-select', 'owl-carousel-responsive' ),
						'owlc_images_add_image' 	=> __( 'Please upload image for this carousel gallery.', 'owlc-images-select', 'owl-carousel-responsive' ),
						'owlc_images_add_title' 	=> __( 'Please enter title for this image.', 'owlc-images-select', 'owl-carousel-responsive' ),
						'owlc_images_add_order' 	=> __( 'Enter enter image order, only number.', 'owlc-images-select', 'owl-carousel-responsive' ),
					);
					wp_localize_script( 'owlc-images', 'owlc_images', $owlc_select_params );
					break;
			}
		}
	}
	
	public static function owlc_add_javascript_files() {
		
		if (!is_admin()) {
			wp_enqueue_style('owl.carousel.min', OWLC_URL.'inc/owl.carousel.min.css');
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'owl.carousel', OWLC_URL.'inc/owl.carousel.js', '', '', true);
		}
		
	}
}


function owlc_shortcode( $atts ) {
	if ( ! is_array( $atts ) ) {
		return '';
	}
	$id = isset($atts['id']) ? $atts['id'] : '0';
	return owlc_cls_widget::owlc_carousel_responsive($id);
}


class owlc_cls_widget {
	public static function owlc_carousel_responsive($id) {
	
		$owlc = "";
		
		$gallery = owlc_cls_dbquery::owlc_gallery_shorcode($id);
		if(count($gallery) > 0) {
			$i = 1;
			
			$owl_guid = $gallery[0]['owl_guid'];
			$owl_title = $gallery[0]['owl_title'];
			$owl_setting = $gallery[0]['owl_setting'];
			
			//wp_enqueue_style('owl.carousel.min', OWLC_URL.'inc/owl.carousel.min.css');
			//wp_enqueue_script('jquery');
			//wp_enqueue_script( 'owl.carousel', OWLC_URL.'inc/owl.carousel.js', '', '', true);
			
			$images = owlc_cls_dbquery::owlc_image_shorcode($owl_guid);
			if(count($gallery) > 0) {
			
				$owlc .= '<div class="owl-carousel owl-theme">';
				foreach ($images as $img) {
					$owlc .= '<div class="item">';
						$owlc .= '<img src="'.$img['owl_image'].'" />';
					$owlc .= '</div>';
				}
				$owlc .= '</div>'; 
				
				$settings = owlc_cls_common::owlc_split_settings($owl_setting);
				$items_1000 = intval($settings["items_1000"]);
				$items_800 = intval($settings["items_800"]);
				$items_600 = intval($settings["items_600"]);
				$items_0 = intval($settings["items_0"]);
				$nav = trim($settings["nav"]);
				$margin = intval($settings["margin"]);
				$autoHeight = trim($settings["autoHeight"]);
				$autoWidth = trim($settings["autoWidth"]);
				$autoplay = trim($settings["autoplay"]);
				$autoplayTimeout = intval($settings["autoplayTimeout"]);
				
				if ( ($nav <> 'true') && ($nav <> 'false') ) {
					$nav = "true";
				}
				
				if ( ($autoHeight <> 'true') && ($autoHeight <> 'false') ) {
					$autoHeight = "true";
				}
				
				if ( ($autoWidth <> 'true') && ($autoWidth <> 'false') ) {
					$autoWidth = "true";
				}
				
				if ( ($autoplay <> 'true') && ($autoplay <> 'false') ) {
					$autoplay = "true";
				}
				
				$owlc .= '<script>'; 
				$owlc .= 'jQuery(document).ready(function() {'; 
					$owlc .= "jQuery('.owl-carousel').owlCarousel({"; 
						$owlc .= 'loop: false,'; 
						$owlc .= 'margin: '.$margin.','; 
						$owlc .= 'responsiveClass: true,'; 
						$owlc .= 'autoplay: '.$autoplay.','; 
						$owlc .= 'autoplayTimeout: '.$autoplayTimeout.','; 
						$owlc .= 'responsive: {'; 
							$owlc .= '0: { items: '.$items_0.', nav: '.$nav.', autoHeight: '.$autoHeight.', autoWidth: '.$autoWidth.' },'; 
							$owlc .= '600: { items: '.$items_600.', nav: '.$nav.', autoHeight: '.$autoHeight.', autoWidth: '.$autoWidth.' },'; 
							$owlc .= '800: { items: '.$items_800.', nav: '.$nav.', autoHeight: '.$autoHeight.', autoWidth: '.$autoWidth.' },'; 
							$owlc .= '1000: { items: '.$items_1000.', nav: '.$nav.', autoHeight: '.$autoHeight.', autoWidth: '.$autoWidth.' }'; 
						$owlc .= '}'; 
					$owlc .= '})'; 
					$owlc .= '})'; 
				$owlc .= '</script>';
			}
 
		} else {
			//No image found
		}
		
		return $owlc;	
	}
}