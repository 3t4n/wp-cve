<?php

namespace Photonic_Plugin\Components;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Platforms\Base;

require_once 'Grid_Anchor.php';

class Grid_Figure implements Printable {
	public $id;
	public $classes = [];
	public $styles = [];
	public $video_markup = '';
	public $prompter_markup = '';
	public $indent = '';

	/**
	 * @var Grid_Anchor $anchor
	 */
	public $anchor;

	public function html(Base $module, Core_Layout $layout, $print = false): string {
		$classes = esc_attr(implode(' ', $this->classes));
		$style = '';
		if (!empty($this->styles)) {
			$styles = array_map(
				function (string $key, string $value): string {
					return $key . ': ' . $value;
				},
				array_keys($this->styles),
				array_values($this->styles)
			);

			$style = "style='" . implode('; ', $styles) . "'";
		}

		$id = '';
		if (!empty($this->id)) {
			$id = "id='" . esc_attr($this->id) . "'";
		}

		$ret = "{$this->indent}\t<figure class='$classes' $id $style>\n";
		$ret .= $this->video_markup;
		$ret .= $this->anchor->html($module, $layout);
		$ret .= $this->prompter_markup;
		$ret .= $this->indent . "\t</figure>\n";
		if ($print) {
			echo wp_kses($ret, Photonic::$safe_tags);
		}
		return wp_kses($ret, Photonic::$safe_tags);
	}
}
