=== TP Education ===
Contributors: themepalace
Tags: Custom Post Type, Meta data, Education
Donate link: http://themepalace.com
Requires PHP: 5.6
Requires at least: 4.7
Tested up to: 6.2
Stable tag: 4.5
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Enhance your educational sites more efficiently. Allow user to utilize post types and meta data on your site with TP Education.

== Description ==
	A plugin to add custom post type ( Events, Courses, Classes, Excursions, Team, Testimonial, Affiliation ) and it's required meta fields for educational sites. This plugin is dedicated for educational themes.

= Frontend Submission =
	TP Eduacation allows you to like post from frontend and view the no of likes for that particular post.


= Customization and Flexibility =
	TP Education plugin is highly flexible and customizable. This Plugin provides hooks that makes very easy for you to customize the output format. You just need to update design with css.

= Template Overwrite =
	* Create a folder named "tp-education" and do all the overwrites inside the folder as instructed below.
	* Archive Pages
		- you can overwrite all archive pages for post types available in this plugin. ie: tp-archive-class.php
	* Search Page
		- you can overwrite search page for post types available in this plugin by tp-archive-search.php
	* Single Pages
	- you can overwrite all single pages for post types available in this plugin. ie: tp-single-class.php

== Installation ==
	= Using The WordPress Dashboard =
	* Navigate to the 'Add New' in the plugins dashboard
	* Search for TP Education
	* Click Install Now
	* Activate the plugin on the Plugin dashboard
	= Uploading in WordPress Dashboard =
	* Navigate to the 'Add New' in the plugins dashboard
	* Navigate to the 'Upload' area
	* Select tp-education.zip from your computer
	* Click 'Install Now'
	* Activate the plugin in the Plugin dashboard
	= Using FTP =
	* Download tp-education.zip
	* Extract the tp-education directory to your computer
	* Upload the tp-education directory to the /wp-content/plugins/ directory
	* Activate the plugin in the Plugin dashboard
	= Setting Options =
	* Setting Page is located inside Default Settings Option
	* Enable and Disable Post Types Options As Per Need
	= Permalink Setup =
	* Go to Settings -> Permalinks and click on "Save Changes" if your custom post type redirects you to 404 page.

== Screenshots ==
	1. Class Meta Fields
	2. Event Meta Fields
	3. Affiliation Meta Fields
	4. Course Meta Fields
	5. Excursion Meta Fields
	6. Team Meta Fields
	7. Testimonial Meta Fields

== Shortcodes ==
	= Defaults Atts :- =
	* category = '',
	* no_of_posts = 2,
	* post_ids = '', ( should be seperated by ','. ie: 15, 27 )
	* column = 2 ( max num value 4 )

	= Class shortcode: =
	[TP_EDUCATION_CLASS] OR [TP_EDUCATION_CLASS category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]

	= Event shortcode: =
	[TP_EDUCATION_EVENT] OR [TP_EDUCATION_EVENT category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]

	= Course shortcode: =
	[TP_EDUCATION_COURSE] OR [TP_EDUCATION_COURSE category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]

	= Team shortcode: =
	[TP_EDUCATION_TEAM] OR [TP_EDUCATION_TEAM category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]

	= Excursion shortcode: =
	[TP_EDUCATION_EXCURSION] OR [TP_EDUCATION_EXCURSION category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]

	= Affiliation shortcode: =
	[TP_EDUCATION_AFFILIATION] OR [TP_EDUCATION_AFFILIATION category="cat-slug" no_of_posts="2" post_ids="217, 115" column="2"]

	= Testimonial shortcode: =
	[TP_EDUCATION_TESTIMONIAL] OR [TP_EDUCATION_TESTIMONIAL no_of_posts="2" post_ids="217, 115" column="2"]

	= Search Tabs shortcode: =
	[TP_EDUCATION_SEARCH_TAB]

