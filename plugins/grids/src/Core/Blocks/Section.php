<?php

namespace Grids\Core\Blocks;

use Grids\Core\Block as Block;
use Grids\Core\Utils\Filter as Filter;
use Grids\Core\Utils\CSS as CSS;

/* Check that we're running this file from the plugin. */
if ( ! defined( 'GRIDS' ) ) die( 'Forbidden' );

/**
 * Section block class.
 *
 * @since 1.0.0
 */
class Section extends Block {

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$params = array(
			'render_callback' => array( $this, 'render' )
		);

		$params = parent::__construct( 'section', $params );

		register_block_type( 'grids/section', $params );
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
		$class = Filter::apply( 'section_class', array() );
		$class[] = 'grids-section';
		// $class[] = 'grids-s-' . $attributes[ 'blockId' ];

		if ( isset( $attributes[ 'className' ] ) ) {
			$class[] = $attributes[ 'className' ];
		}

		$is_single_row = $attributes[ 'rows' ] === 1 || is_array( $attributes[ 'rows' ] ) && count( $attributes[ 'rows' ] ) === 1;

		if ( $is_single_row && ( ! isset( $attributes[ 'stretch' ] ) || $attributes[ 'stretch' ] === true ) ) {
			$class[] = 'grids-is-stretch';
		}

		if ( ! $is_single_row ) {
			$class[] = 'grids-is-advanced';
		}

		if ( isset( $attributes[ 'align' ] ) ) {
			$class[] = 'align' . $attributes[ 'align' ];
		}

		$attrs = '';

		if ( isset( $attributes[ 'anchorID' ] ) && ! empty( $attributes[ 'anchorID' ] ) ) {
			$attrs .= ' id="' . esc_attr( $attributes[ 'anchorID' ] ) . '"';
		}

		$vars = array();

		$breakpoints_config = \Grids\Core::instance()->get_config( 'breakpoints' );

		if ( $breakpoints_config ) {
			foreach ( $breakpoints_config as $breakpoint => $data ) {
				$margin          = CSS::spacing_rules( 'margin', $breakpoint, $attributes );
				$padding         = CSS::spacing_rules( 'padding', $breakpoint, $attributes );
				$gap         	 = CSS::gap_rules( $breakpoint, $attributes, 'data' );
				$bg_image_rules  = CSS::background_image_rules( $breakpoint, $attributes );
				$bg_color_rules  = CSS::background_color_rules( $breakpoint, $attributes );
				$bg_expand_rules = CSS::background_expand_rules( $breakpoint, $attributes );
				$zindex_rules    = CSS::zindex_rules( $breakpoint, $attributes );
				$display_rules   = CSS::display_rules( $breakpoint, $attributes, 'section' );

				if ( $gap ) {
					$vars[] = '--_gs-gap-' . $breakpoint . ':' . $gap[ 'y' ] . ' ' . $gap[ 'x' ];
				}

				if ( $margin ) {
					$vars[] = '--_gs-m-' . $breakpoint . ':' . implode( ' ', $margin );
				}

				if ( $padding ) {
					$vars[] = '--_gs-p-' . $breakpoint . ':' . implode( ' ', $padding );
				}

				$bg_rules = array_merge( $bg_color_rules, $bg_image_rules );

				if ( $bg_rules ) {
					$vars[] = '--_gs-bg-' . $breakpoint . ':' . implode( ' ', $bg_rules );
				}

				if ( $bg_expand_rules ) {
					$vars[] = '--_gs-bg-xp-' . $breakpoint . ':' . $bg_expand_rules;
				}

				if ( $zindex_rules ) {
					$vars[] = '--_gs-zi-' . $breakpoint . ':' . implode( ' ', $zindex_rules );
				}

				if ( $display_rules ) {
					$vars[] = '--_gs-d-' . $breakpoint . ':' . implode( ' ', $display_rules );
				}

				$vars[] = sprintf(
					'--_gs-mw-%s:calc(100%% - %s - %s)',
					$breakpoint,
					$margin[3],
					$margin[1]
				);

				$height_value = isset( $attributes[ 'height_' . $breakpoint ] ) ? $attributes[ 'height_' . $breakpoint ] : 'auto';
				$height_unit = isset( $attributes[ 'height_' . $breakpoint . '_unit' ] ) ? $attributes[ 'height_' . $breakpoint . '_unit' ] : 'px';
				$height_fix = isset( $attributes[ 'height_' . $breakpoint . '_fix' ] ) ? $attributes[ 'height_' . $breakpoint . '_fix' ] : false;

				if ( $height_value !== 'auto' ) {
					if ( ! $height_fix ) {
						$vars[] = '--_gs-min-height-' . $breakpoint . ':' . $height_value . $height_unit;
					}
					else {
						$vars[] = '--_gs-height-' . $breakpoint . ':' . $height_value . $height_unit;
					}
				}
			}
		}

		$vars[] = '--_gs-columns:' . $attributes[ 'columns' ];
		$vars[] = '--_gs-rows:' . $attributes[ 'rows' ];

		return sprintf(
			'<div %s class="%s" style="%s"><div class="grids-s-w_i">%s</div></div>',
			$attrs,
			esc_attr( implode( ' ', $class ) ),
			esc_attr( implode( ';', $vars ) ),
			$content
		);
	}
}
