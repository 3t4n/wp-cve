<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$is_sel_disable_ga = 'disabled';
$cust_g_email =  (isset($tvc_data['g_mail']) && esc_attr($subscriptionId)) ? esc_attr($tvc_data['g_mail']) : "";

$gtm_account_id = isset($ee_options['gtm_settings']['gtm_account_id']) ? $ee_options['gtm_settings']['gtm_account_id'] : "";
$gtm_container_id = isset($ee_options['gtm_settings']['gtm_container_id']) ? $ee_options['gtm_settings']['gtm_container_id'] : "";
$is_gtm_automatic_process = isset($ee_options['gtm_settings']['is_gtm_automatic_process']) ? $ee_options['gtm_settings']['is_gtm_automatic_process'] : false;
?>
<div class="mt-3">
    <div class="convwizard_pixtitle mt-0 mb-3">
        <div class="d-flex flex-row align-items-center">
            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_ganalytics_logo.png'); ?>" />
            <h5 class="m-0 ms-2 h5">
                <?php esc_html_e("Google Analytics 4", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </h5>
        </div>
        <div class="mt-1"><?php esc_html_e("Setup ecommerce tracking in 2 simple steps from below.", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
    </div>


    <form id="gasettings_form" class="convgawiz_form convpixsetting-inner-box mt-0 pb-3 pt-0" datachannel="GA">

        <div class="product-feed">
            <div class="progress-wholebox">
                <div class="card-body">
                    <ul class="progress-steps">
                        <li class="gmc_mail_step ">
                            <!-- Google SignIn -->
                            <div class="convpixsetting-inner-box">
                                <?php
                                $g_email = (isset($tvc_data['g_mail']) && esc_attr($subscriptionId)) ? esc_attr($tvc_data['g_mail']) : "";
                                ?>
                                <?php if ($g_email != "") { ?>
                                    <h5 class="fw-normal mb-1">
                                        <?php esc_html_e("Successfully signed in with account:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </h5>
                                    <span>
                                        <?php echo (isset($tvc_data['g_mail']) && esc_attr($subscriptionId)) ? esc_attr($tvc_data['g_mail']) : ""; ?>
                                        <span class="conv-link-blue ps-2 tvc_google_signinbtn_ga">
                                            <?php esc_html_e("Change", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </span>
                                    </span>
                                <?php } else { ?>

                                    <div class="tvc_google_signinbtn_box" style="width: 185px;">
                                        <div class="tvc_google_signinbtn_ga google-btn">
                                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/btn_google_signin_dark_normal_web.png'); ?>">
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <!-- Google SignIn End -->
                        </li>
                        <li class="gmc_account_id_step">
                            <!-- GA4 account ID Selection -->
                            <?php
                            $tracking_option = (isset($ee_options['tracking_option']) && $ee_options['tracking_option'] != "") ? $ee_options['tracking_option'] : "";
                            $ua_analytic_account_id = (isset($googleDetail->ua_analytic_account_id) && $googleDetail->ua_analytic_account_id != "") ? $googleDetail->ua_analytic_account_id : "";
                            $property_id = (isset($googleDetail->property_id) && $googleDetail->property_id != "") ? $googleDetail->property_id : "";
                            $ga4_analytic_account_id = (isset($googleDetail->ga4_analytic_account_id) && $googleDetail->ga4_analytic_account_id != "") ? $googleDetail->ga4_analytic_account_id : "";
                            $measurement_id = (isset($googleDetail->measurement_id) && $googleDetail->measurement_id != "") ? $googleDetail->measurement_id : "";
                            ?>
                            <div id="analytics_box_GA4" class="py-1">
                                <h5 class="fw-normal mb-1">
                                    <?php esc_html_e("Select Google Analytics 4 account:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h5>
                                <div class="row pt-1 conv-hideme-gasettings">
                                    <div class="col-5">
                                        <select id="ga4_analytic_account_id" name="ga4_analytic_account_id" acctype="GA4" class="form-select form-select-lg mb-3 ga_analytic_account_id ga_analytic_account_id_ga4 selecttwo_search" style="width: 100%" <?php echo esc_attr($is_sel_disable_ga); ?>>
                                            <?php if (!empty($ga4_analytic_account_id)) { ?>
                                                <option selected><?php echo esc_html($ga4_analytic_account_id); ?></option>
                                            <?php } ?>
                                            <option value="">Select GA4 Account ID</option>
                                        </select>
                                    </div>

                                    <div class="col-5">
                                        <select id="ga4_property_id" name="measurement_id" class="form-select form-select-lg mb-3 selecttwo_search pixvalinput_gahot" style="width: 100%" <?php echo esc_attr($is_sel_disable_ga); ?>>
                                            <option value="">Select Measurement ID</option>
                                            <?php if (!empty($measurement_id)) { ?>
                                                <option selected><?php echo esc_html($measurement_id); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-2 d-flex align-items-end">
                                        <?php if ($g_email != "") { ?>
                                            <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_ga conv-link-blue align-items-center">
                                                <span class="material-symbols-outlined md-18">edit</span>
                                                <span class="px-1">Edit</span>
                                            </button>
                                        <?php } ?>

                                    </div>

                                </div>
                            </div>
                            <!-- GA4 account ID Selection End -->
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <div>
            <?php
            $ga4_api_secret = (isset($ee_options["ga4_api_secret"]) && $ee_options["ga4_api_secret"] != "") ? $ee_options["ga4_api_secret"] : "";
            ?>
            <div id="ga4apisecret_box" class="py-3 <?php echo $tracking_option === 'UA' ? 'd-none' : ''; ?>">
                <div class="row pt-2">
                    <div class="col-7">
                        <h5 class="d-flex fw-normal mb-1 text-dark">
                            <?php esc_html_e("GA4 API Secret (To track refund order)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <?php
                            $tooltip = "How to get 'Measurement Protocol API Secret' in GA4: Click Admin > Click Data streams (Under Property) > Select the stream > Additional Settings - Measurement Protocol API secrets > Create a new API secret.";
                            ?>
                            <span class="material-symbols-outlined md-18 ps-2" data-bs-toggle="tooltip" data-bs-placement="right" data-container="body" title="" data-bs-original-title="<?php esc_attr($tooltip); ?>">
                                <?php esc_html_e("info", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                        </h5>
                        <input readonly type="text" name="ga4_api_secret" id="ga4_api_secret" class="form-control disabled" value="<?php echo esc_attr($ga4_api_secret); ?>" placeholder="e.g. CnTrpcbsStWFU5-TmSuhuS">
                    </div>

                </div>
            </div>
            <!-- GA4 API Secret End-->
        </div>

        <!-- Ecommerce Events -->
        <div class="mt-0 rounded-3 mt-3">
            <div class="row">
                <h5 class="fw-normal mb-1">
                    <?php esc_html_e("Select Ecommerce Events for tracking:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    <span class="fw-400 text-color fs-12">
                        <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Page view and purchase event tracking are available in free plan. For complete ecommerce tracking, upgrade to our pro plan">
                            info
                        </span>
                    </span>
                </h5>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <input type="checkbox" class="m-1" name="" style="-webkit-appearance: auto;" checked onclick="return false;">
                    <?php esc_html_e("Page view", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </div>
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Select item", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Remove from cart", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <input type="checkbox" name="" class="m-1" style="-webkit-appearance: auto;" checked onclick="return false;">
                    <?php esc_html_e("Purchase", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </div>
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Add to cart on item list", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </span>
                </div>
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Add payment Info", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 pr-0">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("View item list", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
                <div class="col-md-4 pr-0">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Add to cart on single item", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </span>
                </div>
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span><?php esc_html_e(" Begin checkout", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("View item", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("View cart", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
                <div class="col-md-4">

                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-12 convutp_bot">
                    <h5 class="fw-normal mb-1 d-flex">
                        <span class="material-symbols-outlined text-success me-1">info</span>
                        <?php esc_html_e("To access full ecommerce tracking with GA4, we recommend upgrading to our Premium Plan.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <span class="align-middle conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                            <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </span>
                    </h5>
                </div>
            </div>
        </div>
    </form>

    <form id="hotjarsetings_form" class="convgawiz_form convpixsetting-inner-box mt-4 pb-4 pt-3" datachannel="Hotjar">
        <div>
            <!-- hotjar Pixel -->
            <?php $hotjar_pixel_id = isset($ee_options['hotjar_pixel_id']) ? $ee_options['hotjar_pixel_id'] : ""; ?>
            <div id="hotjar_box" class="py-1">
                <div class="row pt-2">
                    <div class="col-12">
                        <div class="convwizard_pixtitle mt-0 mb-3">
                            <div class="d-flex flex-row align-items-center">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_hotjar_logo.png'); ?>" />
                                <h5 class="m-0 ms-2 h5">
                                    <?php esc_html_e("Hotjar Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h5>
                            </div>
                            <div class="mt-1">
                                <?php esc_html_e("Setup Hotjar pixel by adding the pixel id below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-hotjar-pixel-in-the-conversios-plugin?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                                    <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-row">
                        <input <?php echo $hotjar_pixel_id == "" ? "" : "readonly"; ?> type="text" name="hotjar_pixel_id" id="hotjar_pixel_id" class="pixvalinput_gahot form-control valtoshow_inpopup_this" value="<?php echo esc_attr($hotjar_pixel_id); ?>" placeholder="eg.3694864">
                        <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $hotjar_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                            <span class="material-symbols-outlined md-18">edit</span>
                            <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </button>
                    </div>

                </div>
            </div>
            <!-- Hotjar Pixel End-->
        </div>
    </form>

    <form id="claritysetings_form" class="convgawiz_form convpixsetting-inner-box mt-4 pb-4 pt-3" datachannel="MicrosoftClarity">
        <div>
            <!-- clarity Pixel -->
            <?php $clarity_pixel_id = isset($ee_options['msclarity_pixel_id']) ? $ee_options['msclarity_pixel_id'] : ""; ?>
            <div id="clarity_box" class="py-1">
                <div class="row pt-2">
                    <div class="col-12">
                        <div class="convwizard_pixtitle mt-0 mb-3">
                            <div class="d-flex flex-row align-items-center">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_clarity_logo.png'); ?>" />
                                <h5 class="m-0 ms-2 h5">
                                    <?php esc_html_e("Microsoft Clarity", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h5>
                            </div>
                            <div class="mt-1">
                                <?php esc_html_e("Setup Clarity by adding the pixel id below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-bing-pixel/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                                    <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-row">
                        <input <?php echo $clarity_pixel_id == "" ? "" : "readonly"; ?> type="text" name="msclarity_pixel_id" id="msclarity_pixel_id" class="pixvalinput_gahot form-control valtoshow_inpopup_this" value="<?php echo esc_attr($clarity_pixel_id); ?>" placeholder="e.g. ij312itarj">
                        <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $clarity_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                            <span class="material-symbols-outlined md-18">edit</span>
                            <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </button>
                    </div>

                </div>
            </div>
            <!-- clarity Pixel End-->
        </div>
    </form>

    <form id="crazyeggsetings_form" class="convgawiz_form convpixsetting-inner-box mt-4 pb-4 pt-3" datachannel="Crazyegg">
        <div>
            <!-- crazyegg Pixel -->
            <?php $crazyegg_pixel_id = isset($ee_options['crazyegg_pixel_id']) ? $ee_options['crazyegg_pixel_id'] : ""; ?>
            <div id="crazyegg_box" class="py-1">
                <div class="row pt-2">
                    <div class="col-12">
                        <div class="convwizard_pixtitle mt-0 mb-3">
                            <div class="d-flex flex-row align-items-center">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_crazyegg_logo.png'); ?>" />
                                <h5 class="m-0 ms-2 h5">
                                    <?php esc_html_e("Crazy Egg", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h5>
                            </div>
                            <div class="mt-1">
                                <?php esc_html_e("Setup Crazy Egg by adding the pixel id below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-crazyegg-pixel-in-the-conversios-plugin/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                                    <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-row">
                        <input <?php echo $crazyegg_pixel_id == "" ? "" : "readonly"; ?> type="text" name="crazyegg_pixel_id" id="crazyegg_pixel_id" class="pixvalinput_gahot form-control valtoshow_inpopup_this" value="<?php echo esc_attr($crazyegg_pixel_id); ?>" placeholder="eg.36948643">
                        <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $crazyegg_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                            <span class="material-symbols-outlined md-18">edit</span>
                            <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </button>
                    </div>

                </div>
            </div>
            <!-- crazyegg Pixel End-->
        </div>
    </form>

    <!-- Tab bottom buttons -->
    <div class="tab_bottom_buttons d-flex align-items-center pt-4">
        <div class="gobackwizard d-flex" onclick="changeTabBox('gtmbox-tab')">
            <span class="material-symbols-outlined">
                keyboard_backspace
            </span>
            <div class="align-self-baseline">
                <?php esc_html_e('Go Back', "enhanced-e-commerce-for-woocommerce-store"); ?>
            </div>
        </div>

        <div class="ms-auto d-flex align-items-center">
            <div class="gobackwizard d-flex ms-auto" onclick="changeTabBox('webadsbox-tab')">
                <div class="align-self-baseline">
                    <?php esc_html_e('Skip To Next', "enhanced-e-commerce-for-woocommerce-store"); ?>
                </div>
            </div>

            <button id="save_gahotclcr" type="button" class="btn btn-primary px-5 ms-3">
                <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <?php esc_html_e('Save & Next', "enhanced-e-commerce-for-woocommerce-store"); ?>
            </button>
        </div>
    </div>

</div>

<script>
    // let store_id = "<?php //echo $store_id; ?>";
    // let gtm_account_id = "<?php //echo $gtm_account_id; ?>";
    // let gtm_container_id = "<?php //echo $gtm_container_id; ?>";
    // let is_gtm_automatic_process = "<?php //echo $is_gtm_automatic_process; ?>";


    // get list of google analytics account
    function list_analytics_account(tvc_data, selelement, currele, page = 1) {
        var conversios_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conversios_onboarding_nonce')); ?>";
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
                action: "get_analytics_account_list",
                tvc_data: tvc_data,
                page: page,
                conversios_onboarding_nonce: conversios_onboarding_nonce
            },
            success: function(response) {
                if (response && response.error == false) {
                    var error_msg = 'null';
                    if (response?.data?.items.length > 0) {
                        var AccOptions = '';
                        var selected = '';
                        response?.data?.items.forEach(function(item) {
                            AccOptions = AccOptions + '<option value="' + item.id + '"> ' + item.name + '-' + item.id + '</option>';
                        });

                        jQuery('#ga4_analytic_account_id').append(AccOptions); //GA4 
                        selelement.prop("disabled", false);
                        jQuery(".conv-enable-selection_ga").addClass('d-none');

                    } else {
                        console.log("error1", "There are no Google Analytics accounts associated with this email.");
                        getAlertMessageAll(
                            'info',
                            'Error',
                            message = 'There are no Google Analytics accounts associated with this email.',
                            icon = 'info',
                            buttonText = 'Ok',
                            buttonColor = '#FCCB1E',
                            iconImageSrc = '<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_error_logo.png'); ?>"/ >'
                        );
                    }

                } else if (response && response.error == true && response.error != undefined) {
                    const errors = response.errors[0];
                    getAlertMessageAll(
                        'info',
                        'Error',
                        message = errors,
                        icon = 'info',
                        buttonText = 'Ok',
                        buttonColor = '#FCCB1E',
                        iconImageSrc = '<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_error_logo.png'); ?>"/ >'
                    );
                    var error_msg = errors;
                } else {
                    getAlertMessageAll(
                        'info',
                        'Error',
                        message = 'There are no Google Analytics accounts associated with this email.',
                        icon = 'info',
                        buttonText = 'Ok',
                        buttonColor = '#FCCB1E',
                        iconImageSrc = '<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_error_logo.png'); ?>"/ >'
                    );
                }
                jQuery("#tvc-ga4-acc-edit-acc_box")?.removeClass('tvc-disable-edits');
                conv_change_loadingbar("hide");
                jQuery(".conv-enable-selection_ga").removeClass('disabled');
            }
        });
    }


    // get list properties dropdown options
    function list_analytics_web_properties(type, tvc_data, account_id, thisselid) {
        jQuery("#ga4_property_id").prop("disabled", true);
        var conversios_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conversios_onboarding_nonce')); ?>";
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
                action: "get_analytics_web_properties",
                account_id: account_id,
                type: type,
                tvc_data: tvc_data,
                conversios_onboarding_nonce: conversios_onboarding_nonce
            },
            success: function(response) {
                if (response && response.error == false) {
                    var error_msg = 'null';


                    if (type == "GA4") {
                        jQuery('#ga4_property_id').empty().trigger("change");
                        jQuery('#both_ga4_property_id').empty().trigger("change");
                        if (response?.data?.wep_measurement.length > 0) {
                            var streamOptions = '<option value="">Select Measurement Id</option>';
                            var selected = '';
                            response?.data?.wep_measurement.forEach(function(item) {
                                let dataName = item.name.split("/");
                                streamOptions = streamOptions + '<option value="' + item.measurementId + '">' + item.measurementId + ' - ' + item.displayName + '</option>';
                            });
                            jQuery('#ga4_property_id').append(streamOptions);
                            jQuery('#both_ga4_property_id').append(streamOptions);
                            jQuery('.event-setting-row_ga').addClass("convdisabledbox")
                        } else {
                            var streamOptions = '<option value="">No GA4 Property Found</option>';
                            jQuery('#ga3_property_id').append(streamOptions);
                            jQuery('#both_ga3_property_id').append(streamOptions);
                            getAlertMessageAll(
                                'info',
                                'Error',
                                message = 'There are no Google Analytics 4 Properties associated with this analytics account.',
                                icon = 'info',
                                buttonText = 'Ok',
                                buttonColor = '#FCCB1E',
                                iconImageSrc = '<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_error_logo.png'); ?>"/ >'
                            );
                        }
                        jQuery(".ga_analytic_account_id_ga4:not(#" + thisselid + ")").val(account_id).trigger("change");
                    }

                } else if (response && response.error == true && response.error != undefined) {
                    const errors = response.error[0];
                    getAlertMessageAll(
                        'info',
                        'Error',
                        message = errors,
                        icon = 'info',
                        buttonText = 'Ok',
                        buttonColor = '#FCCB1E',
                        iconImageSrc = '<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_error_logo.png'); ?>"/ >'
                    );
                    //add_message("error", errors);
                    var error_msg = errors;
                } else {
                    //add_message("error", "There are no Google Analytics Properties associated with this email.");
                    getAlertMessageAll(
                        'info',
                        'Error',
                        message = 'There are no Google Analytics Properties associated with this email.',
                        icon = 'info',
                        buttonText = 'Ok',
                        buttonColor = '#FCCB1E',
                        iconImageSrc = '<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_error_logo.png'); ?>"/ >'
                    );
                }
                conv_change_loadingbar("hide");
                jQuery("#ga4_property_id").prop("disabled", false);
                jQuery('.event-setting-row_ga').addClass("convdisabledbox")
            }
        });
    }

    function load_ga_accounts(tvc_data) {
        conv_change_loadingbar("show");
        jQuery(".conv-enable-selection_ga").addClass('disabled');
        var selele = jQuery(".conv-enable-selection_ga").closest(".conv-hideme-gasettings").find("select.ga_analytic_account_id");
        var currele = jQuery(this).closest(".conv-hideme-gasettings").find("select.ga_analytic_account_id");
        list_analytics_account(tvc_data, selele, currele);
    }

    //Onload functions
    jQuery(function() {

        jQuery("#gasettings_form, #hotjarsetings_form, #claritysetings_form, #crazyeggsetings_form").change(function() {
            jQuery(this).addClass("formchanged");
        });

        var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
        var tvc_ajax_url = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
        let subscription_id = "<?php echo esc_attr($subscriptionId); ?>";
        let plan_id = "<?php echo esc_attr($plan_id); ?>";
        let app_id = "<?php echo esc_attr($app_id); ?>";
        let bagdeVal = "yes";
        let convBadgeVal = "<?php echo esc_attr($convBadgeVal); ?>";
        let cust_g_email = "<?php echo esc_attr($cust_g_email); ?>";
        if (cust_g_email == '') {
            jQuery('.event-setting-row_ga').addClass("convdisabledbox")
            // jQuery(".tracking_event_selection").prop('disabled', true);

        } else {
            jQuery('.event-setting-row_ga').removeClass("convdisabledbox")
            // jQuery(".tracking_event_selection").prop('disabled', false);
        }
        if (jQuery('#ga4_api_secret').val() == '') {
            jQuery('input[name="COV - GA4 - Refund"]').prop("disabled", true);
        } else {
            jQuery('input[name="COV - GA4 - Refund"]').prop("disabled", false);
        }
        jQuery(".selecttwo_search").select2({
            minimumResultsForSearch: 1,
            placeholder: function() {
                jQuery(this).data('placeholder');
            }
        });


        jQuery('input[type=radio][name=tracking_option]').change(function() {
            jQuery(".conv-hideme-gasettings").addClass('d-none');
            jQuery(this).parent().find(".conv-hideme-gasettings").removeClass('d-none');
            var tracking_option = jQuery(this).val();
            if (tracking_option == "BOTH" || tracking_option == "GA4") {
                jQuery("#ga4apisecret_box").removeClass("d-none");
            }
            if (tracking_option == "UA") {
                jQuery("#ga4apisecret_box").addClass("d-none");
            }
        });

        <?php if ((isset($_GET['wizard_channel']) && sanitize_text_field($_GET['wizard_channel']) == "gasettings")) { ?>
            load_ga_accounts(tvc_data);
        <?php } ?>


        jQuery(".conv-enable-selection_ga").click(function() {
            conv_change_loadingbar("show");
            jQuery(".conv-enable-selection_ga").addClass('disabled');
            var selele = jQuery(".conv-enable-selection_ga").closest(".conv-hideme-gasettings").find("select.ga_analytic_account_id");
            var currele = jQuery(this).closest(".conv-hideme-gasettings").find("select.ga_analytic_account_id");
            list_analytics_account(tvc_data, selele, currele);
        });

        jQuery(document).on('select2:select', '.ga_analytic_account_id', function(e) {
            if (jQuery(this).val() != "" && jQuery(this).val() != undefined) {
                conv_change_loadingbar("show");
                var account_id = jQuery(e.target).val();
                var acctype = jQuery(e.target).attr('acctype');
                var thisselid = e.target.getAttribute('id');
                list_analytics_web_properties(acctype, tvc_data, account_id, thisselid);
                jQuery(".ga_analytic_account_id").closest(".conv-hideme-gasettings").find("select").prop("disabled", false);
            } else {
                jQuery(".ga_analytic_account_id").closest(".conv-hideme-gasettings").find("select").prop("disabled", false);
                jQuery('#ga4_property_id').val("").trigger("change");
            }

        });

        jQuery(document).on("change", "form#gasettings_form", function() {
            <?php if ($cust_g_email != "") { ?>
                jQuery(".conv-btn-connect_ga").removeClass("conv-btn-connect_ga-disabled_ga");
                jQuery(".conv-btn-connect_ga").addClass("conv-btn-connect_ga-enabled-google");
                jQuery(".conv-btn-connect_ga").text('Save');
                if (jQuery('#ga4_analytic_account_id').val() == "" || jQuery('#ga4_property_id').val() == "") {
                    jQuery('.event-setting-row_ga').addClass("convdisabledbox")
                } else {
                    jQuery('.event-setting-row_ga').removeClass("convdisabledbox")
                }
            <?php } else { ?>
                jQuery(".tvc_google_signinbtn_ga").trigger("click");
                jQuery('.event-setting-row_ga').addClass("convdisabledbox")
                // jQuery(".tracking_event_selection").prop('disabled', true);
            <?php } ?>

            if (jQuery('#ga4_api_secret').val() == '') {

                jQuery('input[name="COV - GA4 - Refund"]').prop("disabled", true);
                jQuery('input[name="COV - GA4 - Refund"]').prop("checked", false);
            } else {
                jQuery('input[name="COV - GA4 - Refund"]').prop("disabled", false);

            }
        });

        // Save data
        jQuery(document).on("click", "#save_gahotclcr", function() {
            jQuery(this).find(".spinner-border").removeClass("d-none");
            jQuery(this).addClass('disabledsection');
            var tracking_option = 'GA4'; //jQuery('input[type=radio][name=tracking_option]:checked').val();
            var box_id = "#analytics_box_" + tracking_option;
            var has_error = 0;
            var selected_vals = {};
            selected_vals["ua_analytic_account_id"] = "<?php echo esc_attr($ua_analytic_account_id); ?>";
            selected_vals["property_id"] = "<?php echo esc_attr($property_id); ?>";
            selected_vals["ga4_analytic_account_id"] = "";
            selected_vals["measurement_id"] = "";
            selected_vals["subscription_id"] = "<?php echo esc_html($tvc_data['subscription_id']) ?>";
            jQuery(box_id).find("select, input").each(function() {
                if (!jQuery(this).val() || jQuery(this).val() == "" || jQuery(this).val() == "undefined") {
                    has_error = 1;
                    return;
                } else {
                    selected_vals[jQuery(this).attr('name')] = jQuery(this).val();
                }
            });
            selected_vals["tracking_option"] = tracking_option;
            selected_vals["ga4_api_secret"] = jQuery("#ga4_api_secret").val();

            selected_vals["hotjar_pixel_id"] = jQuery("#hotjar_pixel_id").val();
            selected_vals["msclarity_pixel_id"] = jQuery("#msclarity_pixel_id").val();
            selected_vals["crazyegg_pixel_id"] = jQuery("#crazyegg_pixel_id").val();


            let channel_data_gahot = {};
            let channel_data_bingvlar = {};
            let selected_event_checkboxes = {};
            let selected_event_checkboxes_bingvlar = {};
            var checkbingloop = 0
            jQuery(".convgawiz_form").each(function() {
                let channel_name = jQuery(this).attr("datachannel");
                let pixvalinput_gahot = jQuery(this).find(".pixvalinput_gahot").val();
                let channel_checkedVals = jQuery(this).find('.tracking_event_selection:checkbox:checked').map(function() {
                    return {
                        "tagId": this.id,
                        "name": this.value,
                        "label": jQuery(this).data('label')
                    }
                }).get();

                selected_event_checkboxes[channel_name] = {
                    "tag": channel_checkedVals.length ? channel_checkedVals : ['']
                };


                if (jQuery(this).hasClass("formchanged")) {
                    if (pixvalinput_gahot != "") {
                        channel_data_gahot[channel_name] = {
                            "tag": channel_checkedVals
                        };
                    } else {
                        channel_data_gahot[channel_name] = {
                            "tag": ['']
                        };
                    }
                }

            });

            if (jQuery("#microsoft_ads_pixel_id").val() != "") {
                jQuery("#bingsetings_form").each(function() {
                    let channel_name = jQuery(this).attr("datachannel");
                    let pixvalinput_bingvlar = jQuery(this).find(".valtoshow_inpopup_this").val();
                    let channel_checkedVals_bingvlar = jQuery(this).find('.tracking_event_selection:checkbox:checked').map(function() {
                        return {
                            "tagId": this.id,
                            "name": this.value,
                            "label": jQuery(this).data('label')
                        }
                    }).get();

                    selected_event_checkboxes_bingvlar["MicrosoftClarity"] = {
                        "tag": channel_checkedVals_bingvlar.length ? channel_checkedVals_bingvlar : ['']
                    };

                    if (pixvalinput_bingvlar != "") {
                        if (channel_data_gahot.MicrosoftClarity != undefined && channel_checkedVals_bingvlar.length) {
                            channel_data_gahot.MicrosoftClarity.tag.push(...channel_checkedVals_bingvlar)
                        }

                    } else {
                        channel_data_bingvlar["MicrosoftClarity"] = {
                            "tag": ['']
                        };
                    }
                });
            }

            selected_vals['gtm_channel_settings'] = Object.assign(selected_event_checkboxes, selected_event_checkboxes_bingvlar);
            allselectedevents = Object.assign({}, selected_event_checkboxes, selected_event_checkboxes_bingvlar);
            var data_gahotclcr = {
                action: "conv_save_pixel_data",
                pix_sav_nonce: "<?php echo esc_html(wp_create_nonce('pix_sav_nonce_val')); ?>",
                conv_options_data: selected_vals,
                conv_options_type: ["eeoptions", "eeapidata", "middleware"],
                //conv_options_type: ["eeoptions"],
                conv_tvc_data: tvc_data,
            };

            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: data_gahotclcr,
                success: function(response) {
                    jQuery(".loadershow-content .overlaycontentbox").html('<p>Connected Successfully</p>');
                    openOverlayLoader('openshow');
                    setTimeout(function() {
                        changeTabBox("webadsbox-tab");
                        openOverlayLoader('close');
                        changeseekbar();
                    }, 2000);
                }
            });
            var want_to_use_your_gtm = jQuery('input[type=radio][name=want_to_use_your_gtm]:checked').val();
            if ((is_gtm_automatic_process === true || is_gtm_automatic_process === 'true' || jQuery("#nav-automatic-tab").hasClass('active')) && want_to_use_your_gtm == 1) {
                //runGtmAutomationChannelWise(Object.assign(channel_data_gahot, channel_data_bingvlar));
            }
        });

    });
</script>