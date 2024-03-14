<?php
/*
Name: 7. Styles
Slug: styles
Description: The styles section allows you to quickly add global style elements across your site. Changing these options will quickly change the entire look and feel of your site instantly.
Position: 10
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-styles-';

return array(
	array(
		'label' => 'Strips',
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
		'label' => 'Strip Background',
		'name' => $slug . 'strip-background',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'Automatically alternate background colors to strips. This will only display on the front end of your site.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Strip Alignment',
		'name' => $slug . 'strip-alignment',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => false,
		'description' => 'Automatically alternate the alignment of strips. This will only display on the front end of your site.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Strip Padding',
		'name' => $slug . 'strip-padding',
		'variable' => '',
		'row' => 'default',
		'input' => 'text',
		'default' => '150',
		'description' => 'Specify how much padding you would like on the top and bottom of each strip.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Strip Divider',
		'name' => $slug . 'strip-divider',
		'variable' => '',
		'row' => 'default',
		'input' => 'dividers',
		'default' => 'none.svg',
		'description' => 'If selected a divider will be added at the top of each block. This can be turned off on specific blocks.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Strip Pattern',
		'name' => $slug . 'strip-pattern',
		'variable' => '',
		'row' => 'default',
		'input' => 'patterns',
		'default' => null,
		'description' => 'If selected a pattern will be added in the background of each block. This can be turned off on specific blocks.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Masks',
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
		'label' => 'Media Mask',
		'name' => $slug . 'image-mask',
		'variable' => '',
		'row' => 'default',
		'input' => 'masks',
		'default' => 'none.svg',
		'description' => 'If selected an image mask will be applied to media added to any Content with Media blocks. This can be turned off on specific blocks.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Frame',
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
		'label' => 'Frame',
		'name' => $slug . 'frame',
		'variable' => '',
		'row' => 'default',
		'input' => 'select',
		'default' => null,
		'description' => 'Add a frame around your website.',
		'allow_reset' => false,
		'options' => array(
			array(
				'id' => null,
				'label' => 'None'
			),
			array(
				'id' => 'square',
				'label' => 'Square'
			),
			array(
				'id' => 'square-lg',
				'label' => 'Large Square'
			),
			array(
				'id' => 'rounded',
				'label' => 'Rounded'
			),
			array(
				'id' => 'rounded-lg',
				'label' => 'Large Rounded'
			),
			array(
				'id' => 'sides',
				'label' => 'Sides'
			),
			array(
				'id' => 'sides-lg',
				'label' => 'Large Sides'
			),
		)
	),
	array(
		'label' => 'Frame Color',
		'name' => $slug . 'frame-color',
		'variable' => '',
		'row' => 'default',
		'input' => 'color-picker',
		'default' => '#fff',
		'description' => '',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Heading',
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
		'label' => 'Divider',
		'name' => $slug . 'headings',
		'variable' => '',
		'row' => 'default',
		'input' => 'select',
		'default' => null,
		'description' => 'Change the global styles of your h1 and h2 tags.',
		'allow_reset' => false,
		'options' => array(
			array(
				'id' => null,
				'label' => 'None'
			),
			array(
				'id' => 'basic',
				'label' => 'Basic Divider Bottom'
			),
			array(
				'id' => 'basic-top',
				'label' => 'Basic Divider Top'
			),
			array(
				'id' => 'wide',
				'label' => 'Wide Divider Bottom'
			),
			array(
				'id' => 'wide-top',
				'label' => 'Wide Divider Top'
			),
			array(
				'id' => 'dotted',
				'label' => 'Dotted Divider Bottom'
			),
			array(
				'id' => 'dotted-top',
				'label' => 'Dotted Divider Top'
			),
		)
	),
	array(
		'label' => 'Include Icon',
		'name' => $slug . 'heading-icon',
		'variable' => '',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => null,
		'description' => 'Tick to include your icon with each h1 and h2 tag.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Button',
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
		'label' => 'Icon',
		'name' => $slug . 'btn-icon',
		'variable' => '',
		'row' => 'default',
		'input' => 'icons',
		'default' => null,
		'description' => 'Select a default icon to append to each button.',
		'allow_reset' => false,
		'options' => array()
	),
);