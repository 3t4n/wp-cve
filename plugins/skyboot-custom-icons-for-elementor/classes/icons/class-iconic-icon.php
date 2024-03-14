<?php
namespace Skb_Cife;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*--------------------------
*   Class Iconic Icon Manager
* -------------------------*/
class Skb_Cife_Iconic_Icon_Manager{

    private static $instance = null;

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct(){
        $this->init();
    }

    public function init() {

        // Custom icon filter
        add_filter( 'elementor/icons_manager/additional_tabs', [ $this,'skb_cife_iconic_icon'] );  

    }

	public function skb_cife_iconic_icon( $iconic_icons_args = array() ) {

	    // Append new icons
	    $iconic_icons = array(
			'chat',
			'chat-alt-stroke',
			'chat-alt-fill',
			'comment-alt1-stroke',
			'comment',
			'comment-stroke',
			'comment-fill',
			'comment-alt2-stroke',
			'comment-alt2-fill',
			'checkmark1',
			'check-alt',
			'x',
			'x-altx-alt',
			'denied',
			'cursor',
			'rss3',
			'rss-alt',
			'wrench1',
			'dial',
			'cog1',
			'calendar1',
			'calendar-alt-stroke',
			'calendar-alt-fill',
			'share1',
			'mail1',
			'heart-stroke',
			'heart-fill',
			'movie',
			'document-alt-stroke',
			'document-alt-fill',
			'document-stroke',
			'document-fill',
			'plus1',
			'plus-alt',
			'minus1',
			'minus-alt',
			'pin',
			'link1',
			'bolt',
			'move',
			'move-alt1',
			'move-alt2',
			'equalizer1',
			'award-fill',
			'award-stroke',
			'magnifying-glass',
			'trash-stroke',
			'trash-fill',
			'beaker-alt',
			'beaker',
			'key-stroke',
			'key-fill',
			'new-window',
			'lightbulb',
			'spin-alt',
			'spin',
			'curved-arrow',
			'undo1',
			'reload',
			'reload-alt',
			'loop3',
			'loop-alt1',
			'loop-alt2',
			'loop-alt3',
			'loop-alt4',
			'transfer',
			'move-vertical',
			'move-vertical-alt1',
			'move-vertical-alt2',
			'move-horizontal',
			'move-horizontal-alt1',
			'move-horizontal-alt2',
			'arrow-left1',
			'arrow-left-alt1',
			'arrow-left-alt2',
			'arrow-right1',
			'arrow-right-alt1',
			'arrow-right-alt2',
			'arrow-up1',
			'arrow-up-alt1',
			'arrow-up-alt2',
			'arrow-down1',
			'arrow-down-alt1',
			'arrow-down-alt2',
			'cd',
			'steering-wheel',
			'microphone',
			'headphones1',
			'volume',
			'volume-mute1',
			'play1',
			'pause1',
			'stop1',
			'eject1',
			'first1',
			'last1',
			'play-alt',
			'fullscreen-exit',
			'fullscreen-exit-alt',
			'fullscreen',
			'fullscreen-alt',
			'iphone',
			'battery-empty',
			'battery-half',
			'battery-full',
			'battery-charging',
			'compass1',
			'box1',
			'folder-stroke',
			'folder-fill',
			'at',
			'ampersand',
			'info1',
			'question-mark',
			'pilcrow1',
			'hash',
			'left-quote',
			'right-quote',
			'left-quote-alt',
			'right-quote-alt',
			'article',
			'read-more',
			'list1',
			'list-nested',
			'book1',
			'book-alt',
			'book-alt2',
			'pen1',
			'pen-alt-stroke',
			'pen-alt-fill',
			'pen-alt2',
			'brush',
			'brush-alt',
			'eyedropper1',
			'layers-alt',
			'layers',
			'image1',
			'camera1',
			'aperture',
			'aperture-alt',
			'chart',
			'chart-alt',
			'bars',
			'bars-alt',
			'eye1',
			'user1',
			'home1',
			'clock1',
			'lock-stroke',
			'lock-fill',
			'unlock-stroke',
			'unlock-fill',
			'tag-stroke',
			'tag-fill',
			'sun-stroke',
			'sun-fill',
			'moon-stroke',
			'moon-fill',
			'cloud1',
			'rain',
			'umbrella',
			'star',
			'map-pin-stroke',
			'map-pin-fill',
			'map-pin-alt',
			'target1',
			'download1',
			'upload1',
			'cloud-download1',
			'cloud-upload1',
			'fork',
			'paperclip'
	    );
	    
	    $iconic_icons_args['skb_cife-iconic-icon'] = array(
	        'name'          => 'skb_cife-iconic-icon',
	        'label'         => esc_html__( 'Skyboot:: Iconic Icons', 'skb_cife' ),
	        'labelIcon'     => 'fa fa-user',
	        'prefix'        => 'iconic-',
	        'displayPrefix' => 'iconic',
	        'url'           => SKB_CIFE_ASSETS . 'css/iconic.css',
	        'icons'         => $iconic_icons,
	        'ver'           => SKB_CIFE_VERSION,
	    );

	    return $iconic_icons_args;
	}



}
Skb_Cife_Iconic_Icon_Manager::instance();