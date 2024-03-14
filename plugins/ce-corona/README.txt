=== COVID19 - Coronavirus Outbreak Data ===
Contributors: priyomukul, CoderExpert
Donate link: https://mukul.me
Tags: corona, coronavirus, covid19
Requires at least: 3.5
Tested up to: 5.7
Requires PHP: 5.6
Stable tag: 0.7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Coronavirus disease (COVID-19) is an infectious disease caused by a new virus.
According to WHO( World Health Organization ), This disease causes respiratory illness (like the flu) with symptoms such as a cough, fever, and in more severe cases, difficulty breathing. You can protect yourself by washing your hands frequently, avoiding touching your face, and avoiding close contact (1 meter or 3 feet) with people who are unwell.

This plugin displays the Coronavirus ( Covid19 ) case data of the whole world and country you care through a shortcode [ce_corona] in your WordPress post or page. You can use this shortcode with some attributes also. For example: [ce_corona data_table=true now=true compareCountry=true]. If data_table is set to false, now and 'compareCountry' will be false automatically.

## Again, How to use it ##
You can use this plugin via a shortcode with some attributes.
### For Example ###
`[ce_corona], [ce_corona data_table=false], [ce_corona compareCountry=false]`

OR

`[ce_corona countries=BD,US,IT]`

OR

`[cec_corona], [cec_corona country_code=US], [cec_corona country_code=US states=true]`

OR

`[cec_graph], [cec_graph data="us,it,bd"]`

You can see how many patient(s) are confirmed, dead or recovered in the world and the country or region you select.

### 🔥 Documentation  ###
Please see at Corona menu in WP Admin Panel.

### 🔥 Feature  ###
* You can search by country.
* You can compare by country on a specific date.
* This Plugin uses data from https://worldometers.info/coronavirus
* This Plugin is free to use.

God bless the people of the world. May everyone in this world be healthy.

## 🔥 Credits  ##
* API: https://github.com/pomber/covid19.
* API: https://github.com/NovelCOVID/API

Privacy Policy: https://github.com/NovelCOVID/API/blob/master/privacy.md

Feel free to suggest if you have any suggestions, regarding plugin or it's use cases.

== Installation ==

= Modern Way: =
1. Go to the WordPress Dashboard "Add New Plugin" section.
2. Search For "Corona".
3. Install, then Activate it.

= Old Way: =
1. Upload `corona` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Does it work with any WordPress theme? =

Yes, it will work with any standard WordPress Theme.

== Screenshots ==

1. Total Stats
2. Country-wise data data_table
3. Graph Comparison by Region or Only For Cases
4. New Style of Data Table with Lots of Data
5. WordPress Widget.
6. Country-wise compares for a specific date.

== Changelog ==

= 0.7.0 =
* Fixed: API issue.

= 0.6.3 =
* Fixed: CORS issue.

= 0.6.2 =
* Fixed: Coutry-wise Data - Shortcode, States for US.

= 0.6.1 =
* Fixed: Negative Data

= 0.6.0 =
* Added: Corona - Graph Elementor Elements.
* Added: Corona - Graph Shortcode. `[cec_graph]` - For More Info Check Documentation Page.
* Improved: CORS issue fixed
* Enhanced: Country Wise Elementor Elements.
* Enhanced: Corona Elementor Elements.

= 0.5.5 =
* Fixed: CORS issue fixed
* Enhanced: Country Wise Elementor Elements.

= 0.5.4 =
* Fixed: WP_Widget
* Enhanced the WP_Widget

= 0.5.3 =
* Elementor Improvement
* New: Some options for cec_corona shortcode, check Documentation
* Language Translation Improvement

= 0.5.2 =
* Fixed: Elementor Improvement
* New: Country Choice for Data Table

= 0.5.1 =
* Fixed: Mobile Issue
* Some overall improvement.

= 0.5.0 =
* Fixed: States Wise Data for USA
* Fixed: Translation Issue Fixed

= 0.4.2 =
* Fix API Issue

= 0.4.1 =
* Fix API Issue

= 0.4.0 =
* Fix Responsive Issue
* New Style for Data Table with Lots of Data
* WP Widget in Elementor Screen Fix

= 0.3.2 =
* Fix Ordering Issue
* Fix Calendar CSS and Country Select CSS Issue.

= 0.3.1 =
* Fix API Response
* World Entry Removed from Data Table

= 0.3.0 =
* WordPress Widget Introduced

= 0.2.1 =
* Elementor Elements Introduced
* New Shortcode `[cec_country]` Introduced
* Lots of new attributes support added for both shortcode.

= 0.1.1 =
* Shortcode attributes fixed.

= 0.1.0 =
* Initial Release

== Upgrade Notice ==

= 0.7.0 =
Attention! Please Backup your site before updating.

The latest version of **BetterDocs v2.5** includes massive changes across different areas of the plugin with revamped code structure for optimized performance. We would highly recommend you to backup your entire site before updating the plugin & test it on your staging website. [Contact our Support](https://wpdeveloper.com/support/) if you encounter any kind of errors or if you need any kind of assistance.