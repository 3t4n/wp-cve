// kimsmap.js - 200514
//////////////////////////////////////////////////

//var oMap;

function ShowMap(arParam){
	
	if (typeof arParam.level == "undefined"){
		arParam.level = 6;
	}
	
	if (typeof arParam.zoom == "undefined"){
		arParam.zoom = "off";
	}

	var mapContainer = document.getElementById('korea-map');
	var imageSize = new kakao.maps.Size(24, 35);
  var markerImage = new kakao.maps.MarkerImage(arParam.marker_url, imageSize);	
	var geocoder = new kakao.maps.services.Geocoder();
	
	geocoder.addressSearch(arParam.address, function(result, status) {
	
		if (status === kakao.maps.services.Status.OK) {
		
			var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
			
			var mapOption = { 
        center: coords,
        level: arParam.level
    	};
			
			var oMap = new kakao.maps.Map(mapContainer, mapOption);
			
			if( arParam.zoom == "on" ){
				var zoomControl = new kakao.maps.ZoomControl();
				oMap.addControl(zoomControl, kakao.maps.ControlPosition.RIGHT);
			}
			
			var marker = new kakao.maps.Marker({
				map: oMap,
				position: coords,
				image : markerImage
			});
			
			var infowindow = new kakao.maps.InfoWindow({
				content: '<div class="kims_marker_info">'+arParam.title+'</div>'
			});
			
			infowindow.open(oMap, marker);
		} 
	});    

}

function KimsPluginLoadEvent(func) {
	var oldOnLoad = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func
	} else {
		window.onload = function () {
			oldOnLoad();
			func();
		}
	}
}
 
KimsPluginLoadEvent(function(){
	if( typeof g_arKimsParams != "undefined" ){
		ShowMap(g_arKimsParams);
	}
});



