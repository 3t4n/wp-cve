=== SheetDB - get your Google Spreadsheet data ===
Plugin name: SheetDB
Contributors: sheetdb
Tags: google spreadsheet, google sheets, sheetdb, sheet-db, google api, api
Requires at least: 4.0
Tested up to: 6.4.3
Requires PHP: 5.4
Stable tag: 1.2.3
License: GPLv2 or later

The SheetDB wordpress plugin allows you to easily add content from Google Spreadsheet to your wordpress site.

== Description ==

The SheetDB wordpress plugin allows you to easily add content from Google Spreadsheet to your wordpress site. To add content, simply add shortcode that looks like this - `[sheetdb url=&#34;https://sheetdb.io/api/v1/YOUR_API_ID&#34;][/sheetdb]`. Content from within will be rendered as many times as there are rows in the spreadsheet (excluding the first row, which are column names - definition). Each time a new row is rendered, you can refer to any value using the braces notation: `{{column_name}}`

Example spreadsheet: [Example spreadsheet document](https://docs.google.com/spreadsheets/u/3/d/1mrsgBk4IAdSs8Ask5H1z3bWYDlPTKplDIU_FzyktrGk/edit)

Example code:
[sheetdb url=&#34;https://sheetdb.io/api/v1/58f61be4dda40&#34; element=&#34;ul&#34; limit=3 search=&#34;id=*&#34; sort-by=&#34;id&#34; sort-order=&#34;desc&#34; lazy-loading=&#34;true&#34;]
{{id}} - {{name}}
[/sheetdb]

You can use following optional attributes:
 * limit - The number of rows that should be returned
 * offset - Row from which it should start (how many rows to skip)
 * sheet - If you want to use a different sheet than the first one (default), enter the name of the tab you want to use here.
 * search - You can search for specific data in your sheet. If you want to use more than one condition join them using & symbol. Example: search=&#34;name=Tom&age=15&#34;
* sort-by - The column you want to sort by
* sort-order - sort in `asc` or `desc` order
* lazy-loading - If you set this attribute to true, the api call will be executed only when the user reaches the point of the table. If your table is lower on the page this can help reduce request consumption.

Additional information:
This plugin use SheetDB.io to fetch data from Google Spreadsheet. You must have an account at SheetDB - account is free for 500 requests per month. More information at [SheetDB.io](https://sheetdb.io) or at [privacy policy](https://sheetdb.io/privacy-policy)

== Installation ==

Add SheetDB plugin to your site, activate it, then all you need to do is use the [sheetdb] shortcode wherever you want.

== Re-use ==

If you want to re-use your data, you can use the `save` attribute in your [sheetdb] element. To re-use your data use [sheetdb-slot]. You can use the same data inside as in the parent. To match them the value of save and slot must be the same.

Example:

[sheetdb url=&#34;https://sheetdb.io/api/v1/58f61be4dda40&#34; save=&#34;slot-name&#34;]<p>{{id}} - {{name}}</p>[/sheetdb]

[sheetdb-slot slot=&#34;slot-name&#34;]<p>{{name}}</p>[/sheetdb-slot]

That way, you only use 1 request instead of 2. Slots have access to the same data as the parent. You can't change things like limit or search.

== Screenshots ==

1. Use SheetDB shortcode
2. Content on the site will be download from spreadsheet with every pageload.
3. The Google Spreadsheet content
