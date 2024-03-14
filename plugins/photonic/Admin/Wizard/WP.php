<?php

namespace Photonic_Plugin\Admin\Wizard;

use Photonic_Plugin\Core\Utilities;

class WP extends Source {
	private static $instance;

	protected function __construct() {
		parent::__construct();
		$this->provider = 'wp';
		$this->allowed_image_sizes['wp'] = [
			'thumb_size' => Utilities::get_wp_image_sizes(false, true),
			'tile_size'  => Utilities::get_wp_image_sizes(true, true),
			'main_size'  => Utilities::get_wp_image_sizes(true, true),
		];
	}

	public static function get_instance(): WP {
		if (null === self::$instance) {
			self::$instance = new WP();
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
						'current-post' => esc_html__('Gallery attached to the current post', 'photonic'),
						'multi-photo'  => esc_html__('Photos from Media Library', 'photonic'),
					],
					'req'     => 1,
				],
			],
		];
	}

	public function get_screen_3(): array {
		return [];
	}

	public function get_screen_4(): array {
		return [];
	}

	public function get_screen_5(): array {
		return [
			'wp' => [
				'count'     => [
					'desc' => esc_html__('Number of photos to show', 'photonic'),
					'type' => 'text',
					'hint' => esc_html__('Numeric values only. Shows all photos by default.', 'photonic'),
				],
				'orderby'   => [
					'desc'    => esc_html__('Sort photos by', 'photonic'),
					'type'    => 'select',
					'options' => [
						''           => '',
						'menu_order' => esc_html__('Sequence in which they are provided', 'photonic'),
						'title'      => esc_html__('Title of the image in the media library', 'photonic'),
						'post_date'  => esc_html__('Creation date', 'photonic'),
						'rand'       => esc_html__('Random order', 'photonic'),
						'ID'         => esc_html__('Photo ID', 'photonic'),
					],
				],
				'order'     => [
					'desc'    => esc_html__('Sort order', 'photonic'),
					'type'    => 'select',
					'options' => [
						''     => '',
						'ASC'  => esc_html__('Ascending', 'photonic'),
						'DESC' => esc_html__('Descending', 'photonic'),
					],
				],
				'link'      => [
					'desc'    => esc_html__('Link photo to', 'photonic'),
					'hint'    => esc_html__('By default WP links photos to their respective attachment pages. You can change this behaviour.', 'photonic'),
					'type'    => 'select',
					'options' => [
						''     => esc_html__('Attachment page', 'photonic'),
						'file' => esc_html__('Image file', 'photonic'),
					],
				],
				'main_size' => [
					'desc'    => esc_html__('Main image size', 'photonic'),
					'type'    => 'select',
					'options' => $this->allowed_image_sizes['wp']['main_size'],
					'std'     => 'full',
				],
			]
		];
	}

	public function get_square_size_options(): array {
		return [
			'thumb_size' => [
				'desc'    => esc_html__('Thumbnail size', 'photonic'),
				'type'    => 'select',
				'options' => $this->allowed_image_sizes['wp']['thumb_size'],
				'std'     => 'thumbnail',
			],
		];
	}

	public function get_random_size_options(): array {
		return [
			'tile_size' => [
				'desc'    => esc_html__('Tile size', 'photonic'),
				'type'    => 'select',
				'options' => $this->allowed_image_sizes['wp']['tile_size'],
				'std'     => 'full',
			],
		];
	}

	public function make_request($display_type, $for, $flattened_fields): array {
		return [];
	}

	/**
	 * Blank for WP, since there is nothing to do.
	 *
	 * @param $response
	 * @param $display_type
	 * @param null $url
	 * @param array $pagination
	 * @return array
	 */
	public function process_response($response, $display_type, $url = null, &$pagination = []): array {
		return [];
	}

	/**
	 * @param $display_type
	 * @return array
	 */
	public function construct_shortcode_from_screen_selections($display_type): array {
		$short_code = [];

		if (check_ajax_referer('photonic-wizard-next-' . get_current_user_id())) {
			if ('current-post' === $display_type && !empty($_POST['post_id'])) {
				$short_code['id'] = sanitize_text_field($_POST['post_id']);
			}
			elseif (!empty($_POST['selected_data'])) {
				$short_code['ids'] = sanitize_text_field($_POST['selected_data']);
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

		if (empty($input->id) && empty($input->ids) && empty($input->include)) {
			$deconstructed['display_type'] = 'current-post';
		}
		elseif (!empty($input->ids) || !empty($input->include)) {
			$deconstructed['display_type'] = 'multi-photo';
			$deconstructed['selected_data'] = !empty($input->ids) ? $input->ids : $input->include;
		}

		return $deconstructed;
	}
}
