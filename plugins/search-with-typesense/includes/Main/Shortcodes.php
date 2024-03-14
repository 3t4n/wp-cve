<?php

namespace Codemanas\Typesense\Main;

use Codemanas\Typesense\Helpers\Templates;
use Codemanas\Typesense\Backend\Admin;

class Shortcodes {
	public static int $instant_search_count = 0;
	public static $instance = null;

	public static function getInstance() {
		return is_null( self::$instance ) ? self::$instance = new self() : self::$instance;
	}

	/**
	 * @return mixed
	 */
	public static function getCount() {
		return self::$instant_search_count;
	}

	/**
	 * Shortcodes constructor.
	 */
	public function __construct() {
		add_shortcode( 'cm_typesense_search', array( $this, 'render_instant_search' ) );
		add_shortcode( 'cm_typesense_autocomplete', array( $this, 'render_autocomplete' ) );
	}

	public function render_instant_search( $atts ) {
		$search_config_settings = Admin::get_search_config_settings();
		$enabled_post_types     = $search_config_settings['enabled_post_types'];
		self::$instant_search_count ++;

		$atts = shortcode_atts( array(
			'filter'           => 'show',
			'post_types'       => $enabled_post_types,
			'per_page'         => 3,
			'pagination'       => 'show',
			'sortby'           => 'show',
			'columns'          => 3,
			'placeholder'      => 'Search for...',
			'query_by'         => 'post_title,post_content',
			'sticky_first'     => 'no',
			'custom_class'     => '',
			'search_query'     => get_search_query(),
			'selected_filters' => 'hide',
			'stats'            => 'hide',
			'routing'            => 'disable'
		), $atts );

		$args              = $atts;
		$args['unique_id'] = 'cm_swt_instant_search_' . self::$instant_search_count;
		ob_start();

		Templates::getInstance()->include_file( 'instant-search.php', $args, false );

		wp_enqueue_script( 'cm-typesense-instant-search' );

		return ob_get_clean();
	}

	public function render_autocomplete( $atts ) {
		$search_config = Admin::get_search_config_settings();
		$placeholder   = $search_config['autocomplete_placeholder_text'] !== '' ? $search_config['autocomplete_placeholder_text'] : 'Search for';
		$atts          = shortcode_atts( [
			'placeholder' => $placeholder,
			'query_by'    => 'post_title,post_content',
		], $atts );
		ob_start();
		Templates::getInstance()->include_file( 'autocomplete.php', $atts, false );

		wp_enqueue_script( 'cm-typesense-autocomplete' );

		return ob_get_clean();
	}
}
