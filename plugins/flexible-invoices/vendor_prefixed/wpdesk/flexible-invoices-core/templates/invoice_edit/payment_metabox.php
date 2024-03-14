<?php

namespace WPDeskFIVendor;

/**
 * @var array $params
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce;
$params = isset($params) ? $params : [];
/**
 * @var WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document $document
 */
$document = $params['document'];
$payment_statuses = isset($params['payment_statuses']) ? $params['payment_statuses'] : '';
$payment_currencies = isset($params['payment_currencies']) ? $params['payment_currencies'] : '';
$payment_methods = isset($params['payment_methods']) ? $params['payment_methods'] : '';
$document_issuing = 'Manual Issuing Proforma and Invoices';
$document_type = isset($_REQUEST['document_type']) ? $_REQUEST['document_type'] : $document->get_type();
?>

<div class="form-wrap inspire-panel">
	<?php 
/**
 * Fires before payment meta box is rendered.
 *
 * @param Document $document Document type.
 * @param array    $params   Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/payment/before', $document, $params);
?>
	<div class="options-group">
		<div class="form-field form-required">
			<input type="hidden" name="document_type" value="<?php 
echo \esc_attr($document_type);
?>"/>
			<input type="hidden" name="number" value="<?php 
echo \esc_html($document->get_number());
?>"/>
			<input type="hidden" name="formatted_number" value="<?php 
echo \esc_attr($document->get_formatted_number());
?>"/>
			<label for="total_price"><?php 
\esc_html_e('Total', 'flexible-invoices');
?></label>
			<input id="total_price" type="text" class="currency" name="total_price" value="<?php 
echo \esc_attr($document->get_total_gross());
?>" readonly/>
		</div>

		<?php 
if ($document->get_type() !== 'proforma') {
    ?>
			<div class="form-field form-required">
				<label for="total_paid"><?php 
    \esc_html_e('Paid', 'flexible-invoices');
    ?></label>
				<input id="total_paid" type="text" class="currency" name="total_paid" value="<?php 
    echo \esc_attr($document->get_total_paid());
    ?>"/>
			</div>
		<?php 
}
?>

		<div class="form-field form-required">
			<label for="payment_status"><?php 
\esc_html_e('Payment status', 'flexible-invoices');
?></label>
			<select name="payment_status" id="payment_status">
				<?php 
foreach ($payment_statuses as $val => $name) {
    ?>
					<option value="<?php 
    echo \esc_html($val);
    ?>" <?php 
    if ($document->get_payment_status() === $val) {
        ?>selected="selected"<?php 
    }
    ?>><?php 
    echo \esc_html($name);
    ?></option>
				<?php 
}
?>
			</select>
		</div>

		<div class="form-field form-required">
			<label for="currency"><?php 
\esc_html_e('Currency', 'flexible-invoices');
?></label>
			<select name="currency" id="currency">
				<?php 
foreach ($payment_currencies as $val => $name) {
    ?>
					<option value="<?php 
    echo \esc_attr($val);
    ?>" <?php 
    if ($document->get_currency() === $val) {
        ?>selected="selected"<?php 
    }
    ?>><?php 
    echo \esc_attr($name);
    ?></option>
				<?php 
}
?>
				<?php 
if ($document->get_currency() && empty($payment_currencies[$document->get_currency()])) {
    ?>
					<option value="<?php 
    echo \esc_attr($document->get_currency());
    ?>" selected="selected"><?php 
    echo \esc_attr($document->get_currency());
    ?></option>
				<?php 
}
?>
			</select>
		</div>

		<div class="form-field form-required">
			<label for="payment_method"><?php 
\esc_html_e('Payment method', 'flexible-invoices');
?></label>
			<select name="payment_method" id="payment_method">
				<?php 
if (isset($payment_methods['woocommerce']) && \is_array($payment_methods['woocommerce']) && \sizeof($payment_methods['woocommerce'])) {
    ?>
					<optgroup label="<?php 
    \esc_html_e('WooCommerce', 'flexible-invoices');
    ?>">
						<?php 
    foreach ($payment_methods['woocommerce'] as $val => $name) {
        ?>
							<option value="<?php 
        echo \esc_attr($val);
        ?>" <?php 
        if ($document->get_payment_method() === $val) {
            ?>selected="selected"<?php 
        }
        ?>><?php 
        echo \esc_attr($name);
        ?></option>
						<?php 
    }
    ?>
					</optgroup>
				<?php 
}
?>

				<?php 
if (isset($payment_methods['standard']) && \is_array($payment_methods['standard']) && \count($payment_methods['standard'])) {
    ?>
					<optgroup label="<?php 
    \esc_html_e('Basic', 'flexible-invoices');
    ?>">
						<?php 
    foreach ($payment_methods['standard'] as $val => $name) {
        ?>
							<option value="<?php 
        echo \esc_attr($val);
        ?>" <?php 
        if ($document->get_payment_method() === $val) {
            ?>selected="selected"<?php 
        }
        ?>><?php 
        echo \esc_attr($name);
        ?></option>
						<?php 
    }
    ?>
					</optgroup>
				<?php 
}
?>
			</select>
		</div>
	</div>

	<div class="options-group">
		<div class="form-field form-required">
			<label for="notes"><?php 
\esc_html_e('Notes', 'flexible-invoices');
?></label>
			<textarea id="notes" class="fluid" name="notes"><?php 
echo \esc_html($document->get_notes());
?></textarea>
		</div>
	</div>
	<?php 
global $post;
$is_order = (int) \get_post_meta($post->ID, '_wc_order_id', \true);
if (\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\WooCommerce::is_active() && $is_order) {
    ?>
		<div class="form-field lonely">
			<label>
				<input type="checkbox" name="add_order_id" value="1" <?php 
    \checked($document->get_show_order_number(), 1, \true);
    ?> /> <?php 
    \esc_html_e('Add order number to an invoice', 'flexible-invoices');
    ?>
			</label>
		</div>
	<?php 
}
?>
	<?php 
/**
 * Fires after payment meta box is rendered.
 *
 * @param Document $document Document type.
 * @param array    $params   Array of params.
 *
 * @since 3.0.0
 */
\do_action('fi/core/layout/metabox/payment/after', $document, $params);
?>
	<input type="hidden" name="wc_order_id" value="<?php 
echo \esc_attr($document->get_order_id());
?>"/>
</div>
<?php 
