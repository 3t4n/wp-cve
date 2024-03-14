<?php

namespace WPDeskFIVendor;

/**
 * File: parts/footer.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
$layout_name = isset($layout_name) ? $layout_name : 'default';
?>
<table id="footer" class="table-without-margin" style="margin-top: 10px;">
    <tr>
        <td style="text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>;">
			<?php 
$note = $correction->get_notes();
?>
			<?php 
if (!empty($note)) {
    ?>
				<p><strong><?php 
    \esc_html_e('Notes', 'flexible-invoices');
    ?></strong></p>
				<p><?php 
    echo \str_replace(\PHP_EOL, '<br/>', $note);
    ?></p>
			<?php 
}
?>
			<?php 
if ($layout_name !== 'default') {
    ?>
			<p><?php 
    \esc_html_e('Related to invoice:', 'flexible-invoices');
    ?> <strong><?php 
    echo $corrected_invoice->get_formatted_number();
    ?></strong></p>
			<p><?php 
    \esc_html_e('Invoice issue date:', 'flexible-invoices');
    ?> <strong><?php 
    echo $corrected_invoice->get_date_of_issue();
    ?></strong></p>
			<?php 
}
?>
			<?php 
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_correction_after_notes($correction, $client_country, $hideVat, $hideVatNumber);
?>
        </td>
    </tr>
</table>
<?php 
