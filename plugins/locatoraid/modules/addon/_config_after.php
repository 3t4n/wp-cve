<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
// https://download.geonames.org/export/zip/
$config['after']['/front/form'][] = function( $app, $ret )
{
	$appSettings = $app->make('/app/settings');
	$thisFieldPname = 'addon:zipcode:nl';
	$thisFieldConf = $appSettings->get( $thisFieldPname );
	if( ! $thisFieldConf ){
		return $ret;
	}

	$zipcodeList = [];
	$filename = __DIR__ . '/zip-nl.csv';
	$fileContent = file( $filename );
	foreach( $fileContent as $line ){
		$line = trim( $line );
		if( ! strlen($line) ) continue;
		list( $zip, $lat, $lng ) = explode( ',', $line );
		$zipcodeList[ $zip ] = [ $lat, $lng ];
	}

	if( ! $zipcodeList ){
		return $ret;
	}

	$jsCode = '';
	$jsCode .= '<script>';
	$jsCode .= 'var hcGeo = {};';

	foreach( $zipcodeList as $zip => $latLng ){
		$jsCode .= 'hcGeo[\'' . $zip . '\']=[' . $latLng[0] . ',' . $latLng[1] . '];';
	}
	$jsCode .= '</script>';

	$ret[ 'js-zipcode' ] = $jsCode;

	return $ret;
};
