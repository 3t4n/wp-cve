<?php

namespace WPDeskFIVendor;

// Seller.php
/**
 * @var \WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $invoice
 */
$owner = $invoice->get_seller();
?>

<table style="margin-bottom: 0;">
    <tr><td><h2><?php 
\esc_html_e('Seller', 'flexible-invoices');
?>:</h2></td>
    </tr>
    <?php 
if (!empty($owner->get_name())) {
    ?>
    <tr><td><?php 
    echo \esc_html($owner->get_name());
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($owner->get_address())) {
    ?>
        <tr><td><?php 
    echo \nl2br(\esc_html($owner->get_address()));
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($owner->get_vat_number()) && !$hideVatNumber) {
    ?>
        <tr><td><?php 
    \esc_html_e('VAT Number', 'flexible-invoices');
    ?>: <?php 
    echo \esc_html($owner->get_vat_number());
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($owner->get_bank_name())) {
    ?>
        <tr><td><?php 
    \esc_html_e('Bank', 'flexible-invoices');
    ?>: <?php 
    echo \esc_html($owner->get_bank_name());
    ?></td></tr>
    <?php 
}
?>
    <?php 
if (!empty($owner->get_bank_account_number())) {
    ?>
        <tr><td><?php 
    \esc_html_e('Account number', 'flexible-invoices');
    ?>: <?php 
    echo \esc_html($owner->get_bank_account_number());
    ?></td></tr>
    <?php 
}
?>
</table>
<?php 
