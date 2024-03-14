jQuery(document).ready(function($){
	$( '#woocommerce-order-items' ).on( "click", "tr.shipping a.edit-order-item", function(){
		$( ".shipping_method" ).trigger( "change" );
	});
	$( "body" ).on( "change", ".shipping_method", function(){
		var shipping = $( this ).val();
		if( shipping == "betanet_epost" ){
			if( $( ".israelpost-wrapper").length ){
				$( "tr.shipping").after( '<tr>'+$( "#israelpost-additional" ).parent().parent().html()+'</tr>' );
				$( ".israelpost-wrapper").remove();
			}
			$( "#betanetgovina-wrapper" ).hide();
			$( "#israelpost-additional" ).show();
			
			var select = $('#city-list');
			var optionLength = select.find( "option" ).length;
			var selected = select.data( 'selected' );
			if( optionLength == 1 ){
				var cities = EpostList.config.cities;
				var i, city, option
				for (i in cities) {
					city = cities[i]
					option = $( '<option>', {value: city, text: city} );
					if( selected == city ){
						option.attr( 'selected', 'selected' );
					}
					select.append(option)
				}
			}
			select.select2();
		}else if( shipping == "betanet_govina" ){
			if( $( ".betanetgovina-wrapper").length ){
				$( "tr.shipping").after( '<tr>'+$( "#betanetgovina-wrapper" ).parent().html()+'</tr>' );
				$( ".betanetgovina-wrapper").remove();
			}
			$( "#betanetgovina-wrapper" ).show();
			$( "#israelpost-additional" ).hide();
		}else{
			$( "#israelpost-additional" ).hide();
			$( "#betanetgovina-wrapper" ).hide();
		}
	});
	$( "body" ).on( "change", "#city-list", function(){
		var serviceUrl = EpostList.getConfig('getSpotsUrl');
		if( !serviceUrl ){
			console.log( 'Invalid getSpotsUrl' );
			return;
		}
		var city = $( this ).val();
		serviceUrl += '&city=' + encodeURIComponent(city);
		$.get( serviceUrl, function( response ){
			var spots = response,locations = {};
			var i, spot;
			for( i in spots ){
				spot = spots[i];
				locations[i] = spot.name + ' - ' + spot.house + ' ' + spot.street + ' - ' +city;
			}
			var spotList = $( '#spot-list' );
			if( !spotList.length ){
				return;
			}
			spotList.find('option').remove();
			var option, cityList = $( '#city-list' );

			if( !cityList.val() ){
				option = $( '<option>', {value: '', text: Translator.translate( 'Select pickup point' )} );
				spotList.append(option);
				return;
			}

			if( !Object.keys(locations).length ){
				option = $( '<option>', {value: '', text: Translator.translate( 'There is no pickup point' )} );
				spotList.append( option );
				return;
			}

			var location, i, selectedLocation = spotList.data('selected');

			option = $( '<option>', {value: '', text: Translator.translate( 'Select pickup point' )} );
			spotList.append(option);
			for( i in locations ){
				location = locations[i];
				option = $( '<option>', {value: i, text: location} );
				if( selectedLocation && selectedLocation == i ){
					option.attr('selected', 'selected');
				}
				spotList.append(option);
			}
			spotList.select2();
	  });
	});
	$( 'body' ).on( "click", ".epost-cancel-shipment", function(){
		var $_this = $( this );
		$_this.prop( 'disabled', true );
		var ajaxurl = EpostList.config.saveSpotInfoUrl;
		var orderRef = $_this.attr( "data-text" );
		var orderID = $_this.attr( "data-id" );
		var data = {
			'action': 'hfd_epost_cancel_shipment',
			'orderRef': orderRef,
			'orderID': orderID
		};
		$.post( ajaxurl, data, function(response) {
			$_this.prop( 'disabled', false );
			var obj = JSON.parse( response );
			console.log( obj );
			if( obj.success ){
				alert( obj.msg );
			}else{
				alert( obj.msg );
			}
		});
		return false;
	});
});