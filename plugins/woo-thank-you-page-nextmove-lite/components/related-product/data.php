<?php
defined( 'ABSPATH' ) || exit;

$config             = array();
$config['slug']     = '_xlwcty_related_product';
$config['title']    = 'Related Products';
$config['instance'] = require( __DIR__ . '/instance.php' );
$config['fields']   = array(
	'id'                     => $config['slug'],
	'position'               => 60,
	'xlwcty_accordion_title' => $config['title'],
	'xlwcty_icon'            => 'xlwcty-fa xlwcty-fa-th-large',
	'xlwcty_disabled'        => 'yes',
	'fields'                 => array(),
);
$config['default']  = array();

return $config;
