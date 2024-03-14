<?php
/*
Plugin Name: Kahi's WP Lite
Plugin URI: http://kahi.cz/wordpress/wp-lite-plugin/
Description: Make WordPress look thin.
Author: Peter Kahoun
Version: 0.9
Author URI: http://kahi.cz
*/
class kwplite {
	const DEV = false;

	// Descr: full name. used on options-page, ...
	static $full_name = 'Kahi\'s WP Lite';

	// Descr: short name. used in menu-item name, ...
	static $short_name = 'WP Lite';

	// Descr: abbreviation. used in textdomain, ...
	// Descr: must be same as the name of the class
	static $abbr = 'kwplite';

	// Descr: path to this this file
	// filled automatically
	static $dir_name;

	// Descr: settings: names => default values
	// Descr: in db are these settings prefixed with abbr_
	// Required if using self::$settings!
	static $settings = array (
		'menuitems' => '',
		'elements_to_hide' => array(),
		'custom_css' => '',
		'userlevel' => false,
	);

	// to store some stuff in original shape ($menu, $submenu)
	static $remember = array();

	/**
	 * Selectors and descriptions of all hide-able elements
	 * @uses apply_filters() 'kwplite_selectors'
	 *
	 * @var array
	 **/
	static $selectors = array(


'General' => array(

	'header-logo' => array('#header-logo', 'Header: WordPress logo'),
	'site-visit-button' => array('#site-visit-button', 'Header: Visit site button'), // not since 3.1
	'favorite-actions' => array('#favorite-actions', 'Header: Favourite actions'),
	'turbo' => array('.turbo-nag', 'Header: Turbo link'), // not since 3.1
	'plugins-number' => array('#adminmenu .update-plugins', 'Menu: Plugins number'),
	'separator1' => '',
	'tab-settings' => array('#show-settings-link', 'Tab: Settings'),
	'tab-help' => array('#contextual-help-link-wrap', 'Tab: Help'),
	'update-nag' => array('#update-nag, .update-nag', 'Update possibility message'),
	'separator2' => '',
	'footer' => array('#footer', 'Footer'),
	'footer-by' => array('#footer-left', 'Footer: links (left part)'),
	'footer-version' => array('#footer-upgrade', 'Footer: version info (right part)'),
),

'Dashboard' => array(
	'right-now' => array('#dashboard_right_now,label[for="dashboard_right_now-hide"]', 'Right now'),
	'right-now-comments' => array('#dashboard_right_now .table_discussion', 'Right now: Discussion'),
	'right-now-theme' => array('.versions > p', 'Right now: Theme info'),
	'right-now-version' => array('#wp-version-message', 'Right now: Version info'),
	'recent-comments' => array('#dashboard_recent_comments,label[for="dashboard_recent_comments-hide"]', 'Recent comments'),
	'incoming-links' => array('#dashboard_incoming_links,label[for="dashboard_incoming_links-hide"]', 'Incoming links'),
	'plugins' => array('#dashboard_plugins,label[for="dashboard_plugins-hide"]', 'Recommended plugins'),
	'quickpress' => array('#dashboard_quick_press,label[for="dashboard_quick_press-hide"]', 'QuickPress'),
	'drafts' => array('#dashboard_recent_drafts,label[for="dashboard_recent_drafts-hide"]', 'Recent drafts'),
	'news1' => array('#dashboard_primary,label[for="dashboard_primary-hide"]', 'WordPress development blog'),
	'news2' => array('#dashboard_secondary,label[for="dashboard_secondary-hide"]', 'WordPress other news'),
),
		
'Post' => array(
	'slug' => array('#edit-slug-box,label[for="slugdiv-hide"]', 'Slug (URL)'),
	'separator' => '',
	'media' => array('#media-buttons', 'Media buttons'),
	'media-add_image' => array('#add_image', 'Media buttons: add image'),
	'media-add_video' => array('#add_video', 'Media buttons: add video'),
	'media-add_audio' => array('#add_audio', 'Media buttons: add audio'),
	'media-add_media' => array('#add_media', 'Media buttons: add media'),
	'separator1' => '',
	'content'  => array('#postdivrich', 'Content'),
	'content-tabs' => array('#editor-toolbar > a', 'Content: Visual/HTML tabs'),
	'content-footer1' => array('tr.mceLast', 'Content: Visual editor footer 1 ("Path")'),
	'content-footer2' => array('table#post-status-info', 'Content: Visual editor footer 2'),
	'content-footer2a' => array('td#wp-word-count', 'Content: Visual editor footer 2: word count'),
	'content-footer2b' => array('td.autosave-info', 'Content: Visual editor footer 2: autosave info'),
	'separator2' => '',
	'box-excerpt' => array('#postexcerpt,label[for="postexcerpt-hide"]', 'Excerpt'),
	'box-comments-management' => array('#commentsdiv,label[for="commentsdiv-hide"]', 'Comments management'),
	'box-comments' => array('#commentstatusdiv,label[for="commentstatusdiv-hide"]', 'Comments status'),
	'box-trackbacks' => array('#trackbacksdiv,label[for="trackbacksdiv-hide"]', 'Trackbacks'),
	'box-customfields' => array('#postcustom,label[for="postcustom-hide"]', 'Custom fields'),
	'box-formatdiv'  => array('#formatdiv,label[for="formatdiv-hide"]', 'Format'),
	'box-categories'  => array('#categorydiv,label[for="categorydiv-hide"]', 'Categories'),
	'box-tags' => array('#tagsdiv-post_tag,label[for="tagsdiv-post_tag-hide"]', 'Tags'),
	'box-author' => array('#authordiv,label[for="authordiv-hide"],#pageauthordiv,label[for="pageauthordiv-hide"]', 'Author'),
	'box-revisions' => array('#revisionsdiv', 'Revisions'),
	'box-postimage' => array('#postimagediv', 'Post image'),
	'separator3' => '',
	'pub-visibility'  => array('#visibility', 'Publishing: privacy/visibility'),
	'pub-curtime' => array('.curtime', 'Publishing: date'),
	'separator4' => '',
	'pageparentdiv' => array('#pageparentdiv', 'Page details (parent page, template and order)'),
),
// @maybe todo add boxes added by plugins - see More Fields plugin - search $wp_meta_boxes in more-fields-manage-pages.php
// @todo remember - add selectors for quickedit
// @maybe submit patch to wp core - add ids for sub-fields in #pageparentdiv


'Links' => array(

	'name-descr' => array('#namediv p', 'Name: note'),
	'address-descr' => array('#addressdiv p', 'Address: note'),
	'separator1' => '',
	'description' => array('#descriptiondiv', 'Description'),
	'description-descr' => array('#descriptiondiv p', 'Description: note'),
	'separator2' => '',
	'categories' => array('#linkcategorydiv', 'Categories'),
	'target' => array('#linktargetdiv', 'Target'),
	'xfn' => array('#linkxfndiv', 'XFN (relations)'),
	'advanced' => array('#linkadvanceddiv', 'Advanced'),
	'separator3' => '',
	'privacy' => array('Privacy #misc-publishing-actions', 'Privacy'),
),

'Media, images & galleries' => array(
	// @todo test !!!
	'media_tab-type' => array('#media-upload #tab-type', 'Tab 1: upload'),
	'media_tab-type_url' => array('#media-upload #tab-type_url', 'Tab 2: insert from URL'),
	'media_tab-gallery' => array('#media-upload #tab-gallery', 'Tab 3: post\'s attachments'),
	'media_tab-library' => array('#media-upload #tab-library', 'Media tab 4: media library'),
	'separator1' => '',
	'media_item_post_title' => array('#media-upload .post_title', 'Title'),
	'media_item_image_alt' => array('#media-upload .image_alt, #media-single-form .image_alt', 'Alternate Text'),
	'media_item_post_excerpt' => array('#media-upload .post_excerpt, #media-single-form .post_excerpt', 'Caption'),
	'media_item_post_content' => array('#media-upload .post_content, #media-single-form .post_content', 'Description'),
	'media_item_url' => array('#media-upload .url', 'Link URL'),
	'media_item_url_button_urlpost' => array('#media-upload .button.urlpost', 'Link URL: "Post URL" button'),
	'media_item_url_help' => array('#media-upload .url .help', 'Link URL: Help note'),
	'media_item_align' => array('#media-upload .align', 'Alignment'),
	'media_item_image-size' => array('#media-upload .image-size', 'Size'),

 	'separator2' => '',	
	
	'media_gallery' => array('#media-upload #gallery-settings,	#gallery-settings > *,', 'Media: Gallery'),

	
	
	'separator3' => '',

#sort-buttons

	'media_search'  => array('#media-upload form#filter', 'Media search'),
	
	
	
	
	
	// @todo Edit Comments (spam marking and folder)
	// @todo Edit Media (Alternate text, Description, Caption)
)
);



