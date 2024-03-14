<?php

/*
Plugin Name: iRobots.txt SEO
Plugin URI: http://markbeljaars.com/plugins/irobottxt-seo/
Description: iRobots.txt SEO is a SEO optimized, secure and customizable robots.txt virtual file creator.
Version: 1.1.2
Author: Mark Beljaars
Author URI: http://markbeljaars.com
*/

/*
Copyright (C) 2010 Mark Beljaars, markbeljaars.com

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see http://www.gnu.org/licenses/.
*/

define('IRSEO_VERSION', '1.1.2'); // used for upgrade options and display purposes
define('IRSEO_PLUGIN_PATH', 'wp-content/plugins/' . basename(dirname(__FILE__)));
define('IRSEO_SENTINEL', 'iRobots.txt SEO'); // used to identify virtuL irseo file

// hooks required for all users
add_action('plugins_loaded','irseo_loaded_hook'); // set defaults on initial load
add_action('init', 'irseo_display_hook'); // serve virtual text file

// the following hooks are only required to be loaded if the current user is a site administrator
if ( is_admin() ) {
	add_action('admin_menu', 'irseo_menu_hook'); // insert IRSEO admin settings into the menu
	add_filter('plugin_action_links','irseo_filter_settings_option', 10, 2); // add settings in plugin page
	register_uninstall_hook(__FILE__, 'irseo_uninstall_hook'); // remove options on uninstall
}

/************************
*** HOOKS AND FILTERS ***
************************/

function irseo_loaded_hook() {

	// this array defines default settings for all options
    global $irseo_defaults;
    $irseo_defaults = array(
		'use_strict' => '',
		'allow_sitemap' => 'checked',
		'disallow_dupe' => '',
		'allow_adsense' => 'checked',
		'inhib_sys' => 'checked',
		'inhib_ia' => '',
		'inhib_img' => '',
		'inhib_dugg' => 'checked',
		'enable_edit' => '',
		'adv_config' => '',
		'freeform_text' => ''
		);
		
	// Creates an option array with default settings. If the option array already exists, the following line
	// exits with no action. If new options are added to the option array, the existing options array must
	// be manipulated by the check_upgrade() function to ensure the correct defaults are loaded for the new 
	// options.
	add_option('irseo_options', $irseo_defaults);
	
	// this function ensures that no existing options are lost during the upgrade process
	irseo_check_upgrade();
}

// Executes before the files are deleted when the plugin is uninstalled. Note that this function does not run
// if the plugin is de-activated.
function irseo_uninstall_hook() {
	// play nice and delete all options from the wp_options database
	delete_option('irseo_options');
	delete_option('irseo_version');
}

// Executes when the admin menu is being displayed.
function irseo_menu_hook() {
	// add settings to the admin menu
	add_options_page('iRobots.txt SEO', 'iRobots.txt SEO', 8, __FILE__, 'irseo_plugin_options');
}

