<?php

namespace WPDeskFIVendor;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Links;
/**
 * @var array $params
 */
$params = isset($params) ? $params : [];
/**
 * @var Document $idocument
 */
$document = $params['document'];
?>

<div class="form-wrap option-form-wrap">
	<?php 
/**
 * Fires before options meta box is rendered.
 *
 * @param Document $document Document type.
 * @param array    $params   Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/options/before', $document, $params);
?>
	<div class="form-field form-required">
		<label for="inspire_invoices_date_issue"><?php 
\esc_html_e('Issue date', 'flexible-invoices');
?></label>
		<input id="inspire_invoices_date_issue" class="hs-beacon-search" type="date" name="date_issue" value="<?php 
echo \esc_attr($document->get_date_of_issue());
?>"/>
	</div>

	<?php 
if ($document->get_type() !== 'proforma') {
    ?>
	<div class="form-field form-required">
		<label for="inspire_invoices_date_sale"><?php 
    \esc_html_e('Date of sale', 'flexible-invoices');
    ?></label>
		<input id="inspire_invoices_date_sale" class="hs-beacon-search" type="date" name="date_sale" value="<?php 
    echo \esc_attr($document->get_date_of_sale());
    ?>"/>
	</div>
	<?php 
}
?>

	<div class="form-field form-required">
		<label for="inspire_invoices_date_pay"><?php 
\esc_html_e('Due date', 'flexible-invoices');
?></label>
		<input id="inspire_invoices_date_pay" class="hs-beacon-search" type="date" name="date_pay" value="<?php 
echo \esc_attr($document->get_date_of_pay());
?>"/>
	</div>

	<div class="actions">
		<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WooCommerce\Links::download_email_links($document);
?>
	</div>

	<?php 
/**
 * Fires after options meta box is rendered.
 *
 * @param Document $document Document type.
 * @param array    $params   Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/options/after', $document, $params);
?>
</div>
<?php 
