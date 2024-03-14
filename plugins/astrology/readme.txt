=== Astrology ===
Contributors: Prokerala
Tags: astrology, prokerala
Requires at least: 5.6
Tested up to: 6.3
Stable tag: 1.3.1
Requires PHP: 7.2.0+
License: GPLV2+

Turn your Wordpress blog into a full astrology site, powered by Prokerala's astrology API.

== Description ==

[youtube https://www.youtube.com/watch?v=QAEMQXtTdIc]

Turn your Wordpress blog into a full astrology site, powered by [__Prokerala__](https://www.prokerala.com)'s astrology API.

= Features =
* Free
* Easy to setup

== Available Services ==

* Daily Horoscope Predictions
* Numerology Calculators
* Daily Panchang Calculators
 * Panchang
 * Auspicious Period
 * Inauspicious Period
 * Choghadiya
* Horoscope Calculators
 * Birth Details
 * Charts
 * Kundli
 * Mangal Dosha
 * Kaalsarp Dosha
 * Papasamyam
 * Planet Position
 * Sade-Sati
* Marriage Matching Calculators
 * Kundli Matching
 * Nakshatra Porutham
 * Thirumana Porutham
 * Porutham
 * Papasamyam Check
* Western Charts
 * Natal Chart
 * Transit Chart
 * Progression Chart
 * Solar Chart 
 * Synastry Chart
 * Composite Chart

== Installation ==

1. Go to Plugins -> Add New
2. Search for "Prokerala Astrology"
3. Click "Install"
4. Click "Activate"
5. Enter your Prokerala API credentials in the plugin's settings page.
6. That's it. The `[astrology]` shortcode and `Astrology Reports` block will be available in your wordpress editor.

== Usage ==

- Install and activate the plugin on your WordPress dashboard.
- Enter you Prokerala API client id and client secret in the plugin settings page.
- Create a blog post or a page and add the report form.

The reports form can be added to a page using the block editor or using shortcode.

= Blocks =

The plugin adds a new block name **Astrology Reports** to the block editor.

= Shortcode =

If you are unable to use the block editor, then you can also activate the plugin using the short code `astrology`.

<code>[astrology report="REPORT_NAME"]</code>

Where `REPORT_NAME` must be one of following

- `AuspiciousPeriod`
- `BirthDetails`
- `Chart`
- `Choghadiya`
- `DailyPrediction`
- `InauspiciousPeriod`
- `KaalSarpDosha`
- `Kundli`
- `KundliMatching`
- `MangalDosha`
- `NakshatraPorutham`
- `Numerology`
- `Panchang`
- `Papasamyam`
- `PapasamyamCheck`
- `PlanetPosition`
- `Porutham`
- `SadeSati`
- `ThirumanaPorutham`
- `WesternChart`
- `CompatibilityChart`

*Shortcode attributes*

- **`result_type`**

   In calculators that support `basic` and `advanced` results, the result type can be forced using the `result_type` attribute. Settings this attribute will remove the corresponding input fields from the form.

        [astrology report="Kundli" result_type="advanced"]

   **Available for**
   - Kundli
   - Panchang

#### Report Specific Options

#### Chart

- **`chart_style`**

  You can set the `chart_style` attribute to one of `north-indian`, `south-indian` or `east-indian` to force the result chart style. Setting this attribute will remove the corresponding input fields from the form.

        [astrology report="Kundli" chart_style="south-indian"]

##### Kundli

- **`display_charts`**

  The `display_charts` option allows showing _Rasi_ / _Navamsa_ chart in Kundli result. This will cost two additional API calls. The value of the attribute must be `lagna,navamsa`.

        [astrology report="Kundli" display_charts="lagna,rasi"]

##### DailyPrediction

- **`sign`**

   By default, the DailyPrediction report will display predictions for all zodiac signs. You can use limit the result to a single zodiac sign using the `sign` attribute. This may be used to create separate page for each zodiac sign or to insert advertisement between the result.

- **`date`**

   By default, the `DailyPrediction` report will display predictions for the current date. If required, the default behaviour can be changed by setting the `date` attribute to `yesterday`, `today` or `tomorrow`.

      [astrology report="DailyPrediction" date="tomorrow"]

##### Panchang
- **`coordinate`**

  By default, the `Panchang` report will display panchang for Ujjain, Maharashtra. If required, the default behaviour can be changed by setting attribute `coordinate`.

      [astrology report="Panchang" date="tomorrow" coordinate="23.179300,75.784912"]

##### WesternChart

- **`report_type`**

  By default, the `WesternChart` report displays the natal chart. The default behaviour can be modified by setting the `report_type` attribute. Allowed values are `natal-chart`, `transit-chart`, `progression-chart`, and `solar-return-chart`.

      [astrology report="WesternChart" report_type="natal-chart" ]

- **`display_options`**

  By default, the `WesternChart` report displays the chart. The default behaviour can be modified by setting the `display_options` attribute. Allowed values are `chart`, `aspect-chart`, `planet-positions`,  `planet-aspects` and `all`. You can specify multiple types by separating them with comma, or use the special `all` value to display everything.

      [astrology report="WesternChart" report_type="natal-chart" display_options="chart,aspect-chart,planet-positions,planet-aspects"]

      [astrology report="WesternChart" report_type="natal-chart" display_options="all"]

##### CompatibilityChart

- **`report_type`**

  By default, the `CompatibilityChart` report displays the synastry chart. The default behaviour can be modified by setting the `report_type` attribute. Allowed values are `synastry-chart`, and `composite-chart`.

      [astrology report="CompatibilityChart" report_type="synastry-chart" display_options="all"]

- **`display_options`**

  By default, the `CompatibilityChart` report displays the chart. The default behaviour can be modified by setting the `display_options` attribute. Allowed values are `chart`, `aspect-chart`, `planet-aspects`, and `all`. You can specify multiple types by separating them with comma, or use the special `all` value to display everything.

      [astrology report="CompatibilityChart" report_type="synastry-chart" display_options="chart,aspect-chart,planet-aspects"]

	  [astrology report="CompatibilityChart" report_type="composite-chart" display_options="chart,aspect-chart,planet-positions,planet-aspects"]

      [astrology report="CompatibilityChart" report_type="synastry-chart" display_options="all"]
      
##### Localization

You can use the following attributes to localize the form / result.  View available languages for each report from  https://api.prokerala.com/docs#tag/Daily-Panchang

- **`form_language`**

  You can set the `form_language` attribute to one of `en`, `hi`, `ml`, `ta`, or `te` to set localization for forms.

        [astrology report="Kundli" form_language="en"]

- **`report_language`**

  You can set the `report_language` attribute to one of `en`, `hi`, `ml`, `ta`, or `te` to add language select fields for forms.

        [astrology report="Kundli" report_language="en,hi,ml"]

== Frequently Asked Questions ==

= Do I need an account to use this plugin? =

Yes, you need to signup for an account at https://api.prokerala.com to use this plugin.

= Do I need a paid subscription? =

No, you can start using the plugin with our free subscription.

== Changelog ==

= 1.3.1 =
* Fix stable tag

= 1.3.0 =
* Added support for western charts

= 1.2.1 =
* Fix Syntax error on PHP 7.x

= 1.2.0 =
* Added localization support
* Added support for Daily Panchang
 
= 1.1.4 =
* Fix form date input changing to current date on result page
* Fix layout issue with short prediction content

= 1.1.3 =
* Fix error message when saving shortcode without ayanamsa attribute
* Fix interchanged default value for chart_type and chart_style

= 1.1.2 =
* Fix PHP 7.x compatibility

= 1.1.1 =
* Fix: Drafts missing after enabling plugin

= 1.1.0 =
* Added support for Daily Horoscope Predictions
* Added support for Numerology
* Added additional astrology services

= 1.0.6 =
* Fix datetimes reverting to UTC

= 1.0.5 =
* Location parameters where not being passed correctly
* Minor inconsistencies and mistakes in template files
* Fix PHP notices

= 1.0.4 =
* Fix php notices

= 1.0.3 =
* Added new shortcode attribute `form_action` to specify a different page url for result.

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
* Initial release

== Screenshots ==

1. Astrology Reports Block
2. Plugin block editor demo
