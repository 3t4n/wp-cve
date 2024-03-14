=== GD Topic Polls: plugin for WordPress and bbPress Forums ===
Contributors: GDragoN
Donate link: https://plugins.dev4press.com/gd-topic-polls/
Tags: dev4press, forum polls, forum, topic poll, bbpress poll
Stable tag: 2.3
Requires at least: 5.7
Tested up to: 6.4
Requires PHP: 7.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Implement polls system for bbPress powered forums, for users to add polls to topics, with settings to control voting, poll closing, display of results...

== Description ==
GD Topic Polls is a plugin for WordPress and bbPress, and it works with bbPress topics. Users can create polls for new topics, or add a poll for existing topics. Each topic can have one poll.

= Overview of plugin features =
GD Topic Polls is easy to use, it doesn't require any coding, but it offers a lot of customization potential to developers.

Here is the list of most important plugin features:

* Create polls with new topics
* Add polls to the existing topics
* Edit poll while editing topic
* Control user roles allowed creating new polls
* Disable polls for selected forums
* Optional description field in the poll
* Add two or more answers to the poll
* Reorder poll answers in the poll edit mode
* Set poll to allow one answer only for each voter
* Set poll to allow unlimited answers for each voter
* Set poll to limit the number of answers for each voter
* Auto close the poll when topic is closed
* Control when the poll results can be displayed

And on the administration side, the plugin has many more useful features:

* Panel with the list of all polls
* List of polls panel: disable or enable any poll
* List of polls panel: delete any poll
* List of polls panel: remove all votes from the poll
* Panel with the list of votes
* List of votes panel: filter by poll, user or answer
* List of votes panel: delete votes
* Panel with the plugin settings
* Panel with the import, export and removal tools

Developers can customize the plugin look and feel by changing the templates or styling. Everything plugin displays in the front end are rendered in form of template, and each template can be overridden through the theme. And several functions can be also overridden via theme functions.php file to allow for more customizations.

= Upgrade to GD Topic Polls Pro =
Pro version contains many more great features:

* Widget to show list of polls with forums filtering
* Auto close the poll: when selected date is reached
* Auto close the poll: when number of voters is reached
* Require users to respond to topic before answering poll
* Option to allow users to remove and change their vote
* Display list of voters in the poll results
* Settings to control default values of some poll options
* Settings to control display of voters list in the poll results
* Instant votes Notifications
* Daily Digest votes Notifications
* bbPress Topics View: All topics with polls
* bbPress Topics View: Logged-in user topics with polls
* Integration with BuddyPress activity stream

With more features on the roadmap exclusively for Pro version.

