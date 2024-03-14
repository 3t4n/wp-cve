<?php
/**
 * Functions
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;


if ( ! function_exists( 'ywcas_strtolower' ) ) {
	/**
	 * String to lower
	 *
	 * @param string $string String to manage.
	 *
	 * @return string
	 */
	function ywcas_strtolower( $string ) {
		return function_exists( 'mb_strtolower' ) ? mb_strtolower( $string ) : strtolower( $string );
	}
}

if ( ! function_exists( 'ywcas_get_product_thumbnail_url' ) ) {
	/**
	 * Thumbnail url
	 *
	 * @param WC_Product $product Product.
	 *
	 * @return string
	 */
	function ywcas_get_product_thumbnail_url( $product ) {
		$thumb_id  = $product->get_image_id();
		$wc_thumb = array();
		if ( ! empty( $thumb_id ) ) {
			$thumb = wp_get_attachment_image_src( $thumb_id );
			if ( is_array( $thumb ) ) {
				$thumb_url         = array_shift( $thumb );
				$wc_thumb['small'] = $thumb_url;
			}
			$thumb = wp_get_attachment_image_src( $thumb_id, 'woocommerce_thumbnail' );
			if ( is_array( $thumb ) ) {
				$thumb_url       = array_shift( $thumb );
				$wc_thumb['big'] = $thumb_url;
			}
		}

		return maybe_serialize( $wc_thumb );
	}
}

if ( ! function_exists( 'ywcas_get_index_arguments' ) ) {
	/**
	 * Return a list of arguments to index
	 *
	 * @return array
	 */
	function ywcas_get_index_arguments() {
		$fields = ywcas()->settings->get_search_fields();
		$types  = array_column( $fields, 'type' );

		return $types;
	}
}

if ( ! function_exists( 'ywcas_get_current_language' ) ) {
	/**
	 * Return the current language of shop
	 *
	 * @return string
	 */
	function ywcas_get_current_language() {
		global $sitepress;

		$wpml = apply_filters( 'wpml_current_language', null );

		return $wpml && ! is_null( $sitepress ) ? ywcas_wpml_get_locale_from_language_code( $wpml ) : get_locale();
	}
}

if ( ! function_exists( 'ywcas_get_json' ) ) {
	/**
	 * Get an array and return a json
	 *
	 * @param array $data The data.
	 *
	 * @return string
	 *
	 * @since  1.0.0
	 * @author YITH
	 */
	function ywcas_get_json( $data ) {
		$data_json = wp_json_encode( $data );
		$data_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $data_json ) : _wp_specialchars( $data_json, ENT_QUOTES, 'UTF-8', true );

		return $data_attr;
	}
}

