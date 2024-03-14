<?php

namespace Photonic_Plugin\Options;

use Photonic_Plugin\Core\Utilities;

class SmugMug extends Option_Tab {
	private static $instance;

	private function __construct() {
		$this->options = [
			[
				'name'     => "How To",
				'desc'     => "Control generic settings for the plugin",
				'category' => 'smug-how-to',
				'buttons'  => 'no-buttons',
				'type'     => 'section',
			],

			[
				'name'     => "Creating a gallery",
				'desc'     => "To create a gallery with SmugMug content you can use either a <strong><em>Gutenberg Block</em></strong> 
			or the <em>Add / Edit Photonic Gallery</em> button in the <strong><em>Classic Editor</em></strong>:<br/><br/>
			<img src='" . PHOTONIC_URL . "Options/screenshots/SmugMug-1.png' style='max-width: 600px;' alt='Wizard'/>",
				'grouping' => 'smug-how-to',
				'type'     => 'blurb',
			],

			[
				'name'     => "SmugMug settings",
				'desc'     => "Control settings for SmugMug",
				'category' => 'smug-settings',
				'type'     => 'section',
			],

			[
				'name'     => "SmugMug API Key",
				'desc'     => "<strong>A SmugMug API key is not required unless you want to show private albums using authentication.</strong><br/>
			To make use of the SmugMug authentication you have to use your <a href='https://api.smugmug.com/api/developer/apply'>SmugMug API Key</a>.<br/>
			See <a href='https://aquoid.com/plugins/photonic/smugmug/#api-key'>here</a> for help.",
				'id'       => 'smug_api_key',
				'grouping' => 'smug-settings',
				'type'     => 'text'
			],

			[
				'name'     => "SmugMug API Secret",
				'desc'     => "You have to enter the Secret provided by SmugMug after you have registered your application.",
				'id'       => 'smug_api_secret',
				'grouping' => 'smug-settings',
				'type'     => 'text'
			],

			[
				'name'     => "Access Token (for authentication)",
				'desc'     => "To get your token go to <em>Photonic &rarr; Authentication &rarr; SmugMug</em>, and authenticate. Save the token you get here. <br/>If you have set
			up a token, your users can see protected SmugMug photos without a SmugMug account. See <a href='https://aquoid.com/plugins/photonic/authentication/'>here</a> for more.",
				'id'       => 'smug_access_token',
				'grouping' => 'smug-settings',
				'type'     => 'text'
			],

			[
				'name'     => "Access Token Secret (for authentication)",
				'desc'     => "To get your token secret go to <em>Photonic &rarr; Authentication &rarr; SmugMug</em>, and authenticate. Save the token secret you get here. Your token secret works with the token set in the prvious option. See <a href='https://aquoid.com/plugins/photonic/authentication/'>here</a> for more.",
				'id'       => 'smug_token_secret',
				'grouping' => 'smug-settings',
				'type'     => 'text'
			],

			[
				'name'     => 'Default user',
				'desc'     => 'If no user is specified in the shortcode this one will be used. This is the username from https://<span style="text-decoration: underline">username</span>.smugmug.com/',
				'id'       => 'smug_default_user',
				'grouping' => 'smug-settings',
				'type'     => 'text'
			],

			[
				'name'     => 'Media to show',
				'desc'     => 'You can choose to include photos as well as videos in your output. This can be overridden by the <code>media</code> parameter in the shortcode:',
				'id'       => 'smug_media',
				'grouping' => 'smug-settings',
				'type'     => 'select',
				'options'  => Utilities::media_options()
			],

			[
				'name'     => "Thumbnail size",
				'desc'     => "Pick a standard size provided by SmugMug for your thumbnails:",
				'id'       => 'smug_thumb_size',
				'grouping' => 'smug-settings',
				'type'     => 'select',
				'options'  => ['Tiny' => 'Tiny', 'Thumb' => 'Thumb', 'Small' => 'Small']
			],

			[
				'name'     => "Main image size",
				'desc'     => "When you click on a thumbnail this size will be displayed if you are using a slideshow. If you are not using a slideshow you will be taken to the SmugMug page:",
				'id'       => 'smug_main_size',
				'grouping' => 'smug-settings',
				'type'     => 'select',
				'options'  => [
					'4K'       => '4K (not always available)',
					'5K'       => '5K (not always available)',
					'Medium'   => "Medium",
					'Original' => "Original (not always available)",
					'Large'    => "Large",
					'Largest'  => 'Largest',
					'XLarge'   => "XLarge (not always available)",
					'X2Large'  => "X2Large (not always available)",
					'X3Large'  => "X3Large (not always available)",
					'X4Large'  => "X3Large (not always available)",
					'X5Large'  => "X3Large (not always available)",
				]
			],

			[
				'name'     => "Tile image size",
				'desc'     => "<strong>This is applicable only if you are using the random tiled gallery, masonry or mosaic layouts.</strong> This size will be used as the image for the tiles. Picking a size smaller than the Main image size above will save bandwidth if your users <strong>don't click</strong> on individual images. Conversely, leaving this the same as the Main image size will save bandwidth if your users <strong>do click</strong> on individual images:",
				'id'       => 'smug_tile_size',
				'grouping' => 'smug-settings',
				'type'     => 'select',
				'options'  => [
					'same'     => "Same as Main image size",
					'Small'    => "Small",
					'4K'       => '4K (not always available)',
					'5K'       => '5K (not always available)',
					'Medium'   => "Medium",
					'Original' => "Original (not always available)",
					'Large'    => "Large",
					'Largest'  => 'Largest',
					'XLarge'   => "XLarge (not always available)",
					'X2Large'  => "X2Large (not always available)",
					'X3Large'  => "X3Large (not always available)",
					'X4Large'  => "X3Large (not always available)",
					'X5Large'  => "X3Large (not always available)",
				]
			],

			[
				'name'     => "Video size",
				'desc'     => "When you click on a thumbnail this size will be displayed if you are using a slideshow. If you are not using a slideshow you will be taken to the SmugMug page:",
				'id'       => 'smug_video_size',
				'grouping' => 'smug-settings',
				'type'     => 'select',
				'options'  => [
					'110'     => '110px along longest side',
					'200'     => '200px along longest side',
					'320'     => '320px along longest side',
					'640'     => '640px along longest side',
					'1280'    => '1280px along longest side',
					'1920'    => '1920px along longest side',
					'Largest' => 'Largest',
				]
			],

			[
				'name'     => "Disable lightbox linking",
				'desc'     => "Check this to disable linking the album title and/or thumbnail, or the title in the lightbox to the SmugMug page for the album / photo.",
				'id'       => 'smug_disable_title_link',
				'grouping' => 'smug-settings',
				'type'     => 'checkbox'
			],

			[
				'name'     => "Show \"Buy\" link",
				'desc'     => "Click to show a link to purchase the photo. This shows up in a lightbox, enabled.",
				'id'       => 'smug_show_buy_link',
				'grouping' => 'smug-settings',
				'type'     => 'checkbox'
			],

			[
				'name'     => "Photo titles and captions for the galleries / slideshows",
				'desc'     => "What do you want to show as the photo title in the gallery / slideshow? This is used for the tooltips and title displays.",
				'id'       => 'smug_title_caption',
				'grouping' => 'smug-settings',
				'type'     => 'select',
				'options'  => Utilities::title_caption_options()
			],

			$this->get_layout_engine_options('smug_layout_engine', 'smug-settings'),

			[
				'name'     => "Album Thumbnails (with other Albums)",
				'desc'     => "Control settings for SmugMug Album thumbnails",
				'category' => 'smug-albums',
				'type'     => 'section',
			],

			[
				'name'     => "What is this section?",
				'desc'     => "Options in this section are in effect when you pick the following gallery creation options:<br/><br/>
			<img src='" . PHOTONIC_URL . "Options/screenshots/SmugMug-2.png' style='max-width: 600px;' alt='Albums'/><br/><br/>
			If you are using the shortcode, the settings kick in for <code>[gallery type='smugmug' nick_name='abc']</code> or 
			<code>[gallery type='smugmug' nick_name='abc' view='albums']</code> or <code>[gallery type='smugmug' nick_name='abc' view='tree']</code>. 
			They are used to control the Album's thumbnail display",
				'grouping' => 'smug-albums',
				'type'     => 'blurb',
			],

			[
				'name'     => "Album Title Display",
				'desc'     => "How do you want the title of the Album?",
				'id'       => 'smug_albums_album_title_display',
				'grouping' => 'smug-albums',
				'type'     => 'radio',
				'options'  => $this->title_styles(),
			],

			[
				'name'     => "Hide Photo Count in Title Display",
				'desc'     => "This will hide the number of photos in your Album's title.",
				'id'       => 'smug_hide_albums_album_photos_count_display',
				'grouping' => 'smug-albums',
				'type'     => 'checkbox'
			],

			[
				'name'     => "Hide thumbnails for Password-protected albums",
				'desc'     => "This will hide the thumbnail of password-protected albums.",
				'id'       => 'smug_hide_password_protected_thumbnail',
				'grouping' => 'smug-albums',
				'type'     => 'checkbox'
			],

			[
				'name'     => 'Album sort order',
				'desc'     => 'What should the results be sorted by?',
				'id'       => 'smug_album_sort_order',
				'grouping' => 'smug-albums',
				'type'     => 'select',
				'options'  => [
					'Last Updated (Descending)' => 'Last Updated (Descending)',
					'Last Updated (Ascending)'  => 'Last Updated (Ascending)',
					'Date Added (Descending)'   => 'Date Added (Descending)',
					'Date Added (Ascending)'    => 'Date Added (Ascending)',
				]
			],

			[
				'name'     => "Constrain Albums Per Row",
				'desc'     => "How do you want the control the number of album thumbnails per row? This can be overridden by adding the '<code>columns</code>' parameter to the '<code>gallery</code>' shortcode.",
				'id'       => 'smug_albums_album_per_row_constraint',
				'grouping' => 'smug-albums',
				'type'     => 'select',
				'options'  => [
					'padding' => 'Automatically calculate thumbnails per row',
					'count'   => 'Fix the number of thumbnails per row',
				]
			],

			[
				'name'     => "Fixed number of thumbnails",
				'desc'     => " If you have fixed the number of thumbnails per row above, enter the number of thumbnails",
				'id'       => 'smug_albums_album_constrain_by_count',
				'grouping' => 'smug-albums',
				'type'     => 'select',
				'options'  => $this->selection_range(1, 10)
			],

			[
				'name'     => "Photos (Main Page)",
				'desc'     => "Control settings for SmugMug Photos when displayed in your page",
				'category' => 'smug-photos',
				'type'     => 'section',
			],

			[
				'name'     => "What is this section?",
				'desc'     => "Options in this section are in effect when you pick the following gallery creation options:<br/><br/>
			<img src='" . PHOTONIC_URL . "Options/screenshots/SmugMug-3.png' style='max-width: 600px;' alt='Photos'/><br/><br/>
			If you are using the shortcode, the settings kick in for <code>[gallery type='smugmug' nick_name='abc' view='album' album='pqr']</code>
			or <code>[gallery type='smugmug' nick_name='abc' view='images' album='pqr']</code>. In other words, the photos are printed directly on the page.",
				'grouping' => 'smug-photos',
				'type'     => 'blurb',
			],

			[
				'name'     => "Hide Album Thumbnail",
				'desc'     => "This will hide the thumbnail for your SmugMug Album.",
				'id'       => 'smug_hide_album_thumbnail',
				'grouping' => 'smug-photos',
				'type'     => 'checkbox'
			],

			[
				'name'     => "Hide Album Title",
				'desc'     => "This will hide the title for your SmugMug Album.",
				'id'       => 'smug_hide_album_title',
				'grouping' => 'smug-photos',
				'type'     => 'checkbox'
			],

			[
				'name'     => "Hide Number of Photos",
				'desc'     => "This will hide the number of photos in your SmugMug Album.",
				'id'       => 'smug_hide_album_photo_count',
				'grouping' => 'smug-photos',
				'type'     => 'checkbox'
			],

			[
				'name'     => "Photo Title Display",
				'desc'     => "How do you want the title of the photos?",
				'id'       => 'smug_photo_title_display',
				'grouping' => 'smug-photos',
				'type'     => 'radio',
				'options'  => $this->title_styles(),
			],

			[
				'name'     => "Constrain Photos Per Row",
				'desc'     => "How do you want the control the number of photo thumbnails per row by default? This can be overridden by adding the '<code>columns</code>' parameter to the '<code>gallery</code>' shortcode.",
				'id'       => 'smug_photos_per_row_constraint',
				'grouping' => 'smug-photos',
				'type'     => 'select',
				'options'  => [
					'padding' => 'Automatically calculate thumbnails per row',
					'count'   => 'Fix the number of thumbnails per row',
				]
			],

			[
				'name'     => "Fixed number of thumbnails",
				'desc'     => " If you have fixed the number of thumbnails per row above, enter the number of thumbnails",
				'id'       => 'smug_photos_constrain_by_count',
				'grouping' => 'smug-photos',
				'type'     => 'select',
				'options'  => $this->selection_range(1, 10)
			],

			[
				'name'     => "Photos (Popup Panel)",
				'desc'     => "Control settings for SmugMug Photos when displayed in a popup",
				'category' => 'smug-photos-pop',
				'type'     => 'section',
			],

			[
				'name'     => "What is this section?",
				'desc'     => "Options in this section are in effect when you use the shortcode format <code>[gallery type='smugmug' nick_name='abc' view='albums']</code>, and you click on an album thumbnail to open its photos in an overlaid panel.",
				'grouping' => 'smug-photos-pop',
				'type'     => 'blurb',
			],

			[
				'name'     => "Photo Title Display",
				'desc'     => "How do you want the title of the photos?",
				'id'       => 'smug_photo_pop_title_display',
				'grouping' => 'smug-photos-pop',
				'type'     => 'radio',
				'options'  => $this->title_styles(),
			],

			[
				'name'     => "Advanced",
				'desc'     => "Advanced settings for SmugMug",
				'category' => 'smug-advanced',
				'type'     => 'section',
			],

			[
				'name'     => "Tree Nesting Levels",
				'desc'     => "When you click on a thumbnail this size will be displayed if you are using a slideshow. If you are not using a slideshow you will be taken to the SmugMug page:",
				'id'       => 'smug_nesting_levels',
				'grouping' => 'smug-advanced',
				'type'     => 'select',
				'options' => [
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				]
			],

		];
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new SmugMug();
		}
		return self::$instance;
	}
}
