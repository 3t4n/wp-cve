<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

if( !class_exists('Stonehenge_EM_OSM_Customize') ) :
Class Stonehenge_EM_OSM_Customize extends Stonehenge_EM_OSM_Metabox {


	#===============================================
	public function custom_markers() {
		$plugin		= $this->plugin;
		$text  		= $plugin['text'];
		if( isset($_GET['page']) && $_GET['page'] === $plugin['slug'] ) {
			$section = array(
				'id' 		=> 'apply_filters',
				'label'		=> __wp('Apply Filters'),
				'fields' 	=> array(
					array(
						'id' 		=> 'intro',
						'label' 	=> 'Intro',
						'type'		=> 'info',
						'default' 	=>  sprintf( __('The customizable markers are based on %s by %s.', $text), ' <a href="https://github.com/coryasilva/Leaflet.ExtraMarkers" target="_blank">Leaflet.ExtraMarkers</a>', 'corysilva') .' '. $this->example_image_markers() .'<br>'. __('Setting a filter will ignore other settings.', $text) ,
					),
					array(
						'id'		=> 'custom_arguments',
						'label'		=> __('Marker Arguments', $text),
						'type' 		=> 'span',
						'default'	=> sprintf('<p>%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p>',
							wp_sprintf("<strong>shape:</strong> %l.", array("'circle'", "'square'", "'star'", "'penta'") ),
							wp_sprintf("<strong>color:</strong> %l.", array("'red'", "'orange-dark'", "'orange'", "'yellow'", "'blue-dark'", "'cyan'", "'purple'", "'violet'", "'pink'", "'green-dark'", "'green'", "'green-light'", "'black'","'white'") ),
							"<strong>icon:</strong> ". sprintf( __('Any icon featured in the <a href=%s target="_blank">%s</a>.', $text), 'https://fontawesome.com/icons?d=gallery&m=free', __('FontAwesome 5 Free Library', $text) ) .' <span class="description">' . sprintf( __('Only use the identifier %s.', $text), '(fa-coffee)') .'</span>',
							sprintf( "<strong>iconColor:</strong> <a href='https://www.quackit.com/css/css_color_codes.cfm' target='_blank'>%s</a>.", __('Any color name or CSS code', $text) ),
							wp_sprintf("<strong>prefix:</strong> %l.", array("'fas'", "'fab'") ). ' <em>' . sprintf( __('(Defaults to %s for solid style.)', $text), "'fas'") .'</em>'),
					),
					array(
						'id' 		=> 'default_marker',
						'label' 	=> __('Default Marker', $text),
						'type' 		=> 'span',
						'default' 	=> $this->demo_default_marker(),
					),
					array(
						'id' 		=> 'custom_marker',
						'label' 	=> __('Location Marker', $text),
						'type' 		=> 'span',
						'default' 	=> '<p class="description">'. sprintf( __('You can easily find the Location ID in the Screen Options (upper right corner) of the %s.', $text), '<a href="'. admin_url('edit.php?post_type=location') .'" target="_blank">'. __('Locations Admin Page', $text) .'</a>') . '</p>'. $this->demo_location_marker(),
					),
					array(
						'id' 		=> 'default_tiles',
						'label' 	=> __('Default Map Style', $text),
						'type' 		=> 'span',
						'default' 	=> '<p class="description">'. __('If you want to add a Map Tile Server that requires an API key, you will need to register your own account there first.', $text) .'</p>'.$this->demo_default_tiles(),
					),
					array(
						'id' 		=> 'location_tiles',
						'label' 	=> __('Location Map Style', $text),
						'type' 		=> 'span',
						'default' 	=> '<p class="description">'. sprintf( __('You can easily find the Location ID in the Screen Options (upper right corner) of the %s.', $text), '<a href="'. admin_url('edit.php?post_type=location') .'" target="_blank">'. __('Locations Admin Page', $text) .'</a>') . '</p>'. $this->demo_location_tiles(),
					),
				)
			);

			echo stonehenge()->render_metabox( $section, $section['id'], 1);
		}
		return false;
	}


	#===============================================
	private static function demo_default_tiles() {
		$demo = '<pre class="code">function my_custom_default_tiles( $url ) {
	$url = \'//tile.thunderforest.com/neighbourhood/{z}/{x}/{y}.png?apikey=[your-apikey]\';
	return $url;
}
add_filter(\'em_osm_default_tiles\', \'my_custom_default_tiles\', 10, 1);</pre>';
		return $demo;
	}

	#===============================================
	private static function demo_location_tiles() {
		$demo = '<pre class="code">function my_custom_location_tiles( $url, $location_id ) {
	if( $location_id === 3 ) {
		$url = \'//tile.thunderforest.com/transport/{z}/{x}/{y}.png?apikey=[your-apikey]\';
	}
	return $url;
}
add_filter(\'em_osm_location_tiles\', \'my_custom_location_tiles\', 10, 2);</pre>';
		return $demo;
	}


	#===============================================
	public function demo_default_marker() {
		$demo = '<pre class="code">function my_default_marker( $marker ) {
	$marker = array(
		\'shape\'		=> \'square\',
		\'color\' 	=> \'green-light\',
		\'icon\'		=> \'fa-walking\',
		\'iconColor\' => \'white\',
	);
	return $marker;
}
add_filter(\'em_osm_default_marker\', \'my_default_marker\', 10, 1);</pre>';
		return $demo;
	}


	#===============================================
	public function demo_location_marker() {
		$demo = '<pre class="code">function my_custom_marker( $marker, $location_id ) {
	if( $location_id === 9 ) {
		$marker = array(
			\'shape\'		=> \'star\',
			\'color\' 	=> \'yellow\',
			\'icon\'		=> \'fa-coffee fa-2x\',
			\'iconColor\' => \'black\',
		);
	}
	return $marker;
}
add_filter(\'em_osm_location_marker\', \'my_custom_marker\', 10, 2);</pre>';
		return $demo;
	}


	#===============================================
	public function example_image_markers() {
		$plugin 	= $this->plugin;
		$example 	= esc_html__('Example Leafet.ExtraMarkers', $plugin['text']);
		$url 		= plugins_url('assets/example-custom-markers.png', __DIR__);
		wp_enqueue_script('thickbox',null,array('jquery'));
		wp_enqueue_style('thickbox.css', '/'.WPINC.'/js/thickbox/thickbox.css', null, $plugin['version']);
		return sprintf( "<a href='{$url}' class='thickbox' title='{$example}' alt='{$example}'>%s</a>", __('Click here for an example.', $plugin['text']) );
	}


} // End class.
endif;