<?php

namespace WPDeskFIVendor;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Decorators\TemplateDocumentDecorator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Currency;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration\MetaPostContainer;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings\Settings;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\WordPress\Translator;
use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\LibraryInfo;
/**
 * @var TemplateDocumentDecorator $invoice
 */
$invoice = isset($params['invoice']) ? $params['invoice'] : \false;
/**
 * @var Currency $helper ;
 */
$helper = isset($params['currency_helper']) ? $params['currency_helper'] : \false;
/**
 * @var LibraryInfo $plugin
 */
$library_info = isset($params['library_info']) ? $params['library_info'] : \false;
/**
 * @var  MetaPostContainer $meta
 */
$meta = isset($params['meta']) ? $params['meta'] : \false;
/**
 * @var string $layout_name;
 */
$layout_name = isset($params['layout_name']) ? $params['layout_name'] : 'default';
/**
 * @var Translator $translator
 */
$translator = isset($params['translator']) ? $params['translator'] : \false;
/**
 * @var Settings $settings
 */
$settings = isset($params['settings']) ? $params['settings'] : \false;
$client = $invoice->get_customer();
$client_country = $client->get_country();
$owner = $invoice->get_seller();
$products = $invoice->get_items();
$show_discounts = $settings->get('show_discount', 'no');
$pkwiuEmpty = \true;
$discountEmpty = \true;
foreach ($products as $product) {
    if (!empty($product['sku'])) {
        $pkwiuEmpty = \false;
    }
    if (\floatval($product['discount']) !== 0 && $show_discounts !== 'no') {
        $discountEmpty = \false;
    }
}
$hideVat = $settings->get('hide_vat') === 'yes' && !$invoice->get_total_tax();
$hideVatNumber = $settings->get('hide_vat_number') === 'yes' && !$invoice->get_total_tax();
$translator::switch_lang($invoice->get_user_lang());
$translator::set_translate_lang($invoice->get_user_lang());
$default_font = 'dejavusanscondensed';
$default_color = '#000000';
$rtl_dir = \is_rtl() ? 'dir="rtl"' : '';
$totals = $invoice->array_to_string_as_money($invoice->get_totals());
$totals_taxes = $invoice->array_to_string_as_money($invoice->get_totals_by_taxes());
$totals = $invoice->array_to_string_as_money($invoice->get_totals());
$items = $invoice->get_items_as_money();
?>
<!DOCTYPE HTML>
<html lang="<?php 
echo \get_locale();
?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?php 
echo \esc_html($invoice->get_formatted_number());
?></title>
    <link href="<?php 
echo \esc_url($library_info->get_assets_url());
?>css/pdf.css" rel="stylesheet" type="text/css"/>
	<style media="screen">
		#wrapper {
			max-width: 800px;
			width: 800px;
			margin: 0 auto;
		}
	</style>
    <style>
        h1 {
            font-size: <?php 
echo (int) $settings->get('template_heading1_font_size', 18);
?>px;
            font-family: <?php 
echo \esc_attr($settings->get('template_heading1_font_family', $default_font));
?>;
            color: <?php 
echo \esc_attr($settings->get('template_heading1_font_color', $default_color));
?>;
        }

        h2 {
            font-size: <?php 
echo (int) $settings->get('template_heading2_font_size', 12);
?>px;
            font-family: <?php 
echo \esc_attr($settings->get('template_heading2_font_family', $default_font));
?>;
            color: <?php 
echo \esc_attr($settings->get('template_heading2_font_color', $default_color));
?>;
        }

        h3 {
            font-size: <?php 
echo (int) $settings->get('template_heading3_font_size', 9);
?>px;
            font-family: <?php 
echo \esc_attr($settings->get('template_heading3_font_family', $default_font));
?>;
            color: <?php 
echo \esc_attr($settings->get('template_heading3_font_color', $default_color));
?>;
        }

        body {
            font-size: <?php 
echo (int) $settings->get('template_text_font_size', 8);
?>px;
            font-family: <?php 
echo \esc_attr($settings->get('template_text_font_family', $default_font));
?>;
            color: <?php 
echo \esc_attr($settings->get('template_text_font_color', $default_color));
?>;
        }

        table.item-table td, table.item-table th {
            border:  <?php 
echo (int) $settings->get('template_table_border_size', 1);
?>px solid  <?php 
echo \esc_attr($settings->get('template_table_border_color', '#000000'));
?>;
            padding: 4px;
            vertical-align: top;
        }

        table.item-table th {
            background-color: <?php 
echo \esc_attr($settings->get('template_table_header_bg', '#F1F1F1'));
?>;
        }

        table tfoot .total td {
            background-color: <?php 
echo \esc_attr($settings->get('template_table_header_bg', '#F1F1F1'));
?>;
            color: <?php 
echo \esc_attr($settings->get('template_heading3_font_color', $default_color));
?>;
        }

        table tfoot .total td.sum-title {
            font-size: <?php 
echo (int) $settings->get('template_text_font_size', 8);
?>px;
            font-family: <?php 
echo \esc_attr($settings->get('template_text_font_family', $default_font));
?>;
            color: <?php 
echo \esc_attr($settings->get('template_text_font_color', $default_color));
?>;
        }

        table.item-table {
            font-size: <?php 
echo (int) $settings->get('template_text_font_size', 8);
?>px;
            font-family: <?php 
echo \esc_attr($settings->get('template_text_font_family', $default_font));
?>;
            color: <?php 
echo \esc_attr($settings->get('template_text_font_color', $default_color));
?>;
        }

        table.item-table tbody tr:nth-child(even) td {
            background-color: <?php 
echo \esc_attr($settings->get('template_table_rows_even', '#F1F1F1'));
?>;
        }
        <?php 
\WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Helpers\Hooks::template_custom_css_hook($settings);
?>
    </style>
</head>
<body>
<div id="wrapper" class="<?php 
echo \esc_attr($invoice->get_type());
?> <?php 
echo \esc_attr($layout_name);
?>" <?php 
echo \esc_attr($rtl_dir);
?>>
<?php 
