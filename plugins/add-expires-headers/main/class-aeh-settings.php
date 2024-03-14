<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/*
* Declaring Class
*/

class AEH_Settings {
	private static $instance = null;
  public static function get_instance() {
    if ( ! self::$instance ) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public $expires_headers_image_types = array(
    'gif' => true,
    'ico' => true,
    'jpeg'=> true,
    'jpg'=> true,
		'png'=> true,
		'tiff'=> true,
		'webp'=> true,
  );
  public $expires_headers_audio_types = array(
    'dct' => true,
    'gsm' => true,
    'mp3'=> true,
    'ogg'=> true,
		'raw'=> true,
		'vox'=> true,
		'wav'=>true,
  );
  public $expires_headers_minify_default_excludes = array('/jquery-migrate.js',
		'/jquery-migrate.min.js',
		'/jquery.min.js',
		'/jquery.js',
		'/themes/Avada/assets/js/main.min.js',
		'/plugins/woocommerce-product-search/js/product-search.js',
		'/plugins/revslider/public/assets/js/jquery.themepunch.tools.min.js',
		'/js/TweenMax.min.js',
		'/themes/jupiter/assets/js/min/full-scripts',
		'/plugins/LayerSlider/static/layerslider/js/greensock.js',
		'/themes/kalium/assets/js/main.min.js',
		'/js/mediaelement/',
		'/plugins/elementor/assets/js/common.min.js',
		'/plugins/elementor/assets/js/frontend.min.js',
		'/plugins/elementor-pro/assets/js/frontend.min.js',
		'/themes/kalium/assets/js/main.min.js','/html5shiv.js',
		'/html5shiv-printshiv.min.js',
		'/excanvas.js',
		'/avada-ie9.js',
		'/respond.js',
		'/respond.min.js',
		'/selectivizr.js',
		'/Avada/assets/css/ie.css',
		'/html5.js',
		'/IE9.js',
		'/fusion-ie9.js',
		'/vc_lte_ie9.min.css',
		'/old-ie.css',
		'/ie.css',
		'/vc-ie8.min.css',
		'/mailchimp-for-wp/assets/js/third-party/placeholders.min.js',
		'/assets/js/plugins/wp-enqueue/min/webfontloader.js',
		'/a.optnmstr.com/app/js/api.min.js',
		'/pixelyoursite/js/public.js',
		'/assets/js/wcdrip-drip.js',
		'/uncode/library/js/min/init.min.js',
    '/common-modules.js',
    '/common-modules.min.js',
	);
	public $expires_headers_minify_pb_keywords = array(
		'vc_action',
		'elementor',
		'customize_theme',
		'preview_id',
		'tve',
		'et_fb',
		'PageSpeed'
	);
  public $expires_headers_video_types = array(
    '3gp' => true,
    'avi' => true,
    'flv'=> true,
    'mkv'=> true,
		'mp4'=> true,
		'webm'=> true,
		'wmv'=>true,
  );
	public $expires_headers_font_types = array(
    'otf' => true,
		'ttf' => true,
		'woff' => true,
		'woff2' => true,
  );
  public $expires_headers_text_types = array(
    'css' => true,
  );
  public $expires_headers_application_types = array(
    'js' => true,
		'javascript' => true,
		'x-javascript' => true,
  );
  public $expires_headers_general_settings = array(
    'image' => false,
    'audio' => false,
    'video' => false,
		'font' => false,
    'text' => false,
    'application' => false,
  );
  public $expires_headers_days_settings = array(
    'image' => 375,
    'audio' => 375,
    'video' => 375,
		'font'=> 375,
    'text' => 375,
    'application' => 375,
  );
	public $expires_headers_clear_all_cache = array(
		'Breeze_PurgeCache' => array('Breeze_PurgeCache','breeze_cache_flush'),
		'cachify_flush_cache' => 'cachify_flush_cache',
		'comet_cache' =>array('comet_cache','clear'),
		'just_test' => 'just_test',
		'rocket_clean_domain' => 'rocket_clean_domain',
		'sg_cachepress_purge_cache' => 'sg_cachepress_purge_cache',
		'Swift_Performance_Cache' => array('Swift_Performance_Cache','clear_all_cache'),
		'wp_cache_flush' => 'wp_cache_flush',
		'w3tc_pgcache_flush' => 'w3tc_pgcache_flush',
		'wp_cache_clear_cache' => 'wp_cache_clear_cache',
		'zencache' => array('zencache','clear'),
	);
	public $expires_headers_minify_settings = array(
    'process_css' => true,
    'min_css' => true,
    'inline_footer_css' => true,
		'async_css' => false,
		'min_html'=> true,
		'inline_gfonts' => true,
		'escape_admin'=>false,
  );

	public function init_minify_default() {
    $defaults = array(
			'process_css' => true,
	    'min_css' => true,
	    'inline_footer_css' => true,
			'async_css' => false,
			'min_html'=> true,
			'inline_gfonts' => true,
			'escape_admin' => false,
    );
    return $defaults;
  }

  public function parse_expires_headers_settings($settings) {
    $args = array(
      'general'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'image'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'audio'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'video'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
			'font'          => array(
				'filter' => FILTER_VALIDATE_BOOLEAN,
				'flags'  => FILTER_REQUIRE_ARRAY,
			),
      'text'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'application'          => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
      'expires_days'         => array(
        'filter' => FILTER_VALIDATE_INT,
        'flags'  => FILTER_REQUIRE_ARRAY,
      ),
    );
    $settings = filter_var_array( $settings, $args );
    return $settings;
  }

	public function parse_expires_headers_minify_escape_settings($str){
		$str = filter_var($str, FILTER_SANITIZE_STRING);
		return $str;
	}

	public function parse_expires_headers_minify_settings($settings) {
		$settings = filter_var_array( $settings,FILTER_VALIDATE_BOOLEAN );
    return $settings;
	}

	public function parse_expires_headers_main_settings($settings) {
    $args = array(
			'expires_headers' => array(
        'filter' => FILTER_VALIDATE_BOOLEAN,
      ),
		);
    $settings = filter_var_array( $settings, $args );
    return $settings;
  }

  public function init_general_defaults() {
		$defaults = array(
			'general' => array(
        'image' => true,
        'audio' => false,
        'video' => false,
				'font' => false,
        'text' => false,
        'application' => false,
			),
			'image'   => array(
        'gif' => true,
        'ico' => true,
        'jpeg'=> true,
        'jpg'=> true,
        'png'=> true,
        'tiff'=> true,
        'webp'=>true,
			),
			'audio'       => array(
        'dct' => true,
        'gsm' => true,
        'mp3'=> true,
        'ogg'=> true,
        'raw'=> true,
        'vox'=> true,
        'wav'=>true,
			),
			'video'         => array(
        '3gp' => true,
        'avi' => true,
        'flv'=> true,
        'mkv'=> true,
        'mp4'=> true,
        'webm'=> true,
        'wmv'=>true,
			),
			'font' => array(
        'otf' => true,
				'ttf' => true,
				'woff' => true,
				'woff2' => true,
			),
			'text'   =>array(
        'css' => true,
      ),
			'application' => array(
        'js' => true,
				'javascript' => true,
				'x-javascript' => true,
      ),
			'expires_days' => array (
        'image' => 375,
        'audio' => 375,
        'video' => 375,
				'font' => 375,
        'text' => 375,
        'application' => 375,
      ),
		);
    return $defaults;
	}
}
