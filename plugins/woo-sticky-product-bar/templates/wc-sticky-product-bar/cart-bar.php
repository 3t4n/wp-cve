<?php 
$class = "$id cart-page";
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
        <a href="<?php echo esc_url( wc_get_checkout_url() );?>" class="action-button">
            <?php esc_html_e( 'Proceed to checkout', 'woocommerce' ); ?>
        </a>
    </section>
<?php 
}
?>
	</div>
</div>