<?php
/*
Plugin Name: Dashboard quick link widget
Plugin URI: http://www.hemthapa.com
Description: This lightweight plugin creates new widget in dashboard with quick access link  button.
Version: 1.6.0
Author: Hem Thapa
Author URI: http://www.hemthapa.com
License: GPL2
*/


/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
	die;
}


/**
 * Enqueue plugin scripts and styles
 */
add_action('admin_enqueue_scripts', 'enqueueScriptsAndStyles');

function enqueueScriptsAndStyles()
{
	// css files
	wp_enqueue_style('style-dqlw', plugins_url('dqlw.css', __FILE__), array(), '1.6.0', 'all');
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_style('fontawesome-script', loadFontAwesomeCdns());

	// js scripts
	wp_enqueue_script('wp-color-picker');
}


/**
 * Add admin setting menu
 */
add_action('admin_menu', 'addAdminSettingMenu');

function addAdminSettingMenu()
{
	add_action('admin_init', 'registerWidgetSettingVariables');
	add_options_page("Dashboard links widget", "Dashboard links widget", "administrator", 'dashboard-quick-link-widget', 'renderPluginSettingForm');
}


/**
 * Register plugin setting variables
 */
function registerWidgetSettingVariables()
{
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_enable');
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_title');
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_header_notice');
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_link_list');
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_open_link');
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_footer_notice');
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_mbox_bcolor');
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_mbox_fcolor');
	register_setting('dashboard-quick-link', 'dashboard_quick_link_widget_fa_version', array('default' => '3.x'));
}


/**
 * Add plugin setting link on the plugin list
 */
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'addPluginSettingActionLink');

function addPluginSettingActionLink($actions)
{

	$settinglink = array(
		'<a href="' . admin_url('options-general.php?page=dashboard-quick-link-widget') . '">Settings</a>',
	);

	$actions = array_merge($actions, $settinglink);
	return $actions;
}


/**
 * load the widget on the dashboard
 */
add_action('wp_dashboard_setup', 'loadDashboardWidget');

function loadDashboardWidget()
{

	// check if dashboard_quick_link_widget_enable variable is on,
	if (trim(esc_attr(get_option('dashboard_quick_link_widget_enable'))) == 'on') {

		// check if widget title is set from the setting page, if not default title is used.
		if (get_option('dashboard_quick_link_widget_title') != false) {
			$widgetTitle = esc_attr(get_option('dashboard_quick_link_widget_title'));
		} else {
			$widgetTitle = "Dashboard quick links";
		}

		wp_add_dashboard_widget(
			'dashboard_quick_links_widget',
			$widgetTitle,
			'renderDashboardWidgetContents'
		);
	}
}


/**
 * Render plugin setting page
 */
