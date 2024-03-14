<?php
/**
 * Integration of the Gutenberg into the plugin.
 *
 * @package Canvas
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once CNVS_PATH . 'gutenberg/block_renderer_controller.php';
require_once CNVS_PATH . 'gutenberg/components/fields-css-output/index.php';
require_once CNVS_PATH . 'gutenberg/utils/is-field-visible/index.php';
require_once CNVS_PATH . 'gutenberg/custom-blocks/index.php';

/**
 * Integration Gutenberg class.
 */
class CNVS_Gutenberg {

	/**
	 * __construct
	 *
	 * This function will initialize the initialize
	 */
	public function __construct() {

		// Define the functionality of the gutenberg.
		$this->initialize();
	}

	/**
	 * Initialize
	 *
	 * This function will initialize the gutenberg.
	 */
	public function initialize() {
		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			add_action( 'block_categories_all', array( $this, 'block_categories' ) );
		} else {
			add_action( 'block_categories', array( $this, 'block_categories' ) );
		}

		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_filter( 'widget_block_content', array( $this, 'widget_all_content' ), -1 );
		add_action( 'wp_head', array( $this, 'content_render_blocks_css' ) );
		add_action( 'wp_footer', array( $this, 'widget_render_blocks_css' ) );
		add_action( 'wp_ajax_cnvs_render_thumbnail', array( $this, 'cnvs_render_thumbnail' ) );
		add_action( 'wp_ajax_nopriv_cnvs_render_thumbnail', array( $this, 'cnvs_render_thumbnail' ) );
	}

	/**
	 * Filter the default array of block categories.
	 *
	 * @param array $categories Array of block categories.
	 */
	public function block_categories( $categories ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'canvas',
					'title' => esc_html__( 'Canvas', 'canvas' ),
				),
			)
		);
	}

	/**
	 * Get breakpoints data.
	 *
	 * @return array
	 */
	public function get_breakpoints_data() {
		$sizes = apply_filters(
			'canvas_register_breakpoints',
			array(
				'desktop' => 992, // >= 992
				'tablet'  => 991, // <= 991
				'mobile'  => 575, // <= 575
			)
		);

		$data = apply_filters(
			'canvas_register_breakpoints_data',
			array(
				'desktop'  => array(
					'label' => esc_html__( 'Desktop', 'canvas' ),
					'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M21 2H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h7v2H8v2h8v-2h-2v-2h7c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H3V4h18v12z"/></svg>',
				),
				'notebook' => array(
					'label' => esc_html__( 'Notebook', 'canvas' ),
					'icon'  => '<svg enable-background="new 0 0 64 64" height="24" viewBox="0 0 64 64" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m11.028 45.416h41.943c1.658 0 2.996-1.342 2.996-2.996v-29.96c0-2.206-1.787-3.994-3.992-3.994h-39.949c-2.206 0-3.993 1.788-3.993 3.994v29.96c0 1.654 1.342 2.996 2.995 2.996zm2.005-31.95h37.934v26.95h-37.934z"/><path d="m57.854 47.252h-51.707c-.553 0-1 .447-1 1v3.701c0 1.979 2.009 3.58 4.486 3.58h44.734c2.477 0 4.486-1.602 4.486-3.58v-3.701c.001-.553-.447-1-.999-1zm-19.616 5.141h-12.477c-.552 0-1-.447-1-1s.448-1 1-1h12.477c.553 0 1 .447 1 1s-.447 1-1 1z"/></svg>',
				),
				'laptop'   => array(
					'label' => esc_html__( 'Laptop', 'canvas' ),
					'icon'  => '<svg enable-background="new 0 0 64 64" height="24" viewBox="0 0 64 64" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m11.028 45.416h41.943c1.658 0 2.996-1.342 2.996-2.996v-29.96c0-2.206-1.787-3.994-3.992-3.994h-39.949c-2.206 0-3.993 1.788-3.993 3.994v29.96c0 1.654 1.342 2.996 2.995 2.996zm2.005-31.95h37.934v26.95h-37.934z"/><path d="m57.854 47.252h-51.707c-.553 0-1 .447-1 1v3.701c0 1.979 2.009 3.58 4.486 3.58h44.734c2.477 0 4.486-1.602 4.486-3.58v-3.701c.001-.553-.447-1-.999-1zm-19.616 5.141h-12.477c-.552 0-1-.447-1-1s.448-1 1-1h12.477c.553 0 1 .447 1 1s-.447 1-1 1z"/></svg>',
				),
				'tablet'   => array(
					'label' => esc_html__( 'Tablet', 'canvas' ),
					'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M18.5 0h-14C3.12 0 2 1.12 2 2.5v19C2 22.88 3.12 24 4.5 24h14c1.38 0 2.5-1.12 2.5-2.5v-19C21 1.12 19.88 0 18.5 0zm-7 23c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm7.5-4H4V3h15v16z"/></svg>',
				),
				'mobile'   => array(
					'label' => esc_html__( 'Mobile', 'canvas' ),
					'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 1h-8C6.12 1 5 2.12 5 3.5v17C5 21.88 6.12 23 7.5 23h8c1.38 0 2.5-1.12 2.5-2.5v-17C18 2.12 16.88 1 15.5 1zm-4 21c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5-4H7V4h9v14z"/></svg>',
				),
			)
		);

		$result = array();

		foreach ( $sizes as $name => $width ) {
			$result[ $name ] = array(
				'label' => isset( $data[ $name ]['label'] ) ? $data[ $name ]['label'] : $name,
				'icon'  => isset( $data[ $name ]['icon'] ) ? $data[ $name ]['icon'] : '',
				'width' => $width,
			);
		}

		// Sort from larger screens to smaller.
		uasort(
			$result,
			function ( $a, $b ) {
				return $b['width'] - $a['width'];
			}
		);

		return $result;
	}

	/**
	 * Get schemes data.
	 *
	 * @return array
	 */
	public function get_schemes_data() {
		$result = array();

		if ( get_theme_support( 'canvas-enable-data-scheme' ) ) {
			$schemes = array(
				'default' => array(
					'label' => esc_html__( 'Default', 'canvas' ),
					'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>',
				),
				'dark'    => array(
					'label' => esc_html__( 'Dark', 'canvas' ),
					'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>',
				),
			);

			foreach ( $schemes as $key => $data ) {
				$result[ $key ] = array(
					'label' => isset( $data['label'] ) ? $data['label'] : $key,
					'icon'  => isset( $data['icon'] ) ? $data['icon'] : '',
				);
			}
		}

		return $result;
	}

	/**
	 * Get all breakpoints data.
	 *
	 * @return array
	 */
	public function get_all_breakpoints_data() {
		$schemes     = cnvs_gutenberg()->get_schemes_data();
		$breakpoints = cnvs_gutenberg()->get_breakpoints_data();

		// Combine scheme and breakpoints.
		foreach ( $schemes as $name => $scheme ) {
			if ( $name && 'default' !== $name ) {
				foreach ( $breakpoints as $res => $breakpoint ) {
					if ( $res && 'desktop' !== $res ) {
						$breakpoints[ $name . '_' . $res ]           = $breakpoint;
						$breakpoints[ $name . '_' . $res ]['scheme'] = $name;
					}
				}
			}
		}

		return $breakpoints;
	}

	/**
	 * Enqueue block editor specific scripts.
	 */
	public function enqueue_block_editor_assets() {
		wp_enqueue_script(
			'canvas-gutenberg',
			CNVS_URL . 'gutenberg/index.js',
			array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-edit-post', 'wp-compose', 'wp-components' ),
			filemtime( CNVS_PATH . 'gutenberg/index.js' )
		);
		wp_localize_script( 'jquery-ui-core', 'canvasAllBreakpoints', $this->get_all_breakpoints_data() );
		wp_localize_script( 'jquery-ui-core', 'canvasBreakpoints', $this->get_breakpoints_data() );
		wp_localize_script( 'jquery-ui-core', 'canvasSchemes', $this->get_schemes_data() );
		wp_add_inline_script( 'jquery-ui-core', 'const canvasSupportInverseScheme = ' . wp_json_encode(
			get_theme_support( 'canvas-support-inverse-scheme' )
		), 'before' );

		if ( get_theme_support( 'canvas-enable-data-scheme' ) ) {
			wp_enqueue_script(
				'canvas-schemes',
				CNVS_URL . 'gutenberg/schemes/index.js',
				array( 'wp-editor', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-edit-post', 'wp-compose', 'wp-components' ),
				filemtime( CNVS_PATH . 'gutenberg/schemes/index.js' )
			);
		}
	}

	/**
	 * Get content all widget
	 *
	 * @param string $content The content.
	 */
	public function widget_all_content( $content ) {
		global $canvas_widget_all_content;

		if ( ! $canvas_widget_all_content ) {
			$canvas_widget_all_content = __return_empty_string();
		}

		$canvas_widget_all_content .= $content;

		return $content;
	}

	/**
	 * Parse blocks and prepare styles
	 *
	 * @param array $blocks Blocks array with attributes.
	 * @return string
	 */
	public function parse_blocks_css( $blocks ) {
		$schemes     = cnvs_gutenberg()->get_schemes_data();
		$breakpoints = cnvs_gutenberg()->get_breakpoints_data();

		$styles = '';

		// Reusable blocks.
		foreach ( $blocks as $key => $block ) {
			if ( isset( $block['blockName'] ) && isset( $block['attrs']['ref'] ) ) {
				if ( 'core/block' === $block['blockName'] ) {
					$gcontent = get_post( $block['attrs']['ref'] );

					if ( $gcontent ) {
						$gblocks = parse_blocks( $gcontent->post_content );

						$blocks[ $key ]['innerBlocks'] = $gblocks;
					}
				}
			}
		}

		// Loop blocks.
		foreach ( $blocks as $block ) {
			if ( isset( $block['attrs'] ) ) {
				if ( isset( $block['attrs']['canvasClassName'] ) && $block['attrs']['canvasClassName'] ) {
					$css_selector = '.' . $block['attrs']['canvasClassName'];

					/**
					 * Background Image.
					 */
					$background_image_css = '';

					foreach ( $breakpoints as $name => $breakpoint ) {
						$suffix                     = '';
						$media_background_image_css = '';

						if ( $name && 'desktop' !== $name ) {
							$suffix = '_' . $name;
						}

						if ( isset( $block['attrs'][ 'backgroundImage' . $suffix ] ) && $block['attrs'][ 'backgroundImage' . $suffix ]['id'] ) {
							$media_background_image_css .= 'background-image: url("' . esc_attr( $block['attrs'][ 'backgroundImage' . $suffix ]['url'] ) . '") !important;';
						}

						if ( isset( $block['attrs']['backgroundImage'] ) && $block['attrs']['backgroundImage']['id'] ) {

							if ( isset( $block['attrs'][ 'backgroundPosition' . $suffix ] ) ) {
								if ( 'custom' === $block['attrs'][ 'backgroundPosition' . $suffix ] ) {
									$unit_x = isset( $block['attrs'][ 'backgroundPositionXUnit' . $suffix ] ) ? $block['attrs'][ 'backgroundPositionXUnit' . $suffix ] : 'px';
									$val_x  = isset( $block['attrs'][ 'backgroundPositionXVal' . $suffix ] ) ? $block['attrs'][ 'backgroundPositionXVal' . $suffix ] : '0';
									$unit_y = isset( $block['attrs'][ 'backgroundPositionYUnit' . $suffix ] ) ? $block['attrs'][ 'backgroundPositionYUnit' . $suffix ] : 'px';
									$val_y  = isset( $block['attrs'][ 'backgroundPositionYVal' . $suffix ] ) ? $block['attrs'][ 'backgroundPositionYVal' . $suffix ] : '0';

									$media_background_image_css .= 'background-position: ' . esc_attr( $val_x ) . esc_attr( $unit_x ) . ' ' . esc_attr( $val_y ) . esc_attr( $unit_y ) . ' !important;';
								} else {
									$media_background_image_css .= 'background-position: ' . esc_attr( $block['attrs'][ 'backgroundPosition' . $suffix ] ) . ' !important;';
								}
							}

							if ( isset( $block['attrs'][ 'backgroundAttachment' . $suffix ] ) ) {
								$media_background_image_css .= 'background-attachment: ' . esc_attr( $block['attrs'][ 'backgroundAttachment' . $suffix ] ) . ' !important;';
							}

							if ( isset( $block['attrs'][ 'backgroundRepeat' . $suffix ] ) ) {
								$media_background_image_css .= 'background-repeat: ' . esc_attr( $block['attrs'][ 'backgroundRepeat' . $suffix ] ) . ' !important;';
							}

							if ( isset( $block['attrs'][ 'backgroundSize' . $suffix ] ) ) {

								if ( 'custom' === $block['attrs'][ 'backgroundSize' . $suffix ] ) {

									if ( isset( $block['attrs'][ 'backgroundSizeVal' . $suffix ] ) ) {
										$unit = isset( $block['attrs'][ 'backgroundSizeUnit' . $suffix ] ) ? $block['attrs'][ 'backgroundSizeUnit' . $suffix ] : '%';

										$media_background_image_css .= 'background-size: ' . esc_attr( $block['attrs'][ 'backgroundSizeVal' . $suffix ] ) . esc_attr( $unit ) . ' auto !important;';
									}
								} else {
									$media_background_image_css .= 'background-size: ' . esc_attr( $block['attrs'][ 'backgroundSize' . $suffix ] ) . ' !important;';
								}
							}
						}

						if ( $suffix && $media_background_image_css ) {
							$background_image_css .= '@media (max-width: ' . $breakpoint['width'] . 'px) { ' . $css_selector . ' { ' . $media_background_image_css . ' } } ';
						} elseif ( $media_background_image_css ) {
							$background_image_css .= $css_selector . ' { ' . $media_background_image_css . ' } ';
						}
					}

					if ( $background_image_css ) {
						$background_image_css_styles = $background_image_css;
						// Background image styles filter.
						$background_image_css = apply_filters( "canvas_blocks_dynamic_css_background_image_{$block['blockName']}", $background_image_css, $css_selector, $background_image_css_styles, $block );
					}

					/**
					 * Spacings.
					 */
					$spacings_css = '';

					// Available spacings.
					$all_spacings = array(
						'marginTop'     => 'margin-top',
						'marginBottom'  => 'margin-bottom',
						'marginLeft'    => 'margin-left',
						'marginRight'   => 'margin-right',
						'paddingTop'    => 'padding-top',
						'paddingBottom' => 'padding-bottom',
						'paddingLeft'   => 'padding-left',
						'paddingRight'  => 'padding-right',
					);

					foreach ( $breakpoints as $name => $breakpoint ) {
						$suffix             = '';
						$margin_unit        = 'px';
						$padding_unit       = 'px';
						$media_spacings_css = '';

						if ( $name && 'desktop' !== $name ) {
							$suffix = '_' . $name;
						}

						if ( isset( $block['attrs'][ 'marginUnit' . $suffix ] ) && $block['attrs'][ 'marginUnit' . $suffix ] ) {
							$margin_unit = $block['attrs'][ 'marginUnit' . $suffix ];
						}
						if ( isset( $block['attrs'][ 'paddingUnit' . $suffix ] ) && $block['attrs'][ 'paddingUnit' . $suffix ] ) {
							$padding_unit = $block['attrs'][ 'paddingUnit' . $suffix ];
						}

						foreach ( $all_spacings as $space => $prop ) {
							if ( isset( $block['attrs'][ $space . $suffix ] ) ) {
								$current_unit        = strpos( $space, 'margin' ) === 0 ? $margin_unit : $padding_unit;
								$media_spacings_css .= $prop . ': ' . esc_attr( $block['attrs'][ $space . $suffix ] ) . $current_unit . ' !important; ';
							}
						}

						if ( $suffix && $media_spacings_css ) {
							$spacings_css .= '@media (max-width: ' . $breakpoint['width'] . 'px) { ' . $css_selector . ' { ' . $media_spacings_css . ' } } ';
						} elseif ( $media_spacings_css ) {
							$spacings_css .= $css_selector . ' { ' . $media_spacings_css . ' } ';
						}
					}

					if ( $spacings_css ) {
						$spacings_css_styles = $spacings_css;

						// Spacings styles filter.
						$spacings_css = apply_filters( "canvas_blocks_dynamic_css_spacings_{$block['blockName']}", $spacings_css, $css_selector, $spacings_css_styles, $block );
					}

					/**
					 * Borders Radius.
					 */
					$borders_radius_css = '';

					// Available borders radius.
					$all_borders_radius = array(
						'borderRadiusTopLeft'     => 'border-top-left-radius',
						'borderRadiusTopRight'    => 'border-top-right-radius',
						'borderRadiusBottomLeft'  => 'border-bottom-left-radius',
						'borderRadiusBottomRight' => 'border-bottom-right-radius',
					);

					foreach ( $breakpoints as $name => $breakpoint ) {
						$suffix                  = '';
						$border_radius_unit      = 'px';
						$media_border_radius_css = '';

						if ( $name && 'desktop' !== $name ) {
							$suffix = '_' . $name;
						}

						if ( isset( $block['attrs'][ 'borderRadiusUnit' . $suffix ] ) && $block['attrs'][ 'borderRadiusUnit' . $suffix ] ) {
							$border_radius_unit = $block['attrs'][ 'borderRadiusUnit' . $suffix ];
						}

						foreach ( $all_borders_radius as $space => $prop ) {
							if ( isset( $block['attrs'][ $space . $suffix ] ) ) {
								$media_border_radius_css .= $prop . ': ' . esc_attr( $block['attrs'][ $space . $suffix ] ) . $border_radius_unit . ' !important; ';
							}
						}

						if ( $suffix && $media_border_radius_css ) {
							$borders_radius_css .= '@media (max-width: ' . $breakpoint['width'] . 'px) { ' . $css_selector . ' { ' . $media_border_radius_css . ' } } ';
						} elseif ( $media_border_radius_css ) {
							$borders_radius_css .= $css_selector . ' { ' . $media_border_radius_css . ' } ';
						}
					}

					if ( $borders_radius_css ) {
						$borders_radius_css_styles = $borders_radius_css;

						// Border styles filter.
						$borders_radius_css = apply_filters( "canvas_blocks_dynamic_css_border_radius_{$block['blockName']}", $borders_radius_css, $css_selector, $borders_radius_css_styles, $block );
					}

					/**
					 * Borders.
					 */
					$borders_css = '';

					// Available borders.
					$all_borders = array(
						'borderWidthTop'    => 'border-top-width',
						'borderWidthBottom' => 'border-bottom-width',
						'borderWidthLeft'   => 'border-left-width',
						'borderWidthRight'  => 'border-right-width',
					);

					if ( isset( $block['attrs']['borderStyle'] ) && $block['attrs']['borderStyle'] ) {
						$main_border_css  = 'border-style: ' . $block['attrs']['borderStyle'] . ';';
						$main_border_css .= 'border-width: 0;';

						$borders_css .= $css_selector . ' { ' . $main_border_css . ' } ';

						foreach ( $schemes as $name => $scheme ) {
							$rule   = '';
							$suffix = '';

							if ( $name && 'default' !== $name ) {
								$suffix = '_' . $name;

								$rule = sprintf( '[data-scheme="%s"] ', $name );
							}

							if ( isset( $block['attrs'][ 'borderColor' . $suffix ] ) ) {
								$main_border_css = 'border-color: ' . $block['attrs'][ 'borderColor' . $suffix ] . ';';

								$borders_css .= $rule . $css_selector . ' { ' . $main_border_css . ' } ';
							}
						}

						foreach ( $breakpoints as $name => $breakpoint ) {
							$suffix            = '';
							$border_width_unit = 'px';
							$media_border_css  = '';

							if ( $name && 'desktop' !== $name ) {
								$suffix = '_' . $name;
							}

							if ( isset( $block['attrs'][ 'borderWidthUnit' . $suffix ] ) && $block['attrs'][ 'borderWidthUnit' . $suffix ] ) {
								$border_width_unit = $block['attrs'][ 'borderWidthUnit' . $suffix ];
							}

							foreach ( $all_borders as $space => $prop ) {
								if ( isset( $block['attrs'][ $space . $suffix ] ) ) {
									$media_border_css .= $prop . ': ' . esc_attr( $block['attrs'][ $space . $suffix ] ) . $border_width_unit . ' !important; ';
								}
							}

							if ( $suffix && $media_border_css ) {
								$borders_css .= '@media (max-width: ' . $breakpoint['width'] . 'px) { ' . $css_selector . ' { ' . $media_border_css . ' } } ';
							} elseif ( $media_border_css ) {
								$borders_css .= $css_selector . ' { ' . $media_border_css . ' } ';
							}
						}

						if ( $borders_css ) {
							$borders_css_styles = $borders_css;

							// Border styles filter.
							$borders_css = apply_filters( "canvas_blocks_dynamic_css_border_{$block['blockName']}", $borders_css, $css_selector, $borders_css_styles, $block );
						}
					}

					/**
					 * Responsive Display.
					 */
					$resp_css = '';

					foreach ( $breakpoints as $name => $breakpoint ) {
						$resp_media = '';

						switch ( $name ) {
							case 'mobile':
								$resp_media = '(max-width: ' . $breakpoints['mobile']['width'] . 'px)';
								break;
							case 'tablet':
								$resp_media = '(min-width: ' . ( $breakpoints['mobile']['width'] + 1 ) . 'px) and (max-width: ' . $breakpoints['tablet']['width'] . 'px)';
								break;
							case 'notebook':
								$resp_media = '(min-width: ' . ( $breakpoints['tablet']['width'] + 1 ) . 'px) and (max-width: ' . $breakpoints['notebook']['width'] . 'px)';
								break;
							case 'laptop':
								$resp_media = '(min-width: ' . ( $breakpoints['tablet']['width'] + 1 ) . 'px) and (max-width: ' . $breakpoints['laptop']['width'] . 'px)';
								break;
							case 'desktop':
								$resp_media = '(min-width: ' . ( $breakpoints['desktop']['width'] ) . 'px)';
								break;
						}

						if ( isset( $block['attrs'][ 'canvasResponsiveHide_' . $name ] ) && $block['attrs'][ 'canvasResponsiveHide_' . $name ] ) {
							$resp_css .= '@media ' . $resp_media . ' { ' . $css_selector . ' { display: none !important; } } ';
						}

						$resp_prev_breakpoint = $breakpoint;
					}

					// Concat.
					$styles .= $background_image_css . $spacings_css . $borders_radius_css . $borders_css . $resp_css;
				}

				// All styles filter.
				$styles = apply_filters( "canvas_blocks_dynamic_css_{$block['blockName']}", $styles, $block );
			}

			// Inner blocks.
			if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				$styles .= $this->parse_blocks_css( $block['innerBlocks'] );
			}
		}

		// All blocks styles filter.
		$styles = apply_filters( 'canvas_blocks_dynamic_css', $styles, $blocks );

		return $styles;
	}

	/**
	 * Content render blocks CSS.
	 */
	public function content_render_blocks_css() {
		if ( ! function_exists( 'has_blocks' ) || ! function_exists( 'parse_blocks' ) || ! has_blocks( get_the_ID() ) ) {
			return;
		}

		global $post;

		if ( ! is_object( $post ) ) {
			return;
		}

		$blocks = parse_blocks( $post->post_content );

		if ( ! is_array( $blocks ) || empty( $blocks ) ) {
			return;
		}

		$style  = "\n<style type=\"text/css\" media=\"all\" id=\"canvas-blocks-dynamic-styles\">\n";
		$style .= $this->parse_blocks_css( $blocks );
		$style .= "\n</style>\n";

		echo (string) $style; // XSS.
	}

	/**
	 * Widget render blocks CSS.
	 */
	public function widget_render_blocks_css() {
		global $canvas_widget_all_content;

		if ( ! function_exists( 'has_blocks' ) || ! function_exists( 'parse_blocks' ) ) {
			return;
		}

		global $canvas_widget_all_content;

		if ( ! $canvas_widget_all_content ) {
			return;
		}

		$blocks = parse_blocks( $canvas_widget_all_content );

		if ( ! is_array( $blocks ) || empty( $blocks ) ) {
			return;
		}

		$style  = "\n<style type=\"text/css\" media=\"all\" id=\"canvas-widget-blocks-dynamic-styles\">\n";
		$style .= $this->parse_blocks_css( $blocks );
		$style .= "\n</style>\n";

		echo (string) $style; // XSS.
	}

	/**
	 * Render thumbnail.
	 */
	public function cnvs_render_thumbnail() {
		if ( isset( $_GET['image_id'] ) ) { // Input var ok.
			$image_id = sanitize_text_field( wp_unslash( $_GET['image_id'] ) ); // Input var ok.

			$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );

			if ( $image_url ) {
				header( 'Location: ' . $image_url );
			}
		}

		die();
	}
}

/**
 * The main function responsible for returning the one true canvas Instance to functions everywhere.
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php $cnvs_gutenberg = cnvs_gutenberg(); ?>
 */
function cnvs_gutenberg() {

	// Globals.
	global $cnvs_gutenberg_instance;

	// Init.
	if ( ! isset( $cnvs_gutenberg_instance ) ) {
		$cnvs_gutenberg_instance = new CNVS_Gutenberg();
	}

	return $cnvs_gutenberg_instance;
}

// Initialize.
cnvs_gutenberg();
