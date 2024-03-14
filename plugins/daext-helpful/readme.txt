=== Helpful - Article Feedback Plugin ===
Contributors: DAEXT
Tags: helpful, feedback, survey, was this helpful, pool, post rating, rate post, rate page, article feedback, user feedback, feedback form, customer feedback
Donate link: https://daext.com
Requires at least: 5.0
Tested up to: 6.4.2
Requires PHP: 5.3
Stable tag: 1.08
License: GPLv3

This plugin lets you easily add a "Was it helpful?" survey on your blog or knowledge base pages.

Use this quick pool to understand if your content resonates with your audience and apply the proper improvements to your website.

== Description ==
This plugin lets you easily add a "Was it helpful?" survey on your blog or knowledge base pages.

Use this quick pool to understand if your content resonates with your audience and apply the proper improvements to your website.

### Pro Version

The [Pro Version](https://daext.com/helpful/) of this plugin is now available with additional features such as the ability to export the feedback data, options to customize the position of the feedback form, and more.

### Why should you use a "Was this helpful? Yes/No" survey to improve your content?

People are willing to share feedback when they do not have to make an effort to open the survey and fill out a lengthy form. Consequently, a simple "Was this helpful? Yes/No" feedback form is ideal for finding issues in your articles.

### How the plugin applies the "Was this helpful?" survey to your WordPress site?

The plugin, using filters, automatically applies the feedback form at the end of the articles that belong to the configured custom post types. The form includes a custom question like "Was this helpful?" (or "Do you find this article helpful?", "Would you recommend it?", etc.), buttons to submit positive or negative feedback, and an optional field that lets the user send a feedback comment.

### Browse the feedback data

The plugin collects the feedback value (positive or negative) and, optionally, a comment from the user. These data are stored in a custom plugin database table and available to you in the Statistics menu or from a dedicated Post sidebar section.

#### Statistics menu

The statistics menu displays the feedback received by the posts. Here you will find essential metrics like the positive feedback ratio and the number of positive and negative feedback. In addition, the plugin will also present details on the single feedback values and feedback comments in a dedicated modal window.

We have also included the ability to filter the feedback data. So you can, for example, quickly find posts that need improvements by displaying only the ones with a specific positive feedback ratio.

#### Post sidebar section

The plugin adds a new dedicated section named "Helpful" in the block editor sidebar. Here you can easily monitor positive and negative feedback while editing the articles.

### Features

#### Limit multiple feedback on the same article
Prevent multiple form submissions by using cookies or by checking the user IP.

#### Apply "Was this helpful?" only to specific custom post types

A dedicated option allows you to apply the "Was this helpful?" survey only to specific post types. Use this option, for example, to collect feedback only on your knowledge base pages and not on your blog articles and pages.

#### Feedback comments

Receive comments from the users. With a dedicated option, you can enable this feature under the following alternative conditions:

* Always
* After a positive feedback
* After a negative feedback
* Never

#### Multiple Rating Buttons Styles

Select between multiple types of rating buttons. Your options include text-only buttons, icons, and text buttons with icons.

#### Customizable SVG for the icons

We included high-quality icons in SVG format to submit the ratings.

Currently, the following SVG icons are available:

* Happy face
* Sad face
* Thumb up
* Thumb down

Note that from the plugin options, you can select your favorite icons and customize the icon's colors.

#### Content customizations

Configure the exact textual content displayed in the form. With this feature, you can, for example, change the "Was this helpful?" question to common variations like "Do you find this article helpful?", "Would you recommend it?"

Here you can also configure the other sentences included in the form. For example, you can encourage the user to leave a feedback comment with sentences like "We're glad that you liked the post! Let us know why.", "How can we make it better?", "We're sorry to hear that. Please let us know how we can improve."

#### Typography customization

Easily configure the font family, font size, font style, font weight, and line height of any textual element displayed in the feedback form.

In addition, you can load custom Google Fonts by including the embed code in a dedicated plugin option.

#### Colors Customizations

You can customize the color of any displayed form element with dedicated options.

#### Spacing

Configure the margin and padding of the form elements with dedicated options.

#### Back-end customizations

Customize the back-end menus of the plugin, for example, by restricting the plugin menus only to users with specific capabilities, configuring a custom number of paginated items, and more.

#### Test mode

Test the "Was this helpful?" form with a dedicated "Test Mode" option before going live.

#### Configure the feedback comment length
Limit the maximum number of characters the users can include in the feedback comment.

#### Enable the form on a per-post basis
Enable or disable the feedback form on a per-post basis with a toggle available in a dedicated section of the block editor sidebar.

#### Other Plugins from us
If you like this plugin, please check out our other projects on [our website](https://daext.com/).

### Credits

This plugin makes use of the following resources:

* [Select2](https://github.com/select2/select2) licensed under the [MIT License](http://www.opensource.org/licenses/mit-license.php)

== Installation ==
= Installation (Single Site) =

With this procedure you will be able to install the DAEXT Helpful plugin on your WordPress website:

1. Visit the **Plugins -> Add New** menu
2. Click on the **Upload Plugin** button and select the zip file you just downloaded
3. Click on **Install Now**
4. Click on **Activate Plugin**

= Installation (Multisite) =

This plugin supports both a **Network Activation** (the plugin will be activated on all the sites of your WordPress Network) and a **Single Site Activation** in a **WordPress Network** environment (your plugin will be activated on a single site of the network).

With this procedure you will be able to perform a **Network Activation**:

1. Visit the **Plugins -> Add New** menu
2. Click on the **Upload Plugin** button and select the zip file you just downloaded
3. Click on **Install Now**
4. Click on **Network Activate**

With this procedure you will be able to perform a **Single Site Activation** in a **WordPress Network** environment:

1. Visit the specific site of the **WordPress Network** where you want to install the plugin
2. Visit the **Plugins** menu
3. Click on the **Activate** button (just below the name of the plugin)

== Changelog ==

= 1.08 =

*January 15, 2024*

* Fixed a block editor error triggered when the "custom-fields" value was not included in the "supports" array of the post type.

= 1.07 =

*September 11, 2023*

* Fixed a CSS issue that prevented the SVG icons from being properly displayed on Safari.
* Fixed PHP warnings.
* Pro version links have been updated.

= 1.06 =

*August 6, 2023*

* The production version of the block editor JavaScript files is now loaded.
* The plugin description in the readme.txt file has been updated.

= 1.05 =

*May 23, 2023*

* The feedback form can now be added with a shortcode.
* The dynamic SVG icons of the form are no longer loaded with file_get_contents() but are stored in PHP variables.
* Other minor improvements.

= 1.04 =

*March 9, 2023*

* Update plugin name and description.
* The "Pro Version" page has been added.

= 1.03 =

*January 25, 2023*

* Initial release.

== Screenshots ==
1. Rating buttons in the front-end.
2. Rating buttons and comment textarea in the front-end.
3. Helpful section in the post editor sidebar.
4. Statistics menu.
5. Statistics menu. (feedback details)
6. Maintenance menu.
7. Options menu in the "Content" tab
8. Options menu in the "Fonts" tab
9. Options menu in the "Colors" tab
10. Options menu in the "Spacing" tab
11. Options menu in the "Analysis" tab
12. Options menu in the "Capabilities" tab
13. Options menu in the "Advanced" tab