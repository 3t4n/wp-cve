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

namespace Glossary\Frontend\Core;

use Glossary\Engine;

/**
 * Engine system that add the tooltips
 */
class HTML_Type_Injector extends Engine\Base {

	/**
	 * Tooltip attributes
	 *
	 * @var array
	 */
	private $atts = array();

	/**
	 * Initialize the class
	 *
	 * @return bool
	 */
	public function initialize() {
		parent::initialize();

		$is_page = new Engine\Is_Methods;

		if ( !$is_page->is_amp() ) {
			return false;
		}

		$this->settings[ 'tooltip' ] = 'link';

		return true;
	}

	/**
	 * Generate a link or the tooltip
	 *
	 * @param array $atts Parameters.
	 * @global object $post The post object.
	 * @return string
	 */
	public function html( array $atts ) {
		$this->set_atts( $atts );
		$html = array( 'before' => '', 'value' => '', 'after' => '' );

		if ( \is_type_inject_set_as( 'link' ) || \is_type_inject_set_as( 'link-tooltip' ) ) {
			$type = new Type\LinkTooltip;
			$type->initialize();
			$html = $type->html( $this->atts );
		}

		if ( \is_type_inject_set_as( 'tooltip' ) || \is_type_inject_set_as( 'link-tooltip' ) ) {
			$type = new Type\Tooltip;
			$type->initialize();
			$temp = $html[ 'before' ] . $html[ 'value' ] . $html[ 'after' ];
			$html = $type->html( $this->atts );

			if ( !empty( $temp ) ) {
				$html[ 'value' ] = $temp;
			}
		}

		if ( \is_type_inject_set_as( 'footnote' ) ) {
			$type = new Type\Footnote;
			$type->initialize();
			$html = $type->html( $this->atts );
		}

		return $html[ 'before' ] . $html[ 'value' ] . $html[ 'after' ];
	}

	/**
	 * Set atts by other atts
	 *
	 * @param array $atts The attribute of tooltip.
	 * @return array
	 */
	public function set_atts( array $atts ) {
		if ( !empty( $atts[ 'link' ] ) ) {
			if ( !empty( $this->settings[ 'open_new_window' ] ) || !empty( $atts[ 'target' ] ) ) {
				$atts[ 'target' ] = ' target="_blank"';
			}

			$atts[ 'rel' ] = 'rel="';

			if ( !empty( $atts[ 'nofollow' ] ) ) {
				$atts[ 'rel' ] .= 'nofollow ';
			}

			if ( !empty( $atts[ 'sponsored' ] ) ) {
				$atts[ 'rel' ] .= 'sponsored';
			}

			$atts[ 'rel' ] .= '"';

			if ( $atts[ 'rel' ] === 'rel=""' ) {
				$atts[ 'rel' ] = '';
			}
		}

		$this->atts            = $atts;
		$this->atts[ 'class' ] = $this->set_class();
		$atts[ 'class' ]       = $this->atts[ 'class' ];

		return $atts;
	}

	/**
	 * Return the class of tooltip based on atts and settings
	 *
	 * @return string
	 */
	public function set_class() {
		$class = '';

		if ( !empty( $this->settings[ 'external_icon' ] ) ) {
			if ( 0 !== \strpos( $this->atts[ 'link' ], \get_site_url() ) ) {
				$class = $this->default_parameters[ 'css_class_prefix' ] . '-external-link ';
			}
		}

		return $class;
	}

}
