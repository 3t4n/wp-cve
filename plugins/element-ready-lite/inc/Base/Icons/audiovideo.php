<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function element_ready_audiovideo_sets(){
	$icons = array(
		'audiovideo-001-album',
		'audiovideo-002-brightness',
		'audiovideo-003-cloud',
		'audiovideo-004-countdown',
		'audiovideo-005-crop',
		'audiovideo-006-duplicate',
		'audiovideo-007-expand',
		'audiovideo-008-film',
		'audiovideo-009-headphone',
		'audiovideo-010-live',
		'audiovideo-011-microphone',
		'audiovideo-012-microphone',
		'audiovideo-013-video-player',
		'audiovideo-014-mixer',
		'audiovideo-015-editor',
		'audiovideo-016-music-edition',
		'audiovideo-017-music-player',
		'audiovideo-018-music-player',
		'audiovideo-019-music',
		'audiovideo-020-music',
		'audiovideo-021-vector',
		'audiovideo-022-picture',
		'audiovideo-023-radio',
		'audiovideo-024-rgb',
		'audiovideo-025-rgb',
		'audiovideo-026-sound',
		'audiovideo-027-speaker',
		'audiovideo-028-storyboard',
		'audiovideo-029-video',
		'audiovideo-030-video',
		'audiovideo-031-video-edition',
		'audiovideo-032-video-edition',
		'audiovideo-033-video-edition',
		'audiovideo-034-video-edition',
		'audiovideo-035-video-edition',
		'audiovideo-036-video-recorder',
		'audiovideo-037-video',
		'audiovideo-038-video',
		'audiovideo-039-volume',
		'audiovideo-040-video',
	);
	return $icons;
}

class Element_Ready_Add_Audiovideo_Icons {
    
    public function __construct() { 
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'element_ready_elementor_audiovideo_setup' ] );
	}
    
    public function element_ready_enqueue_audiovideo(){
        wp_enqueue_style( 'audiovideo', ELEMENT_READY_ROOT_ICON . 'audiovideo/audiovideo.css', array(), '1.0.1' );
    }

	public function element_ready_elementor_audiovideo_setup( $tabs = array()){

		$new_icons = element_ready_audiovideo_sets();

		$tabs['audiovideo'] = array(
			'name'          => 'audiovideo',
			'label'         => esc_html__( 'Audiovideo Icons', 'element-ready-lite' ),
			'labelIcon'     => 'audiovideo-025-rgb',
			'prefix'        => '',
			'displayPrefix' => 'audiovideo',
			'url'           => esc_url(ELEMENT_READY_ROOT_ICON . 'audiovideo/audiovideo.css'),
			'icons'         => $new_icons,
			'ver'           => '1.0.0',
		);
		return $tabs;
	}

}
new Element_Ready_Add_Audiovideo_Icons();