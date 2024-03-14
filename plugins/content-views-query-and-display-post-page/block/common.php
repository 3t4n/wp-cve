<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_Common {

	function __construct() {
		add_filter( 'block_categories_all', array( $this, 'add_block_category' ), PHP_INT_MAX, 2 );
		add_filter( PT_CV_PREFIX_ . 'set_view_settings', array( $this, 'set_block_pagination_settings' ) );
		add_filter( PT_CV_PREFIX_ . 'view_html', array( $this, 'prepend_heading_html' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_enqueue_assets' ) );
		add_action('admin_enqueue_scripts', array($this, 'enqueue_for_blocklib_page'));
	}

	function add_block_category( $categories, $editor_context ) {
		array_unshift( $categories, array(
			'slug'	 => 'contentviews-blocks',
			'title'	 => __( 'Content Views - Post Grid & Filter', 'content-views-query-and-display-post-page' )
		) );

		return $categories;
	}

	// Generate view settings from block attributes in post content
	function set_block_pagination_settings( $args ) {
		if ( !empty( $_POST[ 'isblock' ] ) ) {
			$apost	 = get_post( (int) $_POST[ 'postid' ] );
			$content = isset( $apost->post_content ) ? $apost->post_content : '';
			if ( has_blocks( $content ) ) {
				$blocks = parse_blocks( $content );
				$this->parseBlocks( $args, $blocks, cv_sanitize_vid( $_POST[ 'isblock' ] ), cv_sanitize_vid( $_POST[ 'sid' ] ) );
			}
		}

		return $args;
	}

	function parseBlocks( &$settings, $blocks, $block_name, $block_id ) {
		foreach ( $blocks as $block ) {
			if ( $settings ) {
				return;
			}

			if ( !empty( $block[ 'innerBlocks' ] ) ) {
				$this->parseBlocks( $settings, $block[ 'innerBlocks' ], $block_name, $block_id );
			} else if ( !empty( $block[ 'blockName' ] ) ) {
				if ( $block[ 'blockName' ] === 'contentviews/' . $block_name && $block[ 'attrs' ][ 'blockId' ] === $block_id ) {
					// manually get default value of attributes (it is done automatically when render in block editor)
					$default_atts = [];
					foreach ( $GLOBALS[ 'contentviews_blocks' ][ $block_name ]->attributes as $key => $value ) {
						if ( isset( $value[ 'default' ] ) ) {
							$default_atts[ $key ] = $value[ 'default' ];
						}
					}
					$final_atts	 = array_merge( $default_atts, $block[ 'attrs' ] );
					$block_data	 = $GLOBALS[ 'contentviews_blocks' ][ $block_name ]->get_attributes_and_settings( $final_atts );
					$settings	 = $block_data[ 1 ];
					break;
				}
			}
		}
	}

	function prepend_heading_html( $html ) {
		if ( !defined( 'PT_CV_DOING_PAGINATION' ) ) {
			$show_heading = PT_CV_Functions::setting_value( PT_CV_PREFIX . 'showHeading' );
			if ( !empty( $show_heading ) ) {
				$heading = ContentViews_Block::heading_output();
				$html	 = $heading . $html;
			}
		}

		return $html;
	}

	function block_enqueue_assets() {
		wp_enqueue_style(
		'contentviews-block-style', plugins_url( 'assets/block.css', __FILE__ ), array(), PT_CV_VERSION
		);

		$asset_file = include( dirname( __FILE__ ) . '/build/index.asset.php');
		wp_enqueue_script(
		'contentviews-block-script', plugins_url( 'build/index.js', __FILE__ ), $asset_file[ 'dependencies' ], $asset_file[ 'version' ], true // in footer is required
		);

		// some filters only for Block
		if ( !isset( $GLOBALS[ 'cv_outside_gutenberg' ] ) ) {
			add_filter( PT_CV_PREFIX_ . 'post_types_list', array( 'PT_CV_Hooks', 'filter_post_types_list' ) );
			add_filter( PT_CV_PREFIX_ . 'pagination_styles', array( 'PT_CV_Hooks', 'filter_pagination_styles' ) );
			add_filter( PT_CV_PREFIX_ . 'regular_orderby', array( 'PT_CV_Hooks', 'filter_regular_orderby' ), 11 );
			$GLOBALS[ 'cvBlock' ] = true;
		}

		$js_data = array(
			'plugin_url' => PT_CV_URL,
			'_nonce'	 => wp_create_nonce( 'contentviews_ajax_nonce' ),
			'texts'		 => array(
				'title'			 => 'Content Views - Standard',
				'description'	 => __( 'Select and display one of your views.', 'content-views-query-and-display-post-page' ),
				'keywords'		 => [
					'content',
					__( 'grid', 'content-views-query-and-display-post-page' ),
					__( 'content', 'content-views-query-and-display-post-page' ),
					__( 'view', 'content-views-query-and-display-post-page' ),
					__( 'post', 'content-views-query-and-display-post-page' ),
				],
			),
			'data'		 => array(
				'has_pro' => get_option( 'pt_cv_version_pro' ),
				'require_version' => self::get_require_version(),
				'license_key'  => self::get_site_license(),
				'api_link'	   => CVB_API_URL,
				'upgrade_link' => 'https://www.contentviewspro.com/pricing/',
				'upgrade_text' => __( "Upgrade Now", "content-views-query-and-display-post-page" ),
				'button_text'  => __( "Content Views Library", "content-views-query-and-display-post-page" ),
				'confirm_text' => __( "Press OK/Enter to finish importing", "content-views-query-and-display-post-page" ),
				'hide_button'  => PT_CV_Functions::get_option_value( 'hide_toolbar_button' ),
				'layout_img' => plugins_url( 'assets/layouts/', __FILE__ ),
				'pre_layouts'	 => self::layout_variants(),
				'request_method' => self::set_request_method(),
				'woo_pick' => self::woo_pick_options(),
				'post_types' => PT_CV_Values::post_types(),
				'taxonomies' => PT_CV_Values::taxonomy_list(),
				'taxorelation' => PT_CV_Values::taxonomy_relation(),
				'taxooperator' => self::taxo_operators(),
				'terms'		 => self::terms_list(),
				'post_types_vs_taxonomies' => PT_CV_Values::post_types_vs_taxonomies(),
				'authors'	 => PT_CV_Values::user_list(),
				'author_current' => false,
				'sticky_options' => false,
				'manual_excerpt_options' => false,
				'html_excerpt_options' => false,
				'date_options'	 => self::get_date_options(),
				'orderby'	 => self::get_orderby_options(),
				'orderby_pro'				 => self::pro_sortby(),
				'orders'	 => PT_CV_Values::orders(),
				'title_tags' => PT_CV_Values::title_tag(),
				'content_show'	 => array_merge( PT_CV_Values::content_show() ),
				'thumb_positions' => PT_CV_Values::thumbnail_position(),
				'img_sizes'		 => PT_CV_Values::field_thumbnail_sizes(),
				'img_sub'		 => self::img_sub_options(),
				'paging_types'	 => PT_CV_Values::pagination_types(),
				'paging_styles'	 => PT_CV_Values::pagination_styles(),
				'fields'		 => ContentViews_Block::get_fields(),
				'fields_sort'				 => self::fields_sortable(),
				'fields_sort_pro'			 => self::pro_fields_sortable(),
				'style_options'	 => ContentViews_Block::style_options(),
				'field_toggles'	 => ContentViews_Block::field_toggles(),
				'target_options' => PT_CV_Values::open_in(),
				'border_styles'				 => array(
					'none'	 => __( 'None', 'content-views-query-and-display-post-page' ),
					'solid'	 => __( 'Solid', 'content-views-query-and-display-post-page' ),
					'dotted' => __( 'Dotted', 'content-views-query-and-display-post-page' ),
					'dashed' => __( 'Dashed', 'content-views-query-and-display-post-page' ),
					'double' => __( 'Double', 'content-views-query-and-display-post-page' ),
					'groove' => __( 'Groove', 'content-views-query-and-display-post-page' ),
					'ridge'	 => __( 'Ridge', 'content-views-query-and-display-post-page' ),
					'inset'	 => __( 'Inset', 'content-views-query-and-display-post-page' ),
					'outset' => __( 'Outset', 'content-views-query-and-display-post-page' ),
				),
				'heading_styles'			 => array(
					'heading1'	 => __( 'Heading 1', 'content-views-query-and-display-post-page' ),
					'heading2'	 => __( 'Heading 2', 'content-views-query-and-display-post-page' ),
					'heading3'	 => __( 'Heading 3', 'content-views-query-and-display-post-page' ),
					'heading4'	 => __( 'Heading 4', 'content-views-query-and-display-post-page' ),
					'heading5'	 => __( 'Heading 5', 'content-views-query-and-display-post-page' ),
					'heading6'	 => __( 'Heading 6', 'content-views-query-and-display-post-page' ),
				),
				'thumb_effects'				 => self::thumbnail_effects(),
				'overlaytypes'				 => self::ovl_types(),
				'ovlposi'					 => self::ovl_positions(),
				'onewidth'					 => ContentViews_Block_OneBig2::one_width(),
				'top_meta'					 => self::topmeta_options(),
				'taxo_positions'			 => self::topmeta_positions(),
				'meta_fields'				 => self::meta_list(),
				'meta_separator'			 => array(
					''			 => __( 'None', 'content-views-query-and-display-post-page' ),
					'&#183;'	 => '·',
					'/'			 => '/',
					'//'		 => '//',
					'-'			 => '-',
					'&#8210;'	 => '—',
					'|'			 => '|',
				),
				'date_format'				 => [
					''				 => __( 'Default' ),
					'g:i a'			 => __( '12:50 am' ),
					'Y/m/d'			 => __( '2010/11/06' ),
					'Y-m-d'			 => __( '2010-11-06' ),
					'F j, Y'		 => __( 'November 6, 2010' ),
					'F j, Y g:i a'	 => __( 'November 6, 2010 12:50 am' ),
					'l, F jS, Y'	 => __( 'Saturday, November 6th, 2010' ),
					'custom'	 => __( 'Custom Format', 'content-views-query-and-display-post-page' )
				],
				// prevent error in JS, override in Pro
				'lf_settings' => [],
			),
		);

		// for preview iframe
		$files = [ 'cv-css' => plugins_url( 'public/assets/css/' . (!cv_is_damaged_style() ? 'cv.css' : 'cv.im.css'), PT_CV_FILE ), ];
		if ( defined( 'PT_CV_FILE_PRO' ) ) {
			$files[ 'cvp-css' ] = plugins_url( 'public/assets/css/' . ( function_exists( 'cv_is_damaged_style' ) && cv_is_damaged_style() ? 'cvpro.im.min.css' : 'cvpro.min.css'), PT_CV_FILE_PRO );
		}
		$js_data[ 'data' ][ 'iframe_assets' ] = $files;

		$js_data = apply_filters( PT_CV_PREFIX_ . 'block_localize_data', $js_data );

		wp_localize_script(
		'contentviews-block-script', 'ContentViews', $js_data
		);

		PT_CV_Html::frontend_styles();
	}

	function enqueue_for_blocklib_page() {
		$page = isset( $_GET[ 'page' ] ) ? sanitize_text_field( $_GET[ 'page' ] ) : null;
		if ( $page === 'content-views-blocklibrary' || $page === 'content-views-add' ) {
			$GLOBALS[ 'cv_outside_gutenberg' ] = true;
			$this->block_enqueue_assets();
		}
	}

	static function load_googlefont( $atts ) {
		if ( !isset( $GLOBALS[ 'cv_embedded_fonts' ] ) ) {
			$GLOBALS[ 'cv_embedded_fonts' ] = [];
		}

		$embeded_font = [];
		foreach ( ContentViews_Block::get_fields() as $field ) {
			if ( isset( $atts[ "{$field}Family" ], $atts[ "{$field}Family" ][ 'label' ] ) ) {
				$font = $atts[ "{$field}Family" ][ 'label' ];
				if ( !empty( $font ) && !in_array( $font, $GLOBALS[ 'cv_embedded_fonts' ] ) ) {
					$embeded_font[]						 = "<link href='//fonts.googleapis.com/css?family=" . urlencode( $font ) . "' rel='stylesheet' type='text/css'>";
					$GLOBALS[ 'cv_embedded_fonts' ][]	 = $font;
				}
			}
		}

		return implode( '', $embeded_font );
	}

	static function upgrade_link( $message, $utm_tag = '' ) {
		$url	 = 'https://www.contentviewspro.com/' . $utm_tag;
		$text	 = __( "Upgrade Now", "content-views-query-and-display-post-page" );
		$link	 = "<a href='$url' target='_blank'>$text</a>";
		return '<p class="cvb-upgrade-link">' . sprintf( $message, $link ) . '</p>';
	}

	static function upgrade_for_block( $block, $block_demo = '' ) {
		$utm      = '?utm_source=block-editor&utm_medium=problock&utm_campaign=' . $block;
		$demo_url = 'https://contentviewspro.com/demo/blocks/' . $block_demo . '/' . $utm;
		$img	 = plugins_url( "block/assets/layouts/demo/$block.svg", PT_CV_FILE );
		$text	 = ContentViews_Block_Common::upgrade_link( __( 'To use this Pro block, please %s', 'content-views-query-and-display-post-page' ), $utm );
		$output	 = "<div style='text-align: center; background: #f9f9f9; padding: 10px;'>$text <img src='$img' style='max-width: 450px; max-height: 400px; margin-bottom: 20px;' /> <p><a href='$demo_url' target='_blank'>See Demo</a></p></div>";
		return $output;
	}

	// Use short operator without long label
	static function taxo_operators() {
		$opt = array_keys( PT_CV_Values::taxonomy_operators() );
		return array_combine( $opt, $opt );
	}

	// Get terms list of taxonomies
	static function terms_list() {
		$taxos	 = PT_CV_Values::taxonomy_list();
		$terms	 = array();
		foreach ( (array) array_keys( $taxos ) as $taxonomy ) {
			PT_CV_Values::term_of_taxonomy( $taxonomy, $terms );
		}
		return $terms;
	}

	static function get_require_version() {
		$require_pro = '6.1';
		$pro_version = get_option( 'pt_cv_version_pro' );
		return $pro_version && version_compare( $pro_version, $require_pro, '<' ) ? $require_pro : '';
	}

	static function set_request_method() {
		$method = 'GET';
		if ( get_option( 'pt_cv_version_pro' ) && version_compare( $GLOBALS[ 'wp_version' ], '5.5' ) > 0 ) {
			$method = 'POST';
		}
		return $method;
	}

	static function layout_variants() {
		return array(
			'grid1'		 => [ 'layout1' => '', 'layout2' => '', 'layout3' => 1 ],
			'list1'		 => [ 'layout1' => '', 'layout2' => 1, 'layout3' => 1 ],
			'onebig1'	 => [ 'layout1' => '', 'layout2' => 1, 'layout3' => 1 ],
			'onebig2'	 => [ 'layout1' => '', 'layout2' => 1, 'layout3' => 1, 'layout4' => 1 ],
			'overlay2'	 => [ 'layout1' => '', 'layout2' => 1, 'layout3' => 1 ],
			'overlay3'	 => [ 'layout1' => '', 'layout2' => 1, 'layout3' => 1 ],
			'overlay4'	 => [ 'layout1' => '', 'layout2' => 1, 'layout3' => 1, 'layout4' => 1, 'layout5' => 1 ],
			'overlay5'	 => [ 'layout1' => '', 'layout2' => 1, 'layout3' => 1, 'layout4' => 1 ],
			'overlay6'	 => [ 'layout1' => '', 'layout2' => '', 'layout3' => '', ],
			'overlay7'	 => [ 'layout1' => '', 'layout2' => 1, 'layout3' => 1, 'layout4' => 1, 'layout5' => 1 ],
			'overlay8'	 => [ 'layout1' => 1, 'layout2' => 1, 'layout3' => 1, 'layout4' => 1, ],
		);
	}

	static function thumbnail_effects() {
		return array(
			''					 => __( 'None', 'content-views-query-and-display-post-page' ),
			'cveffect-darken'	 => __( 'Darken', 'content-views-query-and-display-post-page' ),
			'cveffect-zoomin'	 => __( 'Zoom in', 'content-views-query-and-display-post-page' ),
			'cveffect-zoomout'	 => __( 'Zoom out', 'content-views-query-and-display-post-page' ),
			'cveffect-moveup'	 => __( 'Move up', 'content-views-query-and-display-post-page' ),
			'cveffect-movedown'	 => __( 'Move down', 'content-views-query-and-display-post-page' ),
			'cveffect-moveleft'	 => __( 'Move left', 'content-views-query-and-display-post-page' ),
			'cveffect-moveright' => __( 'Move right', 'content-views-query-and-display-post-page' ),
		);
	}

	static function ovl_types() {
		return array(
			''			 => __( 'None', 'content-views-query-and-display-post-page' ),
			'simple'	 => __( 'Color', 'content-views-query-and-display-post-page' ),
			'gradient'	 => __( 'Gradient', 'content-views-query-and-display-post-page' ),
		);
	}

	static function ovl_positions() {
		return array(
			'top'	 => __( 'Top', 'content-views-query-and-display-post-page' ),
			'middle' => __( 'Middle', 'content-views-query-and-display-post-page' ),
			'bottom' => __( 'Bottom', 'content-views-query-and-display-post-page' ),
		);
	}

	static function topmeta_options() {
		return array(
			'mtt_taxonomy'	 => __( 'Taxonomy', 'content-views-query-and-display-post-page' ),
			'mtt_author'	 => __( 'Author', 'content-views-query-and-display-post-page' ),
			'mtt_date'		 => __( 'Date', 'content-views-query-and-display-post-page' ),
		);
	}

	static function topmeta_positions() {
		return array(
			'above_title'		 => __( 'Above Title', 'content-views-query-and-display-post-page' ),
			'below_title'		 => __( 'Below Title', 'content-views-query-and-display-post-page' ),
			'over_top_left'		 => __( 'On Image (Top Left)', 'content-views-query-and-display-post-page' ),
			'over_bottom_left'	 => __( 'On Image (Bottom Left)', 'content-views-query-and-display-post-page' ),
			'over_top_right'	 => __( 'On Image (Top Right)', 'content-views-query-and-display-post-page' ),
			'over_bottom_right'	 => __( 'On Image (Bottom Right)', 'content-views-query-and-display-post-page' ),
			'over_center'		 => __( 'On Image (Center)', 'content-views-query-and-display-post-page' )
		);
	}

	static function meta_list() {
		return array(
			[ 'value' => 'date', 'label' => __( 'Date', 'content-views-query-and-display-post-page' ) ],
			[ 'value' => 'author', 'label' => __( 'Author', 'content-views-query-and-display-post-page' ) ],
			[ 'value' => 'taxonomy', 'label' => __( 'Taxonomies', 'content-views-query-and-display-post-page' ) ],
			[ 'value' => 'comment', 'label' => __( 'Comment', 'content-views-query-and-display-post-page' ) ],
		);
	}

	static function pro_sortby() {
		return array(
			'title_human'		 => __( 'Title (sort alphanumeric strings as human)', 'content-views-query-and-display-post-page' ),
			'post_type'			 => __( 'Post type', 'content-views-query-and-display-post-page' ),
			'rand'				 => __( 'Random', 'content-views-query-and-display-post-page' ),
			'comment_count'		 => __( 'Comment count', 'content-views-query-and-display-post-page' ),
			'post_author'		 => __( 'Author', 'content-views-query-and-display-post-page' ),
			'relevance'			 => __( 'Relevance to the searched keyword', 'content-views-query-and-display-post-page' ),
		);
	}

	static function get_orderby_options() {
		$arr = PT_CV_Values::post_regular_orderby();
		unset( $arr[ 'dragdrop' ] );
		return $arr;
	}

	static function fields_sortable() {
		return [
			'showThumbnail'	 => __( 'Featured Image', 'content-views-query-and-display-post-page' ),
			'showTitle'		 => __( 'Title', 'content-views-query-and-display-post-page' ),
			'showContent'	 => __( 'Content', 'content-views-query-and-display-post-page' ),
			'showReadmore'	 => __( 'Read More', 'content-views-query-and-display-post-page' ),
			] + self::pro_fields_sortable()
			+ [ 'showMeta'		 => __( 'Bottom Meta', 'content-views-query-and-display-post-page' ) ];
	}

	static function pro_fields_sortable() {
		$fields						 = [];
		$fields[ 'showCustomField' ] = __( 'Custom Field', 'content-views-query-and-display-post-page' );
		if ( cv_is_active_plugin( 'woocommerce' ) ) {
			$fields[ 'showWooPrice' ]	 = __( 'Woo - Price', 'content-views-query-and-display-post-page' );
			$fields[ 'showWooATC' ]		 = __( 'Woo - Add To Cart', 'content-views-query-and-display-post-page' );
			$fields[ 'showWooRating' ]	 = __( 'Woo - Star Ratings', 'content-views-query-and-display-post-page' );
			$fields[ 'showWooHook' ]	 = __( 'Woo - Extra Hooks', 'content-views-query-and-display-post-page' );
		}
		return $fields;
	}

	static function get_date_options() {
		$result = array(
			''				 => sprintf( '- %s -', __( 'Select' ) ),
			'today'				 => __( 'Today' ),
			'from_today'		 => __( 'Today and future', 'content-views-pro' ),
			'today_in_history'	 => __( 'Today in history', 'content-views-pro' ),
			'in_the_past'		 => __( 'In the past', 'content-views-pro' ),
			'yesterday'			 => __( 'Yesterday', 'content-views-pro' ),
			'week_ago'			 => __( '1 week ago (to today)', 'content-views-pro' ),
			'month_ago'			 => __( '1 month ago (to today)', 'content-views-pro' ),
			'year_ago'			 => __( '1 year ago (to today)', 'content-views-pro' ),
			'this_week'			 => __( 'This week', 'content-views-pro' ),
			'this_month'		 => __( 'This month', 'content-views-pro' ),
			'this_year'			 => __( 'This year', 'content-views-pro' ),
			'custom_date'		 => __( 'Custom date', 'content-views-pro' ),
			'custom_time'		 => __( 'Custom time (from - to)', 'content-views-pro' ),
			'custom_month'		 => __( 'Custom month', 'content-views-pro' ),
			'custom_year'		 => __( 'Custom year', 'content-views-pro' ),
		);

		return $result;
	}

	static function img_sub_options() {
		return array(
			'none'			 => '(' . __( 'None' ) . ')',
			'image'			 => __( 'Image (in post content)', 'content-views-pro' ),
			'video-audio'	 => __( 'Video / Audio (in post content)', 'content-views-pro' ),
			'image-ctf'		 => __( 'Image (in custom field)', 'content-views-pro' ),
		);
	}

	static function woo_pick_options() {
		return array(
			''						 => sprintf( '- %s -', __( 'Select' ) ),
			'recent_products'		 => __( 'Recent products', 'content-views-pro' ),
			'sale_products'			 => __( 'Sale products', 'content-views-pro' ),			
			'best_selling_products'	 => __( 'Best selling products', 'content-views-pro' ),
			'featured_products'		 => __( 'Featured products', 'content-views-pro' ),
			'top_rated_products'	 => __( 'Top rated products', 'content-views-pro' ),
			'out_of_stock'			 => __( 'Out of stock products', 'content-views-pro' ),			
		);
	}

	// Server-side CSS
	static function view_styles( $atts ) {
		$css = '';
		if ( $atts[ 'blockName' ] === 'onebig2' ) {
			$view_selector	 = "#" . PT_CV_PREFIX . 'view-' . $atts[ 'blockId' ];
			$width			 = $atts[ 'oneWidth' ];
			$value			 = $atts[ 'swapPosition' ] ? "auto $width" : "$width auto";
			$css			 = "$view_selector > .pt-cv-page {grid-template-columns: $value;}";
		}
		if ( $atts[ 'blockName' ] === 'overlay2' ) {
			$view_selector	 = "#" . PT_CV_PREFIX . 'view-' . $atts[ 'blockId' ];
			$ppp			 = $atts[ 'postsPerPage' ];
			$value			 = "1 / 1 / $ppp";
			$css			 = "$view_selector ." . PT_CV_PREFIX . "content-item:first-child {grid-area: $value;}";
		}

		return $css;
	}

	// Generate CSS from attribute values
	static function generate_styles( $atts ) {
		$all_css		 = array();
		$view_selector	 = "#" . PT_CV_PREFIX . 'view-' . $atts[ 'blockId' ];
		$live_filter_selector	 = ".cvp-live-filter[data-sid='{$atts[ 'blockId' ]}']";

		// view_selector element_selector { color: 1; font-family: 1 }
		$fields = ContentViews_Block::get_fields();
		foreach ( $fields as $field ) {
			$field_selector = $view_selector . ' .' . PT_CV_PREFIX . $field;
			if ( $field === 'view' ) {
				$field_selector = $view_selector;
			}
			if ( $field === 'title' ) {
				$field_selector	 = array( $field_selector . ' a' => array( 'color', 'background-color', 'text-align', 'display', 'margin', 'padding', 'border-style', 'border-color', 'border-width', 'border-radius', 'box-shadow' ), $field_selector . ':not(.' . PT_CV_PREFIX . 'titlesm' . ')' . ' a' => '' );
			}
			if ( $field === 'titlesm' ) {
				$field_selector .= ' a';
			}
			if ( $field === 'content-item' ) {
				if ( $atts[ 'blockName' ] === 'list1' && $atts[ 'whichLayout' ] === 'layout3' ) {
					$field_selector = $view_selector . ' .' . PT_CV_PREFIX . 'remain-wrapper';
				}
				if ( $atts[ 'blockName' ] === 'scrollable' ) {
					$field_selector = $view_selector . ' .' . PT_CV_PREFIX . 'carousel-caption';
				}
				if ( $atts[ 'blockName' ] === 'collapsible' ) {
					$field_selector = array( $view_selector . ' .panel-body' => array( 'padding' ), $field_selector => '' );
				}
				if ( $atts[ 'blockName' ] === 'pinterest' ) {
					$field_selector = array( $field_selector => array( 'padding' ), $view_selector . ' .' . PT_CV_PREFIX . 'pinmas' => '' );
				}
			}
			if ( $field === 'thumbnail' ) {
				$field_selector = array( $view_selector . ' .' . PT_CV_PREFIX . 'thumb-wrapper:not(.miniwrap)' => array( 'width', 'min-width' ), $field_selector . ':not(.' . PT_CV_PREFIX . 'thumbnailsm' . ')' => '' );
			}
			if ( $field === 'thumbnailsm' ) {
				$field_selector = array( $view_selector . ' .' . PT_CV_PREFIX . 'thumb-wrapper.miniwrap' => array( 'width' ), $field_selector => '' );
			}
			if ( $field === 'thumbnailAll' ) {
				$field_selector = array( $view_selector . ' .' . PT_CV_PREFIX . 'thumb-wrapper' => array( 'margin' ), $view_selector . ' .' . PT_CV_PREFIX . 'thumbnail' => '' );
			}
			if ( $field === 'taxoterm' ) {
				$field_selector = array( $field_selector => array( 'margin' ), $field_selector . ' *' => '' );
			}
			if ( $field === 'meta-fields' ) {
				$field_selector = array( $field_selector => array( 'text-align', 'background-color', 'margin', 'padding' ), $field_selector . ' *' => '' );
			}
			if ( $field === 'wooatc' ) {
				$field_selector = array( $field_selector => array( 'text-align', ), $field_selector . ' a' => '' );
			}
			if ( $field === 'pagination' ) {
				$pagi_selector	 = $view_selector . ' + .' . PT_CV_PREFIX . 'pagination-wrapper';
				$field_selector	 = array( $pagi_selector => array( 'text-align', 'margin' ), $pagi_selector . ' a' => '' );
			}
			if ( $field === 'paginationActive' ) {
				$field_selector = $view_selector . ' + .' . PT_CV_PREFIX . 'pagination-wrapper .active a';
			}
			if ( $field === 'heading' ) {
				$field_selector	 = '.' . PT_CV_PREFIX . 'heading-container[data-blockid="' . $atts[ 'blockId' ] . '"]';
				$field_selector	 = array( $field_selector => array( 'border-color', 'border-width', 'text-align' ), $field_selector . ' *' => '' );
			}
			/* Live filter style */
			if ( $field === 'LFSlabel' ) {
				$field_selector = $live_filter_selector . ' .cvp-label';
			}
			if ( $field === 'LFSoption' ) {
				$field_selector = $live_filter_selector . ' input[type="text"], ' . $live_filter_selector . ' div > label, ' . $live_filter_selector . ' select, ' . $live_filter_selector . ' .irs-from, ' . $live_filter_selector . ' .irs-to';
			}
			if ( $field === 'LFSrange' ) {
				$field_selector = $live_filter_selector . ' .irs-from, ' . $live_filter_selector . ' .irs-to, ' . $live_filter_selector . ' .irs-bar';
			}
			if ( $field === 'LFSbutton' ) {
				$field_selector = $live_filter_selector . ' input[type=radio]:checked~div';
			}
			if ( $field === 'LFSsubmit' ) {
				$field_selector = $live_filter_selector . '~ .cvp-live-button .cvp-live-submit';
			}
			if ( $field === 'LFSreset' ) {
				$field_selector = $live_filter_selector . '~ .cvp-live-button .cvp-live-reset';
			}
			self::get_field_css( $atts, $field, $field_selector, $all_css );
		}

		$view_css = '';

		if ( !empty($atts[ 'noLFSub' ]) ) {
			$view_css .= $live_filter_selector . ' ~ .cvp-live-button .cvp-live-submit {display: none !important}';
		}
		if ( !empty($atts[ 'noLFRes' ]) ) {
			$view_css .= $live_filter_selector . ' ~ .cvp-live-button .cvp-live-reset {display: none !important}';
		}
		if ( !empty( $atts[ 'lfCustomize' ] ) && !empty( $atts[ 'lfEleColor' ] ) ) {
			$property_val	 = $atts[ 'lfEleColor' ];
			$view_css		 .= "
				{$live_filter_selector}.cvp-customized input:hover,
				{$live_filter_selector}.cvp-customized input:focus,
				{$live_filter_selector}.cvp-customized select:hover,
				{$live_filter_selector}.cvp-customized select:focus,
				{$live_filter_selector}.cvp-customized input~div:hover,
				{$live_filter_selector}.cvp-customized input~div:focus {border-color: $property_val; box-shadow: 0 0 0 1px $property_val !important;}
				{$live_filter_selector}.cvp-customized input[type='checkbox']:checked {background-color: $property_val;}
				{$live_filter_selector}.cvp-customized input[type='radio']:checked {border-color: $property_val;}
				{$live_filter_selector}.cvp-customized input[type='radio']:checked::before {border-color: $property_val; background: $property_val;}
				";
		}

		if ( $atts[ 'viewType' ] === 'overlaygrid' || $atts[ 'viewType' ] === 'blockgrid' || $atts[ 'viewType' ] === 'onebig' ) {
			if ( !empty( $atts[ 'overlayType' ] ) ) {
				$value		 = ($atts[ 'overlayType' ] === 'simple') ? $atts[ 'overlayColor' ] : $atts[ 'overlayGradient' ];
				$opa		 = $atts[ 'overlayOpacity' ];
				$view_css	 .= "$view_selector ." . PT_CV_PREFIX . "thumb-wrapper::before {background: $value; opacity: $opa}";
			}
			if ( !empty( $atts[ 'overlayPosition' ] ) ) {
				$posi		 = [ 'top' => 'flex-start', 'middle' => 'center', 'bottom' => 'flex-end' ];
				$view_css	 .= "$view_selector ." . PT_CV_PREFIX . "overlay-wrapper {justify-content: {$posi[ $atts[ 'overlayPosition' ] ]};}";
			}

			$all_css[ 'grid_template' ]	 = [];
			$columns  = (array) $atts['columns'];
			$grid_col = ( $atts[ 'sameAs' ] === 'overlay6' ) ? '' : 'grid-template-columns: repeat(__VALUE__, 1fr);';
			$grid_row = 'grid-auto-rows: __ROW__;'; $grid_gap = ''; $grid_include = ''; $grid_extend = '';

			if ( $atts[ 'blockName' ] === 'overlay6' ) {
				$grid_row .= 'grid-template-rows: calc(__ROW__*1.5);';
			}

			if ( $atts[ 'blockName' ] === 'onebig2' ) {
				$grid_include = ' .small-items';
			}

			if ( $atts[ 'blockName' ] === 'overlay5' ) {
				if ( $atts[ 'whichLayout' ] === 'layout2' ) {
					$grid_row = 'grid-auto-rows: calc(__ROW__/var(--rowspan,2));';
				}
				// on block editor only: hide height jump when changing layout
				$grid_extend = $view_selector . ":not(." . $atts[ 'whichLayout' ] . ")" . " { opacity: 0; height: calc(__ROW__ * 2); }";
			}

			$gaps = (array) $atts['gridGap'];
			if ( $gaps ) {
				$grid_gap = 'grid-gap: __GAP__px;';
				if ( $atts[ 'blockName' ] === 'onebig2' ) {
					$grid_extend = $view_selector . " > .pt-cv-page" . " {" . $grid_gap . "}";
				}
			}
			
			$arr		 = [ 'md' => 'desktop', 'sm' => 'tablet', 'xs' => 'mobile' ];
			foreach ( $arr as $value => $media ) {
				$grid_css	 = $view_selector . " > .pt-cv-page" . $grid_include . " {" . $grid_col . $grid_row . $grid_gap . "}" . $grid_extend;
				$check_val = isset( $columns[ $value ] ) ? $columns[ $value ] : null;
				if ( !$check_val ) { $grid_css = str_replace( $grid_col, '', $grid_css ); }
				$rowHeight = self::get_attr_value( $atts, 'hetargetHeight', $value, 'Units' );
				if ( !$rowHeight ) { $grid_css = str_replace( $grid_row, '', $grid_css ); }
				$gapVal = isset( $gaps[ $value ] ) ? $gaps[ $value ] : null;
				if ( $gapVal === null ) { $grid_css = str_replace( $grid_gap, '', $grid_css ); }

				$all_css[ 'grid_template' ][ $media ] = str_replace( [ '__VALUE__', '__ROW__', '__GAP__' ], [ $check_val, $rowHeight, $gapVal ], $grid_css );
			}
		}

		$all_css = apply_filters( PT_CV_PREFIX_ . 'block_generated_css', $all_css );

		return
		$view_css . "\n" .
		implode( '', array_filter( array_column( $all_css, 'desktop' ) ) ) .
		implode( '', array_filter( array_column( $all_css, 'hover' ) ) ) .
		"\n@media all and (max-width: 1024px) { \n" .
		implode( '', array_filter( array_column( $all_css, 'tablet' ) ) ) .
		"\n} " .
		"\n@media all and (max-width: 767px) { \n" .
		implode( '', array_filter( array_column( $all_css, 'mobile' ) ) ) .
		"\n} ";
	}

	static function get_field_css( $atts, $field, $field_selector, &$all_css ) {
		$css_desktop = array();
		$css_tablet	 = array();
		$css_mobile	 = array();
		$css_hover	 = array();

		$simple = [
			'text-align'		 => 'Align',
			'color'				 => 'Color',
			'background-color'	 => 'BgColor',
			'font-weight'		 => 'Weight',
			'font-style'		 => 'fStyle',
			'text-transform'	 => 'Tran',
			'text-decoration'	 => 'Deco',
			'border-color'		 => 'BorderColor',
			'border-style'		 => 'BorderStyle',
		];

		foreach ( $simple as $css_key => $attr_key ) {
			$value = self::get_attr_value( $atts, $field . $attr_key );
			self::get_css( $css_desktop, $css_key, $value );

			if ( $field === 'title' && $attr_key === 'Align' ) {
				self::get_css( $css_desktop, 'display', 'block' );
			}
		}

		self::get_css( $css_desktop, 'font-family', self::get_attr_value( $atts, $field . 'Family', 'value' ) );

		$obj_types = [
			'font-size'		 => 'fSize',
			'line-height'	 => 'Line',
			'margin'		 => 'Margin',
			'padding'		 => 'Padding',
			'width'			 => 'MaxWidth',
			'height'		 => 'Height',
			'border-width'	 => 'BorderWidth',
			'border-radius'	 => 'BorderRadius',
			'box-shadow'	 => 'BoxShadow',
		];
		foreach ( $obj_types as $css_key => $attr_key ) {
			self::get_css( $css_desktop, $css_key, self::get_attr_value( $atts, $field . $attr_key, 'md', 'Units' ) );
			self::get_css( $css_tablet, $css_key, self::get_attr_value( $atts, $field . $attr_key, 'sm', 'Units' ) );
			self::get_css( $css_mobile, $css_key, self::get_attr_value( $atts, $field . $attr_key, 'xs', 'Units' ) );
		}

		$hover = [
			'color'				 => 'HoverColor',
			'background-color'	 => 'HoverBgColor',
		];
		foreach ( $hover as $css_key => $attr_key ) {
			$value = self::get_attr_value( $atts, $field . $attr_key );
			self::get_css( $css_hover, $css_key, $value );
		}

		$genCSS	 = [];
		$pairs = [
			'desktop'	 => $css_desktop,
			'tablet'	 => $css_tablet,
			'mobile'	 => $css_mobile,
			'hover'		 => $css_hover,
		];
		foreach ( $pairs as $media => $css ) {
			$suffix = ($media === 'hover') ? ':hover' : '';
			if ( $css ) {
				if ( !is_array( $field_selector ) ) {
					if ( $suffix && strpos( $field, 'LFS' ) !== false ) {
						$field_selector = str_replace( ',', $suffix . ',', $field_selector );
					}
					$genCSS[ $media ] = "$field_selector{$suffix} { " . implode( '', $css ) . " }";
				} else {
					$genCSS[ $media ]	 = '';
					$used_properties	 = [];
					foreach ( $field_selector as $selector => $css_properties ) {
						if ( is_array( $css_properties ) ) {
							$css_this			 = array_intersect_key( $css, array_flip( $css_properties ) );
							$used_properties = array_merge( $used_properties, $css_properties );
						} else {
							// get CSS of remained properties that differ from $used_properties
							$css_this = array_diff_key( $css, array_flip( $used_properties ) );
						}
						$genCSS[ $media ] .= "$selector{$suffix} { " . implode( '', $css_this ) . " }";
					}
				}
			}
		}

		$all_css [ $field ] = $genCSS;
	}

	static function get_attr_value( $atts, $key, $extra = null, $append = null ) {
		$value = null;

		// ignore some cases
		$ignore = false;
		if ( $atts[ 'viewType' ] === 'overlaygrid' ) {
			if ( $atts[ 'overlaid' ] ) {
				if ( $key === 'thumbnailHeight' || $key === 'thumbnailsmHeight' ) {
					$ignore = true;
				}
			} else {
				if ( $key === 'hetargetHeight' ) {
					$ignore = true;
				}
			}
		}
		if ( isset( $atts[ $key ] ) && !$ignore ) {
			if ( is_object( $atts[ $key ] ) ) {
				$atts[ $key ] = (array) $atts[ $key ];
			}
			$value = $extra ? ( isset( $atts[ $key ][ $extra ] ) ? $atts[ $key ][ $extra ] : '' ) : $atts[ $key ];

			// margin, line height, etc.
			if ( $value && $append ) {
				if ( strpos( $key, 'Margin' ) !== false || strpos( $key, 'Padding' ) !== false || strpos( $key, 'BoxShadow' ) !== false || strpos( $key, 'BorderWidth' ) !== false || strpos( $key, 'BorderRadius' ) !== false ) {
					$default_val	 = [ 'top' => 0, 'right' => 0, 'bottom' => 0, 'left' => 0, ];
					$remove_blank	 = array_filter( $value );
					$value			 = array_merge( $default_val, $remove_blank );
				}

				$unit = self::get_attr_value( $atts, $key . $append, $extra );
				if ( empty( $unit ) ) {
					$unit = 'px';
				}
				$value = implode( "$unit ", (array) $value ) . $unit;

				if ( strpos( $key, 'BoxShadow' ) !== false ) {
					$color	 = self::get_attr_value( $atts, $key . 'Color' );
					$value	 .= ' ' . ($color ? $color : '');
				}
			}
		}

		if ( $value && strpos( $key, 'Family' ) !== false ) {
			$value = '"' . $value . '"';
		}

		return $value;
	}

	static function get_css( &$css, $property, $value ) {
		if ( $value ) {
			$css[ $property ] = "$property: $value;";
		}
	}

	// copy from Pro
	public static function get_site_license() {
		$license = null;
		if ( is_multisite() ) {
			$blog_ids = PT_Content_Views::get_blog_ids();
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$b_license = PT_CV_Functions::get_option_value( 'license_key' );
				restore_current_blog();

				if ( !empty( $b_license ) ) {
					$license = $b_license;
					break;
				}
			}
		} else {
			$license = PT_CV_Functions::get_option_value( 'license_key' );
		}

		return $license;
	}

}
new ContentViews_Block_Common();
