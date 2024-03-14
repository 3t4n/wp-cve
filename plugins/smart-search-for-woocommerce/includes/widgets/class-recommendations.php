<?php
/**
 * Searchanise recommendations
 *
 * @package Searchanise/Recommendations
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Searchanise recommendation class
 */
class Recommendations {

	const BLOCK_TYPE_HOME     = 'home page';
	const BLOCK_TYPE_SEARCH   = 'search results';
	const BLOCK_TYPE_CATEGORY = 'category';
	const BLOCK_TYPE_PRODUCT  = 'product';
	const BLOCK_TYPE_CART     = 'cart';

	const WOOCOMMECE_CLASS = 'woocommerce';
	const ALIGN_WIDE_CLASS = 'alignwide';

	/**
	 * Lang code
	 *
	 * @var string
	 */
	protected $lang_code;

	/**
	 * Content.
	 *
	 * @var string
	 */
	private $wc_content = '';

	/**
	 * Recommendation constructor
	 *
	 * @param string $lang_code Lang code.
	 */
	public function __construct( $lang_code ) {
		$this->lang_code = $lang_code;

		$this->init();
	}

	/**
	 * Init
	 */
	public function init() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		add_filter( 'the_content', array( $this, 'add_word_press_content' ), 10 );
		add_filter( 'woocommerce_after_shop_loop', array( $this, 'add_woocommerce_content' ), 10 );
		add_filter( 'woocommerce_after_single_product_summary', array( $this, 'add_after_product_content' ), 10 );
	}

	/**
	 * Returns global woocommerce class
	 *
	 * @return string
	 */
	private function get_woocommerce_class() {
		return self::WOOCOMMECE_CLASS;
	}

	/**
	 * Returns wide align theme css class.
	 *
	 * @return string
	 */
	private function get_align_wide_class() {
		return get_template() != 'twentythirteen' ? self::ALIGN_WIDE_CLASS : '';
	}

	/**
	 * Adds additional content after product content
	 */
	public function add_after_product_content() {
		if ( is_product() ) {
			global $product;
			$this->wc_content = $this->add_to_content( $this->wc_content, $this->get_block_content( self::BLOCK_TYPE_PRODUCT, array( $this->get_woocommerce_class(), $this->get_align_wide_class() ), (array) $product->get_id() ) );
		}

		if ( ! empty( $this->wc_content ) ) {
			echo wp_kses(
				$this->wc_content,
				array(
					'div' => array(
						'class' => array(),
						'data-page-type' => array(),
						'data-product-ids' => array(),
					),
				)
			);
		}
	}

	/**
	 * Adds additional content to Woocommerce pages
	 */
	public function add_woocommerce_content() {
		// Woocommerce category page.
		if ( is_product_category() ) {
			$this->wc_content = $this->add_to_content( $this->wc_content, $this->get_block_content( self::BLOCK_TYPE_CATEGORY, array( $this->get_woocommerce_class(), $this->get_align_wide_class() ) ) );
		}

		// Woocommerce default search page.
		if ( is_search() ) {
			$this->wc_content = $this->add_to_content( $this->wc_content, $this->get_block_content( self::BLOCK_TYPE_SEARCH, array( $this->get_woocommerce_class(), 'is-style-wide' ) ) );
		}

		// Woocommerce home page.
		if ( is_shop() && ! is_search() ) {
			$this->wc_content = $this->add_to_content( $this->wc_content, $this->get_block_content( self::BLOCK_TYPE_HOME, array( $this->get_woocommerce_class(), $this->get_align_wide_class() ) ) );
		}

		if ( ! empty( $this->wc_content ) ) {
			echo wp_kses(
				$this->wc_content,
				array(
					'div' => array(
						'class' => array(),
						'data-page-type' => array(),
					),
				)
			);
		}
	}

	/**
	 * Filters the post content.
	 *
	 * @since 0.71
	 *
	 * @param string $content Content of the current post.
	 */
	public function add_word_press_content( $content ) {
		// Woocommerce home page.
		if ( is_front_page() ) {
			$content = $this->add_to_content( $content, $this->get_block_content( self::BLOCK_TYPE_HOME, array( $this->get_woocommerce_class() ) ) );
		}

		// Searchanise search results page.
		if ( is_page( Api::get_instance()->get_search_results_page() ) ) {
			$content = $this->add_to_content( $content, $this->get_block_content( self::BLOCK_TYPE_SEARCH, array( 'is-style-wide' ) ) );
		}

		// Woocommerce cart page.
		if ( is_cart() ) {
			$cart_product_ids = array();
			foreach ( WC()->cart->get_cart() as $cart_item ) {
				$cart_product_ids[] = $cart_item['product_id'];
			}
			$content = $this->add_to_content( $content, $this->get_block_content( self::BLOCK_TYPE_CART, array( $this->get_woocommerce_class(), 'woocommerce-cart' ), $cart_product_ids ) );
		}

		return $content;
	}

	/**
	 * Adds recommendation block content to WP page
	 *
	 * @param string $main_content  WP page content.
	 * @param string $block_content Recommendation block content.
	 *
	 * @return string
	 */
	private function add_to_content( $main_content, $block_content ) {
		if ( preg_match( '/<!-- \/wp:cover -->/mu', $main_content ) ) {
			$result = preg_replace( '/<!-- \/wp:cover -->/mu', '<!-- /wp:cover -->' . $block_content, $main_content );
		} else {
			$result = $main_content . $block_content;
		}

		return $result;
	}

	/**
	 * Generates block html content
	 *
	 * @param string $page_type   Type of recommendation block.
	 * @param array  $classes     List of css classes for block.
	 * @param array  $product_ids List of related product ids.
	 *
	 * @return string
	 */
	private function get_block_content( $page_type, array $classes = array(), array $product_ids = array() ) {
		$classes[] = get_template();
		$product_ids_str = ! empty( $product_ids ) ? ( 'data-product-ids = "' . implode( ',', $product_ids ) . '"' ) : '';
		$classes_str = implode( ' ', $classes );

		return "<div class=\"snize-recommendation-wrapper {$classes_str}\" data-page-type = \"{$page_type}\" {$product_ids_str}></div>";
	}
}
