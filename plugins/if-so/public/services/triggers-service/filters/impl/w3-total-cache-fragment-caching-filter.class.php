<?php

namespace IfSo\PublicFace\Services\TriggersService\Filters;

require_once( plugin_dir_path ( __DIR__ ) . 'filter-base.class.php');

class W3TotalCacheFragmentCachingFilter extends FilterBase {
	private $is_removed;

	public function __construct() {
		$this->is_removed = false;
	}

	public function change_text($text) {
		if ( ! defined( 'W3TC' ) )
			return $text;

		$changedText = '';

		$changedText .= $this->get_start_no_cache_text();
		$changedText .= 'echo "' .$text . '";';
		$changedText .= $this->get_end_no_cache_text();

		return $changedText;
	}

	private function get_start_no_cache_text() {
		return '<!--mfunc ' . W3TC_DYNAMIC_SECURITY .' -->';
	}

	private function get_end_no_cache_text() {
		return '<!--/mfunc ' . W3TC_DYNAMIC_SECURITY .' -->';
	}

	public function before_apply() {}
	public function after_apply() {}
}