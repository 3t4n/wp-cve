<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$pixel_settings_arr = array(
    "gtmsettings" => array(
        "logo" => "/admin/images/logos/conv_gtm_logo.png",
        "title" => "Google Tag Manager",
        "topnoti" => "Use your own Google Tag Manager account to increase the page speed and customize events as per your requirements."
    ),
    "gasettings" => array(
        "logo" => "/admin/images/logos/conv_ganalytics_logo.png",
        "title" => "Google Analytics 4",
        "topnoti" => "Universal Analytics (Google Analytics 3) Will no longer be available after July 1st 2023. A new GA4 property will automatically be created for you, and your universal Analytics configurations will be copied to the new GA4 property, unless you opt out."
    ),
    "gadssettings" => array(
        "logo" => "/admin/images/logos/conv_gads_logo.png",
        "title" => "Google Ads Remarketing & Conversion Tracking",
        "topnoti" => "Enabling Google Ads enhanced conversion along with Google Ads conversion tracking helps in campaign performance."
    ),
    "fbsettings" => array(
        "logo" => "/admin/images/logos/conv_meta_logo.png",
        "title" => "Facebook Pixel & Facebook Conversions API (Meta)",
        "topnoti" => "Enable FBCAPI along with FB pixel for higher accuracy and better campaign performance."
    ),
    "bingsettings" => array(
        "logo" => "/admin/images/logos/conv_bing_logo.png",
        "title" => "Microsoft Clarity & Ads Pixel (Bing)",
    ),
    "twittersettings" => array(
        "logo" => "/admin/images/logos/conv_twitter_logo.png",
        "title" => "Twitter Pixel",
    ),
    "pintrestsettings" => array(
        "logo" => "/admin/images/logos/conv_pint_logo.png",
        "title" => "Pinterest Pixel",
    ),
    "snapchatsettings" => array(
        "logo" => "/admin/images/logos/conv_snap_logo.png",
        "title" => "Snapchat Pixel",
    ),
    "tiktoksettings" => array(
        "logo" => "/admin/images/logos/conv_tiktok_logo.png",
        "title" => "TikTok Pixel",
    ),
    "customintgrationssettings" => array(
        "logo" => "/admin/images/logos/conv_event_track_custom.png",
        "title" => "Event Tracking - Custom Integration",
    ),
    "gmcsettings" => array(
        "logo" => "/admin/images/logos/conv_gmc_logo.png",
        "title" => "Google Merchant Center Account",
        "topnoti" => "Product feed to Google Merchant Center helps you improve your product's visibility in Google search results and helps to optimize your Google Campaigns resulting in high ROAS."
    ),
    "tiktokBusinessSettings" => array(
        "logo" => "/admin/images/logos/conv_tiktok_logo.png",
        "title" => "TikTok Business Account",
        "topnoti" => "Product feed to TikTok catalog help you to run ads on tiktok for your product and reach out to more than 900 Million people."
    ),
    "hotjarsettings" => array(
        "logo" => "/admin/images/logos/conv_hotjar_logo.png",
        "title" => "Hotjar Pixel",
    ),
    "crazyeggsettings" => array(
        "logo" => "/admin/images/logos/conv_crazyegg_logo.png",
        "title" => "Crazyegg Pixel",
    ),
);

$subpage = (isset($_GET["subpage"]) && $_GET["subpage"] != "") ? esc_attr(sanitize_text_field($_GET["subpage"])) : "";
$version = PLUGIN_TVC_VERSION;

$googleDetail = "";
$tracking_option = "UA";
$login_customer_id = "";

$TVC_Admin_Helper = new TVC_Admin_Helper();
$customApiObj = new CustomApi();
$app_id = CONV_APP_ID;
//get user data
$ee_options = $TVC_Admin_Helper->get_ee_options_settings();
$ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
$get_ee_options_data = $TVC_Admin_Helper->get_ee_options_data();
$tvc_data = $TVC_Admin_Helper->get_store_data();

$subscriptionId = $ee_options['subscription_id'];

