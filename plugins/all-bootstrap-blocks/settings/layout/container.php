<?php
/*
Name: Containers
Slug: container
Description: Specify the max widths for different Bootstrap breakpoints.
Position: 10
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-container-';

return array(
	array(
		'label' => '$container-max-width-sm',
		'name' => $slug . 'container-max-width-sm',
		'variable' => '$container-max-width-sm',
		'row' => 'default',
		'input' => 'text',
		'default' => '540px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$container-max-width-md',
		'name' => $slug . 'container-max-width-md',
		'variable' => '$container-max-width-md',
		'row' => 'default',
		'input' => 'text',
		'default' => '720px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$container-max-width-lg',
		'name' => $slug . 'container-max-width-lg',
		'variable' => '$container-max-width-lg',
		'row' => 'default',
		'input' => 'text',
		'default' => '960px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$container-max-width-xl',
		'name' => $slug . 'container-max-width-xl',
		'variable' => '$container-max-width-xl',
		'row' => 'default',
		'input' => 'text',
		'default' => '1140px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$container-max-width-xxl',
		'name' => $slug . 'container-max-width-xxl',
		'variable' => '$container-max-width-xxl',
		'row' => 'default',
		'input' => 'text',
		'default' => '1320px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
);