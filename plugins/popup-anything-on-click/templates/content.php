<?php
/**
 * Template for Popup Anything on Click Content
 *
 * @package Popup Anything on Click
 * @version 1.2.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// If `Simple Popup` is there
if( $popup_appear == 'simple_link' ) { ?>

	<a class="paoc-popup-click paoc-popup-cust-<?php echo esc_attr( $id ); ?> paoc-popup-<?php echo esc_attr( $popup_appear ); ?> paoc-popup-link" href="javascript:void(0);"><?php echo wp_kses_post( $link_text ); ?></a>

<?php } else if( $popup_appear == 'image' && $image_url ) { // If `Image Popup` is there ?>

	<div class="paoc-image-popup">
		<a class="paoc-popup-click paoc-popup-cust-<?php echo esc_attr( $id ); ?> paoc-popup-<?php echo esc_attr( $popup_appear ); ?> paoc-popup-image" href="javascript:void(0);"><img class="popupaoc-img" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" /></a>

		<?php if( ! empty( $show_img_title ) && $image_title ) { ?>

			<h4 class="paoc-image-title"><?php echo wp_kses_post( $image_title ); ?></h4>

		<?php } if( ! empty( $show_img_caption ) && $image_caption ) { ?>

			<div class="paoc-image-caption"><?php echo wp_kses_post( $image_caption ); ?></div>

		<?php } ?>
	</div>

<?php } else if( $popup_appear == 'button' ) { // If `Button Popup` is there ?>
	<a class="paoc-popup-click paoc-popup-cust-<?php echo esc_attr( $id ); ?> paoc-popup-<?php echo esc_attr( $popup_appear ); ?> paoc-popup-btn <?php echo esc_attr( $button_class ); ?>" href="javascript:void(0);"><?php echo wp_kses_post( $btn_text ); ?></a>
<?php }