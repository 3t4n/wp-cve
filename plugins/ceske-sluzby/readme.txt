=== České služby pro WordPress ===
Donate link: http://www.separatista.net
Tags: Heureka.cz, Sklik.cz, WooCommerce, Ulozenka.cz, Srovname.cz, DPD, Zbozi.cz, Pricemania.cz, Google
Requires at least: 4.0
Tested up to: 4.6.1
Stable tag: 0.5

Implementace různých českých služeb do WordPressu (zejména pro WooCommerce)

== Description ==

Implementace různých českých služeb do WordPressu (zejména pro WooCommerce).

Našli jste nějakou chybu?
Nahlašte ji prosím přímo na fóru: http://www.separatista.net/forum

Chcete se zapojit do dalšího vývoje?
Potom můžete použít přímo Github: https://github.com/pavelevap/ceske-sluzby

Nějaká funkce chybí?
Můžete ji sponzorovat a urychlit její implementaci.

Plugin už sice bez problémů používá více než 900 různých webů, ale berte ho prosím stále jako testovací verzi.

Pro správnou funkčnost vyžaduje WooCommerce verzi 2.2.x.

Plugin zatím podporuje následující služby a pluginy:

* WooCommerce: Ověřeno zákazníky (Heureka.cz a Heureka.sk)
* WooCommerce: Certifikát spokojenosti (Heureka.cz a Heureka.sk)
* WooCommerce: Měření konverzí (Heureka.cz a Heureka.sk, Sklik.cz, Srovname.cz)
* WooCommerce: Doprava (Uloženka.cz) - CZ i SK
* WooCommerce: Doprava (DPD ParcelShop) - CZ i SK
* WooCommerce: Možnost změny provedených objednávek v případě dobírky
* WooCommerce: Předobjednávky
* WooCommerce: Dodací doba
* WooCommerce: Sledování zásilek
* WooCommerce: XML feed (Heureka.cz a Heureka.sk, Zbozi.cz, Google, Pricemania.cz a Pricemania.sk)
* WooCommerce: Základní podpora variant a vlastností pro XML feedy (automatické generování parametrů a názvů)
* WooCommerce: Průběžné generování velkého množství produktů do .xml souboru
* WooCommerce: Možnost vynechání kategorií či produktů v XML feedech
* WooCommerce: Speciální možnosti pro nastavení XML feedů (CATEGORYTEXT, DELIVERY_DATE, PRODUCTNAME, MANUFACTURER, CUSTOM_LABEL, ITEM_TYPE)
* WooCommerce: Omezení nabídky dopravy, pokud je dostupná zdarma
* WooCommerce: Zobrazování recenzí ze služby Ověřeno zákazníky pomocí shortcode (Heureka.cz a Heureka.sk)

== Oficiální podpora pluginu ==

Fórum podpory: http://www.separatista.net/forum

== Frequently Asked Questions ==

**Jak plugin správně nastavit?**

Aktivovat plugin a přejít do menu WooCommerce - Nastavení - záložka České služby.

== Changelog ==

= 0.5 =
* WooCommerce: Certifikát spokojenosti (Heureka.cz a Heureka.sk)
* WooCommerce: Nastavení a zobrazení dodací doby (napojení na XML feedy v podobě DELIVERY_DATE)
* WooCommerce: Předobjednávky - nastavení pro jednotlivé produkty, možnost nastavení místa a formátu zobrazení na webu (napojení na XML feedy)
* WooCommerce: Sledování zásilek - volitelné zasílání notifikačních emailů o odeslané zásilce
* WooCommerce: XML feed (Google)
* WooCommerce: XML feedy:
 * Volitelné generování .xml souborů (probíhá postupně, vhodné pro eshopy s velkým množstvím produktů)
 * Nastavení různých informací na úrovni celého eshopu, kategorie či přímo produktu (vzájemně propojeno)
 * Možnost specifikovat vlastní název produktu (PRODUCTNAME)
 * Možnost vynechat libovolné kategorie či jednotlivé produkty 
 * Základní podpora variant a vlastností (unikátní URL adresy pro varianty, automatické generování parametrů a názvů)
 * Možnost napojení EAN (na základě SKU či vlastního uživatelského pole)
 * Podpora pro doplnění výrobce (MANUFACTURER) - různé pluginy, taxonomie, vlastnosti či uživatelská pole
 * Možnost dodatečného označení produktů (CUSTOM_LABEL) pro Google a Zbozi.cz
 * Možnost označování bazarového a repasovaného zboží (na úrovni kategorie i produktu)  
 * Možnost zobrazování/ignorování shortcodes v popisech produktů (DESCRIPTION)    
 * Doplněno odřádkování pro snadnou čitelnost v prohlížečích
 * Opraveno zobrazení ceny (nově započítáno i DPH)
