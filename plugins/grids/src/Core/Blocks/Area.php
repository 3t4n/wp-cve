<?php

namespace Grids\Core\Blocks;

use Grids\Core\Block as Block;
use Grids\Core\Utils\Filter as Filter;
use Grids\Core\Utils\CSS as CSS;

/* Check that we're running this file from the plugin. */
if ( ! defined( 'GRIDS' ) ) die( 'Forbidden' );

/**
 * Area block class.
 *
 * @since 1.0.0
 */
class Area extends Block {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$params = array(
			'render_callback' => array( $this, 'render' )
		);

		$params = parent::__construct( 'area', $params );

		register_block_type( 'grids/area', $params );
	}

	/**
	 * Render the section markup on frontend.
	 *
	 * @since 1.0.0
	 * @param array $attributes The section attributes.
	 * @param string $content The section content.
	 * @return string
	 */
	public function render( $attributes, $content ) {
		// $blockId = $attributes[ 'blockId' ];

		$class = Filter::apply( 'area_class', array() );
		$class[] = 'grids-area';
		// $class[] = 'grids-a-' . $blockId;

		if ( isset( $attributes[ 'className' ] ) ) {
			$class[] = $attributes[ 'className' ];
		}

		$vars = array();

		if ( ! isset( $attributes[ 'column' ][ 'start' ] ) ) {
			$attributes[ 'column' ][ 'start' ] = 1;
		}

		if ( ! isset( $attributes[ 'column' ][ 'end' ] ) ) {
			$attributes[ 'column' ][ 'end' ] = 1;
		}

		if ( ! isset( $attributes[ 'row' ][ 'start' ] ) ) {
			$attributes[ 'row' ][ 'start' ] = 1;
		}

		if ( ! isset( $attributes[ 'row' ][ 'end' ] ) ) {
			$attributes[ 'row' ][ 'end' ] = 1;
		}

		$vars[] = '--_ga-column:' . $attributes[ 'column' ][ 'start' ] . '/' . ($attributes[ 'column' ][ 'end' ] + 1);
		$vars[] = '--_ga-row:' . $attributes[ 'row' ][ 'start' ] . '/' . ($attributes[ 'row' ][ 'end' ] + 1);

		if ( isset( $attributes[ 'vertical_alignment' ] ) ) {
			if ( $attributes[ 'vertical_alignment' ] == 'center' ) {
				$vars[] = '--_ga-va:center';
			}
			else if ( $attributes[ 'vertical_alignment' ] == 'bottom' ) {
				$vars[] = '--_ga-va:flex-end';
			}
		}

		$breakpoints_config = \Grids\Core::instance()->get_config( 'breakpoints' );

		if ( $breakpoints_config ) {
			foreach ( $breakpoints_config as $breakpoint => $data ) {
				$margin         = CSS::spacing_rules( 'margin', $breakpoint, $attributes );
				$padding        = CSS::spacing_rules( 'padding', $breakpoint, $attributes );
				$bg_image_rules = CSS::background_image_rules( $breakpoint, $attributes );
				$bg_color_rules = CSS::background_color_rules( $breakpoint, $attributes );
				$zindex_rules   = CSS::zindex_rules( $breakpoint, $attributes );
				$display_rules  = CSS::display_rules( $breakpoint, $attributes, 'area' );

				if ( $margin ) {
					$vars[] = '--_ga-m-' . $breakpoint . ':' . implode( ' ', $margin );
				}

				if ( $padding ) {
					$vars[] = '--_ga-p-' . $breakpoint . ':' . implode( ' ', $padding );
				}

				$bg_rules = array_merge( $bg_color_rules, $bg_image_rules );

				if ( $bg_rules ) {
					$vars[] = '--_ga-bg-' . $breakpoint . ':' . implode( ' ', $bg_rules );
				}

				if ( $zindex_rules ) {
					$vars[] = '--_ga-zi-' . $breakpoint . ':' . implode( ' ', $zindex_rules );
				}

				if ( $display_rules ) {
					$vars[] = '--_ga-d-' . $breakpoint . ':' . implode( ' ', $display_rules );
				}

				$vars[] = sprintf(
					'--_ga-mw-%s:calc(100%% - %s - %s)',
					$breakpoint,
					$margin[3],
					$margin[1]
				);
			}
		}

		return sprintf(
			'<div class="%s" style="%s">%s</div>',
			esc_attr( implode( ' ', $class ) ),
			esc_attr( implode( ';', $vars ) ),
			$content
		);
	}
}
