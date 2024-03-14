<?php

/**
 * USPS Services and subservices
 */
return array(
	// Domestic
	'D_GROUND_ADVANTAGE' => array(
		// Name of the service shown to the user
		'name'  => 'USPS Ground Advantage&#8482;',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'1058' => 'USPS Ground Advantage&#8482;',
			'4058' => 'USPS Ground Advantage&#8482; HAZMAT',
			'2058' => 'USPS Ground Advantage&#8482; Hold For Pickup',
			'6058' => 'USPS Ground Advantage&#8482; Parcel Locker',
			'4096' => 'USPS Ground Advantage&#8482; Cubic HAZMAT',
			'1096' => 'USPS Ground Advantage&#8482; Cubic',
			'2096' => 'USPS Ground Advantage&#8482; Cubic Hold For Pickup',
			'6096' => 'USPS Ground Advantage&#8482; Cubic Parcel Locker',
		),
	),
	'D_EXPRESS_MAIL' => array(
		// Name of the service shown to the user
		'name'  => 'Priority Mail Express&#8482;',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'2'  => 'Priority Mail Express&#8482; Hold for Pickup',
			'3'  => 'Priority Mail Express&#8482;',
			'23' => 'Priority Mail Express&#8482; Sunday/Holiday',
		),
	),
	'D_STANDARD_POST' => array(
		// Name of the service shown to the user
		'name'  => 'Retail Ground&#8482;',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'4'  => 'Retail Ground&#8482;',
		),
	),
	'D_MEDIA_MAIL' => array(
		// Name of the service shown to the user
		'name'  => 'Media Mail',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'6'  => 'Media Mail',
		),
	),
	'D_LIBRARY_MAIL' => array(
		// Name of the service shown to the user
		'name'  => 'Library Mail',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'7'  => 'Library Mail',
		),
	),
	'D_PRIORITY_MAIL' => array(
		// Name of the service shown to the user
		'name'  => 'Priority Mail&#0174;',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'1'  => 'Priority Mail&#0174;',
			'18' => 'Priority Mail&#0174; Keys and IDs',
			'33' => 'Priority Mail&#0174; Hold For Pickup',
			'47' => 'Priority Mail&#0174; Regional Rate Box A',
			'49' => 'Priority Mail&#0174; Regional Rate Box B',
		),
	),
	// International
	'I_EXPRESS_MAIL' => array(
		// Name of the service shown to the user
		'name'  => 'Priority Mail Express International&#8482;',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'1'  => 'Priority Mail Express International&#8482;',
		),
	),
	'I_PRIORITY_MAIL' => array(
		// Name of the service shown to the user
		'name'  => 'Priority Mail International&#0174;',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'2'  => 'Priority Mail International&#0174;',
		),
	),
	'I_GLOBAL_EXPRESS' => array(
		// Name of the service shown to the user
		'name'  => 'Global Express Guaranteed&#0174; (GXG)',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'4'  => 'Global Express Guaranteed&#0174; (GXG)',
			'5'  => 'Global Express Guaranteed&#0174; Document',
			'6'  => 'Global Express Guaranteed&#0174; Non-Document Rectangular',
			'7'  => 'Global Express Guaranteed&#0174; Non-Document Non-Rectangular',
			'12'  => 'USPS GXG&#8482; Envelope',
		),
	),
	'I_FIRST_CLASS' => array(
		// Name of the service shown to the user
		'name'  => 'First Class Mail&#0174; International',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'13'  => 'First Class Mail&#0174; International Letters',
			'14'  => 'First Class Mail&#0174; International Large Envelope',
			'15'  => 'First Class Package International Service&#8482;',
		),
	),
	'I_POSTCARDS' => array(
		// Name of the service shown to the user
		'name'  => 'International Postcards',

		// Services which costs are merged if returned (cheapest is used). This gives us the best possible rate.
		'services' => array(
			'21'  => 'International Postcards',
		),
	),
);
