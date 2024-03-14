<?php

$site_url = get_site_url();

$config = include plugin_dir_path(__DIR__) . '../config/config.php';

$ms_install_slug = $config['ms_install_slug'];
$app_slug = $config['app_slug'];
$app_token = $config['token'];
$app_platform = $config['platform'];

$currentUser = wp_get_current_user();

$domain = base64_encode($site_url);
$email = base64_encode($currentUser->user_email);
$siteName  = base64_encode(get_bloginfo('name'));

$email_divided = explode('@',  $currentUser->user_email);
$token_psw = $app_token . '-' . str_replace(['@', ',', '.', '_'], [''], $email_divided[0]);
$token = base64_encode($token_psw);

$sign_up_link = "$ms_install_slug/sign-up/$app_slug/$app_platform?domain=$domain&email=$email&token=$token&name=$siteName";
$sign_in_link = "$ms_install_slug/auto-sign-in/$app_slug/$app_platform?domain=$domain&email=$email&token=$token&name=$siteName&token_psw=$token_psw";
//$sign_in_link = "$ms_install_slug/sign-in/$app_slug/$app_platform?domain=$domain&email=$email&token=$token&name=$siteName&checkexists=true";

$wc_installed =  is_plugin_active( 'woocommerce/woocommerce.php' ) ? true : false;
$wc_access = get_option('wc_api_access');
$wc_access = (!empty($wc_access) && ($wc_access == 'yes')) ? true : false;
$wc_access_true = (!empty($wc_installed) && (!empty($wc_access))) ? true : false;

$api_file_link = get_option('api_file_link');
$api_file_link = $api_file_link ?? '';
?>

<div class="login-mgs">
	<div class="login-mgs__wrapper">
		<div class="login-mgs__content">
			<h2 class="login-mgs__title">Account links:</h2>

			<?php if (empty($wc_access_true)) { ?>
				<h4 class="login-mgs__subtitle">You didn't approve access to Woocommerce data yet.</h4>
				<p class="login-mgs__approve">You can do it <a
						href="<?php echo admin_url('admin.php?page=woocommerce_access') ?>">here</a></p>
			<?php } else { ?>
				<h4 class="login-mgs__approved">You have approved access to Woocommerce.</h4>
			<?php } ?>
			<div class="login-mgs__btns">
				<a class="login-mgs__btn sign-up" href="<?php echo $sign_up_link; ?>" target="_blank">Sign Up</a>
				<a class="login-mgs__btn sign-in" href="<?php echo $sign_in_link; ?>" target="_blank">Sign In</a>
			</div>
		</div>

		<?php if (!empty($api_file_link)) { ?>
			<div class="login-mgs-script script_included" style="display: none;">
				<div class="login-mgs-script-wrap">
					<p class="login-mgs-script-text">Script is included:</p>
					<p><?php echo $api_file_link; ?></p>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
