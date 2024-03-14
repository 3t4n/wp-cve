=== Super Progressive Web Apps ===
Contributors: SuperPWA
Tags: pwa, progressive web apps, manifest, web manifest, android app, chrome app, add to homescreen, mobile web
Requires at least: 3.6.0
Tested up to: 6.4
Requires PHP: 5.3
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

SuperPWA helps you convert your WordPress website into a Progressive Web App instantly.

== Description ==

Progressive Web Apps (PWA) is a new technology that combines the best of mobile web and the best of mobile apps to create a superior mobile web experience. They are installed on the phone like a normal app (web app) and can be accessed from the home screen.

[Home](https://superpwa.com/) | [Documentation](https://superpwa.com/docs/) | [Help](https://superpwa.com/contact/) | [Premium version Features](https://superpwa.com/docs/#pro-doc) 

Users can come back to your website by launching the app from their home screen and interact with your website through an app-like interface. Your return visitors will experience almost-instant loading times and enjoy the great performance benefits of your PWA!

Super Progressive Web Apps makes it easy for you to convert your WordPress website into a Progressive Web App instantly!

Once SuperPWA is installed, users browsing your website from a supported mobile device will see a "Add To Home Screen" notice (from the bottom of the screen) and will be able to 'install your website' on the home screen of their device. Every page visited is stored locally on their device and will be available to read even when they are offline!

SuperPWA is easy to configure, it takes less than a minute to set-up your Progressive Web App! SuperPWA does a clean uninstall, by removing every database entry and file that it creates. In fact, none of the default settings are saved to the database until you manually save it the first time. Go ahead and give it a try.

And the best part? If you ever get stuck, we are here to watch your back! [Open a support](https://wordpress.org/support/plugin/super-progressive-web-apps) ticket if you have a question or need a feature. We are super excited to hear your feedback and we want to genuinely help you build the best Progressive Web App for your WordPress website!

#### Quick Demo? 

* Open up [SuperPWA.com](https://superpwa.com/?utm_source=wordpress.org&utm_medium=description-demo) in a supported device. 
* Add the website to your home screen either from the Add to Home Screen prompt (Chrome for Android) or from the browser menu. 
* Open the app from your home screen and you will see the splash screen. 
* Turn off your data and wifi to go offline and open up the app. You will still be able to see the app and browse the pages you have already visited. 
* Browse to a page that you haven't visited before. The offline page will be displayed. 

#### Thank You PWA Enthusiasts! 

We are humbled by the feedback from the community. Thanks to everyone who believed in us and tried our plugin. Your feedback has been invaluable and we have learned a lot from your experience. Thank you for your love and support and we hope to return the love by striving to bring you the best ever Progressive Web Apps plugin for WordPress!

### What's in the box

Here are the current features of Super Progressive Web Apps: 

* Generate a manifest for your website and add it to the head of your website.
* Set the application icon for your Progressive Web App. 
* Set the background color for the splash screen of your Progressive Web App. 
* Your website will show the "Add to home screen" notice when accessed in a supported browser.
* Aggressive caching of pages using CacheStorage API.
* Pages once cached are served even if the user is offline. 
* Set custom offline page: Select the page you want the user to see when a page that isn't in the cache is accessed and the user is offline.
* New in version 1.2: Support for theme-color meta property. Change the color of browser address bar of Chrome, Firefox OS and Opera to match your website colors. 
* New in version 1.2: Now you can edit the Application Name and Application Short name.
* New in version 1.2: Set the start page of your PWA.
* New in version 1.2: Set Accelerated Mobile Pages (AMP) version of the start page. Supported plugins: AMP for WordPress, AMP for WP, Better AMP, AMP Supremacy, WP AMP.
* New in version 1.3: Added support for high-quality splash screen. You can now set the 512x512 icon for the splash screen of your Progressive Web App.
* New in version 1.3: Super Progressive Web Apps now accounts for content updates and will update the cache as you update the website. 
* New in version 1.3: Improved in-browser service worker update handling.
* New in version 1.4: You can now set the default orientation of your PWA. Choose from "any" (Follow Device Orientation), "Portrait" and "Landscape".
* New in version 1.4: You can now set the theme_color property in the manifest.
* New in version 1.5: OneSignal integration for Push notifications.
* New in version 1.6: WordPress Multisite Network compatibility. 
* New in version 1.7: Add-Ons for SuperPWA is here! Ships with [UTM Tracking Add-On](https://superpwa.com/addons/utm-tracking/?utm_source=wordpress.org&utm_medium=description) to track visits coming from your PWA.
* New in version 1.8: Compatibility issues with OneSignal are now resolved! 
* New in version 1.8: New Add-On: [Apple Touch Icons](https://superpwa.com/addons/apple-touch-icons/?utm_source=wordpress.org&utm_medium=description) that sets your app icons as Apple Touch Icons. 
* New in version 2.0: SuperPWA is now compatible with WordPress installed in a sub-folder. 
* New in version 2.0: You can now set [display property](https://superpwa.com/doc/web-app-manifest-display-modes/?utm_source=wordpress.org&utm_medium=description) from SuperPWA settings.
* New in version 2.1.1: SuperPWA now supports Maskable Icons.
* New in version 2.1.15: SuperPWA now supports Monochrome Icon.
* [Full changelog](https://superpwa.com/changelog/) 

**Upcoming features:**

* Offline Indicator Notice.

**PRO Version** support additional [advance feature](https://superpwa.com/docs/)
* Call To Action (CTA) [More Info](https://superpwa.com/doc/call-to-action-cta-add-on-for-superpwa/)
* Android APK APP Generator [More Info](https://superpwa.com/doc/android-apk-app-generator-add-on-for-superpwa/)
* Data Analytics [More Info](https://superpwa.com/doc/data-analytics-add-on-for-superpwa/)
* Pre-Loader [More Info](https://superpwa.com/docs/article/preloader-add-on-for-superpwa/)
* App Shortcuts [More Info](https://superpwa.com/docs/article/app-shortcuts-add-on-for-superpwa/)
* QR Code Generator [More Info](https://superpwa.com/docs/article/qr-code-generator-add-on-for-superpwa/)

### Progressive Web App Minimum Requirements

Progressive Web Apps require that your WordPress website is served from a secure origin i.e. your website should be HTTPS and not HTTP. If your website isn't HTTPS, please contact your host about it. You can also [ask us](https://wordpress.org/support/plugin/super-progressive-web-apps) if you need help.

### Device and Browser Support For PWA

Progressive web apps need browsers that support manifests and service workers. Currently Google Chrome (version 57+), Chrome for Android (62), Mozilla Firefox (57), Firefox for Android (58) are the major browsers that support PWA. 

The list is fast growing and is likely to be supported in most major browsers by the end of this year.

### How To Convert Your WordPress Website Into A Progressive Web App

#### WordPress Installation

* Visit WordPress Admin > Plugins > Add New
* Search for 'Super Progressive Web Apps'
* Click "Install Now" and then "Activate" Super Progressive Web Apps

To install manually:

* Upload super-progressive-web-apps folder to the /wp-content/plugins/ directory on your server
* Go to WordPress Admin > Plugins
* Activate Super Progressive Web Apps plugin from the list.

#### Customizing Your Progressive Web App

Your Progressive Web App should be ready to test with the default settings on activation. You can customize it further and make it truly your own.

* Go to WordPress Admin > SuperPWA
* Set a Background Color for the splash screen to be shown when your PWA is opened on a mobile device.
* Set the Application Icon. This will be the icon of your PWA when it is added to the homescreen in a mobile device. The icon must be a PNG image and exactly 192 x 192 pixels in size.
* Set the Offline Page. This page will be displayed if the user is offline and the page he requested is not cached already. Ideally you should create a dedicated WordPress page and set it here. Within the page you create, you could add a note that reads, "It looks like you are offline and the page you requested is not available right now. Please check back again once you are online.". 
* Click "Save Settings".

#### Testing Your Progressive Web App

* Open a supported browser in a supported device (for eg: Chrome for Android (62 or higher) in an Android Phone)
* Enter your website and wait till it fully loads
* You should see a pop-up that has your Application Icon and a button that reads "ADD TO HOME SCREEN".
* Click on it and your PWA will be added to your home screen. Wait for the install to complete. 
* Go to your home screen and open your PWA. Browse into a few pages if you like. Close the App.
* Disconnect from the internet and now open your PWA again. You should be able to see all the pages that you previously browsed. 
* Try visiting a page that you did not visit before. You should see the page you set as your "Offline Page" in the settings of SuperPWA. 

#### Troubleshooting Your Progressive Web App

Uh, oh. Your PWA did not work as expected? You do not see the "Add to Home Screen" notice?

* Make sure your website has a SSL certificate installed. i.e. your website should be https instead of http (as in https://your-domain.com).
* Make sure you are using a supported device and a supported browser. Refer to the "Device and Browser Support For PWA" list above.
* Make sure your Application Icon and Splash Screen Icon's are of PNG format and 192px X 192px and 512px X 512px in size respectively. 
* Clear the browser cache and try again. In Chrome for Android, go to Settings > Privacy > "Clear browsing data".
* If the application icon does not update after first install, delete the PWA from your phone, clear browser cache and install again. (We are working on making it better.) 
* Create a [new support ticket](https://wordpress.org/support/plugin/super-progressive-web-apps) and share a link to your website. We will take a look and figure it out for you.

### Feature Requests, Issues, Pull Requests

Here is our repository on [GitHub](https://github.com/SuperPWA/Super-Progressive-Web-Apps). Send us your pull requests, feature requests or issues, if any.

### About us

We are a duo who got excited about the idea. Our mission is simple: Help you build an awesome PWA that your users would want to have on their home screen.

When we first heard about PWA we wanted to learn everything about it. We have spent countless hours learning and wants to share it with the world. 

Please give us your constructive feedback and support. 

== Installation ==

To install this plugin:

1. Install the plugin through the WordPress admin interface, or upload the plugin folder to /wp-content/plugins/ using FTP.
2. Activate the plugin through the 'Plugins' screen in WordPress. 
3. Go to WordPress Admin > SuperPWA

== Frequently Asked Questions ==

If you have any questions, please ask it on the [support forum](https://wordpress.org/support/plugin/super-progressive-web-apps).

= Will Progressive Web Apps work on iOS devices? =

Starting with Safari for iOS 11.3, Apple devices offer partial support for PWA's. However, there is no native Add To Home Screen prompt just yet. You can add your app by tapping "Add to Home Screen" button in the share menu of the browser ( look for the square icon with an up arrow in the foreground ).

Just like you, we are eagerly awaiting the upcoming releases and we hope to see better compatibility in the coming months. 

= How To Customize Splash Screen = 

You can easily change the icon and the background color in SuperPWA > Settings. 

Further customizations are not available right now, not because of any limitation of SuperPWA, but because they are not available in the PWA technology. When more options come up in the future, we will add them to SuperPWA then. 

= How To Track Visits Originating From Your Progressive Web App = 

You can track visits from your PWA in your analytics software (for e.g. Google Analytics) using the UTM Tracking add-on of SuperPWA. Go to SuperPWA > Add-Ons and activate UTM Tracking. Then in SuperPWA > UTM Tracking, you can set the UTM parameters as needed. Please [refer the documentation](https://superpwa.com/addons/utm-tracking/?utm_source=wordpress.org&utm_medium=description-faq) for further information. 

= GDPR Compliance =

SuperPWA does not collect or store user data, nor does it set cookies or store tracking data. Content visited by users from your PWA is stored in the user's own device, in the cache of the browser. This is very similar to how modern browsers caches content offline for faster browsing. 

With the UTM Tracking Add-On, you will be able to differentiate the visits originating from your PWA in your analytics software. You may have to include this in your privacy policy. Please note that SuperPWA does not track the visits, we just help you add the UTM parameters to the URL of the Start Page of your app so that third party analytics tools can differentiate the visits. 

Feel free to get in touch if you have any questions. 

== Screenshots ==

1. Settings page in WordPress Admin > SuperPWA > Settings

== Changelog ==

= 2.2.24.1 =
* Date: [05.March.2024](https://superpwa.com/superpwa-2-2-24.1-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed: Activating Pro version throwing Fatal error #501

= 2.2.24 =
* Date: [17.February.2024](https://superpwa.com/superpwa-2-2-24-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed: CTA on the frontpage is not showing.  #499
* Feature: Make the offline message “You are offline” as an option #482
* Feature: Make the feature Prefetch manifest URL link as an option #496
* Enhancement: Improvements to the app screenshot option. #495


= 2.2.23.1 =
* Date: [24.December.2023](https://superpwa.com/superpwa-2-2-23-1-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed: Parse error: syntax error, unexpected ")" in PHP 7.2 and below 

= 2.2.23 =
* Date: [22.December.2023](https://superpwa.com/superpwa-2-2-23-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Feature: Proper sorting of features #314
* Enhancement: Compatibility with loco translate #426
* Enhancement: Updated manifest as per the pwabuilder recommendation. #440
* Enhancement: Code Improvement checklist #486
* Enhancement: Update readme.txt and add all pro feature list #488
* BugFixed: Warning appearing in manifest #487
* BugFixed: On Uninstall few data still remain inside db. #485

= 2.2.22 =
* Date: [28.November.2023](https://superpwa.com/superpwa-2-2-22-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed: Role Based Access is not working #472
* BugFixed: Fixed Broken Access Control vulnerability [Learn More](https://patchstack.com/database/vulnerability/super-progressive-web-apps/wordpress-super-progressive-web-apps-plugin-2-2-21-broken-access-control-vulnerability)

= 2.2.21 =
* Date: [18.October.2023](https://superpwa.com/superpwa-2-2-21-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed: Push notification is not working on IOS. #468
* Feature: Added Notification badge on the PWA icon #212
* BugFixed: Role Based Access is not working #472
* BugFixed: If add-on is not activated than do not show the settings button #470
* BugFixed: The button network deactivate is not working #475

= 2.2.20 =
* Date: [26.September.2023](https://superpwa.com/superpwa-2-2-20-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Feature: Added feature for Role managers #450
* Feature: Added navigation Bar feature #443
* BugFixed: DevTools Console Warning for Source Map 404 Error in Plugin #466
* BugFixed: The push notification add-on keeps on installing. #465

= 2.2.19 =
* Date: [11.August.2023](https://superpwa.com/superpwa-2-2-19-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Feature: Added Push Notifications support #452

= 2.2.18 =
* Date: [15.July.2023](https://superpwa.com/superpwa-2-2-18-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Feature: Added compatibility with WPML #438
* Enhancement :fix php 8 error #332

= 2.2.17 =
* Date: [30.May.2023](https://superpwa.com/superpwa-2-2-17-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Enhancement :Need to create an option where the user can disable/enable the Add to Home Screens banner on desktop or tablet. #417
* Enhancement :Need to make Start page dropdown with ajax search #412 
* BugFixed :Warning appears in all pages when we install pro version #421

= 2.2.16 =
* Date: [27.April.2023](https://superpwa.com/superpwa-2-2-16-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* New : Add related_applications to manifest #387
* Enhancement : Label should be clickable for all checkbox labels #411 


= 2.2.15 =
* Date: [12.April.2023](https://superpwa.com/superpwa-2-2-15-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* New : Added Monochrome icon to manifest  #328
* Enhancement : Set post as Start Page #328 
* Enhancement : Increased character limit of app name to 20  #328  
* BugFixed : Conflict with WP Fastescache plugin. #401
* BugFixed : Assets not loading with Cache First then Network strategy. #408

= 2.2.14.1 =
* Date: [02.April.2023](https://superpwa.com/superpwa-2-2-14-1-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : Media files not loading properly after 2.2.14 update #405

= 2.2.14 =
* Date: [01.April.2023](https://superpwa.com/superpwa-2-2-14-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : Call To Action banner should not be shown on inner page #392
* BugFixed : Super PWA conflicts with the Real Media Library plugin. #391
* BugFixed : Conflict issue with Wolmart theme #390
* BugFixed : An error appears in the console if the page contains a video #386
* BugFixed : QR code is not working #400
* BugFixed : <a class="superpwa_add_home_close"> effect on SEO #385
* BugFixed : Conflict with CHP ads block detector #399
* BugFixed : Need to add JavaScript:void instead of href # in the SuperPwa  #398
* BugFixed : Need to create an option where the user can show the add-to-screen banner for particular pages. #402

= 2.2.13 =
* Date: [10.March.2023](https://superpwa.com/superpwa-2-2-13-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : CTA install banner is appearing even after installing the PWA #381
* BugFixed : Manifest file showing wrong logo images link #379
* BugFixed : QR code install link not working #394
* BugFixed : Preloader showing with delay and takes longer when click is on Image link #395
* BugFixed : "Stale while revalidate" in the Caching Strategies addon has no effect, it doesn't change the service worker js at all. #396

= 2.2.12 =
* Date: [13.February.2023](https://superpwa.com/superpwa-2-2-12-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : Issue with Network first, then Cache #336
* BugFixed : Preloader not working properly. #375
* Enhancement :Remove Offer Banner #372

= 2.2.11 =
* Date: [29.December.2022](https://superpwa.com/superpwa-2-2-11-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : Error and warning in application section #358
* BugFixed : APP screenshots option default link #370
* BugFixed : A warning appears under the start page and offline page options after resting the settings. #313
* BugFixed : Script does not have a MIME type. #280
* BugFixed : Splash Screen Icon is taking app icon image IOS  #290 #302

= 2.2.10 =
* Date: [09.December.2022](https://superpwa.com/superpwa-2-2-10-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : An error with SuperPWA is displayed while testing in the Page speed dev tool.  #362
* BugFixed : CTA button in menu is appearing in PWA even if user has downloaded the PWA. #356
* BugFixed : CTA banner display option "Show on Scroll" not working properly. #350
* BugFixed : PWA Category issue fixed 
* Enhancement :CTA menu button warning on IOS only appears in the English language. #363

= 2.2.9.1 =
* Date: [01.December.2022](https://superpwa.com/superpwa-2-2-9-1-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : Fixed issue undefined variable $screenshot_array
* BugFixed : The APK has crashed at the user's end, and URL shown inside the APK. #359

= 2.2.9 =
* Date: [29.November.2022](https://superpwa.com/superpwa-2-2-9-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Enhancement : Security nonce is missing #349
* Enhancement : Escaping is missing #348
* Enhancement : Functions must have a prefix #347
* Enhancement : BFCM internal offer #352
* BugFixed : Offline pages not working on Android Devices #91
* BugFixed : In iOS, the splash screen icon does not work #343
* BugFixed : Error in manifest file "name of shortcut is an empty string" #351
* BugFixed : APP Screenshots option not working #353
* BugFixed : The APK has crashed at the user's end, and URL shown inside the APK. #359

= 2.2.8 =
* Date: [09.November.2022](https://superpwa.com/superpwa-2-2-8-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Enhancement : While testing the SupwerPwa on https://www.pwabuilder.com/tool, the service for offline shows zero scores. #316 (Screenshots, Category, Direction, Offline issue)
* Enhancement : upgrade to pro button #319
* Enhancement : feat (issue #101): Offline Indicator Notice feature #106
* BugFixed : issue: excluded_urls" in /wp-content/plugins/super-progressive-web-apps/public/manifest.php on line 252 #333

= 2.2.7 =
* Date: [13.October.2022](https://superpwa.com/superpwa-2-2-7-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : Preloader not working in IOS device #288
* BugFixed : Exclude URLs from Cache list option is not working properly. #300
* BugFixed : getting error when we are adding range data in cache #311
* BugFixed : Bug with network-first cache strategy and REST calls #329

= 2.2.6 =
* Date: [06.July.2022](https://superpwa.com/superpwa-2-2-6-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : Stop servicework for caching the videos. #279
* BugFixed : Apple Touch Icons & Splash Screen not fetching Welcome screen image #284
* Enhancement : Added New Addon Pull To Refresh #285

= 2.2.5 =
* Date: [21.April.2022](https://superpwa.com/superpwa-2-2-5-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : Add to home screen was not working when theme manifest is active #271

= 2.2.4 =
* Date: [8.April.2022](https://superpwa.com/superpwa-2-2-4-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed : CTA Banner should be hidden once app is installed #264

= 2.2.3 =
* Date: [26.March.2022](https://superpwa.com/superpwa-2-2-3-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Enhancement : Added New Addon QR Code Generator Array #255
* Enhancement : Added Warning UI on License tab when key is expired #258

= 2.2.2 =
* Date: [16.March.2022](https://superpwa.com/superpwa-2-2-2-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Enhancement : Added Compatibility with WonderPush Pushnotifications plugin #188
* Enhancement : Improved the Design of User feedback auto email system #252

= 2.2.1 =
* Date: [17.February.2022](https://superpwa.com/superpwa-2-2-1-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Bug Fixed : Some extension addon features are not working properly  #245

= 2.2 =
* Date: [14.December.2021](https://superpwa.com/superpwa-2-2-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Bug Fixed   : Fixed Manifest Warnings Regarding Icon Purpose Value#237
* Enhancement : Added AMP Support with Official AMP and AMPforWP Plugins#218
* Enhancement : Added New option to Reset Settings#233

= 2.1.19 =
* Date: [23.November.2021](https://superpwa.com/superpwa-2-1-19-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Enhancement : Added New option to exclude Add to homescreen banner on particular pages #232

= 2.1.18 =
* Date: [1.November.2021](https://superpwa.com/superpwa-2-1-18-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Bug Fixed : Resolved the issue of License alert showing when key is not activated #230
* Bug Fixed : PHP Warning: Trying to access array offset on value of type bool #230
* Enhancement : Added New Addon PreLoader Array #226

= 2.1.17 =
* Date: [23.October.2021](https://superpwa.com/superpwa-2-1-17-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Bug Fixed : Resolved the issue of Invalid regular expression error when exclude cache URL is placed without end slash #216
* Bug Fixed : Added Seperate caching for range header request urls to aviod 206 type error #220
* Enhancement : Added Pre Caching Feature in Caching Strategies Addon. #222
* Enhancement : Updated License Page to display Complete License Information. #227
* Enhancement : Added Status bar style option for apple icons. #210
* Enhancement : Added Proper Method to render Application name and shortname of PWA App even with non-standard characters. #112

= 2.1.16 =
* Date: [26.August.2021](https://superpwa.com/superpwa-2-1-16-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Bug Fixed : "Add to homescreen" banner was not showing when Wp fastest cache plugin is active #209
* Enhancement : Added an Option to Re-Install Service Worker #211
* Enhancement : Added an Option to Cache External Origin URLs by Service Worker #213

= 2.1.15 =
* Date: [12.July.2021](https://superpwa.com/superpwa-2-1-15-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Bug Fixed : User end issue: Apple splash icon not working properly #200
* Bug Fixed : Centralize image with background color not getting saved. #205

= 2.1.14 =
* Date: [30.June.2021](https://superpwa.com/superpwa-2-1-14-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* Enhancement: Need to Improve Admin UI #196

= 2.1.13 =
* Date: [15.June.2021](https://superpwa.com/superpwa-2-1-13-release-note/?utm_source=wordpress.org&utm_medium=changelog)
* BugFixed: iOS Splash Screen Images are not getting saved #191

= 2.1.12 =
* Date: [29.May.2021](https://superpwa.com/superpwa-2-1-12-release-note/?utm_source=wordpress.org&utm_medium=changelog)
Enhancement: Need An Option to exclude the URL #183
Enhancement: Improved Tabs UI design #190

= 2.1.11 =
* Date: [17.May.2021](https://superpwa.com/superpwa-2-1-11-release-note/?utm_source=wordpress.org&utm_medium=changelog)
BugFixed: Remediation of Splash Screen Settings #178

= 2.1.10 =
* Date: [10.May.2021](https://superpwa.com/superpwa-2-1-10-release-note/?utm_source=wordpress.org&utm_medium=changelog)
BugFixed: iOS splash screen not working using apple icons addon #182
BugFixed: iOS - Splash Screen #175

= 2.1.9 =
* Date: [17.April.2021](https://superpwa.com/changelog/)
Minor Improvment: Added Data Analytics Addon Array #152

= 2.1.8 =
* Date: [15.March.2021](https://superpwa.com/superpwa-2-1-8/?utm_source=wordpress.org&utm_medium=changelog)
BugFixed: manifest json file taking a long time to load #130
Enhancement: Compatibility with Onesignal on multisite #94


= 2.1.7 =
* Date: [16.February.2021](https://superpwa.com/superpwa-2-1-7/?utm_source=wordpress.org&utm_medium=changelog)
BugFixed: Manifest shortcut icons json issue resolve image size 192x192  #163
Enhancement: iOS application splash screen support for all screens #160
Enhancement: Feature of caching, multiple strategies  #138

= 2.1.6 =
* Date: [04.February.2021](https://superpwa.com/superpwa-2-1/?utm_source=wordpress.org&utm_medium=changelog)
BugFixed: Manifest shows start URL is out of the scope URL in Specific cases #162
BugFixed: Offline analytics undefined variable #99
Enhancement: iOS application icons are blur overwrite by WordPress #161
BugFixed: Quick action feature for PWA icons need to specify size #147


= 2.1.5 =
* Date: [06.January.2021](https://superpwa.com/superpwa-2-1/?utm_source=wordpress.org&utm_medium=changelog)
Enhancement: Added the support of google analytics #149
Enhancement: Disabling “Add to home screen” #150
Enhancement: Support for Yandex manifest #146
Enhancement: Addex Quick action (shortcut) feature for PWA #147
Enhancement: Improve user interface #142
Enhancement: Added the tutorial link for Call To Action and Android APK APP Generator #145

= 2.1.4 =
* Date: [18.December.2020](https://superpwa.com/superpwa-2-1/?utm_source=wordpress.org&utm_medium=changelog)
Bug Fix: Wrong manifest path if installed WordPress in a sub-folder #134
Enhancement: Need to increase character limit of APP name. #139
Bug Fix: Default Add to home screen banner is not showing #140
Bug Fix: Changes in Presentation #141 / #143


= 2.1.3 =
Improvement: Changes in Admin Options
Enhancement: Added more pages

= 2.1.2 =
* Date: [25.July.2020](https://superpwa.com/superpwa-2-1/?utm_source=wordpress.org&utm_medium=changelog)
* Bug Fix: Fixed issue where Application Icon was not showing.

= 2.1.1 =
* Date: [4.July.2020](https://superpwa.com/superpwa-2-1/?utm_source=wordpress.org&utm_medium=changelog)
* Tested with WordPress 5.4.2.
* Enhancement: Added support for Maskable Icons [#127](https://github.com/SuperPWA/Super-Progressive-Web-Apps/issues/127)

= 2.1 =
* Date: [29.May.2020](https://superpwa.com/superpwa-2-1/?utm_source=wordpress.org&utm_medium=changelog)
* Tested with WordPress 5.4.1.
* Enhancement: Removed the WordPress admin notice suggesting to add SuperPWA manifest to OneSignal. [#114] (https://github.com/SuperPWA/Super-Progressive-Web-Apps/issues/114)
* Enhancement: Updated fallback value in superpwa_get_display() to match the default value in superpwa_get_settings().
* Enhancement: UTM Tracking Add-on: Added default values for Campaign Medium and Campaign Name.
* Bug Fix: Fixed a rare PHP Notice: Array to string conversion in basic-setup.php on line 415. [#92](https://github.com/SuperPWA/Super-Progressive-Web-Apps/issues/92)
* Bug Fix: Added a check to see if WP_Plugins_List_Table class is available before using it. [#93](https://github.com/SuperPWA/Super-Progressive-Web-Apps/issues/93)

= 2.0.2 =
* Date: 16.January.2019
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context". 

= 2.0.1 =
* Date: [15.January.2019](https://superpwa.com/superpwa-2-0/?utm_source=wordpress.org&utm_medium=changelog#2.0.1)
* Enhancement: Added compatibility for setups where dynamic files are not supported. 

= 2.0 =
* Date: [28.December.2018](https://superpwa.com/superpwa-2-0/?utm_source=wordpress.org&utm_medium=changelog)
* Tested with WordPress 5.0.2. 
* Enhancement: Dynamic service worker and manifest. 
* Enhancement: SuperPWA is now compatible with WordPress in a sub-folder. 
* Enhancement: Added UI to set [Display property](https://superpwa.com/doc/web-app-manifest-display-modes/?utm_source=wordpress.org&utm_medium=changelog) in the web app manifest. 
* Enhancement: Limit short_name to 12 characters to meet Lighthouse recommendation. 
* Enhancement: Added PHP CodeSniffer to stick to "WordPress-Extra" coding standards. Thanks Daniel for the work. 
* Enhancement: SuperPWA is available in 12 languages now, thanks to the awesome translators! Translators are credited in the [release note](https://superpwa.com/superpwa-2-0/). 

= 1.9 =
* Date: [25.July.2018](https://superpwa.com/superpwa-1-9-chrome-mini-infobar-ready/?utm_source=wordpress.org&utm_medium=changelog)
* Tested with WordPress 4.9.7. 
* Enhancement: Added compatibility with Google Chrome 68 Mini Info-Bar. 
* Enhancement: Added support for tagDiv AMP Plugin which ships with Newspaper theme and Newsmag theme. If you use this theme, you can now use AMP version of the start page. 
* Enhancement: Added support for images in offline page. Images added to offline page are now cached during service worker activation. 
* Enhancement: Improved the service worker installation routine to handle invalid entities in the service worker dependencies. 
* Enhancement: SuperPWA is now translated to French, thanks to [@romainvincent](https://profiles.wordpress.org/romainvincent) and Spanish, thanks to [@arkangel](https://profiles.wordpress.org/arkangel/). 
* Bug Fix: Fixed issue where translation files in /languages/ folder was not being loaded. 

= 1.8.1 =
* Date: [05.June.2018](https://superpwa.com/push-notifications-are-here-again/?utm_source=wordpress.org&utm_medium=changelog#1.8.1)
* Enhancement: Added an admin notice with [instructions for OneSignal integration](https://superpwa.com/doc/setup-onesignal-with-superpwa/?utm_source=wordpress.org&utm_medium=changelog). 
* Enhancement: Updated console log message for URLs excluded from cache for better clarity. 

= 1.8 =
* Date: [31.May.2018](https://superpwa.com/push-notifications-are-here-again/?utm_source=wordpress.org&utm_medium=changelog)
* Tested with WordPress 4.9.6. 
* New Add-On: Apple Touch Icons. Set the Application Icon and Splash Screen Icon as Apple Touch Icons for compatibility with iOS devices. 
* Enhancement: Added support for Add to Home Screen prompt for Chrome 68 and beyond. 
* Enhancement: Better add-on activation and deactivation by hooking onto admin_post action. 
* Enhancement: Attempt to generate manifest and service worker automatically on visiting the SuperPWA settings page after adjusting root folder permissions. 
* Enhancement: Generated a .pot file with all strings for translation. You can also translate SuperPWA to your language by visiting [translate.wordpress.org](https://translate.wordpress.org/projects/wp-plugins/super-progressive-web-apps) 
* Bug Fix: Compatibility issues with OneSignal are resolved for single installs. 
* Bug Fix: Updated plugin action links and admin notices with the correct admin menu link. 

= 1.7.1 =
* Date: 05.May.2018
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context".

= 1.7 =
* Date: [03.May.2018](https://superpwa.com/introducing-add-ons-for-superpwa/?utm_source=wordpress.org&utm_medium=changelog)
* Minimum required WordPress version is now 3.6.0 (previously 3.5.0).
* New Feature: Add-Ons for SuperPWA is here!
* New Feature: SuperPWA is now a top-level menu to accommodate for the Add-Ons sub-menu page.
* New Feature: Add UTM Tracking parameters to the Start URL with the [UTM Tracking Add-On](https://superpwa.com/addons/utm-tracking/?utm_source=wordpress.org&utm_medium=changelog).
* Enhancement: Service worker URLs are now relative to accommodate for domain mapped Multisites.
* Bug Fix: Incorrect start_url when WordPress is installed in a folder, or when inner pages are used as start_url.
* Bug Fix: Incorrect manifest and service worker URLs when WordPress is installed in a folder. 

= 1.6 =
* Date: [23.April.2018](https://superpwa.com/1-6-released-multisite-network-support/?utm_source=wordpress.org&utm_medium=changelog)
* New Feature: WordPress Multisite Network Compatibility. One of the most requested features for SuperPWA is now here! Thanks [@juslintek](https://wordpress.org/support/topic/add-manifest-json-support-for-multisite/#post-9998629) for doing a major share of the heavy lifting.
* New Feature: Added description to the manifest. You can now include a brief description of what your app is about. 
* Enhancement: Moved manifest to the very top of wp_head for better compatibility with some browsers.
* Enhancement: Improved the file and folder naming, organization, and inline documentation for better readability.
* Enhancement: Force https:// on all assets and dependencies solving the problem of http:// URLs in manifest and service worker once and for all.
* Enhancement: Relative URL for manifest for out of the box compatibility with CDN's.
* Enhancement: Removed forcing of trailing slash on manifest and service worker URLs for better compatibility. 

= 1.5 =
* Date: 18.March.2018
* New Feature: OneSignal integration for Push notifications.
* Enhancement: Moved manifest to the top of wp_head for easier detection by browsers. 
* Enhancement: Added support for custom AMP endpoints for AMP for WordPress and AMP for WP.
* Enhancement: Added UI notice when using AMP for WordPress to warn user not to use the AMP version of start page if the start page is the homepage, the blog index, or the archives page.

= 1.4 =
* Date: [21.February.2018](https://wordpress.org/support/topic/you-asked-and-we-listened-superpwa-1-4-ships-with-two-user-feature-requests/)
* New Feature: Added UI for default orientation of your PWA. Orientation can now be set as "any", "portrait" or "landscape". [Feature request from @doofustoo](https://wordpress.org/support/topic/almost-perfect-335/).
* New Feature: Added UI for theme_color property in manifest. [Feature request from @krunalsm](https://wordpress.org/support/topic/diffrent-theme_color-and-background_color/).
* Enhancement: Improved compatibility with all major Accelerated Mobile Pages (AMP) plugins.
* Enhancement: Improved handling of external resources. 

= 1.3.1 =
* Date: 15.February.2018
* Enhancement: Improved how Start Page url is handled in the service worker to be compatible with plugins that force SSL. 
* Enhancement: Improved how start_url is handled in the manifest.
* Enhancement: Better handling of external resources.

= 1.3 =
* Date: 10.February.2018
* New Feature: Added support for high-quality splash screen. You can now set the 512x512 icon for the splash screen of your Progressive Web App.
* Enhancement: Super Progressive Web Apps now accounts for content updates and will update the cache as you update the website. 
* Enhancement: Improved in-browser service worker update handling.
* Enhancement: Added automatic upgrade of manifest and service worker on plugin upgrade.
* Bug Fix: Only GET requests are served from the cache now. Fixes [this](https://wordpress.org/support/topic/errors-in-firefox-and-chrome/).

= 1.2 =
* Date: 06.February.2018
* New Feature: Support for theme-color.
* New Feature: Now you can edit the Application Name and Application Short name.
* New Feature: Set the start page of your PWA.
* New Feature: Set AMP version of the start page. Supported plugins: AMP for WordPress, AMP for WP, Better AMP, AMP Supremacy, WP AMP.
* UI Improvement: Better organization of plugin settings. More intuitive with inline help. 
* UI Improvement: Added admin notice with a link to settings page on plugin activation.
* UI Improvement: Added checks for manifest, service worker and HTTPS and display the status neatly in the UI.
* Bug Fix: Fix a parse error that showed up only on PHP 5.3. 

= 1.1.1 =
* Date: 30.January.2018
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context". PHP manual says "Prior to PHP 5.5, empty() only supports variables; anything else will result in a parse error."

= 1.1 =
* Date: 28.January.2018
* New Feature: Aggressive caching of pages using CacheStorage API.
* New Feature: Pages once cached are served even if the user is offline. 
* New Feature: Set custom offline page. Select the page you want the user to see when a page that isn't in the cache is accessed and the user is offline.

= 1.0 =
* Date: 22.January.2018
* First release of the plugin.

== Upgrade Notice ==

= 2.1 =
* Tested with WordPress 5.4.1.
* Enhancement: Removed the WordPress admin notice suggesting to add SuperPWA manifest to OneSignal. 
* Enhancement: Updated fallback value in superpwa_get_display() to match the default value in superpwa_get_settings().
* Enhancement: UTM Tracking Add-on: Added default values for Campaign Medium and Campaign Name.
* Bug Fix: Fixed a rare PHP Notice: Array to string conversion in basic-setup.php on line 415. 
* Bug Fix: Added a check to see if WP_Plugins_List_Table class is available before using it. 

= 2.0.2 =
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context". 

= 2.0.1 =
* Enhancement: Added compatibility for setups where dynamic files are not supported. 

= 2.0 =
* Tested with WordPress 5.0.2. 
* Enhancement: Dynamic service worker and manifest. 
* Enhancement: SuperPWA is now compatible with WordPress in a sub-folder. 
* Enhancement: Added UI to set Display property in the web app manifest. 
* Enhancement: Limit short_name to 12 characters to meet Lighthouse recommendation. 
* Enhancement: Added PHP CodeSniffer to stick to "WordPress-Extra" coding standards. Thanks Daniel for the work. 
* Enhancement: SuperPWA is available in 12 languages now, thanks to the awesome translators! Translators are credited in the release note. 

= 1.9 =
* Tested with WordPress 4.9.7. 
* Enhancement: Added compatibility with Google Chrome 68 Mini Info-Bar. 
* Enhancement: Added support for tagDiv AMP Plugin which ships with Newspaper theme and Newsmag theme. If you use this theme, you can now use AMP version of the start page. 
* Enhancement: Added support for images in offline page. Images added to offline page are now cached during service worker activation. 
* Enhancement: Improved the service worker installation routine to handle invalid entities in the service worker dependencies. 
* Enhancement: SuperPWA is now translated to French, thanks to @romainvincent and Spanish, thanks to @arkangel. 
* Bug Fix: Fixed issue where translation files in /languages/ folder was not being loaded. 

= 1.8.1 =
* Enhancement: Added an admin notice with instructions for OneSignal integration. 
* Enhancement: Updated console log message for URLs excluded from cache for better clarity. 

= 1.8 =
* Tested with WordPress 4.9.6. 
* New Add-On: Apple Touch Icons. Set the Application Icon and Splash Screen Icon as Apple Touch Icons for compatibility with iOS devices. 
* Enhancement: Added support for Add to Home Screen prompt for Chrome 68 and beyond. 
* Enhancement: Better add-on activation and deactivation by hooking onto admin_post action. 
* Enhancement: Attempt to generate manifest and service worker automatically on visiting the SuperPWA settings page after adjusting root folder permissions. 
* Enhancement: Generated a .pot file with all strings for translation. You can also translate SuperPWA to your language by visiting translate.wordpress.org/projects/wp-plugins/super-progressive-web-apps 
* Bug Fix: Compatibility issues with OneSignal are resolved for single installs. 
* Bug Fix: Updated plugin action links and admin notices with the correct admin menu link. 

= 1.7.1 =
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context".

= 1.7 =
* Minimum required WordPress version is now 3.6.0 (previously 3.5.0).
* New Feature: Add-Ons for SuperPWA is here!
* New Feature: SuperPWA is now a top-level menu to accommodate for the Add-Ons sub-menu page.
* New Feature: Add UTM Tracking parameters to the Start URL with the [UTM Tracking Add-On](https://superpwa.com/addons/utm-tracking/?utm_source=wordpress.org&utm_medium=upgrade-notice).
* Enhancement: Service worker URLs are now relative to accommodate for domain mapped Multisites.
* Bug Fix: Incorrect start_url when WordPress is installed in a folder, or when inner pages are used as start_url.
* Bug Fix: Incorrect manifest and service worker URLs when WordPress is installed in a folder. 

= 1.6 =
* New Feature: WordPress Multisite Network Compatibility. One of the most requested features for SuperPWA is now here! Thanks [@juslintek](https://wordpress.org/support/topic/add-manifest-json-support-for-multisite/#post-9998629) for doing a major share of the heavy lifting.
* New Feature: Added description to the manifest. You can now include a brief description of what your app is about. 
* Enhancement: Moved manifest to the very top of wp_head for better compatibility with some browsers.
* Enhancement: Improved the file and folder naming, organization, and inline documentation for better readability.
* Enhancement: Force https:// on all assets and dependencies solving the problem of http:// URLs in manifest and service worker once and for all.
* Enhancement: Relative URL for manifest for out of the box compatibility with CDN's.
* Enhancement: Removed forcing of trailing slash on manifest and service worker URLs for better compatibility. 

= 1.5 =
* New Feature: OneSignal integration for Push notifications.
* Enhancement: Moved manifest to the top of wp_head for easier detection by browsers. 
* Enhancement: Added support for custom AMP endpoints for AMP for WordPress and AMP for WP.
* Enhancement: Added UI notice when using AMP for WordPress to warn user not to use the AMP version of start page if the start page is the homepage, the blog index, or the archives page.

= 1.4 =
* New Feature: Added UI for default orientation of your PWA. Orientation can now be set as "any", "portrait" or "landscape".
* New Feature: Added UI for theme_color property in manifest.
* Enhancement: Improved compatibility with all major Accelerated Mobile Pages (AMP) plugins.
* Enhancement: Improved handling of external resources. 

= 1.3.1 =
* Enhancement: Improved how Start Page url is handled in the service worker to be compatible with plugins that force SSL. 
* Enhancement: Improved how start_url is handled in the manifest.
* Enhancement: Better handling of external resources.

= 1.3 =
* New Feature: Added support for high-quality splash screen. You can now set the 512x512 icon for the splash screen of your Progressive Web App.
* Enhancement: Super Progressive Web Apps now accounts for content updates and will update the cache as you update the website. 
* Enhancement: Improved in-browser service worker update handling.
* Enhancement: Added automatic upgrade of manifest and service worker on plugin upgrade.

= 1.2 =
* New Feature: Support for theme-color.
* New Feature: Now you can edit the Application Name and Application Short name.
* New Feature: Set the start page of your PWA.
* New Feature: Set AMP version of the start page. Supported plugins: AMP for WordPress, AMP for WP, Better AMP, AMP Supremacy, WP AMP.
* UI Improvement: Better organization of plugin settings. More intuitive with inline help. 
* UI Improvement: Added admin notice with a link to settings page on plugin activation.
* UI Improvement: Added checks for manifest, service worker and HTTPS and display the status neatly in the UI.
* Bug Fix: Fix a parse error that showed up only on PHP 5.3. 

= 1.1.1 =
* Bug Fix: Fix fatal error in PHP versions prior to PHP 5.5. "Cant use function return value in write context". PHP manual says "Prior to PHP 5.5, empty() only supports variables; anything else will result in a parse error."

= 1.1 =
* New Feature: Aggressive caching of pages using CacheStorage API.
* New Feature: Pages once cached are served even if the user is offline. 
* New Feature: Set custom offline page: Select the page you want the user to see when a page that isn't in the cache is accessed and the user is offline.

= 1.0 =
* First release of the plugin.
