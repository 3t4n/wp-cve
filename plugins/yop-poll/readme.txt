=== YOP Poll ===
Contributors: yourownprogrammer
Donate Link: https://www.yop-poll.com
Tags: create poll, poll plugin, poll, polls, wordpress poll, vote, voting, survey, polling, yop poll, yop, booth
Requires at least: 3.3
Tested up to: 6.4
Stable tag: 6.5.29
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.htm

Use a full option polling solution to get the answers you need.

YOP Poll is the perfect, easy to use poll plugin for your WordPress site.

== Description ==

YOP Poll plugin allows you to easily integrate a survey in your blog post/page and to manage the polls from within your WordPress dashboard but if offers so much more than other similar products.  Simply put, it doesn't lose sight of your needs and ensures that no detail is left unaccounted for.

To name just a few improvements, you can create polls to include both single or multiple answers, work with a wide variety of options and settings to decide how you wish to sort your poll information, how to manage the results, what details to display and what to keep private, whether you want to view the total votes or the total voters, to set vote permissions or block voters etc.

Scheduling your polls is no longer a problem. YOP Poll can simultaneously run multiple polls (no limit included) or you can schedule your polls to start one after another. Also, keeping track of your polls is easy, you have various sorting functions and you can access older versions at any time.

Designed to intuitive and easy to use, this plugin allows shortcodes and includes a widget functionality that fits perfectly with your WordPress website. For more details on the included features, please refer to the description below.

Current poll features:

   *  Create/ Edit / Clone/Delete poll - allows you to create or intervene in your poll at any time, if you consider it necessary.

   *  Poll scheduling:  programs each poll to start/end on a certain date. You can simultaneously run multiple polls. This option can be used to schedule your polls one after another.

   *  Display polls: you can choose to display one or more polls on your website by simply adding the corresponding poll ID. You can also decide for a random display of your active polls.

   *  View all polls: lists all your polls that you can sort by number of votes or voters, by question or by date. It also includes a search option.

   *  Poll answers - allows other answers, multiple answers and includes a sorting module by various criteria: in exact order, in alphabetical order, by number of votes, ascending, descending etc.

   *  Poll results - offers a great flexibility when displaying the results: before/after vote, after poll's end date, on a custom date or never. The results can also be displayed by vote numbers, percentages or both. You can choose to include a view results link, view number of votes or number of voters.

   *  Add custom fields - is a complex option that you can use to ask for additional information from your voters, information that you can then export and use for.

   *  Reset stats - proves useful when you wish to restart a poll.

   *  Vote permissions: - limits the voting accessibility to guests, registered users or both, or blocks user access by cookie, IP and username.

   *  Archive options - allows the users of the website to access former polls statistics. You can choose which polls to display according to their start/end date.

   *  Display Options - displays answers and results tabulated, vertically or horizontally.

   *  Logs and bans - user logs and bans can be seen in the admin section. You can ban users by email, username and IP and you can set the limitation preferences for all your polls or for just one of them.

== Installation ==

1. Upload 'plugin-name.php' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

This plugin was especially designed for flexibility and it is very easy to use. We don't expect you to encounter serious issues, but we included a list with some logical questions that you may find useful.

= How can I create a poll? =

Go to your YOP Poll menu and select the "Add New" option.
Fill the required information according to the examples we included: name, question, answers (add additional ones if you need), select the start/end date for your poll, and decide on the advanced settings for results, votes, voters, accessibility etc.
Once you decided on all your poll details, click on "Save".
To view your new poll access "All Polls" from your main menu and choose the corresponding entry from the list.

= How can I link a poll to my webpage? =

Under "All Polls", each poll has an option called "Get Code".
Clicking on that will display a popup that generates the code you need to place in your page or post.
This is it. Check your page or post now.

= Do you have some predefined shortcodes that I can use? =

Yes
Current Active Poll ID = -1:   [yop_poll id="-1"]
Latest Poll id = -2:           [yop_poll id="-2"]
Random Poll id = -3:           [yop_poll id="-3"]
List with all polls:            [yop_poll_archive max=0 sort="date_added|num_votes" sort_dir="asc|desc"] 

= Can I have more than one poll active? =

Yes, you can run multiple polls at the same time or you can schedule them to begin one after another using the "Select start/end date" option.

= Can I ask for additional information from my voters? =

Yes, you can customize your poll to request additional information. Eg. name, email, age, profession.
To include this, when you create your poll using the “Add New” form, on Questions&Answers tab drag and drop “Custom Field” element and include as many requests as you need.

