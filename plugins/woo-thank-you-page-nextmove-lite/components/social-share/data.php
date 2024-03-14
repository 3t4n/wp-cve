<?php
defined( 'ABSPATH' ) || exit;

$config             = array();
$config['slug']     = '_xlwcty_share_order';
$config['title']    = 'Social Share';
$config['instance'] = require( __DIR__ . '/instance.php' );
$config['fields']   = array(
	'id'                     => $config['slug'],
	'position'               => 65,
	'xlwcty_accordion_title' => $config['title'],
	'xlwcty_icon'            => 'xlwcty-fa xlwcty-fa-share-alt',
	'xlwcty_disabled'        => 'yes',
	'fields'                 => array(),
);
$config['default']  = array();

return $config;
