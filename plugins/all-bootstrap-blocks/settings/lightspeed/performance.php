<?php
/*
Name: 4. Performance
Slug: performance
Description: Improve the performance of your website. This functionality is currently in beta and may be temperamental.
Position: 10
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-performance-';

return array(
	array(
		'label' => 'Lazy Load',
		'name' => $slug . 'lazy-load',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'If checked images will be lazy loaded.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Convert to WebP',
		'name' => $slug . 'webp',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'If checked pngs, jpegs and gifs will be automatically converted to webp.',
		'allow_reset' => false,
		'options' => array()
	),
);