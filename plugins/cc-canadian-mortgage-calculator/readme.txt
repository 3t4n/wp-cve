=== CC Canadian Mortgage Calculator ===
Contributors: CalculatorsCanada.ca
Donate link: https://calculatorscanada.ca/
Tags: Canada, Canadian mortgage, loan, mortgage, mortgage calculator, interest, calculator, sidebar, widget, plugin, financial, shortcode
Requires at least: 3.0
Tested up to: 6.3.2
Stable tag: 2.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a free simple customizable Canadian mortgage calculator to your web site. 

== Description ==

This simple [Canadian mortgage calculator](https://calculatorscanada.ca/mortgage-calculator/) calculates mortgage monthly payments. 

Calculator is very easy customizable: you can change colour of background, borders and text to match your web site's theme and change widget title.

Note: check [this mortgage calculator plugin](https://wordpress.org/plugins/cc-mortgage-calculator/) if you are looking for mortgage calculator for other country then Canada.

== Installation ==

1. Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Appearance -> Widgets and add the widget to your website sidebar
OR
 Use [cc-mortgage-canada] short code if you want embed the mortgage calculator into a post instead of adding it as a widget on sidebar.

 Short code parameters are:
  * title (optional) - set calculator's title (default - "Mortgage calculator")
  * dev_credit (optional) - show developer's credit (default - "1") 
  * bg_color (optional) - set background color (default - "#ffffff")
  * border_color (optional) - set border color (default - "#cccccc")
  * text_color (optional) - set text color (default - "#000000")

Example of shortcode usage: [cc-mortgage-canada title="Canadian Mortgage Calculator" border_color="#6291c5"]

Use [Color Picker](http://www.colorpicker.com/) to get hex code of color you need. 

Please visit [plugin home page](https://calculatorscanada.ca/mortgage-calculator-wordpress-widget/) for more detailed installation and setup instructions

== Frequently Asked Questions ==

= Can I use this widget on commercial website =
Yes. 

= I am getting error during plugin activation =
For some unknown reason this plugin doesn't work on some commercially available WP themes. Unfortunately we won't be able to help in such case as we don't have access to these themes and we can't test what is causing an error.

Please [contact us](https://calculatorscanada.ca/contact/) if you have further questions or suggestions. In case you have an issue with this plugin please send us WordPress version, the name of theme and list of plugins you are using.


== Screenshots ==

1. Widget settings in appearance panel
2. Widget example on the sidebar
3. Shortcode usage example in post
4. Widget example in WP post/page

== Changelog ==

= 2.0.6 =
* Deprecated PHP function fix (create_function() deprecated)

= 2.0.5 =
* fixed spelling error

= 2.0.4 =
* fixed javascript conflicts
* fixed issues with commas for thousands

= 2.0.3 =
* minor issue with source version 

= 2.0.2 =
* currency sign wasn't shown on some web browsers

= 2.0.1 =
* fixed major issue with currency display

= 2.0.0 =
* Added commas for thousands 
* Input fields changed: now you can enter Purchase price and Down payment
* Mortgage amount is calculated automatically accordingly

= 1.1.0 =
* Supports Shortcode

= 1.0.0 =
* Initial release

== Upgrade Notice ==
* fixed spelling error