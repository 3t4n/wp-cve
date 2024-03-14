const showSelectedPickupPoint = (point) => {
	if (point) {
		window.localStorage.setItem('packetaSelectedPoint', JSON.stringify(point));
		addValuesToInputs(point);
		addPointInfo(point);
		let event = new CustomEvent('wpify_woo_packeta_point_selected', { point });
		document.body.dispatchEvent(event);
	} else {
		const spanElement = document.getElementById('packeta-point-info');
		document.getElementsByClassName('wpify-woo-packeta__shipping-method')[0].classList.remove('wpify-woo-packeta__shipping-method--selected');
		clearInputsValues();
	}

	jQuery('body').trigger('update_checkout');
};

jQuery('body').on('updated_checkout', function () {
	const point = window.localStorage.getItem('packetaSelectedPoint');

	if (point && document.getElementById('packeta-point-id')) {
		const data = JSON.parse(point);
		addValuesToInputs(data);
		addPointInfo(data);
	}
});

const addPointInfo = (point) => {
	const spanElement = document.getElementById('packeta-point-info');
	spanElement.getElementsByClassName('packeta-point-info__details')[0].innerText = point.name + '\n' + point.zip + ' ' + point.city;
	document.getElementsByClassName('wpify-woo-packeta__shipping-method')[0].classList.add('wpify-woo-packeta__shipping-method--selected');
	let event = new CustomEvent('wpify_woo_packeta_point_details_added', { point });
	document.body.dispatchEvent(event);
};

const addValuesToInputs = (point) => {
	document.getElementById('packeta-point-id').value = point.id;
	document.getElementById('packeta-point-name').value = point.place;
	document.getElementById('packeta-point-street').value = point.street;
	document.getElementById('packeta-point-city').value = point.city;
	document.getElementById('packeta-point-postcode').value = point.zip;
	document.getElementById('packeta-point-url').value = point.url;
};

const clearInputsValues = () => {
	document.getElementById('packeta-point-id').value = '';
	document.getElementById('packeta-point-name').value = '';
	document.getElementById('packeta-point-street').value = '';
	document.getElementById('packeta-point-city').value = '';
	document.getElementById('packeta-point-postcode').value = '';
	document.getElementById('packeta-point-url').value = '';
};

document.addEventListener('click', function (e) {
	if (e.target && e.target.classList.contains('zasilkovna-open-widget')) {
		e.preventDefault();

		let country = '';
		if (document.getElementById('shipping_country')) {
			country = document.getElementById('shipping_country').value;
		}

		const shippingCheckbox = document.getElementById('ship-to-different-address-checkbox');
		if (shippingCheckbox) {
			if (!country || !document.getElementById('ship-to-different-address-checkbox').checked) {
				country = document.getElementById('billing_country').value;
			}
		}

		const options = { country: country.toLowerCase(), language: wpifyWooPacketa.language };

		Packeta.Widget.pick(packeta.apiKey, showSelectedPickupPoint, options);
	}
});

