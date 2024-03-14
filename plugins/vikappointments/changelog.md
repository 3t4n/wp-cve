# Changelog

### 1.2.11

*Release date - 14 December 2023*

#### New Features

* The packages can now support an expiration date for their usage.
* The e-mail conditional texts now support the attachments and the payment filter.
* Next to the pagination buttons, it is now possible to choose the number of items to display per page.
* Implemented the "PayPal Express Checkout" payment gateway.
* It is now possible to force the customer to pick a number of options equals to the number of selected participants.
* It is now possible to pause the cron notifications received by the administrators.

#### Improvements

* The system is now able to display a summary with the number of redeemed packages against the total purchased ones.
* Refreshed the style used by the main menu of VikAppointments in the back-end.
* The system now always shows the locations assigned to an employee, even if there's only one.
* The appointments exported in CSV format now include the custom fields assigned to specific services.
* Properly escaped new lines within the description of the ICS events.
* Added full support to the latest PHP 8 versions.

#### Bug Fixes

* Fixed an issue that was not properly autofilling the selected customers from the analytics page.
* Prevented contents translation in case the multilingual feature is disabled.
* Removed account activation references in case there are no published subscriptions.
* Fixed PHP warnings with the cancellation e-mail sent to the employees.

### 1.2.10

*Release date - 28 April 2023*

#### Improvements

* Improved compatibility with PHP 8.
* Improved the user experience while booking an appointment.
* Introduced new plugin actions and filters.
* Implemented an optimizer to avoid exhausting the memory buffer while reading a ICS calendar.
* Updated services widget carousel.

#### Bug Fixes

* Fixed an issue that could allow the users to book appointments with a check-out exceeding the working day bounds.
* Fixed an error that occur while booking an appointment for a non-recurring working day.
* Fixed an issue with the offline credit card which could not accept a CVV with leading zeros.
* Fixed a conflict that could occur when both VikAppointments and VikBooking were installed.
* Fixed fatal error that could occur while trying to subscribe to a waiting list.
* Several minor bug fixes.

### 1.2.9

*Release date - 17 June 2022*

#### Improvements

* Various enhancements for WP Site Health.
* Prevented e-mail sending to employees without a configured e-mail address.
* Adjusted various buttons with an attached "copy-to-clipboard" feature.

#### Bug Fixes

* Fixed issue that was always resetting the number of participants to 1 after saving a reservation from the employees area.
* Fixed price calculation while updating a reservation from the employees area.
* Fixed REST API error while trying to save a page with "confirmapp" shortcode.
* The One-Page Booking widget now properly displays the currency symbol rather than "undefined".
* Fixed an issue that was auto-removing the created closures after some time.

### 1.2.8

*Release date - 23 May 2022*

#### Improvements

* Added support to WordPress 6.0.

#### Bug Fixes

* Fixed an issue that was ignoring the access level of the items.
* Fixed an issue that was displaying the cron schedules with a wrong encoding.
* The Overview widget published within the WP dashboard now properly displays the currency symbol rather than "undefined".

### 1.2.7

*Release date - 28 April 2022*

#### Bug Fixes

* Fixed a bug that could restore the settings of a cron job to their default value.
* Fixed a fatal error that could occur with classic editor inside the management of a post/page.
* Added missing "Custom Fields" language definition in the employees area.
* Fixed fatal error occurred in case of a SMS notification failure.
* Minor JS adjustments.

### 1.2.6

*Release date - 5 April 2022*

#### Improvements

* When creating a shortcode with Elementor (as well as any other page builder), the resulting preview should now be visible.
* Added missing translations to the datepicker of the One-Page Booking widget.

#### Bug Fixes

* Removed a few unexpected javascript errors.
* Fixed a fatal error that could occur with PHP 8 or higher.
* Fixed the validation of the year applied by the Offline Credit Card payment gateway.

### 1.2.5

*Release date - 8 March 2022*

#### New Features

* Implemented a new system to import the appointments from remote calendars, such as Google, Apple and so on.
* Introduced a new parameter to apply an extra duration to the appointments through the options.
* Created a new stunning widget to complete the whole booking process in a single page.

#### Improvements

* It is now possible to restrict the options by user group.
* Extended the import/export framework by adding support to groups, services, employees, options and locations.
* Generic improvements to the login/registration form.
* Added a new search parameter to filter the cron jobs logs by date.
* Added a new setting to auto-flush the logs registered by the cron jobs.

#### Bug Fixes

* The system now displays the correct maximum upload size.
* Updated the URL to load Google Maps to the latest version.
* The custom fields of multi-select type now correctly display the placeholder.
* Fixed PHP warning displayed within the dashboard of the employees area.
* Fixed recursive import of backup elements.

