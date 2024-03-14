=== No Gutenberg - Disable Gutenberg Blocks Editor and FSE Global Styles ===
Contributors: fernandot
Donate link: https://www.paypal.me/fernandotellado
Tags: gutenberg, editor, classic editor, block editor, disable gutenberg, gutenfree, global styles, fse 
Requires at least: 4.9
Requires PHP: 5.6
Tested up to: 6.4
Stable tag: trunk
License: GPLv2+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Disable the Gutenberg Block Editor and FSE Global Styles

== Description ==

WordPress 5.x bundled a new block editor called Gutenberg. With 5.9 version were added the called global styles for Full Site Editing (FSE) that load a lot of inline styles in every page. Despite his benefits, mainly for compatibility reasons with other plugins and previous contents, there are a lot of users that don't want to activate it yet. If you don't want the Gutenberg Block Editor and FSE Global Styles in your WordPress install right now, simply install this plugin, activate it and … That's all!

What does this plugin does?:

* Disables totally the Gutenberg Block Editor
* Disables totally the Full Site Editing (FSE) Global Styles added inline to every page
* Shows and use by default the cool and compatible with everything WordPress Classic Editor
* Plus: Disables the WP 4.9.8 "Try Gutenberg" callout Dashboard widget

<strong>No options</strong>. Just install & activate the plugin prior to update to WordPress 5.x and you that's all. You'll get WordPress 5.x but without the Gutenberg Block Editor.

== Plugin Requirements ==
* This plugin requires WordPress 4.9 or greater with the Gutenberg Plugin installed and activated (for testing purposes) or WordPress 5.0 or greater
* This plugin requires PHP 5.6 or greater


== Installation ==

1. Go to your WP Dashboard > Plugins and search for ‘no gutenberg’ or…
2. Download the plugin from WP repository.
3. Upload the ‘no-gutenberg’ folder to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Do you hate the Gutenberg Editor? =

No, I don't. I think that is more important to decide what you want in your WordPress and what not. If you don't want to make this huge step to the new Gutenberg Editor with this plugin you'll have de decision in your hands.

= What happens if I decide to deactivate the plugin? =

* You'll recover the brand new Gutenberg Editor
* Your life will be a little more difficult from that moment :)
  
= Something went wrong after activation =

This plugin is compatible with all WordPress JavaScript functions (`wp_localize_script()`, js in header, in footer...) and works with all well coded plugins and themes. If a plugin or a theme is not properly enqueuing scripts, your site may not work. If your host doesn’t support any of the tweaks, usually due to security restrictions, is possible that something fails. If anything fails please access to your <code>/wp-content/plugins/no-gutenberg/</code> directory via your favourite FTP client or hosting panel (cPanel, Plesk, etc.) and rename the plugin folder to deactivate it.

= What’s next? =

Nope. I'll expect Matt and all the rest of this beauty guys to abandon the idea that a blocks editor is a good idea for post editing.


== Screenshots ==

1. WordPress posts page before plugin activation.
2. WordPress posts page after plugin activation.

== Changelog ==
= 1.0.7 =
* Tested up to WordPress 6.4

= 1.0.6 =
* Tested up to WordPress 6.2

= 1.0.5 =
* Tested up to WordPress 6.1

= 1.0.4 =
* Tested up to WordPress 6.0.2

= 1.0.3 =
* Tested up to WordPress 6.0

= 1.0.2 =
* Added the action to remove the FSE Global Styles

= 1.0.12 =
* Tested up to WordPress 5.9

= 1.0.11 =
* Tested up to WordPress 5.8

= 1.0.10 =
* Tested up to WordPress 5.6

= 1.0.9 =
* Tested up to WordPress 5.5.1

= 1.0.8 =
* Tested up to WordPress 5.5

= 1.0.7 =
* Tested up to WordPress 5.4.1

= 1.0.6 =
* Tested up to WordPress 5.4

= 1.0.5 =
* Tested up to WordPress 5.3.2

= 1.0.5 =
* Tested up to WordPress 5.3 (And it works!)

= 1.0.4 =
* Tested up to WordPress 5.2.2

= 1.0.3 =
* Tested up to WordPress 5.2

= 1.0.2 =
* Tested up to WordPress 5.1

= 1.0.1 =
* Better and simply readme file

= 1.0.0 =
* Now it works great with all Gutenberg Block Editor versions (plugin and core) 
* New conditional filters added to check Gutenberg version before apply the proper function to disable the Block Editor. 

= 0.9.2 =
* Tested up to WordPress 5.0 tag added

= 0.9.1 =
* Function added to disable the "Try Gutenberg" callout Dashboard widget introduced in WP 4.9.8

= 0.9 =
* Initial release, and hopefully latest
