<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$TVC_Admin_Helper = new TVC_Admin_Helper();
$customApiObj = new CustomApi();
if(isset($_GET['g_mail']) && isset($_GET['wizard_channel'])) {
    update_option('ee_customer_gmail', sanitize_email($_GET['g_mail']));
}
$ee_options = unserialize(get_option("ee_options"));
$tvc_data = $TVC_Admin_Helper->get_store_data();
$g_mail = get_option('ee_customer_gmail');
if(isset($_GET['g_mail'])) {
    $g_mail = sanitize_email($_GET['g_mail']);
}
$tvc_data['g_mail'] = "";
if ($g_mail) {
    $tvc_data['g_mail'] = sanitize_email($g_mail);
}
$subscriptionId = $ee_options['subscription_id'];
$app_id = 1;
$convBadgeVal = isset($ee_options['conv_show_badge'])?$ee_options['conv_show_badge']:"";
$pixelprogressbarclass = 0;
$google_merchant_center_id  = '';
$merchan_id = '';
$google_ads_id = '';
$is_channel_connected = false;
if(isset($ee_options['google_merchant_center_id']) && $ee_options['google_merchant_center_id'] !== ''){
    $google_merchant_center_id  = $ee_options['google_merchant_center_id'];
    $merchan_id = isset($ee_options['merchant_id']) ? $ee_options['merchant_id'] : '';    
}
if(isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] !== ''){
    $google_ads_id  = $ee_options['google_ads_id'];
}
if(isset($ee_options['google_merchant_center_id']) && $ee_options['google_merchant_center_id'] !== '' && isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] !== '') {
    $is_channel_connected = true;
}
$connect_gmc_url = $TVC_Admin_Helper->get_custom_connect_url(admin_url() . 'admin.php?page=conversios&wizard=campaignManagement_gmcsetting');
$getCountris = @file_get_contents(ENHANCAD_PLUGIN_DIR . "includes/setup/json/countries.json");
$contData = json_decode($getCountris);
$site_url = "admin.php?page=conversios-google-shopping-feed&tab=";
$dashboard_url = "admin.php?page=conversios";
$is_refresh_token_expire = false;
$connect_url_gagads = "";
$connect_url_gaa = "";
$connect_url_gadss = "";
$plan_id = 1;
?>
<style>
    body {
        background: white !important;
    }
    
    #conversioshead,
    #conversioshead_notice {
        display: none;
    }
    .nav-item {
        width: 33.3%;
    }
    .nav-link {
        color:#495057;
        background-color: #fff;
        border-color: #fff;
        padding: 0.2rem 1rem 0.5rem 0.2rem;
    }
    .nav-tabs .nav-link.active {
        color:#0d6efd;
        background-color: #fff;
        border-color: #fff;
    }
    .nav-tabs .nav-link:hover {
        background-color: #fff;
        border-color: #fff;
    }
    .w-33 {
        width: 50%!important;
    }
    .w-66 {
        width: 100%!important;
    }
    
    img {
        width: 24px;
        height: 24px;
    }
    .span-text {
        width: 240px;
        height: 24px;
        font-weight: 700;
        font-size: 16px;
        line-height: 24px;
    }
    .inner-text {
        width: 226px;
        height: 22px;
        font-weight: 600;
        font-size: 14px;
        line-height: 24px;
        color: #5F6368;
    }
    .form-control, .select2.select2-container--default .select2-selection--single {
        border-radius: 4px;
        padding-left: 0px;
        height: 40px;
    }  
    
    .fs-15 {
        font-size: 15px;
    }
    .placeholder {
        min-height: 0.5em;
        vertical-align: bottom;
        background-color: #387EF5;
    }
    .placeholder-wave {
        margin: 0px !important;
    }
    .text-grey {
        color: #5F6368;
    }
    .select2-results__option--selectable {
        font-size: 14px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: 14px;
    }
    .pointer {
        cursor: pointer;
    }
    .google-btn img {
        width: 100% !important;
        height: auto !important;
    }
    .btn-channel-primary {
        width: 183px;
        height: 38px;
        border-radius: 4px;
        padding: 8px 10px 8px 10px;
        gap: 10px;
        color: #FFF;
        background-color: #1085F1;
        border-color: #1085F1;
    }
    .btn-channel-primary:hover {
        color: #FFF;
    }
    .btn-channel {
        width: 150px;
        height: 38px;
        border-radius: 4px;
        padding: 8px 10px 8px 10px;
        gap: 10px;
        color: #FFF;
        background-color: #1085F1;
        border-color: #1085F1;
    }
    .btn-channel:hover {
        color: #FFF;
    }
    .fw-semibold {
        font-weight: 600;
    }
    .progress-materializecss {
        z-index: 99;
    }

    .modal_popup_logo_success {
        color: #09BD83;
    }
    .modal_popup_logo_error {
        color: #ff3333;
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
    .errorInput {
        border: 1.3px solid #ef1717 !important ;
        padding: 0px;
        border-radius: 6px;
    }
    .form-check-input:checked {
        border-radius: 4px !important;
    }    
    .nav-item {
        width: 50%;
    }
    .Synced {
        background-color: #c3f6e7;
        color: #09bd83;
        padding: 2px 30px;
    }
    .Draft {
        background-color: #f5e0aa;
        color: #dca310;
        padding: 2px 30px;
    }
    .InProgress {
        background-color: #c8e3f3;
        color: #209ee1;
        padding: 2px 18px;
    }
    .Failed {
        background-color: #f8d9dd;
        color: #f43e56;
        padding: 2px 30px;
    }
    .dataTables_info, .dataTables_length {
        padding-left: 10px;
        font-size: 12px;
        font-weight: 400;
        color: #5F6368;
    }
    .pagination {
        font-size: 12px;
    }
    #DataTables_Table_0_filter label input {
        height: 30px;
    }
    #DataTables_Table_0_filter label {
        font-size: 12px;
        font-weight: 400;
        color: #5F6368;
    }
    .wp-core-ui select {
        font-size: 12px;
        min-height: 27px;
    }
    #DataTables_Table_0_wrapper {
        padding: 2px;
    }
    input[type=date] {  
        border: 1.3px solid #b4b9be;
    }
    .dt-length, .dt-info {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .dt-search, .dt-paging {
        float: right;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .dt-paging-button.current {
        background: #00cff6;
        color: #fff;
    }
    .dt-paging-button {
        /* position: relative;
        display: block; */
        color: #0d6efd;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #dee2e6;
        font-size: 12px;
        padding: 0.375rem 0.75rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .dt-length, .dt-info {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .dt-search, .dt-paging {
        float: right;
        margin-top: 5px;
        margin-bottom: 5px;
    }
    .dt-paging-button.current {
        background: #00cff6;
        color: #fff;
    }
    .dt-paging-button {
        /* position: relative;
        display: block; */
        color: #0d6efd;
        text-decoration: none;
        background-color: #fff;
        border: 1px solid #dee2e6;
        font-size: 12px;
        padding: 0.375rem 0.75rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
</style>

<div class="container container-old conv-container conv-setting-container">
    <div class="row">
        <div class="mx-auto d-flex justify-content-end" style="max-width: 930px;">
            <div class="text-dark m-4 h6 d-flex align-items-center convexitwizard pointer" data-bs-toggle="modal" data-bs-target="#exitwizardconvmodal">
                <span class="material-symbols-outlined">
                    cancel
                </span>
                <span><?php esc_html_e("Exit Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
            </div>
        </div>
        <div class="mx-auto convcard p-0 mt-0 rounded-3 shadow-lg" style="max-width:903px">
            <ul class="nav nav-tabs border-0 p-3 pb-0" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">                    
                    <button style="pointer-events: none;" class="d-inline-flex align-items-center pawizard_tab_but border-0 nav-link active ps-0" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                        <span class="material-symbols-outlined add-channel mt-1 float-start <?php echo $is_channel_connected ? 'text-success' : 'text-warning'?>">
                            <?php echo $is_channel_connected ? 'check_circle' : 'history_toggle_off' ?>
                        </span>    
                        <h5 class="text-start m-0 ps-1 mt-1"><?php esc_html_e("Connect GMC & Google Ads Account", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                    </button>
                </li>                
                <li class="nav-item" role="presentation">                    
                    <button style="pointer-events: none;" class="d-inline-flex align-items-center pawizard_tab_but border-0 nav-link ps-0" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                        <span class="material-symbols-outlined create-feed float-start mt-1 text-warning">
                            history_toggle_off
                        </span>    
                        <h5 class="text-start m-0 ps-1 mt-1"><?php esc_html_e("Select Product Feed & Create Campaign", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                    </button>
                </li>
            </ul>            
            <div class="tab-content p-3 pt-0" id="myTabContent">
                <div class="progress">
                    <div class="progress-bar w-<?php echo esc_attr($pixelprogressbarclass) ?>" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <input type="hidden" id="pixelprogressbarclass" value="<?php echo esc_attr($pixelprogressbarclass) ?>">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">                    
                    <div class="convcard p-4 mt-0 rounded-3 shadow-sm">                        
                        <?php require_once("wizardsettings/gmcsettings.php"); // deprecated! ?>
                        <!-- Google Ads -->
                        <div id="ads_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:100%;">
                            <div class="indeterminate"></div>
                        </div>
                        <div class="gmcSetting_hr border-top mb-3"></div>
                        <img  src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gads_logo.png'); ?>">
                        <span class="span-text"><?php esc_html_e("Google Ads", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        <div class="mt-2">
                            <span class="fs-12 fw-normal text-grey"><?php esc_html_e("Choose Google Ads account from below or create a new one to launch performance max campaigns for selected products.", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </div>
                        <div class="product-feed">
                            <div class="card-body">
                                <div class="progress-wholebox">
                                    <ul class="progress-steps">
                                        <li class="<?php echo $g_mail ? '' : 'disable' ?>" style="min-height:60px;">
                                        <div class="convpixsetting-inner-box">
                                            <?php
                                            $g_email = (isset($tvc_data['g_mail']) && esc_attr($subscriptionId)) ? esc_attr($tvc_data['g_mail']) : "";
                                            ?>
                                            <?php if ($g_email != "") { ?>
                                                <span class="fs-14 fw-normal text-grey">
                                                    <?php echo (isset($tvc_data['g_mail']) && esc_html($subscriptionId)) ? esc_html($tvc_data['g_mail']) : ""; ?>
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
                                        <?php
                                            $connect_url_gagads = $TVC_Admin_Helper->get_custom_connect_url(admin_url() . 'admin.php?page=conversios&wizard=campaignManagement_gasetting');
                                            require_once ENHANCAD_PLUGIN_DIR . 'admin/partials/singlepixelsettings/googlesigninforga.php';
                                        ?>
                                        </li>
                                        <li class="<?php echo isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] !== '' ? '' : 'disable'?>">
                                            <div class="step-box">
                                                <span class="inner-text"><?php esc_html_e("Google Ads Account ID", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                <div class="row">
                                                    <div class="col-6">                                                    
                                                        <select id="google_ads_id" name="google_ads_id"
                                                            class="form-select selecttwo" style="width: 100%" disabled>
                                                            <option value="">Select Google Ads Account Id</option>
                                                            <?php if (isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] !== '') { ?>
                                                                <option value="<?php echo esc_attr($ee_options['google_ads_id']); ?>" selected><?php echo esc_html($ee_options['google_ads_id']); ?></option>
                                                            <?php } ?>                                                        
                                                        </select>
                                                    </div>
                                                    <?php if($g_email !== "") { ?>
                                                        <div class="col-4" style="padding-top: 10px;padding-left:0px">
                                                        <?php //if(isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] !== '') {?>
                                                            <span class="fs-14 text-primary pointer getGAdsList"><span class="material-symbols-outlined md-18">edit</span>Edit</span>
                                                        <?php } ?>
                                                            <span class="fs-14">&nbsp;Or &nbsp;</span>
                                                            <span class="text-primary fs-14 createNewGAds pointer">Create New</span>
                                                        </div>
                                                    <?php // } ?>
                                                </div>                                                
                                                <div>
                                                <div class="mt-2">
                                                    <input class="form-check-input check-height fs-14" type="checkbox" value="" id="ga_GMC" name="ga_GMC" <?php echo isset($ee_options['ga_GMC']) && $ee_options['ga_GMC'] == '1' ? "checked" : "" ?>>
                                                    <label class="fs-12 fw-normal text-grey" for="">Link Google merchant center with Google ads</label>
                                                    <span class="fs-12 text-danger errorGMC_GAds"></span>
                                                </div>
                                                </div>                                                
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button class="btn btn-primary fs-14 saveAds px-5 ms-3" disabled>
                                <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                Save
                            </button> 
                        </div>
                        <!-- Google Ads -->
                        <div style="display: flex; justify-content: end">
                            <button class="btn btn-channel-primary fs-14 channelTabSave" <?php echo $is_channel_connected ? '' : 'disabled' ?>>Next</button>
                        </div>
                    </div>                    
                </div>                
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div id="Feed_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:100%;">
                        <div class="indeterminate"></div>
                    </div>
                    <div class="convcard p-4 mt-0 rounded-3 shadow-sm">
                        <span class="span-text"><?php esc_html_e("Select Feed to Create Performance Max Campaign", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        <div class="mt-2">
                            <span class="fs-12 fw-normal text-grey"><?php esc_html_e("Select feed from the list below and create a performance max campaign in Google Ads in 1 single step. If you have not created a product feed yet, create it from below.", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        </div>
                        <div class="mt-2 feed_list">
                            <div class="border border-bottom-0 rounded-top">
                                <label class="p-2">Recent Feed List</label>
                                <div class="table-responsive border">
                                    <table class="table tablediv mt-1" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="fw-semibold fs-14 text-grey text-start">FEED NAME</th>
                                                <th class="fw-semibold fs-14 text-grey text-end">TOTAL PRODUCTS</th>
                                                <th class="fw-semibold fs-14 text-grey text-center">GMC STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 create_campaign d-none">
                            <span class="fs-14 text-grey"><b>Note:</b> Performance Max Campaign creation will fail if the selected feed has more than 1,000 product.</span>
                            <form id="campaign_form">
                            <span class="otherError text-danger fs-12"></span>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="Campaign_name" class="col-form-label text-dark fs-14 fw-semibold">
                                            <?php esc_html_e("Performance Max Campaign Name", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <span class="text-danger fs-16">*</span>
                                        </label>
                                        <span class="material-symbols-outlined fs-6 pointer" data-bs-toggle="tooltip" data-bs-placement="right"
                                            title="Add a name to your Campaign for your reference, for example, 'April end-of-season sales' or 'Black Friday sales for the USA'.">
                                            info
                                        </span>
                                        <input type="text" class="form-control fs-14" name="campaignName" id="campaignName" placeholder="e.g. New Summer Collection" style="width: 80%">
                                    </div>
                                    <div class="col-6">
                                    <label for="target_country" class="col-form-label text-dark fs-14 fw-semibold" name="">
                                <?php esc_html_e("Target Country", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <span class="text-danger fs-16">*</span>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Specify the target country for your Campaign. Select the country where you intend to promote and sell your products.">
                                info
                            </span>
                            <select class="select2 form-select form-select-sm mb-3" aria-label="form-select-sm example"
                                style="width: 80%" name="target_country_campaign" id="target_country_campaign">
                                <option value="">Select Country</option>
                                <?php
                                $selecetdCountry = $tvc_data['user_country'];
                                foreach ($contData as $key => $value) {
                                    ?>
                                    <option value="<?php echo esc_attr($value->code) ?>" <?php echo $selecetdCountry === $value->code ? 'selected = "selecetd"' : '' ?>><?php echo esc_html($value->name) ?></option>"
                                    <?php
                                }
                                ?>
                            </select>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="daily_budget" class="col-form-label text-dark fs-14 fw-semibold">
                                            <?php esc_html_e("Daily Budget", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            (<span class="ga_currency_symbols"></span>)
                                            <span class="text-danger fs-16">*</span>
                                        </label>
                                        <span class="material-symbols-outlined fs-6 pointer" data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Daily Budget for your Campaign">
                                            info
                                        </span>
                                        <input type="text" class="form-control fs-14" name="daily_budget" id="daily_budget" placeholder="Enter your budget" style="width: 80%">
                                    </div>
                                    <div class="col-6">
                                    <label for="target_roas" class="col-form-label text-dark fs-14 fw-semibold">
                                        <?php esc_html_e("Target ROAS (%)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </label>
                                    <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        title="Target ROAS">
                                        info
                                    </span>
                                    <input type="text" class="form-control fs-14" name="target_roas" id="target_roas"
                                    placeholder="Add Number" style="width:80%">                                  
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="start_date" class="col-form-label text-dark fs-14 fw-semibold">
                                            <?php esc_html_e("Start Date", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <span class="text-danger fs-16">*</span>
                                        </label>
                                        <span class="material-symbols-outlined fs-6 pointer" data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Campaign Start Date">
                                            info
                                        </span><span class="startDateError text-danger"></span>
                                        <input type="date" class="form-control fs-14 datepicker hasDatepicker" name="start_date" id="start_date" style="width:80%" placeholder="dd-mm-yyyy">
                                    </div>
                                    <div class="col-6">
                                        <label for="end_date" class="col-form-label text-dark fs-14 fw-semibold">
                                            <?php esc_html_e("End Date", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <span class="text-danger fs-16">*</span>
                                        </label>
                                        <span class="material-symbols-outlined fs-6 pointer" data-bs-toggle="tooltip"
                                            data-bs-placement="right"
                                            title="Campaign End Date">
                                            info
                                        </span><span class="endDateError text-danger"></span>
                                        <input type="date" class="form-control fs-14 datepicker hasDatepicker" name="end_date" id="end_date" style="width:80%" placeholder="dd-mm-yyyy">                                  
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="col-6">
                                    <label for="status" class="col-form-label text-dark fs-14 fw-semibold">
                                        <?php esc_html_e("Status", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </label>
                                    <span class="material-symbols-outlined fs-6 pointer" data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        title="Status">
                                        info
                                    </span>
                                    <div class="form-row form-row-grp campform-row" bis_skin_checked="1"> 
                                        <input type="radio" checked="checked" class="radio" value="ENABLED" name="status" id="">
                                        <label class="radio-label" for="cmp_active">Enable</label>
                                        <input type="radio" class="radio" value="PAUSED" name="status" id="">
                                        <label class="radio-label" for="cmp_inactive">Pause</label> 
                                    </div>                            
                                </div> 
                            </div>
                            </form>
                        </div>
                        <div class="tab_bottom_buttons d-flex align-items-center mt-2"> 
                            <div class="gobackwizard d-flex">
                                <span class="material-symbols-outlined text-grey">
                                    keyboard_backspace
                                </span>
                                <div class="align-self-baseline text-grey fs-14 goBackHome">
                                    <?php esc_html_e('Go Back', "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                            </div>
                            <div class="ms-auto d-flex align-items-center checkFeedListButton">
                                <button id="submitCampaign" type="button" class="btn btn-primary px-5 ms-3 fs-14" disabled>
                                    <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <?php esc_html_e('Create Campaign', "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </button>
                            </div>
                            <input type="hidden" id="selecetdCampaign" name="selecetdCampaign" value="">
                        </div>
                    </div>
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
        <img style="width:8%; height: auto" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/success_check_mark.gif'); ?>">
        <div class="overlaycontentbox"></div>
    </div>
</div>
<!-- All Modals Here --->
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
                    <span id="gadsinviteloader" class="spinner-grow spinner-grow-sm d-none" role="status" aria-hidden="true"></span>
                    <?php esc_html_e("Send Invite", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </button>

                <button id="ads-continue-close" class="btn btn-secondary m-auto text-white d-none after-ads-acc-creation" data-bs-dismiss="modal">
                    <?php esc_html_e("Ok, close", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="conv_modal_popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">            
            <div class="modal-body text-center p-2 pt-4">
                <span class="material-symbols-outlined modal_popup_logo" style="font-size: 60px;">
                    check_circle
                </span>
                <h3 class="fw-normal pt-3 conv_popup_txt"></h3>
                <span id="conv_popup_txt_msg" class="mb-1 lh-lg"></span>
            </div>
            <div class="modal-footer border-0 pb-4 mb-1">
                <button class="btn btn-primary m-auto text-white" data-bs-dismiss="modal">Ok, Done</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="conv_modal_create_popup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">            
            <div class="modal-body text-center p-2 pt-4">
                <span class="material-symbols-outlined modal_create_popup_logo text-success" style="font-size: 60px;">
                    check_circle
                </span>
                <h3 class="fw-normal pt-3 conv_create_popup_txt"> Congratulation</h3>
                <span id="conv_create_popup_txt_msg" class="mb-1 lh-lg"></span>
            </div>
            <div class="modal-footer border-0 pb-4 mb-1">
                <a href="<?php echo esc_url($dashboard_url) . '&returnFrom=campaignManagement'?>" class="btn btn-primary m-auto text-white">Take me to Dashboard</a>
            </div>
        </div>
    </div>
</div>
<!-- Exit Wizard modal -->
<div class="modal fade" id="exitwizardconvmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
            <p class="m-4 text-center h5"><?php esc_html_e("Are you sure you want to exit the setup?", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                    <?php esc_html_e("Continue Setup", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </button>
                <a href="<?php echo esc_url('admin.php?page=conversios&returnFrom=campaignManagement'); ?>" class="btn btn-primary">
                    <?php esc_html_e("Exit Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Exit wizard modal End -->
<!-- End --------------->
<script> 
    jQuery(function () {         
        checkProgressBar()   
        var allPages = '';
        jQuery('.selecttwo').select2();     
        jQuery('#target_country_campaign').select2();
        var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
        <?php if(isset($_GET['g_mail']) && isset($_GET['wizard_channel']) && $_GET['wizard_channel'] == 'gmcsetting') { ?>
            jQuery('.getGMCList').addClass('d-none')
            list_google_merchant_account(tvc_data);
            jQuery('.saveGMC').prop('disabled', false)
        <?php } ?>
        <?php if(isset($_GET['g_mail']) && isset($_GET['wizard_channel']) && $_GET['wizard_channel'] == 'gasetting') { ?>
            jQuery('.getGAdsList').addClass('d-none')
            list_google_ads_account(tvc_data);
            jQuery('.saveAds').prop('disabled', false)
        <?php } ?>        
        
        
        jQuery(document).on('click', '#home-tab', function() { 
            jQuery('.selecttwo').select2();
        })
        jQuery(document).on('click', '.getGMCList', function() {
            jQuery('.getGMCList').addClass('d-none')
            list_google_merchant_account(tvc_data);
            jQuery('.saveGMC').prop('disabled', false)
        })
        jQuery(document).on('click', '.getGAdsList', function() {
            jQuery('.getGAdsList').addClass('d-none')
            list_google_ads_account(tvc_data);
            jQuery('.saveAds').prop('disabled', false)
        })
        jQuery(document).on('click', '.goBackHome', function() {
            jQuery('[data-bs-target="#home"]').trigger('click') 
        })
        jQuery(document).on('click', '.createNewGAds', function() {
            jQuery(".before-ads-acc-creation").removeClass("d-none");
            jQuery(".after-ads-acc-creation").addClass("d-none");
            jQuery('#conv_create_gads_new').modal('show')

        })
        jQuery("#ads-continue").on('click', function(e) {
            e.preventDefault();
            create_google_ads_account(tvc_data);
        });        
        jQuery(document).on('change', '.feedId', function () {
            if(jQuery(this).prop('checked')) {
                let arr = Array();
                let thisVal = jQuery(this).val();
                let feedstr = jQuery('#selecetdCampaign').val();
                if(feedstr !== '') {
                    arr = feedstr.split(',');
                }            
                arr.push(thisVal);
                arr.join(',');
                jQuery('#selecetdCampaign').val(arr);
                jQuery('.campaignClass').removeClass('disabled');
            } else {
                let arr = Array();
                let thisVal = jQuery(this).val();
                let feedstr = jQuery('#selecetdCampaign').val();
                arr = feedstr.split(',');
                arr = jQuery.grep(arr, function(value) {
                        return value != thisVal;
                    });
                jQuery('#selecetdCampaign').val(arr);
            } 
            if(jQuery('#selecetdCampaign').val() != '') {
                jQuery('#submitCampaign').prop('disabled', false)
                jQuery('.create_campaign').removeClass('d-none')
            }else{
                jQuery('#submitCampaign').prop('disabled', true)
                jQuery('.create_campaign').addClass('d-none')
                jQuery("#campaign_form")[0].reset() 
            }     
        })
        jQuery(document).on('keyup change', '.errorInput', function() {
            jQuery(this).removeClass('errorInput')
            jQuery(this).next('span').removeClass('errorInput')
            jQuery('.endDateError').html('')
            jQuery(".startDateError").html("")
        })
        
        jQuery(document).on('click', '#submitCampaign', function () {
            
            let arrValidate = ['campaignName', 'daily_budget', 'target_country_campaign', 'start_date', 'end_date'];
            let hasError = false;
            jQuery.each(arrValidate, function(i, v) {
                if(jQuery('#'+v).val() == '' && v !== 'target_country_campaign') {
                    jQuery('#'+v).addClass('errorInput');
                    hasError = true
                }
                if(v == 'target_country_campaign' && jQuery('select[name="' + v + '"] option:selected').val() == '') {
                    jQuery('select[name="' + v + '"]').addClass('errorInput');
                    jQuery('select[name="' + v + '"]').next('span').addClass('errorInput');
                    hasError = true
                }
            })            
            var todayDate = new Date();
            var eDate = new Date(jQuery('#end_date').val());
            var sDate = new Date(jQuery('#start_date').val());
            if(new Date(sDate.toDateString()) < new Date(todayDate.toDateString())) {
                jQuery('#start_date').addClass('errorInput');
                jQuery(".startDateError").html("Start date is less than today's date.")
                return false;
            }
            if(sDate > eDate)
            {
                jQuery('#end_date').addClass('errorInput');
                jQuery('.endDateError').html('Check End Date.')
                return false;
            }
            if(hasError == true) {
                return false;
            }
            var subscriptionId = "<?php echo esc_js($subscriptionId) ?>";
            var google_merchant_center_id = "<?php echo esc_js($google_merchant_center_id) ?>";
            var google_ads_id = "<?php echo esc_js($google_ads_id) ?>";
            var store_id = "<?php echo esc_js($get_site_domain['setting']->store_id) ?>";
            if(subscriptionId == '' || google_merchant_center_id == '' || google_ads_id == '' || store_id == '') {
                let missingVal = '';
                if(subscriptionId == '')
                    missingVal = ' Subscription Id is missing';

                if(google_merchant_center_id == '')
                    missingVal = ' Google Merchant Center Id is missing';

                if(google_ads_id == '')
                    missingVal = ' Google Ads Id is missing';

                if(store_id == '')
                    missingVal = ' Store Id is missing';

                jQuery('.otherError').html( missingVal);
                return false;
            }
            
            var conv_onboarding_nonce = "<?php echo esc_js(wp_create_nonce('conv_onboarding_nonce')); ?>";
            var data = {
                action: "ee_createPmaxCampaign",
                campaign_name: jQuery('#campaignName').val(),
                budget: jQuery('#daily_budget').val(),
                target_country: jQuery('#target_country_campaign').find(":selected").val(),
                start_date: jQuery('#start_date').val(),
                end_date: jQuery('#end_date').val(),
                target_roas: jQuery('#target_roas').val() == '' ? 0 : jQuery('#target_roas').val() ,
                status: jQuery('input[name=status]:checked').val(),       
                subscription_id: "<?php echo esc_js($subscriptionId) ?>",
                google_merchant_id: "<?php echo esc_js($google_merchant_center_id) ?>",
                google_ads_id: "<?php echo esc_js($google_ads_id) ?>",
                sync_item_ids: jQuery('#selecetdCampaign').val(),
                domain: "<?php echo esc_url(get_site_url()) ?>",
                store_id: "<?php echo esc_js($get_site_domain['setting']->store_id) ?>",
                sync_type: "feed",
                conv_onboarding_nonce: conv_onboarding_nonce
            }
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: data,
                beforeSend: function () {
                    //openOverlayLoader('open');
                    jQuery("#wpbody").css("pointer-events", "none");
                    jQuery('#submitCampaign').attr('disabled', true);
                    jQuery('#submitCampaign > span').removeClass('d-none')
                },
                error: function (err, status) {
                    jQuery("#wpbody").css("pointer-events", "auto");
                    jQuery('#submitCampaign').attr('disabled', false);
                    openOverlayLoader('close');
                },
                success: function (response) {
                    jQuery("#wpbody").css("pointer-events", "auto");
                    jQuery('#submitCampaign').attr('disabled', false);
                    jQuery('#submitCampaign > span').addClass('d-none')
                    if(response.error == true) {
                        jQuery(".modal_popup_logo").html('cancel')      
                        jQuery('.modal_popup_logo').removeClass('modal_popup_logo_success')
                        jQuery('.modal_popup_logo').addClass('modal_popup_logo_error')
                        jQuery('.conv_popup_txt').text('Error')
                        jQuery('#conv_popup_txt_msg').text(response.message)
                        jQuery('#conv_modal_popup').modal('show')
                        setTimeout(function() {
                            openOverlayLoader('close');
                            jQuery('#conv_modal_popup').modal('show')
                        }, 2000);
                    }else {
                        jQuery('#conv_create_popup_txt_msg').html("Exciting things are happening behind the scenes! We're crafting your Pmax campaign for Google Ads with precision. Your products are gearing up to shine. Sit tight, and get ready for an amplified reach and increased sales.")                        
                        
                        setTimeout(function() {
                            openOverlayLoader('close');
                            jQuery('#conv_modal_create_popup').modal('show')
                        }, 2000);
                    }
                    openOverlayLoader('openshow');
                }
            });
        });
        /*************************document event start *********************************************************************/
        
        /*****************Create Feed Start **************************************************************************/
        jQuery(document).on('click', '#contact-tab', function() {            
            if(jQuery('.selecttwo').data('select2')) {
                jQuery('.selecttwo').select2('destroy');
            }
            jQuery('.createSelect').select2(); 
            if(jQuery('input#all_product').is(':checked'))  {
                jQuery('#all_product').trigger('change');
            }      
        })
        
        jQuery(document).on('keydown', 'input[name="daily_budget"], input[name="target_roas"]', function () {
            if (event.shiftKey == true) {
                event.preventDefault();
            }
            if ((event.keyCode >= 48 && event.keyCode <= 57) || 
                (event.keyCode >= 96 && event.keyCode <= 105) || 
                event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || 
                event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

            } else {
                event.preventDefault();
            }

            if (jQuery(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();
        })
        jQuery(document).on('click', '.saveAds', function() {
            saveChannel('gAds');
        })
        jQuery(document).on('click', '#ga_GMC', function (e) {
            jQuery('#ga_GMC').css('border', '');
        });
        jQuery(document).on('change', '#ga_GMC', function() {
            jQuery('.saveAds').prop('disabled', false)
        })
        /******************Create Feed End ***************************************************************************/
        /************************* document event end ***************************************************************/
    });  
    function create_google_ads_account(tvc_data) {
        var conversios_onboarding_nonce = "<?php echo esc_js(wp_create_nonce('conversios_onboarding_nonce')); ?>";
        var error_msg = 'null';
        var btn_cam = 'create_new';
        var ename = 'conversios_onboarding';
        var event_label = 'ads';   
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

                    jQuery("#new_google_ads_id").text(response.data.adwords_id);
                    if (response.data.invitationLink != "") {
                        jQuery("#ads_invitationLink").attr("href", response.data.invitationLink);
                    } else {
                        jQuery("#invitationLink").html("");
                    }
                    jQuery(".before-ads-acc-creation").addClass("d-none");
                    jQuery(".after-ads-acc-creation").removeClass("d-none");
                    var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
                    list_google_ads_account(tvc_data, response.data.adwords_id);

                } else {
                    var error_msg = response.errors;
                    add_message("error", response.data.message);
                } 
            }
        });
    }
    function getFeedList() {
        var data = {
            action: "get_category_for_filter",
            type: "getFeedList",
            get_category_for_filter: "<?php echo esc_js(wp_create_nonce('get_category_for_filter-nonce')); ?>"
        };
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () { 
                managecreateFeedLoader('show')
            },
            success: function (response) {
                if(response.data.length > 0) {
                    var tableData = "";
                    response.data.forEach(value => {
                        var disabled = '';
                        if(value.status !== 'Synced') {
                            disabled = 'disabled';
                        }
                        tableData += '<tr>';
                        tableData += '<td class="text-grey fs-12 fw-normal text-start"><input class="form-check-input check-height fs-14 feedId" type="checkbox" value="'+value.id+'" id="" name="feedId" '+disabled+'>  &nbsp;' + value.feed_name + '</td>';                        
                        tableData += '<td class="text-grey fs-12 fw-normal text-end">' + value.total_product + '</td>';
                        tableData += '<td class="text-grey fs-12 fw-normal text-center"><div class="badgebox '+value.status.replace(/\s/g,"")+'" >' + value.status + '</div></td>';
                        tableData += '</tr>';
                    });
                    
                    jQuery('.tablediv').DataTable().clear().draw();
                    jQuery('.tablediv').DataTable().destroy();
                    jQuery('.tablediv').find('tbody').append(tableData);
                    jQuery('.tablediv').DataTable({
                        columnDefs: [
                            { orderable: true, targets: 0 },
                            { orderable: false, targets: '_all' },
                        ],
                    }).draw();
                    jQuery('colgroup').empty();
                    jQuery('.ga_currency_symbols').html(response.currency_symbol)
                } else {
                    jQuery('.checkFeedListButton').empty();
                    jQuery('.checkFeedListButton').html('<a href="'+response.href+'" class="btn btn-primary px-5 ms-3 fs-14">Create Feed</a>');
                    var button;
                }
                managecreateFeedLoader('hide')
            }
        });
    }  
    
    function saveChannel(Channel) {
        var selected_vals = {};
        var conv_options_type = [];
        var data = {};
        if(Channel == 'GMC') {
            jQuery('.errorGMC_GAds').text('')
            jQuery('#ga_GMC').prop("checked", true)
            if (jQuery("#google_merchant_center_id").val() === '') {
                jQuery('.selection').find("[aria-labelledby='select2-google_merchant_center_id-container']").addClass('selectError');
                return false;
            }
            var update_site_domain = '';
            if(google_merchant_center_id != jQuery("#google_merchant_center_id").val()) {
                update_site_domain = 'update';
            }
            conv_options_type = ["eeoptions", "eeapidata", "middleware"];
            selected_vals["subscription_id"] = "<?php echo esc_js($subscriptionId) ?>";
            selected_vals["google_merchant_center_id"] = jQuery("#google_merchant_center_id").val();
            selected_vals["google_merchant_id"] = jQuery("#google_merchant_center_id").val();
            selected_vals["merchant_id"] = jQuery('#google_merchant_center_id').find(':selected').data('merchant_id');
            selected_vals["website_url"] = "<?php echo esc_url(get_site_url()); ?>";
            var google_ads_id = jQuery('#google_ads_id').val();
            if(google_ads_id !== ''){
                selected_vals["google_ads_id"] = google_ads_id;
                selected_vals["ga_GMC"] = '1';
            }
            data = {
                action: "conv_save_pixel_data", 
                pix_sav_nonce: "<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')) ?>",
                conv_options_data: selected_vals,
                conv_options_type: conv_options_type,
                update_site_domain: update_site_domain,
            }
        }  
        if(Channel == 'gAds') {
            var google_merchant_center_id = jQuery("#google_merchant_center_id").val();
            var google_ads_id = jQuery("#google_ads_id").val()
            selected_vals["ga_GMC"] = '0';
            if ( google_ads_id === '' ) {
                jQuery('.selection').find("[aria-labelledby='select2-google_ads_id-container']").addClass('selectError');
                return false;
            }
            if (!jQuery('#ga_GMC').is(":checked")) {
                jQuery('#ga_GMC').css('border', '1px solid red');
                return false;
            }
            if( google_merchant_center_id == "" ) {
                if(jQuery('#ga_GMC').is(":checked")) {
                    jQuery('.errorGMC_GAds').text('Google merchant account is required to link Google Ads')
                    jQuery('#ga_GMC').prop("checked", false)
                    return false;
                }
            }
            if( google_merchant_center_id !== "" ) {
                selected_vals["subscription_id"] = "<?php echo esc_js($subscriptionId) ?>";
                selected_vals["google_merchant_center_id"] = google_merchant_center_id;
                selected_vals["google_merchant_id"] = google_merchant_center_id;
                selected_vals["merchant_id"] = jQuery('#google_merchant_center_id').find(':selected').data('merchant_id');
                selected_vals["website_url"] = "<?php echo esc_url(get_site_url()); ?>";
                if(jQuery('#ga_GMC').is(":checked")) {
                    selected_vals["ga_GMC"] = '1';
                }
            }
            selected_vals["google_ads_id"] = google_ads_id;
            conv_options_type = ["eeoptions", "eeapidata"];
            data = {
                action: "conv_save_pixel_data", 
                pix_sav_nonce: "<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')) ?>",
                conv_options_data: selected_vals,
                conv_options_type: conv_options_type,
            }
        }      
        
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () {
                //openOverlayLoader('open'); 
                if(Channel == 'GMC'){
                    jQuery(".verifySite, .verifyDomain, .createNewGMC, .channelTabSave, .saveGMC, .tvc_google_signinbtn").css("pointer-events", "none");
                    jQuery('.saveGMC').prop('disabled', true)
                    jQuery('.saveGMC > span').removeClass('d-none')
                    jQuery('.gmc_account_id_step').removeClass('disable')
                }                
                if(Channel == 'gAds'){
                    jQuery("#saveAds, .createNewGAds, .getGAdsList").css("pointer-events", "none");
                    jQuery('.saveAds > span').removeClass('d-none')
                    jQuery('.saveAds').prop('disabled', true)
                } 
            },
            success: function (response) {
                if(Channel == 'GMC'){
                    jQuery(".verifySite, .verifyDomain, .createNewGMC, .channelTabSave, .saveGMC, .tvc_google_signinbtn").css("pointer-events", "auto");
                    jQuery('.saveGMC > span').addClass('d-none')
                    jQuery('#google_merchant_center_id').attr('disabled', true)              
                    jQuery('.getGMCList').removeClass('d-none')
                    if(google_merchant_center_id != jQuery("#google_merchant_center_id").val()) {
                        var html ='<span class="material-symbols-outlined" style="font-size: 18px;">autorenew</span>Verify Now';
                        jQuery('.verifySite').removeClass('bg-success_')
                        jQuery('.verifySite').addClass('bg-warnings')
                        jQuery('.verifySite').html(html)
                        jQuery('.verifyDomain').removeClass('bg-success_')                    
                        jQuery('.verifyDomain').addClass('bg-warnings')
                        jQuery('.verifyDomain').html(html)
                    }
                }   
                if(Channel == 'gAds'){
                    jQuery("#saveAds, .createNewGAds, .getGAdsList").css("pointer-events", "auto");
                    jQuery('.saveAds > span').addClass('d-none')
                    jQuery('.saveAds').prop('disabled', false)
                }
                checkProgressBar('channel');
                openOverlayLoader('openshow');
                setTimeout(function() {
                    openOverlayLoader('close');
                }, 2000);
            }
        });

    }
    jQuery(document).on('click', '.channelTabSave', function() {
        getFeedList();
        jQuery('[data-bs-target="#contact"]').trigger('click') 
    })
    function list_google_ads_account(tvc_data, new_ads_id = "") {    
        manageGaAdsLoader('show')    
        var selectedValue = jQuery("#google_ads_id").val();
        var conversios_onboarding_nonce = "<?php echo esc_js(wp_create_nonce('conversios_onboarding_nonce')) ?>";
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
                manageGaAdsLoader('hide') 
                if (response.error === false) {
                    var error_msg = 'null';
                    if (response.data.length == 0) {
                        add_message("warning", "There are no Google ads accounts associated with email.");
                    } else {
                        if (response.data.length > 0) {
                            jQuery('#google_ads_id').empty();
                            var AccOptions = '';
                            var selected = '';
                            if (new_ads_id != "" && new_ads_id != undefined) {
                                AccOptions = AccOptions + '<option value="' + new_ads_id + '" selected>' + new_ads_id + '</option>';
                            }
                            response?.data.forEach(function(item) {
                                var selected = item == selectedValue && new_ads_id == "" ? 'selected' : '';
                                AccOptions = AccOptions + '<option value="' + item + '" '+selected+'>' + item + '</option>';
                            });
                            jQuery('#google_ads_id').append(AccOptions);
                            jQuery('#google_ads_id').removeAttr('disabled');
                        }
                    }
                } else {
                    var error_msg = response.errors;
                }
            }
        });
    }
    function manageGaAdsLoader(display = "show") {
        if(display == "show") {
            jQuery('#ads_loader').removeClass('d-none')
            jQuery("#saveAds").css("pointer-events", "none");
        } else {
            jQuery('#ads_loader').addClass('d-none')
            jQuery("#saveAds").css("pointer-events", "auto");
        }
    }
    function managecreateFeedLoader(display = "show") {
        if(display == "show") {
            jQuery('#Feed_loader').removeClass('d-none')
            jQuery("#submitCampaign").css("pointer-events", "none");
        } else {
            jQuery('#Feed_loader').addClass('d-none')
            jQuery("#submitCampaign").css("pointer-events", "auto");
        }
    }
    function checkProgressBar(channel = "") {
        var get = "<?php echo isset($_GET['wizard_channel']) ? esc_js(sanitize_text_field($_GET['wizard_channel'])) : '' ?>";
        var pixelprogressbarclass = 0;
        var is_channel_connected = false;
        var data = {
            action: "get_category_for_filter",
            type: "getProgressCount_campaign",
            get_category_for_filter: "<?php echo esc_js(wp_create_nonce('get_category_for_filter-nonce')); ?>"
        };
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () { 
            },
            success: function (response) { 
                if(response.is_channel_connected == true) {
                    jQuery('.channelTabSave').prop('disabled', false)
                    pixelprogressbarclass = pixelprogressbarclass + 33;
                }
                if(response.feed_count > '0') {
                    jQuery('.create-feed').text("check_circle")
                    jQuery('.create-feed').removeClass("text-warning")
                    jQuery('.create-feed').addClass("text-success")
                    pixelprogressbarclass = pixelprogressbarclass + 33;
                    
                }
                jQuery('.progress-bar').addClass('w-'+pixelprogressbarclass)
                if(response.is_channel_connected == true && get == '' && channel == '') {
                    getFeedList();
                    jQuery('[data-bs-target="#contact"]').trigger('click')
                }
            }
        });   
        
    }        
    function openOverlayLoader(status = "") {
        if (status == 'open') {
            document.getElementById("overlayanimation").style.width = "100%";
            jQuery(".loaderopen-content").removeClass("d-none");
            jQuery(".loadershow-content").addClass("d-none");
        } else if (status == 'openshow') {
            document.getElementById("overlayanimation").style.width = "100%";
            jQuery(".loaderopen-content").addClass("d-none");
            jQuery(".loadershow-content").removeClass("d-none");
        } else {
            document.getElementById("overlayanimation").style.width = "0%";
            jQuery(".overlay_loader_conv-content").addClass("d-none");
            jQuery(".loaderopen-content").addClass("d-none");
            jQuery(".loadershow-content").addClass("d-none");
            jQuery(".loadershow-content .overlaycontentbox").html("");
        }
    }
</script>