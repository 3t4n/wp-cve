// This removes #_=_ that square can add to the redirect URL. This removes it simply for aesthetics reasons
if (window.location.hash === '#_=_') {
	window.location.hash = '';
}

const params = new URLSearchParams(window.location.search);

document.addEventListener('DOMContentLoaded', initPreviews);
document.addEventListener('DOMContentLoaded', clickContactForm);
document.addEventListener('DOMContentLoaded', recommendedProductsSectionEvents);
document.addEventListener('DOMContentLoaded', buttonPageListener);
document.addEventListener('DOMContentLoaded', setupMerchantLogo);

function clickContactForm() {
	const urlSearchParameters = new URLSearchParams(window.location.search);
	const parameters = Object.fromEntries(urlSearchParameters.entries());
	if ('open_help' in parameters) {
		const checkExist = setInterval(() => {
			if (document.querySelector('.eapps-form-button')) {
				const $form = document.querySelector('.eapps-form-floating-button, .eapps-form-floating-button-type-text, .eapps-form-floating-button-position-right, .eapps-form-button eapps-form-floating-button-visible');
				$form?.click();
				clearInterval(checkExist);
			}
		}, 100);
	}
}

function peachpay_initAdminColorInputGroup(inputGroupSelector) {
	const $colorPicker = document.querySelector(`${inputGroupSelector} input[type="color"]`);
	const $colorTextBox = document.querySelector(`${inputGroupSelector} input[type="text"]`);

	const syncColorPickerToColorTextBox = () => {
		$colorTextBox.value = $colorPicker.value;
	};

	const syncColorTextBoxToColorPicker = () => {
		$colorPicker.value = $colorTextBox.value;
	};

	$colorPicker.addEventListener('input', syncColorPickerToColorTextBox);
	$colorTextBox.addEventListener('input', syncColorTextBoxToColorPicker);
}

function initPreviews() {
	if (params.get('tab') === 'express_checkout' && params.get('section') === 'button') {
		initPreviewButton();
		initCheckoutButtonEventListeners();
	}

	if (params.get('tab') === 'express_checkout' && params.get('section') === 'branding') {
		peachpay_initAdminColorInputGroup('#peachpay_button_background_color');
		peachpay_initAdminColorInputGroup('#peachpay_button_text_color');
	}
}

function initPreviewButton() {
	const button = document.querySelector('#pp-button-preview');
	if (button) {
		button.style.width = 220 + 'px';

		const initialRadius = document.querySelector('#button_border_radius').value;
		button.style.borderRadius = initialRadius.toString() + 'px';
	}
}

function initCheckoutButtonEventListeners() {
	const floatButtonIcons = document.querySelectorAll('input[name=peachpay_express_checkout_button\\[floating_button_icon\\]]');
	for (const icon of floatButtonIcons) {
		icon.addEventListener('change', event => {
			if (event.target.checked) {
				updateIcon(event.target.value, true);
			}
		});

		if (icon.checked) {
			updateIcon(icon.value, true);
		}
	}

	const sizeInputStore = document.querySelector('#floating_button_size');
	if (sizeInputStore) {
		const button = document.querySelector('#peachpay-floating-button');
		sizeInputStore.addEventListener('input', event => {
			let val = event.target.value.toString();
			if (val === '') {
				val = '70';
			}

			button.style.width = val + 'px';
			button.style.height = val + 'px';
		});

		// Initialize style
		let val = sizeInputStore.value.toString();
		if (val === '') {
			val = '70';
		}

		button.style.width = val + 'px';
		button.style.height = val + 'px';
	}

	const iconSizeInputStore = document.querySelector('#floating_button_icon_size');
	if (iconSizeInputStore) {
		const icon = document.querySelector('#peachpay-floating-button i');
		iconSizeInputStore.addEventListener('input', event => {
			let val = event.target.value.toString();

			if (val === '') {
				val = '35';
			}

			icon.style.fontSize = val + 'px';
		});

		// Initialize style
		let val = iconSizeInputStore.value.toString();
		if (val === '') {
			val = '35';
		}

		icon.style.fontSize = val + 'px';
	}

	const buttonTextSetting = document.querySelector('#peachpay_button_text');
	buttonTextSetting?.addEventListener('input', event => updateText(event));

	const borderRadiusSetting = document.querySelector('#button_border_radius');
	borderRadiusSetting?.addEventListener('input', event => updateRadius(event));

	const iconSettings = document.querySelectorAll('input[id^="peachpay_button_icon_"]');
	for (const icon of iconSettings) {
		icon.addEventListener('click', event => {
			if (event.target.checked) {
				updateIcon(event.target.value, false);
			}
		});

		if (icon.checked) {
			updateIcon(icon.value, false);
		}
	}

	const buttonEffectSetting = document.querySelectorAll('input[id^="peachpay_button_effect_"]');
	for (const effect of buttonEffectSetting) {
		effect.addEventListener('change', setEffect);
	}

	const paymentMethodIconSetting = document.querySelector('#peachpay_payment_method_icons');
	const paymentMethodContainer = document.querySelector('.available-payment-icons');
	paymentMethodIconSetting?.addEventListener('input', event => {
		if (event.target.checked) {
			paymentMethodContainer.classList.remove('hide');
		} else {
			paymentMethodContainer.classList.add('hide');
		}
	});
}

