
=== AddFunc WYSIWYG Helper ===

Contributors: addfunc,joerhoney
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7AF7P3TFKQ2C2
Tags: wysiwym,wysiwyg,tinymce,element highlighter
Requires at least: 3.0.1
Tested up to: 5.0
Stable tag: 5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Highlights prominent HTML elements in the WYSIWYG editor, to help Editors see what they're editing. Sort of a WYSIWYM (the M is for mean).

== Description ==

AddFunc WYSIWYG Helper is a lightweight plugin that uses CSS to highlight prominent HTML elements in the default WYSIWYG editor (Tiny MCE), to help Editors see what they're editing. This in effect creates a sort of combination WYSIWYG/[WYSIWYM](http://mcs.open.ac.uk/nlg/old_projects/wysiwym/) (What You See Is What You Mean) combination. With the WYSIWYM option turned on in your user profile, the following elements are highlighted with a colored border and label:

*   p
*   div
*   pre
*   ol
*   ul
*   li
*   figure
*   article
*   section
*   aside
*   header
*   footer
*   span*
*   code*

*Span and code elements are highlighted without labels. For span elements, this is to help make them more prominent, so they are easy to spot. This can help Editors keep the code clean, as they can see where they need to remove unwanted styling using the style eraser button. For code elements, we do this because WordPress apparently always wraps these within another element, treating it as inline, rather than a block. The labels can only be workable on block elements.

Your theme can still apply an editor-style.css stylesheet. In most cases, the WYSIWYG will still reflect how the content will look on the front end of the live website.

AddFunc WYSIWYG Helper also provides an option to cancel out certain default styles in the WYSIWYG, such as the caption box/border.

Unlike it's predecessor, Average WYSIWYG Helper, these settings are not on a universal options page that applies to all users. Each user can enable/disable these options on his/her user profile settings page under the heading "WYSIWYG Helper".

**Note:**   It is suggested that Editor's using the WYSIWYM use a browser that supports CSS3 for best results.

== Installation ==

1. Upload the entire `addfunc-wysiwyg-helper` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Turn on the settings you want to use in Settings > WYSIWYG Helper

== Frequently Asked Questions ==

= I activated the plugin and nothing happened. =

All options are disabled by default. Be sure to enable the ones you want to use in Profile (of if you're an Administrator: Users > Your Profile > WYSIWYG Helper).

= How is AddFunc WYSIWYG Helper different from Average WYSIWYG Helper =

Average WYSIWYG Helper has one central options page that applies to all users. AddFunc WYSIWYG Helper settings are set on individual user profiles, applying only to the user account one is setting the options for. This difference however, is sort of an update to the plugin, just being released simultaneous to another big change, which is that the project name "Average" is being changed to "AddFunc".

= Does it really require WordPress 3.0.1 or later? =

I have not tested it on earlier versions. In fact, I could use help with testing. Feel free to try it out in an earlier version and let me know if it works! :)

= Does AddFunc have a website =

Not yet. Soon young padawan. Soon.

== Screenshots ==

1. AddFunc WYSIWYG Helper uses color classification to make elements easily recognizable.

2. These tiny labels clue you in on what the colored highlights are indicating.

3. AddFunc WYSIWYG Helper doesn't interfere with WordPress' default image and gallery editing features. Also, notice this caption has no default WordPress style border.

4. Sneaky <span> elements, revealed!

5. This is what the simple user profile preferences look like.

6. Includes a help tab for quick and easy reference.

== Changelog ==

= 5.0 =
6-Jul-2017

*   Adds highlighting for `<section>` elements
*   Removes option for canceling default image caption styles.
    -   This feature became obsolete a few WordPress versions back as the styling for image captions had been eliminated in WordPress core.
    -   All functions and relevant code for this option have been stripped out
    -   Removes file overrides.css

= 4.0 =
24-May-2016

*   Adds highlighting for `<figure>` `<article>` `<aside>` `<header>` and `<footer>` elements

= 3.0 =
30-Mar-2016

*   Adds highlighting for `<pre>` and `<code>` elements
*   Enhances readability of labels (in case the colorization isn't sufficiant)
*   Changes `<div>` (box) indicating character from "V" to "D". We hope this doesn't upset anyone. Reasons for the change:
    -   "D" is more intuitive than "V" for a word that starts with "D" (div)
    -   "V" isn't really any easier to read on that color of green
    -   We realized "V" has just as much potential need to be reserved as "D" does, so what's the point in reserving it?

= 2.3 =
5-Feb-2015

*   Removes options page. Settings are no longer set on a universal options page
*   Adds "WYSIWYG Helper" preferences to user profile ("WYSIWYM" and "Cancel Default Styles")

= 2.2.1 =
27-Jan-2015

*   Changes all references to Average (including "avrg", "average", etc.) to relate to AddFunc (changing project name)

= 2.2 =
15-Sep-2014

*   Fixes gallery edit/delete buttons in WordPress 4.0

= 2.1 =
8-Sep-2014

*   Fixes image edit/delete buttons in WordPress 4.0

= 2.0 =
5-Aug-2014

*   Adds Help Tab on Post/Page/etc. editing pages (any Post type) when Show WYSIWYM option is checked/on
*   Adds link to buy custom support ticket

= 1.0 =
22-Jul-2014

*   Includes readme.txt
*   includes screenshots
*   Submitted to WordPress repository

= 0.5 =
12-May-2014

*   Accomidates for new gallery editing features of WordPress 3.9 (doesn't apply highlighting styles to image editor elements)

= 0.4 =
19-Apr-2014

*   Accomidates for new image editing features of WordPress 3.9 (doesn't apply highlighting styles to image editor elements)
*   Got the "flip-switch" style to work on the checkboxes if the AddFunc  admin.css file is loaded from the AddFunc theme (not yet released)

= 0.3 =
10-Feb-2014

*   Improves the <span> highlighter, making it more obvious and more reliable, using an outline rule

= 0.2 =
6-Jan-2014

*   Adds admin controls to turn off AddFunc WYSIWYG Helper features
*   Adds ability to turn on Overrides (for overriding certain default styles â€” so far just WP captions)

= 0.1 =
30-Dec-2013

*   Adds a stylesheet to the default WYSIWYG (TinyMCE), which Reveals all of the HTML elements comprehensively, while maintaining edibility as well as any theme styles (in most cases).

== Upgrade Notice ==
