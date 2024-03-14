<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$TVC_Admin_Helper = new TVC_Admin_Helper();
$ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
$tvc_data = $TVC_Admin_Helper->get_store_data();
if ((isset($_GET['g_mail']) && sanitize_text_field($_GET['g_mail'])) && (isset($_GET['subscription_id']) && sanitize_text_field($_GET['subscription_id']))) {
    if (isset($_GET['wizard_channel']) && sanitize_text_field($_GET['wizard_channel']) == "gtmsettings") {
        update_option('ee_customer_gtm_gmail', sanitize_email($_GET['g_mail']));
        $red_url = 'admin.php?page=conversios&wizard=pixelandanalytics';
        //header("Location: ".$red_url);
    }

    if (isset($_GET['wizard_channel']) && (sanitize_text_field($_GET['wizard_channel']) == "gasettings" || sanitize_text_field($_GET['wizard_channel']) == "gadssettings")) {
        update_option('ee_customer_gmail', sanitize_email($_GET['g_mail']));
        if (array_key_exists("access_token", $tvc_data) && array_key_exists("refresh_token", $tvc_data)) {
            $eeapidata = unserialize(get_option('ee_api_data'));
            $eeapidata_settings = new stdClass();
            // if (!empty($eeapidata['setting'])) {
            //     $eeapidata_settings = $eeapidata['setting'];
            // }
            // $eeapidata_settings->access_token = base64_encode(sanitize_text_field($tvc_data["access_token"]));
            // $eeapidata_settings->refresh_token = base64_encode(sanitize_text_field($tvc_data["refresh_token"]));
            // $eeapidata['setting'] = $eeapidata_settings;
            // update_option('ee_api_data', serialize($eeapidata));
        }

        //is not work for existing user && $ee_additional_data['con_created_at'] != "" 
        if (isset($ee_additional_data['con_created_at'])) {
            $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
            $ee_additional_data['con_updated_at'] = gmdate('Y-m-d');
            $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
        } else {
            $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
            $ee_additional_data['con_created_at'] = gmdate('Y-m-d');
            $ee_additional_data['con_updated_at'] = gmdate('Y-m-d');
            $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
        }
    }
}



$ee_options = unserialize(get_option("ee_options"));
$ee_api_data_all = unserialize(get_option("ee_api_data"));
$ee_api_data = $ee_api_data_all['setting'];
$plan_id = $ee_api_data->plan_id;
$store_id = $ee_api_data->store_id;

// From gtm file
$g_gtm_email = get_option('ee_customer_gtm_gmail');
// perform validation on the user email
$g_gtm_email =  ($g_gtm_email != '') ? $g_gtm_email : "";
$stepCls = $g_gtm_email != "" ? "" : "stepper-conv-bg-grey";
$disableTextCls = $g_gtm_email != "" ? "" : "conv-link-disabled";
$select2Disabled = $g_gtm_email != "" ? "" : "disabled";

$gtm_account_id = isset($ee_options['gtm_settings']['gtm_account_id']) ? $ee_options['gtm_settings']['gtm_account_id'] : "";
$gtm_container_id = isset($ee_options['gtm_settings']['gtm_container_id']) ? $ee_options['gtm_settings']['gtm_container_id'] : "";
$gtm_container_publicId = isset($ee_options['gtm_settings']['gtm_public_id']) ? $ee_options['gtm_settings']['gtm_public_id'] : "";
$gtm_account_container_name = isset($ee_options['gtm_settings']['gtm_account_container_name']) ? $ee_options['gtm_settings']['gtm_account_container_name'] : "";
$is_gtm_automatic_process = isset($ee_options['gtm_settings']['is_gtm_automatic_process']) ? $ee_options['gtm_settings']['is_gtm_automatic_process'] : false;
$automation_status =  isset($ee_options['gtm_settings']['status']) ? $ee_options['gtm_settings']['status'] : "";

$selectedGtmEvents = isset($ee_options['gtm_channel_settings']) ? $ee_options['gtm_channel_settings'] : [];

// From single pixel main
$googleDetail = $ee_api_data;
$tracking_option = "UA";
$login_customer_id = "";

$customApiObj = new CustomApi();
$app_id = 1;
//get user data
$ee_options = $TVC_Admin_Helper->get_ee_options_settings();

$get_ee_options_data = $TVC_Admin_Helper->get_ee_options_data();

$subscriptionId =  $ee_options['subscription_id'];

$url = $TVC_Admin_Helper->get_onboarding_page_url();
$is_refresh_token_expire = false; //$TVC_Admin_Helper->is_refresh_token_expire();

//get badge settings
$convBadgeVal = isset($ee_options['conv_show_badge']) ? $ee_options['conv_show_badge'] : "";
$convBadgePositionVal = isset($ee_options['conv_badge_position']) ? $ee_options['conv_badge_position'] : "";

