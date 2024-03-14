<?php
$categories_list = $terms = get_terms( array(
    'taxonomy' => 'store_category'
) );
$categories_options = array(array( 'value' => '', 'text' => __( 'All Categories', 'themify-store-locator' ) ));
if ( !empty($categories_list) ) :
    foreach( $categories_list as $category ) {
        $categories_options[]=array('value'=>esc_attr($category->slug), 'text'=>esc_html($category->name));
    }
endif;
return array (
	'map' => array(
		'label' => __( 'Stores Map', 'themify-store-locator' ),
		'fields' => array(
            array(
                'name' => 'category',
                'type' => 'listbox',
                'values' => $categories_options,
                'label' => __( 'Category:', 'themify-store-locator' ),
                'value' => ''
            ),
            array(
				'name' => 'width',
				'type' => 'textbox',
				'label' => __( 'Map Width:', 'themify-store-locator' ),
				'value' => '100%'
			),
			array(
				'name' => 'height',
				'type' => 'textbox',
				'label' => __( 'Map Height:', 'themify-store-locator' ),
				'value' => '500px'
			),
			array(
				'name' => 'control',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
					array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
				),
				'label' => __( 'Map Controls:', 'themify-store-locator' )
			),
			array(
				'name' => 'scrollwheel',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
				),
				'label' => __( 'Map Scrollwheel:', 'themify-store-locator' )
			),
			array(
				'name' => 'draggable',
				'type' => 'listbox',
				'values' => array(
					array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
					array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
				),
				'label' => __( 'Mobile Draggable:', 'themify-store-locator' )
			)
		),
		'template' => '[tsl_map<# if ( data.category ) { #> category="{{data.category}}"<# } #><# if ( data.width ) { #> width="{{data.width}}"<# } #><# if ( data.height ) { #> height="{{data.height}}"<# } #><# if ( data.control = (data.control) ? data.control : "yes" ) { #> map_controls="{{data.control}}"<# } #><# if ( data.scrollwheel = (data.scrollwheel) ? data.scrollwheel : "no" ) { #> scrollwheel="{{data.scrollwheel}}"<# } #><# if ( data.draggable = (data.draggable) ? data.draggable : "no" ) { #> mobile_draggable="{{data.draggable}}"<# } #>]'
	),
	'store_list' => array(
		'label' => __( 'Stores List', 'themify-store-locator' ),
		'fields' => array(
                array(
                    'name' => 'category',
                    'type' => 'listbox',
                    'values' => $categories_options,
                    'label' => __( 'Category:', 'themify-store-locator' ),
                    'value' => ''
                ),
                array(
					'name' => 'ppp',
					'type' => 'textbox',
					'label' => __( 'Posts Per Page:', 'themify-store-locator' ),
					'value' => ''
				),
				array(
					'name' => 'layout',
					'type' => 'listbox',
					'label' => __( 'Stores List Layout:', 'themify-store-locator' ),
					'values' => array(
						array( 'value' => 'fullwidth', 'text' => __('Fullwidth', 'themify-store-locator') ),
						array( 'value' => 'grid4', 'text' => 'Grid4' ),
						array( 'value' => 'grid3', 'text' => 'Grid3' ),
						array( 'value' => 'grid2', 'text' => 'Grid2' )
					)
				),
				array(
					'name' => 'contact',
					'type' => 'listbox',
					'values' => array(
						array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
						array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
					),
					'label' => __( 'Store Contact:', 'themify-store-locator' )
				),
				array(
					'name' => 'hours',
					'type' => 'listbox',
					'values' => array(
						array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
						array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
					),
					'label' => __( 'Store Hours:', 'themify-store-locator' )
				),
				array(
					'name' => 'description',
					'type' => 'listbox',
					'values' => array(
						array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
						array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
					),
					'label' => __( 'Store Description:', 'themify-store-locator' )
				),
				array(
					'name' => 'feature_image',
					'type' => 'listbox',
					'values' => array(
						array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
						array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
					),
					'label' => __( 'Feature Image:', 'themify-store-locator' )
				),
				array(
					'name' => 'unlink',
					'type' => 'listbox',
					'values' => array(
						array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
						array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
					),
					'label' => __( 'Unlink Title:', 'themify-store-locator' )
				),
				array(
					'name' => 'pagination',
					'type' => 'listbox',
					'values' => array(
						array( 'value' => 'no', 'text' => __( 'No', 'themify-store-locator' ) ),
						array( 'value' => 'yes', 'text' => __( 'Yes', 'themify-store-locator' ) ),
					),
					'label' => __( 'Pagination:', 'themify-store-locator' )
				),
			),
		'template' => '[tsl_stores<# if ( data.category ) { #> category="{{data.category}}"<# } #><# if ( data.ppp ) { #> posts_per_page="{{data.ppp}}"<# } #><# if ( data.layout ) { #> layout="{{data.layout}}"<# } #><# if ( data.hours = (data.hours) ? data.hours : "yes" ) { #> hours="{{data.hours}}"<# } #><# if ( data.contact = (data.contact) ? data.contact : "yes" ) { #> contact="{{data.contact}}"<# } #><# if ( data.description = (data.description) ? data.description : "yes" ) { #> description="{{data.description}}"<# } #><# if ( data.feature_image = (data.feature_image) ? data.feature_image : "yes" ) { #> feature_image="{{data.feature_image}}"<# } #><# if ( data.unlink = (data.unlink) ? data.unlink : "no" ) { #> unlink_title="{{data.unlink}}"<# } #><# if ( data.pagination = (data.pagination) ? data.pagination : "no" ) { #> pagination="{{data.pagination}}"<# } #>]'
	)
);
