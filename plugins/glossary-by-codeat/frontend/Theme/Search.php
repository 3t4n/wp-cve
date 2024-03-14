<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Frontend\Theme;

use Glossary\Engine;

/**
 * Extends the native search
 */
class Search extends Engine\Base {

	/**
	 * Initialize the class with all the hooks
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		if ( !isset( $this->settings[ 'search' ] ) ) {
			return false;
		}

		\add_filter( 'pre_get_posts', array( $this, 'filter_search' ) );

		return true;
	}

	/**
	 * Add support for custom CPT on the search box
	 *
	 * @param object $query The query.
	 * @since 1.0.0
	 * @return object The query with changes.
	 */
	public function filter_search( $query ) {
		if ( $query->is_search && !\is_admin() ) {
			$post_types = $query->get( 'post_type' );

			if ( 'post' === $post_types ) {
				$post_types = array();
				$query->set( 'post_type', \array_push( $post_types, $this->default_parameters['post_type'] ) );
			}
		}

		return $query;
	}

}
