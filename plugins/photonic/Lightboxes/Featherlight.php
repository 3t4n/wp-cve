<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Platforms\Base;

require_once 'Lightbox.php';

class Featherlight extends Lightbox {
	protected function __construct() {
		$this->library = 'featherlight';
		parent::__construct();
		$this->class = ['photonic-lb', 'photonic-featherlight'];
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out  = parent::get_photo_attributes($photo_data, $module);
		$mime = (!empty($photo['mime']) ? $photo['mime'] : 'video/mp4');
		if ('google' === $module->provider && empty($photo_data['video'])) {
			$out['data-featherlight-type'] = 'image';
		}
		elseif ('google' === $module->provider && !empty($photo_data['video'])) {
			$out['data-featherlight'] = esc_attr("<video class=\"photonic\" controls preload=\"none\"><source src=\"" . $photo_data['video'] . "\" type=\"" . $mime . "\">" . esc_html__('Your browser does not support HTML5 videos.', 'photonic') . "</video>");
			$out['data-featherlight-type'] = 'html';
		}
		elseif (!empty($photo_data['video'])) {
			$out['data-featherlight'] = esc_attr("<video class=\"photonic\" controls preload=\"none\"><source src=\"" . $photo_data['video'] . "\" type=\"" . $mime . "\">" . esc_html__('Your browser does not support HTML5 videos.', 'photonic') . "</video>");
			$out['data-featherlight-type'] = 'video';
		}

		return $out;
	}
}
