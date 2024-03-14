import apiFetch from '@wordpress/api-fetch';

const containers = document.querySelectorAll('.wpify-woo-delivery-date');

if (containers) {
	containers.forEach(container => {
		const links = container.querySelectorAll('.wpify-woo-delivery-date__line a');

		if (links) {
			links.forEach(link => {
				link.addEventListener('click', function (e) {
					e.preventDefault();
					container.querySelector('#' + link.dataset.id).classList.toggle('show');
				});
			});
		}

		const selects = container.querySelectorAll('select');
		const lines = container.querySelectorAll('.wpify-woo-delivery-date__line');
		const tables = container.querySelectorAll('.wpify-woo-delivery-date__shipping-methods');

		if (selects) {
			selects.forEach(select => {
				select.addEventListener('change', function (e) {
					const target = e.currentTarget;
					const value = target.value;
					const country = target.options[target.selectedIndex].dataset.country;

					selects.forEach(select => {
						if (select.value !== value) {
							select.value = value;
						}
					})

					if ( lines ) {
						lines.forEach(line => {
							const zones = line.dataset.zones;

							if (zones) {
								if (zones.includes(value)) {
									line.style.display = "block";
								} else {
									line.style.display = "none";
								}
							}
						});
					}

					if ( tables ) {
						tables.forEach(table => {
							const id = table.getAttribute('id');

							if (id.includes(value)) {
								table.classList.add('show');
							} else {
								table.classList.remove('show')
							}
						});
					}

					if (country) {
						apiFetch({
							path: `/${wpifyDeliveryDates.namespace}/delivery-dates-country?country=${country}`
						})
					}
				});
			});
		}
	});
}
