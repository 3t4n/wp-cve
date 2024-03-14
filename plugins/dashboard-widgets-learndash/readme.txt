=== Dashboard Widgets for LearnDash ===
Contributors: escapecreative, davewarfel
Donate link: https://www.paypal.me/escapecreative/10
Tags: learndash, dashboard widget, lms, learning management system
Requires at least: 4.6
Tested up to: 5.9
Stable tag: 1.3
Requires PHP: 5.6
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Simple, informative, beautifully-designed Dashboard widgets for your LearnDash-powered site. Quick stats at a glance, plus helpful links for faster admin navigation.

== Description ==

Dashboard Widgets for LearnDash adds 4 widgets to the Dashboard page in your WordPress admin area -- Overview, Courses, Links & Recently Modified.

* The LearnDash Overview widget shows you the total number of courses, lessons, topics, certificates, quizzes, questions, assignments, essays & groups.
* The LearnDash Courses widget displays a list of up to 10 courses, with handy stats & quick links to edit content that belongs to that course.
* The Links widget exposes most internal LearnDash pages for quick navigation.
* The Recently Modified widget displays the 3 most recently modified courses, lessons & topics. Links are available to edit content, open the course builder or access the individual course settings page.

**NOTE:** By default, only Administrators will see the widgets. See the FAQ below about "user/role permissions" to learn how to enable them for additional users.

### LearnDash Overview Widget

The Overview widget provides the following information:

* Total Courses
* Total Lessons
* Total Topics
* Total Certificates
* Total Quizzes
* Total Questions
* Total Assignments
* Total Essays
* Total Groups
* Total Group Leaders

Clicking on each total will take you to the admin page that lists out the respective content.

### LearnDash Courses Widget

The Courses widget provides a scrollable list of up to 10 courses. The information displayed depends on the user's capabilities & access to certain admin pages. I'll explain each piece of information below.

Visible to **all**:

* Course Title
* Course Status (draft, scheduled, pending review, etc.)
* Course Price Type
* Course Scheduled Date (if course is scheduled for a future date)
* "View" link

Visible if user has BOTH **edit_courses** AND **edit_published_courses** capabilities:

* "Edit" link (to edit the course)

Visible if user has **edit_courses** capability:

* Links to Lesson/Topic/Quiz list pages, along with the total number associated with the course
* Certificate Title, with a link to edit it (if a certificate has been associated with the course)

Visible if user has **list_users** capability:

* "Students" link that displays the list of users who are enrolled in that course

### LearnDash Links Widget

The Links widget contains the following sections, each with their own set of links to different admin pages.

**LearnDash Settings**
General | Registration | Payments | Emails | Custom Labels | Support

**Courses**
View All | Add New | Settings | Categories | Tags

**Lessons**
View All | Add New | Settings | Categories | Tags

**Topics**
View All | Add New | Settings | Categories | Tags

**Quizzes**
View All | Add New | Settings | Essays: Graded | Not Graded

**Assignments**
View All | Approved | Not Approved | Settings

**Groups**
View All | Add New | Settings | Administration | Group Leaders

#### Visibility & Permissions

Who sees each section, as well as the links within each section, will depend on a few things. Here are the important things to know:

* **LearnDash Settings:** Only visible to users with the **manage_options** capability, which by default, is only Administrators.
* **Courses & Lessons:** Only visible to users with the **edit_courses** capability.
* **Topics & Quizzes:** First, you must have at least one published Topic or Quiz for each section to be displayed. Then, they're only visible to users with the **edit_courses** capability.
* **Course/Lesson/Topic/Quiz/Assignment Settings:** Only visible to users with the **manage_options** capability.
* **Categories & Tags:** Only visible to users with the **manage_categories** capability.
* **Essays:** At least one essay must exist. Then, only visible to users with the **edit_essays** capability.
* **Assignments:** At least one assignment must exist. Then, only visible to users with the **edit_assignments** capability.
* **Groups:** At least one group must exist. Then, only visible to users with the **edit_groups** capability.
* **Group Administration:** Only visible to Group Leaders.
* **Group Leaders:** Only visible to users with the **list_users** capability.

### LearnDash Recently Modified Widget

This widget displays the 3 most recently modified courses, lessons, topics, and quizzes.

For Courses, you can navigate directly to "Edit," "Builder" or "Settings."

For Lessons, Topics, and Quizzes, each title links directly to the edit page for that piece of content.

It also includes the last modified date/time (i.e. 3 days ago, 5 months ago, etc.).

### Other Features

* Just like all Dashboard widgets, they can be hidden by unchecking their respective boxes under "Screen Options"

