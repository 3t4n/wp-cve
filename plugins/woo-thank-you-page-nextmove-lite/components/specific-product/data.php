<?php
defined( 'ABSPATH' ) || exit;

$config             = array();
$config['slug']     = '_xlwcty_specific_product';
$config['title']    = 'Specific Products';
$config['instance'] = require( __DIR__ . '/instance.php' );
$config['fields']   = array(
	'id'                     => $config['slug'],
	'position'               => 70,
	'xlwcty_accordion_title' => 'Specific Products',
	'xlwcty_icon'            => 'xlwcty-fa xlwcty-fa-th-list',
	'xlwcty_disabled'        => 'yes',
	'fields'                 => array(),
);

$config['default'] = array();

return $config;
