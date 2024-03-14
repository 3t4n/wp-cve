=== Live CSS Preview ===

Contributors: dojodigital
Plugin Name: Live CSS Preview 
Plugin URI: http://dojodigital.com/developer-tools/live-css-preview/
Tags: wp, css, customize
Author URI: http://dojodigital.com/
Author: Dojo Digital
Requires at least: 3.4
Tested up to: 5.0
Stable tag: 2.0.0
Version: 2.0.0 

Write, preview & implement css code in real time.

= 2.0.0 =
* Added the ability to edit CSS from the front end.

== Description ==

This plugin adds a textarea to the new Customize page that allows theme editors to write, preview & implement css code in real time. 
 
== Installation ==

1. Upload the `live-css-preview` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Screenshots ==


1. live css snapshot1
2. live css snapshot2

== Frequently Asked Questions ==

= Will the css be applied to my live site if I hit "Save & Publish"? =

Yes. The plugin is designed to not only show a preview of your css changes, but to save those changes to the options table and implement them on the front-end as well. It is also useful as a developer tool so you might want to take the changes you've made out of the Live CSS Preview box and paste them into your theme's style.css file. 

= Will the Live CSS Preview allow me to write @media-queries? =

Yes, in fact that is excactly what it was originally designed for!

= My css doesn't seem to have any effect. How do I override my themes default css? =

The Live CSS Preview plugin adds a new class to the body tag called `livecss` for exactly this situation. Most of time you'll want to avoid this, but when necessary you can add `.livecss` or `body.livecss` to the beginning of your css declaration and in most cases it will do the trick. 

For example, when trying to change the background color of the body tag in Twenty Eleven, the following won't work:

`body { background-color:red; }`

Adding the `livecss` class will override the themes specicifity and force the change:

`body.livecss { background-color:red; } /* This will work */`

NOTE: If you deactivate or delete the plugin, the `livecss` class will no longer be available to code you've added to your style.css file. To maintain this feature beyond the life of the plugin, just add the following code to your functions.php file:

`function my_body_class_override( $classes ){ 	
	$classes[] = 'livecss';
	return $classes;	 	
} add_filter( 'body_class', 'my_body_class_override' );`
