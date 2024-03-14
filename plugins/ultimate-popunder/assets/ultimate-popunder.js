var totalURLWeight = 0,
	currentURL = 0,
	weighedURL = [];

// Ensure we are defined before continuing
if (typeof URLlist !== 'undefined' && URLlist !== null)
{
	for (var i = 0; i < URLlist.length; i++)
	{
		totalURLWeight += URLlist[i][1];
	}

	while (currentURL < URLlist.length)
	{
		for (i = 0; i < URLlist[currentURL][1]; i++)
		{
			weighedURL[weighedURL.length] = URLlist[currentURL][0];
		}
		currentURL++
	}

	var pURL = weighedURL[Math.floor(Math.random() * weighedURL.length)];
	if (URLlist.length > 0) {
		jsUnda(pURL, ultimatePopunderSettings);
	}
}