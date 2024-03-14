=== wp-forecast ===
Contributors: tuxlog
Donate link: http://www.tuxlog.de
Tags: weather,forecast,widget,openweathermap,open-meteo
Requires at least: 4.0
Tested up to: 6.4
Stable tag: 9.3
wp-forecast is a highly customizable plugin for wordpress, showing weather-data from open-meteo.com and/or openweathermap.com.

== Description ==
You are reading the readme.txt file for the wp-forecast plugin.
wp-forecast is a plugin for the famous wordpress blogging package,
showing the weather-data from open-meteo.com and/or openweathermap.com. 
please also refer to the terms of usage of open-meteo.com and/or openweathermap.com.

Features:

   + Displays the weather data from Open-Meteo.com and OpenWeatherMap.com 
     at your wordpress pages, posts or sidebar
   + Displays OpenUV.io data 
   + Let you choose the 
	 * location (of course)
	 * the time after the weather data is refreshed
	 * the langugage 
	 * metric or american measures
	 * windspeed unit 
	 * the forecast days 
	 * the forecast for up to six/seven days
   + support wordpress widgets, easy placement :-)
   + customize the information you want to show
   + supports pull-down forecast data to efficiently use space
   + multiple wp-forecast widget support 
   + integration into your site via css (see below)
   + comes with an api for wordpress-pro's ;-)

Credits:

 + Barbary Jany					testing a lot and bring it to valid XHTML
 + Frans Lieshout
   Wim Scholtes					translation to dutch
 + Luís Reis					translation to portugues
 + Håkan Carlström, 
   Susanne Svensso				translation to swedish
 + Gabriele von der Ohe			translation to german with entities (for iso-8859-1 or latin1 blogs)
 + Martin Loyer/Jean-Pierre		translation to french
 + Robert Lang					language file for en_US
 + Detti Giulio/Stefano Boeri	translation to italian
 + Eilif Nordseth				translation to norwegian
 + Michael S.R. Petersen		translation to dansk
 + Jaakko Kangosjärvi			translation to finish
 + Lukasz "linshi" Linhard		translation to polish
 + Castmir & Alejandro 			translation to spanish
 + Tamas Koos					translation to hungarian
 + Valeria Pellegrini			translation to russian
 + Valentina Boeri				translation to romanian 
 + Roland Geci					translation to slovak
 + Pavel Soukenik				translation to czech
 + Pavel Karnaukhov				translation to ukraine
 + Zoran Maric					translation to serbian (latin)
 + Petar Petrov   	            translation to bulgarian
 + Udi Burg						translation to hebrew
 + Uli Kozok					translation to indonesian
 + Ali Zemani					translation to persian
 + Amizda Idriz					translation to bosnian
 +Lovrenco Vladislavić 			translation to croatian
 
 + All the others giving feedback about missing features and bugs.   
   Thank you very much for your contribution to wp-forecast.   


== Installation ==

0. Install via the WordPress plugin admin dialog (search for wp-forecast) or

1. Upload to your plugins folder, usually `wp-content/plugins/`, keeping 
   the directory structure intact \(i.e. wp-forecast.php should end up 
   in `wp-content/plugins/wp-forecast/`\).

2. Activate the plugin on the plugin screen.

3. Visit the configuration page \(`Options -> WP-forecast`\) to pick the 
   number of widgets, data to display and to change any other option.

4. Visit the Themes/Widgets page to place your wp-forecast widget within 
   your themes sidebars or insert it manually and edit your template 
   file and put the wp-forecast function where you want your weather 
   data to show up.
   Example: `<ul><li>
                 <?php if(function_exists(wp_forecast)) { 
                   wp_forecast( <widget_id> ); 
                 } ?>
             </li></ul>`

     You have to replace <widget\_id> with the choosen widget id.
     For the first widget use wp\_forecast("A"), for the second 
     wp\_forecast("B") and so on.
     In most cases it is advisable to put the call into a div environment.

5. Optional
   If you would like to have another set of icons download it
   from <http://accunet.accuweather.com/wx/accunet/graphics_icons.htm>
   and put it into the wp-content/plugins/wp-forecast/icons folder

