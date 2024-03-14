<?php

if (class_exists('RabbitLoader_21_Tab_Usage')) {
    #it seems we have a conflict
    return;
}

final class RabbitLoader_21_Tab_Usage extends RabbitLoader_21_Tab_Init
{

    public static function init()
    {
        add_settings_section(
            'rabbitloader_section_usage',
            ' ',
            '',
            'rabbitloader-usage'
        );
        $start_date = date("Y-m-d", strtotime('-30 days'));
        $end_date = date("Y-m-d");
        $tbl = "RLUsageData.initV2(rabbitloader_local_vars.hostname, `" . RabbitLoader_21_Core::getRLDomain() . "`, `" . RabbitLoader_21_Core::getWpOptVal('api_token') . "`, `" . $start_date . "`, `" . $end_date . "`);";
        //const elBW = document.querySelector('#d3-area-stacked-nest-bandwidth');charts.mountBW(elBW, false)

        $tbl = "
        const charts = window.rlCharts.Charts

        const elPV = document.querySelector('#d3-area-stacked-nest-pageview');
        charts.mountPV(elPV, false)

        charts.init({
            apiHost: `" . RabbitLoader_21_Core::getRLDomainV2() . "`,
            domainID: `" . RabbitLoader_21_Core::getWpOptVal('did') . "`,
            jwt: `" . RabbitLoader_21_Core::getWpOptVal('api_token') . "`,
        })
        charts.setDate(`" . $start_date . "`, `" . $end_date . "`)";

        wp_enqueue_script('rabbitloader-usage-js', 'https://cfw.rabbitloader.xyz/rl/mfe/rl.charts.v3.10.8.js', [], null);
        wp_add_inline_script('rabbitloader-usage-js', $tbl);
    }

    public static function echoMainContent()
    {

        do_settings_sections('rabbitloader-usage');
        $overview = self::getOverviewData($apiError, $apiMessage);

?>
        <div class="" style="max-width: 1160px; margin:40px auto;">
            <div class="row mb-4">
                <div class="col-sm-12 col-md-4">
                    <?php self::quota_used_box($overview, false); ?>
                </div>
                <div class="col-sm-12 col-md-4">
                    <?php self::quota_remaining_box($overview); ?>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="bg-white rounded p-4 tpopup" title="<?php RL21UtilWP::_e(sprintf('Your usage cycle will be reset on %s',  date('jS M, Y', strtotime($overview['plan_end_date'])))); ?>">
                        <h4 class="">
                            <?php echo date('jS', strtotime($overview['plan_end_date'])); ?> <small style="font-size:14px;"><?php echo date('M', strtotime($overview['plan_end_date'])); ?></small>
                        </h4>
                        <span class="text-secondary mt-2"><?php RL21UtilWP::_e('Next Quota Reset'); ?></span>
                    </div>
                </div>
            </div>

            <!-- <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded pb-2">
                        <div class="mb-4" id="d3-area-stacked-nest-bandwidth" style="height:400px; width:100%;max-width:100%;border: none; box-shadow:none;"></div>
                    </div>
                </div>
            </div> -->

            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded pb-2">
                        <div class="" id="d3-area-stacked-nest-pageview" style="height:400px; width:100%;max-width:100%;border: none; box-shadow:none;"></div>
                    </div>
                </div>
            </div>

        </div>
<?php
    }
}

?>