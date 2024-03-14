<?php
/*
Plugin Name: WordPress Tweaks
Plugin URI: http://johnlamansky.com/wordpress/plugins/tweaks/
Description: Adds a variety of useful options and settings, accessible at <a href="options-general.php?page=wordpress-tweaks">Settings &rarr; Tweaks</a>.
Version: 2.2
Author: John Lamansky
Author URI: http://johnlamansky.com/wordpress/
*/

/*
Copyright (c) 2008-2014 John Lamansky

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if (!defined('ABSPATH')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	die();
}

include dirname(__FILE__).'/class-jlwp-env.php';
if (!wpt_env_ok('WordPress Tweaks', 'wordpress-tweaks', __FILE__, '4.3', '3.1')) return;
global $jl_wpt;
$jl_wpt = new JL_WordPress_Tweaks();

class JL_WordPress_Tweaks {
	
	var $version = '2.2';
	
	var $plugin_dir_url = '';
	
	var $tweaks = array();
	
	function __construct() {
		$this->plugin_dir_url = trailingslashit(plugins_url(dirname(plugin_basename(__FILE__))));
		$this->tweaks = get_option('jl_wpt_tweaks', array('wpt_footer_count' => true));
		
		if ($this->te('admin_disable_dashboard')) {
			add_action('admin_menu', array(&$this, 'admin_disable_dashboard_menu'));
			add_action('load-index.php', array(&$this, 'admin_disable_dashboard_page'));
		}
		if ($this->te('archive_excerpts'))
			add_filter('the_content', array(&$this, 'archive_excerpts'), 15);
		if ($this->te('comment_author_dofollow'))
			add_filter('get_comment_author_link', array(&$this, 'remove_nofollow'));
		if ($this->te('comment_body_dofollow'))
			add_filter('get_comment_text', array(&$this, 'remove_nofollow'));
		if ($this->te('comment_reverse'))
			add_filter('comments_array', 'array_reverse');
		if ($this->te('comment_targetblank'))
			add_action('wp_enqueue_scripts', array(&$this, 'comment_targetblank'));
		if ($this->te('core_update_level')) {
			if ('none' == $this->tweaks['core_update_level']) {
				add_filter('auto_update_core', '__return_false', 999);
			} elseif ('minor' == $this->tweaks['core_update_level'] || 'major' == $this->tweaks['core_update_level']) {
				add_filter('automatic_updates_is_vcs_checkout', '__return_false', 999);
				add_filter('auto_update_core', '__return_true', 999);
				add_filter('allow_minor_auto_core_updates', '__return_true', 999);
				
				if ('minor' == $this->tweaks['core_update_level'])
					add_filter('allow_major_auto_core_updates', '__return_false', 999);
				else
					add_filter('allow_major_auto_core_updates', '__return_true', 999);
			}
		}
		if ($this->te('disable_admin_bar')) {
			add_action('admin_enqueue_scripts', array(&$this, 'disable_admin_bar'));
			add_filter('show_admin_bar', '__return_false');
		}
		if ($this->te('disable_admin_footer_text'))
			add_filter('admin_footer_text', '__return_false');
		if ($this->te('jpeg_qualitly'))
			add_filter('jpeg_quality', array(&$this, 'jpeg_quality'));
		if ($this->te('media_upload_default_tab'))
			add_filter('media_upload_default_tab', array(&$this, 'media_upload_default_tab'));
		if ($this->te('ms_update_services_enable') && is_multisite()) {
			add_filter('enable_update_services_configuration', '__return_true', 11);
			add_filter('whitelist_options', array(&$this, 'ms_update_services_enable'), 11);
		}
		if ($this->te('ping_noself'))
			add_action('pre_ping', array(&$this, 'ping_noself'));
		if ($this->te('post_excerpt_readmore'))
			add_filter('excerpt_more', array(&$this, 'post_excerpt_readmore_auto'));
		if ($this->te('post_targetblank'))
			add_action('wp_enqueue_scripts', array(&$this, 'post_targetblank'));
		if ($this->te('post_revisions_disable') && !defined('WP_POST_REVISIONS'))
			define('WP_POST_REVISIONS', false);
		if ($this->te('tag_autocomplete_disable'))
			add_action('admin_enqueue_scripts', array(&$this, 'tag_autocomplete_disable'));
		if ($this->te('wpt_footer_count'))
			add_action('wp_footer', array(&$this, 'wpt_footer_count'));
		
		add_action('plugins_loaded', array(&$this, 'load_textdomain'));
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_action('admin_init', array(&$this, 'admin_init'));
		add_action('admin_enqueue_scripts', array(&$this, 'admin_enqueue_scripts'));
	}
	
	function te($tweak) {
		if (is_array($tweak))
			return (bool)count(array_intersect($tweak, array_keys($this->tweaks)));
		elseif (is_string($tweak))
			return (isset($this->tweaks[$tweak]) && $this->tweaks[$tweak]);
		
		return false;
	}
	
	function load_textdomain() {
		load_plugin_textdomain('wordpress-tweaks', false, trailingslashit(plugin_basename(dirname(__FILE__))) . 'translations/');
	}
	
	function admin_menu() {
		add_options_page(__('WordPress Tweaks', 'wordpress-tweaks'), __('Tweaks', 'wordpress-tweaks'), 'manage_options', 'wordpress-tweaks', array(&$this, 'admin_page'));
	}
	
	function admin_init() {
		if (current_user_can('manage_options'))
			register_setting('jl_wpt', 'jl_wpt_tweaks');
	}
	
	function admin_enqueue_scripts() {
		if ($this->is_admin_page()) {
			wp_enqueue_style ('jl-wpt-admin-page', $this->plugin_dir_url . 'css/admin-page.css', array(), $this->version);
			wp_enqueue_script('jquery-highlight',  $this->plugin_dir_url . 'js/jquery.highlight-3.js', array('jquery'), $this->version);
			wp_enqueue_script('jl-wpt-admin-page', $this->plugin_dir_url . 'js/admin-page.js', array('jquery', 'jquery-highlight'), $this->version);
			wp_localize_script('jl-wpt-admin-page', 'jlWPTweaksL10n', array(
				'confirmUnload' => __('You enabled or disabled tweaks without saving. In order for your changes to take effect, you must click the Save Changes button at the bottom of the page.', 'wordpress-tweaks')
			));
		}
	}
	
	function admin_page() {
		
		//Add our custom footer attribution
		add_action('in_admin_footer', array(&$this, 'admin_footer'));
?>
<div class="wrap">
<div id="jl-wpt">
<?php screen_icon('options-general'); ?>
<h2><?php _e('WordPress Tweaks', 'seo-ultimate'); ?></h2>
<form id="jl-wpt-form" method="post" action="options.php">
<?php settings_fields('jl_wpt'); ?>
<div id="jl-wpt-tweaks-list-search">
	<input type="text" class="textbox" placeholder="<?php _e('Search', 'wordpress-tweaks'); ?>" />
</div>
<table id="jl-wpt-tweaks-list">
<?php
		$this->group_start(array(
			  'label' => __('Admin', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'admin_scroll_to_editor'
			, 'label' => __('Automatically scroll to the post editor', 'wordpress-tweaks')
			, 'info' => __('This tweak will automatically scroll down your screen to the post editor box when you&#8217;re on post/page writing/editing screens. Ideal for situations where screen resolution is limited. (Based on Dougal Campbell&#8217;s &#8220;WriteScroll&#8221; plugin.)', 'wordpress-tweaks')
			, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.2 because this setting has little effect on recent versions of WordPress. To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'disable_admin_bar'
			, 'label' => __('Disable the admin bar on the front-end', 'wordpress-tweaks')
			, 'info' => __('By default, the WordPress administration bar at the top of the screen can be removed from the front-end on a per-user basis. This tweak disables it on the front-end for all users.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'admin_disable_dashboard'
			, 'label' => __('Disable the Dashboard', 'wordpress-tweaks')
			, 'info' => __('Removes the dashboard from the menu and makes the first menu item (e.g. &#8220;Posts&#8221;) the default administration page. (Applies to all users except those with a viewdashboard capability, which you can assign with a user capabilities plugin.)', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'disable_privacy_on_link'
			, 'label' => __('Disable the &#8220;Search Engines Blocked&#8221; notice', 'wordpress-tweaks')
			, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.1 because the &#8220;Search Engines Blocked&#8221; notice is now much less annoying as of WordPress 3.2. (The notice now appears only on the Dashboard instead of across the entire admin area.) To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'tag_autocomplete_disable'
			, 'label' => __('Disable tag autocomplete', 'wordpress-tweaks')
			, 'info'  => __('If you&#8217;d like WordPress to stop giving you suggestions when you&#8217;re typing in post tags or custom taxonomy terms, enable this tweak. (Inspired by Alex King&#8217;s &#8220;Tag Uncomplete&#8221; plugin.)', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'disable_admin_footer_text'
			, 'label' => __('Disable WordPress&#8217;s admin footer text/links', 'wordpress-tweaks')
			, 'info' => __('Enable this tweak to hide the &#8220;Thank you for creating with WordPress&#8221; message in the admin footer.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'admin_remove_maxwidth'
			, 'label' => __('Remove the width restraint on administration pages', 'wordpress-tweaks')
			, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.0 because the admin area no longer has a width restraint as of WordPress 2.7. To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
		));
		
		$this->group_end();
		
		$this->group_start(array(
			  'label' => __('Comments and Pings', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'ping_noself'
			, 'label' => __('Disable self-pinging', 'wordpress-tweaks')
			, 'info' => __('Enable this tweak to stop WordPress from cluttering up your comments area with pingbacks from your own site. (Based on Michael D. Adams&#8217; &#8220;No Self Pings&#8221; plugin.)', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'comment_author_dofollow'
			, 'label' => __('Dofollow comment author links', 'wordpress-tweaks')
			, 'info' => __('If you carefully curate your comments, you may wish to reward your commenting visitors with link juice to their websites.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'comment_body_dofollow'
			, 'label' => __('Dofollow comment body links', 'wordpress-tweaks')
			, 'info' => __('If you carefully curate your comments, you may wish to reward the webpages that your commenting visitors link to.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'comment_targetblank'
			, 'label' => __('Open external comment links in new windows', 'wordpress-tweaks')
			, 'info' => __('Searches comments for links to webpages outside your domain name and adds <tt>target="_blank"</tt> using XHTML Strict-compliant JavaScript.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'comment_reverse'
			, 'label' => __('Show comments in reverse order', 'wordpress-tweaks')
			, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.0 because WordPress 2.7 added out-of-the-box support for showing newer comments first via an option on the <a href="options-discussion.php" target="_blank">Discussion Settings</a> page. To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
		));
		
		$this->group_end();
		
		$this->group_start(array(
			  'label' => __('Media', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'admin_disable_flash_uploader'
			, 'label' => __('Disable the Flash uploader', 'wordpress-tweaks')
			, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.1 because WordPress 3.3 replaced the Flash uploader with a new HTML5 uploader. To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
		));
		
		$this->dropdown(array(
			  'id' => 'media_upload_default_tab'
			, 'label' => __('Default media inserter tab', 'wordpress-tweaks')
			, 'info' => __('This tweak lets you choose the tab that first appears when you click the upload/insert media buttons above the post editor.', 'wordpress-tweaks')
			, 'options' => array(
				  '' => __('Default (From Computer)')
				, 'type' => __('From Computer')
				, 'type_url' => __('From URL')
				, 'gallery' => __('Gallery')
				, 'library' => __('Media Library')
			)
		));
		
		$jpeg_quality_options = array('' => __('Default (90%)', 'wordpress-tweaks'));
		for ($jpeg_quality=100; $jpeg_quality >= 10; $jpeg_quality -= 10)
			$jpeg_quality_options[$jpeg_quality] = $jpeg_quality . '%';
		
		$this->dropdown(array(
			  'id' => 'jpeg_quality'
			, 'label' => __('JPEG Quality', 'wordpress-tweaks')
			, 'info' => __('When WordPress is cropping/resizing JPEG images, it uses this percentage to determine the quality of its output. Higher quality percentages result in better-looking images but larger files.', 'wordpress-tweaks')
			, 'options' => $jpeg_quality_options
		));

		$this->group_end();
		
		if (is_multisite()) {
			$this->group_start(array(
				  'label' => __('Multisite', 'wordpress-tweaks')
			));
			
			$this->checkbox(array(
				  'id' => 'ms_update_services_enable'
				, 'label' => __('Let site admins edit the &#8220;Update Services&#8221; list', 'wordpress-tweaks')
				, 'info' => __('In multisite mode, WordPress removes the &#8220;Update Services&#8221; list that is normally found under <a href="options-writing.php" target="_blank">Settings &rarr; Writing</a>. This tweak will allow that site admins to edit that list just as they would if WordPress were in single-site mode. (Based on David M&aring;rtensson&#8217;s &#8220;Activate Update Services&#8221; plugin.)', 'wordpress-tweaks')
			));
			
			$this->group_end();
		}
		
		if ($this->te(array('comment_popup_link_nofollow', 'post_morelink_nofollow', 'bookmark_nofollow', 'tag_cloud_nofollow', 'meta_nofollow'))) {
			$this->group_start(array(
				  'label' => __('Nofollow', 'wordpress-tweaks')
				, 'removed' => __('You enabled nofollow tweaks in a previous version of WordPress Tweaks. However, these options were removed in WordPress Tweaks 2.0 because in 2008 Google altered its interpretation of the nofollow attribute so that nofollow&#8217;d links now dilute PageRank like normal links (meaning nofollowing may now hurt more than it helps). If you still want to nofollow your links, you can do so with the Nofollow Manager feature of the <a href="plugin-install.php?tab=plugin-information&plugin=seo-ultimate" target="_blank">SEO Ultimate</a> plugin. To remove this message, uncheck the nofollow tweaks&#8217; checkboxes and click Save Changes.', 'wordpress-tweaks')
			));
			
			$this->checkbox(array(
				  'id' => 'comment_popup_link_nofollow'
				, 'label' => __('Add nofollow to post comment links', 'wordpress-tweaks')
				, 'removed' => true
			));
			
			$this->checkbox(array(
				  'id' => 'post_morelink_nofollow'
				, 'label' => __('Add nofollow to &#8220;Read more&#8221; links', 'wordpress-tweaks')
				, 'removed' => true
			));
			
			$this->checkbox(array(
				  'id' => 'bookmark_nofollow'
				, 'label' => __('Add nofollow to link entries', 'wordpress-tweaks')
				, 'removed' => true
			));
			
			$this->checkbox(array(
				  'id' => 'tag_cloud_nofollow'
				, 'label' => __('Add nofollow to tag cloud links', 'wordpress-tweaks')
				, 'removed' => true
			));
			
			$this->checkbox(array(
				  'id' => 'meta_nofollow'
				, 'label' => __('Add nofollow to the &#8220;Register&#8221; and &#8220;Login&#8221; links', 'wordpress-tweaks')
				, 'removed' => true
			));
			
			$this->group_end();
		}
		
		$this->group_start(array(
			  'label' => __('Posts', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'post_revisions_disable'
			, 'label' => __('Disable post revisions', 'wordpress-tweaks')
			, 'info' => __('If you&#8217;re sure you won&#8217;t ever use the post revisions feature, you can stop revisions from using database space with this tweak.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'archive_excerpts'
			, 'label' => __('Force excerpts on archives', 'wordpress-tweaks')
			, 'info' => __('If your theme shows posts&#8217; full content on archive pages, enabling this tweak can help avoid duplicate content issues in search engines.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'post_targetblank'
			, 'label' => __('Open external post links in new windows', 'wordpress-tweaks')
			, 'info' => __('Searches posts for links to webpages outside your domain name and adds <tt>target=&quot;_blank&quot;</tt> using XHTML Strict-compliant JavaScript.', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'post_excerpt_readmore'
			, 'label' => __('Add a &#8220;Continue reading&#8221; link to excerpts', 'wordpress-tweaks')
			, 'info' => __('By default, WordPress adds &#8220;[...]&#8221; to the end of auto-generated post excerpts. This tweak replaces that with a nice &#8220;Continue&nbsp;reading&nbsp;&rarr;&#8221; link. Some themes (such as Twenty Ten) do this already, but if your theme doesn&#8217;t support it, this tweak will let you do it anyway.', 'wordpress-tweaks')
		));
		
		$this->group_end();
		
		if ($this->te(array('security_plugins_indexhtml', 'security_hide_wp_version'))) {
			
			$this->group_start(array(
				  'label' => __('Security', 'wordpress-tweaks')
			));
			
			$this->checkbox(array(
				  'id' => 'security_plugins_indexhtml'
				, 'label' => __('Disable directory listing for my plugins folder', 'wordpress-tweaks')
				, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.0 because WordPress 2.8 and later include this security feature out-of-the-box. To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
			));
			
			$this->checkbox(array(
				  'id' => 'security_hide_wp_version'
				, 'label' => __('Hide WordPress&#8217;s version number from my theme and feeds', 'wordpress-tweaks')
				, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.0 because it provides no protection against modern security threats. To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
			));
			
			$this->group_end();
		}
		
		if ($this->te(array('theme_favicon_link', 'page_list_nospace'))) {
			
			$this->group_start(array(
				  'label' => __('Theme and Appearance', 'wordpress-tweaks')
			));
			
			$this->checkbox(array(
				  'id' => 'theme_favicon_link'
				, 'label' => __('Add code references to favicon.ico', 'wordpress-tweaks')
				, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.0 because no major browsers need code references to locate a favicon.ico placed in the root. To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
			));
			
			$this->checkbox(array(
				  'id' => 'page_list_nospace'
				, 'label' => __('Remove white space from pages list', 'wordpress-tweaks')
				, 'removed' => __('You enabled this tweak in a previous version of WordPress Tweaks. However, this tweak was removed in WordPress Tweaks 2.0 because this is an issue that should be resolved by theme developers. To remove this message, uncheck the tweak&#8217;s checkbox and click Save Changes.', 'wordpress-tweaks')
			));
			
			$this->group_end();
		}
		
		$this->group_start(array(
			  'label' => __('Updates', 'wordpress-tweaks')
		));
		
		$this->dropdown(array(
			  'id' => 'core_update_level'
			, 'label' => __('Core update auto-installation', 'wordpress-tweaks')
			, 'info' => __('Do you want WordPress to upgrade itself automatically? (By default, WordPress auto-installs minor updates only.)', 'wordpress-tweaks')
			, 'options' => array(
				  '' => __('Do not adjust', 'wordpress-tweaks')
				, 'none' => __('No, do not allow WordPress to update itself', 'wordpress-tweaks')
				, 'minor' => __('Yes, but only install minor updates', 'wordpress-tweaks')
				, 'major' => __('Yes, install every WordPress update automatically', 'wordpress-tweaks')
			)
		));
		
		$this->group_end();
		
		$this->group_start(array(
			  'label' => __('Plugin Settings', 'wordpress-tweaks')
		));
		
		$this->checkbox(array(
			  'id' => 'wpt_footer_count'
			, 'label' => __('Enable tweaks counter', 'wordpress-tweaks')
			, 'info' => __('Show off my tweaks count in my blog&#8217;s footer.', 'wordpress-tweaks')
		));
		
		$this->group_end();
?>
	<tr id="jl-wpt-empty-search-results" style="display: none;"><td colspan="2"><p><?php _e('No tweaks contain your search term', 'wordpress-tweaks'); ?></p></td></tr>
</table>
<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes', 'wordpress-tweaks'); ?>" />
</p>
</form>
</div>
</div>
<?php
	}
	
	function admin_footer() {
		
		$plugin = __('WordPress Tweaks', 'wordpress-tweaks');
		$plugin = "<a href='http://johnlamansky.com/wordpress/plugins/tweaks/' target='_blank'>$plugin</a>";
		
		$author = 'John Lamansky';
		$author = "<a href='http://johnlamansky.com/wordpress/' target='_blank'>$author</a>";
		
		printf(__('%1$s %2$s by %3$s', 'wordpress-tweaks'), $plugin, $this->version, $author);
		echo "<br />";
	}
	
	function is_admin_page() {
		if (is_admin()) {
			global $plugin_page;
			return (strcmp($plugin_page ? $plugin_page : $_GET['page'], 'wordpress-tweaks') == 0);
		}
		
		return false;
	}
	
	function group_start($args) {
		$label = $args['label'];
		$removed = $args['removed'];
?>
	<tr valign="top" class="jl-wpt-group<?php if ($removed) echo ' jl-wpt-removed'; ?>">
		<th scope="row"><?php echo $label; ?></th>
		<td>
			<?php if ($removed && is_string($removed)) echo "<div class='jl-wpt-removed-message'>$removed</div>"; ?>
			<table class="jl-wpt-tweaks">
<?php
	}
	
	function group_end() {
?>
			</table>
		</td>
	</tr>
<?php
	}
	
	function checkbox($args) {
		$id = esc_attr($args['id']);
		$label = $args['label'];
		$info = $args['info'];
		$removed = $args['removed'];
		
		if ($removed && !$this->te($args['id']))
			return;
		
?>
				<tr class="jl-wpt-checkbox<?php if ($removed) echo ' jl-wpt-removed'; ?>">
					<td class="jl-wpt-input" valign="top">
						<input type="checkbox" name="jl_wpt_tweaks[<?php echo $id; ?>]" id="<?php echo $id; ?>" value="1"<?php checked($this->te($args['id'])); ?> />
					</td>
					<td class="jl-wpt-text" valign="top">
						<div class="jl-wpt-label"><label for="<?php echo $id; ?>"><?php echo $label; ?></label></div>
						<?php if ($info)  echo "<div class='jl-wpt-info'>$info</div>"; ?>
						<?php if ($removed && is_string($removed)) echo "<div class='jl-wpt-removed-message'>$removed</div>"; ?>
					</td>
				</tr>
<?php
	}
	
	function textarea($args) {
		$id = esc_attr($args['id']);
		$label = $args['label'];
		$info = $args['info'];		
?>
				<tr class="jl-wpt-textarea">
					<td colspan="2" valign="top">
						<div class="jl-wpt-text">
							<div class="jl-wpt-label"><label for="<?php echo $id; ?>"><?php echo $label; ?></label></div>
							<div class="jl-wpt-info"><?php echo $info; ?></div>
						</div>
						<div class="jl-wpt-input">
							<textarea name="jl_wpt_tweaks[<?php echo $id; ?>]" id="<?php echo $id; ?>"><?php echo esc_textarea($this->tweaks[$id]); //esc_textarea requires WordPress 3.1+ ?></textarea>
						</div>
					</td>
				</tr>
<?php
	}
	
	function dropdown($args) {
		$id = esc_attr($args['id']);
		$label = $args['label'];
		$info = $args['info'];
		$options = (array)$args['options'];
?>
				<tr class="jl-wpt-dropdown">
					<td colspan="2" valign="top">
						<div class="jl-wpt-text">
							<div class="jl-wpt-label"><label for="<?php echo $id; ?>"><?php echo $label; ?></label></div>
							<div class="jl-wpt-info"><?php echo $info; ?></div>
						</div>
						<div class="jl-wpt-input">
							<select name="jl_wpt_tweaks[<?php echo $id; ?>]" id="<?php echo $id; ?>">
								<?php foreach ($options as $option_value => $option_text) { ?>
								<option value="<?php echo $option_value; ?>"<?php selected($this->tweaks[$id] == $option_value); ?>>
									<?php echo esc_html($option_text); ?>
								</option>
								<?php } ?>
							</select>
						</div>
					</td>
				</tr>
<?php
	}
	
	function admin_disable_dashboard_menu() {
		if (!current_user_can('viewdashboard')) {
			global $menu, $submenu;
			
			$to_remove = array('index.php', 'separator1');
			
			foreach ($menu as $menu_id => $menu_item) {
				
				if ($menu_item[2] == current($to_remove)) {
					unset($menu[$menu_id]);
					unset($submenu[current($to_remove)]);
					
					if (next($to_remove) === false)
						break;
				}
			}
		}
	}
	
	function admin_disable_dashboard_page() {
		if (!current_user_can('viewdashboard')) {
			global $menu;
			$first_item = reset($menu);
			wp_redirect(admin_url($first_item[2]));
			exit;
		}
	}
	
	function archive_excerpts($content) {
		static $enabled = true;
		
		if ($enabled && is_archive()) {
			$enabled = false;
			the_excerpt();
			$enabled = true;
			return '';
		}
		
		return $content;
	}
	
	function comment_targetblank() {
		if (!is_admin()) {
			//This tweak's JavaScript is based on code from
			//http://www.drupalcoder.com/blog/automatically-open-all-external-links-in-a-new-window-using-jquery
			wp_enqueue_script('jl-wpt-comment-targetblank'
				, $this->plugin_dir_url . 'js/comment-targetblank.js'
				, array('jquery')
				, $this->version
				, true);
		}
	}
	
	function disable_admin_bar() {
		global $pagenow;
		if (is_admin() && in_array($pagenow, array('profile.php', 'user-edit.php'))) {
			wp_enqueue_style('jl-wpt-disable-admin-bar'
				, $this->plugin_dir_url . 'css/disable-admin-bar.css'
				, array()
				, $this->version
				, 'all'
			);
		}
	}
	
	function jpeg_quality() {
		return absint($this->tweaks['jpeg_quality']);
	}
	
	function media_upload_default_tab() {
		return $this->tweaks['media_upload_default_tab'];
	}
	
	function ms_update_services_enable($whitelist) {
		$whitelist['writing'][] = 'ping_sites';
		return $whitelist;
	}
	
	function ping_noself( &$links ) {
		$home = get_option( 'home' );
		foreach ( $links as $l => $link )
			if ( 0 === strpos( $link, $home ) )
				unset($links[$l]);
	}
	
	function post_excerpt_readmore_link() {
		$more_link_text = __('Continue reading &rarr;', 'wordpress-tweaks');
		return apply_filters('the_content_more_link',
			' <a href="' . get_permalink() . '#more-' . get_the_ID() . '" class="more-link">' . $more_link_text . '</a>', $more_link_text);
	}
	
	function post_excerpt_readmore_auto($default) {
		if (in_the_loop())
			return $this->post_excerpt_readmore_link();
		
		return $default;
	}
	
	function post_targetblank() {
		if (!is_admin()) {
			//This tweak's JavaScript is based on code from
			//http://www.drupalcoder.com/blog/automatically-open-all-external-links-in-a-new-window-using-jquery
			wp_enqueue_script('jl-wpt-post-targetblank'
				, $this->plugin_dir_url . 'js/post-targetblank.js'
				, array('jquery')
				, $this->version
				, true);
		}
	}
	
	function remove_nofollow($content) {
		return preg_replace_callback('|<a (.+?)>|i', array(&$this, 'remove_nofollow_callback'), $content);
	}
	
	function remove_nofollow_callback($matches) {
		$attrs = $matches[1];
		$attrs = preg_replace('|rel=["\']([^"\']*)nofollow([^"\']*)["\']|i', ' rel="\1\2"', $attrs);
		$attrs = preg_replace('|rel=["\']( *)["\']|i', '', $attrs);
		return "<a $attrs>";
	}
	
	function tag_autocomplete_disable() {
		if (is_admin()) {
			wp_enqueue_script('jl-wpt-tag-autocomplete-disable'
				, $this->plugin_dir_url . 'js/tag-autocomplete-disable.js'
				, array('jquery')
				, $this->version
				, true);
		}
	}
	
	function wpt_footer_count() {
		
		if (!is_front_page() && !is_home()) return;
		
		$count = count(array_filter($this->tweaks)) - 1; //Don't count the footer count itself in the tweaks count!
		if ($count > 0) {
			
			$format = _n(
				  'This site has been fine-tuned by %1$d %2$sWordPress Tweak%3$s'
				, 'This site has been fine-tuned by %1$d %2$sWordPress Tweaks%3$s'
				, $count
				, 'wordpress-tweaks'
			);
			
			$format = "<div id='jl-wpt-counter'>$format</div>";
			
			printf($format, $count, '<a href="http://johnlamansky.com/wordpress/plugins/tweaks/" style="color: inherit;">', '</a>');
		}
	}
	
}