function updateIcon(iconClass, isFloat) {
	const button = isFloat ? document.querySelector('#peachpay-floating-button i') : document.querySelector('.pp-button-preview-container i');
	if (button && iconClass) {
		button.removeAttribute('class');

		if (iconClass !== 'pp-icon-disabled') {
			button.classList.add(iconClass);
		}
	}
}

function updateRadius(event) {
	const button = document.querySelector('.pp-button-preview-container a');
	if (button) {
		button.style.borderRadius = event.target.value.toString() + 'px';
	}
}

const buttonTextTranslations = {
	'detect-from-page': 'Express checkout',
	ar: 'الخروج السريع',
	'bg-BG': 'експресно плащане',
	'bs-BA': 'ekspresno plaćanje',
	ca: 'Pagament exprés',
	'cs-CZ': 'Expresní pokladna',
	'da-DK': 'Hurtig betaling',
	'de-DE': 'Expresskauf',
	el: 'Γρήγορο ταμείο',
	'en-US': 'Express checkout',
	'es-ES': 'Chequeo rápido',
	'fr-FR': 'Acheter maintenant',
	'hi-IN': 'स्पष्ट नियंत्रण',
	it: 'Cassa rapida',
	ja: 'エクスプレスチェックアウト',
	'ko-KR': '익스프레스 체크아웃',
	'lb-LU': 'Express Kees',
	'nl-NL': 'Snel afrekenen',
	'pt-PT': 'Checkout expresso',
	'ro-RO': 'Cumpără cu 1-click',
	'ru-RU': 'Экспресс-касса',
	'sl-SI': 'Hiter Nakup',
	'sv-SE': 'snabbkassa',
	th: 'ชำระเงินด่วน',
	uk: 'Експрес -оплата',
	'zh-CN': '快速结帐',
	'zh-TW': '快速結帳',
};

function updateText(event) {
	const buttonText = document.querySelector('.pp-button-preview-container a span span');
	if (!buttonText) {
		console.log('HTML content error. Unable to find #pp-button-preview text.');
		return;
	}

	if (event.target.value === '') {
		buttonText.innerHTML = buttonTextTranslations['en-US'];
	} else {
		buttonText.innerHTML = event.target.value;
	}
}

function setEffect(e) {
	const button = document.querySelector('.pp-button-preview-container a');
	if (button) {
		button.classList.remove('effect-fade');
		button.classList.remove('effect-none');

		button.classList.add(e?.target.value);
	}
}