if ( ! function_exists( 'ywcas_get_view' ) ) {
	/**
	 * Get the view
	 *
	 * @param string $file_name Name of the file to get in views.
	 * @param array  $args Arguments.
	 */
	function ywcas_get_view( $file_name, $args = array() ) {
		$file_path = YITH_WCAS_INC . '/admin/views/' . $file_name;
		if ( file_exists( $file_path ) ) {
			extract( $args ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
			include $file_path;
		}
	}
}

if ( ! function_exists( 'ywcas_get_search_fields_type' ) ) {
	/**
	 * Get the type of search fields
	 *
	 * @return array
	 * @since 2.0.0
	 */
	function ywcas_get_search_fields_type() {
		/**
		 * APPLY_FILTERS: ywcas_search_fields_type
		 *
		 * Filter the search field types
		 *
		 * @param array $search_field_types List of search field types.
		 *
		 * @return array
		 */
		return apply_filters(
			'ywcas_search_fields_type',
			array(
				'name'        => _x( 'Product name', '[Admin]search field type', 'yith-woocommerce-ajax-search' ),
				'description' => _x( 'Description', '[Admin]search field type', 'yith-woocommerce-ajax-search' ),
				'summary'     => _x( 'Short description', '[Admin]search field type', 'yith-woocommerce-ajax-search' ),
			)
		);
	}
}

if ( ! function_exists( 'ywcas_get_default_details_to_show' ) ) {
	/**
	 * Return the list of fields to show on results
	 *
	 * @return array
	 * @since 2.0.0
	 */
	function ywcas_get_default_details_to_show() {
		return apply_filters( 'ywcas_get_default_details_to_show', array( 'name', 'image', 'price' ) );
	}
}

if ( ! function_exists( 'ywcas_get_category_list' ) ) {
	/**
	 * Return the list of category
	 *
	 * @param string $type Type of list.
	 * @param string $lang Language requests.
	 *
	 * @return array|int[]|string|string[]|WP_Error|WP_Term[]
	 */
	function ywcas_get_category_list( $type, $lang ) {
		$all        = 'main' !== $type;
		$attributes = array(
			'hide_empty'      => $all,
			'pad_counts'      => true,
			'hierarchical'    => $all,
			'suppress_filter' => true,
		);
		if ( ! $all ) {
			$attributes['parent'] = 0;
		}
		$categories = get_terms(
			'product_cat',
			$attributes
		);

		if ( ! is_array( $categories ) || empty( $categories ) ) {
			return array();
		}

		$new_categories = array();
		foreach ( $categories as $category ) {
			$cat_language = ywcas_get_taxonomy_language( $category->term_id, 'product_cat' );
			if ( $cat_language !== $lang ) {
				continue;
			}

			$category->url    = apply_filters( 'wpml_permalink', get_term_link( $category ), substr( $lang, 0, 2 ) );
			$new_categories[] = (array) $category;
		}

		return 'hierarchical' === $type ? ywcas_build_category_tree( $new_categories ) : (array) $new_categories;
	}
}

if ( ! function_exists( 'ywcas_build_category_tree' ) ) {
	/**
	 * Build hierarchical tree of categories.
	 *
	 * @param array $categories List of terms.
	 *
	 * @return array
	 */
	function ywcas_build_category_tree( $categories ) {
		$categories_by_parent = array();

		foreach ( $categories as $category ) {
			if ( ! isset( $categories_by_parent[ 'cat-' . $category->parent ] ) ) {
				$categories_by_parent[ 'cat-' . $category->parent ] = array();
			}
			$categories_by_parent[ 'cat-' . $category->parent ][] = $category;
		}

		$tree = $categories_by_parent['cat-0'];
		unset( $categories_by_parent['cat-0'] );

		foreach ( $tree as $category ) {
			if ( ! empty( $categories_by_parent[ 'cat-' . $category->term_id ] ) ) {
				$category->children = ywcas_fill_category_children( $categories_by_parent[ 'cat-' . $category->term_id ], $categories_by_parent );
			}
		}

		return $tree;
	}
}

if ( ! function_exists( 'ywcas_fill_category_children' ) ) {
	/**
	 * Build hierarchical tree of categories by appending children in the tree.
	 *
	 * @param array $categories List of terms.
	 * @param array $categories_by_parent List of terms grouped by parent.
	 *
	 * @return array
	 */
	function ywcas_fill_category_children( $categories, $categories_by_parent ) {
		foreach ( $categories as $category ) {
			if ( ! empty( $categories_by_parent[ 'cat-' . $category->term_id ] ) ) {
				$category->children = ywcas_fill_category_children( $categories_by_parent[ 'cat-' . $category->term_id ], $categories_by_parent );
			}
		}

		return $categories;
	}
}

if ( ! function_exists( 'ywcas_get_default_product_post_type' ) ) {
	/**
	 * Return the default post type
	 *
	 * @return array
	 */
	function ywcas_get_default_product_post_type() {
		$product_types = array(
			'product',
			'product_variation'
		);

		return (array) apply_filters( 'ywcas_product_type_list', $product_types );
	}
}

if ( ! function_exists( 'ywcas_get_language' ) ) {
	/**
	 * Return the language of a post
	 *
	 * @param int $post_id Post id.
	 *
	 * @return string
	 */
	function ywcas_get_language( $post_id ) {
		$locale = get_locale();
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$language_details = apply_filters( 'wpml_post_language_details', null, $post_id );
			if ( ! empty( $language_details['locale'] ) ) {
				$locale = $language_details['locale'];
			}
		}

		return $locale;
	}
}

