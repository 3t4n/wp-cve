<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Image Gallery Element
 *
 */
class Powerfolio_Image_Gallery {
	
	//public function __construct() {}

	public static function process_settings_from_gutenberg_block($settings) {

		$list = array();

		// Image
		foreach ($settings['imageIds'] as $imageID) {
			$list[$imageID] = array();
			$list[$imageID]['list_title'] = '';
			$list[$imageID]['list_filter_tag'] = '';

			
			$list[$imageID]['list_image']['id'] = $imageID;
			$list[$imageID]['list_image']['url'] = Powerfolio_Common_Settings::get_image_url($imageID);
			$list[$imageID]['list_image']['size'] = '';
			$list[$imageID]['list_image']['alt'] = '';
			$list[$imageID]['list_image']['source'] = 'library';

			// Let's create the default values
			$list[$imageID]['list_external_link']['url'] = '';
			$list[$imageID]['list_external_link']['is_external'] = 1;
			$list[$imageID]['list_external_link']['nofollow'] = 1;
			$list[$imageID]['list_external_link']['custom_attributes'] = '';

			
			
			$list[$imageID]['linkto'] = '';
			$list[$imageID]['list_description'] = '';			
		}

		// Image Custom URLs
		foreach ($settings['imageCustomUrls'] as $key => $imageCustomUrl) {
			$list[$key]['list_external_link']['url'] = $imageCustomUrl;
			$list[$key]['list_external_link']['is_external'] = 1;
			$list[$key]['list_external_link']['nofollow'] = 1;
			$list[$key]['list_external_link']['custom_attributes'] = '';
		}

		// Tags
		foreach ($settings['imageTags'] as $key => $tags) {
			$list[$key]['list_filter_tag'] = $tags;
		}

		// linkto
		foreach ($settings['linkTo'] as $key => $linkTo) {
			$list[$key]['linkto'] = $linkTo;
		}

		// list description
		foreach ($settings['listContent'] as $key => $listContent) {
			$list[$key]['list_description'] = $listContent;
		}

		// list title
		foreach ($settings['listTitle'] as $key => $listTitle) {
			$list[$key]['list_title'] = $listTitle;
		}


		// Push to the settings array
		$settings['list'] = $list;
	

		return $settings;
	}	

	public static function get_image_gallery_template_for_gutenberg($settings, $css) {  
		$settings = self::process_settings_from_gutenberg_block($settings);				
		return $css.self::get_image_gallery_template($settings);	
	}	

	public static function get_image_gallery_template($settings) {  
		//var_dump($settings);	
		return Powerfolio_Portfolio::get_portfolio_shortcode_output($settings, NULL, NULL, 'image_gallery');
	}	
}