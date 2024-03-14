=== Advanced Form Integration - Connect WooCommerce and Contact Form 7 to Google Sheets and other platforms  ===
Contributors: nasirahmed, freemius
Tags: Contact Form 7, WooCommerce, Google Calendar, Google Sheets, Pipedrive, active campaign, AWeber, campaign monitor, close.io, convertkit, curated, directiq, drip, emailoctopus, freshsales, getresponse, google sheets, jumplead, klaviyo, liondesk, mailerlite, mailify, mailjet, moonmail, moosend, omnisend, Sendinblue
Requires at least: 3.0.1
Tested up to: 6.4
Stable tag: 6.4
Requires PHP: 5.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send WooCommerce order data & Contact Form 7 submissions to many other platforms.

== DESCRIPTION ==

AFI is a simple tool that links your website forms to various other platforms. It can connect with email marketing, CRM, spreadsheets, task management, and various software. When someone fills out a form, AFI makes sure the information goes to these other programs. AFI isn't just for forms; it can also connect with other plugins like WooCommerce, LearnDash, GiveWP, etc.

* **Easy to use**: The plugin was created, keeping not-tech people in mind. Setting up new integrations is a breeze and can be accomplished within minutes. No coding skill is required, almost no learning curve.

* **Flexible**: Integrations can be created from any sender platform to any receiver platform. You can create as many connections as you want—single sender to multiple receivers, multiple senders to a single receiver, multiple senders to multiple receivers. Just keep in mind that all PHP server has a maximum execution time allowed.

* **Conditional Logic**: You can create single or multiple conditional logic to filter the data flow. Submitted data will only be sent if the conditions match. For example, when you want to send contact data only if the user has agreed and filed the checkbox "I agree" (Contact Form 7 acceptance field) or if the city is only New York or the subject contacts the word "Lead," etc. You can set up the conditions as you like.

* **Special Tags**: We have introduced several special tags that can be passed to receiver platforms. These are helpful when you want some more information that the user submitted, like IP address, user agent, etc. Example: `{{_date}}` same format and timezone that is saved in WordPress settings, `{{_time}}` same format and timezone that is saved in WordPress settings, `{{_weekday}}` weekday like Monday, Tuesday, etc., `{{_user_ip}}`, `{{_user_agent}}`, `{{_site_title}}`, `{{_site_description}}`, `{{_site_url}}`, `{{_site_admin_email}}`, `{{_post_id}}`, `{{_post_name}}`, `{{_post_title}}`, `{{_post_url}}`, `{{_user_id}}`, `{{_user_first_name}}`, `{{_user_last_name}}`, `{{_user_last_name}}`, `{{_user_email}}`.

