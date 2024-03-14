=== LH User Taxonomies ===
Contributors: shawfactor
Donate link: https://lhero.org/portfolio/lh-user-taxonomies/
Tags: user, users, taxonomy, custom taxonomy, register_taxonomy, developer
Requires at least: 4.0
Tested up to: 5.7
Stable tag: trunk

Simplify the process of adding support for custom taxonomies for Users. Just use `register_taxonomy` and everything else is taken care of.

== Description ==

This plugin extends the default taxonomy functionality and extends it to users, while automating all the boilerplate code.

Once activated, you can register user taxonomies using the following code:
`
register_taxonomy('profession', 'user', array(
	'public'		=>true,
	'single_value' => false,
	'show_admin_column' => true,
	'labels'		=>array(
		'name'						=>'Professions',
		'singular_name'				=>'Profession',
		'menu_name'					=>'Professions',
		'search_items'				=>'Search Professions',
		'popular_items'				=>'Popular Professions',
		'all_items'					=>'All Professions',
		'edit_item'					=>'Edit Profession',
		'update_item'				=>'Update Profession',
		'add_new_item'				=>'Add New Profession',
		'new_item_name'				=>'New Profession Name',
		'separate_items_with_commas'=>'Separate professions with commas',
		'add_or_remove_items'		=>'Add or remove professions',
		'choose_from_most_used'		=>'Choose from the most popular professions',
	),
	'rewrite'		=>array(
		'with_front'				=>true,
		'slug'						=>'author/profession',
	),
	'capabilities'	=> array(
		'manage_terms'				=>'edit_users',
		'edit_terms'				=>'edit_users',
		'delete_terms'				=>'edit_users',
		'assign_terms'				=>'read',
	),
));
`

Read more about [registering taxonomies in the codex](http://codex.wordpress.org/Function_Reference/register_taxonomy)
This is heavily inspired by previous work by [Justin Tadlock](http://justintadlock.com/archives/2011/10/20/custom-user-taxonomies-in-wordpress) and also forks Damian Gostomskis plugin in the repository to add additional functionality, including:

* Fixes a bug with display of existing user taxonomies in the user-edit screen
* Fixes a bug with taxonomy count in the old plugin where deleting users did not update the count
* Add support for 'single_value' attribute when registering a user taxonomy for taxonomies which should only have one value.
* Properly supports the capabilities associated with the taxonomy when registered.
* Supports 'show_admin_column' attribute when registering the taxonomy in the same way as post taxonomies.
* Where 'show_admin_column' is true admins can assign user taxonomies using bulk edit functionality.

Check out [our documentation][docs] for more information on how to register user taxonomies. 


[docs]: https://lhero.org/portfolio/lh-user-taxonomies/
[GitHub]: https://github.com/shawfactor/lh-user-taxonomies

**Like this plugin? Please consider [leaving a 5-star review](https://wordpress.org/support/view/plugin-reviews/lh-user-taxonomies/).**

**Love this plugin or want to help the LocalHero Project? Please consider [making a donation](https://lhero.org/portfolio/lh-user-taxonomies/).**

== Frequently Asked Questions ==

= Can I set a taxonomy that includes posts/pages/cpt´s with users? =

You can but you should not. The problem being that when taxonomies are shared across objects types in different tables wordpress can get confused.

= Does this create new database tables?  =

No. There are no new database tables with this plugin.

= Does this modify existing database tables?  =

No. All of WordPress's core database tables remain untouched.

= Does this plugin integrate with user roles?  =

No. This is best left to plugins that choose to integrate with this plugin.

= What is something does not work?  =

LH User Taxonomies, and all LocalHero plugins are made to WordPress so should work with all well coded plugins and themes. But not all plugins and themes are well coded (including many popular ones). 

If something does not work properly, firstly decativate ALL other plugins and switch to one of the thesmes that come with core (e.g. twentyfirteen, twentysixteen etc).

If the problem persists pleasse leave a post in the support forum: [https://wordpress.org/support/plugin/lh-user-taxonomies/](https://wordpress.org/support/plugin/lh-user-taxonomies/) . I look there regularly and resolve most queries.

= What if I need a feature that is not in the plugin?  =

Please contact me for custom work and enhancements here: [https://shawfactor.com/contact/](https://shawfactor.com/contact/)

= Can I contribute?  =

Yes, please! The number of users needing LH User Taxonomies is growing fast. Having an easy-to-use API and powerful set of functions is critical to managing complex WordPress installations. If this is your thing, please help us out!



== Installation ==

1. Upload the `lh-user-taxonomies` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use `register_taxonomy` as shown in the description


== Changelog ==

**1.00 February 28, 2015**  
* Initial release

**1.2 July 15, 2015**  
* Code improvements

**1.3 July 17, 2015**  
* Documentation links

**1.41 August 31, 2015**  
* Fix for saving taxonomies on profile when you need to remove term - thanks Greumb

**1.50 March 04, 2016**  
* Added bulk edit functionality

**1.50 March 04, 2016**  
* Added bulk edit functionality

**1.52 February 22, 2017**  
* Buildtree bug fix

**1.53 April 27, 2017**  
* registered_taxonomy fix

**1.54 April 30, 2017**  
* added show_in_menu support

**1.55 May 15, 2017**  
* better single value check

**1.56 July 27, 2017**  
* added class check

**1.57 May 06, 2019**  
* singleton pattern, capability check, and value removal

**1.60 September 22, 2020**  
* remove redundant code, menu fix

**1.61 March 19, 2021**  
* fix term count on deleted_user and linked count