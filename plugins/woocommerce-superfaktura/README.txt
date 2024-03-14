=== SuperFaktura WooCommerce ===
Contributors: webikon, johnnypea, savione, kravco, superfaktura, martinkrcho
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZQDNE7TP3XT36
Tags: superfaktura, invoice, faktura, proforma, woocommerce
Requires at least: 4.4
Tested up to: 6.4.1
Stable tag: 1.40.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Connect your WooCommerce eShop with online invoicing system SuperFaktura.

== Description ==

SuperFaktura extension for WooCommerce enables you to create invoices using third-party online app SuperFaktura.

SuperFaktura is an online invoicing system for small business owners available in Slovakia ([superfaktura.sk](http://www.superfaktura.sk/)) and Czech Republic ([superfaktura.cz](http://www.superfaktura.cz/)).

For more information about the plugin and its settings check the articles on SuperFaktura blog:
[SuperFaktúra a WooCommerce: Diel 1. – Inštalácia a autorizácia](https://www.superfaktura.sk/blog/superfaktura-a-woocommerce-diel-1-instalacia-a-autorizacia/)
[SuperFaktura a WooCommerce: Díl 1. – Instalace a autorizace](https://www.superfaktura.cz/blog/superfaktura-a-woocommerce-dil-1-instalace-a-autorizace/)

Main features of SuperFaktura WooCommerce include:

* Automatically create invoices in SuperFaktura.
* Add fields for invoice details to WooCommerce Checkout form.
* Link to the invoice is added to
	* Customer notification email sent by WooCommerce
	* Order detail
	* WooCommerce My Account page
* Set your own rules, when proforma or real invoice should be generated. Want to send proforma invoice on order creation and real invoice after payment? We got that covered.
* Custom invoice numbering.

This plugin is not directly associated with superfaktura.sk, s.r.o. or with superfaktura cz, s.r.o. or oficially supported by their developers.

Created by [Ján Bočínec](http://bocinec.sk/) with the support of [Slovak WordPress community](http://wp.sk/) and [WordPress agency Webikon](http://www.webikon.sk/).

For priority support and more Woocommerce extensions (payment gateways, invoicing…) check [PlatobneBrany.sk](http://platobnebrany.sk/)

== Installation ==

1. Upload the entire SuperFaktura folder *woocommerce-superfaktura* to the /wp-content/plugins/ directory (or use WordPress native installer in Plugins -> Add New Plugin). And activate the plugin through the 'Plugins' menu in WordPress.
2. Visit your SuperFaktura account and get an API key
3. Set your SuperFaktura Account Email and API key in *WooCommerce -> Settings -> SuperFaktura*

== Screenshots ==
Coming soon.

== Frequently Asked Questions ==

= Invoice is not created automatically =

Check the settings in *WooCommerce -> Settings -> SuperFaktura*
You should fill your Account Email, API key and set the Order status in which you would like to create the invoice.

= Invoice is marked as paid =

Status of the payment is related to Order status. When an invoice is created with the status “On-Hold”, it will not be marked as paid. When an invoice is created with the status “Completed”, it will be marked as paid.

= The plugin stopped working and I don’t know why! =

This usually happens when you change your login email address. The email address in *WooCommerce -> Settings -> SuperFaktura* must be the same as the one you use to log in to SuperFaktura.

= Where can I find more information about SuperFaktura API? =

You can read more about SuperFaktura API integration at [superfaktura.sk/api](http://www.superfaktura.sk/api/)

== Changelog ==

= 1.40.6 =
* Opravená chyba vo výpočte výšky zľavy pri prenesení daňovej povinnosti

= 1.40.5 =
* Pridaná kompatibilita s pluginom YITH WooCommerce Product Add-ons & Extra Options plugin

= 1.40.4 =
* Opravená bezpečnostná chyba v kontrole existencie dokumentu v SuperFaktúre

= 1.40.3 =
* Upravená experimentálna funkcia pre zabránenie duplicite dokumentov spôsobenej súbežnými spätnými volaniami

= 1.40.2 =
* Upravená validácia IČ DPH v objednávke

= 1.40.1 =
* Doplnené logovanie volaní automatického párovania v záložke API log

= 1.40.0 =
* Pridaná experimentálna funkcia pre opakovanie zlyhaného API volania pre vytvorenie dokumentu po 5, 30 a 60 minútach. Ak zlyhajú aj všetky opakované pokusy, plugin zobrazí administrátorovi upozornenie a doplní informácie do poznámok k objednávke.

= 1.32.0 =
* Pridaná experimentálna funkcia pre zabránenie duplicite dokumentov spôsobenej súbežnými spätnými volaniami a/alebo návratovými adresami URL z niektorých platobných pluginov (napríklad GoPay)

= 1.31.4 =
* Pridaná kompatibilita s pluginom WooCommerce EU/UK VAT Manager for WooCommerce

= 1.31.3 =
* Opravená inicializácia povolených HTML tagov

= 1.31.2 =
* Doplnená kontrola existencie objednávky pri dopĺňaní údajov do emailov

= 1.31.1 =
* Pridaná možnosť skryť upozornenie o nesekvenčnom [ORDER_NUMBER] v číslovaní dokumentov navždy

= 1.31.0 =
* Doplnená možnosť validácie IČ DPH v objednávke

= 1.30.5 =
* Opravená chyba v hľadaní objednávok podľa metadát pri aktívnom HPOS

= 1.30.4 =
* Opravená chyba v spracovaní neúspešných API volaní

= 1.30.3 =
* Opravená chyba pri otvorení objednávky v administrácii v starších verziách WooCommerce

= 1.30.2 =
* Doplnené preklady v nastaveniach pluginu
* Pridaná možnosť VŠETKY do nastavení faktúry podľa krajiny zákazníka

= 1.30.1 =
* Opravená chyba pri editácii údajov zákazníka v objednávke

= 1.30.0 =
* Kompatibilita s HPOS

= 1.20.7 =
* Upravené posielania parametra vat_transfer pri pregenerovaní dokumentov

= 1.20.6 =
* Doplnený filter sf_invoice_language pre zmenu jazyka, v ktorom bude faktúra vystavená

= 1.20.5 =
* Doplnený filter sf_attr_separator pre zmenu oddeľovača jednotlivých atribútov produktu

= 1.20.4 =
* Doplnená kontrola existencie produktu

= 1.20.3 =
* Opravená detekcia existencie faktúry

= 1.20.2 =
* Opravená detekcia dopravy pre virtuálne produkty

= 1.20.1 =
* Opravené spracovanie chýb HTTP requestov natívnymi WP funkciami

= 1.20.0 =
* Odstránenie Requests knižnice z pluginu a nahradenie natívnymi WP funkciami

= 1.19.5 =
* Pridané prenášanie telefónneho čísla pre doručenie do SuperFaktúry

= 1.19.4 =
* Pridanie kontroly typu dokladu vytvoreného v SuperFaktúre pri automatickom párovaní úhrady zálohovej faktúry. Ak ide o daňový doklad k prijatej platbe a nie faktúru, doklad sa k objednávke vo WooCommerce nepriradí.

= 1.19.3 =
* Upravená kontrola API prihlasovacích údajov

= 1.19.2 =
* Opravená chyba Unsupported operand types vo výpočte výšky zľavy produktu vo výpredaji

= 1.19.1 =
* Doplnená kontrola daňovej sadzby pre dopravu zadarmo

= 1.19.0 =
* Doplnená kontrola existencie dokumentu v SuperFaktúre a možnosť vygenerovania nového

= 1.18.3 =
* Doplnenie druhého možného scenára prepojenia dokumentov v SuperFaktúre do asynchrónnej kontroly existencie faktúry vytvorenej v SuperFaktúre pri automatickom párovaní úhrady zálohovej faktúry k súvisiacej objednávke

= 1.18.2 =
* Zmenené generovanie pdf do prílohy emailu

= 1.18.1 =
* Pridaná kontrola načítania pdf pred pridaním do prílohy emailu

= 1.18.0 =
* Asynchrónna kontrola existencie faktúry vytvorenej v SuperFaktúre pri automatickom párovaní úhrady zálohovej faktúry k súvisiacej objednávke

= 1.17.0 =
* Pridaná možnosť editovať IČO, DIČ a IČ DPH v profile užívateľa

= 1.16.0 =
* Priradenie ostrej faktúry vytvorenej v SuperFaktúre pri automatickom párovaní úhrady zálohovej faktúry k súvisiacej objednávke

= 1.15.6 =
* Upravený blok s odkazom na faktúru alebo zálohovú faktúru na stránke s potvrdením objednávky

= 1.15.5 =
* Načítavanie spôsobov dopravy zo SuperFaktúry namiesto pevne definovaných možnosťí

= 1.15.4 =
* Opravená chyba v generovaní dobropisov pre refundované objednávky

= 1.15.3 =
* Opravená chyba v generovaní faktúr s nulovou hodnotou
* Pridaná možnosť použiť vo faktúre variabilný symbol zo zálohovej faktúry

= 1.15.2 =
* Pridaná možnosť generovať dobropisy automaticky pomocou filtra sf_generate_invoice

= 1.15.1 =
* Pridané nastavenie pre vynechanie produktov zadarmo na faktúre

= 1.15.0 =
* Pridaná kompatibilita s pluginom WooCommerce EU/UK VAT Compliance (Premium)
* Upravené nastavenia pluginu pre pridanie možnosti fakturácie objednávky na firmu

= 1.14.2 =
* Doplnené preklady do slovenčiny a češtiny

= 1.14.1 =
* Opravený problém v chybovej hláške

= 1.14.0 =
* Odstránenie problému s trademarkom WooCommerce
* Text domain pluginu musel byť zmenený na woocommerce-superfaktura

= 1.13.7 =
* Doplnená konverzia jazykov podporovaných SuperFaktúrou pre WPML

= 1.13.6 =
* Pridaný API parameter vat_transfer

= 1.13.5 =
* Pridané nastavenie pre povinné IČ DPH pri objednávke

= 1.13.4 =
* Pridané nastavenie pre zaokrúhlenie na doklad objednávok na dobierku (na 5 centov pri EUR a na celé číslo pri CZK)

= 1.13.3 =
* Opravený problém s poznámkou o prenesení daňovej povinnosti a príznakom OSS pri doplnení IČ DPH filtrom sf_client_data

= 1.13.2 =
* Opravený problém pri pregenerovaní faktúry s veľkým počtom položiek

= 1.13.1 =
* Pridané oznamy v administrácii pri vytváraní a pregenerovaní dokumentov

= 1.13.0 =
* Pridaná možnosť editovať IČO, DIČ a IČ DPH v administrácii objednávky

= 1.12.1 =
* Opravená chyba pri nesprávnom formáte produktu

= 1.12.0 =
* Skontrolovaná kompatibilita s WordPress 6.0

= 1.11.9 =
* Pridaný filter sf_can_regenerate
* Aktualizovaný SF API klient

= 1.11.8 =
* Opravená kompatibilita s pluginom WooCommerce EU VAT Number

= 1.11.7 =
* Načítavanie spôsobov platieb zo SuperFaktúry namiesto pevne definovaných možnosťí

= 1.11.6 =
* Pridaná možnosť zapnúť poznámku o úhrade zálohovou faktúrou

= 1.11.5 =
* Pridané spôsoby platby zo SuperFaktúry

= 1.11.4 =
* Kompatibilita s WooCommerce 6.0.0

= 1.11.3 =
* Doplnené jazyky faktúry

= 1.11.2 =
* Pridaná kompatibilita s pluginom WooCommerce Gift Cards

= 1.11.1 =
* Pridaná možnosť zrušiť filtrom sf_item_data položku z faktúry vrátením null/false

= 1.11.0 =
* Pridané ID odberného miesta Zásielkovne a váhy objednávky k údajom posielaným do SuperFaktúry (pre Export pre kuriéra)
* Opravené pregenerovanie faktúry v prípade ak bola faktúra predtým vymazaná

= 1.10.12 =
* Pridaná možnosť nastaviť spôsob zaokrúhľovania DPH

= 1.10.11 =
* Pridaný filter sf_generate_invoice

= 1.10.10 =
* Pridaná možnosť zadať typ položky, analytický a syntetický účet pre zľavu

= 1.10.9 =
* Doplnená kompatibilita s "Manual Renewal" objednávkami vo WooCommerce Subscriptions

= 1.10.8 =
* Kompatibilita s WordPress 5.8 a WooCommerce 5.5.1

= 1.10.7 =
* Pridané nastavenie pre osobitnú úpravu dane OSS

= 1.10.6 =
* Zrušené posielanie faktúr a odkazov na faktúru v refundovaných a zlyhaných objednávkach

= 1.10.5 =
* Opravená chyba v použití DIČ podľa krajiny odberateľa

= 1.10.4 =
* Pridaný filter sf_order_api_credentials pre zmenu API prihlasovacích údajov podľa objednávky v prípade fakturácie do viacerých účtov v SuperFaktúre

= 1.10.3 =
* Opravená chyba v objednávke pri vypnutej možnosti fakturácie na firmu

= 1.10.2 =
* Zmenený spôsob priradenia daňovej sadzby zľave
* Opravená chyba vo formátovaní meta atribútov produktu

= 1.10.1 =
* Opravený problém s pripojením v CZ a AT verzii

= 1.10.0 =
* Logovanie PHP chýb pri vystavovaní faktúry (PHP 7.0 a vyššie)
* Aktualizovaný SF API klient
* Doplnená možnosť používať sandbox

= 1.9.85 =
* Pridanie značky [WEIGHT] do popisu produktu

= 1.9.83 =
* Kompatibilita s WordPress 5.7

= 1.9.82 =
* Kompatibilita s WooCommerce 5.0.0

= 1.9.81 =
* Zmenený názov parametra pre odstránenie konfliktu s pluginom UpSolution Core

= 1.9.80 =
* Opravené povinné/voliteľné popisy pre IČO, DIČ a IČ DPH v objednávke

= 1.9.79 =
* Opravená chyba s povinnými IČO, DIČ a IČ DPH pri objednávke

= 1.9.78 =
* Opravená kompatibilita s pluginom N-Media WooCommerce PPOM

= 1.9.77 =
* Doplnené logovanie ďalších typov chýb v záložke API log

= 1.9.76 =
* Odstránenie meta atribútov z tagu [ATTRIBUTES]
* Oprava chyby s prázdnym IČ DPH podľa krajiny odberateľa

= 1.9.75 =
* Pridaná možnosť použiť IČ DPH podľa fakturačnej adresy zákazníka len pre konečného spotrebiteľa

= 1.9.74 =
* Opravené deprecated volania

= 1.9.73 =
* Opravená chyba pri daňovej sadzbe zľavy

= 1.9.72 =
* Pridaný filter sf_shipping_data

= 1.9.71 =
* Pridaná možnosť priradiť faktúram tag

= 1.9.70 =
* Pridaný parameter product do filtra sf_item_data

= 1.9.69 =
* Pridaný tag [ATTRIBUTE:name] v popise produktu

= 1.9.68 =
* Opravený tag [NON_VARIATIONS_ATTRIBUTES] v popise produktu

= 1.9.67 =
* Opravená kompatibilita s pluginom WooCommerce Order Status Manager

= 1.9.66 =
* Doplnené jazyky faktúry

= 1.9.65 =
* Pridaný filter sf_client_country_data, ktorý umožňuje upraviť dáta na základe krajiny zákazníka

= 1.9.64 =
* Pridané filtre sf_skip_email_link a sf_skip_email_attachment

= 1.9.63 =
* Pridaný filter sf_skip_invoice

= 1.9.62 =
* Zmenená možnosť preveriť správnosť API prihlasovacích údajov

= 1.9.61 =
* Oprava problému pri produktoch s rôznymi daňovými sadzbami

= 1.9.60 =
* Možnosť vypnúť faktúry v emailoch pre vybavené objednávky vypne aj odkaz na faktúru

= 1.9.59 =
* Pridaná akcia sf_metabox_after_invoice_generate_button, ktorá umožňuje doplniť obsah metaboxu, za tlačidlom pre generovanie faktúry.

= 1.9.58 =
* Opravená chyba pri vystavovaní zálohových faktúr ako uhradených

= 1.9.57 =
* Pridaná možnosť vystaviť faktúru ako uhradenú podľa typu platby

= 1.9.56 =
* Pridaná záložka Pomoc s odkazmi na články o plugine a jeho nastaveniach

= 1.9.55 =
* Oprava chybnej ceny dopravy na faktúre, ak je vo WooCommerce zadaná s viac ako 2 desatinnými miestami

= 1.9.54 =
* Pridaný log API volaní

= 1.9.53 =
* Do filtrov "woocommerce_sf_invoice_extra_items" a "sf_invoice_data" bol pridaný parameter typ dokumentu.

= 1.9.52 =
* Pridaná možnosť preveriť správnosť API prihlasovacích údajov
* Oprava callbacku automatického párovania pri čiastočných úhradách

= 1.9.51 =
* Úprava kódu pracujúceho s daňovými sadzbami

= 1.9.50 =
* Oprava problému s nesprávne vypočítanou sadzbou DPH, od 1.9.50 sa pre produkty, dopravu a zľavy používajú sadzby z WooCommerce

= 1.9.42 =
* Pridaná možnosť nastavenia automatického párovania pre viac eshopov na jednu firmu v SuperFaktúre

= 1.9.41 =
* Doplnené preklady do češtiny

= 1.9.40 =
* Pridané ukladanie čísiel dokladov (invoice_no_formatted) do meta dát objednávky.

= 1.9.39 =
* Oprava detekcie meny objednávky

= 1.9.38 =
* Doplnenie možnosti zvoliť dátum vytvorenia faktúry ako dátum dodania

= 1.9.37 =
* Použitie default bankového účtu v prípade ak v nastaveniach pre konkrétne krajiny nie je vyplnený

= 1.9.36 =
* Odstránenie atribútov produktu z pluginu WooCommerce Product Add-Ons zo značky [ATTRIBUTES] v popise produktu

= 1.9.35 =
* Oprava funkcionality pridanej v predošlej verzii kvôli kompatibilite so skorším verziami PHP.

= 1.9.34 =
* Pridaná možnosť zvoliť dátum dodania - okrem dátumu platby je teraz možné zvoliť aj dátum vytvorenia objednávky.
* Pridaná možnosť povoliť vygenerovanie faktúry pre objednávky s nulovou hodnotou (napr. pri použitý darčekového kupónu či inej zľavy).
* Pridaná možnosť povoliť vygererovanie faktúry pre objednávky, ktoré nepotrebujú byť spracované (napr. virtuaálne produkty na stiahnutie).

= 1.9.33 =
* Doplnená kompatibilita s pluginom WooCommerce EU VAT Assistant

= 1.9.32 =
* Doplnená možnosť vypnúť faktúry v emailoch pre vybavené objednávky

= 1.9.31 =
* Pridané nastavenia pre vlastné číslovanie dobropisov

= 1.9.30 =
* Pridaná možnosť nastaviť bankový účet na faktúre podľa krajiny odberateľa
* Pridaná možnosť nastaviť faktúru ako uhradenú pre viacero stavov objednávky
* Doplnená možnosť prekladu pre názvy zľavy, poštovného a poštovného zdarma

= 1.9.22 =
* Zálohová faktúra sa neposiela v emailoch ak je už uhradená

= 1.9.21 =
* Opravený popis variabilného produktu

= 1.9.20 =
* Opravená chyba v detekcii pluginu WooCommerce EU VAT Number

= 1.9.19 =
* Doplnená kompatibilita s pluginom WooCommerce EU VAT Number

= 1.9.18 =
* Pridaná možnosť odrátať refudované položky z celkového počtu položiek na faktúre

= 1.9.17 =
* Opravená chyba pri nastavení nových jazykov faktúry

= 1.9.16 =
* Pridaná možnosť zadať analytický a syntetický účet pre produkt, poplatky a poštovné pre export do účtovníctva

= 1.9.15 =
* Doplnené jazyky faktúry

= 1.9.14 =
* Opravena kompatibilita s pluginom WooCommerce Order Status Manager

= 1.9.13 =
* Pridaná možnosť zadať typ položky pre produkt, poplatky a poštovné pre export do účtovníctva

= 1.9.12 =
* Pridaný filter sf_gateway_mapping

= 1.9.11 =
* Nepridávanie faktúr a odkazov na faktúry do emailov týkajúcich sa zrušených objednávok

= 1.9.10 =
* Pridané spracovanie callbacku pri úhrade v SuperFaktúre aj pre zálohové faktúry

= 1.9.9 =
* Pridaná možnosť vystaviť dobropis pre zrušené a refundované objednávky

= 1.9.8 =
* Pridaná možnosť nastaviť dátum dodania na dátum platby
* Pridaná refundácia ako položka na faktúre
* Opravené použitie predvolenej poznámky zo SuperFaktúry

= 1.9.7 =
* Pridaný odkaz na online platbu do emailov

= 1.9.6 =
* Pridaná možnosť zadať predkontácie pre poplatky a poštovné pre export do účtovníctva

= 1.9.5 =
* Neuhrádzanie ostrej faktúry ak už bola uhradená zálohová faktúra

= 1.9.4 =
* Doplnenie vzťahu medzi zálohovou a ostrou faktúrou v SuperFaktúre

= 1.9.3 =
* Doplnený Barion do platobných metód

= 1.9.2 =
* Pridaná možnosť stiahnuť faktúru v zozname objednávok

= 1.9.1 =
* Zobrazovanie hlášky o prenesení daňovej povinnosti na faktúrach do krajín mimo EU

= 1.9.0 =
* Možnosť automaticky nastaviť objednávku ako uhradenú pri spárovaní platby prevodom v SuperFaktúre

= 1.8.23 =
* Pridaná možnosť rozpočítať zľavu z kupónu po položkách

= 1.8.22 =
* Opravená chyba pri výpočte DPH poplatkov

= 1.8.21 =
* Pridané filtre pre zmenu položiek a zľavy

= 1.8.20 =
* Doplnená možnosť zadať DIČ, IČ DPH a ID číselníkov podľa krajiny odberateľa. Aktualizovaný SF API klient.

= 1.8.19 =
* Upravený filter pre pridanie extra položiek do faktúry (woocommerce_sf_invoice_extra_items).
* Opravené 2 výskyty chýb typu Notice.

= 1.8.18 =
* Opravená kompatibilita s pluginom WooCommerce Smart COD

= 1.8.17 =
* Opravená kompatibilita s pluginom Sequential Order Numbers Pro

= 1.8.16 =
* Doplnená možnosť pridať do emailov IČO, IČ DPH a DIČ odberateľa

= 1.8.15 =
* Opravená chyba v nastaveniach vystavenia faktúry

= 1.8.14 =
* Opravený chýbajúci popis produktu v položke faktúry

= 1.8.13 =
* Nahradené volania deprecated funkcií, doplnená značka [YEAR_SHORT] v číslovaní faktúr

= 1.8.12 =
* Doplnená možnosť nastaviť adresu webu v pätičke faktúry

= 1.8.11 =
* Doplnená možnosť aktualizovať pri vystavení faktúry údaje klienta v SuperFaktúre

= 1.8.10 =
* Doplnená možnosť vypnúť zobrazovanie kódu kupónu v popise

= 1.8.9 =
* Opravená chyba v implementácii nastavení pluginu

= 1.8.8 =
* Pridané nastavenie vypnúť/zapnúť možnosť fakturácie na firmu

= 1.8.7 =
* Doplnený autor pluginu

= 1.8.6 =
* Doplnená možnosť nastaviť jazyk faktúry podľa WPML jazyka objednávky
* Doplnená možnosť vypnúť zobrazovanie zľavy na produkt v popise

= 1.8.5 =
* Opravená chyba vo vystavovaní faktúry

= 1.8.4 =
* Opravená chyba v popise produktu

= 1.8.3 =
* Doplnené nastavenia pre stav úhrady faktúry

= 1.8.2 =
* Doplnená možnosť pridať poznámku k objednávke do poznámky na faktúre

= 1.8.1 =
* Doplnená kompatibilita s pluginom N-Media WooCommerce PPOM

= 1.8.0 =
* Rozdelené nastavenia do logických celkov

= 1.7.10 =
* Doplnená možnosť vypnúť automatickú úhradu faktúry pre vybavené objednávky

= 1.7.9 =
* Zobrazenie poznámky o prenesení daňovej povinnosti len pri zadanom VAT ID

= 1.7.8 =
* Opravená chyba pri vyskladávaní mena v dodacej adrese

= 1.7.7 =
* Doplnená možnosť pregenerovať zálohovú faktúru

= 1.7.6 =
* Opravená chyba pri zaokrúhľovaní ceny položiek faktúry

= 1.7.5 =
* Opravená chyba pri prenášaní spôsobu doručenia do SuperFaktúry

= 1.7.4 =
* Opravený problém so zobrazením dátumu dodania pri editácii faktúry

= 1.7.3 =
* Opravená chyba s overovaním SSL certifikátu

= 1.7.2 =
* Opravené české preklady

= 1.7.1 =
* Pridané spôsoby platby zo SuperFaktúry

= 1.7 =
* Pridaná možnosť ručne vytvoriť zálohovú faktúru a faktúru

= 1.6.49 =
* Aktualizovaná informácia o kompatibilite s najnovšou verziou WordPress

= 1.6.48 =
* Pridaný filter, ktorý umožňuje pridať do faktúry položky navyše

= 1.6.47 =
* Doplnená možnosť vypnúť faktúry na stránke Objednávka prijatá

= 1.6.46 =
* Doplnené slovenské a české preklady v nastaveniach pluginu

= 1.6.45 =
* Doplnená možnosť vypnúť faktúry v emailoch pre objednávky na dobierku

= 1.6.44 =
* Opravené chyby v generovaní prílohy emailu

= 1.6.43 =
* Opravené pregenerovanie faktúry

= 1.6.42 =
* Opravené zaokrúhľovanie v poštovnom a zľave

= 1.6.41 =
* Opravený problém s konektivitou na API meine.superfaktura.at

= 1.6.40 =
* Zmeny v implementácii SF API (identifikácia modulu, nastavenie zaokrúhľovania)

= 1.6.39 =
* Opravenie rozbaľovania firemných údajov v My Account

= 1.6.38 =
* Doplnenie podpory pre meine.superfaktura.at
* Pridaná možnosť vypnúť odkaz na faktúru v emailoch

= 1.6.37 =
* Opravenie chyby so zdvojenou zľavou

= 1.6.36 =
* Pridaný filter, ktorý umožňuje prispôsobiť, kedy sa má faktúra vystaviť ako zaplatená

= 1.6.35 =
* Pridaná možnosť pridať do faktúry položku "Poštovné" aj v prípade, že má nulovú sumu (text je možné nastaviť)

= 1.6.34 =
* Pridaná možnosť nastaviť meno v adrese dodania ako názov spoločnosti spolu s menom a priezviskom

= 1.6.33 =
* Opravené vystavovanie faktúr v stave objednávky "prijatá"

= 1.6.32 =
* Pridaná možnosť posielať PDF faktúry v prílohe emailu

= 1.6.31 =
* Opravené selecty v nastaveniach pluginu

= 1.6.30 =
* Opravené počítanie dane pri nulovej hodnote

= 1.6.29 =
* Pridaná podpora pre plugin Nastavenia SK pre WooCommerce

= 1.6.28 =
* Opravená chyba prejavujúca sa v PHP verziách starších ako 5.5

= 1.6.27 =
* Pridaný konfiguračný súbor pre WPML String Translation

= 1.6.26 =
* Pridaná možnosť nastaviť dátum vytvorenia faktúry rovnaký ako dátum vytvorenia objednávky

= 1.6.25 =
* Opravena kompatibilita s pluginom WooCommerce Order Status Manager

= 1.6.24 =
* Pridaná spätná kompatibilita s WooCommerce 2.6+

= 1.6.23 =
* Opravená kompatibilita s pluginom WooCommerce 3.2.0

= 1.6.22 =
* Pridaná možnosť zapnúť/vypnúť PAY by square QR kód

= 1.6.21 =
* Pridaná možnosť filtrovať posielane informácie o zákazníkovi a objednávke
* Opravené počítanie dane pri zľavnených produktoch

= 1.6.20 =
* Pridaná možnosť nastaviť ID číselníka

= 1.6.19 =
* Doplnené Odberné miesto do Dopravy

= 1.6.18 =
* Pridaná možnosť nastaviť ID bankového účtu

= 1.6.17 =
* Pridaný tag [NON_VARIATIONS_ATTRIBUTES] do popisu produktu

= 1.6.16 =
* Doplnené preklady pre češtinu

= 1.6.15 =
* Presunuté číslo objednávky z poznámky do údajov faktúry

= 1.6.14 =
* Pridaná možnosť filtrovania typu vytvorenej/upravenej faktúry

= 1.6.13 =
* Zmena zobrazovania ceny a zľavy pre produkty so zľavou

= 1.6.12 =
* Zmenený výpočet ceny položky bez DPH

= 1.6.11 =
* Opravené delenie nulou pri nulovej dani

= 1.6.10 =
* Opravené delenie nulou pri produktoch zadarmo

= 1.6.9 =
* Opravené prekladanie nadpisov v objednávke a emailoch

= 1.6.8 =
* Opravená chyba v názve variabilných produktov

= 1.6.7 =
* Nastavenie jazyka faktúry už pri jej vytvorení
* Zmena zobrazovania ceny a zľavy pre produkty so zľavou

= 1.6.6 =
* Pridaná možnosť nastaviť názov položky pre poštovné

= 1.6.5 =
* Opravená chyba prejavujúca sa v PHP 7.0+

= 1.6.4 =
* Pridaná možnosť nastaviť Logo ID

= 1.6.3 =
* Nová verzia SuperFaktúra API klienta
* Pridaná možnosť nastaviť Company ID
* Pridané spôsoby platby zo SuperFaktúry
* Pridané nastavenia pokladní
* Obnovenie podpory free pluginu

= 1.6.2 =
* Opravena kompatibilita s pluginom WooCommerce Wholesale Pricing

= 1.6.1 =
* Opravené označovanie faktúry ako poslanej e-mailom
* Ukončenie aktívneho vývoja a podpory free pluginu

= 1.6.0 =
* Mapovanie zadaného spôsobu prispôsobené novým zónam dopravy

= 1.5.12 =
* Pridaná možnosť filtrovať vo faktúrach výber SuperFaktúra číselníka

= 1.5.11 =
* Pridané nastavenie zobrazovania čísla objednávky vo faktúre
* Pridaná možnosť nastavenia jazyka faktúry

= 1.5.10 =
* Pridaný odkaz na proformu a faktúru do zoznamu objednávok, ktorý zákazník vidí na stránke „Môj účet“

= 1.5.9 =
* Pridaná podpora pre Sequential Order Numbers Pro

= 1.5.8 =
* Opravená kompatibilita s WordPress 4.6

= 1.5.7 =
* Pridaná možnosť filtrovať čísla faktúr

= 1.5.6 =
* Pridaná možnosť nastaviť si ako má vyzerať popis produktu vo faktúre

= 1.5.5 =
* Opravená chyba generovania faktúr pri platbe prevodom na účet alebo v hotovosti

= 1.5.4 =
* Pri číslovaní faktúr je teraz možné použiť aj číslo objednávky (ORDER_NUMBER)
* Pridaná možnosť určiť si variabilný symbol
* Pridaná informácia pre SuperFaktúru o poslaní faktúry emailom
* Opravené formátovanie textu v emailoch

= 1.5.0 =
* Opravené aplikovanie zliav.

= 1.4.16 =
* Úprava kalkulácia dane pri poplatoch.

= 1.4.15 =
* Pridané zobrazovanie popisu variácie produktu.

= 1.4.14 =
* Pridané posielanie čísla objednávky ako variabilného symbolu.

= 1.4.13 =
* Pridaná možnosť pregenerovať nezaplatenú faktúru.

= 1.4.12 =
* Fixed item subtotal rounding.

= 1.4.11 =
* Upravené posielanie fakturačnej a dodacej adresy

= 1.4.10 =
* Opravená zľava pri produkte vo výpredaji

= 1.4.9 =
* Opravené aplikácia kupónov
* Opravené zamenené zadanie telefónom a emailom
* Pridaná možnosť zobrazovať popisky pod jednotlivými položkami faktúry

= 1.4.7 =
* Opravené aplikovanie zľav pri zadaní konkrétnej sumy
* Pridané zarátavanie poplatkov

= 1.4.6 =
* Opravené vystavovanie faktúr pri variáciách produktov

= 1.4.5 =
* Pridaná možnosť nastaviť, pri ktorých spôsoboch dodania sa na faktúre zobrazuje dátum dodania
* Opravené vytváranie faktúr pre českú verziu SuperFaktura.cz
* Opravené prehodené telefónne číslo a email klienta
* Opravené správne vypočítavanie zľavových kupónov (momentálne nie je možné miešať percentuálne zľavy a zľavy na konkrétnu sumu, SuperFaktúra vždy upredností percentá)

= 1.4.0 =
* Vo faktúre sa zobrazujú zľavnené produkty
* Opravená zľava pri aplikovaní kupónu
* Pridaná možnosť vlastných komentárov
* Štát sa teraz klientom priraďuje správne

= 1.3.0 =
* Pridaný oznam o daňovej povinnosti
* Zobrazuje sa celý názov štátu
* Predĺžená doba získavania PDF faktúry z API servera SuperFaktúry, aby neostala táto hodnota prázdna

= 1.2.3 =
* Opravené zobrazovanie štátu odberateľa na faktúre

= 1.2.2 =
* Opravený problém zmiznutých nastavení

= 1.2.1 =
* Opravené generovanie faktúr

= 1.2 =
* Kompatibilita s Woocommerce 2.2
* Pridaná možnosť vybrať si slovenskú alebo českú verziu

= 1.1.6 =
* Opravené delenie nulou pri poštovnom zadarmo

= 1.1.5 =
* Opravené prekladanie pomocou po/mo súborov
* Pridané slovenské jazykové súbory
* Automatické pridávanie čísla objednávky do poznámky

= 1.1.4 =
* Opravená kompatibilita s WooCommerce 2.1

= 1.1.3 =
* V zozname modulov pribudla moznost Settings
* Opravena chyba, ktora sa vyskytovala pri zmene stavu objednavky
* Pridane zobrazovanie postovneho na fakture
* Pridane cislo objednavky vo fakture
* Zmeneny vypocet dane

= 1.1.2 =
* Opravené nezobrazovanie názvu firmy vo faktúre

= 1.1.1 =
* Opravený bug v dani.
* Pridané posielane faktúry zákazníkovi mailom (odkaz na stiahnutie faktúry)

= 1.1.0 =
* Pridaný link na faktúru do emailu.

= 1.0.0 =
Prvotné vydanie.
