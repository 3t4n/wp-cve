=== 360 Product Rotation ===
Contributors: YoFLA
Tags: 360, 360 product view, 360 product rotation, 360 product viewer, 3d product viewer, 360 view software,
product rotation, objectvr, object vr, 3D product rotation, 3D, product spin, 360 product spin
Requires at least: 3.3.0
Tested up to: 5.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: 1.5.8

Turn a series of product photos into an interactive 360 degree view.

== Description ==

####Works in 3 steps ####
1. Create a 360 view with the [online 360 view creator](https://www.y360.at/creator/?utm_source=wordpress.org&utm_medium=plugin&utm_content=readme_creator) (requires registration)
2. Enter your license key in the plugin settings page
3. View your created views in the 360 media gallery and copy and paste the short code to embed it in any page/post.
4. Optionally, manually upload your self-hosted 360 view to wp-content/uploads/yofla360/ directory and embed it using a short-code

####Features####
* Full 360° view
* Responsive design
* Works on mobile devices (retina support)
* Works with Woocommerce (storefront theme tested)
* Cloud or Self Hosted option

== Installation ==
* Install 360 Product Rotation Plugin by installing from your Wordpress Admin area
* Enter your license key in the 360 plugin settings page
* View the 360 views you created with the online creator in the media gallery, copy the shortcode and use it in any page/post

####Shortcode parameters####
* **src** is the reference to the cloud-based 360 view (or for legacy players, path to local 360 view)
* **width** is your desired width in px or % (optional parameter, defaults to 500px)
* **height** is your desired height in px or % (optional parameter, defaults to 375px)
* **auto-height="true"** if set to true, automatically resizes the height of the 360 view on small screens to keep its aspect ratio

== Screenshots ==
1. This is how the player looks like. It is possible to customize the theme (buttons)
2. Web-based interface for creating 360 views (external website)
3. List your projects in Wordpress, copy & paste shortcode (authorization using license key is required)


== Changelog ==
#####1.5.8#####
* Fix: (php): Fix woocommerce PHP error

#####1.5.7#####
* Fix: (php): Fix PHP,WP notice error level warnings

#####1.5.6#####
* Feat: (shortcode): keep also the maximum desired width/height of the 360 view with the shortcode option auto-height="true" set to true
* Feat: (plugin): tested the compatibility with wordpress 5.5
* Feat: (plugin): removed the upload functionality (you will need FTP/SFTP to upload your self-hosted 360 views)

#####1.5.5#####
* Fix: (shortcode): make auto-height="true" sizing work in Safari browser

#####1.5.4#####
* Fix: (shortcode): fix "100%" being trimmed to "100"

#####1.5.3#####
* Chore: (version): fixing plugin version number 1.5.2

#####1.5.2#####
* Chore: (styles): fixed typo in inline styles for auto-aspect ratio enabled 360 views
* Chore: (cloud): adding shorter cloud domain: c.y360.at vs next360.lacora.eu
* Chore: (shortcode): improve the auto-height parameter options

#####1.5.1#####
* Feat: (styles): Added the shot-tag option to auto-adjust height of an 360 view to match its aspect ratio ([... auto-height="true"])
* Chore: (mediaPage): Added link to launch the 360 View Creator

#####1.5.0#####
* Feat: (styles): Admin area: nicer word wrapping for long project names in 360 Product Gallery
* Feat: (styles): Admin area: added preview button
* Feat: (styles): By default, the 360 views have now smaller height on mobile screens so the 360 product view fits the screen, rather than the full original height like on computer screens

#####1.4.9#####
* Feat: (settings): communicate more clearly the two distinct input fields for new and legacy license keys
* Chore: (system): internal code improvement

#####1.4.8#####
* Feat: (mediaPage): cleaned-up UI and added support for displaying cloud-hosted 360 views
* Feat: (settingsPage): cleaned-up UI (legacy settings are hidden by default)
* Fix: (security): "reflected XSS" bug fixed (thanks ImplosionSec!)

#####1.4.7#####
* Feat: (player): add support for player version #27 and newer [with dynamic resolution images](https://www.yofla.com/3d-rotate/2019/04/26/360-product-viewer-retina-support/?utm_source=wordpress.org&utm_medium=plugin&utm_content=readme_changelog147)

#####1.4.6#####
* Feat: (woocommerce): new embedding logic added(optional, default off), which enables to accommodate multiple product image galleries in one product page
* Fix: (woocommerce): allow spaces in url when using product url
* Fix: (woocommerce): when using product url (instead of dropdown), do not require (any) product selected in dropdown

#####1.4.5#####
* Feat: (player): option to set global stylesheets (player theme) url added (just for the legacy player)

#####1.4.4#####
* Fix: (shorttag): another smaller improvement

#####1.4.3#####
* Fix: (shorttag): fix the iframe="false" short-tag option functionality when "just images" upload method is used

#####1.4.2#####
* Feat: (WooCommerce): handle the case if variable product has no default value set

#####1.4.1#####
* Feat: Added support for specifying custom 360 thumb view url for WooCommerce product gallery

#####1.4.0#####
* Feat: Added support for the [online 360 view creator (beta)](https://www.y360.at/creator/?utm_source=wordpress.org&utm_medium=plugin&utm_content=readme_changelog140)

#####1.3.9#####
* Feat: (WooCommerce) Support for entering product URL in 360 View Product Tab (for single product) added
* Feat: added the option to clear the cache folder in plugin settings (useful e.g. after migrating to https)

#####1.3.8#####
* Fix: (WooCommerce) Improvements and fixes in woocommerce plugin

#####1.3.7#####
* Fix: (WooCommerce) Fix PHP warning message (second parameter missing)

#####1.3.6#####
* Fix: (ssl) Ensure wp_upload_dir returns always https for SSL enabled websites

#####1.3.5#####
* Fix: (WooCommerce) If product has a product gallery the 360 view is added as additional (first) item of the gallery - and does not remove the product gallery as before

#####1.3.4#####
* Chore: Checked compatibility with latest Wordpress (4.9.6)

#####1.3.3#####
* Fix: Compatiblity fix with WooCommerce 3.1.1

#####1.3.2#####
* Feat: Added WooCommerce support for variable products

#####1.3.1#####
* Fix: BugFix in uploading .zip files creted by 3DRT Setup Utility
* Feat: Improved error reporting when using 360 shortcode with invalid path

#####1.3.0#####
* Minor bugfix (WooCommerce not-360-view Product Gallery Fix)

#####1.2.9#####
* Minor bugfix

#####1.2.8#####
* Fix for WooCommerce 3.0 - replacing main product image now works

#####1.2.7#####
* Folder list sorted by name

#####1.2.6#####
* Smaller UI updates (for .zip upload)

#####1.2.5#####
* Enabling .zip upload and fixing security issues. A must upgrade if you are using 1.2.3 or older version.

#####1.2.4#####
* Disabling file upload, important upgrade.

#####1.2.3#####
* Bugfix in specifying custom themUrl parameter in settings.ini file (used for "just images" upload)

#####1.2.2#####
* Added the option to specify own global location for the player engine file (rotatetool.js)
* Added the option to make the 360 views user their own (local) player engine file (rotatetool.js)
* Added the option to specify custom theme url in settings.ini file (used for "just images" upload)

#####1.2.1#####
* Bugfix: .zip file created on some Windows systems did not extract correctly

#####1.2.0#####
* Bugfix: using dash in folder name caused an error

#####1.1.9#####
* Possible SSL communication bug fix when entering license-id in settings

#####1.1.8#####
* Nicer error reporting when processing of uploaded .zip archive fails

#####1.1.7#####
* Bug fixed for using correct (licensed) rotatetool.js file for "just images upload"

#####1.1.6#####
* Bug fixed when uploading zip file

#####1.1.5#####
* Bug fixed when using License Key and not License ID in settings.

#####1.1.4#####
* WooCommerce integration added. Google Analytics support improved. Plugin code refactored & cleaned up. Many under-the-hood improvements.

#####1.1.3#####
* Added the option to upload product via WordPress Interface - now you do not need an FTP client for uploading a 360 view

#####1.1.2#####
* Improvements in creating 360 views by just uploading the images: [example](https://www.yofla.com/3d-rotate/examples/tank-model/).

#####1.1.1#####
* Updated to support SSL on websites
* Improved "just images" functionality for better support of local settings.ini

#####1.0.9#####
* Small code improvement for better updating from older versions of the plugin (creates yofla360 folder in uploads dir)

#####1.0.8#####
* Added support for creating 360 product views from images only (no need to use 3DRT Setup Utility)

#####1.0.7#####
* Added support for specifying Absolute URLs as src parameter, e.g. for amazon s3 files hosting

#####1.0.6#####
* Added support for Google Analytics Events Tracking

#####1.0.5#####
* typo in 1.0.4 fixed

#####1.0.4#####
* temporary disabled ssl connection for cloud based rotatetool.js (problem with renewing ssl certficate on side of my hosting provider)

#####1.0.3#####
* iframe embed mode is now turned on by default (for better fullscreen support)
* added option to set default iframe styles in Settings page

#####1.0.2#####
* added error message when user wants to embed one object in one page twice or more (what is not currently possible)
* added support for popup embed mode
* fixed bug when using px values

#####1.0.1#####
* added support for embedding flash based 360 product rotations created with 3DRT Setup Utility 1.3.8 and older

#####1.0.0#####
* initial release

== Frequently Asked Questions ==

= Does it work on mobile devices? =

Yes, the 360&deg; player works on iOS/Android and other touch and mobile devices.

= How much product photos do I need? =

It is recommend at least 36 photos for a smooth rotation - one shot at each 10&deg; degrees for a full 360&deg; view.


== Upgrade Notice ==

