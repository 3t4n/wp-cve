=== Change Text Case ===
Contributors: Michael Aronoff
Tags: text, case, TinyMCE, editor
Requires at least: 3.3
Tested up to: 6.3
Requires PHP: 7.4
Stable tag: 2.3.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Change Case adds buttons to change text case in the WordPress visual editor.

== Description ==

This plugin solves one of my greatest annoyances. Quickly changing the case of selected text. Clients often send text with all caps or no caps and clicking through a ton of text to fix the case is often tedious work. I created this simple but effective plugin to eliminate that chore.

Based on a request I have added a fourth button to add Sentence Case as well. So if you have a large block of text with . ? or ! between sentences it will Cap only the first letter of each sentence.

There is an Options Page to enable or disable any of the buttons.

***NEW***
I have also added keyboard shorcuts!
Ctrl+Shift+L = Lowercase
Ctrl+Shift+U = Uppercase
Ctrl+Shift+S = Sentence Case
Ctrl+Shift+T = Title Case
(I do not own a mac to test on but command should work according to the TinyMCE documentation)

== Usage ==

Highlight the text to be changed. Click the Visual Editor button inside your post/page for the desired effect:

* **UC** - Changes selected text to all Uppercase.
* **lc** - Changes selected text to all lowercase.
* **Sc** - Changes selected text to all Sentence case.
* **Tc** - Changes selected text to all Title case.

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use buttons in WordPress TinyMCE Visual Editor

== FAQ ==

= Q. Where can the plugin be used? =
The plugin can be used in the WordPress Visual Editor

= Q. Can I turn off some of the buttons? =
Yes, there is a plugin options page that allows you to choose which buttons to display in the Visual Editor. Even when buttons are off the keyboard shortcuts still work.

== Change Log ==
= 2.0 =
This is a major rewrite. I rebuilt the code from scratch so that it was more compact, flexible and I added keyboard shortcuts. The old version was really 4 TinyMCE plugins packaged together. It is now a single TinyMCE plugin with four discrete functions.

SPECIAL MESSAGE FOR USER @Hrohh...
The thread you started about your changes is locked so I cannot reply. Please start a new support thread or go to my website at www.ciic.com and contact me.

= 2.0.1 =
Fix conflict with keyboard shortcuts.

= 2.0.2 =
Fixed a typo I left in on 2.0.1

= 2.0.3 =
Tested with WP 4.9

= 2.1 =
Changed keyboard shortcuts to not conflict with the new Block editor.
This plugin is not ready for the new paragraph block You must use either the Classic Editor or the Classic Block in the new Block Editor.
Tested with WP 5.3

= 2.2 =
Fixed Deprecated Warning

= 2.2.1 =
Tested with WP 6

= 2.3 =
Updated to work with PHP 8.1

= 2.3.2 =
Tested with WP 6.2