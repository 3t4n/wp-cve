<?php
if (!defined('WPINC')) {
    die('Closed');
}
  if(isset($rm_promo_banner_title))
      $title = $rm_promo_banner_title;
  else
      $title = __('Upgrade and expand the power of','custom-registration-form-builder-with-submission-manager');
?>
<?php if(!defined('REGMAGIC_ADDON')) { ?>
<div class="rm-upgrade-note-gold">        
        <div class="rm-banner-title"><?php echo esc_html($title); ?><img src="<?php echo esc_url(RM_IMG_URL).'logo.png'?>"> </div>
        <div class="rm-banner-box"><a href="<?php echo esc_url(RM_Utilities::comparison_page_link()); ?>" target="_blank"><img src="<?php echo esc_url(RM_IMG_URL).'premium-logo.png'?>"></a>
        </div>
</div>
<?php } else { ?>
<div class="rm-customize-banner-main">
<div class="pg-customize-banner-row rm-box-row">
    <div class="rm-box-col-12">
        <div class="rm-customize-banner-wrap rm-d-flex rm-justify-content-between rm-box-center rm-p-3 rm-box-w-100 rm-white-bg ">
            <div class="rm-customize-banner-logo"><img width="128" src="<?php echo esc_url(RM_IMG_URL).'rm-logo.png'?>"></div>
            <div class="rm-banner-pitch-content-wrap rm-lh-normal">
                <div class="rm-banner-pitch-head rm-fs-2 rm-fw-bold">
                    Customize RegistrationMagic                                            
                </div>
                <div class="rm-banner-pitch-content rm-fs-5 rm-text-muted">
                    Have our team build the exact feature that you need.                                            
                </div>
            </div>

            <div class="rm-banner-btn-wrap">
                <a target="_blank" href="https://registrationmagic.com/customizations/" class=""><button class="button button-primary rm-customize-banner-btn">Get Help Now</button></a>
            </div>


        </div>
    </div>
</div>
</div>
<?php } ?>
<style>
    
.rm-customize-banner-main{
        width: 100%;
        float: left;
    }
    
.rm-customize-banner-wrap {
    width: 100%;
    max-width: 840px;
    margin: 0px auto;
    border: 1px solid #dcdada;
    border-radius: 3px;
    box-shadow: 1px 1px 3px 2px rgb(215 215 215 / 26%);
    background-color: #fff;
    justify-content: space-between;
    padding: 1rem!important;
    margin-top: 30px;
    
}

.rm-banner-pitch-head {
    font-size: 2rem!important;
    font-weight: 700;
    line-height: normal!important;
}

.rm-banner-pitch-content{
    opacity: 1;
    font-size: 1.25rem!important
    color: #6c757d!important;
}

.rm-banner-btn-wrap button.rm-customize-banner-btn {
    vertical-align: top;
    transition: .2s;
    padding: 4px 20px;
    font-size: 15px;
}
    
    </style>