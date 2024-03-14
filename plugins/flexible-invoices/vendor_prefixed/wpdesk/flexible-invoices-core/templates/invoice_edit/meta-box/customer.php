<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Customer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;
$params = isset($params) ? $params : [];
/**
 * @var Document $invoice
 */
$invoice = $params['invoice'];
/**
 * @var Customer $client
 */
$client = $params['client'];
?>
<div class="form-wrap inspire-panel invoice-edit-display">
	<div class="display">
		<div class="inspire_invoices_client_name">
			<?php 
\esc_html_e('Company Name', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($client->get_name());
?></span>
		</div>
		<div class="inspire_invoices_client_nip">
			<?php 
\esc_html_e('VAT Number', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($client->get_vat_number());
?></span>
		</div>
		<div class="inspire_invoices_client_street">
			<?php 
\esc_html_e('Address line 1', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($client->get_street());
?></span>
		</div>
		<div class="inspire_invoices_client_street2">
			<?php 
\esc_html_e('Address line 2', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($client->get_street2());
?></span>
		</div>
		<div class="inspire_invoices_client_city">
			<?php 
\esc_html_e('City', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($client->get_city());
?></span>
		</div>
		<div class="inspire_invoices_client_postcode">
			<?php 
\esc_html_e('Zip code', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($client->get_postcode());
?></span>
		</div>
		<div class="inspire_invoices_client_country">
			<?php 
\esc_html_e('Country', 'flexible-invoices');
?>: <span><?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries::get_country_label($client->get_country());
?></span>
		</div>
		<?php 
$state_style = $client->get_state() ? 'block' : 'none';
?>
		<div class="inspire_invoices_client_state" style="display:<?php 
echo $state_style;
?>;">
			<?php 
\esc_html_e('State', 'flexible-invoices');
?>: <span><?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries::get_country_state_label($client->get_state(), $client->get_country());
?></span>
		</div>
		<div class="inspire_invoices_client_phone">
			<?php 
\esc_html_e('Phone', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($client->get_phone());
?></span>
		</div>
		<div class="inspire_invoices_client_email">
			<?php 
\esc_html_e('Email', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($client->get_email());
?></span>
		</div>
	</div>
	<div class="edit_data">
		<?php 
/**
 * Fires before client meta box is rendered.
 *
 * @param Document $invoice Document type.
 * @param array    $params  Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/client/before', $invoice, $params);
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>

		<div class="form-field">
			<div id="inspire_invoice_client_select_wrap">
				<select data-beacon_search="<?php 
echo $document_issuing;
?>" class="hs-beacon-search" id="inspire_invoice_client_select"></select>
			</div>
			<button class="button get_user_data"><?php 
\esc_html_e('Fetch client data', 'flexible-invoices');
?></button>
		</div>

		<div class="form-field">
			<label for="inspire_invoices_client_name"><?php 
\esc_html_e('Name', 'flexible-invoices');
?></label>
			<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_name" type="text" class="medium hs-beacon-search" name="client[name]" value="<?php 
echo \esc_attr($client->get_name());
?>"/>
		</div>

		<div class="form-field">
			<label for="inspire_invoices_client_nip"><?php 
\esc_html_e('VAT Number', 'flexible-invoices');
?></label>
			<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_nip" type="text" class="medium hs-beacon-search" name="client[nip]" value="<?php 
echo \esc_attr($client->get_vat_number());
?>"/>
		</div>

		<div class="form-field flex-container">
			<div class="flex-container">
				<div class="flex-col">
					<label for="inspire_invoices_client_street"><?php 
\esc_html_e('Address line 1', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_street" type="text" class="medium hs-beacon-search" name="client[street]" value="<?php 
echo \esc_attr($client->get_street());
?>"/>
				</div>
				<div class="flex-col">
					<label for="inspire_invoices_client_street2"><?php 
\esc_html_e('Address line 2', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_street2" type="text" class="medium hs-beacon-search" name="client[street2]" value="<?php 
echo \esc_attr($client->get_street2());
?>"/>
				</div>
			</div>
		</div>

		<div class="form-field flex-container">
			<div class="flex-container">
				<div class="flex-col">
					<label for="inspire_invoices_client_city"><?php 
\esc_html_e('City', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_city" type="text" class="medium hs-beacon-search" name="client[city]" value="<?php 
echo \esc_attr($client->get_city());
?>"/>
				</div>
				<div class="flex-col">
					<label for="inspire_invoices_client_postcode"><?php 
\esc_html_e('Zip code', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_postcode" type="text" class="medium hs-beacon-search" name="client[postcode]" value="<?php 
echo \esc_attr($client->get_postcode());
?>"/>
				</div>
			</div>
		</div>

		<?php 
$fake_option = '';
$countries = [];
$states = [];
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    $countries = \WC()->countries->get_countries();
}
$client_country = $client->get_country();
$client_state = $client->get_state();
if (!isset($countries[$client_country]) && !empty($client_country)) {
    $fake_option = '<option selected="selected" value="' . \esc_attr($client_country) . '">' . \esc_html($client_country) . '</option>';
}
if (empty($client_country)) {
    $client_country = \get_option('woocommerce_default_country');
}
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    $states = \WC()->countries->get_states($client_country);
    if (!$states || empty($states)) {
        $states = [];
    }
}
?>
		<div class="form-field">
			<label for="inspire_invoices_client_country"><?php 
\esc_html_e('Country', 'flexible-invoices');
?></label>
			<?php 
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    ?>
				<select id="inspire_invoices_client_country" name="client[country]" class="country-select2 medium hs-beacon-search">
					<?php 
    echo $fake_option;
    ?>
					<?php 
    foreach ($countries as $country_code => $country_name) {
        ?>
						<option <?php 
        \selected($country_code, $client_country);
        ?> value="<?php 
        echo $country_code;
        ?>"><?php 
        echo $country_name;
        ?></option>
					<?php 
    }
    ?>
				</select>
			<?php 
} else {
    ?>
				<input id="inspire_invoices_client_country" type="text" class="medium" name="client[country]" value="<?php 
    echo \esc_attr($client_country);
    ?>"/>
			<?php 
}
?>
		</div>

		<div class="form-field" id="form-field-state">
			<label for="inspire_invoices_client_state"><?php 
\esc_html_e('State', 'flexible-invoices');
?></label>
			<?php 
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    ?>
				<select data-value="<?php 
    echo \esc_attr($client_state);
    ?>" id="inspire_invoices_client_state" name="client[state]" class="medium hs-beacon-search">
					<?php 
    foreach ($states as $state_code => $state_name) {
        ?>
						<option <?php 
        \selected($state_code, $client_state);
        ?> value="<?php 
        echo $state_code;
        ?>"><?php 
        echo $state_name;
        ?></option>
					<?php 
    }
    ?>
				</select>
			<?php 
}
?>
		</div>

		<div class="form-field flex-container">
			<div class="flex-container">
				<div class="flex-col">
					<label for="inspire_invoices_client_phone"><?php 
\esc_html_e('Phone', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_phone" type="text" class="medium hs-beacon-search" name="client[phone]" value="<?php 
echo \esc_attr($client->get_phone());
?>"/>
				</div>
				<div class="flex-col">
					<label for="inspire_invoices_client_email"><?php 
\esc_html_e('Email', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo $document_issuing;
?>" id="inspire_invoices_client_email" type="text" class="medium hs-beacon-search" name="client[email]" value="<?php 
echo \esc_attr($client->get_email());
?>"/>
				</div>
			</div>
		</div>

		<?php 
/**
 * Fires after client meta box is rendered.
 *
 * @param Document $invoice Document type.
 * @param array    $params  Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/client/after', $invoice, $params);
?>
	</div>
</div>
<?php 
