<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

class wpAPEGalleryModule_Media extends wpApeGallery_Module{

	function getModuleFileName(){ return __FILE__ ; }

	function load(){}

	function hooks(){
		//add_action('admin_head', array( $this, 'hide_attachment_fields') );

		add_action( 'admin_head', array( $this, 'remove_gallery_setting_div') );

		add_filter( 'attachment_fields_to_edit', array( $this, 'attachment_fields'), 1, 2 );
		
		if( !WPAPE_GALLERY_PREMIUM ){
			add_action('admin_head', array( $this, 'setup_attachment_fields') );
		}

		add_filter( 'attachment_fields_to_save', array( $this, 'attachment_fields_save'), 10, 2 );
	}

	
	function setup_attachment_fields() {
		$prefix = ".compat-attachment-fields tr.compat-field-";
		echo "<style>"
			.$prefix.WPAPE_GALLERY_PREFIX."type_link,"
			.$prefix.WPAPE_GALLERY_PREFIX."link{  
				z-index: 1000; 
				opacity: 0.4; 
				pointer-events: none;
		}</style>";
	}

	function attachment_fields_save( $post, $attachment ) {
		
		if( isset( $attachment[WPAPE_GALLERY_PREFIX.'link'] ) && WPAPE_GALLERY_PREMIUM )
			update_post_meta( $post['ID'], WPAPE_GALLERY_PREFIX.'link', 		esc_url( $attachment[WPAPE_GALLERY_PREFIX.'link'] ) );
		
		if( isset( $attachment[WPAPE_GALLERY_PREFIX.'type_link'] ) && WPAPE_GALLERY_PREMIUM )
			update_post_meta( $post['ID'], WPAPE_GALLERY_PREFIX.'type_link',  	$attachment[WPAPE_GALLERY_PREFIX.'type_link'] );
		
		return $post;
	}



	function attachment_fields( $form_fields, $post ) {

		$form_fields[WPAPE_GALLERY_PREFIX.'line'] = array(
			'label' => '',
			'input' => 'html',
			'html' 	=> '<div class="yo_gallery_media_library_header">'.__('2J Gallery', 'gallery-images-ape').'<br/> </div>'
			.(!WPAPE_GALLERY_PREMIUM ? '<a class="button-primary twoj-gallery-option-premium" target="_blank" href="'.APE_GALLERY_PREMIUM_LINK.'">'.__('Add Videos Add-on', 'gallery-images-ape').'</a>' : '' )
			.'<br /> <br />'
			.(!WPAPE_GALLERY_PREMIUM ? '<a class="button-primary twoj-gallery-option-premium" target="_blank" href="'.APE_GALLERY_PREMIUM_LINK.'">'.__('Add Image Link Add-on', 'gallery-images-ape').'</a>' : '' )
		);


			
		$form_fields[WPAPE_GALLERY_PREFIX.'link'] = array(
			'label' => __('Video \ Link', 'gallery-images-ape'),
			'input' => 'text',
			'value' => get_post_meta( $post->ID, WPAPE_GALLERY_PREFIX.'link', true ),
		);

		$value = get_post_meta( $post->ID, WPAPE_GALLERY_PREFIX.'type_link', true );
		if ( empty( $value ) )  $value = 'self';

		$selectBox = 
		"<select name='attachments[{$post->ID}][".WPAPE_GALLERY_PREFIX."type_link]' id='attachments[{$post->ID}][".WPAPE_GALLERY_PREFIX."type_link]'>
			<option value='self' "	.($value=='self'	?'selected':'').">".__( 'Self', 'gallery-images-ape' )."</option>
			<option value='blank' "	.($value=='blank' 	?'selected':'').">".__( 'Blank' , 'gallery-images-ape')."</option>
			<option value='video' "	.($value=='video' 	?'selected':'').">".__( 'Video', 'gallery-images-ape' )."</option>
		</select>";

		$form_fields[WPAPE_GALLERY_PREFIX.'type_link'] = array(
			'label' 	=> __('Link Type', 'gallery-images-ape'),
			'input' 	=> 'html',
			'default' 	=> 'blank',
			'value' 	=> $value,
			'html' 		=> $selectBox 
		);

		return $form_fields;
	}

	function hide_attachment_fields() {
		$prefix = ".compat-attachment-fields tr.compat-field-";
		echo "<style>"
			.$prefix.WPAPE_GALLERY_PREFIX."line,"	
			.$prefix.WPAPE_GALLERY_PREFIX."type_link,"
			.$prefix.WPAPE_GALLERY_PREFIX."link{  
				display:none;
		}</style>";
	}

	function remove_gallery_setting_div() {
        echo '<style type="text/css">
                .apeGalleryFields .media-sidebar .collection-settings.gallery-settings,
                .apeGalleryFields .media-sidebar .thumbnail.thumbnail-image,
                .apeGalleryFields.post-type-yo_gallery tr.compat-field-foogallery_custom_target,
                .apeGalleryFields.post-type-yo_gallery tr.compat-field-foogallery_custom_url,
                .apeGalleryFields.post-type-yo_gallery tr.compat-field-foogallery_custom_class,
                .apeGalleryFields.post-type-yo_gallery tr.compat-field-crop-from-position{
                	display:none;	
                }
                .yo_gallery_media_library_header{
                	font-weight: 600;
					text-transform: uppercase;
					font-size: 12px;
					color: #666;
					margin: 12px 0 8px;
                }
            </style>';
    }
}

$moduleMedia =  new wpAPEGalleryModule_Media();

