<?php

namespace GT3\PhotoVideoGallery;

defined('ABSPATH') or exit;

class Settings {
	private static $key      = 'gt3pg_lite';
	private static $instance = null;
	private        $defaults = array();
	private        $settings = array();
	private        $blocks   = array(
		'Grid',
		'Masonry',
	);
	
	public static function instance(){
		if(!self::$instance instanceof self) {
			self::$instance = new self();
		}
		
		return self::$instance;
	}
	
	private function __construct(){
		$this->initDefaultsSettings();
		
		add_action('init', array( $this, 'init' ), 1);
	}
	
	private function initDefaultsSettings(){
		$this->defaults = array();
		
		$lightboxSettings = array(
			'lightboxAutoplay'     => '0',
			'lightboxContinuous'   => '1',
			'lightboxAutoplayTime' => 6,
			'lightboxThumbnails'   => '1',
			'lightboxImageSize'    => 'full',
			'lightboxShowTitle'    => '1',
			'lightboxShowCaption'  => '1',
			'lightboxTheme'        => 'dark',
			'lightboxDeeplink'     => '0',
			'lightboxAllowZoom'    => '0',
			'allowDownload'        => '0',
			'ytWidth'              => '0',
			'lightboxCover'        => '0',
			'socials'              => '1',
		);
		
		$borderSettings = array(
			'borderType'    => '0',
			'borderColor'   => '#dddddd',
			'borderPadding' => 0,
			'borderSize'    => 1,
		);
		
		$this->defaults['basic'] = array_merge(
			$lightboxSettings,
			array(
				'random'         => '0',
				'rightClick'     => '0',
				'lazyLoad'       => '0',
				'loadMoreEnable' => '0',
				'loadMoreFirst'  => 12,
				'loadMoreLimit'  => 4,
				'filterEnable'   => '0',
				'usage'          => '0',
			)
		);
		
		$this->defaults['grid'] = array_merge(
			$borderSettings,
			array(
				'watermark'   => '0',
				'gridType'    => 'square',
				'showTitle'   => '0',
				'linkTo'      => 'lightbox',
				'showCaption' => '0',
				'imageSize'   => 'thumbnail',
				'columns'     => '3',
				'margin'      => '20',
				'cornersType' => 'standard',
			)
		);
		
		$this->defaults['masonry'] = array_merge(
			$borderSettings,
			array(
				'watermark'   => '0',
				'showTitle'   => '0',
				'linkTo'      => 'lightbox',
				'showCaption' => '0',
				'imageSize'   => 'thumbnail',
				'columns'     => '3',
				'margin'      => '30',
				'cornersType' => 'standard',
			)
		);
		
		$this->defaults['packery'] = array_merge(
			$borderSettings,
			array(
				'watermark'   => '0',
				'packery'     => 2,
				'linkTo'      => 'lightbox',
				'imageSize'   => 'thumbnail',
				'margin'      => 30,
				'cornersType' => 'standard',
			)
		);
		
		$this->defaults['thumbnails'] = array(
			'thumbnails_Controls'            => '1',
			'thumbnails_Size'                => '16x9',
			'thumbnails_thumbnailsImageSize' => 'medium_large',
			'thumbnails_thumbnailsSize'      => 'fixed',
			'thumbnails_Lightbox'            => '0',
			'imageSize'                      => 'medium_large',
		);
		
		$this->defaults['slider'] = array(
			'sliderAllowZoom'    => '0',
			'sliderAutoplay'     => '0',
			'sliderAutoplayTime' => 6,
			'sliderThumbnails'   => '1',
			'sliderCover'        => '0',
			'sliderImageSize'    => 'full',
			'ytWidth'            => '0',
			'allowDownload'      => '0',
			'socials'            => '1',
			'sliderShowTitle'    => '1',
			'sliderShowCaption'  => '1',
			'sliderTheme'        => 'dark',
		);
		
		$this->defaults['fsslider'] = array(
			'socials'            => '1',
			'autoplay'           => '1',
			'interval'           => 4,
			'thumbnails'         => '1',
			'showTitle'          => '1',
			'showCaption'        => '1',
			'scroll'             => '1',
			'boxed'              => '0',
			'cover'              => '0',
			'imageSize'          => 'full',
			'footerAboveSlider'  => '0',
			'textColor'          => '#ffffff',
			'borderOpacity'      => 0.5,
			'externalVideoThumb' => '1',
		);
		
		$this->defaults['kenburns'] = array(
			'moduleHeight'   => '100%',
			'interval'       => 4,
			'transitionTime' => 600,
			'overlayState'   => '0',
			'overlayBg'      => '#ffffff',
		);
		
		$this->defaults['shift']     = array(
			'expandable'     => '0',
			'showTitle'      => '1',
			'controls'       => '1',
			'infinityScroll' => '1',
			'autoplay'       => '1',
			'interval'       => 4,
			'transitionTime' => 600,
			'moduleHeight'   => '100%',
		);
		$this->defaults['instagram'] = array(
			'loadMoreFirst' => 12,
			'columns'       => 4,
			'gridType'      => 'square',
			'margin'        => 30,
			'source'        => 'user',
			'userName'      => '',
			'userID'        => '',
			'tag'           => '',
			'linkTo'        => 'instagram',
		);
		
		$this->defaults['justified'] = array(
			'loader'       => 'fromFirst',
			'gap'          => '10',
			'lightbox'     => '0',
			'height'       => 240,
			'fadeDuration' => 200,
			'fadeDelay'    => 120,
			'imageSize'    => 'full',
		);
		
		$this->defaults['beforeafter'] = array(
			'image_before' => '',
			'image_after'  => '',
			'moduleHeight' => '100%',
		);
		
		$this->defaults['basic']['watermark'] = array(
			'enable'    => false,
			'upload'    => false,
			'alignment' => 'right_bottom',
			'position'  => array(
				'x' => 100,
				'y' => 100,
			),
			'image'     => array(
				'id'    => 0,
				'url'   => '',
				'ratio' => 1
			),
			'sizes'     => array(
				'medium_large' => true,
				'large'        => true,
				'full'         => true,
			),
			//			'width'     => 100,
			//			'height'    => 100,
			'opacity'   => 100,
			'quality'   => 95,
		);
		
	}
	
	
	function getDefaultsSettings(){
		return $this->defaults;
	}
	
	
	function init(){
		$options = get_option(self::$key, '');
		
		try {
			if(!is_array($options) && is_string($options)) {
				$options = json_decode($options, true);
				if(json_last_error() || !is_array($options) || !count($options)) {
					$options = array();
				}
			}
		} catch(\Exception $exception) {
			$options = array();
		}
		
		$options        = array_replace_recursive($this->defaults, $options);
		$this->settings = $options;
		
		$this->blocks = apply_filters('gt3pg-lite/blocks/allowed', $this->blocks);
		
		foreach($this->blocks as $block) {
			$block = __NAMESPACE__.'\\Block\\'.$block;
			if(class_exists($block)) {
				/** @var Block\Basic $block */
				$block::instance();
			};
		}
		
		if(Watermark::check_folder()) {
			$watermark_settings = $this->settings['basic']['watermark'];
			add_action('delete_attachment', array( Watermark::class, 'delete_attachment_handler' ));
			if(!!$watermark_settings['enable'] && !!$watermark_settings['upload']) {
				add_filter('wp_handle_upload', array( Watermark::class, 'wp_handle_upload_handler' ), 10, 2);
			}
		}
	}
	
	function getSettings($module = false){
		if($module && key_exists($module, $this->settings)) {
			return $this->settings[$module];
		}
		
		return $this->settings;
	}
	
	function getBlocks(){
		return $this->blocks;
	}
	
	function setSettings($settings){
		if(!is_array($settings) || !count($settings)) {
			return false;
		}
		$this->settings = $settings;
		update_option(self::$key, wp_json_encode($settings));
		
		return true;
	}
	
	function resetSettings(){
		$this->setSettings($this->getDefaultsSettings());
	}
}

