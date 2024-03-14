<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;

class apeGalleryMediaManagerClass extends Gallery_Images_Ape{

	function hooks(){
		add_action( 'admin_head', array($this, 'hideFields') );

		add_filter( 'attachment_fields_to_edit', array($this, 'initFields'), 10, 2 );

		add_filter( 'attachment_fields_to_save', array($this, 'saveFields'), 10, 2 );

		//add_action( 'admin_head', array($this, 'remove_gallery_setting_div') );
	}


	function remove_gallery_setting_div() {
        echo '<style type="text/css">
                .post-type-wpape_gallery_type .media-sidebar .collection-settings.gallery-settings
                /* , .post-type-wpape_gallery_type .media-sidebar .thumbnail.thumbnail-image*/{
                	display:none;	
                }
            </style>';
	}


	function hideFields(){
		echo "<style>";
		

		if(!WPAPE_GALLERY_PREMIUM){
				echo "
					.compat-attachment-fields tr.compat-field-wpape_gallery_type_link,
					.compat-attachment-fields tr.compat-field-wpape_gallery_video_link,
					.compat-attachment-fields tr.compat-field-wpape_gallery_effect{
						display:none; z-index: 1000;opacity: 0.4;pointer-events: none;
					}";
			}

		return ;

		/*echo "
		.compat-attachment-fields tr.compat-field-wpape_gallery_line,
		.compat-attachment-fields tr.compat-field-wpape_gallery_line_premium,
		.compat-attachment-fields tr.compat-field-wpape_gallery_link,
		.compat-attachment-fields tr.compat-field-wpape_gallery_type_link,
		.compat-attachment-fields tr.compat-field-wpape_gallery_video_link,
		.compat-attachment-fields tr.compat-field-wpape_gallery_effect,
		.compat-attachment-fields tr.compat-field-wpape_gallery_col{
			display:none;
		}";*/
			
		echo "</style>";
	}

	function selectItem( $listSelect, $selectOption = ''){
		$optionsHtml = '';
		if(count($listSelect)){
			foreach($listSelect as $key => $value){
				$optionsHtml .= '<option value="'.$key.'" '.selected($selectOption, $key, 0).'>'.$value.'</option>';
			}
		}
		return $optionsHtml;
	}

