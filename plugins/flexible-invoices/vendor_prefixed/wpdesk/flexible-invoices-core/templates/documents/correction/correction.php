<?php

namespace WPDeskFIVendor;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Documents\Document;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\CalculateTotals;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
/**
 * @var Document $correction
 */
$correction = isset($params['invoice']) ? $params['invoice'] : \false;
/**
 * @var LibraryInfo $library_info
 */
$library_info = isset($params['library_info']) ? $params['library_info'] : \false;
/**
 * @var Settings $library_info
 */
$settings = isset($params['settings']) ? $params['settings'] : \false;
/**
 * @var Document $corrected_invoice
 */
$corrected_invoice = isset($params['corrected_invoice']) ? $params['corrected_invoice'] : \false;
/**
 * @var  MetaPostContainer $meta
 */
$meta = isset($params['meta']) ? $params['meta'] : \false;
/**
 * @var Translator $translator
 */
$translator = isset($params['translator']) ? $params['translator'] : \false;
$client = $correction->get_customer();
$client_country = $client->get_country();
$owner = $correction->get_seller();
$products = $correction->get_items();
$pkwiuEmpty = \true;
if (!\is_array($products)) {
    $products = array();
}
foreach ($products as $product) {
    if (!empty($product['sku'])) {
        $pkwiuEmpty = \false;
    }
}
$hideVat = $settings->get('hide_vat') === 'yes' && !$correction->get_total_tax();
$hideVatNumber = $settings->get('hide_vat_number') === 'yes' && !$correction->get_total_tax();
$translator::switch_lang($correction->get_user_lang());
$translator::set_translate_lang($correction->get_user_lang());
$currency_helper = new \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency($correction->get_currency());
$product_name_style = \true === $hideVat ? 'width: 50%' : 'width: 30%';
?>
<!DOCTYPE HTML>
<html lang="<?php 
echo \get_locale();
?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php 
echo \esc_html($correction->get_formatted_number());
?></title>
    <link href="<?php 
echo \esc_url($library_info->get_assets_url());
?>css/pdf.css" rel="stylesheet" type="text/css" />
    <?php 
/**
 * Fires in the header section of the correction template.
 */
\do_action('fi/core/template/correction/head');
?>

</head>
<body>
<div id="wrapper" class="invoice">
    <div id="header">
        <table>
            <tbody>
            <tr>
                <td>
                    <?php 
if (!empty($owner->get_logo())) {
    ?>
                        <div id="logo">
                            <img class="logo"  src="<?php 
    echo \esc_url($owner->get_logo());
    ?>" alt=""/>
                        </div>
                    <?php 
}
?>
                </td>

                <td id="dates">
					<p><?php 
\esc_html_e($settings->get('invoice_date_of_sale_label', \esc_html__('Date of sale', 'flexible-invoices')), 'flexible-invoices');
?>: <strong><?php 
echo \esc_html($correction->get_date_of_sale());
?></strong></p>
                    <p><?php 
\esc_html_e('Issue date', 'flexible-invoices');
?>: <strong><?php 
echo $correction->get_date_of_issue();
?></strong></p>
                    <?php 
if ($correction->get_date_of_pay() > 0) {
    ?>
                        <p><?php 
    \esc_html_e('Due date', 'flexible-invoices');
    ?>: <strong><?php 
    echo $correction->get_date_of_pay();
    ?></strong></p>
                    <?php 
}
?>
                    <?php 
$payment_method = $correction->get_payment_method_name();
?>
                    <?php 
if (!empty($payment_method)) {
    ?>
                        <p><?php 
    \esc_html_e('Payment method:', 'flexible-invoices');
    ?> <strong><?php 
    echo $payment_method;
    ?></strong></p>
                    <?php 
}
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
                </td>
            </tr>
            </tbody>
        </table>

        <div class="fix"></div>

        <div id="companies">
            <div class="seller">
                <h2><?php 
