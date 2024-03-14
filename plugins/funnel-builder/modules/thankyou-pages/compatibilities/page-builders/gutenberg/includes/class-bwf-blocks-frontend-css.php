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

  class WFTY_Blocks_Frontend_CSS {
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
			if ( ! is_object( $post ) ) {
				return;
			}
			if ( ! class_exists( 'WFFN_Thank_You_WC_Pages' ) || WFFN_Thank_You_WC_Pages::get_post_type_slug() !== $post->post_type ) {
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
	 * @param array $css the css for each rendered block.
	 * @param string $style_id the unique id for the rendered style.
	 * @param bool $in_content the bool for whether or not it should run in content.
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
			$this->compute_bwf_blocks( $blocks );


		}
	}

	public function compute_bwf_blocks( $blocks ) {
		foreach ( $blocks as $block ) {
			$block = apply_filters( 'bwf_blocks_frontend_build_css', $block );
			if ( ! is_object( $block ) && is_array( $block ) && isset( $block['blockName'] ) ) {

				if ( 'bwfblocks/customer-details' === $block['blockName'] ) {
					if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
						$blockattr = $block['attrs'];
						if ( isset( $blockattr['uniqueID'] ) ) {
							$unique_id = $blockattr['uniqueID'];
							$style_id  = 'bwfblocks-' . esc_attr( $unique_id );
							if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
								$css = $this->render_customer_details_css_head( $blockattr, $unique_id );
								if ( ! empty( $css ) ) {
									$this->render_inline_css( $css, $style_id );
								}
							}
						}
					}
				}

				if ( 'bwfblocks/order-details' === $block['blockName'] ) {
					if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
						$blockattr = $block['attrs'];
						if ( isset( $blockattr['uniqueID'] ) ) {
							$unique_id = $blockattr['uniqueID'];
							$style_id  = 'bwfblocks-' . esc_attr( $unique_id );
							if ( ! wp_style_is( $style_id, 'enqueued' ) ) {
								$css = $this->render_order_details_css_head( $blockattr, $unique_id );
								if ( ! empty( $css ) ) {
									$this->render_inline_css( $css, $style_id );
								}
							}
						}
					}
				}

				if ( 'core/block' === $block['blockName'] ) {
					if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
						$blockattr = $block['attrs'];
						if ( isset( $blockattr['ref'] ) ) {
							$reusable_block = get_post( $blockattr['ref'] );
							if ( $reusable_block && 'wp_block' === $reusable_block->post_type ) {
								$reuse_data_block = $this->bwf_parse_blocks( $reusable_block->post_content );
								$this->compute_bwf_blocks( $reuse_data_block );

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
	 *
	 * @return void
	 */
	public function has_attr( $attr, $indexkey, $screen = '', $default_val = null ) {
		$value = null;
		if ( empty( $screen ) ) {
			if ( isset( $attr[ $indexkey ] ) ) {
				$value = $attr[ $indexkey ];
			}
		} else {
			if ( isset( $attr[ $indexkey ] ) && isset( $attr[ $indexkey ][ $screen ] ) ) {
				$value = $attr[ $indexkey ][ $screen ];
			}
		}

		return ! is_null( $default_val ) && ! empty( $value ) ? $default_val : $value;
	}

	public function render_customer_details_css_head( $attr, $unique_id ) {

		$css                   = new BWF_Blocks_CSS();
		$media_query           = array();
		$media_query['mobile'] = apply_filters( 'bwf_blocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet'] = apply_filters( 'bwf_blocks_tablet_media_query', '(max-width: 1024px)' );

		$selector_wrapper = '.wfty-cust-details-block.wfty-' . $unique_id . ' .wfty_wrap';
		$screens = array( 'desktop', 'tablet', 'mobile' );
		$defaults = [
			'headingColor' => [
				'desktop'  => '#333333'
			],
			'subHeadingColor'  => [
				'desktop'    => '#333333'
			],
			'contentColor'   => [
				'desktop'   =>  '#565656'
			]
		];

		$attr = wp_parse_args( $attr, $defaults );

		foreach ($screens as $screen) {
			if( 'desktop' !== $screen ) {
				$css->start_media_query( $media_query[ $screen ] );
			}

			$css->set_selector( $selector_wrapper );
			$css->add_property( 'background', $this->has_attr( $attr, 'background', $screen ) );
			$css->add_property( 'margin', $this->has_attr( $attr, 'margin', $screen ) );
			$css->add_property( 'padding', $this->has_attr( $attr, 'padding', $screen ) );
			$css->add_property( 'border', $this->has_attr( $attr, 'border', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'boxShadow', $screen ) );


			$css->set_selector( $selector_wrapper . ' .wfty-customer-info-heading' );
			$css->add_property( 'color', $this->has_attr( $attr, 'headingColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'headingLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'headingLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'headingFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'headingText', $screen ) );

			$css->set_selector( $selector_wrapper . ' .wfty_content .wfty_text_bold strong' );
			$css->add_property( 'color', $this->has_attr( $attr, 'subHeadingColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'subHeadingLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'subHeadingLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'subHeadingFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'subHeadingText', $screen ) );


			$css->set_selector( $selector_wrapper . ' .wfty_content .wfty_view' );
			$css->add_property( 'color', $this->has_attr( $attr, 'contentColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'contentLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'contentLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'contentFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'contentText', $screen ) );

			if ( 'desktop' !== $screen ) {
				$css->stop_media_query();
			}
		}

		return $css->css_output();
	}

	public function render_order_details_css_head( $attr, $unique_id ) {

		$css                   = new BWF_Blocks_CSS();
		$media_query           = array();
		$media_query['mobile'] = apply_filters( 'bwf_blocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet'] = apply_filters( 'bwf_blocks_tablet_media_query', '(max-width: 1024px)' );

		$selector_wrapper = '.wfty-order-details-block.wfty-' . $unique_id . ' .wfty_wrap';
		$screens = array( 'desktop', 'tablet', 'mobile' );

		$defaults = [
			'headingColor' => [
				'desktop'  => '#000000'
			],
			'productColor'  => [
				'desktop'    => '#565656'
			],
			'subTotalColor'   => [
				'desktop'   =>  '#565656'
			],
			'totalColor'   => [
				'desktop'   =>  '#565656'
			],
			'variationColor'   => [
				'desktop'   =>  '#000000'
			],
			'dividerColor'   => [
				'desktop'   =>  '#dddddd'
			],
			'subscriptionColor'   => [
				'desktop'   =>  '#565656'
			],
			'subscriptionBtnColor'   => [
				'desktop'   =>  '#ffffff'
			],
			'subscriptionBtnBackground'   => [
				'desktop'   =>  '#70dc1d'
			],
			'downloadColor'   => [
				'desktop'   =>  '#565656'
			],
			'downloadBtnColor' => [
				'desktop'  => '#ffffff'
			],
			'downloadBtnBackground'  => [
				'desktop'   => '#565656'
			]
		];

		$attr = wp_parse_args( $attr, $defaults );

		foreach ($screens as $screen) {
			if( 'desktop' !== $screen ) {
				$css->start_media_query( $media_query[ $screen ] );
			}

			$css->set_selector( $selector_wrapper );
			$css->add_property( 'background', $this->has_attr( $attr, 'background', $screen ) );
			$css->add_property( 'margin', $this->has_attr( $attr, 'margin', $screen ) );
			$css->add_property( 'padding', $this->has_attr( $attr, 'padding', $screen ) );
			$css->add_property( 'border', $this->has_attr( $attr, 'border', $screen ) );
			$css->add_property( 'box-shadow', $this->has_attr( $attr, 'boxShadow', $screen ) );


			$css->set_selector( $selector_wrapper . ' .wfty_title' );
			$css->add_property( 'color', $this->has_attr( $attr, 'headingColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'headingLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'headingLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'headingFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'headingTextStyle', $screen ) );

			$css->set_selector( $selector_wrapper . ' .wfty_order_details .wfty_pro_list_cont .wfty_pro_list .wfty_t,' . $selector_wrapper . " .wfty_order_details .wfty_pro_list_cont .wfty_pro_list .woocommerce-Price-amount.amount, " . $selector_wrapper . " .wfty_order_details .wfty_pro_list_cont .wfty_pro_list .woocommerce-Price-amount.amount span" );
			$css->add_property( 'color', $this->has_attr( $attr, 'productColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'productLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'productLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'productFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'productTextStyle', $screen ) );

			$css->set_selector( $selector_wrapper . ' .wfty_order_details .wfty_pro_list_cont table tr:not(:last-child) *' );
			$css->add_property( 'color', $this->has_attr( $attr, 'subTotalColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'subTotalLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'subTotalLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'subTotalFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'subTotalTextStyle', $screen ) );

			$css->set_selector( $selector_wrapper . ' .wfty_order_details .wfty_pro_list_cont table tr:last-child *' );
			$css->add_property( 'color', $this->has_attr( $attr, 'totalColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'totalLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'totalLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'totalFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'totalTextStyle', $screen ) );

			$css->set_selector( $selector_wrapper . ' .wfty_order_details .wfty_pro_list_cont .wfty_pro_list .wfty_info *' );
			$css->add_property( 'color', $this->has_attr( $attr, 'variationColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'variationLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'variationLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'variationFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'variationText', $screen ) );

			$css->set_selector( $selector_wrapper . ' .wfty_order_details table tfoot tr:last-child th, ' . $selector_wrapper . ' .wfty_order_details table tfoot tr:last-child td, ' . $selector_wrapper . ' .wfty_order_details table, ' . $selector_wrapper . ' .wfty_order_details .wfty_pro_list' );
			$css->add_property( 'border-top-color', $this->has_attr( $attr, 'dividerColor', $screen ) );

			$css->set_selector( "{$selector_wrapper} .wfty_subscription table tbody td:not(:last-child), {$selector_wrapper}  .wfty_subscription table tbody td:not(:last-child) *, {$selector_wrapper}  .wfty_subscription table tr th *,{$selector_wrapper} .wfty_subscription table tr tbody td:not(:last-child), {$selector_wrapper}  .wfty_subscription table tr tbody td:not(:last-child) *" );
			$css->add_property( 'color', $this->has_attr( $attr, 'subscriptionColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'subscriptionLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'subscriptionLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'subscriptionFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'subscriptionTextStyle', $screen ) );

			$css->set_selector( $selector_wrapper . ' .wfty_subscription table tr td.subscription-actions a' );
			$css->add_property( 'color', $this->has_attr( $attr, 'subscriptionBtnColor', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'subscriptionBtnBackground', $screen ) );
			$css->add_property( 'font', $this->has_attr( $attr, 'subscriptionFont', $screen ) );

			$css->set_selector( $selector_wrapper . ' .wfty_subscription table tr td.subscription-actions:hover a' );
			$css->add_property( 'color', $this->has_attr( $attr, 'subscriptionBtnColorHover', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'subscriptionBtnBackgroundHover', $screen ) );

			$css->set_selector( "{$selector_wrapper} table.wfty_order_downloads tr td:not(:last-child),  {$selector_wrapper} table.wfty_order_downloads thead * " );
			$css->add_property( 'color', $this->has_attr( $attr, 'downloadColor', $screen ) );
			$css->add_property( 'line-height', $this->has_attr( $attr, 'downloadLineHeight', $screen ), true );
			$css->add_property( 'letter-spacing', $this->has_attr( $attr, 'downloadLetterSpacing', $screen ), true );
			$css->add_property( 'font', $this->has_attr( $attr, 'downloadFont', $screen ) );
			$css->add_property( 'text', $this->has_attr( $attr, 'downloadTextStyle', $screen ) );

			$css->set_selector( $selector_wrapper . ' table.wfty_order_downloads tr td.download-file a' );
			$css->add_property( 'font', $this->has_attr( $attr, 'downloadFont', $screen ) );
			$css->add_property( 'color', $this->has_attr( $attr, 'downloadBtnColor', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'downloadBtnBackground', $screen ) );

			$css->set_selector( $selector_wrapper . ' table.wfty_order_downloads tr td.download-file:hover a' );
			$css->add_property( 'color', $this->has_attr( $attr, 'downloadBtnColorHover', $screen ) );
			$css->add_property( 'background', $this->has_attr( $attr, 'downloadBtnBackgroundHover', $screen ) );

			if ( 'desktop' !== $screen ) {
				$css->stop_media_query();
			}
		}

		return $css->css_output();
	}


}

WFTY_Blocks_Frontend_CSS::get_instance();