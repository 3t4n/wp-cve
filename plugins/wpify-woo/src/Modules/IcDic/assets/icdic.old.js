import $ from 'jquery';

const checkbox = document.querySelector('#company_details');
const icFieldInput = document.getElementById('billing_ic');
const dicFieldInput = document.getElementById('billing_dic');
const dicDphFieldInput = document.getElementById('billing_dic_dph');

const updateIcDicFields = () => {
	const autofill = document.querySelector('#wpify-woo-ares-autofill');
	const dicDph = document.querySelector('#billing_dic_dph_field');
	const country = document.querySelector('#billing_country');

	if (autofill) {
		if (country && country.value === 'CZ') {
			autofill.style.display = 'block';
		} else {
			autofill.style.display = 'none';
		}
	}

	if (country && country.value !== 'SK') {
		dicDph.style.display = 'none';
	} else if (country && country.value === 'SK') {
		if (checkbox && checkbox.checked === false) {
			dicDph.style.display = 'none';
		} else {
			dicDph.style.display = 'block';
		}
	}

	if (wpifyWooIcDic.changePlaceholder) {
		if (country && country.value === 'SK') {
			dicDphFieldInput.placeholder = country.value + '123456789';
			dicFieldInput.placeholder = '123456789';
		} else {
			dicFieldInput.placeholder = country.value + '123456789';
		}
		icFieldInput.placeholder = '123456789';
	}

	updateRequiredFields();
};

const showFields = () => {
	const fields = Array.from(document.getElementsByClassName('wpify-woo-ic-dic__company_field'));
	const country = document.querySelector('#billing_country');

	fields.forEach((item) => {
		if (item.id === 'billing_dic_dph_field' && country.value !== 'SK') {
			item.style.display = 'none';
		} else {
			item.style.display = 'block';
		}
	});
};

const setAsRequired = (field) => {
	const span = field.querySelector('label span');
	field.classList.add('validate-required');
	if (span) {
		span.className = 'required';
		span.innerText = '*';
	}
};

const setAsOptional = (field) => {
	const span = field.querySelector('label span');
	field.classList.remove('validate-required');
	if (span) {
		span.className = 'optional';
		span.innerText = wpifyWooIcDic.optionalText;
	}
};

const updateRequiredFields = () => {
	const companyFieldInput = document.getElementById('billing_company');
	const companyField = document.getElementById('billing_company_field');
	const icField = document.getElementById('billing_ic_field');

	if (wpifyWooIcDic.requireCompany) {
		if (checkbox && checkbox.checked) {
			setAsRequired(companyField);
		} else {
			setAsOptional(companyField);
		}
	}

	if (wpifyWooIcDic.requireVatFields !== null) {
		if ((checkbox && checkbox.checked && wpifyWooIcDic.requireVatFields === 'if_checkbox')
			|| (companyFieldInput.value !== '' && wpifyWooIcDic.requireVatFields === 'if_company')) {
			setAsRequired(icField);
		} else {
			setAsOptional(icField);
		}
	}
};

if (checkbox) {
	let fields = Array.from(document.getElementsByClassName('wpify-woo-ic-dic__company_field'));
	let inputs = Array.from($('.wpify-woo-ic-dic__company_field input'));

	let filterInputs = inputs.filter(function (currentElement) {
		return currentElement !== document.getElementById('wpify-woo-icdic__ares-submit');
	});

	window.addEventListener('load', (event) => {
		if (icFieldInput.value || dicFieldInput.value) {
			checkbox.checked = true;
			showFields();
			updateRequiredFields();
		}

		if (checkbox.checked === false) {
			fields.forEach((item) => item.style.display = 'none');
			filterInputs.forEach((item) => item.value = '');
		}
	});

	checkbox.addEventListener('change', (event) => {
		if (event.target.checked) {
			showFields();
			updateRequiredFields();
		} else {
			fields.forEach((item) => item.style.display = 'none');
			filterInputs.forEach((item) => item.value = '');
			updateRequiredFields();
		}
	});
}

if (wpifyWooIcDic.requireVatFields !== null && wpifyWooIcDic.requireVatFields === 'if_company') {
	const companyFieldInput = document.getElementById('billing_company');

	companyFieldInput.addEventListener('change', function () {
		updateRequiredFields();
	});
}

const aresShowBtn = document.getElementById('wpify-woo-icdic__ares-autofill-button');

if (aresShowBtn) {
	aresShowBtn.addEventListener('click', function (e) {
		e.preventDefault();
		document.getElementsByClassName('wpify-woo-icdic__ares-autofill')[0].style.display = 'block';
	});
}

const aresBtn = document.getElementById('wpify-woo-icdic__ares-submit');

