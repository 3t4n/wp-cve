=== WP Post Navigation ===
Contributors: Anas Mir
Donate Link: http://sharp-coders.com
Tags: next post link, previous post link, post navigation, wp post navigation, next and previous post
Requires at least: 3.0
Tested up to: 4.7.4
Stable tag: trunk

Show Next and Previous Post Links at Posts.

== Description ==

WP Post Navigation Plugin gives you facility to show Previous and Next Post Links at the Top or Bottom of a Post.
You can set to navigate within category or date wise navigation. You've the option to set Post Navigation Bar at Bottom, Top or both Top & Bottom.
You can apply CSS style to Previous and Next Post Links. You can set custom text instead of Next and Previous Post Titles.
You can set custom Image for Next and Previous Post Links.
Manually Place Post Navigation in single.php, just Copy & paste following code anywhere in single.php page under get_header(); 
&lt;?php echo isset( $WPPostNavigation ) ? $WPPostNavigation->WP_Custom_Post_Navigation():''; ?>
For Help visit: http://sharp-coders.com/wp-post-navigation/

== Installation ==

= Step 1 =

1. Download zip file and Extract it 
2. Upload wp-post-navigation folder to your /wp-content/plugins/ folder.
3. Go to the 'Plugins' page in the menu and activate the plugin.

= Step 2 =

1. Go to Wodpress Admin Panel > Setting > WP Post Navigation
2. If you want to apply CSS style to Previous and Next Post Links, then write CSS Code in CSS Code for Links Box.
3. If you want to apply custom text to Previous and Next posts instead of Post Titles, you can enable the Option Custom Text and Set Previous & Next Post Text.
4. If you want to use Images for Previous and Next post links instead of text, you can enable Use Images Options and give complete url of the images.

== Frequently Asked Questions ==

= Which is the good place to show Post Navigation? =

In my opinion, at the Bottom of the Content. 

== Screenshots ==
1. Admin Panel Setting
2. WP Post Navigation

== Changelog ==
= 1.2.4
* Testet on WordPress 4.7.4

= 1.2.3
* Post Next/Previous position Reseverse option added
* Disable Auto place navigation option is added (You can disable auto and can place navigation manually)
* On updation you'll not lose your previous settings
* Bugs Fixed

= 1.2.2 =
* Bugs Fixed
* Position option is removed, by default shows at the bottom
* Manually Place Post Navigation anywhere in single.php file

= 1.2.1 =
* Bugs Fixed

= 1.2 =
* Compatible with WordPress 3.8
* Show Post Navigation above the Post Title

= 1.1 =
* Navigate Within Category Option Added

= 1.0.1 =
* Bugs Fixed