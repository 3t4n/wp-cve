<?php

/**
 * Plugin Name: WP Media Optimizer (.webp)
 * Plugin URI: https://www.francescosganga.it/wordpress/plugin/wp-media-optimizer-webp/
 * Description: Convert your media images to .webp for increase performances, now with lazy load feature
 * Text Domain: wp-media-optimizer-webp
 * Version: 1.4.0
 * Author: Francesco Sganga
 * Author URI: https://www.francescosganga.it/
 **/

function wpmowebp_init()
{
	register_setting('wpmowebp-features', 'wpmowebp-validated');
	register_setting('wpmowebp-options', 'wpmowebp-images-dir');
	register_setting('wpmowebp-options', 'wpmowebp-reviewnotice');
	register_setting('wpmowebp-abovethefold', 'wpmowebp-js');
	register_setting('wpmowebp-abovethefold', 'wpmowebp-css');
	register_setting('wpmowebp-abovethefold', 'wpmowebp-atf');
	register_setting('wpmowebp-options', 'wpmowebp-minifyhtml');

	if (get_option('wpmowebp-atf') == false or get_option('wpmowebp-atf') == '') {
		update_option('wpmowebp-atf', 0);
	}

	if (get_option('wpmowebp-images-dir') == false or get_option('wpmowebp-images-dir') == '') {
		update_option('wpmowebp-images-dir', WP_CONTENT_DIR . "/wpmowebp");
	}
	if (get_option('wpmowebp-reviewnotice') == false or get_option('wpmowebp-reviewnotice') == '') {
		update_option('wpmowebp-reviewnotice', false);
	}
}
add_action('admin_init', 'wpmowebp_init');

function wpmowebp_options_panel()
{
	add_menu_page('WP Media Optimizer', 'WP Media Optimizer', 'manage_options', 'wpmowebp-options', 'wpmowebp_options_settings');
	add_submenu_page('wpmowebp-options', 'Above the Fold', 'Above the Fold', 'manage_options', 'wpmowebp-option-abovethefold', 'wpmowebp_options_abovethefold');
	add_submenu_page('wpmowebp-options', 'About', 'About', 'manage_options', 'wpmowebp-option-about', 'wpmowebp_options_about');
}
add_action('admin_menu', 'wpmowebp_options_panel');

function wpmowebp_detect_browser()
{
	if (stripos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false)
		return "Chrome";
	elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Firefox'))
		return "Firefox";
	elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Safari'))
		return "Safari";
	elseif (stripos($_SERVER['HTTP_USER_AGENT'], 'Opera'))
		return "Opera";
	else
		return false;
}

