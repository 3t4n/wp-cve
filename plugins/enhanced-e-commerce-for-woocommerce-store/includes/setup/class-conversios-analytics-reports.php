<?php
/**
 * @since      4.1.4
 * Description: Conversios Onboarding page, It's call while active the plugin
 */
if (!class_exists('Conversios_Analytics_Reports')) {
  class Conversios_Analytics_Reports
  {
    protected $screen;
    protected $TVC_Admin_Helper;
    protected $CustomApi;
    protected $subscription_id;
    protected $ga_traking_type;
    protected $ga_currency;
    protected $ga_currency_symbols;
    protected $ga4_measurement_id;
    protected $ga4_analytic_account_id;
    protected $ga4_property_id;
    protected $subscription_data;
    protected $plan_id = 1;
    protected $is_need_to_update_api_data_wp_db = false;
    protected $pro_plan_site;
    protected $report_data;
    protected $notice;
    protected $google_ads_id;
    //protected $connect_url;
    protected $g_mail;
    protected $is_refresh_token_expire;
    protected $ga_swatch;
    protected $sch_email_toggle_check;
    protected $sch_custom_email;
    protected $sch_email_frequency;
    protected $ee_options;
    protected $ga_currency_tmp;
    protected $is_ecomm_survey;
    protected $is_ai_unlocked;
    protected $promptLimit;
    protected $promptUsed;
    protected $last_fetched_prompt_date;
    protected $TVC_Admin_DB_Helper;
    protected $measurement_id;
    protected $aiArr;
    protected $aiMainArr;
    protected $todayAiDate01;

    public function __construct()
    {
      $this->ga_swatch = (isset($_GET['ga_type'])) ? sanitize_text_field($_GET['ga_type']) : "ga4";
      $this->TVC_Admin_Helper = new TVC_Admin_Helper();
      $this->TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
      $this->CustomApi = new CustomApi();
      //$this->connect_url =  $this->TVC_Admin_Helper->get_custom_connect_url(admin_url() . 'admin.php?page=conversios');
      $this->subscription_id = $this->TVC_Admin_Helper->get_subscriptionId();
      // update API data to DB while expired token

      if (isset($_GET['subscription_id']) && sanitize_text_field($_GET['subscription_id']) == $this->subscription_id) {
        if (isset($_GET['g_mail']) && sanitize_email($_GET['g_mail'])) {
          $this->TVC_Admin_Helper->update_subscription_details_api_to_db();
        }
      } else if (isset($_GET['subscription_id']) && sanitize_text_field($_GET['subscription_id'])) {
        $this->notice = esc_html__("You tried signing in with different email. Please try again by signing it with the email id that you used to set up the plugin earlier.", "enhanced-e-commerce-for-woocommerce-store");
      }
      $this->is_refresh_token_expire = false; //$this->TVC_Admin_Helper->is_refresh_token_expire();
      $this->subscription_data = $this->TVC_Admin_Helper->get_user_subscription_data();
      $this->pro_plan_site = esc_url($this->TVC_Admin_Helper->get_pro_plan_site() . '?utm_source=EE+Plugin+User+Interface&utm_medium=dashboard&utm_campaign=Upsell+at+Conversios');
      if (isset($this->subscription_data->plan_id) && !in_array($this->subscription_data->plan_id, array("1"))) {
        $this->plan_id = $this->subscription_data->plan_id;
      }
      if (isset($this->subscription_data->google_ads_id) && $this->subscription_data->google_ads_id != "") {
        $this->google_ads_id = $this->subscription_data->google_ads_id;
      }

      if ($this->subscription_id != "") {
        $this->ga_currency_tmp="";
        $this->g_mail = sanitize_email(get_option('ee_customer_gmail'));
        $this->ga_traking_type = $this->subscription_data->tracking_option; // UA,GA4,BOTH
        if ($this->ga_traking_type == "GA4") {
          $this->ga_swatch = "ga4";
        }
        if ($this->is_refresh_token_expire == false) {
          if ($this->ga_swatch == "ga4" || $this->ga_swatch == "") {
            $this->ga4_measurement_id = $this->subscription_data->measurement_id; //GA4 ID
            $this->ga4_analytic_account_id = $this->subscription_data->ga4_analytic_account_id; //GA4 ID
            $this->set_analytics_get_ga4_property_id();
          }
        }
        $this->todayAiDate01 = new DateTime(gmdate('Y-m-d H:i:s'));
        $this->ee_options = unserialize(get_option('ee_options'));
        //echo "<pre>"; print_r($this->ee_options); echo "</pre>";
        $this->sch_email_toggle_check = isset($this->ee_options['sch_email_toggle_check']) ? sanitize_text_field($this->ee_options['sch_email_toggle_check']) : '0';
        $this->sch_custom_email = isset($this->ee_options['sch_custom_email']) ? sanitize_text_field($this->ee_options['sch_custom_email']) : '';
        $this->sch_email_frequency = isset($this->ee_options['sch_email_frequency']) ? sanitize_text_field($this->ee_options['sch_email_frequency']) : 'Weekly';
        
        $this->ga_currency = isset($this->ee_options['ecom_reports_ga_currency']) ? sanitize_text_field($this->ee_options['ecom_reports_ga_currency']) : '';
        if($this->ga_currency !=""){
          $this->ga_currency_symbols = $this->TVC_Admin_Helper->get_currency_symbols($this->ga_currency);
        } 
        $this->is_ecomm_survey = isset($this->ee_options['is_ecomm_survey']) ? sanitize_text_field($this->ee_options['is_ecomm_survey']) : '0';
        $this->is_ai_unlocked = isset($this->ee_options['is_ai_unlocked']) ? sanitize_text_field($this->ee_options['is_ai_unlocked']) : '0';
        $this->promptLimit = isset($this->ee_options['promptLimit']) ? sanitize_text_field($this->ee_options['promptLimit']) : '10';
        $this->promptUsed = isset($this->ee_options['promptUsed']) ? sanitize_text_field($this->ee_options['promptUsed']) : '0';
        $this->last_fetched_prompt_date = isset($this->ee_options['last_fetched_prompt_date']) ? sanitize_text_field($this->ee_options['last_fetched_prompt_date']) : '';
        $this->measurement_id = isset($this->ee_options['gm_id']) ? sanitize_text_field($this->ee_options['gm_id']) : '';
        if($this->is_ai_unlocked == "0"){
            $this->create_prompts_table(); //check if table exists if not create one
        }else{
            //fetch data from ai reports data table
            $this->aiMainArr = $this->TVC_Admin_DB_Helper->tvc_get_results('ee_ai_reportdata');
            if(!empty($this->aiMainArr)){
                $this->aiArr = array();
                foreach($this->aiMainArr as $allElements){
                    $key = $allElements->prompt_key;
                    $value = $allElements->ai_response;
                    $last_prompt_date = $allElements->last_prompt_date;
                    $this->aiArr[$key]['value'] = $value;
                    $this->aiArr[$key]['last_prompt_date'] = $last_prompt_date;
                }
            }
        }
      } else {
        wp_redirect("admin.php?page=conversios-google-analytics");
        exit;
      }
      $this->includes();
      $this->load_html();
    }
    public function includes()
    {
      if (!class_exists('CustomApi.php')) {
        require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
      }
    }
    public function create_prompts_table(){
        global $wpdb;
        $tablename = esc_sql($wpdb->prefix . "ee_ai_reportdata");
        $sql_create = "CREATE TABLE `$tablename` (  `id` int(11) NOT NULL AUTO_INCREMENT,
                                                      `subscription_id` int(11) NOT NULL,
                                                      `prompt_key` varchar(50) NOT NULL,
                                                      `ai_response` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
                                                      `report_cat` varchar(50) NOT NULL,
                                                      `last_prompt_date` datetime DEFAULT NULL,
                                                      `created_date` datetime NOT NULL,
                                                      `updated_date` datetime DEFAULT NULL,
                                                      `is_delete` int(11) Null,
                                                      PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;";
        if (maybe_create_table($tablename, $sql_create)) {
        }
    }
    /* Need to For GA4 API call */
    public function set_analytics_get_ga4_property_id()
    {
      if (isset($this->subscription_data->ga4_property_id)  && $this->subscription_data->ga4_property_id != "") {
        $this->ga4_property_id = $this->subscription_data->ga4_property_id;
      } else {
        $data = array(
          "subscription_id" => sanitize_text_field($this->subscription_id),
          "ga4_analytic_account_id" => sanitize_text_field($this->ga4_analytic_account_id)
        );
        if ($this->ga4_analytic_account_id != null) {
          $api_rs = $this->CustomApi->analytics_get_ga4_property_id($data);
        }
        if (isset($api_rs->error) && $api_rs->error == '') {
          if (isset($api_rs->ga4_property_id) && $api_rs->ga4_property_id != "") {
            $this->ga4_property_id = $api_rs->ga4_property_id;
          }
        }
      }
    }

    public function load_html()
    {
        if( isset($_GET['page']) && $_GET['page'] !="" )
            do_action('conversios_start_html_' . sanitize_text_field($_GET['page']));
        if ($this->ga4_measurement_id != null || $this->google_ads_id != null ) {
        $this->current_html();
        $this->current_js();
        } else {
            ?>
            <script>
            jQuery(document).ready(function() {
                var notice =
                    "<p class='con-dashboard-msg'>To see the insignts dashbaord, connect your Google Analytics accounts.</p><a href='admin.php?page=conversios-google-analytics' class='con-dashboard-pp'>Connect Google Analytics</a>";
                if (notice != "") {
                    tvc_helper.tvc_alert("error", "You have not added Google Analytics accounts.", notice);
                }
            });
            </script>
            <?php
        }
        if( isset($_GET['page']) && $_GET['page'] !="" )
            do_action('conversios_end_html_' . sanitize_text_field($_GET['page']));
    }

    /**
     * Page custom js code
     *
     * @since    4.1.4
     */
    public function current_js() {
      ?>
<script>
function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(email)) {
        return false;
    } else {
        return true;
    }
}

function save_currency_local(ga_currency) {
    var selected_vals = {};
    selected_vals['ecom_reports_ga_currency'] = ga_currency;
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: tvc_ajax_url,
        data: {
            action: "conv_save_pixel_data",
            pix_sav_nonce: "<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')); ?>",
            conv_options_data: selected_vals,
            conv_options_type: ["eeoptions"]
        },
        beforeSend: function() {},
        success: function(response) {
            //console.log('currency saved', response);
        }
    });
}

