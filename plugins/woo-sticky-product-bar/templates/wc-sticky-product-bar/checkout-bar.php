<?php 
$class = "$id checkout-page";
if ($options['displayTotal'] == 'yes') {
    $class .= " display-total";
}
if ($options['displayButton'] == 'yes') {
    $class .= " display-button";
}
?>
<div class="<?php echo $class; ?>">
	<div class="<?php echo $id . '-container';?>">
<?php 
if ($options['displayTerms'] == 'yes' && wc_terms_and_conditions_checkbox_enabled()) {
?>
    <section class="terms">
		<p class="form-row validate-required terms-checkbox-wrapper">
			<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
				<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); // WPCS: input var ok, csrf ok. ?> id="terms-checkbox" />
				<span class="woocommerce-terms-and-conditions-checkbox-text"><?php wc_terms_and_conditions_checkbox_text(); ?></span>&nbsp;<span class="required">*</span>
			</label>
			<input type="hidden" name="terms-field" value="1" />
		</p>
    </section>
<?php 
}
if ($options['displayTotal'] == 'yes') {
?>
    <section class="total">
        <span class="total-label"><?php esc_html_e("Total:", $id) ?></span>
        <?php wc_cart_totals_order_total_html(); ?>
    </section>
<?php 
}
if ($options['displayButton'] == 'yes') {
?>	
    <section class="button">
        <a href="#" class="action-button">
           <?php echo apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) ); ?>
        </a>
    </section>
<?php 
}
?>
	</div>
</div>