function wpmowebp_options_settings()
{
	if (isset($_GET['dismissreviewnotice'])) {
		update_option('wpmowebp-reviewnotice', true);
		wp_redirect(home_url() . "/wp-admin/");
	}
	wp_enqueue_script("wpmowebp-admin", plugin_dir_url(__FILE__) . "assets/admin-main.js", array(), "1.0.0", true);
	if (isset($_REQUEST['disablelazyload'])) {
		update_option('wpmowebp-validated', '0');
		print "<strong style='color: red'>";
		_e("Token successfully disabled. Lazy Load Feature removed.", "wp-media-optimizer-webp");
		print "</strong>";
	}

	if (isset($_REQUEST['item_name']) and isset($_REQUEST['token'])) {
		$_REQUEST['item_name'] = urlencode($_REQUEST['item_name']);
		$_REQUES['token'] = urlencode($_REQUEST['token']);
		$response = wp_remote_get("https://www.francescosganga.it/plugins/validate-token.php?item_name={$_REQUEST['item_name']}&token={$_REQUES['token']}");
		$body = json_decode(wp_remote_retrieve_body($response), true);
		if (!$body['result']) {
			print "<strong style='color: red'>";
			_e("Error during token validation. If the problem persists contact me at <a href='mailto:info@francescosganga.it'>info@francescosganga.it</a>", "wp-media-optimizer-webp");
			print "</strong>";
		} else {
			print "<strong style='color: green'>";
			_e("Token successfully validated.", "wp-media-optimizer-webp");
			print "</strong>";
			update_option('wpmowebp-validated', $body['result']);
		}
	}
?>
	<div class="wrap">
		<h1><?php _e("WP Media Optimizer (.webp)", "wp-media-optimizer-webp") ?></h1>
		<h2><?php _e("Settings", "wp-media-optimizer-webp") ?></h2>
		<?php if (get_option('wpmowebp-validated') !== "1") : ?>
			<h2><?php _e("New Feature: Lazy Load!", "wp-media-optimizer-webp") ?></h2>
			<p>
				<?php _e("I released a new version of WP Media Optimizer (.webp)", "wp-media-optimizer-webp") ?><br />
				<?php _e("Now you can enable the new <strong style='color: red'>Lazy Load</strong> feature for your Website", "wp-media-optimizer-webp") ?><br />
				<?php _e("The Lazy Load feature will not load your images until your visitors arrive at the visual field containing the image.", "wp-media-optimizer-webp") ?><br />
				<?php _e("This will help you improve your <a href='https://developers.google.com/speed/pagespeed/insights/' target='_BLANK'>Page Speed Score</a> and speed up your website.", "wp-media-optimizer-webp") ?><br />
				<?php _e("I made this plugin for free, but I ask you to donate to use the new Lazy Load Feature.", "wp-media-optimizer-webp") ?>
				<?php _e("Once you made your donation you will receive to your email address a <strong>life time</strong> token for enable this wonderful feature and you will get enabled all future improvements.", "wp-media-optimizer-webp") ?><br />
				<?php _e("If you have any trouble please contact me at <a href='mailto:info@francescosganga.it'>info@francescosganga.it</a>"); ?>
				<?php _e("Donate Amount:", "wp-media-optimizer-webp") ?><br />
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="business" value="sgangacreations@gmail.com">
				<input type="hidden" name="cmd" value="_donations">
				<input type="hidden" name="item_name" value="Donate for WPMOWEBP">
				<input type="hidden" name="item_number" value="">
				<input type="radio" name="amount" value="5.00" /> &euro; 5.00<br />
				<input type="radio" name="amount" value="10.00" checked /> &euro; 10.00<br />
				<input type="radio" name="amount" value="20.00" /> &euro; 20.00<br />
				<br />
				<input type="text" name="custom" value="" placeholder="Email address..." /><br />
				<br />
				<input type="hidden" name="currency_code" value="EUR">
				<input type="hidden" name="notify_url" value="https://www.francescosganga.it/plugins/donation.php">
				<input type="image" formtarget="_blank" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate">
				<img alt="" width="1" height="1" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif">
			</form>
			</p>
			<h2><?php _e("Token Validation", "wp-media-optimizer-webp") ?></h2>
			<p>
			<form action="" method="POST">
				<input type="hidden" name="item_name" value="Donate for WPMOWEBP" />
				<input type="text" name="token" value="" placeholder="Token..." />
				<input type="submit" value="<?php _e("Validate", "wp-media-optimizer-webp") ?>" />
			</form>
			</p>
		<?php else : ?>
			<h2 style="color: green">
				Features Token Validated.<br />
				<br />
				<form action="" method="POST">
					<input type="hidden" name="disablelazyload" value="1" />
					<input type="submit" value="<?php _e("Disable Lazy Load", "wp-media-optimizer-webp") ?>" />
				</form>
			</h2>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields('wpmowebp-options'); ?>
			<?php do_settings_sections('wpmowebp-options'); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e(".webp images path (often automatically generated)", "wp-media-optimizer-webp") ?></th>
					<td>
						<input type="text" name="wpmowebp-images-dir" value="<?php print get_option('wpmowebp-images-dir'); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e("Minify HTML", "wp-media-optimizer-webp") ?></th>
					<td>
						<select name="wpmowebp-minifyhtml">
							<option value="1" <?= get_option("wpmowebp-minifyhtml") !== "1" ? "" : " selected" ?>><?php _e("Yes", "wp-media-optimizer-webp") ?></option>
							<option value="0" <?= get_option("wpmowebp-minifyhtml") === "1" ? "" : " selected" ?>><?php _e("No", "wp-media-optimizer-webp") ?></option>
						</select>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
		<hr />
		<h2><?php _e("How it works", "wp-media-optimizer-webp") ?></h2>
		<p><?php _e("When anyone access to a Wordpress page, plugin check for images already converted to .webp.", "wp-media-optimizer-webp") ?></p>
		<p><?php _e("If one or more images have not been already converted, the plugin converts them immediately.", "wp-media-optimizer-webp") ?></p>
		<p><?php _e("Converted images are stored in a subfolder of wp-content folder: wp-content/wpmowebp", "wp-media-optimizer-webp") ?></p>
		<hr />
		<h2><?php _e("Feature Request", "wp-media-optimizer-webp") ?></h2>
		<p><?php _e("Using this form you can send me a feature request so I can insert it in the next releases.", "wp-media-optimizer-webp") ?></p>
		<form action="http://www.francescosganga.it/plugins/featurerequest.php?plugin=wpmowebp&url=<?php print (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" method="POST">
			<input type="text" name="title" placeholder="<?php _e("Feature Request Title", "wp-media-optimizer-webp") ?>" /><br /><br />
			<textarea name="message" placeholder="<?php _e("Describe here what feature do you like to see in WPMOWEBP", "wp-media-optimizer-webp") ?>"></textarea><br /><br />
			<input type="submit" class="button button-primary" value="<?php _e("Send Feature Request", "wp-media-optimizer-webp") ?>" />
		</form>
	</div>
<?php
}

