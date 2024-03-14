<?php

namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




final class Blocks {


	const PREFIX = SB_CSP_PREFIX;

	const BLOCKS_DIR = STILLBE_CSP_BASE_DIR. '/blocks';

	private static $instance = null;


	public static function init() {

		if( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;

	}


	// Constructer
	private function __construct() {

		// Check if Gutenberg is active.
		if( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		// Add Custom Block Category
		add_filter( 'block_categories_all', [ $this, 'register_category' ], 10, 2 );

		// Add REST API Endpoints
		add_action( 'init', [ $this, 'register_blocks' ], 5 );

		// Load Scripts & Styles
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		// Load Scripts & Styles
		add_action( 'wp_enqueue_scripts', [ $this, 'front_enqueue_scripts' ] );

	}


	// Load CSS / Javascript for Admin
	public function admin_enqueue_scripts( $hook_suffix ) {

		if( 'post.php' !== $hook_suffix && 'post-new.php' !== $hook_suffix ) {
		//	return;
		}

		// Common
		Main::init()->admin_enqueue_scripts_common();


		// Block; Simple Grid - common.css
		wp_register_style(
			'combine-social-photos-simple-grid-style',
			STILLBE_CSP_BASE_URL. '/blocks/simple-grid/common.css',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/blocks/simple-grid/common.css' )
		);

		// Block; Simple Grid - editor.css
		wp_register_style(
			'combine-social-photos-simple-grid-editor',
			STILLBE_CSP_BASE_URL. '/blocks/simple-grid/editor.css',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/blocks/simple-grid/editor.css' )
		);

		// Block; Simple Grid - editor.js
		wp_register_script(
			'combine-social-photos-simple-grid-editor',
			STILLBE_CSP_BASE_URL. '/blocks/simple-grid/editor.js',
			[ 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-i18n' ],
			@filemtime( STILLBE_CSP_BASE_DIR. '/blocks/simple-grid/editor.js' )
		);

		// Add JS Translate
		Main::add_admin_js_translate_handles( 'combine-social-photos-simple-grid-editor' );


		// Block; Simple Slider - common.css
		wp_register_style(
			'combine-social-photos-simple-slider-style',
			STILLBE_CSP_BASE_URL. '/blocks/simple-slider/common.css',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/blocks/simple-slider/common.css' )
		);

		// Block; Simple Slider - editor.css
		wp_register_style(
			'combine-social-photos-simple-slider-editor',
			STILLBE_CSP_BASE_URL. '/blocks/simple-slider/editor.css',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/blocks/simple-slider/editor.css' )
		);

		// Block; Simple Slider - editor.js
		wp_register_script(
			'combine-social-photos-simple-slider-editor',
			STILLBE_CSP_BASE_URL. '/blocks/simple-slider/editor.js',
			[ 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-i18n' ],
			@filemtime( STILLBE_CSP_BASE_DIR. '/blocks/simple-slider/editor.js' )
		);

		// Add JS Translate
		Main::add_admin_js_translate_handles( 'combine-social-photos-simple-slider-editor' );

		// Common Style - front.css
		wp_register_style(
			'combine-social-photos-common-front',
			STILLBE_CSP_BASE_URL. '/asset/css/front.css',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/asset/css/front.css' )
		);

		// Common Script - class.js
		wp_register_script(
			'combine-social-photos-common-class',
			STILLBE_CSP_BASE_URL. '/asset/js/class.js',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/asset/js/class.js' )
		);

	}


	// Load CSS / Javascript for Admin
	public function front_enqueue_scripts( $hook_suffix ) {

		// Block; Simple Grid - common.css
		wp_register_style(
			'combine-social-photos-simple-grid-style',
			STILLBE_CSP_BASE_URL. '/blocks/simple-grid/common.css',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/blocks/simple-grid/common.css' )
		);


		// Block; Simple Slider - common.css
		wp_register_style(
			'combine-social-photos-simple-slider-style',
			STILLBE_CSP_BASE_URL. '/blocks/simple-slider/common.css',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/blocks/simple-slider/common.css' )
		);

		// Common Style - front.css
		wp_register_style(
			'combine-social-photos-common-front',
			STILLBE_CSP_BASE_URL. '/asset/css/front.css',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/asset/css/front.css' )
		);

		// Common Script - class.js
		wp_register_script(
			'combine-social-photos-common-class',
			STILLBE_CSP_BASE_URL. '/asset/js/class.js',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/asset/js/class.js' )
		);

		// Common Script - front.js
		wp_register_script(
			'combine-social-photos-common-front',
			STILLBE_CSP_BASE_URL. '/asset/js/front.js',
			[],
			@filemtime( STILLBE_CSP_BASE_DIR. '/asset/js/front.js' )
		);
		wp_localize_script(
			'combine-social-photos-common-front',
			'$stillbeCombineSocialPhotos',
			array(
				'rest' => array(
					'cacheUpdateUrl' => esc_url( home_url( '/wp-json/sb-csp-api/v1/media/cache-update' ) ),
				),
				'asset' => array(
					'thumbnail' => array(
						'goInstagram'  => esc_url( STILLBE_CSP_BASE_URL. '/asset/img/thumb-go-ig.png' ),
						'videoPlay'    => esc_url( STILLBE_CSP_BASE_URL. '/asset/img/thumb-video.png' ),
						'cacheExpired' => esc_url( STILLBE_CSP_BASE_URL. '/asset/img/thumb-cache-expired.png' ),
					),
				),
			)
		);

	}


	//
	public function register_category( $block_categories, $editor_context ) {

		if( empty( $editor_context->post ) ) {
			return $block_categories;
		}

		$stillbe_block_category = array(
			'slug'  => 'still-be',
			'title' => 'Still BE',
			'icon'  => null,   // Custom SVG is Added from JS; wp.blocks.updateCategory( 'still-be', { icon: {Icon Element (wp.element.createElement("svg"))} } );
		);

		return [ ...$block_categories, $stillbe_block_category ];

	}


	// Register Blocks
	public function register_blocks() {

		// Simple Grid
		register_block_type( self::BLOCKS_DIR. '/simple-grid', array(
			'render_callback' => [ $this, 'render_simple_grid' ],
		) );

		// Simple Slider
		register_block_type( self::BLOCKS_DIR. '/simple-slider', array(
			'render_callback' => [ $this, 'render_simple_slider' ],
		) );

	}



	public function render_simple_grid( $attributes, $content ) {

		$id = $attributes['id'] ?? 0;

		wp_enqueue_script( 'combine-social-photos-common-front' );

		// Settings
		$_setting = get_option( Setting::SETTING_NAME, array() );
		$accounts = $_setting['accounts'] ?? [];

		// Select an Account using an Authorized ID
		$account = null;
		foreach( (array) $accounts as $_account ) {
			if( $id == $_account->id ) {
				$account = $_account;
				break;
			}
		}

		// ID is not Found...
		if( empty( $account->token->token ) || empty( $account->api_type ) || empty( $account->me->id ) || empty( $account->me->username ) ) {
			return '<!-- ID = '. esc_html( $id ). " is not found.... -->\n<!-- ". esc_html( json_encode( $attributes ) ). '-->';
		}

		// Grid Size (PC)
		$col_pc = $attributes['columns'] ?? 3;
		$row_pc = $attributes['rows']    ?? 3;

		// Grid Size (Tablet)
		$col_tablet = $attributes['columnsTablet'] ?? 3;
		$row_tablet = $attributes['rowsTablet']    ?? 3;

		// Grid Size (SP)
		$col_sp = $attributes['columnsSp'] ?? 3;
		$row_sp = $attributes['rowsSp']    ?? 3;

		// Highlight Size (PC)
		$hightlight_pc = empty( $attributes['isHighlight'] ) ? 1 : ( $attributes['highlightSize'] ?? 2 );

		// Highlight Size (Tablet)
		$hightlight_tablet = empty( $attributes['isHighlightTablet'] ) ? 1 : ( $attributes['highlightSizeTablet'] ?? 2 );

		// Highlight Size (SP)
		$hightlight_sp = empty( $attributes['isHighlightSp'] ) ? 1 : ( $attributes['highlightSizeSp'] ?? 2 );

		// Media Count
		$media_count_pc     = $col_pc     * $row_pc     - $hightlight_pc     * $hightlight_pc     + 1;
		$media_count_tablet = $col_tablet * $row_tablet - $hightlight_tablet * $hightlight_tablet + 1;
		$media_count_sp     = $col_sp     * $row_sp     - $hightlight_sp     * $hightlight_sp     + 1;

		// Getting API
		$get_media_count = max( $media_count_pc, $media_count_tablet, $media_count_sp );
		$getting_api     = self::_get_api_class( $account->api_type ?? $account->token->api );

		if( empty( $getting_api ) ) {
			return '<!-- No API -->';
		}

		// Advanced Getting
		$business_discovery = empty( $attributes['gettingType'] ) || 'business_discovery' !== $attributes['gettingType'] || empty( $attributes['businessDiscovery'] ) ? null : trim( $attributes['businessDiscovery'] );
		$hashtag            = empty( $attributes['gettingType'] ) || 0 !== strpos( $attributes['gettingType'], 'hashtag_' ) || empty( $attributes['hashtag'] )        ? null : trim( $attributes['hashtag'] );
		$advanced           = (object) array(
			'business_discovery' => $business_discovery,
			'hashtag_recent'     => isset( $attributes['gettingType'] ) && 'hashtag_recent' === $attributes['gettingType'] ? $hashtag : null,
			'hashtag_top'        => isset( $attributes['gettingType'] ) && 'hashtag_top'    === $attributes['gettingType'] ? $hashtag : null,
			'exclude_video'      => $attributes['displayingVideo'] && 'ignore' === $attributes['displayingVideo'],
		);

	//	$business_discovery = empty( $attributes['businessDiscovery'] ) ? null : trim( $attributes['businessDiscovery'] );

		/**
		 * @since 0.11.2
		 *   The cache is not checked on the edit screen.
		 */
		$media_data = $getting_api::get_media_data( $account->me->id, $account->token->token, $get_media_count, [], $advanced, empty( $content ) );

		$data = $media_data->data ?? [];

		if( ! empty( $media_data->user ) ) {
			unset( $account->me->follows_count, $account->me->followers_count );
			$account->me = (object) array_merge(
				(array) $account->me,
				(array) $media_data->user
			);
		}

		if( null !== $advanced->business_discovery && isset( $account->name ) ) {
			unset( $account->name );
		}

		$template = apply_filters( 'stillbe_csp/simple_grid__template_path', STILLBE_CSP_BASE_DIR. '/templates/simple-grid.php' );
		$html = require( $template );

		return $html;

	}


	public function render_simple_slider( $attributes, $content ) {

		$id = $attributes['id'] ?? 0;

		wp_enqueue_script( 'combine-social-photos-common-front' );

		// Settings
		$_setting = get_option( Setting::SETTING_NAME, array() );
		$accounts = $_setting['accounts'] ?? [];

		// Select an Account using an Authorized ID
		$account = null;
		foreach( (array) $accounts as $_account ) {
			if( $id == $_account->id ) {
				$account = $_account;
				break;
			}
		}

		// ID is not Found...
		if( empty( $account->token->token ) || empty( $account->api_type ) || empty( $account->me->id ) || empty( $account->me->username ) ) {
			return '<!-- ID = '. esc_html( $id ). ' is not found.... -->'. '<!-- '. json_encode( $attributes ). '-->';
		}

		// Grid Size
		$col = $attributes['columns'] ?? 10;
		$row = $attributes['rows']    ?? 1;

		// Getting API
		$get_media_count = $col * $row;   // $col * $row - $hightlight * $hightlight + 1
		$getting_api     = self::_get_api_class( $account->api_type ?? $account->token->api );

		if( empty( $getting_api ) ) {
			return '<!-- No API -->';
		}

		// Advanced Getting
		$business_discovery = empty( $attributes['gettingType'] ) || 'business_discovery' !== $attributes['gettingType'] || empty( $attributes['businessDiscovery'] ) ? null : trim( $attributes['businessDiscovery'] );
		$hashtag            = empty( $attributes['gettingType'] ) || 0 !== strpos( $attributes['gettingType'], 'hashtag_' ) || empty( $attributes['hashtag'] )        ? null : trim( $attributes['hashtag'] );
		$advanced           = (object) array(
			'business_discovery' => $business_discovery,
			'hashtag_recent'     => isset( $attributes['gettingType'] ) && 'hashtag_recent' === $attributes['gettingType'] ? $hashtag : null,
			'hashtag_top'        => isset( $attributes['gettingType'] ) && 'hashtag_top'    === $attributes['gettingType'] ? $hashtag : null,
			'exclude_video'      => $attributes['displayingVideo'] && 'ignore' === $attributes['displayingVideo'],
		);

		/**
		 * @since 0.11.2
		 *   The cache is not checked on the edit screen.
		 */
		$media_data = $getting_api::get_media_data( $account->me->id, $account->token->token, $get_media_count, [], $advanced, empty( $content ) );

		$data = $media_data->data ?? [];

		if( ! empty( $media_data->user ) ) {
			unset( $account->me->follows_count, $account->me->followers_count );
			$account->me = (object) array_merge(
				(array) $account->me,
				(array) $media_data->user
			);
		}

		if( null !== $advanced->business_discovery && isset( $account->name ) ) {
			unset( $account->name );
		}

		$template = apply_filters( 'stillbe_csp/simple_slider__template_path', STILLBE_CSP_BASE_DIR. '/templates/simple-slider.php' );
		$html = require( $template );

		return $html;

	}


	private function _get_api_class( $type ) {

		if( 'ig_basic_display' === $type || 'Basic Display API' === $type ) {
			return __NAMESPACE__. '\Basic_Display_API';
		}

		if( 'ig_graph' === $type || 'Graph API' === $type ) {
			return __NAMESPACE__. '\Graph_API';
		}

		return null;

	}



}



