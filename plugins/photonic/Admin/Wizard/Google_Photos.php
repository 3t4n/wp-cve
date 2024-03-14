<?php

namespace Photonic_Plugin\Admin\Wizard;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Core\Utilities;

class Google_Photos extends Source {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		$this->provider = 'google';
		$this->api_base = Wizard::base_apis()['google'];

		$this->allowed_image_sizes['google'] = [
			'thumb_size' => [
				'32'  => '32',
				'48'  => 48,
				'64'  => 64,
				'72'  => 72,
				'104' => 104,
				'144' => 144,
				'150' => 150,
				'160' => 160,
			],
			'tile_size'  => [
				'same' => esc_html__('Same as Main image size', 'photonic'),
				'94'   => 94,
				'110'  => 110,
				'128'  => 128,
				'200'  => 200,
				'220'  => 220,
				'288'  => 288,
				'320'  => 320,
				'400'  => 400,
				'512'  => 512,
				'576'  => 576,
				'640'  => 640,
				'720'  => 720,
				'800'  => 800,
				'912'  => 912,
				'1024' => 1024,
				'1152' => 1152,
				'1280' => 1280,
				'1440' => 1440,
				'1600' => 1600,
			],
			'main_size'  => [
				'94'   => 94,
				'110'  => 110,
				'128'  => 128,
				'200'  => 200,
				'220'  => 220,
				'288'  => 288,
				'320'  => 320,
				'400'  => 400,
				'512'  => 512,
				'576'  => 576,
				'640'  => 640,
				'720'  => 720,
				'800'  => 800,
				'912'  => 912,
				'1024' => 1024,
				'1152' => 1152,
				'1280' => 1280,
				'1440' => 1440,
				'1600' => 1600,
			],
		];
	}

	public static function get_instance(): Google_Photos {
		if (null === self::$instance) {
			self::$instance = new Google_Photos();
		}
		return self::$instance;
	}

	public function get_screen_2(): array {
		return [
			'header'  => esc_html__('Choose Type of Gallery', 'photonic'),
			'display' => [
				'display_type' => [
					'desc'    => esc_html__('What do you want to show?', 'photonic'),
					'type'    => 'select',
					'options' => [
						''                   => '',
						'multi-photo'        => esc_html__('Multiple Photos', 'photonic'),
						'album-photo'        => esc_html__('Photos from an Album Belonging to You', 'photonic'),
						'shared-album-photo' => esc_html__('Photos from an Album Shared by or with You', 'photonic'),
						'multi-album'        => esc_html__('Multiple Albums', 'photonic'),
					],
					'req'     => 1,
				],
			],
		];
	}

	public function get_screen_3(): array {
		return [
			'header'             => esc_html__('Build your gallery', 'photonic'),
			'multi-photo'        => [
				'header'  => esc_html__('All your photos', 'photonic'),
				'desc'    => esc_html__('You can show all your photos, or apply filters to show some of them.', 'photonic'),
				'display' => [
					'date_filters' => [
						'desc'  => esc_html__('Date Filters', 'photonic'),
						'type'  => 'date-filter',
						'count' => 5
					],

					'date_range_filters' => [
						'desc'  => esc_html__('Date Range Filters', 'photonic'),
						'type'  => 'date-range-filter',
						'count' => 5
					],

					'content_filters' => [
						'desc' => esc_html__('Content Filters', 'photonic'),
						'type' => 'text',
						'hint' => sprintf(
							esc_html__('Comma-separated. Pick from: %s. Filters will be applied on the front-end, not on the display below. ', 'photonic'),
							'NONE, LANDSCAPES, RECEIPTS, CITYSCAPES, LANDMARKS, SELFIES, PEOPLE, PETS, WEDDINGS, BIRTHDAYS, DOCUMENTS, TRAVEL, ANIMALS, FOOD, SPORT, NIGHT, PERFORMANCES, WHITEBOARDS, SCREENSHOTS, UTILITY'
						) .
						Photonic::doc_link("https://aquoid.com/plugins/photonic/google-photos/photos/#filtering-photos"),
					],

					'container' => [
						'type' => 'thumbnail-selector',
						'mode' => 'none',
						'for'  => 'selected_data',
					],
				],
			],
			'album-photo'        => [
				'header'  => esc_html__('Pick your album', 'photonic'),
				'desc'    => esc_html__('From the list below pick the album whose photos you wish to display. Photos from that album will show up as thumbnails.', 'photonic'),
				'display' => [
					'container' => [
						'type' => 'thumbnail-selector',
						'mode' => 'single',
						'for'  => 'selected_data',
					],
				],
			],
			'shared-album-photo' => [
				'header'  => esc_html__('Pick your album', 'photonic'),
				'desc'    => esc_html__('From the list below pick the album whose photos you wish to display. Photos from that album will show up as thumbnails.', 'photonic'),
				'display' => [
					'container' => [
						'type' => 'thumbnail-selector',
						'mode' => 'single',
						'for'  => 'selected_data',
					],
				],
			],
			'multi-album'        => [
				'header'  => esc_html__('Pick your albums', 'photonic'),
				'desc'    => esc_html__('From the list below pick the albums you wish to display. Each album will show up as a single thumbnail.', 'photonic'),
				'display' => [
					'selection' => [
						'desc'    => esc_html__('What do you want to show?', 'photonic'),
						'type'    => 'select',
						'options' => [
							'all'          => esc_html__('Automatic all (will automatically add new albums)', 'photonic'),
							'selected'     => esc_html__('Selected albums', 'photonic'),
							'not-selected' => esc_html__('All except selected albums', 'photonic'),
						],
						'hint'    => esc_html__('If you pick "Automatic all" your selections below will be ignored.', 'photonic'),
						'req'     => 1,
					],
					'access'    => [
						'desc'    => esc_html__('What type of album?', 'photonic'),
						'type'    => 'select',
						'options' => [
							''           => '',
							'all'        => esc_html__('Show both shared and not shared albums', 'photonic'),
							'shared'     => esc_html__('Only show shared albums', 'photonic'),
							'not-shared' => esc_html__('Only show albums not shared', 'photonic'),
						],
						'std'     => '',
					],
					'container' => [
						'type' => 'thumbnail-selector',
						'mode' => 'multi',
						'for'  => 'selected_data',
					],
				],
			],
		];
	}

	public function get_screen_4(): array {
		return [];
	}

	public function get_screen_5(): array {
		global $photonic_google_media;
		return [
			'google' => [
				'L1'        => [
					'media' => [
						'desc'    => esc_html__('Media to Show', 'photonic'),
						'type'    => 'select',
						'options' => Utilities::media_options(true, $photonic_google_media),
						'std'     => '',
						'hint'    => sprintf($this->default_under, '<em>Photonic &rarr; Settings &rarr; Google Photos &rarr; Google Photos settings &rarr; Media to show</em>'),
					],

					'caption' => [
						'desc'    => esc_html__('Photo titles and captions', 'photonic'),
						'type'    => 'select',
						'options' => [
							''      => $this->default_from_settings,
							'none'  => esc_html__('No title / caption / description', 'photonic'),
							'title' => esc_html__('Always use the photo title, even if blank', 'photonic'),
						],
						'std'     => '',
						'hint'    => sprintf($this->default_under, '<em>Photonic &rarr; Settings &rarr; Google Photos &rarr; Google Photos Settings &rarr; Photo titles and captions</em>'),
					],
				],
				'main_size' => [
					'desc' => esc_html__('Main image size', 'photonic'),
					'type' => 'text',
					'std'  => 1600,
					'hint' => esc_html__('Numeric values between 1 and 16383, both inclusive.', 'photonic'),
				],
			]
		];
	}

	public function get_square_size_options(): array {
		return [
			'thumb_size' => [
				'desc' => esc_html__('Thumbnail size', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Numeric values between 1 and 256, both inclusive.', 'photonic'),
				'std'  => 150,
			],
			'crop_thumb' => [
				'desc'    => esc_html__('Crop Thumbnail', 'photonic'),
				'type'    => 'select',
				'options' => [
					'crop'    => esc_html__('Crop the thumbnail', 'photonic'),
					'no-crop' => esc_html__('Do not crop the thumbnail', 'photonic'),
				],
				'std'     => 'crop',
				'hint'    => esc_html__('Cropping the thumbnail presents you with a square thumbnail.', 'photonic')
			],
		];
	}

	public function get_random_size_options(): array {
		return [
			'tile_size' => [
				'desc' => esc_html__('Tile size', 'photonic'),
				'type' => 'text',
				'hint' => esc_html__('Numeric values between 1 and 16383, both inclusive. Leave blank to use the "Main image size".', 'photonic'),
			],
		];
	}

	public function make_request($display_type, $for, $flattened_fields): array {
		global $photonic_google_refresh_token;

		require_once PHOTONIC_PATH . '/Platforms/Google_Photos.php';
		$module = \Photonic_Plugin\Platforms\Google_Photos::get_instance();
		$module->authenticate($photonic_google_refresh_token);

		$parameters = [];

		// All OK so far. Let's try to get the data for the next screen
		$parameters['access_token'] = $module->access_token;

		$query_url = '';
		if ('multi-photo' === $display_type) {
			$query_url = 'https://' . $this->api_base . '/v1/mediaItems:search';
		}
		elseif ('multi-album' === $display_type || 'album-photo' === $display_type) {
			$query_url = 'https://' . $this->api_base . '/v1/albums?pageSize=50';
		}
		elseif ('shared-album-photo' === $display_type) {
			$query_url = 'https://' . $this->api_base . '/v1/sharedAlbums?pageSize=50';
		}

		$query_url = add_query_arg($parameters, $query_url);
		$response = wp_remote_request($query_url, ['method' => ('multi-photo' === $display_type ? 'POST' : 'GET'), 'sslverify' => PHOTONIC_SSL_VERIFY]);
		return [$response, [], $query_url];
	}

	/**
	 * Processes a response from Google to build it out into a gallery of thumbnails. Google has both, L1 and L2 displays in the flow.
	 *
	 * @param $response
	 * @param $display_type
	 * @param $url
	 * @param array $pagination
	 * @return array
	 */
	public function process_response($response, $display_type, $url = null, &$pagination = []): array {
		$objects = [];
		$body = json_decode(wp_remote_retrieve_body($response));
		if (isset($body->albums) || isset($body->sharedAlbums)) {
			$albums = isset($body->albums) ? $body->albums : $body->sharedAlbums;
			foreach ($albums as $album) {
				$object = [];
				$object['id'] = $album->id;
				$object['title'] = !empty($album->title) ? esc_attr($album->title) : '';
				$object['counters'] = [esc_html(sprintf(_n('%s media item', '%s media items', $album->mediaItemsCount, 'photonic'), $album->mediaItemsCount))];
				$object['thumbnail'] = $album->coverPhotoBaseUrl . "=w150-h150-c";
				$objects[] = $object;
			}
			if (!empty($body->nextPageToken)) {
				$pagination['url'] = add_query_arg(['pageToken' => $body->nextPageToken], remove_query_arg(['pageToken'], $url));
			}
		}
		elseif (isset($body->mediaItems)) {
			$photos = $body->mediaItems;
			foreach ($photos as $photo) {
				$object = [];
				$object['id'] = $photo->id;
				$object['title'] = !empty($photo->description) ? esc_attr($photo->description) : '';
				$object['thumbnail'] = $photo->baseUrl . "=w150-h150-c";
				$objects[] = $object;
			}
		}
		elseif (isset($body->error)) {
			$objects['error'] = $body->error->message;
		}
		return $objects;
	}

	/**
	 * @param $display_type
	 * @return array
	 */
	public function construct_shortcode_from_screen_selections($display_type): array {
		$short_code = [];

		if (check_ajax_referer('photonic-wizard-next-' . get_current_user_id())) {
			if ('album-photo' === $display_type) {
				$short_code['view'] = 'photos';
				$short_code['album_id'] = sanitize_text_field($_POST['selected_data']);
			}
			elseif ('shared-album-photo' === $display_type) {
				$short_code['view'] = 'shared-photos';
				$short_code['album_id'] = sanitize_text_field($_POST['selected_data']);
			}
			elseif ('multi-photo' === $display_type) {
				$short_code['view'] = 'photos';
				$date_filters = !empty($_POST['date_filters']) ? sanitize_text_field($_POST['date_filters']) : '';
				$date_range_filters = !empty($_POST['date_range_filters']) ? sanitize_text_field($_POST['date_range_filters']) : '';
				$short_code['date_filters'] = trim($date_filters . ',' . $date_range_filters, ',');
			}
			elseif ('multi-album' === $display_type) {
				$short_code['view'] = 'albums';
			}
		}

		return $short_code;
	}

	/**
	 * @param $input
	 * @return array
	 */
	public function deconstruct_shortcode_to_screen_selections($input): array {
		$deconstructed = [];

		if (!empty($input->view)) {
			if ('photos' === $input->view || 'shared-photos' === $input->view) {
				if (!empty($input->album_id)) {
					$deconstructed['display_type'] = ('photos' === $input->view) ? 'album-photo' : 'shared-album-photo';
					$deconstructed['selected_data'] = sanitize_text_field($input->album_id);
				}
				else {
					$deconstructed['display_type'] = 'multi-photo';
					if (!empty($input->date_filters)) {
						$filters = sanitize_text_field($input->date_filters);
						$filters = explode(',', $filters);
						$date_filters = [];
						$date_range_filters = [];
						foreach ($filters as $filter) {
							$maybe_range = explode('-', $filter);
							if (count($maybe_range) === 2) {
								$date_range_filters[] = $filter;
							}
							elseif (count($maybe_range) === 1) {
								$date_filters[] = $filter;
							}
						}
						if (count($date_range_filters) > 5) {
							$date_range_filters = array_slice($date_range_filters, 0, 5);
						}
						if (count($date_filters) > 5) {
							$date_filters = array_slice($date_filters, 0, 5);
						}
						$date_range_filters = implode(',', $date_range_filters);
						$date_filters = implode(',', $date_filters);
						if (!empty($date_filters)) {
							$deconstructed['date_filters'] = $date_filters;
						}
						if (!empty($date_range_filters)) {
							$deconstructed['date_range_filters'] = $date_range_filters;
						}
					}
				}
			}
			elseif ('albums' === $input->view) {
				$deconstructed['display_type'] = 'multi-album';
			}
		}

		return $deconstructed;
	}
}