== Functions to Call Meta Values ==

	= Check if the page loaded is TP Education territorial =
	is_tp_education();

	= Event Details =
	tp_event_date( $post_id = '' ); // Event Date
	tp_event_start_time( $post_id = '' ); // Event Start Time
	tp_event_end_time( $post_id = '' ); // Event End Time
	tp_event_location( $post_id = '' ); // Event Location

	= Class Details =
	tp_class_cost( $post_id = '' ); // Class Cost
	tp_class_period( $post_id = '' ); // Class Period
	tp_class_size( $post_id = '' ); // Class Size
	tp_class_age_group( $post_id = '' ); // Class Age Group

	= Excursion Details =
	tp_excursion_start_date( $post_id = '' ); // Excursion Start Date
	tp_excursion_end_date( $post_id = '' ); // Excursion End Date
	tp_excursion_location( $post_id = '' ); // Excursion Location

	= Team Details =
	tp_team_designation( $post_id = '' ); // Team Designation
	tp_team_email( $post_id = '' ); // Team Email
	tp_team_phone( $post_id = '' ); // Team Phone
	tp_team_skype( $post_id = '' ); // Team Skype
	tp_team_website( $post_id = '' ); // Team Website
	tp_team_courses( $post_id = '' ); // Team Courses
	tp_team_social( $post_id = '' ); // Team Social

	= Testimonial Details =
	tp_testimonial_rating( $post_id = '' ); // Testimonail Rating
	tp_testimonial_designation( $post_id = '' ); // Testimonail Designation

	= Affiliation Details =
	tp_affiliation_link( $post_id = '' ); // Affiliation Link

	= Course Details =
	tp_course_type( $post_id = '' ); // Course Type
	tp_course_duration( $post_id = '' ); // Course Duration
	tp_course_price( $post_id = '' ); // Course Price
	tp_course_students( $post_id = '' ); // Course no of Students
	tp_course_language( $post_id = '' ); // Course Language
	tp_course_assessment( $post_id = '' ); // Course assessment
	tp_course_skills( $post_id = '' ); // Course skills
	tp_course_professor( $post_id = '' ); // Course Professor
	tp_course_counselors( $post_id = '' ); // Course Counselors

	= Get Terms with Link =
	tp_education_get_terms( $taxonomy = '', $post_id = '' ); // Get Terms with link

	= Post Like Button =
	tp_education_like_button( $post_id = '' ); // Like Button ( this function only returns output. )

	= Post Meta =
	tp_education_posted_on(); // Post Meta


== Functions to Return Meta Values ==

	= Event Details =
	get_tp_event_date( $post_id = '' ); // Event Date
	get_tp_event_start_time( $post_id = '' ); // Event Start Time
	get_tp_event_end_time( $post_id = '' ); // Event End Time
	get_tp_event_location( $post_id = '' ); // Event Location

	= Class Details =
	get_tp_class_cost( $post_id = '' ); // Class Cost
	get_tp_class_period( $post_id = '' ); // Class Period
	get_tp_class_size( $post_id = '' ); // Class Size
	get_tp_class_age_group( $post_id = '' ); // Class Age Group

	= Excursion Details =
	get_tp_excursion_start_date( $post_id = '' ); // Excursion Start Date
	get_tp_excursion_end_date( $post_id = '' ); // Excursion End Date
	get_tp_excursion_location( $post_id = '' ); // Excursion Location

	= Team Details =
	get_tp_team_designation( $post_id = '' ); // Team Designation
	get_tp_team_email( $post_id = '' ); // Team Email
	get_tp_team_phone( $post_id = '' ); // Team Phone
	get_tp_team_skype( $post_id = '' ); // Team Skype
	get_tp_team_website( $post_id = '' ); // Team Website

	= Testimonial Details =
	get_tp_testimonial_designation( $post_id = '' ); // Testimonail Designation

	= Affiliation Details =
	get_tp_affiliation_link( $post_id = '' ); // Affiliation Link

	= Course Details =
	get_tp_course_type( $post_id = '' ); // Course Type
	get_tp_course_duration( $post_id = '' ); // Course Duration
	get_tp_course_price( $post_id = '' ); // Course Price
	get_tp_course_students( $post_id = '' ); // Course no of Students
	get_tp_course_language( $post_id = '' ); // Course Language
	get_tp_course_assessment( $post_id = '' ); // Course assessment
	get_tp_course_skills( $post_id = '' ); // Course skills

