<?php
/**
 * SKU for WooCommerce
 *
 * @version 1.6.1
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_SKU' ) ) :

class Alg_WC_SKU {

	/**
	 * Constructor.
	 *
	 * @version 1.2.2
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_sku_for_woocommerce_enabled', 'yes' ) ) {
			// New product
			if ( 'yes' === get_option( 'alg_sku_new_products_generate_enabled', 'yes' ) ) {
				add_action( 'wp_insert_post',                array( $this, 'set_new_product_sku_on_insert_post' ), PHP_INT_MAX, 3 );
				add_action( 'woocommerce_duplicate_product', array( $this, 'set_new_product_sku_on_duplicate' ),   PHP_INT_MAX, 2 );
			}
			// Regenerator tool
			add_action( 'alg_sku_for_woocommerce_before_regenerator_tool', array( $this, 'regenerate_tool_set_sku' ),     PHP_INT_MAX );
			add_action( 'alg_sku_for_woocommerce_after_regenerator_tool',  array( $this, 'regenerate_tool_preview_sku' ), PHP_INT_MAX );
			// Allow duplicates
			if ( 'yes' === get_option( 'alg_sku_for_woocommerce_allow_duplicates', 'no' ) ) {
				add_filter( 'wc_product_has_unique_sku', '__return_false', PHP_INT_MAX );
			}
			// Search by SKU
			if ( 'yes' === get_option( 'alg_sku_for_woocommerce_search_enabled', 'no' ) ) {
				if ( 'pre_get_posts' === get_option( 'alg_sku_for_woocommerce_search_algorithm', 'posts_search' ) ) {
					add_filter( 'pre_get_posts', array( $this, 'add_search_by_sku_to_frontend' ),              PHP_INT_MAX );
				} else { // 'posts_search'
					add_filter( 'posts_search',  array( $this, 'add_search_by_sku_to_frontend_posts_search' ), 9 );
				}
			}
			// SKU in emails
			if ( 'yes' === get_option( 'alg_sku_add_to_customer_emails', 'no' ) ) {
				add_filter( 'woocommerce_email_order_items_args', array( $this, 'add_sku_to_customer_emails' ), PHP_INT_MAX, 1 );
			}
			// SKU variations bulk action
			add_action( 'woocommerce_variable_product_bulk_edit_actions', array( $this, 'add_variations_bulk_action_option' ) );
			add_action( 'woocommerce_bulk_edit_variations', array( $this, 'regenerate_tool_set_variations_skus' ), 10, 4 );
		}
	}

	/**
	 * add_search_by_sku_to_frontend_posts_search.
	 *
	 * @version 1.2.2
	 * @since   1.2.2
	 */
	function add_search_by_sku_to_frontend_posts_search( $where ) {
		global $pagenow, $wpdb, $wp;
		if (
			( is_admin() && 'edit.php' != $pagenow ) ||
			! is_search() ||
			! isset( $wp->query_vars['s'] ) ||
			( isset( $wp->query_vars['post_type'] ) && 'product' != $wp->query_vars['post_type'] ) ||
			( isset( $wp->query_vars['post_type'] ) && is_array( $wp->query_vars['post_type'] ) && ! in_array( 'product', $wp->query_vars['post_type'] ) )
		) {
			return $where;
		}
		$search_ids = array();
		$terms      = explode( ',', $wp->query_vars['s'] );
		foreach ( $terms as $term ) {
			if ( is_admin() && is_numeric( $term ) ) {
				$search_ids[] = $term;
			}
			$variations_query       = "SELECT p.post_parent as post_id" .
				" FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value" .
				" LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent";
			$regular_products_query = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';";
			$sku_to_parent_id       = $wpdb->get_col( $wpdb->prepare( $variations_query,       wc_clean( $term ) ) );
			$sku_to_id              = $wpdb->get_col( $wpdb->prepare( $regular_products_query, wc_clean( $term ) ) );
			$search_ids             = array_merge( $search_ids, $sku_to_id, $sku_to_parent_id );
		}
		$search_ids = array_filter( array_map( 'absint', $search_ids ) );
		if ( sizeof( $search_ids ) > 0 ) {
			$where = str_replace( ')))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . "))))", $where );
		}
		return $where;
	}

	/**
	 * add_sku_to_customer_emails.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function add_sku_to_customer_emails( $args ) {
		$args['show_sku'] = true;
		return $args;
	}
	
	/**
	 * Adds "Generate SKUs for Variations" option to variations bulk action dropdown.
	 *
	 * @since  1.4.0
	 * @author David Grant
	 */
	public function add_variations_bulk_action_option() {
		#region add_variations_bulk_action_option
		?>		
		<optgroup label="<?php esc_attr_e( 'SKUs', 'sku-for-woocommerce' ); ?>">
			<option value="wpw_sku_generator_set_variations_skus"><?php esc_html_e( 'Generate SKUs for Variations', 'sku-for-woocommerce' ); ?></option>
		</optgroup>
		<?php
		#endregion add_variations_bulk_action_option
	}

	/**
	 * search_post_join.
	 *
	 * @version 1.5.0
	 * @since   1.2.0
	 */
	function search_post_join( $join = '' ) {
		global $wpdb, $wp_the_query;
		if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) ) {
			return $join;
		}
		$join .= "INNER JOIN {$wpdb->postmeta} AS alg_sku ON ({$wpdb->posts}.ID = alg_sku.post_id)";
		return $join;
	}

	/**
	 * search_post_where.
	 *
	 * @version 1.5.0
	 * @since   1.2.0
	 */
	function search_post_where( $where = '' ) {
		global $wpdb, $wp_the_query, $wp_version;
		if ( empty( $wp_the_query->query_vars['wc_query'] ) || empty( $wp_the_query->query_vars['s'] ) ) {
			return $where;
		}
		if ( version_compare( $wp_version, '4.8.3', '>=' ) ) {
			// see https://make.wordpress.org/core/2017/10/31/changed-behaviour-of-esc_sql-in-wordpress-4-8-3/
			$where = $wpdb->remove_placeholder_escape( $where );
		}
		$where = preg_replace( "/post_title LIKE ('%[^%]+%')/", "post_title LIKE $1) OR (alg_sku.meta_key = '_sku' AND CAST(alg_sku.meta_value AS CHAR) LIKE $1 ", $where );
		if ( version_compare( $wp_version, '4.8.3', '>=' ) ) {
			$where = $wpdb->prepare( $where );
		}
		return $where;
	}

	/*
	 * add_search_by_sku_to_frontend.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function add_search_by_sku_to_frontend( $query ) {
		if (
			isset( $query->query ) &&
			! $query->is_admin &&
			$query->is_search &&
			! empty( $query->query_vars['wc_query'] ) &&
			! empty( $query->query_vars['s'] ) &&
			'product' === $query->query_vars['post_type']
		) {
			add_filter( 'posts_join',  array( $this, 'search_post_join' ) );
			add_filter( 'posts_where', array( $this, 'search_post_where' ) );
		}
	}

	/**
	 * regenerate_tool_set_sku.
	 *
	 * @version 1.2.0
	 */
	function regenerate_tool_set_sku() {
		if ( ! isset( $_GET['alg_set_sku'] ) ) {
			return;
		}
		echo '<div id="message" class="updated inline"><p><strong>' . __( 'SKUs generated and set successfully!', 'sku-for-woocommerce' ) . '</strong></p></div>';
		$this->set_all_sku( false );
	}

	/**
	 * regenerate_tool_preview_sku.
	 *
	 * @version 1.2.5
	 */
	function regenerate_tool_preview_sku() {
		if ( ! isset( $_GET['alg_preview_sku'] ) ) {
			return;
		}
		$preview_html = '';
		$preview_html .= '<h5>' . __( 'SKU Preview', 'sku-for-woocommerce' ) . '</h5>';
		$preview_html .= '<table class="widefat striped">';
		$preview_html .= '<tr>' .
			'<th>' . '#'                                           . '</th>' .
			'<th>' . __( 'Product ID', 'sku-for-woocommerce' )     . '</th>' .
			'<th>' . __( 'Title', 'sku-for-woocommerce' )          . '</th>' .
			( 'yes' === get_option( 'alg_sku_categories_enabled', 'no' ) ? '<th>' . __( 'First Category', 'sku-for-woocommerce' ) . '</th>' : '' ) .
			( 'yes' === get_option( 'alg_sku_tags_enabled',       'no' ) ? '<th>' . __( 'First Tag', 'sku-for-woocommerce' )      . '</th>' : '' ) .
			'<th>' . __( 'Old SKU', 'sku-for-woocommerce' )        . '</th>' .
			'<th>' . __( 'New SKU', 'sku-for-woocommerce' )        . '</th>' .
		'</tr>';
		ob_start();
		$this->set_all_sku( true );
		$preview_html .= ob_get_clean();
		$preview_html .= '</table>';
		echo $preview_html;
	}
	
	/**
	 * Handle AJAX request to bulk set variations SKUs
	 *
	 * @since  1.4.0
	 * @author David Grant
	 */
	public function regenerate_tool_set_variations_skus( $bulk_action, $data, $product_id, $variations ) {
		if ( $bulk_action !== 'wpw_sku_generator_set_variations_skus' ) {
			return;
		}
		if ( ! $product_id ) {
			return;
		}
		$this->set_variations_skus_by_product_id( $product_id );
	}

	/**
	 * maybe_load_sequential_counter.
	 *
	 * @version 1.2.2
	 * @since   1.2.2
	 */
	function maybe_load_sequential_counter() {
		if ( 'sequential' === apply_filters( 'alg_wc_sku_generator_option', 'product_id', 'number_generation' ) ) {
			$this->sequential_counter = get_option( 'alg_sku_for_woocommerce_number_generation_sequential', 1 );
			if ( 'yes' === get_option( 'alg_sku_categories_enabled', 'no' ) && 'yes' === apply_filters( 'alg_wc_sku_generator_option', 'no', 'categories_sequential' ) ) {
				$products_terms = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
				if ( ! empty( $products_terms ) && ! is_wp_error( $products_terms ) ){
					foreach ( $products_terms as $products_term ) {
						$this->sequential_counter_cats[ $products_term->term_id ] = get_option( 'alg_sku_sequential_cat_' . $products_term->term_id, 1 );
					}
				}
			}
		}
	}

	/**
	 * maybe_update_sequential_counter.
	 *
	 * @version 1.2.2
	 * @since   1.2.2
	 */
	function maybe_update_sequential_counter( $is_preview ) {
		if ( 'sequential' === apply_filters( 'alg_wc_sku_generator_option', 'product_id', 'number_generation' ) && ! $is_preview ) {
			update_option( 'alg_sku_for_woocommerce_number_generation_sequential', $this->sequential_counter );
			if ( 'yes' === get_option( 'alg_sku_categories_enabled', 'no' ) && 'yes' === apply_filters( 'alg_wc_sku_generator_option', 'no', 'categories_sequential' ) ) {
				$products_terms = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
				if ( ! empty( $products_terms ) && ! is_wp_error( $products_terms ) ){
					foreach ( $products_terms as $products_term ) {
						update_option( 'alg_sku_sequential_cat_' . $products_term->term_id, $this->sequential_counter_cats[ $products_term->term_id ] );
					}
				}
			}
		}
	}

	/**
	 * set_all_sku.
	 *
	 * @version 1.2.2
	 */
	function set_all_sku( $is_preview ) {
		$this->maybe_load_sequential_counter();
		$this->product_counter = 1;
		$limit  = 1024;
		$offset = 0;
		while ( TRUE ) {
			$posts = new WP_Query( array(
				'posts_per_page' => $limit,
				'offset'         => $offset,
				'post_type'      => 'product',
				'post_status'    => 'any',
				'order'          => 'ASC',
				'orderby'        => 'date',
				'fields'         => 'ids',
			) );
			if ( ! $posts->have_posts() ) {
				break;
			}
			foreach ( $posts->posts as $post_id ) {
				$this->set_sku_with_variable( $post_id, $is_preview );
			}
			$offset += $limit;
		}
		$this->maybe_update_sequential_counter( $is_preview );
	}
	
	/**
	 * Set Variations SKUs by product ID
	 *
	 * @since  1.4.0
	 * @author WP Wham
	 */
	public function set_variations_skus_by_product_id( $product_id, $is_preview = false ) {
		$this->maybe_load_sequential_counter();
		// semi-silly solution to force us to only append the suffix, don't reparse (and thereby duplicate) the rest of it
		add_filter( 'option_alg_sku_for_woocommerce_template', array( $this, 'set_variations_skus_by_product_id_filter' ) );
		$this->set_sku_with_variable( $product_id, $is_preview, false );
		$this->maybe_update_sequential_counter( $is_preview );
	}
	
	/**
	 * set_variations_skus_by_product_id_filter
	 *
	 * @version 1.6.1
	 * @since   1.4.0
	 * @author  WP Wham
	 */
	public function set_variations_skus_by_product_id_filter( $template ) {
		$variation_handling = get_option( 'alg_sku_for_woocommerce_variations_handling', 'as_variable' );
		if ( in_array( $variation_handling, array( 'as_variable', 'as_variable_with_suffix' ) ) ) {
			$template = str_ireplace( 
				array( '{category_prefix}', '{category_suffix}', '{category_slug}', '{category_name}', '{tag_prefix}', '{tag_suffix}', '{tag_slug}', '{tag_name}', '{prefix}', '{suffix}' ), // remove everything except {variation_suffix}, {sku_number}
				'',
				$template
			);
		}
		return $template;
	}

	/**
	 * set_new_product_sku_on_duplicate.
	 *
	 * @version 1.1.3
	 * @since   1.1.3
	 */
	function set_new_product_sku_on_duplicate( $post_ID, $post ) {
		$this->set_new_product_sku( $post_ID );
	}

	/**
	 * set_new_product_sku_on_insert_post.
	 *
	 * @version 1.2.2
	 * @since   1.1.3
	 */
	function set_new_product_sku_on_insert_post( $post_ID, $post, $update ) {
		if ( 'product' != $post->post_type ) {
			return;
		}
		$do_generate_only_on_first_publish = ( 'yes' === get_option( 'alg_sku_new_products_generate_only_on_publish', 'yes' ) );
		if (
			( false === $update && ! $do_generate_only_on_first_publish ) ||
			( $do_generate_only_on_first_publish && 'publish' === $post->post_status && '' == get_post_meta( $post_ID, '_sku', true ) )
		) {
			$this->set_new_product_sku( $post_ID );
		}
	}

	/**
	 * set_new_product_sku.
	 *
	 * @version 1.2.2
	 */
	function set_new_product_sku( $post_ID ) {
		$this->maybe_load_sequential_counter();
		$this->set_sku_with_variable( $post_ID, false );
		$this->maybe_update_sequential_counter( false );
	}

	/**
	 * get_available_variations.
	 *
	 * @version 1.2.0
	 * @since   1.1.1
	 */
	function get_all_variations( $_product ) {
		$all_variations = array();
		foreach ( $_product->get_children() as $child_id ) {
			$variation = ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ) ? $_product->get_child( $child_id ) : wc_get_product( $child_id );
			$all_variations[] = $_product->get_available_variation( $variation );
		}
		return $all_variations;
	}

	/**
	 * get_and_icrease_sequential_counter.
	 *
	 * @version 1.2.2
	 * @since   1.2.2
	 */
	function get_and_icrease_sequential_counter( $product_id ) {
		if ( 'yes' === get_option( 'alg_sku_categories_enabled', 'no' ) && 'yes' === apply_filters( 'alg_wc_sku_generator_option', 'no', 'categories_sequential' ) ) {
			$product_terms = get_the_terms( $product_id, 'product_cat' );
			if ( is_array( $product_terms ) ) {
				foreach ( $product_terms as $term ) {
					$sku_number = $this->sequential_counter_cats[ $term->term_id ];
					$this->sequential_counter_cats[ $term->term_id ]++;
					return $sku_number;
				}
			}
		}
		$sku_number = $this->sequential_counter;
		$this->sequential_counter++;
		return $sku_number;
	}

	/**
	 * set_sku_with_variable.
	 *
	 * @version 1.6.1
	 * @author  Algoritmika Ltd.
	 * @author  WP Wham
	 */
	public function set_sku_with_variable( $product_id, $is_preview, $overwrite_parent_sku = true ) {
		
		$empty_skus_only = get_option( 'alg_sku_generate_only_for_empty_sku', 'no' ) === 'yes';
		
		$product    = wc_get_product( $product_id );
		$sku_number = $product->get_sku();
		
		if ( $overwrite_parent_sku || $sku_number === '' ) {
			if ( 'sequential' === apply_filters( 'alg_wc_sku_generator_option', 'product_id', 'number_generation' ) ) {
				$sku_number = $this->get_and_icrease_sequential_counter( $product_id );
			} elseif ( 'hash_crc32' === apply_filters( 'alg_wc_sku_generator_option', 'product_id', 'number_generation' ) ) {
				$sku_number = sprintf( "%u", crc32( $product_id ) );
			} else { // if 'product_id'
				$sku_number = $product_id;
			}
			$this->set_sku( $product_id, $sku_number, '', $is_preview, $product_id, $product );
		}
		
		// Handling variable products
		$variation_handling = get_option( 'alg_sku_for_woocommerce_variations_handling', 'as_variable' );
		if ( $product->is_type( 'variable' ) ) {
			$variations = $this->get_all_variations( $product );
			
			// SKU same as parent's product
			if ( 'as_variable' === $variation_handling ) {
				foreach( $variations as $variation ) {
					$this->set_sku( $variation['variation_id'], $sku_number, '', $is_preview, $product_id, new WC_Product_Variation( $variation['variation_id'] ) );
				}
				
			// Generate different SKU for each variation
			} elseif ( 'as_variation' === $variation_handling ) {
				
				foreach( $variations as $variation ) {
					// from 'product_id'
					$sku_number = $variation['variation_id'];
					$this->set_sku( $variation['variation_id'], $sku_number, '', $is_preview, $product_id, new WC_Product_Variation( $variation['variation_id'] ) );
				}
				
			}
			
		}
	}

	/**
	 * set_sku.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	public function set_sku( $product_id, $sku_number, $variation_suffix, $is_preview, $parent_product_id, $_product ) {
		
		// Do generate new SKU
		// $_product->get_sku() is not reliable because get_sku() on a variation without a SKU will actually return
		// the SKU of the parent instead.  So we have to check the postmeta directly:
		$old_sku = get_post_meta( $product_id, '_sku', true );
		$do_generate_new_sku = ( 'no' === get_option( 'alg_sku_generate_only_for_empty_sku', 'no' ) || '' === $old_sku );
		
		// Categories
		$category_prefix = '';
		$category_suffix = '';
		$category_slug   = '';
		$product_cat     = '';
		if ( get_option( 'alg_sku_categories_enabled', 'no' ) === 'yes' ) {
			$term = false;
			$product_terms = get_the_terms( $parent_product_id, 'product_cat' );
			if ( is_array( $product_terms ) ) {
				foreach ( $product_terms as $product_term ) {
					$term = $product_term;
					break;
				}
			}
			if ( $term instanceof WP_Term ) {
				$product_cat     = esc_html( $term->name );
				$category_slug   = esc_html( $term->slug );
				$category_prefix = apply_filters( 'alg_wc_sku_generator_option', '', 'category_prefix', array( 'term_id' => $term->term_id ) );
				$category_suffix = get_option( 'alg_sku_suffix_cat_' . $term->term_id, '' );
			}
		}
		
		// Tags
		$tag_prefix  = '';
		$tag_suffix  = '';
		$tag_slug    = '';
		$product_tag = '';
		if ( 'yes' === get_option( 'alg_sku_tags_enabled', 'no' ) ) {
			$product_terms = get_the_terms( $parent_product_id, 'product_tag' );
			if ( is_array( $product_terms ) ) {
				foreach ( $product_terms as $term ) {
					$product_tag = esc_html( $term->name );
					$tag_slug    = esc_html( $term->slug );
					$tag_prefix  = apply_filters( 'alg_wc_sku_generator_option', '', 'tag_prefix', array( 'term_id' => $term->term_id ) );
					$tag_suffix  = get_option( 'alg_sku_suffix_tag_' . $term->term_id, '' );
					break;
				}
			}
		}
		
		// Format SKU
		$format_template = get_option( 'alg_sku_for_woocommerce_template',
			'{category_prefix}{tag_prefix}{prefix}{sku_number}{suffix}{tag_suffix}{category_suffix}{variation_suffix}' );
		$replace_values = array(
			'{category_prefix}'  => $category_prefix,
			'{tag_prefix}'       => $tag_prefix,
			'{prefix}'           => get_option( 'alg_sku_for_woocommerce_prefix', '' ),
			'{sku_number}'       => sprintf( '%0' . get_option( 'alg_sku_for_woocommerce_minimum_number_length', 0 ) . 's', $sku_number ),
			'{suffix}'           => get_option( 'alg_sku_for_woocommerce_suffix', '' ),
			'{tag_suffix}'       => $tag_suffix,
			'{category_suffix}'  => $category_suffix,
			'{variation_suffix}' => $variation_suffix,
			'{category_slug}'    => $category_slug,
			'{category_name}'    => $product_cat,
			'{tag_slug}'         => $tag_slug,
			'{tag_name}'         => $product_tag,
			'{CATEGORY_PREFIX}'  => strtoupper( $category_prefix ),
			'{TAG_PREFIX}'       => strtoupper( $tag_prefix ),
			'{PREFIX}'           => strtoupper( get_option( 'alg_sku_for_woocommerce_prefix', '' ) ),
			'{SKU_NUMBER}'       => strtoupper( sprintf( '%0' . get_option( 'alg_sku_for_woocommerce_minimum_number_length', 0 ) . 's', $sku_number ) ),
			'{SUFFIX}'           => strtoupper( get_option( 'alg_sku_for_woocommerce_suffix', '' ) ),
			'{TAG_SUFFIX}'       => strtoupper( $tag_suffix ),
			'{CATEGORY_SUFFIX}'  => strtoupper( $category_suffix ),
			'{VARIATION_SUFFIX}' => strtoupper( $variation_suffix ),
			'{CATEGORY_SLUG}'    => strtoupper( $category_slug ),
			'{CATEGORY_NAME}'    => strtoupper( $product_cat ),
			'{TAG_SLUG}'         => strtoupper( $tag_slug ),
			'{TAG_NAME}'         => strtoupper( $product_tag ),
		);
		
		// The filter
		$replace_values = apply_filters(
			'wpwham_sku_sku_template_variables',
			$replace_values, $product_id, $sku_number, $variation_suffix, $is_preview, $parent_product_id, $_product
		);
		
		// Put it all together
		$the_sku = ( $do_generate_new_sku ) ? str_replace( array_keys( $replace_values ), array_values( $replace_values ), $format_template ) : $old_sku;
		
		// Preview or set
		if ( $is_preview ) {
			echo '<tr>' .
				'<td>' . $this->product_counter++     . '</td>' .
				'<td>' . $product_id                  . '</td>' .
				'<td>' . get_the_title( $product_id ) . '</td>' .
				( 'yes' === get_option( 'alg_sku_categories_enabled', 'no' ) ? '<td>' . $product_cat . '</td>' : '' ) .
				( 'yes' === get_option( 'alg_sku_tags_enabled', 'no' )       ? '<td>' . $product_tag . '</td>' : '' ) .
				'<td>' . $old_sku                     . '</td>' .
				'<td>' . $the_sku                     . '</td>' .
			'</tr>';
		} elseif ( $do_generate_new_sku ) {
			update_post_meta( $product_id, '_' . 'sku', $the_sku );
		}
	}

}

endif;

return new Alg_WC_SKU();
