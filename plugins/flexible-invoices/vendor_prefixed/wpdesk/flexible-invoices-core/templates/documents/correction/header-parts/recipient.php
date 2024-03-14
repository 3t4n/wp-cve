<?php

namespace WPDeskFIVendor;

// Seller.php
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries;
/**
 * @var Document $correction
 */
$correction = isset($params['invoice']) ? $params['invoice'] : \false;
/**
 * @var Recipient $recipient
 */
$recipient = $correction->get_recipient();
$should_show_recipient = \false;
$show_recipient_type = $settings->get('woocommerce_shipping_address', 'none');
if ($show_recipient_type !== 'none') {
    ?>
	<?php 
    if (!empty($recipient->get_street()) && !empty($recipient->get_postcode())) {
        ?>
    <table style="margin-bottom: 0;">
        <tr><td><h2><?php 
        \esc_html_e('Shipping', 'flexible-invoices');
        ?>:</h2></td></tr>
        <?php 
        if (!empty($recipient->get_name())) {
            ?>
            <tr><td><?php 
            echo \esc_html($recipient->get_name());
            ?></td></tr>
        <?php 
        }
        ?>
        <?php 
        if (!empty($recipient->get_street())) {
            ?>
            <tr><td><?php 
            echo \esc_html($recipient->get_street());
            ?> <?php 
            echo \esc_html($recipient->get_street2());
            ?></td></tr>
        <?php 
        }
        ?>
        <?php 
        if (!empty($recipient->get_city())) {
            ?>
            <tr><td><?php 
            echo \esc_html($recipient->get_postcode());
            ?> <?php 
            echo \esc_html($recipient->get_city());
            ?></td></tr>
            <tr><td><?php 
            echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries::get_country_label($recipient->get_country());
            ?></td></tr>
        <?php 
        }
        ?>
        <?php 
        if (!empty($recipient->get_city())) {
            ?>
            <tr><td></td></tr>
        <?php 
        }
        ?>
        <?php 
        if (!empty($recipient->get_vat_number())) {
            ?>
            <tr><td><?php 
            \esc_html_e('VAT Number', 'flexible-invoices');
            ?>: <?php 
            echo \esc_html($recipient->get_vat_number());
            ?></td></tr>
        <?php 
        }
        ?>
    </table>



	<?php 
    }
}
