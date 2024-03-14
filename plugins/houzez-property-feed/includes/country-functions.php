<?php

function get_houzez_property_feed_country_by_name($name)
{
	$countries = get_houzez_property_feed_countries();

	foreach ( $countries as $key => $value ) 
	{
        if ( $value['name'] === $name ) 
        {
            return $key;
        }
    }

    return false;
}

function get_houzez_property_feed_countries()
{
	$countries = array(
	    'AR' => array(
	        'name' => 'Argentina',
	        'currency' => 'ARS',
	    ),
	    'AU' => array(
	        'name' => 'Australia',
	        'currency' => 'AUD',
	    ),
	    'BR' => array(
	        'name' => 'Brazil',
	        'currency' => 'BRL',
	    ),
	    'CA' => array(
	        'name' => 'Canada',
	        'currency' => 'CAD',
	    ),
	    'CN' => array(
	        'name' => 'China',
	        'currency' => 'CNY',
	    ),
	    'FR' => array(
	        'name' => 'France',
	        'currency' => 'EUR',
	    ),
	    'DE' => array(
	        'name' => 'Germany',
	        'currency' => 'EUR',
	    ),
	    'IN' => array(
	        'name' => 'India',
	        'currency' => 'INR',
	    ),
	    'ID' => array(
	        'name' => 'Indonesia',
	        'currency' => 'IDR',
	    ),
	    'IT' => array(
	        'name' => 'Italy',
	        'currency' => 'EUR',
	    ),
	    'JP' => array(
	        'name' => 'Japan',
	        'currency' => 'JPY',
	    ),
	    'MX' => array(
	        'name' => 'Mexico',
	        'currency' => 'MXN',
	    ),
	    'RU' => array(
	        'name' => 'Russia',
	        'currency' => 'RUB',
	    ),
	    'ZA' => array(
	        'name' => 'South Africa',
	        'currency' => 'ZAR',
	    ),
	    'ES' => array(
	        'name' => 'Spain',
	        'currency' => 'EUR',
	    ),
	    'GB' => array(
	        'name' => 'United Kingdom',
	        'currency' => 'GBP',
	    ),
	    'US' => array(
	        'name' => 'United States',
	        'currency' => 'USD',
	    ),
	    'KR' => array(
	        'name' => 'South Korea',
	        'currency' => 'KRW',
	    ),
	    'SA' => array(
	        'name' => 'Saudi Arabia',
	        'currency' => 'SAR',
	    ),
	    'NG' => array(
	        'name' => 'Nigeria',
	        'currency' => 'NGN',
	    ),
	    'EG' => array(
	        'name' => 'Egypt',
	        'currency' => 'EGP',
	    ),
	);

	return apply_filters( 'houzez_property_feed_countries', $countries );
}