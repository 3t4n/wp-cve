<?php
/**
 * wf_mlightbox_gallery_default_type_set_link sets the detault value for new galleries to link "to media file" instead the "attachment page"
 */
function wf_mlightbox_gallery_default_type_set_link( $settings ) {
    $settings['galleryDefaults']['link'] = 'file';
    return $settings;
}
//check if we should set the detault value for new galleries to "link to media file"
$galleryOptions = get_option( 'wf-magnific-lightbox-gallery' );
if (isset($galleryOptions['presetMediaFilelink']) && $galleryOptions['presetMediaFilelink'])
	add_filter( 'media_view_settings', 'wf_mlightbox_gallery_default_type_set_link');

/**
 * wf_mlightbox_gallery_shortcode forces all galleries to  link "to media file" instead the "attachment page" even if defined otherwise.
 */
function wf_mlightbox_gallery_shortcode( $atts )
{
    $atts['link'] = 'file';
    return gallery_shortcode( $atts );
}

//check if we should force the link to media file instead the attachment page
$galleryOptions = get_option( 'wf-magnific-lightbox-gallery' );
if (isset($galleryOptions['forceMediaFilelink']) && $galleryOptions['forceMediaFilelink'])
  add_shortcode( 'gallery', 'wf_mlightbox_gallery_shortcode' );

/**
 * Filter to add data-description attribute to all images in galleries to display as lightbox Description
 */
function wf_mlightbox_add_attachment_attributes($attr, $attachment) {
	//get available image informations:
  $attachmentInfos = wf_mlightbox_get_attachment_filtered_info($attachment->ID);
	$attr['data-copyright'] = $attachmentInfos['copyright'];
	$attr['data-headline'] = $attachmentInfos['headline'];
	$attr['data-description'] = $attachmentInfos['description'];

  return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'wf_mlightbox_add_attachment_attributes',10, 2);

/**
 * Filter to add data-description attribute to all inline images added to the editor to display as lightbox Description
 */
function wf_mlightbox_add_editor_image_attributes($html, $id) {
	//get available image informations:
  $attachmentInfos = wf_mlightbox_get_attachment_filtered_info($id);
	$attr['data-headline'] = !empty($attachmentInfos['headline']) ? ' data-headline="' . $attachmentInfos['headline'] . '"' : "";
	$attr['data-description'] = !empty($attachmentInfos['description']) ? ' data-description="' . $attachmentInfos['description'] . '"' : "";
	$attr['data-copyright'] = !empty($attachmentInfos['copyright']) ? ' data-copyright="' . $attachmentInfos['copyright'] . '"' : "";
	$html = str_replace('<img','<img ' . $attr['data-headline'] . $attr['data-description'] . $attr['data-copyright'] ,$html);

	return $html;
}
add_filter( 'image_send_to_editor', 'wf_mlightbox_add_editor_image_attributes',10, 2);

if(!function_exists('wf_mlightbox_add_copyright_field_to_media_uploader')) :

	/**
	 * Adding a "Copyright" field to the media uploader $form_fields array
	 *
	 * @param array $form_fields
	 * @param object $post
	 *
	 * @return array
	 */
	function wf_mlightbox_add_copyright_field_to_media_uploader( $form_fields, $post ) {
    $attachmentMetadata = wp_get_attachment_metadata($post->ID);
    if (is_array($attachmentMetadata)) {
  		$form_fields['copyright_field'] = array(
  			'label' => __('Copyright'),
  			'value' => $attachmentMetadata['image_meta']['copyright'],
  			'helps' => __('Set a copyright credit for the attachment')
  		);
    }
		return $form_fields;
	}
	//check if we should add the copyright field
	$copyrightOptions = get_option( 'wf-magnific-lightbox-copyright' );
	if (isset($copyrightOptions['showCopyright']) && $copyrightOptions['showCopyright'])
		add_filter( 'attachment_fields_to_edit', 'wf_mlightbox_add_copyright_field_to_media_uploader', null, 2 );

endif;