//check last login for check RefreshToken
$g_mail = get_option('ee_customer_gmail');
$cust_g_email = $g_mail;

$tvc_data['g_mail'] = "";
if ($g_mail) {
    $tvc_data['g_mail'] = sanitize_email($g_mail);
}
$TVC_Admin_Helper = new TVC_Admin_Helper();

//get account settings from the api
if ($subscriptionId != "") {
    $google_detail = $customApiObj->getGoogleAnalyticDetail($subscriptionId);
    if (property_exists($google_detail, "error") && $google_detail->error == false) {
        if (property_exists($google_detail, "data") && $google_detail->data != "") {
            $googleDetail = $google_detail->data;
            $tvc_data['subscription_id'] = $googleDetail->id;
            $tvc_data['access_token'] = base64_encode(sanitize_text_field($googleDetail->access_token));
            $tvc_data['refresh_token'] = base64_encode(sanitize_text_field($googleDetail->refresh_token));
            $plan_id = $googleDetail->plan_id;
            $login_customer_id = $googleDetail->customer_id;
            $tracking_option = $googleDetail->tracking_option;
            if ($googleDetail->tracking_option != '') {
                $defaulSelection = 0;
            }
        }
    }

    // $tvc_data['subscription_id'] = $subscriptionId;
    // $tvc_data['access_token'] = "";
    // $tvc_data['refresh_token'] = "";
}

$pixel_setting = array(
    "gtmsettings" => isset($ee_options['tracking_method']) && $ee_options['tracking_method'] == 'gtm' ? 'convo-active' : 'gtmnotconnected',
    "gasettings" => (isset($ee_options['gm_id']) && $ee_options['gm_id'] != '') || (isset($ee_options['gm_id']) && $ee_options['gm_id'] != '') ? 'convo-active' : '',
    "gadssettings" => isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] != '' ? 'convo-active' : '',
    "fbsettings" => isset($ee_options['fb_pixel_id']) && $ee_options['fb_pixel_id'] != '' ? 'convo-active' : '',
    "bingsettings" => isset($ee_options['microsoft_ads_pixel_id']) && $ee_options['microsoft_ads_pixel_id'] != '' ? 'convo-active' : '',
    "twittersettings" => isset($ee_options['twitter_ads_pixel_id']) && $ee_options['twitter_ads_pixel_id'] != '' ? 'convo-active' : '',
    "pintrestsettings" => isset($ee_options['pinterest_ads_pixel_id']) && $ee_options['pinterest_ads_pixel_id'] != '' ? 'convo-active' : '',
    "snapchatsettings" => isset($ee_options['snapchat_ads_pixel_id']) && $ee_options['snapchat_ads_pixel_id'] != '' ? 'convo-active' : '',
    "tiktoksettings" => isset($ee_options['tiKtok_ads_pixel_id']) && $ee_options['tiKtok_ads_pixel_id'] != '' ? 'convo-active' : '',
    "hotjarsettings" => isset($ee_options['hotjar_pixel_id']) && $ee_options['hotjar_pixel_id'] != '' ? 'convo-active' : '',
    "crazyeggsettings" => isset($ee_options['crazyegg_pixel_id']) && $ee_options['crazyegg_pixel_id'] != '' ? 'convo-active' : '',
    "claritysettings" => isset($ee_options['msclarity_pixel_id']) && $ee_options['msclarity_pixel_id'] != '' ? 'convo-active' : ''
);
$pixelprogressbarclass = [];
$gtmtabicon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
$gatabicon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
$gadstabicon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
array_push($pixelprogressbarclass, 0);

if ($pixel_setting['gtmsettings'] == "convo-active") {
    array_push($pixelprogressbarclass, 33);
    $gtmtabicon = '<span class="material-symbols-outlined text-success">check_circle</span>';
}
if ($pixel_setting['gasettings'] == "convo-active") {
    array_push($pixelprogressbarclass, 33);
    $gatabicon = '<span class="material-symbols-outlined text-success">check_circle</span>';
}
if ($pixel_setting['gadssettings'] == "convo-active" || $pixel_setting['fbsettings'] == "convo-active" || $pixel_setting['snapchatsettings'] == "convo-active" || $pixel_setting['tiktoksettings'] == "convo-active" || $pixel_setting['pintrestsettings'] == "convo-active" || $pixel_setting['bingsettings'] == "convo-active" || $pixel_setting['tiktoksettings'] == "convo-active") {
    array_push($pixelprogressbarclass, 33);
    $gadstabicon = '<span class="material-symbols-outlined text-success">check_circle</span>';
}


