=== SyntaxHighlighter TinyMCE Button ===
Contributors: redcocker, homolibere
Donate link: http://www.near-mint.com/blog/donate
Tags: syntaxhighlighter, code, sourcecode, tinymce, button, syntax, highlight
Requires at least: 2.8
Tested up to: 3.3
Stable tag: 0.7.8.4

"SyntaxHighlighter TinyMCE Button" provides buttons for Visual Editor and will help to type &lt;pre&gt; tag for SyntaxHighlighter.

== Description ==

"SyntaxHighlighter TinyMCE Button" provides additional buttons for "Visual Editor(TinyMCE)" and these buttons will help to type or edit `<pre>` tag for Alex Gorbatchev's SyntaxHighlighter.

This plugin is based on "[CodeColorer TinyMCE Button](http://wordpress.org/extend/plugins/codecolorer-tinymce-button/ "homolibere developed")" and becomes more feature-rich.

= Features =

* You can operate in Visual Editor, No need to use HTML Editor.
* Using two buttons, easy to wrap your code in `<pre>` tag and change options of previously-wrapped code.
* Once your code is wrapped in `<pre>` tag, You can type 'tabs' for indent.
* Localization: English(Default), 日本語(Japanese, UTF-8).

= Compliant plugins =

You can use this plugin with following code syntax highlighter.