### 1.2.4

*Release date - 10 January 2022*

#### New Features

* Introduced a tool to customize the main colors of the plugin with a live preview.
* Improved the SEO for the internal media manager, which now provides the possibility to specify titles, alt(s) and captions.
* Added a parameter to choose whether the customers are allowed to edit a custom field after the first booking.

#### Improvements

* Added a button to get the directions to the appointment address through the pre-installed maps software (Apple Maps, Android Maps or Google Maps).
* It is now possible to download backup archives in case the source folder is placed above the root.
* The backup system now includes the styles defined through the customizer of VikAppointments.

#### Bug Fixes

* Adjusted some permissions for the Employees Area, which were not considered in certain cases.
* Fixed a bug that was considering any customer as subscribed.
* Fixed the error that could occur while trying to create/update a custom field.
* Fixed an issue that might not show Google Maps with certain themes.
* Fixed a few PHP warnings that could occur when the login was required before accessing the availability calendars.
* The refunded status code is no more mandatory.
* Fixed an issue with the file filter while fetching the available e-mail custom texts.

### 1.2.3

*Release date - 16 December 2021*

#### New Features

* Added the possibility to allow the customers to self-confirm their appointments through a link received via mail.
* Introduced a new system to backup the contents of VikAppointments (import/export).
* Implemented a wizard to help the administrators to set up the program after its installation.
* Introduced the possibility to offer certain payment methods only to customers with a specific number of purchases/bookings.
* It is now possible to import the working days from a file in JSON, XML or TXT format.
* Added the possibility to choose the execution recurrence for each cron jobs.

#### Improvements

* While registering an appointment from the Employees Area, the system will now automatically create a new customer record.
* It is now possible to apply a manual discount while creating/editing an appointment, a package order or a subscription.
* Updated the PayPal integration to support an instant validation of the transactions.
* Improved the cron jobs management, which now provides an extended editor for the textarea type fields that were allowing HTML tags.
* Added a new ACL role to allow the administrators to access the management page of the closing days/periods.
* The driver used to export the appointments in CSV format now includes the payment column.
* Added the possibility to restore the default status codes.
* The appointments list in the Employees Area dashboard now reports more information.

#### Bug Fixes

* Fixed an issue that was not taking the correct price of a service in case of multiple participants (back-end).
* Removed a duplicate search icon that could appear on retina displays.
* Fixed an issue that could display empty boxes for the attendees within the notification e-mail.
* Fixed a decoding error that could occur while trying to open the popup for the registration into a waiting list.
* Fixed an error with the purchase of the subscriptions.
* Fixed an issue that could not display the button to leave a review.
* Fixed a bug that was not displaying the rates on the timeline for certain logged-in users.
* Fixed the error that occurred while trying to edit a media file.

### 1.2.2

*Release date - 26 November 2021*

#### New Features

* Added support for Clicksend SMS provider.

#### Bug Fixes

* Fixed an unexpected error that could occur while registering a new account.
* Fixed an issue with the detection of the closing periods.
* Removed a PHP warning displayed while generating an invoice.

### 1.2.1

*Release date - 18 November 2021*

#### Bug Fixes

* Fixed an issue that could assign an appointment to an employee that is unavailable for the selected check-in.
* Fixed a few conflicts that could verify with other plugins.
* Fixed an issue that could occur with the WordPress media manager.

### 1.2

*Release date - 20 October 2021*

#### New Features

* The look&feel of the Back-End and the Employees Area has been completely redesigned.
* Implemented the user notes for the customers and the appointments, which also support documents and tags.
* It is now possible to group the options in categories, which will be displayed as a sort of "accordion" widget.
* Added support for the creation of composite taxes. Every item in the system is now taxable.
* It is now possible to create/manage the supported status codes, such as "Confirmed", "Paid", "Pending" and so on.
* Implemented a new type of e-commerce to sell the subscriptions to the customers.
* It is now possible to collect the information of all the participants by flagging the custom fields as "repeatable".
* Added support for 60+ widgets to be published within the dashboard and the new analytics pages.
* Added a new weekly layout for the calendars in the front-end.
* The customers are now able to adjust the times according to their timezone, which can be selected through an apposite dropdown under the calendar.
* The management of the working days in the back-end has been redesigned to be as more intuitive as possible.
* Added a countdown that informs the customers how much time they have to confirm an appointment before it expires.
* It is now possible to create the appointments with recurrence also from the Employees Area.
* Implemented an API framework to handle the requests made by third-party systems.
* Implemented the WebHooks to automatically notify third-party systems hosted on remote servers.
* Added the possibility to edit the existing invoices and to generate new ones by month.
* It is now possible to import/export also the reviews.
* Added 1000+ hooks to enhance the extendability of the software.

