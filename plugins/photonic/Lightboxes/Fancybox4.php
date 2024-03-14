<?php

namespace Photonic_Plugin\Lightboxes;

use Photonic_Plugin\Components\Photo;
use Photonic_Plugin\Platforms\Base;
use Photonic_Plugin\Lightboxes\Features\Show_Videos_Inline;

require_once 'Lightbox.php';
require_once 'Features/Show_Videos_Inline.php';

class Fancybox4 extends Lightbox {
	use Show_Videos_Inline {
		get_video_markup as get_inline_video_markup;
		get_grid_link as get_inline_video_grid_link;
	}

	protected function __construct() {
		$this->library = 'fancybox4';
		parent::__construct();
		$this->class = ['photonic-lb', 'photonic-fancybox', 'fancybox'];
	}

	public function get_photo_attributes(array $photo_data, Base $module): array {
		$out = parent::get_photo_attributes($photo_data, $module);
		if (!empty($photo_data['video'])) {
			$out['data-type'] = 'html5video';
		}

		if (in_array($module->provider, ['google', 'flickr'], true)) {
			if (!empty($photo_data['video'])) {
				$out['data-html5-href'] = $photo_data['video'];
			}
		}
		return $out;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_video_markup(Photo $photo, Base $module, string $indent): string {
		if (in_array($module->provider, ['flickr', 'google'], true)) {
			return $this->get_inline_video_markup($photo, $module, $indent);
		}
		else {
			return parent::get_video_markup($photo, $module, $indent);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_grid_link(Photo $photo, array $short_code, Base $module): string {
		if (!empty($photo->video) && in_array($module->provider, ['flickr', 'google'], true)) {
			return $this->get_inline_video_grid_link($photo, $short_code, $module);
		}
		else {
			return parent::get_grid_link($photo, $short_code, $module);
		}
	}
}
