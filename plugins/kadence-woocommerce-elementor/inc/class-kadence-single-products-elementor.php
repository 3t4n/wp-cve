<?php
/**
 * Class Kadence_Single_Products_Elementor
 *
 * @package Kadence Woocommerce Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Kadence_Single_Products_Elementor
 *
 * @category class.
 */
class Kadence_Single_Products_Elementor {

	/**
	 * Is woo ele enabled static return
	 *
	 * @var null
	 */
	public static $woo_ele_enabled = array();

	/**
	 * Is woo ele template static return
	 *
	 * @var null
	 */
	public static $woo_ele_template = array();

	/**
	 * The woo ele template ID static return
	 *
	 * @var null
	 */
	public static $woo_ele_id = array();

	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

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
	 * Constructor
	 */
	public function __construct() {
		// Replace Woo template.
		add_filter( 'wc_get_template_part', array( $this, 'single_product_page_template' ), 50, 3 );
		// Replace main template.
		add_filter( 'template_include', array( $this, 'single_product_ele_template' ), 102 );
		// Add Elementor to product.
		add_action( 'kadence_woocommerce_product_builder', array( $this, 'get_product_content' ) );
		// Add schema to product.
		add_action( 'kadence_woocommerce_product_builder', array( $this, 'product_schema' ), 20 );
		// Scripts and styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		// Body class.
		add_filter( 'body_class', array( $this, 'body_class' ) );
		// Add meta boxes.
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		// Save Meta boxes.
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
		// the_content loop check..
		add_filter( 'the_content', array( $this, 'do_action_on_the_content' ), 80 );

	}

	/**
	 * Checks if Woo Ele Builder is running for the product.
	 */
	public static function kadence_woo_ele_product_builder_enabled() {
		$status = false;
		if ( is_product() ) {
			global $post;
			if ( ! isset( self::$woo_ele_enabled[ $post->ID ] ) ) {
				$single_product_default = Kadence_Woocommerce_Elementor::get_default_single_setting();
				$custom_template        = get_post_meta( $post->ID, '_kt_woo_ele_product_template', true );
				if ( ( isset( $custom_template ) && ! empty( $custom_template ) && 'default' !== $custom_template ) || ( ! empty( $single_product_default ) && 'default' !== $single_product_default ) ) {
					$status                             = true;
					self::$woo_ele_enabled[ $post->ID ] = true;
				} else {
					self::$woo_ele_enabled[ $post->ID ] = false;
				}
			} else {
				$status = self::$woo_ele_enabled[ $post->ID ];
			}
		}
		return apply_filters( 'kadence_woo_ele_product_builder_enabled', $status );
	}

