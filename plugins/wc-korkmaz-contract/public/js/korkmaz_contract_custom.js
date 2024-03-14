jQuery(document).ready(function ($) {

	var template1;
	var template2;
	var $birinciModal = $('#modalContentbir');
	var $ikinciModal = $('#modalContentiki');
	var $birinci_sozlesme = $('#billing_satis_sozlesme');
	var $ikinci_sozlesme = $('#billing_mesafeli_sozlesme');


	$.post(ajaxurl + '?action=metin_getir').done(function (data) {
		var response = JSON.parse(data);
		template1 = response.birinci;
		template2 = response.ikinci;
		modalIcerigiGuncelle();
	});


	// Formdaki her değişiklikte güncelleme yapma
	$('form.checkout').on('input change', 'input, select', function () {
		modalIcerigiGuncelle();
	});

	function degeriAl(fields, name, alan) {
		var sonuc = alanDegeriniAl(fields, alan) || alanDegeriniAl(fields, name);
		return sonuc || "";
	}


	function alanDegeriniAl(fields, alan) {
		if (alan && alan.startsWith("#")) {
			alan = alan.substring(1);
		}
		var localizedValue = window.korkmaz_var && window.korkmaz_var[alan];
		if (localizedValue && localizedValue.startsWith("#")) {
			localizedValue = localizedValue.substring(1);
		}
		for (var i = 0; i < fields.length; i++) {
			if (fields[i].name === (localizedValue || alan)) {
				return fields[i].value;
			}
		}
	}

	function replaceAll(template, search, replacement) {
		var target = template;
		var regexSearch = new RegExp(search, 'g');
		return target.replace(regexSearch, replacement);
	}

	function modalIcerigiGuncelle() {
		var fields = $('form.checkout').serializeArray();
		var musteriAdi = degeriAl(fields, '#billing_first_name', 'alan_1');
		var musteriSoyadi = degeriAl(fields, '#billing_last_name', 'alan_2');

		var musteriSirketadi = degeriAl(fields, '#billing_company', 'alan_3');
		var musteriSirketvno = degeriAl(fields, '#billing_vergi_nosu', 'alan_4');
		var musteriSirketvd = degeriAl(fields, '#billing_vergi_dairesi', 'alan_5');

		var musteriAdres1 = degeriAl(fields, '#billing_address_1', 'alan_6');
		var musteriAdres2 = degeriAl(fields, '#billing_address_2', 'alan_7');
		var musteriSehir = degeriAl(fields, '#select2-billing_state-container', 'alan_8');
		var musteriPostakodu = degeriAl(fields, '#billing_postcode', 'alan_9');
		var musteriUlke = degeriAl(fields, '#select2-billing_country-container', 'alan_10');


		var musteriIlce = degeriAl(fields, '#billing_city', 'alan_11');
		var musteriEmail = degeriAl(fields, '#billing_email', 'alan_12');
		var musteriTelefon = degeriAl(fields, '#billing_phone', 'alan_13');
		var musteriTc = degeriAl(fields, '#shipping_tc', 'alan_14');


		var alan_urunlerigoster = (typeof korkmaz_var.alan_15 !== 'undefined') ? korkmaz_var.alan_15 : 'table.woocommerce-checkout-review-order-table';
		var checkout_review_table = jQuery(alan_urunlerigoster).clone();
		checkout_review_table.find('td, th').removeAttr('style');
		checkout_review_table.find('img').css('max-width', '75px');
		jQuery('#urunbilgileri').text(checkout_review_table.prop('outerHTML'));


		var musteriUrunler = jQuery('#urunbilgileri').text();
		var musteriSepetToplam = ciftSeciciVeriAl('#sepettoplami', 'alan_16');
		var musteriOdemeyontemi = jQuery("input[name=payment_method]:checked").parent().find("label").text()


		var musteriFadresisim = degeriKontrolEtveAl(fields, '#shipping_first_name', '#billing_first_name', 'alan_17');
		var musteriFadressoyisim = degeriKontrolEtveAl(fields, '#shipping_last_name', '#billing_last_name', 'alan_18');
		var musteriFadressirketadi = degeriKontrolEtveAl(fields, '#shipping_company', '#billing_company', 'alan_19');
		var musteriFadresadres1 = degeriKontrolEtveAl(fields, '#shipping_address_1', '#billing_address_1', 'alan_20');
		var musteriFadresadres2 = degeriKontrolEtveAl(fields, '#shipping_address_2', '#billing_address_2', 'alan_21');
		var musteriFadresilce = degeriKontrolEtveAl(fields, '#shipping_city', '#billing_city', 'alan_22');
		var musteriFadrespostakodu = degeriKontrolEtveAl(fields, '#shipping_postcode', '#billing_postcode', 'alan_23');
		var musteriFfadresulke = degeriKontrolEtveAl(fields, '#select2-shipping_country-container', '#select2-billing_country-container', 'alan_24');
		var musteriFadressehir = degeriKontrolEtveAl(fields, '#select2-shipping_state-container', '#select2-billing_state-container', 'alan_25');


		var bizimFirmaAdi = jQuery("#firmaadi").val();
		var bizimFirmaAdres = jQuery("#firmaadresi").val();
		var bizimFirmaGsm = jQuery("#firmatelno").val();
		var bizimFirmaVd = jQuery("#firmaverdaire").val();
		var bizimFirmaVno = jQuery("#firmaverno").val();

		var mip = jQuery("#musteri_ipadresi").val();
		var meip = jQuery("#musteri_external_ip").val();


		var currentDateTime = new Date();
		var year = currentDateTime.getFullYear();
		var month = ("0" + (currentDateTime.getMonth() + 1)).slice(-2);  // Aylar 0'dan başlar, bu yüzden 1 ekledik.
		var day = ("0" + currentDateTime.getDate()).slice(-2);
		var hours = ("0" + currentDateTime.getHours()).slice(-2);
		var minutes = ("0" + currentDateTime.getMinutes()).slice(-2);
		var seconds = ("0" + currentDateTime.getSeconds()).slice(-2);

		var tarihSaat = day + "-" + month + "-" + year + " " + hours + ":" + minutes + ":" + seconds;


		if (template1) {
			var guncellenmisMetin = template1;
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[adsoyad\\]", musteriAdi + " " + musteriSoyadi);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[adi\\]", musteriAdi);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[soyadi\\]", musteriSoyadi);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[firmaadi\\]", bizimFirmaAdi);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[firmaadresi\\]", bizimFirmaAdres);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[firmatelno\\]", bizimFirmaGsm);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[firmaverdaire\\]", bizimFirmaVd);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[firmaverno\\]", bizimFirmaVno);


			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[alicisirket\\]", musteriSirketadi);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[alicisirketvergino\\]", musteriSirketvno);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[alicisirketvergidairesi\\]", musteriSirketvd);


			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[sirketbilgileri\\]", musteriSirketadi + " " + musteriSirketvno + " " + musteriSirketvd);


			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[adres\\]", musteriAdres1 + " " + musteriAdres2 + " " + musteriPostakodu + " " + musteriIlce + " " + musteriSehir + " " + musteriUlke);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[telefon\\]", musteriTelefon);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[email\\]", musteriEmail);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[tc\\]", musteriTc);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[satin_alinan_urunler\\]", musteriUrunler);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[sepettoplami\\]", musteriSepetToplam);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[aliciadsoyad\\]", musteriFadresisim + " " + musteriFadressoyisim);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[alicisirketismi\\]", musteriFadressirketadi);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[aliciadres\\]", musteriFadresadres1 + " " + musteriFadresadres2 + " " + musteriFadresilce + " " + musteriFadrespostakodu + " " + musteriFfadresulke + " " + musteriFadressehir);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[ipadresibilgisi\\]", mip + " " + meip);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[odemeyontemi\\]", musteriOdemeyontemi);
			guncellenmisMetin = replaceAll(guncellenmisMetin, "\\[tarih\\]", tarihSaat);

			$birinciModal.html(guncellenmisMetin);
			$birinci_sozlesme.html(guncellenmisMetin);
		}


		if (template2) {
			var guncellenmisMetiniki = template2;
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[adsoyad\\]", musteriAdi + " " + musteriSoyadi);


			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[adi\\]", musteriAdi);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[soyadi\\]", musteriSoyadi);

			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[firmaadi\\]", bizimFirmaAdi);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[firmaadresi\\]", bizimFirmaAdres);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[firmatelno\\]", bizimFirmaGsm);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[firmaverdaire\\]", bizimFirmaVd);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[firmaverno\\]", bizimFirmaVno);


			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[alicisirket\\]", musteriSirketadi);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[alicisirketvergino\\]", musteriSirketvno);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[alicisirketvergidairesi\\]", musteriSirketvd);


			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[sirketbilgileri\\]", musteriSirketadi + " " + musteriSirketvno + " " + musteriSirketvd);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[adres\\]", musteriAdres1 + " " + musteriAdres2 + " " + musteriPostakodu + " " + musteriIlce + " " + musteriSehir + " " + musteriUlke);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[telefon\\]", musteriTelefon);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[email\\]", musteriEmail);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[tc\\]", musteriTc);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[satin_alinan_urunler\\]", musteriUrunler);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[sepettoplami\\]", musteriSepetToplam);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[aliciadsoyad\\]", musteriFadresisim + " " + musteriFadressoyisim);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[alicisirketismi\\]", musteriFadressirketadi);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[aliciadres\\]", musteriFadresadres1 + " " + musteriFadresadres2 + " " + musteriFadresilce + " " + musteriFadrespostakodu + " " + musteriFfadresulke + " " + musteriFadressehir);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[ipadresibilgisi\\]", mip + " " + meip);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[odemeyontemi\\]", musteriOdemeyontemi);
			guncellenmisMetiniki = replaceAll(guncellenmisMetiniki, "\\[tarih\\]", tarihSaat);

			$ikinciModal.html(guncellenmisMetiniki);
			$ikinci_sozlesme.html(guncellenmisMetiniki);
		}


	}

	function degeriKontrolEtveAl(fields, selector, backupSelector, alanId) {
		var deger = degeriAl(fields, selector, alanId);
		if (!deger) { // eğer değer boşsa
			deger = degeriAl(fields, backupSelector, alanId); // yedek değeri al
		}
		return deger;
	}

	function ciftSeciciVeriAl(selector1, selector2) {
		// Selector2'yi korkmaz_var öneki ile birleştir
		var globalVariableName = 'korkmaz_var.' + selector2;

		// Eğer global değişkende değer var ve boş değilse, bu değeri seçici olarak kullan
		if (window[globalVariableName] && window[globalVariableName].trim() !== "") {
			var selectorFromGlobal = window[globalVariableName];
			var valueFromGlobalSelector = jQuery(selectorFromGlobal).val();

			if (valueFromGlobalSelector && valueFromGlobalSelector.trim() !== "") {
				return valueFromGlobalSelector;
			}
		}

		// Eğer global değişkende değer yoksa veya boşsa selector1'le belirtilen elementin metnini al
		var value1 = jQuery(selector1).val();

		if (value1 && value1.trim() !== "") {
			return value1;
		}

		// Eğer her iki seçenekte de değer yoksa boş string döndür
		return '';
	}

	// Modal ayarları
	const myModal = new HystModal({
		linkAttributeName: 'data-hystmodal',
		catchFocus: true,
		waitTransitions: true,
		closeOnEsc: false,
		beforeOpen: function (modal) {
			// console.log('Modal açılmadan önce mesaj');
			// console.log(modal);
		},
		afterClose: function (modal) {
			// console.log('Modal kapatıldıktan sonra mesaj');
			// console.log(modal);
		},
	});


	musteriTipiKontrol();


	$('input[name="musteri_tipi"]').change(function () {
		musteriTipiKontrol();
	});

	function musteriTipiKontrol() {
		if ($('input[name="musteri_tipi"]').length == 0) {
        return;
    }
    
		var seciliTip = $('input[name="musteri_tipi"]:checked').val();

		if (seciliTip == 'bireysel') {
			$('#shipping_tc_field').show(); // TC Kimlik No alanını göster
			$('#shipping_tc_field input').attr('required', true);
			$('#shipping_tc_field .optional').removeClass('optional').addClass('required').html('<abbr class="required" title="gerekli">*</abbr>');

			$('#billing_company_field,#billing_vergi_dairesi_field, #billing_vergi_nosu_field').hide(); // Vergi Dairesi ve Vergi Numarası alanlarını sakla
			$('#billing_company_field,#billing_vergi_dairesi_field input, #billing_vergi_nosu_field input').removeAttr('required');
			$('#billing_company_field .required,#billing_vergi_dairesi_field .required, #billing_vergi_nosu_field .required').removeClass('required').addClass('optional').text('isteğe bağlı');
		} else {
			$('#shipping_tc_field').hide(); // TC Kimlik No alanını sakla
			$('#shipping_tc_field input').removeAttr('required');
			$('#shipping_tc_field .required').removeClass('required').addClass('optional').text('isteğe bağlı');

			$('#billing_company_field,#billing_vergi_dairesi_field, #billing_vergi_nosu_field').show(); // Vergi Dairesi ve Vergi Numarası alanlarını göster
			$('#billing_company_field,#billing_vergi_dairesi_field input, #billing_vergi_nosu_field input').attr('required', true);
			$('#billing_company_field .optional,#billing_vergi_dairesi_field .optional, #billing_vergi_nosu_field .optional').removeClass('optional').addClass('required').html('<abbr class="required" title="gerekli">*</abbr>');
		}
	}


});




