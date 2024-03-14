<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$TVC_Admin_Helper = new TVC_Admin_Helper();
$customApiObj = new CustomApi();
if(isset($_GET['g_mail']) && isset($_GET['wizard_channel']) && $_GET['wizard_channel'] == 'gmcsetting') {
    update_option('ee_customer_gmail', sanitize_email($_GET['g_mail']));
    $TVC_Admin_Helper->update_subscription_details_api_to_db();
}
$ee_options = unserialize(get_option("ee_options"));
$ee_mapped_attrs = unserialize(get_option('ee_prod_mapped_attrs'));
$tempAddAttr = $ee_mapped_attrs;

if (!class_exists('TVCProductSyncHelper')) {
    include(ENHANCAD_PLUGIN_DIR . 'includes/setup/class-tvc-product-sync-helper.php');
}
$TVCProductSyncHelper = new TVCProductSyncHelper();
$wooCommerceAttributes = array_map("unserialize", array_unique(array_map("serialize", $TVCProductSyncHelper->wooCommerceAttributes())));

$tvc_data = $TVC_Admin_Helper->get_store_data();
$g_mail = get_option('ee_customer_gmail');
if(isset($_GET['g_mail']) && isset($_GET['wizard_channel']) && $_GET['wizard_channel'] == 'gmcsetting') {
    $g_mail = sanitize_email($_GET['g_mail']);
}
$tvc_data['g_mail'] = "";
if ($g_mail) {
    $tvc_data['g_mail'] = sanitize_email($g_mail);
}
$subscriptionId = $ee_options['subscription_id'];
$app_id = 1;
$pixelprogressbarclass = 0;
$google_merchant_center_id  = '';
$merchan_id = '';
$is_channel_connected = false;
if(isset($ee_options['google_merchant_center_id']) && $ee_options['google_merchant_center_id'] !== ''){
    $google_merchant_center_id  = $ee_options['google_merchant_center_id'];
    $merchan_id = isset($ee_options['merchant_id']) ? $ee_options['merchant_id'] : '';
}

