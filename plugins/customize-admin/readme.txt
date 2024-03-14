=== Customize Admin ===
Contributors: vanderwijk
Author URI: https://vanderwijk.com/
Donate link: https://www.paypal.me/vanderwijk
Tags: custom, admin, customize, logo, login, dashboard, css, meta
Requires at least: 3.5
Tested up to: 6.4
Stable tag: 1.9.1

With this plugin you can use customize the appearance of the WordPress login page, dashboard and head section tags.

== Description ==

Customize Admin is a powerful WordPress plugin that puts you in control of your website's login page and dashboard layout. With its intuitive features, you can effortlessly tailor the look and feel of your WordPress login page and dashboard, creating a seamless and branded experience for your users.

Key Features:

**Personalized Login Screen:** Easily upload your custom image or logo to the login screen and define the link associated with the logo. Take your brand identity to the forefront and leave a lasting impression on your visitors from the very first interaction.

**Streamlined HTML:** Trim down and fine-tune your website's HTML source code by selectively removing meta tags from the head section. Enjoy better control over your site's code and optimize it for improved performance and SEO.

**Dashboard Widget Management:** Take charge of your dashboard widgets! Customize Admin enables you to disable selected widgets, allowing you to declutter and streamline your WordPress dashboard effortlessly.

Want to see the magic of Customize Admin in action? Check out the [plugin homepage](http://www.vanderwijk.com/wordpress/wordpress-customize-admin-plugin/) for more information and an enlightening screencast video that showcases the plugin's capabilities.

== Screenshots ==

1. You can specify the logo image and clickthrough link on the options page. It is also possible to disable the generator meta tag and specified dashboard widgets.

== Installation ==

1. First you will have to upload the plugin to the `/wp-content/plugins/` folder.
2. Then activate the plugin in the plugin panel.
If you have manage options rights you will see the new Custom Admin Settings menu.
3. Specify a clickthrough url for the logo if required.
4. Specify the url for the custom logo. The path can be relative from the root or include the domain name.
5. If you have not yet uploaded a logo, you can do so via the Upload Image button. Make sure you click 'Insert into Post'. For the best result, use an image of maximum 67px height by 326px width.
6. Click Save Changes.

== Frequently Asked Questions ==

= Why did you make another admin logo plugin?  =

There are already quite a few plugins that offer similar functionality, but the fact that my plugin uses the WordPress Media Library makes it super easy to upload and edit your own logo.

I also am not aware of any other plugins that allow you to specify a clickthrough url for the logo. 

Finally, this plugin is ready to be localized. All you have to do is to use the POT file for translating.

== Changelog ==

= 1.9.1 =
WordPress v6.4 compatibility tested.

= 1.9.0 =
WordPress v6.3 compatibility tested. Cleaned up options page.

= 1.8.2 =
WordPress v6.0 compatibility tested.

= 1.8.0 =
WordPress v5.8 compatibility tested.

= 1.7.5 =
Title attribute revoved from logo on login page because login_headertitle is deprecated since version 5.2.0.

= 1.7.4 =
Changed the default css for the logo image from `background-size: auto auto` to `background-size: contain`

= 1.7.3 =
Fixed a conflict with the theme customizer, thank you Freddy for reporting this.

= 1.7.2 =
Added sanitize_hex_color to color picker field to prevent logged-in users from saving anything else than a HEX color value. Thanks to Dan at [Wordfence](https://www.wordfence.com/) for alerting me to this potential issue.

= 1.7.1 =
Updated the nl_NL translation file

= 1.7 =
The plugin is now using the media uploader which was introduced in WP 3.5

= 1.6.6 =
Changed dashboard widget visibility settings for WordPress 3.8 widget changes

= 1.6.4 =
WordPress 3.8 compatibility fixes

= 1.6.1 =
Added an option for removing the RSS feed links from the head section of the html source.

= 1.6 =
The Customize Admin plugin now includes the possibility to select a background color for the login screen by using the color picker and you can now also add custom CSS code to style the WordPress login screen.

= 1.5.1 = 
Changed get_bloginfo('siteurl') to get_bloginfo('url') to prevent notices from being displayed on the login screen when debug is enabled.

= 1.5 =
Added option to remove dashboard RSD and WLW meta tags and image size fix for login logo

= 1.4 =
Added option to remove selected dashboard widgets and a fix for an issue that was introduced by WordPress 3.4 which put the title tag value of the logo in the head section of the html.

= 1.3 =
Added option to remove generator meta tag from the head section of the html source.

= 1.2 =
Code cleanup, inclusion of [option to remove the admin shadow](http://www.vanderwijk.com/updates/remove-wordpress-3-2-admin-shadow-plugin/) which was introduced in WordPress 3.2.

= 1.1 =
Minor update, moved the Customize Admin Options page to the Settings menu.

= 1.0 =
First release

== Upgrade Notice ==
