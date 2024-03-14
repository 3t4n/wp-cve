<?php
namespace LightGallery;

/**
 * This function returns the pro upsell settings for use in admin pages.
 *
 * @param array $boolean_options An array representing boolean choices for controls.
 *
 * @return array An array compatible with the controls array used in LightGallery\SmartlogixCPTWrapper and LightGallery\SmartlogixSettingsWrapper Classes.
 */
function lightgallerywp_get_pro_upsell_settings( $boolean_options ) {
	return [
		'zoom_plugin_pro_upsell'       => [
			'metabox' => 'light_gallery_settings',
			'section' => 'Zoom Plugin <span>PRO</span>',
			'type'    => 'html',
			'label'   => '',
			'id'      => '',
			'data'    => lightgallerywp_upgrade_to_pro_banner(),
		],
		'thumbnails_plugin_pro_upsell' => [
			'metabox' => 'light_gallery_settings',
			'section' => 'Thumbnails Plugin <span>PRO</span>',
			'type'    => 'html',
			'label'   => '',
			'id'      => '',
			'data'    => lightgallerywp_upgrade_to_pro_banner(),
		],
		'hash_plugin_pro_upsell'       => [
			'metabox' => 'light_gallery_settings',
			'section' => 'Hash Plugin <span>PRO</span>',
			'type'    => 'html',
			'label'   => '',
			'id'      => '',
			'data'    => lightgallerywp_upgrade_to_pro_banner(),
		],
		'autoplay_plugin_pro_upsell'   => [
			'metabox' => 'light_gallery_settings',
			'section' => 'Autoplay Plugin <span>PRO</span>',
			'type'    => 'html',
			'label'   => '',
			'id'      => '',
			'data'    => lightgallerywp_upgrade_to_pro_banner(),
		],
		'rotate_plugin_pro_upsell'     => [
			'metabox' => 'light_gallery_settings',
			'section' => 'Rotate Plugin <span>PRO</span>',
			'type'    => 'html',
			'label'   => '',
			'id'      => '',
			'data'    => lightgallerywp_upgrade_to_pro_banner(),
		],
		'share_plugin_pro_upsell'      => [
			'metabox' => 'light_gallery_settings',
			'section' => 'Share Plugin <span>PRO</span>',
			'type'    => 'html',
			'label'   => '',
			'id'      => '',
			'data'    => lightgallerywp_upgrade_to_pro_banner(),
		],
		'pager_plugin_pro_upsell'      => [
			'metabox' => 'light_gallery_settings',
			'section' => 'Pager Plugin <span>PRO</span>',
			'type'    => 'html',
			'label'   => '',
			'id'      => '',
			'data'    => lightgallerywp_upgrade_to_pro_banner(),
		],
		'fullscreen_plugin_pro_upsell' => [
			'metabox' => 'light_gallery_settings',
			'section' => 'FullScreen Plugin <span>PRO</span>',
			'type'    => 'html',
			'label'   => '',
			'id'      => '',
			'data'    => lightgallerywp_upgrade_to_pro_banner(),
		],
	];
}

/**
 * Function to display the upsell banner.
 */
function lightgallerywp_upgrade_to_pro_banner() {
	return lightgallerywp_load_file( 'upgrade-banner.php' );
}
