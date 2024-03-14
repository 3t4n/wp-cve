<?php
/** @var WC_Order $order */
$text_align = is_rtl() ? 'right' : 'left';
if ( version_compare( WOOCOMMERCE_VERSION, '3.0.0', '>=' ) ) {
	$order_id = $order->get_id();
} else {
	$order_id = $order->id;
}
$pan_id = get_post_meta( $order_id , 'pan_id', true);

if ( !empty ( $pan_id ) ) { ?>
    <div style="margin-bottom: 40px;">
        <table class="td" cellspacing="0" cellpadding="6"
               style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
            <tbody>
            <tr>
                <td class="td" style="text-align:<?php esc_attr_e( $text_align ); ?>;">
	                <?php echo wp_kses_post( 'Sie haben bei dieser Bestellung eine Zahlungsart der Santander gewählt.
                        In Kürze erhalten Sie von der Santander eine Email mit allen Informationen
                        zu den weiteren Schritten und Zahlungsfristen. Bitte achten Sie darauf,
                        das Geld nicht an uns, sondern an das unten angegebene Konto der Santander
                        zu überweisen und dabei den folgenden Verwendungszweck anzugeben:' ); ?><br><br>

	                <?php echo wp_kses_post( 'Empfänger: Santander Consumer Bank AG' ); ?><br>
	                <?php echo wp_kses_post( 'IBAN: DE89 3101 0833 8810 0761 20' ); ?><br>
	                <?php echo wp_kses_post( 'BIC: SCFBDE33XXX' ); ?><br>
	                <?php echo wp_kses_post( 'Betrag: ' ); ?><?php echo wp_kses_post( $order->get_total() ); ?><br>
	                <?php echo wp_kses_post( 'Verwendungszweck: ' ); ?><?php echo wp_kses_post( $pan_id ); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
<?php } ?>