	/**
	 * Initialization - filling main variables, preparing data, hooking into WP. Constructor replacement.
	 * 
	 * @uses apply_filters('kwplite_selectors_init')
	 */
	public static function Init () {
		if (self::DEV) error_reporting(E_ALL);

		// set self::$dir_name
		// example: my-plugin
		$t = str_replace('\\', '/', dirname(__FILE__));
		self::$dir_name = trim(substr($t, strpos($t, '/plugins/')+9), '/');

		// load translation
		// @todo: generate .pot (very low priority)
		// load_plugin_textdomain(self::$abbr, 'wp-content/plugins/' . self::$dir_name . '/languages/');

		// prepare settings
		self::prepareSettings();

		// hooking
		register_uninstall_hook(__FILE__, array(self::$abbr, 'uninstall'));
		add_action('admin_init', array (self::$abbr, 'admin_init'));
		add_action('admin_head', array (self::$abbr, 'admin_head'));
		add_action('admin_menu', array (self::$abbr, 'admin_menu'));
		add_filter ('admin_body_class', array(__CLASS__,'admin_body_class'));
		// hookable. $selectors array is modifyable
		self::$selectors = apply_filters('kwplite_selectors_init', self::$selectors);
	}


	// ====================  WP hooked functions  ====================

	// Hook: Action: admin_init
	// fires custom hooks
	// modifies global variables $menu and $submenu
	public static function admin_init ($content) {

		// fire custom hooks
		self::$selectors = apply_filters('kwplite_selectors', self::$selectors);



		// modify global variables $menu and $submenu
		global $menu, $submenu;
		if (!isset($menu) OR !isset($submenu)) return;

		// backup original content of menu (will be needed on options-page)
		// @maybe rewrite: don't modify $menu, just the menu-output cycle (possible? simple?)
		self::$remember['menu'] = $menu;
		self::$remember['submenu'] = $submenu;


		// maybe terminate functíon (if user-level restriction applies)
		// @maybe fix DRY
		global $current_user;
		if (self::$settings['userlevel'] AND $current_user->user_level >= self::$settings['userlevel'])
			return;

		// remove hidden items from $menu
		foreach ($menu as $key => $menuitem) {
			
			// if (array_key_exists('1'.md5($menuitem[2]), (self::$settings['menuitems'])) {
			if (isset(self::$settings['menuitems']['1'.md5($menuitem[2])])) {
				unset($menu[$key]);
				continue;
			}
		}

		// remove hidden items from $submenu (on both levels)
		foreach ($submenu as $parent_id => $items) {
			if (isset(self::$settings['menuitems']['1'.md5($parent_id)])) {
				unset($submenu[$parent_id]);
				continue;
			} else {
				foreach ($items as $id => $menuitem) {
					if (isset(self::$settings['menuitems']['2'.md5($menuitem[2])])) {
						unset($submenu[$parent_id][$id]);
						continue;
					}
				}
			}
		}

	}


