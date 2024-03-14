=== Plugin Name ===
Contributors: scibuff
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=WFXMBAHD523VW
URL: http://wp-connect.tomasvorobjov.com
Tags: facebook, social, sharing, themes, widget, shortcode, template, tags
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 2.0.3c

This plugin integrates the Facebook Social Plugins with Wordpress. 

== Description ==

If you would like to increase the exposure of your blog WordPress Connect 
it the perfect plugin for you. It comes with a unique set of tools through
which you can reach the largest social networking community on the planet 
that would engage and then share and discuss your content. It will enable 
you to build up a fan base and to connect with a wider and a more relevant 
audience. Now your blog readers with a Facebook account can post comments, 
create discussions and like your posts and pages.

The plugin integrates Facebook Social plugins with WordPress. It provides 
tools to add the Facebook Like Button and Facebook Comments automatically 
to every post and page. Of course, the user has a full control over settings 
such as width, font, color scheme, etc. 

Wordpress Connect also provides fully customizable Wordpress Widgets for the 
following Facebook Social Plugins

* Activity Widget
* Comments Widget
* Facepile Widget
* Like Box Widget
* Like Button Widget
* Live Stream Widget
* Login Widget
* Recommendation Widget
* Send Button Widget

The widgets can be added to your blog just as easy as any other Wordpress 
Widget, i.e. through the widgets page (under Appearance on your Dashboard). 

Since 2.0, all Wordpress Connect widgets are rendered in the XFBML mode (i.e 
no iframes) to provide full functionality the iframe versions may lack.

