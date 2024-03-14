var map = L.map( 'map' ).setView([maps_datas[ 'markers' ][0]['coords']['lat'], maps_datas[ 'markers' ][0]['coords']['long']], 13);
L.tileLayer( maps_datas[ 'tiles' ], {
	maxZoom: 18,
	attribution: maps_datas[ 'attribution' ],
	subdomains: maps_datas[ 'subdomains' ]
}).addTo(map);

var smallIcon = L.DivIcon.extend({
    options: {
		iconSize:     maps_datas['iconProp']['iconSizeSmall'],
		iconAnchor:   maps_datas['iconProp']['iconAnchorSmall'],
		className:    maps_datas['iconProp']['className'],
		popupAnchor:  maps_datas['iconProp']['popupAnchorSmall']
    }
});
var mediumIcon = L.DivIcon.extend({
    options: {
		iconSize:     maps_datas['iconProp']['iconSizeMedium'],
		iconAnchor:   maps_datas['iconProp']['iconAnchorMedium'],
		className:    maps_datas['iconProp']['className'],
		popupAnchor:  maps_datas['iconProp']['popupAnchorMedium']
    }
});
var largeIcon = L.DivIcon.extend({
    options: {
		iconSize:     maps_datas['iconProp']['iconSizeLarge'],
		iconAnchor:   maps_datas['iconProp']['iconAnchorLarge'],
		className:    maps_datas['iconProp']['className'],
		popupAnchor:  maps_datas['iconProp']['popupAnchorLarge']
    }
});

//drops markers to map
bound = new Array;
for( var i = 0; i < maps_datas[ 'markers' ].length; i++ ) {
	var m = maps_datas[ 'markers' ][ i ];
	var markerHtml = '<div class="icon" data-marker="' + m['id'] + '">' + m['icon']['letter'] + '</div>';
	if( m['import'] )
		markerHtml += '<div class="central-point"></div>';
	markerHtml += '<div class="shadow"></div>';
	switch(m['size']){
		case 'large' :
			var iconMarker = new largeIcon({className:'nbm-icon ' + m['icon']['color'] + ' ' + m['size'], html: markerHtml});
			break;
		case 'medium' :
			var iconMarker = new mediumIcon({className:'nbm-icon ' + m['icon']['color'] + ' ' + m['size'], html: markerHtml});
			break;
		default :
			var iconMarker = new smallIcon({className:'nbm-icon ' + m['icon']['color'] + ' ' + m['size'], html: markerHtml});
	}

	L.marker([m['coords']['lat'], m['coords']['long']], { 'icon' : iconMarker } ).addTo(map)
		.bindPopup('<b class="popup-title">' + m[ 'name' ] + "</b>" + m[ 'datas' ]);
		bound.push([m['coords']['lat'], m['coords']['long']]);
}

map.fitBounds(bound);