= How do I check the results? =

Locate the poll you want to evaluate by accessing "All Polls".
Below the name of the poll you have several options.
Use the "View Results" option to track the results of the poll,
or access the "Logs" for a more detailed evaluation.

= How can I see the results after the poll ends? =

Edit your poll and in "View Results:" choose "After Poll End Date" and save.

= Can I add more than one question to a poll? =

You can have only one question per poll.

== Screenshots ==

1. Choose Template
2. Choose Skin
3. Available Skins
4. Available skins
5. Customize Style
6. Add Question and Answers
7. Define Poll Settings
8. Define Poll Access Settings
9. Define Poll Results Settings
10. View Results
11. Poll With Vertical Display
12. Poll With Vertical Display Showing Results Before Vote

== Changelog ==

= 6.5.29 =
* fixed issue with built in captcha not working on php 8
* fixed issue with built in captcha allowing multiple votes with the same captcha response

= 6.5.28 =
* fixed issue with voting when other answers option is enabled

= 6.5.27 =
* fixed TOCTOU vulnerability in the voting process
* display newest polls by default on View Polls page


= 6.5.26 =
* remove username and password sanitization for login. wp_signon will handle the sanitization
* added Create Account and Forgot Password links on login modal

= 6.5.25 =
* fixed php warning when using YOP Poll as a widget
* fixed issue with GDPR checkbox not displaying on Brave browser

= 6.5.24 =
* added question and custom field name as column headers in exported votes file
* removed poll name column from exported votes file
* cleaned the exported votes file
* fixed issue with displaying custom field records on results page

= 6.5.23 =
* updated admin javascript to load in footer to prevent conflict with other plugins

= 6.5.22 =
* fixed issue with disabling multiple answers per vote after it has been enabled
* added screen options for Bans page. You can now set the number of records per page
* added yop_poll_vote_recorded_with_details hook for a vote recorded. Poll id and Vote details are passed to the callback

= 6.5.21 =
* added sorting option for Start Date and End Date on View Polls page
* added column for shortcode on View Polls page. You can now get the shortcode for a poll without any extra steps/clicks.
* added screen options for View Logs page. You can now set the number of records per page.

= 6.5.2 =
* added screen options for View Polls page. You can now set the number of polls per page.
* added screen options for View Votes page. You can now set the number of votes per page.
* added Reset Votes option for each poll. You can now reset votes for a poll much easier.

= 6.5.1 =
* fixed issue with voting throwing a notice
* added an option for redirect after vote. You can now set the delay (in seconds) for the redirect
* added yop_poll_vote_recorded hook for a vote recorded. Poll id argument is passed to the callback

= 6.5.0 =
* fix conflict with JNews theme causing issues with "Add Votes Manually" feature
* fix conflict with plugins and themes using datetimepicker

= 6.4.9 =
* fixed voting issue when there are multiple polls on the same page

= 6.4.8 =
* added ajax login when allowing votes from wordpress users
* updated voting flow in facebook browser

= 6.4.7 =
* fixed issue with shortcode popup not showing on certain themes
* fixed issue with displaying current active poll

= 6.4.6 =
* fixed issue with google reCaptcha v2 Checkbox

= 6.4.5 =
* fixed issue with blocking voters per day by reseting at midnight instead of 24 hours from the time of the vote

= 6.4.4 =
* added sanitization for custom headers
* updated 5.x and 4.x importers

= 6.4.3 =
* fixed security issue
* fixed issue with vote button not working inside elementor popups
* added an option to choose if custom headers should be used when getting the ip address of the voters

= 6.4.2 =
* fixed issue with other answers not displaying in view votes details
* fixed issue with other answers not included in exports

= 6.4.1 =
* fixed issue with other answers not being included in notification emails
* added page/post id to the vote data

= 6.4.0 =
* fixed issue that was causing translation files not to load properly

= 6.3.9 =
* fixed issue that was preventing editing polls on windows servers

= 6.3.8 =
* updated sanitization for templates and skins
* fixed issue with total votes and answers not displaying correctly

= 6.3.7 =
* fixed issue with built in captcha
* updated sanitization for built in captcha
* updated the design of built in captcha

= 6.3.6 =
* fixed issue with polls not displaying correctly in widgets
* fixed archive shortcode to only display published polls
* added more sanitization for arrays and objects

= 6.3.5 =
* fixed typo in 4.x importer causing issues on some installs