#### Improvements

* It is now possible to easily switch the selected employee from the management page of an appointment.
* When a service requires the selection of an employee, it is now possible to allow the customers to choose a random one.
* The maximum quantity of the options can now vary according to the number of selected participants.
* It is now possible to include specific e-mail attachments for each service.
* Added a setting to limit the reservations in the future, which may vary for each service.
* It is now possible to filter the reservations by location.
* Added a parameter to display the custom fields according to the language selected by the customer.
* Added a setting to allow the customers to book the appointments only if they purchased a package first.
* The customers are now able to use the coupon codes also for the packages and the subscriptions.
* Added a parameter to limit the maximum number of times that a customer can redeem a coupon.
* While creating a recurring appointment from the back-end, the system now suggests new times or employees in case a slot is not available.
* Added some parameters to the export functions of the appointments, such as the reminder for ICS files and the delimiter type for CSV files.
* Implemented a new rule to export the records in Excel format.
* Added several buttons to save the records as copy.
* While exporting certain records, it is now possible to choose what are the columns to include and whether the file should include raw or formatted values.
* While importing certain records, the system will try to auto-populate the associations.
* It is now possible to choose what are the services for which the system should send reminders through the cron jobs.
* The system now uses FontAwesome 5 to display the icons.
* Enhanced the security to prevent CSRF attack attempts.

#### Bug Fixes

* Removed restriction that hides all the services that are not yet started.
* Fixed an issue that was displaying a calendar field while creating a weekly working day.
* Fixed minor issues with the availability system.
* Fixed some CSS conflicts that might occur with other templates.
* Fixed some errors that could occur with PHP 8.

#### Widgets

* Implemented a new widget for the WordPress dashboard that displays a financial overview.
* Improved the look&feel of almost all the site widgets.
* The Search widget now offers the possibility to skip the selection of the employee, when requested by the service.
* The widgets that display the description of the employees/services now try to look for a short text in case a READ MORE separator is used.

### 1.1.12

*Release date - 01 April 2021*

#### Bug Fixes

* Fixed some errors that could occur with PHP 8 environments.
* The employee details page is now able to use the correct number of people.

### 1.1.11

*Release date - 08 March 2021*

#### Improvements

* Added full support to WordPress automatic updates.
* All the e-mails are now sent by using the sender address.
* Added ALT attribute to the images of the e-mails to improve their score.

#### Bug Fixes

* Fixed availability calculation while changing service from the daily calendar page.
* Fixed check-out issue with sleep time turned on.
* Fixed wrong language with "og:locale" metadata.

### 1.1.10

*Release date - 05 January 2021*

#### Improvements

* RSS opt-in will now be asked after completing a basic configuration of the plugin.

#### Bug Fixes

* Fixed "VikFormValidator undefined" issue in employees area.
* The ICS Sync URL is now safe for external usages.
* Fixed issue with cancellation button.

### 1.1.9

*Release date - 04 December 2020*

#### New Features

* Added the possibility to receive RSS news, tips and offers.

#### Improvements

* All site AJAX requests now rely on WP AJAX end-point for a better stability.
* It is now possible to automatically assign a post to a shortcode after creating it.

#### Bug Fixes

* Fixed wrong timezone when dispatching a cron job.
* Fixed an issue that could show old settings after saving the employee configuration.
* Fixed redirect issue that could occur when changing pagination.
* The services and the user role are now properly assigned when registering an employee from the front-end.
* Fixed usergroup selection when creating a special rate or a restriction.
* Fixed timeout issue with certain HTTP requests.

### 1.1.8

*Release date - 30 September 2020*

#### New Features

* Added support for Zoom.us video meetings (external plugin required).
* Implemented the **description** for custom fields.
* It is now possible to create/edit a customer while managing a reservation.
* Clicking an empty cell on the calendar view will let you create a new reservation.
* The e-mail subject now supports dynamic tags.

#### Improvements

* Improved the layout of the modal that shows the details of an appointment.
* It is now possible to exclude the default custom texts while sending manual e-mails.
* Added several plugin hooks to enhance the software extensibility.

#### Bug Fixes

* Fixed e-mail subject encoding on WP 5.5 or higher

### 1.1.7

*Release date - 12 August 2020*

#### Improvements

* Added support for WordPress 5.5.
* System messages are now safely displayed in case of themes with lazy loading.

#### Bug Fixes