6. Optional
   If you would like to change the style, just edit wp-forecast.css
   there are three classes div.wp-forecast for outer formatting, 
   table.wp-forecast for the middle part or iconpart and 
   wp-forecast-details for everything below the icon


== Translations ==

   wp-forecast comes with various translations, located in the directory `lang`.
   if you would like to add a new translation, just take the file
   wp-forecast.pot (in the wp-forecast main directory) copy it to
   wp-forecast_<iso-code>-<iso-code>.po and edit it to add your translations 
   (e.g. with poedit).

   Please be aware that the number codes stand for the weather-situations.
   you can see the mapping in wp-forecast-en_US-en_US.po. there are also 
   the letters N, S, W, E they stand for the winddirections and can be 
   translates either.

   To use your own translation, a different one as the default just rename the 
   appropriate file wp-forecast_<iso-code>-<iso-code>.po and 
   wp-forecast_<iso-code>-<iso-code>.mo

   To add your translations online at the Translate WordPress project please visit
   https://translate.wordpress.org/projects/wp-plugins/wp-forecast. This should be the prefered
   way from now on.



== Frequently Asked Questions ==
= Where can I get further information about the plugin? =

There are severeal ressources to visit:

* [The german wp-forecast page][germanpage]
* [The german reference][germanref] 
* [A short FAQ list in german][gerfaq]
* [A checklist in case of problems (german and english)][checklist] 
* [The english readme.txt][engreadme]
* [The english reference (a bit out of date but still useful)][engref]

[germanpage]: https://www.tuxlog.de/wp-forecast/ 
          "German wp-forecast-page"
[germanref]: https://www.tuxlog.de/wp-forecast-handbuch/ 
          "German wp-forecast-manual"
[gerfaq]: https://www.tuxlog.de/wordpress/2007/wp-forecast-wie-geht-das/
	  "wp-forecast FAQ - Wie geht das?"
[checklist]: https://www.tuxlog.de/uncategorized/2009/checkliste-fur-wp-forecast-checklist-for-wp-worecast/
          "wp-forecast checklist for connection problems"
[engreadme]: https://wordpress.org/plugins/wp-forecast/
          "you are actually reading this one"
[engref]: https://www.tuxlog.de/wordpress/2008/wp-forecast-reference-v17-english/
          "english reference for wp-forecast"

= After upgrading the plugin my widgets are gone. Any Ideas? =

Probably you used the automatic upgrade which disables the widget during update and sometimes removes the configured widgets. Just add the widgets again.

= After upgrading my settings are gone. How can this be? =

Probably you have enabled "Delete options during plugin deactivation?" and used automatically update or disabled the plugin during update. When this option is marked everey database entries inserted by wp-forecast are removed from the database.

== Screenshots ==
1. wp-forecast as a widget with two day forecast
2. wp-forecast admin dialog
3. wp-forecast in an iframe with a two day forecast


== Changelog ==

= v9.3 (2024-02-26) =
* fixed possibility of XSS from within WordPress with role contributor and up

= v9.2 (2023-11-26) =
* add two filters to change the weather data if wanted wp-forecast-openweathermap-data and wp-forecast-open-meteo-data
* fixed widget with location selection (some attributes were removed for security reasons)
* fixed label typo in admin dialog
* removed some index warnings during widget preview call

= v9.1 (2023-09-20) =
* added weather description from Open-Meteo and OpenWeatherMap instead of old Accuweather descriptions
* removed old accuweather translations
* completed OpenMeteo translations
* fixed some typos
* avoid warning if translation is missing
* fixed activation error in some constellations
* fixed saving setup on Multisite 
* Echo received message in connectiontest

