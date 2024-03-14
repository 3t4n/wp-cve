<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$TVC_Admin_Helper = new TVC_Admin_Helper();
$TVC_Admin_Helper->need_auto_update_db();
$TVC_Admin_Helper->get_feed_status();
$feed_data = $TVC_Admin_Helper->ee_get_results('ee_product_feed');
$count_feed = count($feed_data);
$subscriptionId = $TVC_Admin_Helper->get_subscriptionId();
$site_url = "admin.php?page=conversios-google-shopping-feed&tab=";
$site_url_pmax = "admin.php?page=conversios-pmax";
$customApiObj = new CustomApi();
$googledetail = $customApiObj->getGoogleAnalyticDetail($subscriptionId);
$googleDetail = $googledetail->data;
$conv_data['subscription_id'] = $googleDetail->id;
$conv_data['access_token'] = base64_encode(sanitize_text_field($googleDetail->access_token));
$conv_data['refresh_token'] = base64_encode(sanitize_text_field($googleDetail->refresh_token));
$conv_data = $TVC_Admin_Helper->get_store_data();
$conv_additional_data = $TVC_Admin_Helper->get_ee_additional_data();
$google_detail = $TVC_Admin_Helper->get_ee_options_data();
$total_products = (new WP_Query(['post_type' => 'product', 'post_status' => 'publish']))->found_posts;
$ee_options = $TVC_Admin_Helper->get_ee_options_settings();
$google_merchant_center_id = '';
if (isset($ee_options['google_merchant_id']) === TRUE && $ee_options['google_merchant_id'] !== '') {
    $google_merchant_center_id = $ee_options['google_merchant_id'];
}

$tiktok_business_account = '';
if (isset($ee_options['tiktok_setting']['tiktok_business_id']) === TRUE && $ee_options['tiktok_setting']['tiktok_business_id'] !== '') {
    $tiktok_business_account = $ee_options['tiktok_setting']['tiktok_business_id'];
}

if ($google_merchant_center_id === '' && $tiktok_business_account === '') {
    if ($ee_options['subscription_id'] % 2 == 0) {
        wp_safe_redirect("admin.php?page=conversios&wizard=productFeedEven"); //Even
        exit;
    } else {
        wp_safe_redirect("admin.php?page=conversios&wizard=productFeedOdd"); //Odd
        exit;
    }
    exit;
}

$google_ads_id = '';
$currency_symbol = '';
if (isset($ee_options['google_ads_id']) === TRUE && $ee_options['google_ads_id'] !== '') {
    $google_ads_id = $ee_options['google_ads_id'];
    $PMax_Helper = new Conversios_PMax_Helper();
    $currency_code_rs = $PMax_Helper->get_campaign_currency_code($google_ads_id);
    if(isset($currency_code_rs->data->currencyCode)){
      $currency_code = $currency_code_rs->data->currencyCode;
      $currency_symbol = $TVC_Admin_Helper->get_currency_symbols($currency_code);
    }
    
}

$googleConnect_url = '';
$getCountris = @file_get_contents(ENHANCAD_PLUGIN_DIR."includes/setup/json/countries.json");
$contData = json_decode($getCountris);
$data = unserialize(get_option('ee_options'));

