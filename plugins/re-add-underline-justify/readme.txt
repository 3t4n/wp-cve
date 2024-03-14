=== Re-add text underline and justify ===
Contributors: briKou
Donate link: https://www.paypal.me/BriceCapobianco
Tags: editor, underline, justify, wysiwyg, gutenberg, ACF
Requires at least: 4.7
Tested up to: 6.4.3
Requires PHP: 5.5.12
Stable tag: 0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 
== Description ==

**This tiny plugin re-adds the Editor text underline & text justify buttons in the WYSIWYG removed in WordPress 4.7. It works well with the [Classic Editor](https://fr.wordpress.org/plugins/classic-editor/) plugin, [Advanced Custom Fields](https://fr.wordpress.org/plugins/advanced-custom-fields/) (Free & Pro) and is also compatible with the Gutenberg's "Classic" bloc.**

In WordPress 4.7, the core developper team decided to make various changes in the editor (TinyMce WYSIWYG), including removing the **underline and justify buttons** and rearranging some of the toolbar buttons.
If you don't want to change the way you edit your content and/or don't want to confuse your customers with a new contribution interface, this very lightweight plugin will set the editor style back to its previous state (like in WordPress 4.6 and above versions).

**You may change the Editor style from the Writing option page.**

3 options are available for the Editor style:

* Without underline & justify buttons
* Default - Re-add underline & justify buttons
* Re-add justify only

Please note, the previous option "Re-add underline & justify + rearrange" has been deprecated in 0.2 (sept. 2018) as it causes conflicts with the new Gutenberg editor. This option automatically switches to "Re-add underline & justify buttons" from now on.

[DOCUMENTATION](https://www.b-website.com/re-add-text-underline-and-justify "Plugin documentation")

[CHECK OUT MY OTHER PLUGINS](https://www.b-website.com/category/plugins-en "More plugins by b*web")


**Please ask for help or report bugs if anything goes wrong. It is the best way to make the community benefit!**


== Installation ==

1. Upload and activate the plugin (or install it through the WP admin console)
2. That's it, it is ready to use!
3. If you wan't to change the default parameter, go to Settings -> Writing and select the option you want under "Editor style".

== Frequently Asked Questions ==

= Where can I change the Editor style? =
Just go to Settings -> Writing and select the option you want under "Editor style".


== Screenshots ==

1. Change the Editor style from the Writing option page
2. Gutenberg with justify button
3. Default - without underline & justify buttons
4. Re-add underline & justify buttons
5. Re-add justify only


== Changelog ==

= 0.4.1 - 2024/03/07 =
* Tested on WP 6.4.3 with success!

= 0.4 - 2022/10/24 =
* Tested on WP 6.0.3 with success!
* Update readme

= 0.3 - 2019/11/14 =
* Tested on WP 5.3 with success!
* Change default option to "Re-add underline & justify buttons" on plugin activation

= 0.2 - 2018/04/09 =
* Tested on WP 4.9.8 with success!
* Added support for Gutenberg for its "Classic" bloc only.
* Remove option 3  "Re-add underline & justify + rearrange" (depracated)

= 0.1.4 - 2017/31/03 =
* Tested on WP 4.7.3 with success!
* Fix broken links in plugins meta

= 0.1.3 - 2016/14/01 =
* Added the fourth option to only re-add justify button (push methode)
* Push non standard button to the end of the buttons lines for the third option. This prevents from removing extra buttons added by other plugins.
* Fix for ACF (free) on the second option

= 0.1.2 - 2016/14/11 =
* Changes MCE button hook priority to prevent errors with other plugins adding extra buttons.

= 0.1.1 - 2016/10/11 =
* Fixes an issue hidding Editor content.

= 0.1 - 2016/06/116 =
* First release.

== Upgrade Notice ==
= 0.1 - 2016/06/11 =
* The old option "Re-add underline & justify + rearrange" has been deprecated in 0.2 (sept. 2018) as it cause conflicts with the new Gutenberg editor. This option automatically switches to "Re-add underline & justify buttons" since.

= 0.1.3 =
* You now have a fourth option available to only re-add the justify button.

= 0.1 =
* First release.