= v9.0 (2023-09-18) =
* fixed apparent temperature bug in Open-Meteo data
* added support for Fahrenheit for Open-Meteo
* get more precise UV index for Open-Meteo
* updated some documentation
* fixed typos in english translation
* fixed unescaped quotes in search results (e.g. O'Hare Airport)
* sunset and sunrise are now formatted from blog time format

= v8.9 (2023-09-15) =
* fixed bug with use visitor ip for location and OpenMeteo
* fixed timezone shift on some sunset/sunrise times
* adopted connection test for Open-Meteo.
* completed german translation

= v8.8 (2023-09-13) =
* added support for OpenMeteo as an alternative to accuweather 
* add admin notice for the accuweather problem
* added OpenMeteo to asset banner

= v8.7 (2023-01-20) =
* updated widget translations

= v8.6 (2023-01-13) =
* added handling for empty timezone setting for OpenWeatherMap
* corrected typo in german translation
* removed DarkSky service because it is terminated
* added support for OpenWeatherMap new API v3 service
* removed hint for ending DarkSky service 

= v8.5 (2022-10-13) =
* fixed html incompatibility with some themes

= v8.4 (2022-09-19) =
* fixed pulldown issue when called as shortcode
* fixed undefined id in shortcodes.php 
* fixed some warnings about undefined indices

= v8.3 (2022-09-16) =
* fixed widget output concerning filtering

= v8.2 (2022-09-13) =
* fixed loading shortcode css correct
* fixed pulldown anchors in special constellations

= v8.1 (2022-09-12) =
* enhanced OpenUV error handling

= v8.0 (2022-09-08) =
* fixed check connection test with new modal dialog

= v7.9 (2022-09-07) =
* added local translation for multi language sites
* eliminated extract calls for security
* fixed minor WordPress standard issues

= v7.8 (2022-09-04) =
* fixed shortcode call 

= v7.7 (2022-09-03) =
* removed support for WordPress multisite admin settings 
* fixed various PHP Sniffer errors and warnings
* redesigned admin dialog for connection test (ajax)
* redesigned admin dialog for location search (ajax)
* fixed special problem with some even more special precipitation values from the weatherprovider

= v7.6 (2022-08-27) =
* completed sanitize vars in admin dialog and update nonces
* extended connection test for OpenWeatherMap
* added some missing translations

= v7.5 (2022-02-08) =
* fixed warning in admin dialog when QUERY_STRING is not set

= v7.4 (2021-10-19) =
* fixed current precipitation for 1h

= v7.3 (2021-10-16) =
* added support for precipitation with OpenWeathermap.org

= v7.2 (2021-06-14) =
* fixed some warning of undefined indexes
* added OpenWeathermap weather data provider since accuweather does not send correct forecast data anymore
* added admin hint about weatherservice changes
* fixed wind direction mapping

= v7.1 (2021-04-07) =
* fixed refresh of weather data on WP ajax call
* added support for svg icons
* fixed call of round function for PHP8

= v7.0 (2020-09-16) =
* fixed curly brackets syntax in funclib
* fixed date_i18n timezone issue by replacing it with php date function

= v6.9 (2020-06-01) =
* fixed link to accuweather weathersite for locations in the US

= v6.8 (2020-05-20) =
* fixed WPLANG warning

= v6.7 (2020-01-25) =
* fixed timezone with sunrise and sunset from DarkSky
* fixed shift of conditions for different timezones
* fixed decimals for DarkSky night temepratures

= v6.6 (2019-11-25) =
* fixed some conditions or to ||
* open lat and lon for DarkSky locations withour giving a location
* removed old WeatherBug error message

= v6.5 (2019-11-16) =
* added some css classes for DarkSky
* round some values from DarkSky (e.g. degrees)

= v6.4 (2019-11-10) =
* removed support for WeatherBug since the free API was discontinued
* added support for DarkSky weather API
* fixed some notices
* removed old fashioned debugging aid

= v6.3 (2019-10-20) =
* fixed some more undefined index Notices

= v6.2 (2019-09-12) =
* fixed language override parameter for shortcode
* fixed some undefined index Notices

= v6.1 (2019-01-19) =
* added option to disable load of default CSS rules
* removed some PHP notices

= v6.0 (2018-10-26) =
* added interface to data from OpenUV.io to show detailed UV data
* added interface to ipstack.com for getting the visitors location for displaying the weather
* reordered the admin dialog
* added new widget showing data from OpenUV.com
* added UV icons, thanks to Lars

= v5.9 (2018-08-12) =
* javascript for ajax like call is only loaded when needed now
- updated spanish translation, thanks to Alejandro

= v5.8 (2018-02-17) =
* removed deprecated function call for PHP7

= v5.7 (2017-09-23) =
* added PHP7 compatibility

= v5.6 (2017-08-27) =
* tested with WordPress 4.8.1
* fixed some descriptions
* removed older files from package
* fixed a network bug which occurs sometimes 
* reduces max numer of widgets to 8 avoiding heavy loading on some sites


= v5.5 (2015-08-25) =
* fixed deprecated constructor call for WP_Widget which leads to problems with some PHP Versions

= v5.4 (2015-02-14) =
* error handling if no icon code found in weather data
* updated french translation
* switched to new accuweather server

= v5.3 (2015-02-11) =
* error handling if no icon code found in weather data

= v5.2 (2015-02-08) =
* updated italian translation Thanks to Peter P.
* fixed some php warnings
* fixed dutch translation

= v5.1 (2014-10-24) =
* support for using shortcode in normal textwidget
* update croatian translation Thanks to Stjepan

= v5.0 (2014-07-29) =
* made the css a bit more responsive
* added croatian language (Thanks to Lovrenco)

= v4.9 (2014-05-04) =
* added contextual help and support email in admin dialog
* removed unused code (was commented since v3.x)
* removed old langauge files

= v4.8 (2014-01-30) =
* add updated hebrew translation
* optimized default icons with opt-gif and opt-png
* added css sprites feature for accuweather icons (experimental)
* fixed problem with dutch language and wind icons
* adopted default css to Twenty Fourteen
* added new accesspoint to access accuweather location search

= v4.7 (2013-10-21) =
* add updated czech translation. Thanks to eldenroot
* added update greek translation. Thanks to Nikos
* added a wind direction indicator

= v4.6 (2013-10-13) =
* fixed css output with wp_enqueue_script
* disabled service for google weather because google closed the api
* added uv-index to the accuweather data fields 

= v4.5 (2013-05-07) =
* added serbian language (Thanks to Amizda Idriz)
* fixed langauge codes in language selection dialog

= v4.4 (2013-02-03) =
* fixed some php warnings
* added persian translation (Thanks to Ali Zemani)
* fixed language code in iframe header

= v4.3 (2012-12-24) =
* added new hebrew translation by Udi :-)
* added new greek translation by Nikos :-)
* added updated serbian translation by Zoran :-)
* fixed search dialog for some special accuweather responses

