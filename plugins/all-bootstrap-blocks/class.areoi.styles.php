<?php

class AREOI_Styles
{
	private static $initiated = false;

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	private static function init_hooks() 
	{
		self::$initiated = true;

		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( 'AREOI_Styles', 'enqueue_custom_admin_style' ) );
		} else {
			
			$priority = areoi2_get_option( 'areoi-dashboard-global-bootstrap-css-priority' );
			$priority = !empty( $priority ) && is_numeric( $priority ) ? $priority : false;
			if ( $priority ) {
				add_action( 'wp_enqueue_scripts', array( 'AREOI_Styles', 'enqueue_custom_style' ), $priority );
			} else {
				add_action( 'wp_enqueue_scripts', array( 'AREOI_Styles', 'enqueue_custom_style' ) );
			}
			
			add_action( 'wp_enqueue_scripts', array( 'AREOI_Styles', 'add_block_styles' ) );
		}
	}

	public static function enqueue_custom_admin_style()
	{
		if ( !did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}

		wp_enqueue_script('wp-color-picker');
    	wp_enqueue_style( 'wp-color-picker' );
    	wp_enqueue_style( 'dashicons' );

    	$css_enqueues = array(
    		'areoi-bootstrap' 	=> 'assets/css/editor-bootstrap.min.css',
    		'areoi-index' 		=> 'build/index.css',
    		'areoi-select2' 	=> 'assets/css/select2.min.css'
    	);
    	areoi_enqueue_css( $css_enqueues );

    	$js_enqueues = array(
    		'areoi-js' 			=> array(
    			'path' 			=> 'assets/js/areoi.js',
    			'includes' 		=> array()
    		),
    		'areoi-color-picker' => array(
    			'path' 			=> 'assets/js/wp-color-picker-alpha.min.js',
    			'includes' 		=> array( 'wp-color-picker' )
    		),
    		'areoi-select2' 	=> array(
    			'path' 			=> 'assets/js/select2.min.js',
    			'includes' 		=> array()
    		),
    	);

    	areoi_enqueue_js( $js_enqueues );

		wp_localize_script( 'areoi-blocks', 'areoi_vars', array( 
			'plugin_url' 	=> AREOI__PLUGIN_URI, 
			'hide_buttons' 	=> areoi2_get_option( 'areoi-dashboard-global-hide-buttons-block', 0 ),
			'hide_columns' 	=> areoi2_get_option( 'areoi-dashboard-global-hide-columns-block', 0 ),
			'text_domain' 	=> AREOI__TEXT_DOMAIN,
			'colors' 		=> areoi_get_option_colors(),
			'menus' 		=> self::get_formatted_menus(),
			'grid_columns'	=> areoi2_get_option( 'areoi-layout-grid-grid-columns', 12 ),
			'grid_rows'		=> areoi2_get_option( 'areoi-layout-grid-grid-row-columns', 6 ),
			'utility_bg'	=> areoi_get_utilities_bg(),
			'utility_text'	=> areoi_get_utilities_text(),
			'utility_border'=> areoi_get_utilities_border(),
			'display_units' => areoi2_get_option( 'areoi-dashboard-global-display-units', 'px' ),
			'btn_styles'	=> areoi2_get_btn_styles(),
			'is_grid'		=> areoi2_get_option( 'areoi-customize-options-enable-cssgrid', false ),
		) );

		wp_set_script_translations( 'areoi-blocks', AREOI__TEXT_DOMAIN );
	}

	public static function enqueue_custom_style()
	{
		if ( areoi2_get_option( 'areoi-dashboard-global-bootstrap-css', 1 ) ) {
			$css_enqueues = array(
	    		'areoi-bootstrap' 	=> 'assets/css/bootstrap.min.css',
	    	);
	    	areoi_enqueue_css( $css_enqueues );
		}
		if ( areoi2_get_option( 'areoi-dashboard-global-bootstrap-icon-css', 1 ) ) {
			$css_enqueues = array(
	    		'areoi-bootstrap-icons' 	=> 'src/bootstrap-icons-1.10.2/bootstrap-icons.min.css',
	    	);
	    	areoi_enqueue_css( $css_enqueues );
		}
		$css_enqueues = array(
    		'areoi-style-index' => 'build/style-index.css',
    	);
    	areoi_enqueue_css( $css_enqueues );
		
		if ( areoi2_get_option( 'areoi-dashboard-global-bootstrap-js', 1 ) ) {

			$js_enqueues = array(
	    		'areoi-bootstrap' 	=> array(
	    			'path' 			=> 'assets/js/bootstrap.min.js',
	    			'includes' 		=> array('jquery')
	    		),
	    	);
	    	areoi_enqueue_js( $js_enqueues );

	    	$scripts = '';
	    	ob_start(); include( AREOI__PLUGIN_DIR . 'assets/js/bootstrap-extra.js' ); $scripts .= ob_get_clean();

	    	wp_add_inline_script( 'areoi-bootstrap', areoi_minify_js( $scripts ) );
		}

		$post = get_post(); 
    	$added_styles = array();
    	$added_scripts = array();
		if ( isset( $post->post_content ) && has_blocks( $post->post_content ) ) {
		    $blocks = parse_blocks( $post->post_content );

		    self::traverse_block_styles( $blocks, $added_styles, $added_scripts );
		}
	}

	public static function traverse_block_styles( $blocks, $added_styles, $added_scripts )
	{
		foreach ( $blocks as $block_key => $block ) {
	    	if ( empty( $block['blockName'] ) ) continue;

	    	if ( in_array( $block['blockName'], array( 'areoi/button', 'areoi/icon' ) ) ) {
	    		$css_enqueues = array(
		    		'areoi-bootstrap-icons' 	=> 'src/bootstrap-icons-1.10.2/bootstrap-icons.min.css',
		    	);
		    	areoi_enqueue_css( $css_enqueues );
	    	}
	    	
	    	$block_name = str_replace( 'areoi/', '', $block['blockName'] );
	    	if ( $block['blockName'] == 'core/gallery' ) {
	    		$block_name = str_replace( 'core/', '', $block['blockName'] );
	    	}
	    	
	    	if ( file_exists( AREOI__PLUGIN_DIR . 'blocks/' . $block_name . '/style.css' ) && empty( $added_styles[$block_name] ) ) {
	    		areoi_enqueue_css( array( 'areoi-block-' . $block_name => 'blocks/' . $block_name . '/style.css' ) );
	    		$added_styles[$block_name] = true;
	    	}
	    	if ( file_exists( AREOI__PLUGIN_DIR . 'blocks/' . $block_name . '/script.js' ) && empty( $added_scripts[$block_name] ) ) {
	    		$scripts = array( 
	    			'areoi-block-' . $block_name => array(
		    			'path' => 'blocks/' . $block_name . '/script.js',
		    			'includes' => array()
	    			)
	    		);
	    		areoi_enqueue_js( $scripts );
	    		$added_scripts[$block_name] = true;
	    	}
	    	if ( in_array( $block_name, array( 'media-grid', 'post-grid', 'content-grid' ) ) ) {
	    		$added_styles['gallery'] = true;
	    		$added_styles['post-grid'] = true;
	    		$added_styles['media-grid'] = true;
	    	}

	    	if ( !empty( $block['innerBlocks'] ) ) {
	    		self::traverse_block_styles( $block['innerBlocks'], $added_styles, $added_scripts );
	    	}
	    }
	}

	public static function add_block_styles()
	{
		$xs = str_replace( 'px', '', areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-xs', 0 ) );
		$sm = str_replace( 'px', '', areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-sm', 576 ) );
		$md = str_replace( 'px', '', areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-md', 768 ) );
		$lg = str_replace( 'px', '', areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', 992 ) );
		$xl = str_replace( 'px', '', areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-xl', 1200 ) );
		$xxl = str_replace( 'px', '', areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-xxl', 1400 ) );

		$devices = array( 
			'_xs'	=> $xs,
			'_sm'	=> $sm,
			'_md'	=> $md,
			'_lg'	=> $lg,
			'_xl'	=> $xl,
			'_xxl'	=> $xxl
		);
		$styles = '';

		$page = get_the_ID();
		
		$standard_blocks = ( !$page ? array() : parse_blocks( get_the_content( null, false, $page ) ) );

		$page_reuseable_blocks = array();
		if ( !empty( $standard_blocks ) ) {
			foreach ( $standard_blocks as $block_key => $block ) {
				if ( $block['blockName'] == 'core/block' && !empty( $block['attrs']['ref'] ) ) {
					unset( $standard_blocks[$block_key] );
					$post = get_post($block['attrs']['ref']);
					if ( $post ) $page_reuseable_blocks = array_merge( parse_blocks( $post->post_content ), $page_reuseable_blocks );
				}
				if ( !$block['blockName'] ) {
					unset( $standard_blocks[$block_key] );
				}
			}
		}

		$block_posts = get_posts( array(
			'post_type' => 'wp_block',
			'numberposts' => -1
		) );
		$reuseable_blocks = array();
		if ( !empty( $block_posts ) ) {
			foreach ( $block_posts as $post_key => $post ) {
				$reuseable_blocks = array_merge( parse_blocks( $post->post_content ), $reuseable_blocks );
			}
		}

		$widget_block = areoi2_get_option( 'widget_block', null );
		$sidebars_widgets = areoi2_get_option( 'sidebars_widgets', array() );
		if ( is_array( $sidebars_widgets ) && isset( $sidebars_widgets['array_version'] ) ) unset( $sidebars_widgets['array_version'] );

		$widget_blocks = array();
		if ( !empty( $widget_block ) && !empty( $sidebars_widgets ) && is_array( $sidebars_widgets ) ) {
			foreach ( $sidebars_widgets as $sidebar_widget_key => $sidebar_widget ) {
				foreach ( $sidebar_widget as $block_key => $block ) {
					$block_key = str_replace( 'block-', '', $block );

					if ( !empty( $widget_block[$block_key] ) ) {
						$block = $widget_block[$block_key];
						$widget_blocks = array_merge( parse_blocks( $block['content'] ), $widget_blocks );
					}
				}
			}
		}

		global $_wp_current_template_content;
		$template_blocks = array();
		if ( $_wp_current_template_content ) {
			$template_blocks = parse_blocks( $_wp_current_template_content );
			foreach ( $template_blocks as $template_key => $template ) {
				if ( !empty( $template['blockName'] ) && $template['blockName'] == 'core/template-part' && !empty( $template['attrs']['slug'] ) ) {
					$template_part = get_block_template( get_stylesheet() . '//' . $template['attrs']['slug'], 'wp_template_part' );
					if ( !empty( $template_part->content ) && empty( $template_blocks[$template_key]['innerBlocks'] ) ) {
						$template_blocks[$template_key]['innerBlocks'] = parse_blocks( $template_part->content );
					}
				}
				
			}
		}
		
		$blocks = array_merge( $standard_blocks, $page_reuseable_blocks, $reuseable_blocks, $template_blocks, $widget_blocks );
		
		foreach ( $devices as $device_key => $device ) {

			$new_styles = '@media ( min-width: ' . $device . 'px ) {';

			$inner_styles = self::add_block_style( $blocks, $device_key );
			
			if ( empty( $inner_styles ) ) {
				continue;
			}
			$new_styles .= $inner_styles;

			$new_styles .= '}';
			$styles .= $new_styles;
		}
		
		wp_add_inline_style( 'areoi-style-index', areoi_minify_css( $styles ) );
	}

	public static function add_block_style( $blocks, $device_key )
	{	
		$styles = '';
		if ( !empty( $blocks ) ) {
			foreach ( $blocks as $block_key => $block ) {

				$attributes = $block['attrs'];
				
				$inner_styles = ( !empty( $attributes['height_dimension' . $device_key] ) ? 'height: ' . $attributes['height_dimension' . $device_key] . (!empty( !empty( $attributes['height_unit' . $device_key] ) ) ? $attributes['height_unit' . $device_key] : 'px') . ';' : '' );

				foreach ( array( 'padding', 'margin' ) as $pad_mar_key => $pad_mar ) {
					foreach ( array( 'top', 'right', 'bottom', 'left' ) as $dir_key => $dir ) {
						$attr_key = $pad_mar . '_' . $dir . $device_key;

						$value = ( isset( $attributes[$attr_key] ) ? $attributes[$attr_key] : null );

						if ( $value !== null && $value !== '' ) {
							$inner_styles .= $pad_mar . '-' . $dir . ': ' . $attributes[$attr_key] . areoi2_get_option( 'areoi-dashboard-global-display-units', 'px' ) . ';';
						}
					}
				}
				
				if ( !empty( $inner_styles ) && !empty( $attributes['block_id'] ) ) {
					$styles .= '.block-' . $attributes['block_id'] . ' {';
					$styles .= $inner_styles;			
				    $styles .= '}';
				}

				if ( areoi2_get_option( 'areoi-customize-options-enable-cssgrid', false ) ) {
					
					if ( $block['blockName'] == 'areoi/row' ) {

						$inr = '';

						if ( !empty( $attributes['row_cols' . $device_key] ) ) {
							$cols = preg_match('#(\d+)$#', $attributes['row_cols' . $device_key], $matches);
							if ( !empty( $matches[1] ) ) {
								$inr .= '--bs-columns: ' . $matches[1] . ';';
							}
						}

						if ( !empty( $attributes['grid_gap_dimension' . $device_key] ) ) {

							$dim = ( !empty( $attributes['grid_gap_dimension' . $device_key] ) ? '--bs-gap: ' . $attributes['grid_gap_dimension' . $device_key] . (!empty( !empty( $attributes['grid_gap_unit' . $device_key] ) ) ? $attributes['grid_gap_unit' . $device_key] : 'px') . ';' : '' );

							$inr .= $dim;
						}
						if ( !empty( $attributes['grid_row_gap_dimension' . $device_key] ) ) {

							$dim = ( !empty( $attributes['grid_row_gap_dimension' . $device_key] ) ? 'row-gap: ' . $attributes['grid_row_gap_dimension' . $device_key] . (!empty( !empty( $attributes['grid_row_gap_unit' . $device_key] ) ) ? $attributes['grid_row_gap_unit' . $device_key] : 'px') . ';' : '' );

							$inr .= $dim;
						}
						if ( !empty( $attributes['grid_rows' . $device_key] ) ) {
							$inr .= '--bs-rows: ' . $attributes['grid_rows' . $device_key] . ';';
						}
						if ( !empty( $inr ) ) {
							$styles .= '.block-' . $attributes['block_id'] . '.grid {';
							$styles .= $inr;
							$styles .= '}';
						}
						
					}

					if ( $block['blockName'] == 'areoi/column' ) {

						$inr = '';
						if ( !empty( $attributes['grid_row' . $device_key] ) ) {
							$inr .= 'grid-row: ' . $attributes['grid_row' . $device_key] . ';';
						}

						if ( !empty( $inr ) ) {
							$styles .= '.block-' . $attributes['block_id'] . ' {';
							$styles .= $inr;
							$styles .= '}';
						}
					}
				}			

			    if ( !empty( $block['innerBlocks'] ) ) {
			    	$styles .= self::add_block_style( $block['innerBlocks'], $device_key );
			    }
			}
		}

		return $styles;
	}
	public static function get_formatted_menus()
	{
		$menus = array(
			array(
				'label' => 'Default',
				'value' => ''
			)
		);
		$all_menus = get_terms( 'nav_menu' );

		if ( !empty( $all_menus ) ) {
			foreach ( $all_menus as $menu_key => $menu ) {
				$menus[] = array(
					'label' => $menu->name,
					'value' => $menu->term_id
				);
			}
		}

		return $menus;
	}
}
