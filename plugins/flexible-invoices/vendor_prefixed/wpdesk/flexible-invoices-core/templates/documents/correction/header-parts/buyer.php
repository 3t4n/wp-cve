<?php

namespace WPDeskFIVendor;

// Seller.php
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
$output_street = '';
if (!empty($client->get_street())) {
    $output_street .= '<span>' . $client->get_street() . '</span><br/>';
}
if (!empty($client->get_street2())) {
    $output_street .= '<span>' . $client->get_street2() . '</span><br/>';
}
$client_street = \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_customer_street_filter($output_street, $client);
?>
<table style="margin-bottom: 0;">
	<tr>
		<td><h2><?php 
\esc_html_e('Buyer', 'flexible-invoices');
?>:</h2></td>
	</tr>
	<?php 
if (!empty($client->get_name())) {
    ?>
		<tr>
			<td><?php 
    echo \esc_html($client->get_name());
    ?></td>
		</tr>
	<?php 
}
?>
	<?php 
if (!empty($client_street)) {
    ?>
		<tr>
			<td><?php 
    echo \wp_kses($client_street, ['span' => ['class']]);
    ?></td>
		</tr>
	<?php 
}
?>
	<?php 
if (!empty($client->get_postcode()) || !empty($client->get_city())) {
    ?>
		<tr>
			<td><?php 
    echo \esc_html($client->get_postcode());
    echo \esc_html($client->get_city());
    ?></td>
		</tr>
		<tr>
			<td><?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries::get_country_label($client->get_country());
    ?></td>
		</tr>
		<tr>
			<td><?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries::get_country_state_label($client->get_state(), $client->get_country());
    ?></td>
		</tr>
	<?php 
}
?>
	<?php 
if (!empty($client->get_city())) {
    ?>
		<tr>
			<td></td>
		</tr>
	<?php 
}
?>
	<?php 
if (!empty($client->get_vat_number())) {
    ?>
		<tr>
			<td><?php 
    \esc_html_e('VAT Number', 'flexible-invoices');
    ?>
				: <?php 
    echo \esc_html($client->get_vat_number());
    ?></td>
		</tr>
	<?php 
}
?>
</table>
<?php 
