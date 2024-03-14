=== WP Courseware for WishList Member ===
Contributors: flyplugins
Donate link: https://flyplugins.com/donate
Tags: learning management system, selling online courses
Requires at least: 4.8
Tested up to: 5.8.1
Stable tag: 1.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin adds integration between WishList Member and WP Courseware which allows you to associate course(s) to membership levels for automatic enrollment.

== Description ==
[Fly Plugins](https://flyplugins.com) presents [WishList Member](https://flyplugins.com/wishlist-member) for [WP Courseware](https://flyplugins.com/wp-courseware).

= [IMPORTANT NOTE] = 
This integration is only intended for anyone using WishList Member prior to version 3.1 Build 6546, otherwise, WP Courseware has a native integration built into WishList Member.

= Would you like to sell an online course with WishList Member? =
The WishList Member Addon for WP Courseware will add full integration with WP Courseware. Simply assign WP Courseware course(s) to a WishList Member membership level. When a student purchases the membership level, they will automatically be enrolled into the associated course(s).

With this addon, you will be able to create a fully automated [Learning Management System](https://flyplugins.com/wp-courseware) and sell online courses.

= WishList Membership Plugin Integration with WP Courseware =
[youtube https://www.youtube.com/watch?v=i4N32tEzIzo]

= Basic Configuration Steps =
1. Create a course with WP Courseware and add module(s), unit(s), and quiz(zes)
2. Create a course outline page using [shortcode]
3. Create a membership level and set a price
4. Associate one or more WP Courseware courses with the membership level
5. New student pays for the membership level, and WP Courseware enrolls them to the appropriate course(s) based on the purchased membership level

= Check out Fly Plugins =
For more tools and resources for selling online courses check out:

* [WP Courseware](https://flyplugins.com/wp-courseware/) - The leading learning management system for WordPress. Create and sell online courses with a drag and drop interface. It’s that easy!
* [S3 Media Maestro](https://flyplugins.com/s3-media-maestro) - The most secure HTML 5 media player plugin for WordPress with full AWS (Amazon Web Services) S3 and CloudFront integration.

= Follow Fly Plugins =
* [Facebook](https://facebook.com/flyplugins)
* [YouTube](https://www.youtube.com/flyplugins)
* [Twitter](https://twitter.com/flyplugins)
* [Instagram](https://www.instagram.com/flyplugins/)
* [LinkedIn](https://www.linkedin.com/company/flyplugins)

= Disclaimer =
This plugin is only the integration, or “middle-man” between WP Courseware and WishList Member.

== Installation ==

1. Upload the `WishList Member addon for WP Courseware` folder into the `/wp-content/plugins/` directory
1. Activate the plugin through the plugins menu in WordPress
1. Configure the plugin by going into Training Courses-->WishList Member, then associate courses with membership levels (this assumes you already have courses and membership levels created).

== Frequently asked questions ==

= Does this plugin require WP Courseware to already be installed =

Yes!

= Does this plugin require WishList Member to already be installed =

Yes!

= Where can I get WP Courseware? =

[WP Courseware](https://flyplugins.com/wp-courseware)

= Where can I get WishList Member? =

WishList Member](https://flyplugins.com/wishlist-member).

== Screenshots ==

1. The Course Access Settings screen will display the courses associated with membership levels

2. This is the screen where specific courses are selected to be associated with the membership level. The retroactive function will enroll students to courses that were recently associated to the membership level.

== Changelog ==

= 1.4 =
* Fix: Fixed call to deprecated function for retrieving a list of membership levels.
* Fix: Fixed call to deprecated function for retrieving a list of user's assigned membership levels.

= 1.3 =
* Fix: Fixed issue where retroactive enrollment would timeout when the number of students was extremely high.

= 1.2 =
* Fix: Fixed issue where method was being called statically causing a deprecated error to appear with debug mode on.

= 1.1 =
* New: Added function to retroactively assign new courses to existing membership levels that have already been purchased

= 1.0 =
* Initial release


== Upgrade notice ==