?>
<style>
    body {
        background: white !important;
    }

    #conversioshead,
    #conversioshead_notice {
        display: none;
    }

    .progressinfo {
        text-align: right;
        font-size: 12px;
        line-height: 16px;
        color: #515151;
        margin-top: 9px;
    }

    .progress {
        display: -ms-flexbox;
        display: flex;
        height: 10px;
        overflow: hidden;
        line-height: 0;
        background-color: #F3F3F3;
        border-radius: 100px;
    }

    .progress-bar {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: center;
        justify-content: center;
        overflow: hidden;
        color: #fff;
        text-align: left;
        padding-left: 24px;
        white-space: nowrap;
        background: #1085F1;
        transition: width 0.6s ease;
        border-radius: 100px;
    }
</style>
<script>
    function conv_change_loadingbar(state = 'show') {
        if (state === 'show') {
            jQuery("#loadingbar_blue").removeClass('d-none');
            jQuery("#wpbody").css("pointer-events", "none");
            jQuery("#convwizard_main").addClass("disabledsection");
        } else {
            jQuery("#loadingbar_blue").addClass('d-none');
            jQuery("#wpbody").css("pointer-events", "auto");
            jQuery("#convwizard_main").removeClass("disabledsection");
        }
    }

    function conv_change_loadingbar_popup(state = 'show') {
        if (state === 'show') {
            setTimeout(function() {
                jQuery(".modal.show").find(".topfull_loader").removeClass('d-none');
                jQuery(".modal:visible").find(".modal-content").css("pointer-events", "none");
            }, 1000);
        } else {
            jQuery(".modal:visible").find(".topfull_loader").addClass('d-none');
            jQuery(".modal:visible").find(".modal-content").css("pointer-events", "auto");
        }
    }

    function showtoastdynamically(content) {
        jQuery("#dynamictoastbody").html(content);
        jQuery('.toast').toast('show');
    }


    function getAlertMessageAll(type = 'Success', title = 'Success', message = '', icon = 'success', buttonText = 'Ok, Done', buttonColor = '#1085F1', iconImageTag = '') {

        Swal.fire({
            type: type,
            icon: icon,
            title: title,
            confirmButtonText: buttonText,
            confirmButtonColor: buttonColor,
            text: message,
        })
        let swalContainer = Swal.getContainer();
        jQuery(swalContainer).find('.swal2-icon-show').removeClass('swal2-' + icon).removeClass('swal2-icon')
        jQuery('.swal2-icon-show').html(iconImageTag)

    }
</script>

<div aria-live="polite" aria-atomic="true" class="bg-dark position-relative bd-example-toasts">
    <div id="convdynamictoast" class="toast-container position-absolute p-3 top-0 end-0" id="toastPlacement">
        <div class="toast text-white bg-primary" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Opps</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div id="dynamictoastbody" class="toast-body"></div>
        </div>
    </div>
</div>



