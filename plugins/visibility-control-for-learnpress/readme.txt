=== Visibility Control for LearnPress ===
Contributors: liveaspankaj
Donate link:
Tags: LearnPress, eLearning, LMS, Hide, Hide Content, Hide Message
Requires at least: 4.0
Tested up to: 6.4.2
Stable tag: trunk
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Visibility Control for LearnPress helps you hide messages and content for specific criterion anywhere on your WordPress page.

== Description ==

Visibility Control for [LearnPress](https://www.nextsoftwaresolutions.com/r/learnpress/wp_vclp_info_page) helps you hide messages and content for specific criterion anywhere on your WordPress page.

You can show/hide HTML elements, menus, and other details based on:
1. User's access to a particular **LearnPress Course**, Or
2. User is **Logged In** or **Logged Out**.
3. User's role.

You simply need to add a CSS class to your element div or span. As explained here:

**Example:**

Login/Logout Status:

* To show the element/menu item to a logged-in user, add this CSS class: **visible_to_logged_in** OR  **hidden_to_logged_out**
* To hide the element/menu item from a logged-in user, add this CSS class: **visible_to_logged_out** OR  **hidden_to_logged_in**

For user's role:
* To show the element/menu item to a user will role administrator, add this CSS class: **visible_to_role_administrator** OR **hidden_to_role_administrator**
* Note: To show an element to multiple specific roles only, you need add the element multiple times, one for each role. To hide an element/menu from specific multiple roles only you can add the element once add multiple classes to the same element.

If Course ID is 123

* To show the element/menu item to user with access to above Course, add this CSS class: **visible_to_course_123**
* To hide the element/menu item from user with access to above Course, add this CSS class: **hidden_to_course_123**
* To show the element/menu item to a logged-in user, add this CSS class: **visible_to_logged_in** OR  **hidden_to_logged_out**
* To hide the element/menu item from a logged-in user, add this CSS class: **visible_to_logged_out** OR  **hidden_to_logged_in**

For a course completion status, if Course ID is 123:

* To show the element/menu item to user who completed above course, add this CSS class: **visible_to_course_complete_123**
* To hide the element/menu item from user who completed above course, add this CSS class: **hidden_to_course_complete_123**
* To show the element/menu item to user who has not completed above course, add this CSS class: **visible_to_course_incomplete_123**
* To hide the element/menu item from user who has not completed above course, add this CSS class: **hidden_to_course_incomplete_123**

**Mechanism of Functioning**

* **Multiple CSS Classes:** If multiple visibility control classes are added, ALL of them must meet the criterion to keep the element visible. If any one of them hides the element, it will be hidden. For example: visible_to_course_123 visible_to_course_124 will show the element only to those who have access to both courses.
* Hidden data/elements reaches the browser. Though user's do not see it.
* CSS is added to the page for all CSS elements that needs to be hidden based on above rules.
* After page is loaded. These elements are removed from page using jQuery (if available), so it won't be available on Inspect.
* Elements rendered after the page load are hidden but not removed from DOM/page.

**Future Development**

Depending on the interest in this feature, we will decide on adding a shortcode and/or a Gutenberg Block to achieve this feature.

**Other Visibility Control Plugins:**
- [Visibility Control for LearnDash LMS](https://www.nextsoftwaresolutions.com/learndash-visibility-control/)
- [Visibility Control for WP Courseware LMS](https://www.nextsoftwaresolutions.com/visibility-control-for-wp-courseware/)
- [Visibility Control for LifterLMS](https://www.nextsoftwaresolutions.com/visibility-controlfor-lifterlms/)
- [Visibility Control for TutorLMS](https://www.nextsoftwaresolutions.com/visibility-control-for-tutorlms/)
- [Visibility Control for MasterStudyLMS](https://www.nextsoftwaresolutions.com/visibility-control-for-masterstudy/)
- [Visibility Control for Sensei LMS](https://www.nextsoftwaresolutions.com/visibility-control-for-sensei/)
- [Visibility Control for WooCommerce](https://www.nextsoftwaresolutions.com/woocommerce-visibility-control/)

**Related Plugins for LearnPress:**
- [Experience API for LearnPress LMS](https://www.nextsoftwaresolutions.com/experience-api-for-learnpress/)
- [Manual Completions for LearnPress LMS](https://www.nextsoftwaresolutions.com/manual-completions-for-learnpress/)

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/visibility-control-for-learnpress` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Add the CSS classes to your HTML elements or Menu Items as described in the Details section.

== Frequently Asked Questions ==

= What is LearnPress LMS? =

[LearnPress LMS](https://www.nextsoftwaresolutions.com/r/learnpress/wp_vclp_info_page) is a popular WordPress based Learning Management System (LMS) plugin. It includes many advanced features including, quizzing engine, course management, reports, certificates and payment methods.

You can also add [GrassBlade xAPI Companion plugin](https://www.nextsoftwaresolutions.com/grassblade-xapi-companion/) and [GrassBlade LRS](https://www.nextsoftwaresolutions.com/grassblade-lrs-experience-api/) to start using Experience API (xAPI) based contents with [LearnPress LMS](https://www.nextsoftwaresolutions.com/r/learnpress/wp_vclp_info_page).


= What is GrassBlade xAPI Companion plugin?  =

[GrassBlade xAPI Companion](https://www.nextsoftwaresolutions.com/grassblade-xapi-companion/) is a paid WordPress plugin that enables support for Experience API (xAPI)  based content on WordPress.

It also provides best in industry Advanced Video Tracking feature, that works with YouTube, Vimeo and self-hosted MP4 videos. Tracking of MP3 audios is also supported.

It can be used independently without any LMS. However, to add advanced features, it also has integrations with several LMSes.


= What is GrassBlade Cloud LRS? =

[GrassBlade Cloud LRS](https://www.nextsoftwaresolutions.com/grassblade-lrs-experience-api/) is a cloud-based Learning Record Store (LRS). An LRS is a required component in any xAPI-based ecosystem. It works as a data store of all eLearning data, as well as a reporting and analysis platform.  There is an installable version which can be installed on any PHP/MySQL based server.



== Screenshots ==

1. Show/hide message using CSS in HTML anywhere.
2. Show menu only to Loggout user
3. Show menu only to Logged In user (or Course/Group access)
4. Show a message if user has access to course (using HTML anywhere)
5. Show a message if user doesn't have access to course (using Additional CSS class)

== Changelog ==

= 1.7 =
* Improvement: Added menu item under LearnPress menu for easy access to settings page.

= 1.6 =
* Feature: Added support for course completion status: Example: visible_to_course_complete_123, visible_to_course_incomplete_123, hidden_to_course_complete_123, hidden_to_course_incomplete_123

= 1.5 =
* Feature: Added support for roles: Example: visible_to_role_administrator

= 1.4 =
* Added addons page

= 1.3 =
* Fixed: jQuery 3.0 conflict on some websites.

= 1.2 =
* Fixed: learn_press_is_enrolled_course is deprecated in LearnPress

= 1.1 =
* Improvement: Compatible with different editors now: Gutenberg, WPBakery Builder, Visual Composer, Elementor, Brizy Builder, Beaver Builder, Divi

= 1.0 =
Initial Commit

== Upgrade Notice ==

