=== Surbma | Yoast SEO Breadcrumb Shortcode ===
Contributors: Surbma, CherryPickStudios
Donate link: https://surbma.com/donate/
Tags: yoast, yoast seo, wordpress seo, shortcode, breadcrumb, wpseo-breadcrumb
Requires at least: 5.0
Tested up to: 6.2
Stable tag: 1.1
Requires PHP: 7.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple shortcode to include Yoast's breadcrumb function everywhere on your WordPress website.

== Description ==

A simple shortcode to include Yoast SEO's breadcrumb function everywhere on your WordPress website. You have to install and activate the [WordPress SEO by Yoast](https://wordpress.org/plugins/wordpress-seo/) plugin and enable breadcrumb option to use this shortcode.

With this shortcode you can put Yoast's fantastic breadcrumb feature manually into every post and page or even into custom post types. If your theme supports it, you can use this shortcode in your widget areas with the Text widget.

The shortcode:
`[yoast-breadcrumb]`

There are two parameters for this shortcode:

1. before - The code that your breadcrumb should be prefixed with. Default value: `<div class="breadcrumb" itemprop="breadcrumb">`
2. after - The code that should be added on the back of your breadcrumb. Default value: `</div>`

You can read more informations about Yoast's breadcrumb function here: [Yoast Breadcrumbs – WordPress Breadcrumbs](https://yoast.com/wordpress/plugins/breadcrumbs/)

**Do you want to contribute or help improving this plugin?**

You can find it on GitHub: [https://github.com/Surbma/surbma-yoast-breadcrumb-shortcode](https://github.com/Surbma/surbma-yoast-breadcrumb-shortcode)

**You can find my other plugins and projects on GitHub:**

[https://github.com/Surbma](https://github.com/Surbma)

Please feel free to contribute, help or recommend any new features for my plugins, themes and other projects.

**Do you want to know more about me?**

Visit my webpage: [Surbma.com](http://surbma.com/)

== Installation ==

1. Upload `surbma-yoast-breadcrumb-shortcode` folder to the `/wp-content/plugins/` directory
2. Activate the Surbma - Yoast SEO Breadcrumb Shortcode plugin through the 'Plugins' menu in WordPress
3. That's it. Now you can use the shortcode. :)

== Frequently Asked Questions ==

= What is Yoast SEO Breadcrumb? =

You can read more informations about Yoast SEO's breadcrumb function here: [Yoast Breadcrumbs – WordPress Breadcrumbs](https://yoast.com/wordpress/plugins/breadcrumbs/)

= Can I customize the look of the breadcrumb? =

Yes, you can with css. The default class for this block is breadcrumb, but you can modify the prefix code, as you like.

= Doesn't Yoast SEO plugin have it's own shortcode? =

Yes, it does have a shortcode `[wpseo-breadcrumb]`, but it does not accept any parameters, so my shortcode is a little bit better. :)

= What does Surbma mean? =

It is the reverse version of my last name. ;)

== Changelog ==

= 1.1 =

- Release date: 2019-05-09
- TWEAK - Minor changes in description.
- TWEAK - New shorter version numbering.
- Tested up to WordPress 5.2 version.
- Tested up to Yoast SEO 11.x version.
- Added minimum PHP requirement.

= 1.0.4 =

- Fixed compatibility with Yoast SEO 7.0.x versions.
- Tested up to WordPress 4.9 version.

= 1.0.3 =

- Fixed shortcode's return function. Props to [danieledesantis](https://github.com/Surbma/surbma-yoast-breadcrumb-shortcode/issues/1)
- Tested up to WordPress 4.2 version.
- Description text modifications.

= 1.0.2 =

- Fix localization.
- Prevent direct access to the plugin.

= 1.0.1 =

- Fixed typo.

= 1.0.0 =

- Initial release.
- Added localization.
- First commit to WordPress repo.
