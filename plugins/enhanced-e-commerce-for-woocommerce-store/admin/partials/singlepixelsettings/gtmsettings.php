<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

$ga3_id = isset($ee_options['ga_id']) && $ee_options['ga_id'] != "" ? $ee_options['ga_id'] : "";
$ga4_id = isset($ee_options['gm_id']) && $ee_options['gm_id'] != "" ? $ee_options['gm_id'] : "";
$google_ads_id = isset($ee_options['google_ads_id']) && $ee_options['google_ads_id'] != "" ? $ee_options['google_ads_id'] : "";
$fb_pixel_id = isset($ee_options['fb_pixel_id']) && $ee_options['fb_pixel_id'] != "" ? $ee_options['fb_pixel_id'] : "";
$showfeaturepopup = "";
if ($ga4_id == "" || $google_ads_id == "" || $fb_pixel_id == "") {
  $showfeaturepopup = "yes";
}
?>
<div class="convcard p-4 mt-0 rounded-3 shadow-sm">

  <?php if (isset($pixel_settings_arr[$subpage]['topnoti']) && $pixel_settings_arr[$subpage]['topnoti'] != "") { ?>
    <div class="alert d-flex align-items-cente p-0" role="alert">
      <div class="text-light conv-success-bg rounded-start d-flex">
        <span class="p-2 material-symbols-outlined align-self-center">verified</span>
      </div>
      <div class="p-2 w-100 rounded-end border border-start-0 shadow-sm conv-notification-alert bg-white">
        <div class="">
          <?php esc_html_e("Unlock the power of your own Google Tag Manager. Boost page speed, and customize events effortlessly.", "enhanced-e-commerce-for-woocommerce-store"); ?>
          <a class="conv-link-blue" target="_blank" href="<?php echo esc_url($TVC_Admin_Helper->get_conv_pro_link_adv("notice", "gtmsettings",  "", "linkonly")); ?>" class="conv_link_blue">
            <?php esc_html_e("Upgrade now for greater control and performance!", "enhanced-e-commerce-for-woocommerce-store"); ?>
          </a>
        </div>
      </div>
    </div>
  <?php } ?>

  <form id="gtmsettings_form">

    <div class="convpixsetting-inner-box mt-4">
      <h5 class="fw-normal mb-1">
        <?php esc_html_e("Select Google Tag Manager Container:", "enhanced-e-commerce-for-woocommerce-store"); ?>
      </h5>
      <?php
      $tracking_method = (isset($ee_options['tracking_method']) && $ee_options['tracking_method'] != "") ? $ee_options['tracking_method'] : "";
      $want_to_use_your_gtm = "";
      if ($tracking_method == "gtm") {
        $want_to_use_your_gtm = (isset($ee_options['want_to_use_your_gtm']) && $ee_options['want_to_use_your_gtm'] != "") ? $ee_options['want_to_use_your_gtm'] : "0";
      }
      $use_your_gtm_id = isset($ee_options['use_your_gtm_id']) ? $ee_options['use_your_gtm_id'] : "";
      ?>
      <div>
        <div class="py-1">
          <input type="radio" <?php echo esc_attr(($want_to_use_your_gtm == "0" || $want_to_use_your_gtm == "1") ? 'checked="checked"' : ''); ?> name="want_to_use_your_gtm" id="want_to_use_your_gtm_default" value="0">
          <label class="form-check-label ps-2" for="want_to_use_your_gtm_default">
            <?php esc_html_e("Conversios Global Container (GTM-K7X94DG)", "enhanced-e-commerce-for-woocommerce-store"); ?>
          </label>
        </div>
        <div class="py-1">
          <input type="radio" name="want_to_use_your_gtm" id="want_to_use_your_gtm_own" value="0" readonly disabled class="align-top">
          <label class="form-check-label ps-2" for="want_to_use_your_gtm_own">
            <?php esc_html_e("Use Your Google Tag Manager Container", "enhanced-e-commerce-for-woocommerce-store"); ?>
            <span class="conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
              <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
              <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </span>
            <br />
            <small>(Faster Page Speed & Tag Customization)</small>
          </label>
        </div>

        <div id="use_your_gtm_id_box" class="use_your_gtm_id py-1 <?php echo esc_attr(($want_to_use_your_gtm == "0") || $want_to_use_your_gtm == "" ? 'd-none' : ''); ?>">
          <input type="hidden" class="form-control-lg display-6" name="use_your_gtm_id" id="use_your_gtm_id" value="<?php echo esc_attr($use_your_gtm_id); ?>">
        </div>

      </div>

      <div class="py-3">
        <h5 class="fw-normal mb-1">
          <?php esc_html_e("Select User Roles to Disable Tracking:", "enhanced-e-commerce-for-woocommerce-store"); ?>
        </h5>
        <select class="form-select mb-3 selecttwo w-100" id="conv_disabled_users" name="conv_disabled_users[]" multiple="multiple" data-placeholder="Select role">
          <?php foreach ($TVC_Admin_Helper->conv_get_user_roles() as $slug => $name) {
            $is_selected = "";
            if (!empty($ee_options['conv_disabled_users'])) {
              $is_selected = in_array($slug, $ee_options['conv_disabled_users']) ? "selected" : "";
            }
          ?>
            <option value="<?php echo esc_attr($slug); ?>" <?php echo esc_html($is_selected); ?>><?php echo esc_html($name); ?></option>
          <?php } ?>
        </select>
      </div>
      <input type="hidden" name="tracking_method" id="tracking_method" value="gtm">
    </div>
  </form>
