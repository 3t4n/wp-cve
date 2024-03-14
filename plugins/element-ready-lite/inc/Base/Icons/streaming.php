<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function element_ready_streaming_sets(){
	$icons = array(
		'streaming-001-film-reel',
		'streaming-002-monitor-screen',
		'streaming-003-alarm',
		'streaming-004-musical-note',
		'streaming-005-video-camera',
		'streaming-006-tv-screen',
		'streaming-007-video-player',
		'streaming-008-download',
		'streaming-009-video-player',
		'streaming-010-play-button',
		'streaming-011-laptop-screen',
		'streaming-012-web-browser',
		'streaming-013-thumb-up',
		'streaming-014-projector',
		'streaming-015-mobile-phone',
		'streaming-016-magnifying-glass',
		'streaming-017-video-player',
		'streaming-018-paper',
		'streaming-019-pause',
		'streaming-020-clapperboard',
		'streaming-021-web-browser',
		'streaming-022-wifi-signal',
		'streaming-023-mobile-phone',
		'streaming-024-game-controller',
		'streaming-025-web-browser',
		'streaming-026-laptop-screen',
		'streaming-027-photographic-film',
		'streaming-028-aim',
		'streaming-029-film-roll',
		'streaming-030-dvd',
		'streaming-031-web-browser',
		'streaming-032-fast-forward-button',
		'streaming-033-reload',
		'streaming-034-stop-button',
		'streaming-035-monitor-screen',
		'streaming-036-remote-control',
		'streaming-037-monitor-screen',
		'streaming-038-headphone',
		'streaming-039-spotlight',
		'streaming-040-web-browser',
		'streaming-041-play-button',
		'streaming-042-upload',
		'streaming-043-search',
		'streaming-044-play-button',
		'streaming-045-lens',
		'streaming-046-speech-bubble',
		'streaming-047-vision',
		'streaming-048-cogwheel',
		'streaming-049-wifi-router',
	);
	return $icons;
}

class Element_Ready_Add_Streaming_Icons {
    
    public function __construct() { 
		add_filter( 'elementor/icons_manager/additional_tabs', [ $this, 'element_ready_elementor_streaming_setup' ] );
	}
    
    public function element_ready_enqueue_streaming(){
        wp_enqueue_style( 'streaming', ELEMENT_READY_ROOT_ICON . 'streaming/streaming.css', array(), '1.0.1' );
    }

	public function element_ready_elementor_streaming_setup( $tabs = array()){

		$new_icons = element_ready_streaming_sets();

		$tabs['streaming'] = array(
			'name'          => 'streaming',
			'label'         => esc_html__( 'Online Streaming', 'element-ready-lite' ),
			'labelIcon'     => 'streaming-039-spotlight',
			'prefix'        => '',
			'displayPrefix' => 'streaming',
			'url'           => esc_url(ELEMENT_READY_ROOT_ICON . 'streaming/streaming.css'),
			'icons'         => $new_icons,
			'ver'           => '1.0.0',
		);
		return $tabs;
	}

}
new Element_Ready_Add_Streaming_Icons();