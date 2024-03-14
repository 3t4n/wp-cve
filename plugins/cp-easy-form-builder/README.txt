=== Form Builder CP ===
Contributors: codepeople
Donate link: https://wordpress.dwbooster.com/forms/cp-easy-form-builder
Tags: contact form,contact form plugin,form builder,form to email,emailer,forms,form mailer,form creator,form maker,create form,build form,send email
Requires at least: 3.0.5
Tested up to: 6.4
Stable tag: 1.2.37
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Form Builder CP is a contact form plugin for creating contact forms with a visual form builder and email them.

== Description ==

With Form Builder CP you can:

    - Build a form
    - Use a visual form builder to create a form
    - Receive the form data via email
    - Add validation rules to the form
    - Add captcha anti-spam to the form
    - Style the form
    - Customize the emails

Form Builder CP is a contact form plugin that allows creating **contact forms** and **email** them.

This forms plugin lets you to use its form builder to create contact forms, booking forms, or other types of forms that capture information via your website.

The form builder has a visual interface for creating the **contact form** with **field validation** and anti-spam **captcha** image verification included in all versions. The form builder is as simple as just drag and drop the form fields into the contact form.

This version support three type of form fields: **text fields, email fields and textarea fields**. These fields can be used unlimited times into the same form to create large forms. Most contact forms are based just in those three type of fields. There are other versions of the plugin (commercial versions) that support other field types, info about other versions can be found at https://wordpress.dwbooster.com/forms/cp-easy-form-builder

= Form Builder CP Features: =

* Allows to create contact forms **visually**, with a modern and simple interface
* **Sends** the contact form data to the email addresses that you provide
* Allows including additional user information (IP, user-agent)
* Allows to customize the text of **email messages**, including specific tags for each form field
* Includes **validation** of the contact form data: required fields, emails, dates, number, etc.
* Includes a **built-in captcha** image verification.
* Contact forms are processed using Ajax: more speed and comfort for the user

The contact form is rendered and validated using a modern jQuery script, compatible with mobile pages.

The kernel of the Form Builder CP is its form maker (or form builder). It is 100% JavaScript and supports the basic email, text and comments fields. There are other versions that support more advanced fields. The form maker also allows to specify CSS classes for each form field (read more in the FAQ) or align various form fields in the same row. 

The validations, also integrated in the form builder, cover email form fields, confirmation form fields, length of the texts entered in the form fields, required form fields and other common form validation rules.

The captcha is built 100% into the plugin, there is no need for external captchas or anti-spam services. The captcha image can be visually configured to modify the font, colors, amount of noise and size. The captcha verification is made with Ajax to avoid reloading the page. The captcha configuration section is located below the form builder in the settings area.

= Updates =

New features has been published in the current Form Builder CP version 1.1.4 based on the feedback received and we would like to thank you all the people that have supported the development, provided feedback and feature requests. The plugin is currently over the 30,000 downloads/installations and a new set of updates is already being prepared, any feature requests will be welcome. Thank you!

== Installation ==

To install Form Builder CP, follow these steps:

1.	Download and unzip the Form Builder CP plugin
2.	Upload the entire cp-easy-form-builder/ directory to the /wp-content/plugins/ directory
3.	Activate the Form Builder CP plugin through the Plugins menu in WordPress
4.	Configure the Form Builder CP settings at the administration menu >> Settings >> Form Builder CP
5.	To insert the Form Builder CP into some content or post use the icon that will appear when editing contents

== Frequently Asked Questions ==

= Q: What means each field in the settings area? =

A: The Form Builder CP product's page contains detailed information about each field and customization:

https://wordpress.dwbooster.com/forms/cp-easy-form-builder

= Q: How can I apply CSS styles to the form fields? =

A: Into the form editor (form builder), click a field to edit its details, there is a setting there named "Add CSS Layout Keywords". You can add the class name into that field, so the style specified into the CSS class will be applied to that field.

Note: Don't add style rules directly there but the name of a CSS class.

You can place the CSS class either into the CSS file of your template or into the file "cp-easy-form-builder\css\stylepublic.css" located into the plugin's folder.

= Q: Can I align the form in two or more columns?  =

A: Yes, use the "Add CSS Layout Keywords" field into the form creator for doing that. Into the form creator click a field and into its settings there is one field named "Add Css Layout Keywords". Into that field you can put the name of a CSS class that will be applied to the field.

There are some pre-defined CSS classes to use align two, three or four form fields into the same line. The CSS classes are named:

    column2
    column3
    column4
    
For example if you want to put two form fields into the same line then specify for both form fields the class name "column2".

= Q: Which is the Form Builder CP shortcode for publishing the form? = 

A: This is the Form Builder CP shortcode:

    [CP_EASY_FORM_WILL_APPEAR_HERE]
    
You can paste it in any place into a post/page or directly into the template using the do_shortcode function. In the edition of pages and posts there is a link that inserts the Form Builder CP shortcode into the page/post.


= Q: How to modify the submit button design? = 

A:  The class="pbSubmit" can be used to modify the button styles. 

The styles can be applied into any of the CSS files of your theme or into the CSS file "cp-easy-form-builder\css\stylepublic.css". 

For further modifications the submit button is located at the end of the file "cp_easyform_public_int.inc.php".

= Q: How to edit the submit label? = 

A: There is a new settings box in the Form Builder CP settings named "Submit Button". You can edit the submit button label there and get info about editing its CSS styles.


= Q: Can I display hits on each field with instructions for the user? = 

A: Yes, into the form builder click the field and you will see various options in the "Field Settings" tab. In this case you are interested in the option labeled "Instructions for User".

Type the instruction in the settings field "Instructions for User" and click the checkbox labeled "Show as floating tooltip" below that field, this way the instructions will appear when the field receives the focus (when the user enters to the field to type something).

