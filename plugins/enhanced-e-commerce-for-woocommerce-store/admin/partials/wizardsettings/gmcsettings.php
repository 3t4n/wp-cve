<?php 
$get_site_domain = unserialize(get_option('ee_api_data'));
$is_domain_claim = (isset($get_site_domain['setting']->is_domain_claim)) ? esc_attr($get_site_domain['setting']->is_domain_claim) : 0;
$is_site_verified = (isset($get_site_domain['setting']->is_site_verified)) ? esc_attr($get_site_domain['setting']->is_site_verified) : 0;
$plan_id = isset($get_site_domain['setting']->plan_id) ? $get_site_domain['setting']->plan_id : 1 ;
?>
<style>
    .bg-success_ {
        border: 1px solid #09BD83;
        color: #09BD83;
        background-color: #FFF;
        border-radius: 8px;
        font-size: 12px;
        padding: 0px 0px 0px 8px;
        width:89px;
        height: 28px;
        font-weight: 500;
    }
    .bg-success_:hover {
        color:#09BD83
    }
    .bg-success_:focus {
        box-shadow: none;
    }
    .bg-warnings {
        border: 1px solid #DCA310;
        color: #DCA310;
        background-color: #FBEBC2;
        border-radius: 8px;
        font-size: 12px;
        padding: 0px 0px 0px 8px;
        width:109px;
        height: 28px;
        font-weight: 500;
    }
    .bg-warnings:hover {
        color:#DCA310
    }
    .bg-warnings:focus {
        box-shadow: none;
    }
</style>
<div id="gmcSetting_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:100%;">
    <div class="indeterminate"></div>