function wpmowebp_options_about()
{
?>
	<h1><?php _e("About", "wp-media-optimizer-webp") ?></h1>
<?php
	$response = wp_remote_get("http://www.francescosganga.it/dev/about.html");
	$body = wp_remote_retrieve_body($response);

	print $body;
}

function wpmowebp_options_abovethefold()
{
	$atf = get_option("wpmowebp-atf");
?>
	<h1><?php _e("Above the Fold", "wp-media-optimizer-webp") ?></h1>
	<?php _e("Welcome to the new Above the Fold SECTION of WPMOWEBP! This plugin will try to set 'blacklisted' and 'whitelisted' resources automatically but your website may need your help to work correctly.", "wp-media-optimizer-webp"); ?><br />
	<?php _e("A blacklisted resource will go to the footer of your page.", "wp-media-optimizer-webp"); ?><br />
	<?php _e("A whitelisted resource will remain on the header of your page.", "wp-media-optimizer-webp"); ?><br />
	<?php _e("You may need to update this list if you install any plugin or theme. Purging this list you will loose all settings, remember to save them!", "wp-media-optimizer-webp"); ?> <a href="<?= $_SERVER['REQUEST_URI'] ?>&purge" style="font-weight: bold"><?= _e("Purge", "wp-media-optiomizer-webp") ?></a><br />
	<?php
	if (isset($_REQUEST['purged'])) {
	?>
		<strong style="color: red"><?= _e("List purged! Go to any page of your website to rebuild it!", "wp-media-optiomizer-webp") ?></strong>
	<?php
	}
	?>
	<?php _e("Below you can see a list of current loaded resources. For every loaded resource simply choose if will be blacklisted or whitelisted.", "wp-media-optimizer-webp"); ?><br />
	<hr />
	<strong style="color: blue"><?php if ($atf == "1") _e("Above the Fold feature is currenctly ENABLED");
								else _e("Above the Fold feature is currenctly DISABLED"); ?></strong><br />
	<br />
	<?php _e("<strong>Enable or disable Above the Fold function</strong>", "wp-media-optimizer-webp") ?><br />
	<?php if (isset($_REQUEST['abovethefold'])) {
		update_option("wpmowebp-atf", $_REQUEST['abovethefold']);
		$atf = $_REQUEST['abovethefold'];
		print "<strong style='color: red'>SAVED!</strong>";
	} ?>
	<form action="" method="POST">
		<select name="abovethefold">
			<option value="1" <?= ($atf == "1") ? " selected" : "" ?>><?php _e("Yes", "wp-media-optimizer-webp") ?></option>
			<option value="0" <?= ($atf == "0") ? " selected" : "" ?>><?php _e("No", "wp-media-optimizer-webp") ?></option>
		</select><br />
		<?php submit_button(); ?>
	</form>
	<hr />
	<?php
	if (isset($_REQUEST['purge'])) {
		update_option("wpmowebp-js", false);
		update_option("wpmowebp-css", false);
		header("Location: " . str_replace("&purge", "", $_SERVER['REQUEST_URI']) . "&purged");
	}
	if (isset($_REQUEST['wpmowebp-js-resources']) and isset($_REQUEST['wpmowebp-css-resources'])) {
		$newjs = [
			'whitelisted' => [],
			'blacklisted' => []
		];
		foreach ($_REQUEST['wpmowebp-js-resources'] as $handle => $jsr) {
			$ex = explode("##", $jsr);
			$newjs[$ex[0]][] = [
				'src' => $ex[1],
				'handle' => $handle
			];
		}

		$newcss = [
			'whitelisted' => [],
			'blacklisted' => []
		];
		foreach ($_REQUEST['wpmowebp-css-resources'] as $handle => $cssr) {
			$ex = explode("##", $cssr);
			$newcss[$ex[0]][] = [
				'src' => $ex[1],
				'handle' => $handle
			];
		}

		update_option("wpmowebp-js", serialize($newjs));
		update_option("wpmowebp-css", serialize($newcss));
		print "<strong style='color: green'>SAVED!</strong>";
	}
	?>
	<form action="" method="POST">
		<h3><?php _e("CSS Resources", "wp-media-optimizer-webp") ?></h3>
		<?php
		$css = unserialize(get_option('wpmowebp-css'));
		$cssr = [];
		foreach ($css['whitelisted'] as $c) {
			$cssr[] = [
				'current' => 'whitelisted',
				'handle' => $c['handle'],
				'src' => $c['src']
			];
		}
		foreach ($css['blacklisted'] as $c) {
			$cssr[] = [
				'current' => 'blacklisted',
				'handle' => $c['handle'],
				'src' => $c['src']
			];
		}
		?>
		<?php
		foreach ($cssr as $cr) {
		?>
			<br />
			<strong><?= $cr['handle'] ?></strong><br />
			<select name='wpmowebp-css-resources[<?= $cr['handle'] ?>]'>
				<option value='blacklisted##<?= $cr['src'] ?>' name='<?= $cr['handle'] ?>' <?= $cr['current'] == 'blacklisted' ? ' selected' : '' ?>><?php _e("Blacklisted", "wp-media-optiomizer-webp") ?></option>
				<option value='whitelisted##<?= $cr['src'] ?>' name='<?= $cr['handle'] ?>' <?= $cr['current'] == 'whitelisted' ? ' selected' : '' ?>><?php _e("Whitelisted", "wp-media-optiomizer-webp") ?></option>
			</select><br />
			<br />
		<?php
		}
		?>
		<hr />
		<h3><?php _e("Javascript Resources", "wp-media-optimizer-webp") ?></h3>
		<?php
		$js = unserialize(get_option('wpmowebp-js'));
		$jsr = [];
		foreach ($js['whitelisted'] as $c) {
			$jsr[] = [
				'current' => 'whitelisted',
				'handle' => $c['handle'],
				'src' => $c['src']
			];
		}
		foreach ($js['blacklisted'] as $c) {
			$jsr[] = [
				'current' => 'blacklisted',
				'handle' => $c['handle'],
				'src' => $c['src']
			];
		}
		?>
		<?php
		foreach ($jsr as $jr) {
		?>
			<br />
			<strong><?= $jr['handle'] ?></strong><br />
			<select name='wpmowebp-js-resources[<?= $jr['handle'] ?>]'>
				<option value='blacklisted##<?= $jr['src'] ?>' name='<?= $jr['handle'] ?>' <?= $jr['current'] == 'blacklisted' ? ' selected' : '' ?>><?php _e("Blacklisted", "wp-media-optiomizer-webp") ?></option>
				<option value='whitelisted##<?= $jr['src'] ?>' name='<?= $jr['handle'] ?>' <?= $jr['current'] == 'whitelisted' ? ' selected' : '' ?>><?php _e("Whitelisted", "wp-media-optiomizer-webp") ?></option>
			</select><br />
			<br />
		<?php
		}
		?>
		<?php submit_button(); ?>
	</form>
	<?php
}