\esc_html_e('Seller', 'flexible-invoices');
?>:</h2>

                <?php 
if (!empty($owner->get_name())) {
    ?>
                    <p class="name"><?php 
    echo $owner->get_name();
    ?></p>
                <?php 
}
?>

                <p class="details"><?php 
echo \nl2br($owner->get_address());
?></p>

                <?php 
if (!empty($owner->get_vat_number()) && !$hideVatNumber) {
    ?>
                    <p class="nip"><?php 
    \esc_html_e('VAT Number:', 'flexible-invoices');
    ?> <?php 
    echo $owner->get_vat_number();
    ?></p>
                <?php 
}
?>

                <?php 
if ($owner->get_bank_name()) {
    ?>
                    <p><?php 
    \esc_html_e('Bank:', 'flexible-invoices');
    ?> <?php 
    echo $owner->get_bank_name();
    ?></p>
                <?php 
}
?>

                <?php 
if ($owner->get_bank_account_number()) {
    ?>
                    <p><?php 
    \esc_html_e('Account number:', 'flexible-invoices');
    ?> <?php 
    echo $owner->get_bank_account_number();
    ?></p>
                <?php 
}
?>

            </div>

            <div class="buyer">
                <h2><?php 
\esc_html_e('Buyer', 'flexible-invoices');
?>:</h2>

                <p>
                    <?php 
if (!empty($client->get_name())) {
    ?>
                        <span><?php 
    echo $client->get_name();
    ?></span><br/>
                    <?php 
}
?>

					<?php 
$output_street = '';
$output_street .= $client->get_street() ? '<span>' . \esc_html($client->get_street()) . '</span><br/>' : '';
$output_street .= $client->get_street2() ? '<span>' . \esc_html($client->get_street2()) . '</span><br/>' : '';
echo \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_customer_street_filter($output_street, $client);
?>

                    <?php 
if (!empty($client->get_postcode())) {
    ?>
                        <span><?php 
    echo \esc_html($client->get_postcode());
    ?></span>
                    <?php 
}
?>

                    <?php 
if (!empty($client->get_city())) {
    ?>
                        <span><?php 
    echo \esc_html($client->get_city());
    ?>,</span>

                        <?php 
    if (!empty($client->get_country())) {
        ?>
                            <span><?php 
        echo \esc_html($client->get_country());
        ?></span><br/>
                        <?php 
    }
    ?>
						<?php 
    if (!empty($client->get_state())) {
        ?>
							<span><?php 
        echo \esc_html($client->get_state());
        ?></span><br/>
						<?php 
    }
    ?>
                    <?php 
} elseif (!empty($client->get_postcode())) {
    ?>
                        <span><?php 
    echo \esc_html($client->get_postcode());
    ?></span>
                        <br/>
                    <?php 
} else {
    ?>
                        <span><?php 
    echo $client->get_country() ? \esc_html($client->get_country()) : '';
    ?></span>
                    <?php 
}
?>
                </p>

                <?php 
if (!empty($client->get_vat_number())) {
    ?>
                    <p><?php 
    \esc_html_e('VAT Number:', 'flexible-invoices');
    echo \esc_html($client->get_vat_number());
    ?></p>
                <?php 
}
?>
            </div>

            <div class="fix"></div>
        </div>
        <div class="fix"></div>
    </div>

    <h1>
        <?php 
echo \esc_html($correction->get_formatted_number());
?>
    </h1>

    <table>
        <thead>
        <?php 
$correction_colspan = 6;
?>
        <tr>
            <th><?php 
\esc_html_e('#', 'flexible-invoices');
?></th>
            <th class="item-title" style="<?php 
echo \esc_html($product_name_style);
?>"><?php 
\esc_html_e('Name', 'flexible-invoices');
?></th>
            <?php 
