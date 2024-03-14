<?php
	class MarkNewPosts_MarkerPlacement {
		const TITLE_BEFORE = 0;
		const TITLE_AFTER = 1;
		const TITLE_BOTH = 2;
	}

	class MarkNewPosts_MarkerType {
		const NONE = 0;
		const CIRCLE = 1;
		const TEXT = 2;
		const IMAGE_DEFAULT = 3;
		const FLAG = 5;
		const TEXT_NEW = 6;
	}

	class MarkNewPosts_MarkAfter {
		const OPENING_POST = 0; // a post gets marked after it's opened
		const OPENING_LIST = 1; // a post gets marked after it's displayed in the post list
		const OPENING_BLOG = 2; // all posts get marked after any blog page is opened
	}

	class MarkNewPosts_Options {
		public $marker_placement = MarkNewPosts_MarkerPlacement::TITLE_AFTER;
		public $marker_type = MarkNewPosts_MarkerType::TEXT_NEW;
		public $mark_title_bg = true;
		public $mark_bg_color = '#f0fff0';
		public $mark_after = MarkNewPosts_MarkAfter::OPENING_LIST;
		public $post_stays_new_days = 0;
		public $all_new_for_new_visitor = false;
		public $disable_for_custom_posts = false;
		public $allow_outside_the_loop = false;
		public $use_js = false;
	}
?>