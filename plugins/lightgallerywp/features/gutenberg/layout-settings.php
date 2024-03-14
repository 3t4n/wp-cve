<?php
namespace LightGallery;

/**
 * This function returns the Gutenberg gallery layout settings for use in admin pages.
 *
 * @param array $boolean_options An array representing boolean choices for controls.
 *
 * @return array An array compatible with the controls array used in LightGallery\SmartlogixCPTWrapper and LightGallery\SmartlogixSettingsWrapper Classes.
 */
function lightgallerywp_get_gutenberg_layout_settings( $boolean_options ) {
	return [
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Layout',
			'type'    => 'select',
			'label'   => 'Gallery Layout',
			'id'      => 'layout_ignore',
			'data'    => [
				[
					'text'  => 'Default',
					'value' => 'default',
				],
				[
					'text'  => 'Justified Gallery',
					'value' => 'justified',
				],

			],
		],
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Layout',
			'type'    => 'number',
			'label'   => 'Justified gallery row height in pixels',
			'id'      => 'justified_gallery_row_height_ignore',
			'style'   => 'layout_option justified_layout_option',
			'default' => '220',
		],
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Layout',
			'type'    => 'text',
			'label'   => 'Justified gallery maximum row height in pixels',
			'id'      => 'justified_gallery_max_row_height_ignore',
			'style'   => 'layout_option justified_layout_option',
			'default' => 'false',
		],
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Layout',
			'type'    => 'number',
			'label'   => 'Justified gallery limit number of rows to show',
			'id'      => 'justified_gallery_max_row_count_ignore',
			'style'   => 'layout_option justified_layout_option',
			'default' => '0',
		],
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Layout',
			'type'    => 'text',
			'label'   => 'Justified gallery last row style',
			'info'    => "Decide to justify the last row (using 'justify') or not (using 'nojustify'), or to hide the row if it can't be justified (using 'hide'). By default, using 'nojustify', the last row images are aligned to the left, but they can be also aligned to the center (using 'center') or to the right (using 'right').",
			'id'      => 'justified_gallery_last_row_ignore',
			'style'   => 'layout_option justified_layout_option',
			'default' => 'nojustify',
		],
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Layout',
			'type'    => 'number',
			'label'   => 'Thumbnail margin',
			'info'    => 'Decide the margins between the thumbnail images',
			'id'      => 'justified_gallery_margin_ignore',
			'style'   => 'layout_option justified_layout_option',
			'default' => '1',
		],
		[
			'metabox' => 'light_gallery_settings',
			'section' => 'Layout',
			'type'    => 'text',
			'label'   => 'Thumbnail border size',
			'info'    => 'Decide the border size of the gallery. With a negative value the border will be the same as the margins.',
			'id'      => 'justified_gallery_border_ignore',
			'style'   => 'layout_option justified_layout_option',
			'default' => '-1',
		],
	];
}