// Executes each time a plugin links are displayed in the admin plugin page.
function irseo_filter_settings_option($links, $file) {
	// only add settings link to the IRSEO plugin
	if ($file == plugin_basename(__FILE__)) {

		// load text domain to allow "settings" text to be translatable
		load_plugin_textdomain('irseo', constant('IRSEO_PLUGIN_PATH'), basename(dirname(__FILE__)));

		// add settings link to the start of the plugin links
		$settings_link = '<a href="options-general.php?page=irobotstxt-seo/irobotstxt-seo.php">' 
							. __('Settings', 'irseo') . '</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}

// Executes before any page is displayed.
function irseo_display_hook() {
	// get current URL and robots URL - strip www from both
	$selfURL = strtolower(str_replace('//www.', '//', irseo_selfURL()));
	$robotURL = strtolower(str_replace('//www.', '//', get_bloginfo('url') . '/robots.txt'));
	
	// exit if not the robots url 
	if($selfURL != $robotURL)
		return;
	
	// create robots.txt file 
	header('Content-type: text/plain');
	echo irseo_construct_robots(get_option('irseo_options'));
	
	// don't allow wordpress to continue processing this page
	die;
}

/********************************
*** BUILD THE ROBOTS.TXT FILE ***
********************************/

function irseo_construct_robots(&$irseo_options) {

	// return free form text if enable edit option checked
	if($irseo_options['enable_edit']) {
		return $irseo_options['freeform_text'];
	}

	// text domain loaded here to allow comments to be translated
	load_plugin_textdomain('irseo', constant('IRSEO_PLUGIN_PATH'), basename(dirname(__FILE__)));

	// create virtual sentinel
	$content = '#######################################################'. PHP_EOL
		  		. '# ' . constant('IRSEO_SENTINEL') . PHP_EOL;

	// inihibit wordpress system files and directories
	if($irseo_options['inhib_sys']) {
		$content .= PHP_EOL . '# All Bots' . PHP_EOL
					. 'User-agent: *' . PHP_EOL
					. 'Disallow: /cgi-bin' . PHP_EOL
					. 'Disallow: /wp-admin/' . PHP_EOL
					. 'Disallow: /wp-includes/' . PHP_EOL
					. 'Disallow: /wp-content/' . PHP_EOL
					. 'Disallow: /readme.html' . PHP_EOL
					. 'Disallow: /license.txt' . PHP_EOL
					. 'Disallow: /search/' . PHP_EOL;
		if(!$irseo_options['use_strict']) {
			$content .= 'Disallow: /*?' . PHP_EOL
						. 'Disallow: /*.php$' . PHP_EOL
						. 'Disallow: /*.js$' . PHP_EOL
						. 'Disallow: /*.inc$' . PHP_EOL
						. 'Disallow: /*.css$' . PHP_EOL
						. 'Disallow: /*.gz$' . PHP_EOL
						. 'Disallow: /*.wmv$' . PHP_EOL
						. 'Disallow: /*.cgi$' . PHP_EOL
						. 'Disallow: /*.xhtml$' . PHP_EOL
						. 'Disallow: /*rurl=*' . PHP_EOL
						. 'Allow: /sitemap.xml.gz$' . PHP_EOL
						. 'Allow: /wp-content/uploads/' . PHP_EOL;
		}
	}		
	
	// disallow duplicate content	
	if($irseo_options['disallow_dupe']) {
		$content .= 'Disallow: /tag/' . PHP_EOL
					. 'Disallow: /category/' . PHP_EOL
					. ($irseo_options['use_strict'] ? '' : 'Disallow: /*?*' . PHP_EOL);
	}

	// inhibit internet archive
	if($irseo_options['inhib_ia']) {
		$content .= PHP_EOL . '# Internet Archiver Wayback Machine' . PHP_EOL
					. 'User-agent: ia_archiver' . PHP_EOL
					. 'Disallow: /' . PHP_EOL;
	}

	// inhibit image bots
	if($irseo_options['inhib_img']) {
		$content .= PHP_EOL .'# Google Image' . PHP_EOL
					. 'User-agent: Googlebot-Image' . PHP_EOL
					. 'Disallow: /' . PHP_EOL
					. PHP_EOL . '# Yahoo Multimedia' . PHP_EOL
					. 'User-agent: Yahoo-MMCrawler' . PHP_EOL
					. 'Disallow: /' . PHP_EOL
					. PHP_EOL . '# Bing Picture Search' . PHP_EOL
					. 'User-agent: psbot' . PHP_EOL
					. 'Disallow: /' . PHP_EOL;
	}

	// inhibit dugg mirror
	if($irseo_options['inhib_dugg']) {
		$content .= PHP_EOL .'# Dugg Mirror' . PHP_EOL
					. 'User-agent: duggmirror' . PHP_EOL
					. 'Disallow: /' . PHP_EOL;
	}

	// allow full access to adsense
	if($irseo_options['allow_adsense']) {
		$content .= PHP_EOL .'# Google AdSense' . PHP_EOL
					. 'User-agent: Mediapartners-Google' . PHP_EOL
					. 'Disallow: ' . PHP_EOL;
		if(!$irseo_options['use_strict']) {
			$content .= 'Allow: /' . PHP_EOL;
		}
	}
	
	// include any advanced configuration options
	$adv_options = $irseo_options['adv_config'];
	if($adv_options && is_array($adv_options)) {
		// sort so that user agents are grouped
		sort($adv_options);
		// add each advanced config option
		$content .= PHP_EOL .'# Custom Records';
		$last_useragent = '';
		foreach($adv_options as $option => $ua) {
			// ignore the option if strict robots and option is not strict
			if(!$irseo_options['use_strict'] || ($ua['directive'] != 'Allow:'
						&& ($ua['userAgent'] == "*" || strpos($ua['userAgent'], '*') == FALSE) 
						&& strpos($ua['action'], '*') == FALSE && strpos($ua['action'], '$') == FALSE)) {
						
				// only display user agent if it has changed
				if($ua['userAgent'] != $last_useragent) {
					$content .= PHP_EOL . 'User-agent: '. $ua['userAgent'] . PHP_EOL;
					$last_useragent = $ua['userAgent'];
				}
				$content .= $ua['directive'] . ' ' . $ua['action'] . PHP_EOL;
			}
		}
	}
	
	// include the site map
	if($irseo_options['allow_sitemap']) {
		$content .= PHP_EOL . '# Sitemap' . PHP_EOL;
		// sitemaps not part of strict definition
		if($irseo_options['use_strict']) {
			$content .= '# ' . __('Sitemaps not allowed if strict definition selected.', 'irseo');
		}
		// use compressed sitemap by default
		elseif(irseo_file_exists(get_bloginfo('url') . '/sitemap.xml.gz')) {
			$content .= 'Sitemap: ' . get_bloginfo('url') . '/sitemap.xml.gz';
		}
		// or look for use the uncompressed site map
		elseif(irseo_file_exists(get_bloginfo('url') . '/sitemap.xml')) {
			$content .= 'Sitemap: ' . get_bloginfo('url') . '/sitemap.xml';
		}
		// if sitemap could not be found, display a warning
		else {
			$content .= __(
'# YOUR WEBSITE DOES NOT HAVE A SITEMAP! Please consider
# installing an automated sitemap generator such as
# Google XML Sitemaps -
# http://www.arnebrachhold.de/redir/sitemap-home/', 'irseo');
		}
		$content .= PHP_EOL;
	}
	
	// include the sitemap footer and author credit
	$content .= PHP_EOL . '#######################################################' . PHP_EOL
			   . '#' . PHP_EOL
			   . '# Robots.txt ' . __('file generated by', 'irseo') . ' iRobots.txt SEO v' . constant('IRSEO_VERSION') . PHP_EOL
			   . '# by Mark Beljaars' . PHP_EOL
			   . '#' . PHP_EOL
			   . '#  _ _  _  _ |  |_  _ |. _  _  _ _  _ _  _ _ ' . PHP_EOL
			   . '# | | |(_||  |< |_)(/_||(_|(_|| _\.(_(_)| | |'. PHP_EOL
			   . '#                     _|'. PHP_EOL
			   . '# http://markbeljaars.com/plugins/irobotstxt-seo'. PHP_EOL
			   . '#'. PHP_EOL
			   . '#######################################################'. PHP_EOL;

	// display a warning if strict sitemap not selected
	if(!$irseo_options['use_strict']) {			   
		$content .= '#'. PHP_EOL . __(
'# Note:
# The Allow directive and wildcards (*) in filenames are
# not standard robots.txt syntax, however they are
# supported by most search engines.', 'irseo') . PHP_EOL;
	}

	return $content;
}

/***********************
*** HELPER FUNCTIONS ***
***********************/

// Handle seamless upgrade from previous versions
function irseo_check_upgrade() {
	$version = get_option('irseo_version');
	// manipulate options to ensure the latest version is compatible with previous versions
	if ($version && $version != constant('IRSEO_VERSION')) {
	
		// retrieve the version prior to update and convert into a number
		$old_ver = explode('.', $version . '.0.0.0');
		$old_ver = $old_ver[0] * 1000000 + $old_ver[1] * 1000 + $old_ver[2];

		// copy individual settings into the new combined array
		if ($old_ver < 1001000) {
			$irseo_options = $GLOBALS['irseo_defaults'];
			foreach (array_keys($irseo_options) as $option) {
				// only update default value with values from options that actually exist
				$value = get_option('irseo_' . $option);
				if(delete_option('irseo_' . $option)) {
					$irseo_options[$option] = $value;
				}
			}
			update_option('irseo_options', $irseo_options);
		}
		
		// update version so this code doesn't run again
		update_option('irseo_version', constant('IRSEO_VERSION'));
	}
}	

// Detect non-irseo robots.txt file.
function irseo_physical_robots_exists() {
	// open robots.txt file and grab contents
	$fp = @fopen(get_bloginfo('url') . '/robots.txt', 'r');
	if($fp !== false) {
		$content =  fread($fp, 1024);
		fclose($fp);
		// return sentinel found status
		return strpos($content, constant('IRSEO_SENTINEL')) === false;
	}
	// no robots.txt file - this should not happen
	return false;
}

// Detect if a file exists.
function irseo_file_exists($url) {
	$fp = @fopen($url, 'r');
	if($fp) {
		fclose($fp);
	}
	return ($fp !== false);   
} 

// Retrieve actual URL of the current page.
function irseo_selfURL() {
	// this function by S.M. Saidur Rahman
	$s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER["HTTPS"] == 'on') ? 's' : '';	
	$protocol = irseo_strleft(strtolower($_SERVER['SERVER_PROTOCOL']), '/').$s;
	$port = ($_SERVER['SERVER_PORT'] == '80') ? '' : (':'.$_SERVER['SERVER_PORT']);
	return $protocol.'://'.$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}

// Return left part of a string up until the match string is found.
function irseo_strleft($s1, $s2) {
	// this function by S.M. Saidur Rahman
	$values = substr($s1, 0, strpos($s1, $s2));
	return  $values;
}

/*******************************
*** ADMINISTRATION FUNCTIONS ***
*******************************/

// Option section header.
function irseo_admin_section_start($heading_text, $heading_name, $is_hidden = '1') {

	// show section if was previously showing before page was updated
	echo '<input type="hidden" id="' . $heading_name . '_display" name="' 
		. $heading_name . '_display" value="' . $_POST[$heading_name . '_display'] . '">' . PHP_EOL;
	if($_POST[$heading_name . '_display']) {
		$is_hidden = $_POST[$heading_name . '_display'] == 'none';
	}

	// create option section
	echo PHP_EOL . '<div id="normal-sortables" class="meta-box-sortables">'
		. '<div class="postbox">'
		. '<div class="handlediv" title="' . __('Click to toggle!', 'irseo') . '" onclick="toggleVisibility(\'' . $heading_name . '\');">'
		. '<br /></div><h3 class="hndle" title="' . __('Click to toggle!', 'irseo') 
		. '" onclick="toggleVisibility(\'' . $heading_name . '\');"><span>' . $heading_text . '</span></h3>'
		. '<div class="inside" id="' . $heading_name . '" style="display:' . ($is_hidden ? 'none' : 'block') . ';">' . PHP_EOL;
}

// Option creator. This is an abreviated version of my normal option creator as I only need checkbox options
function irseo_admin_section_option(&$irseo_options, $option_text, $tooltip_text, $option_name, $disabled="", $onclick="") {

	// create checkbox
	echo '<p class="meta-options">'
		. '<label for="' . $option_name . '" class="selectit" ' . ($disabled ? 'style="color:grey;"' : '') . ' >'
		. '<input name="' . $option_name . '" type="checkbox" id="'. $option_name .'"' 
			. ($irseo_options[$option_name] ? ' checked="checked"' : '') 
			. ($disabled ? ' disabled="disabled"' : '')
			. ($onclick ? ' onClick="' . $onclick . '"' : '')
		. ' />'
		. '&nbsp;' . $option_text
		. '</label>' . PHP_EOL;

	// add tooltip text icon
	if($tooltip_text) {
		echo '<a title="' . __('Click here for help!', 'irseo') . '" onclick="toggleVisibility(\'' . $option_name . '_tip\');">'
			. '&nbsp;'
			. '<img border="0" style="cursor:pointer;vertical-align:text-top;" src="' 
				. get_bloginfo('url') . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/help.gif" />'
			. '</a>';
	}
	echo '</p>';
	
	// add tooltip text
	if($tooltip_text) {
		echo '<div id="' . $option_name . '_tip" style="display:none;margin-left:8px;color:#21759b;">'
			. $tooltip_text
			. '<br /></div>' . PHP_EOL;
	}
}

// Option section footer.
function irseo_admin_section_end() {
	echo '</div>' . PHP_EOL 
		. '</div>' . PHP_EOL 
		. '</div>' . PHP_EOL;
}

// Construct the admin settings page
function irseo_plugin_options() {

	// text domain loaded here to allow setting headers and tooltip text to be translated
	load_plugin_textdomain('irseo', constant('IRSEO_PLUGIN_PATH'), basename(dirname(__FILE__)));

	// retrieve current options
	$irseo_options = get_option('irseo_options');
	
	// add javascript to show and hide option sections and enable/disable free form editing
	echo '<script type="text/javascript">' . PHP_EOL 
		. '<!--' . PHP_EOL 
		. 'function toggleVisibility(id) { ' . PHP_EOL 
		. '   var e = document.getElementById(id);' . PHP_EOL 
		. '   if(e.style.display == "block")' . PHP_EOL 
		. '      e.style.display = "none";' . PHP_EOL 
		. '   else' . PHP_EOL 
		. '      e.style.display = "block";' . PHP_EOL 
		. '	document.getElementById(id.concat("_display")).value = e.style.display;' . PHP_EOL
		. '}' . PHP_EOL
		. 'function setButton() {' . PHP_EOL
		. '	but = document.forms[0].freeform_update;' . PHP_EOL
		. '	if(document.forms[0].enable_edit.checked) {' . PHP_EOL
		. '		but.disabled = false;' . PHP_EOL
		. '		but.style.color = "black";' . PHP_EOL
		. '      document.getElementById("content").readOnly = false;' . PHP_EOL
		. '	}' . PHP_EOL 
		. '	else {' . PHP_EOL
		. '		but.disabled = true;' . PHP_EOL
		. '		but.style.color="#cacaca";' . PHP_EOL
		. '      document.getElementById("content").readOnly = true;' . PHP_EOL
		. '	}' . PHP_EOL
		. '}' . PHP_EOL
		. '-->' . PHP_EOL
		. '</script>' . PHP_EOL 
		. PHP_EOL;
		
	// security checks
	if(is_admin()) {
	
		// restore defaults button pressed
		if (isset ($_POST['set_defaults']) && check_admin_referer('irseo-change-options-nonce')) {
			$irseo_options = $GLOBALS['irseo_defaults'];
			update_option('irseo_options', $irseo_options);
			echo '<div id="message" class="updated fade"><p><strong>' . __('Default options loaded!', 'irseo') . '</strong></p></div>';
		}

		// update button pressed
		elseif (isset ($_POST['info_update']) && check_admin_referer('irseo-change-options-nonce')) {
			// don't update if all options were previously disabled
			if(!($irseo_options['enable_edit'] && !$_POST['enable_edit'])) {
				// use the defaults array as a source for option names
				$irseo_options = $GLOBALS['irseo_defaults'];			
				foreach (array_keys($irseo_options) as $option) {
					// do not strip slashed from array options as this breaks the array 
					$irseo_options[$option] = (!is_array($_POST[$option]) ? stripslashes($_POST[$option]) : $_POST[$option]);
				}
			} else {
				$irseo_options['enable_edit'] = stripslashes((string) $_POST['enable_edit']);
			}
			update_option('irseo_options', $irseo_options);
			echo '<div id="message" class="updated fade"><p><strong>' . __('Configuration updated!', 'irseo') . '</strong></p></div>';
		}
	
		// delete advanced option button pressed
		elseif (isset ($_POST['adv_delete']) && check_admin_referer('irseo-change-options-nonce')) {
			$adv_options = $irseo_options['adv_config'];
			echo '<div id="message" class="updated fade">';
			foreach($_POST['adv_delete'] as $element => $value) {
				unset($adv_options[$element]);
				echo '<p><strong>(ID ' . $element . ') ' . __('Custom record deleted!', 'irseo') . '</strong></p>';
			}
			echo '</div>';
			$irseo_options['adv_config'] = $adv_options;
			update_option('irseo_options', $irseo_options);
		}
	
		// add advanced option button pressed
		elseif (isset ($_POST['adv_update']) && check_admin_referer('irseo-change-options-nonce')) {
			$adv_options = $irseo_options['adv_config'];
			if($_POST['adv_agent'] != '' && $_POST['adv_string'] != '') {
				$adv_options[] = array(
					"userAgent" => $_POST['adv_agent'],
					"directive" => $_POST['adv_directive'],
					"action" => $_POST['adv_string']);	
				end($adv_options);
				echo '<div id="message" class="updated fade"><p><strong>(ID ' . key($adv_options) . ') ' 
					. __('Custom record added!', 'irseo') . '</strong></p></div>';
				$irseo_options['adv_config'] = $adv_options;
				update_option('irseo_options', $irseo_options);
			}
			else {
				echo '<div id="message" class="updated fade"><p><strong>' . __('Failed to add custom record!', 'irseo') . '</strong></p></div>';
			}
		}
	
		// free form text update button pressed
		elseif (isset ($_POST['freeform_update']) && check_admin_referer('irseo-change-options-nonce')) {
			$irseo_options['enable_edit'] = stripslashes((string) $_POST['enable_edit']);
			$irseo_options['freeform_text'] = $_POST['robots_content'];
			echo '<div id="message" class="updated fade"><p><strong>' 
					. __('Robots.txt manual changes updated!', 'irseo') 
				. '</strong></p></div>';
			update_option('irseo_options', $irseo_options);
		}
	}
	
	// store current robots.txt file in free form text
	if(!$irseo_options['enable_edit']) {				
		$irseo_options['freeform_text'] = irseo_construct_robots($irseo_options);
	}
	
	// introduction
	echo '<div class="wrap">'
		. '<h2>iRobots.txt SEO v' . constant('IRSEO_VERSION') . '</h2>'
		. '<div id="poststuff" class="metabox-holder has-right-sidebar">' . PHP_EOL
		. '<form method="post" action="" name="dofollow">' . PHP_EOL;

	// display a warning if there is an existing robots.txt file
	if(irseo_physical_robots_exists()) {
		echo '<h4 style="text-align:center; color: #a00">' . __('WARNING: A ROBOTS.TXT FILE ALREADY EXISTS!', 'irseo') . '</h4><p>' . PHP_EOL 
			. __(
'iRobots.txt SEO creates a virtual robots.txt file and as such it cannot replace a physical one. Please ensure that a physical robots.txt file does not already exist in your site root directory.', 'irseo');

		// display an additional warning if XML sitemap generator exists
		if(class_exists('GoogleSitemapGeneratorLoader')) {
			echo ' ' . __(
'iRobots has also detected the XML Sitemap Generator plugin. This plugin creates a basic virtual robots.txt file that may conflict with IRSEO. Please ensure that the <em>Add sitemap URL to the virtual robots.txt file</em> option located in the <em>XML-Sitemap</em> administrator settings is de-selected.', 'irseo');
		}
		echo '</p><br />' . PHP_EOL;
	}

	// disable options if freeform editing is enabled
	$disops = $irseo_options['enable_edit'];	
	
	// general options
	irseo_admin_section_start(__('General Options', 'irseo'), 'genopts', '');
	irseo_admin_section_option($irseo_options, __('Use strict robots.txt standard definition', 'irseo'), __('The official <a href="http://www.robotstxt.org/robotstxt.html" target="_blank">robots.txt definition</a> specifically identifies which directories or files a search engine can <strong>not</strong> index and does not include any directives for detailing which files a search engine can index. Google has expanded the definition to include an <code>allow</code> directive and also allows wildcards in file names. Although not officially supported, the ammended standard is understood by most search engines.', 'irseo'), 'use_strict', $disops);
	irseo_admin_section_option($irseo_options, __('Automatically add the website sitemap to the robots.txt file', 'irseo'), __('Sitemaps inform search engines of your site structure and also allow you to estimate how often your pages will change. Obviously search engines find this sort of information beneficial. The sitemap protocol is defined <a href="http://www.sitemaps.org/protocol.php" target="_blank">here</a>. Sitemaps can be automatically produced by Wordpress plugins such as <a href="http://www.arnebrachhold.de/projects/wordpress-plugins/google-xml-sitemaps-generator/" target="_blank">Google XML Sitemaps Generator</a>.', 'irseo'), 'allow_sitemap', $disops);
	irseo_admin_section_option($irseo_options, __('Inhibit indexing of Wordpress system folders', 'irseo'), __('Wordpress system folders such as the plugin and content directories are not keyword optimized and therefore should not be indexed by a search engine. Further, indexing system folders may present a security risk.', 'irseo'), 'inhib_sys', $disops);
	irseo_admin_section_option($irseo_options, __('Do not allow duplicate content', 'irseo'), __('Wordpress has many ways of displaying the same post, including by tag, by category or by author. This appears to Google as multiple pages with the same content. It is debated that Google does not like sites with lots of duplicate content, but on the other hand it is also debated that Google likes sites with many pages. Use this option to inhibit or allow some duplicate content.', 'irseo'), 'disallow_dupe', $disops);
	irseo_admin_section_option($irseo_options, __('Allow Google Adsense to access entire site', 'irseo'), __('<a href="https://www.google.com/adsense/" target="blank_">Google Adsense</a> automatically determines which ads are relevant for your audience by crawling the contents of your site. Giving Adsense full access to your site may result in more targeted advertisements. Ignore this option if you have not implemented Adsense.', 'irseo'), 'allow_adsense', $disops);
	irseo_admin_section_option($irseo_options, __('Inhibit indexing by the Internet Archive', 'irseo'), __('The <a href="http://www.archive.org/index.php" target="_blank">Internet Archive</a> is a not-for-profit organization with aims to archive all information on the Internet at regular intervals. It is speculated that Google uses the Internet Archive to determine the age of a website to assist in defining a site\'s authority. Some SEO experts recommend that the Internet Archive be disabled from indexing young website. The Internet Archive also raises issues of document control (old versions of your posts may be archived), intellectual property rights and privacy.', 'irseo'), 'inhib_ia', $disops);
	irseo_admin_section_option($irseo_options, __('Inhibit image indexing', 'irseo'), __('You may wish to inhibit search engines from indexing your images if your images are copyright, have been dubiously obtained (they infringe copyright), are not related to your site or are not likely to generate traffic. Affiliate marketers may also find that images may generate untargeted traffic thus affecting a site\'s conversion ratio.', 'irseo'), 'inhib_img', $disops);
	irseo_admin_section_option($irseo_options, __('Inhibit indexing by the Dugg Mirror', 'irseo'), __('Duggmirror provides a mirror for the most popular stories on Digg.com. Sites are often overloaded by the amount of traffic Digg sends their way, causing the webpage to become unavailable. To alleviate the so-called "digg effect" Duggmirror hosts a mirror of the most popular stories making them available to Digg users. The problem is that Google may index the DuggMirror page before the source and inturn drive traffic from your site to the mirror.', 'irseo'), 'inhib_dugg', $disops);
	irseo_admin_section_end();

	// advanced configuration options
	irseo_admin_section_start(__('Advanced Configuration', 'irseo'), 'advops');
	echo '<div id="postcustomstuff">'
		. '<table id="list-table"><thead>'
		. '<tr>'
			. '<th class="left">' . __('User Agent', 'irseo') . '</th>'
			. '<th>' . __('Directive', 'irseo') . '</th>'
			. '<th>' . __('Action', 'irseo') . '</th>'
			. '<th>&nbsp;</th>'
		. '</tr></thead>'
		. '<tbody id="the-list" class="list:meta">';
	// display each advanced option
	$adv_options = $irseo_options['adv_config'];
	if($adv_options && is_array($adv_options)) {	
		foreach($adv_options as $option => $ua) {
			echo '<tr><td><p>' . $ua['userAgent'] . '</p></td>'
				. '<td><p>' . $ua['directive'] .'</p></td>'
				. '<td><p>' . $ua['action'] . '</p></td>'
				. '<td class="submit">'
				. '<input style="margin:1px 0 0 0;' 
					. ($disops ? 'color:#cacaca;' : '') . '" ' . ($disops ? 'disabled = "disabled"' : '') 
					. ' type="submit" name="adv_delete[' . $option . ']" value="' . __('Delete', 'irseo') . '" />' 
				. '</td></tr>';	
		}
	}
	// display advanced option entry fields, add button and tooltip text
	echo '</tbody></table>'
		. '<p><strong>' . __('Add a custom record:', 'irseo') . '</strong></p>'
		. '<p><table>'
		. '<tr>'
			. '<td class="left" style="width:100px;"><label for="adv_agent"><p>' . __('User Agent:', 'irseo') . '</p></label></td>'
			. '<td><input type="text" id="adv_agent" name="adv_agent" value="" /></td>'
		. '</tr>'
		. '<tr>'
			. '<td><select name="adv_directive">' 
			. '<option value="Disallow:" selected="selected">Disallow:</option>' 
			. '<option value="Allow:">Allow:</option></select></td>'
			. '<td><input type="text" id="adv_string" name="adv_string" value="" /></td>'
		. '</tr>'
		. '<tr>'
		. '<td colspan="2">'
		. '<input type="submit" style="width:auto;' 
			. ($disops ? 'color:#cacaca;' : '') . '" ' . ($disops ? 'disabled = "disabled"' : '') 
			. ' name="adv_update" value="' . __('Add Custom Record', 'irseo') . '" />' 
		. '</td>'
		. '</tr>'
		. '</table></p>' 
		. '</div>'
		. '<p>' . __(
'Custom records can be added or deleted from the robots.txt file using this form. A complete list of user agents can be found at <a href="http://www.user-agents.org/" target="_blank">http://www.user-agents.org/</a>. Examples of robot.txt directive strings (the text that goes after the <em>allow</em> or <em>disallow</em> directives) can be found at <a href="http://www.robotstxt.org/robotstxt.html" target="_blank">http://www.robotstxt.org/robotstxt.html</a>. Google\'s non-official extensions are described in detail in this <a href="http://googlewebmastercentral.blogspot.com/2008/06/improving-on-robots-exclusion-protocol.html" target="_blank">blog post</a>. Note that all <em>allow</em> directive records and directive strings including wildcard globbing will be ignored if <em>Use strict robots.txt standard definition</em> is selected.', 'irseo') 
		. '</p>';
	irseo_admin_section_end();

	// display current robot.txt file
	irseo_admin_section_start(__('View Robots.txt', 'irseo'), 'viewrobots');
	echo '<div id="editorcontainer">' 
		. '<textarea rows="15" cols="40" name="robots_content" id="content"' . ($irseo_options['enable_edit'] ? '' : ' readonly="readonly"'). '>'
			. $irseo_options['freeform_text']
		. '</textarea></div>'
		. '<div style="float:right;margin-top:3px;">' 
		. '<input type="submit" name="freeform_update" value="' . __('Update Manual Changes to Robots.txt', 'irseo') . ' "' 
		. ($irseo_options['enable_edit'] ? '' : 'disabled = "disabled" style="color:#cacaca"')
		. ' /> ' 
		. '</div>';

	// free form edit options
	irseo_admin_section_option($irseo_options, __('Enable free form editing', 'irseo'), __('Enables manual editing of the robots.txt file. Caution is required as a badly formed robots.txt file may seriously effect search engine rankings. Note that once free form editing is enabled, modification of the general and advanced configuration settings is inhibited. Further, when free form is disabled, any manual changes to the robots.txt file will be lost.', 'irseo') . '<br />', 'enable_edit', '', 'javascript:setButton()');
	irseo_admin_section_end();

	// display update and restore default buttons
	echo '<div>'
		. '<input type="submit" name="info_update" value="' . __('Update Options', 'irseo') . ' &raquo;" />'
		. '&nbsp;&nbsp'
		. '<input type="submit" style="background-Color:#E0FFFF;" '
		. 'onmouseover="this.style.backgroundColor=\'red\'" '
		. 'onmouseout="this.style.backgroundColor=\'#E0FFFF\'" '
		. 'onclick="return confirm(\'' . __('Click OK to reset to defaults. Any settings will be lost!', 'irseo') . '\');" '
		. 'name="set_defaults" value="' . __('Load Default Options', 'irseo') . ' &raquo;" />'
		. '</div>' . PHP_EOL;

	// acknowledgments
	echo PHP_EOL . '<h4>' . __('Acknowledgments', 'tocc') . '</h4>' . PHP_EOL
		. '<p>' . __('I wish to personally acknowledge the following people for their valuable contributions:', 'tocc') . '</p>' . PHP_EOL 
		. '<div class="inside">'  . PHP_EOL
		. '<a href="http://pc.de/">Marcis G.</a> - ' . __('Belorussian translation', 'irseo') . PHP_EOL
		. '</div>' . PHP_EOL;
		
	// create a nonce for security purposes
	wp_nonce_field('irseo-change-options-nonce');
			
	echo '</form></div></div>' . PHP_EOL;
}
?>
