=== WebFacing™ - Email Accounts management for cPanel® ===
Contributors: knutsp, proisp
Donate link: https://paypal.me/knutsp
Tags: cpanel, email, auto-reply, backup, membership
Requires at least: 6.3
Tested up to: 6.4.1
Requires PHP: 7.4
Tested up to PHP: 8.2
Stable tag: 5.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

WebFacing™ - Email Accounts management for cPanel® 

== Description ==

🕸️ By [WebFacing™](https://webfacing.eu/). Read, send, show, manage, list, create, add, remove or delete email accounts, old messages, forwarders and autoresponders.
One click read, send and manage all your emails without a login step. Update notification recipients. Backup and download your complete cPanel® account.

This plugin requires your site is hosted on a cPanel® equipped server.

It uses it's UAPI through shell access by default, or via HTTP API. Using without `shell_exec` initially requires a temporary access token generated in the cPanel® native interface.

[![WP compatibility](https://plugintests.com/plugins/wporg/wf-cpanel-email-accounts/wp-badge.svg)](https://plugintests.com/plugins/wporg/wf-cpanel-email-accounts/latest)
[![PHP compatibility](https://plugintests.com/plugins/wporg/wf-cpanel-email-accounts/php-badge.svg)](https://plugintests.com/plugins/wporg/wf-cpanel-email-accounts/latest)

=== Translation ready. Ready translations are ===

* Norwegian (bokmål)

=== Current features ===

* NEW: WP Block for frontend access user's own Webmail
* Bulk entry of from dependent forwarders and email accounts (trial only, limited use)
* Screen Options to select visible forms on New Email screen, saved for as user option (and per site for Mulitsite)
* Search for email
* Duplicate check for New Account Form
* Shortcode `[wf_cpanel_email_webmail]` or as `[wf_cpanel_email_webmail]`My Email`[/wf_cpanel_email_webmail]` for frontend access user's own Webmail
* API Tokens Management
* No need to have `shell_exec` enabled in PHP, see below
* Backup account to file, and download
* Restore account backup files by extracting it to a folder (experimental)
* Accounts listing per domain managed via cPanel®
* Completely automated migration to new email server, like setup of new accounts for users, with sending setup instructions, passwords and links to setup guides for most common email clients
* Add 10 single Email Accounts per week (without Pro nag) -- else unlimited
* Remove single/multiple Email Accounts
* List mailboxes with number of messages for each account
* Remove old messages from mailboxes (older than 52 weeks implied, currently)
* Shrink a mailbox (empty it)
* Change storage quota for for email accounts
* Add 20 forwarders per week (without Pro nag) -- else unlimited
* Add/remove single/multiple Email Failure addresses or Blackhole addresses
* Add/delete/edit email autoresponders (for, subject, body, from, start, stop, interval)
* Send single cPanel® Email Account Instruction (Client Setup) to specfied address
* Send multipe cPanel® Email Account Instructions (Client Setups) to yourself for distribution
* Open your cPanel® Webmail app for selected account (single click/tap - no further login needed!)
* Set/change Email Account passwords
* Set Default Email Address (catch-all) as forwarder, failure or blackhole
* View/change Notification/Contact Email Addresses
* Check auto detect and actual email routing in a Site Health tab (any email address, as sent from this server)
* Access for any user to view and read their own emails, if given the <code>cpanel</code> capability
* Support for subdomain email addresses when main domain is a subdomain
* Option to set the default visibility for users on New Email screen (users may still set their own preferences):
  - <code>add_filter( 'wf-cpanel-email-new-email_user-option', static function( bool $default, string $option, int $user_id ) {
	if ( $option === 'wc-show-new-blackhole' /*or by $user_id*/ ) {
		$default = true/*false*/;
	}
	return $default;
}, 10, 2 );</code>
* Option to allow other users than those with <code>manage_options</code> capability to manage email adresses, single- or multisite, one of the following:
  - <code>add_filter( 'wf_cpanel_email_capability', static fn( string $cap ) => $my_cpanel_email_cap );</code>
  - <code>add_filter( 'wf_cpanel_email_capability', static fn( string $cap ) => 'edit_published_pages' );</code>
* Option to limit email addresses to current site domain, even for single site admins, <em>one</em> of the following
  - <code>const WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY = true;</code>
  - <code>define( 'WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY', true );</code>
  - <code>add_filter( 'wf_cpanel_email_site_domain_only', '__return_true' );</code>
  - <code>add_filter( 'wf_cpanel_email_site_domain_only', fn() => true );</code>
* Multisite Network: Option not to limit email addresses to current subsite domain, for site admins that are not network (super) admins, one of the following:
  - <code>const WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY = false;</code>
  - <code>define( 'WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY', false );</code>
  - <code>add_filter( 'wf_cpanel_email_site_domain_only', '__return_false' );</code>
  - <code>add_filter( 'wf_cpanel_email_site_domain_only', fn() => false );</code>
* Many optional parameters and API filters for the shortcode output, see `includes/ShortCode.php` until further tested and documented (coming soon)
* If the `shell_exec` function is disabled in your server PHP configuration, add <em>one</em> of these lines to your `wp-config.php` file, child theme `functions.php` or in Must-use plugin
  - <code>const WF_CPANEL_API_TOKEN = 'paste-your-api-token';</code>
  - <code>define( 'WF_CPANEL_API_TOKEN', 'paste-your-api-token' );</code>
  - See [Manage API Tokens in cPanel®](https://docs.cpanel.net/cpanel/security/manage-api-tokens-in-cpanel/)
  - The `WF_CPANEL_API_TOKEN`may be removed when a new token is created and activated from the admin page
 * If you want to access another user on the server, use one of the following
  - <code>const WF_CPANEL_USER = '%username%';</code>
  - <code>define( 'WF_CPANEL_USER', '%username%' );</code>
 * If you want to access a remote server, use one of the following
  - <code>const WF_CPANEL_HOST = '%host%';</code>
  - <code>define( 'WF_CPANEL_HOST', '%host% );</code>
  - using `WF_CPANEL_HOST`requires `WF_CPANEL_USER` to also be defined
 * Site Health tests and information

=== Possible future features ===

* Scheduled automatic removal of old messages in/from mailboxes
* Import migration list for create account, send instructions and password to current address
* Delete selected email messages from mailboxes (by selectd message age etc.)
* Suspend/unsuspend incoming/outgoing for email accounts (maybe)
* Suspend/unsuspend login to email accounts (if requested)
* Domain Managament
* Domain level email forwarding

=== Limitations, security, privacy - be warned ===

* Maximum New Forwarders = 20, Maximum New Accounts = 5, weekly reset
* Trial: Maximum New Forwarders as **bulk entry** = 4, Maximum New Accounts as **bulk entry** = 2
* <ins>Now works without shell access!</ins> <del>Will not work at all if `shell_exec` is disabled in `php.ini`</del>
* Works only for admins, or other users with a custom `cpanel` capability and email on site domain (so far)
* Any admin (if multisite, only network admins), or user with the filtered capability, on a site, can fully access <em>any</em> account on the cPanel® server instance
* No AYS warnings for delete actions
* If several sites/admins share the same cPanel® account, without being part of a WP Multisite network, no bulletproof separation, because of the way the cPanel® UAPI CLI works, with or without this plugin

== Pro Addon ==

- Pro Addon plugin will be released May 1, 2023 at https::/webfacing.eu/ and will unlock **in bulk entry** new forwarders and new accounts to the numbers in the purchesed license


== Frequently Asked Questions ==

= Does this (free) plugin limit the it's use in any way? =

- Yes, **in bulk entry** of new forwaders and new accounts is considered a just trial for the Pro Edition, not included or supported in free plugin after being used once

- No, in spite the that it will ask you, in a standard, dismissable admin notice, to consider the Pro addon after 20 new forwarders, or 10 new accounts, per week, it doesn't stop you from continuing to add more

= The latest version has a regression and don't work as well as it did in previous version, but other things are fixed and I like the new features, what can I do? =

Please, immediately download and reinstall the **previous version** from the Advanced tab here, observe that the new issues are no longer present, then post in the support forum here.

= Does this plugin add database tables, store many options, crons/scheduled tasks, custom post type content or lines to `wp-config.php` or write to any existing file? =

No, no, no, no, no. It stores transients, and, in case they are stored in the database (no persistent object cache), automatically deletes the expired ones from the `options` table. Account backup files are stored.

= Does it require my login information to cPanel® or store any account passwords? =

No. Nope. You may manually enter an API token in `wp-config.php` in case the `shell_exec` function is disabled.

= Can it be used to serve my users or members on a frontend page? =

Yes. Use the shortcode [wf_cpanel_email_webmail]. Many parametres for users, many filters the defaults, for developers.
See `includes/ShortCode.php´ for details.

= Does it work with WP Multisite Network? =

Yes. Subsite admin access is then limited by default (filterable option). Unreliable site admin user separation may occur.

= Can it communicate with other cPanel® instances that the one my site usues?

Yes. You need the constants (need to define in ´wp-config.php´ or in another plugin) <code>WF_CPANEL_HOST</code>,
<code>WF_CPANEL_USER</code> and (temporarily) <code>WF_CPANEL_API_TOKEN</code>

= Does it work without being on cPanel®? =

No.

= Can I contribute to this plugin? =

Use support forum for feedback, reports and suggestions.

= Can I contact you by email for support, maybe with screen shots, or ask for new features that I need? =

No, use the support forum here if not using the Pro Addon.

= Can I contact you by email to suggest a new feature that I believe to be useful and have a use case for, as help?

Yes, use support@webfacing.eu

= Can I donate to the continued maintenance and further development of this plugin? =

 - Report bugs or suggest enhancements or new features in the Support forum.
 - **Use the Donate button on the right sidebar on this page.**

== Screenshots ==

1. Accounts Overview in admin
2. Add forwarders (aliases), failures or full accounts
3. Email sendt to existing email upon new account creation (optional)
4. Your page button example using Webmail block or shortcode `[wf_cpanel_email_webmail]` (text and styling friendly, filterable CSS classes)
5. Batch Entry

== Changelog ==

= 5.2.6 =

- Nov 14, 2023
- Fix fatal error: Uncaught TypeError in UAPI-php.
- Fix warning: Attempt to read property "type" on null in UAPI.php.

= 5.2.5 =

- Aug 12, 2023
- Fix fatal error: DivisionByZeroError in Site Health Status Disk Space test. Thanks @cbonallo for reporting.

= 5.2.4 =

- Jul 27, 2023
- Fix: Handle warning when `shell_exec` against uapi fails
- Better handling of missing expected properties in returned objects from uapi

= 5.2.3 =

- Jul 21, 2023
- Fewer calls to cPanel when in ajax or in cron

= 5.2.2 =

- Jul 10, 2023
- Show Webmail block as button in editor
- Translations for Webmail block

= 5.2.1 =

- Jul 9, 2023
- Added Gutenberg block for frontend access to the user's own Webmail account

= 5.2 =

- Jul 9, 2023
- Added Gutenberg block for frontend access to the user's own Webmail account

= 5.1.1 =

- Allow "." (dot) in email local

= 5.1 =

- Usage registration redesign
- Ready for Pro Addon

= 5.0.3 =

- Fix: Namespace the global 'PLUGIN_BASENAME'
- More guides for email setup change (Norwegian only)

= 5.0.2 =

- Fix: Fatal error on New Email for new users

= 5.0 =

- Apr 26, 2023
- Prepared for upcoming Pro Addon with embedded license
- Usage registering, Free/Pro separate
- License handling, Free/Pro separate
- REST API lookup for license validation (Pro only)
- License exceedd handling and nag
- Site Health Info tab with usage and license
- More Site Health Info about cPanel® Email server, storage and available Webmail apps
- Single entry of new forwarders or accounts limited to 20 forwarders and 10 accounts, but usage count will be reset after about one week.
- The new bulk entry trial feature for new forwarders and accounts, limited to 4 forwarders and 2 accounts.
- Prepared for upcoming Pro addon
- New feature: Bulk entry of new forwarders and accounts, limited to 4 forwarders or 2 accounts

= 4.9.6 =

* Apr 12, 2023
* Fix a stubborn transient deletion bug causing delay in listing new email accounts/addresses
* Display error messages from UAPI on delete
* Some help text pointing to Screen Options on New Email screen
* Fix: PHP `Undfined array key`

= 4.9 =

* Email Addresses Table: Added Search box
* New Email Screen: Added Screen Options to reduce the number of forms shown by default
* New Email Account Form: Warning if duplicate (already exists)

= 4.8.6 =

* Bugfix: Set `$response_message` property in all cases

= 4.8.5 =

* Bugfix: Set `$cpanel_user` property earlier

= 4.8.4 =

* Mar 21, 2023
* Privacy: Do dot show Webmail to administrators in case email is held by a user
* Performance: Save token validation in transient for 1 minute
* Proisp: Webmail goes directly to Webmail server

= 4.8.3 =

* Bugfix saving new token as known

= 4.8.1 =

* Better transient names to cope with different cPanel® users and hosts

= 4.8 =

* Mar 20, 2023
* Fixed: Bulk sending of email client setup instructions, nonce error
* Added support for `WF_CPANEL_HOST` and `WF_CPANEL_USER` constants in `wp-config.php`
* Special for Proisp, special (Norwegian) instructions are sent for email client setup

= 4.7.5 =

* Mar 18, 2023
* Adjustment: Let Site Health test failure for missing cPanel® Contact Info be recommended, not critical

= 4.7.4 =

* Feb 9, 2023
* Bug: Use correct method `get_attributes` on `Locale` module
* Bug: Division by zero in Site Health Info tab, Cache %

= 4.7 =

* Feb 1, 2023
* Do not set language on ajax requests, avoid updating cPanel® account
* Fixed: Tracing fatal error
* A few more Site Health Info items, ported from my other plugin (retrired)

= 4.5 =

* Nov 7, 2022
* Added shortcode for frontend Webmail
* Fix for admin pages content disappearing in certain situations (incorrect html)
* Added storage space test in Site Health Status
* More descriptive error messages

= 4.4.18 =

* Nov 3, 2022
* Site Health Info: Do not show auto update on multisite
* Better detect that `uapi` shell command returns other than null before using
* On Multisite, Site Health Info the "Directories and Sizes" panel must have a title
* Enhancement: Respect `WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY` in Site Health Info
* Enhancement: Respect `WF_CPANEL_EMAIL_SITE_DOMAIN_ONLY` for Backups and Tokens pages
* Bugfix: Correct color for expired column
* Enhancement: Formatting of admin footer text
* Bugfix: Keep active token when renaming
* Bugfix: Only show constant when exists
* Bugfix: Use correct santitization for new token name
* Bugfix: Delete transients on delete token
* Bugfix: Use correct santitization for tokens

= 4.4 =

* Added: Token Management page and table
* Add new, delete, rename and activate stored tokens
* Bugfix: Site Health Info: Email domains not shown

= 4.3.2 =

* Oct 24, 2022
* Further strengthen security with backups, ensure no indexing of backups folder
* Backups download file names stripped for obscurity string
* Set cPanel® language on start
* Site Health test for two factor cPanel® account login

= 4.3.1 =

* Oct 21, 2022
* Important security release, fixes:
  - Puts empty `index.php` to backup destination folder to avoid directory listing on badly configured servers
* Added storage space item to Dashboard - At a Glance widget
* Added storage space and account limit to - Site Health Info - Directories and sizes
* Removed remote test when API token is used

= 4.2 =

- New feature: Site Health tests for contact information email domains and notifiactions configuration
- More Site Health Info tab information, like maximum number of emails per hour

= 4.1.5 =

- Oct 14, 2022
- Regression: Better protection against fatal error in case `uapi` command fails
- Bugfix: Backup file copied to download dir before finished. Thanks to @archonic08
- Bugfix: Always fresh files from home dir (not cached).
- Bugfix: Correct time for backup finished
- Enhancement: Show processing backups
- Enhancement: Experimental support for filesystem credentials
- Bugfix: Allow email local parts to contain dash ("-"). Thanks to @alanb2718

= 4.1 =

- Oct 10, 2022
- Edit contact/notification email addresses/recipients for cPanel® account
- Better protection against fatal error in case `uapi` command fails
- Cache stats in Site Health Info

= 4.0 =

- Oct 03, 2022
- New HTTP based API, no need for `shell_exec`
- Added cPanel® Account Backup, Create new and List
- Email notification when finished to user on new account backup creation
- Account backup files moved to `wp-content/cpanel` for download and delete
- Download Backup and Delete Backup as row actions
- Delete Backups as bulk action
- `wp-config.php` support for
	- `const WF_CPANEL_API_TOKEN = 'your-apitoken';`

= 3.4 =

- Added check for auto detection of domain email delivery as local or remote in Site Health tab Email Routing, with Fix button
- Added check for auto detection of all domain email delivery as local or remote in Site Health tab Info
- Link to Add New Email in toolbar Add new
- Text changes on New Email page

= 3.3 =

- Sep 4, 2022
- New Site Health tab for cheking email routing from this cPanel® server
- Email routing shown on Mailboxes page

= 3.2 =

- Jul 18, 2022
- Requires PHP 7.4+
- Tested up to PHP 8.2
- Bugfix: Fatal error when deleting account, use correct translation function.

= 3.1 =

- May 12, 2022
- Bugfix: When the capability is filtered, all actions are now allowed, except deleting others accounts
- New feature: When creating a new account, optionally send instructions and password with setup guide links
- Site Health Status: Test for recommended plugin
- Site Health Status: Test for MX pointer
- Site Health Info: Show an extra constant and cPanel® Email info (main domain, MX-pointer)
- Safer with PHP <code>strict_types</code>
- Last release running on PHP version 7.3, plugin version 3.2 will require PHP version 7.4
- Please upgrade your PHP to at least version 7.4 to continue receiveing further fixes and updates

= 3.0 =

- Apr 2, 2022
- Enhancements: Default address now has Delete and Edit row actions in Accounts Table
- Enhancement: New UI layout and textual responder name with suggested options for Autoresponder in Add New Account form
- Coding Standards and refactoring

= 2.9 =

- Mar 08, 2022
- New feature: Send account setup instructions to any email address, your own just as the default
- Fix: Better handling (no action) when setting email account qouta or password javascript prompt is cancelled
- Enhancement: Plugin promotion at bottom of Dashboard widget can now be dismissed or removed

= 2.8 =

- Send account setup instructions to any email address, your own as default
- Fix: Better handling (no action) when setting email account qouta or password javascript prompt is cancelled

= 2.7 =

- Added config option/filter to limit email domains to current site domain, for single site admins (thanks to @manuelseffe for suggesting and testing)
- Added config option/filter not to limit email domains to current subsite domain, for site admins in network
- Coding standard fixes

= 2.6 =

- WP 5.9 tested
- Fixed: Webmail button not always working
- Fixed: Bulk actions not always working
- Better translation of bulk action results
- Internal: Namespace changes

= 2.5 =

- Fixed fatal error on New Email screen
- Code cleanup
- Translations simplifiaction
- Shorter transient (cache) times, better invalidation

= 2.4 =

- Security release: On multisite, check that the submitted email domain is legal when creating new email addresses/forwarders
- Tighter multisite filtering of domains and email accounts
- Removed email address select step (dropdown) on Mailboxes page when there is only one email account available

= 2.3 =

- Multisite support: Limit email domains to current host for all users except superadmins

= 2.2 =

- Make sure no errors from gethostbyaddr() when no "SERVER_ADDR" (cron, CLI)

= 2.1 =

* Support for email addresses on (all) subdomains of main account is a subdomain

= 2.0 =

* Recommending my other plugin 'WebFacing™ – cPanel® Storage, resource usage and errors'
* Translate email types
* Bugfix: IDN display for Postboxes page
* Translate postbox names
* Row action: Show mailboxes
* Row action: Delete messages
* Contact email editing (not working)
* Remove redundant email column for mailboxes table
* Minor text changes

= 1.9 = 

* Show mailboxes for all accounts
* Remove old messages per mailbox

= 1.8 =

* Add action to change email storage quota
* Add action to edit a current autoresponder

= 1.7 =

* Bugfix: Correctly account for timezone when adding new autoresponders start/end times
* Limit from email for new autoresponders (select)
* New Autoresponder start/end input as date and time separated (a better user interface)
* Allow limited access for other users than administrators, given a capability (<code>cpanel</code>) and having a user email under the site domain

= 1.6 =

* Correct placeholder for start/stop dates
* Icon placement fix
* Add forwarder/default destination email icon
* Text/translation fix for "From email"
* Add/delete email autoresponders

= 1.5 =

* Revamped Add Email screen with selects for domains
* Add Blackhole for email fowarding and default adresses
* Support for International Domain Names (IDN)
* Disable Webmail button on click and after 10 minutes
* Added some icons on screens
* Better cache invalidation
* Each Webmail button open their links in different tabs

= 1.4 =

* Display and add default email destination for each domain
* Faster, better perfomance, with caching using transients (timeout 10 minutes)
* Timeout for Webmail buttons, refresh needed (10 minutes)
* Bug fixes and code cleanup

= 1.3 =

* Change password for email accounts
* View contact/notifcation emails
* Bugfix: Account passwords work for new accounts
* Bugfix: Proper label for new account input
* Bugfix: Removed dupliacte html ids

= 1.2 =

* Better cPanel® detection and feature check
* Remote DNS MX server detection for domains, as these will not receive remote emails
* Auto login to Webmail for accounts (button)

= 1.1 =

* Dashboard  - At a Glance: Number of Email accounts
* More translated strings
* Filter ´removable_query_args´ only for list table page
* Text changes and corrections

= 1.0 =

* Initial release, Apr 21, 2021.
