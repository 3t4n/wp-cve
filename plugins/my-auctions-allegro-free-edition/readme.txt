=== My auctions allegro ===
Contributors: wphocus
Tags: allegro, wordpress, import, auctions, display, woocommerce, product, category, media
Requires at least: 5.0
Tested up to: 6.2.0
Stable tag: 3.6.13
Requires PHP: 7.4
Support Link: https://wphocus.com/
Demo Link: https://tastewp.com/new?pre-installed-plugin-slug=woocommerce%2Cmy-auctions-allegro-free-edition&redirect=plugins.php&ni=true
Text Domain: my-auctions-allegro-free-edition
Domain Path: /lang/

== Description ==

= The best description will be if I describe all functionalities that this extension can do. =
- You can connect plugin with REST API and/or WebAPI
- You can easily add infinity count of settings (apps), for example if you have 3 accounts on allegro, you can add all to plugin
- You can get your or somebody else auctions from allegro to WordPress.
- You can show them as widget and/or as shortcode on page/post, to do it you just need to add some code (ex. [gjmaa id="5" count="10" show_price="0" show_time="0" show_comments="0" count_of_comments="5" /])

= Do you have WooCommerce? Awesome! =
- You can import auctions from allegro as products
- Importer only add new products
- Autosync Stock of products ( you need to run cron on your server )
- Autosync Price of products (you can enable price update to sync between allegro and your WooCommerce)
- Automap Categories (schema like on allegro)

= Do you want more? =
- Don't hesitate to be in touch

= Do you need support? =
- I can help you

= Do you want to test plugin by one click? =
- Check it out <a href="https://tastewp.com/new?pre-installed-plugin-slug=woocommerce%2Cmy-auctions-allegro-free-edition&redirect=plugins.php&ni=true">here</a>

== WooCommerce Allegro PRO ==

= Features =
- Import orders to WooCommerce
- Update status of order in WooCommerce based on status in Allegro
- Possibility of send order from WooCommerce
- Map categories and attributes
- Creating your own templates
- Possibility of management multiple accounts in one place
- Two-way synchronization of stock (WooCommerce -> Allegro and vice versa)

Pro Version was released at 15.04.2019!

Hope that this will help you to manage products / orders from allegro in woocommerce more simple.

Ream more about <a href="https://wphocus.com/produkt/woocommerce-allegro-pro-integracja/?utm_source=wp-plugins&utm_medium=banner&utm_campaign=advertise" target="_blank">WooCommerce Allegro PRO</a>.

== Support ==

1. You can find all possibilities of contact with me at plugin dashboard.
2. You can call to me
3. And much much more

Watch a video that will show you how to set up this extension

