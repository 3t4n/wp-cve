<?php
/**
* Template for Design 1
*
* @package Popup Anything On Click
* @since 2.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
} ?>
<div class="paoc-cb-popup-body paoc-wrap paoc-popup paoc-modal-popup <?php echo esc_attr( $popup_classes ); ?>" id="paoc-popup-<?php echo esc_attr( $popup_id ).'-'.esc_attr( $unique ); ?>" <?php echo $popup_attr; // WPCS: XSS ok. ?>>
	<div class="paoc-popup-inr-wrap">
		<div class="paoc-padding-20 paoc-popup-con-bg">
			<div class="paoc-popup-inr">
				<?php if( $main_heading ) { ?>
					<div class="paoc-popup-margin paoc-popup-mheading"><?php echo wp_kses_post( $main_heading ); ?></div>
				<?php }

				if( $sub_heading ) { ?>
					<div class="paoc-popup-margin paoc-popup-sheading"><?php echo wp_kses_post( $sub_heading ); ?></div>
				<?php }

				if( $popup_content ) { ?>
					<div class="paoc-popup-margin paoc-popup-content"><?php echo $popup_content; // WPCS: XSS ok. ?></div>
				<?php }

				if( $secondary_content ) { ?>
					<div class="paoc-popup-margin paoc-secondary-con"><?php echo $secondary_content; // WPCS: XSS ok. ?></div>
				<?php }

				if( $cust_close_txt ) { ?>
					<div class="paoc-popup-margin paoc-cus-close-txt">
						<span class="paoc-popup-close"><?php echo esc_html( $cust_close_txt ); ?></span>
					</div>
				<?php }

				if( $security_note ) { ?>
					<div class="paoc-popup-margin paoc-popup-snote"><i class="fa fa-lock"></i> <span><?php echo esc_html( $security_note ); ?></span></div>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php if( ! empty( $show_credit ) ) { ?>
		<div class="paoc-credit-wrp">
			<span class="paoc-credit-inr-wrp">
				<a class="paoc-credit-link" href="<?php echo esc_url( $credit_link ); ?>" target="_blank" title="<?php esc_attr_e('Powered by', 'popup-anything-on-click'); ?> EssentialPlugin">
					<span class="paoc-credit-copyright-text"><?php esc_html_e('Powered by', 'popup-anything-on-click'); ?></span>
					<span class="paoc-credit-copyright-logo">
						<img src="<?php echo esc_url(POPUPAOC_URL); ?>assets/images/essentialplugin-logo-small.png" alt="EssentialPlugin" />
					</span>
				</a>
			</span>
		</div>
	<?php }

	if( ! $hide_close ) { ?>
		<a href="javascript:void(0);" class="paoc-close-popup paoc-popup-close">
			<svg viewBox="0 0 1792 1792"><path d="M1490 1322q0 40-28 68l-136 136q-28 28-68 28t-68-28l-294-294-294 294q-28 28-68 28t-68-28l-136-136q-28-28-28-68t28-68l294-294-294-294q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 294 294-294q28-28 68-28t68 28l136 136q28 28 28 68t-28 68l-294 294 294 294q28 28 28 68z"></path></svg>
		</a>
	<?php } ?>
</div>