= 6.3.4 =
* fixed issue with google reCaptcha v2 Invisible
* fixed issue with archive shortcode displaying polls not started when "show" is set to active
* fixed issue with displaying incorrect message when a poll is ended
* added support for hCaptcha
* added more sanitization

= 6.3.3 =
* fixed XSS bugs
* fixed issue with validating email addresses when sending notifications for votes
* added tags for messages - [strong][/strong], [u][/u], [i][/i], [br]
* added tags for elements - [strong][/strong], [u][/u], [i][/i], [br]
* added support for links in the consent text
* added new option for yop_poll_archive shortcode. Now it supports displaying only polls that accept votes. Usage - [yop_poll_archive sort=date_added/num_votes sortdir=asc/desc max=0/number-desired show=active/ended/all]

= 6.3.2 =
* fixed issue with migrating polls from versions lower than 6.0.0

= 6.3.1 =
* fixed XSS bugs CVE-2021-24833, CVE-2021-24834 - Props to Vishnupriya Ilango of Fortinet's FortiGuard Labs
* fixed issue with custom styles not applying to custom fields

= 6.3.0 =
* fixed issue with bans affecting all polls when creating a ban specific to a poll
* added support for %VOTER-EMAIL%, %VOTER-FIRST-NAME%, %VOTER-LAST-NAME%, %VOTER-USERNAME% in both subject and body. These tags can be used only when allowing votes from wordpress users

= 6.2.9 =
* fixed issue with "Ban by Username" not working as expected
* fixed display issue on View Bans page
* added support for %VOTER-EMAIL% in Recipients list when sending email notifications. The tag can be used only when allowing votes from wordpress users

= 6.2.8 =
* fixed XSS bug
* fixed issue with allowed formatting tags for answers not showing when displaying results

= 6.2.7 =
* fixed issue with answers set as default not showing selected
* added an option to choose the location for the notification section. When set to "Bottom" scrolling to the top of the poll is disabled

= 6.2.6 =
* fixed error showing up when activating the plugin via cli
* remove scrolling effect when voting
* added more parameters to [yop_poll_archive]

= 6.2.5 =
* fix issue with notification message not being updated on successfull votes
* added reset for radio and checkbox controls on page refresh

= 6.2.4 =
* fixed issue with GDPR/CCPA checkbox when having multiple polls on the same page
* fixed issue with Results and Get Code icons not showing
* fixed issue with cloning polls

= 6.2.3 =
* fixed issue with [br] tag showing on results page
* added more tags for answers - [strong][/strong], [p][/p], [b][/b], [u][/u], [i][/i]

= 6.2.2 =
* fixed issue with polls loading with ajax
* added %VOTER-FIRST-NAME%, %VOTER-LAST-NAME%, %VOTER-EMAIL%, %VOTER-USERNAME% to new vote email notifications

= 6.2.1 =
* removed 2 options from built in captcha
* updated icons for View Results and Get Shortcode
* fixed issue with duplicate answers when viewing results

= 6.2.0 =
* fixed issue with google reCaptcha loading intermitently when polls are loaded with ajax
* fixed issue with google reCaptcha when allowing votes from guests and wordpress users
* added support for google reCaptcha v3

= 6.1.9 =
* fixed issue with wp login window blocking voting if window is manually closed

= 6.1.8 =
* fixed issue with votes not being deleted when poll is removed
* fixed issue with logs not being deleted when poll is removed
* fixed issue with guest voting and limit number of votes
* fixed issue on edit poll screen that was causing polls to stop displaying when a new template was choosen

= 6.1.7 =
* fixed broken css rule
* added option to keep/remove plugin data on uninstall
* added default message with tags for email notifications

= 6.1.6 =
* fixed issue with blocking voters when wordpress voting is enabled

= 6.1.5 =
* fixed typos
* fixed security issue when previewing a poll
* fixed issue with loading language files
* fixed issue with loader not being shown when voting
* fixed issue with answers displayed below radio/checkbox controls on small screens

= 6.1.4 =
* fixed issue with polls loading in facebook inapp browser
* fixed issue with scroll location when there is an error in voting
* moved voting buttons at the bottom of poll container
* added links to answers when displaying results
* added support for adding custom fields on click

= 6.1.2 =
* fixed conflict with JNews theme
* fixed issue with answers being displayed twice in results
* improved flow for Edit Poll
* fixed XSS bug

