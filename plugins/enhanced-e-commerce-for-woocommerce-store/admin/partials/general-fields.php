<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$TVC_Admin_Helper = new TVC_Admin_Helper();
$this->customApiObj = new CustomApi();
$class = "";
$message_p = "";
$validate_pixels = array();
$google_detail = $TVC_Admin_Helper->get_ee_options_data();
$plan_id = 1;
$googleDetail = "";
if (isset($google_detail['setting'])) {
  $googleDetail = $google_detail['setting'];
  if (isset($googleDetail->plan_id) && !in_array($googleDetail->plan_id, array("1"))) {
    $plan_id = $googleDetail->plan_id;
  }
}

$data = unserialize(get_option('ee_options'));
$conv_selected_events = unserialize(get_option('conv_selected_events'));
$this->current_customer_id = $TVC_Admin_Helper->get_currentCustomerId();
$subscription_id = $TVC_Admin_Helper->get_subscriptionId();

$TVC_Admin_Helper->add_spinner_html();
$is_show_tracking_method_options =  true; //$TVC_Admin_Helper->is_show_tracking_method_options($subscription_id);
?>

<!-- Main container -->
<div class="container-old conv-container conv-setting-container pt-4">

  <!-- Main row -->
  <div class="row justify-content-center">
    <!-- Main col8 center -->
    <div class="convfixedcontainermid col-md-8 col-xs-12 m-0 p-0">

      <div class="pt-4 pb-4 conv-heading-box">
        <h3>
          <?php esc_html_e("IMPLEMENTATION METHOD", "enhanced-e-commerce-for-woocommerce-store"); ?>
        </h3>
        <span>
          <?php esc_html_e("Connect your Google Tag Manager account to start configuring Google Analytics and/or pixel tracking.", "enhanced-e-commerce-for-woocommerce-store"); ?>
        </span>
      </div>

      <!-- GTM Card -->
      <?php
      $tracking_method = (isset($data['tracking_method']) && $data['tracking_method'] != "") ? $data['tracking_method'] : "";
      $want_to_use_your_gtm = (isset($data['want_to_use_your_gtm']) && $data['want_to_use_your_gtm'] != "") ? $data['want_to_use_your_gtm'] : "0";
      $use_your_gtm_id = "";
      if (isset($tracking_method) && $tracking_method == "gtm") {
        $use_your_gtm_id =  ($data['tracking_method'] == 'gtm' && $want_to_use_your_gtm == 1) ? "Your own GTM container - " . $data['use_your_gtm_id'] : (($data['tracking_method'] == 'gtm') ? "Container ID: GTM-K7X94DG (Conversios Default Container) " : esc_attr("Your own GTM container - " . $data['use_your_gtm_id']));
      }
      ?>

      <?php if (isset($tracking_method) && $tracking_method == 'gtag') { ?>
        <div class="alert d-flex align-items-cente p-0" role="alert">
          <div class="text-light conv-error-bg rounded-start d-flex">
            <span class="p-2 material-symbols-outlined align-self-center">info</span>
          </div>

          <div class="p-2 w-100 rounded-end border border-start-0 shadow-sm conv-notification-alert lh-lg bg-white">
            <h6 class="fs-6 lh-1 text-dark fw-bold border-bottom w-100 py-2">
              <?php esc_html_e("Attention!", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </h6>

            <span class="fs-6 lh-1 text-dark">
              <?php esc_html_e("As you might be knowing, GA3 is seeing sunset from 1st July 2023, we are also removing gtag.js based implementation for the old app users soon. Hence, we recommend you to change your implementation method to Google Tag Manager from below to avoid data descrepancy in the future.", "enhanced-e-commerce-for-woocommerce-store"); ?>
          </div>
        </div>

      <?php } ?>

      <div class="convcard d-flex flex-row p-2 mt-0 rounded-3 shadow-sm">
        <div class="convcard-left conv-pixel-logo">
          <div class="convcard-logo text-center p-2 pe-3 border-end">
            <img src="<?php print esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gtm_logo.png'); ?>" />
          </div>
        </div>
        <div class="convcard-center p-2 ps-3 col-10">
          <div class="convcard-title">
            <div class="row">
              <div class="col-md-10">
                <h3>
                  <?php esc_html_e("Google Tag Manager (GTM)", "enhanced-e-commerce-for-woocommerce-store"); ?>

                </h3>
              </div>
              <div class="col-md-2 p-0">
                <span class="gtm-badge badge rounded-pill conv-badge <?php print (empty($subscription_id) || $tracking_method != "gtm") ? "conv-badge-yellow" : "conv-badge-green"; ?>">
                  <?php print (empty($subscription_id) || $tracking_method !== "gtm") ? "Mandatory" : "Connected"; ?>
                </span>
              </div>
            </div>

            <span class="gtm-lable">
              Container ID: <b> GTM-K7X94DG (Conversios Default Container)</b>
            </span>
            <hr>
            <div class="d-flex">
              <span class="fw-bold-500 conv-recommended-text">
                <?php esc_html_e("Recommended: ", "enhanced-e-commerce-for-woocommerce-store"); ?>

              </span>
            </div>
            <div class="d-flex mt-2">
              <span>
                <?php esc_html_e("Use your Own GTM to get Faster, Secure and Personalize Experience. ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                <!-- <a target="_blank" class="conv-link-blue conv-watch-video" href="https://www.youtube.com/watch?v=bvR1M0nh2qU"> -->
                <!-- <?php esc_html_e("Watch here", "enhanced-e-commerce-for-woocommerce-store"); ?>
                  <span class="material-symbols-outlined align-middle">play_circle_outline</span> -->
                </a>
              </span>
            </div>

            <div class="d-flex mt-2">
              <span>
                <?php esc_html_e("To User Your Own GTM Container. ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M8.01953 11.417L8.76953 9.02116L6.79036 7.47949H9.20703L9.9987 5.00033L10.7695 7.47949H13.207L11.2279 9.02116L11.957 11.417L9.9987 9.93783L8.01953 11.417ZM5.08203 19.167V12.8337C4.45703 12.1809 4.00911 11.4656 3.73828 10.6878C3.46745 9.91005 3.33203 9.12533 3.33203 8.33366C3.33203 6.44477 3.97092 4.86144 5.2487 3.58366C6.52648 2.30588 8.10981 1.66699 9.9987 1.66699C11.8876 1.66699 13.4709 2.30588 14.7487 3.58366C16.0265 4.86144 16.6654 6.44477 16.6654 8.33366C16.6654 9.12533 16.5299 9.91005 16.2591 10.6878C15.9883 11.4656 15.5404 12.1809 14.9154 12.8337V19.167L9.9987 17.5212L5.08203 19.167ZM9.9987 13.7503C11.5126 13.7503 12.7938 13.226 13.8424 12.1774C14.8911 11.1288 15.4154 9.84755 15.4154 8.33366C15.4154 6.81977 14.8911 5.53852 13.8424 4.48991C12.7938 3.4413 11.5126 2.91699 9.9987 2.91699C8.48481 2.91699 7.20356 3.4413 6.15495 4.48991C5.10634 5.53852 4.58203 6.81977 4.58203 8.33366C4.58203 9.84755 5.10634 11.1288 6.15495 12.1774C7.20356 13.226 8.48481 13.7503 9.9987 13.7503ZM6.33203 17.417L9.9987 16.2712L13.6654 17.417V13.8545C13.1098 14.2573 12.5126 14.5489 11.8737 14.7295C11.2348 14.91 10.6098 15.0003 9.9987 15.0003C9.38759 15.0003 8.76259 14.91 8.1237 14.7295C7.48481 14.5489 6.88759 14.2573 6.33203 13.8545V17.417Z" fill="#1967D2" />
                </svg>

                <a target="_blank" class="conv-link-blue" href="https://www.conversios.io/wordpress/all-in-one-google-analytics-pixels-and-product-feed-manager-for-woocommerce-pricing/?utm_source=in_app&utm_medium=use_your_own_gtm&utm_campaign=pixel_list">
                  <span class="text fw-bold"><?php esc_html_e("Upgrade to Pro", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                </a>
              </span>
            </div>

          </div>
        </div>

        <div class="convcard-right ms-auto">
          <a href="<?php print esc_url('admin.php?page=conversios-google-analytics&subpage="gtmsettings"'); ?>" class="h-100 rounded-end d-flex justify-content-center convcard-right-arrow link-dark">
            <span class="material-symbols-outlined align-self-center">chevron_right</span>
          </a>
        </div>

      </div>
      <!-- GTM Card End -->

      <!-- GTM Server Side Start -->
      <div class="convo_sst convcard d-flex flex-row  mt-3" data-bs-toggle="modal" data-bs-target="#convSsttoProModal">
        <div class="convcard-left conv-pixel-logo">
          <div class="convcard-logo text-center">
            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_sstgtm_logo.svg'); ?>" />
          </div>
        </div>
        <div class="convcard-center ">
          <div class="convcard-title">
            <h3>
              <?php esc_html_e("Server Side Tagging Via GTM", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </h3>
            <p>
              <?php esc_html_e("To Know The Benefits and How To User Server Side Tagging", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <span><img src="" alt=""><?php esc_html_e("Click Here", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
            </p>
          </div>
        </div>

        <div class="convcard-right ms-auto">
          <a href="" class=" rounded-end d-flex justify-content-center convcard-right-arrow link-dark" data-bs-toggle="modal" data-bs-target="#convSsttoProModal">
            <span class="material-symbols-outlined align-self-center">chevron_right</span>
          </a>
        </div>
      </div>
      <!-- GTM Server Side End -->

      <div id="pixelslist" class="pt-4 conv-heading-box">
        <h3><?php esc_html_e("INTEGRATIONS", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
        <span><?php esc_html_e("Once you’ve finished setting up your Google Tag Manager (GTM), go ahead with pixels & other integrations.", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
      </div>

      <!-- All pixel list -->
      <?php
      $conv_gtm_not_connected = (empty($subscription_id) || $tracking_method != "gtm") ? "conv-gtm-not-connected" : "conv-gtm-connected";
      $pixel_not_connected = array(
        "ga_id" => (isset($data['ga_id']) && $data['ga_id'] != '') ? '' : 'conv-pixel-not-connected',
        "gm_id" => (isset($data['gm_id']) && $data['gm_id'] != '') ? '' : 'conv-pixel-not-connected',
        "google_ads_id" => (isset($data['google_ads_id']) && $data['google_ads_id'] != '') ? '' : 'conv-pixel-not-connected',
        "fb_pixel_id" => (isset($data['fb_pixel_id']) && $data['fb_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
        "microsoft_ads_pixel_id" => (isset($data['microsoft_ads_pixel_id']) && $data['microsoft_ads_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
        "msclarity_pixel_id" => (isset($data['msclarity_pixel_id']) && $data['msclarity_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
        "twitter_ads_pixel_id" => (isset($data['twitter_ads_pixel_id']) && $data['twitter_ads_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
        "pinterest_ads_pixel_id" => (isset($data['pinterest_ads_pixel_id']) && $data['pinterest_ads_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
        "snapchat_ads_pixel_id" => (isset($data['snapchat_ads_pixel_id']) && $data['snapchat_ads_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
        "tiKtok_ads_pixel_id" => (isset($data['tiKtok_ads_pixel_id']) && $data['tiKtok_ads_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
        "hotjar_pixel_id" => (isset($data['hotjar_pixel_id']) && $data['hotjar_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
        "crazyegg_pixel_id" => (isset($data['hotjar_pixel_id']) && $data['crazyegg_pixel_id'] != '') ? '' : 'conv-pixel-not-connected',
      );


      $pixel_video_link = array(
        "ga_id" => "https://www.conversios.io/docs/ecommerce-events-that-will-be-automated-using-conversios/?utm_source=galisting_inapp&utm_medium=resource_center_list&utm_campaign=resource_center",
        "gm_id" => "https://www.conversios.io/docs/ecommerce-events-that-will-be-automated-using-conversios/?utm_source=galisting_inapp&utm_medium=resource_center_list&utm_campaign=resource_center",
        "google_ads_id" => "https://youtu.be/Vr7vEeMIf7c",
        "fb_pixel_id" => "https://youtu.be/8nIyvQjeEkY",
        "microsoft_ads_pixel_id" => "https://youtu.be/BeP1Tp0I92o",
        "twitter_ads_pixel_id" => "",
        "pinterest_ads_pixel_id" => "https://youtu.be/Z0rcP1ItJDk",
        "snapchat_ads_pixel_id" => "https://youtu.be/uLQqAMQhFUo",
        "tiKtok_ads_pixel_id" => "https://www.conversios.io/docs/how-to-set-up-tiktok-pixel-using-conversios-plugin/?utm_source=Tiktoklisting_inapp&utm_medium=resource_center_list&utm_campaign=resource_center",
        "hotjar_pixel_id" => "",
        "crazyegg_pixel_id" => "",
      );
      ?>

      <div id="conv_pixel_list_box" class="shadow-sm">

        <!-- Google analytics  -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-4 rounded-top <?php echo esc_attr($conv_gtm_not_connected); ?>">

          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_ganalytics_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Google Analytics", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <a target="_blank" class="conv-link-blue conv-watch-video ps-2 fw-normal invisible" href="<?php echo esc_url($pixel_video_link['gm_id']); ?>">
                <span class="material-symbols-outlined align-text-bottom">play_circle_outline</span>
                <?php esc_html_e("Watch here", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </a>
            </span>
            <?php if ((empty($pixel_not_connected['ga_id']) || empty($pixel_not_connected['gm_id'])) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="m-0"> <?php echo (isset($data['ga_id']) && $data['ga_id'] != '') ? esc_attr("GA3: " . $data['ga_id']) : ''; ?> </span>
                <?php if (isset($data['gm_id']) && $data['gm_id'] != '') { ?>
                  <span class="<?php echo (isset($data['ga_id']) && $data['ga_id'] != '') ? 'border-start ps-2  ms-2' : 'm-0'; ?> "> <?php echo (isset($data['gm_id']) && $data['gm_id'] != '') ? esc_attr("GA4: " . $data['gm_id']) : ''; ?> </span>
                <?php } ?>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if ((empty($pixel_not_connected['ga_id']) || empty($pixel_not_connected['gm_id'])) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="gasettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>

        </div>

         <!-- Google Ads -->
         <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gads_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Google Ads Remarketing & Conversion Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <a target="_blank" class="conv-link-blue conv-watch-video ps-2 fw-normal invisible" href="<?php echo esc_url($pixel_video_link['google_ads_id']); ?>">
                <span class="material-symbols-outlined align-text-bottom">play_circle_outline</span>
                <?php esc_html_e("Watch here", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </a>
            </span>
            <?php if (empty($pixel_not_connected['google_ads_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="pe-2 m-0"> <?php echo (isset($data['google_ads_id']) && $data['google_ads_id'] != '') ? esc_attr($data['google_ads_id']) : ''; ?> </span>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if (empty($pixel_not_connected['google_ads_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="gadssettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>
        
        <!-- FB Pixel -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_meta_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Facebook Pixel & Facebook Conversions API (Meta)", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <a target="_blank" class="conv-link-blue conv-watch-video ps-2 fw-normal invisible" href="<?php echo esc_url($pixel_video_link['fb_pixel_id']); ?>">
                <span class="material-symbols-outlined align-text-bottom">play_circle_outline</span>
                <?php esc_html_e("Watch here", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </a>
            </span>
            <?php if (empty($pixel_not_connected['fb_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="pe-2 m-0"> <?php echo (isset($data['fb_pixel_id']) && $data['fb_pixel_id'] != '') ? esc_attr($data['fb_pixel_id']) : ''; ?> </span>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if (empty($pixel_not_connected['fb_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="fbsettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>


        <!-- MS Bing Pixel -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_bing_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Microsoft Clarity & Ads Pixel (Bing)", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <a target="_blank" class="conv-link-blue conv-watch-video ps-2 fw-normal invisible" href="<?php echo esc_url($pixel_video_link['microsoft_ads_pixel_id']); ?>">
                <span class="material-symbols-outlined align-text-bottom">play_circle_outline</span>
                <?php esc_html_e("Watch here", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </a>
            </span>

            <?php if ((empty($pixel_not_connected['microsoft_ads_pixel_id']) || empty($pixel_not_connected['msclarity_pixel_id']))  && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">

                <?php
                if (isset($data['msclarity_pixel_id']) && $data['msclarity_pixel_id'] != '') { ?>
                  <span class="pe-2 m-0"> <?php echo esc_html($data['msclarity_pixel_id']); ?> </span>
                <?php }
                ?>

                <?php
                if (isset($data['microsoft_ads_pixel_id']) && $data['microsoft_ads_pixel_id'] != '') { ?>
                  <span class="pe-2 m-0"> <?php echo (isset($data['microsoft_ads_pixel_id']) && $data['microsoft_ads_pixel_id'] != '') ? esc_attr($data['microsoft_ads_pixel_id']) : ''; ?> </span>
                <?php }
                ?>

              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if ((empty($pixel_not_connected['microsoft_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") || (empty($pixel_not_connected['msclarity_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected")) { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="bingsettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>


        <!-- Pinterest Pixel -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_pint_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Pinterest Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <a target="_blank" class="conv-link-blue conv-watch-video ps-2 fw-normal invisible" href="<?php echo esc_url($pixel_video_link['pinterest_ads_pixel_id']); ?>">
                <span class="material-symbols-outlined align-text-bottom">play_circle_outline</span>
                <?php esc_html_e("Watch here", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </a>
            </span>
            <?php if (empty($pixel_not_connected['pinterest_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="pe-2 m-0"> <?php echo (isset($data['pinterest_ads_pixel_id']) && $data['pinterest_ads_pixel_id'] != '') ? esc_attr($data['pinterest_ads_pixel_id']) : ''; ?> </span>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if (empty($pixel_not_connected['pinterest_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="pintrestsettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>


        <!-- Snapchat Pixel -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_snap_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Snapchat Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <a target="_blank" class="conv-link-blue conv-watch-video ps-2 fw-normal invisible" href="<?php echo esc_url($pixel_video_link['snapchat_ads_pixel_id']); ?>">
                <span class="material-symbols-outlined align-text-bottom">play_circle_outline</span>
                <?php esc_html_e("Watch here", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </a>
            </span>
            <?php if (empty($pixel_not_connected['snapchat_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="pe-2 m-0"> <?php echo (isset($data['snapchat_ads_pixel_id']) && $data['snapchat_ads_pixel_id'] != '') ? esc_attr($data['snapchat_ads_pixel_id']) : ''; ?> </span>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if (empty($pixel_not_connected['snapchat_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="snapchatsettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>

        <!-- Tiktok -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_tiktok_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Tiktok Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <a target="_blank" class="conv-link-blue conv-watch-video ps-2 fw-normal invisible" href="<?php echo esc_url($pixel_video_link['tiKtok_ads_pixel_id']); ?>">
                <span class="material-symbols-outlined align-text-bottom">play_circle_outline</span>
                <?php esc_html_e("Watch here", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </a>
            </span>
            <?php if (empty($pixel_not_connected['tiKtok_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="pe-2 m-0"> <?php echo (isset($data['tiKtok_ads_pixel_id']) && $data['tiKtok_ads_pixel_id'] != '') ? esc_attr($data['tiKtok_ads_pixel_id']) : ''; ?> </span>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if (empty($pixel_not_connected['tiKtok_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="tiktoksettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>

        <!-- Twitter Pixel -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_twitter_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Twitter Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </span>
            <?php if (empty($pixel_not_connected['twitter_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="pe-2 m-0"> <?php echo (isset($data['twitter_ads_pixel_id']) && $data['twitter_ads_pixel_id'] != '') ? esc_attr($data['twitter_ads_pixel_id']) : ''; ?> </span>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if (empty($pixel_not_connected['twitter_ads_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="twittersettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>

        <!-- Hotjar -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_hotjar_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Hotjar Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </span>
            <?php if (empty($pixel_not_connected['hotjar_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="pe-2 m-0"> <?php echo (isset($data['hotjar_pixel_id']) && $data['hotjar_pixel_id'] != '') ? esc_attr($data['hotjar_pixel_id']) : ''; ?> </span>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if (empty($pixel_not_connected['hotjar_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="hotjarsettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>

        <!-- Crazyegg -->
        <div class="convcard conv-pixel-list-item d-flex flex-row p-2 mt-0 border-top <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_crazyegg_logo.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold m-0">
              <?php esc_html_e("Crazyegg Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </span>
            <?php if (empty($pixel_not_connected['crazyegg_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <div class="d-flex pt-2">
                <span class="pe-2 m-0"> <?php echo (isset($data['crazyegg_pixel_id']) && $data['crazyegg_pixel_id'] != '') ? esc_attr($data['crazyegg_pixel_id']) : ''; ?> </span>
              </div>
            <?php } ?>
          </div>

          <div class="ms-auto d-flex">
            <?php if (empty($pixel_not_connected['crazyegg_pixel_id']) && $conv_gtm_not_connected == "conv-gtm-connected") { ?>
              <span class="badge rounded-pill conv-badge conv-badge-green m-0 me-3 align-self-center">Connected</span>
            <?php } else { ?>
              <span class="badge rounded-pill conv-badge conv-badge-red m-0 me-3 align-self-center">Not Connected</span>
            <?php } ?>
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="crazyeggsettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>
        </div>

      </div>
      <!-- All pixel list end -->

      <?php if (is_plugin_active_for_network('woocommerce/woocommerce.php') || in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) { ?>
        <div class="pt-4 conv-heading-box">
          <h3>
            <?php esc_html_e("Advance Options", "enhanced-e-commerce-for-woocommerce-store"); ?>
          </h3>
          <span class="lh-base">
            <?php esc_html_e("This feature is for the woocommerce store which has changed standard woocommerce hooks or implemented custom woocommerce hooks.", "enhanced-e-commerce-for-woocommerce-store"); ?>
          </span>
        </div>

        <!-- Advanced option -->
        <div class="convcard conv-pixel-list-item rounded d-flex flex-row p-2 mt-1 <?php echo esc_attr($conv_gtm_not_connected); ?>">
          <div class="p-2 pe-3 conv-pixel-logo border-end d-flex">
            <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_event_track_custom.png'); ?>" />
          </div>

          <div class="p-1 ps-3 align-self-center">
            <span class="fw-bold">
              <?php esc_html_e("Event Tracking - Custom Integration", "enhanced-e-commerce-for-woocommerce-store"); ?>
              <a target="_blank" class="conv-link-blue conv-watch-video ps-2 fw-normal invisible" href="<?php echo esc_url("https://" . TVC_AUTH_CONNECT_URL . "/help-center/event-tracking-custom-integration.pdf"); ?>">
                <span class="material-symbols-outlined align-text-bottom">article</span>
                <?php esc_html_e("Read Here", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </a>
            </span>
          </div>

          <div class="ms-auto d-flex">
            <a href="<?php echo esc_url('admin.php?page=conversios-google-analytics&subpage="customintgrationssettings"'); ?>" class="rounded-end convcard-right-arrow align-self-center link-dark">
              <span class="material-symbols-outlined p-2">chevron_right</span>
            </a>
          </div>

        </div>
        <!-- Advance option End -->
      <?php } ?>


      <!-- Blue upgrade to pro -->
      <div class="convcard conv-green-grad-bg rounded-3 d-flex flex-row p-3 mt-4 shadow-sm">
        <div class="convcard-blue-left align-self-center p-2 bd-highlight">
          <h3 class="text-light mb-3">
            <?php esc_html_e("Upgrade your Plan to get pro benefits", "enhanced-e-commerce-for-woocommerce-store"); ?>
          </h3>
          <span class="text-light">
            <ul class="conv-green-banner-list ps-4">
              <li><?php esc_html_e("Take control, boost speed. Automate your Google Tag Manager.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
              <li><?php esc_html_e("Maximize campaigns with Google Ads Conversion integration.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
              <li><?php esc_html_e("Quick and Easy install of Facebook Conversions API to drive sales via Facebook Ads.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
              <li><?php esc_html_e("Sync unlimited product feeds with Content API and more.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
              <li><?php esc_html_e("Make data-driven decisions. Scale your ecommerce business with our reporting dashboard.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
              <li><?php esc_html_e("Free website audit, dedicated success manager, priority slack support.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
            </ul>
          </span>
          <span class="d-flex">
            <a style="padding:8px 24px 8px 24px;" class="btn conv-yellow-bg mt-4 btn-lg" href="<?php print esc_url($TVC_Admin_Helper->get_conv_pro_link_adv("banner", "pixel_list", "", "linkonly")); ?>" target="_blank">Upgrade Now</a>
          </span>
        </div>
        <div class="convcard-blue-right align-self-center p-2 bd-highlight mx-auto">
          <img src="<?php print esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/freetopaid_banner_img.png'); ?>" />
        </div>
      </div>
      <!-- Blue upgrade to pro End -->


    </div>
    <!-- Main col8 center -->
  </div>
  <!-- Main row -->
</div>
<!-- Main container End -->

<!-- Upgrade to SST modal -->
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

<!-- EC Start -->
<div class="rounded-3 p-3 bg-white ecbuttonbox">
  <div class="convcard-left conv-pixel-logo">
    <div class="convcard-title">
      <h6 class="mb-0 text-white">
        <?php esc_html_e("Event Tracking Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
      </h6>
      <p class="mb-2 text-white mt-2" style="line-height: 15px;">
        <?php esc_html_e("See in real time if events are being tracked correctly on your website.", "enhanced-e-commerce-for-woocommerce-store"); ?>
      </p>
      <small class="mb-3 text-white">
        <b><?php esc_html_e("Note:", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
        <?php esc_html_e("Make sure to use this feature in Chrome browser for accurate result. Also, make sure the pop blocker is not enabled in your browser.", "enhanced-e-commerce-for-woocommerce-store"); ?>
      </small>
    </div>
  </div>

  <div id="starttrackingbut" class="w-100 d-flex justify-content-between rounded-3 px-3 align-items-center py-2">
    <div class="convecbuttext">
      <?php esc_html_e("Start Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
    </div>
    <span class="material-symbols-outlined align-self-center">chevron_right</span>
  </div>
</div>
<!-- EC End -->
<!-- Upgrade to PRO modal End -->


<script>
  // Set GTM on page load. 
  let tracking_method = "<?php echo esc_js($tracking_method) ?>";
  if (tracking_method != 'gtm' && tracking_method == '') {
    jQuery.ajax({
      type: "POST",
      dataType: "json",
      url: tvc_ajax_url,
      data: {
        action: "conv_save_pixel_data",
        pix_sav_nonce: "<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')); ?>",
        conv_options_data: {
          want_to_use_your_gtm: 0,
          tracking_method: 'gtm'
        },
        conv_options_type: ["eeoptions", "eeapidata"],
      },
      success: function(response) {
        jQuery('.gtm-badge').removeClass('conv-badge-yellow').addClass('conv-badge-green');
        jQuery('.gtm-badge').text('Connected')
        jQuery('.conv-pixel-list-item').removeClass('conv-gtm-not-connected').addClass('conv-gtm-connected')
        jQuery('.gtm-lable').html('Container ID: <b> GTM-K7X94DG (Conversios Default Container)</b>')
      },
      error: function(error) {
        console.log('error', error)
        jQuery('.gtm-badge').removeClass('conv-badge-green').addClass('conv-badge-yellow');
        jQuery('.gtm-badge').text('Mandatory')
        jQuery('.conv-pixel-list-item').removeClass('conv-gtm-connected').addClass('conv-gtm-not-connected')
      }
    });
  }
  jQuery(function() {
    var connectedcount = jQuery("#conv_pixel_list_box .conv-gtm-connected.conv-pixel-list-item .conv-badge.conv-badge-green").length;
    if (connectedcount == 0) {
      jQuery(".ecbuttonbox").hide();
    }

    jQuery('#starttrackingbut').click(function() {
      jQuery('#starttrackingbut').addClass('convdisabledbox');
      var ecrandomstring = "<?php echo esc_js($TVC_Admin_Helper->generateRandomStringConv()); ?>";
      var subscription_id = "<?php echo esc_js($subscription_id); ?>";
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
          // console.log(response);
        }
      });
    });
  });
</script>