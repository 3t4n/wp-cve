/*
Track clicks from special elements
Version: 2.0
With help from: Ionut Staicu
Copyright: See notice in adrotate-pro.php
*/

document.addEventListener('click', (e) => {
	const trackable = e.target.matches('a.gofollow') || e.target.closest('a.gofollow');
	
	if (!trackable) return;
	
	fetch(click_object.ajax_url, {
		method: 'POST',
		headers: {'Content-Type': 'application/x-www-form-urlencoded'},
		body: new URLSearchParams([
			['action', 'adrotate_click'],
			['track', trackable.getAttribute("data-track")]
		])
	});
});