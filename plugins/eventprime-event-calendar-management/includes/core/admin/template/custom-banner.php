<?php 
defined( 'ABSPATH' ) || exit;

if( ! empty( ep_check_for_premium_extension_installed() ) ) {?>
    <div class="ep-customize-banner-main emagic ep-box-w-100" style="float:left">
        <div class="ep-customize-banner-row ep-box-row">
            <div class="ep-box-col-12">
                <div class="ep-customize-banner-wrap ep-d-flex ep-justify-content-between ep-align-items-center ep-p-3 ep-box-w-100 ep-bg-white ">
                    <div class="ep-customize-banner-logo">
                        <img width="128" src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/ep-logo-icon.svg'); ?>" >
                    </div>
                    <div class="ep-banner-pitch-content-wrap ep-lh-normal">
                        <div class="ep-banner-pitch-head ep-fs-2 ep-fw-bold">
                            Customize EventPrime                                            
                        </div>
                        <div class="ep-banner-pitch-content ep-fs-6 ep-text-muted">
                            Have our team build the exact feature that you need.                                            
                        </div>
                    </div>
                    <div class="ep-banner-btn-wrap">
                        <a target="_blank" href="<?php echo esc_url( 'https://theeventprime.com/customizations/' );?>">
                            <button class="button button-primary rm-customize-banner-btn">Get Help Now</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
} else{?>
    <div class="ep-premium-banner-main emagic ep-box-w-100" style="float:left">
        <div class="ep-customize-banner-row ep-box-row">
            <div class="ep-box-col-12">
                <div class="ep-customize-banner-wrap ep-d-flex ep-justify-content-between ep-align-items-center ep-p-3 ep-box-w-100 ep-bg-white ep-text-center">
                    <div class="ep-customize-banner-logo">
                        <img width="128" src="<?php echo esc_url( EP_BASE_URL . 'includes/assets/images/ep-logo-icon.svg'); ?>" >
                    </div>
                    <div class="ep-banner-pitch-content-wrap ep-lh-normal">
                        <div class="ep-banner-pitch-head ep-fs-2 ep-fw-bold">
                            Extend the power of EventPrime                                           
                        </div>
                        <div class="ep-banner-pitch-content ep-fs-5 ep-text-muted ">
                            <strong>Free</strong> and paid extensions now available!                                            
                        </div>
                    </div>
                    <div class="ep-banner-btn-wrap">
                        <a target="_blank" href="<?php echo esc_url( 'https://theeventprime.com/all-extensions/' );?>">
                            <button class="button button-primary rm-customize-banner-btn">Download Now</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div><?php
}?>

<style>
    
/*--Customization Banner--*/

.ep-customize-banner-main {}

.ep-customize-banner-wrap {
    max-width: 700px;
    margin: 30px auto;
    box-shadow: 1px 1px 3px 2px rgb(215 215 215 / 26%);
}

.ep-premium-banner-main .ep-customize-banner-wrap{
    max-width: 840px;
    margin: 30px auto;
    box-shadow: 1px 1px 3px 2px rgb(215 215 215 / 26%);
}
 /*--Customization Banner End--*/
</style>

