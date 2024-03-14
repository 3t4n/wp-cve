<?php
defined( 'ABSPATH' ) || exit;

$config             = array();
$config['slug']     = '_xlwcty_cross_sell_product';
$config['title']    = 'Cross-Sell';
$config['instance'] = require( __DIR__ . '/instance.php' );
$config['fields']   = array(
	'id'                     => $config['slug'],
	'position'               => 80,
	'xlwcty_accordion_title' => $config['title'],
	'xlwcty_icon'            => 'xlwcty-fa xlwcty-fa-random',
	'xlwcty_disabled'        => 'yes',
	'fields'                 => array(),
);
$config['default']  = array();

return $config;
