<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div id="vi_wpb_popup_email" style="display: none">
    <div class="vi-wpb_overlay"></div>
    <div class="vi-wpb_popup">
        <div class="woopb-title"><?php esc_html_e( 'Send email to your friend', 'woocommerce-product-builder' ) ?></div>
        <span class="woopb-close"></span>

        <div class="content">
            <form action="" method="post" class="vi-wpb_send_email">
				<?php wp_nonce_field( 'woocommerce_product_builder_send_email' ); ?>
                <div class="woopb-rows">
                    <div class="woopb-row">
                        <label><?php esc_html_e( 'To', 'woocommerce-product-builder' ) ?></label>

                        <div class="woopb-field">
                            <input name="woopb_emailto_field" type="email" value="" class="woopb-text-field">
                        </div>
                    </div>
                    <div class="woopb-row">
                        <label><?php esc_html_e( 'Subject', 'woocommerce-product-builder' ) ?></label>

                        <div class="woopb-field">
                            <input name="woopb_subject_field" type="text" class="woopb-text-field">
                        </div>
                    </div>
                    <div class="woopb-row woopb-full-width">
                        <label><?php esc_html_e( 'Message', 'woocommerce-product-builder' ) ?></label>

                        <div class="woopb-field">
                            <textarea cols="30" rows="5" name="woopb_content_field" class="woopb-textarea-field"></textarea>
                        </div>
                    </div>
                    <div class="woopb-row">
                        <button class="vi-wpb_review_btn vi-wpb_addtocart_btn woopb-button">
							<?php esc_html_e( 'Send', 'woocommerce-product-builder' ) ?>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>