	// Hook: Special: admin_menu
	// Descr: adds own item into menu in administration
	public static function admin_menu () {

		add_submenu_page( // for add_menu_page - skip first parameter
			'options-general.php',
			$page_title = self::$short_name,
			$menu_title = self::$short_name,
			$access_level = 'level_10',
			$file = __FILE__,
			$function = array (self::$abbr, 'adminPage')
			);

	}


	/**
	 * WP Hook (action): admin_head. Outputs my CSS depending on settings.
	 */
	public static function admin_head () {

?>

<!-- by plugin: <?php echo self::$full_name; ?> -->
<style type="text/css">

	/* options-page interface */

	#kwplite h3 {
		font-family: Georgia, serif; font-size: 20px; font-weight: normal;
	}

	#kwplite div.col {
		padding:20px;
		border: 1px solid #ccc;
		-moz-border-radius:    7px; 
		-webkit-border-radius: 7px; 
		border-radius:         7px; 
	}
	
	#kwplite div.col .col-content.tall {
		-moz-column-count: 3;
		-moz-column-gap: 20px;
		-webkit-column-count: 3;
		-webkit-column-gap: 20px;
		column-count: 3;
		column-gap: 20px;
	}

		#kwplite div.col .col-content {
			overflow-y: auto;
			max-height: 520px;
		}

			#kwplite div.col h3 {
				margin-top:0;
			}

			#kwplite ul li.separated {
				margin-top:1.5em;
			}

			#kwplite ul ul {
				margin-left:1.7em;
			}


	#kwplite p.submit {
		clear: both; padding-top:30px;
	}

	#kwplite .cleaner {
		clear:both;
	}

	/* tabs */

	#kwplite nav.tabs {
		display: block; margin: 0 14px;
	}
	
	#kwplite nav.tabs a {
		float: left; margin-right: 3px; position: relative; top: 0px; 
		padding: 5px 15px;
		border: 1px solid #ccc; border-bottom-color: #f7f7f7; 
		-moz-border-radius:    7px 7px 0 0; 
		-webkit-border-radius: 7px 7px 0 0; 
		border-radius:         7px 7px 0 0; 
		background-color: #f4f4f4;
		text-decoration: none;
	}
	
	#kwplite nav.tabs a.active {
		top: 1px; 
		background-color: transparent;
		color: #333;
	}
	#kwplite nav.tabs a:hover {
		color: #333;
	}

	/* post-editing elements hiding xxx */

