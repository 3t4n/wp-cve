<?php
/*
Name: Grid
Slug: grid
Description: Set the number of columns and specify the width of the gutters.
Position: 10
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-grid-';

return array(
	array(
		'label' => 'Breakpoints',
		'name' => '',
		'variable' => '',
		'row' => 'default',
		'input' => 'header',
		'default' => '',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-breakpoint-xs',
		'name' => $slug . 'grid-breakpoint-xs',
		'variable' => '$grid-breakpoint-xs',
		'row' => 'default',
		'input' => 'text',
		'default' => '0px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-breakpoint-sm',
		'name' => $slug . 'grid-breakpoint-sm',
		'variable' => '$grid-breakpoint-sm',
		'row' => 'default',
		'input' => 'text',
		'default' => '576px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-breakpoint-md',
		'name' => $slug . 'grid-breakpoint-md',
		'variable' => '$grid-breakpoint-md',
		'row' => 'default',
		'input' => 'text',
		'default' => '768px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-breakpoint-lg',
		'name' => $slug . 'grid-breakpoint-lg',
		'variable' => '$grid-breakpoint-lg',
		'row' => 'default',
		'input' => 'text',
		'default' => '992px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-breakpoint-xl',
		'name' => $slug . 'grid-breakpoint-xl',
		'variable' => '$grid-breakpoint-xl',
		'row' => 'default',
		'input' => 'text',
		'default' => '1200px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-breakpoint-xxl',
		'name' => $slug . 'grid-breakpoint-xxl',
		'variable' => '$grid-breakpoint-xxl',
		'row' => 'default',
		'input' => 'text',
		'default' => '1400px',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),

	array(
		'label' => 'Grid',
		'name' => '',
		'variable' => '',
		'row' => 'default',
		'input' => 'header',
		'default' => '',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-columns',
		'name' => $slug . 'grid-columns',
		'variable' => '$grid-columns',
		'row' => 'default',
		'input' => 'text',
		'default' => '12',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-gutter-width',
		'name' => $slug . 'grid-gutter-width',
		'variable' => '$grid-gutter-width',
		'row' => 'default',
		'input' => 'text',
		'default' => '1.5rem',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$grid-row-columns',
		'name' => $slug . 'grid-row-columns',
		'variable' => '$grid-row-columns',
		'row' => 'default',
		'input' => 'text',
		'default' => '6',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
	array(
		'label' => '$gutters',
		'name' => $slug . 'gutters',
		'variable' => '$gutters',
		'row' => 'default',
		'input' => 'text',
		'default' => '$spacers',
		'description' => '',
		'allow_reset' => true,
		'options' => array()
	),
);