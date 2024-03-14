=== Cab Grid ===
Contributors: nimbusdigital
Tags: taxi fare calculator,price calculator,taxi,taximap,cab,minicab,mini-cab,cab grid,fare,price,calculator,journey,travel,transport,uber,estimate,calculate,booking,system,quote,grid,pricing,table,bus,coach,train,car,limo,limousine,transfer,airport,taxi price calculator,taxi booking,plane,jet,airplane,aeroplane,transfers,car hire,shuttle
Requires at least: 3.0
Version: 1.6.9
Tested up to: 6.4.3
Stable tag: trunk
License: GP2

Easily add a taxi fare price calculator to your website via shortcode [cabGrid] or widget. Simply enter journey prices in a table.

== Description ==
(Disponible en Español | Disponível em Português | Disponible en Français | Disponibile in Italiano | In Deutsch verfügbar | Verkrijgbaar in het Nederlands)

Cab Grid is a simple fare price calculator for your Wordpress website (taxi/bus/limo/train/plane/coach). It provides a simple interface for visitors to get fare prices for simple point to point journeys. It can be shown anywhere on your site via shortcode [cabGrid] or widget:

First define areas, then enter prices for journeys between each area. Prices are entered in a simple pricing table.

### Deutsch
Cab Grid ist ein einfacher Fahrpreis-Kalkulator für Ihre WordPress Website (Taxi / Bus / Limousine / Zug / Flugzeug / Bus). Es bietet eine einfache Oberfläche für Besucher um Kosten für einfache von A-nach—B-Fahrten zu bekommen. Es kann überall auf Ihrer Website über den Shortcode [cabGrid] oder Widget gefunden werden.
Zuerst definieren Sie Bereiche, dann geben Sie Preise für Fahrten zwischen jedem Bereich ein. Die Preise sind in einer einfachen Preistabelle angegeben.

### Español
Cab Grid es un sencillo calculador de tarifas para su Wordpress (taxi/autob�s/limusina/tren/avi�n/entrenador). Dispone de un sencillo interface para que sus visitantes obtengan tarifas para un sencillo trayecto punto a punto. Se puede mostrar en cualquier lugar de su website mediante un shortcode [cabGrid] o un widget.
En primer lugar, defina areas y luego introduzca precios para trayectos entre cada �rea. Los precios se introducen en una sencilla tabla de precios.

### Français
Cab Grid est un calculateur de tarif simple pour votre site Wordpress (taxi/bus/limousine/train/avion /autocar). Il propose une interface facile d'utilisation où les visiteurs peuvent obtenir des tarifs pour les trajets d'un point d'arrivée à un point de départ. Vous pouvez l'afficher n'importe où sur votre site via un code [cabGrid] ou un widget.
Définissez d'abord les zones, puis entrez les tarifs pour les trajets entre chaque zone. Les tarifs sont inscrits dans un tableau tarificaire simple.

### Italiano
Semplice calcolatore di prezzi per taxi da punto A a punto B.

### Nederlands
Cab Grid is een eenvoudige prijs calculator voor ritprijzen voor uw Wordpress website (taxi / bus / limousine / trein / vliegtuig / auto). Het biedt een eenvoudige interface voor bezoekers om ritprijzen te ontvangen voor eenvoudige punt naar punt ritten. Het kan overal op uw site worden weergegeven via een verkorte code [cabGrid] of widget.
Bepaal eerst de gebieden, voer dan de prijzen voor het reizen tussen elk gebied in. Prijzen worden ingevoerd in een eenvoudige prijzentabel.

### Features:

