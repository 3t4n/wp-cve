=== QuickEmailVerification ===
Contributors: quickemailverification
Tags: email verification, form validation, email checker, email validation, disposable email, accept-all checker, role email, email verifier
Requires at least: 4.6
Tested up to: 6.3.2
Stable tag: 1.9.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The QuickEmailVerification email verification plugin to avoid fake, bad and nonexistent emails.

== Description ==

An email verification plugin by [QuickEmailVerification](http://quickemailverification.com) enables you to validate and verify the email address existence in real time, before you actually send them an email. Its unique email verification system is composed of multiple different validations starting from syntax checking to the end users' mailbox existence checking. It not only detects invalid email addresses but also detects many other types of poor email addresses which could negatively impact the email deliverability and harm your sender reputation.

This plugin need a QuickEmailVerification API key to verify. You can get it from [API settings](https://quickemailverification.com/apisettings "Get your API key") page of QuickEmailVerification service.

= Key features =

* Email address syntax checking
* Domain/MX records checking
* Role address detection
* Disposable email address (DEA) detection
* Free email service detection
* Typo detection and suggestion
* Accept all email checking
* Mailbox existence checking

= Supported form Plugins =

* Contact Form 7
* Formidable forms
* Ninja forms
* WooCommerce
* WPForms
* Profile Builder
* Contact Form by BestWebSoft
* Ultimate Member registration form
* Fluent Forms
* WPEverest forms
* MailPoet
* Theme My Login
* WP-Members
* Visual Form Builder
* PlanSo forms
* Buddypress registration form
* Any other form which uses the is_email() function

This plugin works either with "is_email()" function or with any other supported form plugins. So if any supported form plugin is enabled, "is_email()" hook will automatically be disabled.

To know more about email verification results, please have a look at the [QuickEmailVerification knowledge base](http://docs.quickemailverification.com/getting-started/understanding-email-verification-result).

== Installation ==

1. Click on the **Plugins** from left menu
2. Click on the **Add New** button given on the top
3. Search for "QuickEmailVerification" in the plugin search bar and click **Install Now** once the QuickEmailVerification appears in the search result
4. Now the plugin is installed. Click on the **Activate** link to activate it.
5. Get your [QuickEmailVerification API key](https://quickemailverification.com/apisettings "QuickEmailVerification API key") to start with the plugin
6. Click on the **QuickEmailVerification** option from the left side **Settings** menu
7. On the settings page, paste the API key. You can also change other settings like emails to exclude, customized error message, form plugins to hook with QuickEmailVerification plugin etc.

== Frequently Asked Questions ==

= How does it work? =

Once the QuickEmailVerification plugin is installed and activated, it will automatically detect and verify email fields present on the form when the form is submitted. If the email verification results are not as per your allowed result values, it will show an error message to the person entering the email address. When the email provided by the user is acceptable, form submission will proceed normally.

= What happens when free credits are exceeded? =

When your account is running out of credits, the QuickEmailVerification plugin will skip the verification. To avoid any disruption to the service, we recommend to add persistent credits to your QuickEmailVerification account which does not have any expiry attached and can be used any time. We also recommend, you subscribe to low credit email alerts for your QuickEmailVerification account [here](https://quickemailverification.com/settings "Account settings").

= Which forms does this plugin supports? =

Currently, the QuickEmailVerification plugin supports following form plugins.

Contact Form 7
Formidable forms
Ninja forms
WooCommerce
WPForms
Profile Builder
Contact Form by BestWebSoft
Ultimate Member registration form
Fluent Forms
WPEverest Forms
Theme My Login
WP-Members
Visual Form Builder
PlanSo forms
Buddypress registration form

Apart from this, the QuickEmailVerification plugin also extends the basic syntax validation done by is_email() function so it supports many more form plugin which are using is_email() function.

= Does it verify accept all emails as well? =

Yes, the QuickEmailVerification plugin is able to detect 'accept_all' emails with highest accuracy.

= Do I need an API key to use this plugin? =

Yes, you need an API key to use the QuickEmailVerification plugin. Creating an API key is very easy. Just [sign up](https://quickemailverification.com/register "QuickEmailVerification sign up") to the QuickEmailVerification for free and get your [API key](https://quickemailverification.com/apisettings).

= How do I change the default behavior of the plugin? =

You can customize the behavior of the QuickEmailVerification plugin by changing its settings. From the provided list, select the form plugin which you want to hook with QuickEmailVerification plugin. By default, plugin will not accept emails which has been marked invalid or disposable. But you can change this default setting by changing the value of "Results to Exclude" checkboxes.

During plugin setup, sometimes you may want to generate the log of QuickEmailVerification API results. With the plugin settings, there is a radio button to ON or OFF the API result "Debug Log". We recommend to always keep it OFF for production.

= Unable to find your answer here? =

Send us an email at support@quickemailverification.com if you have any query or need any assistance. We will be happy to help!

== Screenshots ==

1. Setting page for QuickEmailVerification Email Validator. Here you can add your QuickEmailVerification API Key and select which email addresses you want to exclude from Role, Disposable and Accept All.
2. Example of Contact Form 7 email validation.
3. Example of Ninja form email validation.
4. Example of Formidable Forms email validation.
5. Example of Contact Form by BestWebSoft email validation.
6. Example of Profile Builder email validation.
7. Example of Contact Form by WPForms.
8. Example of MailPoet plugin.
9. Example of Ultimate member plugin.
10. Example of PlanSo Forms.
11. Example of Buddypress registration form

== Changelog ==
= 1.9.0 =
- Update versin to 1.9.0

= 1.8.0 =
- Added support for Buddypress registration form plugin

= 1.7.0 =
- Update versin to 1.7.0

= 1.6.0 =
- Update email validation

= 1.5.0 =
- Added support for WPEverest Forms plugin

= 1.4.0 =
- Added support for Fluent Forms plugin

= 1.3.1 =
- Minor Improvements

= 1.3.0 =
- Added support for WooCommerce plugin

= 1.2.0 =
- Added feedback feature when deactivate the plugin
- Show remaining credits in the plugin setting page

= 1.1.0 =
- Added support for Ultimate Member form and WPForms plugin.
- Revised support for plugins with is_email().