* Fixed issue that could show duplicated system messages.
* Fixed list limit issue with WordPress 5.4.2 or higher.
* Fixed minor PHP notices.

### 1.1.6

*Release date - 14 May 2020*

#### New Features

* Added the possibility of including custom texts while sending a manual notification to the customers.
* It is now possible to limit the maximum number of appointments per interval that a customer can book.

#### Improvements

* Services can have different restrictions for advance bookings.
* Added a button next to the ICS Sync link to quickly start a calendar subscription.
* The generated ICS calendars now own a title based on the agency name and selected employee.
* Conversion codes now support `<noscript>` tags.
* Added support for Loco Translate plugin (auto-load translations from "loco" folder).
* The "Search Nearby" feature of the Employees Filter plugin can be applied also to the searched parameters.

#### Bug Fixes

* It is now possible to switch service while editing a reservation from the Employees Area.
* Days and months abbreviations are now translatable.
* Fixed an issue that displayed certain recurrence options even if they were unpublished.
* TCPDF is now loaded only when needed to avoid exhausting memory allocation.

### 1.1.5

*Release date - 30 March 2020*

#### Improvements

* Added support for WordPress 5.4.
* The "Shortcodes" and "Permissions" buttons are now visible in case the default back-end view was set to "Calendar".
* The "Coupons" menu item is no more visible in the Employees Area in case the employees don't have this capability.
* Added support for "Select2" i18n.
* The "Search Tools" dropdowns are now 50px smaller.

#### Bug Fixes

* Fixed an error that might occur with PHP 7.2+ when trying to assign a service to an employee.
* Fixed the error displayed by Gutenbeg when trying to save a shortcode of type "confirmapp".

### 1.1.4

*Release date - 11 February 2020*

#### Bug Fixes

* Fixed an issue that might not automatically confirm the appointments without online payment.

### 1.1.3

*Release date - 27 January 2020*

#### New Features

* It is now possible to manually sort the employees assigned to the services.
* Added support for "fortnightly" and "bi-monthly" types of recurrence.
* The subscriptions now support a custom ordering.

#### Improvements

* The changelog is now displayed also when downloading the PRO version.
* The "default status" setting is now ignored when completing an appointment with the bank transfer gateway.
* Added a few plugin hooks to extend the functionalities of VikAppointments.

#### Bug Fixes

* The custom fields with multiple options are now properly displayed within the reservations list.
* Fixed a layout issue within the reports page of the packages.
* Fixed an issue that might occur while validating a payment through the offline credit card gateway.
* Fixed a timezone issue that might occur with certain widgets.
* Fixed an availability issue that might occur when using contiguous working shifts.

### 1.1.2

*Release date - 20 November 2019*

#### Improvements

* Added the possibility to leave a feedback while deactivating the plugin.
* Shortcodes are now assignable to posts with protected visibility or scheduled for a future date.

#### Bug Fixes

* Fixed an issue that prevented to display HTML tags within popovers.
* Fixed the error that occurred when trying to export something.

### 1.1.1

*Release date - 6 November 2019*

#### Improvements

* Added support for WordPress 5.3.

#### Bug Fixes

* Escaped navbar titles to prevent javascript errors.
* Fixed an issue that could cause conflicts with other concurrent VIK plugins.
* Portal and Global menu items are no more visible in case a user cannot access them.

### 1.1

*Release date - 18 September 2019*

#### New Features

* Added some reports for the packages orders.
* Created the reports also for the services booked.
* Closing days can be specified also for some services only.
* Custom fields for the employees now support a visual editor.

#### Improvements

* Packages are automatically redeemed (if any) also from the back-end when creating a reservation.
* Packages are automatically restored after cancelling a reservation from both the sections.
* The packages list in the back-end now displays the number of redeemed appointments per package.
* Added some placeholders for displaying location details within the reminders.
* All the reports can be generated also by number of appointments instead of total amount earned.
* Coupon publishing dates can, optionally, refer to the checkin date or to the current date.
* The number of participants (when higher than 1) is now reported within all the notification e-mails.
* The PayPal form now owns the name and id attributes.

#### Bug Fixes

* Booking restrictions are not applied when creating a reservation as employee.
* The employee name is no more visible within the notification e-mail for customers, in case the employee selection was disabled.
* Fixed the way the system detects the default country code in case of multilingual websites.
* The payments filter within the back-end is no more visible in case there are no payment gateways.
* The parameter used to enable the recurrence for a service is no more visible in case the recurrence is globally turned off.
* Some input values have been escaped to prevent XSS attacks.

### 1.0

*Release date - 10 July 2019*

* First stable release of the VikAppointments plugin for WordPress.