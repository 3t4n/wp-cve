=== Menu Caching ===
Contributors: theogk
Donate link: https://www.buymeacoffee.com/theogk
Plugin Name: Menu Caching
Tags: wordpress menu, caching, menu cache, menu caching, speed up menu
Author: Theo Gkitsos
Author link: https://theodorosgkitsos.com/
Version: 1.1.2
Stable tag: 1.1.2
Requires at least: 5.3
Tested up to: 6.3
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin caches WordPress menus to improve page loading time.

== Description ==

We all know that database calls are the main performance bottleneck in WordPress. What most people don't know though, is how "expensive" in terms of performance the WordPress menus are.

This plugin will cache the menu HTML and show the cached version to your visitors, saving your database from far too many unnecessary calls.

Menu data are scattered across six(!) different database tables. When a user visits a page, an odyssey throughout the database begins.
In 'wp_terms', 'wp_term_taxonomy' and 'wp_options' tables we'll find menu ID, slug and theme location. Then 'wp_posts' and 'wp_postmeta' to find menu's nav items and their metas.
In the metas, we will find its targeted object, so let's pay 'wp_terms' or 'wp_posts' a visit again to find the menu item's target and 'wp_termmeta' to find its metas.

These are a lot of tables and even more database calls! When all required data is collected, the menu HTML is created and it is shown to the user.

The same process is repeated for every menu on the page. Desktop menu, mobile menu, some menus on the footer, a menu for the customer account on the header's right next to the cart icon...

Imagine all these menus, some of them with decades of menu items, loading in every page for every visitor on your site.

Menu changes are very rare and this makes them an easy target for caching. Caching the generated menu HTML will prevent all these unnecessary database calls and greatly improve page loading times.


= Full features list =
- Caches all classic WordPress menus to improve page loading time.
- Enable/disable caching per menu.
- Clear all menu cache with a button on the settings page.
- Every time you edit a menu, its cache is automatically purged.
- All cached data are automatically purged every 10 hours to keep everything fresh, like nonces etc.
- Compatible with caching plugins like WPRocket, W3 Total Cache etc.
- Compatible with most translation plugins. Tested ok with WPML and TranslatePress.

= How it works =

Everytime a user visits a page with a menu, WordPress collects all menu data from the database, and then runs a walker to create the menu's HTML. Right before this generated HTML is returned to the user, the HTML is saved in the database in a transient.

Next time a user requests this specific menu, the saved HTML will be returned, instead of creating the menu from scratch.

= Test results - How effective is it anyway? =

If you use a good caching plugin and a certain page is served from cache, then you not see a difference in loading times. BUT... there are many cases when a page is not served from cache and it that scenario you will notice a huge difference.

Some of the scenarios when a page is not served from cache:

- If you are not using a page caching plugin (Why not? Please install one!).
- No existing cached version exists of the requested page. You benefit from menu caching during the first page load on every page, before the cached version is saved.
- When users are logged-in. Even when using a per-user cache for logged-in users, menu caching is still super useful to quickly create the menu as the cache files are getting created.
- On e-commerce sites on pages that can't be cached by default, like cart, checkout, my account, wishlist etc.
- In most e-commerce sites, when a customer adds something to cart, then serving pages from cache stops to prevent false data in the mini-cart.
- On some AJAX requests that load page content that includes menus.

Let's see the results from some tests run on a medium-to-large e-commerce site with a mega menu with many categories, a separate mobile menu and a couple more small menus.

For admin user:

- Loading time for all menus - no menu caching: 0.46s (in average)
- Loading time for all menus - with menu caching: 0.0015s (in average)
- Speed benefit: 300+ times faster - menu loads almost instantly!

For incognito visitor:

- Loading time for all menus - no menu caching: 0.232194 sec
- Loading time for all menus - with menu caching: 0.001185 sec
- Speed benefit: ~200 times faster - menu loads almost instantly!

Of course these numbers depend on your WordPress installation, your server setup and so many parameters but the outcome will be the same.