function wpmowebp_imagetowebp_v2($source, $dest)
{
	$source = realpath($_SERVER['DOCUMENT_ROOT']) . $source;
	$dest = realpath($_SERVER['DOCUMENT_ROOT']) . $dest;
	$extension = pathinfo($source, PATHINFO_EXTENSION);

	switch ($extension) {
		case "jpg":
			$img = imagecreatefromjpeg($source);
			imagepalettetotruecolor($img);
			if (!imagewebp($img, $dest))
				return false;
			break;

		case "jpeg":
			$img = imagecreatefromjpeg($source);
			imagepalettetotruecolor($img);
			if (!imagewebp($img, $dest))
				return false;
			break;

		case "png":
			$img = imagecreatefrompng($source);
			imagepalettetotruecolor($img);
			if (!imagewebp($img, $dest))
				return false;
			break;
	}

	return true;
}

function wpmowebp_imagetowebp($wpcontentdir, $realImage)
{
	$realImage = WP_CONTENT_DIR . str_replace($wpcontentdir, "", $realImage);
	if (file_exists($realImage)) {
		if (!is_dir(get_option('wpmowebp-images-dir')))
			mkdir(get_option('wpmowebp-images-dir'), 0755, true);

		$image = get_option('wpmowebp-images-dir') . "/" . str_replace(WP_CONTENT_DIR, "{$wpcontentdir}/", $realImage);
		$path = dirname($image);
		$filename = pathinfo($image, PATHINFO_FILENAME);
		$extension = pathinfo($image, PATHINFO_EXTENSION);

		if (!is_dir($path))
			mkdir($path, 0755, true);

		if (!file_exists("{$path}/{$filename}.webp")) {
			switch ($extension) {
				case "jpg":
					$img = imagecreatefromjpeg($realImage);
					imagepalettetotruecolor($img);
					if (!imagewebp($img, "{$path}/{$filename}.webp"))
						return false;
					break;

				case "jpeg":
					$img = imagecreatefromjpeg($realImage);
					imagepalettetotruecolor($img);
					if (!imagewebp($img, "{$path}/{$filename}.webp"))
						return false;
					break;

				case "png":
					$img = imagecreatefrompng($realImage);
					imagepalettetotruecolor($img);
					if (!imagewebp($img, "{$path}/{$filename}.webp"))
						return false;
					break;
			}
		}

		return true;
	} else {
		return false;
	}
}

