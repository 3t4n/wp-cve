<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<div class="wfacp_woocommerce_form_coupon wfacp-form-control-wrapper acfw-store-credits-redeem-form-field <?php echo esc_attr( $classes ); ?>">
    <div class="wfacp-coupon-section wfacp_custom_row_wrap clearfix">
        <div class="wfacp-coupon-page">
            <div class="woocommerce-form-coupon-toggle">
                <?php wc_print_notice( sprintf( '<a class="wfacp_showcoupon">%s</a>', $labels['toggle_text'] ), 'notice' ); ?>
            </div>
            <div class="wfacp-row wfacp_coupon_field_box" style="display:none">
                <p class="form-row wfacp-form-control-wrapper acfw-store-credit-user-balance">
                <?php
                    echo wp_kses_post(
                        sprintf(
                            /* Translators: %s User store credit balance */
                            $labels['balance_text'],
                            '<strong>' . wc_price( $user_balance ) . '</strong>'
                        )
                    );
                ?>
                </p>
                <p class="form-row wfacp-form-control-wrapper acfw-store-credit-instructions">
                    <?php echo wp_kses_post( $labels['instructions'] ); ?>
                </p>
                <?php
                    woocommerce_form_field(
                        'acfw_redeem_store_credit',
                        array(
                            'id'          => 'acfw_redeem_store_credit',
                            'type'        => 'acfw_redeem_store_credit',
                            'value'       => '',
                            'label'       => $labels['placeholder'],
                            'label_class' => array( 'wfacp-form-control-label' ),
                            'input_class' => array( 'wfacp-form-control' ),
                            'placeholder' => $labels['placeholder'],
                        )
                    );
                ?>
            </div>
        </div>
    </div>
    
</div>
