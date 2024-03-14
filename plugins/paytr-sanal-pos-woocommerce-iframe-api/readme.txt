=== PayTR Sanal POS WooCommerce - iFrame API ===
Contributors: paytrteknik
Tags: PayTR, checkout, ödeme, WooCommerce, sanal pos
Stable tag: 2.0.4
Requires at least: 4.4
Requires PHP: 5.6
Tested up to: 5.8.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

PayTR üyeliğiniz ile WooCommerce üzerinden ödeme almanız için gerekli altyapı.

== Description ==

PayTR Sanal POS ve Ödeme Çözümleri, web sitesi sahiplerinin en hızlı ve en kolay şekilde web sitelerinden güvenli online ödemeler almalarına imkan tanıyan bir servistir. Aidat ücreti bulunmayan PayTR, web sitelerine kolayca entegre edilerek çok kısa sürede kullanıma açılabilmektedir.

[PayTR Ödeme ve Elektronik Para Kuruluşu A.Ş.](https://www.paytr.com/)

= PayTR Nasıl Çalışır? =

PayTR ile ödeme süreci aşağıdaki gibi işler:

1. Müşteri ürünün/hizmetin sergilendiği web sitesine ulaşır.
1. Satın almak istediği ürünü/hizmeti belirler.
1. Ödeme formunu sitede doldurur "veya" ortak ödeme sayfasına yönlenir.
1. PayTR'a herhangi bir üyelik gerçekleştirmeden ödemesini tamamlar.
1. Ödeme işlemi güvenlik alt yapısı tarafından doğrulanarak onaylanır.
1. Web sitesine ödemenin güvenli ve başarılı olduğu bilgisi verilir.
1. Ürün/hizmet web sitesi tarafından müşteriye sunulur.

= PayTR'ın Avantajları Nelerdir? =

PayTR ödeme çözümlerini tercih etmeniz için birkaç neden:

* Çalışma seçenekleri uygundur, aidat veya gizli ücretler yoktur.
* Entegrasyon süreci kolay ve hızlı işler, hazır modüller ve örnek kodlama sunulur.
* Ödeme güvenliği sağlanır; mağazalar ve müşterileri sahtecilikten korunur.
* Üye işyerleri "Ertesi İş Günü" ödeme alma avantajından faydalanır.
* Ödeme sayfasını sitenizin tasarımına göre özelleştirebilirsiniz.
* Mobil uyumlu ödeme sayfaları ile platform bağımsız ödeme alabilirsiniz.

= Gereksinimler =
* WooCommerce eklentisi ile birlikte PayTR’da bir mağazanız olması gerekmektedir.

== Installation ==
1. İndirdiğiniz dosyadan çıkan klasörü "/wp-content/plugins/" klasörüne kopyalayın.
1. Eklentiler menüsünden **PayTR - WooCommerce Eklentisi**ni etkinleştirin.
1. WooCommerce menüsünden **Ayarlar > Ödemeler** sekmesini açın.
1. **PayTR Ödeme Alt Yapısı** eklentisini etkinleştirin, **Yönet** veya **Ayarla** butonuna tıklayın.
1. Açılan sayfada gerekli düzenlemeleri yaparak kullanmaya başlayın.

**Mağaza No**, **Mağaza Parola** ve **Mağaza Gizli Anahtar** girilmesi zorunludur. Bu bilgilere; 
**[PayTR Mağaza Paneli > Bilgi](https://www.paytr.com/magaza/bilgi)** sayfasında bulunan **API Entegrasyon Bilgileri** alanından ulaşabilirsiniz.

== Changelog ==

= 2.0.4 =
* Update - iFrameResizer.js dosyası güncellendi.
* Update - iFrame tag parametre güncellendi.
* Update - Küçük düzeltmeler yapıldı.
* Update - Sürüm uyumluluğu sağlandı.

= 2.0.3 =
* Update - Sepet içeriği fiyat bilgisi birim adet olarak değiştirildi.
* Update - Küçük düzeltmeler yapıldı.
* Update - Sürüm uyumluluğu sağlandı.

= 2.0.2 =
* Update - payTRiFrameResizer.js güncellendi.

= 2.0.0 =
* Tweak - WooCommerce versiyon 3.0 altındaki sürüm desteği kaldırıldı.
* Tweak - Wordpress versiyon 4.4 altındaki sürüm desteği kaldırıldı.
* Update - Açıklamalar güncellendi.
* Update - payTRiFrameResizer.js güncellendi.
* Add - Başarılı ödemeler için sipariş durumu seçilebilir.
* Add - Taksitli ödemelerde vade farkı siparişe eklenebilir.
* Add - İngilizce dil desteği.
* Add - İade işlemleri sipariş detay sayfasından yapılabilir.

= 1.4.6 =
* Update - Logo güncellendi.

= 1.4.5 =
* Update - Logo güncellendi.

= 1.4.4 =
* Update - payTRiFrameResizer.js güncellendi.

= 1.4.3 =
* Update - payTRiFrameResizer.js güncellendi.

= 1.4.1 =
* Fix - Logo boyutunun çok büyük görünmesi sorunu düzeltildi.

= 1.4.0 =
* Update - Min. Wordpress 4.0 ve WooCommerce 2.5 sürüm uyumluluğu iyileştirildi.

= 1.3.0 =
* Update - Sipariş notu açıklamaları güncellendi.

= 1.2.0 =
* New - Logo kaldırma seçeneği eklendi.

= 1.1.0 =
* New - Güncel açıklama yazıları.

= 1.0.0 =
* Initial release.