= v4.2 (2012-10-23) =
* changed global variable name for xml parsing to prevent collision 
* added width and height attributes to img tags for better performance
* fixed HTML warning in admin dialog
* fixed problem with plugin_locale filter
* fixed problem with webservers which do not decompress automatically
* added indonesian language thanks to Uli

= v4.1 (2012-08-03) =
* fix admin dialog, it was not possible to save the WeatherBug Partner-ID
* only include javascript for multi widget in frontend
* changed translation loading from load_textdomain to load_plugin_textdomain
* fixed a problem with search dialog and individual wp-content folder

= v4.0 (2012-06-10) =
* support individual wp-content folder
* added hebrew translation from Udi Burg

= v3.9 (2012-05-26) =
* fixed a bug with new search dialog and multiwidgets
* fixed some php notices
* fixed weather provider switch in admin dialog
* check for apikey in search dialog for weatherbug

= v3.8 (2012-02-05) =
* fixed a special data constellation returned by accuweather for the pressure
* changed js loading to load thickbox only in admin dialog
* changed search dialog to an ajax like replacement
* redesigned the admin dialog a bit more convenient
* fixed an url problem in connection check

= v3.7 (2011-12-21) =
* fixed include of wordpress specific vars (wp-config.php, wp-load.php) to support secure setting
* various html5 validity fixes 
* calculate copyright year automatically

= v3.6 (2011-07-06) =
* changed accuweather copyright notice to 2011
* removed use of HttpExt class for transport, since it was removed from wordpress 3.2

= v3.5 (2011-04-21) =
* added updated finish translation from Jaakko
* selection widget now accepts language_override to set langauge different from default

= v3.4 (2011-03-13) =
* added updated dansk translation (thanks to Michael S.R. Petersen)
* fixed a problem with wp 3.1 in network mode, due to a different search path th
e wrong setup.php was included


= v3.3 (2010-11-02) =
* moved enqueue script to init hook where it should be to avoid conflicts with other plugins
* added bulgarian translation
* if you return to a wp-forecast site which uses selection widget, your selected location will be remembered and automatically displayed when you return (uses cookies)

