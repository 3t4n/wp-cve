<?php

namespace WPDeskFIVendor;

/**
 * File: parts/header.php
 */
$width = '25%';
$palign = 'center';
if ($invoice->get_type() === 'proforma') {
    $width = '33.3%';
    $palign = 'left';
}
?>
<div id="header">
	<table>
		<tr>
			<?php 
if ($invoice->get_type() !== 'proforma') {
    ?>
			<td style="width:<?php 
    echo \esc_attr($width);
    ?>; text-align: <?php 
    echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('left');
    ?>;">
				<?php 
    echo \esc_html(\trim($translator::translate_meta('inspire_invoices_invoice_date_of_sale_label', \esc_html__('Date of sale', 'flexible-invoices'))));
    ?>: <strong><?php 
    echo \esc_html($invoice->get_date_of_sale());
    ?></strong>
			</td>
			<?php 
}
?>
			<td style="width:<?php 
echo \esc_attr($width);
?>; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align($palign);
?>;">
				<?php 
\esc_html_e('Issue date', 'flexible-invoices');
?>: <strong><?php 
echo \esc_html($invoice->get_date_of_issue());
?></strong>
			</td>
			<td style="width:<?php 
echo \esc_attr($width);
?>; text-align: center;">
				<?php 
\esc_html_e('Due date', 'flexible-invoices');
?>: <strong><?php 
echo \esc_html($invoice->get_date_of_pay());
?></strong>
			</td>
			<td style="width:<?php 
echo \esc_attr($width);
?>; text-align: <?php 
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Template::rtl_align('right');
?>;">
				<?php 
\esc_html_e('Payment', 'flexible-invoices');
?>: <strong><?php 
echo \esc_html($invoice->get_payment_method_name());
?></strong>
			</td>

		</tr>
	</table>
    <table>
        <tbody>
        <tr>
            <td style="text-align: center; padding: 0 0 20px;">
                <?php 
if (!empty($owner->get_logo())) {
    ?>
                    <img alt="" class="logo" src="<?php 
    echo \esc_url($owner->get_logo());
    ?>"/>
                <?php 
}
?>
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <h1><?php 
echo \esc_html($invoice->get_formatted_number());
?></h1>
            </td>
        </tr>
        </tbody>
    </table>
    <table>
        <tr>
            <td style="width:50%;">
                <?php 
require \dirname(__DIR__, 2) . '/header-parts/seller.php';
?>
            </td>
            <td style="width:25%;">
                <?php 
require \dirname(__DIR__, 2) . '/header-parts/buyer.php';
?>
            </td>
            <td style="width:25%;">
                <?php 
require \dirname(__DIR__, 2) . '/header-parts/recipient.php';
?>
            </td>
        </tr>
    </table>
</div>
<?php 
