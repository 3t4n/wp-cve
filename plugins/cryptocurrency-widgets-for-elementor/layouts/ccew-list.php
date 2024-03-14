<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$dynamic_cls = '';

if ($display_graph == '') {
    $dynamic_cls = 'ccew-align';
}

$unique_num = rand(10, 1000);
$content .= '<div class="ccew-inner-list  ccew-br">
<div class="ccew-logo-list">
    <span class="ccew-coin-logo">';
$content .= $coin_logo_html;
$content .= '</span>
</div>
<div class="ccew-name-list">
    <span class="ccew-coin-name ccew-primary">
    ' . esc_html($coin_name) . '
    </span>
    <span class="ccew-coin-symbol ccew-secondary">
    ' . esc_html($symbol) . ' / ' . esc_html($fiat_currency) . '
    </span>
</div>
<div class="ccew-price-list  ' . esc_attr($dynamic_cls) . '">
    <span class="ccew-coin-price ccew-primary">
    ' . esc_html($price) . '
    </span>';
if ($display_24h_changes == 'yes') {
    $content .= '<div class="ccew-price-change ccew-coin-percentage">
            <span class="ccew-change-percent">';
    $content .= ccew_changes_up_down($change_24_h);
    $content .= '<span class="ccew-changes-time">' . esc_html__('24H', 'ccew') . '</span>';
    $content .= '</span>
        </div>';
}
$content .= '</div>';

if ($display_graph == 'yes') {
    if ($api == "coin_gecko") {
        $content .= '
        <div class="ccew-graph-list">
            <div class="ccew-coin-graph"  id="ccew-coin-graph_' . esc_attr($coin_id . $unique_num) . '" data-currency_price="' . esc_attr($coin['price']) . '"  data-currency=$ data-chartprice="' . esc_attr($chartprice) . '"  data-stroke_color="' . esc_attr($stroke_color) . '">
            <span class="ccew-7D-changes-time">' . __('7D', 'ccew') . '</span>
            </div>
        </div>';
    } else {
        $content .= '
    <div class="ccew_coin_paprika_graph">
    <img src="https://graphs.coinpaprika.com/currency/chart/' . ccew_coin_array($coin_id, true) . '/7d/chart.svg">
    </div>';
    }

}
$content .= '</div>';
