<?php
global $eod_api;
global $eod;
$user_data = $eod->admin->user_data;
$is_free = $eod->admin->get_subscription() === 'free';
$options = get_option('eod_options');
?>
<?php if($is_free){ ?>
<div class="eod_section tariff">
    <div class="name">
        <span>All World Extended</span>
        <label>bundle</label>
    </div>
    <div class="price">$29.99<span>/month</span></div>
    <ul class="eod_checklist">
        <li>30+ years of historical data</li>
        <li>70+ stock exchanges</li>
        <li>Real-time data</li>
        <li>24/7 customer support</li>
    </ul>
    <a class="eod_btn blue big" target="_blank" href="https://eodhd.com/pricing">Get bundle</a>
</div>
<?php } ?>
<div>
    <div class="eod_section">
        <div class="h">Available Data Feeds</div>
        <div><a href="https://eodhd.com/financial-apis/list-supported-exchanges/?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp"
                rel="nofollow" target="_blank">List of Exchanges</a></div>
        <div><a href="https://eodhd.com/financial-apis/list-supported-crypto-currencies/?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp"
                rel="nofollow" target="_blank">List of CRYPTO Currencies</a></div>
<!--        <div><a href="https://eodhd.com/financial-apis/list-supported-futures-commodities/?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp"-->
<!--                rel="nofollow" target="_blank">List of Futures/Commodities</a></div>-->
        <div><a href="https://eodhd.com/financial-apis/list-supported-forex-currencies/?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp"
                rel="nofollow" target="_blank">List of Forex Currencies</a></div>
<!--        <div><a href="https://eodhd.com/financial-apis/list-supported-indices/?utm_source=p_c&utm_medium=wp_plugin&utm_campaign=new_wp"-->
<!--                rel="nofollow" target="_blank">List of Indices</a></div>-->
    </div>
</div>
        
<div>
    <div class="eod_section">
        <div class="h">Your API Key</div>
        <div>
            <form method="post" action="options.php" name="form">
                <input id="eod_option_api_key" name="eod_options[api_key]" size="40" type="text" value="<?= $options['api_key'] ? : EOD_DEFAULT_API ?>" placeholder="Your API key">
                <?php settings_fields('eod_options'); ?>
                <p>
                    <input type="submit" name="submit" id="submit" class="eod_btn blue" value="Save Changes">
                </p>
                <?php do_action('api_key_error'); ?>
            </form>
        </div>
    </div>
</div>

<?php
if( !array_key_exists('error', $user_data) ){
$requests_limit_p = round(100*$user_data['apiRequests']/$user_data['dailyRateLimit'], 2);
?>
<div>
    <div class="eod_section">
        <div class="h">Daily Usage</div>
        <div id="chartdiv" style="width: 100%;height: 100%; font-size: 11px;"></div>
        <div>
            <div class="eod_gray_label">You used</div>
            <p><?= $user_data['apiRequests'] ?> out of <?= $user_data['dailyRateLimit'] ?> API requests <?= $requests_limit_p ?>% of your daily limit.</p>
        </div>
        <div>
            <div class="eod_gray_label">Last day of API usage</div>
            <p><?= $user_data['apiRequestsDate'] ?></p>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['gauge']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Label', 'Value'], ['% of total', <?= $requests_limit_p ?>],
        ]);

        var options = {
            width: 200, height: 200,
            redFrom: 85, redTo: 100,
            yellowFrom: 60, yellowTo: 85,
            minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chartdiv'));
        chart.draw(data, options);
    }
</script>
<?php } ?>