== Hooks ==

 	= Event Details =
	do_action( 'tp_event_date_action', $post_id = '' ); // Event Date
	do_action( 'tp_event_start_time_action', $post_id = '' ); // Event Start Time
	do_action( 'tp_event_end_time_action', $post_id = '' ); // Event End Time
	do_action( 'tp_event_location_action', $post_id = '' ); // Event Location

	= Class Details =
	do_action( 'tp_class_cost_action', $post_id = '' ); // Class Cost
	do_action( 'tp_class_period_action', $post_id = '' ); // Class Period
	do_action( 'tp_class_size_action', $post_id = '' ); // Class Size
	do_action( 'tp_class_age_group_action', $post_id = '' ); // Class Age Group

	= Excursion Details =
	do_action( 'tp_excursion_start_date_action', $post_id = '' ); // Excursion Start Date
	do_action( 'tp_excursion_end_date_action', $post_id = '' ); // Excursion End Date
	do_action( 'tp_excursion_location_action', $post_id = '' ); // Excursion Location

	= Team Details =
	do_action( 'tp_team_designation_action', $post_id = '' ); // Team Designation
	do_action( 'tp_team_email_action', $post_id = '' ); // Team Email
	do_action( 'tp_team_phone_action', $post_id = '' ); // Team Phone
	do_action( 'tp_team_skype_action', $post_id = '' ); // Team Skype
	do_action( 'tp_team_website_action', $post_id = '' ); // Team Website
	do_action( 'tp_team_courses_action', $post_id = '' ); // Team Courses
	do_action( 'tp_team_social_action', $post_id = '' ); // Team Social Links

	= Testimonial Details =
	do_action( 'tp_testimonial_rating_action', $post_id = '' ); // Testimonial Rating
	do_action( 'tp_testimonial_designation_action', $post_id = '' ); // Testimonial Designation
	do_action( 'tp_testimonial_social_action', $post_id = '' ); // Testimonial Social Links

	= Course Details =
	do_action( 'tp_course_type_action', $post_id = '' ); // Course Type
	do_action( 'tp_course_duration_action', $post_id = '' ); // Course Duration
	do_action( 'tp_course_price_action', $post_id = '' ); // Course Price
	do_action( 'tp_course_students_action', $post_id = '' ); // Course no of Students
	do_action( 'tp_course_language_action', $post_id = '' ); // Course Language
	do_action( 'tp_course_assessment_action', $post_id = '' ); // Course Assessment
	do_action( 'tp_course_skills_action', $post_id = '' ); // Course Skills
	do_action( 'tp_course_professor_action', $post_id = '' ); // Course Professor
	do_action( 'tp_course_counselors_action', $post_id = '' ); // Course Counselors

	= Affiliation Details =
	do_action( 'tp_affiliation_link_action', $post_id = '' ); // Affiliation Link

	= Meta Details =
	do_action( 'tp_education_posted_on_action' ); // Post Meta

	= Sidebar Condition Filter Hook =
	apply_filters( 'tp_education_is_sidebar_enable_filter', true );
	add_filter( 'tp_education_is_sidebar_enable_filter', 'custom_function' ); // Use your custom sidebar enable condition function

	= Archive Pagination Action Hook =
	do_action( 'tp_education_pagination_action' );
	add_action( 'tp_education_pagination_action', 'custom_function' ); //add your custom pagination function

	= Single Pagination Action Hook =
	do_action( 'tp_education_post_pagination_action' );
	add_action( 'tp_education_post_pagination_action', 'custom_function' ); //add your custom post pagination function


== Frequently Asked Questions ==
	= When viewing additional post type's single, why does it redirect to 404( page not found ) page? =
    To resolve the issue, go to Dashboard -> Settings -> Permalinks and press "Save Changes".


