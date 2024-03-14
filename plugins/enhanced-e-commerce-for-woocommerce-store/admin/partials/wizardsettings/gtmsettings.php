<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<div class="">
    <form id="gtmsettings_form">
        <div class="convpixsetting-inner-box mt-4">
            <h5 class="fw-normal mb-1">
                <?php esc_html_e("Select the Google Tag Manager container:", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </h5>
            <?php
            $disabledsection = "disabledsection";
            $tracking_method = (isset($ee_options['tracking_method']) && $ee_options['tracking_method'] != "") ? $ee_options['tracking_method'] : "";
            $want_to_use_your_gtm = "";
            if ($tracking_method == "gtm") {
                $want_to_use_your_gtm = (isset($ee_options['want_to_use_your_gtm']) && $ee_options['want_to_use_your_gtm'] != "") ? $ee_options['want_to_use_your_gtm'] : "0";
            }
            if ((isset($_GET['wizard_channel']) && sanitize_text_field($_GET['wizard_channel']) == "gtmsettings")) {
                $want_to_use_your_gtm = "1";
            }
            $use_your_gtm_id = isset($ee_options['use_your_gtm_id']) ? $ee_options['use_your_gtm_id'] : "";
            ?>
            <div>
                <div class="py-1">
                    <input class="align-top" type="radio" checked="checked" name="want_to_use_your_gtm" id="want_to_use_your_gtm_default" value="0">
                    <label class="form-check-label ps-2" for="want_to_use_your_gtm_default">
                        <?php esc_html_e("Conversios Container - GTM-K7X94DG", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <small><?php esc_html_e("(By default, the Conversios GTM container is set for tracking purposes, and access to this container will not be available to you.)", "enhanced-e-commerce-for-woocommerce-store"); ?></small>
                    </label>

                </div>

                <div class="py-1 pt-4" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                    <input class="align-top" type="radio" name="want_to_use_your_gtm" id="want_to_use_your_gtm_own" value="1" disabled readonly>
                    <label class="form-check-label ps-2" for="want_to_use_your_gtm_own">
                        <?php esc_html_e("Automate your GTM container", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        <span class="conv-link-blue ms-2 fw-bold-500 upgradetopro_badge">
                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                            <?php esc_html_e("Premium", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </span>
                        <small><?php esc_html_e("(Choose this option to automatically configure your GTM container with all essential tags, triggers, and variables.)", "enhanced-e-commerce-for-woocommerce-store"); ?></small>
                    </label>

                </div>

            </div>

            <div class="convpremiumdisabled position-relative">
                <div class="container-section pb-3 <?php //echo $disabledsection; 
                                                    ?> convpremiumdisabled_in">
                    <div class="card border-0 p-0 shadow-none" style="max-width: 100% !important;">
                        <div class="container-setting">
                            <nav>
                                <div class="nav nav-tabs gtmautotabs" id="nav-tab" role="tablist">
                                    <!-- <button class="button-five"></button> -->
                                    <button class="nav-link active conv-nav-tab" id="nav-automatic-tab" data-bs-toggle="tab" data-bs-target="#nav-automatic" type="button" role="tab" aria-controls="nav-automatic" aria-selected="true"><span>Automatic</span></button>
                                    <button class="nav-link conv-nav-tab" id="nav-manual-tab" data-bs-toggle="tab" data-bs-target="#nav-manual" type="button" role="tab" aria-controls="nav-manual" aria-selected="false"><span>Manual</span></button>

                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-automatic" role="tabpanel" aria-labelledby="nav-automatic-tab">

                                    <div class="row pt-3">
                                        <div class="col-md-1 stepper-parent-div">
                                            <div class="stepper step-one <?php echo esc_html($stepCls) ?>">1</div>
                                        </div>
                                        <div class="col-md-11">
                                            <div class="convpixsetting-inner-box ">
                                                <div class="google_signing_image tvc_google_signinbtn">
                                                    <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/btn_google_signin_dark_normal_web.png'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex" style="height: 40px;padding-left: 23px;">
                                        <div class="vr"></div>
                                    </div>


                                    <div class="row" style="margin-top: -10px;">
                                        <div class="col-md-1 stepper-parent-div">
                                            <div class="stepper step-two <?php echo esc_html($stepCls) ?>">2</div>
                                        </div>
                                        <div class="col-md-11">
                                            <div class="gtm_div">
                                                <div class="row">
                                                    <h5 class="fw-normal mb-1">
                                                        <?php esc_html_e("GTM Account container:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </h5>
                                                </div>
                                                <div class="row pt-1">
                                                    <div class="col-md-7">
                                                        <div class="gtm-account-div">

                                                            <select class="form-select mb-3 selecttwo w-100" id="gtm_account_container_list" name="gtm_account_container_list" disabled="true" style="width: 100% !important;">
                                                                <option value="">
                                                                    <?php esc_html_e("Your GTM Account - GTM-ABCXYZ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                </option>
                                                            </select>
                                                            <input type="hidden" name="hidden_gtm_account_id" id="hidden_gtm_account_id" value="<?php echo esc_attr($gtm_account_id); ?>">
                                                            <input type="hidden" name="hidden_gtm_container_id" id="hidden_gtm_container_id" value="<?php echo esc_attr($gtm_container_id); ?>">
                                                            <input type="hidden" name="hidden_gtm_container_publicId" id="hidden_gtm_container_publicId" value="<?php echo esc_attr($gtm_container_publicId); ?>">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5 d-flex align-items-center p-0">
                                                        <div class="row">
                                                            <div class="col-3 d-flex edit-container-div">
                                                                <button type="button" class="shadow-none btn btn-sm d-flex conv-enable-selection_gtm conv-link-blue align-items-center" id="editContainerDropDown" <?php echo esc_html($select2Disabled); ?>>
                                                                    <span class="material-symbols-outlined md-18">edit</span>
                                                                    <span class="px-1">Edit</span>
                                                                </button>
                                                            </div>
                                                            <div class="col-md-9 d-flex align-items-center create-container-link">
                                                                <span><strong><span class="fw-bold-400"> Or </span><a class="fw-bold-500 conv-link-blue <?php echo esc_html($disableTextCls) ?>" id="create_container_link" href="#"> Create New Container</a></strong></span>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-1"></div>
                                        <div class="col-md-11 pt-2">
                                            <label class="conv-gtm-guide"><?php esc_html_e('Pre build tags, triggers, variable and template will be created in the selected container.', "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- <div class="impot-container-div"><button type="button" class="btn btn-success" id="import_container_btn" data-bs-toggle="modal" data-bs-target="#importContainerModal">import</button></div> -->
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Revenue settings -->

                    <div class="py-3 net_revenue_setting_box <?php //echo $disabledsection; 
                                                                ?>">
                        <div class="d-flex">
                            <h5 class="fw-normal mb-1">
                                <?php esc_html_e("Revenue Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h5>
                            <span class="material-symbols-outlined text-secondary md-18 ps-2 align-self-center" data-bs-toggle="tooltip" data-bs-placement="top" title="Select metrics from below that will be calculated for revenue tracking on the purchase event. For Example, if you select Product subtotal and Shipping then order revenue = product subtotal + shipping.">
                                info
                            </span>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input conv_revnue_checkinput" type="checkbox" id="conv_revnue_subtotal" value="subtotal" checked>
                            <label class="form-check-label" for="conv_revnue_subtotal">
                                <?php esc_html_e("Product subtotal (Sum of Product prices)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input conv_revnue_checkinput" type="checkbox" id="conv_revnue_shipping" value="shipping" checked>
                            <label class="form-check-label" for="conv_revnue_shipping">
                                <?php esc_html_e("Include Shipping", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input conv_revnue_checkinput" type="checkbox" id="conv_revnue_tax" value="tax">
                            <label class="form-check-label" for="conv_revnue_tax">
                                <?php esc_html_e("Include Tax", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <input type="hidden" name="tracking_method" id="tracking_method" value="gtm">
        </div>
    </form>




    <!-- Tab bottom buttons -->
    <div class="tab_bottom_buttons d-flex justify-content-end pt-4">
        <!-- <a class="btn btn-outline-primary px-5 me-3" href="<?php echo esc_url('admin.php?page=conversios'); ?>">
            <?php esc_html_e('Go Back', "enhanced-e-commerce-for-woocommerce-store"); ?>
        </a> -->
        <button type="button" class="btn btn-primary px-5 ms-3" id="save_gtm_settings">
            <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            <?php esc_html_e('Next', "enhanced-e-commerce-for-woocommerce-store"); ?>
        </button>
    </div>
</div>



<script>
    var store_id = '<?php echo esc_html($store_id); ?>';
    // set static width to container dropdown to avoid lenght issue when there is no account.
    // jQuery('#gtm_account_container_list').siblings('.select2:first').attr('style', 'width: 312px');
    jQuery('#gtm_account_container_list').select2();
    let automation_status = "<?php echo esc_html($automation_status); ?>";
    let plan_id = "<?php echo esc_html($plan_id); ?>";
    let gtm_account_id = "<?php echo esc_html($gtm_account_id); ?>";
    let gtm_container_id = "<?php echo esc_js($gtm_container_id); ?>";
    let gtm_container_public_id = "<?php echo esc_js($gtm_container_publicId); ?>";
    let gtm_account_container_name = "<?php echo esc_js($gtm_account_container_name); ?>";
    let subscription_id = "<?php echo esc_html($tvc_data['subscription_id']); ?>"; //subscription_id  
    let selectedOption = gtm_account_id + '_' + gtm_container_id + '_' + gtm_container_public_id;

    let is_gtm_automatic_process = false;
    let is_gtm_automatic_process_check = "<?php echo esc_html($is_gtm_automatic_process); ?>"
    let gtm_gmail = "<?php echo esc_url($g_gtm_email); ?>";
    if (is_gtm_automatic_process_check == true || is_gtm_automatic_process_check == 'true') {
        jQuery('#nav-automatic-tab').click()
    } else {
        if (jQuery('#use_your_gtm_id').val() != '') {
            <?php if ((isset($_GET['wizard_channel']) && sanitize_text_field($_GET['wizard_channel']) == "gtmsettings")) { ?>
                jQuery('#nav-automatic-tab').click()
            <?php } else { ?>
                jQuery('#nav-manual-tab').click()
            <?php } ?>
        }
    }

    // Conversios JS
    jQuery('input[type=radio][name=want_to_use_your_gtm]').change(function() {
        if (this.value == '0') {
            jQuery("#use_your_gtm_id_box").hide();
            jQuery("#use_your_gtm_id_box").addClass('d-none');
            jQuery('.container-section').hide().addClass('d-none');
            jQuery(".event-setting-row").addClass("convdisabledbox");
        } else if (this.value == '1') {
            jQuery("#use_your_gtm_id_box").show();
            jQuery("#use_your_gtm_id_box").removeClass('d-none');
            jQuery('.container-section').show().removeClass('d-none');
            jQuery(".event-setting-row").removeClass("convdisabledbox");
            jQuery('#nav-automatic-tab').click()
        }
    });

    jQuery(document).on('change', 'form#gtmsettings_form', function() {
        var want_to_use_your_gtm = jQuery('input[type=radio][name=want_to_use_your_gtm]:checked').val();
        let want_to_use_your_gtm_default = jQuery("#want_to_use_your_gtm_default").prop("checked");
        var use_your_gtm_id = jQuery('#use_your_gtm_id').val();
        let isAutomatic = (jQuery('#nav-automatic-tab').hasClass('active') && want_to_use_your_gtm && !want_to_use_your_gtm_default) ? true : false
        if (!isAutomatic && want_to_use_your_gtm == 1 && use_your_gtm_id == "") {
            disableSaveBtn()
        } else {
            enableSaveBtn()
        }
        if (isAutomatic) {
            let gtmIds = jQuery('#gtm_account_container_list').val();
            if (gtmIds != null && gtmIds.length > 2) {
                enableSaveBtn();
            } else {
                disableSaveBtn();
            }
        }

    });

    jQuery(document).on("click", "#save_gtm_settings", function() {
        jQuery(this).find(".spinner-border").removeClass("d-none");
        jQuery(this).addClass('disabledsection');
        save_gtm_settings();
    });

    function save_gtm_settings() {
       var use_your_gtm_id = jQuery('#use_your_gtm_id').val();
        var net_revenue_setting = [];
        data = {
            action: "conv_save_pixel_data",
            pix_sav_nonce: "<?php echo esc_html(wp_create_nonce('pix_sav_nonce_val')); ?>",
            conv_options_data: {
                want_to_use_your_gtm: 0,
                use_your_gtm_id: use_your_gtm_id,
                tracking_method: "gtm",
                subscription_id: "<?php echo esc_html($tvc_data['subscription_id']); ?>",
            },
            conv_options_type: ["eeoptions", "eeapidata", "middleware"],
        };

        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function() {
                jQuery(".conv-btn-connect-enabled-google").text("Saving...");
            },
            success: function(response) {
                jQuery(".loadershow-content .overlaycontentbox").html('<p>Connected Successfully</p>');
                openOverlayLoader('openshow', 'Connected with Conversios GTM Container');
                setTimeout(function() {
                    changeTabBox("webpixbox-tab");
                    openOverlayLoader('close');
                }, 2000);
                changeseekbar();
            }
        });
    };



    // change text of the container details modal collapse
    jQuery('#want_to_use_your_gtm_default').on('change', function() {
        setTimeout(() => {
            enableSaveBtn();
        }, 300)
    });

    function getAlertMessage(type = 'Success', title = 'Success', message = '', icon = 'success', buttonText = 'Ok, Done', buttonColor = '#1085F1', iconImageTag = '') {

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

    jQuery('#conv_save_automation_success_modal_btn').on('click', function() {
        jQuery('#conv-modal-redirect-btn').click();
    })
</script>