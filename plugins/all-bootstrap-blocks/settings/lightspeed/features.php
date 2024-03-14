<?php
/*
Name: 1. Dashboard
Slug: features
Description: The settings found under "Lightspeed" have been created to work along with our "Lightspeed WordPress theme". Because of this they have not been tested with other themes and using these settings should be done with care as they could have detrimental effects on your website.
Position: 20
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-features-';

return array(
	array(
		'label' => 'Include Gallery',
		'name' => $slug . 'include-gallery',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'If checked media will display in a full screen gallery when clicked.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Include Block Patterns',
		'name' => $slug . 'include-block-patterns',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'If checked block patterns will be included for each template available for each lightspeed block.',
		'allow_reset' => false,
		'options' => array()
	),
);