function wpmowebp_reviewnotice()
{
	$reviewnotice = get_option('wpmowebp-reviewnotice');
	if ($reviewnotice == false) {
	?>
		<div class="notice notice-warning">
			<p><?php _e("I hope you like <strong>WP Media Optimizer (.webp)</strong>.", 'wp-media-optimizer-webp'); ?></p>
			<p><?php _e("I would like to ask you if you can <a href=\"https://wordpress.org/support/plugin/wp-media-optimizer-webp/reviews/\" target=\"_BLANK\">review my plugin</a>.", 'wp-media-optimizer-webp'); ?></p>
			<p><?php printf(__("<a href=\"%s\">I already reviewed WPMOWEBP</a>", 'wp-media-optimizer-webp'), home_url() . "/wp-admin/admin.php?page=wpmowebp-options&dismissreviewnotice"); ?></p>
		</div>
	<?php
	}
}
add_action("admin_notices", "wpmowebp_reviewnotice");

function wpmowebp_check_activation_notice()
{
	if (get_transient('wpmowebp-activation-notice')) {
	?>
		<div class="notice notice-success is-dismissible">
			<p><?php printf(__("Welcome to WP Media Optimizer (.webp). You don't need to do anything. Go to your <a href=\"%s\">homepage</a> and you will see your converted webp images.", "wp-media-optimizer-webp"), home_url()) ?></p>
		</div>
<?php
		delete_transient('wpmowebp-activation-notice');
	}
}
add_action("admin_notices", "wpmowebp_check_activation_notice");

register_activation_hook(__FILE__, 'wpmowebp_check_activation_notice_hook');
function wpmowebp_check_activation_notice_hook()
{
	set_transient('wpmowebp-activation-notice', true, 5);
}

