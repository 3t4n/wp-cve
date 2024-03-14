jQuery( ( $ ) => {
	const init_free_shipping_checkbox = function() {
		let cb = $('[name$="_free_shipping"]');
		let elem = $('[name$="_free_shipping_threshold"]');

		if ( ! cb.length || ! elem.length ) {
			return;
		}

		let row = elem.closest('tr');
		let content = row.find('label,fieldset');
		content.toggle(cb.is(':checked'));

		cb.on(
			'change',
			() => {
				if (cb.is(':checked'))
					content.fadeIn();
				else
					content.fadeOut();
			}
		)
	}
	$( document.body ).on( 'wc_backbone_modal_loaded', init_free_shipping_checkbox );
} );
