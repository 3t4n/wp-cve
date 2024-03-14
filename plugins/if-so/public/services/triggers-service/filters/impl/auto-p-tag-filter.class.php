<?php

namespace IfSo\PublicFace\Services\TriggersService\Filters;

require_once( plugin_dir_path ( __DIR__ ) . 'filter-base.class.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'services/plugin-settings-service/plugin-settings-service.class.php');

use IfSo\Services\PluginSettingsService;

class AutoPTagFilter extends FilterBase {
	private $is_removed_the_content_wpautop;
	private $is_removed_the_excerpt_wpautop;
	private $is_removed_gutenberg_wpautop;

	public function change_text($text) {
		$remove_auto_p_tag = PluginSettingsService\PluginSettingsService::get_instance()->removeAutoPTagOption->get();

		if ( !$remove_auto_p_tag ) {
			if (function_exists('wpautop')) {
				$text = wpautop($text);
			}
		}

		return $text;
	}

	public function __construct() {
		$this->is_removed_the_content_wpautop = false;
		$this->is_removed_the_excerpt_wpautop = false;
		$this->is_removed_gutenberg_wpautop = false;
	}

	public function before_apply() {
		$this->is_removed_the_content_wpautop = false;
		if ( has_filter('the_content', 'wpautop') ) {
			remove_filter( 'the_content', 'wpautop' );
			$this->is_removed_the_content_wpautop = true;
		}

		$this->is_removed_the_excerpt_wpautop = false;
		if ( has_filter('the_excerpt', 'wpautop') ) {
			remove_filter('the_excerpt', 'wpautop');
			$this->is_removed_the_excerpt_wpautop = true;
		}

		$this->is_removed_gutenberg_wpautop = false;
		if ( has_filter('the_content', 'gutenberg_wpautop') ) {
			remove_filter('the_content', 'gutenberg_wpautop', 8);
			$this->is_removed_gutenberg_wpautop = true;
		}
	}

	public function after_apply() {
		if ( $this->is_removed_the_content_wpautop ) {
			add_filter( 'the_content', 'wpautop' );
			$this->is_removed_the_content_wpautop = false;
		}

		if ( $this->is_removed_the_excerpt_wpautop ) {
			add_filter('the_excerpt', 'wpautop');
			$this->is_removed_the_excerpt_wpautop = false;
		}

		if ( $this->is_removed_gutenberg_wpautop ) {
			add_filter('the_content', 'gutenberg_wpautop', 8);
			$this->is_removed_gutenberg_wpautop = false;
		}
	}
}