<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TVC_Pricings
{
    protected $TVC_Admin_Helper = "";
    protected $url = "";
    protected $subscriptionId = "";
    protected $google_detail;
    protected $customApiObj;
    protected $pro_plan_site;
    protected $convositeurl;

    public function __construct()
    {
        $this->TVC_Admin_Helper = new TVC_Admin_Helper();
        $this->customApiObj = new CustomApi();
        $this->subscriptionId = $this->TVC_Admin_Helper->get_subscriptionId();
        $this->google_detail = $this->TVC_Admin_Helper->get_ee_options_data();
        $this->TVC_Admin_Helper->add_spinner_html();
        $this->pro_plan_site = $this->TVC_Admin_Helper->get_pro_plan_site();
        $this->convositeurl = "http://conversios.io";
        $this->create_form();
    }

    public function create_form()
    {
        $close_icon = esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/close.png');
        $check_icon = esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/check.png');
?>


        <div class="convo-global">
            <div class="convo-pricingpage">
                <!-- pricing timer -->
                <div class="pricing-timer d-none">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="timer-box">
                                            <div id="time"> <span id="min">00</span>:<span id="sec">00</span></div>
                                        </div>
                                        <h5 class="card-title">
                                            <?php esc_html_e("Wait! Get 10% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </h5>
                                        <p class="card-text">
                                            <?php esc_html_e("Purchase any yearly plan in next 10 minutes with coupon code", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            <strong>
                                                <?php esc_html_e("FIRSTBUY10", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </strong>
                                            <?php esc_html_e("and get additional 10% off.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </p>
                                        <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_1_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                            <?php esc_html_e(" Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- business area -->
                <div class="business-area">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="title-text">
                                    <h2 data-aos="flip-up" data-aos-duration="1000" class="aos-init aos-animate"> <?php esc_html_e("Scale Your
                                Business Faster with Conversios", "enhanced-e-commerce-for-woocommerce-store"); ?></h2>
                                    <h3> <?php esc_html_e("Get", "enhanced-e-commerce-for-woocommerce-store"); ?><strong>
                                            <?php esc_html_e("15 days money back guarantee", "enhanced-e-commerce-for-woocommerce-store"); ?></strong>
                                        <?php esc_html_e("On any plan you choose.", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="myplan-wholebox">
                            <div class="row align-items-end">
                                <div class="col-auto me-auto">
                                    <div class="myplan-box">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" checked type="checkbox" role="switch" id="yearmonth_checkbox">
                                        </div>
                                        <!-- <p>Monthly | <span>Yearly</span> Get Flat 50% off on all yearly plans. </p> -->
                                    </div>
                                </div>
                                <div class="col-auto ms-auto">
                                    <div class="domain-box">
                                        <p><?php esc_html_e("Select Number Of Domains", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </p>
                                        <div class="choose-domainbox">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" checked>
                                                <label class="form-check-label" for="inlineRadio1"><?php esc_html_e("1", "enhanced-e-commerce-for-woocommerce-store"); ?></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="5">
                                                <label class="form-check-label" for="inlineRadio2"><?php esc_html_e("5", "enhanced-e-commerce-for-woocommerce-store"); ?></label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="10">
                                                <label class="form-check-label" for="inlineRadio3"><?php esc_html_e("10", "enhanced-e-commerce-for-woocommerce-store"); ?></label>
                                            </div>
                                            <!-- <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio4" value="10+">
                                        <label class="form-check-label" for="inlineRadio4">10+</label>
                                    </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pricingcard-wholebox">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?php esc_html_e(" Enterprise", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </h5>

                                            <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                <p class="card-text">
                                                    <?php esc_html_e("1 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("$399.00/", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    <span><?php esc_html_e(" year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e(" Regular Price:", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$798.00/year", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </span></div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>
                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=plansst_1_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                <p class="card-text">
                                                    <?php esc_html_e("5 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("$999.00/", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    <span><?php esc_html_e(" year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e(" Regular Price:", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$1998.00/year", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </span></div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_EY5&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                <p class="card-text">
                                                    <?php esc_html_e("10 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("$1999.00/", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    <span><?php esc_html_e(" year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e(" Regular Price:", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$3998 .00/year", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </span></div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_EY10&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e(" BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10+">
                                        <p class="card-text contactus">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">
                                                Contact Us
                                            </button>
                                        </p>
                                    </div> -->



                                            <ul class="feature-listing custom-scrollbar">
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e("Everything in Professional", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <span>&#43;</span>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Complete automation of server side tagging setup. No coding, no expertise needed.">
                                                            <?php esc_html_e("End to end Server-Side Tagging automation", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Powerful Google Cloud hosting for 100% uptime and security.">
                                                            <?php esc_html_e("Google cloud hosting", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Allows unlimited hits.">
                                                            <?php esc_html_e("Unlimited hits", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Quick & One click automation of server and web GTM container and datalayer for mentioned channels.">
                                                            <?php esc_html_e("sGTM automation for", "enhanced-e-commerce-for-woocommerce-store"); ?>

                                                        </button>
                                                        <ul class="sub-list">
                                                            <li><?php esc_html_e("- GA4", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                            </li>
                                                            <li><?php esc_html_e("- Google Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                            </li>
                                                            <li><?php esc_html_e("- Facebook pixel and conversions API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                            </li>
                                                            <li><?php esc_html_e("- TikTok pixel and events API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                            </li>
                                                            <li><?php esc_html_e("- Snapchat pixel and conversions API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Make your tagging first party compliant.">
                                                            <?php esc_html_e("Customer loader", "enhanced-e-commerce-for-woocommerce-store"); ?>

                                                        </button>

                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e(" Free setup and audit", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="features-link">
                                                <a href="#seeallfeatures"><?php esc_html_e("Compare All Features", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                                            </div>
                                            <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=plansst_1_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_EY5&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_EY10&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10+">
                                        <p class="card-text contactus">
                                          
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">
                                                Contact Us
                                            </button>
                                        </p>
                                    </div> -->
                                            <div class="popular-plan">
                                                <p><?php esc_html_e("Most Popular", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                    <div class="card active">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?php esc_html_e("Professional", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </h5>

                                            <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                <p class="card-text">
                                                    <?php esc_html_e("1 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("  $199.00/ ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$398.00/year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>
                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_1_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                <p class="card-text">
                                                    <?php esc_html_e("5 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("$299.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$598.00/year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_2_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                <p class="card-text">
                                                    <?php esc_html_e("10 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("$399.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$798.00/year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_3_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10+">
                                        <p class="card-text contactus">
                                           
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">
                                                <?php esc_html_e("Contact Us", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </button>
                                        </p>
                                    </div> -->



                                            <ul class="feature-listing custom-scrollbar">
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e(" Everything in Starter", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <span>&#43;</span>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Facebook Conversions API integration for all the e-commerce events and conversions. Enhances accurate audience building, campaign tracking and performance.">
                                                            <?php esc_html_e(" Facebook Conversions API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="TikTok Events API integration for all the e-commerce events and conversions. Enhances accurate audience building, campaign tracking and performance.">
                                                            <?php esc_html_e("TikTok Events API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Snapchat Conversions API integration for all the e-commerce events and conversions. Enhances accurate audience building, campaign tracking and performance.">
                                                            <?php esc_html_e(" Snapchat Conversions API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Builds dynamic remarketing audiences in ad channels like Google Ads, Meta, Snapchat, Tiktok, Pinterest, Microsoft Ads & more. Build and grow audiences based on the visitor browsing. ">
                                                            <?php esc_html_e(" Dynamic Audience building (8+ Ads Channels)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Reach out to our professional team for custom events tracking like form tracking, conversion tracking for different goals.">
                                                            <?php esc_html_e(" Custom events tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <span>&#43;</span>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Allows unlimited products sync.">
                                                            <?php esc_html_e(" Unlimited number of products sync", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Compatible with 50+ plugins so that you can sync any attribute you want. Reach out if you don't find specific attributes.">
                                                            <?php esc_html_e(" 50+ plugins compatibility", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <span>&#43;</span>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="A dedicated customer success manager ensures that everything is set up accurately and helps you solve any issue that you may face.">
                                                            <?php esc_html_e(" Dedicated Customer Success Manager", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e(" Priority support", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Analytics and Ads management becomes complicated some time. Our team of expert helps you in set up everything and performs audit so that you focus on the things that matter for your business.">
                                                            <?php esc_html_e(" Free setup and audit", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Worrying about lower ROAS or how to get started? Our team helps you define the right strategy for your business.">
                                                            <?php esc_html_e("Free consultation for campaign management", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="features-link">
                                                <a href="#seeallfeatures"><?php esc_html_e("Compare All Features", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                                            </div>

                                            <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_1_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_2_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_3_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10+">
                                        <p class="card-text contactus">
                                           
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">
                                                Contact Us
                                            </button>
                                        </p>
                                    </div> -->
                                            <div class="popular-plan">
                                                <p><?php esc_html_e("Most Popular", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">
                                                <?php esc_html_e("Starter", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </h5>

                                            <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                <p class="card-text">
                                                    <?php esc_html_e("1 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("$99.00/ ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$198.00/year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY1&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                <p class="card-text">
                                                    <?php esc_html_e("5 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("$199.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$398.00/year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY5&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                <p class="card-text">
                                                    <?php esc_html_e("10 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                                <div class="card-price">
                                                    <?php esc_html_e("$299.00/ ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="slash-price">
                                                    <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("$598.00/year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                </div>
                                                <div class="offer-price">
                                                    <?php esc_html_e("50% Off", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </div>

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY10&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10+">
                                        <p class="card-text contactus">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">
                                                <?php esc_html_e("Contact Us", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                            </button>
                                        </p>
                                    </div> -->

                                            <ul class="feature-listing custom-scrollbar">
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e("Everything in Free", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <span>&#43;</span>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="One click automation of your own GTM container for faster page speed and flexibility. Create tags, triggers & variables based on your needs.">
                                                            <?php esc_html_e("Automation of GTM container", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Automates complete e-commerce datalayer for your Wordpress or WooCommerce stores. Single unified datalayer automation that can be used with all the analytics and Ads tracking.">
                                                            <?php esc_html_e(" E-Commerce datalayer automation", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Automates complete GA4 e-commerce tracking. The most accurate and efficient GA4 solution in the market.">
                                                            <?php esc_html_e(" GA4 E-Commerce Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Enables conversion tracking for Ads channels like Google Ads, Meta (Facebook + Instagram), Snapchat, Tiktok, Pinterest, Microsoft Ads, Twitter & More. Measures and optimizes your campaign performance. ">
                                                            <?php esc_html_e(" Conversion tracking for 8+ Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <span>&#43;</span>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Set up high quality product feed for Ad Channels like Google, Facebook and Tiktok.">
                                                            <?php esc_html_e(" Product feed for 3 Ad Channels", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Allows upto 500 product sync.">
                                                            <?php esc_html_e(" Upto 500 products sync limit", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Keep your product details up to date in Google Merchant Center, Facebook Catalog and TikTok Catalog. Set time interval for auto product sync.">
                                                            <?php esc_html_e(" Schedule product sync", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Create and manage campaigns based on feeds directly in Google Ads.">
                                                            <?php esc_html_e(" Feed based Camapign Management", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <span>&#43;</span>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Know about e-commerce funnel, product, source and order performance reports from wordpress admin. Enables data driven decision making to increase conversion %.">
                                                            <?php esc_html_e(" E-Commerce reporting", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Enables you to measure the campaign performance in Google Ads.">
                                                            <?php esc_html_e(" Ads reporting", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="ChatGPT powered insights on your analytics and campaigns data.">
                                                            <?php esc_html_e(" AI powered Insights", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Schedule daily, weekly or monthly reports straight into your inbox.">
                                                            <?php esc_html_e(" Schedule email reports", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Create and manage Google Ads performance max campaigns and increase ROAS.">
                                                            <?php esc_html_e(" Product Ads Campaign Management", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="features-link">
                                                <a href="#seeallfeatures"><?php esc_html_e("Compare All Features", "enhanced-e-commerce-for-woocommerce-store"); ?></a>
                                            </div>

                                            <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY1&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY5&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">

                                                <div class="getstarted-btn">
                                                    <a class="btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY10&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                        <?php esc_html_e("BUY NOW", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10+">
                                        <p class="card-text contactus">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop">
                                                Contact Us
                                            </button>
                                        </p>
                                    </div> -->
                                            <div class="popular-plan">
                                                <p><?php esc_html_e("Most Popular", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>


                    </div>
                    <div class="moneyback-badge" data-aos="fade-right" data-aos-delay="50">
                        <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/money-back.png'); ?>" alt="Money Back Badge" class="img-fluid">
                    </div>
                </div>
                <!-- one stop section -->
                <div class="onestop-area">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="title-text">
                                    <p> <?php esc_html_e("50,000+ E-commerce Businesses Use Conversios To Scale Faster as One Stop Solution to", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        <?php esc_html_e(" Save Time, Efforts & Costs", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Compare feature -->
                <div class="comparefeature-wholebox" id="seeallfeatures">
                    <div class="comparefeature-area space">
                        <div class="container-full">
                            <div class="row">
                                <div class="col-12">
                                    <div class="title-text">
                                        <h2> <strong><?php esc_html_e("Comprehensive Feature", "enhanced-e-commerce-for-woocommerce-store"); ?></strong><?php esc_html_e(" Comparison", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </h2>
                                        <h3><?php esc_html_e("Explore our solutions all features in detail", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                            <div class="comparetable-box">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive custom-scrollbar">
                                            <table id="sticky-header-tbody-id" class="feature-table table ">
                                                <thead id="con_stick_this">
                                                    <tr>
                                                        <th scope="col" class="th-data">
                                                            <div class="feature-box">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <div class="card-icon">
                                                                            <img src="<?php echo esc_url(ENHANCAD_PLUGIN_URL . '/admin/images/pricing-privacy.png'); ?>" class="img-fluid">
                                                                        </div>
                                                                        <h5 class="card-title">
                                                                            <?php esc_html_e("100% No Risk ", "enhanced-e-commerce-for-woocommerce-store"); ?><br>
                                                                            <?php esc_html_e("Moneyback Gurantee", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </th>
                                                        <th scope="col" class="thd-data">
                                                            <div class="feature-box">
                                                                <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Enterprise", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("1 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$798.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$399.00/ ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>

                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=?pid&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin=plansst_1_y"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Enterprise", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("5 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$1998.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$999.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>


                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_EY5&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Enterprise", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("10 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$3998.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$1999.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>

                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_EY10&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <!-- <div class="dynamicprice_box d-none" boxperiod="yearly"
                                                                boxdomain="10+">
                                                                <div class="title card-title">Enterprise</div>
                                                                <p class="card-text contactus">
                                                                  
                                                                    <button type="button" class="btn btn-primary"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#staticBackdrop">
                                                                        Contact Us
                                                                    </button>
                                                                </p>

                                                            </div> -->
                                                            </div>
                                                        </th>
                                                        <th scope="col" class="thd-data">
                                                            <div class="feature-box">
                                                                <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Professional", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("1 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$398.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$199.00/ ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_1_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">

                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Professional", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("5 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$598.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$299.00/ ", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_2_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>


                                                                </div>
                                                                <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">

                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Professional", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("10 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$798.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$399.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_3_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>

                                                                </div>
                                                                <!-- <div class="dynamicprice_box d-none" boxperiod="yearly"
                                                                boxdomain="10+">
                                                                <div class="title card-title">Professional</div>
                                                                <p class="card-text contactus">
                                                                   
                                                                    <button type="button" class="btn btn-primary"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#staticBackdrop">
                                                                        Contact Us
                                                                    </button>
                                                                </p>

                                                            </div> -->
                                                            </div>
                                                        </th>
                                                        <th scope="col" class="thd-data">
                                                            <div class="feature-box">
                                                                <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">

                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Starter", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("1 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$198.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$99.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY1&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>


                                                                </div>
                                                                <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">

                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Starter", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("5 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$398.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$199.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY5&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>

                                                                </div>
                                                                <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">

                                                                    <div class="title card-title">
                                                                        <?php esc_html_e("Starter", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <p class="sub-title card-text">
                                                                        <?php esc_html_e("10 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </p>
                                                                    <div class="strike-price">
                                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                                            <?php esc_html_e("$598.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="price">
                                                                        <?php esc_html_e("$299.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                                    </div>
                                                                    <div class="offer-price">
                                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                    </div>
                                                                    <div class="getstarted-btn get-it-now">
                                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY10&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">
                                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                                        </a>
                                                                    </div>

                                                                </div>
                                                                <!-- <div class="dynamicprice_box d-none" boxperiod="yearly"
                                                                boxdomain="10+">
                                                                <div class="title card-title">Starter</div>
                                                                <p class="card-text contactus">
                                                                  
                                                                    <button type="button" class="btn btn-primary"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#staticBackdrop">
                                                                        Contact Us
                                                                    </button>
                                                                </p>

                                                            </div> -->
                                                            </div>
                                        </div>
                                        </th>
                                        <th scope="col" class="thd-data">
                                            <div class="feature-box">
                                                <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">

                                                    <div class="title card-title">
                                                        <?php esc_html_e("Free", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                    <p class="sub-title card-text">
                                                        <?php esc_html_e("1 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </p>
                                                    <div class="strike-price">
                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                            <?php esc_html_e("$00.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                    </div>
                                                    <div class="price">
                                                        <?php esc_html_e("$00.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                    </div>
                                                    <div class="offer-price" style="opacity:0">
                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                    <div class="getstarted-btn get-it-now">
                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url("https://wordpress.org/plugins/enhanced-e-commerce-for-woocommerce-store/"); ?>">
                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </a>
                                                    </div>


                                                </div>
                                                <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                    <div class="title card-title">
                                                        <?php esc_html_e("Free", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                    <p class="sub-title card-text">
                                                        <?php esc_html_e("5 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </p>
                                                    <div class="strike-price">
                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                            <?php esc_html_e("$00.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                    </div>
                                                    <div class="price">
                                                        <?php esc_html_e("$00.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                    </div>
                                                    <div class="offer-price" style="opacity:0">
                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                    <div class="getstarted-btn get-it-now">
                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url("https://wordpress.org/plugins/enhanced-e-commerce-for-woocommerce-store/"); ?>">
                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                    <div class="title card-title">
                                                        <?php esc_html_e("Free", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                    <p class="sub-title card-text">
                                                        <?php esc_html_e("10 Active Website", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </p>
                                                    <div class="strike-price">
                                                        <?php esc_html_e("Regular Price: ", "enhanced-e-commerce-for-woocommerce-store"); ?><span>
                                                            <?php esc_html_e("$00.00", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                    </div>
                                                    <div class="price">
                                                        <?php esc_html_e("$00.00/", "enhanced-e-commerce-for-woocommerce-store"); ?><span><?php esc_html_e("year", "enhanced-e-commerce-for-woocommerce-store"); ?></span>
                                                    </div>
                                                    <div class="offer-price" style="opacity:0">
                                                        <?php esc_html_e("Flat 50% Off Applied ", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                    <div class="getstarted-btn get-it-now">
                                                        <a class="label btn btn-secondary common-btn" target="_blank" href="<?php echo esc_url("https://wordpress.org/plugins/enhanced-e-commerce-for-woocommerce-store/"); ?>">
                                                            <?php esc_html_e("Get It Now", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10+">
                                                <div class="title card-title">Free</div>
                                                <p class="card-text contactus">
                                                   
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#staticBackdrop">
                                                        Contact Us
                                                    </button>
                                                </p>

                                            </div> -->
                                            </div>
                                        </th>




                                        </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Accessibility Features -->
                                            <!-- 0 -->
                                            <tr class="title-row" data-title="Accessibility Features">
                                                <td colspan="5" class="data">
                                                    <div class="feature-title">
                                                        <?php esc_html_e("Accessibility Features", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- 1 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="A dedicated customer success manager ensures that everything is set up accurately and helps you solve any issue that you may face.">
                                                            <?php esc_html_e("Dedicated Customer Success Manager", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>

                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 2 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e("Priority Support", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 3 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Analytics and Ads management becomes complicated some time. Our team of expert helps you in set up everything and performs audit so that you focus on the things that matter for your business.">
                                                            <?php esc_html_e("Free Setup and Audit", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 4 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Free consultation for campaign management and conversion rate optimization tips.">
                                                            <?php esc_html_e("Free Consultation for Campaign Management & CRO", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>


                                            <!-- GTM for Google Analytics and Pixels -->
                                            <!-- 0 -->
                                            <tr class="title-row">
                                                <td colspan="5" class="data">
                                                    <div class="feature-title">
                                                        <?php esc_html_e("GTM & Datalayer Automation", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- 1 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="By default your website will interact with Conversios GTM container.">
                                                            <?php esc_html_e("Conversios GTM container", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 2 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="One click automation of your own GTM container for faster page speed and flexibility. Create tags, triggers & variables based on your needs.">
                                                            <?php esc_html_e("Automate your GTM container", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 3 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Automates complete e-commerce datalayer for your Wordpress or WooCommerce stores. Single unified datalayer automation that can be used with all the analytics and Ads tracking.">
                                                            <?php esc_html_e("E-Commerce Datalayer", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- GA4, Ads Conversion Tracking & Audience Building -->
                                            <!-- 0 -->
                                            <tr class="title-row" data-title="Accessibility Features">
                                                <td colspan="5" class="data">
                                                    <div class="feature-title">
                                                        <?php esc_html_e("GA4, Ads Conversion Tracking & Audience Building", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- 1 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("GA4 E-commerce tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 2 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Tracking of all the web pages.">
                                                            <?php esc_html_e("page_view", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 3 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Tracking of all the web pages.">
                                                            <?php esc_html_e("purchase", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 4 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user views products on any product listing page. ie. Home page, product listing page, category page, similar products block etc.">
                                                            <?php esc_html_e("view_item_list", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 5 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user views any specific product's details page">
                                                            <?php esc_html_e("view_item", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 6 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user selects/clicks on any specific product.">
                                                            <?php esc_html_e("select_item", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 7 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user add product in the cart.">
                                                            <?php esc_html_e("add_to_cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 8 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user removes product from the cart.">
                                                            <?php esc_html_e("remove_from_cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 9 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user views cart page.">
                                                            <?php esc_html_e("view_cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 10 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user initiated checkout.">
                                                            <?php esc_html_e("begin_checkout", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 11 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user selects payment method in checkout.">
                                                            <?php esc_html_e("add_payment_info", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 12 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="GA4 tracking when user selects shipping method in checkout.">
                                                            <?php esc_html_e("add_shipping_info", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- 13 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Form submission tracking in GA4.">
                                                            <?php esc_html_e("form field tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 14 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Google Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 15 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Google Ads conversion tracking for purchase event.">
                                                            <?php esc_html_e("Conversion Tracking for purchase", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 16 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Google Ads conversion tracking for add to cart event.">
                                                            <?php esc_html_e("Conversion Tracking for add to cart", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 17 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Google Ads conversion tracking for begin checkout event.">
                                                            <?php esc_html_e("Conversion Tracking for begin checkout", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 18 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Google Ads enhanced conversion tracking for accurate and efficient conversion recording.">
                                                            <?php esc_html_e("Enhanced Conversion tracking", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 18 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Google Ads dynamic remarketing audience building based on user browsing behavior. 5 audience lists creation in Google Ads.">
                                                            <?php esc_html_e("Dynamic Audience builiding", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 19 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Facebook Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 20 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Facebook Ads conversion tracking for purchase event.">
                                                            <?php esc_html_e("Conversion tracking (purchase)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 21 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Facebook dynamic remarketing audience building based on user browsing behavior. ">
                                                            <?php esc_html_e("Audience building based on e-commerce events", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 22 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Enable this feature to improve the event quality score in business management account. ">
                                                            <?php esc_html_e("Advanced Matching", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 23 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Server-Side Tagging   for FB events in order to increase accurate and efficient events tracking.">
                                                            <?php esc_html_e("Facebook Conversions API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 24 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("TikTok Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 25 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="TikTok Ads conversion tracking for purchase event.">
                                                            <?php esc_html_e("Conversion tracking (purchase)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 26 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="TikTok dynamic remarketing audience building based on user browsing behavior. ">
                                                            <?php esc_html_e("Audience building based on e-commerce events", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 27 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Enable this feature to improve the event quality score in business management account.">
                                                            <?php esc_html_e("Advanced Matching", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 28 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Server-Side Tagging of e-commerce events for accurate and efficient events tracking for TikTok Ads.">
                                                            <?php esc_html_e("TikTok Events API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>


                                            <!-- 29 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Snapchat Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 30 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Snapchat Ads conversion tracking for purchase event.">
                                                            <?php esc_html_e("Conversion tracking (purchase)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 31 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Snapchat dynamic remarketing audience building based on user browsing behavior. ">
                                                            <?php esc_html_e("Audience building based on e-commerce events", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 32 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Server-Side Tagging of e-commerce events for accurate and efficient events tracking for Snapchat Ads.">
                                                            <?php esc_html_e("Snapchat Conversions API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 33 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Pinterest Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 34 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Pinterest Ads conversion tracking for purchase event.">
                                                            <?php esc_html_e("Conversion tracking (purchase)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 35 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Pinterest dynamic remarketing audience building based on user browsing behavior. ">
                                                            <?php esc_html_e("Audience building based on e-commerce events", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 36 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Microsoft Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 37 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Microsoft Ads conversion tracking for purchase event.">
                                                            <?php esc_html_e("Conversion tracking (purchase)", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 38 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Microsoft Ads dynamic remarketing audience building based on user browsing behavior.">
                                                            <?php esc_html_e("Audience building based on e-commerce events", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 39 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Microsoft Clarity Intergation", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 40 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Hotjar Integration", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 41 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Crazy Egg Integration", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 42 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <b><?php esc_html_e("Twitter Ads Tracking", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            <!-- Server-Side Tagging  -->
                                            <!-- 0 -->
                                            <tr class="title-row" data-title="hello">
                                                <td colspan="5" class="data">
                                                    <div class="feature-title">
                                                        <?php esc_html_e("Server-Side Tagging", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- 1 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="One click automation of server GTM container for e-commerce events and ad channels.">
                                                            <?php esc_html_e("Automation of Server GTMf", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 2 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="One click automation of web GTM container for e-commerce events and ad channels.">
                                                            <?php esc_html_e("Automation of Web GTM", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 3 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="One click provisioning of powerful google cloud server hosting for 100% uptime, scalability and security.">
                                                            <?php esc_html_e("Google cloud hosting for sGTM", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 4 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="No code automation for server e-commerce events datalayer.">
                                                            <?php esc_html_e("Server e-commerce datalayer automation", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 5 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Add your own sub domain to make tagging first party compliant.">
                                                            <?php esc_html_e("Customer loader", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 6 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Unlimited number of hits on the server.">
                                                            <?php esc_html_e("Unlimited hits", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 7 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Complete e-commerce tracking.">
                                                            <?php esc_html_e("Server-Side Tagging for GA4", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 8 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Complete conversion tracking and audience building in Google Ads.">
                                                            <?php esc_html_e("Server-Side Tagging for Google Ads", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 9 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Complete conversion tracking and audience building in Facebook.">
                                                            <?php esc_html_e("Server-Side Tagging for FB Ads and CAPI", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                            <!-- 10 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Complete conversion tracking and audience building in Snapchat.">
                                                            <?php esc_html_e("Server-Side Tagging for Snapchat Ads and CAPI", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>

                                            <!-- 11 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Complete conversion tracking and audience building in TikTok.">
                                                            <?php esc_html_e("Server-Side Tagging for TikTok Events API", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>


                                            <!-- Product Feed Manager  -->
                                            <!-- 0 -->
                                            <tr class="title-row" data-title="hello">
                                                <td colspan="5" class="data">
                                                    <div class="feature-title">
                                                        <?php esc_html_e("Product Feed Manager", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- 1 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Total number of WooCommerce product sync limit.">
                                                            <?php esc_html_e("Number of products", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <b><?php esc_html_e("Unlimited", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <b><?php esc_html_e("Unlimited", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <b><?php esc_html_e("Upto 500", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <b><?php esc_html_e("Upto 100", "enhanced-e-commerce-for-woocommerce-store"); ?></b>
                                                        </div>
                                                    </div>
                                                </td>




                                            </tr>

                                            <!-- 2 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e("Google Shopping Feed", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 3 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e("Facebook Catalog Feed", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 4 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc remove" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip">
                                                            <?php esc_html_e("TikTok Catalog Feed", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 5 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Auto schedule product updates in ad channels.">
                                                            <?php esc_html_e("Schedule auto product sync", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 6 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Sync your all yout WooCommerce products in one go.">
                                                            <?php esc_html_e("Super feed", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 7 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Filter your WooCommerce product to create feed.">
                                                            <?php esc_html_e("Advanced filters", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 8-->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Sync handpicked products from the product grid.">
                                                            <?php esc_html_e("Select specific WooCommerce products", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!--9-->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Sync product attributes from 50+ product plugins.">
                                                            <?php esc_html_e("Compatibility with 50+ product plugins", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- Reporting & Campaing Management  -->
                                            <!-- 0 -->
                                            <tr class="title-row" data-title="hello">
                                                <td colspan="5" class="data">
                                                    <div class="feature-title">
                                                        <?php esc_html_e("Reporting & Campaing Management", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <!-- 1 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Know about e-commerce funnel, product, source and order performance reports from wordpress admin. Enables data driven decision making to increase conversion %.">
                                                            <?php esc_html_e("E-Commerce reporting", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 2 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Enables you to measure the campaign performance in Google Ads.">
                                                            <?php esc_html_e("Ads reporting", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 3 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="ChatGPT powered insights on your analytics and campaigns data.">
                                                            <?php esc_html_e("AI powered Insights", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <?php esc_html_e("Unlimited", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <?php esc_html_e("Unlimited", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <?php esc_html_e("Upto 50", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <?php esc_html_e("Upto 10", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </div>
                                                    </div>
                                                </td>




                                            </tr>

                                            <!-- 4 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Schedule daily, weekly or monthly reports straight into your inbox.">
                                                            <?php esc_html_e("Schedule smart email reports", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>

                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span class="cross">&#10539;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 5 -->
                                            <tr>
                                                <th class="th-data" scope="row">
                                                    <div class="tooltip-box">
                                                        <button type="button" class="btn btn-secondary tooltipc" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-custom-class="custom-tooltip" data-bs-title="Create and manage Google Ads performance max campaigns and increase ROAS. Create and manage campaigns based on feeds.">
                                                            <?php esc_html_e("Product Ads Campaign Management", "enhanced-e-commerce-for-woocommerce-store"); ?>
                                                        </button>
                                                    </div>
                                                </th>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="feature-data">
                                                        <div class="items">
                                                            <span>&#10003;</span>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>

                                            <!-- 18 buttons -->
                                            <tr>
                                                <th class="th-data" scope="row" style="border: 0px;"></th>
                                                <td style="border: 0px;">
                                                    <div class="feature-data">
                                                        <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=plansst_1_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                        <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_EY5&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                        <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_EY10&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </td>
                                                <td style="border: 0px;">
                                                    <div class="feature-data">
                                                        <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_1_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                        <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_2_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                        <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=planD_3_y&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="border: 0px;">
                                                    <div class="feature-data">
                                                        <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY1&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                        <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY5&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                        <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url($this->convositeurl . "/checkout/?pid=wpAIO_SY10&utm_source=in_app&utm_medium=freevspro&utm_campaign=freeplugin"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="border: 0px;">
                                                    <div class="feature-data">
                                                        <div class="dynamicprice_box" boxperiod="yearly" boxdomain="1">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url("https://wordpress.org/plugins/enhanced-e-commerce-for-woocommerce-store/"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                        <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="5">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url("https://wordpress.org/plugins/enhanced-e-commerce-for-woocommerce-store/"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                        <div class="dynamicprice_box d-none" boxperiod="yearly" boxdomain="10">
                                                            <div class="getnow-btn">
                                                                <a class="btn btn-secondary getnow" index='1' target="_blank" href="<?php echo esc_url("https://wordpress.org/plugins/enhanced-e-commerce-for-woocommerce-store/"); ?>">GET
                                                                    NOW</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>


                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        </div>

        <script>
            function checkperiod_domain() {

                jQuery(".dynamicprice_box").addClass("d-none");

                var yearmonth_checkbox = "monthly";
                if (jQuery("#yearmonth_checkbox").is(":checked")) {
                    yearmonth_checkbox = "yearly"
                }
                var domain_num = jQuery('input[name=inlineRadioOptions]:checked').val()
                jQuery(".dynamicprice_box").each(function() {
                    var boxperiod = jQuery(this).attr("boxperiod");
                    var boxdomain = jQuery(this).attr("boxdomain");

                    if (boxperiod == yearmonth_checkbox && boxdomain == domain_num) {
                        jQuery(this).removeClass("d-none");
                    }
                });
            }

            jQuery(function() {
                jQuery("#yearmonth_checkbox").click(function() {
                    checkperiod_domain();
                });

                jQuery("input[name=inlineRadioOptions]").change(function() {
                    checkperiod_domain();
                });

                var distance = jQuery('#con_stick_this').offset().top;
                var convpwindow = jQuery(window);
                convpwindow.scroll(function() {
                    if (convpwindow.scrollTop() >= 2040 && convpwindow.scrollTop() <= 3650) {

                        jQuery("#con_stick_this").addClass("sticky-header");
                        jQuery("#sticky-header-tbody-id").addClass("sticky-header-tbody");
                    } else {
                        jQuery("#con_stick_this").removeClass("sticky-header");
                        jQuery("#sticky-header-tbody-id").removeClass("sticky-header-tbody");
                    }
                });
            });
        </script>

        <script>
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        </script>

<?php
    }
}
?>