document.addEventListener("DOMContentLoaded", function(event) {
	var els = document.getElementsByTagName('*');
	for (var i = 0; i < els.length; i++) {
		var el = els[i];
		for (var j = 0; j < el.attributes.length; j++) {
			var a = el.attributes[j];
			if (a.value.indexOf('<mnp-mark>') !== -1)
				a.value = a.value.replace(/<\/?mnp-mark>/g, '');
		}
	}
	var wrapper = document.getElementsByClassName('mnp-unread')[0];
	var wrapperHtml = wrapper.outerHTML;
	var titles = document.getElementsByTagName('mnp-mark');
	for (var i = 0; i < titles.length; i++) {
		var el = titles[i];
		el.innerHTML = wrapperHtml.replace('{title}', el.innerHTML);
	}
	wrapper.parentNode.removeChild(wrapper);
});
