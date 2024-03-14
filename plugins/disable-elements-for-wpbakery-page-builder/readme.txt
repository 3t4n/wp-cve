=== Disable Elements for WPBakery Page Builder ===
Contributors: WPExplorer
Tags: wpbakery, page builder, wpexplorer
Requires at least: 5.2.0
Requires PHP: 7.4
Tested up to: 6.4
Stable Tag: 1.1
License: GNU Version 2 or Any Later Version.

== Description ==
This plugin adds a new page at WPBakery Page Builder > Disable Elements so you can disable elements not in use. This plugin was created for use with the [Total Theme](https://total.wpexplorer.com/) since many of our customers have requested it, but it should work with any well coded theme.

== Installation ==

1. Go to your WordPress website admin panel
2. Select Plugins > Add New
3. Search for "Disable Elements for WPBakery Page Builder"
4. Click Install
5. Activate the plugin
6. Go to WPBakery Page Builder > Disable Elements
7. Uncheck any element you wish to disable
8. Save

== Frequently Asked Questions ==

= Why would I use this plugin? =
The WPBakery Page Builder includes many core elements and depending on what theme or addon plugins you are using it could include even more. If there are elements you either don't like or don't need for your website this plugin can be used to unregister them and remove unnecessary bloat.

= What happens when I disable an element? =
Disabled elements pass through the WPBakery vc_remove_element which removes them from the page builder "mapper" and are also unregistered from the site via the core remove_shortcode function.

= Why do I see disabled elements code on the site? =
This plugin does not scan your site and remove any previously added content. When you disable an element it becomes unregistered and won't render on the live site so instead you will see the shortcode in text form.

If you wish to actually remove all previously added elements from a site this would require manual site editing or a different plugin.

== Changelog ==

= 1.1 =

* Removed required elements from the list of items that can be disabled (Row, Inner Row, Column, Inner Column) to prevent errors.
* Updated admin to include labels so you can click the element names to uncheck/check items.
* Updated the get_disabled_elements() function to be public.
* Added extra permissions check to the admin screen.

= 1.0 =

* First official release