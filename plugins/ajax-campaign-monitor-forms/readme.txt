=== Plugin Name ===
Contributors: leewillis77
Donate link: http://www.leewillis.co.uk/wordpress-plugins/?utm_source=wordpress&utm_medium=www&utm_campaign=ajax-campaign-monitor-forms
Tags: campaign monitor, email, subscribers, mailing list
Requires at least: 4.3
Tested up to: 4.9
Stable tag: 1.5.0

== Description ==

A WordPress plugin that adds Ajax powered forms to allow site visitors to sign up to your Campaign Monitor powered email lists.

== Installation ==

1. Upload the plugin to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

Adding a form to your sidebars

* Add the widgets to your sidebar from Appearance > Widgets
* Add your account API key and list API key to the widget
* Save

Adding a form to a post or page

* Edit the post/page
* Click the "Campaign Monitor" button [Screenshot of button](http://s.wordpress.org/extend/plugins/ajax-campaign-monitor-forms/screenshot-5.png?r=333233)
* Enter your account API Key and list API key
* Save

== Frequently Asked Questions ==

= Where do I find my Campaign Monitor API keys? =
Campaign Monitor have an excellent guide here:
http://www.campaignmonitor.com/api/getting-started/

= Is there a shortcode, so I can insert the form into posts or pages? =
Yes, but it's beta - please let me know if it works for you! Just click the CampaignMonitor button in the post/page editor

= What if users don't have Javascript enabled? =
The widget falls back to a standard web page request, but will still keep users on your site, unlike the normal CampaignMonitor forms.

= I've published the form, but it won't sign users up =
The most common faults are:

* PHP needs to be 5.2 or above
* Make sure you've entered the Account ID and List ID, not the Client ID

Check out the [debugging guide](http://www.leewillis.co.uk/debugging-problems-campaign-monitor-widgets/) to find out exactly what your problem might be.

== Screenshots ==

1. Configuration
2. Sign-up form
3. Ajax submission
4. Feedback
5. Inserting a shortcode
6. Choosing a shortcode / creating a new shortcode

== Changelog ==

= 1.5.0 =
Update Campaign Monitor libraries to avoid deprecated constructor warnings.

= 1.4 =
WordPress 4.3 compatibility.

= 1.3 =
Use latest Campaign Monitor API
Some UI tweaks in the admin area

= 1.2 =
Don't throw warnings on more recent PHP versions
Minor markup fix

= 1.1 =
Add translation triggers, and French translation from Boris Rannou

= 1.0 =
Check classes aren't defined before (re)defining them. Fixes issues if you already load the CM API in another plugin

= 0.9 =
Fixes for multiple widgets not picking up the correct settings

= 0.8 =
More fixes for the shortcode forms

= 0.7.4 =
Fix problem with shortcode button

= 0.7.2 =
Log debug issues for easier diagnosis of failed connections. No need to upgrade to this if you're up and running already

= 0.7.1 =
Tweaks for some issues with jQuery in the admin area. Should solve problems with shortcode not being inserted properly

= 0.7 =
Don't include services_json if class already defined

= 0.6 =
HTML tweaks from timvanoostrom

= 0.5 =
Small bugfixes, and allow user to pick from list of existing shortcodes

= 0.4 =
Add button to the editor to allow shortcode to be inserted into posts (Beta - please let me know if this works for you!)

= 0.3 =
Fix compatability with some themes

= 0.2 =
Commit missing files & remove dev branch stuff

= 0.1 =
First release
