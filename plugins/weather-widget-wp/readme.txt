=== Weather Widget WP ===
Contributors: ajdethemes
Tags: weather, current weather, today weather, temperature, temp, wind, rain, celsius, fahrenheit
Requires at least: 5.0
Tested up to: 6.1
Stable tag: 1.0.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display weather information for a specific location.

== Description ==

This plugin allows you to show the current weather info for any location. You can show the temperature in Celsius or Fahrenheit units, show wind speed, max & min temperature, weather description, date and weather icon.
You can use this plugin as a block (with a name "Weather Widget WP") in the WordPress block editor in a page, post or as a widget in a sidebar, or as a shortcode - example `[weather_widget_wp_location city="London" title="London, UK"]`.


= Customization =

You can customize the weather widget however you like. The widget have two styles: minimal & expanded that show different amount of data.

When using the custom block, click the gear icon (top right) to show the settings, under Customize Style there numerous settings to style the widget in order to match your website style.


= API Key =

The plugin gets the weather data from Open Weather API, so you will need to have account & API key to use it. If you don't have an account follow the instructions in the plugin settings page to setup an account.

Also, keep in mind that **newly created API keys will need ~ 15 min to be activated and show data, so you might see an API error in meantime**.


= Caching =

You can utilize the caching feature from the plugin settings page, to save (cache) the weather data for number of hours, so you don't exceed the you API free tier rate too fast.
This is enable by default, so the weather data on the front-end might not match the back-end editor (this is because of the caching).
Setting the cache setting to 0 will disable the caching - not recommended.


= Shortcode [weather_widget_wp_location] =

Recommended use of this plugin is as a block via the WordPress block editor - for ease of use, but you can use the plugin also as a shortcode, bellow are all the shortcode attributes explained.

Example: `[weather_widget_wp_location city="Santorini" title="Santorini, Greece" style="style-minimal" units="F" desc=1 icon=0]`

* `style` - two options: 'style-expanded' or 'style-minimal', e.g. `style="style-minimal"`;
* `city` - location for which the weather data will be shown e.g `city="london"`;
* `title` - the title inside the widget e.g. `title="London, UK"`;
* `units` - temperature units, two options Celsius or Fahrenheit, e.g. `units="C"`;
* `desc` - show short weather description (1 - enable, 0 - disable), e.g. `desc=0`;
* `icon` - show weather icon (1 - enable, 0 - disable), e.g. icon=0;
* `date` - show the current date (1 - enable, 0 - disable), e.g. `date=0`;
* `wind` - show the wind speed (1 - enable, 0 - disable), e.g. `wind=0`;
* `max` - show the max. temperature prediction (1 - enable, 0 - disable), e.g. `max=0`;
* `min` - show the min. temperature prediction (1 - enable, 0 - disable), e.g. `min=0`;
* `block_align` - alignment of the widget (left, right, center, wide, full);
* `css_class` - add custom css class;
* `css_style` - add custom inline style;


= Errors (API Errors) =

* Most common error is the "new API key" - does not show any weather data and it says "Weather API Error" each new API key created will need (around) 15 minutes to be active and show data so please be patient and check again after 15 min.;
* Typo in the location input field, make sure your location is correctly written;
* It is (rare) possibility that the Open Weather API currently down;
* You have exceeded the free tier of you Open Weather account and they are rate limiting you - this is possible if you have a huge amount of visitors to your website,
so make sure you are utilizing the caching feature in the plugin settings page.


== Screenshots ==

1. Weather Widget WP used as block via the block editor and the setting panel open.
2. Customization settings for the weather widget wp block.
3. Setting page of the Weather Widget WP plugin.


== Installation ==

= Installation from within WordPress =

1. Visit Plugins > Add New
2. Search for **Weather Widget WP**.
3. Install and activate the Weather Widget WP plugin.


= Manual installation =

1. Upload the entire `weather-widget-wp` folder to the `/wp-content/plugins/` directory;
2. Activate the plugin through the **Plugins** screen (**Plugins > Installed Plugins**);

= After Activation =

You will find **Weather Widget** menu in your WordPress admin screen. Click on that and add your own **Open Weather API** key, in order to show weather data in the widget.
It will need around **15 min for newly created api keys to show data**, so you might see API error shown in the meantime, so please be patient and check again after 15 min.


== Frequently Asked Questions ==

= Why the weather data is incorrect at (some) location? =

The plugin only display the weather data received from the Open Weather API, we don't know what the data is and if is correct or not.


= Why the weather data in the back-end does not match the front-end? =

The weather data on the front end is cached (by default) and in the back-end is not, so in rare case it might not match, that means caching works.
You can adjust the caching time via the plugin settings page, set 0 to disable it - not recommended.


= Why the widget shows API Error/s? =

* Most common error is the "new API key" - does not show any weather data and it says "Weather API Error" each new API key created will need (around) 15 minutes to be active and show data so please be patient and check again after 15 min.;
* Typo in the location input field, make sure your location is correctly written;
* It is (rare) possibility that the Open Weather API currently down;
* You have exceeded the free tier of you Open Weather account and they are rate limiting you - this is possible if you have a huge amount of visitors to your website,
so make sure you are utilizing the caching feature in the plugin settings page.

= Is support available for this plugin? =

This is a free plugin, so there is no active support, but your feedback - especially bug reports is welcomed.
You can submit your feedback in the plugin support forum or get in touch with the plugin author on the [Ajdethemes website](https://ajdethemes.com/), any reported bug will be fixed as soon as possible.

= Where can I submit my plugin feedback? =

Providing feedback is encouraged and much appreciated! You can submit your feedback either in the plugin support forum or, if you have a specific issue to report, via the [Ajdethemes website](https://ajdethemes.com/).


== Changelog ==

For more information, see [Releases](https://ajdethemes.com/weather-widget-wp/releases/).

= 1.0.0 =
Initial release.
