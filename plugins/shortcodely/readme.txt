=== Shortcodely ===
Contributors: patilswapnilv
Donate Link: http://swapnilpatil.in/contribute/
Tags: shortcode, widget, page, templates, page template, widget_area, sidebar
Requires at least: 4.0
Tested up to: 5.7.8
Version: 1.0.1
Stable tag: 1.0.1
Licence: GPL-3.0 or later
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Enable the usage of shortcodes almost any where on your website

== Description ==
Insert separate widgets or a entire widget area (sidebar) into a page using a shortcode.

Customize the widget setting in "widgets for shortcodely" widget area.No extra efforts. Exactly as you would do it for any other widget.

 *do_widget shortcode*,will do its magic and display the widget or the even a chosen sidebar from the *do_widget_area* shortcode.

The plugins default action is that it will magically find the settings for your themes first sidebar and use them to control the widget's output.  In most cases this would mean that the widget will be styled as per the rest of your theme.  In case, yout need to change the styling, there are parameters to override it.

Whenever you need to change your theme, the plugin automagically saves and restores the widgets and its settings. It will then select the sidebar setting from the currect theme you are using.

**Instructions:**

1. Make sure you test the widget in the regualr widget area, or any exisitng sidebar.
2. Then Activate shortcodely. (Ignore id already done.)
3. Go to Appearance > Widgets and find the " widgets for shortcodely" sidebar or widget area
4. Add your desired widgets to the Shortcodely side bar. Save.
5. Go the Shortcodely settings.  Click on one of the create page links to help you setup the shortcode.
6. OR go to an existing page and enter a shortcode:

 [do_widget widgetname]   eg: [do_widget calendar]
 [do_widget "widget name"].   eg: [do_widget "tag cloud"]
 [do_widget id=widgetid]

 [do_widget_area]  (will use the  "widgets in shortcodes" widget area / sidebar
 [do_widget_area sidebarname]  for another sidebar or widget area - eg: to maximise likelihood of getting your theme's widget css to apply.


7. If the plugin cannot work out what you want and you are logged in as an administrator, it will show a debug prompt to you, the logged in admin only.
Click on the link 'Try debug'.  It will produce a bunch of info. Look for the id of your widget in the shortcodes sidebar (you may have to scroll through a lot of debug info). Try using the widget id.   Sometimes the widget name that wordpress calls it internally is not the same as what you see on the screen and you will need the 'debug' to find the id.

**[do_widget ...] Parameters:**

*  *nameofwidget* or name="*nameofwidget*"  This is NOT the title of the widget.  THis is the name that you see in the widgets menu page even when unassigned to a sidebar.  For existing users , you can also use just the name of the widget without name=.  It must bethe first parameter then.
*  id=*wordpress assigned id of widget*
*  title=false to hide the widgets title OR to override your themes first sidebar html settings for widget titles, use title=*htmltag* where *htmltag* is one of h1,h2,h3,h4,h5,header,strong,em.  If not entered plugin will use the same title html that your themes first sidebar uses - yes even if you change themes, it will switch to the new themes html.
*  wrap=*htmltag* where *htmltag* is one of div,p,main,aside,section.  This will override your themes first sidebar html settings for widget wrapping html.
*  widget_classes=none  This will remove the class "widget" from the wrapping html and the class "widget_title" from the title html.  This may be enough to remove any unwanted css styling from your theme.
*  class=*yourclassname*  By default the plugin will add a class of amr-widget tto the wrapping html.  You can use this to add any special css.  Add css either by editing your themes stylesheet if it is a custom theme, or using something like the wordpress custom css plugin.
*  sidebar="*sidebarname*"  will default to widgets_for_shortcode sidebar.  Only use if you want to re-use a widget already beingused in another sidebar.  THis is theme dependent and WILL break if you change themes.

**[do_widget_area ...] Parameters:**

*  widget_area=*yourwidgetarea*  defaults to 'widgets_for_shortcodes' if nothing entered
*  widget_area_class=none    /* option to remove theme styling by removing the widget_area class from the sidebar html */
*  widget_classes=none     /* option to remove the widget class from the widget wrappinghtml
*  class=*yourclassname*   default is amr_widget_area'


See the settings page for links to help your create the shortcodes in a page.

To 'remove debug mode'
remove ?do_widget_debug=1 from the url you are looking at (NOTE it only debugs if you are logged in and an administrator, so this is really NOT a problem.)

The plugin has been tested with most standard widgets (rss feeds, tag cloud, pages, meta, search, and of course my own plugins widgets - upcoming events list, calendar and user lists.

If you use a widget more than once for different reasons, you may need to use the widget id to isolate which widget instance and it's settings to use.  ie: [do_widget id=categories-6] .  If you just use the name, it will display all widgets in the shortcode sidebar with that name (all instances).

If you liked this plugin, you might also like my other plugins:

[**Login and Logout redirect**](https://wordpress.org/plugins/login-and-logout-redirect/) - Redirects users to specified url on logging in, logging out OR both.

[**Gravity Pre-submission Confirmation**](https://wordpress.org/plugins/gravity-pre-submission-confirmation/) - adds a pre-submission confirmation page to your Graviy forms where users can preview their entered data before they submit it.



== Changelog ==
= Version 1.0.0 =
*  First stabel release.

== Installation ==

0. Activate plugin
1. Goto Appearance > Widgets and find "shortcode" sidebar
1. Drag chosen widgets to shortcodes sidebar. Save. (note the names)
2. Add [do_widget widgetname] in a page or post  or [do_widget_area]
3. If it fails, click on the debug prompt and look for the id of your widget, use that.

Or  can use [do_widget widgetname] within the text in the page and save.  If the widget name has a space in it, use [do_widget "widget name"].

If you use a widget more than once for different shortcodes, you can use the widget id to isolate which widget instance (and of course associated settings to use).  ie: [do_widget id=categories-6]

do_widget parameters:

* title=false to hide a title
* title= one of h1,h2,h3,h4,h5,strong,em
* class=yourclassname  (else it will add amr_widget)
* wrap=one of div, section, p, aside
* widget_classes=none  (will take the widget class out and the widget-title class out)

do_widget_area parameters:

* widget_classes=none  (will take the 'widget' class out and the widget-title class out)
* widget_area_class=none (will take out the 'widget_area' class )

**Styling**

Check your styling.  The effects are very dependent on how your theme has specified the css that may apply to widgets and sidebars/widget areas.  It may work beautifully and have the content looking like it belongs with your theme.
You may have undesired effects applying that do not work in the main content area.
You may have desired effects not applying because the css is specific to a themes sidebar.

Via the plugin you can do the following to affect styling:
* Remove the general wordpress 'widget' class from the widgets wrapping html and the 'widget_title' from the title html.  ([do_widget *widgetname* widget_classes=none]
* Hardcode away from your themes html for widgets and widget title.  EG: if your theme uses aside and h2, you could specify: [do_widget *widgetname* widget_classes=none wrap=div title=h3].  By default the plugin will use whatever your first sidebar uses.  This will change if you change themes, but only if you have NOT overridden the html with wrap and title.
* Apply an existing class in your theme.  Use [do_widget *widgetname* class=*yourclass*].
* Use the addition class 'amr-widget' provided to specify alternate css in your themes stylesheet or with something like wordpress custom css.




== Screenshots ==


== Frequently Asked Questions ==
