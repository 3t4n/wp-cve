=== IFrame Widget ===
Contributors: debashish
Tags: iframe,widget,HTML,iframe-widget
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

IFrame widget can display any external HTML page inside an HTML IFrame component.

== Description ==
The IFrame widget can display any external HTML page inside an [HTML IFrame](http://www.w3.org/TR/html4/present/frames.html#edef-IFRAME "Know more about IFrames") component. The need came from the Hindi Tagcloud JSP that I had once created for [Chittha Vishwa](http://web.archive.org/web/20080821123115/http://www.myjavaserver.com/~hindi "Chittha Vishwa, Hindi for World of Blogs, is the first ever Hindi blog aggregator") and I always thought that there should be some way to display that page on my blog.

= What's new in verson 4.x of this plugin? =
1. The Widget now offers configuration of IFrame Border and Scrolling attributes.
2. You can now have multiple instances of Sidebar Widgets, thanks to the new Widget API to which this plugin has been re-written.
3. A new "Markup Generator" to easily generate the markup that can simply be copy-pasted on your page.

== Installation ==
1. Download and unzip iframe-widget.zip 
1. Upload the folder containing `iframe-widget.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. To add an IFrame on a sidebar, browse to `Appearance > Widgets` and add the 'IFrame Widget" to desired sidebar. Configure the parameters such as IFrame Title, Dimensions, URL etc and save your changes.
1. To add IFrames to any post or page we suggest that you use the Markup Generator (under `Settings > IFrame Markup Generator`) and just copy-paste the generated markup to your Post/Page. More experienced users can just add the markup `[dciframe]url,width,height,frameborder,scrolling,style[/dciframe]`, for instance `[dciframe]http://www.google.com,50%,50%,1,auto,border:1px solid red;[/dciframe]`. Please ensure that your URL doesn't contain any "comma" character. 

== Frequently Asked Questions ==

= How can I  change scroll-bar around the webpage on the IFrame? =

If the dimension of the webpage you are trying to display within the IFrame exceeds the configured dimension of the IFrame (by default) will automatically add scrollbars. This option can be changed, using the "Display Scroll bars?" configuration option, you may chose to either display the scroll-bar always, or not at all or select `auto` to let IFrame decide whether to show the horizontal and/or vertical scroll-bar. If you are using the `[dciframe]` Markup then you may similarly supply the value as the 5th parameters as `[dciframe]http://www.google.com,400,250,1,no,border:1px solid red;[/dciframe]`, for example (note that the fifth parameter value for `scrolling` has been set to "no", thus disabling any scrolling).

= How do I add border to my IFrame? =

To add a border to your IFrame Widget, select the option "Yes" as answer to "Display Frame border?". Then add the required style information in the "CSS Style" field (example: you may add `border:1px solid red;`). If you are using the `[dciframe]` Markup then you may similarly supply the parameters as `[dciframe]http://www.google.com,400,250,1,auto,border:1px solid red;[/dciframe]`, for example (note that the fourth parameter value for `frameborder` has been set to "1").

= How do I add an IFrame to a blog-post or Page? =

To add IFrames to any post or page just add the markup `[dciframe]url,width,height,frameborder,scrolling,style[/dciframe]`, for instance `[dciframe]http://www.google.com,400,250,1,yes,border:1px solid red;[/dciframe]`. 

Note that supplying the URL is mandatory while other parameters are optional; which means that you may specify only the URL or only the URL & width. Therefore, `[dciframe]http://www.google.com,400[/dciframe]` and `[dciframe]http://www.google.com[/dciframe]` are valid tags. Also note that the order of the parameters is important and URL, Width, Height, Frameborder, Scrolling and Style must be separated with commas. Lastly, the closing tag `[/dciframe]` is mandatory.

= Can I add multiple IFrames on a Post or Page? =

Yes you can. Just add multiple `[dciframe]` tags where required when using the markup or drop the "IFrame Widget" on your sidebars, as needed [See the [screen shot](http://wordpress.org/extend/plugins/iframe-widget/screenshots/ "Screenshots") section for a screen-grab of multiple widgets in action]. All of these can be configured independently.

= I don't understand about adding the Markup. Is there a tool available to generate the Markup for me? =

Fortunately there is now a Markup Generator available to help you, just use the Markup Generator under `Settings > IFrame Markup Generator`, click the "Generate Markup" button and copy-paste the markup to your post or page, as simple as that.

== Screenshots ==

1. Configuration for the IFrame Sidebar Widget.
2. Example of IFrame Widget markup being used on a page. 
3. Example of multiple instances of the IFrame Widget & Markups. 
4. Screengrab of the Markup generator page under "Settings > IFrame Markup Generator".

== Changelog ==

= 4.1 =
* Added the Markup generator page to easily generate the markup for simple copy-pasting to page/post. The Markup option now also accepts the Scrolling parameter as its Sidebar widget counter-part.

= 4.0 =
* Rewrite of the plugin to the new Widget API (thus adding the facility to add multiple instances of IFrame sidebar-widgets). New Parameters Style, Frameborder and Scrolling added.

= 3.0 = 
* Bug fix: is_nan changed to is_numeric (Thanks eddan). Paypal link corrected. Tested on Wordpress 2.8.

= 2.0 = 
* New feature: Multiple IFrames can now be added on Wordpress Posts and Pages using the Markup option.

= 1.0 =
* Initial public release.

== Upgrade Notice ==
* We strongly recommend upgrading to V4.x. There are new features to savor. Note that you may loose any configuration of previously set Widgets after the upgrade and would need to re-configure your widgets.