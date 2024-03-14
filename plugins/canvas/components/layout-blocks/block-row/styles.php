<?php
/**
 * Row block styles for Frontend
 *
 * @var     $attributes - block attributes
 * @var     $class_name - block class name
 *
 * @package Canvas
 */

$breakpoints = cnvs_gutenberg()->get_breakpoints_data();
$break_columns = true;

foreach ( $breakpoints as $name => $data ) {
	$result = '';
	$suffix = '';

	if ( 'desktop' !== $name ) {
		$suffix = '_' . $name;
	}

	/**
	 * Break columns.
	 */
	if ( $suffix && $break_columns ) {
		$break_columns = false;
		$result .= '
			.' . esc_attr( $class_name ) . ' > .cnvs-block-row-inner {
				-ms-flex-wrap: wrap;
    			flex-wrap: wrap;
			}
		';
	}

	/**
	 * Gap.
	 */
	if ( isset( $attributes[ 'gap' . $suffix ] ) && $attributes[ 'gap' . $suffix ] ) {
		$gap = $attributes[ 'gap' . $suffix ];
		$result .= '
			.' . esc_attr( $class_name ) . ' > .cnvs-block-row-inner {
				margin-top: ' . esc_attr( - $gap / 2 ) . 'px;
				margin-left: ' . esc_attr( - $gap / 2 ) . 'px;
				margin-right: ' . esc_attr( - $gap / 2 ) . 'px;
			}
			.' . esc_attr( $class_name ) . ' > .cnvs-block-row-inner > .cnvs-block-column {
				padding-top: ' . esc_attr( $gap / 2 ) . 'px;
				padding-left: ' . esc_attr( $gap / 2 ) . 'px;
				padding-right: ' . esc_attr( $gap / 2 ) . 'px;
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
