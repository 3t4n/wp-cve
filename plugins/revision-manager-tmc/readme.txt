=== Revision Manager TMC ===
Contributors: themastercut
Donate Link: http://jetplugs.com
Tags: revision, revisionary, revision control, revision manager, clone post, revisions, authors reviser, draft manager, permissions, post permit, accept, accept post, accept changes, revisionize, submit changes, acf, Advanced Custom Fields, show differences in changes
Requires at least: 5.3.0
Tested up to: 6.2.2
Requires PHP: 5.6
Stable tag: 2.8.18
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Clone your post, page or custom post type to a draft. Draft up revisions of live, published content. Accept posts. It works with ACF...

== Screenshots ==

1. Revision Manager Clone post to draft view (FREE)
2. Revision Manager shows the appearance of a new ACF field (NEW)
3. Revision Manager shows differences (old ACF field value) (NEW)
4. Revision Manager shows differences (compare box) (NEW)
5. Revision e-mail (FREE)
6. Configuration - Choosing supported post (FREE)
7. Revision Manager (PRO) e-mail templates
8. Creating revision draft (FREE)
9. Working on revision draft (submit for revision) (FREE)
10. Admin view of pending revision draft (FREE)
11. Admin revision view - editing (FREE)
12. Admin revision view - compare revisions (FREE)
13. Display of differences in title and content (FREE)


== Description ==

**Revision Manager TMC clones your already published posts and submits them to review.**

Do you have multi-user site? Allow your editors to create clones of posts and replace them with one click.
When cloned post has been marked as *pending*, administrator receives e-mail notification.

Revision Manager TMC is an easy and simple way to revisionize your posts.
Until acceptance of the revision, published content remains *unchanged* one the web.
Our plugin is very lightweight. It will not bloat your WordPress. We promise!

Revision Manager TMC helps, approve posts, accepts posts, creates revision, clones your post, page (PRO version) or Custom Post Type (PRO version) to a draft.

**Create and remove revisions from code**
From version 2.7.5 you can use publicly accessible methods:

rm_tmc_createRevision( [ORIGINAL POST ID YOU WANT TO MAKE COPY OF] )

rm_tmc_acceptRevision( [REVISION POST ID TO ACCEPT] )

>Read more about our plugin in BEGINNER'S GUIDE FOR WORDPRESS  — [wpbeginner.com - Revision Manager TMC](https://www.wpbeginner.com/plugins/how-to-allow-authors-to-revise-published-posts-in-wordpress/)

**Schedule posts**

From version 2.4.6 we support scheduling revisions.

**Gutenberg editor (Core WP Blocks)**

From version 2.5.3 (10.04.2019) we support Gutenberg / WP Blocks. Feel free to discover this feature. Your feedback is welcome.

**Advanced Custom Fields**

From version 2.1.0 we strongly support ACF.
In 2.1.0 we launched new feature, allows to show differences in fields changes. So if there is a difference between original post field and clone, there will be a mark on the side of field. This feature is still on beta ( undergoing work and testing )

In 2.2.1 of Revision Manager TMC finally we launched feature Showing differences in changes ACF filed values changes.
We are showing old value, before u decide publish revision. Also we mark place were changes were made. What more, you can decide what color you want to show on the changed field.

In 2.4.0 of Revision Manager TMC We add compare box, so now you have full view of changes.

**Beaver Builder**
From version 2.5.4 we support Beaver Builder seamlessly.
Just publish changes from front-end editor and posts will merge.

**Elementor**
From version 2.5.8 we support Elementor seamlessly.
Just publish changes from front-end editor and posts will merge.

https://www.youtube.com/watch?v=rzxtuEsppFg

**CUSTOMIZATION**

**Revision Manager Features:**