function recommendedProductsSectionEvents() {
	const urlParams = new URLSearchParams(window.location.search);
	if (urlParams.get('tab') !== 'express_checkout' || urlParams.get('section') !== 'product_recommendations') {
		return;
	}

	const relatedProductsToggle = document.querySelector('#peachpay_related_products_toggle');
	const autoSlider = document.querySelector('#peachpay_related_slider_toggle');

	if (relatedProductsToggle && autoSlider) {
		relatedProductsToggle.addEventListener('change', event => {
			event.target.checked ? autoSlider.disabled = false : (autoSlider.checked = false, autoSlider.disabled = true);
		});
	}

	const ocuProductImg = document.querySelector('.pp-ocu-product-img');
	const ocuProductName = document.querySelector('.pp-ocu-product-name');
	const ocuProductPrice = document.querySelector('.pp-ocu-product-price');
	const ocuPrimaryHeader = document.querySelector('#peachpay_one_click_upsell_primary_header');
	const ocuSecondaryHeader = document.querySelector('#peachpay_one_click_upsell_secondary_header');
	const ocuCustomDescription = document.querySelector('#peachpay_one_click_upsell_custom_description');
	const ocuAcceptButtonText = document.querySelector('#peachpay_one_click_upsell_accept_button_text');
	const ocuDeclineButtonText = document.querySelector('#peachpay_one_click_upsell_decline_button_text');

	if (ocuPrimaryHeader) {
		ocuPrimaryHeader.addEventListener('input', event => {
			!event.target.value ? document.querySelector('.pp-ocu-headline').innerHTML = 'Recommended for you' : document.querySelector('.pp-ocu-headline').textContent = event.target.value;
		});
	}

	if (ocuSecondaryHeader) {
		ocuSecondaryHeader.addEventListener('input', event => {
			event.target.value ? document.querySelector('.pp-ocu-sub-headline').classList.remove('hide') : document.querySelector('.pp-ocu-sub-headline').classList.add('hide');
			document.querySelector('.pp-ocu-sub-headline').textContent = event.target.value;
		});
	}

	if (ocuCustomDescription) {
		ocuCustomDescription.addEventListener('input', event => {
			event.target.value ? document.querySelector('.pp-ocu-product-description').classList.remove('hide') : document.querySelector('.pp-ocu-product-description').classList.add('hide');
			document.querySelector('.pp-ocu-product-description').innerHTML = event.target.value;
		});
	}

	if (ocuAcceptButtonText) {
		ocuAcceptButtonText.addEventListener('input', event => {
			!event.target.value ? document.querySelector('.pp-ocu-accept-button').textContent = 'Add to order' : document.querySelector('.pp-ocu-accept-button').textContent = event.target.value;
		});
	}

	if (ocuDeclineButtonText) {
		ocuDeclineButtonText.addEventListener('input', event => {
			!event.target.value ? document.querySelector('.pp-ocu-decline-button').textContent = 'No thanks' : document.querySelector('.pp-ocu-decline-button').textContent = event.target.value;
		});
	}

	(function ($) {
		$(() => {
			$('.pp-product-search').on('select2:select', async e => {
				const { data } = e.params;
				const response = await getOCUProductData(data.id);
				const responseData = await response.json();

				ocuProductImg ? ocuProductImg.src = responseData.data.ocu_product_img : '';
				ocuProductName ? ocuProductName.innerText = responseData.data.ocu_product_name : '';
				ocuProductPrice ? ocuProductPrice.innerHTML = responseData.data.ocu_product_price : '';
			});
		});
	})(jQuery);
}

async function getOCUProductData(ocu_product_id) {
	const formData = new FormData();

	formData.append('product_id', ocu_product_id);

	const response = await fetch('/?wc-ajax=pp-ocu-product', {
		method: 'POST',
		body: formData,
	});

	return response;
}

function buttonPageListener() {
	const productEnabler = document.querySelector('#peachpay_display_on_product_page');
	const productSettings = document.querySelectorAll('.pp-product-page-settings');

	const cartEnabler = document.querySelector('#peachpay_enabled_on_cart_page');
	const cartSettings = document.querySelectorAll('.pp-cart-page-settings');

	const checkoutEnabler = document.querySelector('#peachpay_enabled_on_checkout_page');
	const checkoutSettings = document.querySelectorAll('.pp-checkout-page-settings');

	if (productEnabler && productSettings.length) {
		productEnabler.addEventListener('change', event => {
			productSettings.forEach($section => {
				event.target.checked ? $section.classList.remove('hide') : $section.classList.add('hide');
			});
		});
	}

	if (cartEnabler && cartSettings.length) {
		cartEnabler.addEventListener('change', event => {
			cartSettings.forEach($section => {
				event.target.checked ? $section.classList.remove('hide') : $section.classList.add('hide');
			});
		});
	}

	if (checkoutEnabler && checkoutSettings.length) {
		checkoutEnabler.addEventListener('change', event => {
			checkoutSettings.forEach($section => {
				event.target.checked ? $section.classList.remove('hide') : $section.classList.add('hide');
			});
		});
	}
}

function setupMerchantLogo() {
	if (!wp || !wp.media) {
		return;
	}

	const $selectButton = document.querySelector('#pp-merchant-logo-select');
	const $removeButton = document.querySelector('#pp-merchant-logo-remove');
	const $merchantImg = document.querySelector('#pp-merchant-logo-img');
	const $merchantImgField = document.querySelector('#pp-merchant-logo-img-field');
	if (!$selectButton || !$removeButton || !$merchantImg || !$merchantImgField) {
		return;
	}

	$selectButton.addEventListener('click', () => {
		const imageFrame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image',
			},
			multiple: false,
		});

		imageFrame.on('select', () => {
			const attachment = imageFrame.state().get('selection').first().toJSON();
			$removeButton.classList.remove('hide');
			$merchantImg.src = attachment.url;
			$merchantImgField.value = attachment.id;
		});

		imageFrame.open();
	});

	$removeButton.addEventListener('click', () => {
		$removeButton.classList.add('hide');
		$merchantImg.removeAttribute('src');
		$merchantImgField.value = '';
	});
}
