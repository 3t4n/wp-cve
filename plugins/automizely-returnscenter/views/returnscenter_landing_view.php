<?php
// Prevent direct file access
if (!defined('ABSPATH')) {
    exit;
}

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

$this->options      = get_option('returnscenter_option_name');
$is_display_connected = $this->options['connected'] && $this->options['connected'] === true;

$store_url = get_home_url();

$query = [
    'shop'=>$store_url,
    'utm_source' => 'wordpress_plugin',
    'utm_medium' => 'landingpage'
];

$debug = isset($_GET['debug']) ? $_GET['debug'] : 'no';

$go_to_dashboard_url = "https://accounts.aftership.com/oauth-session?callbackUrl=".urlencode("https://accounts.aftership.com/oauth/woocommerce-returnscenter?signature=".base64_encode(json_encode($query)));
$go_to_visit_url = "https://www.returnscenter.com/?utm_source=wordpress_plugin&utm_medium=landingpage";

if ($debug === 'yes') {
    $go_to_dashboard_url = "https://accounts.aftership.io/oauth-session?callbackUrl=".urlencode("https://accounts.aftership.io/oauth/woocommerce-returnscenter?signature=".base64_encode(json_encode($query)));
    $go_to_visit_url = "https://www.returnscenter.com/?utm_source=wordpress_plugin&utm_medium=landingpage";
}


?>

<!-- Main wrapper -->
<div class="auto-rc-admin-container">
    <div class="auto-rc-admin-header">
        <div class="auto-rc-admin-logo">
            <img
                src="https://websites.am-static.com/assets/brands/logo/aftership_returns.svg"
                alt=""
            />
        </div>
        <?php if (!$is_display_connected) { ?>
            <div class="auto-rc-admin-header-title">Connect with Returns Center</div>
            <div class="auto-rc-admin-header-desc">
                Remove friction from returns with a branded interactive return page.
                Reduce costs and recapture revenue.
            </div>
            <button class="auto-rc-admin-header-button" onclick="window.open('<?php echo $go_to_dashboard_url; ?>')">
                Connect now
            </button>
        <?php }else{ ?>
            <div class="auto-rc-admin-header-title">You have connected with Returns Center</div>
        <?php } ?>
    </div>
    <?php if (!$is_display_connected) { ?>
    <div class="auto-rc-index-content">
    <img
        style="
        width: 600px;
        height: 540px;
        display: block;
        margin: 80px auto 0px;
        "
        src="<?php echo AUTOMIZELY_RETURNSCENTER_URL . '/assets/images/returns-index-img.png'; ?>"
        alt=""
    />
    </div>
    <?php } ?>
    <?php if ($is_display_connected) { ?>
        <div class="auto-rc-admin-link">
            <div class="auto-rc-admin-link-content">
                <span class="auto-rc-admin-text-bold">Loving AfterShip Returns? Rate us on the </span><a href="https://wordpress.org/plugins/automizely-returnscenter/#reviews">WordPress Plugin Directory</a>
            </div>
        </div>
        <div class="auto-rc-admin-recommand">
            <div class="auto-rc-admin-recommand-title">Recommand for you</div>
            <div class="auto-rc-admin-recommand-list">
                <!-- tracking -->
                <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=aftership-woocommerce-tracking" target="_blank">
                    <div class="auto-rc-admin-recommand-list-item">
                        <img style="width: 64px; height: 64px" src="https://websites.am-static.com/assets/brands/glyph/aftership_tracking.svg" alt="" />
                        <div class="auto-rc-admin-recommand-list-item-detail">
                        <span>
                        <strong>AfterShip Tracking </strong>
                        </span>
                        <span>
                            All-in-one post-purchase platform to help brands build trust, drive loyalty, and accelerate revenue
                        </span>
                        </div>
                    </div>
                </a>

                <!-- shipping -->
                <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=postmen-woo-shipping" target="_blank">
                    <div class="auto-rc-admin-recommand-list-item">
                        <img style="width: 64px; height: 64px" src="https://websites.am-static.com/assets/brands/glyph/aftership_shipping.svg" alt="" />
                        <div class="auto-rc-admin-recommand-list-item-detail">
                        <span>
                        <strong>AfterShip Shipping </strong>
                        </span>
                        <span>
                            Multi-carrier shipping tool to help brands automate their shipping process and print labels faster
                        </span>
                        </div>
                    </div>
                </a>

                <!-- marketing -->
                <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=automizely-marketing" target="_blank">
                    <div class="auto-rc-admin-recommand-list-item">
                        <img
                                style="width: 64px; height: 64px"
                                src="https://websites.am-static.com/assets/brands/glyph/aftership_email.svg"
                                alt=""
                        />
                        <div class="auto-rc-admin-recommand-list-item-detail">
                        <span>
                        <strong>Automizely Marketing </strong>
                        </span>
                        <span>
                        The all-in-one marketing automation platform for WooCommerce stores
                        </span>
                        </div>
                    </div>
                </a>

                <!-- feed -->
                <a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=feed-for-tiktok-shop" target="_blank">
                    <div class="auto-rc-admin-recommand-list-item">
                        <img
                                style="width: 64px; height: 64px"
                                src="https://websites.am-static.com/assets/brands/glyph/aftership_feed.svg"
                                alt=""
                        />
                        <div class="auto-rc-admin-recommand-list-item-detail">
                        <span>
                        <strong>Automizely Feed </strong>
                        </span>
                        <span>
                        Sync and sell with TikTok Shop in minutes
                        </span>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php } ?>
</div>
