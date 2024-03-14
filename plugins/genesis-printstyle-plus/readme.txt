=== Genesis Printstyle Plus ===
Contributors: daveshine, deckerweb
Donate link: https://www.paypal.me/deckerweb
Tags: genesis, genesiswp, genesis framework, paper, print, printstyle, stylesheet, printing, office, deckerweb
Requires at least: 3.6
Tested up to: 5.1
Stable tag: 1.9.3
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php

This plugin is adding a printer-ready stylesheet file for the Genesis Framework and its currently active child theme.

== Description ==

> #### Optimized: Saves You Printing Ink & Paper :)
> This **small and lightweight plugin** adds a printer-ready stylesheet file (print.css / print-html5.css) for the *Genesis Framework* and its currently active child theme. Any unneeded site elements will be removed, such as main and sub navigation bar, sidebar, footer widgets, form input fields to name a few.
> 
> For most use cases and most existing/ regular Genesis Child Themes this should work really fine!

**NEW since version 1.5.0+** For fully custom styles or additions just have look at the [FAQ section](http://wordpress.org/extend/plugins/genesis-printstyle-plus/faq/) here. :) It is now really easy to handle and complete update-safe!

**Please note:** The plugin requires the *Genesis Theme Framework* (GPL-2.0+), a paid premium product released by StudioPress/ Copyblogger Media LLC (via studiopress.com).

Credit where credit is due: The first plugin version was based on the work of Ramoonus and his plugin.

= Translations: Internationalization (i18n) / Localization (L10n) =
* English (default) - always included
* German (de_DE) - always included
* Italian (it_IT) - user-submitted, thanks to Marco Rosselli - currently 32% complete for v1.9.3
* .pot file (`genesis-printstyle-plus.pot`) for translators is also always included :)
* Easy plugin translation platform with GlotPress tool: [Translate "Genesis Printstyle Plus"...](http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-printstyle-plus)
* *Your translation? - [Just send it in](http://genesisthemes.de/en/contact/)*

[A plugin from deckerweb.de and GenesisThemes](http://genesisthemes.de/en/)

= Feedback =
* I am open for your suggestions and feedback - Thank you for using or trying out one of my plugins!
* Drop me a line [@deckerweb](https://twitter.com/deckerweb) on Twitter
* Follow me on [my Facebook page](https://www.facebook.com/deckerweb.service)
* Or follow me on [+David Decker](https://plus.google.com/+DavidDecker/posts) on Google Plus ;-)

= This Plugin... =
* ...is *Quality Made in Germany*
* ...was created with love (plus some coffee) on an [Ubuntu Linux](http://www.ubuntu.com/desktop) powered machine :)

= More =
* [Also see my other plugins](http://genesisthemes.de/en/wp-plugins/) or see [my WordPress.org profile page](http://profiles.wordpress.org/daveshine/)
* Tip: [*GenesisFinder* - Find then create. Your Genesis Framework Search Engine.](http://genesisfinder.com/)
* Hey, come & join the [Genesis Community on Google+ :)](http://ddwb.me/genesiscommunity)

== Installation ==

**NOTE:** Only works with *Genesis Framework* (GPL-2.0+) as the parent theme. This is a paid premium product by StudioPress/ Copyblogger Media LLC, available via studiopress.com.

= Installation Steps =
1. Installing alternatives:
 * *via Admin Dashboard:* Go to 'Plugins > Add New', search for "Genesis Printstyle Plus", click "install"
 * *OR via direct ZIP upload:* Upload the ZIP package via 'Plugins > Add New > Upload' in your WP Admin
 * *OR via FTP upload:* Upload `genesis-printstyle-plus` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Print out any page of your Genesis-powered website :-)

**Note:** The "Genesis Framework" is required for this plugin in order to work. If you don't own a copy it yet, this premium parent theme has to be bought. More info about that you'll find here: http://ddwb.me/getgenesis

**Custom Styles:** For fully custom styles or additions just have look at the [FAQ section](http://wordpress.org/extend/plugins/genesis-printstyle-plus/faq/) here. :)

**Also note:** The plugin has no extra settings page, as it has *built-in logic* for loading the print stylesheet from plugin (default) or for additions, also via child theme.

== Frequently Asked Questions ==

= How can I get a more customized print stylesheet for my (customized) child theme? =
That is really easy now, since plugin version 1.5+! There are two alternatives of doing that:

(1) Add a print stylesheet file `print-additions.css` to your active child theme's root folder and you're done. It will be automatically enqueued after the packaged plugin styles so you are able to override them.

(2) To not use the packaged plugin stylesheet at all just add your full own custom print stylesheet `gpsp-print.css` to your active child theme's root folder and you're done. This will be properly enqueued then and NOT the plugin file.

Both ways are really easy and update-secure. Enjoy!

= How can I use another font for printing? =
Same two alternatives as explained above. Edit the "font-family" setting for the body tag and you're good to go.

= Why are you using CSS Media Queries for the print stylesheet? =
In short: We believe that this is the recommended way of doing such things.

...some more thoughts: In most cases this will work across different browsers and operating systems with most current printers. So I really see no reason here of not doing it that way. -- Please note that I CANNOT test all browsers, OS, printers etc. that are out there. Still, you might report any issues you might have on your system configuration and together we will see what could be done/solved. Ok with that?

= There Are a Few Issues with Some Child Themes, What Could I Do? =
This seems to be true, regarding the HUGE number of Genesis Child Themes out there yet! However, I'll do my best to support as much stuff out of the box as possible but cannot be specific for all child themes on the market... You just have to tweak some existing CSS rules or add a new one for printing. Please see the questions above how you could replace, add/tweak print CSS rules. Yes, it's really easy so just try it :-).

= Could I use this plugin also with another theme/ framework? =
Good question but unfortunately this is not possible and not recommended. I've built in a theme check function so it's only useable with the Genesis Framework and its child themes. The reason for that is simple because the print stylesheet references a lot of unique Genesis CSS IDs and classes so it will be nearly unuseable with other themes. However, you might have a look at the included print stylesheet and maybe got inspired for your own print stylesheet for your custom theme or even doing your own plugin with this...

== Screenshots ==

Not relevant for this plugn.

== Changelog ==

= 1.9.3 (2014-02-26) =
* *Next round of print CSS improvements and fixes :)*
* UPDATE: Print CSS for HTML5 child themes improved for (fixed) header/ title, content.
* UPDATE: Print CSS for XHTML child themes improved for (fixed) header/ title, content.
* UPDATE: Added `.sharedaddy` (Jetpack sharing module) and `.post-edit-link` classes to the print stylesheets to not display on print.
* CODE: Minor internal code/ documentation improvements.
* UPDATE: Updated German translations and also the .pot file for all translators!
* NEW: Added Gist on GitHub with snippets and example custom print stylesheets for customization etc.: https://gist.github.com/deckerweb/9230551

= 1.9.2 (2014-02-23) =
* UPDATE: Another round of print CSS tweaking. Hopefully for the better this time :-).

= 1.9.1 (2014-02-22) =
* UPDATE: Tweaked print CSS again. This hopefully in the right way :-).

= 1.9.0 (2014-02-21) =
* UPDATE: Fixed CSS to not truncate content on the right side, especially for responsive/ HTML5 child themes.
* CODE: Some internal code/ documentation updates & improvements.
* UPDATE: Updated German translations and also the .pot file for all translators!

= 1.8.0 (2013-09-01) =
* NEW: Plugin now supports Genesis 2.0+ HTML5 markup if activated by a child theme. Then, the "HTML5 version" of the print stylesheet gets loaded because of the different markup. (Reason for the divided stylesheet: no overbloat, better performance!)
* UPDATE: Added `.backstretch` class to the print stylesheet to not display those background images on print. This comes in handy for child themes like "Metro" or "Sixteen Nine Pro" that use *Backstretch* by default.
* UPDATE: Improved translation loading.
* CODE: Minor code/documentation updates & improvements.
* UPDATE: Updated and improved readme.txt file here.
* UPDATE: Updated German translations and also the .pot file for all translators!

= 1.7.0 (2013-02-04) =
* NEW: Plugin now uses WordPress standard for minified and development stylesheet filenames. Therefore plugin default style is `print.min.css` and the development version of that is `print.css`. Also, if you are an Administrator and have `WP_DEBUG` on, the development version gets loaded. Cool, hehe :)
* UPDATE: Plugin print stylesheet now validates for CSS3 via official W3C Validator! - A media query rule was improved, thanks to [@juicedaniel](http://juiced.de/) for that!
* CODE: Minor code/documentation updates & improvements.
* UPDATE: Updated and improved readme.txt file here.
* UPDATE: Updated German translations and also the .pot file for all translators!

= 1.6.1 (2012-09-11) =
* *Maintenance release*
* UPDATE: Fixed a CSS error and optimized a few other rules. -- Thanks to forum user report from "webwise"!
* UPDATE: Updated German translations and also the .pot file for all translators!

= 1.6.0 (2012-09-07) =
* *Maintenance release*
* NEW: Added help tab to Genesis settings pages.
* UPDATE: Optimized a few CSS rules, especially for homepage logic with title/description.
* NEW: Compressed CSS file (`print.min.css`) for improved performance (the development file has now the file name `print.css` and is still packaged).
* CODE: Minor code/documentation updates & improvements.
* UPDATE: Corrected readme.txt file here.
* UPDATE: Updated German translations and also the .pot file for all translators!
* UPDATE: Initiated new three digits versioning, starting with this version.

= 1.5.0 (2012-05-06) =
* *New features:*
 * NEW: Added own action hook for enqueueing own plugin or custom user stylesheets!
 * NEW: If a print stylesheet file `gpsp-print.css` is found in your active child theme's root folder, this will be your print stylesheet - if it's not there, the packaged plugin print stylesheet is being used! This is really handy, if you need to enqueue your own stylesheet and nothing else (i.e. for Multisite purposes...). All update-secure and really easy to handle!
 * NEW: Possible user style additions, additional to the plugin's default stylesheet: if a print stylesheet file `print-additions.css` is found in your active child theme's root folder it will be added *after* the plugin's default. This way you can add some more rules or override existing selectors/rules. Again, all update-secure and really easy to handle!
 * REMOVED: Removed the update nag message as it was annoying to some users and is no longer needed, because you can use your own stylesheet now, or enqueue additional user styles via our action hook! All in all the new way is more user-friendly, future-proof and using best practices. Enjoy!
* UPDATE: Simplified Genesis detection on installation, making it much more future-proof and user-friendly.
* CODE: Beside new features, minor code/documentation tweaks and improvements.
* CODE: Successfully tested against Genesis 1.8+ plus WordPress 3.3 branch and new 3.4 branch. Also successfully tested in WP_DEBUG mode (no notices or warnings).
* UPDATE: Simplified language files; updated German translations and also the .pot file for all translators!
* NEW: Added new Italian translation by Marco Rosselli.
* UPDATE: Extended GPL License info in readme.txt as well as main plugin file.
* NEW: Easy plugin translation platform with GlotPress tool: [Translate "Genesis Printstyle Plus"...](http://translate.wpautobahn.com/projects/wordpress-plugins-deckerweb/genesis-printstyle-plus)

= 1.4.0 (2011-12-14) =
* Fixed possible enqueue issue with stylesheet: replaced deprecated hook with new standard.
* CSS print styles: added experimental support for removing Google Adsense text ads from prints - Please note: this should not affect regular printing at all, though all testing is very welcomed!
* Updated German translations and also the .pot file for all translators!
* Tested & proved compatibility with WordPress 3.3 final release :-)

= 1.3.0 (2011-12-04) =
* Fixed a critical bug!
* Important: PLEASE do a manual upgrade from v1.2 to v1.3 - upload files via FTP. Thanx!

= 1.2.0 (2011-10-04) =
* Print stylesheet: improved image display with regular WordPress image classes in content area - removed printed link urls for images in content area
* Print stylesheet: added rule for BuddyPress admin bar when using the GenesisConnect Plugin & BuddyPress
* Print stylesheet: added compatibility with my plugin "Genesis Single Post Navigation" - browse links now removed for print!
* Print stylesheet: improved css code and inline documentation - now validades for CSS 2.1 by W3C standards!
* Added checks for activated Genesis Framework and its minimum version before allowing plugin to activate
* Added plugin update nag in WP Admin with advice for existing print CSS customizations to be backuped/saved
* Added localization for the whole plugin, which is pretty much the plugin description section and links on the plugin page
* Added German translations (English included by default)
* Added .pot file for translators (`genesis-printstyle-plus.pot` in `/languages/`)
* Improved and documented plugin code
* Tested & proved compatibility with WordPress 3.3-aortic-dissection :-)
* Big update to readme.txt file; added new FAQ entries here

= 1.1.0 (2011) =
* (unreleased private beta)

= 1.0.2 (2011-07-01) =
* Fixed repo problems

= 1.0.1 (2011-07-01) =
* Unique function name to avoid conflicts
* Removed Readme bugs :)

= 1.0.0 (2011-07-01) =
* Initial release

== Upgrade Notice ==

= 1.9.2 =
Some improvements: Even more print CSS tweaks.

= 1.9.1 =
Some improvements: More print CSS tweaks.

= 1.9.0 =
Some improvements: CSS fixes for right side truncation; code improvements. Updated all translations as well as .pot file for translators.

= 1.8.0 =
Several improvements: Dynamic Genesis 2.0+ HTML5 markup support. Updated all translations as well as .pot file for translators.

= 1.7.0 =
Several improvements: Tweaked stylesheet naming and development tools. Updated all translations as well as .pot file for translators.

= 1.6.1 =
Maintenance release: Tweaked a few CSS rules. Updated all translations as well as .pot file for translators.

= 1.6.0 =
Maintenance release: Added help tab on Genesis settings pages. Compressed (minified) CSS file. Updated all translations as well as .pot file for translators.

= 1.5.0 =
Major changes & improvements: Improved stylesheet loading, added ability for fully custom files. Code cleanup & improvements. Added Italian translations, updated German translations as well as .pot file for translators.

= 1.4.0 =
Important change: improved compatibility with WordPress 3.3+. Added experimental css rules for removing Google Adsense text ads when printing.

= 1.3.0 =
Important change - fixed a critical bug - PLEASE do a manual upgrade - upload files via FTP. Thanx!

= 1.2.0 =
Several changes - Added activation checks and localization and further improved code and documentation.

= 1.0.2 - 1.1 =
Minor internal testing stuff.

= 1.0.1 =
Minor changes - Unique function name.

= 1.0.0 =
Just released into the wild.

== Plugin Links ==
* [Translations (GlotPress)](http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-printstyle-plus)
* [User support forums](http://wordpress.org/support/plugin/genesis-printstyle-plus)
* [Code snippets archive for customizing, GitHub Gist](https://gist.github.com/deckerweb/9230551)

== Donate ==
Enjoy using *Genesis Printstyle Plus*? Please consider [making a small donation](https://www.paypal.me/deckerweb) to support the project's continued development.

== Translations ==
* English - default, always included
* German (de_DE): Deutsch - immer dabei! [Download auch via deckerweb.de](http://deckerweb.de/material/sprachdateien/genesis-plugins/#genesis-printstyle-plus)
* Italian (it_IT): Italiano - user-submitted by [Marco Rosselli](http://www.prenotazionetraghetti.com/)

**Easy plugin translation platform with GlotPress tool:** [**Translate "Genesis Printstyle Plus"...**](http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-printstyle-plus)

*Note:* All my plugins are internationalized/ translateable by default. This is very important for all users worldwide. So please contribute your language to the plugin to make it even more useful. For translating I recommend the awesome ["Codestyling Localization" plugin](http://wordpress.org/extend/plugins/codestyling-localization/) and for validating the ["Poedit Editor"](http://www.poedit.net/), which works fine on Windows, Mac and Linux.

== Idea Behind / Philosophy ==
I just wanted to have a special print layout optimized for most Genesis Child Themes - with any unneeded elements removed but still with proper image display  (including title tag text!) and proper footer line/copyright included. And I wanted that as a plugin so I could just activate it on client sites or if clients just request an easy-to-use solution. I intentionally used CSS Media Queries because they are made for this case - in combination with the output medium print.

== Credits ==
* Thanks to [@juicedaniel](http://juiced.de/) for reporting a CSS3 bug!
* Thanks to all users that helped with their feedback to improve this plugin, especially the print CSS! :)

== Last but not least ==
**Special Thanks go out to my family for allowing me to do such spare time projects (aka free plugins) and supporting me in every possible way!**