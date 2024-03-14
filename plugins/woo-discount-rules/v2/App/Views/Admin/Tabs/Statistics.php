<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$is_pro = Wdr\App\Helpers\Helper::hasPro();
?>
<style>
    .chart-options select {
        vertical-align: inherit;
    }

    .chart-options .chart-period-start,
    .chart-options .chart-period-end {
        padding: 4px 8px;
    }

    .chart-tooltip {
        position: absolute;
    }

    .chart-placeholder {
        margin-right: 50px;
        height: 400px;
    }

    .chart-placeholder.loading:after {
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, .6);
        content: '';
    }
    #chart-container, #coupon-container{
        margin-top: 10px;
        padding: 20px;
        background: #fff;
    }

    .wdr-rule-statistics .select2-selection--single {
        height: 34px !important;
    }

    #info-container {
        display: flex;
        margin-bottom: 10px;
        gap: 10px;
    }
    #info-container .wdr-card {
        width: 100%;
        padding: 0.5rem 20px;
        min-width: 255px;
        box-shadow: 0 1px 1px rgb(0 0 0 / 4%);
        background: #fff;
        box-sizing: border-box;
    }
    #info-container .total-orders {
        border-left: 3px solid #0092e1;
    }
    #info-container .total-revenue {
        border-left: 3px solid #45cc7a;
    }
    #info-container .discounted-amount {
        border-left: 3px solid #e59b42;
    }
    #info-container .total-free-shipping {
        border-left: 3px solid #4f31d5;
    }
    #info-container .wdr-card h4 {
        margin: 4px 0;
    }

    #coupon-container table {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    #coupon-container table td, #coupon-container table th {
        border: 1px solid #e2e4e8;
        padding: 8px 10px;
    }
    #coupon-container table tr:nth-child(even){background-color: #fcfcfc;}
    #coupon-container table tr:hover {background-color: #efeff2;}
    #coupon-container table th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #f8f9fa;
        color: #222;
    }
    #coupon-container table td {color: #444}

    #wdr-statistics-tabs {
        margin: 8px 0 0 0;
        width: 100%;
        display: flex;
        list-style: none;
        padding: 0;
        font-size: 13px;
        float: left;
        color: #646970;
    }
    #wdr-statistics-tabs a {
        line-height: 2;
        padding: 0.2em 0.4rem;
        text-decoration: none;
    }
    #wdr-statistics-tabs a:hover {
        font-weight: 500;
    }
    #wdr-statistics-tabs a.active {
        color: #000;
        font-weight: 600;
    }
</style>

