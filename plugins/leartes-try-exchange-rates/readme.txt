=== Leartes TRY Exchange Rates ===
Contributors: Leartes
Tags: döviz, dolar, euro, doviz kurları, turkish, lira, currency, exchange rates, TL, TRY, doviz kuru, widget, plugin, sidebar
Requires at least: 5.0
Tested up to: 6.3.1
Requires PHP: 7.4
Stable tag: 2.1
Version: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Gets TRY Exchange Rates from TCMB (Turkish Central Bank). Use as widget or Shortcode

== Description ==

= English =
* Displays the daily exchange rates from the Central Bank of Turkey (TCMB).
* Ability to display 12 different currency rates/TRY.
* No tables. All design handle by CSS.

Turkish Lira Exchange Rates are gathered from The Central Bank of Turkey.

* WPML Compatiable (English and Turkish translations included)
* Use as widget or shortcode
* WPBakery Page Builder compatible
* Elementor Website Builder compatible
* Rates are locally cached for a time.

For Theme Developers
* Filters available: "lbi_date_format" hook for default date format. "lbi_excache_time" hook for default cache duration.
* Usefull Functions: get_rates_data() gets raw data, get_rate($currency='USD',$the_rate = 'FS') gets single rate and exchange_rates($atts, $do_echo = false) displays rates table.


= Turkce =
* Turkiye Cumhuriyeti Merkez Bankasi gunluk doviz kurlarini gosterir,
* 12 degisik kurun Turk Lirasi karsisindaki degerini yayinlama olanagi,
* Tablolar olmadan tamami CSS ile hazirlandi,
* WPML Uyumlu. Turkce ve Ingilizce dil dosyalari paket icindedir, 
* Widget ve Shortcode olarak kullanılabilir,
* WPBakery Page Builder Entegrasyonu mevcuttur,
* Elementor Website Builder Entegrasyonu mevcuttur,
* Kurlar bir süreliğine lokal olarak bellekte tutulur.

Tema Geliştiriciler için
* Kullanılabilir Filtreler: "lbi_date_format" tarih formatı değiştirmek için kullanilir. "lbi_excache_time" varsayilan lokal bellek süresini değiştirmek için kullanilir.
* Kullanılabilir Fonksiyonlar: get_rates_data() ham verileri çeker, get_rate($currency='USD',$the_rate = 'FS') tek bir döviz cinsi için kur bilgisini alir ve exchange_rates($atts, $do_echo = false) kur tablosunu yazar.

== Screenshots ==


== Changelog ==
= 2.1 =
* The bank url address from which the rates are withdrawn has been updated. / Kurların çekildiği banka url adresi güncellendi.  

= 2.0 =
* Made compatible with Elementor Website Builder. / Elementor Website Builder entegrasyonu eklendi. 
* Data Timestamp & Data Source display options is fixed / Veri Zaman Damgası ve Veri Kaynağı görüntüleme seçenekleri düzeltildi.

= 1.3 =
* Made compatible with the current version of WordPress. / WordPress güncel sürümü ile uyumlu hale getirildi.

= 1.2 =
* WPBakery Page Builder Mapping Fixed / WPBakery Page Builder Entegrasyon Hatası Düzeltildi.

= 1.1 =
* Multilingual problem fixed. / Çoklu dil problemi düzeltildi.
* Fixed fatal errors in PHP versions under 5.4 / PHP 5.4 altı sürümlerde yaşanan hatalar giderildi

= 1.0 =
* Initial release. / İlk Sürüm

== Upgrade Notice ==
= 1.2 =
WPBakery Page Builder plugin users need to update. / WPBakery Page Builder Eklentisi Kullanıcıları için 

= 1.1 =
PHP < 5.4 users and multi-language users need to update. / PHP 5.4 altı ve çoklu dil kullananların güncelleme yapması gerekli.