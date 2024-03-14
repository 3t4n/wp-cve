<?php
defined( 'ABSPATH' ) || exit;

$config             = array();
$config['slug']     = '_xlwcty_upsell_product';
$config['title']    = 'Upsells';
$config['instance'] = require( __DIR__ . '/instance.php' );
$config['fields']   = array(
	'id'                        => $config['slug'],
	'position'                  => 85,
	'xlwcty_accordion_title'    => $config['title'],
	'xlwcty_icon'               => 'xlwcty-fa xlwcty-fa-level-up',
	'xlwcty_accordion_head_end' => 'yes',
	'xlwcty_disabled'           => 'yes',
	'fields'                    => array(),
);

$config['default'] = array();

return $config;
