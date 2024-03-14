<?php
/**
 * Metabox handling class
 *
 * @since 1.1.0
 * @package YITH_Proteo_tookit
 */

/**
 * Class Proteo_Testimonials_Metabox
 *
 * @author Francesco Grasso <francgrasso@yithemes.com>
 */
class Proteo_Testimonials_Metabox {

	/**
	 * Post type
	 *
	 * @var array $custom_post_types
	 */
	private $custom_post_types = array(
		'proteo_testimonials',
	);

	/**
	 * Fields
	 *
	 * @var array $fields
	 */
	private $fields = array(
		array(
			'label' => 'Review',
			'id'    => 'proteo_testimonial_review',
			'type'  => 'editor',
			'class' => 'testimonial-review',
		),
		array(
			'label' => 'Small quote',
			'id'    => 'proteo_testimonial_small_quote',
			'type'  => 'textarea',
			'class' => 'testimonial-small-quote',
		),
		array(
			'label' => 'Subtitle',
			'id'    => 'proteo_testimonial_subtitle',
			'type'  => 'text',
			'class' => 'testimonial-subtitle',
		),
		array(
			'label' => 'Website',
			'id'    => 'proteo_testimonial_website',
			'type'  => 'url',
			'class' => 'testimonial-website',
		),
		array(
			'label' => 'Facebook',
			'id'    => 'proteo_testimonial_social_facebook',
			'type'  => 'url',
			'class' => 'testimonial-facebook',
		),
		array(
			'label' => 'Twitter',
			'id'    => 'proteo_testimonial_social_twitter',
			'type'  => 'url',
			'class' => 'testimonial-twitter',
		),
		array(
			'label' => 'Youtube',
			'id'    => 'proteo_testimonial_social_youtube',
			'type'  => 'url',
			'class' => 'testimonial-youtube',
		),
		array(
			'label' => 'Instagram',
			'id'    => 'proteo_testimonial_social_instagram',
			'type'  => 'url',
			'class' => 'testimonial-instagram',
		),
		array(
			'label' => 'TikTok',
			'id'    => 'proteo_testimonial_social_tiktok',
			'type'  => 'url',
			'class' => 'testimonial-tiktok',
		),
		array(
			'label' => 'LinkedIn',
			'id'    => 'proteo_testimonial_social_linkedin',
			'type'  => 'url',
			'class' => 'testimonial-linkedin',
		),
		array(
			'label' => 'Skype',
			'id'    => 'proteo_testimonial_social_skype',
			'type'  => 'url',
			'class' => 'testimonial-skype',
		),
	);

	/**
	 * Retrieve filtered meta fields array
	 *
	 * @return array
	 */
	public function get_fields() {
		return apply_filters( 'proteo_testimonial_meta_fields', $this->fields );
	}

	/**
	 * Metabox constructor method.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_css' ) );
	}

	/**
	 * Add metabox to post
	 *
	 * @return void
	 */
	public function add_meta_boxes() {
		foreach ( $this->custom_post_types as $custom_post_type ) {
			add_meta_box(
				'proteo_testimonial_meta',
				__( 'Testimonial review', 'yith-proteo-toolkit' ),
				array( $this, 'meta_box_callback' ),
				$custom_post_type,
				'normal',
				'default'
			);
		}
	}

	/**
	 * Callback to print metabox controls
	 *
	 * @param object $post Post.
	 * @return void
	 */
	public function meta_box_callback( $post ) {
		wp_nonce_field( 'proteo_testimonial_meta_data', 'proteo_testimonial_meta_nonce' );
		$this->field_generator( $post );
	}

