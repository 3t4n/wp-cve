<?php
	
	defined( 'ABSPATH' ) || exit;
	
	if ( ! class_exists( 'Variation_Duplicator_For_Woocommerce_Compatibility' ) ) :
		
		class Variation_Duplicator_For_Woocommerce_Compatibility {
			
			protected static $_instance = null;
			
			protected function __construct() {
				$this->includes();
				$this->hooks();
				$this->init();
				
				do_action( 'variation_duplicator_for_woocommerce_compatibility_loaded', $this );
			}
			
			public static function instance() {
				if ( is_null( self::$_instance ) ) {
					self::$_instance = new self();
				}
				
				return self::$_instance;
			}
			
			protected function includes() {
			}
			
			protected function hooks() {
				$this->wc_additional_variation_images_support();
			}
			
			protected function init() {
			}
			
			// Start
			
			public function wc_additional_variation_images_support() {
				if ( class_exists( 'WC_Additional_Variation_Images' ) ) {
					add_action( 'woo_variation_duplicator_variation_save', array(
						$this,
						'duplicator_variation_save'
					),          10, 2 );
					
					add_action( 'woo_variation_duplicator_image_saved_to', array(
						$this,
						'duplicator_image_saved_to'
					),          10, 2 );
					
					add_action( 'woo_variation_duplicator_image_saved_from', array(
						$this,
						'duplicator_image_saved_from'
					),          10, 2 );
				}
			}
			
			public function duplicator_variation_save( $new_variation_id, $variation_id ) {
				$images = get_post_meta( $variation_id, '_wc_additional_variation_images', true );
				
				if ( $images ) {
					update_post_meta( $new_variation_id, '_wc_additional_variation_images', $images );
				}
			}
			
			public function duplicator_image_saved_to( $selected_variation, $current_variation ) {
				$images = get_post_meta( $current_variation->get_id(), '_wc_additional_variation_images', true );
				
				if ( $images ) {
					update_post_meta( $selected_variation->get_id(), '_wc_additional_variation_images', $images );
				}
			}
			
			public function duplicator_image_saved_from( $current_variation, $selected_variation ) {
				$images = get_post_meta( $selected_variation->get_id(), '_wc_additional_variation_images', true );
				
				if ( $images ) {
					update_post_meta( $current_variation->get_id(), '_wc_additional_variation_images', $images );
				}
			}
		}
	
	endif;