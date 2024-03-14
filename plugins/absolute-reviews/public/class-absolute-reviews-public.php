<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ABR
 * @subpackage ABR/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    ABR
 * @subpackage ABR/public
 */
class ABR_Public {

	/**
	 * The ID of this plugin.

	 * @access   private
	 * @var      string    $abr    The ID of this plugin.
	 */
	private $abr;

	/**
	 * The version of this plugin.

	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $abr     The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $abr, $version ) {

		$this->abr     = $abr;
		$this->version = $version;
	}

	/**
	 * Filter output review in post content.
	 *
	 * @param string $content The content of post.
	 */
	public function the_content( $content ) {

		// Check enabled.
		if ( ! apply_filters( 'abr_review_content_enabled', true ) ) {
			return $content;
		}

		// Check AMP endpoint.
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			return $content;
		}

		if ( is_singular() && ( is_single( get_the_ID() ) || is_page( get_the_ID() ) ) ) {
			ob_start();
				abr_the_review();
			$review = ob_get_clean();

			// Concatenation.
			$content = $content . $review;
		}

		return $content;
	}

	/**
	 * Register Posts Templates
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @param array $templates List of Templates.
	 */
	public function posts_templates( $templates = array() ) {
		$templates = array(
			'reviews-1' => array(
				'name' => esc_html__( 'Reviews 1', 'absolute-reviews' ),
				'func' => 'abr_reviews_posts_template',
			),
			'reviews-2' => array(
				'name' => esc_html__( 'Reviews 2', 'absolute-reviews' ),
				'func' => 'abr_reviews_posts_template',
			),
			'reviews-3' => array(
				'name' => esc_html__( 'Reviews 3', 'absolute-reviews' ),
				'func' => 'abr_reviews_posts_template',
			),
			'reviews-4' => array(
				'name' => esc_html__( 'Reviews 4', 'absolute-reviews' ),
				'func' => 'abr_reviews_posts_template',
			),
			'reviews-5' => array(
				'name' => esc_html__( 'Reviews 5', 'absolute-reviews' ),
				'func' => 'abr_reviews_posts_template',
			),
			'reviews-6' => array(
				'name' => esc_html__( 'Reviews 6', 'absolute-reviews' ),
				'func' => 'abr_reviews_posts_template',
			),
			'reviews-7' => array(
				'name' => esc_html__( 'Reviews 7', 'absolute-reviews' ),
				'func' => 'abr_reviews_posts_template',
			),
			'reviews-8' => array(
				'name' => esc_html__( 'Reviews 8', 'absolute-reviews' ),
				'func' => 'abr_reviews_posts_template',
			),
		);

		return $templates;
	}

	/**
	 * Fire the wp_head action.
	 */
	public function wp_head() {
		?>
		<link rel="preload" href="<?php echo esc_url( ABR_URL . 'fonts/absolute-reviews-icons.woff' ); ?>" as="font" type="font/woff" crossorigin>
		<?php
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 */
	public function wp_enqueue_scripts() {

		// Styles.
		wp_enqueue_style( $this->abr, abr_style( plugin_dir_url( __FILE__ ) . 'css/absolute-reviews-public.css' ), array(), $this->version, 'all' );

		// Add RTL support.
		wp_style_add_data( $this->abr, 'rtl', 'replace' );
	}

}
