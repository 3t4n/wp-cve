<?php

/**
 * @since      4.1.4
 * Description: Conversios Onboarding page, It's call while active the plugin
 */
if (class_exists('Conversios_Dashboard') === FALSE) {
    class Conversios_Dashboard
    {

        protected $screen;
        protected $TVC_Admin_Helper;
        protected $TVC_Admin_DB_Helper;
        protected $CustomApi;
        protected $PMax_Helper;
        protected $subscription_id;
        protected $ga_traking_type;
        protected $currency_code;
        protected $currency_symbol;
        protected $ga_currency;
        protected $ga_currency_symbols;
        protected $ga4_measurement_id;
        protected $ga4_analytic_account_id;
        protected $ga4_property_id;
        protected $subscription_data;
        protected $plan_id = 1;
        protected $is_need_to_update_api_data_wp_db = false;
        protected $report_data;
        protected $notice;
        protected $google_ads_id;
        protected $connect_url;
        protected $g_mail;
        protected $is_refresh_token_expire;

        protected $resource_center_data = array();
        protected $ee_options;
        protected $ee_customer_gmail;
        protected $is_channel_connected;
        protected $chkEvenOdd;

        public function __construct()
        {
            $this->TVC_Admin_Helper = new TVC_Admin_Helper();
            $this->TVC_Admin_DB_Helper = new TVC_Admin_DB_Helper();
            $this->CustomApi = new CustomApi();
            $this->PMax_Helper = new Conversios_PMax_Helper();
            $this->connect_url = $this->TVC_Admin_Helper->get_custom_connect_url(admin_url() . 'admin.php?page=conversios');
            $this->subscription_id = $this->TVC_Admin_Helper->get_subscriptionId();
            // update API data to DB while expired token

            $this->ee_options = $this->TVC_Admin_Helper->get_ee_options_settings();
            $this->ee_customer_gmail = get_option("ee_customer_gmail");


            $this->subscription_data = $this->TVC_Admin_Helper->get_user_subscription_data();
            if (isset($this->subscription_data->plan_id) && !in_array($this->subscription_data->plan_id, array("1"))) {
                $this->plan_id = $this->subscription_data->plan_id;
            }
            if (isset($this->subscription_data->google_ads_id) && $this->subscription_data->google_ads_id != "") {
                $this->google_ads_id = $this->subscription_data->google_ads_id;
            }
            if (empty($this->subscription_id)) {
                wp_redirect("admin.php?page=conversios-google-analytics");
                exit;
            }

            // resource center data
            $rcd_postdata = array("app_id" => 1, "platform_id" => 1, "plan_id" => "1", "screen_name" => "dashboard");
            $resource_center_res = $this->CustomApi->get_resource_center_data($rcd_postdata);
            if (!empty($resource_center_res->data)) {
                $this->resource_center_data = $resource_center_res->data;
            }
            $this->currency_symbol = '';            
            if($this->google_ads_id) {
                if (isset($currency_code_rs->data->currencyCode)) {
                    $this->currency_code = $currency_code_rs->data->currencyCode;
                }
                $this->currency_symbol = $this->TVC_Admin_Helper->get_currency_symbols($this->currency_code);
            }
            

            $this->includes();
            $this->screen = get_current_screen();
            $this->load_html();
        }

        public function includes()
        {
            if (!class_exists('CustomApi.php')) {
                require_once(ENHANCAD_PLUGIN_DIR . 'includes/setup/CustomApi.php');
            }
        }


        public function load_html()
        {
            if( isset($_GET['page']) && $_GET['page'] != "" )    
                do_action('conversios_start_html_' . sanitize_text_field($_GET['page']));
            $this->current_html();
            $this->current_js_licence_active();
            if( isset($_GET['page']) && $_GET['page'] != "" )    
                do_action('conversios_end_html_' . sanitize_text_field($_GET['page']));
        }



        public function current_js_licence_active()
        { ?>
            <script>
                jQuery(function() {
                    jQuery("#acvivelicbtn").click(function() {
                        var post_data_lic = {
                            action: "tvc_call_active_licence",
                            licence_key: jQuery("#licencekeyinput").val(),
                            conv_licence_nonce: '<?php echo esc_js(wp_create_nonce("conv_lic_nonce")); ?>',
                        }
                        jQuery.ajax({
                            type: "POST",
                            dataType: "json",
                            url: tvc_ajax_url,
                            data: post_data_lic,
                            beforeSend: function() {
                                jQuery("#acvivelicbtn").find(".spinner-border").removeClass("d-none");
                            },
                            success: function(response) {
                                jQuery("#licencemsg").removeClass();
                                if (response.error === false) {
                                    jQuery("#licencemsg").addClass('text-success').text(response.message);
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                } else {
                                    jQuery("#licencemsg").addClass('text-danger').text(response.message);
                                }
                                jQuery('#acvivelicbtn').find(".spinner-border").addClass("d-none");
                            }
                        });
                    });
                });
            </script>
        <?php }
        public function dashboard_licencebox_html()
        { ?>
            <div class="dash-area">
                <div class="dashwhole-box">
                    <div class="card">
                        <div class="card-body">
                            <div class="purchase-box">
                                <h4>
                                    <?php esc_html_e("Already purchased license Key?", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h4>
                                <div class="form-box">
                                    <input type="email" class="form-control icontrol" readonly id="exampleFormControlInput1" placeholder="Enter your key">
                                </div>
                                <div class="upgrade-btn">
                                    <a target="_blank" href="<?php echo esc_url($this->TVC_Admin_Helper->get_conv_pro_link_adv("licenceinput", "dashboard", "", "linkonly", "")); ?>" class="btn btn-dark common-btn">
                                        <?php esc_html_e("Upgrade to Pro", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }

        // Sidebar
        public function dashboard_videocardbox_html()
        {
            $gettingstarr = new stdClass;
            $res_data = $this->resource_center_data;
            foreach ($res_data as $value) {
                if ($value->screen_name == "dashboard" && $value->sub_type == "gettingstartedvideo") {
                    $gettingstarr = $value;
                    break;
                }
            }
            if (!empty((array) $gettingstarr)) {
            ?>
                <div class="videocard card">
                    <div class="videoimage">
                        <img class="align-self-center" src="<?php echo esc_url($gettingstarr->thumbnail_url); ?>" />
                    </div>
                    <div class="card-body">
                        <div class="title-dropdown">
                            <div class="title-text">
                                <h3>
                                    <?php 
                                    printf(
                                        /* translators: %s: Title */
                                        esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ),
                                        esc_html( $gettingstarr->title )
                                    );
                                    ?>
                                </h3>
                            </div>
                        </div>
                        <div class="card-content">
                            <p>
                                <?php printf( esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ), esc_html( $gettingstarr->description ) ); ?>
                            </p>
                            <div class="watch-videobtn">
                                <a target="_blank" href="<?php echo esc_url($gettingstarr->link) ?>" class="btn btn-dark common-btn">
                                    <?php esc_html_e("Watch Video", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php
            }
        }


        // Sidebar
        public function dashboard_convproducts_html()
        { ?>
            <div class="videocard card scaleyourbusiness">
                <div class="card-body">
                    <h2 class="text-white">
                        <?php esc_html_e("Scale Your Business Faster", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </h2>
                    <p class="text-white lh-sm pt-2">
                        <?php esc_html_e("Explore Our Analytics and Marketing Solutions For Shopify, Magento & Shopware", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <ul class="convprolist p-0 pt-3">
                        <li class="bg-white p-2 rounded-3">
                            <a class="d-flex align-items-center" href="<?php echo esc_url("https://www.conversios.io/ga4-fbcapi-pixel-for-shopify/?utm_source=in_app&utm_medium=sidebar&utm_campaign=scale_business"); ?>" target="_blank">
                                <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_shopify_icon.png'); ?>" />
                                <p class="text-dark lh-sm ps-2">
                                    <?php esc_html_e("All In One Pixel and FBCAPI for Shopify", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </p>
                            </a>
                        </li>

                        <li class="bg-white p-2 rounded-3">
                            <a class="d-flex align-items-center" href="<?php echo esc_url("https://www.conversios.io/google-analytics-4-marketing-pixels-for-magento/?utm_source=in_app&utm_medium=sidebar&utm_campaign=scale_business"); ?>" target="_blank">
                                <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_magento_icon.png'); ?>" />
                                <p class="text-dark lh-sm ps-2">
                                    <?php esc_html_e("Pixel Manager Extension for Magento 2", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </p>
                            </a>
                        </li>

                        <li class="bg-white p-2 rounded-3">
                            <a class="d-flex align-items-center" href="<?php echo esc_url("https://www.conversios.io/google-analytics-4-and-ads-pixels-for-shopware/?utm_source=in_app&utm_medium=sidebar&utm_campaign=scale_business"); ?>" target="_blank">
                                <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/conv_shopware_icon.png'); ?>" />
                                <p class="text-dark lh-sm ps-2">
                                    <?php esc_html_e("Google Analytics 4 & Ads Pixels via GTM For Shopware", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php
        }

        public function dashboard_requestfeature_html()
        { ?>
            <div class="videocard card">
                <div class="card-body">
                    <h2>
                        <?php esc_html_e("Request a feature", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </h2>
                    <p class="pt-2">
                        <?php esc_html_e("Did not find what you are looking for? Submit a feature requirement and we will look into it.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </p>
                    <form id="conv_requestafeature_form">
                        <ul class="convprolist pt-3 p-0">
                            <li class="rounded-3">
                                <textarea rows="6" class="col-12" id="conv_requestafeature_message" name="featurereq_message" placeholder="Enter a message"></textarea>
                            </li>

                            <li class="bg-white rounded-3">
                                <button type="button" id="requestfeaturebut" class="btn btn-primary px-4">
                                    <span class="spinner-border text-light spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    <?php esc_html_e("Submit", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </button>
                            </li>
                        </ul>
                    </form>

                    <div id="conv_requestafeature_mesasge" class="alert alert-success d-none mt-4" role="alert">
                        <?php esc_html_e("Thank you for submitting new feature request. Our team will review it and contact you soon.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </div>
                </div>
            </div>
            <script>
                jQuery(function() {
                    jQuery("#conv_requestafeature_message").blur(function() {
                        if (jQuery("#conv_requestafeature_message").val() != "") {
                            jQuery("#conv_requestafeature_message").removeClass("border border-danger");
                        } else {
                            jQuery("#conv_requestafeature_message").addClass("border border-danger");
                        }
                    });
                    jQuery('#requestfeaturebut').on('click', function(event, el) {
                        if (jQuery("#conv_requestafeature_message").val() != "") {
                            jQuery('#requestfeaturebut').find(".spinner-border").removeClass("d-none");
                            jQuery('#requestfeaturebut').addClass("disabled");
                            var reqfeatdata = jQuery("#conv_requestafeature_form").serializeArray();
                            console.log(reqfeatdata);
                            jQuery.ajax({
                                type: "POST",
                                dataType: "json",
                                url: tvc_ajax_url,
                                data: {
                                    action: "tvc_call_add_customer_featurereq",
                                    feature_req_nonce: "<?php echo esc_js(wp_create_nonce('feature_req_nonce_val')); ?>",
                                    featurereq_message: jQuery("#conv_requestafeature_message").val(),
                                    subscription_id: "<?php echo esc_js($this->subscription_id); ?>",
                                },
                                success: function(response) {
                                    console.log(response);
                                    jQuery('#conv_requestafeature_form').remove();
                                    jQuery('#conv_requestafeature_mesasge').removeClass("d-none");
                                }
                            });
                        } else {
                            jQuery("#conv_requestafeature_message").addClass("border border-danger");
                        }


                    });
                });
            </script>
        <?php
        }


        // Sidebar
        public function dashboard_recentpostbox_html()
        { ?>
            <div class="videocard recent-post card">
                <div class="card-body p-4 m-2">
                    <div class="">
                        <div class="title-text d-flex justify-content-between">
                            <h2 class="fw-bold">
                                <?php esc_html_e("Help Topics", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </h2>
                            <a target="_blank" class="fs-18 fw-400 ms-auto conv-link-blue fw-bold" href="<?php echo esc_url('https://www.conversios.io/docs-category/woocommerce-2/?utm_source=in_app&utm_medium=top_menu&utm_campaign=help_center'); ?>">
                                <u><?php esc_html_e("Help Center", "enhanced-e-commerce-for-woocommerce-store"); ?></u>
                            </a>
                        </div>
                    </div>
                    <div class="card-content recentpostcard">
                        <?php
                        $res_data = $this->resource_center_data;
                        foreach ($res_data as $key => $value) {
                            if ($value->screen_name != "dashboard" && $value->sub_type != "recentposts") {
                                continue;
                            }
                        ?>
                            <a href="<?php echo esc_url($value->link); ?>" target="_blank">
                                <span><?php printf( esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ), esc_html( $value->title ) ); ?></span>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php
        }

        // Sidebar
        public function dashboard_gethelp_html()
        { ?>
            <div class="commoncard-box get-premium need-help card">
                <div class="card-body">
                    <div class="title-title">
                        <h3>
                            <?php esc_html_e("Need More Help", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </h3>
                        <p>
                            <?php esc_html_e("Book your Demo and our Support team will help you in setting up your account.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </p>
                    </div>
                    <div class="card-content">
                        <div class="premium-btn book-demo">
                            <a target="_blank" href="<?php echo esc_url("https://calendly.com/conversios/30min"); ?>" class="btn btn-dark common-btn">
                                <?php esc_html_e("Book Demo", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }


        // Pixel accordion
        public function get_pixel_accordion()
        {
            $pixel_setting = array(
                "gtmsettings" => isset($this->ee_options['tracking_method']) && $this->ee_options['tracking_method'] == 'gtm' ? 'convo-active' : 'gtmnotconnected',
                "gasettings" => (isset($this->ee_options['ga_id']) && $this->ee_options['ga_id'] != '') || (isset($this->ee_options['gm_id']) && $this->ee_options['gm_id'] != '') ? 'convo-active' : '',
                "gadssettings" => isset($this->ee_options['google_ads_id']) && $this->ee_options['google_ads_id'] != '' ? 'convo-active' : '',
                "fbsettings" => isset($this->ee_options['fb_pixel_id']) && $this->ee_options['fb_pixel_id'] != '' ? 'convo-active' : '',
                "bingsettings" => isset($this->ee_options['microsoft_ads_pixel_id']) && $this->ee_options['microsoft_ads_pixel_id'] != '' ? 'convo-active' : '',
                "twittersettings" => isset($this->ee_options['twitter_ads_pixel_id']) && $this->ee_options['twitter_ads_pixel_id'] != '' ? 'convo-active' : '',
                "pintrestsettings" => isset($this->ee_options['pinterest_ads_pixel_id']) && $this->ee_options['pinterest_ads_pixel_id'] != '' ? 'convo-active' : '',
                "snapchatsettings" => isset($this->ee_options['snapchat_ads_pixel_id']) && $this->ee_options['snapchat_ads_pixel_id'] != '' ? 'convo-active' : '',
                "tiktoksettings" => isset($this->ee_options['tiKtok_ads_pixel_id']) && $this->ee_options['tiKtok_ads_pixel_id'] != '' ? 'convo-active' : '',
                "hotjarsettings" => isset($this->ee_options['hotjar_pixel_id']) && $this->ee_options['hotjar_pixel_id'] != '' ? 'convo-active' : '',
                "crazyeggsettings" => isset($this->ee_options['crazyegg_pixel_id']) && $this->ee_options['crazyegg_pixel_id'] != '' ? 'convo-active' : '',
                "claritysettings" => isset($this->ee_options['msclarity_pixel_id']) && $this->ee_options['msclarity_pixel_id'] != '' ? 'convo-active' : ''
            );

            $gtm_status_icon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
            $webtracking_status_icon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
            $adstracking_status_icon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';

            $pixelprogressbarclass = [];
            
            if ($pixel_setting['gtmsettings'] == "convo-active") {
                array_push($pixelprogressbarclass, 33);
                $gtm_status_icon = '<span class="material-symbols-outlined text-success">check_circle</span>';
            }
            if ($pixel_setting['gasettings'] == "convo-active") {
                array_push($pixelprogressbarclass, 33);
                $webtracking_status_icon = '<span class="material-symbols-outlined text-success">check_circle</span>';
            }
            if ($pixel_setting['gadssettings'] == "convo-active" || $pixel_setting['fbsettings'] == "convo-active" || $pixel_setting['snapchatsettings'] == "convo-active" || $pixel_setting['tiktoksettings'] == "convo-active" || $pixel_setting['pintrestsettings'] == "convo-active" || $pixel_setting['bingsettings'] == "convo-active" || $pixel_setting['tiktoksettings'] == "convo-active") {
                array_push($pixelprogressbarclass, 33);
                $adstracking_status_icon = '<span class="material-symbols-outlined text-success">check_circle</span>';
            }

        ?>

            <div class="accordion-item">
                <h2 class="accordion-header d-flex p-2" id="flush-headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        <img class="pe-2" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/conv-accordio-pa.png'); ?>">
                        <?php esc_html_e("Ecommerce & Conversion Tracking | Audience Building", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </h2>
                <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <ul class="ps-4 ms-2 mb-4">
                            <li class="pt-0 ps-1">
                                <p class="py-2"><?php esc_html_e("Let's enhance your site's analytics and marketing capabilities in just three easy steps:", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                <div class="d-flex justify-content-between">
                                    <h3><?php esc_html_e("Step 1: Integrate Google Tag Manager", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                    <?php echo wp_kses_post($gtm_status_icon); ?>
                                </div>
                                <div class="convps_threefive">
                                    <p class="fw-bold ps-3 py-1">
                                        <?php esc_html_e("Quick GTM Integration", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </p>

                                    <ul class="list-styled pt-2">
                                        <li>
                                            <?php esc_html_e("Seamlessly connect Google Tag Manager with your website using our plugin. ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <br><?php esc_html_e("Activate your GTM container with a single click!", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </li>
                                        <li>
                                            <?php esc_html_e("Benefit from streamlined web analytics and ad tracking setup. GTM not only", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <br><?php esc_html_e("ensures your pages load swiftly but also offers unmatched flexibility in managing", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <br><?php esc_html_e("your tags.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="pt-0 ps-1">
                                <div class="d-flex justify-content-between">
                                    <h3><?php esc_html_e("Step 2: Activate Comprehensive Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                    <?php echo wp_kses_post($webtracking_status_icon); ?>
                                </div>
                                <div class="convps_threefive">
                                    <p class="fw-bold ps-3 py-1">
                                        <?php esc_html_e("Advanced Analytics Setup", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </p>

                                    <ul class="list-styled pt-2">
                                        <li>
                                            <?php esc_html_e("Effortlessly implement tracking for Web & Ecommerce activities with GA4, Hotjar,", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <br><?php esc_html_e("Clarity, and Crazy Egg.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </li>
                                        <li>
                                            <?php esc_html_e("Gain insightful data on every interaction visitors have on your site. Use these", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <br><?php esc_html_e("insights to make informed decisions, boosting both conversions and sales.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                            <li class="pt-0 ps-1">
                                <div class="d-flex justify-content-between">
                                    <h3><?php esc_html_e("step 3: Optimize Your Ad Campaigns", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                    <?php echo wp_kses_post($adstracking_status_icon); ?>
                                </div>
                                <div class="convps_threefive">
                                    <p class="fw-bold ps-3 py-1">
                                        <?php esc_html_e("Conversion Tracking & Audience Building", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </p>

                                    <ul class="list-styled pt-2">
                                        <li>
                                            <?php esc_html_e("Precisely configure conversion tracking for a range of ad platforms including", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <br><?php esc_html_e("Google Ads, Meta, TikTok, Snapchat, Pinterest, and Microsoft Ads.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </li>
                                        <li>
                                            <?php esc_html_e("Enhance your ad campaigns' efficiency with accurate conversion data, allowing", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <br><?php esc_html_e("for better optimization and performance evaluation.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </li>
                                        <li>
                                            <?php esc_html_e("Create targeted audiences for remarketing, ensuring you re-engage visitors who", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <br><?php esc_html_e("haven't converted yet.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </li>
                                    </ul>
                                </div>
                            </li>

                        </ul>

                        <div class="convps_threefive">
                            <p>
                                <?php esc_html_e("Each step you complete brings you closer to a fully optimized site, ready to leverage", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                <br><?php esc_html_e("data for growth. Let's get started!", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </p>
                        </div>
                        <div class="accbutbox d-flex justify-content-end pt-4">
                            <?php if (array_sum($pixelprogressbarclass) != 99) { ?>
                                <img class="align-self-baseline setupinfive scroll-bounce" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/setupin5min.png'); ?>">
                            <?php } ?>
                            <a class="btn btn-primary ms-3 px-4 align-self-baseline" href="<?php echo esc_url('admin.php?page=conversios&wizard=pixelandanalytics'); ?>">
                                <?php esc_html_e("Launch Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        <?php
        }
        public function get_reporting_accordion()
        {

            $reporting_setting = array(
                "gasettings" => isset($this->ee_options['gm_id']) && $this->ee_options['gm_id'] != '' ? 'convo-active' : '',
                "gadssettings" => isset($this->ee_options['google_ads_id']) && $this->ee_options['google_ads_id'] != '' ? 'convo-active' : ''
            );
            $launch_cls = '';
            $ga_status_icon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';
            $init_reporting_status_icon = '<span class="material-symbols-outlined text-warning">history_toggle_off</span>';

            if ($reporting_setting['gasettings'] == "convo-active" && $reporting_setting['gadssettings'] == "convo-active") {
                $ga_status_icon = '<span class="material-symbols-outlined text-success">check_circle</span>';
                $init_reporting_status_icon = $ga_status_icon;
                $launch_cls = 'd-none';
            } else {
                if ($reporting_setting['gasettings'] == "convo-active") {
                    $init_reporting_status_icon = '<span class="material-symbols-outlined text-success">check_circle</span>';
                    $launch_cls = 'd-none';
                }
            }
        ?>
            <div class="accordion-item">
                <h2 class="accordion-header d-flex p-2" id="flush-headingThree">
                    <button class="accordion-button collapsed reporting-accordian" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                        <img class="pe-2" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/reporting-images/reporting_dashboard_icon.png'); ?>">
                        <?php esc_html_e("Reports & Insights", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </h2>
                <div id="flush-collapseThree" class="accordion-collapse collapse " aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <ul class="ps-4 ms-2 mb-4">
                            <li class="pt-0 ps-1">
                                <div class="d-flex justify-content-between">
                                    <h3>
                                        <?php esc_html_e("Step 1: Setup Google Analytics 4 & Google Ads", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </h3>
                                    <?php echo wp_kses_post($ga_status_icon); ?>
                                </div>
                                <div class="convps_threefive" bis_skin_checked="1">
                                    <ul class="list-styled pt-2">
                                        <li><?php esc_html_e("Connect your Google Analytics 4 & Google Ads account for advanced reporting & insights.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="pt-0 ps-1">
                                <div class="d-flex justify-content-between">
                                    <h3>
                                        <?php esc_html_e("Step 2: Redirection to View Reports, AI Powered Insights & Live Event Tracking.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </h3>
                                    <?php echo wp_kses_post($init_reporting_status_icon); ?>
                                </div>
                            </li>
                        </ul>
                        <?php if ($reporting_setting['gasettings'] == "convo-active") { ?>
                            <div id="ga4reportcard" class="commoncard-box card">
                                <div class="card-body">
                                    <div class="title-title d-flex justify-content-between">
                                        <h3>
                                            <?php esc_html_e("Reports & Insights", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <div class="spinner-border text-primary spinner-border-sm" role="status"></div>
                                        </h3>
                                        <h3 class="text-secondary fw-normal">
                                            <?php esc_html_e("(Last 30 Days Google Analytics 4 Reports)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </h3>
                                    </div>
                                    <div id="dash-reportnotgenerated" class="report-notgenratedbox d-none">
                                        <div class="card-content">
                                            <div class="card-image">
                                                <img class="align-self-center" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/report_img.svg'); ?>" />
                                            </div>
                                            <div class="card-content">
                                                <h3><?php esc_html_e("Report Not Generated", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                                <p><?php esc_html_e("Please Re-connect your Google Analytics or try again later", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="dash-reportgenerated" class="genrated-box d-none mb-2">
                                        <ul>
                                            <?php
                                            $ga4ReportArr = array(
                                                "totalRevenue" => array(
                                                    "title" => "Revenue",
                                                    "divid" => "totalRevenue",
                                                ),
                                                "transactions" => array(
                                                    "title" => "Total Transactions",
                                                    "divid" => "transactions",
                                                ),
                                                "averagePurchaseRevenue" => array(
                                                    "title" => "Avg. Order Value",
                                                    "divid" => "averagePurchaseRevenue",
                                                ),
                                                "addToCarts" => array(
                                                    "title" => "Added to Cart",
                                                    "divid" => "addToCarts",
                                                ),
                                                "sessions" => array(
                                                    "title" => "Sessions",
                                                    "divid" => "sessions",
                                                ),
                                                "totalUsers" => array(
                                                    "title" => "Total Users",
                                                    "divid" => "totalUsers",
                                                ),
                                                "newUsers" => array(
                                                    "title" => "New Users",
                                                    "divid" => "newUsers",
                                                ),
                                                "itemViews" => array(
                                                    "title" => "Product Views",
                                                    "divid" => "itemViews",
                                                ),
                                            );
                                            foreach ($ga4ReportArr as $key => $value) {
                                            ?>
                                                <li id="<?php echo esc_attr($value['divid']); ?>">
                                                    <div class="revenue-box card">
                                                        <div class="card-body">
                                                            <h3>
                                                                <?php printf( esc_html__( '%s', 'enhanced-e-commerce-for-woocommerce-store' ), esc_html( $value['title'] ) ); ?>
                                                            </h3>
                                                            <p>
                                                                <?php esc_html_e("From Previous Period", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                            </p>
                                                            <div class="market-box">
                                                                <div class="price">-</div>
                                                                <div class="market">
                                                                    <img class="align-self-center greenup d-none" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/green-up.png'); ?>" />
                                                                    <img class="align-self-center reddown d-none" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/red-down.png'); ?>" />
                                                                    <span>-</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php
                                            }
                                            ?>
                                        </ul>
                                        <div class="d-flex justify-content-end pe-2">
                                            <a href="<?php echo esc_url('admin.php?page=conversios-analytics-reports'); ?>" class="conv-link-blue fw-bold">
                                                <?php esc_html_e("View All Reports", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <script>
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
                                jQuery(document).on('click', '.reporting-accordian', function() {
                                    if (jQuery(this).attr("aria-expanded") == 'true') {
                                        var post_data = {
                                            action: 'get_google_analytics_reports_dashboard',
                                            subscription_id: '<?php echo esc_attr($this->subscription_id); ?>',
                                            start_date: '<?php echo esc_js(gmdate('d-m-Y', strtotime("-1 month"))) ?>',
                                            end_date: '<?php echo esc_js(gmdate('d-m-Y', strtotime("now"))) ?>',
                                            conversios_nonce: '<?php echo esc_js(wp_create_nonce('conversios_nonce')); ?>'
                                        };
                                        jQuery.ajax({
                                            type: "POST",
                                            dataType: "json",
                                            url: tvc_ajax_url,
                                            data: post_data,
                                            success: function(response) {
                                                //console.log("ga4 data", response);
                                                jQuery("#ga4reportcard .spinner-border").addClass('d-none');
                                                if (response.error == false) {
                                                    jQuery("#dash-reportgenerated").removeClass("d-none");
                                                    if (Object.keys(response.data).length > 0) {
                                                        var data = JSON.parse(response.data);
                                                        var dashboard_data_point = data.dashboard_data_point;
                                                        Object.keys(dashboard_data_point).forEach(function(key, index) {
                                                            let rawval = dashboard_data_point[key];
                                                            let parsedval = parseFloat(rawval).toFixed(2);
                                                            let divid = key.replace("compare_", "");
                                                            if (key.includes("compare_")) {
                                                                if (rawval < 0) {
                                                                    jQuery("#" + divid + " .market .reddown").removeClass("d-none");
                                                                } else {
                                                                    jQuery("#" + divid + " .market .greenup").removeClass("d-none");
                                                                }
                                                                jQuery("#" + divid + " .market span").html(rawval + "%");
                                                            } else {
                                                                if (key == "averagePurchaseRevenue" || key == "totalRevenue") {
                                                                    let currsymb = tvc_helper.get_currency_symbols(data.currencyCode);
                                                                    jQuery("#" + divid + " .price").html(currsymb + parsedval);
                                                                } else {
                                                                    jQuery("#" + divid + " .price").html(rawval);
                                                                }
                                                            }
                                                        });
                                                    }
                                                } else {
                                                    console.log("error", "Error", "Analytics report data not fetched");
                                                    jQuery("#dash-reportnotgenerated").removeClass("d-none");
                                                }
                                            }
                                        });
                                    }
                                });
                            </script>
                        <?php } ?>
                        <div class="accbutbox d-flex justify-content-end">
                            <a class="btn btn-outline-primary px-4 d-none" href="<?php echo esc_url('admin.php?page=conversios-google-analytics'); ?>">
                                <?php esc_html_e("Set Up Manually", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </a>
                            <a class="btn btn-primary ms-3 px-4 <?php echo esc_attr($launch_cls); ?>" href="<?php echo esc_url('admin.php?page=conversios&wizard=pixelandanalytics'); ?>">
                                <?php esc_html_e("Launch Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            <?php }
        function evenOdd($number)
        {
            if ($number % 2 == 0) {
                return 0;  //Even
            } else {
                return 1; //Odd
            }
        }
        function getEvenOddAccordion()
        {
            $this->chkEvenOdd = $this->evenOdd($this->ee_options['subscription_id']);

            if (isset($this->ee_options['google_merchant_center_id']) && $this->ee_options['google_merchant_center_id'] !== '') {
                $this->is_channel_connected = true;
            }
            if (isset($this->ee_options['facebook_setting']) && $this->ee_options['facebook_setting']['fb_business_id'] !== '' && $this->is_channel_connected == false) {
                $this->is_channel_connected = true;
            }
            if (isset($this->ee_options['tiktok_setting']) && $this->ee_options['tiktok_setting']['tiktok_business_id'] !== '' && $this->is_channel_connected == false) {
                $this->is_channel_connected = true;
            }
            if ($this->chkEvenOdd == 1) {
            ?>
                <div class="accordion-body">
                    <ul class="ps-4 ms-2 mb-4">
                        <li class="pt-0 ps-1">
                            <p class="py-2"><?php esc_html_e("Embark on optimizing your product visibility and boosting your advertising efficiency with our easy-to-follow guide. Here’s how you can leverage this feature for the greatest impact:", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                            <div class="d-flex justify-content-between">
                                <h3>
                                    <?php esc_html_e("Step 1: Streamlined Channel Setup", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h3>

                                <span class="material-symbols-outlined <?php echo $this->is_channel_connected ? 'text-success' : 'text-warning' ?>">
                                    <?php echo $this->is_channel_connected ? 'check_circle' : 'history_toggle_off' ?>
                                </span>
                            </div>

                            <div class="convps_threefive">
                                <p class="fw-bold ps-3 py-1"><?php esc_html_e("One-Click Integration with Google Merchant Center and TikTok", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                <ul class="list-styled pt-2">
                                    <li><b><?php esc_html_e("Single Click Connection:", "enhanced-e-commerce-for-woocommerce-store"); ?> </b>
                                        <?php esc_html_e("Instantly link to Google Merchant Center and TikTok.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                    <li><b><?php esc_html_e("Dual-Channel Benefits:", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                        <?php esc_html_e("Boost visibility on Google and TikTok, enhancing ROAS.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                </ul>
                            </div>
                        </li>
                        <li class="pt-0 ps-1">
                            <div class="d-flex justify-content-between">
                                <h3>
                                    <?php esc_html_e("Step 2: Effortless Product Attribute Mapping", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h3>
                                <span class="material-symbols-outlined product-attribute text-warning">
                                    history_toggle_off
                                </span>
                            </div>
                            <div class="convps_threefive">
                                <p class="fw-bold ps-3 py-1"><?php esc_html_e("Automatic Alignment with Conversios", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                <ul class="list-styled pt-2">
                                    <li><b><?php esc_html_e("Intuitive Mapping: ", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                        <?php esc_html_e("Automatically sync WooCommerce categories and attributes with Conversios.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                    <li><b><?php esc_html_e("Enhanced Discoverability:", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                        <?php esc_html_e("Ensure accurate categorization for better visibility.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                </ul>
                            </div>
                        </li>
                        <li class="pt-0 ps-1">
                            <div class="d-flex justify-content-between">
                                <h3><?php esc_html_e("Step 3 : Advanced Product Feed Management", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                <span class="material-symbols-outlined create-product-feed text-warning">
                                    history_toggle_off
                                </span>
                            </div>
                            <div class="convps_threefive">
                                <p class="fw-bold ps-3 py-1"><?php esc_html_e("Centralized Feed Optimization", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                <ul class="list-styled pt-2">
                                    <li><b><?php esc_html_e("Single Click Connection:", "enhanced-e-commerce-for-woocommerce-store"); ?> </b>
                                        <?php esc_html_e("Instantly link to Google Merchant Center and TikTok.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                    <li><b><?php esc_html_e("Dual-Channel Benefits:", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                        <?php esc_html_e("Boost visibility on Google and TikTok, enhancing ROAS.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <div class="convps_threefive">
                        <p><?php esc_html_e("By implementing these steps, you position your products for optimal online visibility and", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <br /><?php esc_html_e("engagement, ensuring a competitive edge in the digital marketplace. Start now to fully", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <br /><?php esc_html_e("utilize these features and drive tangible results for your eCommerce business.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </p>
                    </div>
                    <div class="feedTable"></div>
                    <div class="accbutbox d-flex justify-content-end">
                        <a class="btn btn-outline-primary feed-management d-none" href="<?php echo esc_url('admin.php?page=conversios-google-shopping-feed&tab=feed_list'); ?>">
                            <?php esc_html_e("Go To Feed Management", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </a>
                        <img class="pf_wizard_img align-self-baseline setupinfive scroll-bounce" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/setupin5min.png'); ?>">
                        <a class="btn btn-primary ms-3 pf_wizard" href="<?php echo esc_url('admin.php?page=conversios&wizard=productFeedOdd'); ?>">
                            <?php esc_html_e("Launch Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </a>
                    </div>
                </div> <?php
                    } else { ?>
                <div class="accordion-body">
                    <ul class="ps-4 ms-2 mb-4">
                        <li class="pt-0 ps-1">
                            <p class="py-2"><?php esc_html_e("Embark on optimizing your product visibility and boosting your advertising efficiency with our easy-to-follow guide. Here’s how you can leverage this feature for the greatest impact:", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                            <div class="d-flex justify-content-between">
                                <h3>
                                    <?php esc_html_e("Step 1: Streamlined Channel Setup", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </h3>

                                <span class="material-symbols-outlined <?php echo $this->is_channel_connected ? 'text-success' : 'text-warning' ?>">
                                    <?php echo $this->is_channel_connected ? 'check_circle' : 'history_toggle_off' ?>
                                </span>
                            </div>

                            <div class="convps_threefive">
                                <p class="fw-bold ps-3 py-1"><?php esc_html_e("One-Click Integration with Google Merchant Center and TikTok", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                <ul class="list-styled pt-2">
                                    <li><b><?php esc_html_e("Single Click Connection:", "enhanced-e-commerce-for-woocommerce-store"); ?> </b>
                                        <?php esc_html_e("Instantly link to Google Merchant Center and TikTok.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                    <li><b><?php esc_html_e("Dual-Channel Benefits:", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                        <?php esc_html_e("Boost visibility on Google and TikTok, enhancing ROAS.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                </ul>
                            </div>
                        </li>
                        <li class="pt-0 ps-1">
                            <div class="d-flex justify-content-between">
                                <h3><?php esc_html_e("Step 2: Advanced Product Feed Management", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                <span class="material-symbols-outlined create-product-feed text-warning">
                                    history_toggle_off
                                </span>
                            </div>
                            <div class="convps_threefive">
                                <p class="fw-bold ps-3 py-1"><?php esc_html_e("Centralized Feed Optimization", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                <ul class="list-styled pt-2">
                                    <li><b><?php esc_html_e("Single Click Connection:", "enhanced-e-commerce-for-woocommerce-store"); ?> </b>
                                        <?php esc_html_e("Instantly link to Google Merchant Center and TikTok.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                    <li><b><?php esc_html_e("Dual-Channel Benefits:", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                        <?php esc_html_e("Boost visibility on Google and TikTok, enhancing ROAS.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <div class="convps_threefive">
                        <p><?php esc_html_e("By implementing these steps, you position your products for optimal online visibility and", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <br /><?php esc_html_e("engagement, ensuring a competitive edge in the digital marketplace. Start now to fully", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            <br /><?php esc_html_e("utilize these features and drive tangible results for your eCommerce business.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </p>
                    </div>
                    <div class="feedTable"></div>
                    <div class="accbutbox d-flex justify-content-end">
                        <a class="btn btn-outline-primary feed-management d-none" href="<?php echo esc_url('admin.php?page=conversios-google-shopping-feed&tab=feed_list'); ?>">
                            <?php esc_html_e("Go To Feed Management", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </a>
                        <img class="pf_wizard_img align-self-baseline setupinfive scroll-bounce" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/setupin5min.png'); ?>">
                        <a class="btn btn-primary ms-3 pf_wizard" href="<?php echo esc_url('admin.php?page=conversios&wizard=productFeedEven'); ?>">
                            <?php esc_html_e("Launch Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
                        </a>
                    </div>
                </div>

            <?php }
                }
                public function get_pf_accordion()
                { ?>
            <style>
                .draft {
                    background-color: #f5e0aa;
                    color: #dca310;
                }

                .synced {
                    background-color: #c3f6e7;
                    color: #09bd83;
                }

                .failed {
                    background-color: #f8d9dd;
                    color: #f43e56;
                }

                .deleted {
                    background-color: #c8d1cf;
                    color: #5d6261;
                }

                .inprogress {
                    background-color: #c8e3f3;
                    color: #209ee1;
                }

                .badgebox {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    width: 100px;
                    padding: 2px 10px;
                    border-radius: 30px;
                    margin-bottom: 6px;
                    position: relative;
                    height: 22px;
                    font-size: 12px;
                    font-weight: 500;
                    margin: 0 auto;
                    margin-bottom: 10px;
                }
            </style>
            <div class="accordion-item">
                <div id="pf_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:100%;">
                    <div class="indeterminate"></div>
                </div>
                <h2 class="accordion-header d-flex p-2" id="flush-headingTwo">
                    <button class="accordion-button collapsed product-feed-accordian fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                        <img class="pe-2" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/product_feed.png'); ?>">
                        <?php esc_html_e("Product Feed Manager for Google Shopping & Tiktok", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </h2>
                <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                    <?php $this->getEvenOddAccordion() ?>
                </div>
            </div>
            <script>
                jQuery(document).on('click', '.product-feed-accordian', function() {
                    if (jQuery(this).attr("aria-expanded") == 'true') {
                        var chkEvenOdd = '<?php echo esc_js($this->chkEvenOdd) ?>';
                        var is_channel_connected = '<?php echo esc_js($this->is_channel_connected) ?>';
                        var post_data = {
                            action: "get_pf_accordian_data",
                            conv_licence_nonce: '<?php echo esc_js(wp_create_nonce("conv_lic_nonce")); ?>',
                        }
                        jQuery.ajax({
                            type: "POST",
                            dataType: "json",
                            url: tvc_ajax_url,
                            data: post_data,
                            beforeSend: function() {
                                jQuery('#pf_loader').removeClass('d-none')
                                jQuery(".pf_wizard").css("pointer-events", "none");
                            },
                            success: function(response) {
                                jQuery('#pf_loader').addClass('d-none')
                                jQuery(".pf_wizard").css("pointer-events", "auto");
                                if (is_channel_connected == true && response.ee_prod_mapped_attrs !== false && chkEvenOdd == 1) {
                                    jQuery('.pf_wizard').text('Create Feed')
                                    jQuery('.pf_wizard_img').addClass('d-none')
                                } else if (is_channel_connected == true && chkEvenOdd == 0) {
                                    jQuery('.pf_wizard').text('Create Feed')
                                    jQuery('.pf_wizard_img').addClass('d-none')
                                }
                                jQuery('.feedTable').empty()
                                if (response.ee_prod_mapped_attrs !== false) {
                                    jQuery('.product-attribute').removeClass('text-warning');
                                    jQuery('.product-attribute').addClass('text-success');
                                    jQuery('.product-attribute').text('check_circle');
                                }
                                if (response.feed_count !== 0) {
                                    jQuery('.create-product-feed').removeClass('text-warning');
                                    jQuery('.create-product-feed').addClass('text-success');
                                    jQuery('.create-product-feed').text('check_circle');
                                    jQuery('.feed-management').removeClass('d-none')
                                    var feed_wise_url = "admin.php?page=conversios-google-shopping-feed&tab=";
                                    var html = '<div class="border border-bottom-0 rounded-top "><label class="p-2">Recent Feed List</label></div>' +
                                        '<div class="table-responsive "><table class="table tablediv border">' +
                                        '<thead class="table-light">' +
                                        '<tr><th class="fw-semibold fs-14">FEED NAME</th><th class="fw-semibold fs-14">CHANNELS</th>' +
                                        '<th class="fw-semibold fs-14">LAST SYNC</th><th class="fw-semibold fs-14 text-center">STATUS</th></tr></thead>' +
                                        '<tbody>';
                                    jQuery.each(response.feed_data, function(index, value) {
                                        html += '<tr>';
                                        if (value.is_delete == '1') {
                                            html += '<td><span style="cursor: no-drop; font-size: 12px">' + value.feed_name + '</span></td>';
                                        } else {
                                            html += '<td><span style="font-size: 12px"><a style="font-size: 12px" title="Go to feed wise product list" href="' + feed_wise_url + 'product_list&id=' + value.id + '">' + value.feed_name + '</a></span></td>';
                                        }
                                        html += '<td>';
                                        channel_id = value.channel_ids.split(",");
                                        jQuery.each(channel_id, function(i, val) {
                                            if (val == '1') {
                                                html += '<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/google_channel_logo.png') ?>" />';
                                            } else if (val == '2') {
                                                //html +='<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/fb_channel_logo.png') ?>" />';
                                            } else if (val == '3') {
                                                html += '<img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/tiktok_channel_logo.png') ?>" />';
                                            }
                                        });
                                        html += '</td><td class="align-middle">';
                                        var val_lastsync = "NA";
                                        var val_lastsynctime = "NA";
                                        if (value.last_sync_date && value.last_sync_date !== '0000-00-00 00:00:00') {
                                            var inputDate = new Date(value.last_sync_date);
                                            var day = ('0' + inputDate.getDate()).slice(-2);
                                            var monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                                            var month = monthNames[inputDate.getMonth()];
                                            var year = inputDate.getFullYear();
                                            val_lastsync = day + ' ' + month + ' ' + year;
                                            var hours = ('0' + inputDate.getHours()).slice(-2);
                                            var minutes = ('0' + inputDate.getMinutes()).slice(-2);
                                            var period = inputDate.getHours() < 12 ? 'AM' : 'PM';
                                            val_lastsynctime = hours + ':' + minutes + ' ' + period;
                                        }
                                        html += '<p style="font-size: 12px">' + val_lastsync + '</p><p class="mb-0" style="font-size: 12px">' + val_lastsynctime + '</p></td><td class="align-middle ">';
                                        if (value.is_delete == '1') {
                                            html += '<span class="badgebox deleted" style="font-size: 12px">Deleted</span>';
                                        } else {
                                            var draft = 0;
                                            var inprogress = 0;
                                            var synced = 0;
                                            var failed = 0;
                                            switch (value.status) {
                                                case 'Draft':
                                                    draft++;
                                                    break;

                                                case 'In Progress':
                                                    inprogress++;
                                                    break;

                                                case 'Synced':
                                                    synced++;
                                                    break;

                                                case 'Failed':
                                                    failed++;
                                                    break;
                                            }

                                            switch (value.tiktok_status) {
                                                case 'Draft':
                                                    draft++;
                                                    break;

                                                case 'In Progress':
                                                    inprogress++;
                                                    break;

                                                case 'Synced':
                                                    synced++;
                                                    break;

                                                case 'Failed':
                                                    failed++;
                                                    break;
                                            }

                                            // switch (value.fb_status) {
                                            // case 'Draft':
                                            //     draft++;
                                            //     break;

                                            // case 'In Progress':
                                            //     inprogress++;
                                            //     break;

                                            // case 'Synced':
                                            //     synced++;
                                            //     break;

                                            // case 'Failed':
                                            //     failed++;
                                            //     break;
                                            // }
                                            if (draft !== 0) {
                                                var gmc = '';
                                                var tiktok = '';
                                                var fb = '';
                                                if (value.status == 'Draft') {
                                                    gmc = "<img class='draft-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/google_channel_logo.png') ?>' />";
                                                }
                                                if (value.tiktok_status == 'Draft') {
                                                    tiktok = "<img class='draft-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/tiktok_channel_logo.png') ?>' />";
                                                }
                                                // if(value.fb_status == 'Draft'){
                                                //     fb ="<img class='draft-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/fb_channel_logo.png') ?>' />";
                                                // }
                                                html += '<div class="badgebox draft" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover" data-bs-trigger="hover focus">' +
                                                    'Draft<div class="count-badge" style="margin-top:-4px;color:#DCA310">' + draft + '</div></div>' +
                                                    '<input type="hidden" class="draftGmcImg" value="' + gmc + '">' +
                                                    '<input type="hidden" class="draftTiktokImg" value="' + tiktok + '">' +
                                                    '<input type="hidden" class="draftfbImg" value="' + fb + '">';
                                            }
                                            if (inprogress !== 0) {
                                                var gmc = '';
                                                var tiktok = '';
                                                var fb = '';
                                                if (value.status == 'In Progress') {
                                                    gmc = "<img class='inprogress-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/google_channel_logo.png') ?>' />";
                                                }
                                                if (value.tiktok_status == 'In Progress') {
                                                    tiktok = "<img class='inprogress-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/tiktok_channel_logo.png') ?>' />";
                                                }
                                                // if(value.fb_status == 'In Progress'){
                                                //     fb ="<img class='inprogress-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/fb_channel_logo.png') ?>' />";
                                                // }
                                                html += '<div class="badgebox inprogress" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover" data-bs-trigger="hover focus">' +
                                                    'In Progress<div class="count-badge" style="margin-top:-4px;color:#209ee1">' + inprogress + '</div></div>' +
                                                    '<input type="hidden" class="inprogressGmcImg" value="' + gmc + '">' +
                                                    '<input type="hidden" class="inprogressTiktokImg" value="' + tiktok + '">' +
                                                    '<input type="hidden" class="inprogressfbImg" value="' + fb + '">';
                                            }
                                            if (synced !== 0) {
                                                var gmc = '';
                                                var tiktok = '';
                                                var fb = '';
                                                if (value.status == 'Synced') {
                                                    gmc = "<img class='synced-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/google_channel_logo.png') ?>' />";
                                                }
                                                if (value.tiktok_status == 'Synced') {
                                                    tiktok = "<img class='synced-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/tiktok_channel_logo.png') ?>' />";
                                                }
                                                // if(value.fb_status == 'Synced'){
                                                //     fb ="<img class='synced-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/fb_channel_logo.png') ?>' />";
                                                // }
                                                html += '<div class="badgebox synced" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover" data-bs-trigger="hover focus">' +
                                                    'Synced<div class="count-badge" style="margin-top:-4px;color:#09bd83">' + synced + '</div></div>' +
                                                    '<input type="hidden" class="syncedGmcImg" value="' + gmc + '">' +
                                                    '<input type="hidden" class="syncedTiktokImg" value="' + tiktok + '">' +
                                                    '<input type="hidden" class="syncedfbImg" value="' + fb + '">';
                                            }
                                            if (failed !== 0) {
                                                var gmc = '';
                                                var tiktok = '';
                                                var fb = '';
                                                if (value.status == 'Failed') {
                                                    gmc = "<img class='failed-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/google_channel_logo.png') ?>' />";
                                                }
                                                if (value.tiktok_status == 'Failed') {
                                                    tiktok = "<img class='failed-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/tiktok_channel_logo.png') ?>' />";
                                                }
                                                // if(value.fb_status == 'Failed'){
                                                //     fb ="<img class='failed-status' src='<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/fb_channel_logo.png') ?>' />";
                                                // }
                                                html += '<div class="badgebox failed" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover" data-bs-trigger="hover focus">' +
                                                    'Failed<div class="count-badge" style="margin-top:-4px;color:#DCA310">' + failed + '</div></div>' +
                                                    '<input type="hidden" class="failedGmcImg" value="' + gmc + '">' +
                                                    '<input type="hidden" class="failedTiktokImg" value="' + tiktok + '">' +
                                                    '<input type="hidden" class="failedfbImg" value="' + fb + '">';
                                            }
                                        }
                                        html += '</td></tr>';
                                    });
                                    html += '</tbody></table></div>';
                                    jQuery('.feedTable').html(html)

                                }
                            }
                        });
                    }
                })
            </script>
        <?php }
                public function get_campain_accordan()
                {
                    $campaign_connected = false;
                    if (isset($this->ee_options['google_merchant_center_id']) && isset($this->ee_options['google_ads_id']) && $this->ee_options['google_merchant_center_id'] !== '' && isset($this->ee_options['google_ads_id']) &&  $this->ee_options['google_ads_id'] !== '') {
                        $campaign_connected = true;
                    }
        ?>
            <div class="accordion-item">
                <div id="campaign_loader" class="progress-materializecss d-none ps-2 pe-2" style="width:100%;">
                    <div class="indeterminate"></div>
                </div>
                <h2 class="accordion-header d-flex p-2" id="flush-headingFour">
                    <button class="accordion-button collapsed camapign-management-accordian fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                        <img class="pe-2" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/Campaign-Management.png'); ?>">
                        <?php esc_html_e("Campaign Management For Google Shopping", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        <ul class="ps-4 ms-2 mb-4">
                            <li class="pt-0 ps-1">
                                <p class="py-2"><?php esc_html_e("Seamlessly connect Google Merchant Center with Google ads and select specific products to create performance max campaigns.", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                <div class="d-flex justify-content-between">
                                    <h3>
                                        <?php esc_html_e("Step 1: Link GMC & Google Ads Accounts", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </h3>

                                    <span class="material-symbols-outlined <?php echo $campaign_connected ? 'text-success' : 'text-warning' ?> ">
                                        <?php echo $campaign_connected ? 'check_circle' : 'history_toggle_off' ?>
                                    </span>
                                </div>

                                <div class="convps_threefive">
                                    <ul class="list-styled pt-2">
                                        <li><?php esc_html_e("Instantly integrate your Google Merchant Center with Google Ads with just one click.", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex justify-content-between">
                                    <h3><?php esc_html_e("Step 2: Choose Product Feed & Launch Campaign", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                    <span class="material-symbols-outlined create-product-feed-campaign text-warning">
                                        history_toggle_off
                                    </span>
                                </div>
                                <div class="convps_threefive">
                                    <ul class="list-styled pt-2">
                                        <li><?php esc_html_e("Easily select your product feed and initiate your campaign. Utilize web and behavioral analytics to understand visitor actions on your site, enabling informed decisions to enhance conversions and sales", "enhanced-e-commerce-for-woocommerce-store"); ?></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                        <div class="campaignTable"></div>
                        <div class="accbutbox d-flex justify-content-end pt-4">
                            <img class="campaign_wizard_img align-self-baseline setupinfive scroll-bounce" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/setupin5min.png'); ?>">
                            <a class="btn btn-primary ms-3 campaign_wizard" href="<?php echo esc_url('admin.php?page=conversios&wizard=campaignManagement'); ?>">
                                <?php $campaign_connected ? esc_html_e("Create Campaign", "enhanced-e-commerce-for-woocommerce-store") : esc_html_e("Launch Wizard", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                jQuery(document).on('click', '.camapign-management-accordian', function() {
                    if (jQuery(this).attr("aria-expanded") == 'true') {
                        var google_ads_id = "<?php echo isset($this->ee_options['google_ads_id']) ? esc_js($this->ee_options['google_ads_id']) : '' ?>";
                        if (google_ads_id != '') {
                            var data = {
                                action: "get_category_for_filter",
                                type: "get_campaign_accordan",
                                get_category_for_filter: "<?php echo esc_html(wp_create_nonce('get_category_for_filter-nonce')); ?>"
                            };
                            jQuery.ajax({
                                type: "POST",
                                dataType: "json",
                                url: tvc_ajax_url,
                                data: data,
                                beforeSend: function() {},
                                success: function(response) {
                                    if (response.count > 0) {
                                        jQuery('.create-product-feed-campaign').removeClass('text-warning')
                                        jQuery('.create-product-feed-campaign').html('check_circle')
                                        jQuery('.create-product-feed-campaign').addClass('text-success')
                                    }
                                }
                            });

                            var data = {
                                action: 'get_pmax_campaign_list',
                                subscription_id: '<?php echo esc_attr($this->subscription_id); ?>',
                                plan_id: "<?php echo esc_js(($this->plan_id)) ?>",
                                plugin_url: '<?php echo esc_url(ENHANCAD_PLUGIN_URL); ?>',
                                page_size: 3,
                                page_token: "",
                                page: 1,
                                google_ads_id: google_ads_id,
                                conversios_nonce: '<?php echo esc_js(wp_create_nonce('conversios_nonce')); ?>'
                            };
                            jQuery.ajax({
                                type: "POST",
                                dataType: "json",
                                url: tvc_ajax_url,
                                data: data,
                                beforeSend: function() {
                                    jQuery('#campaign_loader').removeClass('d-none')
                                    jQuery(".campaign_wizard").css("pointer-events", "none");
                                },
                                success: function(response) {
                                    jQuery('#campaign_loader').addClass('d-none')
                                    jQuery(".campaign_wizard").css("pointer-events", "auto");
                                    if (typeof(response.data) !== "undefined" && response.data.results !== null) {
                                        jQuery('.campaign_wizard_img').addClass('d-none')
                                        var html = '<div class="border border-bottom-0 rounded-top "><label class="p-2">Recent Campaign List</label></div>' +
                                            '<div class="table-responsive "><table class="table tablediv border">' +
                                            '<thead class="table-light">' +
                                            '<tr><th class="fw-semibold fs-14 text-start">CAMPAIGN NAME</th><th class="fw-semibold fs-14 text-end">DAILY BUDGET (<?php echo esc_js($this->currency_symbol) ?>)</th><th class="fw-semibold fs-14 text-end">STATUS</th>' +
                                            '<th class="fw-semibold fs-14 text-end">CLICK</th><th class="fw-semibold fs-14 text-end">COST (<?php echo esc_js($this->currency_symbol) ?>)</th><th class="fw-semibold fs-14 text-end">CONVERSION</th><th class="fw-semibold fs-14 text-end">SALES</th></tr></thead>';
                                        html += '<tbody>';

                                        jQuery.each(response.data.results, function(index, value) {
                                            html += '<tr><td class="fs-12">' + value.campaign.name + '</td><td class="fs-12 text-end">' + numberWithCommas(parseInt(value.campaignBudget.amountMicros / 1000000).toFixed(2)) + '</td><td class="fs-12 text-end">' + value.campaignBudget.status + '</td>' +
                                                '<td class="fs-12 text-end">' + value.metrics.clicks + '</td><td class="fs-12 text-end">' + numberWithCommas((value.metrics.costMicros / 1000000).toFixed(2)) + '</td>' +
                                                '<td class="fs-12 text-end">' + numberWithCommas(value.metrics.conversions.toFixed(2)) + '</td><td class="fs-12 text-end">' + numberWithCommas(value.metrics.conversionsValue.toFixed(2)) + '</td></tr>'
                                        });
                                        html += '</tbody></table></div>';
                                        jQuery('.campaignTable').html(html)
                                    }
                                }
                            });
                        }
                    }
                });

                function numberWithCommas(x) {
                    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            </script>
        <?php }

                function get_sst_accordion()
                {
        ?>
            <div class="accordion-item">
                <h2 class="accordion-header d-flex p-2" id="flush-headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                        <img class="pe-2" src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/conv-accordio-sst.png'); ?>">
                        <?php esc_html_e("Server Side Tracking for GA4, Facebook, Tiktok, Snapchat & Google Ads.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                    </button>
                </h2>
                <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">

                    <div class="accordion-body">

                        <ul class="ps-4 ms-2 mb-4">
                            <li class="pt-0 ps-1">
                                <p class="py-2"><?php esc_html_e("Let’s automate end to end server side tracking for Google, Facebook, Tiktok and Snapchat in 3 easy steps.", "enhanced-e-commerce-for-woocommerce-store"); ?></p>
                                <div class="d-flex justify-content-between">
                                    <h3><?php esc_html_e("Step 1: Initialize Both Web and Server Containers", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                </div>
                                <div class="convps_threefive">
                                    <?php esc_html_e("Effortlessly link your web and server containers in just two steps, enabling server-side", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <br><?php esc_html_e("event tracking. This enhances data accuracy while mitigating the effects of ad blockers ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <br><?php esc_html_e("and reducing reliance on third-party cookies.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                            </li>

                            <li class="pt-0 ps-1">
                                <div class="d-flex justify-content-between">
                                    <h3><?php esc_html_e("Step 2: Choose Your Server's Location and Set It Up", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                </div>
                                <div class="convps_threefive">
                                    <?php esc_html_e("Create a Google Cloud server with ease by selecting your preferred region, improving ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <br><?php esc_html_e("site speed and ensuring GDPR compliance.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                            </li>

                            <li class="pt-0 ps-1">
                                <div class="d-flex justify-content-between">
                                    <h3><?php esc_html_e("step 3: Implement Server-Side Tracking Across Major Platforms", "enhanced-e-commerce-for-woocommerce-store"); ?></h3>
                                </div>
                                <div class="convps_threefive">
                                    <?php esc_html_e("Simplify the integration of server-side tracking for Google Analytics 4, Facebook, TikTok,", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <br><?php esc_html_e("Snapchat, and Google Ads. Set up conversion APIs for Facebook, TikTok, and Snapchat", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    <br><?php esc_html_e("effortlessly.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                </div>
                            </li>

                        </ul>

                        <div class="accbutbox d-flex justify-content-end">
                            <div class="btn btn-outline-primary conv-link-blue ms-2 fw-bold-500 upgradetopro_badge upgradetopro_badge_invert" data-bs-toggle="modal" data-bs-target="#convSsttoProModal">
                                <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/logos/upgrade_badge.png'); ?>" />
                                <?php esc_html_e("Upgrade to Premium", "enhanced-e-commerce-for-woocommerce-store"); ?>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        <?php
                }
                // Main function for HTM structure
                public function current_html()
                {
        ?>
            <style>
                .accordion-item {
                    border: 1px solid #CCC !important;
                    border-radius: 2px !important;
                    margin-top: 8px;
                    box-shadow: 0px 0px 5px 0px #00000038;
                }
            </style>
            <section style="max-width: 1200px; margin:auto;">
                <div class="dash-convo">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="dash-area">
                                    <div class="dashwhole-box">
                                        <div class="head-title d-flex justify-content-between">
                                            <h2 class="fw-bold text-dark">
                                                <?php esc_html_e("Welcome to Your Onboarding Journey!", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="welcome-wholebox">
                            <div class="row">
                                <div class="col-xl-8 col-lg-12 col-md-12 col-12 ">
                                    <!-- licence key html call-->
                                    <?php
                                    if ($this->plan_id == 1) {
                                        $this->dashboard_licencebox_html();
                                    }
                                    ?>

                                    <div class="pixel-setup card">
                                        <div class="card-body">
                                            <div class="accordion accordion-flush" id="convdashacc_box">

                                                <!-- Pixal and analytics accordion -->
                                                <?php $this->get_pixel_accordion(); ?>
                                                <!-- Feed Collaps -->
                                                <!-- Product Feed accordion --------->
                                                <?php $this->get_pf_accordion(); ?>
                                                <!----------  END ------------------->
                                                <!-- Reporting Collaps -->
                                                <?php $this->get_reporting_accordion(); ?>
                                                <!-- Campaign Collaps -->
                                                <?php $this->get_campain_accordan(); ?>

                                                <?php $this->get_sst_accordion(); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <?php $this->dashboard_recentpostbox_html(); ?>

                                </div>

                                <div class="col-xl-4 col-lg-12 col-md-12 col-12">
                                    <?php $this->dashboard_videocardbox_html(); ?>
                                    <?php $this->dashboard_requestfeature_html(); ?>
                                    <?php $this->dashboard_gethelp_html(); ?>
                                    <?php $this->dashboard_convproducts_html(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Modal SST Pro-->
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
            <!-- Modal SST Pro End -->
            <!-- End -->
            <script>
                jQuery(function() {
                    var returnFrom = "<?php echo isset($_GET['returnFrom']) ? esc_js(sanitize_text_field($_GET['returnFrom'])) : '' ?>";
                    if (returnFrom == 'productFeed') {
                        jQuery('[data-bs-target="#flush-collapseTwo"]').trigger('click')
                        jQuery('.product-feed-accordian').trigger('click')
                        jQuery('[data-bs-target="#flush-collapseOne"]').addClass('collapsed')
                        jQuery('#flush-collapseOne').removeClass('show')
                        jQuery('[data-bs-target="#flush-collapseThree"]').addClass('collapsed')
                        jQuery('#flush-collapseThree').removeClass('show')
                        jQuery('[data-bs-target="#flush-collapseFour"]').addClass('collapsed')
                        jQuery('#flush-collapseFour').removeClass('show')
                    } else if (returnFrom == 'campaignManagement') {
                        jQuery('[data-bs-target="#flush-collapseFour"]').trigger('click')
                        jQuery('.camapign-management-accordian').trigger('click')
                        jQuery('[data-bs-target="#flush-collapseOne"]').addClass('collapsed')
                        jQuery('#flush-collapseOne').removeClass('show')
                        jQuery('[data-bs-target="#flush-collapseThree"]').addClass('collapsed')
                        jQuery('#flush-collapseThree').removeClass('show')
                        jQuery('[data-bs-target="#flush-collapseTwo"]').addClass('collapsed')
                        jQuery('#flush-collapseTwo').removeClass('show')
                    }
                });
                /*********************Card Popover Start***********************************************************************/
                jQuery(document).on('mouseover', '.synced', function() {
                    var syncedGmcImg = jQuery(this).next('.syncedGmcImg').val();
                    var syncedTiktokImg = jQuery(this).next('.syncedGmcImg').next('.syncedTiktokImg').val();
                    var syncedfbImg = jQuery(this).next('.syncedGmcImg').next('.syncedTiktokImg').next('.syncedfbImg').val();
                    var content = '<div class="popover-box border-synced">' + syncedGmcImg + '  ' + syncedTiktokImg + ' ' + syncedfbImg + '</div>';
                    jQuery(this).popover({
                        html: true,
                        template: content,
                    });
                    jQuery(this).popover('show');
                })

                jQuery(document).on('mouseover', '.failed', function() {
                    var failedGmcImg = jQuery(this).next('.failedGmcImg').val();
                    var failedTiktokImg = jQuery(this).next('.failedGmcImg').next('.failedTiktokImg').val();
                    var failedfbImg = jQuery(this).next('.failedGmcImg').next('.failedTiktokImg').next('.failedfbImg').val();
                    var content = "<div class='popover-box border-failed'>" + failedGmcImg + "  " + failedTiktokImg + " " + failedfbImg + "</div>";
                    jQuery(this).popover({
                        html: true,
                        template: content,
                    });
                    jQuery(this).popover('show');
                })

                jQuery(document).on('mouseover', '.draft', function() {
                    var draftGmcImg = jQuery(this).next('.draftGmcImg').val();
                    var draftTiktokImg = jQuery(this).next('.draftGmcImg').next('.draftTiktokImg').val();
                    var draftfbImg = jQuery(this).next('.draftGmcImg').next('.draftTiktokImg').next('.draftfbImg').val();
                    var content = '<div class="popover-box border-draft">' + draftGmcImg + '  ' + draftTiktokImg + ' ' + draftfbImg + '</div>';
                    jQuery(this).popover({
                        html: true,
                        template: content,
                    });
                    jQuery(this).popover('show');
                })
                jQuery(document).on('mouseover', '.inprogress', function() {
                    var inprogressGmcImg = jQuery(this).next('.inprogressGmcImg').val();
                    var inprogressTiktokImg = jQuery(this).next('.inprogressGmcImg').next('.inprogressTiktokImg').val();
                    var inprogressfbImg = jQuery(this).next('.inprogressGmcImg').next('.inprogressTiktokImg').next('.inprogressfbImg').val();
                    var content = '<div class="popover-box border-inprogress">' + inprogressGmcImg + '  ' + inprogressTiktokImg + ' ' + inprogressfbImg + '</div>';
                    jQuery(this).popover({
                        html: true,
                        template: content,
                    });
                    jQuery(this).popover('show');
                })
                /*********************Card Popover  End**************************************************************************/
            </script>
<?php
                }
            }
        }
        new Conversios_Dashboard();
