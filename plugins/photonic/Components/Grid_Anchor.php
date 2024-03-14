<?php

namespace Photonic_Plugin\Components;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Platforms\Base;

require_once 'Grid_Figure.php';

class Grid_Anchor implements Printable {
	public $href;
	public $id;
	public $classes = [];
	public $rel = [];
	public $title = '';
	public $data = [];
	public $figcaption = '';

	public $indent = '';

	/**
	 * @var Grid_Image $image
	 */
	public $image;

	public function html(Base $module, Core_Layout $layout, $print = false): string {  // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$data_pieces = array_map(
			function (string $key, string $value): string {
				return $key . '="' . $value . '"';
			},
			array_keys($this->data),
			array_values($this->data)
		);
		$data_attributes = implode(' ', $data_pieces); // includes target='_blank', if needed

		$classes = esc_attr(implode(' ', $this->classes));

		$rel = '';
		if (!empty($this->rel)) {
			$rel = "rel='" . esc_attr(implode(' ', $this->rel)) . "'";
		}

		$id = '';
		if (!empty($this->id)) {
			$id = "id='" . esc_attr($this->id) . "'";
		}

		$ret = "{$this->indent}\t\t<a href='{$this->href}' title='{$this->title}' $rel class='$classes' $id $data_attributes>\n";
		$ret .= $this->indent . "\t\t\t" . $this->image->html($module, $layout) . "\n";
		if (!empty($this->figcaption)) {
			$ret .= $this->indent . "\t\t\t" . $this->figcaption . "\n";
		}
		$ret .= "{$this->indent}\t\t</a>\n";

		if ($print) {
			echo wp_kses($ret, Photonic::$safe_tags);
		}
		return wp_kses($ret, Photonic::$safe_tags);
	}
}
