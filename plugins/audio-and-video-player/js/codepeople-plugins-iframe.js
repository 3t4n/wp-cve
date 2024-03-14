(function(){
	var iframeDimensions;
	function _getDimension() {
		return {
			'width': window.innerWidth, //supported from IE9 onwards
			'height': Math.max( document.body.scrollHeight, document.documentElement.scrollHeight)
		};
	}

	function sendDimensionsToParent() {
		var iframeDimensions_new = _getDimension();

		if( (iframeDimensions_new.width != iframeDimensions.width) || (iframeDimensions_new.height != iframeDimensions.height) )
		{
			window.parent.postMessage(iframeDimensions_new, "*");
			iframeDimensions = iframeDimensions_new;
		}
	}

	window.addEventListener( 'load', function() {
		iframeDimensions = _getDimension();

		window.parent.postMessage(iframeDimensions, "*");
		if( window.MutationObserver )
		{
			var observer = new MutationObserver(sendDimensionsToParent);
			config = {
				  attributes: true,
				  attributeOldValue: false,
				  characterData: true,
				  characterDataOldValue: false,
				  childList: true,
				  subtree: true
			};
			observer.observe(document.body, config);
		} else {
			// if mutationobserver is NOT supported
			window.setInterval(sendDimensionsToParent, 500);
		}
	});
})()



