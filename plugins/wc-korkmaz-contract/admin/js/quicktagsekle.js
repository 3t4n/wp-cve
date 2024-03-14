;(function ($) {

	// Define translations for menu items
	var menuItemsTranslations = [
		["adi", "[adi]"],
		["soyadi", "[soyadi]"],
		["ad_soyad", "[adsoyad]"],
		["firma_adi", "[firmaadi]"],
		["firma_adresi", "[firmaadresi]"],
		["firma_tel", "[firmatelno]"],
		["firma_vergi", "[firmaverdaire]"],
		["firma_vergi_no", "[firmaverno]"],
		["sirket_bilgileri", "[sirketbilgileri]"],
		["alicisirket", "[alicisirket]"],
		["alicisirketvergino", "[alicisirketvergino]"],
		["alicisirketvergidairesi", "[alicisirketvergidairesi]"],
		["musteri_adres", "[adres]"],
		["musteri_telefon", "[telefon]"],
		["musteri_mail", "[email]"],
		["musteri_tc", "[tc]"],
		["satinalinan_urunler", "[satin_alinan_urunler]"],
		["sepet_toplami", "[sepettoplami]"],
		["kargo_ad_soyad", "[aliciadsoyad]"],
		["kargo_sirket_ismi", "[alicisirketismi]"],
		["kargo_adress", "[aliciadres]"],
		["bugun_tarih", "[tarih]"],
		["musteri_ip", "[ipadresibilgisi]"],
		["odemeyontemi", "[odemeyontemi]"]
	];

	// Generate menu items based on translations
	var generateMenuItems = function (editor) {
		return menuItemsTranslations.map(function (item) {
			var textKey = "korkmaz_contract." + item[0];
			return {
				text: tinymce.translate(textKey),
				onclick: function () {
					editor.insertContent(item[1]);
				}
			};
		});
	};

	tinymce.PluginManager.add('wdm_mce_button', function (editor) {
		editor.addButton('wdm_mce_button', {
			text: tinymce.translate("myplugin.title"),
			icon: false,
			type: 'menubutton',
			menu: generateMenuItems(editor)
		});
	});

	tinymce.addI18n("tr", {
		"myplugin.title": "Sözleşme Kısayolları",
		"korkmaz_contract.adi": "Adı",
		"korkmaz_contract.soyadi": "Soyadı",
		"korkmaz_contract.ad_soyad": "Ad Soyad",
		"korkmaz_contract.firma_adi": "Firmanızın İsmi",
		"korkmaz_contract.firma_adresi": "Firmanızın Adresi",
		"korkmaz_contract.firma_tel": "Firmanızın Tel No",
		"korkmaz_contract.firma_vergi": "Firmanızın Vergi Dairesi",
		"korkmaz_contract.firma_vergi_no": "Firmanızın Vergi No",
		"korkmaz_contract.sirket_bilgileri": "Müşteri Şirket Bilgileri",
		"korkmaz_contract.alicisirket": "Müşteri Şirket Adı",
		"korkmaz_contract.alicisirketvergino": "Müşteri Şirket Vergi No",
		"korkmaz_contract.alicisirketvergidairesi": "Müşteri Şirket Vergi Dairesi",
		"korkmaz_contract.musteri_adres": "Müşterinin Adresi",
		"korkmaz_contract.musteri_telefon": "Müşteri GSM Numarası",
		"korkmaz_contract.musteri_mail": "Müşteri Mail Adresi",
		"korkmaz_contract.musteri_tc": "Müşteri Tc Kimlik No",
		"korkmaz_contract.satinalinan_urunler": "Satın Alınan Ürünler",
		"korkmaz_contract.sepet_toplami": "Sepet Toplam Tutar",
		"korkmaz_contract.kargo_ad_soyad": "Farklı Teslimat Ad Soyad",
		"korkmaz_contract.kargo_sirket_ismi": "Farklı Teslimat Sirket İsmi(Varsa)",
		"korkmaz_contract.kargo_adress": "Farklı Teslimat Adres",
		"korkmaz_contract.bugun_tarih": "Sözleşme Tarihi",
		"korkmaz_contract.musteri_ip": "Müşteri Ip Adress",
		"korkmaz_contract.odemeyontemi": "Ödeme Yöntemi"
	});


	tinymce.addI18n("en", {
		"myplugin.title": "Contract Shortcode",
		"korkmaz_contract.adi": "Customer First Name",
		"korkmaz_contract.soyadi": "Customer Last Name",
		"korkmaz_contract.ad_soyad": "Customer First and Last Name",
		"korkmaz_contract.firma_adi": "Our Company",
		"korkmaz_contract.firma_adresi": "Our Company Adress",
		"korkmaz_contract.firma_tel": "Our Company Gsm",
		"korkmaz_contract.firma_vergi": "Our Company Tax Office",
		"korkmaz_contract.firma_vergi_no": "Our Company Tax Number",
		"korkmaz_contract.sirket_bilgileri": "Customer Company info",
		"korkmaz_contract.alicisirket": "Customer Company Name",
		"korkmaz_contract.alicisirketvergino": "Customer Company Tax Number",
		"korkmaz_contract.alicisirketvergidairesi": "Customer Company Tax Department",
		"korkmaz_contract.musteri_adres": "Customer Adress",
		"korkmaz_contract.musteri_telefon": "Customer Phone Number",
		"korkmaz_contract.musteri_mail": "Customer Email Adress",
		"korkmaz_contract.musteri_tc": "Customer Identity No",
		"korkmaz_contract.satinalinan_urunler": "Purchased Products",
		"korkmaz_contract.sepet_toplami": "Cart Total",
		"korkmaz_contract.kargo_ad_soyad": "Different Delivery Name",
		"korkmaz_contract.kargo_sirket_ismi": "Different Delivery Company(if available)",
		"korkmaz_contract.kargo_adress": "Different Delivery Adress",
		"korkmaz_contract.bugun_tarih": "Contract Date",
		"korkmaz_contract.musteri_ip": "Customer IP Adress",
		"korkmaz_contract.odemeyontemi": "Payment Method"
	});

})(jQuery);
