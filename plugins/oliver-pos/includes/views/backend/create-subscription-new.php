<?php
defined( 'ABSPATH' ) || exit;

include_once OLIVER_POS_ABSPATH . 'includes/class-pos-bridge-install.php';
include_once OLIVER_POS_ABSPATH . 'includes/views/backend/loader.php';
$udid = get_option('oliver_pos_subscription_udid'); // replace by client id
$login_auth = get_option('oliver_pos_subscription_autologin_token'); // replace by login token
$sync = "none";
$syncing = "none";
$hub_register = "none";
$app_url = ASP_TRY_ONBOARD;
$encode_url_auth = "";
if ($login_auth) {
	$encode_url_auth = urlencode($login_auth);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        dataLayer = [{
            'oliverpos-client-guid': `<?php echo get_option("oliver_pos_subscription_client_id"); ?>`,
            'oliverpos-client-url': `<?php echo home_url(); ?>`,
            'oliverpos-client-email': `<?php echo wp_get_current_user()->user_email; ?>`
        }];
    </script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TQBJ7K8');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Oliver Version <?php echo OLIVER_POS_PLUGIN_VERSION_NUMBER; ?> -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <title>Oliver - login</title>
</head>
<body>
<!-- id="oliver-fullHeight" -->
<section class="op-bridge-container min">
    <div class="op-logo-container">
        <img src="<?php echo plugins_url('public/resource/img/oliver-horizontal.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="">
    </div>
    <!-- Server send error START -->
    <div class="op-streached-card op-fail" style="display: none">
        <div class="op-streached-card-container">
            <div class="op-streached-card-content">
                <div class="op-streached-card-img-group">
                    <img src="<?php echo plugins_url('public/resource/img/hex.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
                <span class="op-server-error"></span>
            </div>
            <a href="#" id="op-reload"><?php esc_html_e( 'Try Again', 'oliver-pos' ); ?></a>
        </div>
    </div>
    <!-- server send error END -->

    <!-- without sync show -->
    <div class="op-content-container" id="op-sync-banner" style="display: <?php echo $sync;?>">
        <div class="op-cards-container">
            <h1 class="op-heading-primary">
                <span class="op-heading-primary--sub"><?php esc_html_e( 'Time to', 'oliver-pos' ); ?> <strong><?php esc_html_e( 'sync', 'oliver-pos' ); ?> </strong><?php esc_html_e( 'your WooCommerce store', 'oliver-pos' ); ?></span>
            </h1>
            <div class="op-bridge-card op-sync">
                <div class="op-card-text-group" id="op-sync-pane-url">
                    <h1 class="op-heading-primary"><span class="op-heading-primary--sub" id="op-replace-text">🚀 <?php esc_html_e( 'Ready to start syncing?', 'oliver-pos' ); ?></span></h1>
                    <p class="op-subheading"><?php esc_html_e( 'Oliver relies on a two-way sync with customers, orders, and stock to bring your WooCommerce store in-store.', 'oliver-pos' ); ?></p>
                    <a href="<?php echo ONBOARDING;?>" class="op_sunc_btn" id="op-sync-pannel-url" target="_blank">
                        <button class="op-btn-primary"><?php esc_html_e( 'Start syncing', 'oliver-pos' ); ?></button>
                    </a>
                </div>
                <div class="op-card-img-group">
                    <img src="<?php echo plugins_url('public/resource/img/op_sync.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!-- without sync show END-->

    <!-- with syncing show -->
    <div class="op-content-container" id="op-syncing-banner" style="display: <?php echo $syncing;?>">
        <div class="op-cards-container">
            <h1 class="op-heading-primary">
                <span class="op-heading-primary--sub"><?php esc_html_e( 'Time to', 'oliver-pos' ); ?> <strong><?php esc_html_e( 'sync', 'oliver-pos' ); ?> </strong><?php esc_html_e( 'your WooCommerce store', 'oliver-pos' ); ?></span>
            </h1>
            <div class="op-bridge-card op-sync">
                <div class="op-card-text-group" id="op-sync-pane-url">
                    <h1 class="op-heading-primary op_syncing">
                        <span class="op-heading-primary--sub" id="op-replace-text1"><?php esc_html_e( 'Just a moment while we sync', 'oliver-pos' ); ?></span>
                    </h1>
                </div>
                <div class="op-card-img-group">
                    <img src="<?php echo plugins_url('public/resource/img/op_sync.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!-- with syncing show END-->

    <!--show after login-->
    <h1 class="op-heading-primary op_welcomehome" style="display: <?php echo $hub_register;?>"><?php esc_html_e( 'Welcome', 'oliver-pos' ); ?>&nbsp; <span class="op-heading-primary--sub"><?php esc_html_e( 'home', 'oliver-pos' ); ?></span></h1>
    <div class="op-content-container" id="op-open-hub-reg" style="display: <?php echo $hub_register;?>">
        <div class="op-cards-container op-cards-container-row">
            <!-- Card 1 -->
            <div class="op-bridge-card op-card-col">
                <div class="op-card-text-group">
                    <h1 class="op-heading-primary"><?php esc_html_e( 'The Oliver HUB', 'oliver-pos' ); ?></h1>
                    <p class="op-subheading"><?php esc_html_e( 'Resync your WooCommerce store, access reports & configure your Oliver products with Oliver Hub.', 'oliver-pos' ); ?></p>
                </div>
                <div class="op-card-img-group">
                    <img src="<?php echo plugins_url('public/resource/img/image-hub.png', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
                <a id="op-connection-pane-url" class="op-btn-primary" href="<?php echo ONBOARDING;?>" target="_blank"><?php esc_html_e( 'Open Hub', 'oliver-pos' ); ?></a>
            </div>
            <!-- Card 2 -->
            <div class="op-bridge-card op-card-col">
                <div class="op-card-text-group">
                    <h1 class="op-heading-primary"><?php esc_html_e( 'The Oliver Register', 'oliver-pos' ); ?></h1>
                    <p class="op-subheading"><?php esc_html_e( 'Start selling with our uncluttered and intuitive WooCommerce POS register.', 'oliver-pos' ); ?></p>
                </div>
                <div class="op-card-img-group">
                    <img src="<?php echo plugins_url('public/resource/img/op_register.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
                <a href="#" target="_blank" id="op-open-register" class="op-btn-primary"><?php esc_html_e( 'Open Register', 'oliver-pos' ); ?></a>
            </div>
        </div>
    </div>

    <!-- System check start -->
    <div class="op-system-check op-login-user" style="display: none">
        <!-- Hardbloker Start-->
        <div class="op-system-check-header">
            <h1 class="op-heading-primary"><?php esc_html_e( 'System', 'oliver-pos' ); ?><span class="op-heading-primary--sub">&nbsp<?php esc_html_e( 'Check', 'oliver-pos' ); ?></span></h1>
            <button class="op-btn-secondary op-btn-refresh">
                <img src="<?php echo plugins_url('public/resource/img/refresh-icon.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" /><?php esc_html_e( 'Refresh', 'oliver-pos' ); ?>
            </button>
        </div>
        <div class="op-streached-card op-no-issue">
            <div class="op-streached-card-container">
                <div class="op-streached-card-content">
                    <div class="op-streached-card-img-group">
                        <img src="<?php echo plugins_url('public/resource/img/no_issue_check.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                    </div>
                    <span><strong><?php esc_html_e( 'Good news!', 'oliver-pos' ); ?></strong> <?php esc_html_e( 'No issues found.', 'oliver-pos' ); ?></span>
                </div>
            </div>
        </div>
        <div class="op-streached-card op-ssl" style="display: none">
            <div class="op-streached-card-container">
                <div class="op-streached-card-content">
                    <div class="op-streached-card-img-group">
                        <img src="<?php echo plugins_url('public/resource/img/hex.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                    </div>
                    <span><?php esc_html_e( 'Error: Outdated SSL Certification', 'oliver-pos' ); ?></span>
                </div>
                <a href="https://help.oliverpos.com/can-oliver-connect-to-an-unsecure-website" target="_blank"><?php esc_html_e( 'Learn More', 'oliver-pos' ); ?></a>
            </div>
        </div>
        <div class="op-streached-card op-Permalinks" style="display: none">
            <div class="op-streached-card-container">
                <div class="op-streached-card-content">
                    <div class="op-streached-card-img-group">
                        <img src="<?php echo plugins_url('public/resource/img/hex.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                    </div>
                    <span><?php esc_html_e( 'Error: Permalinks Not Set', 'oliver-pos' ); ?></span>
                </div>
                <a href="<?php echo home_url().'/wp-admin/options-permalink.php'?>" target="_blank"><?php esc_html_e( 'Fix Now', 'oliver-pos' ); ?></a>
            </div>
        </div>
        <div class="op-streached-card op-streached-card--2 op-localhost" style="display: none">
            <div class="op-streached-card-container">
                <div class="op-streached-card-start">
                    <div class="op-streached-card-content">
                        <div class="op-streached-card-img-group">
                            <img src="<?php echo plugins_url('public/resource/img/hex.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                        </div>
                        <span><?php esc_html_e( 'Connection Issue', 'oliver-pos' ); ?></span>
                    </div>
                    <h1 class="op-streached-card-heading"><?php esc_html_e( 'Looks like something is blocking us!', 'oliver-pos' ); ?></h1>
                    <p><?php esc_html_e( 'This can be because you are on a local host environment or your hosting provider is blocking us. Please contact host to allow
                        the ip below.', 'oliver-pos' ); ?> <strong><?php esc_html_e( 'Oliver IP: 168.648.48.48', 'oliver-pos' ); ?></strong></p>
                    <button class="op-btn-primary" id="op-free-migration"><?php esc_html_e( 'Get a free migration offer', 'oliver-pos' ); ?></button>
                </div>
                <a href="https://help.oliverpos.com/what-permalinks-settings-do-i-need-to-connect-to-oliver"><?php esc_html_e( 'Learn more', 'oliver-pos' ); ?></a>
            </div>
        </div>
        <div class="op-streached-card op-streached-card--2 op-woo-pos" style="display: none">
            <div class="op-streached-card-container">
                <div class="op-streached-card-start">
                    <div class="op-streached-card-content">
                        <div class="op-streached-card-img-group">
                            <img src="<?php echo plugins_url('public/resource/img/tri.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                        </div>
                        <span><?php esc_html_e( 'Potential Issue:  Plugin', 'oliver-pos' ); ?></span>
                    </div>
                    <h1 class="op-streached-card-heading"><?php esc_html_e( 'It looks like you have ', 'oliver-pos' ); ?> <strong class="op_plugin_name"><?php esc_html_e( 'Plugin name', 'oliver-pos' ); ?> </strong><?php esc_html_e( ' installed!', 'oliver-pos' ); ?></h1>
                    <p><?php esc_html_e( 'This can potentially cause issues. For the best experience it is advised to deactivate while using Oliver.', 'oliver-pos' ); ?></p>
                    <p><strong><?php esc_html_e( 'If you are just trying Oliver out you can get a free test environment', 'oliver-pos' ); ?> <a href="https://try.oliverpos.com" target="_blank"><?php esc_html_e( 'here', 'oliver-pos' ); ?></a></strong></p>
                </div>
            </div>
        </div>
        <!-- Softblock Start-->
        <div class="op-plugin-warning"></div>
        <!-- Softblock End-->
        <div class="op-streached-card op-server-speed-check" style="display:none">
            <div class="op-streached-card-container">
                <div class="op-streached-card-start">
                    <div class="op-streached-card-content">
                        <div class="op-streached-card-img-group">
                            <img src="<?php echo plugins_url('public/resource/img/tri.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                        </div>
                        <span><?php esc_html_e( 'Optimization: Slow server response detected!', 'oliver-pos' ); ?></span>
                    </div>
                    <h1 class="op-streached-card-heading"><?php esc_html_e( 'Your server took more than', 'oliver-pos' ); ?> <strong><?php esc_html_e( '500ms', 'oliver-pos' ); ?> </strong><?php esc_html_e( 'to respond!', 'oliver-pos' ); ?></h1>
                    <p><?php esc_html_e( 'Get a professional migration from our server partner Nexcess', 'oliver-pos' ); ?></p>
                    <p><strong><?php esc_html_e( '14 day money-back guarantee', 'oliver-pos' ); ?></strong></p>
                </div>
                <div class="op-streached-card-end">
                    <div>
                        <img src="<?php echo plugins_url('public/resource/img/nexcess-logo-md1.png', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                    </div>
                    <a class="op-btn-primary" href="https://liquidweb.grsm.io/4gr2f7333x1r" target="_blank"><?php esc_html_e( 'Get a free migration offer', 'oliver-pos' ); ?></a>
                </div>
            </div>
        </div>

        <div class="op-streached-card op-server-speed-check" style="display:none">
            <div class="op-streached-card-container">
                <div class="op-streached-card-start">
                    <div class="op-streached-card-content">
                        <div class="op-streached-card-img-group">
                            <img src="<?php echo plugins_url('public/resource/img/tri.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                        </div>
                        <span><?php esc_html_e( 'Optimization: Very slow site speed detected!', 'oliver-pos' ); ?></span>
                    </div>
                    <h1 class="op-streached-card-heading"><?php esc_html_e( 'Did you know that', 'oliver-pos' ); ?> <strong><?php esc_html_e( 'one', 'oliver-pos' ); ?></strong> <?php esc_html_e( 'second delay cost you on average', 'oliver-pos' ); ?> <strong><?php esc_html_e( '$70 pr. day?', 'oliver-pos' ); ?></strong></h1>
                    <p><?php esc_html_e( 'Optimize your site in minutes with our partner Nitro pack.', 'oliver-pos' ); ?></p>
                    <p><strong><?php esc_html_e( '14 day money-back guarantee', 'oliver-pos' ); ?></strong></p>
                </div>
                <div class="op-streached-card-end">
                    <div>
                        <img src="<?php echo plugins_url('public/resource/img/logo-142x801.png', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                    </div>
                    <a class="op-btn-primary" href="https://nitropack.io/#5LSF33" target="_blank"><?php esc_html_e( 'Optimize site', 'oliver-pos' ); ?></a>
                </div>
            </div>
        </div>
    </div>
    <!-- System check End -->
    <!-- Need help -->
    <div class="op-contact-container op-login-user" style="display: none">
        <h1 class="op-heading-primary"><?php esc_html_e( 'Need', 'oliver-pos' ); ?> <span class="op-heading-primary--sub"><?php esc_html_e( 'help?', 'oliver-pos' ); ?></span></h1>
        <div class="op-contact-content">
            <div class="op-contact-img-container">
                <img src="<?php echo plugins_url('public/resource/img/CustomerSupportPerson_1.png', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
            </div>
            <div class="op-contact-content-text-group">
                <h1 class="op-heading-primary"><span class="op-heading-primary--sub"><?php esc_html_e( 'Contact', 'oliver-pos' ); ?> </span><?php esc_html_e( 'Us', 'oliver-pos' ); ?></h1>
                <div class="op-inline-item">
                    <img src="<?php echo plugins_url('public/resource/img/rounded-checkmark.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                    <span><?php esc_html_e( 'WooCommerce & POS Experts', 'oliver-pos' ); ?></span>
                </div>
                <div class="op-inline-item">
                    <img src="<?php echo plugins_url('public/resource/img/rounded-checkmark.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                    <span><?php esc_html_e( 'Works with 99% of WC stores', 'oliver-pos' ); ?></span>
                </div>
                <div class="op-button op-btn-primary">
                    <a href="https://calendly.com/oliverteam/onboarding?month=<?php echo date('Y-m');?>" target="_blank" id="op-book-a-free"><?php esc_html_e( 'Visit our support center', 'oliver-pos' ); ?></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Need help END-->
    <!--show after login END-->
    <!--show before login register form-->
    <div class="op-content-container" id="op-register-banner" style="display: none">
        <div class="op-cards-container">
            <!-- Create an Account -->
            <div class="op-bridge-card">
                <div class="op-card-text-group" id="op-register-pane-url">
                    <h1 class="op-heading-primary">👋 <?php esc_html_e( "Let's get you ready to sell.", 'oliver-pos' ); ?></h1>
                    <p class="op-subheading"><?php esc_html_e( "First thing's first. You'll need to create an account to get started with Oliver.", 'oliver-pos' ); ?></p>
                    <a href="<?php echo ONBOARDING;?>" target="_blank">
                        <button class="op-btn-primary"><?php esc_html_e( 'Create an Account', 'oliver-pos' ); ?></button>
                    </a>
                </div>
                <div class="op-card-img-group">
                    <img src="<?php echo plugins_url('public/resource/img/op_register.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
            </div>
            <!-- Subscription form -->
            <div class="op-bridge-card">
                <div class="op-card-text-group" id="op-got-a-subscription">
                    <h1 class="op-heading-primary"><?php esc_html_e( 'Already got a subscription?', 'oliver-pos' ); ?></h1>
                    <div class="op-card-form">
                        <label for="op-subscription-input"><?php esc_html_e( 'License Key', 'oliver-pos' ); ?></label>
                        <input type="text" placeholder="<?php esc_attr_e( 'Enter your license key here', 'oliver-pos' ); ?>" id="op-subscription-input" />
                        <button type="submit" class="op-btn-primary" id="op-connect-site"><?php esc_html_e( 'Connect Site', 'oliver-pos' ); ?></button>
                        <div class="op_error-message"></div>
                    </div>
                    <div class="op-small-text-container">
                        <p><strong><?php esc_html_e( 'Can’t find your license key?', 'oliver-pos' ); ?></strong></p>
                        <p><?php esc_html_e( "You will receive your license key once you sign up for Oliver, and can find it anytime on your ", 'oliver-pos' ); ?><a href="<?php echo ACCOUNT;?>" target="_blank"><?php esc_html_e( 'account page', 'oliver-pos' ); ?></a>.</p>
                        <p><strong><?php esc_html_e( 'If you signed up with an Oliver Expert, contact them to get started.', 'oliver-pos' ); ?></strong></p>
                    </div>
                </div>
                <!-- Already In use -->
                <div class="op-card-text-group op-already-use none">
                    <h1 class="op-heading-primary"><?php esc_html_e( 'Already got a subscription?', 'oliver-pos' ); ?></h1>
                    <div class="op-small-text-container">
                        <p><strong><?php esc_html_e( 'License key is already in use.', 'oliver-pos' ); ?></strong></p>
                        <p><?php esc_html_e( 'Please make sure that you have used the right license key. You can view your license key and connected domain anytime via your ', 'oliver-pos' ); ?> <a href="<?php echo ACCOUNT;?>" target="_blank"><?php esc_html_e( 'account page', 'oliver-pos' ); ?></a>.</p>
                        <p><?php esc_html_e( 'If you are still having issues please contact us by clicking', 'oliver-pos' ); ?> <a href="https://help.oliverpos.com" target="_blank"><?php esc_html_e( 'here', 'oliver-pos' ); ?></a></p>
                        <p><strong><?php esc_html_e( 'If a partner has signed you up please contact them in order to get your key.', 'oliver-pos' ); ?></strong></p>
                    </div>
                    <a href="javascript:window.location.reload(true)" class="op-btn-primary"><?php esc_html_e( 'Try Again', 'oliver-pos' ); ?></a>
                </div>
                <!-- Image -->
                <div class="op-card-img-group">
                    <img src="<?php echo plugins_url('public/resource/img/op_sync.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!--show when subscription expired-->
    <div class="op-content-container" id="op-subscription-expired" style="display: none">
        <div class="op-cards-container">
            <div class="op-bridge-card">
                <div class="op-card-text-group">
                    <h1 class="op-heading-primary">👋 <?php esc_html_e( "Subscription expired.", 'oliver-pos' ); ?></h1>
                    <a href="<?php echo ACCOUNT;?>" target="_blank">
                        <button class="op-btn-primary"><?php esc_html_e( 'Click here to renew', 'oliver-pos' ); ?></button>
                    </a>
                </div>
                <div class="op-card-img-group">
                    <img src="<?php echo plugins_url('public/resource/img/op_register.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!-- END subscription expired -->
    <!-- Support -->
    <div class="op-contact-container" id="op-contact-support" style="display: none">
        <h1 class="op-heading-primary"><?php esc_html_e( 'Support you can count on', 'oliver-pos' ); ?></h1>
        <p class="op-contact-sub-heading"><?php esc_html_e( "With Oliver, you're in good hands", 'oliver-pos' ); ?></p>
        <div class="op-contact-content-container">
            <div class="op-contact-img-container">
                <img src="<?php echo plugins_url('public/resource/img/CustomerSupportPerson_1.png', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
            </div>
            <!-- Card -->
            <div class="op-contact-card">
                <div class="op-contact-card-img">
                    <img src="<?php echo plugins_url('public/resource/img/chat-icon.png', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
                <h1 class="op-contact-card-heading"><?php esc_html_e( 'Talk to an Oliver support agent', 'oliver-pos' ); ?></h1>
                <p class="op-contact-card-para"><?php esc_html_e( 'The Oliver support team is available 24/7 and offers free onboarding and demo calls.', 'oliver-pos' ); ?></p>
                <a href="https://www.oliverpos.com/find-an-oliver-expert" class="op-contact-btn" target="_blank"><span><?php esc_html_e( 'Contact support', 'oliver-pos' ); ?></span><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z" fill="rgba(39,146,232,1)"></path></svg></span></a>
            </div>
            <!-- Card -->
            <div class="op-contact-card">
                <div class="op-contact-card-img">
                    <img src="<?php echo plugins_url('public/resource/img/graduation-icon.png', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
                <h1 class="op-contact-card-heading"><?php esc_html_e( 'Check out our Knowledge Base', 'oliver-pos' ); ?></h1>
                <p class="op-contact-card-para"><?php esc_html_e( "Don't feel like waiting? Read our knowledge base to see if your question has been answered already.", 'oliver-pos' ); ?></p>
                <a href="https://help.oliverpos.com" class="op-contact-btn" target="_blank"><span> <?php esc_html_e( 'Knowledge Base', 'oliver-pos' ); ?> </span><span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z" fill="rgba(39,146,232,1)"></path></svg></span></a>
            </div>
        </div>
    </div>
    <!-- Support END-->
    <!--show before login END-->
    <!-- Disconnect -->
    <div class="op-streached-card op-login-user op-disconnect" style=" display: none;">
        <div class="op-streached-card-container">
            <div class="op-streached-card-content">
                <span><strong><?php esc_html_e( 'Disconnect Site:', 'oliver-pos' ); ?> </strong> <?php esc_html_e( 'This will disconnect your Oliver subscription from this site.', 'oliver-pos' ); ?></span>
            </div>
            <button type="button" class="op-delete-connection" id="op_open_model"><?php esc_html_e( 'Disconnect Site', 'oliver-pos' ); ?></button>
        </div>
    </div>
    <!-- Disconnect end-->
    <!-- ========= this is the first form used for rendering connected ========== -->
    <div class="oliver_info" style="margin-top: 5px; display: none;" id="error-panel"></div>
    <!-- model start confirm remove subscription-->
    <div class="op-modal op-close" id="op_confirm_removed_subscription">
        <div class="op-modal_overlay"></div>
        <div class="op-modal_container">
            <div class="op-modal_header">
                <h3><?php esc_html_e( 'Disconnect Site', 'oliver-pos' ); ?></h3>
                <div class="op-modal_close ">&times;</div>
            </div>
            <div class="op-modal_content">
                <div class="op-medel-text">
                    <p><?php esc_html_e( 'Are you sure that you want to disconnect this site?', 'oliver-pos' ); ?></p>
                    <p><?php esc_html_e( 'This will unlink your Oliver POS subscription from this WooCommerce database.', 'oliver-pos' ); ?></p>
                    <p><?php esc_html_e(' You will need to re-enter your license key to reconnect Oliver.', 'oliver-pos' ); ?></p>
                </div>
                <div class="op-modal_buttons-container">
                    <button class="op-btn-primary op-cancel-btn"><?php esc_html_e( 'Cancel', 'oliver-pos' ); ?></button>
                    <button class="op-delete-connection" id="op-confirm-delete"><?php esc_html_e( 'Delete', 'oliver-pos' ); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- model start remove subscription-->
    <div class="op-modal op-close" id="op-removed_subscription">
        <div class="op-modal_overlay"></div>
        <div class="op-modal_container">
            <div class="op-modal_header">
                <h3 class="op_show_deleted_msg"><?php esc_html_e( 'Site Disconnected', 'oliver-pos' ); ?></h3>
                <div class="op-modal_close">&times;</div>
            </div>
            <div class="op-modal_content">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" viewBox="0 0 24 24">
                        <path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11.0026 16L6.75999 11.7574L8.17421 10.3431L11.0026 13.1716L16.6595 7.51472L18.0737 8.92893L11.0026 16Z" fill="rgba(100,205,138,1)"></path>
                    </svg>
                    <p class="op_deleted_msg_show"><?php esc_html_e( 'Your license key has been successfully removed from this site.', 'oliver-pos' ); ?></p>
                </div>
                <div class="op-modal_buttons-container">
                    <button class="op-btn-primary op-modal_close"><?php esc_html_e( 'Go Back', 'oliver-pos' ); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- model END-->
    <!-- immunity system model start-->
    <div class="op-modal op-close op-modal_large" id="op_immunity_model">
        <div class="op-modal_overlay"></div>
        <div class="op-modal_container">
            <div class="op-modal_content">
                <div class="op-modal_logo">
                    <img src="<?php echo plugins_url('public/resource/img/oliver_break_icon.svg', dirname(dirname(dirname(__FILE__)))); ?>" alt="" />
                </div>
                <h2><?php esc_html_e( 'Oh no!', 'oliver-pos' ); ?></h2>
                <div class="op-text_container">
                    <p class="op-text_blue"><?php esc_html_e( 'Olivers immunity system have found an issue', 'oliver-pos' ); ?></p>
                    <p><?php esc_html_e( 'Please fix the system check issues before continuing', 'oliver-pos' ); ?></p>
                </div>
                <div class="op-modal_buttons-container">
                    <button class="op-btn-primary op-modal_close"><?php esc_html_e( 'Go Back', 'oliver-pos' ); ?></button>
                </div>
                <span>Still having issues?<a href="https://www.oliverpos.com/find-an-oliver-expert" class="op-modal_link" target="_blank"> <?php esc_html_e( 'Contact us.', 'oliver-pos' ); ?></a></span>
            </div>
        </div>
    </div>
    <!-- immunity system model END-->
    <!-- sync model start-->
    <div class="op-modal op-close op-modal_sync" id="op_modal_sync">
        <div class="op-modal_overlay"></div>
        <div class="op-modal_container">
            <div class="op-modal_header">
                <h3 class="op_show_deleted_msg"><?php esc_html_e( 'System Check', 'oliver-pos' ); ?></h3>
                <div class="op-modal_close">&times;</div>
            </div>
            <div class="op-modal_content">
                <div>
                    <p class="op_deleted_msg_show"><?php esc_html_e( 'Please fix all the red errors before proceeding with synchronization', 'oliver-pos' ); ?></p>
                </div>
            </div>
        </div>
    </div>
    <!-- sync model END-->
</section>
<script type="text/javascript">
    var JQueryAjaxUrl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>';
    var UDID = "<?php echo $udid; ?>";
    var op_count=1;
    jQuery(document).ready(function() {
        //invoke init connection
        oliver_pos_init_connection();

        //On Click refresh button
        jQuery(".op-btn-refresh").click(function(){
            oliver_pos_systemCheck();
            oliver_pos_getWebsiteSpeed();
        });
        //On click back button reload page
        jQuery(document).on('click', '#op-reload', function() {
            window.location.reload();
        });
        jQuery(document).on('click', '.op_sunc_btn.op_disable', function() {
            jQuery('#op_modal_sync').css({"display": "block"});
        });
        jQuery(document).on('click', '#op-confirm-delete', function() {
            jQuery('#op_confirm_removed_subscription').css({"display": "none"});
            oliver_pos_startLoader();
            oliver_pos_remove_subscription();
        });
        jQuery(document).on('click', '#op-connect-site', function() {
            var subscription_key = jQuery("#op-subscription-input").val();
            if( subscription_key.length === 0 ) {
                jQuery(this).siblings("#op-subscription-input").addClass('op-warning');
                jQuery(".op_error-message p").remove();
                jQuery(".op_error-message").append('<p>License key is required</p>');
                jQuery(".op_error-message").css({"display": "block"});
                jQuery("#op-subscription-input").focus();
            }
            else{
                oliver_pos_startLoader();
                oliver_pos_connect_site(subscription_key);
            }
        });
        jQuery('#op-subscription-input').keyup(function(){
            jQuery(this).removeClass('op-warning');
            jQuery(".op_error-message p").remove('');
            jQuery(".op_error-message").css({"display": "none"});
        });

        jQuery('.op-modal_close, .op-cancel-btn').click(function(){
            jQuery('.op-modal').css({"display": "none"});
        });
        jQuery('#op_open_model').click(function(){
            jQuery('#op_confirm_removed_subscription').css({"display": "block"});
        });
        jQuery('#op-free-migration').click(function(){
            jQuery('#op_immunity_model').css({"display": "block"});
        });
        jQuery('#op-sync-pannel-url').click(function(){
            jQuery('#op-syncing-banner').css({"display": "flex"});
            jQuery('#op-sync-banner').css({"display": "none"});
            oliver_pos_get_sync_status();
        }); 
    });
    //CHeck sync status
    function oliver_pos_get_sync_status(){
        //oliver_pos_startLoader();
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_syncing_status',
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function( result ){
                if (result) {
                    let response = JSON.parse(result);
                    if (response.Synced =='not_started') {
                        oliver_pos_show_sync_view();
                        if(op_count<=10){
                            oliver_pos_set_timeout(response.interval);
                            op_count=1+op_count;
                        }
                    }
                    if (response.Synced=='synced') {
                        oliver_pos_show_synced_view();
                        oliver_pos_getRegisterUrl();
                    }
                    if(response.Synced=='syncing'){
                        oliver_pos_show_syncing_progress_view();
                        oliver_pos_set_timeout(response.interval);
                    }
                    oliver_pos_stopLoader();
                }
            }
        );
    }
    /**
     * For establish connection with oliver pos
     * @since 2.2.5.0
     * @return boolena true : false (errors).
     */
    function oliver_pos_init_connection() {
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_init_connection',
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function(result){
                let msg = "Oliver POS did not respond in a timely manner";
                if (result) {
                    let response = JSON.parse(result);
                    if (response.is_success) {
                        let cId = response.content.client_id;
                        let tId = response.content.server_token;
                        let autoToken = null;
                        if( response.content.Status=='subscription_not_found' ){
                            //show subscription form window
                            //don't run any background process
                            oliver_pos_show_registration_view();
                            oliver_pos_stopLoader();
                            return; //this means compiler should not read next line
                        }
                        if( response.content.Status=='registration_not_done' ){
                            autoToken = response.content.auth_token;
                            oliver_pos_displayConnectionPanel(cId, tId, autoToken);
                            oliver_pos_registration_in_progress();
                            oliver_pos_stopLoader();
                            return; //this means compiler should not read next line
                        }
                        if(response.content.Status=='success'){
                            autoToken = response.content.auth_token;
                            oliver_pos_displayConnectionPanel(cId, tId, autoToken);
                            oliver_pos_registration_success();
                            oliver_pos_get_sync_status();
                            oliver_pos_getWebsiteSpeed();
                            oliver_pos_systemCheck();
                            return; //this means compiler should not read next line
                        }

                    } else {
                        // IF is success false then show error message
                        if (response.message) {
                            msg = response.message;
                            if (response.subscription_expired == true){
                                oliver_pos_subscription_expired(msg);
                                oliver_pos_stopLoader();
                            }
                            else {
                                jQuery.each(response.exceptions, function (key, val) {
                                    msg = val;
                                });
                                oliver_pos_displayError(msg);
                            }
                        }
                        else{
                            oliver_pos_displayError(msg);
                        }
                    }
                } else {
                    //Show error msg if ajax result not found
                    oliver_pos_displayError(msg);
                }
            }
        ).fail(function() {
            //Show if API not working
            msg='Error: Server is not responding!';
            oliver_pos_displayError(msg);
        })
        .always(function() {
            //oliver_pos_stopLoader();
        });
    }
    function oliver_pos_displayConnectionPanel(cId, tId, autoToken){
        let appURL = '<?php echo $app_url ?>';
        let encodeUrlAuth = '<?php echo $encode_url_auth ?>';
        let serverURL = `${appURL}?_client=${cId}`;
        if (autoToken) {
            serverURL += `&_token=${autoToken}`;
        }
        if ( ! serverURL.includes("&_token=")) {
            if (encodeUrlAuth != "") {
                serverURL += `&_token=${encodeUrlAuth}`;
            }
        }
        jQuery("a#op-connection-pane-url").attr("href", decodeURI(serverURL));
        jQuery("a#op-sync-pannel-url").attr("href", decodeURI(serverURL));
        <?php
		$op_speed_check = get_option('op_speed_check');
		if($op_speed_check==true){
		?>
        jQuery('.op-system-check.op-login-user').css("display", "flex");
        jQuery('.op-server-speed-check').css({"display": "flex"});
        jQuery('.op-no-issue').css({"display": "none"});

		<?php } ?>
    }
    //check why this code added
    jQuery(".confirm-deactivate").click( function() {
        jQuery(this).find('a').text("Disconnecting....");
        jQuery(this).css({
            'pointer-events' : "none"
        });
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_disconnect_subscription',
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function( response ){
                let res = JSON.parse( response );
                if (res.IsSuccess) {
                    oliver_pos_delete_subscription();
                }
            }
        );
    });
    function oliver_pos_delete_subscription() {
        // body...
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_delete_subscription',
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function( response ){
                res = JSON.parse( response );
                if ( res.status ) {
                    location.reload(true);
                }
            }
        );
    }
    // remove subscription
    function oliver_pos_remove_subscription() {
        //oliver_pos_initLoader()
        oliver_pos_startLoader();
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_remove_subscription',
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function( response ){
                oliver_pos_stopLoader();
                jQuery('#op-removed_subscription').css({"display": "block"});
                res = JSON.parse( response );
                if (res.exceptions != null){
                    console.log('exception');
                    jQuery.each(res.exceptions, function (key, val) {
                        jQuery(".op_show_deleted_msg").text('');
                        jQuery("p.op_deleted_msg_show").text('');
                        jQuery("p.op_deleted_msg_show").append(val);
                    });
                }
                else if (res.Message != null){
                    console.log(res.Message);
                    jQuery("p.op_deleted_msg_show").text(res.Message);
                }
                location.reload(true);
            }
        );
    }
    //connect site with subscription key
    function oliver_pos_connect_site(subscription_key){
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_connect_site',
                'subscription_key'   : subscription_key,
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function( response ){
                oliver_pos_connection_responce(response);
            }
        );
    }
    function oliver_pos_connection_responce(response) {
        oliver_pos_stopLoader();
        res = JSON.parse( response );
        jQuery(".op_error-message p").remove();
        if(res.opConnect=='noResponse'){
            jQuery(".op_error-message").append('<p>Something went wrong try again!</p>');
        }
        if (res.exceptions != null){
            // if subscription key is already in use
            jQuery.each(res.exceptions, function (key, val) {
                var str2 = "already registered";
                if(val.indexOf(str2) != -1){
                    jQuery("#op-got-a-subscription").css({"display": "none"});
                    jQuery(".op-already-use").css({"display": "flex"});
                }
                jQuery(".op_error-message").append('<p>'+val+'</p>');
                jQuery(".op_error-message").css({"display": "block"});
            });
        }
        else if (res.Message != null){
            //if connect successfully
            jQuery(".op_error-message").css({"background-color": "#64cd8a33"});
            jQuery(".op_error-message").append('<p>'+res.Message+'</p>');
            jQuery(".op_error-message").css({"display": "block"});
            location.reload(true);
        }
    }
    /**
     * Subscription Expired
     * @since 2.4.1.7
     * @ show Subscription Expired message.
     */
    function oliver_pos_subscription_expired(eMsg) {
        jQuery('.op-login-user').css({"display": "none"});
        jQuery('#op-register-banner').css({"display": "none"});
        jQuery('#op-contact-support').css({"display": "grid"});
        jQuery('#op-subscription-expired').css({"display": "flex"});
        jQuery('.op-login-user.op-disconnect').css({"display": "flex"});
        //jQuery('.op-change-subscription').css({"display": "flex"});
        oliver_pos_stopLoader();
    }
    /**
     * display error
     * @since 2.2.5.0
     * @return html|message.
     */
    function oliver_pos_displayError(eMsg) {
        jQuery('.op-server-error').text(eMsg);
        jQuery('.op-fail').css({"display": "block"});
        jQuery('.op-login-user').css({"display": "none"});
        jQuery('#op-register-banner').css({"display": "none"});
        oliver_pos_stopLoader();
    }
    /**
     * init loader
     * @since 2.2.5.0
     * @return html|loader.
     */
    function oliver_pos_initLoader() {
        jQuery("body").append(`<?php oliver_pos_loader(); ?>`);
    }
    /**
     * start loader
     * @since 2.2.5.0
     * @return html|loader.
     */
    function oliver_pos_startLoader() {
        jQuery('#image_loading').css({"display": "flex"});
    }
    /**
     * stop loader
     * @since 2.2.5.0
     * @return html|loader.
     */
    function oliver_pos_stopLoader() {
        setTimeout(function () {
            jQuery('.loader-fixed').css({"display": "none"});
        }, 2000);

    }

    function oliver_pos_getWebsiteSpeed() {
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_getWebsiteSpeed',
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function(plans_result){
                let speed_response = JSON.parse(plans_result);
                console.log(speed_response);
                if(speed_response.opSpeed=='show'){
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                    jQuery('.op-server-speed-check').css({"display": "flex"});
                    jQuery('.op-no-issue').css({"display": "none"});
                }
                else{
                    jQuery('.op-server-speed-check').css({"display": "none"});
                }
            }
        );
    }
    function oliver_pos_getRegisterUrl() {
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_register_url',
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function(reg_result){
                let reg_response = JSON.parse(reg_result);
                if(reg_response.register_url=='not_register'){
                    jQuery('#op-open-register').bind('click', false);
                    jQuery('#op-open-register').addClass("op_disable");
                }
                else{
                    jQuery("a#op-open-register").attr("href", reg_response.register_url);
                    jQuery("a#op-connection-pane-url").attr("href", reg_response.hub_url);
                }
            }
        );
    }
    
    function oliver_pos_systemCheck() {
        console.log('system check');
        oliver_pos_startLoader();
        jQuery('.op-system-check.op-login-user').css("display", "none");
        jQuery.post(JQueryAjaxUrl ,{
                'action'    : 'oliver_pos_system_check',
                'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
            },
            function(op_result){

                let op_response = JSON.parse(op_result);
                if(op_response.issue_count==0){
                    jQuery('.op-no-issue').css("display", "flex");
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                }
                else{
                    jQuery('.op-no-issue').css("display", "none");
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                }
                if(op_response.plugin_issue=='yes'){
                    jQuery('.op-woo-pos').css("display", "block");
                    jQuery('.op_plugin_name').text(op_response.plugin_name);
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                }
                else{
                    jQuery('.op-woo-pos').css("display", "none");
                }
                if(op_response.permalink=='notset'){
                    jQuery('.op-Permalinks').css("display", "block");
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                }
                else{
                    jQuery('.op-Permalinks').css("display", "none");
                }
                if(op_response.ssl_result==true){
                    jQuery('.op-ssl').css("display", "none");
                }
                else{
                    jQuery('.op-ssl').css("display", "block");
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                }
                if(op_response.redError=='yes'){
                    jQuery('#op-sync-pannel-url').off('click');
                    jQuery(".op_sunc_btn").attr("href", "#");
                    jQuery('.op_sunc_btn').removeAttr('id');
                    jQuery(".op_sunc_btn").addClass("op_disable");
                    jQuery('.op_sunc_btn').removeAttr('target');
                }
                if(op_response.localhost=='localhost'){
                    jQuery('.op-localhost').css("display", "block");
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                }
                else{
                    jQuery('.op-localhost').css("display", "none");
                }
                if(op_response.HardBloker !='')
                {	jQuery('.op-plugin-warning').css("display", "flex");
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                    jQuery('.op-system-check .op-hard-error-warning').removeClass("op-hide");
                    jQuery('.op-system-check .op-hard-error-warning').remove();
                    jQuery('.op-system-check .op-plugin-warning').append(op_response.HardBloker);
                }
                if(op_response.SoftBloker !='')
                {	jQuery('.op-plugin-warning').css("display", "flex");
                    jQuery('.op-system-check.op-login-user').css("display", "flex");
                    jQuery('.op-system-check .op-error-warning').removeClass("op-hide");
                    jQuery('.op-system-check .op-error-warning').remove();
                    jQuery('.op-system-check .op-plugin-warning').append(op_response.SoftBloker);
                }
                else{
                    jQuery('.systemcheck ul.list-errors .op-error-warning').addClass("op-hide");
                }
                if(op_response.op_speed =='show'){
                    jQuery('.op-server-speed-check').css({"display": "flex"});
                }
                oliver_pos_stopLoader();

            }
        );
    }
    function oliver_pos_show_registration_view(){
        jQuery('#op-register-banner').css({"display": "block"});
        jQuery('#op-contact-support').css({"display": "grid"});
        jQuery('.op-login-user').css({"display": "none"});
        jQuery("#op-sync-banner").css({"display": "none"});
        jQuery("#op-syncing-banner").css({"display": "none"});
        jQuery("#op-open-hub-reg").css({"display": "none"});
        jQuery(".op_welcomehome").css({"display": "none"});
    }
    function oliver_pos_show_sync_view(){
        jQuery("#op-sync-banner").css({"display": "flex"});
        jQuery(".op_welcomehome").css({"display": "none"});
        jQuery("#op-open-hub-reg").css({"display": "none"});
        jQuery("#op-syncing-banner").css({"display": "none"});
    }
    function oliver_pos_show_synced_view(){
        jQuery("#op-sync-banner").css({"display": "none"});
        jQuery(".op_welcomehome").css({"display": "flex"});
        jQuery("#op-open-hub-reg").css({"display": "flex"});
        jQuery("#op-syncing-banner").css({"display": "none"});
    }
    function oliver_pos_show_syncing_progress_view(){
        jQuery("#op-sync-banner").css({"display": "none"});
        jQuery(".op_welcomehome").css({"display": "none"});
        jQuery("#op-open-hub-reg").css({"display": "none"});
        jQuery("#op-syncing-banner").css({"display": "flex"});
    }
    function oliver_pos_registration_in_progress(){
        jQuery('#op-register-banner').css({"display": "none"});
        jQuery('.op-login-user').css({"display": "flex"});
        jQuery('#op-contact-support').css({"display": "none"});
        jQuery("a#op-book-a-free").attr("href", 'https://www.oliverpos.com/knowledge-base');
        jQuery("#op-sync-banner").css({"display": "flex"});
    }
    function oliver_pos_registration_success(){
        jQuery('#op-register-banner').css({"display": "none"});
        jQuery('.op-login-user').css({"display": "flex"});
        jQuery('#op-contact-support').css({"display": "none"});
        jQuery("a#op-book-a-free").attr("href", 'https://www.oliverpos.com/knowledge-base');
    }
    function oliver_pos_set_timeout(interval){
        setTimeout( function(){
            oliver_pos_get_sync_status();
        }  , interval );
    }
</script>
</body>
</html>