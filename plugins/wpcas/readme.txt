=== wpCAS ===
Contributors: misterbisson
Donate link: http://MaisonBisson.com/
Tags: cas, phpcas, wpcas, central authentication service, authentication, auth, integration
Requires at least: 2.7
Tested up to: 2.7.1
Stable tag: trunk

Plugin to integrate WordPress or WordPressMU with existing <a href="http://en.wikipedia.org/wiki/Central_Authentication_Service">CAS</a> architectures. Based largely on <a href="http://schwink.net">Stephen Schwink</a>'s <a href="http://wordpress.org/extend/plugins/cas-authentication/">CAS Authentication</a> plugin. 

== Description ==

wpCAS integrates WordPress into an established CAS architecture, allowing centralized management and authentication of user credentials in a heterogeneous environment.

<a href="http://en.wikipedia.org/wiki/Central_Authentication_Service">From Wikipedia</a>:

<blockquote>The Central Authentication Service (CAS) is a single sign-on protocol for the web. Its purpose is to permit a user to log into multiple applications simultaneously and automatically. It also allows untrusted web applications to authenticate users without gaining access to a user's security credentials, such as a password. The name CAS also refers to a software package that implements this protocol.</blockquote>

Users who attempt to login to WordPress are redirected to the central CAS sign-on screen. After the user's credentials are verified, s/he is then redirected back to the WordPress site. If the CAS username matches the WordPress username, the user is recognized as valid and allowed access. 

<a href="http://en.wikipedia.org/wiki/AuthZ">Authorization</a> of that user's capabilities is based on native WordPress settings and functions. CAS only authenticates that the user is who s/he claims to be.

If the CAS user does not have an account in the WordPress site, an administrator defined function can be called to provision the account or do other actions. By default, CAS users without WordPress accounts are simply refused access.

== Installation ==

1. Download <a href="http://www.ja-sig.org/wiki/display/CASC/phpCAS">phpCAS</a> and place it on your webserver so that it can be included by the wpCAS plugin.
1. Place the plugin folder in your `wp-content/plugins/` directory and activate it.
1. Set any options you want in Settings -> wpCAS _or_ in the `wpcas-conf.php` file.
1. The plugin starts intercepting authentication attempts as soon as you activate it. Use another browser or another computer to test the configuration.

= wpcas-conf.php =
wpCAS can be configured either via the settings page in the WordPress dashboard, or via a configuration file. See `wpcas-conf-sample.php` for an example. If a config file is used, it overrides any settings that might have been made via the settings page and that page is hidden. 

Use of `wpcas-conf.php` is recommended for WordPressMU installations, as doing so hides the settings menu from users.

= WordPressMU Installation =

1. Download <a href="http://www.ja-sig.org/wiki/display/CASC/phpCAS">phpCAS</a> and place it on your webserver so that it can be included by the wpCAS plugin.
1. Place the plugin `wpcas.php` in your `wp-content/mu-plugins/` directory.
1. Make a copy of `wpcas-conf-sample.php`, rename it `wpcas-conf.php`, and put it in your `wp-content/mu-plugins/` directory.
1. Set the options in the config file.
1. The plugin starts intercepting authentication attempts as soon as you activate it. Use another browser or another computer to test the configuration.
1. Consider creating a function to provision user accounts for CAS-authenticated users who do not have WordPress accounts.

== Frequently Asked Questions ==

= What version of phpCAS should I use? =
I've only tested it with the 1.0 release available from ja-sig.

= How's it work? =
Users who attempt to login to WordPress are redirected to the central CAS sign-on screen. After the user's credentials are verified, s/he is then redirected back to the WordPress site. If the CAS username matches the WordPress username, the user is recognized as valid and allowed access. If the CAS username does not exist in WordPress, you can define a function that could provision the user in the site.

= You keep talking about provisioning users. How? =
Each environment is different; each environment probably needs its own solution for this. I've posted my <a href="http://maisonbisson.com/projects/wpcas">user provisioning script</a> here, if you find something in there that works, <a href="http://maisonbisson.com/projects/wpcas">leave a comment</a>.

= What's the relationship between LDAP and CAS? =
There is none.

= This looks familiar... =
You might be thinking of <a href="http://schwink.net">Stephen Schwink</a>'s <a href="http://wordpress.org/extend/plugins/cas-authentication/">CAS Authentication</a> plugin. This plugin would be a lot different if I couldn't lean on Stephen's excellent work. My primary reasons for branching (under the the terms of the GPL) were that I wanted it to work better with WPMU and needed an easier way to hook-in functions to provision users and wanted to do that while also making it easy to upgrade using SVN (thus the config file).
