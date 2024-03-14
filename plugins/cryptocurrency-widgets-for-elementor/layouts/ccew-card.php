<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
$unique_num = rand(10, 1000);
if ($card_style == 'style-1') {

    $content .= '<div class="ccew-wrapper ccew-price-card ccew-bg ' . esc_attr($card_style) . '">
    <div class="ccew-inner-section">
        <div class="ccew-title-section">
            <div class="ccew-logo-section">
                <div class="ccew-coin-logo">';
    $content .= $coin_logo_html;
    $content .= '</div>
                <div class="ccew-coin-name ccew-primary">
                    ' . esc_html($coin_name) . '
                </div>';

    if ($coin_symbol_visibility == 'yes') {
        $content .= '<div class="ccew-coin-symbol ccew-primary">
                        <span>' . esc_html($symbol) . ' / ' . esc_html($fiat_currency) . '</span>
                    </div>';
    }
    $content .= '</div>

            <div class="ccew-price-section">
                <div class="ccew-coin-price">
                    <span class="ccew-price ccew-primary">' . esc_html($price) . '</span>
                </div>';

    if ($display_24h_changes == 'yes') {
        $content .= '<div class="ccew-price-change">
                        <span class="ccew-change-percent">';
        $content .= ccew_changes_up_down($change_24_h);
        $content .= '</span>
                    </div>';
    }
    $content .= '</div>
        </div>

        <div class="ccew-coin-info ccew-secondary">';
    if ($display_rank == 'yes') {
        $content .= '<div class="ccew-info-item">
                    <span class="ccew-item-label">' . __('Rank:', 'ccew') . '</span>
                    <span class="ccew-item-value"># ' . esc_html($rank) . '</span>
                </div>';
    }

    if ($display_1h_changes == 'yes') {
        $content .= '<div class="ccew-info-item">
                   <span class="ccew-item-label">' . __('1H change:', 'ccew') . '</span>
                   <span class="ccew-item-value">' . ccew_changes_up_down($change_1h) . '</span>
                </div>';
    }
    if ($display_24h_changes == 'yes') {
        $content .= '<div class="ccew-info-item">
                    <span class="ccew-item-label">' . __('24H change:', 'ccew') . '</span>
                    <span class="ccew-item-value">' . ccew_changes_up_down($change_24h) . '</span>
                </div>';
    }
    if ($display_7d_changes == 'yes') {
        $content .= '<div class="ccew-info-item">
                   <span class="ccew-item-label">' . __('7d change:', 'ccew') . '</span>
                   <span class="ccew-item-value">' . ccew_changes_up_down($change_7d) . '</span>
                </div>';
    }
    if ($display_30d_changes == 'yes') {
        $content .= '<div class="ccew-info-item">
                    <span class="ccew-item-label">' . __('30d change:', 'ccew') . '</span>
                    <span class="ccew-item-value">' . ccew_changes_up_down($change_30d) . '</span>
                </div>';
    }
    if ($display_marketcap == 'yes') {
        $content .= '<div class="ccew-info-item">
                    <span class="ccew-item-label">' . __('Market Cap:', 'ccew') . '</span>
                    <span class="ccew-item-value">' . esc_html($market_cap) . '</span>
                </div>';
    }
    if ($display_high_low == 'yes') {
        $content .= '<div class="ccew-info-item">
                    <span class="ccew-item-label">' . __('24H High/Low', 'ccew') . '</span>
                    <span class="ccew-item-value">' . esc_html($high_24h) . '/' . esc_html($low_24h) . '</span>
                </div>';
    }

    $content .= '</div>
    </div>
</div>';
} else if ($card_style == 'style-2') {
    $offset_class = $ccew_display_chart_offset == 'yes' ? "" : "card-offset-hide";
    $chart_class = $ccew_display_chart_offset == 'yes' ? "" : "card-chart-offset-hide";
    $content .= '<div class="ccew-wrapper ccew-price-card ccew-bg ' . esc_attr($card_style) . '">

                    <div class="ccew-card-details">
                     <div class="ccew-card-content">
                        <div class="ccew-card-coin"><span>' . esc_html($coin_name) . '(' . esc_html($symbol) . ')</span></div>';
    if ($display_24h_changes == 'yes') {
        $content .= '<div class="ccew-card-change"><span>' . ccew_changes_up_down($change_24h) . '</span><span class="ccew-changes-time">' . esc_html__('24H', 'ccew') . '</span></div>';

    }
    $content .= ' </div>
                     <div class="ccew-card-content">';
    $content .= '<div class="ccew-card-price"><span>' . esc_html($price) . '</span></div>';
    if ($ccew_card2_changes == "yes") {
        $content .= '<div class="ccew-card-volume"><span>' . esc_html($changes) . '</span><br><span class="ccew-changes-time">' . esc_html__('Changes 24H', 'ccew') . '</span></div>';
    }
    $content .= '</div>
                    </div>';

    $content .= '<div class="ccew-chart-container" style="width:100%;height:100px;">
    <canvas data-bgcolor="' . esc_attr($ccew_chart_color) . '"
        data-coin-id="bitcoin"
        data-points="0"
        data-color="' . esc_attr($ccew_chart_border_color) . '"
        data-content="' . esc_attr($points_24) . '"
        data-currency-symbol="' . esc_attr($currency_symbol) . '"
        data-currency-price="' . esc_attr($fiat_c_rate) . '"
        data-chart-fill="true"
      class="ccew-sparkline-charts ' . esc_attr($chart_class) . '"></canvas>
      </div>';
    $content .= '<div class="ccew-chart-card-offset ' . esc_attr($offset_class) . '" style="background:' . esc_attr($ccew_chart_color) . ';"><div class="ccew-card-offset-content">';
    if ($api == "coin_gecko") {
        $content .= '<div class="ccew-low-24">⇣ ' . esc_html($low_24h) . '<br><span class="ccew-changes-time">' . esc_html__('Low 24H', 'ccew') . '</span></div>';
        $content .= '<div class="ccew-high-24">⇡ ' . esc_html($high_24h) . '<br><span class="ccew-changes-time">' . esc_html__('High 24H', 'ccew') . '</span></div>';
    }
    $content .= '</div></div>';
    $content .= '</div>';
}
