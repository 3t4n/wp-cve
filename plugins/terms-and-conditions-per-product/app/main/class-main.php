<?php
/**
 * Class for custom work.
 *
 * @package Terms_Conditions_Per_Product
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class is exist, then don't execute this.
if ( ! class_exists( 'TACPP4_Terms_Conditions_Per_Product' ) ) {

	/**
	 * Class for transxen core.
	 */
	class TACPP4_Terms_Conditions_Per_Product {

		static $meta_key;

		protected static $instance = null;

		public static function get_instance() {
			null === self::$instance and self::$instance = new self;

			return self::$instance;
		}


		/**
		 * Constructor for class.
		 */
		public function __construct() {

			// Deprecated, use tacpp_custom_terms_meta_key
			self::$meta_key = apply_filters( 'gkco_custom_terms_meta_key', '_custom_product_terms_url' );

			self::$meta_key = apply_filters( 'tacpp_custom_terms_meta_key', '_custom_product_terms_url' );

			// Enqueue front-end scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style_scripts' ), 100 );

			// Enqueue Back end scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_style_scripts' ), 100 );

			// The code for displaying WooCommerce Product Custom Fields
			add_action( 'woocommerce_product_options_advanced',
				array( $this, 'woocommerce_product_custom_fields' ) );

			// The following code Saves  WooCommerce Product Custom Fields
			add_action( 'woocommerce_process_product_meta',
				array( $this, 'woocommerce_product_custom_fields_save' ) );


			// Add product specific Terms and Conditions to WC Checkout
			add_action( 'woocommerce_review_order_before_submit',
				array( __class__, 'add_checkout_per_product_terms' ) );

			// Notify user if terms are not selected
			add_action( 'woocommerce_checkout_process',
				array( $this, 'action_not_approved_terms' ), 20 );

			add_action( 'woocommerce_product_after_variable_attributes',
				array( $this, 'add_terms_and_conditions_input_to_variations' ), 20, 3 );

			add_action( 'woocommerce_save_product_variation',
				array( $this, 'save_terms_field_variations' ), 10, 2 );

			add_filter( 'woocommerce_available_variation',
				array( $this, 'add_terms_to_variable_data' ) );

		}


		/**
		 * Enqueue style/script.
		 *
		 * @return void
		 */
		public function enqueue_style_scripts() {

			// Custom plugin script.
			wp_enqueue_style(
				'terms-per-product-core-style',
				TACPP4_PLUGIN_URL . 'assets/css/terms-per-product.css',
				'',
				TACPP4_PLUGIN_VERSION
			);

			// Register plugin's JS script
			wp_register_script(
				'terms-per-product-custom-script',
				TACPP4_PLUGIN_URL . 'assets/js/terms-per-product.js',
				array(
					'jquery',
				),
				TACPP4_PLUGIN_VERSION,
				true
			);

			wp_enqueue_script( 'terms-per-product-custom-script' );

		}

		/**
		 * Enqueue Admin style/script.
		 *
		 * @return void
		 */
		public function admin_enqueue_style_scripts() {

		}


		/**
		 * Add custom fields to WC product
		 *
		 */
		public function woocommerce_product_custom_fields() {

			global $woocommerce, $post;

			if ( (int) $post->ID <= 0 || ! class_exists( 'WC_Product_Factory' ) ) {
				return;
			}

			// Set up skipped types
			$skipped_product_types = array(
				'external',
			);

			// Get product type
			$product_type = WC_Product_Factory::get_product_type( $post->ID );


			// Do not add the field if the product type is not supported
			if ( in_array( $product_type, $skipped_product_types ) ) {
				return;
			}


			?>
			<div class="product_custom_field">
				<?php
				$args = array(
					'id'          => self::$meta_key,
					'placeholder' => 'Add the URL of the terms page.',
					'label'       => __( 'Custom Terms and Condition Page (URL)', 'terms-and-conditions-per-product' ),
					'desc_tip'    => 'true'
				);

				// Apply filters
				$args = apply_filters( 'gkco_custom_product_terms_input_args', $args );

				// Custom Product Text Field
				woocommerce_wp_text_input( $args );
				?>
			</div>
			<?php
		}

		/**
		 * Save fields
		 *
		 */
		public function woocommerce_product_custom_fields_save( $post_id ) {

			// Custom Product Text Field
			$woocommerce_custom_product_text_field = $_POST[ self::$meta_key ];

			// Sanitize input
			$link = filter_var( $woocommerce_custom_product_text_field, FILTER_SANITIZE_URL );

			// Run this action before saving the link
			do_action( 'gkco_before_save_custom_product_terms_link', $link, $woocommerce_custom_product_text_field );

			// Add post meta
			update_post_meta( $post_id, self::$meta_key, esc_attr( $link ) );

		}

		/**
		 * Add product Terms and Conditions in checkout page
		 *
		 */
		public static function add_checkout_per_product_terms() {

			// Log items that show T&C checkbox in order to avoid duplicate checkboxes
			$tac_shown_for_items = array();

			// Loop through each cart item
			foreach ( WC()->cart->get_cart() as $cart_item ) {

				$product_id   = $cart_item['product_id'];
				$variation_id = $cart_item['variation_id'];

				$product_terms_url  = '';
				$product_terms_text = '';

				// To get T&C from variations
				$parent_id = wp_get_post_parent_id( $product_id );

				if ( $variation_id > 0 ) {
					$product_terms_url = get_post_meta(
						$variation_id,
						'variation_terms_url',
						true
					);

					$product_terms_text = get_post_meta(
						$variation_id,
						'variation_terms_text',
						true
					);

					if ( ! empty( $product_terms_url ) ) {
						$product_id = $variation_id;
					}
				}

				if ( empty( $product_terms_url ) && $parent_id > 0 ) {
					$product_id = $parent_id;
				}

				// Skip already shown T&C
				if ( in_array( $product_id, $tac_shown_for_items ) ) {
					continue;
				}

				if ( empty( $product_terms_url ) ) {
					$product_terms_url = trim( get_post_meta( $product_id, self::$meta_key, true ) );
				}

				if ( ! empty( $product_terms_url ) ) {
					?>
					<div class="extra-terms">
						<p class="form-row terms wc-terms-and-conditions form-row validate-required">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
								<input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms-<?php echo $product_id; ?>" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST[ 'terms-' . $product_id ] ) ), true ); ?> id="terms-<?php echo $product_id; ?>">

								<?php
								if ( empty( $product_terms_text ) ) {
									$terms_text = '<a href="[TERMS_URL]" target="_blank">[TERMS]</a> ' . __( 'of', 'terms-and-conditions-per-product' ) . ' <strong>[PRODUCT_TITLE]</strong>';
								} else {

									if ( strstr( $product_terms_text, '[link]' ) && strstr( $product_terms_text, '[/link]' ) ) {

										$search = array(
											'[link]',
											'[/link]'
										);

										$replace = array(
											'<a href="[TERMS_URL]" target="_blank">',
											'</a>'
										);

										$terms_text = str_replace( $search, $replace, $product_terms_text );
									} else {
										$terms_text = '<a href="[TERMS_URL]" target="_blank">' . $product_terms_text . '</a>';
									}

								}


								$terms_text = apply_filters(
									'gkco_custom_product_terms_text',
									$terms_text,
									$product_terms_url,
									$product_id
								);

								$search = array(
									'[TERMS_URL]',
									'[TERMS]',
									'[PRODUCT_TITLE]'
								);

								$replace = array(
									esc_html( $product_terms_url ),
									__( 'Terms and Conditions', 'terms-and-conditions-per-product' ),
									get_the_title( $product_id ),
								);

								$terms_html = str_replace( $search, $replace, $terms_text );

								// Apply HTML filter
								$terms_html = apply_filters(
									'gkco_custom_product_terms_html',
									$terms_html,
									$product_terms_url,
									$product_id
								);
								?>
								<span>
									<?php echo $terms_html; ?>
								</span>

								<span class="required">*</span>

							</label>
						</p>
						<div class="clearfix"></div>
					</div>
					<?php
				}

				$tac_shown_for_items[] = $product_id;
			}
		}

		/**
		 * Notify user if they have not selected the terms checkbox
		 *
		 */
		public function action_not_approved_terms() {

			// Log items that show T&C checkbox in order to avoid duplicate checkboxes
			$tac_shown_for_items = array();

			// Loop through each cart item
			foreach ( WC()->cart->get_cart() as $cart_item ) {

				$product_id   = $cart_item['product_id'];
				$variation_id = $cart_item['variation_id'];

				$product_terms_url  = '';
				$product_terms_text = '';


				if ( $variation_id > 0 ) {
					$product_terms_url = get_post_meta(
						$variation_id,
						'variation_terms_url',
						true
					);

					if ( ! empty( $product_terms_url ) ) {
						$product_id = $variation_id;

						$product_terms_text = get_post_meta(
							$variation_id,
							'variation_terms_text',
							true
						);
					}
				}

				// Skip already shown T&C
				if ( in_array( $product_id, $tac_shown_for_items ) ) {
					continue;
				}

				if ( empty( $product_terms_url ) ) {
					$product_terms_url = trim( get_post_meta( $product_id, self::$meta_key, true ) );
				}

				// Check if the product has a custom terms page set
				if ( ! empty( $product_terms_url ) && ! isset( $_POST[ 'terms-' . $product_id ] ) ) {
					$error_text = __( 'Please <strong>read and accept</strong> the Terms and Conditions of', 'terms-and-conditions-per-product' ) . ": &quot;";
					if ( ! empty( $product_terms_text ) ) {

						// Clean up [link] tags if they exist in the text
						$remove_tags        = array( '[link]', '[/link]' );
						$product_terms_text = str_replace( $remove_tags, '', $product_terms_text );

						$error_text .= "<b>" . $product_terms_text . "</b>";
					} else {
						$error_text .= "<b>" . get_the_title( $product_id ) . "</b>.";
					}
					$error_text .= "&quot;";

					// Add filter for error notice
					$error_text = apply_filters( 'gkco_custom_product_terms_error_notice', $error_text, $product_id );

					// Display notice
					wc_add_notice( $error_text, 'error' );

				}

				$tac_shown_for_items[] = $product_id;
			}


		}

		/**
		 * Add a terms and conditions input field to variations
		 *
		 * @param $loop
		 * @param $variation_data
		 * @param $variation
		 */
		public function add_terms_and_conditions_input_to_variations( $loop, $variation_data, $variation ) {
			woocommerce_wp_text_input( array(
				'id'    => 'variation_terms_url[' . $loop . ']',
				'class' => 'short',
				'label' => __( 'Terms And Conditions URL', 'terms-and-conditions-per-product' ),
				'value' => get_post_meta( $variation->ID, 'variation_terms_url', true )
			) );
			woocommerce_wp_text_input( array(
				'id'    => 'variation_terms_text[' . $loop . ']',
				'class' => 'short',
				'label' => __( 'Terms And Conditions Text (You can use [link][/link] tags to select the specific text to link)', 'terms-and-conditions-per-product' ),
				'value' => get_post_meta( $variation->ID, 'variation_terms_text', true )
			) );
		}


		/**
		 * Save variations' terms and conditions fields
		 *
		 * @param $variation_id
		 * @param $i
		 */
		public function save_terms_field_variations( $variation_id, $i ) {
			$variation_terms_url = $_POST['variation_terms_url'][ $i ];
			if ( isset( $variation_terms_url ) ) {
				update_post_meta( $variation_id, 'variation_terms_url', esc_attr( $variation_terms_url ) );
			}

			$variation_terms_text = $_POST['variation_terms_text'][ $i ];
			if ( isset( $variation_terms_text ) ) {
				update_post_meta( $variation_id, 'variation_terms_text', esc_attr( $variation_terms_text ) );
			}
		}


		/**
		 * Store terms and conditions data to variable details
		 *
		 * @param $variations
		 *
		 * @return mixed
		 */
		public function add_terms_to_variable_data( $variations ) {
			$variations['variation_terms_url'] = get_post_meta( $variations['variation_id'], 'variation_terms_url', true );

			$variations['variation_terms_text'] = get_post_meta( $variations['variation_id'], 'variation_terms_text', true );

			return $variations;
		}
	}

	new TACPP4_Terms_Conditions_Per_Product();
}
