=== Stock Market News ===
Contributors: stockdio  
Tags: stock market news, stock news, financial news, stocks, market, news, ticker, quote, finance, quotes, stock, financial, index, indices, list, currencies, commodities, forex
License: See www.stockdio.com/wordpress for details
Requires at least: 3.1
Tested up to: 6.4.3
Stable tag: 1.9.12
WordPress plugin and widget for displaying a list of stock news for a given public company, available in several languages.


== Description ==

Easily display a list of company and stock news, for any given stock exchange and symbol. Over 65 different stock exchanges and a large number of market indices, currencies and commodities are supported.

If you're using the standard Gutenberg editor, the easiest way to include this plugin on your page is using the Company & Stock News block, which is included in the Stockdio Financial Visualizations category.

If you're using a different editor o prefer to use the shortcode, below is a sample to help you start. Please be aware that most of the parameters listed below are optional, and are also available through the plugin's settings page. Any parameter you include in the shortcode will overwrite the parameter used in the settings page.

`[stock-market-news symbol="AAPL" stockExchange="NYSENasdaq" width="100%"]`

If you're looking for economic and general markets news, please use the Economic & Market News plugin instead, available at:

* [Economic & Market News](https://wordpress.org/plugins/economic-market-news/)

This plugin is part of the Stockdio Financial Widgets, which also includes the following plugins:

* [Stockdio Historical Chart](https://wordpress.org/plugins/stockdio-historical-chart/)
* [Stock Market Overview](https://wordpress.org/plugins/stock-market-overview/)
* [Stock Quotes List](https://wordpress.org/plugins/stock-quotes-list/)
* [Stock Market Ticker](https://wordpress.org/plugins/stock-market-ticker/)
* [Economic & Market News](https://wordpress.org/plugins/economic-market-news/)

The following parameters are supported in the shortcode and also available through the plugin's settings page:

**stockExchange**: The exchange market the symbol belongs to (optional). If not specified, NYSE/NASDAQ will be used by default. For a list of available exchanges please visit www.stockdio.com/exchanges.

**symbol**: The company's stock symbol (ex. **AAPL**) or market index ticker (ex. **^SPX**). For a list of available market indices please visit www.stockdio.com/indices.

**width**: Width of the list in either px or % (default: 100%).

**height**: Height of the list in pixels (default: none). If not specified, the list height will be calculated automatically.

**title**: Allows to specify a title for the list, e.g. News (optional).

**culture**: Allows to specify a combination of language and country settings, used to display texts and to format numbers and dates, e.g. Spanish-Spain (optional). For a list of available culture combinations please visit http://www.stockdio.com/cultures.

**includeImage**: Allows to include/exclude the news image, if available. Use includeImage=false to hide the image (optional).

**imageWidth**: Specify the image width in pixels (if available). The image may be partially clipped and centered, depending on the original image dimensions and specified height, to maintain the image's aspect ratio (optional).

**imageHeight**: Specify the image height in pixels (if available). The image may be partially clipped and centered, depending on the original image dimensions and specified width, to maintain the image's aspect ratio (optional).

**includeDescription**: Allows to include/exclude the news description, if available. Use includeImage=false to hide the description (optional).

**maxDescriptionSize**: Allows to set the maximum number of characters to display in the description, if available. By default, an estimate of the number of characters to display is calculated based on the image height and display width, but this may not be totally accurate, and a manual setting might be required (optional).

**includeRelated**: Allows to include general market news in the list, not including the symbol's company, if available.

**maxItems**: Allows to set the maximum number of news items to be displayed (optional, default: 10).

**motif**: Design used to display the visualization with specific aesthetics, including borders and styles, among other elements (optional). For a list of available motifs please visit www.stockdio.com/motifs.

**palette**: Includes a set of consistent colors used for the visualization (optional). For a list of available palettes please visit www.stockdio.com/palettes.

**font**: Allows to specify the font that will be used to render the chart. Multiple fonts may be specified separated by comma, e.g. Lato,Helvetica,Arial (optional).

**filterSources**: Allows to filter news from a list of sources, separated by colon (;). For example, setting the value to Seeking Alpha;Yahoo Finance will only display news that come from any of these sources.

**ignoreSources**: Allows to ignore news coming from a list of sources, separated by colon (;). For example, setting the value to Seeking Alpha;Yahoo Finance will ignore news that come from any of these sources.

**ignoreItems**: Allows to ignore news items that start or contain the text specified in a list, separated by colon (;). If the text in the list starts with *, the news item will be ignored if it contains the text anywhere inside its title; otherwise, the news item will be ignored if it starts with the specified text. For example, setting the value to canada;*share price, will ignore any news whose title starts with the word Canada or contains the phrase share price. It is not case sensitive.

**loadDataWhenVisible**: Allows to fetch the data and display the visualization only when it becomes visible on the page, in order to avoid using calls (requests) when they are not needed. This is particularly useful when the visualization is not visible on the page by default, but it becomes visible as result of a user interaction (e.g. clicking on an element, etc.). It is also useful when using the same visualization multiple times on a page for different devices (e.g. using one instance of the plugin for mobile and another one for desktop). We recommend not using this by default but only on scenarios as those described above, as it may provide the end user with a small delay to display the visualization (optional).

== Installation ==

1. Upload the `StockdioPlugin` folder to your `/wp-content/plugins/` directory.

2. Activate the "Stock Market News" plugin in your WordPress administration interface.

3. If you want to change the preset defaults, go to the Stock Market News settings page.

4. If you're using the standard Gutenberg editor, add a Company & Stock News block from the Stockdio Financial Visualizations category and configure the news using the settings sidebar.

5. If you prefer to use the shortcode, insert the `[stock-market-news]` shortcode into your post content, customizing it with the appropriate parameters. You also have the option to use the Stock Market News widget included when you install the plugin.

6. For ease of use, a Stockdio icon is available in the toolbar of the HTML editor for certain versions of WordPress (see screenshots for details).

== Frequently Asked Questions ==

= How do I integrate the Stockdio Market News in my page? =

There are three options to integrate it: a. Using the Company & Stock News block, b. Using the short code, or c. Through the use of the widget in your sidebars.

= How do I know if the Stock Exchange I need is supported by Stockdio? =

Stockdio supports over 65 different world exchanges. For a list of all exchanges currently supported, please visit [www.stockdio.com/exchanges](http://www.stockdio.com/exchanges). If the stock exchange you're looking for is not in the list, please contact us to info@stockdio.com. Once you have found in the list the stock exchange you need, you must pass the corresponding Exchange Symbol using the stockExchange parameter.

= How do I specify the symbol to display? =

You can specify any symbol you want, from the selected exchange. If the symbols you want to displa does not show up, you can go to [http://finance.stockdio.com](http://finance.stockdio.com) to verify if the symbol is currently available in Stockdio. If the symbol you require is missing, please contact us at info@stockdio.com.

= Can I get news for market index? =

Yes, you can specify an index in the symbol parameter, using the ^ prefix. For example, use ^SPX for S&P 500 or ^DJI for the Dow Jones. For a complete list of indices currently supported, please visit [www.stockdio.com/indices](http://www.stockdio.com/indices)

= Can I get news for a specific commodity? =

Yes. You must use **COMMODITIES** as the stockExchange and then specify the commodity in the symbol parameter. For example, use GC for Gold. For a complete list of commodities currently supported by Stockdio, please visit [www.stockdio.com/commodities](http://www.stockdio.com/commodities)

= Can I get news for a particular currency? =

Yes. You must use **FOREX** as the stockExchange and then specify the currency in the symbol parameter. For example, use EUR for Euro. For a complete list of currencies currently supported by Stockdio, please visit [www.stockdio.com/currencies](http://www.stockdio.com/currencies)

= Can I display general economic and stock market news isntead of a specific company or market index? =

Yes, but you will need a different plugin for that purpose. Please use the Economic & Market News plugin available at [https://wordpress.org/plugins/economic-market-news/](https://wordpress.org/plugins/economic-market-news/).

= Can I get news in my language? =

Yes, Stockdio supports a number of cultures, used to properly display texts and dates, e.g. Spanish-Spain. For a complete list of cultures currently supported by Stockdio, please visit [www.stockdio.com/cultures](http://www.stockdio.com/cultures).

= Can I specify my own colors for the news? =

Yes, this plugin is provided with a number of predefined color palettes, for ease of use. For a complete list of color palettes currently supported by Stockdio, please visit [www.stockdio.com/palettes](http://www.stockdio.com/palettes). However, if you need specific color customization, you can use the Company & Stock News block, or you can use the Stockdio iframe available at [http://services.stockdio.com](http://services.stockdio.com), which supports more options.

= The company logo for the symbol is not correct or updated, can this be fixed? =

Sure! Simply contact us to info@stockdio.com with the correct or updated logo and we will update it, once it has been verified.

= Can I place more than one news plugin on the same page? =

Yes. By default, all news will use the values specified in the plugin settings page. However, any of these values can be overridden using the appropriate shortcode parameter. Each shortcode can be customized entirely independent.

= How can I contact Stockdio if something is not working as expected? =

Simply send an email to info@stockdio.com with your question and we will reply as soon as possible.

== Screenshots ==

1. Example of stock market news with images and description, in English.

2. Example of stock market news without images, in French.

3. Example of stock market news without images or description, in Spanish.

4. Example of stock market news with small images, in Italian.

5. Example of stock market news with large images, in German.

6. Example of stock market news with images and description, in Portuguese.

7. Stockdio Historical Chart is also available as a complement to the Stock Market News.

8. Stockdio Stock Quotes List is also available as a complement to the Stockdio Market News.

9. Stockdio Stock Market Overview is also available as a complement to the Stockdio Market News.

10. Stockdio Stock Market Ticker List is also available as a complement to the Stock Market News.

11. Settings page.

12. Stockdio toolbar integration with easy to use dialog.

13. Stock Market News widget dialog.

14. Company & Stock News block as part of the Stockdio Financial Visualizations category.

15. Company & Stock News block sidebar settings.

== Changelog ==
= 1.9.12 =
Release date: March 07, 2024

* Fixes vulnerability issue.

= 1.9.11 =
Release date: March 05, 2024

* Fixes vulnerability issue.

= 1.9.10 =
Release date: November 01, 2023

* Fixes vulnerability issue.

= 1.9.9 =
Release date: March 30, 2023

* Minor bug fixes.

= 1.9.7 =
Release date: May 24, 2022

* Minor bug fixes.

= 1.9.6 =
Release date: June 29, 2021

* Minor bug fixes.

= 1.9.5 =
Release date: May 03, 2021

* Minor bug fixes.

= 1.9.4 =
Release date: January 27, 2021

* Minor bug fixes to properly support compatibility with legacy versions of WordPress.

= 1.9.3 =
Release date: January 24, 2021

* Minor block bug fixes and enhancements.

= 1.9.2 =
Release date: January 19, 2021

* Minor block bug fixes and enhancements.

= 1.9.1 =
Release date: January 14, 2021

* Addition of wizard to easily support selection of symbols.
* Minor bug fixes and security enhancements.

= 1.8.2 =
Release date: June 19, 2020

Bug Fixes:

* Minor block bug fixes and enhancements.

= 1.8.1 =
Release date: June 18, 2020

* Addition of the Company & Stock News block for easy configuration in the standard Gutenberg editor.

= 1.7.13 =
Release date: May 7, 2020

* Change to support referrals on certain browsers.

= 1.7.12 =
Release date: December 09, 2019

* Fixes issue with Load Data When Visible setting.

= 1.7.11 =
Release date: August 16, 2019

* Support for NEO Exchange (NEO).

= 1.7.10 =
Release date: January 31, 2019

* Fixes issue with deprecated functions.

= 1.7.9 =
Release date: October 24, 2018

* Fixes issue with ticker auto calculated height.

= 1.7.8 =
Release date: June 05, 2018
 
New features:
 
* Support for ability load data only when the visualization becomes visible. Please refer to the documentation for details.

= 1.7.5 =
Release date: May 14, 2018

* Fixes issue with deprecated functions.

= 1.7.3 =
Release date: November 30, 2017

* Support for WordPress 4.9

= 1.7.2 =
Release date: August 3, 2017

Bug Fixes:

* Fixes an issue that might cause some visualizations to appear cut off.

= 1.7.1 =
Release date: August 2, 2017

* Enhancements on mobile display.

= 1.7 =
Release date: June 30, 2017

New features:

* Support for ability to only display news coming from specific sources.
* Support for ability to ignore news coming from specific sources.
* Support for ability to ignore items that start with or contain certain phrases.

= 1.6.1 =
Release date: June 21, 2017

Bug Fixes:

* Some properties in Settings page and shortcode were not being honored during plugin rendering.

= 1.6 =
Release date: June 12, 2017

* Support for BATS ETF (included in the NYSENasdaq stockExchange category).

= 1.5 =
Release date: May 25, 2017

* Support for Canadian Securities Exchange (CSE).

= 1.4 =
Release date: May 16, 2017

* Stock Market News Widget is now available along with the plugin, for even easier integration.

= 1.3.1 =
Release date: April 4, 2017

Fixes an issue with an undefined function in older wordpress versions.

= 1.3 =
Release date: March 22, 2017

Compatibility with new Stock Market Overview plugin.

= 1.2 =
Release date: March 1, 2017

New feature: ability to include or exclude news from related market sources.

= 1.1 =
Release date: February 24, 2017

Compatibility with new Stock Market Ticker plugin.

= 1.0 =
* Initial version.

== Upgrade Notice ==
