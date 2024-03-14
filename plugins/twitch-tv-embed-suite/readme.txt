=== Twitch TV Embed Suite ===
Contributors: plumwd
Donate link: http://www.plumeriawebdesign.com
Tags: live stream, twitch tv, gaming
Requires at least: 2.0.9
Tested up to: 4.5.3
Stable Tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Twitch TV Embed Suite allows easy placement of a twitch tv stream and/or chat anywhere on your WordPress site.  
== Description ==

Twitch TV Embed Suite is a plugin that allows for fast and easy embedded of twitch tv stream and chat on your WordPress site. The plugin features an easy to configure 
settings area that allows the user to preview the stream prior to placement on your site.

* Specify stream width and height
* Decide whether or not to show chat
* Specifiy chat width and height

== Installation ==

1. Download and unzip the file.
2. Place the entire contents of the directory into your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==

1. Settings
2. Widget 
3. Stream with Chat 
4. Stream Icon for WYSIWYG Editor
4. Chat Icon for WYSIWYG Editor


== Shortcode Usage ==

1. To add a stream to your posts, pages, or widgets use the following code. Ensuring you set the Twitch channel name, height, and width if differing from the default settings:
    [plumwd_twitch_stream channel="twitchchannelname" height="400" width="600"]
2. To add a stream to your WordPress theme use the following code inside your template: `echo do_shortcode('[plumwd_twitch_stream channel="twitchchannelname" height="400" width="600"]');`
3. Shortcode options are:
    channel - *twitch channel name*
    width - *width in pixels or percent. If using percent must use % sign.*
    height - *height in pixels or percent. If using percent must use % sign.*
4. Use the icon from the WYSIWYG editor to insert into a post or page.

1. To add twitch chat to your posts, pages, or widgets use the following code. Ensure you set the Twitch channel name, height, and width like with the stream shortcode.
    [plumwd_twitch_chat channel="twitchchannelname" height="400" width="600"]
2. To add chat to your WordPress theme use the following code inside your template: `echo do_shortcode('[plumwd_twitch_chat channel="twitchchannelname" height="400" width="600"]');`
3. Shortcode options are:
    channel - *twitch channel name*
    width - *width in pixels or percent. If using percent must use % sign.*
    height - *height in pixels or percent. If using percent must use % sign.*
3. Use the icon from the WYSIWYG editor to insert into a post or page.

1.To add a Twitch TV stream list to your posts, pages, or widgets use the following shortcode:
  [plumwd_twitch_streamlist channel="twitchchannelname" display="vertical" videonum="5"]
2. To add the Twitch TV stream plugin to your WordPress theme use the following shortcode inside your template:
    echo do_shortcode('[plumwd_twitch_streamlist channel="twitchchannelname" display="vertical" videonum="5"]');

The plugin also supports several attributes for the shortcode, below is a listing of the attributes and what their purpose is:

1. channel -> this must be set or the feed will not display. Usage:
    [plumwd_twitch_streamlist channel="plumwd"]
2. videonum -> The number of streams to display. Will return the most recent streams in order from newest to oldest. Usage:
    [plumwd_twitch_display channel="plumwd" videonum="4"]
3. display -> accepts two different options: horizontal or vertical. Usage:
    [plumwd_twitch_streamlist channel="plumwd" display="horizonal"]
 
== Frequently Asked Questions ==

For help please visit http://www.plumeriawebdesign.com

== Changelog ==
= 2.1.0 =
Fixed bug causing headers to be sent early from widget. Also replaced default thumbnail for widget.

= 2.0.9 =
Fixed bug with TinyMCE

= 2.0.8 =
Updated to most recent Twitch embed code. Fixed bug in the streamlist.

= 2.0.7 =
Fixed a bug with the widget stream status display. With the closing of Justin.tv the api was no longer functional.

= 2.0.6 =
Fixed the shorcode editor buttons so that they display again. Also fixed an error with an extra value attribute.
= 2.0.5=
Fixed a bug in the chat iframe height and width parameters

= 2.0.4 =
Fixed a bug with improper path setting in widget.php. Thanks Natdragon for the headsup

= 2.0.3 =
Fixed a bug with an incorrect path in the help file

= 2.0.2 =
Fixed issue with widget not having proper channel settings

= 2.0.1 =
Added clarification to admin screen regarding channel name settings

= 2.0 =
* Multi-stream Support Added
* Other random bug fixes
* Preview removed from admin
* Default height and width settings add in case of no setting for height/width
* Must give channel name in shortcode

= 1.0.7 =
* Further bug fixes related to the widget display

= 1.0.6 =
* Updated a bug with the widget display

= 1.0.5 =
* Updated a typo in the shortcode for a stream display
* Updated the widget to grab contents from a local json file instead of remote file

= 1.0.4 = 
* Autoplay parameter was not set correctly

= 1.0.3 = 
* Fixed incorrect path

= 1.0.2 =
* Fixed undefined index error

= 1.0.1 =
* Fixed footer credits