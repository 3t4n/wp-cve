=== Adaptive Learning With LearnDash ===
Contributors: wooninjas
Donate link:
Tags: learndash, lms, adaptive, learning, quiz, student, performance
Requires at least: 5.1
Tested up to: 6.1.1
Stable tag: 1.7
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adaptive learning with LearnDash allow admin to apply the concept of adaptive learning to LearnDash and make non linear course pattern for the students. It enables admin to create multiple child courses and associate distinct levels with each child course.

== Description ==

This add-on allow course admin to design courses in a non-linear fashion, there can be a variety of child courses each with a distinct course level, the student will be assigned child course based on their performance in the quiz/quizzes of the prerequisite/deterministic/parent course.

For more advanced features, check out our [Adaptive Learning Pro](https://wooninjas.com/downloads/learndash-adaptive-learning-pro/ "Adaptive Learning Pro!")

**Prerequisites**

* [LearnDash LMS](https://www.learndash.com/ "LearnDash LMS")

**Features**

* Allow your students to unlock a child course based on the result of parent course
* Create multiple course levels/percentage range
* Associate multiple courses to each level
* Get stats for each user progress

**How It Works**

== Parent Courses ==

These course are design to determine the behavior of each student and understand the learning pace/style of each student. Parent course percentage is a *total of all scores* obtained in parent course *quizzes*. Set the **Access Mode** of these courses to **Open/Free/Buy Now/Recurring**.

== Child Courses ==

These course are child/sub courses which will be assigned to student based on their performance in parent courses. Parent course will be assigned to these child courses as a prerequisite course. Set the **Access Mode** of these courses to **Closed**

== Course Levels ==

To determine which "*child course*" should be assigned to student based on the the percentage acquired in "parent course", admin needs to create "*Course Levels*". Admin can define a percentage range in each course level, child courses will be assigned to student if the acquired percentage fall in any of these level's percentage range.

*Example*

*Course Level 1 -> 0%  to 33%*
*Course Level 2 -> 34%  to 66%*
*Course Level 3 -> 67%  to 100%*


**Real Use Case:** In a parent course, say, Course 1, there is one quiz and a student obtains a result of 20% in that quiz, now, If there is a course level corresponding to this range and If such level is associated with any of the child courses by the admin, the completion of Course 1 will automatically assign the specific child course corresponding to that course level and the student will be able to access that particular child course "only".

**Note:** It is important to note that the child courses should be created with the LearnDash course type "closed" so that only the child course corresponding to the specific course level is assigned to the student and other child courses would remain inaccessible.

For any support or assistance  reach us at [WooNinjas](https://wooninjas.com/contact/)

== Installation ==

Before installing this plugin, please make sure you have the latest version of LearnDash LMS installed and activated.

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

== Frequently Asked Questions ==

= How many child courses can be assigned to a prerequisite course?

The admins can create any number of child courses and "levels" for these "child courses".

= What happens if we have multiple quizzes in the parent course?

If there are multiple quizzes in the parent course, the average will be calculated from these quizzes and the average percentage value will be considered as the final percentage score to be associated with a course level.


== Screenshots ==

1. Edit course level
1. Edit course level percentage range
1. Assigning pre-requisite course and level to child course
1. Adaptive learning stats, with assign parent and assign child courses details

== Changelog ==

= 1.7 =
* Fix: Compatibility issues with latest versions of LearnDash and WordPress

= 1.6 =
* New: Added support to assign multiple child courses for single parent course
* Fix: Compatibility issues with latest versions of LearnDash and WordPress
* Fix: Fixed invalid course level assignments

= 1.5 =
* Fix: Compatibility issues with latest versions of LearnDash and WordPress

= 1.4 =
* Fix: Remove direct db call, used LearnDash provided functions
* Fix: Fix ajax 500 error issue

= 1.3 =
* Fix: Compatibility issues with latest versions of LearnDash and WordPress
* New: Added animated notification when enrolled to associated course
* New: Displayed associated course on course detail page
* New: Added filter "notification_message" to override the notification message

= 1.2 =
* New: Added plugin branding

= 1.1 =
* Fix: Compatibility issues with latest versions of LearnDash and WordPress

= 1.0 =
* Initial

== Upgrade Notice ==