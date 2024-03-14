=== Resume / CV ===
Contributors: wpamanuke
Tags: resume , cv , resume-cv, bootstrap, fontawesome, curiculum vitae, resume templates
Requires at least: 4.9
Requires PHP: 5.6
Tested up to: 5.2.2
Stable tag: 1.2.1
License: GPLv2 or later


Resume CV WordPress Plugin . Easily build resume with wordpress.

== Description ==

Create Resume / CV easily with WordPress. Perfect way tools that help you to make positive impression. This minimal and modern design will highlight your most relevant features to get you noticed and create a consistent voice of your personal brand in all communications. To use this plugin :
* Create a Page and in the Page Attribute , choose Template : Resume CV Template
* In the admin area . Click Resume CV and do modification than save
Be sure to check out the **[online demo](http://wpamanuke.com/resume)**! 
Here is too see red version **[Red Template](http://wpamanuke.com/resume/?template=shark-2)**!

= A quick overview video: =
[youtube https://www.youtube.com/watch?v=kE9_G0q3vfE]

= Features =

* Profile
* Contact
* Qualification
* Experience
* Education
* Hobby
* Skill

= Social Network Supported =
* facebook
* twitter
* instagram
* youtube
* linkedin


== Frequently Asked Questions ==

= Where is the documentation ? =
Here it is [Resume CV Documentation](http://wpamanuke.com/resume-cv/ "WordPress Resume CV plugin documentation")

= How To Create Your Own Resume CV Theme =
Create new folder in your resume plugin , just copy themes in /resume-cv/themes/ and make it like this  /your-resume-plugin/themes/ and do modification which you need.
In your main plugin functions. For example your theme name myresumetheme , in you plugin function just add this code. 

```
function resumecvsample_theme_filter_add($resumecv_theme) {
	$resumecv_theme[plugin_dir_path( __FILE__ ) . 'themes/myresumetheme'] = 'myresumetheme';
	return $resumecv_theme;
}
add_filter('resumecv_theme_filter', 'resumecvsample_theme_filter_add');
```

== Screenshots ==

1. Resume CV Admin Area
2. Shark Resume Template
3. Shark-2 Resume CV Template

== Credits ==

* CMB2 https://wordpress.org/plugins/cmb2/, (C) 2018 Justin Sternberg , Zao , webdevstudios , Michael Beckwith  , GNU GPL v2 or later
* Font Awesome 4.7.0 ,Created by @davegandy, http://fontawesome.io - @fontawesome , MIT License Font: SIL OFL 1.1, CSS: MIT License
* Bootstrap v3.3.7 http://getbootstrap.com, Copyright 2011-2018 Twitter, Inc. , MIT License
