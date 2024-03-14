<?php

namespace Photonic_Plugin\Admin\Wizard;

use Photonic_Plugin\Core\Utilities;

class Instagram extends Source {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		$this->provider = 'instagram';
		$this->api_base = Wizard::base_apis()['instagram'];
	}

	public static function get_instance(): Instagram {
		if (null === self::$instance) {
			self::$instance = new Instagram();
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
						''             => '',
						'single-photo' => esc_html__('Single Photo', 'photonic'),
						'album-photo'  => esc_html__('Photos in a Post', 'photonic'),
						'multi-photo'  => esc_html__('Multiple Photos', 'photonic'),
					],
					'req'     => 1,
				],
			],
		];
	}

	public function get_screen_3(): array {
		return [
			'header'       => esc_html__('Build your gallery', 'photonic'),
			'single-photo' => [
				'header'  => esc_html__('Pick a photo', 'photonic'),
				'desc'    => esc_html__('From the list below pick the single photo you wish to display.', 'photonic'),
				'display' => [
					'container' => [
						'type' => 'thumbnail-selector',
						'mode' => 'single',
						'for'  => 'selected_data',
					],
				],
			],
			'album-photo'  => [
				'header'  => esc_html__('Pick a post (a.k.a. Carousel)', 'photonic'),
				'desc'    => esc_html__('From the list below pick the post whose photos you wish to display.', 'photonic'),
				'display' => [
					'carousel_caption' => [
						'desc'    => esc_html__('Where do you want to show the caption for the post / carousel?', 'photonic'),
						'type'    => 'select',
						'options' => [
							''      => $this->default_from_settings,
							'none'  => esc_html__('Do not show the caption', 'photonic'),
							'above' => esc_html__('Show above the photos', 'photonic'),
							'below' => esc_html__('Show below the photos', 'photonic'),
						],
						'std'     => '',
						'hint'    => sprintf($this->default_under, '<em>Photonic &rarr; Settings &rarr; Instagram &rarr; Instagram Settings &rarr; Caption positioning for carousels / posts</em>'),
					],
					'container'        => [
						'type' => 'thumbnail-selector',
						'mode' => 'single',
						'for'  => 'selected_data',
					],
				],
			],
			'multi-photo'  => [
				'header'  => esc_html__('All your photos', 'photonic'),
				'desc'    => esc_html__('You can only show all your photos without filtering. In the following only the latest 25 photos are displayed. You can change this in subsequent screens.', 'photonic'),
				'display' => [
					'carousel_handling' => [
						'desc'    => esc_html__('How do you want to show photos in a carousel?', 'photonic'),
						'type'    => 'select',
						'options' => [
							''       => '',
							'single' => esc_html__('Show first photo', 'photonic'),
							'expand' => esc_html__('Show all photos', 'photonic'),
						],
						'std'     => '',
					],
					'container'         => [
						'type' => 'thumbnail-selector',
						'mode' => 'none',
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
		global $photonic_instagram_media;
		return [
			'instagram' => [
				'media' => [
					'desc'    => esc_html__('Media to Show', 'photonic'),
					'type'    => 'select',
					'options' => Utilities::media_options(true, $photonic_instagram_media),
					'std'     => '',
					'hint'    => sprintf($this->default_under, '<em>Photonic &rarr; Settings &rarr; Instagram &rarr; Instagram Settings &rarr; Media to show</em>'),
				],
			]
		];
	}

	public function get_square_size_options(): array {
		return [];
	}

	public function get_random_size_options(): array {
		return [];
	}

	public function make_request($display_type, $for, $flattened_fields): array {
		require_once PHOTONIC_PATH . '/Platforms/Instagram.php';
		$module = \Photonic_Plugin\Platforms\Instagram::get_instance();
		$base_url = 'https://' . $this->api_base . '/me/media?fields=' . $module->field_list . '&access_token=' . $module->access_token;
		$response = wp_remote_request($base_url, ['sslverify' => PHOTONIC_SSL_VERIFY]);

		return [$response, [], $base_url];
	}

	/**
	 * Processes a response from Instagram to build it out into a gallery of thumbnails. Instagram only has L1 displays.
	 *
	 * @param $response
	 * @param $display_type
	 * @param $url
	 * @param array $pagination
	 * @return array
	 */
	public function process_response($response, $display_type, $url = null, &$pagination = []): array {
		$objects = [];
		$body = json_decode($response['body']);
		if (isset($body->data)) {
			$data = $body->data;
			foreach ($data as $photo) {
				if (isset($photo->media_type) && ('image' === strtolower($photo->media_type) || 'carousel_album' === strtolower($photo->media_type) || 'video' === strtolower($photo->media_type))) {
					if (('album-photo' === $display_type && 'carousel_album' === strtolower($photo->media_type)) || 'album-photo' !== $display_type) {
						$object = [];
						$link = $photo->permalink;
						$link = explode('/', $link);
						if (empty($link[count($link) - 1])) {
							$link = $link[count($link) - 2];
						}
						else {
							$link = $link[count($link) - 1];
						}
						$object['alt_id'] = $link;
						$object['id'] = $photo->id;
						if (isset($photo->caption)) {
							$object['title'] = esc_attr($photo->caption);
						}
						else {
							$object['title'] = '';
						}
						$object['thumbnail'] = empty($photo->thumbnail_url) ? $photo->media_url : $photo->thumbnail_url;
						$objects[] = $object;
					}
				}
			}

			if (isset($body->paging) && isset($body->paging->next) && 'single-photo' === $display_type) {
				$pagination['url'] = $body->paging->next; // add_query_arg(['max_id' => $body->pagination->next_max_id], remove_query_arg(['max_id'], $url));
			}
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
			if ('single-photo' === $display_type) {
				$short_code['media_id'] = sanitize_text_field($_POST['selected_data']);
			}
			elseif ('album-photo' === $display_type) {
				$short_code['carousel'] = sanitize_text_field($_POST['selected_data']);
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

		if (!empty($input->media_id)) {
			$deconstructed['display_type'] = 'single-photo';
			$deconstructed['selected_data'] = $input->media_id;
		}
		elseif (!empty($input->carousel)) {
			$deconstructed['display_type'] = 'album-photo';
			$deconstructed['selected_data'] = $input->carousel;
		}
		else {
			$deconstructed['display_type'] = 'multi-photo';
		}

		return $deconstructed;
	}
}
