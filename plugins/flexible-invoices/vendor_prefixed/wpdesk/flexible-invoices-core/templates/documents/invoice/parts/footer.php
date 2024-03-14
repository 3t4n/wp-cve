<?php

namespace WPDeskFIVendor;

/**
 * File: parts/footer.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template;
$layout_name = isset($layout_name) ? $layout_name : 'default';
?>
<table id="footer" class="table-without-margin" style="margin-top: 10px;">
    <tr>
        <td style="text-align: <?php 
echo \esc_attr(\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left'));
?>;">
			<?php 
$note = $invoice->get_notes();
?>
			<?php 
if (!empty($note)) {
    ?>
				<p><strong><?php 
    \esc_html_e('Notes', 'flexible-invoices');
    ?></strong></p>
				<p><?php 
    echo \str_replace(\PHP_EOL, '<br/>', \esc_html($note));
    ?></p>
			<?php 
}
?>
            <?php 
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_invoice_after_notes($invoice, $client_country, $hideVat, $hideVatNumber);
?>

			<?php 
if ($invoice->get_show_order_number()) {
    ?>
				<?php 
    $order = $invoice->get_order_number();
    ?>
				<p><?php 
    \esc_html_e('Order number', 'flexible-invoices');
    ?>: <?php 
    echo \esc_html($invoice->get_order_number());
    ?></p>
			<?php 
}
?>
        </td>
    </tr>
</table>
<?php 