if (aresBtn) {
	document.getElementById('ares_in').value = icFieldInput.value;
	icFieldInput.addEventListener('keyup', function () {
		document.getElementById('ares_in').value = icFieldInput.value;
	});
	aresBtn.addEventListener('click', function () {
		const ino = document.getElementById('ares_in').value;
		return fetch(`${wpifyWooIcDic.restUrl}/icdic?in=${ino}`)
			.then(res => {
				if (!res.ok) {
					return res.json().then(json => {
						document.getElementById('wpify-woo-icdic__ares-result').innerHTML = json.message;
					});
				}

				res.json().then(json => {
					const details = json.details;

					Object.keys(details).forEach(function (key) {
						const element = document.getElementById(key);

						if (element) {
							element.value = details[key];
							element.dispatchEvent(new Event('change'));
						}
					});

					const checkbox = document.getElementById('company_details');

					if (checkbox) {
						checkbox.checked = true;
					}

					document.getElementById('wpify-woo-icdic__ares-result').innerHTML = '';
					if (wpifyWooIcDic.position !== 'after_ic_field') {
						document.getElementsByClassName('wpify-woo-icdic__ares-autofill')[0].style.display = 'none';
					}

					showFields();
				});
			})
			.catch(console.error);
	});
}

const removeIcErrorMessage = () => {
	Array.from(document.querySelectorAll('.wpify-woo__ic-error')).forEach(function (item) {
		item.parentNode.removeChild(item);
	});
};

icFieldInput.addEventListener('change', function () {
	const icFieldValue = icFieldInput.value.replace(/\D/g, '');
	icFieldInput.value = icFieldValue;

	if (document.getElementsByClassName('wpify-woo-ic--validate').length) {
		const countryField = document.getElementById('billing_country');

		if (countryField.value !== 'CZ') {
			removeIcErrorMessage();
			return;
		}

		if (!icFieldValue) {
			removeIcErrorMessage();
			return false;
		}

		console.log(icFieldInput);
		icFieldInput.parentNode.classList.add('loading');

		return fetch(`${wpifyWooIcDic.restUrl}/icdic?in=${icFieldValue}`)
			.then(res => {
				if (!res.ok) {
					return res.json().then(json => {
						icFieldInput.parentNode.classList.remove('loading');
						removeIcErrorMessage();
						const el = document.createElement('div');
						el.innerHTML = json.message;
						el.classList.add('wpify-woo__ic-error');
						icFieldInput.parentNode.insertBefore(el, icFieldInput.nextSibling);
						document.getElementById('wpify-woo-icdic__ares-result').innerHTML = json.message;
					});
				}

				res.json().then(json => {
					icFieldInput.parentNode.classList.remove('loading');
					removeIcErrorMessage();
					dicFieldInput.value = json.details.billing_dic;
					document.getElementById('billing_company').value = json.details.billing_company;
					document.getElementById('billing_address_1').value = json.details.billing_address_1;
					document.getElementById('billing_city').value = json.details.billing_city;
					document.getElementById('billing_postcode').value = json.details.billing_postcode;

					if (dicFieldInput.value) {
						validateDic(dicFieldInput, countryField.value);
					}
				});
			})
			.catch(console.error);
	}
});

const removeViesErrorMessage = () => {
	Array.from(document.querySelectorAll('.wpify-woo__vies-error')).forEach(function (item) {
		item.parentNode.removeChild(item);
	});
};

const validateDic = (dicInput, countryValue) => {
	let dicValue = dicInput.value.replace(/[^a-zA-Z0-9]/g, '');

	if (dicInput.value) {
		if (dicValue.match(/[a-z]/)) {
			dicValue = dicValue.toUpperCase();
		}

		if (!dicValue.match(/^[A-Z]/)) {
			dicValue = countryValue + dicValue;
		}

		dicInput.value = dicValue;
	}

	if (document.getElementsByClassName('wpify-woo-vies--validate').length) {
		if (!dicValue) {
			removeViesErrorMessage();
			return false;
		}

		dicInput.parentNode.classList.add('loading');

		return fetch(`${wpifyWooIcDic.restUrl}/icdic-vies?in=${dicValue}`)
			.then(res => {
				if (!res.ok) {
					return res.json().then((json) => {
						removeViesErrorMessage();
						const el = document.createElement('div');
						el.innerHTML = json.message;
						el.classList.add('wpify-woo__vies-error');
						dicInput.parentNode.insertBefore(el, dicInput.nextSibling);
					});
				}

				return res.json().then((json) => {
					removeViesErrorMessage();
				});
			})
			.finally(() => {
				dicInput.parentNode.classList.remove('loading');
			})
			.catch(console.error);
	}
};

dicFieldInput.addEventListener('change', function () {
	const country = document.querySelector('#billing_country');

	if (country && country.value === 'SK') {
		dicFieldInput.value = dicFieldInput.value.replace(/\D/g, '');
	} else {
		validateDic(dicFieldInput, country.value);
	}

});

dicDphFieldInput.addEventListener('change', function () {
	const country = document.querySelector('#billing_country');

	if (country && country.value === 'SK') {
		validateDic(dicDphFieldInput, country.value);
	}
});

let typingTimer;

$('body').on('keyup', '#billing_dic,#billing_dic_dph,#company_details', function () {
	clearTimeout(typingTimer);
	typingTimer = setTimeout(doneTyping, 1700);
});

$('body').on('keydown', '#billing_dic,#billing_dic_dph,#company_details', function () {
	clearTimeout(typingTimer);
});

function doneTyping () {
	$('body').trigger('update_checkout');
}

$(document.body).on('updated_checkout', updateIcDicFields);
