function getImages(session_key, tags) {
	for (var i = 0; i < tags.length; i++) {
		var src = tags[i].src
		src = src.replace('placeholder', 'api/get/images')
		if (!src.includes('session')) {
			src = src + (src.includes('?') ? '&' : '?') + 'session=' + session_key + '&domain=' + window.location.hostname
		}
		tags[i].src = src
	}
}

function loadImages() {
	var tags = document.getElementsByTagName('img')
	tags = Array.from(tags).filter(tag => tag.src.includes('placeholder'))
	if (tags.length > 0) {
		var session_key = window.localStorage.getItem('session_key')
		var expiry = window.localStorage.getItem('expiry')
		if (session_key && (Math.round(+new Date() / 1000) < expiry)) {
			getImages(session_key, tags)
		} else {
			// TODO: switch to brandlogos.org
			fetch('https://brandlogos.org/api/get/session/', {
				method: 'POST',
				body: JSON.stringify({ domain: window.location.href }),
			}).then(res => res.json())
				.then(data => {
					if (data.session_key) {
						storage = window.localStorage
						storage.setItem('session_key', data.session_key)
						storage.setItem('expiry', data.expiry)
						getImages(storage.getItem('session_key'), tags)
					}
				})
		}
	}
}
window.addEventListener('load', (event) => {
	loadImages();
});