jQuery(document).ready(function($){
	//from map to info
	var $nbm = $('#nbm-all-places');
	var $map = $('#map');
	$map.find('.icon').hover(function(){
		var data = $(this).attr('data-marker');
		$nbm.find($('[data-nbm="' + data + '"]')).addClass('hover');
	},function(){
		var data = $(this).attr('data-marker');
		$nbm.find($('[data-nbm="' + data + '"]')).removeClass('hover');
	});

	//from infos to map
	$nbm.find('.nbm-more').hover(function(){
		var data = $(this).attr('data-nbm');
		$map.find($('[data-marker="' + data + '"]')).addClass('hover');
	},function(){
		var data = $(this).attr('data-nbm');
		$map.find($('[data-marker="' + data + '"]')).removeClass('hover');
	});


	if(document.getElementById('nbm-route-form')) {
		var notRoutedAgain = true;
		var firstpolyline = '';

		// show custom point
		$('#nbm-route-form').on('change', '.nbm-select',function(){
			if($(this).val() == 'custom'){
				$(this).parent().find('input').removeClass('hidden');
			}else{
				$(this).parent().find('input').addClass('hidden');
			}
		});

		// sShow options
		$('#nbm-show-route-options').on('click',function(e){
			e.preventDefault();
			$('#nbm-route-options').toggleClass('hidden');
		});
		

/**
 ROUTE
*/
		$('#nbm-route-form').on('submit',function(e){
			e.preventDefault();
			var routeQuery = $(this).serialize();
			var routeDetails = '';

			function printArrows(arr){
				switch(arr){
					case 'TR':
						return '<span>&#8625;</span> ';
						break;
					case 'TL':
						return '<span>&#8624;</span> ';
						break;
					case 'C':
						return '<span>&uarr;</span> ';
						break;
					case 'EXIT1':
						return '<span>&#8634;</span> ';
						break;
					case 'EXIT2':
						return '<span>&#8634;</span> ';
						break;
					case 'EXIT3':
						return '<span>&#8634;</span> ';
						break;
					case 'EXIT4':
						return '<span>&#8634;</span> ';
						break;
					case 'EXIT5':
						return '<span>&#8634;</span> ';
						break;
					case 'EXIT6':
						return '<span>&#8634;</span> ';
						break;
					case 'EXIT7':
						return '<span>&#8634;</span> ';
						break;
					case 'EXIT8':
						return '<span>&#8634;</span> ';
						break;
					case 'TSLR':
						return '<span>&#8625;</span> ';
						break;
					case 'TSLL':
						return '<span>&#8624;</span> ';
						break;
					default: 
						return '';
				}
			}

			function wichTransp(trs){
				switch(trs){
					case 'bicycle':
						return 'bicycle';
						break;
					case 'foot':
						return 'foot';
						break;
					default :
						return 'car';
						break;
				}
			}
			
			function wichSpeed(trs){
				switch(trs){
					case 'bicycle':
						return 1000;
						break;
					case 'foot':
						return 1250;
						break;
					default :
						return 500;
						break;
				}
			}

			//ROUTE
			$.ajax({
				url:"/wp-admin/admin-ajax.php",
				type:'POST',
				data:'action=nbm_get_route&' + routeQuery,
				success : function(data){
					var obj    = $.parseJSON(data);

					//PATH
					var path = new Array;
					for(i in obj.route_geometry) {
						path.push( new L.LatLng(obj.route_geometry[i][0],obj.route_geometry[i][1]));
					}

					if(notRoutedAgain == true) {
						firstpolyline = new L.Polyline(path, {
							color: '#4fdaea',
							weight: 5,
							opacity: 0.5,
							smoothFactor: 1
						});
						firstpolyline.addTo(map);
						notRoutedAgain = false;
					}else{
						firstpolyline.setLatLngs(path).redraw();
					}

					map.fitBounds(firstpolyline.getBounds());

					//INFORMATION
					var time = obj.route_summary['total_time'];
					var minutes = (Math.floor(time / 60) != 'NaN') ? Math.floor(time / 60)+'min' : '';
					var hh = ((time - minutes * 60) > 0) ? (time - minutes * 60)+'H' : '';
					var time = hh + minutes;

					routeDetails = '<div class="nbm-route-title">' + obj.route_summary['start_point'] + ' ' + routeDial['to'] + ' ' + obj.route_summary['end_point'] + ' (' + routeDial['in'] + ' ' + parseInt(obj.route_summary['total_distance']/1000) + 'km ' + routeDial['and'] + ' ' + time + ')</div>';
					routeDetails += '<ol id="nbm-route-details" class="nbm-route-details">';
					for(i in obj.route_instructions) {
						routeDetails += '<li class="route-detail"><div class="nbm-route-instr">' + printArrows(obj.route_instructions[i][7]) + obj.route_instructions[i][0] + '<div><div class="nbm-route-dist">' + obj.route_instructions[i][4] + '<div></li>';
					}
					routeDetails += '</ol>';
					$('#route-details').html(routeDetails);


					//TRIP
					var bikeIcon = L.icon({
						iconUrl: routeDial['transIcon'][wichTransp($('.nbm-trans:checked').val())],
						iconSize: [25, 39],
						iconAnchor: [12, 39],
						shadowUrl: null
					});
					var velo = L.animatedMarker(firstpolyline.getLatLngs(), {
						icon: bikeIcon,
						autoStart: false,
						interval: wichSpeed($('.nbm-trans:checked').val()),
						onEnd: function() {
							$(this._shadow).fadeOut();
							$(this._icon).fadeOut(3000, function(){
								map.removeLayer(this);
							});
						}
					});
					map.addLayer(velo);

					$(velo._icon).hide().fadeIn(1000, function(){
						velo.start();
					});
				}
			});
		});
	}
});