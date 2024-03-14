=== Integration for Szamlazz.hu & WooCommerce ===
Contributors: passatgt
Tags: szamlazz.hu, szamlazz, woocommerce, szamlazo, magyar
Requires at least: 5.0
Tested up to: 6.4.3
Stable tag: 5.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Számlázz.hu összeköttetés WooCommerce-hez.

== Description ==

> **PRO verzió**
> A bővítménynek elérhető a PRO verziója évi 30 €-ért, amelyet itt vásárolhatsz meg: [https://visztpeter.me](https://visztpeter.me/woocommerce-szamlazz-hu/)
> A licensz kulcs egy weboldalon aktiválható, 1 évig érvényes és természetesen email-es support is jár hozzá beállításhoz, testreszabáshoz, konfiguráláshoz.
> A vásárlással támogathatod a fejlesztést akkor is, ha esetleg a PRO verzióban elérhető funkciókra nincs is szükséged.

= Funkciók =

* **Manuális számlakészítés**
Minden rendelésnél a jobb oldalon megjelenik egy új gomb, rákattintáskor elküldi az adatokat számlázz.hu-nak és legenerálja a számlát.
* **Automata számlakészítés** _PRO_
Lehetőség van a számlát automatikusan elkészíteni bizonyos fizetési módoknál, vagy ha a rendelés teljesítve lett
* **Mennyiségi egység**
A tételek mellett a mennyiségi egységet is feltüntetni a számlát, amelyet a beállításokban minden termékhez külön-külön meg tudod adni és megjegyzést is tudsz megadni a tételhez
* **E-Nyugta** _PRO_
Ha elektronikus termékeket, jegyeket, letölthető tartalmakat értékesítesz, nem fontos bekérni a vásárló számlázási címét, elég csak az email címét, a bővítmény pedig elektronikus nyugtát készít
* **Számlaértesítő** _PRO_
Az ingyenes verzióban a számlázz.hu küldi ki a számlaértesítőt a vásárlónak. A PRO verzióban csatolni lehet a WooCommerce által küldött emailekhez, így nem fontos használni a Számlázz.hu számlaértesítőjét és a vásárlód egyel kevesebb emailt fog kapni
* **Nemzetközi számla**
Ha külföldre értékesítesz például euróban, lehetőség van a számla nyelv átállítására és az aktuális MNB árfolyam feltüntetésére a számlán. Kompatibilis WPML-el és Polylang-al is.
* **Automata díjbekérő létrehozás** _PRO_
Ha a rendelés állapota átállítódik függőben lévőre, automatán legenerálja a díjbekérő számlát. Lehet kézzel egy-egy rendeléshez külön díjbekérőt is csinálni.
* **Előleg számla**
A díjbekérő helyett lehetőség van előleg számlát készíteni
* **IPN és teljesítettnek jelölés** _PRO_
A kifizetettségéről értesítést kaphat a webáruház, illetve automatán teljesítettnek jelölheti a számlát
* **Naplózás**
Minden számlakészítésnél létrehoz egy megjegyzést a rendeléshez, hogy mikor, milyen néven készült el a számla
* **Sztornózás**
A számla sztornózható a rendelés oldalon, vagy kikapcsolható 1-1 rendeléshez
* **Szállítólevél**
Lehetőség van arra, hogy a számlakészítés mellett szállítólevelet is készítsen automatikusan a rendszer
* **Adószám mező**
A WooCommerce-ben alapértelmezetten nincs adószám mező. Ezzel az opcióval bekapcsolható, hogy a számlázási adatok között megjelenjen. Az adószámot a rendszer eltárolja, a vásárlónak küldött emailben és a rendelés adatai között is megjelenik. Lehetőség van arra, hogy csak 100.000 Ft áfatartalom felett látszódjon.
* **Könyvelési adatok** _PRO_
Termékkategóriánként megadhatók a könyveléssel kapcsolatos adatok magyar és külföldi vásárlásoknál: főkönyvi szám, árbevételi főkönyvi szám, gazdasági esemény, áfa gazdasági esemény. A felhasználó azonosítóját pedig a vevő főkönyvi azonosítójaként eltárolja.
* **Automata teljesítettnek jelölés**
Beállítható, melyik fizetési módoknál jelölje meg a számlát teljesítettnek automatikusan
* **És még sok más**
Papír és elektronikus számla állítás, áfakulcs állítás, számlaszám előtag módosítása, letölthető számlák a vásárló profiljában, hibás számlakészítésről e-mailes értesítő stb...

= Fontos kiemelni =
* A generált számlát letölti saját weboldalra is, egy véletlenszerű fájlnéven tárolja a wp-content/uploads/wc_szamlazz mappában
* Fizetési határidő és megjegyzés írható a számlákhoz
* Kuponokkal is működik, a számlán negatív tételként fog megjelenni a végén
* Szállítást is ráírja a számlára
* A PDF fájl letölthető egyből a Rendelések oldalról is(táblázat utolsó oszlopa)

= Használat =
Részletes dokumentációt [itt](https://visztpeter.me/dokumentacio/) találsz.
Telepítés után a WooCommerce / Beállítások oldalon meg kell adni a szamlazz.hu felhasználónevet és jelszót, illetve az ott található többi beállításokat igény szerint.
Minden rendelésnél jobb oldalon megjelenik egy új doboz, ahol egy gombnyomással létre lehet hozni a számlát. Az Opciók gombbal felül lehet írni a beállításokban megadott értékeket 1-1 számlához.
Ha az automata számlakészítés be van kapcsolva, akkor a rendelés lezárásakor(Teljesített rendelés státuszra állítás) automatikusan létrehozza a számlát a rendszer.
A számlakészítés kikapcsolható 1-1 rendelésnél az Opciók legördülőn belül.
Az elkészült számla a rendelés aloldalán és a rendelés listában az utolsó oszlopban található PDF ikonra kattintva letölthető.

**FONTOS:** Mindenen esetben ellenőrizd le, hogy a számlakészítés megfelelő e és konzultálj a könyvelőddel, neki is megfelelnek e a generált számlák. Sajnos minden esetet nem tudok tesztelni, különböző áfakulcsok, termékvariációk, kuponok stb..., így mindenképp teszteld le éles használat előtt és ha valami gond van, jelezd felém és megpróbálom javítani.

= Fejlesztőknek =

A plugin egy XML fájlt generál, ezt küldi el a szamlazz.hu-nak, majd az egy pdf-ben visszaküldi az elkészített számlát. Az XML fájl generálás előtt módosítható a `wc_szamlazz_xml` filterrel. Ez minden esetben az éppen aktív téma functions.php fájlban történjen, hogy az esetleges plugin frissítés ne törölje ki a módosításokat!

Lehetőség van sikeres és sikertelen számlakészítés után egyedi funckiók meghívására a bővítmény módosítása nélkül:

   <?php
   add_action('wc_szamlazz_after_invoice_success', 'sikeres_szamlakeszites',10,4);
   function($order, $response, $szamlaszam, $pdf_link) {
     //...
   }

   add_action('wc_szamlazz_after_invoice_error', 'sikeres_szamlakeszites',10,5);
   function($order, $response, $agent_error_code, $agent_error, $agent_body) {
     //...
   }
   ?>

== Installation ==

1. Töltsd le a bővítményt
2. Wordpress-ben bővítmények / új hozzáadása menüben fel kell tölteni
3. WooCommerce / Integráció menüpontban találhatod a Számlázz.hu beállítások, itt legalább az agent kulcs mezőt kötelező kitölteni
4. Működik

== Frequently Asked Questions ==

= Mi a különbség a PRO verzió és az ingyenes között? =

A PRO verzió néhány hasznos funkciót tud, amiről [itt](https://visztpeter.me/woocommerce-szamlazz-hu/) olvashatsz. De a legfontosabb az automata számlakészítés, díjbekérő létrehozás és az E-Nyugta támogatás, amivel digitális termékeidet(letölthető könyvek, tanfolyamok, jegyek) számla helyett nyugtával is eladhatsz, így a vásárlódnak nem kell megadnia az összes személyes adatát(csak név + email). Továbbá 1 éves emailes support is jár hozzá.

= Hogyan lehet tesztelni a számlakészítést? =

A számlázz.hu-tól lehet kérni kapcsolat űrlapon keresztül, hogy állítsák át a fiókot Teszt üzemmódba, így lehet próbálgatni a számlakészítést.

= Teszt módban vagyok, de a számlaértesítő nem a vásárló email címére megy. =

A számlaértesítő teszt módban nem a vásárló email címére érkezik, hanem a számlázz.hu-n használt fiók email címére.

== Screenshots ==

1. Beállítások képernyő(WooCommerce / Beállítások)
2. Számlakészítés doboz a rendelés oldalon

== Changelog ==

5.9
* EU adószámot is elfogad az adószám mező és validálja is
* Új termékszerkesztő kompatibilitás(extra számlázással kapcsolatos beállítások megjelenítése)
* Adószám blokk javítások
* Előlegszámla sztornózás hibajavítása
* Csoportazonosító paraméter felülírható a wc_szamlazz_xml filterrel
* Komatpiblitás megjelölése WC 8.5-el
* Rendelés törlés bug javítás

5.8.7.2
* Kompatibilitás megjelölése WC 8.4-el
* Duplikált tétel megjegyzés javítás

5.8.7.1
* Woo verzió minimum 7.0--nak jelölve

5.8.7
* 404-es hiba javítás

5.8.6
* Adószám kompatiblitás fejlesztés az új pénztár blokkal - lehet rádió gombos vagy checkbox-os megoldás, beállítások átkerültek a blokk beállításaiba, a blokk automatán bekerül a bekapcsolás után a megfelelő helyre, töltés indikátor az automata kitöltéshez
* wc_szamlazz_invoice_line_item filter javítás(false értéknél nem lesz php warning)
* WooCommerce Product Bundles esetén beállítható, hogy el legyenek e rejtve az ingyenes bundled termékek a számlán(alapból elrejti)

5.8.5
* Kompatibilitás megjelölése legújabb WP/WC verziókkal

5.8.4
* A számla generálás egyedi automatizálás lefuttatja a nyugta készítést is
* Be lehet állítani, hogy melyik fizetési módoknál nem engedélyezett a nyugta kérése

5.8.3
* Számlázz.hu logo csere
* Polylang javítás

5.8.2
* Manuális számlafeltöltés javítás(plusz a fájl kiválasztásakor a számla nevét beírja autoamtán a dokumentum neve mezőbe)

5.8.1
* Megjegyzés termék kategória feltétellel javítva variálható termékeknél
* Tétel megjegyzés elrejtése opció
* Ingyenes tétel elrejtése opció

5.8
* Pénztár blokk kompatibilitás(adószám mező)
* Fordítás javítás(customer_note helyett notes)
* Adószám PHP warning javítás
* Adózási osztállyal kapcsolatots hiba javítása
* Kompatibilitás megjelölése legújabb WC / WP verzióval

5.7.6.1
* EU adószámos JS probléma javítása
* Kompatibilitás megjelölése legújabb WC verzióval(7.9)

5.7.6
* Fordítási hiányosság javítása

5.7.5
* Egyedi automatizálásnál a fizetési határidő pontosabban beállítható
* A `wc_szamlazz_get_accounting_details` filterrel módosíthatók a könyvelési adatok
* Product Bundles kompatibilitás javítás
* Kompatibilitás megjelölése WC 7.8-al
* Egy PHP Warning javítása a beállítások mentésekor
* EU Vat Number kompatibilitás fejelsztése

5.7.4
* Termék attribútum javítás feltételeknél
* IPN hívás után eltárolja a fizetés idejét, így a rendelésben látszik mikor lett kifizetve pontosan
* Admin JS bug javítás

5.7.3
* Fordítási hiányosság javítása
* PHP warning javítása termék variációknál és HPOS kompatibilitás javítás
* Kompatibilitás megjelölése legújabb WC és WP verziókkal

5.7.2
* Még javítás WooCommerce Subscriptions kompatibilitáshoz
* Fiók feltétel javítása manuális számlakészítéskor
* Termék beállítások javítás

5.7.1
* Javítás EU Vat Number és Translatepress kompatibilitáshoz
* Javítás WooCommerce Subscriptions kompatibilitáshoz

5.7
* A céges rendelés feltétel EU-n belül nézi az EU Vat Number bővítménnyel megadott adószámot is(csak akkor céges, ha adószám is lett megadva)
* Feltételek ABC sorrendben
* HPOS kompatibilitás hibajavítások
* Adószám mező megjelenítéshez extra opció: céges rendelés checkbox, az adószámot és a cégnevet akkor jeleníti meg, ha bepipálod a "Céges rendelés" kapcsolót
* EU Vat Number kompatibilitás fejlesztés: Magyarország esetén elrejti az EU adószám mezőt és kompatibilis az új "Céges rendelés" jelölődobozzal is
* Opció áfa nélküli rendelés feladása, ha a vásárló EU-n kívüli, céges(adott meg cégnevet) és virtuális terméket vásárol(nincs szállítás)
* Kata kompatibilitás kapcsoló figyeli azt is, ha valamelyik név mezőbe Kft, Bt és egyéb céges értékeket ír be a vásárló
* Translatepress javítás fizetési mód és tétel név fordításokhoz
* Rendeléskezelőben duplán megjelenő ár javítása az összeg oszlopban
* Kompatibilitás megjelölése legújabb WC és WP verziókkal

5.6.6
* Translatepress kompatibilitás javítás
* Hibajavítás WooCommerce Subscriptions ingyenes időszakhoz

5.6.5
* Hibajavítás a CTX Feed bővítményhez
* Hibajavítás WPML-hez

5.6.4
* HPOS kompatibilitás
* Számlák / díjbekérők megjelenítése a csomagpontos bővítmény csomagkövetés oldalán
* Ha egyedi rendelés státuszt használsz valamilyen extra bővítménnyel és nem fut le a beállított automatizálás, megadhatod manuálisan a státusz azonosítókat a beállításokban
* Automatizálás beállításánál szállítási osztály is kiválasztható feltételnek
* Hibaüzenetben lévő rendelés szerkesztés link javítása

5.6.3.5
* Több fiók használatával kapcsolatos hibajavítás automata számlakészítéskor
* Kompatibilitás ellenőrzése és megjelölése WC 7.0-val

5.6.3.4
* WooCommerce European VAT / IVA Compliance kompatibilitás

5.6.3.3
* PRO verzió aktiválás/deaktiválás biztonsági javítás

5.6.3.2
* Különbözeti áfa(K.AFA) áfakulcs támogatása

5.6.3.1
* Javítás egyedi áfakulcs felülíráshoz termék kategóriás és attribútumos feltétel esetén

5.6.3
* KATA kompatibilitás kapcsoló: ha be van kapcsolva és a számlára kerülne valamilyen adószám(akár EU-s adószám), akkor nem fog számlát generálni, helyette hibaüzenetet ír ki

5.6.2.1
* Nyugtával kapcsolatos javítások

5.6.2
* Adatok felülírása opció: PRO verzióban a bank adatokat, előtagot és nyelvet lehet módosítani a számlán különböző feltételek szerint, például eurós számlánál másik bankszámlaszámot tudsz feltüntetni a számlán
* A feltételek közé bekerült a rendelés pénzneme is
* Kompatibilitás megjelölése legújabb WP/WC verzióval
* Százalékos áfakulcsnál tizedesjegy támogatása
* A wc_szamlazz_receipt_enabled filterrel kóddal el lehet rejteni egyszerűen a nyugtát kérek opciót(pl ha bizonyos fizetési módoknál nem akarod engedélyezni)

5.6.1.1
* Hucommerce kompatibilitás javítás(adószám)

5.6.1
* PHP 8 kompatibilitás javítások
* 0 Ft-os számla automatán fizetettnek jelölése
* Manuális számlafeltöltés javítás
* Ha teljesített státuszban van a rendelés, akkor az IPN hívás nem módosítja a státuszt(wc_szamlazz_ipn_should_change_order_status filterrel módosítható a feltétel)
* Javítás Product Bundles újabb verziójával való kompatibilitáshoz
* Kompatibilitás megjelölése legújabb WP/WC verzióval

5.6
* Egyedi automatizálásoknál beállítható fizetettnek jelölés automatizálás is
* Dokan kompatibilitás javítás
* Sequential Order Number kompatibilitás(IPN használatakor)

5.5.9
* HuCommerce adószám javítás
* Fordítás update
* Új licenc kulcs kezelés
* Kompatibilitás megjelölése legújabb WP/WC verzióval

5.5.8
* WooCommerce bookings kompatibilitás(tétel megjegyzésben fel lehet tüntetni a foglalás infókat és számla megjegyzést, automatizálást lehet beállítani azzal a feltétellel, hogy a rendelésben van e foglalás)
* Custom Order Numbers for WooCommerce kompatibilitás IPN kapcsolathoz
- Kompatibilitás megjelölése WC 6.2-vel

= 5.5.7 =
* Fizetettnek jelölés és kedvezményes rendelés AAM-el hibajavítása
* Kompatibilitás megjelölése legújabb WC verzióval

= 5.5.6 =
* Termékkategóriás feltételek működnek variálható termékkel is(több fióknál, megjegyzésnél, egyedi automatizálásnál)

= 5.5.5 =
* Termék beállítások hibajavítás
* Kompatibilitási hiba WooCommerce Order Status Manager bővítménnyel javítva(IPN-el kapcsolatos)
* Kompatibilitás megjelölése új WP és WC verziókkal

= 5.5.4 =
* Speciális karakter az országnévben nem generál hibát
* Fizetettnek jelölés működik a generate_invoice funkcióval

= 5.5.3 =
* Hibajavítás egyedi áfakulcs felülíráshoz százalékos értékeknél és termék kategória feltételnél

= 5.5.2 =
* Hibajavítás a WooCommerce levelekhez csatolt számlákkal és automatizálással kapcsolatban

= 5.5.1.2 =
* cURL buggal kapcsolatos figyelmeztetés elrejtése - a számlázz.hu megoldotta a problémát

= 5.5.1.1 =
* cURL buggal kapcsolatos figyelmeztetés

= 5.5.1 =
* cURL buggal kapcsolatos figyelmeztetés
* WC 5.7 kompatibilitás megjelölése

= 5.5 =
* Beállítható, hogy a csoportos műveleteknél melyik opciók látszódjanak(egyéb beállítások szekció)
* Csoportos műveleteknél a számla nyomtatása / letöltése opció a nyugtákat is letölti
* Javítva egy olyan bug, amikor kétszer kattintasz véletlen a rendelés frissítése gombra, akkor duplikált számlát generál automatán(WordPress / WooCommerce bug)
* Sztornózáskor a rendelés jegyzetekbe beírja az sztornózandó számla/nyugta linkjét, így onnan később is visszakereshető a sztornózott számla/nyugta
* WC 5.6 kompatibilitás megjelölése

= 5.4.9 =
* IPN hibajavítás

= 5.4.8 =
* Áfakulcs hibajavítás
* Nyugta e-mail csatolás hibajavítás

= 5.4.7 =
* Hibajavítás visszatérítéssel kapcsolatban
* Javítva egy olyan hiba, amikor néha 1-1 tétel 0 Ft-os lett a számlán
* Az EU VAT Assistant bővítmény kompatibilitás javítása: csak akkor használja, ha valid volt az adószám
* WC 5.5.2 és WP 5.8 kompatibilitás ellenőrzése

= 5.4.6 =
* IPN státusz módosítás kompatibilitás egyedi rendelés státuszokkal
* Díjbekérőt ki lehet törölni akkor is, ha már a számlázz.hu-n manuálisan ki lett törölve

= 5.4.5 =
* Új áfakulcsok(EUFAD37, KBAUK és hasonló őrültségek...)
* Az `eusAfa` paraméter támogatása, aminek a megadásával a külföldi áfakulccsal kiállított számlákat nem továbbítja a NAV rendszere felé. A könyvelési adatoknál lehet bekapcsolni(PRO verzió)

= 5.4.4.1 =
* Shortcode használható a számla és tétel megjegyzésekben

= 5.4.4 =
* Szállítólevelet is lehet csinálni az új egyedi automatizálás funkcióval
* Rendelés törlésekor letörli az elmentett PDF számlát is(nem sztornózza, csak a fájlt törli)

= 5.4.3 =
* Tétel elrejtése számlán és egyedi ár megadása termékeknél, ami csak a számlán látszik
* Ha angol az admin felület nyelve, de a weboldal magyar, akkor a számla letöltési link magyar lesz a manuálisan kiküldött WooCommerce e-mailekben

= 5.4.2 =
* Manuális számlakészítéskor fel lehet tölteni saját PDF fájlt számlagenerálás helyett(Opciók gombra kattints, ott van ez a lehetőség)
* Beállítások oldal php warning javítás
* WooCommerce 5.4.1 kompatibilitás megjelölése

= 5.4.1.3 =
* `wc_szamlazz_calculate_item_prices_args` filter
* Fióknál van termék kategória feltétel is
* Hibajavítás csoportos műveleteknél PHP 8.0-val

= 5.4.1.1 =
* Termék attribútum feltétel hibajavítás variálható terméknél

= 5.4.1 =
* Termék attribútum feltétel megjegyzés beállításánál és kategória feltétel javítása
* Speciális karakter a címben javítva

= 5.4 =
* Áfakulcs felülírás konfigurálása különböző feltétel szerint
* Csoportos számlakészítés fejlesztése
* Automatizálásnál lehet megadni több feltételt egyszerre
* Visszatérítés megjelenítése számlán
* IPN hibajavítás előlegszámlánál
* Jobban szem előtt lévő lejárt licensz figyelmeztetés

= 5.3.0.4 =
* Nyugtakészítés bugfix

= 5.3.0.3 =
* A nyugtára is lehet feltételes megjegyzést írni
* Woo Subscription bugfix

= 5.3.0.2 =
* Az "ingyenes rendeléshez nem kell számla" opciót a nyugtára is vonatkozik

= 5.3.0.1 =
* Bugfix az új automatizáláshoz

= 5.3 =
* Új beállítási lehetőségek automatikus számlakészítéshez - egyelőre beta funkcióként, a meglévő beállításokat nem módosítja.
* Beállítások link hozzáadása a WooCommerce kezdőoldalhoz, hogy ne kelljen mindig az Integráció menüig elnavigálni

= 5.2.9.1 =
* Hibajavítás az "Adószám mező mindig látszódjon" opcióhoz

= 5.2.9 =
* Adószám mező javítások
* Kategória szerinti könyvelési adatok javítása
* PHP warning javítás az előnézeten
* Egyedi számla logo beállítás hibajavítása
* WP 5.7 és WC 5.1 kompatibilitás ellenőrzése / megjelölése

= 5.2.8 =
* WooCommerce Subscriptions kompatibilitás fejlesztések
* Cím kitöltés javítása adószám alapján

= 5.2.7 =
* TEHK és TAHK áfakulcs kompatibilitás

= 5.2.6 =
* A számla megjegyzésben feltüntethető a szállítási cím({shipping_address}) és a vásárlói megjegyzés({customer_note}). További placeholder egyszerűen hozzáadható a wc_szamlazz_get_order_note_placeholders filterrel.
* A megjegyzés mezőben használt rövidkódok működnek a számlaértesítű levél címében és tárgyában is
* EU és EUK áfakulcsok átnevezése EUT-re és EUKT-re
* Kompatibilitás megjelölése legújabb WC verzióval

= 5.2.5 =
* Adószámot elfogadja kötőjelek nélkül is(mentéskor automatán hozzáadja a kötőjeleket)
* Új megjegyzés feltétel: számlázási ország

= 5.2.4 =
* Sztornózáskor a díjbekérőt is letörli automatikusan(opcionális)
* WooCommerce Advanced Quantity kompatibilitás javítása
* Manuális számlakészítéskor az Opciók gombra kattintva nem görget fel az oldal tetejére
* Minified admin és frontend JS

= 5.2.3 =
* WooCommerce Checkout Manager kompatibilitás javítása
* WooCommerce Advanced Quantity kompatibilitás javítása

= 5.2.2.1 =
* Egy PHP warning javítása
* Wordpress 5.6 kompatibilitás
* WooCommerce 4.8 kompatibilitás

= 5.2.2 =
* WooCommerce Currency Switcher by WooBeWoo kompatibilitás
* Bugfix max_input_vars-al kapcsolatban
* Számla megjegyzésnél feltételnek kiválasztható, hogy EU-n belüli, vagy kívüli-e a vásárló
* A wc_szamlazz_ipn_document_type filterrel módosítható, hogy a számlázz.hu milyen típusú dokumentumot küld IPN-en keresztül
* A bővítmény fájlméretének csökkentése
* Hibajavítás több fiók használata esetén

= 5.2.1 =
* Adószám mező kompatibilitás a Checkout Manager for WooCommerce bővítménnyel
* Egy potenciális PHP warning javítása
* Kompatibilitás megjelölése a legújabb WC verzióval(4.7.0)

= 5.2.0.1 =
* Hibajavítás az számla továbbítás funkcióhoz

= 5.2 =
* Termék beállításoknál kikapcsolható az automata számlakészítés: ha a rendelésben van az adott termék, nem készül számla automatán
* Translatepress kompatibilitás: a kiválasztott nyelv alapján készül a számla is
* Ha több fiókot használsz, működni fog a sztornózás manuális számlakészítés után
* Csatolható a sztornó számla a visszatérített rendelés e-mailhez
* Kompatibilitás a WooCommerce EU Vat Number 2.3.21+ verziókkal
* A rendelés előnézetben is látszódnak a számlák
* Hibajavítás csoportos számla letöltéshez és nyomtatáshoz
* Apróbb hibajavítások az előnézet funkciónál
* Fejlesztői módban az előnézeten látszik az XML fájl is(fejlesztéshez és supporthoz is hasznos)
* Javítás a számla megjegyzésben lévő sortöréshez
* Ha egy rendelésnél ki van kapcsolva a számlakészítés, nem látszódik feleslegesen a fizetettnek jelölés gomb
* Angol nyelvű lett a bővítmény, amihez mellékelve van a magyar fordítás
* WooCommerce 4.6.1 kompatibilitás megjelölése

= 5.1.3 =
* Kompatibilitás a megbízott számlakibocsátáshoz és önszámlázáshoz
* PHP Warning javítása automata sztornózásnál

= 5.1.2 =
* Hibajavítás a rendelési tétel attribútumok megjelenítésével kapcsolatban
* Kompatibilitás megjelölése legújabb WC verziókkal

= 5.1.1 =
* Adószám funkciók megfelelően működnek a fiókom oldalon is
* Az előnézeten látszik az adószám is
* Hibajavítás az előnézeten akkor, ha több tétel van a számlán
* PHP warning javítása belépett felhasználóknál pénztár űrlap elküldése után üres adószámmal

= 5.1.0.1 =
* Közösségi adószám bugfix

= 5.1 =
* Ha egyedi dátummal van megjelölve a teljesítettnek jelölés, akkor megfelelő dátumot tárol el az adatbázisban is
* Apróbb hibajavítások az automata adatkitöltés adószám alapján funkcióhoz
* Fizetettnek jelöléskor megfelelő dátumot ír ki a válaszban(oldal frissítés után eddig is jó volt)
* Hibajavítás adószám mező megjelenítésével kapcsolatosan
* Hibajavítás fixen beállított százalékos áfakulcsnál a negatív kedvezmény tételhez
* Kompatibilitás WooCommerce 4.4-el és WordPress 5.5-el

= 5.0.0.3 =
* Kompatibilitás régebbi WooCommerce verzióval

= 5.0.0.2 =
* Hibajavítás a WooCommerce Product Bundles kompatibilitással kapcsolatban

= 5.0.0.1 =
* Kompatibilitás javítása régebbi WC verziókkal

= 5.0 =
* Az adószám megadása után automatikusan ki tudja tölteni a pénztár űrlapot(PRO)
* A rendeléskezelőben egy kattintással fizetettnek lehet jelölni a fizetetlen számlákat(és látni, hogy melyik mikor lett fizetve)(PRO)
* Előnézet funkció: számlakészítés előtt meg tudod nézni, hogy hogyan fog kinézni a számla(PRO)
* Okosabban kezeli az áfakulcsokat ingyenes tételeknél(terméknél és szállításnál is)
* Beállítások az EU és EUK áfakulcs automatizálására
* Az üzenetek(például a bővítmény aktiválásakor, hibás számlakészítéskor stb...) átkerültek a WooCommerce Admin-ba, így nem keverednek a többi Wordpress üzenettel és csak a WooCommerce menüben látszódnak
* Az adószámnál ellenőrzi az adóalany ÁFA jellegét is(9. számjegy)
* Ha manuálisan van létrehozva a rendelés és kiválasztasz egy meglévő vásárlót, akkor kitölti az adószám mezőt is automatán(ha volt neki elmentve)
* Lehet megadni egyedi tétel megjegyzést és mennyiségi egységet a szállítási tételnek is
* Hibajavítás egy olyan bughoz, ami bizonyos PHP verziónál jött csak elő
* EU adószám kompatibilitás javítás
* A wc_szamlazz_xml_adoszam és wc_szamlazz_xml_adoszam_eu filterekkel könnyen módosítható az adószám
* Kompatibilitás megjelölése WooCommerce 4.3.0-val

= 4.8.1 =
* Az "adószám mező a mindig látszódik" beállítás használatakor csak Magyarországot kiválasztva látszódik a mező, így több értelme van
* WooCommerce Product Bundles kompatibilitás(az ingyenes tételek helyett a fő tétel megjegyzésébe írja bele a csomagban lévő termék nevét és mennyiségét)
* A `wc_szamlazz_pdf_file_name` filterbe bekerült a számla paraméter is, így a fájlnévben megjeleníthető a számla száma is

= 4.8.0.1 =
* Kompatibilitás javítása a HuCommerce bővítménnyel

= 4.8 =
* Csoportos műveletekben van egy új dokumentum készítés opció, amivel egyedi paraméterekkel(fizetési határidő, teljesítés ideje, fiók stb...) lehet csoportosan létrehozni dokumentumokat(PRO verzió része)
* A `wc_szamlazz_pdf_file_name` filterrel módosítható a generált PDF fájl neve(például ha a vásárló nevét, vagy rendelés számát szeretnéd megjeleníteni)
* WooCommerce EU Vat Number kompatibilitás

= 4.7.3.1 =
* Hibajavítás a WooCommerce Advanced Quantity kompatibilitással kapcsolatban
* Adószám lekérdezés eltárolja a címet is, nem csak a nevet

= 4.7.3 =
* Az adószám mező ellenőrzése mindig megtörténik(nem kell külön bekapcsolni)
* Az adószám mezőt meg lehet jeleníteni állandóan(alapértelmezetten csak akkor látszik, ha a vásárló megadott egy cégnevet)
* Az adószámos JS fájlt csak a pénztár és a fiókom oldalon tölti be
* Hibajavítás fixen beállított százalékos áfakulcsokhoz
* Ha már készült számla a rendeléshez, akkor az IPN nem állítja át a rendelés státuszt
* IPN kérelem után készült számla mellé is készül automatikusan szállítólevél
* Az IPN kérelmet akkor is naplózza, ha nincs bekapcsolva a fejlesztői mód
* Hibajavítás csoportos szállítólevél letöltéssel és nyomtatással kapcsolatban
* Kompatibilitás a WooCommerce Advanced Quantity bővítménnyel(mennyiségi egység megjelenítéséhez)

= 4.7.2 =
* A számlán megjelenő dátumoknál figyelembe veszi a WordPress beállításaiban megadott időzónát(éjfél utáni számlakészítéskor átcsúszhatott a kelt dátum az előző napra)

= 4.7.1.3 =
* Hibajavítás az IPN kapcsolatnál

= 4.7.1.2 =
* A beérkező IPN hívásnál beállítható, hogy pontosan milyen státuszba kerüljön a rendelés
* Kompatibilitás megjelölése legújabb WC verzióval

= 4.7 =
* Az adószám megjelenik a köszönöm oldalon, a kiküldött e-mailekben, a fiókom menüben(rendelések és címek oldalon).
* Az adószám módosítható a rendelés adatainál
* A csoportos műveleteknél szállítólevelet is lehet letölteni és nyomtatni
* A `wc_szamlazz_ipn_request_parameters` filterrel módosítható a beérkező IPN kérelem(például hogy az egyedi rendelésszámot le tudd cserélni az adatbázisban lévő ID-ra)
* A `wc_szamlazz_notes_conditions` és `wc_szamlazz_notes_conditions_values` filterekkel létrehozható egyedi feltétel a megjegyzésekhez
* Lehetőség van arra, hogy a csoportos számlaletöltéskor ne egy PDF fájlt generáljon, hanem egy ZIP fájlba becsomagolja a kiválasztott számlákat
* Beállítható a számlakép opció is
* Kompatibilitás megjelölése legújabb WC és WP verziókkal

= 4.6 =
* A fizetési módok elnevezése felülírható a számlán
* Kikapcsolható az automata számlakészítés bizonyos fizetési módoknál
* A megjegyzésnél az agent kulcs is kiválasztható feltételként
* A tétel megjegyzésben nem szerepel az "Utánrendelve" leírás
* Kiválasztható normál %-os áfakulcs felülírás a beállításokban
* Ha be van kapcsolva az automata számlakészítés, akkor a számlakészítő dobozban kiírja, hogy melyik státusznál fog automatán számla készülni
* Támogatás kiegészítő bővítményekhez
* A `wc_szamlazz_settings_fields` filterrel lehet hozzáadni egyedi beállításokat
* A számlakészítő doboz testreszabható a `wc_szamlazz_metabox_generate_before`, `wc_szamlazz_metabox_generate_after`, `wc_szamlazz_metabox_generate_options_before` és `wc_szamlazz_metabox_generate_options_after` action-ökkel
* Webhook hibajavítás
* PRO verzióban kikapcsolható az automata számlakészítés(egy hiba miatt mindig ment)
* Hibajavítás a Webhely Egészség menüben(jól mutatja, ha a fejlesztői mód bekapcsolva maradt)