== Files ==

	Font Awesome 4.2.0 by @davegandy
	License: http://fontawesome.io/license (Font: SIL OFL 1.1, CSS: MIT License)
	Source: http://fontawesome.io

	jQuery UI - v1.12.0
	License: https://jquery.org/license/ ( Copyright jQuery Foundation and other contributors; Licensed MIT )
	Source: http://jqueryui.com

	jquery-timepicker v1.11.5 by Jon Thornton
	License: https://github.com/jonthornton/jquery-timepicker  MIT License © 2014
	source: http://jonthornton.github.com/jquery-timepicker/


== Changelog ==

= 4.5 April 27, 2023 =
* Fixed attribute escaping issue for all shortcode

= 4.4 March 14, 2023 =
* Fixed postmeta issue

= 4.3 August 15, 2022 =
* Tested up to WordPress v6.0

= 4.2 September 6, 2021 =
* Added starting date course post meta

= 4.1 March 3, 2021 =
* Tested up to WordPress v5.7

= 4.0 August 06, 2019 =
* Added function to check if the page loaded is TP Education territorial
* Added filter hook for sidebar in all page
* Added action hook for arcive pagination
* Added action hook for single pagination
* Refinement on minor issues
* Refinement on inner pages layout

= 3.9 June 25, 2019 =
* Minor translation update

= 3.8 May 10, 2019 =
* Minor update

= 3.7 January 22, 2019 =
* Minor update

= 3.6 January 22, 2019 =
* Added meta value return fuction

= 3.5 January 15, 2019 =
* Tested upto WordPress version 5.0.3
* Updated rewrite issue

= 3.4 July 20, 2018 =
* Updated classes metabox

= 3.3 July 18, 2018 =
* Updated classes metabox

= 3.2 March 15, 2018 =
* Tested upto 4.9.4

= 3.1 October 24, 2017 =
* Added Tags to all custom post types except testimonial.

= 3.0 October 13, 2017 =
* Rewrite rules and Permalink setup has been fixed
* Updated Metaboxs
* Resolved datepicker jquery issue
* FAQ added in plugin documentation

= 2.9 October 12, 2017 =
* Rewrite rules and Permalink setup has been updated

= 2.8 October 12, 2017 =
* Bug Fix: Widget register condition and Plugin widgets updated

= 2.7 October 10, 2017 =
* Bug Fix: Widget register condition
* Flush rewrite rules added only in deactivation

= 2.6 October 5, 2017 =
* Bug Fix: Listing of Professors in Course post type.

= 2.5 =
* Widgets updated

= 2.4 =
* Class attribute added to meta value labels

= 2.3 =
* Minor changes in datepicker jquery dependency

= 2.2 =
* Add datepicker dependency for custom.js
* Update info in readme.txt

= 2.1 =
* Updated Custom Css
* Updated Custom JS

= 2.0 =
* Updated Featured Course Widget
* Updated Content Single

= 1.9 =
* Updated Affiliation Widget
* Updated Team Widget
* Added Setting Links to plugin page
* Updated Screenshots

= 1.8 =
* Added Hooks
* Testimonial Rating Updated

= 1.7 =
* Testimonial Meta Fields Updated
* Template Overwrite Format Updated
* Shortcodes Updated

= 1.6 =
* Design Updated
* Added Team Widget
* Added Course Widget
* Added Affiliation Widget

= 1.5 =
* Setting Page For TP Education
* Added Options to Enable and Disable Post Types
* Added Affiliation Post Type and It's Meta
* Updated Team Post Type Meta Fields
* Updated Course Post Type Meta Fields
* Updated Search Page
* Updated Archive Page
* Updated Template Overwrite Option
* Datepicker UI Updated in Frontend

= 1.4 =
* File Prefixing
* Search Filter Issue Updated
* Datepicker UI Images Uploaded

= 1.3 =
* Custom Js Updated

= 1.2 =
* Search Tab shortcode Added
* Custom Jquery Added
* Custom Style Updated

= 1.1 =
* Archive Page Design Updated

= 1.0 =
* Initial release.


== Upgrade Notice ==

= 2.6 =
* Bug Fix: Listing of Professors in Course post type.

= 2.5 =
* Plugin Updated.
