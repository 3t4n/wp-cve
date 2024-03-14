<?php
/**
 * Column block styles for Frontend
 *
 * @var     $attributes - block attributes
 * @var     $class_name - block class name
 *
 * @package Canvas
 */

$breakpoints = cnvs_gutenberg()->get_breakpoints_data();

foreach ( $breakpoints as $name => $data ) {
	$result = '';
	$suffix = '';

	if ( 'desktop' !== $name ) {
		$suffix = '_' . $name;
	}

	/**
	 * Size.
	 */
	if ( isset( $attributes[ 'size' . $suffix ] ) ) {
		$size = $attributes[ 'size' . $suffix ];
		$result .= '
			.' . esc_attr( $class_name ) . ' {
				-ms-flex-preferred-size: ' . esc_attr( 100 * $size / 12 ) . '%;
				flex-basis: ' . esc_attr( 100 * $size / 12 ) . '%;
			}
		';
	}

	/**
	 * Order.
	 */
	if ( isset( $attributes[ 'order' . $suffix ] ) && $attributes[ 'order' . $suffix ] ) {
		$order = $attributes[ 'order' . $suffix ];
		$result .= '
			.' . esc_attr( $class_name ) . ' {
				-ms-flex-order: ' . esc_attr( $order ) . ';
				order: ' . esc_attr( $order ) . ';
			}
		';
	}

	/**
	 * Min Height.
	 */
	if ( isset( $attributes[ 'minHeight' . $suffix ] ) && '' !== $attributes[ 'minHeight' . $suffix ] ) {
		$minHeight = $attributes[ 'minHeight' . $suffix ];
		$result .= '
			.' . esc_attr( $class_name ) . ' > .cnvs-block-column-inner {
				min-height: ' . esc_attr( $minHeight ) . ';
			}
		';
	}

	/**
	 * Vertical Align.
	 */
	if ( isset( $attributes[ 'verticalAlign' . $suffix ] ) && $attributes[ 'verticalAlign' . $suffix ] ) {
		$verticalAlign = $attributes[ 'verticalAlign' . $suffix ];

		if ( 'top' === $verticalAlign ) {
			$verticalAlign = 'flex-start';
		} else if ( 'bottom' === $verticalAlign ) {
			$verticalAlign = 'flex-end';
		}

		$result .= '
			.' . esc_attr( $class_name ) . ',
			.' . esc_attr( $class_name ) . ' > .cnvs-block-column-inner {
				align-items: ' . esc_attr( $verticalAlign ) . ';
			}
		';
	}

	// add media query.
	if ( $suffix && $result ) {
		$result = '@media (max-width: ' . esc_attr( $data['width'] ) . 'px) { ' . $result . ' } ';
	}

	if ( $result ) {
		echo $result; // XSS Ok.
	}
}
