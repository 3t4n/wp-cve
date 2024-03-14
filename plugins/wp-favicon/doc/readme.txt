=== WP Favicon ===
Contributors: Jean-Michel Paris
Donate link: http://www.geekthegathering.com/category/wordpress/wp-favicon/
Tags: theme, favicon
Requires at least: 2.8
Tested up to: 2.8.1
Stable tag: 0.1

Just add a favicon on your site. If your activated theme doesn't support favicon; the plugin add the functionality.

== Description ==

The "WP Favicon" plugin add a favicon to your site. Most of the themes just forgot to manage this small but nice feature.
The plugin just add - inside the HEAD section - the needed links to the favicon.
It supports, both the .ico and a .gif favicon.

= Maximum Compatibility
Due to the famous web browser from Redmond. The best approach is to have 2 separates favicon files located at the site root directory.
1. The first  favicon file (favicon.ico) is either a 16x16 or a 32x32 icon. Nowadays, all modern browser supports both size.
2. The second favicon file (favicon.gif) is a (reasonable) free size, and can be an animated GIF too!
The .gif version have precedence over the .ico. So, if the browser support animated favicon,
the animated will be displayed, else the static .ico will be used instead.
Note that both favicon.ico and favicon.gif can be different pictures.

== Installation ==

1. Upload `wp-favicon.php` to the `/wp-content/plugins/` directory, or install it through the admin interface
2. Upload your favicon.ico and favicon.gif to your blog root directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. That's all!

== Frequently Asked Questions ==

= What else? =

You can directly ask questions or suggestions to the plugin [homepage](http://www.geekthegathering.com/category/wordpress/wp-favicon/ "WP Favicon") and leave a comment.

== Screenshots ==

1. The admin option panel.
2. The generated HTML code in the HEAD section.

== Changelog ==

= 0.1 =
* First version!

== Author ==

[Jean-Michel Paris](http://www.jeanmichelparis.com/ "Jean-Michel Paris")