if (!$pkwiuEmpty) {
    ?>
                <?php 
    $correction_colspan = $correction_colspan + 1;
    ?>
                <th><?php 
    \esc_html_e('SKU', 'flexible-invoices');
    ?></th>
            <?php 
}
?>
            <th><?php 
\esc_html_e('Quantity', 'flexible-invoices');
?></th>
            <th><?php 
\esc_html_e('Unit', 'flexible-invoices');
?></th>
            <th><?php 
\esc_html_e('Net price', 'flexible-invoices');
?></th>
            <th><?php 
\esc_html_e('Net amount', 'flexible-invoices');
?></th>
            <?php 
if (!$hideVat) {
    ?>
                <?php 
    $correction_colspan = $correction_colspan + 3;
    ?>
                <th><?php 
    \esc_html_e('Tax rate', 'flexible-invoices');
    ?></th>
                <th><?php 
    \esc_html_e('Tax amount', 'flexible-invoices');
    ?></th>
                <th><?php 
    \esc_html_e('Gross amount', 'flexible-invoices');
    ?></th>
            <?php 
}
?>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td colspan="<?php 
echo $correction_colspan;
?>"><?php 
\esc_html_e('Before correction', 'flexible-invoices');
?></td>
        </tr>
        <?php 
$index = 0;
$total_tax_amount = 0;
$total_net_price = 0;
$total_gross_price = 0;
$total_tax_net_price = array();
$total_tax_tax_amount = array();
$total_tax_gross_price = array();
?>
        <?php 
foreach ($products as $item) {
    ?>
            <?php 
    if (isset($item['before_correction']) && (int) $item['before_correction'] === 1) {
        $index++;
        ?>
                <tr>
                    <td class="center"><?php 
        echo $index;
        ?></td>
                    <td class="left"><?php 
        echo $item['name'];
        ?></td>
                    <?php 
        if (!$pkwiuEmpty) {
            ?>
						<td><?php 
            if (!isset($item['sku'])) {
                echo \wordwrap($item['sku'], 6, "\n", \true);
            }
            ?></td>
                    <?php 
        }
        ?>
                    <td class="quantity number"><?php 
        echo -1 * $item['quantity'];
        ?></td>
                    <td class="unit center"><?php 
        echo $item['unit'];
        ?></td>
                    <td class="net-price number"><?php 
        echo $currency_helper->string_as_money($item['net_price']);
        ?></td>

                    <td class="total-net-price number"><?php 
        echo $currency_helper->string_as_money(-1 * $item['net_price_sum']);
        ?></td>
                    <?php 
        if (!$hideVat) {
            ?>
                        <td class="tax-rate number"><?php 
            echo $item['vat_type_name'];
            ?></td>
                        <td class="tax-amount number"><?php 
            echo $currency_helper->string_as_money(-1 * $item['vat_sum']);
            ?></td>
                        <td class="total-gross-price number"><?php 
            echo $currency_helper->string_as_money(-1 * $item['total_price']);
            ?></td>
                    <?php 
        }
        ?>

                    <?php 
        $total_net_price += $item['net_price_sum'];
        $total_tax_amount += $item['vat_sum'];
        $total_gross_price += $item['total_price'];
        if (!empty($item['vat_type_name'])) {
            $total_tax_net_price[$item['vat_type_name']] = @\floatval($total_tax_net_price[$item['vat_type_name']]) + $item['net_price_sum'];
            $total_tax_tax_amount[$item['vat_type_name']] = @\floatval($total_tax_tax_amount[$item['vat_type_name']]) + $item['vat_sum'];
            $total_tax_gross_price[$item['vat_type_name']] = @\floatval($total_tax_gross_price[$item['vat_type_name']]) + $item['total_price'];
        }
        ?>
                </tr>
                <?php 
    }
    ?>
        <?php 
}
?>
        <tr>
            <td colspan="<?php 
echo $correction_colspan;
?>"><?php 
\esc_html_e('After correction', 'flexible-invoices');
?></td>
        </tr>
        <?php 
