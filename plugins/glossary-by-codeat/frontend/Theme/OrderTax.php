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
class OrderTax extends Engine\Base {

	/**
	 * Initialize the class with all the hooks
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		if ( !isset( $this->settings[ 'order_terms' ] ) ) {
			return false;
		}

		\add_action( 'pre_get_posts', array( $this, 'order_glossary' ), 9999 );

		return true;
	}

	/**
	 * Order the glossary terms alphabetically
	 *
	 * @param object $query The query.
	 * @return bool|object
	 * @since 1.0.0
	 */
	public function order_glossary( $query ) {
		if ( \is_admin() ) {
			return $query;
		}

		if (
			( !$query->is_tax( 'glossary-cat' ) && !$query->is_post_type_archive( 'glossary' ) ) ||
			!$query->is_main_query() ) {
			return true;
		}

		$query->set( 'orderby', 'title' );
		$query->set( 'order', 'ASC' );

		return $query;
	}

}
