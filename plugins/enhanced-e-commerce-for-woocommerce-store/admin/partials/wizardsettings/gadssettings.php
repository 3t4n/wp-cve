<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$is_sel_disable_gads = 'disabled';
$cust_g_email_gads =  (isset($tvc_data['g_mail']) && esc_attr($subscriptionId)) ? esc_attr($tvc_data['g_mail']) : "";
$tvs_admin = new TVC_Admin_Helper();
$tvs_admin_data = $tvs_admin->get_ee_options_data();
$store_id = $tvs_admin_data['setting']->store_id;
$gtm_account_id = isset($ee_options['gtm_settings']['gtm_account_id']) ? $ee_options['gtm_settings']['gtm_account_id'] : "";
$gtm_container_id = isset($ee_options['gtm_settings']['gtm_container_id']) ? $ee_options['gtm_settings']['gtm_container_id'] : "";
$is_gtm_automatic_process = isset($ee_options['gtm_settings']['is_gtm_automatic_process']) ? $ee_options['gtm_settings']['is_gtm_automatic_process'] : false;
?>

<div class="convgads_mainbox">
    <form id="gadssetings_form" class="convgawiz_form_webads convpixsetting-inner-box mt-3 pb-4 convwiz_border formchanged_webads" datachannel="GoogleAds">

        <div class="convwizard_pixtitle mt-0 mb-3">
            <div class="d-flex flex-row align-items-center">
                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gads_logo.png'); ?>" />
                <h5 class="m-0 ms-2 h5">
                    <?php esc_html_e("Google Ads", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </h5>
            </div>
            <div class="mt-1">
                <?php esc_html_e("Configure conversions and audience creation effortlessly in just three simple steps outlined below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </div>
        </div>

        <div class="product-feed">
            <div class="progress-wholebox">
                <div class="card-body">
                    <ul class="progress-steps">
                        <li class="gmc_mail_step ">
                            <!-- Google Auth for GAds -->
                            <?php
                            $site_url_feedlist = "admin.php?page=conversios-google-shopping-feed&tab=feed_list";
                            $google_ads_id = (isset($googleDetail->google_ads_id) && $googleDetail->google_ads_id != "") ? $googleDetail->google_ads_id : "";
                            ?>
                            <div class="convpixsetting-inner-box">
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
                            <!-- Google Auth for GAds End-->
                        </li>
                        <li class="gmc_account_id_step">

                            <!-- GAds Acc Selection -->

                            <div id="analytics_box_ads" class="py-1">
                                <div class="row pt-2">
                                    <div class="col-5">
                                        <h5 class="fw-normal mb-1">
                                            <?php esc_html_e("Select Google Ads Account:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </h5>
                                        <select id="google_ads_id" name="google_ads_id" class="valtoshow_inpopup_this form-select form-select-lg mb-3 selecttwo google_ads_id" style="width: 100%" <?php echo esc_attr($is_sel_disable_gads); ?>>
                                            <?php if (!empty($google_ads_id)) { ?>
                                                <option value="<?php echo esc_attr($google_ads_id); ?>" selected><?php echo esc_html($google_ads_id); ?></option>
                                            <?php } ?>
                                            <option value="">Select Account</option>
                                        </select>
                                    </div>

                                    <div class="col-2 d-flex align-items-end">
                                        <button id="fetchgadsaccs" type="button" class="btn btn-sm d-flex conv-enable-selection conv-link-blue align-items-center">
                                            <span class="material-symbols-outlined md-18">edit</span>
                                            <span class="px-1">
                                                <?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </span>
                                        </button>
                                    </div>

                                    <div class="col-12 flex-row pt-1">
                                        <h6 class="fw-normal mb-1">
                                            <?php esc_html_e("OR", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </h6>
                                        <div class="col-12">
                                            <button id="conv_create_gads_new_btn" type="button" class="btn conv-blue-bg text-white" data-bs-toggle="modal" data-bs-target="#conv_create_gads_new">
                                                <?php esc_html_e("Create New Google Ads account for me!", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </button>
                                            <img style="cursor: default;" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/BFriday-Google-Ads-Screen-Image.png'); ?>" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- GAds Acc Selection End -->


                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <!-- Accordion start -->
        <div class="accordion accordion-flush" id="gadsConversionAcco">

            <div class="accordion-item mt-3 rounded-3 shadow-sm">
                <h2 class="accordion-header p-2" id="flush-headingTwo">
                    <button class="accordion-button collapsed conv-link-blue p-2" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        <?php esc_html_e("Setup Conversions Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#gadsConversionAcco">
                    <div class="accordion-body pt-0">
                        <ul class="ps-0">
                            <li class="d-flex align-items-center my-2">
                                <div class="inlist_text_pre ms-2 disabledsection" conversion_name="">
                                    <h5 class="mb-0"><?php esc_html_e("When product is added to cart:", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                                    <div class="inlist_text_notconnected">
                                        <?php esc_html_e("Implement conversion tracking for 'add to cart' events to evaluate campaign effectiveness.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </div>
                                    <div class="inlist_text_connected d-flex d-none">
                                        <div class="text-success"><?php esc_html_e("Connected with Conversion ID:", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                                        <div class="inlist_text_connected_convid ps-2"></div>
                                    </div>
                                </div>

                                <span class="conv-link-blue ms-2 fw-bold-500 upgradetopro_badge ms-auto" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                    <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                    <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </span>

                            </li>

                            <li class="d-flex align-items-center my-2">
                                <div class="inlist_text_pre ms-2 disabledsection" conversion_name="">
                                    <h5 class="mb-0"><?php esc_html_e("When checkout is initiated:", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                                    <div class="inlist_text_notconnected">
                                        <?php esc_html_e("Implement conversion tracking for 'begin checkout' events to evaluate campaign effectiveness.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </div>
                                    <div class="inlist_text_connected d-flex d-none">
                                        <div class="text-success"><?php esc_html_e("Connected with Conversion ID:", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                                        <div class="inlist_text_connected_convid ps-2"></div>
                                    </div>
                                </div>
                                <span class="conv-link-blue ms-2 fw-bold-500 upgradetopro_badge ms-auto" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                    <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                    <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </span>
                            </li>

                            <li class="d-flex align-items-center my-2">
                                <div class="inlist_text_pre ms-2" conversion_name="PURCHASE">
                                    <h5 class="mb-0"><?php esc_html_e("When purchase happens:", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                                    <div class="inlist_text_notconnected">
                                        <?php esc_html_e("Implement conversion tracking for 'purchase' events to evaluate campaign effectiveness.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </div>
                                    <div class="inlist_text_connected d-flex d-none">
                                        <div class="text-success"><?php esc_html_e("Connected with Conversion ID:", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                                        <div class="inlist_text_connected_convid ps-2"></div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm ms-auto conv_con_modal_opener px-4 py-2" conversion_name="PURCHASE">
                                    <?php esc_html_e("+ Create Conversion", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </button>
                            </li>


                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item mt-3 rounded-3 shadow-sm">
                <h2 class="accordion-header p-2" id="flush-headingThree">
                    <button class="accordion-button collapsed conv-link-blue p-2" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        <?php esc_html_e("Setup Dynamic Remarketing Audience Building", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#gadsConversionAcco">
                    <div class="accordion-body pt-0">

                        <!-- Checkboxes -->
                        <div id="checkboxes_box">

                            <div class="d-flex pt-2 align-items-center">
                                <input class="form-check-input" type="checkbox" value="1" id="remarketing_tags" name="remarketing_tags" <?php echo (esc_attr($googleDetail->remarketing_tags) == 1) ? 'checked="checked"' : ''; ?>>
                                <label class="form-check-label ps-2" for="remarketing_tags">
                                    <?php esc_html_e("Enable remarketing tags", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </label>
                            </div>

                            <div class="d-flex pt-2 align-items-center">
                                <input class="form-check-input" type="checkbox" value="1" id="dynamic_remarketing_tags" name="dynamic_remarketing_tags" <?php echo (esc_attr($googleDetail->dynamic_remarketing_tags) == 1) ? 'checked="checked"' : ''; ?>>
                                <label class="form-check-label ps-2" for="dynamic_remarketing_tags">
                                    <?php esc_html_e("Enable dynamic remarketing tags", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </label>
                            </div>

                            <div class="d-flex pt-2 align-items-center">
                                <input class="form-check-input" type="checkbox" value="1" id="link_google_analytics_with_google_ads" name="link_google_analytics_with_google_ads" <?php echo (esc_attr($googleDetail->link_google_analytics_with_google_ads) == 1) ? 'checked="checked"' : ''; ?>>
                                <label class="form-check-label ps-2" for="link_google_analytics_with_google_ads">
                                    <?php esc_html_e("Link Google analytics with Google ads", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </label>
                            </div>


                            <div class="d-flex pt-2 align-items-center">
                                <?php $ga_GMC = isset($get_ee_options_data['setting']->ga_GMC) ? $get_ee_options_data['setting']->ga_GMC : 0; ?>
                                <input class="form-check-input" type="checkbox" value="1" id="ga_GMC" name="ga_GMC" <?php echo isset($_GET['feedType']) && (esc_attr($googleDetail->google_merchant_id) !== '') || esc_attr($ga_GMC) == 1 ? 'checked' : ''; ?> <?php echo ($googleDetail->google_merchant_id == "" ? "disabled" : "") ?>>
                                <label class="form-check-label ps-2" for="ga_EC">
                                    <?php esc_html_e("Link Google ads with Google Merchant Center", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </label>
                            </div>

                        </div>
                        <!-- Checkboxes end -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Accordion End -->


        <input type="hidden" id="merchant_id" name="merchant_id" value="<?php echo esc_attr($googleDetail->merchant_id) ?>">
        <input type="hidden" id="google_merchant_id" name="google_merchant_id" value="<?php echo esc_attr($googleDetail->google_merchant_id) ?>">
        <input type="hidden" id="feedType" name="feedType" value="<?php echo isset($_GET['feedType']) && $_GET['feedType'] != '' ? esc_attr(sanitize_text_field($_GET['feedType'])) : '' ?>" />
    </form>


    <!-- Facebook Form -->
    <form id="facebooksetings_form" class="convgawiz_form_webads convpixsetting-inner-box mt-4 pb-4 pt-3 convwiz_border" datachannel="FB">
        <div class="pb-1">
            <!-- Facebook ID  -->
            <?php
            $fb_pixel_id = (isset($ee_options["fb_pixel_id"]) && $ee_options["fb_pixel_id"] != "") ? $ee_options["fb_pixel_id"] : "";
            ?>
            <div id="fbpixel_box" class="py-1">
                <div class="row pt-2">
                    <div class="convwizard_pixtitle mt-0 mb-3">
                        <div class="d-flex flex-row align-items-center">
                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_meta_logo.png'); ?>" />
                            <h5 class="m-0 ms-2 h5">
                                <?php esc_html_e("Meta (Facebook) Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h5>
                        </div>
                        <div class="mt-1">
                            <?php esc_html_e("Configure conversions and audience creation effortlessly in a single step outlined below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-fb-pixel-and-fbcapi-using-conversios-plugin/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                                <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                            </a>
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-row">
                        <input <?php echo $fb_pixel_id == "" ? "" : "readonly"; ?> type="text" name="fb_pixel_id" id="fb_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($fb_pixel_id); ?>" placeholder="e.g. 518896233175751">
                        <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $fb_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                            <span class="material-symbols-outlined md-18">edit</span>
                            <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Facebook ID End-->


            <!-- Facebook ID  -->
            <?php
            $fb_conversion_api_token = (isset($ee_options["fb_conversion_api_token"]) && $ee_options["fb_conversion_api_token"] != "") ? $ee_options["fb_conversion_api_token"] : "";
            $isbox_disabled_fbcapi = "boxdisabled disabled";
            ?>
            <div id="fbapi_box" class="pt-2">
                <div class="row pt-2">
                    <div class="col-12">
                        <div class="convwizard_pixtitle mt-0 mb-3">
                            <div class="d-flex flex-row align-items-center">
                                <h6 class="m-0 ms-0 h6 d-flex">
                                    <?php esc_html_e("Meta (Facebook) Conversion API Token", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h6>
                                <span class="align-middle conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                    <img style="width: 14px;" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                    <?php esc_html_e("Premium", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </span>
                            </div>
                            <div class="mt-1">
                                <?php esc_html_e("Configure Facebook Conversions API by adding it below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-fb-pixel-and-fbcapi-using-conversios-plugin/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                                    <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                                </a>
                            </div>
                        </div>
                        <div class="d-flex">
                            <textarea readonly class="form-control disabled" style="height: 75px; cursor: not-allowed;"></textarea>
                            <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $fb_conversion_api_token == "" ? "disabled text-dark" : ""; ?>">
                                <span class="material-symbols-outlined md-18">edit</span>
                                <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Facebook ID End-->

        </div>

        <!-- Ecommerce Events -->
        <div class="mt-0 rounded-3 mt-3">
            <div class="row">
                <h5 class="fw-normal mb-1">
                    <?php esc_html_e("Select Ecommerce Events for tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
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
                        <?php esc_html_e("View content", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
                <div class="col-md-4 pr-0">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Initiate checkout", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
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
                        <?php esc_html_e("Add to cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </span>
                </div>
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Add payment info", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-12 convutp_bot">
                    <h5 class="fw-normal mb-1 d-flex">
                        <span class="material-symbols-outlined text-success me-1">info</span>
                        <?php esc_html_e(" To access full Facebook Ads tracking and the Facebook Conversions API, consider upgrading to our Starter Plan.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <span class="align-middle conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                            <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </span>
                    </h5>
                </div>
            </div>

        </div>
    </form>

    <!-- Snapchat -->
    <form id="snapchatsetings_form" class="convgawiz_form_webads convpixsetting-inner-box mt-4 pb-4 pt-3 convwiz_border" datachannel="Snapchat">
        <div>
            <!-- Snapchat Pixel -->
            <?php $snapchat_ads_pixel_id = isset($ee_options['snapchat_ads_pixel_id']) ? $ee_options['snapchat_ads_pixel_id'] : ""; ?>
            <div id="snapchat_box" class="py-1">
                <div class="row pt-2">
                    <div class="convwizard_pixtitle mt-0 mb-3">
                        <div class="d-flex flex-row align-items-center">
                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_snap_logo.png'); ?>" />
                            <h5 class="m-0 ms-2 h5">
                                <?php esc_html_e("Snapchat Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h5>
                        </div>
                    </div>

                    <label class="fw-bold mb-1 h6 text-dark">
                        <?php esc_html_e("Snapchat Pixel ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </label>

                    <div class="mt-1">
                        <?php esc_html_e("Configure conversions and audience creation effortlessly in a single step outlined below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-snapchat-pixel-using-conversios-plugin/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                            <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                        </a>
                    </div>

                    <div class="col-6 d-flex flex-row">
                        <input <?php echo $snapchat_ads_pixel_id == "" ? "" : "readonly"; ?> type="text" name="snapchat_ads_pixel_id" id="snapchat_ads_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($snapchat_ads_pixel_id); ?>" placeholder="e.g. 12e1ec0a-90aa-4267-b1a0-182c455711e9">
                        <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $fb_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                            <span class="material-symbols-outlined md-18">edit</span>
                            <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Snapchat Pixel End-->


            <!-- Snapchat CAPI Pixel -->
            <?php
            $isbox_disabled_snapchat = "boxdisabled disabled";
            $snapchat_access_token = "";
            ?>
            <div id="snapchat_capi_box" class="py-1 pt-2">
                <div class="row pt-2">
                    <div class="col-12">
                        <label class="d-flex fw-normal mb-1 text-dark">
                            <h5 class="mb-0"><?php esc_html_e("Snapchat Conversion API Token ", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>

                            <span class="align-middle conv-link-blue fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                <?php esc_html_e(" UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                        </label>

                        <div class="mt-1">
                            <?php esc_html_e("Configure Snapchat Conversions API by adding it below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-snapchat-pixel-using-conversios-plugin/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                                <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                            </a>
                        </div>

                        <div class="d-flex">
                            <textarea readonly type="text" class="form-control <?php echo esc_html($isbox_disabled_snapchat); ?>" style="height: 75px; cursor: not-allowed;"></textarea>
                            <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center disabled text-dark">
                                <span class="material-symbols-outlined md-18">edit</span>
                                <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Snapchat CAPI Pixel End-->
        </div>


        <!-- Ecommerce Events -->
        <div class="mt-0 rounded-3 mt-3">
            <div class="row">
                <h5 class="fw-normal mb-1">
                    <?php esc_html_e("Select Ecommerce Events for tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
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
                        <?php esc_html_e("Product view", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
                <div class="col-md-4 pr-0">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Begin checkout", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
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
                        <?php esc_html_e("Add to cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </span>
                </div>

                <div class="row pt-3">
                    <div class="col-md-12 convutp_bot">
                        <h5 class="fw-normal mb-1 d-flex">
                            <span class="material-symbols-outlined text-success me-1">info</span>
                            <div>
                                <?php esc_html_e("Get access to complete ecommerce tracking and snapchat conversions api, by switching to our premium plan.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <span class="align-middle conv-link-blue fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                    <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                    <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </span>
                            </div>
                        </h5>

                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Tiktok -->

    <form id="tiktoksetings_form" class="convgawiz_form_webads convpixsetting-inner-box mt-4 pb-4 pt-3 convwiz_border" datachannel="Tiktok">
        <div>
            <!-- tiktok Pixel -->
            <?php $tiKtok_ads_pixel_id = isset($ee_options['tiKtok_ads_pixel_id']) ? $ee_options['tiKtok_ads_pixel_id'] : ""; ?>
            <div id="tiktok_box" class="py-1">
                <div class="row pt-2">
                    <div class="convwizard_pixtitle mt-0 mb-3">
                        <div class="d-flex flex-row align-items-center">
                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_tiktok_logo.png'); ?>" />
                            <h5 class="m-0 ms-2 h5">
                                <?php esc_html_e("Tiktok Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h5>
                        </div>
                    </div>
                    <label class="fw-bold mb-1 h6 text-dark">
                        <?php esc_html_e("Tiktok Pixel ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </label>

                    <div class="mt-1">
                        <?php esc_html_e("Configure conversions and audience creation effortlessly in a single step outlined below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-snapchat-pixel-using-conversios-plugin/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                            <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                        </a>
                    </div>
                    <div class="col-6 d-flex flex-row">
                        <input <?php echo $tiKtok_ads_pixel_id == "" ? "" : "readonly"; ?> type="text" name="tiKtok_ads_pixel_id" id="tiKtok_ads_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($tiKtok_ads_pixel_id); ?>" placeholder="e.g. eg.CBET743C77U5BM7P178N">
                        <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $tiKtok_ads_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                            <span class="material-symbols-outlined md-18">edit</span>
                            <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- tiktok Pixel End-->


            <!-- tiktok CAPI Pixel -->
            <div id="tiktok_capi_box" class="py-1">
                <div class="row pt-2">
                    <div class="col-12">
                        <h5>
                            <?php esc_html_e("TikTok Events API Key", "enhanced-e-commerce-for-woocommerce-store"); ?>

                            <span class="align-middle conv-link-blue fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                        </h5>


                        <div class="mt-1">
                            <?php esc_html_e("Setup Tiktok Events API by adding the API ID below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-tiktok-pixel-tiktok-conversions-api-using-conversios-app/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                                <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                            </a>
                        </div>

                        <div class="d-flex">
                            <textarea readonly type="text" class="form-control" style="height: 75px; cursor: not-allowed;"></textarea>
                            <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center disabled text-dark">
                                <span class="material-symbols-outlined md-18">edit</span>
                                <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <!-- tiktok CAPI Pixel End-->
        </div>

        <!-- Ecommerce Events -->
        <div class="mt-0 rounded-3 mt-3">
            <div class="row">
                <h5 class="fw-normal mb-1">
                    <?php esc_html_e("Ecommerce Events for Tiktok Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
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
                        <?php esc_html_e("View item", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
                <div class="col-md-4 pr-0">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Intiate checkout", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
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
                        <?php esc_html_e("Add to cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </span>
                </div>
                <div class="col-md-4">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Add payment info", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
            </div>

            <div class="row pt-3">
                <div class="col-md-12 convutp_bot">
                    <h5 class="fw-normal mb-1 d-flex">
                        <span class="material-symbols-outlined text-success me-1">info</span>
                        <div>
                            <?php esc_html_e("Get access to complete ecommerce tracking and Tiktok Events api, by switching to our premium plan.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <span class="align-middle conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                        </div>
                    </h5>

                </div>
            </div>
        </div>
    </form>

    <!-- pinterest -->

    <form id="pinetrestsetings_form" class="convgawiz_form_webads convpixsetting-inner-box mt-4 pb-4 pt-3 convwiz_border" datachannel="Pinterest">
        <div>
            <!-- Pinterest Pixel -->
            <?php $pinterest_ads_pixel_id = isset($ee_options['pinterest_ads_pixel_id']) ? $ee_options['pinterest_ads_pixel_id'] : ""; ?>
            <div id="pintrest_box" class="py-1">
                <div class="row pt-2">
                    <div class="convwizard_pixtitle mt-0 mb-3">
                        <div class="d-flex flex-row align-items-center">
                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_pint_logo.png'); ?>" />
                            <h5 class="m-0 ms-2 h5">
                                <?php esc_html_e("Pinterest Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h5>
                        </div>
                    </div>

                    <label class="fw-bold mb-1 h6 text-dark">
                        <?php esc_html_e("Pinterest Pixel ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </label>

                    <div class="mt-1">
                        <?php esc_html_e("Configure conversions and audience creation effortlessly in a single step outlined below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-set-up-pinterest-pixel-using-conversios-plugin/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                            <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                        </a>
                    </div>

                    <div class="col-6 d-flex">
                        <input <?php echo $pinterest_ads_pixel_id == "" ? "" : "readonly"; ?> type="text" name="pinterest_ads_pixel_id" id="pinterest_ads_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($pinterest_ads_pixel_id); ?>" placeholder="e.g. 2612831678022">
                        <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $pinterest_ads_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                            <span class="material-symbols-outlined md-18">edit</span>
                            <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Pinterest Pixel End-->
        </div>

        <!-- Ecommerce Events -->
        <div class="mt-0 rounded-3 mt-3">
            <div class="row">
                <h5 class="fw-normal mb-1">
                    <?php esc_html_e("Select Ecommerce Events for tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
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
                        <?php esc_html_e("Product views", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </div>
                <div class="col-md-4 pr-0">
                    <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                        <span class="material-symbols-outlined lock-icon">
                            lock
                        </span>
                        <?php esc_html_e("Checkout", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
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
                        <?php esc_html_e("Add to cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </span>
                </div>


                <div class="row pt-3">
                    <div class="col-md-12 convutp_bot">
                        <h5 class="fw-normal mb-1 d-flex">
                            <span class="material-symbols-outlined text-success me-1">info</span>
                            <?php esc_html_e("Get access to complete ecommerce tracking, by switching to our premium plan.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <span class="align-middle conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                        </h5>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- Bing Form -->
    <form id="bingsetings_form" class="convgawiz_form_webads convpixsetting-inner-box mt-4 pb-4 pt-3 convwiz_border" datachannel="MicrosoftClarity">
        <div>
            <!-- MS Bing Pixel -->
            <?php $microsoft_ads_pixel_id = isset($ee_options['microsoft_ads_pixel_id']) ? $ee_options['microsoft_ads_pixel_id'] : ""; ?>
            <div id="msbing_box" class="py-1">
                <div class="row pt-2">
                    <div class="convwizard_pixtitle mt-0 mb-3">
                        <div class="d-flex flex-row align-items-center">
                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_bing_logo.png'); ?>" />
                            <h5 class="m-0 ms-2 h5">
                                <?php esc_html_e("Microsoft Ads Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h5>
                        </div>
                    </div>


                    <label class="fw-bold mb-1 h6 text-dark">
                        <?php esc_html_e("Bing Pixel ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </label>

                    <div class="mt-1">
                        <?php esc_html_e("Configure conversions and audience creation effortlessly in a single step outlined below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-set-up-microsoft-ads-pixel-using-conversios-plugin/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                            <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                        </a>
                    </div>


                    <div class="col-6 d-flex">
                        <input <?php echo $microsoft_ads_pixel_id == "" ? "" : "readonly"; ?> type="text" name="microsoft_ads_pixel_id" id="microsoft_ads_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($microsoft_ads_pixel_id); ?>" placeholder="e.g. 343003931" popuptext="Microsoft Ads (Bing) Pixel:">
                        <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $microsoft_ads_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                            <span class="material-symbols-outlined md-18">edit</span>
                            <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </button>
                    </div>
                </div>

                <div class="hideme_msbingconversios disabled" id="checkboxes_box_bing" class="pt-2">
                    <?php $msbing_conversion = isset($ee_options['msbing_conversion']) ? $ee_options['msbing_conversion'] : ""; ?>
                    <div class="d-flex pt-2 align-items-center">
                        <input class="form-check-input convchkbox_setting" type="checkbox" value="<?php echo esc_attr($msbing_conversion); ?>" id="msbing_conversion" name="msbing_conversion" <?php echo (esc_attr($msbing_conversion) == 1) ? 'checked="checked"' : ''; ?>>
                        <label class="form-check-label ps-2" for="msbing_conversion">
                            <?php esc_html_e("Enable Microsoft Ads (Bing) Conversion Tracking (Only for purchase event)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </label>
                    </div>
                </div>

            </div>
            <!-- MS Bing Pixel End-->

            <!-- Ecommerce Events -->
            <div class="mt-0 rounded-3 mt-3">
                <div class="row">
                    <h5 class="fw-normal mb-1">
                        <?php esc_html_e("Select Ecommerce Events for tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
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
                            <?php esc_html_e("Add to cart", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                    </div>
                    <div class="col-md-4 pr-0">
                        <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                            <span class="material-symbols-outlined lock-icon">
                                lock
                            </span>
                            <?php esc_html_e("Initiate checkout", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
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
                            <?php esc_html_e("Add payment info", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </span>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-12 convutp_bot">
                            <h5 class="fw-normal mb-1 d-flex">
                                <span class="material-symbols-outlined text-success me-1">info</span>
                                <?php esc_html_e("Get access to complete ecommerce tracking, by switching to our premium plan.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <span class="align-middle conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                    <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                    <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </span>
                            </h5>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>


    <!-- Twitter form -->

    <form id="pixelsetings_form" class="convgawiz_form_webads convpixsetting-inner-box mt-4 pb-4 pt-3 convwiz_border" datachannel="Twitter">
        <div>
            <!-- Twitter Pixel -->
            <?php $twitter_ads_pixel_id = isset($ee_options['twitter_ads_pixel_id']) ? $ee_options['twitter_ads_pixel_id'] : ""; ?>
            <div id="twitter_box" class="py-1">
                <div class="row pt-2">
                    <div class="">
                        <div class="convwizard_pixtitle mt-0 mb-3">
                            <div class="d-flex flex-row align-items-center">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_twitter_logo.png'); ?>" />
                                <h5 class="m-0 ms-2 h5">
                                    <?php esc_html_e("Twitter Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h5>
                            </div>
                        </div>


                        <label class="fw-bold mb-1 h6 text-dark">
                            <?php esc_html_e("Twitter Pixel ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </label>

                        <div class="mt-1">
                            <?php esc_html_e("Configure conversions and audience creation effortlessly in a single step outlined below.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <a target="_blank" href="<?php echo esc_url('https://www.conversios.io/docs/how-to-setup-twitter-pixel/?utm_source=in_app&utm_medium=pixelandanalytics_wizard&utm_campaign=knowmore'); ?>" class="conv-link-blue">
                                <u><?php esc_html_e("Know how", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                            </a>
                        </div>

                        <div class="col-6 d-flex">
                            <input <?php echo $twitter_ads_pixel_id == "" ? "" : "readonly"; ?> type="text" name="twitter_ads_pixel_id" id="twitter_ads_pixel_id" class="form-control valtoshow_inpopup_this" value="<?php echo esc_attr($twitter_ads_pixel_id); ?>" placeholder="e.g. ocihb">
                            <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_comman conv-link-blue align-items-center <?php echo $twitter_ads_pixel_id == "" ? "disabled text-dark" : ""; ?>">
                                <span class="material-symbols-outlined md-18">edit</span>
                                <span class="px-1"><?php esc_html_e("Edit", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                            </button>
                        </div>


                    </div>
                </div>
            </div>
            <!-- Twitter Pixel End-->

            <!-- Ecommerce Events -->
            <div class="mt-0 rounded-3 mt-3">
                <div class="row">
                    <h5 class="fw-normal mb-1">
                        <?php esc_html_e("Select Ecommerce Events for tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
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
                            <?php esc_html_e("Add to cart", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                    </div>
                    <div class="col-md-4 pr-0">
                        <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
                            <span class="material-symbols-outlined lock-icon">
                                lock
                            </span>
                            <?php esc_html_e("Initiate checkout", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
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
                            <?php esc_html_e("Add payment info", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </span>
                    </div>

                    <div class="row pt-3">
                        <div class="col-md-12 convutp_bot">
                            <h5 class="fw-normal mb-1 d-flex">
                                <span class="material-symbols-outlined text-success me-1">info</span>
                                <?php esc_html_e("Get access to complete ecommerce tracking, by switching to our premium plan.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <span class="align-middle conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                    <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                    <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </span>
                            </h5>
                        </div>
                    </div>

                </div>
            </div>


        </div>

    </form>

    <!-- Tab bottom buttons -->
    <div class="tab_bottom_buttons d-flex justify-content-end pt-4">

        <div class="gobackwizard d-flex" onclick="changeTabBox('webpixbox-tab')">
            <span class="material-symbols-outlined">
                keyboard_backspace
            </span>
            <div class="align-self-baseline">
                <?php esc_html_e('Go Back', "enhanced-e-commerce-for-woocommerce-store"); ?>
            </div>
        </div>

        <div class="ms-auto d-flex align-items-center">
            <button id="save_gads_finish" type="button" class="btn btn-primary px-5 ms-3">
                <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                <?php esc_html_e('Finish Setup', "enhanced-e-commerce-for-woocommerce-store"); ?>
            </button>
        </div>

    </div>
</div>

<!-- Create New Ads Account Modal -->
<div class="modal fade" id="conv_create_gads_new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">
                    <span id="before_gadsacccreated_title" class="before-ads-acc-creation"><?php esc_html_e("Enable Google Ads Account", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                    <span id="after_gadsacccreated_title" class="d-none after-ads-acc-creation"><?php esc_html_e("Account Created", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <span id="before_gadsacccreated_text" class="mb-1 lh-lg fs-6 before-ads-acc-creation">
                    <?php esc_html_e("You’ll receive an invite from Google on your email. Accept the invitation to enable your Google Ads Account.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </span>

                <div class="onbrdpp-body alert alert-primary text-start d-none after-ads-acc-creation" id="new_google_ads_section">
                    <p>
                        <?php esc_html_e("Your Google Ads Account has been created", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <strong>
                            (<b><span id="new_google_ads_id"></span></b>).
                        </strong>
                    </p>
                    <h6>
                        <?php esc_html_e("Steps to claim your Google Ads Account:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </h6>
                    <ol>
                        <li>
                            <?php esc_html_e("Accept invitation mail from Google Ads sent to your email address", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <em><?php echo (isset($tvc_data['g_mail'])) ? esc_attr($tvc_data['g_mail']) : ""; ?></em>
                            <span id="invitationLink">
                                <br>
                                <em><?php esc_html_e("OR", "enhanced-e-commerce-for-woocommerce-store"); ?></em>
                                <?php esc_html_e("Open", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <a href="" target="_blank" id="ads_invitationLink"><?php esc_html_e("Invitation Link", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </span>
                        </li>
                        <li><?php esc_html_e("Log into your Google Ads account and set up your billing preferences", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                    </ol>
                </div>

            </div>
            <div class="modal-footer">
                <button id="ads-continue" class="btn conv-blue-bg m-auto text-white before-ads-acc-creation">
                    <span id="gadsinviteloader" class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <?php esc_html_e("Send Invite", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </button>

                <button id="ads-continue-close" class="btn btn-secondary m-auto text-white d-none after-ads-acc-creation" data-bs-dismiss="modal">
                    <?php esc_html_e("Ok, close", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Conversion creation edit popup start -->
<div class="modal fade" id="conv_con_modal" tabindex="-1" aria-labelledby="conv_con_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div id="loadingbar_blue_popup" class="progress-materializecss d-none ps-2 pe-2 w-100">
                <div class="indeterminate"></div>
            </div>
            <div class="modal-header conv-blue-bg">
                <h4 id="convconmodtitle" class="modal-title text-white"></h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="conversion_setting_form" class="disabledsection">
                    <div class="row">
                        <div class="col-12">

                            <h5 for="conv_conversion_select" class="form-label" id="conv_con_modalLabel"></h5>
                            <div class="placeholder-glow">
                                <div id="conv_conversion_selectHelp" class="form-text"></div>
                                <input type="text" id="conv_conversion_textbox" class="form-control d-none" name="conv_conversion_textbox">
                                <div id="conv_conversion_selectbox">
                                    <select id="conv_conversion_select" class="form-control mw-100" name="conv_conversion_select" readonly>
                                        <option value="">
                                            <?php esc_html_e("Select Conversion Label and ID", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <h5 class="my-4"><?php esc_html_e("OR", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>

                        <div id="create_conversion_box" class="col-12">
                            <div class="col-12">
                                <button id="convcon_create_but" type="button" class="btn btn-outline-primary">
                                    <?php esc_html_e("Create Conversion id and label", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <div class="spinner-border spinner-border-sm d-none" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>
                                <div>
                                    <small><?php esc_html_e(" If you haven't yet created a conversion ID and label in your Google Ads account, you can create a new one by clicking here.", "enhanced-e-commerce-for-woocommerce-store"); ?></small>
                                </div>

                                <input type="hidden" class="form-control" id="concre_name">
                                <input type="hidden" class="form-control" id="concre_value">
                                <input type="hidden" class="form-control" id="concre_category">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="selected_conversion" id="selected_conversion">
                </div>
            </div>
            <div class="modal-footer d-flex">
                <button id="convsave_conversion_but" type="button" class="btn btn-success disabled">
                    <?php esc_html_e("Save and Finish", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    <div class="spinner-border spinner-border-sm d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Conversion creation edit popup end -->


<!-- Modal -->
<div class="modal fade" id="convgadseditconfirm" tabindex="-1" aria-labelledby="convgadseditconfirmLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="progress-materializecss d-none ps-2 pe-2 w-100 topfull_loader">
                <div class="indeterminate"></div>
            </div>
            <div class="modal-header">
                <h5 class="modal-title" id="convgadseditconfirmLabel">Change Google Ads Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Changing Google Ads Account will remove selected conversions ID and Labels
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="conv_changegadsacc_but" type="button" class="btn btn-primary">Change Now</button>
            </div>
        </div>
    </div>
</div>


<script>
    jQuery(function() {

        jQuery(document).on("change", "#conv_conversion_select", function() {
            jQuery("#conv_conversion_textbox").val(jQuery(this).val());
            jQuery("#conv_conversion_textbox").trigger('change');
        });

        jQuery(document).on("change", "#conv_conversion_textbox", function() {
            if (jQuery(this).val() != "") {
                jQuery("#convsave_conversion_but").removeClass("disabled");
            } else {
                jQuery("#convsave_conversion_but").addClass("disabled");
            }
        });

        // Only for Bing
        jQuery('.convchkbox_setting').change(function() {
            this.value = (Number(this.checked));
        });

        if (jQuery("#microsoft_ads_pixel_id").val() == "") {
            jQuery("#msbing_conversion").attr('disabled', true);
            jQuery("#msbing_conversion").prop("checked", false);
            jQuery("#msbing_conversion").attr('checked', false);
        }

        jQuery("#microsoft_ads_pixel_id").change(function() {
            if (jQuery(this).hasClass('conv-border-danger') || jQuery(this).val() == "") {
                jQuery("#msbing_conversion").attr('disabled', true);
                jQuery("#msbing_conversion").prop("checked", false);
                jQuery("#msbing_conversion").attr('checked', false);
            } else {
                jQuery("#msbing_conversion").removeAttr('disabled');
            }
        });
    });
</script>

<script>
    function clearallcheck() {
        jQuery("#checkboxes_box input.form-check-input").prop('checked', false);
        jQuery("#checkboxes_box input.form-check-input").removeAttr('checked');
    }

    function convpopuploading(state = "loading") {
        if (state == "loading") {
            jQuery("#conversion_setting_form").addClass("disabledsection");
            jQuery('#conv_conversion_select').removeAttr("readonly");
            jQuery("#loadingbar_blue_popup").removeClass("d-none");
        } else {
            jQuery("#conversion_setting_form").removeClass("disabledsection");
            jQuery('#conv_conversion_select').attr("readonly");
            jQuery("#loadingbar_blue_popup").addClass("d-none");
        }

    }


    // get list google ads dropdown options
    function list_google_ads_account(tvc_data, new_ads_id) {
        conv_change_loadingbar_popup("show");
        conv_change_loadingbar("show");
        jQuery("#ee_conversio_send_to_static").removeClass("conv-border-danger");
        jQuery(".conv-btn-connect").removeClass("conv-btn-connect-disabled");
        jQuery(".conv-btn-connect").addClass("conv-btn-connect-enabled-google");
        jQuery(".conv-btn-connect").text('Save');

        cleargadsconversions();

        var selectedValue = jQuery("#google_ads_id").val();
        var conversios_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conversios_onboarding_nonce')); ?>";
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
                action: "list_googl_ads_account",
                tvc_data: tvc_data,
                conversios_onboarding_nonce: conversios_onboarding_nonce
            },
            success: function(response) {
                var btn_cam = 'ads_list';
                if (response.error === false) {
                    var error_msg = 'null';
                    if (response.data.length == 0) {
                        showtoastdynamically("There are no Google ads accounts associated with email.");
                    } else {
                        if (response.data.length > 0) {
                            var AccOptions = '';
                            var selected = '';
                            if (new_ads_id != "" && new_ads_id != undefined) {
                                AccOptions = AccOptions + '<option value="' + new_ads_id + '" selected>' + new_ads_id + '</option>';
                            }
                            response?.data.forEach(function(item) {
                                AccOptions = AccOptions + '<option value="' + item + '">' + item + '</option>';
                            });
                            jQuery('#google_ads_id').append(AccOptions);
                            jQuery('#google_ads_id').prop("disabled", false);
                            jQuery(".conv-enable-selection").addClass('d-none');
                        }
                    }
                } else {
                    var error_msg = response.errors;
                    showtoastdynamically("No Google Ads Account Found");
                }
                jQuery('#ads-account').prop('disabled', false);
                conv_change_loadingbar_popup("hide");
                conv_change_loadingbar("hide");
            }

        });

        jQuery("#conv_conversion_select").trigger("change");
    }


    //Get conversion list
    function get_conversion_list(conversionCategory = "", selectedVal = "") {
        //conv_change_loadingbar("show");
        //jQuery("#conversion_idlabel_box").addClass("d-none");
        convpopuploading("loading");
        var data = {
            action: "conv_get_conversion_list_gads_bycat",
            gads_id: jQuery("#google_ads_id").val(),
            TVCNonce: "<?php echo esc_html(wp_create_nonce('con_get_conversion_list-nonce')); ?>",
            conversionCategory: conversionCategory
        };
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            success: function(response) {
                if (response == 0) {
                    jQuery('#conv_conversion_select').html("<option value=''>No Conversion Label and ID Found for " + conversionCategory + "</option>");
                    jQuery("#conversion_idlabel_box").removeClass("d-none");
                    jQuery("#conv_conversion_selectHelp").html("<span class='text-danger'>No conversion labels are retrived, if conversion label is available in your google ads account kindly Enter it manually in below input box.");
                    jQuery("#conv_conversion_selectbox").addClass("d-none");
                    jQuery("#conv_conversion_textbox").removeClass("d-none");
                    //conv_change_loadingbar("hide");
                } else {
                    var AccOptions = '<option value="">Select Conversion ID and Label</option>';
                    var selected = '';
                    Object.keys(response)?.forEach(item => {
                        if (selectedVal == item) {
                            selected = response[item];
                        }
                        AccOptions = AccOptions + '<option value="' + response[item] + '">' + response[item] + ' - ' + item + '</option>';
                    });
                    jQuery('#conv_conversion_select').html(AccOptions);
                    jQuery('#conv_conversion_select').prop("disabled", false);
                    jQuery("#conv_conversion_selectHelp").html("");
                }

                convpopuploading("notloading");
                jQuery("#conv_conversion_select").select2({
                    dropdownParent: jQuery("#conv_con_modal"),
                    minimumResultsForSearch: -1,
                    placeholder: function() {
                        jQuery(this).data('placeholder');
                    }
                });
                jQuery("#conv_conversion_select").val(selected).trigger("change");
            }

        });
    }



    // Create new gads acc function
    function create_google_ads_account(tvc_data) {
        var conversios_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conversios_onboarding_nonce')); ?>";
        var error_msg = 'null';
        var btn_cam = 'create_new';
        var ename = 'conversios_onboarding';
        var event_label = 'ads';
        //user_tracking_data(btn_cam, error_msg,ename,event_label);   
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
                action: "create_google_ads_account",
                tvc_data: tvc_data,
                conversios_onboarding_nonce: conversios_onboarding_nonce
            },
            beforeSend: function() {
                jQuery("#gadsinviteloader").removeClass('d-none');
                jQuery("#ads-continue").addClass('disabled');
            },
            success: function(response) {
                if (response) {
                    error_msg = 'null';
                    var btn_cam = 'complate_new';
                    var ename = 'conversios_onboarding';
                    var event_label = 'ads';

                    //add_message("success", response.data.message);
                    jQuery("#new_google_ads_id").text(response.data.adwords_id);
                    if (response.data.invitationLink != "") {
                        jQuery("#ads_invitationLink").attr("href", response.data.invitationLink);
                    } else {
                        jQuery("#invitationLink").html("");
                    }
                    jQuery(".before-ads-acc-creation").addClass("d-none");
                    jQuery(".after-ads-acc-creation").removeClass("d-none");
                    //localStorage.setItem("new_google_ads_id", response.data.adwords_id);
                    var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
                    list_google_ads_account(tvc_data, response.data.adwords_id);

                } else {
                    var error_msg = response.errors;
                    add_message("error", response.data.message);
                }
                //user_tracking_data(btn_cam, error_msg,ename,event_label);   
            }
        });
    }

    function cleargadsconversions() {
        var data = {
            action: "conv_save_gads_conversion",
            cleargadsconversions: "yes",
            CONVNonce: "<?php echo esc_html(wp_create_nonce('conv_save_gads_conversion-nonce')); ?>",
        };
        jQuery.ajax({
            type: "POST",
            url: tvc_ajax_url,
            data: data,
            success: function(response) {
                jQuery('div[conversion_name="PURCHASE"] .inlist_text_pre').find(".inlist_text_notconnected").removeClass("d-none");
                jQuery('div[conversion_name="PURCHASE"] .inlist_text_pre').find(".inlist_text_connected").addClass("d-none");
                jQuery('div[conversion_name="PURCHASE"] .inlist_text_pre').find(".inlist_text_connected").find(".inlist_text_connected_convid").html("");
                jQuery('div[conversion_name="PURCHASE"] .inlist_text_pre').next().html("+ Create Conversion");
                jQuery("#convgadseditconfirm").modal("hide");

                jQuery("input[name='COV - GAds - AddToCart - Conversion']").prop('checked', false);
                jQuery("input[name='COV - GAds - BeginCheckout - Conversion']").prop('checked', false);
                jQuery("input[name='COV - Google Ads Conversion Tracking Purchase']").prop('checked', false);
                jQuery("input[name='COV - Google ads dynamic remarketing purchase']").prop('checked', false)
            }
        });
    }
    //Onload functions
    jQuery(function() {
        var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
        var tvc_ajax_url = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
        let subscription_id = "<?php echo esc_attr($subscriptionId); ?>";
        let plan_id = "<?php echo esc_attr($plan_id); ?>";
        let app_id = "<?php echo esc_attr($app_id); ?>";
        let bagdeVal = "yes";
        let convBadgeVal = "<?php echo esc_attr($convBadgeVal); ?>";

        jQuery("#gadssetings_form, #facebooksetings_form, #snapchatsetings_form, #tiktoksetings_form, #pinetrestsetings_form, #bingsetings_form, #pixelsetings_form").change(function() {
            jQuery(this).addClass("formchanged_webads");
            let valtoshow_inpopup_this = jQuery(this).find(".valtoshow_inpopup_this").val();
            if (valtoshow_inpopup_this != "") {
                jQuery(this).find(".event-setting-row").removeClass("convdisabledbox");
            } else {
                jQuery(this).find(".event-setting-row").addClass("convdisabledbox");
            }
        });

        jQuery(".selecttwo").select2({
            minimumResultsForSearch: -1,
            placeholder: function() {
                jQuery(this).data('placeholder');
            }
        });

        jQuery(".conv-enable-selection").click(function() {
            jQuery("#convgadseditconfirm").modal('show');
        });

        jQuery("#conv_changegadsacc_but").click(function() {
            jQuery(".conv-enable-selection").addClass('disabled');
            list_google_ads_account(tvc_data);
            //conv_change_loadingbar_popup("hide");
        });

        <?php
        $gads_conversions = [];
        if (array_key_exists("gads_conversions", $ee_options)) {
            $gads_conversions = $ee_options["gads_conversions"];
        }
        ?>

        // gads_conversions = <?php echo wp_json_encode($gads_conversions); ?>;
        // jQuery.each(gads_conversions, function(key, value) {
        //     jQuery('.inlist_text_pre[conversion_name="' + key + '"]').find(".inlist_text_notconnected").addClass("d-none");
        //     jQuery('.inlist_text_pre[conversion_name="' + key + '"]').find(".inlist_text_connected").removeClass("d-none");
        //     jQuery('.inlist_text_pre[conversion_name="' + key + '"]').find(".inlist_text_connected").find(".inlist_text_connected_convid").html(value);
        //     jQuery('.inlist_text_pre[conversion_name="' + key + '"]').next().html("Edit");
        // });

        <?php
        $ee_conversio_send_to = !empty(get_option('ee_conversio_send_to')) ? get_option('ee_conversio_send_to') : "";
        if ($ee_conversio_send_to != "") {
        ?>
            jQuery('.inlist_text_pre[conversion_name="PURCHASE"]').find(".inlist_text_notconnected").addClass("d-none");
            jQuery('.inlist_text_pre[conversion_name="PURCHASE"]').find(".inlist_text_connected").removeClass("d-none");
            jQuery('.inlist_text_pre[conversion_name="PURCHASE"]').find(".inlist_text_connected").find(".inlist_text_connected_convid").html('<?php echo esc_js($ee_conversio_send_to); ?>');
            jQuery('.inlist_text_pre[conversion_name="PURCHASE"]').next().html("Edit");
        <?php } ?>


        // jQuery(".conv-enable-selection_cli").click(function() {
        //     jQuery(".conv-enable-selection_cli").addClass('disabled');
        //     get_conversion_list(tvc_data);
        // });


        jQuery(document).on("change", "form#gadssetings_form", function() {
            <?php if ($cust_g_email != "") { ?>
                var ee_conversio_send_to_static = jQuery("#ee_conversio_send_to_static").val();
                jQuery("#ee_conversio_send_to_static").removeClass("conv-border-danger");
                jQuery(".conv-btn-connect").removeClass("conv-btn-connect-disabled");
                jQuery(".conv-btn-connect").addClass("conv-btn-connect-enabled-google");
                jQuery(".conv-btn-connect").text('Save');

                if (jQuery('#remarketing_tags').is(":checked")) {
                    jQuery("input[name='COV - Google Ads Remarketing All Page']").prop('checked', true);
                } else {
                    jQuery("input[name='COV - Google Ads Remarketing All Page']").prop('checked', false);
                }
                if (jQuery('#dynamic_remarketing_tags').is(":checked")) {
                    jQuery("input[name='COV - Google Ads Dynamic Remarketing Ecommerce Events']").prop('checked', true);
                } else {
                    jQuery("input[name='COV - Google Ads Dynamic Remarketing Ecommerce Events']").prop('checked', false);
                }

            <?php } else { ?>
                jQuery(".tvc_google_signinbtn").trigger("click");
                jQuery('.event-setting-row').addClass("convdisabledbox");
            <?php } ?>
        });


        <?php if ($cust_g_email == "") { ?>
            jQuery("#conv_create_gads_new_btn").addClass("disabled");
            jQuery(".conv-enable-selection, .conv-enable-selection_cli").addClass("d-none");
            jQuery('.event-setting-row').addClass("convdisabledbox")
        <?php } ?>


        <?php if (isset($_GET['subscription_id']) && sanitize_text_field($_GET['subscription_id']) && isset($_GET['wizard_channel']) && sanitize_text_field($_GET['wizard_channel']) == "gadssettings") { ?>
            list_google_ads_account(tvc_data);
            jQuery(".conv-enable-selection").addClass("d-none");
        <?php } ?>




        jQuery("#google_ads_conversion_tracking").click(function() {
            if (jQuery("#google_ads_conversion_tracking").is(":checked")) {
                jQuery('#ga_EC').removeAttr('disabled');
                jQuery("#ga_EC").prop("checked", true);
                jQuery("#ga_EC").attr('checked', true);
                jQuery("#analytics_box_adstwo").removeClass("d-none");
            } else {
                jQuery('#ga_EC').attr('disabled', true);
                jQuery("#ga_EC").prop("checked", false);
                jQuery("#ga_EC").attr('checked', false);
                jQuery("#analytics_box_adstwo").addClass("d-none");
            }
        });

        // jQuery(document).on("change", "#google_ads_id", function() {
        //     if (jQuery("#google_ads_conversion_tracking").is(":checked")) {
        //         get_conversion_list();
        //     }
        // })

        jQuery("#save_gads_finish").click(function() {
            jQuery(this).find(".spinner-border").removeClass("d-none");
            jQuery("#save_gads_finish").addClass("disabledsection");
            save_webadsdata();
        });

        // Save data
        function save_webadsdata() {
            //openOverlayLoader('open');
            var has_error = 0;
            var selected_vals_webads = {};
            selected_vals_webads["subscription_id"] = "<?php echo esc_html($tvc_data['subscription_id']) ?>";

            selected_vals_webads["fb_pixel_id"] = jQuery("#fb_pixel_id").val();

            selected_vals_webads["snapchat_ads_pixel_id"] = jQuery("#snapchat_ads_pixel_id").val();

            selected_vals_webads["tiKtok_ads_pixel_id"] = jQuery("#tiKtok_ads_pixel_id").val();

            selected_vals_webads["pinterest_ads_pixel_id"] = jQuery("#pinterest_ads_pixel_id").val();

            selected_vals_webads["microsoft_ads_pixel_id"] = jQuery("#microsoft_ads_pixel_id").val();
            selected_vals_webads["msbing_conversion"] = jQuery("#msbing_conversion").val();

            selected_vals_webads["twitter_ads_pixel_id"] = jQuery("#twitter_ads_pixel_id").val();

            // selected_vals_webads["tiKtok_ads_pixel_id"] = jQuery("#tiKtok_ads_pixel_id").val();
            // selected_vals_webads["pinterest_ads_pixel_id"] = jQuery("#pinterest_ads_pixel_id").val();

            let channel_data_webads = {};
            let selected_event_checkboxes = {};
            jQuery(".convgawiz_form_webads").each(function() {
                let channel_name = jQuery(this).attr("datachannel");
                let pixvalinput_webads = jQuery(this).find(".valtoshow_inpopup_this").val();
                let channel_checkedVals_webads = jQuery(this).find('.tracking_event_selection:checkbox:checked').map(function() {
                    return {
                        "tagId": this.id,
                        "name": this.value,
                        "label": jQuery(this).data('label')
                    }
                }).get();

                if (jQuery("#msclarity_pixel_id").val() != "") {
                    channel_checkedVals_webads.push({
                        "tagId": "222",
                        "name": "COV - Microsoft - Clarity"
                    })
                }

                selected_event_checkboxes[channel_name] = {
                    "tag": channel_checkedVals_webads.length ? channel_checkedVals_webads : ['']
                };

                if (jQuery(this).hasClass("formchanged_webads")) {
                    if (pixvalinput_webads != "") {
                        channel_data_webads[channel_name] = {
                            "tag": channel_checkedVals_webads.length ? channel_checkedVals_webads : ['']
                        }
                    } else {
                        channel_data_webads[channel_name] = {
                            "tag": ['']
                        };
                    }
                }
            });

            selected_vals_webads['gtm_channel_settings'] = selected_event_checkboxes;
            var data_webadsclcr = {
                action: "conv_save_pixel_data",
                pix_sav_nonce: "<?php echo esc_html(wp_create_nonce('pix_sav_nonce_val')); ?>",
                conv_options_data: selected_vals_webads,
                conv_options_type: ["eeoptions", "eeapidata", "middleware"],
                //conv_options_type: ["eeoptions"],
                conv_tvc_data: tvc_data,
            };
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: data_webadsclcr,
                success: function(response) {
                    jQuery(".loadershow-content .overlaycontentbox").html('<p>Connected Successfully</p>');
                    openOverlayLoader('openshow');
                    if (jQuery("#gadssetings_form").hasClass("formchanged_webads")) {
                        save_gads_data();
                    }
                    setTimeout(function() {
                        //changeTabBox("webadsbox-tab");
                        openOverlayLoader('close');
                        changeseekbar();
                        setconfirmpopup();
                        jQuery("#save_gads_finish").find(".spinner-border").addClass("d-none");
                        jQuery("#save_gads_finish").removeClass("disabledsection");
                    }, 2000);
                }
            });
            var want_to_use_your_gtm = jQuery('input[type=radio][name=want_to_use_your_gtm]:checked').val();

        };

        function save_gads_data() {
            conv_change_loadingbar("show");
            var feedType = jQuery('#feedType').val();
            var google_ads_id = jQuery("#google_ads_id").val();
            var remarketing_tags = jQuery("#remarketing_tags").val();
            var dynamic_remarketing_tags = jQuery("#dynamic_remarketing_tags").val();
            var link_google_analytics_with_google_ads = jQuery("#link_google_analytics_with_google_ads").val();
            var google_ads_conversion_tracking = jQuery("#google_ads_conversion_tracking").val();
            var ga_EC = jQuery("#ga_EC").val();
            var ee_conversio_send_to = jQuery("#ee_conversio_send_to").val();
            var ga_GMC = jQuery('#ga_GMC').val();

            var selectedoptions = {};

            selectedoptions['google_ads_id'] = jQuery("#google_ads_id").val();

            selectedoptions["subscription_id"] = "<?php echo esc_html($tvc_data['subscription_id']) ?>";
            selectedoptions['merchant_id'] = jQuery("#merchant_id").val();
            selectedoptions['google_merchant_id'] = jQuery("#google_merchant_id").val();


            jQuery('#checkboxes_box input[type="checkbox"]').each(function() {

                if (jQuery(this).is(':checked') && !jQuery(this).hasClass('tracking_event_selection')) {
                    selectedoptions[jQuery(this).attr("name")] = jQuery(this).val();
                } else {
                    selectedoptions[jQuery(this).attr("name")] = "0";
                }
            });
            // get selected tracking
            let checkedVals = jQuery('.tracking_event_selection:checkbox:checked').map(function() {
                return {
                    "tagId": this.id,
                    "name": this.value,
                    "label": jQuery(this).data('label')
                }
            }).get();

            let channel_data = {
                "GoogleAds": {
                    "tag": (jQuery('#google_ads_id').val() != '' && checkedVals.length > 0) ? checkedVals : ['']
                }
            };
            let selected_event_checkboxes = {
                "GoogleAds": {
                    "tag": checkedVals.length > 0 ? checkedVals : ['']
                }
            }
            //selectedoptions['gtm_channel_settings'] = selected_event_checkboxes;
            //selectedoptions['gtm_channel_settings']['channel'] = 'GoogleAds'

            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: {
                    action: "conv_save_googleads_data",
                    pix_sav_nonce: "<?php echo esc_html(wp_create_nonce('pix_sav_nonce_val')); ?>",
                    conv_options_data: selectedoptions,
                    conv_tvc_data: tvc_data,
                    conv_options_type: ["eeoptions"]
                },
                beforeSend: function() {
                    jQuery(".conv-btn-connect-enabled-google").text("Saving...");
                    jQuery('.conv-btn-connect-enabled-google').addClass('disabled');
                },
                success: function(response) {
                    var user_modal_txt = "Congratulations, you have successfully connected your <br> Google Ads Account ID: " + google_ads_id + ".";
                    if (feedType !== '') {
                        window.location.replace("<?php echo esc_url($site_url_feedlist); ?>");
                    } else if (response == "0" || response == "1") {
                        jQuery(".conv-btn-connect-enabled-google").text("Connect");
                        jQuery("#conv_save_success_txt").html(user_modal_txt);

                        if (channel_data['GoogleAds']['tag'].length > 0) {
                            let selectedEventHtml = '<h4 class="fw-normal pt-3"><span><?php esc_html_e("Selected Events:", "enhanced-e-commerce-for-woocommerce-store"); ?></span></h4><div class="row p-3 pt-0 pb-0">';
                            channel_data['GoogleAds']['tag'].map(function(v, i) {
                                if (v['label'] != undefined) {
                                    selectedEventHtml += '<div class="col-md-6 d-flex"> <img class="align-self-center p-2 pt-0 pb-0" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/icon/check_circle_black.png"); ?>" /> <label class="p-2">' + v['label'] + '</label></div>';
                                }

                            })

                            selectedEventHtml += '</div>'
                            jQuery("#conv_save_event_txt").html(selectedEventHtml)
                        }


                        jQuery("#conv_save_success_modal").modal("show");
                    }
                    conv_change_loadingbar("hide");
                }
            });

            //run gtm Automation
            // if (is_gtm_automatic_process === true || is_gtm_automatic_process === 'true' || jQuery("#nav-automatic-tab").hasClass('active')) {
            //     runGtmAutomationChannelWise(channel_data);
            // }
        };

        // Create new gads acc
        jQuery("#ads-continue").on('click', function(e) {
            e.preventDefault();
            create_google_ads_account(tvc_data);
            cleargadsconversions();
            jQuery('.ggladspp').removeClass('showpopup');
        });

        jQuery('#conv_con_modal').modal({
            backdrop: 'static',
            keyboard: false
        })

        jQuery(".conv_con_modal_opener").click(function() {
            jQuery("#gadssetings_form").addClass("formchanged_webads");
            var conversion_name = jQuery(this).attr("conversion_name");
            if (conversion_name == "PURCHASE") {
                jQuery("#enhmsg").removeClass("d-none");
            }

            conversion_title_arr = {
                ADD_TO_CART: "Setup Conversion tracking for Add To Cart",
                BEGIN_CHECKOUT: "Setup Conversion tracking for Begin Checkout",
                PURCHASE: "Setup Conversion tracking for Purchase",
            }

            conversion_label_arr = {
                ADD_TO_CART: "Select conversion id and label from below",
                BEGIN_CHECKOUT: "Select conversion id and label from below",
                PURCHASE: "Select conversion id and label from below",
            }

            conversion_value_arr = {
                ADD_TO_CART: "Product Value",
                BEGIN_CHECKOUT: "Order Total",
                PURCHASE: "Order Total",
            }

            conversion_name_arr = {
                ADD_TO_CART: "Conversios-AddToCart",
                BEGIN_CHECKOUT: "Conversios-BeginCheckout",
                PURCHASE: "Conversios-Purchase",
            }

            jQuery("#conv_con_modalLabel").html(conversion_label_arr[conversion_name]);
            jQuery("#convconmodtitle").html(conversion_title_arr[conversion_name]);
            jQuery("#concre_name").val(conversion_name_arr[conversion_name]);
            jQuery("#concre_value").val(conversion_value_arr[conversion_name]);
            jQuery("#concre_category").val(conversion_name);
            jQuery("#conv_con_modal").modal("show");
            get_conversion_list(conversion_name);
        });

        jQuery('#conv_con_modal').on('hide.bs.modal', function() {

            jQuery("#ee_conversio_send_to_static").removeClass("conv-border-danger");
            jQuery(".conv-btn-connect").removeClass("conv-btn-connect-disabled");
            jQuery(".conv-btn-connect").addClass("conv-btn-connect-enabled-google");
            jQuery(".conv-btn-connect").text('Save');

            jQuery("#conv_con_modalLabel").html("");
            jQuery("#concre_name").val("");
            jQuery("#concre_value").val("");
            jQuery("#concre_category").val("");

            jQuery("#enhmsg").addClass("d-none");

            convpopuploading("show");

            var AccOptions = '<option value="">Select Conversion ID and Label</option>';
            jQuery('#conv_conversion_select').html(AccOptions);

            //jQuery("#conv_conversion_select").select2("destroy");

            jQuery(this).find(".spinner-border").addClass("d-none");
            jQuery(this).removeClass("disabled");

            jQuery("#convsave_conversion_but").addClass("disabled");

            jQuery("#conv_conversion_selectbox").removeClass("d-none");
            jQuery("#conv_conversion_textbox").addClass("d-none");
            jQuery("#conv_conversion_selectHelp").html("");
        })

        //Create GAds conversion action
        function create_gads_conversion(conversionCategory, conversionName) {
            var data = {
                action: "conv_create_gads_conversion",
                gads_id: jQuery("#google_ads_id").val(),
                TVCNonce: "<?php echo esc_html(wp_create_nonce('con_get_conversion_list-nonce')); ?>",
                conversionCategory: conversionCategory,
                conversionName: conversionName,
            };
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: data,
                success: function(response) {
                    if (response.status == "200" && response.data != undefined && response.data != "") {
                        var responsearr = response.data.split("/");
                        get_conversion_list(conversionCategory, responsearr[responsearr.length - 1]);
                    }
                    jQuery("#convcon_create_but").find(".spinner-border").addClass("d-none");
                }
            });
        }

        jQuery("#convcon_create_but").click(function() {
            convpopuploading("loading");
            jQuery("#convcon_create_but").find(".spinner-border").removeClass("d-none");
            create_gads_conversion(jQuery("#concre_category").val(), jQuery("#concre_name").val());
        });

        jQuery("#convsave_conversion_but").on("click", function() {
            jQuery("#convsave_conversion_but").addClass("disabled");
            jQuery("#convsave_conversion_but").find(".spinner-border").removeClass("d-none");
            var conversion_action = jQuery("#conv_conversion_textbox").val();
            var conversion_category = jQuery("#concre_category").val();
            var data = {
                action: "conv_save_gads_conversion",
                conversion_action: conversion_action,
                conversion_category: conversion_category,
                CONVNonce: "<?php echo esc_html(wp_create_nonce('conv_save_gads_conversion-nonce')); ?>",
            };
            jQuery.ajax({
                type: "POST",
                url: tvc_ajax_url,
                data: data,
                success: function(response) {
                    jQuery("#convsave_conversion_but").find(".spinner-border").addClass("d-none");

                    jQuery('.inlist_text_pre[conversion_name="' + conversion_category + '"]').find(".inlist_text_notconnected").addClass("d-none");
                    jQuery('.inlist_text_pre[conversion_name="' + conversion_category + '"]').find(".inlist_text_connected").removeClass("d-none");
                    jQuery('.inlist_text_pre[conversion_name="' + conversion_category + '"]').find(".inlist_text_connected").find(".inlist_text_connected_convid").html(conversion_action);
                    jQuery('.inlist_text_pre[conversion_name="' + conversion_category + '"]').next().html("Edit");

                    jQuery("#conv_con_modal").modal("hide");

                    if (conversion_category == "ADD_TO_CART") {
                        jQuery("input[name='COV - GAds - AddToCart - Conversion']").prop('checked', true);
                    } else if (conversion_category == "BEGIN_CHECKOUT") {
                        jQuery("input[name='COV - GAds - BeginCheckout - Conversion']").prop('checked', true);
                    } else {
                        jQuery("input[name='COV - Google Ads Conversion Tracking Purchase']").prop('checked', true);
                        jQuery("input[name='COV - Google ads dynamic remarketing purchase']").prop('checked', true)
                    }
                }
            });

        });
        <?php if ($google_ads_id == "" || $cust_g_email == "") { ?>
            jQuery("#gadsConversionAcco .accordion-body").addClass("disabledsection");
            jQuery(".accordion-button").addClass("text-dark");
        <?php } ?>

        jQuery(document).on("change", "#google_ads_id", function() {
            if (jQuery("#google_ads_id").val() != "") {
                jQuery("#gadsConversionAcco .accordion-body").removeClass("disabledsection");
            } else {
                jQuery("#gadsConversionAcco .accordion-body").addClass("disabledsection");
            }
            cleargadsconversions();
        });

    });
</script>