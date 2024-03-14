<?php
/**
 * Slider title.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/slider-title.php
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend/views
 */

if ( $slider_title ) {
	?>
	<h2 class="sp-woo-product-slider-section-title"> <?php echo wp_kses_post( $main_section_title ); ?> </h2>
	<?php
}
