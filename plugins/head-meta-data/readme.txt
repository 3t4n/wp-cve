=== Head Meta Data ===

Plugin Name: Head Meta Data
Plugin URI: https://perishablepress.com/head-metadata-plus/
Description: Adds a custom set of &lt;meta&gt; tags to the &lt;head&gt; section of all posts &amp; pages.
Tags: meta, metadata, header, author, publisher
Author: Jeff Starr
Author URI: https://plugin-planet.com/
Donate link: https://monzillamedia.com/donate.html
Contributors: specialk
Requires at least: 4.6
Tested up to: 6.5
Stable tag: 20240301
Version:    20240301
Requires PHP: 5.6.20
Text Domain: head-meta-data
Domain Path: /languages
License: GPL v2 or later

Adds a custom set of &lt;meta&gt; tags to the &lt;head&gt; section of all posts &amp; pages.



== Description ==

Head Meta Data (HMD) improves the definition and semantic quality of your web pages by adding a custom set of `<meta>` tags to the `<head>` section of your web pages.


**Basic Example**

Display any set of custom meta tags for your site:

	<head>
		
		<meta charset="utf-8">
		<meta name="abstract" content="Obsessive Web Development">
		<meta name="author" content="Perishable">
		<meta name="classification" content="Website Design">
		<meta name="copyright" content="Copyright Perishable Press - All rights Reserved.">
		<meta name="designer" content="Monzilla Media">
		<meta name="language" content="EN-US">
		<meta name="publisher" content="Perishable Press">
		<meta name="rating" content="General">
		<meta name="resource-type" content="Document">
		<meta name="revisit-after" content="3">
		<meta name="subject" content="WordPress, Web Design, Code & Tutorials">
		<meta name="template" content="Awesome Theme">
		<meta name="robots" content="index,follow">
		
		<!-- plus you can add your own custom tags! -->
		
	</head>


**SEO Example**

Use shortcodes to dynamically output SEO meta tags:

	<title>[hmd_post_title] | Your Awesome Website</title>
	<meta name="description" content="[hmd_post_excerpt]">

This outputs dynamic titles and descriptions, useful for SEO. You also can add custom meta tags and markup on specific posts and pages. Check out [Installation](https://wordpress.org/plugins/head-meta-data/#installation) for more infos.


**Features**

* Simple and easy to use
* Clean, standards-based code
* Customize all `<meta>` tags
* Add your own custom `<meta>` tags
* NEW: Add custom tags on any post or page
* Use shortcodes to include dynamic information
* Includes meta tags on all of your site's web pages
* Check out a Live Preview of your meta tags & custom content
* Automatically adds tags to the `<head>` section of all pages
* Auto-populates tags using your site's information
* Choose HTML or XHTML format for meta tags
* Supports Twitter Cards and Facebook Open Graph tags
* Easily disable any unwanted tags
* Option to reset default settings
* Works with or without Gutenberg Block Editor
* Regularly updated and "future proof"
* Lightweight, fast, and secure

> Pro version coming soon! :)


**Shortcodes**

Shortcodes enable you to include dynamic bits of information in your meta tags. Head Meta Data currently provides the following shortcodes:

* `[hmd_post_excerpt]` -- post excerpt
* `[hmd_post_date]` -- post date
* `[hmd_post_author]` -- post author name
* `[hmd_post_title]` -- post title
* `[hmd_post_cats]` -- post categories
* `[hmd_post_tags]` -- post tags
* `[hmd_year]` -- current year
* `[hmd_tab]` -- adds tab space to markup

