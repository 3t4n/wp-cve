<?php
/*
Plugin Name: Sheet2Site
Plugin URI: https://wordpress.org/plugins/sheet2site/
Description: Embed your Google Sheet into your WordPress website
Author: Sheet2Site
Version: 1.0.18
Author URI: https://sheet2site.com
 */
namespace sheet2site;

function is_hook_supported($hook) {
	return $hook == 'plugins.php' || $hook == 'plugins_page_sheet2site';
}

function insert_embed($key, $should_use_full_page = false, $version = "") {
	if ($version == "new") {
		$version = "api.";
	}

	if ($should_use_full_page) {
		$response = wp_remote_get("https://" . $version . "sheet2site.com/api/v3/index.php?key=" . $key . "&g=1");
		return wp_remote_retrieve_body($response);
	}

	$result = '
        <div data-sheet2site="' . $key . '&g=1"></div>
        <script src="https://' . $version . 'sheet2site.com/js/embedded.js"></script>
    ';
	return $result;
}

function s2s_shortcode($atts = [], $content = null, $tag = '') {
	$atts = array_change_key_case((array) $atts, CASE_LOWER);

	$s2s_atts = shortcode_atts([
		'key' => '',
		'full' => '',
		'version' => '',
	], $atts, $tag);

	$key = $s2s_atts['key'];
	if ($key === '') {
		return <<<HTML
            <p style="color: red;">
                <b>Sheet2Site Error:</b><br>
                Please insert a key of your Sheet2Site website, for example: <br>
                [sheet2site key=1KAJrWQvoJx9a59w79iyStklxUQeJa93LOtODWhwjFM8]
            </p>
HTML;
	}
	$should_use_full_page = $s2s_atts['full'] === 'true';
	$version = $s2s_atts['version'] === 'new';

	return insert_embed($key, $should_use_full_page, $version);
}

function sheet2site_init() {
	add_shortcode("sheet2site", 'sheet2site\s2s_shortcode');
}

function display_notice() {
	global $hook_suffix;
	$activated = get_option('sheet2site_terms_accepted', FALSE);
	if (is_hook_supported($hook_suffix) && !$activated) {
		?>
        <div class="notice notice-warning sheet2site-activation">
            <span>Last step to start using Sheet2Site plugin.</span>
            <br>
            <span>By clicking Accept, I confirm I have read and accept <a target="_blank" href="https://sheet2site.com/wordpress/terms">Terms of Use and Services</a>.</span>
            <form id="sheet2site-terms-ajax">
                <?=wp_nonce_field('sheet2site_accept_terms_of_use')?>
                <input type="hidden" name="action" value="accept_terms">
                <input type="hidden" name="terms" value="1">
                <input type="submit" name="submit" class="button button-primary" value="Accept">
            </form>
        </div>
        <?php
}
}

function asset($path) {
	return esc_url(plugins_url($path, __FILE__));
}

