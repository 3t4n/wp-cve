=== Plugin Name ===
Contributors: bmodesign2
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4AWSR2J4DK2FU
Tags: nextgen, scrollgallery, scrollgallery2, scroll-gallery, bmo-design, expo, exposition, plugin, picture, pictures, photo, photos, widgets, photo-albums, post, posts, page, admin, media, scroll, gallery, image, images, slideshow, galerie, jquery, javascript, next gen, next, generation, Style
Requires at least: 3.1
Tested up to: 3.9.1
Stable tag: trunk

BMo Exposition is a gallery and exhibition plugins for wordpress. It allows you to replace default wordpress galleries and NextGen galleries with beautiful Javascript galleries.

== Description ==

**BMo Expo** is one of the best gallery and exhibition plugins for wordpress. It allows you to replace default wordpress galleries and NextGen galleries with impressive gallery designs. The plugin is easy to use and configure. Slideshow, vertical scroll, Lightbox and more could be used. Perfect for photographers, artists or exhibitor. Try it out or watch the demo video.

[youtube https://www.youtube.com/watch?v=YZS_fkrQieQ]

The plugin is an evolution of the successful [NextGEN Scroll Gallery](http://wordpress.org/plugins/nextgen-scrollgallery/) plugins.

You can use it as stand alone gallery viewer for your default wordpress galleries or as extension the very cool Wordpress gallery manager NextGen Gallery. 


After the installation, you will find an admin interface, with which you can adjust the plugin settings.
This gallery do not use flash, so no extra browser plugin is required and search engines can crawl your content easily.
If you have some questions or you need instructions look at [BMo-Design - BMo Exhibition](http://software.bmo-design.de/wordpress-plugin-bmo-exhibition.html). There you will find instructions and discussions, which can help you.
If you want a special adaptation to the needs of your page, you can commission me at [BMo-Design](http://BMo-design.de/kontakt/).

Tested with Safari 6, FF 24, IE 9.

== Screenshots ==

1. Screenshot BMo Expo scrollGallery Example - Design Classic Black
2. Screenshot BMo Expo scrollGallery Example - Design Shadow
3. Screenshot BMo Expo scrollGallery Example - Admin Interface
4. Screenshot BMo Expo scrollGallery Example - Admin Options

== Installation ==

1. download, upload & activate this plugin 

= Use the Plugin with the default WP Gallery =

The plugin will automatically replace the default wordpress gallery shortcode [gallery]. You can change the visualization by changing the global options at the options page.

The options can be overridden in the post/page tag. For example: [gallery ids="1,2,3" duration=slow gallery_width=600 slG_vertical=0]

That's it ... Have fun!

[youtube https://www.youtube.com/watch?v=YZS_fkrQieQ]

= Use the Plugin together with the NextGen Gallery =

It's very easy to enter a new BMo Expo Gallery into your post or page.

Just click the "BMoExpo" editor button and select one of your NextGen Galleries.

That's it ... Have fun!

[youtube http://www.youtube.com/watch?v=fVR1aTLGPBs]


== Frequently Asked Questions ==

= How to add a custom theme? =

 * Build your custom css theme is easy. Just create a folder with the name “bmo-expo-themes” in “wp-content”. Create a second folder “scrollGallery” and a third folder “scrollLightboxGallery” hierin. In these folders you can save your own css themes. Every theme need a “CSS Name:”, a “Description:” an “Author:“ and a “Version:“. A good template provides the “classicBlackDesign_sG.css” in bmo-expo/css/themes/scrollGallery/classicBlackDesign_sG.css.
 * You will find a example at my [FAQs](http://software.bmo-design.de/wordpress-plugin-bmo-exhibition/faq.html).

= In which languages ​​is the plugin available? =

 * The plugin is translated in English, German and Serbo-Croatian.
 * If you translate the plugin in other languages, please send me your translation. It would be great if you let the community benefit from your language skills. Translating a plugin is very easy. If you would like to help me to translate the plugin in other languages, then use the .po file available in the plugin folder. Download the tool poEdit (http://www.poedit.net/) and you can translate the file.
 * Thank you to Borisa Djuraskovic and [Web Hosting Hub](http://www.webhostinghub.com/) for translating the plugin to Serbo-Croatian.

= [No pictures in galley] =

 * If you having an issue showing a NextGen Gallery, it could help to recreate the meta information of your gallery.
 * Therefore go to your NextGen Gallery (Manage Galleries), mark all images in this gallery, select the bulk action “Import metadata” and click “apply”. 

= Have a question? =

 * Ask the forum: [forum](http://wordpress.org/tags/bmo-expo/).
 * Look at [BMo-Design - BMo Exhibition](http://software.bmo-design.de/wordpress-plugin-bmo-exhibition.html), you will find instructions and discussions, which can help you.
 * You also can commission me at [BMo-Design](http://bmo-design.de).

= Compatibility Problems =

Not compatible with IE 7,6,5...

= Developer tips =

== Changelog ==

= Version 1.0 =
	* 1.0.15 Fix for the new NextGen Gallery MetaData Format
	* 1.0.14 TinyMCE fix for WP 3.9 
	* 1.0.13 Checkbox Fix for NextGen Gallery Version
	* 1.0.12 CSS Safari Fix
	* 1.0.11 Fix for passing no attributes
	* 1.0.10 Wordpress 3.8 fix
	* 1.0.9 Integrated a option for switching from NextGen Scroll Gallery to BMo Expo.
		A big thank you to Borisa Djuraskovic and Webhostinghub for translating the plugin to Serbo-Croatian.
	* 1.0.8 lightbox none responsive js fix
	* 1.0.7 js height fix for the responsive feature
	* 1.0.6 german translation
	* 1.0.5 scrollGallery img width fix
 	* 1.0.4 Admin CSS changes 
    * 1.0.3 No Borders Scroll Gallery Design, Possibility to add custom themes in the folder wp-content/bmo-expo-themes/[gallerytype]/ 
    * 1.0.2 Thumb size fix, thanks to grifmo
    * 1.0.1 Add option to show/hide the caption 
    * First release
	* BMo Expo Version 1.0 - a new version of the BMo Nextgen Scroll Gallery 