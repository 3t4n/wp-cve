=== Membee Login === 
Contributors: DaleAB, achilles_sm
Tags: membership, login, members, membee, social, authentication
Requires at least: 2.7.0
Tested up to: 6.4.3
Stable tag: 2.3.2
Add member authentication and access role management to your WordPress site via Membee's powerful Member Single Sign-On web service

== Description ==

This plug-in allows a WordPress developer to utilize the popular membership management system, [Membee](https://www.membee.com/) to control user access to a WordPress site. For a membership based organizations, this plug-in extends to WordPress the ability to manage access and roles within the member's record in Membee and then use the roles to permit access to content in a WordPress site. Since Membee allows for the creation of unlimited groups and committees, each with their own unlimited access roles, the WordPress developer has very granular control over access to content. For the client membership based organization, they gain the desired ability manage all aspects of their relationship with their member, including website content access in one place, Membee.

For example, the assignment a "BoardOnly" role to the "Board of Directors" committee in Membee would restrict access to website content secured in WordPress using the "BoardOnly" role. All roles created and managed in Membee are passed to WordPress via this plug-in so there are no additional steps to insure the roles are the same in Membee and the WordPress site. Since committee members inherit the access role from the committee, adding people to the committee or removing them instantly grants or removes the roll respectively. For the WordPress developer, this means one time only deployment of the functionality without the need to constantly revise their site as their client organization adds, drops, and revises groups and committees in Membee.

The plug-in also extends Membee's support for it's Social Login feature. This feature allows an organization to activate support for social network login in Membee to permit members to use their social network identity (Facebook, Twitter, Google, Yahoo, and LinkedIn) to access restricted website content and features. The plug-in allows the WordPress developer to permit the use of the social network identities by members to access content the developer has restricted access to. To extend the example above, a member serving on the Board of Directors could access the site content restricted with the "BoardOnly" access role using their Facebook username and password.

== Installation ==

Here's how to install the Membee Login plugin in your WordPress site:

1. Login to your WordPress site and go to the Dashboard 
1. Choose Plugins
1. Choose "Add New"
1. Type "membee" and click Search Plugins
1. When the Membee Login plugin appears in the search results, choose the "Install Now" link 
1. Answer "OK" to the "Are you sure you want to install this plugin?" prompt 
1. Choose "Activate Plugin" - takes you to the Installed Plugins section of the Dashboard
1. Choose Settings on the Dashboard
1. Choose Membee Login
1. In Membee Login Options, enter the Client ID, Secret, and Application ID (these are generated in Membee's "Programs & Access Roles" feature - see http://membee.zendesk.com/entries/21277223-setting-up-membee-s-integrated-login-system-with-your-wordpress-site )
1. Choose Save Options
1. You're done!

Known Issues

1) WordPress Access Control Plugin is Redundant

If the WordPress Access Control (WPAC) plugin is installed on your site, you should remove it because it is now redundant, it may cause erratic behavior in the Membee Member Login plugin, and it will prevent key components of WordPress such as PHP from updating to the current version. WPAC was popular in that it allowed the site admin to easily mark a page as "members only" and/or specify that a Member Access Role was required to view the page. Handy stuff. WPAC has gone without updates for a number of years and to address this, the Membee Member Login plugin was augmented to provide the same capability and as a result, WPAC should be removed from your site.

2) Plugin Options Not Being Saved

The Form Action to write the plugin's options to the WordPress database may have failed in a circumstance where the site was running on the HTTPS protocol. We have augmeted the plugin to insure the Form Action uses the protocol identified by the server. As a result, this issue of the plugin's options not being saved should be solved. If you still encounter the situation where the plugin's options are not being saved, please contact us directly at support@membee.com.

3) Occasional 302 Redirect Errors in AJAX Calls

It seems that for sites making a large number of AJAX calls, you may encounter an occasional 302 error from your AJAX function. Membee's Login plugin may be the culprit because membee_init() is called via a WordPress add_action 'init' so it is called for every AJAX call made. 

Note you should only make the following change if your site exhibits the behavior described above.

The fix is to temporarily disable the Membee Login plugin for the AJAX calls you are making. Here are the steps:

a) Open your theme's "functions.php" file and add the following code:

if (check_ajax_referer( 'your-special-string', 'security', false )) {  
  remove_action('init', 'membee_init');
}

b) Substitute arguments for check_ajax_referer function with values you used in your AJAX call

== Frequently Asked Questions == 

= Do we need use Membee to manage our membership? =

Yes. The sole purpose for this plugin is allow you to manage member access to protected content from their member record, committees, or groups in Membee while making it "check box easy" to grant or restrict access to content over in your WordPress site when you create a new page or section.

= What do we need to do when we get a new member in Membee? =

