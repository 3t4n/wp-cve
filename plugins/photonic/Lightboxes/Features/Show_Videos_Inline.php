<?php

namespace Photonic_Plugin\Lightboxes\Features;

use Photonic_Plugin\Components\Photo;
use Photonic_Plugin\Platforms\Base;

trait Show_Videos_Inline {
	public function get_video_id(Photo $photo, Base $module): string {
		return $module->provider . '-' . $module->gallery_index . '-' . $photo->id;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_video_markup(Photo $photo, Base $module, string $indent): string {
		$ret = '';
		if (!empty($photo->video)) {
			$video_id = $this->get_video_id($photo, $module);
			$width = !empty($photo->main_size) ? ('width="' . $photo->main_size['w'] . '"') : '';
			$height = !empty($photo->main_size) ? ('height="' . $photo->main_size['h'] . '"') : '';
			$poster = !empty($photo->main_image) ? ('poster="' . $photo->main_image . '"') : '';
			$ret .= $indent . "\t\t" . '<div class="photonic-html5-external" id="photonic-video-' . $video_id . '">' . "\n";
			$ret .= $indent . "\t\t\t" . '<video class="photonic" controls preload="none" ' . $width . ' ' . $height . ' ' . $poster . '>' . "\n";
			$ret .= $indent . "\t\t\t\t" . '<source src="' . $photo->video . '" type="' . ($photo->mime ?: 'video/mp4') . '">' . "\n";
			$ret .= $indent . "\t\t\t\t" . esc_html__('Your browser does not support HTML5 videos.', 'photonic') . "\n";
			$ret .= $indent . "\t\t\t" . '</video>' . "\n";
			$ret .= $indent . "\t\t" . '</div>' . "\n";
		}
		return $ret;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get_grid_link(Photo $photo, array $short_code, Base $module): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		if (!empty($photo->video)) {
			return esc_attr('#photonic-video-' . $this->get_video_id($photo, $module));
		}
		else {
			return esc_url($photo->main_image);
		}
	}
}
