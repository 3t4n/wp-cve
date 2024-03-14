<?php
namespace LightGallery;

/**
 * This function returns the Custom gallery advanced settings for use in admin pages.
 *
 * @param array $boolean_options An array representing boolean choices for controls.
 *
 * @return array An array compatible with the controls array used in LightGallery\SmartlogixCPTWrapper and LightGallery\SmartlogixSettingsWrapper Classes.
 */
function lightgallerywp_get_custom_gallery_advanced_settings( $boolean_options ) {
	return [
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Advanced',
			'type'    => 'text',
			'label'   => 'Gallery Container',
			'id'      => 'advanced_container_ignore',
			'info'    => 'Advanced Users : If you want to apply lightGallery to any specific HTML block, instead of using the shortcode, you can just specify any HTML element containing the gallery items.<br/ > For example, if you want to target a specific WordPress default gallery, add a custom classname to the gallery block from the gutenberg editor adwanced tab and specify the className as Gallery container. Then using the Gallery selector pass `a` as selector.',
		],
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Advanced',
			'type'    => 'text',
			'label'   => 'Gallery Selector',
			'id'      => 'advanced_selector_ignore',
			'info'    => 'Advanced Users : Specify the individual gallery elements. </br><a href="https://www.lightgalleryjs.com/docs/settings/#selector">JavaScript API Docs</a></br> <a href="https://www.lightgalleryjs.com/demos/html-markup/">Usage Demo</a>',
		],
	];
}
