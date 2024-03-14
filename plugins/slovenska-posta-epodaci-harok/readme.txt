=== Slovenská pošta - ePodací hárok ===
Contributors: matejpodstrelenec, europegraphics 
Donate link: 
Tags: posta, epodaci harok, eph, doporuceny list, balik
Requires at least: 3.5
Tested up to: 6.3
Requires PHP: 5.2.4
Stable tag: 1.4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin prepája WooCommerce so službou eph slovenskej pošty. 
Komunikácia je formou API alebo generovaním XML súboru, ktorý viete následne manuálne nahrať do pošty.

== Nové bonus funkcie ==
- Express kuriér podacie čísla
- Úložná lehota

Zakúpením bonusovej funkcionality podporíte ďalší vývoj pluginu 🙂

== Návod na použitie ==

1. Plugin nainštalujete priamo cez Wordpress -> Administrácia -> Pluginy -> Pridať nový -> Hľadajte ePodací hárok
	- Prípadne stiahnite si .zip súbor a nahrajte ho cez možnosť "Nahrať plugin" v administrácii. 
2. V administrácii, časť Nastavenia -> ePodací hárok, vyplníte potrebné údaje.
	- Odporúčame používať pre komunikáciu s poštou API, nie je to však nevyhnutné.
	- Vaše API údaje zistíte tu: https://mojezasielky.posta.sk/#settings
3. V administrácii, časť WooCommerce -> Objednávky, označíte objednávky, ktoré chcete odoslať.
	- Pri každej objednávke viete osobitne nastaviť druh zásielky, štandardná hodnota je "Doporučený list"
4. Následne vyberiete zo zoznamu "Hromadné akcie" možnosť:
	- "Podací hárok odoslať (API)", pre odoslanie objednávok cez API
	- alebo "Podací hárok export (XML)"
	- a stlačíte "Použiť"
5. Pri možnosti API, máte hotovo. 
	- V prípade voľby XML, musíte ešte stiahnuť vytvorený .zip súbor, rozbaliť ho a jednotlivé xml súbory nahrať manuálne tu: https://mojezasielky.posta.sk/ 

== Nápady na vylepšenie ==
Plugin nefunguje ako má? Prípadne nepokrýva vaše potreby?
Napíšte nám, radi pomôžeme a plugin vylepšíme.

== Podpora vývoja pluginu ==
Ak ste si obľúbili plugin Slovenská pošta – ePodací hárok, môžete jeho [vývoj podporiť](https://matejpodstrelenec.sk/podpora-vyvoja-pluginov/).

== EN ==
Slovak post office - eph service
This plugin connects WooCommerce to eph service of Slovak post office.
It is designed mainly for local community and is not translated to English.
If you think that this plugin could be useful to you, let us know and we will proceed with translation.   

== Screenshots ==

1. Settings
2. WooCommerce orders table
 
== Changelog ==

= 1.4.3 =
* WooCommerce HPOS podpora

= 1.4.2 =
* Fix pre Predvolený druh zásielky

= 1.4.1 =
* Bonus funkcia Generovanie adresných štítkov
* Pridanie poznámky do API komunikácie obsahujúce číslo objednávky
* Nový druh zásielky Expres kuriér na poštu
* Fix pre XML export: Formát podacích čísiel
* Fix pre XML export: Viackusová zásielka

= 1.4 =
* Bonus funkcia Express kuriér podacie čísla
* Bonus funkcia Úložná lehota

= 1.3.4 =
* Úprava json_encode funkcie pre správne posielanie decimal čísiel

= 1.3.3 =
* Podpora nového REST API od Slovenskej pošty
* Fix pre nezapisovanie sledovacieho čísla (tracking number)

= 1.2.1 =
* Pridaný nový druh zásielky: "List"
*
* Za podporu vývoja verzie 1.2.1 patrí ĎAKUJEM:	
* www.adamkuric.sk

= 1.2 =
* Pridaný nový druh zásielky: "Poistený list"
* Pridanie API logu do nastavení
* Pridaná kompatibilita pre plugin WooCommerce Sequential Order Numbers
* PHP 8 bug fixy
* Fix pre sledovacie číslo + automatické pridanie sledovacieho čísla do potvrdzujúcej objednávky (stav = vybavená)
*
* Za podporu vývoja verzie 1.2 patrí ĎAKUJEM:
* www.tvojetricko.sk

= 1.1 =
* Nastavenie predvoleného druhu dopravy podľa metódy doručenia
* Pridanie možnosti zadať inú návratovú adresu odosielateľa
* Automatické skrátenie symbolu prevodu na 10 znakov (max. povolený limit)
*
* Za podporu vývoja verzie 1.1 patrí ĎAKUJEM:
* www.tvojetricko.sk

= 1.0.9 =
* Získanie podacieho čísla po vytvorení štítkov v EPH.
* Oprava hmotnosti (Neposielala sa do EPH pre Doporučený list)
* Pridanie nastavenia "Predvolený druh zásielky"

= 1.0.8 =
* Aktualizácia Select2 knižnice

= 1.0.7 =
* Pridanie konverzie váhy z (g) na (kg)
* Oprava spočítavania váhy pre varianty

= 1.0.6 =
* Pridanie parametru počet kusov pre Express kuriéra

= 1.0.5 =
* Pridanie Express kuriéra

= 1.0.4 =
* Pridanie metaboxu do detailu objednávky, kde si viete nastaviť váhu objednávky a aktivovať službu "Pozor krehké"

= 1.0.3 =
* Nový spôsob úhrady "Faktúra" -> Zmluvní zákazníci odteraz vedia zadať svoje podacie čísla do nastavení pluginu a využívať službu "Balík - zmluvní zákazníci". 
* Zobrazovanie chybového stavu pri zlyhaní odoslania objednávky cez API

= 1.0.2 =
* Nastavenie dobierky -> Odteraz viete priradiť platobnú metódu k dobierke
* Oprava automatického výpočtu váhy zásielky
* Oprava verziovania CSS a JS

= 1.0.1 =
* Doplnenie možností platby za podací hárok (Online platobnou kartou)
* Oprava XML exportu
* Oprava pomocného textu

= 1.0.0 =
* Zverejnenie pluginu