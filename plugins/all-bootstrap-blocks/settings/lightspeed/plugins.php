<?php
/*
Name: 6. Plugins
Slug: plugins
Description: Override third party plugin settings and styles.
Position: 10
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-plugins-';

return array(
	array(
		'label' => 'Override Ninja Forms styles',
		'name' => $slug . 'nf-styles',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => null,
		'description' => 'If checked Ninja Forms styles will be overriden by Bootstrap styles. This will also set the Ninja Forms theme to "none".',
		'allow_reset' => false,
		'options' => array()
	),
);