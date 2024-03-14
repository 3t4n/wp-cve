=== QR Redirector ===
Contributors: kionae
Donate Link: http://nlb-creations.com/donate/
Tags: qr code, redirection
Requires at least: 3.2.0
Tested up to: 6.1
Stable tag: trunk

QR Redirector lets you create a QR code for a URL on your site, and redirect that URL anywhere.  The result is a reusable QR Code.

== Description ==

QR Redirector lets you set up your own QR Code redirection site.  The plugin creates a new custom post type called QR Redirect, which generates a QR code that points to the post's permalink.  You may then specify any URL you like for the post to redirect to.  Useful if you have an offsite contest, form, newsletter sign-up, etc. You can even change the URL you're redirecting to without having to worry about updating the QR code in your advertising media.

This allows you to continuously reuse your QR codes on printed or linked marketing material... you can change the destination you're sending your users to without ever having to change the artwork you're using to promote it.

This plugin uses the Endroid QR-Code PHP library.

== Installation ==

1. Upload plugin .zip file to the `/wp-content/plugins/` directory and unzip.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add new redirects under the "QR Redirects" menu option.
4. Use the shortcode discussed in the FAQ section to display the code on your site.   

== Frequently Asked Questions ==

= How can I add my QR code to a post? =
Use the following shortcode:

`[qr-code id="xx"]`

where "id" is the post ID of your QR Redirect post.  This shortcode will be generated on the edit page for each of your QR Redirects for you to copy and paste.

= If I need to change the URL I want to redirect to, do I have to update the QR Code image in all of my ads? =

No.  The QR Code points to a Wordpress permalink on your site.  The only time you would ever have to switch out an image is if you change your site's permalink settings, and thus change the permalinks of the QR Redirect posts.  Presumably this is something you won't be doing too often, if ever.

= What happens if I change the size or error correction level? =

A new image with the appropriate settings will be generated and the original image will be removed from your site.  Any shortcodes in use on your site will be automatically updated.  Printed versions of the old image will still function, but if you were hotlinking the original image somewhere outside of your website you will need to update it there.  If you have previously uploaded an old image to another site, rather than hotlinking, it will still function.

= Why do I need this? =

QR Codes on their own are static.  In order to update them, you have to generate a whole new image (which would suck if you were putting them on flyers or some other printed medium and suddenly needed to change them).  This plugin lets you point your QR code's embeded URL to a different web address from the WordPress backend rather than changing your marketing artwork.  

For example, if you are using an off-site service to host a contest, you can point a QR code at that site for the duration of the contest and later change it to point to another page with the contest winners. 

= How are your QR Codes generated? =

Using the Endroid QR-Code library.

For more information, see: https://github.com/endroid/qr-code

= Why did you turn this into a new plugin instead of just updating the old QR Code Redirect plugin? =

Largely because of the new way in which the QR codes are generated.  Users would have had to re-save all of their old QR Redirect posts in order to generate the new images.  This didn't seem very user friendly to me.

Additionally, not everyone may want to host QR images on their own site.  The old plugin will continue to function until Google removes the QR functionality from its Charts API.  I decided that this would be a good way to give people the option of hosting the images themselves, or letting Google do it for them.

= I'm getting a "Page not found" error when I scan my QR Code.  What's wrong? = 

This sometimes happens when first installing the plugin, and occasionally if you have deactivated it and re-activated it.  This issue can be fixed by going to Settings > Permalinks, and clicking the save button to let WordPress know it needs to update its link structure.

== Screenshots ==

1. QR Redirector index screen
2. QR Redirector edit screen
3. QR Redirector edit screen
4. QR Redirector edit screen

== Changelog ==

= 2.0.2 =
* Verifying 6.1 compatibility.
* Fixed some minor PHP warnings.
* Added QR Codes to the At a Glance pane in the WP dashboard.

= 2.0.1 =
* fixed an issue with image file not updating in the browser after changes are made

= 2.0 =
* migrated QR generator library to use Endoird QR-code instead of PHP QR Code (which is no longer in active development)
* added the ability to change the color of the QR Code's foreground and background
* added the ability to make the QR Code's background transparent
* changed the size settings (this was neccessary due the changing QR libraries)
* general code cleanup and documentation

= 1.6.3 =
* fixed conflict with the Quick Edit script and non-qrcode Quick Edit links in the WordPress dashboard
* added the ability to clear the redirect count for a QR code from the edit page

= 1.6.2 =
* added a check to prevent infinite redirect loop in situations
* added code to prevent browsers from caching the redirect url
* added QR code download link on the dashboard QR Redirects index page 

= 1.6.1 =
* Security Fix: Added sanitization to admin notes field before save

= 1.6 =
* Security Fix: Added security checks for bulk edit of QR Code
* Security Fix: Added sanitization to URL string before save

= 1.5 =
* Added the ability to use quick edit and bulk edit on the HTTP Response Code field
* Minor code cleanup

= 1.4 =
* Verified compatibility with latest WordPress release
* Fixed multisite compatibility
* Added ability to set the HTTP Status Code of the redirect
* Some minor cosmetic changes

= 1.3 =
* Verified compatibility with latest WordPress release
* Some minor cosmetic changes
* Removed margin around generated QR code images
* Added tooltips

= 1.2 =
* Fixed issue for people who have changed the default name of their wp-content directory.
* Minor fixes for Wordpress 3.7 compatibility

= 1.1 =
* Fixed an issue that was preventing the QR Redirector menu option from displaying when certain plugins were activated.

= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.0.2 =
* Verifying 6.1 compatibility.
* Fixed some minor PHP warnings.
* Added QR Codes to the At a Glance pane in the WP dashboard.

= 2.0.1 =
* fixed an issue with image file not updating in the browser after changes are made

= 2.0 =
* migrated QR generator library to use Endoird QR-code instead of PHP QR Code (which is no longer in active development)
* added the ability to change the color of the QR Code's foreground and background
* added the ability to make the QR Code's background transparent
* changed the size settings (this was neccessary due the changing QR libraries)
* general code cleanup and documentation

= 1.6.3 =
* fixed conflict with the Quick Edit script and non-qrcode Quick Edit links in the WordPress dashboard
* added the ability to clear the redirect count for a QR code from the edit page

= 1.6.2 =
* added a check to prevent infinite redirect loop in situations
* added code to prevent browsers from caching the redirect url
* added QR code download link on the dashboard QR Redirects index page 

= 1.6.1 =
* Security Fix: Added sanitization to admin notes field before save

= 1.6 =
* Security Fix: Added security checks for bulk edit of QR Code
* Security Fix: Added sanitization to URL string before save

= 1.5 =
* Added the ability to use quick edit and bulk edit on the HTTP Response Code field
* Minor code cleanup

= 1.4 =
* Verified compatibility with latest WordPress release
* Fixed multisite compatibility
* Added ability to set the HTTP Status Code of the redirect
* Some minor cosmetic changes

= 1.3 =
* Verified compatibility with latest WordPress release
* Some minor cosmetic changes
* Removed margin around generated QR code images
* Added tooltips

= 1.2 =
* Fixed issue for people who have changed the default name of their wp-content directory.
* Minor fixes of PHP warnings and for Wordpress 3.7 compatibility

= 1.1 =
* Fixed an issue that was preventing the QR Redirector menu option from displaying when certain plugins were activated.

= 1.0 =
* Initial release