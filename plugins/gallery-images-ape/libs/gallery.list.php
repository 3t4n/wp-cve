<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ){ exit;  }

class wpApeGalleryListingClass {

	private $dialogPremium = 0;
	private $wizard = 0;
	

	function __construct(){
		$this->dialogPremium 	= isset($_GET['dialogpremium']) && $_GET['dialogpremium'] && !WPAPE_GALLERY_PREMIUM ; 		
		
		$this->wizard 			= !(int) get_option( WPAPE_GALLERY_NAMESPACE.'hideWizard', 0);

		$this->hooks();
	}

	function hooks(){
		add_action( 'init', 												array( $this, 'addCssFiles') );
		
		add_action( 'manage_'.WPAPE_GALLERY_POST.'_posts_custom_column', 	array( $this, 'addCols'), 10, 2 );
		add_filter( 'manage_'.WPAPE_GALLERY_POST.'_posts_columns' , 		array( $this, 'addTitleCols') );

		if( $this->dialogPremium ) 	add_action( 'in_admin_header', array( $this, 'infoBox') );
		//if( $this->wizard ) 		add_action( 'in_admin_header', array( $this, 'showWizard') 	);
	}

	function addCssFiles(){
		wp_enqueue_style ('wpape-gallery-listing-style', WPAPE_GALLERY_URL.'assets/css/admin/list.style.css', array( ), WPAPE_GALLERY_VERSION );
	}

	function addCols( $column, $post_id ) {
		switch ( $column ) {
			case 'wpape_gallery_images' :

				$images = get_post_meta( $post_id, WPAPE_GALLERY_NAMESPACE.'galleryImages', true);

				if( is_array($images) && count($images) ){
					if( isset($images[0]) && trim($images[0])!='' ){
						printf(
							'<a href="%s" title="%s"><span class="dashicons-before dashicons-format-gallery"> %d</span></a>',
							admin_url('post.php?post='.$post_id.'&action=edit&ape_media_show=1'),
							__('Images', 'gallery-images-ape'),
							count($images)
						);
					}
				}		    
			break;

/*			case 'wpape_gallery_static' :
				$staticGallery =get_post_meta( $post_id, WPAPE_GALLERY_NAMESPACE.'static', true);
				if($staticGallery) echo '
					<div class="tooltip">					
						<span class="dashicons-before dashicons-performance red"> </span>
						<span class="twoj_tooltip tooltiptext">
							<span >'.__('Static gallery', 'gallery-images-ape').'</span>
 							<span>'.__('This option make gallery load faster and speed up page.', 'gallery-images-ape').'</span>
						</span>
					</div>
				'; 	    
			break;*/

			case 'wpape_gallery_theme' :
				$themeId = get_post_meta( $post_id, WPAPE_GALLERY_NAMESPACE.'themeId', true);
				$this->getThemeTitle($themeId);	  
			break;

		   	case 'wpape_gallery_shortcode' :
			    echo '<span>[ape-gallery '.$post_id.']</span>'; 
			break;

		   	case 'wpape_gallery_code' :
			    echo '<span>apeGallery( '.$post_id.' ); </span>'; 
			break;
	    }
	}


	function addTitleCols($columns) { 
		return array_merge( $columns, 
			array( 
				/*'wpape_gallery_static' 		=> __('Static ', 	'gallery-images-ape') ,*/
				'wpape_gallery_theme' 		=> __('Theme ', 	'gallery-images-ape') ,
				'wpape_gallery_images' 		=> __('Images', 	'gallery-images-ape') ,
				'wpape_gallery_shortcode' 	=> __('ShortCode', 	'gallery-images-ape'), 
				'wpape_gallery_code' 		=> __('PHP Code', 	'gallery-images-ape'), 
			) 
		); 
	}

