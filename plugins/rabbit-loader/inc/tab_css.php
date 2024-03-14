<?php

if(class_exists('RabbitLoader_21_Tab_Css')){
    #it seems we have a conflict
    return;
}

final class RabbitLoader_21_Tab_Css extends RabbitLoader_21_Tab_Init{

    public static function init(){
        add_settings_section(
            'rabbitloader_section_css',
            ' ',
            '',
            'rabbitloader-css'
        );

        $tbl = "RLCssData.initV2(rabbitloader_local_vars.hostname, `".RabbitLoader_21_Core::getRLDomain()."`, `".RabbitLoader_21_Core::getWpOptVal('api_token')."`, `table_page_css`,``);";

        self::addDtDependencies();

        wp_enqueue_script('rabbitloader-css-js', RabbitLoader_21_Core::getRLDomain().'account/common/js/report_css.js', ['rabbitloader-datatable-js'], RABBITLOADER_PLUG_VERSION);
        wp_add_inline_script('rabbitloader-css-js', $tbl);
    }

    public static function echoMainContent(){

        do_settings_sections( 'rabbitloader-css' );

        ?>
        <div class="" style="max-width: 1160px; margin:40px auto;">

            <div class="row mb-4 d-none" id="rl_alert_no_images">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">For recently added websites, CSS optimization report may take up to 1 hour for the first time.</div>
                </div>
            </div>

            <div class="row mb-4">
                <?php 
                echo self::getStatsWidget(0, 'Total Pages','rl_total_pages');
                echo self::getStatsWidget(0, 'Original CSS/Page','rl_original_size');
                echo self::getStatsWidget(0, 'Optimized CSS/Page','rl_p1_size');
                echo self::getStatsWidget(0, 'Improvement','rl_reductio_p');
                ?>
            </div>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded p-4">
                        <div class="row">
                            <div class="text-center col border-right">
                                <h4 class="" id="rl_lt_or">0 sec</h4>
                                <span class="text-secondary mt-2">Average render time before optimization</span>
                            </div>
                            <div class="text-center col">
                                <h4 class="" id="rl_lt_op">0 sec</h4>
                                <span class="text-secondary mt-2">Average render time after optimization</span>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="row mb-4">
                <div class="col">
                    <div class="bg-white rounded py-4">
                        <div class="row">
                            <div class="col-12 text-secondary">
                                <h5 class="px-4">Critical CSS Details</h5>
                                <span class="d-block px-4">CSS optimization details for pages detected on this website. For every page, critical CSS is generated to render the page in fastest possible way.</span>
                            </div>
                            <div class="mt-4">
                                <table class="table rl-table" id="table_page_css" style="width:100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    private static function getStatsWidget($val, $lbl, $id){
        $html = '
        <div class="col">
            <div class="bg-white rounded p-4">
                <h4 class="" id="'.$id.'">'.$val.'</h4>
                <span class="text-secondary mt-2">'.RL21UtilWP::__($lbl).'</span>
            </div>
        </div>';
        return $html;
    }
}

?>