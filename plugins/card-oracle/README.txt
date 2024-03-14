=== Tarot Card Oracle ===

Contributors: chillichalli, freemius
Donate link: https://paypal.me/cartouchecards?locale.x=en_US
Tags: Tarot cards, Cartouche cards, Tarot readings, Runes
Requires at least: 4.6
Tested up to: 6.2
Stable tag: 1.1.6
Requires PHP: 5.6.20
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin lets you create Tarot, Cartouche, Rune, and oracle readings using your own cards, spreads, and interpretations.

== Description ==
Create your own oracle and tarot readings. Use this plugin to build any spread, using any deck of cards and any interpretation of those cards. If cards have different meanings in different positions, it’s possible to define each card for each position.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress 
plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Card Oracle->Dashboard to configure the plugin

== Frequently Asked Questions ==
= How do I even start? =
The best place to start is with our videos. Tarot Card Oracle is very configurable and can do a lot, but we know that can cause some confusion. So as well as the creation Wizard and Demo data you can install, we have created a set of videos to help you get set up. To get started go to:
[https://www.chillichalli.com/tarot-card-oracle-videos/](https://www.chillichalli.com/tarot-card-oracle-videos/)
= What is the best way to build a reading =

Use our new Wizard to create it! But if you want to do it manually then:
1. Start by creating a Reading.
2. Create all the Positions for the Reading.
3. Create all the Cards.
4. Create a Description for each Card in all the different Positions.
5. Copy the Shortcode for the Reading and add it to a page.
= How do I see the reading on my site? =
You can find the Shortcodes for your Readings in one of two places. The Readings menu or the Dashboard.
1. From the Readings menu it's located next to the reading in the Shortcode column with a copy button. Click the copy button and paste that into a page.
2. From the Card Oracle Dashboard, click the Reading Shortcodes for the Reading you want, a popup will appear, copy the Shortcode and paste that into a page.

== Screenshots ==
1. Card Oracle Dashboard - Dashboard showing statistics for Card Oracle. Giving you the totals of all Readings, Positions, 
Cards, and Descriptions. As well, as breaking down the individual Readings.
2. Add New Reading page - Enter your readings on this page.
3. Add New Positions page - Enter your Positions on this page.
4. Add New Cards page - Enter your Cards on this page.
5. Add New Descriptions page - Enter your Descriptions on this page.
6. Validation wizard (Premium) - Check your Readings, Positions, Cards, and Descriptions for errors and quickly correct them using the links.
7. Email Settings (Premium) - Options for allowing users to subscribe to your list. Daily Card settings for sending cards to your subscribers.
8. Integrations (Premium) - Settings to configure your subscriber email lists. Supports ActiveCampaign, MailChimp, and SendinBlue current, more coming soon. Yours not on the list email us and request it.
9. Question Layout (Premium) - Example of the various layouts available when displaying the question to the user.

== Videos ==
A brief overview of how the WordPress plugin elements, Readings (Spreads), Positions, Cards, and Descriptions work together.
For all our videos visit our site [ChilliChalli.com](https://www.chillichalli.com/tarot-card-oracle-videos/) or our [youtube channel](https://www.youtube.com/channel/UCa2PDf86FYDSsdYV5dDd0ew).
Overview: [youtube https://youtu.be/1smjZpmUeNE]


== Changelog ==

= 1.1.6 = 

* New: Update Freemius to version 2.5.10
* Tweak: Code cleanup.
* Fix: Remove unused files.

= 1.1.5 =

* New: Update Freemius to version 2.5.6

= 1.1.4 =

* New: Update Freemius to version 2.5.4

= 1.1.3 =

* New: Reading can be set to open in a new browser window or tab.
* Tweak: Code cleanup.

= 1.1.2 =

* New: Can add all Major and Minor Marseille Tarot Deck cards to Media. Use the Demo Data menu.
* Fix: Reversed card alignment.
* Fix: Email button not appearing.
* Fix: Toggle switch icons hidden.

= 1.1.1 =

* Fix: Code cleanup.

= 1.1.0 = 

* New: Add Stripe payment options (Premium).

= 1.0.6 =

* New: Update Freemius to version 2.4.3.

= 1.0.5 =

* Fix: Toggle switch error in PHP 8.
* Fix: Blank email address field after sending the email to the user.
* Fix: Update the order email to be same as non-order.

= 1.0.4 = 

* New: Mobile style layout, displays mobile layout even on larger screens.
* Tweak: Added examples for Standard and Mobile layouts.
* Fix: Images not reversed on Overlapping and Circular deck display layout.

= 1.0.3 =

* New: Random card can be configured to displayed for a day or more in the General tab.
* Tweak: Additional information added to the status page.
* Tweak: Updated PayPal to use wp_mail.
* Tweak: Code cleanup.
* Proformance: Cached daily and random cards. Daily cards cached until midnight. Random cards for 10 minutes if number of days is not set.
* Fix: Reversed images displaying right side up on results pages for some types of layouts.

= 1.0.2 =

* Fix: Paypal invalid item_name corrected
* Fix: Celtic Cross card 2 not rotated properly on all browsers.

= 1.0.1 =

* Fix: User was able to pick 1 additional card.

= 1.0.0 =

* New: Removed the limits for readings, positions, and cards for the free version.
* Tweak: Code cleanup.
* Tweak: CSS updates.
* Tweak: Logging updates.
* Fix: Some emails sent with entire page instead of just the reading.

= 0.27.0 = 

* New: Added three card Past Present Future layout.
* New: Added another three card display layout (Premium).
* New: Added another five card display layout (Premium).
* New: Added two card display layout (Premium).
* New: Added four card display layout (Premium).
* Tweak: Only show card layouts that match the number of positions for a reading.
* Tweak: Removed unused CSS to reduce size.
* Tweak: Allow Layout table display for all users.
* Fix: Javascript incorrect counting of the picks if user double clicks card.
* Fix: Updated incorrect parameter name.
* Fix: Ended div in wrong place for supplemental text box.
* Fix: Incorrect value for demo data in log file.
* Fix: Error checking in Newsletter API.

= 0.26.0 =

* New: Added Celtic Cross display layout (Premium).
* New: Added Tree of Life display layout (Premium).
* New: Added 2 five card display layout (Premium).
* Tweak: Updated layout formatting for Footer text.
* Tweak: CSS tweaks.
* Tweak: Removed the need to create php session for wizard.
* Fix: Fixed several Spanish translations.
* Fix: Fixed RSS feed check is array.

= 0.25.0 =

* New: Added PayPal integration for Readings (Premium).
* Tweak: Improve translations sanitization.
* Tweak: Adding logging for email failures.
* Tweak: Update email from address to include <>.
* Tweak: CSS updates for list items.
* Fix: Remove unused files.

= 0.24.0 =

* New: Added The Newsletter Plugin integration (Premium).
* Fix: Incorrect email directory fixed.

= 0.22.0 =

* New: Minimized JS files for faster performance.
* New: Update Freemius to version 2.4.2.
* New: New classes added for better responsive display.
* New: Changed Card image method for WordPress 5.7.
* New: Added lazy loading to Card images for WordPress 5.7.
* Tweak: Disable Free version when Premium verison activated.
* Tweak: CSS updates for better user display experience.
* Tweak: Removed duplicate code for Daily and Random cards.
* Tweak: Updated Demo media text for improved readablity.
* Fix: CSS updates for WordPress 5.7.
* Fix: Fixed json error when adding shortcode in Gutenberg shortcode block.
* Fix: Reversed cards not working on new layouts.

= 0.21.0 =

* New: Update to text displayed to user for positions. Admin can now choose to display the position title, the position post text, or nothing. The post text is fully editable. (Premium)
* Tweak: Small CSS changes for Reading settings display.

= 0.20.3 =

* New: Added new responsive overlapping layout.
* New: Removed hardcoded 'Select x Cards.' from user display. Now customizable per reading.
* New: Minimized CSS files for faster performance.
* New: Can change the location of Question and Button. Can align them Left, Right, or Center. Question input and button can also be set inline, Inline Left, Inline Right, or Inline Center. (Premium)
* New: Added SendinBlue email and marketing automation integration. (Premium)
* New: Added subscriber list to readings. Allows you to subscribe users to different lists based on Reading. (Premium)
* Tweak: Reading Settings look with new design and layout.
* Tweak: CSS cleanup.
* Tweak: Replaced direct cURL call with WordPress remote request.

= 0.19.0 =

* New: Added Auto submit option for readings. The results page will be displayed after user select final card.
* New: Added Circular layout for displaying readings. (Premium)
* Fix: MailChimp update for API to add subscribers.
* Tweak: Updated checkbox javascript for better response/performance.

= 0.17.0 =

* New: Added a Daily Card RSS feed for Readings. (Premium)
* Fix: Copy button not working.
* Tweak: Ablility to change number of logs show on status page.
* Tweak: Code cleanup.

= 0.16.0 =

* New: Added Wizard for creating Readings quickly.
* New: Added new CO_Logging class for logging events.
* New: Display last 20 (if any) errors to the Status page.
* New: Improved speed and flow of ActiveCampaign and MailChimp integrations.
* New: Added public-facing transients for DB queries to improve site speed.
* Tweak: Updated Shortcode modal popup design.
* Tweak: Change email content to a transient.
* Tweak: Additional sanitization of internal calls.
* Tweak: Updated Author to ChilliChalli.
* Tweak: Code formatting improvements.
* Tweak: Centered and enlarged copy button image.
* Tweak: Grouped Defines together.
* Tweak: Changelog now in descending order.

= 0.15.0 =

* New: Added ActiveCampaign to Integrations. (Premium)

= 0.11.0 =

* New: Add demo data for a 3 position past, present, future reading.
* Fix: Minor bug fixes

= 0.9.0 =

* New: Added option to email Daily Cards to subscribers.
* New: Added Daily Card email to MailChimp (Premium)
* Fix: Removed duplicate validation test 

= 0.8.1 =

* New: Added MailChimp integration (Premium)
* Fix: Remove missing file from require_once
* Tweak: Updated Admin Styling

= 0.7 =

* New: Added Validations for the Premium version
* Fix: Fixed slider checkbox display
* Fix: Fixed sortable field Order
* Tweak: Removed unused code.

== Upgrade Notice ==

= 0.8.1 =
* Tweak: Update options to include card_oracle.

== Translations ==

* English - default, always included
* French
* German
* Portuguese
* Spanish

== How to ==

The Card Oracle is made up of different areas:

= Menus =

*Dashboard*
Where the user gets an overview of what's been configured in the Card Oracle and where they can pick up the Shortcodes 
for their Readings to display on the front end of the site.

*Readings*
Where the user defines the Spreads that are going to be used

*Positions*
Where the user defines the Positions to be used with each specific Reading/Spread. Positions are assigned to a Spread here.

*Cards*
Where the user defines the Cards that will be used with the Readings/Spreads

*Descriptions*
Where the user configures the definitions used for each Card in each specific Position defined in 'Positions' above.

*Demo Data*
This will create an example Tarot Card reading. It is a custom Past, Present, Future Reading using the 22 Major Arcana cards. You are free to use this on your site but only with the Card Oracle plugin. You can update it as needed, ie. removing (Demo) from the Title, etc.

*Orders (Premium)*
The purchases from your Card Oracle readings will be displayed on this tab. In includes the purchasers email address, price, status, the reading details, transaction details and the date and time of the purchase.
Status
*New - the user has submitted the reading but has not finished payment yet.
*Completed - the user has made the payment and an email has been sent with their reading.
*Payment error - the user has paid an incorrect amount for the reading.

*Validation (Premium)*
Shows protential problems with Readings, Positions, Cards, and Descriptions. It includes links to fix the common problems found.

= Dashboard =
This provides an overview of the Readings, Positions, Cards, and Descriptions that the user has defined within their version 
of the 
Card Oracle.

It is a useful quick sanity-checker – if you know how many Cards you have, and you know how many Positions you have for each Card 
for each Reading, you can quickly check that you have a Description defined for every Card in every Position in a specific Reading.

The user must take care to link Descriptions to the correct Cards and Positions. And in turn to link the Positions to the correct 
Reading, but this is a great overall checker – if the numbers do not add up, then you need to check your configuration.

Additional tabs on Dashboard:

*General*
Multiple Positions for a Description – this is the global setting where you can allow the Card Oracle to use the same Position for multiple Descriptions within a single Reading and across multiple Readings. The multiple Positions for a Description on the Readings 
tab will not work if this is not enabled.

*Email Settings*
If you want your users to be able to email themselves a copy of their Reading, then toggle the 'Allow users to send Reading to an email address' option here. 

From email address – this is where you define the email address from which the Readings will be sent.

From email name – this can be different from the email address above if you have a site name or want to use your own name.

Text to display
This is where you can configure what text you want to display – it accepts HTML. If this is left blank, the default text reads: 
"Email this Reading to:"

*Integrations* (Premium)
Setup the integration to various List/Email providers. The current integrations include  ActiveCampaign(tm), MailChimp(tm), and SendinBlue(tm). Need one that's not listed? Email us.

*Payment Provider* (Premium) 
Setup for the payment providers. Currently, the only PayPal is supported. Please let us know if you require something different.

*Wizard*
Creates the basic framework of a Reading. Creates the Reading, Positions, Cards, Descriptions, and all the links between them. You will need to add your text and images for the cards. As well as your text for the Descriptions in the upright and reversed positions.

*Status*
Display of current WordPress and Card Oracle settings. Used to help debug any issues found including the last 20 Card Oracle error logs, if any.

= Readings =
This tab is used to define the different Readings (or Spreads) the user wants to offer on the front end of the site.

Click Add New Reading.

*Title*
Add the name of the new Reading.

*Settings*
Settings offer the following options:

Display Question Input Box - when enabled, this displays a question input box. The submit button will only show (it displays after the correct number of Cards have been picked) if the text has been entered in this box. If you do not want your user to enter 
a question for this Reading, leave this toggle disabled.

Text for input box - allows you to prompt the user to enter text in the input box - e.g. 'What is your question today?', 
'Where do you need clarity' etc.

**IMPORTANT** - do NOT use apostrophes in your text here, if you plan on allowing users to email the Readings to themselves.

Footer to be displayed on daily and random Readings - This is a free text area - you can use it to upsell other Readings or 
to direct your users to another link or Reading.

*Back of Card Image*
On the right panel of the screen, underneath the WordPress standard post content, add an image for the back of the Card. This is what will display before your user picks their Cards. There is a default image programmed into the Card Oracle which will display 
if you do not add a Card Image, but you can personalise your Card back here.

To save the Reading, click on the Publish button as you would with a normal WordPress post.

**Top Tip:** To save time, if you are going to offer multiple Readings, create all of them before you start configuring the rest 
of the Card Oracle - you'll save yourself a lot of clicks.

When you have created your Reading and saved it, return to the main Dashboard tab.

*Shortcode*
You will see a record with your Reading name, and then a Shortcode option.

If you click on the Reading Shortcodes link at the bottom of the record (below the Positions, Cards and Descriptions), you will 
be presented with three Shortcodes:

Reading Shortcode
This displays the backs of the number of Cards that you have defined for this Reading. When the user clicks on the correct number 
of Cards (and may or may not need to enter a question, depending on how you have configured the specific Reading) the submit 
button will display, the user will click the submit button in order to display the results of the Reading.

This is the Shortcode you will use if you have more than one Position in the Reading.

Daily Card Shortcode
Each day, this displays one new Card on the page/post you have used the Shortcode. The Card will be one of the Cards you have defined on the Cards tab. The plugin starts with the first Card you created and then it displays them all in the order that you created them (time and date order). If you want the Cards to display in a 'random' order, you will need to create them in a 'random' order in the first place. Once the last Card you have defined has been displayed, the Oracle will start displaying them all again from the start. Only one Card is displayed for this Reading.

Random Shortcode
This displays one random Card each time you refresh a page. Only one Card is displayed for this Reading.

To the right of each Shortcode, you'll see a copy button. Click this to copy the Shortcode. Paste the Shortcode into the 
the relevant module on your post or page to display it on the front end of the site.

= Positions =
This tab is where you define the different Positions that you will use in your Readings/Spreads. This is where you link 
Positions to Readings/Spreads.

Click on Add New Position.

**Top Tip:** Once you have all of the Readings defined, define all possible Positions you might use and assign them to the 
specific Readings here.

*Title*
Name of Position - this will display on the Reading. For this reason, use a meaningful name. Doing this will also make it 
easier to set up the Descriptions for each Position later in the configuration.

You will see all of the Readings you have set up displayed under Settings. This allows you to assign the Position you create to multiple Readings at the same time. Tick the boxes for the relevant Readings.

*Order*
Currently, you need to identify a Position in the same order (number) overall Readings if you are using it in multiple Readings. 
e.g. Past, Present, Future Spread, and Success Spread. E.g:

Reading 1 - Past Present Future

Past = Order 1

Present = Order 2\* (as in Reading below)

Future = Order 3

Reading 2 - Success Spread

Major concern or obstacle = Order 1

Present situation = Order 2\* (as in Reading above)

Hidden Factors = Order 3

New Ideas or People that will help = Order 4

What you need to do so succeed = Order 5

You CANNOT use a different Order for the same Position over multiple Readings.

Click Publish to save the Position.

On the Positions index page, you will see which Readings are associated with each Position.

= Cards =
Create the Cards you will use in your Readings here. This is where you link Cards to Readings/Spreads.

Click on Add New Card

*Title*
Add the title of the Card. This will display as the name of the Card on the Reading.

*Text Box*
Below the title, there is a text box. This is used to show definitions for the one Card Daily and Random Readings. If you do not enter any text here, nothing other than the Card will show. If you want the text to appear for these Readings, you must 
enter it here on the original Card record.

*Settings*
Below the text box, the Settings tab displays all of the Readings that you defined in the Readings section of the 
Card Oracle. Click the Readings in which you want to use this Card.

**Front of Card Image**
To the right of the text box, under the normal WordPress post options, you will see an option to Add Card Image. Click this, 
select the image you want to associate with this Card.

Click on Publish to save the Card.

If you want to check which Card is assigned to which Reading if you click on the overall Cards tab in the Card Oracle menu, 
you will see which Cards are associated with each Reading.
You must associate Cards with a Reading in order for their backs to display (and be selected) on the Reading on the front-end of the site. If you do not have the right number of Cards displaying for a particular Reading once you have configured it, 
check here that all the Cards required are associated with the Reading.

**Top Tip:** On the Card index page, you will see the Number of Descriptions associated with each Card. This is a fast way to 
check that all of the Cards have the right number of Descriptions.

= Descriptions =
This is the tab where you create individual Descriptions for a specific Card in a specific Position in a specific Reading. 
The link between Position and Reading has already been made (Positions, above). The link between Card and Reading has already been made (Cards, above). This is where we link all three elements together.

Click on Add New Card Description.

*Title*
Add a title for your Description. Make your Description title obvious in order to be able to identify exactly what it is. 
This title will display on the Reading.

*Text Box*
Add the content you want to display when you display this specific Card in this specific Position of this specific Reading.

*Settings*
Card
Select the Card you want to associate with this Description from the drop-down box. You must associate a Card with a 
Description if you want the Description to display on the front end of the Reading.

Description Position
This displays all of the Positions available to you to associate with this Description. You can associate a single 
Description with multiple Positions.

Click Publish to save the Description.

**Top Tip:** On this main Descriptions index page, you will see a list of Descriptions and the Positions with which you 
have associated them.
