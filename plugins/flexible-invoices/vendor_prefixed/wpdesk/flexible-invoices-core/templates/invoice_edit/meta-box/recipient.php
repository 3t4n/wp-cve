<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\DocumentData\Recipient;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers;
$params = isset($params) ? $params : [];
/**
 * @var Document $invoice
 */
$invoice = $params['invoice'];
/**
 * @var Recipient $recipient
 */
$recipient = $params['recipient'];
?>

<div class="form-wrap inspire-panel invoice-edit-display">
	<div class="display">
		<div class="inspire_invoices_recipient_name"><?php 
\esc_html_e('Company Name', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($recipient->get_name());
?></span></div>
		<div class="inspire_invoices_recipient_nip"><?php 
\esc_html_e('VAT Number', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($recipient->get_vat_number());
?></span></div>
		<div class="inspire_invoices_recipient_street"><?php 
\esc_html_e('Address line 1', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($recipient->get_street());
?></span></div>
		<div class="inspire_invoices_recipient_street2"><?php 
\esc_html_e('Address line 2', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($recipient->get_street2());
?></span></div>
		<div class="inspire_invoices_recipient_city"><?php 
\esc_html_e('City', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($recipient->get_city());
?></span></div>
		<div class="inspire_invoices_recipient_postcode"><?php 
\esc_html_e('Zip code', 'flexible-invoices');
?>: <span><?php 
echo \esc_html($recipient->get_postcode());
?></span></div>
		<div class="inspire_invoices_recipient_country"><?php 
\esc_html_e('Country', 'flexible-invoices');
?>: <span><?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries::get_country_label($recipient->get_country());
?></span></div>
		<div class="inspire_invoices_recipient_country"><?php 
\esc_html_e('State', 'flexible-invoices');
?>: <span><?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Countries::get_country_state_label($recipient->get_state(), $recipient->get_country());
?></span></div>
    </div>
	<div class="edit_data">
		<?php 
$document_issuing = 'Manual Issuing Proforma and Invoices';
?>

		<div class="form-field">
			<label for="inspire_invoices_recipient_name"><?php 
\esc_html_e('Name', 'flexible-invoices');
?></label>
			<input data-beacon_search="<?php 
echo \esc_attr($document_issuing);
?>" id="inspire_invoices_recipient_name" type="text" class="medium hs-beacon-search" name="recipient[name]" value="<?php 
echo \esc_attr($recipient->get_name());
?>" />
		</div>

		<div class="form-field">
			<label for="inspire_invoices_recipient_nip"><?php 
\esc_html_e('VAT Number', 'flexible-invoices');
?></label>
			<input data-beacon_search="<?php 
echo \esc_attr($document_issuing);
?>" id="inspire_invoices_recipient_nip" type="text" class="medium hs-beacon-search" name="recipient[nip]" value="<?php 
echo \esc_attr($recipient->get_vat_number());
?>" />
		</div>

		<div class="form-field flex-container">
			<div class="flex-container">
				<div class="flex-col">
					<label for="inspire_invoices_recipient_street"><?php 
\esc_html_e('Address line 1', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo \esc_attr($document_issuing);
?>" id="inspire_invoices_recipient_street" type="text" class="medium hs-beacon-search" name="recipient[street]" value="<?php 
echo \esc_attr($recipient->get_street());
?>" />
				</div>
				<div class="flex-col">
					<label for="inspire_invoices_recipient_street2"><?php 
\esc_html_e('Address line 2', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo \esc_attr($document_issuing);
?>" id="inspire_invoices_recipient_street2" type="text" class="medium hs-beacon-search" name="recipient[street2]" value="<?php 
echo \esc_attr($recipient->get_street2());
?>" />
				</div>
			</div>
		</div>

		<div class="form-field flex-container">
			<div class="flex-container">
				<div class="flex-col">
					<label for="inspire_invoices_recipient_city"><?php 
\esc_html_e('City', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo \esc_attr($document_issuing);
?>" id="inspire_invoices_recipient_city" type="text" class="medium hs-beacon-search" name="recipient[city]" value="<?php 
echo \esc_attr($recipient->get_city());
?>" />
				</div>
				<div class="flex-col">
					<label for="inspire_invoices_recipient_postcode"><?php 
\esc_html_e('Zip code', 'flexible-invoices');
?></label>
					<input data-beacon_search="<?php 
echo \esc_attr($document_issuing);
?>" id="inspire_invoices_recipient_postcode" type="text" class="medium hs-beacon-search" name="recipient[postcode]" value="<?php 
echo \esc_attr($recipient->get_postcode());
?>" />
				</div>
			</div>
		</div>

        <?php 
$fake_option = '';
$countries = [];
$states = [];
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    $countries = \WC()->countries->get_countries();
    $states = \WC()->countries->get_states();
}
$recipient_country = $recipient->get_country();
$recipient_state = $recipient->get_state();
if (!isset($countries[$recipient_country]) && !empty($recipient_country)) {
    $fake_option = '<option selected="selected" value="' . \esc_attr($recipient_country) . '">' . \esc_html($recipient_country) . '</option>';
}
if (empty($recipient_country)) {
    $recipient_country = \get_option('woocommerce_default_country');
}
if ($recipient_country && \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    $states = \WC()->countries->get_states($recipient_country);
}
?>

        <div class="form-field">
            <label for="inspire_invoices_recipient_country"><?php 
\esc_html_e('Country', 'flexible-invoices');
?></label>
            <?php 
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    ?>
                <select id="inspire_invoices_recipient_country" name="recipient[country]" class="country-select2 medium hs-beacon-search">
                    <?php 
    echo $fake_option;
    ?>
                    <?php 
    foreach ($countries as $country_code => $country_name) {
        ?>
                        <option <?php 
        \selected($country_code, $recipient_country);
        ?> value="<?php 
        echo \esc_attr($country_code);
        ?>"><?php 
        echo \esc_html($country_name);
        ?></option>
                    <?php 
    }
    ?>
                </select>
            <?php 
} else {
    ?>
                <input id="inspire_invoices_recipient_country" type="text" class="medium" name="recipient[country]" value="<?php 
    echo \esc_attr($recipient_country);
    ?>" />
            <?php 
}
?>
        </div>

		<div class="form-field recipient_state">
			<label for="inspire_invoices_recipient_state"><?php 
\esc_html_e('State', 'flexible-invoices');
?></label>
			<?php 
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active()) {
    ?>
				<select id="inspire_invoices_recipient_state" name="recipient[state]" class="country-select2 medium hs-beacon-search">
					<?php 
    foreach ($states as $state_code => $state_name) {
        ?>
						<option <?php 
        \selected($state_code, $recipient_state);
        ?> value="<?php 
        echo \esc_attr($state_code);
        ?>"><?php 
        echo \esc_html($state_name);
        ?></option>
					<?php 
    }
    ?>
				</select>
			<?php 
} else {
    ?>
				<input id="inspire_invoices_recipient_state" type="text" class="medium" name="recipient[state]" value="<?php 
    echo \esc_attr($recipient_state);
    ?>" />
			<?php 
}
?>
		</div>
	</div>
</div>
<?php 
