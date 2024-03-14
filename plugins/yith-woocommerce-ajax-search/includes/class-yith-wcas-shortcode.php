<?php
/**
 * Main class
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS_Shortcode' ) ) {
	/**
	 * Class definition
	 */
	class YITH_WCAS_Shortcode {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_shortcode( 'yith_woocommerce_ajax_search', array( $this, 'ajax_search_shortcode' ) );
		}


		/**
		 * Initialize the legacy shortcode
		 *
		 * @param array $args Arguments.
		 *
		 * @return string
		 */
		public function print_legacy_shortcode( $args ) {

			wp_enqueue_script( 'yith_autocomplete' );
			wp_enqueue_script( 'yith_wcas_jquery-autocomplete' );
			wp_enqueue_script( 'yith_wcas_frontend' );
			wp_enqueue_style( 'yith_wcas_frontend' );
			$template_default = get_option( 'yith_wcas_search_default_template', '' );
			$args             = shortcode_atts(
				array(
					'template' => $template_default,
					'class'    => '',
				),
				$args
			);
			$template         = ! empty( $args['template'] ) ? '-wide' : '';
			unset( $args['template'] );

			return $this->get_legacy_template( $template, $args );

		}

		/**
		 * Return the legacy template
		 *
		 * @param string $template The template type.
		 * @param array  $args The args.
		 *
		 * @return false|string
		 */
		public function get_legacy_template( $template, $args ) {
			ob_start();
			$wc_get_template = function_exists( 'wc_get_template' ) ? 'wc_get_template' : 'woocommerce_get_template';
			$wc_get_template( 'yith-woocommerce-ajax-search.php', $args, '', YITH_WCAS_DIR . 'templates/' );

			return ob_get_clean();
		}

		/**
		 * Ajax form shortcode
		 *
		 * @param array $args Array of arguments.
		 *
		 * @return string
		 */
		public function ajax_search_shortcode( $args = array() ) {
			if ( ! yith_wcas_is_fresh_block_installation() && ! yith_wcas_user_switch_to_block() ) {
				return $this->print_legacy_shortcode( $args );
			} else {
				$args = shortcode_atts(
					array(
						'preset' => 'default',
					),
					$args
				);

				$preset     = $args['preset'];
				$shortcodes = ywcas()->settings->get_shortcodes_list();
				$shortcode  = $shortcodes[ $preset ] ?? $shortcodes['default'];

				$block = $this->get_block_code( $shortcode['options'] );

				return do_blocks( $block );
			}
		}

		/**
		 * Return the block code based on options.
		 *
		 * @param array $options Options to check.
		 *
		 * @return string
		 */
		protected function get_block_code( $options ) {

			$type = $options['general']['type'] ?? 'classic';

			$method = "get_{$type}_block_code";

			if( method_exists( $this, $method)){
				$block = $this->$method( $options );
			}else{
				$block = $this->get_classic_block_code( $options );
			}

			return $block;
		}

		/**
		 * Return the block classic code
		 *
		 * @param array $options Options
		 *
		 * @return string
		 * @since 2.1.0
		 */
		public function get_classic_block_code( $options ) {

			$block_options = array(
				'size'      => $options['general']['style'],
				'className' => $options['general']['custom_class'],
			);

			$block = '<!-- wp:yith/search-block ' . wp_json_encode( $block_options ) . '  -->';
			$block .= '<div class="wp-block-yith-search-block alignwide ' . esc_attr( $block_options['className'] ) . '">';

			// Input.
			$block .= $this->get_input_block_code_by_options( $options );

			// Filled block.
			$block .= $this->get_filled_block_code_by_options( $options );


			$block .= '</div><!-- /wp:yith/search-block -->';

			return $block;
		}

		/**
		 * Return the string to show the input block
		 *
		 * @param array $options Options.
		 *
		 * @return string
		 */
		protected function get_input_block_code_by_options( $options ) {
			$input_options        = $options['search-input'];
			$submit_options       = $options['submit-button'];
			$border_size          = ! isset( $input_options['border_size'] ) ? '1px' : $input_options['border_size'] . 'px';
			$border_radius        = ! isset( $input_options['border_radius'] ) ? '20px' : $input_options['border_radius'] . 'px';
			$border_radius_button = ! isset( $submit_options['border-radius'] ) ? '20px' : $submit_options['border-radius'] . 'px';
			$block_options        = array(
				'placeholder'             => $input_options['placeholder'],
				'placeholderTextColor'    => $input_options['colors'] ['placeholder'],
				'inputTextColor'          => $input_options['colors'] ['textcolor'],
				'inputBgColor'            => $input_options['colors'] ['background'],
				'inputBgFocusColor'       => $input_options['colors'] ['background-focus'],
				'inputBorderColor'        => $input_options['colors'] ['border'],
				'inputBorderFocusColor'   => $input_options['colors'] ['border-focus'],
				'inputBorderSize'         => array(
					'topLeft'     => $border_size,
					'topRight'    => $border_size,
					'bottomLeft'  => $border_size,
					'bottomRight' => $border_size,
				),
				'inputBorderRadius'       => array(
					'topLeft'     => $border_radius,
					'topRight'    => $border_radius,
					'bottomLeft'  => $border_radius,
					'bottomRight' => $border_radius,
				),
				'submitStyle'             => $submit_options['search-style'] === 'both' ? 'iconText' : $submit_options['search-style'],
				'submitContentColor'      => $submit_options['icon-colors']['icon'],
				'submitContentHoverColor' => $submit_options['icon-colors']['icon-hover'],
				'submitBgColor'           => $submit_options['icon-colors']['background'],
				'submitBgHoverColor'      => $submit_options['icon-colors']['background-hover'],
				'submitBorderColor'       => $submit_options['icon-colors']['border'],
				'submitBorderHoverColor'  => $submit_options['icon-colors']['border-hover'],
				'buttonLabel'             => $submit_options['button-label'] ?? '',
				'buttonBorderRadius'      => array(
					'topLeft'     => $border_radius_button,
					'topRight'    => $border_radius_button,
					'bottomLeft'  => $border_radius_button,
					'bottomRight' => $border_radius_button,
				),
				'iconType'                => 'icon-' . $submit_options['icon-position']

			);

			$block = '<!-- wp:yith/input-block ' . wp_json_encode( $block_options ) . ' -->';
			$block .= '<div class="wp-block-yith-input-block"></div>';
			$block .= '<!-- /wp:yith/input-block -->';

			return $block;
		}

		/**
		 * Return the string to show the filled block
		 *
		 * @param array $options Options.
		 *
		 * @return string
		 */
		protected function get_filled_block_code_by_options( $options ) {
			$block = '<!-- wp:yith/filled-block -->';
			$block .= '<div class="wp-block-yith-filled-block">';
			$block .= self::get_product_results_block_code_by_options( $options );
			$block .= '</div><!-- /wp:yith/filled-block -->';

			return $block;
		}


		/**
		 * Get the string to show the product results block
		 *
		 * @param array $options Options.
		 *
		 * @return string
		 */
		protected function get_product_results_block_code_by_options( $options ) {

			$options       = $options['search-results'];
			$block_options = array(
				'showName'             => in_array( 'name', $options['info-to-show'], true ),
				'showImage'            => in_array( 'image', $options['info-to-show'], true ),
				'showPrice'            => in_array( 'price', $options['info-to-show'], true ),
				'showCategories'       => in_array( 'categories', $options['info-to-show'], true ),
				'showStock'            => in_array( 'stock', $options['info-to-show'], true ),
				'showSKU'              => in_array( 'sku', $options['info-to-show'], true ),
				'showSummary'          => in_array( 'excerpt', $options['info-to-show'], true ),
				'showAddToCart'        => in_array( 'add-to-cart', $options['info-to-show'], true ),
				'maxResultsToShow'     => $options['max-results'],
				'imagePosition'        => $options['image-position'] ?? 'left',
				'layout'               => $options['results-layout'],
				'imageSize'            => $options['image-size'],
				'limitSummary'         => false,
				'summaryMaxWord'       => $options['max-summary'] ?? 10,
				'productNameColor'     => $options['name-color'],
				'priceLabel'           => $options['price-label'] ?? '',
				'noResults'            => $options['no-results-label'],
				'showViewAll'          => isset( $options['show-view-all'] ),
				'showViewAllText'      => $options['view-all-label'],
				'showSaleBadge'        => isset( $options['badges-to-show'] ) && in_array( 'sale', $options['badges-to-show'], true ),
				'showOutOfStockBadge'  => isset( $options['badges-to-show'] ) && in_array( 'out-of-stock', $options['badges-to-show'], true ),
				'showFeaturedBadge'    => isset( $options['badges-to-show'] ) && in_array( 'featured', $options['badges-to-show'], true ),
				'hideFeaturedIfOnSale' => isset( $options['show-hide-featured-if-on-sale'] ) && 'yes' === $options['show-hide-featured-if-on-sale'],
			);

			$block = '<!-- wp:yith/product-results-block ' . wp_json_encode( $block_options ) . ' -->';
			$block .= '<div class="wp-block-yith-product-results-block"></div>';
			$block .= '<!-- /wp:yith/product-results-block -->';

			return $block;
		}

	}

}
