<?php

namespace Photonic_Plugin\Options;

use Photonic_Plugin\Core\Utilities;

class Instagram extends Option_Tab {
	private static $instance;

	private function __construct() {
		$this->options = [
			[
				'name'     => 'Instagram settings',
				'desc'     => 'Control settings for Instagram',
				'category' => 'instagram-settings',
				'type'     => 'section',
			],

			[
				'name'     => "Instagram Update",
				'desc'     => "With effect from September 2022, Meta has blocked its API for individual developers, and only allows registered businesses. 
								As Photonic is developed by an individual, unfortunately Instagram can no longer be supported. Please switch to a business-supported plugin if you wish to use Instagram as a source for your galleries.",
				'grouping' => 'instagram-settings',
				'type'     => 'blurb',
			],

/*			[
				'name'     => 'Instagram Access Token',
				'desc'     => "Enter your Instagram Access Token. You can get this from <em>Photonic &rarr; Authentication</em> by clicking on <em>Login and get Access Token</em>",
				'id'       => 'instagram_access_token',
				'grouping' => 'instagram-settings',
				'type'     => 'text'
			],

			[
				'name'     => 'Media to show',
				'desc'     => 'You can choose to include photos as well as videos in your output. This can be overridden by the <code>media</code> parameter in the shortcode:',
				'id'       => 'instagram_media',
				'grouping' => 'instagram-settings',
				'type'     => 'select',
				'options'  => Utilities::media_options()
			],

			[
				'name'     => 'Disable lightbox linking',
				'desc'     => 'Check this to disable linking the photo title in the lightbox to the original photo page.',
				'id'       => 'instagram_disable_title_link',
				'grouping' => 'instagram-settings',
				'type'     => 'checkbox'
			],

			[
				'name'     => 'Caption positioning for carousels / posts',
				'desc'     => "Instagram carousels (posts) don't have a caption for each individual photo, rather the caption is for the post itself. Where do want the caption to show while displaying the carousel? You can override this for each shortcode individually.",
				'id'       => 'instagram_carousel_caption_position',
				'grouping' => 'instagram-settings',
				'type'     => 'select',
				'options'  => [
					'none'  => 'Do not show the caption',
					'above' => 'Show above the photos',
					'below' => 'Show below the photos',
				]
			],

			[
				'name'     => 'Title Display',
				'desc'     => 'How do you want the title of the photo thumbnail?',
				'id'       => 'instagram_photo_title_display',
				'grouping' => 'instagram-settings',
				'type'     => 'radio',
				'options'  => $this->title_styles()
			],*/
		];
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new Instagram();
		}
		return self::$instance;
	}
}