function renderPluginSettingForm()
{ ?>

	<div class="wrap">

		<h1>Dashboard quick link widget</h1>

		<form method="post" action="options.php">

			<?php
			settings_fields('dashboard-quick-link');
			do_settings_sections('dashboard-quick-link');
			?>

			<table class="form-table quick_dashboard_link_form">
				<tr valign="top">
					<th scope="row"><strong>Enable dashboard widget</strong></th>
					<td>
						<input type="checkbox" name="dashboard_quick_link_widget_enable" <?php if (get_option('dashboard_quick_link_widget_enable') == 'on') {
																								echo " checked";
																							} ?>>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Widget title </th>
					<td>
						<input type="text" name="dashboard_quick_link_widget_title" value="<?php echo esc_attr(get_option('dashboard_quick_link_widget_title')); ?>" placeholder="Dashboard quick links" size="50" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Header message<br>(You can use HTML content)</th>
					<td>
						<textarea name="dashboard_quick_link_widget_header_notice" rows="4" cols="50" placeholder="Enter header message"><?php echo esc_attr(get_option('dashboard_quick_link_widget_header_notice')); ?></textarea>
					</td>
				</tr>


				<!----- links list ----->
				<tr valign="top">
					<th scope="row">Links list</th>
					<td>List each link in separate lines<br>
						<textarea name="dashboard_quick_link_widget_link_list" rows="8" cols="120" placeholder='Post new blog article | /wp-admin/post-new.php | Create new post'><?php echo esc_attr(get_option('dashboard_quick_link_widget_link_list')); ?></textarea><br>
						<div class="dashboard-link-widget-infobox"><strong>Link format: </strong><i> Link text </i>| <i> Button link </i>| <i> Button text</i> | <i> Fontawesome icon class (optional) </i><br><b>Example</b>: <br><u>Post new blog article</u> | <u>/wp-admin/post-new.php</u> | <u>Create new post</u>| <u>fa-pencil-square-o</u> (with icon)<br><u>Post new blog article</u> | <u>/wp-admin/post-new.php</u> | <u>Create new post</u> (Without icon)<br>Post new blog article | /wp-admin/post-new.php newtab | Create new post (Open link in new tab)</div>
					</td>
				</tr>


				<!----- link target settings ----->
				<tr valign="top">
					<th scope="row">Link target</th>
					<td>
						<input type="checkbox" name="dashboard_quick_link_widget_open_link" id="dashboard_quick_link_target" <?php if (get_option('dashboard_quick_link_widget_open_link') != false) {
																																	echo " checked";
																																} ?>>
						<label for="dashboard_quick_link_target">Open all links in a new tab</label>
						<div class="dashboard-link-widget-infobox">
							If you want to open only selected links in a new tab, please add the '<strong>newtab</strong>' keyword on the link parameter.<br>
							Example:<br>Post new blog article | /wp-admin/post-new.php <strong>newtab</strong> | Create new post
						</div>
					</td>
				</tr>


				<!----- footer settings ----->
				<tr valign="top">
					<th scope="row">Footer Message <br>(You can use HTML content)</th>
					<td>
						<textarea name="dashboard_quick_link_widget_footer_notice" rows="4" cols="50" placeholder="Enter footer message"><?php echo esc_attr(get_option('dashboard_quick_link_widget_footer_notice')); ?></textarea>
					</td>
				</tr>

				<!----- color settings ----->
				<tr valign="top">
					<th scope="row">Header message background color</th>
					<td>
						<input type="text" class="color-picker" name="dashboard_quick_link_widget_mbox_bcolor" maxlength="7" value="<?php echo esc_attr(get_option('dashboard_quick_link_widget_mbox_bcolor')); ?>" placeholder="#ff0000" size="15" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Header message font color</th>
					<td>
						<input type="text" class="color-picker" name="dashboard_quick_link_widget_mbox_fcolor" maxlength="7" value="<?php echo esc_attr(get_option('dashboard_quick_link_widget_mbox_fcolor')); ?>" placeholder="#ff0000" size="15" />
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Fontawesome icon version</th>
					<td>
						<select name="dashboard_quick_link_widget_fa_version" value="<?php echo esc_attr(get_option('dashboard_quick_link_widget_fa_version')); ?>">
							<?php foreach (fontAwesomeCdns() as $key => $value) { ?>
								<option value="<?php echo $key; ?>" <?php if (get_option('dashboard_quick_link_widget_fa_version') != false && get_option('dashboard_quick_link_widget_fa_version') == $key) {
																		echo ' selected';
																	} ?>><?php echo $key; ?></option>';
							<?php } ?>
						</select>
					</td>
				</tr>

				<!----- auther note ----->
				<tr valign="top">
					<th scope="row"></th>
					<td>
						<div class="dashboard-link-widget-infobox">
							If you got any feedback regarding the plugin, please let us know <a href="http://hemthapa.com?ref=<?php echo $_SERVER['HTTP_HOST']; ?>&pl=dqlink" target="_blank">hemthapa.com</a></div>
					</td>
				</tr>

			</table>

			<?php submit_button(); ?>

			<script>
				jQuery(document).ready(function($) {
					$("input.color-picker").wpColorPicker();
				});
			</script>

		</form>

	</div>

	<?php }


/**
 * Render dashboard widget contents
 */
