(function () {
	const params = new URLSearchParams(window.location.search);
	const utm_params = [];
	params.forEach(function (value, key) {
	   if (key.startsWith('utm_')) {
		  utm_params.push(key + '=' + value)
	   }
	});
	
	utm_search = utm_params.join('&');
	
	if (utm_search.length > 0) {
	   function applyUTMS() {
		  Array.from(document.querySelectorAll('a[href]')).forEach(item => {
			 if (
				item.href.indexOf('#') === -1 &&
				item.href.indexOf('mailto:') === -1 &&
				item.href.indexOf('tel:') === -1
			 ) {
				const newHref = item.href + (item.href.indexOf('?') === -1 ? '?' : '&') + utm_search;
				item.setAttribute('href', newHref);
			 }
		  });
	   }
	   
	   if (document.readyState === "complete") {
		  applyUTMS();
	   } else {
		  window.addEventListener("load", function () {
			 applyUTMS();
		  });
	   }
	}
 })();