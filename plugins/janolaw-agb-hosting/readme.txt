=== Janolaw AGB Hosting ===
Tags: agb, Impressum, Datenschutzerklärung, Widerrufsbelehrung, Rechtstexte, disclaimer, imprint, legal documents, privacy, revocation, datasecurity, model withdrawal, general terms and conditions, shop, blog, website, janolaw
Requires at least: 3.0
Tested up to: 6.4.3
Stable tag: 4.4.7

This plugin gets legal documents provided by janolaw AG (commercial service) like General terms, Imprint etc. for Webshops and Pages. (German Service only)

== Description ==

English description

General terms and conditions, cancellation policy, model withdrawal form or privacy policy and the legal disclosure belong to the legal basic equipment of an online shop or web page. New developments of the legal situation driven by case law and new statutory law have the effect that these documents do not reflect the actual legal requirements. This may encourage your competitors to legally challenge your online shop or Internet presence.
Therefore, all shop and website operators must keep up their legal knowledge with the current development with respect to the case law and the statutory law, or accept the risk vested to invalid legal documents. Or you entrust this task to janolaw – and focus on your business solely.
With the janolaw Plugin for WordPress your shop and website is provided with the valid legal documents with automatic updates, and you are protected from all liabilities in connection with these documents. 
janolaw AGB Hosting-Service for online traders and janolaw Rechtstext-Service for website owners enables to create taylor-made german legal documents (option for translation to English and French) and to integrate them with the extension into the CMS of WordPress
The module implements an interface between WordPress and the janolaw-Service. After the implementation of the extension the legal texts are imported on the respective websites. The documents are updated automatically and always according thereby to the current law. 

These documents are part of the janolaw Rechtstext-Service and janolaw AGB Hosting-Service package and will be updated regularly:

* Imprint* Data privacy policy* General Terms and Conditions* Instructions on withdrawal* Model withdrawal form
For more Informations visit: 

* <a href="http://www.janolaw.de/internetrecht/agb/agb-hosting-service/" target="_blank">AGB-Service for Webshops</a>
* <a href="https://www.janolaw.de/internetrecht/firmen-webseiten/datenschutzerklaerung_impressum.html#menu" target="_blank">Websites & Blogs</a>
* <a href="http://www.janolaw.de/agb-service/einbindung-wordpress.html#menu" target="_blank">Manual for Wordpress integration</a>

The service provide german, english and french documents!


German description

AGB, Widerrufsbelehrung, Muster-Widerrufsformular bzw. Datenschutzerklärung und Impressum gehören zur rechtlichen Grundausstattung eines Onlineshops bzw. Webseite. Doch ständig neue Gerichtsurteile und Gesetzesänderungen sorgen dafür, dass diese Dokumente schnell wieder veralten. Dies wiederum kann Mitbewerber ermuntern Ihren Online-Shop bzw. Internetpräsenz abzumahnen.
Als Shopbetreiber bzw. Inhaber einer Webseite müssen Sie sich somit ständig über die neusten Entwicklungen der Gesetzgebung und Rechtsprechung auf dem Laufenden halten – oder mit dem Risiko veralteter Dokumente leben. Sie können die Pflege und das Risiko für Ihre Dokumente aber auch an das Rechtsportal janolaw „outsourcen“ – und sich in Ruhe Ihrem Business widmen.
Mit dem janolaw Plugin für WordPress sind Sie durch aktuelle Dokumente, automatische Updates und Abmahnkostenhaftung dauerhaft auf der sicheren Seite – und gewinnen zusätzlich Zeit für Ihr Kerngeschäft. 
Mit dem janolaw AGB Hosting-Service für Online-Händler und dem janolaw Rechtstext-Service für Webseiten Besitzer ist es möglich die rechtlich erforderlichen deutschen Dokumente selbst individuell zu erstellen und in das CMS von WordPress einzubinden.
Das Modul implementiert eine Schnittstelle zwischen Wordpress und dem janolaw Service. Nach der Implementierung der Erweiterung werden die rechtlichen Texte auf den jeweiligen Webseiten eingespielt. Die Dokumente werden automatisch aktualisiert und entsprechen dadurch immer dem aktuellen Recht.

Diese Dokumente sind Teil des janolaw Rechtstext bzw. AGB Hosting-Service Pakets und werden regelmäßig aktualisiert:

* Impressum* Datenschutzerklärung* AGB* Widerrufsbelehrung* Muster-Widerrufsformular

Für weitere Informationen besuchen Sie bitte: 

* <a href="http://www.janolaw.de/internetrecht/agb/agb-hosting-service/" target="_blank">AGB-Service für Webshops</a>
* <a href="https://www.janolaw.de/internetrecht/firmen-webseiten/datenschutzerklaerung_impressum.html#menu" target="_blank">Webseiten & Blogs</a>
* <a href="http://www.janolaw.de/agb-service/einbindung-wordpress.html#menu" target="_blank">Anleitung Wordpress Integration</a>

Dieser Service bietet die Dokumente in deutsch, englisch und französisch an!

== Installation ==

