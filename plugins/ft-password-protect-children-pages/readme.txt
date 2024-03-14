=== FT Password Protect Children Pages ===
Contributors: fullthrottledevelopment, blepoxp
Tags: passwords, protected, pages
Requires at least: 2.7
Tested up to: 3.0
Stable tag: 0.3

Applies the same protection to children pages applied to the parent page.

== Description ==

This plugin does one thing. If a page that is password protected has children pages, all children pages will be protected with the same password. 

If the correct password is entered on the parent page or any of its children pages, all related pages will be viewable to the user.

The plugin protects unlimited levels of grandchildren pages via the $post->ancestors. It uses first ancestor that is password protected.

== Installation ==

Upload the plugin to your plugins directory then activate it.

Ask Questions here: [http://fullthrottledevelopment.com/password-protect-children-pages/]

== Additional Information ==

The plugin currently works by looking for parent pages that are password protected and applying the same restrictions the the currently being viewed child page. This means that the children pages do not actually get a password added to the database.

I'm trying to decide if this is the best way to proceeed or if I should take another route (such as adding / updating / removing passwords from children pages at the write or save post screen).

Please feel free to offer any suggestions or report any bugs here: [http://fullthrottledevelopment.com/password-protect-children-pages/]

Thanks!

== Changelog ==

= 0.3 =
* Fixed bug where children pages of non-protected parents had 'Protected: ' prepended to the title.
* In case where grandparent page is not protected but parent page was, child pages are now protected.

= 0.2 =
* Added ability to protect all levels below initially protected page. Props to trevorgehman on the WP.org support forums for the tip.

== Upgrade Notice ==
= 0.3 =
Fixed bug where children pages of non-protected parents had 'Protected: ' prepended to the title. Can also detect if parent page is protected now even when grandparent page is not protected.