= v3.2 (2010-10-01) =
* fixed duplicate id in selection widget

= v3.1 (2010-08-02) =
* fixed several bugs when using presettings in multiusermode
* added support for GoogleWeather API
* added serbian latin translation (thanks to Zoran)
* fixed year in copyright notice
* update accuweather servicelink to new server
* fixed translation for superadmin dialog on multisites
* added colored checkboxes to superadmin dialog
* removed examples folder from package

= v3.0 (2010-05-15) =
* timeoffset is now considered for current time too
* added support for wpmu (maintain settings on a per blog base, support for wpmu admin plugin)
* updated dansk translation
* fixed handling of country specific characters in accuweather data
* added "Less..." link at the top (pull down widget)
* updated swedish translation
* added css class for iframe tag for shortcodes

= v2.9 (2010-02-19) =
* fixed an javascript, jquery incompatibility with ie6, ie7, ie8 concerning the selection dialog widget

= v2.8 (2010-01-30) =
* prevent wp-forecast-nowp.css from being deleted during automatic plugin update
* added beaufort to set of windunits
* updated dutch translation, thanks to Wim :-)
* fixed warning during autoupdate with wordpress >Version 2.8.6
* rounded pressure to get rid of long values

= v2.7 (2010-01-22) =
* added unit label to timeoffset field in admindialog
* added selection dialog widget to let user choose which location to view
* extended pulldown widget to use more than one pulldown widget per page 
* prevent wp-forecast.css from being deleted during automatic plugin update

= v2.6 (2009-12-17) =
* fixed wrong urlencoded link to weather forecast at accuweather
* fixed undefined variable warning for $wp_forecast_pre_transport in wp-forecast.php
* fixed uncompress bug in wordpress 2.9 with workaround in fetch_url
* fixed invalid xhtml/javascript in admin dialog once more
* added open in new window feature for weather provider link
* added time offset to correct wrong calculated accuweather times

= v2.5 (2009-10-17) =
* fixed wrong html in widget dialog with wordpress v2.8, that leads to problems with placing widgets in internet explorer
* set default for widget call via v2.8.1 widgetdialog
* replaced "Copyright" with &copy; 
* added parameters width and height to shortcode
* added feature to show/hide forecast data with javascript
* added transport check to admin dialog

= v2.4 (2009-07-04) =
* changed readme to support new changelog feature at wordpress.org
* fixed faq section in readme, resized icon
* added default css file which is used when no user specific one is available
* fixed div container when date/time is disabled
* fixed widget title to be display correct

= v2.4beta (2009-06-18) =

* fixed translation of winddirection in api
* added translation for romanian
* fixed russian and italian translation
* modified show function to use data api
* avoid to store new cache when http fetch results in failure notice from acuweather
* add support for weatherbug
* reduced the number of database reads and writes and raise performance
* changed the xhtml using only div and not table or others
* added shortcode wpforecast
* catch error when weather bug does not deliver a shorttext
* since it leads to problems with some installations
* added menu icon
* moved settings to main menu
* switched the widget dialog to new oo-progamming for versions after 2.7.1
* added a preselection of the transfer method to be used with wp-forecast


= v2.3 (2009-01-30) =

* fixed some wrong italian translations
* added shorttext for api daily forecast
* fixed translation in api
* process failure notice from accuweather if it occures
* added lat and lon to data api
* switched to wordpress default function to get remote data from accuweather
* changed default value of cache refresh to 1800 seconds
* added translation to russian


= v2.2 (2008-12-29) =

* fixed some mistakes in finish translation (thanks to Jaska)
* added hungarian translation
* added parameter to avoid option deletion when deactivating the plugin
* added parameter for direct call to add a html-header
* added multi-checkbox-switch in admin-dialog (for convenience only)
* added translation for winddirections
* added data api for wordpress-pro's to design layouts individually


= v2.1 (2008-11-09) =

* added language support for spanish (thanks to Castmir) and 
* polish (thanks to Lukasz)
* fixed minor css bug 


= v2.0 (2008-11-02) =

* added finish translation (thanks to Jaska)
* fixed a problem with overloaded textdomains (translations)
* since wordpress does not a sanity check if a loaded domain is reloaded, we have to do it


