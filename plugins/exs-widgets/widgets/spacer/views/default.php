<?php
/**
 * Widget Theme meta view file
 *
 * @package ExS
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$exs_center_class = ( ! empty( $exs_text_center ) ) ? 'text-center' : '';
$exs_pt_class     = ( ! empty( $exs_pt ) ) ? 'mt-' . $exs_pt : '';
$exs_pb_class     = ( ! empty( $exs_pb ) ) ? 'mb-' . $exs_pb : '';


echo wp_kses_post( str_replace( 'class="', 'class="widget-fullwidth ', $exs_args['before_widget'] ) );
echo '<div class="widget-theme-spacer ' . esc_attr( $exs_css_class . ' ' . $exs_center_class . ' ' . $exs_pt_class . ' ' . $exs_pb_class ) . '">';
if ( $exs_title ) {
	echo wp_kses_post( $exs_args['before_title'] . $exs_title . $exs_args['after_title'] );
}
if ( $exs_sub_title ) {
	echo '<p class="sub-title">' . wp_kses_post( $exs_sub_title ) . '</p><!-- .sub-title-->';
}

if ( 'hr' === $exs_layout ) {
	echo '<hr>';
}
?>
	</div><!-- .widget-theme-spacer -->
<?php
echo wp_kses_post( $exs_args['after_widget'] );
