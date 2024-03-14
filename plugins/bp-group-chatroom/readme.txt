=== BuddyPress Group Chatroom ===
Contributors: aekeron
Tags: buddypress, ajax, chat, groups
Requires at least: 4.6.0
Tested up to: 5.6
Stable tag: 1.7.7
Copyright: Venutius
Basic live chat within BuddyPress groups.
Donate Link: paypal.me/GeorgeChaplin
License: GPLv2 or later

== Description ==

This plugin provides neat chatrooms into BuddyPress groups. Each Group admin can enable a group Chat room, available for all group members to view and post.

The Chat area provides an ajax chat room which displays the most recent messages along with timestamps and usernames.  It also has a "who's online" area which shows other group members viewing the Chat page.

= Features = 

* Who's Online list of members in the chatroom.
* Text Chat: Supports text and links added via the text input box.
* Video and other embeds - Videos such as You-tube can be added as an embedded video using the Video button. The same link can be used for other WordPress embedable urls.
* Emojis supported: over 1,000 emojis easily inserted into the chat stream with options to load them all or just a subset.
* Images: Users with upload_files capability can add images from the media directory/upload into the chat stream.
* Images Lightbox: Supports WP Featherlight lightbox, if you install this plugin then images loaded into chat will open in a lightbox.
* Activity threading: chat conversations can be collected and posted to the activity stream.
* Moderation: Group admin and moderators can delete chat messages.
* Theming: Chat message box colours can be set by Group Admin
* Auto-hide of chat messages after up to 30 days.
* Auto-deletion of chat messages after up to 30 days.

Chat messages support links, embeded video is supported an it's possible to call in another site member to the chat using @mentions.

I've introduced rudimentary threads support. The behavior is that a new message, posted more then 15 minutes after the last message will be marked as a new thread. All messages posted after that initial message will be regarded as part of that thread. After 15 minutes the thread will be deemed closed and if posting of threads to group activity is enabled then all messages in the thread will be posted in a single activity update. I'm expecting this functionality to change as the plugin develops.

Currently the chat supports text chat and the sharing of links. I'm interested in adding further features but would like to see some user requests for the same.

This plugin was originally created by David Cartwright and has been forked by Venutius. It includes an emoji set which was sourced from WP Emoji One by Monchito.net.

This plugin runs from your own server, no chat data leaves your site. This has has the benefit of minimizing your exposure regarding user privacy and GDPR, however chat will be as responsive as your server and can be laggy because of this. User conversations are stored for one month then deleted.

== Installation ==

Download and upload the plugin to your plugins folder. Activate.

== Changelog ==

= 1.7.7 =

* 09/02/2021

* Fix: Translation improvements.

= 1.7.6 =

* 14/01/2021

* Fix: Corrected error with color picker, now works in wp 5.6.

= 1.7.5 =

* 09/01/2021

* Fix: Removed repeating "No messages yet" from chat display to improve UI
* Fix: Removed color picker due to i18n compatibility issues with WP 5+ will try to find a solution and reinstate.

= 1.7.4 =

* 06/05/2019

* Fix: More css improvements, user list consistency update.

= 1.7.3 =

* 05/05/2019

* Fix: Corrected blank messages on page reload.
* Fix: Corrected duplicate messages being displayed.
* Fix: CSS improvements.

= 1.7.2 =

* 23/04/2019

* Fix: Corrected error with mentions notifications.
* Fix: Refactored notifications code.

= 1.7.1 =

* 14/04/2019

* New: Now supports WP Featherlight for lightbox views of images added to chat.
* Fix: Corrected a number of file not found error's related to emojis.
* Fix: Ensured AJAX functions were called with a unique id.

= 1.7.0 =

* 09/04/2019

* New: Users with upload_files capability can upload and add images to the chatroom.
* Fix: Corrected blank notifications issue.
* Fix: Corrected error not clearing down the message after it's sent.

= 1.6.0 =

* 08/04/2019

* Fix: Prevent blank messages.
* Fix: Close Video and Emoji dialog when user changes the selection.
* Fix: Corrected JS Error tabSelector is null when not viewing  group Chat page.
* Fix: Corrected typo causing threads to be always saved to activity.
* Fix: Lowered Online User List refresh count to improve the UX.
* New: Chat message hide time now selectable by Group Admin
* New: Chat message delete time now selectable by Group Admin
* New: Chat colours can now be set by group admin.
* New: Emojis now have an option to load the full set or just a subset.

= 1.5.1 =

* 08/04/2019

* New: Added uninstall.php to clean up database and group meta on uninstall.
* New: Added French translation

= 1.5.0 =

* 06/04/2019

* New: Added support for emoji's.

= 1.4.0 =

* 06/04/2019

* New: Added ability for group moderators and admin to delete chat messages.

= 1.3.1 =

* 05/04/2019

* Fix: Activity updates now have a descriptive title
* Fix: Videos now display correctly in the activity feed.

= 1.3.0 =

* 05/04/2019

* New: Added support for embeded videos and other embeddable content.
* New: Updated New message update routines to prevent constant content loading.

= 1.2.2 =

* 05/04/2019

* Fix: Corrected function no found error when friendships are not active.
* Fix: Further translation updates.
* Fix: Corrected invalid argument passed to foreach error.

= 1.2.1 =

* 05/04/2019

* Fix: Revised text for translations.

= 1.2.0 =

* 05/04/2019

* New: Added Mentions support with a twist - mentioned users will get an email pointed to the chatroom. Notifications however will only be sent if the option of posting threads to activity is enabled.
* Fix: Added Text Domain.
* Fix: Made "Say" translatable.

= 1.1.0 =

* 01/04/2019

* New: Introduced rudimentary threads, thread timeout is 15 mins.
* New: Introduced posting of completed threads to activity.
* New: Messages now scroll from the bottom.
* New: Refactored message structure in anticipation of more complex actions.
* New: Refactored settings into array to cope with future growth.
* New: Added option to delete messages after thirty days.
* Fix: Updated message delete after 30 days.
* Fix: Corrected error causing Chat to be displayed in groups where it is not enabled.
* Fix: Corrected date to local server date rather than UTC.

= 1.0.0 =

* 27/03/2019

* Initial release.
== screenshots ==

* screenshot-1.jpg - LiveChat in the group.