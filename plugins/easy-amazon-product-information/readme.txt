=== Plugin Name ===
Contributors: jensmueller
Donate link: http://jensmueller.one/spenden/
Tags: Amazon Product Advertising API, API, Amazon, Affiliate, Nischenseiten, Button, Nischenseite, EAPI, Easy Amazon Product Information, Information, intext, Google Analytics
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Stable tag: trunk
Requires at least: 4.0
Tested up to: 5.2.3

Mit diesem Plugin können Sie Produktdaten aus der Amazon-API auslesen und automatisiert in Ihre Webseite einbinden.

== Description ==

EAPI ermöglicht es Ihnen schnell und einfach die Produktdaten von Amazon in Ihre bestehende WordPress-Seite zu integrieren.
Easy Amazon Product Information greift dazu auf die Amazon Product Advertising API zurück. Es stehen Ihnen verschiedene Darstellungstypen zur Verfügung. Jeder der Typen kann
von Ihnen so anpasst werden, dass er perfekt auf Ihre Seite passt. Zudem gibt es bereits vordefinierte Darstellungstypen.
Das Plugin ist perfekt für die Verwendung auf einer Nischenseite geeignet.

EAPI ist und wird auch immer kostenlos bleiben.

Viel Spaß und Erfolg mit dem Plugin und Ihrem Blog oder Nischenseite!