function show_s2s_options_page() {
	?>
    <!-- SETTING PAGE START ----------------------------------------------------- -->

    <div class="wrap sheet2site-help">
        <h1>Sheet2Site Plugin Documentation--</h1>
		</hr>
        <h2>1. Quick Start</h2>
        <p>To quickly check how it will looks on your website please do this:</p>
        <ol>
            <li>Create a new or open an existing page in your WordPress website.</li><br>
            <li>Add new block -> shortcode:</li><br>
            <img style="width: 500px" src="<?=asset('/assets/img/wp1.png')?>"></img><br>
            <br>
            <li>Paste this code: [sheet2site key=1KAJrWQvoJx9a59w79iyStklxUQeJa93LOtODWhwjFM8]</li>
            <img style="width: 900px" src="<?=asset('/assets/img/wp2a.png')?>"></img>
            <li>Publish the page</li>
        </ol>
        <p>You should see embedded catalog of the products in your page. But It's just an sample, so you can't edit it:</p>
        <img style="width: 600px" src="<?=asset('/assets/img/wp3.jpg')?>"></img>
        <hr>
        <h2>2. How to use your own spreadsheet?</h2>
        <p></p>
        <ol>
            <li>Please install <a target="_blank" href="https://chrome.google.com/webstore/detail/sheet2site/lnghdaodcgpnlelngmedmjbencnpiach">Sheet2Site Google Sheet Add-on</a> </li><br>
            <li>Choose a template:</li><br>
            <img style="width: 500px" src="<?=asset('/assets/img/getting_started.jpg')?>"></img>
            <br><br>
            <li> Open menu: Embed in your website:</li><br>
            <img style="width: 500px" src="<?=asset('/assets/img/embed1.png')?>"></img>
            <br><br>
            <li>Copy WordPress shortcode. Example: [sheet2site key=1KAJrWQvoJx9a59w79iyStklxUQeJa93LOtODWhwjFM8&g=1&e=1]</li><br>
            <img style="width: 500px" src="<?=asset('/assets/img/embed3.png')?>"></img><br><br><br>
            <li>Go back to your WordPress page and paste code into Sheet2Site shortcode:</li>
            <img style="width: 900px" src="<?=asset('/assets/img/wp2a.png')?>"></img>
            <li>Publish the page</li>
        </ol>
        <hr>
        <h2>⭐️ Go Premium ⭐️</h2>

           <a style="background-color: #00a760; background-color: #00a760;
    padding: 6px;
    color: white;
    border-style: solid;
    border-color: #00a760;
    border-radius: 3px;
    text-decoration: none;" href="https://www.sheet2site.com/pricing/" class="btn-small" id="subscribe-btn" target="_blank">
             Visit Pricing Page
           </a>
         <hr>
        <h2>Help/Feedback</h2>
        <p>If you need any help please message us in the <a target="_blank" href="https://sheet2site.com/wordpress/support">support chat</a>.</p>
    </div>

    <!-- SETTING PAGE END ----------------------------------------------------- -->

    <?php
}

function s2s_options_page() {
	add_plugins_page(
		'Sheet2Site plugin',
		'Sheet2Site',
		'manage_options',
		'sheet2site',
		'sheet2site\show_s2s_options_page'
	);
}

function process_post_from_admin_page() {
	if (!check_ajax_referer('sheet2site_accept_terms_of_use')) {
		error_log('failed nonce verification');
		return;
	}

	$accepted_terms = isset($_POST['terms']);
	if ($accepted_terms) {
		update_option('sheet2site_terms_accepted', TRUE);
	} else {
		update_option('sheet2site_terms_accepted', FALSE);
	}
}

function add_setting_link_to_plugin_in_list($actions, $plugin_file) {
	static $plugin;
	if (!isset($plugin)) {
		$plugin = plugin_basename(__FILE__);
	}

	if ($plugin == $plugin_file) {
		$settings = array('settings' => '<a href="' . admin_url('plugins.php?page=sheet2site') . '">Settings</a>');
		$actions = array_merge($settings, $actions);
	}

	return $actions;
}

function s2s_deactivate() {
	delete_option('sheet2site_terms_accepted');
}

function maybe_add_assets_to_admin_page($hook) {
	if (!is_hook_supported($hook)) {
		return;
	}

	$script_path = '/assets/sheet2site.js';
	wp_enqueue_script(
		'sheet2site-admin',
		asset($script_path),
		array(),
		filemtime(dirname(__FILE__) . $script_path)
	);
	wp_localize_script('sheet2site-admin', 'sheet2siteAdmin', array(
		'pluginPage' => admin_url('plugins.php?page=sheet2site'),
	));

	$style_path = '/assets/sheet2site.css';
	wp_enqueue_style(
		'sheet2site-admin',
		asset($style_path),
		array(),
		filemtime(dirname(__FILE__) . $style_path)
	);
}

add_action('init', 'sheet2site\sheet2site_init');
add_action('admin_notices', 'sheet2site\display_notice');
add_action('admin_menu', 'sheet2site\s2s_options_page');
add_action('admin_enqueue_scripts', 'sheet2site\maybe_add_assets_to_admin_page');
add_action('wp_ajax_accept_terms', 'sheet2site\process_post_from_admin_page');
add_filter('plugin_action_links', 'sheet2site\add_setting_link_to_plugin_in_list', 10, 5);
add_action('deactivate_sheet2site/sheet2site.php', 'sheet2site\s2s_deactivate');
