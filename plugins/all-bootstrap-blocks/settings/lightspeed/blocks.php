<?php
/*
Name: 5. Blocks
Slug: blocks
Description: Specify the templates you would like to use for each block as a default.
Position: 10
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-blocks-';

return array(
	array(
		'label' => 'Header Template',
		'name' => $slug . 'header',
		'block' => 'header',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Footer Template',
		'name' => $slug . 'footer',
		'block' => 'footer',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Hero Template',
		'name' => $slug . 'hero',
		'block' => 'hero',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Content with Media Template',
		'name' => $slug . 'content-with-media',
		'block' => 'content-with-media',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Content with Media Alternate Template',
		'name' => $slug . 'content-with-media-alternate',
		'block' => 'content-with-media',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),

	array(
		'label' => 'Content with Items Template',
		'name' => $slug . 'content-with-items',
		'block' => 'content-with-items',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),

	array(
		'label' => 'Media Template',
		'name' => $slug . 'media',
		'block' => 'media',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),

	array(
		'label' => 'Posts Template',
		'name' => $slug . 'posts',
		'block' => 'posts',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),

	
	array(
		'label' => 'Call to Action Template',
		'name' => $slug . 'call-to-action',
		'block' => 'call-to-action',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),

	array(
		'label' => 'Next and Previous Template',
		'name' => $slug . 'next-and-previous',
		'block' => 'next-and-previous',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),

	array(
		'label' => 'Logos Template',
		'name' => $slug . 'logos',
		'block' => 'logos',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),

	array(
		'label' => 'Contact',
		'name' => $slug . 'contact',
		'block' => 'contact',
		'variable' => '',
		'row' => 'default',
		'input' => 'block-template',
		'default' => 'basic.php',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),
);