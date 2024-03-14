=== Styleguide - Custom Fonts and Colors ===
Contributors: binarymoon
Tags: customizer, css, color, colors, colour, colours, fonts, google fonts, localize, localization
Requires at least: 4.5
Tested up to: 6.5.0
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Styleguide allows you to customize fonts and colors in WordPress themes through the Customizer - no need to touch any code!

== Description ==

Quickly and easily edit fonts and colors in your WordPress themes using the WordPress Customizer so that you can get live previews before saving the new settings.

Perfect for giving your website a unique look without having to hire a designer or make code changes yourself.

All default WordPress themes are fully supported and support for more themes will be added in the future. All other themes can customize fonts.

Styleguide uses a carefully chosen selection of the top 45 Google Fonts to give you a lot of options for personalising your site.

= Features =

* Choose from a varied selection of the top Google fonts in any theme
* Additional themes (listed below) support editing colors
* Filter fonts based on character set - great for non latin languages such as Russian
* Developer functionality for adding support to other themes, and for adding additional fonts.

= Supported Character Sets =

Styleguide supports fonts that have a variety of different character sets. This makes selecting a font for your language super easy. The supported character sets are:

* Cyrillic
* Devanagari
* Greek
* Hebrew
* Latin
* Vietnamese

By default Styleguide uses Latin. To limit the font choice to those supporting your character set you should go to *Settings &rarr; General &rarr; Character Set* and select your set there.

= Supported Themes =

* [Kent](https://wordpress.org/themes/kent)
* [Bromley](https://wordpress.org/themes/bromley)
* [Romero](https://prothemedesign.com/theme/romero/)
* [Puzzle](https://prothemedesign.com/theme/puzzle/)
* [Chronicle](https://prothemedesign.com/theme/chronicle/)
* [Broadsheet](https://prothemedesign.com/theme/broadsheet/)
* [Lens](https://prothemedesign.com/theme/lens/)
* [Monet](https://prothemedesign.com/theme/monet/)
* [Mimbo Pro](https://prothemedesign.com/theme/mimbopro/)
* [Opti](https://prothemedesign.com/theme/opti/)
* [Adaline](https://themetry.com/shop/adaline/)
* [Twenty Ten](https://wordpress.org/themes/twentyten)
* [Twenty Eleven](https://wordpress.org/themes/twentyeleven)
* [Twenty Twelve](https://wordpress.org/themes/twentytwelve)
* [Twenty Thirteen](https://wordpress.org/themes/twentythirteen)
* [Twenty Fourteen](https://wordpress.org/themes/twentyfourteen)
* [Twenty Fifteen](https://wordpress.org/themes/twentyfifteen)
* [Twenty Sixteen](https://wordpress.org/themes/twentysixteen)

Developers can add support for their themes quite easily - see the 'Other Notes' tab for more info.

If you're in the market for a WordPress theme then you could check out my Premium GPL WordPress themes site here: https://prothemedesign.com/

== How To ==

After downloading and installing the plugin you can go to the Customizer (Appearance > Customizer) and edit fonts and colors in the 'Colors & Fonts' panel.

= Adding Theme Support =

Styleguide allows any theme to add support through the `add_theme_support` command. For examples check out the theme-styles directory.

I have added an example of a basic implementation below. This code would be placed in your themes functions.php

`function prefix_add_styleguide_support() {

  // for properties check out the included theme styles as seen here:
  // https://github.com/BinaryMoon/styleguide/tree/master/theme-styles
  $properties = array(
	...
  );
  add_theme_support( 'styleguide', $properties );

}

add_filter( 'after_setup_theme', 'prefix_add_styleguide_support' );`

= Extra Fonts =

Styleguide currently offers developers a filter for adding additional fonts. You can use it as shown below

`function my_fonts( $font_list ) {
	$font_list['Cormorant Infant'] = array(
		'name' => 'Cormorant Infant',
		'family' => '"Cormorant Infant", serif',
		'weight' => '400,700',
		'sets' => array( 'latin' ),
	);
	$font_list['Poppins'] = array(
		'name' => 'Poppins',
		'family' => 'Poppins, sans-serif',
		'weight' => '400,700',
		'sets' => array( 'latin' ),
	);
	return $font_list;
}
add_filter( 'styleguide_get_fonts', 'my_fonts' );`

== Screenshots ==

1. An example of Twenty Eleven having it's fonts changed
2. Twenty Thirteen with some alternate colors

== Changelog ==

= 1.8.1 =
* Improve theme support.
* Improve coding standards, and fix potential XSS issues.

= 1.8 =
* Add support for Bromley theme.
* Optimize colour contrast calculations.
* Improve text contrast for improved accessibility.

= 1.7.2 =
* Fix Puzzle theme styles based on recent theme changes.

= 1.7.1 =
* Tested with the latest version of WordPress.
* Add support for Carmack theme.
* Add Nunito and Nunito Sans fonts.

= 1.7 =
* Add support for Romero theme
* Improve coding standards
* Scroll selected font into view when first opening the customizer to make it easier to see what is currently being used.
* Tweak customizer styles

= 1.6.1 =
* More tweaks to Chronicle
* Add support for TwentySeventeen custom fonts
* Improve localizations further (ensure all text is written in American)

= 1.6 =
* Fix Chronicle styles
* Make localizations work

= 1.5.2 =
* Tweak css sanitization to allow more CSS rules
* Add support for Chronicle theme
* bump version number (now tested with WP 4.5.2)

= 1.5.1 =
* improve support for Opti theme
* improve support for Monet theme
* bump version number (now tested with WP 4.5)
* change default font weight property so that it behaves more nicely

= 1.5 =
* Various code and security improvements
* Add support for Adaline theme
* Improve Monet theme colors

= 1.4.1, 1.4.2 & 1.4.3 =
* fix bugs introduced with character sets

= 1.4 =
* Add support for next years default theme - Twenty Sixteen (requires WP4.4)! :)
* Add a link to the Kent theme - which is out now - https://wordpress.org/themes/kent
* Option to select character set from *Settings &rarr; General*
* Filters available fonts according to supported character sets
* Loads character sets as required

= 1.3.1 =
* Better fallbacks for non latin fonts

= 1.3 =
* Allow font weights to be selected for each font
* Improve font weights for more attractive letters
* Add support for some themes that have recently been submitted to wordpress.org repo
* Add support for more of my premium themes
* Fix a some minor bugs and add additional security
* Move screenshots to plugin assets directory to make download package a little smaller

= 1.2.1 =
* make sure the font preview styles load

= 1.2.0 =
* add new fonts
* add font preview control in customizer
* various small tweaks and bug fixes

= 1.1.0 =
* Note that this update may break some of the settings. This is a one time problem and I apologise for the frustration!
* fix how Styleguide enqueues fonts with more than one word in the name
* add some new fonts to play with

= 1.0 =
* first version live! :)
