<?php

namespace WPDeskFIVendor;

/**
 * File: dates.php
 */
/**
 * @var Document $corrected_invoice
 */
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
$corrected_invoice = isset($params['corrected_invoice']) ? $params['corrected_invoice'] : \false;
?>
<table style="float: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
    <tr>
        <td style="padding-<?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
?>: 10px; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
			<?php 
echo \trim($translator::translate_meta('inspire_invoices_invoice_date_of_sale_label', \esc_html__('Date of sale', 'flexible-invoices')));
?>: <strong><?php 
echo \esc_html($correction->get_date_of_sale());
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
\esc_html_e('Issue date', 'flexible-invoices');
?>: <strong><?php 
echo \esc_html($correction->get_date_of_issue());
?></strong>
        </td>
    </tr>
    <?php 
if ($correction->get_date_of_pay() > 0) {
    ?>
        <tr>
            <td style="padding-<?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
    ?>: 10px; text-align: <?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
    ?>;">
				<?php 
    \esc_html_e('Due date', 'flexible-invoices');
    ?>: <strong><?php 
    echo \esc_html($correction->get_date_of_pay());
    ?></strong>
            </td>
        </tr>
    <?php 
}
?>
    <?php 
$paymentMethod = $correction->get_payment_method_name();
?>
    <?php 
if (!empty($paymentMethod)) {
    ?>
        <tr>
            <td style="padding-<?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
    ?>: 10px; text-align: <?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
    ?>;">
				<?php 
    \esc_html_e('Payment method', 'flexible-invoices');
    ?>: <strong><?php 
    echo \esc_html($paymentMethod);
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
\esc_html_e('Related to invoice:', 'flexible-invoices');
?> <strong><?php 
echo \esc_html($corrected_invoice->get_formatted_number());
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
\esc_html_e('Invoice issue date:', 'flexible-invoices');
?> <strong><?php 
echo \esc_html($corrected_invoice->get_date_of_issue());
?></strong>
		</td>
	</tr>
</table>

<?php 
