<?php
if ( $calc_text ) {
    $text = $calc_text;
}
else {
    $text = $show_esto_3 ? __( 'Pay in three equal instalments 3 x %s', 'woo-esto' ) : __( 'Monthly payment from %s', 'woo-esto' );
}
$text_html = sprintf( $text, '<span id="esto_monthly_payment">' . $estoMonthlyPayment . '</span>' );

$show_period = apply_filters( 'woo_esto_show_period', true );
if ( $show_period && $period_months ) {
    $text_html .= ' / ' . sprintf( _n( '%d month', '%d months', $period_months, 'woo-esto' ), $period_months );
}

$logo_width_attr = ( $logo_width > 0 ) ? ' width="' . $logo_width . '"' : '';
$logo_height_attr = ( $logo_height > 0 ) ? ' height="' . $logo_height . '"' : '';
?>
<div id="esto_calculator">
    <?php if(isset($logoSrc) && isset($logoUrl) && ! empty($logoUrl)): ?>
        <a href="<?php echo $logoUrl; ?>" target="_blank">
            <img<?= $logo_width_attr . $logo_height_attr ?> src="<?php echo $logoSrc; ?>" style="max-width:110px;width:auto;">
            <strong class="monthly_payment"><?php echo $text_html ?></strong>
        </a>
    <?php else: ?>
        <?php if(isset($logoSrc)): ?>
            <img<?= $logo_width_attr . $logo_height_attr ?> src="<?php echo $logoSrc; ?>" style="max-width:110px;width:auto;">
        <?php endif ?>
        <strong class="monthly_payment"><?php echo $text_html ?></strong>
    <?php endif; ?>
</div>