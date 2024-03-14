<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'acfw_before_display_edit_bogo_panel', $bogo_deals ); ?>

<div id="<?php echo esc_attr( $panel_id ); ?>" 
    class="<?php echo esc_attr( implode( ' ', $classnames ) ); ?>"
    data-bogo_deals="<?php echo esc_attr( wp_json_encode( $bogo_deals ) ); ?>"
    data-nonce="<?php echo esc_attr( wp_create_nonce( 'acfw_save_bogo_deals' ) ); ?>"
    style="display:none;"
>
    <div class="acfw-help-link" data-module="bogo-deals"></div>
    <div class="bogo-info">
        <h3><?php esc_html_e( 'Buy X Get X (BOGO) Deal', 'advanced-coupons-for-woocommerce-free' ); ?></h3>
        <p><?php esc_html_e( 'BOGO (Buy One, Get One) style deals let you define a "Buy" Type which says what activates the deal, and a "Get" type that says what the customer will get when fulfilling the deal.', 'advanced-coupons-for-woocommerce-free' ); ?></p>
        <p><?php esc_html_e( 'The "Buy" products must be present in the cart before the BOGO deal becomes eligible, they are not part of the "Get" products. The customer must add the "Get" products to the cart to fully satisfy the BOGO coupon and get the discount. If multiple products are eligible, the cheapest product is always given the discount first.', 'advanced-coupons-for-woocommerce-free' ); ?></p>
    </div>

    <div class="bogo-conditions-wrap">

        <div class="bogo-type-selector">
            <label for="bogo-condition-type"><?php esc_html_e( 'Customer Buys:', 'advanced-coupons-for-woocommerce-free' ); ?></label>
            <select id="bogo-condition-type" data-block="conditions">
                <?php foreach ( $trigger_type_options as $option => $label ) : ?>
                    <option value="<?php echo esc_attr( $option ); ?>" <?php selected( $cond_type, $option ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="bogo-conditions-block bogo-block">
        </div>

    </div>

    <div class="bogo-product-deals-wrap">

        <div class="bogo-type-selector">
            <label for="bogo-deals-type"><?php esc_html_e( 'Customer Gets:', 'advanced-coupons-for-woocommerce-free' ); ?></label>
            <select id="bogo-deals-type" data-block="deals">
                <?php foreach ( $apply_type_options as $option => $label ) : ?>
                    <option value="<?php echo esc_attr( $option ); ?>" <?php selected( $deals_type, $option ); ?>>
                        <?php echo esc_html( $label ); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="bogo-product-deals-block bogo-block">
        </div>
    </div>

    <div class="bogo-type-block additional-settings-block">
        <h2><?php esc_html_e( 'Additional Settings', 'advanced-coupons-for-woocommerce-free' ); ?></h2>

        <?php do_action( 'acfw_bogo_before_additional_settings', $bogo_deals, $coupon ); ?>

        <div class="bogo-type-form bogo-settings-field">
            <label><?php esc_html_e( 'How should the BOGO deal be applied?', 'advanced-coupons-for-woocommerce-free' ); ?></label>
            <div class="radio-group-wrap">
                <label>
                    <input type="radio" name="bogo_type" value="once" <?php checked( $type, 'once' ); ?>>
                    <span><?php esc_html_e( 'Only once', 'advanced-coupons-for-woocommerce-free' ); ?></span>
                    <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Only apply the coupon once when one of the conditions is met (even multiple times)', 'advanced-coupons-for-woocommerce-free' ); ?>"></span>
                </label>
                <label>
                    <input type="radio" name="bogo_type" value="repeat" <?php checked( $type, 'repeat' ); ?>>
                    <span><?php esc_html_e( 'Repeatedly', 'advanced-coupons-for-woocommerce-free' ); ?></span>
                    <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Everytime the condition is met, apply the coupon repeatedly.', 'advanced-coupons-for-woocommerce-free' ); ?>"></span>
                </label>
            </div>
            <div class="repeat-limit-field-wrap">
                <label><?php esc_html_e( 'Repeated BOGO maximum limit', 'advanced-coupons-for-woocommerce-free' ); ?></label>
                <input id="bogo-repeat-limit" type="number" name="bogo_repeat_limit" value="<?php echo esc_attr( $repeat_limit > 0 ? $repeat_limit : '' ); ?>" min="0" step="1" placeholder="<?php esc_attr_e( 'No limit', 'advanced-coupons-for-woocommerce-free' ); ?>">
            </div>
        </div>

        <div class="notice-option">
            <div class="bogo-settings-field">
                <label><?php esc_html_e( 'Notice shown when BOGO deal is eligible, but "Get" products are not in the cart:', 'advanced-coupons-for-woocommerce-free' ); ?></label>
                <textarea class="text-input" name="acfw_bogo_notice_message_text" placeholder="<?php echo esc_attr( $global_notice_message ); ?>"><?php echo wp_kses_post( $notice_message ); ?></textarea>
                <span class="woocommerce-help-tip" data-tip="<?php esc_attr_e( 'Custom variables available: {acfw_bogo_remaining_deals_quantity} to display the count of product deals that can be added to the cart, and {acfw_bogo_coupon_code} for displaying the coupon code that offered the deal.', 'advanced-coupons-for-woocommerce-free' ); ?>"></span>
            </div>
            <div class="bogo-settings-field">
                <label><?php esc_html_e( 'Button Text:', 'advanced-coupons-for-woocommerce-free' ); ?></label>
                <input class="text-input" type="text" name="acfw_bogo_notice_button_text" placeholder="<?php echo esc_attr( $global_notice_btn_text ); ?>" value="<?php echo esc_html( $notice_btn_text ); ?>">
            </div>
            <div class="bogo-settings-field">
                <label><?php esc_html_e( 'Button URL:', 'advanced-coupons-for-woocommerce-free' ); ?></label>
                <input class="text-input" type="url" name="acfw_bogo_notice_button_url" placeholder="<?php echo esc_attr( $global_notice_btn_url ); ?>" value="<?php echo esc_url_raw( $notice_btn_url ); ?>">
            </div>
            <div class="bogo-settings-field">
                <label><?php esc_html_e( 'Notice Type:', 'advanced-coupons-for-woocommerce-free' ); ?></label>
                <select name="acfw_bogo_notice_type">
                    <option value="global" <?php selected( 'global', $notice_type ); ?>>
                    <?php
                    echo esc_html(
                        sprintf(
                        /* Translators: %s: Global notice type value */
                            __( 'Global setting (%s)', 'advanced-coupons-for-woocommerce-free' ),
                            $globa_notice_type_label
                        )
                    );
                    ?>
                    </option>
                    <?php foreach ( $notice_types as $key => $label ) : ?>
                        <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $notice_type ); ?>><?php echo esc_html( $label ); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php do_action( 'acfw_bogo_after_additional_settings', $bogo_deals, $coupon ); ?>

    </div>

    <div class="bogo-actions-block">
        <button id="save-bogo-deals" class="button-primary" type="button" disabled><?php esc_html_e( 'Save BOGO Deals', 'advanced-coupons-for-woocommerce-free' ); ?></button>
        <button id="clear-bogo-deals" class="button" type="button"
            data-prompt="<?php esc_attr_e( 'Are you sure you want to do this?', 'advanced-coupons-for-woocommerce-free' ); ?>"
            data-nonce="<?php echo esc_attr( wp_create_nonce( 'acfw_clear_bogo_deals' ) ); ?>"
            <?php echo empty( $bogo_deals ) ? 'disabled' : ''; ?>>
            <?php esc_html_e( 'Clear BOGO Deals', 'advanced-coupons-for-woocommerce-free' ); ?>
        </button>
    </div>

    <div class="acfw-overlay" style="background-image:url(<?php echo esc_attr( $spinner_img ); ?>)"></div>

</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

    $('#acfw_bogo_deals').on( 'mouseenter' , '.notice-option' , function() {
        $('#tiptip_content').css( 'max-width' , '250px' );
    } );

    $('#acfw_bogo_deals').on( 'mouseleave' , '.notice-option' , function() {
        $('#tiptip_content').css( 'max-width' , '150px' );
    } );
});
</script>

<?php do_action( 'acfw_after_display_edit_bogo_panel', $bogo_deals ); ?>
