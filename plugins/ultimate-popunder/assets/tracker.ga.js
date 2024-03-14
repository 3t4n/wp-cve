function trackEvent(sUrl)
{
	ga(
		'send',
		'event',
		'Referral',
		'PopUnder',
		sUrl,
		1
	);
}