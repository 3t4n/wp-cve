=== Recent Posts Slider Responsive ===

Contributors: dilemma123

Donate link: http://www.anitamourya.com/donate

Tags: responsive slider, responsive carousel, posts, recent, recent posts, recent post, slider, latest posts, posts slider, posts carousel

Requires at least: 1.0.1

Tested up to: 4.1

Stable tag: /trunk/

License: GPLv2 or later

Recent posts slider responsive displays your blog's recent posts using flexisel carousel.

== Description ==

Recent Posts slider responsive displays your blog's recent posts either with thumbnail image of your post and the post title.

**Features you can Customize**

1. Total Posts Per Slider
2. Posts Per Slide
3. Slider Speed
4. Image Size
5. Auto Slide the Slider
6. Post to include / exclude
7. Category to display
8. Color of Post title / Background

**Shortcodes**

Just to use your default setting (can be customized from the admin panel settings) you can use

`[rpf]`

Using custom options in the shortcode

`[rpf category_ids="2,3" total_posts="2" post_per_slide="1" post_include_ids="1,10" post_exclude_ids="2,12" slider_id="1"]`

category_ids -  Posts to display with the Category ID given

total_posts - Total no. of post to display

post_per_slide - Posts to display per slide

post_include_ids - Posts ID should passed to include it in the slider

post_exclude_ids - Posts ID should passed to exclude it in the slider

slider_id - Slider CSS ID should be passed with unique value if you are using multiple slider per page, else it can be null

**Donate to this plugin**

[Donate Now](http://anitamourya.com/donate/ "Donate via Paypal")

If you find it useful please don't forget to rate this plugin.

== Installation ==

= Installation =

1. You can use the built-in installer.
	OR
	Download the zip file and extract the contents.
	Upload the 'recent-posts-slider-responsive' folder to your plugins directory (wp-content/plugins/).
1. Activate the plugin through the 'Plugins' menu in WordPress.

Now go to **Settings** and then **Recent Posts Slider Responsive** to configure any options as desired.

= How to use =

In order to display the recent posts slider responsive, you have three options

1. Simply place `<?php if (function_exists('rpf_display_slider')) echo rpf_display_slider(); ?>` in your theme 
or use `rpf_display_slider($category_ids, $total_posts, $post_per_slide, $post_include_ids, $post_exclude_ids, $slider_id);` to have different slider on same or different page templates.
2. Add the shortcode simply `[rpf]` or `[rpf category_ids="2,3" total_posts="2" post_per_slide="1" post_include_ids="1" post_exclude_ids="2" slider_id="1"]`
3. Using widget.

== Frequently Asked Questions ==

= Why there is need for Slider id? =

If you use many sliders in the same page, then it is necessary to have different css id for the div that we use to display the sliders, neither there will be clash as the div's will have same id. If you are using multiple slider on same page use `[rpf slider_id="1"]` else you can just use `[rpf]`

= In Widgets it just shows one slide per slider? =

As it is responsive, it takes the width to display the slider, for sidebar width, only one slider can accomodate, hence it shows one slide per slider.

= Having problems, questions, bugs & suggestions =

Please add them in WordPress Plugin support section

== Screenshots ==

1. Demo Frontend Page

== Changelog ==

= 1.0.1 =
* Rectifying the option "Full" option of Slider Image size and border boxing applied to Slider image.

= 1.0.0 =
* Initial release version.

== Upgrade Notice ==

= 1.0.1 =
* Rectifying the option "Full" option of Slider Image size and border boxing applied to Slider image.

= 1.0.0 =
* Initial release version.