$is_tiktok_connected = false;
$tiktok_mail = isset($ee_options['tiktok_setting']['tiktok_mail']) === TRUE ? $ee_options['tiktok_setting']['tiktok_mail'] : '';
$tiktok_user_id = isset($ee_options['tiktok_setting']['tiktok_user_id']) === TRUE ? $ee_options['tiktok_setting']['tiktok_user_id'] : '';
$tiktok_business_id = isset($ee_options['tiktok_setting']['tiktok_business_id']) === TRUE ? $ee_options['tiktok_setting']['tiktok_business_id'] : '';
$tiktok_business_name = isset($ee_options['tiktok_setting']['tiktok_business_name']) === TRUE ? $ee_options['tiktok_setting']['tiktok_business_name'] : '';
// $fb_business_id = isset($ee_options['facebook_setting']['fb_business_id']) === TRUE ? $ee_options['facebook_setting']['fb_business_id'] : '';
if (isset($_GET['tiktok_mail']) == TRUE) {
    $tiktok_mail = sanitize_email($_GET['tiktok_mail']);
    $is_tiktok_connected = true;
}
if (isset($_GET['tiktok_user_id']) == TRUE) {
    $tiktok_user_id = sanitize_text_field($_GET['tiktok_user_id']);
}
$connect_gmc_url = $TVC_Admin_Helper->get_custom_connect_url(admin_url() . 'admin.php?page=conversios&wizard=productFeedOdd_gmcsetting');
$getCountris = @file_get_contents(ENHANCAD_PLUGIN_DIR . "includes/setup/json/countries.json");
$contData = json_decode($getCountris);
$site_url = "admin.php?page=conversios-google-shopping-feed&tab=";
$dashboard_url = "admin.php?page=conversios";
$is_refresh_token_expire = false;
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
        width: 33.3%!important;
    }
    .w-66 {
        width: 66.6%!important;
    }
    .w-99 {
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
    .attributeMappingDiv {
        height: 48px;
        border-radius: 8px 8px 0px 0px;
        padding: 0px 24px 0px 24px;
    }
    .attrDiv {
        border-radius: 8px;
        border: 1px solid #ccc;
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
    #autoSync:checked {
        border-radius: 2em !important;
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
                        <h5 class="text-start m-0 ps-1 mt-1"><?php esc_html_e("Channel Configuration", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                    </button>
                </li> 
                <li class="nav-item" role="presentation">                    
                    <button style="pointer-events: none;" class="d-inline-flex align-items-center pawizard_tab_but border-0 nav-link ps-0" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">
                        <span class="material-symbols-outlined product-attribute float-start mt-1 text-warning">
                        history_toggle_off
                        </span>    
                        <h5 class="text-start m-0 ps-1 mt-1"><?php esc_html_e("Product Attribute Mapping", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                    </button>
                </li>
                <li class="nav-item" role="presentation">                    
                    <button style="pointer-events: none;" class="d-inline-flex align-items-center pawizard_tab_but border-0 nav-link ps-0" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">
                        <span class="material-symbols-outlined create-feed float-start mt-1 text-warning">
                            history_toggle_off
                        </span>    
                        <h5 class="text-start m-0 ps-1 mt-1"><?php esc_html_e("Create Product Feed", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
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
                        <?php require_once("wizardsettings/gmcsettings.php"); ?>
                        
                        <div id="tiktokSetting_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:100%;">
                            <div class="indeterminate"></div>
                        </div>
                        <div class="tiktokSetting_hr border-top mb-3"></div>
                        <?php
                        require_once ENHANCAD_PLUGIN_DIR . 'admin/class-tvc-admin-helper.php';
                        $tvcAdminHelper = new TVC_Admin_DB_Helper();
                        $catalogData = $tvcAdminHelper->tvc_get_results('ee_tiktok_catalog');
                        $catalogCountry = array();
                        $catalog_business_id = array();
                        if (is_array($catalogData) && !empty($catalogData)) {
                            foreach ($catalogData as $key => $value) {
                                $catalogCountry[$key] = $value->country;
                                $catalog_business_id[$key] = $value->catalog_id;
                            }
                        }
                        //$connect_url = $TVC_Admin_Helper->get_custom_connect_url_subpage(admin_url() . 'admin.php?page=conversios-google-shopping-feed', "tiktokBusinessSettings");
                        /**************Tiktok Auth start ********************************************************/    
                        $confirm_url = "admin.php?page=conversios&wizard=productFeedOdd_tiktoksetting";
                        $state = ['confirm_url' => admin_url() . $confirm_url, 'subscription_id' => $subscriptionId];
                        $tiktok_auth_url = "https://ads.tiktok.com/marketing_api/auth?app_id=7233778425326993409&redirect_uri=https://connect.tatvic.com/laravelapi/public/auth/tiktok/callback&rid=q6uerfg9osn&state=" . urlencode(wp_json_encode($state)); 
                        ?>
                        <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_tiktok_logo.png'); ?>">
                        <span class="span-text"><?php esc_html_e("TikTok Business Account", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        <div class="mt-2">
                            <span class="fs-12 fw-normal text-grey">
                                <?php esc_html_e("Product feed to TikTok catalog help you to run ads on tiktok for your product and reach out to more than 900 Million people.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                        </div>
                        <div class="product-feed">
                            <div class="card-body">
                                <div class="progress-wholebox">
                                    <ul class="progress-steps">
                                        <li class="<?php echo ($tiktok_mail && $tiktok_user_id) ? "" : "disable"; ?>" style="min-height:68px;">
                                            <div class="step-box">
                                                <div style="padding-top: 8px">
                                                    <?php if ($tiktok_mail === '' && $tiktok_user_id === '') { ?>    
                                                        <a onclick='window.open("<?php echo esc_url($tiktok_auth_url) ?>","MyWindow","width=800,height=700,left=300, top=150"); return false;'
                                                            href="#" class="signIn">
                                                            <button class="btn btn-outline-dark" id=""><img style="width:19px"
                                                                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_tiktok_logo.png'); ?>">
                                                                &nbsp;Continue with TikTok</button>
                                                        </a>
                                                        <p class="mt-2" style="font-size: 10px;">Please login to the Email account linked to your TikTok Business account so that we can get you business accounts and catalogs and use Chrome for best experience.</p>
                                                        
                                                    <?php } else { ?>
                                                        <span class="fs-14 fw-normal text-grey mb-1">
                                                        <?php echo (esc_html($tiktok_mail) . ', <b>User Id: </b>' . esc_html($tiktok_user_id) . ' '); ?>
                                                        </span>                                                        
                                                        <a onclick='window.open("<?php echo esc_url($tiktok_auth_url) ?>","MyWindow","width=800,height=700,left=300, top=150"); return false;'
                                                            href="#" class="signIn">Change</a>
                                                    <?php } ?>   
                                                    <input type="hidden" id="tiktok_mail" value=<?php echo esc_attr($tiktok_mail) ?>>
                                                    <input type="hidden" id="tiktok_user_id" value=<?php echo esc_attr($tiktok_user_id) ?>> 
                                                </div>    
                                            </div>                                    
                                        </li>
                                        <li class="tiktok-step <?php echo ($tiktok_business_id) ? "" : "disable"; ?>" style="min-height:120px;">
                                            <div class="step-box">
                                                <span class="inner-text"><?php esc_html_e("TikTok Business Account ID", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <select id="tiktok_business_id" name="tiktok_business_id"
                                                            class="form-select selectthree" disabled style="width: 100%">
                                                            <option value="">Select Tiktok Business Account Id</option>                                              
                                                        </select>
                                                    </div>
                                                    <div class="col-4" style="padding-top: 10px;padding-left:0px">
                                                        <?php if($tiktok_user_id) {?>
                                                            <span class="fs-14 text-primary pointer gettitokList"><span class="material-symbols-outlined md-18">edit</span>Edit</span>
                                                        <?php } ?>                                                    
                                                    </div>
                                                    <span class="fs-12 fw-normal text-grey">
                                                        <?php esc_html_e("Choose your Tiktok Business account from the dropdown.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="tiktok-catalog-step <?php echo (is_array($catalogData) && !empty($catalogData)) ? "" : "disable"; ?>" style="min-height:68px;">
                                            <div class="step-box">
                                                <span class="inner-text">
                                                    <?php esc_html_e(" Map Catalog For Target Country", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </span>
                                                <div class="row pt-2">
                                                    <div class="col-12">                                                    
                                                        <table class="table table-bordered" id="map_catalog_table" style="width:100%">
                                                            <thead>
                                                                <tr class="">
                                                                    <th scope="col" class="text-start ">
                                                                        <?php esc_html_e("Target Country", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </th>
                                                                    <th scope="col" class="text-start">
                                                                        <?php esc_html_e("Catalog Id", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="table-body">

                                                            </tbody>
                                                        </table>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div> 
                                <button class="btn btn-primary fs-14 saveTiktok px-5 ms-3" disabled>
                                    <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    Save
                                </button>                               
                            </div>
                        </div>
                        <div style="display: flex; justify-content: end">                            
                            <button class="btn btn-channel-primary fs-14 channelTabSave" disabled>Next</button>
                        </div>
                        
                    </div>                    
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div id="attribute_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:100%;">
                        <div class="indeterminate"></div>
                    </div>
                    <div class="convcard p-4 mt-0 rounded-3 shadow-sm">
                        <span class="span-text"><?php esc_html_e("Attribute Mapping for Products", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                        <div class="mt-2">
                            <p class="fs-12 fw-normal text-grey"><?php esc_html_e("Correctly mapping your products to the appropriate attributes enhances their relevance to customers, potentially leading to improved conversion rates and boosted sales.", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                            <p class="fs-12 fw-normal text-grey"><?php esc_html_e("Map your Woocommerce product attributes against Conversios product attributes and we will make sure they are rightly synced in google merchant center and/or tiktok.", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                        </div>
                        <div class="attrDiv">                        
                            <div class="conv-light-grey-bg mb-2 attributeMappingDiv" >
                                <div class="row">
                                    <div class="col-6 pt-2">
                                        <span class="ps-2 fs-14 fw-normal text-grey">
                                            <img
                                                src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conversios_logo.png'); ?>" />
                                            <?php esc_html_e("Conversios Product Attribute", "enhanced-e-commerce-for-woocommerce-store") ?></span>
                                    </div>
                                    <div class="col-6 pt-2 ps-0">
                                        <span class="ps-0 fs-14 fw-normal text-grey">
                                            <img
                                                src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/woocommerce_logo.png'); ?>" />
                                            <?php esc_html_e("WooCommerce Product Attribute", "enhanced-e-commerce-for-woocommerce-store") ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 row bg-white m-0 p-0 mb-3">
                                <div class="col-12  attributeDiv" style="overflow-y: scroll;overflow-x: hidden; max-height:600px; position: relative;">
                                    <form id="attribute_mapping" class="row">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab_bottom_buttons d-flex align-items-center mt-1"> 
                            <div class="gobackwizard d-flex">
                                <span class="material-symbols-outlined text-grey">
                                    keyboard_backspace
                                </span>
                                <div class="align-self-baseline text-grey fs-14 goBackHome">
                                    <?php esc_html_e('Go Back', "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                            </div>
                            <div class="ms-auto d-flex align-items-center"> 
                                <div class="align-self-baseline text-grey fs-14 goToFeed ms-3 mt-1" style="border-bottom: 1px dashed; cursor: pointer">
                                    <?php esc_html_e('Skip To Next', "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>                               
                                <button id="attr_mapping_save" type="button" class="attr_mapping_save btn btn-primary px-5 ms-3 fs-14">
                                    <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <?php esc_html_e('Save & Next', "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                    <div id="createFeed_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:100%;">
                        <div class="indeterminate"></div>
                    </div>
                    <div class="convcard p-4 mt-0 rounded-3 shadow-sm">
                        <span class="span-text"><?php esc_html_e("Create Product Feed", "enhanced-e-commerce-for-woocommerce-store"); ?></span>                       
                        <div class="mt-3 col-6">
                            <span for="feed_name" class="inner-text">
                                <?php esc_html_e("Feed Name", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                            <span class="material-symbols-outlined fs-6 text-grey pointer " data-bs-toggle="tooltip" data-bs-placement="right"
                                title="Add a name to your feed for your reference, for example, 'April end-of-season sales' or 'Black Friday sales for the USA'.">
                                info
                            </span>
                            <input type="text" class="form-control fs-14" name="feedName" id="feedName"
                                placeholder="e.g. New Summer Collection">
                        </div>
                        <div class="mt-3 col-6">
                            <span for="" class="inner-text">
                                <?php esc_html_e("Target Country", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                            <span class="material-symbols-outlined fs-6 text-grey pointer" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="Specify the target country for your product feed. Select the country where you intend to promote and sell your products.">
                                info
                            </span>
                            <select class="form-select form-select-sm mb-3" aria-label="form-select-sm example"
                                style="width: 100%" name="feed_target_country" id="feed_target_country">
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
                        <div class="mt-3 col-12">
                            <span for="" class="inner-text">
                                <?php esc_html_e("Select Channels", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </span>
                            <span class="material-symbols-outlined fs-6 text-grey pointer" data-bs-toggle="tooltip" data-bs-placement="right"
                                title="Below is the list of channels that you have linked for product feed. Please note you will not be able to make any changes in the selected channels once product feed process is done.">
                                info
                            </span>
                            <div class="">
                                <div class="form-check form-check-custom">
                                    <input class="form-check-input check-height fs-14 errorChannel" type="checkbox"
                                        value="" id="gmc_id"
                                        name="gmc_id" >
                                    <span for="" class="fs-14 pt-0 text-grey fw-normal">
                                        <?php esc_html_e("Google Merchant Center Account :", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </span>
                                    <label class="fs-14 pt-0 fw-normal text-grey google_merchant_center_id">
                                        
                                    </label>
                                </div>
                            </div>
                            <div class="">
                                <div class="form-check form-check-custom">
                                    <input class="form-check-input check-height fs-14 errorChannel" type="checkbox"
                                        value="" id="tiktok_id"
                                        name="tiktok_id" >
                                    <span for="" class="fs-14 pt-0 text-grey fw-normal">
                                        <?php esc_html_e("TikTok Catalog Id :", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </span>
                                    <label class="fs-14 pt-0 fw-normal text-grey tiktok_catalog_id">
                                        
                                    </label>
                                </div>
                            </div>
                            <!-- <div class="">
                                <div class="form-check form-check-custom">
                                    <input class="form-check-input check-height fs-14 errorChannel" type="checkbox"
                                        value="" id="fb_id"
                                        name="fb_id" >
                                    <span for="" class="fs-14 pt-0 text-grey fw-normal">
                                        <?php //esc_html_e("Facebook Catalog Id :", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </span>
                                    <label class="fs-14 pt-0 fw-normal text-grey fb_id">
                                        
                                    </label>
                                </div>
                            </div>                             -->
                        </div>  
                        <div class="row mt-3">
                            <div class="col-4 row">
                                <div class="col-6 pe-0">
                                    <span for="auto_sync" class="inner-text pe-0">Auto Sync</span>
                                    <span class="material-symbols-outlined fs-6 text-grey pointer" data-bs-toggle="tooltip"
                                        data-bs-placement="right"
                                        title="Turn on this feature to schedule an automated product feed to keep your products up to date with the changes made in the products. You can come and change this any time.">
                                        info
                                    </span>
                                </div>
                                <div class="col-4 ps-0" style="cursor:none">
                                    <div class="form-check form-switch fs-5">
                                        <input class="form-check-input" type="checkbox" name="autoSync" id="autoSync" checked>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <span class="inner-text pe-2">Auto Sync Interval</span>
                                <input type="text" class="form-control-sm" readonly name="autoSyncIntvl" id="autoSyncIntvl" size="3" min="1" value="25" style="width: 42px"
                                    onkeypress="return ( event.charCode === 8 || event.charCode === 0 || event.charCode === 13 || event.charCode === 96) ? null : event.charCode >= 48 && event.charCode <= 57">
                                <span class="fs-14 fw-normal text-grey ps-2">Days</span>
                                <span class="conv-link-blue ms-2 fw-bold-500 upgradetopro_badge fs-12" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
                                    <img style="width:14px; max-width:100%; height:auto" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                    <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span for="auto_sync" class="inner-text">Product Selection</span>
                            <span class="material-symbols-outlined fs-6 text-grey pointer" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Select your woocommerce product to sync in channels.">
                                info
                            </span>
                            <div class="mt-1">
                                <input type="radio" checked name="product_selection" id="all_product" value="all_product">
                                <label class="form-check-label ps-2 fs-14 fw-normal text-grey" for="all_product">
                                    <?php esc_html_e("Select All Products", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </label>
                                <div class="totalCountDiv"><span class="text-grey fs-12"><span class="totalCount"></span> products are ready to sync</span></div>
                            </div>
                            <div class="mt-2">
                                <input type="radio" name="product_selection" id="filter_product" value="filter_product">
                                <label class="form-check-label ps-2 fs-14 fw-normal text-grey" for="filter_product">
                                    <?php esc_html_e("Filter Products", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </label>                                
                            </div>
                            <div class="col-12 mt-2 d-none AllFilters">
                                <div class="row">
                                    <div class="col-3 productDiv">
                                        <select class="product createSelect" name="product" style="width: 100%">
                                            <option value="0"><?php esc_html_e("Select Attribute", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="product_cat"><?php esc_html_e("Category", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="ID"><?php esc_html_e("Product Id", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="post_title"><?php esc_html_e("Product Title", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="_sku"><?php esc_html_e("SKU", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="_regular_price"><?php esc_html_e("Regular Price", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="_sale_price"><?php esc_html_e("Sale Price", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="post_content"><?php esc_html_e("Product Description", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="post_excerpt"><?php esc_html_e("Product Short Description", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                            <option value="_stock_status"><?php esc_html_e("Stock Status", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-3 conditionDiv" >
                                        <select class="condition createSelect" name="condition" style="width: 100%">
                                            <option value="0"><?php esc_html_e("Select Conditions", "enhanced-e-commerce-for-woocommerce-store"); ?></option>
                                        </select>
                                    </div>
                                    <div class="col-3 textValue">
                                        <input type="text" class="form-control from-control-overload value" placeholder="Add value" name="value">
                                    </div>
                                    <div class="col-3 mt-1">
                                        <span class="text-primary fs-14 applyFilter pointer">Apply Filter</span>
                                    </div>
                                </div>
                                <div class="filterCard">

                                </div>
                                <div class="totalCountDivFilter d-none"><span class="text-grey fs-12"><span class="totalCountFilter"></span> products are ready to sync</span></div>
                                <input type="hidden" id="productVal" value="">
                                <input type="hidden" id="conditionVal" value="">
                                <input type="hidden" id="valueVal" value="">
                            </div>

                            <div class="mt-2">
                                <input type="radio" name="product_selection" id="specific_product" value="specific_product">
                                <label class="form-check-label ps-2 fs-14 fw-normal text-grey" for="">
                                    <?php esc_html_e("Select Specific Products", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </label>
                            </div>
                        </div>
                        <div class="tab_bottom_buttons d-flex align-items-center border-top mt-1"> 
                            <div class="gobackwizard d-flex mt-2">
                                <span class="material-symbols-outlined text-grey">
                                    keyboard_backspace
                                </span>
                                <div class="align-self-baseline text-grey fs-14 goBackAttr">
                                    <?php esc_html_e('Go Back', "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                            </div>
                            <div class="ms-auto d-flex align-items-center mt-2">                                
                                <button id="createFeed" type="button" class="btn btn-primary px-5 ms-3 fs-14">
                                    <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <?php esc_html_e('Create', "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </button>
                            </div>
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
                <a href="<?php echo esc_url($dashboard_url) . '&returnFrom=productFeed'?>" class="btn btn-primary m-auto text-white">Take me to Dashboard</a>
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
                <a href="<?php echo esc_url('admin.php?page=conversios&returnFrom=productFeed'); ?>" class="btn btn-primary">
                    <?php esc_html_e("Exit Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </a>
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
<!-- End --------------->
<script>   
    var selected = Array();
    var cnt = '';
    jQuery(function () {         
        checkProgressBar()   
        jQuery('.selecttwo').select2();
        jQuery('.selectthree').select2();
        jQuery('.selectfour').select2();
        jQuery('.selectfive').select2();        
        
        var tempArr = <?php echo wp_json_encode($tempAddAttr) ?> 
        var arr = Object.keys(tempArr).map(function (key) { return key; });                                           
        selected = arr;
        var tiktok_user_id = "<?php echo esc_attr($tiktok_user_id) ?>";
        var tvc_data = "<?php echo esc_js(wp_json_encode($tvc_data)); ?>";
        <?php if(isset($_GET['g_mail']) && isset($_GET['wizard_channel']) && $_GET['wizard_channel'] == 'gmcsetting') { ?>
            jQuery('.getGMCList').addClass('d-none')
            list_google_merchant_account(tvc_data);
            jQuery('.saveGMC').prop('disabled', false)
        <?php } 
        if (isset($_GET['tiktok_mail']) == TRUE) { ?>
            jQuery('.gettitokList').addClass('d-none')
            jQuery('#tiktok_business_id').prop('disabled', false)
            list_tiktok_business_account();
            jQuery('.saveTiktok').prop('disabled', false)
        <?php } 
        if (isset($_GET['g_mail']) == TRUE && isset($_GET['wizard_channel']) &&  $_GET['wizard_channel'] == 'fbsetting') { ?>                
                // get_facebook_bussiness()
                // jQuery('#fb_business_id').prop('disabled', false)
                // jQuery('#fb_catalog_id').prop('disabled', false)
                // jQuery('.getfbList').addClass('d-none')
                // jQuery('.saveFB').prop('disabled', false)
        <?php }
        ?>      
        if(tiktok_user_id !== '') {
            list_tiktok_business_account(); 
        } 
        // get_facebook_bussiness()  
        
        jQuery(document).on('click', '#home-tab', function() { 
            if(jQuery("select").hasClass('catalogId')){
                if(jQuery('.catalogId').data('select2')) {
                    jQuery(".catalogId").select2('destroy')
                }
            }   
            if(jQuery('.createSelect').data('select2')) {
                jQuery('.createSelect').select2('destroy');
                jQuery('#feed_target_country').select2('destroy');
            }         
            jQuery('.selecttwo').select2();
            jQuery('.selectthree').select2();
            jQuery('.selectfour').select2();
            jQuery('.selectfive').select2();
            if(jQuery("select").hasClass('catalogId')){
                jQuery(".catalogId").select2({ dropdownCssClass: "fs-12" })
            }
        })
        jQuery(document).on('click', '.getGMCList', function() {
            jQuery('.getGMCList').addClass('d-none')
            list_google_merchant_account(tvc_data);
            jQuery('.saveGMC').prop('disabled', false)
        })
        jQuery(document).on('click', '.gettitokList', function() {
            jQuery('.gettitokList').addClass('d-none')
            jQuery('#tiktok_business_id').prop('disabled', false)
            list_tiktok_business_account();
            jQuery('.saveTiktok').prop('disabled', false)            
        })
        // jQuery(document).on('click', '.getfbList', function() {
        //     jQuery('.getfbList').addClass('d-none')
        //     jQuery('#fb_business_id').prop('disabled', false)
        //     jQuery('#fb_catalog_id').prop('disabled', false)
        //     get_facebook_bussiness();
        //     jQuery('.saveFB').prop('disabled', false)
        // })
        jQuery(document).on('change', '.catalogId', function() {
            jQuery('.saveTiktok').prop('disabled', false)
        })
        jQuery(document).on('click', '.goBackAttr', function() {
            jQuery('[data-bs-target="#profile"]').trigger('click') 
        })
        jQuery(document).on('click', '.goBackHome', function() {
            jQuery('[data-bs-target="#home"]').trigger('click') 
        })
        jQuery(document).on('click', '.goToFeed', function() {
            jQuery('[data-bs-target="#contact"]').trigger('click') 
        })
        /*************************document event start *********************************************************************/
        /**********Channel Configuration Events start **********************************************************************/
        jQuery(document).on("change", "#tiktok_business_id", function () {            
            get_tiktok_user_catalogs()
        });
        // jQuery(document).on("change", "#fb_business_id", function () {
        //     jQuery('.selection').find("[aria-labelledby='select2-fb_business_id-container']").removeClass('selectError');
        //     jQuery('.selection').find("[aria-labelledby='select2-fb_catalog_id-container']").removeClass('selectError');
        //     get_fb_catalog_data()
        // });
        // jQuery(document).on("change", "#fb_catalog_id", function () {
        //     jQuery('.selection').find("[aria-labelledby='select2-fb_catalog_id-container']").removeClass('selectError');
        // });        
        jQuery(document).on('click', '.saveTiktok', function() {
            saveChannel('Tiktok');
        })
        // jQuery(document).on('click', '.saveFB', function() {
        //     saveChannel('FB');
        // })
        jQuery(document).on("change", "#google_merchant_center_id", function () {
            jQuery('.selection').find("[aria-labelledby='select2-google_merchant_center_id-container']").removeClass('selectError');
        })        
        
        /**********Channel Configuration Events end **********************************************************************/
        //On click get Mapping html
        jQuery(document).on('click', '#profile-tab', function() {
            if(jQuery('.selecttwo').data('select2')) {
                jQuery('.selecttwo').select2('destroy');
                jQuery('.selectthree').select2('destroy');
                jQuery('.selectfour').select2('destroy');
                jQuery('.selectfive').select2('destroy');
                jQuery(".catalogId").select2('destroy');
            }
            if(jQuery('.createSelect').data('select2')) {
                jQuery('.createSelect').select2('destroy');
                jQuery('#feed_target_country').select2('destroy');
            }
            var classname = jQuery('#profile').attr('class')
            if(classname.indexOf('active') != -1){
                return true;
            }
            getAttrubuteMappingDiv()
        })

        //Vallidation for number input
        jQuery(document).on('keydown', 'input[name="shipping"]', function (event) {
            if (event.shiftKey == true) {
                event.preventDefault();
            }
            if ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105) || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {
                
                
            } else {
                event.preventDefault();
            }

            if (jQuery(this).val().indexOf('.') !== -1 && event.keyCode == 190)
                event.preventDefault();

        })    
        jQuery(document).on('input', 'input[name="tax"]', function() {
            // Remove any non-numeric characters
            jQuery(this).val(jQuery(this).val().replace(/[^0-9.]/g, ''));

            // Ensure only one decimal point
            if (jQuery(this).val().split('.').length > 2) {
                jQuery(this).val(jQuery(this).val().substring(0, jQuery(this).val().lastIndexOf('.')));
            }
            
            // Restrict the maximum value to 100
            if (parseFloat(jQuery(this).val()) > 100) {
                console.log(jQuery(this))
                jQuery(this).val('100');
            }
        });

        //Add Additional Attribute options
        jQuery(document).on('click', '.add_additional_attr', function() { 
            var additionalAttribute=[{"field":"condition"},{"field":"shipping_weight"},{"field":"product_weight"},
                                    {"field":"gender"},{"field":"sizes"},{"field":"color"},{"field":"age_group"},
                                    {"field":"additional_image_links"},{"field":"sale_price_effective_date"},
                                    {"field":"material"},{"field":"pattern"},{"field":"availability_date"},{"field":"expiration_date"},
                                    {"field":"product_types"},{"field":"ads_redirect"},{"field":"adult"},{"field":"shipping_length"},
                                    {"field":"shipping_width"},{"field":"shipping_height"},{"field":"custom_label_0"},{"field":"custom_label_1"},
                                    {"field":"custom_label_2"},{"field":"custom_label_3"},{"field":"custom_label_4"},{"field":"mobile_link"},
                                    {"field":"energy_efficiency_class"},{"field":"is_bundle"},{"field":"promotion_ids"},{"field":"loyalty_points"},
                                    {"field":"unit_pricing_measure"},{"field":"unit_pricing_base_measure"},{"field":"shipping_label"},
                                    {"field":"excluded_destinations"},{"field":"included_destinations"},{"field":"tax_category"},
                                    {"field":"multipack"},{"field":"installment"},{"field":"min_handling_time"},{"field":"max_handling_time"},
                                    {"field":"min_energy_efficiency_class"},{"field":"max_energy_efficiency_class"},{"field":"identifier_exists"},
                                    {"field":"cost_of_goods_sold"}];
            var count = Object.keys(additionalAttribute).length;
            $option = '<option value="">Please Select Attribute</option>';
            jQuery.each(additionalAttribute, function (index, value) {
                /*****Check for selected option to disabled start*******/
                $disabled = "";                                                    
                if(jQuery.inArray(value.field, selected) !== -1){
                    $disabled = "disabled";
                }
                /*****Check for selected option to disabled end*******/                                              
                $option += '<option value="'+value.field+'" '+$disabled+'>'+value.field+'</option>'
            });
            var wooCommerceAttributes = <?php echo wp_json_encode($wooCommerceAttributes); ?>;
            $option1 = '<option value="">Please Select Attribute</option>';
            jQuery.each(wooCommerceAttributes, function (index, value) {
                $option1 += '<option value="'+value.field+'">'+value.field+'</option>'
            });

            $html = '';
            $html += '<div class="row additinal_attr_div m-0 p-0" ><div class="col-6 mt-2">';
            $html += '<select style="width:100%" id="'+ cnt++ +'" name="additional_attr_[]" class="additinal_attr_'+cnt+' additinal_attr fw-light text-secondary fs-6 form-control form-select-sm select2">';
            $html += $option;
            $html += '</select></div>';
            $html += '<div class="col-5 mt-2">';
            $html += '<select style="width:100%" id="" name="additional_attr_value_[]" class="additional_attr_value fw-light text-secondary fs-6 form-control form-select-sm select2">';
            $html += $option1;
            $html += '</select></div>';
            $html += '<div class="col-1 mt-2">';
            $html += '<span class="material-symbols-outlined text-danger remove_additional_attr fs-5 mt-2" title="Add Additional Attribute" style="cursor: pointer; margin-right:35px;">';
            $html += 'delete';
            $html += '</span>';                                               
            $html += '</div></div>';
            jQuery('.additinal_attr_main_div').append($html);
            setTimeout(function() {
                jQuery('.additinal_attr, .additional_attr_value, .select_modal').select2({
                    initSelection: function(element, callback) {                   
                     }
                });  
            }, 200);       
            jQuery('.add_additional_attr')[0].scrollIntoView(true);
            var div_count = jQuery('.additinal_attr_div').length;
            if(count == div_count){
                jQuery('.add_additional_attr').addClass('d-none');
            }   
        });

        //remove Additional Attribute options
        jQuery(document).on('click', '.remove_additional_attr', function() {
            jQuery('.remove_additional_attr *').addClass('disabled');
            //get deleted selected tag value
            var deleted = jQuery(this).parent().parent('.additinal_attr_div').find('.additinal_attr').find(':selected').val();
            if(deleted != ''){
                //Remove value from array
                selected = jQuery.grep(selected, function(value) {
                            return value != deleted;
                        });
            //Enable deleted value to other selecet tag
            jQuery(".additinal_attr option").each(function() {
                    var $thisOption = jQuery(this);
                    var valueToCompare = deleted;
                    if($thisOption.val() == valueToCompare) {
                        $thisOption.removeAttr("disabled");
                    }
                });  
            }
                                                                                        
            jQuery(this).parent().parent('.additinal_attr_div').remove();
            jQuery('.add_additional_attr').removeClass('d-none');
            jQuery('.remove_additional_attr *').removeClass('disabled');
        });

        //get Additional Attribute values
        jQuery(document).on('change', '.additinal_attr', function() {  
            selected = []; 
            jQuery(this).parent().find('.select2-selection').removeClass('selectError')                                             
            var sel =  jQuery(this).find(":selected").val();
            var id = jQuery(this).attr("id");        
            //All empty select add more used, it will add disable attribute to selected value
            jQuery(".additinal_attr:not(#"+id+") option").each(function() {
                var $thisOption = jQuery(this);
                var valueToCompare = sel;
                if($thisOption.val() == valueToCompare) {
                    $thisOption.attr("disabled", "disabled");
                }
            });  
            var attr_choices = jQuery(".additinal_attr option:selected");
            jQuery(attr_choices).each(function(i, v) {
                selected.push(attr_choices.eq(i).val());
            })     
            disableOptions();   
        })

        //remove Additional Attribute error on change
        jQuery(document).on('change', '.additional_attr_value', function() { 
            jQuery(this).parent().find('.select2-selection').removeClass('selectError')
        });
        jQuery(document).on('change', '#id', function() {
            jQuery('.selection').find("[aria-labelledby='select2-id-container']").removeClass('selectError');
        })
        jQuery(document).on('change', '#title', function() {
            jQuery('.selection').find("[aria-labelledby='select2-title-container']").removeClass('selectError');
        })
        jQuery(document).on('change', '#description', function() {
            jQuery('.selection').find("[aria-labelledby='select2-description-container']").removeClass('selectError');
        })
        jQuery(document).on('change', '#price', function() {
            jQuery('.selection').find("[aria-labelledby='select2-price-container']").removeClass('selectError');
        })
        //Save Attribute mapping
        jQuery(document).on("click", "#attr_mapping_save", function () {
            /*******************Check  some mandatory filed*****************/
            var attrCheck = ['id', 'title', 'description', 'price'];
            var hasError = false;
            jQuery.each(attrCheck, function (index, value) {
                if(jQuery("#"+value).find(":selected").val() == '') {
                    jQuery('.selection').find("[aria-labelledby='select2-"+value+"-container']").addClass('selectError');
                    hasError = true;
                }
            })
            if(hasError === true) {
                return false
            }
            
            /****additional Attribute validation start*********/
            var attrValidation = false;
            jQuery(".additinal_attr").each(function () {
                if (this.selectedIndex === 0) {
                    jQuery(this).parent().find('.select2-selection').addClass('selectError')
                    attrValidation = true;
                    //return false;
                }
            })
            
            var attrValueValidation = false;
            jQuery(".additional_attr_value").each(function () {
                if (this.selectedIndex === 0) {
                    jQuery(this).parent().find('.select2-selection').addClass('selectError')
                    attrValueValidation = true;
                    //return false;
                }
            })
            if(attrValidation === true) {
                return false;
            }
            if(attrValueValidation === true) {
                return false;
            }
            /****additional Attribute validation end*********/
            let ee_data = jQuery("#attribute_mapping").find("input[value!=''], select:not(:empty), input[type='number']").serialize();            
            var data = {
                action: "save_attribute_mapping",
                ee_data: ee_data,
                auto_product_sync_setting: "<?php echo esc_html(wp_create_nonce('auto_product_sync_setting-nonce')); ?>"
            };
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: data,
                beforeSend: function () { 
                    //manageattributeLoader('show');
                    jQuery('#attr_mapping_save').prop('disabled', true)
                    jQuery('.add_additional_attr').prop('disabled', true)
                    jQuery('#attr_mapping_save > span').removeClass('d-none')
                    
                },
                success: function (response) { 
                    //manageattributeLoader('hide');
                    jQuery('#attr_mapping_save').prop('disabled', false)
                    jQuery('.add_additional_attr').prop('disabled', false)
                    jQuery('#attr_mapping_save > span').addClass('d-none')
                    checkProgressBar(); 
                    openOverlayLoader('openshow');
                    setTimeout(function() {
                        openOverlayLoader('close');
                        jQuery('[data-bs-target="#contact"]').trigger('click')
                    }, 2000);                   
                    
                }
            });
        });
        /********** Attribute Mapping End ******************************************************************************/
        /*****************Create Feed Start **************************************************************************/
        jQuery(document).on('click', '#contact-tab', function() {            
            if(jQuery('.selecttwo').data('select2')) {
                jQuery('.selecttwo').select2('destroy');
                jQuery('.selectthree').select2('destroy');
                jQuery('.selectfour').select2('destroy');
                jQuery('.selectfive').select2('destroy');
            }
            jQuery('.createSelect').select2();
            jQuery('#feed_target_country').select2(); 
            if(jQuery('input#all_product').is(':checked'))  {
                jQuery('#all_product').trigger('change');
            }
            getAllChannel();      
        })
        jQuery(document).on('change', '#filter_product', function() {
            if(jQuery('input#filter_product').is(':checked')) {
                jQuery('.AllFilters').removeClass('d-none')
            }
            jQuery('.totalCountDiv').addClass('d-none')
        })
        jQuery(document).on('change', '#specific_product', function() {
            jQuery('.AllFilters').addClass('d-none')
            jQuery('.totalCountDiv').addClass('d-none')
        })
        jQuery(document).on('change', '#all_product', function() {
            jQuery('.AllFilters').addClass('d-none')
            var data = {
                    action: "get_category_for_filter",
                    type: "all_product",
                    get_category_for_filter: "<?php echo esc_html(wp_create_nonce('get_category_for_filter-nonce')); ?>"
                };
                jQuery.ajax({
                    type: "POST",
                    dataType: "json",
                    url: tvc_ajax_url,
                    data: data,
                    beforeSend: function () { 
                    },
                    success: function (response) {
                        jQuery('.totalCountDiv').removeClass('d-none')
                        jQuery('.totalCount').text(response)
                    }
                });
        })
        jQuery(document).on('change', '.product', function(event) {
            var changeValue = jQuery(this).val();
            jQuery(this).parent().parent().children('div').eq(1).empty();
            var conditionDropDown = getConditionDropDown(changeValue);
            jQuery(this).parent().parent().children('div').eq(1).append(conditionDropDown);
            if (changeValue === 'product_cat') {
                var data = {
                    action: "get_category_for_filter",
                    type: "category",
                    get_category_for_filter: "<?php echo esc_html(wp_create_nonce('get_category_for_filter-nonce')); ?>"
                };
                jQuery.ajax({
                    type: "POST",
                    dataType: "json",
                    url: tvc_ajax_url,
                    data: data,
                    beforeSend: function () { 
                        jQuery('.condition, .value').css("pointer-events", "none");
                    },
                    success: function (response) {
                        jQuery('.condition, .value').css("pointer-events", "auto");
                        var category = response;
                        let option = '<option value="0">Select Category</option>';
                        jQuery.each(category, function(key, value) {
                            option += '<option value="' + key + '">' + value + '</option>';
                        });
                        jQuery(this).parent().parent().children('.textValue').empty();
                        var html = '<select class="createSelect value" name="value" style="width:100%">' +
                            option +
                            '</select>';
                            jQuery('.textValue').html(html);
                            jQuery('.createSelect').select2();
                    }
                });
                
            } else if (changeValue === '_stock_status'){
                jQuery(this).parent().parent().children('.textValue').empty();
                var html = '<select class="createSelect value" name="value" style="width:100%">'+
                    '<option value="0">Select Stock Status</option>'+
                    '<option value="instock">In Stock</option>'+
                    '<option value="outofstock">Out Of Stock</option>'+
                    '<option value="onbackorder">Back Order</option>'+
                    '</select>';
                    jQuery(this).parent().parent().children('.textValue').append(html);
            } else {
                jQuery(this).parent().parent().children('.textValue').empty();
                var html = '<input type="text" class="form-control from-control-overload value" placeholder="Add value" name="value" >';
                jQuery(this).parent().parent().children('.textValue').append(html);
            }
            jQuery('.createSelect').select2();
        });
        jQuery(document).on('click', '.applyFilter', function() {   
            var newProductVal = jQuery('.product').val();
            var newConditionVal = jQuery('.condition').val();
            var newValueVal = jQuery('.value').val();  
            if( newProductVal != 0 && newConditionVal != 0 && newValueVal != 0 ) {
                if( newProductVal !== '' && newConditionVal !== '' && newValueVal !== '' ) {
                    var count = jQuery('.removecardThis').length
                    var productVal= jQuery('#productVal').val();
                    var conditionVal = jQuery('#conditionVal').val();
                    var valueVal = jQuery('#valueVal').val();            

                    productVal == "" ? jQuery('#productVal').val(newProductVal) : jQuery('#productVal').val(productVal + "," +newProductVal);
                    conditionVal == "" ? jQuery('#conditionVal').val(newConditionVal) : jQuery('#conditionVal').val(conditionVal + "," +newConditionVal);
                    valueVal == "" ? jQuery('#valueVal').val(newValueVal) : jQuery('#valueVal').val(valueVal + "," +newValueVal);
                    if(newProductVal == 'product_cat' || newProductVal == '_stock_status') {
                        newValueVal = jQuery("select[name='value'] option:selected").text();
                    } 
                    var newCard = '<div class="btn-group border rounded mt-1 me-1 removecardThis" >' +
                                        '<button class="btn btn-light btn-sm text-secondary fs-7 ps-1 pe-1 pt-0 pb-0" type="button" value="'+count+'">' + newProductVal + ' <b>' + newConditionVal + '</b> ' + newValueVal + '</button>' +
                                        '<button type="button" class="btn btn-sm btn-light onhover-close pt-0 pb-0" data-bs-toggle="" aria-expanded="false" style="cursor: pointer;">' +
                                        '<span class="material-symbols-outlined fs-6 pt-1 onhover-close removecard">close</span></button></div>';
                    jQuery('.filterCard').append(newCard)
                    jQuery('.product').val('0').trigger('change');
                    jQuery('.condition').val('0').trigger('change');
                    jQuery('.value').val('').trigger('change');
                    getFilterCount();     
                }  
            }         
        })
        jQuery(document).on('click', '#createFeed', function() {
            let feedName = jQuery('#feedName').val();
            if (feedName === '') {
                jQuery('#feedName').css('margin-left', '0px');
                jQuery('#feedName').css('margin-right', '0px');
                jQuery('#feedName').addClass('errorInput');
                var l = 4;
                for (var i = 0; i <= 2; i++) {
                    jQuery('#feedName').animate({
                        'margin-left': '+=' + (l = -l) + 'px',
                        'margin-right': '-=' + l + 'px'
                    }, 50);
                }
                return false;
            }

            let autoSyncIntvl = jQuery('#autoSyncIntvl').val();
            if (autoSyncIntvl === '') {
                jQuery('#autoSyncIntvl').css('margin-left', '0px');
                jQuery('#autoSyncIntvl').css('margin-right', '0px');
                jQuery('#autoSyncIntvl').addClass('errorInput');
                var l = 4;
                for (var i = 0; i <= 2; i++) {
                    jQuery('#autoSyncIntvl').animate({
                        'margin-left': '+=' + (l = -l) + 'px',
                        'margin-right': '-=' + l + 'px'
                    }, 50);
                }
                return false;
            }

            let target_country = jQuery('#feed_target_country').find(":selected").val();
            if (target_country === "") {
                jQuery('[aria-labelledby="select2-feed_target_country-container"]').css('border', '1px solid #ef1717');                
                return false;
            }

            if (!jQuery('#gmc_id').is(":checked") && !jQuery('#tiktok_id').is(":checked") && !jQuery('#fb_id').is(':checked')) {
                jQuery('.errorChannel').not(':disabled').css('border', '1px solid red');
                return false;
            }
            var product_selection = jQuery('input[name="product_selection"]:checked').val();            
            if(product_selection == 'filter_product') {
                var productVal= jQuery('#productVal').val();
                var conditionVal = jQuery('#conditionVal').val();
                var valueVal = jQuery('#valueVal').val(); 
                if(productVal == '' || conditionVal == '' || valueVal == '') {
                    jQuery('.product').next().find('.select2-selection').css('border', '1px solid #ef1717')
                    jQuery('.condition').next().find('.select2-selection').css('border', '1px solid #ef1717')
                    jQuery('.value').next().find('.select2-selection').css('border', '1px solid #ef1717')
                    jQuery('.value').css('border', '1px solid #ef1717')
                    return false;
                }                
            }
            save_feed_data();
            
        })
        jQuery(document).on('input', '#feedName', function (e) {
            e.preventDefault();
            jQuery('#feedName').css('margin-left', '0px');
            jQuery('#feedName').css('margin-right', '0px');
            jQuery('#feedName').removeClass('errorInput');
        });
        jQuery(document).on('click', '#gmc_id', function (e) {
            jQuery('.errorChannel').css('border', '');
        });
        jQuery(document).on('click', '#tiktok_id', function (e) {
            jQuery('.errorChannel').css('border', '');
        });
        jQuery(document).on('click', '#fb_id', function (e) {
            jQuery('.errorChannel').css('border', '');
        });
        jQuery(document).on('change', '#feed_target_country', function (e) {
            jQuery('.select2-selection').css('border', '1px solid #c6c6c6');
            let target_country = jQuery('#feed_target_country').find(":selected").val();
            jQuery('#tiktok_id').empty();
            jQuery('.tiktok_catalog_id').empty()
            if (jQuery('input#tiktok_id').is(':checked')) {
                getCatalogId(target_country);
            }
        });
        jQuery(document).on('change', '#tiktok_id', function () {
            jQuery('.tiktok_catalog_id').empty();
            jQuery('#tiktok_id').val('');
            if (jQuery('#tiktok_id').is(":checked")) {
                getCatalogId(jQuery('#feed_target_country').find(":selected").val())
            }
        });
        jQuery(document).on('change', '#autoSync', function () {
            var autoSync = jQuery('input#autoSync').is(':checked');
            if (autoSync) {
                jQuery('#autoSyncIntvl').attr('disabled', false);
            } else {
                jQuery('#autoSyncIntvl').attr('disabled', true);
                jQuery('#autoSyncIntvl').val(25);
                jQuery('#autoSyncIntvl').removeClass('errorInput');
            }
        });
        jQuery(document).on('click', '.removecard', function(event) {
            var ele = jQuery(this).parent();
            var productVal= jQuery('#productVal').val().split(',');
            var conditionVal = jQuery('#conditionVal').val().split(',');
            var valueVal = jQuery('#valueVal').val().split(','); 
            var val = ele.prev().val();            
            jQuery(ele.parent()).remove();
            productVal.splice(val, 1);
            conditionVal.splice(val, 1);
            valueVal.splice(val, 1);

            jQuery(".removecard").each(function(index, value) {
                jQuery(this).parent().prev().val(index);
            });


            productVal = productVal.join();
            conditionVal = conditionVal.join();
            valueVal = valueVal.join();
            jQuery('#productVal').val(productVal);
            jQuery('#conditionVal').val(conditionVal);
            jQuery('#valueVal').val(valueVal);
            getFilterCount();
            //table.draw();
        });
        /******************Create Feed End ***************************************************************************/
        /************************* document event end ***************************************************************/
    });
    
    function list_tiktok_business_account() {   
        var conversios_onboarding_nonce = "<?php echo esc_attr(wp_create_nonce('conversios_onboarding_nonce')); ?>";    
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
                action: "get_tiktok_business_account",
                subscriptionId: "<?php echo esc_attr($subscriptionId) ?>",
                conversios_onboarding_nonce: conversios_onboarding_nonce
            },
            beforeSend: function(){ 
                manageTiktokLoader('show')
            },
            success: function (response) { 
                if (response.error === false) {
                    jQuery('#tiktok_business_id').empty();
                    jQuery('#tiktok_business_id').append(jQuery('<option>', {
                        value: "",
                        text: "Select TikTok Business Account Id"
                    }));
                    if (response.data) {
                        var tiktok_business_id = "<?php echo esc_attr($tiktok_business_id) ?>";
                        jQuery.each(response.data, function (key, value) {
                            jQuery('#tiktok_business_id').append(jQuery('<option>', {
                                value: key,
                                "data-business_name": value,
                                text: key + ' - ' + value,
                                selected: (key === tiktok_business_id)
                            }));
                        });
                    }
                    get_tiktok_user_catalogs();
                } else {
                    jQuery('#tiktok_business_id').empty();
                    jQuery('#tiktok_business_id').append(jQuery('<option>', {
                        value: "",
                        text: "Select Tiktok Business Account Id"
                    })); 
                    manageTiktokLoader('hide')                  
                }
            }
        })
    }
    function get_tiktok_user_catalogs() {
        var catalogCountry = <?php echo wp_json_encode($catalogCountry) ?>;
        var catalog_business_id = <?php echo wp_json_encode($catalog_business_id) ?>;
        var conversios_onboarding_nonce = "<?php echo esc_attr(wp_create_nonce('conversios_onboarding_nonce')); ?>";
        var business_id = jQuery('#tiktok_business_id').find(":selected").val();
        jQuery('.selection').find("[aria-labelledby='select2-tiktok_business_id-container']").removeClass('selectError');
        jQuery('#table-body').empty();
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
                action: "get_tiktok_user_catalogs",
                customer_subscription_id: "<?php echo esc_attr($subscriptionId) ?>",
                business_id: business_id,
                conversios_onboarding_nonce: conversios_onboarding_nonce
            },
            beforeSend: function(){ 
                manageTiktokLoader('show')
            },
            success: function (response) {
                if (response.error === false) {
                    if (response.data) {                        
                        var tableBody = '';
                        jQuery.each(response.data, function (key, value) {                            
                            tableBody += '<tr>';
                            tableBody += '<td class="align-middle text-start">' + key + '</td>';
                            tableBody += '<td class="align-middle text-start"><select id="" name="catalogId[]" class="form-select form-select-lg mb-3 catalogId" style="width: 100%">';
                            jQuery.each(value, function (valKey, ValValue) {
                                var selected = "";
                                if (jQuery.inArray(valKey, catalog_business_id) !== -1 && catalog_business_id.length > 0) {
                                    var selected = 'selected="selected"';
                                }
                                tableBody += '<option value="' + valKey + '"  data-catalog_country="' + key + '" data-catalog_name="' + ValValue + '" ' + selected + '>' + valKey + ' - ' + ValValue + '</option>';
                            })
                            tableBody += '</select></td></tr>';
                        });
                        jQuery('#table-body').html(tableBody);
                        jQuery(".catalogId").select2({ dropdownCssClass: "fs-12" })
                    }
                }
                manageTiktokLoader('hide')
            }
        })
    }
    // function get_facebook_bussiness() {
    //     var data = {
    //             action: "get_user_businesses",
    //             customer_subscription_id: <?php //echo $subId ?>,
    //             fb_business_nonce: "<?php// echo wp_create_nonce('fb_business_nonce'); ?>"
    //         }
    //         jQuery.ajax({
    //             type: "POST",
    //             dataType: "json",
    //             url: tvc_ajax_url,
    //             data: data,
    //             beforeSend: function(){ 
    //                 manageFbLoader('show')
    //             },
    //             success: function(response){
    //                 if(Object.keys(response).length > 0) {
    //                     // jQuery('#fb_business_id').removeAttr('disabled')
    //                     var cat_id = "<?php// echo isset($ee_options['facebook_setting']['fb_business_id']) ? $ee_options['facebook_setting']['fb_business_id'] : '' ?>";                      
    //                     $html = '<option value="">Select Catalog Id</option>';
    //                     $.each(response, function(index, value){
    //                         var selected = (index == cat_id ) ? 'selected' : '';                        
    //                         $html +='<option value="'+index+'" '+selected+'>'+value+'</option>';
    //                     });
    //                     $('#fb_business_id').html($html);
    //                     get_fb_catalog_data()
    //                 } else {
    //                     manageFbLoader('hide')
    //                 } 
    //             }
    //         });
    // }
    // function get_fb_catalog_data() {        
    //     var fb_business = jQuery('#fb_business_id').find(":selected").val();
    //     if(fb_business != ''){
    //         var data = {
    //             action: "get_fb_catalog_data",
    //             customer_subscription_id: <?php //echo $subId ?>,
    //             fb_business_id: fb_business,
    //             fb_business_nonce: "<?php //echo wp_create_nonce('fb_business_nonce'); ?>"
    //         }
    //         jQuery.ajax({
    //             type: "POST",
    //             dataType: "json",
    //             url: tvc_ajax_url,
    //             data: data,
    //             beforeSend: function(){ 
    //                 manageFbLoader('show')
    //             },
    //             success: function(response){ 
    //                 // jQuery('#fb_catalog_id').removeAttr('disabled')
    //                 var cat_id = "<?php echo isset($ee_options['facebook_setting']['fb_catalog_id']) ? esc_attr($ee_options['facebook_setting']['fb_catalog_id']) : '' ?>";                      
    //                 $html = '<option value="">Select Catalog Id</option>';
    //                 $.each(response, function(index, value){
    //                     var selected = (value.id == cat_id ) ? 'selected' : '';                        
    //                     $html +='<option value="'+value.id+'" '+selected+'>'+value.id+'-'+value.name+'</option>';
    //                 });
    //                 $('#fb_catalog_id').html($html);
    //                 manageFbLoader('hide')                 
    //             }
    //         });
    //     } else {
    //         $html = '<option value="">Select Catalog Id</option>';
    //         $('#fb_catalog_id').html($html);
    //         manageFbLoader('hide') 
    //     }
    // }
    
    function getAttrubuteMappingDiv() {
        var data = {
                action: "get_attribute_mappingv_div",                
                fb_business_nonce: "<?php echo esc_attr(wp_create_nonce('fb_business_nonce')); ?>"
            }
            jQuery.ajax({
                type: "POST",
                dataType: "",
                url: tvc_ajax_url,
                data: data,
                beforeSend: function(){ 
                },
                success: function(response){ 
                    jQuery('#attribute_mapping').html(response)
                    jQuery('.select_modal').select2();
                    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl)
                    });
                    cnt = jQuery('#cnt').val();  
                }
            });
    }
    function disableOptions() {
        //remove attr
        jQuery('.additinal_attr *').removeAttr("disabled");
        jQuery(selected).each(function(i, v) {
            jQuery(".additinal_attr option").each(function() {
                var $thisOption = jQuery(this);
                var valueToCompare = v;
                if(jQuery(this).parent().find(':selected').val() != v) {
                    if($thisOption.val() == valueToCompare) {
                        $thisOption.attr("disabled", "disabled");
                    }
                }
                
            }); 
        })
    }
    function getConditionDropDown(val = '', condition = '') {
        let conditionOption = '<select class="condition createSelect" name="condition[]" style="width: 100%"><option value="0">Select Condition</option>';
        if (val != '0') {
            if (val != '' || condition != '') {
                switch (val) {
                    case 'product_cat':
                    case 'ID':
                        conditionOption += '<option value="=" ' + ((condition == "=") ? "selected" : "") + ' > = </option>' +
                            '<option value="!=" ' + ((condition == "!=") ? "selected" : "") + ' > != </option>';
                        break;
                    case '_stock_status':
                        conditionOption += '<option value="=" ' + ((condition == "=") ? "selected" : "") + ' > = </option>' +
                            '<option value="!=" ' + ((condition == "!=") ? "selected" : "") + ' > != </option>';
                        break;
                    case 'post_title':
                    case '_sku':
                    case 'post_content':
                    case 'post_excerpt':
                        conditionOption += '<option value="Contains" ' + ((condition === "Contains") ? "selected" : "") + ' > Contains </option>' +
                            '<option value="Start With" ' + ((condition === "Start With") ? "selected" : "") + ' > Start With </option>' +
                            '<option value="End With" ' + ((condition === "End With") ? "selected" : "") + ' > End With </option>';
                        break;
                    case '_regular_price':
                    case '_sale_price':
                        conditionOption += '<option value="=" ' + ((condition === "=") ? "selected" : "") + ' > = </option>' +
                            '<option value="!=" ' + ((condition === "!=") ? "selected" : "") + ' > != </option>' +
                            '<option value="<" ' + ((condition === "<") ? "selected" : "") + ' > < </option>' +
                            '<option value=">" ' + ((condition === ">") ? "selected" : "") + ' > > </option>' +
                            '<option value=">=" ' + ((condition === ">=") ? "selected" : "") + ' > >= </option>' +
                            '<option value="<=" ' + ((condition === "<=") ? "selected" : "") + ' > <= </option>';
                        break;
                }
            }
        }
        conditionOption += '</select>';
        return conditionOption;
    }
    function getFilterCount() {
        var productVal= jQuery('#productVal').val();
        var conditionVal = jQuery('#conditionVal').val();
        var valueVal = jQuery('#valueVal').val();
        jQuery('.totalCountDivFilter').addClass('d-none')                
        jQuery('.totalCountFilter').html('')
        if(productVal !== '' && conditionVal !== '' && valueVal !== '') {
            var data = {
                action: "get_product_filter_count",
                productVal: productVal,
                conditionVal: conditionVal,
                valueVal: valueVal,
                getFilterCount: "<?php echo esc_html(wp_create_nonce('getFilterCount-nonce')); ?>"
            };
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: data,
                beforeSend: function () { 
                },
                success: function (response) {   
                    jQuery('.totalCountDivFilter').removeClass('d-none')                
                    jQuery('.totalCountFilter').html(response)
                }
            });
        }
    }
    function getAllChannel() {
        let target_country = jQuery('#feed_target_country').find(":selected").val();
        var data = {
            action: "get_category_for_filter",
            type: "getAllChannel",
            target_country: target_country,
            get_category_for_filter: "<?php echo esc_html(wp_create_nonce('get_category_for_filter-nonce')); ?>"
        };
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () { 
                jQuery('#gmc_id').prop('disabled', true)
                jQuery('#fb_id').prop('disabled', true)
                jQuery('#tiktok_id').prop('disabled', true)
            },
            success: function (response) {
                if(response.data.google_merchant_center_id){
                    jQuery('#gmc_id').prop('disabled', false)
                    jQuery('#gmc_id').prop('checked', true)
                    jQuery('.google_merchant_center_id').text(response.data.google_merchant_center_id)
                }
                if(response.data.fb_catalog_id){
                    jQuery('#fb_id').prop('disabled', false)
                    jQuery('#fb_id').prop('checked', true)
                    jQuery('.fb_id').text(response.data.fb_catalog_id)
                }                    
                if (response.data.tiktok_setting == 1 && response.data.titkok_catalog_id !== '') {
                    jQuery('#tiktok_id').prop('disabled', false)
                    jQuery('#tiktok_id').prop('checked', true)
                    jQuery('#tiktok_id').val(response.data.titkok_catalog_id);
                    jQuery('.tiktok_catalog_id').text(response.data.titkok_catalog_id)
                } else if (response.data.tiktok_setting == 1 && response.data.titkok_catalog_id == "") {
                    jQuery('#tiktok_id').prop('disabled', false)
                    jQuery('#tiktok_id').prop('checked', true)
                    jQuery('#tiktok_id').val('Create New');
                    jQuery('.tiktok_catalog_id').text('You do not have a catalog associated with the selected target country. Do not worry we will create a new catalog for you.');
                }
            }
        });
    }
    function getCatalogId($countryCode) {
        var conv_country_nonce = "<?php echo esc_html(wp_create_nonce('conv_country_nonce')); ?>";
        var data = {
            action: "ee_getCatalogId",
            countryCode: $countryCode,
            conv_country_nonce: conv_country_nonce
        }
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () {
            },
            error: function (err, status) {
                //conv_change_loadingbar_modal('hide');
            },
            success: function (response) {
                jQuery('.tiktok_catalog_id').empty()
                jQuery('#tiktok_id').empty();
                jQuery('.tiktok_catalog_id').removeClass('text-danger');

                if (response.error == false) {
                    if (response.data.catalog_id !== '') {
                        jQuery('#tiktok_id').val(response.data.catalog_id);
                        jQuery('.tiktok_catalog_id').text(response.data.catalog_id)
                    } else {
                        jQuery('#tiktok_id').val('Create New');
                        jQuery('.tiktok_catalog_id').text('You do not have a catalog associated with the selected target country. Do not worry we will create a new catalog for you.');
                    }
                }
            }
        });
    }
    
    function save_feed_data() {
        var conv_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conv_onboarding_nonce')); ?>";
        var product_selection = jQuery('input[name="product_selection"]:checked').val();
        var productVal= jQuery('#productVal').val();
        var conditionVal = jQuery('#conditionVal').val();
        var valueVal = jQuery('#valueVal').val();
        var data = {
            action: "create_dashboard_feed_data",
            feedName: jQuery('#feedName').val(),
            google_merchant_center: jQuery('input#gmc_id').is(':checked') ? '1' : '',
            fb_catalog_id:jQuery('input#fb_id').is(':checked') ? '2' : '',
            tiktok_id: jQuery('input#tiktok_id').is(':checked') ? '3' : '',
            tiktok_catalog_id: jQuery('input#tiktok_id').is(':checked') ? jQuery('input#tiktok_id').val() : '',
            autoSync: jQuery('input#autoSync').is(':checked') ? '1' : '0',
            autoSyncIntvl: jQuery('#autoSyncIntvl').val(),
            edit: '',
            last_sync_date: '',
            is_mapping_update: '',
            target_country: jQuery('#feed_target_country').find(":selected").val(),
            customer_subscription_id: "<?php echo esc_attr($subscriptionId) ?>",
            tiktok_business_account: jQuery('#tiktok_business_id').find(":selected").val(),
            product_selection : product_selection,
            productVal : productVal,
            conditionVal : conditionVal,
            valueVal : valueVal,
            conv_onboarding_nonce: conv_onboarding_nonce
        }
        
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () {
                //managecreateFeedLoader('show')
                jQuery('#createFeed').prop('disabled', true)
                jQuery('.applyFilter').prop('disabled', true)
                jQuery('#createFeed > span').removeClass('d-none')
                
            },
            error: function (err, status) {               
            },
            success: function (response) {
                //managecreateFeedLoader('hide')
                jQuery('#createFeed').prop('disabled', false)
                jQuery('.applyFilter').prop('disabled', false)
                jQuery('#createFeed > span').addClass('d-none')
                if (response.id) {
                    if(product_selection == 'specific_product') {
                        setTimeout(function () {
                            window.location.replace("<?php echo esc_url($site_url . 'product_list&id='); ?>" + response.id);
                        }, 100);
                    } else {
                        var totalProduct = jQuery('.totalCount').text();
                        if(product_selection == 'filter_product') {
                            totalProduct = jQuery('.totalCountFilter').text();
                        }
                        jQuery('#conv_create_popup_txt_msg').text('You have successfully created the new Feed with '+ totalProduct + ' products') 
                        checkProgressBar()                                                 
                        openOverlayLoader('openshow');
                        setTimeout(function() {
                            openOverlayLoader('close');
                            jQuery('#conv_modal_create_popup').modal('show') 
                        }, 2000);
                    }                     
                                     
                } else if (response.errorType === 'tiktok') {
                    jQuery('.tiktok_catalog_id').empty();
                    jQuery('.tiktok_catalog_id').html(response.message);
                    jQuery('.tiktok_catalog_id').addClass('text-danger');
                } 
            }
        });
    }

    function saveChannel(Channel) {
        var selected_vals = {};
        var conv_options_type = [];
        var data = {};
        if(Channel == 'GMC') {
            var google_merchant_center_id = "<?php echo esc_attr($google_merchant_center_id) ?>";
            if (jQuery("#google_merchant_center_id").val() === '') {
                jQuery('.selection').find("[aria-labelledby='select2-google_merchant_center_id-container']").addClass('selectError');
                return false;
            }
            var update_site_domain = '';
            if(google_merchant_center_id != jQuery("#google_merchant_center_id").val()) {
                update_site_domain = 'update';
            }
            conv_options_type = ["eeoptions", "eeapidata", "middleware"];
            selected_vals["subscription_id"] = "<?php echo esc_attr($subscriptionId) ?>";
            selected_vals["google_merchant_center_id"] = jQuery("#google_merchant_center_id").val();
            selected_vals["google_merchant_id"] = jQuery("#google_merchant_center_id").val();
            selected_vals["merchant_id"] = jQuery('#google_merchant_center_id').find(':selected').data('merchant_id');
            selected_vals["website_url"] = "<?php echo esc_url(get_site_url()); ?>";
            var google_ads_id = jQuery('#gmc_google_ads_id').val();
            if(google_ads_id !== ''){
                selected_vals["google_ads_id"] = google_ads_id;
                selected_vals["ga_GMC"] = '1';
            }
            data = {
                action: "conv_save_pixel_data", 
                pix_sav_nonce: "<?php echo esc_attr(wp_create_nonce('pix_sav_nonce_val')); ?>",
                conv_options_data: selected_vals,
                conv_options_type: conv_options_type,
                update_site_domain: update_site_domain,
            }
        }
        
        // if(Channel == 'FB') {
        //     var facebook_data = {};
        //     facebook_data["fb_mail"] = jQuery('#fb_mail').val();
        //     facebook_data["fb_business_id"] = jQuery('#fb_business_id').find(":selected").val();
        //     facebook_data["fb_catalog_id"] = jQuery('#fb_catalog_id').find(":selected").val();
        //     selected_vals["facebook_setting"] = facebook_data;
        //     if (facebook_data["fb_business_id"] === '') {
        //         jQuery('.selection').find("[aria-labelledby='select2-fb_business_id-container']").addClass('selectError');
        //         return false;
        //     }
        //     if (facebook_data["fb_catalog_id"] === '') {
        //         jQuery('.selection').find("[aria-labelledby='select2-fb_catalog_id-container']").addClass('selectError');
        //         return false;
        //     }
        //     conv_options_type = ["eeoptions", "middleware", "facebookmiddleware", "facebookcatalog"];
        //     data = {
        //         action: "conv_save_pixel_data", 
        //         pix_sav_nonce: "<?php //echo wp_create_nonce('pix_sav_nonce_val'); ?>",
        //         conv_options_data: selected_vals,
        //         conv_options_type: conv_options_type,
        //         customer_subscription_id: "<?php //echo $subId ?>",
        //     }
        // }
        if(Channel == 'Tiktok') {
            var tiktok_data = {};
            tiktok_data["tiktok_mail"] = jQuery('#tiktok_mail').val();
            tiktok_data["tiktok_user_id"] = jQuery('#tiktok_user_id').val();
            tiktok_data["tiktok_business_id"] = jQuery('#tiktok_business_id').find(":selected").val();
            tiktok_data["tiktok_business_name"] = jQuery('#tiktok_business_id').find(":selected").data("business_name")
            selected_vals["tiktok_setting"] = tiktok_data;
            if (tiktok_data["tiktok_business_id"] === '') {
                jQuery('.selection').find("[aria-labelledby='select2-tiktok_business_id-container']").addClass('selectError');
                return false;
            }
            var catalogData = {};
            jQuery('.catalogId').each(function () {
                catalogData[jQuery(this).find(":selected").data("catalog_country")] = [jQuery(this).find(":selected").val(), jQuery(this).find(":selected").data("catalog_name"), jQuery(this).find(":selected").data("catalog_country")];
            })
            conv_options_type = ["eeoptions", "middleware", "tiktokmiddleware", "tiktokcatalog"];
            data = {
                action: "conv_save_pixel_data", 
                pix_sav_nonce: "<?php echo esc_attr(wp_create_nonce('pix_sav_nonce_val')); ?>",
                conv_options_data: selected_vals,
                conv_options_type: conv_options_type,
                customer_subscription_id: "<?php echo esc_attr($subscriptionId) ?>",
                conv_catalogData: catalogData,
            }
        }
        
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () {
               // openOverlayLoader('open'); 
                if(Channel == 'GMC'){
                    jQuery(".verifySite, .verifyDomain, .createNewGMC, .channelTabSave, .saveGMC, .tvc_google_signinbtn").css("pointer-events", "none");
                    jQuery('.saveGMC').prop('disabled', true)
                    jQuery('.saveGMC > span').removeClass('d-none')
                    jQuery('.gmc_account_id_step').removeClass('disable')                   
                }
                // if(Channel == 'FB'){
                //     jQuery('.saveFB').prop('disabled', true)
                //     jQuery('.saveFB').text('Saving...')
                // }
                if(Channel == 'Tiktok'){
                    jQuery(".signIn, #tiktok_business_id, .channelTabSave, .saveTiktok").css("pointer-events", "none");
                    jQuery('.saveTiktok').prop('disabled', true)
                    jQuery('.saveTiktok > span').removeClass('d-none')
                    jQuery('.tiktok-catalog-step').removeClass('disable')
                    jQuery('.tiktok-step').removeClass('disable')
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
                        var html2 ='<span class="material-symbols-outlined" style="font-size: 18px;">autorenew</span>Claim Now';
                        jQuery('.verifySite').removeClass('bg-success_')
                        jQuery('.verifySite').addClass('bg-warnings')
                        jQuery('.verifySite').html(html)
                        jQuery('.verifyDomain').removeClass('bg-success_')                    
                        jQuery('.verifyDomain').addClass('bg-warnings')
                        jQuery('.verifyDomain').html(html2)
                    }
                }
                // if(Channel == 'FB'){
                //     jQuery('#fb_business_id').prop('disabled', true)
                //     jQuery('#fb_catalog_id').prop('disabled', true)
                //     jQuery('.getfbList').removeClass('d-none')
                //     jQuery('.saveFB').text('Save')
                // }
                if(Channel == 'Tiktok'){
                    jQuery(".signIn, #tiktok_business_id, .channelTabSave, .saveTiktok").css("pointer-events", "auto");
                    jQuery('.saveTiktok > span').addClass('d-none')
                    jQuery('#tiktok_business_id').attr('disabled', true) 
                    jQuery('.gettitokList').removeClass('d-none')
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
        jQuery('[data-bs-target="#profile"]').trigger('click')            
    })
    
    function manageTiktokLoader(display = "show") {
        if(display == "show") {
            jQuery('#tiktokSetting_loader').removeClass('d-none')
            jQuery(".signIn, #tiktok_business_id, .channelTabSave, .saveTiktok").css("pointer-events", "none");
        } else {
            jQuery(".signIn, #tiktok_business_id, .channelTabSave, .saveTiktok").css("pointer-events", "auto");
            jQuery('#tiktokSetting_loader').addClass('d-none')
        }
    }
    // function manageFbLoader(display = "show") {
    //     if(display == "show") {
    //         jQuery('#fbSetting_loader').removeClass('d-none')
    //         jQuery(".facebookLogin, #fb_business_id, #fb_catalog_id, .channelTabSave, .saveFB").css("pointer-events", "none");
    //     } else {
    //         jQuery('#fbSetting_loader').addClass('d-none')
    //         jQuery(".facebookLogin, #fb_business_id, #fb_catalog_id, .channelTabSave, .saveFB").css("pointer-events", "auto");
    //     }
    // }
    function managecreateFeedLoader(display = "show") {
        if(display == "show") {
            jQuery('#createFeed_loader').removeClass('d-none')
            jQuery("#createFeed").css("pointer-events", "none");
        } else {
            jQuery('#createFeed_loader').addClass('d-none')
            jQuery("#createFeed").css("pointer-events", "auto");
        }
    }
    function manageattributeLoader(display = "show") {
        if(display == "show") {
            jQuery('#attribute_loader').removeClass('d-none')
            jQuery("#attr_mapping_save").css("pointer-events", "none");
        } else {
            jQuery('#attribute_loader').addClass('d-none')
            jQuery("#attr_mapping_save").css("pointer-events", "auto");
        }
    }
    function checkProgressBar(channel = "") {
        var get = "<?php echo isset($_GET['wizard_channel']) ? esc_attr(sanitize_text_field(wp_unslash($_GET['wizard_channel']))) : '' ?>";
        var data = {
            action: "get_category_for_filter",
            type: "getProgressCount",
            get_category_for_filter: "<?php echo esc_html(wp_create_nonce('get_category_for_filter-nonce')); ?>"
        };
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () { 
            },
            success: function (response) { 
                var pixelprogressbarclass = 0;
                jQuery('.progress-bar').removeClass('w-'+pixelprogressbarclass)
                if(response.feed_count == 1) {
                    pixelprogressbarclass = parseInt(pixelprogressbarclass) + 33;
                    jQuery('.create-feed').text("check_circle")
                    jQuery('.create-feed').removeClass("text-warning")
                    jQuery('.create-feed').addClass("text-success")
                }
                if(response.isAttrMapped == 1) {
                    pixelprogressbarclass = parseInt(pixelprogressbarclass) + 33;
                    jQuery('.product-attribute').text("check_circle")
                    jQuery('.product-attribute').removeClass("text-warning")
                    jQuery('.product-attribute').addClass("text-success")
                }
                if(response.is_channel_connected == 1) {
                    jQuery('.channelTabSave').prop('disabled', false)
                    pixelprogressbarclass = parseInt(pixelprogressbarclass) + 33;
                    jQuery('.add-channel').text("check_circle")
                    jQuery('.add-channel').removeClass("text-warning")
                    jQuery('.add-channel').addClass("text-success")
                }                
                jQuery('.progress-bar').addClass('w-'+pixelprogressbarclass)
                if(response.isAttrMapped == 1 && response.is_channel_connected == 1 && get == '' && channel == '') {
                    jQuery('[data-bs-target="#contact"]').trigger('click')
                }
                if(response.isAttrMapped == 0 && response.is_channel_connected == 1 && get == '' && channel == '') {
                    jQuery('[data-bs-target="#profile"]').trigger('click')
                }           
            }
        }); 
    }
    jQuery(document).on('change', '.additinal_attr', function() {
        $fixed_att_select_list = ["gender", "age_group", "condition"];
        var attr = jQuery(this).val();
        if(jQuery.inArray( attr, $fixed_att_select_list ) !== -1){
            $option1 = '<option value="">Please Select Attribute</option>';
            if(attr == 'gender') {
                $option1 += '<option value="male">Male</option><option value="female">Female</option><option value="unisex">Unisex</option>'
            }
            if(attr == 'condition') {
                $option1 += '<option value="new">New</option><option value="refurbished">Refurbished</option><option value="used">Used</option>'
            }
            if(attr == 'age_group') {
                $option1 += '<option value="newborn">Newborn</option><option value="infant">Infant</option><option value="toddler">Toddler</option><option value="kids">Kids</option><option value="adult">Adult</option>'
            }
            jQuery(this).parent().next().find('.additional_attr_value').html($option1)
        } else {
            var wooCommerceAttributes = <?php echo wp_json_encode($wooCommerceAttributes); ?>;
            $option1 = '<option value="">Please Select Attribute</option>';
            jQuery.each(wooCommerceAttributes, function (index, value) {
                $option1 += '<option value="'+value.field+'">'+value.field+'</option>'
            });
            jQuery(this).parent().next().find('.additional_attr_value').html($option1)
        }
    })    
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