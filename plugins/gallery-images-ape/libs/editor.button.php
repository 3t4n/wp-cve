<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class wpApeGalleryEditorButtonClass extends  Gallery_Images_Ape {

	public function hooks(){
		add_action('media_buttons', array( $this, 'addButtonToEditor'), 15);
	}

	function addButtonToEditor(){
		global $pagenow;

		if( isset( $pagenow) &&  $pagenow=='admin-ajax.php' ) return ;

		wp_enqueue_style("wp-jquery-ui-dialog");
		wp_enqueue_script('jquery-ui-dialog');

	  	wp_enqueue_script( WPAPE_GALLERY_ASSET.'editor-button', WPAPE_GALLERY_URL.'assets/js/admin/post.editor.js', array( 'jquery' ), '1.0.0', true );    
	  	
	  	$translation_array = array( 
			'apeGalleryTitle' 	=> __('Gallery Ape', 'gallery-images-ape').' '.__('Shortcode', 'gallery-images-ape'),
			'closeButton'		=> __( 'Close', 'gallery-images-ape', 'gallery-images-ape'),
			'insertButton'		=> __('Add', 'gallery-images-ape'),
		);
		
		wp_localize_script( WPAPE_GALLERY_ASSET.'editor-button', 'wpape_gallery_trans', $translation_array );
		wp_enqueue_script( WPAPE_GALLERY_ASSET.'editor-button' );

	  	echo '<a href="#wpape-gallery" id="insert-wpape-gallery" class="button">
	  			<span class="dashicons dashicons-palmtree" style="margin: 2px 2px 0 0; color:green;"></span>'
	  			.__( 'Add Gallery Ape', 'gallery-images-ape')
	  		.'</a>';
	  		
		$args = array(
		    'child_of'     => 0,
		    'sort_order'   => 'ASC',
		    'sort_column'  => 'post_title',
		    'hierarchical' => 1,
		    'echo'		=> 0,
		    'post_type' => WPAPE_GALLERY_POST
		);
	  	echo '<div id="wpape-gallery" style="display: none;">'
	  			.'<p style="margin-top:0px;">'
	  				.__( 'First setup some gallery in ', 'gallery-images-ape')
	  				.' <a href="edit.php?post_type='.WPAPE_GALLERY_POST.'" target="_blank">'
	  					.__( 'Ape galleries manage', 'gallery-images-ape')
	  				.'</a>'
	  			.'</p>'
	  			.__('After that you can select configured gallery from list here:', 'gallery-images-ape').' '.wp_dropdown_pages( $args )
	  		.'</div>';
	}
}

$wpApeGalleryEditorButtonClass = new wpApeGalleryEditorButtonClass();

