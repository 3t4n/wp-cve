=== MaxiCharts Gravity Forms Source add-on ===
Contributors: munger41,maxicharts
Tags: gravity, forms, chart, chartsjs, graph, graphs, visualisation, survey, MaxiCharts, maxicharts, entry, stats, visualization, HTML5, canvas, pie chart, line chart, charts, chart js, plugin, widget, shortcode
Requires at least: 4.0
Tested up to: 5.8
Stable tag: 1.0

Extends MaxiCharts to chart Gravity Forms data.

== Description ==

Extends MaxiCharts to chart Gravity Forms data with a simple shortcode. Requires installation and activation of free [MaxiCharts](https://wordpress.org/plugins/maxicharts/ "MaxiCharts") plugin. And of course Gravity Forms.

[youtube https://youtu.be/ZcKpVkDNYIM]

[>> Demonstration site <<](https://maxicharts.com/random-demos/ "Demonstration")

All code has been moved to GitHub now : [MaxiCharts on Github](https://github.com/Maxicharts)

### Usage ###

Use the post visual editor brand new button to add the as many shortcodes as you want in a few clics !

the shortcode is

`[gfchartsreports gf_form_id="form_id" include="fieldNb1,fieldNb2,fieldNb3,.." exclude="fieldNb4,fieldNb5,..." color_set="set" type="graphType" width="width(px|%)" float="true|false" chart_js_options="options"]`

where all parameters are optional:

* *gf_form_id* : expects the **form ID** in Gravity Forms (defaults to first form : id=1)
* *type* : is the type of graph, at the moment only **line,pie,doughnut,bar,horizontalBar** available (defaults to pie)
* *include* : expects the **field ids** to include (example : `include="1,3,4"`)
* *exclude* : expects the **field ids** to exlude (example : `exclude="2,4,6"`)
* *colors* : to use your custom colors : a list of [coma separated hex colors](https://maxicharts.com/custom-colors/)
* *color_set* : expects the name of the color set : **blue,green,red,orange,purple** (defaults to a standard color set including different colors)
* *color_rand* : expects **true or false** and randomizes colors in color set if true (defaults to false)
* ...

see [all parameters on demonstration site](https://maxicharts.com/all-shortcode-parameters/ "All parameters") 

### Examples ###
Display all fields of form #1 as pie charts with custom [chartjs options](http://www.chartjs.org/docs/#chart-configuration-creating-a-chart-with-options) for titles:

`[gfchartsreports chart_js_options="title: {display: true, text: 'My Funky Chart Title', fontSize:28,fontFamily:'Arial',fontColor:'#00B88A',fontStyle:'bold',padding:20}"]`

Displays fields 4, 7 and 18 of gravity form #3 with bar charts. Default colors used.

`[gfchartsreports gf_form_id="3" include="4,7,18" type="bar"]`

Displays field 2 of gravity form #2 with default pie chart. Use red color set, with not randomization.

`[gfchartsreports gf_form_id="2" include="2" color_set="red"]`

Displays all but fields 4,7,18 of gravity form #8 with horizontalBar type charts. Use blue color set, randomized.

`[gfchartsreports gf_form_id="8" exclude="4,7,18" color_set="blue" color_rand="true" type="horizontalBar"]`

Test it live here : [>> Demonstration site <<](https://maxicharts.com/random-demos/ "Demonstration")

== Installation ==

### Easy ###
1. Search via plugins > add new.
2. Find the plugin listed and click activate.
3. Use the Shortcode

== Screenshots ==

== Changelog ==

* 1.7.10 - no entries custom message bug fix

* 1.7.9 - std dev added

* 1.7.8 - exclude bugfix

* 1.7.7 - better debug infos

* 1.7.6 - filter parameter fix

* 1.7.5 - new singleton structure

* 1.7.4 - fix on datasets_field

* 1.7.3 - extract() removed

* 1.7.1 - new parameter ignore_empty_values in order to skip empty values in answers before charting

* 1.6.4 - bug on group_fields

* 1.6.3 - more error msg feedback

* 1.6.2 - more on grouping and solo entry display

* 1.6.1 - Bug fix on multi fields surveys

* 1.6.0 - Radar charts enhancements

* 1.5.3 - multiselect fields introduced

* 1.5.2 - name fields managed

* 1.5.1 - rank survey type managed

* 1.5.0 - product options fields management

* 1.4.1 - surveys bug fixed

* 1.4 - betsy bug #3

* 1.3.9 - betsy bug #2

* 1.3.8 - betsy bug

* 1.3.7 - check JSON decoding of custom criteria

* 1.3.6 - order of answers now same as order of field items

* 1.3.5 - round precision set to 0 (will be parameter in next versions)

* 1.3.4 - multiple custom criteria (query builder and user custom) managed

* 1.3.3 - adjusmtent for UK clients

* 1.3.2 - only int field keys

* 1.3.1 - new parameter **mode**

* 1.3 - query builder compatibility

* 1.2.7 - survey multirows should be fixed at least for bars

* 1.2.6 - bug fixed on survey fields fetching mechanism

* 1.2.5 - changes for query builder add on

* 1.2.4 - cleaned main file

* 1.2.3 - can sum list fields as well

* 1.2.2 - skipping HTML fields

* 1.2.1 - other logger categories

* 1.2 - log fix

* 1.1 - monolog replaced log4php

* 1.0 - module extraction