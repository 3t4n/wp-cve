=== FC's Mortgage Calculator ===
Contributors: financialcalculators
Donate link: nada
Tags: calculator, loan, mortgage, mortgage calculator, mortgage loan calculator, amortization, amortization schedule, plugin, sidebar, widget
Requires at least: 5.0.0
Tested up to: 6.2
Stable tag: 1.5.4
License: GNU General Public License
License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html

Calculates loan payment and optionally down payment, affordable price, taxes, points and PMI. Includes schedule. Supports international conventions.

== Description ==

[FC's Mortgage Calculator Plugin](https://AccurateCalculators.com/calculator-plugins/mortgage-plugin) can calculate a number of unknown values including affordable home price, monthly loan payment or percentage of cost available as down payment. It can create a detailed amortization schedule with date based payments and charts. The calculator optionally supports points, private mortgage insurance (PMI), property taxes and hazard insurance. Your site's visitors can select their own currency and date convention used in the loan schedule. This is ideal if your site attracts visitors from around the globe. Select from one of four predefined sizes or you can modify CSS file to customize size and colors. Supports touch devices and a responsive websites. This plugin is based on and uses the code from my [Mortgage Calculator](https://AccurateCalculators.com/mortgage-calculator).

*Rebranding with your site's brand name is supported and encouraged.*

The plugin may be used (a) in a post or page's content area via a *shortcode*; or (b) used in a *widget area*; or (c) called from any template file. See __usage__ under installation for details.


**Installation**

Either (a) upload the *fc-mortgage-calculator* folder with all its files to the */wp-content/plugins/* folder or (b) unzip the plugin's zip file in the */wp-content/plugins/* folder.

Activate the plugin through the *Plugins -> Installed Plugins* menu in WordPress

*Upgrading*

If you translated the plugin from English to another language, please backup your work prior to upgrading the plugin.

*Usage*

There are 3 mutually exclusive ways you can deploy the calculator to an individual page (though you can use all three methods on different pages within a site):

1. If your theme supports widgets, add the plugin to a widget area through the Appearance -> Widgets menu in WordPress. WordPress v5.8 introduced the block editor to the Admin's widget management screen. To install this plugin as a widget in WordPress v5.8 or later, install first the widget shortcode and then copy this plugin's shortcode into it.
1. Add the following code *&lt;?php show_fcmortgage_plugin(); ?&gt;* to your template where you want the calculator to appear. See below for options.
1. Add the shortcode *[fcmortgageplugin]* in the content area of your page or post and configure shortcode parameters.

__Shortcode parameters__

	* sc_size= tiny | small | medium | large
	* sc_custom_style= No | Yes
	* sc_add_link= No | Yes
	* sc_brand_name= 
	* sc_hide_resize= No | Yes
	* sc_price=
	* sc_pct_dwn=
	* sc_loan_amt=
	* sc_n_months=
	* sc_rate=
	* sc_points=
	* sc_taxes=
	* sc_insurance=
	* sc_pmi=
	* sc_currency=
	* sc_date_mask=

Examples (1st includes all options):

`[fcmortgageplugin sc_size="medium" sc_custom_style="No" sc_add_link="No" sc_brand_name="" sc_hide_resize="No" sc_price="355000.0" sc_pct_dwn="15.0" sc_loan_amt="0.0" sc_n_months="360" sc_rate="5.5" sc_points="0" sc_taxes="6000" sc_insurance="600" sc_pmi="0" sc_currency="83" sc_date_mask="2"]`

`[fcmortgageplugin sc_size="small" sc_custom_style="Yes" sc_hide_resize="Yes" sc_currency="83" sc_date_mask="2"]`

`[fcmortgageplugin sc_custom_style="No" sc_add_link="Yes" sc_brand_name="Friendly Mortgage" sc_hide_resize="Yes" sc_price="275400" sc_pct_dwn="20.0" sc_loan_amt="0.0" sc_n_months="180" sc_rate="5.5"]`

__Optional array parameter passed to *show_fcmortgage_plugin()*__

Valid values for options are the same as the shortcode above.

	<?php show_fcmortgage_plugin(array('op_size' => "medium",
			'op_custom_style' => "No",
			'op_add_link' => "Yes",
			'op_brand_name' => "Karl's",
			'op_hide_resize' => "No",
			'op_price' => "350000.00",
			'op_pct_dwn' => "20.0",
			'op_loan_amt' => "0.00",
			'op_n_months' => "180",
			'op_rate' => "5.5",
			'op_points' => "2.5",
			'op_taxes' => "12000.0",
			'op_insurance' => "1200.0",
			'op_pmi' => "0.0",
			'op_currency' => "999",
			'op_date_mask' => "999"
			)); ?>

*Notes:*

1. If you want to add your brand to the calculator, the *_add_link option must be set to "Yes" (i.e. create a subtle follow link to AccurateCalculators.com). 
1. When branding, the brand name will be added before "Mortgage Loan Calculator".
1. If _custom_style is set to "Yes", the plugin will load fin-calc-widgets-custom.css located in the plugin's CSS folder. If you set the option to "Yes" without making any changes, the calculator will change to a horrendous red which indicates the custom css is being used.
1. The plugin is built and tested on HTML5/CSS3 pages.
1. size (max-width): large: 440px, medium: 340px, small: 290px, tiny: 150px
1. The modal dialog that allows users to select a default currency and date format is NOT compatible with some other dialogs. If you have a modeal on the webpage you want to install this calculator on, either your modal or the calculator's modal may not work.
1. Website developers can set a default currency sign and preferred date format by setting <op/sc>_currency and <op/sc>_date_mask respectively. Set one or both to an integer value. For the list of integers to support 90 plus currency symbols and 6 date format options, see the file __currency_and_date_conventions.txt__ in the plugin's root folder. (example: India, Indian Rupee: ₹1,23,45,678.99 = 83)

*Enhanced Internationalization*

Support for over 90 currency signs (using appropriate nummber formatting conventions) and 6 date formats (mm/dd/yyyy, dd/mm/yyyy, yyyy.mm.dd etc.). If neither the website developer or the user makes a selection, the calculator will read the browser's default currency and date options and automatically use them. Without doing anything, a website visitor from Japan visiting a website hosted in France will initially see a floating yen sign. The website developer can easily override this default behavior by setting either shortcodes or function options. The user (if the website designer keeps the feature enabled) can override both and select a currency and date format.

*To summarize, the plugin determines what currency symbol (and date format) to use by applying the following rules:*

1. If a website allows a user to select a symbol, and the user makes a selection, the plugin uses the visitor's choice first.
2. Otherwise, if a user has not made a selection, and the website owner has set a default currency (i.e., currency does not equal 999), the calculator uses the website's selection.
3. Otherwise, the calculator plugin will attempt to read the browser's currency default and pick a currency symbol.
4. Otherwise, the plugin defaults to using the '$' symbol.

*Language Translations*

New in v1.5, support for 14 languages in addition to English. The plugin also includes a translation template file (.POT) in the "languages" folder. Using a POT/PO file editor, website owners can translate this plugin to any language supported by WordPress.

The supported languages are:

da :  Danish : Dansk
nl :  Dutch : Nederlandse
fi :  Finnish : Suomalainen
fr :  French : Français
de :  German : Deutsch
hu :  Hungarian : Magyar
it :  Italian : Italiano
lt :  Lithuanian : Lietuvių
pl :  Polish : Polski
pt :  Portuguese : Português
ro :  Romanian : Românesc
ru :  Russian : Русский
es :  Spanish : Español
sv :  Swedish : Svenska

Notes: (1) The plugin depends on the site's "Site Language" setting to display to your visitors the correct language. The files in the language folder can be renamed if needed. For example, if you want the plug to display in Portuguese the .MO and .JSON files include assume your site's language setting is Portuguese (Portugal) which is local pt_PT. But if your site is in Brazil, you'll need to rename the plugin's language files to use pt_BR. (2) At this time, these translations were computer generated. A fluent speaker can edit the included .PO file and regenerate the .MO file to update the tranlation. (3) If you are willing to allow me to include your edits with the plugin, I'll regenerate the .MO, and if needed the .JSON files for you.

I am making the other calculators at my website available free-of-charge to bloggers who are abe to help with translation. See the website for details.

*Support*

I'm happy to offer support for all my plugins. If you have a question or face an issue, please go to the plugin page linked in the description above and leave your question at the bottom of the page. I'm able to provide faster support at my site than I am on the WordPress.org website.

*Other Calculators*

As of this writing, AccurateCalculators.com has seven plugins listed in the WordPress Plugin Directory with several more available on the website. All plugins have the same general feature set and are consistent in their styling and the way they work. This means you can install all these plugins and maintain a consistent look and feel across your website or blog. If you blog about money, you are encouraged to install all the plugins on your site. It's simple. The more pages, the more opportunity.

Below links take you to the indicated WordPress Plugin Directory page.

1. [Auto Loan Calculator](https://wordpress.org/plugins/fc-auto-loan-calculator/) - solves for several unknowns and creates a payment schedule.
1. [Loan Calculator](https://wordpress.org/plugins/fc-loan-calculator/) - a general purpose loan calculator with amortization schedule and charts.
1. [Retirement Age Calculator](https://wordpress.org/plugins/fc-retirement-age-calculator/) - answers, at what age will I be able to retire given my investment plan?
1. [Retirement Nest Egg Calculator](https://wordpress.org/plugins/fc-retirement-nest-egg-calculator/) - answers, what will be the value of my retirement fund when I retire?
1. [Retirement Savings Calculator](https://wordpress.org/plugins/fc-retirement-savings-calculator/) - how much do I have to invest periodically to reach my retirement goal?
1. [Savings Calculator](https://wordpress.org/plugins/fc-savings-calculator/) - calculates the results of regular savings and investing


== Frequently Asked Questions ==

__Can the Mortgage Calculator plugin be used on a commercial website?__

Yes. I would be honored. Thanks.

Also, if you happen to be a financial blogger, I would encourage you to add a "Calculators" or "Tools" section to your site and include all my calculators. More content equals more opportunity. I expect to have six free plugins by early 2017.

__Does your plugin have any embedded advertising?__

Absolutely not.

__Is your plugin self contained?__

Yes. 100% of the plugin is installed on your server. There are no external dependencies.

__Does the plugin include any backlinks?__

No, not by default. If you decide to brand the calculator with your brand and / or set the *add_link* option to *Yes*, one discreet link is added to my site. (User will not know there is a link unless their mouse passes over it.) The link is around the copyright in the lower left. :)

__Is the calculator plugin responsive?__

Yes. In fact, I use it on a Bootstrap responsive site. 

__Does the calculator support touch devices?__

Yes. Users use the calculator with all types of devices. (A previous issue with some Android devices is fixed.)

__Do you offer other calculator plugins?__

Yes. I have a conventional [Loan Calculator](https://wordpress.org/plugins/fc-loan-calculator/) hosted in this directory. And here is my [widgets and plugin](https://AccurateCalculators.com/calculator-plugins) page where you'll find other free plugins as well as some "plus" versions which are free for a limited time only.

__I like your plugin and I'd like to contribute something but I notice you don't have a link for contributions, why not?__

Thank you. That's very kind of you. Actually, you can contribute, and it won't cost you a cent. Please stop by my [website](https://AccurateCalculators.com) and check it out. In addition to providing some very advanced calculators, I think that I'm the only one that hosts a public question and answer site to provide support for their calculators. Take a look, and if you like what you see, please spread the word. That's better than any monetary compensation.

__The plugin version indicates it is new. Can I trust it?__

Yes. While this is a new plugin, the calculator has been used on my site for years. It has been used by literally 100,000's of users. Any issues you might encounter would most likely be around installing it on a site. Should you run into an problem, I'm happy to help resolve it.


== Screenshots ==

1. The Mortgage Calculator's front end showing 2 of the 4 configurable sizes, one with custom brand and no sizing buttons.
2. Loan payment schedule shown in a lightbox. User can select how date is displayed from 3 international date conventions.
3. Three charts shown in a lightbox.
4. Plugin's settings dialogue, as seen under *Appearance* *Widgets* page in WordPress's administration area.

== Changelog ==
= 1.5.4 =
* Changed all references to author's website from financial-calculators.com to AccurateCalculators.com.
= 1.5.3 =
* Fixed issue impacting some IOS devices preventing users from entering decimal precision. Example, users could only enter 4%, not 4.25%.
= 1.5.2 =
* Fixed problem initializing calculator when site selected a currency convention that uses ',' as the decimal separator.
= 1.5.1 =
* Not released.
= 1.5.0 =
* Added initial support for language translations
* Change in the minimum required WordPress version to v5.0 (requires i18n support)
* Added documentation how site owners can hide or disable an input from visitors near the top of source file: en/calulator.gui.php
* Renamed file js/interface.MORTGAGE-WIDGET.min.js to js/interface.MORTGAGE-WIDGET.js (the file is still minified)
= 1.4.4 =
* Fixed a style which made the calculator way too tall on some themes (specifically Neve Theme by Themeisle).
* Fixed a calculation error in the chart. Not all prior values were cleared.
= 1.4.3 =
* Fixed a display bug that only impacted some sites if they installed plugin via a sidebar.
* Tweaked styling to make it cleaner.
= 1.4.2 =
* Fixed a bug that prevented your website users from selecting a currency of their choice in some situations.
* Added support for Nigeria's Naira currency symbol
= 1.4.1 =
* Internal change. Never released.
= 1.4.0 =
* Added currency sign support for 80 plus additional currencies.
* Added three additional date styles.
* Now the first time a visitor tries the calculator, the calculator will detect their browser's currency symbol and date style and use them.
* Website owner has the ability to set a preferred currency sign and date format for all first time visitors.
* Website owner can give the visitor an option to select their preferred currency symbol and date format.
* IMPROVEMENT: on mobile devices calculator opens numeric keyboard by default.
* UPDATE: some layout and style settings
* BUG FIX: Fixed an input issue on Android devices that required a long press to input numbers for some Android users.

= 1.3.0 =
* Slight style changes
* Tweaks to work better with WordPress 5.0
* Switched from Bootstrap v3 to Bootstrap v4 utilities. If your site is built using Bootstrap 3, this may cause conflicts. Test on a dev machine.
* Since there are no user enhancements, I would only upgrade if you have experienced a style issue.

= 1.2.0 =
* Multiple fc calculators can now be used on a single web page.
* On a few sites, the text in the dropdowns was cut off - fixed.
* Previously, if site had opted to allow backlinks, 2 had been inserted. Now one is inserted.

= 1.1.2 =
* Updated the CSS so as to resolve a few reported compatibility issues with some sites.
* Improved layout of international date and currency selection dialogue.

= 1.1.1 =
* Fixed - some installations the currency / date dialogue was not accessible because the background overlay was on top of the dialogue.
* Fixed - some installations the Help text was visible on the main page and not just when the Help button was clicked
* Fixed - missing "+" "-" signs for the optional resizing feature

= 1.1 =
* Improved styling
* Converted project to use a single file CSS regardless of calculator size selected. Single file will be compatible across all AccurateCalculators.com plugins so site owners can modify style once and copy to all other plugins.

= 1.0.2 =
* Fixed readme.txt, 2 errant commas in source and optional copyright backlinks.

= 1.0 =
*	First release

== Upgrade Notice ==
No upgrades.
