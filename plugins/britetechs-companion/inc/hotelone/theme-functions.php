<?php
function bc_testimonial_default_data(){
	return array(
		array(
			'photo' => 
			array(
               'url' => bc_plugin_url . '/inc/hotelone/img/testi-1.jpg',
                'id' => 51,
             ),
			'name' => 'Kely Wathson',
			'designation' => 'Founder',
			'review' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi … ',
			'link' => '#',
		),
		array(
			'photo' => 
			array(
               'url' => bc_plugin_url . '/inc/hotelone/img/testi-1.jpg',
                'id' => 51,
             ),
			'name' => 'Kely Wathson',
			'designation' => 'Founder',
			'review' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi … ',
			'link' => '#',
		),
	);
}

if( !function_exists('bc_get_section_testimonial_data') ){
	function bc_get_section_testimonial_data(){
		$testimonials = get_theme_mod('hotelone_testimonial_items');
		if (is_string($testimonials)) {
            $testimonials = json_decode($testimonials, true);
        }
		
		$testi_data = array();
		if (!empty($testimonials) && is_array($testimonials)) {
            foreach ($testimonials as $k => $v) {
               $testi_data[] = wp_parse_args($v, array(
                            'photo' => '',
                            'name' => '',
                            'review' => '',
                            'designation' => '',
                            'link' => '#',
                        ));
            }
        }
		return $testi_data;
	}
}

function bc_team_default_data(){
	return array(
		array(
			'image' => 
			array(
               'url' => bc_plugin_url . '/inc/hotelone/img/team1.jpg',
                'id' => 51,
             ),
			'name' => 'Kely Wathson',
			'designation' => 'Founder',
			'facebook_hide' => false,
         'facebook' => '',
         'twitter_hide' => false,
         'twitter' => '',
			'link' => '#',
		),
		array(
			'image' => 
			array(
               'url' => bc_plugin_url . '/inc/hotelone/img/team2.jpg',
                'id' => 51,
             ),
			'name' => 'Kely Wathson',
			'designation' => 'Founder',
			'facebook_hide' => false,
         'facebook' => '',
         'twitter_hide' => false,
         'twitter' => '',
			'link' => '#',
		),
		array(
			'image' => 
			array(
               'url' => bc_plugin_url . '/inc/hotelone/img/team3.jpg',
                'id' => 51,
             ),
			'name' => 'Kely Wathson',
			'designation' => 'Founder',
			'facebook_hide' => false,
         'facebook' => '',
         'twitter_hide' => false,
         'twitter' => '',
			'link' => '#',
		),
	);
}

if( !function_exists('bc_get_section_team_data') ){
	function bc_get_section_team_data(){
		$team = get_theme_mod('hotelone_team_members');
		if (is_string($team)) {
            $team = json_decode($team, true);
        }
		
		$team_data = array();
		if (!empty($team) && is_array($team)) {
            foreach ($team as $k => $v) {
               $team_data[] = wp_parse_args($v, array(
                            'image' => '',
                            'name' => '',
                            'designation' => '',
                            'facebook_hide' => true,
                            'facebook' => '',
                            'twitter_hide' => true,
                            'twitter' => '',
                            'link' => '#',
                        ));
            }
        }
		return $team_data;
	}
}