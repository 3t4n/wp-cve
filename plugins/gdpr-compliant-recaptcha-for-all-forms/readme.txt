=== Anti-spam, Spam protection, ReCaptcha for all forms and GDPR-compliant ===
Contributors: MatthiasNordwig
Tags: anti-spam, recaptcha, captcha, spam-protection, gdpr
Requires at least: 4.8+
Tested up to: 6.4.2
Stable tag: 3.6.7
Requires PHP: 5.6+
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Donate link: https://paypal.me/MatthiasNordwig

Anti-spam - CAPTCHA that protects all forms against spam and brute-force. Invisible and GDPR-compliant.


== Description ==

Protect all your forms and logins against spam and brute-force attacks. The plugin is invisible and compliant to GDPR (RGPD, DSGVO). 
It has a lot of options on the one hand and comes with a well balanced default configuration. Thus it starts working very well, as soon as it is activated.

== Setup Guide ==

[vimeo https://player.vimeo.com/video/905897718]

== Key features ==
* Blocks spam on all(!) public forms, comments and logins
* Invisible. No user-input required
* Still receive 100 percent of the real requests
* Compliant to GDPR (respectively DSGVO, RGPD)
* The Plugin is for free
* No tracking, no cookies, no sessions
* No external ressources
* Easy to use
* SEO-friendly
* Only necessary code
* Optionally messages can be flagged instead of blocking them

== Examples Wordpress ==
* Login Form
* Registration Form
* Password Reset Form
* Comments Form

== Examples WooCommerce ==
* Checkout
* Login Form
* Registration Form
* Password Reset Form
* Comments form
* Product Evaluation Form

== Examples other Plugins ==
* Elementor Pro Forms, Contact Form 7, Fluent Forms, Jetpack Forms, Divi Forms, WPForms, Forminator, Thrive Architect & Thrive Apprentice, Gravity Forms, Formidable Forms, Mailchimp for WordPress Forms, BuddyPress Registration Form, bbPress Create Topic & Reply Forms, Ultimate Member Forms, wpDiscuz Custom Comments Form, Easy Digital Downloads Forms, Paid Memberships Pro Forms, MemberPress Forms, WP-Members Forms, WP User Frontend Forms, CheckoutWC & Flux Checkout, Ninja Forms, Everest Forms, Formidable Forms, WS Forms, Quform, Otter Blocks, Typeform, NEX-Forms, Bit Form, Form Maker, Funnelforms, Mailjet, Jotform, Page Builder, Metform, Calculated Fields Form, JetFormBuilder, weForms, Responsive Contact Form Builder, Zoho Forms, Smart Forms, Kali Forms, Happyforms, ApplyOnline, Subscribe Forms, FormCraft, Advanced Forms, CRM Perks Forms, Tripetto, Formstack, BuddyForms, vcita, Easy Form Builder, SimpleForm

== Thank you! ==
I hope you enjoy using the CAPTCHA plugin! If you are happy with it, I would be glad to get your review and probably a coffee too.

== Installation ==
1. Install and activate the plugin via WordPress Plugins page. Done!
2. Optionally: After activation, you can adjust precisely how messages shall be blocked, flagged or saved in plugin's settings menu.
3. You should take a look into the message inbox. As many system functions act like bots, it may happen that they are blocked too. From the inbox and from the spam inbox you can jsut whitelist them with one click respectively.

== Frequently Asked Questions ==
= Submissions are incorrectly treated as spam =
1. The problem occasionally occurs right after installation due to caching. In such cases, the necessary JavaScript for proof-of-work isn't loaded as intended. To resolve this, clear the cache on your webserver (WordPress caching is typically managed by plugins, which offer an option to clear the cache) and in your browser.
2. JavaScript might crash due to incompatibility between this plugin and another one you're using. If you notice this, please report it to me. I usually address such issues within the same day. Additionally, it's crucial to ensure that JavaScript is functioning correctly on all your pages, even without this plugin. In most browsers, you can identify JavaScript errors by pressing F12 on your page and navigating to the console. Here, you can observe what's happening on your page.
3. Generally, I recommend running the plugin in **Explicit mode 🎯** as it's more efficient and avoids compatibility problems. Please refer to the "help" section for this option.
= Neither messages, nore spam is shown in the inbox =
1. Activate the **Analysis mode 🔍**, 
2. Submit the form and look for the message that has been saved for the new submission in the <strong>Analytic Box</strong>
3. Open the message and enhance the scope of the spam to this type of message
4. If the message doesn't appear here, or is already in scope, please give me a note
In general I recommend to run the plugin in the **Explicit mode 🎯** and to do so with all types of submissions that you which to be considered for the spam check.
= Problems with WooCommerce/ Jetpack activation =
If you face problems with the activation of Jetpack this may occur during the handshake-procedure of jetpack. This procedure acts like a bot, when it passes a passphrase from a certain IP adress to an automatically generated form on your site. 
In order to get this fixed, you need either to disable the option **🖥️ Apply on REST-API**, or to whitelist the respective form that is used to exchange the passphrase. 
Usually you need to process the following steps for whitelisting:
1. Check the spam folder for the respective message that has been blocked
2. Copy the site-adress "from_site"
3. Paste the site-adress into the option **📄 Site-Whitelist** on the properties site 
4. Press save
Usually you need to whitelist two different sites to connect jetpack:
1. To connect the site: your-domnain-without-protocol/?rest_route=/jetpack/v4/verify_registration/
2. To connect your user: your-domnain-without-protocol/?rest_route=/jetpack/v4/remote_authorize/
Generally, I recommend running the plugin in **Explicit mode 🎯** as it's more efficient and avoids such compatibility problems. Please refer to the "help" section for this option.
= Problems with activation/ installation of other plugins =
If you face problems with other plugins (i.e. during plugin installation/ activation) this may occur during handshake-procedures, or during maintenance of your plugin from the vendor. These procedures usually act like bots, as they pass a code or contents via certain automatically generated forms on your site.
In order to get this fixed you can either disable the option **🖥️ Apply on REST-API**, or whitelist the IP address of your vendor, or you can whitelist the page which contains the maintenance form. In order to check whether such a problem occurs you can check the spam folder of this plugin. Here you find the site adress that you can use for whitelisting as "from_site" too
Generally, I recommend running the plugin in **Explicit mode 🎯** as it's more efficient and avoids such compatibility problems. Please refer to the "help" section for this option.
= Webhooks from Thrive automation don't work properly when the plugin is activated =
You need to whitelist the respective webhooks ( those which the respective service is using to call your site) with the option **📄 Site-Whitelist**. Do not forget to cut the protocoll (i.e. "http" and "https").
Note: As Thrive doesn't use the standard WordPress-REST-route, just deactivating the option **🖥️ Apply on REST-API** will not work.
Generally, I recommend running the plugin in **Explicit mode 🎯** as it's more efficient and avoids those compatibility problems. Please refer to the "help" section for this option.
= Any Webhooks or API-calls do not work =
You need to whitelist the respective webhooks ( those which the respective service is using to call your site) with the option **📄 Site-Whitelist**. Do not forget to cut the protocoll (i.e. "http" and "https").
Alternatively you can deactivate the option **🖥️ Apply on REST-API** if your services is using the standard WordPress-REST route.
Generally, I recommend running the plugin in **Explicit mode 🎯** as it's more efficient and avoids those compatibility problems. Please refer to the "help" section for this option.
= Problems with Borlabs Script Blocker =
When you use the Borlabs Script Blocker to scan for JavaScripts, the scan doesn't work properly, as it doesn't show any JavaScripts. Just deactivate this plugin for the scan and activate it again after the scan.
= Can't get my problems fixed =
1. Important messages could be shown in browser console (F12) on problematic page
2. Whenever you post something to the support forum, try to hand over all details
3. If the recaptcha doesn't work on any form, give me a notice and I will try to fix that

= How to disable this plugin? =
* Use standard WordPress plugins page for deactivation and deletion of the plugin
* When deactivating the plugin you will be asked for the reason. If you face any problems I would be glad if you report to it me as detailed as possible. Usually I will fix them quickly. If you give me contcat details, I may inform you as soon as it is fixed.

== Changelog ==
= 3.6.7 =
* Fixed: Variables that where not initialized caused warnings on higher debug-levels
= 3.6.6 =
* Problem with forminator and possibly other form builders too fixed: Bots where able to bypassed the pattern matching and thus the spam check too.
= 3.6.5 =
* Fixed: A dedicated spam-check for WordPRess-standard-requests was introduced, in order to treat them differently from other post-requests. It turned out that some spam showed up after the last release. This should not happen anymore
= 3.6.4 =
* Fixed: In v.3.5.5 the plugin was changed to apply the spam check always on WordPress standard submissions such as comments. Even in explicit mode. This behaviour is changed now, in a way that even for WordPress standard submission types patterns have to match, before they are checked for spam.
* This means: If you are using WordPress standard submission-types such as comments and posts, from now on you need to add the respective patterns for them, as for any other type of submission, in order to make the spam check work for them.
= 3.6.3 =
* Fixed Bug with Inboxes
= 3.6.2 =
* Improved performance administration area and inboxes
* Bug with empty pages for inboxes solved
= 3.6.1 =
* Loading error for Direct Analysis Mode fixed
= 3.6 =
* "Direct analysis mode" introduced: This mode allows easier administration of the explicit mode, as froms and submission-types now now can be added directly and life from the forms
* Settings page devided into tabs