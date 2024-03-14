=== Login Redirect ===
Contributors: eflyjason
Donate link: http://www.arefly.com/donate/
Tags: Redirect, Login
Requires at least: 3.0
Tested up to: 3.8
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Redirect to a link after login. 登入後跳轉至特定鏈接

== Description ==

Redirect to a link after login.

登入後跳轉至特定鏈接

= Translators =

* Chinese, Simplified (zh_CN) - [Arefly](http://www.arefly.com/)
* Chinese, Traditional (zh_TW) - [Arefly](http://www.arefly.com)
* English (en_US) - [Arefly](http://www.arefly.com)

If you have created your own language pack, or have an update of an existing one, you can send [gettext PO and MO files](http://codex.wordpress.org/Translating_WordPress) to [Arefly](http://www.arefly.com/about/) so that I can bundle it into Login Redirect. You can download the latest [POT file](http://plugins.svn.wordpress.org/login-redirect/trunk/lang/login-redirect.pot).

== Installation ==

###Updgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload it to `/wp-content/plugins/`.

This should result in the following file structure:

`- wp-content
    - plugins
        - login-redirect
            - lang
                | login-redirect-zh_CN.mo
                | login-redirect-zh_CN.po
                | login-redirect-zh_TW.mo
                | login-redirect-zh_TW.po
                | login-redirect.pot
                | readme.txt
            | login-redirect.php
            | LICENSE
            | license.txt
            | options.php
            | readme.txt`

Then just visit your admin area and activate the plugin.

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Frequently Asked Questions ==

= I cannot active this plugin, what can i do? =

You may post on the [support forum of this plugin](http://wordpress.org/support/plugin/login-redirect/) to ask for help.

= I love this plugin! Can I donate to you? =

YES! I do this in my free time and I appreciate all donations that I get. It makes me want to continue to update this plugin. You can find more details on [About Me Page](http://www.arefly.com/about/).

== Changelog == 

**Version 1.0.5**

* Fix Bug of Option. (Thanks to [_MattCapper_](http://wordpress.org/support/topic/no-login-redirect-choice?replies=1#post-5451669))

**Version 1.0.4**

* Remove All Remote Load File.

**Version 1.0.3**

* Fix Bug of `define`. (Thanks to cmhello)

**Version 1.0.2**

* Now you will still redirect to the URL of `$_GET['redirect_to']` if it is exist.

**Version 1.0.1**

* Fix Bugs.

**Version 1.0**

* Initial release.

== Upgrade Notice ==

See Changelog.