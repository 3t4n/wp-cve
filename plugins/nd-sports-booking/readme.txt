=== Sports Booking ===
Contributors: nicdark
Tags: sports, booking
Requires at least: 4.5
Tested up to: 5.9
Stable tag: 1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Perfect solution to manage your sports booking. For any sports activities. Show and manage your booking in the best way possible. sport WP plugin.

== Description ==

= Welcome to Sports Booking WP plugin =
This plugin is an useful system to manage all your sport booking, search and filter them in a very simple way. 

In the [nd_spt_booking_form] shortcode, Stripe is used as the booking method. In the last step the user should enter his data through an iframe provided by Stripe. Once the operation has been completed, the user will be redirect to the thank you page. Below some useful links :

[Official site](https://stripe.com)
[Information for developers](https://stripe.com/docs)
[Terms and conditions](https://stripe.com/legal)

= Below some live preview demos =
Click on the links below for view all plugin features in action:

== Installation ==

1. Install and activate the "ND sport" plugin.
2. Create a page and add in it the shortcode [nd_spt_booking_form] for display the sport booking steps.
3. By default the system does not allow the booking of any sport since you will have to add at least one sport and create the time-slots through the plugin settings.
4. Create your sport : sports -> Add New.
5. Set the required settings : ND sport -> Plugin Settings -> Max players number option, remember to save the option using the "Save Changes" at the bottom of the panel. ( Saving in this step is essential since if you don't do it you won't be able to create the time slots in the next step )
6. Create your time slots : ND sport -> Add Timing -> Check all checkboxes and set the hours for start to receive bookings, use always the "Save Changes" button for save all options.
7. The steps above are mandatory for start to use the booking form. Remember that you have more settings available for fit the plugin to your needs.

== Screenshots ==

1. Sport Booking

== Changelog ==

= 1.2 =
* Improved plugin security ( added realpath(), Data Sanitization/Escaping variables )

= 1.1 =
* Elementor compatibility 3.6

= 1.0 =
* Initial version