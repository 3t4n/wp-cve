<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class Strip extends Lightbox {
	protected function __construct() {
		$this->library = 'strip';
		parent::__construct();
	}

	/**
	 * @param $rel_id
	 * @param Base $module
	 * @return array
	 */
	public function get_gallery_attributes($rel_id, Base $module): array {
		global $photonic_lightbox_no_loop;
		$specific = [
			'data-strip-group' => 'lightbox-photonic-' . $module->provider . '-stream-' . (empty($rel_id) ? $module->gallery_index : $rel_id)
		];
		if (!empty($photonic_lightbox_no_loop)) {
			$specific['data-strip-group-options'] = "loop: false";
		}

		return [
			'class'    => $this->class,
			'rel'      => ['lightbox-photonic-' . $module->provider . '-stream-' . (empty($rel_id) ? $module->gallery_index : $rel_id)],
			'specific' => $specific,
		];
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		$out['data-strip-caption'] = esc_attr($photo_data['title']);
		return $out;
	}
}
