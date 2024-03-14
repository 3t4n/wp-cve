<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

if(!function_exists('wpApeGalleryBannerDialog')){
	function wpApeGalleryBannerDialog(){
		wp_enqueue_script( 	WPAPE_GALLERY_ASSET.'banner-js', 	WPAPE_GALLERY_URL.'assets/js/admin/gallery-banner.js', array( 'jquery' ), WPAPE_GALLERY_VERSION, true );
		wp_enqueue_style ( 	WPAPE_GALLERY_ASSET.'banner-css', WPAPE_GALLERY_URL.'assets/css/admin/banner.style.css', array( ), WPAPE_GALLERY_VERSION );
		
		$editNew = apeGalleryHelper::check_new_edit_page('new') || apeGalleryHelper::check_new_edit_page('edit');
		echo '<div class="wpapeTopBlock wpape_getproversion_blank">
			<div class="wpapeTopBig"><span class="dashicons dashicons-palmtree"></span>'.WPAPE_GALLERY_BUTTON_PREMIUM.'</div>
			<div class="wpapeTopSmall">'.__( 'more wonderful features, absolutely no restrictions for creativity', 'gallery-images-ape').' </div>
		</div>';
		if( defined('WPAPE_GALLERY_OFFER') && WPAPE_GALLERY_OFFER ){
			if( WPAPE_GALLERY_OFFER==1 ){
				echo '<div class="wpapeTopBlockFree wpape_getproversionfree_blank">
					<div class="wpapeTopSmall"><span class="dashicons dashicons-admin-post"></span> '.WPAPE_GALLERY_BUTTON_PREMIUM.__( ' for FREE!' , 'gallery-images-ape' ).' </div>
				</div>';	
			}
			if(WPAPE_GALLERY_OFFER==2){
				echo '<div class="wpapeTopBlockFree wpape_getproversiontrans_blank">
					<div class="wpapeTopSmall"><span class="dashicons dashicons-admin-post"></span> '.WPAPE_GALLERY_BUTTON_PREMIUM.__( ' for translation!' , 'gallery-images-ape').' </div>
				</div>';
			}
		}
		/*  */
	}
	if(!WPAPE_GALLERY_PREMIUM) add_action( 'in_admin_header', 'wpApeGalleryBannerDialog' );
}
