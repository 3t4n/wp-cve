=== Wp-autosave ===
Contributors: wp-autosave team
Donate link: wpautosave@gmail.com
link: wpautosave@gmail.com
Tags: save, autosave, editor, draft, post
Requires at least: 3.0.1
Tested up to: 5.3.1
Requires PHP: 5.6
Stable tag: 4.9
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

"Wp-autosave" plugin is for automatically saving posts being written in the Classic Editor

== Description ==
"Wp-autosave" plugin is for automatically saving posts  being written in the Classic Editor

**Note:**
This plugin works as an additional module to the [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin by [WordPress Contributors](https://github.com/WordPress/classic-editor/) since [WordPress version 5.0+](https://wordpress.org/support/wordpress-version/version-5-0/), because the original Classic Editor was replaced with a new editor - Gutenberg.
So the original Classic Editor plugin is required to work with WP-Autosave plugin for all actual WordPress versions.   

In case of older versions of WordPress (<5.0), the original Classic Editor plugin is not required because Classic Editor is used by default.

**Major features of plugin:**
- Auto-save post to drafts at regular time intervals by multipart requests. _(To check the functionality it's enough to have the rights to create an entry in the wp-blog (you can test through a user-account) - you will see all requests through the developer tools or some other tools)_
- Intellectual immediately auto-save post to drafts when you change the text in the editor
- You can attach a time-stamp to request through the settings of the plugin (appendix time of saving in the query)  
- You can set your auto-save interval  
- You can choose the type of saving - by time intervals or when content is changed  

== Installation ==
**Prerequisites:**
To use *WP-Autosave* plugin you need installed [Classic Editor](https://wordpress.org/plugins/classic-editor/) plugin (in case of WordPress version 5.0+)

**Manual Installation:** 
- Download *"wp-autosave.zip"* archive from this page with the *"Download"* button
- Unzip directory *"wp-autosave"* with plugin from archive into *"plugins"* directory of your WordPress Installation  (e.g. *wp-content/plugins/wp-autosave*)
- Hit the *"Activate"* button in plugins menu of WordPress administration console

**Installing from WordPress plugins:**
- Find *"WP-Autosave"* plugin in the plugins menu of your WordPress administration console
- Hit *"Install"* and then *"Activate"*

== Frequently Asked Questions ==
= How to use this plugin? =  
To use the plugin just install and activate it from your WordPress administration console, and after that everything that you write in your editor will be automatically saved in drafts.
The frequency of saving depends on the settings.  

= What is minimal time to save by intervals? =  
It depends on you: if you want, you can set a time interval even to any value in seconds, but the preferable value is 30 seconds.

= What will happen if my connection is lost and I am disconnected from the server? =  
Don't worry. The plugin automatically checks your connection. If you lose it, the plugin will wait until the connection is restored.
As a result, all your changes will be saved.  

== Screenshots ==
1. This is the administration/settings page of this plugin.

== Changelog ==
= 1.1.1 =
* Update to WordPress 5.3.*

= 1.1.0 =
* Update to WordPress 5.2.*
* Update installation instructions
* Update information and documentation
* Update the core modules of the plugin
* Fix a bug when the plugin can be installed incorrectly
* Fix a bug when the last character of a post can be lost in the draft
* Improve plugin stability

= 1.0.1 =
* Update to WordPress 4.9.*
* Update documentation
* Improve plugin stability

= 1.0 =
* Stable version release

== Upgrade Notice ==
= 1.1.1 =
* Update to WordPress 5.3.*

= 1.1.0 =
* Update to WordPress 5.2.*

= 1.0.1 =
* Update to WordPress 4.9.*

= 1.0 =
* Stable version release