= 6.1.1 =
* fixed display issue for Sort Results when "As Defined" is choosed
* removed select2 controls
* improved polls display when a start/end date is choosed
* added option to load polls via ajax
* added support for reCaptcha v2 Invisible

= 6.1.0 =
* fixed issue with limit votes
* fixed issue with other answers when "Show in results" is set to Yes
* fixed issue with fingerprint
* removed extra space on results page when an answer has no votes

= 6.0.9 =
* fixed issue with cloning polls
* fixed issue with editing poll duplicating new elements
* fixed issue with display results tag
* fixed issue with resetting settings when plugin was disabled
* fixed issue with customizing skin throwing an error on saving poll
* fixed issue with results not sorting "View Results" option
* fixed issue with recaptcha
* fixed issue with font size
* fixed issue with color for messages
* fixed issue with tracking ids
* improved email notifications
* added a new option for blocks
* added labels to answers for better user experience

= 6.0.8 =
* added ability to manually add votes
* added support for multisite
* fixed issue with built in captcha not working on nginx environments
* fixed issue with sorting results

= 6.0.7 =
* fixed issue with other answers when resetting votes
* fixed issue with timezones when using block feature

= 6.0.6 =
* fixed issue with blocking voters
* fixed issue with logs
* fixed issue with bans
* fixed issue with settings
* fixed issue with WordPress voting

= 6.0.5 =
* added skins
* redesigned templates
* improved ux for chosing templates
* cleaned add/edit poll screens
* cleaned files structure

= 6.0.4 =
* added ability to search votes
* added ability to delete votes
* added columns for username and email on View Votes screen
* added notifications messages to admin settings
* fixed css issue
* fixed issue with overlapping
* fixed compatibility issue with Elementor
* fixed bug with searching logs

= 6.0.3 =
* added support for reCaptcha v2
* added scroll to thank you/error message after voting
* fixed spacing with total votes
* fixed issue with thank you message not being displayed when GDPR enabled
* fixed XSS vulnerability
* updated notification messages for blocks and limits

= 6.0.2 =
* load plugin js and css only on plugin pages
* fixed issue with exporting custom fields data
* added column for each custom field when exporting votes
* fixed issue with "Show total answers" being set to "Yes" when "Show total votes" is set to "Yes"
* fixed issue with email notifications
* fixed issue with captcha
* added support for poll archive page
* added ability to set number of polls displayed per page
* fixed issue with results colour when poll is ended
* fixed issue with generating page for poll
* removed p tag from notification messages
* fixed issue with gdpr consent checkbox

= 6.0.1 =
* css cleanout
* fixed issue with css for custom fields
* fixed issue with the gridline
* fixed issue with results after vote
* fixed issue with displaying number of votes and percentages
* fixed issue with spacing between answers
* fixed issue with export
* fixed issue with redirect after vote time
* fixed issue with reset votes
* fixed issue with results set to Never
* fixed issue with deleted polls

= 6.0.0 =
* complete re-write
* add GDPR compliance

= 5.8.3 =
* fixed php7 issues

= 5.8.2 =
* fixed issue with notices showing up on front pages

= 5.8.1 =
* fixed security issue
* fixed issue with multisite
* compatibility with WordPress 4.7.2

= 5.8.0 =
* compatibility with WordPress 4.5.2
* fixed issue with navigation links on archive page
* fixed loading issue
* fixed issue with custom fields

= 5.7.9 =
* start date and end date easier to read on the front end
* Fixed issue with showing results before vote

= 5.7.8 =
* Fixed issue with reset stats
* Fixed security issue
* Fixed issue with automatically reset stats
* Fixed issue with custom loading image
* Fixed display issues
* Updated Get Code with more options

= 5.7.7 =
* Fixed issue with translations

= 5.7.6 =
* Fixed issues with cloning poll
* Fixed conflicts with different plugins
* Fixed issue with pagination on archive page
* Fixed issue with logs page
* Fixed issue with facebook voting
* Added new shortcuts for email notifications
* Added new column for username in view votes page

= 5.7.5 =
* Fixed issue with vote button not showing up
* Other minor fixes

