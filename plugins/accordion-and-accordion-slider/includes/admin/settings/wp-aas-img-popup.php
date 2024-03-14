<?php
/**
 * Image Data Popup
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
?>

<div class="wp-aas-img-data-wrp wp-aas-hide">
	<div class="wp-aas-img-data-cnt">

		<div class="wp-aas-img-cnt-block">
			<div class="wp-aas-popup-close wp-aas-popup-close-wrp"><img src="<?php echo esc_url(WP_AAS_URL); ?>assets/images/close.png" alt="<?php esc_html_e('Close (Esc)', 'accordion-and-accordion-slider'); ?>" title="<?php esc_html_e('Close (Esc)', 'accordion-and-accordion-slider'); ?>" /></div>

			<div class="wp-aas-popup-body-wrp">
			</div><!-- end .wp-aas-popup-body-wrp -->

			<div class="wp-aas-img-loader"><?php esc_html_e('Please Wait', 'accordion-and-accordion-slider'); ?> <span class="spinner"></span></div>

		</div><!-- end .wp-aas-img-cnt-block -->

	</div><!-- end .wp-aas-img-data-cnt -->
</div><!-- end .wp-aas-img-data-wrp -->
<div class="wp-aas-popup-overlay"></div>