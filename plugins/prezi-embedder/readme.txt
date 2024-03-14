=== Prezi Embedder ===
Contributors: dan.rossiter
Tags: prezi, embed, presentation, iframe
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=EE5LWRLG933EN&lc=US&item_name=Prezi%20Embedder%20Plugin&item_number=prezi%2dembedder&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Requires at least: 2.5
Tested up to: 4.7
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin allows the user to quickly & easily embed a Prezi in WordPress site by directly implementing the published 
iframe embed code.

== Description ==

This plugin, like most things useful, grew out of a need. I had a [Prezi](http://www.prezi.com/recommend/qv1ms7qvtplw) 
and I had a WordPress blog. I Googled for a few hours, trying to find a way to embed one within the other, and found some 
dirty hacks that ended up not working, as well as a plugin or two that didn't work, or only half worked. When I realized 
that there were no *good* solutions for this issue, I decided to create one.

This plugin is based on simplicity, making it extremely robust. On the back end, it is actually only a few lines of code. 
It takes the embed code that Prezi provides when clicking the share option through their site and simply inserts the 
necessary values that are needed to render a working Prezi.

I hope that you all find this plugin as useful as I have!

*If this plugin has been helpful, please take a moment to
[rate it](http://wordpress.org/support/view/plugin-reviews/prezi-embedder#postform)!*

== Installation ==

1. Upload `prezi-embedder` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[prezi id="<Prezi ID>"]` in any post or page were you want to embed a Prezi.

To find your Prezi ID, the easiest solution is to simply copy the URL of your Prezi, which should look something 
like this: *http&#58;//prezi.com/&lt;Prezi ID&gt;/&lt;Prezi Slug&gt;*. If you paste that URL exactly as it is, the plugin is 
now smart enough to extract the ID for you. Alternatively, if you would like to do it the hard way, you can 
manually extract the &lt;Prezi ID&gt; from that same URL.

= Prezi Embedder Options =

In addition to a simple embed, you can also set some additional embed options:

1. **align (default = none)**: Aligns the Prezi *left*, *right*, or *center* in your post.
1. **width (default = 500)**: Width of viewer in pixels.
1. **height (default = 400)**: Height of viewer in pixels.
1. **lock_to_path (default = 0)**: Whether to constrain user navigation to Prezi path (1), or let them roam free (0).
1. **autoplay (default = 0)**: Enables option at bottom of Prezi display to start autoplay.
1. **html5 (default = 1)**: True (1) to use new HTML5 viewer, false (0) to use Flash-based viewer.

The following shortcode is equivalent to `[prezi id="<Prezi ID>"]`, with default values explicitly set:

`[prezi id="<Prezi ID>" width=500 height=400 lock_to_path=0 html5=1]`

== Changelog ==

= 2.1 =
* **Tested Up To 4.4**
* **Enhancement:** Minor changes to embed code matching recent Prezi updates.
* **Enhancement:** Some under-the-hood cleanup.
* **Bug Fix:** In some cases the plugin would fail to startup with a warning about `PreziEmbedder` class not existing.

= 2.0 =
* **Enhancement:** Big news in this release is the addition of alignment. You can now center your Prezi
  as well as align left or right. See installation tab for details.
* **Enhancement:** Added support for [autoplay](https://prezi.com/support/article/presenting/autoplay/).

= 1.4 =
* **Enhancement:** Added new [HTML5 option](http://prezibase.com/prezi-old-vs-new-player-embed-code/).
* **Enhancement:** Made user input sanitization more robust.

= 1.3 =
* **Enhancement:** Now uses Prezi's latest embed code, which includes cross-browser
  full-screen support through use of [allowfullscreen](http://www.allowfullscreen.com/) attribute.
* **Enhancement:** Prezi Embedder is now fully
  [Internationalization-enabled](https://codex.wordpress.org/I18n_for_WordPress_Developers).
  This means that we can now support users speaking all languages. If you are interested
  in translating Prezi Embedder into a language that you speak, please
  [let me know](http://wordpress.org/support/topic/seeking-translators-1)!

= 1.2 =
* **Bug Fix:** Corrects issue with `lock_to_path` attribute. Thanks to **lhkstudio** for spotting this!

= 1.1.2 =
* **Bug Fix:** Corrected typos in readme.
* **Update:** Changed plugin URL.

= 1.1.1 =
* **Bug Fix:** Corrected some typos in the documentation.
* **Enhancement:** Cleaned up source code for easier reading.

= 1.1 =
* **Enhancement:** Shortcode now handles the full URL of the Prezi as an ID value (while still supporting just the id value).
* **Enhancement:** Added some simple error checking for attribute values.

= 1.0 =
* **Released:** Initial release.
