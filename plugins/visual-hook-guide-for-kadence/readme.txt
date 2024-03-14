=== Visual Hook Guide for Kadence ===
Contributors: srikat
Tags: kadence, hooks, action hooks, visual hooks, hook guide
Donate link: https://www.paypal.me/sridharkatakam
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.html
Tested up to: 6.2.2
Stable tag: trunk
Requires PHP: 7.4

Find Kadence action hooks quickly and easily by seeing their actual locations inside your Kadence theme.

== Description ==

This plugin is for use by developers working with the [Kadence](https://www.kadencewp.com/kadence-theme/) theme and adds a `Kadence Hooks` item in the WP admin bar on the front end.

Clicking `Action Hooks` item in the submenu will display the currently available Kadence action hooks on that page. `Clear` clears the hooks.

Clicking anywhere on a hook copies the hook name to your clipboard.

The hook name is also shown as the tooltip when hovering on a hook.

**Kadence theme required.**

== Frequently Asked Questions ==

= My fixed header appears to be offset from the top when scrolling down with the hooks showing =

Use your browser DevTools, locate the the div having a class of `item-is-fixed` (this is usually div.site-main-header-wrap) and uncheck `position: fixed` temporarily.

= Is it possible to view the hooks when the WP admin bar is not visible? =

Yes. Add `?kvhg_hooks=show` at the end of your page URL.

= Is this an official plugin by Kadence WP? =

No. This is a third party plugin created by a community member.

== Screenshots ==

1. Screenshot showing the WP admin bar menu item.
2. Screenshot showing the actions hooks on the page.

== Installation ==

=== Automatic Installation ===

Search for `visual hook guide kadence` from within your WordPress plugins' Add New page and install.

=== Manual Installation ===

1. Click on the `Download` button to download the plugin.
2. Upload the entire `kadence-visual-hook-guide` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the `Plugins` menu in WordPress.

== Changelog ==

= 1.0.1 =
Added copy to clipboard function.

= 1.0 =
Initial Release.

== Credits ==

This plugin is based on my [Genesis Simple Hook Guide](https://github.com/srikat/Genesis-Simple-Hook-Guide) which was made possible thanks to

* [Gary Jones](https://github.com/GaryJones/) for [the idea](http://d.pr/i/qSKK)
* [Sal Ferrarello](https://github.com/salcode) for [the code](http://d.pr/i/h2DA)