If the checkbox "Show as floating tooltip" isn't marked then the instructions will appear always displayed immediately below the field in the form.


== Other Notes ==

**If the form doesn't appear:** If the form doesn't appear in the public website that's probable due to a conflict with the theme. The solution in most cases is the following:

1. Edit the file cp_easy_form_builder.php, go to the line #22 where says:

    define('CP_EASYFORM_DEFAULT_DEFER_SCRIPTS_LOADING', false);
    
2. Put that configuration constant to true, example:

    define('CP_EASYFORM_DEFAULT_DEFER_SCRIPTS_LOADING', true);    

That way the scripts with be loaded in a different way that avoid conflicts with third party themes that force their own jQuery versions. This update may solve also conflicts with the form builder in the dashboard area.

**Other Form Builder CP versions:** There is a pro version of the Form Builder CP plugin that also supports these features:

* More form field types in the form builder: upload fields, phone fields, password fields, number fields, date fields, checkboxes, radio buttons, select drop-down fields
* Additional formatting options in the form builder: Section breaks, comment areas.
* Supports multiple forms in the website (max 1 form on each page)
* Automatic file uploads/attachments processing
* Supports tags for specific form fields into the email
* Includes autoreply
* WordPress Multi-site compatible

You can read more details about that version at https://wordpress.dwbooster.com/forms/cp-easy-form-builder


== Screenshots ==

1. Adding fields to the contact form using the form creator
2. Editing fields using the form builder
3. Contact form processing settings
4. Contact form validation settings
5. Inserting a contact form into a page
6. Built-in captcha image anti-spam protection

== Changelog ==

= 1.0 =
* First stable version released.
* More configuration options added.
* fixing tags in wp directory

= 1.0.1 =
* Several bug fixes
* Compatible with latest WP versions

= 1.1.2 =
* Compatible with latest WP versions
* Better interface and access to the plugin options
* Captcha image works better in different server environments
* Minor bug fixes
* Fixed warning that appeared with PHP safe mode restrictions 

= 1.1.3 =
* Compatible with the WordPress 4.2.2 version

= 1.1.4 =
* Optimized submission process
* Fixed bug in the form builder scripts
* Removing use of PHP 4 style class constructors
* Compatible with the latest WordPress version

= 1.1.5 =
* Tested and compatible with WordPress 4.4
* Fixed bug in submission parameter.

= 1.1.6 =
* Tested for WordPress 4.5

= 1.1.7 =
* Script load method modified for issues in WP 4.5

= 1.1.8 =
* Compatible with WP 4.6

= 1.1.82 =
* Default email fix

= 1.1.83 =
* Fix to magic quotes detection

= 1.1.84 =
* Fix to email address format

= 1.1.85 =
* Compatible with WordPress 4.7

= 1.1.86 =
* Support pointed to WP forum
* Optional review banner

= 1.1.87 =
* Fixed issue in review panel

= 1.1.88 =
* Tested and compatible with WordPress 4.8

= 1.1.89 =
* Better validation and captcha color pickers

= 1.1.90 =
* Moved plugin website and links to SSL

= 1.1.91 =
* Compatible with WordPress 4.9

= 1.1.92 =
* Added CSS and JavaScript customization panel

= 1.1.93 =
* Fixed conflict with autoptimize plugin

= 1.1.94 =
* Fixed captcha reloading issue

= 1.1.95 =
* Easier activation process

= 1.1.96 =
* Optional deactivation feedback

= 1.1.97 =
* Fixed bug in activation process

= 1.1.98 =
* Database creating encoding fix 

= 1.1.99 =
* Fixed conflict with Gutemberg editor

= 1.2.01 =
* Fixed magic quotes issue

= 1.2.02 =
* Fixed conflict with third party plugins

= 1.2.03 =
* Form builder fixes

= 1.2.04 =
* Compatible with WordPress 5.0

= 1.2.05 =
* New Gutemberg block

= 1.2.06 =
* Removed use of CURL

= 1.2.07 =
* Compatible with WordPress 5.1

= 1.2.08 =
* Fixed bug in submission process

= 1.2.09 =
* Fixed conflict with lazy loading feature of Jetpack

= 1.2.10 =
* Review link update

= 1.2.11 =
* Compatible with WordPress 5.2

= 1.2.12 =
* Update for compatibility with WordPress 5.2

= 1.2.14 =
* Improved validation

= 1.2.15 =
* Multiple sanitization

= 1.2.16 =
* Captcha key sanitization

= 1.2.17 =
* Fix to database encoding

= 1.2.18 =
* Compatible with WordPress 5.3

= 1.2.19 =
* Compatible with WordPress 5.4

= 1.2.20 =
* Fixed compatibility isue with optimization plugins

= 1.2.21 =
* WP Editor update

= 1.2.22 =
* Compatible with WordPress 5.5

= 1.2.23 =
* Replaced old jQuery codepeople

= 1.2.24 =
* Compatible with WordPress 5.6

= 1.2.25 =
* Compatible with WordPress 5.7

= 1.2.26 =
* CSS fixes

= 1.2.27 =
* Removed non-needed session initialization

= 1.2.28 =
* Compatible with WordPress 5.9

= 1.2.29 =
* PHP 8 update

= 1.2.30 =
* Compatible with WordPress 6.0

= 1.2.31 =
* Validation fix

= 1.2.33 =
* Feedback panel update

= 1.2.34 =
* Compatible with WP 6.1

= 1.2.35 =
* Compatible with WP 6.2

= 1.2.36 =
* WP 6.2 update

= 1.2.37 =
* Compatible with WP 6.4

== Upgrade Notice ==

= 1.2.37 =
* Compatible with WP 6.4