* [WP SyntaxHighlighter](http://wordpress.org/extend/plugins/wp-syntaxhighlighter/ "WP SyntaxHighlighter") (recommend)
* [Syntax Highlighter Compress](http://wordpress.org/extend/plugins/syntax-highlighter-compress/ "Syntax Highlighter Compress")
* [Auto SyntaxHighlighter](http://wordpress.org/extend/plugins/auto-syntaxhighlighter/ "Auto SyntaxHighlighter")
* [Syntax Highlighter and Code Colorizer for WordPress](http://wordpress.org/extend/plugins/syntax-highlighter-and-code-prettifier/ "Syntax Highlighter and Code Colorizer for WordPress")
* [Syntax Highlighter MT](http://wordpress.org/extend/plugins/syntax-highlighter-mt/ "Syntax Highlighter MT")
* [SyntaxHighlighterPro](http://wordpress.org/extend/plugins/syntaxhighlighterpro/ "SyntaxHighlighterPro")
* [Easy Google Syntax Highlighter](http://wordpress.org/extend/plugins/easy-google-syntax-highlighter/ "Easy Google Syntax Highlighter")
* [SyntaxHighlighter Evolved](http://wordpress.org/extend/plugins/syntaxhighlighter/ "SyntaxHighlighter Evolved") etc.

When using with "SyntaxHighlighter Evolved", "Load All Brushes" option must be enabled on the "SyntaxHighlighter" setting panel.

This plugin has operating modes optimized for "WP SyntaxHighlighter", "Syntax Highlighter Compress" or "SyntaxHighlighter Evolved".

= Notes =

This plugin can work only with the default visual editor(TinyMCE). Other visual editors are not supported.

This plugin is designed to work with a plugin based on Alex Gorbatchev's SyntaxHighlighter Ver, 2.0 or higher.

You can not use this plugin with some 'SyntaxHighlighter'-based plugins which do not support `<pre>` tag.

== Installation ==

= Installation =

1. Upload plugin folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the "Plugins" menu in WordPress.
1. If you need, go to "Settings" -> "SH TinyMCE Button" to configure.

If you use "[WP SyntaxHighlighter](http://wordpress.org/extend/plugins/wp-syntaxhighlighter/ "WP SyntaxHighlighter")", "[Syntax Highlighter Compress](http://wordpress.org/extend/plugins/syntax-highlighter-compress/ "Syntax Highlighter Compress")" or "[SyntaxHighlighter Evolved](http://wordpress.org/extend/plugins/syntaxhighlighter/ "SyntaxHighlighter Evolved")", you should configure "Using with" option on the setting panel.

= Usage =

**Usage: To paste your code into the post or page.**

If you want to paste the code from the other document into your post or page, this way is best. Your pasted code will be warpped in `<pre>` tag automatically.

1. Click "CODE" button.
1. Select language and options and paste your code into textbox.
1. Click "Insert" button.

**Usage: To wrap previously-written code in `<pre>` tag.**

If you have previously-written code on your post or page, by this way, you can wrap your code in `<pre>` tag for 'SyntaxHighlighter'.

1. With the mouse, select and highlight your code where you want to aplly "SyntaxHighlighter".
1. Click "pre" button.
1. Select language and options.
1. Click "Insert" button.

**Usage: To change language and options of previously-markuped code.**

1. With the mouse, select and highlight your code.
1. Click "pre" button.
1. Change language and options.
1. Click "Update" button.

**Usage: To indent by tabs.**

1. Just type tab in your code. But till your code is wrapped in `<pre>` tag, you can not type any tabs.

== Frequently Asked Questions ==

= Q. I want to change language and options of previously-markuped code with "pre" button. But I can't select whole my code. =

A. You need not to select whole your code. It's enough to select first line of your code.

= Q. After the update to new version, Buttons do not work correctly. =

A. Old javascript files may be cached. Please clear your browser's cache or delete cached javascript files.

= Q. I get errors trying to check "html-scripts box". =

A. "html-scripts" option requires "XML" brush. However, some 'SyntaxHighlighter'-based plugins do not load "XML" brush by default. If you want this optiin, you should consider the use of "[WP SyntaxHighlighter](http://wordpress.org/extend/plugins/wp-syntaxhighlighter/ "WP SyntaxHighlighter")".

== Screenshots ==

1. This is added buttons on TinyMCE.
2. This is pop up window at the click of "pre" button.
3. This is pop up window at the click of "CODE" button.
4. This is setting panel.

== Changelog ==

= 0.7.8.4 =
* Fix a bug: Can't change "Using with" option.

= 0.7.8.3 =
* Fix a bug: Using bloginfo() in the wrong way.
* Fix a bug: A missing textdomains.

= 0.7.8.1 =
* Fix a bug: TinyMCE "code" button can't handle sourcecodes including HTML entities(`&amp;`, `&lt;`, `&gt;`, `&quot;`, `&#039`;) correctly.

= 0.7.8 =
* Rewritten the codes for array definition.
* Changed setting data migration process.
* Fix a bug: When using code button, some browsers give extra line break.

= 0.7.7 =
* Validating the setting values more closely.
* Added the icon before title block on the setting panel.
* Fix a bug: The setting data migration processing can't work concurrently with auto-update.
* Fix a bug: A error message don't be translated.

= 0.7.5 =
* Support WP SyntaxHighlighter ver. 1.5.5 or higher.
* Checking if the current request carries a valid nonce when settings are saved or reset.
* Validating the setting value before saved.
* Changed line feed codes to LF.(except text files) The line feed codes were CR/LF in the many of files.
* Changed the method of displaying the notice message for admin.
* Changed the processing method when plugin is updated.
* Using dirname() and plugin_basename() instead of hardcoded directory name.
* Changed directory name stored translation files.

= 0.7.2 =
* Fix a bug: "Insert" function of "pre" button does not work in the fullscreen mode.

= 0.7.1 =
* New fullscreen mode in WordPress 3.2 or higher was also supported.

= 0.7 =
* Added the "Dafault languages settings for your buttons" options.
* Added "Reset All Settings" button into the setting panel.
* All setting value are stored in the associative arrays.
* Changed processing code for creating buttons.
* Changed the method to add javascript into setting panel.

= 0.6 =
* Added the option to choose pop-up windows size.
* Changed the method to get plugin directory url.
* "Secondary mode" is fully integrated into "Normal mode" and removed.

= 0.5.7 =
* Changed the method to get plugin directory url.

= 0.5.6 =
* Removed "sh-tinymce-button-ins-2nd.php" and "sh-tinymce-button-box-2nd.php".
* "Secondary mode" integrated into "Normal mode".
* Changed the method to get plugin directory url in "Secondary mode".

= 0.5.5 =
* Fix "Notice: has_cap was called with an argument that is deprecated since version 2.0! Usage of user levels by plugins and themes is deprecated. Use roles and capabilities instead." when "WP_DEBUG" is turned on.
* Added "System Info" in setting panel.
* Changed processing code for "Secondary mode".

= 0.5 =
* Can choose TinyMCE toolbar row which buttons will be placed in.
* New operating modes optimized for "[SyntaxHighlighter Evolved](http://wordpress.org/extend/plugins/syntaxhighlighter/ "SyntaxHighlighter Evolved")".
* Added "Secondary mode" for troubleshoot.
* Changed processing code for creating buttons.

= 0.4.1 =
* Optimized for "[WP SyntaxHighlighter ver. 1.2.3 or higher](http://wordpress.org/extend/plugins/wp-syntaxhighlighter/ "WP SyntaxHighlighter ver. 1.2.3 or higher")".

= 0.4 =
* Added operating modes optimized for "[WP SyntaxHighlighter](http://wordpress.org/extend/plugins/wp-syntaxhighlighter/ "WP SyntaxHighlighter")" and "[Syntax Highlighter Compress](http://wordpress.org/extend/plugins/syntax-highlighter-compress/ "Syntax Highlighter Compress")".
* Added new options to popup window at the click of "pre" and "CODE" button.
* Fix a bug: "CODE" button does not work in fullscreen mode.

= 0.3 =
* Added new options to popup window at the click of "pre" and "CODE" button.

= 0.2.2 =
* The priority of a function hooked has been changed for "[Auto SyntaxHighlighter](http://wordpress.org/extend/plugins/auto-syntaxhighlighter/ "Auto SyntaxHighlighter")".

= 0.2.1 =
* Simplified codes related to processing tabfocus.
* Modified Japanese translation(modified "shtb_adv_lang-ja.mo" file).
* Translation of button labels on TinyMCE popup window has been completed.
* layout of buttons on TinyMCE popup window has been changed.

= 0.2 =
* Added "CODE" button which allows to paste sourcecode into post or page, keeping indent by tab.
* Enable to change language and options of previously-markuped code.

= 0.1.1 =
* This is the first version.

== Upgrade Notice ==

= 0.7.8.4 =
This version has a bug fix.

= 0.7.8.3 =
This version has some bug fixes.

= 0.7.8.1 =
This version has a bug fix.

= 0.7.8 =
This version has some changes and bug fix.

= 0.7.7 =
This version has some changes and bug fixes.

= 0.7.5 =
This version has some changes.

= 0.7.2 =
This version has a bug fix.

= 0.7.1 =
This version has a new feature.

= 0.7 =
This version has a new feature and changes.

= 0.6 =
This version has a new feature and changes.

= 0.5.7 =
This version has some changes.

= 0.5.6 =
This version has some changes.

= 0.5.5 =
This version has some changes and bug fix.

= 0.5 =
This version has some new features and changes.

= 0.4.1 =
This version has a low-priority change.

= 0.4 =
This version has some new features and bug fix.

= 0.3 =
This version has some new features.

= 0.2.2 =
This version has a low-priority change.

= 0.2.1 =
This version has some low-priority changes.

= 0.2 =
This version has some new features.

= 0.1.1 =
This version is the first version.
