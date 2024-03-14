<?php

namespace IfSo\PublicFace\Services\TriggersService\Filters;

require_once( plugin_dir_path ( __DIR__ ) . 'filter-base.class.php');

class RichSnippetFilter extends FilterBase {
	private $is_removed;

	public function __construct() {
		$this->is_removed = false;
	}

	public function before_apply() {
		$this->is_removed = false;
		if ( has_filter('the_content', 'display_rich_snippet') ) {
			remove_filter('the_content','display_rich_snippet');
			$this->is_removed = true;
		}
	}

	public function after_apply() {
		if ( $this->is_removed ) {
			add_filter('the_content','display_rich_snippet');
			$this->is_removed = false;
		}
	}
}