$url = $TVC_Admin_Helper->get_onboarding_page_url();
$is_refresh_token_expire = false;

//get badge settings
$convBadgeVal = isset($ee_options['conv_show_badge']) ? $ee_options['conv_show_badge'] : "";
$convBadgePositionVal = isset($ee_options['conv_badge_position']) ? $ee_options['conv_badge_position'] : "";

$g_mail = get_option('ee_customer_gmail');
$tvc_data['g_mail'] = "";
if ($g_mail) {
    $tvc_data['g_mail'] = sanitize_email($g_mail);
}

//check if redirected from the authorization
if (isset($_GET['subscription_id']) && sanitize_text_field($_GET['subscription_id'])) {
    $subscriptionId = sanitize_text_field($_GET['subscription_id']);
    if (isset($_GET['g_mail']) && sanitize_email($_GET['g_mail'])) {
        $tvc_data['g_mail'] = sanitize_email($_GET['g_mail']);
        $ee_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
        $ee_additional_data['ee_last_login'] = sanitize_text_field(current_time('timestamp'));
        $TVC_Admin_Helper->set_ee_additional_data($ee_additional_data);
        $is_refresh_token_expire = false;
    }
}

$resource_center_data = array();
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
            $rcd_postdata = array("app_id" => CONV_APP_ID, "platform_id" => 1, "plan_id" => $plan_id, "screen_name" => $subpage);
            $resource_center_res = $customApiObj->get_resource_center_data($rcd_postdata);
            if (!empty($resource_center_res->data)) {
                $resource_center_data = $resource_center_res->data;
            }
        }
    }
}
?>
<!-- Main container -->
<div class="container-old conv-container conv-setting-container pt-4">
    <!-- Main row -->
    <div class="row justify-content-center">
        <!-- Main col8 center -->
        <div class="col-xs-12 row convfixedcontainerfull m-0 p-0">

            <div class="col-md-8 g-0">
                <!-- Pixel setting header -->
                <div class="conv_pixel_settings_head d-flex flex-row mt-0 align-items-center mb-3">
                    <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics'); ?>" class="link-dark rounded-3 border border-2 hreflink">
                        <span class="material-symbols-outlined p-1">arrow_back</span>
                    </a>
                    <div class="ms-4 ps-1">
                        <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . $pixel_settings_arr[$subpage]['logo']); ?>" />
                    </div>
                    <h4 class="m-0 fw-normal ms-2 fw-bold-500">
                        <?php
                        printf(
                              esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ),
                              esc_html( $pixel_settings_arr[$subpage]['title'] )
                        );
                        ?>
                    </h4>
                    <button class="btn text-white ms-auto d-flex justify-content-center conv-btn-connect conv-btn-connect-disabled" style="width:110px">Save</button>
                </div>
                <!-- Pixel setting header end-->

                <!-- Pixel setting body -->

                <div id="loadingbar_blue" class="progress-materializecss d-none">
                    <div class="indeterminate"></div>
                </div>
                <?php
                if (array_key_exists($subpage, $pixel_settings_arr)) {
                    require_once("singlepixelsettings/" . $subpage . '.php');
                }
                ?>
                <!-- Pixel setting body end -->

                <!-- Hero block -->
                <div class="conv_hero_block">
                    <?php if ($subpage == "gtmsettings") { ?>
                        <div class="convcard p-4 mt-0 rounded-3 shadow-sm mt-3">
                            <h4>Benefits of Using Your Own GTM Container:</h4>
                            <p>As a free user, our plugin automatically tags your website using Conversios Global Container.
                            </p>
                            <p>But as a pro user, you gain full control by integrating your own GTM account. Unlock 76+
                                pre-built tags and triggers, along with powerful data layer automation.</p>
                            <p><a class="conv-link-blue" target="_blank" href="https://www.conversios.io/wordpress/all-in-one-google-analytics-pixels-and-product-feed-manager-for-woocommerce-pricing/?utm_source=inapp&utm_medium=gtm_heroblock&utm_campaign=benefitsofowngtm">Upgrade
                                    now for ultimate customization and optimization!</a></p>
                            <ol>
                                <li><b>Faster Page Speed:</b> Optimize your site with your own GTM container for
                                    lightning-fast load times and improved user experience.</li>
                                <li><b>Custom Event Tracking:</b> Tailor event tracking to your needs, gaining insights into
                                    user behaviour, conversions, and engagement.</li>
                                <li><b>Centralized Management:</b> Take control of tags and analytics in one place. Manage
                                    and update with ease, saving time and reducing reliance on developers.</li>
                                <li><b>Flexible Scalability:</b> Easily adapt to changing tracking requirements and scale
                                    your business. Add or modify tags without disrupting your site's functionality.</li>
                                <li>Reach out to our experts for any clarification at info@conversios.io or from the help
                                    section.</li>
                            </ol>
                        </div>
                    <?php } ?>

                    <?php if ($subpage == "gasettings") { ?>
                        <div class="convcard p-4 mt-0 rounded-3 shadow-sm mt-3">
                            <h4>Tips to validate Google Analytics 4 tracking:</h4>
                            <ol>
                                <li>Validate from GTM preview if the events are being tracked as expected. Complete an
                                    entire user journey to validate every event and data is being tracked. <a href="https://youtu.be/KGGI8m_oiaU" class="conv-link-blue" target="_blank">Refer
                                        this video to validate</a>.</li>
                                <li>GA4 takes up to 48 hours to reflect data in your GA4 account. Hence, if you are able to
                                    validate tracking in step 1, do not worry your data will be populated in GA4 in upto 48
                                    hours.</li>
                                <li>Monitor the tracking on Conversios - GA4 reporting dashboard for up 5-7 days and compare
                                    it with your woocommerce data.</li>
                                <li>If you still find data discrepency, reach out to your dedicated customer success manager
                                    or reach out directly at info@conversios.io.</li>
                            </ol>
                        </div>
                    <?php } ?>



                    <?php if ($subpage == "gadssettings") { ?>
                        <div class="convcard p-4 mt-0 rounded-3 shadow-sm mt-3">
                            <h4>Tips to validate Google Ads conversion tracking and leveraging it to optimize your Google
                                Ads campaigns:</h4>
                            <ol>
                                <li>Make sure you select right conversion id and label in the settings above and validate
                                    the conversion tracking <a href="https://youtu.be/iBOayyJijnU" class="conv-link-blue" target="__blank">by following this steps</a>.</li>
                                <li>Enable enhanced conversion tracking from the settings above this helps Google understand
                                    your traffic better and it in turn optimize your campaigns.</li>
                                <li>You can see the conversion tracking data for your campaigns only if the campaigns are
                                    live and it takes upto 24 hours to reflect the data in Google Ads.</li>
                                <li>Connect your Google Analytics 4 account with Google Ads account for better attribution
                                    and detail analysis.</li>
                            </ol>
                        </div>
                    <?php } ?>

                    <?php if ($subpage == "fbsettings") { ?>
                        <div class="convcard p-4 mt-0 rounded-3 shadow-sm mt-3">
                            <h4>Tips to validate and leverage FB pixel and FBCAPI:</h4>
                            <ol>
                                <li>It is advised to use FB pixel and FBCAPI together for better accuracy and efficiency.
                                    Hence, make sure you have configured both in above settings.</li>
                                <li>Once you have set up FB pixel and/or FBCAPI, validate if the tracking is accurate on
                                    your store <a href="https://youtu.be/yRf83wuxU4E" target="_blank" class="conv-link-blue"> by visiting this guide </a>.</li>
                                <li>Open your FB business manager and go to Assets > Pixels to check if the data is being
                                    populated.</li>
                                <li>Connect with your dedicated customer success manager if you are facing any issue or
                                    reach out to <a class="conv-link-blue" href="mailto:info@conversios.io">info@conversios.io</a> with your query.</li>
                            </ol>
                        </div>
                    <?php } ?>
                </div>
                <!-- Hero block end -->

            </div>

            <!-- Resource center sidebar -->
            <div class="col-md-4 pe-0 ps-4">
                <div class="convcard mt-0 rounded-3 shadow-sm h-100">

                    <div class="conv-rc-side-header border-2 border-bottom">
                        <h6 class="h6 fw-normal m-0 p-3">
                            <?php esc_html_e("Recommended For You", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </h6>
                    </div>

                    <div class="conv-rc-side-body">
                        <?php
                        foreach ($resource_center_data as $resource) {
                            if ($resource->screen_name != $subpage) {
                                continue;
                            }
                        ?>
                            <a target="_blank" href="<?php echo esc_url($resource->link); ?>">
                                <div class="card m-0 border-0" style="max-width: 540px;">

                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="<?php echo esc_url($resource->thumbnail_url); ?>" class="img-fluid border rounded">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body p-0 ps-2">
                                                <h6 class="fw-normal mb-1">
                                                    <?php echo esc_attr($resource->title); ?>
                                                </h6>
                                            </div>
                                            <div class="ps-2">
                                                <span class="text-secondary">
                                                    <?php echo esc_attr($resource->type); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
        <!-- Main col8 center End-->
    </div>
    <!-- Main row End -->

</div>
<!-- Main container End -->


<!-- Success Save Modal -->
<div class="modal fade" id="conv_save_success_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">

            </div>
            <div class="modal-body text-center p-0">
                <img style="width:184px;" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_modal_img_highfive.png'); ?>">
                <h3 class="fw-normal pt-3">Successfully Connected</h3>
                <span id="conv_save_success_txt" class="mb-1 lh-lg"></span>
            </div>
            <div class="modal-footer border-0 pb-4 mb-1">
                <button type="button" id="conv-modal-redirect-btn" class="btn conv-blue-bg m-auto text-white">Ok, Done</button>
            </div>
        </div>
    </div>
</div>
<!-- Success Save Modal End -->



<!-- Upgrade to PRO modal -->
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



<script>
    //Other then GTM,GA,GAds
    function change_top_button_state(state = "enable") {
        if (state == "enable" && !jQuery("form#pixelsetings_form input").hasClass("conv-border-danger")) {
            jQuery(".conv-btn-connect").removeClass("conv-btn-connect-disabled");
            jQuery(".conv-btn-connect").addClass("conv-btn-connect-enabled");
            jQuery(".conv-btn-connect").text('Save');
        }

        if (state == "disable") {
            jQuery(".conv-btn-connect").addClass("conv-btn-connect-disabled");
            jQuery(".conv-btn-connect").removeClass("conv-btn-connect-enabled");
            jQuery(".conv-btn-connect").text('Save');
        }
    }

    function conv_change_loadingbar(state = 'show') {
        if (state == 'show') {
            jQuery("#loadingbar_blue").removeClass('d-none');
        } else {
            jQuery("#loadingbar_blue").addClass('d-none');
        }
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
        jQuery(swalContainer).find('.swal2-icon-show').removeClass('swal2-' + icon).removeClass('swal2-icon').addClass('justify-content-center')
        jQuery('.swal2-icon-show').html(iconImageTag)

    }
    //On page load logics
    jQuery(function() {
        var tvc_ajax_url = '<?php echo esc_url(admin_url('admin-ajax.php')); ?>';
        let subscription_id = "<?php echo esc_attr($subscriptionId); ?>";

        //initilize select2 for the inner screens
        jQuery(".selecttwo").select2({
            minimumResultsForSearch: -1,
            placeholder: function() {
                jQuery(this).data('placeholder');
            }
        });

        // Show tootltip on click
        jQuery('a[data-bs-toggle="tooltip"]').tooltip({
            trigger: 'click'
        });


        // Enable save button on form change
        jQuery(document).on("change", "form#pixelsetings_form", function() {
            change_top_button_state("enable");
        });

        // Client side pixel id validations
        jQuery(document).on("input", "#fb_pixel_id, #microsoft_ads_pixel_id, #twitter_ads_pixel_id, #pinterest_ads_pixel_id, #snapchat_ads_pixel_id, #tiKtok_ads_pixel_id, #hotjar_pixel_id, #crazyegg_pixel_id, #msclarity_pixel_id", function() {
            var ele_id = this.id;
            var ele_val = jQuery(this).val();
            var regex_arr = {
                fb_pixel_id: new RegExp(/^\d{14,16}$/m),
                microsoft_ads_pixel_id: new RegExp(/^\d{7,9}$/m),
                twitter_ads_pixel_id: new RegExp(/^[a-z0-9]{5,7}$/m),
                pinterest_ads_pixel_id: new RegExp(/^\d{13}$/m),
                snapchat_ads_pixel_id: new RegExp(/^[a-z0-9\-]*$/m),
                tiKtok_ads_pixel_id: new RegExp(/^[A-Z0-9]{20,20}$/m),
                hotjar_pixel_id: new RegExp(/^[0-9]{7,7}$/m),
                crazyegg_pixel_id: new RegExp(/^[0-9]{8,8}$/m),
                msclarity_pixel_id: new RegExp(/^[a-z0-9]{10,10}$/m),
            };
            if (ele_val.match(regex_arr[ele_id]) || ele_val === "") {
                jQuery(this).removeClass("conv-border-danger");
                change_top_button_state("enable");
            } else {
                jQuery(this).addClass("conv-border-danger");
                change_top_button_state("disable");
            }

        });


        //Save data other then GTM,GA,GAds
        jQuery(document).on("click", ".conv-btn-connect-enabled", function() {
            conv_change_loadingbar("show");
            jQuery(this).addClass('disabled');
            var valtoshow_inpopup = jQuery("#valtoshow_inpopup").val() + " " + jQuery(
                ".valtoshow_inpopup_this").val();
            var selected_vals = {};
            selected_vals["subscription_id"] = "<?php echo esc_html($tvc_data['subscription_id']) ?>";

            jQuery('form#pixelsetings_form input, textarea').each(function() {
                selected_vals[jQuery(this).attr("name")] = jQuery(this).val();
            });

            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: {
                    action: "conv_save_pixel_data",
                    pix_sav_nonce: "<?php echo esc_html(wp_create_nonce('pix_sav_nonce_val')); ?>",
                    conv_options_data: selected_vals,
                    conv_options_type: ["eeoptions"],
                },
                beforeSend: function() {
                    jQuery(".conv-btn-connect-enabled").text("Saving...");
                },
                success: function(response) {
                    var user_modal_txt =
                        "Congratulations, you have successfully connected your <br> " +
                        valtoshow_inpopup;

                    if (response == "0" || response == "1") {
                        jQuery(".conv-btn-connect-enabled").text("Save");
                        jQuery("#conv_save_success_txt").html(user_modal_txt);
                        jQuery("#conv_save_success_modal").modal("show");
                    }
                    conv_change_loadingbar("hide");
                }

            });

        });

        jQuery("#conv-modal-redirect-btn").click(function() {
            var redirectscreen =
                '<?php echo (isset($_GET["redirectscreen"]) && $_GET["redirectscreen"] == "productfeed") ? "1" : "0"; ?>';
            var subPage =
                '<?php echo (isset($_GET["subpage"]) && $_GET["subpage"] == "gmcsettings") ? "1" : "0"; ?>';
            if (subPage == "1") {
                redirectscreen = "1";
            }
            if (redirectscreen == "1") {
                location.href = "admin.php?page=conversios-google-shopping-feed";
            } else {
                location.href = "admin.php?page=conversios-google-analytics";
            }

        });

    });
</script>

<?php
// echo '<pre>--ee_options--';
// print_r($ee_options);
// echo '</pre>';


// echo '<pre>--tvc_data---';
// print_r($tvc_data);
// echo '</pre>';


// echo '<pre>--ee_additional_data--';
// print_r($ee_additional_data);
// echo '</pre>';

// echo '<pre>--ee_api_data--';
// print_r($get_ee_options_data);
// echo '</pre>';



// echo '<pre>--Google Details--';
// print_r($googleDetail);
// echo '</pre>';
?>