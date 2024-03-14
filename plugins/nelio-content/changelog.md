= 2.0.19 (April 14, 2021) =
* **Improvement**. Add placeholder selector in template editor dialog.
* **Improvement**. Add new option to attach external images in social messages using their URLs.

= 2.0.18 (March 30, 2021) =
* **Bug Fix**. Update a few text strings in the UI.

= 2.0.17 (March 29, 2021) =
* **Bug Fix**. Remove support for Pinterest until they grant Nelio Content access to the new API.
* **Improvement**. Tweak data store to prevent reducers from triggering a load request of already-loaded posts.
* **Improvement**. Tweak social timeline to display messages scheduled for one week or month after post’s publication under the “Week” and “Month” sections respectively.

= 2.0.16 (March 11, 2021) =
* **Bug Fix**. Tweak post auto-resharing condition to properly use the global setting.

= 2.0.15 (March 8, 2021) =
* **Improvement**. Tweak code to disable auto-appending of `{permalink}` when editing existing social messages.
* **Improvement**. Disable _Save_ button in social message editor when message is emptyish (e.g. spaces only).

= 2.0.14 (March 5, 2021) =
* **Bug Fix**. Load external featured images during AJAX requests too.
* **Bug Fix**. Prevent automations from generating social messages with HTML fragments in them.
* **Bug Fix**. Extract cited links to add Twitter mentions in automatic social messages.
* **Improvement**. Add new filter (`nelio_content_get_max_age_for_resharing`) to exclude posts older than `x` months from being reshared.

= 2.0.13 (January 26, 2021) =
* **New Feature**. Compatibility with Reddit.
* **Bug Fix**. Hide external featured image if external featured images are disabled in plugin settings.
* **Bug Fix**. Add workaround to make sure posts created in the calendar show the correct publication date in Gutenberg (instead of “Immediately”).

= 2.0.12 (December 28, 2020) =
* **Bug Fix**. Use the “auto share” setting when generating timeline.

= 2.0.11 (December 14, 2020) =
* **Compatible with WordPress 5.6.**
* **Bug Fix**. Add extra check to remove PHP warning.
* **Bug Fix**. Show error message when Pinterest profile has no boards (or they couldn’t be retrieved).
* **Bug Fix**. Tweak Nelio Content’s date functions to make sure `wp.date.format` doesn’t receive momentjs instances.

= 2.0.10 (November 19, 2020) =
* **Bug Fix**. Restore `nelio_content_staging_urls` filter to deactivate Nelio Content on sites with the specified URls.

= 2.0.9 (November 9, 2020) =
* **Bug Fix**. Fix CSV exporting of the Editorial Calendar.
* **Improvement**. Add new function `updatePostQualitySettings` to let users customize post quality.

= 2.0.8 (November 2, 2020) =
* **Improvement**. Remove unnecessary call to Nelio’s API to retrieve target names of non-target profiles.
* **Improvement**. Add quick edit and bulk edit settings to toggle post auto sharing on social media.

= 2.0.7 (October 9, 2020) =
* **WordPress Minimum Version** is now 5.4.
* **Bug Fix**. Refactor `edit-post` and `data` stores to store comments in `data`.
* **Bug Fix**. Modify edit and unschedule post methods to remove previous `post_date` when unscheduling.
* **Bug Fix**. Add missing hook to update engagement metric when there’s a new comment.
* **Bug Fix**. Fix post edit screen to properly load post analytics when required.
* **Bug Fix**. Fix post filter in calendar.
* **Bug Fix**. Change `categories` value in AWS-encoded posts to fix social template usage in automations.
* **Bug Fix**. Fix post search to prevent post duplication when searching by ID.
* **Improvement**. Add check to detect profiles that need re-authentication and warn users if needed.
* **Improvement**. Add related post in dialog for viewing published social messages.
* **Improvement**. Update UI string to better explain how to activate Google analytics.
* **Improvement**. Modify social message editor to show warning if there aren’t any profiles available.
* **Improvement**. Add new UI elements to let the users know that free preview messages require a subscription.

= 2.0.6 (September 21, 2020) =
* **Improvement**. Hide “Nelio Content Tools” sidebar when editing a new post that hasn’t been saved yet.
* **Bug Fix**. Modify Nelio Content’s REST API to update post related items (i.e. messages and tasks) when updating a post.

= 2.0.5 (September 18, 2020) =
* **Improvement**. You can now create and edit a post in the calendar without specifying a concrete date time.
* **Bug Fix**. Unscheduled posts should be draggable and droppable onto a calendar day. This has now been fixed.
* **Bug Fix**. The previous version introduced a PHP warning in a helper function. This one fixes the issue.
* **Bug Fix**. Now, quality analysis counts the number of internal links included in your copy properly.

