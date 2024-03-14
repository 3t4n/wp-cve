<?php
/**
 * Core integration pack class, to be extended by all Suffusion add-ons.
 */

if (!class_exists('Suffusion_Integration_Pack')) {
	abstract class Suffusion_Integration_Pack {
		var $option_page, $page_title, $menu_title, $capability, $menu_slug, $version;

		function __construct($page_title, $menu_title, $menu_slug, $version, $capability = 'edit_theme_options') {
			$this->page_title = $page_title;
			$this->menu_title = $menu_title;
			$this->capability = $capability;
			$this->menu_slug = $menu_slug;
			$this->version = $version;

			add_action('admin_menu', array(&$this, 'admin_menu'));
			add_action('admin_enqueue_scripts', array(&$this, 'add_admin_scripts'));
			add_action('wp_enqueue_scripts', array(&$this, 'add_scripts'));
			add_action('wp_print_scripts', array(&$this, 'direct_scripts'));
		}

		/**
		 * Checks if you are using a child theme of Suffusion or not.
		 *
		 * @param bool $child_required
		 * @return void
		 */
		function check_theme($child_required = true) {
			$theme = wp_get_theme(); // Need this because a child theme might be getting used.
			if (isset($theme['Template']) && $theme['Template'] != 'suffusion') {
				?>
			<div class="error">
				<p>
					You are not using Suffusion or a child theme. The plugin may still be used, but you might not get the desired results with it.
				</p>
			</div>
			<?php
			}
			else if (isset($theme['Template']) && $theme['Template'] == 'suffusion' && $theme['Template'] == $theme['Stylesheet'] && $child_required) {
				?>
			<div class="error">
				<p>
					You are using Suffusion, but not a child theme. Note that any changes made using this plugin will get wiped out the next time you
					update Suffusion. To avoid this, <a href='http://aquoid.com/news/2012/02/suffu-scion-a-starter-child-theme-for-suffusion/'>create a child theme of Suffusion</a> and use that.
				</p>
			</div>
			<?php
			}
		}

		/**
		 * Adds an item to the "Appearance" menu
		 */
		function admin_menu() {
			$this->option_page = add_theme_page($this->page_title, $this->menu_title, $this->capability, $this->menu_slug, array(&$this, 'render_options'));
		}

		/**
		 * Enqueues scripts in the admin pages for the plugin.
		 *
		 * @abstract
		 * @param $hook String Specifies which pages the scripts should be added to. Without this the scripts get added to all admin pages
		 * @return mixed
		 */
		abstract function add_admin_scripts($hook);

		/**
		 * Enqueues front-end scripts for the plugin.
		 *
		 * @abstract
		 * @return mixed
		 */
		abstract function add_scripts();

		/**
		 * Prints JS / CSS directly in the header of the pages. This is hooked to wp_print_scripts, not wp_head, because
		 * this must be executed before wp_enqueue_scripts. Global variables are defined here.
		 *
		 * @abstract
		 * @return mixed
		 */
		function direct_scripts() {
			// Intentionally left blank
		}

		/**
		 * Builds out the configuration page for the plugin.
		 *
		 * @abstract
		 * @return mixed
		 */
		abstract function render_options();

		function other_plugins() { ?>
		<fieldset>
			<legend>Suffusion Extension Plugins</legend>
			<p>
				Suffusion has the following extension plugins that let you harness third party scripts, or move away from using Suffusion without
				affecting your content.
			</p>

			<ul class="suf-extensions">
				<li>
					<a href="http://wordpress.org/extend/plugins/suffusion-buddypress-pack" class='pack pack-bp' title="Suffusion BuddyPress Pack">BuddyPress Pack</a>
				</li>
				<li>
					<a href="http://wordpress.org/extend/plugins/suffusion-commerce-pack" class='pack pack-cp' title="Suffusion Commerce Pack">Commerce Pack</a>
				</li>
				<li>
					<a href="http://wordpress.org/extend/plugins/suffusion-bbpress-pack" class='pack pack-bbp' title="Suffusion bbPress Pack">bbPress Pack</a>
				</li>
				<li>
					<a href="http://wordpress.org/extend/plugins/suffusion-shortcodes" class='pack pack-sc' title="Suffusion Shortcodes">Shortcodes</a>
				</li>
			</ul>
		</fieldset>

		<?php
		}
	}
}