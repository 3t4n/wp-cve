<?php
/**
 * Loop Start - Slider Template
 *
 * /slider/loop-start.php
 * 
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div class="lswssp-wrap lswssp-logo-showcase lswssp-logo-carousel-wrap lswssp-post-data-wrap lswssp-<?php echo esc_attr( $atts['design'] ).' '.esc_attr(  $atts['css_class'] ); ?>">
	<div class="lswssp-logo-carousel lswssp-logo-carousel-inr-wrap lswssp-post-data-inr-wrap lswssp-tooltip-conf lswssp-clearfix" id="lswssp-logo-carousel-<?php echo esc_attr( $atts['unique'] ); ?>" data-conf="<?php echo htmlspecialchars( json_encode( $atts ) ); ?>">