So you can display your own set of customized meta tags exactly as desired. See the [Installation section](https://wordpress.org/plugins/head-meta-data/#installation) for more information about the HMD shortcodes!


**More Info**

Head Meta Data is designed to complete a site's head construct by including some of the more obscure meta tags, such as "author", "copyright", "designer", and so forth. As a matter of practicality, the more widely used tags such as "description" and "keywords" have been omitted, as they are already included via wide variety of plugins (such as "All in One SEO") in a more dynamic way. Even so, adding "description", "keyword", or any other tags is easy from the plugin's settings page.


**Privacy**

This plugin does not collect or store any user data. It does not set any cookies, and it does not connect to any third-party locations. Thus, this plugin does not affect user privacy in any way.

Head Meta Data is developed and maintained by [Jeff Starr](https://twitter.com/perishable), 15-year [WordPress developer](https://plugin-planet.com/) and [book author](https://books.perishablepress.com/).


**Support development**

I develop and maintain this free plugin with love for the WordPress community. To show support, you can [make a donation](https://monzillamedia.com/donate.html) or purchase one of my books: 

* [The Tao of WordPress](https://wp-tao.com/)
* [Digging into WordPress](https://digwp.com/)
* [.htaccess made easy](https://htaccessbook.com/)
* [WordPress Themes In Depth](https://wp-tao.com/wordpress-themes-book/)
* [Wizard's SQL Recipes for WordPress](https://books.perishablepress.com/downloads/wizards-collection-sql-recipes-wordpress/)

And/or purchase one of my premium WordPress plugins:

* [BBQ Pro](https://plugin-planet.com/bbq-pro/) - Super fast WordPress firewall
* [Blackhole Pro](https://plugin-planet.com/blackhole-pro/) - Automatically block bad bots
* [Banhammer Pro](https://plugin-planet.com/banhammer-pro/) - Monitor traffic and ban the bad guys
* [GA Google Analytics Pro](https://plugin-planet.com/ga-google-analytics-pro/) - Connect WordPress to Google Analytics
* [Simple Ajax Chat Pro](https://plugin-planet.com/simple-ajax-chat-pro/) - Unlimited chat rooms
* [USP Pro](https://plugin-planet.com/usp-pro/) - Unlimited front-end forms

Links, tweets and likes also appreciated. Thanks! :)



== Installation ==

**Installation**

1. Upload the plugin to your blog and activate
2. Visit the settings to configure your options

[More info on installing WP plugins](https://wordpress.org/support/article/managing-plugins/#installing-plugins)


**Shortcodes**

Shortcodes enable you to include dynamic bits of information in your meta tags. Head Meta Data currently provides the following shortcodes:

* `[hmd_post_excerpt]` -- post excerpt
* `[hmd_post_date]`    -- post date
* `[hmd_post_author]`  -- post author name
* `[hmd_post_title]`   -- post title
* `[hmd_post_cats]`    -- post categories
* `[hmd_post_tags]`    -- post tags
* `[hmd_year]`         -- current year
* `[hmd_tab]`          -- adds tab space to markup

So you can include any of these shortcodes in the "Custom Content" setting, and the displayed information will reflect the current post being viewed. On non-post views, like the homepage, archives, and search results, the shortcodes display general information about the site:

* `[hmd_post_excerpt]` -- site description
* `[hmd_post_date]`    -- date of latest post
* `[hmd_post_author]`  -- site name
* `[hmd_post_title]`   -- site description
* `[hmd_post_cats]`    -- all view categories
* `[hmd_post_tags]`    -- all view tags

For example, we could add this meta tag via the "Custom Content" setting:

	<meta name="date" content="[hmd_post_date]">
	<meta name="abstract" content="[hmd_post_title]">
	<meta name="author" content="[hmd_post_author]">
	<meta name="copyright" content="Copyright [hmd_year] Awesome Website">

Another example: use shortcodes to dynamically output SEO meta tags:

	<title>[hmd_post_title] | Your Awesome Website</title>
	<meta name="description" content="[hmd_post_excerpt]">

This outputs dynamic titles and descriptions, useful for SEO.

If you have ideas for other shortcodes, [let me know](https://perishablepress.com/)!


**Custom meta tags**

Using this method, you can add custom meta tags to the &lt;head&gt; section of any post or page. Follow these steps:

1. Visit the "Edit Post" or "Edit Page" screen
2. Open the "Custom Fields" meta box
3. Add a new custom field
4. For the custom field Name, enter `head-meta-data`
5. For the custom field Value, enter any tag/markup
6. Save changes and done.

For example, for the custom field value, we could enter the following markup:

`<link rel="icon" type="image/png" href="/android.png" sizes="192x192">`

After saving changes that exact code will be included in the post's &lt;head&gt; section.

__Tip:__ You can include tab spaces in the custom field value, for example:

`		<link rel="icon" type="image/png" href="/android.png" sizes="192x192">`

Useful for indenting code to align with other meta tags ;)

__Tip:__ By default, any custom tags are displayed _after_ the default meta tags. To disable the default meta tags on any post, add a new custom field with the name `hmd_disable_default`. Give it a value of `1`. Save changes and done.


**Upgrades**

To upgrade Head Meta Data, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 


**Restore Default Options**

To restore default plugin options, either uninstall/reinstall the plugin, or visit the plugin settings &gt; Restore Default Options.


**Like the plugin?**

If you like Head Meta Data, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/head-meta-data/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**Uninstalling**

Head Meta Data cleans up after itself. All plugin settings will be removed from your database when the plugin is uninstalled via the Plugins screen.



== Upgrade Notice ==

To upgrade Head Meta Data, remove the old version and replace with the new version. Or just click "Update" from the Plugins screen and let WordPress do it for you automatically.

__Note:__ uninstalling the plugin from the WP Plugins screen results in the removal of all settings from the WP database. 



== Screenshots ==

1. Head Meta Data: Plugin Settings (panels toggle open/closed)

More screenshots available at the [HMD Homepage](https://perishablepress.com/head-metadata-plus/).



== Frequently Asked Questions ==

To ask a question, suggest a feature, or provide feedback, [contact me directly](https://plugin-planet.com/support/#contact).



== Changelog ==

If you like Head Meta Data, please take a moment to [give a 5-star rating](https://wordpress.org/support/plugin/head-meta-data/reviews/?rate=5#new-post). It helps to keep development and support going strong. Thank you!


**20240301**

* Fixes deprecated warning in PHP 8.2 (Thanks [@knofte](https://wordpress.org/support/topic/php-8-2-deprecated-warnings-fix/))
* Updates plugin settings page
* Updates default translation template
* Improves plugin docs/readme.txt
* Tests on WordPress 6.5 (beta)


Full changelog @ [https://plugin-planet.com/wp/changelog/head-meta-data.txt](https://plugin-planet.com/wp/changelog/head-meta-data.txt)
