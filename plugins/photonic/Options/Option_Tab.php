<?php

namespace Photonic_Plugin\Options;

abstract class Option_Tab {
	protected $options;

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	public function title_styles(): array {
		return [
			'regular'            => "<img src='" . trailingslashit(PHOTONIC_URL) . 'include/images/title-regular.png' . "' />Normal title display using the HTML \"title\" attribute",
			'below'              => "<img src='" . trailingslashit(PHOTONIC_URL) . 'include/images/title-below.png' . "' />Below the thumbnail (Doesn't work for Random Justified Gallery and Mosaic Layout)",
			'tooltip'            => "<img src='" . trailingslashit(PHOTONIC_URL) . 'include/images/title-jq-tooltip.png' . "' />Using a JavaScript tooltip",
			'hover-slideup-show' => "<img src='" . trailingslashit(PHOTONIC_URL) . 'include/images/title-slideup.png' . "' />Slide up from bottom upon hover",
			'slideup-stick'      => "<img src='" . trailingslashit(PHOTONIC_URL) . 'include/images/title-slideup.png' . "' />Cover the lower portion always",
			'none'               => 'No title'
		];
	}

	public function selection_range($min, $max): array {
		$ret = [];
		for ($i = $min; $i <= $max; $i++) {
			$ret[$i] = $i;
		}
		return $ret;
	}

	public function get_layout_engine_options(string $option_id, string $option_grouping): array {
		return [
			'name'     => "Layout processing engine",
			'desc'     => "When possible, Photonic tries to use CSS to build the layouts. 
					This has the advantage of being fast and can work well with lazy-loading and AJAX-based plugins, particularly if the loading mode (<em>Photonic &rarr; Settings &rarr; Generic Options &rarr; Advanced &rarr; Loading Mode</em>) is PHP. 
					<br/><br/>The downside is that CSS-based rendering is occasionally incorrect, particularly if the source has incorrect sizes. 
					If this is a frequent issue, you can default to a JS-generated layout (which is always more accurate). This can be managed individually for each gallery.
					<br/><br/>In case some of the images in a gallery have missing size metadata, the JS mode will be used, regardless of this selection.
					<br/><br/>Pick your default processor:",
			'id'       => $option_id,
			'grouping' => $option_grouping,
			'type'     => 'select',
			'options'  => [
				'css' => 'Use CSS unless overridden by a gallery individually',
				'js'  => 'Use JS unless overridden by a gallery individually',
			]
		];
	}
}
