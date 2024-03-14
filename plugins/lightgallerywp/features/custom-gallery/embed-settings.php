<?php
namespace LightGallery;

/**
 * This function returns the Custom gallery embed settings for use in admin pages.
 *
 * @param array $boolean_options An array representing boolean choices for controls.
 *
 * @return array An array compatible with the controls array used in LightGallery\SmartlogixCPTWrapper and LightGallery\SmartlogixSettingsWrapper Classes.
 */
function lightgallerywp_get_custom_gallery_embed_settings( $boolean_options ) {
	return [
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Embed',
			'type'    => 'html',
			'label'   => 'Shortcode',
			'id'      => '',
			'data'    => '<p id="embed_shortcode_fail">Please publish the gallery to receive your embed shortcode.</p>
						 <code id="embed_shortcode_success" style="display: none;">[lightgallery id=""]</code>
						 <p id="embed_shortcode_success_instruction">You can use this "shortcode" in your posts or pages.</p>',
		],
	];
}
