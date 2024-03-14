jQuery( ( $ ) => {
	const ldsCode = '<div class="dropp-spinner"><div></div><div></div><div></div><div></div></div>';
	let loading = false;
	const loaded_prices = function( price_inputs ) {
		return function( response ) {
			for (let i = 0; i < response.data.length; i++) {
				let price = response.data[i].price;
				$(price_inputs[i]).val(price);
			}
			price_inputs.prop('readonly', false)
			price_inputs.parent().addClass('dropp-loaded');
			setTimeout(
				() => price_inputs.parent().removeClass('dropp-loading'),
				500
			);
			loading = false;
		};
	};
	const loading_error = function ( error ) {
		alert('An unknown error occured when loading prices. Please report this as an issue on the WordPress support forum for the dropp-for-woocommerce plugin.')
		loading = false;
	}
	const init_load_prices_from_api_button = function() {
		let elem = $('[name$="_load_prices_from_api"]');
		if ( ! elem.length || loading ) {
			return;
		}
		elem.addClass('button button-primary button-large');
		elem.val(elem.prop('placeholder'));
		let table = elem.closest('table');
		let price_inputs = table.find('[name*="_cost"]');
		price_inputs.parent().append(ldsCode);
		let instance_id = $('[name="instance_id"]').val();

		elem.on(
			'click',
			() => {
				price_inputs.prop('readonly', true).parent().addClass('dropp-loading').removeClass('dropp-loaded');

				loading = true;
				// @TODO: Add blocker
				jQuery.ajax( {
					url: _dropp.ajaxurl,
					method: 'get',
					data: {
						action: 'dropp_get_instance_prices',
						instance_id: instance_id,
					},
					success: loaded_prices(price_inputs),
					error: loading_error,
				} );
			}
		)
	}
	$( document.body ).on( 'wc_backbone_modal_loaded', init_load_prices_from_api_button);
} );
