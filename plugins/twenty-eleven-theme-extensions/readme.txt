=== Twenty Eleven Theme Extensions ===
Contributors: Mike Walker
Donate link: http://moztools.com/wordpress/theme2011-plugin/ 
Tags: twenty eleven, 2011, twenty eleven theme, default theme, theme, themes, theme tweaks, theme extensions, theme customization
Requires at least: 3.2
Tested up to: 3.2.1
Stable tag: 1.2

Easy to use customizations for the default theme Twenty Eleven--add sidebars back into your blog pages, and quickly change individual color settings. 
 
== Description ==

Twenty Eleven Theme Extensions is an easy-to-use plugin designed for use with the latest default
WordPress theme, Twenty Eleven. It adds a set of customizable features for the theme, designed to
add more flexibility to the theme's design without having to modify the template files.

The features include: 

* Add the widget sidebar to all pages, and single-post pages.
* Optionally center the navigation links at the top of single-post pages with a sidebar.
* Adjust the alignment of sidebar widget titles.
* Automatically turn sticky posts into single-line headlines to alert your readers of something important.
* Change the default height of the banner image in the header.
* Add custom CSS styles for your site safely, in a place that won't be overwritten when you update WordPress or Twenty Eleven.
* **NEW:** Modify twelve individual theme colors directly from the **Theme Options** admin page.
* **NEW:** If you want to add your own color settings, now you can with the **moz_theme_custom_colors** filter.
* **NEW:** Access the Theme Options and Theme Extensions pages directly from the admin menu bar on your home page.  
* **NEW:** Any child themes using the template **twentyeleven** have all the Theme Extensions available to them.  
 
For more information, go to the [Twenty Eleven Theme Extension Plugin's Home Page](http://moztools.com/wordpress/theme2011-plugin/)
where you will find more documentation for the plugin, and the 
[Twenty Eleven Theme Extensions Plugin Support Forum](http://moztools.com/wordpress/theme2011-plugin/support-forum/).

If you decide to give this plugin a try, please let me know how you get on by
leaving feedback in the [Twenty Eleven Theme Extensions Plugin Support Forum](http://moztools.com/wordpress/theme2011-plugin/support-forum/).
There is no need to register, just click the link and post your comments.

== Installation ==

Installation Instructions:

1. Navigate to the **Plugins** >> **Add New** page in your blog's administration section.
1. Search for **Twenty Eleven Theme Extensions**.
1. Locate the plugin in the list then click **Install** and follow the on-screen instructions. 

Getting Started:   
   
Login to your blog's Site Admin and go to **Appearance** >> **Theme Extensions**, where you can select from the
list of options available. Online help is available for all the options. Just click the question mark icon next
to the option title you need help for. 

When you have changed the settings to your satisfaction, click **Save Changes**
and the requested changes to the theme will be made instantaneously.

== Frequently Asked Questions ==

= Will the plugin work with themes other than Twenty Eleven? =
  
No, the plugin has been designed to work specifically with Twenty Eleven, the current default WordPress theme, and
child themes using Twenty Eleven as their template. Most of the features depend on the HTML and CSS used by this theme, 
and will almost certainly not work with the vast majority of the other themes out there.   
  
= But what if I am using a tailored version of Twenty Eleven? Can I use it then? =

Yes, you can. The plugin checks to see if the current theme is a child of "Twenty Eleven" and also 
looks for the phrase "Twenty Eleven" in the name of the theme, so as long as it is a child theme of "Twenty Eleven" or the theme's name 
(obtained from the styles.css file in the theme directory) contains "Twenty Eleven", the Theme Extensions admin page will be 
active and the extensions will be available to you.

Please note, however, that some or all of the plugin's features may not work with your customized version
of Twenty Eleven. If you, other plugins or child themes have changed something this plugin depends up, then
the results cannot be guaranteed. But the only way to tell if it will work is to give it a try. If something doesn't work,
you can easily disable the option again on the administration page.   

= The color I want to change is not listed in the Theme Options page. How do I change it? =
  
Not all the colors used in Twenty Eleven are configurable from the Theme Options page, but you can 
still use the Theme Extensions plugin to change those that are not listed. 

Look up the CSS styling you need to modify in the **styles.css** file of Twenty Eleven. 
(Click on **Appearance** >> **Editor** and locate **styles.css** near the bottom of the list of 
files on the right.)

Now enable the **Custom CSS** option on the **Theme Extensions** admin page, and enter the 
CSS styles you want set for your blog.

If you have a lot of changes to make, it would be better to create a child theme, and put the
CSS changes in its own **styles.css** file. For more information, see the 
[WordPress Child Theme](http://codex.wordpress.org/Child_Themes) documentation.

Finally, you can add your own color settings using a filter called by the Theme Extensions plugin. For
more information, please see 
[Adding Your Own Custom Color Settings](http://moztools.com/wordpress/theme2011-plugin/adding-your-own-custom-color-settings).
  
= I changed the height of the header image, but the images still come out the same height as before. What's happening? =

There is no automatic way to recrop existing header images to make them smaller. You can turn
on the option to force all images to the new height, but that will distort those images that are taller or shorter than
the new height, which will include all of the preinstalled header images that came with the theme. 

The option is really intended to allow you to upload *new* images (or new copies of existing images) that will be
cropped to the new height. Therefore, if you like the set of header images that comes with the theme, but want
to use smaller versions of them, you will have to download the images to your computer (right click on them and
select "Save Image As") and then use the **Header** theme options page to upload them at the new height. This will
allow you to crop the image just the way you want it.   
  
= What are the minimum versions of software the plugin has been tested with? =

* WordPress: version 3.2 is the minimum the plugin has been tested on
* PHP5: version 5.2.6 (required for WordPress 3.2)
* MYSQL: no dependencies

== Screenshots ==

[Click here](http://moztools.com/wordpress/theme2011-plugin/theme-extensions-admin-page/) 
for screenshots of the Theme Extensions administration page for the plugin.

[Click here](http://moztools.com/wordpress/theme2011-plugin/custom-color-options) 
for a screenshot of the new color settings options added to the Theme Options administration page.

== Known issues ==
 
Enabling the Custom Color settings may override some of the color changes you have already
made to your theme. If that happens, you can always copy the affected color settings to 
the appropriate settings on the Theme Options page. Alternatively, you can keep the Custom
Color option disabled, and none of your changes will be impacted.

== Changelog ==

= Version 1.2 = 

* New feature: a new option allowing you to customize twelve more colors on the Theme Options page.
* New feature: added a WordPress filter to allow others to add or modify the colors that can be edited.
* New feature: access the Theme Options and Theme Extensions pages directly from the admin menu bar on your home page. 
* Now any child theme using the template **twentyeleven** can use the theme extensions.

= Version 1.1.1 =

* Bug fix: adjusted percentage widths slightly to keep right-sidebar in place when sizing the page on IE7.
* Bug fix: added two more CSS styles to correct content positioning problem when using the "Widget Titles" option with left-sidebars.

= Version 1.0 =

* This is the first version.

== Upgrade Notice ==
  
This version is compatible with all previous versions.