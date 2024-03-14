<?php
$invoice_date = WC_Swiss_Qr_Bill_Generate::get_formatted_date($order->get_date_created());
$invoice_customer_address = '<b>' . $order->get_formatted_billing_full_name() . '</b>';
if ($order->get_billing_company()) {
    $invoice_customer_address = '<b>' . $order->get_billing_company() . '</b><br>' . $order->get_formatted_billing_full_name();
}

?>
<style>
    td.f-small {
        font-size: 10px;
    }
</style>
<table border="0">
    <tr>
        <td class="f-small"><?php echo $invoice_customer_address; ?>
            <br><?php echo $order->get_billing_address_1(); ?>
            <?php echo $order->get_billing_address_2() ? '<br>' . $order->get_billing_address_2() : ''; ?>
            <br><?php echo $order->get_billing_postcode() . ' ' . $order->get_billing_city(); ?>
            <br>
            <br><?php echo ($gateway_options['shop_city'] ? $gateway_options['shop_city'] . ', ' : '') . $invoice_date ?>
        </td>
    </tr>
</table>