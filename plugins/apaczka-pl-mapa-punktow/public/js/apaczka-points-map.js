(function ($) {
	var hide_services_cod;
	var criteria;
	var apaczkaMap = new ApaczkaMap();

	$( document ).ready(
		function (){
			$( document.body ).on(
				'updated_checkout',
				function(evt, data) {
					console.log( data );

					apaczkaMap = new ApaczkaMap(
						{
							app_id : apaczka_points_map.app_id,
							onChange : function( record) {
								if (record) {
									if (record) {
										$( "#apm_supplier" ).val( record.supplier );
										$( "#apm_access_point_id" ).val( record.access_point_id );
										$( "#apm_name" ).val( record.name );
										$( "#apm_foreign_access_point_id" ).val( record.foreign_access_point_id );
										$( "#apm_street" ).val( record.street );
										$( "#apm_city" ).val( record.city );
										$( "#apm_postal_code" ).val( record.postal_code );
										$( "#apm_country_code" ).val( record.country_code );
										$( '#amp-delivery-point-desc' ).html(
											apaczka_points_map.translation.delivery_point + ' : ' +
											record.foreign_access_point_id + ' (' + record.supplier + ')' + '<br>' +
											record.name + '<br>' +
											record.street + '<br>' +
											record.postal_code + ' ' + record.city
										);
									}
								}
							}
						}
					);

					$( '#amp-map-button' ).on(
						"click",
						function() {
							if ( data.fragments.data_only_cod === 'yes' ) {
								hide_services_cod = true;
								criteria          = [
									{ field: 'services_cod', operator: 'eq', value: true },
									{ field: 'services_receiver', operator: 'eq', value: true }
								];
							} else {
								hide_services_cod = false;
								criteria          = [
									{ field: 'services_receiver', operator: 'eq', value: true }
								];
							}

							apaczkaMap.criteria        = criteria;
							apaczkaMap.hideServicesCod = hide_services_cod;

							if ( data.fragments.data_supplier !== 'all' ) {
								apaczkaMap.setFilterSupplierAllowed(
									[data.fragments.data_supplier],
									[data.fragments.data_supplier],
								);
							} else {
								apaczkaMap.setFilterSupplierAllowed(
									['DHL_PARCEL', 'DPD', 'INPOST', 'PWR', 'POCZTA', 'UPS'],
									['DHL_PARCEL', 'DPD', 'INPOST', 'PWR', 'POCZTA', 'UPS'],
								);
							}

							apaczkaMap.show(
								{
									address : {street: data.fragments.data_shipping_address, city: data.fragments.data_shipping_city},
								}
							);
						}
					);

					$( 'input.shipping_method' ).on(
						"click",
						function() {
							$( '#amp-delivery-point-desc' ).html( '' );
							$( '#apm_access_point_id' ).val( '' );
						}
					);
				}
			);
		}
	);
})( jQuery );
