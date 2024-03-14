<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['after']['/conf/view/layout->tabs'][] = function( $app, $ret )
{
	$langList = get_available_languages();
	if( ! $langList ) return $ret;
	$ret['dynamictranslate'] = array( 'dynamictranslate.conf', __('Translate dynamic content', 'locatoraid') );
	return $ret;
};