<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Dicode_Icons_Simple_Lineicons{

    public static function init() {

        add_filter( 'elementor/icons_manager/additional_tabs', array( __CLASS__, 'el_icons_args' ) );  

    }
	
	public static function icons_list() {
	    $icons = array(
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
		
		return  $icons;
	}
	
	public static function el_icons_args( $tabs = array() ) {
	    
	    $tabs['dicode_icons_simple_lineicons'] = array(
	        'name'          => 'dicode_icons_simple_lineicons',
	        'label'         => esc_html__( 'Dicode - Simple Lineicons', 'dicode-icons-pack' ),
	        'labelIcon'     => 'fas fa-user',
	        'prefix'        => 'icon-',
	        'displayPrefix' => 'icons',
	        'url'           => DICODE_ICONS_ASSETS_URL . 'simple-lineicons/simple-lineicons.min.css',
	        'icons'         => self::icons_list(),
	        'ver'           => DICODE_ICONS_PACK_VERSION,
	    );

	    return $tabs;
	}



}
Dicode_Icons_Simple_Lineicons::init();