	/**
	 * Checks if Woo Ele Builder using a template or the products editor
	 */
	public static function kadence_woo_ele_product_template_enabled() {
		$status = false;
		if ( is_product() ) {
			global $post;
			if ( ! isset( self::$woo_ele_template[ $post->ID ] ) ) {
				$single_product_default = Kadence_Woocommerce_Elementor::get_default_single_setting();
				$custom_template        = get_post_meta( $post->ID, '_kt_woo_ele_product_template', true );
				if ( isset( $custom_template ) && ! empty( $custom_template ) && 'default' !== $custom_template && 'elementor' !== $custom_template ) {
					$status                              = true;
					self::$woo_ele_template[ $post->ID ] = true;
				} elseif ( isset( $custom_template ) && ! empty( $custom_template ) && 'elementor' === $custom_template ) {
					$status                              = false;
					self::$woo_ele_template[ $post->ID ] = false;
				} elseif ( ! empty( $single_product_default ) && 'default' !== $single_product_default ) {
					$status                              = true;
					self::$woo_ele_template[ $post->ID ] = true;
				}
			} else {
				$status = self::$woo_ele_template[ $post->ID ];
			}
		}
		return apply_filters( 'kadence_woo_ele_product_template_enabled', $status );
	}
	/**
	 * Gets the template id for the builder
	 */
	public static function get_kadence_woo_ele_product_builder_id() {
		global $post;
		if ( ! isset( self::$woo_ele_id[ $post->ID ] ) ) {
			$template = get_post_meta( $post->ID, '_kt_woo_ele_product_template', true );
			if ( isset( $template ) && ! empty( $template ) && 'default' !== $template && 'elementor' !== $template ) {
				$template_id = $template;
			} elseif ( isset( $template ) && ! empty( $template ) && 'default' !== $template && 'elementor' === $template ) {
				$template_id = $post->ID;
			} else {
				$template_id = Kadence_Woocommerce_Elementor::get_default_single_setting();
			}
			self::$woo_ele_id[ $post->ID ] = $template_id;
		} else {
			$template_id = self::$woo_ele_id[ $post->ID ];
		}

		return apply_filters( 'kadence_product_elementor_template', $template_id );
	}
	/**
	 * Load The product schema.
	 */
	public function product_schema() {
		WC()->structured_data->generate_product_data();
	}
	/**
	 * Function to keep track of the_content calls.
	 *
	 * @param string $content has product content.
	 */
	public function do_action_on_the_content( $content ) {
		if ( in_the_loop() ) {
			do_action( 'kadence_woo_ele_content_ran' );
		}
		return $content;
	}
	/**
	 * Enqueue styles and scripts.
	 */
	public function enqueue_scripts() {
		if ( is_singular( 'product' ) || is_singular( 'ele-product-template' ) ) {
			wp_enqueue_style( 'kt-woo-ele-style', KT_WOOELE_URL . 'assets/css/kadence-woocommerce-elementor.css', array(), KT_WOOELE_VERSION );

			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {

				if ( class_exists( '\Elementor\Plugin' ) ) {
					$elementor = \Elementor\Plugin::instance();
					$elementor->frontend->enqueue_styles();
				}

				if ( class_exists( '\ElementorPro\Plugin' ) ) {
					$elementor_pro = \ElementorPro\Plugin::instance();
					$elementor_pro->enqueue_styles();
				}

				if ( Kadence_Single_Products_Elementor::kadence_woo_ele_product_template_enabled() ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( Kadence_Single_Products_Elementor::get_kadence_woo_ele_product_builder_id() );
					$css_file->enqueue();
				}
			}
		}
	}