<?php

	global $current_user;

	if ((!self::$settings['userlevel']) OR (self::$settings['userlevel'] AND $current_user->user_level < self::$settings['userlevel'])) {

		if (isset(self::$settings['elements_to_hide'])) {
			foreach (self::$settings['elements_to_hide'] as $s_group_name => $s_group_data) {
				if (is_array($s_group_data)) {
					foreach ($s_group_data as $e_id => $e_on) {
						if (isset(self::$selectors[$s_group_name][$e_id])) {
							echo self::$selectors[$s_group_name][$e_id][0] . ',';
						}
					}
				}
			}
		}

	} ?> #non_ex_ist_ing {display:none;}


	/* custom css */

<?php
	if ((!self::$settings['userlevel']) OR (self::$settings['userlevel'] AND $current_user->user_level < self::$settings['userlevel'])) {
		echo self::$settings['custom_css'];
	}
?>

</style>

<script type="text/javascript">
jQuery(document).ready(function(){

	// #reusable #tabs 2010.12.12
	jQuery('#kwplite nav.tabs a')
	.each(function(i){
		
		if (i > 0)
			jQuery(jQuery(this).attr('href')).hide();
		
	})
	.click(function(){
		
		// clicked = active
		if (jQuery(this).is('.active'))
			return false;
		
		// show content
		jQuery(jQuery(this).parents('nav').find('.active').attr('href')).hide();
		jQuery(jQuery(this).attr('href')).hide().removeClass('hidden').fadeIn('fast');
		
		// mark tab
		jQuery(this).parents('nav').find('.active').removeClass('active');
		jQuery(this).addClass('active');

		return false;
	});
	
});
		
</script>
<?php
	}


	/**
	 * WP Hook: admin_body_class
	 * Modifies body class by adding class-names reflecting currently logged user's level. Usage: Custom CSS rules applied only on specific user-groups.
	 **/
	public function admin_body_class ($in) {
	 	$current_user = wp_get_current_user();
		
		for ($i = 1; $i<11; $i++)
			if ($i < $current_user->user_level)
				$in .= ' level_gt_'.$i;
			elseif ($i == $current_user->user_level)
				$in .= ' level_'.$i;
			elseif ($i > $current_user->user_level)
				$in .= ' level_lt_'.$i;
			
		return $in;
	}

	// ====================  WP administration pages  ====================

	/**
	 * Requires own admin-page (plugin's settings)
	 * @return void
	 */
	public static function adminPage () {
		require_once 'admin-page.php';
	}


	// ====================  WP-general code  ====================

	/**
	 * Loads settings from db (wp_options) and stores them to self::$settings[setting_name_without_plugin_prefix]
	 * Settings-names are in db prefixed with "{self::$abbr}_", keys in $settings aren't. Very reusable.
	 * @see self::$settings
	 * @return void
	 */
	public static function prepareSettings () {

		foreach (self::$settings as $name => $default_value) {
			if (false !== ($option = get_option(self::$abbr . '_' . $name))) {
				self::$settings[$name] = $option;
			} else {
				// do nothing, let there be the default value
			}
		}

		// self::debug(self::$settings);

	}


	/**
	 * WP Hook: Uninstallation. Removes all plugin's settings. Very reusable.
	 * @return void
	 */
	public static function uninstall () {
		foreach (self::$settings as $name => $value) {
			delete_option(self::$abbr.'_'.$name);
		}
	}


	/**
	 * Outputs content given as first parameter. Enhanced replacement for var_dump().
	 * @param mixed Variable to output
	 * @param string (Optional) variable description
	 * @return void
	 */
	public static function debug($var, $descr = false) {

		if ($descr) echo '<p style="background:#666; color:#fff"><b>'.$descr.':</b></p>';

		echo '<pre style="max-height:300px; overflow-y:auto">'.htmlSpecialChars(var_export($var, true)).'</pre>';

	}


} // end of class


// ====================  Initialize the plugin  ====================
if (is_admin())
	kwplite::Init();