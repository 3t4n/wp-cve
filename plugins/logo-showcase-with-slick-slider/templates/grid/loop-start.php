<?php
/**
 * Loop Start - Grid Template
 *
 * /grid/loop-start.php
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="lswssp-wrap lswssp-logo-grid-wrap lswssp-post-data-wrap lswssp-tooltip-conf lswssp-<?php echo esc_attr( $atts['design'] ).' '.esc_attr( $atts['css_class'] ); ?>" id="lswssp-logo-showcase-<?php echo esc_attr( $atts['unique'] ); ?>">
	<div class="lswssp-logo-grid lswssp-logo-grid-inr-wrap lswssp-post-data-inr-wrap lswssp-clearfix">