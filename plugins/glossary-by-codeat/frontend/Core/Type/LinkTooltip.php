<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 2.0+
 * @link      https://codeat.co
 */

namespace Glossary\Frontend\Core\Type;

use Glossary\Engine;

/**
 * Get the HTML about Tooltips
 */
class LinkTooltip extends Engine\Base {

	/**
	 * Initialize the class
	 *
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		return true;
	}

	/**
	 * Generate a link or the tooltip
	 *
	 * @param array $atts Parameters.
	 * @global object $post The post object.
	 * @return array
	 */
	public function html( array $atts ) {
		$class = ' class="glossary-only-link';

		if ( !empty( $atts[ 'class' ] ) ) {
			$class .= ' ' . $atts[ 'class' ];
		}

		$rel = '';

		if ( isset( $atts[ 'rel' ] ) ) {
			$rel = $atts[ 'rel' ];
		}

		$class .= '"';

		return array( 'before' => '<a href="' . $atts[ 'link' ] . '"' . $atts[ 'target' ] . $rel . $class . '>', 'value' => $atts[ 'replace' ], 'after' => '</a>' );
	}

}