Tested with LearnDash 3.6.0.3

### Roadmap

There is no scheduled roadmap, but if you have any suggestions, please let us know in the [support forum](https://wordpress.org/support/plugin/dashboard-widgets-learndash).

== Installation ==

=== From within WordPress ===

1. Visit "Plugins > Add New"
1. Search for "Dashboard Widgets for LearnDash"
1. Click the "Install" button
1. Click the "Activate" button

== Frequently Asked Questions ==

= Who can see the widgets? (user/role permissions) =

We decided to show the widgets to users with the "edit_dashboard" capability. By default, this only includes the "Administrator" role.

Any other role, by default, does not have access to edit courses, lessons, topics or quizzes, thus rendering most of the quick links useless. If you want another user role to see the widgets, we suggest using the [User Role Editor plugin](https://wordpress.org/plugins/user-role-editor/) to add the "edit_dashboard" capability to that user role.

Additionally, the links in the Overview & Links widget only work if the user has access to the corresponding page. For example:

* **edit_courses** - Can access list pages for Courses, Lessons, Topics, Certificates, Questions & Quizzes
* **edit_assignments** - Can access the list page for Assignments
* **edit_essays** - Can access the list page for Essays
* **edit_groups** - Can access the list page for Groups
* **list_users** - Can access the "Users > All Users" page
* **group_leader** - Can access the "Group Administration" page
* **manage_options** - Can access most settings pages
* **manage_categories** - Can access & manage all Category & Tag pages

For the Courses widget:

* The "Edit" link will appear if the user has BOTH the **edit_courses** & **edit_published_courses** capabilities
* The lesson/topic/quiz links & counts just need the **edit_courses** capability
* The "Students" link will appear if the user has the **list_users** capability

For the Recently Modified widget:

* The **edit_courses** capability is required to use this widget

= How many courses are displayed in the LearnDash Courses widget? =

We have chosen to display up to 10 courses. This cannot be customized.

= In what order are the courses displayed in the LearnDash Courses widget? =

First, the courses are sorted by their Menu Order, which can be manually chosen/updated by anyone with access to edit a course. If two courses share the same Menu Order, they are then sorted by their course title. This cannot be customized.

= Can I change how many items appear in the Recently Modified widget? =

No. The 3 most recently modified will always be displayed.

= My numbers are incorrect. What's going on? =

In the **Overview widget,** the numbers reflect **published** content.

In the **Courses widget:**

* For courses, we display all courses except those in the trash, and revisions
* For lessons, topics & quizzes, we only count the **published** content that is associated with the course. Drafts, revisions & trashed content do not affect counts.

If you still think your numbers are off, it might be a bug. Please let us know in the [support forum](https://wordpress.org/support/plugin/dashboard-widgets-learndash) so we can try to fix it.

== Screenshots ==

1. The LearnDash Overview widget
2. The LearnDash Courses widget
3. The LearnDash Links widget
4. The LearnDash Recently Modified widget
5. Show/Hide the widgets using "Screen Options"

== Changelog ==

= 1.3 - Jan 24, 2022 =

- Added: LearnDash Links: Settings: Links to Registration, Payment, and Email settings (added in LearnDash 3.6)
- Added: Recently Modified: Added 3 most recently modified quizzes
- Fixed: LearnDash Links: Link to Custom Labels page
- Fixed: LearnDash Links: Assignments: Approved & Unapproved assignment links

= 1.2.2 - Dec 8, 2020 =

- Added: LearnDash Links: Link to the Group Settings page (added in LearnDash 3.2)
- Added: LearnDash Overview: Number of Group Leaders

= 1.2.1 - Dec 25, 2019 =

- Changed: Courses Widget: Removed extra space after Lessons, Topics & Quizzes links
- Changed: Recently Modified Widget: Updated link style for Lessons & Topics

= 1.2 - Dec 20, 2019 =

- New: LearnDash Recently Modified: Courses now include Edit, Builder & Settings links
- Added: LearnDash Links: Assignments now has a "View All" link
- Changed: Removed "Data Upgrades" from LearnDash Links widget

= 1.1 - Dec 20, 2019 =

- New: LearnDash Links widget displays links to many internal LearnDash pages for quick navigation.
- New: LearnDash Recently Modified widget shows the 3 most recently modified courses, lessons & topics, with links to go directly to their edit pages.
- New: Total Essays was added to the Overview widget
- New: "Students" links were added to each course in the Courses widget
- Changed: Small CSS improvements, including a few styles to better support RTL languages

= 1.0 - Jan 15, 2019 =

- Initial release