- ✭✭✭NEW!✭✭✭ Possibility to limit notifications to only authors of posts (PRO).
- ✭✭✭NEW!✭✭✭ Support Beaver Builder.
- ✭✭✭NEW!✭✭✭ Support Elementor.
- ✭✭✭NEW!✭✭✭ Support Gutenberg editor.
- Schedule Revision - now you can easily schedule the publication of an approved revision post
- Support section - we add system info dump for rapid environment testing. When you need help, you can simple send us system info.
- Show differences in changes (don't forget to update your ACF to newest free ACF verstion 5.X.X )
- Support [Bedrock Project](https://roots.io/bedrock/)
- Support [ACF PLUGIN - Advanced Custom Fields ( PRO )](https://wordpress.org/plugins/advanced-custom-fields/)
- Support 3rd party metadata based plugins.
- Delete all revisions from database
- Dashboard widget to see all prepared clones
- Quick and easy setup
- Email notifications
- Choose who can receive notifications by e-mail
- Option to receive
- Select who can review and approve revisions

**NEED SOME MORE FEATURES?**

**Look at the video tutorial on Revision Manager TMC PRO**

https://www.youtube.com/watch?v=yMxNCtQTe6A

**Revision Manager TMC Pro**

- All revisions in one e-mail (one per day). Choose frequency of receiving e-mails notification : every single or one per day.
- Five professional [e-mail notification designs](http://jetplugs.com/) - spice up your e-mail notifications
- Choose addresses you want to exclude from notifications
- Choose every post type in PRO version
- Edit content of notification e-mail or add your own HTML
- Use codes (such as '%author_name% - Author of this revision') to personalize your e-mail notifications.

**Revision Manager TMC Pro e-mail templates**

Five professional [landing page designs](http://jetplugs.com/) - pleasing to the eye Revision Manager e-mail templates:

- _aqua Revision Mananger e-mail template_
- _moonlight Revision Mananger e-mail template_
- _blue Revision Mananger e-mail template_
- _light Revision Mananger e-mail template_
- _mono Revision Mananger e-mail template_

[Preview](http://jetplugs.com)

**SUPPORT**

Your feedback is WELCOME!

== Installation ==

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'revision-manager-tmc'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `revision-manager-tmc.zip` from your computer
4. Click on 'Install Now' button
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `revision-manager-tmc.zip`
2. Extract the `revision-manager-tmc` directory to your computer
3. Upload the `revision-manager-tmc` folder to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Frequently Asked Questions ==

**1. How can I delete all previous revisions on publish?**
Just go to the wordpress Dashboard. Find "Revision Manager Section". You will see two section "Revisions to accept" and "Tools".In the tools section you will find a button that will allow you to delete all revisions

**2. How can I find all pending reviews for acceptance?**
Just go to the wordpress Dashboard. Find "Revision Manager Section". In the Revision Manager section you will find all pending revisions.

**3. How can I enable the additional features?**
The additional features are available in the PRO version. Get it from here: http://jetplugs.com/

**4. How can I change frequency of receiving e-mails notification with revisions?**
Since version 2.3.0 of Revision Manger TMC PRO, we launched new feature: Possibility to choose frequency of receiving e-mails notification : every single or all revisions in one e-mail (one per day).

**5. How can I get help or report a bug?**
Since version 2.4.3 we provide better support for Revision Manager TMC. We added system info dump for rapid environment testing.We added system info dump for rapid environment testing. You might find this feature in "Tools" bookmark in "System info" section. You easly download file withi system info, and send to us.

NEW! Support section - we add system info dump for rapid environment testing. When you need help, you can simple send us system info.

== Changelog ==

= 2.8.18 =
Refactor: Wordpress 6.2.2 compatibility check.

= 2.8.17 =
Fix: Missing ACF styles and messed up differences option.

= 2.8.15 =
Refactor: WordPress 6.3.1 - compatibility check.

= 2.8.14 =
Refactor: WordPress 5.9.1 - compatibility check.

= 2.8.13 =
Refactor: API Keys functionality.

= 2.8.0 =
BIG RELEASE.
- Due to bugs in AdminPageFramework we changed the whole engine for admin backend.

Remove: APF dependency.
Remove: Tools tab.
Fix: Front-end Universal ShellPress scripts and styles - loading when not necessary.

= 2.7.92 =
Refactor: Completely removed AdminPageFramework dependency.

= 2.7.91 =
Fix: Manual revision creation now supports copy of meta and taxonomies.

= 2.7.9 =
Fix: Duplicated display of post differences on classic post screen.

= 2.7.8 =
Add: Revision title and content display in BlockEditor metabox area.
Update: PHP version 5.6
Update: WordPress version.

= 2.7.7 =
Refactor: Update WordPress version support.

= 2.7.6 =
Fix: Latest WordPress diff styles.

= 2.7.5 =
Update: Compatibility.
Refactor: Upper menu accesibility.
Update: ShellPress version.
Add: Public methods for copy creation and acceptance.

= 2.7.3 =
Update: Compatibility.

= 2.7.2 =
Fix: WP 5.5.1 radios.

= 2.7.1 =
Update: ShellPress library.
Add: Support for WordPress 5.5.1.
Refactor: Better site performance, when using plugin on non-options pages.
Refactor: Moved post types settings to the top.

= 2.7.0 =
Add: Support for multiple meta values.

= 2.6.9 =
Add: Hooks: tmc/revisionmanager/revisions/merge/after, tmc/revisionmanager/revisions/merge/before

= 2.6.8 =
Remove: All logging functionality.

= 2.6.7 =
Refactor: Better support for Windows-based servers.

= 2.6.6 =
Add: Support for serialized fields, when stored as escaped strings.

= 2.6.5 =
Add: Support for slashed content when duplicating posts.

= 2.6.3 =
Update: ShellPress.
Refactor: Add nonce authentication for new clone creation link.
Refactor: Moved license field to new tab.

= 2.6.2 =
Fix: Elementor script is not loading with dependency now.

= 2.6.1 =
Add: Better support for Gutenberg( aka block editor ).
Add: Support for all other post statuses from Gutenberg and trigger merging only on publish.
Refactor: Do not hide publish panel on Gutenberg.
Refactor: Merging detection is now based on subscription, not on button press.

= 2.6.0 =
Refactor: Update libraries.

= 2.5.9 =
Refactor: Licenser code.

= 2.5.98 =
Refactor: Raw metadata duplication.

= 2.5.97 =
Refactor: Force delete revision after merging, due to issues with post_type changes.

= 2.5.96 =
Refactor: After posts merging, revision is marked as draft.

= 2.5.95 =
Refactor: Better ACF sub-fields parser.

= 2.5.94 =
Refactor: Bulletproof ajax post modifications marking as private status.

= 2.5.93 =
Fix: ACF input names mismatch (broken values indicators).

= 2.5.92 =
Refactor: Gutenberg loading class for actions.

= 2.5.91 =
Fix: Changing private status to public.
Add: Hook for creating linked revision URL.

= 2.5.8 =
Add: Elementor support.

= 2.5.7 =
Add: Possibility to replace original post's date with the one from revision.

= 2.5.6 =
Refactor: WordPress compatibility checks.

= 2.5.5 =
Add: Possibility to limit notifications to only authors of posts in PRO version.
Add: Possibility to hide display of differences in built in "post title" and "post content".

= 2.5.4 =
Add: Support for merging changes in Beaver Builder.

= 2.5.3 =
Add: Better accepting flow with Gutenberg editor.

= 2.5.2 =
Refactor: Moved Gutenberg style from enqueue area to admin_head.

= 2.5.1 =
Add: Display of differences in built in WordPress the_title and the_content fields.

= 2.5.0 =
Fix: Bug with collective notifications - was sending only first instance.
Fix: Admin post permalink could not be created on cron action.
Refactor: WordPress  5.1.1 compatibility.

= 2.4.9 =
Refactor: Check compatibility with the newest WordPress version.

= 2.4.8 =
Update: ShellPress library.
Refactor: Composer autoloading dependencies (much faster).
Add: Prevent copy creation if user is not logged in.
Fix: Nested ACF groups always showing as changed.

= 2.4.7 =
Add: Button for manual revision acceptance from posts list table.
Remove: Publish button from Gutenberg editor (only on supported view).

= 2.4.6 =
Add: Support for scheduled revisions.

= 2.4.5 =
Refactor: Changed default notifications.

= 2.4.4 =
Fix: Broken url paths on Windows Servers.

= 2.4.3 =
Add: System info dump for rapid environment testing.
Fix: Sending emails to all users, when role is empty.
Fix: Getting revision author on notification.

= 2.4.2 =
Fix: Cases when ACF is loaded late ( for example inside other plugin or theme ).

= 2.4.0 =
Refactor: Better way of displaying differences between ACF fields.

= 2.3.1 =
FIX: Headers already sent on classic editor: publish action (non REST_REQUEST).

= 2.3.0 =
Refactor: The way of displaying ACF changes inside of fields.
Fix: Showing marks in ACF form on wrong pages.
Fix: Problem with sending multiple notifications when using plugins, which hook up to save_post action.

= 2.2.1 =
Add: Support for showing differences inside ACF repeater field.

= 2.2.0 =
Refactor: Better differences between ACF fields.
Add: Support for nested fields.
Add: Support for "Flexible content" field.

= 2.1.5 =
Add: Factory reset button.
Add: Show differences between cloned ACF fields.

= 2.1.4 =
Refactor: Changed post table action links to red color.

= 2.1.3 =
Fix: Dashboard widget doesn't show correct posts.

= 2.1.2 =
Update: ShellPress library.

= 2.1.1 =
FIX: New e-mail target bug.

= 2.1.0 =
REFACTOR: More flexible way of checking plugins directory.

= 2.0.9 =
ADD: support for Bedrock Project

= 2.0.8 =
ADD: BETA support for Gutenberg editor.
REFACTOR: Mark new revisions as "Revision ✓".
FIX: Removing meta data.

= 2.0.7 =
Fix: Options bug.

= 2.0.6 =
Refactor: New way of handling metadata copying.

= 2.0.5 =
Refactor: Dashboard widget is now visible only for users with 'manage_options' capability.
Fix: Permalink duplication error.
Update: ShellPress library.

= 2.0.4 =
REFACTOR: Plugin description and tags.
TESTING: up to 4.9.5.

= 2.0.3 =
FIX: Memory leak on dashboard widget when there are many revisions.
ADD: Support for non-public post types ( ACF, etc. )

= 2.0.2 =
FIX: Bug on old PHP litespeed servers. Returned values could not be processed as write input on method empty().

= 2.0.1 =
FIX: Default options compatibility with old PHP.

= 2.0.0 =
REFACTOR: Whole engine has been rewritten.
ADD: Import/ export of settings.
ADD: New options page.
ADD: More notifications over duplicated post.
ADD: Dashboard widget.
ADD: Possibility to clear all revisions from database.
REFACTOR: Better support over custom post types.
REFACTOR: All supported post types are now visible in options page.

= 1.1.3.2 =
Testing 4.9.4 WordPress.

= 1.1.3.1 =
Testing 4.9.2 WordPress.

= 1.1.3 =
FIX: Options PHP warning.
ADD: Notifications while editing cloned posts.
ADD: Possibility to merge cloned post in DRAFT status.
FIX: Stability issues.

= 1.1.2 =
FIXED: See 1.1.1

= 1.1.1 =
ADD: Post type chooser
FIX: Installation problem

= 1.1.0 =
ADD: Insert e-mail address for quick test

= 1.0.0 =
First release
