<?php
namespace Skb_Cife;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/*--------------------------
*   Class linearicons Icon Manager
* -------------------------*/
class Skb_Cife_linearicons_Icon_Manager{

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
        add_filter( 'elementor/icons_manager/additional_tabs', [ $this,'skb_cife_linearicons_icons'] );  

    }

	public function skb_cife_linearicons_icons( $linearicons_icons_args = array() ) {

	    // Append new icons
	    $linearicons_icons = array(
								'home',
								'apartment',
								'pencil',
								'magic-wand',
								'drop',
								'lighter',
								'poop',
								'sun',
								'moon',
								'cloud',
								'cloud-upload',
								'cloud-download',
								'cloud-sync',
								'cloud-check',
								'database',
								'lock',
								'cog',
								'trash',
								'dice',
								'heart',
								'star',
								'star-half',
								'star-empty',
								'flag',
								'envelope',
								'paperclip',
								'inbox',
								'eye',
								'printer',
								'file-empty',
								'file-add',
								'enter',
								'exit',
								'graduation-hat',
								'license',
								'music-note',
								'film-play',
								'camera-video',
								'camera',
								'picture',
								'book',
								'bookmark',
								'user',
								'users',
								'shirt',
								'store',
								'cart',
								'tag',
								'phone-handset',
								'phone',
								'pushpin',
								'map-marker',
								'map',
								'location',
								'calendar-full',
								'keyboard',
								'spell-check',
								'screen',
								'smartphone',
								'tablet',
								'laptop',
								'laptop-phone',
								'power-switch',
								'bubble',
								'heart-pulse',
								'construction',
								'pie-chart',
								'chart-bars',
								'gift',
								'diamond',
								'linearicons',
								'dinner',
								'coffee-cup',
								'leaf',
								'paw',
								'rocket',
								'briefcase',
								'bus',
								'car',
								'train',
								'bicycle',
								'wheelchair',
								'select',
								'earth',
								'smile',
								'sad',
								'neutral',
								'mustache',
								'alarm',
								'bullhorn',
								'volume-high',
								'volume-medium',
								'volume-low',
								'volume',
								'mic',
								'hourglass',
								'undo',
								'redo',
								'sync',
								'history',
								'clock',
								'download',
								'upload',
								'enter-down',
								'exit-up',
								'bug',
								'code',
								'link',
								'unlink',
								'thumbs-up',
								'thumbs-down',
								'magnifier',
								'cross',
								'menu',
								'list',
								'chevron-up',
								'chevron-down',
								'chevron-left',
								'chevron-right',
								'arrow-up',
								'arrow-down',
								'arrow-left',
								'arrow-right',
								'move',
								'warning',
								'question-circle',
								'menu-circle',
								'checkmark-circle',
								'cross-circle',
								'plus-circle',
								'circle-minus',
								'arrow-up-circle',
								'arrow-down-circle',
								'arrow-left-circle',
								'arrow-right-circle',
								'chevron-up-circle',
								'chevron-down-circle',
								'chevron-left-circle',
								'chevron-right-circle',
								'crop',
								'frame-expand',
								'frame-contract',
								'layers',
								'funnel',
								'text-format',
								'text-format-remove',
								'text-size',
								'bold',
								'italic',
								'underline',
								'strikethrough',
								'highlight',
								'text-align-left',
								'text-align-center',
								'text-align-right',
								'text-align-justify',
								'line-spacing',
								'indent-increase',
								'indent-decrease',
								'pilcrow',
								'direction-ltr',
								'direction-rtl',
								'page-break',
								'sort-alpha-asc',
								'sort-amount-asc',
								'hand',
								'pointer-up',
								'pointer-right',
								'pointer-down',
								'pointer-left'
	    );
	    
	    $linearicons_icons_args['skb_cife-linearicons-icon'] = array(
	        'name'          => 'skb_cife-linearicons-icon',
	        'label'         => esc_html__( 'Skyboot:: Linearicons Icon', 'skb_cife' ),
	        'labelIcon'     => 'lnr lnr-linearicons',
	        'prefix'        => 'lnr-',
	        'displayPrefix' => 'lnr',
	        'url'           => SKB_CIFE_ASSETS . 'css/linearicons.css',
	        'icons'         => $linearicons_icons,
	        'ver'           => SKB_CIFE_VERSION,
	    );

	    return $linearicons_icons_args;
	}



}
Skb_Cife_linearicons_Icon_Manager::instance();
