<?php

/**
 * Helper functions.
 * @link       https://tranzly.io
 * @since      1.0.0
 */
if ( !function_exists( 'tranzly_get_deepl_api_key' ) ) {
    /**
     * Gets the deepl api key.
     */
    function tranzly_get_deepl_api_key()
    {
        $tranzly_options = get_option( 'tranzly_options' );
        $deepl_api_key = $tranzly_options['deepl_api_key'];
        return $deepl_api_key;
    }

}
if ( !function_exists( 'tranzly_get_deepl_endpoint' ) ) {
    /**
     * Gets the deepl api key.
     */
    function tranzly_get_deepl_endpoint()
    {
		/*This is the endpoint to DeepL api key integration.
		/*Tranzly uses DeepL as API provider.
		/*Tranzly is not affiliate or taking any commission from users when they purchases DeepL.com Pro Key.
		*/
        $endpoint = apply_filters( 'tranzly_deepl_endpoint', 'https://api.deepl.com/v2/translate' );
        return $endpoint;
    }

}
if ( !function_exists( 'tranzly_supported_languages' ) ) {
    /**
     * DeepL supported languages.
     */
    function tranzly_supported_languages()
    {
        $languages = array(
            'FR' => 'French',
            'EN' => 'English',
            'DE' => 'German',
            'ES' => 'Spanish',
            'PT' => 'Portuguese',
            'IT' => 'Italian',
            'NL' => 'Dutch',
            'PL' => 'Polish',
            'RU' => 'Russian',
        );
        return apply_filters( 'tranzly_supported_languages', $languages );
    }

}
if ( !function_exists( 'tranzly_tags_n_atts_to_be_translated' ) ) {
    /**
     * List of tags and attributes to be translated.
     */
    function tranzly_tags_n_atts_to_be_translated()
    {
        $tags = array(
            'a'   => array( 'title' ),
            'img' => array( 'title', 'alt' ),
        );
        return apply_filters( 'tranzly_tags_n_atts_to_be_translated', $tags );
    }

}
if ( !function_exists( 'tranzly_split_long_string' ) ) {
    /**
     * Split long string into multiple strings.
     */
    function tranzly_split_long_string( $long_string )
    {
        $length = apply_filters( 'tranzly_split_length', 1500 );
        $max_length = apply_filters( 'tranzly_split_max_length', 2000 );
        $truncate = new Tranzly_Truncate_HTML();
        $truncate->set_length( $length );
        $truncate->set_max_length( $max_length );
        $truncate->set_content( $long_string );
        return $truncate->get_splitted();
    }

}
if ( !function_exists( 'tranzly_is_gutenberg_active' ) ) {
    /**
     * Check if gutenberg plugin is active.
     */
    function tranzly_is_gutenberg_active()
    {
        if ( function_exists( 'is_gutenberg_page' ) && is_gutenberg_page() ) {
            // The Gutenberg plugin is on.
            return true;
        }
        $current_screen = get_current_screen();
        if ( method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
            // Gutenberg page on 5+.
            return true;
        }
        return false;
    }

}
if ( !function_exists( 'tranzly_get_error_message_wrapper' ) ) {
    /**
     * Gets the error message wrapper.
     */
    function tranzly_get_error_message_wrapper()
    {
        echo  '<div class="tranzly-translation-error"></div>' ;
    }

}
if ( !function_exists( 'tranzly_get_meta_box_screens' ) ) {
    /**
     * Gets the screens where the meta box should be displayed.
     */
    function tranzly_get_meta_box_screens()
    {
        $_post_types = get_post_types( array(
            'public' => true,
        ) );
        $post_types = array();
        foreach ( $_post_types as $key => $value ) {
            if ( 'attachment' !== $key ) {
                $post_types[$key] = $value;
            }
        }
        return apply_filters( 'tranzly_meta_box_screens', $post_types );
    }

}
if ( !function_exists( 'tranzly_get_translatable_post_types' ) ) {
    /**
     * Gets the post types customer should be able to translate.
     */
    function tranzly_get_translatable_post_types()
    {
        $_post_types = get_post_types( array(
            'public' => true,
        ), 'objects' );
        $post_types = array();
        foreach ( $_post_types as $post_type ) {
            // Premium Plan only
            if ( 'attachment' !== $post_type->name && 'product' !== $post_type->name ) {
                $post_types[$post_type->name] = $post_type->label;
            }
        }
        return apply_filters( 'tranzly_translatable_post_types', $post_types );
    }

}
if ( !function_exists( 'tranzly_get_post_type_taxonomies' ) ) {
    /**
     * Gets the post type taxonomies.
     */
    function tranzly_get_post_type_taxonomies( $post_type )
    {
        $taxonomies = get_object_taxonomies( $post_type, 'objects' );
        $filtered_taxonomies = array();
        foreach ( $taxonomies as $taxonomy ) {
            $name = $taxonomy->name;
            if ( 'product_visibility' === $name || 'product_shipping_class' === $name ) {
                continue;
            }
            
            if ( 'product_type' === $name ) {
                $label = esc_html__( 'Product Type', 'tranzly' );
            } else {
                $label = $taxonomy->label;
            }
            
            $filtered_taxonomies[$taxonomy->name] = $label;
        }
        return apply_filters(
            'tranzly_post_type_taxonomies',
            $filtered_taxonomies,
            $post_type,
            $taxonomies
        );
    }

}
if ( !function_exists( 'tranzly_format_select_field' ) ) {
    /**
     * Format the select field with options.
     */
    function tranzly_format_select_field( $name, $id, $terms )
    {
        ?>
		<div class="tranzly-select-field-wrapper" style="max-width: 25em;">
			<select
				name="<?php 
        echo  esc_attr( $name ) ;
        ?>"
				id="<?php 
        echo  esc_attr( $id ) ;
        ?>"
				class="tranzly-taxonomy-select"
				multiple="multiple"
				style="width: 100%;"
			>
				<?php 
        foreach ( $terms as $term ) {
            ?>
					<option value="<?php 
            echo  esc_attr( $term->term_id ) ;
            ?>">
						<?php 
            echo  esc_html( $term->name ) ;
            ?>
					</option>
				<?php 
        }
        ?>
			</select>
		</div>
		<?php 
    }

}
if ( !function_exists( 'tranzly_get_post_type_taxonomy_filter_markup' ) ) {
    /**
     * Gets the post type taxonomy filter markup.
     */
    function tranzly_get_post_type_taxonomy_filter_markup( $post_type )
    {
        do_action( 'tranzly_before_post_type_taxonomy_filter_markup', $post_type );
        $taxonomies = tranzly_get_post_type_taxonomies( $post_type );
        foreach ( $taxonomies as $taxonomy => $name ) {
            $args = apply_filters( 'tranzly_taxonomy_terms_args', array(
                'taxonomy' => $taxonomy,
            ), $taxonomy );
            $terms = apply_filters( 'tranzly_taxonomy_terms', get_terms( $args ) );
            if ( !$terms ) {
                return;
            }
            printf( '<h3 class="term-title" style="font-size: 14px;">%s:</h3>', esc_html( $name ) );
            $name = "tranzly_options[taxonomy][{$taxonomy}][]";
            $id = $taxonomy;
            tranzly_format_select_field( $name, $id, $terms );
        }
        do_action( 'tranzly_after_post_type_taxonomy_filter_markup', $post_type );
    }

}
if ( !function_exists( 'tranzly_get_placeholder_markup_for_total_translated_posts' ) ) {
    /**
     * Gets the placeholder markup for total translated posts.
     */
    function tranzly_get_placeholder_markup_for_total_translated_posts()
    {
        return sprintf( '<span class="count">0</span> %1$s <span class="total">0</span> %2$s', esc_html__( 'of', 'tranzly' ), esc_html__( 'done', 'tranzly' ) );
    }

}