= 2.0.4 (September 15, 2020) =
* **Improvement**. [A bug in `make-pot`](https://github.com/wp-cli/i18n-command/issues/225) prevents a few strings from being properly extracted and translated. We’ve implemented a workaround so that, while we wait for the fix, those strings can be translated.
* **Improvement**. When creating a new social message in the calendar, the editor shows “Today/Now” by default if the user wants to schedule a message for “Today.”
* **Improvement**. Social message editor should show the related post when outside the edit post screen. This version makes sure it does.
* **Improvement**. When an uncompleted task is due, it shows up in red to grab the user’s attention.
* **Bug Fix**. When extracting relevant sentences from the content to generate social messages, the plugin used to remove inline `code` tags. This might have resulted in sentences being incomplete, as they were missing the content included in those tags. This has now been fixed.
* **Bug Fix**. Our plugin’s pages are now registered during WordPress’ `init` action. This way, if a site uses its own custom post types and registers it during `init`, our plugin will be able to realize those post types actually exists.
* **Bug Fix**. Date selector in social message editor didn’t accept some valid dates. Also, its behavior was erratic, as it sometimes cleared itself while the user was typing in a date. This has all been fixed.
* **Bug Fix**. Fixes JavaScript error in older version browsers that didn’t have the string method `replaceAll` by using `replace` instead.

= 2.0.3 (September 3, 2020) =
* **Improvement**. Recovers the feature by which one could reuse previous social messages associated to a certain post.
* **Improvement**. Adds “Calendar” link in the admin bar menu.
* **Bug Fix**. _Classic Editor_ blocks (or even the classic editor itself) showed an error message stating that “a plugin URL failed to load.” This has now been fixed.
* **Bug Fix**. When editing a social message in the editorial calendar, the message preview didn’t show the related post’s featured image. This has now been fixed and the featured image (if available) is visible.

= 2.0.2 (September 2, 2020) =
* **Bug Fix**. Fixes fatal error that showed in PHP 7.0.

= 2.0.1 (September 2, 2020) =
* **Bug Fix**. External featured images should work in the front-end, but they didn’t. This is now fixed.

= 2.0.0 (September 1, 2020) =
* **New Implementation**. Reimplements Nelio Content using React and Redux.

= 1.6.27 (August 7, 2020) =
* Fixed some warnings.
* Updated compatibility with latest WordPress version.

= 1.6.26 (April 27, 2020) =
* Simplified process for upgrading to Nelio Content Premium.
* Added contextual help in some screens to help users navigate the plugin.
* **Bug Fix**. When creating a social message in the Feeds screen, if the user tried to add an image, the social message editor dialog crashed. This has now been fixed.

= 1.6.25 (February 24, 2020) =
* **New Feature**. Added Google My Business support.

= 1.6.24 (December 11, 2019) =
* **Improvement**. Added new tag in social messages: `{excerpt}`. This tag uses the related post’s excerpt (if any).
* Fixed translation domain in a few strings so that they can be properly translated.
* **Bug fix**. Social messages can be properly rescheduled in the calendar.

= 1.6.23 (November 13, 2019) =
* **Bug Fix**. Social message preview includes featured image when using Classic Editor.

= 1.6.22 (October 23, 2019) =
* **Bug fix**. Removed PHP warning. See [this support ticket](https://wordpress.org/support/topic/coding-error-11/).
* Plugin tested with WordPress 5.3.
* Minimum WordPress version required bumped to 4.7.
* Other minor improvements.

= 1.6.21 (October 18, 2019) =
* **Bug fix**. Removed twitter handler autocompletion, as it’s unreliable on certain installations.
* **Bug fix**. On certain installations, the permalink of a post was incorrect (it included the `undefined` keyword at the end). This has now been fixed.

= 1.6.20 (October 17, 2019) =
* **Bug fix**. Post analysis now detects if there’s a featured image in the classic editor after the post has been saved.
* **Bug fix**. In JavaScript, somes dates were strings when they should have been instances of `momentjs`, which resulted in JavaScript errors. This has now been fixed.

= 1.6.19 (October 1, 2019) =
* **Bug fix**. Removed `<span contenteditable="false">` tag when editing automatic social messages with Twitter mentions.
* **Bug fix**. Post analysis correctly reports whether the current post has an image or not.
* **Bug fix**. Social message in post edit screen shows the proper featured image in the preview after featured image has been changed.
* **Improvement**. In previous versions, generating social messages after changing the reshare setting in a specific post ignored the new value of the reshare setting. If you wanted to generate social messages taking into account that setting, you first had to save the post. This has now been fixed.
* **Improvement**. Social message previews in Social Media box update in real time when changing a post’s slug in Gutenberg.
* **Improvement**. Remove custom metas from post types not managed by Nelio Content.

= 1.6.18 (August 19, 2019) =
* **Improvement**. Added social message ID in HTML to ease debug.
* **Improvement**. Search Twitter profiles while writing a post now works more smoothly (in previous versions, the cursor moved to the end of the social message for no reason).
* **Bug fix**. Fixed compatibility issue with Yoast SEO version 11.8.

= 1.6.17 (July 1, 2019) =
* **Bug fix**. Saving external featured images properly.
* **Bug fix**. Sometimes, function `is_plugin_active` was undefined. In those instances where this failed, we now check the function exists before invoking it.

= 1.6.16 (June 26, 2019) =
* **Improvement**. Added a new option to completely disable featured images.
* **Improvement**. Removed the option to use a single meta box for external featured images and regular featured imaegs, as this is no longer possible in Gutenberg and makes code more complicated.

= 1.6.15 (June 5, 2019) =
* **Bug Fix**. Synching external featured images in our custom meta box and Gutenberg’s Featured Image section.

= 1.6.14 (May 30, 2019) =
* **Bug Fix**. PHP warning message removed from logs.
* **Bug Fix**. You can now create social messages in the Social Media section when using the Free version of Nelio Content, even if the timeline is full of preview messages.

= 1.6.13 (April 30, 2019) =
* **Improvement**. External Featured Images are back in Gutenberg.

= 1.6.12 (February 5, 2019) =
* **Bug Fix**. Scheduling an unscheduled post in the calendar using Nelio Content’s built-in dialog didn’t render the post in the correct day. This has now been fixed.
* **Bug Fix**. Sometimes, our plugin tried to fetch a featured image when the post had no featured image, which showed an error message in the JavaScript console. This has now been fixed.
* **Improvement**. Social message previews in the timeline are now automatically updated when the title of the post changes.

= 1.6.11 (January 31, 2019) =
* **Bug Fix**. In a WordPress multisite setup where all subsites use the same domain name, some data that our plugin cached in the browser’s local storage wasn’t working properly, as this local storage was shared across all subdomains. This has now been fixed.

= 1.6.10 (January 29, 2019) =
* **Update**. Google+ profiles can no longer be connected to Nelio Content, as Google plans to shut G+ down soon.

= 1.6.8 (January 16, 2019) =
* **Improvement**. When creating social messages automatically, the plugin might now show an error if the user didn’t enable social automations on any of their profiles.
* **Improvement**. Sometimes, the plugin tried to send notification emails with no recipients in them. This has now been fixed.
* **Improvement**. Added a new notification email. Now, whenever a new user is added to the list of followers of a given post, they’ll be notified via email.
* **Improvement**. In Gutenberg, you can now access the social message meta box after a new post has been saved for the first time.
* **Improvement**. Synching posts with the cloud if, and only if, the post has changed.
* **Improvement**. Loading social profiles and templates faster.

= 1.6.7 (December 19, 2018) =
* **Improvement**. Click on a sent social message to preview the full message.
* **Bug Fix**. The length of a Tweet wasn’t properly computed, and tweets that were too long weren’t reported as such. As a result, some scheduled tweets couldn’t be actually shared because of their length. This has now been fixed and the proper length is reported in Nelio Content.

= 1.6.6 (December 13, 2018) =
* **Bug Fix**. In Gutenberg, when creating a new social message, the preview will show the current title of the post.
* **Bug Fix**. In Gutenberg, when creating a new social message, the preview will show the current featured image of the post.
* **Bug Fix**. Removed the hint for using a shortcut to highlight text in the editor, as the shortcut is not (and will not be) implemented.
* **Bug Fix**. Fixed Fatal Error on WordPress versions pre 5.0 using the Gutenberg plugin.

= 1.6.5 (December 12, 2018) =
* **Bug Fix**. Extracting highlighted sentences from the Block Editor didn’t work as expected in some circumstances (the resulting social messages didn’t contain the full selection). This has now been fixed.

= 1.6.4 (December 6, 2018) =
* **Improvement**. Following the release of WordPress 5.0, we fixed the styles of some components that looked ugly in Gutenberg.

= 1.6.3 (December 5, 2018) =
* **Gutenberg**. Improved Gutenberg support in our plugin: post analysis, reference analysis, selection to social message, and share highlights are now working in Gutenberg too.

= 1.6.2 (November 27, 2018) =
* **Improvement**. Added Gutenberg support in our plugin. You should now be able to use the Social Media meta box in Gutenberg. Some of our features aren’t compatible with Gutenberg yet (for instance, highlighting the sentences you want to share), but as soon as Gutenberg is released and stable, we’ll address them.
* **Bug Fix**. Removed autocomplete Facebook usernames while editing a social message, because Facebook does no longer offer the API callback we used to perform said autocompletion.
* **Bug Fix**. After refreshing a social profile, the auto-publication setting was automatically toggled (from `on` to `off` or viceversa). This has now been fixed.
* Changed minimum WordPress version to 4.5, as we need the underscore and backbone libraries to be more up-to-date or else the plugin won’t work.

= 1.6.1 (November 5, 2018) =
* **New Feature**. Quickly preview the image attached to a social message in the timeline view while editing a post.
* **New Feature**. Added a filter to load the editorial calendar only: `nelio_content_use_editorial_calendar_only`.
* **Improvement**. Using multibyte functions when dealing with UTF8 strings.

= 1.6.0 (September 27, 2018) =
* **New Feature**. Added Tumblr support (Beta).

= 1.5.16 (September 3, 2018) =
* **Bug Fix**. On some installations, dates weren’t in UTC (probably because they used PHP’s function `date_default_timezone_set` or a PHP’s init setting) and this meant that posts shown in the calendar used invalid datetimes. This has now been fixed.

= 1.5.15 (August 8, 2018) =
* **Improvement**. You can now pause/resume the publication of social messages on your social media.
* **Improvement**. You can now decide which user roles should have access to the post quality analyzer.
* **Change**. Removed Personal Facebook profiles due to [policy changes in Facebook](https://developers.facebook.com/docs/graph-api/changelog/breaking-changes#login-4-24).
* **Bug Fix**. Some themes/plugins (e.g. Thrive Leads) inserted a `style` tag in the post’s content that broke automatic message generation. Our plugin now takes into account that such a tag might exist in the post content and ignores it when generating social messages.
* **Bug Fix**. Fixed an endless loop error that prevented ical calendar from being exported.
* **UI Changes**. Redesigned a few assets.

= 1.5.14 (July 9, 2018) =
* **Bug Fix**. In the calendar view, the selector to filter social messages by social profile or social network wasn’t working. This has now been fixed.

= 1.5.13 (July 6, 2018) =
* **Improvement**. There’s a new advanced setting for manually specifying the number of automatic social messages Nelio Content has to generate during Publication and Resharing.
* **Improvement**. You can now rename your feeds.
* **Bug Fix**. Extracting the URL of a social message properly when the URL is surrounded by non-breaking spaces or other spacing characters that aren’t a regular space.
* **Bug Fix**. PHP Fatal Error in our PageFrog compatibility file, as reported [here](https://wordpress.org/support/topic/php-fatal-error-at-wp-login-php/). This has now been fixed.
* **Bug Fix**. Some feeds couldn’t be removed because SimplePie had not been able to extract their permalinks. We’re now using feeds’ ID to remove them (which are always properly set).
* **Bug Fix**. Some feeds don’t have a name and, therefore, some UI elements in Nelio Content look weird, as they assume all feeds have a name. We’re now using a feed’s ID as its default name if none was found.
* **Change**. Tweaked Caption and Description when inserting images from Giphy, Unsplash, and so on.
* **Change**. PHP version 5.4 or up is now required.

= 1.5.12 (June 19, 2018) =
* **Bug Fix**. On Firefox (and maybe other browsers), the list of social profiles can’t be automatically updated after adding a new social profile. Now we show a message telling the user that they should manually refresh the profile list page after successfully adding a new profile.
* **Bug Fix**. On Firefox (and maybe other browsers), an unknown error showed up while adding new feeds. This has now been fixed.
* **Improvement**. Several typos in the UI have been fixed.

= 1.5.11 (May 24, 2018) =
* **Update**. Modified our welcome screen for new users so that it complies with EU’s GDPR.
* **Update**. Updated a few strings.
* **Improvement**. Minor changes in UX on feeds.
* **Bug Fix**. Some feeds caused our feed retrieval process to freeze. This has now been fixed.

= 1.5.9 (May 14, 2018) =
* **New Feature**. Feeds page to load RSS feeds from other sites and create social messages and posts from them.

= 1.5.8 (May 2, 2018) =
* **Update**. Modified access to Unsplash’ API, so that the plugin fulfills their T&C.
* **Update**. Engagement values for LinkedIn and Google Plus deleted from Analytics. These networks do not provide valid share count data anymore.

= 1.5.7 (April 13, 2018) =
* **Bug Fix**. When generating the JSON version of a post, there was a call to `apply_filters( 'the_title', … )` and the post ID was missing. This triggered a fatal error on some installations. This has now been fixed.

= 1.5.6 (April 12, 2018) =
* **Bug Fix**. Apparently, the latest WPML compatibility fix didn’t work as expected. We’ve now changed this fix, run a few tests with some real customers, and we’re glad to announce it finally works as expected.
* **Bug Fix**. Social message editor didn’t work in Microsoft Edge, because user input wasn’t processed. This has now been fixed.

= 1.5.5 (April 5, 2018) =
* **Bug Fix**. Compatibility issue with WPML fixed. When the different translations of a scheduled post were automatically published, WPML sometimes changed their permalinks and used the same one in all translations. For example, the English and Spanish version of a certain post had both the English permalink. This resulted in social messages being shared incorrectly. This has now been fixed.
* **Bug Fix**. Undefined variable `post_id` notice has been fixed.
* **Bug Fix**. When using Nelio Content in Gutenberg, a Fatal error related to our TinyMCE extensions occurred. This has now been fixed.

= 1.5.4 (March 28, 2018) =
* **Improvement**. Free users now have a redesigned the _Account_ screen.
* **Bug Fix**. In Chrome, new lines in social messages didn’t always work as expected. This has now been fixed.
* **Bug Fix**. Sometimes, it wasn’t possible to edit certain social messages because the related post was missing an edit link, which triggered a JavaScript error. This has now been fixed.
* **Bug Fix**. Sometimes, the filters in the calendar view were set to `null` and prevented the calendar from loading. This has now been fixed.
* **Bug Fix**. In the calendar view, unscheduled posts weren’t removed from the unscheduled list when trashed using the edit dialog. This has now been fixed.
* **Bug Fix**. In the calendar view, the author of a post wasn’t automatically selected when editing it using the edit dialog. This has now been fixed.

= 1.5.3 (March 21, 2018) =
* **Bug Fix**. Automatic messages on published posts weren’t properly scheduled, because the publication date of said posts wasn’t properly retrieved. This has now been fixed.

= 1.5.2 (March 19, 2018) =
* **Improvement**. All users have now access to Social Automations, but only subscribers can benefit from them.
* **Bug Fix**. Sometimes, when sharing a certain post, its permalink wasn’t correct. There was an issue in how WordPress manages _The Loop_. We’ve now fixed this.

= 1.5.1 (March 14, 2018) =
* **Bug Fix**. Social Timeline showed a button (_Create Social Messages Automatically_) to non-subscribed users. This button doesn’t work unless you’re subscribed and, therefore, it shouldn’t have beenthere in the first place. This has now been fixed.

= 1.5.0 (March 12, 2018) =
* **Improvement**. We’ve simplified several components of our plugin (in particular, subscription details and FastSpring related products).
* **Bug Fix**. Properly removing `script` tags from post content before generating social messages automatically.

= 1.4.9 (March 7, 2018) =
* **Bug Fix**. Social messages shown in the editorial calendar were missing some content (like the post’s title). This has now been fixed.
* **Bug Fix**. In the calendar, social messages with line breaks should be shown as if they had no line breaks at all (to reduce the amount of vertical space they take). This has now been fixed.
* **Bug Fix**. Sometimes, when pasting a social message with line breaks in the Social Message Editor, the final result had more line breaks than expected. This has now been fixed.

= 1.4.8 (March 5, 2018) =
* **Improvement**. DIVI breaks our social message editor. We’ve added a tiny fix to prevent that from happening.
* **Bug Fix**. Users subscribed to the Personal Plan couldn’t paste in the Visual Editor due to a bug in our Share button. This has now been fixed.

= 1.4.7 (February 21, 2018) =
* **New Feature** (only for subscribers). Added new image sources in _Add Media_ action. You can now include images from Pixabay directly in your content using WordPress’ default image selector (uploading the image to the Media Library is required).
* **Bug Fix**. If a post is excluded from reshare and you generate automatic social messages, the timeline should only contain messages in _Today_, _Tomorrow_, and _Week_. This has now been fixed.
* **Bug Fix**. There was an error exporting the calendar to _ICS_ format for individual users. This has now been fixed.
* **Bug Fix**. Unscheduled posts that where scheduled in the past appeared when exporting the calendar to _ICS_ format. This has now been fixed.
* **Bug Fix**. Share post type with Nelio’s cloud when scheduling automatic social messages, so that proper templates are used.

= 1.4.6 (January 29, 2018) =
* **New Feature** (only for subscribers). Added new image sources in _Add Media_ action. You can now include images from Giphy and Unsplash directly in your content using WordPress’ default image selector.
* **Improvement**. Tiny redesign in the _Social Profiles Settings_ Screen. Adding a Facebook, Google+, or LinkedIn profile now opens a dialog, clearly showing that these networks have different kinds of profiles (personal profiles, pages, groups, companies, and so on).
* **Improvement**. Social messages created from a Divi-based post do no longer contain Divi shortcodes.
* **Bug Fix**. Styles and JavaScript files are properly included in all pages, even if internationalization changes menu names.
* **Bug Fix**. Removed warning due to an undefined variable name.

= 1.4.5 (January 11, 2018) =
* **New Feature**. Added new filters for customizing the featured images used in automatic messages based on the social network in which they’ll be shared. The filter is `nelio_content_{network}_featured_image`, where `{network}` can be `facebook`, `googleplus`, `instagram`, `linkedin`, `pinterest`, and `twitter`.
* **New Feature**. Added new filter for customizing the post permalink used in Social Message: `nelio_content_post_permalink`.
* **Improvement**. New class loader implemented, so that Nelio Content’s classes are loaded if, and only if, they’re needed in current execution.
* **Bug Fix**. Notifications were sent to authors regardless of the _Notifications_ setting. This has now been fixed and notifications are only sent if you’ve enabled them.
* **Bug Fix**. There was a fatal error in some cron tasks, because class `Nelio_Content_User_Helper` couldn’t be found. This has now been fixed.
* **Bug Fix**. Setting a date of an unscheduled post in the calendar using the Edit Post Dialog didn’t work and the post remained unscheduled. This has now been fixed.

= 1.4.4 (December 13, 2017) =
* **New Feature**. Don’t create social messages from scratch. Instead, use already-created social messages as the basis of your new messages.
* **Bug Fix**. Some HTML entities weren’t properly escaped in post titles and, therefore, automatic social messages looked weird. This has now been fixed.

= 1.4.3 (December 5, 2017) =
* **Bug Fix**. JavaScript error stopped users from using Nelio Content in the Post Edit Screen. This has now been fixed.

= 1.4.2 (December 5, 2017) =
* **New Feature**. The plugin is now able to send _Notifications_ to your users when the status of a Post changes, there are new _Editorial Comments_ in a Post, or an _Editorial Task_ is created or completed.
* **New Feature**. Export your Editorial Calendar using the `ical` format, so that you can include it in Google Calendar or other calendar tools.
* **Improvement**. Added Bulk Edit option for disabling automatic reshare.
* **Bug Fix**. Next renewal date was incorrect in the Account page (it always showed “today”). This has now been fixed.
* **Bug Fix**. Social Profile settings are available to Author users.
* **Bug Fix**. When creating a social message without an explicit permalink in its content, there shouldn’t be any “preview card.” This has now been fixed.
* **Bug Fix**. In the _Analytics_ page, you couldn’t add an image to a social message because the image selector dialog wouldn’t open. This has now been fixed.

= 1.4.1 (November 15, 2017) =
* **Bug Fix**. Under some weird circumstances, Nelio Content deleted WordPress posts that were detected as external references. This has now been fixed and Nelio Content is no longer allowed to remove WordPress content.
* **New Features**. Added colors to your Editorial Tasks.
* **Improvement**. You can now see when Nelio Content published the last message of any given post in both the Analytics page and whilst editing a post.
* **Improvement**. In Pinterest you no longer need to explicitly include the post’s permalink in your social message to make the shared image a link to your WordPress post. From now on, this link is implicity: if you’re sharing a WordPress post, the Pinterest image will take your visitors to your blog.

= 1.4.0 (November 9, 2017) =
* **New Feature**. Unscheduled Posts section in the calendar is now available. You no longer need to schedule all your posts in order to see them in the calendar. From now on, there’s a list of unscheduled posts. Move posts from the calendar to this section to unschedule a post; move it the other way around to schedule it.
* **New Feature**. The plugin is now disabled in URLs containing the “staging” keyword. This way, a staging site won’t interfere with your live site. There’s a new filter (`nelio_content_staging_urls`) to let Nelio Content know which URLs should be considered as “staging URLs.”
* **Improvement**. Maximum tweet length has been updated to 280 characters.
* **Improvement**. Actions in calendar days now look and behave better, specially when the calendar is small.
* **Improvement**. You can now use post excerpts in your social templates.
* **Bug Fix**. The button for suggesting references is properly enabled when a URL is pasted in its related input field.

= 1.3.6 (September 29, 2017) =
* **Improvement**. New filter `nelio_content_permalink` for customizing post permalinks used in Nelio Content. With this filter, you’ll be able to change the value of `{permalink}` placeholders in your social messages.
* **Bug fix**. Extracting titles properly for suggested and included external references.
* **Bug fix**. Editing a social message from the calendar resulted in Nelio Content sharing your home page. This has now been fixed.

= 1.3.5 (September 14, 2017) =
* **Bug fix**. Nelio Content triggered multiple requests to the WordPress server whilst editing content to obtain information about the external references. Most of these requests were redundant, and resulted in poor JavaScript performance. This has now been fixed.
* **Improvement**. Meta boxes in the post edit screen now show more specific error messages.
* **Improvement**. Modified the information we send to Nelio’s cloud when saving/publishing a new post.

= 1.3.4 (September 12, 2017) =
* **New Feature** (only for subscribers with social automations). Manually highlight the sentences in your content that Nelio should try to automatically schedule when creating automatic messages.
* **New Feature** (only for subscribers). Automatically extract the external links included in a post and use them to generate social messages that mention the referenced authors.
* **Improvement** (only for subscribers). The visual editor now includes a char counter when text is selected. This will help you determine whether your current selection can be shared or not.
* **Improvement**. All users (including free users) should be able to schedule social messages for _Today_ in the _Social Timeline_. This has now been fixed.
* **Improvement**. Fixed the style of plugin manager selector (applies to free and personal plans only).
* **Improvement**. Retrieving meta information from Medium links now works as expected.
* **Improvement**. All messages in timeline have a special icon indicating the source of the message. Messages can be based on a _Publication_, _Reshare_, or _Reference_ template; _Highlighted Fragment_ (if the message is a fragment the user highlighted in the post content); and _Automatically Extracted Sentence_ (if Nelio extracted the fragment from the post content based on its relevance).
* **Bug fix**. When the user was about to subscribe to a yearly plan from the plugin, they were redirected to our website to complete the subscription. The problem: they were shown the monthly plan (instead of the yearly plan). This has now been fixed.
* **Bug fix**. To access the API, Nelio Content generates an access token that expires in a few minutes and saves it as an expiring transient. For some reason, sometimes the transient never expired (the `transient_timeout` option was lost) and, as a result, API errors occurred. We now make sure that the transient can expire by checking the existence of a `transient_timeout` option.

= 1.3.3 (July 28, 2017) =
* **Improvement**. Some WordPress installations use absolute paths without the domain name in post permalinks. A social message sharing that permalink wouldn’t work. We now detect this scenario and add the WordPress domain name if required.
* **Improvement**. New implementation for converting HTML code to plain text, which is used for generating automatic messages.
* **Bug fix**. A couple of templates used the old version of _momentjs_ (see update 1.3.1). We’ve now fixed this.

= 1.3.2 (July 25, 2017) =
* **Bug fix**. In version 1.3.1 we updated _momentjs_, but we didn’t update its version number. As a result, browsers were serving an old, cached version of the library. Forcing a refresh would have fixed the issue. This version defines a new version number for _momentjs_ so that manual refreshing is not required.

= 1.3.1 (July 24, 2017) =
* **Improvement**. Updated Twitter preview when creating social messages. Social profile picture is now rounded.
* **Improvement**. Some WordPress installations use absolute paths without the domain name in their image URLs. Social messages with such images didn’t work. We now detect this scenario and add the WordPress domain name if required.
* **Improvement**. Our plugin uses the _momentjs_ library for working with dates. Since other plugins also use this library, we identified that, sometimes, their version of the library and ours resulted in an incompatibility issue. We renamed the library from `moment` to `ncmoment`, hoping to fix these issues.
* **Bug fix**. Post selector for resharing old content in the calendar triggered a JavaScript error due to an undefined variable. This has now been fixed.
* **Bug fix**. Dates before 2000 couldn’t be selected in the analytics page. This has now been fixed.
* **Bug fix**. Checking if a certain role exists before adding new capabilities.
* **Bug fix**. Arrays in the `Auto_Sharer` class are created using `array()` instead of `[]` (see this [support thread](https://wordpress.org/support/topic/site-unavailable-after-update-1-3-0-parse-error-unexpected-in/)).
* **Bug fix**. Accessing a property of a `null` object (see this [support thread](https://wordpress.org/support/topic/notice-trying-to-get-property-of-non-object-in-home-wp-co/)).

= 1.3.0 (July 18, 2017) =
* **Auto-Schedule** (new optional addon for subscribers). Generate the social timeline for promoting your posts automatically, summarizing the content, using templates, and mentioning external sources. Reshare your old content automatically.
* **Improvement**. Graphical assets are now in SVG (instead of PNG). This should look better on all devices.
* **Improvement**. Added placeholder on date selectors, so that Safari and Firefox users know the expected time format.
* **Improvement**. When creating a Facebook message with a single URL, this URL is hidden from the final message (just as Facebook does by default).
* **Improvement**. New design of the Social Profile Settings screen. The buttons for adding new profiles appear at the end of the screen and the focus is set on your connected social profiles.
* **Improvement**. Tabs in Settings screen now use regular `a` tags instead of `span` tags. This means you can now open each category in a new browser tab easily.
* **Improvement**. When creating new posts from the calendar, you can now select their category.
* **Improvement**. Cropping URL length in Social Timeline, so that it doesn’t disturb users too much.
* **Improvement**. Added new filter: `nelio_content_analytics_post_paths`. Used in Google Analytics for grouping alternative post paths in a single query.
* **Improvement**. Modified all underscore templates to use mustache-like tags. Previously, we used underscore’s default tags (`<%`, `%>`, and so on), which generated fatal errors in some PHP installations where [ASP-like tags were enabled](http://php.net/manual/en/language.basic-syntax.phptags.php).
* **Improvement**. Matching internal links in post analysis regardless of the specific protocol used (`http` vs `https`).
* **Bug Fix**. The social timeline shows the initial dot `.` of all tweets starting with a dot `.` and a mention.
* **Bug Fix**. Using _Site Address_ (instead of _WordPress Address_) to detect internal links in post analysis.
* **Bug Fix**. Date selector in Analytics screen works properly. In the previous version, some options couldn’t be selected.
* **Bug Fix**. Modified CSS rules so that Select2 dropdown doesn’t appear below dialogs (surprisingly, it still did on some installations).
* **Bug Fix**. Nelio’s post analysis didn’t analyze links and images properly without editing the content in the editor. Hopefully, this version fixes the issue.
* **Bug Fix**. Several _Save_ buttons show a warning message when disabled (e.g. in the Social Message Editor Dialog). This message wasn’t properly escaped and weird HTML codes were visible. This is now fixed.

= 1.2.14 (June 28, 2017) =
* **Bug Fix**. Sometimes, Google Analytics view selector generated a JavaScript error and the user wasn’t able to select any views. This has now been fixed.
* **Improvement**. Support for Nelio Content new plans has been added.

= 1.2.13 (May 30, 2017) =
* **Bug Fix**. Adding new social profiles refreshes the settings page properly, so that you can see the new profile instantaneously.

= 1.2.12 (May 2, 2017) =
* **Improvement**. The plugin does no longer block access to editing content when Nelio Content’s API wasn’t accessible.
* **Improvement**. SSL Certificate of API renewed. The temporary fix of the previous version has been reverted and the proper API URL is used.

= 1.2.11 (April 30, 2017) =
* **Bug Fix**. SSL Certificate of API expired. This version provides a temporary fix until the SSL Certificate is renewed.

= 1.2.10 (April 19, 2017) =
* **Improvement**. Social messages of unpublished posts used to show a “fake” permalink (e.g. `https://example.com/post-name/`) in the timeline and preview sections. This has now been changed and the actual permalink of the related post is shown instead.
* **Bug Fix**. When you publish a post, the plugin sends a notification to Nelio Content’s cloud so that social messages are automatically sent at the right time. If this notification failed, messages wouldn’t be sent. This version detects such failure and re-synchronizes WordPress and Nelio Content to avoid this issue.

= 1.2.9 (April 6, 2017) =
* **Colors in Calendar**. Posts in the calendar now have different colors based on their status. You can disable this in _Settings_ » _Content_.
* **New Post Statuses**. We added new post statuses to improve your workflow. These statuses have their own icon and color in the calendar. You can enable them in _Settings_ » _Advanced_.
* **Improvement**. Calendar filters are now “permanent.” That is, if you apply certain filters in the calendar, these filters will still be active after you reload the page.
* **Improvement**. Modified the code for extracting the author name of any given URL. The meta info extractor might still fail, but it’s slightly better now.

= 1.2.8 (March 23, 2017) =
* **Bug Fix**. Hiding Editorial Task meta box if user is not subscribed (because they can’t interact with it).

= 1.2.7 (March 22, 2017) =
* **New Feature** (only for subscribers). Download your calendar as a CSV file.
* **Improvement**. You can now view Pages in your calendar and schedule social messages promoting them.
* **Bug Fix**. If a user can’t create a post from Nelio’s editorial calendar, the “Save” button label is properly updated (from, for example, “Saving” to “Save”).
* **Bug Fix**. Preview card works properly when `{permalink}` set in its own line.

= 1.2.6 (March 16, 2017) =
* **Bug Fix**. Last update removed social meta box (and others) from the Edit Post screen. This has now been fixed.

= 1.2.5 (March 15, 2017) =
* **Improvement**. Added warning message in Analytics when GA tokens expired and reauthentication is required.
* **Improvement**. Using subscription plan properly to show the right set of tools to each user.
* **Bug Fix**. Some WordPress setups modify jQuery’s AJAX default headers, preventing our plugin from communicating with our cloud. We now force the appropriate headers in our own AJAX calls to solve this.

= 1.2.4 (March 14, 2017) =
* **Bug Fix**. “Too Many Requests” HTTP error doesn’t block the analytics computation process.

= 1.2.3 (March 8, 2017) =
* **New Feature**. Calendar collapses sent social messages to simplify the UI and offer more information in less space.
* **Improvement**. Reduced item size in calendar, so that the screen can show more information.
* **Improvement**. Using helper functions for checking the plan the user is subscribed to (if any).
* **Improvement**. Reduced number of calls to the API and, therefore, improved performance.
* **Premium Trial**. Prepared the code base to support free trials of our premium plans.
* **Bug Fix**. Social message length counting now uses [Twitter Text library](https://github.com/twitter/twitter-text/tree/master/js).
* **Bug Fix**. Buttons in (Custom) Featured Image Box are now properly translated.

= 1.2.2 (February 20, 2017) =
* **Improvement**. Extended number of chars allowed in Facebook and Instagram.
* **Improvement**. Faster and more responsive calendar.
* **Improvement**. Reset jQuery UI default styles, so that dialogs look the way they’re supposed to.
* **Bug Fix**. StoreJS library has been renamed to avoid name collisions with other plugins.

= 1.2.1 (February 10, 2017) =
* **Improvement** (only relevant to subscribers). We added the _Share Selection_ action button in WordPress _Text_ (HTML) editor.
* **Improvement**. Added author and date filters in post analytics.
* **Bug Fix**. Task filter in calendar now shows all users in the system.
* **Bug Fix**. Removed unnecessary `error_log`s from code.
* **Bug Fix**. Sometimes, engagement analytics (the overall counting) didn’t include Facebook likes. This has now been fixed.
* **Bug Fix**. Iterating over the list of Google Analytics views properly, so that all of them are included in the selector.

= 1.2.0 (February 1, 2017) =
* **New Feature** (only available to subscribers). Create social messages faster by selecting some text in the TinyMCE editor and clicking on the new _Share Selection_ action button.
* **New Feature**. Analytics section with information about your content’s social media impact and traffic acquisition rates.
* **Improvement**. Featured images in Mega Menus (a feature from Newspaper Theme) now have the right dimensions.
* **Improvement**. When sharing old posts in social media, the post selector loaded only 10 posts at a time. This has now been increased to 50.
* **Improvement**. Highlighting numeral hashtags in Pinterest.
* **Improvement**. Extended number of chars allowed in Pinterest and Google+.
* **Improvement**. Synchronization of subscription information is now properly implemented.
* **Bug Fix**. Social messages with HTML tags in their content triggered a JavaScript error. These are now properly escaped.
* **Bug Fix**. Some plugins enqueue styles that overwrite WordPress’ default rules. For instance, jQuery UI stylesheet resets the `z-index` property of jQuery dialogs, which results in those dialogs appearing below other HTML components. We tried to fix this.
* **Bug Fix**. If the user cleans the plugin, we try to clean the information in the cloud too. This failed sometimes, and resulted in the user not being able to effectively clean the plugin. This has now been fixed; if we can’t access the cloud, we’ll still clean the local database and let the user know something went wrong and ask them to contact us.

= 1.1.10 (December 27, 2016) =
* **Bug Fix**. Last update throwed a warning (which, in some cases, resulted in a WSOD) because the plugin tried to iterate over a non-array element returned by Nelio’s settings. This has now been fixed.

= 1.1.9 (December 26, 2016) =
* **Bug Fix**. Publishing custom post types sends scheduled social messages as expected.
* **Bug Fix**. Network icons in calendar are rounded again.
* **Bug Fix**. The number of social profiles you can connect in the free version was incorrect—instead of 4, it’s 6.
* **Bug Fix**. Disabled social profile networks can no longer be clicked.
* **Bug Fix**. Pinterest and Instagram require you to share an image. If you don’t add one, social messages can’t be saved. In the previous version, sometimes the _Save_ button remained disabled even if you added an image. This has now been fixed.

= 1.1.8 (December 19, 2016) =
* **Bug Fix**. On Chrome 55, adding images to social messages repositioned the social message dialog, making it difficult to continue to work with it. This update fixes the issue.

= 1.1.7 (December 19, 2016) =
* **Improvement**. Google Plus and Instagram support added using [Buffer](http://bufferapp.com/).
* **Improvement**. External Featured Images also work if inserted using `wp_get_attachment_image`.

= 1.1.6 (December 12, 2016) =
* **Bug Fix**. Support Thread [Autoset Featured Image error with newspaper theme](https://wordpress.org/support/topic/autoset-featured-image-error-with-newspaper-theme/) is fixed.
* **Bug Fix**. Editing an already-existing social message in calendar now loads the appropriate preview.

= 1.1.5 (December 9, 2016) =
* **Bug Fix**. Changes in your subscription (under the _Account_ screen) does no longer return a 401 error.
* **Improvement**. External featured images inserted using `(get_)the_post_thumbnail` now take into account all this function’s params.
* **Improvement**. Pagefrog does no longer break our plugin in custom post types.
* **Bug Fix**. JavaScript error on (some) custom post types.

= 1.1.4 (November 24, 2016) =
* **Bug Fix**. Editing a post from the _Calendar_ or using _Quick Edit_ in _All Posts_ screen removed the featured image of said post. This is now fixed.

= 1.1.3 (November 23, 2016) =
* **Bug Fix**. Pages couldn’t be edited with version 1.1.1 and 1.1.2 due to an unexpected error. This has now been fixed.

= 1.1.2 (November 22, 2016) =
* **Bug Fix**. Sometimes, the post analysis reported that there was a featured image set, even if none was actually set. This has now been fixed.
* **Improvement**. Post analysis now takes into account whether the analyzed post type supports featured image and, if they’re not supported, the plugin doesn’t check whether the post includes one or not (it simply doesn’t make sense). Similarly, tags are only checked if the analyzed post is a WordPress regular _Post_.
* **Improvement**. Tiny UI modifications in Connected Social Profiles screen.

= 1.1.1 (November 18, 2016) =
* **New Feature**. [Nelio External Featured Image](https://wordpress.org/plugins/external-featured-image/) has been re-implemented using a new engine and merged into Nelio Content. Now, you can insert Featured Images using external URLs with just a couple of clicks. It works with virtually all themes, including Newspaper, Newsmag, and Enfold, among others.
* **New Feature**. Use one of the images in the post as the featured image. This feature uses the improved external featured image engine, so that the image in the post is properly scaled and cropped when used as featured image.
* **Bug Fix**. The button for Re-Authenticating social profiles is working properly. In previous versions, the profile wasn’t reauthenticated&mdash;a new profile was registered instead.
* **Bug Fix**. Social message preview for drafts now shows the post card properly in Twitter, Facebook, and so on.

= 1.1.0 (November 10, 2016) =
* **New Feature**. You can now use custom post types in the calendar! Manage your regular posts and/or custom post types from Nelio’s editorial calendar and save time.
* **Improvement**. Social message preview now matches Twitter new styles (e.g. last link is hidden and only shown as a “preview card”).
* **Improvement**. During installation, we now detect if the server supports SNI and, if it doesn’t, we access Nelio Content’s API using a secure proxy.
* **Improvement**. Select2 components are now internationalized.
* **Bug Fix**. Sometimes, Nelio’s post analysis didn’t analyze post length or links properly. This was supposed to be fixed in the previous version, but it wasn’t. Hopefully, it is now.

= 1.0.5 (September 29, 2016) =
* **Testing Environment**. We’ve included a test suite in development for testing the plugin. Notice the test suite is not included in releases/tags.
* **Bug Fix**. If the post doesn’t have an excerpt, the post quality analysis detects it properly and warns the user about this situation.
* **Bug Fix**. Sometimes, Nelio’s post analysis didn’t analyze post length or links properly. This has now been fixed.
* **Improvement**. We’ve extended our code base so that we can overcome some issues generated by other plugins. In this release, we added a snippet of code for [fixing these issues generated by Pagefrog](https://neliosoftware.com/blog/when-wordpress-freedom-kills-your-business/).
* **Improvement**. Previous versions of Nelio Content loaded all author users in a JavaScript object. Usually, this works perfectly fine, but on some installations with tons of “author users,” page load times might be slower. We now modified this behavior so that author users are loaded “on demand,” offering a more responsive UX.
* **Improvement**. Post editor dialog in calendar page has now been slightly redesigned. From now on, users can open/preview any given post from the calendar itself (there’s a new action button for that).
* **Minor Improvement**. An author with the admin role now triggers a “soft warning” (orange) in the post analysis section.
* **Minor Improvement**. In a multisite installation, you can now jump to the calendar of each individual blog directly using “My Sites” in admin bar.
* **Minor Improvement**. After accepting Nelio Content’s terms and conditions, new users are automatically redirected to the calendar page (instead of the account page).
* **Minor Improvement**. Refactored some portions of our code.


= 1.0.4 (September 6, 2016) =
* **Improvement**. When loading the current month in the calendar, the UI now smoothly scrolls to “today” using an animation.
* **Improvement**. First-time post analysis (right after loading the page for editing a post) is faster.
* **Improvement**. Posts without a publication date triggered a JavaScript warning. This has now been fixed and MomentJS does no longer complain.
* **Bug Fix**. Sometimes, suggested references included in a post weren’t marked as such unless the user changed the post content. This has now been fixed.
* **Bug Fix**. Now, you can only add new items on future dates and today. Before the fix, you could add items on any date whose month and day was past the current date, regardless of the year (which was clearly wrong).
* **Bug Fix**. Calendar does no longer “auto-scroll” up (or down) after dragging an element around and dropping it in the top (or bottom) shadow.

= 1.0.3 (August 10, 2016) =
* **Improvement**. Shortcut to Nelio’s Calendar in top admin bar, under site menu.
* **Improvement**. Social message previews highlight hashtags and mentions properly, using a library from Twitter: `twitter-text` ([see it on GitHub](https://github.com/twitter/twitter-text/)).
* **Improvement**. If your social message has multiple lines, you’ll see them on the preview.
* **Improvement**. Default profile pictures show the first letter of the user.
* **Bug Fix**. When adding images to Twitter messages, char count didn’t match Twitter’s char count. As a result, some messages couldn’t be shared. This has now been fixed.
* **Bug Fix**. Given a scheduled social message that’s related to a published post, it can now be rescheduled in the calendar (it wasn’t possible in previous versions).
* **Minor Tweaks**. A few animations and icons have been polished.

= 1.0.2 (August 2, 2016) =
* **UI Improvement**. A few dialogs are now bigger, so that internationalized titles and texts can fit in.
* **Translators & i18n**. A few more changes in our string contexts and comments, so that translators can translate the plugin easily.

= 1.0.1 (July 27, 2016) =
* **Bug Fix**. Post analysis summary and finer details now match. That is, if the post quality analysis is all green, then Nelio Content reports: _The post looks awesome!_
* **Bug Fix**. Number of words in post is properly counted when using the Text editor.
* **Bug Fix**. Internal relative links are properly managed by the post analysis. Thus, including links such as `/some-page` or `#name` work fine.
* **Translators & i18n**. Some string contexts have been changed from `user` to `text`. Hopefully, they’re more consistent now.

= 1.0.0 (July 25, 2016) =
* **First Release**. This is the first release of our plugin.


