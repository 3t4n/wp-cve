<?php
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if( apeGalleryHelper::compareVersion('2.1') ){
	return include WPAPE_GALLERY_LICENCE_PATH_DIR.'fields/subfields/ext.layout.php';
}

return array(
			'type' => 'composite',
			'view' => 'columns',
			'name' => 'colums',
			'label' => __('Grid Manager', 'gallery-images-ape'),

			'fields' => array(
				array(
					'type' => 'html',
					'view' => 'raw',
					'options' => array(
						'content'=> '<div class="row"><div class="field small-2 columns text-before"><strong>'.__('Default', 'gallery-images-ape').'</strong></div>',
					)
				),

				array(
					'type' => 'checkbox',
					'view' => 'switch/c3',
					'name' => 'autowidth',
					'default' => 1,
					'options' => array(
						'size' => 'large',
						'onLabel' => 'On',
						'offLabel' => 'Off',
					),
					"dependents" => array(
						1 => array(
							'hide' => array('#field-div-layout-width'),
							'show' => array('#field-div-layout-colums'),
						),
						0 => array(
							'hide' => array('#field-div-layout-colums'),
							'show' => array('#field-div-layout-width'),
						),
					)
				),


				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'width',
					'default' => '300',
					'id'=> 'layout-width',
					'cb_sanitize' => 'intval',
					'options' => array(
						'hide'			=> 1,
						'rightLabel' 	=> 'px',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
					),
				),

				array(
					'type' => 'text',
					'view' => 'group',
					'name' => 'colums',
					'id'=> 'layout-colums',
					'default' => 3,
					'cb_sanitize' => 'intval',
					'options' => array(
						'rightLabel' 	=> 'columns',
						'column'		=> '12',
						'columnWrap'	=> '12  medium-6',
					),
				),

				array(
					'type' => 'html',
					'view' => 'raw',
					'options' => array(
						'content'=> 
						apeGalleryHelper::getAddonButton( __('Add Mobile Grid Add-on', 'gallery-images-ape')).
						'</div>',
					)
				),

			)
		);