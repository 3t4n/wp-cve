<?php
if (!defined('ABSPATH')) {
	exit;
}

$tabs = ['welcome', 'buttons', 'customer-notifications', 'seller-notifications', 'abandoned-carts', 'settings', 'news', 'use-your-phone', 'invite-a-friend'];
$GLOBALS['current_tab'] = isset($_GET['tab']) && in_array($_GET['tab'], $tabs) ? $_GET['tab'] : $tabs[0];
$has_telephone = isset($this->options['telephone']) && $this->options['telephone'] != '';
$has_wc = spoki_has_woocommerce();
$account_info = $this->options['account_info'] ?? ['plan' => []];
$is_free = (!isset($account_info['plan']['slug']) || (isset($account_info['plan']['slug']) && $account_info['plan']['slug'] == 'flex-10'));
$is_pro = isset($account_info['plan']['is_pro']) && $account_info['plan']['is_pro'] == true;
$is_credit_based = isset($account_info['plan']['is_credit_based']) && $account_info['plan']['is_credit_based'] == true;
$has_abandoned_carts = isset($this->options['abandoned_carts']['enable_tracking']) && $this->options['abandoned_carts']['enable_tracking'] == 1;
$has_seller_notifications = isset($this->options['woocommerce']['order_created_to_seller']) && $this->options['woocommerce']['order_created_to_seller'] == 1;
$has_spoki_settings = isset($this->options['secret']) && $this->options['secret'] != '' && isset($this->options['delivery_url']) && $this->options['delivery_url'] != '';


if ($has_wc && !$has_abandoned_carts): ?>
    <div class="notice notice-warning">
        <p>
			<?php
			$link = "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=abandoned-carts";
			/* translators: %1$s: Abandoned Carts link. */
			printf(__('<strong>Warning!</strong> Visitors may have abandoned their cart! Discover the new <a href="%1$s">Abandoned Carts</a> feature and reduce dropout rates.', "spoki"), $link) ?>
        </p>
    </div>
<?php endif ?>

<div class="wrap spoki-admin">
    <h1 class="title">
        <b><?php _e('Spoki', "spoki") ?></b>
        <img src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/logo.svg' ?>" alt="spoki logo">
    </h1>

	<?php if (!$has_telephone) { ?>
        <div class="notice notice-error">
            <p>
				<?php _e('Please insert a valid telephone number!', "spoki"); ?>
                <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=settings">
					<?php _e('Settings', "spoki") ?>
                </a>
            </p>
        </div>
	<?php } ?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=welcome" class="nav-tab <?php echo esc_attr($GLOBALS['current_tab'] == 'welcome') ? 'nav-tab-active' : ''; ?>">
			<?php _e((!$has_wc || !$has_spoki_settings) ? 'Welcome' : 'Statistics', "spoki"); ?>
        </a>
        <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=buttons" class="nav-tab <?php echo esc_attr($GLOBALS['current_tab'] == 'buttons') ? 'nav-tab-active' : ''; ?>">
			<?php _e('Buttons', "spoki"); ?>
        </a>
        <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=customer-notifications" class="nav-tab <?php echo esc_attr($GLOBALS['current_tab'] == 'customer-notifications') ? 'nav-tab-active' : ''; ?>">
			<?php _e('Customer Notifications', "spoki"); ?>
        </a>
        <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=seller-notifications" class="nav-tab <?php echo esc_attr($GLOBALS['current_tab'] == 'seller-notifications') ? 'nav-tab-active' : ''; ?>">
            <div class="nav-tab-content">
				<?php _e('Seller Notifications', "spoki"); ?>
            </div>
        </a>
        <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=abandoned-carts" class="nav-tab <?php echo esc_attr($GLOBALS['current_tab'] == 'abandoned-carts') ? 'nav-tab-active' : ''; ?>">
			<?php _e('Abandoned Carts', "spoki"); ?>
        </a>
        <a style="margin-right: 1rem;" href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=settings" class="nav-tab <?php echo esc_attr($GLOBALS['current_tab'] == 'settings') ? 'nav-tab-active' : ''; ?>">
			<?php _e('Settings', "spoki"); ?>
        </a>
		<?php if (!$is_pro): ?>
            <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=use-your-phone" class="nav-tab <?php echo esc_attr($GLOBALS['current_tab'] == 'use-your-phone') ? 'nav-tab-active' : ''; ?>">
				<?php _e('Send from your phone number', "spoki"); ?>
            </a>
		<?php endif; ?>
		<?php if (!$is_credit_based): ?>
            <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=invite-a-friend" class="nav-tab <?php echo esc_attr($GLOBALS['current_tab'] == 'invite-a-friend') ? 'nav-tab-active' : ''; ?>">
				<?php _e('Invite a friend', "spoki"); ?>
            </a>
		<?php endif; ?>
    </h2>

    <form name="spoki-options-form" method="post" action="options.php">
		<?php settings_fields('wp-spoki-option');
		require_once SPOKI_DIR . 'views/html-welcome.php';
		require_once SPOKI_DIR . 'views/html-settings.php';
		require_once SPOKI_DIR . 'views/html-buttons.php';
		require_once SPOKI_DIR . 'views/html-customer-notifications.php';
		require_once SPOKI_DIR . 'views/html-seller-notifications.php';
		require_once SPOKI_DIR . 'views/html-abandoned-carts.php';
		require_once SPOKI_DIR . 'views/html-news.php';
		require_once SPOKI_DIR . 'views/html-use-your-phone.php';
		require_once SPOKI_DIR . 'views/html-invite-friend.php';
		?>
    </form>

    <div id="spoki-bottom-badges">
        <div id="spoki-feedback">
            <a href="?page=<?php echo urlencode(SPOKI_PLUGIN_NAME) ?>&tab=news">
                ✩ <?php _e('What\'s New?', "spoki") ?>
            </a>
        </div>

        <div id="spoki-support">
            <a href="https://api.whatsapp.com/send/?phone=393666989618&text=Hi%20Spoki,%20I%20need%20support%20about%20Spoki%20Plugin" target="_blank">
				<?php echo spoki_get_wa_logo() ?>
				<?php _e('Need Support?', "spoki") ?>
            </a>
        </div>
    </div>
</div>
