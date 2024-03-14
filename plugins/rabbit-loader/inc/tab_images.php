<?php

if(class_exists('RabbitLoader_21_Tab_Images')){
    #it seems we have a conflict
    return;
}

final class RabbitLoader_21_Tab_Images extends RabbitLoader_21_Tab_Init{

    public static function init(){
        add_settings_section(
            'rabbitloader_section_image',
            ' ',
            '',
            'rabbitloader-image'
        );

        $tbl = "RLImageData.initV2(rabbitloader_local_vars.hostname, `".RabbitLoader_21_Core::getRLDomain()."`, `".RabbitLoader_21_Core::getWpOptVal('api_token')."`);";

        wp_enqueue_script('rabbitloader-img-js', RabbitLoader_21_Core::getRLDomain().'account/common/js/report_img.js', [], RABBITLOADER_PLUG_VERSION);
        wp_add_inline_script('rabbitloader-img-js', $tbl);
    }

    public static function echoMainContent(){

        do_settings_sections( 'rabbitloader-image' );

        ?>
        <div class="" style="max-width: 1160px; margin:40px auto;">

            <div class="row mb-4 d-none" id="rl_alert_no_images">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">For recently added websites, Images optimization report may take up to 1 hour for the first time.</div>
                </div>
            </div>

            <div class="row mb-4">
                <?php 
                echo self::getStatsWidget(0, 'Total Images','rl_total_images');
                echo self::getStatsWidget(0, 'Original Size','rl_original_size');
                echo self::getStatsWidget(0, 'Optimized Size (WebP)','rl_webp_size');
                echo self::getStatsWidget(0, 'Size Improvement','rl_reductio_p');
                ?>
            </div>
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <a href="https://rabbitloader.com/kb/enable-image-auto-conversion-to-webp/" target="_blank" class="text-secondary">seeing less than expected total image count?</a>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12">
                    <div class="bg-white rounded p-4">
                        <div class="row">
                            <div class="text-center col border-right">
                                <h4 class="" id="rl_lt_or">0 sec</h4>
                                <span class="text-secondary mt-2">Average loadtime before optimization</span>
                            </div>
                            <div class="text-center col">
                                <h4 class="" id="rl_lt_op">0 sec</h4>
                                <span class="text-secondary mt-2">Average loadtime after optimization</span>
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
                                <h5 class="px-4">Image Sampling</h5>
                                <span class="d-block px-4">Sample images optimized and served to visitors. The optimized copy of images are stored in our CDN edge location servers.</span>
                            </div>
                            <div class="col-12">
                                <div class="p-4" id="img_recent">
                                
                                </div>
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