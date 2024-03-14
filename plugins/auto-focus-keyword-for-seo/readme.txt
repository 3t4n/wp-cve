=== Auto Focus Keyword for SEO ===
Contributors: the-rock, pagup, freemius
Tags: Yoast SEO, Rank Math, Focus Keyword, Meta Tag Keyword, Search Engines
Requires at least: 4.1
Requires PHP: 5.6
Tested up to: 6.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will assign Focus Keywords to all your pages (on the backend) based on post titles, for websites using Yoast SEO and Rank Math.

== Description ==

The "Focus Keyword" feature of Yoast SEO and Rank Math is a dynamic backend tool that allows the optimization of a page based on a central query, with the aim of maximizing its understanding by search engines and generating consistent SEO. This "Focus Keyword" will also be deployed as a "Meta Tag keyword" on the frontend, in the HTML code of your website.

The "Focus Keyword" feature (or "Primary Keyword") allows users to specify a target keyword or phrase for each article or page of their website. The goal is to optimize the content around this keyword to improve the chances of ranking in search engines for that specific query.

Once you have defined your primary keyword, these plugins analyze the content of your page and provide recommendations to improve your SEO. They check if the target keyword is present in essential elements of the page, such as the title, meta description, heading tags (H1, H2, etc.), body content, and image alt attributes. They also indicate whether you need to add or remove instances of the keyword for better optimization.

By using the "Focus Keyword" feature of Yoast SEO or Rank Math, you can refine your content to align with the best SEO practices and increase your chances of being well-ranked in search engine results for your target keyword.

**The role of the Auto Focus Keyword plugin**

The fact is that it can sometimes be challenging to determine which keywords to use or to optimize for all the pages/articles/products of a website.

That's where the plugin comes in: **Auto Focus Keyword for Yoast SEO & Rank Math**.

The "Auto Focus Keyword" plugin has been specially designed to work seamlessly with the Yoast SEO and Rank Math plugins, two of the most popular SEO plugins on WordPress. This seamless integration allows users to benefit from advanced optimization features without encountering any issues.

When you use the "Auto Focus Keyword" plugin in conjunction with Yoast SEO or Rank Math, you can fully leverage the potential of these plugins to improve your SEO and optimize your content. Here's how this synergy works:

This plugin will allow you to automatically add a focus keyword corresponding to the title of your page/article/product in the backend of your website using Yoast SEO and Rank Math. This means you don't need to manually enter each target keyword, as the plugin does it for you. This feature saves you time and ensures consistency in optimizing your content.

Once installed, you simply need to select the parts of your website that will be affected by its execution (Post types). If necessary, you can identify the pages/URLs that are not relevant for this process and then initiate a scan ("FETCH") to identify the pages on the website that do not have any focus keyword identified in the backend via Yoast SEO or Rank Math.

Once done, you just need to click on "SYNC," and all these pages will have the focus keyword populated with the page title.

By combining the "Auto Focus Keyword" plugin with Yoast SEO and Rank Math, you have a comprehensive SEO optimization solution. You can automate the process of adding targeted keywords, improve the consistency of your optimization, and fully utilize the advanced features of Yoast SEO and Rank Math. This synergy between the plugins ensures optimal SEO for your site without any conflicts or technical issues.

**The PRO version allows you to:**

- Continuously run the process of adding Focus Keywords to any new content created on the website, for which the Focus Keyword has not been identified at the time of publishing.
- Extend the optimization process to your product pages.

**Why use the "Auto Focus Keyword" plugin?**

There are several ideas behind the creation of this plugin, including:

- Save you time.

When it comes to optimizing your website using tools like Yoast SEO or Rank Math, especially for a large content structure (such as an online store), it can be tedious to optimize everything diligently for search engines. However, this will no longer be a problem with this plugin.

The plugin will also help you quickly identify pages that require special attention, thanks to the color-coded "dots" provided by Yoast SEO. This is because a Focus Keyword, which is an optimization target, will be defined.

Furthermore, in the case of an online store, the optimization target is often the product name. Once again, the plugin will automatically add the page title (i.e., the product name) as the Focus Keyword.

- Enable the use of other SEO plugins.

By automatically adding a Focus Keyword to all your pages, you can take advantage of various other SEO plugins that utilize the Focus Keyword as an optimization tool.

For example, the "BIALTY" plugin used to optimize Alt tags for images can now deploy Alt tags across your entire site since its execution is directly tied to the existence of Focus Keywords. [See here](https://wordpress.org/plugins/bulk-image-alt-text-with-yoast/)

The same applies to the "BIGTA" plugin used to optimize Title Text for images, as it also utilizes the Focus Keywords from Yoast SEO or Rank Math. [See here](https://wordpress.org/plugins/bulk-image-title-attribute/)

Lastly, and this is a significant advantage, deploying Focus Keywords on all your pages allows you to implement the most effective internal linking strategy on your site using "[Auto links for SEO](https://wordpress.org/plugins/automatic-internal-links-for-seo/)" another plugin developed by us at PAGUP.com. It uses Focus Keywords as anchor text for link creation.

- Understand how SEO works.

By combining the use of these plugins and optimizing your Focus Keywords, you will understand the importance of creating consistency in your content to maximize search engine comprehension.

Enjoy !

== Installation ==

= Installing manually =

1. Unzip all files to the `/wp-content/plugins/auto-focus-keyword-for-seo` directory
2. Log into WordPress admin and activate the 'Auto Focus Keyword for Yoast SEO and Rank Math' plugin through the 'Plugins' menu
3. Go to "Auto Focus Keyword" in the left-hand menu to start work on it.

== Screenshots ==

1. Auto Focus Keyword for Yoast SEO and Rank Math
2. Auto Focus Keyword for Yoast SEO and Rank Math

== Changelog ==

= 1.0.0 =
* Initial release.

= 1.0.1 =
* 🐛 FIX: WP version
* 👌 IMPROVE: Updated Freemius SDK to v2.5.12

= 1.0.2 =
* 🐛 FIX: Critical error because of array sort function