function renderDashboardWidgetContents()
{

	if (get_option('dashboard_quick_link_widget_header_notice') != false) { ?>

		<div class="dashboard-link-widget-notice" style="color:<?php if (get_option('dashboard_quick_link_widget_mbox_fcolor') != false) {
																	echo esc_attr(get_option('dashboard_quick_link_widget_mbox_fcolor'));
																} ?>; background-color:<?php if (get_option('dashboard_quick_link_widget_mbox_bcolor') != false) {
																							echo esc_attr(get_option('dashboard_quick_link_widget_mbox_bcolor'));
																						} ?>; ">
			<?php echo html_entity_decode(esc_attr(get_option('dashboard_quick_link_widget_header_notice'))); ?>
		</div>

	<?php }

	$links = esc_attr(get_option('dashboard_quick_link_widget_link_list'));
	$linklist = explodeLinks($links);

	if (isset($linklist) && sizeof($linklist) > 0) {
	?>
		<table class="dashboard-link-widget">
			<?php foreach ($linklist as $dqlw_links) { ?>

				<tr>

					<?php if (is_array($dqlw_links) && sizeof($dqlw_links) >= 3) { ?>
						<td scope="row">
							<?php if (!empty($dqlw_links[3])) {
								// for backward compatibility with older version of fontawesome icon, class 'fa' is by default added.
								echo '<span class="dashboard-link-widget-icon fa ' . $dqlw_links[3] . '" aria-hidden="true"></span>';
							} ?>
							<?php echo $dqlw_links[0]; ?>
						</td>
						<td><a href="<?php echo $dqlw_links[1][0]; ?>" class="button-primary dashboard-link-widget-button" target="<?php if (trim(esc_attr(get_option('dashboard_quick_link_widget_open_link'))) == 'on') {
																																		echo "_blank";
																																	} else {
																																		echo $dqlw_links[1][1];
																																	} ?>"><?php echo $dqlw_links[2]; ?></a></td>
					<?php } else { ?>
						<td scope="row" colspan="2"><?php echo html_entity_decode($dqlw_links); ?></td>
					<?php } ?>
				</tr>
			<?php } ?>
		</table>
	<?php } else {
		echo 'No link is added. <a href="' . admin_url('options-general.php?page=dashboard-quick-link-widget') . '">Click here</a> to add links';
	} ?>

	<div class="quick-dashboard-link-widget-footer"><?php echo html_entity_decode(esc_attr(get_option('dashboard_quick_link_widget_footer_notice'))); ?></div>
<?php

}

/**
 * Explode links text
 */
function explodeLinks($links)
{

	$linklist = explode("\n", $links);

	$linkArray = array();

	foreach ($linklist as $a) {

		$a = trim($a);

		// if empty line skip it
		if (empty($a)) {
			continue;
		}

		// check if html or text line is added with hash prefix on the line
		if (substr($a, 0, 1) == '#') {
			array_push($linkArray, str_replace('#', '', $a));
		} else {

			$newarrayline = explode('|', $a);
			$newarrayline = array_map('trim', $newarrayline);

			// further explode link item to check link target variable
			$newarrayline[1] = explodeLinkForTargetAttributes($newarrayline[1]);

			if (sizeof($newarrayline) < 3) {
				continue;
			}
			array_push($linkArray, $newarrayline);
		}
	}

	return $linkArray;
}


/**
 * Explode link text to determine link target attributes
 */
function explodeLinkForTargetAttributes($linkText)
{

	// remove doublice whitespaces
	$linkText = str_replace("  ", " ", trim($linkText));

	$linkAttributes = explode(" ", $linkText);

	if (sizeof($linkAttributes) == 1) {
		return array($linkAttributes[0], '_self');
	}

	if ($linkAttributes[1] == 'newtab') {
		return array($linkAttributes[0], '_blank');
	} else {
		return array($linkAttributes[0], '_self');
	}
}


/**
 * Determine fontawesome cdn
 */
function loadFontAwesomeCdns()
{

	$cdnList = fontAwesomeCdns();

	// for backward compatibility default version is set as 4.x
	$version = '4.x';

	$fa_version = get_option('dashboard_quick_link_widget_fa_version');
	if ($fa_version != false && array_key_exists($fa_version, $cdnList)) {
		$version = $fa_version;
	}

	return $cdnList[$version];
}

/**
 * fontawesome version and CDN pair
 */
function fontAwesomeCdns()
{
	return [
		'4.x' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
		'5.x' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css',
		'6.x' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css',
	];
}