	/**
	 * Echo metabox controls.
	 *
	 * @param object $post Post.
	 * @return void
	 */
	public function field_generator( $post ) {

		$review = get_post_meta( $post->ID, 'proteo_testimonial_review', true );

		wp_editor(
			$review,
			'proteo_testimonial_review',
			array(
				'wpautop'       => true,
				'media_buttons' => false,
				'textarea_name' => 'proteo_testimonial_review',
				'textarea_rows' => 20,
			)
		);

		$output = '';
		foreach ( $this->get_fields() as $field ) {
			switch ( $field['type'] ) {
				case 'select':
					$meta_value = get_post_meta( $post->ID, $field['id'], true );
					$label      = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
					$input      = sprintf(
						'<select id="%s" name="%s" class="%s">',
						esc_attr( $field['id'] ),
						esc_attr( $field['id'] ),
						esc_attr( $field['class'] )
					);
					foreach ( $field['options'] as $key => $value ) {
						$field_value = ! is_numeric( $key ) ? $key : $value;
						$input      .= sprintf(
							'<option %s value="%s">%s</option>',
							$meta_value === $field_value ? 'selected' : '',
							esc_attr( $field_value ),
							esc_html( $value )
						);
					}
					$input .= '</select>';
					break;
				case 'products-select':
					$meta_value = get_post_meta( $post->ID, $field['id'], true );
					$label      = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
					$input      = sprintf(
						'<select class="wc-product-search" id="%s" name="%s" data-placeholder="%s" data-action="woocommerce_json_search_products_and_variations">',
						esc_attr( $field['id'] ),
						esc_attr( $field['id'] ),
						esc_attr__( 'Search for a product&hellip;', 'yith-proteo-toolkit' ),
					);

					if ( $meta_value && ! is_array( $meta_value ) ) {
						$meta_value = explode( ',', $meta_value );
					}

					if ( ! empty( $meta_value ) ) {
						foreach ( $meta_value as $product_id ) {
							$product = wc_get_product( $product_id );
							if ( is_object( $product ) ) {
								$input .= '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . wp_kses_post( $product->get_formatted_name() ) . '</option>';
							}
						}
					}

					$input .= '</select>';
					?>

					<?php
					break;
				case 'editor':
					$label = '';
					$input = '';
					break;
				case 'textarea':
					$meta_value = get_post_meta( $post->ID, $field['id'], true );
					$label      = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
					$input      = sprintf(
						'<textarea id="%s" name="%s" rows="5" class="%s">%s</textarea>',
						esc_attr( $field['id'] ),
						esc_attr( $field['id'] ),
						esc_attr( $field['class'] ),
						esc_html( $meta_value )
					);
					break;
				case 'url':
					$meta_value = get_post_meta( $post->ID, $field['id'], true );
					$label      = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
					$input      = sprintf(
						'<input %s class="%s" id="%s" name="%s" type="%s" value="%s">',
						'color' !== $field['type'] ? 'style="width: 100%"' : '',
						esc_attr( $field['class'] ),
						esc_attr( $field['id'] ),
						esc_attr( $field['id'] ),
						esc_attr( $field['type'] ),
						esc_url( $meta_value )
					);
					break;
				default:
					$meta_value = get_post_meta( $post->ID, $field['id'], true );
					$label      = '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
					$input      = sprintf(
						'<input %s class="%s" id="%s" name="%s" type="%s" value="%s">',
						'color' !== $field['type'] ? 'style="width: 100%"' : '',
						esc_attr( $field['class'] ),
						esc_attr( $field['id'] ),
						esc_attr( $field['id'] ),
						esc_attr( $field['type'] ),
						$meta_value
					);
			}
			$output .= $this->format_rows( $label, $input );

		}
		echo '<table class="form-table"><tbody>' . $output . '</tbody></table>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Prettify metabox controls
	 *
	 * @param mixed $label meta control label element.
	 * @param mixed $input meta control html element.
	 * @return string
	 */
	public function format_rows( $label, $input ) {
		return '<div class="proteo-testimonials-meta" style="margin-top: 10px;">' . $label . $input . '</div>';
	}

	/**
	 * Hooks into WordPress' save_post function
	 *
	 * @param int $post_id Slider post ID.
	 *
	 * @return int|void
	 */
	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['proteo_testimonial_meta_nonce'] ) ) {
			return $post_id;
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST['proteo_testimonial_meta_nonce'] ) );
		if ( ! wp_verify_nonce( $nonce, 'proteo_testimonial_meta_data' ) ) {
			return $post_id;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		foreach ( $this->get_fields() as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( wp_unslash( $_POST[ $field['id'] ] ) );
						break;
					case 'url':
						$_POST[ $field['id'] ] = esc_url_raw( wp_unslash( $_POST[ $field['id'] ] ) );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( wp_unslash( $_POST[ $field['id'] ] ) );
						break;
				}
				update_post_meta( $post_id, $field['id'], wp_kses_post( wp_unslash( $_POST[ $field['id'] ] ) ) );
			} elseif ( 'checkbox' === $field['type'] ) {
				update_post_meta( $post_id, $field['id'], '0' );
			}
		}
	}

	/**
	 * Enqueue custom admin css for the metaboxes
	 */
	public function admin_css() {

		if ( 'proteo_testimonials' === get_post_type() ) {
			wp_enqueue_style( 'proteo_testimonials_admin_css', YITH_PROTEO_TOOLKIT_URL . 'includes/testimonials-module/assets/testimonials-admin.css', array(), YITH_PROTEO_TOOLKIT_VERSION );

			if ( function_exists( 'WC' ) ) {
				$suffix  = 'true' === SCRIPT_DEBUG ? '' : '.min';
				$version = WC_VERSION;
				wp_enqueue_script( 'proteo_testimonials_admin_js', YITH_PROTEO_TOOLKIT_URL . 'includes/testimonials-module/assets/testimonials-admin.js', array(), YITH_PROTEO_TOOLKIT_VERSION, true );
				wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js', array( 'jquery', 'selectWoo' ), $version, true );
				wp_enqueue_script( 'wc-enhanced-select' );
			}
		}
	}

}

if ( class_exists( 'Proteo_Testimonials_Metabox' ) ) {
	new Proteo_Testimonials_Metabox();
};
