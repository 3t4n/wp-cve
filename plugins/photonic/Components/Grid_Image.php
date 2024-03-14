<?php

namespace Photonic_Plugin\Components;

use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Layouts\Core_Layout;
use Photonic_Plugin\Platforms\Base;

require_once 'Grid_Anchor.php';

class Grid_Image implements Printable {
	public $alt;
	public $src_attr = 'src';
	public $src = '';
	public $classes = [];
	public $dimensions = [];
	public $lazy_load;

	public function html(Base $module, Core_Layout $layout, $print = false): string { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$img_dim = '';
		if (!empty($this->dimensions) && !empty($this->dimensions['w']) && !empty($this->dimensions['h'])) {
			$img_dim = " width='{$this->dimensions['w']}' height='{$this->dimensions['h']}'";
		}
		$alt = esc_attr($this->alt);
		$classes = esc_attr(implode(' ', $this->classes));

		$ret = "<img alt='$alt' class='$classes' {$this->src_attr}='" . esc_url($this->src) . "' loading='{$this->lazy_load}' $img_dim />";
		if ($print) {
			echo wp_kses($ret, Photonic::$safe_tags);
		}
		return wp_kses($ret, Photonic::$safe_tags);
	}
}