if ( ! function_exists( 'ywcas_get_taxonomy_language' ) ) {
	/**
	 * Return the language of a taxonomy
	 *
	 * @param int    $term_id Term id.
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return string
	 */
	function ywcas_get_taxonomy_language( $term_id, $taxonomy ) {
		$locale      = get_locale();
		$wpml_locale = null;
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$language_details = apply_filters(
				'wpml_element_language_details',
				null,
				array(
					'element_id'   => $term_id,
					'element_type' => $taxonomy,
				)
			);
			if ( $language_details ) {
				$wpml_locale = ywcas_wpml_get_locale_from_language_code( $language_details->language_code );
			}

			$locale = $wpml_locale ?? $locale;
		}

		return $locale;
	}
}

if ( ! function_exists( 'ywcas_wpml_get_locale_from_language_code' ) ) {
	/**
	 * Return the locale
	 *
	 * @param string $language_code Language code.
	 *
	 * @return string
	 */
	function ywcas_wpml_get_locale_from_language_code( $language_code ) {
		global $wpdb;
		$wpml_locale = $wpdb->get_var( $wpdb->prepare( "SELECT locale FROM {$wpdb->prefix}icl_locale_map WHERE code=%s", $language_code ) );

		return $wpml_locale;
	}
}

if ( ! function_exists( 'ywcas_disable_wpml_admin_lang_switcher' ) ) {
	/**
	 * Disable the wpml admin bar and set the default language
	 *
	 * @param bool $state Current status.
	 *
	 * @return bool
	 */
	function ywcas_disable_wpml_admin_lang_switcher( $state ) {
		global $pagenow, $sitepress;

		if ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'yith_wcas_panel' === $_GET['page'] ) { //phpcs:ignore
			$state = false;
			$sitepress->set_admin_language( $sitepress->get_default_language() );
			$sitepress->set_admin_language_cookie( $sitepress->get_default_language() );
		}

		return $state;
	}

	add_filter( 'wpml_show_admin_language_switcher', 'ywcas_disable_wpml_admin_lang_switcher' );
}

if ( ! function_exists( 'ywcas_wpml_add_multi_language_terms_list' ) ) {
	/**
	 * Disable the wpml admin bar and set the default language
	 *
	 * @param array  $list List of taxonomy of default language.
	 * @param string $taxonomy Taxonomy.
	 *
	 * @return array
	 */
	function ywcas_wpml_add_multi_language_terms_list( $list, $taxonomy ) {
		global $sitepress;
		if ( ! $sitepress ) {
			return $list;
		}

		$new_list = array();

		if ( $list ) {
			$languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
			if ( ! empty( $languages ) ) {
				foreach ( $languages as $language_code => $l ) {
					foreach ( $list as $term_id ) {
						$translated_term_id = apply_filters( 'wpml_object_id', $term_id, $taxonomy, true, $language_code );
						if ( $translated_term_id ) {
							$new_list[] = $translated_term_id;
						}
					}
				}
			}
		}

		return array_unique( array_filter( $new_list ) );
	}

	add_filter( 'ywcas_wpml_add_multi_language_terms_list', 'ywcas_wpml_add_multi_language_terms_list', 10, 2 );
}