* More information about [GD Topic Polls Pro](https://plugins.dev4press.com/gd-topic-polls/)
* Compare [Free vs. Pro Plugin](https://plugins.dev4press.com/gd-topic-polls/free-vs-pro-plugin/)
* More premium [bbPress Plugins](https://bbpress.dev4press.com/)

= More free dev4Press.com plugins for bbPress =
* [GD Forum Manager](https://wordpress.org/plugins/gd-forum-manager-for-bbpress/) - quick and bulk forums and topics edit
* [GD Members Directory](https://wordpress.org/plugins/gd-members-directory-for-bbpress/) - add new page with list of all forum members
* [GD Power Search](https://wordpress.org/plugins/gd-power-search-for-bbpress/) - add advanced search to the bbPress topics
* [GD bbPress Attachments](https://wordpress.org/plugins/gd-bbpress-attachments/) - attachments for topics and replies
* [GD bbPress Tools](https://wordpress.org/plugins/gd-bbpress-tools/) - various expansion tools for forums

= Documentation and Support =
You need to register for free account on [Dev4Press](https://www.dev4press.com/):

* [Frequently Asked Questions](https://support.dev4press.com/kb/product/gd-topic-polls/faqs/)
* [Knowledge Base Articles](https://support.dev4press.com/kb/product/gd-topic-polls/articles/)
* Support Forum: [Free](https://support.dev4press.com/forums/forum/plugins-lite/gd-topic-polls/) & [Pro](https://support.dev4press.com/forums/forum/plugins/gd-topic-polls/)

== Installation ==
= General Requirements =
* PHP: 7.3 or newer
* bbPress 2.6.2 or newer
* WordPress: 5.7 or newer

= PHP Notice =
* The plugin doesn't work with PHP 7.2 or older versions.

= WordPress Notice =
* The plugin doesn't work with WordPress 5.6 or older versions.

= Basic Installation =
* Plugin folder in the WordPress plugins folder must be `gd-topic-polls`.
* Upload `gd-topic-polls` folder to the `/wp-content/plugins/` directory
* Activate the plugin through the 'Plugins' menu in WordPress
* Check all the plugin settings before using the plugin.

== Frequently Asked Questions ==
= Does plugin works with WordPress MultiSite installations? =
Yes. Each website in the network can activate and use the plugin on it's on.

= Can I translate plugin to my language? =
Yes. POT file is provided as a base for translation. Translation files should go into Languages directory.

== Translations ==
* English

== Upgrade Notice ==
= 2.3 =
Various updates and improvements.

= 2.2 =
Various updates and improvements. Bug fixes.

== Changelog ==
= 2.3 - 2023.11.01. =
* New: updated plugin system requirements
* New: tested with WordPress 6.3
* Edit: many small tweaks and improvements to the plugin core
* Edit: changes to the interface for new library
* Edit: d4pLib 4.3.4

= 2.2 - 2023.05.15. =
* New: polls admin table: expanded with the Status column
* New: plugin icon for menu replaced with the latest version
* Edit: d4pLib 4.1
* Fix: polls admin table: polls per page option not working
* Fix: votes admin table: votes per page option not working
* Fix: few issues with saving of the poll settings

= 2.1 - 2023.03.08. =
* New: system requirements: now requires PHP 7.3 or newer
* New: system requirements: now requires WordPress 5.5 or newer
* New: fully tested with PHP 8.0, 8.1 and 8.2
* New: updated the plugin admin interface
* New: updated table based panels and base classes
* New: replaced select rendering in templates with library function
* New: reorganized dashboard interface files
* Edit: various styling improvements for notices
* Edit: d4pLib 3.9.3
* Fix: poll editing shows some settings with invalid values
* Fix: some small front end styling issues
* Fix: several issues related to PHP 8.x versions

= 2.0.1 - 2023.01.02. =
* Fix: several issues with file names on some server configurations

= 2.0 - 2022.05.16. =
* New: updated plugin system requirements
* New: rebuilt from the latest version of the Pro edition
* New: admin interface completely replaced and enhanced
* New: options to control the display of the poll results
* New: support for the Quantum templates package
* Edit: improved default styling for the Default templates package
* Edit: d4pLib 3.8
* Fix: several small issues with main Poll class

= 1.6 - 2021.10.05. =
* New: system requirements: now requires WordPress 5.1 or newer
* New: user poll creation check: added option to change check method
* New: user poll creation check: added filter for additional control
* Fix: check if user can create poll on topic save or edit

= 1.5 - 2021.02.13. =
* New: system requirements: now requires PHP 7.0 or newer
* New: system requirements: now requires WordPress 5.0 or newer
* New: system requirements: now requires bbPress 2.6.2 or newer
* Edit: d4pLib 2.8.13

= 1.4 - 2019.06.05. =
* New: filter for poll icon displayed in the topics lists
* Edit: few minor styling updates and improvements
* Fix: icon for the topics is missing due to the bug in GD bbPress Toolbox Pro
* Fix: regression issue with the loading of admin side JavaScript

= 1.3.2 - 2019.01.02. =
* Edit: few minor updates to the admin panels
* Edit: d4pLib 2.5.2

= 1.3.1 - 2018.02.22. =
* Fix: wrong method call on the admin side polls list
* Fix: wrong method call on the admin side votes list

= 1.3 - 2018.01.11. =
* New: action fired when the poll is saved
* New: actions fired when the poll vote is saved or removed
* Edit: d4pLib 2.2.4
* Fix: admin side attempt to load missing JavaScript file
* Fix: XSS vulnerability: query string panel was not sanitized
* Fix: XSS vulnerability: panel variable for some pages was not verified

= 1.2 - 2017.10.12. =
* Edit: minor updates to the plugin readme file
* Edit: several settings related updates and changes
* Edit: d4pLib 2.1.2
* Fix: missing several translation strings

= 1.1 - 2017.08.05. =
* New: core poll object: use the static cache for polls
* New: core poll object: additional public methods
* New: show back to voting action when viewing the results
* Edit: hide view results button if poll in results mode

= 1.0 - 2017.08.01. =
* First official release

== Screenshots ==
1. Example poll results
2. Example poll open for voting
3. Create topic poll form
4. Admin side list of polls
5. Admin side list of poll votes
6. Example poll results
