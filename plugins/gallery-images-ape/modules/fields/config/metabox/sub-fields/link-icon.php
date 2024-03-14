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
					'show' => array('#div-hover-link-icon-options-block'),
				),
				0 => array(
					'hide' => array('#div-hover-link-icon-options-block'),
				),
			),
		),


	array(
		'type' 		=> 'html',
		'view' 		=> 'raw',
		'options'   => array(
			'content' 	=> '<div id="div-hover-link-icon-options-block">',
		),
	),


	array(
		'type' 		=> 'html',
		'view' 		=> 'default',
		'options'   => array(
			'content' 	=> '<label><h5>'.__('Link Button Options', 'gallery-images-ape').'</h5></label>',
		),
	),


	array(
		'type' => 'text',
		'view' => 'group',
		'name' => 'iconSelect',
		'default' => 'fa-link',
		'options' => array(
			'column' => '12',
			'columnWrap'	=> '12 medium-6',
			'leftLabel' 	=> 'Icon',
			'rightLabel' 	=> '<a href="https://fontawesome.com/icons?d=gallery&s=regular,solid&m=free" target="_blank">'.__('icons link', 'gallery-images-ape').'</a>',
		),
	),

	array(
		'type' => 'text',
		'view' => 'group',
		'name' => 'borderSize',
		'default' => '0',
		'options' => array(
			'column' => '12',
			'columnWrap'	=> '12 medium-6',
			'leftLabel' 	=> 'Border width',
			'rightLabel' 	=> 'px',
		),
	),

	array(
		'type' => 'text',
		'view' => 'group',
		'name' => 'fontSize',
		'default' => '22',
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
		'default' => '88',
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
		'type' => 'text',
		'view' => 'color',
		'name' => 'colorBg',
		'default' => 'rgba(0,0,0,0)',
		'options' => array(
			'alpha'			=> true,
			'column'		=> '12',
			'columnWrap'	=> '12  medium-6',
			'leftLabel' 	=> 'Background Color',
		),
	),

	array(
		'type' => 'text',
		'view' => 'color',
		'name' => 'colorBgHover',
		'default' => 'rgba(0,0,0,0)',
		'options' => array(
			'alpha'			=> true,
			'column'		=> '12',
			'columnWrap'	=> '12  medium-6',
			'leftLabel' 	=> 'Background Color',
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