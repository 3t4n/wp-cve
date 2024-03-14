<?php

namespace Photonic_Plugin\Admin\Forms;

use Photonic_Plugin\Core\Utilities;

/**
 * Contains all fields required on the add / edit forms for the gallery.
 */
class Vanilla_Form {
	private $fields;
	private $default_layout;
	private static $instance = null;

	private function __construct() {
		global $photonic_thumbnail_style;
		$this->default_layout = $photonic_thumbnail_style ?? 'square';

		$this->fields = [];
		$this->fields['default'] = $this->get_wp_fields();
		$this->fields['flickr'] = $this->get_flickr_fields();
		$this->fields['google'] = $this->get_google_fields();
		$this->fields['smugmug'] = $this->get_smugmug_fields();
		$this->fields['zenfolio'] = $this->get_zenfolio_fields();
		$this->fields['instagram'] = $this->get_instagram_fields();
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new Vanilla_Form();
		}
		return self::$instance;
	}

	public function get_fields() {
		return $this->fields;
	}

	private function get_wp_fields() {
		global $photonic_wp_title_caption;
		return [
			'name'   => esc_html__('WP Galleries', 'photonic'),
			'fields' => [
				[
					'id'   => 'id',
					'name' => esc_html__('Gallery ID', 'photonic'),
					'type' => 'text',
					'req'  => true,
				],

				[
					'id'   => 'ids',
					'name' => esc_html__('Image IDs', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Comma-separated. You can specify this if there is no Gallery ID specified.', 'photonic'),
				],

				[
					'id'      => 'style',
					'name'    => esc_html__('Display Style', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::layout_options(true),
					'hint'    => esc_html__('The first four options trigger a slideshow, the rest trigger a lightbox.', 'photonic'),
				],

				[
					'id'   => 'count',
					'name' => esc_html__('Number of photos to show', 'photonic'),
					'type' => 'text',
				],

				[
					'id'   => 'more',
					'name' => esc_html__('"More" button text', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button', 'photonic'),
				],

				[
					'id'      => 'caption',
					'name'    => esc_html__('Photo title / caption', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::title_caption_options(),
					'std'     => $photonic_wp_title_caption,
					'hint'    => esc_html__('This will be used as the title for your photos.', 'photonic'),
				],

				[
					'id'   => 'columns',
					'name' => esc_html__('Number of columns', 'photonic'),
					'type' => 'text',
					'std'  => 3,
				],

				[
					'id'   => 'thumb_width',
					'name' => esc_html__('Thumbnail width', 'photonic'),
					'type' => 'text',
					'std'  => 75,
					'hint' => esc_html__('In pixels', 'photonic')
				],

				[
					'id'   => 'thumb_height',
					'name' => esc_html__('Thumbnail height', 'photonic'),
					'type' => 'text',
					'std'  => 75,
					'hint' => esc_html__('In pixels', 'photonic')
				],

				[
					'id'   => 'thumb_size',
					'name' => esc_html__('Thumbnail size', 'photonic'),
					'type' => 'raw',
					'std'  => self::get_image_sizes_selection('thumb_size', false),
					'hint' => esc_html__('Sizes defined by your theme. Image picked here will be resized to the dimensions above.', 'photonic')
				],

				[
					'id'     => 'main_size',
					'name'   => esc_html__('Main image size', 'photonic'),
					'type'   => 'raw',
					'std'    => self::get_image_sizes_selection('main_size', true),
					'hint'   => esc_html__('Sizes defined by your theme. Shown in a slideshow or lightbox. Avoid loading large sizes to reduce page loads.', 'photonic'),
				],

				[
					'id'   => 'tile_size',
					'name' => esc_html__('Tile image size', 'photonic'),
					'type' => 'raw',
					'std'  => self::get_image_sizes_selection('tile_size', true),
					'hint' => esc_html__('Sizes defined by your theme. Shown in mosaic or masonry or random justified grid galleries. Avoid loading large sizes to reduce page loads.', 'photonic'),
				],

				[
					'id'      => 'fx',
					'name'    => esc_html__('Slideshow Effects', 'photonic'),
					'type'    => 'select',
					'options' => [
						'fade'  => esc_html__('Fade', 'photonic'),
						'slide' => esc_html__('Slide', 'photonic'),
					],
					'std'     => 'slide',
					'hint'    => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'      => 'controls',
					'name'    => esc_html__('Slideshow Controls', 'photonic'),
					'type'    => 'select',
					'options' => [
						'hide' => esc_html__('Hide', 'photonic'),
						'show' => esc_html__('Show', 'photonic'),
					],
					'hint'    => esc_html__('Shows Previous and Next buttons on the slideshow.', 'photonic'),
				],

				[
					'id'   => 'timeout',
					'name' => esc_html__('Time between slides in ms', 'photonic'),
					'type' => 'text',
					'std'  => 4000,
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'   => 'speed',
					'name' => esc_html__('Time for each transition in ms', 'photonic'),
					'type' => 'text',
					'std'  => 1000,
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],
			],
		];
	}

	private function get_flickr_fields() {
		global $photonic_flickr_title_caption, $photonic_flickr_thumb_size, $photonic_flickr_main_size, $photonic_flickr_media;
		return [
			'name'    => esc_html__('Flickr', 'photonic'),
			'prelude' => sprintf(
				esc_html__('You have to define your Flickr API Key under %10$s.%1$s Documentation: %3$sOverall%2$s | %4$sPhotos%2$s | %5$sSingle Photos%2$s | %6$sAlbums (Photosets)%2$s| %7$sGalleries%2$s | %8$sCollections%2$s | %9$sAuthentication%2$s', 'photonic'),
				'<br/>',
				'</a>',
				'<a href="https://aquoid.com/plugins/photonic/flickr/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/flickr/flickr-photos/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/flickr/flickr-photo/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/flickr/flickr-photosets/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/flickr/flickr-galleries/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/flickr/flickr-collections/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/flickr/flickr-authentication/" target="_blank">',
				'<strong>Photonic &rarr; Settings &rarr; Flickr &rarr; Flickr Settings</strong>'
			),
			'fields'  => [
				[
					'id'   => 'user_id',
					'name' => esc_html__('User ID', 'photonic'),
					'type' => 'text',
					'req'  => true,
					'hint' => sprintf(esc_html__('Find your user ID from %s.', 'photonic'), '<strong>Photonic &rarr; Helpers</strong>')
				],

				[
					'id'      => 'view',
					'name'    => esc_html__('Display', 'photonic'),
					'type'    => 'select',
					'options' => [
						'photos'      => esc_html__('Photos', 'photonic'),
						'photosets'   => esc_html__('Photosets', 'photonic'),
						'galleries'   => esc_html__('Galleries', 'photonic'),
						'collections' => esc_html__('Collections', 'photonic'),
						'photo'       => esc_html__('Single Photo', 'photonic'),
					],
					'req'     => true,
				],

				[
					'id'   => 'photoset_id',
					'name' => esc_html__('Album ID (Photoset ID)', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a single photoset if "Display" is set to "Photosets"', 'photonic')
				],

				[
					'id'   => 'gallery_id',
					'name' => esc_html__('Gallery ID', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a single gallery if "Display" is set to "Galleries"', 'photonic')
				],

				[
					'id'   => 'collection_id',
					'name' => esc_html__('Collection ID', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show contents of a single collection if "Display" is set to "Collections"', 'photonic')
				],

				[
					'id'   => 'photo_id',
					'name' => esc_html__('Photo ID', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a single photo if "Display" is set to "Single Photo"', 'photonic')
				],

				[
					'id'   => 'filter',
					'name' => esc_html__('Filter', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('If "Display" is "Photosets" or "Galleries" or "Collections" and you provide a comma-separated list of values here, these entities will be included / excluded based on the next option. Useful if you want to display a single thumbnail for a single photoset / gallery, ignored if Photoset, Gallery or Collection ID is provided', 'photonic')
				],

				[
					'id'      => 'filter_type',
					'name'    => esc_html__('Filter Type', 'photonic'),
					'type'    => 'select',
					'options' => [
						''        => '',
						'include' => esc_html__('Include above list in results', 'photonic'),
						'exclude' => esc_html__('Exclude above list from results', 'photonic'),
					],
				],

				[
					'id'      => 'media',
					'name'    => esc_html__('Media to Show', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::media_options(),
					'std'     => $photonic_flickr_media,
				],

				[
					'id'   => 'columns',
					'name' => esc_html__('Number of columns', 'photonic'),
					'type' => 'text',
				],

				[
					'id'   => 'tags',
					'name' => esc_html__('Tags', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Comma-separated list of tags', 'photonic')
				],

				[
					'id'      => 'tag_mode',
					'name'    => esc_html__('Tag mode', 'photonic'),
					'type'    => 'select',
					'options' => [
						'any' => esc_html__('Any tag', 'photonic'),
						'all' => esc_html__('All tags', 'photonic'),
					],
				],

				[
					'id'   => 'text',
					'name' => esc_html__('With text', 'photonic'),
					'type' => 'text',
				],

				[
					'id'      => 'sort',
					'name'    => esc_html__('Sort by', 'photonic'),
					'type'    => 'select',
					'options' => [
						'date-posted-desc'     => esc_html__('Date posted, descending', 'photonic'),
						'date-posted-asc'      => esc_html__('Date posted, ascending', 'photonic'),
						'date-taken-asc'       => esc_html__('Date taken, ascending', 'photonic'),
						'date-taken-desc'      => esc_html__('Date taken, descending', 'photonic'),
						'interestingness-desc' => esc_html__('Interestingness, descending', 'photonic'),
						'interestingness-asc'  => esc_html__('Interestingness, ascending', 'photonic'),
						'relevance'            => esc_html__('Relevance', 'photonic'),
					],
				],

				[
					'id'   => 'group_id',
					'name' => esc_html__('Group ID', 'photonic'),
					'type' => 'text',
				],

				[
					'id'     => 'count',
					'name'   => esc_html__('Number of photos / albums / galleries to show', 'photonic'),
					'type'   => 'text',
					'hint'   => esc_html__('Will show at the most 100 by default, 500 is the maximum', 'photonic'),
				],

				[
					'id'   => 'more',
					'name' => esc_html__('"More" button text', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a "More" button with the specified text if the number of photos / albums / galleries is higher than the above entry. Leave blank to show no button', 'photonic'),
				],

				[
					'id'   => 'photo_count',
					'name' => esc_html__('Number of photos to show in overlaid popup', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable for pagination if %1$s is selected. Leave blank to show maximum allowed photos.', 'photonic'), '<strong>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</strong>'),
				],

				[
					'id'   => 'photo_more',
					'name' => esc_html__('"More" button text in overlaid popup', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable for pagination if %1$s is selected. Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button.', 'photonic'), '<strong>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</strong>'),
				],

				[
					'id'      => 'privacy_filter',
					'name'    => esc_html__('Privacy filter', 'photonic'),
					'type'    => 'select',
					'options' => [
						''  => esc_html__('None', 'photonic'),
						'1' => esc_html__('Public photos', 'photonic'),
						'2' => esc_html__('Private photos visible to friends', 'photonic'),
						'3' => esc_html__('Private photos visible to family', 'photonic'),
						'4' => esc_html__('Private photos visible to friends & family', 'photonic'),
						'5' => esc_html__('Completely private photos', 'photonic'),
					],
					'hint'    => esc_html__('Applicable only if Flickr private photos are turned on', 'photonic'),
				],

				[
					'id'      => 'layout',
					'name'    => esc_html__('Layout', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::layout_options(),
					'hint'    => esc_html__('The first four options trigger a slideshow, the rest trigger a lightbox.', 'photonic'),
					'std'     => $this->default_layout,
				],

				[
					'id'      => 'caption',
					'name'    => esc_html__('Photo title / caption', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::title_caption_options(),
					'std'     => $photonic_flickr_title_caption,
					'hint'    => esc_html__('This will be used as the title for your photos.', 'photonic'),
				],

				[
					'id'      => 'thumb_size',
					'name'    => esc_html__('Thumbnail size', 'photonic'),
					'type'    => 'select',
					'std'     => $photonic_flickr_thumb_size,
					"options" => [
						's' => esc_html__('Small square, 75x75px', 'photonic'),
						'q' => esc_html__('Large square, 150x150px', 'photonic'),
						't' => esc_html__('Thumbnail, 100px on longest side', 'photonic'),
						'm' => esc_html__('Small, 240px on longest side', 'photonic'),
						'n' => esc_html__('Small, 320px on longest side', 'photonic'),
					],
					'hint'    => esc_html__('In pixels, only applicable to square and circular thumbnails', 'photonic')
				],

				[
					'id'      => 'main_size',
					'name'    => esc_html__('Main image size', 'photonic'),
					'type'    => 'select',
					'std'     => $photonic_flickr_main_size,
					'options' => [
						'none' => esc_html__('Medium, 500px on the longest side', 'photonic'),
						'z'    => esc_html__('Medium, 640px on longest side', 'photonic'),
						'c'    => esc_html__('Medium, 800px on longest side', 'photonic'),
						'b'    => esc_html__('Large, 1024px on longest side', 'photonic'),
						'h'    => esc_html__('Large, 1600px on longest side', 'photonic'),
						'k'    => esc_html__('Large, 2048px on longest side', 'photonic'),
						'o'    => esc_html__('Original', 'photonic'),
					],
				],

				[
					'id'      => 'collections_display',
					'name'    => esc_html__('Expand Collections', 'photonic'),
					'type'    => 'select',
					'options' => [
						''         => '',
						'lazy'     => esc_html__('Lazy loading', 'photonic'),
						'expanded' => esc_html__('Expanded upfront', 'photonic'),
					],
					'hint'    => sprintf(esc_html__('The Collections API is slow, so, if you are displaying collections, pick %1$slazy loading%2$s if your collections have many albums / photosets.', 'photonic'), '<a href="https://aquoid.com/plugins/photonic/flickr/flickr-collections/">', '</a>'),
				],

				[
					'id'      => 'fx',
					'name'    => esc_html__('Slideshow Effects', 'photonic'),
					'type'    => 'select',
					'options' => [
						''      => '',
						'fade'  => esc_html__('Fade', 'photonic'),
						'slide' => esc_html__('Slide', 'photonic'),
					],
					'std'     => '',
					'hint'    => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'      => 'controls',
					'name'    => esc_html__('Slideshow Controls', 'photonic'),
					'type'    => 'select',
					'options' => [
						''     => '',
						'hide' => esc_html__('Hide', 'photonic'),
						'show' => esc_html__('Show', 'photonic'),
					],
					'hint'    => esc_html__('Shows Previous and Next buttons on the slideshow.', 'photonic'),
				],

				[
					'id'   => 'timeout',
					'name' => esc_html__('Time between slides in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'   => 'speed',
					'name' => esc_html__('Time for each transition in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],
			],
		];
	}

	private function get_google_fields() {
		global $photonic_google_media;
		return [
			'name'    => esc_html__('Google Photos', 'photonic'),
			'prelude' => sprintf(
				esc_html__('Documentation: %2$sOverall%1$s | %3$sPhotos%1$s | %4$sAlbums%1$s', 'photonic'),
				'</a>',
				'<a href="https://aquoid.com/plugins/photonic/google-photos/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/google-photos/photos/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/google-photos/albums/" target="_blank">'
			),
			'fields'  => [
				[
					'id'      => 'view',
					'name'    => esc_html__('Display', 'photonic'),
					'type'    => 'select',
					'options' => [
						'albums' => esc_html__('Albums', 'photonic'),
						'photos' => esc_html__('Photos', 'photonic'),
					],
					'hint'    => esc_html__('Pick "Albums" if you want to display a collection of albums. Pick "Photos" if you want to display photos from a single album or from your photo-stream.', 'photonic')
				],

				[
					'id'   => 'album_id',
					'name' => esc_html__('Album', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable if "Display" is "Photos" and you want to show photos from an album. See %1$shere%2$s for more details.', 'photonic'), '<a href="https://aquoid.com/plugins/photonic/google-photos/albums/">', '</a>')
				],

				[
					'id'      => 'access',
					'name'    => esc_html__('Displayed Access Levels', 'photonic'),
					'type'    => 'select',
					'options' => [
						''           => '',
						'all'        => esc_html__('Show all shared and not shared albums', 'photonic'),
						'shared'     => esc_html__('Only show shared albums', 'photonic'),
						'not-shared' => esc_html__('Only show albums not shared', 'photonic'),
					],
					'std'     => '',
					'hint'    => sprintf(esc_html__('If "Display" is "Albums" you can decide to show shared or not-shared albums. See %1$shere%2$s for more details.', 'photonic'), '<a href="https://aquoid.com/plugins/photonic/google-photos/albums/">', '</a>')
				],

				[
					'id'   => 'filter',
					'name' => esc_html__('Album Filter', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('If "Display" is "Albums" and you provide a comma-separated list of values here, these entities will be included / excluded based on the next option. Useful if you want to display thumbnails for certain albums only, ignored if an album id is provided above', 'photonic')
				],

				[
					'id'      => 'filter_type',
					'name'    => esc_html__('Album Filter Type', 'photonic'),
					'type'    => 'select',
					'options' => [
						''        => '',
						'include' => esc_html__('Include above list in results', 'photonic'),
						'exclude' => esc_html__('Exclude above list from results', 'photonic'),
					],
				],

				[
					'id'   => 'date_filters',
					'name' => esc_html__('Date Filters', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(
						esc_html__('Applicable only if "Display" is "Photos". You can provide a comma-separated list of dates and ranges in the format %1$sY/M/D%2$s or %1$sY/M/D-Y/M/D%2$s.', 'photonic'),
						'<code>',
						'</code>'
					)
				],

				[
					'id'   => 'content_filters',
					'name' => esc_html__('Content Filters', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(
						esc_html__('Applicable only if "Display" is "Photos". You can provide a comma-separated list of categories from %s.', 'photonic'),
						'NONE, LANDSCAPES, RECEIPTS, CITYSCAPES, LANDMARKS, SELFIES, PEOPLE, PETS, WEDDINGS, BIRTHDAYS, DOCUMENTS, TRAVEL, ANIMALS, FOOD, SPORT, NIGHT, PERFORMANCES, WHITEBOARDS, SCREENSHOTS, UTILITY'
					)
				],

				[
					'id'      => 'media',
					'name'    => esc_html__('Media to Show', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::media_options(),
					'std'     => $photonic_google_media,
				],

				[
					'id'   => 'count',
					'name' => esc_html__('Number of photos / albums to show', 'photonic'),
					'type' => 'text',
				],

				[
					'id'   => 'more',
					'name' => esc_html__('"More" button text', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a "More" button with the specified text if the number of photos / albums is higher than the above entry. Leave blank to show no button', 'photonic'),
				],

				[
					'id'   => 'photo_count',
					'name' => esc_html__('Number of photos to show in overlaid popup', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable for pagination if %s is selected. Leave blank to show maximum allowed photos.', 'photonic'), '<strong>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</strong>'),
				],

				[
					'id'   => 'photo_more',
					'name' => esc_html__('"More" button text in overlaid popup', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable for pagination if %s is selected. Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button.', 'photonic'), '<strong>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</strong>'),
				],

				[
					'id'      => 'layout',
					'name'    => esc_html__('Layout', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::layout_options(),
					'hint'    => esc_html__('The first four options trigger a slideshow, the rest trigger a lightbox.', 'photonic'),
					'std'     => $this->default_layout,
				],

				[
					'id'   => 'thumb_size',
					'name' => esc_html__('Thumbnail size', 'photonic'),
					'type' => 'text',
					'std'  => 150,
					'hint' => esc_html__('In pixels, only applicable to square and circular thumbnails. Permitted values: 32, 48, 64, 72, 104, 144, 150, 160.', 'photonic')
				],

				[
					'id'      => 'crop_thumb',
					'name'    => esc_html__('Crop Thumbnail', 'photonic'),
					'type'    => 'select',
					'options' => [
						'crop'    => esc_html__('Crop the thumbnail', 'photonic'),
						'no-crop' => esc_html__('Do not crop the thumbnail', 'photonic'),
					],
					'std'     => 'crop',
					'hint'    => esc_html__('Cropping the thumbnail presents you with a square thumbnail.', 'photonic')
				],

				[
					'id'   => 'main_size',
					'name' => esc_html__('Main image size', 'photonic'),
					'type' => 'text',
					'std'  => 1600,
					'hint' => esc_html__('Numeric values between 1 and 16383, both inclusive.', 'photonic')
				],

				[
					'id'   => 'tile_size',
					'name' => esc_html__('Tile image size', 'photonic'),
					'type' => 'text',
					'std'  => 1600,
					'hint' => esc_html__('Numeric values between 1 and 16383, both inclusive. Leave blank to use the "Main image size".', 'photonic')
				],

				[
					'id'      => 'fx',
					'name'    => esc_html__('Slideshow Effects', 'photonic'),
					'type'    => 'select',
					'options' => [
						''      => '',
						'fade'  => esc_html__('Fade', 'photonic'),
						'slide' => esc_html__('Slide', 'photonic'),
					],
					'std'     => '',
					'hint'    => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'      => 'controls',
					'name'    => esc_html__('Slideshow Controls', 'photonic'),
					'type'    => 'select',
					'options' => [
						''     => '',
						'hide' => esc_html__('Hide', 'photonic'),
						'show' => esc_html__('Show', 'photonic'),
					],
					'hint'    => esc_html__('Shows Previous and Next buttons on the slideshow.', 'photonic'),
				],

				[
					'id'   => 'timeout',
					'name' => esc_html__('Time between slides in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'   => 'speed',
					'name' => esc_html__('Time for each transition in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],
			],
		];
	}

	private function get_smugmug_fields() {
		global $photonic_smug_title_caption, $photonic_smug_thumb_size, $photonic_smug_main_size, $photonic_smug_media;
		return [
			'name'    => esc_html__('SmugMug', 'photonic'),
			'prelude' => sprintf(
				esc_html__('You have to define your SmugMug API Key under %1$s.%2$s Documentation: %4$sOverall%3$s | %5$sPhotos%3$s | %6$sAlbums%3$s | %7$sFolders%3$s | %8$sUser Tree%3$s', 'photonic'),
				'<strong>Photonic &rarr; Settings &rarr; SmugMug &rarr; SmugMug Settings</strong>',
				'<br/>',
				'</a>',
				'<a href="https://aquoid.com/plugins/photonic/smugmug/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/smugmug/smugmug-photos/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/smugmug/smugmug-albums/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/smugmug/folders/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/smugmug/smugmug-tree/" target="_blank">'
			),
			'fields'  => [
				[
					'id'      => 'view',
					'name'    => esc_html__('Display', 'photonic'),
					'type'    => 'select',
					'options' => [
						'tree'   => esc_html__('Tree', 'photonic'),
						'albums' => esc_html__('All albums', 'photonic'),
						'album'  => esc_html__('Single Album', 'photonic'),
						'folder' => esc_html__('Single Folder', 'photonic'),
					],
					'req'     => true,
				],

				[
					'id'   => 'nick_name',
					'name' => esc_html__('Nickname', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('If your SmugMug URL is https://joe-sixpack.smugmug.com, this is "joe-sixpack". Required if the "Display" is "Tree" or "All albums".', 'photonic')
				],

				[
					'id'   => 'site_password',
					'name' => esc_html__('Site Password', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Required if your entire SmugMug site is password-protected. See documentation link for "Albums" above.', 'photonic')
				],

				[
					'id'   => 'album',
					'name' => esc_html__('Album', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Required if you are showing "Single Album" above. To find this, go to %s on your dashboard and find the albums for your nickname.', 'photonic'), '<strong>Photonic &rarr; Helpers &rarr; SmugMug</strong>')
				],

				[
					'id'   => 'filter',
					'name' => esc_html__('Filter', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('If "Display" is "All albums" and you provide a comma-separated list of album keys here, these entities will be included / excluded based on the next option. Useful if you want to display thumbnails for certain albums only, ignored if an album is provided above', 'photonic')
				],

				[
					'id'      => 'filter_type',
					'name'    => esc_html__('Filter Type', 'photonic'),
					'type'    => 'select',
					'options' => [
						''        => '',
						'include' => esc_html__('Include above list in results', 'photonic'),
						'exclude' => esc_html__('Exclude above list from results', 'photonic'),
					],
				],

				[
					'id'   => 'text',
					'name' => esc_html__('Photos with text', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show photos with the specified text. Leave blank for no filter.', 'photonic')
				],

				[
					'id'   => 'keywords',
					'name' => esc_html__('Photos with keywords', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show photos with the specified keywords. Leave blank for no filter.', 'photonic')
				],

				[
					'id'      => 'media',
					'name'    => esc_html__('Media to Show', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::media_options(),
					'std'     => $photonic_smug_media,
				],

				[
					'id'   => 'password',
					'name' => esc_html__('Album Password', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Required if you are showing "Single Album" above, and if your album is password-protected. See documentation link for "Photos" above.', 'photonic')
				],

				[
					'id'      => 'album_sort_order',
					'name'    => esc_html__('Album sort order', 'photonic'),
					'type'    => 'select',
					'options' => [
						'Album Settings'            => esc_html__('Album Settings', 'photonic'),
						'Position'                  => esc_html__('Position', 'photonic'),
						'Last Updated (Descending)' => esc_html__('Last Updated (Descending)', 'photonic'),
						'Last Updated (Ascending)'  => esc_html__('Last Updated (Ascending)', 'photonic'),
						'Date Added (Descending)'   => esc_html__('Date Added (Descending)', 'photonic'),
						'Date Added (Ascending)'    => esc_html__('Date Added (Ascending)', 'photonic'),
					],
					'std'     => 'Album Settings',
					'hint'    => esc_html__('If you are displaying multiple albums the results are sorted by this parameter', 'photonic'),
				],

				[
					'id'   => 'folder',
					'name' => esc_html__('Folder', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Required if you are showing "Single Folder" above. To find this, go to %s on your dashboard and find the folders for your nickname.', 'photonic'), '<strong>Photonic &rarr; Helpers &rarr; SmugMug</strong>')
				],

				[
					'id'      => 'layout',
					'name'    => esc_html__('Layout', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::layout_options(),
					'hint'    => esc_html__('The first four options trigger a slideshow, the rest trigger a lightbox.', 'photonic'),
					'std'     => $this->default_layout,
				],

				[
					'id'      => 'caption',
					'name'    => esc_html__('Photo title / caption', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::title_caption_options(),
					'std'     => $photonic_smug_title_caption,
					'hint'    => esc_html__('This will be used as the title for your photos.', 'photonic'),
				],

				[
					'id'      => 'thumb_size',
					'name'    => esc_html__('Thumbnail size', 'photonic'),
					'type'    => 'select',
					'std'     => $photonic_smug_thumb_size,
					"options" => [
						'Tiny'  => esc_html__('Tiny', 'photonic'),
						'Thumb' => esc_html__('Thumb', 'photonic'),
						'Small' => esc_html__('Small', 'photonic'),
					],
					'hint'    => esc_html__('In pixels, only applicable to square and circular thumbnails', 'photonic')
				],

				[
					'id'      => 'main_size',
					'name'    => esc_html__('Main image size', 'photonic'),
					'type'    => 'select',
					'std'     => $photonic_smug_main_size,
					'options' => [
						'4K'       => esc_html__('4K (not always available)', 'photonic'),
						'5K'       => esc_html__('5K (not always available)', 'photonic'),
						'Medium'   => esc_html__('Medium', 'photonic'),
						'Original' => esc_html__('Original (not always available)', 'photonic'),
						'Large'    => esc_html__('Large', 'photonic'),
						'Largest'  => esc_html__('Largest available', 'photonic'),
						'XLarge'   => esc_html__('XLarge (not always available)', 'photonic'),
						'X2Large'  => esc_html__('X2Large (not always available)', 'photonic'),
						'X3Large'  => esc_html__('X3Large (not always available)', 'photonic'),
					],
				],

				[
					'id'   => 'columns',
					'name' => esc_html__('Number of columns', 'photonic'),
					'type' => 'text',
				],

				[
					'id'   => 'count',
					'name' => esc_html__('Number of photos / albums to show', 'photonic'),
					'type' => 'text',
				],

				[
					'id'   => 'more',
					'name' => esc_html__('"More" button text', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a "More" button with the specified text if the number of photos / albums is higher than the above entry. Leave blank to show no button', 'photonic'),
				],

				[
					'id'   => 'photo_count',
					'name' => esc_html__('Number of photos to show in overlaid popup', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable for pagination if %s is selected. Leave blank to show maximum allowed photos.', 'photonic'), '<strong>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</strong>'),
				],

				[
					'id'   => 'photo_more',
					'name' => esc_html__('"More" button text in overlaid popup', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable for pagination if %s is selected. Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button.', 'photonic'), '<strong>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</strong>'),
				],

				[
					'id'      => 'fx',
					'name'    => esc_html__('Slideshow Effects', 'photonic'),
					'type'    => 'select',
					'options' => [
						''      => '',
						'fade'  => esc_html__('Fade', 'photonic'),
						'slide' => esc_html__('Slide', 'photonic'),
					],
					'std'     => '',
					'hint'    => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'      => 'controls',
					'name'    => esc_html__('Slideshow Controls', 'photonic'),
					'type'    => 'select',
					'options' => [
						''     => '',
						'hide' => esc_html__('Hide', 'photonic'),
						'show' => esc_html__('Show', 'photonic'),
					],
					'hint'    => esc_html__('Shows Previous and Next buttons on the slideshow.', 'photonic'),
				],

				[
					'id'   => 'timeout',
					'name' => esc_html__('Time between slides in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'   => 'speed',
					'name' => esc_html__('Time for each transition in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],
			],
		];
	}

	private function get_zenfolio_fields() {
		global $photonic_zenfolio_title_caption, $photonic_zenfolio_thumb_size, $photonic_zenfolio_main_size, $photonic_zenfolio_media;
		return [
			'name'    => esc_html__('Zenfolio', 'photonic'),
			'prelude' => sprintf(
				esc_html__('Documentation: %2$sOverall%1$s | %3$sPhotos%1$s | %4$sPhotosets%1$s | %5$sGroups%1$s | %6$sGroup Hierarchy%1$s', 'photonic'),
				'</a>',
				'<a href="https://aquoid.com/plugins/photonic/zenfolio/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/zenfolio/photos/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/zenfolio/photosets/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/zenfolio/groups/" target="_blank">',
				'<a href="https://aquoid.com/plugins/photonic/zenfolio/group-hierarchy/" target="_blank">'
			),
			'fields'  => [
				[
					'id'      => 'view',
					'name'    => esc_html__('Display', 'photonic'),
					'type'    => 'select',
					'options' => [
						'photos'    => esc_html__('Photos', 'photonic'),
						'photosets' => esc_html__('Photosets', 'photonic'),
						'hierarchy' => esc_html__('Group Hierarchy', 'photonic'),
						'group'     => esc_html__('Group', 'photonic'),
					],
					'req'     => true,
				],

				[
					'id'   => 'object_id',
					'name' => esc_html__('Object ID', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Can be set if "Display" is %1$sPhotos%2$s, %1$sPhotosets%2$s or %1$sGroup%2$s.', 'photonic'), '<code>', '</code>'),
				],

				[
					'id'   => 'text',
					'name' => esc_html__('Search by text', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Can be set if "Display" is %1$sPhotos%2$s or %1$sPhotosets%2$s.', 'photonic'), '<code>', '</code>'),
				],

				[
					'id'   => 'category_code',
					'name' => esc_html__('Search by category code', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Can be set if "Display" is %1$sPhotos%2$s or %1$sPhotosets%2$s.', 'photonic'), '<code>', '</code>') . '<br/>' .
						sprintf(esc_html__('See the list of categories from %s.', 'photonic'), '<strong>Photonic &rarr; Helpers</strong>'),
				],

				[
					'id'      => 'sort_order',
					'name'    => esc_html__('Search results sort order', 'photonic'),
					'type'    => 'select',
					'options' => [
						''           => '',
						'Date'       => esc_html__('Date', 'photonic'),
						'Popularity' => esc_html__('Popularity', 'photonic'),
						'Rank'       => esc_html__('Rank (for searching by text only)', 'photonic'),
					],
					'hint'    => sprintf(esc_html__('Can be set if "Display" is %1$sPhotos%2$s or %1$sPhotosets%2$s.', 'photonic'), '<code>', '</code>') . '<br/>' .
						esc_html__('For search results only.', 'photonic'),
				],

				[
					'id'      => 'photoset_type',
					'name'    => esc_html__('Photoset type', 'photonic'),
					'type'    => 'select',
					'options' => [
						''           => '',
						'Gallery'    => esc_html__('Gallery', 'photonic'),
						'Collection' => esc_html__('Collection', 'photonic'),
					],
					'hint'    => esc_html__('Mandatory if Display = Photosets and no Object ID is specified.', 'photonic'),
				],

				[
					'id'      => 'kind',
					'name'    => esc_html__('Display classification', 'photonic'),
					'type'    => 'select',
					'options' => [
						''        => '',
						'popular' => esc_html__('Popular', 'photonic'),
						'recent'  => esc_html__('Recent', 'photonic'),
					],
					'hint'    => sprintf(esc_html__('Mandatory if "Display" is %1$sPhotos%2$s or %1$sPhotosets%2$s, and none of the other criteria above is specified.', 'photonic'), '<code>', '</code>'),
				],

				[
					'id'   => 'login_name',
					'name' => esc_html__('Login name', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Mandatory if Display = Hierarchy', 'photonic'),
				],

				[
					'id'      => 'media',
					'name'    => esc_html__('Media to Show', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::media_options(),
					'std'     => $photonic_zenfolio_media,
				],

				[
					'id'      => 'layout',
					'name'    => esc_html__('Layout', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::layout_options(),
					'hint'    => esc_html__('The first four options trigger a slideshow, the rest trigger a lightbox.', 'photonic'),
					'std'     => $this->default_layout,
				],

				[
					'id'      => 'structure',
					'name'    => esc_html__('Group / Hierarchy structure', 'photonic'),
					'type'    => 'select',
					'options' => [
						''       => '',
						'flat'   => esc_html__('All photosets shown in single level', 'photonic'),
						'nested' => esc_html__('Photosets shown nested within groups', 'photonic'),
					],
					'hint'    => esc_html__('Applicable if groups or hierarchies are being displayed.', 'photonic'),
				],

				[
					'id'      => 'caption',
					'name'    => esc_html__('Photo title / caption', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::title_caption_options(),
					'std'     => $photonic_zenfolio_title_caption,
					'hint'    => esc_html__('This will be used as the title for your photos.', 'photonic'),
				],

				[
					'id'      => 'thumb_size',
					'name'    => esc_html__('Thumbnail size', 'photonic'),
					'type'    => 'select',
					'std'     => $photonic_zenfolio_thumb_size,
					"options" => [
						"1"  => esc_html__("Square thumbnail, 60 &times; 60px, cropped square", 'photonic'),
						"0"  => esc_html__("Small thumbnail, upto 80 &times; 80px", 'photonic'),
						"10" => esc_html__("Medium thumbnail, upto 120 &times; 120px", 'photonic'),
						"11" => esc_html__("Large thumbnail, upto 120 &times; 120px", 'photonic'),
						"2"  => esc_html__("Small image, upto 400 &times; 400px", 'photonic'),
					],
					'hint'    => esc_html__('In pixels, only applicable to square and circular thumbnails', 'photonic')
				],

				[
					'id'      => 'main_size',
					'name'    => esc_html__('Main image size', 'photonic'),
					'type'    => 'select',
					'std'     => $photonic_zenfolio_main_size,
					'options' => [
						'2' => esc_html__('Small image, upto 400 &times; 400px', 'photonic'),
						'3' => esc_html__('Medium image, upto 580 &times; 450px', 'photonic'),
						'4' => esc_html__('Large image, upto 800 &times; 630px', 'photonic'),
						'5' => esc_html__('X-Large image, upto 1100 &times; 850px', 'photonic'),
						'6' => esc_html__('XX-Large image, upto 1550 &times; 960px', 'photonic'),
					],
				],

				[
					'id'   => 'columns',
					'name' => esc_html__('Number of columns', 'photonic'),
					'type' => 'text',
				],

				[
					'id'     => 'count',
					'name'   => esc_html__('Number of photos to show', 'photonic'),
					'type'   => 'text',
				],

				[
					'id'   => 'more',
					'name' => esc_html__('"More" button text', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button', 'photonic'),
				],

				[
					'id'   => 'photo_count',
					'name' => esc_html__('Number of photos to show in overlaid popup', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable for pagination if %s is selected. Leave blank to show maximum allowed photos.', 'photonic'), '<strong>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</strong>'),
				],

				[
					'id'   => 'photo_more',
					'name' => esc_html__('"More" button text in overlaid popup', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('Applicable for pagination if %s is selected. Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button.', 'photonic'), '<strong>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr; Enable Interim Popup for Album Thumbnails</strong>'),
				],

				[
					'id'      => 'fx',
					'name'    => esc_html__('Slideshow Effects', 'photonic'),
					'type'    => 'select',
					'options' => [
						''      => '',
						'fade'  => esc_html__('Fade', 'photonic'),
						'slide' => esc_html__('Slide', 'photonic'),
					],
					'std'     => '',
					'hint'    => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'      => 'controls',
					'name'    => esc_html__('Slideshow Controls', 'photonic'),
					'type'    => 'select',
					'options' => [
						''     => '',
						'hide' => esc_html__('Hide', 'photonic'),
						'show' => esc_html__('Show', 'photonic'),
					],
					'hint'    => esc_html__('Shows Previous and Next buttons on the slideshow.', 'photonic'),
				],

				[
					'id'   => 'timeout',
					'name' => esc_html__('Time between slides in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'   => 'speed',
					'name' => esc_html__('Time for each transition in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],
			],
		];
	}

	private function get_instagram_fields() {
		global $photonic_instagram_media;
		return [
			'name'    => esc_html__('Instagram', 'photonic'),
			'prelude' => sprintf(esc_html__('Documentation: %1$sInstagram%2$s', 'photonic'), '<a href="https://aquoid.com/plugins/photonic/instagram/" target="_blank">', '</a>'),
			'fields'  => [
				[
					'id'   => 'media_id',
					'name' => esc_html__('Media ID', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('If your photo is at %1$shttps://instagram.com/p/ABcde5678fg/%2$s, your media id is %1$sABcde5678fg%2$s.', 'photonic'), '<code>', '</code>')
				],

				[
					'id'   => 'carousel',
					'name' => esc_html__('Instagram Post ID', 'photonic'),
					'type' => 'text',
					'hint' => sprintf(esc_html__('If your post is at %1$shttps://instagram.com/p/ABcde5678fg/%2$s, your post id is %1$sABcde5678fg%2$s.', 'photonic'), '<code>', '</code>')
				],

				[
					'id'      => 'layout',
					'name'    => esc_html__('Layout', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::layout_options(),
					'hint'    => esc_html__('The first four options trigger a slideshow, the rest trigger a lightbox.', 'photonic'),
					'std'     => $this->default_layout,
				],

				[
					'id'      => 'media',
					'name'    => esc_html__('Media to Show', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::media_options(),
					'std'     => $photonic_instagram_media,
				],

				[
					'id'   => 'columns',
					'name' => esc_html__('Number of columns', 'photonic'),
					'type' => 'text',
				],

				[
					'id'   => 'count',
					'name' => esc_html__('Number of photos to show', 'photonic'),
					'type' => 'text',
				],

				[
					'id'   => 'more',
					'name' => esc_html__('"More" button text', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Will show a "More" button with the specified text if the number of photos is higher than the above entry. Leave blank to show no button', 'photonic'),
				],

				[
					'id'      => 'fx',
					'name'    => esc_html__('Slideshow Effects', 'photonic'),
					'type'    => 'select',
					'options' => [
						''      => '',
						'fade'  => esc_html__('Fade', 'photonic'),
						'slide' => esc_html__('Slide', 'photonic'),
					],
					'std'     => '',
					'hint'    => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'      => 'controls',
					'name'    => esc_html__('Slideshow Controls', 'photonic'),
					'type'    => 'select',
					'options' => [
						''     => '',
						'hide' => esc_html__('Hide', 'photonic'),
						'show' => esc_html__('Show', 'photonic'),
					],
					'hint'    => esc_html__('Shows Previous and Next buttons on the slideshow.', 'photonic'),
				],

				[
					'id'   => 'timeout',
					'name' => esc_html__('Time between slides in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],

				[
					'id'   => 'speed',
					'name' => esc_html__('Time for each transition in ms', 'photonic'),
					'type' => 'text',
					'std'  => '',
					'hint' => esc_html__('Applies to slideshows only', 'photonic')
				],
			],
		];
	}

	public static function get_image_sizes_selection($element_name, $show_full = false) {
		$image_sizes = Utilities::get_wp_image_sizes($show_full);
		$ret = "<select name='$element_name'>";
		foreach ($image_sizes as $size_name => $size_attrs) {
			$ret .= "<option value='$size_name'>$size_name ({$size_attrs['width']} &times; {$size_attrs['height']})</option>";
		}
		$ret .= '</select>';
		return $ret;
	}
}
