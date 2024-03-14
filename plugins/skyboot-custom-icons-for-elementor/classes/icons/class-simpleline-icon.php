<?php
namespace Skb_Cife;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*--------------------------
*   Class Simple Line Icon Manager
* -------------------------*/
class Skb_Cife_Simple_Line_Icon_Manager{

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
        add_filter( 'elementor/icons_manager/additional_tabs', [ $this,'skb_cife_simple_line_icon'] );  

    }

	public function skb_cife_simple_line_icon( $simple_line_icons_args = array() ) {

	    // Append new icons
	    $simple_line_icons = array(
			'user',
			'people',
			'user-female',
			'user-follow',
			'user-following',
			'user-unfollow',
			'login',
			'logout',
			'emotsmile',
			'phone',
			'call-end',
			'call-in',
			'call-out',
			'map',
			'location-pin',
			'direction',
			'directions',
			'compass',
			'layers',
			'menu',
			'list',
			'options-vertical',
			'options',
			'arrow-down',
			'arrow-left',
			'arrow-right',
			'arrow-up',
			'arrow-up-circle',
			'arrow-left-circle',
			'arrow-right-circle',
			'arrow-down-circle',
			'check',
			'clock',
			'plus',
			'minus',
			'close',
			'event',
			'exclamation',
			'organization',
			'trophy',
			'screen-smartphone',
			'screen-desktop',
			'plane',
			'notebook',
			'mustache',
			'mouse',
			'magnet',
			'energy',
			'disc',
			'cursor',
			'cursor-move',
			'crop',
			'chemistry',
			'speedometer',
			'shield',
			'screen-tablet',
			'magic-wand',
			'hourglass',
			'graduation',
			'ghost',
			'game-controller',
			'fire',
			'eyeglass',
			'envelope-open',
			'envelope-letter',
			'bell',
			'badge',
			'anchor',
			'wallet',
			'vector',
			'speech',
			'puzzle',
			'printer',
			'present',
			'playlist',
			'pin',
			'picture',
			'handbag',
			'globe-alt',
			'globe',
			'folder-alt',
			'folder',
			'film',
			'feed',
			'drop',
			'drawer',
			'docs',
			'doc',
			'diamond',
			'cup',
			'calculator',
			'bubbles',
			'briefcase',
			'book-open',
			'basket-loaded',
			'basket',
			'bag',
			'action-undo',
			'action-redo',
			'wrench',
			'umbrella',
			'trash',
			'tag',
			'support',
			'frame',
			'size-fullscreen',
			'size-actual',
			'shuffle',
			'share-alt',
			'share',
			'rocket',
			'question',
			'pie-chart',
			'pencil',
			'note',
			'loop',
			'home',
			'grid',
			'graph',
			'microphone',
			'music-tone-alt',
			'music-tone',
			'earphones-alt',
			'earphones',
			'equalizer',
			'like',
			'dislike',
			'control-start',
			'control-rewind',
			'control-play',
			'control-pause',
			'control-forward',
			'control-end',
			'volume-1',
			'volume-2',
			'volume-off',
			'calendar',
			'bulb',
			'chart',
			'ban',
			'bubble',
			'camrecorder',
			'camera',
			'cloud-download',
			'cloud-upload',
			'envelope',
			'eye',
			'flag',
			'heart',
			'info',
			'key',
			'link',
			'lock',
			'lock-open',
			'magnifier',
			'magnifier-add',
			'magnifier-remove',
			'paper-clip',
			'paper-plane',
			'power',
			'refresh',
			'reload',
			'settings',
			'star',
			'symbol-female',
			'symbol-male',
			'target',
			'credit-card',
			'paypal',
			'social-tumblr',
			'social-twitter',
			'social-facebook',
			'social-instagram',
			'social-linkedin',
			'social-pinterest',
			'social-github',
			'social-google',
			'social-reddit',
			'social-skype',
			'social-dribbble',
			'social-behance',
			'social-foursqare',
			'social-soundcloud',
			'social-spotify',
			'social-stumbleupon',
			'social-youtube',
			'social-dropbox',
			'social-vkontakte',
			'social-steam'
			
	    );
	    
	    $simple_line_icons_args['skb_cife-simple_line-icon'] = array(
	        'name'          => 'skb_cife-simple_line-icon',
	        'label'         => esc_html__( 'Skyboot:: Simple Line Icon', 'skb_cife' ),
	        'labelIcon'     => 'fas fa-user',
	        'prefix'        => 'icon-',
	        'displayPrefix' => 'icons',
	        'url'           => SKB_CIFE_ASSETS . 'css/simple-line-icons.css',
	        'icons'         => $simple_line_icons,
	        'ver'           => SKB_CIFE_VERSION,
	    );

	    return $simple_line_icons_args;
	}



}
Skb_Cife_Simple_Line_Icon_Manager::instance();