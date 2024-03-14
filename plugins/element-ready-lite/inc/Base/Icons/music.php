<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function element_ready_music_sets(){
	$icons = array(
		'music-001-drum',
		'music-002-equalizer-1',
		'music-003-microphone-1',
		'music-004-ipod-1',
		'music-005-speaker-2',
		'music-006-cassette',
		'music-007-speaker-1',
		'music-008-record-player',
		'music-009-piano',
		'music-010-earphones-1',
		'music-011-music',
		'music-012-equalizer',
		'music-013-settings-1',
		'music-014-cd',
		'music-015-microphone',
		'music-016-ipod',
		'music-017-earphones',
		'music-018-settings',
		'music-019-speaker',
		'music-020-headphones',
	);
	return $icons;
}

class Element_Ready_Add_Music_Icons {
    
    public function __construct() { 
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'element_ready_elementor_music_setup' ] );
	}
    
    public function element_ready_enqueue_music(){
        wp_enqueue_style( 'music', ELEMENT_READY_ROOT_ICON . 'music/music.css', array(), '1.0.1' );
    }

	public function element_ready_elementor_music_setup( $tabs = array()){

		$new_icons = element_ready_music_sets();

		$tabs['music'] = array(
			'name'          => 'music',
			'label'         => esc_html__( 'Music Icons', 'element-ready-lite' ),
			'labelIcon'     => 'music-011-music',
			'prefix'        => '',
			'displayPrefix' => 'music',
			'url'           => esc_url(ELEMENT_READY_ROOT_ICON . 'music/music.css'),
			'icons'         => $new_icons,
			'ver'           => '1.0.0',
		);
		return $tabs;
	}

}
new Element_Ready_Add_Music_Icons();