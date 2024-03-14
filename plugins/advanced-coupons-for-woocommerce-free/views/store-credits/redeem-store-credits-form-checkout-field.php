<?php if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

<div id="<?php echo esc_attr( $args['id'] ); ?>" class="acfw-redeem-store-credit-form-field acfw-checkout-form-button-field <?php echo esc_attr( implode( ' ', $args['class'] ) ); ?>">
    <p class="form-row form-row-first <?php echo esc_attr( $class_prefix ); ?>-form-control-wrapper <?php echo esc_attr( $class_prefix ); ?>-col-left-half wfacp-input-form">
        <label for="coupon_code" class="<?php echo esc_attr( implode( ' ', $args['label_class'] ) ); ?>"><?php echo esc_attr( $args['label'] ); ?></label>
        <input type="text" class="input-text wc_input_price <?php echo esc_attr( implode( ' ', $args['input_class'] ) ); ?>" value="<?php echo esc_attr( $value ?? '' ); ?>" placeholder="<?php echo esc_html( $args['placeholder'] ); ?>" />
    </p>
    <p class="form-row form-row-last <?php echo esc_attr( $class_prefix ); ?>-col-left-half <?php echo esc_attr( $class_prefix ); ?>_coupon_btn_wrap">
        <label class="<?php echo esc_attr( $class_prefix ); ?>-form-control-label">&nbsp;</label>
        <button type="button" class="button alt"><?php echo esc_html( $args['button_text'] ); ?></button>
    </p>
</div>
