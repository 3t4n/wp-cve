<?php

if(class_exists('RabbitLoader_21_Tab_Geo')){
    #it seems we have a conflict
    return;
}

final class RabbitLoader_21_Tab_Geo extends RabbitLoader_21_Tab_Init{

    public static function init(){
        add_settings_section(
            'rabbitloader_section_geo',
            ' ',
            '',
            'rabbitloader-geo'
        );
        $start_date = date("Y-m-d", strtotime('-30 days'));
        $end_date = date("Y-m-d");
        $top10_format = '
        <div class="col-sm-12 col-md-4">
        <div class="bg-white rounded py-4">
            <div class="row">
                <div class="col-12">
                    <h5 class="px-4">%%TITLE%% <span class="dashicons dashicons-info-outline text-secondary tpopup" title="%%TOOLTIP%% in last 30 days" style="font-size: 16px; line-height: 28px;"></span></h5>
                    <div class="px-4 mt-3" id="%%BODY_ID%%">%%BODY%%</div>
                </div>
            </div>
        </div>
        </div>';
        $tbl = "RLGeoReport.setTop10Format(`$top10_format`);RLGeoReport.initV2(rabbitloader_local_vars.hostname, `".RabbitLoader_21_Core::getRLDomain()."`, `".RabbitLoader_21_Core::getWpOptVal('api_token')."`, `".$start_date."`, `".$end_date."`);";

        self::addDtDependencies();

        wp_enqueue_style( 'rabbitloader-leaflet-css', RABBITLOADER_PLUG_URL . 'admin/css/leaflet.css', [], RABBITLOADER_PLUG_VERSION);
        wp_enqueue_script('rabbitloader-leaflet-js', RABBITLOADER_PLUG_URL . 'admin/js/leaflet.js', [], RABBITLOADER_PLUG_VERSION);
        wp_enqueue_script('rabbitloader-geo-js', RabbitLoader_21_Core::getRLDomain().'account/common/js/report_geo.js', ['rabbitloader-datatable-js', 'rabbitloader-leaflet-js'], RABBITLOADER_PLUG_VERSION);
        wp_add_inline_script('rabbitloader-geo-js', $tbl);
    }

    public static function echoMainContent(){

        do_settings_sections( 'rabbitloader-geo' );

        ?>
        <div class="" style="max-width: 1160px; margin:40px auto;">

            <div class="row mb-4">
                <div class="col-12">
                    <div  id="rl_analytics_map" style="height:400px; border-radius:4px;"></div>
                </div>
            </div>

            <div class="row mb-4" id="rl_top10_locations">
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="bg-white rounded py-4">
                        <div class="row">
                            <div class="col-12 text-secondary">
                                <h5 class="px-4">All Countries</h5>
                                <span class="d-block px-4">Bandwidth usage and total assets loaded in different countries in last 30 days.</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <table class="table rl-table" id="rl_table_country_stats" style="width:100%">
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

?>