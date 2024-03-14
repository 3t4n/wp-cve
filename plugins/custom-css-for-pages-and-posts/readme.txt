=== Custom CSS for Pages and Posts ===
Contributors: Ahjira
Tags: css
Requires at least: 3.5
Tested up to: 3.6.1
Stable tag: 1.1
License: GPLv2 or later



== Description ==

This plugin adds a metabox to all post and page edit screens ( and optionally to 
custom post type edit screens ) that allows the user specify custom CSS for that
page or post. All it does is wrap your CSS in <style type="text/css"></style> tags
and then inserts the block into the <head> section of your page/post. Pretty simple.



== Installation ==

1.  If you use FTP to upload your plugins, unzip the zip file and upload the resulting directoy to /wp-content/plugins/
    If you use the automatic uploader, there's no need to unzip the file first.
    
2. Activate the plugin through the 'Plugins' menu in WordPress

You will now see a new metabox on all edit screens for posts and pages.



== Screenshots ==

1. New metabox with Custom CSS text area



== Frequently Asked Questions ==

Q: How do I enable this for my custom post types?
A: Add this line to your theme's functions.php file and change POST_TYPE to the name of your post type. 

add_post_type_support( 'POST_TYPE', 'ahjira-custom-css');

Examples: 

add_post_type_support( 'book', 'ahjira-custom-css');
add_post_type_support( 'event', 'ahjira-custom-css');
add_post_type_support( 'product', 'ahjira-custom-css');

For more information, please visit the plugin's home page at:
http://ahjira.com/plugins/custom-css-for-pages-and-posts




== Changelog ==

= 1.1 =
• Updated for auto-update
• Refined the code for better performance

= 1.0 =
• Initial release




== Upgrade Notice ==

Nothing to note.