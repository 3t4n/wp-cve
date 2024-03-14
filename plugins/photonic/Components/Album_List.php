<?php

namespace Photonic_Plugin\Components;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Platforms\Base;

require_once 'Pagination.php';

class Album_List implements Printable {
	public $albums = [];

	public $title_position;
	public $row_constraints = [];
	public $indent = '';
	public $short_code = [];

	/**
	 * @var Pagination $pagination
	 */
	public $pagination;

	public $type;
	public $singular_type;
	public $level_1_count_display;
	public $album_opens_gallery = false;

	public $gallery_attributes = [];

	public function __construct(array $short_code) {
		$this->pagination = new Pagination();
		$this->short_code = $short_code;

		$this->gallery_attributes['photo-count'] = empty($short_code['photo_count']) ? $short_code['count'] : $short_code['photo_count'];

		$data = [
			'photo-more'         => 'photo_more',
			'overlay-size'       => 'overlay_size',
			'overlay-crop'       => 'overlay_crop',
			'overlay-video-size' => 'overlay_video_size',
			'thumb-size'         => 'thumb_size',
		];

		foreach ($data as $attr => $value) {
			if (isset($short_code[$value])) {
				$this->gallery_attributes[$attr] = $short_code[$value];
			}
		}
	}

	private function custom_sort(Base $module) {
		$this->albums = apply_filters('photonic_custom_sort_albums', $this->albums, $module->provider);
	}

	/**
	 * {@inheritDoc}
	 */
	public function html(Base $module, Core_Layout $layout, $print = false): string {
		$ret = '';

		$this->custom_sort($module);

		if (is_a($layout, 'Photonic_Plugin\Layouts\Level_Two_Gallery')) {
			$ret = $layout->generate_level_2_gallery($this, $this->short_code, $module);
		}

		if ($print) {
			echo wp_kses($ret, Photonic::$safe_tags);
		}

		return wp_kses($ret, Photonic::$safe_tags);
	}
}
