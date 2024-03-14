<?php 
return [
	'advanced_settings'=> array(
	    'type'=> 'group',
	    'heading'=> 'Advanced Settings',
	    'description' => '',
	    'options'=> array(	    	
	    	// 'search' => [
			// 	'type' =>'textfield',
			// 	'heading' => 'Search',
			// 	'default' => '',
			// ],
			// 'replace' => [
			// 	'type' =>'textfield',
			// 	'heading' => 'Replace',
			// 	'default' => '',
			// ],
			'$content' => [
				'heading' => 'Template',
				'type' =>'text-editor',
				'full_width' => false,
				'height'     => '100px',
				'tinymce'    => false,
				"description" => "[ux_image id=\"XXX\"]"
			],
			'css' => [
				'type' =>'textarea',
				'heading' => 'CSS',
				'default' => '',
				'placeholder' => "",				
			],
			'class' => [
				'type' =>'textfield',
				'heading' => 'Class',
				'default' => '',
				'placeholder' => "",				
			],
			'visibility'  => array(
				'type' => 'select',
				'heading' => 'Visibility',
				'default' => '',
				'options' => array(
					''   => 'Visible',
					'hidden'  => 'Hidden',
					'hide-for-medium'  => 'Only for Desktop',
					'show-for-small'   =>  'Only for Mobile',
					'show-for-medium hide-for-small' =>  'Only for Tablet',
					'show-for-medium'   =>  'Hide for Desktop',
					'hide-for-small'   =>  'Hide for Mobile',
				),
			),
			
			
	    )
	)
];