* Customisable currency (set your own symbol $/£/€/¥/etc.)
* Customisable CSS (styling)
* Customisable booking message
* Can be displayed on your site via a shortcode: [cabGrid] or as a widget in your sidebar
* [Translation ready](https://cabgrid.com/help-and-support/translations/cab-grid-translations/)

### More info...
* [Demo](https://cabgrid.com/#cabGridDemo)
* [Support](https://cabgrid.com/support/)
* [Install Video](https://youtu.be/--QvY467ecM)

== Requirements ==
No external requirements


== Installation ==
Copy the plugin folder to your Wordpress plugins directory - typically wp-install-directory/wp-content/plugins/

### Activate the plugin:
Log in to the Wordpress admin, go to Plugins > Installed Plugins.
Find the Cab Grid item and click *Activate*

### Configure your prices:
1. In the admin menu, click Cab Grid Settings. 
2. Enter the areas (place names) you would like to cover (journey between). 
3. Save. 
4. Click the PRICES tab. 
5. Enter prices for journeys between two places in the corresponding grid cell (see note below). 
6. Save. 
7. (Optional) Click the OPTIONS tab and enter a currency symbol (ie $, £ or €) in the currency field. 
8. (Optional) Also under OPTIONS tab, define any custom CSS in the STYLING text area. 
9. (Optional) Also under OPTIONS tab, enter a message to be shown with the price in the MESSAGE text area. (This could include details on how to book or the maximum number of passengers.) 
10. Save. 

*Note: Apart from journeys remaining within a single area, each two places can have both FROM and TO prices. These may often be the same, but you are able to specify different prices if the need arises.  i.e. The price for a journey starting from place1 going to place2 might be different to the price starting from place2 to place1.


### Add shortcode to a page:
Create (or edit) a page (or post) on your Wordpress site where you want your Cab Grid Price Calculator to appear.
Enter the shortcode: [cabGrid] at the point on the page where you want the calculator to be displayed.


### Add the Cab Grid widget to your sidebar:
From the Wordpress admin page menu, select Widgets from the Appearance section.
Under 'Available Widgets' look for the item named Cab Grid and drag it to you preferred widget area (on the right)
Expand the new widget and enter a title (arbitrary text of your discerning) and the height you wish the widget to have on your sidebar/widget area.

For more details and screenshots, see [cabgrid.com](http://cabgrid.com)

== Frequently Asked Questions ==

= Where can I get installation help? =

<http://cabgrid.com>

= Can I translate labels and text? =

Yes. Translations are stored in the 'languages' folder.
For instructions, see <https://cabgrid.com/help-and-support/translations/cab-grid-translations/>



== Screenshots ==
1. Admin screen: AREAS tab where areas/places are entered (1-cab-grid-places)
2. Admin screen: PRICES tab - enter journey prices (2-cab-grid-price-table)
3. Admin screen: OPTIONS tab - configure currency, CSS & custom message (3-cab-grid-options)
4. Initial appearance on your website (4-cab-grid-shorcode-plugin-display)
5. Price display after pick up and drop off locations selected (5-cab-grid-shorcode-plugin-display-price)



== Changelog ==
1.6.9	WP 6.4.3 compatibility
1.6.8	WP 6.4.2 compatibility
1.6.7	WP 6.4.1 compatibility
1.6.6	WP 6.4 compatibility
1.6.5	WP 6.3.2 compatibility
1.6.4	WP 6.3.1 compatibility
1.6.3	WP 6.3 compatibility
1.6.2	WP 6.2.2 compatibility
1.6.1	WP 6.2.1 compatibility
1.6		Admin styling updates
		Option to position currency symbol after price
		Message can contain new lines (but no HTML)
		XSS Prevention improvements
		WP 6.2 compatibility
1.5.15	WP 6.1.1 compatibility
1.5.14	WP 6.1 compatibility
1.5.13	WP 6.0.2 compatibility
1.5.12	WP 6.0.1 compatibility
1.5.11	WP 6.0 compatibility
1.5.10	WP 5.9.3 compatibility
1.5.9	WP 5.9.2 compatibility
1.5.8	WP 5.9 compatibility
1.5.7	WP 5.8.1 compatibility
1.5.6	Admin styling updates
		WP 5.8 compatibility
1.5.5	WP 5.7.2 compatibility
1.5.4	WP 5.7 compatibility
1.5.3	WP 5.6.2 compatibility
1.5.2	WP 5.6 compatibility
1.5.1	Further admin layout improvements (only show price cells for populated places, currency symbol placement)
1.5		Increased areas to 15
		Admin table layout improvements
		Prevent currency symbol showing when no price available
		Added markup to price display to enable better manipulation in CSS (e.g. moving currency symbol via flexbox)
		Bug fix: Destination drop-down element appearing below subsequent elements. 
		WP 5.5.3 compatibility
1.4.11	Bug fix: destination dropdown obscured by price
		WP 5.5.1 compatibility
1.4.10	WP 5.5 compatibility
1.4.9	Bug fixes
1.4.8	WP 5.4.2 compatibility
1.4.7	WP 5.4.1 compatibility
1.4.6	animate steps
		WP 5.4 compatibility
		improved destination label
		detect cache visitors
1.4.6	WP 5.3 compatibility
1.4.5	WP 5.2.4 compatibility
1.4.4	Spelling corrections :-|
1.4.3	Cab Grid Pro compatibility check can check for incompatible plugins
1.4.2	Cab Grid Pro compatibility check update
1.4.1	Gutenberg block bug fix
1.4		Added Cab Grid Gutenberg Block
1.3.14	Admin improvements
1.3.13	Bug fix: destination drop down not activating
1.3.12	Hide empty destinations
1.3.11	More precise default style selectors
		Drop-down labelling on mobile
1.3.10	Bug fix: Firefox loading
1.3.9	Load javascript in footer for speed gains
		Styling updates
1.3.8	WP 5.1.1 Compatibility
		Custom CSS to head
		Merged Chosen JS/CSS to main JS/CSS
1.3.7	WP 4.9.8 Compatibility
		CSS updates
		Hire us! tab
		Prep for Cab Grid Pro v5.0
1.3.6	Admin role update
		Shortcode render buffer adjustment for better compatibility with some themes
1.3.5	WP 4.9.4 compatibility
1.3.4	WP 4.9.3 compatibility
1.3.3	WP 4.9.1 compatibility
1.3.2	WP 4.9 compatibility
1.3.1	Fix 'deprecated constructor' error on Widget
		WP 4.8.3 compatibility
1.3		Pro compatibility tests
		WP 4.8.2 compatibility
1.2.10	Basic AMP support (requires https://en-gb.wordpress.org/plugins/amp/) (https://support.google.com/webmasters/answer/6340290)
1.2.9	Error message if no places added
1.2.8	WP 4.8.1 compatibility
1.2.7	Bug fix: errors if no prices entered
		Bug fix: widget instance count
1.2.6	WP 4.8 compatibility
1.2.5	WP 4.7.5 compatibility
1.2.4	CSS updates
		WP 4.7.4 compatibility
1.2.3	Translation updates
1.2.2	Price number format obeys locale
		Translations: Dutch/German/Spanish/French/Italian
1.2.1	Further drop-down fixes
		Updated CSS resets for some pseudo selectors (:before/:after)
1.2		Bug fixes (AJAX, destination drop-down not enabled when pickup selected)
		Rudimentary debugging
1.1.1	Readme updates
1.1		Tested in WP 4.7
		Cab Grid Pro upgrade integration (with auto-discount of up to 50%)
1.0.9	Better mobile layout
1.0.8	Insure all PHP opens with <?php  for greater compatibility
1.0.7.2	adding translations (Spanish/French/Portuguese/Italian)
1.0.7.1	Fixed missing admin icon	
1.0.7	Changed languages folder from 'lang' to more common 'languages'
		Updated translatable strings
		Updated readme syntax
		CSS reset to combat theme styling overrides
		Spanish translation
		Made JS strings translatable
1.0.6	Upgrade link on plugins page
		Readme overview update
		translations...
		admin icon update
1.0.5	Compatibility update
1.0.4	Langage translations
		'Powered by' option
		Admin scripts
		Hide __constructor() error
1.0.3	Minor updates and fixes
1.0.2	Compatibility alongside PRO version
1.0.1	Corrections
1.0 	Initial version.