(function( $ ) {
	'use strict';
	// The initViz function. Refer Tableau JS API for details on how this works (https://onlinehelp.tableau.com/current/api/js_api/en-us/JavaScriptAPI/js_api.htm)
	function initViz(containerDiv) {
		if(containerDiv.hasAttributes()){
			var url = containerDiv.getAttribute("data-url"),
			tabs = containerDiv.getAttribute("data-hide-tabs"),
			toolbar = containerDiv.getAttribute("data-hide-toolbar"),
			device = '',
			height = '',
			width = '';
			if(containerDiv.hasAttributes('data-device')){
				device = containerDiv.getAttribute('data-device');
			}
			if(containerDiv.hasAttributes('data-height')){
				height = parseInt(containerDiv.getAttribute('data-height'));
			}
			if(containerDiv.hasAttributes('data-width')){
				width = parseInt(containerDiv.getAttribute('data-width'));
			}
			var options = {
					hideTabs: JSON.parse(tabs),
					hideToolbar: JSON.parse(toolbar),
					device: device,
					height: height,
					width: width,
					onFirstInteractive: function () {
							//console.log('tabs: ' + tabs + '\ntoolbar: ' + toolbar + '\ndevice: ' + device + '\nheight: ' + height + '\nwidth: ' + width);
							//console.log("The viz has finished loading.");
					}
			};
			// Remove unset options
			if(!height) { delete options.height; }
			if(!width) { delete options.width; }
			if(!device) { delete options.device; }
			// Create a viz object and embed it in the container div.
			if (url && 0 !== url.length) {
				var viz = new tableau.Viz(containerDiv, url, options);
			}
		}

	}
	//Document ready fuction
	$(document).ready(function() {
  		//if the data attribute is populated, initialize the Tableau Viz.
  		var containerDivs = document.querySelectorAll('*[id^="viz-container"]');
  		if(containerDivs.length > 0){
			containerDivs.forEach(function(contDiv){
				if ($(contDiv.id).attr("data-url") !== ''){
		     		initViz(contDiv);
		   		}
			})
		} else {
			//for backward compatibility with older versions of the plugin
			var containerDiv = document.getElementById("vizContainer");
		   	if (containerDiv && $(containerDiv).attr("data-url") !== ''){
		     	initViz(containerDiv);
		   	}
		}

  });
})( jQuery );