if ( ! function_exists( 'ywcas_wpml_get_translated_terms_list' ) ) {
	/**
	 * Return the translated term list
	 *
	 * @param array  $list List of terms of default language.
	 * @param string $taxonomy Taxonomy.
	 * @param string $lang Language.
	 *
	 * @return array
	 */
	function ywcas_wpml_get_translated_terms_list( $list, $taxonomy, $lang ) {
		global $sitepress;

		if ( ! $list || ! $sitepress || $sitepress->get_current_language() === substr( $lang, 0, 2 ) ) {
			return $list;
		}

		$new_list = array();
		remove_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1 );
		if ( $list ) {
			foreach ( $list as $term ) {
				$term_id            = $term->term_id;
				$translated_term_id = apply_filters( 'wpml_object_id', $term_id, $taxonomy, true, substr( $lang, 0, 2 ) );
				if ( $translated_term_id ) {
					$translated_term = get_term( $translated_term_id, $taxonomy );
					$new_list[]      = $translated_term;
				}
			}
		}
		add_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1 );
		if ( $new_list ) {
			return $new_list;
		}
	}

	add_filter( 'ywcas_wpml_get_translated_terms_list', 'ywcas_wpml_get_translated_terms_list', 10, 3 );
}

if ( ! function_exists( 'ywcas_wpml_get_translated_terms_list_by_term_name' ) ) {
	/**
	 * Disable the wpml admin bar and set the default language
	 *
	 * @param array  $list List of term names of default language.
	 * @param string $taxonomy Taxonomy.
	 * @param string $lang Language.
	 *
	 * @return array
	 */
	function ywcas_wpml_get_translated_terms_list_by_term_name( $list, $taxonomy, $lang ) {
		global $sitepress;

		if ( ! $list || ! $sitepress || $sitepress->get_current_language() === substr( $lang, 0, 2 ) ) {
			return $list;
		}

		$new_list = array();
		remove_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1 );
		if ( $list ) {
			foreach ( $list as $term_name ) {
				$term               = get_term_by( 'name', $term_name, $taxonomy );
				$term_id            = $term->term_id;
				$translated_term_id = apply_filters( 'wpml_object_id', $term_id, $taxonomy, true, substr( $lang, 0, 2 ) );
				if ( $translated_term_id ) {
					$translated_term = get_term( $translated_term_id, $taxonomy );
					$new_list[]      = $translated_term->name;
				}
			}
		}
		add_filter( 'get_term', array( $sitepress, 'get_term_adjust_id' ), 1 );

		return array_unique( array_filter( $new_list ) );
	}

	add_filter( 'ywcas_wpml_get_translated_terms_list_by_term_name', 'ywcas_wpml_get_translated_terms_list_by_term_name', 10, 3 );
}

if ( ! function_exists( 'ywcas_is_variation_purchasable' ) ) {
	/**
	 * Check if the variation can be purchasable
	 *
	 * @param WC_Product_Variation $product Variation.
	 *
	 * @return bool
	 */
	function ywcas_is_variation_purchasable( $product ) {
		$attributes  = $product->get_attributes();
		$is_complete = ! in_array( '', array_values( $attributes ), true );

		return $product->is_purchasable() && $is_complete;
	}
}

if ( ! function_exists( 'ywcas_has_disabled_options' ) ) {
	/**
	 * Check if there are some option that should be disabled
	 *
	 * @return bool
	 */
	function ywcas_has_disabled_options() {
		return ( ! yith_wcas_is_fresh_block_installation() && ! yith_wcas_user_switch_to_block() );
	}
}

if ( ! function_exists( 'ywcas_get_disabled_class' ) ) {
	/**
	 * Return the disable option class
	 *
	 * @return string
	 */
	function ywcas_get_disabled_class() {
		return ywcas_has_disabled_options() ? 'ywcas-disable' : '';
	}
}

