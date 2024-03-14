<?php

namespace WPDeskFIVendor;

/**
 * File: dates.php
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template;
?>
<table style="float: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
	<?php 
if ($invoice->get_type() !== 'proforma') {
    ?>
    <tr>
        <td style="padding-<?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
    ?>: 10px; text-align: <?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
    ?>;">
			<?php 
    echo \esc_html(\trim($translator::translate_meta('inspire_invoices_invoice_date_of_sale_label', \esc_html__('Date of sale', 'flexible-invoices'))));
    ?>: <strong><?php 
    echo \esc_html($invoice->get_date_of_sale());
    ?></strong>
        </td>
    </tr>
	<?php 
}
?>
    <tr>
        <td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
			<?php 
\esc_html_e('Issue date', 'flexible-invoices');
?>: <strong><?php 
echo \esc_html($invoice->get_date_of_issue());
?></strong>
        </td>
    </tr>
	<tr>
		<td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
			<?php 
\esc_html_e('Due date', 'flexible-invoices');
?>: <strong><?php 
echo \esc_html($invoice->get_date_of_pay());
?></strong>
		</td>
	</tr>
	<tr>
		<td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
			<?php 
\esc_html_e('Payment method', 'flexible-invoices');
?>: <strong><?php 
echo \esc_html($invoice->get_payment_method_name());
?></strong>
		</td>
	</tr>
</table>
<?php 
