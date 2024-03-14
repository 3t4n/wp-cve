<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

return array(

	array(
		'type' => 'checkbox',
		'view' => 'switch/c2',
		'name' => 'enabled',
		'default' => true,
		'options' => array(
			'size' => 'large',
			'onLabel' => 'On',
			'offLabel' => 'Off',
		),
		"dependents" => array(
			1 => array(
				'show' => array('#div-hover-title-options-block'),
			),
			0 => array(
				'hide' => array('#div-hover-title-options-block'),
			),
		),
	),


	array(
		'type' 		=> 'html',
		'view' 		=> 'raw',
		'options'   => array(
			'content' 	=> '<div id="div-hover-title-options-block">',
		),
	),


	array(
		'type' => 'checkbox',
		'view' => 'group-button',
		'name' => 'fontStyle',
		'label'=> __('Font Options', 'gallery-images-ape'),
		'default' => null,
		'options' => array(
			'values' => array(
				array(
					'name' => 'fontBold',
					'label' => __( 'Bold' , 	'gallery-images-ape' ),
				),
				array(
					'name' => 'fontItalic',
					'label' => __( 'Italic' , 	'gallery-images-ape' ),
				),
				array(
					'name' => 'fontUnderline',
					'label' => __( 'Underline' , 	'gallery-images-ape' ),
				),
				
			),
		),
	),


	array(
		'type' => 'text',
		'view' => 'group',
		'name' => 'fontSize',
		'default' => '12',
		'options' => array(
			'column' => '12',
			'columnWrap'	=> '12 medium-6',
			'leftLabel' 	=> 'Font size',
			'rightLabel' 	=> 'px',
		),
	),


	array(
		'type' => 'text',
		'view' => 'group',
		'name' => 'fontLineHeight',
		'default' => '101',
		'options' => array(
			'column' => '12',
			'columnWrap'	=> '12 medium-6',
			'leftLabel' 	=> 'Line height',
			'rightLabel' 	=> '%',
		),
	),

	array(
		'type' => 'text',
		'view' => 'color',
		'name' => 'color',
		'default' => '#ffffff',
		'options' => array(
			'alpha'			=> false,
			'column'		=> '12',
			'columnWrap'	=> '12  medium-6',
			'leftLabel' 	=> 'Color',
		),
	),


	array(
		'type' => 'text',
		'view' => 'color',
		'name' => 'colorHover',
		'default' => '#ffffff',
		'options' => array(
			'alpha'			=> false,
			'column'		=> '12',
			'columnWrap'	=> '12  medium-6',
			'leftLabel' 	=> 'Hover',
		),
	),


	array(
		'type' 		=> 'html',
		'view' 		=> 'raw',
		'options'   => array(
			'content' 	=> '</div>',
		),
	),
 );