</div>
<div class="gmcSetting_hr mb-3"></div>
<img  src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gmc_logo.png'); ?>">
<span class="span-text"><?php esc_html_e("Google Merchant Center", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
<div class="mt-2">
<span class="fs-12 fw-normal text-grey"><?php esc_html_e("Product feed to Google Merchant Center helps you improve your product's visibility in Google search results and helps to optimize your Google Campaigns resulting in high ROAS.", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
</div>
<div class="product-feed">
    <div class="card-body">
        <div class="progress-wholebox">
            <ul class="progress-steps">
                <li class="gmc_mail_step <?php echo isset($tvc_data['g_mail']) && $tvc_data['g_mail'] !== '' ? "" : "disable"; ?>" style="min-height:68px;">
                    <div class="step-box">
                    <?php
                        $g_email = isset($tvc_data['g_mail']) ? esc_attr($tvc_data['g_mail']) : "";
                        if($g_email == "") {
                    ?>
                        <div class="tvc_google_signinbtn_box" style="width: 185px;">
                            <div class="tvc_google_signinbtn google-btn" >
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/btn_google_signin_dark_normal_web.png'); ?>">
                            </div>
                        </div>
                    </div>
                    <?php } else { ?>
                        <div style="padding-top: 3px;">
                        <span class="fs-14 fw-normal text-grey">
                            <?php echo isset($tvc_data['g_mail']) ? esc_attr($tvc_data['g_mail']) : ""; ?></span>
                            <span class="conv-link-blue ps-2 tvc_google_signinbtn fs-14">
                                <?php esc_html_e("Change", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                        </div>
                    <?php }?>
                </li>
                <li class="gmc_account_id_step <?php echo ($google_merchant_center_id) ? "" : "disable"; ?>" style="min-height:120px;">
                    <div class="step-box">
                        <span class="inner-text"><?php esc_html_e("Google Merchant Center Account ID", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        <div class="row">
                            <div class="col-6">                                                    
                                <select id="google_merchant_center_id" name="google_merchant_center_id"
                                    class="form-select selecttwo" style="width: 100%" disabled>
                                    <option value="">Select Google Merchant Center Account</option>
                                    <?php if (!empty($google_merchant_center_id)) { ?>
                                        <option value="<?php echo esc_attr($google_merchant_center_id); ?>" selected data-merchant_id = "<?php echo esc_attr($merchan_id); ?>"><?php echo esc_html($google_merchant_center_id); ?></option>
                                    <?php } ?>                                                        
                                </select>
                            </div>
                            <?php if($g_email !== "") { ?>
                                <div class="col-4" style="padding-top: 10px;padding-left:0px">
                                    <?php //if($google_merchant_center_id) {?>
                                        <span class="fs-14 text-primary pointer getGMCList"><span class="material-symbols-outlined md-18">edit</span>Edit</span>
                                    <?php //} ?>
                                    <span class="fs-14">&nbsp; Or &nbsp;</span>
                                    <span class="text-primary fs-14 createNewGMC pointer">Create New</span>
                                </div>
                            <?php } ?>
                        </div>
                        <span class="fs-12 fw-normal text-grey">
                            <?php esc_html_e(" Choose your Google Merchant Center account from the dropdown menu. If you don't have one, create a new account by clicking the button above.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </span>
                        <input type="hidden" id="gmc_google_ads_id" value="<?php echo esc_attr($ee_options['google_ads_id']) ?>">
                    </div>
                </li>
                <li class="site_domain_step <?php echo ($is_site_verified) == '1' || ($is_domain_claim) == '1' ? "" : "disable"; ?>" style="min-height:68px;">
                    <div class="step-box">
                        <div class="row">
                            <div class="col-5">  
                                <div class="col-12 d-flex align-items-start">                                                 
                                    <span class="inner-text" style="width: auto"><?php esc_html_e("Verify Site:", "enhanced-e-commerce-for-woocommerce-store"); ?></span> 
                                    <button type="button" class="btn <?php echo $is_site_verified == '1' ? 'bg-success_ verifySite pointer' : 'bg-warnings verifySite pointer' ?> d-flex align-items-center ms-2">                                     
                                        <span class="material-symbols-outlined" style="font-size: 18px;">
                                        <?php echo $is_site_verified == '1' ? 'verified' : 'autorenew' ?>
                                        </span><?php echo $is_site_verified == '1' ? 'Verified' : 'Verify Now' ?>
                                    </button>
                                </div>                                
                                <div class="col-12">
                                    <span class="fs-12 fw-normal text-grey">
                                        <?php esc_html_e("When you claim your website, it gives you the right to use your website in connection with your Merchant Center account. First you need to verify your website and then you can claim it. Only the user who verified the website can claim it.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="col-12 d-flex align-items-start">                                                 
                                    <span class="inner-text" style="width: auto"><?php esc_html_e("Claim Domain:", "enhanced-e-commerce-for-woocommerce-store"); ?></span> 
                                    <button type="button" class="btn <?php echo $is_domain_claim == '1' ? 'bg-success_ verifySite pointer' : 'bg-warnings verifyDomain pointer' ?> d-flex align-items-center float-end ms-2">                                     
                                        <span class="material-symbols-outlined" style="font-size: 18px;">
                                        <?php echo $is_domain_claim == '1' ? 'verified' : 'autorenew' ?>
                                        </span><?php echo $is_domain_claim == '1' ? 'Verified' : 'Claim Now' ?>
                                    </button>
                                </div>
                                <div class="col-12">
                                    <span class="fs-12 fw-normal text-grey">
                                        <?php esc_html_e("Claiming your website grants you the authority to associate it with your Merchant Center account. If you do not claim your website, your GMC account may be disapproved.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div> 
        <button class="btn btn-channel fs-14 saveGMC" disabled>
            <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            Save
        </button>                               
    </div>
</div>
<div class="pp-modal onbrd-popupwrp" id="tvc_google_signin" tabindex="-1" role="dialog">
    <div class="onbrdppmain" role="document">
        <div class="onbrdnpp-cntner acccretppcntnr">
            <div class="onbrdnpp-hdr">
                <div class="ppclsbtn clsbtntrgr"><img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/close-icon.png'); ?>" alt="" />
                </div>
            </div>
            <div class="onbrdpp-body">
                <p>-- We recommend to use Chrome browser to configure the plugin if you face any issues during setup. --
                </p>
                <div class="google_signin_sec_left">
                    <?php if (!isset($tvc_data['g_mail']) || $tvc_data['g_mail'] == "" || $subscriptionId == "") { ?>
                        <div class="google_connect_url google-btn">

                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/btn_google_signin_dark_normal_web.png'); ?>">

                        </div>
                    <?php } else { ?>
                        <?php if ($is_refresh_token_expire == true) { ?>
                            <p class="alert alert-primary">
                                <?php esc_html_e("It seems the token to access your Google accounts is expired. Sign in again to continue.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </p>
                            <div class="google_connect_url google-btn">

                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/btn_google_signin_dark_normal_web.png'); ?>">

                            </div>
                        <?php } else { ?>
                            <div class="google_connect_url google-btn">

                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/btn_google_signin_dark_normal_web.png'); ?>">

                            </div>
                        <?php } ?>
                    <?php } ?>
                    <p>
                        <?php esc_html_e("Make sure you sign in with the google email account that has all privileges to access google analytics, google ads and google merchant center account that you want to configure for your store.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                </div>
                <div class="google_signin_sec_right">
                    <h5>
                        <?php esc_html_e("Why do I need to sign in with google?", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </h5>
                    <p>
                        <?php esc_html_e("When you sign in with Google, we ask for limited programmatic access for your accounts in order to automate below features for you:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <p><strong>
                            <?php esc_html_e("1. Google Analytics:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </strong>
                        <?php esc_html_e("To give you option to select GA accounts, to show actionable google analytics reports in plugin dashboard and to link your google ads account with google analytics account.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <p><strong>
                            <?php esc_html_e("2. Google Ads:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </strong>
                        <?php esc_html_e("To automate dynamic remarketing, conversion and enhanced conversion tracking and to create performance campaigns if required.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <p><strong>
                            <?php esc_html_e("3. Google Merchant Center:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </strong>
                        <?php esc_html_e("To automate product feed using content api and to set up your GMC account.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>

                </div>                
            </div>
        </div>
    </div>
</div>
<!--Modal -->
<div class="modal fade" id="conv_create_gmc_new" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">    
        <form id="feedForm" onfocus="this.className='focused'">        
            <div class="modal-header bg-light p-2 ps-4">
                <h5 class="modal-title fs-16 fw-500" id="feedType">
                    <?php esc_html_e("Create New Google Merchant Center Account", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </h5>
                <button type="button" class="btn-close pe-4 closeButton" data-bs-dismiss="modal" aria-label="Close"
                    onclick="jQuery('#feedForm')[0].reset()"></button>
            </div>
            <div id="create_gmc_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:98%">
                <div class="indeterminate"></div>
            </div>
            <div class="modal-body text-start">
                <div class="row">                    
                    <div class="col-7 pe-4">
                        <div id="before_gadsacccreated_text" class="mb-1 fs-6 before-gmc-acc-creation">
                            <div id="create_gmc_error" class="alert alert-danger d-none" role="alert">
                                <small></small>
                            </div>
                            <form id="conv_form_new_gmc">
                                <div class="mb-3">
                                    <span class="inner-text">Your Website URL</span> <span class="text-danger"> *</span>
                                    <input class="form-control mb-2" type="text" id="gmc_website_url" name="website_url"
                                        value="<?php echo esc_attr($tvc_data['user_domain']); ?>"
                                        placeholder="Enter Website" required>
                                    <span class="inner-text">Your Email</span><span class="text-danger"> *</span>
                                    <input class="form-control mb-2" type="text" id="gmc_email_address"
                                        name="email_address"
                                        value="<?php echo isset($tvc_data['g_mail']) ? esc_attr($tvc_data['g_mail']) : ""; ?>"
                                        placeholder="Enter email address" required>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="gmc_adult_content"
                                            name="adult_content" value="1" style="float:none">
                                        <label class="form-check-label fs-14" for="flexCheckDefault">
                                            <?php esc_html_e("My site contain", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <b class="inner-text">
                                                <?php esc_html_e("Adult Content", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </b>
                                        </label>
                                    </div>
                                    <span class="inner-text">Your Store Name</span><span class="text-danger"> *</span>
                                        <input class="form-control mb-0" type="text" id="gmc_store_name" name="store_name"
                                        value="" placeholder="Enter Store Name" required>
                                    <small>
                                        <?php esc_html_e("This name will appear in your Shopping Ads.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </small>

                                    <div class="mb-3 mt-2" id="conv_create_gmc_selectthree">
                                        <select id="gmc_country" name="country"
                                            class="form-select form-select-lg mb-3" style="width: 100%"
                                            placeholder="Select Country" required>
                                            <option value="">Select Country</option>
                                            <?php
                                            $getCountris = file_get_contents(ENHANCAD_PLUGIN_DIR . "includes/setup/json/countries.json");
                                            $contData = json_decode($getCountris);
                                            foreach ($contData as $key => $value) {
                                                ?>
                                                <option value="<?php echo esc_attr($value->code) ?>" <?php echo $tvc_data['user_country'] == $value->code ? 'selected = "selecetd"' : '' ?>>
                                                    <?php echo esc_attr($value->name) ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-check">
                                        <input id="gmc_concent" name="concent" class="form-check-input" type="checkbox"
                                            value="1" required style="float:none">
                                        <label class="form-check-label fs-12" for="concent">
                                            <?php esc_html_e("I accept the", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <a class="fs-14" target="_blank"
                                                href="<?php echo esc_url("https://support.google.com/merchants/answer/160173?hl=en"); ?>"><?php esc_html_e("terms & conditions", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                                            <span class="text-danger"> *</span>
                                        </label>
                                    </div>

                                </div>

                            </form>
                        </div>
                        <!-- Show this after creation -->
                        <div class="onbrdpp-body alert alert-primary text-start d-none after-gmc-acc-creation">
                            New Google Merchant Center Account With Id: <span id="new_gmc_id"></span> is created
                            successfully.
                        </div>
                    </div>
                    <div class="col-5 ps-4 border-start">
                        <div>
                            <h6 class="text-grey">
                                <?php esc_html_e("To use Google Shopping, your website must meet these requirements:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h6>
                            <ul class="p-0">
                                <li><a target="_blank"
                                        href="<?php echo esc_url("https://support.google.com/merchants/answer/6149970?hl=en"); ?>"><?php esc_html_e("Google Shopping ads policies", "enhanced-e-commerce-for-woocommerce-store"); ?></a></li>
                                <li><a target="_blank"
                                        href="<?php echo esc_url("https://support.google.com/merchants/answer/6150127"); ?>"><?php esc_html_e("Accurate Contact Information", "enhanced-e-commerce-for-woocommerce-store"); ?></a></li>
                                <li><a target="_blank"
                                        href="<?php echo esc_url("https://support.google.com/merchants/answer/6150122"); ?>"><?php esc_html_e("Secure collection of process and personal data", "enhanced-e-commerce-for-woocommerce-store"); ?></a></li>
                                <li><a target="_blank"
                                        href="<?php echo esc_url("https://support.google.com/merchants/answer/6150127"); ?>"><?php esc_html_e("Return Policy", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                                </li>
                                <li><a target="_blank"
                                        href="<?php echo esc_url("https://support.google.com/merchants/answer/6150127"); ?>"><?php esc_html_e("Billing terms & conditions", "enhanced-e-commerce-for-woocommerce-store"); ?></a></li>
                                <li><a target="_blank"
                                        href="<?php echo esc_url("https://support.google.com/merchants/answer/6150118"); ?>"><?php esc_html_e("Complete checkout process", "enhanced-e-commerce-for-woocommerce-store"); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
            <div class="modal-footer">                
                <button data-bs-dismiss="modal" style="width:112px; height:38px; border-radius: 4px; padding: 8px; gap:10px; border: 1px solid #ccc" class="btn btn-light fs-14 fw-medium" id="model_close_gmc_creation">Cancel</button>
                <button id="create_merchant_account_new" style="width:112px; height:38px; border-radius: 4px; padding: 8px; gap:10px;" class="btn btn-primary fs-14 fw-medium">Create</button>
            </div>
        </div>
    </div>
</div>
<script>
    //Call Google Auth pop-up
    jQuery(document).on("click", ".tvc_google_signinbtn", function() {
        jQuery('#tvc_google_signin').addClass('showpopup');
        jQuery('body').addClass('scrlnone');            
    });
    //Google Auth connect
    jQuery(document).on("click", ".google_connect_url", function() {
        const w = 600;
        const h = 650;
        const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft;
        const top = (height - h) / 2 / systemZoom + dualScreenTop;
        var url = '<?php echo esc_url($connect_gmc_url); ?>';
        url = url.replace(/&amp;/g, '&');
        url = url.replaceAll('&#038;', '&');
        const newWindow = window.open(url, "newwindow", config = `scrollbars=yes,
                width=${w / systemZoom}, 
                height=${h / systemZoom}, 
                top=${top}, 
                left=${left},toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,directories=no,status=no
                `);
        if (window.focus) newWindow.focus();
    });
    //Close Google Auth pop-up
    jQuery(document).on("click", ".clsbtntrgr, .ppblubtn", function() {
        jQuery(this).closest('.onbrd-popupwrp').removeClass('showpopup');
        jQuery('body').removeClass('scrlnone');
    });
    //Create GMC Id POP-up call
    jQuery(document).on('click', '.createNewGMC', function () {
        jQuery("#create_gmc_error").addClass("d-none");
        jQuery(".before-gmc-acc-creation").removeClass("d-none");
        jQuery(".after-gmc-acc-creation").addClass("d-none");
        jQuery('#create_merchant_account_new').removeClass('disabled')
        jQuery('#gmc_store_name').val('')
        jQuery('#gmc_concent').prop('checked', false)
        jQuery('#conv_create_gmc_new').modal('show')
        jQuery("#gmc_country").select2({
            minimumResultsForSearch: 5,
            dropdownParent: jQuery('#conv_create_gmc_selectthree'),
            placeholder: function () {
                jQuery(this).data('placeholder');
            }
        });
    })
    //Create GMC Id under our MCC account
    jQuery(document).on('click', "#create_merchant_account_new", function() {
        jQuery('.selection').find("[aria-labelledby='select2-google_merchant_center_id-container']").removeClass('selectError');
        var is_valide = true;
        var website_url = jQuery("#gmc_website_url").val();
        var email_address = jQuery("#gmc_email_address").val();
        var store_name = jQuery("#gmc_store_name").val();
        var country = jQuery("#gmc_country").val();
        var customer_id = '<?php echo esc_js($get_site_domain['setting']->customer_id); ?>';
        var adult_content = jQuery("#gmc_adult_content").is(':checked');
        if (website_url == "") {
            jQuery("#create_gmc_error").removeClass("d-none");
            jQuery("#create_gmc_error small").text("Missing value of website url");
            is_valide = false;
        } else if (email_address == "") {
            jQuery("#create_gmc_error").removeClass("d-none");
            jQuery("#create_gmc_error small").text("Missing value of email address.");
            is_valide = false;
        } else if (store_name == "") {
            jQuery("#create_gmc_error").removeClass("d-none");
            jQuery("#create_gmc_error small").text("Missing value of store name.");
            is_valide = false;
        } else if (country == "") {
            jQuery("#create_gmc_error").removeClass("d-none");
            jQuery("#create_gmc_error small").text("Missing value of country.");
            is_valide = false;
        } else if (jQuery('#gmc_concent').prop('checked') == false) {
            jQuery("#create_gmc_error").removeClass("d-none");
            jQuery("#create_gmc_error small").text("Please accept the terms and conditions.");
            is_valide = false;
        }

        if (is_valide == true) {
            var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
            var data = {
                action: "create_google_merchant_center_account",
                website_url: website_url,
                email_address: email_address,
                store_name: store_name,
                country: country,
                concent: 1,
                customer_id: customer_id,
                adult_content: adult_content,
                tvc_data: tvc_data,
                conversios_onboarding_nonce: "<?php echo esc_js(wp_create_nonce('conversios_onboarding_nonce')); ?>"
            };
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: data,
                beforeSend: function () {
                    jQuery('#create_gmc_loader').removeClass('d-none')
                    jQuery('#create_merchant_account_new, #model_close_gmc_creation, .closeButton').addClass('disabled')
                },
                success: function (response, status) {
                    jQuery('#create_gmc_loader').addClass('d-none')
                    jQuery('#model_close_gmc_creation, .closeButton').removeClass('disabled')
                    if (response.error === true ) {
                        var error_msg = 'Check your inputs!!!';
                        jQuery("#create_gmc_error").removeClass("d-none");
                        jQuery('#create_gmc_error small').text(error_msg)
                        jQuery('#create_merchant_account_new').removeClass('disabled')
                    } else if (response.account.id) { 
                        jQuery("#new_gmc_id").text(response.account.id);
                        jQuery(".before-gmc-acc-creation").addClass("d-none");
                        jQuery(".after-gmc-acc-creation").removeClass("d-none");
                        var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
                        list_google_merchant_account(tvc_data, "", response.account.id, response.merchant_id);
                    } else {
                    }
                }
            });
        }
    });
    //Remove Error class on change
    jQuery(document).on("change", "#google_merchant_center_id", function () {
        jQuery('.selection').find("[aria-labelledby='select2-google_merchant_center_id-container']").removeClass('selectError');
        if(jQuery('#google_merchant_center_id').find(':selected').val() !== '') {
            jQuery('.gmc_account_id_step').removeClass('disabled')
        }else{
            jQuery('.gmc_account_id_step').addClass('disabled')
        }
    })
    //Call site verify
    jQuery(document).on('click', '.verifySite', function() {        
        call_site_verified()
    })
    //Call Domain claim
    jQuery(document).on('click', '.verifyDomain', function() {
        call_domain_claim()
    }) 
    //Save GMC channel       
    jQuery(document).on('click', '.saveGMC', function() {
        saveChannel('GMC');
    })
    //Get Google Merchant Id
    function list_google_merchant_account(tvc_data, selelement, new_gmc_id = "", new_merchant_id = "") {
        let google_merchant_center_id = jQuery('#google_merchant_center_id').val();
        var selectedValue = '0';
        var conversios_onboarding_nonce = "<?php echo esc_js(wp_create_nonce('conversios_onboarding_nonce')); ?>";        
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
                action: "list_google_merchant_account",
                tvc_data: tvc_data,
                conversios_onboarding_nonce: conversios_onboarding_nonce
            },
            beforeSend: function(){ 
                manageGmcLoader('show')
            },
            success: function (response) {
                var btn_cam = 'gmc_list';
                jQuery('#google_merchant_center_id').removeAttr('disabled')
                if (response.error === false) {
                    var error_msg = 'null';                    
                    jQuery('#google_merchant_center_id').empty();
                    jQuery('#google_merchant_center_id').append(jQuery('<option>', {
                        value: "",
                        text: "Select Google Merchant Center Account"
                    }));
                    if (response.data.length > 0) {
                        jQuery.each(response.data, function (key, value) {
                            jQuery('#google_merchant_center_id').append(jQuery('<option>', {
                                value: value.account_id,
                                "data-merchant_id": value.merchant_id,
                                text: value.account_id,
                                selected: (value.account_id === google_merchant_center_id)
                            }));
                        });

                        if (new_gmc_id != "" && new_gmc_id != undefined) {
                            jQuery('#google_merchant_center_id').append(jQuery('<option>', {
                                value: new_gmc_id,
                                "data-merchant_id": new_merchant_id,
                                text: new_gmc_id,
                                selected: "selected"
                            }));
                            jQuery('.getGMCList').addClass('d-none')
                            jQuery('.saveGMC').prop('disabled', false)
                        }
                    } else {
                        if (new_gmc_id != "" && new_gmc_id != undefined) {
                            jQuery('#google_merchant_center_id').append(jQuery('<option>', {
                                value: new_gmc_id,
                                "data-merchant_id": new_merchant_id,
                                text: new_gmc_id,
                                selected: "selected"
                            }));
                            jQuery('.getGMCList').addClass('d-none')
                            jQuery('.saveGMC').prop('disabled', false)
                        }                        
                        console.log("error", "There are no Google merchant center accounts associated with email.");
                    }
                    manageGmcLoader('hide')
                } else {
                    var error_msg = response.errors;
                    console.log("error", "There are no Google merchant center  accounts associated with email.");
                    manageGmcLoader('hide')
                }
            }
        });
    }
    // Call site verification
    function call_site_verified() {
        jQuery("#wpbody").css("pointer-events", "none");
        manageGmcLoader('show')
        jQuery.post(tvc_ajax_url, {
            action: "tvc_call_site_verified",
            SiteVerifiedNonce: "<?php echo esc_js(wp_create_nonce('tvc_call_site_verified-nonce')); ?>"
        }, function (response) {
            manageGmcLoader('hide')
            jQuery("#wpbody").css("pointer-events", "auto");
            var rsp = JSON.parse(response);
            if (rsp.status == "success") {
                // jQuery('.site_domain_step')
                var html ='<span class="material-symbols-outlined" style="font-size: 18px;">verified</span>Verified';
                jQuery('.verifySite').removeClass('bg-warnings')
                jQuery('.verifySite').addClass('bg-success_')
                jQuery('.verifySite').html(html)
                jQuery(".modal_popup_logo").html('check_circle')
                jQuery('.modal_popup_logo').removeClass('modal_popup_logo_error')
                jQuery('.modal_popup_logo').addClass('modal_popup_logo_success')
                jQuery('.conv_popup_txt').text('Congratulations')
                jQuery('#conv_popup_txt_msg').text('Site is verified')
                jQuery('#conv_modal_popup').modal('show')
            } else {           
                jQuery(".modal_popup_logo").html('cancel')      
                jQuery('.modal_popup_logo').removeClass('modal_popup_logo_success')
                jQuery('.modal_popup_logo').addClass('modal_popup_logo_error')
                jQuery('.conv_popup_txt').text('Error')
                jQuery('#conv_popup_txt_msg').text(rsp.message)
                jQuery('#conv_modal_popup').modal('show')  
            }
        });
    }
    //Call domain claim
    function call_domain_claim() {
        jQuery("#wpbody").css("pointer-events", "none");
        manageGmcLoader('show')
        jQuery.post(tvc_ajax_url, {
            action: "tvc_call_domain_claim",
            apiDomainClaimNonce: "<?php echo esc_js(wp_create_nonce('tvc_call_domain_claim-nonce')); ?>"
        }, function (response) {
            manageGmcLoader('hide')
            jQuery("#wpbody").css("pointer-events", "auto");
            var rsp = JSON.parse(response);
            if (rsp.status == "success") {                
                var html ='<span class="material-symbols-outlined" style="font-size: 18px;">verified</span>Verified';
                jQuery('.verifyDomain').removeClass('bg-warnings')
                jQuery('.verifyDomain').addClass('bg-success_')
                jQuery('.verifyDomain').html(html)
                jQuery(".modal_popup_logo").html('check_circle')
                jQuery('.modal_popup_logo').removeClass('modal_popup_logo_error')
                jQuery('.modal_popup_logo').addClass('modal_popup_logo_success')
                jQuery('.conv_popup_txt').text('Congratulations')
                jQuery('#conv_popup_txt_msg').text('Domain claim successfull')
                jQuery('#conv_modal_popup').modal('show')
            } else {
                jQuery(".modal_popup_logo").html('cancel')
                jQuery('.modal_popup_logo').removeClass('modal_popup_logo_success')
                jQuery('.modal_popup_logo').addClass('modal_popup_logo_error')
                jQuery('.conv_popup_txt').text('Error')
                jQuery('#conv_popup_txt_msg').text(rsp.message)
                jQuery('#conv_modal_popup').modal('show')                
            }
        });
    }
    // GMC loader
    function manageGmcLoader(display = "show") {
        if(display == "show") {
            jQuery('#gmcSetting_loader').removeClass('d-none')
            jQuery(".verifySite, .verifyDomain, .createNewGMC, .channelTabSave, .saveGMC").css("pointer-events", "none");
        } else {
            jQuery(".verifySite, .verifyDomain, .createNewGMC, .channelTabSave, .saveGMC").css("pointer-events", "auto");
            jQuery('#gmcSetting_loader').addClass('d-none')
        }
    }
</script>