function wpmowebp_filter_content($content)
{
	if (is_admin() && (!defined('DOING_AJAX') || !DOING_AJAX))
		return $content;
	$currentBrowser = wpmowebp_detect_browser();
	if (wpmowebp_detect_browser() != false) {
		$prot = isset($_SERVER["HTTPS"]) ? 'https' : 'http';
		$withoutwww = str_replace("www.", "", $_SERVER['SERVER_NAME']);
		$withwww = "www." . $withoutwww;

		//relative urls
		$content = preg_replace_callback("/\/([^\/]+)\/uploads\/([^\/]+)\/([^\/]+)\/([äöüåA-zÀ-ú0-9-.]+).(png|jpg|jpeg)/", function ($matches) {
			$url = str_replace($matches[5], "webp", $matches[0]);
			$r = [];
			$r[] = "";
			$r[] = $matches[0];
			$r[] = $url;
			$r[] = var_export(file_exists($url), true);
			$r[] = realpath($_SERVER['DOCUMENT_ROOT']) . $matches[0];
			$r[] = var_export(wpmowebp_imagetowebp_v2($matches[0], $url), true);
			//return implode("\n", $r);
			if (file_exists($url)) return "exists";

			if (wpmowebp_imagetowebp_v2($matches[0], $url))
				return $url;
			else
				return "error";
		}, $content);
	}
	//lady load
	if (get_option('wpmowebp-validated') == "1") {
		$content = preg_replace_callback("/<img(|(.*))src\=(\"|')([^ ]*)(\"|')/", function ($matches) {
			return "<img{$matches[1]}data-lazysrc=\"{$matches[4]}\"";
		}, $content);
	}

	//minify html
	if (get_option('wpmowebp-minifyhtml') == "1") {
		$search = array(
			'/\>[^\S ]+/s',     // strip whitespaces after tags, except space
			'/[^\S ]+\</s',     // strip whitespaces before tags, except space
			'/(\s)+/s',         // shorten multiple whitespace sequences
			'/<!--(.|\s)*?-->/' // Remove HTML comments
		);

		$replace = array(
			'>',
			'<',
			'\\1',
			''
		);

		$content = preg_replace($search, $replace, $content);
	}

	return $content;
}

//some useful functions
function strposa($haystack, $needle, $offset = 0)
{
	if (!is_array($needle)) $needle = array($needle);
	foreach ($needle as $query) {
		if (strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
	}
	return false;
}

// disable srcset on frontend
function wpmowebp_disablesrcset()
{
	return 1;
}
add_filter('max_srcset_image_width', 'wpmowebp_disablesrcset');

function wpmowebp_scripts()
{
	if (get_option('wpmowebp-validated') == "1") {
		wp_enqueue_style('wpmowebp-lazy-load', plugins_url('/assets/css/lazy-load.css', __FILE__));
		wp_enqueue_script('wpmowebp-lazy-load', plugins_url('/assets/js/lazy-load.js', __FILE__), array("jquery"));
	}
}
add_action("init", "wpmowebp_scripts");

function wpmowebp_abovethefold()
{
	global $wp_scripts;
	global $wp_styles;
	$css = get_option('wpmowebp-css');
	$js = get_option('wpmowebp-js');

	if (!$js || $js === "") {
		$js = [];
		$jsbl = [];
		$jswl = [];
		foreach ($wp_scripts->queue as $style) {
			$js['whitelisted'][] = [
				'handle' => $wp_scripts->registered[$style]->handle,
				'src' => $wp_scripts->registered[$style]->src
			];
		}
		update_option('wpmowebp-js', serialize($js));
	} else {
		$js = unserialize($js);
	}

	if (!$css || $css === "") {
		$css = [];
		foreach ($wp_styles->queue as $style) {
			$css['whitelisted'][] = [
				'handle' => $wp_styles->registered[$style]->handle,
				'src' => $wp_styles->registered[$style]->src
			];
		}
		update_option('wpmowebp-css', serialize($css));
	} else {
		$css = unserialize($css);
	}

	if (get_option('wpmowebp-atf') == "1") {
		foreach ($css['blacklisted'] as $style) {
			$src = $wp_styles->registered[$style['handle']]->src;
			wp_dequeue_style($style['handle']);
			wp_deregister_style($style['handle']);
			wp_styles()->remove($style['handle']);
		}
	}
}

add_action("wp_enqueue_scripts", "wpmowebp_abovethefold", 99999);

function wpmowebp_queuetofooter()
{
	if (get_option('wpmowebp-atf') === 1) {
		$js = unserialize(get_option("wpmowebp-js"));
		$css = unserialize(get_option("wpmowebp-css"));
		foreach ($css['blacklisted'] as $style) {
			wp_enqueue_style($style['handle'], $style['src'], true, '1.0', 'all');
		}
	}
}
add_action("get_footer", "wpmowebp_queuetofooter");

ob_start('wpmowebp_filter_content');
