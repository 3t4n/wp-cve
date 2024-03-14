<?php

/**
 * The Exclude Pages From Menu Public defines all functionality of plugin
 * for the site front
 *
 * This class defines the meta box used to display the post meta data and registers
 * the style sheet responsible for styling the content of the meta box.
 *
 * @package EPFM
 * @since    1.0
 */
class Exclude_Pages_From_Menu_Public {

	/**
	 * Global plugin option.
	 */
	 public $options;

	/**
	 * A reference to the version of the plugin that is passed to this class from the caller.
	 *
	 * @access private
	 * @var    string    $version    The current version of the plugin.
	 */
	private $version;

	/**
	 * Initializes this class and stores the current version of this plugin.
	 *
	 * @param    string    $version    The current version of this plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
		$this->options = get_option( 'exclude_pages_from_menu' );
	}

	/* Excludes pages from the pages menu in the front end of site */
	function exclude_pages_from_menu_pages( $args ){

		$exclude_pages = get_pages( array( 'meta_value' => 'epfm_meta_box_value' ) );
		$exclude_pages_ids = '';

		foreach ( $exclude_pages as $exclude_page ) {

			if ( $exclude_pages_ids != '' ) {
				$exclude_pages_ids .= ', ';
			}

			$exclude_pages_ids .= $exclude_page->ID;
		}

		if ( ! empty( $args['exclude'] ) ) {
			$args['exclude'] .= ',';
		} else {
			$args['exclude'] = '';
		}

		$args['exclude'] .= $exclude_pages_ids;

		return $args;
	}

	/* Excludes pages from the navigation bar menu in the front end of site */
	function exclude_pages_from_menu_items( $items, $menu, $args ){
		$exclude_pages = get_pages( array( 'meta_value' => 'epfm_meta_box_value' ) );
		$exclude_pages_ids = array();

		foreach ( $exclude_pages as $exclude_page ) {
			array_push( $exclude_pages_ids, $exclude_page->ID );
		}

		if ( ! empty( $exclude_pages_ids ) ) {
			foreach ( $items as $key => $item ) {
				if ( in_array( $item->object_id , $exclude_pages_ids ) ) {
					unset( $items[ $key ] );
				}
			}
		}
		return $items;
	}
}