With menu caching, instead of building the menu everytime and losing precious time during page load, your menus will load instantly from cache.

= Compatibility with new "block" themes =

Menus on the new block themes are constructed in a different way via the block editor, and not via the classic nav builder we all love to hate. This plugin can't optimize menus built with the new block editor. It's not going to break anything though, it won't do anything at all. If you use a classic theme like 99.9% of the WordPress websites out there, this plugin will be useful to you.

== Screenshots ==

1. Plugin settings.
2. Speed improvement results.

== Frequently Asked Questions ==

 = How to activate menu caching? =
 You don't have to do anything at all. As soon as you install and activate the plugin, menu caching starts working immediately. You can visit the plugin's settings under Tools > Menu Caching to disable menu caching for a specific menu, or clear manually the cache.

 = Does it benefit my site if I use a page caching plugin? =
Yes, it does. Pages in some circumstances are not sarved from cache, or a cached version is not available. In these scenarios menu caching greatly reduces page load time. For more info see the respective section in the description.

 = Can I clean the menus' cache manually? =
Yes, there is a button on the plugin's settings under Tools > Menu Caching. You probably don't need to, though. The cache auto-refreshes frequently, and also gets flushed after you edit a menu.

 = What about menus with links which contain nonces or any really special custom menu items? =
Menu items like a logout link, contain a nonce for security reasons. Nonces are unique for each logged-in user session. This plugin detects automatically if a menu contains links with nonces and cache this specific menu separately for each user session.

If you still have any problem with caching of a menu, or you just don't want to cache it for any reason, you can disable caching for this specific menu from the plugin's settings.

 = Where are the cached data saved? =
The cached menus are saved in the database in the 'wp_options' table as transients with a lifetime of 10 hours.

 = Why cache data are refreshing every 10 hours? Can I change that? =
Cache gets automatically refreshed as a preventive measure, so that it never gets stale. Also, the 10 hours period prevents nonces from expiring, in case some of your menus contain some of them.

Can't see why you want to change it but sure you can, using the 'dc_wp_menu_caching_lifetime' filter.
 `
 add_filter( 'dc_wp_menu_caching_lifetime', function( $original_value ) { return $time_in_seconds; } );
 `

 = Do you have problem with a specific menu after enabling this plugin? =
 Some themes make weird things to menus. Or maybe you use a plugin that add extra functionality to menus, like mega menus etc. This plugin works fine with most of the themes and plugins out there, but I can't promise compatibility with anything out there.

 If you notice anything weird in a menu after enabling this plugin, please head to this plugin's settings and disable caching for this specific menu.

 = Is it compatible with the new block themes? =
 No, it's not because menus in block themes work in an entirely different way. It's not going to break anything though, it won't do anything at all, like wearing sunglasses at night.

== Roadmap ==

This plugin does only one specific thing, menu caching. There is no plan to add more features. Of course small improvements may be implemented, and if any bug appears, I will try to fix it. Next versions will try to expand compatibility with more popular themes.

A next update is already under development, intending to add compatibility with Woodmart theme.

If you have any good ideas about this plugin and yoy want to contribute, you can make a PR in the [github repo](https://github.com/theo-gk/menu-caching).

== Installation ==

1. Download the plugin from [Official WP Plugin Repository](https://wordpress.org/plugins/menu-caching/).
2. Upload Plugin from your WP Dashboard ( Plugins>Add New>Upload Plugin ) the menu-caching.zip file.
3. Activate the plugin through the 'Plugins' menu in WordPress Dashboard.
4. Plugin's settings are located inside Tools > Menu Caching.

== Changelog ==

= 1.1.2 =
* Tested up to 6.3.

= 1.1.1 =
* Tested up to 6.2.

= 1.1 =
* Fix error during uninstallation.
* Change hash generator to cover more cases and menu classes.
* Save no-cache checkbox state for menus with non-english slug.

= 1.0.0 =
* Initial Release