</div>

<!-- Ecommerce Events -->
<div class="convcard p-4 mt-0 rounded-3 shadow-sm mt-3">
  <div class="row">
    <h5 class="fw-normal mb-1">
      <?php esc_html_e("Ecommerce Events", "enhanced-e-commerce-for-woocommerce-store"); ?>
      <span class="fw-400 text-color fs-12">
        <span class="text-color fs-6"> *</span> <span class="material-symbols-outlined fs-6" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Page view and purchase event tracking are available in free plan. For complete ecommerce tracking, upgrade to our pro plan">
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
        <?php esc_html_e("Select iten", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
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
        <?php esc_html_e("Add shipping info", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
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
        </span><?php esc_html_e(" Add payment Info", "enhanced-e-commerce-for-woocommerce-store"); ?>
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
      <span data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Available with Pro Plan">
        <span class="material-symbols-outlined lock-icon">
          lock
        </span>
        <?php esc_html_e("Begin checkout", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
    </div>
  </div>

  <div class="row pt-3">
    <div class="col-md-12">
      <h5 class="fw-bold-500 conv-recommended-text" style="font-size: 17px;">
        <?php esc_html_e("Recommended:", "enhanced-e-commerce-for-woocommerce-store"); ?>
      </h5>
      <h5 class="fw-normal mb-1">
        <?php esc_html_e("For complete ecommerce tracking and user browsing behavior for your Woo Shop, switch to our Starter plan.", "enhanced-e-commerce-for-woocommerce-store"); ?>
        <span class="align-middle conv-link-blue ms-2 fw-bold-500 upgradetopro_badge" data-bs-toggle="modal" data-bs-target="#upgradetopromodal">
          <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
          <?php esc_html_e("UPGRADE TO PRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
        </span>
      </h5>
    </div>
  </div>
</div>

<!-- Feature adaption modal -->
<div class="modal fade" id="conv_featureadptmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="conv_featureadptmodalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      <div class="modal-body p-2 py-5">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12 text-center">
              <img class="mb-3" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gtm_logo.png'); ?>">
              <h4 class="fw-normal fs-4 my-3">
                <?php esc_html_e("Successfully Connected", "enhanced-e-commerce-for-woocommerce-store"); ?>
              </h4>
              <div class="fs-6 lh-3 lh-base">
                <span class="fw-bold-500">
                  <?php esc_html_e("Conversios Container - GTM-K7X94DG", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </span>
                <span>
                  <?php esc_html_e("has been successfully connected with", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </span>
                <br>
                <span>
                  <?php esc_html_e("your store. Connect respective channels to automate ecommerce events and", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </span>
                <br>
                <span>
                  <?php esc_html_e("conversion tracking for your store.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </span>
              </div>

            </div>
          </div>
          <div class="row mt-5 featureadptboxes">
            <div class="col-md-4 text-center py-3 <?php echo $ga4_id == "" ? "" : "convboxactive"; ?>">
              <div class="convborder p-2 rounded">
                <img class="mb-3 img-32" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_ganalytics_logo.png'); ?>">
                <h4 class="fw-normal fs-6">
                  <?php esc_html_e("Google Analytics 4", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </h4>
                <p class="py-2 text-12px">
                  <?php esc_html_e("Start collecting all the ecommerce events & conversions for data driven decision making by adding Google Analytics 4 measurement id.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </p>
                <?php if ($ga4_id == "") { ?>
                  <a href="<?php echo esc_url("admin.php?page=conversios-google-analytics&subpage=gasettings"); ?>" class="btn w-100 conv-blue-bg text-white btn-lg fs-6">
                    <?php esc_html_e("Connect Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                  </a>
                <?php } else { ?>
                  <h4 class="fw-normal fs-6 d-flex justify-content-center pt-2">
                    <?php esc_html_e("Connected", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    <img class="align-self-center ps-2" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/icon/selected.png"); ?>" />
                  </h4>
                <?php } ?>
              </div>
            </div>

            <div class="col-md-4 text-center py-3 <?php echo $google_ads_id == "" ? "" : "convboxactive"; ?>">
              <div class="convborder p-2 rounded">
                <img class="mb-3 img-32" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_gads_logo.png'); ?>">
                <h4 class="fw-normal fs-6">
                  <?php esc_html_e("Google Ads Conversion Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </h4>
                <p class="py-2 text-12px">
                  <?php esc_html_e("Optimize and improve campaign sales by automating Google Ads Conversion Tracking.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </p>

                <?php if ($google_ads_id == "") { ?>
                  <a href="<?php echo esc_url("admin.php?page=conversios-google-analytics&subpage=gadssettings"); ?>" class="btn w-100 conv-blue-bg text-white btn-lg fs-6">
                    <?php esc_html_e("Connect Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                  </a>
                <?php } else { ?>
                  <h4 class="fw-normal fs-6 d-flex justify-content-center pt-2">
                    <?php esc_html_e("Connected", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    <img class="align-self-center ps-2" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/icon/selected.png"); ?>" />
                  </h4>
                <?php } ?>

              </div>
            </div>

            <div class="col-md-4 text-center py-3 <?php echo $fb_pixel_id == "" ? "" : "convboxactive"; ?>">
              <div class="convborder p-2 rounded">
                <img class="mb-3 img-32" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_fb_logo.png'); ?>">
                <h4 class="fw-normal fs-6">
                  <?php esc_html_e("Facebook Pixel", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </h4>
                <p class="py-2 text-12px">
                  <?php esc_html_e("Improve sales from FB ads and build remarketing audience by setting up FB pixel and FB Conversions API integration.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </p>

                <?php if ($fb_pixel_id == "") { ?>
                  <a href="<?php echo esc_url("admin.php?page=conversios-google-analytics&subpage=fbsettings"); ?>" class="btn w-100 conv-blue-bg text-white btn-lg fs-6">
                    <?php esc_html_e("Connect Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                  </a>
                <?php } else { ?>
                  <h4 class="fw-normal fs-6 d-flex justify-content-center pt-2">
                    <?php esc_html_e("Connected", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    <img class="align-self-center ps-2" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . "/admin/images/icon/selected.png"); ?>" />
                  </h4>
                <?php } ?>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Feature adaption modal End -->


<script>
  jQuery(function() {

    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })


    //jQuery("#conv_featureadptmodal").modal("show");
    jQuery("#upgradetopro_modal_link").attr("href", '<?php echo esc_url($TVC_Admin_Helper->get_conv_pro_link_adv("popup", "gtmsettings",  "conv-link-blue fw-bold", "linkonly")); ?>');
    // Conversios JS
    jQuery('input[type=radio][name=want_to_use_your_gtm]').change(function() {
      if (this.value == '0') {
        jQuery("#use_your_gtm_id_box").hide();
        jQuery("#use_your_gtm_id_box").addClass('d-none');
      } else if (this.value == '1') {
        jQuery("#use_your_gtm_id_box").show();
        jQuery("#use_your_gtm_id_box").removeClass('d-none');
      }
    });

    jQuery(document).on('change', 'form#gtmsettings_form', function() {
      var want_to_use_your_gtm = jQuery('input[type=radio][name=want_to_use_your_gtm]:checked').val();
      var use_your_gtm_id = jQuery('#use_your_gtm_id').val();

      if (want_to_use_your_gtm == 1 && use_your_gtm_id == "") {
        jQuery('#use_your_gtm_id').addClass("conv-border-danger");
        jQuery(".conv-btn-connect").addClass("conv-btn-connect-disabled");
        jQuery(".conv-btn-connect").removeClass("conv-btn-connect-enabled-google");
        jQuery(".conv-btn-connect").text('Save');
      } else {
        jQuery('#use_your_gtm_id').removeClass("conv-border-danger");
        jQuery(".conv-btn-connect").removeClass("conv-btn-connect-disabled");
        jQuery(".conv-btn-connect").addClass("conv-btn-connect-enabled-google");
        jQuery(".conv-btn-connect").text('Save');
      }

    });

    jQuery(document).on("click", ".conv-btn-connect-enabled-google", function() {
      conv_change_loadingbar("show");
      jQuery(this).addClass('disabled');
      var want_to_use_your_gtm = jQuery('input[type=radio][name=want_to_use_your_gtm]:checked').val();
      var use_your_gtm_id = jQuery('#use_your_gtm_id').val();
      var conv_disabled_users_arr = jQuery("#conv_disabled_users").val();
      var conv_disabled_users = conv_disabled_users_arr.length ? conv_disabled_users_arr : [""];
      var tracking_method = jQuery('#tracking_method').val();

      jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: tvc_ajax_url,
        data: {
          action: "conv_save_pixel_data",
          pix_sav_nonce: "<?php echo esc_html(wp_create_nonce('pix_sav_nonce_val')); ?>",
          conv_options_data: {
            want_to_use_your_gtm: want_to_use_your_gtm,
            use_your_gtm_id: use_your_gtm_id,
            conv_disabled_users: conv_disabled_users,
            tracking_method: tracking_method
          },
          conv_options_type: ["eeoptions", "eeapidata"],
        },
        beforeSend: function() {
          jQuery(".conv-btn-connect-enabled-google").text("Saving...");
        },
        success: function(response) {
          var user_modal_txt = "Conversios Container - GTM-K7X94DG";
          if (want_to_use_your_gtm == "1") {
            user_modal_txt = "Your own GTM Container - " + use_your_gtm_id;
          }
          if (response == "0" || response == "1") {
            jQuery(".conv-btn-connect-enabled-google").text("Save");
            <?php if ($showfeaturepopup == "yes") { ?>
              jQuery("#conv_featureadptmodal").modal("show");
            <?php } else { ?>
              jQuery("#conv_save_success_txt").html('Congratulations, you have successfully connected your <br> Google Tag Manager account with <br> ' + user_modal_txt);
              jQuery("#conv_save_success_modal").modal("show");
            <?php } ?>

            conv_change_loadingbar("hide");
          }

        }
      });
    });

  });
</script>