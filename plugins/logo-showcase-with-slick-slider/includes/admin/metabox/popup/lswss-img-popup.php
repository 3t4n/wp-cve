<?php
/**
 * Image Data Popup
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="lswssp-img-data-wrp lswssp-hide">
	<div class="lswssp-img-data-cnt">

		<div class="lswssp-img-cnt-block">
			<div class="lswssp-popup-close lswssp-popup-close-wrp"><img src="<?php echo LSWSS_URL; ?>assets/images/close.png" alt="<?php esc_attr_e('Close', 'logo-showcase-with-slick-slider'); ?>" title="<?php esc_html_e('Close', 'logo-showcase-with-slick-slider'); ?>" /></div>

			<div class="lswssp-popup-body-wrp">
			</div><!-- end .lswssp-popup-body-wrp -->
			
			<div class="lswssp-img-loader"><?php esc_html_e('Please Wait', 'logo-showcase-with-slick-slider'); ?> <span class="spinner"></span></div>
			<div class="lswssp-error lswssp-hide"></div>

		</div><!-- end .lswssp-img-cnt-block -->

	</div><!-- end .lswssp-img-data-cnt -->
</div><!-- end .lswssp-img-data-wrp -->
<div class="lswssp-popup-overlay"></div>