= 5.7.4 =
* Fixed security issue. A big thank you to [g0blin Research](https://twitter.com/g0blinResearch) for his help in getting this issue fixed

= 5.7.3 =
* Fixed display poll issue

= 5.7.2 =
* Display poll improvements

= 5.7.1 =
* Fixed issue with polls not being displayed

= 5.7 =
* Fixed issue with random polls
* Fixed issue with tabulated display
* Removed autoscroll after a failed vote
* Fixed issue with inserted code when using html editor
* Fixed issue with blocking voters option
* Fixed issue with in_array causing errors
* Fixed twig compatibility
* Added Print Votes page

= 5.6 =
* Fixed issue with login popup
* Fixed issue with vote button
* Fixed issue with html

= 5.5 =
* Fixed issue with clone poll
* Fixed issue with archive page
* Fixed issue with captcha

= 5.3 =
* Fixed issue with links color being overwritten
* Fixed issue with start date and end date not displaying corectly
* Fixed issue with widget
* Added email notifications customization per poll

= 5.2 =
* Complete new design
* Wizard to guide you when creating a poll
* You can now change the order answers are being displayed

= 4.9.3 =
* Fixed security issue. Many thanks to Antonio Sanchez for all his help.

= 4.9.2 =
* Fixed security issue

= 4.9.1 =
* Fixed issue with Template preview not working in IE8
* Fixed issue with wpautop filter
* Redefined admin area allowed tags: a(href, title, target), img( src, title), br
* Fixed issue with Other answers

= 4.9 =
* Added templates preview when adding/editing a poll
* Added sidebar scroll
* Typos fixes
* CSS and Javascript improvements
* Various bugs fixes

= 4.8 =
* Re-added ability to use html tags
* Added new tags: %POLL-SUCCESS-MSG% and %POLL-ERROR-MSG%
* Various bug fixes

= 4.7 =
* Fixed bug with Other answers. Html code is no longer allowed

= 4.6 =
* Added ability to send email notifications when a vote is recorded
* Various bug fixes

= 4.5 =
* Added ability to choose date format when displaying polls
* Added ability to limit viewing results only for logged in users
* Added ability to add custom answers to poll answers
* Added new shortcode [yop_poll id="-4"] that displays latest closed poll
* Added an offset for shortcodes. [yop_poll id="-1" offset="0"] displays the first active poll found, [yop_poll id="-1" offset="1"] displays the second one
* Added WPML compatibility
* Various bugs fixes

= 4.4 =
* Added ability to reset polls
* Added ability to to add a custom message to be displayed after voting
* Added ability to allow users to vote multiple times on the same poll
* Various bugs fixes

= 4.3 =
* Added multisite support
* Added ability to redirect to a custom url after voting
* Added ability to edit polls and templates author
* Added ability to set a response as default
* Improvements on View Results
* Added ability to edit number of votes (very usefull when migrating polls)
* Added tracking capabilities
* Various improvements on logs

= 4.2 =
* Added captcha
* Fixed issue with start date and end date when adding/editing a poll
* Fixed issue with the message displayed when editing a poll

= 4.1 =
* Fixed js issue causing the widget poll not to work

= 4.0 =
* Added ability to use custom loading animation
* Added capabilities and roles
* Fixed issue with update overwritting settings

= 3.9 =
* Fixed display issue with IE7 and IE8

= 3.8 =
* Fixed compatibility issue with Restore jQuery plugin
* Added ability to link poll answers

= 3.7 =
* Fixed issue with Loading text displayed above the polls
* Fixed issue with deleting answers from polls

= 3.6 =
* Fixed issue with missing files

= 3.5 =
* Added french language pack
* Added loading animation when vote button is clicked
* Fixed issue with characters encoding

= 3.4 =
* Fixed issue with menu items in admin area
* Fixed issue with language packs

= 3.3 =
* Added option to auto generate a page when a poll is created
* Fixed compatibility issues with IE
* Fixed issues with custom fields

= 3.2 =
* Fixed bug that was causing issues with TinyMCE Editor

= 3.1 =
* Various bugs fixed

= 3.0 =
* Added export ability for logs
* Added date filter option for logs
* Added option to view logs grouped by vote or by answer
* Various bugs fixed

= 2.0 =
* Fixed various bugs with templates

= 1.9 =
* Fixed various bugs with templates

= 1.8 =
* Fixed bug with WordPress editor

= 1.7 =
* Fixed bug that was causing poll not to update it's settings

= 1.6 =
* Added ability to change the text for Vote button
* Added ability to display the answers for Others field

= 1.5 =
* Fixed sort_answers_by_votes_asc_callback() bug

= 1.4 =
* Fixed compatibility issues with other plugins

= 1.3 =
* Fixed bug that was causing widgets text not to display

= 1.2 =
* Fixed do_shortcode() with missing argument bug

= 1.1 =
* Fixed call_user_func_array() bug
