=== Dilli Email Validator ===
Contributors: dillilabs
Tags: email validation,email address validation,email validator,mail validation,gmail validation,verify email address,validate email address,spam,comments,verification,validation,anti-spam,contact form 7,jetpack,grunion,contact form,ninja form,spam form submission,validate contact form,lead validation,fake email,disposable,temporary,role-based,mx,business email address
Requires at least: 3.6.0
Requires PHP: 5.2.4
Tested up to: 6.4.2
Stable tag: 1.6.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds advanced email address validation to forms using [Dilli Email Validation API](https://www.dillilabs.com/products/email-validation-api/). Prevents typos in email address field and eliminates spam submissions with fake email addresses.

== Description ==

Add advanced email address validation using Dilli Email Validation API (DEVA). Prevent a site visitor from submitting a form with fake or incorrect email address on your site. No more lost leads due to mis-typed email addresses. No more wasting your precious time reading and responding to spam leads with incorrect email addresses. It hooks into the Wordpress core function that is used by most form plugins to validate email address. As a result, it works with most forms.

Learn more about [Dilli Email Validation API](https://www.dillilabs.com/products/email-validation-api/). This plugin requires an API Key which can be obtained for FREE by [signing up here](https://deva.dillilabs.com/register).

= Known Compatible Forms =

* Wordpress registration
* Contact Form 7
* Gravity Forms
* Ninja Forms
* Jetpack/Grunion contact forms
* Any other forms that uses `is_email($email)` Wordpress core function to validate email address field.

= Available Languages =

* English
* German (Deutsche)

= Features =

* Validates email address format. Ex: email address `fooAtdillilabs.com` is invalid because of missing '@'
* Checks for existence of MX records of the email address domain. Ex: `foobar@dlfkdlfkf.co` is invalid because no MX records exist for domain dlfkdlfkf.co.
* Checks for Disposable/Temporary email addresses. Ex: user@mailinator.com is invalid because mailinator.com provides temporary shared temporary email inboxes.
* Checks for conformity with ESP (Email Service Provider) grammar rules. Ex: `bob@yahoo.com` is invalid because Yahoo does not allow user part (`bob` in this case) to be less than 4 characters.
* Checks for profanity in user part of email address. Ex: `f***you@gmail.com` is invalid.
* Checks user and domain parts of an email address for known malicious patterns. Ex: `jondoe@gmail.com`, `foobar@yahoo.com`, `idontwanttogive@gmail.com` and `noemail@gmail.com` will be treated as invalid.
* Checks email address domain against a known blacklist.
* Checks email address against a known blacklist of frequently used email addresses for spam.
* Checks for reserved domains. Ex: example.com is a reserved domain. Therefore, validemail@example.com is not allowed.
* Checks for Role-based email addresses. Ex: info@someorganization.com will be treated as invalid.
* Checks for safe domains. Restricts emails whose domains represents sites with adult content.
* Checks for non-business email address.  Ex: *@gmail.com, *@yahoo.com, *@hotmail.com email addresses will be treated as invalid.
* Add custom blocklist. You may request certain domains, emails and users to be blocked. 

== Installation ==

1. Unzip and upload the `dilli-email-validator` folder to the `/wp-content/plugins/` directory.
2. Activate the <strong>Dilli Email Validator</strong> plugin through the 'Plugins' page in WordPress.
3. Configure the plugin by going to `Settings > Email Validation` page.
4. Get API Key by signing up with [Dilli Email Validation API](https://www.dillilabs.com/products/email-validation-api/).
5. Paste your API key in WP-ADMIN at `Settings > Email Validation` page, verify it and then Save changes.

== Frequently Asked Questions ==

= Where do I obtain the API key? =

Register instantly for FREE with [Dilli Email Validation API](https://deva.dillilabs.com/register) to receive your API key

= Is there a dashboard where I can track valid and invalid emails? =

Yes, you may track it [here](https://deva.dillilabs.com). The dashboard URL is also accessible from within the Wordpress dashboard on Settings->Email Validation page.

= Will this work with Contact Form 7? =

Yes.

= Will this work with Ninja Form? =

Yes.

= Will this work with Gravity Form? =

Yes.

= Will this work with Jetpack/Grunion Form? =

Yes.

= Will this work with Elementor Form? =

Yes, if you have Elementor Pro.

= Can I use the same API key on different websites ? =

Yes.

= How can I whitelist certain email addresses ? =

You can do so under plugin settings. See 'Whitelist' field.

= Can I add a custom black list of email addresses? = 

Yes. Just e-mail us the e-mails you want blocked at support@dillilabs.com.

== Screenshots ==

1. Configuration under WP-Admin Dashboard->Settings->Email Validation
2. Email field validation in Contact Form 7 using Dilli Email Validator.