	function infoBox(){
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-dialog' );

		if( !WPAPE_GALLERY_PREMIUM ){
			wp_register_script('wpape-gallery-info', 		WPAPE_GALLERY_URL.'assets/js/admin/gallery-info.js', array( 'jquery', 'jquery-ui-dialog' ), WPAPE_GALLERY_VERSION, true ); 
			wp_localize_script( 'wpape-gallery-info', 'ape_gallery_js_text', array(
				'title' 	=> __('Gallery Ape', 'gallery-images-ape').' :: '.WPAPE_GALLERY_BUTTON_PREMIUM,
				'close' 	=> __('Continue with free version'),
				'info' 		=> __('Get').' '.WPAPE_GALLERY_BUTTON_PREMIUM,
				'open' 		=> get_option( 'gallery-images-ape-dialog' , 0) ? 1 : 0 ,
			));
			wp_enqueue_script( 'wpape-gallery-info' ); 
			delete_option( 'gallery-images-ape-dialog' );
		}

		echo '<div id="wpape_showInformation" style="display: none;">'
				.__('Only 3 galleries in FREE version.', 'gallery-images-ape')
				.'<br />'
				.__("We're really appreciate if You update your gallery to Premium version. It's gonna help us to create more wonderful functions :-)", 'gallery-images-ape')
			.'</div>';
	}

	/*function showWizard(){
		wp_enqueue_style( 'wp-jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('wpape-gallery-wizard', WPAPE_GALLERY_URL.'assets/js/admin/wizard.js', array( 'jquery', 'jquery-ui-dialog' ), WPAPE_GALLERY_VERSION, true ); 
		echo '<div id="wpape_showWizard" style="display: none;" '
					.'data-open="1" '
					.'data-title="'.__('Gallery Ape', 'gallery-images-ape').' :: '.__('New functionality', 'gallery-images-ape').'" '
					.'data-close="'.__('Close', 'gallery-images-ape').'" '
					.'data-info="'.__('Options', 'gallery-images-ape').'" '
				.'>'

				.'<h4>'
				.__('New possibility to speed up  WordPress gallery. You can find this option in gallery settings.', 'gallery-images-ape')
				.'</h4>'

				.'<p style="text-align:center; ">'
					.'<img src="'.WPAPE_GALLERY_URL.'assets/static-gallery.png" alt="option: Replace default gallery shortcode" style="width: 550px;" />'
				.'</p>'
			.'</div>';
	}*/

	function showWizard(){
		wp_enqueue_style( 'wp-jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('wpape-gallery-wizard', WPAPE_GALLERY_URL.'assets/js/admin/wizard.js', array( 'jquery', 'jquery-ui-dialog' ), WPAPE_GALLERY_VERSION, true ); 
		echo '<div id="wpape_showWizard" style="display: none;" '
					.'data-open="1" '
					.'data-title="'.__('Gallery Ape', 'gallery-images-ape').' :: '.__('Licence Update Notice', 'gallery-images-ape').'" '
					.'data-close="'.__('Close', 'gallery-images-ape').'" '
					.'data-info="'.__('Options', 'gallery-images-ape').'" '
				.'>'
				.'<h4>'
				.__('Please update license key to the latest version.', 'gallery-images-ape').'<br />'
				.__('With latest version of the license key you get access to the full list of the latest functionality of the plugin.', 'gallery-images-ape')
				.'</h4>'
			.'</div>';
	}

	function getThemeTitle( $themeId ){

		$themeId = $themeId == -1 ? 
			(int) get_option( WPAPE_GALLERY_PREFIX.'default_theme', 0 ) : 
			$themeId
		;
		
		if( !$themeId ) return ;

		$title = get_the_title( $themeId );

		if(!$title) return ;

		if( $title && strlen($title) > 20  ) $title = substr( $title, 0, 20 ).'...';

		printf( 
			'<strong><a target="_blank" href="%s">%s</a></strong> / %s', 
				admin_url('post.php?action=edit&post='.$themeId),
				$title,
				ucfirst( get_post_meta( $themeId, WPAPE_GALLERY_NAMESPACE.'type', true ) )
		);
	}


}

$wpApeGalleryListingClass = new wpApeGalleryListingClass();

if(isset($_GET['wpApeShowWizard'])){
	delete_option( WPAPE_GALLERY_NAMESPACE.'hideWizard' );
}