[youtube https://www.youtube.com/watch?v=fBjf3cmKmRI]

Regards WPHocus

== Installation ==

1. Upload plug-in to wp-content/plugins
2. Enable plug-in in your panel administration
3. Go to Plugin Settings, Add new, Please fill all required fields and save.
4. Click on Create App button, please login and create app. Please copy Redirect URI to application.
5. Get your Client ID and Client Secret and fill in form (save).
6. Click on "Connect" to get Client Token.
7. Go to profiles and add your new profile.
8. Set your configuration of imported auctions
9. Go to My auctions allegro - Import
10. Choose your profile and click Import
11. After import you can create a widget where you will show auctions
12. Then you can see how it looks on front of your WordPress.

== Screenshots ==

1. Dashboard
2. Plugin Settings Table
3. Plugin Settings Add
4. Plugin settings connected WebAPI
5. Plugin settings connected Rest API
6. Profiles
7. Add Profile
8. Ready profile Aukro.cz
9. Ready profile Allegro.pl
10. Ready profile with import to WooCommerce
11. Import section
12. Import processing
13. Import success
14. Import failed
15. Imported auctions to WordPress
16. Imported auctions as products in WooCommerce
17. Product Imported
18. Shortcode
18. Widget

== Frequently Asked Questions ==

= Plugin installed, what's next? =
Please watch video prepared specially for you!

[youtube https://www.youtube.com/watch?v=fBjf3cmKmRI]

= Clicking on button "Create App", not trigger any event =
Your browser could block popup windows (please allow to open)

= How to import auctions from allegro =
First, we need to add allegro profile to your plug-in. Go to My auctions allegro -> Profiles. Click add, set your profile and click save. Then you can go to import auctions and choose your added profile and click "Import" to import your auctions.

= Auctions are imported, but shortcode provided on page shows message "no offers" =
Main problem of this is that your shortcode probably copied from description of plugin has incorrect characters
WordPress replace simple quotation marks with a little curved, that's why your shortcode not working.

= Products has different stock state than on allegro =
Please write to your host provider, about task scheduling (CRON), it allow to automate your stock synchronization

= Import for auctions of user stop working =
Please immediately update your plugin to minimum v2.4.0

== Upgrade Notice ==

= 2.8.0 =
Before run update, please be sure that your WordPress has setup server cron for more read https://grojanteam.pl/poradnik/93-cron-czyli-jak-uruchomic-harmonogram-zadan-dla-wordpress
In this version is totally updated flow of import auctions

= 2.4.0 =
Very Important Notice for users that use import type "Auctions of user", please update it immediately and go through tips in profile seller ID field!
If you only use import type "My Auctions", you don't need to be afraid.

= 2.0.28 =
WooCommerce breaks wc_get_product_id_by_sku method in version 3.6.1, this upgrade is needed to not create duplicates of imported auctions

= 2.0.0 =
Plugin core totally changed, added REST API connection, you need to configure your application with OAuth2

= 1.9.16 =
Allegro change limit from 25 to 10 (without update, woocommerce import will not work)

= 1.7 =
Upgrade is needed if you have version 1.6.2 because of problems with aliases, also if you have WooCommerce Store in your WordPress you can use this plugin to import auctions from allegro as product

== Change Log ==

= 3.6.13 = fix fatal error when update plugin

= 3.6.12 = Hpos support - fix small bug

= 3.6.11 =
- Add downloading of attributes from productization

= 3.6.10 =
- Bug fix

= 3.6.9 =
- Bug fix

= 3.6.8 =
- Update Allegro API path that are deprecated - follow up

= 3.6.7 =
- Update Allegro API path that are deprecated

= 3.6.6 =
- rebranding to WPHocus

= 3.6.5 =
- fix for checking status of synced auctions

= 3.6.4 =
- add possibility to choose if you want to link WooCommerce Product by Allegro Signature

= 3.6.3 =
- added flag to choose if you want to only map offers from allegro to existing products or import everything

= 3.6.2 =
- update accept language for allegro depends on wp configuration

= 3.6.1 =
- fixed sync with cron flag

= 3.6.0 =
- update relates to support for DC MultiVendor from WC Marketplace
- fix type of value for signature

= 3.5.3 =
- extend modifications for woocommerce product
- fix import offers from allegro

= 3.5.2 =
- stop generating new media entry if nothing is updated

= 3.5.1 =
- support for import additional data ( like size tables )

= 3.5.0 =
- automate map offers from allegro with woocommerce products by allegro signature and product sku (or create new with sku as auction id)

= 3.4.1 =
- fix for regenerate media and limit to 30

= 3.4.0 =
- added screen options
- added hooks

= 3.3.1 =
- fix for saving multiple allegro attributes

= 3.3.0 =
- cron job optimization

= 3.2.1 =
- fix problem with removing products (if set) after renew offer

= 3.2.0 =
- optimize import auctions from over 10s per auction to 1s
- move generating media images to separate cron job (running every 5 minutes)

= 3.1.1 =
- fix permissions to plugin menu

= 3.1.0 =
- added possibility to import auctions as product with specified status in allegro (not only ACTIVE)
- fixed calculation with visits on auctions (visit is calculated when you come to page with auctions / not visit on allegro)

= 3.0.2 =
- fix import media to description

= 3.0.1 =
- fix update latest modification from allegro

= 3.0.0 =
- add possibility to update gallery, categories and attributes
- limit free version to 100 auctions if no pro version
- compatible with the latest version of WooCommerce, WordPress and PHP

= 2.9.1 =
- fix updating title of product to be equal to auction title

= 2.9.0 =
- add possibility to update title, description
- fix for import description

= 2.8.12 =
- fix database for different mysql version

= 2.8.11 =
- add category update date and options

= 2.8.10 =
- possibility to import only specified type of offers (BUY_NOW, ADVERTISEMENT, AUCTION)
- fix for url request with multiple values

= 2.8.9 =
- fix creating drafts

= 2.8.8 =
- decrease batch for creating product from auctions
- fix countable variables

= 2.8.7 =
- fix for duplicates with auction_id 0 on the plugin auctions list

= 2.8.6 =
- add possibility to check and fix database (when update goes wrong)

= 2.8.5 =
- fix load first category data if not set specific account

= 2.8.4 =
- stable tag for fixes

= 2.8.3 =
- fix undefined variable

= 2.8.2 =
- fix import price from plugin to woocommerce
- fix import stock from plugin to woocommerce

= 2.8.1 =
- PHP 8 stability
- fix for import events if one of account fail

= 2.8.0 =
- changed flow of import auctions
- every day is imported full profile
- flag full import if any auction is activated on allegro
- flag update stock if stock is changed
- flag update price if price is changed
- flag auction as ended if is sold on allegro or is ended by any action
- update stock in woocommerce if any stock is updated on allegro
- update price in woocommerce if any price is updated on allegro

= 2.7.0 =
- fix problem with empty entries in auctions
- fix problem with changing stock to out of stock even if auction on allegro is active and has > 0
- add new status in trash to auctions level (this state describe that product mapped with auction is in trash)
- add new flag sync stock to profile, you can disable import auctions from allegro to woocommerce and instead you can sync only stock
- update flag sync price for profile, you can disable import auctions from allegro to woocommerce and instead you can sync only price
- possibility to remove single entry from auction list (you can also use clear auctions from profile level)

= 2.6.18 =
- fix problem with attribute wih too many options
- fix import multiple text values

= 2.6.17 =
- throw exceptions with HTTP Code
- fix loop for price synchronization

= 2.6.16 =
- fix fail on categories import

= 2.6.15 =
- fix for updating categories

= 2.6.14 =
- fix for import with existing categories

= 2.6.13 =
- add possibility to use DELETE request on API

= 2.6.12 =
- fix import attributes
- possibility to add CTA button for product (link to allegro)
- fix initialize SOAP client without SOAP extension

= 2.6.11 =
- type terms to integer

= 2.6.10 =
- fix creating categories (no more duplicates)
- fix aukro cron import

= 2.6.9 =
- fix update auction prices from cron

= 2.6.8 =
- fix file structures

= 2.6.7 =
- fix auction prices

= 2.6.6 =
- fix auction prices

= 2.6.5 =
- Fixes import for auctions

= 2.6.4 =
- Fix import attributes

= 2.6.3 =
- Import attribute amends

= 2.6.2 =
- Fix for category filter

= 2.6.1 =
- Default value for category level

= 2.6.0 =
- Limit my auctions to category level
- Save products in WooCommerce with deepest category level (new field in profile)

= 2.5.2 =
- Prevent to not throw error when table exists

= 2.5.1 =
- Fix import auctions using cron

= 2.5.0 =
- Stop support older version than PHP 7.2
- Stop support Web API
- More information about lost connections
- Rewrite import to WooCommerce
- EAN as first is taken to connect with WooCommerce Data (if no EAN -> SKU will be as always auction number)

= 2.4.4 =
- Optimization related to refresh token

= 2.4.3 =
- support non-ssl sites
- fix for Aukro

= 2.4.2 =
- refresh token 2h before expire

= 2.4.1 =
- fix price value sync

= 2.4.0 =
- fix import auctions of user (changed logic) look for FAQ

= 2.3.12 =
- fix buy now price / bid price for auctions

= 2.3.11 =
- added possibility to update prices (you can choose)
- update prices between allegro and woocommerce

= 2.3.10 =
- fixes for core of module

= 2.3.9 =
- support error codes on Allegro REST API

= 2.3.8 =
- fix for creating product categories (no duplicates anymore)

= 2.3.7 =
- use EAN field for sku if filled

= 2.3.6 =
- fix for lock profile when import using cron
- [widget, shortcode] show only active auctions

= 2.3.4 =
- support for woocommerce lower than 3.6

= 2.3.3 =
- rebuild product index of search and sort after import

= 2.3.2 =
- added to auction lock profile during import auctions
- speed up import data
- optimize usage of memory
- optimize creating products
- optimize manage of stock for imported products

= 2.2.5 =
- skipping ended and not active auctions

= 2.2.4 =
- fix search and pagination
- fix decision related to closed auction
- removed clear database on disable plugin
- added restart system in support section
- added checking allow url fopen is enable in dashboard
- added filters

= 2.2.3 =
- fix creating products when one of profile is broken
- fix removing products action when auction is ended

= 2.2.2 =
- fix getting auctions for user
- clearing media when products are removed

= 2.2.1 =
- fix redirect system

= 2.2.0 =
- added status to auctions
- remove connection with WebAPI
- add possibility to choose action what to do with products when auction is ended
- add support tab with new videos
- fix problem with import auctions on different settings
- use WebAPI only for aukro.cz site
- send error when REST API is not connected

= 2.1.5 =
- refactor code related to database

= 2.1.4 =
- fix problem with import auction to WooCommerce (check state)

= 2.1.3 =
- fixes

= 2.1.2 =
- add support for other delivery countries

= 2.1.1 =
- you can enable/disable import auctions by cron

= 2.1.0 =
- added new template slidebox to shortcode [ add to shortcode template="slidebox" ]
- minify css
- move scripts and styles to footer
- optimize for page insights
- remove not needed css / js

= 2.0.32 =
- change helper method price (support PHP 5.x)

= 2.0.31 =
- fix default template name (typo)

= 2.0.30 =
- add new template for listing auctions (shortcode / widgets) to use in shortcode just add in shortcode new template="list" for example
- add to product list, auction ids that are assigned to woocommerce product
- support lazy load (lozad lib)

= 2.0.29 =
- getting data with sandbox

= 2.0.28 =
- avoid problem about not working method wc_get_product_id_by_sku, broken by WooCommerce 3.6.1

= 2.0.27 =
- fix problem for sync stock when WC Allegro PRO is enabled

= 2.0.26 =
- fix problem with display current bid on wordpress
- fix problem with cron schedules

= 2.0.25 =
- Add new parameter to categories table

= 2.0.24 =
- Avoid problem to connect with sandbox webapi

= 2.0.23 =
- fix condition to create new categories in WooCommerce

= 2.0.22 =
- Always check vars send by request from WP

= 2.0.21 =
- fix names of sorting options

= 2.0.20 =
- fix sorting profiles
- possibility to set clear auctions in profile (it will clear auctions every new import)

= 2.0.19 =
- fix database installation after download from another plugin

= 2.0.18 =
- fix requests to allegro

= 2.0.17 =
- fix getting auctions from sandbox environment
- fix sorting auctions

= 2.0.16 =
- prevent before get auctions from other user if we can't get user ID
- adding columns to handle errors
- added plugin notifications

= 2.0.15 =
- if you remove profile, all assigned auctions also will be removed

= 2.0.14 =
- added possibility to set your own image width and image height in widgets and shortcodes
- in [gjmaa /] shortcode you need to add image_width="your own width in pixels" ex. 100 and image_height="as in width" ex. 50

= 2.0.12 =
- backwards compatibility

= 2.0.11 =
- backwards compatibility

= 2.0.10 =
- Restored showing time to end
- Show only actual auctions
- Recognize using sandbox mode or not

= 2.0.9 =
- Complete translation for Plugin

= 2.0.7 =
- Fix after choose to show price
- Added video tutorial how to set up configuration

= 2.0.6 =
- Show column profile ID

= 2.0.4 =
- during import auctions, was error with saving categories from rest API

= 2.0.3 =
- fixed loading categories after integrate aukro.cz

= 2.0.2 =
- fixed typo in schema of database

= 2.0.1 =
- fix getting model of mapped categories
- revert method for translations
- sometimes widget wasn't proper rendered
- optimize code for upgrade database

= 2.0.0 =
- Update Core of plugin (from scratch)
- Migration data
- New design of plugin
- Possibility to add more than one connection (sandbox mode, webapi, rest api)
- Importing only new auctions as WooCommerce Product
- Faster update of stock room
- Possibility to add your own template of widget

= 1.10.2 =
- fix typo in method

= 1.10.1 =
- fix importing auctions by adding auction id at the end of slug
- optimize woocommerce sync

= 1.10.0 =
- saving auction attributes to woocommerce product
- refactoring sort options for import
- added profile name field for auction settings
- fix some bugs
- compatible with WP 5.x and PHP 7.2

= 1.9.16 =
- allegro change limit from 25 to 10 (without update, woocommerce import will not work)

= 1.9.15 =
- fix database update

= 1.9.14 =
- Add posibility to close notice

= 1.9.13 =
- Big upgrade coming soon, needed feedback about supporting plugin

= 1.9.12 =
- fix problem with importing auctions (caused not set sort options and get randomly auctions)
- added columns of process import
- full path of allegro category set in auction settings
- added notifications

= 1.9.11 =
- fix calculating date to timestamp
- added column last synchronization to auction settings
- fixed encoding

= 1.9.10 =
- added synchronization option to choose (minimum every 1 hour)

= 1.9.9 =
- adding 3 tries before end import, if API is not stable

= 1.9.8 =
- improove import for auctions
- split import to 3 steps (around 10k for 1 hour)
- added new frequency for cron (every 5 minutes)

= 1.9.7 =
- add current bid to import
- add new option to show price (current bid)
- choose between show buy now price or current bid price

= 1.9.6 =
- add support for aukro.cz (it could be really dangerous change, if you have any issues, please create a ticket)

= 1.9.5 =
- fix readme


= 1.9.4 =
- add possibility to search auctions on frontend side
- add possibility to sort auctions by price, title, time to end


= 1.9.3 =
- fix for js scripts

= 1.9.2 =
- fix for installation main table

= 1.9.1 =
- fix for installation

= 1.9.0 =
- possibility to map allegro category with woocommerce category

= 1.8.2 =
- fix for creating duplicate products

= 1.8.1 =
- optimize moving images (max_execution_time resolved problem)

= 1.8.0 =
- disable limit for downloaded auctions, you can set how much auctions you want to get

= 1.7.9 =
- get more image from allegro auctions and import as product gallery
- set thumbnail from allegro as main product image

= 1.7.8 =
- fix new database installation

= 1.7.7 =
- Beautify description imported from allegro.

= 1.7.6 =
- fix woocommerce product description
- fix woocommerce product gallery
- fix woocommerce cron frequency

= 1.7.5 =
- Changed media upload from allegro because of problems with broken access media in woocommerce
- Disable updating existing products in WooCommerce
- Cron Run Hourly instead of one per day

= 1.7.4 =
- Added row action "Show on allegro" to woocommerce product table (if imported from allegro)

= 1.7.3 =
- Fix import auctions using cron

= 1.7.2 =
- Fix problem with automatic update

= 1.7 =
- Import basic information from allegro auctions as WooCommerce Product

= 1.6.2 =
- Fix translations again

= 1.6.1 =
- Fix synchronize auctions using cron

= 1.6 =
- Added cron job to sync auctions once per day for profile

= 1.5 =
- Compatible with PHP 7
- Fixed Category Update
- Fixed translations
- Fixed widget form

= 1.4.1 =
- Removed feedback list

= 1.4 =
- Support Google Structural Data

= 1.3.5 =
- Not supported aukro.cz (bug with getting sites) FIXED

= 1.3.4 =
- Added new option of auction sort

= 1.3.3 =
- Fix shortcode popup

= 1.3.2 =
- Fix import categories for all countries
- Fix import auctions from Aukro.cz

= 1.3.1 =
- Fix encryption password stored in database
- Update categories will be change automatically

= 1.3 =
- Added to view link `details`
- You can choose that you want to show link `details`
- Click on `details` show your html auction in popup
- Fix to show more than 10 auctions

= 1.2.2 =
- Import auctions from allegro with details or not, before start import you need to choose

= 1.2.1 =
- Fix category loading when you edit existing settings of auctions

= 1.2.0.1 =
- Fix translations for plug-in

= 1.2 =
- Added to WordPress catalog plugins (Refactor)

= 1.1 =
- possibility to show comments from allegro profile
- preparing database to showing details of auction on WordPress Page / Post
- add 2nd stage of importing auctions to WordPress (details)

= 1.0 =
- possibility to add unlimited auction settings
- possibility to import auctions from allegro
- possibility to show auctions as widget
- possibility to show auctions on post/page as ShortCode
- possibility to set up settings of auctions based on category, query search or type of auction
