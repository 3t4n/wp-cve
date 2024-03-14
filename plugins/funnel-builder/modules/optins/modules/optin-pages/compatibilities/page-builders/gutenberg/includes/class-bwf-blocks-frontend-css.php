<?php
/**
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package BWF Blocks
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class to Enqueue CSS of all the blocks.
 *
 * @category class
 */
#[AllowDynamicProperties]

  class BWF_Blocks_Optin_Frontend_CSS {
	/**
	 * Instance of this class
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Google fonts to enqueue
	 *
	 * @var array
	 */
	public static $gfonts = array();

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_inline_css' ), 20 );
	}
	
	/**
	 * Outputs extra css for blocks.
	 */
	public function frontend_inline_css() {
		if ( function_exists( 'has_blocks' ) && has_blocks( get_the_ID() ) ) {

			global $post;
			if ( ! is_object( $post ) || true !== WFOPP_Core()->optin_pages->is_wfop_page() ) {
				return;
			}

			global $wp_query;
			$post_to_pass = $post;
			if ( isset( $wp_query->query['preview'] ) && 'true' === $wp_query->query['preview'] ) {
				$post_to_pass = $wp_query->posts[0];
			}
			$this->frontend_build_css( $post_to_pass );
		}
	}

	/**
	 * Render Inline CSS helper function
	 *
	 * @param array  $css the css for each rendered block.
	 * @param string $style_id the unique id for the rendered style.
	 * @param bool   $in_content the bool for whether or not it should run in content.
	 */
	public function render_inline_css( $css, $style_id, $in_content = false ) {
		if ( ! is_admin() ) {
			wp_register_style( $style_id, false );
			wp_enqueue_style( $style_id );
			wp_add_inline_style( $style_id, $css );
			if ( 1 === did_action( 'wp_head' ) && $in_content ) {
				wp_print_styles( $style_id );
			}
		}
	}

	/**
	 * Gets the parsed blocks, need to use this becuase wordpress 5 doesn't seem to include gutenberg_parse_blocks
	 *
	 * @param string $content string of page/post content.
	 */
	public function bwf_parse_blocks( $content ) {
		$parser_class = apply_filters( 'block_parser_class', 'WP_Block_Parser' );
		if ( class_exists( $parser_class ) ) {
			$parser = new $parser_class();
			return $parser->parse( $content );
		} elseif ( function_exists( 'gutenberg_parse_blocks' ) ) {
			return gutenberg_parse_blocks( $content );
		} else {
			return false;
		}
	}
	
	
	/**
	 * Outputs extra css for blocks.
	 *
	 * @param $post_object object of WP_Post.
	 */
	public function frontend_build_css( $post_object ) {
		if ( ! is_object( $post_object ) ) {
			return;
		}
		if ( ! method_exists( $post_object, 'post_content' ) ) {
			$blocks = $this->bwf_parse_blocks( $post_object->post_content );
			if ( ! is_array( $blocks ) || empty( $blocks ) ) {
				return;
			}
			$this->compute_bwf_blocks($blocks);
			
		}
	}

	public function compute_bwf_blocks( $blocks ) {
		foreach ( $blocks as $block ) {
			$block = apply_filters( 'bwf_blocks_frontend_build_css', $block );
			if ( ! is_object( $block ) && is_array( $block ) && isset( $block['blockName'] ) ) {
				
				if ( 'bwfblocks/optin-form' === $block['blockName'] ) {
					if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
						$blockattr = $block['attrs'];
						if ( isset( $blockattr['uniqueID'] ) ) {
							$unique_id = $blockattr['uniqueID'];
							$style_id  = 'bwfblocks-' . esc_attr( $unique_id );
							if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
								$css = $this->render_optin_form_css_head( $blockattr, $unique_id );
								if ( ! empty( $css ) ) {
									$this->render_inline_css( $css, $style_id );
								}
							}
						}
					}
				}

				if ( 'bwfblocks/popup-form' === $block['blockName'] ) {
					if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
						$blockattr = $block['attrs'];
						if ( isset( $blockattr['uniqueID'] ) ) {
							$unique_id = $blockattr['uniqueID'];
							$style_id  = 'bwfblocks-' . esc_attr( $unique_id );
							if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
								$css = $this->render_form_popup_css_head( $blockattr, $unique_id );
								if ( ! empty( $css ) ) {
									$this->render_inline_css( $css, $style_id );
								}
							}
						}
					}
				}
			
				if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
					$this->compute_bwf_blocks( $block['innerBlocks'] );
				}
			}
		}
	}

	/**
	 * @param mixed $attr 
	 * @param string $indexkey - check whether indexkey is set in $attr[] array or not
	 * @param mixed $default - function return default value which you passed as a 3rd parameter eg. you need 'inherit' value when $indexkey value is true
	 * @return void
	 */
	public function has_attr( $attr, $indexkey, $screen = '', $default_val = null ) {
		$value = null;
		if( empty( $screen ) ) {
			if( isset( $attr[ $indexkey ] ) ) {
				$value = $attr[ $indexkey ];
			}
		} else {
			if( isset( $attr[ $indexkey ] ) && isset( $attr[ $indexkey ][ $screen ] ) ) {
				$value = $attr[ $indexkey ][ $screen ];
			}
		}
		return ! is_null( $default_val ) && ! empty( $value ) ? $default_val : $value;
	}

	/**
	 * Render button Block CSS
	 *
	 * @param array $attributes the blocks attribtues.
	 */
	public function render_button_css_head( $attr, $unique_id ) {
		$css = new BWF_Blocks_CSS();
		$media_query           = array();
		$media_query['mobile'] = apply_filters( 'bwf_blocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet'] = apply_filters( 'bwf_blocks_tablet_media_query', '(max-width: 1024px)' );
		
		$selector_wrapper = 'body .bwf-btn-wrap.bwf-' . $unique_id;

		$screens = array( 'desktop', 'tablet', 'mobile' );
		$button = $this->has_attr( $attr, 'button' ) ?? [];

		$icon_space = $this->has_attr( $button, 'iconSpace' );
		if ( $button && $icon_space ) {
			$iconPos = $this->has_attr( $button, 'iconPos' );
			$iconPos = $iconPos && 'left' === $iconPos ? 'margin-right' : 'margin-left';
			$css->set_selector( "{$selector_wrapper} .bwf-icon-inner-svg" );
			$css->add_property( $iconPos, "{$icon_space}px" );
		}

		foreach ($screens as $screen) {
			if( 'desktop' !== $screen ) {
				$css->start_media_query( $media_query[ $screen ] );
			}
			
			$css->set_selector( $selector_wrapper );
			$alignment = $this->has_attr( $attr, 'text', $screen ) ? $this->has_attr( $attr, 'text', $screen )['align']: '';
			$css->add_property( 'text-align', $alignment );

			$css->set_selector( $selector_wrapper . ' .bwf-btn' );
			$css->add_property( 'width', $this->has_attr( $attr, 'width', $screen ), 'width' );
			$css->add_property( 'min-width', $this->has_attr( $attr, 'minWidth', $screen ), 'width' );
			$css->add_property( 'max-width', $this->has_attr( $attr, 'maxWidth', $screen ), 'width' );
			$css->add_property( 'height', $this->has_attr( $attr, 'height', $screen ), 'height' );
			$css->add_property( 'min-height', $this->has_attr( $attr, 'minHeight', $screen ), 'height' );
			$css->add_property( 'max-height', $this->has_attr( $attr, 'maxHeight', $screen ), 'height' );
			$css->add_property( 'background', $this->has_attr( $attr, 'background', $screen ) );
			$css->add_property( 'border', $this->has_attr( $attr, 'border', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'boxShadow', $screen ) );
			$css->add_property( 'color', $this->has_attr( $attr, 'color', $screen ) );
			$i_space = $this->has_attr( $button, 'iconSpace' ) ? $this->has_attr( $button, 'iconSpace' ) . 'px' : '';
			$css->add_property( 'gap', $i_space );
			if( $this->has_attr( $attr, 'marginAuto', $screen ) && 'full' !== $this->has_attr( $attr, 'align', $screen ) ) {
				$css->add_property( 'margin-left', 'auto' );
				$css->add_property( 'margin-right', 'auto' );
			}

			$css->set_selector( $selector_wrapper . ' .bwf-btn .bwf-btn-inner-text' );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'lineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'letterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'font', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'text', $screen ) );

			$css->set_selector( $selector_wrapper . ' .bwf-icon-inner-svg svg' );
			$i_size = $this->has_attr( $button, 'iconSize' ) ? $this->has_attr( $button, 'iconSize' ) . 'px' : '';
			$css->add_property( 'width', $i_size );
			$css->add_property( 'height', $i_size );

			$css->set_selector( $selector_wrapper . ' .bwf-btn:hover' );
			$css->add_property( 'background', $this->has_attr( $attr, 'backgroundHover', $screen ) );
			$css->add_property( 'border', $this->has_attr( $attr, 'borderHover', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'boxShadowHover', $screen ) );
			$css->add_property( 'color', $this->has_attr( $attr, 'colorHover', $screen ) );


				$css->set_selector( $selector_wrapper . ' .bwf-btn .bwf-btn-sub-text' );
				$css->add_property( 'line-height', $this->has_attr( $attr, 'secondaryLineHeight', $screen ), true );
				$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'secondaryLetterSpacing', $screen ), true );
				$css->add_property( 'font', $this->has_attr( $attr, 'secondaryFont', $screen ) );
				$css->add_property( 'text', $this->has_attr( $attr, 'secondaryText', $screen ) );
				$css->add_property( 'color', $this->has_attr( $attr, 'secondaryColor', $screen ) );
				$css->add_property( 'margin-top', $this->has_attr( $attr, 'contentSpace', $screen ), true );

				$css->set_selector( $selector_wrapper . ' .bwf-btn:hover .bwf-btn-sub-text' );
				$css->add_property( 'color', $this->has_attr( $attr, 'secondaryColorHover', $screen ) );
				$css->add_property( 'line-height', $this->has_attr( $attr, 'secondaryLineHeightHover', $screen ), true );
				$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'secondaryLetterSpacingHover', $screen ), true );
				$css->add_property( 'font', $this->has_attr( $attr, 'secondaryFontHover', $screen ) );
				$css->add_property( 'text', $this->has_attr( $attr, 'secondaryTextHover', $screen ) );
				$css->add_property( 'margin-top', $this->has_attr( $attr, 'contentSpace'), true );



	
			if( 'desktop' !== $screen ) {
				$css->stop_media_query();
			}
		}

		$custom_css = $this->has_attr( $attr, 'bwfBlockCSS' );
		return $css->custom_css( $custom_css, $selector_wrapper . ' .bwf-btn' )->css_output();

	}


	/**
	 * Render heading Block CSS
	 *
	 * @param array $attributes the blocks attribtues.
	 */
	public function render_optin_form_css_head( $attr, $unique_id ) {
		$css = new BWF_Blocks_CSS();
		$media_query           = array();
		$media_query['mobile'] = apply_filters( 'bwf_blocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet'] = apply_filters( 'bwf_blocks_tablet_media_query', '(max-width: 1024px)' );
		
		$selector_wrapper = '.bwfop-form-container.bwf-' . $unique_id . ' .bwfop-form-wrap';
		$selector_hover = '.bwfop-form-container.bwf-' . $unique_id . ':hover .bwfop-form-wrap:hover';

		$screens = array( 'desktop', 'tablet', 'mobile' );

		$css->set_selector( "{$selector_wrapper} .wfop_section .bwfac_form_sec, {$selector_wrapper} .bwfac_form_sec.submit_button" );
		$col = $this->has_attr( $attr, 'columns' );
		$col = $col ? $col : '10';
		$css->add_property( 'padding-right', "calc( {$col}px/2)" );
		$css->add_property( 'padding-left', "calc( {$col}px/2)" );
		
		$rows = $this->has_attr( $attr, 'rows' );
		$rows = $rows ? $rows : '10';
		$css->add_property( 'margin-bottom', "{$rows}px" );
		
		$label = $this->has_attr( $attr, 'labelWidth' );
		if ( $label ) {
			$css->set_selector( $selector_wrapper . ' .wfop_section .bwfac_form_sec > label' );
			$css->add_property( 'margin-bottom', "{$label}px" );
		}
		
		$field_size = $this->has_attr( $attr, 'fieldSize' );
		if ( $field_size && '12px' !== $field_size ) {
			$css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec input, {$selector_wrapper} .single_step .bwfac_form_sec select, {$selector_wrapper} .single_step .bwfac_form_sec textarea" );
			$css->add_property( 'padding-top', $field_size );
			$css->add_property( 'padding-bottom', $field_size );
		}

		$contentSpace = $this->has_attr( $attr, 'contentSpace' );
		if ( $contentSpace && isset( $contentSpace['value'] ) ) {
			$unit = isset( $contentSpace['unit'] ) ?  $contentSpace['unit'] : 'px';
			$css->set_selector( "{$selector_wrapper} .bwf-custom-button .wfop_submit_btn .bwf_subheading " );
			$css->add_property( 'margin-top', "{$contentSpace['value']}{$unit}" );
		}

		foreach ($screens as $screen) {
			if( 'desktop' !== $screen ) {
				$css->start_media_query( $media_query[ $screen ] );
				$css->set_selector( "{$selector_wrapper} .wfop_section .bwfac_form_sec, {$selector_wrapper} .bwfac_form_sec.submit_button" );
				$col = $this->has_attr( $attr, 'columnsObject', $screen );
				$col = $col ? $col : '10';
				$css->add_property( 'padding-right', "calc({$col}px/2)" );
				$css->add_property( 'padding-left', "calc( {$col}px/2)" );
				
				$rows = $this->has_attr( $attr, 'rowsObject', $screen );
				$rows = $rows ? $rows : '10';
				$css->add_property( 'margin-bottom', "{$rows}px" );
				
				$label = $this->has_attr( $attr, 'labelWidthObject', $screen );
				if ( $label ) {
					$css->set_selector( $selector_wrapper . ' .wfop_section .bwfac_form_sec > label' );
					$css->add_property( 'margin-bottom', "{$label}px" );
				}
			}

			$css->set_selector( $selector_wrapper );
			$css->add_property( 'z-index', $this->has_attr( $attr, 'zIndex', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'background', $screen ) );
			$css->add_property( 'margin', $this->has_attr( $attr, 'margin', $screen ) );
			$css->add_property( 'padding', $this->has_attr( $attr, 'padding', $screen ) );
			$css->add_property( 'width', $this->has_attr( $attr, 'width', $screen ), 'width' );
			$css->add_property( 'min-width', $this->has_attr( $attr, 'minWidth', $screen ), 'width' );
			$css->add_property( 'max-width', $this->has_attr( $attr, 'maxWidth', $screen ), 'width' );
			$css->add_property( 'height', $this->has_attr( $attr, 'height', $screen ), 'height' );
			$css->add_property( 'min-height', $this->has_attr( $attr, 'minHeight', $screen ), 'height' );
			$css->add_property( 'max-height', $this->has_attr( $attr, 'maxHeight', $screen ), 'height' );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'boxShadow', $screen ) );

			$css->set_selector( $selector_hover );
			$css->add_property( 'border', $this->has_attr( $attr, 'borderHover', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'backgroundHover', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'boxShadowHover', $screen ) );

			/**
			 * Label
			 */
			$css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec label" );
			$css->add_property( 'color', $this->has_attr( $attr, 'labelColor', $screen ) );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'labelLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'labelFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'labelText', $screen ) );

            $css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec label:first-child" );
            $css->add_property( 'line-height', $this->has_attr( $attr, 'labelLineHeight', $screen ), true );

			$css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec label:hover" );
			$css->add_property( 'color', $this->has_attr( $attr, 'labelColorHover', $screen ) );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'labelLetterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'labelFontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'labelTextHover', $screen ) );


            $css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec label:hover:first-child" );
            $css->add_property( 'line-height', $this->has_attr( $attr, 'labelLineHeightHover', $screen ), true );

			/**
			 * Asterisk
			 */
			$css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec label span" );
			$css->add_property( 'color', $this->has_attr( $attr, 'asteriskColor', $screen ) );

			$css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec label span:hover" );
			$css->add_property( 'color', $this->has_attr( $attr, 'asteriskColorHover', $screen ) );

			/**
			 * Input
			 */
			$css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec input, {$selector_wrapper} .single_step .bwfac_form_sec select, {$selector_wrapper} .single_step .bwfac_form_sec textarea, {$selector_wrapper} .single_step .bwfac_form_sec input::-webkit-input-placeholder, {$selector_wrapper} .single_step .bwfac_form_sec textarea::-webkit-input-placeholder" );
			$css->add_property( 'color', $this->has_attr( $attr, 'inputColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'inputLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'inputLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'inputFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'inputText', $screen ) );
            $css->add_property( 'background', $this->has_attr( $attr, 'inputBackground', $screen ) );

			$css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec input, {$selector_wrapper} .single_step .bwfac_form_sec select, {$selector_wrapper} .single_step .bwfac_form_sec textarea" );
			$css->add_property( 'border', $this->has_attr( $attr, 'inputBorder', $screen ) );

			$css->set_selector( "{$selector_wrapper} .single_step .bwfac_form_sec input:hover, {$selector_wrapper} .single_step .bwfac_form_sec select:hover, {$selector_wrapper} .single_step .bwfac_form_sec textarea:hover" );
			$css->add_property( 'color', $this->has_attr( $attr, 'inputColorHover', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'inputLineHeightHover', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'inputLetterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'inputFontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'inputTextHover', $screen ) );
			$css->add_property( 'border', $this->has_attr( $attr, 'inputBorderHover', $screen ) );

			
			$css->set_selector( "{$selector_wrapper} .bwf-custom-button" );
			$css->add_property( 'text-align', $this->has_attr( $attr, 'alignment', $screen ) );

			/**
			 * Button
			 */
			$css->set_selector( "{$selector_wrapper} .bwf-custom-button .wfop_submit_btn .bwf-text-wrapper" );
			$css->add_property( 'color', $this->has_attr( $attr, 'color', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'lineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'letterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'font', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'text', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf-custom-button .wfop_submit_btn .bwf-text-wrapper:hover" );
			$css->add_property( 'color', $this->has_attr( $attr, 'colorHover', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'lineHeightHover', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'letterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'fontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'textHover', $screen ) );

			/**
			 * Button
			 */
			$css->set_selector( "{$selector_wrapper} .bwf-custom-button .wfop_submit_btn .bwf_subheading " );
			$css->add_property( 'color', $this->has_attr( $attr, 'secondaryColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'secondaryLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'secondaryLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'secondaryFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'secondaryText', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf-custom-button .wfop_submit_btn .bwf_subheading:hover" );
			$css->add_property( 'color', $this->has_attr( $attr, 'secondaryColorHover', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'secondaryLineHeightHover', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'secondaryLetterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'secondaryFontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'secondaryTextHover', $screen ) );
			
			/**
			 * Button BG
			 */
			$css->set_selector( "{$selector_wrapper} .bwf-custom-button .wfop_submit_btn" );
			$css->add_property( 'background', $this->has_attr( $attr, 'buttonBackground', $screen ) );
			$css->add_property( 'width', $this->has_attr( $attr, 'buttonWidth', $screen ), 'width' );
			$css->add_property( 'height', $this->has_attr( $attr, 'buttonHeight', $screen ), 'height' );
			$css->add_property( 'margin', $this->has_attr( $attr, 'marginButton', $screen ) );
			$css->add_property( 'padding', $this->has_attr( $attr, 'paddingButton', $screen ) );
			$css->add_property( 'border', $this->has_attr( $attr, 'border', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf-custom-button .wfop_submit_btn:hover" );
			$css->add_property( 'background', $this->has_attr( $attr, 'buttonBackgroundHover', $screen ) );

			if( 'desktop' !== $screen ) {
				$css->stop_media_query();
			}
		}

		$custom_css = $this->has_attr( $attr, 'bwfBlockCSS' );
		return $css->custom_css( $custom_css, '.wfocu-price-wrapper.bwf-' . $unique_id )->css_output();

	}

	/**
	 * Render heading Block CSS
	 *
	 * @param array $attributes the blocks attribtues.
	 */
	public function render_form_popup_css_head( $attr, $unique_id ) {
		$css = new BWF_Blocks_CSS();
		$media_query           = array();
		$media_query['mobile'] = apply_filters( 'bwf_blocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet'] = apply_filters( 'bwf_blocks_tablet_media_query', '(max-width: 1024px)' );
		
		$selector_wrapper = 'body .bwf-' . $unique_id . '.bwfop-popup-form-container ';
		$selector_hover = 'body .bwf-' . $unique_id . '.bwfop-popup-form-container:hover';

		$screens = array( 'desktop', 'tablet', 'mobile' );

		$button     = $this->has_attr( $attr, 'button' );
		$icon_space = $this->has_attr( $button, 'iconSpace' );
		if ( $button && $icon_space ) {
			$iconPos = $this->has_attr( $button, 'iconPos' );
			$iconPos = $iconPos && 'left' === $iconPos ? 'margin-right' : 'margin-left';
			$css->set_selector( "{$selector_wrapper} .bwfop-poup-button-wrap .bwf-btn-popup .bwf-icon-inner-svg" );
			$css->add_property( $iconPos, "{$icon_space}px" );
		}

		foreach ( $screens as $screen ) {
			if ( 'desktop' !== $screen ) {
				$css->start_media_query( $media_query[ $screen ] );
			}
			$css->set_selector( "{$selector_wrapper} .bwfop-poup-button-wrap" );
			$css->add_property( 'text-align', $this->has_attr( $attr, 'alignment', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf-btn.bwf-btn-popup" );
			$css->add_property( 'z-index', $this->has_attr( $attr, 'zIndex', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'lineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'letterSpacing', $screen ), true );

			$css->set_selector( "{$selector_wrapper} .bwfop-poup-button-wrap .bwf-btn-popup" );
			$css->add_property( 'background', $this->has_attr( $attr, 'buttonBackground', $screen ) );
			$css->add_property( 'width', $this->has_attr( $attr, 'buttonWidth', $screen ), 'width' );
			$css->add_property( 'height', $this->has_attr( $attr, 'buttonHeight', $screen ), 'height' );
			$css->add_property( 'margin', $this->has_attr( $attr, 'marginButton', $screen ) );
			$css->add_property( 'padding', $this->has_attr( $attr, 'paddingButton', $screen ) );
            $css->add_property( 'border', $this->has_attr( $attr, 'border', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'buttonBoxShadow', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwfop-poup-button-wrap .bwf-btn-popup:hover" );
			$css->add_property( 'background', $this->has_attr( $attr, 'buttonBackgroundHover', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'buttonBoxShadowHover', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf-btn.bwf-btn-popup .bwf-icon-inner-svg" );
			$css->add_property( 'color', $this->has_attr( $attr, 'color', $screen ) );

			$css->set_selector( "{$selector_hover} .bwf-btn.bwf-btn-popup .bwf-icon-inner-svg" );
			$css->add_property( 'color', $this->has_attr( $attr, 'colorHover', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf-btn.bwf-btn-popup .bwfop-secondary" );
			$css->add_property( 'text', $this->has_attr( $attr, 'text', $screen ) );
			if ( $this->has_attr( $attr, 'text', $screen ) && isset( $this->has_attr( $attr, 'text', $screen )['align'] ) ) {
				$css->add_property( 'justify-content', $this->has_attr( $attr, 'text', $screen )['align'] );
			}

			$css->set_selector( "{$selector_hover} .bwf-btn.bwf-btn-popup .bwfop-secondary" );
			$css->add_property( 'text', $this->has_attr( $attr, 'text', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf-btn.bwf-btn-popup .bwf-btn-inner-text" );
			$css->add_property( 'color', $this->has_attr( $attr, 'color', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'lineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'letterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'font', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'text', $screen ) );

			$css->set_selector( "{$selector_hover} .bwf-btn.bwf-btn-popup .bwf-btn-inner-text" );
			$css->add_property( 'color', $this->has_attr( $attr, 'colorHover', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'lineHeightHover', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'letterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'fontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'textHover', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwfop-poup-button-wrap .bwf-btn-sub-text" );
			$css->add_property( 'color', $this->has_attr( $attr, 'secondaryColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'secondaryLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'secondaryLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'secondaryFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'secondaryText', $screen ) );
			$css->add_property( 'margin-top', $this->has_attr( $attr, 'contentSpace'), true );

			$css->set_selector( "{$selector_hover} .bwfop-poup-button-wrap .bwf-btn-sub-text" );
			$css->add_property( 'color', $this->has_attr( $attr, 'secondaryColorHover', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'secondaryLineHeightHover', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'secondaryLetterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'secondaryFontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'secondaryTextHover', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwfop-poup-button-wrap .bwf-btn-popup .bwf-icon-inner-svg svg" );
			$i_size = $this->has_attr( $button, 'iconSize' ) ? $this->has_attr( $button, 'iconSize' ) . 'px' : '';
			$css->add_property( 'width', $i_size );
			$css->add_property( 'height', $i_size );
			$css->set_selector( "{$selector_wrapper} .bwf_pp_wrap .bwf_pp_cont" );
			
			$css->set_selector( "{$selector_wrapper} .bwf_pp_cont" );
			$css->add_property( 'background', $this->has_attr( $attr, 'background', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'boxShadow', $screen ) );
			$css->add_property( 'margin', $this->has_attr( $attr, 'margin', $screen ) );
			$css->add_property( 'padding', $this->has_attr( $attr, 'padding', $screen ) );
			
			$css->set_selector( "{$selector_wrapper} .bwf_pp_wrap" );
			$css->add_property( 'width', $this->has_attr( $attr, 'width', $screen ), 'width' );
			$css->add_property( 'min-width', $this->has_attr( $attr, 'minWidth', $screen ), 'width' );
			$css->add_property( 'max-width', $this->has_attr( $attr, 'maxWidth', $screen ), 'width' );

			$css->set_selector( "{$selector_wrapper} .bwf_pp_wrap .bwf_pp_cont" );
			$css->add_property( 'height', $this->has_attr( $attr, 'height', $screen ), 'height' );
			$css->add_property( 'min-height', $this->has_attr( $attr, 'minHeight', $screen ), 'height' );
			$css->add_property( 'max-height', $this->has_attr( $attr, 'maxHeight', $screen ), 'height' );

			$css->set_selector( "{$selector_wrapper} .bwf_pp_cont:hover" );
			$css->add_property( 'background', $this->has_attr( $attr, 'backgroundHover', $screen ) );
			$css->add_property( 'border', $this->has_attr( $attr, 'borderHover', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'backgroundHover', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'boxShadowHover', $screen ) );
			
			
			$css->set_selector( "{$selector_wrapper} .bwf_pp_close" );
			$css->add_property( 'color', $this->has_attr( $attr, 'crossColor', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'crossBackgroundColor', $screen ) );
            $size = $this->has_attr( $attr, 'crossFont', $screen );
            if ( $size && isset( $size['size'] ) ) {
                $unit = isset( $size['unit'] ) ? $size['unit'] : 'px';
                $css->add_property( 'width', $size['size'] . $unit );
                $css->add_property( 'height', $size['size'] . $unit );
            }
			$css->add_property( 'font', $size );
            $vertical = $this->has_attr( $attr, 'closeVertical', $screen );
            if ( $vertical && isset( $vertical['width'] ) ) {
                $unit = !empty($vertical['unit']) ? $vertical['unit'] : 'px';
                $css->add_property( 'top', $vertical['width'].$unit );
            }
            $horizontal = $this->has_attr( $attr, 'closeHorizontal', $screen );
            if ( $horizontal && isset( $horizontal['width'] ) ) {
                $unit = !empty($horizontal['unit']) ? $horizontal['unit'] : 'px';
                $css->add_property( 'right', $horizontal['width'].$unit );
            }
			$css->add_property( 'padding', $this->has_attr( $attr, 'crossPadding', $screen ) );
			$css->add_property( 'border', $this->has_attr( $attr, 'crossBorder', $screen ) );
			
			$css->set_selector( "{$selector_wrapper} .bwf_pp_close:hover" );
			$css->add_property( 'color', $this->has_attr( $attr, 'crossColorHover', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'crossBackgroundColorHover', $screen ) );
			
			$css->set_selector( "{$selector_wrapper} .bwf_pp_bar_wrap .bwf_pp_bar, {$selector_wrapper} .pp-bar-text" );
			$css->add_property( 'color', $this->has_attr( $attr, 'progressColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'progressLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'progressLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'progressFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'progressText', $screen ) );

            $css->set_selector( "{$selector_wrapper} .bwf_pp_bar_wrap" );
            $css->add_property( 'background', $this->has_attr( $attr, 'progressBackgroundRemain', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf_pp_bar_wrap .bwf_pp_bar" );
			$css->add_property( 'background', $this->has_attr( $attr, 'progressBackground', $screen ) );
			if( 'desktop' === $screen ) {
				$width_attr  = $this->has_attr( $attr, 'progressWidth' );
				$height_attr = $this->has_attr( $attr, 'progressHeight' );
				$css->add_property( 'width', $width_attr !== '' && $width_attr !== NULL  ? $width_attr . '%' : '75%' );

				$css->set_selector( "{$selector_wrapper} .bwf_pp_bar_wrap" );
				$css->add_property( 'height', $height_attr ? $height_attr . 'px' : null );
			} elseif( 'tablet' === $screen ) {
				$width_attr  = $this->has_attr( $attr, 'progressWidthTablet' );
				$height_attr = $this->has_attr( $attr, 'progressHeightTablet' );
				$css->add_property( 'width', $width_attr !== '' && $width_attr !== NULL  ? $width_attr . '%' : null );

				$css->set_selector( "{$selector_wrapper} .bwf_pp_bar_wrap" );
				$css->add_property( 'height', $height_attr ? $height_attr . 'px' : null );
			} else{
				$width_attr  = $this->has_attr( $attr, 'progressWidthMobile' );
				$height_attr = $this->has_attr( $attr, 'progressHeightMobile' );
				$css->add_property( 'width', $width_attr !== '' && $width_attr !== NULL  ? $width_attr . '%' : null );

				$css->set_selector( "{$selector_wrapper} .bwf_pp_bar_wrap" );
				$css->add_property( 'height', $height_attr ? $height_attr . 'px' : null );
			}

			$css->set_selector( "{$selector_wrapper} .bwf_pp_bar_wrap" );
			$css->add_property( 'padding', $this->has_attr( $attr, 'progressBarPadding', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf_pp_bar_wrap .bwf_pp_bar:hover" );
			$css->add_property( 'background', $this->has_attr( $attr, 'progressBackgroundHover', $screen ) );
			$css->add_property( 'color', $this->has_attr( $attr, 'progressColorHover', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'progressLineHeightHover', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'progressLetterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'progressFontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'progressTextHover', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf_pp_opt_head");
			$css->add_property( 'color', $this->has_attr( $attr, 'headingColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'headingLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'headingLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'headingFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'headingText', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf_pp_opt_head:hover");
			$css->add_property( 'color', $this->has_attr( $attr, 'headingColorHover', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'headingLineHeightHover', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'headingLetterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'headingFontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'headingTextHover', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf_pp_opt_sub_head");
			$css->add_property( 'color', $this->has_attr( $attr, 'subHeadingColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'subHeadingLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'subHeadingLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'subHeadingFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'subHeadingText', $screen ) );

			$css->set_selector( "{$selector_wrapper} .bwf_pp_opt_sub_head:hover");
			$css->add_property( 'color', $this->has_attr( $attr, 'subHeadingColorHover', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'subHeadingLineHeightHover', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'subHeadingLetterSpacingHover', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'subHeadingFontHover', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'subHeadingTextHover', $screen ) );


            $css->set_selector( "{$selector_wrapper} .bwf_pp_footer" );
            $css->add_property( 'color', $this->has_attr( $attr, 'textAfterColor', $screen ) );
            $css->add_property( 'line-height', $this->has_attr( $attr, 'textAfterLineHeight', $screen ), true );
            $css->add_property( 'letter-spacing', $this->has_attr( $attr, 'textAfterLetterSpacing', $screen ), true );
            $css->add_property( 'font', $this->has_attr( $attr, 'textAfterFont', $screen ) );
            $css->add_property( 'text', $this->has_attr( $attr, 'textAfterText', $screen ) );

			if( 'desktop' !== $screen ) {
				$css->stop_media_query();
			}
		}

		$custom_css = $this->has_attr( $attr, 'bwfBlockCSS' );
		return $css->custom_css( $custom_css, '.bwfop-popup-form-container.bwf-' . $unique_id )->css_output();

	}

}
BWF_Blocks_Optin_Frontend_CSS::get_instance();