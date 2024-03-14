<?php

namespace Photonic_Plugin\Components;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Platforms\Base;

require_once 'Header.php';
require_once 'Pagination.php';

class Photo_List implements Printable {
	public $photos = [];

	public $title_position;
	public $row_constraints = [];
	public $parent = 'stream';
	public $indent = "\t";
	public $short_code = [];

	/**
	 * @var Pagination $pagination
	 */
	public $pagination;

	public function __construct(array $short_code) {
		$this->short_code = $short_code;
		$this->pagination = new Pagination();
	}

	private function custom_sort(Base $module) {
		$this->photos = apply_filters('photonic_custom_sort_photos', $this->photos, $module->provider);
	}

	public function html(Base $module, Core_Layout $layout, $print = false): string {
		$ret = '';

		$this->custom_sort($module);

		if (is_a($layout, 'Photonic_Plugin\Layouts\Level_One_Gallery')) {
			$ret = $layout->generate_level_1_gallery($this, $this->short_code, $module);
		}

		if ($print) {
			echo wp_kses($ret, Photonic::$safe_tags);
		}
		return wp_kses($ret, Photonic::$safe_tags);
	}
}