* **Job Queue**: Leverage the proven reliability of [Action Scheduler](https://actionscheduler.org) for seamless background processing of extensive task queues within WordPress. Activate this functionality in AFI settings to improve the submission process and ensure a smooth user experience.

* **Multisite**: Multisite supported.

* **Log**: A powerful log feature with an edit and resend function. If somethings goes wrong on a submission, the admin can go to the log, edit/correct the data and resend it.

[youtube https://youtu.be/iU0YmEks84Q]

[**[Website](https://advancedformintegration.com/)**]   [**[Documentation](https://advancedformintegration.com/docs/afi/)**]   [**[Tutorial Videos](https://www.youtube.com/channel/UCyl43pLFvAi6JOMV-eMJUbA)**] [**[Comparison](https://advancedformintegration.com/pricing/)**]

### SENDER PLATFORMS (TRIGGER) ###

The following plugins work as a sender platform.

*  **[Academy LMS](https://wordpress.org/plugins/academy/)**: Triggers when students enroll a new course, complete a lesson.

*  **[AffiliateWP](https://affiliatewp.com/)**: Triggers on different AffiliateWP events.

* **[Amelia Booking](https://wordpress.org/plugins/ameliabooking/)**: Triggers when an appointment or event is booked. Works with both free and paid versions of Amelia. Amelia Pro also triggers on changes to the booking status, reschedules, or cancellations.

* **[ARForms](https://wordpress.org/plugins/arforms-form-builder/)**: Basic fields support.

* **[Beaver Builder Form](https://www.wpbeaverbuilder.com/)**: Triggers on form submission.

* **[BuddyBoss](https://www.buddyboss.com/)**: Triggers on various BuddyBoss events like firendship requested or accepted, new topic or reply, join or leave group, membership requested or accepted and more.

* **[Contact Form 7](https://advancedformintegration.com/docs/afi/sender-platforms/contact-form-7/)**: Any contact form created using Contact Form 7 can be integrated. So when a user submits the form, data will be sent to connected platforms.

* **[Caldera Forms](https://advancedformintegration.com/docs/afi/sender-platforms/caldera-forms/)**: Basic fields support.

* **[Divi Forms](https://www.elegantthemes.com/gallery/divi/)**: Basic fields support.

* **[Elementor Pro Form](https://advancedformintegration.com/docs/afi/sender-platforms/elementor-pro-form/)**: Basic fields support.

* **[Everest Forms](https://advancedformintegration.com/docs/afi/sender-platforms/everest-forms/)**: Basic fields support.

* **[Fluent Forms](https://advancedformintegration.com/docs/afi/sender-platforms/wp-fluent-forms/)**: Basic fields support.

* **[FormCraft](https://advancedformintegration.com/docs/afi/sender-platforms/formcraft/)**: Basic fields support.

* **[Formidable Forms](https://advancedformintegration.com/docs/afi/sender-platforms/formidable-forms/)**: Basic fields support.

* **[Forminator (Forms only)](https://advancedformintegration.com/docs/afi/sender-platforms/forminator/)**: Basic fields support.

* **[GiveWP](https://wordpress.org/plugins/give/)**: Triggers on several events in GiveWP like new donation, cancel recurring, etc.

* **[Gravity Forms](https://advancedformintegration.com/docs/afi/sender-platforms/gravity-forms/)**: Basic fields support.

* **[Happyforms](https://advancedformintegration.com/docs/afi/sender-platforms/happy-forms/)**: Basic fields support.

* **[Kadence Blocks Form](https://www.kadencewp.com/kadence-blocks/)**: Triggers on submission.

* **[LearnDash](https://www.learndash.com/)**: Triggers on several events in LearnDash like enroll, unenroll, lesson complete or quiz attempt.

* **[LifterLMS](https://wordpress.org/plugins/lifterlms/)**: Triggers on several events in LifterLMS like enroll, unenroll, lesson complete or quiz attempt, pass, fail, etc.

* **[Live Forms](https://wordpress.org/plugins/liveforms/)**: Basic fields support.

* **[Metform](https://wordpress.org/plugins/metform/)**: Basic fields support.

* **[Ninja Forms](https://advancedformintegration.com/docs/afi/sender-platforms/ninja-forms/)**: Basic fields support.

* **[TutorLMS](https://wordpress.org/plugins/tutor/)**: Triggers on enroll, lesson complete, course complete, quiz attempt.

* **[QuForm](https://advancedformintegration.com/docs/afi/sender-platforms/quform/)**: Basic fields support.

* **[Smart Forms](https://advancedformintegration.com/docs/afi/sender-platforms/smart-forms/)**: Basic fields support.

* **[weForms](https://advancedformintegration.com/docs/afi/sender-platforms/weforms/)**: Basic fields support.

* **[WPForms](https://advancedformintegration.com/docs/afi/sender-platforms/wpforms/)**: Basic fields support.

* **[Gravity Forms](https://advancedformintegration.com/docs/afi/sender-platforms/gravity-forms/)**: Basic fields support.

* **[Gravity Forms](https://advancedformintegration.com/docs/afi/sender-platforms/gravity-forms/)**: Basic fields support.



*  **[WooCommerce](https://advancedformintegration.com/docs/afi/sender-platforms/woocommerce/)**: When a new order is placed or the order status is processing/on-hold/completed/failed/pending/refunded/canceled in a WooCommerce shop, the order data can be sent to connected platforms. Go to the Documentation page to see a list of order data that are sent.
In addition to regular order fields, you can also use WooCommerce additional checkout fields. For example, to send fields created by the [Checkout Field Editor](https://wordpress.org/plugins/woo-checkout-field-editor-pro/) plugin,  you have to add the value of the name attribute of an input field manually as a tag. For example, in the checkout page, you’ve added an extra input field for Interests and its name attribute is “interests”, then add a tag {{interests}} while creating the integration. The plugin should parse the value from user input and send it to the connected platform field. You can also send order item meta like color, size or any custom product field using {{itemmeta_[productfieldname]}} tag. See the documentation for more details. 





*  **[UTM Parameters](https://advancedformintegration.com/docs/afi/sender-platforms/utm-parameters/)**: You can also grab and send UTM variables. Just activate the feature from the plugin's settings page. Now use tags like {{utm_source}}, {{utm_medium}}, {{utm_term}}, {{utm_content}}, {{utm_campaign}}, {{gclid}}, etc.

<blockquote>
<p><strong>Premium Version Features.</strong></p>
<ul>
<li>All form fields</li>
<li>Inbound Webhooks</li>
</ul>
</blockquote>

### RECEIVER PLATFORMS (ACTION) ###

*  **[Acelle Mail](https://acellemail.com/)** - Creates contact and adds to a list. Requires a pro license to use custom fields and tags.

*  **[ActiveCampaign](https://advancedformintegration.com/docs/afi/receiver-platforms/activecampaign/)** - ActiveCampaign is a popular email marketing and automation platform. This plugin allows you to integrate it with any sender platform, so when a user submits the form with personal details, it will automatically create a contact in ActiveCampaign. The contact can be added to a list or automation. Additionally, deals and notes can be created, too, for that contact. Requires a pro license to use custom fields.

*  **[Agile CRM](https://www.agilecrm.com/)** - This plugin allows creating contact, deal and note. Requires a pro license to use tags and custom fields.

*  **[Airtable](https://airtable.com/)** - Creates new row to selected table.

*  **[Asana](https://www.asana.com/)** - Allows to create a new task. Custom fields are support in the AFI Pro version.

*  **[Autopilot](https://journeys.autopilotapp.com/)** - This plugin allows creating/updating contact and adding to a list. Requires a pro license to use custom fields.

*  **[AWeber](https://www.aweber.com/)** - Allows to create contact and subscribe to a list. A Pro license is required to use custom fields and tags.

*  **[beehiiv](https://www.beehiiv.com/)** - Create new subscriber to a selected publiction.

*  **[Benchmark Email](https://www.benchmarkemail.com/)** - Allows to create contact and subscribe to a list. A Pro license is required to use custom fields.

*  **[Campaign Monitor](https://www.campaignmonitor.com/)** - Allows to create contact and subscribe to a list. A Pro license is required to use custom fields.

*  **[Capsule CRM](https://capsulecrm.com/)** - Supports adding party, opportunity, case, and task. Requires the Pro version to add tags, and custom fields.

*  **[ClinchPad CRM](https://clinchpad.com/)** - Creates new Lead including organization, contact, note, product, etc.

*  **[Close CRM](https://close.com/)** - Close is the inside sales CRM of choice for startups and SMBs. You can add a new lead and contact to Close CRM. The Pro version supports custom fields.

*  **[CompanyHub](https://www.companyhub.com/)** - Creates basic contact.

*  **[Constant Contact](https://www.constantcontact.com/)** - Allows you to create new contacts and subscribe to a list. A Pro license is required to use custom fields and tags.

*  **[ConvertKit](https://convertkit.com/)** - ConvetKit is another popular email marketing software. This plugin allows you to create a new contact and subscribe to a sequence or form. A Pro license is required to use custom fields and tags.

*  **[Copper CRM](https://www.copper.com/)** - This allows you to create a new company, person, and deal in Copper CRM. The Pro version supports custom fields and tags.

*  **[ClickUp](https://clickup.com/)** - Create tasks. Requires a Pro license to add tags and custom fields.

*  **[Curated](https://curated.co/)** - Add subscriber.

*  **[Demio](https://www.demio.com/)** - Register people to webinar.

*  **[DirectIQ](https://www.directiq.com/)** - Allows you to create contact and add to the mailing list.

*  **[Drip](https://www.drip.com/)** - Create Contact (Basic Fields), add to Campaign, Workflow. The Pro version supports custom fields.

*  **[EasySendy](https://www.easysendy.com/)** - This allows you to create contact and add them to the mailing list. Requires a Pro license to use custom fields.

*  **[Elastic Email](https://elasticemail.com/)** - Elastic Email is a marketing platform built on the most cost-effective delivery engine. You can create a contact and add it to a mailing list. A Pro license is required to use custom fields.

*  **[EmailOctopus](https://emailoctopus.com/)** - Allows you to add contact and subscribe to a list. A Pro license is required to use custom fields.

*  **[EngageBay](https://engagebay.com/)** - Create new contact and subscribe to a list. A Pro license is required to use custom fields.

*  **[EverWebinar](https://home.everwebinar.com/index)** - Add registrant to webinar.

*  **[Freshworks CRM (Freshsales)](https://www.freshworks.com/crm/sales/)** - Freshworks CRM is a full-fledged Sales CRM software for your business. This plugin allows you to create accounts, contact, and deals with custom fields.

*  **[GetResponse](https://www.getresponse.com/)** - GetResponse is a powerful, simplified tool to send emails, create pages, and automate your marketing. This plugin allows you to create a subscriber and add it to the mailing list The Pro version supports custom fields and tags.

*  **[Google Calendar](https://calendar.google.com)** - Allows you to create a new event on a selected Google Calendar with supplied data.

*  **[Google Sheets](https://seheets.google.com)** - When a sender form is submitted, or a WooCommerce order is created, this plugin allows you to create a new row on a selected sheet with supplied data. In the Pro version, it is allowed to create separate rows for WooCommerce Order Items. For example, if an order has 5 items so 5 separate rows will be created for each item.

*  **[Hubspot CRM](https://www.hubspot.com/)** - Allows you to create a new contact in Hubspot CRM with additional custom fields support. The AFI Pro supports creating companies, deals, tickets, tasks, etc.

*  **[Insightly](https://www.insightly.com/)** - Create New organisation, contact and opportunity with basic fields. The Pro plugin supports custom fields and tags.

*  **[Jumplead](https://jumplead.com/)** - Jumplead offers a full all-in-one inbound marketing automation platform. This plugin allows adding a contact to it.

*  **[Klaviyo](https://www.klaviyo.com/)** - Klaviyo is an email marketing platform created for online businesses — featuring powerful email and SMS marketing automation. Using Advanced Form Integration, you can add a contact, and subscribe to a list. Pro license is required to use custom properties.

*  **[lemlist](https://lemlist.com/)** - A cold email tool powering sales teams, agencies, and B2B businesses to personalize and automate outreach campaigns. This plugin allows creating contact and adds it to a campaign.

*  **[LionDesk](https://www.liondesk.com/)** - LionDesk offers sales and marketing automation for Real Estate Agents and Brokers. Creating a new contact is supported using our plugin. In the Pro version, you can add tags and custom fields.

*  **[Livestorm](https://livestorm.co/)** - Add people to event session.

*  **[MailBluster](https://mailbluster.com/)** - Creates new lead. Requires Pro license to use Custom fields, and tags.

*  **[Mailchimp](https://mailchimp.com/)** - Allows you to create contacts, subscribe to a list and group, and unsubscribe from the list. Requires Pro license to use Custom|Merge fields, and tags.

*  **[Mailercloud](https://www.mailercloud.com/)** - Adds new subscribers to a selected lists. Requires a Pro license to use custom fields.

*  **[MailerLite](https://www.mailerlite.com/)** - Allows you to add contact and subscribe to a group. Requires a Pro license to use custom fields.

*  **[MailerLite Classic](https://www.mailerlite.com/)** - Allows you to add contact and subscribe to a group. Requires a Pro license to use custom fields.

*  **[Mailify](https://www.mailify.com/)** - Mailify is a email marketing solution. This plugin allows you to create contacts and subscribe to lists. Requires a Pro license to use custom fields.

*  **[Mailjet](https://www.mailjet.com/)** - Allows you to create a contact and add it to a list. Requires a Pro license to use custom fields.

*  **[MailWizz](https://www.mailwizz.com/)** - Create contact and add to a list. The Pro plugin supports custom fields.

*  **[Mautic](https://www.mautic.org/)** - Allows you to create a contact. Requires a Pro license to use custom fields.

*  **[Moosend](https://moosend.com/)** - Allows you to create a contact and add it to a list. Requires a Pro license to use custom fields.

*  **[Omnisend](https://www.omnisend.com/)** - Create new contacts. Requires pro license to use custom fields and tags.

*  **[Onehash.ai](https://www.onehash.ai/)** - The plugin allows you to create new leads, contacts, and customers.

*  **[Ortto](https://ortto.com/)** - Allows creating contact. Requires a pro license to use tags and custom fields.

*  **[Pabbly Email Marketing](https://www.pabbly.com//)** - Allows you to create a subscriber and add it to a list. Requires a Pro license to use custom fields.

*  **[Pipedrive](https://www.pipedrive.com/)** - This plugin allows you to create organizations, people, deals, notes, activity with custom fields support. Requires a Pro license to add new lead.

*  **[Pushover](https://pushover.net/)** - Allows you to send push messages to Android/iOS/Desktop.

*  **[Robly](https://robly.com/)** - Add/update new subscriber. Requires a Pro license to use custom fields and tags.

*  **[Sales.Rocks](https://sales.rocks/)** - Allows you to add contact and subscribe to a list.

*  **[Salesflare](https://salesflare.com/)** - Allows you to create organization, contact, opportunity and task.

*  **[Selzy](https://selzy.com/)** - Create new contact and subscribe to a list. The Pro version supports custom fields and tags.

*  **[SendFox](https://sendfox.com/)** - Allows you to create contact and subscribe to a list. Custom fields can be added in the Pro version.

*  **[SendPulse](https://sendpulse.com/)** - Allows you to create contact and subscribe to a list.

*  **[Brevo (Sendinblue)](https://www.brevo.com/)** - Brevo (formerly Sendinblue) is a complete all-in-one digital marketing toolbox. Our plugin allows you to create subscribers and add them to a list. A Pro license is required to use custom fields and other languages.

*  **[SendX](https://www.sendx.io/)** - Allows you to create new contact.

*  **[Sendy](https://sendy.co/)** - Allows creating contact and subscribe to a list. A Pro license is required to use custom fields.

*  **[Slack](https://slack.com/)** - Allows sending channel message.

*  **[Smartsheet](https://smartsheet.com/)** - Allows creating new row.

*  **[Trello](https://www.trello.com/)** - This plugin allows you to create a new card in Trello.

*  **[Twilio](https://www.twilio.com/)** - This plugin allows you to send customized SMS using Twilio.

*  **[Vertical Response](https://verticalresponse.com/)** - This plugin allows creating contacts in a certain list. Requires a pro license to use custom fields.

*  **[Wealthbox CRM](https://www.wealthbox.com/)** - This plugin allows creating contacts. Requires a pro license to use tags and custom fields.

*  **Webhook** - Allows you to send data to any webhook URL. In the Pro version, you can send fully customized headers and body (GET, POST, PUT, DELETE), literally can send data to any API with API token and Basic auth.

*  **[WebinarJam](https://home.webinarjam.com/index)** - Add registrant to webinar.

*  **[Woodpecker.co](https://woodpecker.co/)** - Allows creating subscriber. Requires Pro license to use custom fields.

*  **WordPress** - Create new post.

*  **[Zapier](https://zapier.com/)** - Sends data to Zapier webhook.

*  **[Zoho Campaigns](https://www.zoho.com/campaigns/)** - Allows creating subscribers and adding to a list. Requires Pro license to use custom fields.

*  **[Zoho Bigin](https://bigin.com/)** - Allows creating Contacts, Companies, Pipelines, Tasks, Notes, etc. Requires Pro license to use custom fields.

*  **[Zoho CRM](https://www.zoho.com/crm/)** - Allows creating Leads, Contacts, Accounts, Deals, Tasks, Meetings, Calls, Products, Campaigns, Vendors, Cases and Solution . Requires Pro license to use custom fields.

*  **[Zoho Sheet](https://www.zoho.com/sheet/)** - Creates a new row on selected worksheet.

### SOME VIDEOS ON HOW TO USE THE PLUGIN ###

= Create Google API project =
[youtube https://youtu.be/VJIHgJkyyCM]

= Connect Contact Form 7 to google sheets =
[youtube https://youtu.be/-xTMz58j00k]

= Connect woocommerce new order to google sheets =
[youtube https://youtu.be/zDGNSuqYHA4]

= Connect Contact Form 7 to Agile CRM =
[youtube https://youtu.be/7QU5gt0Rpps]

= Connect Contact Form 7 to Campaign Monitor =
[youtube https://youtu.be/d60Z25oq0ns]

= Connect Contact Form 7 to ConvertKit =
[youtube https://youtu.be/JbwsWIHb7cw]

= Connect Contact Form 7 to Elastic Email =
[youtube https://youtu.be/r8pPAXuJMWw]

= Connect Contact Form 7 to EmailOctopus =
[youtube https://youtu.be/CY29B1JDhZg]

= Connect Contact Form 7 to GetResponse =
[youtube https://youtu.be/znDFRLHHwF0&t]

= Connect Contact Form 7 to Klaviyo =
[youtube https://www.youtu.be/PMMAGlc9kd0]

= Connect Contact Form 7 to Mailify =
[youtube https://www.youtu.be/OXeUy3XtJXc]

= Connect Contact Form 7 to Sendinblue =
[youtube https://www.youtu.be/9XG4ATtwWq0]

= Connect Contact Form 7 to lemlist =
[youtube https://www.youtu.be/y6GMtaY0kE0]

= Connect Contact Form 7 to SendFox =
[youtube https://www.youtu.be/8xvxa5zvIf8]

= Connect Contact Form 7 to Mailchimp =
[youtube https://www.youtu.be/0SgWjwQuMYo]

= Connect Contact Form 7 to LionDesk CRM =
[youtube https://www.youtu.be/adKZdY4rn4k]

= Connect Contact Form 7 to Omnisend =
[youtube https://www.youtu.be/dHm6W17K6j4]


== Installation ==
###Automatic Install From WordPress Dashboard

1. log in to your admin panel
2. Navigate to Plugins -> Add New
3. Search **Advanced Form Integration**
4. Click install and then active.

###Manual Install

1. Download the plugin by clicking on the **Download** button above. A ZIP file will be downloaded.
2. Login to your site’s admin panel and navigate to Plugins -> Add New -> Upload.
3. Click choose file, select the plugin file and click install

== Frequently Asked Questions ==

= Why I can't see Contact Form 7 in the dropdown list? =

Make sure that Contact Form 7 is installed and activated.

= Connection error, how can I re-authorize Google Sheets? =

If authorization is broken/not working for some reason, try re-authorizing. Please go to https://myaccount.google.com/permissions, remove app permission then authorize again from plugin settings.

= Getting "The requested URL was not found on this server" error while authorizing Google Sheets =

Please check the permalink settings in WordPress. Go to Settings > Permalinks > select Post name then Save.

= Do I need to map all fields while creating integration? =

No, but required fields must be mapped.

= Can I add additional text while field mapping?

Sure, you can. It is possible to mix static text and form field placeholder tags. Placeholder tags will be replaced with original data after form submission.

= How can I get support? =

For any query, feel free to send an email to support@advancedformintegration.com.

== Screenshots ==

1. All integrations list
2. Settings page
3. New integration page
4. Conditional logic

== Changelog ==

= 1.82.0 [2024-03-05] =
* [Added] GamiPress as trigger
* [Added] Kadence Blocks Form as trigger.
* [Added] Metform as trigger.
* [Added] ARMember as trigger.

= 1.81.0 [2024-02-29] =
* [Added] BuddyBoss as trigger.
* [Added] AffiliateWP as trigger.
* [Added] Beaver Builder Form as trigger.

= 1.80.0 [2024-02-22] =
* [Added] TutorLMS as trigger.

= 1.79.0 [2024-02-06] =
* [Added] ARForms and Divi Forms as trigger.

= 1.78.0 [2024-01-09] =
* [Added] LifterLMS as trigger.

= 1.77.0 [2023-12-05] =
* [Added] Acelle Mail as the receiver

= 1.76.0 [2023-11-06] =
* [Updated] Updated all trigger processing 

= 1.75.0 [2023-10-10] =
* [Added] beehiiv as a receiver platform

= 1.74.0 [2023-09-25] =
* [Added] Zoho Bigin as a receiver platform

= 1.73.0 [2023-09-06] =
* [Added] MailBluster as a receiver platform

= 1.72.0 [2023-08-30] =
* [Added] GiveWP as a sender platform

= 1.71.0 [2023-08-23] =
* [Added] Encharge as a receiver platform

= 1.70.0 [2023-08-16] =
* [Added] LearnDash as a sender platform

= 1.69.0 [2023-06-26] =
* [Added] New MailerLite support added

= 1.68.2 [2023-06-05] =
* [Updated] WooCommerce item meta support added

= 1.68.1 [2023-05-29] =
* [Fixed] WP new user password issue

= 1.68.0 [2023-05-22] =
* [Added] Zoho Sheet integration

= 1.67.0 [2023-05-15] =
* [Added] Airtable integration

= 1.66.0 [2023-04-03] =
* [Added] Vertical Response integration

= 1.65.0 [2023-02-27] =
* [Added] CompanyHub integration

= 1.64.0 [2023-02-01] =
* [Updated] Mailwizz integration
* [Updated] ConvertKit integration
* [Updated] Hubspot CRM integration
* [Updated] Mailchimp integration

= 1.63.1 [2023-01-03] =
* [Updated] Livestorm integration
* [Updated] Klaviyo integration 

= 1.63.0 [2022-12-15] =
* [Fixed] Some sanitizing issues

= 1.62.0 [2022-12-01] =
* [Added] Amelia Booking as a trigger

= 1.61.0 [2022-11-21] =
* [Updated] The log table

= 1.60.0 [2022-11-02] =
* [Added] EngageBay integration support

= 1.59.0 [2022-10-10] =
* [Added] Selzy integration support

= 1.58.0 [2022-10-03] =
* [Added] Robly integration support

= 1.57.0 [2022-09-12] =
* [Added] Mailercloud integration support
* [Added] Flowlu CRM integration support
* [Updated] AWeber integration
* [Updated] LionDesk Integration
* [Updated] Clinchpad CRM Integration
* [Updated] Constant Contact Integration
* [Updated] Benchmark Email Integration

= 1.56.9 [2022-09-01] =
* [Fixed] Moosend integration was fetching partial email lists

= 1.56.8 [2022-08-31] =
* [Updated] Address fields added to LionDesk integration

= 1.56.7 [2022-08-23] =
* [Updated] Several scripts


= 1.56.4 [2022-08-02] =
* [Updated] Asana Integration process
* [Updated] Hubspot Private Apps auth
* [Updated] Zoho CRM Integration process

= 1.56.3 [2022-07-25] =
* [Updated] Updated Hubspot auth process
* [Updated] MailerLite double-optin

= 1.56.2 [2022-07-16] =
* [Fixed] A bug

= 1.56.1 [2022-07-09] =
* [Updated] Added data centers to Zoho CRM integration

= 1.56.0 [2022-07-06] =
* [Added] Zoho CRM integration
* [Updated] Pipedrive allow duplicate contacts

= 1.55.6 [2022-06-19] =
* [Updated] Mailchimp integration

= 1.55.5 [2022-06-18] =
* [Updated] Google Sheets integration

= 1.55.4 [2022-06-14] =
* [Updated] LionDesk auth process

= 1.55.2 [2022-06-08] =
* [Fixed] LionDesk last name was not being sent

= 1.55.1 [2022-06-06] =
* [Updated] Mailchimp integration.

= 1.55.0 [2022-06-02] =
* [Added] Salesflare integration support
* [Added] Grab and send UTM variables
* [Updated] The plugin is now WordPress 6 compatible.

= 1.54.0 [2022-05-24] =
* [Added] EasySendy integration support

= 1.53.0 [2022-05-18] =
* [Added] ClickUp integration support

= 1.52.0 [2022-05-15] =
* [Added] SendX integration support

= 1.51.0 [2022-05-08] =
* [Added] Capsule CRM integration support

= 1.50.3 [2022-04-07] =
* [Updated] Google Sheets integration
* [Updated] Klaviyo integration
* [Updated] Smartsheet integration
* [Updated] GetResponse integration


= 1.50.0 [2022-02-18] =
* [Added] Sales.Rocks integration support
* [Added] Demio integration support
* [Added] Livestorm integration support
* [Added] MailWizz integration support

= 1.49.1 [2022-02-12] =
* [Updated] Freshworks CRM inegration
* [Updated] Aweber Integration

= 1.49.0 [2022-02-28] =
* [Added] Wealthbox CRM integration support
* [Fixed] WooCommerce variable product issue

= 1.48.0 [2022-02-22] =
* [Added] Onehash.ai integration support

= 1.47.1 [2022-02-11] =
* [Updated] ConvertKit integration
* [Updated] Mailchimp integration

= 1.47.0 [2022-02-10] =
* [Added] Mautic integration support
* [Added] Subscribe to ConvertKit forms

= 1.46.0 [2022-01-24] =
* [Added] Keap integration support
* [Updated] Pipedrive integration

= 1.45.0 [2022-01-17] =
* [Added] ZOHO Campaigns support

= 1.44.0 [2022-01-04] =
* [Added] New Autopilot support

= 1.43.01 [2021-12-20] =
* [Updated] Copper CRM Integration

= 1.43.00 [2021-12-15] =
* [Added] Constant Contact support
* [Fixed] Minor OAuth issue
* [Updated] Close CRM integration
* [Updated] Omnisend integration
* [Updated] Mailify integration
* [Updated] Moosend Integration

= 1.42.13 [2021-11-29] =
* [Updated] Conditional Logic

= 1.42.12 [2021-11-19] =
* [Fixed] Pipedrive empty fields

= 1.42.11 [2021-11-17] =
* [Fixed] AWeber access token expires

= 1.42.10 [2021-11-15] =
* [Updated] SendPulse integration
* [Fixed] WooCommerce addtional checkout fields issue

= 1.42.9 [2021-11-06] =
* [Fixed] Few issues

= 1.42.5 [2021-10-26] =
* [Fixed] AWeber auth issue

= 1.42.5 [2021-10-22] =
* [Fixed] Campaign Monitor integration issue

= 1.42.4 [2021-10-19] =
* [Fixed] Aweber fetching contact list while edit integration
* [Updated] As the supported list grew, activate/deactivate option added in the settings.

= 1.42.3 [2021-10-11] =
* [Update] Revue integration

= 1.42.2 [2021-10-06] =
* [Fixed] Error while showing full log

= 1.42.1 [2021-10-04] =
* [Updated] Freshworks CRM (Freshsales) integration

= 1.42.0 [2021-09-28] =
* [Added] WordPress - Create new post

= 1.41.0 [2021-09-08] =
* [Added] Google Calendar support

= 1.40.1 [2021-08-10] =
* [Fixed] SendPulse list loading issue

= 1.40.0 [2021-08-02] =
* [Added] Benchmark Email support

= 1.39.1 [2021-07-19] =
* [Fixed] Aweber not showing all contact lists

= 1.39.0 [2021-07-07] =
* [Added] Asana Integration
* [Fixed] Fetch 50+ Sendinblue lists

= 1.38.0 [2021-06-10] =
* [Added] Slack Integration

= 1.37.8 [2021-05-31] =
* [Updated] Edit Integration
* [Updated] Log View

= 1.37.7 [2021-05-22] =
* [Updated] Mailify Integration
* [Updated] Mailjet Integration

= 1.37.6 [2021-05-05] =
* [Updated] Sendinblue Integration

= 1.37.5 [2021-05-03] =
* [Updated] Close CRM Integration

= 1.37.4 [2021-04-22] =
* [Updated] Pipedrive Integration
* [Updated] Insightly Integration

= 1.37.3 [2021-04-14] =
* [Updated] Copper integration

= 1.37.2 [2021-04-08] =
* [Updated] GetResponse integration

= 1.37.1 [2021-03-30] =
* [Fixed] Pipedrive integration

= 1.37.0 [2021-03-29] =
* [Added] SendPulse support (Subscribe to email list)
* [Added] Trello support (Creat Card)

= 1.36.3 [2021-03-17] =
* [Fixed] CF7 id deprecated notice
* [Fixed] CF7 form submission

= 1.36.2 [2021-03-09] =
* [Added] Disable log checkbox in general settings
* [Fixed] lemlist integration issue
* [Fixed] Sendinblue update issue

= 1.36.1 [2021-02-24] =
* [Added] WooCommerce new fields
* [Fixed] FireFox submission issue
* [Fixed] Google Sheets integration issue
* [Updated] Some notifications

= 1.36.0 [2021-02-10] =
* Hubspot CRM (add new contact) support added

= 1.35.1 [2021-02-03] =
* Updated WooCommerce fields
* Updated Pipedrive integration

= 1.35.0 [2021-01-25] =
* Smartsheet (add new row) support added

= 1.34.0 [2021-01-08] =
* Pabbly Email Marketing (add contact) support added

= 1.33.1 [2020-12-16] =
* Autopilot (add/update contact) support added

= 1.33.0 [2020-12-02] =
* Autopilot (add/update contact) support added

= 1.32.3 [2020-11-16] =
* Mailchimp - user can now activate double opt-in

= 1.32.1 [2020-11-08] =
* Improved Omnisend integration

= 1.32.0 [2020-11-06] =
* Added Multisite support

= 1.31.3 [2020-10-23] =
* Improved LionDesk integration

= 1.31.2 [2020-10-23] =
* Fixed few bugs

= 1.31.1 [2020-10-21] =
* Fixed Sendinblue FIRSTNAME, LASTNAME issue on other languages

= 1.31.0 [2020-10-20] =
* Added special tags support

= 1.30.0 [2020-10-06] =
* Added lemlist support
* Temporarily disabled Kartra integration

= 1.29.4 [2020-10-04] =
* Updated Woocommerce Order fields

= 1.29.3 [2020-09-29] =
* Fixed bugs and updated triggers for WooCommerce

= 1.29.2 [2020-09-23] =
* Fixed Omnisend SMS subscription issue

= 1.29.1 [2020-09-19] =
* Updated WP REST API call
* Updated ActiveCampaign featues

= 1.29.0 [2020-08-29] =
* Added Pushover support

= 1.28.0 [2020-08-21] =
* Added Agile CRM support

= 1.27.0 [2020-07-17] =
* Added Twilio (Send SMS) support

= 1.26.4 [2020-07-17] =
* Updated Klaviyo API endpoint

= 1.26.3 [2020-07-15] =
* Fixed Contact Form 7 connection issue

= 1.26.1 [2020-06-20] =
* Fixed EverWebinar/WebinarJam field issue
* Fixed Sendinble list limits from 10 items

= 1.26.0 [2020-06-19] =
* Added WooCommerce New Order support
* Improve admin js loading

= 1.25.2 [2020-06-11] =
* Fixed log pagination

= 1.25.1 [2020-06-06] =
* Fixed EmailOctopus double opt-in

= 1.25.0 [2020-06-03] =
* Added Kartra support

= 1.24.0 [2020-05-30] =
* Added conditional logics - acceptance field check, etc.

= 1.23.1 [2020-05-28] =
* Fixed checkbox issue

= 1.23.0 [2020-05-27] =
* WebinarJam / EverWebinar support added

= 1.22.3 =
* Sendinblue update contact

= 1.22.2 =
* Changed Google Sheets authentication process

= 1.22.1 =
* LOG view improved

= 1.22.0 =
* Added SendFox support

= 1.21.2 =
* Fixed warnings

= 1.21.1 =
* Fixed Klaviyo subscribe issue

= 1.21.0 =
* Woodpecker.co support added

= 1.20.0 =
* ActiveCampaign support added

= 1.19.0 =
* Elastic Email support added

= 1.18.3 =
* Updated Sendinblue API to V3 and fixed FIRSTNAME, LASTNAME field issue

= 1.18.2 =
* Fixed Bugs

= 1.18.1 =
* Fixed Omnisend create contact issue

= 1.18.0 =
* Added Sendy support

= 1.17.0 =
* Added Jumplead support

= 1.16.0 =
* Added Omnisend support
* Added DirectIQ support

= 1.15.0 =
* Added Mailify support

= 1.14.0 =
* Added Moosend support

= 1.13.0 =
* Added MailerLite support

= 1.12.0 =
* Added Aweber support

= 1.11.0 =
* Added Curated support

= 1.10.0 =
* Added EmailOctopus support

= 1.9.0 =
* Added Close CRM support

= 1.8.1 =
* Fixed Google Sheet fetching header issue for a single worksheet

= 1.8.0 =
* Added LionDesk support

= 1.7.0 =
* Added Google Sheets support

= 1.6.0 =
* Added ClinchPad CRM support

= 1.5.0 =
* Added Moonmail support

= 1.4.0 =
* Mappings fields are now editable
* Added Campaign Monitor support
* Added log page

= 1.3.0 =
* Added Drip support

= 1.2.0 =
* Added Freshsales support

= 1.0.1 =
* Fixed Copper, Insightly & Pipedrive issues.

= 1.0.0 =
* First public release.