Nothing. Just send the member the link to setup their password ([here is how to do that in Membee for an individual member or a group of member](http://membee.zendesk.com/entries/20662423/)) and once they have setup their password or selected a Social Network to use to login, the plugin takes it from there. When the member then visits your site and tries to access a "members only" content area, their latest and greatest access role information is updated in WordPress via the Membee Login plugin. 

= How do we create access roles in Membee? =

Easy. In Membee, just create the role and then assign it to a group ( a list of people) or a committee. Once the role is associated with the group and/or committee, all of the members in that group or committee automatically get the role. Similarly, remove someone from a group or committee and they automatcially lose any role(s) associated with that group or committee. [Here are the steps in Membee](http://membee.zendesk.com/entries/20730812/).

= Do our members need to login twice if we use other Membee features on our site? =

No. The Membee Login plugin supports Membee's full member single sign-on service. So, a member can choose to login to access "members only" content and then decide to update the member profile in Membee's Profile widget and Membee knows who they are and presents their member profile to them for updating. Yes, the reverse scenario works too!

== Changelog ==

= 2.3.2 = 
* Tested for WordPress version 6.4.3.

= 2.3.1 = 
* Tested for WordPress version 6.2.

= 2.3.0 = 
* Minor structural clean up and tested for WordPress version 6.1.

= 2.2.8 = 
* Tested for WordPress version 6.0.3.

= 2.2.7 = 
* Tested for WordPress version 5.9.3 and removed one minor conflict.

= 2.2.6 = 
* Tested for WordPress version 5.8.2.

= 2.2.5 = 
* Corrected a condition that was not retaining the correct post-login redirect URL.

= 2.2.4 = 
* Corrected a condition that would prevent a proper redirect after login in specific scenario.

= 2.2.3 = 
* Tested for compatibility with WordPress 5.7.1 and fixed a situation where the member's email address as not being updated after being changed by the member in Membee.

= 2.2.2 = 
* Tested for compatibility with WordPress 5.7

= 2.2.1 = 
* Eliminated an unnecessary redirect.

= 2.2.0 = 
* Tested for compatibility with WordPress 5.6 & Nickname now contains the member's first name.

= 2.1.7 = 
* Tested for compatibility with WordPress 5.5.

= 2.1.6 = 
* Tested for compatibility with WordPress 5.4.

= 2.1.5 = 
* Fixed another minor non-performance related PHP syntax error caused by a deprecated function.

= 2.1.4 = 
* Fixed some minor non-performance related PHP syntax errors.

= 2.1.3 = 
* Removed a now unnecessary redirect previously needed for a deprecated feature to improve performance.

= 2.1.2 = 
* Replaced deprecated functions to eliminate erroneous error messages.

= 2.1.1 = 
* Removed an obscure bug that cause login to fail if an already secured Membee widget was placed on a members only WP page

= 2.1.0 = 
* Compatibility tested for WordPress 5.2

= 2.0.7 = 
* Compatibility tested for WordPress 4.9

= 2.0.6 = 
* This update addresses a circumstance where the plugin's options would not be saved to the WordPress database
* If you have the WordPress Access Control (WPAC) plugin installed, it is highly recommended you deactivate and delete it

= 2.0.5 = 
* Added code to protect against a fatal error caused by the redundant WordPress Access Control plugin
* If you have the WordPress Access Control plugin installed, it is highly recommended you deactivate and delete it

= 2.0.4 = 
* Fixed typo in the Upgrade notification

= 2.0.3 = 
* The Membee Member Login plugin now attempts to deactivate redundant plugins relating to member login

= 2.0.2 = 
* Updating the Membee Member Login plugin now checks to make sure redundant plugins have been removed

= 2.0.1 = 
* Strengthened user notification to remove the redundant WordPress Access Control plugin before updating the Membee Member Login plugin

= 2.0.0 = 
* Tested for WordPress 4.8.1
* PHP 7 compatibility
* Added capability to specify a page, post, or custom post type as Members Only and specify Member Access Role if required.

= 1.3.0 =
* Tested for WordPress 4.8
* Stopped unnecessary redirects caused by search bots
* Improved cache management to correct some redirects after login

= 1.2.9 =
* Tested for WordPress 4.7.2
* Minor updates to some inline documentation

= 1.2.8 =
* Tested for WordPress 4.7.2
* Closed a hole where a site on https could redict login incorrectly

= 1.2.7 =
* Tested for WordPress 4.7.1
* Strengthened support for absolute paths

= 1.2.6 =
* Tested for WordPress 4.6.1
* Added an exclusion to ignore MailChimp's checking of the site's RSS feed

= 1.2.5 =
* Tested for WordPress 4.6

= 1.2.4 =
* Tested for WordPress 4.5

= 1.2.3 =
* Tested for WordPress 4.4.2
* Fix for specific situation where Remember Me that would not remember the member in subsequent sessions

= 1.2.2 =
* Tested for WordPress 4.3.1
* Minor housekeeping and reorganization

= 1.2.1 =
* Tested for WordPress 4.2.1
* Minor efficiency changes

= 1.2.0 =
* Corrected rare scenario where upon logging out, a site visitor would be redirected to the native WordPress login
* Removed an unnecessay redirect
* Tested for WordPress 4.0

= 1.1.7 =
* Removed a conflict with a Google+ plugin
* Added friendlier error handling
* Will retain settings when WordPress updates its version

= 1.1.6 =
* Removed a conflict with FeedBurner

= 1.1.5 =
* Fixed a jQuery issue with the flyout feature of the login

= 1.1.4 =
* Insured that the plugin uses the latest stable version of the jQuery UI Core

= 1.1.3 =
* Insured that the plugin uses the latest stable version of the jQuery library

= 1.1.2 =
* Addressed the erroneous generation of two script errors that did not affect the performance of the plugin

= 1.1.1 =
* Removed an issue with a social sharing plugin that prevented the "fetching" of images from a WP post when a user was trying to share the post on a social network. The plugin now allows for people in the WP Users table with site admin roles to inherit member roles defined & managed in Membee. 

= 1.0.4 =
* Revision to better take advantage of WordPress' ability to hide/display menu choices via the "Display In Menus" feature based on whether or not the site visitor has logged in

= 1.0.3 = 
* Approved for public release* Minor update to fix an obscure cirumstance where members using one of their social network identities to login to the WordPress site may not be authenticated correctly

= 1.0.2 = 
* Strenghtened the check to insure all access is removed in the WordPress site if the member's login access is deactivated completely in their member record in Membee

= 1.0.1 =
* Fixed a bug that prevented accurate updating of the member's login information in WordPress if they had muitple roles in Membee

= 1.0.0 =
* Initial release for testers.


== Upgrade Notice ==

= 2.3.2 = 
* Tested for WordPress version 6.4.3.

= 2.3.1 = 
* Tested for WordPress version 6.2.

= 2.3.0 = 
* Minor structural clean up and tested for WordPress version 6.1.

= 2.2.8 = 
* Tested for WordPress version 6.0.3.

= 2.2.7 = 
* Tested for WordPress version 5.9.3 and removed one minor conflict.

= 2.2.6 = 
* Tested for WordPress version 5.8.2.

= 2.2.5 = 
* Corrected a condition that was not retaining the correct post-login redirect URL.

= 2.2.4 = 
* Corrected a condition that would prevent a proper redirect after login in specific scenario.

= 2.2.3 = 
* Tested for compatibility with WordPress 5.7.1 and fixed a situation where the member's email address as not being updated after being changed by the member in Membee.

= 2.2.2 = 
* Tested for compatibility with WordPress 5.7

= 2.2.1 = 
* Eliminated an unnecessary redirect.

= 2.2.0 = 
* Tested for compatibility with WordPress 5.6 & Nickname now contains the member's first name.
IMPORTANT: If you have the WordPress Access Control plugin installed, it is now redundant (since version 2.0.6 of Membee's plugin).  Be sure to deactivate and delete the WordPress Access Control plugin before updating to this newest version of Membee Login. This version of Membee Login includes the capability to specify whether a page, post, or custom post type is set to Members Only.

= 2.1.7 = 
* Tested for compatibility with WordPress 5.5.
IMPORTANT: If you have the WordPress Access Control plugin installed, it is now redundant (since version 2.0.6 of Membee's plugin).  Be sure to deactivate and delete the WordPress Access Control plugin before updating to this newest version of Membee Login. This version of Membee Login includes the capability to specify whether a page, post, or custom post type is set to Members Only.

= 2.1.6 = 
* Tested for compatibility with WordPress 5.4.
IMPORTANT: If you have the WordPress Access Control plugin installed, it is now redundant (since version 2.0.6 of Membee's plugin).  Be sure to deactivate and delete the WordPress Access Control plugin before updating to this newest version of Membee Login. This version of Membee Login includes the capability to specify whether a page, post, or custom post type is set to Members Only.

= 2.1.5 = 
* Fixed another minor non-performance related PHP syntax error caused by a deprecated function.
IMPORTANT: If you have the WordPress Access Control plugin installed, it is now redundant (since version 2.0.6 of Membee's plugin).  Be sure to deactivate and delete the WordPress Access Control plugin before updating to this newest version of Membee Login. This version of Membee Login includes the capability to specify whether a page, post, or custom post type is set to Members Only.

= 2.1.4 = 
* Fixed some minor non-performance related PHP syntax errors.
IMPORTANT: If you have the WordPress Access Control plugin installed, it is now redundant (since version 2.0.6 of Membee's plugin).  Be sure to deactivate and delete the WordPress Access Control plugin before updating to this newest version of Membee Login. This version of Membee Login includes the capability to specify whether a page, post, or custom post type is set to Members Only.

= 2.1.3 = 
Removed a now unnecessary redirect previously needed for a deprecated feature to improve performance.
IMPORTANT: If you have the WordPress Access Control plugin installed, it is now redundant (since version 2.0.6 of Membee's plugin).  Be sure to deactivate and delete the WordPress Access Control plugin before updating to this newest version of Membee Login. This version of Membee Login includes the capability to specify whether a page, post, or custom post type is set to Members Only.