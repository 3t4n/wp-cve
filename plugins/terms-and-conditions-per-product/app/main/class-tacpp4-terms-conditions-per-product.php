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

// If class exists, then don't execute this.
if ( ! class_exists( 'TACPP4_Terms_Conditions_Per_Product' ) ) {

    /**
     * Class for transxen core.
     */
    class TACPP4_Terms_Conditions_Per_Product {

        static $meta_key;
        static $meta_key_text;

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
            self::$meta_key      = apply_filters( 'gkco_custom_terms_meta_key', '_custom_product_terms_url' );
            self::$meta_key      = apply_filters( 'tacpp_custom_terms_meta_key', '_custom_product_terms_url' );
            self::$meta_key_text = apply_filters( 'tacpp_custom_terms_meta_key_text', '_custom_product_terms_text' );


            // Enqueue front-end scripts
            add_action( 'wp_enqueue_scripts',
				array( $this, 'enqueue_style_scripts' ), 100 );


            // Enqueue Back end scripts
            add_action( 'admin_enqueue_scripts',
				array( $this, 'admin_enqueue_style_scripts' ), 100 );

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

            add_action( 'woocommerce_after_add_to_cart_form',
                array( __class__, 'show_terms_on_product_page' ), 20 );

            add_filter( 'woocommerce_checkout_show_terms',
                array( $this, 'show_default_terms' ) );
        }


        /**
         * Enqueue style/script.
         *
         * @return void
         */
        public function enqueue_style_scripts() {

            // Bailout if not checkout page or single product
            if ( is_admin() || ! function_exists( 'is_checkout' ) ||
                 ! ( is_checkout() || is_product() )
            ) {
                return;
            }

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

            // Register plugin's admin JS script
            wp_register_script(
                'terms-per-product-admin-script',
                TACPP4_PLUGIN_URL . 'assets/js/terms-per-product-admin.js',
                array(
                    'jquery',
                ),
                TACPP4_PLUGIN_VERSION,
                true
            );

            wp_localize_script( 'terms-per-product-admin-script',
                'tacppObj',
                array(
                    'ajaxURL'   => admin_url( 'admin-ajax.php' ),
                    'ajaxNonce' => wp_create_nonce( 'tacpp-ajax-nonce' ),
                )
            );

            wp_enqueue_script( 'terms-per-product-admin-script' );


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

                // Deprecated, use tacpp_custom_product_terms_input_args
                $args = apply_filters( 'gkco_custom_product_terms_input_args', $args );
                // Apply filters
                $args = apply_filters( 'tacpp_custom_product_terms_input_args', $args );

                // Custom Product Text Field
                woocommerce_wp_text_input( $args );
                ?>
			</div>
			<div class="product_custom_field">
                <?php


                $args = array(
                    'id'    => self::$meta_key_text,
                    'class' => 'short',
                    'label' => __( 'Terms And Conditions Text (You can use [link][/link] tags to select the specific text to link)', 'terms-and-conditions-per-product' ),
                    'value' => get_post_meta( $post->ID, self::$meta_key_text, true )
                );

                // Apply filters
                $args = apply_filters( 'tacpp_custom_product_terms_text_args', $args );

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
            $woocommerce_product_text_field_text   = $_POST[ self::$meta_key_text ];

            // Sanitize input
            $link = filter_var( $woocommerce_custom_product_text_field, FILTER_SANITIZE_URL );
            $text = sanitize_text_field( $woocommerce_product_text_field_text );

            //Deprecated, use tacpp_before_save_custom_product_terms_link
            do_action( 'gkco_before_save_custom_product_terms_link', $link,
                $woocommerce_custom_product_text_field );
            // Run this action before saving the link
            do_action( 'tacpp_before_save_custom_product_terms_link', $text,
                $woocommerce_product_text_field_text );

            // Add post meta
            update_post_meta( $post_id, self::$meta_key, esc_attr( $link ) );
            update_post_meta( $post_id, self::$meta_key_text, esc_attr( $text ) );
        }

        /**
         * Add product Terms and Conditions in checkout page
         *
         */
        public static function add_checkout_per_product_terms() {

            // Log items that show T&C checkbox in order to avoid duplicate checkboxes
            $tac_shown_for_items = array();

            $settings = get_option( TACPP4_Terms_Conditions_Settings::$tacpp_option_name );

            // Get the terms must read option
            $must_read_option      = isset( $settings['terms_must_read'] ) ? $settings['terms_must_read'] : 0;
            $show_must_read_notice = false;

            $disabled        = '';
            $must_open_class = '';
            if ( $must_read_option === 1 ) {
                $disabled        = 'disabled';
                $must_open_class = 'must-open-url';
            }
			if ( ! WC()->cart ) {
				return;
            }

            // Loop through each cart item
            foreach ( WC()->cart->get_cart() as $cart_item ) {

                $terms = self::get_custom_terms( $cart_item );

                foreach ( $terms as $term ) {

                    $product_terms_url  = $term['url'];
                    $product_terms_text = $term['text'];
                    $product_id         = absint( $term['product_id'] );
                    $variation_id       = absint( $term['variation_id'] );
                    $term_id            = absint( $term['term_id'] );

                    // Skip already shown T&C
                    if ( in_array( $term['uid'], $tac_shown_for_items ) ) {
                        continue;
                    }

                    // Add the uid to the shown items list
                    $tac_shown_for_items[] = $term['uid'];

                    // Display the checkbox
                    if ( ! empty( $product_terms_url ) ) {

                        // Create the unique term id for the terms input field
                        $check_item_id = self::get_terms_input_uid( $product_id, $variation_id, $term_id );

                        if ( ! $show_must_read_notice && $must_read_option == 1 ) {
                            ?>
							<div class="extra-terms-must-read">
                                <?php
                                $must_read_notice_text = __( '* To continue, <strong>please review</strong> the Terms and Conditions by <strong>clicking the term\'s link</strong> before checking the checkbox.', 'terms-and-conditions-per-product' );
                                /**
                                 * Add a filter for the notice text
                                 */
                                $must_read_notice_text = apply_filters(
                                    'tacpp_force_open_terms_link_notice',
                                    $must_read_notice_text );
                                echo $must_read_notice_text;
                                ?>
							</div>
                            <?php
                            $show_must_read_notice = true;
                        }

                        ?>
						<div class="extra-terms">
							<p class="form-row terms wc-terms-and-conditions form-row validate-required <?php echo $must_open_class; ?>">
								<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox  ">
									<input type="checkbox"
										   class="woocommerce-form__input
										   woocommerce-form__input-checkbox input-checkbox"
										   name="<?php echo $check_item_id; ?>"
                                        <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST[ $check_item_id ] ) ), true ); ?>
										   id="<?php echo $check_item_id; ?>"
                                        <?php
                                        echo $disabled;
                                        ?>
									>
                                    <?php
                                    // Show default text if no text exists
                                    if ( empty( $product_terms_text ) ) {
                                        $terms_text = '<a href="[TERMS_URL]" target="_blank" class="terms-url">[TERMS]</a> ' . __( 'of', 'terms-and-conditions-per-product' ) . ' <strong>[PRODUCT_TITLE]</strong>';
                                    } else {
                                        if ( strstr( $product_terms_text, '[link]' )
                                             && strstr( $product_terms_text,
                                                '[/link]' ) ) {

                                            $search = array(
                                                '[link]',
                                                '[/link]'
                                            );

                                            $replace = array(
                                                '<a href="[TERMS_URL]" target="_blank" class="terms-url">',
                                                '</a>'
                                            );

                                            $terms_text = str_replace( $search, $replace, $product_terms_text );
                                        } else {
                                            $terms_text = '<a href="[TERMS_URL]" target="_blank" class="terms-url">' . $product_terms_text . '</a>';
                                        }

                                    }

                                    /**
                                     * Deprecated: Please use
                                     * 'tacpp_custom_product_terms_text'
                                     */
                                    $terms_text = apply_filters(
                                        'gkco_custom_product_terms_text',
                                        $terms_text,
                                        $product_terms_url,
                                        $product_id
                                    );
                                    /**
                                     * Filter the HTML text of the terms output,
                                     * still includes dynamic keywords
                                     */
                                    $terms_text = apply_filters(
                                        'tacpp_custom_product_terms_text',
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

                                    /**
                                     * Deprecated, please use
                                     * tacpp_custom_product_terms_html
                                     */
                                    $terms_html = apply_filters(
                                        'gkco_custom_product_terms_html',
                                        $terms_html,
                                        $product_terms_url,
                                        $product_id
                                    );

                                    /**
                                     * Apply HTML filter
                                     */
                                    $terms_html = apply_filters(
                                        'tacpp_custom_product_terms_html',
                                        $terms_html,
                                        $product_terms_url,
                                        $product_id
                                    );
                                    ?>
									<span class="extra-terms-checkbox">
								        <?php echo $terms_html; ?>
								    </span>

									<span class="required">*</span>

								</label>
							</p>
							<div class="clearfix"></div>
						</div>
                        <?php
                    }
                }
            }
        }

        /**
         * Return custom terms and conditions for a specific cart item
         *
         */
        public static function get_custom_terms( $cart_item ) {
            $terms = array();

            $product_id   = $cart_item['product_id'];
            $variation_id = $cart_item['variation_id'];

            $product_terms_url  = '';
            $product_terms_text = '';

            // Get the applied checkbox type;
            $type = '';

            // Check if variation has custom terms
            if ( $variation_id > 0 ) {
                $product_terms_url = get_post_meta(
                    $variation_id,
                    'variation_terms_url',
                    true
                );

                $variation_terms_text = get_post_meta(
                    $variation_id,
                    'variation_terms_text',
                    true
                );
                if ( ! empty( $product_terms_url ) ) {
                    $product_terms_text = $variation_terms_text;
                }


                // To get T&C from variations
                $parent_id = wp_get_post_parent_id( $product_id );

                $type = 'variation';

                if ( ! empty( $product_terms_url ) ) {
                    // Set the terms array
                    $terms[] = array(
                        'uid'          => crc32( $product_terms_text .
                                                 $product_terms_url ),
                        'type'         => $type,
                        'text'         => $product_terms_text,
                        'url'          => $product_terms_url,
                        'product_id'   => absint( $product_id ),
                        'variation_id' => absint( $variation_id ),
                        'term_id'      => '',
                    );
                }

            }

            // Check if main product has custom terms
            if ( empty( $product_terms_url ) ) {
                $product_terms_url  = trim( get_post_meta( $product_id, self::$meta_key, true ) );
                $product_terms_text = get_post_meta(
                    $product_id,
                    self::$meta_key_text,
                    true
                );

                $type = 'product';
                if ( ! empty( $product_terms_url ) ) {

                    // Set the terms array
                    $terms[] = array(
                        'uid'          => crc32( $product_terms_text .
                                                 $product_terms_url ),
                        'type'         => $type,
                        'text'         => $product_terms_text,
                        'url'          => $product_terms_url,
                        'product_id'   => absint( $product_id ),
                        'variation_id' => absint( $variation_id ),
                        'term_id'      => '',
                    );
                }
            }

            // Check if premium and category or tag has custom terms
            if ( tacppp_fs()->is_paying_or_trial() && empty( $product_terms_url ) ) {
                $term_types = array( 'product_cat', 'product_tag' );
                $term_types = apply_filters( 'tacpp_allowed_term_types',
                    $term_types );

                // Get product categories
                $product_terms = array();
                foreach ( $term_types as $term_type ) {
                    $cat_terms = get_the_terms( $product_id, $term_type );
                    if ( ! empty( $cat_terms ) && is_array( $cat_terms ) ) {
                        $product_terms = array_merge( $product_terms, $cat_terms );
                    }
                }

                // Loop through each category and fetch terms and conditions
                foreach ( $product_terms as $product_term ) {
                    $term_text = get_term_meta(
                        $product_term->term_id,
                        TACPP4_Terms_Conditions_Per_Categories::$term_text_meta_key,
                        true );

                    $term_url = get_term_meta(
                        $product_term->term_id,
                        TACPP4_Terms_Conditions_Per_Categories::$term_url_meta_key,
                        true );


                    // Set the terms array
                    if ( ! empty( $term_url ) ) {

                        // Set the terms array
                        $terms[] = array(
                            'uid'          => crc32( $term_text .
                                                     $term_url ),
                            'type'         => $product_term->taxonomy,
                            'text'         => $term_text,
                            'url'          => $term_url,
                            'product_id'   => absint( $product_id ),
                            'variation_id' => absint( $variation_id ),
                            'term_id'      => $product_term->term_id,
                        );
                    }
                }

            }

            // Apply filter to array
            return apply_filters( 'taccp_get_custom_terms_cart_item', $terms, $cart_item );
        }

        /**
         * Return a unique input ID for each term item on the checkout page
         *
         */
        private static function get_terms_input_uid( $product_id, $variation_id, $term_id ) {
            $input_id = "tacpp-term-" . $product_id . "-" . $variation_id . "-" . $term_id;

            return apply_filters( 'tacpp_get_terms_input_uid',
                $input_id, $product_id, $variation_id, $term_id );
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

                $terms = self::get_custom_terms( $cart_item );

                foreach ( $terms as $term ) {
                    $product_terms_url  = $term['url'];
                    $product_terms_text = $term['text'];
                    $product_id         = absint( $term['product_id'] );
                    $variation_id       = absint( $term['variation_id'] );
                    $term_id            = absint( $term['term_id'] );

                    // Skip already shown T&C
                    if ( in_array( $term['uid'], $tac_shown_for_items ) ) {
                        continue;
                    }

                    // Add the uid to the shown items list
                    $tac_shown_for_items[] = $term['uid'];

                    // Get the terms unique field ID
                    $check_item_id = self::get_terms_input_uid( $product_id, $variation_id, $term_id );


                    // Check if the product has a custom terms page set
                    if ( ! empty( $product_terms_url ) && ! isset( $_POST[ $check_item_id ] ) ) {
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

                        // Deprecated, use tacpp_custom_product_terms_error_notice
                        $error_text = apply_filters( 'gkco_custom_product_terms_error_notice', $error_text, $product_id );
                        //Add filter for error notice
                        $error_text = apply_filters( 'tacpp_custom_product_terms_error_notice', $error_text, $product_id );

                        // Display notice
                        wc_add_notice( $error_text, 'error' );

                    }
                }
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

        /**
         * Show terms and conditions on the single product page
         */
        public static function show_terms_on_product_page() {
            $product_id = get_the_ID();

            // Bail out if the setting is off.
            $settings = get_option( TACPP4_Terms_Conditions_Settings::$tacpp_option_name );

            if ( ! isset( $settings['terms_on_product'] ) || $settings['terms_on_product'] !== 1 ) {
                return;
            }

            $args = array(
                'product_id'   => $product_id,
                'variation_id' => 0,
            );

            // Log items that show T&C checkbox in order to avoid duplicate checkboxes
            $tac_shown_for_items = array();

            $terms = self::get_custom_terms( $args );
            if ( ! empty( $terms ) && is_array( $terms ) && count( $terms ) > 0 ) {
                echo '<div class="extra-terms-wrapper">';
            }
            foreach ( $terms as $term ) {
                $product_terms_url  = $term['url'];
                $product_terms_text = $term['text'];
                $product_id         = absint( $term['product_id'] );
                $variation_id       = absint( $term['variation_id'] );
                $term_id            = absint( $term['term_id'] );

                // Skip already shown T&C
                if ( in_array( $term['uid'], $tac_shown_for_items ) ) {
                    continue;
                }

                // Add the uid to the shown items list
                $tac_shown_for_items[] = $term['uid'];

                // Display the checkbox
                if ( ! empty( $product_terms_url ) ) {

                    // Create the unique term id for the terms input field
                    $check_item_id = self::get_terms_input_uid( $product_id, $variation_id, $term_id );

                    ?>
					<div class="extra-terms">
                        <?php
                        // Show default text if no text exists
                        if ( empty( $product_terms_text ) ) {
                            $terms_text = '<a href="[TERMS_URL]" target="_blank" class="terms-url">[TERMS]</a> ' . __( 'of', 'terms-and-conditions-per-product' ) . ' <strong>[PRODUCT_TITLE]</strong>';
                        } else {
                            if ( strstr( $product_terms_text, '[link]' )
                                 && strstr( $product_terms_text,
                                    '[/link]' ) ) {

                                $search = array(
                                    '[link]',
                                    '[/link]'
                                );

                                $replace = array(
                                    '<a href="[TERMS_URL]" target="_blank" class="terms-url">',
                                    '</a>'
                                );

                                $terms_text = str_replace( $search, $replace, $product_terms_text );
                            } else {
                                $terms_text = '<a href="[TERMS_URL]" target="_blank" class="terms-url">' . $product_terms_text . '</a>';
                            }

                        }

                        /**
                         * Deprecated: Please use
                         * 'tacpp_custom_product_terms_text'
                         */
                        $terms_text = apply_filters(
                            'gkco_custom_product_terms_text',
                            $terms_text,
                            $product_terms_url,
                            $product_id
                        );
                        /**
                         * Filter the HTML text of the terms output,
                         * still includes dynamic keywords
                         */
                        $terms_text = apply_filters(
                            'tacpp_custom_product_terms_text',
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

                        /**
                         * Deprecated, please use
                         * tacpp_custom_product_terms_html
                         */
                        $terms_html = apply_filters(
                            'gkco_custom_product_terms_html',
                            $terms_html,
                            $product_terms_url,
                            $product_id
                        );

                        /**
                         * Apply HTML filter
                         */
                        $terms_html = apply_filters(
                            'tacpp_custom_product_terms_html',
                            $terms_html,
                            $product_terms_url,
                            $product_id
                        );
                        ?>
						<span><?php echo $terms_html; ?></span>
						<div class="clearfix"></div>
					</div>
                    <?php
                }
            }

            if ( ! empty( $terms ) && is_array( $terms ) && count( $terms ) > 0 ) {
                echo '</div>';
            }
        }


        /**
         * Disable the default WC terms if custom ones exist
         *
         * @param bool $show
         */
        public function show_default_terms( $show ) {
            $has_custom_terms = $this->has_custom_terms();

            // Get the default WC terms hide option
            $settings = get_option( TACPP4_Terms_Conditions_Settings::$tacpp_option_name );
            $hide_terms_option = isset( $settings['hide_default_terms'] ) ? $settings['hide_default_terms'] : 0;

            if ( ! empty( $hide_terms_option ) && $has_custom_terms ) {
                $show = false;
            }

            return $show;
        }

        /**
         * Does the checkout contain have custom terms?
         *
         * @return bool
         */
        private function has_custom_terms() {
            $has_custom_terms = false;

            // Loop through each cart item
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $terms = self::get_custom_terms( $cart_item );

                if ( is_array( $terms ) && count( $terms ) > 0 ) {
                    $has_custom_terms = true;
                    break;
                }
            }

            return apply_filters( 'tacpp_checkout_has_custom_terms', $has_custom_terms );
        }

    }

    new TACPP4_Terms_Conditions_Per_Product();
}