A big addition since 2.0 is a simple yet powerful [API](http://wp-connect.tomasvorobjov.com/api/ "WordPress Connect API") 
for plugin and theme developers who can now specify default settings for Wordpress 
Connect to reflect the needs of their plugin/theme. The API provides an easy 
way to include any widget (such as the Like Button of Comments) anywhere in 
the theme, e.g. to add the Like Button to the header or Comments to the 
footer.

**Requirements**

* PHP 5.2.0 or newer
* WordPress 3.0 or newer

== Installation ==

1. Upload the plugin files to your `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to the "WP Connect" settings (via you administration dashboard) and set 
   the value for Application ID (there is a link to a Facebook page where 
   you can create those).

== Frequently Asked Questions ==

Below are asnwers to most common questions. If you have a question which is not 
answered here, please ask your question on the [support forums](http://wordpress.org/tags/wordpress-connect/ "WordPress Connect Support Forums"). 
I monitor those and will try to answer as soon as possible. 

= How does it work =

Using Wordpress Connect is very easy. Simply install and activate the plugin. 
The default settings will ensure that you can start benefiting from the 
features of Wordpress Connect right away. By default, Like Buttons will appear 
at the top of all posts and pages (just before the content) and Comments will 
appear at the bottom of your posts and pages (right after the content). All 
widgets are pre-configured with Facebook default values and are available from 
the Widgets Menu for an immediately use. 

= I have upgraded to 2.0 and some/all of my previous settings are gone =

Version 2.0 of WordPress connect offers greatly extended functionality, more 
features and more control over the social plugins. Unfortunately, as a direct 
consequence, a few settings from previous version(s) are incompatible and will 
need to be entered as again via the settings pages. 

Furthermore, Facebook has rolled out many new features in March 2011 and
consequently the format and xfbml markup of some of their social plugins has 
changed. New options on WordPress Connect setting pages reflect these changes, 
but unfortunately, it was not possible to keep every single setting from 
previous versions.

If you notice any unexpected behavior, please double check the values set 
on the WordPress Connect settings (available through your administratior 
dashboard). Additionally, please consult the [documentation pages](http://wp-connect.tomasvorobjov.com/docs/ "Wordpress Connect Documentation").

= Cannot access comments moderation =

After adding the comments plugin to your blog and creating a new [Facebook Application](http://developers.facebook.com/setup/) 
clicking on "Settings" on the comments box displays the following error: 
> Cannot edit global settings or moderation mode without fb:app_id

To solve this problem, you will need to add the domain of your blog 
to the Facebook Applpication settings:

1. Go to [My Apps](http://www.facebook.com/developers/apps.php "My Apps on Facebook")
2. Select the application your created for your blog/site (from the list of applications on the left side)
3. Click on 'Edit Settings' in the list of links on the right side (next to application details)
4. Click on 'Web Site' (the second link from the top)
5. Fill in the 'Site Domain' field. If your blog is at http://www.myblog.com enter just 'myblog.com'
6. After clicking on 'Save Changes' and reloading the page with the comments on it, you should be able to access the moderation settings

Please visit the [FAQ pages](http://wp-connect.tomasvorobjov.com/faq/#comments-moderation-access "WordPress Connect FAQ") 
for a more detailed walk-through of the process described above (including screenshots)

= What is the Application ID for? =

The Facebook Javascript API require a unique application ID to work. A new 
ID can easiliy be created by registering your site/blog at 
http://developers.facebook.com/setup/

= How do I change the position of the Like Button/Comments? =

The default position of the Like Button and Comments can be changed from the 
WP Connect options pages accessible through the dashboard. If you would 
like to change the position of the Like Button of Comments in a particular 
post or page, simply go to edit the post/page and select the new position 
from the Like Button and Comments position drop-downs (or the right-hand side, 
in the "Wordpress Connect" module).

For more details about customizing the position and the settings of individual 
Like Buttons and Comments see the [documentation page](http://wp-connect.tomasvorobjov.com/docs/ "WordPress Connect Documentation") 
and [FAQ pages](http://wp-connect.tomasvorobjov.com/faq/ "WordPress Connect FAQ").

= Do the widgets work with WP Super Cache and other caching plugins? =

Yes, the content in all widgets will update every time a user visits your site, 
even if the page (in which the widgets are embedded) remains unchanged. 
This is because Facebook delivers content to widgets either via asynchronous 
Javascript calls (xfbml), i.e. content is requested after every page load.

= The widgets I place in my sidebar are cut-off =

Some Facebook Social Plugins have a minimum width below which they will not 
resize any further (but the will rather get cut-off). For example, 
the comments box needs to be at least 400px wide. If the container into which 
you are placing the widget is not wide enough, the content of the widget will 
not display properly. The usual sidebar that comes with most themes is too 
narrow for widgets such as the comments box. The only solution is to place the 
widget into a widget area that is wide enough, such as the 'Footer Widget 
Area' of the defeault twenty ten theme.

= The plugin doesn't work in my favorite theme =

If the plugin doesn't work in one theme but does in others, the most 
likely reason is that the theme itself doesn't implement Wordpress hooks 
properly. For everything to work correctly, the plugin needs to add code into 
the html head tag (wordpress header) and the footer of the blog pages. 
It uses wordpress hooks (`add_action` and `add_filter`) for this purpose. 
If the theme author has not included the common wordpress hooks into the 
theme, required code cannot be added into the pages. 

You should contact the theme author and ask him/her to implement the 
wp_head and wp_footer action calls correctly.  

= I updated the plugin to the latest version but don't see the changes =

If you are using any caching solution, such as WP Cache or WP Supercache, 
you'll need to reset the cache, i.e. to delete the cached pages. This is 
necessary so that the xfbml markup values can be updated. 

= Facebook Social Plugins Info =

More information about the plugins can be found back on Facebook at 
http://developers.facebook.com/docs/reference/plugins/

= I set an image as the feature image but it doesn't show on Facebook =

The theme you are using must support `post-thumbnails` for this particular
feature to work.

= FAQ =

See the [FAQ pages](http://wp-connect.tomasvorobjov.com/faq/ "WordPress Connect FAQ") 
for more info and details about Wordpress Connect Plugin

== Screenshots ==

1. Settings page - General plugin settings: The Application ID and Application Admins field will need to have proper values filled in. Here, the Image URL is set to the logo of the [WordPress Connect Facebook Page](http://www.facebook.com/pages/Wordpress-Connect/203079109734244) to demo how to option appears when a valid image URL is saved - note that you can use the URL of any image you upload to your WordPress blog (simply copy and paste the url).
2. WordPress Connect in action - The Like Button and Comments are displayed using the default options (set when the WordPress Connect plugin is activated or updated from a previous version). Here the Like Button is shown immediately  after the post (article) title (the position is set to `Top` by default) and the Comments box is at the end after the post's content (the position is set to `Bottom` by default).
3. Using WordPress shortcodes to customize the Like Button and Comments - The Like Button and the Comments positions are set to `Custom`. Setting the position value to `Custom` prevents the plugin from automatically adding the Like Button and Comments in their usual place; the shortcodes will be replaced with the appropriate HTML markup even if the position is set to `Top` or `Bottom` but in that case another Like Button and/or Comments would appear at that position within the post). Leaving the `href` options empty will automatically use the permalink of the current post (or page) when the Like Button and/or Comments are rendered. The colorscheme value is set to `dark`. Also notice, that the `href` option of the second Like Button (at the end of the post) is set to a completely different URL (nhl.com). Theoretically, you can add as many Like Buttons (and even Comments) into a single post as you would like. 
4. Custom Like Buttons and Comment box (based on the values set in screenshot 3) rendered from shortcodes. The top button links to the current post/page but the bottom button is a for a different page hosted on a completely different domain. 
5. Admin Dashboard - Widgets: WordPress Connect provides WordPress Widget for every single of the Facebook Social Plugins. Simply drag-n-drop a widget in one of the widget areas and set the appropriate options (or leave in the defaults). 
6. Widgets: Send Button and Comments in the footer widgets area (content's height is shrunk to fit in the footer for a better demo view)
   
== ChangeLog ==
= 2.0.3c =
* Fixes an error with the Like button ($show_faces_value undefined)
= 2.0.3b =
* Fixes an XSS vulnerability in the editor pages
= 2.0.3 =
* Fixes encoding of non-latin characters in description and title
  of the og meta tags
* Fixes the bug with the description meta tag that would print just the
  first letter of the content followed by a space and the ellipsis.
* Fixes a rare bug `Division by zero ... on line 220` for posts/pages with
  featured images
= 2.0.2 =
* Fixes the bug that like button display settings are not saved correctly
  after saving them
= 2.0.1 =
* Fixes the bug that non-admin users can no longer log in and access
  the dashboard
* Fixes the problem with api functions not defaulting to the current 
  post/page url when an empty string is passed in as the url parameter
* Change Facebook initialization to load the Facebook Javascript SDK 
  asynchronously so that other elements/modules on pages are not blocked
  while the social plugins are loading
* Spelling fixes (Homapage -> Homepage, etc.) 
= 2.0 =
* API for plugin and theme developers
	* template tags
	* hooks 
* New settings pages with more options 
* Greater control and flexibility 
* New widgets with additional customization options
* Shortcodes for placing the Like Button and Comments anywhere within
  the body of your posts/pages
* Editor buttons (WYSIWYG) for Like Button and Comments 
* Ability to disable/enable and reposition the Like Button and Comments for
  individual posts and pages
* Light and dark colorscheme
* Automated description meta tag based on the currently display page (home,
  category, tag, search, single, etc)
* Ability to set the share image (the og:image meta tag) to the featured image
  of the post/page - The image must still be at least 50px by 50px and have a 
  maximum aspect ratio of 3:1 (if the image does not fulfill the requirements, 
  the default image will be used instead)
* New documentation and support pages
* Improved [FAQ](http://wp-connect.tomasvorobjov.com/faq/ "WordPress Connect FAQ")
  and [Support](http://wordpress.org/tags/wordpress-connect "WordPress Connect Support") (professional support available on request)
* Thorough API [documentation](http://wp-connect.tomasvorobjov.com/api/ "Wordpress Connect API").
* Professional Support available

= 1.09 =
* Added a like button to the comments box (iframe only for now)

= 1.07 =
* Fixes the problem that comments no longer appear after facebook plugins have
  been updated

= 1.05 =
* Adds the option to force the Comments and the Like Button to appear on a new
  line in case posts' content isn't enclosed in a block element.
* Compatibility up to Wordpress 3.0

= 1.03 =
* Adds options to control on which pages comments and like button appear, i.e.
  it is possible to disable the comments and the like button on the homepage,
  pages and categories (they always appear in single posts) - see screenshot #5.

= 1.02 =
* Fixes the issue with the Like Button within the Comments plugin

= 1.01 =
* Update to the plugin package to include all necessary files
* Added Facebook Connect languages 

= 1.0 =
* The initial release of this plugin. Due to a failed svn update this version of the 
  plugin does NOT deliver any functional widgets or modules.
  
== Upgrade Notice ==
= 2.0.3 =
* Minor bug fixes (featured image, og:description, etc.)
= 2.0.2 = 
* Options bug fix
= 2.0.1 =
* Bug fixes
* Facebook Javascript SDK asynchronous loading
= 2.0 =
* The 2.0 version of Wordpress Connect brings the newest features of Facebook
  Social plugins such as light/dark theme for the comments box, threaded 
  comments and many features requested from versions 1.x such as 
  enabled/disable the like button or comments for indivual posts/pages, 
  choosing the place within a particular post/page where the FB plugins should
  appear (a completely custom location can be set so that the like box can
  appear in the middle of the post if that is desired), etc.
   
* This latest version provides API for plugin and theme developers to include
  facebook social plugins automatically within their theme/plugins.

= 1.05 =
* Adds the option to force the Comments and the Like Button to appear on a new
  line in case posts' content isn't enclosed in a block element.
* Compatibility up to Wordpress 3.0

= 1.03 =
* Adds options to control on which pages comments and like button appear, i.e.
  it is possible to disable the comments and the like button on the homepage,
  pages and categories (they always appear in single posts)

= 1.02 =
* Fixes the issue with the Like Button within the Comments plugin

= 1.01 =
* The first fully functional version (1.0 release failed due to SVN issue)