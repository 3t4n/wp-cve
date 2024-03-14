=== Email Subscribers - Group Selector ===
Contributors: icegram, Mansi Shah, storeapps
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=CPTHCDC382KVA
Author URI: https://www.icegram.com/
Tags: email marketing, email newsletter form, group selection, email signup, email widget, newsletter, subscribe, subscription form, bulk emails, signup form, list builder, lead generation
Requires at least: 3.9
Tested up to: 4.9.6
Stable tag: 1.5.1
License: GPLv3

Add-on for Email Subscribers plugin using which you can provide option to your users to select interested groups in the Subscribe Form.
 
== Description ==
*This plugin is now merged within the latest Email Subscribers 4.0*
*Download it from here [Email Subscribers 4.0](https://wordpress.org/plugins/email-subscribers/)*

It is an add-on for Email Subscribers plugin which extends Subscribe Form functionality. You can provide option to your users to select interested group in the Subscribe Form during sign up. It will create separate menu called Group Selector within Email Subscribers plugin menu. There you will have option to control form fields. Both widget and shortcode options are available. Also, please note, that this plugin works only if you have active Email Subscribers plugin activated.


### Plugin Features

*   Option to display or hide subscribers form Name field.
*   Option to display or hide subscribers form Email field.
*   Option to display or hide subscribers form Interested Group field.
*   Option to set mandatory field for Name, Email and Interested Group.

### Plugin configuration

*   **Shortcode for any posts or pages**

` [email-subscribers-advanced-form id="1"] `

*   **Widget option**

Go to Dashboard -> Appearance -> Widgets. Drag and drop the Email Subscribers - Group Selector widget to your sidebar location.

*   **Add directly in the theme**

Add following line of PHP code directly in your theme :
` es_af_subbox( $id = "1" ); `

**Translations on [translate.wordpress.org](https://translate.wordpress.org/)**

* Dutch Nederlands - [mesan00](https://profiles.wordpress.org/mesan00) ([nl_NL](https://translate.wordpress.org/locale/nl/default/wp-plugins/email-subscribers-advanced-form))

== Installation ==

Option 1:

1. Make sure you have installed & activated Email Subscribers before installing Email Subscribers - Group Selector
2. Go to WordPress Dashboard -> Plugins-> Add New
2. Search Email Subscribers - Group Selector plugin using search option
3. Find the plugin and click Install Now button
4. After installtion, click on Activate Plugin link to activate the plugin.

Option 2:

1. Make sure you have installed & activated Email Subscribers before installing Email Subscribers - Group Selector
2. Download the plugin email-subscribers-advanced-form.zip
2. Unpack the email-subscribers-advanced-form.zip file and extract the email-subscribers-advanced-form folder
3. Upload the plugin folder to your /wp-content/plugins/ directory
4. Go to WordPress dashboard, click on Plugins from the menu
5. Locate the Email Subscribers - Group Selector plugin and click on Activate link to activate the plugin.

Option 3:

1. Make sure you have installed & activated Email Subscribers before installing Email Subscribers - Group Selector
2. Download the plugin email-subscribers-advanced-form.zip
2. Go to WordPress Dashboard->Plugins->Add New
3. Click on Upload Plugin link from top
4. Upload the downloaded email-subscribers-advanced-form.zip file and click on Install Now
5. After installtion, click on Activate Plugin link to activate the plugin.

== Frequently Asked Questions ==

= 1. How to create Subscribers Form? =

Go to WordPress Admin Dashboard -> Email Subscribers -> Group Selector
There your have option to create and update Subscriber Form details. Also, in the same page you can find shortcode details

= 2. Is widget available for this plugin? =

Yes, Widget option is available for this plugin. Please go to dashboard widget menu to find more details.

== Screenshots ==

1. Front Page. Subscription form.

2. Admin Page. Group Selector.

== Changelog ==

= 1.5.1 (18.06.2018) =

* Update: Use [input type=email] in the subscribe form
* Update: Add form class in the subscribe form

= 1.5.0 (17.05.2018) =

* New: [GDPR] Provision for consent checkbox in the subscribe form [Steps to enable it](https://www.icegram.com/documentation/esaf-gdpr-how-to-enable-consent-checkbox-in-the-subscription-form/)
* Fix: Undefined variable: es_af_txt_nm
* Update: POT file

= 1.4.3 (03.04.2018) =

* New: Compatible with the latest Email Subscribers (v3.4.10+)

= 1.4.2 (18.10.2017) =

* Fix: Compatibility with Email Subscribers v3.4.0+

= 1.4.1 (19.06.2017) =

* New: Admin can now include user subscribed group name in the Admin Email (Email Subscribers v3.3.1+)
* Update: POT file
* Tweak: Table creation on plugin activation

= 1.4 (13.06.2017) =

* New: Compatible with Email Subscribers 3.3
* Update: POT file

= 1.3.5 (01.06.2017) =

* Fix: Unable to delete the form

= 1.3.4 (11.05.2017) =

* New: Admin can now include user subscribed group name in the Welcome Email (Email Subscribers v3.2.10+)
* New: Improvements in Admin screen of Group Selector
* Fix: Form validation messages were not getting translated
* Fix: Missing text domain at few places
* Update: Code improvements when using $wpdb->prefix for queries
* Update: POT file
* Update: Re-structure files & folder inside plugin
* Tweak: Do not allow special characters in the group name while creating a form

= 1.3.3 (10.03.2017) =

* Fix: Duplicate _wpnonce in the subscribe form
* Fix: wp_enqueue_style was adding extra slash(/) resulting in failing of enqueuing the style.css file
* Fix: CSS issues on Edit subscribe form screen
* Fix: Made few strings translatable
* Update: POT file

= 1.3.2 (02.03.2017) =

* New: WordPress 4.7.2 compatible
* Fix: Multiple confirmation emails were sent when a subscriber subscribed to more than one group
* Fix: Made few strings translatable
* Update: Renamed admin menu from 'Advanced Form' to 'Group Selector'
* Update: UI improvements
* Update: Text correction in few places
* Update: 5 star rating link
* Update: POT file
* Tweak: Disable selecting 'Display EMAIL field?' & 'Make EMAIL field Mandatory?' fields while creating and editing forms
* Tweak: Do not show form Database id of form on Group Selector screen

= 1.3.1 (27.10.2016) =

* New: New contributor name has been added
* Update: POT file

= 1.3 (17.02.2016) =

* New: Scripts are now localized and can be translated
* Fix: Double menu of Email Subscribers in WordPress admin upon activating the plugin
* Fix: Incorrect text domain for few texts
* Update: Added text domain for missing texts
* Update: Added POT file

= 1.2.1 (18.12.2015) =

* New contributor has been added successfully

= Earlier Versions =

For the changelog of earlier versions, please refer to the separate [changelog.txt](https://plugins.svn.wordpress.org/email-subscribers-advanced-form/trunk/changelog.txt) file

== Upgrade Notice ==

= 1.5.1 (18.06.2018) =

* Update: Use [input type=email] in the subscribe form
* Update: Add form class in the subscribe form

= 1.5.0 (17.05.2018) =

* New: [GDPR] Provision for consent checkbox in the subscribe form [Steps to enable it](https://www.icegram.com/documentation/esaf-gdpr-how-to-enable-consent-checkbox-in-the-subscription-form/)
* Fix: Undefined variable: es_af_txt_nm
* Update: POT file

= 1.4.3 (03.04.2018) =

* New: Compatible with the latest Email Subscribers (v3.4.10+)

= 1.4.2 (18.10.2017) =

* Fix: Compatibility with Email Subscribers v3.4.0+

= 1.4.1 (19.06.2017) =

* New: Admin can now include user subscribed group name in the Admin Email (Email Subscribers v3.3.1+)
* Update: POT file
* Tweak: Table creation on plugin activation

= 1.4 (13.06.2017) =

* New: Compatible with Email Subscribers 3.3
* Update: POT file

= 1.3.5 (01.06.2017) =

* Fix: Unable to delete the form

= 1.3.4 (11.05.2017) =

* New: Admin can now include user subscribed group name in the Welcome Email (Email Subscribers v3.2.10+)
* New: Improvements in Admin screen of Group Selector
* Fix: Form validation messages were not getting translated
* Fix: Missing text domain at few places
* Update: Code improvements when using $wpdb->prefix for queries
* Update: POT file
* Update: Re-structure files & folder inside plugin
* Tweak: Do not allow special characters in the group name while creating a form

= 1.3.3 (10.03.2017) =

* Fix: Duplicate _wpnonce in the subscribe form
* Fix: wp_enqueue_style was adding extra slash(/) resulting in failing of enqueuing the style.css file
* Fix: CSS issues on Edit subscribe form screen
* Fix: Made few strings translatable
* Update: POT file

= 1.3.2 (02.03.2017) =

* New: WordPress 4.7.2 compatible
* Fix: Multiple confirmation emails were sent when a subscriber subscribed to more than one group
* Fix: Made few strings translatable
* Update: Renamed admin menu from 'Advanced Form' to 'Group Selector'
* Update: UI improvements
* Update: Text correction in few places
* Update: 5 star rating link
* Update: POT file
* Tweak: Disable selecting 'Display EMAIL field?' & 'Make EMAIL field Mandatory?' fields while creating and editing forms
* Tweak: Do not show form Database id of form on Group Selector screen

= 1.3.1 (27.10.2016) =

* New: New contributor name has been added
* Update: POT file

= 1.3 (17.02.2016) =

* New: Scripts are now localized and can be translated
* Fix: Double menu of Email Subscribers in WordPress admin upon activating the plugin
* Fix: Incorrect text domain for few texts
* Update: Added text domain for missing texts
* Update: Added POT file

= 1.2.1 (18.12.2015) =

* New contributor has been added successfully

= Earlier Versions =

For the changelog of earlier versions, please refer to the separate [changelog.txt](https://plugins.svn.wordpress.org/email-subscribers-advanced-form/trunk/changelog.txt) file.