* WooCommerce: XML feed (Heureka.cz a Heureka.sk) - možnost doplnění CATEGORYTEXT v nastavení kategorie i produktu
* WooCommerce: XML feed (Zbozi.cz):
 * Aktualizace pro novou strukturu XML
 * Opraveno kódování URL adres
 * Opraveno zobrazení elementu PARAM
* WooCommerce: Doprava (DPD ParcelShop, Uloženka.cz):
 * Zamezení dvojitému načítání funkce pro zobrazení poboček pomocí podmínky is_ajax().
 * Funkce get_shipping_methods() nahrazena funkcí load_shipping_methods(), takže by se měly pobočky bez problémů zobrazovat.
 * Zachování zvolené pobočky při změně platební metody.
 * Podpora pluginu WooCommerce Currency Switcher (cena poštovného a dobírky).
* WooCommerce: Měření konverzí (Heureka.cz a Heureka.sk) - aktualizován měřící skript
* WooCommerce: Ověřeno zákazníky - možnost omezení počtu recenzí (Heureka.cz a Heureka.sk)
* WooCommerce: Opravena drobná chyba ve verzi 2.6 

= 0.4 =
* WooCommerce: Ověřeno zákazníky (Heureka.sk)
* WooCommerce: XML feed (Zbozi.cz)
* WooCommerce: Doprava (DPD ParcelShop)
* Základní implementace (není přímo napojeno na provozovatele)
* Možnost provozovat samostatně nebo jako dodatečné pobočky pro Uloženku
* Nastavení jednotné ceny za doručení a dobírku (CZ i SK)
* Automatická nabídka poboček podle zvolené země zákazníka (CZ i SK)
* Uloženka nově podporuje všechny výše uvedené funkce jako DPD ParcelShop
* Napojeno na API Uloženky (nastavení obchodu a volba poboček)
* WooCommerce: Zobrazování recenzí ze služby Ověřeno zákazníky pomocí shortcode (Heureka.cz a Heureka.sk)
* Aktualizace recenzí jednou denně
* Jednoduchý shortcode: [heureka-recenze-obchodu]
* WooCommerce: Měření konverzí (Heureka.sk)
* Automatická podpora slovenské verze podle nastavené lokalizace
* Optimalizace databázového dotazu pro generování XML feedů
* WooCommerce: XML feed (Pricemania.cz a Pricemania.sk)

= 0.3 =
* WooCommerce: XML feed (Heureka.cz)
* Možnost volitelné aktivace generovaného feedu
* Implemetace samostatné záložky s nastavením
* Možnost nastavení globální dodací doby pro všechny produkty
* Možnost zobrazovat EAN kód pokud ho zadávate do pole SKU
* Skryté produkty nejsou součástí feedu
* Generován základní strom použitých kategorií
* Pro snadné generování feedu použita PHP knihovna XMLWriter
* WooCommerce: Omezení nabídky dopravy, pokud je dostupná zdarma

= 0.2 =
* WooCommerce: Měření konverzí (Srovname.cz)
* WooCommerce: Možnost změny provedených objednávek v případě dobírky
* Oprava zobrazení Uloženky ve WooCommerce 2.3.x
* Plugin vyžaduje WooCommerce verzi 2.2.x
* Použití funkce wc_add_notice()
* Drobné čistky a úpravy

= 0.1 =
* WooCommerce: Ověřeno zákazníky (Heureka.cz)
* WooCommerce: Měření konverzí (Heureka.cz)
* WooCommerce: Měření konverzí (Sklik.cz)
* WooCommerce: Doprava (Uloženka.cz)

== Screenshots ==

Screenshoty budou doplněny.

== Installation ==

Instalovat plugin, aktivovat a přejít do menu WooCommerce - Nastavení - záložka České služby.
