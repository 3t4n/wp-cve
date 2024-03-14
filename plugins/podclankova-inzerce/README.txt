=== Podčlánková inzerce ===
Contributors: webdeal
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40firma-webdeal%2ecz
Tags: adsense, ad, reklama, link, links, odkaz, money, peníze
Requires at least: 5.0
Tested up to: 5.9.3
Stable tag: 2.4.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Podčlánková inzerce je jednoduchý plugin, který vám umožní prodávat reklamu přímo pod články na vaší stránce.

== Description ==
Podčlánková inzerce je jednoduchý plugin, který vám umožní prodávat reklamu přímo pod články na vaší stránce. Vše je jednoduché a rychlé - nemusíte ovládat žádné programovací jazyky.

Plugin obsahuje kompletní českou a slovenskou lokalizaci včetně nápovědy, takže jeho nastavení zvládne opravdu každý.

Kromě propojení s Copywriting.cz plugin podporuje i platební bránu PayPal.com.

== Installation ==
1. Rozbalte archív a nahrejte plugin ’podclankova-inzerce’ do složky ’/wp-content/plugins/’ na vašem hostingu
2. Aktivujte plugin a nastavte dle nápovědy přímo v administraci
3. Uložte nastavení a povolte plugin

== Frequently Asked Questions ==

= Jak aktualizovat z EasyAD na modul Podclankova-inzerce? =

Aktualizaci provedete tak, že smažete složku easyad z wp-content/plugins a nahrajete složku podclankova-inzerce. Při aktivaci si modul sám ověří, zda byl EasyAD nainstalován a přetáhne si data. NEMA®TE PLUGIN EASYAD Z ADMINISTRACE! PŘI©LI BYSTE O DATA!

= Jak obnovím data ze serveru Copywriting.cz? =

Data obnovíte po přihlášení na Copywriting.cz v detailu webu. Dostanete se tam: nahoře vyberte Reklamní prostor -> Moje weby -> daný web -> dole byste měli vidět pravidelné poslední zálohy.

= Jak často se provádí zálohy na Copywriting.cz? =

Zálohy se provádí každý den v nočních hodinách.

= Co udělá obnova dat zálohou z Copywriting.cz? =

Obnova dat nepřemaže stávající odkazy, pouze doplní chybějící.

== Screenshots ==

1. Ukázka základního nastavení po první aktivaci pluginu.

Tyto řádky si můžete změnit v administraci pluginu.

== Changelog ==
= 2.4.0 =
* oprava chyby při dotazování se na API Copywriting.cz
* oprava chyby stylování u WP 5.9.3
* otestování funkčnosti s WP 5.9.3

= 2.3.3 =
* přidání ikony pluginu
* otestování s WP 5.2
* nápověda kategorie v administraci
* odstraněna možnost ruční zálohy, zůstává už jen možnost automatického zálohování s Copywriting.cz
* možnost upravovat/skrývat a mazat odkazy z rozhraní copywriting.cz (zatím jen pro provozovatele)
* oprava chyb

= 2.3.2 =
* přesměrování domovské stránky

= 2.3.0 =
* podpora pro W3Total cache, WP Fastest cache a vylepšena podpora pro Comet Cache
* nenačítá zbytečně styly PayPalu, pokud se nevyužívá
* umožňuje inzerentovi volbu mezi follow a nofollow odkazy
* umožňuje majiteli webu zvolit jaké odkazy budou prodávány (follow/nofollow/volba na inzerentovi)
* umožňuje nastavit si konverzní pixel
* podpora emoji
* úprava administrace, kosmetické změny, zjednodušení

= 2.2.5 =
* změna URL API, starší verze než 2.2.5 přestanou nejpozději od 1.5.2017 fungovat

= 2.2.4 =
* úprava synchronizace s Copywriting.cz

= 2.2.3 =
* úprava stylů
* optimalizace SQL dotazů pluginu

= 2.2.0 =
* přesměrování platební brány na HTTPS verzi
* drobná vylepšení a opravy

= 2.1.12 =
* opravena chyba při načítání článků na Copywriting.cz

= 2.1.11 =
* opravena chyba s extra částkou

= 2.1.10 =
* opravena chyba s nezobrazováním textu tlačítek

= 2.1.9 =
* opravena chyba, která rozhodila shortcode pokud se plugin do článků vkládal automaticky
* opraven popisek, který odkazoval na neexistující stránku při aktivaci PayPalu

= 2.1.8 =
* opravena chyba při prohození PayPal testovacích údajů a těch na ostrém režimu

= 2.1.7 =
* vylepšena podpora šablony Hueman
* Copywriting.cz pop-up okno se otevírá uprostřed stránky namísto v levém rohu
* nové PayPal tlačítko
* ostylování PayPal hlášek
* oprava fungování PayPalu
* přidáno nastavení PayPal rozhraní do administrace (pro účely testování)
* oprava drobných chyb

= 2.1.6 =
* možnost vložit box ručně
* SK instalace automaticky vloží slovenský titulek
* oprava drobných chyb

= 2.1.4 =
* podpora WP Super Cache
* podpora ZenCache
* podpora CometCache

= 2.1.3 =
* oprava drobných chyb

= 2.1.2 =
* vylepšena komunikace s API

= 2.1.1 =
* zobrazování formuláře i při vypnuté jquery knihovně
* opravena kolize s Speed booster pack
* nově si lze nakoupit u sebe na webu reklamu - není účtován poplatek a slouží to k otestování, zda celý proces je funkční

= 2.1 =
* podpora slovenštiny (automaticky se aktivuje u slovenské lokalizace Wordpressu)
* oprava drobných chyb

= 2.0 =
* přejmenování z EasyAd na Podčlánková inzerce
* možnost nastavit si, zda se formulář pro nákup bude zobrazovat po kliknutí na odkaz nebo přímo
* při psaní odkazu se generuje i náhled jak bude vypadat po koupi (víz obrázek níže)
* odstranění brandingu pokud bude aktivována brána Copywriting.cz
* zálohování zakoupených odkazů na Copywriting.cz
* možnost obnovit odkazy ze zálohy na Copywriting.cz
* možnost vypnout modul v určitých rubrikách
* aktualizace přímo z Wordpress.org
* odstranění nutnosti vložit do šablony kód
* zpřehlednění administrace
* zjednodušení prvotního nastavení modulu
* možnost nastavit si vlastní titulek
* možnost stylovat zvlášť box a odkazy
* nastylování chybových hlášek
* překlad modulu do slovenštiny
* úklid po smazání pluginu

= 1.0 =
* propojení s Copywriting.cz
* extra cena (možnost nastavit u starších článků nižší cenu)
* manuální přidávání odkazů
* úprava CSS
* nápověda
* možnost ručně zálohovat odkazy

== Upgrade Notice ==

= 2.0 =
Nová verze je plně kompaktibilní se starší verzí. Stačí smazat z FTP (ne z WP administrace!) složku easyad (nachází se v wp-content/plugins/) a nahrát novou složku podclankova-inzerce. Poté aktivovat a plugin už sám převede data ze staré verze do nové.
