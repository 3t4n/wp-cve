<?php
/**
 * Class for custom work.
 *
 * @package Terms_Conditions_Per_Categories
 */

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If class exists, then don't execute this.
if ( ! class_exists( 'TACPP4_Terms_Conditions_Per_Categories' ) ) {

    /**
     * Class for transxen core.
     */
    class TACPP4_Terms_Conditions_Per_Categories {

        static $term_url_meta_key;
        static $term_text_meta_key;

        protected static $instance = null;

        public static function get_instance() {
            null === self::$instance and self::$instance = new self;

            return self::$instance;
        }


        /**
         * Constructor for class.
         */
        public function __construct() {

            self::$term_text_meta_key = apply_filters( 'tacpp_pro_term_text_meta_key', 'product_category_terms_text' );
            self::$term_url_meta_key  = apply_filters( 'tacpp_pro_term_url_meta_key', 'product_category_terms_url' );

            // Add the fields to the "product category" taxonomy
            add_action( 'product_cat_edit_form_fields',
                array( __class__, 'product_terms_custom_fields' ), 10, 2 );

            add_action( 'product_cat_add_form_fields',
                array( __class__, 'product_terms_custom_fields' ), 10, 2 );

            // Save the changes made on the "product category" taxonomy
            add_action( 'edited_product_cat',
                array( __class__, 'save_product_terms_custom_fields' ), 10, 2 );
            add_action( 'created_product_cat',
                array( __class__, 'save_product_terms_custom_fields' ), 10, 2 );


			// Add the fields to the "product tags" taxonomy
            add_action( 'product_tag_edit_form_fields',
                array( __class__, 'product_terms_custom_fields' ), 10, 2 );

            add_action( 'product_tag_add_form_fields',
                array( __class__, 'product_terms_custom_fields' ), 10, 2 );

            // Save the changes made on the "product tag" taxonomy
            add_action( 'edited_product_tag',
                array( __class__, 'save_product_terms_custom_fields' ), 10, 2 );
            add_action( 'created_product_tag',
                array( __class__, 'save_product_terms_custom_fields' ), 10, 2 );
        }

        public static function product_terms_custom_fields( $tag ) {
            // Check for existing taxonomy meta for the term you're editing
            $term_id = false;

            if ( isset( $tag->term_id ) ) {
                $term_id = $tag->term_id; // Get the ID of the term you're editing
            }
            ?>
			<tr class="form-field">
                <?php
                // Show this only in the category edit page
                if ( $term_id ) { ?>
					<th scope="row" valign="top">
						<label for="category_terms_text"><?php _e( 'Terms and Conditions URL' ); ?></label>
					</th>
                <?php } ?>
				<td>
					<div class="product_cat_custom_field">
                        <?php
                        if ( ! tacppp_fs()->is_paying_or_trial() ) {
                            $args = array(
                                'id'                => 'tacpp-url-blocked',
                                'placeholder'       => 'https://',
                                'label'             => __( 'Add the URL of the terms page.', 'terms-and-conditions-per-product' ),
                                'desc_tip'          => 'true',
                                'custom_attributes' => array( 'readonly' => 'readonly' ),
                                'value'             => ''
                            );
                        } else {
                            $args = array(
                                'id'          => self::$term_url_meta_key,
                                'placeholder' => 'https://',
                                'label'       => __( 'Add the URL of the terms page.', 'terms-and-conditions-per-product' ),
                                'desc_tip'    => 'true',
                                'value'       => get_term_meta( $term_id, self::$term_url_meta_key, true )
                            );
                        }

                        // Apply filters
                        $args = apply_filters( 'tacpp_pro_product_cat_url_input_args', $args );

                        // Custom Product Text Field
                        woocommerce_wp_text_input( $args );
                        ?>
					</div>
				</td>
			</tr>


			<tr class="form-field">
                <?php
                // Show this only in the category edit page
                if ( $term_id ) { ?>
					<th scope="row" valign="top">
						<label for="category_terms_text"><?php _e( 'Terms and Conditions Text' ); ?></label>
					</th>
                <?php } ?>
				<td>
					<div class="product_cat_custom_field">
                        <?php
                        if ( ! tacppp_fs()->is_paying_or_trial() ) {
                            $args = array(
                                'id'                => 'tacpp-text-blocked',
                                'placeholder'       => __( 'This is available to premium only', 'terms-and-conditions-per-product' ),
                                'label'             => __( 'Terms And Conditions Text (You can use [link][/link] tags to mark text as the terms link)', 'terms-and-conditions-per-product' ),
                                'desc_tip'          => 'true',
                                'custom_attributes' => array( 'readonly' => 'readonly' ),
                                'value'             => ''
                            );
                        } else {
                            $args = array(
                                'id'          => self::$term_text_meta_key,
                                'placeholder' => 'Add the text for the link of the terms page.',
                                'label'       => __( 'Terms And Conditions Text (You can use [link][/link] tags to mark text as the terms link)', 'terms-and-conditions-per-product' ),
                                'desc_tip'    => 'true',
                                'value'       => get_term_meta( $term_id, self::$term_text_meta_key, true )
                            );
                        }

                        // Apply filters
                        $args = apply_filters( 'tacpp_pro_product_cat_text_input_args', $args );

                        // Custom Product Text Field
                        woocommerce_wp_text_input( $args );
                        ?>
					</div>
				</td>
			</tr>
			<tr class="form-field">
				<th></th>
				<td>
                    <?php
                    if ( ! tacppp_fs()->is_paying_or_trial() ) {
                        printf( __( 'Get the <a href="%s">premium version</a> now!', 'terms-and-conditions-per-product' ),
                            TACPP4_PLUGIN_PRO_BUY_URL
                        );
                    }
                    ?>
				</td>
			</tr>

            <?php
        }

        /**
         * Update the product category meta
         *
         * @param $term_id
         */
        public static function save_product_terms_custom_fields( $term_id ) {

            // Store the product category Terms and Conditions link text
            if ( isset( $_POST[ self::$term_text_meta_key ] ) && ! empty( $_POST[ self::$term_text_meta_key ] ) ) {
                update_term_meta( $term_id, self::$term_text_meta_key,
                    sanitize_text_field( $_POST[ self::$term_text_meta_key ] ) );
            } else {
                delete_term_meta( $term_id, self::$term_text_meta_key );
            }

            // Store the product category Terms and Conditions link URL
            if ( isset( $_POST[ self::$term_url_meta_key ] ) && ! empty( $_POST[ self::$term_url_meta_key ] ) ) {
                update_term_meta( $term_id, self::$term_url_meta_key,
                    sanitize_text_field( $_POST[ self::$term_url_meta_key ] ) );
            } else {
                delete_term_meta( $term_id, self::$term_url_meta_key );
            }
        }
    }

    new TACPP4_Terms_Conditions_Per_Categories();
}