	/**
	 * Adds classes to the body tag conditionally.
	 *
	 * @param  Array $classes array with class names for the body tag.
	 */
	public function body_class( $classes ) {
		if ( Kadence_Single_Products_Elementor::kadence_woo_ele_product_builder_enabled() ) {
			$classes[] = 'kwe-woo-builder-product';
		}
		return $classes;
	}
	/**
	 * Changes template if elementor take over
	 *
	 * @param string $template path to template.
	 * @param string $slug the template slug.
	 * @param string $name the template name.
	 */
	public function single_product_page_template( $template, $slug, $name ) {

		if ( 'content' === $slug && 'single-product' === $name ) {
			if ( Kadence_Single_Products_Elementor::kadence_woo_ele_product_builder_enabled() ) {
				$template = KT_WOOELE_PATH . 'templates/product-elementor.php';
			}
		}

		return $template;
	}
	/**
	 * Changes template if elementor take over
	 *
	 * @param string $template path to template.
	 */
	public function single_product_ele_template( $template ) {
		if ( is_embed() ) {
			return $template;
		}

		if ( is_singular( 'product' ) ) {
			// Check if this is an elementor template.
			if ( Kadence_Single_Products_Elementor::kadence_woo_ele_product_builder_enabled() ) {
				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
				// Get the template slug for the elementor template we are using.
				$t_slug = get_page_template_slug( Kadence_Single_Products_Elementor::get_kadence_woo_ele_product_builder_id() );
				if ( 'elementor_header_footer' === $t_slug ) {
					$template = KT_WOOELE_PATH . 'templates/product-elementor-fullwidth.php';
				} elseif ( 'elementor_canvas' === $t_slug ) {
					$template = KT_WOOELE_PATH . 'templates/product-elementor-canvas.php';
				}
			}
		}
		return $template;
	}
	/**
	 * Prints the product content.
	 *
	 * @param object $post the post object.
	 */
	public static function get_product_content( $post ) {
		if ( Kadence_Single_Products_Elementor::kadence_woo_ele_product_template_enabled() ) {
			$template_id = Kadence_Single_Products_Elementor::get_kadence_woo_ele_product_builder_id();
			echo Kadence_Woocommerce_Elementor::$elementor_instance->frontend->get_builder_content_for_display( $template_id );
		} else {
			the_content();
		}
	}
	/**
	 * Adds the meta box.
	 */
	public function add_metabox() {
		add_meta_box(
			'kt-woo-ele-product-template-meta',
			__( 'Product Template', 'kadence-woocommerce-elementor' ),
			array( $this, 'render_metabox' ),
			'product',
			'side',
			'default'
		);
	}
	/**
	 * Renders the meta box.
	 *
	 * @param object $post the post object.
	 */
	public function render_metabox( $post ) {
		// Add nonce for security and authentication.
		wp_nonce_field( 'kadence_woo_ele_nonce_action', 'kadence_woo_ele_nonce' );
		$output  = '<div class="kt_meta_boxes">';
		$output .= '<div class="kt_meta_box" style="padding: 10px 0 0; border-bottom:1px solid #e9e9e9;">';
		$output .= '<div>';
		$output .= '<label for="_kt_woo_ele_product_template" style="font-weight: 600;">' . esc_html__( 'Assign Product Template', 'kadence-woocommerce-elementor' ) . '</label>';
		$output .= '</div>';
		$output .= '<div>';

		$option_values = $this->get_template_ids();
		$select_value  = get_post_meta( $post->ID, '_kt_woo_ele_product_template', true );

		$output .= '<select name="_kt_woo_ele_product_template">';
		foreach ( $option_values as $key => $value ) {
			if ( $key == $select_value ) {
				$output .= '<option value="' . esc_attr( $key ) . '" selected>' . esc_attr( $value ) . '</option>';
			} else {
				$output .= '<option value="' . esc_attr( $key ) . '">' . esc_attr( $value ) . '</option>';
			}
		}
		$output .= '</select>';
		$output .= '</div>';
		$output .= '<div class="clearfixit" style="padding: 5px 0; clear:both;"></div>';
		$output .= '</div>';
		$output .= '</div>';

		echo $output;
	}

	/**
	 * Get product template ids
	 *
	 * @param string $type Type of the post.
	 */
	public static function get_template_ids( $type = 'ele-product-template' ) {

		$cached = wp_cache_get( $type );

		if ( false !== $cached ) {
			return $cached;
		}
		$post_options = array();
		$args = array(
			'post_type'   => 'ele-product-template',
			'numberposts' => 300,
		);
		$post_options['default']   = __( 'Default', 'kadence-woocommerce-elementor' );
		$post_options['elementor'] = __( 'Use this products Elementor', 'kadence-woocommerce-elementor' );

		$posts = get_posts( $args );

		if ( $posts ) {
			foreach ( $posts as $post ) {
				$post_options[ $post->ID ] = $post->post_title;
			}
			wp_cache_set( $type, $post_options );
		}

		return $post_options;
	}
	/**
	 * Handles saving the meta box.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 * @return null
	 */
	public function save_metabox( $post_id, $post ) {
		// Add nonce for security and authentication.
		$nonce_name   = isset( $_POST['kadence_woo_ele_nonce'] ) ? wp_unslash( $_POST['kadence_woo_ele_nonce'] ) : '';
		$nonce_action = 'kadence_woo_ele_nonce_action';

		// Check if nonce is set.
		if ( ! isset( $nonce_name ) ) {
			return;
		}

		// Check if nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
			return;
		}

		// Check if user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if not an autosave.
		if ( wp_is_post_autosave( $post_id ) ) {
			return;
		}

		// Check if not a revision.
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}
		if ( isset( $_POST['_kt_woo_ele_product_template'] ) ) {
			$woo_ele_product_template = sanitize_text_field( wp_unslash( $_POST['_kt_woo_ele_product_template'] ) );
			update_post_meta( $post_id, '_kt_woo_ele_product_template', $woo_ele_product_template );
		}
	}

}
Kadence_Single_Products_Elementor::get_instance();