if ( ! function_exists( 'ywcas_get_disable_field' ) ) {
	/**
	 * Return the disabled fields HTML code
	 *
	 * @return string
	 */
	function ywcas_get_disable_field() {
		$html = '';
		if ( ywcas_has_disabled_options() ) {
			// translators: Placeholders are HTML tags.
			$text = sprintf( _x( 'This option is available only in the new search form. %1$sUpdate the plugin now, it\'s easy >%2$s', 'Placeholders are HTML tags', 'yith-woocommerce-ajax-search' ), '<a href="#" class="ywcas-show-modal">', '</a>' );
			$html = '<div class="ywcas-disable-field-description">';

			$html .= ' <i class="yith-icon yith-icon-warning-triangle"></i>';
			$html .= '<div class="ywcas-disable-field-text">' . $text . '</div>';
			$html .= '</div>';
		}

		return $html;
	}
}

if ( ! function_exists( 'ywcas_get_shortcode_list' ) ) {
	/**
	 * Return the shortcode lists
	 *
	 * @return array
	 */
	function ywcas_get_shortcode_list() {
		return get_option( 'ywcas_shortcodes_list', ywcas()->settings->get_default_shortcode_args() );
	}
}

if ( ! function_exists( 'ywcas_is_elementor_editor' ) ) {
	/**
	 * Check if is an elementor editor
	 *
	 * @return bool
	 */
	function ywcas_is_elementor_editor() {
		if ( did_action( 'admin_action_elementor' ) ) {
			return Plugin::$instance->editor->is_edit_mode();
		}

		return is_admin() && isset( $_REQUEST['action'] ) && in_array(
				sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ),
				array(
					'elementor',
					'elementor_ajax',
				) ); //phpcs:ignore
	}
}

if ( ! function_exists( 'ywcas_show_elementor_preview' ) ) {
	/**
	 * Return the ajax search preview for Elementor
	 *
	 * @return void
	 */
	function ywcas_show_elementor_preview() {
		?>
        <div class="ywcas-elementor-input-field-wrapper" style="pointer-events: none;">
            <div class="ywcas-input-field">
                <input autocomplete="off"
                       placeholder="<?php echo esc_html__( 'Search products...', 'yith-woocommerce-ajax-search' ); ?>"
                       type="text" value=""/>
                <div class="endAdornment">
                    <div class="ywcas-submit-wrapper">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24"
                             class="ywcas-submit-icon" focusable="false">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
		<?php
	}
}

if ( ! function_exists( 'ywcas_get_sidebar_with_searches' ) ) {
	/**
	 * Returns an array of sidebars containing YITH_WCAS widgets
	 *
	 * @return array Array of sidebars.
	 */
	function ywcas_get_sidebar_with_searches() {
		$sidebars_widgets = wp_get_sidebars_widgets();

		if ( ! empty( $sidebars_widgets ) ) {
			foreach ( $sidebars_widgets as $sidebar => $widgets ) {
				$filtered_widgets = array_filter( $widgets, 'ywcas_is_search_widget' );

				if ( ! $filtered_widgets ) {
					unset( $sidebars_widgets[ $sidebar ] );
				}
			}
		}

		return $sidebars_widgets;
	}
}

if ( ! function_exists( 'ywcas_is_search_widget' ) ) {
	/**
	 * Returns true iw widget name matches structure expected for a filter widget
	 *
	 * @param string $name Widget name.
	 *
	 * @return bool Whether name matches or not.
	 */
	function ywcas_is_search_widget( $name ) {
		return preg_match( '/yith_woocommerce_ajax_search/', $name );
	}
}

if ( ! function_exists( 'ywcas_remove_duplicated_results' ) ) {
	/**
	 * Remove duplicated results
	 *
	 * @param array $results Results to filter.
	 *
	 * @return array
	 */
	function ywcas_remove_duplicated_results( $results ) {
		$ids     = array_column( $results, 'post_id' );
		$ids     = array_unique( $ids );
		$results = array_filter( $results, function ( $key, $value ) use ( $ids ) {
			return in_array( $value, array_keys( $ids ) );
		}, ARRAY_FILTER_USE_BOTH );

		return $results;
	}
}