1. Use the installer from Wordpress backend or upload the folder `janolaw-agb-hosting` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Enter your personal IDs provided by janolaw AG at `Settings -> janolaw AGB Service` (UserID / ShopID)
4. Enter a path writeable for the Webserver to cache documents if not auto detected (should be by default: /tmp on most linux/unix systems)
5. Use the following tags at the desired pages [janolaw_agb], [janolaw_impressum], [janolaw_widerrufsbelehrung], [janolaw_datenschutzerklaerung],  [janolaw_widerrufsformular]
6. Done !

Opional: if you change the language of the documents, it may be necessary to rename the title tags of the desired pages.

== Frequently Asked Questions ==

= The documents dosn't get updated! What can i do?

The document refresh interval is hardcoded to 12 hours to prevent to much server stress on the document server. If you cwant to refresh them manually because, e.g. you made changes, please tick the checkbox "Clear Cache" at the settng page and save the settings.
The document cache get cleared and all documents will be refreshed directly from the server as soon as you click on of the desired pages.

= What if i have another question? =

Please contact janolaw for support at support@janolaw.de

= Howto style the documents?

Use this CSS !

	#janolaw-body ol li {
		list-style: upper-roman;
		margin-left: 40px;
	}
	#janolaw-paragraph {
		color: #555;
		font-size: 14px;
		font-weight: bold;
		margin: 10px 0 10px;
		padding: 0 0 5px;
	}
	#janolaw-absatz {

	}
	.janolaw-text {
		font-size: 12px;
		margin-left: 40px;
	}

== Screenshots ==

1. Janolaw Settings

== Changelog ==
= 4.4.7 =
* compatibility with Wordpress 6.4.3

= 4.4.6 =
* compatibility with Wordpress 6.4.2

= 4.4.5 =
* compatibility with Wordpress 6.4.1
* fix for multilanguage documents

= 4.4.4 =
* compatibility with Wordpress 6.3.1
* some minor bugfixes

= 4.4.2 =
* compatibility with Wordpress 6.2

= 4.4.1 =
* compatibility with Wordpress 6.1.1

= 4.4 =
* compatibility with Wordpress 6.x
* compatibility with PHP 8.x
* fixed a bug with attachments for Woocommerce

= 4.3.9 =
* some fixes for PDF documents download via HTTPS

= 4.3.7 =
* some typo fixes
* check for compatibility WP 5.8.2
* added referral program

= 4.3.6 =
* some fixes regarding the API

= 4.3.0 =
* updated Wordpress compatibility to 5.7
* link to janolaw API
* documentation provided via API

= 4.2.10 =

* fixed WooCommerce attachments

= 4.2.9 =

* updated Wordpress compatibility to 5.4
* tested for "Elementor Page Builder"

= 4.2.7 =

* updated Wordpress compatibility to 5.3.2

= 4.2.6 =

* updated Wordpress compatibility to 5.3.1

= 4.2.5 =

* updated Wordpress compatibility to 5.2.3
* fixed a bug for PDF attachments to woocommerce mails

= 4.2.4 =

* updated FAQ

= 4.2.3 =

* updated Wordpress compatibility

= 4.2.2 =

* fixed PHP notice if browser language is not set

= 4.2 =

* tested up to Wordpress 5.0.3
* implemented allow_url_fopen problem fix by using cURL if applicable

= 4.1.1 =

* fix PDF Links

= 4.1 =

* hardcoded language tags
* updated documentation
* fix of initial default language on new installations
* minor bug fixing of warning messages when debug flag in WP config is set to true

= 4.0 =

* added woocommerce mailattachments for order confirmation
* fix: if for any reason no language can be detected or is missing in configuration, 'de' is selected as default
* minor fixes

= 3.7.1 =

* fix for deprecated function parameters - thanks to "joe1860"

= 3.7 =

* checked and updated Wordpress Version up to 4.9.4
* fixes for content replacement
* added new setting - set default language: Set default language for pages, if no matching language could be found with 'auto' method.
* added support for page builder plugins like: WPBackery, Beaver Builder
* added support for multiple documents on one page, e.g. model withdrawal form below the cancellation policy

= 3.6.2 =

* checked and updated Wordpress Version up to 4.7.3

= 3.6.1 =

* added more comprehensive description
* changed versioning: <major>.<minor>.<maintnance>

= 3.6 =

* translation fixes

= 3.5 =

* some minor bugfixes
* finished translation integration
* added description links to service provider janolaw

= 3.4 =

* added enhanced visual style for plugin repository of wordpress
* fixed document retrival for hosts which doesn't allow the usage of some security related php file functions (e.g. fopen)
* added preprations for translations

= 3.3 =

* fixed cache path check if writable
* enhanced cache path automatic detection, if default (/tmp) is not writable, it checks for plugin path and use this path instead

= 3.2 =

* enhanced content creation, include your own content before page-tags ([janolaw_..])
* fixed serviceversion detection

= 3.1 =

* enhanced content creation, include your own content below page-tags ([janolaw_..])

= 3.0 =
* added support for Wordpress 4.3
* added widerrufsbelehrung Form
* added clear cache button
* added PDF download of documents if PDF provided by janolaw
* added service availability check
* added language selection for documents at backend
* added language selection by browser
* service update notification 
* fixed permalink generation
* enhanced plugin messages for info, notice and errors

= 2.2 =
* added multilanguage files for English + German

= 2.1 =
* fixed privacy page

= 2.0 =
* tmp folder predefined default
* checkboxes for automatic page creation to makes it even simpler for users to install

= 1.0 =
* Initial Version