$index = 0;
?>
        <?php 
foreach ($products as $item) {
    ?>
            <?php 
    if (!isset($item['before_correction'])) {
        $index++;
        ?>
                <tr>
                    <td class="center"><?php 
        echo $index;
        ?></td>
                    <td><?php 
        echo $item['name'];
        ?></td>
                    <?php 
        if (!$pkwiuEmpty) {
            ?>
                        <td><?php 
            echo $item['sku'];
            ?></td>
                    <?php 
        }
        ?>
                    <td class="quantity number"><?php 
        echo $item['quantity'];
        ?></td>
                    <td class="unit center"><?php 
        echo $item['unit'];
        ?></td>
                    <td class="net-price number"><?php 
        echo $currency_helper->string_as_money($item['net_price']);
        ?></td>

                    <td class="total-net-price number"><?php 
        echo $currency_helper->string_as_money($item['net_price_sum']);
        ?></td>
                    <?php 
        if (!$hideVat) {
            ?>
                        <td class="tax-rate number"><?php 
            echo $item['vat_type_name'];
            ?></td>
                        <td class="tax-amount number"><?php 
            echo $currency_helper->string_as_money($item['vat_sum']);
            ?></td>
                        <td class="total-gross-price number"><?php 
            echo $currency_helper->string_as_money($item['total_price']);
            ?></td>
                    <?php 
        }
        ?>


                    <?php 
        $total_net_price += $item['net_price_sum'];
        $total_tax_amount += $item['vat_sum'];
        $total_gross_price += $item['total_price'];
        if (!empty($item['vat_type_name'])) {
            $total_tax_net_price[$item['vat_type_name']] = @\floatval($total_tax_net_price[$item['vat_type_name']]) + $item['net_price_sum'];
            $total_tax_tax_amount[$item['vat_type_name']] = @\floatval($total_tax_tax_amount[$item['vat_type_name']]) + $item['vat_sum'];
            $total_tax_gross_price[$item['vat_type_name']] = @\floatval($total_tax_gross_price[$item['vat_type_name']]) + $item['total_price'];
        }
        ?>
                </tr>
                <?php 
    }
    ?>
        <?php 
}
?>

        </tbody>

        <tfoot>
        <tr class="total">
            <td class="empty">&nbsp;</td>
            <td class="empty">&nbsp;</td>
            <td class="empty">&nbsp;</td>
            <td class="empty">&nbsp;</td>
            <?php 
if (!$pkwiuEmpty) {
    ?>
                <td class="empty">&nbsp;</td>
            <?php 
}
?>

            <td class="sum-title"><?php 
\esc_html_e('Total', 'flexible-invoices');
?></td>
            <td class="number"><?php 
echo $currency_helper->string_as_money($total_net_price);
?></td><?php 
// suma "Total net price"
?>
            <?php 
if (!$hideVat) {
    ?>
                <td class="number">X</td><?php 
    // tu zawsze X
    ?>
                <td class="number"><?php 
    echo $currency_helper->string_as_money($total_tax_amount);
    ?></td><?php 
    // suma "Tax amount"
    ?>
                <td class="number"><?php 
    echo $currency_helper->string_as_money($total_gross_price);
    ?></td><?php 
    // suma "Total gross price"
    ?>
            <?php 
}
?>
        </tr>

        <?php 
// poniższe sekcje to rozbicie podatków wg stawek
?>

        <?php 
