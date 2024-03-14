<?php

namespace WPDeskFIVendor;

$selected = $params['selected'];
echo '<select name="user" id="inspire_invoice_client_select">';
if (isset($selected['id'])) {
    echo '<option value="' . \esc_attr($selected['id']) . '">' . \esc_html($selected['text']) . '</option>';
}
echo '</select>';
echo '<select name="paystatus">';
$statuses = $params['statuses'];
$statuses['exceeded'] = \esc_html__('Overdue', 'flexible-invoices');
echo '<option value="">' . \esc_html__('All statuses', 'flexible-invoices') . '</option>';
$paystatus = '';
if (isset($_GET['paystatus'])) {
    $paystatus = $_GET['paystatus'];
}
foreach ($statuses as $key => $status) {
    echo '<option value="' . \esc_attr($key) . '" ' . ($key === $paystatus ? 'selected="selected"' : '') . '>' . \esc_html($status) . '</option>';
}
echo '</select>';
