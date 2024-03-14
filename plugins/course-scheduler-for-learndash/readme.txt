=== Course Scheduler for LearnDash ===
Contributors: wooninjas
Tags: lms, learndash, course, schedule, learning, course-scheduler, schedule courses using learndash
Requires at least: 4.0
Tested up to: 6.1.1
Stable tag: 1.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Course Scheduler for LearnDash is an add-on which enables admin to activate/deactivate courses for specific dates.

== Description ==

This is a LearnDash addon to enable the scheduling of learndash courses. It enables admins to simply drag and drop the
active courses on the site to a calendar. The addon has 2 options, whether to show the courses on the dates set on the
calendar OR to show the courses on all dates except the ones set on the calendar from the menu Course scheduler. It also provides the ability to set custom messages for the scheduled courses/lessons/topics/quizzes before the course is accessible on the specified dates

Once the plugin is installed successfully following menu will be shown in the admin under "Course Scheduler" menu
- Calendar (sub menu)
- Setting Page (sub menu)

For more advanced features, check out our [Course Planner Pro](https://wooninjas.com/downloads/learndash-course-planner-pro/ "Course Planner Pro")

== Prerequisites ==

* LearnDash

== Features ==

* Show courses on specific dates
* Hide courses on specific dates
* Drag and drop courses on calendar UI
* Admin can add multiple courses on the same date
* Admin can add the same course on multiple dates
* A course that is already dropped on the calendar can be dragged and dropped on any other date on the calendar.
* Course can be removed from the calendar through the cross button available on the course.
* Admin can view previous courses added to the calendar as they show up on the calendar afterward.
* There are 2 modes available for the add-on which can be set on the settings page, "Show Courses on specified dates" is the default option.
* Show Courses on specified dates: Set this option if you want to show the courses "only" on the dates set on the calendar
* Show courses except for the specified dates: Set this option if you want to show the courses "except" the dates set on the calendar.
* A course that is scheduled to not show on a specific date, will show a message about the unavailability of the course
* All related topic's, lesson's and quizzes access will be blocked if course is not accessible.
* Admin can change frontend messages from setting's page.
* Widget to display the scheduled courses
* Option to customize the widget texts
== Installation ==

Before installation please make sure you have the latest Learndash plugin installed.

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Course scheduler->settings menu to configure the plugin.
4. Go to `WooNinjas > Calendar` menu
5. Drag & Drop courses from sidebar to the calendar and schedule it

== Frequently Asked Questions ==

= Does this plugin allow recurring schedule of courses? =

This feature is not present in the addon.

= Does it show all courses in the navigation menu of calendar page? =

It shows only the "published" courses in the left navigation, only published courses could be added to the calendar.

= Can I add multiple courses on the same date? =

Yes, You can add multiple courses on the same date.

== Screenshots ==

1. Calender to Schedule Courses assets/calender.png
2. General Setting Page assets/general-settings.png
3. Course Page Message Setting assets/course-page-setting.png
4. Lesson Page MessageSetting assets/lesson-page-setting.png
5. Quiz Page Message Setting assets/quiz-page-setting.png
6. Topic Page Message Setting assets/topic-page-setting.png
7. Front End Messages assets/frontend.png
8. Scheduled Courses Widget assets/course-scheduler-widget.png

== Changelog ==

= 1.5.1 =
* New: Added new admin menu
* New: Removed WooNinjas dashboard
* Fix: Settings selection issue
* Fix: Critical error when Pro version is activated
* Fix: Missing plugin link in required plugin message

= 1.5.0 =
* Fix: JavaScript errors on WordPress 5.6 and above
* New: Ajax course search
* New: Calendar events tooltip
* Fix: Other UI Improvement

= 1.4 =
* New: Added new widget to display the scheduled courses/lessons/topics/quizzes
* New: Made the add-on compatible with LearnDash labels
* Fix: Made the add-on compatible with latest versions of LearnDash & WordPress

= 1.3 =
* New: Added support to display dates on front end with the date format admin selected on WordPress setting page
* Fix: Show/Hide course content issue on specific dates selected
* Fix: Ajax 500 response error when scheduling courses
* Fix: Typo

= 1.2 =
* Remove past dates from calendar schedule 

= 1.1 =
* Fixed the use of invalid function and warning on plugin activation 

= 1.0 =
* Initial