if (!$hideVat) {
    ?>

            <?php 
    foreach ($total_tax_net_price as $taxType => $price) {
        ?>
                <tr>
                    <td class="empty">&nbsp;</td>
                    <td class="empty">&nbsp;</td>
                    <td class="empty">&nbsp;</td>
                    <td class="empty">&nbsp;</td>
                    <?php 
        if (!$pkwiuEmpty) {
            ?>
                        <td class="empty">&nbsp;</td>
                    <?php 
        }
        ?>
                    <td class="sum-title"><?php 
        \esc_html_e('Including', 'flexible-invoices');
        ?></td>
                    <td class="number"><?php 
        echo $currency_helper->string_as_money($price);
        ?></td><?php 
        // suma "Total net price" dla danej stawki podatkowej
        ?>
                    <td class="number"><?php 
        echo $taxType;
        ?></td><?php 
        //tu stawka podatkowa
        ?>
                    <td class="number"><?php 
        echo $currency_helper->string_as_money($total_tax_tax_amount[$taxType]);
        ?></td><?php 
        // suma "Tax amount" dla danej stawki podatkowej
        ?>
                    <td class="number"><?php 
        echo $currency_helper->string_as_money($total_tax_gross_price[$taxType]);
        ?></td><?php 
        // suma "Total gross price" dla danej stawki podatkowej
        ?>
                </tr>
            <?php 
    }
    ?>

        <?php 
}
?>

        </tfoot>
    </table>
    <table class="totals"><?php 
//tutaj wszystkie kwoty są brutto z podsumowania
?>
        <tbody>
        <tr>
            <td style="width:33.3%"><?php 
\esc_html_e('Total', 'flexible-invoices');
?>:
                <strong><?php 
echo $currency_helper->string_as_money($correction->get_total_gross());
?></strong></td>
            <td style="width:33.3%"><?php 
\esc_html_e('Paid', 'flexible-invoices');
?>:
                <strong><?php 
echo $currency_helper->string_as_money($correction->get_total_paid());
?></strong></td>
            <td style="width:33.3%"><?php 
\esc_html_e('Due', 'flexible-invoices');
?>:
                <strong><?php 
echo $currency_helper->string_as_money($correction->get_total_gross() - $correction->get_total_paid());
?></strong>
            </td>
        </tr>
        </tbody>
    </table>

    <?php 
if ($settings->get('show_signatures') === 'yes') {
    ?>
        <div id="signatures">
            <table>
                <tr>
                    <td>
                        <p class="user"></p>
                        <p>&nbsp;</p>
                        <p>........................................</p>
                    </td>

                    <td width="15%"></td>

                    <td>
                        <?php 
    if ($owner->get_signature_user()) {
        ?>
                            <p class="user">
                                <?php 
        $user = \get_user_by('id', (int) $owner->get_signature_user());
        if (isset($user->data->display_name) && !empty($user->data->display_name)) {
            echo $user->data->display_name;
        } else {
            echo $user->data->user_login;
        }
        ?>
                            </p>
                        <?php 
    }
    ?>
                        <p>&nbsp;</p>
                        <p>........................................</p>
                    </td>
                </tr>

                <tr>
                    <td>
                        <p><?php 
    \esc_html_e('Buyer signature', 'flexible-invoices');
    ?></p>
                    </td>
                    <td width="15%"></td>
                    <td>
                        <p><?php 
    \esc_html_e('Seller signature', 'flexible-invoices');
    ?></p>
                    </td>
                </tr>
            </table>
        </div>
    <?php 
}
?>
    <?php 
$note = $correction->get_notes();
?>
    <?php 
if (!empty($note)) {
    ?>
        <div id="footer">
            <p><strong><?php 
    \esc_html_e('Notes', 'flexible-invoices');
    ?></strong></p>
            <p><?php 
    echo \str_replace("\n", '<br/>', $note);
    ?></p>
        </div>
    <?php 
}
?>
    <?php 
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_correction_after_notes($correction, $client_country, $hideVat, $hideVatNumber);
?>

    <div class="fix"></div>
</div>
<input type="hidden" name="document_id" value="<?php 
echo \esc_attr($correction->get_id());
?>"/>
<input type="hidden" name="order_id" value="<?php 
echo \esc_attr($correction->get_order_id());
?>"/>
<div class="no-page-break"></div>
</body>
</html>
<?php 