if(!function_exists('wf_mlightbox_add_copyright_field_to_media_uploader_save')) :

	/**
	 * Save our new "Copyright" field
	 *
	 * @param object $post
	 * @param object $attachment
	 *
	 * @return array
	 */
	function wf_mlightbox_add_copyright_field_to_media_uploader_save( $post, $attachment ) {
		$meta = wp_get_attachment_metadata($post['ID']);
		if ( ! empty( $attachment['copyright_field'] ) )
			$meta['image_meta']['copyright'] = $attachment['copyright_field'];
		else
			$meta['image_meta']['copyright'] = '';

		wp_update_attachment_metadata($post['ID'], $meta);

		return $post;
	}
	//check if we should add the copyright field
	$copyrightOptions = get_option( 'wf-magnific-lightbox-copyright' );
	if (isset($copyrightOptions['showCopyright']) && $copyrightOptions['showCopyright'])
		add_filter( 'attachment_fields_to_save', 'wf_mlightbox_add_copyright_field_to_media_uploader_save', null, 2 );

endif;

/**
* Helpers
*/

if(!function_exists('wf_mlightbox_get_attachment')) :
	/**
	* wf_mlightbox_get_attachment returns additional attachment-infos as alt, caption, description, src ... as an array
	*/
  function wf_mlightbox_get_attachment( $attachment_id ) {

		$attachment = get_post($attachment_id);
    $attachmentMetadata = wp_get_attachment_metadata($attachment_id);

    $attachmentMetadataCopyright = '';

    if(isset($attachmentMetadata['image_meta']['copyright'])) {
      $attachmentMetadataCopyright = $attachmentMetadata['image_meta']['copyright'];
    }

    return array(
      'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
      'caption' => $attachment->post_excerpt,
      'description' => $attachment->post_content,
      'href' => get_permalink( $attachment->ID ),
      'src' => $attachment->guid,
      'title' => $attachment->post_title,
      'copyright_field' => $attachmentMetadataCopyright
    );

	}

endif;

if(!function_exists('wf_mlightbox_get_attachment_filtered_info')) :
	/**
	* wf_mlightbox_get_attachment_filtered_info returns pre filtered attachment-infos: headline, description, copyright. It uses all available attachment infos (caption, alt, title) to generate the returned fields.
	*/
	function wf_mlightbox_get_attachment_filtered_info($attachment_id) {
		//get available image informations:
		$wfml_attachments = wf_mlightbox_get_attachment($attachment_id);

		$attr['headline'] = "";
		$attr['description'] = "";
		$attr['copyright'] = "";

		//check if we should hide the copyright
		$copyrightOptions = wf_mlightbox_get_language_depending_option( 'wf-magnific-lightbox-copyright' );
		if (isset($copyrightOptions['showCopyright']) && $copyrightOptions['showCopyright']  && !empty($wfml_attachments['copyright_field'])) {
			if (isset($copyrightOptions['copyrightPrefix']))
				$attr['copyright'] = $copyrightOptions['copyrightPrefix'] . $wfml_attachments['copyright_field'];
			else
				$attr['copyright'] = $wfml_attachments['copyright_field'];
		}
		//let's go to search for a headline at first
		if(!empty($wfml_attachments['caption'])) {
			//we have a caption, return it as main img headline
			$attr['headline'] = $wfml_attachments['caption'];
		} elseif(!empty($wfml_attachments['alt'])) {
			//let's take the alt-tag instead
			$attr['headline'] = $wfml_attachments['alt'];
		} elseif(!empty($wfml_attachments['title'])) {
			//let's take the title-tag instead
			$attr['headline'] = $wfml_attachments['title'];
		}

		//let's set a description if any
		if(!empty($wfml_attachments['description']))
			$attr['description'] = $wfml_attachments['description'];

		return $attr;
	}

endif;

if(!function_exists('wf_mlightbox_get_language_depending_option')) :
/**
* wf_mlightbox_get_language_depending_option returns get_option values filtered by wf_get_language. Returns the language independent options too.
*/
	function wf_mlightbox_get_language_depending_option($option_name) {

		$option = get_option($option_name);

		if (function_exists('wf_get_language')) {
			$lang = wf_get_language();
			if(isset($option[$lang])) {
				$langOption = $option[$lang];
				unset($option[$lang]);
				return array_merge($option, $langOption);
			}
			else {
				return $option;
			}
		}
		else {
			return $option;
		}
	}

endif;
