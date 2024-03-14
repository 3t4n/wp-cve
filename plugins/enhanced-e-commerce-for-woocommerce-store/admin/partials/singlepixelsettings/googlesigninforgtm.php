<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="row pt-3">
    <div class="col-md-1 stepper-parent-div">
        <div class="stepper step-one <?php echo esc_html($stepCls) ?>">1</div>
    </div>
    <div class="col-md-11">
        <div class="convpixsetting-inner-box ">
            <?php if ($g_gtm_email != "") { ?>
                <h5 class="fw-normal mb-1">
                    <?php esc_html_e("Successfully signed in with account:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </h5>
                <span>
                    <?php echo esc_url($g_gtm_email); ?>
                    <span class="conv-link-blue ps-2 tvc_google_signinbtn">
                        <?php esc_html_e("Change", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </span>
                </span>
            <?php } else { ?>
                <div class="google_signing_image tvc_google_signinbtn">
                    <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/btn_google_signin_dark_normal_web.png'); ?>">
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="d-flex" style="height: 40px;padding-left: 23px;">
    <div class="vr"></div>
</div>