= v1.9 (2008-10-04) =

* surpress fsockopen warning messages in case of connection problems and output the error as html comment


= v1.8 (2008-09-17) =

* added css class wpf-icon to make it easier formating the weather icons
* added autodetection for icon filetype
* gif, png and jpg are supported
* corrected some translations


= v1.7 (2008-07-20) =

* removed a bit of redundant html when widget title is empty
* fixed bug in output of current conditions
* added option to show a link to the accuweather forecast
* added dansk translation


= v1.6 (2008-07-11) =

* removed some hardcoded css
* it is now possible to call the widget directly outside from wp
* fixed a problem with wp >2.5 and the widget dialog
* removed some redundant html
* when showing no current weather information 
* placed forecast header into own table with own css class
* added timeout parameter for the accuweather connections
* rounded humidity to integer values
* fixed some typos in swedish translation and added norwegian selection (thanks to RAM_OS) 


= v1.5 (2008-05-12) =

* fixed two dutch phrases in dutch translation
* added norwegian translation (thanks to Eilif)


= v1.4 (2008-01-26) =

* fix loading the wright textdomain when called from outside wordpress
* added a bit debug code
* work around for a bug in k2rc3 theme
* added italian translation
* added english lanuage file
* a bit of code cleanup
* extend function wp-forecast to select language per widget
* added functions to display a set and a range of widgets at once


= v1.3 (2007-12-26) = 

* added french translation
* added german icon 11 (fog, 11_de.gif)
* extended css classes to support horizontal view via css
* removed repeating section title 


= v1.2 (2007-11-05) =

* extend error handling for serverloss
* added iso8859-1 coded german translation
* fixed bug with german winddirections
* added a widget title
* removed standard location label (this can be handled via alternate location)


= v1.1 (2007-10-01) =

* fixed: setting the current time could not be disabled
* fixed: on some servers the current date was converted to 0
* switched translations to gettext as recommended by wp codex


= v1.0 (2007-09-09) =

* fixed accuweather call for us locations
* now works with wordpress mu


= v1.0b4 (2007-09-01) = 

* fixed humidity / pressure checkbox
* removed hard coded formatting, added css class
* added support to show current time


= v1.0b3 (2007-07-29) =

* fixed output of before/after widget stuff for empty forecast
* fixed different parameters for calling wp_forecast as widget and from sidebar.php
* added swedish translation (thx to Håkan Carlström)


= v1.0b2 (2007-07-25) =

* work around for bug 4275 in wordpress 2.2
* removed widget id from output


= v1.0b (2007-07-17) =

* added support for up to 20 widgets with different locations and settings 
* added portugese language support
* weather data is now cached in the database 
* no cookies needed anymore
* default value of missing translations is now english
* removed configuration dialog from widgets page to avoid misunderstanding about setup
* fixed some minor errors


= v0.9.1 (2007-07-01) =

* added new field windgusts
* fixed some incompatibility with complex themes


= v0.9 (2007-06-23) =

* added copyright notice
* added date for current conditions
* added alternative location name


= v0.8 (2007-06-18) =

* added dutch language support
* show time in wordpress format (option: time_format)

= v0.7 (2007-06-11) =
* Fixed an incompatibility with wpSEO (used same global variable language which should never happen)


= v0.6 (2007-06-07) =

* Fixed a lot of incorrect XHTML 
* added translation for winddirection
* changed display of low- and hightemperature in forecast
* no decimals for windspeed
* fixed two phrases in translation
* added hint for dealing with german umlaute and search location dialog
* added a bit error handling to surpress long error messages when receiving no or invalid xml from accuweather


= v0.5 (2007-06-03) =

* added support for wp widgets
* Fixed some incorrect XHTML code
* added selection of the fields to show
* added windspeed unit support (hope you like it Barbara :-))
* added german language support for admin page


= v0.4 (2007-05-31) =

* never published, only code cleaning done


= v0.3 (2007-05-18) =

* Integrate forecast


= v0.2 (2007-05-17) =

* Fixed some incorrect XHTML code
* Fixed path settings for icons and css
* Tested with various browsers


= v0.1 (2007-01-15) =

* Initial beta release
