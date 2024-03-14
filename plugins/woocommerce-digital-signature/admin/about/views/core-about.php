<?php
// Silence is golden
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


function esig_generate_about_page(array $about_options = array())
{
    require_once(dirname(__DIR__) . '/includes/esig-activations-states.php');

    if (empty($about_options)) {
        echo 'plugin about page load failed';
        return;
    } else {
        if (
            !array_key_exists('pluginName', $about_options) ||
            !array_key_exists('stepContent', $about_options)
        ) {
            echo 'plugin about page missing information, load failed';
            return;
        }
    }

    $esigStatus = esig_get_activation_state();

    if (!$esigStatus) {
        $esigStatus = "no_wpe"; //default to no_wpe if false, null or otherwise
    }

    if (!array_key_exists('plugin-slug', $about_options)) {
        $about_options['plugin-slug'] = sanitize_title_with_dashes($about_options['pluginName']);
    }

    if (!array_key_exists('main-cta', $about_options)) {
        $about_options['main-cta'] = 'https://www.approveme.com/' . $about_options['plugin-slug'] . '-signature-special/?utm_campaign=wprepo';
    }

    $single_name = substr($about_options['pluginName'], -1) == 's' ?  substr($about_options['pluginName'], 0, -1) :  $about_options['pluginName'];
    $single_name_without_space = preg_replace('/\s+/', '', $about_options['pluginName']);

?>
    

    <div id="about-body" class="<?php echo esc_attr($esigStatus . ' ' . $about_options['plugin-slug']); ?>">
        <div id="about-body-content" aria-label="Main content" tabindex="0">
            <div id="screen-meta" class="metabox-prefs">

                <div id="contextual-help-wrap" class="hidden no-sidebar" tabindex="-1" aria-label="Contextual Help Tab">
                    <div id="contextual-help-back"></div>
                    <div id="contextual-help-columns">
                        <div class="contextual-help-tabs">
                            <ul>

                            </ul>
                        </div>
                        <div class="contextual-help-tabs-wrap">
                        </div>
                    </div>
                </div>
            </div>
            <!--[if lt IE 9]><script>document.createElement('audio');document.createElement('video');</script><![endif]-->
            <div class="wrap approveme-about-wrap">
                <div id="header-wrapper">
                    <h1 class="alert-target"></h1>
                    <header class="about-header-container">
                        <img src="<?php echo esc_url(plugins_url('../assets/images/approveme-wp-logo.png', __FILE__)); ?>" alt="ApproveMe" class="approveme-logo" />
                        <div class="right-section">
                            <?php

                            //header buttons
                            if (!$esigStatus || $esigStatus == 'no_wpe') {
                                $headerOneText = 'Get Started';
                                $headerOneURL = 'https://www.approveme.com/' . $about_options['plugin-slug'] . "-special-pricing/?utm_campaign=wprepo";
                                $headerTwoText = 'See all features';
                                $headerTwoURL = $about_options['main-cta'] . '#features';
                            } else {
                                $headerOneText = 'My Account';
                                $headerOneURL = 'http://www.approveme.com/sign-in/';
                                $headerTwoText = 'Contact Support';
                                $headerTwoURL = $about_options['main-cta'] . '#letschat/';
                            }
                            ?>
                            <a href='<?php echo esc_url($headerOneURL); ?>' class='button-purple' target='blank' title='<?php echo esc_attr($headerOneText); ?>'><?php echo esc_attr($headerOneText); ?></a>
                            <a href='<?php echo esc_url($headerTwoURL); ?>' target='blank' class='button-border-block small' title='<?php echo esc_attr($headerTwoText); ?>'><?php echo esc_attr($headerTwoText); ?></a>

                        </div>
                    </header>
                </div>

                <div id="approveme-about-container">
                    <div class="hero-text">


                        <!-- Start Activation state -->
                        <?php
                        switch ($esigStatus) {

                            case 'wpe_inactive':
                                echo 'Excellent work! You have WP E-Signature installed.<br> <a href="' . esig_plugin_activation_link("e-signature/e-signature.php") . '">Activate it now so you can get started.</a>';
                                break;
                            case 'wpe_expired':
                                echo 'WP E-Signature requires a valid license for critical security updates.';
                                break;
                            case 'wpe_active_pro':
                                echo 'Excellent work! You have WP E-Signature installed and you\'re ready to automate your contracts.';
                                break;
                            case 'wpe_active_basic':
                            case 'no_wpe':
                            default:
                                echo 'Connect your ' . $single_name . ' with the #1 document signing tool built for WordPress websites.';
                                break;
                        }
                        ?>
                        <!-- End Activation state -->
                    </div>
                    <!-- Start Website-preview -->
                    <div class="flex-container">
                        <div class="left-col">
                            <img class="gf-preview-wedsite-img" src="<?php echo esc_url(plugins_url('../assets/images/gf-preview-webpage.png', __FILE__)); ?>">
                        </div>

                        <div class="right-col">
                            <!-- Start Activation state -->
                            <?php add_thickbox();
                            $extraCTA = '';

                            switch ($esigStatus) {

                                case 'wpe_inactive':
                                    $heroCTAText = 'Activate WP E-Signature';
                                    $heroCTAUrl = esig_plugin_activation_link("e-signature/e-signature.php");
                            ?>
                                    <div class="section__action">
                                        <div class="m-cta-whiskers-container">
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                            <a href="<?php echo esc_url($heroCTAUrl); ?>" class="button-purple"><?php echo esc_attr($heroCTAText); ?></a>
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                        </div>
                                        <div class="m-cta-whiskers-container">
                                            <?php echo $extraCTA; ?>
                                        </div>
                                        <section class="watch-demo">or<a href="<?php echo esc_url($about_options['setupVidURL']); ?>" class="watch-demo-link thickbox">Watch getting started video</a></section>
                                    </div>
                                <?php
                                    break;

                                case 'wpe_expired':
                                    $heroCTAText = 'Enter Your License Here';
                                    $heroCTAUrl = admin_url('admin.php?page=esign-licenses-general');
                                ?>
                                    <div class="section__action">
                                        <div class="m-cta-whiskers-container">
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                            <a href="<?php echo esc_url($heroCTAUrl); ?>" class="button-purple"><?php echo esc_attr($heroCTAText); ?></a>
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                        </div>
                                        <?php echo $extraCTA; ?>
                                        <section class="watch-demo">or<a href="<?php echo esc_url($about_options['setupVidURL']); ?>" class="watch-demo-link thickbox">Watch getting started video</a></section>
                                    </div>
                                <?php
                                    break;
                                case 'wpe_active_basic':
                                    $heroCTAText = 'Install Pro Add-Ons Now';
                                    $heroCTAUrl = admin_url("admin.php?page=esign-addons");
                                ?>
                                    <div class="section__action">
                                        <div class="m-cta-whiskers-container">
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                            <a href="<?php echo esc_url($heroCTAUrl); ?>" class="button-purple"><?php echo esc_attr($heroCTAText); ?></a>
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                        </div>
                                        <?php echo $extraCTA; ?>
                                        <section class="watch-demo">or<a href="<?php echo esc_url($about_options['setupVidURL']); ?>" class="watch-demo-link thickbox">Watch getting started video</a></section>
                                    </div>
                                <?php
                                    break;

                                case 'wpe_inactive_pro':
                                    $heroCTAText = 'Enable Pro Add-Ons Now';
                                    $heroCTAUrl = esig_plugin_activation_link("e-signature-business-add-ons/e-signature-business-add-ons.php");
                                ?>
                                    <div class="section__action">
                                        <div class="m-cta-whiskers-container">
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                            <a href="<?php echo esc_url($heroCTAUrl); ?>" class="button-purple"><?php echo esc_attr($heroCTAText); ?></a>
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                        </div>
                                        <?php echo $extraCTA; ?>
                                        <section class="watch-demo">or<a href="<?php echo esc_url($about_options['setupVidURL']); ?>" class="watch-demo-link thickbox">Watch getting started video</a></section>
                                    </div>
                                <?php
                                    break;
                                case 'wpe_active_pro':
                                    $heroCTAUrl = 'https://www.approveme.com/profile/';

                                    if ($esigStatus == 'wpe_active_basic') {
                                        $heroCTAText = 'Login to your account';
                                        $heroCTAUrl = admin_url("admin.php?page=esign-addons");
                                    } elseif ($esigStatus == 'wpe_active_pro') {
                                        $heroCTAText = 'Add a New Document';
                                        $heroCTAUrl = admin_url('admin.php?post_type=esign&page=esign-add-document&esig_type=sad');
                                        $extraCTA = '<a href="' . $about_options['main-cta'] . '" class="button-border" title="Learn more about this integration">Learn more about this integration</a>';
                                    } else {
                                        $heroCTAText = 'Install Pro Add-Ons to Get Started';
                                    }
                                ?>
                                    <div class="section__action">
                                        <div class="m-cta-whiskers-container">
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                            <a href="<?php echo esc_url($heroCTAUrl); ?>" class="button-purple"><?php echo esc_attr($heroCTAText); ?></a>
                                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                            </figure>
                                        </div>
                                        <div class="m-cta-whiskers-container">
                                            <?php echo $extraCTA; ?>
                                        </div>
                                            <section class="watch-demo">or<a href="<?php echo esc_attr($about_options['setupVidURL']); ?>" class="watch-demo-link thickbox">Watch getting started video</a></section>
                                        </div>
                                    <?php
                                    break;
                                case 'no_wpe':
                                default:
                                    ?>
                                        <p>
                                            You’re here because you want more out of eSignatures.
                                            Meet WP E-Signature, an easy-to-use, reliable WordPress plugin that gives you the eSignature & WooCommerce automation tools you need to protect your business (while saving you a ton of money and time).
                                        </p>

                                        <div class="section__action">
                                            <p class="small">Sign up today and save up to <a href="<?php echo esc_url($about_options['main-cta']); ?>" target="blank" title="save 50% off">50% off.</a></p>
                                            <div class="m-cta-whiskers-container">
                                                <figure class="wp-block-image m-cta-whiskers__whisker">
                                                    <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                                </figure>
                                                <a href="<?php echo esc_url($about_options['main-cta']); ?>" target="_blank" class="button-purple">Start eSigning in WordPress</a>
                                                <figure class="wp-block-image m-cta-whiskers__whisker">
                                                    <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded"><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                                                </figure>
                                            </div>
                                            <section class="watch-demo">or<a href="<?php echo esc_url($about_options['setupVidURL']); ?>" class="watch-demo-link thickbox">Watch a Demo</a></section>
                                        </div>
                                <?php
                                    break;
                            }

                                ?>



                                <?php /* echo $stateContent['heroHTMLContent']; */





                                ?>
                                <!-- End Activation state -->
                                    </div>
                        </div>
                        <!-- End Website-preview -->

                        <!-- Start Sub-text -->
                        <h4 class="sub-text">Create your powerful (and secure) eSignature workflow with <?php echo esc_attr($about_options['pluginName']); ?> & WP E-Signature</h4>
                        <!-- END Sub-text -->

                        <!-- Start About card -->
                        <section class="gf-card">
                            <img src="<?php echo esc_url(plugins_url('../assets/images/gf-girl-on-laptop-small.png', __FILE__)); ?>" class="gf-laptop-girl-img" alt="Laptop girl" />
                            <p class="text">Automatically collect digital signatures on contracts, after your visitors submit a form using ApproveMe's WP E-Signature. <a href="<?php echo esc_url($about_options['main-cta']); ?>#compliant" target="blank">UETA/ESIGN Compliant</a>, legally binding contracts with WordPress.</p>
                        </section>
                        <!-- End About card -->

                        <!-- Start Company supporters -->
                        <section class="company-support">
                            <h6>JOIN 1,000,000+ PROFESSIONALS USING APPROVEME FOR DOCUMENT SIGNING</h6>
                            <div class="trustedList">
                                <img src="<?php echo esc_url(plugins_url('../assets/images/companies/HBO_Latino_Logo.svg', __FILE__)); ?>" alt="HBO Latino" />
                                <img src="<?php echo esc_url(plugins_url('../assets/images/companies/Bulletproof.svg', __FILE__)); ?>" alt="Bulletproof" />
                                <img src="<?php echo esc_url(plugins_url('../assets/images/companies/NC_State_University.svg', __FILE__)); ?>" alt="NC State University" />
                                <img src="<?php echo esc_url(plugins_url('../assets/images/companies/UCLA.svg', __FILE__)); ?>" alt="UCLA" />
                                <img src="<?php echo esc_url(plugins_url('../assets/images/companies/Paypal.svg', __FILE__)); ?>" alt="Paypal" />
                                <img src="<?php echo esc_url(plugins_url('../assets/images/companies/NBA.svg', __FILE__)); ?>" alt="NBA" />
                                <img src="<?php echo esc_url(plugins_url('../assets/images/companies/PhoenixSuns.svg', __FILE__)); ?>" alt="Phoenix Suns" />
                                <img src="<?php echo esc_url(plugins_url('../assets/images/companies/Habbits-for-humanity.svg', __FILE__)); ?>" alt="Habbits for humanity" />
                            </div>
                        </section>
                        <!-- End Company supporters -->






                        <!-- START Youtube -->
                        <div id="esig-yt-video">
                            <p align="center">
                                <?php add_thickbox(); ?>
                                <a class="thickbox" href="<?php echo esc_url($about_options['setupVidURL']); ?>" class="thickbox">
                                    <img src="<?php echo esc_url($about_options['setupVidImage']); ?>" align="center" width="70%">
                                </a>
                            </p>
                        </div>
                        <!-- END Youtube -->


                        <!-- Start The Solution -->
                        <section class="gf-card">
                            <img src="<?php echo esc_url(plugins_url('../assets/images/gf-magic-hat.png', __FILE__)); ?>" class="gf-magic-hat-img" alt="magic hat" />
                            <div>
                                <h5 class="card-title">Your all-in-one eSignature solution</h5>
                                <p class="text">If you’re looking to reduce paperwork headaches… you should discover WP E-Signature, the #1 WordPress document signing software that agencies, freelancers and organizations use to take control of the eSignature experience™ </p>
                                <ul class="small-inline">
                                    <li>Build custom workflows.</li>
                                    <li>Securely own your data.</li>
                                    <li>UETA/ESIGN Compliant.</li>
                                    <ul>
                            </div>
                        </section>
                        <!-- End The Solution -->

                        <div class="spacer"></div>
                        <!-- Start whiskers Button -->
                        <div class="m-cta-whiskers-container">
                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded" /><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                            </figure>
                            <a href="<?php echo esc_url($about_options['main-cta']); ?>" target="_blank" class="button-purple small">Get My WP E-Sign License</a>
                            <figure class="wp-block-image m-cta-whiskers__whisker">
                                <img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="" class="lazyloaded" data-ll-status="loaded" /><noscript><img src="https://cdn.approveme.com/wp-content/themes/approveme/assets/img/cta-whisker.svg" alt="whiskers" /></noscript>
                            </figure>
                        </div>
                        <!-- END whiskers Button -->
                        <p align="center" class="small">Smash this button if you’re ready to trade administration drudgery for more free-time.</p>

                        <div class="spacer"></div>

                        <!-- Start Website-preview -->
                        <div class="flex-container">
                            <div class="left-col">
                                <img class="gf-count-on-us-img" src="<?php echo esc_url(plugins_url('../assets/images/gf-count-on-us.png', __FILE__)); ?>">
                            </div>
                            <div class="right-col center">
                                <h4 class="title">You can count on us</h4>
                                <p>
                                    We're here to help from day one, with 24/7 outstanding support. This is the beginning of a beautiful friendship.
                                </p>
                                <br />
                                <a href="<?php echo esc_url($about_options['main-cta']); ?>/&utm_medium=snipbar&utm_source=<?php echo esc_attr($single_name_without_space); ?>#letschat" target="_blank" class="button-border-block" align="center">Got a question?</a>

                            </div>
                        </div>
                        <!-- End Website-preview -->

                        <div class="changelog feature-list">

                            <h2 class="title">Follow these easy steps to connect WP E-Signature to your <?php echo esc_attr($about_options['pluginName']); ?></h2>

                            <?php echo $about_options['stepContent']; ?>

                            <!-- Start Talk to advisor -->
                            <section class="footer-container">
                                <img class="gf-advisor-img" src="<?php echo esc_url(plugins_url('../assets/images/gf-advisor.png', __FILE__)); ?>">

                                <div>
                                    <p class="medium-text">We're here to help! </br>
                                        Ask us anything.
                                    </p>
                                    <a href="<?php echo esc_url($about_options['main-cta']); ?>&utm_medium=talktoadvisor#letschat" class="button-pink" title="let's chat">
                                        <img src="<?php echo esc_url(plugins_url('../assets/images/chat-icon.svg', __FILE__)); ?>" alt="Chat Icon" class="button-right-icon" /> Talk to Advisor
                                    </a>
                                </div>
                            </section>
                            <!-- End Talk to advisor -->

                        </div>
                    </div>
                </div>
                <!-----------------approveme snip load here ------------------------>
               
                        <?php include('esign-iframe.php'); ?>

                <!---------------------- Approveme snip loads end here  ----------------------------->

            </div><!-- wpbody-content -->
        </div>
    <?php
}
