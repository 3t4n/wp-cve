<?php
/**
 * Preloader.
 *
 * This template can be overridden by copying it to yourtheme/woo-product-slider/templates/preloader.php
 *
 * @link       https://shapedplugin.com/
 * @since      2.5.0
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Frontend/views
 */

if ( $preloader ) {
	$preloader_style = ( $preloader ) ? '' : 'display: none;';
	$preloader_image = apply_filters( 'sp_wps_product_preloader_img', SP_WPS_URL . 'Admin/assets/images/spinner.svg' );
	if ( ! empty( $preloader_image ) ) {
		?>
	<div class="wps-preloader" id="wps-preloader-<?php echo esc_attr( $post_id ); ?>" style="<?php echo esc_attr( $preloader_style ); ?>">
		<img src="<?php echo esc_url( $preloader_image ); ?>" alt="loader-image"/>
	</div>
		<?php
	}
}