<div id="wpbody-content" class="awdr-container">
    <div>
        <ul id="wdr-statistics-tabs">
            <li><a id="chart-tab" class="active" data-target="#chart-panel"><?php _e('Rule statistics', 'woo-discount-rules'); ?></a> | </li>
            <li><a id="coupon-tab" data-target="#coupon-panel"><?php _e('Coupon Statistics', 'woo-discount-rules'); ?></a></li>
        </ul>
    </div>
    <div class="clear"></div>
    <div class="wdr-statistics-container" id="chart-panel">
        <form method="post" name="wdr-statistics" class="chart-options">
            <div class="wdr-rule-statistics">
                <div class="statistics_date_range">
                    <select name="period" class="chart-period" style="height: 33px">
                        <option value="this_week"><?php _e('This Week', 'woo-discount-rules'); ?></option>
                        <option value="this_month"><?php _e('This Month', 'woo-discount-rules'); ?></option>
                        <option value="custom"><?php _e('Custom Range', 'woo-discount-rules'); ?></option>
                    </select>
                </div>
                <div class="wdr-dateandtime-value">
                    <input type="text"
                           name="from"
                           class="wdr-condition-date wdr-title chart-period-start" data-class="start_dateonly"
                           placeholder="<?php esc_attr_e('From: yyyy/mm/dd', 'woo-discount-rules'); ?>" data-field="date"
                           autocomplete="off"
                           id="rule_datetime_from" value="<?php if (isset($date[0]) && !empty($date[0])) {
                        echo esc_attr($date[0]);
                    } ?>" style="height: 34px;">
                </div>
                <div class="wdr-dateandtime-value">
                    <input type="text"
                           name="to"
                           class="wdr-condition-date wdr-title chart-period-end" data-class="end_dateonly"
                           placeholder="<?php _e('To: yyyy/mm/dd', 'woo-discount-rules'); ?>"
                           data-field="date" autocomplete="off"
                           id="rule_datetime_to" value="<?php if (isset($date[1]) && !empty($date[1])) {
                        echo esc_attr($date[1]);
                    } ?>" style="height: 34px;">
                </div>
                <div class="awdr-report-type" >
                    <select name="type" class="chart-type awdr-show-report-limit">
                        <?php foreach ( $charts as $group => $charts_by_group ): ?>
                            <optgroup label="<?php echo esc_attr($group); ?>">
                                <?php foreach ( $charts_by_group as $key => $name ): ?>
                                    <option value="<?php echo esc_attr($key) ?>"><?php echo esc_html($name) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <input type="hidden" name="awdr_nonce" value="<?php echo esc_attr(\Wdr\App\Helpers\Helper::create_nonce('wdr_ajax_report')); ?>">
                    <button type="submit" class="update-chart btn btn-success"><?php _e('Update', 'woo-discount-rules'); ?></button>
                </div>
            </div>
        </form>
        <div id="info-container" style="display: none;">
            <div class="wdr-card total-orders">
                <h4><?php esc_html_e("Discounted orders", 'woo-discount-rules'); ?></h4>
                <h4 id="total-orders">-</h4>
            </div>
            <div class="wdr-card total-revenue">
                <h4><?php esc_html_e("Total sales", 'woo-discount-rules'); ?></h4>
                <h4 id="total-revenue">-</h4>
            </div>
            <div class="wdr-card discounted-amount">
                <h4><?php esc_html_e("Discounted amount", 'woo-discount-rules'); ?></h4>
                <h4 id="discounted-amount">-</h4>
            </div>
            <?php if ($is_pro) { ?>
                <div class="wdr-card total-free-shipping">
                    <h4><?php esc_html_e("Orders with free shipping", 'woo-discount-rules'); ?></h4>
                    <h4 id="total-free-shipping">-</h4>
                </div>
            <?php } ?>
        </div>
        <div id="chart-container"></div>
    </div>
    <div class="clear"></div>
    <div class="wdr-statistics-container" id="coupon-panel" style="display: none;">
        <form method="post" name="wdr-coupon-analytics" class="coupon-options">
            <div class="wdr-rule-statistics">
                <div class="statistics_date_range">
                    <select name="period" class="chart-period" style="height: 33px">
                        <option value="this_week"><?php _e('This Week', 'woo-discount-rules'); ?></option>
                        <option value="this_month"><?php _e('This Month', 'woo-discount-rules'); ?></option>
                        <option value="custom"><?php _e('Custom Range', 'woo-discount-rules'); ?></option>
                    </select>
                </div>
                <div class="wdr-dateandtime-value">
                    <input type="text"
                           name="from"
                           class="wdr-condition-date wdr-title chart-period-start" data-class="start_dateonly"
                           placeholder="<?php esc_attr_e('From: yyyy/mm/dd', 'woo-discount-rules'); ?>" data-field="date"
                           autocomplete="off"
                           id="rule_datetime_from" value="<?php if (isset($date[0]) && !empty($date[0])) {
                        echo esc_attr($date[0]);
                    } ?>" style="height: 34px;">
                </div>
                <div class="wdr-dateandtime-value">
                    <input type="text"
                           name="to"
                           class="wdr-condition-date wdr-title chart-period-end" data-class="end_dateonly"
                           placeholder="<?php _e('To: yyyy/mm/dd', 'woo-discount-rules'); ?>"
                           data-field="date" autocomplete="off"
                           id="rule_datetime_to" value="<?php if (isset($date[1]) && !empty($date[1])) {
                        echo esc_attr($date[1]);
                    } ?>" style="height: 34px;">
                </div>
                <div class="awdr-coupon-type" >
                    <select name="type" class="chart-type awdr-show-report-limit">
                        <?php foreach ( $coupons as $group => $charts_by_group ): ?>
                            <optgroup label="<?php echo esc_attr($group); ?>">
                                <?php foreach ( $charts_by_group as $key => $name ): ?>
                                    <?php if ($group != __('Coupon', 'woo-discount-rules')) $key = $name; ?>
                                    <option value="<?php echo esc_attr($key) ?>"><?php echo esc_html($name) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <input type="hidden" name="awdr_nonce" value="<?php echo esc_attr(\Wdr\App\Helpers\Helper::create_nonce('wdr_ajax_report')); ?>">
                    <button type="submit" class="update-chart btn btn-success"><?php _e('Update', 'woo-discount-rules'); ?></button>
                </div>
            </div>
        </form>
        <div id="coupon-container">
            <div class="no-data"></div>
            <div class="list-coupons" style="display: none;">
                <table>
                    <thead>
                    <tr>
                        <th><?php esc_html_e("Discount Label", 'woo-discount-rules'); ?></th>
                        <th><?php esc_html_e("Discounted orders", 'woo-discount-rules'); ?></th>
                        <th><?php esc_html_e("Discounted amount", 'woo-discount-rules'); ?></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>