(Zögern Sie nicht, mich zu [kontaktieren](http://jensmueller.one/easy-amazon-product-information/), wenn sie eine coole Idee für eine Erweiterung/Ergänzung haben.)

= Funktionen =

*  Vordefinierte Darstellungstypen: standard, small, negative, sidebar, price, button, picture (können angepasst werden.)
*  Intext-Ausgabe der Produktbilder oder Preise
*  Cache der Produktdaten mit regelmäßiger Aktualisiserung
*  Verwendung der Buttons auch für Nicht-Affiliatelinks (interne Links, andere externe Links)
*  Anzeige eines bestimmten Produkts mit Angabe der ASIN
*  Anzeige einer Liste von Produkten mit Angabe der ASINs ',' getrennt
*  Anzeige von Bestsellerlisten
*  Einbindung von Google Analytics zum Tracken von Klicks
*  Anzeige der Produktbewertungen sowie Prime
*  Kombination mit [Easy Integrated Image Gallery](http://jensmueller.one/easy-integrated-image-gallery/)
*  Viele Weitere: [Easy Amazon Product Information](http://jensmueller.one/easy-amazon-product-information/)
*  Anbindung weiterer Shops (z.B.: Ebay): [EAPI PLUS](http://jensmueller.one/eapi-plus/)


= Vorteile = 

*  Kostenlos
*  Individuelle Anpassung der Darstellung
*  SEO freundlich (standardmäßige Verwendung von nofollow, title-tags, alt-tags etc.)
*  Responsive Darstellung
*  Mobile Darstellung
*  Vordefiniertes Design von Amazon-Buttons
*  Optimierungsmöglichkeiten durch Verknüpfung mit Google Analytics
*  Sämtliche Affiliate-Links können mit EAPI erstellt werden


= Informationen aus der API =

*  Amazon-Produkttitel
*  URL
*  Features
*  Preis
*  Bilder
*  Prime-Verfügbarkeit
*  Zudem: Anzahl Bewertungen + Bewertungssterne bei Amazon


= Weitere Anleitungen =
*  [Ausführliche Installationsanleitung](http://jensmueller.one/easy-amazon-product-information/)
*  [Einstellungsmöglichkeiten im Backend](http://jensmueller.one/backend-einstellungen/)
*  [Verwendung der Tags](http://jensmueller.one/eapi-parameter-tags/)
*  [Weitere Beispiele](http://jensmueller.one/beispiele/)


== Installation ==

1. Laden Sie alle Dateien in den Ordner `/wp-content/plugins/easy_amazon_product_information`. Alternativ können Sie sich das Plugin auch über die Plugin-Suche herunterladen.
2. Aktivieren Sie das Plugin.
3. Setzen Sie im Backend den **Access Key ID, Secret Access Key** sowie Ihren **Affiliate-Tag** von Amazon.
4. Sie können das Plugin nutzen.
5. Im WordPress-Backend können Sie unter Einstellungen->Easy Amazon Product Information die Einstellungen nach Ihren Wünschen anpassen.
6. Alle weiteren Einstellungen finden Sie unter: [EAPI - Easy Amazon Product Information](http://jensmueller.one/easy-amazon-product-information/).

== Screenshots ==

1. Darstellung im Backend
2. Darstellungstyp 'standard' im Amazon-Style
3. Darstellungstyp 'negative'
4. Darstellungstyp 'sidebar'
5. Darstellungstyp 'small'
6. Darstellungstyp intext 'price'
7. Darstellung mit inbox-Schatten

== Changelog ==

4.0.1:

*  Fix für Anfrage bei nicht vorhandenem Produkt.

4.0.0:

*  Anpassung für [Amazon PA API 5.0](https://webservices.amazon.com/paapi5/documentation/)

3.3.0:

*  Anpassung an die [DSGVO](http://jensmueller.one/blog/eapi-und-die-dsgvo/)


3.2.0:

*  Bug fix


3.1.0:

*  [EAPI PLUS](http://jensmueller.one/eapi-plus/)
*  Anbindung weiterer Shops, beispielsweise Ebay


3.0.0:

*  Verknüpfung mit [Easy Integrated Image Gallery](https://wordpress.org/plugins/easy-integrated-image-gallery/).| Combination mit EIIG.
*  Behebung zahlreicher Bugs.| Fix small bugs.


2.7.0:

*  Ein Badge kann jetzt bei einem oder mehreren Produkten zur besonderen Hervorhebung verwendet werden.| A special badge can now be displayed.
*  Behebung zahlreicher Bugs.| Fix small bugs.


2.6.0:

*  Es kann nur das Prime-Logo alleine angezeigt werden, beispielsweise in einer Tabelle. | Only the prime sign can now be displayed.
*  Design-Optimierungen. | Design optmiziations.
*  Email-Benachrichtigungsfeature wurde entfernt. | Email notification is no longer available.

2.5.0:

*  Der Algorithmus zur Anzeige der Produktbewertungen wurde minimal angepasst. So können Produktbewertungen wieder zuverlässiger angezeigt werden. | Fixed a bug with the product ratings.

2.4.0:

*  Ein Bug wurde behoben, der teilweise zu Fehlermeldungen beim Update geführt hat. | A bug was fixed.

2.3.0:

*  Einige Bugs wurden behoben. | Some bugs are fixed.
*  Neue Darstellungsmöglichkeit 'stars' und 'reviews'. | New display options 'stars' and 'reviews'.


2.2.0:

*  Einige Bugs wurden behoben. | Some bugs are fixed.
*  Datenbank-Performance wurde optimiert. | Database performance optimized.
*  Bilder werden via https geladen. | Pictures are now loaded via https.


2.1.0:

*  Überschreibung der Feature-Liste aus Amazon mit eigenen "Texten". | Overwrite the feature list.
*  Es kann per Parameter festgelegt werden, welches Bild angezeigt werden soll. | It can be set with a parameter which should be displayed.
*  Performance bei der Übergabe einer ASIN-Liste als Suchbegriff wurde optimiert. | Performance with multiple ASIN-request is optmized.
*  Einige CSS-Fehler wurden behoben. | Fixed some CSS Bugs.
*  Es kann ein Schatten in den Produkt-Boxen angezeigt werden. | A shadow can be displayed in the products.


2.0.0:

*  Zahlreiche Bugs wurden behoben. | Fixed a lot of bugs.
*  Verfügbarkeitsalarm
*  Prime-Logo kann jetzt angezeigt werden. | Prime logo can now be displayed.
*  Produktbewertungen (Anzahl und Sterne) können jetzt angezeigt werden. | product reviews (number and stars) can now be displayed.
*  Vieles mehr | A lot more: [EAPI 2.0](http://jensmueller.one/blog/eapi-version-2-0-mit-zahlreichen-neuen-funktionen-und-verbesserungen/)


1.2.0:

*  Bessere Möglichkeiten zur Fehlersuche. [EAPI Problemsuche](http://jensmueller.one/eapi-fehlersuche/) | More options to find mistakes.
*  Der Button kann jetzt direkt im Amazon-Style angezeigt werden.| Button can now be shown in Amazon-Style.


1.1.0:

*  Ein Bug beim API-Zugriff wurde behoben. | Fixed a bug in the API-file


1.0.8:

*  Einführung der Funktion [Analytics Tracking](http://jensmueller.one/blog/analytics-tracking/) | Release of a new function.
*  Auflösung von Bildern kann jetzt unabhängig von der Darstellungsgröße ausgewählt werden. | You can now choose the resolution independently from the displayed size.
*  Neuer Darstellungstyp "link" verfügbar. Erstellt bloß einen Link. | New type available.


1.0.7:

*  Der Preis kann jetzt als Option komplett ausgeblendet werden (rechtliche Gründe). Standardmäßig wird er aber bei allen Typen angezeigt.| The price can now optionally be displayed. Default is the price displayed on every type.

1.0.6:

*  Es ist nun möglich den Preis innerhalb des Buttons zusätzlich/optional anzuzeigen. | It is now possible to display the price of a product additionally/optionally in the button text.

1.0.5:

*  Ein paar Bugs wurden behoben. | Fixed some bugs.
*  Datenbank-Einträge werden jetzt als json-Objekte gespeichert. Spart Platz in der Datenbank und ist viel schneller. | Database-entries are saved as JSON-objects. Is much faster and better for the whole database.
*  Auswahl zwischen Bild small/medium/large im Backend und in den Parameter-Tags. | User can select the picture-size. Can be changed in the backend and in the tags-parameter.
*  Die maximale Höhe der Features kann jetzt selbst festgelegt werden. 200px ist hier Standardwert. | The maximum height of the displayed features can be defined by the user.
*  Die Ausrichtung (float) der Bilder kann jetzt beim typ=picture selbst festgelegt werden. "links" ist hier Standardwertwert. | The float of the typ picture can be changed with the parameters.

1.0.4:

*  Ein paar Bugs wurden behoben. | Fixed some bugs.
*  Vor/Nach dem Preis kann jetzt zusätzlich ein Text angezeigt werden. | You can now display a text before and after the price
*  Vor dem Streichpreis kann auch ein Text angezeigt werden. | You can now display a text before the crossed out price.
*  Das Plugin arbeitet jetzt mit Sprachdateien. Derzeit gibt es de_DE und en_GB. | The plugin uses now language-files and can be used in de_DE and en_GB.
	
1.0.3:	

*  Erste offizielle Version, die zum Download bereit steht. | First official version to download.