	function initFields( $form_fields, $post ) {

		$form_fields[WPAPE_GALLERY_NAMESPACE.'gallery_line'] = array(
			'label' => '',
			'input' => 'html',
			'html' 	=> '<h4> <a target="_blank" href="edit.php?post_type=wpape_gallery_type">'.__('Gallery Ape', 'gallery-images-ape').'</a></h4>'
		);
		
		$value = get_post_meta( $post->ID, WPAPE_GALLERY_NAMESPACE.'gallery_col', true );
		$selectBox = 
		"<select name='attachments[{$post->ID}][".WPAPE_GALLERY_NAMESPACE."gallery_col]' id='attachments[{$post->ID}][".WPAPE_GALLERY_NAMESPACE."gallery_col]'>
			<option value='1' ".($value=='1' || !$value?'selected':'').">1</option>
			<option value='2' ".($value=='2'?'selected':'').">2</option>
			<option value='3' ".($value=='3'?'selected':'').">3</option>
			<option value='4' ".($value=='4'?'selected':'').">4</option>
			<option value='5' ".($value=='5'?'selected':'').">5</option>
			<option value='6' ".($value=='6'?'selected':'').">6</option>
		</select>";

		$form_fields[WPAPE_GALLERY_NAMESPACE.'gallery_col'] = array(
			'label' => '<strong>'.__('Column', 'gallery-images-ape').'</strong>',
			'input' => 'html',
			'value' => $value,
			'html' => $selectBox 
		);
		
		$form_fields[WPAPE_GALLERY_NAMESPACE.'gallery_link'] = array(
			'label' => '<strong>'.__('Image link', 'gallery-images-ape').'</strong>',
			'input' => 'text',
			'value' => get_post_meta( $post->ID, WPAPE_GALLERY_NAMESPACE.'gallery_link', true ),
		);
		if(!WPAPE_GALLERY_PREMIUM){
			$form_fields[WPAPE_GALLERY_NAMESPACE.'gallery_line_premium'] = array(
				'label' => '',
				'input' => 'html',
				'html' 	=> ( 
					'<h4>'.__('Gallery Ape', 'gallery-images-ape').' '.WPAPE_GALLERY_BUTTON_PREMIUM.'</h4>'.
					'<a class="button button-primary button-small" target="_blank" href="https://wpape.net/open.php?type=gallery&action=premium">'.__('Get').' '.WPAPE_GALLERY_BUTTON_PREMIUM.'</a>'
					)
			);
		}

		$value = get_post_meta( $post->ID, WPAPE_GALLERY_NAMESPACE.'gallery_type_link', true );
		$selectBox = 
		"<select name='attachments[{$post->ID}][".WPAPE_GALLERY_NAMESPACE."gallery_type_link]' id='attachments[{$post->ID}][".WPAPE_GALLERY_NAMESPACE."gallery_type_link]'>
			<option value='1' ".($value=='1'?'selected':'').">".__( 'On', 'gallery-images-ape' )."</option>
			<option value='0' ".($value=='0' || !$value ?'selected':'').">".__( 'Off', 'gallery-images-ape')."</option>
		</select>";

		$form_fields[WPAPE_GALLERY_NAMESPACE.'gallery_type_link'] = array(
			'label' 	=> __('Target blank', 'gallery-images-ape'),
			'input' 	=> 'html',
			'default' 	=> 'link',
			'value' 	=> $value,
			'html' 		=> $selectBox 
		);

		$form_fields[WPAPE_GALLERY_NAMESPACE.'gallery_video_link'] = array(
			'label' => '<strong>'.__('Video link', 'gallery-images-ape').'</strong>',
			'input' => 'text',
			'value' => get_post_meta( $post->ID, WPAPE_GALLERY_NAMESPACE.'gallery_video_link', true ),
		);

		$value = get_post_meta( $post->ID, WPAPE_GALLERY_NAMESPACE.'gallery_effect', true );
		
		$listSelect = array(
			 'push-up' 				=> __( 'push-up' , 'gallery-images-ape' ),
			 'push-down'	 		=> __( 'push-down' , 'gallery-images-ape' ),
			 'push-up-100%' 		=> __( 'push-up-100%' , 'gallery-images-ape' ),
			 'push-down-100%' 		=> __( 'push-down-100%' , 'gallery-images-ape' ),
			 'reveal-top'			=> __( 'reveal-top' , 'gallery-images-ape' ),
			 'reveal-bottom' 		=> __( 'reveal-bottom' , 'gallery-images-ape' ),
			 'reveal-top-100%' 		=> __( 'reveal-top-100%' , 'gallery-images-ape' ),
			 'reveal-bottom-100%' 	=> __( 'reveal-bottom-100%' , 'gallery-images-ape' ),
			 'direction-aware' 		=> __( 'direction-aware' , 'gallery-images-ape' ),
			 'direction-aware-fade' => __( 'direction-aware-fade' , 'gallery-images-ape' ),
			 'direction-right' 		=> __( 'direction-right' , 'gallery-images-ape' ),
			 'direction-left' 		=> __( 'direction-left' , 'gallery-images-ape' ),
			 'direction-top' 		=> __( 'direction-top' , 'gallery-images-ape' ),
			 'direction-bottom' 	=> __( 'direction-bottom' , 'gallery-images-ape' ),
			 'fade' 				=> __( 'fade', 'gallery-images-ape' ),
			 '' 					=> __( 'inherit', 'gallery-images-ape' ),
		);
		$selectBox = "<select name='attachments[{$post->ID}][".WPAPE_GALLERY_NAMESPACE."gallery_effect]' id='attachments[{$post->ID}][".WPAPE_GALLERY_NAMESPACE."gallery_effect]'>";
			$selectBox .= $this->selectItem( $listSelect, $value );
		$selectBox .= '</select>';

		$form_fields[WPAPE_GALLERY_NAMESPACE.'gallery_effect'] = array(
			'label' 	=> '<strong>'.__('Hover effect', 'gallery-images-ape').'</strong>',
			'input' 	=> 'html',
			'default' 	=> 'link',
			'value' 	=> $value,
			'html' 		=> $selectBox 
		);

		return $form_fields;
	}

	function saveFields( $post, $attachment ) {
		if( isset( $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_video_link'] ) )
			update_post_meta( $post['ID'], WPAPE_GALLERY_NAMESPACE.'gallery_video_link', esc_url( $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_video_link'] ) );
		
		if( isset( $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_link'] ) )
			update_post_meta( $post['ID'], WPAPE_GALLERY_NAMESPACE.'gallery_link', esc_url( $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_link'] ) );
		
		if( isset( $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_type_link'] ) )
			update_post_meta( $post['ID'], WPAPE_GALLERY_NAMESPACE.'gallery_type_link',  $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_type_link'] );
		
		if( isset( $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_col'] ) )
			update_post_meta( $post['ID'], WPAPE_GALLERY_NAMESPACE.'gallery_col', $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_col'] );
		
		if( isset( $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_effect'] ) )
			update_post_meta( $post['ID'], WPAPE_GALLERY_NAMESPACE.'gallery_effect', $attachment[WPAPE_GALLERY_NAMESPACE.'gallery_effect'] );
		
		return $post;
	}
}

$apeGalleryMediaManagerClass = new apeGalleryMediaManagerClass();