<?php
$activate = array(
        'sidebar-primary' => array(
            'search-1',
            'recent-posts-1',
            'archives-1',
        ),
		'footer-widget-area' => array(
			'search-1',
            'categories-1',
            'archives-1',
			'meta-1'
        ),
		'specia_feature_widget' => array(
			 'specia_feature_widget-1',
			 'specia_feature_widget-2',
			 'specia_feature_widget-3',
			 'specia_feature_widget-4',
			 'specia_feature_widget-5',
			 'specia_feature_widget-6',
			 'specia_feature_widget-7',
			 'specia_feature_widget-8',
			 'specia_feature_widget-9'
        )
    );
    /* the default titles will appear */
		 update_option('widget_categories', array(
			1 => array('title' => 'Categories'), 
			2 => array('title' => 'Categories')));

		update_option('widget_archives', array(
			1 => array('title' => 'Archives'), 
			2 => array('title' => 'Archives')));
			
		update_option('widget_search', array(
			1 => array('title' => 'Search'), 
			2 => array('title' => 'Search')));

		update_option('widget_meta', array(
			1 => array('title' => 'Meta'), 
			2 => array('title' => 'Meta')));	

		update_option('widget_specia_feature_widget', array(
			1 => array('features_widget_title' => 'Flexible Office Hours','features_widget_icon' => 'fa-clock-o','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.'), 
			2 => array('features_widget_title' => 'Macbook Pro','features_widget_icon' => 'fa-apple','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.'),
			3 => array('features_widget_title' => '180 Bottle Wine Vault','features_widget_icon' => 'fa-glass','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.'),
			4 => array('features_widget_title' => 'Well Stocked Fridge','features_widget_icon' => 'fa-cutlery','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.'),
			5 => array('features_widget_title' => 'Generous Holidays','features_widget_icon' => 'fa-umbrella','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.'),
			6 => array('features_widget_title' => 'Public Transport','features_widget_icon' => 'fa-bus','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.'),
			7 => array('features_widget_title' => 'Friday Teatime Talks','features_widget_icon' => 'fa-coffee','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.'),
			8 => array('features_widget_title' => 'Awesome Clients','features_widget_icon' => 'fa-user','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.'),
			9 => array('features_widget_title' => 'Training & Support','features_widget_icon' => 'fa-life-ring','features_widget_description' => 'Pellentesque molestie laoreet ipsum eu laoreet.')
			));	
		
    update_option('sidebars_widgets',  $activate);
	$MediaId = get_option('specia_media_id');
	set_theme_mod( 'custom_logo', $MediaId[0] );
?>