<div id="convwizard_main" class="container container-old conv-container conv-setting-container">
    <div class="row">
        <div class="mx-auto d-flex justify-content-end" style="max-width: 930px;">
            <div class="text-dark m-4 h6 d-flex align-items-center convexitwizard" data-bs-toggle="modal" data-bs-target="#exitwizardconvmodal">
                <span class="material-symbols-outlined">
                    cancel
                </span>
                <span><?php esc_html_e("Exit Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
            </div>
        </div>
        <div class="mx-auto convcard p-0 mt-0 rounded-3 shadow-lg" style="max-width: 903px;">
            <div id="loadingbar_blue" class="progress-materializecss d-none ps-2 pe-2 w-100 topfull_loader">
                <div class="indeterminate"></div>
            </div>
            <ul class="nav nav-tabs border-0 p-3 pb-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="d-inline-flex align-items-center pawizard_tab_but border-0 nav-link active ps-0" id="gtmbox-tab" data-bs-toggle="tab" data-bs-target="#gtmbox" type="button" role="tab" aria-controls="gtmbox" aria-selected="true">
                        <?php echo wp_kses_post($gtmtabicon); ?>
                        <h5 class="text-start m-0 ps-1">
                            <?php esc_html_e("Google Tag Manager", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </h5>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="d-inline-flex align-items-center pawizard_tab_but border-0 nav-link" id="webpixbox-tab" data-bs-toggle="tab" data-bs-target="#webpixbox" type="button" role="tab" aria-controls="webpixbox" aria-selected="false">
                        <?php echo wp_kses_post($gatabicon); ?>
                        <h5 class="text-start m-0 ps-1">
                            <?php esc_html_e("Google Analytics 4,Hotjar & Crazy Egg", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </h5>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="d-inline-flex align-items-center pawizard_tab_but border-0 nav-link px-0" id="webadsbox-tab" data-bs-toggle="tab" data-bs-target="#webadsbox" type="button" role="tab" aria-controls="webadsbox" aria-selected="false">
                        <?php echo wp_kses_post($gadstabicon); ?>
                        <h5 class="text-start m-0 ps-1">
                            <?php esc_html_e("Track Conversions & Build Audiences", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </h5>
                    </button>
                </li>
            </ul>

            <div class="tab-content p-3 pt-0" id="myTabContent">
                <div class="progress">
                    <div class="progress-bar w-<?php echo esc_attr(array_sum($pixelprogressbarclass)); ?>" style="width:<?php echo esc_attr(array_sum($pixelprogressbarclass)); ?>%" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="tab-pane fade show active" id="gtmbox" role="tabpanel" aria-labelledby="gtmbox-tab">
                    <?php require_once("wizardsettings/gtmsettings.php"); ?>
                </div>
                <div class="tab-pane fade" id="webpixbox" role="tabpanel" aria-labelledby="webpixbox-tab">
                    <?php require_once("wizardsettings/gasettings.php"); ?>
                </div>
                <div class="tab-pane fade" id="webadsbox" role="tabpanel" aria-labelledby="webadsbox-tab">
                    <?php require_once("wizardsettings/gadssettings.php"); ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="overlayanimation" class="overlay_loader_conv">
    <div class="overlay_loader_conv-content loaderopen-content d-none">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <div class="overlay_loader_conv-content loadershow-content d-none">
        <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/success_check_mark.gif'); ?>">
        <div class="overlaycontentbox"></div>
    </div>
</div>



<!-- Exit Wizard modal -->
<div class="modal fade" id="exitwizardconvmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-3">
                <p class="m-4 text-center h5"><?php esc_html_e("Are you sure you want to exit the setup?", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
            </div>
            <div class="modal-footer p-4">
                <div class="m-auto">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                        <?php esc_html_e("Continue Setup", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                    <a href="<?php echo esc_url('admin.php?page=conversios'); ?>" class="btn btn-primary">
                        <?php esc_html_e("Exit Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Exit wizard modal End -->

<!-- Upgrade to PRO modal -->
<!-- Modal -->
<div class="modal fade" id="upgradetopromodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-body p-4 pb-0">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-8">
                            <h3 class="fw-bold text-uppercase pt-0">
                                <?php esc_html_e("Upgrade to Pro &", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <br><?php esc_html_e("Unlock Exclusive Benefits Today!", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h3>
                            <ul class="conv-upgrade-banner-list ps-4 pt-4">
                                <li>
                                    <?php esc_html_e("Personalize your GTM by integrating and automating with over 70 tags.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </li>
                                <li>
                                    <?php esc_html_e("Track conversions precisely and create dynamic remarketing audiences across", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <br><?php esc_html_e("Google Ads, Facebook, TikTok, Snapchat, and over 8 other advertising platforms.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </li>
                                <li>
                                    <?php esc_html_e("Simplify Conversions APIs for Meta, Tiktok and Snapchat with our quick", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <br><?php esc_html_e("installation feature.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </li>
                                <li>
                                    <?php esc_html_e("Explore detailed insights with our Ecommerce Reporting Dashboard.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </li>
                                <li>
                                    <?php esc_html_e("Enjoy seamless Unlimited Product Feed synchronization via our robust Content", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <?php esc_html_e("API.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </li>
                                <li>
                                    <?php esc_html_e("Benefit from a complimentary website audit, a dedicated success manager, and", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <?php esc_html_e("prioritized Slack support.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </li>
                            </ul>
                        </div>
                        <div class="col-4 ms-auto">
                            <div class="col-12">
                                <button type="button" class="btn-close btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="col-12">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgradetopro_popup_img.png'); ?>">
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer border-0 pb-4 mb-1 pt-4">
                <a id="upgradetopro_modal_link" class="btn conv-yellow-bg m-auto w-100 mx-4 p-2" href="https://www.conversios.io/wordpress/all-in-one-google-analytics-pixels-and-product-feed-manager-for-woocommerce-pricing/?utm_source=in_app&utm_medium=modal_popup&utm_campaign=upgrade" target="_blank">
                    <?php esc_html_e("Activate Your Pro Upgrade Today!", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Upgrade to PRO modal End -->

<!-- Modal SST Pro-->
<div class="modal fade upgradetosstmodal" id="convSsttoProModal" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="modal-content">

            <h2><?php esc_html_e("Unlock The benefits of", "enhanced-e-commerce-for-woocommerce-store"); ?> <br> <span><?php esc_html_e("Server Side Tagging Via GTM", "enhanced-e-commerce-for-woocommerce-store"); ?></span> </h2>
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <ul class="listing">
                        <span><?php esc_html_e("Benefits", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        <li><?php esc_html_e("Adopt To First Party Cookies", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                        <li><?php esc_html_e("Improve Data Accuracy & Reduced Ad Blocker Impact", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                        <li><?php esc_html_e("Faster Page Speed", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                        <li><?php esc_html_e("Enhanced Data Privacy & Security", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <ul class="listing">
                        <span><?php esc_html_e("Features", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        <li><?php esc_html_e("Server Side Tagging Via GTM", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                        <li><?php esc_html_e("Powerful Google Cloud Servers", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                        <li><?php esc_html_e("Custom Loader & Custom Domain Mapping", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                        <li><?php esc_html_e("Server Side Tagging For Google Analytics 4 (GA4), Google Ads, Facebook CAPI, Tiktok Events API & Snapchat CAPI", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                        <li><?php esc_html_e("Free Setup & Audit By Dedicated Customer Success Manager", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                    </ul>
                </div>
                <div class="col-12">
                    <div class="discount-btn">
                        <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/wordpress/all-in-one-google-analytics-pixels-and-product-feed-manager-for-woocommerce-pricing/?utm_source=in_app&utm_medium=modal_popup&utm_campaign=sstpopup'); ?>" class="btn btn-dark common-btn">Get Early Bird Discount</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal SST Pro End -->

<div class="modal fade" id="wizsetconfirm" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="wizsetconfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body">

                <div id="conv_pixel_list_box">

                    <h4 class="h6 conv-link-blue mb-2 mt-4"><?php esc_html_e("Google Tag Manager", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
                    <div style="width: 100%;" pixelname="gtmsettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img style="width: 47px;" class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gtm_logo.png'); ?>" />
                        </div>

                        <div class="p-1 ps-3 align-self-center">
                            <span class="fw-bold m-0"><?php esc_html_e("Google Tag Manager", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                            <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                        </div>
                        <div class="p-1 ps-3 align-self-center convpixstatus">
                            <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                            <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                        </div>

                    </div>

                    <h4 class="h6 conv-link-blue mb-2 mt-4"><?php esc_html_e("Google Analytics 4,Hotjar & Crazy Egg", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
                    <div pixelname="gasettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_ganalytics_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Google Analytics 4", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>


                    <div pixelname="hotjarsettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_hotjar_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Hotjar Pixel ID", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>


                    <div pixelname="claritysettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_clarity_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Microsoft Clarity", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                    <div pixelname="crazyeggsettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_crazyegg_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Crazy Egg", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                    <h4 class="h6 conv-link-blue mb-2 mt-4"><?php esc_html_e("Track Conversions & Build Audiences", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
                    <div pixelname="gadssettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gads_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Google Ads Account", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                    <div pixelname="fbsettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_meta_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Meta (Facebook) Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                    <div pixelname="snapchatsettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_snap_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Snapchat Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                    <div pixelname="tiktoksettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_tiktok_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Tiktok Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                    <div pixelname="pintrestsettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_pint_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Pinterest Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                    <div pixelname="bingsettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_bing_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Microsoft Ads Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                    <div pixelname="twittersettings" class="shadow convcard conv-pixel-list-item d-inline-flex p-1 mt-1 rounded-top conv-gtm-connected">
                        <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
                            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_twitter_logo.png'); ?>" />
                        </div>
                        <div class="conconfpop_text">
                            <div class="p-1 ps-3 align-self-center">
                                <span class="fw-bold m-0"><?php esc_html_e("Twitter Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                <div class="d-flex pt-1"><span class="channel_pixelid"></span></div>
                            </div>
                            <div class="p-1 ps-3 align-self-center convpixstatus">
                                <span class="d-none isconnected badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
                                <span class="d-none isnotconnected badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer justify-content-end d-flex">
                <div class="col-6 m-0 text-center">
                    <button id="starttrackingbut_wizard" type="button" class="col-11 btn btn-primary btn btn-primary m-0">
                        <h4 class="text-white text-center"><?php esc_html_e("Event Tracking Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
                        <small><?php esc_html_e("See in real time if events are being tracked correctly on your website.", "enhanced-e-commerce-for-woocommerce-store"); ?></small>
                    </button>
                </div>

                <div class="col-3 m-0">
                    <a class="m-0" href="<?php echo esc_url('admin.php?page=conversios'); ?>">
                        <h4 class="conv-link-blue text-center">
                            <u><?php esc_html_e("Go to Dashboard", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                        </h4>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Google Sign In -->
<?php
$connect_url_gagads = $tvs_admin->get_custom_connect_url_wizard(admin_url() . 'admin.php?page=conversios&wizard=pixelandanalytics_gasettings');
$connect_url_gaa = $tvs_admin->get_custom_connect_url_wizard(admin_url() . 'admin.php?page=conversios&wizard=pixelandanalytics_gasettings');
$connect_url_gadss = $tvs_admin->get_custom_connect_url_wizard(admin_url() . 'admin.php?page=conversios&wizard=pixelandanalytics_gadssettings');
require_once ENHANCAD_PLUGIN_DIR . 'admin/partials/singlepixelsettings/googlesigninforga.php';
?>

<?php
// echo '<pre>';
// print_r($ee_options);
// echo '</pre>';
?>
<script>
    function changeseekbar() {
        console.log('Update seekbar');
        var pixel_setting = [];
        pixel_setting["gtmsettings"] = jQuery("#tracking_method").val() != "" ? "convo-active" : "";
        pixel_setting["gasettings"] = jQuery("#ga4_property_id").val() != "" && jQuery("#ga4_property_id").val() != null ? "convo-active" : "";
        pixel_setting["gadssettings"] = jQuery("#google_ads_id").val() != "" && jQuery("#google_ads_id").val() != null ? "convo-active" : "";
        pixel_setting["fbsettings"] = jQuery("#fb_pixel_id").val() != "" ? "convo-active" : "";
        pixel_setting["bingsettings"] = jQuery("#microsoft_ads_pixel_id").val() != "" ? "convo-active" : "";
        pixel_setting["twittersettings"] = jQuery("#twitter_ads_pixel_id").val() != "" ? "convo-active" : "";
        pixel_setting["pintrestsettings"] = jQuery("#pinterest_ads_pixel_id").val() != "" ? "convo-active" : "";
        pixel_setting["snapchatsettings"] = jQuery("#snapchat_ads_pixel_id").val() != "" ? "convo-active" : "";
        pixel_setting["tiktoksettings"] = jQuery("#tiKtok_ads_pixel_id").val() != "" ? "convo-active" : "";
        pixel_setting["hotjarsettings"] = jQuery("#hotjar_pixel_id").val() != "" ? "convo-active" : "";
        pixel_setting["crazyeggsettings"] = jQuery("#crazyegg_pixel_id").val() != "" ? "convo-active" : "";
        pixel_setting["claritysettings"] = jQuery("#msclarity_pixel_id").val() != "" ? "convo-active" : "";

        
        var pixelprogressbarclass = [];
        var gtmtabicon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
        var gatabicon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
        var gadstabicon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
        pixelprogressbarclass.push(1);

        if (pixel_setting['gtmsettings'] == "convo-active") {
            pixelprogressbarclass.push(33);
            gtmtabicon = '<span class="material-symbols-outlined text-success">check_circle</span>';
        }
        if (pixel_setting['gasettings'] == "convo-active") {
            pixelprogressbarclass.push(33);
            gatabicon = '<span class="material-symbols-outlined text-success">check_circle</span>';
        }

        if (pixel_setting['gadssettings'] == "convo-active" || pixel_setting['fbsettings'] == "convo-active" || pixel_setting['snapchatsettings'] == "convo-active" || pixel_setting['tiktoksettings'] == "convo-active" || pixel_setting['pintrestsettings'] == "convo-active" || pixel_setting['bingsettings'] == "convo-active" || pixel_setting['tiktoksettings'] == "convo-active") {
            pixelprogressbarclass.push(33);
            gadstabicon = '<span class="material-symbols-outlined text-success">check_circle</span>';
        }
        var sumnum = pixelprogressbarclass.reduce((accumulator, current) => accumulator + current);
        jQuery("#myTabContent").find(".progress-bar").css("width", sumnum + "%");

        jQuery("#gtmbox-tab").find(".material-symbols-outlined").removeClass("text-success text-secondary");
        jQuery("#gtmbox-tab").find(".material-symbols-outlined").remove();
        jQuery("#gtmbox-tab").prepend(gtmtabicon);

        jQuery("#webpixbox-tab").find(".material-symbols-outlined").removeClass("text-success text-secondary");
        jQuery("#webpixbox-tab").find(".material-symbols-outlined").remove();
        jQuery("#webpixbox-tab").prepend(gatabicon);

        jQuery("#webadsbox-tab").find(".material-symbols-outlined").removeClass("text-success text-secondary");
        jQuery("#webadsbox-tab").find(".material-symbols-outlined").remove();
        jQuery("#webadsbox-tab").prepend(gadstabicon);
    }

    function setconfirmpopup() {
        var pixel_setting_confirm = [];

        var want_to_use_your_gtm = jQuery('input[type=radio][name=want_to_use_your_gtm]:checked').val();
        pixel_setting_confirm["gtmsettings"] = "Conversios GTM Container";

        if (want_to_use_your_gtm == 1) {
            if (jQuery("#nav-automatic-tab").hasClass('active')) {
                pixel_setting_confirm["gtmsettings"] = jQuery("#hidden_gtm_container_publicId").val() != "" ? jQuery("#hidden_gtm_container_publicId").val() : "";
            } else {
                pixel_setting_confirm["gtmsettings"] = jQuery("#use_your_gtm_id").val() != "" ? jQuery("#use_your_gtm_id").val() : "";
            }

        }

        pixel_setting_confirm["gasettings"] = jQuery("#ga4_property_id").val() != "" ? jQuery("#ga4_property_id").val() : "";
        pixel_setting_confirm["gadssettings"] = jQuery("#google_ads_id").val() != "" ? jQuery("#google_ads_id").val() : "";
        pixel_setting_confirm["fbsettings"] = jQuery("#fb_pixel_id").val() != "" ? jQuery("#fb_pixel_id").val() : "";
        pixel_setting_confirm["bingsettings"] = jQuery("#microsoft_ads_pixel_id").val() != "" ? jQuery("#microsoft_ads_pixel_id").val() : "";
        pixel_setting_confirm["twittersettings"] = jQuery("#twitter_ads_pixel_id").val() != "" ? jQuery("#twitter_ads_pixel_id").val() : "";
        pixel_setting_confirm["pintrestsettings"] = jQuery("#pinterest_ads_pixel_id").val() != "" ? jQuery("#pinterest_ads_pixel_id").val() : "";
        pixel_setting_confirm["snapchatsettings"] = jQuery("#snapchat_ads_pixel_id").val() != "" ? jQuery("#snapchat_ads_pixel_id").val() : "";
        pixel_setting_confirm["tiktoksettings"] = jQuery("#tiKtok_ads_pixel_id").val() != "" ? jQuery("#tiKtok_ads_pixel_id").val() : "";
        pixel_setting_confirm["hotjarsettings"] = jQuery("#hotjar_pixel_id").val() != "" ? jQuery("#hotjar_pixel_id").val() : "";
        pixel_setting_confirm["crazyeggsettings"] = jQuery("#crazyegg_pixel_id").val() != "" ? jQuery("#crazyegg_pixel_id").val() : "";
        pixel_setting_confirm["claritysettings"] = jQuery("#msclarity_pixel_id").val() != "" ? jQuery("#msclarity_pixel_id").val() : "";

        jQuery("#wizsetconfirm #conv_pixel_list_box").find(".conv-pixel-list-item").each(function() {
            var pixelname = jQuery(this).attr("pixelname");
            var pixelname_val = pixel_setting_confirm[pixelname] == "" ? "Not Set" : pixel_setting_confirm[pixelname];
            var pixelname_class = pixel_setting_confirm[pixelname] == "" ? "pixconfirmnotsuccess" : "pixconfirmsuccess";
            jQuery(this).addClass(pixelname_class);
            jQuery(this).find(".channel_pixelid").html(pixelname_val);
            jQuery("#wizsetconfirm").modal("show");
        });
    }

    /* Open when someone clicks on the span element */
    function openOverlayLoader(status = "", contentmsg = "") {
        if (status == 'open') {
            document.getElementById("overlayanimation").style.width = "100%";
            jQuery(".loaderopen-content").removeClass("d-none");
            jQuery(".loadershow-content").addClass("d-none");
        } else if (status == 'openshow') {
            document.getElementById("overlayanimation").style.width = "100%";
            jQuery(".loaderopen-content").addClass("d-none");
            jQuery(".loadershow-content").removeClass("d-none");
            jQuery(".loadershow-content .overlaycontentbox").html(contentmsg);
        } else {
            document.getElementById("overlayanimation").style.width = "0%";
            jQuery(".overlay_loader_conv-content").addClass("d-none");
            jQuery(".loaderopen-content").addClass("d-none");
            jQuery(".loadershow-content").addClass("d-none");
            jQuery(".loadershow-content .overlaycontentbox").html("");
            jQuery(".spinner-border").addClass("d-none");
            jQuery(".spinner-border").parent().removeClass("disabledsection");
        }
    }

    function changeTabBox(tbaname = "gtmbox-tab") {
        jQuery("#" + tbaname).tab('show');
        window.scrollTo(0, 0);
    }

    jQuery(function() {
        var tabhash = location.hash.replace(/^#/, ''); // ^ means starting, meaning only match the first hash
        if (tabhash) {
            changeTabBox(tabhash);
        }
        <?php if (isset($_GET['wizard_channel']) && $_GET['wizard_channel'] == "gtmsettings") { ?>
            changeTabBox("gtmbox-tab");
        <?php } ?>

        <?php if (isset($_GET['wizard_channel']) && $_GET['wizard_channel'] == "gasettings") { ?>
            changeTabBox("webpixbox-tab");
        <?php } ?>

        <?php if (isset($_GET['wizard_channel']) && $_GET['wizard_channel'] == "gadssettings") { ?>
            changeTabBox("webadsbox-tab");
        <?php } ?>

        jQuery(".conv-enable-selection_comman").click(function() {
            jQuery(this).parent().find("input").removeAttr("readonly")
            jQuery(this).parent().find("textarea").removeAttr("readonly")
        });

        //For tooltip
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        jQuery(".gtmautotabs button").click(function() {
            if (jQuery("#nav-automatic-tab").hasClass('active')) {
                is_gtm_automatic_process = true;
            } else {
                is_gtm_automatic_process = false;
            }
        });
        jQuery('#convdynamictoast').on('hide.bs.modal', function() {
            jQuery("#dynamictoastbody").html("");
        });
        <?php if ($selectedGtmEvents != true || $is_gtm_automatic_process != "true") { ?>
            jQuery(".event-setting-row").addClass("convdisabledbox");
        <?php } ?>

        jQuery(".event-setting-row").addClass("disabledsection");

        jQuery(".event-setting-row").each(function() {
            jQuery(this).find(".item").each(function() {
                let inpid = jQuery(this).find("input").attr("id");
                jQuery(this).find("label").attr("for", inpid);
            });
        });

        jQuery(".col-md-4 span[data-bs-toggle='tooltip']").addClass("d-flex align-items-center");

        jQuery('#starttrackingbut_wizard').click(function() {
            jQuery('#starttrackingbut_wizard').addClass('convdisabledbox');
            var ecrandomstring = "<?php echo esc_js($TVC_Admin_Helper->generateRandomStringConv()); ?>";
            var subscription_id = "<?php echo esc_js($subscriptionId); ?>";
            var fronturl = '<?php echo esc_url(site_url()); ?>?is_calc_on=1&ec_token=' + ecrandomstring;
            // console.log(fronturl);
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: {
                    action: "conv_create_ec_row",
                    pix_sav_nonce: "<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')); ?>",
                    ecrandomstring: ecrandomstring,
                    subscription_id: subscription_id
                },
                success: function(response) {
                    window.open(fronturl, '_blank');
                    location.href = "<?php echo esc_url('admin.php?page=conversios'); ?>";
                }
            });
        });

        jQuery('.pawizard_tab_but').on('shown.bs.tab', function(e) {
            if (jQuery("#ga4_property_id").val() == "") {
                jQuery("#link_google_analytics_with_google_ads").attr("disabled", true);
                jQuery("#ga_GMC").attr("disabled", true);
            }
            var gadslength = jQuery('#google_ads_id > option').length;
            if (jQuery(e.target).attr('aria-controls') == "webadsbox" && gadslength < 2) {
                var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
                list_google_ads_account(tvc_data);
            }

            if (jQuery(e.target).attr('aria-controls') == "webadsbox" && jQuery("#google_ads_id").val() != "" && jQuery("#google_ads_id").val() != null) {
                <?php if (!isset($googleDetail->remarketing_tags)) { ?>
                    jQuery("#remarketing_tags").prop('checked', true);
                <?php } ?>

                <?php if (!isset($googleDetail->dynamic_remarketing_tags)) { ?>
                    jQuery("#dynamic_remarketing_tags").prop('checked', true);
                <?php } ?>


                <?php if (!isset($googleDetail->link_google_analytics_with_google_ads)) { ?>
                    jQuery("#link_google_analytics_with_google_ads").prop('checked', true);
                <?php } ?>

            }
        });

        jQuery(document).on("change", "#google_ads_id", function() {
            var remarketing_tags = '<?php echo isset($googleDetail->remarketing_tags) ? esc_js($googleDetail->remarketing_tags) : "notset"; ?>';
            var dynamic_remarketing_tags = '<?php echo isset($googleDetail->dynamic_remarketing_tags) ? esc_js($googleDetail->dynamic_remarketing_tags) : "notset"; ?>';
            var link_google_analytics_with_google_ads = '<?php echo isset($googleDetail->link_google_analytics_with_google_ads) ? esc_js($googleDetail->link_google_analytics_with_google_ads) : "notset"; ?>';
            jQuery("#remarketing_tags").prop('checked', true);
            jQuery("#dynamic_remarketing_tags").prop('checked', true);

            if (jQuery("#ga4_property_id").val() != "") {
                jQuery("#link_google_analytics_with_google_ads").removeAttr("disabled");
                jQuery("#link_google_analytics_with_google_ads").prop('checked', true);
            }
        });

        jQuery(document).on("click", "#ads-continue-close", function() {
            jQuery("#gadsConversionAcco .accordion-body").removeClass("disabledsection");
            if (jQuery("#ga4_property_id").val() == "") {
                jQuery("#link_google_analytics_with_google_ads").attr("disabled", true);
                jQuery("#ga_GMC").attr("disabled", true);
            } else {
                jQuery("#link_google_analytics_with_google_ads").removeClass("disabled");
                jQuery("#ga_GMC").removeClass("disabled");

                jQuery("#remarketing_tags").prop('checked', true);
                jQuery("#dynamic_remarketing_tags").prop('checked', true);

                if (jQuery("#ga4_property_id").val() != "") {
                    jQuery("#link_google_analytics_with_google_ads").removeAttr("disabled");
                    jQuery("#link_google_analytics_with_google_ads").prop('checked', true);
                }
            }
        });

    });
</script>