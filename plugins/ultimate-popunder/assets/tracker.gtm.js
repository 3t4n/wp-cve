function trackEvent(sUrl)
{
	dataLayer.push({
		'event': "popUnderSuccess",
		'popunder_followed': sUrl
	});
}