function save_local_data(email_toggle_check, custom_email, email_frequency) {
          var selected_vals = {};
          selected_vals['sch_email_toggle_check'] = email_toggle_check;
          selected_vals['sch_custom_email'] = custom_email;
          selected_vals['sch_email_frequency'] = email_frequency;
          jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
              action: "conv_save_pixel_data",
              pix_sav_nonce: "<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')); ?>",
              conv_options_data: selected_vals,
              conv_options_type: ["eeoptions"]
            },
            beforeSend: function() {},
            success: function(response) {
              //console.log('saved');
            }
          });
        }
    function compareDates(date1, date2) {
      const jsDate1 = new Date(
        date1.split("-")[2],
        date1.split("-")[1] - 1,
        date1.split("-")[0]
      );
      const jsDate2 = new Date(
        date2.split("-")[2],
        date2.split("-")[1] - 1,
        date2.split("-")[0]
      );
      if (jsDate1 > jsDate2) {
        return 1;
      } else if (jsDate1 < jsDate2) {
        return -1;
      } else {
        return 0;
      }
    }
jQuery(document).ready(function() {
    /**
     * daterage script
     **/
    var notice = '<?php echo esc_html($this->notice); ?>';
    if (notice != "") {
        tvc_helper.tvc_alert("error", "Email error", notice);
    }
    var g_mail = '<?php echo esc_attr($this->g_mail); ?>';
    var is_sch_email = '<?php echo esc_attr($this->sch_email_toggle_check); ?>';
    if (is_sch_email == '0') {
        jQuery('#schedule_form_btn_set').show();
        jQuery('#schedule_form_btn_raw').hide();
    } else {
        jQuery('#schedule_form_btn_set').hide();
        jQuery('#schedule_form_btn_raw').show();
    }
    var plan_id = '<?php echo esc_attr($this->plan_id); ?>';
    var ga_swatch = '<?php echo esc_attr($this->ga_swatch); ?>';
    var is_refresh_token_expire = '<?php echo esc_attr($this->is_refresh_token_expire); ?>';
    is_refresh_token_expire = (is_refresh_token_expire == "") ? false : true;
    //console.log(is_refresh_token_expire);
    var start = moment().subtract(30, 'days');
    var end = moment();

    function cb(start, end) {
        var start_date = start.format('DD/MM/YYYY') || 0,
            end_date = end.format('DD/MM/YYYY') || 0;
        jQuery('span.daterangearea').html(start_date + ' - ' + end_date);
        var data = {
            action: 'get_google_analytics_reports',
            subscription_id: '<?php echo esc_attr($this->subscription_id); ?>',
            plan_id: plan_id,
            ga_swatch: ga_swatch,
            ga_traking_type: '<?php echo esc_attr($this->ga_traking_type); ?>',
            property_id: '<?php echo esc_attr($this->ga4_property_id); ?>',
            ga4_analytic_account_id: '<?php echo esc_attr($this->ga4_analytic_account_id); ?>',
            ga_currency: '<?php echo esc_attr($this->ga_currency); ?>',
            plugin_url: '<?php echo esc_url(ENHANCAD_PLUGIN_URL); ?>',
            start_date: jQuery.trim(start_date.replace(/\//g, "-")),
            end_date: jQuery.trim(end_date.replace(/\//g, "-")),
            g_mail: g_mail,
            google_ads_id: '<?php echo esc_attr($this->google_ads_id); ?>',
            conversios_nonce: '<?php echo esc_js(wp_create_nonce('conversios_nonce')); ?>',
            domain: '<?php echo esc_attr(get_site_url()); ?>',
            measurement_id: '<?php echo esc_attr($this->measurement_id);?>'
        };
        // Call API
        if (notice == "" && is_refresh_token_expire == false) {
            tvc_helper.get_google_analytics_reports(data);
            /* save all reports data */
            let promptUsed_chk = '<?php echo esc_js($this->promptUsed); ?>';
            let promptLimit_chk = '<?php echo esc_js($this->promptLimit); ?>';
            if(promptLimit_chk > promptUsed_chk) {
                var last_report_date = '<?php echo esc_js($this->last_fetched_prompt_date); ?>';
                //console.log("last fetched date", last_report_date);
                let currDate = moment().format("DD-MM-YYYY");
                //console.log("curr date", currDate);
                if(last_report_date == ""){
                    tvc_helper.save_all_reports(data, '<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')); ?>');
                }else{
                    const dateRes = compareDates(currDate, last_report_date);
                    if (dateRes === 1) {
                        //console.log("date 1 > date 2 here");
                        tvc_helper.save_all_reports(data, '<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')); ?>');
                    }
                }
            }
        }

        if (notice == "" && is_refresh_token_expire == true && g_mail != "") {
            tvc_helper.tvc_alert("error", "",
                "It seems the token to access your Google Analytics account is expired. Sign in with " +
                g_mail +
                " again to reactivate the token. <span class='google_connect_url'>Click here..</span>");
        } else if (notice == "" && is_refresh_token_expire == true) {
            tvc_helper.tvc_alert("error", "",
                "It seems the token to access your Google Analytics account is expired. Sign in with the connected email again to reactivate the token. <span class='google_connect_url'>Click here..</span>"
            );
        }
    }
    jQuery('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        maxDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                .endOf('month')
            ]
        }
    }, cb);
    cb(start, end);

    //upgrds plan popup

    jQuery(".dashtpleft-btn, .dshtpdaterange").on("click", function() {
        var slug = $(this).text();
        if (slug != 'Download PDF' && slug != 'Smart Emails') {
            str_menu = 'report_range';
        } else {
            str_menu = slug.replace(/\s+/g, '_').toLowerCase();
        }
        //user_tracking_data('click', 'null', 'conversios', str_menu);
        jQuery('.upgradsbscrptn-pp').addClass('showpopup');
        jQuery('body').addClass('scrlnone');
        $('.ppupgrdbtnwrap a').attr('data-el', str_menu);
    });

    jQuery(".ppupgrdbtnwrap .blueupgrdbtn").on("click", function() {
        var el = $(this).attr("data-el");
        //user_tracking_data('upgrade_now', 'null', 'conversios', el);
    });

    jQuery(".prochrtcntn .blueupgrdbtn").on("click", function() {
        var slug = $(this).parent().find('.prochrtitle').text();
        str_menu = slug.replace(/\s+/g, '_').toLowerCase();
        //user_tracking_data('upgrade_now', 'null', 'conversios', str_menu);
    });

    jQuery('body').click(function(evt) {
        if (jQuery(evt.target).closest('.upgrdsbrs-btn, .upgradsbscrptnpp-cntr').length) {
            return;
        }
        jQuery('.upgradsbscrptn-pp').removeClass('showpopup');
        jQuery('body').removeClass('scrlnone');
    });
    jQuery(".clsbtntrgr").on("click", function() {
        jQuery(this).closest('.pp-modal').removeClass('showpopup');
        jQuery('body').removeClass('scrlnone');
    });
    //upcoming_featur popup
    jQuery(".upcoming-featur-btn").on("click", function() {
        jQuery('.upcoming_featur-btn-pp').addClass('showpopup');
        jQuery('body').addClass('scrlnone');
    });
    jQuery('body').click(function(evt) {
        if (jQuery(evt.target).closest('.upcoming-featur-btn, .upgradsbscrptnpp-cntr').length) {
            return;
        }
        jQuery('.upcoming_featur-btn-pp').removeClass('showpopup');
        jQuery('body').removeClass('scrlnone');
    });
    
    jQuery(".upgrdsbrs-btn").on("click", function() {
        jQuery('#upgradsbscrptn').addClass('showpopup');
        jQuery('body').addClass('scrlnone');
    });
    /**
     * Custom js code for API call
     **/

    //var start_date = moment().subtract(5, 'months').format('YYYY-MM-DD');
    //var end_date = moment().subtract(1, 'days').format('YYYY-MM-DD');


    jQuery(".prmoclsbtn").on("click", function() {
        jQuery(this).parents('.promobandtop').fadeOut()
    });

    /**
     * table responcive
     **/
    jQuery('.mbl-table').basictable({
        breakpoint: 768
    });

    /**
     * Convesios custom script
     */
    //Step-0
    jQuery("#tvc_popup_box").on("click", 'span.google_connect_url', function() {
        location.href = '<?php echo esc_url(admin_url("admin.php?page=conversios-google-analytics")); ?>';
    });

          /*schedule email form submit event listner*/
          jQuery("#schedule_email_save_config").on("click", function() {
            let email_toggle_check = '0'; //default
            if (jQuery("#email_toggle_btn").prop("checked")) {
              email_toggle_check = '0'; //enabled
            } else {
              email_toggle_check = '1'; //disabled
            }
            let custom_email = '<?php echo esc_attr($this->g_mail); ?>';
            let email_frequency = "Weekly";
            let email_frequency_final = "7_day";
            var data = {
              "action": "set_email_configurationGA4",
              "subscription_id": '<?php echo esc_attr($this->subscription_id); ?>',
              "is_disabled": email_toggle_check,
              "custom_email": custom_email,
              "email_frequency": email_frequency_final,
              "conversios_nonce": '<?php echo esc_js(wp_create_nonce('conversios_nonce')); ?>'
            };
            jQuery.ajax({
              type: "POST",
              dataType: "json",
              url: tvc_ajax_url,
              data: data,
              beforeSend: function() {
                jQuery("#loadingbar_blue").show();
              },
              success: function(response) {
                //console.log("source response", response);
                if (response.error == false) {
                  jQuery("#err_sch_msg").hide();
                  jQuery("#loadingbar_blue").hide();
                  jQuery('#schedule_email_modal').modal('hide');
                  jQuery('#sch_ack_msg').show();
                  jQuery("#schedule_email_save_config").html('Save');
                  //local storage
                  save_local_data(email_toggle_check, custom_email, email_frequency);
                  if (email_toggle_check == '0') {
                    jQuery('#schedule_form_btn_set').show();
                    jQuery('#schedule_form_btn_raw').hide();
                  } else {
                    jQuery('#schedule_form_btn_set').hide();
                    jQuery('#schedule_form_btn_raw').show();
                  }
                } else {
                  jQuery("#err_sch_msg").show();
                  jQuery("#loadingbar_blue").hide();
                }
                setTimeout(
                  function() {
                    jQuery("#sch_ack_msg").hide();
                  }, 8000);
              }
            });
          });
          jQuery("#sch_ack_msg_close").on("click", function() {
            jQuery("#sch_ack_msg").hide();
          });
          jQuery('#email_toggle_btn').change(function() {
            if (jQuery(this).prop("checked")) {
              jQuery("#email_toggle_btnLabel").addClass("convEmail_default_cls_enabled");
              jQuery("#email_toggle_btnLabel").removeClass("convEmail_default_cls_disabled");
              jQuery("#email_frequency,#custom_email").attr("style","color: #2A2D2F !important");
              jQuery("#schedule_email_save_config").html('Save Changes');
            } else {
              jQuery("#email_toggle_btnLabel").addClass("convEmail_default_cls_disabled");
              jQuery("#email_toggle_btnLabel").removeClass("convEmail_default_cls_enabled");
              jQuery("#email_frequency,#custom_email").attr("style","color: #94979A !important");
              jQuery("#schedule_email_save_config").html('Save Changes');
            }
          });
    //set currency in local
    setTimeout(function() {
        var ga_currency = "<?php echo esc_attr($this->ga_currency); ?>";
        //console.log("ga_currency");
        if (ga_currency != "") {
            save_currency_local(ga_currency);
        }
    }, 8000);

    /* ai powered insights scripts */
    jQuery(".unlock_ai_insights").on("click", function() {
        //hide all initial ai sections from the page and show prompt section for all reports.
        jQuery(".initial_ai_sections").hide();
        jQuery(".advanced_ai_sections").show();
        //set flag for advanced sections
        var selected_vals = {};
        selected_vals['is_ai_unlocked'] = "1";
        selected_vals['promptLimit'] = "10";
        selected_vals['promptUsed'] = "0";
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: {
                action: "conv_save_pixel_data",
                pix_sav_nonce: "<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')); ?>",
                conv_options_data: selected_vals,
                conv_options_type: ["eeoptions"]
            },
            beforeSend: function() {},
            success: function(response) {
                //console.log('saved');
            }
        });

    });
    function displayTypingEffectnormal(text, element) {
        const delay = 100; // Adjust the typing speed (milliseconds per character)
        let index = 0;
        function type() {
            if (index < text.length) {
                element.append(text.charAt(index));
                index++;
                setTimeout(type, delay);
            }
        }
        type();
    }
    function displayTypingEffect(text, element) {
        const delay = 20; // Adjust the typing speed (milliseconds per character)
        let index = 0;
        function type() {
            if (index < text.length) {
                if(index == 0){
                    text = "<li>" + text;
                }
                element.innerHTML = text.substr(0, index + 1);
                element.empty().append(element.innerHTML); // Clear the div and append the new element
                index++;
                setTimeout(type, delay);
            }
        }
        type();
    }
    /* get prompt response from middleware */
    jQuery(".ai_prompts").on("click", function() {
        let destination = this.dataset.destination;
        let conv_prompt_key = this?.dataset?.key;
        let conv_type = this?.dataset.type;
        let ele_type = this?.dataset.ele_type;
        let ref_btn_id = this?.id;
        //console.log("api calling");
        if (conv_prompt_key == "" || destination == "" || conv_type== "" || ele_type == "") {
            return false;
        }
        if(ele_type == "button"){
            jQuery("#" + ref_btn_id).off("click");
        }
        let promptUsed = jQuery("#conv_ai_count").val();
        let promptLimit = jQuery("#conv_ai_limit").val();
        if(parseInt(promptLimit) <= parseInt(promptUsed)) { 
            jQuery('#' + destination).html('Prompt Limit reached.');
            if(ele_type != "button"){
                jQuery('#' + conv_type + '-' + conv_prompt_key).click();
                //console.log("id is",ref_btn_id);
                if(ref_btn_id != ""){
                    jQuery("#" + ref_btn_id).hide();
                }
            }
            return false;
        }
        var data = {
            "action": "generate_ai_response",
            "subscription_id": '<?php echo esc_attr($this->subscription_id); ?>',
            "key": conv_prompt_key,
            "domain": '<?php echo esc_attr(get_site_url()); ?>',
            "conversios_nonce": '<?php echo esc_js(wp_create_nonce('conversios_nonce')); ?>'
        };
        jQuery("#" + conv_type + "-pills-tabContent").hide();
        jQuery("#" + conv_type + "-robotyping-box").show();
        
        const loader_span = jQuery('.conv_loader_type');
        displayTypingEffectnormal("Generating Insights based on your analytics data...", loader_span);
        //ai_flag is setv
        jQuery.ajax({
            type: "POST",
            dataType: "json",
            url: tvc_ajax_url,
            data: data,
            success: function(response) {
                jQuery("#" + conv_type + "-pills-tabContent").show();
                jQuery("#" + conv_type + "-robotyping-box").hide();
                jQuery(".conv_loader_type").text("");
                if (response?.error == false && response?.data != "") {
                    //convert to lis then append
                    promptUsed = Number(promptUsed) + 1;
                    jQuery(".prompt_used_count").text(promptUsed);
                    jQuery("#conv_ai_count").val(promptUsed);
                    //save new prompt used in db
                    var selected_vals = {};
                    selected_vals['promptUsed'] = promptUsed;
                    jQuery.ajax({
                        type: "POST",
                        dataType: "json",
                        url: tvc_ajax_url,
                        data: {
                            action: "conv_save_pixel_data",
                            pix_sav_nonce: "<?php echo esc_js(wp_create_nonce('pix_sav_nonce_val')); ?>",
                            conv_options_data: selected_vals,
                            conv_options_type: ["eeoptions"]
                        },
                        beforeSend: function() {},
                        success: function(response) {
                            //console.log('new prompt used saved');
                        }
                    });
                    let newData = response?.data;
                    const responseDiv = jQuery('#' + destination);
                    displayTypingEffect(newData, responseDiv);
                    //jQuery('#' + destination).html('<li>'+ newData +'</li>');
                    if(ele_type != "button"){
                        jQuery('#' + conv_type + '-' + conv_prompt_key).click();
                        //console.log("id is",ref_btn_id);
                        if(ref_btn_id != ""){
                            jQuery("#" + ref_btn_id).hide();
                        }
                    }
                }else{
                    if(response?.error == true && response?.errors?.[0] == "Prompt limit reached."){
                        jQuery('#' + destination).html(response?.errors[0]);
                        if(ele_type != "button"){
                            jQuery('#' + conv_type + '-' + conv_prompt_key).click();
                        }
                    }else{
                        jQuery('#' + destination).html("Not enough analytics data please try again later.");
                        if(ele_type != "button"){
                            jQuery('#' + conv_type + '-' + conv_prompt_key).click();
                        }
                    }
                }
            }
        });
    });
        /* Add inputs for prompt suggestions */
        jQuery("#add_prompt_submit").on("click", function() {
            let pval = jQuery("#add_input_prompt").val(); 
            let c = jQuery(".remove_prompt").length;
            if ( c > 4) {
                return false;
            }
            if ( c == null || c == "" ) {
                c = 0;
            }
            if(pval == null || pval == ""){
                jQuery("#add_input_prompt").attr('style','border-color: red !important');    
                return false;
            }else{ 
                jQuery("#add_input_prompt").attr('style','border-color: #1085F1 !important');
            }
            jQuery("#edit-box-prompt").css('display', 'flex');
            c++;
            let edit_body = '<div id="edit_input_prompt' + c + '" style="display:flex;flex-direction:row;margin: 10px 0px;"><input type="text" class="form-control icontrol user_prompt_suggestions" placeholder="Give your suggestions" aria-label="Recipients username" aria-describedby="basic-addon2" value = "' + pval + '" maxlength="300" autocomplete="off" readonly><div type="submit" class="input-group-text edit remove_prompt" data-promptId="edit_input_prompt' + c + '"><span></span> Remove</div></div>';
            jQuery("#edit-box-prompt").append(edit_body);
            jQuery("#prompt_counter").val(c);
            jQuery("#add_input_prompt").val(""); 
    });
    //remove prompt
    jQuery(document).on('click', '.remove_prompt', function(){    
        let promptId = this.dataset.promptid;
            jQuery("#" + promptId).remove();
     });
     /* Save suggestions */
     jQuery("#save_suggestions").on("click", function() {
        jQuery('#err_sug_msg').hide();
        var valueArray = [];
        valueArray = jQuery('.user_prompt_suggestions').map(function() {
            return this.value;
        }).get();
        if(valueArray.length === 0){
            jQuery("#add_input_prompt").attr('style','border-color: red !important');  
            return false;
        }else{
            jQuery("#add_input_prompt").attr('style','border-color: #1085F1 !important');
        }
        //ajax call to save values
        jQuery.ajax({
            type: "POST",
            url: tvc_ajax_url,
            data: {
                action: "save_prompt_suggestions",
                data: valueArray,
                conversios_nonce: '<?php echo esc_js(wp_create_nonce('conversios_nonce')); ?>',
                subscription_id: '<?php echo esc_attr($this->subscription_id); ?>',
                domain: '<?php echo esc_attr(get_site_url()); ?>'
            },
            beforeSend: function() {
                jQuery("#loadingbar_blue2").css("display","block");
            },
            success: function(result) {
                if(result != ""){
                    result = JSON.parse(result);
                    if (result?.error == false && result?.data != "") {
                        jQuery('#suggestprompt').modal('hide');
                        jQuery('#sug_ack_msg').show();  
                    }else{
                        jQuery('#err_sug_msg').css("display","block");
                    }
                }else{
                    jQuery('#err_sug_msg').css("display","block");
                }
                jQuery("#loadingbar_blue2").hide();
                setTimeout(
                    function() {
                        jQuery("#sug_ack_msg").hide();
                    }, 8000);
            }
        });
    });
    jQuery("#sug_ack_msg_close").on("click", function() {
        jQuery("#sug_ack_msg").hide();
    });
});
</script>
<?php
    }
    protected function add_upgrdsbrs_btn_calss($featur_name)
    {
      if ($this->ga_swatch == "") {
        return "upgrdsbrs-btn";
      } else if ($featur_name != "") {
        $upcoming_featur  = array('download_pdf', 'schedule_email');
        if (in_array($featur_name, $upcoming_featur)) {
          return "upcoming-featur-btn";
        }
      }
    }

    /**
     * Main html code
     *
     * @since    4.1.4
     */
    public function current_html()
    {
      $current_page = admin_url("admin.php?page=conversios-analytics-reports");
      $ai_cls = 'style="display: none;"';
    ?>
<div class="dashbrdpage-wrap p-2">
    <div class="dflex align-items-center mt24 dshbrdtoparea">
    <div class="ga_swatch">
            <?php if ($this->ga_traking_type == "GA4" || $this->ga_traking_type == "BOTH") { ?>
            <span class="<?php echo esc_attr($this->ga_swatch == "ga4") ? "active" : ""; ?>" id="ga4"><a
                    href="<?php echo esc_url($current_page . "&ga_type=ga4"); ?>"><?php esc_html_e("GA4", "enhanced-e-commerce-for-woocommerce-store"); ?></a></span>
            <?php } ?>
        </div>
        <div class="dashtp-left">
            <button class="dashtpleft-btn <?php echo esc_attr($this->add_upgrdsbrs_btn_calss('download_pdf')); ?>"><img
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/download-icon.png'); ?>"
                    alt="" /><?php esc_html_e("Download PDF", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
            <button id="schedule_form_btn_set" style="display: none;" class="dashtpleft-btn" data-bs-toggle="modal"
                data-bs-target="#schedule_email_modal"><img
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/check_dashboard.png'); ?>"
                    alt="" /><?php esc_html_e("Smart Emails", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
            <button id="schedule_form_btn_raw" style="display: none;" class="dashtpleft-btn" data-bs-toggle="modal"
                data-bs-target="#schedule_email_modal"><img
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/clock-icon.png'); ?>"
                    alt="" /><?php esc_html_e("Smart Emails", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
        </div>
        <div class="dashtp-right">
            <div id="reportrange" class="dshtpdaterange">
                <div class="dateclndicn">
                    <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/claendar-icon.png'); ?>"
                        alt="" />
                </div>
                <span class="daterangearea report_range_val"></span>
                <div class="careticn"><img
                        src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/caret-down.png'); ?>" alt="" />
                </div>
            </div>
        </div>
    </div>
    <?php /* <!-- modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#onload">
        Launch demo modal
    </button> */ ?>
    <!--- dashboard summary section start -->
    <div class="wht-rnd-shdwbx mt24 dashsmry-wrap">
        <div class="dashsmry-item">
            <div class="dashsmrybx" id="s1_newUsers">
                <div class="dshsmrycattxt dash-smry-title">
                    <?php esc_html_e("New Users", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                <div class="dshsmrylrgtxt dash-smry-value">-</div>
                <div class="updownsmry dash-smry-compare-val">
                    %
                </div>
                <div class="dshsmryprdtxt">
                    <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
            </div>
            <div class="dashsmrybx mblsmry3bx" id="s1_transactions">
                <div class="dshsmrycattxt dash-smry-title">
                    <?php esc_html_e("Total Transactions", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                <div class="dshsmrylrgtxt dash-smry-value">-</div>
                <div class="updownsmry dash-smry-compare-val">
                    %
                </div>
                <div class="dshsmryprdtxt">
                    <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
            </div>
            <div class="dashsmrybx" id="s1_totalRevenue">
                <div class="dshsmrycattxt dash-smry-title">
                    <?php esc_html_e("Revenue", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                <div class="dshsmrylrgtxt dash-smry-value">-</div>
                <div class="updownsmry dash-smry-compare-val">
                    %
                </div>
                <div class="dshsmryprdtxt">
                    <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
            </div>
            <div class="dashsmrybx mblsmry3bx" id="s1_averagePurchaseRevenue">
                <div class="dshsmrycattxt dash-smry-title">
                    <?php esc_html_e("Avg. Order Value", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                <div class="dshsmrylrgtxt dash-smry-value">-</div>
                <div class="updownsmry dash-smry-compare-val">
                    %
                </div>
                <div class="dshsmryprdtxt">
                    <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
            </div>
        </div>
        <div class="dashsmry-item">
            <div class="dashsmrybx" id="s1_itemViews">
                <div class="dshsmrycattxt dash-smry-title">
                    <?php esc_html_e("Product Views", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                <div class="dshsmrylrgtxt dash-smry-value">-</div>
                <div class="updownsmry dash-smry-compare-val">
                    %
                </div>
                <div class="dshsmryprdtxt">
                    <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
            </div>
            <div class="dashsmrybx mblsmry3bx flwdthmblbx" id="s1_addToCarts">
                <div class="dshsmrycattxt dash-smry-title">
                    <?php esc_html_e("Added to Cart", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                <div class="dshsmrylrgtxt dash-smry-value">-</div>
                <div class="updownsmry dash-smry-compare-val">
                    %
                </div>
                <div class="dshsmryprdtxt">
                    <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
            </div>
            <div class="dashsmrybx" id="s1_sessions">
                <div class="dshsmrycattxt dash-smry-title">
                    <?php esc_html_e("Sessions", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                <div class="dshsmrylrgtxt dash-smry-value">-</div>
                <div class="updownsmry dash-smry-compare-val">
                    %
                </div>
                <div class="dshsmryprdtxt">
                    <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
            </div>
            <div class="dashsmrybx" id="s1_totalUsers">
                <div class="dshsmrycattxt dash-smry-title">
                    <?php esc_html_e("Total Users", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                <div class="dshsmrylrgtxt dash-smry-value">-</div>
                <div class="updownsmry dash-smry-compare-val">
                    %
                </div>
                <div class="dshsmryprdtxt">
                    <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
            </div>
        </div>
</div>
<!--- dashboard summary section end -->
<!--- dashboard ecommerce cahrt section start -->
<div class="mt24 dshchrtwrp ecomfunnchart">
    <div class="row">
        <div class="col50">
            <div class="chartbx ecomfunnchrtbx ecom-funn-chrt-bx">
                <div class="chartcntnbx">
                    <h5><?php esc_html_e("Ecommerce Conversion Funnel (# of Events)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </h5>
                    <div class="chartarea">
                        <canvas id="ecomfunchart" width="400" height="300"></canvas>
                    </div>
                    <hr>
                    <div class="ecomchartinfo">
                        <div class="ecomchrtinfoflex custom-scrollbar">
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel">
                                    <?php esc_html_e("Sessions", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                                <div class="chartpercarrow conversion_s1"></div>
                            </div>
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel">
                                    <?php esc_html_e("Product View", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                                <div class="chartpercarrow conversion_s2"></div>
                            </div>
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel">
                                    <?php esc_html_e("Add to Cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                                <div class="chartpercarrow conversion_s3"></div>
                            </div>
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel">
                                    <?php esc_html_e("Checkouts", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                                <div class="chartpercarrow conversion_s4"></div>
                            </div>
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel ecomm_lastRecord">
                                    <?php esc_html_e("Order Confirmation", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col50">
            <div class="chartbx ecomfunnchrtbx ecom-funn-chrt-bx checkoutfunnelga4">
                <div class="chartcntnbx">
                    <h5><?php esc_html_e("Ecommerce Checkout Funnel (# of Active Users)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </h5>
                    <div class="chartarea">
                        <canvas id="ecomcheckoutfunchart" width="400" height="300"></canvas>
                    </div>
                    <hr>
                    <div class="ecomchartinfo">
                        <div class="ecomchrtinfoflex custom-scrollbar">
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel">
                                    <?php esc_html_e("View Cart", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                                <div id="conversionRate_s1" class="chartpercarrow"></div>
                            </div>
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel">
                                    <?php esc_html_e("Begin Checkout", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                                <div id="conversionRate_s2" class="chartpercarrow"></div>
                            </div>
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel">
                                    <?php esc_html_e("Add Shipping Info", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                                <div id="conversionRate_s3" class="chartpercarrow"></div>
                            </div>
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel">
                                    <?php esc_html_e("Add Payment Info", "enhanced-e-commerce-for-woocommerce-store"); ?></div>
                                <div id="conversionRate_s4" class="chartpercarrow"></div>
                            </div>
                            <div class="ecomchartinfoitem">
                                <div class="ecomchartinfolabel ecomm_lastRecord">
                                    <?php esc_html_e("Purchase", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--- dashboard ecommerce cahrt section over -->

<!--- Checkout funnel section start -->
<div class="mt24 whiteroundedbx dshreport-sec">
    <div class="row dsh-reprttop">
        <div class="dshrprttp-left">
            <h4><?php esc_html_e("Ecommerce Checkout Funnel Report", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
        </div>
    </div>
    <div class="dashtablewrp checkout_funnel_report checkoutfunnelga4" id="checkout_funnel_report">
        <table class="dshreporttble mbl-table">
            <thead>
                <tr>
                    <th class="prdnm-cell"><?php esc_html_e("Step", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Event", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Active Users", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Conversion Rate from view_cart (%)", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Completion Rate (%)", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Drop-off (Users)", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Drop-off Rate (%)", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!--- Checkout funnel report section over -->
<!--- Order Performance section start -->
<div class="mt24 whiteroundedbx dshreport-sec">
    <div class="row dsh-reprttop">
        <div class="dshrprttp-left">
            <h4><?php esc_html_e("Order Performance Report", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
            <a class="viewallbtn upgrdsbrs-btn"><?php esc_html_e("View all", "enhanced-e-commerce-for-woocommerce-store"); ?> <img
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/blue-right-arrow.png'); ?>"
                    alt="" /></a>
        </div>
    </div>
    <div class="dashtablewrp order_performance_report" id="order_performance_report">
        <table class="dshreporttble mbl-table">
            <thead>
                <tr>
                    <th class="prdnm-cell">
                        <?php esc_html_e("Transaction Id", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Source / Medium", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Purchase Revenue", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                    <th><?php esc_html_e("Tax Amount", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                    <th><?php esc_html_e("Refund Amount", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                    <th><?php esc_html_e("Shipping Amount", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<!--- Order Performance section over -->
<!--- Product Performance section start -->
<div class="mt24 whiteroundedbx dshreport-sec">
    <div class="row dsh-reprttop">
        <div class="dshrprttp-left">
            <h4><?php esc_html_e("Product Performance Report", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
            <a class="viewallbtn upgrdsbrs-btn"><?php esc_html_e("View all", "enhanced-e-commerce-for-woocommerce-store"); ?> <img
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/blue-right-arrow.png'); ?>"
                    alt="" /></a>
            
        </div>
    </div>
    <div class="dashtablewrp product_performance_report" id="product_performance_report">
        <table class="dshreporttble mbl-table">
            <thead>
                <tr>
                    <th class="prdnm-cell">
                        <?php esc_html_e("Product Name", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Views", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Added to Cart", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <?php if( $this->ga_swatch != "ga4" ) { ?>
                    <th><?php esc_html_e("Orders", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <?php } ?>
                    <th><?php esc_html_e("Qty", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Revenue", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                    <?php if ($this->ga_swatch == "" || $this->ga_swatch == "ga3") { ?>
                    <th><?php esc_html_e("Avg Price", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        (<?php echo esc_html($this->ga_currency_symbols); ?>)</th>
                    <th><?php esc_html_e("Refund Amount", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        (<?php echo esc_html($this->ga_currency_symbols); ?>)</th>
                    <?php } ?>
                    <th><?php esc_html_e("Cart to details (%)", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Buy to details (%)", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <?php if($this->is_ai_unlocked == "0" ) { ?>
        <!-- smart insights -->
        <div class="dash-ga4 initial_ai_sections">
    <div class="card">
        <div class="card-body">
            <div class="card-content">
                <div class="smart-powered">
                    <a ><span> <img
                                src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/ai.png'); ?>"
                                alt="" class="img-fluid" /></span><?php esc_html_e("Powered Smart Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                </div>
                <h2><?php esc_html_e("Hurray!", "enhanced-e-commerce-for-woocommerce-store"); ?></h2>
                <h3><?php esc_html_e("New AI powered Smart Insights feature is live now", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                <div class="genrate-insights">
                    <a class="btn btn-dark common-btn unlock_ai_insights"><?php esc_html_e("Generate Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                </div>
            </div>
        </div>
        </div>
    </div>
        <?php 
        $ai_cls = 'style="display: none;"';
        }else{
           $ai_cls = 'style="display: block;"';
       } ?>
        <!-- prompt box -->
        <input type="hidden" id="conv_ai_count" value="<?php echo esc_attr($this->promptUsed); ?>">
        <input type="hidden" id="conv_ai_limit" value="<?php echo esc_attr($this->promptLimit); ?>">
        <div class="dash-ga4 advanced_ai_sections" <?php echo esc_attr($ai_cls); ?>>
            <div class="card smartprompt-card">
                <div class="card-body">
                    <div class="card-content">
                        <div class="smart-promptbox">
                            <div class="smart-powered">
                                <a><span><img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/ai.png'); ?>"
                                            alt="" class="img-fluid" /></span><?php esc_html_e("Powered Smart Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                            <div class="genrate-insights">
                                <a class="btn btn-dark common-btn" data-bs-toggle="modal"
                                    data-bs-target="#suggestprompt"> <span><img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/plus.png'); ?>"
                                            alt="" class="img-fluid" /></span><?php esc_html_e("Suggest Prompt", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                        </div>
                        <?php 
                            $ProductAiResult01 = isset($this->aiArr['ProductConv15']['value'])?$this->aiArr['ProductConv15']['value']:"";
                            $ProductAiResult02 = isset($this->aiArr['Productlowperform']['value'])?$this->aiArr['Productlowperform']['value']:"";
                            $pstatus_cls01="";
                            $pstatus_cls_val01="";
                            $pstatus_cls02="";
                            $pstatus_cls_val02="";
                            if($ProductAiResult01 != ""){
                                $pstatus_cls01="active";
                                $pstatus_cls_val01="show active";
                            }else if($ProductAiResult02 != ""){
                                $pstatus_cls02="active";
                                $pstatus_cls_val02="show active";
                            }
                        ?>
                        <div class="prompttab-box">
                            <span><?php esc_html_e("Select Prompt", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                <?php 
                                        $ProductAiDate01 = isset($this->aiArr['ProductConv15']['last_prompt_date'])?$this->aiArr['ProductConv15']['last_prompt_date']:"";
                                        if($ProductAiDate01 != ""){
                                            $ProductAiDate01 = new DateTime($ProductAiDate01);
                                            $interval = $this->todayAiDate01->diff($ProductAiDate01);
                                            $daysDifferencep01 = $interval->days;
                                            $btn_cls_promptp01 = '';
                                        }else{
                                            $daysDifferencep01 ='-1';
                                            $btn_cls_promptp01 = 'ai_prompts';
                                        } ?>
                                    <button class="nav-link <?php echo esc_attr($pstatus_cls01); ?> <?php echo esc_attr($btn_cls_promptp01); ?>" id="product-ProductConv15" data-bs-toggle="pill"
                                        data-bs-target="#product-prompt-tab1" type="button" role="tab" data-ele_type="button" data-key="ProductConv15" data-destination="product_prompt_tab1_ul" data-type="product"
                                        aria-controls="pills-home" aria-selected="true"><?php esc_html_e("To increase conversions by 15%", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
                                        <?php if( $daysDifferencep01 >= '1' ) { ?>
                                        <div id="product01-refresh-btn" class="refresh-btn ai_prompts" data-key="ProductConv15" data-destination="product_prompt_tab1_ul" data-type="product" data-ele_type="div">
                                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/refresh_ai.png'); ?>"
                                                alt="refresh" class="img-fluid" />
                                                <div class="tool-tip">
                                                    <p><?php echo esc_html_e("Refresh to generate new insights", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                                </div>
                                        </div>
                                        <?php } ?>
                                </li>
                                <li class="nav-item" role="presentation">
                                <?php 
                                    $ProductAiDate02 = isset($this->aiArr['Productlowperform']['last_prompt_date'])?$this->aiArr['Productlowperform']['last_prompt_date']:"";
                                    if($ProductAiDate02 != ""){
                                        $ProductAiDate02 = new DateTime($ProductAiDate02);
                                        $interval = $this->todayAiDate01->diff($ProductAiDate02);
                                        $daysDifferencep02 = $interval->days;
                                        $btn_cls_promptp02 = '';
                                    }else{
                                        $daysDifferencep02 ='-1';
                                        $btn_cls_promptp02 = 'ai_prompts';
                                    } ?>
                                    <button class="nav-link <?php echo esc_attr($pstatus_cls02); ?> <?php echo esc_attr($btn_cls_promptp02); ?>" id="product-Productlowperform" data-bs-toggle="pill"
                                        data-bs-target="#product-prompt-tab2" type="button" role="tab" data-key="Productlowperform" data-destination="product_prompt_tab2_ul" data-type="product" data-ele_type="button"
                                        aria-controls="pills-profile" aria-selected="false"><?php esc_html_e("Identify low performing products", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
                                        <?php if( $daysDifferencep02 >= '1' ) { ?>
                                        <div id="product02-refresh-btn" class="refresh-btn ai_prompts" data-key="Productlowperform" data-destination="product_prompt_tab2_ul" data-type="product" data-ele_type="div">
                                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/refresh_ai.png'); ?>"
                                                alt="refresh" class="img-fluid" />
                                                <div class="tool-tip">
                                                    <p><?php echo esc_html_e("Refresh to generate new insights", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                                </div>
                                        </div>
                                        <?php } ?>
                                </li>
                            </ul>
                            <div class="tab-content" id="product-pills-tabContent">
                                <div class="tab-pane fade <?php echo esc_attr($pstatus_cls_val01); ?>" id="product-prompt-tab1" role="tabpanel"
                                    aria-labelledby="pills-home-tab" tabindex="0">
                                    <ul id="product_prompt_tab1_ul" class="listing">
                                        <?php if($ProductAiResult01 != ""){ ?>
                                        <li><?php echo wp_kses_post($ProductAiResult01); ?></li>
                                        <?php }else{ ?>
                                            <?php esc_html_e("No data available, Click Refresh to generate new insights.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        <?php  } ?>
                                    </ul>
                                </div>
                                <div class="tab-pane fade <?php echo esc_attr($pstatus_cls_val02); ?>" id="product-prompt-tab2" role="tabpanel"
                                    aria-labelledby="pills-profile-tab" tabindex="0">
                                    <ul id="product_prompt_tab2_ul" class="listing">
                                        <?php if($ProductAiResult02 != ""){ ?>
                                        <li><?php echo wp_kses_post($ProductAiResult02); ?></li>
                                        <?php }else{ ?>
                                            <?php esc_html_e("No data available, Click Refresh to generate new insights.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        <?php  } ?>
                                    </ul>
                                </div>
                            </div>
                            <div id="product-robotyping-box" class="robotyping-box" style="display: none;">
                                <div class="ai-robot">
                                    <video autoplay loop muted height="150" width="150">
                                        <source src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/airobot.mp4'); ?>" type="video/mp4">
                                    </video>    
                                </div>
                                <div class="ai-typing">
                                    <h2><span class="conv_loader_type"></span></h2>
                                </div>
                            </div>
                            <div class="response-box">
                                <div class="response-validity">
                                    <p><span><?php echo esc_html_e("Prompt Limit : ", "enhanced-e-commerce-for-woocommerce-store"); ?></span><span
                                            class="prompt_used_count"><?php echo esc_html($this->promptUsed); ?></span><span>/<?php echo esc_html($this->promptLimit); ?></span>
                                    </p>
                                    <p class="response-note"><span><?php echo esc_html_e("*Insights generated based on your last 45 days of google analytics data.","enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--- Product Performance section over -->

<!--- Source Performance Report section start -->
<div class="mt24 whiteroundedbx dshreport-sec">
    <div class="row dsh-reprttop">
        <div class="dshrprttp-left">
            <h4><?php esc_html_e("Source/Medium Performance Report", "enhanced-e-commerce-for-woocommerce-store"); ?>
            </h4>
            <a class="viewallbtn upgrdsbrs-btn"><?php esc_html_e("View all", "enhanced-e-commerce-for-woocommerce-store"); ?> <img
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/blue-right-arrow.png'); ?>"
                    alt="" /></a>
        </div>
    </div>
    <div class="dashtablewrp medium_performance_report" id="medium_performance_report">
        <table class="dshreporttble mbl-table">
            <thead>
                <tr>
                    <th class="prdnm-cell">
                        <?php esc_html_e("Source/Medium", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <?php if ($this->ga_swatch == "" || $this->ga_swatch == "ga3") { ?>
                    <th><?php esc_html_e("Conversion (%)", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <?php } ?>
                    <th><?php esc_html_e("Revenue", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                    <th><?php esc_html_e("Total transactions", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Avg Order value", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                    <th><?php esc_html_e("Added to carts", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <?php if ($this->ga_swatch == "" || $this->ga_swatch == "ga3") { ?>
                    <th><?php esc_html_e("removed from cart", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <?php } ?>
                    <th><?php esc_html_e("Product views", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Users", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Sessions", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <?php if($this->is_ai_unlocked == "0" ) { ?>
        <!-- smart insights -->
        <div class="dash-ga4 initial_ai_sections">
            <div class="card smart-insightscard">
                <div class="card-body">
                    <div class="card-content">
                        <div class="smart-insightsbox">
                            <div class="smart-powered">
                                <a><span> <img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/ai.png'); ?>"
                                            alt="" class="img-fluid" /></span><?php echo esc_html_e("Powered Smart Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                            <div class="genrate-insights">
                                <a class="btn btn-dark common-btn unlock_ai_insights"><?php echo esc_html_e("Generate Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <!-- prompt box -->
        <div class="dash-ga4 advanced_ai_sections" <?php echo esc_attr($ai_cls); ?>>
            <div class="card smartprompt-card">
                <div class="card-body">
                    <div class="card-content">
                        <div class="smart-promptbox">
                            <div class="smart-powered">
                                <a ><span> <img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/ai.png'); ?>"
                                            alt="" class="img-fluid" /></span><?php echo esc_html_e("Powered Smart Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                            <div class="genrate-insights">
                                <a class="btn btn-dark common-btn" data-bs-toggle="modal"
                                    data-bs-target="#suggestprompt"> <span><img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/plus.png'); ?>"
                                            alt="" class="img-fluid" /></span><?php echo esc_html_e("Suggest Prompt", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                        </div>
                        <?php 
                            $SourceAiResult01 = isset($this->aiArr['SourceSales25']['value'])?$this->aiArr['SourceSales25']['value']:"";
                            $SourceAiResult02 = isset($this->aiArr['SourceConv20']['value'])?$this->aiArr['SourceConv20']['value']:"";
                            $SourceAiResult03 = isset($this->aiArr['SourceProfit20']['value'])?$this->aiArr['SourceProfit20']['value']:"";
                            $status_cls01="";
                            $status_cls_val01="";
                            $status_cls02="";
                            $status_cls_val02="";
                            $status_cls03="";
                            $status_cls_val03="";
                            if($SourceAiResult01 != ""){
                                $status_cls01="active";
                                $status_cls_val01="show active";
                            }else if($SourceAiResult02 != ""){
                                $status_cls02="active";
                                $status_cls_val02="show active";
                            }else if($SourceAiResult03 != ""){
                                $status_cls03="active";
                                $status_cls_val03="show active"; 
                            }
                            ?>
                        <div class="prompttab-box">
                            <span><?php echo esc_html_e("Select Prompt", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                <?php 
                                    $SourceAiDate01 = isset($this->aiArr['SourceSales25']['last_prompt_date'])?$this->aiArr['SourceSales25']['last_prompt_date']:"";
                                    if($SourceAiDate01 != ""){
                                        $SourceAiDate01 = new DateTime($SourceAiDate01);
                                        $interval = $this->todayAiDate01->diff($SourceAiDate01);
                                        $daysDifferences01 = $interval->days;
                                        $btn_cls_prompts01 = '';
                                    }else{
                                        $daysDifferences01 ='-1';
                                        $btn_cls_prompts01 = 'ai_prompts';
                                    } ?>
                                    <button class="nav-link <?php echo esc_attr($status_cls01); ?> <?php echo esc_attr($btn_cls_prompts01); ?>" id="source-SourceSales25" data-bs-toggle="pill"
                                        data-bs-target="#source-prompt-tab1" type="button" role="tab" data-ele_type="button" data-key="SourceSales25" data-type="source" data-destination="source_prompt_tab1_ul"
                                        aria-controls="pills-home" aria-selected="true"><?php echo esc_html_e("To increase sales by 25%", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
                                        <?php if( $daysDifferences01 >= '1' ) { ?>
                                        <div id="source01-refresh-btn" class="refresh-btn ai_prompts" data-key="SourceSales25" data-type="source" data-destination="source_prompt_tab1_ul" data-ele_type="div">
                                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/refresh_ai.png'); ?>"
                                                alt="refresh" class="img-fluid" />
                                                <div class="tool-tip">
                                                    <p><?php echo esc_html_e("Refresh to generate new insights", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                                </div>
                                        </div>
                                        <?php } ?>
                                </li>
                                <li class="nav-item" role="presentation">
                                <?php 
                                    $SourceAiDate02 = isset($this->aiArr['SourceConv20']['last_prompt_date'])?$this->aiArr['SourceConv20']['last_prompt_date']:"";
                                    if($SourceAiDate02 != ""){
                                        $SourceAiDate02 = new DateTime($SourceAiDate02);
                                        $interval = $this->todayAiDate01->diff($SourceAiDate02);
                                        $daysDifferences02 = $interval->days;
                                        $btn_cls_prompts02 = '';
                                    }else{
                                        $daysDifferences02 ='-1';
                                        $btn_cls_prompts02 = 'ai_prompts';
                                    } ?>
                                    <button class="nav-link <?php echo esc_attr($status_cls02); ?> <?php echo esc_attr($btn_cls_prompts02); ?>" id="source-SourceConv20" data-bs-toggle="pill"
                                        data-bs-target="#source-prompt-tab2" type="button" role="tab" data-type="source" data-key="SourceConv20" data-destination="source_prompt_tab2_ul" data-ele_type="button"
                                        aria-controls="pills-profile" aria-selected="false"><?php echo esc_html_e("To increase conversions by 20%", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
                                        <?php if( $daysDifferences02 >= '1' ) { ?>
                                        <div id="source02-refresh-btn" class="refresh-btn ai_prompts" data-type="source" data-key="SourceConv20" data-destination="source_prompt_tab2_ul" data-ele_type="div">
                                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/refresh_ai.png'); ?>"
                                                alt="refresh" class="img-fluid" />
                                                <div class="tool-tip">
                                                    <p><?php echo esc_html_e("Refresh to generate new insights", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                                </div>
                                        </div>
                                        <?php } ?>
                                </li>
                                <li class="nav-item" role="presentation">
                                <?php 
                                    $SourceAiDate03 = isset($this->aiArr['SourceProfit20']['last_prompt_date'])?$this->aiArr['SourceProfit20']['last_prompt_date']:"";
                                    if($SourceAiDate03 != ""){
                                        $SourceAiDate03 = new DateTime($SourceAiDate03);
                                        $interval = $this->todayAiDate01->diff($SourceAiDate03);
                                        $daysDifferences03 = $interval->days;
                                        $btn_cls_prompts03 = '';
                                    }else{
                                        $daysDifferences03 ='-1';
                                        $btn_cls_prompts03 = 'ai_prompts';
                                    } ?>
                                    <button class="nav-link <?php echo esc_attr($status_cls03); ?> <?php echo esc_attr($btn_cls_prompts03); ?>" id="source-SourceProfit20" data-bs-toggle="pill"
                                        data-bs-target="#source-prompt-tab3" type="button" role="tab" data-type="source" data-key="SourceProfit20" data-destination="source_prompt_tab3_ul" data-ele_type="button"
                                        aria-controls="pills-profile" aria-selected="false"><?php echo esc_html_e("To increase the profitability by 20%", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
                                        <?php if( $daysDifferences03 >= '1' ) { ?>
                                        <div id="source03-refresh-btn" class="refresh-btn ai_prompts" data-type="source" data-key="SourceProfit20" data-destination="source_prompt_tab3_ul" data-ele_type="div">
                                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/refresh_ai.png'); ?>"
                                                alt="refresh" class="img-fluid" />
                                                <div class="tool-tip">
                                                    <p><?php echo esc_html_e("Refresh to generate new insights", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                                </div>
                                        </div>
                                        <?php } ?>
                                </li>
                            </ul>
                            <div class="tab-content" id="source-pills-tabContent">
                                <div class="tab-pane fade <?php echo esc_attr($status_cls_val01); ?>" id="source-prompt-tab1" role="tabpanel"
                                    aria-labelledby="pills-home-tab" tabindex="0">
                                    <ul id="source_prompt_tab1_ul" class="listing">
                                        <?php if($SourceAiResult01 != ""){ ?>
                                        <li><?php echo wp_kses_post($SourceAiResult01); ?></li>
                                        <?php }else{ ?>
                                            <?php echo esc_html_e("No data available, Click Refresh to generate new insights.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        <?php  } ?>
                                    </ul>
                                </div>
                                <div class="tab-pane fade <?php echo esc_attr($status_cls_val02); ?>" id="source-prompt-tab2" role="tabpanel"
                                    aria-labelledby="pills-profile-tab" tabindex="0">
                                    <ul id="source_prompt_tab2_ul" class="listing">
                                        <?php if($SourceAiResult02 != ""){ ?>
                                        <li><?php echo wp_kses_post($SourceAiResult02); ?></li>
                                        <?php }else{ ?>
                                            <?php echo esc_html_e("No data available, Click Refresh to generate new insights.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        <?php  } ?>
                                    </ul>
                                </div>
                                <div class="tab-pane fade <?php echo esc_attr($status_cls_val03); ?>" id="source-prompt-tab3" role="tabpanel"
                                    aria-labelledby="pills-contact-tab" tabindex="0">
                                    <ul id="source_prompt_tab3_ul" class="listing">
                                        <?php if($SourceAiResult03 != ""){ ?>
                                        <li><?php echo wp_kses_post($SourceAiResult03); ?></li>
                                        <?php }else{ ?>
                                            <?php echo esc_html_e("No data available, Click Refresh to generate new insights.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        <?php  } ?>
                                    </ul>
                                </div>
                            </div>
                            <div id="source-robotyping-box" class="robotyping-box" style="display: none;">
                                <div class="ai-robot">
                                <video autoplay loop muted height="150" width="150">
                                        <source src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/airobot.mp4'); ?>" type="video/mp4">
                                    </video>
                                </div>
                                <div class="ai-typing">
                                    <h2><span class="conv_loader_type"></span></h2>
                                </div>
                            </div>
                            <div class="response-box">
                                <div class="response-validity">
                                    <p><span><?php echo esc_html_e("Prompt Limit : ", "enhanced-e-commerce-for-woocommerce-store"); ?></span><span
                                            class="prompt_used_count"><?php echo esc_html($this->promptUsed); ?></span><span>/<?php echo esc_html($this->promptLimit); ?></span>
                                    </p>
                                    <p class="response-note"><span><?php echo esc_html_e("*Insights generated based on your last 45 days of google analytics data.","enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--- Source Performance Report section over -->
<input type="hidden" id="conv_curr_tmp" value="<?php echo esc_attr($this->ga_currency_tmp); ?>" style="display: none !important;">

<!--- Shopping and Google Ads Performance section start -->
<?php //if ($this->plan_id != 1) { ?>
<div class="mt24 whiteroundedbx ggladsperfom-sec">
    <h4><?php esc_html_e("Google Ads Account Performance", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
    <div class="row">
        <div class="col50">
            <div class="chartbx ggladschrtbx daily-clicks-bx">
                <div class="chartcntnbx">
                    <h5><?php esc_html_e("Clicks", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                    <div class="chartarea">
                        <canvas id="dailyClicks" width="400" height="300" class="chartcntainer"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col50">
            <div class="chartbx ggladschrtbx daily-cost-bx">
                <div class="chartcntnbx">
                    <h5><?php esc_html_e("Cost", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                    <div class="chartarea">
                        <canvas id="dailyCost" width="400" height="300" class="chartcntainer"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col50">
            <div class="chartbx ggladschrtbx daily-conversions-bx">
                <div class="chartcntnbx">
                    <h5><?php esc_html_e("Conversions", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                    <div class="chartarea">
                        <canvas id="dailyConversions" width="400" height="300" class="chartcntainer"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col50">
            <div class="chartbx ggladschrtbx daily-sales-bx">
                <div class="chartcntnbx">
                    <h5><?php esc_html_e("Sales", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
                    <div class="chartarea">
                        <canvas id="dailySales" width="400" height="300" class="chartcntainer"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--- Shopping and Google Ads Performance section end -->

<!--- Compaign section start -->
<div class="mt24 whiteroundedbx dshreport-sec">
    <div class="row dsh-reprttop">
        <div class="dshrprttp-left">
            <h4><?php esc_html_e("Campaign Performance", "enhanced-e-commerce-for-woocommerce-store"); ?></h4>
            <a class="viewallbtn upgrdsbrs-btn"><?php esc_html_e("View all", "enhanced-e-commerce-for-woocommerce-store"); ?> <img
                    src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/blue-right-arrow.png'); ?>"
                    alt="" /></a>
        </div>
    </div>
    <div class="dashtablewrp campaign_performance_report" id="campaign_performance_report">
        <?php if ($this->google_ads_id == "") {
              ?>
        <p><?php esc_html_e("Set up your google ads account from", "enhanced-e-commerce-for-woocommerce-store"); ?> <a
                href="<?php echo esc_url($this->TVC_Admin_Helper->get_onboarding_page_url()); ?>"><?php esc_html_e("here", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
            <?php esc_html_e("in order to access Campaign performance data.", "enhanced-e-commerce-for-woocommerce-store"); ?>
        </p>
        <?php
              } ?>
        <table class="dshreporttble mbl-table">
            <thead>
                <tr>
                    <th class="prdnm-cell">
                        <?php esc_html_e("Campaign Name", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Daily Budget", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                    <th><?php esc_html_e("Status", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Clicks", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Cost", "enhanced-e-commerce-for-woocommerce-store"); ?> (<span
                            class="ga_currency_symbols"><?php echo esc_html($this->ga_currency_symbols); ?></span>)</th>
                    <th><?php esc_html_e("Conversions", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                    <th><?php esc_html_e("Sales", "enhanced-e-commerce-for-woocommerce-store"); ?></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <?php if($this->is_ai_unlocked == "0" ) { ?>
        <!-- smart insights -->
        <div class="dash-ga4 initial_ai_sections">
            <div class="card smart-insightscard">
                <div class="card-body">
                    <div class="card-content">
                        <div class="smart-insightsbox">
                            <div class="smart-powered">
                                <a ><span> <img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/ai.png'); ?>"
                                            alt="" class="img-fluid" /></span><?php echo esc_html_e("Powered Smart Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                            <div class="genrate-insights">
                                <a class="btn btn-dark common-btn unlock_ai_insights"><?php echo esc_html_e("Generate Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
        <!-- prompt box -->
        <div class="dash-ga4 advanced_ai_sections" <?php echo esc_attr($ai_cls); ?>>
            <div class="card smartprompt-card">
                <div class="card-body">
                    <div class="card-content">
                        <div class="smart-promptbox">
                            <div class="smart-powered">
                                <a ><span> <img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/ai.png'); ?>"
                                            alt="" class="img-fluid" /></span><?php echo esc_html_e("Powered Smart Insights", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                            <div class="genrate-insights">
                                <a class="btn btn-dark common-btn" data-bs-toggle="modal"
                                    data-bs-target="#suggestprompt"> <span><img
                                            src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/plus.png'); ?>"
                                            alt="" class="img-fluid" /></span> <?php echo esc_html_e("Suggest Prompt", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </div>
                        </div>
                        <?php 
                            $CampaignAiResult01 = isset($this->aiArr['CampaignPerformImprove']['value'])?$this->aiArr['CampaignPerformImprove']['value']:"";
                            $cstatus_cls01="";
                            $cstatus_cls_val01="";
                            if($CampaignAiResult01 != ""){
                                $cstatus_cls01="active";
                                $cstatus_cls_val01="show active";
                            }
                            ?>
                        <div class="prompttab-box">
                            <span><?php echo esc_html_e("Select Prompt", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                            <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                <?php 
                                    $CampaignAiDate01 = isset($this->aiArr['CampaignPerformImprove']['last_prompt_date'])?$this->aiArr['CampaignPerformImprove']['last_prompt_date']:"";
                                    if($CampaignAiDate01 != ""){
                                        $CampaignAiDate01 = new DateTime($CampaignAiDate01);
                                        $interval = $this->todayAiDate01->diff($CampaignAiDate01);
                                        $daysDifferencec01 = $interval->days;
                                        $btn_cls_promptc01 = '';
                                    }else{
                                        $daysDifferencec01 ='-1';
                                        $btn_cls_promptc01 = 'ai_prompts';
                                    } ?>
                                    <button class="nav-link <?php echo esc_attr($cstatus_cls01); ?> <?php echo esc_attr($btn_cls_promptc01); ?>" id="campaign-CampaignPerformImprove" data-bs-toggle="pill"
                                        data-bs-target="#campaign-prompt-tab1" type="button" role="tab" data-key="CampaignPerformImprove" data-type="campaign" data-destination="campaign_prompt_tab1_ul" data-ele_type="button"
                                        aria-controls="pills-home" aria-selected="true"><?php echo esc_html_e("To improve campaign performance", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
                                        <?php if( $daysDifferencec01 >= '1' ) { ?>
                                        <div id="campaign01-refresh-btn" class="refresh-btn ai_prompts" data-key="CampaignPerformImprove" data-type="campaign" data-destination="campaign_prompt_tab1_ul" data-ele_type="div">
                                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/refresh_ai.png'); ?>"
                                                alt="refresh" class="img-fluid" />
                                                <div class="tool-tip">
                                                    <p><?php echo esc_html_e("Refresh to generate new insights", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                                </div>
                                        </div>
                                        <?php } ?>
                                </li>
                            </ul>
                            <div class="tab-content" id="campaign-pills-tabContent">
                                <div class="tab-pane fade <?php echo esc_attr($cstatus_cls_val01); ?>" id="campaign-prompt-tab1" role="tabpanel"
                                    aria-labelledby="pills-home-tab" tabindex="0">
                                    <ul id="campaign_prompt_tab1_ul" class="listing">
                                        <?php if($CampaignAiResult01 != ""){ ?>
                                        <li><?php echo wp_kses_post($CampaignAiResult01); ?></li>
                                        <?php }else{ ?>
                                            <?php esc_html_e("No data available, Click Refresh to generate new insights.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        <?php  } ?>
                                    </ul>
                                </div>
                            </div>
                            <div id="campaign-robotyping-box" class="robotyping-box" style="display: none;">
                                <div class="ai-robot">
                                    <video autoplay loop muted height="150" width="150">
                                        <source src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/airobot.mp4'); ?>" type="video/mp4">
                                    </video>
                                </div>
                                <div class="ai-typing">
                                    <h2><span class="conv_loader_type"></span></h2>
                                </div>
                            </div>
                            <div class="response-box">
                                <div class="response-validity">
                                    <p><span><?php echo esc_html_e("Prompt Limit : ", "enhanced-e-commerce-for-woocommerce-store"); ?></span><span
                                            class="prompt_used_count"><?php echo esc_html($this->promptUsed); ?></span><span>/<?php echo esc_html($this->promptLimit); ?></span>
                                    </p>
                                    <p class="response-note"><span><?php echo esc_html_e("*Insights generated based on your last 45 days of google ads data.","enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--- Campaign Performance Report section over -->
<!-- UPGRADE SUBSCRIPTION -->
<div id="upgradsbscrptn" class="pp-modal whitepopup upgradsbscrptn-pp">
    <div class="sycnprdct-ppcnt">
        <div class="ppwhitebg pp-content upgradsbscrptnpp-cntr">
            <div class="ppclsbtn absltpsclsbtn clsbtntrgr">
            <span class="material-symbols-outlined closeModal text-white" >
                close
            </span>
            </div>
            <div class="upgradsbscrptnpp-hdr">
                <h5 class="prochrtitle">
                    <?php esc_html_e("Upgrade to Pro..!!", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
            </div>
            <div class="ppmodal-body">
                <p><?php esc_html_e("This feature is only available in the paid plan. Please upgrade to get the full range of reports and more.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </p>
                <div class="ppupgrdbtnwrap">
                    <a class="blueupgrdbtn" href="<?php echo esc_url($this->pro_plan_site); ?>"
                        target="_blank"><?php esc_html_e("Upgrade Now", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  Upcoming featur -->
<div id="upcoming_featur" class="pp-modal whitepopup upcoming_featur-btn-pp">
    <div class="sycnprdct-ppcnt">
        <div class="ppwhitebg pp-content upgradsbscrptnpp-cntr">
            <div class="ppclsbtn absltpsclsbtn clsbtntrgr">
            <span class="material-symbols-outlined closeModal text-white" >
                close
            </span>
            </div>
            <div class="upgradsbscrptnpp-hdr">
                <h5><?php esc_html_e("Upcoming..!!", "enhanced-e-commerce-for-woocommerce-store"); ?></h5>
            </div>
            <div class="ppmodal-body">
                <p><?php esc_html_e("We are currently working on this feature and we will reach out to you once this is live.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </p>
                <p><?php esc_html_e("We aim to give you a capability to schedule the reports directly in your inbox whenever you want.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                </p>
            </div>
        </div>
    </div>
</div>
          <!-- Schedule Email Modal box -->
          <div class="modal email-modal fade" id="schedule_email_modal" tabindex="-1" aria-labelledby="schedule_email_modalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div id="loadingbar_blue" class="progress-materializecss" style="
    display: none;
">
                <div class="indeterminate"></div>
              </div>
              <div class="modal-content">
                <div class="modal-body">
                  <div class="scheduleemail-box">
                    <h2><?php esc_html_e("Smart Emails", "enhanced-e-commerce-for-woocommerce-store"); ?></h2>
                    <p>
                      <?php esc_html_e("Schedule your Google Analytics 4 Insight Report email for", "enhanced-e-commerce-for-woocommerce-store"); ?>
                      <br>
                      <?php esc_html_e("data-driven insights", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <?php
                    if ($this->sch_email_toggle_check == '0') { //enabled
                      $switch_cls = 'convEmail_default_cls_enabled';
                      $switch_checked = 'checked';
                      $txtcls="form-fields-dark";
                    } else { //disabled
                      $switch_cls = 'convEmail_default_cls_disabled';
                      $switch_checked = '';
                      $txtcls="form-fields-light";
                    } ?>
                    <div class="schedule-formbox">
                      <div class="toggle-switch">
                        <div class="form-check form-switch">
                          <div class="form-check form-switch">
                            <label id="email_toggle_btnLabel" for="email_toggle_btn" class="form-check-input switch <?php echo esc_attr($switch_cls); ?>" role="switch">
                              <input id="email_toggle_btn" type="checkbox" class="<?php echo esc_attr($switch_cls); ?>" <?php echo esc_attr($switch_checked); ?>>
                              <div></div>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-wholebox">
                        <div class="form-box">
                          <label for="custom_email" class="form-label llabel"><?php esc_html_e("Email address", "enhanced-e-commerce-for-woocommerce-store"); ?></label>
                          <input type="email" class="form-control icontrol <?php echo esc_attr($txtcls); ?>" id="custom_email" aria-describedby="emailHelp" placeholder="user@gmail.com" value="<?php echo esc_attr($this->g_mail); ?>" disabled readonly>
                        </div>
                        <div class="form-box">
                          <h5>
                              <?php esc_html_e("To get emails on your alternate address. ", "enhanced-e-commerce-for-woocommerce-store"); ?><a style="color:  #1085F1;cursor: pointer;" href="https://www.conversios.io/wordpress/all-in-one-google-analytics-pixels-and-product-feed-manager-for-woocommerce-pricing/?utm_source=EE+Plugin+User+Interface&amp;utm_medium=dashboard&amp;utm_campaign=Upsell+at+Conversios" target="_blank"><?php esc_html_e("Upgrade To Pro", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </h5>
                        </div>
                        <div class="form-box">
                          <label for="email_frequency" class="form-label llabel">
                            <?php esc_html_e("Email Frequency", "enhanced-e-commerce-for-woocommerce-store"); ?>
                          </label>
                          <input type="text" class="form-control icontrol <?php echo esc_attr($txtcls); ?>" id="email_frequency" value="<?php echo esc_attr($this->sch_email_frequency); ?>" disabled readonly>
                          <div id="email_frequency_arrow" class="down-arrow"></div>
                        </div>
                    
                        <div class="form-box">
                          <h5>
                              <?php esc_html_e("By default, you will receive a Monthly report in your email inbox.", "enhanced-e-commerce-for-woocommerce-store"); ?><br><?php esc_html_e("To get report ","enhanced-e-commerce-for-woocommerce-store"); ?><strong>Daily</strong> or <strong>Weekly</strong>. <a href="https://www.conversios.io/wordpress/all-in-one-google-analytics-pixels-and-product-feed-manager-for-woocommerce-pricing/?utm_source=EE+Plugin+User+Interface&amp;utm_medium=dashboard&amp;utm_campaign=Upsell+at+Conversios" target="_blank" style="color:  #1085F1;"><?php esc_html_e("Upgrade To Pro", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                            </h5>
                        </div>
                        <div class="form-box">
                          <div class="save">
                            <button id="schedule_email_save_config" class="btn  save-btn"><?php esc_html_e("Save", "enhanced-e-commerce-for-woocommerce-store"); ?></button>
                          </div>
                        </div>
                        <div class="form-box">
                          <div class="save">
                            <span id="err_sch_msg" style="display: none;color: red;position: absolute;top: -9px;"><?php esc_html_e("Something went wrong, please try again later.", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--modal end-->
          <div class=" custom-toast position-fixed bottom-0 end-0 p-3" style="z-index: 11;">
            <div id="sch_ack_msg" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
              <button id="sch_ack_msg_close" type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              <div class="toast-body">
                <strong>Email Configruation updated successfully</strong>
              </div>
            </div>
          </div>

<div class=" custom-toast position-fixed bottom-0 end-0 p-3" style="z-index: 11;">
    <div id="sug_ack_msg" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <button id="sug_ack_msg_close" type="button" class="btn-close" data-bs-dismiss="toast"
            aria-label="Close"></button>
        <div class="toast-body">
            <strong>Prompt suggestions submitted successfully</strong>
        </div>
    </div>
</div>

<!-- Modal suggest prompt -->
<div class="dash-ga4">
    <div class="custom-modal prompt-feedback modal fade" id="suggestprompt" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
            <div id="loadingbar_blue2" class="progress-materializecss" style="
    display: none;
">
            <div class="indeterminate"></div>
        </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <h2>Did not find the prompt as per <br> your need ?</h2>
                    <p>
                        Rest assured! <br> We are here to ease your concerns. Simply inform us about the prompts you
                        require
                        for your business goals, and we will promptly deliver tailored prompts that suit your needs.
                    </p>
                    <form>
                        <div class="prompt-form">
                            <input type ="hidden" id="prompt_counter" value="">
                            <div id="edit-box-prompt" class="input-group form-box" style="display:none;flex-direction:column;">
                            </div>
                            <div id="add-box-prompt"class="input-group form-box">
                                <input id="add_input_prompt" type="text" class="form-control icontrol add" placeholder="Write Your Suggestion"
                                    aria-label="Recipient's username" aria-describedby="basic-addon2">
                                <div id="add_prompt_submit" type="submit" class="input-group-text add operations_suggest_form" id="basic-addon2"> <span>
                                        <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/add-icon.png'); ?>"
                                            alt="Edit icon" class="img-fluid" /> </span> Add</div>
                            </div>
                            <div id ="save_suggestions" class="submit">
                                <button type="button" class="btn btn-dark common-btn">Submit</button>
                            </div>
                        </div>
                        <div class="form-box">
                                <div class="save">
                                    <span id="err_sug_msg"
                                        style="display: none;color: red;margin: 6px 0px 0px 0px;"><?php esc_html_e("Something went wrong, please try again later.", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?php
    }
  }
}
new Conversios_Analytics_Reports();