=== LH Private BuddyPress ===
Contributors: shawfactor, jonathanmoorebcsorg
Donate link: https://lhero.org/portfolio/lh-private-buddypress/
Tags: buddypress, protection, privacy, private, protect, hide, community
Tested up to: 6.0
Requires PHP: 5.6
Stable tag: 1.12

Protect your BuddyPress Installation from strangers. Only registered users will be allowed to view directory pages, activity and profile pages.

== Description ==

Protect your BuddyPress Installation from strangers. Only registered users will be allowed to view view directory pages, activity and profile pages. Users attempting to view blog content via RSS are also authenticated via HTTP Auth.

This plugin is inspired by the Private Buddypress plugin by Dennis Morhardt. I rewrote it to provide a solution that followed the WordPress coding stndards and the decisions rather than options philosophy. I deploy this on my own multisite environment where I don't want public profiles, activity or members directories (and where I don't want site admins changing this).

**Like this plugin? Please consider [leaving a 5-star review](https://wordpress.org/support/view/plugin-reviews/lh-private-buddypress/).**

**Love this plugin or want to help the LocalHero Project? Please consider [making a donation](https://lhero.org/portfolio/lh-private-buddypress/).**

== Installation ==

Use the automatic plugin installation in the backand or install the plugin manuell:

1. Upload 'lh-private-buddypress' to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Can I change the URL where non-loggedin users are being redirected? =

Yes, currently you need to write a filter function in your functions.php or site specific plugin.

`function redirect_nonloggedin_users($current_uri, $redirect_to) {
	// Redirect users to the homepage
	return get_option('siteurl') . '/?from=' . $redirect_to;
}

add_filter('lh_private_buddypress_redirect_login_page', 'redirect_nonloggedin_users', 10, 2);`

= Are there other actions or filters? =

In LH Private BuddyPress are 4 existing filters:

*   **lh_private_buddypress_is_buddypress_feed**: Boolean value if the current page is a BuddyPress feed
*   **lh_private_buddypress_redirect_to_after_login**: Called URI from where the users came from
*   **lh_private_buddypress_display_login_message**: Customise the message when vistors try to access a private component
*   **lh_private_buddypress_redirect_login_page**: URI where nonloggedin users are being redirected
*   **lh_private_buddypress_login_required_check**: Boolean value if for the current page a login is needed

= What components are made private? =

Only the members directory, user profile, and activity pages. These can only be visited by logged in users. All other components are still public.

= Why this set up? =

This is primarily for organisations, they usually want a public presence but may not want to disclose membership details to non members.

= What if something does not work?  =

LH Private Buddypress, and all [https://lhero.org](LocalHero) plugins are made to WordPress standards. Therefore they should work with all well coded plugins and themes. However not all plugins and themes are well coded (and this includes many popular ones). 

If something does not work properly, firstly deactivate ALL other plugins (except Buddypress and this on) and switch to one of the themes that come with core, e.g. twentyfifeen, twentysixteen etc.

If the problem persists please leave a post in the support forum: [https://wordpress.org/support/plugin/lh-private-buddypress/](https://wordpress.org/support/plugin/lh-private-buddypress/). I look there regularly and resolve most queries.

= What if I need a feature that is not in the plugin?  =

Please contact me for custom work and enhancements here: [https://shawfactor.com/contact/](https://shawfactor.com/contact/)

== Changelog ==
**1.00 November 02, 2016**  
* First release

**1.01 November 04, 2016**  
* More documentation

**1.02 March 24, 2017**  
* Fixed very minor error in php strict error reporting

**1.03 March 24, 2017**  
* Nest bp_init

**1.04 March 30, 2017**  
* More explicit variable check

**1.05 May 10, 2017**  
* Another explicit variable check

**1.06 July 26, 2017**  
* Added class check

**1.07 December 23, 2017**  
* Made translation ready

**1.08 January 17, 2018**  
* Singleton and did_action

**1.09 March 05, 2019**  
* Minor changes and improvements

**1.10 April 04, 2019**  
* Better Authorisation handling

**1.11 May 16, 2019**  
* Prevent direct access

**1.12 July 29, 2022**  
* code fromatting