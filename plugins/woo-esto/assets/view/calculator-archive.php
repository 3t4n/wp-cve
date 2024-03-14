<?php
if ( $calc_text ) {
    $text = $calc_text;
}
else {
    $text = $show_esto_3 ? __( 'Pay in three equal instalments 3 x %s', 'woo-esto' ) : __( 'Monthly payment from %s', 'woo-esto' );
}
$text_html = sprintf( $text, '<span id="esto_monthly_payment">' . $estoMonthlyPayment . '</span>' );
?>
<div class="esto_calculator">
    <strong class="monthly_payment"><?php echo $text_html ?></strong>
</div>