?>
<style>
    .errorInput {
        border: 1.3px solid #ef1717 !important ;
        padding: 0px;
        border-radius: 6px;
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
<div class="container-fluid conv-light-grey-bg pt-4 ps-4">
    <div class="row ps-4 pe-4">
        <div class="convfixedcontainermid m-0 p-0">
            <div class="conv-heading-box">
                <h5 class="fs-20">
                    <?php esc_html_e("Feed Management", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </h5>
                <span class="fw-400 fs-14 text-secondary">
                    <?php
                    printf(
                          /* translators: %s: Total number of product */
                          esc_html__( 'You have total %s products in your WooCommerce store', "enhanced-e-commerce-for-woocommerce-store" ),
                          esc_html(number_format_i18n($total_products))
                    );
                    ?>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid conv-light-grey-bg p-4 pb-2">
    <div id="loadingbar_blue" class="progress-materializecss d-none ps-2 pe-2">
        <div class="indeterminate"></div>
    </div>
    <nav class="navbar navbar-light bg-white shadow-sm" style="border-top-left-radius:8px;border-top-right-radius:8px;">            
        <div class="col-12 col-md-12 col-sm-12">   
            <div class="row">         
                <div class="col-8 col-md-8 col-sm-8 ps-3">
                    <input type="search" class="form-control border from-control-width empty" placeholder="Search..."
                            aria-label="Search" name="search_feed" id="search_feed" aria-controls="feed_list_table">
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <?php if (isset($ee_options['google_merchant_id']) && $ee_options['google_merchant_id'] !== '') { 
                        if (isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] == '') {
                        $googleConnect_url = $TVC_Admin_Helper->get_custom_connect_url_subpage(admin_url() . 'admin.php?page=conversios-google-shopping-feed', "gadssettings"). "&amp;Campaign=Campaign";
                    ?>
                    <button
                            class="signinWithGoogle btn btn-soft-primary fs-14 me-2 disabled campaignClass"
                            title="Select Feed from below to create performance max campaign in Google Ads." style="pointer-events: auto !important">
                            <?php esc_html_e("Create Campaign", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </button>                                                                                                                
                    <?php } else { ?>
                        <button
                            class="createCampaign btn btn-soft-primary fs-14 me-2 disabled campaignClass"
                            title="Select Feed from below to create performance max campaign in Google Ads." style="pointer-events: auto !important">
                            <?php esc_html_e("Create Campaign", "enhanced-e-commerce-for-woocommerce-store"); ?> 
                        </button>
                    <?php } 
                    } ?>               
                    <button class="btn btn-soft-primary fs-14 me-2" name="create_new_feed" id="create_new_feed">
                        <?php esc_html_e("Create New Feed", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </div>
            </div>            
        </div>        
    </nav>
    <input type="hidden" id="feedCount" name="feedCount"
                value="<?php echo !empty($feed_data) ? count($feed_data) : 0; ?>">
    <div class="table-responsive shadow-sm" style="border-bottom-left-radius:8px;border-bottom-right-radius:8px;">
        <table class="table" id="feed_list_table" style="width:100%">
            <thead>
                <tr>
                    <th scope="col" class="text-dark text-start">
                        <div class="form-check form-check-custom">
                            <input class="form-check-input checkbox fs-17" type="checkbox" name="selectAll" id="selectAll" value="selectAll">
                        </div>
                    </th>
                    <th scope="col" class="text-dark text-start" style="width:25%">
                        <?php esc_html_e("FEED NAME", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-center" style="width:10%">
                        <?php esc_html_e("TARGET COUNTRY", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-start" style="width:15%">
                        <?php esc_html_e("CHANNELS", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-center" style="width:10%">
                        <?php esc_html_e("TOTAL PRODUCT", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-center" style="width:12%">
                        <?php esc_html_e("AUTO SYNC", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-center" style="width:10%">
                        <?php esc_html_e("CREATED", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-center" style="width:10%">
                        <?php esc_html_e("LAST SYNC", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-center" style="width:10%">
                        <?php esc_html_e("NEXT SYNC", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-center" style="width:5%">
                        <?php esc_html_e("STATUS", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                    <th scope="col" class="text-dark text-center" style="width:3%">
                        <?php esc_html_e("MORE", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </th>
                </tr>
            </thead>
            <tbody id="table-body" class="table-body">
                <?php
                    $feedIdArr = []; 
                    if (empty($feed_data) === FALSE) {
                    foreach ($feed_data as $value) {
                        $channel_id = explode(',', $value->channel_ids);
                        if($value->status == 'Synced') {
                            array_push($feedIdArr, $value->id);
                        }
                        
                        ?>
                        <tr class="height">
                            <td class="align-middle text-start">
                                <div class="form-check form-check-custom">
                                    <input class="form-check-input checkbox_feed fs-17" <?php echo $value->status == 'Synced' ? '' : 'disabled="disabled"' ?> type="checkbox" name="" id="checkFeed_<?php echo esc_attr($value->id); ?>" value="<?php echo esc_attr($value->id); ?>">
                                </div>
                            </td>
                            <td class="align-middle text-start">
                                <?php if ($value->is_delete === '1') { ?>
                                    <span style="cursor: no-drop;">
                                        <?php echo esc_html($value->feed_name); ?>
                                    </span>
                                <?php } else { ?>
                                    <span>
                                        <a title="Go to feed wise product list"
                                            href="<?php echo esc_url($site_url . 'product_list&id=' . $value->id); ?>"><?php echo esc_html($value->feed_name); ?></a>
                                    </span>
                                <?php } ?>

                            </td>
                            <td class="align-middle text-center">
                                <?php
                                foreach ($contData as $key => $country) {
                                    if ($value->target_country === $country->code) { ?>
                                        <?php echo esc_html($country->name); ?>
                                    <?php }
                                }
                                ?>
                            </td>
                            <td class="align-middle text-start">
                                <?php foreach ($channel_id as $val) {
                                    if ($val === '1') { ?>
                                        <img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/google_channel_logo.png'); ?>" />
                                    <?php } elseif ($val === '2') { ?>
                                        <img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/fb_channel_logo.png'); ?>" />
                                    <?php } elseif ($val === '3') { ?>
                                        <img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/tiktok_channel_logo.png'); ?>" />
                                    <?php }
                                } ?>
                            </td>
                            <td class="align-middle text-center">
                                <?php echo esc_html(number_format_i18n($value->total_product ? $value->total_product : 0)); ?>
                            </td>
                            <td class="align-middle text-center">
                                <span class="dot <?php echo $value->auto_schedule === '1' ? 'dot-green' : 'dot-red'; ?>"></span>
                                <span>
                                    <?php echo $value->auto_schedule === '1' ? 'Yes' : 'No'; ?>
                                </span>
                                <p class="fs-10 mb-0">
                                    <?php echo $value->auto_sync_interval !== 0 && $value->auto_schedule === '1' ? 'Every ' . esc_html($value->auto_sync_interval) . ' Days' : ' '; ?>
                                </p>
                            </td>
                            <td class="align-middle text-center" data-sort='" <?php echo esc_html(strtotime($value->created_date)) ?> "'>
                                <span>
                                    <?php echo esc_html(date_format(date_create($value->created_date), "d M Y")); ?>
                                </span>
                                <p class="fs-10 mb-0">
                                    <?php echo esc_html(date_format(date_create($value->created_date), "H:i a")); ?>
                                </p>
                            </td>
                            <td class="align-middle text-center" data-sort='" <?php echo esc_html(strtotime($value->last_sync_date)) ?> "'>
                                <span>
                                    <?php echo $value->last_sync_date && $value->last_sync_date != '0000-00-00 00:00:00' ? esc_html(date_format(date_create($value->last_sync_date), "d M Y")) : 'NA'; ?>
                                </span>
                                <p class="fs-10 mb-0">
                                    <?php echo $value->last_sync_date && $value->last_sync_date != '0000-00-00 00:00:00' ? esc_html(date_format(date_create($value->last_sync_date), "H:i a")) : ''; ?>
                                </p>
                            </td>
                            <td class="align-middl text-center"
                                data-sort='" <?php echo esc_html(strtotime($value->next_schedule_date)) ?> "'>
                                <span>
                                    <?php echo $value->next_schedule_date && $value->next_schedule_date != '0000-00-00 00:00:00' ? esc_html(date_format(date_create($value->next_schedule_date), "d M Y")) : 'NA'; ?>
                                </span>
                                <p class="fs-10 mb-0">
                                    <?php echo $value->next_schedule_date && $value->next_schedule_date != '0000-00-00 00:00:00' ? esc_html(date_format(date_create($value->next_schedule_date), "H:i a")) : ''; ?>
                                </p>
                            </td>
                            <td class="align-middle text-center">
                                <?php if ($value->is_delete === '1') { ?>
                                    <span class="badgebox rounded-pill  fs-10 deleted">
                                        Deleted
                                    </span>
                                <?php } else {
                                    $draft = 0;
                                    $inprogress = 0;
                                    $synced = 0;
                                    $failed = 0;
                                    switch ($value->status) {
                                        case 'Draft':
                                            $draft++;
                                            break;

                                        case 'In Progress':
                                            $inprogress++;
                                            break;

                                        case 'Synced':
                                            $synced++;
                                            break;

                                        case 'Failed':
                                            $failed++;
                                            break;
                                    }

                                    switch ($value->tiktok_status) {
                                        case 'Draft':
                                            $draft++;
                                            break;

                                        case 'In Progress':
                                            $inprogress++;
                                            break;

                                        case 'Synced':
                                            $synced++;
                                            break;

                                        case 'Failed':
                                            $failed++;
                                            break;
                                    }

                                    if ($draft !== 0) { ?>
                                        <div class="badgebox draft" data-bs-toggle="popover" data-bs-placement="left"
                                            data-bs-content="Left popover" data-bs-trigger="hover focus">
                                            <?php echo esc_html('Draft'); ?>
                                            <div class="count-badge" style="margin-top:-4px;color:#DCA310">
                                                <?php echo esc_html($draft) ?>
                                            </div>
                                        </div>
                                        <input type="hidden" class="draftGmcImg"
                                            value="<?php echo $value->status == 'Draft' ? "<img class='draft-status' src='" . esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/logos/google_channel_logo.png") . "' />" : '' ?>">
                                        <input type="hidden" class="draftTiktokImg"
                                            value="<?php echo $value->tiktok_status == 'Draft' ? "<img class='draft-status' src='" . esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/logos/tiktok_channel_logo.png") . "' />" : '' ?>">
                                    <?php }
                                    if ($inprogress !== 0) { ?>
                                        <div class="badgebox inprogress" data-bs-toggle="popover" data-bs-placement="left"
                                            data-bs-content="Left popover" data-bs-trigger="hover focus">
                                            <?php echo esc_html('In Progress'); ?>
                                            <div class="count-badge" style="margin-top:-4px;color:#209EE1">
                                                <?php echo esc_html($inprogress) ?>
                                            </div>
                                        </div>
                                        <input type="hidden" class="inprogressGmcImg"
                                            value="<?php echo $value->status == 'In Progress' ? "<img class='inprogress-status' src='" . esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/logos/google_channel_logo.png") . "' />" : '' ?>">
                                        <input type="hidden" class="inprogressTiktokImg"
                                            value="<?php echo $value->tiktok_status == 'In Progress' ? "<img class='inprogress-status' src='" . esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/logos/tiktok_channel_logo.png") . "' />" : '' ?>">
                                    <?php }
                                    if ($synced !== 0) { ?>
                                        <div class="badgebox synced" data-bs-toggle="popover" data-bs-placement="left"
                                            data-bs-content="Left popover" data-bs-trigger="hover focus">
                                            <?php echo esc_html('Synced'); ?>
                                            <div class="count-badge" style="margin-top:-4px;color:#09bd83">
                                                <?php echo esc_html($synced) ?>
                                            </div>
                                        </div>
                                        <input type="hidden" class="syncedGmcImg"
                                            value="<?php echo $value->status == 'Synced' ? "<img class='synced-status' src='" . esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/logos/google_channel_logo.png") . "' />" : '' ?>">
                                        <input type="hidden" class="syncedTiktokImg"
                                            value="<?php echo $value->tiktok_status == 'Synced' ? "<img class='synced-status' src='" . esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/logos/tiktok_channel_logo.png") . "' />" : '' ?>">
                                    <?php }
                                    if ($failed !== 0) { ?>
                                        <div class="badgebox failed" data-bs-toggle="popover" data-bs-placement="left"
                                            data-bs-content="Left popover" data-bs-trigger="hover focus">
                                            <?php echo esc_html('Failed'); ?>
                                            <div class="count-badge" style="margin-top:-4px;color:#f43e56">
                                                <?php echo esc_html($failed) ?>
                                            </div>
                                        </div>
                                        <input type="hidden" class="failedGmcImg"
                                            value="<?php echo $value->status == 'Failed' ? "<img class='failed-status' src='" . esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/logos/google_channel_logo.png") . "' />" : '' ?>">
                                        <input type="hidden" class="failedTiktokImg"
                                            value="<?php echo $value->tiktok_status == 'Failed' ? "<img class='failed-status' src='" . esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/logos/tiktok_channel_logo.png") . "' />" : '' ?>">
                                    <?php }
                                } //end if ?>
                            </td>
                            <td class="align-middle">
                                <div class="dropdown position-static">
                                    <?php if ($value->is_delete === '1') { ?>
                                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                            style="cursor: no-drop;">
                                            <span class="material-symbols-outlined">
                                                more_horiz
                                            </span>
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="material-symbols-outlined">
                                                more_horiz
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark bg-white">
                                            <li class="mb-0 pointer"><a class="dropdown-item text-secondary border-bottom fs-12"
                                                    onclick="editFeed(<?php echo esc_html($value->id); ?>)">Edit</a>
                                            </li>
                                            <li class="mb-0 pointer"><a class="dropdown-item text-secondary border-bottom fs-12 "
                                                    onclick="duplicateFeed(<?php echo esc_html($value->id); ?>)">Duplicate</a>
                                            </li>
                                            <li class="mb-0 pointer"><a class="dropdown-item text-secondary fs-12"
                                                    onclick="deleteFeed(<?php echo esc_html($value->id); ?>)">Delete</a></li>
                                        </ul>
                                    <?php } //end if
                                            ?>
                                </div>
                            </td>
                        </tr>
                    <?php } //end foreach
                } //end if
                $feedIdString = implode(",",$feedIdArr);
                ?>
            </tbody>
        </table>
        <input type="hidden" id="selecetdCampaign" name="selecetdCampaign" value="">
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="convCreateFeedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content ">
            <form id="feedForm" onfocus="this.className='focused'">
                <div id="loadingbar_blue_modal" class="progress-materializecss d-none ps-2 pe-2" style="width:98%">
                    <div class="indeterminate"></div>
                </div>
                <div class="modal-header bg-light p-2 ps-4">
                    <h5 class="modal-title fs-16 fw-500" id="feedType">
                        <?php esc_html_e("Create New Feed", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="jQuery('#feedForm')[0].reset()"></button>
                </div>
                <div class="modal-body ps-4 pt-0">
                    <div class="mb-4">
                        <label for="feed_name" class="col-form-label text-dark fs-14 fw-500">
                            <?php esc_html_e("Feed Name", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </label>
                        <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip" data-bs-placement="right"
                            title="Add a name to your feed for your reference, for example, 'April end-of-season sales' or 'Black Friday sales for the USA'.">
                            info
                        </span>
                        <input type="text" class="form-control fs-14" name="feedName" id="feedName"
                            placeholder="e.g. New Summer Collection">
                    </div>
                    <div class="mb-2 row">
                        <div class="col-5">
                            <label for="auto_sync" class="col-form-label text-dark fs-14 fw-500">
                                <?php esc_html_e("Auto Sync", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Turn on this feature to schedule an automated product feed to keep your products up to date with the changes made in the products. You can come and change this any time.">
                                info
                            </span>
                        </div>
                        <div class="form-check form-switch col-7 mt-0 fs-5">
                            <input class="form-check-input" type="checkbox" name="autoSync" id="autoSync" checked>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-5">
                            <label for="auto_sync_interval" class="col-form-label text-dark fs-14 fw-500">
                                <?php esc_html_e("Auto Sync Interval", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Set the number of days to schedule the next auto-sync for the products in this feed. You can come and change this any time.">
                                info
                            </span>
                        </div>
                        <div class="col-7">
                            <input type="text" class="form-control-sm fs-14 " readonly="readonly" name="autoSyncIntvl" id="autoSyncIntvl" size="3" min="1"
                                onkeypress="return ( event.charCode === 8 || event.charCode === 0 || event.charCode === 13 || event.charCode === 96) ? null : event.charCode >= 48 && event.charCode <= 57"
                                oninput="removeZero();"
                                value="25">
                            <label for="" class="col-form-label fs-14 fw-400">
                                <?php esc_html_e("Days", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                            <span>
                                <a target="_blank" href="https://www.conversios.io/wordpress/product-feed-manager-for-woocommerce-pricing/?utm_source=app_wooPFM&utm_medium=BUSINESS&utm_campaign=Pricing"><b> Upgrade To Pro</b></a>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-5">
                            <label for="target_country" class="col-form-label text-dark fs-14 fw-500" name="">
                                <?php esc_html_e("Target Country", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Specify the target country for your product feed. Select the country where you intend to promote and sell your products.">
                                info
                            </span>
                        </div>
                        <div class="col-7">
                            <select class="select2 form-select form-select-sm mb-3" aria-label="form-select-sm example"
                                style="width: 100%" name="target_country" id="target_country">
                                <option value="">Select Country</option>
                                <?php
                                $selecetdCountry = $conv_data['user_country'];
                                foreach ($contData as $key => $value) {
                                    ?>
                                    <option value="<?php echo esc_attr($value->code) ?>" <?php echo $selecetdCountry === $value->code ? 'selected = "selecetd"' : '' ?>><?php echo esc_html($value->name) ?></option>"
                                    <?php
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="auto_sync_interval" class="col-form-label text-dark fs-14 fw-500">
                            <?php esc_html_e("Select Channel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </label>
                        <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip" data-bs-placement="right"
                            title="Below is the list of channels that you have linked for product feed. Please note you will not be able to make any changes in the selected channels once product feed process is done.">
                            info
                        </span>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-check-custom">
                            <input class="form-check-input check-height fs-14 errorChannel" type="checkbox"
                                value="<?php printf( esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ), esc_html( $google_merchant_center_id ) ); ?>" id="gmc_id" name="gmc_id"
                                <?php echo $google_merchant_center_id !== '' ? "checked" : 'disabled' ?>>
                            <label for="" class="col-form-label fs-14 pt-0 text-dark fw-500">
                                <?php esc_html_e("Google Merchant Center Account :", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                            <label class="col-form-label fs-14 pt-0 fw-400">
                                <?php
                                printf(
                                      esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ),
                                      esc_html( $google_merchant_center_id )
                                );
                                ?>
                            </label>
                        </div>
                        <div class="form-check form-check-custom">
                            <input class="form-check-input check-height fs-14 errorChannel" type="checkbox" value=""
                                id="tiktok_id" name="tiktok_id" <?php echo $tiktok_business_account !== '' ? "checked" : 'disabled' ?>>
                            <label for="" class="col-form-label fs-14 pt-0 text-dark fw-500">
                                <?php esc_html_e("TikTok Catalog Id :", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                            <label class="col-form-label fs-14 pt-0 fw-400 tiktok_catalog_id">

                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <input type="hidden" id="edit" name="edit">
                    <input type="hidden" value="<?php echo esc_attr($conv_data['user_domain']); ?>" class="fromfiled"
                        name="url" id="url" placeholder="Enter Website">
                    <input type="hidden" id="is_mapping_update" name="is_mapping_update" value="">
                    <input type="hidden" id="last_sync_date" name="last_sync_date" value="">
                    <button type="button" class="btn btn-light btn-sm border" data-bs-dismiss="modal"
                        onclick="jQuery('#feedForm')[0].reset()">
                        <?php esc_html_e("Cancel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                    <button type="button" class="btn btn-soft-primary btn-sm" id="submitFeed">
                        <?php esc_html_e("Create and Next", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="convCampaignModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content ">
            <form id="CampaignForm" onfocus="this.className='focused'">
                <div id="loadingbar_blue_modal_campign" class="progress-materializecss d-none ps-2 pe-2" style="width:98%">
                    <div class="indeterminate"></div>
                </div>
                <div class="modal-header bg-light p-2 ps-4">
                    <h5 class="modal-title fs-16 fw-500" id="">
                        <?php esc_html_e("Create Pmax Campaign ", "enhanced-e-commerce-for-woocommerce-store"); ?>                        
                    </h5>
                    &nbsp; <h5 class="modal-title fs-16"><?php echo $google_ads_id !== '' ? '- Google Ads Id - '.esc_html($google_ads_id) : '' ?></h5>
                    &nbsp;<span class="otherError text-danger"></span>                    
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="jQuery('#CampaignForm')[0].reset(); jQuery('.otherError').html('')"></button>                       
                </div>
                <div class="modal-body ps-4 pt-0">
                <div class="row"><span><b>Note:</b>  Campaign creation will fail if the feeds selected has more than 1000 products.</span></div> 
                    <div class="mb-4">
                        <label for="Campaign_name" class="col-form-label text-dark fs-14 fw-500">
                            <?php esc_html_e("Campaign Name", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <span class="text-danger fs-16">*</span>
                        </label>
                        <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip" data-bs-placement="right"
                            title="Add a name to your Campaign for your reference, for example, 'April end-of-season sales' or 'Black Friday sales for the USA'.">
                            info
                        </span>
                        <input type="text" class="form-control fs-14" name="campaignName" id="campaignName"
                            placeholder="e.g. New Summer Collection" style="width: 50%">
                    </div>
                    <div class="mb-2 row">
                        <div class="col-6">
                            <label for="daily_budget" class="col-form-label text-dark fs-14 fw-500">
                                <?php esc_html_e("Daily Budget", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                (<span class="ga_currency_symbols"><?php echo esc_html($currency_symbol); ?></span>)
                                <span class="text-danger fs-16">*</span>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Daily Budget for your Campaign">
                                info
                            </span>
                            <input type="text" class="form-control fs-14" name="daily_budget" id="daily_budget"
                            placeholder="Enter your budget">
                        </div>
                        <div class="col-6">
                            <label for="target_country" class="col-form-label text-dark fs-14 fw-500" name="">
                                <?php esc_html_e("Target Country", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <span class="text-danger fs-16">*</span>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Specify the target country for your Campaign. Select the country where you intend to promote and sell your products.">
                                info
                            </span>
                            <select class="select2 form-select form-select-sm mb-3" aria-label="form-select-sm example"
                                style="width: 100%" name="target_country_campaign" id="target_country_campaign">
                                <option value="">Select Country</option>
                                <?php
                                $selecetdCountry = $conv_data['user_country'];
                                foreach ($contData as $key => $value) {
                                    ?>
                                    <option value="<?php echo esc_attr($value->code) ?>" <?php echo $selecetdCountry === $value->code ? 'selected = "selecetd"' : '' ?>><?php echo esc_html($value->name) ?></option>"
                                    <?php
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-6">
                            <label for="target_roas" class="col-form-label text-dark fs-14 fw-500">
                                <?php esc_html_e("Target ROAS (%)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Target ROAS">
                                info
                            </span>
                            <input type="text" class="form-control fs-14" name="target_roas" id="target_roas"
                            placeholder="Add Number">
                        </div>                        
                    </div>
                    <div class="mb-2 row">
                        <div class="col-6">
                            <label for="start_date" class="col-form-label text-dark fs-14 fw-500">
                                <?php esc_html_e("Start Date", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <span class="text-danger fs-16">*</span>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Campaign Start Date">
                                info
                            </span>
                            <input type="date" class="form-control fs-14 datepicker hasDatepicker" name="start_date" id="start_date"
                            placeholder="">
                        </div>
                        <div class="col-6">
                            <label for="end_date" class="col-form-label text-dark fs-14 fw-500">
                                <?php esc_html_e("End Date", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <span class="text-danger fs-16">*</span>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
                                data-bs-placement="right"
                                title="Campaign End Date">
                                info
                            </span><span class="endDateError text-danger"></span>
                            <input type="date" class="form-control fs-14 datepicker hasDatepicker" name="end_date" id="end_date"
                            placeholder="">
                        </div>                        
                    </div>
                    <div class="mb-2 row">
                        <div class="col-6">
                            <label for="status" class="col-form-label text-dark fs-14 fw-500">
                                <?php esc_html_e("Status", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </label>
                            <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip"
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
                    
                </div>
                <div class="modal-footer p-2">                    
                    <button type="button" class="btn btn-light btn-sm border" data-bs-dismiss="modal"
                        onclick="jQuery('#feedForm')[0].reset()">
                        <?php esc_html_e("Cancel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                    <button type="button" class="btn btn-soft-primary btn-sm" id="submitCampaign">
                        <?php esc_html_e("Create", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Error Save Modal -->
<div class="modal fade" id="conv_save_error_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">

            </div>
            <div class="modal-body text-center p-0">
                <img style="width:184px;"
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL.'/admin/images/logos/error_logo.png'); ?>">
                <h3 class="fw-normal pt-3">Error</h3>
                <span id="conv_save_error_txt" class="mb-1 lh-lg"></span>
            </div>
            <div class="modal-footer border-0 pb-4 mb-1">
                <button class="btn conv-yellow-bg m-auto text-white dismissErrorModal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Error Save Modal End -->
<!-- Success Save Modal -->
<div class="modal fade" id="conv_save_success_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">

            </div>
            <div class="modal-body text-center p-0">
                <img style="width:184px;"
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL.'/admin/images/logos/update_success_logo.png'); ?>">
                <h3 class="fw-normal pt-3"><?php esc_html_e("Updated Successfully", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                <span id="conv_save_success_txt" class="mb-1 lh-lg"></span>
            </div>
            <div class="modal-footer border-0 pb-4 mb-1 modalFooterSuccess" style="display:flex; justify-content: center">
                <button class="btn conv-blue-bg text-white dismissModal" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="pp-modal onbrd-popupwrp" id="tvc_google_signin" tabindex="-1" role="dialog">
    <div class="onbrdppmain" role="document">
        <div class="onbrdnpp-cntner acccretppcntnr">
            <div class="onbrdnpp-hdr">
                <div class="ppclsbtn clsbtntrgr"><img
                        src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/close-icon.png'); ?>"
                        alt="" /></div>
            </div>
            <div class="onbrdpp-body">
                <p>-- We recommend to use Chrome browser to configure the plugin if you face any
                    issues during setup. --</p>
                <div class="google_signin_sec_left">
                    <div class="google_connect_url google-btn">
                        <div class="google-icon-wrapper">
                            <img class="google-icon"
                                src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/g-logo.png'); ?>" />
                        </div>
                        <p class="btn-text">
                            <b><?php esc_html_e("Sign in with google", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                        </p>
                    </div>
                    <p><?php esc_html_e("Make sure you sign in with the google email account that has all privileges to access google analytics, google ads and google merchant center account that you want to configure for your store.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                </div>
                <div class="google_signin_sec_right">
                    <h5><?php esc_html_e("Why do I need to sign in with google?", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </h5>
                    <p><?php esc_html_e("When you sign in with Google, we ask for limited programmatic access for your accounts in order to automate below features for you:", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <p><strong><?php esc_html_e("1. Google Analytics:", "enhanced-e-commerce-for-woocommerce-store"); ?></strong><?php esc_html_e("To give you option to select GA accounts, to show actionable google analytics reports in plugin dashboard and to link your google ads account with google analytics account.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <p><strong><?php esc_html_e("2. Google Ads:", "enhanced-e-commerce-for-woocommerce-store"); ?></strong><?php esc_html_e("To automate dynamic remarketing, conversion and enhanced conversion tracking and to create performance campaigns if required.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <p><strong><?php esc_html_e("3. Google Merchant Center:", "enhanced-e-commerce-for-woocommerce-store"); ?></strong><?php esc_html_e("To automate product feed using content api and to set up your GMC account.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>

                </div>
                <!--badge consent & toggle -->
                <div style="margin-top: 10px;">
                    <label id="badge_label_check" for="conv_show_badge_onboardingCheck"
                        class="switch <?php echo empty($ee_options['conv_show_badge']) || esc_attr($ee_options['conv_show_badge']) == "no" ? "conv_default_cls_disabled" : "conv_default_cls_enabled"; ?>">
                        <input id="conv_show_badge_onboardingCheck" type="checkbox"
                            <?php echo empty($ee_options['conv_show_badge']) || esc_attr($ee_options['conv_show_badge']) == "no" ? "class ='conv_default_cls_disabled'" : "class ='conv_default_cls_enabled' checked"; ?> />
                        <div></div>
                    </label>
                    <span style="font-weight: 600; padding: 10px; font-size: 14px;">Influence
                        visitor's perceptions and actions on your website via trusted partner
                        Badge</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Success Save Modal End -->
<script>   
    jQuery(document).ready(function () { 
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
        /*********************Card Popover Start***********************************************************************/
        jQuery(document).on('mouseover', '.synced', function () {
            var syncedGmcImg = jQuery(this).next('.syncedGmcImg').val();
            var syncedTiktokImg = jQuery(this).next('.syncedGmcImg').next('.syncedTiktokImg').val();
            var content = '<div class="popover-box border-synced">' + syncedGmcImg + '  ' + syncedTiktokImg + '</div>';
            jQuery(this).popover({
                html: true,
                template: content,
            });
            jQuery(this).popover('show');
        })

        jQuery(document).on('mouseover', '.failed', function () {
            var failedGmcImg = jQuery(this).next('.failedGmcImg').val();
            var failedTiktokImg = jQuery(this).next('.failedGmcImg').next('.failedTiktokImg').val();
            var content = "<div class='popover-box border-failed'>" + failedGmcImg + "  " + failedTiktokImg + "</div>";
            jQuery(this).popover({
                html: true,
                template: content,
            });
            jQuery(this).popover('show');
        })

        jQuery(document).on('mouseover', '.draft', function () {
            var draftGmcImg = jQuery(this).next('.draftGmcImg').val();
            var draftTiktokImg = jQuery(this).next('.draftGmcImg').next('.draftTiktokImg').val();
            var content = '<div class="popover-box border-draft">' + draftGmcImg + '  ' + draftTiktokImg + '</div>';
            jQuery(this).popover({
                html: true,
                template: content,
            });
            jQuery(this).popover('show');
        })
        jQuery(document).on('mouseover', '.inprogress', function () {
            var inprogressGmcImg = jQuery(this).next('.inprogressGmcImg').val();
            var inprogressTiktokImg = jQuery(this).next('.inprogressGmcImg').next('.inprogressTiktokImg').val();
            var content = '<div class="popover-box border-inprogress">' + inprogressGmcImg + '  ' + inprogressTiktokImg + '</div>';
            jQuery(this).popover({
                html: true,
                template: content,
            });
            jQuery(this).popover('show');
        })
        /*********************Card Popover  End**************************************************************************/
        /*********************Custom DataTable for Search functionality Start*********************************************/
        jQuery('#feed_list_table').DataTable({
            order: [[6, 'desc']],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12't>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            rowReorder: true,
            columnDefs: [
                { orderable: true, targets: 1 },
                { orderable: true, targets: 2 },
                { orderable: true, targets: 4 },
                { orderable: true, targets: 5 },
                { orderable: true, targets: 6 },
                { orderable: true, targets: 7 },
                { orderable: true, targets: 8 },
                { orderable: false, targets: '_all' },

            ],

            initComplete: function () {
                jQuery('#search_feed').on('input', function () {
                    jQuery('#feed_list_table').DataTable().search(jQuery(this).val()).draw();
                });
            }
        });
        jQuery('.dataTables_filter').addClass('d-none');
        /*********************Custom DataTable for Search functionality End***********************************************/
        /****************Create Feed call start********************************/
        jQuery('#create_new_feed').on('click', function (events) {
            jQuery('#gmc_id').attr('disabled', false);
            jQuery('#tiktok_id').attr('disabled', false);
            jQuery('#target_country').attr('disabled', false);
            jQuery('#autoSyncIntvl').attr('disabled', false);
            jQuery("#feedForm")[0].reset();
            jQuery('#feedType').text('Create New Feed');
            jQuery('#submitFeed').text('Create and Next');
            jQuery('#edit').val('');
            jQuery('#tiktok_id').val('');
            jQuery('.tiktok_catalog_id').empty();
            jQuery('.tiktok_catalog_id').removeClass('text-danger');
            jQuery('#convCreateFeedModal').modal('show');
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
            jQuery('.select2').select2({ dropdownParent: jQuery("#convCreateFeedModal") });
            var tiktok_business_account = "<?php echo esc_js($tiktok_business_account) ?>";
            if (tiktok_business_account !== '' && jQuery('#tiktok_id').is(":checked")) {
                getCatalogId(jQuery('#target_country').find(":selected").val());
            }
        });
        /****************Create Feed call end***********************************/
        /****************Feed Name error dismissed start************************/
        jQuery(document).on('input', '#feedName', function (e) {
            e.preventDefault();
            jQuery('#feedName').css('margin-left', '0px');
            jQuery('#feedName').css('margin-right', '0px');
            jQuery('#feedName').removeClass('errorInput');
        });
        /****************Feed Name error dismissed end**************************/
        /****************Submit Feed call start*********************************/
        jQuery(document).on('click', '#submitFeed', function (e) {
            e.preventDefault();
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

            let target_country = jQuery('#target_country').find(":selected").val();
            if (target_country === "") {
                jQuery('.select2-selection').css('border', '1px solid #ef1717');
                return false;
            }

            if (!jQuery('#gmc_id').is(":checked") && !jQuery('#tiktok_id').is(":checked")) {
                jQuery('.errorChannel').css('border', '1px solid red');
                return false;
            }
            save_feed_data();
        });

        /****************Submit Feed call end***********************************/
        /********************Modal POP up validation on click remove**********************************/
        jQuery(document).on('click', '#gmc_id', function (e) {
            jQuery('.errorChannel').css('color', '');
        });
        jQuery(document).on('click', '#tiktok_id', function (e) {
            jQuery('.errorChannel').css('border', '');
        });
        /********************Modal POP up validation on click remove end **********************************/
        /****************Get tiktok catalog id on target country change ***************************************/
        jQuery(document).on('change', '#target_country', function (e) {
            var tiktok_business_account = "<?php echo esc_js($tiktok_business_account) ?>";
            jQuery('.select2-selection').css('border', '1px solid #c6c6c6');
            let target_country = jQuery('#target_country').find(":selected").val();
            jQuery('#tiktok_id').empty();
            jQuery('.tiktok_catalog_id').empty()
            if (target_country !== "" && tiktok_business_account !== "" && jQuery('input#tiktok_id').is(':checked')) {
                getCatalogId(target_country);
            }
        });
        /****************Get tiktok catalog id on target country change end ***************************************/
        /************************************* Auto Sync Toggle Button Start*************************************************************************/
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
        /************************************* Auto Sync Toggle Button End*************************************************************************/
        /****************Get tiktok catalog id on check box change ***************************************/
        jQuery(document).on('change', '#tiktok_id', function () {
            jQuery('.tiktok_catalog_id').empty();
            jQuery('#tiktok_id').val('');
            if (jQuery('#tiktok_id').is(":checked")) {
                getCatalogId(jQuery('#target_country').find(":selected").val())
            }
        });
        /****************Get tiktok catalog id on check box change end ***************************************/
    });
    /*************************************Process Loader Start*************************************************************************/
    function conv_change_loadingbar(state = 'show') {
        if (state === 'show') {
            jQuery("#loadingbar_blue").removeClass('d-none');
            jQuery("#wpbody").css("pointer-events", "none");
        } else {
            jQuery("#loadingbar_blue").addClass('d-none');
            jQuery("#wpbody").css("pointer-events", "auto");
        }
    }
    function conv_change_loadingbar_modal(state = 'show') {
        if (state === 'show') {
            jQuery("#loadingbar_blue_modal").removeClass('d-none');
            jQuery("#wpbody").css("pointer-events", "none");
            jQuery('#submitFeed').attr('disabled', true);
        } else {
            jQuery("#loadingbar_blue_modal").addClass('d-none');
            jQuery("#wpbody").css("pointer-events", "auto");
            jQuery('#submitFeed').attr('disabled', false);
        }
    }
    /*************************************Process Loader End*************************************************************************/
    /*************************************Restrict Zero start*************************************************************************/
    function removeZero() {
        var val = jQuery("#autoSyncIntvl").val();
        if (val === '0') {
            jQuery("#autoSyncIntvl").val('')
        }
    }
    /*************************************Restrict Zero  End*************************************************************************/
    /*************************************Save Feed Data Start*************************************************************************/
    function save_feed_data(google_merchant_center_id, catalog_id) {
        var conv_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conv_onboarding_nonce')); ?>"
        let edit = jQuery('#edit').val();
        var data = {
            action: "save_feed_data",
            feedName: jQuery('#feedName').val(),
            google_merchant_center: jQuery('input#gmc_id').is(':checked') ? '1' : '',
            tiktok_id: jQuery('input#tiktok_id').is(':checked') ? '3' : '',
            tiktok_catalog_id: jQuery('input#tiktok_id').is(':checked') ? jQuery('input#tiktok_id').val() : '',
            autoSync: jQuery('input#autoSync').is(':checked') ? '1' : '0',
            autoSyncIntvl: '25',
            edit: edit,
            last_sync_date: jQuery('#last_sync_date').val(),
            is_mapping_update: jQuery('#is_mapping_update').val(),
            target_country: jQuery('#target_country').find(":selected").val(),
            customer_subscription_id: "<?php echo esc_js($subscriptionId) ?>",
            tiktok_business_account: "<?php echo esc_js($tiktok_business_account) ?>",
            conv_onboarding_nonce: conv_onboarding_nonce
        }
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () {
                conv_change_loadingbar_modal('show');
            },
            error: function (err, status) {
                conv_change_loadingbar_modal('hide');
                jQuery('#convCreateFeedModal').modal('hide');
                jQuery("#conv_save_error_txt").html('Error occured.');
                jQuery("#conv_save_error_modal").modal("show");
            },
            success: function (response) {
                
                jQuery('#convCreateFeedModal').modal('hide');
                if (response.id) {
                    jQuery('#convCreateFeedModal').modal('hide');
                    jQuery("#conv_save_success_txt").html("Great job! Your product feed is ready! The next step is to select the products you want to sync and expand your reach across multiple channels.");
                    jQuery("#conv_save_success_modal").modal("show");                    
                    setTimeout(function () {
                        if (edit !== '') {
                            location.reload(true);
                        } else {
                            window.location.replace("<?php echo esc_url($site_url.'product_list&id='); ?>"+response.id);
                        }

                    }, 100);
                } else if (response.errorType === 'tiktok') {
                    jQuery('.tiktok_catalog_id').empty();
                    jQuery('.tiktok_catalog_id').html(response.message);
                    jQuery('.tiktok_catalog_id').addClass('text-danger');

                } else {
                    jQuery('#convCreateFeedModal').modal('hide');
                    jQuery("#conv_save_error_txt").html(response.message);
                    jQuery("#conv_save_error_modal").modal("show");
                }
                conv_change_loadingbar_modal('hide');
            }
        });

    }
    /*************************************Save Feed Data End***************************************************************************/
    /*************************************Edit Feed Data Start*************************************************************************/
    function editFeed($id) {
        jQuery('#gmc_id').attr('disabled', false);
        jQuery('#target_country').attr('disabled', false);
        var conv_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conv_onboarding_nonce')); ?>"
        var data = {
            action: "get_feed_data_by_id",
            id: $id,
            conv_onboarding_nonce: conv_onboarding_nonce
        }
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () {
                conv_change_loadingbar('show');
            },
            error: function (err, status) {
                conv_change_loadingbar('hide');
                jQuery("#conv_save_error_txt").html('Error occured.');
                jQuery("#conv_save_error_modal").modal("show");
            },
            success: function (response) {
                jQuery('#feedName').val(response[0].feed_name);
                jQuery('#last_sync_date').val(response[0].last_sync_date);
                jQuery('#is_mapping_update').val(response[0].is_mapping_update);
                jQuery('#autoSyncIntvl').val(response[0].auto_sync_interval);
                
                if(response[0].target_country){
                    jQuery('#target_country').val(response[0].target_country);
                }
                if (response[0].auto_schedule === '1') {
                    jQuery('input#autoSync').prop('checked', true);
                    jQuery('#autoSyncIntvl').attr('disabled', false);
                } else {
                    jQuery('input#autoSync').prop('checked', false);
                    jQuery('#autoSyncIntvl').attr('disabled', true);
                }
                jQuery('#gmc_id').prop("checked", false);
                jQuery('#gmc_id').attr('disabled', false);
                jQuery('#tiktok_id').prop("checked", false);
                jQuery('#tiktok_id').attr('disabled', false);
                jQuery('.tiktok_catalog_id').empty();
                //jQuery('#fb_id').prop("checked", false);
                channel_id = response[0].channel_ids.split(",");
                jQuery.each(channel_id, function (index, val) {
                    if (val === '1') {
                        jQuery('#gmc_id').prop("checked", true);
                    }
                    if (val === '3') {
                        jQuery('#tiktok_id').prop("checked", true);
                        jQuery('#tiktok_id').val(response[0].tiktok_catalog_id);
                        jQuery('.tiktok_catalog_id').html(response[0].tiktok_catalog_id)
                    }
                });
                if (response[0].is_mapping_update == '1') {
                    jQuery('#gmc_id').attr('disabled', true);
                    jQuery('#tiktok_id').attr('disabled', true);
                    jQuery('#target_country').attr('disabled', true);
                }
                jQuery('#edit').val(response[0].id);
                jQuery('#centered').html();
                jQuery('#submitFeed').text('Update Feed');
                jQuery('#feedType').text('Edit Feed - '+response[0].feed_name);
                conv_change_loadingbar('hide');                
                jQuery('#target_country').select2({ dropdownParent: jQuery("#convCreateFeedModal") });
                jQuery('#convCreateFeedModal').modal('show');
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            }
        });
    }
    /*************************************Edit Feed Data End****************************************************************************/
    /*************************************Duplicate Feed Data Start*********************************************************************/
    function duplicateFeed($id) {
        var feed_count = jQuery('#feedCount').val();
        var conv_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conv_onboarding_nonce')); ?>"
        var data = {
            action: "ee_duplicate_feed_data_by_id",
            id: $id,
            conv_onboarding_nonce: conv_onboarding_nonce
        }
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () {
                conv_change_loadingbar('show');
            },
            error: function (err, status) {
                conv_change_loadingbar('hide');
                jQuery("#conv_save_error_txt").html('Error occured.');
                jQuery("#conv_save_error_modal").modal("show");
            },
            success: function (response) {
                conv_change_loadingbar('hide');
                if (response.error === false) {
                    jQuery("#conv_save_success_txt").html(response.message);
                    jQuery("#conv_save_success_modal").modal("show");
                    setTimeout(function () {
                        location.reload(true);
                    }, 2000);
                } else {
                    jQuery("#conv_save_error_txt").html(response.message);
                    jQuery("#conv_save_error_modal").modal("show");
                }
            }
        });
    }
    /*************************************Duplicate Feed Data End*********************************************************************/
    /*************************************DELETE Feed Data Start**********************************************************************/
    function deleteFeed($id){
        if(confirm("Alert! Deleting this feed will remove its products from the Google Merchant Center, affecting your campaigns. Make sure it aligns with your strategy. Questions? We're here!")){
            var conv_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conv_onboarding_nonce')); ?>"
            var data = {
                action: "ee_delete_feed_data_by_id",
                id: $id,
                conv_onboarding_nonce:conv_onboarding_nonce  
            }
            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: tvc_ajax_url,
                data: data,
                beforeSend: function () { 
                    conv_change_loadingbar('show'); 
                },
                error: function (err, status) {
                    conv_change_loadingbar('hide');
                    jQuery("#conv_save_error_txt").html('Error in Deleting Feed.');
                    jQuery("#conv_save_error_modal").modal("show");
                },
                success: function (response) { 
                    conv_change_loadingbar('hide');                    
                    jQuery("#conv_save_success_txt").html(response.message);
                    jQuery("#conv_save_success_modal").modal("show");
                    setTimeout(function () {
                        location.reload(true);
                    }, 1000);             
                }
            });
        }            
    }
    /*************************************Delete Feed Data End*************************************************************************/
    /*************************************Save Feed Data End***************************************************************************/
    function conv_change_loadingbar_header(state = 'show') {
        if (state === 'show') {
            jQuery("#loadingbar_blue_header").removeClass('d-none');
            jQuery("#wpbody").css("pointer-events", "none");
        } else {
            jQuery("#loadingbar_blue_header").addClass('d-none');
            jQuery("#wpbody").css("pointer-events", "auto");
        }
    }
    /*************************Create Super AI Feed Start ************************************************************************/
    /*************************Slider animation start ************************************************************************/
    jQuery(document).on('click', '.toggleOpen', function() {
        jQuery('.toggleSpan').show(300);
    })
    jQuery(document).on('click', '.toggleClose', function() {
        jQuery('.toggleSpan').hide(300);
    })
    /*************************Slider animation end ************************************************************************/
    /*************************************Get saved catalog id by country code start **************************************************/
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
                conv_change_loadingbar_modal('show');
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
                conv_change_loadingbar_modal('hide');
            }
        });
    }
    /*************************************Get saved catalog id by country code End ****************************************************/
</script>
<script>  
    /*********************************** Pmax Campaign related code start *************************************************************/  
    var feedId = "<?php echo esc_js($feedIdString) ?>"; //Get all feedId 
    jQuery(document).on('change', "#selectAll", function () {
        jQuery(".checkbox_feed").not(':disabled').prop('checked', jQuery(this).prop('checked')); 
        if(jQuery(this).prop('checked')){
            if(feedId !== "") {
                jQuery('.campaignClass').removeClass('disabled');
                jQuery('#selecetdCampaign').val(feedId)
            }            
        } else {
            jQuery('.campaignClass').addClass('disabled');
            jQuery('#selecetdCampaign').val('')
        }                 
    })
    jQuery(document).on('change', '.checkbox_feed', function () {
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
            jQuery("#selectAll").prop('checked', false)
            if(jQuery('#selecetdCampaign').val() == '') {
                jQuery('.campaignClass').addClass('disabled');  
            }
        }         
    })
    jQuery(document).on('click', '.page-item ', function () {
        let feedstr = jQuery('#selecetdCampaign').val();
        if(feedstr !== ''){
            arr = feedstr.split(',');
            jQuery.each(arr, function (i, v) {
                jQuery('#checkFeed_'+v).prop('checked', true)
            })
        }
    })
    jQuery(document).on('click', '.createCampaign', function () {
        jQuery('#target_country_campaign').select2({ dropdownParent: jQuery("#convCampaignModal") });        
        jQuery('#convCampaignModal').modal('show');     
    })
    
    jQuery(".google_connect_url").on("click", function() {
        const w = 600;
        const h = 650;
        const dualScreenLeft = window.screenLeft !== undefined ? window.screenLeft : window.screenX;
        const dualScreenTop = window.screenTop !== undefined ? window.screenTop : window.screenY;

        const width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document
            .documentElement.clientWidth : screen.width;
        const height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document
            .documentElement.clientHeight : screen.height;

        const systemZoom = width / window.screen.availWidth;
        const left = (width - w) / 2 / systemZoom + dualScreenLeft;
        const top = (height - h) / 2 / systemZoom + dualScreenTop;
        var url = '<?php echo esc_url($googleConnect_url); ?>';
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
    jQuery(document).on('click', '.signinWithGoogle', function() {
        jQuery('#tvc_google_signin').addClass('showpopup');
        jQuery('body').addClass('scrlnone');
    });
    /***************************Submit Campaign start ****************************************************************/
    jQuery(document).on('click', '#submitCampaign', function () {
        //check validation start
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
        
        if(hasError == true) {
            return false;
        }
        var eDate = new Date(jQuery('#end_date').val());
        var sDate = new Date(jQuery('#start_date').val());
        if(sDate > eDate)
        {
            jQuery('#end_date').addClass('errorInput');
            jQuery('.endDateError').html('Check End Date.')
            return false;
        }
        let subscriptionId = "<?php echo esc_js($subscriptionId) ?>";
        let google_merchant_center_id = "<?php echo esc_js($google_merchant_center_id) ?>";
        let google_ads_id = "<?php echo esc_js($google_ads_id) ?>";
        let store_id = "<?php echo esc_js($google_detail['setting']->store_id) ?>";
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
        //check validation end
        var conv_onboarding_nonce = "<?php echo esc_html(wp_create_nonce('conv_onboarding_nonce')); ?>";
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
            domain: "<?php echo esc_js(get_site_url()) ?>",
            store_id: "<?php echo esc_js($google_detail['setting']->store_id) ?>",
            sync_type: "feed",
            conv_onboarding_nonce: conv_onboarding_nonce
        }

        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            beforeSend: function () {
                jQuery("#loadingbar_blue_modal_campign").removeClass('d-none');
                jQuery("#wpbody").css("pointer-events", "none");
                jQuery('#submitCampaign').attr('disabled', true);
            },
            error: function (err, status) {
                jQuery("#loadingbar_blue_modal_campign").addClass('d-none');
                jQuery("#wpbody").css("pointer-events", "auto");
                jQuery('#submitCampaign').attr('disabled', false);
            },
            success: function (response) {
                jQuery("#loadingbar_blue_modal_campign").addClass('d-none');
                jQuery("#wpbody").css("pointer-events", "auto");
                jQuery('#submitCampaign').attr('disabled', false);
                jQuery('#convCampaignModal').modal('hide');
                if(response.error == true) {
                    jQuery('#conv_save_error_txt').html(response.message);
                    jQuery('.dismissErrorModal').addClass('reloadPage')
                    jQuery('#conv_save_error_modal').modal('show')
                }else {
                    jQuery('#conv_save_success_txt').html("Exciting things are happening behind the scenes! We're crafting your Pmax campaign for Google Ads with precision. Your products are gearing up to shine. Sit tight, and get ready for an amplified reach and increased sales.")
                    jQuery('.modalFooterSuccess').html('<button type="button" class="btn conv-blue-bg text-white gotopmaxlist">Go to PMax list</button><button class="btn conv-blue-bg text-white dismissModal" data-bs-dismiss="modal">Close</button>')
                    jQuery('.dismissModal').addClass('reloadPage')
                    jQuery('#conv_save_success_modal').modal('show')
                }
                
            }
        });

    })
    /***************************Submit Campaign end ******************************************************************/
    /***************************Remove Error on input change satrt ***************************************************/
    jQuery(document).on('keyup change', '.errorInput', function() {
        jQuery(this).removeClass('errorInput')
        jQuery(this).next('span').removeClass('errorInput')
        jQuery('.endDateError').html('')
    })
    /***************************Remove Error on input change end *****************************************************/
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
    // function NumAndTwoDecimals(e , field) {
    //     var val = field.value;
    //     var re = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)$/g;
    //     var re1 = /^([0-9]+[\.]?[0-9]?[0-9]?|[0-9]+)/g;
    //     if (re.test(val)) {
    //         //do something here

    //     } else {
    //         val = re1.exec(val);
    //         if (val) {
    //             field.value = val[0];
    //         } else {
    //             field.value = "";
    //         }
    //     }
    // }
    jQuery(".clsbtntrgr").on("click", function() {
        jQuery(this).closest('.pp-modal').removeClass('showpopup');
        jQuery('body').removeClass('scrlnone');
    });

    jQuery(document).on('click', '.reloadPage', function() {
        jQuery('.dismissModal').removeClass('reloadPage');
        jQuery('.dismissErrorModal').removeClass('reloadPage');
        location.reload(true);
    })
    jQuery(document).on('click', '.gotopmaxlist', function() {
        window.location.replace("<?php echo esc_url($site_url_pmax); ?>");
    })
    /*********************************** Pmax Campaign related code End ***************************************************************/ 
</script>
